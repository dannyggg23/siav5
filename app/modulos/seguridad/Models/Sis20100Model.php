<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20100Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00300':'sis00300';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getPermisos($usuario){
        //$query="SELECT d.id,d.nombre,d.descripcion FROM dti_external_user a INNER JOIN dti_rol_external b ON b.id = a.rolid INNER JOIN $this->table c ON b.id = c.rolid INNER JOIN dti_permisos d ON d.id = c.permisosid WHERE a.user = '".$usuario."' ";
        $query="SELECT * FROM $this->table where id = 100";
        $inv_articulo=$this->ejecutarSql($query);
        return $inv_articulo;
    }
}