<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40120 extends \EntidadBase {
	/**
	* @var string
	* Class Unique ID
	*/
	private $formulario;

	/**
	* @var string
	*/
	private $usuario;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var int
	*/
	private $conform;

	/**
	* @var string
	*/
	private $accion;

	/**
	* @var string
	*/
	private $metodo;

	/**
	* @var string
	*/
	private $encrypt;

	/**
	* @var int
	*/
	private $version;

	/**
	* @var int
	*/
	private $columnas;

	/**
	* @var int
	*/
	private $validacion;

	/**
	* @var string
	*/
	private $css;

	/**
	* @var string
	*/
	private $controller;

	/**
	* @var string
	*/
	private $entidad;

	/**
	* @var string
	*/
	private $colid;

	/**
	* @var string
	*/
	private $colid2;

	/**
	* @var string
	*/
	private $colid3;

	/**
	* @var int
	*/
	private $activo;
        
        private $table;
        
	public function __construct($adapter) {
            $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40120':'sis40120';
            parent::__construct($this->table,$adapter);
	}

	function setFormulario($formulario) {
		$this->formulario = $formulario;
	}

	function getFormulario() {
		return $this->formulario;
	}

	function setUsuario($usuario) {
		$this->usuario = $usuario;
	}

	function getUsuario() {
		return $this->usuario;
	}

	function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	function getFecha() {
		return $this->fecha;
	}

	function setConform($conform) {
		$this->conform = $conform;
	}

	function getConform() {
		return $this->conform;
	}

	function setAccion($accion) {
		$this->accion = $accion;
	}

	function getAccion() {
		return $this->accion;
	}

	function setMetodo($metodo) {
		$this->metodo = $metodo;
	}

	function getMetodo() {
		return $this->metodo;
	}

	function setEncrypt($encrypt) {
		$this->encrypt = $encrypt;
	}

	function getEncrypt() {
		return $this->encrypt;
	}

	function setVersion($version) {
		$this->version = $version;
	}

	function getVersion() {
		return $this->version;
	}

	function setColumnas($columnas) {
		$this->columnas = $columnas;
	}

	function getColumnas() {
		return $this->columnas;
	}

	function setValidacion($validacion) {
		$this->validacion = $validacion;
	}

	function getValidacion() {
		return $this->validacion;
	}

	function setCss($css) {
		$this->css = $css;
	}

	function getCss() {
		return $this->css;
	}

	function setController($controller) {
		$this->controller = $controller;
	}

	function getController() {
		return $this->controller;
	}

	function setEntidad($entidad) {
		$this->entidad = $entidad;
	}

	function getEntidad() {
		return $this->entidad;
	}

	function setColid($colid) {
		$this->colid = $colid;
	}

	function getColid() {
		return $this->colid;
	}

	function setColid2($colid2) {
		$this->colid2 = $colid2;
	}

	function getColid2() {
		return $this->colid2;
	}

	function setColid3($colid3) {
		$this->colid3 = $colid3;
	}

	function getColid3() {
		return $this->colid3;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	public function save(){
		$query="INSERT INTO $this->table(formulario,usuario,fecha,conform,accion,metodo,encrypt,version,columnas,validacion,css,controller,entidad,colid,colid2,colid3,activo)
			VALUES(NULL,
			'".$this->usuario."',
			'".$this->fecha."',
			'".$this->conform."',
			'".$this->accion."',
			'".$this->metodo."',
			'".$this->encrypt."',
			'".$this->version."',
			'".$this->columnas."',
			'".$this->validacion."',
			'".$this->css."',
			'".$this->controller."',
			'".$this->entidad."',
			'".$this->colid."',
			'".$this->colid2."',
			'".$this->colid3."',
			'".$this->activo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}