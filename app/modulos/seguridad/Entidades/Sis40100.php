<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40100 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $template;

	/**
	* @var string
	*/
	private $file_name;

	/**
	* @var string
	*/
	private $css_name;

	/**
	* @var string
	*/
	private $image;
        
        private $table;
        
	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40100':'sis40100';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setTemplate($template) {
		$this->template = $template;
	}

	function getTemplate() {
		return $this->template;
	}

	function setFile_name($file_name) {
		$this->file_name = $file_name;
	}

	function getFile_name() {
		return $this->file_name;
	}

	function setCss_name($css_name) {
		$this->css_name = $css_name;
	}

	function getCss_name() {
		return $this->css_name;
	}

	function setImage($image) {
		$this->image = $image;
	}

	function getImage() {
		return $this->image;
	}

	public function save(){
		$query="INSERT INTO $this->table(id,template,file_name,css_name,image)
			VALUES(NULL,
			'".$this->template."',
			'".$this->file_name."',
			'".$this->css_name."',
			'".$this->image."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}