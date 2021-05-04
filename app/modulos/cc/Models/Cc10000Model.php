<?php

namespace Models;



class Cc10000Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc10000':'cc10000';
        parent::__construct($this->table,$adapter);
    }

    public function listarPedidos($usuario){
        $this->cc20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc20010':'cc20010';
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';

        $sql="SELECT cc10000.id,cc10000.aprobado,cc10000.alerta_porcentaje, cc10000.documento, cc00000.ruc,cc00000.razonsocial, cc00002.direccion, cc10000.fecha, cc10000.subtotal, cc10000.descuento, cc10000.iva, cc10000.total, cc10000.usuario,
        IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc10000.documento ),0) AS monto_abonado,
         cc10000.total-IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc10000.documento ),0) AS pendiente
         FROM $this->table  INNER JOIN $this->cc00000 ON cc00000.id=cc10000.cc00000id INNER JOIN $this->cc00002 ON cc00002.id=cc10000.cc00002id
         WHERE cc10000.usuario='$usuario'
         ORDER BY cc10000.fecha DESC";
        
        return $this->ejecutarConsulta($sql);   
}

public function listarPedidosAll($usuario){
    $this->cc20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc20010':'cc20010';
    $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
    $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';
    $this->cc10010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc10010':'cc10010';

    $sql="SELECT cc10000.id,cc10000.aprobado,cc10000.alerta_porcentaje, cc10000.documento, cc00000.ruc,cc00000.razonsocial, cc00002.direccion, cc10000.fecha, cc10000.subtotal, cc10000.descuento, cc10000.iva, cc10000.total, cc10000.usuario,
    (SELECT sum(cantidad) from $this->cc10010  where  cc10000id=cc10000.id) as nitems,
    IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc10000.documento ),0) AS monto_abonado,
     cc10000.total-IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc10000.documento ),0) AS pendiente
     FROM $this->table  INNER JOIN $this->cc00000 ON cc00000.id=cc10000.cc00000id INNER JOIN $this->cc00002 ON cc00002.id=cc10000.cc00002id
    --  WHERE cc10000.usuario='$usuario'
     ORDER BY cc10000.fecha DESC";
     //die($sql);
    
    return $this->ejecutarConsulta($sql);   
}

    public function listarPedidosAprobar($usuario){
        $this->cc20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc20010':'cc20010';
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';
        $this->cc11000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc11000':'cc11000';


        $sql="SELECT cc11000.id,cc11000.aprobado,cc11000.alerta_porcentaje, cc11000.documento, cc00000.ruc,cc00000.razonsocial, cc00002.direccion, cc11000.fecha, cc11000.subtotal, cc11000.descuento, cc11000.iva, cc11000.total, cc11000.usuario,
        IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc11000.documento ),0) AS monto_abonado,
        cc11000.total-IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc11000.documento ),0) AS pendiente
        FROM $this->cc11000  INNER JOIN $this->cc00000 ON cc00000.id=cc11000.cc00000id INNER JOIN $this->cc00002 ON cc00002.id=cc11000.cc00002id
        WHERE  cc11000.aprobadoCobranza=0
        ORDER BY cc11000.fecha DESC";
        return $this->ejecutarConsulta($sql);   
    }

    public function listarPedidosCobranzas($usuario){
        $this->cc20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc20010':'cc20010';
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';
        $this->cc11000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc11000':'cc11000';


        $sql="SELECT cc11000.aprobadoCobranza,cc11000.id,cc11000.aprobado,cc11000.alerta_porcentaje, cc11000.documento, cc00000.ruc,cc00000.razonsocial, cc00002.direccion, cc11000.fecha, cc11000.subtotal, cc11000.descuento, cc11000.iva, cc11000.total, cc11000.usuario,
        IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc11000.documento ),0) AS monto_abonado,
        cc11000.total-IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc11000.documento ),0) AS pendiente
        FROM $this->cc11000  INNER JOIN $this->cc00000 ON cc00000.id=cc11000.cc00000id INNER JOIN $this->cc00002 ON cc00002.id=cc11000.cc00002id
        WHERE  cc11000.usuario='$usuario'
        ORDER BY cc11000.fecha DESC";
        return $this->ejecutarConsulta($sql);   
    }

    public function listarPedidosCobranzasTodos(){
        $this->cc20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc20010':'cc20010';
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';
        $this->cc11000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc11000':'cc11000';

 
        $sql="SELECT cc11000.aprobadoCobranza,cc11000.id,cc11000.aprobado,cc11000.alerta_porcentaje, cc11000.documento, cc00000.ruc,cc00000.razonsocial, cc00002.direccion, cc11000.fecha, cc11000.subtotal, cc11000.descuento, cc11000.iva, cc11000.total, cc11000.usuario,
        IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc11000.documento ),0) AS monto_abonado,
        cc11000.total-IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc11000.documento ),0) AS pendiente
        FROM $this->cc11000  INNER JOIN $this->cc00000 ON cc00000.id=cc11000.cc00000id INNER JOIN $this->cc00002 ON cc00002.id=cc11000.cc00002id
        ORDER BY cc11000.fecha DESC";
        return $this->ejecutarConsulta($sql);   
    }

