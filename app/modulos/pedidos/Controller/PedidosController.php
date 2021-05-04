<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class PedidosController extends Controllers
{
    private $session,$conectar,$adapter,$layout,$website;
    
    public function __construct()
    {
        $this->session = new Session();
        $this->session->init();
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        $this->cliente= new Models\Sis00300Model($this->adapter);
        $this->layout = new dti_layout($this->website);
        $this->layout_proformas = new dti_layout_pedidos($this->website);
       
    }
    
    public function exec()
    {
        $this->index();
    }

    public function index(){

        if (empty($_GET['pedido'])) $this->redirect("cc","index");
        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();

         $clientesModel= new \Models\ClientesModel($this->adapter);
         $respClienteModel=$clientesModel->ListarClientesId($regcc10000->cc00000id);
         $regClienteModel=$respClienteModel->fetch_object();
         $cliente=array(
             'id'=>$regClienteModel->id,
             'ruc'=>$regClienteModel->ruc,
             'cliente'=>$regClienteModel->cliente,
             'razonsocial'=>$regClienteModel->razonsocial,
             'descuento'=>$regClienteModel->descuento,
             'correo'=>$regClienteModel->correo
         );
 
         //DATOS SUCURSAL
         $sucursalesModel= new \Models\SucursalesModel($this->adapter);
         $respSucursalModel=$sucursalesModel->ListarSucursalesId($regcc10000->cc00002id);
         $regSucursalModel=$respSucursalModel->fetch_object();
         $sucursal=array(
             'codigodireccion'=>$regSucursalModel->codigodireccion,
             'telefono'=>$regSucursalModel->telefono,
             'ciudad'=>$regSucursalModel->ciudad,
             'provincia'=>$regSucursalModel->provincia,
             'direccion'=>$regSucursalModel->direccion
         );

        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_proformas->lisarProductos($cliente,$sucursal).' '.$this->layout_proformas->asideCarrito($regcc10000->descuento_porce,$_GET['pedido']),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Productos"
        ));

    }

    public function listarProductos(){ 
        
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

         

        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();
        $Cc00000 = new Entidades\Cc00000($this->adapter);
        $respCc00000=$Cc00000->getMultiObj('id',$regcc10000->cc00000id);
        $regCc00000=$respCc00000->fetch_object();
        $this->session->add('categoria',$regCc00000->categoria);

        
            //codigo de listar 
            $conf= new \Models\ProformasModel($this->adapter);
            $rspta=$conf->listar($regCc00000->nivelprecio);
            $data= Array();
             while ($reg=$rspta->fetch_object()){

                //PORCENTAJE PRODUCTO
           
                $precioDescuento='';
                $descuentoClienteTotal=0;
                $descuentoCliente=0;
                if($regCc00000->descuento>0){
                    $precioDescuento='<strike>'.$reg->precio.'</strike><br>';
    
                    $descuentoClienteTotal=$reg->precio-(($reg->precio*$regCc00000->descuento)/100);
                    $descuentoClienteTotal=number_format((float)$descuentoClienteTotal, 2, '.', '');
                    $descuentoCliente=($reg->precio*$regCc00000->descuento)/100;
                    $descuentoCliente=number_format((float)$descuentoCliente, 2, '.', '');
    
    
                    $precioDescuento.='<b>'.$descuentoClienteTotal.'</b>';
                    
                }else{
                    $precioDescuento='<b>'.$reg->precio.'</b>';
                }

             $stockBodega=0;
             $reg->descripcion=str_replace('"',"",$reg->descripcion);
                 $data[]=array(
             "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('."'".$reg->codigo."'".',\''.$reg->precio.'\',\''.$reg->descripcion.'\',\''.strtoupper($this->session->get('bodUsuario')).'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-plus"></span></button> '.
             ' <button data-target="#ajax" data-toggle="modal" class="btn btn-info" onclick="consultarStock('."'".$reg->codigo."'".','."'".strtoupper($this->session->get('bodUsuario'))."'".',\''.$reg->precio.'\',\''.$reg->descripcion.'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-search"></span></button> ',
             "1"=>$reg->codigo,
             "2"=>$reg->descripcion,
             "3"=>$reg->codoriginal1,
             //"3"=>$reg->descripcioncorta,
             "4"=>'$.'.$precioDescuento,
             "5"=>"",
             //"6"=>$reg->linea,
             "6"=>$reg->sublinea,
             "7"=>$reg->marcavehiculo,
             "8"=>$reg->modelo,
             "9"=>$reg->marcaproducto,
             //"11"=>$reg->codoriginal1,
             "10"=>$reg->codanterior);
             }
             $results = array(
                 "sEcho"=>1, 
                 "iTotalRecords"=>count($data), 
                 "iTotalDisplayRecords"=>count($data), 
                 "aaData"=>$data);
             echo json_encode($results);
    }

    public function agregarItemCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter); 
        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();
        $id_cabecera=$regcc10000->id;
        $id_producto=isset($param['codigo'])? $conf->limpiarCadenaString($param["codigo"]):"";
        $descripcion_producto=isset($param['descripcion'])? $conf->limpiarCadenaString($param["descripcion"]):"";
        $costo_producto=isset($param['costo'])? $conf->limpiarCadenaString($param["costo"]):"";
        $bodega_producto=isset($param['bodega'])? $conf->limpiarCadenaString($param["bodega"]):"";
        $descuentoCliente=isset($param['descuentoCliente'])? $conf->limpiarCadenaString($param["descuentoCliente"]):"";
        $stock_producto=isset($param[''])? $conf->limpiarCadenaString($param[""]):"";
        if($bodega_producto=='') {
            $bodega_producto=strtoupper($this->session->get('bodUsuario'));
         }

         $Inv00000 = new Entidades\Inv00000($this->adapter);
         $repInv00000=$Inv00000->getMultiObj('codigo',$id_producto);
         $regInv00000=$repInv00000->fetch_object();
         if(!isset($regInv00000->marcaproducto)){
           $marcaproducto='';
         }else{
            $marcaproducto=$regInv00000->marcaproducto;
         }

         $data = array(
             'codigo'=>trim($id_producto),
             'bodega' => strtoupper($bodega_producto)
         );
         $data = http_build_query($data);
                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproductobodega");
                 curl_setopt($ch, CURLOPT_POST, 1);
                 curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 $server_output = curl_exec($ch);
                 curl_close ($ch);

                 if(trim($id_producto)=='VTAS-SERV-LOG'){
                    $stock_producto=100;
                }else{
                    $stock_producto=intval($server_output);
                }

                
        $cantidad_producto=1;
        $precio_producto=isset($param['precio'])? $conf->limpiarCadenaString($param["precio"]):"";
        $descuento_producto=0;
        $subtotal_producto=$cantidad_producto*$precio_producto;
        $subtotal_producto=number_format($subtotal_producto, 2, '.', '');
        $Cc10010 = new Entidades\Cc10010($this->adapter);
        $Cc10010->setCc10000id($id_cabecera);
        $Cc10010->setInv00000codigo($id_producto);
        $Cc10010->setDescripcion($descripcion_producto);
        $Cc10010->setCosto( $costo_producto);
        $Cc10010->setBodega($bodega_producto);
        $Cc10010->setCantidad($cantidad_producto);
        $Cc10010->setPrecio($precio_producto);
        $Cc10010->setDescuento($descuento_producto);
        $Cc10010->setSubtotal($subtotal_producto);
        $Cc10010->setStock_producto($stock_producto);
        $Cc10010->setMarca_producto($marcaproducto);
        $Cc10010->setGuia(0);
        $Cc10010->setCantidad_guia($cantidad_producto);
        $Cc10010->setDescuento_cliente($descuentoCliente);
        $resp=$Cc10010->save_id();
        echo $resp;
     }


     public function eliminarItemCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Cc10010 = new Entidades\Cc10010($this->adapter);
        $id=isset($param['id_base'])? $Cc10010->limpiarCadenaString($param["id_base"]):"";
        $rspta=$Cc10010->deleteMulti('id',$id);
        echo $rspta ? "1" : "0";
    }

    public function generarCodigo(){
        $secuencial = new Entidades\Sis00300($this->adapter);
        $dtsecuencial = $secuencial->getMulti('usuario', $this->session->get('usuario'));
        $detalle = new \Models\Sis50300Model($this->adapter);
        $dtdetalle = $detalle->getNumMax($dtsecuencial['id']);
        if (isset($dtdetalle['total'])) {
            if (strlen($dtdetalle['total'])>0) {
                $numero = '00000';
                switch (strlen($dtdetalle['total'])) {
                    case 1:
                        $numero = '0000'.$dtdetalle['total'];
                        break;
                    case 2:
                        $numero = '000'.$dtdetalle['total'];
                        break;
                    case 3:
                        $numero = '00'.$dtdetalle['total'];
                        break;
                    case 4:
                        $numero = '0'.$dtdetalle['total'];
                        break;
                    case 5:
                        $numero = $dtdetalle['total'];
                        break;
                }
                echo  $dtsecuencial['id'].'.'.$numero;
            } else {
                echo  $dtsecuencial['id'].'.00001';
            }
        } else {
            echo  $dtsecuencial['id'].'.00001';
        }
    }

    public function listarProductosBusqueda($param=array()){


        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();

        $Cc00000 = new Entidades\Cc00000($this->adapter);
        $respCc00000=$Cc00000->getMultiObj('id',$regcc10000->cc00000id);
        $regCc00000=$respCc00000->fetch_object();

            //codigo de listar 
            $conf= new \Models\ProformasModel($this->adapter);
            $param['busqueda']=str_replace(" ","%",$param['busqueda']);
            $rspta=$conf->ListarBusqueda($regCc00000->nivelprecio,$param['busqueda']);

            $data= Array();
            $reg="";
             while ($reg=$rspta->fetch_object()){
            $reg->descripcion=str_replace('"',"",$reg->descripcion);

             //STOCK BODEGA 
             $datos = array(
                'codigo'=>trim($reg->codigo),
                'bodega' => strtoupper($this->session->get('bodUsuario'))
            );
            $datos = http_build_query($datos);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproductobodega");
                    curl_setopt($ch, CURLOPT_POST, 1);

                    curl_setopt($ch, CURLOPT_POSTFIELDS,$datos);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    curl_close ($ch);

                    if(trim($reg->codigo)=='VTAS-SERV-LOG'){
                        $stock_producto=100;
                    }else{
                        $stock_producto=intval($server_output);
                    }

                    
                    
          //restar stock de pedido00
          if($stock_producto!=0 || $stock_producto!='0'){
            $Cc10010  = new Entidades\Cc10010 ($this->adapter);
            $resp=$Cc10010->getMultiObj('inv00000codigo',$reg->codigo);
           
            while($regCc10010=$resp->fetch_object()){
                if($regCc10010->bodega==$this->session->get('bodUsuario')){
                    $stock_producto=$stock_producto-$regCc10010->cantidad;
                }
            }
        }

            if($stock_producto<0){
                $stock_producto=0; 
            }
        

            $Cc11000  = new Entidades\Cc11000 ($this->adapter);
            $Cc11010  = new Entidades\Cc11010 ($this->adapter);
            $Sis00300  = new Entidades\Sis00300 ($this->adapter);

           
            $respCc11000=$Cc11000->getMultiObj('aprobadoCobranza','0');
            while($regCc11000=$respCc11000->fetch_object()){

                $respSis00300=$Sis00300->getMultiObj('usuario',$regCc11000->usuario)->fetch_object();
                if($this->session->get('bodUsuario')==$respSis00300->bodega){
                    $respcc11010=$Cc11010->getMultiObj('cc11000id',$regCc11000->id,'inv00000codigo',$reg->codigo)->fetch_object();
                    if(!empty($respcc11010)){
                        $stock_producto=$stock_producto-$respcc11010->cantidad;
                    }
                        if($stock_producto<0){
                            $stock_producto=0; 
                        }
                } 
            }
        ////######################
           
           $precioDescuento='';
           $descuentoClienteTotal=0;
           $descuentoCliente=0;
           if($regCc00000->descuento>0){
               $precioDescuento='<strike>'.$reg->precio.'</strike><br>';

               $descuentoClienteTotal=$reg->precio-(($reg->precio*$regCc00000->descuento)/100);
               $descuentoClienteTotal=number_format((float)$descuentoClienteTotal, 2, '.', '');
               $descuentoCliente=($reg->precio*$regCc00000->descuento)/100;
               $descuentoCliente=number_format((float)$descuentoCliente, 2, '.', '');


               $precioDescuento.='<b>'.$descuentoClienteTotal.'</b>';
               
           }else{
               $precioDescuento='<b>'.$reg->precio.'</b>';
           }


            $data[]=array(
             "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('."'".$reg->codigo."'".',\''.$reg->precio.'\',\''.$reg->descripcion.'\',\''.strtoupper($this->session->get('bodUsuario')).'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-plus"></span></button> '.
             ' <button data-target="#ajax" data-toggle="modal" class="btn btn-info" onclick="consultarStock('."'".$reg->codigo."'".','."'".strtoupper($this->session->get('bodUsuario'))."'".',\''.$reg->precio.'\',\''.$reg->descripcion.'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-search"></span></button> ',
             "1"=>$reg->codigo,
             "2"=>$reg->descripcion,
             "3"=>$reg->descripcioncorta,
             //"4"=>'<h4>$'.$reg->precio.'</h4><br><h4>'.$this->session->get('bodUsuario').":".$stock_producto.'</h4>',
             "4"=>'$.'.$precioDescuento,
             "5"=>'<b>'.$stock_producto.'</b>',
             "6"=>$reg->linea,
             "7"=>$reg->sublinea,
             "8"=>$reg->marcavehiculo,
             "9"=>$reg->modelo,
             "10"=>$reg->marcaproducto,
             "11"=>$reg->codoriginal1,
             "12"=>$reg->codanterior);
            }
             $results = array(
                 "sEcho"=>1, 
                 "iTotalRecords"=>count($data), 
                 "iTotalDisplayRecords"=>count($data), 
                 "aaData"=>$data);
             echo json_encode($results);
        
       
    }

    public function modificarDetalleCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        if (empty($_GET['pedido'])) $this->redirect("cc","index");
        $cc10010 = new Entidades\Cc10010($this->adapter);
        $id=isset($param['id_base'])?  $cc10010->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])?  $cc10010->limpiarCadenaString($param["value"]):"";
        $rspta=$cc10010->updateMultiColum('descripcion',$value,'id',$id);
        echo $rspta ? "1" : "0";
    }

    public function modificarCodigoCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $cc10010 = new Entidades\Cc10010($this->adapter);
        $id=isset($param['id_base'])?  $cc10010->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])?  $cc10010->limpiarCadenaString($param["value"]):"";
        $rspta= $cc10010->updateMultiColum('inv00000codigo',$value,'id',$id);
        echo $rspta ? "1" : "0";
    }

    public function modificarCantidadCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $cc10010 = new Entidades\Cc10010($this->adapter);
        $id=isset($param['id_base'])?  $cc10010->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])?  $cc10010->limpiarCadenaString($param["value"]):"";
        $rspta= $cc10010->updateMultiColum('cantidad',$value,'id',$id);
        echo $rspta ? "1" : "0";
    }

    public function modificarPrecioCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $cc10010 = new Entidades\Cc10010($this->adapter);
        $id=isset($param['id_base'])?  $cc10010->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])?  $cc10010->limpiarCadenaString($param["value"]):"";
        $rspta= $cc10010->updateMultiColum('precio',$value,'id',$id);
        echo $rspta ? "1" : "0";
    }

    public function modificarDescuentoCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $cc10010 = new Entidades\Cc10010($this->adapter);
        $id=isset($param['id_base'])?  $cc10010->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])?  $cc10010->limpiarCadenaString($param["value"]):"";
        $rspta= $cc10010->updateMultiColum('descuento',$value,'id',$id);
        echo $rspta ? "1" : "0";
    }


    public function llenarCarritoTemporal(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();
        $cc10010 = new Entidades\Cc10010($this->adapter);
        $respcc10010=$cc10010->getMultiObj('cc10000id',$regcc10000->id);
        $html='';
        $cont=0;
        $detalles=0;
        $conf= new \Models\ProformasModel($this->adapter);

    
        $respcc10000Costo=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000Costo=$respcc10000Costo->fetch_object();
        $Cc00000Costo = new Entidades\Cc00000($this->adapter);
        $respCc00000Costo=$Cc00000Costo->getMultiObj('id',$regcc10000Costo->cc00000id);
        $regCc00000Costo=$respCc00000Costo->fetch_object();
      
        while ($reg=$respcc10010->fetch_object()){
            $rsptaPrecio=$conf->ListarBusquedaPrecioProducto($regCc00000Costo->nivelprecio,$reg->inv00000codigo);
            $regPrecio=$rsptaPrecio->fetch_object();

            $descuentoCliente=0;

            if(empty($regPrecio)){
                $precioProd=0;
            }else{

                $descuentoCliente=0;

                if($regCc00000Costo->descuento>0){
                   
                    
                    $descuentoCliente=($regPrecio->precio*$regCc00000Costo->descuento)/100;
                    $descuentoCliente=number_format((float)$descuentoCliente, 2, '.', '');
    
                    $precioProd=$regPrecio->precio;
                    
                }else{
                    $precioProd=$regPrecio->precio;
                }
                
            }

            $html.='<tr  class="filas" id="fila'.$cont.'">';

            $guiacc10010=$cc10010->getMultiObj('id',$reg->id);
            $regguiacc10010=$guiacc10010->fetch_object();

            if($regguiacc10010->guia){
                $html.='<td ></td>';
            }else{
            $html.='<td ><button type="button" class="btn btn-danger" onclick="eliminarDetalle('.$cont.','.$reg->id.')">X</button></td>';
                }
            $html.='<td ><input type="hidden" name="idarticulo[]" value="'.$reg->inv00000codigo.'">'.$reg->inv00000codigo.'</td>';
            $html.='<td ><textarea name="descripcion[]" id="descripcion[]" cols="15" rows="4" class="form-control" onchange="modificarDetalleCarrito(this.value,'.$reg->id.')" value="'.$reg->descripcion.'">'.$reg->descripcion.'</textarea></td>';
            $html.='<td class="txtzize"><input class="txtzize" onchange="modificarCantidadCarrito(this.value,'.$reg->id.')" type="number" name="cantidad[]" id="cantidad[]" value="'.$reg->cantidad.'"><input type="hidden" name="nomBodega[]" value="'.$reg->bodega.'">'.$reg->bodega.'</td>';
            $html.='<td class="txtprecio"><input class="txtprecio" type="number" step="0.01" min="'.$precioProd.'" onchange="modificarPrecioCarrito(this.value,'.$reg->id.')" name="precio[]" id="precio[]" value="'.$reg->precio.'"></td>';
            $html.='<td class="txtzize"><input class="txtzize" type="number" onchange="modificarDescuentoCarrito(this.value,'.$reg->id.')" step="0.01" min="0"  name="descuento[]" id="descuento[]" readonly value="'.$reg->descuento.'"></td>';
            $html.='<td class="txtzize"><input class="txtzize" type="text" readonly step="0.01" min="0" name="descuentoCliente[]" id="descuentoCliente[]" value="'.$descuentoCliente.'"></td>';
            $html.='<td ><span name="subtotal" id="subtotal'.$cont.'">'.number_format($reg->subtotal,2,'.','').'</span></td>';
            $html.='</tr>'; 
            $cont++;  
            $detalles=$detalles+1;        
        }
        $resp=array(
            'cont'=>$cont,
            'detalles'=>$detalles,
            'html'=>$html
        );
        echo json_encode($resp);
    }

    public function guardarTotales($param=array())
    {
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        $this->modificarDescuentoBse($param['aplica'],$param['daplicar']);

        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();
        $cc10010 = new Entidades\Cc10010($this->adapter);
        $respcc10010=$cc10010->getMultiObj('cc10000id',$regcc10000->id);
        $descuento=0;
        $subtotalF=0;
        while ($reg=$respcc10010->fetch_object()){
            $subtotalF=$subtotalF+$reg->subtotal;
            $descuento=$descuento+((float)$reg->descuento+(float)$reg->descuento_cliente);
        }

        $totalFac=((float)$subtotalF-(float)$descuento)+(((float)$subtotalF-(float)$descuento)*0.12);

        $cc10000->updateMultiColum('subtotal',$param['subtotal'],'documento',$_GET['pedido']);
        $cc10000->updateMultiColum('iva',number_format(((float)$subtotalF-(float)$descuento)*0.12,'2','.',''),'documento',$_GET['pedido']);
        $cc10000->updateMultiColum('total',$totalFac,'documento',$_GET['pedido']);
        $rsptaAct = $cc10000->updateMultiColum('descuento',$descuento,'documento',$_GET['pedido']);
        echo $rsptaAct ? "1" : "0";
    }

    public function modificarDescuentoPorce($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf=new Entidades\Cc10000($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";

        $usu=new Entidades\Sis00300($this->adapter);
        $respUsu=$usu->getMultiObj('usuario',$this->session->get('usuario'))->fetch_object();

        $descuento=0.00;
        if((float)$value<=(float)$respUsu->descuento){
            $descuento=(float)$value;
        }else{
            $descuento=(float)$respUsu->descuento;
        }

        $resp=$conf->updateMultiColum('descuento_porce',$descuento,'documento',$id);
        echo $resp ? "1" : "0";
    }


    public function modificarDescuentoBse($aplicaDescuento,$daplicar){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();
        $Cc10010 = new Entidades\Cc10010($this->adapter);
        $respcc10010=$Cc10010->getMultiObj('cc10000id',$regcc10000->id);

      

        $descuentoResp=0.00;


        $conf= new \Models\CarritoModel($this->adapter);

        $subtotalCarrito=$conf->subtotalPedidoP($regcc10000->id)->fetch_object();
        $valsubtotalCarrito=0;

        
        if(!empty($subtotalCarrito)){
            $valsubtotalCarrito=$subtotalCarrito->subtotal;
        }

       
        if((trim($this->session->get('categoria')))=="TALLERES/PYMES"){
            $categoria="1";
        }

        if(trim($this->session->get('categoria'))=="CONSUMIDOR FINAL"){
            $categoria="2";
        }

        if(trim($this->session->get('categoria'))=="CORPORATIVO"){
            $categoria="3";
        }

        if(trim($this->session->get('categoria'))=="ASEGURADORAS"){
            $categoria="4";
        }

        if($categoria=="4"){

            $descuentoPedido=$conf->descuentoClientePedido($regcc10000->cc00000id)->fetch_object();
            $valDescuentoCarrito=0;
            if(!empty($descuentoPedido)){
                $valDescuentoCarrito=$descuentoPedido->descuento;
            }

        }else{
            $descuentoPedido=$conf->descuentoPedido($categoria,$valsubtotalCarrito)->fetch_object();
            $valDescuentoCarrito=0;
            if(!empty($descuentoPedido)){
                $valDescuentoCarrito=$descuentoPedido->descuento;
            }
        }

        if($aplicaDescuento=='true'){
            $valDescuentoCarrito=(float)0;
        }

        if($daplicar<=$valDescuentoCarrito && $daplicar>0){
            $valDescuentoCarrito=$daplicar;
        }


        $descuentoResp=(float)$valDescuentoCarrito;

       
        $cc10000->updateMultiColum('descuento_porce',$descuentoResp,'id',$regcc10000->id);

      
       
        while($regCc10010=$respcc10010->fetch_object()){
        
            // $TotalDesc=number_format(($descuentoResp)/100*$regCc10010->precio,2,'.','');
            // $TotalSubto=number_format($regCc10010->precio-(number_format(($descuentoResp)/100*$regCc10010->precio,2,'.','')),2,'.','')*$regCc10010->cantidad;

            $TotalDesc=(($regCc10010->precio*$regCc10010->cantidad)*$descuentoResp)/100;
            $TotalSubto=($regCc10010->precio*$regCc10010->cantidad);
         
            $resp=$Cc10010->updateMultiColum('descuento',$TotalDesc,'id',$regCc10010->id);
            $resp ? $return='1' : $return='0';
            $resp=$Cc10010->updateMultiColum('subtotal',$TotalSubto,'id',$regCc10010->id);
            $resp ? $return='1' : $return='0';
        }

        //echo $return;
    }

    ///########################---REVISAR ---############################
    public function revisar(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        //VARIABLE DEL PEDIDO
        $pedido=$_GET['pedido'];
        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$pedido);
        $regcc10000=$respcc10000->fetch_object();




        //DATOS CLIENTE
        $clientesModel= new \Models\ClientesModel($this->adapter);
        $respClienteModel=$clientesModel->ListarClientesId($regcc10000->cc00000id);
        $regClienteModel=$respClienteModel->fetch_object();
        $cliente=array(
            'id'=>$regClienteModel->id,
            'ruc'=>$regClienteModel->ruc,
            'cliente'=>$regClienteModel->cliente,
            'razonsocial'=>$regClienteModel->razonsocial
        );

        //DATOS SUCURSAL
        $sucursalesModel= new \Models\SucursalesModel($this->adapter);
        $respSucursalModel=$sucursalesModel->ListarSucursalesId($regcc10000->cc00002id);
        $regSucursalModel=$respSucursalModel->fetch_object();
        $sucursal=array(
            'codigodireccion'=>$regSucursalModel->codigodireccion,
            'telefono'=>$regSucursalModel->telefono,
            'ciudad'=>$regSucursalModel->ciudad,
            'provincia'=>$regSucursalModel->provincia,
            'direccion'=>$regSucursalModel->direccion
        );
        
        //DATOS VALORES
        $conf =new \Entidades\Cc20010($this->adapter);
        $resp=$conf->getSumMulti('valor','documento',$pedido);

        // $carritoModel= new \Models\CarritoModel($this->adapter);
        // $respCarritoModel=$carritoModel->ListarCabeceraCarritoId($this->session->get('idCarritoTemporal'));
        // $regCarritoModel=$respCarritoModel->fetch_object();
        
        $valores=array(
            'subtotal_cc_tem'=> $regcc10000->subtotal,
            'iva_cc_tem'=> $regcc10000->iva,
            'total_cc_tem'=> $regcc10000->total,
            'descuento_cc_tem'=> $regcc10000->descuento,
            'monto_abonado'=>$resp['total']
        );
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_proformas->revisarCarrito($cliente,$sucursal,$valores,$pedido),
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"revisar"
        ));
    }

    public function revisarCarrito(){ 
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $pedido=$_GET['pedido'];

        $Cc10000 =new Entidades\Cc10000($this->adapter);
        $rsptaCc10000=$Cc10000->getMultiObj('documento',$pedido);
        $regCc10000=$rsptaCc10000->fetch_object();

        $Cc10010 =new Entidades\Cc10010($this->adapter);
        $Gui30000 =new Entidades\Gui30000($this->adapter);
        $Gui30010 =new Entidades\Gui30010($this->adapter);
        $rspta=$Cc10010->getMultiObj('cc10000id',$regCc10000->id);
        // $idCabecera=$this->session->get('idCarritoTemporal');
         $conf= new \Models\CarritoModel($this->adapter);
        // $rspta= $conf->llenarCarritoTemporal($idCabecera);
        $data=array();
        while ($reg=$rspta->fetch_object()){

            $inv00000=$conf->verificarProductos($reg->inv00000codigo);
            $reginv00000=$inv00000->fetch_object();
            $resp='';
            $check='';
            if(!isset($reginv00000)){
                $resp='<button type="button" class="btn btn-info" onclick="cambiarCodigo('.$reg->id.')"><i class="mdi mdi-grease-pencil" ></i></button>';
            }

            $gui= new \Models\PedidosModel($this->adapter);
            $respPedido=$gui->verificar($pedido,$reg->inv00000codigo);
            $regPedido=$respPedido->fetch_object();
          
            if(!isset($regPedido)){
                $Cc10010->updateMultiColum('guia',0,'id',$reg->id);
                $check='<input type="checkbox"  class="form-control" onclick="activarruta(this.checked,'.$reg->id.')">';
                $inputCantidad='<input type="number" value="'.$reg->cantidad.'"  class="form-control" min=1 max='.$reg->cantidad.' onclick="mofificarCantidadGuia(this.value,'.$reg->id.')">';
            }
            else{
                $check=' <input type="checkbox"  class="form-control"  disabled>';
                $inputCantidad='<input type="number" class="form-control" value="'.$reg->cantidad.'" disabled>';  
            }
            
     

            
        $data[]=array(
        "0"=>$check,
        "1"=>$inputCantidad,
        "2"=>$reg->inv00000codigo.' '.$resp,
        "3"=>$reg->descripcion,
        "4"=>$reg->bodega,
        "5"=>$reg->cantidad,
        "6"=>$reg->precio);
        }

        $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
        echo json_encode($results);
    }

    public function ingresarMonto($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $pedido=$_GET['pedido'];

        $conf= new Entidades\Cc20010($this->adapter);
        $limpiar= new \Models\CarritoModel($this->adapter);
    
        $value=isset($param['monto'])? $limpiar->limpiarCadenaString($param["monto"]):"";
        $metodo=isset($param['metodo'])? $limpiar->limpiarCadenaString($param["metodo"]):"";
       
        $conf->setCc40020id($metodo);
        $conf->setDocumento($pedido);
        $conf->setFecha(date('Y-m-d'));
        $conf->setUsuario($this->session->get('usuario'));
        $conf->setValor($value);
        $rspta =$conf->save();
        echo $rspta ? "1" : "0";
    }

    public function modificarGuia($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Cc10010= new Entidades\Cc10010($this->adapter);
        $id=isset($param['id_base'])? $Cc10010->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $Cc10010->limpiarCadenaString($param["value"]):"";
        if($value=='true'){
            $value=2;
        }else{
            $value=0;
        }
        $rspta=$Cc10010->updateMultiColum('guia',$value,'id',$id);
        echo $rspta ? "1" : "0";

    }

    public function listarMontos(){

        $conf= new \Models\Sis20010Model($this->adapter);
        $rspta=$conf->listarMontosCc20010($_GET['pedido']);
        $data=array();
        while ($reg=$rspta->fetch_object()){
           $var="";
            if($reg->fecha==date('Y-m-d') && $reg->usuario==$this->session->get('usuario')){
                $var='<button type="button" class="btn btn-danger" onclick="elimiarMonto('.$reg->id.')">X</button>';
            }
            $data[]=array(
            "0"=>$var,
            "1"=>$reg->fecha,
            "2"=>$reg->valor,
            "3"=>$reg->usuario,
            "4"=>$reg->formapago);
            }
            $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
            echo json_encode($results);
    }

    public function eliminarMonto($param=array()){
        $conf=new Entidades\Cc20010($this->adapter);
        $rspta=$conf->deleteMulti('id',$param['id']);
        echo $rspta ? "1" : "0";

    }

    public function modificarCantidadGuia($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf=new Entidades\Cc10010($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";
        $rspta= $conf->updateMultiColum('cantidad_guia',$value,'id',$id);
        echo $rspta ? "1" : "0";


    }

    public function editarCodigo($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        $pedido=$_GET['pedido'];
        $Cc10000=new Entidades\Cc10000($this->adapter);
        $respCc10000=$Cc10000->getMultiObj('documento',$pedido);
        $regcc10000=$respCc10000->fetch_object();

        $Cc00000=new Entidades\Cc00000($this->adapter);
        $respCc00000=$Cc00000->getMultiObj('id',$regcc10000->cc00000id);
        $regcc00000=$respCc00000->fetch_object();


        $conf= new \Models\ProformasModel($this->adapter);
        $inv00000=$conf->BuscarCodigo($regcc00000->nivelprecio,$param['codigo']);
        $reginv00000=$inv00000->fetch_object();
        $resp=1;
        if(!isset($reginv00000)){
            $resp='El codigo no existe en el sistema';
        }else{
            //actualizar carrito
        $Cc10010=new Entidades\Cc10010($this->adapter);
        $Cc10010->updateMultiColum('inv00000codigo',$reginv00000->codigo,'id',$param['id']);
        $Cc10010->updateMultiColum('descripcion',$reginv00000->descripcion,'id',$param['id']);
        $Cc10010->updateMultiColum('costo',$reginv00000->costo,'id',$param['id']);
        $Cc10010->updateMultiColum('marca_producto',$reginv00000->marcaproducto,'id',$param['id']);

        
         //consultar stock 
         $data = array(
            'codigo'=>trim($param['codigo']),
            'bodega' => strtoupper($this->session->get('bodUsuario'))
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproductobodega");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);


                if(trim($param['codigo'])=='VTAS-SERV-LOG'){
                    $stock_producto=100;
                }else{
                    $stock_producto=intval($server_output);
                }

                
        $Cc10010->updateMultiColum('stock_producto',$stock_producto,'id',$param['id']);
        $Cc10010->updateMultiColum('precio',$reginv00000->precio,'id',$param['id']);
        }

        echo  $resp;

    }

}

?>