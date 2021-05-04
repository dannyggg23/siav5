<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2019-11-23
-- Version:	2.0.{numero de veces que se edita}
****************************************************************/

class Inv10000 extends \EntidadBase {
    
    private $id;
    private $pedido;
    private $lote;
    private $fecha;
    private $usuario;
    private $table;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv10000':'inv10000';
        parent::__construct($this->table,$adapter);
    }
    
    function getId() {
        return $this->id;
    }

    function getPedido() {
        return $this->pedido;
    }

    function getLote() {
        return $this->lote;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setPedido($pedido) {
        $this->pedido = $pedido;
    }

    function setLote($lote) {
        $this->lote = $lote;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function save(){
            $query="INSERT INTO $this->table(pedido,lote,fecha,usuario)
                    VALUES(
                    '".$this->pedido."',
                    '".$this->lote."',
                    '".$this->fecha."',
                    '".$this->usuario."'
            );";
        $this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion')));
        return $this->db()->insert_id;
    }
}