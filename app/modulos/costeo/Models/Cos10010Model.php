<?php

namespace Models;



class Cos10010Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cos10010':'cos10010';
        parent::__construct($this->table,$adapter);
    }

    public function listarEntradas($documento){
        $this->inv00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
        $sql="SELECT cos10010.id,cos10010.tasa,cos10010.inv00000id,cos10010.cantidad,inv00000.descripcion,inv00000.unidad FROM $this->table INNER JOIN $this->inv00000 on inv00000.id=cos10010.inv00000id WHERE cos10010.documento='$documento'";  
        return $this->ejecutarConsulta($sql);   
    }

    public function listarSalidas($documento){
        $this->inv00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
        $this->cos10020=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cos10020':'cos10020';
        $sql="SELECT cos10020.id,cos10020.inv00000id,cos10020.cantidad,inv00000.descripcion,inv00000.unidad FROM $this->cos10020 INNER JOIN $this->inv00000 on inv00000.id=cos10020.inv00000id WHERE cos10020.documento='$documento'";  
        return $this->ejecutarConsulta($sql);   
    }

    public function listarOrdenes(){
        $this->cos20100=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cos20100':'cos20100';
        $this->inv00001=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00001':'inv00001';
        $sql="SELECT cos20100.id, cos20100.documento, cos20100.observacion, cos20100.fecha, cos20100.inv00001idEntrada, cos20100.inv00001idSalida, cos20100.editable, cos20100.estado, cos20100.usuario, cos20100.activo,a.bodega as 'bodEntrada',b.bodega as 'bodSalida' FROM $this->cos20100
        INNER JOIN $this->inv00001 a ON a.id=cos20100.inv00001idEntrada
        INNER JOIN $this->inv00001 b ON b.id=cos20100.inv00001idSalida";
        return $this->ejecutarConsulta($sql);   
    }
    
}
