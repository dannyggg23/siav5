<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-06-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00203Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00203':'sis00203';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getCountResul(){
        $query="SELECT count(*) as numrows from $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getModal(){
        $query="SELECT a.id,a.accion,a.descripcion FROM $this->table a";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTransacciones($id) {
        $this->sis41003=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis41003':'sis41003';
        $query="SELECT count(*) as numrows from $this->sis41003 WHERE sis00203id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}