<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 05/05/2017
 * Version: 1.0.1
 *    */

class dti_login {
    
    public $conectar;
    public $adapter;
    private static $login,$ctlVariables;

    function setLogin($datosLogin){
        
        $form = new \dti_form("", "post", "encrypt", 1,true);
        
        $txtusuario = new \dti_form_element();
        $txtusuario->setType("text");
        //$txtusuario->setLabel("SMPT User");
        $txtusuario->setCssclass("form-control");
        $txtusuario->setNameid("txtusuario");
        $txtusuario->setPlaceholder("Ingresar Usuario");

        $form->addelement($txtusuario);

        $txtpass = new \dti_form_element();
        $txtpass->setType("password");
        //$txtpass->setLabel("SMPT Password");
        $txtpass->setCssclass("form-control");
        $txtpass->setNameid("txtpass");
        $txtusuario->setPlaceholder("Ingresar Contraseña");

        $form->addelement($txtpass);

        //Activar Manejo de Empresas
        if ($datosLogin['empresa']) {
            //Traemos las empresas
            $empresas = new Entidades\Dti_empresa($this->adapter);
            $empresas = $empresas->getAllActivo();

            $txtempresa = new \dti_form_element();
            $txtempresa->setType("select");
            //$txtempresa->setLabel("Template Core");
            $txtempresa->setSelect($empresas, "id", "nomempresa");
            $txtempresa->setCssclass("form-control");
            $txtempresa->setNameid("txtempresa");

            $form->addelement($txtempresa);
        }
        
        $btnIngresar = new \dti_form_element();
        $btnIngresar->setType("button");
        $btnIngresar->setOnClick($datosLogin['evento'],$datosLogin['controller'],$datosLogin['accion']);
        //$btnIngresar->setLabel("SMPT Password");
        $btnIngresar->setCssclass("btn btn-lg btn-success btn-block");
        $btnIngresar->setNameid("btnIngresar");
        $btnIngresar->setValue("Ingresar");

        $form->addelement($btnIngresar);

        $form = $form->getForm();
        
        switch ($datosLogin['numlogin']) {
            case '1':
                self::$login = "<div class='container'>
                                <div class='row'>
                                    <div class='col-md-4 col-md-offset-4'>
                                        <div class='panel panel-default'>
                                            <div class='panel-heading'>
                                                <h3 class='panel-title'>Ingresar al Sistema</h3>
                                                <div id='_AJAX_LOGIN_'></div>
                                            </div>
                                            <div class='panel-body'>
                                                ".$form."
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                break;
            case '2':
                self::$login = "<div class='container'>
                                <div class='row'>
                                    <div class='col-md-4 col-md-offset-4'>
                                        <div class='panel panel-default'>
                                            <div class='panel-heading'>
                                                <h3 class='panel-title'>Ingresar al Sistema</h3>
                                                <div id='_AJAX_LOGIN_'></div>
                                            </div>
                                            <div class='panel-body'>
                                                ".$form."
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                break;
        }
    }

    public function __construct() {
        //Conexion a la base de datos
        $this->conectar = new \core\Conectar();
        $this->adapter= $this->conectar->conexion();
        //Cargas Css/Js/Script Obligatorios
        if (!isset(self::$ctlVariables)) {
            $variables = new \dti_core("login1");
            self::$ctlVariables = 0;
        }
    }

    public function getlogin(){
        return self::$login;
    }
}