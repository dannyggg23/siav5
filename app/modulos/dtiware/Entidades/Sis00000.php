<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-03-27
-- Version:	1.0.{numero de veces que se edita}
****************************************************************/

class Sis00000 extends \EntidadBase
{
	/**
	* @var int
	*/
	private $id;

	/**
	* @var int
	*/
	private $languageid;

	/**
	* @var int
	*/
	private $template_core;

	/**
	* @var int
	*/
	private $template_portal;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $description;

	/**
	* @var string
	*/
	private $telefono;

	/**
	* @var string
	*/
	private $keywords;

	/**
	* @var string
	*/
	private $website_url;

	/**
	* @var string
	*/
	private $logo;

	/**
	* @var string
	*/
	private $icon;

	/**
	* @var string
	*/
	private $info_email;

	/**
	* @var string
	*/
	private $copyright;

	/**
	* @var string
	*/
	private $smtp_hostname;

	/**
	* @var int
	*/
	private $smtp_port;
        
        /**
	* @var string
	*/
	private $smtp_username;
        
        /**
	* @var string
	*/
	private $smtp_password;
        
	public function __construct($adapter) {
		$table='sis00000';
		parent::__construct($table,$adapter);
	}

	public function save(){
		$query="INSERT INTO sis00000(id,languageid,template_core,template_portal,nombre,description,telefono,keywords,website_url,logo,icon,info_email,copyright,smtp_hostname,smtp_port,smtp_username,smtp_password)
				VALUES(NULL,
				'".$this->languageid."',
				'".$this->template_core."',
				'".$this->template_portal."',
				'".$this->nombre."',
				'".$this->description."',
				'".$this->telefono."',
				'".$this->keywords."',
				'".$this->logo."',
				'".$this->icon."',
				'".$this->info_email."',
				'".$this->copyright."',
				'".$this->smtp_hostname."',
				'".$this->smtp_port."',
				'".$this->smtp_username."',
				'".$this->smtp_password."'
			);";
			$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
			return $save;
		}
}