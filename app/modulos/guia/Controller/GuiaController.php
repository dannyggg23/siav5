<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class GuiaController extends Controllers
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
        //Cargamos la empresa logueada
        /*if (isset($_SESSION['rucEmpresa']))
        {
            $this->login_empresa = new \Entidades\Sis00100($this->adapter);
            $this->login_empresa = $this->login_empresa->getMulti('ruc', $_SESSION['rucEmpresa']);
        }*/
    }
    
    public function exec()
    {
        $this->index();
    }

    public function index(){

        $formulario=new dti_builder_form($this->adapter);
        $maestro=new Entidades\Sis40120($this->adapter);

        $formulario->setForm($maestro->getMulti('formulario','frmTransportista'),'orden');

        // $contenedor = globalFunctions::renderizar($this->website,array(
        //     'section'=>array(
        //         'layout_section'=> $formulario->getForm()
        //     )
        // ));

        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_guia->listar($formulario->getForm())
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Transportistas"
        ));

    }

    public function listar(){

        $guias= new \Models\Gui00000Model($this->adapter);
        $rspta=$guias->listar();
        $data= Array();
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->suspendido)?'<button class="btn btn-info btn-outline btn-circle btn-sm m-r-5" onclick="mostrar('."'".$reg->codigo."'".')"><i class="ti-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger  btn-sm m-r-5" onclick="activar('."'".$reg->codigo."'".')">INACTIVO</button>':
                '<button class="btn btn-info btn-outline btn-circle btn-sm m-r-5" onclick="mostrar('."'".$reg->codigo."'".')"><i class="ti-pencil-alt"></i></button>'.
                ' <button class="btn btn-success btn-sm m-r-5" onclick="desactivar('."'".$reg->codigo."'".')">ACTIVO</button>',
                "1"=>$reg->codigo,
                "2"=>$reg->razonsocial,
                "3"=>$reg->correo,
                "4"=>$reg->direccion,
                "5"=>$reg->telefono,
                "6"=>$reg->celular,
                "7"=>$reg->placa
                );
        }
        $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
        echo json_encode($results);
    }

    public function listarRevisar(){

        $guias= new \Models\Gui00000Model($this->adapter);
        $rspta=$guias->listar();
        $data= Array();
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->codigo,
                "1"=>$reg->razonsocial,
                "2"=>$reg->correo,
                "3"=>$reg->direccion,
                "4"=>$reg->telefono,
                "5"=>$reg->celular,
                "6"=>$reg->placa
                );
        }
        $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
        echo json_encode($results);
    }

    public function guardar($param=array()){

        $guias= new \Models\Gui00000Model($this->adapter);
        $codigo=isset($param["txtcodigo"])? $guias->limpiarCadenaString($param["txtcodigo"]):"";
        $razonsocial=isset($param["txtrazonsocial"])? $guias->limpiarCadenaString($param["txtrazonsocial"]):"";
        $correo=isset($param["txtcorreo"])? $guias->limpiarCadenaString($param["txtcorreo"]):"";
        $direccion=isset($param["txtdireccion"])? $guias->limpiarCadenaString($param["txtdireccion"]):"";
        $telefono=isset($param["txttelefono"])? $guias->limpiarCadenaString($param["txttelefono"]):"";
        $celular=isset($param["txtcelular"])? $guias->limpiarCadenaString($param["txtcelular"]):"";
        $placa=isset($param["txtplaca"])? $guias->limpiarCadenaString($param["txtplaca"]):"";
        $sis40170id=isset($param["txtsis40170id"])? $guias->limpiarCadenaString($param["txtsis40170id"]):"";
        $fcreacion=date('Y-m-d');
        $usuario=$this->session->get('usuario');
        $empresa=$this->session->get('empresa');
        $rspta=$guias->insertar($codigo,$razonsocial,$correo,$direccion,$telefono,$celular,$placa,$fcreacion,$usuario,$empresa,$sis40170id);
        echo $rspta ? "1" : "0";
    }

    public function mostrar($param=array()){
        $guias= new \Models\Gui00000Model($this->adapter);
        $codigo=isset($param["codigo"])? $guias->limpiarCadenaString($param["codigo"]):"";
        $rspta=$guias->mostrar($codigo);
 		echo json_encode($rspta);
    }

    public function editar($param=array()){
        $guias= new \Models\Gui00000Model($this->adapter);
        $guiasEnt= new Entidades\Gui00000($this->adapter);
        $codigo=isset($param["txtcodigo"])? $guias->limpiarCadenaString($param["txtcodigo"]):"";
        $razonsocial=isset($param["txtrazonsocial"])? $guias->limpiarCadenaString($param["txtrazonsocial"]):"";
        $correo=isset($param["txtcorreo"])? $guias->limpiarCadenaString($param["txtcorreo"]):"";
        $direccion=isset($param["txtdireccion"])? $guias->limpiarCadenaString($param["txtdireccion"]):"";
        $telefono=isset($param["txttelefono"])? $guias->limpiarCadenaString($param["txttelefono"]):"";
        $celular=isset($param["txtcelular"])? $guias->limpiarCadenaString($param["txtcelular"]):"";
        $placa=isset($param["txtplaca"])? $guias->limpiarCadenaString($param["txtplaca"]):"";
        $sis40170id=isset($param["txtsis40170id"])? $guias->limpiarCadenaString($param["txtsis40170id"]):"";
        $guiasEnt->autocommit();
        $guiasEnt->updateMultiColum('razonsocial',$razonsocial,'codigo',$codigo);
        $guiasEnt->updateMultiColum('correo',$correo,'codigo',$codigo);
        $guiasEnt->updateMultiColum('direccion',$direccion,'codigo',$codigo);
        $guiasEnt->updateMultiColum('telefono',$telefono,'codigo',$codigo);
        $guiasEnt->updateMultiColum('celular',$celular,'codigo',$codigo);
        $guiasEnt->updateMultiColum('sis40170id',$sis40170id,'codigo',$codigo);
        $resp=$guiasEnt->updateMultiColum('placa',$placa,'codigo',$codigo);
        $guiasEnt->commit();
        echo $resp ? "1" : "0";
    }

    public function activar($param=array()){
        $guiasEnt= new Entidades\Gui00000($this->adapter);
        $codigo=isset($param["codigo"])? $guiasEnt->limpiarCadenaString($param["codigo"]):"";
        $resp='';
        if($param['tipo']=='activar'){
            $resp= $guiasEnt->updateMultiColum('suspendido','0','codigo',$codigo);
        }else{
            $resp= $guiasEnt->updateMultiColum('suspendido','1','codigo',$codigo);
        }
        echo $resp ? "1" : "0";
    }

    public function selectTransportistas(){
        $conf= new \Models\Gui00000Model($this->adapter);
        $rep=$conf->select();
        $option='<option value="">--SELECCIONE --</option>';
        while ($reg=$rep->fetch_object()){
        $option.='<option   value="'.$reg->codigo.'">'.$reg->razonsocial.'</option>';
        }
        echo $option;
    }


    public function guardarGuia($param=array()){
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
        }

        $guias->updateMultiColum('num_guia',$numero,'secuencial',$regBodega->secuencial);


        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $numPedido=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
        $numPedido=$numPedido['numpedido'];



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
                        $clientes_model= new \Models\ClientesModel($this->adapter);
                        $respCliente=$clientes_model->ListarClientesId($this->session->get('idCliente'));
                        $regCliente=$respCliente->fetch_object();
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
                                    //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLASÇ
                                    $guiaCabeceraEntidad = new Entidades\Cc10000($this->adapter);
                                    $guiaCabeceraEntidad->autocommit();
                                    $Cc10010 = new Entidades\Cc10010($this->adapter);
                                    $guiaCabeceraCarritoTemp = new Entidades\Sis50200($this->adapter);
                                    $guiaDetalleCarritoTemp= new Entidades\Sis50300($this->adapter);
                                    


                                    //insertar cabecera
                                    $respGuiaCabeceraEntidad=$guiaCabeceraCarritoTemp->getMultiObj('id',$this->session->get('idCarritoTemporal'));
                                    $regCabecera=$respGuiaCabeceraEntidad->fetch_object();
                                    $guiaCabeceraEntidad->setDocumento($numPedido);
                                    $guiaCabeceraEntidad->setFecha(date('Y-m-d'));
                                    $guiaCabeceraEntidad->setUsuario($regCabecera->id_usuario);
                                    $guiaCabeceraEntidad->setCc00000id($regCabecera->id_cliente);
                                    $guiaCabeceraEntidad->setCc00002id($regCabecera->id_sucursal);
                                    $guiaCabeceraEntidad->setSubtotal($regCabecera->subtotal_cc_tem);
                                    $guiaCabeceraEntidad->setDescuento($regCabecera->descuento_cc_tem);
                                    $guiaCabeceraEntidad->setIva($regCabecera->iva_cc_tem);
                                    $guiaCabeceraEntidad->setTotal($regCabecera->total_cc_tem);
                                    $guiaCabeceraEntidad->setDescuento_porce($regCabecera->descuento_porce_cc);
                                    $idCabecera=$guiaCabeceraEntidad->save();
                                    //##CABECERA TRANSFERENCIAS##
                                    $inv10000 = new Entidades\Inv10000($this->adapter);
                                    $inv10000->setPedido($numPedido);
                                    $inv10000->setLote(strtoupper($this->session->get('bodUsuario')));
                                    $inv10000->setFecha(date('Y-m-d'));
                                    $inv10000->setUsuario($this->session->get('usuario'));
                                    $inv10000id=$inv10000->save();
                                    $inv10100 = new Entidades\Inv10100($this->adapter);
                                    //##FIN CABECERA TRANSFERENCIAS##
                                  
                                    
                                    $guiaDetalle=$guiaDetalleCarritoTemp->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));
                                    $cont=1;
                                    $transferencia=false;
                                    while($regDetalle=$guiaDetalle->fetch_object()){
                                        //##DETALLE TRANSFERENCIAS##
                                        if($regDetalle->bodega_producto!=strtoupper($this->session->get('bodUsuario'))){
                                            $linea=16384*$cont;
                                            $inv10100->setInv10000id($inv10000id);
                                            $inv10100->setInv00000codigo($regDetalle->id_producto);
                                            $inv10100->setLinea($linea.'.00000');
                                            $inv10100->setCantidad($regDetalle->cantidad_producto);
                                            $inv10100->setBodega($regDetalle->bodega_producto);
                                            $inv10100->setBodega_destino(strtoupper($this->session->get('bodUsuario')));
                                            $inv10100->save();
                                            $cont++;
                                            $transferencia=true;
                                        }
                                        //##FIN DETALLE TRANSFERENCIAS##
                                        $Cc10010->setCc10000id($idCabecera);
                                        $Cc10010->setInv00000codigo($regDetalle->id_producto);
                                        $Cc10010->setDescripcion($regDetalle->descripcion_producto);
                                        $Cc10010->setCosto($regDetalle->costo_producto);
                                        $Cc10010->setBodega($regDetalle->bodega_producto);
                                        $Cc10010->setCantidad($regDetalle->cantidad_producto);
                                        $Cc10010->setPrecio($regDetalle->precio_producto);
                                        $Cc10010->setDescuento($regDetalle->descuento_producto);
                                        $Cc10010->setSubtotal($regDetalle->subtotal_producto);
                                        $Cc10010->setGuia($regDetalle->guia);
                                        $Cc10010->setCantidad_guia($regDetalle->cantidad_guia);
                                        $Cc10010->setMarca_producto($regDetalle->marca_producto);
                                        $Cc10010->setStock_producto($regDetalle->stock_producto);
                                        $Cc10010->setDescuento_cliente($regDetalle->descuento_cliente);
                                        $resp =$Cc10010->save();
                                        if($resp){
                                            $bandera=false;
                                        }else{
                                            $bandera=true;
                                        }  
                                    }

                                     //VERIFICO TRANSFERENCIA
                                        if(!$transferencia){
                                            $inv10000->deleteMulti('pedido',$numPedido);
                                        }else{

                                            //############--ENVIO DATOS DE TRANSFERENCIA AL WEB SERVICE ---##########
                                        //     $transReg=$inv10000->getMultiObj('pedido',$numPedido);
                                        //     $regInv10000=$transReg->fetch_object();
                                        //     $data = array(
                                        //         'pedido'=>$regInv10000->pedido,
                                        //         'lote' =>$regInv10000->lote,
                                        //         'fecha' =>$regInv10000->fecha,
                                        //         'usuario' =>$regInv10000->usuario,
                                        //         'estado' =>1,
                                        //         'observacion' =>'NUEVO'
                                        //     );
                                        //     $data = http_build_query($data);
                                        //             $ch = curl_init();
                                        //             curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/transferencias.php?op=maestroTransferencia");
                                        //             curl_setopt($ch, CURLOPT_POST, 1);
                                        //             curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                        //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        //             $server_output = curl_exec($ch);
                                                
                                        //             curl_close ($ch);

                                        // //ENVIAR DETALLE
                                        // $detalleInv10100=$inv10100->getMultiObj('inv10000id',$regInv10000->id);
                                        // while($regInv10100=$detalleInv10100->fetch_object()){
                                        //     if($regInv10100->bodega!=strtoupper($this->session->get('bodUsuario'))){
                                        //         $data = array(
                                        //             'inv10000id'=>$server_output,
                                        //             'inv00000codigo' =>$regInv10100->inv00000codigo,
                                        //             'linea' =>$regInv10100->linea,
                                        //             'cantidad' =>$regInv10100->cantidad,
                                        //             'bodega' =>$regInv10100->bodega,
                                        //             'bodega_destino' =>$regInv10100->bodega_destino
                                        //         );
                                        //         $data = http_build_query($data);
                                        //                 $ch = curl_init();
                                        //                 curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/transferencias.php?op=detalleTransferencia");
                                        //                 curl_setopt($ch, CURLOPT_POST, 1);
                                        //                 curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                        //                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        //                 $server_output = curl_exec($ch);
                                        //                 curl_close ($ch);
                                        //     }        
                                        // }

                                        }

                                    ///SI TODO ESTA BIEN COMPRUEBO LA BANDERA
                                   if($bandera==false){
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
                                        // $reginventario= $respinv0000->fetch_object();
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
                                    $guiaCabeceraEntidad->commit();
                                    echo 'OK';
                                    }
                                   }
                                }
                    }else{
                        $guiaCabeceraEntidad->rollback();
                        echo $error;
                    }

                }else
                {
                    echo 'ERROR CABECERA'.$server_output;
                }
    }


    public function guardarGuiaCobranzas($param=array()){
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
        }

        $guias->updateMultiColum('num_guia',$numero,'secuencial',$regBodega->secuencial);


        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $numPedido=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
        $numPedido=$numPedido['numpedido'];



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
                        $clientes_model= new \Models\ClientesModel($this->adapter);
                        $respCliente=$clientes_model->ListarClientesId($this->session->get('idCliente'));
                        $regCliente=$respCliente->fetch_object();
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

                           
                                    
                                     
   
                        $guias= new \Models\Gui40000Model($this->adapter);
                        $bandera=false;
                        $error="";
       
                        //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLAS
                        $guiaCabeceraEntidad = new Entidades\Cc11000($this->adapter);
                        $guiaCabeceraEntidad->autocommit();
                        $Cc10010 = new Entidades\Cc11010($this->adapter);
                        $guiaCabeceraCarritoTemp = new Entidades\Sis50200($this->adapter);
                         $guiaDetalleCarritoTemp= new Entidades\Sis50300($this->adapter);
                        $Gui40000 = new Entidades\Gui40000($this->adapter);
                        $numPedido=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
                        $numPedido=$numPedido['numpedido'];
                        //insertar cabecera
                        $respGuiaCabeceraEntidad=$guiaCabeceraCarritoTemp->getMultiObj('id',$this->session->get('idCarritoTemporal'));
                        $regCabecera=$respGuiaCabeceraEntidad->fetch_object();
                        $guiaCabeceraEntidad->setDocumento($numPedido);
                        $guiaCabeceraEntidad->setFecha(date('Y-m-d'));
                        $guiaCabeceraEntidad->setUsuario($regCabecera->id_usuario);
                        $guiaCabeceraEntidad->setCc00000id($regCabecera->id_cliente);
                        $guiaCabeceraEntidad->setCc00002id($regCabecera->id_sucursal);
                        $guiaCabeceraEntidad->setSubtotal($regCabecera->subtotal_cc_tem);
                        $guiaCabeceraEntidad->setDescuento($regCabecera->descuento_cc_tem);
                        $guiaCabeceraEntidad->setIva($regCabecera->iva_cc_tem);
                        $guiaCabeceraEntidad->setTotal($regCabecera->total_cc_tem);
                        $guiaCabeceraEntidad->setDescuento_porce($regCabecera->descuento_porce_cc);
                        $idCabecera=$guiaCabeceraEntidad->save();

                        $guiaDetalle=$guiaDetalleCarritoTemp->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));
                        $cont=1;
                        
                        while($regDetalle=$guiaDetalle->fetch_object()){
                            $Cc10010->setCc11000id($idCabecera);
                            $Cc10010->setInv00000codigo($regDetalle->id_producto);
                            $Cc10010->setDescripcion($regDetalle->descripcion_producto);
                            $Cc10010->setCosto($regDetalle->costo_producto);
                            $Cc10010->setBodega($regDetalle->bodega_producto);
                            $Cc10010->setCantidad($regDetalle->cantidad_producto);
                            $Cc10010->setPrecio($regDetalle->precio_producto);
                            $Cc10010->setDescuento($regDetalle->descuento_producto);
                            $Cc10010->setSubtotal($regDetalle->subtotal_producto);
                            $Cc10010->setGuia($regDetalle->guia);
                            $Cc10010->setCantidad_guia($regDetalle->cantidad_guia);
                            $Cc10010->setMarca_producto($regDetalle->marca_producto);
                            $Cc10010->setStock_producto($regDetalle->stock_producto);
                            $Cc10010->setDescuento_cliente($regDetalle->descuento_cliente);
                            $resp =$Cc10010->save();
                            if($resp){
                                $bandera=false;
                            }else{
                                $bandera=true;
                            } 
                        }

                        
                            if($bandera==false){
                            // //ELIMINAR EL CARRITO TEMPORAL
                            // $guiaDetalleCarritoTemp->deleteMulti('id_cabecera',$this->session->get('idCarritoTemporal'));
                            // $guiaCabeceraCarritoTemp->deleteMulti('id',$this->session->get('idCarritoTemporal'));
                        
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
                            $guiaCabeceraEntidad->commit();
                            //echo 'OK';
                        }


                                   if($bandera==false){
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
                                        // $reginventario= $respinv0000->fetch_object();
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

                                   

                                    if($bandera==false){
                                         //ELIMINAR EL CARRITO TEMPORAL
                                    $guiaDetalleCarritoTemp->deleteMulti('id_cabecera',$this->session->get('idCarritoTemporal'));
                                    $guiaCabeceraCarritoTemp->deleteMulti('id',$this->session->get('idCarritoTemporal'));
                              
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
                                    $guiaCabeceraEntidad->commit();
                                    echo 'OK';
                                    }
                                   }
                                }
                    }else{
                        $guiaCabeceraEntidad->rollback();
                        echo $error;
                    }

                }else
                {
                    echo 'ERROR CABECERA'.$server_output;
                }
    }

     

    public function guardarPedido(){
        $guias= new \Models\Gui40000Model($this->adapter);
        $bandera=false;
        $error="";
        if($bandera==false){
                        //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLASÇ
                        $guiaCabeceraEntidad = new Entidades\Cc10000($this->adapter);
                        $guiaCabeceraEntidad->autocommit();
                        $Cc10010 = new Entidades\Cc10010($this->adapter);
                        $guiaCabeceraCarritoTemp = new Entidades\Sis50200($this->adapter);
                        $guiaDetalleCarritoTemp= new Entidades\Sis50300($this->adapter);
                        $Gui40000 = new Entidades\Gui40000($this->adapter);
                        $numPedido=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
                        $numPedido=$numPedido['numpedido'];
                        //insertar cabecera
                        $respGuiaCabeceraEntidad=$guiaCabeceraCarritoTemp->getMultiObj('id',$this->session->get('idCarritoTemporal'));
                        $regCabecera=$respGuiaCabeceraEntidad->fetch_object();
                        $guiaCabeceraEntidad->setDocumento($numPedido);
                        $guiaCabeceraEntidad->setFecha(date('Y-m-d'));
                        $guiaCabeceraEntidad->setUsuario($regCabecera->id_usuario);
                        $guiaCabeceraEntidad->setCc00000id($regCabecera->id_cliente);
                        $guiaCabeceraEntidad->setCc00002id($regCabecera->id_sucursal);
                        $guiaCabeceraEntidad->setSubtotal($regCabecera->subtotal_cc_tem);
                        $guiaCabeceraEntidad->setDescuento($regCabecera->descuento_cc_tem);
                        $guiaCabeceraEntidad->setIva($regCabecera->iva_cc_tem);
                        $guiaCabeceraEntidad->setTotal($regCabecera->total_cc_tem);
                        $guiaCabeceraEntidad->setDescuento_porce($regCabecera->descuento_porce_cc);
                        $idCabecera=$guiaCabeceraEntidad->save();

                        //##CABECERA TRANSFERENCIAS##
                        $inv10000 = new Entidades\Inv10000($this->adapter);
                        $inv10000->setPedido($numPedido);
                        $inv10000->setLote(strtoupper($this->session->get('bodUsuario')));
                        $inv10000->setFecha(date('Y-m-d'));
                        $inv10000->setUsuario($this->session->get('usuario'));
                        $inv10000id=$inv10000->save();
                        $inv10100 = new Entidades\Inv10100($this->adapter);
                        //##FIN CABECERA TRANSFERENCIAS##

                        $guiaDetalle=$guiaDetalleCarritoTemp->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));
                        $cont=1;
                        $transferencia=false;
                        while($regDetalle=$guiaDetalle->fetch_object()){
                            //##DETALLE TRANSFERENCIAS##
                            if($regDetalle->bodega_producto!=strtoupper($this->session->get('bodUsuario'))){
                                $linea=16384*$cont;
                                $inv10100->setInv10000id($inv10000id);
                                $inv10100->setInv00000codigo($regDetalle->id_producto);
                                $inv10100->setLinea($linea.'.00000');
                                $inv10100->setCantidad($regDetalle->cantidad_producto);
                                $inv10100->setBodega($regDetalle->bodega_producto);
                                $inv10100->setBodega_destino(strtoupper($this->session->get('bodUsuario')));
                                $inv10100->save();
                                $cont++;
                                $transferencia=true;
                            }
                            //##FIN DETALLE TRANSFERENCIAS##
                            $Cc10010->setCc10000id($idCabecera);
                            $Cc10010->setInv00000codigo($regDetalle->id_producto);
                            $Cc10010->setDescripcion($regDetalle->descripcion_producto);
                            $Cc10010->setCosto($regDetalle->costo_producto);
                            $Cc10010->setBodega($regDetalle->bodega_producto);
                            $Cc10010->setCantidad($regDetalle->cantidad_producto);
                            $Cc10010->setPrecio($regDetalle->precio_producto);
                            $Cc10010->setDescuento($regDetalle->descuento_producto);
                            $Cc10010->setSubtotal($regDetalle->subtotal_producto);
                            $Cc10010->setGuia($regDetalle->guia);
                            $Cc10010->setCantidad_guia($regDetalle->cantidad_guia);
                            $Cc10010->setMarca_producto($regDetalle->marca_producto);
                            $Cc10010->setStock_producto($regDetalle->stock_producto);
                            $Cc10010->setDescuento_cliente($regDetalle->descuento_cliente);
                            $resp =$Cc10010->save();
                            if($resp){
                                $bandera=false;
                            }else{
                                $bandera=true;
                            } 
                        }

                        //VERIFICO TRANSFERENCIA
                        if(!$transferencia){
                            $inv10000->deleteMulti('pedido',$numPedido);
                        }else{
                            $transReg=$inv10000->getMultiObj('pedido',$numPedido);
                            $regInv10000=$transReg->fetch_object();
                            $data = array(
                                'pedido'=>$regInv10000->pedido,
                                'lote' =>$regInv10000->lote,
                                'fecha' =>$regInv10000->fecha,
                                'usuario' =>$regInv10000->usuario,
                                'estado' =>1,
                                'observacion' =>'NUEVO'
                            );
                            $data = http_build_query($data);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/transferencias.php?op=maestroTransferencia");
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $server_output = curl_exec($ch);
                                  
                                    curl_close ($ch);

                        //ENVIAR DETALLE
                        $detalleInv10100=$inv10100->getMultiObj('inv10000id',$regInv10000->id);
                        while($regInv10100=$detalleInv10100->fetch_object()){
                            if($regInv10100->bodega!=strtoupper($this->session->get('bodUsuario'))){
                                $data = array(
                                    'inv10000id'=>$server_output,
                                    'inv00000codigo' =>$regInv10100->inv00000codigo,
                                    'linea' =>$regInv10100->linea,
                                    'cantidad' =>$regInv10100->cantidad,
                                    'bodega' =>$regInv10100->bodega,
                                    'bodega_destino' =>$regInv10100->bodega_destino
                                );
                                $data = http_build_query($data);
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/transferencias.php?op=detalleTransferencia");
                                        curl_setopt($ch, CURLOPT_POST, 1);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        $server_output = curl_exec($ch);
                                        curl_close ($ch);
                            }        
                        }

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
                        $guiaCabeceraEntidad->commit();
                        echo 'OK';
                       }
        }else{
            $guiaCabeceraEntidad->rollback();
            echo $error;
        }
    }

    public function prueba(){

        $guiaCabeceraEntidad = new Entidades\Sis50300($this->adapter);
        print_r($guiaCabeceraEntidad->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal')));
    }

    public function guardarGuiaPedido($param=array()){       
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

        $Cc10000 = new Entidades\Cc10000($this->adapter);
        $respCc10000=$Cc10000->getMultiObj('documento',$_GET['pedido']);
        $regcc10000=$respCc10000->fetch_object();

        

        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $numPedido=$_GET['pedido'];

        $Cc10010 = new Entidades\Cc10010($this->adapter);



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
                   $respCarrito=$Cc10010->getMultiObj('cc10000id',$regcc10000->id);
                   $detSecuencia=0;
                   $bandera=false;
                   $error="";
                   while ($regCarrito=$respCarrito->fetch_object()){
                    $detSecuencia++;
                    $detalles = array(
                        'd_numControl'=> $numeroGuia,
                        'codigoInterno' => $regCarrito->inv00000codigo,
                        'codigoAdicional' =>'',
                        'descripcion' =>$regCarrito->descripcion,
                        'cantidad' => $regCarrito->cantidad_guia,
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


                       


                        $clientes_model= new \Models\ClientesModel($this->adapter);
                        $respCliente=$clientes_model->ListarClientesId($regcc10000->cc00000id);
                        $regCliente=$respCliente->fetch_object();
                       
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
                                    //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLASÇ
                                    $Cc10000 = new Entidades\Cc10000($this->adapter);
                                    $Cc10000->autocommit();
                                   
                                    $guiaCabeceraCarritoTemp = new Entidades\Sis50200($this->adapter);
                                    $guiaDetalleCarritoTemp= new Entidades\Sis50300($this->adapter);
                                    
                                    // $numPedido=$Gui40000->getMulti('bodega',$this->session->get('bodUsuario'));
                                  
                                    
                                    ///SI TODO ESTA BIEN COMPRUEBO LA BANDERA
                                   if($bandera==false){
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

                                        $respCc10000=$Cc10000->getMultiObj('documento',$_GET['pedido']);
                                        $regcc10000=$respCc10000->fetch_object();
                                      

                                       $guiaDetalle=$Cc10010->getMultiObj('cc10000id',$regcc10000->id,'guia',2);
                                  
                                    while($regDetalle=$guiaDetalle->fetch_object()){
                                        if($regDetalle->guia){
                                            
                                            $Gui30010->setGui30000id($Cc3000id);
                                            $Gui30010->setInv00000codigo($regDetalle->inv00000codigo);
                                            $Gui30010->setDescripcion($regDetalle->descripcion);
                                            $Gui30010->setCosto($regDetalle->costo);
                                            $Gui30010->setBodega($regDetalle->bodega);
                                            $Gui30010->setCantidad($regDetalle->cantidad);
                                            $Gui30010->setPrecio($regDetalle->precio);
                                            $Gui30010->setDescuento($regDetalle->descuento);
                                            $Gui30010->setSubtotal($regDetalle->subtotal);
                                            $resp =$Gui30010->save();
                                            $Cc10010->updateMultiColum('guia_pedido',1,'id',$regDetalle->id);
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

                                   
                                    if($bandera==false){
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
                                    $Cc10010->commit();
                                    echo 'OK';
                       

                                    }
                                   }
                                }
                    }else{
                        $Cc10010->rollback();
                        echo $error;
                    }

                }else
                {
                    echo 'ERROR CABECERA'.$server_output;
                }
    }

    public function enviarCobranzas($param=array()){
        
   
        $guias= new \Models\Gui40000Model($this->adapter);
        $bandera=false;
        $error="";
       
                        $pedidoMundoMotriz=false;
                        //INSERTADO EN LOS WEB SERVICE INSERTAR EN LAS TABLAS
                        $guiaCabeceraEntidad = new Entidades\Cc11000($this->adapter);
                        $guiaCabeceraEntidad->autocommit();
                        $Cc10010 = new Entidades\Cc11010($this->adapter);
                        $guiaCabeceraCarritoTemp = new Entidades\Sis50200($this->adapter);
                         $guiaDetalleCarritoTemp= new Entidades\Sis50300($this->adapter);
                        $Gui40000 = new Entidades\Gui40000($this->adapter);
                        $numPedido=$Gui40000->getMulti('bodega',strtoupper($this->session->get('bodUsuario')));
                        $numPedido=$numPedido['numpedido'];
                        //insertar cabecera
                        $respGuiaCabeceraEntidad=$guiaCabeceraCarritoTemp->getMultiObj('id',$this->session->get('idCarritoTemporal'));
                        $regCabecera=$respGuiaCabeceraEntidad->fetch_object();
                        $guiaCabeceraEntidad->setDocumento($numPedido);
                        $guiaCabeceraEntidad->setFecha(date('Y-m-d'));
                        if($regCabecera->id_usuario=="888"){
                            $regCabecera->id_usuario="069";
                            $pedidoMundoMotriz=true;
                        }
                        $guiaCabeceraEntidad->setUsuario($regCabecera->id_usuario);
                        $guiaCabeceraEntidad->setCc00000id($regCabecera->id_cliente);
                        $guiaCabeceraEntidad->setCc00002id($regCabecera->id_sucursal);
                        $guiaCabeceraEntidad->setSubtotal($regCabecera->subtotal_cc_tem);
                        $guiaCabeceraEntidad->setDescuento($regCabecera->descuento_cc_tem);
                        $guiaCabeceraEntidad->setIva($regCabecera->iva_cc_tem);
                        $guiaCabeceraEntidad->setTotal($regCabecera->total_cc_tem);
                        $guiaCabeceraEntidad->setDescuento_porce($regCabecera->descuento_porce_cc);
                        $idCabecera=$guiaCabeceraEntidad->save();

                        $guiaDetalle=$guiaDetalleCarritoTemp->getMultiObj('id_cabecera',$this->session->get('idCarritoTemporal'));
                        $cont=1;
                        
                        while($regDetalle=$guiaDetalle->fetch_object()){
                            $Cc10010->setCc11000id($idCabecera);
                            $Cc10010->setInv00000codigo($regDetalle->id_producto);
                            $Cc10010->setDescripcion($regDetalle->descripcion_producto);
                            $Cc10010->setCosto($regDetalle->costo_producto);
                            $Cc10010->setBodega($regDetalle->bodega_producto);
                            $Cc10010->setCantidad($regDetalle->cantidad_producto);
                            $Cc10010->setPrecio($regDetalle->precio_producto);
                            $Cc10010->setDescuento($regDetalle->descuento_producto);
                            $Cc10010->setSubtotal($regDetalle->subtotal_producto);
                            $Cc10010->setGuia($regDetalle->guia);
                            $Cc10010->setCantidad_guia($regDetalle->cantidad_guia);
                            $Cc10010->setMarca_producto($regDetalle->marca_producto);
                            $Cc10010->setStock_producto($regDetalle->stock_producto);
                            $Cc10010->setDescuento_cliente($regDetalle->descuento_cliente);
                            $resp =$Cc10010->save();
                            if($resp){
                                $bandera=false;
                            }else{
                                $bandera=true;
                            } 
                        }

                        
                        if($bandera==false){
                        

                        if($pedidoMundoMotriz==true){
                            //enviar email
 //MONTOS

                            $Cc10000Model=new \Models\Cc10000Model($this->adapter);
                            $rspta=$Cc10000Model->listarCarritoId($this->session->get('idCarritoTemporal'));
                            $regResp=$rspta->fetch_object();
                            $montoAbonado=$regResp->monto_abonado;
                            $montoPendiente=$regResp->pendiente;

                            //

                            $conf= new Entidades\Sis50200($this->adapter);
                            $Sis50200=$conf->getMultiObj('id',$this->session->get('idCarritoTemporal'));
                            $RowSis50200=$Sis50200->fetch_object();

                            $e60200= new \Models\CarritoModel($this->adapter);

                            $ide60200=$e60200->GuardarCabecera60200($RowSis50200->id_usuario,
                            $RowSis50200->id_cliente,
                            $RowSis50200->id_sucursal,
                            $RowSis50200->fecha_cc_tem,
                            $RowSis50200->subtotal_cc_tem,
                            $RowSis50200->iva_cc_tem,
                            $RowSis50200->total_cc_tem,
                            $RowSis50200->descuento_porce_cc);


                            $conf= new Entidades\Sis00300($this->adapter);
                            $Sis00300=$conf->getMultiObj('usuario',$this->session->get('usuario'));
                            $RowSis00300=$Sis00300->fetch_object();

                            $conf= new Entidades\Sis00300($this->adapter);
                            $Sis00300=$conf->getMultiObj('usuario',$this->session->get('usuario'));
                            $RowSis00300=$Sis00300->fetch_object();


                            $conf= new Entidades\Cc00000($this->adapter);
                            $Cc00000=$conf->getMultiObj('id', $RowSis50200->id_cliente);
                            $RowCc00000=$Cc00000->fetch_object();


                            $conf= new Entidades\Cc00002($this->adapter);
                            $Cc00002=$conf->getMultiObj('id', $this->session->get('idSucursalCliente'));
                            $RowCc00002=$Cc00002->fetch_object();


                            $conf= new Entidades\Sis50300($this->adapter);
                            $Sis50300=$conf->getMultiObj('id_cabecera', $this->session->get('idCarritoTemporal'));
                            $total=0;
                            $detalles='';
                            $sucursal='';

                            $e60300= new Entidades\Sis60300($this->adapter);

                            if($RowSis00300->bodega=='PVA1'){

                                $sucursal.='        <div class="borde" style="height: 140px">';
                                $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;03-299-7600&nbsp;Ext 5003</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;AMBATO - ECUADOR</FONT></label><br><br>';
                                $sucursal.='        </div>';
                                $sucursal.='    </div>';
                                $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
                                $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
                                $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
                                $sucursal.='    </div>';
                                $sucursal.='</div>';
                            }elseif($RowSis00300->bodega=='PVA2'){
                                $sucursal.='        <div class="borde" style="height: 140px">';
                                $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av. Bolivariana y Julio Jaramillo, junto a la Gasolinera Oriente</label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(03) 2406-944&nbsp;/ 0988458028</label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;AMBATO - ECUADOR</label><br><br>';
                                $sucursal.='        </div>';
                                $sucursal.='    </div>';
                                $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
                                $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
                                $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
                                $sucursal.='    </div>';
                                $sucursal.='</div>';

                            }elseif($RowSis00300->bodega=='PVQ1'){
                                $sucursal.='        <div class="borde" style="height: 140px">';
                                $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av. 10 de Agosto N° 35-118 e Ignacio San María</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(02) 2245-046&nbsp;/ 0990195985</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;QUITO - ECUADOR</FONT></label><br><br>';
                                $sucursal.='        </div>';
                                $sucursal.='    </div>';
                                $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
                                $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
                                $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
                                $sucursal.='    </div>';
                                $sucursal.='</div>';

                            }elseif($RowSis00300->bodega=='PVQ2'){
                                $sucursal.='        <div class="borde" style="height: 140px">';
                                $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av. Mariscar Sucre 517 - 147 entre Toacazo y Chicaña</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(02) 241-115&nbsp;/ 0990197232</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;QUITO - ECUADOR</FONT></label><br><br>';
                                $sucursal.='        </div>';
                                $sucursal.='    </div>';
                                $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
                                $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
                                $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
                                $sucursal.='    </div>';
                                $sucursal.='</div>';

                            }elseif($RowSis00300->bodega=='PVG1'){
                                $sucursal.='        <div class="borde" style="height: 140px">';
                                $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Carchi 2130B y Ayacucho</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(04) 2365-796&nbsp;/ 0983623693</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;GUAYAQUIL - ECUADOR</FONT></label><br><br>';
                                $sucursal.='        </div>';
                                $sucursal.='    </div>';
                                $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
                                $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
                                $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
                                $sucursal.='    </div>';
                                $sucursal.='</div>';

                            }elseif($RowSis00300->bodega=='PVS1'){
                                $sucursal.='        <div class="borde" style="height: 140px">';
                                $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av. Quevedo s/n y Jasinto Cortez</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(02) 3712-775&nbsp;/ 0998325701</FONT></label><br><br>';
                                $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;SANTO DOMINGO - ECUADOR</FONT></label><br><br>';
                                $sucursal.='        </div>';
                                $sucursal.='    </div>';
                                $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
                                $sucursal.='        <br><b><label style="font-size:12px;">R<FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
                                $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
                                $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
                                $sucursal.='    </div>';
                                $sucursal.='</div>';
                            }

                            while ($RowSis50300=$Sis50300->fetch_object()){


                                $e60200->GuardarCarrito60300((int)$ide60200,
                                $RowSis50300->id_producto,
                                $RowSis50300->descripcion_producto,
                                $RowSis50300->costo_producto,
                                $RowSis50300->stock_producto,
                                $RowSis50300->bodega_producto,
                                $RowSis50300->cantidad_producto,
                                $RowSis50300->precio_producto,
                                $RowSis50300->descuento_producto,
                                $RowSis50300->subtotal_producto,
                                $RowSis50300->marca_producto,
                                number_format((float)$RowSis50300->descuento_cliente,2,'.','')
                            );

                                


                                $total=$total+$RowSis50300->subtotal_producto;
                                
                                $detalles.='        <tr>';
                                $detalles.='            <td>'.(string) $RowSis50300->id_producto.'</td>';
                                $detalles.='            <td>'.(string) $RowSis50300->descripcion_producto.'</td>';
                                $detalles.='            <td>'.(string) $RowSis50300->marca_producto.'</td>';
                                $detalles.='            <td>'.(string) $RowSis50300->cantidad_producto.'</td>';
                                $detalles.='            <td>'.(string) $RowSis50300->precio_producto.'</td>';
                                $detalles.='            <td>'.(string) $RowSis50300->subtotal_producto.'</td>';
                                $detalles.='        </tr>';
                            }
                            
                            $html ='<!DOCTYPE html>';
                            $html.='<html>';
                            $html.='<head>';
                            $html.='<meta charset="utf-8">';
                            $html.='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
                            $html.='<title>Proformas</title>';
                            $html.='<style type="text/css">';
                            $html.='body {font-family: Delicious, sans-serif;font-size: 9px;}';
                            $html.='.bordeLinea {float:left;width: 50%;}';
                            $html.='.borde {border-radius: 20px 20px 20px 20px;-moz-border-radius: 20px 20px 20px 20px;-webkit-border-radius: 20px 20px 20px 20px;border: 1px solid #000000;}';
                            $html.='.bordeCuadrado{border: 1px solid #000000;}';
                            $html.='label {margin-left: 20px;padding-bottom: 30px;padding-top: 30px;}';
                            $html.='.bordeTabla{border-collapse: collapse;text-align: center;}';
                            $html.='.bordeTablaLeft{border-collapse: collapse;}';
                            $html.='.bordeTabla tr td{border: 1px solid black;}';
                            $html.='.bordeTablaLeft tr td{border: 1px solid black;}';
                            $html.='.imgBarcode{width:300px;height:50px;margin-left:15px}';
                            $html.='.imgLogo{width:200px;margin-left: 60px;  margin-top: 10px;max-height: 120px;}';
                            $html.='.txtDerecha{text-align: right;}';
                            $html.='.infoAdicional{margin-left:15px}';
                            $html.='.sinlogo{margin-left:35px;color:#FF0000}';
                            $html.='td {padding: 2px;}';
                            $html.='#footer {padding-top:5px 0; border-top: 1px solid; width:100%; position: fixed; left: 0; bottom: 0;}
                                    #footer .fila td {text-align:left; width:100%;}
                                    #footer .fila td span {font-size: 10px; color: #334373;}';
                            $html.='</style>';
                            $html.='</head>';
                            $html.='<body>';
                            $html.='    <div>';
                            $html.='        <div style="float:left;width: 48%;margin-right: 15px;">';
                            $html.='        <div style="height: 130px">';
                            $html.='            <img alt="SIN LOGO" class="imgLogo" src="http://proforma.allparts.com.ec/public/images/logoAllparts.jpg" /><br><br><br><br>';
                            $html.='        </div>';
                            $html.= $sucursal;
                            $html.='<div style="clear:both"></div>';
                            $html.='<br>';
                            $html.='<div class="bordeCuadrado">';
                            $html.='    <table width="100%">';
                            $html.='        <tr>';
                            $html.='            <td><b>Razón Social / Nombres y Apellidos:</b> '.$RowCc00000->razonsocial.'</td>';
                            $html.='            <td></td>';
                            $html.='            <td><b>Identificación:</b> '.$RowCc00000->ruc.'</td>';
                            $html.='        </tr>';
                            $html.='        <tr>';
                            $html.='            <td><b>Fecha Emisión:</b> '.$RowCc00000->telefono.'</td>';
                            $html.='            <td></td>';
                            $html.='            <td></td>';
                            $html.='        </tr>';
                            $html.='        <tr>';
                            $html.='            <td><b>Dirección:</b> '.$RowCc00002->ciudad.' - '.$RowCc00002->provincia.' - '.$RowCc00002->direccion.'</td>';
                            $html.='            <td></td>';
                            $html.='            <td></td>';
                            $html.='        </tr>';
                            $html.='    </table>';
                            $html.='</div>';
                            $html.='<br>';
                            $html.='<div>';
                            $html.='    <table width="100%" class="bordeTabla">';
                            $html.='        <tr>';
                            $html.='            <td>Codigo</td>';
                            $html.='            <td>Descripción</td>';
                            $html.='            <td>Marca</td>';
                            $html.='            <td>Cantidad</td>';
                            $html.='            <td>Precio U</td>';
                            $html.='            <td>Precio Total</td>';
                            $html.='        </tr>';
                            $html.=$detalles;
                            $html.='    </table>';
                            $html.='</div>';
                            $html.='<br>';
                            $html.='<div>';
                            $html.='    <div style="float:left;width: 58%;margin-right: 15px;">';
                            $html.='        <div class="bordeCuadrado">';
                            $html.='            <br>  <b class="infoAdicional">Información Adicional</b><br><br>';
                            $html.='            <table width="100%">';
                            $html.='                <tr>';
                            $html.='                    <td>Monto Abonado: </td>';
                            $html.='                    <td>'.$montoAbonado.'</td>';
                            $html.='                </tr>';
                            $html.='                <tr>';
                            $html.='                    <td>Monto Pendiente: </td>';
                            $html.='                    <td>'.$montoPendiente.'</td>';
                            $html.='                </tr>';
                            $html.='            </table>';
                            $html.='        </div>';
                            $html.='        <br>';
                            $html.='    </div>';
                            //$html.='    <div style="float:left;width: 38%;margin-left: 480px;">';
                            $html.='    <div style="float:rigth;width: 38%;margin-left: 490px;">';
                            //$html.='      <table width="80%" class="bordeTablaLeft">';
                            $html.='        <table width="80%" class="bordeTablaLeft">';
                            $html.='            <tr>';
                            $html.='                <td>SUBTOTAL</td>';
                            $html.='                <td class="txtDerecha">'.$RowSis50200->subtotal_cc_tem.'</td>';
                            $html.='            </tr>';
                            $html.='            <tr>';
                            $html.='                <td>DESCUENTO</td>';
                            $html.='                <td class="txtDerecha">'.$RowSis50200->descuento_cc_tem.'</td>';
                            $html.='            </tr>';
                            $html.='            <tr>';
                            $html.='                <td>IVA</td>';
                            $html.='                <td class="txtDerecha">'.$RowSis50200->iva_cc_tem.'</td>';
                            $html.='            </tr>';
                            $html.='            <tr>';
                            $html.='                <td>TOTAL</td>';
                            $html.='                <td class="txtDerecha">'.$RowSis50200->total_cc_tem.'</td>';
                            $html.='            </tr>';
                            $html.='        </table>';
                            $html.='    </div>';
                            $html.='</div>';
                            $html.='</body>';
                            $html.='</html>';


                     
                            $sis00100 = new Entidades\Sis00100($this->adapter);
                            $respSis00100=$sis00100->getMultiObj('id',1);
                            $regSis00100=$respSis00100->fetch_object();
                            $mail=new \PHPMailer\PHPMailer\PHPMailer();
                            $mail->SMTPDebug  = 0;
                            $mail->Mailer="smtp";
                            $mail->Helo = "www.allparts.com.ec"; //Muy importante para que llegue a hotmail y otros
                            $mail->SMTPAuth=true;
                            $mail->Host=$regSis00100->smtp_hostname;
                            $mail->Port=$regSis00100->smtp_port; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
                            $mail->Username=$regSis00100->smtp_username;
                            $mail->Password=$regSis00100->smtp_password;
                            $mail->From=$regSis00100->smtp_username;
                            $mail->FromName='MUNDOMOTRIZ ';
                            $mail->Subject = "PEDIDO MUNDOMOTRIZ";
                            $mail->Timeout=60;
                            $mail->IsHTML(true);
                            $mail->AddAddress("baleman@allparts.com.ec");
                            $mail->AddAddress("eflores@mundomotriz.com.ec");
                            $mail->Body=$html;
                            $mail->AltBody='Estimado(a) se realizo un pedido de MUNDOMOTRIZ ';
                            $exito = $mail->Send();

                        
                    }
                       
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

                        //ELIMINAR EL CARRITO TEMPORAL
                        $guiaDetalleCarritoTemp->deleteMulti('id_cabecera',$this->session->get('idCarritoTemporal'));
                        $guiaCabeceraCarritoTemp->deleteMulti('id',$this->session->get('idCarritoTemporal'));

                        $Gui40000->updateMultiColum('numpedido', $valexplode[0].'-'.$newsecuencial, 'bodega',strtoupper($this->session->get('bodUsuario')));
                        $guiaCabeceraEntidad->commit();
                        echo 'OK';
                       }
    }
}

