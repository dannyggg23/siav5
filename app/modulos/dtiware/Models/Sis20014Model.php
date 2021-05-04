<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-04
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20014Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table='sis20014';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getMontoIngresado($empresa) {
        $query="select sum(valor) as monto FROM $this->table WHERE ruc = '$empresa'";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTablePaginacion($cliente,$empresa,$buscar,$offset,$per_page)
    {
        $this->sis40040='sis40040';
        $query="SELECT b.pago,b.descripcion,z.fecha,z.valor FROM $this->table z INNER JOIN $this->sis40040 b ON z.pagoid = b.id where (b.pago like '%$buscar%' OR b.descripcion like '%$buscar%') and clienteid = '$cliente' and ruc = '$empresa' LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}