<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Cc20010Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc20010':'cc20010';
        
        parent::__construct($this->table,$adapter);
    }

    public function listarMontos($documento){
        $this->cc40020=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc40020':'cc40020';
        $sql="SELECT cc20010.`id`,cc20010.`documento`, CAST(cc20010.`fecha` AS DATE) as fecha , cc20010.`valor`, cc20010.`cc40020id`, cc20010.`usuario`, cc40020.formapago FROM $this->table  
        INNER JOIN  $this->cc40020 on cc40020.id=cc20010.cc40020id
        WHERE cc20010.documento='$documento'";
        return $this->ejecutarConsulta($sql);
    }

    public function limpiarCadena($cadena)
    {
        return $this->limpiarCadenaString($cadena);
    }
}