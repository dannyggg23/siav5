<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-03-27
-- Version:	1.0.{numero de veces que se edita}
****************************************************************/

class Cc00000 extends \EntidadBase
{
    private $id;
    private $ruc;
    private $cliente;
    private $razonsocial;
    private $vendedor;
    private $nivelprecio;
    private $direccion;
    private $telefono;
    private $ciudad;
    private $provincia;
    private $categoria;
    private $pais;
    private $cupo;
    private $correo;
    private $idcorto;
    private $contacto;
    private $condicionpago;
    private $descuento = 0;
    private $descuento_outlet = 0;
    private $bajomonto = 0;
    private $suspendido = 0;
    private $inactivo = 0;
    private $fechaCreacion;
    private $relacionado = 0;
    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        parent::__construct($this->table,$adapter);
    }

    function getId() {
        return $this->id;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function getRuc() {
        return $this->ruc;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getRazonsocial() {
        return $this->razonsocial;
    }

    function getVendedor() {
        return $this->vendedor;
    }

    function getNivelprecio() {
        return $this->nivelprecio;
    }

    function getDireccion() {
        return $this->direccion;
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

    function getPais() {
        return $this->pais;
    }

    function getCupo() {
        return $this->cupo;
    }

    function getCorreo() {
        return $this->correo;
    }

    function getIdcorto() {
        return $this->idcorto;
    }

    function getContacto() {
        return $this->contacto;
    }

    function getCondicionpago() {
        return $this->condicionpago;
    }

    function getDescuento() {
        return $this->descuento;
    }

    function getDescuento_outlet() {
        return $this->descuento_outlet;
    }

    function getBajomonto() {
        return $this->bajomonto;
    }

    function getSuspendido() {
        return $this->suspendido;
    }

    function getInactivo() {
        return $this->inactivo;
    }

    function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    function getRelacionado() {
        return $this->relacionado;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setRuc($ruc) {
        $this->ruc = $ruc;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setRazonsocial($razonsocial) {
        $this->razonsocial = $razonsocial;
    }

    function setVendedor($vendedor) {
        $this->vendedor = $vendedor;
    }

    function setNivelprecio($nivelprecio) {
        $this->nivelprecio = $nivelprecio;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
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

    function setPais($pais) {
        $this->pais = $pais;
    }

    function setCupo($cupo) {
        $this->cupo = $cupo;
    }

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setIdcorto($idcorto) {
        $this->idcorto = $idcorto;
    }

    function setContacto($contacto) {
        $this->contacto = $contacto;
    }

    function setCondicionpago($condicionpago) {
        $this->condicionpago = $condicionpago;
    }

    function setDescuento($descuento) {
        $this->descuento = $descuento;
    }

    function setDescuento_outlet($descuento_outlet) {
        $this->descuento_outlet = $descuento_outlet;
    }

    function setBajomonto($bajomonto) {
        $this->bajomonto = $bajomonto;
    }

    function setSuspendido($suspendido) {
        $this->suspendido = $suspendido;
    }

    function setInactivo($inactivo) {
        $this->inactivo = $inactivo;
    }

    function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    function setRelacionado($relacionado) {
        $this->relacionado = $relacionado;
    }

    public function save(){
        $query="INSERT INTO $this->table (`id`, `ruc`, `cliente`, `razonsocial`, `vendedor`, `nivelprecio`, `direccion`, `telefono`, `ciudad`, `provincia`, `pais`, `cupo`, `correo`, `idcorto`, `contacto`, `condicionpago`, `descuento`, `descuento_outlet`, `bajomonto`, `suspendido`, `inactivo`, `fechaCreacion`,categoria, `relacionado`)
                        VALUES(NULL,
                        '".$this->ruc."',
                        '".$this->cliente."',
                        '".$this->razonsocial."',
                        '".$this->vendedor."',
                        '".$this->nivelprecio."',
                        '".$this->direccion."',
                        '".$this->telefono."',
                        '".$this->ciudad."',
                        '".$this->provincia."',
                        '".$this->pais."',
                        '".$this->cupo."',
                        '".$this->correo."',
                        '".$this->idcorto."',
                        '".$this->contacto."',
                        '".$this->condicionpago."',
                        '".$this->descuento."',
                        '".$this->descuento_outlet."',
                        '".$this->bajomonto."',
                        '".$this->suspendido."',
                        '".$this->inactivo."',
                        '".$this->fechaCreacion."',
                        '".$this->categoria."',
                        '".$this->relacionado."'
                );";
                $save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
                return $save;
        }
}