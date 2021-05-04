<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Cc11000 extends \EntidadBase {
	
        private $id;
        private $documento;
        private $cc00000id;
        private $cc00002id;
        private $fecha;
        private $subtotal;
        private $descuento;
        private $iva;
        private $total;
        private $usuario;
        private $descuento_porce;
        private $table;
        private $alerta_porcentaje;
        
        
	public function __construct($adapter,$param=array(),$bd='') {
            if (isset($param['param1']))
            {
                $this->table=$param['param1'].'.cc11000';
            }
            else if (strlen($bd)>0)
            {
                $this->table=$bd.'.cc11000';
            }
            else
            {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc11000':'cc11000';
            }
            parent::__construct($this->table,$adapter);
	}
        
        function getId() {
            return $this->id;
        }

        function getDescuento_porce() {
            return $this->descuento_porce;
        }

        function getAlerta_porcentaje() {
            return $this->alerta_porcentaje;
        }

        function getDocumento() {
            return $this->documento;
        }

        function getCc00000id() {
            return $this->cc00000id;
        }

        function getCc00002id() {
            return $this->cc00002id;
        }

        function getFecha() {
            return $this->fecha;
        }

        function getSubtotal() {
            return $this->subtotal;
        }

        function getDescuento() {
            return $this->descuento;
        }

        function getIva() {
            return $this->iva;
        }

        function getTotal() {
            return $this->total;
        }

        function getUsuario() {
            return $this->usuario;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setDocumento($documento) {
            $this->documento = $documento;
        }

        function setAlerta_porcentaje($alerta_porcentaje) {
            $this->alerta_porcentaje = $alerta_porcentaje;
        }

        function setDescuento_porce($descuento_porce) {
            $this->descuento_porce = $descuento_porce;
        }

        function setCc00000id($cc00000id) {
            $this->cc00000id = $cc00000id;
        }

        function setCc00002id($cc00002id) {
            $this->cc00002id = $cc00002id;
        }

        function setFecha($fecha) {
            $this->fecha = $fecha;
        }

        function setSubtotal($subtotal) {
            $this->subtotal = $subtotal;
        }

        function setDescuento($descuento) {
            $this->descuento = $descuento;
        }

        function setIva($iva) {
            $this->iva = $iva;
        }

        function setTotal($total) {
            $this->total = $total;
        }

        function setUsuario($usuario) {
            $this->usuario = $usuario;
        }
        
	public function save()
        {
		$query="INSERT INTO $this->table (id,documento,cc00000id,cc00002id,fecha,subtotal,descuento,iva,total,usuario,descuento_porce)
			VALUES(NULL,
			'".$this->documento."',
                        '".$this->cc00000id."',
                        '".$this->cc00002id."',
                        '".$this->fecha."',
                        '".$this->subtotal."',
                        '".$this->descuento."',
                        '".$this->iva."',
                        '".$this->total."',
                        '".$this->usuario."',
                        '".$this->descuento_porce."'
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