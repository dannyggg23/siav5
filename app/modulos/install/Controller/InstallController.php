<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class InstallController extends Controllers
{
    private $session,$conectar,$adapter,$layout,$website;
    
    public function __construct()
    {
        $this->session = new Session();
        $this->session->init();
        //Conexion a la base de datos
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        //Validar que tenga permisos de seguir usando el sistema
        if (isset($_SESSION['usuario']) && isset($_SESSION['bdcliente']) && isset($_SESSION['idRand']) && isset($_SESSION['MismoUsuario']))
        {
            $validar_login = new Models\Sis50000Model($this->adapter);
            $dtvalidar_login = $validar_login->getSessionActiva($_SESSION['usuario'], $_SESSION['bdcliente'], $_SESSION['idRand']);
            if ($dtvalidar_login['numrows']==0 && $_SESSION['MismoUsuario']==0)
            {
                $this->redirect('default', 'logout');
            }
            else
            {
                $validar_login->updateMultiColum('fecha_actividad', date('Y-m-d H:m:s'), 'usuario', $_SESSION['usuario'], 'bd', $_SESSION['bdcliente'], 'con', $_SESSION['idRand']);
            }
        }
        //Traemos los datos del portal configurados
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        //Traemos los datos del cliente
        $this->cliente= new Models\Sis00300Model($this->adapter);
        //Cargamos el layout
        $this->layout = new dti_layout($this->website);
    }
    
    public function exec()
    {
        $this->index();
    }
    
    public function index()
    {
        $bienvenido = 'Bienvenido al Modulo Instalacion';

        $contenedor = globalFunctions::renderizar($this->website,array(
            'modulo'=>6,
            'section'=>array(
                'boxquick'=>$bienvenido,
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Bienvenido",
        ));
    }
    
    
    public function newempresa()
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("default","login"); }
        if (isset($_SESSION["empresa"])) { $this->redirect("default","selectempresa"); }
        //--Formularios--
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        if (!empty($param)) {
            $formClientes->setForm($maestro->getBy('formulario', 'frmEmpresa'),'orden',$param);
        }else{
            $formClientes->setForm($maestro->getBy('formulario', 'frmEmpresa'),'orden');
        }
        $formulario =$formClientes->getForm();
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>$formulario,
            'tip'=>'Datos Generales',
            'icono'=>'fa fa-user',
            'guardar'=>true,
            'onclic'=>'setJsonDatosGenerales("Insert")',
        ));
        
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        if (!empty($param)) {
            $formClientes->setForm($maestro->getBy('formulario', 'frmEmpresaConfig'),'orden',$param);
        }else{
            $formClientes->setForm($maestro->getBy('formulario', 'frmEmpresaConfig'),'orden');
        }
        $formulario =$formClientes->getForm();
        
        $step->setStep(array(
            'id'=>'step2',
            'contenido'=>$formulario,
            'tip'=>'Configuración Global',
            'icono'=>'fa fa-user',
            'guardar'=>true,
            'onclic'=>'setJsonConfiguracion("Update")',
        ));
        
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        if (!empty($param)) {
            $formClientes->setForm($maestro->getBy('formulario', 'frmEmpresaSmtp'),'orden',$param);
        }else{
            $formClientes->setForm($maestro->getBy('formulario', 'frmEmpresaSmtp'),'orden');
        }
        $formulario =$formClientes->getForm();
        
        $step->setStep(array(
            'id'=>'step3',
            'contenido'=>$formulario,
            'tip'=>'Configuración SMTP',
            'icono'=>'fa fa-user',
            'guardar'=>true,
            'onclic'=>'setJsonSMTP("Update")',
        ));
        
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        if (!empty($param)) {
            $formClientes->setForm($maestro->getBy('formulario', 'frmEmpresaLogo'),'orden',$param);
        }else{
            $formClientes->setForm($maestro->getBy('formulario', 'frmEmpresaLogo'),'orden');
        }
        $formulario =$formClientes->getForm();
        
        $step->setStep(array(
            'id'=>'step4',
            'contenido'=>$formulario,
            'tip'=>'Logo',
            'icono'=>'fa fa-book',
            'fin'=>true,
            'onclic'=>'setJsonLogo("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonDatosGenerales',
            'data'=>"{'ruc': ruc,'razonsocial':razonsocial,'nomempresa':nomempresa,'direccion':direccion,'telefono':telefono"
            . ",'rucrepresentante':rucrepresentante,'representante':representante,'obligaconta':obligaconta,'contriespecial':contriespecial"
            . ",'valcontriespecial':valcontriespecial,'correo':correo,'accionSql': accionSql}",
            'ok'=>'$("#smartwizard").smartWizard("next")',
        ));
        $datos_generales = $dti_ajax->getAjax();
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonConfiguraciones',
            'data'=>"{'ruc': ruc,'languageid':languageid,'template':template,'zona_horaria':zona_horaria,'moneda':moneda"
            . ",'caracter_decimal':caracter_decimal,'caracter_miles':caracter_miles,'segmentos_cuentas':segmentos_cuentas,'separador_segmentos':separador_segmentos"
            . ",'decimales_ventas':decimales_ventas,'decimales_compras':decimales_compras,'accionSql': accionSql}",
            'ok'=>'$("#smartwizard").smartWizard("next")',
        ));
        $datos_configuracion = $dti_ajax->getAjax();
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonSmtp',
            'data'=>"{'ruc': ruc,'smtp_hostname':smtp_hostname,'smtp_port':smtp_port,'smtp_username':smtp_username,'smtp_password':smtp_password
                       ,'smtpdefecto':smtpdefecto,'accionSql': accionSql}",
            'ok'=>'$("#smartwizard").smartWizard("next")',
        ));
        $datos_smtp = $dti_ajax->getAjax();

        \dti_core::set("script", "<script type='text/javascript'>
                    //Bloqueos por defecto
                    document.getElementById('txtvalcontriespecial').disabled = true;

                    $('#txtsmtpdefecto').change(function() {
                        if(this.checked)
                        {
                            document.getElementById('txtsmtp_hostname').disabled = true;
                            document.getElementById('txtsmtp_port').disabled = true;
                            document.getElementById('txtsmtp_username').disabled = true;
                            document.getElementById('txtsmtp_password').disabled = true;
                        }
                        else
                        {
                            document.getElementById('txtsmtp_hostname').disabled = false;
                            document.getElementById('txtsmtp_port').disabled = false;
                            document.getElementById('txtsmtp_username').disabled = false;
                            document.getElementById('txtsmtp_password').disabled = false;
                        }
                    });
                    
                    $('#txtcontriespecial').change(function() {
                        if(this.checked)
                        {
                            document.getElementById('txtvalcontriespecial').disabled = false;
                        }
                        else
                        {
                            document.getElementById('txtvalcontriespecial').disabled = true;
                        }
                    });
                    
                    function setJsonDatosGenerales(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar la nueva empresa!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var ruc,razonsocial,nomempresa,direccion,telefono,rucrepresentante,representante,obligaconta,contriespecial,valcontriespecial,correo;
                                    ruc = document.getElementById('txtruc').value;
                                    razonsocial = document.getElementById('txtrazonsocial').value;
                                    nomempresa = document.getElementById('txtnomempresa').value;
                                    direccion = document.getElementById('txtdireccion').value;
                                    telefono = document.getElementById('txttelefono').value;
                                    rucrepresentante = document.getElementById('txtrucrepresentante').value;
                                    representante = document.getElementById('txtrepresentante').value;
                                    obligaconta = document.getElementById('txtobligaconta').checked;
                                    contriespecial = document.getElementById('txtcontriespecial').checked;
                                    valcontriespecial = document.getElementById('txtvalcontriespecial').value;
                                    correo = document.getElementById('txtcorreo').value;

                                    if (validarCorreo(correo))
                                    {
                                        if (ruc != '' && razonsocial != '' && telefono != '' && nomempresa != '' && direccion != '' && rucrepresentante != '' && representante != '' && correo != '')
                                        {
                                            if (contriespecial) {
                                                if (valcontriespecial != '') {
                                                    Swal.fire('Error!', 'El valor de contribuyente especial es obligatorio!', 'error');
                                                }
                                            }
                                            ".$datos_generales."
                                        }
                                        else{
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                        }
                                    }
                                    else
                                    {
                                        Swal.fire('Error!', 'Debe Ingresar un correo valido!', 'error');
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }
                    
                    function setJsonConfiguracion(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar las configuraciones de la empresa!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var form = document.getElementById('frmsis00100');
                                    if (!form.checkValidity())
                                    {
                                        Swal.fire('Error!', 'Debe poner información correcta!', 'error');
                                    }
                                    else
                                    {
                                        var ruc,languageid,template,zona_horaria,moneda,caracter_decimal,caracter_miles,segmentos_cuentas
                                        ,separador_segmentos,decimales_ventas,decimales_compras;
                                        ruc = document.getElementById('txtruc').value;
                                        languageid = document.getElementById('txtlanguageid').value;
                                        template = document.getElementById('txttemplate').value;
                                        zona_horaria = document.getElementById('txtzona_horaria').value;
                                        moneda = document.getElementById('txtmoneda').value;
                                        caracter_decimal = document.getElementById('txtcaracter_decimal').value;
                                        caracter_miles = document.getElementById('txtcaracter_miles').value;
                                        segmentos_cuentas = document.getElementById('txtsegmentos_cuentas').value;
                                        separador_segmentos = document.getElementById('txtseparador_segmentos').value;
                                        decimales_ventas = document.getElementById('txtdecimales_ventas').value;
                                        decimales_compras = document.getElementById('txtdecimales_compras').value;

                                        if (languageid > 0 && template > 0 && zona_horaria > 0 && moneda > 0 
                                            && caracter_decimal != '' && caracter_miles != '' && segmentos_cuentas != ''
                                            && separador_segmentos != '' && decimales_ventas != '' && decimales_compras != '')
                                        {
                                            if(caracter_decimal != caracter_miles)
                                            {
                                                ".$datos_configuracion."
                                            }
                                            else
                                            {
                                                Swal.fire('Error!', 'El Caracter de miles y decimal deben ser diferentes!', 'error');
                                            }
                                        }
                                        else
                                        {
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                        }
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }

                    function setJsonSMTP(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro la informacion SMTP de la empresa!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var ruc,smtp_hostname,smtp_port,smtp_username,smtp_password,smtpdefecto;
                                    ruc = document.getElementById('txtruc').value;
                                    smtp_hostname = document.getElementById('txtsmtp_hostname').value;
                                    smtp_port = document.getElementById('txtsmtp_port').value;
                                    smtp_username = document.getElementById('txtsmtp_username').value;
                                    smtp_password = document.getElementById('txtsmtp_password').value;
                                    smtpdefecto = document.getElementById('txtsmtpdefecto').checked;

                                    if (smtpdefecto)
                                    {
                                        ".$datos_smtp."
                                    }
                                    else if(smtp_hostname != '' && smtp_port != '' && smtp_username != '' && smtp_password != '')
                                    {
                                        ".$datos_smtp."
                                    }
                                    else
                                    {
                                        Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }

                    function setJsonLogo(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar el logo!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var ruc,logo;
                                    var imagen_data = new FormData();
                                    ruc = document.getElementById('txtruc').value;
                                    imagen_data.append('logo',$('#txtlogo')[0].files[0]);
                                    imagen_data.append('ruc',ruc);
                                    $.ajax({
                                        url: 'install/jsonlogo',
                                        data: imagen_data,
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',
                                        dataType: 'json',
                                        success: function(data){
                                            if (data.status != 'OK')
                                            {
                                                Swal.fire('Error!', ''+data.descripcion+'!', 'error');
                                            }
                                            else
                                            {
                                                Swal.fire('Correcto!', 'Logo Actualizado!', 'success');
                                                location.href = 'default/index';
                                            }
                                        }
                                      });
                                })
                            },
                            allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Crear Nueva Empresa',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

      

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Nueva Empresa",
        ));
    }
    
    public function installModulos()
    {
        //Validar si ya esta logeado
        if (!isset($_SESSION["usuario"])) $this->redirect("default","login");
        //Validar si tiene permiso
        $rol = new \Models\Sis00200Model($this->adapter);
        $dtrol = $rol->getPermisoVentana($_SESSION['usuario'],'installmodulos');       
        if ($dtrol['numrows']>0)
        {
            $content = '';
            $btninstalado = '';
            //****************************
            //Modulo de Facturacion
            //****************************
            $instalado = new Entidades\Sis20012($this->adapter);
            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $modulos = new \Entidades\Sis00060($this->adapter);
            $dtmodulos = $modulos->getAllOrderBy('orden','activo','1','desc');
            if (globalFunctions::es_bidimensional($dtmodulos))
            {
                foreach ($dtmodulos as $mod)
                {
                    $btninstalado = '';
                    $btninstalado = '';$image = '';
                    //$dtinstalado = $instalado->getCountMulti('id', 'modulesid', $mod['id'],'ruc',$empresa['ruc'],'clienteid',$cliente['id']);
                    $dtinstalado = $instalado->getCountMulti('id', 'modulesid', $mod['id'],'ruc',$empresa['ruc']);

                    if ($dtinstalado['numrows']>0) $btninstalado = '<a class="btn btn-primary disabled">Instalado</a>'; else $btninstalado = '<a href="install/'.$mod['accion'].'" class="btn btn-primary">Instalar</a>';

                    //1. Si instalo FE no puede instalar FE ERP o viceversa controlar
                    //$valFE = $instalado->getCountMulti('id', 'ruc',$empresa['ruc'],'clienteid',$cliente['id'],'modulesid',2);
                    //$valFEerp = $instalado->getCountMulti('id', 'ruc',$empresa['ruc'],'clienteid',$cliente['id'],'modulesid',3);
                    $valFE = $instalado->getCountMulti('id', 'ruc',$empresa['ruc'],'modulesid',2);
                    $valFEerp = $instalado->getCountMulti('id', 'ruc',$empresa['ruc'],'modulesid',3);

                    //Mostar la data
                    $content .= '<!-- Card -->
                            <div class="card col-lg-3 col-md-5 col-sm-6 col-xs-12">
                              <!-- Card image -->
                              <div class="view overlay">
                                <div class="card-header" data-background-color="green">
                                    <h4 class="card-title"><i class="fa fa-usd"></i>'.$mod['nombre'].'</h4>
                                  </div>
                              </div>
                              <!-- Card content -->
                              <div class="card-body">
                                <!-- Text -->
                                <p class="card-text">'.$mod['descripcion'].'</p>
                                <!-- Button -->
                                '.$btninstalado.'
                              </div>
                            </div>
                            <!-- Card -->';
                }
            }
            else if(isset($dtmodulos['id']))
            {
                $image = '';
                if ($dtinstalado['numrows']>0) $btninstalado = '<a class="btn btn-primary disabled">Instalado</a>'; else $btninstalado = '<a href="install/'.$dtmodulos['accion'].'" class="btn btn-primary">Instalar</a>';
                if (strlen($dtmodulos['imagen'])>5) $image = $dtmodulos['imagen']; else $image = 'https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg';

                //1. Si instalo FE no puede instalar FE ERP o viceversa controlar
                //$valFE = $instalado->getCountMulti('id', 'ruc',$empresa['ruc'],'clienteid',$cliente['id'],'modulesid',2);
                //$valFEerp = $instalado->getCountMulti('id', 'ruc',$empresa['ruc'],'clienteid',$cliente['id'],'modulesid',3);
                $valFE = $instalado->getCountMulti('id', 'ruc',$empresa['ruc'],'modulesid',2);
                $valFEerp = $instalado->getCountMulti('id', 'ruc',$empresa['ruc'],'modulesid',3);

                if ($valFE['numrows']>0 && $dtmodulos['id']==3) {
                    $btninstalado = '<a class="btn btn-primary disabled">Ya Tiene FE</a>';
                }

                if ($valFEerp['numrows']>0 && $dtmodulos['id']==2) {
                    $btninstalado = '<a class="btn btn-primary disabled">Ya Tiene FE ERP</a>';
                }

                //Mostar la data

                $content .= '<!-- Card -->
                        <div class="card col-lg-3 col-md-5 col-sm-6 col-xs-12">
                          <!-- Card image -->
                          <div class="view overlay">
                            <img class="card-img-top" src="'.$image.'" alt="Card image cap">
                            <a href="#!">
                              <div class="mask rgba-white-slight"></div>
                            </a>
                          </div>
                          <!-- Card content -->
                          <div class="card-body">
                            <!-- Title -->
                            <h4 class="card-title">'.$dtmodulos['nombre'].'</h4>
                            <!-- Text -->
                            <p class="card-text">'.$dtmodulos['descripcion'].'</p>
                            <!-- Button -->
                            '.$btninstalado.'
                          </div>
                        </div>
                        <!-- Card -->';
            }

            $contenedor = globalFunctions::renderizar($this->website,array(
                'section'=>array(
                    'manual'=>$content,
                )
            ));
            
            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalación de Modulos",
            ));
        }
        else
        {
            $this->redirect('error','exec');
        }
    }
    
    public function installPlanes()
    {
        //Validar si tiene permiso
        $rol = new \Models\Sis00200Model($this->adapter);
        $dtrol = $rol->getPermisoVentana($_SESSION['usuario'],'installplanes');
        if ($dtrol['numrows']>0)
        {
            $content = '';
            $btninstalado = '';
            //****************************
            //Planes segun lo instalado
            //****************************
            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $modulos = new Entidades\Sis20012($this->adapter);
            //$dtmodulos = $modulos->getMulti('ruc',$empresa['ruc'],'clienteid',$cliente['id']);
            $dtmodulos = $modulos->getMulti('ruc',$empresa['ruc']);

            $instalado = new Entidades\Sis20013($this->adapter);

            if (globalFunctions::es_bidimensional($dtmodulos))
            {
                foreach ($dtmodulos as $mod) {
                    $planes = new \Entidades\Sis00061($this->adapter);
                    $dtplanes = $planes->getMulti('sis00060id', $mod['modulesid']);

                    if (globalFunctions::es_bidimensional($dtplanes))
                    {
                        foreach ($dtplanes as $plan) {
                            $btninstalado = '<a onClick="setJsonPlan(\''.$plan['id'].'\')" class="btn btn-primary">Comprar</a>';
                            if (strlen($plan['imagen'])>5) $image = $plan['imagen']; else $image = 'https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg';

                            //1. Bloqueamos los planes que solo se pueden instalar 1 vez
                            $comprado = new Entidades\Sis20013($this->adapter);
                            $valcomprado = $comprado->getCountMulti('id', 'planid', $plan['id'],'ruc',$empresa['ruc']);
                            if ($plan['id'] == 4 && $valcomprado['numrows']>0) {
                                $btninstalado = '<a class="btn btn-primary">Solo una vez</a>';
                            }

                            $content .= '<!-- Card -->
                                    <div class="card col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                      <!-- Card image -->
                                      <div class="view overlay">
                                        <img class="card-img-top" src="'.$image.'" alt="Card image cap">
                                        <a href="#!">
                                          <div class="mask rgba-white-slight"></div>
                                        </a>
                                      </div>
                                      <!-- Card content -->
                                      <div class="card-body">
                                        <!-- Title -->
                                        <h4 class="card-title">'.$plan['nombre_plan'].'<!--<br />Cantidad: '.$plan['cantidad'].'--></h4>
                                        <!-- Text -->
                                        <p class="card-text">'.$plan['descripcion'].'</p>
                                        <!-- Button -->
                                        Precio: <b>$ '.number_format((float)$plan['costo'], 2, '.', '').'</b><br />
                                        '.$btninstalado.'
                                      </div>
                                    </div>
                                    <!-- Card -->';
                        }
                    }
                    else if(isset($dtplanes['id']))
                    {
                        $btninstalado = '<a onClick="setJsonPlan(\''.$dtplanes['id'].'\')" class="btn btn-primary">Comprar</a>';
                        if (strlen($dtplanes['imagen'])>5) $image = $dtplanes['imagen']; else $image = 'https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg';

                        //1. Bloqueamos los planes que solo se pueden instalar 1 vez
                        $comprado = new Entidades\Sis20013($this->adapter);
                        $valcomprado = $comprado->getCountMulti('id', 'planid', $dtplanes['id'],'ruc',$empresa['ruc']);
                        if ($dtplanes['id'] == 4 && $valcomprado['numrows']>0) {
                            $btninstalado = '<a class="btn btn-primary">Solo una vez</a>';
                        }

                        $content .= '<!-- Card -->
                                <div class="card col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                  <!-- Card image -->
                                  <div class="view overlay">
                                    <img class="card-img-top" src="'.$image.'" alt="Card image cap">
                                    <a href="#!">
                                      <div class="mask rgba-white-slight"></div>
                                    </a>
                                  </div>
                                  <!-- Card content -->
                                  <div class="card-body">
                                    <!-- Title -->
                                    <h4 class="card-title">'.$dtplanes['nombre_plan'].'<!--<br />Cantidad: '.$dtplanes['cantidad'].'--></h4>
                                    <!-- Text -->
                                    <p class="card-text">'.$dtplanes['descripcion'].'</p>
                                    <!-- Button -->
                                    Precio: <b>$ '.number_format((float)$dtplanes['costo'], 2, '.', '').'</b><br />
                                    '.$btninstalado.'
                                  </div>
                                </div>
                                <!-- Card -->';
                    }
                }
            }
            else if (isset($dtmodulos['id']))
            {
                $planes = new \Entidades\Sis00061($this->adapter);
                $dtplanes = $planes->getMulti('sis00060id', $dtmodulos['modulesid']);

                if (globalFunctions::es_bidimensional($dtplanes))
                {
                    foreach ($dtplanes as $plan) {
                        $btninstalado = '<a onClick="setJsonPlan(\''.$plan['id'].'\')" class="btn btn-primary">Comprar</a>';
                        if (strlen($plan['imagen'])>5) $image = $plan['imagen']; else $image = 'https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg';

                        //1. Bloqueamos los planes que solo se pueden instalar 1 vez
                        $comprado = new Entidades\Sis20013($this->adapter);
                        $valcomprado = $comprado->getCountMulti('id', 'planid', $plan['id']);
                        if ($plan['id'] == 4 && $valcomprado['numrows']>0) {
                            $btninstalado = '<a class="btn btn-primary">Solo una vez</a>';
                        }

                        $content .= '<!-- Card -->
                                <div class="card col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                  <!-- Card image -->
                                  <div class="view overlay">
                                    <img class="card-img-top" src="'.$image.'" alt="Card image cap">
                                    <a href="#!">
                                      <div class="mask rgba-white-slight"></div>
                                    </a>
                                  </div>
                                  <!-- Card content -->
                                  <div class="card-body">
                                    <!-- Title -->
                                    <h4 class="card-title">'.$plan['nombre_plan'].'<!--<br />Cantidad: '.$plan['cantidad'].'--></h4>
                                    <!-- Text -->
                                    <p class="card-text">'.$plan['descripcion'].'</p>
                                    <!-- Button -->
                                    Precio: <b>$ '.number_format((float)$plan['costo'], 2, '.', '').'</b><br />
                                    '.$btninstalado.'
                                  </div>
                                </div>
                                <!-- Card -->';
                    }
                }
                else if(isset($dtplanes['id']))
                {
                    $btninstalado = '<a onClick="setJsonPlan(\''.$dtplanes['id'].'\')" class="btn btn-primary">Comprar</a>';
                    if (strlen($dtplanes['imagen'])>5) $image = $dtplanes['imagen']; else $image = 'https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg';

                    //1. Bloqueamos los planes que solo se pueden instalar 1 vez
                    $comprado = new Entidades\Sis20013($this->adapter);
                    $valcomprado = $comprado->getCountMulti('id', 'planid', $dtplanes['id']);
                    if ($dtplanes['id'] == 4 && $valcomprado['numrows']>0) {
                        $btninstalado = '<a class="btn btn-primary">Solo una vez</a>';
                    }

                    $content .= '<!-- Card -->
                            <div class="card col-lg-3 col-md-5 col-sm-6 col-xs-12">
                              <!-- Card image -->
                              <div class="view overlay">
                                <img class="card-img-top" src="'.$image.'" alt="Card image cap">
                                <a href="#!">
                                  <div class="mask rgba-white-slight"></div>
                                </a>
                              </div>
                              <!-- Card content -->
                              <div class="card-body">
                                <!-- Title -->
                                <h4 class="card-title">'.$dtplanes['nombre_plan'].'<!--<br />Cantidad: '.$dtplanes['cantidad'].'--></h4>
                                <!-- Text -->
                                <p class="card-text">'.$dtplanes['descripcion'].'</p>
                                <!-- Button -->
                                Precio: <b>$ '.number_format((float)$dtplanes['costo'], 2, '.', '').'</b><br />
                                '.$btninstalado.'
                              </div>
                            </div>
                            <!-- Card -->';
                }
            }

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/comprarPlan',
                'data'=>"{'plan' : plan,'accionSql': 'Insert'}",
                'ok'=>'location.href="install/listPlan"',
            ));
            $datos_plan = $dti_ajax->getAjax();

            \dti_core::set("script", "  <script type='text/javascript'>
                    function setJsonPlan(plan){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Comprar?',
                            text: 'Esta seguro que desea adquirir el plan seleccionado!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_plan."
                                })
                            },
                            allowOutsideClick: false
                        });
                    }
                    </script>");

            $notifi = ' <div class="alert alert-info alert-dismissible fade show" role="alert">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                           Fondos Actuales: $0.00
                       </div><br /><br />';

            //Btn Agrupados
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'default/index',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngroup->setGroupButtons(array(
                    'clic'=>'install/listPlan',
                    'enlace'=>true,
                    'icono'=>'fa fa-home',
                    'titulo'=>'Administrar Planes',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngroup->setGroupButtons(array(
                    'clic'=>'seguridad/listFondos',
                    'enlace'=>true,
                    'icono'=>'fa fa-home',
                    'titulo'=>'Administrar Fondos',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngroup->setGroupButtons(array(
                    'clic'=>'seguridad/addfondos',
                    'enlace'=>true,
                    'icono'=>'fa fa-usd',
                    'titulo'=>'Añadir Fondos',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();

            $contenedor = globalFunctions::renderizar($this->website,array(
                'section'=>array(
                    'layout'=>$btngrp['layout'],
                    'layout_header'=>$notifi,
                    'manual'=>$content,
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalación de Planes",
                'script'=>$btngrp['script'],
                'modal'=>$btngrp['modal'],
            ));
        }
        else
        {
            $this->redirect('error','exec');
        }
    }

    public function installFe()
    {
        //--Formularios--
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        if (!empty($param)) {
            $formClientes->setForm($maestro->getMulti('formulario', 'frmFirmaElectronica'),'orden',$param);
        }else{
            $formClientes->setForm($maestro->getMulti('formulario', 'frmFirmaElectronica'),'orden');
        }
        $formulario =$formClientes->getForm();
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>$formulario,
            'tip'=>'Firma Electronica',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonFirmaElectronica("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonFirmaelectronica',
            'data'=>"{'ambiente': ambiente,'firma':firma,'clave':clave,'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    
                    function setJsonFirmaElectronica(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea instalar la firma electronica!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                var ambiente,firma,clave;
                                ambiente = document.getElementById('txtambiente').value;
                                clave = document.getElementById('txtclavefirma').value;

                                if (ambiente > 0 && clave != '')
                                {
                                    var imagen_data = new FormData();
                                    imagen_data.append('firma',$('#txtfirma')[0].files[0]);
                                    if ($('#txtfirma')[0].files.length > 0) {
                                        $.ajax({
                                            url: 'install/jsonfirma',
                                            data: imagen_data,
                                            processData: false,
                                            contentType: false,
                                            type: 'POST',
                                            dataType: 'json',
                                            success: function(data){
                                                if (data.status === 'OK')
                                                {
                                                    ".$datos_firma."
                                                }
                                                else
                                                {
                                                    Swal.fire('Error!', ''+data.descripcion+'!', 'error');
                                                }
                                            }
                                          });
                                    }
                                    else
                                    {
                                        Swal.fire('Error!', 'El archivo de la firma es obligatorio!', 'error');
                                    }
                                }
                                else{
                                    Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                }
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar FE',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar FE",
        ));
    }
    
    public function installFeErp()
    {
        //--Formularios--
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        if (!empty($param)) {
            $formClientes->setForm($maestro->getMulti('formulario', 'frmFirmaElectronica'),'orden',$param);
        }else{
            $formClientes->setForm($maestro->getMulti('formulario', 'frmFirmaElectronica'),'orden');
        }
        $formulario =$formClientes->getForm();
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>$formulario,
            'tip'=>'Firma Electronica',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonFirmaElectronica("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonFirmaelectronicaErp',
            'data'=>"{'ambiente': ambiente,'firma':firma,'clave':clave,'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    
                    function setJsonFirmaElectronica(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea instalar la firma electronica!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                var ambiente,firma,clave;
                                ambiente = document.getElementById('txtambiente').value;
                                clave = document.getElementById('txtclavefirma').value;

                                if (ambiente > 0 && clave != '')
                                {
                                    var imagen_data = new FormData();
                                    imagen_data.append('firma',$('#txtfirma')[0].files[0]);
                                    if ($('#txtfirma')[0].files.length > 0) {
                                        $.ajax({
                                            url: 'install/jsonfirma',
                                            data: imagen_data,
                                            processData: false,
                                            contentType: false,
                                            type: 'POST',
                                            dataType: 'json',
                                            success: function(data){
                                                if (data.status === 'OK')
                                                {
                                                    ".$datos_firma."
                                                }
                                                else
                                                {
                                                    Swal.fire('Error!', ''+data.descripcion+'!', 'error');
                                                }
                                            }
                                          });
                                    }
                                    else
                                    {
                                        Swal.fire('Error!', 'El archivo de la firma es obligatorio!', 'error');
                                    }
                                }
                                else{
                                    Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                }
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar FE ERP',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar FE ERP",
        ));
    }
    
    public function installFeSolo()
    {
        //--Formularios--
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        if (!empty($param)) {
            $formClientes->setForm($maestro->getMulti('formulario', 'frmEstablecimiento'),'orden',$param);
        }else{
            $formClientes->setForm($maestro->getMulti('formulario', 'frmEstablecimiento'),'orden');
        }
        $formulario =$formClientes->getForm();

        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step2',
            'contenido'=>$formulario,
            'tip'=>'Establecimiento Ventas',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonEstablecimiento("Insert")',
        ));    
        
        //--Formularios--
        $formClientes = new dti_builder_form($this->adapter);
        $maestro = new Entidades\Sis40120($this->adapter);
        if (!empty($param)) {
            $formClientes->setForm($maestro->getMulti('formulario', 'frmFirmaElectronica'),'orden',$param);
        }else{
            $formClientes->setForm($maestro->getMulti('formulario', 'frmFirmaElectronica'),'orden');
        }
        $formulario =$formClientes->getForm();

        $step->setStep(array(
            'id'=>'step3',
            'contenido'=>$formulario,
            'tip'=>'Firma Electronica',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonFirmaElectronica("Update")',
        ));  
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonFirmaelectronicaSolo',
            'data'=>"{'ambiente': ambiente,'firma':firma,'clave':clave,'accionSql': accionSql}",
            'ok'=>'$("#smartwizard").smartWizard("next")',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    
                    function setJsonFirmaElectronica(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea instalar la firma electronica!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                var ambiente,firma,clave;
                                ambiente = document.getElementById('txtambiente').value;
                                clave = document.getElementById('txtclavefirma').value;

                                if (ambiente > 0 && clave != '')
                                {
                                    var imagen_data = new FormData();
                                    imagen_data.append('firma',$('#txtfirma')[0].files[0]);
                                    if ($('#txtfirma')[0].files.length > 0) {
                                        $.ajax({
                                            url: 'install/jsonfirma',
                                            data: imagen_data,
                                            processData: false,
                                            contentType: false,
                                            type: 'POST',
                                            dataType: 'json',
                                            success: function(data){
                                                if (data.status === 'OK')
                                                {
                                                    ".$datos_firma."
                                                }
                                                else
                                                {
                                                    Swal.fire('Error!', ''+data.descripcion+'!', 'error');
                                                }
                                            }
                                          });
                                    }
                                    else
                                    {
                                        Swal.fire('Error!', 'El archivo de la firma es obligatorio!', 'error');
                                    }
                                }
                                else{
                                    Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                }
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $formulario = '';
        $formulario .= '<form id="dti_validate" name="dti_validate"><table class="table table bordered"  style="width: 100%;"><tbody>';
        $formulario .= '<tr>
                            <th>Documento</th>
                            <th>Punto Emision</th>
                            <th>Secuencial</th>
                        </tr>';
        $formulario .= '<tr>
                            <td>Factura</td>
                            <td><input type="text" id="txtfactptoemision" name="txtfactptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3" /></td>
                            <td><input type="text" id="txtfactsecuencial" name="txtfactsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                        </tr>';
        $formulario .= '<tr>
                            <td>Notas de Crédito</td>
                            <td><input type="text" id="txtncptoemision" name="txtncptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                            <td><input type="text" id="txtncsecuencial" name="txtncsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                        </tr>';
        $formulario .= '<tr>
                        <td>Notas de Débito</td>
                        <td><input type="text" id="txtndptoemision" name="txtndptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                        <td><input type="text" id="txtndsecuencial" name="txtndsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                    </tr>';
        $formulario .= '<tr>
                            <td>Comprobantes de Retención</td>
                            <td><input type="text" id="txtretenptoemision" name="txtretenptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                            <td><input type="text" id="txtretensecuencial" name="txtretensecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                        </tr>';
        $formulario .= '<tr>
                            <td>Guias de Remisión</td>
                            <td><input type="text" id="txtgrptoemision" name="txtgrptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                            <td><input type="text" id="txtgrsecuencial" name="txtgrsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                        </tr>';
        $formulario .= '<tr>
                            <td>Cobros</td>
                            <td><input type="text" id="txtccptoemision" name="txtccptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                            <td><input type="text" id="txtccsecuencial" name="txtccsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                        </tr>';
        $formulario .= '</tbody></table></form>';
        
        $step->setStep(array(
            'id'=>'step4',
            'contenido'=>$formulario,
            'tip'=>'Secuencial',
            'icono'=>'fa fa-user',
            'guardar'=>true,
            'onclic'=>'setJsonSecuencial("Update")',
        ));

        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonEstablecimiento',
            'data'=>"{'establecimiento': establecimiento,'direccion':direccion,'secuencial':secuencial,'fe':fe,'activo':activo,'accionSql': accionSql}",
            'ok'=>'$("#smartwizard").smartWizard("next")',
        ));
        $datos_establecimiento = $dti_ajax->getAjax();

        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonSecuencialOptica',
            'data'=>"{'txtfactptoemision' : txtfactptoemision,
                    'txtfactsecuencial' : txtfactsecuencial,
                    'txtncptoemision' : txtncptoemision,'txtncsecuencial' : txtncsecuencial,
                    'txtndptoemision' : txtndptoemision,
                    'txtndsecuencial' : txtndsecuencial,
                    'txtretenptoemision' : txtretenptoemision,'txtretensecuencial' : txtretensecuencial,
                    'txtgrptoemision' : txtgrptoemision,
                    'txtgrsecuencial' : txtgrsecuencial,
                    'txtccptoemision' : txtccptoemision,
                    'txtccsecuencial' : txtccsecuencial,
                    'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_secuencial = $dti_ajax->getAjax();        

        \dti_core::set("script", "<script type='text/javascript'>                   
                    function setJsonEstablecimiento(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar el establecimiento!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var form = document.getElementById('frmcc00020');
                                    if (!form.checkValidity())
                                    {
                                        Swal.fire('Error!', 'Debe poner información correcta!', 'error');
                                    }
                                    else
                                    {
                                        var establecimiento,direccion,secuencial,fe,activo;
                                        establecimiento = document.getElementById('txtestablecimiento').value;
                                        direccion = document.getElementById('txtdireccion').value;
                                        secuencial = document.getElementById('txtsecuencial').value;
                                        fe = document.getElementById('txtfe').checked;
                                        activo = document.getElementById('txtactivo').checked;
                                    
                                        if (establecimiento != '' && direccion != '' && secuencial != '')
                                        {
                                            ".$datos_establecimiento."
                                        }
                                        else{
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                        }
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }

                    function setJsonSecuencial(accionSql=''){
                         //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar el secuencial!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var form = document.getElementById('dti_validate');
                                    if (!form.checkValidity())
                                    {
                                        Swal.fire('Error!', 'Debe poner información correcta!', 'error');
                                    }
                                    else
                                    {
                                        //Agregar Validaciones
                                        var txtfactptoemision = document.getElementById('txtfactptoemision').value;
                                        var txtfactsecuencial = document.getElementById('txtfactsecuencial').value;
                                        var txtncptoemision = document.getElementById('txtncptoemision').value;
                                        var txtncsecuencial = document.getElementById('txtncsecuencial').value;
                                        var txtndptoemision = document.getElementById('txtndptoemision').value;
                                        var txtndsecuencial = document.getElementById('txtndsecuencial').value;
                                        var txtretenptoemision = document.getElementById('txtretenptoemision').value;
                                        var txtretensecuencial = document.getElementById('txtretensecuencial').value;
                                        var txtgrptoemision = document.getElementById('txtgrptoemision').value;
                                        var txtgrsecuencial = document.getElementById('txtgrsecuencial').value;
                                        var txtccptoemision = document.getElementById('txtccptoemision').value;
                                        var txtccsecuencial = document.getElementById('txtccsecuencial').value;

                                        if (txtfactptoemision != '' && txtfactsecuencial != '' && txtncptoemision != ''
                                        && txtncsecuencial != '' && txtndptoemision != '' && txtndsecuencial != '' && txtgrsecuencial != ''
                                        && txtretenptoemision != '' && txtretensecuencial != '' && txtgrptoemision != ''
                                        && txtccptoemision != '' && txtccsecuencial != '') {
                                            ".$datos_secuencial."
                                        }
                                        else{
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                        }
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }
                    </script>");

        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar FE Solo',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar FE Solo",
        ));
    }
    
    public function installGastos()
    {
        //--Formularios--
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Gastos',
            'tip'=>'Gastos',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonGastos("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonGastos',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonGastos(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de GASTOS!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_firma."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Gastos',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Gastos",
        ));
    }
    
    public function installPromociones()
    {
        //--Formularios--
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Promociones',
            'tip'=>'Promociones',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonPromociones("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonPromociones',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonPromociones(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de PROMOCIONES!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_firma."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Promociones',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Promociones",
        ));
    }
    
    public function installPos()
    {
        //--Formularios--
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Pos',
            'tip'=>'Pos',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonGastos("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonPos',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonGastos(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de POS!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_firma."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Pos',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Pos",
        ));
    }
    
    public function installContabilidad()
    {
        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 3,'ruc', $dtempresa['ruc']);
        $dtvalinstallCom = $valinstall->getCountMulti('id', 'modulesid', 12,'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0 || $dtvalinstallCom['numrows']>0)
        {
            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmInsPeriodo'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmInsPeriodo'),'orden');
            }
            $formulario =$formClientes->getForm();

            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>$formulario,
                'tip'=>'Ejercicio/Períofo Fiscal',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonPeriodo("Insert")',
            ));

            //###########################
            //Formulario Dinamico

            $cantSeg = new \Entidades\Fin40030($this->adapter);
            $dtcantSeg = $cantSeg->getMulti('empresa', $this->session->get('empresa'));

            $variables = '';
            $formulario = '';
            $data = '{';
            $formulario .= '<form id="dti_validate" name="dti_validate"><table class="table table bordered"  style="width: 100%;"><tbody>';
            $formulario .= '<tr>
                                <th>Segmento</th>
                                <th>Maximo</th>
                                <th>Tamaño</th>
                            </tr>';
            foreach ($dtcantSeg as $seg) {
                $formulario .= '<tr>
                                    <td>'.$seg['segmento'].'</td>
                                    <td>máx. '.$seg['maximo'].'</td>
                                    <td><input type="hidden" id="txtid'.$seg['id'].'" name="txtid'.$seg['id'].'" value="'.$seg['id'].'"/> <input type="number" id="txttamanio'.$seg['id'].'" name="txttamanio'.$seg['id'].'" value="'.$seg['tamanio'].'" class="form-control" required="true" max="10" min="1"/></td>
                                </tr>';

                $variables .= "var id".$seg['segmento']. " = document.getElementById('txtid".$seg['id']."').value;";
                $variables .= "var valor".$seg['segmento']. " = document.getElementById('txttamanio".$seg['id']."').value;";
                $data .= "'id".$seg['segmento']. "':id".$seg['segmento']. ",";
                $data .= "'valor".$seg['segmento']. "':valor".$seg['segmento']. ",";
            }
            $formulario .= '</tbody></table></form>';
            $data .= "'accionSql': accionSql}";

            $step->setStep(array(
                'id'=>'step2',
                'contenido'=>$formulario,
                'tip'=>'Configurar Segmentos de Cuenta',
                'icono'=>'fa fa-user',
                'guardar'=>true,
                'onclic'=>'setJsonTamanioSegmento("Update")',
            ));

            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmInsCuentas'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmInsCuentas'),'orden');
            }
            $formulario =$formClientes->getForm();

            //POner la tabla de las cuentas
            $tabla = new \dti_table();
            $tabla->setIdtable('tb_cuentas');
            $tabla->setTitulo('Lista de Cuentas');
            $tabla->setColumnas('cuenta,descripcion,movimiento,naturaleza,categoria');
            $tabla->setEtiquetas('Cuentas,Descripcion,Movimiento,Naturaleza,Categoria');
            $tabla->setFiltro(true,'goTablePaginacion','install','buscarcuentas');

            $step->setStep(array(
                'id'=>'step3',
                'contenido'=>$formulario.$tabla->gettable('paginacion'),
                'tip'=>'Plan de Cuentas',
                'icono'=>'fa fa-user',
                'solo_guardar'=>true,
                'onclic'=>'setJsonCuentas("Insert")',
                'solo_fin'=>'setJsonInstallContabilidad()'
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonPeriodo',
                'data'=>"{'anio': anio,'accionSql': accionSql}",
                'ok'=>'$("#smartwizard").smartWizard("next")',
            ));
            $datos_periodo = $dti_ajax->getAjax();

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonTamanioSegmento',
                'data'=>$data,
                'ok'=>'$("#smartwizard").smartWizard("next")',
            ));
            $datos_segmento = $dti_ajax->getAjax();

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonCuenta',
                'data'=>"{'cuenta': cuenta,'descripcion':descripcion,'movimiento':movimiento,'tipocuenta':tipocuenta,'naturaleza':naturaleza,'accionSql': accionSql}",
                'ok'=>'goTablePaginacion(1)'
            ));
            $datos_cuenta = $dti_ajax->getAjax();

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonScriptContabilidad',
                'ok'=>'location.href="default/index"'
            ));
            $datos_install = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonPeriodo(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Guardar?',
                                text: 'Esta seguro que desea crear el periodo!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var anio;
                                    anio = document.getElementById('txtanio').value;

                                    if (anio != '')
                                    {
                                        ".$datos_periodo."
                                    }
                                    else
                                    {
                                        Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }
                                })
                              },
                              allowOutsideClick: false
                            });
                        }

                        function setJsonTamanioSegmento(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Guardar?',
                                text: 'Esta seguro que desea guardar tamaño de segmentos!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                    return new Promise(function(resolve) {
                                        var form = document.getElementById('dti_validate');
                                        if (!form.checkValidity())
                                        {
                                            Swal.fire('Error!', 'Debe poner información correcta!', 'error');
                                        }
                                        else
                                        {
                                            ".$variables."
                                            ".$datos_segmento."
                                        }
                                    })
                                },
                                allowOutsideClick: false
                            });
                        }

                        function setJsonCuentas(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Guardar?',
                                text: 'Esta seguro que desea guardar la cuenta!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                    return new Promise(function(resolve) {
                                        var form = document.getElementById('frmfin00000');
                                        if (!form.checkValidity())
                                        {
                                            Swal.fire('Error!', 'Debe poner información correcta!', 'error');
                                        }
                                        else
                                        {
                                            var cuenta,descripcion,movimiento,tipocuenta,naturaleza;
                                            cuenta = document.getElementById('txtcuenta').value;
                                            descripcion = document.getElementById('txtdescripcion').value;
                                            movimiento = document.getElementById('txtmovimiento').checked;
                                            tipocuenta = document.getElementById('txtfin40000id').value;
                                            naturaleza = document.getElementById('txtfin40020id').value;

                                            if (cuenta != '' && descripcion != '' && tipocuenta > 0 && naturaleza > 0)
                                            {
                                                ".$datos_cuenta."
                                            }
                                            else{
                                                Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                            }
                                        }
                                    })
                                },
                                allowOutsideClick: false
                            });
                        }

                        function setJsonInstallContabilidad()
                        {
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Finalizar?',
                                text: 'Esta seguro que desea finalizar la instalación!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                    return new Promise(function(resolve) {
                                        ".$datos_install."
                                    })
                                },
                                allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Contabilidad',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Contabilidad",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Debe tener instalado el modulo de CONTABILIDAD para instalar el modulo de COMPRAS.',
                'tip'=>'Inventario',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Contabilidad',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Contabilidad",
            ));
        }
    }
    
    public function installArticulos()
    {
        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 4,'ruc', $dtempresa['ruc']);
        $dtvalinstallOptart = $valinstall->getCountMulti('id', 'modulesid', 14,'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']==0 && $dtvalinstallOptart['numrows']==0)
        {
            //--Formularios--
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Articulos',
                'tip'=>'Articulos',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonArticulos("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonArticulos',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonArticulos(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de Articulos!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Articulos',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Articulos",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Usted ya tiene instalado el modulo de Articulos con otras caracteristicas.',
                'tip'=>'Inventario',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Articulos',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Articulos",
            ));
        }
    }
    
    public function installArticulosOptica()
    {
        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        //Modulo ventas
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 4,'ruc', $dtempresa['ruc']);
        //Modulo ventas Optica
        $dtvalinstallOpt = $valinstall->getCountMulti('id', 'modulesid', 13,'ruc', $dtempresa['ruc']);
        //Modulo compras Optica
        $dtvalinstallOptCom = $valinstall->getCountMulti('id', 'modulesid', 14,'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']==0 && $dtvalinstallOpt['numrows']>0 && $dtvalinstallOptCom['numrows']==0)
        {
            //--Formularios--
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Articulos Optica',
                'tip'=>'Articulos',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonArticulos("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonArticulosOptica',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonArticulos(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de Articulos Optica!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Articulos Optica',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Articulos Optica",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Usted ya tiene instalado el modulo de Optica con otras caracteristicas, o todavia no instala el mdulo de opticas.',
                'tip'=>'Optica',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Articulos Optica',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Articulos Optica",
            ));
        }
    }
    
    public function installArticulosAutomotriz()
    {
        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        //Modulo ventas
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 4,'ruc', $dtempresa['ruc']);
        
        if ($dtvalinstall['numrows']==0)
        {
            //--Formularios--
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Articulos Automotriz',
                'tip'=>'Articulos',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonArticulos("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonArticulosAutomotriz',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonArticulos(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de Articulos Automotriz!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Articulos Automotriz',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Articulos Automotriz",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Usted ya tiene instalado el modulo de Optica con otras caracteristicas, o todavia no instala el mdulo de articulos opticas.',
                'tip'=>'Optica',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Articulos Automotriz',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Articulos Automotriz",
            ));
        }
    }
    
    public function installInventario()
    {
        //--Formularios--
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Inventario',
            'tip'=>'Inventario',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonInventario("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonInventario',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonInventario(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de INVENTARIO!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_firma."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Inventario',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Inventario",
        ));
    }
    
    public function installOptica()
    {
        //--Formularios--
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Optica',
            'tip'=>'Optica',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonOptica("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonOptica',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonOptica(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de OPTICA!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_firma."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Optica',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Optica",
        ));
    }
    
    public function installSeguridad()
    {
        //--Formularios--
            
        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Seguridad',
            'tip'=>'Inventario',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonSeguridad("Update")',
        ));
        
        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonSeguridad',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_firma = $dti_ajax->getAjax();
        
        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonSeguridad(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de SEGURIDAD!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_firma."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Seguridad',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Seguridad",
        ));
    }
    
    public function installClientes()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 1, 'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0)
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Clientes',
                'tip'=>'Clientes',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonClientes("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonClientes',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonClientes(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de CLIENTES!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Modulo Clientes',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Clientes",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Debe tener instalado el modulo de empresa para instalar el modulo de Clientes.',
                'tip'=>'Clientes',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Modulo Clientes',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Clientes",
            ));
        }
    }
    
    public function installClientesOptica()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 1, 'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0)
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Clientes Optica',
                'tip'=>'Compras',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonClientes("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonClientesOptica',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonClientes(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de CLIENTES OPTICA!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Modulo Clientes Optica',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Clientes Optica",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Debe tener instalado el modulo de empresa para instalar el modulo de Empresa.',
                'tip'=>'Clientes',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Modulo Clientes Optica',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Clientes Optica",
            ));
        }
    }
    
    public function installVentas()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        //Inventario Normal
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 5,'ruc', $dtempresa['ruc']);
        //Inventario Optica
        $dtvalinstall2 = $valinstall->getCountMulti('id', 'modulesid', 2, 'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0 || $dtvalinstall2['numrows']>0)
        {
            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEstablecimiento'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEstablecimiento'),'orden');
            }
            $formulario =$formClientes->getForm();

            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step2',
                'contenido'=>$formulario,
                'tip'=>'Establecimiento Ventas',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonEstablecimiento("Insert")',
            ));    

            $formulario = '';
            $formulario .= '<form id="dti_validate" name="dti_validate"><table class="table table bordered"  style="width: 100%;"><tbody>';
            $formulario .= '<tr>
                                <th>Documento</th>
                                <th>Punto Emision</th>
                                <th>Secuencial</th>
                            </tr>';
            $formulario .= '<tr>
                                <td>Factura</td>
                                <td><input type="text" id="txtfactptoemision" name="txtfactptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3" /></td>
                                <td><input type="text" id="txtfactsecuencial" name="txtfactsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                            </tr>';
            $formulario .= '<tr>
                                <td>Notas de Crédito</td>
                                <td><input type="text" id="txtncptoemision" name="txtncptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                                <td><input type="text" id="txtncsecuencial" name="txtncsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                            </tr>';
            $formulario .= '<tr>
                            <td>Notas de Débito</td>
                            <td><input type="text" id="txtndptoemision" name="txtndptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                            <td><input type="text" id="txtndsecuencial" name="txtndsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                        </tr>';
            $formulario .= '<tr>
                                <td>Comprobantes de Retención</td>
                                <td><input type="text" id="txtretenptoemision" name="txtretenptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                                <td><input type="text" id="txtretensecuencial" name="txtretensecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                            </tr>';
            $formulario .= '<tr>
                                <td>Guias de Remisión</td>
                                <td><input type="text" id="txtgrptoemision" name="txtgrptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                                <td><input type="text" id="txtgrsecuencial" name="txtgrsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                            </tr>';
            $formulario .= '<tr>
                                <td>Cobros</td>
                                <td><input type="text" id="txtccptoemision" name="txtccptoemision" value="001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{3}" maxlength="3"/></td>
                                <td><input type="text" id="txtccsecuencial" name="txtccsecuencial" value="000000001" class="form-control" required="true" title="Solo se permite Números" pattern="[0-9]{9}" maxlength="9"/></td>
                            </tr>';
            $formulario .= '</tbody></table></form>';

            $step->setStep(array(
                'id'=>'step3',
                'contenido'=>$formulario,
                'tip'=>'Secuencial',
                'icono'=>'fa fa-user',
                'guardar'=>true,
                'onclic'=>'setJsonSecuencial("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonEstablecimiento',
                'data'=>"{'establecimiento': establecimiento,'direccion':direccion,'secuencial':secuencial,'accionSql': accionSql}",
                'ok'=>'$("#smartwizard").smartWizard("next")',
            ));
            $datos_establecimiento = $dti_ajax->getAjax();

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonSecuencialOptica',
                'data'=>"{'txtfactptoemision' : txtfactptoemision,
                        'txtfactsecuencial' : txtfactsecuencial,
                        'txtncptoemision' : txtncptoemision,'txtncsecuencial' : txtncsecuencial,
                        'txtndptoemision' : txtndptoemision,
                        'txtndsecuencial' : txtndsecuencial,
                        'txtretenptoemision' : txtretenptoemision,'txtretensecuencial' : txtretensecuencial,
                        'txtgrptoemision' : txtgrptoemision,
                        'txtgrsecuencial' : txtgrsecuencial,
                        'txtccptoemision' : txtccptoemision,
                        'txtccsecuencial' : txtccsecuencial,
                        'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_secuencial = $dti_ajax->getAjax();        

            \dti_core::set("script", "<script type='text/javascript'>                   
                        function setJsonEstablecimiento(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Guardar?',
                                text: 'Esta seguro que desea guardar el establecimiento!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                    return new Promise(function(resolve) {
                                        var form = document.getElementById('frmcc00020');
                                        if (!form.checkValidity())
                                        {
                                            Swal.fire('Error!', 'Debe poner información correcta!', 'error');
                                        }
                                        else
                                        {
                                            var establecimiento,direccion,secuencial;
                                            establecimiento = document.getElementById('txtestablecimiento').value;
                                            direccion = document.getElementById('txtdireccion').value;
                                            secuencial = document.getElementById('txtsecuencial').value;

                                            if (establecimiento != '' && direccion != '' && secuencial != '')
                                            {
                                                ".$datos_establecimiento."
                                            }
                                            else{
                                                Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                            }
                                        }
                                    })
                                },
                                allowOutsideClick: false
                            });
                        }

                        function setJsonSecuencial(accionSql=''){
                             //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Guardar?',
                                text: 'Esta seguro que desea guardar el secuencial!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                    return new Promise(function(resolve) {
                                        var form = document.getElementById('dti_validate');
                                        if (!form.checkValidity())
                                        {
                                            Swal.fire('Error!', 'Debe poner información correcta!', 'error');
                                        }
                                        else
                                        {
                                            //Agregar Validaciones
                                            var txtfactptoemision = document.getElementById('txtfactptoemision').value;
                                            var txtfactsecuencial = document.getElementById('txtfactsecuencial').value;
                                            var txtncptoemision = document.getElementById('txtncptoemision').value;
                                            var txtncsecuencial = document.getElementById('txtncsecuencial').value;
                                            var txtndptoemision = document.getElementById('txtndptoemision').value;
                                            var txtndsecuencial = document.getElementById('txtndsecuencial').value;
                                            var txtretenptoemision = document.getElementById('txtretenptoemision').value;
                                            var txtretensecuencial = document.getElementById('txtretensecuencial').value;
                                            var txtgrptoemision = document.getElementById('txtgrptoemision').value;
                                            var txtgrsecuencial = document.getElementById('txtgrsecuencial').value;
                                            var txtccptoemision = document.getElementById('txtccptoemision').value;
                                            var txtccsecuencial = document.getElementById('txtccsecuencial').value;

                                            if (txtfactptoemision != '' && txtfactsecuencial != '' && txtncptoemision != ''
                                            && txtncsecuencial != '' && txtndptoemision != '' && txtndsecuencial != '' && txtgrsecuencial != ''
                                            && txtretenptoemision != '' && txtretensecuencial != '' && txtgrptoemision != ''
                                            && txtccptoemision != '' && txtccsecuencial != '') {
                                                ".$datos_secuencial."
                                            }
                                            else{
                                                Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                            }
                                        }
                                    })
                                },
                                allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Ventas Optica',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Ventas Optica",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Debe tener instalado el modulo de INVENTARIO para instalar el modulo de VENTAS OPTICA.',
                'tip'=>'Inventario',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Ventas Optica',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Ventas Optica",
            ));
        }
    }
    
    public function installCompras()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        //Modulo Proveedores
        $proveedor = $valinstall->getCountMulti('id', 'modulesid', 3,'ruc', $dtempresa['ruc']);
        //Modulo de Optica
        $optica = $valinstall->getCountMulti('id', 'modulesid', 13,'ruc', $dtempresa['ruc']);
        if (($proveedor['numrows']>0 || $optica['numrows']>0))
        {
            //--Formularios--

            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Compras',
                'tip'=>'Compras',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonCompras("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonCompras',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonCompras(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de COMPRAS!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Compras',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Compras",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Debe tener instalado el modulo de Proveedores para instalar el modulo de COMPRAS.',
                'tip'=>'Inventario',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Compras',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Compras",
            ));
        }
    }
    
    public function installImpuestos()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        //Modulo ventas
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 8,'ruc', $dtempresa['ruc']);
        //Modulo ventas Optica
        $dtvalinstallOpt = $valinstall->getCountMulti('id', 'modulesid', 9,'ruc', $dtempresa['ruc']);
        //Modulo compras Optica
        $dtvalinstallOptCom = $valinstall->getCountMulti('id', 'modulesid', 10,'ruc', $dtempresa['ruc']);
        if (($dtvalinstall['numrows']>0 || $dtvalinstallOpt['numrows']>0) && $dtvalinstallOptCom['numrows']==0)
        {
            //--Formularios--

            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Impuestos',
                'tip'=>'Impuestos',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonCompras("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonImpuestos',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonCompras(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de Impuestos!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Impuestos',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Impuestos",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Debe tener instalado el modulo de Bancos para instalar el modulo de Impuestos.',
                'tip'=>'Inventario',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Impuestos',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Impuestos",
            ));
        }
    }
    
    public function installEtiquetas()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        //--Formularios--

        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Etiquetas',
            'tip'=>'Etiquetas',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonEtiquetas("Update")',
        ));

        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonEtiquetas',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_etiqueta = $dti_ajax->getAjax();

        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonEtiquetas(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de Etiquetas!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_etiqueta."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");

        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Etiquetas',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Etiquetas",
        ));
    }
    
    public function installRetenciones()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        //--Formularios--

        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Retencioness',
            'tip'=>'Retenciones',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonRetenciones("Update")',
        ));

        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonRetenciones',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_retenciones = $dti_ajax->getAjax();

        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonRetenciones(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de Retenciones!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_retenciones."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");

        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Retenciones',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Retenciones",
        ));
    }
    
    public function installListaPrecios()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        //--Formularios--

        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Lista de Precios',
            'tip'=>'Etiquetas',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonListaPrecios("Update")',
        ));

        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonListaPrecios',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_etiqueta = $dti_ajax->getAjax();

        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonListaPrecios(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de Lista de Precios!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_etiqueta."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");

        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Lista de Precios',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Lista de Precios",
        ));
    }
    
    public function installConteo()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        //--Formularios--

        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Conteo',
            'tip'=>'Conteo',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonConteo("Update")',
        ));

        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonConteo',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_etiqueta = $dti_ajax->getAjax();

        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonConteo(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de Conteo!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_etiqueta."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");

        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Conteo',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Conteo",
        ));
    }
    
    public function installCaja()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        //--Formularios--

        $step = new \dti_step();
        $step->setStep(array(
            'id'=>'step1',
            'contenido'=>'Instalar Modulo de Caja',
            'tip'=>'Caja',
            'icono'=>'fa fa-user',
            'cancelar'=>true,
            'onclicCancel'=>'location.href = "default/index"',
            'guardar'=>true,
            'onclic'=>'setJsonCaja("Update")',
        ));

        $dti_ajax = new dti_builder_ajax();
        $dti_ajax->setAjax(array(
            'url'=>'install/jsonCaja',
            'data'=>"{'accionSql': accionSql}",
            'ok'=>'location.href="default/index"',
        ));
        $datos_caja = $dti_ajax->getAjax();

        \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonCaja(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Instalar?',
                            text: 'Esta seguro que desea instalar el modulo de Caja!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Instalar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$datos_caja."
                            })
                          },
                          allowOutsideClick: false
                        });
                    }
                    </script>");

        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual_titulo'=>array(
                    'titulo'=>'Instalar Caja',
                    'layout'=>$step->getStep(),
                ),
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Instalar Modulo Caja",
        ));
    }
    
    public function installBancos()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        //Modulo ventas
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 5,'ruc', $dtempresa['ruc']);
        //Modulo ventas Optica
        $dtvalinstallOpt = $valinstall->getCountMulti('id', 'modulesid', 8,'ruc', $dtempresa['ruc']);
        //Modulo compras Optica
        $dtvalinstallOptCom = $valinstall->getCountMulti('id', 'modulesid', 11,'ruc', $dtempresa['ruc']);
        if (($dtvalinstall['numrows']>0 || $dtvalinstallOpt['numrows']>0) && $dtvalinstallOptCom['numrows']==0)
        {
            //--Formularios--

            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Bancos',
                'tip'=>'Compras',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonBancos("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonBancos',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonBancos(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de BANCOS!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Bancos',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Bancos",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Debe tener instalado el modulo de VENTAS y COMPRAS para instalar el modulo de BANCOS.',
                'tip'=>'Bancos',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Bancos',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Bancos",
            ));
        }
    }
    
    public function installProveedores()
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $dtcliente = $cliente->getMulti('usuario', $_SESSION['usuario']);

        $empresa = new Entidades\Sis00100($this->adapter);
        $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
        
        $valinstall = new \Entidades\Sis20012($this->adapter);
        //Modulo ventas
        $dtvalinstall = $valinstall->getCountMulti('id', 'modulesid', 1,'ruc', $dtempresa['ruc']);
        //Modulo ventas Optica
        $dtvalinstallOpt = $valinstall->getCountMulti('id', 'modulesid', 2,'ruc', $dtempresa['ruc']);
        if ($dtvalinstall['numrows']>0 || $dtvalinstallOpt['numrows']>0)
        {
            //--Formularios--

            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Instalar Modulo de Proveedores',
                'tip'=>'Proveedores',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "default/index"',
                'guardar'=>true,
                'onclic'=>'setJsonProveedor("Update")',
            ));

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonProveedores',
                'data'=>"{'accionSql': accionSql}",
                'ok'=>'location.href="default/index"',
            ));
            $datos_firma = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonProveedor(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Instalar?',
                                text: 'Esta seguro que desea instalar el modulo de Proveedores!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Instalar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                return new Promise(function(resolve) {
                                    ".$datos_firma."
                                })
                              },
                              allowOutsideClick: false
                            });
                        }
                        </script>");

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Modulo Proveedores',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Proveedores",
            ));
        }
        else
        {
            $step = new \dti_step();
            $step->setStep(array(
                'id'=>'step1',
                'contenido'=>'Debe tener instalado el modulo de Clientes y Empresa para instalar el modulo de Proveedores.',
                'tip'=>'Inventario',
                'icono'=>'fa fa-user',
                'cancelar'=>true,
                'onclicCancel'=>'location.href = "install/installmodulos"',
            ));
            
            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Instalar Proveedores',
                        'layout'=>$step->getStep(),
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Instalar Modulo Proveedores",
            ));
        }
    }
    
    public function listPlan()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        
        $datos = new Models\Sis20013Model($this->adapter);
        if (isset($_POST['page']))
        {
            //Limpiar la Variable
            if ($_POST['q'] != 'undefined') {
                $q = $_POST['q'];
            }else{
                $q = '';
            }
            $numrows = $datos->getCount();
            //Muchos Datos
            //Paginacion
            //las variables de paginación
            $page = (isset($_POST['page']) && !empty($_POST['page']))?$_POST['page']:1;
            $per_page = 6; //la cantidad de registros que desea mostrar
            $adjacents  = 4; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            //Consultar Inventario
            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));
            
            $datos = $datos->getTablePaginacion($cliente['id'],$empresa['ruc'],$q,$offset,$per_page);
            
            if(globalFunctions::es_bidimensional($datos))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_planes');
                $tabla->setTitulo('Lista de Planes Adquiridos');
                $tabla->setColumnas('plan,descripcion,fecha,disponible,usado');
                $tabla->setEtiquetas('PLan,Descripcion,Fecha,Disponible,Utilizado');
                $tabla->setDatos($datos);
                //$tabla->setEditar('cc/newfactura',true,'mdleditFactura');
                //$tabla->setNuevo('cc/newfactura');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["plan"]))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_planes');
                $tabla->setTitulo('Lista de Planes Adquiridos');
                $tabla->setColumnas('plan,descripcion,fecha,disponible,usado');
                $tabla->setEtiquetas('PLan,Descripcion,Fecha,Disponible,Utilizado');
                $tabla->setDatos($datos);
                //$tabla->setEditar('cc/newfactura',true,'mdleditFactura');
                //$tabla->setNuevo('cc/newfactura');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else
            {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_planes');
                $tabla->setTitulo('Lista de Planes Adquiridos');
                $tabla->setColumnas('plan,descripcion,fecha,disponible,usado');
                $tabla->setEtiquetas('PLan,Descripcion,Fecha,Disponible,Utilizado');
                $tabla->setDatos(null);

                echo $tabla->gettable('Dpaginacion');
            }
        }
        else
        {
            if (isset($_POST['codigo']) || isset($_GET['codigo']))
            {
                if (ELIMINAR_JS == 0) {
                    try{
                            $delete = $datos->deleteById($_GET['codigo']);
                    } catch (Exception $ex) {
                            print_r($ex);
                    }
                    $this->redirect('cc','listClientes');
                }else{
                    try{
                            $delete = $datos->deleteById($_POST['codigo']);
                            echo 'OK';
                    } catch (Exception $ex) {
                            echo $ex;
                    }
                }
            }
            else
            {
                $tabla = new \dti_table();
                //$ds = $datos->getAll('fecha');
                $tabla->setIdtable('tb_planes');
                $tabla->setTitulo('Lista de Planes Adquiridos');
                $tabla->setColumnas('plan,descripcion,fecha,disponible,usado');
                $tabla->setEtiquetas('PLan,Descripcion,Fecha,Disponible,Utilizado');
                //$tabla->setDatos(null);
                //$tabla->setEditar('cc/newfactura',true,'mdleditFactura');
                //$tabla->setNuevo('cc/newfactura');
                $tabla->setFiltro(true,'goTablePaginacion','install','listPlan');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'default/index',
                        'enlace'=>true,
                        'icono'=>'fa fa-reply',
                        'titulo'=>'Regresar',
                        'btntitulo'=>'',
                        'btnmensaje'=>'',
                        'btn'=>array(),
                    ));

                $contenedor = globalFunctions::renderizar($this->website,array(
                    'section'=>array(
                        'manual'=>$btngroup->getGroupButtons()['layout'],
                        'layout_section'=>$tabla->gettable('paginacion'),
                    )
                ));

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Planes Adquiridos",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function comprarPlan($param=array())
    {
        $cliente = new Entidades\Sis00050($this->adapter);
        $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

        $empresa = new Entidades\Sis00100($this->adapter);
        $empresa = $empresa->getMulti('id', $this->session->get('empresa'));
        
        $monto = new \Models\Sis20014Model($this->adapter);
        $dtmonto = $monto->getMontoIngresado($empresa['ruc']);
        
        $monto_utilizado = new \Models\Sis20013Model($this->adapter);
        $dtmonto_utilizado = $monto_utilizado->getMontoOcupado($empresa['ruc']);
        
        $plan = new \Entidades\Sis00061($this->adapter);
        $dtplan = $plan->getById($param['plan']);
        
        if (($dtmonto['monto']-$dtmonto_utilizado['monto'])>=$dtplan['costo'])
        {
            //Tenemos dinero para comprar el plan
            $entidad = new Entidades\Sis20013($this->adapter);
            $entidad->setActivo(1);
            $entidad->setClienteid($cliente['id']);
            $entidad->setRuc($empresa['ruc']);
            $entidad->setPlanid($param['plan']);
            $entidad->setUsado(0);
            $entidad->setDisponible($dtplan['cantidad']);
            $entidad->setFecha(date('Y-m-d'));
            $entidad->save();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Comprado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No Dispone de Saldo Suficiente.')));
        }
    }
    
    /**
     * MANEJO DE JSON
     */
    
    public function jsonEmpresa($param=array())
    {
        if($param['accionSql'] == 'Insert')
        {
            //Guardar la empresa en General
            //Mensaje si todo sale correcto
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Update datos empresa
            $entidad = new Entidades\Sis00100($this->adapter);
            $entidad->updateMultiColum('nomempresa', $param['nomempresa'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('razonsocial', $param['razonsocial'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('direccion', $param['direccion'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('telefono', $param['telefono'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('rucrepresentante', $param['rucrepresentante'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('representante', $param['representante'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('obligaconta', $param['obligaconta'] === 'true'?1:0, 'ruc', $param['ruc']);
            $entidad->updateMultiColum('contriespecial', $param['contriespecial'] === 'true'?1:0, 'ruc', $param['ruc']);
            $entidad->updateMultiColum('valcontriespecial', $param['valcontriespecial'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('correo', $param['correo'], 'ruc', $param['ruc']);
            
            $entidad->updateMultiColum('languageid', $param['languageid'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('template', $param['template'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('zona_horaria', $param['zona_horaria'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('moneda', $param['moneda'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('caracter_decimal', $param['caracter_decimal'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('caracter_miles', $param['caracter_miles'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('segmentos_cuentas', $param['segmentos_cuentas'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('separador_segmentos', $param['separador_segmentos'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('decimales_ventas', $param['decimales_ventas'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('decimales_compras', $param['decimales_compras'], 'ruc', $param['ruc']);
            
            $entidad->updateMultiColum('smtp_hostname', $param['smtp_hostname'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('smtp_port', $param['smtp_port'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('smtp_username', $param['smtp_username'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('smtp_password', $param['smtp_password'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('smtpdefecto', $param['smtpdefecto'] === 'true'?1:0, 'ruc', $param['ruc']);
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonDatosGenerales($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert')
        {
            //Validamos que no se inserte 2 veces
            $entidad = new Entidades\Sis00100($this->adapter);
            $existe_empresa = $entidad->getCountMulti('id', 'ruc', $param['ruc']);
            if ($existe_empresa['numrows']==0) {
                //Guardar la empresa en General
                //Ultimo Prefijo
                $entidad = new Entidades\Sis00059($this->adapter);
                $empresaGeneral = new \Models\Sis00059Model($this->adapter);
                $prefijo = $empresaGeneral->getNextPrefijo();
                
                $entidad->autocommit();
                
                $cliente = new Entidades\Sis00050($this->adapter);
                $cliente = $cliente->getBy('usuario', $this->session->get('usuario'));
                
                $entidad->setClienteid($cliente['id']);
                $entidad->setRuc($param['ruc']);
                $entidad->setNomempresa($param['nomempresa']);
                $entidad->setRazonsocial($param['razonsocial']);
                $entidad->setPrefijo($prefijo['prefijo']);
                $entidad->save();
                //Insertar en la web transaccional
                $entidad = new Entidades\Sis00100($this->adapter);
                $entidad->setRuc($param['ruc']);
                $entidad->setNomempresa($param['nomempresa']);
                $entidad->setRazonsocial($param['razonsocial']);
                $entidad->setDireccion($param['direccion']);
                $entidad->setTelefono($param['telefono']);
                $entidad->setRucrepresentante($param['rucrepresentante']);
                $entidad->setRepresentante($param['representante']);
                $entidad->setObligaconta($param['obligaconta'] === 'true'?1:0);
                $entidad->setContriespecial($param['contriespecial'] === 'true'?1:0);
                $entidad->setValcontriespecial($param['valcontriespecial']);
                $entidad->setTemplate(1);
                $entidad->setLanguageid(1);
                $entidad->setZona_horaria(1);
                $entidad->setMoneda(1);
                $entidad->setAmbiente(1);
                $entidad->setLogo('');
                $entidad->setImgfondo('');
                $entidad->setCorreo($param['correo']);
                
                //Obtener la base de datos
                $bd = new Entidades\Sis00050($this->adapter);
                $dtbd = $bd->getMulti('usuario', $this->session->get('usuario'));
                $entidad->setBd($dtbd['bd']);
                $entidad->setActivo(1);
                $entidad->setSmtp_port(25);
                $entidad->setSmtpdefecto(0);
                $entidad->setSegmentos_cuentas(0);
                $entidad->setDecimales_ventas(0);
                $entidad->setDecimales_compras(0);
                //Guardamos Clientes
                $entidad->save();
                
                //Ingresamos en la tabla el rol y la empresa
                $empresa = new Entidades\Sis00100($this->adapter); 
                $usuario = new Entidades\Sis00300($this->adapter);
                $permiso = new Entidades\Sis20200($this->adapter);

                $dtempresa = $empresa->getMulti('ruc', $param['ruc']);
                $usuarioid = $usuario->getMulti('usuario', $this->session->get('usuario'));

                $permiso->setSis00100id($dtempresa['id']);
                $permiso->setSis00300id($usuarioid['id']);
                $permiso->save();
                
                $usuarioid = $usuario->getMulti('usuario', 'dtiware');
                
                $permiso->setSis00100id($dtempresa['id']);
                $permiso->setSis00300id($usuarioid['id']);
                $permiso->save();
                
                $this->session->add('empresa',$dtempresa['id']);
                
                $entidad->commit();
                
                //Mensaje si todo sale correcto
                die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
            }
            else
            {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Volver a cargar la pagina puesto que ya creo una empresa.')));
            }
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00010($this->adapter);
            $entidad->updateMultiColum('rol', $param['rol'],'id', $param['id']);
            $entidad->updateMultiColum('activo', $param['activo'] === 'true'?1:0,'id', $param['id']);
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonConfiguraciones($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00100($this->adapter);
            
            $entidad->autocommit();
            
            $entidad->updateMultiColum('languageid', $param['languageid'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('template', $param['template'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('zona_horaria', $param['zona_horaria'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('moneda', $param['moneda'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('caracter_decimal', $param['caracter_decimal'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('caracter_miles', $param['caracter_miles'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('segmentos_cuentas', $param['segmentos_cuentas'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('separador_segmentos', $param['separador_segmentos'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('decimales_ventas', $param['decimales_ventas'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('decimales_compras', $param['decimales_compras'], 'ruc', $param['ruc']);
            
            //Insertamos para que se configure el tamaño de los segmentos
            
            $entidad->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonSmtp($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00100($this->adapter);
            $entidad->updateMultiColum('smtp_hostname', $param['smtp_hostname'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('smtp_port', is_numeric($param['smtp_port'])?$param['smtp_port']:0, 'ruc', $param['ruc']);
            $entidad->updateMultiColum('smtp_username', $param['smtp_username'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('smtp_password', $param['smtp_password'], 'ruc', $param['ruc']);
            $entidad->updateMultiColum('smtpdefecto', $param['smtpdefecto'] === 'true'?1:0, 'ruc', $param['ruc']);
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonLogo($param=array())
    {
        //Guardamos que se acabo de instalar
        $entidad = new Entidades\Sis00060($this->adapter);
        $idModulo = $entidad->getMulti('nombre', 'Empresa');
        
        $cliente = new Entidades\Sis00050($this->adapter);
        $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));
        
        $empresa = new Entidades\Sis00100($this->adapter);
        $empresa = $empresa->getMulti('id', $this->session->get('empresa'));
        
        $insert = new \Entidades\Sis20012($this->adapter);
        $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
        if ($existe['numrows']==0) {
            
            $insert->setActivo(1);
            $insert->setClienteid($cliente['id']);
            $insert->setRuc($empresa['ruc']);
            $insert->setModulesid($idModulo['id']);
            $insert->save();
        }

        if (isset($_FILES["logo"]["name"]))
        {
            //$ext = basename($_FILES["logo"]["type"]);
            $ext = substr($_FILES["logo"]["name"], strpos($_FILES["logo"]["name"],'.')+1, strlen($_FILES["logo"]["name"]));
            if ($ext == "png" || $ext == "jpg" || $ext == "jpeg") {
                $target_file = PATH_LOGOS . $param['ruc'].".".$ext;
                move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);
                $empresa = new Entidades\Sis00100($this->adapter);
                $empresa->updateMultiColum('logo', $target_file, 'ruc', $param['ruc']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Subido Con exito.')));
            }
            else
            {
                die(json_encode(array('status' => 'Error', 'descripcion' => 'No tiene formato correcto.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'OK', 'descripcion' => 'No tiene logo.')));
        }
    }
    
    public function jsonFirma($param=array())
    {
        //Validar si tengo la firma
        $empresa = new Entidades\Sis00100($this->adapter);
        
        $val_firma = $empresa->getMulti('id', $this->session->get('empresa'));
        if (!isset($_FILES["firma"]["name"]) && strlen($val_firma['firma'])>0)
        {
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Subido Con exito.')));
        }
        else
        {
            if (isset($_FILES["firma"]["name"]))
            {
                $ext = basename($_FILES["firma"]["type"]);
                if ($ext == 'x-pkcs12')
                {
                    $ext = substr($_FILES["firma"]["name"], strpos($_FILES["firma"]["name"],'.')+1, strlen($_FILES["firma"]["name"]));
                    $dtempresa = $empresa->getMulti('id', $_SESSION['empresa']);
                    $target_file = PATH_FIRMA . $dtempresa['ruc'].".".$ext;
                    move_uploaded_file($_FILES["firma"]["tmp_name"], $target_file);
                    $empresa->updateMultiColum('firma', $target_file, 'ruc', $dtempresa['ruc']);

                    die(json_encode(array('status' => 'OK', 'descripcion' => 'Subido Con exito.')));
                }
                else
                {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'El archivo debe tener formato .p12.')));
                }
            }
            else
            {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'La Firma es obligatoria.')));
            }
        }
    }
    
    public function jsonFirmaelectronica($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            $entidad = new Entidades\Sis00100($this->adapter);
            $dtempresa = $entidad->getMulti('id', $_SESSION['empresa']);
            //Validar Firma Electronica
            $resultado = $this->getDatosFirma($dtempresa['firma'],$param['clave'],$dtempresa['ruc']);
            if ($resultado === "OK") {
                $entidad->autocommit();
                //Insertar en la web transaccional
                $entidad->updateMultiColum('ambiente', $param['ambiente'], 'ruc', $dtempresa['ruc']);
                $entidad->updateMultiColum('clavefirma', $param['clave'], 'ruc', $dtempresa['ruc']);
                
                //Correr el script de instalacion de facturacion electronica
                $fichero = 'docs/install/0012FE.sql';  // Ruta al fichero que vas a cargar.
                $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                    mysqli_autocommit($conx,FALSE);
                    // Si la linea acaba en ; hemos encontrado el final de la sentencia
                    if (substr($linea, -1, 1) == ';') {
                        // Ejecutamos la consulta
                        mysqli_set_charset($conx, "utf8");
                        mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                        // Limpiamos sentencia temporal
                        $temp = '';
                    }
                    if (!mysqli_commit($conx)) {
                        die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                        mysqli_rollback($conx);
                        exit();
                    }
                }
                $conx->close();

                $entidad = new Entidades\Sis00060($this->adapter);
                $idModulo = $entidad->getMulti('nombre', 'FE');

                $cliente = new Entidades\Sis00050($this->adapter);
                $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

                $empresa = new Entidades\Sis00100($this->adapter);
                $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

                $insert = new \Entidades\Sis20012($this->adapter);

                $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
                if ($existe['numrows']==0)
                {
                    $insert->setActivo(1);
                    $insert->setClienteid($cliente['id']);
                    $insert->setRuc($empresa['ruc']);
                    $insert->setModulesid($idModulo['id']);
                    $insert->save();
                }

                $insert->commit();
                
                die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
            }else{
                die(json_encode(array('status' => 'ERROR', 'descripcion' => $resultado)));
            }
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonFirmaelectronicaErp($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            $entidad = new Entidades\Sis00100($this->adapter);
            $dtempresa = $entidad->getMulti('id', $_SESSION['empresa']);
            //Validar Firma Electronica
            $resultado = $this->getDatosFirma($dtempresa['firma'],$param['clave'],$dtempresa['ruc']);
            if ($resultado === "OK") {
                $entidad->autocommit();
                //Insertar en la web transaccional
                $entidad->updateMultiColum('ambiente', $param['ambiente'], 'ruc', $dtempresa['ruc']);
                $entidad->updateMultiColum('clavefirma', $param['clave'], 'ruc', $dtempresa['ruc']);
                
                //Correr el script de instalacion de facturacion electronica
                $fichero = 'docs/install/0018FEerp.sql';  // Ruta al fichero que vas a cargar.
                $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                    mysqli_autocommit($conx,FALSE);
                    // Si la linea acaba en ; hemos encontrado el final de la sentencia
                    if (substr($linea, -1, 1) == ';') {
                        // Ejecutamos la consulta
                        mysqli_set_charset($conx, "utf8");
                        mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                        // Limpiamos sentencia temporal
                        $temp = '';
                    }
                    if (!mysqli_commit($conx)) {
                        die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                        mysqli_rollback($conx);
                        exit();
                    }
                }
                $conx->close();

                $entidad = new Entidades\Sis00060($this->adapter);
                $idModulo = $entidad->getMulti('nombre', 'FE ERP');

                $cliente = new Entidades\Sis00050($this->adapter);
                $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

                $empresa = new Entidades\Sis00100($this->adapter);
                $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

                $insert = new \Entidades\Sis20012($this->adapter);

                $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
                if ($existe['numrows']==0)
                {
                    $insert->setActivo(1);
                    $insert->setClienteid($cliente['id']);
                    $insert->setRuc($empresa['ruc']);
                    $insert->setModulesid($idModulo['id']);
                    $insert->save();
                }

                $insert->commit();
                
                die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
            }else{
                die(json_encode(array('status' => 'ERROR', 'descripcion' => $resultado)));
            }
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonFirmaelectronicaSolo($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            $entidad = new Entidades\Sis00100($this->adapter);
            $dtempresa = $entidad->getMulti('id', $_SESSION['empresa']);
            //Validar Firma Electronica
            $resultado = $this->getDatosFirma($dtempresa['firma'],$param['clave'],$dtempresa['ruc']);
            if ($resultado === "OK") {
                $entidad->autocommit();
                //Insertar en la web transaccional
                $entidad->updateMultiColum('ambiente', $param['ambiente'], 'ruc', $dtempresa['ruc']);
                $entidad->updateMultiColum('clavefirma', $param['clave'], 'ruc', $dtempresa['ruc']);
                
                //Correr el script de instalacion de facturacion electronica
                $fichero = 'docs/install/0020FEsolo.sql';  // Ruta al fichero que vas a cargar.
                $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                    mysqli_autocommit($conx,FALSE);
                    // Si la linea acaba en ; hemos encontrado el final de la sentencia
                    if (substr($linea, -1, 1) == ';') {
                        // Ejecutamos la consulta
                        mysqli_set_charset($conx, "utf8");
                        mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                        // Limpiamos sentencia temporal
                        $temp = '';
                    }
                    if (!mysqli_commit($conx)) {
                        die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                        mysqli_rollback($conx);
                        exit();
                    }
                }
                $conx->close();

                $insert->commit();
                
                die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
            }else{
                die(json_encode(array('status' => 'ERROR', 'descripcion' => $resultado)));
            }
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonInventario($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0008Inventario.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Inventario');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonOptica($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0101Optica.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Optica');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonSeguridad($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0013Seguridad.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Seguridad');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonGastos($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0021Gastos.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Gastos');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonPromociones($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0023Promociones.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Promociones');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonPos($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0022Pos.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Pos');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonBancos($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0010Bancos.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Bancos');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonArticulos($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0005Articulos.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Articulos');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonArticulosOptica($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0105ArticulosOptica.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Articulos Optica');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonArticulosAutomotriz($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0205ArticulosAutomotriz.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Articulos Automotriz');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonCompras($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0006CXP.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'CXP');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonImpuestos($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0011Impuestos.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Impuestos');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonRetenciones($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0019Retenciones.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Retenciones');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonEtiquetas($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0014Etiquetas.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Etiquetas');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonListaPrecios($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0015Listaprecios.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Lista Precios');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonConteo($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0016Conteo.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Conteo');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonCaja($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0017Caja.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Caja');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonClientes($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0003Clientes.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Clientes');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonClientesOptica($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0103ClientesOptica.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Clientes Optica');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonProveedores($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Correr el script de instalacion de facturacion electronica
            $fichero = 'docs/install/0004Proveedores.sql';  // Ruta al fichero que vas a cargar.
            $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_autocommit($conx,FALSE);
                // Si la linea acaba en ; hemos encontrado el final de la sentencia
                if (substr($linea, -1, 1) == ';') {
                    // Ejecutamos la consulta
                    mysqli_set_charset($conx, "utf8");
                    mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                    // Limpiamos sentencia temporal
                    $temp = '';
                }
                if (!mysqli_commit($conx)) {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                    mysqli_rollback($conx);
                    exit();
                }
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Proveedores');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonEstablecimiento($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert')
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Cc00020($this->adapter);
            $entidad->autocommit();
            //Validar si no existe
            $dtentidad = $entidad->getCountMulti('id','establecimiento', $param['establecimiento'], 'empresa', $this->session->get('empresa'),'secuencial',$param['secuencial']);
            if ($dtentidad['numrows']==0)
            {
                $entidad->setEmpresa($this->session->get('empresa'));
                $entidad->setEstablecimiento($param['establecimiento']);
                $entidad->setDireccion($param['direccion']);
                $entidad->setSecuencial($param['secuencial']);
                $entidad->setActivo($param['activo']=='true'?1:0);
                $entidad->setFe($param['fe']=='true'?1:0);
                $entidad->save();
            }
            
            //Optenemos dato ingresado
            $dtestable = $entidad->getBy('establecimiento', $param['establecimiento']);
            //Insertamos todo lo necesario de los secuenciales
            $entidad = new Entidades\Cc00030($this->adapter);
            $dtentidad = $entidad->getCountMulti('id','cc00020id', $dtestable['id'], 'documento', 1);
            if ($dtentidad['numrows']==0)
            {
                $entidad->setCc00020id($dtestable['id']);
                $entidad->setDocumento(1);
                $entidad->setPtoemision('0');
                $entidad->setSecuencial('0');
                $entidad->setActivo(1);
                $entidad->save();
            }
            
            $dtentidad = $entidad->getCountMulti('id','cc00020id', $dtestable['id'], 'documento', 2);
            if ($dtentidad['numrows']==0)
            {
                $entidad->setCc00020id($dtestable['id']);
                $entidad->setDocumento(2);
                $entidad->setPtoemision('0');
                $entidad->setSecuencial('0');
                $entidad->setActivo(1);
                $entidad->save();
            }
            
            $dtentidad = $entidad->getCountMulti('id','cc00020id', $dtestable['id'], 'documento', 3);
            if ($dtentidad['numrows']==0)
            {
                $entidad->setCc00020id($dtestable['id']);
                $entidad->setDocumento(3);
                $entidad->setPtoemision('0');
                $entidad->setSecuencial('0');
                $entidad->setActivo(1);
                $entidad->save();
            }
            
            $dtentidad = $entidad->getCountMulti('id','cc00020id', $dtestable['id'], 'documento', 4);
            if ($dtentidad['numrows']==0)
            {
                $entidad->setCc00020id($dtestable['id']);
                $entidad->setDocumento(4);
                $entidad->setPtoemision('0');
                $entidad->setSecuencial('0');
                $entidad->setActivo(1);
                $entidad->save();
            }
            
            $dtentidad = $entidad->getCountMulti('id','cc00020id', $dtestable['id'], 'documento', 5);
            if ($dtentidad['numrows']==0)
            {
                $entidad->setCc00020id($dtestable['id']);
                $entidad->setDocumento(5);
                $entidad->setPtoemision('0');
                $entidad->setSecuencial('0');
                $entidad->setActivo(1);
                $entidad->save();
            }
            
            $dtentidad = $entidad->getCountMulti('id','cc00020id', $dtestable['id'], 'documento', 6);
            if ($dtentidad['numrows']==0)
            {
                $entidad->setCc00020id($dtestable['id']);
                $entidad->setDocumento(6);
                $entidad->setPtoemision('0');
                $entidad->setSecuencial('0');
                $entidad->setActivo(1);
                $entidad->save();
            }
            
            //Inicializamos la variable session
            $this->session->add('establecimiento', $dtestable['id']);
            
            $entidad->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Cc00020($this->adapter);
            $entidad->updateMultiColum('direccion', $param['direccion'], 'establecimiento', $param['establecimiento']);
            $entidad->updateMultiColum('secuencial', $param['secuencial'], 'establecimiento', $param['establecimiento']);
            $entidad->updateMultiColum('fe', $param['fe']=='true'?1:0, 'establecimiento', $param['establecimiento']);
            $entidad->updateMultiColum('activo', $param['activo']=='true'?1:0, 'establecimiento', $param['establecimiento']);
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonSecuencial($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Guardamos que se acabo de instalar
            
            //Insertar en la web transaccional
            $entidad = new Entidades\Cc00030($this->adapter);
            $entidad->autocommit();
            //Facturas
            $entidad->updateMultiColum('ptoemision', $param['txtfactptoemision'], 'documento', '1');
            $entidad->updateMultiColum('secuencial', $param['txtfactsecuencial'], 'documento', '1');
            //Notas de Credito
            $entidad->updateMultiColum('ptoemision', $param['txtncptoemision'], 'documento', '2');
            $entidad->updateMultiColum('secuencial', $param['txtncsecuencial'], 'documento', '2');
            //Notas de Debito
            $entidad->updateMultiColum('ptoemision', $param['txtndptoemision'], 'documento', '3');
            $entidad->updateMultiColum('secuencial', $param['txtndsecuencial'], 'documento', '3');
            //Retenciones
            $entidad->updateMultiColum('ptoemision', $param['txtretenptoemision'], 'documento', '4');
            $entidad->updateMultiColum('secuencial', $param['txtretensecuencial'], 'documento', '4');
            //Guias Remision
            $entidad->updateMultiColum('ptoemision', $param['txtgrptoemision'], 'documento', '5');
            $entidad->updateMultiColum('secuencial', $param['txtgrsecuencial'], 'documento', '5');
            //Cobros
            $entidad->updateMultiColum('ptoemision', $param['txtccptoemision'], 'documento', '6');
            $entidad->updateMultiColum('secuencial', $param['txtccsecuencial'], 'documento', '6');
            
            try {
                //Correr el script de instalacion de facturacion electronica
                $fichero = 'docs/install/0007CXC.sql';  // Ruta al fichero que vas a cargar.
                $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                        mysqli_autocommit($conx,FALSE);
                        // Si la linea acaba en ; hemos encontrado el final de la sentencia
                        if (substr($linea, -1, 1) == ';')
                        {
                            // Ejecutamos la consulta
                            mysqli_set_charset($conx, "utf8");
                            //mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                            mysqli_query($conx, $temp);
                            // Limpiamos sentencia temporal
                            $temp = '';
                        }
                        if (!mysqli_commit($conx))
                        {
                            mysqli_rollback($conx);
                            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));                    
                            exit();
                        }
                }
            } catch (Exception $ex) {
                mysqli_rollback($conx);
                $conx->close();
                die(json_encode(array('status' => 'ERROR', 'descripcion' => $ex->getMessage())));
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Ventas');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonSecuencialFe($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Guardamos que se acabo de instalar
            
            //Insertar en la web transaccional
            $entidad = new Entidades\Cc00030($this->adapter);
            $entidad->autocommit();
            //Facturas
            $entidad->updateMultiColum('ptoemision', $param['txtfactptoemision'], 'documento', '1');
            $entidad->updateMultiColum('secuencial', $param['txtfactsecuencial'], 'documento', '1');
            //Notas de Credito
            $entidad->updateMultiColum('ptoemision', $param['txtncptoemision'], 'documento', '2');
            $entidad->updateMultiColum('secuencial', $param['txtncsecuencial'], 'documento', '2');
            //Notas de Debito
            $entidad->updateMultiColum('ptoemision', $param['txtndptoemision'], 'documento', '3');
            $entidad->updateMultiColum('secuencial', $param['txtndsecuencial'], 'documento', '3');
            //Retenciones
            $entidad->updateMultiColum('ptoemision', $param['txtretenptoemision'], 'documento', '4');
            $entidad->updateMultiColum('secuencial', $param['txtretensecuencial'], 'documento', '4');
            //Guias Remision
            $entidad->updateMultiColum('ptoemision', $param['txtgrptoemision'], 'documento', '5');
            $entidad->updateMultiColum('secuencial', $param['txtgrsecuencial'], 'documento', '5');
            //Cobros
            $entidad->updateMultiColum('ptoemision', $param['txtccptoemision'], 'documento', '6');
            $entidad->updateMultiColum('secuencial', $param['txtccsecuencial'], 'documento', '6');
            
            try {
                //Correr el script de instalacion de facturacion electronica
                $fichero = 'docs/install/0007CXC.sql';  // Ruta al fichero que vas a cargar.
                $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                        mysqli_autocommit($conx,FALSE);
                        // Si la linea acaba en ; hemos encontrado el final de la sentencia
                        if (substr($linea, -1, 1) == ';')
                        {
                            // Ejecutamos la consulta
                            mysqli_set_charset($conx, "utf8");
                            //mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                            mysqli_query($conx, $temp);
                            // Limpiamos sentencia temporal
                            $temp = '';
                        }
                        if (!mysqli_commit($conx))
                        {
                            mysqli_rollback($conx);
                            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));                    
                            exit();
                        }
                }
            } catch (Exception $ex) {
                mysqli_rollback($conx);
                $conx->close();
                die(json_encode(array('status' => 'ERROR', 'descripcion' => $ex->getMessage())));
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'Fe Solo');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonSecuencialOptica($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Guardamos que se acabo de instalar
            
            //Insertar en la web transaccional
            $entidad = new Entidades\Cc00030($this->adapter);
            $entidad->autocommit();
            //Facturas
            $entidad->updateMultiColum('ptoemision', $param['txtfactptoemision'], 'documento', '1');
            $entidad->updateMultiColum('secuencial', $param['txtfactsecuencial'], 'documento', '1');
            //Notas de Credito
            $entidad->updateMultiColum('ptoemision', $param['txtncptoemision'], 'documento', '2');
            $entidad->updateMultiColum('secuencial', $param['txtncsecuencial'], 'documento', '2');
            //Notas de Debito
            $entidad->updateMultiColum('ptoemision', $param['txtndptoemision'], 'documento', '3');
            $entidad->updateMultiColum('secuencial', $param['txtndsecuencial'], 'documento', '3');
            //Retenciones
            $entidad->updateMultiColum('ptoemision', $param['txtretenptoemision'], 'documento', '4');
            $entidad->updateMultiColum('secuencial', $param['txtretensecuencial'], 'documento', '4');
            //Guias Remision
            $entidad->updateMultiColum('ptoemision', $param['txtgrptoemision'], 'documento', '5');
            $entidad->updateMultiColum('secuencial', $param['txtgrsecuencial'], 'documento', '5');
            //Cobros
            $entidad->updateMultiColum('ptoemision', $param['txtccptoemision'], 'documento', '6');
            $entidad->updateMultiColum('secuencial', $param['txtccsecuencial'], 'documento', '6');
            
            try {
                //Correr el script de instalacion de facturacion electronica
                $fichero = 'docs/install/0007CXC.sql';  // Ruta al fichero que vas a cargar.
                $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                        mysqli_autocommit($conx,FALSE);
                        // Si la linea acaba en ; hemos encontrado el final de la sentencia
                        if (substr($linea, -1, 1) == ';')
                        {
                            // Ejecutamos la consulta
                            mysqli_set_charset($conx, "utf8");
                            //mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                            mysqli_query($conx, $temp);
                            // Limpiamos sentencia temporal
                            $temp = '';
                        }
                        if (!mysqli_commit($conx))
                        {
                            mysqli_rollback($conx);
                            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));                    
                            exit();
                        }
                }
            } catch (Exception $ex) {
                mysqli_rollback($conx);
                $conx->close();
                die(json_encode(array('status' => 'ERROR', 'descripcion' => $ex->getMessage())));
            }
            $conx->close();

            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'CXC');

            $cliente = new Entidades\Sis00050($this->adapter);
            $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));

            $insert = new \Entidades\Sis20012($this->adapter);

            $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
            if ($existe['numrows']==0)
            {
                $insert->setActivo(1);
                $insert->setClienteid($cliente['id']);
                $insert->setRuc($empresa['ruc']);
                $insert->setModulesid($idModulo['id']);
                $insert->save();
            }

            $insert->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00010Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00010($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonPeriodo($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert')
        {
            //Validamos que no se inserte 2 veces
            $entidad = new \Entidades\Sis40300($this->adapter);
            $existe_empresa = $entidad->getCountMulti('anio', 'empresa', $this->session->get('empresa'),'anio',$param['anio']);
            if ($existe_empresa['numrows']==0) {
                //Guardar la empresa en General
                $entidad->autocommit();
                
                $entidad->setAnio($param['anio']);
                $entidad->setEmpresa($this->session->get('empresa'));
                $entidad->setFecha_inicial($param['anio'].'-01-01');
                $entidad->setFecha_final($param['anio'].'-12-31');
                $entidad->setHistorico(0);
                $entidad->setNum_periodos(12);
                $entidad->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-01-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(1);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-01-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(1);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-01-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(1);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-01-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(1);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-02-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(2);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-02-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(2);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-02-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(2);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-02-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(2);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-03-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(3);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-03-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(3);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-03-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(3);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-03-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(3);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-04-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(4);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-04-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(4);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-04-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(4);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-04-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(4);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-05-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(5);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-05-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(5);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-05-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(5);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-05-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(5);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-06-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(6);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-06-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(6);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-06-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(6);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-06-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(6);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-07-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(7);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-07-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(7);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-07-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(7);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-07-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(7);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-08-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(8);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-08-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(8);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-08-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(8);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-08-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(8);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-09-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(9);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-09-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(9);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-09-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(9);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-09-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(9);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-10-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(10);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-10-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(10);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-10-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(10);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-10-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(10);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-11-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(11);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-11-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(11);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-11-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(11);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-11-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(11);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-12-01');
                $detalle->setModulo('VENTAS');
                $detalle->setPeriodo(12);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-12-01');
                $detalle->setModulo('COMPRAS');
                $detalle->setPeriodo(12);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-12-01');
                $detalle->setModulo('INVENTARIO');
                $detalle->setPeriodo(12);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $detalle = new \Entidades\Sis40310($this->adapter);
                $detalle->setActivo(1);
                $detalle->setEmpresa($this->session->get('empresa'));
                $detalle->setFecha($param['anio'].'-12-01');
                $detalle->setModulo('FINANCIERO');
                $detalle->setPeriodo(12);
                $detalle->setSis40300id($param['anio']);
                $detalle->save();
                
                $entidad->commit();
                
                //Mensaje si todo sale correcto
                die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
            }
            else
            {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'El periodo fiscal que esta digitando ya existe.')));
            }
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonTamanioSegmento($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert')
        {
            //Validamos que no se inserte 2 veces
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            $seg = new \Entidades\Fin40030($this->adapter);
            
            $seg->autocommit();
            
            $dtseg = $seg->getMulti('empresa', $this->session->get('empresa'));
            foreach ($dtseg as $segmento) {
                $seg->updateMultiColum('tamanio', $param['valorseg'.$segmento['id']], 'id', $param['idseg'.$segmento['id']]);
            }
            
            $seg->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonCuenta($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert')
        {
            //Validamos que no se inserte 2 veces
            $empresa = new \Entidades\Sis00100($this->adapter);
            $entidad = new Entidades\Fin00000($this->adapter);
            $seg = new \Entidades\Fin40030($this->adapter);
            
            $entidad->autocommit();
            //Validar cuenta
            $dtseg = $seg->getMulti('empresa', $this->session->get('empresa'));
            $dtempresa = $empresa->getMulti('id', $this->session->get('empresa'));
            
            $seg1 = '';
            $seg2 = '';
            $seg3 = '';
            $seg4 = '';
            $seg5 = '';
            $seg6 = '';
            $seg7 = '';
            $tamanio = strlen($param['cuenta']);
            $valor_aceptable = 0;
            $aceptado = false;
            $i = 0;
            $valor_str = 0;
            foreach ($dtseg as $segmento) {
                $i++;
                $valor_aceptable += $segmento['tamanio'];

                if ($tamanio >= $valor_aceptable) {
                    switch ($i) {
                        case '1':
                            $seg1 = substr($param['cuenta'], 0,$segmento['tamanio']);
                            $valor_str += $segmento['tamanio'] + 1;
                            break;
                        case '2':
                            $seg2 = substr($param['cuenta'], $valor_str,$segmento['tamanio']);
                            $valor_str += $segmento['tamanio'] + 1;
                            break;
                        case '3':
                            $seg3 = substr($param['cuenta'], $valor_str,$segmento['tamanio']);
                            $valor_str += $segmento['tamanio'] + 1;
                            break;
                        case '4':
                            $seg4 = substr($param['cuenta'], $valor_str,$segmento['tamanio']);
                            $valor_str += $segmento['tamanio'] + 1;
                            break;
                        case '5':
                            $seg5 = substr($param['cuenta'], $valor_str,$segmento['tamanio']);
                            $valor_str += $segmento['tamanio'] + 1;
                            break;
                        case '6':
                            $seg6 = substr($param['cuenta'], $valor_str,$segmento['tamanio']);
                            $valor_str += $segmento['tamanio'] + 1;
                            break;
                        case '7':
                            $seg7 = substr($param['cuenta'], $valor_str,$segmento['tamanio']);
                            $valor_str += $segmento['tamanio'] + 1;
                            break;
                    }
                }
                if ($valor_aceptable == $tamanio)
                {
                    $aceptado = true;
                }
                else
                {
                    $valor_aceptable += 1;
                }
            }
            
            if (!$aceptado) {
                $entidad->rollback();
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'La cuenta no tiene el formato correcto.')));
            }
            
            if ($i>$dtempresa['segmentos_cuentas']) {
                $entidad->rollback();
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'La cuenta tiene más segmentos de los configurados.')));
            }
            
            $entidad->setActivo(1);
            $entidad->setDescripcion($param['descripcion']);
            $entidad->setEmpresa($this->session->get('empresa'));
            $entidad->setFcreacion(date('Y-m-d'));
            $entidad->setMovimiento($param['movimiento']==='true'?1:0);
            $entidad->setUsuario($this->session->get('usuario'));
            $entidad->setSeg1($seg1);
            $entidad->setSeg2($seg2);
            $entidad->setSeg3($seg3);
            $entidad->setSeg4($seg4);
            $entidad->setSeg5($seg5);
            $entidad->setSeg6($seg6);
            $entidad->setSeg7($seg7);
            $entidad->setFin40020id($param['naturaleza']);
            $entidad->setFin40000id($param['tipocuenta']);
            $entidad->save();
            
            $entidad->commit();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonScriptContabilidad()
    {
        //Guardamos que se acabo de instalar
        $entidad = new Entidades\Sis00060($this->adapter);
        $idModulo = $entidad->getMulti('nombre', 'Financiero');
        
        $cliente = new Entidades\Sis00050($this->adapter);
        $cliente = $cliente->getMulti('usuario', $this->session->get('usuario'));

        $empresa = new Entidades\Sis00100($this->adapter);
        $empresa = $empresa->getMulti('id', $this->session->get('empresa'));
        
        $insert = new \Entidades\Sis20012($this->adapter);
        $existe = $insert->getCountMulti('id', 'modulesid', $idModulo['id'],'ruc', $empresa['ruc']);
        if ($existe['numrows']==0) {
            
            $insert->setActivo(1);
            $insert->setClienteid($cliente['id']);
            $insert->setRuc($empresa['ruc']);
            $insert->setModulesid($idModulo['id']);
            $insert->save();
        }
        
        //Correr el script de instalacion de facturacion electronica
        $fichero = 'docs/install/0009Financiero.sql';  // Ruta al fichero que vas a cargar.
        $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, $this->session->get('bdcliente')) or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
            mysqli_autocommit($conx,FALSE);
            // Si la linea acaba en ; hemos encontrado el final de la sentencia
            if (substr($linea, -1, 1) == ';') {
                // Ejecutamos la consulta
                mysqli_set_charset($conx, "utf8");
                mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                // Limpiamos sentencia temporal
                $temp = '';
            }
            if (!mysqli_commit($conx)) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error al realizar commit')));
                mysqli_rollback($conx);
                exit();
            }
        }
        $conx->close();
        die(json_encode(array('status' => 'OK', 'descripcion' => 'Instalado correctamente.')));
    }
    
    /*
     * MANEJO DE FIRMA ELECTRONICA
     */
    
    public function getDatosFirma($firma,$clave,$ruc)
    {
        if (!is_file($firma)) return "La firma tiene un formato erroneo, por favor volver a subir la firma.";
        // Extract public and private keys from store
        if (openssl_pkcs12_read(file_get_contents($firma), $certs, $clave))
        {
            $publicKey = openssl_x509_read($certs['extracerts'][0]);
            $certData = openssl_x509_parse($publicKey);
            //Actualizamos Fechas
            $productos = new Entidades\Sis00100($this->adapter);
            $productos->updateMultiColum("fechaCreacion", date('Y-m-d', $certData['validFrom_time_t']),'ruc',$ruc);
            $productos->updateMultiColum("fechaValidez", date('Y-m-d', $certData['validTo_time_t']),'ruc',$ruc);
            return "OK";
        }
        else
        {
            return "La contraseña es erronea, por favor digitar correctamente la contraseña.";
        }
    }
    
    public function getValidadRucFirma($firma,$clave,$ruc)
    {
        if (!is_file($firma)) return "La firma tiene un formato erroneo, por favor volver a subir la firma.";
        // Extract public and private keys from store
        if (openssl_pkcs12_read(file_get_contents($firma), $certs, $clave))
        {
            $publicKey = openssl_x509_read($certs['extracerts'][0]);
            $certData = openssl_x509_parse($publicKey);
            print_r($publicKey);
            //Actualizamos Fechas
            $publicKey = openssl_pkey_get_public($certs['extracerts'][0]);
            $data = openssl_pkey_get_details($publicKey);
            print_r($publicKey);
            die();
            return "OK";
        }
        else
        {
            return "La contraseña es erronea, por favor digitar correctamente la contraseña.";
        }
    }
    
    /**
     * MANEJO DE BUSQUEDAS
     */
    
    public function buscarcuentas()
    {
        $datos = new Models\Fin00000Model($this->adapter);
        //Limpiar la Variable
        if ($_POST['q'] != 'undefined') {
            $q = $_POST['q'];
        }else{
            $q = '';
        }
        $numrows = $datos->getCount('id');
        //Muchos Datos
        //Paginacion
        //las variables de paginación
        $page = (isset($_POST['page']) && !empty($_POST['page']))?$_POST['page']:1;
        $per_page = 20; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Consultar Inventario
        $datos = $datos->getTablePaginacion($q,$offset,$per_page);
        if(globalFunctions::es_bidimensional($datos))
        {
            $total_pages = ceil($numrows["numrows"]/$per_page);

            $tabla = new \dti_table();
            $tabla->setIdtable('tb_cuentas');
            $tabla->setTitulo('Lista de Cuentas');
            $tabla->setColumnas('cuenta,descripcion,movimiento,naturaleza,categoria');
            $tabla->setEtiquetas('Cuentas,Descripcion,Movimiento,Naturaleza,Categoria');
            $tabla->setDatos($datos);
            //$tabla->setEliminar('mies/listmies_comunidades');
            $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

            echo $tabla->gettable('Dpaginacion');
        }
        else if (isset($datos["cuenta"]))
        {
            $total_pages = ceil($numrows["numrows"]/$per_page);

            $tabla = new \dti_table();
            $tabla->setIdtable('tb_cuentas');
            $tabla->setTitulo('Lista de Cuentas');
            $tabla->setColumnas('cuenta,descripcion,movimiento,naturaleza,categoria');
            $tabla->setEtiquetas('Cuentas,Descripcion,Movimiento,Naturaleza,Categoria');
            $tabla->setDatos($datos);
            //$tabla->setEliminar('mies/listmies_comunidades');
            $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

            echo $tabla->gettable('Dpaginacion');
        }
        else
        {
            $tabla = new \dti_table();
            $tabla->setIdtable('tb_cuentas');
            $tabla->setTitulo('Lista de Cuentas');
            $tabla->setColumnas('cuenta,descripcion,movimiento,naturaleza,categoria');
            $tabla->setEtiquetas('Cuentas,Descripcion,Movimiento,Naturaleza,Categoria');
            $tabla->setDatos(null);

            echo $tabla->gettable('Dpaginacion');
        }
    }

    public function pruebahtml(){
        $html='
        <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-md-flex align-items-center">
                    <div>
                        <h4 class="card-title">Productos</h4>
                        <h5 class="card-subtitle">Lista de productos</h5>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Búsqueda de productos" aria-label="Búsqueda de productos" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary" type="button"><i class="ti-search"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table no-wrap v-middle">
                        <thead>
                            <tr class="border-0">
                                <th class="border-0">+</th>
                                <th class="border-0">ID</th>
                                <th class="border-0">CODIGO</th>
                                <th class="border-0">PRECIO</th>
                                <th class="border-0">DESCRIPCIÓN</th>
                                <th class="border-0">STOCK</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                        <div class="popover-icon">
                                        <a class="btn-circle btn btn-outline-secondary" href="javascript:void(0)">+</a>
                                        </div>
                                </td>
                               
                                <td>Elite Admin</td>
                                <td>
                                    DESC
                                </td>
                                <td>$25</td>
                                <td>DESCRIPCION PRODUCTOS</td>
                                <td>30</td>
                            </tr>
                            <tr>
                                <td>
                                        <div class="popover-icon">
                                        <a class="btn-circle btn btn-outline-secondary" href="javascript:void(0)">+</a>
                                        </div>
                                </td>
                            
                                <td>Elite Admin</td>
                                <td>
                                    DESC
                                </td>
                                <td>$50</td>
                                <td>Lorem ipsum dolor sit, amet consectetur<br> adipisicing elit.  Adipisci facilis nemo, <br>corporis eaque optio saepe nesciunt <br> quos porro repudiandae, eos cupiditate.<br> Delectus maiores accusantium exercitationem laborum, <br> inventore sed sint dolores.</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>
                                        <div class="popover-icon">
                                        <a class="btn-circle btn btn-outline-secondary" href="javascript:void(0)">+</a>
                                        </div>
                                </td>
                            
                                <td>Elite Admin</td>
                                <td>
                                   DESC
                                </td>
                                <td>$80</td>
                                <td>Descripcion del producto</td>
                                <td>45</td>
                                
                            </tr>
                            <tr>
                                <td>
                                        <div class="popover-icon">
                                        <a class="btn-circle btn btn-outline-secondary" href="javascript:void(0)">+</a>
                                        </div>
                                </td>
                                <td>Elite Admin</td>
                                <td>
                                   DESC
                                </td>
                                <td>$100</td>
                                <td>Lorem ipsum dolor sit, amet consectetur <br> adipisicing elit.  Animi architecto eaque sint <br> necessitatibus unde quos quaerat  similique <br>explicabo voluptatem consectetur ut, <br> inventore neque in esse nemo odit quas, vero suscipit.</td>
                                <td>12</td>
                                
                            </tr>
                        </tbody>
                    </table>

                    
                    
                </div>
            </div>
        </div>
    </div>';
    $contenedor = globalFunctions::renderizar($this->website,array(
        'modulo'=>6,
        'section'=>array(
            'boxquick'=>$html,
        )
    ));

    $this->render($this->website,__CLASS__,array(
        "layout"=>$contenedor,
        "titulo"=>"Bienvenido",
    ));
    }

    

}