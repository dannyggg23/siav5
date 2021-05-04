<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-22
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00050Model extends \ModeloBase {

    public function __construct($adapter) {
        $table='sis00050';
        parent::__construct($table,$adapter);
    }

    public function getLogin($user,$password)
    {
        $query="SELECT COUNT(*) as Existe FROM sis00050 WHERE usuario = '".$user."' and pass = '".$password."' and activo = 1";
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }
    
    public function getUserID($user)
    {
        $query="SELECT id,nombre,apellido,correo,usuario,bd FROM sis00050 WHERE usuario = '".$user."'";
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }
    
    public function getRucEmpresa($user)
    {
        $query="SELECT b.ruc FROM sis00050 a INNER JOIN sis00059 b ON a.id = b.clienteid WHERE usuario = '".$user."'";
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }
    
    public function getCliente($cliente)
    {
        $query="SELECT * FROM sis00050 WHERE usuario = '".$cliente."'";
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }
    
    public function getTablePaginacion($buscar,$offset,$per_page){
        $query="SELECT z.* FROM sis00050 z where (z.nombre like '%$buscar%' or z.apellido like '%$buscar%') LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTransacciones($id) {
        $query="SELECT count(*) as numrows from sis00000 where rolid = $id ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getFECliente() {
        $query="SELECT a.bd,b.ruc FROM sis00050 a INNER JOIN sis00059 b ON a.id = b.clienteid INNER JOIN sis20012 c ON c.ruc = b.ruc AND c.modulesid IN (11,21) AND a.id NOT IN (1)";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getBDCliente($rucEmpresa) {
        $query="SELECT a.bd FROM sis00050 a INNER JOIN sis00059 b ON a.id = b.clienteid where b.ruc = '$rucEmpresa' LIMIT 0,1;";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getBDUsuario($usuario) {
        $query="SELECT a.bd FROM sis00050 a WHERE a.usuario = '$usuario' LIMIT 0,1;";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getEmpresas($prefilo) {
        $query="SELECT a.ruc,b.bd FROM sis00059 a INNER JOIN sis00050 b ON a.clienteid = b.id WHERE a.prefijo = '$prefilo' LIMIT 0,1;";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getCountBDCliente($rucEmpresa) {
        $query="SELECT count(a.bd) as numrows FROM sis00050 a INNER JOIN sis00059 b ON a.id = b.clienteid where b.ruc = '$rucEmpresa' LIMIT 0,1;";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}