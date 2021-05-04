<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class MetodopagoModel extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc40020':'cc40020';
        parent::__construct($this->table,$adapter);
    }


    public function ListarMetodo(){
       
        $query="SELECT `id`, `tipo`, `formapago`, `activa`
        FROM $this->table ";
        return $this->ejecutarConsulta($query);
    }

    public function limpiarCadena($cadena)
    {
        return $this->limpiarCadenaString($cadena);
    }
}