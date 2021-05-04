<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-18
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40310 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $sis40300id;

	/**
	* @var int
	*/
	private $periodo;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var int
	*/
	private $modulo;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var int
	*/
	private $empresa;

	private $table;

	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40310':'sis40310';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setSis40300id($sis40300id) {
		$this->sis40300id = $sis40300id;
	}

	function getSis40300id() {
		return $this->sis40300id;
	}

	function setPeriodo($periodo) {
		$this->periodo = $periodo;
	}

	function getPeriodo() {
		return $this->periodo;
	}

	function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	function getFecha() {
		return $this->fecha;
	}

	function setModulo($modulo) {
		$this->modulo = $modulo;
	}

	function getModulo() {
		return $this->modulo;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	function setEmpresa($empresa) {
		$this->empresa = $empresa;
	}

	function getEmpresa() {
		return $this->empresa;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,sis40300id,periodo,fecha,modulo,activo,empresa)
			VALUES(NULL,
			'".$this->sis40300id."',
			'".$this->periodo."',
			'".$this->fecha."',
			'".$this->modulo."',
			'".$this->activo."',
			'".$this->empresa."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}