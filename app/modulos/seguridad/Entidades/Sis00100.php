<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00100 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var int
	*/
	private $languageid;

	/**
	* @var int
	*/
	private $template;

	/**
	* @var string
	*/
	private $razonsocial;

	/**
	* @var string
	*/
	private $nomempresa;

	/**
	* @var string
	*/
	private $ruc;

	/**
	* @var string
	*/
	private $representante;

	/**
	* @var string
	*/
	private $rucrepresentante;

	/**
	* @var string
	*/
	private $telefono;

	/**
	* @var string
	*/
	private $direccion;

	/**
	* @var string
	*/
	private $obligaconta;

	/**
	* @var string
	*/
	private $contriespecial;

	/**
	* @var string
	*/
	private $valcontriespecial;

	/**
	* @var int
	*/
	private $ambiente;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var string
	*/
	private $logo;

	/**
	* @var string
	*/
	private $firma;

	/**
	* @var string
	*/
	private $clavefirma;

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

	/**
	* @var int
	*/
	private $zona_horaria;

	/**
	* @var int
	*/
	private $moneda;

	/**
	* @var string
	*/
	private $caracter_decimal;

	/**
	* @var string
	*/
	private $caracter_miles;

	/**
	* @var int
	*/
	private $segmentos_cuentas;

	/**
	* @var string
	*/
	private $separador_segmentos;

	/**
	* @var int
	*/
	private $decimales_ventas;

	/**
	* @var int
	*/
	private $decimales_compras;
        
        /**
	* @var int
	*/
        private $smtpdefecto;
        
        /**
	* @var string
	*/
	private $correo;
        private $bd;
        private $imgfondo;
        private $minilogo;
        
        private $table;
        
	public function __construct($adapter,$param=array(),$bd='') {
            if (isset($param['param1']))
            {
                $this->table=$param['param1'].'.sis00100';
            }
            else if (strlen($bd)>0)
            {
                $this->table=$bd.'.sis00100';
            }
            else
            {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00100':'sis00100';
            }
            parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setLanguageid($languageid) {
		$this->languageid = $languageid;
	}

	function getLanguageid() {
		return $this->languageid;
	}

	function setTemplate($template) {
		$this->template = $template;
	}

	function getTemplate() {
		return $this->template;
	}

	function setRazonsocial($razonsocial) {
		$this->razonsocial = $razonsocial;
	}

	function getRazonsocial() {
		return $this->razonsocial;
	}

	function setNomempresa($nomempresa) {
		$this->nomempresa = $nomempresa;
	}

	function getNomempresa() {
		return $this->nomempresa;
	}

	function setRuc($ruc) {
		$this->ruc = $ruc;
	}

	function getRuc() {
		return $this->ruc;
	}

	function setRepresentante($representante) {
		$this->representante = $representante;
	}

	function getRepresentante() {
		return $this->representante;
	}

	function setRucrepresentante($rucrepresentante) {
		$this->rucrepresentante = $rucrepresentante;
	}

	function getRucrepresentante() {
		return $this->rucrepresentante;
	}

	function setTelefono($telefono) {
		$this->telefono = $telefono;
	}

	function getTelefono() {
		return $this->telefono;
	}

	function setDireccion($direccion) {
		$this->direccion = $direccion;
	}

	function getDireccion() {
		return $this->direccion;
	}

	function setObligaconta($obligaconta) {
		$this->obligaconta = $obligaconta;
	}

	function getObligaconta() {
		return $this->obligaconta;
	}

	function setContriespecial($contriespecial) {
		$this->contriespecial = $contriespecial;
	}

	function getContriespecial() {
		return $this->contriespecial;
	}

	function setValcontriespecial($valcontriespecial) {
		$this->valcontriespecial = $valcontriespecial;
	}

	function getValcontriespecial() {
		return $this->valcontriespecial;
	}

	function setAmbiente($ambiente) {
		$this->ambiente = $ambiente;
	}

	function getAmbiente() {
		return $this->ambiente;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	function setLogo($logo) {
		$this->logo = $logo;
	}

	function getLogo() {
		return $this->logo;
	}

	function setFirma($firma) {
		$this->firma = $firma;
	}

	function getFirma() {
		return $this->firma;
	}

	function setClavefirma($clavefirma) {
		$this->clavefirma = $clavefirma;
	}

	function getClavefirma() {
		return $this->clavefirma;
	}

	function setSmtp_hostname($smtp_hostname) {
		$this->smtp_hostname = $smtp_hostname;
	}

	function getSmtp_hostname() {
		return $this->smtp_hostname;
	}

	function setSmtp_port($smtp_port) {
		$this->smtp_port = $smtp_port;
	}

	function getSmtp_port() {
		return $this->smtp_port;
	}

	function setSmtp_username($smtp_username) {
		$this->smtp_username = $smtp_username;
	}

	function getSmtp_username() {
		return $this->smtp_username;
	}

	function setSmtp_password($smtp_password) {
		$this->smtp_password = $smtp_password;
	}

	function getSmtp_password() {
		return $this->smtp_password;
	}

	function setZona_horaria($zona_horaria) {
		$this->zona_horaria = $zona_horaria;
	}

	function getZona_horaria() {
		return $this->zona_horaria;
	}

	function setMoneda($moneda) {
		$this->moneda = $moneda;
	}

	function getMoneda() {
		return $this->moneda;
	}

	function setCaracter_decimal($caracter_decimal) {
		$this->caracter_decimal = $caracter_decimal;
	}

	function getCaracter_decimal() {
		return $this->caracter_decimal;
	}

	function setCaracter_miles($caracter_miles) {
		$this->caracter_miles = $caracter_miles;
	}

	function getCaracter_miles() {
		return $this->caracter_miles;
	}

	function setSegmentos_cuentas($segmentos_cuentas) {
		$this->segmentos_cuentas = $segmentos_cuentas;
	}

	function getSegmentos_cuentas() {
		return $this->segmentos_cuentas;
	}

	function setSeparador_segmentos($separador_segmentos) {
		$this->separador_segmentos = $separador_segmentos;
	}

	function getSeparador_segmentos() {
		return $this->separador_segmentos;
	}

	function setDecimales_ventas($decimales_ventas) {
		$this->decimales_ventas = $decimales_ventas;
	}

	function getDecimales_ventas() {
		return $this->decimales_ventas;
	}

	function setDecimales_compras($decimales_compras) {
		$this->decimales_compras = $decimales_compras;
	}

	function getDecimales_compras() {
		return $this->decimales_compras;
	}
        
        function getSmtpdefecto() {
            return $this->smtpdefecto;
        }

        function setSmtpdefecto($smtpdefecto) {
            $this->smtpdefecto = $smtpdefecto;
        }

        function getCorreo() {
            return $this->correo;
        }

        function setCorreo($correo) {
            $this->correo = $correo;
        }
        
        function getBd() {
            return $this->bd;
        }

        function setBd($bd) {
            $this->bd = $bd;
        }
        
        function getImgfondo() {
            return $this->imgfondo;
        }

        function setImgfondo($imgfondo) {
            $this->imgfondo = $imgfondo;
        }
        
        function getMinilogo() {
            return $this->minilogo;
        }

        function setMinilogo($minilogo) {
            $this->minilogo = $minilogo;
        }
        
	public function save()
        {
		$query="INSERT INTO $this->table (id,languageid,template,razonsocial,nomempresa,ruc,representante,rucrepresentante,telefono,direccion,obligaconta,contriespecial,valcontriespecial,ambiente,activo,imgfondo,logo,minilogo,firma,clavefirma,smtp_hostname,smtp_port,smtp_username,smtp_password,smtpdefecto,zona_horaria,moneda,caracter_decimal,caracter_miles,segmentos_cuentas,separador_segmentos,decimales_ventas,decimales_compras,bd,correo)
			VALUES(NULL,
			'".$this->languageid."',
			'".$this->template."',
			'".$this->razonsocial."',
			'".$this->nomempresa."',
			'".$this->ruc."',
			'".$this->representante."',
			'".$this->rucrepresentante."',
			'".$this->telefono."',
			'".$this->direccion."',
			'".$this->obligaconta."',
			'".$this->contriespecial."',
			'".$this->valcontriespecial."',
			'".$this->ambiente."',
			'".$this->activo."',
                        '".$this->imgfondo."',
			'".$this->logo."',
                        '".$this->minilogo."',
			'".$this->firma."',
			'".$this->clavefirma."',
			'".$this->smtp_hostname."',
			'".$this->smtp_port."',
			'".$this->smtp_username."',
			'".$this->smtp_password."',
                        '".$this->smtpdefecto."',
			'".$this->zona_horaria."',
			'".$this->moneda."',
			'".$this->caracter_decimal."',
			'".$this->caracter_miles."',
			'".$this->segmentos_cuentas."',
			'".$this->separador_segmentos."',
			'".$this->decimales_ventas."',
			'".$this->decimales_compras."',
                        '".$this->bd."',
                        '".$this->correo."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
        
        /**
        * Activa el web service rest
        */
        public function REST($param=array()){
            header('Content-Type: application/json');
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($method) {
            case 'GET'://consulta
                $this->get($param);
                //echo 'GET';
                break;
            case 'POST'://inserta
                $this->post();
                //echo 'POST';
                break;
            case 'PUT'://actualiza
                $this->put();
                //echo 'PUT';
                break;
            case 'DELETE'://elimina
                $this->delete();
                //echo 'DELETE';
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
            }
        }

        /**
        * Respuesta al cliente
        * @param int $code Codigo de respuesta HTTP
        * @param String $status indica el estado de la respuesta puede ser "success" o "error"
        * @param String $message Descripcion de lo ocurrido
        */
        private function response($code=200, $status="", $message="") {
           http_response_code($code);
           if( !empty($status) && !empty($message) ){
               $response = array("status" => $status ,"message"=>$message);
               echo json_encode($response,JSON_PRETTY_PRINT);
           }
        }

        /**
        * función que segun el valor de "action" e "id":
        *  - mostrara una array con todos los registros de personas
        *  - mostrara un solo registro 
        *  - mostrara un array vacio
        */
        private function get($param=array())
        {   
            if (isset($param['param1']))
            {
                $documento = new \Models\Sis10000Model($this->adapter);
                $bd = new \Models\Sis00050Model($this->adapter);
                $dtbd = $bd->getCountBDCliente($param['param1']);
                if ($dtbd['numrows']>0)
                {
                    if($param['param2']=='fe_documentos' && $param['param3']!='')
                    {
                        $response = $documento->getAutorizacion($param['param3'],$param['param4'],$param);
                        if (is_array($response)) {
                            $this->response(200,'Correcto',$response);
                        }else{
                            $this->response(400,'Error',$response);
                        }
                    }
                    else if($param['param2']=='fe_documentos')
                    {
                        $response = $documento->getFirmar($param);
                        if (is_array($response)) {
                            $this->response(200,'Correcto',$response);
                        }else{
                            $this->response(400,'Error',$response);
                        }
                    }
                    else
                    {
                        $this->response(400,'Error','Datos Erroneos, comunicarse con el soporte@dtiware.com');
                    }
                }
                else
                {
                    $this->response(400,'Error','Credenciales incorrectas, comunicarse con el soporte@dtiware.com');
                }
            }
        }

        private function post()
        {
        }

        private function put()
        {
        }

        private function delete()
        {
        }
}