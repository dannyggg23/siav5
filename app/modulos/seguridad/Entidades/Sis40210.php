<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40210 extends \EntidadBase {
	/**
	* @var string
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $parroquia;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var int
	*/
	private $fcreacion;

	/**
	* @var string
	*/
	private $cantonid;

	private $table;

	public function __construct($adapter,$bd='') {
            if (strlen($bd)>0) {
                $this->table=$bd.'.sis40210';
            }
            else {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40210':'sis40210';
            }
            parent::__construct($this->table,$adapter);
	}

	function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	function getCodigo() {
		return $this->codigo;
	}

	function setParroquia($parroquia) {
		$this->parroquia = $parroquia;
	}

	function getParroquia() {
		return $this->parroquia;
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

	function setCantonid($cantonid) {
		$this->cantonid = $cantonid;
	}

	function getCantonid() {
		return $this->cantonid;
	}

	public function save(){
		$query="INSERT INTO $this->table(codigo,parroquia,activo,fcreacion,cantonid)
			VALUES(NULL,
			'".$this->parroquia."',
			'".$this->activo."',
			'".$this->fcreacion."',
			'".$this->cantonid."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}