<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-18
-- Version:	1.0.{numero de veces que se edita}
****************************************************************/

class Sis00010 extends \EntidadBase {
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
    * @var int
    */
    private $activo;

    public function __construct($adapter) {
            $table='sis00010';
            parent::__construct($table,$adapter);
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

    function setActivo($activo) {
            $this->activo = $activo;
    }

    function getActivo() {
            return $this->activo;
    }

    public function save()
    {
        $query="INSERT INTO sis00010(id,rol,activo)
                        VALUES(NULL,
                        '".$this->rol."',
                        '".$this->activo."'
                );";
        $save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
        return $save;
    }
}