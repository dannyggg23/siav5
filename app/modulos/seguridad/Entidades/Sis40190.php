<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40190 extends \EntidadBase {
	/**
	* @var string
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $provincia;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var int
	*/
	private $fcreacion;

	private $table;

	public function __construct($adapter,$bd='') {
            if (strlen($bd)>0) {
                $this->table=$bd.'.sis40190';
            }
            else {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40190':'sis40190';
            }
            parent::__construct($this->table,$adapter);
	}

	function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	function getCodigo() {
		return $this->codigo;
	}

	function setProvincia($provincia) {
		$this->provincia = $provincia;
	}

	function getProvincia() {
		return $this->provincia;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	function setFcreacion($fcreacion) {
		$this->fcreacion = $fcreacion;
	}

	function getFcreacion() {
		return $this->fcreacion;
	}

	public function save(){
		$query="INSERT INTO $this->table(codigo,provincia,activo,fcreacion)
			VALUES(NULL,
			'".$this->provincia."',
			'".$this->activo."',
			'".$this->fcreacion."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}