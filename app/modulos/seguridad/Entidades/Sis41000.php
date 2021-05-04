<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-06-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis41000 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $sis00200id;

	/**
	* @var int
	*/
	private $sis00300id;

	private $table;

	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis41000':'sis41000';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setSis00200id($sis00200id) {
		$this->sis00200id = $sis00200id;
	}

	function getSis00200id() {
		return $this->sis00200id;
	}

	function setSis00300id($sis00300id) {
		$this->sis00300id = $sis00300id;
	}

	function getSis00300id() {
		return $this->sis00300id;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,sis00200id,sis00300id)
			VALUES(NULL,
			'".$this->sis00200id."',
			'".$this->sis00300id."'
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