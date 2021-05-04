<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-09
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00061Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table='sis00061';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}