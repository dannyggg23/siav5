<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-09
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00061 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre_plan;

	/**
	* @var string
	*/
	private $descripcion;

	/**
	* @var int
	*/
	private $sis00060id;

	/**
	* @var int
	*/
	private $costo;

	/**
	* @var int
	*/
	private $cantidad;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var int
	*/
	private $orden;

	private $table;

	public function __construct($adapter) {
		$this->table='sis00061';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setNombre_plan($nombre_plan) {
		$this->nombre_plan = $nombre_plan;
	}

	function getNombre_plan() {
		return $this->nombre_plan;
	}

	function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}

	function getDescripcion() {
		return $this->descripcion;
	}

	function setSis00060id($sis00060id) {
		$this->sis00060id = $sis00060id;
	}

	function getSis00060id() {
		return $this->sis00060id;
	}

	function setCosto($costo) {
		$this->costo = $costo;
	}

	function getCosto() {
		return $this->costo;
	}

	function setCantidad($cantidad) {
		$this->cantidad = $cantidad;
	}

	function getCantidad() {
		return $this->cantidad;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	function setOrden($orden) {
		$this->orden = $orden;
	}

	function getOrden() {
		return $this->orden;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,nombre_plan,descripcion,sis00060id,costo,cantidad,activo,orden)
			VALUES(NULL,
			'".$this->nombre_plan."',
			'".$this->descripcion."',
			'".$this->sis00060id."',
			'".$this->costo."',
			'".$this->cantidad."',
			'".$this->activo."',
			'".$this->orden."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}