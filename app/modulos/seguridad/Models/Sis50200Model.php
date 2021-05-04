<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-06-20
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis50200Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50200':'sis50200';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function getProformas() {
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $query="SELECT a.id,a.fecha_cc_tem,a.id_usuario,b.cliente,a.total_cc_tem,a.monto_abonado FROM $this->table a INNER JOIN $this->cc00000 b ON a.id_cliente = b.id;";
        $result=$this->ejecutarSql($query);
        return $result;
    }

}