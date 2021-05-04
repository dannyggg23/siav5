<?php

namespace Models;

/****************************************************************
-- Tabla:	Tabla Usuario Empresa
-- Author:	Gabriel Reyes
-- Fecha:	2018-04-25
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20200Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis20200':'sis20200';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getEmpresas($usuarioid){
        $sis00300 = isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].".sis00300":"sis00300";
        $sis00100 = isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].".sis00100":"sis00100";
        $query="SELECT d.* FROM $this->table a INNER JOIN ".$sis00300." c ON c.id = a.sis00300id INNER JOIN ".$sis00100." d ON a.sis00100id = d.id wHERE c.usuario = '$usuarioid' ";
        $inv_articulo=$this->ejecutarSql($query);
        return $inv_articulo;
    }
    
    public function getEmpresa($usuarioid,$empresaid){
        $query="SELECT d.* FROM $this->table a INNER JOIN ".$sis00300." c ON a.sis00300id = c.id INNER JOIN ".$sis00100." d ON a.sis00100id = d.id wHERE c.usuario = '$usuarioid' and d.id = '$empresaid' ";
        $inv_articulo=$this->ejecutarSql($query);
        return $inv_articulo;
    }

}