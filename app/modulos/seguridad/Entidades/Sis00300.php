<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-04-23
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis00300 extends \EntidadBase {
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
    private $newpass;

    /**
    * @var string
    */
    private $bd;
    
    private $table;
    private $adapter;
    private $descuento;
    private $editprecio;

    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis00300':'sis00300';
        $this->adapter=$adapter;
        parent::__construct($this->table,$adapter);
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
    
    function getDescuento() {
        return $this->descuento;
    }

    function setDescuento($descuento) {
        $this->descuento = $descuento;
    }
    
    function getEditprecio() {
        return $this->editprecio;
    }

    function setEditprecio($editprecio) {
        $this->editprecio = $editprecio;
    }

    public function save(){
            $query="INSERT INTO $this->table(id,nombre,apellido,correo,usuario,pass,activo,keyreg,keypass,newpass,descuento,editprecio,bd)
                    VALUES(NULL,
                    '".$this->nombre."',
                    '".$this->apellido."',
                    '".$this->correo."',
                    '".$this->usuario."',
                    '".$this->pass."',
                    '".$this->activo."',
                    '".$this->keyreg."',
                    '".$this->keypass."',
                    '".$this->newpass."',
                    '".$this->descuento."',
                    '".$this->editprecio."',
                    '".$this->bd."'
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
            $this->post($param);
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
            if ($param['param1']=='login') {
                $bd = new \Models\sis00300Model($this->adapter);
                $dtbd = $bd->getLogin($param['param2'],$param['param3'],$param);
                $this->response(200,'Correcto',$dtbd);
            }
        }
    }

    private function post($param=array())
    {
    }

    private function put()
    {
    }

    private function delete()
    {
    }
}