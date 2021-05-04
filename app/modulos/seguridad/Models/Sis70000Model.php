<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-24
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis70000Model extends \ModeloBase {

    private $table;

    public function __construct($adapter) {
            $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis70000':'sis70000';
            parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '') {
            $query="";
            $result=$this->ejecutarSql($query);
            return $result;
    }
    
    public function getCorreosAll($finicio,$ffin,$cliente)
    {
        $this->sis00100=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
        $this->cc00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cc00000':'cc00000';
        $this->cp00000=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.cp00000':'cp00000';
        $query="select z.*,x.ambiente from $this->sis00100 x INNER JOIN $this->table z ON x.id = z.empresa where cast(z.fecha_envio as date) BETWEEN '$finicio' and '$ffin' and z.empresa = '".$_SESSION['empresa']."' and (z.modulo like '%".$cliente."%' or z.documento like '%".$cliente."%') and tipo = 'Factura'
                UNION ALL
                select z.*,x.ambiente from $this->sis00100 x INNER JOIN $this->table z ON x.id = z.empresa where cast(z.fecha_envio as date) BETWEEN '$finicio' and '$ffin' and z.empresa = '".$_SESSION['empresa']."' and (z.modulo like '%".$cliente."%' or z.documento like '%".$cliente."%') and tipo = 'Nota de Credito'
                UNION ALL
                select z.*,x.ambiente from $this->sis00100 x INNER JOIN $this->table z ON x.id = z.empresa where cast(z.fecha_envio as date) BETWEEN '$finicio' and '$ffin' and z.empresa = '".$_SESSION['empresa']."' and (z.modulo like '%".$cliente."%' or z.documento like '%".$cliente."%') and tipo = 'Comprobante de Retencion' ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}