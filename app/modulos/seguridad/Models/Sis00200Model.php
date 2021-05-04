<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00200Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00200':'sis00200';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getCountResul(){
        $query="SELECT count(*) as numrows from $this->table";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getModal(){
        $query="SELECT a.id,a.rol,a.descripcion FROM $this->table a";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTransacciones($id) {
        $this->sis41000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis41000':'sis41000';
        $query="SELECT count(*) as numrows from $this->sis41000 WHERE sis00200id = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getPermisoVentana($usuario,$ventana) {
        $this->sis41001=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis41001':'sis41001';
        $this->sis00201=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00201':'sis00201';
        $this->sis41002=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis41002':'sis41002';
        $this->sis00202=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00202':'sis00202';
        $this->sis41000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis41000':'sis41000';
        $this->sis00300=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00300':'sis00300';
        $query="SELECT count(e.ventana) as numrows FROM $this->table a INNER JOIN $this->sis41001 b ON a.id = b.sis00200id INNER JOIN $this->sis00201 c ON c.id = b.sis00201id INNER JOIN $this->sis41002 d ON d.sis00201id = c.id INNER JOIN $this->sis00202 e ON e.id = d.sis00202id INNER JOIN $this->sis41000 f ON a.id = f.sis00200id INNER JOIN $this->sis00300 g ON g.id = f.sis00300id where g.usuario = '$usuario' and e.ventana= '$ventana' ";
        $result=$this->ejecutarSqlObj($query);
        return $result;
    }
}