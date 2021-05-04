<?php

namespace Models;



class Gui30010Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui30010':'gui30010';
        parent::__construct($this->table,$adapter);
    }
    
}
