<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class CosteoController extends Controllers
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
        $this->layout = new dti_layout($this->website);
        $this->layout_costeo = new dti_layout_costeo($this->website);
        //Cargamos la empresa logueada
        /*if (isset($_SESSION['rucEmpresa']))
        {
            $this->login_empresa = new \Entidades\Sis00100($this->adapter);
            $this->login_empresa = $this->login_empresa->getMulti('ruc', $_SESSION['rucEmpresa']);
        }*/
    }
    
    public function exec()
    {
        
    }

    public function listRecetas(){
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_costeo->recetas()
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Recetas"
        ));
    }

    public function listOrdenes(){
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_costeo->ordenTrabajo()
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Ordenes"
        ));
    }



    public function  listarReceta(){
   
        $Cos10000=new Entidades\Cos10000($this->adapter);
        $rspta=$Cos10000->getAllObj();
        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>($reg->activo)?' <a onclick="mostrar('.$reg->id.','."'".$reg->documento."'".')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>'
                    .' <a onclick="desactivar('.$reg->id.')" class="btn btn-warning btn-sm btn-icon icon-left"><i class="entypo-check"></i>Desactivar</a>'
                    :'<a onclick="mostrar('.$reg->id.','."'".$reg->documento."'".')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>'.
                    ' <a onclick="activar('.$reg->id.')" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Activar</a>',
                    "1"=>$reg->documento,
                    "2"=>$reg->descripcion,
                    "3"=>$reg->fecha,
                    "4"=>$reg->usuario,
                    "5"=>($reg->activo)?'<span class="btn btn-sm btn-rounded btn-success">Activado</span>'
                    :'<span class="btn btn-sm btn-rounded btn-danger">Desactivado</span>');
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);


    }

    public function listarEntradas(){

        $Cos10000=new Entidades\Inv00000($this->adapter);
        $rspta=$Cos10000->getMultiObj('materia_prima',1);
        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>'<a onclick="agregarEntrada('.$reg->id.','."'".$reg->descripcion."'".','."'".$reg->unidad."'".')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>+</a>',
                    "1"=>$reg->codigo,
                    "2"=>$reg->descripcion,
                    "3"=>$reg->costo,
                    "4"=>$reg->unidad);
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);

    }
    
    public function listarSalidas(){

        $Inv00000=new Entidades\Inv00000($this->adapter);
        $rspta=$Inv00000->getMultiObj('producido',1);
        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>'<a onclick="agregarSalida('.$reg->id.','."'".$reg->descripcion."'".','."'".$reg->unidad."'".')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>+</a>',
                    "1"=>$reg->codigo,
                    "2"=>$reg->descripcion,
                    "3"=>$reg->costo,
                    "4"=>$reg->unidad);
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
    }

    public function guardarReceta($param=Array()){
        $Cos10000=new Entidades\Cos10000($this->adapter);
        $Cos10010=new Entidades\Cos10010($this->adapter);
        $Cos10020=new Entidades\Cos10020($this->adapter);
        $documento=isset($_POST["id_receta"])? $Cos10020->limpiarCadenaString($_POST["id_receta"]):"";
        $return=1;
        $entradas=$param['id_Entrada'];
            $salidas=$param['id_salida'];
            $cantidadEntrada=$param['cantidadEntrada'];
            $tasaEntrada=$param['tasa'];
            $cantidadSalida=$param['cantidadSalida'];
        if (empty($documento)){
            $Cos10000->setDocumento($Cos10000->limpiarCadenaString($param['documento']));
            $Cos10000->setFecha(date('Y-m-d'));
            $Cos10000->setUsuario($this->session->get('usuario'));
            $Cos10000->setActivo(1);
            $Cos10000->setDescripcion($Cos10000->limpiarCadenaString($param['descripcion']));
            $Cos10000id=$Cos10000->save();
            if($Cos10000id>0){
            $num_elementos=0;
            while ($num_elementos < count($entradas)) {
                $Cos10010->setDocumento($Cos10000->limpiarCadenaString($param['documento']));
                $Cos10010->setInv00000id($entradas[$num_elementos]);
                $Cos10010->setCantidad($cantidadEntrada[$num_elementos]);
                $Cos10010->setTasa($tasaEntrada[$num_elementos]);
                ($Cos10010->save()>0)?$return=1:$return=0;
                $num_elementos=$num_elementos+1;
            }
            $num_elementos=0;
            while ($num_elementos < count($salidas)) {
                $Cos10020->setDocumento($Cos10000->limpiarCadenaString($param['documento']));
                $Cos10020->setInv00000id($salidas[$num_elementos]);
                $Cos10020->setCantidad($cantidadSalida[$num_elementos]);
                ($Cos10020->save()>0)?$return=1:$return=0;
                $num_elementos=$num_elementos+1;
            }
            }else{
                $return=0;
            }
        }else{
            $Cos10000->updateMultiColum('descripcion',$param['descripcion'],'documento',$documento)?$return=1:$return=0;
            $Cos10000->updateMultiColum('usuario',$this->session->get('usuario'),'documento',$documento)?$return=1:$return=0;
            $Cos10000->updateMultiColum('fecha',date('Y-m-d'),'documento',$documento)?$return=1:$return=0;
            $Cos10010->deleteMulti('documento',$documento);
            $Cos10020->deleteMulti('documento',$documento);
            if($return){
                $num_elementos=0;
                while ($num_elementos < count($entradas)) {
                    $Cos10010->setDocumento($documento);
                    $Cos10010->setInv00000id($entradas[$num_elementos]);
                    $Cos10010->setCantidad($cantidadEntrada[$num_elementos]);
                    $Cos10010->setTasa($tasaEntrada[$num_elementos]);
                    ($Cos10010->save()>0)?$return=1:$return=0;
                    $num_elementos=$num_elementos+1;
                }
                $num_elementos=0;
                while ($num_elementos < count($salidas)) {
                    $Cos10020->setDocumento($documento);
                    $Cos10020->setInv00000id($salidas[$num_elementos]);
                    $Cos10020->setCantidad($cantidadSalida[$num_elementos]);
                    ($Cos10020->save()>0)?$return=1:$return=0;
                    $num_elementos=$num_elementos+1;
                }
            }
        }
        echo $return;
    }

    public function mostrarReceta($param=Array()){
        $Cos10000=new Entidades\Cos10000($this->adapter);
        $Cos10000resp=$Cos10000->getMultiObj('id',$param['id']);
        $Cos10010reg=$Cos10000resp->fetch_object();
        echo json_encode($Cos10010reg);
    }

    public function mostrarEntradas($param=Array()){
       
        $id = [];
        $Cos10000=new \Models\Cos10010Model($this->adapter);
        $Cos10000resp=$Cos10000->listarEntradas($param['documento']);
        $html='';

        $cont=0;
        $detalles=0;
        while ($reg=$Cos10000resp->fetch_object()){
            array_push($id,$reg->inv00000id);
            $html.='<tr  class="filas" id="filaEntrada'.$cont.'">';
            $html.='<td > <input type="hidden" name="id_Entrada[]" id="id_Entrada[]" value="'.$reg->inv00000id.'"> <button type="button" class="btn btn-danger" onclick="eliminarDetalle('.$cont.')">X</button></td>';
            $html.='<td ><span>'.$reg->descripcion.'</span></td>';
            $html.='<td ><span>'.$reg->unidad.'</span></td>';
            $html.='<td ><input class="txtzize" type="number" step="0.01" min="0"  name="cantidadEntrada[]" id="cantidadEntrada[]" value="'.$reg->cantidad.'" required></td>';
            $html.='<td ><input class="txtzize" type="number" step="0.01" min="0"  name="tasa[]" id="tasa[]" value="'.$reg->tasa.'" required></td>';
            $html.='</tr>';
            $cont++;  
            $detalles=$detalles+1;        
        }
        $resp=array(
            'cont'=>$cont,
            'detalles'=>$detalles,
            'html'=>$html,
            'id'=>$id
        );
        echo json_encode($resp);

    }

    public function mostrarSalidas($param=Array()){
        $id = [];
        $Cos10000=new \Models\Cos10010Model($this->adapter);
        $Cos10000resp=$Cos10000->listarSalidas($param['documento']);
        $html='';
        $cont=0;
        $detalles=0;
        while ($reg=$Cos10000resp->fetch_object()){
            array_push($id,$reg->inv00000id);
            $html.='<tr  class="filas" id="filaSalida'.$cont.'">';
            $html.='<td > <input type="hidden" name="id_salida[]" id="id_salida[]" value="'.$reg->inv00000id.'"> <button type="button" class="btn btn-danger" onclick="eliminarDetalleSalida('.$cont.')">X</button></td>';
            $html.='<td ><span>'.$reg->descripcion.'</span></td>';
            $html.='<td ><span>'.$reg->unidad.'</span></td>';
            $html.='<td ><input class="txtzize" type="number" step="0.01" min="0"  name="cantidadSalida[]" id="cantidadSalida[]" value="'.$reg->cantidad.'" required></td>';
            $html.='</tr>';
            $cont++;  
            $detalles=$detalles+1;        
        }
        $resp=array(
            'cont'=>$cont,
            'detalles'=>$detalles,
            'html'=>$html,
            'id'=>$id
        );
        echo json_encode($resp);

    }

    public function desactivar($param=array()){
       $return=0;
       $Cos10000=new Entidades\Cos10000($this->adapter);
       $Cos10000->updateMultiColum('activo',0,'id',$param['id'])?$return=1:$return=0;
       echo $return;

    }
    public function activar($param=array()){
        $return=0;
        $Cos10000=new Entidades\Cos10000($this->adapter);
        $Cos10000->updateMultiColum('activo',1,'id',$param['id'])?$return=1:$return=0;
        echo $return;
    }

    public function listarOrdenes(){
       

        $Cos20100=new \Models\Cos10010Model($this->adapter);
        $rspta=$Cos20100->listarOrdenes();
        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>($reg->activo)?' <a onclick="mostrar('.$reg->id.','."'".$reg->documento."'".')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>'
                    .' <a onclick="desactivar('.$reg->id.')" class="btn btn-warning btn-sm btn-icon icon-left"><i class="entypo-check"></i>Desactivar</a>'
                    :'<a onclick="mostrar('.$reg->id.','."'".$reg->documento."'".')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>'.
                    ' <a onclick="activar('.$reg->id.')" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Activar</a>',
                    "1"=>$reg->documento,
                    "2"=>$reg->observacion,
                    "3"=>$reg->bodEntrada,
                    "4"=>$reg->bodSalida,
                    "5"=>$reg->fecha,
                    "6"=>$reg->usuario,
                    "7"=>$reg->Estado,
                    "8"=>($reg->activo)?'<span class="btn btn-sm btn-rounded btn-success">Activado</span>'
                    :'<span class="btn btn-sm btn-rounded btn-danger">Desactivado</span>');
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);

    }

    public function selectBodegas(){
        $Inv00001=new Entidades\Inv00001($this->adapter);
        $Inv00001resp=$Inv00001->getAllObj();

        $html='<option value=""></option>';
        while ($reg=$Inv00001resp->fetch_object()){
            $html.='<option value="'.$reg->id.'">'.$reg->bodega.' - '.$reg->descripcion.'</option>';
        }
        echo $html;
    }

    public function  listarRecetaOrden(){
   
        $Cos10000=new Entidades\Cos10000($this->adapter);
        $rspta=$Cos10000->getAllObj();
        $data= Array();
        while ($reg=$rspta->fetch_object()){
                $data[]=array(
                    "0"=>'<a onclick="agregarEntrada('.$reg->id.','."'".$reg->descripcion."'".','."'".$reg->documento."'".')" class="btn btn-info btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>+</a>',
                    "1"=>$reg->documento,
                    "2"=>$reg->descripcion,
                    "3"=>$reg->fecha,
                    "4"=>$reg->usuario);
        }
        $results = array(
                "sEcho"=>1, 
                "iTotalRecords"=>count($data), 
                "iTotalDisplayRecords"=>count($data), 
                "aaData"=>$data);
        echo json_encode($results);
    }

    public function mostrarSalidasOrden($param=Array()){
        $id = [];
        $Cos10000=new \Models\Cos10010Model($this->adapter);
        $Cos10000resp=$Cos10000->listarSalidas($param['documento']);
        $html='';
        $cont=0;
        $detalles=0;
        while ($reg=$Cos10000resp->fetch_object()){
            array_push($id,$reg->inv00000id);
            $html.='<tr  class="filas" id="filaSalida'.$cont.'">';
            $html.='<td > <input type="hidden" name="id_salida[]" id="id_salida[]" value="'.$reg->inv00000id.'"> <button type="button" class="btn btn-danger" onclick="eliminarDetalleSalida('.$cont.')">X</button></td>';
            $html.='<td ><span>'.$reg->descripcion.'</span></td>';
            $html.='<td ><span>'.$reg->unidad.'</span></td>';
            $html.='<td ><input class="txtzize" type="number" step="0.01" min="0"  name="cantidadSalida[]" id="cantidadSalida[]" value="" required></td>';
            $html.='</tr>';
            $cont++;  
            $detalles=$detalles+1;        
        }
        $resp=array(
            'cont'=>$cont,
            'detalles'=>$detalles,
            'html'=>$html,
            'id'=>$id
        );
        echo json_encode($resp);

    }

    public function cargarDocumentoOrden(){
        $Cos40100=new Entidades\Cos40100($this->adapter);
        $Cos40100resp=$Cos40100->getMultiObj('id',1);
        $Cos40100reg=$Cos40100resp->fetch_object();
        echo $Cos40100reg->prefijo.'-'.$Cos40100reg->secuencia;
    }
    
}