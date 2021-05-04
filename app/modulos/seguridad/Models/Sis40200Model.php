<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40200Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
            $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40200':'sis40200';
            parent::__construct($this->table,$adapter);
    }

    public function getComboBox(){
        $query="SELECT codigo as id,canton as nombre FROM $this->table order by canton asc";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id){
        $query="SELECT codigo as id,canton as nombre FROM $this->table where provinciaid = $id order by canton asc";
        $result=$this->ejecutarSql($query);
        return $result;
    }

}