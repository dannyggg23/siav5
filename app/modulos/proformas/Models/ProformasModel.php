<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class ProformasModel extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
        
        parent::__construct($this->table,$adapter);
    }

    public function Listar($nivelprecio){
        $this->inv00020=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00020':'inv00020';
        $query="SELECT inv00000.id, inv00000.codigo,inv00000.costo, inv00000.descripcion, inv00000.descripcioncorta, inv00000.descripciongenerica, 
        inv00000.tipo, inv00000.descontinuado, inv00000.fechamodificada, inv00000.fechacreacion, inv00000.linea, inv00000.sublinea, 
        inv00000.marcavehiculo, inv00000.modelo, inv00000.marcaproducto, inv00000.codoriginal1, inv00000.codoriginal2, inv00000.codoriginal3, 
        inv00000.codanterior, inv00000.inactivo,inv00020.precio 
        FROM $this->table
        INNER JOIN $this->inv00020 ON inv00020.codigo=inv00000.codigo
        WHERE inv00020.nivelprecio='$nivelprecio'  AND inv00000.inactivo=0
        LIMIT 40
	";
        return $this->ejecutarConsulta($query);
    }

    public function BuscarCodigo($nivelprecio,$codigo){
        $this->inv00020=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00020':'inv00020';
        $query="SELECT inv00000.id, inv00000.codigo,inv00000.costo, inv00000.descripcion, inv00000.descripcioncorta, inv00000.descripciongenerica, 
        inv00000.tipo, inv00000.descontinuado, inv00000.fechamodificada, inv00000.fechacreacion, inv00000.linea, inv00000.sublinea, 
        inv00000.marcavehiculo, inv00000.modelo, inv00000.marcaproducto, inv00000.codoriginal1, inv00000.codoriginal2, inv00000.codoriginal3, 
        inv00000.codanterior, inv00000.inactivo,inv00020.precio 
        FROM $this->table
        INNER JOIN $this->inv00020 ON inv00020.codigo=inv00000.codigo
        WHERE inv00020.nivelprecio='$nivelprecio' AND inv00000.codigo='$codigo' AND inv00000.inactivo=0";
        return $this->ejecutarConsulta($query);
    }

    public function ListarBusqueda($nivelprecio,$busqueda){
        $this->inv00020=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00020':'inv00020';
        $query="SELECT inv00000.id, inv00000.codigo,inv00000.costo ,inv00000.descripcion, inv00000.descripcioncorta, inv00000.descripciongenerica, 
        inv00000.tipo, inv00000.descontinuado, inv00000.fechamodificada, inv00000.fechacreacion, inv00000.linea, inv00000.sublinea, 
        inv00000.marcavehiculo, inv00000.modelo, inv00000.marcaproducto, inv00000.codoriginal1, inv00000.codoriginal2, inv00000.codoriginal3, 
        inv00000.codanterior, inv00000.inactivo,inv00020.precio 
        FROM $this->table
        INNER JOIN $this->inv00020 ON inv00020.codigo=inv00000.codigo
        WHERE inv00020.nivelprecio='$nivelprecio' AND inv00000.inactivo=0
        AND (inv00000.codigo LIKE '%$busqueda%' OR inv00000.descripcion LIKE '%$busqueda%' OR inv00000.codoriginal1 LIKE '%$busqueda%' OR inv00000.codanterior LIKE '%$busqueda%') LIMIT 40 ";
       
        return $this->ejecutarConsulta($query);
    }

    public function TieneDescuento($codigo){

        $this->precio_desc=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.precio_desc':'precio_desc';
        $query="SELECT * FROM $this->precio_desc where codigo ='$codigo' ";
       
        return $this->ejecutarConsulta($query);
    }

    
    
    public function ListarBusquedaTransferencia($busqueda){
        $this->inv00020=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00020':'inv00020';
        $query="SELECT inv00000.id, inv00000.codigo,inv00000.costo ,inv00000.descripcion, inv00000.descripcioncorta, inv00000.descripciongenerica, 
        inv00000.tipo, inv00000.descontinuado, inv00000.fechamodificada, inv00000.fechacreacion, inv00000.linea, inv00000.sublinea, 
        inv00000.marcavehiculo, inv00000.modelo, inv00000.marcaproducto, inv00000.codoriginal1, inv00000.codoriginal2, inv00000.codoriginal3, 
        inv00000.codanterior, inv00000.inactiv
        FROM $this->table
        WHERE  inv00000.inactivo=0 AND (inv00000.codigo LIKE '%$busqueda%' OR inv00000.descripcion LIKE '%$busqueda%' OR inv00000.codoriginal1 LIKE '%$busqueda%' OR inv00000.codanterior LIKE '%$busqueda%') LIMIT 40";
        return $this->ejecutarConsulta($query);
    }

    
    public function ListarBusquedaPrecioProducto($nivelprecio,$codigo){
        $this->inv00020=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00020':'inv00020';
        $query="SELECT inv00020.precio 
        FROM $this->table
        INNER JOIN $this->inv00020 ON inv00020.codigo=inv00000.codigo
        WHERE inv00020.nivelprecio='$nivelprecio'
        AND inv00000.codigo='$codigo' ";
        return $this->ejecutarConsulta($query);
    }

  
    public function limpiarCadena($cadena)
    {
        return $this->limpiarCadenaString($cadena);
    }
}
