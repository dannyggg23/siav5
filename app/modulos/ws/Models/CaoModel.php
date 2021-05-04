<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class CaoModel extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table='';
        parent::__construct($this->table,$adapter);
    }

    public function getProductos(){
        $sql='SELECT RTRIM(LTRIM(ITEMNMBR)) AS ITEMNMBR ,RTRIM(LTRIM(ITEMDESC)) AS ITEMDESC ,RTRIM(LTRIM(CURRCOST)) AS CURRCOST,RTRIM(LTRIM(UOMSCHDL)) AS UOMSCHDL FROM IV00101 WHERE ITEMTYPE <> 5';
        return $this->ejecutarConsultaSQL($sql);
    }

    public function getBodegas(){
        $sql='SELECT  RTRIM(LTRIM(LOCNCODE)) AS LOCNCODE,RTRIM(LTRIM(LOCNDSCR)) AS LOCNDSCR,RTRIM(LTRIM(ADDRESS1)) AS ADDRESS1 FROM IV40700';
        return $this->ejecutarConsultaSQL($sql);
    }

}

?>