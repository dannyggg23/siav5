<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2019-01-09
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis50000Model extends \ModeloBase {

    private $table;

    public function __construct($adapter)
    {
        $this->table='sis50000';
        parent::__construct($this->table,$adapter);
    }

    public function getComboBox($padre = '')
    {
        $query="";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    
    public function getExistUsuario($usuario,$bd)
    {
        $query="select count(id) as numrows from $this->table where usuario = '$usuario' and bd = '$bd';";
        $result=$this->ejecutarSql($query);
        if ($result['numrows']>0)
        {
            $fecha_actual = date('Y-m-d H:i:s');
            $query="select count(id) as numrows from $this->table where usuario = '$usuario' and bd = '$bd' and timestampdiff(MINUTE, fecha_actividad, '$fecha_actual') > 20 ;";
            $result=$this->ejecutarSql($query);
            if ($result['numrows']>0)
            {
                $query="DELETE FROM $this->table WHERE usuario = '$usuario' and bd = '$bd';";
                $this->ejecutarCRUD($query);
                $result['numrows'] = 0;
                return $result;
            }
            else
            {
                $result['numrows'] = 1;
                return $result;
            }
        }
        else
        {
            return $result;
        }
    }
    
    public function getSessionActiva($usuario,$bd,$con)
    {
        $query="select count(id) as numrows from $this->table where usuario = '$usuario' and bd = '$bd' and con='$con';";
        $result=$this->ejecutarSql($query);
        return $result;
    }

}