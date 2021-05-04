<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis50300 extends \EntidadBase {
	
        private $id;
        private $id_cabecera;
        private $id_producto;
        private $descripcion_producto;
        private $costo_producto;
        private $stock_producto;
        private $bodega_producto;
        private $cantidad_producto;
        private $precio_producto;
        private $descuento_producto;
        private $subtotal_producto;
	private $table;

	public function __construct($adapter,$bd='') {
            if (strlen($bd)>0) {
                $this->table=$bd.'.sis50300';
            }
            else {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50300':'sis50300';
            }
            parent::__construct($this->table,$adapter);
	}

        function getId() {
            return $this->id;
        }

        function getId_cabecera() {
            return $this->id_cabecera;
        }

        function getId_producto() {
            return $this->id_producto;
        }

        function getDescripcion_producto() {
            return $this->descripcion_producto;
        }

        function getCosto_producto() {
            return $this->costo_producto;
        }

        function getStock_producto() {
            return $this->stock_producto;
        }

        function getBodega_producto() {
            return $this->bodega_producto;
        }

        function getCantidad_producto() {
            return $this->cantidad_producto;
        }

        function getPrecio_producto() {
            return $this->precio_producto;
        }

        function getDescuento_producto() {
            return $this->descuento_producto;
        }

        function getSubtotal_producto() {
            return $this->subtotal_producto;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setId_cabecera($id_cabecera) {
            $this->id_cabecera = $id_cabecera;
        }

        function setId_producto($id_producto) {
            $this->id_producto = $id_producto;
        }

        function setDescripcion_producto($descripcion_producto) {
            $this->descripcion_producto = $descripcion_producto;
        }

        function setCosto_producto($costo_producto) {
            $this->costo_producto = $costo_producto;
        }

        function setStock_producto($stock_producto) {
            $this->stock_producto = $stock_producto;
        }

        function setBodega_producto($bodega_producto) {
            $this->bodega_producto = strtoupper($bodega_producto);
        }

        function setCantidad_producto($cantidad_producto) {
            $this->cantidad_producto = $cantidad_producto;
        }

        function setPrecio_producto($precio_producto) {
            $this->precio_producto = $precio_producto;
        }

        function setDescuento_producto($descuento_producto) {
            $this->descuento_producto = $descuento_producto;
        }

        function setSubtotal_producto($subtotal_producto) {
            $this->subtotal_producto = $subtotal_producto;
        }
        
	public function save(){
		$query="INSERT INTO $this->table(id,documento,fcreacion,activo)
			VALUES(NULL,
			'".$this->documento."',
			'".$this->fcreacion."',
			'".$this->activo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}