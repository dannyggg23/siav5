<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00020 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $apellido;

	/**
	* @var string
	*/
	private $correo;

	/**
	* @var string
	*/
	private $usuario;

	/**
	* @var string
	*/
	private $pass;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var string
	*/
	private $keyreg;

	/**
	* @var string
	*/
	private $keypass;

	/**
	* @var string
	*/
	private $newpass;

	/**
	* @var int
	*/
	private $rolid;

	public function __construct($adapter) {
		$table='sis00020';
		parent::__construct($table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	function getNombre() {
		return $this->nombre;
	}

	function setApellido($apellido) {
		$this->apellido = $apellido;
	}

	function getApellido() {
		return $this->apellido;
	}

	function setCorreo($correo) {
		$this->correo = $correo;
	}

	function getCorreo() {
		return $this->correo;
	}

	function setUsuario($usuario) {
		$this->usuario = $usuario;
	}

	function getUsuario() {
		return $this->usuario;
	}

	function setPass($pass) {
		$this->pass = $pass;
	}

	function getPass() {
		return $this->pass;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	function setKeyreg($keyreg) {
		$this->keyreg = $keyreg;
	}

	function getKeyreg() {
		return $this->keyreg;
	}

	function setKeypass($keypass) {
		$this->keypass = $keypass;
	}

	function getKeypass() {
		return $this->keypass;
	}

	function setNewpass($newpass) {
		$this->newpass = $newpass;
	}

	function getNewpass() {
		return $this->newpass;
	}

	function setRolid($rolid) {
		$this->rolid = $rolid;
	}

	function getRolid() {
		return $this->rolid;
	}

	public function save(){
            $query="INSERT INTO sis00020(id,nombre,apellido,correo,usuario,pass,activo,keyreg,keypass,newpass,rolid)
                        VALUES(NULL,
                        '".$this->nombre."',
                        '".$this->apellido."',
                        '".$this->correo."',
                        '".$this->usuario."',
                        '".$this->pass."',
                        '".$this->activo."',
                        '".$this->keyreg."',
                        '".$this->keypass."',
                        '".$this->newpass."',
                        '".$this->rolid."'
                );";
                $save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
                return $save;
        }
}