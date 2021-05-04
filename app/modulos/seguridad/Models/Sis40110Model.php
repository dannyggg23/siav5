<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40110Model extends \ModeloBase {

    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40110':'sis40110';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox(){
        $query="SELECT id,lenguaje as nombre FROM $this->table ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id){
        $query="SELECT id,lenguaje as nombre FROM $this->table where id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }

}