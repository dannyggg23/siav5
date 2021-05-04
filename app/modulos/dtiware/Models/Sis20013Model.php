<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-04
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis20013Model extends \ModeloBase
{

    private $table;

    public function __construct($adapter)
    {
        $this->table='sis20013';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '')
    {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getMontoOcupado($empresa)
    {
        $this->sis00061='sis00061';
        $query="SELECT SUM(costo) as monto FROM $this->table a INNER JOIN $this->sis00061 b ON a.planid = b.id WHERE a.ruc = '$empresa'";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getTablePaginacion($cliente,$empresa,$buscar,$offset,$per_page)
    {
        $this->sis00061='sis00061';
        $query="SELECT b.nombre_plan as plan,b.descripcion,z.fecha,z.disponible,z.usado FROM $this->table z INNER JOIN $this->sis00061 b ON z.planid = b.id where (b.nombre_plan like '%$buscar%') and clienteid = '$cliente' and ruc = '$empresa' LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getEmpresasXCrear($cliente,$empresa,$planid)
    {
        $query="SELECT SUM(disponible)-SUM(usado) as total FROM $this->table a WHERE a.clienteid = $cliente AND a.ruc = '$empresa' AND a.planid = $planid";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComprobantesDisponibles($cliente,$empresa,$modulo)
    {
        $this->sis00061='sis00061';
        $query="SELECT SUM(disponible)-SUM(usado) as total FROM $this->table a WHERE a.clienteid = $cliente AND a.ruc = '$empresa' AND a.planid IN (SELECT b.id FROM $this->sis00061 b WHERE b.sis00060id = $modulo)";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getVentasdia($cliente,$empresa,$modulo)
    {
        $this->cc30000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc30000':'cc30000';
        $query="SELECT sum(total) as venta from $this->cc30000 where empresa= ".$empresa." and cast(fecha as date) = cast(now() as date) ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getVentasSemana($cliente,$empresa,$modulo)
    {
        $this->cc30000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc30000':'cc30000';
        $query="SELECT sum(total) as venta from $this->cc30000 where empresa= ".$empresa." and cast(fecha as date) >= cast(DATE_ADD(CURDATE(), INTERVAL -5 DAY) as date)  ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getVentasMes($cliente,$empresa,$modulo)
    {
        $this->cc30000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc30000':'cc30000';
        $query="SELECT sum(total) as venta from $this->cc30000 where empresa= ".$empresa." and MONTH(fecha) = MONTH(now()) ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getVentasAnio($cliente,$empresa,$modulo)
    {
        $this->cc30000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc30000':'cc30000';
        $query="SELECT sum(total) as venta from $this->cc30000 where empresa= ".$empresa." and YEAR(fecha) = YEAR(now()) ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function countPlan($cliente,$empresa,$modulo)
    {
        $this->sis00061='sis00061';
        $query="SELECT count(id) as numrows FROM $this->sis00061 a WHERE a.sis00060id = $modulo AND a.id IN (SELECT b.planid FROM $this->table b WHERE b.clienteid = $cliente AND b.ruc = '$empresa' AND b.activo = 1)";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getIdPlan($cliente,$empresa,$modulo)
    {
        $this->sis00061='sis00061';
        $query="SELECT id FROM $this->table a WHERE a.clienteid = '$cliente' AND a.ruc = '$empresa' AND a.planid IN (SELECT b.id FROM $this->sis00061 b WHERE b.sis00060id = $modulo) AND a.activo = 1 LIMIT 0,1";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComprobantesEmitidos($empresa)
    {
        $this->sis10000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis10000':'sis10000';
        $query="SELECT COUNT(id) as total FROM $this->sis10000 a WHERE a.empresa = '$empresa'";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComprobantesEmitidosMensual($empresa)
    {
        $this->sis10000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis10000':'sis10000';
        $query="SELECT COUNT(id) as total FROM $this->sis10000 a WHERE a.empresa = '$empresa' AND MONTH(fecha) = MONTH(now())";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getComprobantesEmitidosAnual($empresa)
    {
        $this->sis10000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis10000':'sis10000';
        $query="SELECT COUNT(id) as total FROM $this->sis10000 a WHERE a.empresa = '$empresa' AND YEAR(fecha) = YEAR(now())";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}