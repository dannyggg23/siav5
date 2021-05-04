<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-03-27
-- Version:	1.0.{numero de veces que se edita}
****************************************************************/

class Sis40020 extends \EntidadBase
{
	/**
	* @var string
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
	private $entidad;

	/**
	* @var string
	*/
	private $columnid;

	/**
	* @var string
	*/
	private $columnid2;

	/**
	* @var int
	*/
	private $activo;

	public function __construct($adapter) {
		$table= 'sis40020';
		parent::__construct($table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
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

	function setEntidad($entidad) {
		$this->entidad = $entidad;
	}

	function getEntidad() {
		return $this->entidad;
	}

	function setColumnid($columnid) {
		$this->columnid = $columnid;
	}

	function getColumnid() {
		return $this->columnid;
	}

	function setColumnid2($columnid2) {
		$this->columnid2 = $columnid2;
	}

	function getColumnid2() {
		return $this->columnid2;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	public function save(){
		$query="INSERT INTO sis40020(id,formulario,usuario,fecha,conform,accion,metodo,encrypt,version,columnas,validacion,css,entidad,columnid,columnid2,activo)
				VALUES(NULL,
				'".$this->formulario."',
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
				'".$this->entidad."',
				'".$this->columnid."',
				'".$this->columnid2."',
				'".$this->activo."'
			);";
			$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
			return $save;
		}
}