<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 12/04/2018
 * Version: 3.0.0
 *    */

class dti_layout {
    
    private $website;
    private $conectar;
    private $adapter;
    public static $css = "";
    public static $js = "";
    public static $script = "";
    public static $ctlVariables;
    
    public function __construct($website)
    {
        $this->website = $website;
        //Conexion
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        /*if (!isset(self::$ctlVariables)) {
            //Cargas Css/Js/Script Obligatorios
            $variables = new \dti_core($this->website["template_portal"]);
            self::$ctlVariables = 0;
        }*/
    }
    
    /**
     * Templates de Login
     * @param array $datos (version/controller/accion)
     * @return string html
     */
    public function loginAction($datos=array())
    {
        if (isset($datos['version'])) {
            switch ($datos['version']) {
                case "1":
                    $loginAction = "<div class='container'>
                                        <div class='row'>
                                            <div class='col-md-4 col-md-offset-4'>
                                                <div class='panel panel-default'>
                                                    <div class='panel-heading'>
                                                        <h3 class='panel-title'>Ingresar al Sistema</h3>
                                                        <div id='_AJAX_LOGIN_'></div>
                                                    </div>
                                                    <div class='panel-body'>
                                                        <form accept-charset='UTF-8' role='form'>
                                                        <fieldset>
                                                            <div class='form-group'>
                                                                <input class='form-control' placeholder='Ingrese Usuario' id='txtusuario' name='txtusuario' type='text' required='required'>
                                                            </div>
                                                            <div class='form-group'>
                                                                <input class='form-control' placeholder='Ingrese Contraseña' id='txtpass' name='txtpass' type='password' value='' required='required'>
                                                            </div>
                                                            <button class='btn btn-lg btn-success btn-block' onclick='goIngresar(\"".$datos['controller']."\",\"".$datos['accion']."\")'><span>Ingresar</span></button>
                                                        </fieldset>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                    dti_core::set('css', '<link href="public/css/componentes/login/login1.css" rel="stylesheet" type="text/css"/>');
                    dti_core::set('script', '<script src="public/js/componentes/login/login.js" type="text/javascript"></script>');
                    break;
                case "2":
                    $loginAction = "<div class='container'>
                                    <div class='row'>
                            <!-- Mixins-->
                            <br>
                            <div class='container'>
                              <div class=''></div>
                              <div class='card'>
                                <h1 class='title'>Sistema Mies</h1>
                                <div id='_AJAX_LOGIN_'></div>
                                  <div class='input-container'>
                                    <input type='text' id='txtusuario' name='txtusuario' required='required'/>
                                    <label for='txtusuario'>Usuario</label>
                                    <div class='bar'></div>
                                  </div>
                                  <div class='input-container'>
                                    <input type='password' id='txtpass' name='txtpass' required='required'/>
                                    <label for='txtpass'>Contraseña</label>
                                    <div class='bar'></div>
                                  </div>
                                  <div class='button-container'>
                                    <button onclick='goIngresar(\"".$datos['controller']."\",\"".$datos['accion']."\")'><span>Ingresar</span></button>
                                  </div>
                                  <div class='footer'><a class='toggle'>Olvide Contraseña?</a></div>
                              </div>
                              <div class='card alt'>
                                <div class='toggle'></div>
                                    <h1 class='title'>Recuperar Contraseña
                                    <div id='_AJAX_LOSTPASS_'></div>
                                    <div class='close'></div>
                                </h1>
                                  <div class='input-container'>
                                    <input type='text' id='txtcorreo' name='txtcorreo' required='required'/>
                                    <label for='Correo'>Correo</label>
                                    <div class='bar'></div>
                                  </div>
                                  <div class='button-container'>
                                    <button onclick='goRecuperarClave()'><span>Recuperar</span></button>
                                  </div>
                              </div>
                            </div>
                                    </div>
                            </div>";
                    dti_core::set('css', '<link href="public/css/componentes/login/login2.css" rel="stylesheet" type="text/css"/>');
                    dti_core::set('script', '<script src="public/js/componentes/login/login.js" type="text/javascript"></script>');
                    break;
                case "3":
                    $loginAction = '<div class="main-agileinfo slider ">
                                        <div class="items-group">
                                                <div class="item agileits-w3layouts">
                                                        <div class="block text main-agileits">
                                                                <span class="circleLight"></span>
                                                                <!-- login form -->
                                                                <div class="login-form loginw3-agile">
                                                                        <div class="agile-row">
                                                                                <img src="'.$this->website['logo'].'" width="150px" alt="">
                                                                                <div id="_AJAX_LOGIN_"></div>
                                                                                <div class="login-agileits-top">
                                                                                    <p>Usuario: </p>
                                                                                    <input type="text" class="name" id="txtusuario" name="txtusuario" required=""/>
                                                                                    <p>Contraseña: </p>
                                                                                    <input type="password" class="password" id="txtpass" name="txtpass" required=""/>  
                                                                                    <label class="anim">
                                                                                    </label>
                                                                                    <button id="btn_login" class="btn-login3" onclick=\'goIngresar("'.$datos['controller'].'","'.$datos['accion'].'")\'><span>Ingresar</span></button>
                                                                                </div>
                                                                                <div class="login-agileits-bottom wthree">
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <!--<div class="w3lsfooteragileits">
                                                                <p> &copy; '. date('Y',time()) .' '.$this->website["nombre"].'</p>
                                                        </div>-->
                                                </div>
                                        </div>
                                </div>';
                    dti_core::set('css', '<link href="public/css/componentes/login/login3.css" rel="stylesheet" type="text/css"/>');
                    dti_core::set('script', '<script src="public/js/componentes/login/login.js" type="text/javascript"></script>');
                    break;
                case "4":
                    $loginAction = '<div class="limiter">
                                        <div class="container-login100">
                                            <div class="wrap-login100">
                                                <div class="login100-pic js-tilt" data-tilt>
                                                    <img src="'.$datos['imgLogo'].'" alt="Sin Logo">
                                                </div>
                                                <div class="img">
                                                    <img src="'.$datos['miniLogo'].'" alt="Sin Logo">
                                                </div>

                                                <div class="login100-form validate-form">
                                                    <span class="login100-form-title">
                                                            Ingresar
                                                    </span>

                                                    <div class="wrap-input100 validate-input" data-validate = "Ingrese un usuario válido">
                                                            <input class="input100" type="text" id="txtusuario" name="txtusuario" placeholder="Usuario">
                                                            <span class="focus-input100"></span>
                                                            <span class="symbol-input100">
                                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                            </span>
                                                    </div>

                                                    <div class="wrap-input100 validate-input" data-validate = "Ingrese una contraseña válida">
                                                            <input class="input100" type="password" id="txtpass" name="txtpass" placeholder="Contraseña">
                                                            <span class="focus-input100"></span>
                                                            <span class="symbol-input100">
                                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                            </span>
                                                    </div>

                                                    <div class="container-login100-form-btn">
                                                            <button id="btn_login" class="login100-form-btn" onclick=\'goIngresar("'.$datos['controller'].'","'.$datos['accion'].'")\'>
                                                                <a>INGRESAR</a>
                                                            </button>
                                                    </div>

                                                    <div class="text-center p-t-12">
                                                            <span class="txt1">
                                                                    Olvidó su
                                                            </span>
                                                            <a class="txt2" href="#">
                                                                    Usuario / Contraseña?
                                                            </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <style>
                                    .container-login100 {
                                        background: url('.$datos['imgFondo'].')repeat 0px 0px;
                                        background-position: center center;
                                      -webkit-background-size: cover;
                                      -moz-background-size: cover;
                                      -o-background-size: cover;
                                      background-size: cover;
                                    }
                                    </style>';
                    dti_core::set('css', '<link href="public/css/componentes/login/login4.css" rel="stylesheet" type="text/css"/>');
                    dti_core::set('css', '<link href="public/css/componentes/login/login4_media.css" rel="stylesheet" type="text/css"/>');
                    dti_core::set('script', '<script src="public/js/componentes/login/login.js" type="text/javascript"></script>');
                    dti_core::set('script', '<script src="public/js/componentes/tilt/tilt.jquery.min.js" type="text/javascript"></script>');
                    break;
                case "5":
                    $loginAction = '<div class="main-wrapper">
                    <div class="preloader">
                        <div class="lds-ripple">
                            <div class="lds-pos"></div>
                            <div class="lds-pos"></div>
                        </div>
                    </div>
                    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url('.$datos['imgFondo'].') no-repeat left center;">
                        <div class="auth-box on-sidebar">
                            <div id="loginform">
                                <div class="logo">
                                    <span class="db"><img src="'.$datos['imgLogo'].'" alt="logo" /></span>
                                    <h5 class="font-medium m-b-20">Bienvenidos</h5>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-horizontal m-t-20" >
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                                </div>
                                                <input type="text" id="txtusuario" name="txtusuario" class="form-control form-control-lg" placeholder="Usuario" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                                </div>
                                                <input type="password" id="txtpass" name="txtpass" class="form-control form-control-lg" placeholder="Contraseña" aria-label="Password" aria-describedby="basic-addon1">
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <!--<div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                        <label class="custom-control-label" for="customCheck1">Remember me</label>
                                                        <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a>
                                                    </div>-->
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <div class="col-xs-12 p-b-20">
                                                    <button id="btn_login" class="btn btn-block btn-lg btn-danger" onclick=\'goIngresar("'.$datos['controller'].'","'.$datos['accion'].'")\'>Ingresar</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                                                    <div class="social">
                                                        <!--<a href="javascript:void(0)" class="btn  btn-facebook" data-toggle="tooltip" title="" data-original-title="Login with Facebook"> <i aria-hidden="true" class="fab  fa-facebook"></i> </a>
                                                        <a href="javascript:void(0)" class="btn btn-googleplus" data-toggle="tooltip" title="" data-original-title="Login with Google"> <i aria-hidden="true" class="fab  fa-google-plus"></i> </a>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group m-b-0 m-t-10">
                                                <div class="col-sm-12 text-center">
                                                    <!--No tienes una cuenta? <a href="authentication-register1.html" class="text-info m-l-5"><b>Sign Up</b></a>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="recoverform">
                                <div class="logo">
                                    <span class="db"><img src="public/logos/logo-allpats.png" alt="logo" /></span>
                                    <h5 class="font-medium m-b-20">Recover Password</h5>
                                    <span>Enter your Email and instructions will be sent to you!</span>
                                </div>
                                <div class="row m-t-20">
                                    <form class="col-12" action="index.html">
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input class="form-control form-control-lg" type="email" required="" placeholder="Username">
                                            </div>
                                        </div>
                                        <div class="row m-t-20">
                                            <div class="col-12">
                                                <button class="btn btn-block btn-lg btn-danger"  name="action">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
              

               
               // dti_core::set('script', '<script src="public/js/componentes/xtremetheme/bootstrap.min.js" type="text/javascript"></script>');
                dti_core::set('script', '<script src="public/js/componentes/xtremetheme/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>');
                // dti_core::set('script', '<script src="public/js/componentes/xtremetheme/sparkline.js" type="text/javascript"></script>');
                dti_core::set('script', '<script src="public/js/componentes/login/login.js" type="text/javascript"></script>');
                dti_core::set('script', '<script src="public/js/componentes/tilt/tilt.jquery.min.js" type="text/javascript"></script>');
                dti_core::set('css', '<link href="resources/template/template6/style.css" rel="stylesheet" type="text/css"/>');
               // dti_core::set('script', '<script src="public/js/componentes/xtremetheme/popper.min.js" type="text/javascript"></script>');
                
                    break;
            }
        }else{
            $loginAction = '';
        }

        return $loginAction;
    }
    
