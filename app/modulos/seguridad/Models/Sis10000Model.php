<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-02
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis10000Model extends \ModeloBase {

    private $table,$adapter;

    public function __construct($adapter,$param=array())
    {
        if (isset($param['param1']))
        {
            $this->table=$param['param1'].'.sis10000';
        }
        else
        {
            $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis10000':'sis10000';
        }        
        $this->adapter=$adapter;
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '')
    {
            $query="";
            $result=$this->ejecutarSql($query);
            return $result;
    }
    
    public function getFEAll($finicio,$ffin,$cliente)
    {
        $this->sis00100=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->cp00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cp00000':'cp00000';
        $query="select b.*,a.ambiente,d.correo from $this->sis00100 a INNER JOIN $this->table b ON a.id = b.empresa INNER JOIN $this->cc00000 d ON b.cliente = d.codigo and a.id = d.empresa where cast(b.fechaAutorizacion as date) BETWEEN '$finicio' and '$ffin' and b.empresa = '".$_SESSION['empresa']."' and (b.cliente like '%".$cliente."%' or b.nombre like '%".$cliente."%') and tipo = 'Factura'
                UNION ALL
                select z.*,x.ambiente,y.correo from $this->sis00100 x INNER JOIN $this->table z ON x.id = z.empresa INNER JOIN $this->cc00000 y ON z.cliente = y.codigo and x.id = y.empresa where cast(z.fechaAutorizacion as date) BETWEEN '$finicio' and '$ffin' and z.empresa = '".$_SESSION['empresa']."' and (z.cliente like '%".$cliente."%' or z.nombre like '%".$cliente."%') and tipo = 'Nota de Credito'
                UNION ALL
                select z.*,x.ambiente,y.correo from $this->sis00100 x INNER JOIN $this->table z ON x.id = z.empresa INNER JOIN $this->cp00000 y ON z.cliente = y.codigo and x.id = y.empresa where cast(z.fechaAutorizacion as date) BETWEEN '$finicio' and '$ffin' and z.empresa = '".$_SESSION['empresa']."' and (z.cliente like '%".$cliente."%' or z.nombre like '%".$cliente."%') and tipo = 'Comprobante de Retencion' ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getFEAllTipo($tipo,$finicio,$ffin,$cliente)
    {
        $query = "";
        $this->sis00100=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->cp00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cp00000':'cp00000';
        switch($tipo){
            case 'Factura':
                $query="select b.*,a.ambiente,d.correo from $this->sis00100 a INNER JOIN $this->table b ON a.id = b.empresa INNER JOIN $this->cc00000 d ON b.cliente = d.codigo and a.id = d.empresa where cast(b.fechaAutorizacion as date) BETWEEN '$finicio' and '$ffin' and b.empresa = '".$_SESSION['empresa']."' and (b.cliente like '%".$cliente."%' or b.nombre like '%".$cliente."%') and tipo = 'Factura'";
            break;
            case 'Nota de Credito':
                $query="select z.*,x.ambiente,y.correo from $this->sis00100 x INNER JOIN $this->table z ON x.id = z.empresa INNER JOIN $this->cc00000 y ON z.cliente = y.codigo and x.id = y.empresa where cast(z.fechaAutorizacion as date) BETWEEN '$finicio' and '$ffin' and z.empresa = '".$_SESSION['empresa']."' and (z.cliente like '%".$cliente."%' or z.nombre like '%".$cliente."%') and tipo = 'Nota de Credito'";
            break;
            case 'Comprobante de Retención':
                $query="select z.*,x.ambiente,y.correo from $this->sis00100 x INNER JOIN $this->table z ON x.id = z.empresa INNER JOIN $this->cp00000 y ON z.cliente = y.codigo and x.id = y.empresa where cast(z.fechaAutorizacion as date) BETWEEN '$finicio' and '$ffin' and z.empresa = '".$_SESSION['empresa']."' and (z.cliente like '%".$cliente."%' or z.nombre like '%".$cliente."%') and tipo = 'Comprobante de Retencion' ";
            break;
        }
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getFirmar($param=array())
    {
        if (isset($param['param1']))
        {
            $this->sis00100=$param['param1'].'.sis00100';
            $this->table=$param['param1'].'.sis10000';
            $query="select '".$param['param1']."' as ruc,a.firma,a.clavefirma,a.ambiente,b.tipo,case when b.tipo = 'Factura' THEN CONCAT(b.documento,'_FAC') when b.tipo = 'Comprobante de Retención' THEN CONCAT(b.documento,'_RET') when b.tipo = 'Nota de Crédito' THEN CONCAT(b.documento,'_NDC') ELSE b.documento END as documento from $this->sis00100 a INNER JOIN $this->table b ON a.id = b.empresa where b.autorizado = 'NO AUTORIZADO' and b.autorizacion = '' and a.firma is not null";
        }
        else
        {
            $this->sis00100=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
            $query="select '".$_SESSION['bdcliente']."' as ruc,a.firma,a.clavefirma,a.ambiente,b.tipo,case when b.tipo = 'Factura' THEN CONCAT(b.documento,'_FAC') when b.tipo = 'Comprobante de Retención' THEN CONCAT(b.documento,'_RET') when b.tipo = 'Nota de Crédito' THEN CONCAT(b.documento,'_NDC') ELSE b.documento END as documento from $this->sis00100 a INNER JOIN $this->table b ON a.id = b.empresa where b.autorizado = 'NO AUTORIZADO' and b.autorizacion = '' and a.firma is not null";        }
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getDownloadSri($param=array())
    {
        if (isset($param['param1']))
        {
            $this->sis00100=$param['param1'].'.sis00100';
            $this->table=$param['param1'].'.sis10000';
            $query="select '".$param['param1']."' as ruc,a.firma,a.clavefirma,a.ambiente,b.tipo,b.claveAcceso,case when b.tipo = 'Factura' THEN CONCAT(b.documento,'_FAC') when b.tipo = 'Comprobante de Retención' THEN CONCAT(b.documento,'_RET') when b.tipo = 'Nota de Crédito' THEN CONCAT(b.documento,'_NDC') ELSE b.documento END as documento from $this->sis00100 a INNER JOIN $this->table b ON a.id = b.empresa where b.autorizado = 'RECIBIDA' and b.autorizacion = '' and a.firma is not null";
        }
        else
        {
            $this->sis00100=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
            $query="select '".$_SESSION['bdcliente']."' as ruc,a.firma,a.clavefirma,a.ambiente,b.tipo,b.claveAcceso,case when b.tipo = 'Factura' THEN CONCAT(b.documento,'_FAC') when b.tipo = 'Comprobante de Retención' THEN CONCAT(b.documento,'_RET') when b.tipo = 'Nota de Crédito' THEN CONCAT(b.documento,'_NDC') ELSE b.documento END as documento from $this->sis00100 a INNER JOIN $this->table b ON a.id = b.empresa where b.autorizado = 'RECIBIDA' and b.autorizacion = '' and a.firma is not null";        }
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getAutorizacion($tipo,$documento,$param=array())
    {
        if (isset($param['param1']))
        {
            $bd = new Sis00050Model($this->adapter);
            $dtbd = $bd->getBDCliente($param['param1']);
            $this->table=$dtbd['bd'].'.sis10000';
            switch ($tipo) {
                case 1:
                    $query="SELECT autorizacion FROM $this->table WHERE tipo = 'Factura' and documento = '$documento' order by id desc limit 0,1 ";
                    break;
                case 2:
                    $query="SELECT autorizacion FROM $this->table WHERE tipo = 'Nota de Credito' and documento = '$documento' order by id desc limit 0,1 ";
                    break;
                case 3:
                    $query="SELECT autorizacion FROM $this->table WHERE tipo = 'Comprobante de Retencion' and documento = '$documento' order by id desc limit 0,1 ";
                    break;
            }
        }
        else
        {
            switch ($tipo) {
                case 1:
                    $query="SELECT autorizacion FROM $this->table WHERE tipo = 'Factura' and documento = '$documento' order by id desc limit 0,1 ";
                    break;
                case 2:
                    $query="SELECT autorizacion FROM $this->table WHERE tipo = 'Nota de Credito' and documento = '$documento' order by id desc limit 0,1 ";
                    break;
                case 3:
                    $query="SELECT autorizacion FROM $this->table WHERE tipo = 'Comprobante de Retencion' and documento = '$documento' order by id desc limit 0,1 ";
                    break;
            }
        }
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getDescargarSri($param=array())
    {
        if (isset($param['param1']))
        {
            $this->table=$param['param1'].'.sis10000';
            $this->sis00100=$param['param1'].'.sis00100';
            $query="select b.*,a.ambiente from $this->sis00100 a INNER JOIN $this->table b ON a.id = b.empresa where b.descargada = 0 and b.autorizado <> 'NO AUTORIZADO' and length(b.autorizacion) = '49' ";
        }
        else
        {
            $this->sis00100=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
            $query="select b.*,a.ambiente from $this->sis00100 a INNER JOIN $this->table b ON a.id = b.empresa where b.descargada = 0 and b.autorizado <> 'NO AUTORIZADO' and length(b.autorizacion) = '49' ";
        }
        $result=$this->ejecutarSql($query);
        return $result;
    }
}