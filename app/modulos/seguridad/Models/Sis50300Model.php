<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-06-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis50300Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50300':'sis50300';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function getNumMax($usuario) {
        $query="SELECT MAX(SUBSTRING_INDEX(id_producto,'.',-1))+1 as total from $this->table where SUBSTRING_INDEX(id_producto,'.',1) = '$usuario'";
        $result=$this->ejecutarSql($query);
        return $result;
    }

  

    public function countStockCarritoTemporal($codigo,$bodega) {
        $query="SELECT IFNULL(SUM(cantidad_producto),0)  AS 'CANTIDAD' FROM $this->table WHERE id_producto ='$codigo' AND bodega_producto='$bodega'";
        return $this->ejecutarConsulta($query);
    }

    

}