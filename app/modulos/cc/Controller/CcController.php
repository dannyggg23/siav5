<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class CcController extends Controllers
{
    private $session,$conectar,$adapter,$layout,$website,$cliente,$login_empresa;
    
    public function __construct()
    {
        $this->session = new Session();
        //Conexion a la base de datos
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        //Traemos los datos del portal configurados
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        //Traemos los datos del cliente
        $this->cliente= new Models\Sis00050Model($this->adapter);
        //Cargamos el layout
        $this->layout_guia = new dti_layout_guias($this->website);
 
    }
    
    public function exec()
    {
        $this->pedidos();
    }

    public function pedidos(){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $formulario=new dti_builder_form($this->adapter);
        $maestro=new Entidades\Sis40120($this->adapter);
        $formulario->setForm($maestro->getMulti('formulario','frmTransportista'),'orden');
       
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_guia->listarPedidos()
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Pedidos"
        ));

    }

    public function aprobarpedidos(){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
       
       
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_guia->listarPedidosAprobar()
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Pedidos por aprobar"
        ));

    }


    public function pedidosall(){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
       
       
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_guia->listarPedidosall()
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Pedidos realizados"
        ));

    }


    public function listCobranzas(){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
       
       
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_guia->listCobranzas()
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Pedidos en cobranzas"
        ));

    }

    public function listarPedidosCobranzas(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Cc10000Model=new \Models\Cc10000Model($this->adapter);

        if($this->session->get('bodUsuario')=='TODOS'){
            $rspta=$Cc10000Model->listarPedidosCobranzasTodos();
        }else{
            $rspta=$Cc10000Model->listarPedidosCobranzas($this->session->get('usuario'));
        }

        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>'<button class="btn btn-success" title="Imprimir" onclick="imprimirpedido('.$reg->id.','."'".$reg->documento."'".')"><span class="fa fa-print"></span></button>'.
                    '  <a class="btn btn-primary" title="Detalle de pedido" onclick="listarDetalle('.$reg->id.','."'".$reg->documento."'".')" ><span class="fa fa-list"></span></a>'.
                    '  <a class="btn btn-warning" title="Estado de cuenta" onclick="detallesCuenta('."'".$reg->ruc."'".')" ><span class="fa fa-eye"></span></a>',
                    "1"=>$reg->documento,
                    "2"=>$reg->ruc,
                    "3"=>$reg->razonsocial,
                    "4"=>$reg->direccion,
                    "5"=>$reg->fecha,
                    "6"=>$reg->usuario,
                    "7"=>$reg->total,
                    "8"=>($reg->aprobadoCobranza)?'<span class="label label-success label-rouded">APROBADO</span>':
                    '<span class="label  label-danger label-rouded">EN PROCESO</span>');
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
    }




    public function listarPedidosAprobar(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Cc10000Model=new \Models\Cc10000Model($this->adapter);
        $rspta=$Cc10000Model->listarPedidosAprobar($this->session->get('usuario'));
        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>'<button class="btn btn-success" title="Aprobar pedido" onclick="aprobarPedido('.$reg->id.','."'".$reg->documento."'".')"><span class="fa fa-check"></span></button>'.
                    '  <a class="btn btn-primary" title="Detalle de pedido" onclick="listarDetalle('.$reg->id.','."'".$reg->documento."'".')" ><span class="fa fa-list"></span></a>'.
                    '  <a class="btn btn-warning" title="Estado de cuenta" onclick="detallesCuenta('."'".$reg->ruc."'".')" ><span class="fa fa-eye"></span></a>'.
                    '  <a class="btn btn-danger" title="Eliminar el pedido" onclick="eliminarPedido('.$reg->id.','."'".$reg->documento."'".')" ><span class="fa fa-trash"></span></a>',
                    "1"=>$reg->documento,
                    "2"=>$reg->ruc,
                    "3"=>$reg->razonsocial,
                    "4"=>$reg->direccion,
                    "5"=>$reg->fecha,
                    "6"=>$reg->usuario,
                    "7"=>$reg->total);
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
    }


    public function listarPedidos(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Cc10000Model=new \Models\Cc10000Model($this->adapter);
        $rspta=$Cc10000Model->listarPedidos($this->session->get('usuario'));
        $data= Array();
        while ($reg=$rspta->fetch_object()){

            $eliminar='';
            if($this->session->get('usuario')=='varaujo'){
                $eliminar='<a class="btn btn-danger" title="Eliminar el pedido" onclick="eliminarPedido('.$reg->id.','."'".$reg->documento."'".')" ><span class="fa fa-trash"></span></a>';
            }else{
                $eliminar='';
            }

                $data[]=array(
                    "0"=>'<button class="btn btn-warning" title="Ingresar un abono" onclick="abonar('.$reg->total.','.$reg->monto_abonado.','."'".$reg->documento."'".')"><span class="fa fa-plus"></span></button>'.
                   // '  <button class="btn btn-info" title="Revisar los abonos" onclick="revisarAbonos('."'".$reg->documento."'".')"><span class="fa fa-list"></span></button>'.
                   '  <a class="btn btn-primary" title="Detalle de pedido" onclick="listarDetalle('.$reg->id.','."'".$reg->documento."'".')" ><span class="fa fa-list"></span></a>'.
                    '  <a class="btn btn-success" title="Editar el pedido" href=pedidos/?pedido='.$reg->documento.'><span class="fa fa-info"></span></a>'.
                    $eliminar,
                    "1"=>$reg->documento,
                    "2"=>$reg->ruc,
                    "3"=>$reg->razonsocial,
                    "4"=>$reg->direccion,
                    "5"=>$reg->fecha,
                    "6"=>$reg->usuario,
                    "7"=>$reg->total,
                    "8"=>$reg->monto_abonado,
                    "9"=>$reg->pendiente);
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
    }

    public function listarPedidosAll(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Cc10000Model=new \Models\Cc10000Model($this->adapter);
        $rspta=$Cc10000Model->listarPedidosAll($this->session->get('usuario'));
        $data= Array();
        while ($reg=$rspta->fetch_object()){

            $eliminar='';
            if($this->session->get('usuario')=='varaujo'){
                $eliminar='<a class="btn btn-danger" title="Eliminar el pedido" onclick="eliminarPedido('.$reg->id.','."'".$reg->documento."'".')" ><span class="fa fa-trash"></span></a>';
            }else{
                $eliminar='';
            }

                $data[]=array(
                    "0"=>' <button class="btn btn-info" title="Revisar los abonos" onclick="revisarAbonos('."'".$reg->id."'".')"><span class="fa fa-list"></span></button>'.
                    // '  <a class="btn btn-success" title="Editar el pedido" href=pedidos/?pedido='.$reg->documento.'><span class="fa fa-info"></span></a>'.
                    $eliminar,
                    "1"=>$reg->documento,
                    "2"=>$reg->ruc,
                    "3"=>$reg->razonsocial,
                    "4"=>$reg->nitems >0? $reg->nitems:0,
                    "5"=>$reg->direccion,
                    "6"=>$reg->fecha,
                    "7"=>$reg->usuario,
                    "8"=>$reg->total,
                    "9"=>$reg->monto_abonado,
                    "10"=>$reg->pendiente);
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
        $conf= new Entidades\Cc20010($this->adapter);
        $limpiar= new \Models\CarritoModel($this->adapter);
        $id=isset($param['documento'])? $limpiar->limpiarCadenaString($param["documento"]):"";
        $value=isset($param['monto'])? $limpiar->limpiarCadenaString($param["monto"]):"";
        $metodo=isset($param['metodo'])? $limpiar->limpiarCadenaString($param["metodo"]):"";
        $conf->setCc40020id($metodo);
        $conf->setDocumento($id);
        $conf->setFecha(date('Y-m-d'));
        $conf->setUsuario($this->session->get('usuario'));
        $conf->setValor($value);
        $rspta =$conf->save();
        echo $rspta ? "1" : "0";
    }

    public function listarMontos($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\Cc20010Model($this->adapter);
        $id=isset($param['documento'])? $conf->limpiarCadenaString($param["documento"]):"";
        $rspta=$conf->listarMontos($id);
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

    public function listarDetallePedido($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\Cc10010Model($this->adapter);
        $id=isset($param['documento'])? $conf->limpiarCadenaString($param["documento"]):"";
        //print_r($id);
        $rspta=$conf->detallepedido($id);
        $data=array();
        while ($reg=$rspta->fetch_object()){
           
            $data[]=array(
            "0"=>$reg->inv00000codigo,
            "1"=>$reg->descripcion,
            "2"=>$reg->marca_producto,
            "3"=>$reg->cantidad,
            "4"=>$reg->precio,
            "5"=>$reg->descuento,
            "6"=>number_format($reg->subtotal*1.12,2,'.','')
        );
            }
            $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
            echo json_encode($results);
    }

    public function eliminarMonto($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf=new Entidades\Cc20010($this->adapter);
        $rspta=$conf->deleteMulti('id',$param['id']);
        echo $rspta ? "1" : "0";
    }

    public function eliminarPedido($param=array()){

        $Cc20010Entidad=new Entidades\Cc20010($this->adapter);
        $Cc10010Entidad=new Entidades\Cc10010($this->adapter);
        $Cc10000Entidad=new Entidades\Cc10000($this->adapter);
        $Cc20010Entidad->deleteMulti('documento',$param['documento']);
        $Cc10010Entidad->deleteMulti('cc10000id',$param['id']);
        $rspta=$Cc10000Entidad->deleteMulti('id',$param['id']);
        echo $rspta ? "1" : "0";
    }

    public function eliminarPedidoAprobar($param=array()){

    
        $Cc11010Entidad=new Entidades\Cc11010($this->adapter);
        $Cc11000Entidad=new Entidades\Cc11000($this->adapter);
      
        $Cc11010Entidad->deleteMulti('cc11000id',$param['id']);
        $rspta=$Cc11000Entidad->deleteMulti('id',$param['id']);
        echo $rspta ? "1" : "0";


    }

    public function verificarStock(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $bandera=true;

        $Sis50200 = new Entidades\Sis50200($this->adapter);
        $respSis50200=$Sis50200->getMultiObj('id',$this->session->get('idCarritoTemporal'));
        $regSis50200=$respSis50200->fetch_object();
        $id_cabecera=$regSis50200->id;
        $subtotal=$regSis50200->subtotal_cc_tem;
        $descuento=$regSis50200->descuento_cc_tem;
        $aprobado=$regSis50200->aprobado;

        $Cc00000 = new Entidades\Cc00000($this->adapter);
        $Cc00000Con= $Cc00000->getMultiObj('id',$regSis50200->id_cliente);
        $regCc00000=$Cc00000Con->fetch_object();


        $conf= new \Models\CarritoModel($this->adapter);
        $idCabecera=$this->session->get('idCarritoTemporal');
        $rspta= $conf->llenarCarritoTemporal($idCabecera);
        $resp='';
        $Sis50300 = new Entidades\Sis50300($this->adapter);
        while ($reg=$rspta->fetch_object()){
            $data = array(
                'codigo'=>trim($reg->id_producto),
                'bodega' => strtoupper($reg->bodega_producto)
            );
            $data = http_build_query($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproductobodega");
                    curl_setopt($ch, CURLOPT_POST, 1);

                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    curl_close ($ch);
                   
                    if(trim($reg->id_producto)=='VTAS-SERV-LOG'){
                        $stock_producto=100;
                    }else{
                        $stock_producto=intval($server_output);
                    }
                    $Sis50300->updateMultiColum('stock_producto',$stock_producto,'id',$reg->id);
        }

        $rspta= $conf->llenarCarritoTemporal($idCabecera);

        while ($reg=$rspta->fetch_object()){
            $inv00000=$conf->verificarProductos($reg->id_producto);
            $reginv00000=$inv00000->fetch_object();
            if(!isset($reginv00000)){
                $bandera=false;
                $resp.='<h3>EL CODIGO <strong>'.$reg->id_producto.'</strong> NO EXISTE EN GP</h3><br>';
            }else{
                if($reg->cantidad_producto>$reg->stock_producto){
                    $bandera=false;
                    $resp.='<h3>EL PRODUCTO <strong>'.$reg->id_producto.'</strong> SOLO DISPONE DE UN STOCK DE <strong>'.$reg->stock_producto.'</strong></h3><br>';
                }

                if(strtoupper($reg->bodega_producto)!=strtoupper($this->session->get('bodUsuario'))){
                    $bandera=false;
                    $resp.='<h3>EL PRODUCTO <strong>'.$reg->id_producto.'</strong> PERTENECEN A DIREFENTES BODEGAS</strong></h3><br>';
                }

             
            }  
        }

         //####VALIDAR SI EL PORCENTAJE DEL USUARIO CON EL REALIZADO EN EL CARRITO;

        if($aprobado){
        }else{
            $descuentoResp=($descuento*100)/($subtotal+$descuento);
            if((int)$descuentoResp<=((int)$this->session->get('descVendedor')+(int)$regCc00000->descuento)){
            }else{
                $Sis50200->updateMultiColum('alerta_porcentaje',1,'id',$this->session->get('idCarritoTemporal'));
                $bandera=false;
                $resp.='<h3>Supera el limite de descuento su % de descuento es: <strong>'.(int)$this->session->get('descVendedor').'% </strong> El pedido esta con un descuento del  <strong>'.(int)$descuento.'%</strong></h3><br>';
            }
        }

        if($bandera){
            echo 'OK';
        }else{
            echo $resp;
        }
    }

    public function verificarStockPedido(){ 
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $bandera=true;

        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();
        $id_cabecera=$regcc10000->id;
        $subtotal=$regcc10000->subtotal;
        $descuento=$regcc10000->descuento;
        $aprobado=$regcc10000->aprobado;

        $Cc00000 = new Entidades\Cc00000($this->adapter);
        $Cc00000Con= $Cc00000->getMultiObj('id',$regcc10000->cc00000id);
        $regCc00000=$Cc00000Con->fetch_object();

        
        $conf= new \Models\CarritoModel($this->adapter);
        $Cc10010= new Entidades\Cc10010($this->adapter);
        $rspta= $Cc10010->getMultiObj('cc10000id',$id_cabecera);
        $resp='';

        while ($reg=$rspta->fetch_object()){
            $data = array(
                'codigo'=>trim($reg->inv00000codigo),
                'bodega' => strtoupper($reg->bodega)
            );
            $data = http_build_query($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproductobodega");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    curl_close ($ch);

                    if(trim($reg->inv00000codigo)=='VTAS-SERV-LOG'){
                        $stock_producto=100;
                    }else{
                        $stock_producto=intval($server_output);
                    }

                    $Cc10010->updateMultiColum('stock_producto',$stock_producto,'id',$reg->id);
        }

                $rspta= $Cc10010->getMultiObj('cc10000id',$id_cabecera);
                while ($reg=$rspta->fetch_object()){
                    $inv00000=$conf->verificarProductos($reg->inv00000codigo);
                    $reginv00000=$inv00000->fetch_object();
                    if(!isset($reginv00000)){
                        $bandera=false;
                        $resp.='<h3>EL CODIGO <strong>'.$reg->inv00000codigo.'</strong> NO EXISTE EN GP</h3><br>';
                    }else{
                        if($reg->cantidad>$reg->stock_producto){
                            $bandera=false;
                            $resp.='<h3>EL PRODUCTO <strong>'.$reg->inv00000codigo.'</strong> SOLO DISPONE DE UN STOCK DE <strong>'.$reg->stock_producto.'</strong></h3><br>';
                        }

                        if(strtoupper($reg->bodega)!=strtoupper($this->session->get('bodUsuario'))){
                            $bandera=false;
                            $resp.='<h3>EL PRODUCTO <strong>'.$reg->inv00000codigo.'</strong> PERTENECEN A DIREFENTES BODEGAS</strong></h3><br>';
                        }

                    }  
                }


        //####VALIDAR SI EL PORCENTAJE DEL USUARIO CON EL REALIZADO EN EL PEDIDO;

        if($aprobado){
        }else{
            $descuentoResp=($descuento*100)/($subtotal+$descuento);
            if((int)$descuentoResp<=((int)$this->session->get('descVendedor')+(int)$regCc00000->descuento)){
            }else{
                $cc10000->updateMultiColum('alerta_porcentaje',1,'id',$id_cabecera);
                $bandera=false;
                $resp.='<h3>Supera el limite de descuento su % de descuento es: <strong>'.(int)$this->session->get('descVendedor').'% </strong> El pedido esta con un descuento del  <strong>'.(int)$descuento.'%</strong></h3><br>';
            }
        }
        if($bandera){
            echo 'OK';
        }else{
            echo $resp;
        }
    }


    public function verificarStockPedidoAprobar(){ 
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $bandera=true;

        $cc11000 = new Entidades\Cc11000($this->adapter);
        $respcc11000=$cc11000->getMultiObj('documento',$_GET['pedido']);
        $regcc11000=$respcc11000->fetch_object();
        $id_cabecera=$regcc11000->id;
        $subtotal=$regcc11000->subtotal;
        $descuento=$regcc11000->descuento;
        $aprobado=$regcc11000->aprobado;

        $Cc00000 = new Entidades\Cc00000($this->adapter);
        $Cc00000Con= $Cc00000->getMultiObj('id',$regcc11000->cc00000id);
        $regCc00000=$Cc00000Con->fetch_object();

        
        $conf= new \Models\CarritoModel($this->adapter);
        $Cc11010= new Entidades\Cc11010($this->adapter);
        $rspta= $Cc11010->getMultiObj('cc11000id',$id_cabecera);
        $resp='';

        while ($reg=$rspta->fetch_object()){
            $data = array(
                'codigo'=>trim($reg->inv00000codigo),
                'bodega' => strtoupper($reg->bodega)
            );
            $data = http_build_query($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproductobodega");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    curl_close ($ch);
                    

                    if(trim($reg->inv00000codigo)=='VTAS-SERV-LOG'){
                        $stock_producto=100;
                    }else{
                        $stock_producto=intval($server_output);
                    }

                    $Cc11010->updateMultiColum('stock_producto',$stock_producto,'id',$reg->id);
        }

                $rspta= $Cc11010->getMultiObj('cc11000id',$id_cabecera);
                while ($reg=$rspta->fetch_object()){
                    $inv00000=$conf->verificarProductos($reg->inv00000codigo);
                    $reginv00000=$inv00000->fetch_object();
                    if(!isset($reginv00000)){
                        $bandera=false;
                        $resp.='<h3>EL CODIGO <strong>'.$reg->inv00000codigo.'</strong> NO EXISTE EN GP</h3><br>';
                    }else{
                        if($reg->cantidad>$reg->stock_producto){
                            $bandera=false;
                            $resp.='<h3>EL PRODUCTO <strong>'.$reg->inv00000codigo.'</strong> SOLO DISPONE DE UN STOCK DE <strong>'.$reg->stock_producto.'</strong></h3><br>';
                        }

                        if ($this->session->get('bodUsuario')=='TODOS'){

                        }else{
                            if(strtoupper($reg->bodega)!=strtoupper($this->session->get('bodUsuario'))){
                                $bandera=false;
                                $resp.='<h3>EL PRODUCTO <strong>'.$reg->inv00000codigo.'</strong> PERTENECEN A DIREFENTES BODEGAS</strong></h3><br>';
                            }

                        }

                       

                    }  
                }


        //####VALIDAR SI EL PORCENTAJE DEL USUARIO CON EL REALIZADO EN EL PEDIDO;

        if($aprobado){
        }else{
            $descuentoResp=($descuento*100)/($subtotal+$descuento);
            if((int)$descuentoResp<=((int)$this->session->get('descVendedor')+(int)$regCc00000->descuento)){
            }else{
                $cc11000->updateMultiColum('alerta_porcentaje',1,'id',$id_cabecera);
                $bandera=false;
                $resp.='<h3>Supera el limite de descuento su % de descuento es: <strong>'.(int)$this->session->get('descVendedor').'% </strong> El pedido esta con un descuento del  <strong>'.(int)$descuento.'%</strong></h3><br>';
            }
        }
        if($bandera){
            echo 'OK';
        }else{
            echo $resp;
        }

        //echo 'OK';
    }



    public function verificarCodigo(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $bandera=true;
        $conf= new \Models\CarritoModel($this->adapter);
        $idCabecera=$this->session->get('idCarritoTemporal');
        $rspta= $conf->llenarCarritoTemporal($idCabecera);
        $resp='';
        while ($reg=$rspta->fetch_object()){
            $inv00000=$conf->verificarProductos($reg->id_producto);
            $reginv00000=$inv00000->fetch_object();
            if(!isset($reginv00000)){
                $bandera=false;
                $resp.='<h3>EL CODIGO <strong>'.$reg->id_producto.'</strong> NO EXISTE EN GP</h3><br>';
            }
        }
        if($bandera){
            echo 'OK';
        }else{
            echo $resp;
        }
    }

    public function editarCodigo($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\ProformasModel($this->adapter);
        $inv00000=$conf->BuscarCodigo($this->session->get('nivelprecio'),$param['codigo']);
        $reginv00000=$inv00000->fetch_object();
        $resp=1;
        if(!isset($reginv00000)){
            $resp='El codigo no existe en el sistema';
        }else{
            //actualizar carrito
        $sis50300=new Entidades\Sis50300($this->adapter);
        $sis50300->updateMultiColum('id_producto',$reginv00000->codigo,'id',$param['id']);
        $sis50300->updateMultiColum('descripcion_producto',$reginv00000->descripcion,'id',$param['id']);
        $sis50300->updateMultiColum('costo_producto',$reginv00000->costo,'id',$param['id']);
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

          
        $sis50300->updateMultiColum('stock_producto',$stock_producto,'id',$param['id']);
        $sis50300->updateMultiColum('precio_producto',$reginv00000->precio,'id',$param['id']);
        }

        echo  $resp;

    }


    public function guardarFactura($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $guias= new \Models\Gui40000Model($this->adapter);

        $respBodega=$guias->select(strtoupper($this->session->get('bodUsuario')));
        $regBodega=$respBodega->fetch_object();
        $transportista= new \Models\Gui00000Model($this->adapter);
        $respTransportista=$transportista->selectTransportista($param['trnasportista']);
        $regTransportista=$respTransportista->fetch_object();
        $secuencial= new \Models\Gui30000Model($this->adapter);
        $numSecuencial= $secuencial->getNumGuia($regBodega->secuencial);

        $numeroGuia=$regBodega->secuencial.'-'.$numSecuencial['num_guia'];

        $numero = (int)$numSecuencial['num_guia']+1;
        switch (strlen($numero)) {
            case 1:
                $numero = '00000000'.$numero;
                break;
            case 2:
                $numero = '0000000'.$numero;
                break;
            case 3:
                $numero = '000000'.$numero;
                break;
            case 4:
                $numero = '00000'.$numero;
                break;
            case 5:
                $numero = '0000'.$numero;
                break;
            case 6:
                $numero = '000'.$numero;
                break;
            case 7:
                $numero = '00'.$numero;
                break;
            case 8:
                $numero = '0'.$numero;
                break;
            default:
                $numero = $numero;
                break;
        }

        $guias->updateMultiColum('num_guia',$numero,'secuencial',$regBodega->secuencial);
        
        
        

        //WEB SERVICE FACTURA
        //CLIENTE
        $clientes_model= new \Models\ClientesModel($this->adapter);
        $respCliente=$clientes_model->ListarClientesId($this->session->get('idCliente'));
        $regCliente=$respCliente->fetch_object();
        //NUM_PEDIDO
        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $numPedido2=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
        $numPedido=$numPedido2['numpedido'];
        $docid=$numPedido2['docid'];
        $bodega=$numPedido2['bodega'];
        $Bachnmbr=$numPedido2['Bachnmbr'];
        //CC00002 CODIGODIRECCION
        $cc00002 = new Entidades\Cc00002($this->adapter);
        $respcc00002=$cc00002->getMulti('id',$this->session->get('idSucursalCliente'));
        $Prbtadcd=$respcc00002['codigodireccion'];
        //USUARIO-VENDEDORES
        $sis00300 = new Entidades\Sis00300($this->adapter);
        $respsis00300=$sis00300->getMulti('usuario',$this->session->get('usuario'));
        $Slprsnid=$respsis00300['cod_vendedor'];

        //CABECERA CARRITO TEMPORAL
        $Sis50200 = new Entidades\Sis50200($this->adapter);
        $respSis50200=$Sis50200->getMulti('id',$this->session->get('idCarritoTemporal'));
        $SubTotal=$respSis50200['subtotal_cc_tem'];


        //DESCUENTO TOTAL DE CARRITO
        $Sis50300Model=new \Models\CarritoModel($this->adapter);
        $respSis500300=$Sis50300Model->listarDescuentoTotal($this->session->get('idCarritoTemporal'));
        $regSis50300=$respSis500300->fetch_object();
        $DescuentoTotal=$regSis50300->descuento;
      


        $data = array(
            'Ruc'=> $regCliente->ruc,//RUC CLIENTE
            'NumeroPedido' => $numPedido,//NUMERO PEDIDO
            'Fecha' => date('Y-m-d H:i:s'),
            'DocID' => $docid,//GUI40000 DOCID
            'SubTotal' => $SubTotal+$DescuentoTotal,
            'DescuentoTotal' =>$DescuentoTotal,
            'Bodega' => $bodega,
            'Prbtadcd' =>$Prbtadcd,//CC000002 CODIGODIRECCION
            'Bachnmbr' =>$Bachnmbr,//GUI00004 Bachnmbr
            'Estado' =>'gp',// GP
            'Activo' =>1,
            'Slprsnid' =>$Slprsnid,//CODIGO VENDEDOR
            'Observacion' =>''
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=cabecera");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);

        if($server_output=='OK'){

              //detalles carrito temporal
        $Sis50300 = new Entidades\Sis50300($this->adapter);
        $respSis50300Obj=$Sis50300->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));

        while($respSis50300=$respSis50300Obj->fetch_object()){

            $Articulo=$respSis50300->id_producto;
            $Cantidad=$respSis50300->cantidad_producto;
            $Precio=$respSis50300->precio_producto;
            $Descuento=(float)$respSis50300->descuento_producto+(float)$respSis50300->descuento_cliente;
    
                            $data = array(
                                'Ruc'=> $regCliente->ruc,
                                'NumeroPedido' => $numPedido,
                                'Articulo' => $Articulo,
                                'Cantidad' => $Cantidad,
                                'Precio' => $Precio,
                                'Descuento' =>$Descuento,
                                'Estado' => 'gp',
                                'Activo' => 1
                            );
                            $data = http_build_query($data);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=detalles");
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $server_output = curl_exec($ch);
                                    curl_close ($ch);
        }

        $data = array(
            'NumeroPedido' => $numPedido
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=actualizarDatos");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_outputRes = curl_exec($ch);
                curl_close ($ch);


            if($server_output=='OK'){
            //#################################

            $data = array(
                'NumeroPedido' => $numPedido
            );
            $data = http_build_query($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=facturaOK");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $verifi_Factura = curl_exec($ch);
                    curl_close ($ch);
                if($verifi_Factura=='OK'){

                    
                $data = array(
                    'd_numControl'=>$numeroGuia,
                    'ig_dirPartida' => $regBodega->direccion,
                    'ig_razonSocialTransportista' => $regTransportista->razonsocial,
                    'ig_tipoDocumento' =>$regTransportista->sis40170id,
                    'ig_identificacionTransportista' => $regTransportista->codigo,
                    'ig_rise' =>'',
                    'ig_fechaIniTransporte' =>$param['ig_fechaIniTransporte'],
                    'ig_fechaFinTransporte' =>$param['ig_fechaFinTransporte'],
                    'ig_placa' =>$param['ig_placa'],
                    'd_observacion' =>$param['d_observacion']
                );


                $data = http_build_query($data);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/guias.php?op=cabecera");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $server_output = curl_exec($ch);
                        curl_close ($ch);
                        if($server_output=='OK'){
                        //GUARDAR DETALLES
                        $carritoGuias= new \Models\CarritoModel($this->adapter);
                        $respCarrito=$carritoGuias->listarCarritoGuia($this->session->get('idCarritoTemporal'));
                        $detSecuencia=0;
                        $bandera=false;
                        $error="";
                        while ($regCarrito=$respCarrito->fetch_object()){
                            $detSecuencia++;
                            $detalles = array(
                                'd_numControl'=> $numeroGuia,
                                'codigoInterno' => $regCarrito->id_producto,
                                'codigoAdicional' =>'',
                                'descripcion' =>$regCarrito->descripcion_producto,
                                'cantidad' => $regCarrito->cantidad_producto,
                                'unidad_medida' =>'',
                                'bodega' =>strtoupper($this->session->get('bodUsuario')),
                                'detSecuencia'=>$detSecuencia
                            );
                            $detalles = http_build_query($detalles);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/guias.php?op=detalles");
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$detalles);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $server_output = curl_exec($ch);
                                    curl_close ($ch);
                                    if($server_output!='OK'){
                                        $bandera =true;
                                        $error=$server_output;
                                    }
                            }
                            if($bandera==false){
                                
                                $data = array(
                                    'd_numControl'=>$numeroGuia,
                                    'Secuencia' => '1',
                                    'd_IdentificacionDestinatario' => $regCliente->ruc,
                                    'd_razonSocialDestinatario' =>$regCliente->razonsocial,
                                    'd_dirDestinatario' => $regCliente->direccion,
                                    'd_ruta' =>$regCliente->ciudad,
                                    'd_numPedidoVenta' =>$this->session->get('idCarritoTemporal'));
                                $data = http_build_query($data);
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/guias.php?op=destino");
                                        curl_setopt($ch, CURLOPT_POST, 1);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        $server_output = curl_exec($ch);
                                        curl_close ($ch);

                                        if($server_output=='OK'){
                                            $data = array(
                                                'd_numControl'=>$numeroGuia);
                                            $data = http_build_query($data);
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/guias.php?op=xml");
                                                    curl_setopt($ch, CURLOPT_POST, 1);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    $server_output = curl_exec($ch);
                                                    curl_close ($ch);
                                    }



                                        if($server_output=='OK'){
                                            //ENVIAR LOS WEB SERVICE DE FACTURA (RETORNA EL NUMERO DE FACTURA)


                                            //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLASÇ
                                            $guiaCabeceraEntidad = new Entidades\Cc30000($this->adapter);
                                            $guiaCabeceraEntidad->autocommit();
                                            $guiaDetalleEntidad = new Entidades\Cc30010($this->adapter);
                                            $inventario = new Entidades\Inv00000($this->adapter);
                                            $guiaCabeceraCarritoTemp = new Entidades\Sis50200($this->adapter);
                                            $guiaDetalleCarritoTemp= new Entidades\Sis50300($this->adapter);
                                           
                                            //insertar cabecera
                                            $respGuiaCabeceraEntidad=$guiaCabeceraCarritoTemp->getMultiObj('id',$this->session->get('idCarritoTemporal'));
                                            $regCabecera=$respGuiaCabeceraEntidad->fetch_object();
                                            $guiaCabeceraEntidad->setDocumento('');
                                            $guiaCabeceraEntidad->setPedido($numPedido);
                                            $guiaCabeceraEntidad->setFecha(date('Y-m-d'));
                                            $guiaCabeceraEntidad->setUsuario($regCabecera->id_usuario);
                                            $guiaCabeceraEntidad->setCc00000id($regCabecera->id_cliente);
                                            $guiaCabeceraEntidad->setCc00002id($regCabecera->id_sucursal);
                                            $guiaCabeceraEntidad->setSubtotal($regCabecera->subtotal_cc_tem);
                                            $guiaCabeceraEntidad->setDescuento($regCabecera->descuento_cc_tem);
                                            $guiaCabeceraEntidad->setIva($regCabecera->iva_cc_tem);
                                            $guiaCabeceraEntidad->setTotal($regCabecera->total_cc_tem);
                                            $guiaCabeceraEntidad->setMonto_abonado($regCabecera->monto_abonado);
                                            $idCabecera=$guiaCabeceraEntidad->save();

                                            $guiaDetalle=$guiaDetalleCarritoTemp->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));

                                            while($regDetalle=$guiaDetalle->fetch_object()){
                                                $respinv0000= $inventario->getMultiObj('codigo', $regDetalle->id_producto);
                                                $reginventario=$respinv0000->fetch_object();
                                                if(!isset($reginventario->id)){
                                                    $reginventario->id=$regDetalle->id_producto;
                                                }
                                                $guiaDetalleEntidad->setCc30000id($idCabecera);
                                                $guiaDetalleEntidad->setInv00000id($reginventario->id);
                                                $guiaDetalleEntidad->setDescripcion($regDetalle->descripcion_producto);
                                                $guiaDetalleEntidad->setCosto($regDetalle->costo_producto);
                                                $guiaDetalleEntidad->setBodega($regDetalle->bodega_producto);
                                                $guiaDetalleEntidad->setCantidad($regDetalle->cantidad_producto);
                                                $guiaDetalleEntidad->setPrecio($regDetalle->precio_producto);
                                                $guiaDetalleEntidad->setDescuento($regDetalle->descuento_producto);
                                                $guiaDetalleEntidad->setSubtotal($regDetalle->subtotal_producto);
                                                $guiaDetalleEntidad->setGuia($regDetalle->guia);
                                                $guiaDetalleEntidad->setCantidad_guia($regDetalle->cantidad_guia);
                                                $guiaDetalleEntidad->setMarca_producto($regDetalle->marca_producto);
                                                $guiaDetalleEntidad->setDescuento_cliente($regDetalle->descuento_cliente);
                                                $resp =$guiaDetalleEntidad->save();
                                                if($resp){
                                                    $bandera=false;
                                                }else{
                                                    $bandera=true;
                                                }  
                                            }
                                            ///SI TODO ESTA BIEN COMPRUEBO LA BANDERA
                                        if($bandera==false){

                                            ////####-VALIDAR SI GENERA GUIA--####////

                                            //GUARDAR EN LAS GUIAS
                                            $gui30000 = new Entidades\Gui30000($this->adapter);
                                            $Gui30010 = new Entidades\Gui30010($this->adapter);
                                            $inventario = new Entidades\Inv00000($this->adapter);
                                            $gui30000->setDocumento($numeroGuia);
                                            $gui30000->setPedido($numPedido);
                                            $gui30000->setGui00000id($regTransportista->codigo);
                                            $gui30000->setPlaca($param['ig_placa']);
                                            $gui30000->setFecha(date('Y-m-d'));
                                            $gui30000->setFechainicio($param['ig_fechaIniTransporte']);
                                            $gui30000->setFechafin($param['ig_fechaFinTransporte']);
                                            $gui30000->setUsuario($this->session->get('usuario'));
                                            $Cc3000id=$gui30000->save();
                                            if($Cc3000id > 0){
                                                $bandera=false;
                                                //GUARDAR DETALLES GUI30010
                                            $guiaDetalle=$guiaDetalleCarritoTemp->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));
                                            while($regDetalle=$guiaDetalle->fetch_object()){
                                               
                                               
                                                if($regDetalle->guia){
                                                // $respinv0000=$inventario->getMultiObj('codigo', $regDetalle->id_producto);
                                                // $reginventario=$respinv0000->fetch_object();
                                                // if(!isset($reginventario->id)){
                                                //     $reginventario->id=$regDetalle->id_producto;
                                                // }
                                                    $Gui30010->setGui30000id($Cc3000id);
                                                    $Gui30010->setInv00000codigo($regDetalle->id_producto);
                                                    $Gui30010->setDescripcion($regDetalle->descripcion_producto);
                                                    $Gui30010->setCosto($regDetalle->costo_producto);
                                                    $Gui30010->setBodega($regDetalle->bodega_producto);
                                                    $Gui30010->setCantidad($regDetalle->cantidad_producto);
                                                    $Gui30010->setPrecio($regDetalle->precio_producto);
                                                    $Gui30010->setDescuento($regDetalle->descuento_producto);
                                                    $Gui30010->setSubtotal($regDetalle->subtotal_producto);
                                                   
                                                    $resp =$Gui30010->save();
                                                    if($resp){
                                                        $bandera=false;
                                                    }else{
                                                        $bandera=true;
                                                    }
                                                } 
                                            }
                                            }else{
                                                $bandera=true;
                                            }

                                            //GUARDAR MONTOS EN LA TABLA CC20010
                                            $Sis20010 = new Entidades\Sis20010($this->adapter);
                                            $Cc20010=new Entidades\Cc20010($this->adapter);
                                            $respMonto=$Sis20010->getMultiObj('documento',$this->session->get('idCarritoTemporal'));
                                            while($regMonto=$respMonto->fetch_object()){
                                                
                                                $Cc20010->setDocumento($numPedido);
                                                $Cc20010->setFecha(date('Y-m-d'));
                                                $Cc20010->setValor($regMonto->valor);
                                                $Cc20010->setUsuario($regMonto->usuario);
                                                $Cc20010->setCc40020id($regMonto->cc40020id);
                                                $resp=$Cc20010->save();
                                                    if($resp){
                                                        $bandera=false;
                                                    }else{
                                                        $bandera=true;
                                                    }
                                            }

                                            if($bandera==false){
                                                //ELIMINAR EL CARRITO TEMPORAL
                                            $guiaDetalleCarritoTemp->deleteMulti('id_cabecera',$this->session->get('idCarritoTemporal'));
                                            $guiaCabeceraCarritoTemp->deleteMulti('id',$this->session->get('idCarritoTemporal'));
                                            $Sis20010->deleteMulti('documento',$this->session->get('idCarritoTemporal'));
                                            //AUMENTAR EL NUMERO DEL PEDIDO
                                            $valexplode = explode('-', $numPedido);
                                            $newsecuencial = (int) $valexplode[1];
                                            $newsecuencial += 1;
                                            switch (strlen($newsecuencial)) {
                                                case 1:
                                                    $newsecuencial = '00000000'.$newsecuencial;
                                                    break;
                                                case 2:
                                                    $newsecuencial = '0000000'.$newsecuencial;
                                                    break;
                                                case 3:
                                                    $newsecuencial = '000000'.$newsecuencial;
                                                    break;
                                                case 4:
                                                    $newsecuencial = '00000'.$newsecuencial;
                                                    break;
                                                case 5:
                                                    $newsecuencial = '0000'.$newsecuencial;
                                                    break;
                                                case 6:
                                                    $newsecuencial = '000'.$newsecuencial;
                                                    break;
                                                case 7:
                                                    $newsecuencial = '00'.$newsecuencial;
                                                    break;
                                                case 8:
                                                    $newsecuencial = '0'.$newsecuencial;
                                                    break;
                                            }
                                            $Gui40000->updateMultiColum('numpedido', $valexplode[0].'-'.$newsecuencial, 'bodega',strtoupper($this->session->get('bodUsuario')));
                                            $Gui40000->commit();
                                            echo 'OK';
                                            }
                                        }
                                        }
                            }else{
                            
                                echo $error.$server_output;
                            }
                        }else{
                            echo 'ERROR DETALLES FACTURA'.$server_output;
                        }

                }else{
                    echo $verifi_Factura;
                }
        

                        }else
                        {
                            echo 'ERROR CABECERA'.$server_output;
                        }

                    }else {
                        echo'ERROR CABECERA FACTURA'.$server_output;
                    }
    }
    

    public function validarGuia(){
        $carritoGuias= new \Models\CarritoModel($this->adapter);
        $respCarrito=$carritoGuias->listarCarritoGuia($this->session->get('idCarritoTemporal'));
        $regCarrito=$respCarrito->fetch_object();
        $resp=1;
        if(!isset($regCarrito)){
            $resp=0;
        }
        echo $resp;
    }

    public function validarGuiaPedido(){
        $pedido=$_GET['pedido'];
        $Cc10010= new Entidades\Cc10010($this->adapter);
        $Cc10000= new Entidades\Cc10000($this->adapter);
        $respCc10000=$Cc10000->getMultiObj('documento',$pedido);
        $regCc10000=$respCc10000->fetch_object();
        $respCc10010=$Cc10010->getMultiObj('guia',2,'cc10000id',$regCc10000->id,'guia_pedido',0);
        $regCc10010=$respCc10010->fetch_object();
        $resp=1;
        if(!isset($regCc10010)){
            $resp=0;
        }
        echo $resp;
    }

    public function getNumPedido()
    {
        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $numPedido2=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
        $numPedido=$numPedido2['numpedido'];
        //AUMENTAR EL NUMERO DEL PEDIDO
        $valexplode = explode('-', $numPedido);
        $newsecuencial = (int) $valexplode[1];
        $newsecuencial += 1;
        switch (strlen($newsecuencial)) {
            case 1:
                $newsecuencial = '00000000'.$newsecuencial;
                break;
            case 2:
                $newsecuencial = '0000000'.$newsecuencial;
                break;
            case 3:
                $newsecuencial = '000000'.$newsecuencial;
                break;
            case 4:
                $newsecuencial = '00000'.$newsecuencial;
                break;
            case 5:
                $newsecuencial = '0000'.$newsecuencial;
                break;
            case 6:
                $newsecuencial = '000'.$newsecuencial;
                break;
            case 7:
                $newsecuencial = '00'.$newsecuencial;
                break;
            case 8:
                $newsecuencial = '0'.$newsecuencial;
                break;
        }
        $Gui40000->updateMultiColum('numpedido', $valexplode[0].'-'.$newsecuencial, 'bodega',strtoupper($this->session->get('bodUsuario')));
                                                
        //Validar que no exista el numero de pedido
        $valNumPedido = $secuencial->valNumPedido($numPedido2['numpedido']);
        if ($valNumPedido['total']>0) {
            $this->getNumPedido();
        }
        else {
            return $numPedido;
        }
    }

    public function guardarFacSola(){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $guias= new \Models\Gui40000Model($this->adapter);
        $respBodega=$guias->select(strtoupper($this->session->get('bodUsuario')));
        $regBodega=$respBodega->fetch_object();
        $secuencial= new \Models\Gui30000Model($this->adapter);
        $numSecuencial= $secuencial->getNumGuia($regBodega->secuencial);

        //WEB SERVICE FACTURA
        //CLIENTE
        $clientes_model= new \Models\ClientesModel($this->adapter);
        $respCliente=$clientes_model->ListarClientesId($this->session->get('idCliente'));
        $regCliente=$respCliente->fetch_object();
        //NUM_PEDIDO
        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $numPedido2=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
        $numPedido=$numPedido2['numpedido'];
        $docid=$numPedido2['docid'];
        $bodega=$numPedido2['bodega'];
        $Bachnmbr=$numPedido2['Bachnmbr'];
        //AUMENTAR EL NUMERO DEL PEDIDO
            $valexplode = explode('-', $numPedido);
            $newsecuencial = (int) $valexplode[1];
            $newsecuencial += 1;
            switch (strlen($newsecuencial)) {
                case 1:
                    $newsecuencial = '00000000'.$newsecuencial;
                    break;
                case 2:
                    $newsecuencial = '0000000'.$newsecuencial;
                    break;
                case 3:
                    $newsecuencial = '000000'.$newsecuencial;
                    break;
                case 4:
                    $newsecuencial = '00000'.$newsecuencial;
                    break;
                case 5:
                    $newsecuencial = '0000'.$newsecuencial;
                    break;
                case 6:
                    $newsecuencial = '000'.$newsecuencial;
                    break;
                case 7:
                    $newsecuencial = '00'.$newsecuencial;
                    break;
                case 8:
                    $newsecuencial = '0'.$newsecuencial;
                    break;
            }
            $Gui40000->updateMultiColum('numpedido', $valexplode[0].'-'.$newsecuencial, 'bodega',strtoupper($this->session->get('bodUsuario')));
                                                
        //Validar que no exista el numero de pedido
        $valNumPedido = $secuencial->valNumPedido($numPedido2['numpedido']);
        if ($valNumPedido['total']>0) {
            $numPedido=$this->getNumPedido();
        }
        
        //CC00002 CODIGODIRECCION
        $cc00002 = new Entidades\Cc00002($this->adapter);
        $respcc00002=$cc00002->getMulti('id',$this->session->get('idSucursalCliente'));
        $Prbtadcd=$respcc00002['codigodireccion'];
        //USUARIO-VENDEDORES
        $sis00300 = new Entidades\Sis00300($this->adapter);
        $respsis00300=$sis00300->getMulti('usuario',$this->session->get('usuario'));
        $Slprsnid=$respsis00300['cod_vendedor'];
        //CABECERA CARRITO TEMPORAL
        $Sis50200 = new Entidades\Sis50200($this->adapter);
        $respSis50200=$Sis50200->getMulti('id',$this->session->get('idCarritoTemporal'));
        $SubTotal=$respSis50200['subtotal_cc_tem'];

        //DESCUENTO TOTAL DE CARRITO
        $Sis50300Model=new \Models\CarritoModel($this->adapter);
        $respSis500300=$Sis50300Model->listarDescuentoTotal($this->session->get('idCarritoTemporal'));
        $regSis50300=$respSis500300->fetch_object();
        $DescuentoTotal=$regSis50300->descuento;

        $data = array(
            'Ruc'=> $regCliente->ruc,//RUC CLIENTE
            'NumeroPedido' => $numPedido,//NUMERO PEDIDO
            'Fecha' => date('Y-m-d'),
            'DocID' => $docid,//GUI40000 DOCID
            'SubTotal' => $SubTotal+$DescuentoTotal,
            'DescuentoTotal' =>$DescuentoTotal,
            'Bodega' => $bodega,
            'Prbtadcd' =>$Prbtadcd,//CC000002 CODIGODIRECCION
            'Bachnmbr' =>$Bachnmbr,//GUI00004 Bachnmbr
            'Estado' =>'gp',// GP
            'Activo' =>1,
            'Slprsnid' =>$Slprsnid,//CODIGO VENDEDOR
            'Observacion' =>''
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=cabecera");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);

        if($server_output=='OK'){

              //detalles carrito temporal
        $Sis50300 = new Entidades\Sis50300($this->adapter);
        $respSis50300Obj=$Sis50300->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));

        while($respSis50300=$respSis50300Obj->fetch_object()){

            $Articulo=$respSis50300->id_producto;
            $Cantidad=$respSis50300->cantidad_producto;
            $Precio=$respSis50300->precio_producto;
            $Descuento=(float)$respSis50300->descuento_producto+(float)$respSis50300->descuento_cliente;
    
                            $data = array(
                                'Ruc'=> $regCliente->ruc,
                                'NumeroPedido' => $numPedido,
                                'Articulo' => $Articulo,
                                'Cantidad' => $Cantidad,
                                'Precio' => $Precio,
                                'Descuento' =>$Descuento,
                                'Estado' => 'gp',
                                'Activo' => 1
                            );
                            $data = http_build_query($data);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=detalles");
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $server_output = curl_exec($ch);
                                    curl_close ($ch);
        }

        ///
        $data = array(
            'NumeroPedido' => $numPedido
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=actualizarDatos");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_outputRes = curl_exec($ch);
                curl_close ($ch);

        ///

                                             if($server_output=='OK'){
                                            //VERIFICO LA FACTURA
                                            $data = array(
                                                'NumeroPedido' => $numPedido
                                            );
                                            $data = http_build_query($data);
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=facturaOK");
                                                    curl_setopt($ch, CURLOPT_POST, 1);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    $verifi_Factura = curl_exec($ch);
                                                    curl_close ($ch);

                                                if($verifi_Factura=='OK'){
                                                    //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLASÇ
                                                $guiaCabeceraEntidad = new Entidades\Cc30000($this->adapter);
                                                $guiaCabeceraEntidad->autocommit();
                                                $guiaDetalleEntidad = new Entidades\Cc30010($this->adapter);
                                                $guiaCabeceraCarritoTemp = new Entidades\Sis50200($this->adapter);
                                                $guiaDetalleCarritoTemp= new Entidades\Sis50300($this->adapter);
                                                $inv00000= new Entidades\Inv00000($this->adapter);

                                                
                                                //insertar cabecera
                                                $respGuiaCabeceraEntidad=$guiaCabeceraCarritoTemp->getMultiObj('id',$this->session->get('idCarritoTemporal'));
                                                $regCabecera=$respGuiaCabeceraEntidad->fetch_object();
                                                $guiaCabeceraEntidad->setDocumento('');
                                                $guiaCabeceraEntidad->setPedido($numPedido);
                                                $guiaCabeceraEntidad->setFecha(date('Y-m-d'));
                                                $guiaCabeceraEntidad->setUsuario($regCabecera->id_usuario);
                                                $guiaCabeceraEntidad->setCc00000id($regCabecera->id_cliente);
                                                $guiaCabeceraEntidad->setCc00002id($regCabecera->id_sucursal);
                                                $guiaCabeceraEntidad->setSubtotal($regCabecera->subtotal_cc_tem);
                                                $guiaCabeceraEntidad->setDescuento($regCabecera->descuento_cc_tem);
                                                $guiaCabeceraEntidad->setIva($regCabecera->iva_cc_tem);
                                                $guiaCabeceraEntidad->setTotal($regCabecera->total_cc_tem);
                                                $guiaCabeceraEntidad->setMonto_abonado($regCabecera->monto_abonado);
                                                $idCabecera=$guiaCabeceraEntidad->save();

                                                $guiaDetalle=$guiaDetalleCarritoTemp->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));

                                                while($regDetalle=$guiaDetalle->fetch_object()){

                                                    $respInv00000=$inv00000->getMultiObj('codigo',$regDetalle->id_producto);
                                                    $resgInv00000=$respInv00000->fetch_object();
                                                    $guiaDetalleEntidad->setCc30000id($idCabecera);
                                                    $guiaDetalleEntidad->setInv00000id($resgInv00000->id);
                                                    $guiaDetalleEntidad->setDescripcion($regDetalle->descripcion_producto);
                                                    $guiaDetalleEntidad->setCosto($regDetalle->costo_producto);
                                                    $guiaDetalleEntidad->setBodega($regDetalle->bodega_producto);
                                                    $guiaDetalleEntidad->setCantidad($regDetalle->cantidad_producto);
                                                    $guiaDetalleEntidad->setPrecio($regDetalle->precio_producto);
                                                    $guiaDetalleEntidad->setDescuento($regDetalle->descuento_producto);
                                                    $guiaDetalleEntidad->setSubtotal($regDetalle->subtotal_producto);
                                                    $guiaDetalleEntidad->setGuia($regDetalle->guia);
                                                    $guiaDetalleEntidad->setCantidad_guia($regDetalle->cantidad_guia);
                                                    $guiaDetalleEntidad->setMarca_producto($regDetalle->marca_producto);
                                                    $guiaDetalleEntidad->setDescuento_cliente($regDetalle->descuento_cliente);
                                                    $resp =$guiaDetalleEntidad->save();
                                                    if($resp){
                                                        $bandera=false;
                                                    }else{
                                                        $bandera=true;
                                                    }  
                                                }
                                                ///SI TODO ESTA BIEN COMPRUEBO LA BANDERA
                                            if($bandera==false){

                                                //GUARDAR MONTOS EN LA TABLA CC20010
                                                $Sis20010 = new Entidades\Sis20010($this->adapter);
                                                $Cc20010=new Entidades\Cc20010($this->adapter);
                                                $respMonto=$Sis20010->getMultiObj('documento',$this->session->get('idCarritoTemporal'));
                                                while($regMonto=$respMonto->fetch_object()){
                                                    
                                                    $Cc20010->setDocumento($numPedido);
                                                    $Cc20010->setFecha(date('Y-m-d'));
                                                    $Cc20010->setValor($regMonto->valor);
                                                    $Cc20010->setUsuario($regMonto->usuario);
                                                    $Cc20010->setCc40020id($regMonto->cc40020id);
                                                    $resp=$Cc20010->save();
                                                        if($resp){
                                                            $bandera=false;
                                                        }else{
                                                            $bandera=true;
                                                        }
                                                }

                                                if($bandera==false){
                                                    //ELIMINAR EL CARRITO TEMPORAL
                                                $guiaDetalleCarritoTemp->deleteMulti('id_cabecera',$this->session->get('idCarritoTemporal'));
                                                $guiaCabeceraCarritoTemp->deleteMulti('id',$this->session->get('idCarritoTemporal'));
                                                $Sis20010->deleteMulti('documento',$this->session->get('idCarritoTemporal'));
                                                $Gui40000->commit();
                                                echo 'OK';
                                                }
                                            }

                                            }else{
                                            echo  $verifi_Factura;
                                            }

                                          
                                        }else{
                                            echo $server_output;
                                        }
                            }else{
                            
                                echo $server_output;
                            }
    }

    public function guardarFacSolaPedidoAprobar(){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $pedido=$_GET['pedido'];

        $cc11000 = new Entidades\Cc11000($this->adapter);
        $respcc11000=$cc11000->getMultiObj('documento',$_GET['pedido']);
        $regcc11000=$respcc11000->fetch_object();
        $regcc11000->id;
        $id_cabecera=$regcc11000->id;

        $Sis00300 = new Entidades\Sis00300($this->adapter);
        $respSis00300=$Sis00300->getMultiObj('usuario',$regcc11000->usuario);
        $regSis00300=$respSis00300->fetch_object();
        $regSis00300->bodega;



 
       
        $guias= new \Models\Gui40000Model($this->adapter);
        $respBodega=$guias->select(strtoupper($regSis00300->bodega));
        $regBodega=$respBodega->fetch_object();
        $secuencial= new \Models\Gui30000Model($this->adapter);
        $numSecuencial= $secuencial->getNumGuia($regBodega->secuencial);


        

        $inv00000= new Entidades\Inv00000($this->adapter);
        //WEB SERVICE FACTURA
        //CLIENTE
        $clientes_model= new \Models\ClientesModel($this->adapter);
        $respCliente=$clientes_model->ListarClientesId($regcc11000->cc00000id);
        $regCliente=$respCliente->fetch_object();
        //NUM_PEDIDO
        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $numPedido2=$Gui40000->getMulti('bodega',strtoupper($regSis00300->bodega));
        $numPedido=$pedido;
        $docid=$numPedido2['docid'];
        $bodega=$numPedido2['bodega'];
        $Bachnmbr=$numPedido2['Bachnmbr'];
        //CC00002 CODIGODIRECCION
        $cc00002 = new Entidades\Cc00002($this->adapter);
        $respcc00002=$cc00002->getMulti('id',$regcc11000->cc00002id);
        $Prbtadcd=$respcc00002['codigodireccion'];
        //USUARIO-VENDEDORES
        $sis00300 = new Entidades\Sis00300($this->adapter);
        $respsis00300=$sis00300->getMulti('usuario',$regcc11000->usuario);
        $Slprsnid=$respsis00300['cod_vendedor'];

        //CABECERA CARRITO TEMPORAL
        $Cc11000 = new Entidades\Cc11000($this->adapter);
        $respCc11000=$Cc11000->getMultiObj('documento',$pedido);
        $respCc11000=$respCc11000->fetch_object();
        $SubTotal=$respCc11000->subtotal;


        //DESCEUENTO TOTAL PEDIDOS
        $Cc11010Model= new \Models\Cc10010Model($this->adapter);
        $respCc11010Model=$Cc11010Model->descuentoTotalPedidosAprobar($id_cabecera);
        $regCc11010Model= $respCc11010Model->fetch_object();
        $DescuentoTotal=$regCc11010Model->descuento;

        $data = array(
            'Ruc'=> $regCliente->ruc,//RUC CLIENTE
            'NumeroPedido' => $numPedido,//NUMERO PEDIDO
            'Fecha' => date('Y-m-d'),
            'DocID' => $docid,//GUI40000 DOCID
            'SubTotal' => $SubTotal+$DescuentoTotal,
            'DescuentoTotal' =>$DescuentoTotal,
            'Bodega' => $bodega,
            'Prbtadcd' =>$Prbtadcd,//CC000002 CODIGODIRECCION
            'Bachnmbr' =>$Bachnmbr,//GUI00004 Bachnmbr
            'Estado' =>'gp',// GP
            'Activo' =>1,
            'Slprsnid' =>$Slprsnid,//CODIGO VENDEDOR
            'Observacion' =>''
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=cabecera");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);

        if($server_output=='OK'){

              //detalles carrito temporal

        $Cc11010 = new Entidades\Cc11010($this->adapter);
        $respCc11010Obj=$Cc11010->getMultiObj('cc11000id',$id_cabecera);

        while($respCc11010=$respCc11010Obj->fetch_object()){

            $Articulo=$respCc11010->inv00000codigo;
            $Cantidad=$respCc11010->cantidad;
            $Precio=$respCc11010->precio;
            $Descuento=(float)$respCc11010->descuento+(float)$respCc11010->descuento_cliente;
    
                            $data = array(
                                'Ruc'=> $regCliente->ruc,
                                'NumeroPedido' => $numPedido,
                                'Articulo' => $Articulo,
                                'Cantidad' => $Cantidad,
                                'Precio' => $Precio,
                                'Descuento' =>$Descuento,
                                'Estado' => 'gp',
                                'Activo' => 1
                            );
                            $data = http_build_query($data);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=detalles");
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $server_output = curl_exec($ch);
                                    curl_close ($ch);
        }


         ///
         $data = array(
            'NumeroPedido' => $numPedido
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=actualizarDatos");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_outputRes = curl_exec($ch);
                curl_close ($ch);

        ///
                                //########################GUARDAR EN LABLAS CC300000 DE GUI

                        if($server_output=='OK'){

                            $data = array(
                                'NumeroPedido' => $numPedido
                            );
                            $data = http_build_query($data);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=facturaOK");
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $verifi_Factura = curl_exec($ch);
                                    curl_close ($ch);
                                if($verifi_Factura=='OK'){

                                     //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLASÇ
                                $guiaCabeceraEntidad = new Entidades\Cc30000($this->adapter);
                                $guiaCabeceraEntidad->autocommit();
                                $guiaDetalleEntidad = new Entidades\Cc30010($this->adapter);

                            
                                $Cc11000 = new Entidades\Cc11000($this->adapter);
                                $Cc11010= new Entidades\Cc11010($this->adapter);
                                
                                //insertar cabecera
                                $respGuiaCabeceraEntidad=$Cc11000->getMultiObj('documento',$pedido);
                                $regCabecera=$respGuiaCabeceraEntidad->fetch_object();
                                $guiaCabeceraEntidad->setDocumento('');
                                $guiaCabeceraEntidad->setPedido($pedido);
                                $guiaCabeceraEntidad->setFecha(date('Y-m-d'));
                                $guiaCabeceraEntidad->setUsuario($regCabecera->usuario);
                                $guiaCabeceraEntidad->setCc00000id($regCabecera->cc00000id);
                                $guiaCabeceraEntidad->setCc00002id($regCabecera->cc00002id);
                                $guiaCabeceraEntidad->setSubtotal($regCabecera->subtotal);
                                $guiaCabeceraEntidad->setDescuento($regCabecera->descuento);
                                $guiaCabeceraEntidad->setIva($regCabecera->iva);
                                $guiaCabeceraEntidad->setTotal($regCabecera->total);
                                $guiaCabeceraEntidad->setMonto_abonado(0);
                                $idCabecera=$guiaCabeceraEntidad->save();

                                $guiaDetalle=$Cc11010->getMultiObj('cc11000id',$id_cabecera);

                                while($regDetalle=$guiaDetalle->fetch_object()){

                                    $respInv00000=$inv00000->getMultiObj('codigo',$regDetalle->inv00000codigo);
                                    $resgInv00000=$respInv00000->fetch_object();

                                    $guiaDetalleEntidad->setCc30000id($idCabecera);
                                    $guiaDetalleEntidad->setInv00000id($resgInv00000->id);
                                    $guiaDetalleEntidad->setDescripcion($regDetalle->descripcion);
                                    $guiaDetalleEntidad->setCosto($regDetalle->costo);
                                    $guiaDetalleEntidad->setBodega($regDetalle->bodega);
                                    $guiaDetalleEntidad->setCantidad($regDetalle->cantidad);
                                    $guiaDetalleEntidad->setPrecio($regDetalle->precio);
                                    $guiaDetalleEntidad->setDescuento($regDetalle->descuento);
                                    $guiaDetalleEntidad->setSubtotal($regDetalle->subtotal);
                                    $guiaDetalleEntidad->setGuia($regDetalle->guia);
                                    $guiaDetalleEntidad->setCantidad_guia($regDetalle->cantidad_guia);
                                    $guiaDetalleEntidad->setMarca_producto($regDetalle->marca_producto);
                                    $guiaDetalleEntidad->setDescuento_cliente($regDetalle->descuento_cliente);
                                    $resp =$guiaDetalleEntidad->save();
                                    if($resp){
                                        $bandera=false;
                                    }else{
                                        $bandera=true;
                                    }  
                                }
                                ///SI TODO ESTA BIEN COMPRUEBO LA BANDERA
                            if($bandera==false){

                                    //ELIMINAR EL CARRITO TEMPORAL
                               
                                $Cc11000->updateMultiColum('aprobadoCobranza',1,'documento',$pedido);
                                // //AUMENTAR EL NUMERO DEL PEDIDO
                                // $valexplode = explode('-', $numPedido);
                                // $newsecuencial = (int) $valexplode[1];
                                // $newsecuencial += 1;
                                // switch (strlen($newsecuencial)) {
                                //     case 1:
                                //         $newsecuencial = '00000000'.$newsecuencial;
                                //         break;
                                //     case 2:
                                //         $newsecuencial = '0000000'.$newsecuencial;
                                //         break;
                                //     case 3:
                                //         $newsecuencial = '000000'.$newsecuencial;
                                //         break;
                                //     case 4:
                                //         $newsecuencial = '00000'.$newsecuencial;
                                //         break;
                                //     case 5:
                                //         $newsecuencial = '0000'.$newsecuencial;
                                //         break;
                                //     case 6:
                                //         $newsecuencial = '000'.$newsecuencial;
                                //         break;
                                //     case 7:
                                //         $newsecuencial = '00'.$newsecuencial;
                                //         break;
                                //     case 8:
                                //         $newsecuencial = '0'.$newsecuencial;
                                //         break;
                                // }
                                // $Gui40000->updateMultiColum('numpedido', $valexplode[0].'-'.$newsecuencial, 'bodega',strtoupper($this->session->get('bodUsuario')));
                                 $Gui40000->commit();
                                echo 'OK';
                               
                            }

                                }else{
                                    echo $verifi_Factura;
                                }

                               
                        }else{
                            echo $server_output;
                        }
            }else{
                echo $server_output;
            }
    }

    public function guardarFacSolaPedido(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $pedido=$_GET['pedido'];
        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respcc10000->fetch_object();
        $id_cabecera=$regcc10000->id;

       
        $guias= new \Models\Gui40000Model($this->adapter);
        $respBodega=$guias->select(strtoupper($this->session->get('bodUsuario')));
        $regBodega=$respBodega->fetch_object();
        $secuencial= new \Models\Gui30000Model($this->adapter);
        $numSecuencial= $secuencial->getNumGuia($regBodega->secuencial);

        $inv00000= new Entidades\Inv00000($this->adapter);
        //WEB SERVICE FACTURA
        //CLIENTE
        $clientes_model= new \Models\ClientesModel($this->adapter);
        $respCliente=$clientes_model->ListarClientesId($regcc10000->cc00000id);
        $regCliente=$respCliente->fetch_object();
        //NUM_PEDIDO
        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $numPedido2=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
        $numPedido=$pedido;
        $docid=$numPedido2['docid'];
        $bodega=$numPedido2['bodega'];
        $Bachnmbr=$numPedido2['Bachnmbr'];
        //CC00002 CODIGODIRECCION
        $cc00002 = new Entidades\Cc00002($this->adapter);
        $respcc00002=$cc00002->getMulti('id',$regcc10000->cc00002id);
        $Prbtadcd=$respcc00002['codigodireccion'];
        //USUARIO-VENDEDORES
        $sis00300 = new Entidades\Sis00300($this->adapter);
        $respsis00300=$sis00300->getMulti('usuario',$this->session->get('usuario'));
        $Slprsnid=$respsis00300['cod_vendedor'];

        //CABECERA CARRITO TEMPORAL
        $Cc10000 = new Entidades\Cc10000($this->adapter);
        $respCc10000=$Cc10000->getMultiObj('documento',$pedido);
        $respCc10000=$respCc10000->fetch_object();
        $SubTotal=$respCc10000->subtotal;


        //DESCEUENTO TOTAL PEDIDOS
        $Cc10010Model= new \Models\Cc10010Model($this->adapter);
        $respCc10010Model=$Cc10010Model->descuentoTotalPedidos($id_cabecera);
        $regCc10010Model= $respCc10010Model->fetch_object();
        $DescuentoTotal=$regCc10010Model->descuento;

        $data = array(
            'Ruc'=> $regCliente->ruc,//RUC CLIENTE
            'NumeroPedido' => $numPedido,//NUMERO PEDIDO
            'Fecha' => date('Y-m-d'),
            'DocID' => $docid,//GUI40000 DOCID
            'SubTotal' => $SubTotal+$DescuentoTotal,
            'DescuentoTotal' =>$DescuentoTotal,
            'Bodega' => $bodega,
            'Prbtadcd' =>$Prbtadcd,//CC000002 CODIGODIRECCION
            'Bachnmbr' =>$Bachnmbr,//GUI00004 Bachnmbr
            'Estado' =>'gp',// GP
            'Activo' =>1,
            'Slprsnid' =>$Slprsnid,//CODIGO VENDEDOR
            'Observacion' =>''
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=cabecera");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);

        if($server_output=='OK'){

              //detalles carrito temporal

        $Cc10010 = new Entidades\Cc10010($this->adapter);
        $respCc10010Obj=$Cc10010->getMultiObj('cc10000id',$id_cabecera);

        while($respCc10010=$respCc10010Obj->fetch_object()){

            $Articulo=$respCc10010->inv00000codigo;
            $Cantidad=$respCc10010->cantidad;
            $Precio=$respCc10010->precio;
            $Descuento=(float)$respCc10010->descuento+(float)$respCc10010->descuento_cliente;
    
                            $data = array(
                                'Ruc'=> $regCliente->ruc,
                                'NumeroPedido' => $numPedido,
                                'Articulo' => $Articulo,
                                'Cantidad' => $Cantidad,
                                'Precio' => $Precio,
                                'Descuento' =>$Descuento,
                                'Estado' => 'gp',
                                'Activo' => 1
                            );
                            $data = http_build_query($data);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=detalles");
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $server_output = curl_exec($ch);
                                    curl_close ($ch);
        }

          ///
          $data = array(
            'NumeroPedido' => $numPedido
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=actualizarDatos");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_outputRes = curl_exec($ch);
                curl_close ($ch);

        ///

                                //########################GUARDAR EN LABLAS CC300000 DE GUI

                        if($server_output=='OK'){

                            $data = array(
                                'NumeroPedido' => $numPedido
                            );
                            $data = http_build_query($data);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=facturaOK");
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $verifi_Factura = curl_exec($ch);
                                    curl_close ($ch);
                                if($verifi_Factura=='OK'){

                                     //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLASÇ
                                $guiaCabeceraEntidad = new Entidades\Cc30000($this->adapter);
                                $guiaCabeceraEntidad->autocommit();
                                $guiaDetalleEntidad = new Entidades\Cc30010($this->adapter);

                            
                                $Cc10000 = new Entidades\Cc10000($this->adapter);
                                $Cc10010= new Entidades\Cc10010($this->adapter);
                                
                                //insertar cabecera
                                $respGuiaCabeceraEntidad=$Cc10000->getMultiObj('documento',$pedido);
                                $regCabecera=$respGuiaCabeceraEntidad->fetch_object();
                                $guiaCabeceraEntidad->setDocumento('');
                                $guiaCabeceraEntidad->setPedido($pedido);
                                $guiaCabeceraEntidad->setFecha(date('Y-m-d'));
                                $guiaCabeceraEntidad->setUsuario($regCabecera->usuario);
                                $guiaCabeceraEntidad->setCc00000id($regCabecera->cc00000id);
                                $guiaCabeceraEntidad->setCc00002id($regCabecera->cc00002id);
                                $guiaCabeceraEntidad->setSubtotal($regCabecera->subtotal);
                                $guiaCabeceraEntidad->setDescuento($regCabecera->descuento);
                                $guiaCabeceraEntidad->setIva($regCabecera->iva);
                                $guiaCabeceraEntidad->setTotal($regCabecera->total);
                                $guiaCabeceraEntidad->setMonto_abonado(0);
                                $idCabecera=$guiaCabeceraEntidad->save();

                                $guiaDetalle=$Cc10010->getMultiObj('cc10000id',$id_cabecera);

                                while($regDetalle=$guiaDetalle->fetch_object()){

                                    $respInv00000=$inv00000->getMultiObj('codigo',$regDetalle->inv00000codigo);
                                    $resgInv00000=$respInv00000->fetch_object();

                                    $guiaDetalleEntidad->setCc30000id($idCabecera);
                                    $guiaDetalleEntidad->setInv00000id($resgInv00000->id);
                                    $guiaDetalleEntidad->setDescripcion($regDetalle->descripcion);
                                    $guiaDetalleEntidad->setCosto($regDetalle->costo);
                                    $guiaDetalleEntidad->setBodega($regDetalle->bodega);
                                    $guiaDetalleEntidad->setCantidad($regDetalle->cantidad);
                                    $guiaDetalleEntidad->setPrecio($regDetalle->precio);
                                    $guiaDetalleEntidad->setDescuento($regDetalle->descuento);
                                    $guiaDetalleEntidad->setSubtotal($regDetalle->subtotal);
                                    $guiaDetalleEntidad->setGuia($regDetalle->guia);
                                    $guiaDetalleEntidad->setCantidad_guia($regDetalle->cantidad_guia);
                                    $guiaDetalleEntidad->setMarca_producto($regDetalle->marca_producto);
                                    $guiaDetalleEntidad->setDescuento_cliente($regDetalle->descuento_cliente);
                                    $resp =$guiaDetalleEntidad->save();
                                    if($resp){
                                        $bandera=false;
                                    }else{
                                        $bandera=true;
                                    }  
                                }
                                ///SI TODO ESTA BIEN COMPRUEBO LA BANDERA
                            if($bandera==false){

                                //     //ELIMINAR EL CARRITO TEMPORAL
                                $Cc10010->deleteMulti('cc10000id',$id_cabecera);
                                $Cc10000->deleteMulti('documento',$pedido);
                                // //AUMENTAR EL NUMERO DEL PEDIDO
                                // $valexplode = explode('-', $numPedido);
                                // $newsecuencial = (int) $valexplode[1];
                                // $newsecuencial += 1;
                                // switch (strlen($newsecuencial)) {
                                //     case 1:
                                //         $newsecuencial = '00000000'.$newsecuencial;
                                //         break;
                                //     case 2:
                                //         $newsecuencial = '0000000'.$newsecuencial;
                                //         break;
                                //     case 3:
                                //         $newsecuencial = '000000'.$newsecuencial;
                                //         break;
                                //     case 4:
                                //         $newsecuencial = '00000'.$newsecuencial;
                                //         break;
                                //     case 5:
                                //         $newsecuencial = '0000'.$newsecuencial;
                                //         break;
                                //     case 6:
                                //         $newsecuencial = '000'.$newsecuencial;
                                //         break;
                                //     case 7:
                                //         $newsecuencial = '00'.$newsecuencial;
                                //         break;
                                //     case 8:
                                //         $newsecuencial = '0'.$newsecuencial;
                                //         break;
                                // }
                                // $Gui40000->updateMultiColum('numpedido', $valexplode[0].'-'.$newsecuencial, 'bodega',strtoupper($this->session->get('bodUsuario')));
                                $Gui40000->commit();
                                echo 'OK';
                               
                            }

                                }else{
                                    echo $verifi_Factura;
                                }

                               
                        }else{
                            echo $server_output;
                        }
            }else{
                echo $server_output;
            }
    }

    public function listFacturas(){
            if (empty($this->session->get('usuario'))) $this->redirect("default","login");
            $contenedor = globalFunctions::renderizar($this->website,array(
                'section'=>array(
                    'layout_section'=> $this->layout_guia->listarFacturas(),
                )
            ));
            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Facturas"
            ));
    }

    public function listarFacturas(){

        $Cc30000 =new \Models\Cc30000Model($this->adapter);
        $respCc30000=$Cc30000->listarFacturas($this->session->get('usuario'));
        $data= Array();
        while ($regCc30000=$respCc30000->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" title="Listar detalle de factura" onclick="listarDetalle('."'".$regCc30000->pedido."'".')"><span class="fa fa-list"></span></button>',
                "1"=>$regCc30000->documento,
                "2"=>$regCc30000->pedido,
                "3"=>$regCc30000->ruc,
                "4"=>$regCc30000->razonsocial,
                "5"=>$regCc30000->direccion,
                "6"=>$regCc30000->fecha,
                "7"=>$regCc30000->usuario,
                "8"=>$regCc30000->total,
                "9"=>$regCc30000->monto_abonado,
                "10"=>$regCc30000->pendiente);
    }
    $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
    echo json_encode($results);

    }

    public function listarDetalleFactura($param=array()){

        $Cc30000 =new \Models\Cc30000Model($this->adapter);

        $respCc30000=$Cc30000->listarDetalle($param['documento']);
        $data= Array();
        while ($regCc30000=$respCc30000->fetch_object()){
            $data[]=array(
                "0"=>$regCc30000->codigo,
                "1"=>$regCc30000->descripcion,
                "2"=>$regCc30000->marca_producto,
                "3"=>$regCc30000->cantidad,
                "4"=>$regCc30000->precio,
                "5"=>$regCc30000->descuento,
                "6"=>$regCc30000->subtotal,
                );
    }
    $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
    echo json_encode($results);

    }


 

    public function listarDetallePedidoAprobar($param=array()){

        $Cc30000 =new \Models\Cc30000Model($this->adapter);

        $respCc30000=$Cc30000->listarDetallePedidoAprobar($param['documento']);

        $data= Array();
        while ($regCc30000=$respCc30000->fetch_object()){
            $data[]=array(
                "0"=>$regCc30000->codigo,
                "1"=>$regCc30000->descripcion,
                "2"=>$regCc30000->marca_producto,
                "3"=>$regCc30000->cantidad,
                "4"=>$regCc30000->precio,
                "5"=>$regCc30000->descuento,
                "6"=>$regCc30000->subtotal,
                );
    }
    $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
    echo json_encode($results);

    }

    public function validarClave($param=array()){

        $Sis00100 = new Entidades\Sis00100($this->adapter);
        $respSis00100=$Sis00100->getMultiObj('id',1)->fetch_object();
        if($respSis00100->clave_cobranzas==$param['clave']){
            echo 1;
        }else{
            echo 'ERROR DE CLAVE';
        }

    }

}
