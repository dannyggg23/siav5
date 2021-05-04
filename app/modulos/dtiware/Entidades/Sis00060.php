<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00060 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $descripcion;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var string
	*/
	private $accion;

	/**
	* @var string
	*/
	private $icono;

	/**
	* @var string
	*/
	private $imagen;

	/**
	* @var int
	*/
	private $orden;

	public function __construct($adapter) {
            $table='sis00060';
            parent::__construct($table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	function getNombre() {
		return $this->nombre;
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

	function setAccion($accion) {
		$this->accion = $accion;
	}

	function getAccion() {
		return $this->accion;
	}

	function setIcono($icono) {
		$this->icono = $icono;
	}

	function getIcono() {
		return $this->icono;
	}

	function setImagen($imagen) {
		$this->imagen = $imagen;
	}

	function getImagen() {
		return $this->imagen;
	}

	function setOrden($orden) {
		$this->orden = $orden;
	}

	function getOrden() {
		return $this->orden;
	}

	public function save(){
		$query="INSERT INTO sis00060(id,nombre,descripcion,activo,accion,icono,imagen,orden)
				VALUES(NULL,
				'".$this->nombre."',
				'".$this->descripcion."',
				'".$this->activo."',
				'".$this->accion."',
				'".$this->icono."',
				'".$this->imagen."',
				'".$this->orden."'
			);";
			$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
			return $save;
		}
}