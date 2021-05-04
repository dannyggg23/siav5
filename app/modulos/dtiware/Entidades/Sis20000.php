<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20000 extends \EntidadBase {
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
	private $rolid;

	public function __construct($adapter) {
		$table='sis20000';
		parent::__construct($table,$adapter);
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

	function setRolid($rolid) {
		$this->rolid = $rolid;
	}

	function getRolid() {
		return $this->rolid;
	}

	public function save(){
		$query="INSERT INTO sis20000(id,modulesid,rolid)
				VALUES(NULL,
				'".$this->modulesid."',
				'".$this->rolid."'
			);";
			$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
			return $save;
		}
}