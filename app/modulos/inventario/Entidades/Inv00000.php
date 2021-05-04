<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Inv00000 extends \EntidadBase {
	
        private $id;
        private $codigo;
        private $costo;
        private $descripcion;
        private $descripcioncorta;
        private $descripciongenerica;
        private $descuento;
        private $tipo;
        private $descontinuado;
        private $fechamodificada;
        private $fechacreacion;
        private $linea;
        private $sublinea;
        private $marcavehiculo;
        private $modelo;
        private $marcaproducto;
        private $codoriginal1;
        private $codoriginal2;
        private $codoriginal3;
        private $codanterior;
        private $inactivo;
        private $table;
        private $vender;
        
        /*Costeo*/
        private $unidad;
        private $maquinaria;
        private $compra;
        private $venta;
        private $producido;
        private $materia_prima;
        private $activo;
        
	public function __construct($adapter,$param=array(),$bd='') {
            if (isset($param['param1']))
            {
                $this->table=$param['param1'].'.inv00000';
            }
            else if (strlen($bd)>0)
            {
                $this->table=$bd.'.inv00000';
            }
            else
            {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
            }
            parent::__construct($this->table,$adapter);
	}
        
        function getId() {
            return $this->id;
        }

        function getVender() {
            return $this->vender;
        }

        function getCodigo() {
            return $this->codigo;
        }

        function getCosto() {
            return $this->costo;
        }

        function getDescripcion() {
            return $this->descripcion;
        }

        function getDescripcioncorta() {
            return $this->descripcioncorta;
        }

        function getDescripciongenerica() {
            return $this->descripciongenerica;
        }

        function getDescuento() {
            return $this->descuento;
        }

        function getTipo() {
            return $this->tipo;
        }

        function getDescontinuado() {
            return $this->descontinuado;
        }

        function getFechamodificada() {
            return $this->fechamodificada;
        }

        function getFechacreacion() {
            return $this->fechacreacion;
        }

        function getLinea() {
            return $this->linea;
        }

        function getSublinea() {
            return $this->sublinea;
        }

        function getMarcavehiculo() {
            return $this->marcavehiculo;
        }

        function getModelo() {
            return $this->modelo;
        }

        function getMarcaproducto() {
            return $this->marcaproducto;
        }

        function getCodoriginal1() {
            return $this->codoriginal1;
        }

        function getCodoriginal2() {
            return $this->codoriginal2;
        }

        function getCodoriginal3() {
            return $this->codoriginal3;
        }

        function getCodanterior() {
            return $this->codanterior;
        }

        function getInactivo() {
            return $this->inactivo;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setVender($vender) {
            $this->vender = $vender;
        }


        function setCodigo($codigo) {
            $this->codigo = $codigo;
        }

        function setCosto($costo) {
            $this->costo = $costo;
        }

        function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
        }

        function setDescripcioncorta($descripcioncorta) {
            $this->descripcioncorta = $descripcioncorta;
        }

        function setDescripciongenerica($descripciongenerica) {
            $this->descripciongenerica = $descripciongenerica;
        }

        function setDescuento($descuento) {
            $this->descuento = $descuento;
        }

        function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        function setDescontinuado($descontinuado) {
            $this->descontinuado = $descontinuado;
        }

        function setFechamodificada($fechamodificada) {
            $this->fechamodificada = $fechamodificada;
        }

        function setFechacreacion($fechacreacion) {
            $this->fechacreacion = $fechacreacion;
        }

        function setLinea($linea) {
            $this->linea = $linea;
        }

        function setSublinea($sublinea) {
            $this->sublinea = $sublinea;
        }

        function setMarcavehiculo($marcavehiculo) {
            $this->marcavehiculo = $marcavehiculo;
        }

        function setModelo($modelo) {
            $this->modelo = $modelo;
        }

        function setMarcaproducto($marcaproducto) {
            $this->marcaproducto = $marcaproducto;
        }

        function setCodoriginal1($codoriginal1) {
            $this->codoriginal1 = $codoriginal1;
        }

        function setCodoriginal2($codoriginal2) {
            $this->codoriginal2 = $codoriginal2;
        }

        function setCodoriginal3($codoriginal3) {
            $this->codoriginal3 = $codoriginal3;
        }

        function setCodanterior($codanterior) {
            $this->codanterior = $codanterior;
        }

        function setInactivo($inactivo) {
            $this->inactivo = $inactivo;
        }
        
        function getUnidad() {
            return $this->unidad;
        }

        function getMaquinaria() {
            return $this->maquinaria;
        }

        function getCompra() {
            return $this->compra;
        }

        function getVenta() {
            return $this->venta;
        }

        function getProducido() {
            return $this->producido;
        }

        function getMateria_prima() {
            return $this->materia_prima;
        }

        function getActivo() {
            return $this->activo;
        }

        function setUnidad($unidad) {
            $this->unidad = $unidad;
        }

        function setMaquinaria($maquinaria) {
            $this->maquinaria = $maquinaria;
        }

        function setCompra($compra) {
            $this->compra = $compra;
        }

        function setVenta($venta) {
            $this->venta = $venta;
        }

        function setProducido($producido) {
            $this->producido = $producido;
        }

        function setMateria_prima($materia_prima) {
            $this->materia_prima = $materia_prima;
        }

        function setActivo($activo) {
            $this->activo = $activo;
        }
        
	public function save($tipo='') {
            switch ($tipo) {
                case 'costeo':
                    $query = "INSERT INTO $this->table(id,codigo,descripcion,costo,unidad,maquinaria,compra,vender,producido,materia_prima,activo)
                            VALUES(NULL,
                            '".$this->codigo."',
                            '".$this->descripcion."',
                            '".$this->costo."',
                            '".$this->unidad."',
                            '".$this->maquinaria."',
                            '".$this->compra."',
                            '".$this->vender."',
                            '".$this->producido."',
                            '".$this->materia_prima."',
                            '".$this->activo."'
                          );";
                    break;
                default:
                    $query="INSERT INTO $this->table (`id`, `codigo`, `costo`, `descripcion`, `descripcioncorta`, `descripciongenerica`, `tipo`, `descontinuado`, `fechamodificada`, `fechacreacion`, `linea`, `sublinea`, `marcavehiculo`, `modelo`, `marcaproducto`, `codoriginal1`, `codoriginal2`, `codoriginal3`, `codanterior`, `inactivo`)
                            VALUES(NULL,
                            '".$this->codigo."',
                            '".$this->costo."',
                            '".$this->descripcion."',
                            '".$this->descripcioncorta."',
                            '".$this->descripciongenerica."',
                            '".$this->tipo."',
                            '".$this->descontinuado."',
                            '".$this->fechamodificada."',
                            '".$this->fechacreacion."',
                            '".$this->linea."',
                            '".$this->sublinea."',
                            '".$this->marcavehiculo."',
                            '".$this->modelo."',
                            '".$this->marcaproducto."',
                            '".$this->codoriginal1."',
                            '".$this->codoriginal2."',
                            '".$this->codoriginal3."',
                            '".$this->codanterior."',          
                            '".$this->inactivo."'
                    );";
                    break;
            }
            $this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
            return $this->db()->insert_id;
	}
        
        /**
        * Activa el web service rest
        */
        public function REST($param=array()){
            header('Content-Type: application/json');
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($method) {
            case 'GET'://consulta
                $this->get($param);
                //echo 'GET';
                break;
            case 'POST'://inserta
                $this->post();
                //echo 'POST';
                break;
            case 'PUT'://actualiza
                $this->put();
                //echo 'PUT';
                break;
            case 'DELETE'://elimina
                $this->delete();
                //echo 'DELETE';
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
            }
        }

        /**
        * Respuesta al cliente
        * @param int $code Codigo de respuesta HTTP
        * @param String $status indica el estado de la respuesta puede ser "success" o "error"
        * @param String $message Descripcion de lo ocurrido
        */
        private function response($code=200, $status="", $message="") {
           http_response_code($code);
           if( !empty($status) && !empty($message) ){
               $response = array("status" => $status ,"message"=>$message);
               echo json_encode($response,JSON_PRETTY_PRINT);
           }
        }

        /**
        * función que segun el valor de "action" e "id":
        *  - mostrara una array con todos los registros de personas
        *  - mostrara un solo registro 
        *  - mostrara un array vacio
        */
        private function get($param=array())
        {   
            if (isset($param['param1']))
            {
                $documento = new \Models\Sis10000Model($this->adapter);
                $bd = new \Models\Sis00050Model($this->adapter);
                $dtbd = $bd->getCountBDCliente($param['param1']);
                if ($dtbd['numrows']>0)
                {
                    if($param['param2']=='fe_documentos' && $param['param3']!='')
                    {
                        $response = $documento->getAutorizacion($param['param3'],$param['param4'],$param);
                        if (is_array($response)) {
                            $this->response(200,'Correcto',$response);
                        }else{
                            $this->response(400,'Error',$response);
                        }
                    }
                    else if($param['param2']=='fe_documentos')
                    {
                        $response = $documento->getFirmar($param);
                        if (is_array($response)) {
                            $this->response(200,'Correcto',$response);
                        }else{
                            $this->response(400,'Error',$response);
                        }
                    }
                    else
                    {
                        $this->response(400,'Error','Datos Erroneos, comunicarse con el soporte@dtiware.com');
                    }
                }
                else
                {
                    $this->response(400,'Error','Credenciales incorrectas, comunicarse con el soporte@dtiware.com');
                }
            }
        }

        private function post()
        {
        }

        private function put()
        {
        }

        private function delete()
        {
        }
}
