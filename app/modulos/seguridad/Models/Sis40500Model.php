<?php

namespace Models;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-11-16
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40500Model extends \ModeloBase {

	private $table;

	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40500':'sis40500';
		parent::__construct($this->table,$adapter);
	}

	public function getComboBox($padre = '') {
		$query="";
		$result=$this->ejecutarSql($query);
		return $result;
	}

}