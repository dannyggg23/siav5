<?php

namespace Models;



class Gui30000Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui30000':'gui30000';
        parent::__construct($this->table,$adapter);
    }
    
    public function getNumGuia($secuencial) {

        $this->gui40000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui40000':'gui40000';
        $query="SELECT num_guia FROM $this->gui40000  WHERE secuencial = '$secuencial'";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function valNumPedido($numPedido) {

        $this->cc10000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc10000':'cc10000';
        $this->cc30000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc30000':'cc30000';
        $query="SELECT sum(total) as total from (
                    SELECT count(documento) as total FROM $this->cc10000 where documento = '$numPedido'
                    UNION ALL
                    SELECT count(documento) as total FROM $this->cc30000 where documento = '$numPedido'
                ) as tb1";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}
