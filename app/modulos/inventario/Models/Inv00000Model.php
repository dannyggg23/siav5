<?php

namespace Models;



class Inv00000Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.inv00000':'inv00000';
        parent::__construct($this->table,$adapter);
    }

    /*
     * FUNCIONES CON ADAPTER SECUNDARIO
     */
    
    public function tieneStock($articulo,$bodega){
        $sql="select isnull((QTYONHND-ATYALLOC),0) as stock from iv00102 where itemnmbr = '$articulo' and LOCNCODE = '$bodega'";
        return  $this->ejecutarConsulta($sql);
    }
}
