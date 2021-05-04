<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2019-11-23
-- Version:	2.0.{numero de veces que se edita}
****************************************************************/

class Inv10100 extends \EntidadBase {

    private $id;
    private $inv10000id;
    private $inv00000codigo;
    private $linea;
    private $cantidad;
    private $bodega;
    private $bodega_destino;
    private $descripcion;
    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv10100':'inv10100';
        parent::__construct($this->table,$adapter);
    }
    
    function getId() {
        return $this->id;
    }

    function getInv10000id() {
        return $this->inv10000id;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getInv00000codigo() {
        return $this->inv00000codigo;
    }

    function getLinea() {
        return $this->linea;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getBodega() {
        return $this->bodega;
    }

    function getBodega_destino() {
        return $this->bodega_destino;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setInv10000id($inv10000id) {
        $this->inv10000id = $inv10000id;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setInv00000codigo($inv00000codigo) {
        $this->inv00000codigo = $inv00000codigo;
    }

    function setLinea($linea) {
        $this->linea = $linea;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setBodega($bodega) {
        $this->bodega = $bodega;
    }

    function setBodega_destino($bodega_destino) {
        $this->bodega_destino = $bodega_destino;
    }

    public function save(){
            $query="INSERT INTO $this->table(inv10000id,inv00000codigo,linea,cantidad,bodega,descripcion,bodega_destino)
                    VALUES(
                    '".$this->inv10000id."',
                    '".$this->inv00000codigo."',
                    '".$this->linea."',
                    '".$this->cantidad."',
                    '".$this->bodega."',
                    '".$this->descripcion."',
                    '".$this->bodega_destino."'
            );";
           
        $this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion')));
        
        return $this->db()->insert_id;
    }
}