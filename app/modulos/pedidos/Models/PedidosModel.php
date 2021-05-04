<?php

namespace Models;



class PedidosModel extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui30000':'gui30000';
        parent::__construct($this->table,$adapter);
    }

    public function verificar($pedido,$codigo){
        $this->gui30010=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui30010':'gui30010';
      
      $sql="SELECT * FROM $this->table INNER JOIN $this->gui30010 ON gui30000.id=gui30010.gui30000id WHERE gui30000.pedido='$pedido' AND gui30010.inv00000codigo='$codigo'";
        return  $this->ejecutarConsulta($sql);
      }

}
