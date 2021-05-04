<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-04
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00059Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table='sis00059';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function getNextPrefijo() {
        $query="select IFNULL(max(prefijo)+1,0) as prefijo FROM $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}