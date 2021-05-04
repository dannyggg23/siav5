<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40170Model extends \ModeloBase {

    private $table;

    public function __construct($adapter,$bd='') {
        if (strlen($bd)>0) {
            $this->table=$bd.'.sis40170';
        }
        else {
            $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40170':'sis40170';
        }
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox(){
        $query="SELECT codigo as id,documento as nombre FROM $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id){
        $query="SELECT codigo as id,documento as nombre FROM $this->table where id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }

}