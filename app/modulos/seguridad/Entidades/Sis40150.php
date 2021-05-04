<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40150 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $moneda;

	/**
	* @var string
	*/
	private $abreviatura;

	/**
	* @var string
	*/
	private $descripcion;
        
        private $table;
        
	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40150':'sis40150';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setMoneda($moneda) {
		$this->moneda = $moneda;
	}

	function getMoneda() {
		return $this->moneda;
	}

	function setAbreviatura($abreviatura) {
		$this->abreviatura = $abreviatura;
	}

	function getAbreviatura() {
		return $this->abreviatura;
	}

	function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}

	function getDescripcion() {
		return $this->descripcion;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,moneda,abreviatura,descripcion)
			VALUES(NULL,
			'".$this->moneda."',
			'".$this->abreviatura."',
			'".$this->descripcion."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}