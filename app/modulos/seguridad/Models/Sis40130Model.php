<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40130Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40130':'sis40130';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function getTablePaginacionInventario($buscar,$offset,$per_page)
    {
        $query="SELECT id,titulo,tipo,placeholder FROM $this->table WHERE titulo like '%$buscar%' AND idform = 'frmDAinventario' LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}