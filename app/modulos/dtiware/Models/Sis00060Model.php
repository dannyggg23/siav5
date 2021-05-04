<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00060Model extends \ModeloBase {

    private $table;
    
    public function __construct($adapter) {
        $this->table='sis00060';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox(){
        $query="SELECT id,nombre FROM $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id){
        $query="SELECT id,nombre FROM $this->table where id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getPermisos($id,$q,$offset,$per_page){
        $query="SELECT d.id,d.nombre,d.descripcion,d.activo,d.icono,(select count(z.modulesid) from sis20010 z where z.modulesid = d.id and z.rolid = $id) as temporal FROM sis00060 d WHERE d.nombre like '%$q%' LIMIT $offset,$per_page; ";
        $inv_articulo=$this->ejecutarSql($query);
        return $inv_articulo;
    }

}