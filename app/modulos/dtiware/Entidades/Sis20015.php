<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-10
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20015 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $modulesid;

	/**
	* @var string
	*/
	private $descripcion;

	/**
	* @var int
	*/
	private $activo;

	private $table;

	public function __construct($adapter) {
		$this->table='sis20015';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setModulesid($modulesid) {
		$this->modulesid = $modulesid;
	}

	function getModulesid() {
		return $this->modulesid;
	}

	function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}

	function getDescripcion() {
		return $this->descripcion;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,modulesid,descripcion,activo)
			VALUES(NULL,
			'".$this->modulesid."',
			'".$this->descripcion."',
			'".$this->activo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}