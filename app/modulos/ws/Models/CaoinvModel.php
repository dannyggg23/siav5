<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class CModel extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table='';
        parent::__construct($this->table,$adapter);
    }

    public function getProductos(){
        $sql='SELECT RTRIM(LTRIM(ITEMNMBR)),RTRIM(LTRIM(ITEMDESC)),RTRIM(LTRIM(CURRCOST)),RTRIM(LTRIM(UOMSCHDL)) FROM IV00101 WHERE ITEMTYPE <> 5';
        return $this->ejecutarConsultaSQL($sql);
    }

    public function getBodegas(){
        $sql='SELECT  RTRIM(LTRIM(LOCNCODE)),RTRIM(LTRIM(LOCNDSCR)),RTRIM(LTRIM(ADDRESS1)) FROM IV40700';
        return $this->ejecutarConsultaSQL($sql);
    }


}

?>