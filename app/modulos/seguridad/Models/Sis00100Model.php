<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00100Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTablePaginacion($buscar,$offset,$per_page){
        $query="SELECT z.* FROM $this->table z where (z.ruc like '%$buscar%' or z.razonsocial like '%$buscar%' or z.nomempresa like '%$buscar%') LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getWebsite($bd){
        $query="SELECT z.* FROM ".$bd.".sis00100 z WHERE z.bd = '$bd' ";
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }

}