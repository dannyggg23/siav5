<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis60000Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
            $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis60000':'sis60000';
            parent::__construct($table,$adapter);
    }

    public function getComboBox($padre = '') {
            $query="";
            $result=$this->ejecutarSql($query);
            return $result;
    }
    
    public function getPermisosMenu($usuario) {
            $query="";
            $result=$this->ejecutarSql($query);
            return $result;
    }

}