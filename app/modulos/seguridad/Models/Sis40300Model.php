<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-18
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40300Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40300':'sis40300';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox(){
        $query="SELECT anio as id,anio as nombre FROM $this->table ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id){
        $query="SELECT anio as id,anio as nombre FROM $this->table where provinciaid = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getObtenerAnio(){
        $query="select anio from $this->table where historico = 0 order by anio desc limit 1;";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
}