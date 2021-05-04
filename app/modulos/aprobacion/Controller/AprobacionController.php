<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class AprobacionController extends Controllers
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
        $this->layout_aprobacion = new dti_layout_aprobacion($this->website);
 
    }
    
    public function exec()
    {
        $this->index();
    }

    public function index(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login"); 
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_aprobacion->aprobacion()
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Aprobacion de pedidos"
        ));
    }

    
    public function listarPedidos(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Cc10000Model=new \Models\Cc10000Model($this->adapter);
        $Cc10010=New Entidades\Cc10010($this->adapter);

        $rspta=$Cc10000Model->listarPedidos($this->session->get('usuario'));
        $data= Array();
       
        while ($reg=$rspta->fetch_object()){
            
            $costo=0;
            $porUtilidad=0;
            $respCc10010=$Cc10010->getMultiObj('cc10000id',$reg->id);
           
            while($regCc10010=$respCc10010->fetch_object()){
                $costo=$costo+($regCc10010->costo*$regCc10010->cantidad);
            }

            if($costo==0){
                $costo=1;
            }

            // die($costo.'_'.$reg->subtotal.'_'.$reg->id);
           
            $porUtilidad=(($reg->subtotal*100)/$costo)-100;
            $descuento=($reg->descuento*100)/($reg->subtotal+$reg->descuento);
                $data[]=array(
                    "0"=>($reg->aprobado) ?' <a onclick="mostrarPedido('.$reg->id.')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>'
                    .' <a onclick="desactivarPedido('.$reg->id.')" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-check"></i>No-Aprobar</a>'
                    :'<a onclick="mostrarPedido('.$reg->id.')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>'.
                    ' <a onclick="aprobarPedido('.$reg->id.')" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Aprobar</a>',
                    "1"=>$reg->documento,
                    "2"=>$reg->ruc,
                    "3"=>$reg->razonsocial,
                    "4"=>$reg->direccion,
                    "5"=>$reg->fecha,
                    "6"=>$reg->usuario,
                    "7"=>$reg->total,
                    "8"=>$reg->subtotal,
                    "9"=>$reg->descuento,
                    "10"=>(int)$descuento,
                    "11"=>number_format($porUtilidad,2,'.',''),
                    "12"=>($reg->alerta_porcentaje)?'<span class="btn btn-sm btn-rounded btn-danger">ALERTA</span>':'',
                    "13"=>$reg->monto_abonado,
                    "14"=>$reg->pendiente,
                    "15"=>($reg->aprobado)?'<span class="btn btn-sm btn-rounded btn-default">Aprobado</span>':'<span class="btn btn-sm btn-rounded btn-danger">No Aprobado</span>',
                );
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
    }

    public function listarCarrito(){ 
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Cc10000Model=new \Models\Cc10000Model($this->adapter);
        $Sis50300=New Entidades\Sis50300($this->adapter);

        $rspta=$Cc10000Model->listarCarrito();
        $data= Array();
        while ($reg=$rspta->fetch_object()){

            $costo=0;
            $porUtilidad=0;
            $respSis50300=$Sis50300->getMultiObj('id_cabecera',$reg->id);
           
            while($regSis50300=$respSis50300->fetch_object()){
                $costo=$costo+($regSis50300->costo_producto*$regSis50300->cantidad_producto);
            }
           
            if($costo==0){
                $costo=1;
            }
            $porUtilidad=(($reg->subtotal_cc_tem*100)/$costo)-100;

            if($reg->subtotal_cc_tem+$reg->descuento_cc_tem==0){
                $reg->subtotal_cc_tem=1;
                $reg->descuento_cc_tem=1;
            }
            $descuento=($reg->descuento_cc_tem*100)/($reg->subtotal_cc_tem+$reg->descuento_cc_tem);


                $data[]=array(
                    "0"=>($reg->aprobado) ?' <a onclick="mostrarCarrito('.$reg->id.')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>'
                    .' <a onclick="desactivarCarrito('.$reg->id.')" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-check"></i>No-Aprobar</a>'
                    :'<a onclick="mostrarCarrito('.$reg->id.')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>'.
                    ' <a onclick="aprobarCarrito('.$reg->id.')" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Aprobar</a>',
                    "1"=>$reg->id,
                    "2"=>$reg->ruc,
                    "3"=>$reg->razonsocial,
                    "4"=>$reg->direccion,
                    "5"=>$reg->fecha_cc_tem,
                    "6"=>$reg->id_usuario,
                    "7"=>$reg->total_cc_tem,
                    "8"=>$reg->subtotal_cc_tem,
                    "9"=>$reg->descuento_cc_tem,
                    "10"=>(int)$descuento,
                    "11"=>number_format($porUtilidad,2,'.',''),
                    "12"=>($reg->alerta_porcentaje)?'<span class="btn btn-sm btn-rounded btn-danger">ALERTA</span>':'',
                    "13"=>$reg->monto_abonado,
                    "14"=>$reg->pendiente,
                    "15"=>($reg->aprobado)?'<span class="btn btn-sm btn-rounded btn-default">Aprobado</span>':'<span class="btn btn-sm btn-rounded btn-danger">No Aprobado</span>',
                );
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
    }

     public function activarPedido($param=array()){

        $c10000=new Entidades\Cc10000($this->adapter);
        $resp=$c10000->updateMultiColum('aprobado',1,'id',$param['id']);
        echo $resp ? 1 : 0;

     }
     public function desactivarPedido($param=array()){
        $c10000=new Entidades\Cc10000($this->adapter);
        $resp=$c10000->updateMultiColum('aprobado',0,'id',$param['id']);
        echo $resp ? 1 : 0;
     }

     public function activarCarriro($param=array()){

        $c10000=new Entidades\Sis50200($this->adapter);
        $resp=$c10000->updateMultiColum('aprobado',1,'id',$param['id']);
        echo $resp ? 1 : 0;

     }
     public function desactivarCarriro($param=array()){
        $c10000=new Entidades\Sis50200($this->adapter);
        $resp=$c10000->updateMultiColum('aprobado',0,'id',$param['id']);
        echo $resp ? 1 : 0;
     }  

     public function editarPedido($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
       
        $cc10000 = new Entidades\Cc10000($this->adapter);
        $respcc10000=$cc10000->getMultiObj('id',$param['id']);
        $regcc10000=$respcc10000->fetch_object();
        $Cc10010 = new Entidades\Cc10010($this->adapter);
        $respcc10010=$Cc10010->getMultiObj('cc10000id',$regcc10000->id);
        $descuento=isset($param['descuento'])? $Cc10010->limpiarCadenaString($param["descuento"]):"";
        $return='1';
        $totalCabe=0;
        $SubtoCabe=0;
        $DesctoCabe=0;
       
        while($regCc10010=$respcc10010->fetch_object()){

            $TotalDesc=number_format(($descuento)/100*$regCc10010->precio,2,'.','')*$regCc10010->cantidad;
            $TotalSubto=number_format($regCc10010->precio-(number_format(($descuento)/100*$regCc10010->precio,2,'.','')),2,'.','')*$regCc10010->cantidad;

            $resp=$Cc10010->updateMultiColum('descuento',number_format($TotalDesc,2,'.',''),'id',$regCc10010->id);
            $resp ? $return='1' : $return='0';
            $resp=$Cc10010->updateMultiColum('subtotal',number_format($TotalSubto,2,'.',''),'id',$regCc10010->id);
            $resp ? $return='1' : $return='0';
            $SubtoCabe=number_format($SubtoCabe,2,'.','')+number_format($TotalSubto,2,'.','');
            $DesctoCabe=number_format($DesctoCabe,2,'.','')+number_format($TotalDesc,2,'.','');
        }

        $iva=($SubtoCabe*0.12);
        $totalCabe=$iva+$SubtoCabe;
        $cc10000->updateMultiColum('total',number_format($totalCabe,2,'.',''),'id',$param['id'])? $return='1' : $return='0';
        $cc10000->updateMultiColum('subtotal',number_format($SubtoCabe,2,'.',''),'id',$param['id'])? $return='1' : $return='0';
        $cc10000->updateMultiColum('descuento',number_format($DesctoCabe,2,'.',''),'id',$param['id'])? $return='1' : $return='0';
        $cc10000->updateMultiColum('iva',number_format($iva,2,'.',''),'id',$param['id'])? $return='1' : $return='0';
        $cc10000->updateMultiColum('descuento_porce',$descuento,'id',$param['id'])? $return='1' : $return='0';

        echo $return;

     }

     public function editarCarriro($param=array()){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $Sis50200=new Entidades\Sis50200($this->adapter);
        $Sis50300=new Entidades\Sis50300($this->adapter);
        $descuento=isset($param['descuento'])? $Sis50300->limpiarCadenaString($param["descuento"]):"";
        $respSis50300=$Sis50300->getMultiObj('id_cabecera',$param['id']);
        $return='1';
        $totalCabe=0;
        $SubtoCabe=0;
        $DesctoCabe=0;
       
        while($regSis50300=$respSis50300->fetch_object()){

            $TotalDesc=number_format(($descuento)/100*$regSis50300->precio_producto,2,'.','')*$regSis50300->cantidad_producto;
            $TotalSubto=number_format($regSis50300->precio_producto-(number_format(($descuento)/100*$regSis50300->precio_producto,2,'.','')),2,'.','')*$regSis50300->cantidad_producto;
            $resp=$Sis50300->updateMultiColum('descuento_producto',$TotalDesc,'id',$regSis50300->id);
            $resp ? $return='1' : $return='0';
            $resp=$Sis50300->updateMultiColum('subtotal_producto',$TotalSubto,'id',$regSis50300->id);
            $resp ? $return='1' : $return='0';
           
            $SubtoCabe=number_format($SubtoCabe,2,'.','')+number_format($TotalSubto,2,'.','');
            $DesctoCabe=number_format($DesctoCabe,2,'.','')+number_format($TotalDesc,2,'.','');
        }

      

        $iva=($SubtoCabe*0.12);
        $totalCabe=$iva+$SubtoCabe;
        $Sis50200->updateMultiColum('total_cc_tem',number_format($totalCabe,2,'.',''),'id',$param['id'])? $return='1' : $return='0';
        $Sis50200->updateMultiColum('subtotal_cc_tem',number_format($SubtoCabe,2,'.',''),'id',$param['id'])? $return='1' : $return='0';
        $Sis50200->updateMultiColum('descuento_cc_tem',number_format($DesctoCabe,2,'.',''),'id',$param['id'])? $return='1' : $return='0';
        $Sis50200->updateMultiColum('iva_cc_tem',number_format($iva,2,'.',''),'id',$param['id'])? $return='1' : $return='0';
        $Sis50200->updateMultiColum('descuento_porce_cc',$descuento,'id',$param['id'])? $return='1' : $return='0';
       
        echo $return;

     }

     public function descuentoClientes(){

        if (empty($this->session->get('usuario'))) $this->redirect("default","login"); 
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_aprobacion->descuentoClientes()
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Descuento Clientes"
        ));

     }

     public function listarClientes(){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\ClientesModel($this->adapter);
        $rspta=$conf->ListarClientes();
        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>'<button class="btn btn-warning" title="Editar Descuento" onclick="modificarDescuento('.$reg->id.','."'".$reg->descuento."'".')"><span class="fa fa-edit"></span></button>'.
                    ' <button class="btn btn-info" title="Estado de cuenta" onclick="detallesCuenta('."'".$reg->ruc."'".')"><span class="fa fa-eye"></span></button>',
                    "1"=>$reg->ruc,
                    "2"=>$reg->cliente,
                    "3"=>$reg->razonsocial,

                    "4"=>$reg->descuento,
                    "5"=>$reg->nivelprecio,
                    "6"=>$reg->direccion,
                    "7"=>$reg->telefono,
                    "8"=>$reg->ciudad);
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
     }

    public function editarDescuento($param=array()){

        $cc00000 = new Entidades\Cc00000($this->adapter);
        echo $cc00000->updateMultiColum('descuento',$param['monto'],'id',$param['id'])? 'OK':'FALSE';

    }

    public function listClientesBusquedaAjax($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $conf= new \Models\ClientesModel($this->adapter);
        $param['busqueda']=str_replace(" ","%",$param['busqueda']);
        $rspta=$conf->ListarClientesBusqueda($param['busqueda']);

        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>'<button class="btn btn-warning" title="Editar Descuento" onclick="modificarDescuento('.$reg->id.','."'".$reg->descuento."'".')"><span class="fa fa-edit"></span></button>'.
                    ' <button class="btn btn-info" title="Estado de cuenta" onclick="detallesCuenta('."'".$reg->ruc."'".')"><span class="fa fa-eye"></span></button>',
                    "1"=>$reg->ruc,
                    "2"=>$reg->cliente,
                    "3"=>$reg->razonsocial,

                    "4"=>$reg->descuento,
                    "5"=>$reg->nivelprecio,
                    "6"=>$reg->direccion,
                    "7"=>$reg->telefono,
                    "8"=>$reg->ciudad);
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);

    }
        


}

?>