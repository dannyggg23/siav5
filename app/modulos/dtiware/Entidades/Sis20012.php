<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-04
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20012 extends \EntidadBase {
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
	* @var int
	*/
	private $clienteid;

	/**
	* @var string
	*/
	private $ruc;

	/**
	* @var int
	*/
	private $activo;

	private $table;

	public function __construct($adapter) {
		$this->table='sis20012';
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

	function setClienteid($clienteid) {
		$this->clienteid = $clienteid;
	}

	function getClienteid() {
		return $this->clienteid;
	}

	function setRuc($ruc) {
		$this->ruc = $ruc;
	}

	function getRuc() {
		return $this->ruc;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,modulesid,clienteid,ruc,activo)
			VALUES(NULL,
			'".$this->modulesid."',
			'".$this->clienteid."',
			'".$this->ruc."',
			'".$this->activo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}