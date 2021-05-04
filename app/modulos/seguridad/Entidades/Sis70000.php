<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-24
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis70000 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $modulo;

	/**
	* @var string
	*/
	private $documento;

	/**
	* @var string
	*/
	private $descripcion;

	/**
	* @var string
	*/
	private $correo;

	/**
	* @var string
	*/
	private $estado;

	/**
	* @var int
	*/
	private $fecha_envio;

	/**
	* @var int
	*/
	private $fecha_entregado;
        private $empresa;
        private $tipo;
        
	private $table;

	public function __construct($adapter,$param) {
            if (isset($param['param1']))
            {
                $this->table=$param['param1'].'.sis70000';
            }
            else
            {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis70000':'sis70000';
            }
            parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setModulo($modulo) {
		$this->modulo = $modulo;
	}

	function getModulo() {
		return $this->modulo;
	}

	function setDocumento($documento) {
		$this->documento = $documento;
	}

	function getDocumento() {
		return $this->documento;
	}

	function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}

	function getDescripcion() {
		return $this->descripcion;
	}

	function setCorreo($correo) {
		$this->correo = $correo;
	}

	function getCorreo() {
		return $this->correo;
	}

	function setEstado($estado) {
		$this->estado = $estado;
	}

	function getEstado() {
		return $this->estado;
	}

	function setFecha_envio($fecha_envio) {
		$this->fecha_envio = $fecha_envio;
	}

	function getFecha_envio() {
		return $this->fecha_envio;
	}

	function setFecha_entregado($fecha_entregado) {
		$this->fecha_entregado = $fecha_entregado;
	}

	function getFecha_entregado() {
		return $this->fecha_entregado;
	}
        
        function getEmpresa() {
            return $this->empresa;
        }

        function setEmpresa($empresa) {
            $this->empresa = $empresa;
        }
        
        function getTipo() {
            return $this->tipo;
        }

        function setTipo($tipo) {
            $this->tipo = $tipo;
        }

	public function save(){
		$query="INSERT INTO $this->table(id,modulo,documento,tipo,descripcion,correo,estado,fecha_envio,empresa,fecha_entregado)
			VALUES(NULL,
			'".$this->modulo."',
			'".$this->documento."',
                        '".$this->tipo."',
			'".$this->descripcion."',
			'".$this->correo."',
			'".$this->estado."',
			'".$this->fecha_envio."',
                        '".$this->empresa."',
			'".$this->fecha_entregado."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}