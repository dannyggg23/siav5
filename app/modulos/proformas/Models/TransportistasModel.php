<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class TransportistasModel extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.guia00000':'guia00000';
        parent::__construct($this->table,$adapter);
    }


    public function insertar($codigo, $razonsocial, $correo, $direccion, $telefono, $celular, $placa, $fcreacion, $suspendido, $usuario, $empresa){
       
        $query="INSERT INTO `guia00000`(`codigo`, `razonsocial`, `correo`, `direccion`, `telefono`, `celular`, `placa`, `fcreacion`, `suspendido`, `usuario`, `empresa`) VALUES 
        ('$codigo', '$razonsocial', '$correo', '$direccion', '$telefono', '$celular', '$placa', '$fcreacion', '$suspendido', '$usuario',  '$empresa')";
        return $this->ejecutarConsulta($query);
    }

  
    public function limpiarCadena($cadena)
    {
        return $this->limpiarCadenaString($cadena);
    }
}