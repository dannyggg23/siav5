<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-18
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40300 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $anio;

	/**
	* @var int
	*/
	private $fecha_inicial;

	/**
	* @var int
	*/
	private $fecha_final;

	/**
	* @var int
	*/
	private $num_periodos;

	/**
	* @var int
	*/
	private $historico;

	/**
	* @var int
	*/
	private $empresa;

	private $table;

	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40300':'sis40300';
		parent::__construct($this->table,$adapter);
	}

	function setAnio($anio) {
		$this->anio = $anio;
	}

	function getAnio() {
		return $this->anio;
	}

	function setFecha_inicial($fecha_inicial) {
		$this->fecha_inicial = $fecha_inicial;
	}

	function getFecha_inicial() {
		return $this->fecha_inicial;
	}

	function setFecha_final($fecha_final) {
		$this->fecha_final = $fecha_final;
	}

	function getFecha_final() {
		return $this->fecha_final;
	}

	function setNum_periodos($num_periodos) {
		$this->num_periodos = $num_periodos;
	}

	function getNum_periodos() {
		return $this->num_periodos;
	}

	function setHistorico($historico) {
		$this->historico = $historico;
	}

	function getHistorico() {
		return $this->historico;
	}

	function setEmpresa($empresa) {
		$this->empresa = $empresa;
	}

	function getEmpresa() {
		return $this->empresa;
	}

	public function save(){
		$query="INSERT INTO $this->table(anio,fecha_inicial,fecha_final,num_periodos,historico,empresa)
			VALUES(
                        '".$this->anio."',
                        '".$this->fecha_inicial."',
			'".$this->fecha_final."',
			'".$this->num_periodos."',
			'".$this->historico."',
			'".$this->empresa."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}