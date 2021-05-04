<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-11-16
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40500 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $configuracion;

	/**
	* @var string
	*/
	private $valor;

	/**
	* @var string
	*/
	private $observacion;

	/**
	* @var int
	*/
	private $activo;
        private $tipo;
        private $mascara;

	private $table;

	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40500':'sis40500';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setConfiguracion($configuracion) {
		$this->configuracion = $configuracion;
	}

	function getConfiguracion() {
		return $this->configuracion;
	}

	function setValor($valor) {
		$this->valor = $valor;
	}

	function getValor() {
		return $this->valor;
	}

	function setObservacion($observacion) {
		$this->observacion = $observacion;
	}

	function getObservacion() {
		return $this->observacion;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}
        
        function getTipo() {
            return $this->tipo;
        }

        function getMascara() {
            return $this->mascara;
        }

        function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        function setMascara($mascara) {
            $this->mascara = $mascara;
        }
        
	public function save(){
		$query="INSERT INTO $this->table(id,configuracion,valor,observacion,tipo,mascara,activo)
			VALUES(NULL,
			'".$this->configuracion."',
			'".$this->valor."',
			'".$this->observacion."',
                        '".$this->tipo."',
                        '".$this->mascara."',
			'".$this->activo."'
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