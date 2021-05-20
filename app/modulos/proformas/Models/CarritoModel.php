<?php

namespace Models;



class CarritoModel extends \ModeloBase {
    
    private $table;
    private $tableDetalle;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50200':'sis50200';
        $this->tableDetalle=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50300':'sis50300';
        parent::__construct($this->table,$adapter);
    }


    public function ListarCabeceraCarrito( $idUsuario, $idCliente, $idSucursal){
       
        $query=" SELECT `id`, `id_usuario`, `id_cliente`, `id_sucursal`, `fecha_cc_tem`, `subtotal_cc_tem`, `iva_cc_tem`, `total_cc_tem`, `descuento_cc_tem` 
        FROM $this->table
        where $this->table.id_usuario='$idUsuario' AND $this->table.id_cliente='$idCliente' AND $this->table.id_sucursal='$idSucursal' order by id desc";
       return $this->ejecutarConsulta($query);

    }

 
    public function subtotalPedido($idCarrito){
       
        $query="SELECT sum(subtotal_producto) as subtotal from $this->tableDetalle where id_cabecera='$idCarrito';";
        return $this->ejecutarConsulta($query);

    }

    public function subtotalPedidoP($id){
        $this->cc10010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc10010':'cc10010';
        $query="SELECT sum(subtotal) subtotal from $this->cc10010 where cc10000id='$id';";
        return $this->ejecutarConsulta($query);

    }

    public function descuentoPedido($id,$subtotal){
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc50010':'cc50010';
        $query="SELECT descuento from $this->table where cc50000id='$id' and $subtotal>=$this->table.min and $subtotal <=$this->table.max";
        return $this->ejecutarConsulta($query);

    }

    public function descuentoCliente($id){
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->sis50200=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50200':'sis50200';
        $sql="SELECT descuento from $this->cc00000 where id=(select id_cliente from $this->sis50200 where id='$id')";
        return $this->ejecutarConsulta($sql);

    }

    public function descuentoClientePedido($id){
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $sql="SELECT descuento from $this->cc00000 where id='$id'";
        return $this->ejecutarConsulta($sql);

    }

    


    
    public function ListarCabeceraCarritoId($id){
       
        $query=" SELECT `id`,monto_abonado, `id_usuario`, descuento_porce_cc, `id_cliente`, `id_sucursal`, `fecha_cc_tem`, `subtotal_cc_tem`, `iva_cc_tem`, `total_cc_tem`, `descuento_cc_tem` 
        FROM $this->table
        where id='$id' ";
       return $this->ejecutarConsulta($query);

    }


    public function ListarCarrito( $idUsuario, $idCliente, $idSucursal){
       
        $query=" SELECT $this->table.id, id_cabecera, id_producto, descripcion_producto, cantidad_producto, precio_producto, descuento_producto, subtotal_producto 
        FROM $this->tableDetalle 
        INNER JOIN  $this->table ON  $this->table.id=$this->tableDetalle.id_cabecera
        where $this->table.id_usuario='$idUsuario' AND $this->table.id_cliente='$idCliente' AND $this->table.id_sucursal='$idSucursal'";
       return $this->ejecutarConsulta($query);

    }

    public function GuardarCabecera( $id_usuario, $id_cliente, $id_sucursal, $fecha_cc_tem, $subtotal_cc_tem, $iva_cc_tem, $total_cc_tem, $descuento_cc_tem){
       
        $query="INSERT INTO $this->table ( id_usuario, id_cliente, id_sucursal, fecha_cc_tem, subtotal_cc_tem, iva_cc_tem, total_cc_tem, descuento_cc_tem) 
        VALUES ('$id_usuario', '$id_cliente', '$id_sucursal', '$fecha_cc_tem', '$subtotal_cc_tem', '$iva_cc_tem', '$total_cc_tem', '$descuento_cc_tem')";
        return $this->ejecutarConsulta_retornarID($query);

    }

    public function GuardarCarrito($id_cabecera, $id_producto, $descripcion_producto, $costo_producto, $stock_producto, $bodega_producto, $cantidad_producto, $precio_producto, $descuento_producto, $subtotal_producto,$marca_producto,$descuentoCliente){
        $sql="INSERT INTO $this->tableDetalle (`id_cabecera`, `id_producto`, `descripcion_producto`, `costo_producto`, `stock_producto`, `bodega_producto`, `cantidad_producto`, `precio_producto`, `descuento_producto`, `subtotal_producto`,cantidad_guia,marca_producto,descuento_cliente) 
        VALUES ('$id_cabecera','$id_producto','$descripcion_producto','$costo_producto','$stock_producto','$bodega_producto','$cantidad_producto' ,'$precio_producto','$descuento_producto' ,'$subtotal_producto','$cantidad_producto','$marca_producto','$descuentoCliente')";
        return $this->ejecutarConsulta_retornarID($sql);
    }

    public function GuardarCabecera60200( $id_usuario, $id_cliente, $id_sucursal, $fecha_cc_tem, $subtotal_cc_tem, $iva_cc_tem, $total_cc_tem, $descuento_cc_tem){
       
        $this->table60200=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis60200':'sis60200';
        $this->tableDetalle60300=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis60300':'sis60300';

        $query="INSERT INTO $this->table60200 ( id_usuario, id_cliente, id_sucursal, fecha_cc_tem, subtotal_cc_tem, iva_cc_tem, total_cc_tem, descuento_cc_tem) 
        VALUES ('$id_usuario', '$id_cliente', '$id_sucursal', '$fecha_cc_tem', '$subtotal_cc_tem', '$iva_cc_tem', '$total_cc_tem', '$descuento_cc_tem')";
        return $this->ejecutarConsulta_retornarID($query);

    }

    public function GuardarCarrito60300($id_cabecera, $id_producto, $descripcion_producto, $costo_producto, $stock_producto, $bodega_producto, $cantidad_producto, $precio_producto, $descuento_producto, $subtotal_producto,$marca_producto,$descuentoCliente){
      
        $this->tableDetalle60300=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis60300':'sis60300';
        $sql="INSERT INTO $this->tableDetalle60300 (`id_cabecera`, `id_producto`, `descripcion_producto`, `costo_producto`, `stock_producto`, `bodega_producto`, `cantidad_producto`, `precio_producto`, `descuento_producto`, `subtotal_producto`,cantidad_guia,marca_producto,descuento_cliente) 
        VALUES ('$id_cabecera','$id_producto','$descripcion_producto','$costo_producto','$stock_producto','$bodega_producto','$cantidad_producto' ,'$precio_producto','$descuento_producto' ,'$subtotal_producto','$cantidad_producto','$marca_producto','$descuentoCliente')";
      
        return $this->ejecutarConsulta_retornarID($sql);
    }
    

    public function ActualizarCarrito($id,$id_cabecera, $id_producto, $descripcion_producto, $costo_producto, $stock_producto, $bodega_producto, $cantidad_producto, $precio_producto, $descuento_producto, $subtotal_producto){
        $sql="UPDATE $this->tableDetalle SET 
        `id_cabecera`='$id_cabecera',
        `id_producto`='$id_producto',
        `descripcion_producto`='$descripcion_producto',
        `costo_producto`='$costo_producto',
        `stock_producto`='$stock_producto',
        `bodega_producto`='$bodega_producto',
        `cantidad_producto`='$cantidad_producto',
        `precio_producto`='$precio_producto',
        `descuento_producto`='$descuento_producto',
        `subtotal_producto`= '$subtotal_producto'
        WHERE  `id`='$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function actualizarCabeceraCarrito($id, $subtotal_cc_tem, $iva_cc_tem, $total_cc_tem, $descuento_cc_tem){

        $sql="UPDATE $this->table SET 
        subtotal_cc_tem='$subtotal_cc_tem',
        iva_cc_tem='$iva_cc_tem',
        total_cc_tem='$total_cc_tem',
        descuento_cc_tem='$descuento_cc_tem' 
        WHERE  id='$id'";
         return $this->ejecutarConsulta($sql);
    }


    public function actualizarItemCarrito($id,$id_cabecera, $id_producto, $descripcion_producto, $cantidad_producto, $precio_producto, $descuento_producto, $subtotal_producto){

        $sql="UPDATE $this->tableDetalle SET 
        id_cabecera='$id_cabecera',
        id_producto='$id_producto',
        descripcion_producto='$descripcion_producto',
        cantidad_producto='$cantidad_producto',
        precio_producto='$precio_producto',
        descuento_producto='$descuento_producto',
        subtotal_producto='$subtotal_producto' WHERE id= '$id' ";
         return $this->ejecutarConsulta($sql);
    }

    public function eliminarCabeceraCarriro($id){
        $sql="DELETE FROM $this->table WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function eliminarItemCarriro($id){
        $sql="DELETE FROM $this->tableDetalle WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function editarDescripcionItemCarriro($id,$descripcion_producto){
        $sql="UPDATE $this->tableDetalle SET 
        descripcion_producto='$descripcion_producto'
        WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function modificarCodigoCarrito($id,$id_producto){
        $sql="UPDATE $this->tableDetalle SET 
        id_producto='$id_producto'
        WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function modificarCantidadCarrito($id,$cantidad_producto){
        $sql="UPDATE $this->tableDetalle SET 
        cantidad_producto=$cantidad_producto,
        cantidad_guia= $cantidad_producto,
        subtotal_producto=(($cantidad_producto*precio_producto)-descuento_producto)
        WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }


    public function modificarPrecioCarrito($id,$precio_producto){
        $sql="UPDATE $this->tableDetalle SET 
        precio_producto='$precio_producto',
        subtotal_producto=((cantidad_producto*$precio_producto)-descuento_producto)
        WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function modificarDescuentoCarrito($id,$descuento_producto){
        $sql="UPDATE $this->tableDetalle SET 
        descuento_producto='$descuento_producto',
        subtotal_producto=((cantidad_producto*precio_producto)-$descuento_producto)
        WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function ingresarMonto($id,$monto){
        $sql="UPDATE $this->table SET 
        monto_abonado=monto_abonado+'$monto'
        WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function modificarGuiaCarrito($id,$value){
        $sql="UPDATE $this->tableDetalle SET 
        guia='$value'
        WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
    }

    public function modificarCantidadGuia($id,$value){

        $sql="UPDATE $this->tableDetalle SET 
        cantidad_guia= '$value'
        WHERE id= '$id' ";
        return $this->ejecutarConsulta($sql);
        
    }

    public function llenarCarritoTemporal($idCabecera){
        $sql="SELECT `id`, `id_cabecera`, `id_producto`, `descripcion_producto`, `costo_producto`, `stock_producto`, `bodega_producto`, `cantidad_producto`, `precio_producto`, `descuento_producto`,`descuento_cliente`, `subtotal_producto`,guia FROM $this->tableDetalle WHERE id_cabecera='$idCabecera'";
        return $this->ejecutarConsulta($sql);
    }

    public function verificarProductos($codigo){
        $this->inv00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
        $sql="SELECT `codigo`, `costo`, `descripcion` FROM $this->inv00000 WHERE codigo='$codigo'";
        return $this->ejecutarConsulta($sql);
    }


    public function listarCarritoGuia($id_cabecera){
        $sql="SELECT `id`, `id_cabecera`, `id_producto`, `descripcion_producto`, `costo_producto`, `stock_producto`, `bodega_producto`, `cantidad_producto`, `precio_producto`, `descuento_producto`, `subtotal_producto`, `guia` FROM $this->tableDetalle WHERE guia = '1' AND id_cabecera='$id_cabecera'";
        return $this->ejecutarConsulta($sql);
    }

    public function listarDescuentoTotal($id_cabecera){
        $sql="SELECT SUM(descuento_producto)+SUM(descuento_cliente*cantidad_producto) as 'descuento' FROM $this->tableDetalle WHERE id_cabecera='$id_cabecera'";
        return $this->ejecutarConsulta($sql);
    }

    
}