    public function empresaAction($param=array())
    {
        switch ($param['version']) {
            case "1":
                $loginAction = "<div class='container'>
                                    <div class='row'>
                                        <div class='col-md-4 col-md-offset-4'>
                                            <div class='panel panel-default'>
                                                <div class='panel-heading'>
                                                    <h3 class='panel-title'>Ingresar al Sistema</h3>
                                                    <div id='_AJAX_LOGIN_'></div>
                                                </div>
                                                <div class='panel-body'>
                                                    <form accept-charset='UTF-8' role='form'>
                                                    <fieldset>
                                                        <div class='form-group'>
                                                            <input class='form-control' placeholder='Ingrese Usuario' id='txtusuario' name='txtusuario' type='text' required='required'>
                                                        </div>
                                                        <div class='form-group'>
                                                            <input class='form-control' placeholder='Ingrese Contraseña' id='txtpass' name='txtpass' type='password' value='' required='required'>
                                                        </div>
                                                        <button class='btn btn-lg btn-success btn-block' onclick='goIngresar()'><span>Ingresar</span></button>
                                                    </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                dti_core::set('css', '<link href="public/css/componentes/login/login1.css" rel="stylesheet" type="text/css"/>');
                dti_core::set('script', '<script src="public/js/componentes/login/login.js" type="text/javascript"></script>');
                break;
            case "2":
                $loginAction = "<div class='container'>
                                <div class='row'>
                        <!-- Mixins-->
                        <br>
                        <div class='container'>
                          <div class=''></div>
                          <div class='card'>
                            <h1 class='title'>Sistema Mies</h1>
                            <div id='_AJAX_LOGIN_'></div>
                              <div class='input-container'>
                                <input type='text' id='txtusuario' name='txtusuario' required='required'/>
                                <label for='txtusuario'>Usuario</label>
                                <div class='bar'></div>
                              </div>
                              <div class='input-container'>
                                <input type='password' id='txtpass' name='txtpass' required='required'/>
                                <label for='txtpass'>Contraseña</label>
                                <div class='bar'></div>
                              </div>
                              <div class='button-container'>
                                <button onclick='goIngresar()'><span>Ingresar</span></button>
                              </div>
                              <div class='footer'><a class='toggle'>Olvide Contraseña?</a></div>
                          </div>
                          <div class='card alt'>
                            <div class='toggle'></div>
                                <h1 class='title'>Recuperar Contraseña
                                <div id='_AJAX_LOSTPASS_'></div>
                                <div class='close'></div>
                            </h1>
                              <div class='input-container'>
                                <input type='text' id='txtcorreo' name='txtcorreo' required='required'/>
                                <label for='Correo'>Correo</label>
                                <div class='bar'></div>
                              </div>
                              <div class='button-container'>
                                <button onclick='goRecuperarClave()'><span>Recuperar</span></button>
                              </div>
                          </div>
                        </div>
                                </div>
                        </div>";
                dti_core::set('css', '<link href="public/css/componentes/login/login2.css" rel="stylesheet" type="text/css"/>');
                dti_core::set('script', '<script src="public/js/componentes/login/login.js" type="text/javascript"></script>');
                break;
            case "3":
                
                $componente = "<label for = 'txtempresa' class='control-label'>Seleccionar Empresa</label>
                        <div>
                            <select class='form-control' id='txtempresa' name='txtempresa'></select>
                        </div>";
                
                $loginAction = '<div class="main-agileinfo slider ">
                                    <div class="items-group">
                                            <div class="item agileits-w3layouts">
                                                    <div class="block text main-agileits">
                                                            <span class="circleLight"></span>
                                                            <!-- login form -->
                                                            <div class="login-form loginw3-agile">
                                                                    <div class="agile-row">
                                                                            <img src="'.$param['miniLogo'].'" width="150px" alt="">
                                                                            <br><br>
                                                                            <div id="_AJAX_LOGIN_"></div>
                                                                            <div class="login-agileits-top">
                                                                                '.$componente.'
                                                                                <br>
                                                                                <button class="btn-login3" onclick=\'goEmpresa("default","selectempresa")\'><span>Ingresar</span></button>
                                                                                <br><br>
                                                                                <button class="btn-login3" onclick=\'location.href="default/logout"\'><span>Salir</span></button>
                                                                            </div>
                                                                            <div class="login-agileits-bottom wthree">
                                                                            </div>
                                                                    </div>
                                                            </div>
                                                    </div>
                                                            <p> &copy; '. date('Y',time()) .' '.$this->website["nombre"].'</p>
                                            </div>
                                    </div>
                            </div>
                            <style>
                            body {
                                font-family: "Arsenal", sans-serif; 
                                background:#fff; 
                                background: url('.$param['imgFondo'].')repeat 0px 0px;
                                background-size: cover;
                                -webkit-background-size: cover;
                                -moz-background-size: cover;
                                -o-background-size: cover; 
                                background-attachment: fixed;
                                text-align:center;
                            }
                            </style>';
                
                \dti_core::set('script', '<script type="text/javascript"> '
                        . '$(document).ready(function() { '
                        . '$.ajax({ url: "default/getEmpresa",  '
                        . 'type: "post", '
                        . 'success: function(response) { $("#txtempresa").html(response).fadeIn(); } }); }); '
                        . '</script>');
                
                dti_core::set('css', '<link href="public/css/componentes/login/login3.css" rel="stylesheet" type="text/css"/>');
                dti_core::set('script', '<script src="public/js/componentes/login/empresa.js" type="text/javascript"></script>');
                break;
        }

        return $loginAction;
    }
    
