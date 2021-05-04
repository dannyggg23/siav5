<?php

namespace Models;

class Sis00020Model extends \ModeloBase 
{
    private $table;
        
    public function __construct($adapter) 
    {
        $this->table = "sis00020";
        parent::__construct($this->table, $adapter);
    }
    
    public function getLogin($user,$password)
    {
        $query="SELECT COUNT(*) as Existe FROM sis00020 WHERE usuario = '".$user."' and pass = '".$password."'";
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }
    
    public function getTablePaginacion($buscar,$offset,$per_page){
        $query="SELECT z.* FROM sis00020 z where (z.nombre like '%$buscar%' or z.apellido like '%$buscar%') LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTransacciones($id) {
        $query="SELECT count(*) as numrows from sis00000 where rolid = $id ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}