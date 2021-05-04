<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Cos20020 extends \EntidadBase {
	
        private $id;
        private $documento;
        private $documento_orden;
        private $inv00000id;
        private $cantidad;
        private $cantidad_real;
        private $activo;
        private $mano_obra;
        private $porcen_mano_obra;
        private $usuario;
        private $porcen_cif;
        private $cif;
        private $table;
        
	public function __construct($adapter,$param=array(),$bd='') {
            if (isset($param['param1']))
            {
                $this->table=$param['param1'].'.cos20020';
            }
            else if (strlen($bd)>0)
            {
                $this->table=$bd.'.cos20020';
            }
            else
            {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cos20020':'cos20020';
            }
            parent::__construct($this->table,$adapter);
	}
        
        function getId() {
            return $this->id;
        }

        function getDocumento() {
            return $this->documento;
        }

        function getDocumento_orden() {
            return $this->documento_orden;
        }

        function getInv00000id() {
            return $this->inv00000id;
        }

        function getCantidad() {
            return $this->cantidad;
        }

        function getActivo() {
            return $this->activo;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setDocumento($documento) {
            $this->documento = $documento;
        }

        function setDocumento_orden($documento_orden) {
            $this->documento_orden = $documento_orden;
        }

        function setInv00000id($inv00000id) {
            $this->inv00000id = $inv00000id;
        }

        function setCantidad($cantidad) {
            $this->cantidad = $cantidad;
        }

        function setActivo($activo) {
            $this->activo = $activo;
        }
        
        function getCantidad_real() {
            return $this->cantidad_real;
        }

        function getMano_obra() {
            return $this->mano_obra;
        }

        function getPorcen_mano_obra() {
            return $this->porcen_mano_obra;
        }

        function getUsuario() {
            return $this->usuario;
        }

        function getPorcen_cif() {
            return $this->porcen_cif;
        }

        function getCif() {
            return $this->cif;
        }

        function setCantidad_real($cantidad_real) {
            $this->cantidad_real = $cantidad_real;
        }

        function setMano_obra($mano_obra) {
            $this->mano_obra = $mano_obra;
        }

        function setPorcen_mano_obra($porcen_mano_obra) {
            $this->porcen_mano_obra = $porcen_mano_obra;
        }

        function setUsuario($usuario) {
            $this->usuario = $usuario;
        }

        function setPorcen_cif($porcen_cif) {
            $this->porcen_cif = $porcen_cif;
        }

        function setCif($cif) {
            $this->cif = $cif;
        }
        
	public function save() {
            $query = "INSERT INTO $this->table(id,documento,documento_orden,inv00000id,cantidad,cantidad_real,porcen_mano_obra,mano_obra,porcen_cif,cif,usuario,activo)
                            VALUES(NULL,
                            '".$this->documento."',
                            '".$this->documento_orden."',
                            '".$this->inv00000id."',
                            '".$this->cantidad."',
                            '".$this->cantidad_real."',
                            '".$this->porcen_mano_obra."',
                            '".$this->mano_obra."',
                            '".$this->porcen_cif."',
                            '".$this->cif."',
                            '".$this->usuario."',
                            '".$this->activo."'
                          );";
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