<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class DefaultController extends Controllers
{
    private $session,$conectar,$adapter,$layout,$website,$cliente,$login_empresa;
    
    public function __construct()
    {
        $this->session = new Session();
        //Conexion a la base de datos
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        //Traemos los datos del portal configurados
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        //Traemos los datos del cliente
        $this->cliente= new Models\Sis00050Model($this->adapter);
        //Cargamos el layout
        $this->layout = new dti_layout($this->website);
        //Cargamos la empresa logueada
        /*if (isset($_SESSION['rucEmpresa']))
        {
            $this->login_empresa = new \Entidades\Sis00100($this->adapter);
            $this->login_empresa = $this->login_empresa->getMulti('ruc', $_SESSION['rucEmpresa']);
        }*/
    }
    
    public function exec()
    {
        $this->login();
    }
    
    public function login($param=array())
    {
        //Validar si ya esta logeado
        if (!empty($this->session->get('usuario'))) $this->redirect("default","index");

        //Verificar si es un evento POST o GET;
        if (isset($param["usuario"]))
        {
            $usuario = $param["usuario"];
            $pass = sha1($param["pass"]);
            if (isset($_SESSION['bdcliente']))
            {
                $model = new Models\Sis00300Model($this->adapter);
                $result = $model->getLogin($usuario, $pass);
                if ($result['Existe'] > 0)
                {
                    $control_actividad = new \Models\Sis50000Model($this->adapter);
                    $exist_actividad = $control_actividad->getExistUsuario($usuario,$_SESSION['bdcliente']);
                    $empresa = new \Models\Sis00100Model($this->adapter);
                    $dtempresa = $empresa->getWebsite($_SESSION['bdcliente']);
                    if ($dtempresa['ctrl_usuario']==1) {
                        //Variable de Session
                        $this->session->add('MismoUsuario', '1');
                    }
                    else
                    {
                        $this->session->add('MismoUsuario', '0');
                    }
                    if ($exist_actividad['numrows']==0 || $dtempresa['ctrl_usuario']==1)
                    {

                        $rucempresa = $model->getRucEmpresa($_SESSION['bdcliente']);
                        $this->session->add('usuario', $usuario);

                        switch($rucempresa['ruc']){

                            case '1891757995001': //allparts
                            $datosCliente=new Entidades\Sis00300($this->adapter);
                            $dtDatosCliente=$datosCliente->getMulti("usuario",$usuario);
                            $this->session->add('idUsuario',  $dtDatosCliente['id']);
                            $this->session->add('bodUsuario', strtoupper($dtDatosCliente['bodega']));
                            $this->session->add('codVendedor', $dtDatosCliente['cod_vendedor']);
                            $this->session->add('descVendedor', $dtDatosCliente['descuento']);
                            $this->session->add('rucEmpresa', $rucempresa['ruc']);
                                

                        break;

                            case '1890141281001': //cao
                              $this->session->add('rucEmpresa', $rucempresa['ruc']);
                            break;
                        }
                        
                        $rand = rand(1, 100);
                        $this->session->add('idRand', $rand);
                        $actividad = new Entidades\Sis50000($this->adapter);
                        $actividad->setBd($_SESSION['bdcliente']);
                        $actividad->setCon($rand);
                        $actividad->setFecha(date('Y-m-d H:m:s'));
                        $actividad->setFecha_actividad(date('Y-m-d H:m:s'));
                        $actividad->setUsuario($usuario);
                        $actividad->save();
                        echo '1';
                    }
                    else
                    {
                        echo 'El Usuario ya se encuentra ocupado por otra persona.';
                    }
                }
                else
                {
                    echo 'Usuario o Clave Erroneos, por favor vuelva a ingresar los datos';
                }
            } 
            else {
                $model = new \Models\Sis00050Model($this->adapter);
                $result = $model->getLogin($usuario, $pass);
                if ($result['Existe'] > 0)
                {
                    $this->cliente=$this->cliente->getCliente($usuario);
                    $control_actividad = new \Models\Sis50000Model($this->adapter);
                    $exist_actividad = $control_actividad->getExistUsuario($usuario,$this->cliente['bd']);
                    $empresa = new \Models\Sis00100Model($this->adapter);
                    $dtempresa = $empresa->getWebsite($this->cliente['bd']);
                    if ($dtempresa['ctrl_usuario']==1) {
                        //Variable de Session
                        $this->session->add('MismoUsuario', '1');
                    }
                    else
                    {
                        $this->session->add('MismoUsuario', '0');
                    }
                    if ($exist_actividad['numrows']==0 || $dtempresa['ctrl_usuario']==1)
                    {
                        //Variable de Session
                        $this->session->add('usuario', $usuario);
                        $this->session->add('bdcliente', $this->cliente['bd']);
                        $rucempresa = $model->getRucEmpresa($usuario);
                        
                        switch($rucempresa['ruc']){

                            case '1891757995001': //allparts
                                $datosCliente=new Entidades\Sis00300($this->adapter);
                                $dtDatosCliente=$datosCliente->getMulti("usuario",$usuario);
                                $this->session->add('idUsuario',  $dtDatosCliente['id']);
                                $this->session->add('bodUsuario', $dtDatosCliente['bodega']);
                                $this->session->add('codVendedor', $dtDatosCliente['cod_vendedor']);
                                $this->session->add('rucEmpresa', $rucempresa['ruc']);

                            break;

                            case '1890141281001': //cao
                              $this->session->add('rucEmpresa', $rucempresa['ruc']);
                            break;


                        }

                        $rand = rand(1, 100);
                        $this->session->add('idRand', $rand);

                        $actividad = new Entidades\Sis50000($this->adapter);
                        $actividad->setBd($this->cliente['bd']);
                        $actividad->setCon($rand);
                        $actividad->setFecha(date('Y-m-d H:m:s'));
                        $actividad->setFecha_actividad(date('Y-m-d H:m:s'));
                        $actividad->setUsuario($usuario);
                        $actividad->save();
                        echo 1;
                    }
                    else
                    {
                        echo 'El Usuario ya se encuentra ocupado por otra persona.';
                    }
                }
                else
                {
                    echo 'Usuario o Clave Erroneos, por favor vuelva a ingresar los datos';
                }
            }
        }
        else
        {
            if (isset($_SESSION['bdcliente']))
            {
                $empresa = new \Models\Sis00100Model($this->adapter);
                $dtempresa = $empresa->getWebsite($_SESSION['bdcliente']);
                //Imagen de la empresa                
                $login = $this->layout->loginAction(array(
                    'version'=>5,
                    'controller'=>'default',
                    'accion'=>'login',
                    'imgFondo'=> strlen($dtempresa['imgfondo'])>0?$dtempresa['imgfondo']:'/public/images/bck4.jpeg',
                    'imgLogo'=> strlen($dtempresa['logo'])>0?$dtempresa['logo']:'/public/images/logo-allpats.png',
                    'miniLogo'=> strlen($dtempresa['minilogo'])>0?$dtempresa['minilogo']:'/public/images/logo-allpats.png',
                ));
            }
            else
            {
                //  /public/images/logo3.jpg    logo
                //  /public/images/logim.jpg    minilogo
                //  /public/images/bck4.jpeg    Fondo
                //Imagen de Dtiware
                $login = $this->layout->loginAction(array(
                    'version'=>5,
                    'controller'=>'default',
                    'accion'=>'login',
                    'imgFondo'=>'/public/images/bck4.jpeg',
                    'imgLogo'=>'/public/images/logo-allpats.png',
                    'miniLogo'=>'/public/images/logo-allpats.png'
                ));
            }

            $this->render($this->website,__CLASS__,array(
                "layout"=>$login,
                "titulo"=>"Ingresar al Sistema",
            ));
        }
    }
    
    public function logout()
    {
        $idempresa = new \Entidades\Sis00059($this->adapter);
        $dtidempresa = $idempresa->getMulti('ruc', $this->session->get('rucEmpresa'));
        
        $actividad = new Entidades\Sis50000($this->adapter);
        $actividad->deleteMulti('con', $this->session->get('idRand'),'usuario',$this->session->get('usuario'));
        
        $this->session->remove('usuario');
        $this->session->remove('empresa');
        $this->session->remove('bdcliente');
        $this->session->remove('establecimiento');
        $this->session->remove('rucEmpresa');
        $this->session->remove('DTI_SEGCUENTA');
        $this->session->remove('DTI_SEGSEPARADOR');
        $this->session->remove('DTI_DECIMALVEN');
        $this->session->remove('DTI_DECIMALCOM');
        $this->session->remove('DTI_CARACTERDECIMAL');
        $this->session->remove('DTI_CARACTERMILES');
        $this->session->remove('idUsuario');
        $this->session->remove('bodUsuario');
        $this->session->remove('codVendedor');
        $this->session->remove('idCliente');
        $this->session->remove('nivelprecio');
        $this->session->remove('idSucursalCliente');
        $this->session->remove('idCarritoTemporal');
        $this->redirectLogout('_'.$dtidempresa['prefijo']);
    }
    
    public function index()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        
        //Validar si tiene permisos de empresas el cliente
        /*$validacion = new Models\Sis20010Model($this->adapter);
        if ($validacion->getPermisoModulo($this->session->get('usuario'), 'Empresa')['numrows']==0) 
        {
            $this->session->remove('usuario');
            $this->session->remove('empresa');
            $this->session->remove('bdcliente');
            $this->redirect("error","exec");
        }*/
        //Validar si el cliente tiene empresas configurado
        if(empty($this->session->get('bdcliente'))) { exit('No tiene seleccionado la base del cliente'); }
        $validacion = new Entidades\Sis00100($this->adapter);
        $validacion = $validacion->getCount();
        if ($validacion['numrows'] > 0)
        {
            if (!empty($this->session->get('empresa')))
            {
                $val_modulo = new \Entidades\Sis20012($this->adapter);
                $empresa = new \Entidades\Sis00100($this->adapter);
                $dtempresa = $empresa->getById($this->session->get('empresa'));
                
                $cliente = new Entidades\Sis00300($this->adapter);
                $dticliente = $cliente->getMulti('usuario', $this->session->get('usuario'));
                
                $val_modulo = $val_modulo->getCountMulti('id','ruc', $dtempresa['ruc']);
                
                if ($val_modulo['numrows'] > 1)
                {
                    //Si tiene modulos instalados
                    $val_plan = new \Entidades\Sis20013($this->adapter);
                    $val_plan = $val_plan->getCountMulti('id','ruc', $dtempresa['ruc']);
                    
                    if ($val_plan['numrows'] > 0)
                    {
                        $this->redirect('default', 'dashboard');
                    }
                    else
                    {
                        //No tiene planes instalados
                        $this->redirect('install', 'installplanes');
                    }
                }
                else
                {
                    //Si no tiene modulos instalados
                    $this->redirect('install', 'installmodulos');
                }
            }
            else
            {
                //No tiene seleccionada la empresa
                $this->redirect('default', 'selectempresa');
            }
        }
        else
        {
            //No tiene empresas proceso de nueva empresa
            $this->redirect('install', 'newempresa');
        }
    }
    
    public function selectempresa($param=array())
    {
        if (isset($_SESSION["empresa"])) $this->redirect("default","index");
        if (!isset($_SESSION["usuario"])) $this->redirect("default","login");
         //Verificar si es un evento POST o GET;
        if (isset($param["empresa"])) {
            $this->session->add('empresa', $param['empresa']);
            echo 1;
        }
        else{
            if (isset($_SESSION['bdcliente']))
            {
                $empresa = new \Models\Sis00100Model($this->adapter);
                $numEmpresas = $empresa->getCountMulti('id', 'bd', $this->session->get('bdcliente'));
                if ($numEmpresas['numrows']==1) {
                    $dtempresa = $empresa->getMulti('bd', $this->session->get('bdcliente'));
                    $this->session->add('empresa', $dtempresa['id']);
                    $this->redirect("default","index");
                }
                else {
                    $dtempresa = $empresa->getWebsite($this->session->get('bdcliente'));
                    //Imagen de la empresa
                    $empresa = $this->layout->empresaAction(array(
                        'version'=>3,
                        'imgFondo'=>$dtempresa['imgfondo'],
                        'imgLogo'=>$dtempresa['logo'],
                        'miniLogo'=>$dtempresa['minilogo']
                    ));
                }
            }
            else
            {
                //  /public/images/logo3.jpg    logo
                //  /public/images/logim.jpg    minilogo
                //  /public/images/bck4.jpeg    Fondo
                //Imagen de Dtiware
                $empresa = $this->layout->empresaAction(array(
                    'version'=>3,
                    'imgFondo'=>'/public/images/bck4.jpeg',
                    'imgLogo'=>'/public/images/logo-allpats.png',
                    'miniLogo'=>'/public/images/logo-allpats.png'
                ));
            }

            $this->render($this->website,__CLASS__,array(
                "layout"=>$empresa,
                "titulo"=>"Seleccionar Empresa",
            ));
        }
    }
    
    public function dashboard()
    {
        //Validar si ya esta logeado
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        
        $empresa = new \Models\Sis00100Model($this->adapter);
        $dtempresa = $empresa->getWebsite($this->session->get('bdcliente'));
        //Crear Varaibles Globales del sistema
        $this->session->add('DTI_SEGCUENTA', $dtempresa['segmentos_cuentas']); //segmentos_cuentas
        $this->session->add('DTI_SEGSEPARADOR', $dtempresa['separador_segmentos']); //separador_segmentos
        $this->session->add('DTI_DECIMALVEN', $dtempresa['decimales_ventas']); //decimales_ventas
        $this->session->add('DTI_DECIMALCOM', $dtempresa['decimales_compras']); //decimales_compras
        $this->session->add('DTI_CARACTERDECIMAL', $dtempresa['caracter_decimal']); //caracter_decimal
        $this->session->add('DTI_CARACTERMILES', $dtempresa['caracter_miles']); //caracter_miles

        //Variables Normales
        $this->session->add('CLIENTES', 'NORMAL');
        $this->session->add('ARTICULOS', 'NORMAL');

        switch ($dtempresa['decimales_ventas']) {
            case 1:
                $this->session->add('MASC_PRECIO', '000000000000000.0');
                break;
            case 2:
                $this->session->add('MASC_PRECIO', '000000000000000.00');
                break;
            case 3:
                $this->session->add('MASC_PRECIO', '000000000000000.000');
                break;
            case 4:
                $this->session->add('MASC_PRECIO', '000000000000000.0000');
                break;
            case 5:
                $this->session->add('MASC_PRECIO', '000000000000000.00000');
                break;
            default:
                $this->session->add('MASC_PRECIO', '000000000000000.00');
                break;
        }
        switch ($dtempresa['decimales_compras']) {
            case 1:
                $this->session->add('MASC_PRECIO', '000000000000000.0');
                break;
            case 2:
                $this->session->add('MASC_PRECIO', '000000000000000.00');
                break;
            case 3:
                $this->session->add('MASC_PRECIO', '000000000000000.000');
                break;
            case 4:
                $this->session->add('MASC_PRECIO', '000000000000000.0000');
                break;
            case 5:
                $this->session->add('MASC_PRECIO', '000000000000000.00000');
                break;
            default:
                $this->session->add('MASC_PRECIO', '000000000000000.00');
                break;
        }
        //Redireccionar al modulo instalado
        $valinstall = new \Entidades\Sis20012($this->adapter);
        //Tiene clientes optica
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 15,'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0) {
            $this->session->add('CLIENTES', 'OPTICA');
        }
        //Tiene clientes / articulos fe
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 24,'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0) {
            $this->session->add('CLIENTES', 'FE SOLO');
            $this->session->add('ARTICULOS', 'FE SOLO');
        }
        //Tiene articulos optica
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 14,'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0) {
            $this->session->add('ARTICULOS', 'OPTICA');
        }
        //Tiene articulo automotriz
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 17,'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0) {
            $this->session->add('ARTICULOS', 'AUTOMOTRIZ');
        }
        
        //*************************************
        //DashBoard de como maneja los Planes
        //*************************************
        
        //De acuerdo al tipo de empresa INTERNA o EXTERNA mostrar la informacion
        /*
         * INTERNA
         * -> Comprobantes Restantes
         * -> Ventas del Dia
         * -> Ventas de la Semana
         * -> Ventas del Mes
         * -> Ventas del Año
         * -> Clientes
         * -> Proveedores
         * -> Productos
         * 
         * EXTERNOS
         * -> Total de Comprobantes
         * -> Comprobantes por Mes
         * -> Comprobantes por Año
         * -> Clientes
         * -> Proveedores
         */
        
        //Planes Disponibles con la cantidad Disponible
        $dashboard = new Models\Sis20015Model($this->adapter);
        $dtdashboard = $dashboard->getPlanesActivos();
        
        $boxquickConstruida = '';
        
        if (globalFunctions::es_bidimensional($dtdashboard))
        {
            foreach ($dtdashboard as $dash)
            {
                if ($dash['descripcion'] == 'FE ERP')
                {
                    $this->redirect("fe","exec");
                }
                else
                {
                    $boxquickConstruida .= $this->layout->dashboardAction($dash['descripcion']);
                }
            }
        }
        else if (isset($dtdashboard['id']))
        {
            if ($dtdashboard['descripcion'] == 'FE ERP')
            {
                $this->redirect("fe","exec");
            }
            else
            {
                $boxquickConstruida .= $this->layout->dashboardAction($dtdashboard['descripcion']);
            }
        }
        
       // $script  = '<script src="public/js/modulos/guia/Bienvenida.js" type="text/javascript"></script>';
        
        $btngroup = new dti_builder_buttons();

        $btngroup->setGroupButtons(array(
                'funcionClick'=>true,
                'id'=>'btnAyuda',
                'icono'=>'fa fa-question',
                'titulo'=>'Ayuda',
                'btntitulo'=>'',
                'btnmensaje'=>'',
                'btn'=>array(),
            ));
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'layout_section_id'=>'mnuModulos',
            'section'=>array(
                'layout_header'=>$btngroup->getGroupButtons()['layout'],
                'layout_section'=>$boxquickConstruida,
            )
        ),$this->login_empresa);

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Bienvenido",
            'script'=>$btngroup->getGroupButtons()['script'],
            'modal'=>$btngroup->getGroupButtons()['modal'],
        ));
    }
    
    /**
     * MANEJO DE COMBOBOX
     */
    
    public function getEmpresa()
    {
        $result = '';
        $valDefecto = '';
        $empresa = new \Models\Sis20200Model($this->adapter);
        $this->dtempresa = $empresa->getEmpresas($this->session->get('usuario'));
        $result .= '<option value="0">Seleccionar Empresa</option>';
        if (globalFunctions::es_bidimensional($this->dtempresa)) {
            foreach ($this->dtempresa as $key => $value) {
                if (isset($_POST['get'])) {
                    if ($value["id"] == $_POST['get']) {
                        $result .= '<option value="'.$value["id"].'" '.$valDefecto.'>'.$value["nomempresa"].'</option>';
                    }else{
                        $result .= '<option value="'.$value["id"].'">'.$value["nomempresa"].'</option>';
                    }
                }else{
                    $result .= '<option value="'.$value["id"].'">'.$value["nomempresa"].'</option>';
                }
            }
        }else{
            if (isset($_POST['get'])) {
                if ($this->dtempresa["id"] == $_POST['get']) {
                    $result .= '<option value="'.$this->dtempresa["id"].'" '.$valDefecto.'>'.$this->dtempresa["nomempresa"].'</option>';
                }else{
                    $result .= '<option value="'.$this->dtempresa["id"].'">'.$this->dtempresa["nomempresa"].'</option>';
                }
            }else{
                    $result .= '<option value="'.$this->dtempresa["id"].'">'.$this->dtempresa["nomempresa"].'</option>';
                }
        }
        echo $result;
    }
    
    public function perfil()
    {
        $btngroup = new dti_builder_buttons();
        
        $btngroup->setGroupButtons(array(
            'id'=>'editPerfil',
            'modal'=>true,
            'icono'=>'fa fa-user',
            'titulo'=>'Editar Perfil',
            'controller'=>'default',
            'accion'=>'editPerfil',
            'url'=>'default/perfil',
            'outer'=>'true',
            'form'=>'true',
            'btn'=>array(),
        ));
        
        //Datos del cliente
        $cli = new Entidades\Sis00300($this->adapter);
        $dtcli = $cli->getMulti('usuario', $this->session->get('usuario'));
        
        $perfil = '<!-- ******HEADER****** -->
            <header class="header">
              <div class="container">
                <div class="teacher-name" style="padding-top:20px;">
                  <div class="row" style="margin-top:0px;">
                  <div class="col-md-9">
                    <h2 style="font-size:38px"><strong>demoopt</strong></h2>
                  </div>
                  <div class="col-md-3">
                    <div class="button" style="float:right;">
                    '.$btngroup->getGroupButtons()['layout'].'
                    </div>
                  </div>
                  </div>
                </div>

                <div class="row" style="margin-top:20px;">
                <!-- Image -->
                  <!--<div class="col-md-3">
                    <a href="#"> <img class="rounded-circle" src="images/kamal.jpg" alt="Kamal" style="width:200px;height:200px"></a>
                  </div>-->

                  <!-- Rank & Qualifications -->
                  <!--<div class="col-md-6"> 
                    <h5 style="color:#3AAA64">Associate Professor, <small>Dept. of CSE, Jatiya Kabi Kazi Nazrul Islam University</small></h5>
                    <p>PhD (On study at BUET), M.Sc. in research on ICT(UPC, Spain), M.Sc. in research on ICT(UCL, Belgium).</p>
                    <p>Address: Namapara, Trishal, Mymensingh</p>
                  </div>-->

                  <!-- Phone & Social -->
                  <!--<div class="col-md-3 text-center"> 
                    <span class="number" style="font-size:18px">Phone:<strong>+8801732226402</strong></span>
                    <div class="button" style="padding-top:18px">
                      <a href="mailto:ahmkctg@yahoo.com" class="btn btn-outline-success btn-block">Send Email</a>
                    </div>
                  </div>-->
                </div>
              </div>
            </header>
              <!--End of Header-->

          <!-- Main container -->
            <div class="container">
          <!-- Section:Datos Personales -->
            <div class="row">
              <div class="col-md-12">
                  <div class="card card-block">
                    <h2 class="card-title" style="color:#009688"><i class="fa fa-book fa-fw"></i>Datos Personales</h2>
                    <div style="height: 15px"></div>
                    <table class="table table-bordered">
                      <tbody>
                        <tr>
                          <th>Nombre</th>
                          <td>'.$dtcli['nombre'].' '.$dtcli['apellido'].'</td>
                        </tr>
                        <tr>
                          <th>Correo</th>
                          <td>'.$dtcli['correo'].'</td>
                        </tr>
                        <tr>
                          <th>Usuario</th>
                          <td>'.$dtcli['usuario'].'</td>
                        </tr>
                        <tr>
                          <th>Contraseña</th>
                          <td><a class="btn btn-outline-success btn-sm editClave" data-toggle="modal" data-target="#editClave">Cambiar Contraseña</a></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
          <!-- End:Datos Personales -->';
        
        dti_core::set('css', '<style>body{ background: #DAE3E7; }
                  html,body,h1,h2,h3,h4,h5,h6 {font-family: "Roboto", sans-serif}
                  div.card { box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 3px 10px 0 rgba(0, 0, 0, 0.1); }
                  .header { padding: 10px 0; background: #f5f5f5; border-top: 3px solid #3AAA64; }
                  .list-group { list-style: disc inside; }
                  .list-group-item { display: list-item; }
                  .find-more{ text-align: right; }
                  .label-theme{ background: #3AAA64; font-size: 14px; padding: .3em .7em .3em; color: #fff; border-radius: .25em; }
                  .label a{ color: inherit; }</style>');
        
        $modalClave = "<div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='editClave' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        <h2 class='modal-title' id='exampleModalLabel'>Editar Contraseña</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                                  </div>
                                  <div class='modal-body'>
                                        <div id='loadereditClave' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                    <div class='outer_diveditClave'></div>
                                  </div>
                                  <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary btn-lg' data-dismiss='modal'>Cancelar</button>
                                  </div>
                                </div>
                          </div>
                        </div>";
        
        dti_core::set('script', "<script>
                                $(function() {
                                    $(document).on('click','.editClave',function(e){
                                        $('#loadereditClave').fadeIn('slow');
                                         $.ajax({
                                                url:'default/editClave',
                                                data: {'panel':true,'url':'default/perfil'},
                                                type: 'post',
                                                beforeSend: function(objeto){
                                                    $('#loadereditClave').html(\"<img src='public/images/ajax-loader.gif'> Cargando...\");
                                                },
                                                success:function(data){
                                                    $('.outer_diveditClave').html(data).fadeIn('slow');
                                                    $('#loadereditClave').html('');
                                                }
                                            });
                                     });
                                });
                                </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=>$perfil,
            )
        ),$this->login_empresa);

        $this->render($this->website,__CLASS__,array(
            'layout'=>$contenedor,
            'titulo'=>'Perfil',
            'script'=>$btngroup->getGroupButtons()['script'],
            'modal'=>$btngroup->getGroupButtons()['modal'].$modalClave,
        ));
    }
    
    public function editClave($param=array())
    {
        //Btn Agrupados
        $btngroup = new dti_builder_buttons();

        $btngroup->setGroupButtons(array(
            'clic'=>'default/perfil',
            'enlace'=>true,
            'icono'=>'fa fa-reply',
            'titulo'=>'Regresar',
            'btntitulo'=>'',
            'btnmensaje'=>'',
            'btn'=>array(),
        ));

        $btngroup->setGroupButtons(array(
            'id'=>'savePerfil',
            'swal'=>true,
            'icono'=>'fa fa-floppy-o',
            'titulo'=>'Guardar',
            'clic'=>'setJsonClave("Update");',
        ));
        //--Formularios--
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        $formClientes->setForm($maestro->getMulti('formulario', 'frmEditClave'),'orden');
        $formulario =$formClientes->getForm();
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'default/jsonClave',
            'data'=>"{'clave': clave,'accionSql': accionSql}",
            'ok'=>'location.href="default/perfil"',
        ));
        $datos_clave = $dti_ajax->getAjax();
        
        $script = "<script type='text/javascript'>
                       function setJsonClave(accionSql=''){
                           //Agregar Validaciones
                           Swal.fire({
                               title: 'Desea Cambiar?',
                               text: 'Esta seguro que desea cambiar la contraseña!',
                               type: 'warning',
                               showCancelButton: true,
                               confirmButtonText: 'Cambiar',
                               showLoaderOnConfirm: true,
                               preConfirm: function() {
                               return new Promise(function(resolve) {
                                   var clave,claverep;
                                   clave = document.getElementById('txtclave').value;
                                   claverep = document.getElementById('txtclaveconfirmar').value;

                                   if (clave == claverep)
                                   {
                                       ".$datos_clave."
                                   }
                                   else
                                   {
                                       Swal.fire('Error!', 'Ambas claves deben ser iguales, digitar nuevamente!', 'error');
                                   }
                               })
                             },
                             allowOutsideClick: false
                           });
                       }</script>";
        
        echo $btngroup->getGroupButtons()['layout'].$formulario.$script;
    }
    
    public function editPerfil($param=array())
    {
        //Btn Agrupados
        $btngroup = new dti_builder_buttons();

        $btngroup->setGroupButtons(array(
            'clic'=>'default/perfil',
            'enlace'=>true,
            'icono'=>'fa fa-reply',
            'titulo'=>'Regresar',
            'btntitulo'=>'',
            'btnmensaje'=>'',
            'btn'=>array(),
        ));

        $btngroup->setGroupButtons(array(
            'id'=>'savePerfil',
            'swal'=>true,
            'icono'=>'fa fa-floppy-o',
            'titulo'=>'Guardar',
            'clic'=>'setJsonPerfil("Update");',
        ));
        
        //Id del cliente
        $cli = new Entidades\Sis00300($this->adapter);
        $dtcli = $cli->getMulti('usuario', $this->session->get('usuario'));
        //--Formularios--
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        $formClientes->setForm($maestro->getMulti('formulario', 'frmEditPerfil'),'orden',$dtcli['id']);
        $formulario =$formClientes->getForm();
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'default/jsonPerfil',
            'data'=>"{'nombre': nombre,'apellido': apellido,'correo': correo,'accionSql': accionSql}",
            'ok'=>'location.href="default/perfil"',
        ));
        $datos_perfil = $dti_ajax->getAjax();
        
        $script = "<script type='text/javascript'>
                       function setJsonPerfil(accionSql=''){
                           //Agregar Validaciones
                           Swal.fire({
                               title: 'Desea Cambiar?',
                               text: 'Esta seguro que desea cambiar la contraseña!',
                               type: 'warning',
                               showCancelButton: true,
                               confirmButtonText: 'Cambiar',
                               showLoaderOnConfirm: true,
                               preConfirm: function() {
                               return new Promise(function(resolve) {
                                   var nombre,apellido,correo;
                                   nombre = document.getElementById('txtnombre').value;
                                   apellido = document.getElementById('txtapellido').value;
                                   correo = document.getElementById('txtcorreo').value;

                                   if (nombre != '' && apellido != '' && correo != '')
                                   {
                                       ".$datos_perfil."
                                   }
                                   else
                                   {
                                       Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                   }
                               })
                             },
                             allowOutsideClick: false
                           });
                       }</script>";
        
        echo $btngroup->getGroupButtons()['layout'].$formulario.$script;
    }
    
    public function jsonClave($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert')
        {
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //#################Validaciones######################
            $entidad = new Entidades\Sis00300($this->adapter);
            $entidad->autocommit();
            
            $entidad->updateMultiColum('pass', sha1($param['clave']), 'usuario', $this->session->get('usuario'));
 
            $entidad->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Actualizados.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            $result = "OK";
            echo json_encode($result);
            exit();
        }
        else
        {
            $result = "ERROR";
            echo json_encode($result);
            exit();
        }
    }
    
    public function jsonPerfil($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert')
        {
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //#################Validaciones######################
            $entidad = new Entidades\Sis00300($this->adapter);
            $entidad->autocommit();
            
            $entidad->updateMultiColum('nombre', $param['nombre'], 'usuario', $this->session->get('usuario'));
            $entidad->updateMultiColum('apellido', $param['apellido'], 'usuario', $this->session->get('usuario'));
            $entidad->updateMultiColum('correo', $param['correo'], 'usuario', $this->session->get('usuario'));
 
            $entidad->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Actualizados.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            $result = "OK";
            echo json_encode($result);
            exit();
        }
        else
        {
            $result = "ERROR";
            echo json_encode($result);
            exit();
        }
    }

    public function config_theme($param=array()){

        $usuario= new \Entidades\Sis00300($this->adapter);

        switch ($param['op']){
            case 'actualizarThema':
               try {
                $usuario->updateMultiColum('dark_theme',$param['valor'],'usuario',$this->session->get('usuario'));
                echo '1';
               } catch (\Throwable $th) {
                  echo $th;
               }
            break;
            case 'boxed-layout':
            try {
                $usuario->updateMultiColum('boxed_layout',$param['valor'],'usuario',$this->session->get('usuario'));
                echo '1';
               } catch (\Throwable $th) {
                  echo $th;
               }
            break;
            case 'logo_theme';
            try {
                $usuario->updateMultiColum('logo_theme',$param['valor'],'usuario',$this->session->get('usuario'));
                echo '1';
               } catch (\Throwable $th) {
                  echo $th;
               }
            case 'navbar_theme';
            try {
                $usuario->updateMultiColum('navbar_theme',$param['valor'],'usuario',$this->session->get('usuario'));
                echo '1';
               } catch (\Throwable $th) {
                  echo $th;
               }
            break;

            case 'sidebar_theme';
            try {
                $usuario->updateMultiColum('sidebar_theme',$param['valor'],'usuario',$this->session->get('usuario'));
                echo '1';
               } catch (\Throwable $th) {
                  echo $th;
               }
            break;

            
            default:
               echo "No existe la funcion";
            break;

        }

    }
}
