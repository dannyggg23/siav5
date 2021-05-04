<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40200 extends \EntidadBase {
	/**
	* @var string
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $canton;

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
	private $provinciaid;

	private $table;

	public function __construct($adapter,$bd='') {
            if (strlen($bd)>0) {
                $this->table=$bd.'.sis40200';
            }
            else {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40200':'sis40200';
            }
            parent::__construct($this->table,$adapter);
	}

	function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	function getCodigo() {
		return $this->codigo;
	}

	function setCanton($canton) {
		$this->canton = $canton;
	}

	function getCanton() {
		return $this->canton;
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

	function setProvinciaid($provinciaid) {
		$this->provinciaid = $provinciaid;
	}

	function getProvinciaid() {
		return $this->provinciaid;
	}

	public function save(){
		$query="INSERT INTO $this->table(codigo,canton,activo,fcreacion,provinciaid)
			VALUES(NULL,
			'".$this->canton."',
			'".$this->activo."',
			'".$this->fcreacion."',
			'".$this->provinciaid."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}