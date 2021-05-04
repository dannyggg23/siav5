<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis60000 extends \EntidadBase {
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
	private $href;

	/**
	* @var string
	*/
	private $icono;

	/**
	* @var int
	*/
	private $orden;

	/**
	* @var string
	*/
	private $hijos;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var int
	*/
	private $padre_id;
        
        private $table;
        
	public function __construct($adapter) {
            $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis60000':'sis60000';
            parent::__construct($this->table,$adapter);
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

	function setHref($href) {
		$this->href = $href;
	}

	function getHref() {
		return $this->href;
	}

	function setIcono($icono) {
		$this->icono = $icono;
	}

	function getIcono() {
		return $this->icono;
	}

	function setOrden($orden) {
		$this->orden = $orden;
	}

	function getOrden() {
		return $this->orden;
	}

	function setHijos($hijos) {
		$this->hijos = $hijos;
	}

	function getHijos() {
		return $this->hijos;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	function setPadre_id($padre_id) {
		$this->padre_id = $padre_id;
	}

	function getPadre_id() {
		return $this->padre_id;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,nombre,href,icono,orden,hijos,activo,padre_id)
			VALUES(NULL,
			'".$this->nombre."',
			'".$this->href."',
			'".$this->icono."',
			'".$this->orden."',
			'".$this->hijos."',
			'".$this->activo."',
			'".$this->padre_id."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}