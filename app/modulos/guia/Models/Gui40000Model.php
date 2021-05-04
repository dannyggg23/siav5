<?php

namespace Models;



class Gui40000Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui40000':'gui40000';
        parent::__construct($this->table,$adapter);
    }

    public function select($bodega){
        $bodega=strtoupper($bodega);
        $sql="SELECT `id`, `bodega`, `direccion`, `secuencial`, `activo` FROM $this->table WHERE  bodega='$bodega'";
        return  $this->ejecutarConsulta($sql);
    }
    
}
