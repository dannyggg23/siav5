<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-10
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20015Model extends \ModeloBase
{

    private $table,$adapter;

    public function __construct($adapter) {
        $this->table='sis20015';
        $this->adapter = $adapter;
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getPlanesActivos()
    {
        $empresa = new \Entidades\Sis00100($this->adapter);
        $empresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $this->Sis00061='sis00061';
        $this->Sis20013='sis20013';
        $query="SELECT * FROM $this->table WHERE modulesid IN (SELECT case when id = 4 then 4 else sis00060id end as modulo FROM $this->Sis00061 WHERE id IN (SELECT planid FROM $this->Sis20013 WHERE ruc = '".$empresa['ruc']."' and activo = 1))";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getExistsPlan($modulo)
    {
        $empresa = new \Entidades\Sis00100($this->adapter);
        $empresa = $empresa->getMulti('id', $_SESSION['empresa']);

        $this->sis20012='sis20012';
        $query="SELECT count(*) as numrows FROM $this->sis20012 WHERE ruc = '".$empresa['ruc']."' and activo = 1 and modulesid = $modulo;";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
}