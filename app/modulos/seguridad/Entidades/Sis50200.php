<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-29
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis50200 extends \EntidadBase {
	
        private $id;
        private $id_usuario;
        private $id_cliente;
        private $id_sucursal;
        private $fecha_cc_tem;
        private $subtotal_cc_tem;
        private $iva_cc_tem;
        private $total_cc_tem;
        private $descuento_cc_te;
        private $descuento_porce_cc;
	private $table;

	public function __construct($adapter,$bd='') {
            if (strlen($bd)>0) {
                $this->table=$bd.'.sis50200';
            }
            else {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50200':'sis50200';
            }
            parent::__construct($this->table,$adapter);
	}
        
        function getId() {
            return $this->id;
        }

        function getDescuento_porce_cc() {
            return $this->descuento_porce_cc;
        }

    
        function getId_usuario() {
            return $this->id_usuario;
        }

        function getId_cliente() {
            return $this->id_cliente;
        }

        function getId_sucursal() {
            return $this->id_sucursal;
        }

        function getFecha_cc_tem() {
            return $this->fecha_cc_tem;
        }

        function getSubtotal_cc_tem() {
            return $this->subtotal_cc_tem;
        }

        function getIva_cc_tem() {
            return $this->iva_cc_tem;
        }

        function getTotal_cc_tem() {
            return $this->total_cc_tem;
        }

        function getDescuento_cc_te() {
            return $this->descuento_cc_te;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setDescuento_porce_cc($descuento_porce_cc) {
            $this->descuento_porce_cc = $descuento_porce_cc;
        }

        

        function setId_usuario($id_usuario) {
            $this->id_usuario = $id_usuario;
        }

        function setId_cliente($id_cliente) {
            $this->id_cliente = $id_cliente;
        }

        function setId_sucursal($id_sucursal) {
            $this->id_sucursal = $id_sucursal;
        }

        function setFecha_cc_tem($fecha_cc_tem) {
            $this->fecha_cc_tem = $fecha_cc_tem;
        }

        function setSubtotal_cc_tem($subtotal_cc_tem) {
            $this->subtotal_cc_tem = $subtotal_cc_tem;
        }

        function setIva_cc_tem($iva_cc_tem) {
            $this->iva_cc_tem = $iva_cc_tem;
        }

        function setTotal_cc_tem($total_cc_tem) {
            $this->total_cc_tem = $total_cc_tem;
        }

        function setDescuento_cc_te($descuento_cc_te) {
            $this->descuento_cc_te = $descuento_cc_te;
        }
        
	public function save(){
		$query="INSERT INTO $this->table(id,id_usuario,id_cliente,id_sucursal,fecha_cc_tem,subtotal_cc_tem,iva_cc_tem,descuento_cc_te,descuento_porce_cc)
			VALUES(NULL,
			'".$this->id_usuario."',
			'".$this->id_cliente."',
                        '".$this->id_sucursal."',
                        '".$this->fecha_cc_tem."',
                        '".$this->subtotal_cc_tem."',
                        '".$this->iva_cc_tem."',
                        '".$this->total_cc_tem."',
                        '".$this->descuento_cc_te."',
                        '".$this->descuento_porce_cc."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}