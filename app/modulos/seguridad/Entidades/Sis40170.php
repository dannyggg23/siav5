<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40170 extends \EntidadBase {
	/**
	* @var string
	* Class Unique ID
	*/
	private $codigo;

	/**
	* @var string
	*/
	private $documento;

	/**
	* @var int
	*/
	private $fcreacion;

	/**
	* @var int
	*/
	private $activo;

	private $table;

	public function __construct($adapter,$bd='') {
            if (strlen($bd)>0) {
                $this->table=$bd.'.sis40170';
            }
            else {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40170':'sis40170';
            }
            parent::__construct($this->table,$adapter);
	}

	function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	function getCodigo() {
		return $this->codigo;
	}

	function setDocumento($documento) {
		$this->documento = $documento;
	}

	function getDocumento() {
		return $this->documento;
	}

	function setFcreacion($fcreacion) {
		$this->fcreacion = $fcreacion;
	}

	function getFcreacion() {
		return $this->fcreacion;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	public function save(){
		$query="INSERT INTO $this->table(codigo,documento,fcreacion,activo)
			VALUES(NULL,
			'".$this->documento."',
			'".$this->fcreacion."',
			'".$this->activo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}