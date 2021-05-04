<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00300Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter)
    {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00300':'sis00300';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox()
    {
        $query="SELECT id,usuario as nombre FROM $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComboboxEdit($id)
    {
        $query="SELECT id,usuario as nombre FROM $this->table where id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getLogin($user,$password,$param=array())
    {
        if (isset($param['param1']))
        {
            $this->table=$param['param1'].'.sis00300';
            $query="SELECT COUNT(*) as Existe FROM $this->table WHERE usuario = '".$user."' and pass = '".$password."' and activo = 1";
        }
        else
        {
            $query="SELECT COUNT(*) as Existe FROM $this->table WHERE usuario = '".$user."' and pass = '".$password."' and activo = 1";
        }
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }
    
    public function getTablePaginacion($buscar,$offset,$per_page){
        $query="SELECT z.* FROM $this->table z where (z.usuario like '%$buscar%' or z.nombre like '%$buscar%' or z.apellido like '%$buscar%') LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function getRucEmpresa($bd)
    {
        $this->sis00100=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
        $query="SELECT a.ruc FROM $this->sis00100 a  WHERE a.bd = '".$bd."'";
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }
    
    public function getTransacciones($id) {
        $this->opt00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.opt00000':'opt00000';
        $query="SELECT count(*) as numrows from $this->opt00000 WHERE usuario = '$id'";
        $result=$this->ejecutarSql($query);
        return $result;
    }

}