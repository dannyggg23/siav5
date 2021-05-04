<?php

namespace Models;



class Cc30000Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc30000':'cc30000';
        parent::__construct($this->table,$adapter);
    }

    public function listarFacturas($usuario){
        $this->cc20010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc20010':'cc20010';
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->cc00002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00002':'cc00002';

        $sql="SELECT cc30000.id, cc30000.pedido ,cc30000.documento, cc00000.ruc,cc00000.razonsocial, cc00002.direccion, cc30000.fecha, cc30000.subtotal, cc30000.descuento, cc30000.iva, cc30000.total, cc30000.usuario,
         IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc30000.pedido ),0) AS monto_abonado,
         cc30000.total-IFNULL((SELECT SUM(cc20010.valor) FROM $this->cc20010 WHERE cc20010.documento= cc30000.pedido ),0) AS pendiente
         FROM $this->table  INNER JOIN $this->cc00000 ON cc00000.id=cc30000.cc00000id INNER JOIN $this->cc00002 ON cc00002.id=cc30000.cc00002id
         WHERE cc30000.usuario='$usuario' order by cc30000.id desc";
        
        return $this->ejecutarConsulta($sql);   
    }

    public function listarDetalle($pedido){
        $this->cc30010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc30010':'cc30010';
        $this->inv00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
        $sql="SELECT cc30010.*,inv00000.codigo from  $this->cc30010 
        INNER JOIN  $this->inv00000 on inv00000.id=cc30010.inv00000id
        INNER JOIN $this->table ON cc30000.id=cc30010.cc30000id
        WHERE cc30000.pedido='$pedido'";
        return $this->ejecutarConsulta($sql);  
    }

    public function listarDetallePedidoAprobar($pedido){
        
        $this->cc11010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc11010':'cc11010';
        $this->inv00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
        $this->cc11000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc11000':'cc11000';
       
       $sql="SELECT cc11010.*,inv00000.codigo from  $this->cc11010 
        INNER JOIN  $this->inv00000 on inv00000.codigo=cc11010.inv00000codigo
        INNER JOIN $this->cc11000 ON cc11000.id=cc11010.cc11000id
        WHERE cc11000.documento='$pedido'";
        return $this->ejecutarConsulta($sql);  
    }
    
}
