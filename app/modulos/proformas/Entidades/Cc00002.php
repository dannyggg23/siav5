<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-03-27
-- Version:	1.0.{numero de veces que se edita}
****************************************************************/

class Cc00002 extends \EntidadBase
{
    private $id;
    private $ruc;
    private $codigodireccion;
    private $telefono;
    private $ciudad;
    private $provincia;
    private $direccion;
    private $estado;
    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';
        parent::__construct($this->table,$adapter);
    }

    function getId() {
        return $this->id;
    }

    function getRuc() {
        return $this->ruc;
    }

    function getCodigodireccion() {
        return $this->codigodireccion;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getCiudad() {
        return $this->ciudad;
    }

    function getProvincia() {
        return $this->provincia;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getEstado() {
        return $this->estado;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setRuc($ruc) {
        $this->ruc = $ruc;
    }

    function setCodigodireccion($codigodireccion) {
        $this->codigodireccion = $codigodireccion;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    function setProvincia($provincia) {
        $this->provincia = $provincia;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    public function save(){
        $query="INSERT INTO $this->table (`id`, `ruc`, `codigodireccion`, `telefono`, `ciudad`, `provincia`, `direccion`, `estado`)
                        VALUES(NULL,
                        '".$this->ruc."',
                        '".$this->codigodireccion."',
                        '".$this->telefono."',
                        '".$this->ciudad."',
                        '".$this->provincia."',
                        '".$this->direccion."',
                        '".$this->estado."'
                );";
                $save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
                return $save;
        }
}