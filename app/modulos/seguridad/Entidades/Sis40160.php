<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40160 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $ambiente;

	/**
	* @var int
	*/
	private $activo;
        
        private $table;
        
	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40160':'sis40160';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setAmbiente($ambiente) {
		$this->ambiente = $ambiente;
	}

	function getAmbiente() {
		return $this->ambiente;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,ambiente,activo)
			VALUES(NULL,
			'".$this->ambiente."',
			'".$this->activo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}