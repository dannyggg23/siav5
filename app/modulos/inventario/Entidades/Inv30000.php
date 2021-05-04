<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-08-24
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Inv30000 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $documento;

	/**
	* @var int
	*/
	private $inv00000id;

	/**
	* @var string
	*/
	private $bodega;

	/**
	* @var int
	*/
	private $cantidad;

	/**
	* @var string
	*/
	private $bodega_destino;
        private $costo_unitario;
        private $precio_unitario;
        private $cos40100id;
        private $fecha;
        
	private $table;

	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv30000':'inv30000';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setDocumento($documento) {
		$this->documento = $documento;
	}

	function getDocumento() {
		return $this->documento;
	}

	function setInv00000id($inv00000id) {
		$this->inv00000id = $inv00000id;
	}

	function getInv00000id() {
		return $this->inv00000id;
	}

	function setBodega($bodega) {
		$this->bodega = $bodega;
	}

	function getBodega() {
		return $this->bodega;
	}

	function setCantidad($cantidad) {
		$this->cantidad = $cantidad;
	}

	function getCantidad() {
		return $this->cantidad;
	}

	function setBodega_destino($bodega_destino) {
		$this->bodega_destino = $bodega_destino;
	}

	function getBodega_destino() {
		return $this->bodega_destino;
	}

        function getCosto_unitario() {
            return $this->costo_unitario;
        }

        function getPrecio_unitario() {
            return $this->precio_unitario;
        }

        function setCosto_unitario($costo_unitario) {
            $this->costo_unitario = $costo_unitario;
        }

        function setPrecio_unitario($precio_unitario) {
            $this->precio_unitario = $precio_unitario;
        }
        
        function getFecha() {
            return $this->fecha;
        }

        function setFecha($fecha) {
            $this->fecha = $fecha;
        }
        
        function getCos40100id() {
            return $this->cos40100id;
        }

        function setCos40100id($cos40100id) {
            $this->cos40100id = $cos40100id;
        }
     
	public function save(){
		$query="INSERT INTO $this->table(id,documento,fecha,cos40100id,inv00000id,bodega,cantidad,costo_unitario,precio_unitario,bodega_destino)
			VALUES(NULL,
			'".$this->documento."',
                        '".$this->fecha."',
                        '".$this->cos40100id."',
			'".$this->inv00000id."',
			'".$this->bodega."',
			'".$this->cantidad."',
                        '".$this->costo_unitario."',
                        '".$this->precio_unitario."',
			'".$this->bodega_destino."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}

	public function REST($param=array()){
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
		header('Allow: GET, POST, OPTIONS, PUT, DELETE');
		header('Content-Type: application/json');
		$method = $_SERVER['REQUEST_METHOD'];
		switch ($method) {
		case 'GET':
			$this->get($param);
			break;
		case 'POST':
			$this->post();
			break;
		case 'PUT':
			$this->put();
			break;
		case 'DELETE':
			$this->delete();
			break;
		default:
			echo 'METODO NO SOPORTADO';
			break;
		}
	}

	private function response($code=200, $status='', $message='') {
		http_response_code($code);
		if( !empty($status) && !empty($message) ){
			$response = array('status' => $status ,'message'=>$message);
			echo json_encode($response,JSON_PRETTY_PRINT);
		}
	}

	private function get($param=array())
	{
		echo 'GET';
	}
	private function post()
	{
		echo 'POST';
	}
	private function delete()
	{
		echo 'DELETE';
	}
	private function put()
	{
		echo 'PUT';
	}
}