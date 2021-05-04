<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2019-01-09
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis50000 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $usuario;

	/**
	* @var string
	*/
	private $bd;

	/**
	* @var int
	*/
	private $con;

	/**
	* @var int
	*/
	private $fecha;
        private $fecha_actividad;

	private $table;

	public function __construct($adapter) {
		$this->table='sis50000';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setUsuario($usuario) {
		$this->usuario = $usuario;
	}

	function getUsuario() {
		return $this->usuario;
	}

	function setBd($bd) {
		$this->bd = $bd;
	}

	function getBd() {
		return $this->bd;
	}

	function setCon($con) {
		$this->con = $con;
	}

	function getCon() {
		return $this->con;
	}

	function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	function getFecha() {
		return $this->fecha;
	}

        function getFecha_actividad() {
            return $this->fecha_actividad;
        }

        function setFecha_actividad($fecha_actividad) {
            $this->fecha_actividad = $fecha_actividad;
        }

	public function save(){
		$query="INSERT INTO $this->table(id,usuario,bd,con,fecha,fecha_actividad)
			VALUES(NULL,
			'".$this->usuario."',
			'".$this->bd."',
			'".$this->con."',
			'".$this->fecha."',
                        '".$this->fecha_actividad."'
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