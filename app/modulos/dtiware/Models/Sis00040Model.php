<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-22
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00040Model extends \ModeloBase {

    public function __construct($adapter) {
        $table='sis00040';
        parent::__construct($table,$adapter);
    }

    public function getComboBox(){
        $query="SELECT id,rol as nombre FROM sis00040 ";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function getTablePaginacion($buscar,$offset,$per_page){
        $query="SELECT z.* FROM sis00040 z where (z.rol like '%$buscar%') LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTransacciones($id) {
        $query="SELECT count(*) as numrows from sis20010 where rolid = $id ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getCountResul(){
        $query="SELECT count(*) as numrows from $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getModal($q){
        $query="SELECT a.id,a.rol FROM $this->table a";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}