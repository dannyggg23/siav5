<?php

namespace Models;



class Cc10010Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc10010':'cc10010';
        parent::__construct($this->table,$adapter);
    }

    public function countStockCarritoPedidos($codigo,$bodega) {
        $query="SELECT IFNULL(SUM(cantidad),0)  AS 'CANTIDAD' FROM $this->table WHERE inv00000codigo ='$codigo' AND bodega='$bodega'";
        return $this->ejecutarConsulta($query);
    }

    public function descuentoTotalPedidos($cc10000id){
        $sql="SELECT SUM(descuento)+SUM(descuento_cliente*cantidad) as 'descuento' from $this->table WHERE cc10000id='$cc10000id'";
        return $this->ejecutarConsulta($sql);
    }

    public function descuentoTotalPedidosAprobar($cc11000id){
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc11010':'cc11010';

        $sql="SELECT SUM(descuento)+SUM(descuento_cliente*cantidad) as 'descuento' from $this->table WHERE cc11000id='$cc11000id'";
        return $this->ejecutarConsulta($sql);
    }

    public function detallepedido($pedido){
   

        $sql="SELECT inv00000codigo,descripcion,marca_producto,cantidad,precio,descuento,subtotal from $this->table where cc10000id='$pedido';";
        return $this->ejecutarConsulta($sql);
    }


    
    
}
