<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class ErrorController extends Controllers
{
    private $conectar,$adapter,$website,$path_inicio;
    
    public function __construct()
    {
        //Conexion a la base de datos
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        //Traemos los datos del portal configurados
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        //Path Inicial
        $this->path_inicio = PATH_ERROR;
        $this->path_salir = PATH_ERROR.'/default/logout';
    }
    
    public function exec()
    {
        $this->show();
    }
    
    /**
    * Método de ejemplo
    */
    public function show()
    {
        $this->render($this->website,__CLASS__,array('path_inicio' => $this->path_inicio,'path_salir' => $this->path_salir));
    }
    
    public function errorCuentaPedido()
    {
        $this->render($this->website,__CLASS__,array('error'=>'Debe configurar la cuenta contable para continuar, comuniquese con su proveedor.','path_inicio' => $this->path_inicio,'path_salir' => $this->path_salir));
    }
}