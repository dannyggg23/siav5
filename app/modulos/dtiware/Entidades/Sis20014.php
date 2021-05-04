<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-04
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20014 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $pagoid;

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
	private $valor;

	/**
	* @var string
	*/
	private $fecha;

	/**
	* @var int
	*/
	private $activo;

	private $table;

	public function __construct($adapter) {
		$this->table='sis20014';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setPagoid($pagoid) {
		$this->pagoid = $pagoid;
	}

	function getPagoid() {
		return $this->pagoid;
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

	function setValor($valor) {
		$this->valor = $valor;
	}

	function getValor() {
		return $this->valor;
	}

	function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	function getFecha() {
		return $this->fecha;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,pagoid,clienteid,ruc,valor,fecha,activo)
			VALUES(NULL,
			'".$this->pagoid."',
			'".$this->clienteid."',
			'".$this->ruc."',
			'".$this->valor."',
			'".$this->fecha."',
			'".$this->activo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}