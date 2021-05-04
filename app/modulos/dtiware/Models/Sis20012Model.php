<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-04
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20012Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table='sis20012';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getInventarioInstall($ruc) {
        $query="select funcion from $this->table a INNER JOIN sis00060 b ON a.modulesid = b.id where a.activo = 1 and b.nombre like '%Articulos%' and a.ruc = '$ruc';";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}