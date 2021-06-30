<?php
defined('BASEPATH') or exit('No se permite acceso directo');


class ProformasController extends Controllers
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
        $this->layout_proformas = new dti_layout_proformas($this->website);
    }
    
    public function exec()
    {
        $this->listarClientes();
    }

    public function index(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        //llamar cliente y sucursal

         //DATOS CLIENTE
         $clientesModel= new \Models\ClientesModel($this->adapter);
         $respClienteModel=$clientesModel->ListarClientesId($this->session->get('idCliente'));
         $regClienteModel=$respClienteModel->fetch_object();
         $cliente=array(
             'id'=>$regClienteModel->id,
             'ruc'=>$regClienteModel->ruc,
             'cliente'=>$regClienteModel->cliente,
             'razonsocial'=>$regClienteModel->razonsocial,
             'descuento'=>$regClienteModel->descuento,
             'correo'=>$regClienteModel->correo,
             'cupoPermitido'=>$this->session->get('cupoPermitido'),
             'condicionpago'=>$regClienteModel->condicionpago,
             'categoria'=>$regClienteModel->categoria
           
         );

         $_SESSION['condicionpago']=$regClienteModel->condicionpago;
 
         //DATOS SUCURSAL
         $sucursalesModel= new \Models\SucursalesModel($this->adapter);
         $respSucursalModel=$sucursalesModel->ListarSucursalesId($this->session->get('idSucursalCliente'));
         $regSucursalModel=$respSucursalModel->fetch_object();
         $sucursal=array(
             'codigodireccion'=>$regSucursalModel->codigodireccion,
             'telefono'=>$regSucursalModel->telefono,
             'ciudad'=>$regSucursalModel->ciudad,
             'provincia'=>$regSucursalModel->provincia,
             'direccion'=>$regSucursalModel->direccion
         );

         $Sis50200 = new Entidades\Sis50200($this->adapter);
         $respSis50200=$Sis50200->getMultiObj('id',$this->session->get('idCarritoTemporal'));
         $regSis50200=$respSis50200->fetch_object();



        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_proformas->listarProductos($cliente,$sucursal).' '.$this->layout_proformas->asideCarrito($regSis50200->descuento_porce_cc),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Productos"
        ));

    }

    public function listarProductos($param=array()){ 

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        if($this->session->get('idCliente')>0){
            //DATOS CLIENTE
            $clientesModel= new \Models\ClientesModel($this->adapter);
            $respClienteModel=$clientesModel->ListarClientesId($this->session->get('idCliente'));
            $regClienteModel=$respClienteModel->fetch_object();
            
               
            //codigo de listar 
            $conf= new \Models\ProformasModel($this->adapter);
            $rspta=$conf->listar($this->session->get('nivelprecio'));
            $data= Array();

             while ($reg=$rspta->fetch_object()){
            //PORCENTAJE PRODUCTO
           
            $precioDescuento='';
            $descuentoClienteTotal=0;
            $descuentoCliente=0;
            $precioiva=0;

            if($regClienteModel->descuento>0){
                // $precioDescuento='<strike>'.$reg->precio.'</strike><br>';

                // $descuentoClienteTotal=$reg->precio-(($reg->precio*$regClienteModel->descuento)/100);
                // $descuentoClienteTotal=number_format((float)$descuentoClienteTotal, 2, '.', '');
               
                // $descuentoCliente=($reg->precio*$regClienteModel->descuento)/100;
                // $descuentoCliente=number_format((float)$descuentoCliente, 2, '.', '');


                // $precioDescuento.='<b>'.$descuentoClienteTotal.'</b>';

                // $precioiva=number_format((float)($descuentoClienteTotal+($descuentoClienteTotal*0.12)), 2, '.', '');

                $precioDescuento='<b>'.$reg->precio.'</b>';
                $precioiva=number_format((float)($reg->precio+($reg->precio*0.12)), 2, '.', '');
                
            }else{
                $precioDescuento='<b>'.$reg->precio.'</b>';
                $precioiva=number_format((float)($reg->precio+($reg->precio*0.12)), 2, '.', '');
            }

             $stockBodega=0;
             $reg->descripcion=str_replace('"',"",$reg->descripcion);
             $reg->descripcion = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $reg->descripcion);
             $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
             $reemplazar=array("", "", "", "");
             $reg->descripcion=str_ireplace($buscar,$reemplazar,$reg->descripcion);
                    $data[]=array(
                        "0"=>'<button class="btn btn-warning" title="Agregar a carriro" onclick="agregarDetalle('."'".$reg->codigo."'".',\''.$reg->precio.'\',\''.$reg->descripcion.'\',\''.strtoupper($this->session->get('bodUsuario')).'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-plus"></span></button> '.
                        ' <button data-target="#ajax" title="Mostrar stock de todas las bodegas" data-toggle="modal" class="btn btn-info" onclick="consultarStock('."'".$reg->codigo."'".','."'".strtoupper($this->session->get('bodUsuario'))."'".',\''.$reg->precio.'\',\''.$reg->descripcion.'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-search"></span></button> ',
                        "1"=>$reg->codigo,
                        "2"=>$reg->descripcion,
                        "3"=>$reg->codoriginal1,
                        "4"=>$reg->marcaproducto,
                        "5"=>'',
                        //"3"=>$reg->descripcioncorta,
                        "6"=>'<b><h5>$.'.$precioiva.'</h5></b>');
                       // "7"=>'$.'.$precioDescuento);
                    }
                    $results = array(
                        "sEcho"=>1, 
                        "iTotalRecords"=>count($data), 
                        "iTotalDisplayRecords"=>count($data), 
                        "aaData"=>$data);
             echo json_encode($results);
        }else{
            if (empty($this->session->get('usuario'))) $this->redirect("proformas","listarclientes");
        } 

    }

    public function listarProductosBusqueda($param=array()){

           

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        if($this->session->get('idCliente')>0){

            //DATOS CLIENTE
         $clientesModel= new \Models\ClientesModel($this->adapter);
         $respClienteModel=$clientesModel->ListarClientesId($this->session->get('idCliente'));
         $regClienteModel=$respClienteModel->fetch_object();

            //codigo de listar 
            $conf= new \Models\ProformasModel($this->adapter);
            $param['busqueda']=str_replace(" ","%",$param['busqueda']);
            $rspta=$conf->ListarBusqueda($this->session->get('nivelprecio'),$param['busqueda']);
            $data= Array();
             while ($reg=$rspta->fetch_object()){
            $rsptaDesc=$conf->TieneDescuento($reg->codigo)->fetch_object();
            $rsptaDescBodeg=$conf->TieneDescuento($reg->codigo);
            $descrpJs="";

            if(!empty($rsptaDesc)){

                $reg->descripcion=str_replace('"',"",$reg->descripcion);
                $reg->descripcion = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $reg->descripcion);
                $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
                $reemplazar=array("", "", "", "");
                $reg->descripcion=str_ireplace($buscar,$reemplazar,$reg->descripcion);
                $descrpJs=$reg->descripcion;

                if($this->session->get('usuario')!='888'){

                    $bandera=false;
                    $descuento=0;
                    while($rowBode=$rsptaDescBodeg->fetch_object()){
                        if($rowBode->bodega==$this->session->get('bodUsuario')){
                            $bandera=true;
                            $descuento=$rowBode->descuento;
                        }
                    }

                    if($bandera){
                        
                        $reg->descripcion="<p>  $reg->descripcion <em style='color: red'>**PROMOCION ".$descuento."% DESC - <strong style='color: red'>SOLO EN ".$this->session->get('bodUsuario')."</strong>** </em> </p>";
                        
                            $reg->precio=$reg->precio-($reg->precio*$descuento)/100;
                    }else{

                        $reg->descripcion="<p>  $reg->descripcion <em style='color: red'>**PROMOCION $rsptaDesc->descuento% DESC - <strong style='color: red'>SOLO EN ".$rsptaDesc->bodega."</strong>** </em> </p>";
                       
                }



                }
                
                
            }else{
                $reg->descripcion=str_replace('"',"",$reg->descripcion);
                $reg->descripcion = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $reg->descripcion);
                $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
                $reemplazar=array("", "", "", "");
                $reg->descripcion=str_ireplace($buscar,$reemplazar,$reg->descripcion);
                $descrpJs=$reg->descripcion;
            }

            

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

		  $Cc11000  = new Entidades\Cc11000 ($this->adapter);
                    $Cc11010  = new Entidades\Cc11010 ($this->adapter);
                    $Sis00300  = new Entidades\Sis00300 ($this->adapter);
                    $respCc11000=$Cc11000->getMultiObj('aprobadoCobranza','0');
                    while($regCc11000=$respCc11000->fetch_object()){
        
                        $respSis00300=$Sis00300->getMultiObj('usuario',$regCc11000->usuario)->fetch_object();
                        //if($this->session->get('bodUsuario')==$respSis00300->bodega){
                            $respcc11010=$Cc11010->getMultiObj('cc11000id',$regCc11000->id,'inv00000codigo',$reg->codigo,'bodega',$this->session->get('bodUsuario'))->fetch_object();
                            if(!empty($respcc11010)){
                                $stock_producto=$stock_producto-$respcc11010->cantidad;
                            }
                        //} 
                    }

                    $Cc10010= new \Models\Cc10010Model($this->adapter);


                    $respP=$Cc10010->countStockCarritoPedidos($reg->codigo,$this->session->get('bodUsuario'));
                    $regP=$respP->fetch_object();
                    $stock_producto=$stock_producto-$regP->CANTIDAD;
 
                    if($stock_producto<0){
                     $stock_producto=0;
                    }
                    ////######################


           // FIN STOCK BODEGA

           //PORCENTAJE PRODUCTO
           $precioDescuento='';
           $descuentoClienteTotal=0;
           $descuentoCliente=0;

           $precioiva=0;
           
           if($regClienteModel->descuento>0){
               $precioDescuento='<strike>'.$reg->precio.'</strike><br>';

               $descuentoClienteTotal=$reg->precio-(($reg->precio*$regClienteModel->descuento)/100);
               $descuentoClienteTotal=number_format((float)$descuentoClienteTotal, 2, '.', '');
               $descuentoCliente=($reg->precio*$regClienteModel->descuento)/100;
               $descuentoCliente=number_format((float)$descuentoCliente, 2, '.', '');


               $precioiva=number_format((float)($descuentoClienteTotal+($descuentoClienteTotal*0.12)), 2, '.', '');
               $precioDescuento.='<b>'.$descuentoClienteTotal.'</b>';
               
           }else{
               $precioiva=number_format((float)($reg->precio+($reg->precio*0.12)), 2, '.', '');
               $precioDescuento='<b>'.$reg->precio.'</b>';
           }

           $reg->codigo=str_replace(" ","",$reg->codigo);

            $data[]=array(

            
           
                "0"=>'<button class="btn btn-warning" title="Agregar a carriro" onclick="agregarDetalle('."'".$reg->codigo."'".',\''.$reg->precio.'\',\''.$descrpJs.'\',\''.strtoupper($this->session->get('bodUsuario')).'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-plus"></span></button> '.
                ' <button data-target="#ajax" title="Mostrar stock de todas las bodegas" data-toggle="modal" class="btn btn-info" onclick="consultarStock('."'".$reg->codigo."'".','."'".strtoupper($this->session->get('bodUsuario'))."'".',\''.$reg->precio.'\',\''.$descrpJs.'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-search"></span></button> ',
                "1"=>$reg->codigo,
                "2"=>$reg->descripcion,
                "3"=>$reg->codoriginal1,
                "4"=>$reg->marcaproducto,
                "5"=>'<b>'.$stock_producto.'</b>',
                //"3"=>$reg->descripcioncorta,
                "6"=>'<b><h5>$.'.$precioiva.'</h5></b>');
              //  "7"=>'$.'.$precioDescuento);
                
               
                //"5"=>$server_output,
                //"7"=>$reg->linea,
                // "7"=>$reg->sublinea,
                // "8"=>$reg->marcavehiculo,
                // "9"=>$reg->modelo,
                
                //"12"=>$reg->codoriginal1,
               // "11"=>$reg->codanterior);
             }
             $results = array(
                 "sEcho"=>1, 
                 "iTotalRecords"=>count($data), 
                 "iTotalDisplayRecords"=>count($data), 
                 "aaData"=>$data);
             echo json_encode($results);
        }else{
            if (empty($this->session->get('usuario'))) $this->redirect("proformas","listarclientes");
        }  
    }

    public function listarProductosStock($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        if($this->session->get('idCliente')>0){

            //codigo de listar 

            $conf= new \Models\ProformasModel($this->adapter);
            $rspta=$conf->listar($this->session->get('nivelprecio'));
            $data= Array();

            //stock de productos
          
             while ($reg=$rspta->fetch_object()){
             $stockBodega=0;

            // echo $reg->codigo.'<br>';
            // echo strtoupper($this->session->get('bodUsuario')).'<br>';
            // die();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproductobodega");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"codigo=".$reg->codigo."&bodega=".strtoupper($this->session->get('bodUsuario')));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            curl_close ($ch);
            // print_r($server_output);
            // die();

            if(trim($reg->codigo)=='VTAS-SERV-LOG'){
                $server_output=100;
            }else{
                $server_output=intval($server_output);
            }
           
           
             $reg->descripcion=str_replace('"',"",$reg->descripcion);
             $reg->descripcion = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $reg->descripcion);
             

             $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
             $reemplazar=array("", "", "", "");
             $reg->descripcion=str_ireplace($buscar,$reemplazar,$reg->descripcion);
                 $data[]=array(

                                                                        
                     
             "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('."'".$reg->codigo."'".',\''.$reg->precio.'\',\''.$reg->descripcion.'\',\''.$reg->costo.'\',\''.$this->session->get('descVendedor').'\')"><span class="fa fa-plus"></span></button>',
             "1"=>$reg->codigo,
             "2"=>$reg->descripcion,
             "3"=>$reg->descripcioncorta,
             "4"=>$reg->precio,
             "5"=>($reg->precio+($reg->precio*0.12)),
             "6"=>$server_output,
             "7"=>$reg->linea,
             "8"=>$reg->sublinea,
             "9"=>$reg->marcavehiculo,
             "10"=>$reg->modelo,
             "11"=>$reg->marcaproducto,
             "12"=>$reg->codoriginal1,
             "13"=>$reg->codanterior);
             }
             $results = array(
                 "sEcho"=>1, 
                 "iTotalRecords"=>count($data), 
                 "iTotalDisplayRecords"=>count($data), 
                 "aaData"=>$data);
             echo json_encode($results);

        }else{

            if (empty($this->session->get('usuario'))) $this->redirect("proformas","listarclientes");
        }
 
        
    }

    public function listarClientes(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_proformas->listarClientes(),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Clientes"
        ));
    }

    public function listClientesAjax(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\ClientesModel($this->adapter);
        if($this->session->get('usuario')=="888"){
            
            $rspta=$conf->ListarClientesMM();

        }else{
            $rspta=$conf->ListarClientes();
        }
        
        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>'<button class="btn btn-warning" title="Seleccionar cliente" onclick="openModalSucur('.$reg->id.','."'".$reg->nivelprecio."'".','."'".$reg->ruc."'".')"><span class="fa fa-plus"></span></button>'.
                    ' <button class="btn btn-info" title="Estado de cuenta" onclick="detallesCuenta('."'".$reg->ruc."'".')"><span class="fa fa-eye"></span></button>',
                    "1"=>$reg->ruc,
                    "2"=>$reg->cliente,
                    "3"=>$reg->razonsocial,
                    "4"=>$reg->vendedor,
                    "5"=>$reg->nivelprecio,
                    "6"=>$reg->direccion,
                    "7"=>$reg->telefono,
                    "8"=>$reg->ciudad,
                    "9"=>$reg->categoria);
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
    }

    public function guardarCliente($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        //estado de cuenta
        $cc00000  = new Entidades\Cc00000 ($this->adapter);
        $cc00000Resp=$cc00000->getMultiObj('id',$param['id']);
        $regcc00000=$cc00000Resp->fetch_object();


        $data = array(
            'ruc'=>$regcc00000->ruc
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=clienteBloqueo");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);
        if($server_output=="1"){
            echo 'bloqueo';
        }else{

            $data = array(
                'ruc'=>$regcc00000->ruc
            );
            $data = http_build_query($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=estado_cuenta");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    curl_close ($ch);
    
                    if($server_output=='false' || $server_output==false){
                        $cupoPermitido=0;
                    }else{
                        $someArray = json_decode($server_output, true);
                        $cupoPermitido=(float)(number_format($someArray['CUPO'],2,'.',''))-((float)(number_format($someArray['CH_POSF'],2,'.',''))+(float)(number_format($someArray['CH_PROT'],2,'.',''))+(float)(number_format($someArray['DocVencidos'],2,'.',''))+(float)(number_format($someArray['DocVencer'],2,'.',''))-(float)(number_format($someArray['NC'],2,'.','')));
                    }
    
                    $conf= new \Models\CarritoModel($this->adapter);
                    $this->session->add('idCliente',$param['id']);
                    $this->session->add('nivelprecio',$param['nivelprecio']);
                    $this->session->add('idSucursalCliente',$param['idSucursalCliente']);
                    $this->session->add('cupoPermitido',$cupoPermitido);
                    $this->session->add('categoria',$regcc00000->categoria);
                    //die($this->session->get("categoria"));
                    if($cupoPermitido<0){
                        $clientesModel= new \Models\ClientesModel($this->adapter);
                        $resp=$clientesModel->clienteAprobadoCobranza($regcc00000->ruc);
                        $respCliCob=$resp->fetch_object();
                        if(empty($respCliCob))
                            {
                                echo 'clave';
                            }else{
                                $reg=$conf->ListarCabeceraCarrito($this->session->get('usuario'),$this->session->get('idCliente'),$this->session->get('idSucursalCliente'));
                                    $respu=$reg->fetch_object();
                                    if(empty($respu))
                                    {
                                    $this->session->add('idCarritoTemporal',$conf->GuardarCabecera($this->session->get('usuario'),$this->session->get('idCliente'),$this->session->get('idSucursalCliente'),date('Y-m-d'),'0','0','0','0'));
                                        echo $this->session->get('idCarritoTemporal');
                                    }else{
                                        $this->session->add('idCarritoTemporal',$respu->id);
                                        echo  $this->session->get('idCarritoTemporal');
                                    }
                            }
                    }else{
                         //VERIFICAR SI EXISTE UNA CABECERA SI NO CREAR UNA NUEVA 
                    $reg=$conf->ListarCabeceraCarrito($this->session->get('usuario'),$this->session->get('idCliente'),$this->session->get('idSucursalCliente'));
                    $respu=$reg->fetch_object();
                    if(empty($respu))
                    {
                    $this->session->add('idCarritoTemporal',$conf->GuardarCabecera($this->session->get('usuario'),$this->session->get('idCliente'),$this->session->get('idSucursalCliente'),date('Y-m-d'),'0','0','0','0'));
                        echo $this->session->get('idCarritoTemporal');
                    }else{
                        $this->session->add('idCarritoTemporal',$respu->id);
                        echo  $this->session->get('idCarritoTemporal');
                    }
                    }  

        }


             
    }

    public function guardarClienteClave($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        //estado de cuenta
        $cc00000  = new Entidades\Cc00000 ($this->adapter);
        $cc00000Resp=$cc00000->getMultiObj('id',$param['id']);
        $regcc00000=$cc00000Resp->fetch_object();

        $data = array(
            'ruc'=>$regcc00000->ruc
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=estado_cuenta");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);

                if($server_output=='false' || $server_output==false){
                    $cupoPermitido=0;
                }else{
                    $someArray = json_decode($server_output, true);
                    $cupoPermitido=(float)(number_format($someArray['CUPO'],2,'.',''))-((float)(number_format($someArray['CH_POSF'],2,'.',''))+(float)(number_format($someArray['CH_PROT'],2,'.',''))+(float)(number_format($someArray['DocVencidos'],2,'.',''))+(float)(number_format($someArray['DocVencer'],2,'.',''))-(float)(number_format($someArray['NC'],2,'.','')));
                }

                $conf= new \Models\CarritoModel($this->adapter);
                $this->session->add('idCliente',$param['id']);
                $this->session->add('nivelprecio',$param['nivelprecio']);
                $this->session->add('idSucursalCliente',$param['idSucursalCliente']);
                $this->session->add('cupoPermitido',$cupoPermitido);
               
                     //VERIFICAR SI EXISTE UNA CABECERA SI NO CREAR UNA NUEVA 
                $reg=$conf->ListarCabeceraCarrito($this->session->get('usuario'),$this->session->get('idCliente'),$this->session->get('idSucursalCliente'));
                $respu=$reg->fetch_object();
                if(empty($respu))
                {
                $this->session->add('idCarritoTemporal',$conf->GuardarCabecera($this->session->get('usuario'),$this->session->get('idCliente'),$this->session->get('idSucursalCliente'),date('Y-m-d'),'0','0','0','0'));
                    echo $this->session->get('idCarritoTemporal');
                }else{
                    $this->session->add('idCarritoTemporal',$respu->id);
                    echo  $this->session->get('idCarritoTemporal');
                }
                      
    }



    public function listClientesBusquedaAjax($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\ClientesModel($this->adapter);
        $param['busqueda']=str_replace(" ","%",$param['busqueda']);

        if($this->session->get('usuario')=="888"){
            $rspta=$conf->ListarClientesMM();
        }else{
            $rspta=$conf->ListarClientesBusqueda($param['busqueda']);
        }

       
        $data= Array();
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
         "0"=>'<button class="btn btn-warning" title="Selecionar cliente" onclick="openModalSucur('.$reg->id.','."'".$reg->nivelprecio."'".','."'".$reg->ruc."'".')"><span class="fa fa-plus"></span></button>'.
         ' <button class="btn btn-info" title="Estado de cuenta" onclick="detallesCuenta('."'".$reg->ruc."'".')"><span class="fa fa-eye"></span></button>',
         "1"=>$reg->ruc,
         "2"=>$reg->cliente,
         "3"=>$reg->razonsocial,
         "4"=>$reg->vendedor,
         "5"=>$reg->nivelprecio,
         "6"=>$reg->direccion,
         "7"=>$reg->telefono,
         "8"=>$reg->ciudad,
         "9"=>$reg->categoria
        );
 		}
 		$results = array(
 			"sEcho"=>1, 
 			"iTotalRecords"=>count($data), 
 			"iTotalDisplayRecords"=>count($data), 
             "aaData"=>$data);
 		echo json_encode($results);
    }


    public function listSucursalesClientes($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        $idCliente=$param['idCliente'];
        $nivelprecio=$param['nivelprecio'];
        $rucCliente=$param['rucCliente'];

        $conf= new \Models\SucursalesModel($this->adapter);
        $rspta=$conf->ListarSucursales($rucCliente);

        $data= Array();
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
         "0"=>'<button class="btn btn-warning" title="Seleccionar sucursal" onclick="selectCliente('.$idCliente.','."'".$nivelprecio."'".','."'".$reg->id."'".')"><span class="fa fa-plus"></span></button>',
         "1"=>$reg->codigodireccion,
         "2"=>$reg->provincia,
         "3"=>$reg->ciudad,
         "4"=>$reg->direccion);
 		}
 		$results = array(
 			"sEcho"=>1, 
 			"iTotalRecords"=>count($data), 
 			"iTotalDisplayRecords"=>count($data), 
             "aaData"=>$data);
 		echo json_encode($results);
    }

    public function guardarClienteBD($param=array()){ 
    if (empty($this->session->get('usuario'))) $this->redirect("default","login");

    $cliente = new Entidades\Cc00000($this->adapter);
    $sucursal = new Entidades\Cc00002($this->adapter);

    $conf= new \Models\ClientesModel($this->adapter);
    $Ruc=isset($_POST["ruc"])? $conf->limpiarCadenaString($_POST["ruc"]):"";
	$NombreComercial=isset($param["cliente"])? $conf->limpiarCadenaString($param["cliente"]):"";
	$RazonSocial=isset($param["razonsocial"])? $conf->limpiarCadenaString($param["razonsocial"]):"";
	$Direccion=isset($param["direccion"])? $conf->limpiarCadenaString($param["direccion"]):"";
	$Telefono=isset($param["telefono"])? $conf->limpiarCadenaString($param["telefono"]):"";
	$Ciudad=isset($param["ciudad"])? $conf->limpiarCadenaString($param["ciudad"]):"";
	$Parroquia=isset($param["parroquia"])? $conf->limpiarCadenaString($param["parroquia"]):"";
	$Provincia=isset($param["provincia"])? $conf->limpiarCadenaString($param["provincia"]):"";
	$CorreoElectronico=isset($param["correo"])? $conf->limpiarCadenaString($param["correo"]):"";
    $NombreContacto=isset($param["contacto"])? $conf->limpiarCadenaString($param["contacto"]):"";
    $Categoria=isset($param["categoria"])? $conf->limpiarCadenaString($param["categoria"]):"";
    $Vendedor=$this->session->get('codVendedor');

    $NombreComercial=str_replace("'","",$NombreComercial);
    $RazonSocial=str_replace("'","",$RazonSocial);
    $Direccion=str_replace("'","",$Direccion);
    $NombreContacto=str_replace("'","",$NombreContacto);
 

    
    $data = array(
        'ruc'=>$Ruc,
        'cliente' => $NombreComercial,
        'razonsocial' => $RazonSocial,
        'direccion' =>$Direccion,
        'telefono' => $Telefono,
        'ciudad' =>$Ciudad,
        'parroquia' =>$Parroquia,
        'provincia' =>$Provincia,
        'correo' =>$CorreoElectronico,
        'contacto' =>$NombreContacto,
        'categoria' =>$Categoria,
        'vendedor' =>$Vendedor
    );
    $data = http_build_query($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=guardar_cliente");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            curl_close ($ch);

            if($server_output=='OK'){
                 //ingresar en la base de datos interna
                $cliente->setRuc($Ruc);
                $cliente->setCliente($NombreComercial);
                $cliente->setRazonsocial($RazonSocial);
                $cliente->setVendedor($Vendedor);
                $cliente->setNivelprecio('MINORISTA');
                $cliente->setDireccion($Direccion);
                $cliente->setTelefono($Telefono);
                $cliente->setCiudad($Ciudad);
                $cliente->setProvincia($Provincia);
                $cliente->setPais('ECUADOR');
                $cliente->setCupo('0');
                $cliente->setCorreo($CorreoElectronico);
                $cliente->setContacto($NombreContacto);
                $cliente->setCondicionpago('CONTADO');
                $cliente->setCategoria($Categoria);
                $cliente->setFechaCreacion(date('Y-m-d'));
                $guardarCliente=$cliente->save();

                if ($guardarCliente){
                    $sucursal->setRuc($Ruc);
                    $sucursal->setCodigodireccion('PRINCIPAL');
                    $sucursal->setTelefono($Telefono);
                    $sucursal->setCiudad($Ciudad);
                    $sucursal->setProvincia($Provincia);
                    $sucursal->setDireccion($Direccion);
                    $sucursal->setEstado(0);
                    $rspta= $sucursal->save();

                    echo $rspta ? "1" : "0";
            
                }else{
                    echo 'Error al crear el cliente';
                }

            }else{
                echo $server_output;
            }
 
    }


    public function agregarItemCarrito($param=array()){
       if (empty($this->session->get('usuario'))) $this->redirect("default","login");
      
       $conf= new \Models\CarritoModel($this->adapter); 

       $id_cabecera=$this->session->get('idCarritoTemporal');
       $id_producto=isset($param['codigo'])? $conf->limpiarCadenaString($param["codigo"]):"";
       $descripcion_producto=isset($param['descripcion'])? $conf->limpiarCadenaString($param["descripcion"]):"";
       $costo_producto=isset($param['costo'])? $conf->limpiarCadenaString($param["costo"]):"0";
       $bodega_producto=isset($param['bodega'])? $conf->limpiarCadenaString($param["bodega"]):"";
       $descuentoCliente=isset($param['descuentoCliente'])? $conf->limpiarCadenaString($param["descuentoCliente"]):"";
       $stock_producto=isset($param[''])? $conf->limpiarCadenaString($param[""]):"";
       if($bodega_producto=='') {
           $bodega_producto=strtoupper($this->session->get('bodUsuario'));
      }
      $Inv00000 = new Entidades\Inv00000($this->adapter);
      $repInv00000=$Inv00000->getMultiObj('codigo',$id_producto);
      $regInv00000=$repInv00000->fetch_object();
      $marcaProducto="";
      if(!isset($regInv00000->marcaproducto)){
        $marcaProducto="";
      }else{
        $marcaProducto=$regInv00000->marcaproducto;
      }
        //consultar stock 
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

        // if($stock_producto==""){
        //     $stock_producto=0;
        // }
       $cantidad_producto=1;
       $precio_producto=isset($param['precio'])? $conf->limpiarCadenaString($param["precio"]):"";
       $descuento_producto=0;
       $subtotal_producto=$cantidad_producto*$precio_producto;
       $subtotal_producto=number_format($subtotal_producto, 2, '.', '');

       $Sis50300 = new Entidades\Sis50300($this->adapter);
       $resp=$Sis50300->getMultiObj('id_cabecera',$id_cabecera,'id_producto',$id_producto);

       if(empty($resp->fetch_object())){

        $resp=$conf->GuardarCarrito($id_cabecera, $id_producto, $descripcion_producto, $costo_producto, $stock_producto, $bodega_producto, $cantidad_producto, $precio_producto, $descuento_producto, $subtotal_producto,$marcaProducto,$descuentoCliente);
        echo $resp;
       }else{
           echo 'carrito';
       }
       
      
    }

   

    public function eliminarItemCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $rspta= $conf->eliminarItemCarriro($id);
        echo $rspta ? "1" : "0";
    }

    public function modificarCodigoCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";
        $rspta= $conf->modificarCodigoCarrito($id,$value);
        echo $rspta ? "1" : "0";
    }

    public function modificarDetalleCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";
        $rspta= $conf->editarDescripcionItemCarriro($id,$value);
        echo $rspta ? "1" : "0";
    }

    public function modificarCantidadCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";
        $rspta= $conf->modificarCantidadCarrito($id,$value);
        echo $rspta ? "1" : "0";
    }

    public function modificarPrecioCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";
        $rspta= $conf->modificarPrecioCarrito($id,$value);
        echo $rspta ? "1" : "0";
    }

    public function modificarDescuentoCarrito($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";
        $rspta= $conf->modificarDescuentoCarrito($id,$value);
        echo $rspta ? "1" : "0";
    }

    public function llenarCarritoTemporal(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

         //DATOS CLIENTE
         $clientesModel= new \Models\ClientesModel($this->adapter);
         $respClienteModel=$clientesModel->ListarClientesId($this->session->get('idCliente'));
         $regClienteModel=$respClienteModel->fetch_object();

        $idCabecera=$this->session->get('idCarritoTemporal');
        $conf= new \Models\CarritoModel($this->adapter);
        $rspta= $conf->llenarCarritoTemporal($idCabecera);
        $html='';
        $cont=0;
        $detalles=0;
        $conf= new \Models\ProformasModel($this->adapter);
        
        while ($reg=$rspta->fetch_object()){
            $rsptaPrecio=$conf->ListarBusquedaPrecioProducto($this->session->get('nivelprecio'),$reg->id_producto);
            $regPrecio=$rsptaPrecio->fetch_object();
            $descuentoCliente=0;

            if(empty($regPrecio)){
                $precioProd=0;
            }else{

                if($regClienteModel->descuento>0){
                    $descuentoCliente=($regPrecio->precio*$regClienteModel->descuento)/100;
                    $descuentoCliente=number_format((float)$descuentoCliente, 2, '.', '');
                    $precioProd=$regPrecio->precio;
                }else{
                    $precioProd=$regPrecio->precio;
                }
                
            }
            $descuentoCliente=0;

            $html.='<tr  class="filas" id="fila'.$cont.'">';
            $html.='<td ><button type="button" class="btn btn-danger" onclick="eliminarDetalle('.$cont.','.$reg->id.')">X</button></td>';
            $html.='<td ><input type="hidden" name="idarticulo[]" value="'.$reg->id_producto.'">'.$reg->id_producto.'</td>';
            $html.='<td ><textarea name="descripcion[]" id="descripcion[]" cols="15" rows="4" class="form-control" onchange="modificarDetalleCarrito(this.value,'.$reg->id.')" value="'.$reg->descripcion_producto.'">'.$reg->descripcion_producto.'</textarea></td>';
            $html.='<td class="txtzize"><input class="txtzize" onchange="modificarCantidadCarrito(this.value,'.$reg->id.')" type="number" name="cantidad[]" id="cantidad[]" value="'.$reg->cantidad_producto.'"><input type="hidden" name="nomBodega[]" value="'.$reg->bodega_producto.'">'.$reg->bodega_producto.'</td>';
            $html.='<td class="txtprecio"><input class="txtprecio" type="number" step="0.01" min="'.$precioProd.'" onchange="modificarPrecioCarrito(this.value,'.$reg->id.')" name="precio[]" id="precio[]" value="'.$reg->precio_producto.'"></td>';
            $html.='<td class="txtzize"><input class="txtzize" type="number" onchange="modificarDescuentoCarrito(this.value,'.$reg->id.')" step="0.01" min="0" name="descuento[]" id="descuento[]" readonly value="'.$reg->descuento_producto.'"></td>';
            $html.='<td class="txtzize"><input class="txtzize" type="text" readonly step="0.01" min="0" name="descuentoCliente[]" id="descuentoCliente[]" value="'.$descuentoCliente.'"></td>';
            $html.='<td ><span name="subtotal" id="subtotal'.$cont.'">'.number_format((float)$reg->subtotal_producto, 2, '.', '').'</span></td>';
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
    
    public function revisar(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        //DATOS CLIENTE
        $clientesModel= new \Models\ClientesModel($this->adapter);
        $respClienteModel=$clientesModel->ListarClientesId($this->session->get('idCliente'));
        $regClienteModel=$respClienteModel->fetch_object();
        $cliente=array(
            'id'=>$regClienteModel->id,
            'ruc'=>$regClienteModel->ruc,
            'cliente'=>$regClienteModel->cliente,
            'razonsocial'=>$regClienteModel->razonsocial,
            'cupoPermitido'=>$this->session->get('cupoPermitido'),
            'condicionpago'=>$regClienteModel->condicionpago
        );

        $_SESSION['condicionpago']=$regClienteModel->condicionpago;

        //DATOS SUCURSAL
        $sucursalesModel= new \Models\SucursalesModel($this->adapter);
        $respSucursalModel=$sucursalesModel->ListarSucursalesId($this->session->get('idSucursalCliente'));
        $regSucursalModel=$respSucursalModel->fetch_object();
        $sucursal=array(
            'codigodireccion'=>$regSucursalModel->codigodireccion,
            'telefono'=>$regSucursalModel->telefono,
            'ciudad'=>$regSucursalModel->ciudad,
            'provincia'=>$regSucursalModel->provincia,
            'direccion'=>$regSucursalModel->direccion
        );
        
        //DATOS VALORES
        $conf =new Entidades\Sis20010($this->adapter);
        $resp=$conf->getSumMulti('valor','documento',$this->session->get('idCarritoTemporal'));
        $carritoModel= new \Models\CarritoModel($this->adapter);
        $respCarritoModel=$carritoModel->ListarCabeceraCarritoId($this->session->get('idCarritoTemporal'));
        $regCarritoModel=$respCarritoModel->fetch_object();
        $valores=array(
            'subtotal_cc_tem'=>$regCarritoModel->subtotal_cc_tem,
            'iva_cc_tem'=>$regCarritoModel->iva_cc_tem,
            'total_cc_tem'=>$regCarritoModel->total_cc_tem,
            'descuento_cc_tem'=>$regCarritoModel->descuento_cc_tem,
            'descuento_porce_cc'=>$regCarritoModel->descuento_porce_cc,
            'monto_abonado'=>$resp['total']
        );
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_proformas->revisarCarrito($cliente,$sucursal,$valores),
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"revisar"
        ));
    }

    public function guardarTotales($param=array())
    {
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");


        $this->modificarDescuentoBse($param['aplica'],$param['daplicar']);

        $idCabecera=$this->session->get('idCarritoTemporal');
        $conf= new \Models\CarritoModel($this->adapter);
        $rspta= $conf->llenarCarritoTemporal($idCabecera);
        $descuento=0;
        $subtotalF=0;
        $iva=0;

        while ($reg=$rspta->fetch_object()){
            $subtotalF=$subtotalF+$reg->subtotal_producto;
            $descuento=$descuento+((float)$reg->descuento_producto+(float)$reg->descuento_cliente);
        }

        $totalFac=((float)$subtotalF-(float)$descuento)+(((float)$subtotalF-(float)$descuento)*0.12); 
        $rsptaAct= $conf->actualizarCabeceraCarrito($idCabecera,$param['subtotal'],number_format(((float)$subtotalF-(float)$descuento)*0.12,'2','.',''),$totalFac, $descuento);
        echo $rsptaAct ? "1" : "0";
    }

    public function revisarCarrito(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $idCabecera=$this->session->get('idCarritoTemporal');
        $conf= new \Models\CarritoModel($this->adapter);
        $rspta= $conf->llenarCarritoTemporal($idCabecera);

        $Sis50300=new Entidades\Sis50300($this->adapter);
        $data= Array();
        while ($reg=$rspta->fetch_object()){
            //$Sis50300->updateMultiColum('guia',0,'id',$reg->id);
            $inv00000=$conf->verificarProductos($reg->id_producto);
            $reginv00000=$inv00000->fetch_object();
            $resp='';
            $status= $reg->guia?'checked':'';
            if(!isset($reginv00000)){
                $resp='<button type="button" class="btn btn-info" onclick="cambiarCodigo('.$reg->id.')"><i class="mdi mdi-grease-pencil" ></i></button>';
            }
            if(strtoupper($reg->bodega_producto)==strtoupper($this->session->get('bodUsuario'))){
                $check='<input type="checkbox" '.$status.'  class="form-control" onclick="activarruta(this.checked,'.$reg->id.')">';
            }
            else{
                $check='<input type="checkbox" disabled readonly class="form-control" onclick="activarruta(this.checked,'.$reg->id.')">';
            }
        $data[]=array(
        "0"=>$check,
        "1"=>'<input type="number" value="'.$reg->cantidad_producto.'"  class="form-control" min=1 max='.$reg->cantidad_producto.' onclick="mofificarCantidadGuia(this.value,'.$reg->id.')">',
        "2"=>$reg->id_producto.' '.$resp,
        "3"=>$reg->descripcion_producto,
        "4"=>$reg->bodega_producto,
        "5"=>$reg->cantidad_producto,
        "6"=>$reg->precio_producto);
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
        $conf= new Entidades\Sis20010($this->adapter);
        $limpiar= new \Models\CarritoModel($this->adapter);
        $id=$this->session->get('idCarritoTemporal');
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

    public function modificarGuia($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";
        if($value=='true'){
            $value=1;
        }else{
            $value=0;
        }
        $rspta= $conf->modificarGuiaCarrito($id,$value);
        echo $rspta ? "1" : "0";

    }

    public function metodoPago(){
        $conf= new \Models\MetodopagoModel($this->adapter);
        $rep=$conf->ListarMetodo();
        $option='<option value="">--SELECCIONE UN METODO DE PAGO--</option>';
        while ($reg=$rep->fetch_object()){
        $option.='<option value="'.$reg->id.'">'.$reg->formapago.'</option>';
        }
        echo $option;
    }

    public function listarMontos(){

        $conf= new \Models\Sis20010Model($this->adapter);
        $rspta=$conf->listarMontos($this->session->get('idCarritoTemporal'));
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
        $conf=new Entidades\Sis20010($this->adapter);
        $rspta=$conf->deleteMulti('id',$param['id']);
        echo $rspta ? "1" : "0";

    }

    public function modificarCantidadGuia($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\CarritoModel($this->adapter);
        $id=isset($param['id_base'])? $conf->limpiarCadenaString($param["id_base"]):"";
        $value=isset($param['value'])? $conf->limpiarCadenaString($param["value"]):"";
        $rspta= $conf->modificarCantidadGuia($id,$value);
        echo $rspta ? "1" : "0";
    }

    public function modificarDescuentoPorce($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        $conf=new Entidades\Sis50200($this->adapter);

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

        $resp=$conf->updateMultiColum('descuento_porce_cc',$descuento,'id',$id);
        echo $resp ? "1" : "0";

    }

    public function modificarDescuentoBse($aplicaDescuento,$daplicar){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Sis50300=new Entidades\Sis50300($this->adapter);

        //$descuento=isset($param['descuento'])? $Sis50300->limpiarCadenaString($param["descuento"]):"";

        $respSis50300=$Sis50300->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));

        // $usu=new Entidades\Sis00300($this->adapter);
        // $respUsu=$usu->getMultiObj('usuario',$this->session->get('usuario'))->fetch_object();

        $conf= new \Models\CarritoModel($this->adapter);

        $subtotalCarrito=$conf->subtotalPedido($this->session->get('idCarritoTemporal'))->fetch_object();
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

            $descuentoPedido=$conf->descuentoCliente($this->session->get('idCarritoTemporal'))->fetch_object();
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




        if($_SESSION['condicionpago']=='CONTADO' || $categoria=="4"){
            $descuentoResp=(float)$valDescuentoCarrito;
        }else{
            $descuentoResp=(float)0;
        }
        $descuentoResp=(float)10;
      
         //die($valDescuentoCarrito);
        

        $conf=new Entidades\Sis50200($this->adapter);
        $resp=$conf->updateMultiColum('descuento_porce_cc',$descuentoResp,'id',$this->session->get('idCarritoTemporal'));

        $return='1';
        $conf= new \Models\ProformasModel($this->adapter);
        $banderaDesc=$descuentoResp;
        while($regSis50300=$respSis50300->fetch_object()){
            $descuentoResp=$banderaDesc;
            // $TotalDesc=(($regSis50300->cantidad_producto*$regSis50300->precio_producto)*$descuento)/100;
            // $TotalSubto=(($regSis50300->cantidad_producto*$regSis50300->precio_producto)-((($regSis50300->cantidad_producto*$regSis50300->precio_producto)*$descuento)/100));
            $rsptaDesc=$conf->TieneDescuento($regSis50300->id_producto)->fetch_object();

            if(!empty($rsptaDesc)){
                if($this->session->get('usuario')!='888'){
                    $descuentoResp=(float)0;
                }
                $TotalDesc=(($regSis50300->precio_producto*$regSis50300->cantidad_producto)*$descuentoResp)/100;
            }else{
                $TotalDesc=(($regSis50300->precio_producto*$regSis50300->cantidad_producto)*$descuentoResp)/100;
            }
            
            
            $TotalSubto=($regSis50300->precio_producto*$regSis50300->cantidad_producto);
         
            // $TotalDesc=number_format((($descuentoResp)/100*$regSis50300->precio_producto),2,'.','');
            // $TotalSubto=number_format($regSis50300->precio_producto-(number_format(($descuentoResp)/100*$regSis50300->precio_producto,2,'.','')),2,'.','')*$regSis50300->cantidad_producto;
            $resp=$Sis50300->updateMultiColum('descuento_producto',$TotalDesc,'id',$regSis50300->id);
            $resp ? $return='1' : $return='0';
            $resp=$Sis50300->updateMultiColum('subtotal_producto',$TotalSubto,'id',$regSis50300->id);
            $resp ? $return='1' : $return='0';
        }
        //echo $return;
    }

    public function consultarStock($param=array()){

        $Sis50300= new \Models\Sis50300Model($this->adapter);
        $Cc10010= new \Models\Cc10010Model($this->adapter);

        

        $data = array(
            'codigo'=>trim($param['codigo']),
            'bodega' => strtoupper($param['bodega'])
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproducto");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);
                
                $stock= json_decode($server_output);
                $cont=0;
                $data=array();
                while($cont<count($stock)){
                    //CONSULTO EL STOCK EN CARRITO TEMPORAL
                   $cantidad=0;
                //    $resp=$Sis50300->countStockCarritoTemporal($param['codigo'],$stock[$cont]->bodega);
                //    $reg=$resp->fetch_object();
                //    $cantidad=$reg->CANTIDAD;
                    //CONSULTO EL STOCK DE LA TABLA PEDIDOS

                    $Cc11000  = new Entidades\Cc11000 ($this->adapter);
                    $Cc11010  = new Entidades\Cc11010 ($this->adapter);
                    $Sis00300  = new Entidades\Sis00300 ($this->adapter);
                    $respCc11000=$Cc11000->getMultiObj('aprobadoCobranza','0');
                    while($regCc11000=$respCc11000->fetch_object()){
                        $respSis00300=$Sis00300->getMultiObj('usuario',$regCc11000->usuario)->fetch_object();
                      //  if($this->session->get('bodUsuario')==$respSis00300->bodega){
                            $respcc11010=$Cc11010->getMultiObj('cc11000id',$regCc11000->id,'inv00000codigo',$param['codigo'],'bodega',$stock[$cont]->bodega)->fetch_object();
                            if(!empty($respcc11010)){
                                $cantidad=$cantidad+$respcc11010->cantidad;
                            }
                       // } 
                    }

                   $respP=$Cc10010->countStockCarritoPedidos($param['codigo'],$stock[$cont]->bodega);
                   $regP=$respP->fetch_object();
                   $cantidad=$cantidad+$regP->CANTIDAD;

                   if($cantidad<0){
                    $cantidad=0;
                   }


                 //  if($stock[$cont]->bodega==$this->session->get('bodUsuario')){

                    $stockMostrar=($stock[$cont]->stock-$cantidad)<0?0:$stock[$cont]->stock-$cantidad;
                 //  }
		  //else
                  // {
                  //  $stockMostrar=$stock[$cont]->stock;
                  //}

                   $data[]=array(
                       'bodega'=>$stock[$cont]->bodega,

                       'stock'=>number_format($stockMostrar,'0','','')
                   );
                    $cont++;
                }
                echo  json_encode($data);


    }
}
