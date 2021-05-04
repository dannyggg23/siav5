<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis40130 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $tipo;

	/**
	* @var string
	*/
	private $nameid;

	/**
	* @var string
	*/
	private $bdd;

	/**
	* @var string
	*/
	private $titulo;

	/**
	* @var string
	*/
	private $valor;

	/**
	* @var string
	*/
	private $placeholder;

	/**
	* @var string
	*/
	private $controller;

	/**
	* @var int
	*/
	private $requerido;

	/**
	* @var string
	*/
	private $mascara;

	/**
	* @var string
	*/
	private $errortext;

	/**
	* @var string
	*/
	private $errorcontrol;

	/**
	* @var string
	*/
	private $css;

	/**
	* @var int
	*/
	private $readonly;

	/**
	* @var int
	*/
	private $readonlyid;

	/**
	* @var int
	*/
	private $readonlycrud;

	/**
	* @var string
	*/
	private $icono;

	/**
	* @var string
	*/
	private $combobox;

	/**
	* @var string
	*/
	private $modal;

	/**
	* @var string
	*/
	private $accion;

	/**
	* @var string
	*/
	private $nomparam;

	/**
	* @var string
	*/
	private $valparam;

	/**
	* @var string
	*/
	private $nomparam2;

	/**
	* @var string
	*/
	private $valparam2;

	/**
	* @var string
	*/
	private $nomparam3;

	/**
	* @var string
	*/
	private $valparam3;

	/**
	* @var string
	*/
	private $linkbutton;

	/**
	* @var string
	*/
	private $linktipo;

	/**
	* @var string
	*/
	private $linkparametro;

	/**
	* @var int
	*/
	private $minlegth;

	/**
	* @var int
	*/
	private $maxlegth;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var int
	*/
	private $orden;

	/**
	* @var string
	*/
	private $idform;
        private $mayusculas;
        
        private $table;
        
	public function __construct($adapter) {
		$this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis40130':'sis40130';
		parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	function getTipo() {
		return $this->tipo;
	}

	function setNameid($nameid) {
		$this->nameid = $nameid;
	}

	function getNameid() {
		return $this->nameid;
	}

	function setBdd($bdd) {
		$this->bdd = $bdd;
	}

	function getBdd() {
		return $this->bdd;
	}

	function setTitulo($titulo) {
		$this->titulo = $titulo;
	}

	function getTitulo() {
		return $this->titulo;
	}

	function setValor($valor) {
		$this->valor = $valor;
	}

	function getValor() {
		return $this->valor;
	}

	function setPlaceholder($placeholder) {
		$this->placeholder = $placeholder;
	}

	function getPlaceholder() {
		return $this->placeholder;
	}

	function setController($controller) {
		$this->controller = $controller;
	}

	function getController() {
		return $this->controller;
	}

	function setRequerido($requerido) {
		$this->requerido = $requerido;
	}

	function getRequerido() {
		return $this->requerido;
	}

	function setMascara($mascara) {
		$this->mascara = $mascara;
	}

	function getMascara() {
		return $this->mascara;
	}

	function setErrortext($errortext) {
		$this->errortext = $errortext;
	}

	function getErrortext() {
		return $this->errortext;
	}

	function setErrorcontrol($errorcontrol) {
		$this->errorcontrol = $errorcontrol;
	}

	function getErrorcontrol() {
		return $this->errorcontrol;
	}

	function setCss($css) {
		$this->css = $css;
	}

	function getCss() {
		return $this->css;
	}

	function setReadonly($readonly) {
		$this->readonly = $readonly;
	}

	function getReadonly() {
		return $this->readonly;
	}

	function setReadonlyid($readonlyid) {
		$this->readonlyid = $readonlyid;
	}

	function getReadonlyid() {
		return $this->readonlyid;
	}

	function setReadonlycrud($readonlycrud) {
		$this->readonlycrud = $readonlycrud;
	}

	function getReadonlycrud() {
		return $this->readonlycrud;
	}

	function setIcono($icono) {
		$this->icono = $icono;
	}

	function getIcono() {
		return $this->icono;
	}

	function setCombobox($combobox) {
		$this->combobox = $combobox;
	}

	function getCombobox() {
		return $this->combobox;
	}

	function setModal($modal) {
		$this->modal = $modal;
	}

	function getModal() {
		return $this->modal;
	}

	function setAccion($accion) {
		$this->accion = $accion;
	}

	function getAccion() {
		return $this->accion;
	}

	function setNomparam($nomparam) {
		$this->nomparam = $nomparam;
	}

	function getNomparam() {
		return $this->nomparam;
	}

	function setValparam($valparam) {
		$this->valparam = $valparam;
	}

	function getValparam() {
		return $this->valparam;
	}

	function setNomparam2($nomparam2) {
		$this->nomparam2 = $nomparam2;
	}

	function getNomparam2() {
		return $this->nomparam2;
	}

	function setValparam2($valparam2) {
		$this->valparam2 = $valparam2;
	}

	function getValparam2() {
		return $this->valparam2;
	}

	function setNomparam3($nomparam3) {
		$this->nomparam3 = $nomparam3;
	}

	function getNomparam3() {
		return $this->nomparam3;
	}

	function setValparam3($valparam3) {
		$this->valparam3 = $valparam3;
	}

	function getValparam3() {
		return $this->valparam3;
	}

	function setLinkbutton($linkbutton) {
		$this->linkbutton = $linkbutton;
	}

	function getLinkbutton() {
		return $this->linkbutton;
	}

	function setLinktipo($linktipo) {
		$this->linktipo = $linktipo;
	}

	function getLinktipo() {
		return $this->linktipo;
	}

	function setLinkparametro($linkparametro) {
		$this->linkparametro = $linkparametro;
	}

	function getLinkparametro() {
		return $this->linkparametro;
	}

	function setMinlegth($minlegth) {
		$this->minlegth = $minlegth;
	}

	function getMinlegth() {
		return $this->minlegth;
	}

	function setMaxlegth($maxlegth) {
		$this->maxlegth = $maxlegth;
	}

	function getMaxlegth() {
		return $this->maxlegth;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	function setOrden($orden) {
		$this->orden = $orden;
	}

	function getOrden() {
		return $this->orden;
	}

	function setIdform($idform) {
		$this->idform = $idform;
	}

	function getIdform() {
		return $this->idform;
	}
        
        function getMayusculas() {
            return $this->mayusculas;
        }

        function setMayusculas($mayusculas) {
            $this->mayusculas = $mayusculas;
        }
        
	public function save(){
		$query="INSERT INTO $this->table(id,tipo,nameid,bdd,titulo,valor,placeholder,controller,requerido,mascara,errortext,errorcontrol,css,readonly,readonlyid,readonlycrud,icono,combobox,modal,accion,nomparam,valparam,nomparam2,valparam2,nomparam3,valparam3,linkbutton,linktipo,linkparametro,minlegth,maxlegth,activo,mayusculas,orden,idform)
			VALUES(NULL,
			'".$this->tipo."',
			'".$this->nameid."',
			'".$this->bdd."',
			'".$this->titulo."',
			'".$this->valor."',
			'".$this->placeholder."',
			'".$this->controller."',
			'".$this->requerido."',
			'".$this->mascara."',
			'".$this->errortext."',
			'".$this->errorcontrol."',
			'".$this->css."',
			'".$this->readonly."',
			'".$this->readonlyid."',
			'".$this->readonlycrud."',
			'".$this->icono."',
			'".$this->combobox."',
			'".$this->modal."',
			'".$this->accion."',
			'".$this->nomparam."',
			'".$this->valparam."',
			'".$this->nomparam2."',
			'".$this->valparam2."',
			'".$this->nomparam3."',
			'".$this->valparam3."',
			'".$this->linkbutton."',
			'".$this->linktipo."',
			'".$this->linkparametro."',
			'".$this->minlegth."',
			'".$this->maxlegth."',
			'".$this->activo."',
                        '".$this->mayusculas."',
			'".$this->orden."',
			'".$this->idform."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
}