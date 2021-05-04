<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00030Model extends \ModeloBase {

    public function __construct($adapter) {
            $table='sis00030';
            parent::__construct($table,$adapter);
    }

    public function getComboBox($padre = '') {
            $query="";
            $result=$this->ejecutarSql($query);
            return $result;
    }
    
    public function getPermisos($id,$q,$offset,$per_page){
        $query="SELECT d.id,d.nombre,d.descripcion,d.activo,d.icono,(select count(z.modulesid) from sis20000 z where z.modulesid = d.id and z.rolid = $id) as temporal FROM sis00030 d WHERE d.nombre like '%$q%' LIMIT $offset,$per_page; ";
        $inv_articulo=$this->ejecutarSql($query);
        return $inv_articulo;
    }

}