    public function menuAction($template,$menu=0,$core='',$modulo=1)
    {
        $menuAction = "";
        $banderaid = 0;
        //Manejar el Menu
        if ($menu == 0)
        {
            if (strlen($core)>0)
            {
                $datosMenu = new Entidades\Sis60000($this->adapter);
                $datosMenu = $datosMenu->getByMultiOrder('id','padre_id', 0,'sis00400id',$modulo);
            }
            else
            {
                $datosMenu = new Entidades\Sis60000($this->adapter);
                $dtMenu = $datosMenu->getByMultiOrderObj('id','padre_id', 0,'sis00400id',$modulo);
                $rol = new \Models\Sis00200Model($this->adapter);
                
                while($row=$dtMenu->fetch_object())
                {
                    $ventana = substr($row->href, strpos($row->href,'/')+1, strlen($row->href));
                    $dtrol = $rol->getPermisoVentana($_SESSION['usuario'],$ventana)->fetch_object();
                    if ($dtrol->numrows>0){
                        $menuAction .= "<li id='li".$row->id."' class='sidebar-item'> <a class='sidebar-link waves-effect waves-dark sidebar-link' href='".$row->href."' aria-expanded='false'><i class='".$row->icono."'></i><span class='hide-menu'>".$row->nombre."</span></a></li>";
                    }
                    else if($ventana=='index')
                    {
                        $menuAction .= "<li id='li".$row->id."' class='sidebar-item'> <a class='sidebar-link waves-effect waves-dark sidebar-link' href='".$row->href."' aria-expanded='false'><i class='".$row->icono."'></i><span class='hide-menu'>".$row->nombre."</span></a></li>";
                    }
                }
            }
            $template = '';
            switch ($template) {
                case 'template1':
                    //Crear menu desde la base de datos
                    //Ejemplo Manual
                    foreach($datosMenu as $dato) {
                        if ($banderaid == 0) {
                            $menuAction .= "<li class='active'><a href='".$dato['href']."'>".$dato['nombre']."</a></li>";
                            $banderaid = 1;
                        }else{
                            $menuAction .= "<li><a href='".$dato['href']."'>".$dato['nombre']."</a></li>";
                        }
                    }
                    if (ECOMMERCE == '1') {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/tempcarrito'><i class='fa fa-shopping-cart '></i><span class='outerCatalogo'></span></a></li>";
                    }
                    if (isset($_SESSION["usuarioCore"])) {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/logout'>Salir</a></li>";
                    }
                    if (isset($_SESSION["usuario"])) {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/logout'>Salir</a></li>";
                    }
                    break;
                case 'template2':
                    foreach($datosMenu as $dato) {
                        if ($banderaid == 0) {
                            $menuAction .= "<li class='active'><a href='".$dato['href']."'>".$dato['nombre']."</a></li>";
                            $banderaid = 1;
                        }else{
                            $menuAction .= "<li><a href='".$dato['href']."'>".$dato['nombre']."</a></li>";
                        }
                    }
                    if (ECOMMERCE == '1') {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/tempcarrito'><i class='fa fa-shopping-cart '></i><span class='outerCatalogo'></span></a></li>";
                    }
                    if (isset($_SESSION["user"])) {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/logout'>Salir</a></li>";
                    }
                    //$menuAction .= "<li class='dropdown' id='dti_logout'></li>";
                    break;
                case 'template3':
                    foreach($datosMenu as $dato) {
                        if ($banderaid == 0) {
                            $menuAction .= "<li class='active'><a href='".$dato['href']."'>".$dato['nombre']."</a></li>";
                            $banderaid = 1;
                        }else{
                            $menuAction .= "<li><a href='".$dato['href']."'>".$dato['nombre']."</a></li>";
                        }
                    }
                    if (ECOMMERCE == '1') {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/tempcarrito'><i class='fa fa-shopping-cart '></i><span class='outerCatalogo'></span></a></li>";
                    }
                    if (isset($_SESSION["user"])) {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/logout'>Salir</a></li>";
                    }
                    //$menuAction .= "<li class='dropdown' id='dti_logout'></li>";
                    break;
                case 'template4':
                    //Crear menu desde la base de datos
                    foreach($datosMenu as $dato) {
                        if ($dato['hijos'] == 'S') {
                            $hijo = $this->menuHijoAction($dato['id']);
                            if (strlen($hijo)>0) {
                                $menuAction .= '<li class="submenu">
                                            <a href="'.$dato['href'].'"><span class="icon-rocket"></span>'.$dato['nombre'].'<span class="caret icon-arrow-down6"></span></a>
                                            <ul class="children">'.$hijo.'
                                            </ul>
                                        </li>';
                            }else{
                                $menuAct0ion .= '<li><a href="'.$dato['href'].'"><span class="icon-earth"></span>'.$dato['nombre'].'</a></li>';
                            }
                        }else if ($dato['padre_id'] == 0) {
                            $menuAction .= '<li><a href="'.$dato['href'].'"><span class="icon-earth"></span>'.$dato['nombre'].'</a></li>';
                        }
                    }
                    if (ECOMMERCE == '1') {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/tempcarrito'><i class='fa fa-shopping-cart '></i><span class='outerCatalogo'></span></a></li>";
                    }
                    if (isset($_SESSION["user"])) {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/logout'>Salir</a></li>";
                    }
                    break;
                case 'template5':
                    //Agregar select de establecimiento
                    //Menu Normal
                    if (globalFunctions::es_bidimensional($datosMenu))
                    {
                        foreach($datosMenu as $dato)
                        {
                            //Verificar si tiene permiso
                            //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                            $ventana = substr($dato['href'], strpos($dato['href'],'/')+1, strlen($dato['href']));
                            if ($dato['hijos'] == 'S')
                            {
                                $hijo = $this->menuHijoAction($dato['id'],'template5');
                                if (strlen($hijo)>0)
                                {
                                    $menuAction .= '<li id="li'.$dato['id'].'">
                                                <a><span class="'.$dato['icono'].'"></span>'.$dato['nombre'].'<span class="fa fa-caret-down"></span></a>
                                                <ul>'.$hijo.'
                                                </ul>
                                            </li>';
                                }
                                else if(strlen($ventana)>0)
                                {
                                    $menuAction .= "<li id='li".$dato['id']."' ><a href='".$dato['href']."'><span class='".$dato['icono']."'></span>".$dato['nombre']."</a></li>";
                                }
                            }
                            else if ($dato['padre_id'] == 0)
                            {
                                if ($banderaid == 0)
                                {
                                    $menuAction .= "<li id='li".$dato['id']."' class='dti_menu_item_active'><a href='".$dato['href']."'><span class='".$dato['icono']."'></span>".$dato['nombre']."</a></li>";
                                    $banderaid = 1;
                                }
                                else if(strlen($ventana)>0)
                                {
                                    $menuAction .= "<li id='li".$dato['id']."'><a href='".$dato['href']."'><span class='".$dato['icono']."'></span>".$dato['nombre']."</a></li>";
                                }
                            }
                        }
                    }
                    else if(isset($datosMenu['id']))
                    {
                        //Verificar si tiene permiso
                        //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                        $ventana = substr($datosMenu['href'], strpos($datosMenu['href'],'/')+1, strlen($datosMenu['href']));
                        if ($datosMenu['hijos'] == 'S')
                        {
                            $hijo = $this->menuHijoAction($datosMenu['id'],'template5');
                            if (strlen($hijo)>0)
                            {
                                $menuAction .= '<li id="li'.$datosMenu['id'].'">
                                            <a><span class="'.$datosMenu['icono'].'"></span>'.$datosMenu['nombre'].'<span class="fa fa-caret-down"></span></a>
                                            <ul>'.$hijo.'
                                            </ul>
                                        </li>';
                            }
                            else if(strlen($ventana)>0)
                            {
                                $menuAction .= "<li id='li".$datosMenu['id']."'><a href='".$datosMenu['href']."'><span class='".$datosMenu['icono']."'></span>".$dato['nombre']."</a></li>";
                            }
                        }
                        else if ($dato['padre_id'] == 0)
                        {
                            if ($banderaid == 0)
                            {
                                $menuAction .= "<li id='li".$datosMenu['id']."' class='dti_menu_item_active'><a href='".$datosMenu['href']."'><span class='".$dato['icono']."'></span>".$dato['nombre']."</a></li>";
                                $banderaid = 1;
                            }
                            else if(strlen($ventana)>0)
                            {
                                $menuAction .= "<li id='li".$datosMenu['id']."'><a href='".$datosMenu['href']."'><span class='".$datosMenu['icono']."'></span>".$dato['nombre']."</a></li>";
                            }
                        }
                    }
                    
                    if (ECOMMERCE == '1') {
                        $variables = new \dti_core("catalogo");
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/tempcarrito'><i class='fa fa-shopping-cart '></i><span class='outerCatalogo'></span></a></li>";
                    }
                    if (isset($_SESSION["usuario"])) {
                        $menuAction .= "<li><a href='".CONTROLADOR_DEFECTO."/logout'><span class='la la-close'></span>Salir</a></li>";
                    }
                    break;
                case 'template6':
                    if (globalFunctions::es_bidimensional($datosMenu))
                    {
                        foreach($datosMenu as $dato)
                        {
                            //Verificar si tiene permiso
                            //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                            $ventana = substr($dato['href'], strpos($dato['href'],'/')+1, strlen($dato['href']));
                            if ($dato['hijos'] == 'S')
                            {
                                $hijo = $this->menuHijoAction($dato['id'],'template6');
                                if (strlen($hijo)>0)
                                {
                                    $menuAction .= '<li id="li'.$dato['id'].'" class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="'.$dato['icono'].'"></i><span class="hide-menu">'.$dato['nombre'].'</span></a>
                                                    <ul aria-expanded="false" class="collapse  first-level">'.$hijo.'
                                                    </ul>
                                                </li>';
                                }
                                else if(strlen($ventana)>0)
                                {
                                    $menuAction .= "<li id='li".$dato['id']."' class='sidebar-item'> <a class='sidebar-link waves-effect waves-dark sidebar-link' href='".$dato['href']."' aria-expanded='false'><i class='".$dato['icono']."'></i><span class='hide-menu'>".$dato['nombre']."</span></a></li>";
                                }
                            }
                            else if ($dato['padre_id'] == 0)
                            {
                                if ($banderaid == 0)
                                {
                                    $menuAction .= "<li id='li".$dato['id']."' class='sidebar-item'> <a class='sidebar-link waves-effect waves-dark sidebar-link' href='".$dato['href']."' aria-expanded='false'><i class='".$dato['icono']."'></i><span class='hide-menu'>".$dato['nombre']."</span></a></li>";
                                    $banderaid = 1;
                                }
                                else if(strlen($ventana)>0)
                                {
                                    $menuAction .= "<li id='li".$dato['id']."' class='sidebar-item'> <a class='sidebar-link waves-effect waves-dark sidebar-link' href='".$dato['href']."' aria-expanded='false'><i class='".$dato['icono']."'></i><span class='hide-menu'>".$dato['nombre']."</span></a></li>";
                                }
                            }
                        }
                    }
                    else if(isset($datosMenu['id']))
                    {
                        //Verificar si tiene permiso
                        //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                        $ventana = substr($datosMenu['href'], strpos($datosMenu['href'],'/')+1, strlen($datosMenu['href']));
                        if ($datosMenu['hijos'] == 'S')
                        {
                            $hijo = $this->menuHijoAction($datosMenu['id'],'template6');
                            if (strlen($hijo)>0)
                            {
                                $menuAction .= '<li id="li'.$datosMenu['id'].'" class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="'.$datosMenu['icono'].'"></i><span class="hide-menu">'.$datosMenu['nombre'].'</span></a>
                                                    <ul aria-expanded="false" class="collapse  first-level">'.$hijo.'
                                                    </ul>
                                                </li>';
                            }
                            else if(strlen($ventana)>0)
                            {
                                $menuAction .= "<li id='li".$datosMenu['id']."' class='sidebar-item'> <a class='sidebar-link waves-effect waves-dark sidebar-link' href='".$datosMenu['href']."' aria-expanded='false'><i class='".$datosMenu['icono']."'></i><span class='hide-menu'>".$datosMenu['nombre']."</span></a></li>";
                            }
                        }
                        else if ($dato['padre_id'] == 0)
                        {
                            if ($banderaid == 0)
                            {
                                $menuAction .= "<li id='li".$datosMenu['id']."' class='sidebar-item'> <a class='sidebar-link waves-effect waves-dark sidebar-link' href='".$datosMenu['href']."' aria-expanded='false'><i class='".$datosMenu['icono']."'></i><span class='hide-menu'>".$datosMenu['nombre']."</span></a></li>";
                                $banderaid = 1;
                            }
                            else if(strlen($ventana)>0)
                            {
                                $menuAction .= "<li id='li".$datosMenu['id']."' class='sidebar-item'> <a class='sidebar-link waves-effect waves-dark sidebar-link' href='".$datosMenu['href']."' aria-expanded='false'><i class='".$datosMenu['icono']."'></i><span class='hide-menu'>".$datosMenu['nombre']."</span></a></li>";
                            }
                        }
                    }
                    break;
            }
        }
        else
        {
            if (isset($_SESSION["usuarioCore"])) {
                $menuAction .= "<li><a href='dtiware/logout'>Salir</a></li>";
            }
            if (isset($_SESSION["usuario"])) {
                $menuAction .= "<li><a href='seguridad/logout'>Salir</a></li>";
            }
        }

        
        
        return $menuAction.'';
    }
    
