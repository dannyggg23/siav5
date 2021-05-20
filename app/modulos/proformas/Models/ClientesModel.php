<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class ClientesModel extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        parent::__construct($this->table,$adapter);
    }


    public function ListarClientes(){
       
        $query="SELECT `id`, `ruc`, `cliente`, `razonsocial`,categoria, `vendedor`, `nivelprecio`, `direccion`, `telefono`, `ciudad`, `provincia`, `pais`, `cupo`, `correo`, `idcorto`, `contacto`, `condicionpago`, `descuento`, `descuento_outlet`, `bajomonto`, `suspendido`, `inactivo`, `fechaCreacion`, `relacionado` 
        FROM $this->table WHERE condicionpago = 'CONTADO'
        ORDER BY id DESC  LIMIT 10";
        return $this->ejecutarConsulta($query);
    }

    public function ListarClientesMM(){
       
        $query="SELECT `id`, `ruc`, `cliente`, `razonsocial`,categoria, `vendedor`, `nivelprecio`, `direccion`, `telefono`, `ciudad`, `provincia`, `pais`, `cupo`, `correo`, `idcorto`, `contacto`, `condicionpago`, `descuento`, `descuento_outlet`, `bajomonto`, `suspendido`, `inactivo`, `fechaCreacion`, `relacionado` 
        FROM $this->table WHERE ruc = '1792151473001'
        ORDER BY id DESC ";
        return $this->ejecutarConsulta($query);
    }


    public function ListarClientesAll(){
       
        $query="SELECT `id`, `ruc`, `cliente`, `razonsocial`, categoria, `vendedor`, `nivelprecio`, `direccion`, `telefono`, `ciudad`, `provincia`, `pais`, `cupo`, `correo`, `idcorto`, `contacto`, `condicionpago`, `descuento`, `descuento_outlet`, `bajomonto`, `suspendido`, `inactivo`, `fechaCreacion`, `relacionado` 
        FROM $this->table WHERE condicionpago = 'CONTADO'
        ORDER BY id DESC  ";
        return $this->ejecutarConsulta($query);
    }

    public function ListarClientesBusqueda($busqueda){
       
        $query="SELECT `id`, `ruc`, `cliente`, `razonsocial`, categoria, `vendedor`, `nivelprecio`, `direccion`, `telefono`, `ciudad`, `provincia`, `pais`, `cupo`, `correo`, `idcorto`, `contacto`, `condicionpago`, `descuento`, `descuento_outlet`, `bajomonto`, `suspendido`, `inactivo`, `fechaCreacion`, `relacionado` 
        FROM $this->table 
        WHERE (ruc LIKE '%$busqueda%' OR cliente LIKE '%$busqueda%' OR razonsocial LIKE '%$busqueda%' )
        ORDER BY id DESC  LIMIT 20";
        //AND condicionpago = 'CONTADO'
        return $this->ejecutarConsulta($query);
    }

    public function ListarClientesId($id){
       
        $query="SELECT `id`, `ruc`, `cliente`, `razonsocial`, categoria, `vendedor`, `nivelprecio`, `direccion`, `telefono`, `ciudad`, `provincia`, `pais`, `cupo`, `correo`, `idcorto`, `contacto`, `condicionpago`, `descuento`, `descuento_outlet`, `bajomonto`, `suspendido`, `inactivo`, `fechaCreacion`, `relacionado` 
        FROM $this->table WHERE id='$id'";
        return $this->ejecutarConsulta($query);
    }

    public function clienteAprobadoCobranza($ruc){
        $this->cc00003=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00003':'cc00003';
        $query="SELECT ruc from $this->cc00003 where ruc ='$ruc';";
        return $this->ejecutarConsulta($query);
    }

    public function limpiarCadena($cadena)
    {
        return $this->limpiarCadenaString($cadena);
    }
}