<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Gui30000 extends \EntidadBase {
	
        private $id;
        private $documento;
        private $pedido;
        private $gui00000id;
        private $placa;
        private $fecha;
        private $fecha_inicio;
        private $fecha_fin;
        private $usuario;
        private $table;
        
	public function __construct($adapter,$param=array(),$bd='') {
            if (isset($param['param1']))
            {
                $this->table=$param['param1'].'.gui30000';
            }
            else if (strlen($bd)>0)
            {
                $this->table=$bd.'.gui30000';
            }
            else
            {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui30000':'gui30000';
            }
            parent::__construct($this->table,$adapter);
	}
        
        function getId() {
            return $this->id;
        }

        function getDocumento() {
            return $this->documento;
        }

        function getPedido() {
            return $this->pedido;
        }

        function getGui00000id() {
            return $this->gui00000id;
        }

        function getPlaca() {
            return $this->placa;
        }

        function getFecha() {
            return $this->fecha;
        }

        function getFechainicio() {
            return $this->fecha_inicio;
        }

        function getFechafin() {
            return $this->fecha_fin;
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

        function setPedido($pedido) {
            $this->pedido = $pedido;
        }

        function setGui00000id($gui00000id) {
            $this->gui00000id = $gui00000id;
        }

        function setPlaca($placa) {
            $this->placa = $placa;
        }

        function setFecha($fecha) {
            $this->fecha = $fecha;
        }

        function setFechainicio($fecha_inicio) {
            $this->fecha_inicio = $fecha_inicio;
        }

        function setFechafin($fecha_fin) {
            $this->fecha_fin = $fecha_fin;
        }


        function setUsuario($usuario) {
            $this->usuario = $usuario;
        }
        
	public function save()
        {
		$query="INSERT INTO $this->table (id,documento,pedido,gui00000id,placa,fecha,fecha_inicio,fecha_fin,usuario)
			VALUES(NULL,
			'".$this->documento."',
                        '".$this->pedido."',
			'".$this->gui00000id."',
                        '".$this->placa."',
			'".$this->fecha."',
                        '".$this->fecha_inicio."',
                        '".$this->fecha_fin."',
			'".$this->usuario."'
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