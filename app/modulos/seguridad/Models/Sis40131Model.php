<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-06-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40131Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40131':'sis40131';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox(){
        $query="SELECT id,tipo as nombre FROM $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id){
        $query="SELECT id,tipo as nombre FROM $this->table where id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }

}