public function listarPedidosId($pedido){
    $this->cc20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc20010':'cc20010';
    $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
    $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';

    $sql="SELECT cc10000.id,cc10000.aprobado,cc10000.alerta_porcentaje, cc10000.documento, cc00000.ruc,cc00000.razonsocial, cc00002.direccion, cc10000.fecha, cc10000.subtotal, cc10000.descuento, cc10000.iva, cc10000.total, cc10000.usuario,
    IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc10000.documento ),0) AS monto_abonado,
     cc10000.total-IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc10000.documento ),0) AS pendiente
     FROM $this->table  INNER JOIN $this->cc00000 ON cc00000.id=cc10000.cc00000id INNER JOIN $this->cc00002 ON cc00002.id=cc10000.cc00002id
     WHERE cc10000.documento='$pedido'
     ORDER BY cc10000.fecha DESC";
    return $this->ejecutarConsulta($sql);   
}

public function listarCarrito(){
    $this->sis50200=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50200':'sis50200';
    $this->sis20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis20010':'sis20010';
    $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
    $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';

    $sql="SELECT sis50200.id, sis50200.id_usuario, sis50200.id_cliente, sis50200.id_sucursal, sis50200.fecha_cc_tem, sis50200.subtotal_cc_tem, sis50200.iva_cc_tem, sis50200.total_cc_tem, sis50200.descuento_cc_tem, sis50200.descuento_porce_cc, sis50200.alerta_porcentaje, sis50200.aprobado,cc00000.ruc,cc00000.razonsocial, cc00002.direccion,      
    IFNULL((SELECT SUM(sis20010.valor) FROM $this->sis20010 WHERE sis20010.documento= sis50200.id ),0) AS monto_abonado,
     sis50200.total_cc_tem-IFNULL((SELECT SUM(sis20010.valor) FROM $this->sis20010 WHERE sis20010.documento= sis50200.id ),0) AS pendiente
     FROM $this->sis50200  INNER JOIN $this->cc00000 ON cc00000.id=sis50200.id_cliente INNER JOIN $this->cc00002 ON cc00002.id=sis50200.id_sucursal
     ORDER BY sis50200.fecha_cc_tem DESC";
    
    return $this->ejecutarConsulta($sql);   
}

public function listarCarritoId($id){
    $this->sis50200=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis50200':'sis50200';
    $this->sis20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis20010':'sis20010';
    $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
    $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';

    $sql="SELECT sis50200.id, sis50200.id_usuario, sis50200.id_cliente, sis50200.id_sucursal, sis50200.fecha_cc_tem, sis50200.subtotal_cc_tem, sis50200.iva_cc_tem, sis50200.total_cc_tem, sis50200.descuento_cc_tem, sis50200.descuento_porce_cc, sis50200.alerta_porcentaje, sis50200.aprobado,cc00000.ruc,cc00000.razonsocial, cc00002.direccion,      
    IFNULL((SELECT SUM(sis20010.valor) FROM $this->sis20010 WHERE sis20010.documento= sis50200.id ),0) AS monto_abonado,
     sis50200.total_cc_tem-IFNULL((SELECT SUM(sis20010.valor) FROM $this->sis20010 WHERE sis20010.documento= sis50200.id ),0) AS pendiente
     FROM $this->sis50200  INNER JOIN $this->cc00000 ON cc00000.id=sis50200.id_cliente INNER JOIN $this->cc00002 ON cc00002.id=sis50200.id_sucursal
     WHERE sis50200.id='$id'
     ORDER BY sis50200.fecha_cc_tem DESC";
    return $this->ejecutarConsulta($sql);   
}
    
}
