<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40190Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
            $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40190':'sis40190';
            parent::__construct($this->table,$adapter);
    }

    public function getComboBox(){
        $query="SELECT codigo as id,provincia as nombre FROM $this->table order by provincia asc";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id){
        $query="SELECT codigo as id,provincia as nombre FROM $this->table where id = $id order by provincia asc";
        $result=$this->ejecutarSql($query);
        return $result;
    }

}