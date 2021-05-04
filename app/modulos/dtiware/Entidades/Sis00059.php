<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-04
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00059 extends \EntidadBase {
	/**
	* @var int
	*/
	private $clienteid;

	/**
	* @var string
	* Class Unique ID
	*/
	private $ruc;

	/**
	* @var string
	*/
	private $razonsocial;

	/**
	* @var string
	*/
	private $nomempresa;
        
        /**
	* @var string
	*/
	private $prefijo;

	private $table;

	public function __construct($adapter) {
		$this->table='sis00059';
		parent::__construct($this->table,$adapter);
	}

	function setClienteid($clienteid) {
		$this->clienteid = $clienteid;
	}

	function getClienteid() {
		return $this->clienteid;
	}

	function setRuc($ruc) {
		$this->ruc = $ruc;
	}

	function getRuc() {
		return $this->ruc;
	}

	function setRazonsocial($razonsocial) {
		$this->razonsocial = $razonsocial;
	}

	function getRazonsocial() {
		return $this->razonsocial;
	}

	function setNomempresa($nomempresa) {
		$this->nomempresa = $nomempresa;
	}

	function getNomempresa() {
		return $this->nomempresa;
	}

        function getPrefijo() {
            return $this->prefijo;
        }

        function setPrefijo($prefijo) {
            $this->prefijo = $prefijo;
        }

	public function save(){
		$query="INSERT INTO $this->table(clienteid,ruc,razonsocial,nomempresa,prefijo)
			VALUES(
			'".$this->clienteid."',
                        '".$this->ruc."',
			'".$this->razonsocial."',
			'".$this->nomempresa."',
                        '".$this->prefijo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}