<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Cc10010 extends \EntidadBase {
	
        private $id;
        private $cc10000id;
        private $inv00000codigo;
        private $descripcion;
        private $costo;
        private $bodega;
        private $cantidad;
        private $precio;
        private $descuento;
        private $subtotal;
        private $cantidad_guia;
        private $marca_producto;
        private $stock_producto;
        private $table;
        private $guia;
        private $descuento_cliente;
        
	public function __construct($adapter,$param=array(),$bd='') {
            if (isset($param['param1']))
            {
                $this->table=$param['param1'].'.cc10010';
            }
            else if (strlen($bd)>0)
            {
                $this->table=$bd.'.cc10010';
            }
            else
            {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc10010':'cc10010';
            }
            parent::__construct($this->table,$adapter);
	}
             
        function getInv00000codigo() {
            return $this->inv00000codigo;
        }

        function getDescuento_cliente() {
            return $this->descuento_cliente;
        }


        

        function setInv00000codigo($inv00000codigo) {
            $this->inv00000codigo = $inv00000codigo;
        }

        function setDescuento_cliente($descuento_cliente) {
            $this->descuento_cliente = $descuento_cliente;
        }

        

        function setMarca_producto($marca_producto) {
            $this->marca_producto = $marca_producto;
        }

        function setStock_producto($stock_producto) {
            $this->stock_producto = $stock_producto;
        }

        function getId() {
            return $this->id;
        }

        function getMarca_producto() {
            return $this->marca_producto;
        }

        function getStock_producto() {
            return $this->stock_producto;
        }

        function setGuia($guia) {
            $this->guia = $guia;
        }

        function setCantidad_guia($cantidad_guia) {
            $this->cantidad_guia = $cantidad_guia;
        }

        function getCantidad_guia() {
            return  $this->cantidad_guia;
         }

        function getGuia() {
           return  $this->guia;
        }


        function getCc10000id() {
            return $this->cc10000id;
        }

        function getDescripcion() {
            return $this->descripcion;
        }

        function getCosto() {
            return $this->costo;
        }

        function getBodega() {
            return $this->bodega;
        }

        function getCantidad() {
            return $this->cantidad;
        }

        function getPrecio() {
            return $this->precio;
        }

        function getDescuento() {
            return $this->descuento;
        }

        function getSubtotal() {
            return $this->subtotal;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setCc10000id($cc10000id) {
            $this->cc10000id = $cc10000id;
        }

        function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
        }

        function setCosto($costo) {
            $this->costo = $costo;
        }

        function setBodega($bodega) {
            $this->bodega = $bodega;
        }

        function setCantidad($cantidad) {
            $this->cantidad = $cantidad;
        }

        function setPrecio($precio) {
            $this->precio = $precio;
        }

        function setDescuento($descuento) {
            $this->descuento = $descuento;
        }

        function setSubtotal($subtotal) {
            $this->subtotal = $subtotal;
        }
        
	public function save()
        {
		$query="INSERT INTO $this->table (id,cc10000id,inv00000codigo,descripcion,costo,bodega,cantidad,precio,descuento,subtotal,guia,cantidad_guia,marca_producto,stock_producto,descuento_cliente)
			VALUES(NULL,
			'".$this->cc10000id."',
                        '".$this->inv00000codigo."',
                        '".$this->descripcion."',
                        '".$this->costo."',
                        '".$this->bodega."',
                        '".$this->cantidad."',
                        '".$this->precio."',
                        '".$this->descuento."',
                        '".$this->subtotal."',
                        '".$this->guia."',
                        '".$this->cantidad_guia."' ,
                        '".$this->marca_producto."' ,
                        '".$this->stock_producto."', 
                        '".$this->descuento_cliente."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
    }

    public function save_id()
        {
		$query="INSERT INTO $this->table (id,cc10000id,inv00000codigo,descripcion,costo,bodega,cantidad,precio,descuento,subtotal,guia,cantidad_guia,marca_producto,stock_producto,descuento_cliente)
			VALUES(NULL,
			'".$this->cc10000id."',
                        '".$this->inv00000codigo."',
                        '".$this->descripcion."',
                        '".$this->costo."',
                        '".$this->bodega."',
                        '".$this->cantidad."',
                        '".$this->precio."',
                        '".$this->descuento."',
                        '".$this->subtotal."',
                        '".$this->guia."',
                        '".$this->cantidad_guia."' ,
                        '".$this->marca_producto."' ,
                        '".$this->stock_producto."' ,
                        '".$this->descuento_cliente."' 
                        

		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
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