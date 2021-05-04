<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00200 extends \EntidadBase {
    /**
    * @var int
    * Class Unique ID
    */
    private $id;

    /**
    * @var string
    */
    private $rol;

    /**
    * @var string
    */
    private $descripcion;

    /**
    * @var int
    */
    private $activo;

    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00200':'sis00200';
        parent::__construct($this->table,$adapter);
    }

    function setId($id) {
        $this->id = $id;
    }

    function getId() {
        return $this->id;
    }

    function setRol($rol) {
        $this->rol = $rol;
    }

    function getRol() {
        return $this->rol;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function getActivo() {
        return $this->activo;
    }

    public function save(){
        $query="INSERT INTO $this->table(id,rol,descripcion,activo)
                VALUES(NULL,
                '".$this->rol."',
                '".$this->descripcion."',
                '".$this->activo."'
        );";
        $save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
        return $save;
    }
}