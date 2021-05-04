<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00030 extends \EntidadBase {
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

	public function __construct($adapter) {
		$table='sis00030';
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

	public function save(){
		$query="INSERT INTO sis00030(id,nombre,descripcion,activo,accion,icono,imagen)
				VALUES(NULL,
				'".$this->nombre."',
				'".$this->descripcion."',
				'".$this->activo."',
				'".$this->accion."',
				'".$this->icono."',
				'".$this->imagen."'
			);";
			$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
			return $save;
		}
}