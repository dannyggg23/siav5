<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-13
-- Version:	1.0.{numero de veces que se edita}
****************************************************************/

class Sis20000Model extends \ModeloBase {
    public function __construct($adapter) {
        $table='sis20000';
        parent::__construct($table,$adapter);
    }
    
    public function getPermisos($usuario){
        $query="SELECT d.id,d.nombre,d.descripcion,d.accion,d.icono FROM sis00020 a INNER JOIN sis00010 b ON b.id = a.rolid INNER JOIN sis20000 c ON b.id = c.rolid INNER JOIN sis00030 d ON d.id = c.modulesid WHERE a.usuario = '".$usuario."' order by id";
        $inv_articulo=$this->ejecutarSql($query);
        return $inv_articulo;
    }
}