    public function menuHijoAction($padre,$template,$core='')
    {
        if (strlen($core)>0)
        {
            $menudt = new Entidades\Sis60000($this->adapter);
        }
        else
        {
            $menudt = new Entidades\Sis60000($this->adapter);
        }
        $menu = $menudt->getByOrderByActivo('padre_id', $padre,'orden');
        $menuHijoAction = '';
        
        if ($_SESSION['usuario']) {
            switch ($template) {
                case 'template1':
                    if (globalFunctions::es_bidimensional($menu)) {
                        foreach ($menu as $valor) {
                             $menuHijoAction .= '<li><a href="'.$valor['href'].'">'.$valor['nombre'].' <span class="icon-dot"></span></a></li>';
                        }
                    }else if(isset($menu['id'])){
                        $menuHijoAction .= '<li><a href="'.$menu['href'].'">'.$menu['nombre'].' <span class="icon-dot"></span></a></li>';
                    }else{
                        $menuHijoAction = '';
                    }
                    break;
                case 'template5':
                    $seguridad = new \Models\Sis20100Model($this->adapter);
                    $datosseg = $seguridad->getPermisos($_SESSION["usuario"]);
                    if (globalFunctions::es_bidimensional($datosseg))
                    {
                        foreach ($datosseg as $dato)
                        {
                            //Verificar si tiene permiso
                            //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                            $ventana = substr($dato['href'], strpos($dato['href'],'/')+1, strlen($dato['href']));
                            $rol = new \Models\Sis00200Model($this->adapter);
                            $dtrol = $rol->getPermisoVentana($_SESSION['usuario'],$ventana);       
                            if ($dtrol['numrows']>0 || $ventana == 'index')
                            {
                                switch (strtolower($dato["nombre"]))
                                {
                                    case 'tipo negocio':
                                        $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('tipo negocio'));
                                            if(isset($menu['id'])){
                                                $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                            }else{
                                                $menuHijoAction .= '';
                                            }
                                        break;
                                    case 'asociaciones':
                                            $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('asociaciones'));
                                            if(isset($menu['id'])){
                                                $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                            }else{
                                                $menuHijoAction .= '';
                                            }
                                        break;
                                    case 'responsables':
                                            $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('responsables'));
                                            if(isset($menu['id'])){
                                                $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                            }else{
                                                $menuHijoAction .= '';
                                            }
                                        break;
                                    case 'perfiles':
                                            $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('perfiles'));
                                            if(isset($menu['id'])){
                                                $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                            }else{
                                                $menuHijoAction .= '';
                                            }
                                        break;
                                    case 'usuarios':
                                            $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('usuarios'));
                                            if(isset($menu['id'])){
                                                $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                            }else{
                                                $menuHijoAction .= '';
                                            }
                                        break;
                                    case 'sectores':
                                            $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('sectores'));
                                            if(isset($menu['id'])){
                                                $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                            }else{
                                                $menuHijoAction .= '';
                                            }
                                        break;
                                    case 'comunidades':
                                            $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('comunidades'));
                                            if(isset($menu['id'])){
                                                $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                            }else{
                                                $menuHijoAction .= '';
                                            }
                                        break;
                                    case 'agendar':
                                        $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('agendar'));
                                        if(isset($menu['id'])){
                                            $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                        }else{
                                            $menuHijoAction .= '';
                                        }
                                    break;
                                    case 'seguimiento':
                                        $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('revisar'));
                                        if(isset($menu['id'])){
                                            $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                        }else{
                                            $menuHijoAction .= '';
                                        }
                                    break;
                                    case 'proyectos':
                                        $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('proyectos'));
                                        if(isset($menu['id'])){
                                            $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                        }else{
                                            $menuHijoAction .= '';
                                        }
                                    break;
                                    case 'reportes':
                                        $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('reporte'));
                                        if(isset($menu['id'])){
                                            $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                        }else{
                                            $menuHijoAction .= '';
                                        }
                                    break;
                                }
                            }
                        }
                    }
                    else if (isset($datosseg["id"]))
                    {
                        switch (strtolower($datosseg["nombre"])) {
                            case 'tipo negocio':
                                    $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('tipo negocio'));
                                        if(isset($menu['id'])){
                                            $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                        }else{
                                            $menuHijoAction .= '';
                                        }
                                    break;
                            case 'asociaciones':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('asociaciones'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'responsables':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('asociaciones'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'perfiles':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('asociaciones'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'comunidades':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('comunidades'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'usuarios':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('usuarios'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'sectores':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('sectores'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'agendar':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('agendar'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'seguimiento':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('revisar'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'proyectos':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('proyectos'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                            case 'reportes':
                                $menu = $menudt->getMulti('padre_id', $padre, 'nombre', strtolower('reporte'));
                                if(isset($menu['id'])){
                                    $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }else{
                                    $menuHijoAction .= '';
                                }
                            break;
                        }
                    }
                    else
                    {
                        if (globalFunctions::es_bidimensional($menu))
                        {
                            foreach ($menu as $valor) {
                                //Verificar si tiene permiso
                                //Tiene palabra reservada ejemplo Optica
                                //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                                $ventana = substr($valor['href'], strpos($valor['href'],'/')+1, strlen($valor['href']));
                                if (strpos($ventana, 'Optica')>0)
                                {
                                    $ventana = substr($valor['href'], strpos($valor['href'],'/')+1, strpos($ventana, 'Optica'));
                                }
                                if (strpos($ventana, 'Automotriz')>0)
                                {
                                    $ventana = substr($valor['href'], strpos($valor['href'],'/')+1, strpos($ventana, 'Automotriz'));
                                }
                                if (strpos($ventana, 'Fe')>0)
                                {
                                    $ventana = substr($valor['href'], strpos($valor['href'],'/')+1, strpos($ventana, 'Fe'));
                                }
                                $rol = new \Models\Sis00200Model($this->adapter);
                                $dtrol = $rol->getPermisoVentana($_SESSION['usuario'],$ventana);
                                if ($dtrol['numrows']>0)
                                {
                                    if ($valor['hijos'] == 'S')
                                    {
                                        $hijo = $this->menuHijoAction($valor['id'],'template5');
                                        if (strlen($hijo)>0) {
                                            $menuHijoAction .= '<li id="li'.$valor['id'].'">
                                                        <a><span class="'.$valor['icono'].'"></span>'.$valor['nombre'].'<span class="fa fa-caret-down"></span></a>
                                                        <ul>'.$hijo.'
                                                        </ul>
                                                    </li>';
                                        }else{
                                            $menuHijoAction .= "<li id='li".$valor['id']."'><a href='".$valor['href']."'><span class='".$valor['icono']."'></span>".$valor['nombre']."</a></li>";
                                        }
                                    }
                                    else
                                    {
                                        $menuHijoAction .= '<li id="li'.$valor['id'].'"><a href="'.$valor['href'].'"><span class="'.$valor['icono'].'"></span>'.$valor['nombre'].' </a></li>';
                                    }
                                }
                            }
                        }
                        else if(isset($menu['id']))
                        {
                            //Verificar si tiene permiso
                            //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                            $ventana = substr($menu['href'], strpos($menu['href'],'/')+1, strlen($menu['href']));
                            if (strpos($ventana, 'Optica')>0)
                            {
                                $ventana = substr($menu['href'], strpos($menu['href'],'/')+1, strpos($ventana, 'Optica'));
                            }
                            if (strpos($ventana, 'Automotriz')>0)
                            {
                                $ventana = substr($menu['href'], strpos($menu['href'],'/')+1, strpos($ventana, 'Automotriz'));
                            }
                            if (strpos($ventana, 'Fe')>0)
                            {
                                $ventana = substr($menu['href'], strpos($menu['href'],'/')+1, strpos($ventana, 'Fe'));
                            }
                            $rol = new \Models\Sis00200Model($this->adapter);
                            $dtrol = $rol->getPermisoVentana($_SESSION['usuario'],$ventana);
                            if ($dtrol['numrows']>0)
                            {
                                if ($menu['hijos'] == 'S')
                                {
                                    $hijo = $this->menuHijoAction($menu['id'],'template5');
                                    if (strlen($hijo)>0) {
                                        $menuHijoAction .= '<li id="li'.$menu['id'].'">
                                                    <a><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'<span class="fa fa-caret-down"></span></a>
                                                    <ul>'.$hijo.'
                                                    </ul>
                                                </li>';
                                    }else{
                                        $menuHijoAction .= "<li id='li".$menu['id']."'><a href='".$menu['href']."'><span class='".$menu['icono']."'></span>".$menu['nombre']."</a></li>";
                                    }
                                }
                                else
                                {
                                    $menuHijoAction .= '<li id="li'.$menu['id'].'"><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                                }
                            }
                        }
                        else
                        {
                            $menuHijoAction = '';
                        }
                    }
                    break;
                case 'template6':
                    if (globalFunctions::es_bidimensional($menu))
                    {
                        foreach ($menu as $valor) {
                            //Verificar si tiene permiso
                            //Tiene palabra reservada ejemplo Optica
                            //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                            $ventana = substr($valor['href'], strpos($valor['href'],'/')+1, strlen($valor['href']));
                            if (strpos($ventana, 'Optica')>0)
                            {
                                $ventana = substr($valor['href'], strpos($valor['href'],'/')+1, strpos($ventana, 'Optica'));
                            }
                            if (strpos($ventana, 'Automotriz')>0)
                            {
                                $ventana = substr($valor['href'], strpos($valor['href'],'/')+1, strpos($ventana, 'Automotriz'));
                            }
                            if (strpos($ventana, 'Fe')>0)
                            {
                                $ventana = substr($valor['href'], strpos($valor['href'],'/')+1, strpos($ventana, 'Fe'));
                            }
                            $rol = new \Models\Sis00200Model($this->adapter);
                            $dtrol = $rol->getPermisoVentana($_SESSION['usuario'],$ventana);
                            if ($dtrol['numrows']>0)
                            {
                                if ($valor['hijos'] == 'S')
                                {
                                    $hijo = $this->menuHijoAction($valor['id'],'template5');
                                    if (strlen($hijo)>0) {
                                        $menuHijoAction .= '<li id="li'.$valor['id'].'" class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="'.$valor['icono'].'"></i><span class="hide-menu">'.$valor['nombre'].'</span></a>
                                                    <ul aria-expanded="false" class="collapse  first-level">'.$hijo.'
                                                    </ul>
                                                </li>';
                                    }else{
                                        $menuHijoAction .= "<li id='li".$valor['id']."' class='sidebar-item'><a href='".$valor['href']."' class='sidebar-link'><i class='".$valor['icono']."'></i><span class='hide-menu'>".$valor['nombre']."</span></a></li>";
                                    }
                                }
                                else
                                {
                                    $menuHijoAction .= "<li id='li".$valor['id']."' class='sidebar-item'><a href='".$valor['href']."' class='sidebar-link'><i class='".$valor['icono']."'></i><span class='hide-menu'>".$valor['nombre']."</span></a></li>";
                                }
                            }
                        }
                    }
                    else if(isset($menu['id']))
                    {
                        //Verificar si tiene permiso
                        //SIS00200 rol -> SIS00201 Tareas -> SIS00202 Ventana
                        $ventana = substr($menu['href'], strpos($menu['href'],'/')+1, strlen($menu['href']));
                        if (strpos($ventana, 'Optica')>0)
                        {
                            $ventana = substr($menu['href'], strpos($menu['href'],'/')+1, strpos($ventana, 'Optica'));
                        }
                        if (strpos($ventana, 'Automotriz')>0)
                        {
                            $ventana = substr($menu['href'], strpos($menu['href'],'/')+1, strpos($ventana, 'Automotriz'));
                        }
                        if (strpos($ventana, 'Fe')>0)
                        {
                            $ventana = substr($menu['href'], strpos($menu['href'],'/')+1, strpos($ventana, 'Fe'));
                        }
                        $rol = new \Models\Sis00200Model($this->adapter);
                        $dtrol = $rol->getPermisoVentana($_SESSION['usuario'],$ventana);
                        if ($dtrol['numrows']>0)
                        {
                            if ($menu['hijos'] == 'S')
                            {
                                $hijo = $this->menuHijoAction($menu['id'],'template5');
                                if (strlen($hijo)>0) {
                                    $menuHijoAction .= '<li id="li'.$menu['id'].'" class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="'.$menu['icono'].'"></i><span class="hide-menu">'.$menu['nombre'].'</span></a>
                                                    <ul aria-expanded="false" class="collapse  first-level">'.$hijo.'
                                                    </ul>
                                                </li>';
                                }else{
                                    $menuHijoAction .= "<li id='li".$menu['id']."' class='sidebar-item'><a href='".$menu['href']."' class='sidebar-link'><i class='".$menu['icono']."'></i><span class='hide-menu'>".$menu['nombre']."</span></a></li>";
                                }
                            }
                            else
                            {
                                $menuHijoAction .= "<li id='li".$menu['id']."' class='sidebar-item'><a href='".$menu['href']."' class='sidebar-link'><i class='".$menu['icono']."'></i><span class='hide-menu'>".$menu['nombre']."</span></a></li>";
                            }
                        }
                    }
                    else
                    {
                        $menuHijoAction = '';
                    }
                break;
            }
        }
        else{
            switch ($template) {
                case 'template1':
                    if (globalFunctions::es_bidimensional($menu)) {
                        foreach ($menu as $valor) {
                             $menuHijoAction .= '<li><a href="'.$valor['href'].'">'.$valor['nombre'].' <span class="icon-dot"></span></a></li>';
                        }
                    }else if(isset($menu['id'])){
                        $menuHijoAction .= '<li><a href="'.$menu['href'].'">'.$menu['nombre'].' <span class="icon-dot"></span></a></li>';
                    }else{
                        $menuHijoAction = '';
                    }
                    break;
                case 'template5':
                    if (globalFunctions::es_bidimensional($menu)) {
                        foreach ($menu as $valor) {
                            $menuHijoAction .= '<li><a href="'.$valor['href'].'"><span class="'.$valor['icono'].'"></span>'.$valor['nombre'].'</a></li>';
                        }
                    }else if(isset($menu['id'])){
                        $menuHijoAction .= '<li><a href="'.$menu['href'].'"><span class="'.$menu['icono'].'"></span>'.$menu['nombre'].'</a></li>';
                    }else{
                        $menuHijoAction = '';
                    }
                    break;
                case 'template6':
                    if (globalFunctions::es_bidimensional($menu)) {
                        foreach ($menu as $valor) {
                            $menuHijoAction .= "<li id='li".$valor['id']."' class='sidebar-item'><a href='".$valor['href']."' class='sidebar-link'><i class='".$valor['icono']."'></i><span class='hide-menu'>".$valor['nombre']."</span></a></li>";
                        }
                    }else if(isset($menu['id'])){
                        $menuHijoAction .= "<li id='li".$menu['id']."' class='sidebar-item'><a href='".$menu['href']."' class='sidebar-link'><i class='".$menu['icono']."'></i><span class='hide-menu'>".$menu['nombre']."</span></a></li>";
                    }else{
                        $menuHijoAction = '';
                    }
                    break;
            }
        }
        return $menuHijoAction;
    }
    
    public function establecimientoAction()
    {
        $select = new Models\Cc00020Model($this->adapter);
        $datos = $select->getCombobox('');
        
        $result = '';
        
        if (isset($_SESSION['establecimiento'])) {
            $valDefecto = 'selected="true" ';
        }
        
        $result .= '<option value="0">Seleccionar</option>';
        if (globalFunctions::es_bidimensional($datos)) {
            foreach ($datos as $key => $value) {
                if (isset($_SESSION['establecimiento'])) {
                    if ($value["id"] == $_SESSION['establecimiento']) {
                        $result .= '<option value="'.$value["id"].'" '.$valDefecto.'>'.$value["nombre"].'</option>';
                    }else{
                        $result .= '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                    }
                }else{
                    $result .= '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                }
            }
        }
        else{
            if (isset($_SESSION['establecimiento'])) {
                if ($datos["id"] == $_SESSION['establecimiento']) {
                    $result .= '<option value="'.$datos["id"].'" '.$valDefecto.'>'.$datos["nombre"].'</option>';
                }else{
                    $result .= '<option value="'.$datos["id"].'">'.$datos["nombre"].'</option>';
                }
            }else{
                    $result .= '<option value="'.$datos["id"].'">'.$datos["nombre"].'</option>';
                }
        }
        
        $componente = "<br><label for = 'txtEstablecimiendoSelect' class='col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label'>Establecimiento</label><br>
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <select class='form-control' id='txtEstablecimiendoSelect' name='txtEstablecimiendoSelect'>
                            ".$result."
                            </select>
                        </div><br>";
        
        \dti_core::set('script', "<script>
                    $( '#txtEstablecimiendoSelect' ).change(function() {
                        var seleccionado = document.getElementById('txtEstablecimiendoSelect').value;
                        $.ajax({
                            data: {'seleccionado': seleccionado },
                            type: 'POST',
                            dataType: 'json',
                            url: 'seguridad/selectEstablecimiento',
                        })
                         .done(function( data ) {
                             location.reload();
                         })
                         .fail(function( jqXHR, textStatus, errorThrown ) {
                             if ( console && console.log ) {
                                Swal.fire('Error!',textStatus,'error');
                             }
                        });
                    });
                </script>");
        
        return $componente;
    }

    public function dashboardAction($dash)
    {
        $result = '';
        
        $plan = new \Models\Sis20013Model($this->adapter);
        
        switch ($dash) {
            case 'CONTABILIDAD':
                /*Validar si estan instalados los Modulos*/
                $valinstall = new \Entidades\Sis20012($this->adapter);
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 8,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuContabilidad");
                    $boxquick->setColor("green");
                    $boxquick->setIcono("fa-calculator");
                    $boxquick->setTitulo("Contablidad");
                    $boxquick->setUrl("financiero/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 11,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuFE");
                    $boxquick->setColor("secondary");
                    $boxquick->setIcono("fa-soundcloud");
                    $boxquick->setTitulo("FE");
                    $boxquick->setUrl("fe/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 5,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuCompras");
                    $boxquick->setColor("primary");
                    $boxquick->setIcono("fa-cart-arrow-down");
                    $boxquick->setTitulo("Compras");
                    $boxquick->setUrl("cp/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 6,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuVentas");
                    $boxquick->setColor("info");
                    $boxquick->setIcono("fa-money");
                    $boxquick->setTitulo("Ventas");
                    $boxquick->setUrl("cc/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 7,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuInventario");
                    $boxquick->setColor("success");
                    $boxquick->setIcono("fa-book");
                    $boxquick->setTitulo("Inventario");
                    $boxquick->setUrl("inventario/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 12,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuSeguridad");
                    $boxquick->setColor("danger");
                    $boxquick->setIcono("fa-wrench");
                    $boxquick->setTitulo("Seguridad");
                    $boxquick->setUrl("seguridad/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 13,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuOptica");
                    $boxquick->setColor("danger");
                    $boxquick->setIcono("fa-eye");
                    $boxquick->setTitulo("Optica");
                    $boxquick->setUrl("optica/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 9,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuBancos");
                    $boxquick->setColor("secondary");
                    $boxquick->setIcono("fa-university");
                    $boxquick->setTitulo("Bancos");
                    $boxquick->setUrl("bancos/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 10,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuImpuestos");
                    $boxquick->setColor("primary");
                    $boxquick->setIcono("fa-file-text-o");
                    $boxquick->setTitulo("Impuestos");
                    $boxquick->setUrl("impuestos/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 16,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuEtiquetas");
                    $boxquick->setColor("danger");
                    $boxquick->setIcono("fa-print");
                    $boxquick->setTitulo("Etiquetas");
                    $boxquick->setUrl("etiquetas/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 19,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuListaPrecios");
                    $boxquick->setColor("info");
                    $boxquick->setIcono("fa-file");
                    $boxquick->setTitulo("Lista Precios");
                    $boxquick->setUrl("listaprecios/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 20,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuConteo");
                    $boxquick->setColor("green");
                    $boxquick->setIcono("fa-dropbox");
                    $boxquick->setTitulo("Conteo");
                    $boxquick->setUrl("conteo/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 21,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuCierreCaja");
                    $boxquick->setColor("success");
                    $boxquick->setIcono("fa-calendar-o");
                    $boxquick->setTitulo("Cierre de Caja");
                    $boxquick->setUrl("caja/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 27,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuPos");
                    $boxquick->setColor("info");
                    $boxquick->setIcono("fa-usd");
                    $boxquick->setTitulo("Pos");
                    $boxquick->setUrl("pos/index");
                    $result .= $boxquick->getbox();
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 25,'ruc', $_SESSION['rucEmpresa']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setId("mnuPromociones");
                    $boxquick->setColor("green");
                    $boxquick->setIcono("fa-usd");
                    $boxquick->setTitulo("Promociones");
                    $boxquick->setUrl("promociones/index");
                    $result .= $boxquick->getbox();
                }
                break;
            case 'FE ERP':
                //COMPROBANTES EMITIDOS
                $dtplan = $plan->getComprobantesEmitidos($_SESSION['empresa']);
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("COMPROBANTES EMITIDOS");
                $boxquick->setSubtitulo("# ".($dtplan['total'])."");
                $result .= $boxquick->getboxQuick('3');
                
                //COMPROBANTES MENSUAL
                $dtplan = $plan->getComprobantesEmitidosMensual($_SESSION['empresa']);
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("COMPROBANTES MENSUAL");
                $boxquick->setSubtitulo("# ".($dtplan['total'])."");
                $result .= $boxquick->getboxQuick('3');
                
                //COMPROBANTES ANUAL
                $dtplan = $plan->getComprobantesEmitidosAnual($_SESSION['empresa']);
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("COMPROBANTES ANUAL");
                $boxquick->setSubtitulo("# ".($dtplan['total'])."");
                $result .= $boxquick->getboxQuick('3');
                
                break;
            case 'TOTAL EMPRESAS':
                //Sacar el total de empresa
                $empresas = new \Entidades\Sis00100($this->adapter);
                $dtempresas = $empresas->getCount();
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("TOTAL EMPRESAS");
                $boxquick->setSubtitulo("# ".($dtempresas['numrows'])."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'EMPRESAS POR CREAR':
                $dtplan = $plan->getEmpresasXCrear($cliente['id'], $empresa['ruc'],1);
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("EMPRESAS POR CREAR");
                $boxquick->setSubtitulo("# ".($dtplan['total'])."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'COMPROBANTES RESTANTES':
                $dtplan = $plan->getComprobantesDisponibles($cliente['id'], $empresa['ruc'],2);
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("COMPROBANTES RESTANTES");
                $boxquick->setSubtitulo("# ".($dtplan['total'])."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'VENTAS DEL DIA':
                $dtplan = $plan->getVentasdia($cliente['id'], $empresa['id'],2);
                
                if (isset($dtplan['venta'])) {
                    $ventadia = $dtplan['venta'];
                }else{
                    $ventadia = "0.00";
                }
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-usd");
                $boxquick->setTitulo("VENTAS DEL DIA");
                $boxquick->setSubtitulo("$ ".$ventadia."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'VENTAS DE LA SEMANA':
                $dtplan = $plan->getVentasSemana($cliente['id'], $empresa['id'],2);
                
                if (isset($dtplan['venta'])) {
                    $ventadia = $dtplan['venta'];
                }else{
                    $ventadia = "0.00";
                }
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-usd");
                $boxquick->setTitulo("VENTAS DE LA SEMANA");
                $boxquick->setSubtitulo("$ ".$ventadia."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'VENTAS DEL MES':
                $dtplan = $plan->getVentasMes($cliente['id'], $empresa['id'],2);
                
                if (isset($dtplan['venta'])) {
                    $ventadia = $dtplan['venta'];
                }else{
                    $ventadia = "0.00";
                }
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-usd");
                $boxquick->setTitulo("VENTAS DEL MES");
                $boxquick->setSubtitulo("$ ".$ventadia."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'VENTAS DEL AÑO':
                $dtplan = $plan->getVentasAnio($cliente['id'], $empresa['id'],2);
                
                if (isset($dtplan['venta'])) {
                    $ventadia = $dtplan['venta'];
                }else{
                    $ventadia = "0.00";
                }
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-usd");
                $boxquick->setTitulo("VENTAS DEL AÑO");
                $boxquick->setSubtitulo("$ ".$ventadia."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'COMPROBANTES EMITIDOS':
                $dtplan = $plan->getComprobantesEmitidos($cliente['id'], $empresa['id']);
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("COMPROBANTES EMITIDOS");
                $boxquick->setSubtitulo("# ".($dtplan['total'])."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'COMPROBANTES MENSUAL':
                $dtplan = $plan->getComprobantesEmitidosMensual($cliente['id'], $empresa['id']);
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("COMPROBANTES MENSUAL");
                $boxquick->setSubtitulo("# ".($dtplan['total'])."");
                $result = $boxquick->getboxQuick('3');
                break;
            case 'COMPROBANTES ANUAL':
                $dtplan = $plan->getComprobantesEmitidosAnual($cliente['id'], $empresa['id']);
                
                $boxquick = new \dti_boxquick();//Declaramos el Boxquick
                $boxquick->setColor("dark-blue");
                $boxquick->setIcono("fa-plus");
                $boxquick->setTitulo("COMPROBANTES ANUAL");
                $boxquick->setSubtitulo("# ".($dtplan['total'])."");
                $result = $boxquick->getboxQuick('3');
                break;
        }
        return $result;
    }
    
    public function minimenuAction()
    {
        $dashboard = new Models\Sis20015Model($this->adapter);
        $dtdashboard = $dashboard->getPlanesActivos();
        $boxquickConstruida = '';
        
        if (globalFunctions::es_bidimensional($dtdashboard))
        {
            foreach ($dtdashboard as $dash)
            {
                $boxquickConstruida .= $this->minidashboard($dash['descripcion']);
            }
        }
        else if ($dtdashboard['id'])
        {
            $boxquickConstruida .= $this->minidashboard($dtdashboard['descripcion']);
        }
        
        return $boxquickConstruida;
    }
    
    public function minidashboard($dash)
    {
        $result = '';
        
        $cliente = new Entidades\Sis00050($this->adapter);
        $cliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $empresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $plan = new \Models\Sis20013Model($this->adapter);
        
        switch ($dash) {
            case 'CONTABILIDAD':
                /*Validar si estan instalados los Modulos*/
                $valinstall = new \Entidades\Sis20012($this->adapter);
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 8,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("green");
                    $boxquick->setIcono("fa-calculator");
                    $boxquick->setTitulo("Contablidad");
                    $boxquick->setUrl("financiero/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 11,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("secondary");
                    $boxquick->setIcono("fa-soundcloud");
                    $boxquick->setTitulo("FE");
                    $boxquick->setUrl("fe/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 5,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("primary");
                    $boxquick->setIcono("fa-cart-arrow-down");
                    $boxquick->setTitulo("Compras");
                    $boxquick->setUrl("cp/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 6,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("info");
                    $boxquick->setIcono("fa-money");
                    $boxquick->setTitulo("Ventas");
                    $boxquick->setUrl("cc/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 7,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("success");
                    $boxquick->setIcono("fa-book");
                    $boxquick->setTitulo("Inventario");
                    $boxquick->setUrl("inventario/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 12,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("danger");
                    $boxquick->setIcono("fa-wrench");
                    $boxquick->setTitulo("Seguridad");
                    $boxquick->setUrl("seguridad/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 13,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("danger");
                    $boxquick->setIcono("fa-eye");
                    $boxquick->setTitulo("Optica");
                    $boxquick->setUrl("optica/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 9,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("secondary");
                    $boxquick->setIcono("fa-university");
                    $boxquick->setTitulo("Bancos");
                    $boxquick->setUrl("bancos/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 10,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("primary");
                    $boxquick->setIcono("fa-file-text-o");
                    $boxquick->setTitulo("Impuestos");
                    $boxquick->setUrl("impuestos/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 16,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("danger");
                    $boxquick->setIcono("fa-print");
                    $boxquick->setTitulo("Etiquetas");
                    $boxquick->setUrl("etiquetas/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 19,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("info");
                    $boxquick->setIcono("fa-file");
                    $boxquick->setTitulo("Lista Precios");
                    $boxquick->setUrl("listaprecios/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 20,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("green");
                    $boxquick->setIcono("fa-dropbox");
                    $boxquick->setTitulo("Conteo");
                    $boxquick->setUrl("conteo/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 21,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("success");
                    $boxquick->setIcono("fa-calendar-o");
                    $boxquick->setTitulo("Caja");
                    $boxquick->setUrl("caja/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 27,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("info");
                    $boxquick->setIcono("fa-usd");
                    $boxquick->setTitulo("Pos");
                    $boxquick->setUrl("pos/index");
                    $result .= $boxquick->getbox(3);
                }
                
                $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 25,'ruc', $empresa['ruc']);
                if ($dtvalinstall['numrows']>0)
                {
                    $boxquick = new \dti_box();//Declaramos el Boxquick
                    $boxquick->setColor("green");
                    $boxquick->setIcono("fa-usd");
                    $boxquick->setTitulo("Promociones");
                    $boxquick->setUrl("promociones/index");
                    $result .= $boxquick->getbox(3);
                }
                break;
        }
        return $result;
    }
      
    public function mnuPerfil()
    {
        $usuario = new \Entidades\Sis00300($this->adapter);
        $dtusuario = $usuario->getMulti('usuario', $_SESSION['usuario']);
        
        $mnuperfil = '<div class=""><img src="public/img/usuario-sin-foto.png" alt="user" class="img-circle" width="60"></div>
                        <div class="m-l-10">
                            <h4 class="m-b-0">'.$dtusuario['nombre'].' '.$dtusuario['apellido'].'</h4>
                            <p class=" m-b-0">'.$dtusuario['correo'].'</p>
                        </div>';
        
        return $mnuperfil;
    }
}