<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-04
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20013 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $planid;

	/**
	* @var int
	*/
	private $clienteid;

	/**
	* @var string
	*/
	private $ruc;

	/**
	* @var int
	*/
	private $disponible;

	/**
	* @var int
	*/
	private $usado;

	/**
	* @var int
	*/
	private $activo;
        
        /**
	* @var date
	*/
	private $fecha;
        
	private $table;

	public function __construct($adapter) {
		$this->table='sis20013';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setPlanid($planid) {
		$this->planid = $planid;
	}

	function getPlanid() {
		return $this->planid;
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

	function setDisponible($disponible) {
		$this->disponible = $disponible;
	}

	function getDisponible() {
		return $this->disponible;
	}

	function setUsado($usado) {
		$this->usado = $usado;
	}

	function getUsado() {
		return $this->usado;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

        function getFecha() {
            return $this->fecha;
        }

        function setFecha($fecha) {
            $this->fecha = $fecha;
        }

	public function save(){
		$query="INSERT INTO $this->table(id,planid,clienteid,ruc,disponible,usado,fecha,activo)
			VALUES(NULL,
			'".$this->planid."',
			'".$this->clienteid."',
			'".$this->ruc."',
			'".$this->disponible."',
			'".$this->usado."',
                        '".$this->fecha."',
			'".$this->activo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}