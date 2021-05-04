<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-06-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00202Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00202':'sis00202';
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
        $query="SELECT a.id,a.ventana,a.descripcion FROM $this->table a";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTransacciones($id) {
        $this->sis41002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis41002':'sis41002';
        $query="SELECT count(*) as numrows from $this->sis41002 WHERE sis00202id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}