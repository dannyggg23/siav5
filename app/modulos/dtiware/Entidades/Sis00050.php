<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-22
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00050 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $apellido;

	/**
	* @var string
	*/
	private $correo;

	/**
	* @var string
	*/
	private $usuario;

	/**
	* @var string
	*/
	private $pass;

	/**
	* @var int
	*/
	private $activo;

	/**
	* @var string
	*/
	private $keyreg;

	/**
	* @var string
	*/
	private $keypass;
        
        /**
	* @var string
	*/
	private $bd;
        
	/**
	* @var string
	*/
	private $newpass;

	/**
	* @var int
	*/
        private $adapter;

	public function __construct($adapter) {
		$table='sis00050';
                $this->adapter=$adapter;
		parent::__construct($table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	function getNombre() {
		return $this->nombre;
	}

	function setApellido($apellido) {
		$this->apellido = $apellido;
	}

	function getApellido() {
		return $this->apellido;
	}

	function setCorreo($correo) {
		$this->correo = $correo;
	}

	function getCorreo() {
		return $this->correo;
	}

	function setUsuario($usuario) {
		$this->usuario = $usuario;
	}

	function getUsuario() {
		return $this->usuario;
	}

	function setPass($pass) {
		$this->pass = $pass;
	}

	function getPass() {
		return $this->pass;
	}

	function setActivo($activo) {
		$this->activo = $activo;
	}

	function getActivo() {
		return $this->activo;
	}

	function setKeyreg($keyreg) {
		$this->keyreg = $keyreg;
	}

	function getKeyreg() {
		return $this->keyreg;
	}

	function setKeypass($keypass) {
		$this->keypass = $keypass;
	}

	function getKeypass() {
		return $this->keypass;
	}

	function setNewpass($newpass) {
		$this->newpass = $newpass;
	}

	function getNewpass() {
		return $this->newpass;
	}

        function getBd() {
            return $this->bd;
        }

        function setBd($bd) {
            $this->bd = $bd;
        }

	public function save()
        {
            $query="INSERT INTO sis00050(id,nombre,apellido,correo,usuario,pass,activo,bd,keyreg,keypass,newpass)
                        VALUES(NULL,
                        '".$this->nombre."',
                        '".$this->apellido."',
                        '".$this->correo."',
                        '".$this->usuario."',
                        '".sha1($this->pass)."',
                        '".$this->activo."',
                        'dtierp_".$this->usuario."',
                        '".$this->keyreg."',
                        '".$this->keypass."',
                        '".$this->newpass."'
            );";
            $save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
            //Crear la base de datos
            $querybd = "CREATE DATABASE dtierp_".$this->usuario." CHARACTER SET utf8 COLLATE utf8_spanish_ci;";
            $save=$this->db()->query($querybd) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
            //Corremos Script de Inicio
            $fichero = 'docs/install/0002Empresa.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, "dtierp_".$this->usuario) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
            $temp = ''; // Linea donde vamos montando la sentencia actual
            $comentario_multilinea = false; // Flag para controlar los comentarios multi-linea
            $lineas = file($fichero); // Leemos el fichero SQL al completo
            // Procesamos el fichero linea a linea
            foreach ($lineas as $linea) {
                $linea = trim($linea); // Quitamos espacios/tabuladores por delante y por detrás
                // Si es una linea en blanco o tiene un comentario nos la saltamos
                if ((substr($linea, 0, 2) == '--') or (substr($linea, 0, 1) == '#') or ($linea == '') ) 
                    continue;

                // Saltamos los comentarios multilinea /* texto */ Se detecta cuando empiezan y cuando acaban mediante estos dos ifs  
                if ( substr($linea, 0, 2) == '/*' ) $comentario_multilinea = true;
                if ( $comentario_multilinea ) {
                   if ( (substr($linea, -2, 2) == '*/') or (substr($linea, -3, 3) == '*/;') ) $comentario_multilinea = false;
                   continue;
                }
                // Añadimos la linea actual a la sentencia en la que estamos trabajando 
                $temp .= $linea;
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
            }
            $query="INSERT INTO sis00300(id,nombre,apellido,correo,usuario,pass,activo,bd,keyreg,keypass,newpass)
                        VALUES(NULL,
                        '".$this->nombre."',
                        '".$this->apellido."',
                        '".$this->correo."',
                        '".$this->usuario."',
                        '".sha1($this->pass)."',
                        '".$this->activo."',
                        'dtierp_".$this->usuario."',
                        '".$this->keyreg."',
                        '".$this->keypass."',
                        '".$this->newpass."'
            );";
            mysqli_query($conx, $query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
            /*Usuario Admin*/
            $query="INSERT INTO sis00300(id,nombre,apellido,correo,usuario,pass,activo,bd,keyreg,keypass,newpass)
                        VALUES(NULL,
                        'Gabriel',
                        'Reyes',
                        'greyes@dtiware.com',
                        'dtiware',
                        '".sha1('dti')."',
                        '1',
                        'dtierp_".$this->usuario."',
                        '".$this->keyreg."',
                        '".$this->keypass."',
                        '".$this->newpass."'
            );";
            mysqli_query($conx, $query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
            $query="INSERT INTO sis41000(sis00200id,sis00300id) VALUES(1,1);";
            mysqli_query($conx, $query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
            $query="INSERT INTO sis41000(sis00200id,sis00300id) VALUES(1,2);";
            mysqli_query($conx, $query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
            $conx->close();
            return $save;
        }
    
    /**
    * Activa el web service rest
    */
    public function REST($param=array()){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Allow: GET, POST, OPTIONS, PUT, DELETE");
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
        if (isset($param))
        {
            if ($param=='empresa') {
                $bd = new \Models\Sis00050Model($this->adapter);
                $dtbd = $bd->getFECliente();
                $this->response(200,'Correcto',$dtbd);
            }
            else if ($param['param1']=='login') {
                $bd = new \Models\Sis00050Model($this->adapter);
                $dtbd = $bd->getLogin($param['param2'],sha1($param['param3']));
                if ($dtbd['Existe']>0)
                {
                    $dtbd = $bd->getUserID($param['param2']);
                    $this->response(200,'Correcto',$dtbd);
                }
                else
                {
                    $this->response(200,'Error','Usuario y Clave no son correctos');
                }
            }
            else
            {
                $this->response(400,"Error","No tiene acceso a la información"); 
            }
        }
    }

    private function post()
    {
        try
        {
            $obj = json_decode(file_get_contents('php://input'));
            $objArr = (array)$obj;
            if (empty($objArr))
            {
                $this->response(400,"error","No envia información, revisar el webservice."); 
            }
            else
            {
                if (isset($obj->tipo))
                {
                    if ($obj->tipo == 'Update')
                    {
                        $this->autocommit();
                        
                        $cliente = new \Models\Sis00050Model($this->adapter);
                        $dtcliente = $cliente->getUserID($obj->usuario);
                        
                        if ($dtcliente['id'])
                        {
                            $this->updateMultiColum('nombre', $obj->nombre, 'usuario', $obj->usuario);
                            $this->updateMultiColum('apellido', $obj->apellido, 'usuario', $obj->usuario);
                            $this->updateMultiColum('correo', $obj->correo, 'usuario', $obj->usuario);

                            $this->commit();
                            
                            //REsponder
                            $this->response(200,"success","Correcto");
                        }
                        else
                        {
                            $this->response(400,"error","No Existe el Usuario");
                        }
                    }
                    else if ($obj->tipo == 'clave')
                    {
                        $this->autocommit();
                        
                        $cliente = new \Models\Sis00050Model($this->adapter);
                        $dtcliente = $cliente->getUserID($obj->usuario);
                        
                        if ($dtcliente['id'])
                        {
                            $this->updateMultiColum('pass', sha1($obj->clave), 'usuario', $obj->usuario);

                            $this->commit();
                            
                            //REsponder
                            $this->response(200,"success","Correcto");
                        }
                        else
                        {
                            $this->response(400,"error","No Existe el Usuario");
                        }
                    }
                }
                else
                {
                    $this->response(400,"error","No tiene el formato correcto");
                }
            }
        }
        catch (Exception $ex) {
            $this->response(400,"error",$ex);
        }
    }

    private function put()
    {
    }

    private function delete()
    {
    }
}