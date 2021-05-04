<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-13
-- Version:	1.0.{numero de veces que se edita}
****************************************************************/

class Sis40010Model extends \ModeloBase {
    public function __construct($adapter) {
        $table='sis40010';
        parent::__construct($table,$adapter);
    }
    
    public function getComboBox(){
        $query="SELECT id,lenguaje as nombre FROM sis40010 ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id){
        $query="SELECT id,lenguaje as nombre FROM sis40010 where id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}