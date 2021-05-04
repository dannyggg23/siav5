<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-18
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40310Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40310':'sis40310';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox() {
        $query="SET lc_time_names = 'es_EC';";
        $result=$this->ejecutarSql($query);
        $query="select distinct DATE_FORMAT(fecha,'%Y - %b') as nombre,fecha as id from $this->table;";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit(){
        $query="SET lc_time_names = 'es_EC';";
        $result=$this->ejecutarSql($query);
        $query="select distinct DATE_FORMAT(fecha,'%Y - %b') as nombre,fecha as id from $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getCountResul(){
        $query="SELECT count(*) as numrows from $this->table where empresa = ".$_SESSION['empresa']."";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getModal($q){
        $query="SELECT a.periodo,a.fecha,(SELECT z.activo FROM $this->table z WHERE z.modulo='VENTAS' AND z.periodo = a.periodo and z.sis40300id = a.sis40300id) as Ventas ,(SELECT z.activo FROM $this->table z WHERE z.modulo='COMPRAS' AND z.periodo = a.periodo and z.sis40300id = a.sis40300id) as Compras ,(SELECT z.activo FROM $this->table z WHERE z.modulo='INVENTARIO' AND z.periodo = a.periodo and z.sis40300id = a.sis40300id) as Inventario ,(SELECT z.activo FROM $this->table z WHERE z.modulo='FINANCIERO' AND z.periodo = a.periodo and z.sis40300id = a.sis40300id) as Financiero FROM $this->table a where sis40300id = '$q' group by periodo order by periodo";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}