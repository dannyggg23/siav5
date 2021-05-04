<?php

namespace Models;



class Inv10100Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv10100':'inv10100';
        parent::__construct($this->table,$adapter);
    }

    public function detalleTransferencia($idTransferencia,$bodega){
      $this->inv00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
      $sql="SELECT inv10100.*,inv00000.descripcion from $this->table 
      INNER JOIN  $this->inv00000 ON inv00000.codigo=inv10100.inv00000codigo
      WHERE inv10100.inv10000id='$idTransferencia' AND UPPER(inv10100.bodega)=UPPER('$bodega')";
        return  $this->ejecutarConsulta($sql);
      }

    public function separarBodegasTransferencias($inv10000id,$bodega){
      $sql="SELECT UPPER(bodega) as 'bodega' from $this->table WHERE inv10000id = '$inv10000id' and bodega='$bodega'  GROUP BY bodega";
      return  $this->ejecutarConsulta($sql);
    }

    public function deltalleTransferenciaBodega($inv10000id,$bodega){
      $sql="SELECT id,inv10000id,descripcion,inv00000codigo,linea,cantidad,bodega,bodega_destino from $this->table 
      where inv10000id='$inv10000id' AND bodega='$bodega' and aprobado=0";
      return  $this->ejecutarConsulta($sql);

    }

    public function listarTransferenciaBodega($bodega){
      $this->inv10000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv10000':'inv10000';

      $sql="SELECT DISTINCT a.*, b.aprobado from $this->inv10000 a INNER JOIN $this->table b ON a.id = b.inv10000id 
      where b.bodega = '$bodega' ";
      return  $this->ejecutarConsulta($sql);
      
    }

    public function litarDetaleTransferencia($id,$bodega){
      $this->inv10000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv10000':'inv10000';
      $this->inv00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
      $sql="SELECT inv10100.* ,(select descripcion from $this->inv00000 where codigo=inv10100.inv00000codigo ) as 'descripcion' from 
      $this->table INNER JOIN  $this->inv10000 ON inv10000.id=inv10100.inv10000id
      WHERE inv10100.inv10000id='$id'";
      //WHERE inv10100.inv10000id='$id' AND inv10100.bodega='$bodega'";

      return  $this->ejecutarConsulta($sql);
    }

}
