<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-03-27
-- Version:	1.0.{numero de veces que se edita}
****************************************************************/

class Gui00000 extends \EntidadBase
{
    private $codigo;
    private $razonsocial;
    private $correo;
    private $direccion;
    private $telefono;
    private $celular;
    private $fcreacion;
    private $suspendido;
    private $usuario;
    private $empresa;
    private $sis40170id;
    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui00000':'gui00000';
        parent::__construct($this->table,$adapter);
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getRazonsocial() {
        return $this->razonsocial;
    }

    function getCorreo() {
        return $this->correo;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getCelular() {
        return $this->celular;
    }

    function getFcreacion() {
        return $this->fcreacion;
    }

    function getSuspendido() {
        return $this->suspendido;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getEmpresa() {
        return $this->empresa;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setRazonsocial($razonsocial) {
        $this->razonsocial = $razonsocial;
    }

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setCelular($celular) {
        $this->celular = $celular;
    }

    function setFcreacion($fcreacion) {
        $this->fcreacion = $fcreacion;
    }

    function setSuspendido($suspendido) {
        $this->suspendido = $suspendido;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }
    
    function getSis40170id() {
        return $this->sis40170id;
    }

    function setSis40170id($sis40170id) {
        $this->sis40170id = $sis40170id;
    }
    
    public function save(){
        $query="INSERT INTO $this->table (`codigo`, `razonsocial`,sis40170id, `correo`, `direccion`, `telefono`, `celular`, `fcreacion`, `suspendido`, `usuario`, `empresa`)
                        VALUES(
                        '".$this->codigo."',
                        '".$this->razonsocial."',
                        '".$this->sis40170id."',
                        '".$this->correo."',
                        '".$this->direccion."',
                        '".$this->telefono."',
                        '".$this->celular."',
                        '".$this->fcreacion."',
                        '".$this->suspendido."',
                        '".$this->usuario."',
                        '".$this->empresa."'
                );";
                $save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
                return $save;
        }
}