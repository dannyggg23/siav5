<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class SucursalesModel extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';
        parent::__construct($this->table,$adapter);
    }


    public function ListarSucursales($ruc){
       
        $query="SELECT `id`, `ruc`, `codigodireccion`, `telefono`, `ciudad`, `provincia`, `direccion`, `estado` 
        FROM $this->table 
        WHERE ruc='$ruc'";
        return $this->ejecutarConsulta($query);
    }

    public function ListarSucursalesId($id){
       
        $query="SELECT `id`, `ruc`, `codigodireccion`, `telefono`, `ciudad`, `provincia`, `direccion`, `estado` 
        FROM $this->table 
        WHERE id='$id'";
        return $this->ejecutarConsulta($query);
    }



    public function limpiarCadena($cadena)
    {
        return $this->limpiarCadenaString($cadena);
    }
}