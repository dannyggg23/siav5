<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class SeguridadController extends Controllers
{
    private $session,$conectar,$adapter,$layout,$website,$cliente,$login_empresa;
    
    public function __construct()
    {
        $this->session = new Session();
        $this->session->init();
        //Conexion a la base de datos
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        //Validar que tenga permisos de seguir usando el sistema
        if (isset($_SESSION['usuario']) && isset($_SESSION['bdcliente']) && isset($_SESSION['idRand']))
        {
            $validar_login = new Models\Sis50000Model($this->adapter);
            $dtvalidar_login = $validar_login->getSessionActiva($_SESSION['usuario'], $_SESSION['bdcliente'], $_SESSION['idRand']);
            if ($dtvalidar_login['numrows']==0)
            {
                $this->redirect('default', 'logout');
            }
        }
        //Traemos los datos del portal configurados
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        //Traemos los datos del cliente
        $this->cliente= new Models\Sis00050Model($this->adapter);
        //Cargamos el layout
        $this->layout = new dti_layout($this->website);
        //Cargamos la empresa logueada
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        $this->login_empresa = new \Entidades\Sis00100($this->adapter);
        $this->login_empresa = $this->login_empresa->getMulti('ruc', $_SESSION['rucEmpresa']);
    }
    
    public function exec()
    {
        $this->index();
    }
    
    public function index()
    {
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        
        $bienvenido = '<h2>Bienvenido al Modulo Seguridad</h2>';
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'boxquick'=>$bienvenido,
            )
        ),$this->login_empresa);

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Bienvenido",
        ));
    }
    
    public function listEmpresa()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuario'))) $this->redirect("seguridad","login");
        
        $datos = new Models\Sis00100Model($this->adapter);
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
            $datos = $datos->getTablePaginacion($q,$offset,$per_page);
            
            if(globalFunctions::es_bidimensional($datos))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_empresas');
                $tabla->setTitulo('Lista de Empresas');
                $tabla->setColumnas('ruc,razonsocial,nomempresa,telefono,direccion');
                $tabla->setEtiquetas('Ruc,Razon Social,Nom Empresa,Telefono,Direccion');
                $tabla->setDatos($datos);
                //Si es demo no activar
                if ($this->session->get('usuario')!='demo')
                {
                    $tabla->setEditar('seguridad/editempresa',true,'mdleditEmpresa');
                }
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"]))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_empresas');
                $tabla->setTitulo('Lista de Empresas');
                $tabla->setColumnas('ruc,razonsocial,nomempresa,telefono,direccion');
                $tabla->setEtiquetas('Ruc,Razon Social,Nom Empresa,Telefono,Direccion');
                $tabla->setDatos($datos);
                if ($this->session->get('usuario')!='demo')
                {
                    $tabla->setEditar('seguridad/editempresa',true,'mdleditEmpresa');
                }
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else
            {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_empresas');
                $tabla->setTitulo('Lista de Empresas');
                $tabla->setColumnas('ruc,razonsocial,nomempresa,telefono,direccion');
                $tabla->setEtiquetas('Ruc,Razon Social,Nom Empresa,Telefono,Direccion');
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
                    $this->redirect('seguridad','listEmpresa');
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
                $tabla->setIdtable('tb_empresas');
                $tabla->setTitulo('Lista de Empresas');
                $tabla->setColumnas('ruc,razonsocial,nomempresa,telefono,direccion');
                $tabla->setEtiquetas('Ruc,Razon Social,Nom Empresa,Telefono,Direccion');
                
                //Si es demo no activar
                if ($this->session->get('usuario')!='demo')
                {
                    $tabla->setEditar('seguridad/editempresa',true,'mdleditEmpresa');
                }

                $tabla->setFiltro(true,'goTablePaginacion','seguridad','listempresa');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'seguridad/index',
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
                ),$this->login_empresa);

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Empresas",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function listEmpresaFe()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuario'))) $this->redirect("seguridad","login");
        
        $datos = new Models\Sis00100Model($this->adapter);
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
            $datos = $datos->getTablePaginacion($q,$offset,$per_page);
            
            if(globalFunctions::es_bidimensional($datos))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_empresas');
                $tabla->setTitulo('Lista de Empresas');
                $tabla->setColumnas('ruc,razonsocial,nomempresa,telefono,direccion');
                $tabla->setEtiquetas('Ruc,Razon Social,Nom Empresa,Telefono,Direccion');
                $tabla->setDatos($datos);
                //Si es demo no activar
                if ($this->session->get('usuario')!='demo')
                {
                    $tabla->setEditar('seguridad/editempresaFe',true,'mdleditEmpresa');
                }
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"]))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_empresas');
                $tabla->setTitulo('Lista de Empresas');
                $tabla->setColumnas('ruc,razonsocial,nomempresa,telefono,direccion');
                $tabla->setEtiquetas('Ruc,Razon Social,Nom Empresa,Telefono,Direccion');
                $tabla->setDatos($datos);
                if ($this->session->get('usuario')!='demo')
                {
                    $tabla->setEditar('seguridad/editempresaFe',true,'mdleditEmpresa');
                }
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else
            {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_empresas');
                $tabla->setTitulo('Lista de Empresas');
                $tabla->setColumnas('ruc,razonsocial,nomempresa,telefono,direccion');
                $tabla->setEtiquetas('Ruc,Razon Social,Nom Empresa,Telefono,Direccion');
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
                    $this->redirect('seguridad','listEmpresaFe');
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
                $tabla->setIdtable('tb_empresas');
                $tabla->setTitulo('Lista de Empresas');
                $tabla->setColumnas('ruc,razonsocial,nomempresa,telefono,direccion');
                $tabla->setEtiquetas('Ruc,Razon Social,Nom Empresa,Telefono,Direccion');
                
                //Si es demo no activar
                if ($this->session->get('usuario')!='demo')
                {
                    $tabla->setEditar('seguridad/editempresaFe',true,'mdleditEmpresa');
                }

                $tabla->setFiltro(true,'goTablePaginacion','seguridad','listempresaFe');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'fe/index',
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
                ),$this->login_empresa);

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Empresas",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function editempresa($param=array(),$url='')
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("seguridad","login"); }
        if (!isset($_SESSION["empresa"])) { $this->redirect("seguridad","selectempresa"); }
        
        if (isset($param['panel']))
        {
            //Btn Agrupados
            $btngroup = new dti_builder_buttons();
            
            $btngroup->setGroupButtons(array(
                    'clic'=>'seguridad/listEmpresa',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));
            
            if (isset($param['edit']))
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'saveEmpresa',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonEmpresa("Update");',
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonEmpresa("Insert");',
                ));
            }
            
            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();
            
            $paneldetalle = new \dti_panel();
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresa'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresa'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'generales', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'Datos Generales', //Nombre del encabezado
            ), $formulario);//$detalle
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaConfig'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaConfig'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'config', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'Configuracion', //Nombre del encabezado
            ), $formulario);//$detalle
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaSmtp'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaSmtp'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'smtp', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'SMTP', //Nombre del encabezado
            ), $formulario);//$detalle
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaLogo'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaLogo'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'logo', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'logo', //Nombre del encabezado
            ), $formulario);//$detalle
            
            $contpaneldetalle = $paneldetalle->getPanel();
            
            $ok = strlen($url)>0?'location.href="'.$url.'"':'location.href="seguridad/listempresa"';
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonempresa',
                'data'=>"{'ruc': ruc,'razonsocial':razonsocial,'nomempresa':nomempresa,'direccion':direccion,'telefono':telefono
                        ,'rucrepresentante':rucrepresentante,'representante':representante,'obligaconta':obligaconta,'contriespecial':contriespecial
                        ,'valcontriespecial':valcontriespecial,'languageid':languageid,'template':template,'zona_horaria':zona_horaria,'moneda':moneda
                        ,'caracter_decimal':caracter_decimal,'caracter_miles':caracter_miles,'segmentos_cuentas':segmentos_cuentas,'separador_segmentos':separador_segmentos
                        ,'decimales_ventas':decimales_ventas,'decimales_compras':decimales_compras,'smtp_hostname':smtp_hostname,'smtp_port':smtp_port,'smtp_username':smtp_username,'smtp_password':smtp_password
                        ,'smtpdefecto':smtpdefecto,'correo':correo,'accionSql': accionSql}",
                'ok'=>$ok,
            ));
            $datos_empresa = $dti_ajax->getAjax();
            
            //Validar si esta instalado la firma electronica
            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'FE');

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));
            
            $existe = 0;
            if (isset($idModulo['id'])) {
                $feinstall = new Entidades\Sis20012($this->adapter);
                $dtfeinstall = $feinstall->getCountMulti('id','modulesid', $idModulo['id'], 'ruc', $empresa['ruc']);
                $existe++;
            }
            
            if ($existe>0)
            {
                //Tiene Facturacion Electronica Instalado
                $script = "<script type='text/javascript'>
                    document.getElementById('txtvalcontriespecial').disabled = true;
                    
                    if(document.getElementById('txtsmtpdefecto').checked)
                    {
                        document.getElementById('txtsmtp_hostname').disabled = true;
                        document.getElementById('txtsmtp_port').disabled = true;
                        document.getElementById('txtsmtp_username').disabled = true;
                        document.getElementById('txtsmtp_password').disabled = true;
                    }

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
                    
                    function setJsonEmpresa(accionSql=''){
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea actualizar datos de la empresa!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var ruc,razonsocial,nomempresa,direccion,telefono,rucrepresentante,representante,obligaconta,contriespecial,valcontriespecial
                                    ,languageid,template,zona_horaria,moneda,caracter_decimal,caracter_miles,segmentos_cuentas
                                    ,separador_segmentos,decimales_ventas,decimales_compras,smtp_hostname,smtp_port,smtp_username
                                    ,smtp_password,smtpdefecto,logo,ambiente,firma,clave,correo;
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
                                    smtp_hostname = document.getElementById('txtsmtp_hostname').value;
                                    smtp_port = document.getElementById('txtsmtp_port').value;
                                    smtp_username = document.getElementById('txtsmtp_username').value;
                                    smtp_password = document.getElementById('txtsmtp_password').value;
                                    smtpdefecto = document.getElementById('txtsmtpdefecto').checked;
                                    ambiente = document.getElementById('txtambiente').value;
                                    clave = document.getElementById('txtclavefirma').value;
                                    correo = document.getElementById('txtcorreo').value;

                                    if (ruc != '' && razonsocial != '' && telefono != '' && nomempresa != '' && direccion != '' 
                                        && rucrepresentante != '' && representante != ''
                                        && languageid > 0 && template > 0 && zona_horaria > 0 && moneda > 0 
                                        && caracter_decimal != '' && caracter_miles != '' && segmentos_cuentas != ''
                                        && separador_segmentos != '' && decimales_ventas != '' && decimales_compras != ''
                                        && ambiente > 0 && clave != '' && correo != '')
                                    {
                                        if (contriespecial) {
                                            if (valcontriespecial != '') {
                                                Swal.fire('Error!', 'El valor de contribuyente especial es obligatorio!', 'error');
                                            }
                                        }
                                        else if (smtpdefecto)
                                        {
                                            var imagen_data = new FormData();
                                            imagen_data.append('firma',$('#txtfirma')[0].files[0]);
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
                                                        var imagen_data = new FormData();
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
                                                                    ".$datos_empresa."
                                                                }
                                                            }
                                                          });
                                                    }
                                                    else
                                                    {
                                                        Swal.fire('Error!', ''+data.descripcion+'!', 'error');
                                                    }
                                                }
                                              });
                                        }
                                        else if(smtp_hostname != '' && smtp_port != '' && smtp_username != '' && smtp_password != '')
                                        {
                                            var imagen_data = new FormData();
                                            imagen_data.append('firma',$('#txtfirma')[0].files[0]);
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
                                                        var imagen_data = new FormData();
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
                                                                    ".$datos_empresa."
                                                                }
                                                            }
                                                          });
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
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                        }
                                    }
                                    else{
                                        Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }</script>";
            }
            else
            {
                //Tiene Facturacion Electronica Instalado
                $script = "<script type='text/javascript'>
                    document.getElementById('txtvalcontriespecial').disabled = true;
                    
                    if(document.getElementById('txtsmtpdefecto').checked)
                    {
                        document.getElementById('txtsmtp_hostname').disabled = true;
                        document.getElementById('txtsmtp_port').disabled = true;
                        document.getElementById('txtsmtp_username').disabled = true;
                        document.getElementById('txtsmtp_password').disabled = true;
                    }

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
                    
                    function setJsonEmpresa(accionSql=''){
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea actualizar datos de la empresa!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var ruc,razonsocial,nomempresa,direccion,telefono,rucrepresentante,representante,obligaconta,contriespecial,valcontriespecial
                                    ,languageid,template,zona_horaria,moneda,caracter_decimal,caracter_miles,segmentos_cuentas
                                    ,separador_segmentos,decimales_ventas,decimales_compras,smtp_hostname,smtp_port,smtp_username
                                    ,smtp_password,smtpdefecto,logo,correo;
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
                                    smtp_hostname = document.getElementById('txtsmtp_hostname').value;
                                    smtp_port = document.getElementById('txtsmtp_port').value;
                                    smtp_username = document.getElementById('txtsmtp_username').value;
                                    smtp_password = document.getElementById('txtsmtp_password').value;
                                    smtpdefecto = document.getElementById('txtsmtpdefecto').checked;
                                    correo = document.getElementById('txtcorreo').value;

                                    if (ruc != '' && razonsocial != '' && telefono != '' && nomempresa != '' && direccion != '' 
                                        && rucrepresentante != '' && representante != ''
                                        && languageid > 0 && template > 0 && zona_horaria > 0 && moneda > 0 
                                        && caracter_decimal != '' && caracter_miles != '' && segmentos_cuentas != ''
                                        && separador_segmentos != '' && decimales_ventas != '' && decimales_compras != ''
                                        && correo != '')
                                    {
                                        if (contriespecial) {
                                            if (valcontriespecial != '') {
                                                Swal.fire('Error!', 'El valor de contribuyente especial es obligatorio!', 'error');
                                            }
                                        }
                                        else if (smtpdefecto)
                                        {
                                            var imagen_data = new FormData();
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
                                                        ".$datos_empresa."
                                                    }
                                                }
                                              });
                                        }
                                        else if(smtp_hostname != '' && smtp_port != '' && smtp_username != '' && smtp_password != '')
                                        {
                                            var imagen_data = new FormData();
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
                                                        ".$datos_empresa."
                                                    }
                                                }
                                              });
                                        }
                                        else
                                        {
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                        }
                                    }
                                    else{
                                        Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }</script>";
            }

            die(json_encode(array(
                'status' => 'OK',
                'layout' => $btngrp['layout'].$contpaneldetalle,
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
    }
    
    public function editempresaFe($param=array(),$url='')
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("seguridad","login"); }
        if (!isset($_SESSION["empresa"])) { $this->redirect("seguridad","selectempresa"); }
        
        if (isset($param['panel']))
        {
            //Btn Agrupados
            $btngroup = new dti_builder_buttons();
            
            $btngroup->setGroupButtons(array(
                    'clic'=>'seguridad/listEmpresaFe',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));
            
            if (isset($param['edit']))
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'saveEmpresa',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonEmpresa("Update");',
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonEmpresa("Insert");',
                ));
            }
            
            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();
            
            $paneldetalle = new \dti_panel();
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresa'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresa'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'generales', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'Datos Generales', //Nombre del encabezado
            ), $formulario);//$detalle
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaConfig'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaConfig'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'config', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'Configuracion', //Nombre del encabezado
            ), $formulario);//$detalle
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaSmtp'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaSmtp'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'smtp', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'SMTP', //Nombre del encabezado
            ), $formulario);//$detalle
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaLogo'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmEmpresaLogo'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'logo', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'logo', //Nombre del encabezado
            ), $formulario);//$detalle

            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmFirmaElectronica'),'orden',$param['edit']);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmFirmaElectronica'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            $paneldetalle->setPanel(array(
                'page'=>'fe', //Siempre minusculas
                'icono'=>'fa-home', //Siempre minusculas y de font-awesome
                'titulo'=>'Firma Electronica', //Nombre del encabezado
            ), $formulario);//$detalle
            
            $contpaneldetalle = $paneldetalle->getPanel();
            
            $ok = strlen($url)>0?'location.href="'.$url.'"':'location.href="seguridad/listempresaFe"';
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'install/jsonempresa',
                'data'=>"{'ruc': ruc,'razonsocial':razonsocial,'nomempresa':nomempresa,'direccion':direccion,'telefono':telefono
                        ,'rucrepresentante':rucrepresentante,'representante':representante,'obligaconta':obligaconta,'contriespecial':contriespecial
                        ,'valcontriespecial':valcontriespecial,'languageid':languageid,'template':template,'zona_horaria':zona_horaria,'moneda':moneda
                        ,'caracter_decimal':caracter_decimal,'caracter_miles':caracter_miles,'segmentos_cuentas':segmentos_cuentas,'separador_segmentos':separador_segmentos
                        ,'decimales_ventas':decimales_ventas,'decimales_compras':decimales_compras,'smtp_hostname':smtp_hostname,'smtp_port':smtp_port,'smtp_username':smtp_username,'smtp_password':smtp_password
                        ,'smtpdefecto':smtpdefecto,'ambiente': ambiente,'firma':firma,'clave':clave,'correo':correo,'accionSql': accionSql}",
                'ok'=>$ok,
            ));
            $datos_empresa = $dti_ajax->getAjax();
            
            //Validar si esta instalado la firma electronica
            $entidad = new Entidades\Sis00060($this->adapter);
            $idModulo = $entidad->getMulti('nombre', 'FE');

            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));
            
            $feinstall = new Entidades\Sis20012($this->adapter);
            $dtfeinstall = $feinstall->getCountMulti('id','modulesid', $idModulo['id'], 'ruc', $empresa['ruc']);
            
            if ($dtfeinstall['numrows']>0)
            {
                //Tiene Facturacion Electronica Instalado
                $script = "<script type='text/javascript'>
                    document.getElementById('txtvalcontriespecial').disabled = true;
                    
                    if(document.getElementById('txtsmtpdefecto').checked)
                    {
                        document.getElementById('txtsmtp_hostname').disabled = true;
                        document.getElementById('txtsmtp_port').disabled = true;
                        document.getElementById('txtsmtp_username').disabled = true;
                        document.getElementById('txtsmtp_password').disabled = true;
                    }

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
                    
                    function setJsonEmpresa(accionSql=''){
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea actualizar datos de la empresa!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var ruc,razonsocial,nomempresa,direccion,telefono,rucrepresentante,representante,obligaconta,contriespecial,valcontriespecial
                                    ,languageid,template,zona_horaria,moneda,caracter_decimal,caracter_miles,segmentos_cuentas
                                    ,separador_segmentos,decimales_ventas,decimales_compras,smtp_hostname,smtp_port,smtp_username
                                    ,smtp_password,smtpdefecto,logo,ambiente,firma,clave,correo;
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
                                    smtp_hostname = document.getElementById('txtsmtp_hostname').value;
                                    smtp_port = document.getElementById('txtsmtp_port').value;
                                    smtp_username = document.getElementById('txtsmtp_username').value;
                                    smtp_password = document.getElementById('txtsmtp_password').value;
                                    smtpdefecto = document.getElementById('txtsmtpdefecto').checked;
                                    ambiente = document.getElementById('txtambiente').value;
                                    clave = document.getElementById('txtclavefirma').value;
                                    correo = document.getElementById('txtcorreo').value;

                                    if (ruc != '' && razonsocial != '' && telefono != '' && nomempresa != '' && direccion != '' 
                                        && rucrepresentante != '' && representante != ''
                                        && languageid > 0 && template > 0 && zona_horaria > 0 && moneda > 0 
                                        && caracter_decimal != '' && caracter_miles != '' && segmentos_cuentas != ''
                                        && separador_segmentos != '' && decimales_ventas != '' && decimales_compras != ''
                                        && ambiente > 0 && clave != '' && correo != '')
                                    {
                                        if (contriespecial) {
                                            if (valcontriespecial != '') {
                                                Swal.fire('Error!', 'El valor de contribuyente especial es obligatorio!', 'error');
                                            }
                                        }
                                        else if (smtpdefecto)
                                        {
                                            var imagen_data = new FormData();
                                            imagen_data.append('firma',$('#txtfirma')[0].files[0]);
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
                                                        var imagen_data = new FormData();
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
                                                                    ".$datos_empresa."
                                                                }
                                                            }
                                                          });
                                                    }
                                                    else
                                                    {
                                                        Swal.fire('Error!', ''+data.descripcion+'!', 'error');
                                                    }
                                                }
                                              });
                                        }
                                        else if(smtp_hostname != '' && smtp_port != '' && smtp_username != '' && smtp_password != '')
                                        {
                                            var imagen_data = new FormData();
                                            imagen_data.append('firma',$('#txtfirma')[0].files[0]);
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
                                                        var imagen_data = new FormData();
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
                                                                    ".$datos_empresa."
                                                                }
                                                            }
                                                          });
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
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                        }
                                    }
                                    else{
                                        Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }</script>";
            }
            else
            {
                //Tiene Facturacion Electronica Instalado
                $script = "<script type='text/javascript'>
                    document.getElementById('txtvalcontriespecial').disabled = true;
                    
                    if(document.getElementById('txtsmtpdefecto').checked)
                    {
                        document.getElementById('txtsmtp_hostname').disabled = true;
                        document.getElementById('txtsmtp_port').disabled = true;
                        document.getElementById('txtsmtp_username').disabled = true;
                        document.getElementById('txtsmtp_password').disabled = true;
                    }

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
                    
                    function setJsonEmpresa(accionSql=''){
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea actualizar datos de la empresa!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var ruc,razonsocial,nomempresa,direccion,telefono,rucrepresentante,representante,obligaconta,contriespecial,valcontriespecial
                                    ,languageid,template,zona_horaria,moneda,caracter_decimal,caracter_miles,segmentos_cuentas
                                    ,separador_segmentos,decimales_ventas,decimales_compras,smtp_hostname,smtp_port,smtp_username
                                    ,smtp_password,smtpdefecto,logo,ambiente,firma,clave,correo;
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
                                    smtp_hostname = document.getElementById('txtsmtp_hostname').value;
                                    smtp_port = document.getElementById('txtsmtp_port').value;
                                    smtp_username = document.getElementById('txtsmtp_username').value;
                                    smtp_password = document.getElementById('txtsmtp_password').value;
                                    smtpdefecto = document.getElementById('txtsmtpdefecto').checked;
                                    ambiente = document.getElementById('txtambiente').value;
                                    clave = document.getElementById('txtclavefirma').value;
                                    correo = document.getElementById('txtcorreo').value;

                                    if (ruc != '' && razonsocial != '' && telefono != '' && nomempresa != '' && direccion != '' 
                                        && rucrepresentante != '' && representante != ''
                                        && languageid > 0 && template > 0 && zona_horaria > 0 && moneda > 0 
                                        && caracter_decimal != '' && caracter_miles != '' && segmentos_cuentas != ''
                                        && separador_segmentos != '' && decimales_ventas != '' && decimales_compras != ''
                                        && ambiente > 0 && correo != '')
                                    {
                                        if (contriespecial) {
                                            if (valcontriespecial != '') {
                                                Swal.fire('Error!', 'El valor de contribuyente especial es obligatorio!', 'error');
                                            }
                                        }
                                        else if (smtpdefecto)
                                        {
                                            var imagen_data = new FormData();
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
                                                        ".$datos_empresa."
                                                    }
                                                }
                                              });
                                        }
                                        else if(smtp_hostname != '' && smtp_port != '' && smtp_username != '' && smtp_password != '')
                                        {
                                            var imagen_data = new FormData();
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
                                                        ".$datos_empresa."
                                                    }
                                                }
                                              });
                                        }
                                        else
                                        {
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                        }
                                    }
                                    else{
                                        Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }
                                })
                            },
                            allowOutsideClick: false
                        });
                    }</script>";
            }

            die(json_encode(array(
                'status' => 'OK',
                'layout' => $btngrp['layout'].$contpaneldetalle,
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
    }
    
    public function addfondos()
    {
        //Btn Agrupados
        $btngroup = new dti_builder_buttons();
        
        $btngroup->setGroupButtons(array(
                'clic'=>'seguridad/installplanes',
                'enlace'=>true,
                'icono'=>'fa fa-reply',
                'titulo'=>'Regresar',
                'btntitulo'=>'',
                'btnmensaje'=>'',
                'btn'=>array(),
            ));
        
        $content = '<!-- Card -->
                    Para Añadir fondos comunicate al (Claro) 0995466833.
                    <!-- Card -->';
        
        //Agrupamos los botones
        $btngrp = $btngroup->getGroupButtons();
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout'=>$btngrp['layout'],
                'manual_section'=>$content,
            )
        ),$this->login_empresa);

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Bienvenido",
            'script'=>$btngrp['script'],
            'modal'=>$btngrp['modal'],
        ));
    }
    
    public function listFondos()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuario'))) $this->redirect("seguridad","login");
        
        $datos = new Models\Sis20014Model($this->adapter);
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
            $empresa = new Entidades\Sis00100($this->adapter);
            $empresa = $empresa->getMulti('id', $this->session->get('empresa'));
            
            $datos = $datos->getTablePaginacion($cliente['id'],$empresa['ruc'],$q,$offset,$per_page);
            
            if(globalFunctions::es_bidimensional($datos))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_fondos');
                $tabla->setTitulo('Lista de Fondos');
                $tabla->setColumnas('pago,descripcion,valor,fecha');
                $tabla->setEtiquetas('Pago,Descripcion,Valor,Fecha');
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
                $tabla->setIdtable('tb_fondos');
                $tabla->setTitulo('Lista de Fondos');
                $tabla->setColumnas('pago,descripcion,valor,fecha');
                $tabla->setEtiquetas('Pago,Descripcion,Valor,Fecha');
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
                $tabla->setIdtable('tb_fondos');
                $tabla->setTitulo('Lista de Fondos');
                $tabla->setColumnas('pago,descripcion,valor,fecha');
                $tabla->setEtiquetas('Pago,Descripcion,Valor,Fecha');
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
                $tabla->setIdtable('tb_fondos');
                $tabla->setTitulo('Lista de Fondos');
                $tabla->setColumnas('pago,descripcion,valor,fecha');
                $tabla->setEtiquetas('Pago,Descripcion,Valor,Fecha');
                //$tabla->setDatos(null);
                //$tabla->setEditar('cc/newfactura',true,'mdleditFactura');
                //$tabla->setNuevo('cc/newfactura');
                $tabla->setFiltro(true,'goTablePaginacion','seguridad','listFondos');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'seguridad/index',
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
                ),$this->login_empresa);

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Planes Adquiridos",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function listCostoDescarga()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        $datos = new \Models\Com40000Model($this->adapter);
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
            $datos = $datos->getTablePaginacion($q,$offset,$per_page);
            
            if(globalFunctions::es_bidimensional($datos))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_ordenes');
                $tabla->setTitulo('Lista de Facturas de Compra');
                $tabla->setColumnas('-id,descripcion');
                $tabla->setEtiquetas('-ID,Documento');
                $tabla->setDatos($datos);
                $tabla->setEditar('seguridad/newCostoDescarga',true,'mdlnewCostoDescarga');
                $tabla->setNuevo('seguridad/newCostoDescarga',true,'mdleditCostoDescarga');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"]))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_ordenes');
                $tabla->setTitulo('Lista de Facturas de Compra');
                $tabla->setColumnas('-id,descripcion');
                $tabla->setEtiquetas('-ID,Documento');
                $tabla->setDatos($datos);
                $tabla->setEditar('seguridad/newCostoDescarga',true,'mdlnewCostoDescarga');
                $tabla->setNuevo('seguridad/newCostoDescarga',true,'mdleditCostoDescarga');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else
            {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_ordenes');
                $tabla->setTitulo('Lista de Facturas de Compra');
                $tabla->setColumnas('-id,descripcion');
                $tabla->setEtiquetas('-ID,Documento');
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
                    $this->redirect('seguridad','listCostoDescarga');
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
                $tabla->setIdtable('tb_ordenes');
                $tabla->setTitulo('Lista de Costos de Descarga');
                $tabla->setColumnas('-id,descripcion');
                $tabla->setEtiquetas('-ID,Documento');
                $tabla->setEditar('seguridad/newCostoDescarga',true,'mdlnewCostoDescarga');
                $tabla->setNuevo('seguridad/newCostoDescarga',true,'mdleditCostoDescarga');
                $tabla->setFiltro(true,'goTablePaginacion','seguridad','listCostoDescarga');
                
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
                
                $btngroup->setGroupButtons(array(
                        'clic'=>'seguridad/listCostoDescarga',
                        'enlace'=>true,
                        'icono'=>'fa fa-refresh',
                        'titulo'=>'Actualizar',
                        'btntitulo'=>'',
                        'btnmensaje'=>'',
                        'btn'=>array(),
                    ));
                
                \dti_core::set('script', '<script>
                            function goAgregarCuenta(cuenta){
                                if (document.getElementById("txtfin00000id"))
                                {
                                    document.getElementById("txtfin00000id").value = cuenta;
                                    $("#modeltxtfin00000id").modal("toggle");
                                }
                                if (document.getElementById("txtfin00000idEdit"))
                                {
                                    document.getElementById("txtfin00000idEdit").value = cuenta;
                                    $("#modeltxtfin00000idEdit").modal("toggle");
                                }
                            }</script>');
                
                $contenedor = globalFunctions::renderizar($this->website,array(
                    'section'=>array(
                        'manual'=>$btngroup->getGroupButtons()['layout'],
                        'layout_section'=>$tabla->gettable('paginacion'),
                    )
                ),$this->login_empresa);

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Costos de Descarga",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function newCostoDescarga($param=array())
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("default","login"); }
        if (!isset($_SESSION["empresa"])) { $this->redirect("default","selectempresa"); }
        
        if (isset($param['panel']))
        {
            //Btn Agrupados
            $btngroup = new dti_builder_buttons();
            
            if (!isset($param['url']))
            {
                $btngroup->setGroupButtons(array(
                    'clic'=>'seguridad/listCostoDescarga',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));
            }
            
            if (isset($param['edit']))
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonCostoDescarga("Update");',
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonCostoDescarga("Insert");',
                ));
            }
            
            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();
            
            if (isset($param['url']))
            {
                $ok = strlen($param['url'])>0?'location.href="'.$param['url'].'"':'location.href="seguridad/listCostoDescarga"';
            }
            else
            {
                $ok = 'location.href="seguridad/listCostoDescarga"';
            }
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'seguridad/jsonCostoDescarga',
                'data'=>"{'id' : id,'descripcion' : descripcion,'fin00000id' : fin00000id,'activo' : activo,'accionSql': accionSql}",
                'ok'=>$ok,
            ));
            $datos_costodescarga = $dti_ajax->getAjax();
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit']))
            {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSEGCostoDescargaEdit'),'orden',$param);
                
                $script = "<script type='text/javascript'>
                    
                    function setJsonCostoDescarga(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Editar?',
                            text: 'Esta seguro que desea editar el Costo de Descarga!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Editar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var id,descripcion,fin00000id,activo;
                                    id = document.getElementById('txtidCostoEdit').value;
                                    descripcion = document.getElementById('txtdescripcionEdit').value;
                                    fin00000id = document.getElementById('txtfin00000idEdit').value;
                                    activo = document.getElementById('txtactivoEdit').checked;
                                      
                                    if (descripcion != '' && fin00000id != '') {
                                            ".$datos_costodescarga."
                                        }else{
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }//Else
                                })
                            },
                            allowOutsideClick: false
                        });
                    }</script>";
                
            }
            else
            {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSEGCostoDescarga'),'orden');
                
                $script = "<script type='text/javascript'>
                    
                    function setJsonCostoDescarga(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar el Costo de Descarga!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var id,descripcion,fin00000id,activo;
                                    id = document.getElementById('txtidCosto').value;
                                    descripcion = document.getElementById('txtdescripcion').value;
                                    fin00000id = document.getElementById('txtfin00000id').value;
                                    activo = 0;
                                      
                                    if (descripcion != '' && fin00000id != '') {
                                            ".$datos_costodescarga."
                                        }else{
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }//Else
                                })
                            },
                            allowOutsideClick: false
                        });
                    }</script>";
            }
            $formulario =$formClientes->getForm();
            
            die(json_encode(array(
                'status' => 'OK',
                'layout' => $btngrp['layout'].$formulario,
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
    }
    
    public function listOptArreglos()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");

        $datos = new \Models\Opt40030Model($this->adapter);
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
            $datos = $datos->getTablePaginacion($q,$offset,$per_page);
            
            if(globalFunctions::es_bidimensional($datos))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_ordenes');
                $tabla->setTitulo('Lista de Motivos de Arreglo');
                $tabla->setColumnas('-id,motivo');
                $tabla->setEtiquetas('-ID,Motivo');
                $tabla->setDatos($datos);
                $tabla->setEditar('seguridad/newOptArreglos',true,'mdlnewOptArreglos');
                $tabla->setNuevo('seguridad/newOptArreglos',true,'mdleditOptArreglos');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"]))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_ordenes');
                $tabla->setTitulo('Lista de Motivos de Arreglo');
                $tabla->setColumnas('-id,motivo');
                $tabla->setEtiquetas('-ID,Motivo');
                $tabla->setDatos($datos);
                $tabla->setEditar('seguridad/newOptArreglos',true,'mdlnewOptArreglos');
                $tabla->setNuevo('seguridad/newOptArreglos',true,'mdleditOptArreglos');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else
            {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_ordenes');
                $tabla->setTitulo('Lista de Motivos de Arreglo');
                $tabla->setColumnas('-id,motivo');
                $tabla->setEtiquetas('-ID,Motivo');
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
                    $this->redirect('seguridad','listCostoDescarga');
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
                $tabla->setIdtable('tb_ordenes');
                $tabla->setTitulo('Lista de Motivos de Arreglo');
                $tabla->setColumnas('-id,motivo');
                $tabla->setEtiquetas('-ID,Motivo');
                $tabla->setEditar('seguridad/newOptArreglos',true,'mdlnewOptArreglos');
                $tabla->setNuevo('seguridad/newOptArreglos',true,'mdleditOptArreglos');
                $tabla->setFiltro(true,'goTablePaginacion','seguridad','listOptArreglos');
                
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
                
                $btngroup->setGroupButtons(array(
                        'clic'=>'seguridad/listOptArreglos',
                        'enlace'=>true,
                        'icono'=>'fa fa-refresh',
                        'titulo'=>'Actualizar',
                        'btntitulo'=>'',
                        'btnmensaje'=>'',
                        'btn'=>array(),
                    ));
                
                $contenedor = globalFunctions::renderizar($this->website,array(
                    'section'=>array(
                        'manual'=>$btngroup->getGroupButtons()['layout'],
                        'layout_section'=>$tabla->gettable('paginacion'),
                    )
                ),$this->login_empresa);

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Arreglos",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function newOptArreglos($param=array())
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("default","login"); }
        if (!isset($_SESSION["empresa"])) { $this->redirect("default","selectempresa"); }
        
        if (isset($param['panel']))
        {
            //Btn Agrupados
            $btngroup = new dti_builder_buttons();
            
            if (!isset($param['url']))
            {
                $btngroup->setGroupButtons(array(
                    'clic'=>'seguridad/listOptArreglos',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));
            }
            
            if (isset($param['edit']))
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonOptArreglos("Update");',
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonOptArreglos("Insert");',
                ));
            }
            
            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();
            
            if (isset($param['url']))
            {
                $ok = strlen($param['url'])>0?'location.href="'.$param['url'].'"':'location.href="seguridad/listOptArreglos"';
            }
            else
            {
                $ok = 'location.href="seguridad/listOptArreglos"';
            }
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'seguridad/jsonOptArreglos',
                'data'=>"{'id' : id,'motivo' : motivo,'activo' : activo,'accionSql': accionSql}",
                'ok'=>$ok,
            ));
            $datos_costodescarga = $dti_ajax->getAjax();
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit']))
            {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSEGOptArreglosEdit'),'orden',$param);
                
                $script = "<script type='text/javascript'>
                    
                    function setJsonOptArreglos(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Editar?',
                            text: 'Esta seguro que desea editar el Motivo de Arreglo!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Editar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var id,motivo,activo;
                                    id = document.getElementById('txtidArregloEdit').value;
                                    motivo = document.getElementById('txtmotivoEdit').value;
                                    activo = document.getElementById('txtactivoEdit').checked;
                                      
                                    if (motivo != '') {
                                            ".$datos_costodescarga."
                                        }else{
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }//Else
                                })
                            },
                            allowOutsideClick: false
                        });
                    }</script>";
                
            }
            else
            {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSEGOptArreglos'),'orden');
                
                $script = "<script type='text/javascript'>
                    
                    function setJsonOptArreglos(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar el Motivo de Arreglo!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var id,motivo,activo;
                                    id = document.getElementById('txtidArreglo').value;
                                    motivo = document.getElementById('txtmotivo').value;
                                    activo = 0;
                                      
                                    if (motivo != '') {
                                            ".$datos_costodescarga."
                                        }else{
                                            Swal.fire('Error!', 'Todos los campos son obligatorios!', 'error');
                                    }//Else
                                })
                            },
                            allowOutsideClick: false
                        });
                    }</script>";
            }
            $formulario =$formClientes->getForm();
            
            die(json_encode(array(
                'status' => 'OK',
                'layout' => $btngrp['layout'].$formulario,
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
    }
    
    public function adminseguridad()
    {
        \dti_core::set("script", "<script src='public/js/modulos/sistemas/goSeg_permisos.js' type='text/javascript'></script>");
        
        $btngroup = new dti_builder_buttons();

        $btngroup->setGroupButtons(array(
                'clic'=>'seguridad/index',
                'enlace'=>true,
                'icono'=>'fa fa-reply',
                'titulo'=>'Regresar',
                'btntitulo'=>'',
                'btnmensaje'=>'',
                'btn'=>array(),
            ));

        //Sacar el anio
        $form = new dti_builder_form($this->adapter);
        $selectPeriodo = $form->createSelect(array(
            'titulo'=>'Seleccione Usuario:',
            'nameid'=>'txtusuario',
            'combobox'=>'sis00300',
            'controller'=>'dtiware'
        ));
        
        \dti_core::set('script', '<script>
                                $("#txtusuario").change(function(){
                                    $.ajax({
                                        //Escogemos la URL donde vamos a buscar.
                                        url:"seguridad/buscarRoles/",
                                        //Envialos los parametros.
                                        data: {"search":$(this).val()},
                                        //Escogemos el metodo de envio en esta caso POST.
                                        type: "post",
                                        //Mostramos una imagen y la palabra cargando mientras espera.
                                        beforeSend: function(objeto){
                                            $("#loaderDetalle").html("<img src=\'public/images/ajax-loader.gif\'> Cargando...");
                                        },
                                        //Una vez que termino mostramos los datos y limpiamos el cargando.
                                        success:function(data){
                                            $(".outer_divDetalle").html(data).fadeIn("slow");
                                            $("#loaderDetalle").html("");
                                        }
                                    });
                                });
                                </script>');
        
        $modal_build = new dti_builder_modal();
        $modal_build->setModal(array(
            'id'=>'Tareas',
            'tipo'=>'form',
            'modal'=>'newTarea',
            'titulo'=>'Administrar Tareas',
            'mensaje'=>"<div id='loaderTareas' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                        <div class='outer_divTareas'></div>",
            'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
        ));
        $modal = $modal_build->getModal();

        dti_core::set("modal", $modal);
        
        $modal_build = new dti_builder_modal();
        $modal_build->setModal(array(
            'id'=>'Ventanas',
            'tipo'=>'form',
            'modal'=>'newVentana',
            'titulo'=>'Administrar Ventanas',
            'mensaje'=>"<div id='loaderVentana' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                        <div class='outer_divVentana'></div>",
            'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
        ));
        $modal = $modal_build->getModal();

        dti_core::set("modal", $modal);
        
        $modal_build = new dti_builder_modal();
        $modal_build->setModal(array(
            'id'=>'Funcion',
            'tipo'=>'edit',
            'modal'=>'newFuncion',
            'titulo'=>'Administrar Acciones',
            'mensaje'=>"<div id='loaderFuncion' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                        <div class='outer_divFuncion'></div>",
            'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
        ));
        $modal = $modal_build->getModal();

        dti_core::set("modal", $modal);
        
        $detalle = "<div id='loaderDetalle' style='position: absolute;text-align: center; top: 55px; width: 100%;display:none;'></div>
                   <div class='outer_divDetalle' ></div>";
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'manual'=>$btngroup->getGroupButtons()['layout'],
                'layout_section'=>$selectPeriodo,
                'manual_titulo'=>array(
                    'titulo'=>'Roles',
                    'btn'=>'Nuevo',
                    'url'=>'seguridad/newRol',
                    'layout'=>$detalle
                )
            )
        ),$this->login_empresa);

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Administrar Seguridades",
            'script'=>$btngroup->getGroupButtons()['script'],
            'modal'=>$btngroup->getGroupButtons()['modal'],
        ));
        //echo 'Tabla Con los Roles'; //--> Clic abrir Tareas Modal --> Abrir Ventanas Modal --> Abrir Accion Modal
    }
    
    public function adminCC()
    {
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        
        $btngroup = new dti_builder_buttons();

        $btngroup->setGroupButtons(array(
                'clic'=>'seguridad/index',
                'enlace'=>true,
                'icono'=>'fa fa-reply',
                'titulo'=>'Regresar',
                'btntitulo'=>'',
                'btnmensaje'=>'',
                'btn'=>array(),
            ));
        
        $config = new \Entidades\Cc40500($this->adapter);
        $dtconfig = $config->getAllActivo('asc');
        
        $table = '<form id="dti_validate" name="dti_validate"><table class="table table bordered"  style="width: 100%;"><tbody>';
        $table .= '<tr>
                        <th>Descripcion</th>
                        <th>Accion</th>
                    </tr>';
        
        if (globalFunctions::es_bidimensional($dtconfig))
        {
            foreach ($dtconfig as $value) {
                switch ($value['tipo']) {
                    case 'checkbox':
                        $chek = $value['valor']=='0'?'':'checked';
                        $table .= '<tr>
                                        <td>'.$value['observacion'].'</td>
                                        <td><input edit-id="'.$value['id'].'" '.$chek.' type="checkbox" id="txt'.$value['id'].'" name="txt'.$value['id'].'"  class="form-control"/></td>
                                    </tr>';

                        \dti_core::set('script', '<script>$(document).ready(function(){
                                                    $("#txt'.$value['id'].'").change(function() {
                                                        var id = $(this).attr("edit-id"); 
                                                        if(this.checked)
                                                        {
                                                            $.ajax({
                                                                url:"seguridad/jsonGeneralCC",
                                                                type: "post",
                                                                data:{"id":id,"valor":"1"},
                                                            });
                                                        }
                                                        else
                                                        {
                                                            $.ajax({
                                                                url:"seguridad/jsonGeneralCC",
                                                                type: "post",
                                                                data:{"id":id,"valor":"0"},
                                                            });
                                                        }
                                                    });
                                                });</script>');
                        break;
                    case 'textbox':
                        $table .= '<tr>
                                        <td>'.$value['observacion'].'</td>
                                        <td><input edit-id="'.$value['id'].'" type="textbox" id="txt'.$value['id'].'" name="txt'.$value['id'].'" value="'.$value['valor'].'"  class="form-control"/></td>
                                    </tr>';
                        \dti_core::set('script', '<script>$(document).ready(function(){
                                                    $("#txt'.$value['id'].'").change(function() {
                                                        var id = $(this).attr("edit-id"); 
                                                        var valor = document.getElementById("txt'.$value['id'].'").value; 
                                                        $.ajax({
                                                            url:"seguridad/jsonGeneralCC",
                                                            type: "post",
                                                            data:{"id":id,"valor":valor},
                                                        });
                                                    });
                                                    '.$value['mascara'].'
                                                });</script>');
                        break;
                }
            }
        }
        else if (isset($dtconfig['id']))
        {
            switch ($dtconfig['tipo']) {
                case 'checkbox':
                    $chek = $dtconfig['valor']=='0'?'':'checked';
                    $table .= '<tr>
                                    <td>'.$dtconfig['observacion'].'</td>
                                    <td><input edit-id="'.$dtconfig['id'].'" '.$chek.' type="checkbox" id="txt'.$dtconfig['id'].'" name="txt'.$dtconfig['id'].'"  class="form-control"/></td>
                                </tr>';

                    \dti_core::set('script', '<script>$(document).ready(function(){
                                                    $("#txt'.$dtconfig['id'].'").change(function() {
                                                        var id = $(this).attr("edit-id"); 
                                                        if(this.checked)
                                                        {
                                                            $.ajax({
                                                                url:"seguridad/jsonGeneralEmpresa",
                                                                type: "post",
                                                                data:{"id":id,"valor":"1"},
                                                            });
                                                        }
                                                        else
                                                        {
                                                            $.ajax({
                                                                url:"seguridad/jsonGeneralEmpresa",
                                                                type: "post",
                                                                data:{"id":id,"valor":"0"},
                                                            });
                                                        }
                                                    });
                                                });</script>');
                    break;
                case 'textbox':
                    $table .= '<tr>
                                    <td>'.$dtconfig['observacion'].'</td>
                                    <td><input edit-id="'.$dtconfig['id'].'" type="textbox" id="txt'.$dtconfig['id'].'" name="txt'.$dtconfig['id'].'" value="'.$dtconfig['valor'].'"  class="form-control"/></td>
                                </tr>';
                    \dti_core::set('script', '<script>$(document).ready(function(){
                                                $("#txt'.$dtconfig['id'].'").change(function() {
                                                    var id = $(this).attr("edit-id"); 
                                                    var valor = document.getElementById("txt'.$dtconfig['id'].'").value; 
                                                    $.ajax({
                                                        url:"seguridad/jsonGeneralEmpresa",
                                                        type: "post",
                                                        data:{"id":id,"valor":valor},
                                                    });
                                                });
                                                '.$dtconfig['mascara'].'
                                            });</script>');
                    break;
            }
        }
        $table .= '</tbody></table></form>';
        
        
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'manual'=>$btngroup->getGroupButtons()['layout'],
                'manual_titulo'=>array(
                    'titulo'=>'Configuración General CXC',
                    'layout'=>$table,
                )
            )
        ),$this->login_empresa);

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Configuracion General CXC",
            'script'=>$btngroup->getGroupButtons()['script'],
            'modal'=>$btngroup->getGroupButtons()['modal'],
        ));
    }
    
    public function adminGeneral()
    {
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
        
        $btngroup = new dti_builder_buttons();

        $btngroup->setGroupButtons(array(
                'clic'=>'seguridad/index',
                'enlace'=>true,
                'icono'=>'fa fa-reply',
                'titulo'=>'Regresar',
                'btntitulo'=>'',
                'btnmensaje'=>'',
                'btn'=>array(),
            ));
        
        $config = new \Entidades\Sis40500($this->adapter);
        $dtconfig = $config->getAllActivo('asc');

        $table = '<form id="dti_validate" name="dti_validate"><table class="table table bordered"  style="width: 100%;"><tbody>';
        $table .= '<tr>
                        <th>Descripcion</th>
                        <th>Accion</th>
                    </tr>';
        
        if (globalFunctions::es_bidimensional($dtconfig))
        {
            foreach ($dtconfig as $value) {
                switch ($value['tipo']) {
                    case 'checkbox':
                        $chek = $value['valor']=='0'?'':'checked';
                        $table .= '<tr>
                                        <td>'.$value['observacion'].'</td>
                                        <td><input edit-id="'.$value['id'].'" '.$chek.' type="checkbox" id="txt'.$value['id'].'" name="txt'.$value['id'].'"  class="form-control"/></td>
                                    </tr>';

                        \dti_core::set('script', '<script>$(document).ready(function(){
                                                    $("#txt'.$value['id'].'").change(function() {
                                                        var id = $(this).attr("edit-id"); 
                                                        if(this.checked)
                                                        {
                                                            $.ajax({
                                                                url:"seguridad/jsonGeneralEmpresa",
                                                                type: "post",
                                                                data:{"id":id,"valor":"1"},
                                                            });
                                                        }
                                                        else
                                                        {
                                                            $.ajax({
                                                                url:"seguridad/jsonGeneralEmpresa",
                                                                type: "post",
                                                                data:{"id":id,"valor":"0"},
                                                            });
                                                        }
                                                    });
                                                });</script>');
                        break;
                    case 'textbox':
                        $table .= '<tr>
                                        <td>'.$value['observacion'].'</td>
                                        <td><input edit-id="'.$value['id'].'" type="textbox" id="txt'.$value['id'].'" name="txt'.$value['id'].'" value="'.$value['valor'].'"  class="form-control"/></td>
                                    </tr>';
                        \dti_core::set('script', '<script>$(document).ready(function(){
                                                    $("#txt'.$value['id'].'").change(function() {
                                                        var id = $(this).attr("edit-id"); 
                                                        var valor = document.getElementById("txt'.$value['id'].'").value; 
                                                        $.ajax({
                                                            url:"seguridad/jsonGeneralEmpresa",
                                                            type: "post",
                                                            data:{"id":id,"valor":valor},
                                                        });
                                                    });
                                                    '.$value['mascara'].'
                                                });</script>');
                        break;
                }
            }
        }
        else if (isset($dtconfig['id']))
        {
            switch ($dtconfig['tipo']) {
                case 'checkbox':
                    $chek = $dtconfig['valor']=='0'?'':'checked';
                    $table .= '<tr>
                                    <td>'.$dtconfig['observacion'].'</td>
                                    <td><input edit-id="'.$dtconfig['id'].'" '.$chek.' type="checkbox" id="txt'.$dtconfig['id'].'" name="txt'.$dtconfig['id'].'"  class="form-control"/></td>
                                </tr>';

                    \dti_core::set('script', '<script>$(document).ready(function(){
                                                    $("#txt'.$dtconfig['id'].'").change(function() {
                                                        var id = $(this).attr("edit-id"); 
                                                        if(this.checked)
                                                        {
                                                            $.ajax({
                                                                url:"seguridad/jsonGeneralEmpresa",
                                                                type: "post",
                                                                data:{"id":id,"valor":"1"},
                                                            });
                                                        }
                                                        else
                                                        {
                                                            $.ajax({
                                                                url:"seguridad/jsonGeneralEmpresa",
                                                                type: "post",
                                                                data:{"id":id,"valor":"0"},
                                                            });
                                                        }
                                                    });
                                                });</script>');
                    break;
                case 'textbox':
                    $table .= '<tr>
                                    <td>'.$dtconfig['observacion'].'</td>
                                    <td><input edit-id="'.$dtconfig['id'].'" type="textbox" id="txt'.$dtconfig['id'].'" name="txt'.$dtconfig['id'].'" value="'.$dtconfig['valor'].'"  class="form-control"/></td>
                                </tr>';
                    \dti_core::set('script', '<script>$(document).ready(function(){
                                                $("#txt'.$dtconfig['id'].'").change(function() {
                                                    var id = $(this).attr("edit-id"); 
                                                    var valor = document.getElementById("txt'.$dtconfig['id'].'").value; 
                                                    $.ajax({
                                                        url:"seguridad/jsonGeneralEmpresa",
                                                        type: "post",
                                                        data:{"id":id,"valor":valor},
                                                    });
                                                });
                                                '.$dtconfig['mascara'].'
                                            });</script>');
                    break;
            }
        }
        $table .= '</tbody></table></form>';
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'manual'=>$btngroup->getGroupButtons()['layout'],
                'manual_titulo'=>array(
                    'titulo'=>'Configuración General Empresa',
                    'layout'=>$table,
                )
            )
        ),$this->login_empresa);

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Configuracion General Empresa",
            'script'=>$btngroup->getGroupButtons()['script'],
            'modal'=>$btngroup->getGroupButtons()['modal'],
        ));
    }
    
    public function newRol($param=array())
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("seguridad","login"); }
        if (!isset($_SESSION["empresa"])) { $this->redirect("seguridad","selectempresa"); }
        
        if (isset($param['panel']))
        {
            //Btn Agrupados
            $btngroup = new dti_builder_buttons();
             
            if (isset($param['edit']))
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonRol("Update");',
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonRol("Insert");',
                ));
            }
            
            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSegRol'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSegRol'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            if (isset($param['url']))
            {
                $ok = strlen($param['url'])>0?'location.href="'.$param['url'].'"':'location.href="seguridad/adminseguridad"';
            }
            else
            {
                $ok = 'location.href="seguridad/adminseguridad"';
            }
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'seguridad/jsonrol',
                'data'=>"{'id' : id,'rol' : rol,'descripcion' : descripcion,'accionSql': accionSql}",
                'ok'=>$ok,
            ));
            $datos_rol = $dti_ajax->getAjax();

            $script = "<script type='text/javascript'>
                    
                    function setJsonRol(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar el rol!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var id,rol,descripcion;
                                    id = document.getElementById('txtidRol').value;
                                    rol = document.getElementById('txtrolRol').value;
                                    descripcion = document.getElementById('txtdescripcionRol').value;
                                    
                                    if (rol != '' && descripcion != '')
                                    {
                                        ".$datos_rol."
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
            
            die(json_encode(array(
                'status' => 'OK',
                'layout' => $btngrp['layout'].$formulario,
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
    }
    
    public function newTarea($param=array())
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("seguridad","login"); }
        if (!isset($_SESSION["empresa"])) { $this->redirect("seguridad","selectempresa"); }
        
        if (isset($param['panel']))
        {
            //Btn Agrupados
            $btngroup = new dti_builder_buttons();
             
            if (isset($param['edit']))
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonTarea("Update");',
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonTarea("Insert");',
                ));
            }
            
            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSegTarea'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSegTarea'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            if (isset($param['url']))
            {
                $ok = strlen($param['url'])>0?'location.href="'.$param['url'].'"':'location.href="seguridad/adminseguridad"';
            }
            else
            {
                $ok = 'location.href="seguridad/adminseguridad"';
            }
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'seguridad/jsonTarea',
                'data'=>"{'id' : id,'tarea' : tarea,'descripcion' : descripcion,'accionSql': accionSql}",
                'ok'=>$ok,
            ));
            $datos_tarea = $dti_ajax->getAjax();

            $script = "<script type='text/javascript'>
                    
                    function setJsonTarea(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar la Tarea!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var id,tarea,descripcion;
                                    id = document.getElementById('txtidTarea').value;
                                    tarea = document.getElementById('txttareaTarea').value;
                                    descripcion = document.getElementById('txtdescripcionTarea').value;
                                    
                                    if (tarea != '' && descripcion != '')
                                    {
                                        ".$datos_tarea."
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
            
            die(json_encode(array(
                'status' => 'OK',
                'layout' => $btngrp['layout'].$formulario,
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
    }
    
    public function newVentana($param=array())
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("seguridad","login"); }
        if (!isset($_SESSION["empresa"])) { $this->redirect("seguridad","selectempresa"); }
        
        if (isset($param['panel']))
        {
            //Btn Agrupados
            $btngroup = new dti_builder_buttons();
             
            if (isset($param['edit']))
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonVentana("Update");',
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonVentana("Insert");',
                ));
            }
            
            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSegVentana'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmSegVentana'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            if (isset($param['url']))
            {
                $ok = strlen($param['url'])>0?'location.href="'.$param['url'].'"':'location.href="seguridad/adminseguridad"';
            }
            else
            {
                $ok = 'location.href="seguridad/adminseguridad"';
            }
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'seguridad/jsonVentana',
                'data'=>"{'id' : id,'ventana' : ventana,'descripcion' : descripcion,'accionSql': accionSql}",
                'ok'=>$ok,
            ));
            $datos_tarea = $dti_ajax->getAjax();

            $script = "<script type='text/javascript'>
                    
                    function setJsonVentana(accionSql=''){
                        //Agregar Validaciones
                        Swal.fire({
                            title: 'Desea Guardar?',
                            text: 'Esta seguro que desea guardar la Ventana!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            showLoaderOnConfirm: true,
                            preConfirm: function() {
                                return new Promise(function(resolve) {
                                    var id,ventana,descripcion;
                                    id = document.getElementById('txtidVentana').value;
                                    ventana = document.getElementById('txtventanaVentana').value;
                                    descripcion = document.getElementById('txtdescripcionVentana').value;
                                    
                                    if (ventana != '' && descripcion != '')
                                    {
                                        ".$datos_tarea."
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
            
            die(json_encode(array(
                'status' => 'OK',
                'layout' => $btngrp['layout'].$formulario,
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
    }
    
    public function listUsuarios($param=array())
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuario'))) $this->redirect("seguridad","login");
        
        $datos = new Models\Sis00300Model($this->adapter);
        if (isset($param['page']))
        {
            //Limpiar la Variable
            if ($param['q'] != 'undefined') {
                $q = $param['q'];
            }else{
                $q = '';
            }
            $numrows = $datos->getCount();
            //Muchos Datos
            //Paginacion
            //las variables de paginación
            $page = (isset($param['page']) && !empty($param['page']))?$param['page']:1;
            $per_page = 15; //la cantidad de registros que desea mostrar
            $adjacents  = 4; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            //Consultar Inventario
            $datos = $datos->getTablePaginacion($q,$offset,$per_page);
            
            if(globalFunctions::es_bidimensional($datos))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Usuarios');
                $tabla->setColumnas('usuario,nombre,apellido,correo,activo');
                $tabla->setEtiquetas('Usuario,Nombre,Apellido,Correo,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('seguridad/newusuario',true,'mdlEditEmpresa');
                $tabla->setNuevo('seguridad/newusuario',true,'mdlNewEmpresa');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"]))
            {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Usuarios');
                $tabla->setColumnas('usuario,nombre,apellido,correo,activo');
                $tabla->setEtiquetas('Usuario,Nombre,Apellido,Correo,Activo');
                $tabla->setEditar('seguridad/newusuario',true,'mdlEditEmpresa');
                $tabla->setNuevo('seguridad/newusuario',true,'mdlNewEmpresa');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else
            {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Usuarios');
                $tabla->setColumnas('usuario,nombre,apellido,correo,activo');
                $tabla->setEtiquetas('Usuario,Nombre,Apellido,Correo,Activo');
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
                    $this->redirect('seguridad','listUsuarios');
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
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Usuarios');
                $tabla->setColumnas('usuario,nombre,apellido,correo,activo');
                $tabla->setEtiquetas('Usuario,Nombre,Apellido,Correo,Activo');
                $tabla->setEditar('seguridad/newusuario',true,'mdlEditEmpresa');
                $tabla->setNuevo('seguridad/newusuario',true,'mdlNewEmpresa');
                $tabla->setFiltro(true,'goTablePaginacion','seguridad','listUsuarios');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'seguridad/index',
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
                ),$this->login_empresa);

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Usuarios",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function newUsuario($param=array())
    {
        if (!isset($_SESSION["usuario"])) { $this->redirect("seguridad","login"); }
        if (!isset($_SESSION["empresa"])) { $this->redirect("seguridad","selectempresa"); }
        
        if (isset($param['panel']))
        {
            //Btn Agrupados
            $btngroup = new dti_builder_buttons();
             
            if (isset($param['edit']))
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonUsuario("Update");',
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonUsuario("Insert");',
                ));
            }
            
            if (isset($param['url']))
            {
                $ok = strlen($param['url'])>0?'location.href="'.$param['url'].'"':'location.href="seguridad/listUsuarios"';
            }
            else
            {
                $ok = 'location.href="seguridad/listUsuarios"';
            }
            
            //Agrupamos los botones
            $btngrp = $btngroup->getGroupButtons();
            
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (isset($param['edit']))
            {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmNewUsuarioEdit'),'orden',$param);

                $dti_ajax = new dti_builder_ajax();
                $dti_ajax->setAjax(array(
                    'url'=>'seguridad/jsonUsuario',
                    'data'=>"{'id' : id,'usuario' : usuario,'nombre' : nombre,'apellido' : apellido,'descuento' : descuento"
                . ",'correo' : correo,'pass' : pass,'editprecio' : editprecio,'activo' : activo,'accionSql': accionSql}",
                    'ok'=>$ok,
                ));
                $datos_tarea = $dti_ajax->getAjax();

                $script = "<script type='text/javascript'>
                        function setJsonUsuario(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Guardar?',
                                text: 'Esta seguro que desea guardar el Usuario!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                    return new Promise(function(resolve) {
                                        var id,usuario,nombre,apellido,correo,pass,passverificar,editprecio,activo,descuento;
                                        id = document.getElementById('txtidUsuEdit').value;
                                        usuario = document.getElementById('txtusuarioEdit').value;
                                        nombre = document.getElementById('txtnombreEdit').value;
                                        apellido = document.getElementById('txtapellidoEdit').value;
                                        correo = document.getElementById('txtcorreoEdit').value;
                                        pass = document.getElementById('txtpassEdit').value;
                                        passverificar = document.getElementById('txtpassConfirmacionEdit').value;
                                        activo = document.getElementById('txtactivoEdit').checked;
                                        descuento = document.getElementById('txtdescuentoEdit').value;
                                        editprecio = document.getElementById('txteditprecioEdit').checked;

                                        if (usuario != '' && nombre != '' && apellido != '' && correo != '' && pass != '' && passverificar != '')
                                        {
                                            if (pass == passverificar)
                                            {
                                                ".$datos_tarea."
                                            }
                                            else
                                            {
                                                Swal.fire('Error!', 'Las Contraseñas no son iguales!', 'error');
                                            }
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
            }
            else
            {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmNewUsuario'),'orden');
                
                $dti_ajax = new dti_builder_ajax();
                $dti_ajax->setAjax(array(
                    'url'=>'seguridad/jsonUsuario',
                    'data'=>"{'id' : id,'usuario' : usuario,'nombre' : nombre,'apellido' : apellido,'descuento' : descuento"
                . ",'correo' : correo,'pass' : pass,'editprecio' : editprecio,'activo' : activo,'accionSql': accionSql}",
                    'ok'=>$ok,
                ));
                $datos_tarea = $dti_ajax->getAjax();
                
                $script = "<script type='text/javascript'>
                        function setJsonUsuario(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Guardar?',
                                text: 'Esta seguro que desea guardar el Usuario!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                    return new Promise(function(resolve) {
                                        var id,usuario,nombre,apellido,correo,pass,passverificar,activo,descuento,editprecio;
                                        id = document.getElementById('txtidUsu').value;
                                        usuario = document.getElementById('txtusuario').value;
                                        nombre = document.getElementById('txtnombre').value;
                                        apellido = document.getElementById('txtapellido').value;
                                        correo = document.getElementById('txtcorreo').value;
                                        pass = document.getElementById('txtpass').value;
                                        passverificar = document.getElementById('txtpassConfirmacion').value;
                                        descuento = document.getElementById('txtdescuento').value;
                                        editprecio = document.getElementById('txteditprecio').checked;
                                        activo = document.getElementById('txtactivo').checked;
                                        
                                        if (usuario != '' && nombre != '' && apellido != '' && correo != '' && pass != '' && passverificar != '')
                                        {
                                            if (pass == passverificar)
                                            {
                                                ".$datos_tarea."
                                            }
                                            else
                                            {
                                                Swal.fire('Error!', 'Las Contraseñas no son iguales!', 'error');
                                            }
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
            }
            $formulario =$formClientes->getForm();
            
            die(json_encode(array(
                'status' => 'OK',
                'layout' => $btngrp['layout'].$formulario,
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
    }
    /**
     * MANEJO DE JSON
     */
    
    public function jsonRol($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new \Entidades\Sis00200($this->adapter);
            $entidad->setRol($param['rol']);
            $entidad->setDescripcion($param['descripcion']);
            $entidad->setActivo(1);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update') 
        {
            //Insertar en la web transaccional
            $entidad = new \Entidades\Sis00200($this->adapter);
            $entidad->updateMultiColum('rol', $param['rol'],'id', $param['id']);
            $entidad->updateMultiColum('descripcion', $param['descripcion'],'id', $param['id']);
            //$entidad->updateMultiColum('activo', $param['activo'],'id', $param['id']);
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new \Models\Sis00200Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\sis00200($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonAsignarRol($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis41000($this->adapter);
            $entidad->setSis00200id($param['rol']);
            $entidad->setSis00300id($param['cliente']);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            $clientes = new Entidades\Sis41000($this->adapter);
            $clientes->deleteMulti('sis00300id', $param['cliente'],'sis00200id',$param['rol']);

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonTarea($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new \Entidades\Sis00201($this->adapter);
            $entidad->setTarea($param['tarea']);
            $entidad->setDescripcion($param['descripcion']);
            $entidad->setActivo(1);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update') 
        {
            //Insertar en la web transaccional
            $entidad = new \Entidades\Sis00201($this->adapter);
            $entidad->updateMultiColum('tarea', $param['tarea'],'id', $param['id']);
            $entidad->updateMultiColum('descripcion', $param['descripcion'],'id', $param['id']);
            //$entidad->updateMultiColum('activo', $param['activo'],'id', $param['id']);
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00201Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new \Entidades\Sis00201($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonAsignarTarea($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis41001($this->adapter);
            $entidad->setSis00200id($param['rol']);
            $entidad->setSis00201id($param['tarea']);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            $clientes = new Entidades\Sis41001($this->adapter);
            $clientes->deleteMulti('sis00201id', $param['tarea'],'sis00200id',$param['rol']);

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonVentana($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00202($this->adapter);
            $entidad->setVentana($param['ventana']);
            $entidad->setDescripcion($param['descripcion']);
            $entidad->setActivo(1);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00202($this->adapter);
            $entidad->updateMultiColum('ventana', $param['ventana'],'id', $param['id']);
            $entidad->updateMultiColum('descripcion', $param['descripcion'],'id', $param['id']);
            //$entidad->updateMultiColum('activo', $param['activo'],'id', $param['id']);
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00202Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00202($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonGeneralCC($param=array())
    {
        $entidad = new \Entidades\Cc40500($this->adapter);
        $entidad->updateMultiColum('valor', $param['valor'],'id', $param['id']);
        die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
    }
    
    public function jsonGeneralEmpresa($param=array())
    {
        $entidad = new \Entidades\Sis40500($this->adapter);
        $entidad->updateMultiColum('valor', $param['valor'],'id', $param['id']);
        die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
    }
    
    public function jsonUsuario($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new \Entidades\Sis00300($this->adapter);
            $entidad->setUsuario($param['usuario']);
            $entidad->setNombre($param['nombre']);
            $entidad->setApellido($param['apellido']);
            $entidad->setCorreo($param['correo']);
            $entidad->setPass(sha1($param['pass']));
            $entidad->setBd($this->session->get('bdcliente'));
            $entidad->setDescuento($param['descuento']);
            $entidad->setEditprecio($param['editprecio']=='true'?1:0);
            $entidad->setActivo($param['activo']=='true'?1:0);
            
            $entidad->save();
            
            $usuario = $entidad->getMulti('usuario', $param['usuario'], 'correo', $param['correo']);
            
            $entidad = new \Entidades\Sis20200($this->adapter);
            $entidad->setSis00100id($this->session->get('empresa'));
            $entidad->setSis00300id($usuario['id']);
            
            //Guardamos Clientes
            $entidad->save();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            $entidad = new \Entidades\Sis00300($this->adapter);
            $entidad->updateMultiColum('usuario', $param['usuario'],'id', $param['id']);
            $entidad->updateMultiColum('nombre', $param['nombre'],'id', $param['id']);
            $entidad->updateMultiColum('apellido', $param['apellido'],'id', $param['id']);
            $entidad->updateMultiColum('correo', $param['correo'],'id', $param['id']);
            $entidad->updateMultiColum('pass',sha1($param['pass']),'id', $param['id']);
            $entidad->updateMultiColum('activo', $param['activo']=='true'?1:0,'id', $param['id']);
            $entidad->updateMultiColum('descuento',$param['descuento'],'id', $param['id']);
            $entidad->updateMultiColum('editprecio',$param['editprecio']=='true'?1:0,'id', $param['id']);
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00300Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new \Entidades\Sis00300($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonAsignarVentana($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis41002($this->adapter);
            $entidad->setSis00202id($param['ventana']);
            $entidad->setSis00201id($param['tarea']);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            $clientes = new Entidades\Sis41002($this->adapter);
            $clientes->deleteMulti('sis00201id', $param['tarea'],'sis00202id',$param['ventana']);

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonAsignarFuncion($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis41003($this->adapter);
            $entidad->setSis00202id($param['ventana']);
            $entidad->setSis00203id($param['funcion']);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            $clientes = new Entidades\Sis41003($this->adapter);
            $clientes->deleteMulti('sis00203id', $param['funcion'],'sis00202id',$param['ventana']);

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonNewCampo($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            $tipo = new \Entidades\Sis40131($this->adapter);
            $dttipo = $tipo->getById($param['tipo']);
            
            //Insertar en la web transaccional
            $entidad = new \Entidades\sis40130($this->adapter);
            $entidad->setNameid('campo');
            $entidad->setTipo($dttipo['tipo']);
            $entidad->setTitulo($param['titulo']);
            $entidad->setPlaceholder($param['placeholder']);
            $entidad->setIdform('frmDAinventario');
            $entidad->setCss('form-control');
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update')
        {
            //Insertar en la web transaccional
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if($param['accionSql'] == 'Delete')
        {
            $clientes = new \Entidades\Sis40130($this->adapter);
            $clientes->deleteMulti('titulo', $param['titulo'],'placeholder',$param['placeholder'],'idform','frmDAinventario');

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonCostoDescarga($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new \Entidades\Com40000($this->adapter);
            $entidad->setActivo($param['activo']);
            $entidad->setDescripcion($param['descripcion']);
            $entidad->setEmpresa($this->session->get('empresa'));
            if (strlen($param['fin00000id'])>0)
            {
                $cuenta = new Models\Fin00000Model($this->adapter);
                $dtcuenta = $cuenta->getIdCuenta($param['fin00000id']);
                $entidad->setFin00000id($dtcuenta['id']);
            }
            $entidad->setUsuario($this->session->get('usuario'));
            $entidad->save();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update') 
        {
            $entidad = new \Entidades\Com40000($this->adapter);
            if (strlen($param['fin00000id'])>3)
            {
                $cuenta = new Models\Fin00000Model($this->adapter);
                $dtcuenta = $cuenta->getIdCuenta($param['fin00000id']);
                $entidad->updateMultiColum('fin00000id', $dtcuenta['id'], 'id', $param['id']);
            }
            $entidad->updateMultiColum('descripcion', $param['descripcion'], 'id', $param['id']);
            $entidad->updateMultiColum('activo', $param['activo']==='true'?1:0, 'id', $param['id']);
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
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
    
    public function jsonOptArreglos($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Opt40030($this->adapter);
            $entidad->setActivo($param['activo']);
            $entidad->setMotivo($param['motivo']);
            $entidad->save();
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if($param['accionSql'] == 'Update') 
        {
            $entidad = new Entidades\Opt40030($this->adapter);
            $entidad->updateMultiColum('motivo', $param['motivo'], 'id', $param['id']);
            $entidad->updateMultiColum('activo', $param['activo']==='true'?1:0, 'id', $param['id']);
            
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
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
    
    /**
     * MANEJO DE FUNCIONES UNICAS
     */
    
    public function selectEstablecimiento($param=array())
    {
        if (isset($param['seleccionado']))
        {
            $this->session->add('establecimiento', $param['seleccionado']);
            echo $param['seleccionado'];
        }
    }
    
    /**
     * MANEJO DE BUSQUEDAS
     */
    
    public function buscarRoles()
    {
        //Limpiar la Variable
        $accion = '';
        $q = $_POST['search'];
        if (isset($_POST['accion'])) $accion = '/'.$_POST['accion'];
        //Inicializar Variables
        $codigos = new Models\Sis00200Model($this->adapter);
        $numrows = $codigos->getCountResul();
        if ($numrows["numrows"] > 0 && $q > 0)
        {
            //Consultar Inventario
            $datos = $codigos->getModal();
            if(globalFunctions::es_bidimensional($datos))
            {
                //Buscamos si tiene visteado o no
                $visteado = new Entidades\Sis41000($this->adapter);
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Rol</th>
                            <th>Descripcion</th>
                        </tr>
                        <?php
                        foreach($datos as $dato)
                        {
                            $dtvisteado = $visteado->getCountMulti('id', 'sis00300id', $q, 'sis00200id', $dato["id"]);
                            ?>
                            <tr>
                                <td><input type="checkbox" id="txtFin<?php echo $dato["id"]; ?>" name="txtFin<?php echo $dato["id"]; ?>"  <?php echo $dtvisteado['numrows']==='1'?'checked':'' ?> onClick="goAsignarRol(<?php echo $dato["id"]; ?>)" class="form-control" /></td>
                                <td><a class="dti-links Tareas" data-id="<?php echo $dato["id"]; ?>" data-toggle="modal" data-target="#Tareas"><?php echo $dato["rol"]; ?></a></td>
                                <td><?php echo $dato["descripcion"]; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            }
            else if (isset($datos["id"]))
            {
                //Buscamos si tiene visteado o no
                $visteado = new Entidades\Sis41000($this->adapter);
                $dtvisteado = $visteado->getCountMulti('id', 'sis00300id', $q, 'sis00200id', $datos["id"]);
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Rol</th>
                            <th>Descripcion</th>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="txtFin<?php echo $datos["id"]; ?>" name="txtFin<?php echo $datos["id"]; ?>"  <?php echo $dtvisteado["numrows"]==='1'?'checked':'' ?>  onClick="goAsignarRol(<?php echo $datos["id"]; ?>)" class="form-control" /></td>
                            <td><a class="dti-links Tareas" data-id="<?php echo $datos["id"]; ?>" data-toggle="modal" data-target="#Tareas"><?php echo $datos["rol"]; ?></a></td>
                            <td><?php echo $datos["descripcion"]; ?></td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Rol</th>
                            <th>Descripcion</th>
                        </tr>
                  </table>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Activo</th>
                        <th>Rol</th>
                        <th>Descripcion</th>
                    </tr>
              </table>
            </div>
            <?php
        }
    }
    
    public function buscarTareas()
    {
        //Limpiar la Variable
        $accion = '';
        $q = $_POST['search'];
        if (isset($_POST['accion'])) $accion = '/'.$_POST['accion'];
        //Inicializar Variables
        $codigos = new Models\Sis00201Model($this->adapter);
        $numrows = $codigos->getCountResul();
        if ($numrows["numrows"] > 0 && $q > 0)
        {
            //Consultar Inventario
            $datos = $codigos->getModal();
            if(globalFunctions::es_bidimensional($datos))
            {
                //Buscamos si tiene visteado o no
                $visteado = new Entidades\Sis41001($this->adapter);
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Tarea</th>
                            <th>Descripcion</th>
                        </tr>
                        <?php
                        foreach($datos as $dato)
                        {
                            $dtvisteado = $visteado->getCountMulti('id', 'sis00200id', $q, 'sis00201id', $dato["id"]);
                            ?>
                            <tr>
                                <td><input type="checkbox" id="txtTarea<?php echo $dato["id"]; ?>" name="txtTarea<?php echo $dato["id"]; ?>"  <?php echo $dtvisteado['numrows']==='1'?'checked':'' ?> onClick="goAsignarTarea(<?php echo $q ?>,<?php echo $dato["id"]; ?>)" class="form-control" /></td>
                                <td><a class="dti-links Ventanas" data-id="<?php echo $dato["id"]; ?>" data-toggle="modal" data-target="#Ventanas"><?php echo $dato["tarea"]; ?></a></td>
                                <td><?php echo $dato["descripcion"]; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            }
            else if (isset($datos["id"]))
            {
                //Buscamos si tiene visteado o no
                $visteado = new Entidades\Sis41001($this->adapter);
                $dtvisteado = $visteado->getCountMulti('id', 'sis00200id', $q, 'sis00201id', $datos["id"]);
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Tarea</th>
                            <th>Descripcion</th>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="txtTarea<?php echo $datos["id"]; ?>" name="txtTarea<?php echo $datos["id"]; ?>"  <?php echo $dtvisteado["numrows"]==='1'?'checked':'' ?>  onClick="goAsignarTarea(<?php echo $q ?>,<?php echo $datos["id"]; ?>)" class="form-control" /></td>
                            <td><a class="dti-links Ventanas" data-id="<?php echo $datos["id"]; ?>" data-toggle="modal" data-target="#Ventanas"><?php echo $datos["tarea"]; ?></a></td>
                            <td><?php echo $datos["descripcion"]; ?></td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Tarea</th>
                            <th>Descripcion</th>
                        </tr>
                  </table>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Activo</th>
                        <th>Tarea</th>
                        <th>Descripcion</th>
                    </tr>
              </table>
            </div>
            <?php
        }
    }
    
    public function buscarVentanas()
    {
        //Limpiar la Variable
        $accion = '';
        $q = $_POST['search'];
        if (isset($_POST['accion'])) $accion = '/'.$_POST['accion'];
        //Inicializar Variables
        $codigos = new Models\Sis00202Model($this->adapter);
        $numrows = $codigos->getCountResul();
        if ($numrows["numrows"] > 0 && $q > 0)
        {
            //Consultar Inventario
            $datos = $codigos->getModal();
            if(globalFunctions::es_bidimensional($datos))
            {
                //Buscamos si tiene visteado o no
                $visteado = new Entidades\Sis41002($this->adapter);
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Ventana</th>
                            <th>Descripcion</th>
                        </tr>
                        <?php
                        foreach($datos as $dato)
                        {
                            $dtvisteado = $visteado->getCountMulti('id', 'sis00201id', $q, 'sis00202id', $dato["id"]);
                            ?>
                            <tr>
                                <td><input type="checkbox" id="txtVentana<?php echo $dato["id"]; ?>" name="txtVentana<?php echo $dato["id"]; ?>"  <?php echo $dtvisteado['numrows']==='1'?'checked':'' ?> onClick="goAsignarVentana(<?php echo $q ?>,<?php echo $dato["id"]; ?>)" class="form-control" /></td>
                                <td><a class="dti-links Funcion" data-id="<?php echo $dato["id"]; ?>" data-toggle="modal" data-target="#Funcion"><?php echo $dato["ventana"]; ?></a></td>
                                <td><?php echo $dato["descripcion"]; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            }
            else if (isset($datos["id"]))
            {
                //Buscamos si tiene visteado o no
                $visteado = new Entidades\Sis41002($this->adapter);
                $dtvisteado = $visteado->getCountMulti('id', 'sis00201id', $q, 'sis00202id', $datos["id"]);
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Ventana</th>
                            <th>Descripcion</th>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="txtVentana<?php echo $datos["id"]; ?>" name="txtVentana<?php echo $datos["id"]; ?>"  <?php echo $dtvisteado["numrows"]==='1'?'checked':'' ?>  onClick="goAsignarVentana(<?php echo $q ?>,<?php echo $datos["id"]; ?>)" class="form-control" /></td>
                            <td><a class="dti-links Funcion" data-id="<?php echo $datos["id"]; ?>" data-toggle="modal" data-target="#Funcion"><?php echo $datos["ventana"]; ?></a></td>
                            <td><?php echo $datos["descripcion"]; ?></td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Ventana</th>
                            <th>Descripcion</th>
                        </tr>
                  </table>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Activo</th>
                        <th>Ventana</th>
                        <th>Descripcion</th>
                    </tr>
              </table>
            </div>
            <?php
        }
    }
    
    public function buscarFuncion()
    {
        //Limpiar la Variable
        $accion = '';
        $q = $_POST['search'];
        if (isset($_POST['accion'])) $accion = '/'.$_POST['accion'];
        //Inicializar Variables
        $codigos = new Models\Sis00203Model($this->adapter);
        $numrows = $codigos->getCountResul();
        if ($numrows["numrows"] > 0 && $q > 0)
        {
            //Consultar Inventario
            $datos = $codigos->getModal();
            if(globalFunctions::es_bidimensional($datos))
            {
                //Buscamos si tiene visteado o no
                $visteado = new Entidades\Sis41003($this->adapter);
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Accion</th>
                            <th>Descripcion</th>
                        </tr>
                        <?php
                        foreach($datos as $dato)
                        {
                            $dtvisteado = $visteado->getCountMulti('id', 'sis00202id', $q, 'sis00203id', $dato["id"]);
                            ?>
                            <tr>
                                <td><input type="checkbox" id="txtFuncion<?php echo $dato["id"]; ?>" name="txtFuncion<?php echo $dato["id"]; ?>"  <?php echo $dtvisteado['numrows']==='1'?'checked':'' ?> onClick="goAsignarFuncion(<?php echo $q ?>,<?php echo $dato["id"]; ?>)" class="form-control" /></td>
                                <td><?php echo $dato["accion"]; ?></td>
                                <td><?php echo $dato["descripcion"]; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            }
            else if (isset($datos["id"]))
            {
                //Buscamos si tiene visteado o no
                $visteado = new Entidades\Sis41003($this->adapter);
                $dtvisteado = $visteado->getCountMulti('id', 'sis00202id', $q, 'sis00203id', $datos["id"]);
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Accion</th>
                            <th>Descripcion</th>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="txtFuncion<?php echo $datos["id"]; ?>" name="txtFuncion<?php echo $datos["id"]; ?>"  <?php echo $dtvisteado["numrows"]==='1'?'checked':'' ?>  onClick="goAsignarFuncion(<?php echo $q ?>,<?php echo $datos["id"]; ?>)" class="form-control" /></td>
                            <td><?php echo $datos["accion"]; ?></td>
                            <td><?php echo $datos["descripcion"]; ?></td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="table-responsive">
                  <table class="table">
                        <tr  class="warning">
                            <th>Activo</th>
                            <th>Accion</th>
                            <th>Descripcion</th>
                        </tr>
                  </table>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Activo</th>
                        <th>Accion</th>
                        <th>Descripcion</th>
                    </tr>
              </table>
            </div>
            <?php
        }
    }
    
    public function buscarDAinventario()
    {
        $datos = new \Models\Sis40130Model($this->adapter);
        //Limpiar la Variable
        if ($_POST['q'] != 'undefined') {
            $q = $_POST['q'];
        }else{
            $q = '';
        }
        $numrows = $datos->getCountMulti('id', 'idform', 'frmDAinventario');
        //Muchos Datos
        //Paginacion
        //las variables de paginación
        $page = (isset($_POST['page']) && !empty($_POST['page']))?$_POST['page']:1;
        $per_page = 20; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Consultar Inventario
        $datos = $datos->getTablePaginacionInventario($q,$offset,$per_page);
        if(globalFunctions::es_bidimensional($datos))
        {
            $total_pages = ceil($numrows["numrows"]/$per_page);

            $tabla = new \dti_table();
            $tabla->setIdtable('tb_campos');
            $tabla->setTitulo('Lista de Campos Creados');
            $tabla->setColumnas('id-,titulo,tipo,placeholder');
            $tabla->setEtiquetas('id-,Titulo,Tipo,Placeholder');
            $tabla->setDatos($datos);
            $tabla->setEliminar('seguridad/adicionalArticulos');
            $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

            echo $tabla->gettable('Dpaginacion');
        }
        else if (isset($datos["id"]))
        {
            $total_pages = ceil($numrows["numrows"]/$per_page);

            $tabla = new \dti_table();
            $tabla->setIdtable('tb_campos');
            $tabla->setTitulo('Lista de Campos Creados');
            $tabla->setColumnas('id-,titulo,tipo,placeholder');
            $tabla->setEtiquetas('id-,Titulo,Tipo,Placeholder');
            $tabla->setDatos($datos);
            $tabla->setEliminar('seguridad/adicionalArticulos');
            $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

            echo $tabla->gettable('Dpaginacion');
        }
        else
        {
            $tabla = new \dti_table();
            $tabla->setIdtable('tb_campos');
            $tabla->setTitulo('Lista de Campos Creados');
            $tabla->setColumnas('id-,titulo,tipo,placeholder');
            $tabla->setEtiquetas('id-,Titulo,Tipo,Placeholder');
            $tabla->setDatos(null);

            echo $tabla->gettable('Dpaginacion');
        }
    }
    
    /**
     * MANEJO DE XML
     */
    
    public function getxmlAll($param=array())
    {
        $documentos = new Models\Sis10000Model($this->adapter,$param);
        $dtdoc = $documentos->getDescargarSri($param);
        if (globalFunctions::es_bidimensional($dtdoc))
        {
            foreach ($dtdoc as $value)
            {
                $nro_clave_acceso = trim($value['autorizacion']);
                if ($value['ambiente'] == 1) {
                    $clienteSOAP = new \SoapClient('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
                }else{
                    $clienteSOAP = new \SoapClient('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
                }
                $resultado = $clienteSOAP->autorizacionComprobante(array('claveAccesoComprobante'=>$nro_clave_acceso));
                if (isset($resultado->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
                    //Documento Enviar Por Correo
                    if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.".xml"))
                    {
                        unlink(PATH_FIRMADAS.$nro_clave_acceso.".xml");
                    }
                    //XML Limpio
                    $file=fopen(PATH_FIRMADAS.$nro_clave_acceso.".xml","a") or die("Problemas");
                    fputs($file,$resultado->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
                    fclose($file);

                    $documentos->updateMultiColum('descargada', 1, 'id', $value['id']);
                }
            }
        }
        else if (isset($dtdoc['id']))
        {
            $nro_clave_acceso = $dtdoc['autorizacion'];
            if ($dtdoc['ambiente'] == 1) {
                    $clienteSOAP = new \SoapClient('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
                }else{
                    $clienteSOAP = new \SoapClient('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
            }
            $resultado = $clienteSOAP->autorizacionComprobante(array('claveAccesoComprobante'=>$nro_clave_acceso));
            if (isset($resultado->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
                if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.".xml"))
                {
                    unlink(PATH_FIRMADAS.$nro_clave_acceso.".xml");
                }

                $file=fopen(PATH_FIRMADAS.$nro_clave_acceso.".xml","a") or die("Problemas");
                fputs($file,$resultado->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
                fclose($file);

                $documentos->updateMultiColum('descargada', 1, 'id', $dtdoc['id']);
            }
        }
        $this->getpdfAll($param);
    }
    
    public function getpdfAll($param=array())
    {
        $documentos = new Entidades\Sis10000($this->adapter,$param);
        $dtdoc = $documentos->getMulti("descargada", 1);
        if (globalFunctions::es_bidimensional($dtdoc))
        {
            foreach ($dtdoc as $value) {
                $nro_clave_acceso = $value['autorizacion'];
                switch ($value['tipo']) {
                    case 'Factura':
                        $this->getpdfsriF($nro_clave_acceso,$param);
                        break;
                    case 'Nota de Credito':
                        $this->getpdfsriNC($nro_clave_acceso,$param);
                        break;
                    case 'Comprobante de Retencion':
                        $this->getpdfsriR($nro_clave_acceso,$param);
                        break;
                }
                $documentos->updateMultiColum('descargada', 2, 'id', $value['id']);
                //Verifico el ambiente antes de enviar o no el correo
                $empresa = new Entidades\Sis00100($this->adapter,$param);
                $dtempresa = $empresa->getMulti("id", $value['empresa']);
                if ($dtempresa['ambiente'] != "1")
                {
                    $this->sendCorreoManual($nro_clave_acceso,$param);
                }
                else
                {
                    $this->sendCorreoManual($nro_clave_acceso,$param);
                }
            }
        }
        
        else if (isset($dtdoc['id']))
        {
            $nro_clave_acceso = $dtdoc['autorizacion'];
            switch ($dtdoc['tipo']) {
                case 'Factura':
                    $this->getpdfsriF($nro_clave_acceso,$param);
                    break;
                case 'Nota de Credito':
                    $this->getpdfsriNC($nro_clave_acceso,$param);
                    break;
                case 'Comprobante de Retencion':
                    $this->getpdfsriR($nro_clave_acceso,$param);
                    break;
            }
            $documentos->updateMultiColum('descargada', 2, 'id', $dtdoc['id']);
            //Verifico el ambiente antes de enviar o no el correo
            $empresa = new Entidades\Sis00100($this->adapter,$param);
            $dtempresa = $empresa->getMulti("id", $dtdoc['empresa']);
            if ($dtempresa['ambiente'] != "1") {
                $this->sendCorreoManual($nro_clave_acceso,$param);
            }
            else
            {
                $this->sendCorreoManual($nro_clave_acceso,$param);
            }
        }
        echo 'OK';
    }
    
    public function sendCorreoManual($nro_clave_acceso,$param=array())
    {
        $nro_clave_acceso = trim($nro_clave_acceso);
        
        if (strlen($nro_clave_acceso)>0) {
            if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.'.xml')) {
                $xml = simplexml_load_file(PATH_FIRMADAS.$nro_clave_acceso.'.xml');
                $estab = (string) $xml->infoTributaria->estab;
                $ptoEmi = (string) $xml->infoTributaria->ptoEmi;
                $secuencial = (string) $xml->infoTributaria->secuencial;
                $ruc = (string) $xml->infoTributaria->ruc;
                $razonsocial = (string) $xml->infoTributaria->razonSocial;
                $cliente = "";
                $documento = "";
                if (isset($xml->infoCompRetencion)) {
                    $cliente = (string) $xml->infoCompRetencion->razonSocialSujetoRetenido;
                    $ruccliente = (string) $xml->infoCompRetencion->identificacionSujetoRetenido;
                    $documento = "Comprobante de Retencion";
                    
                    //Correo del cliente
                    $correocli = new \Models\Cp00000Model($this->adapter,$param);
                    $dtcorreocli = $correocli->getEnvioProveedor($ruccliente,$ruc,$param);
                }
                if (isset($xml->infoNotaCredito)) {
                    $cliente = (string) $xml->infoNotaCredito->razonSocialComprador;
                    $ruccliente = (string) $xml->infoNotaCredito->identificacionComprador;
                    $documento = "Nota de Credito";
                    
                    //Correo del cliente
                    $correocli = new \Models\Cc00000Model($this->adapter,$param);
                    $dtcorreocli = $correocli->getEnvioCliente($ruccliente,$ruc,$param);
                }
                if (isset($xml->infoFactura)) {
                    $cliente = (string) $xml->infoFactura->razonSocialComprador;
                    $ruccliente = (string) $xml->infoFactura->identificacionComprador;
                    $documento = "Factura";
                    
                    //Correo del cliente
                    $correocli = new \Models\Cc00000Model($this->adapter,$param);
                    $dtcorreocli = $correocli->getEnvioCliente($ruccliente,$ruc,$param);
                }
            }
            else {
                exit('No existe el XML.');
            }
            
            $correo_opcional = new Entidades\Sis00100($this->adapter,$param);
            $dtcorreo_opcional = $correo_opcional->getMulti('ruc', $ruc);
            
            //Correo de la empresa
            $correo_soporte = 'soporte@dtiware.com';
            if ($ruc=='1191757528001') {
                $correo_soporte = 'ventas@telasa.com.ec';
            }
            if ($ruc=='1891772021001') {
                $correo_soporte = 'ingepartscontabilidad@gmail.com';
            }
            
            //Validamos que tenga correo sino enviamos a un correo
            if (isset($dtcorreocli['correo']))
            {
                $correos = explode(";",$dtcorreocli['correo']);
                foreach ($correos as $correo) {
                    //Creamos la instancia de la clase PHPMailer y configuramos la cuenta
                    $mail=new \PHPMailer\PHPMailer\PHPMailer();
                    //$mail->SMTPDebug  = 2;
                    $mail->Mailer="smtp";
                    $mail->Helo = "www.dtiware.com"; //Muy importante para que llegue a hotmail y otros
                    $mail->SMTPAuth=true;
                    $mail->Host=$this->website["smtp_hostname"];
                    $mail->Port=$this->website["smtp_port"]; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
                    $mail->Username=$this->website["smtp_username"];
                    $mail->Password=$this->website["smtp_password"];
                    $mail->From=$this->website["smtp_username"];//Poner el correo de soporte del cliente
                    $mail->FromName=$razonsocial.' - Comprobante Electronico';
                    $mail->Timeout=60;
                    $mail->IsHTML(true);
                    //Enviamos el correo
                    $mail->AddAddress($correo); //Puede ser Hotmail
                    $mail->Subject ='Ha recibido un(a) '.$documento.' nuevo(a) No. '.$estab.'-'.$ptoEmi.'-'.$secuencial.'';

                    $mail->AddEmbeddedImage('public/images/header_fe.png', 'header_fe');
                    $mail->AddEmbeddedImage('public/images/footer_fe.png', 'footer_fe');

                    $htmlFactura = '<!DOCTYPE html>
                                <html>
                                    <head>
                                            <meta charset="utf-8">
                                            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                            <title>Facturacion Electronica</title>
                                    </head>
                                    <body>
                                        <div>
                                            <table width="100%" style="align-content: center;">
                                                    <tr>
                                                        <td><img src="cid:header_fe" alt="BIEVENIDA" width="520" height="100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Estimado(a) '.$cliente.'</b><br>
                                                            <p>Hemos emitido el siguiente comprobante electronico:<br></p>
                                                            <p><b>Documento:</b>'.$documento.'</p>
                                                            <p><b>Numero:</b>'.$estab.'-'.$ptoEmi.'-'.$secuencial.'</p>
                                                            <p><b>Clave de Acceso:</b>'.$nro_clave_acceso.'</p>
                                                            <p><b>Fecha:</b>'.date("Y-m-d").'</p><br>
                                                            <p>Si tiene cualquier inquietud escribanos a: '.$correo_soporte.' </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="cid:footer_fe" alt="EMPRESA" width="520" height="100"></td>
                                                    </tr>
                                            </table>
                                        </div>
                                    </body>
                                </html>';

                    $htmlFactura.= '<img border="0" src="'.APP_URL.'/publico/mailtracker/'.$correo.'/'.$estab.'-'.$ptoEmi.'-'.$secuencial.'/'.$documento.'" width="1" height="1" alt="" >';

                    $mail->Body=$htmlFactura;
                    $mail->AltBody='Estimado(a) '.$cliente.' Hemos emitido el siguiente comprobante electronico: Documento: '.$documento.' Numero: '.$estab.'-'.$ptoEmi.'-'.$secuencial.' Clave de Acceso: '.$nro_clave_acceso.' Fecha: '.date("Y-m-d").' Si tiene cualquier inquietud escribanos a: '.$correo_soporte.'';

                    $docxml = PATH_FIRMADAS."_".$nro_clave_acceso.".xml";
                    $docpdf = PATH_FIRMADAS.$nro_clave_acceso.".pdf";
                    $mail->addAttachment($docxml);
                    $mail->addAttachment($docpdf, $name = $nro_clave_acceso.".pdf",  $encoding = 'base64', $type = 'application/pdf');

                    $exito = $mail->Send();

                    //Guardar Historial
                    $auditoria = new Entidades\Sis70000($this->adapter,$param);
                    $auditoria->autocommit();
                    
                    $auditoria->setEmpresa($this->session->get('empresa'));
                    $auditoria->setCorreo($correo);
                    $auditoria->setDocumento(''.$estab.'-'.$ptoEmi.'-'.$secuencial.'');
                    $auditoria->setModulo('FE');
                    $auditoria->setFecha_envio(date("Y-m-d"));
                    if($exito){
                        $mail->ClearAddresses();
                        $html = 1;
                        $auditoria->setDescripcion('Correo Entregado');
                        $auditoria->setEstado('Entregado');
                    }
                    else
                    {
                        $auditoria->setDescripcion($mail->ErrorInfo);
                        $auditoria->setEstado('NO Entregado');
                        $html = '<div class="alert alert-dismissible alert-warning">
                                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                                      <h4>ERROR!</h4>
                                      <p><strong>' . $mail->ErrorInfo . '</strong></p>
                                 </div>';
                    }
                    $auditoria->save();
                    $auditoria->commit();
                    echo $html;
                }
            }
            else
            {
                //Creamos la instancia de la clase PHPMailer y configuramos la cuenta
                $mail=new \PHPMailer\PHPMailer\PHPMailer();
                //$mail->SMTPDebug  = 2;
                $mail->Mailer="smtp";
                $mail->Helo = "www.dtiware.com"; //Muy importante para que llegue a hotmail y otros
                $mail->SMTPAuth=true;
                $mail->Host=$this->website["smtp_hostname"];
                $mail->Port=$this->website["smtp_port"]; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
                $mail->Username=$this->website["smtp_username"];
                $mail->Password=$this->website["smtp_password"];
                $mail->From=$this->website["smtp_username"];//Poner el correo de soporte del cliente
                $mail->FromName=$razonsocial.' - Comprobante Electronico';
                $mail->Timeout=60;
                $mail->IsHTML(true);
                //Enviamos el correo
                $mail->AddAddress($dtcorreo_opcional['correo']); //Puede ser Hotmail
                $mail->Subject ='Ha recibido un(a) '.$documento.' nuevo(a) No. '.$estab.'-'.$ptoEmi.'-'.$secuencial.'';
                
                $mail->AddEmbeddedImage('public/images/header_fe.png', 'header_fe');
                $mail->AddEmbeddedImage('public/images/footer_fe.png', 'footer_fe');
                
                $htmlFactura = '<!DOCTYPE html>
                            <html>
                                <head>
                                        <meta charset="utf-8">
                                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                        <title>Facturacion Electronica</title>
                                </head>
                                <body>
                                    <div>
                                        <table width="100%" style="align-content: center;">
                                                <tr>
                                                    <td><img src="cid:header_fe" alt="BIEVENIDA" width="520" height="100"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Estimado(a) '.$cliente.'</b><br>
                                                        <p>Hemos emitido el siguiente comprobante electronico:<br></p>
                                                        <p><b>Documento:</b>'.$documento.'</p>
                                                        <p><b>Numero:</b>'.$estab.'-'.$ptoEmi.'-'.$secuencial.'</p>
                                                        <p><b>Clave de Acceso:</b>'.$nro_clave_acceso.'</p>
                                                        <p><b>Fecha:</b>'.date("Y-m-d").'</p><br>
                                                        <p>Si tiene cualquier inquietud escribanos a: '.$correo_soporte.' </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><img src="cid:footer_fe" alt="EMPRESA" width="520" height="100"></td>
                                                </tr>
                                        </table>
                                    </div>
                                </body>
                            </html>';
                
                $htmlFactura.= '<img border="0" src="'.APP_URL.'/publico/mailtracker/'.$dtcorreo_opcional['correo'].'/'.$estab.'-'.$ptoEmi.'-'.$secuencial.'/'.$documento.'" width="1" height="1" alt="" >';

                $mail->Body=$htmlFactura;
                $mail->AltBody='Estimado(a) '.$cliente.' Hemos emitido el siguiente comprobante electronico: Documento: '.$documento.' Numero: '.$estab.'-'.$ptoEmi.'-'.$secuencial.' Clave de Acceso: '.$nro_clave_acceso.' Fecha: '.date("Y-m-d").' Si tiene cualquier inquietud escribanos a: '.$correo_soporte.'';
                
                $docxml = PATH_FIRMADAS."_".$nro_clave_acceso.".xml";
                $docpdf = PATH_FIRMADAS.$nro_clave_acceso.".pdf";
                $mail->addAttachment($docxml);
                $mail->addAttachment($docpdf, $name = $nro_clave_acceso.".pdf",  $encoding = 'base64', $type = 'application/pdf');
                
                $exito = $mail->Send();
                
                //Guardar Historial
                $auditoria = new Entidades\Sis70000($this->adapter,$param);
                $auditoria->autocommit();
                
                $auditoria->setEmpresa($this->session->get('empresa'));
                $auditoria->setCorreo($dtcorreo_opcional['correo']);
                $auditoria->setDocumento(''.$estab.'-'.$ptoEmi.'-'.$secuencial.'');
                $auditoria->setModulo('FE');
                $auditoria->setFecha_envio(date("Y-m-d"));
                if($exito){
                    $mail->ClearAddresses();
                    $html = 1;
                    $auditoria->setDescripcion('Correo Entregado');
                    $auditoria->setEstado('Entregado');
                }
                else
                {
                    $auditoria->setDescripcion($mail->ErrorInfo);
                    $auditoria->setEstado('NO Entregado');
                    $html = '<div class="alert alert-dismissible alert-warning">
                                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                                  <h4>ERROR!</h4>
                                  <p><strong>' . $mail->ErrorInfo . '</strong></p>
                             </div>';
                }
                $auditoria->save();
                $auditoria->commit();
                echo $html;
            }
        }
    }
    
    public function sendReManual($param=array())
    {
        $nro_clave_acceso =  trim($param['autorizacion']);
        
        if (strlen($nro_clave_acceso)>0) {
            if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.'.xml')) {
                $xml = simplexml_load_file(PATH_FIRMADAS.$nro_clave_acceso.'.xml');
                $estab = (string) $xml->infoTributaria->estab;
                $ptoEmi = (string) $xml->infoTributaria->ptoEmi;
                $secuencial = (string) $xml->infoTributaria->secuencial;
                $ruc = (string) $xml->infoTributaria->ruc;
                $razonsocial = (string) $xml->infoTributaria->razonSocial;
                $cliente = "";
                $documento = "";
                if (isset($xml->infoCompRetencion)) {
                    $cliente = (string) $xml->infoCompRetencion->razonSocialSujetoRetenido;
                    $ruccliente = (string) $xml->infoCompRetencion->identificacionSujetoRetenido;
                    $documento = "Comprobante de Retencion";
                    
                    //Correo del cliente
                    $correocli = new \Models\Cp00000Model($this->adapter);
                    $dtcorreocli = $correocli->getEnvioProveedor($ruccliente,$ruc);
                }
                if (isset($xml->infoNotaCredito)) {
                    $cliente = (string) $xml->infoNotaCredito->razonSocialComprador;
                    $ruccliente = (string) $xml->infoNotaCredito->identificacionComprador;
                    $documento = "Nota de Credito";
                    
                    //Correo del cliente
                    $correocli = new \Models\Cc00000Model($this->adapter);
                    $dtcorreocli = $correocli->getEnvioCliente($ruccliente,$ruc);
                }
                if (isset($xml->infoFactura)) {
                    $cliente = (string) $xml->infoFactura->razonSocialComprador;
                    $ruccliente = (string) $xml->infoFactura->identificacionComprador;
                    $documento = "Factura";
                    
                    //Correo del cliente
                    $correocli = new \Models\Cc00000Model($this->adapter);
                    $dtcorreocli = $correocli->getEnvioCliente($ruccliente,$ruc);
                }
            }
            else {
                die(json_encode(array('status' => 'Error', 'descripcion' => 'No Existe XML.')));
            }
            
            //Correo de la empresa
            $correo_soporte = 'soporte@dtiware.com';
            if ($ruc=='1191757528001') {
                $correo_soporte = 'ventas@telasa.com.ec';
            }
            if ($ruc=='1891772021001') {
                $correo_soporte = 'ingepartscontabilidad@gmail.com';
            }
            
            $correos = explode(";",$param['correo']);
            foreach ($correos as $correo) {
                //Creamos la instancia de la clase PHPMailer y configuramos la cuenta
                $mail=new \PHPMailer\PHPMailer\PHPMailer();
                //$mail->SMTPDebug  = 2;
                $mail->Mailer="smtp";
                $mail->Helo = "www.dtiware.com"; //Muy importante para que llegue a hotmail y otros
                $mail->SMTPAuth=true;
                $mail->Host=$this->website["smtp_hostname"];
                $mail->Port=$this->website["smtp_port"]; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
                $mail->Username=$this->website["smtp_username"];
                $mail->Password=$this->website["smtp_password"];
                $mail->From=$this->website["smtp_username"];//Poner el correo de soporte del cliente
                $mail->FromName=$razonsocial.' - Comprobante Electronico';
                $mail->Timeout=60;
                $mail->IsHTML(true);
                //Enviamos el correo
                $mail->AddAddress($correo); //Puede ser Hotmail
                $mail->Subject ='Ha recibido un(a) '.$documento.' nuevo(a) No. '.$estab.'-'.$ptoEmi.'-'.$secuencial.'';

                $mail->AddEmbeddedImage('public/images/header_fe.png', 'header_fe');
                $mail->AddEmbeddedImage('public/images/footer_fe.png', 'footer_fe');

                $htmlFactura = '<!DOCTYPE html>
                            <html>
                                <head>
                                        <meta charset="utf-8">
                                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                        <title>Facturacion Electronica</title>
                                </head>
                                <body>
                                    <div>
                                        <table width="100%" style="align-content: center;">
                                                <tr>
                                                    <td><img src="cid:header_fe" alt="BIEVENIDA" width="520" height="100"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Estimado(a) '.$cliente.'</b><br>
                                                        <p>Hemos emitido el siguiente comprobante electronico:<br></p>
                                                        <p><b>Documento:</b>'.$documento.'</p>
                                                        <p><b>Numero:</b>'.$estab.'-'.$ptoEmi.'-'.$secuencial.'</p>
                                                        <p><b>Clave de Acceso:</b>'.$nro_clave_acceso.'</p>
                                                        <p><b>Fecha:</b>'.date("Y-m-d").'</p><br>
                                                        <p>Si tiene cualquier inquietud escribanos a: '.$correo_soporte.' </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><img src="cid:footer_fe" alt="EMPRESA" width="520" height="100"></td>
                                                </tr>
                                        </table>
                                    </div>
                                </body>
                            </html>';

                $htmlFactura.= '<img border="0" src="'.APP_URL.'/publico/mailtracker/'.$correo.'/'.$estab.'-'.$ptoEmi.'-'.$secuencial.'/'.$documento.'" width="1" height="1" alt="" >';

                $mail->Body=$htmlFactura;
                $mail->AltBody='Estimado(a) '.$cliente.' Hemos emitido el siguiente comprobante electronico: Documento: '.$documento.' Numero: '.$estab.'-'.$ptoEmi.'-'.$secuencial.' Clave de Acceso: '.$nro_clave_acceso.' Fecha: '.date("Y-m-d").' Si tiene cualquier inquietud escribanos a: '.$correo_soporte.'';

                $docxml = PATH_FIRMADAS."_".$nro_clave_acceso.".xml";
                $docpdf = PATH_FIRMADAS.$nro_clave_acceso.".pdf";
                $mail->addAttachment($docxml);
                $mail->addAttachment($docpdf, $name = $nro_clave_acceso.".pdf",  $encoding = 'base64', $type = 'application/pdf');

                $exito = $mail->Send();

                //Guardar Historial
                $auditoria = new Entidades\Sis70000($this->adapter,$param);
                $auditoria->autocommit();
                
                $auditoria->setEmpresa($this->session->get('empresa'));
                $auditoria->setCorreo($correo);
                $auditoria->setDocumento(''.$estab.'-'.$ptoEmi.'-'.$secuencial.'');
                $auditoria->setModulo('FE');
                $auditoria->setFecha_envio(date("Y-m-d"));
                if($exito){
                    $auditoria->setDescripcion('Correo Entregado');
                    $auditoria->setEstado('Entregado');
                    $auditoria->save();
                    $auditoria->commit();
                }
                else
                {
                    $auditoria->setDescripcion($mail->ErrorInfo);
                    $auditoria->setEstado('NO Entregado');
                    $auditoria->save();
                    $auditoria->commit();
                }
            }
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Correo Enviado Correctamente.')));
        }
    }
    
    public function getpdfsriF($nro_clave_acceso,$param=array())
    {
        if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.'.xml')) {
            $xml = simplexml_load_file(PATH_FIRMADAS.$nro_clave_acceso.'.xml');
            $clave_acceso = (string) $xml->infoTributaria->claveAcceso;
            $razonSocial = (string) $xml->infoTributaria->razonSocial;
            $dirMatriz = (string) $xml->infoTributaria->dirMatriz;
            $dirEstablecimiento = (string) $xml->infoFactura->dirEstablecimiento;
            $contribuyenteEspecial = (string) $xml->infoFactura->contribuyenteEspecial;
            $obligadoContabilidad = (string) $xml->infoFactura->obligadoContabilidad;
            $ruc = (string) $xml->infoTributaria->ruc;
            $estab = (string) $xml->infoTributaria->estab;
            $ptoEmi = (string) $xml->infoTributaria->ptoEmi;
            $secuencial = (string) $xml->infoTributaria->secuencial;
            $ambiente = (string) $xml->infoTributaria->ambiente;
            $razonSocialComprador = (string) $xml->infoFactura->razonSocialComprador;
            $direccionComprador = (string) $xml->infoFactura->direccionComprador;
            $identificacionComprador = (string) $xml->infoFactura->identificacionComprador;
            $fechaEmision = (string) $xml->infoFactura->fechaEmision;
            $totalSinImpuestos = (string) $xml->infoFactura->totalSinImpuestos;
            $totalDescuento = (string) $xml->infoFactura->totalDescuento;
            $propina = (string) $xml->infoFactura->propina;
            $importeTotal = (string) $xml->infoFactura->importeTotal;
            $detalles = $xml->detalles->detalle;
            $totalConImpuestos = $xml->infoFactura->totalConImpuestos;
            $pagos = $xml->infoFactura->pagos;
            $infoAdicional = $xml->infoAdicional;
            //Forma de Pago en letras
        } 
        else {
            exit('No existe el XML.');
        }
        
        $generator = new \barcode_generator();
        $image = $generator->render_image('code-128', $clave_acceso, array(
            'ts'=>2,
            'th'=>12,
            'w'=>300,
            'h'=>70,
        ));
        $save = "public/images/".$clave_acceso.".jpg";
        imagejpeg($image, $save);
        //imagepng($image, $save);
        imagedestroy($image);
        
        //Consultar logo
        $empre = new Entidades\Sis00100($this->adapter,$param);
        $dtempre = $empre->getMulti("ruc", $ruc);
        
        $html ='<!DOCTYPE html>';
        $html.='<html>';
        $html.='<head>';
        $html.='<meta charset="utf-8">';
        $html.='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html.='<title>Facturacion Electronica</title>';
        $html.='<style type="text/css">';
        $html.='body {font-family: Delicious, sans-serif;font-size: 9px;}';
        $html.='.bordeLinea {float:left;width: 50%;}';
        $html.='.borde {border-radius: 20px 20px 20px 20px;-moz-border-radius: 20px 20px 20px 20px;-webkit-border-radius: 20px 20px 20px 20px;border: 1px solid #000000;}';
        $html.='.bordeCuadrado{border: 1px solid #000000;}';
        $html.='label {margin-left: 20px;padding-bottom: 30px;padding-top: 30px;}';
        $html.='.bordeTabla{border-collapse: collapse;text-align: center;}';
        $html.='.bordeTablaLeft{border-collapse: collapse;}';
        $html.='.bordeTabla tr td{border: 1px solid black;}';
        $html.='.bordeTablaLeft tr td{border: 1px solid black;}';
        $html.='.imgBarcode{width:300px;height:50px;margin-left:15px}';
        $html.='.imgLogo{width:200px;margin-left: 60px;  margin-top: 10px;max-height: 120px;}';
        $html.='.txtDerecha{text-align: right;}';
        $html.='.infoAdicional{margin-left:15px}';
        $html.='td {padding: 2px;}';
        $html.='#footer {padding-top:5px 0; border-top: 1px solid; width:100%; position: fixed; left: 0; bottom: 0;}
                #footer .fila td {text-align:left; width:100%;}
                #footer .fila td span {font-size: 10px; color: #334373;}';
        $html.='</style>';
        $html.='</head>';
        $html.='<body>';
        $html.='    <div>';
        $html.='        <div style="float:left;width: 48%;margin-right: 15px;">';
        $html.='        <div style="height: 130px">';
        $html.='            <img alt="SIN LOGO" class="imgLogo" src="'.PATH_IMG_PDF.$dtempre['logo'].'" /><br><br><br><br>';
        $html.='        </div>';
        $html.='        <div class="borde" style="height: 140px">';
        $html.='            <br><label>'.$razonSocial.'</label><br><br>';
        $html.='            <label>Dir Matriz: '.$dirMatriz.'</label><br><br>';
        $html.='            <label>Dir Sucursal: '.$dirEstablecimiento.'</label><br><br>';
        if (strlen($contribuyenteEspecial)>0)
            $html.='            <label>Contribuyente Especial Nro '.$contribuyenteEspecial.'</label><br>';
        if (strlen($obligadoContabilidad)>0){
            $html.='            <label>OBLIGADO A LLEVAR CONTABILIDAD: '.$obligadoContabilidad.'</label><br><br>';
        }
        $html.='        </div>';
        $html.='    </div>';
        $html.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
        $html.='        <br><b><label style="font-size:12px;">R.U.C.: '.$ruc.'</label></b><br><br>';
        $html.='        <b><label style="font-size:14px;">F A C T U R A</label></b><br><br>';
        $html.='        <label>No. '.$estab.'-'.$ptoEmi.'-'.$secuencial.'</label><br><br>';
        $html.='        <label>NÚMERO DE AUTORIZACIÓN:</label><br><br>';
        $html.='        <label style="font-size:8px;">'.$clave_acceso.'</label><br><br>';
        if ($ambiente == 1){
            $html.='        <label>AMBIENTE: PRUEBAS</label><br><br>';
        }
        else {
            $html.='        <label>AMBIENTE: PRODUCCION</label><br><br>';
        }
        $html.='        <label>EMISIÓN: NORMAL</label><br><br>';
        $html.='        <b><label>CLAVE DE ACCESO:</label></b><br>';
        $html.='        <img alt="ERROR" class="imgBarcode" src="'.PATH_IMG_PDF.'public/images/'.$clave_acceso.'.jpg" /><br><br><br><br>';
        $html.='    </div>';
        $html.='</div>';
        $html.='<div style="clear:both"></div>';
        $html.='<br>';
        $html.='<div class="bordeCuadrado">';
        $html.='    <table width="100%">';
        $html.='        <tr>';
        $html.='            <td><b>Razón Social / Nombres y Apellidos:</b> '.$razonSocialComprador.'</td>';
        $html.='            <td></td>';
        $html.='            <td><b>Identificación:</b> '.$identificacionComprador.'</td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td><b>Fecha Emisión:</b> '.$fechaEmision.'</td>';
        $html.='            <td></td>';
        $html.='            <td><b>Guía Remisión:</b> </td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td><b>Dirección:</b> '.$direccionComprador.'</td>';
        $html.='            <td></td>';
        $html.='            <td></td>';
        $html.='        </tr>';
        $html.='    </table>';
        $html.='</div>';
        $html.='<br>';
        $html.='<div>';
        $html.='    <table width="100%" class="bordeTabla">';
        $html.='        <tr>';
        $html.='            <td>Cod. Principal</td>';
        $html.='            <td>Cod. Auxiliar</td>';
        $html.='            <td>Cantidad</td>';
        $html.='            <td>Descripción</td>';
        $html.='            <td>Precio Unitario</td>';
        $html.='            <td>Subsidio</td>';
        $html.='            <td>Precio Sin Subsidio</td>';
        $html.='            <td>Descuento</td>';
        $html.='            <td>Precio Total</td>';
        $html.='        </tr>';
        
        foreach ($detalles as $dtprod) {
            $html.='        <tr>';
            $html.='            <td>'.(string) $dtprod->codigoPrincipal.'</td>';
            $html.='            <td>'.(string) $dtprod->codigoAuxiliar.'</td>';
            $html.='            <td>'.(string) $dtprod->cantidad.'</td>';
            $html.='            <td>'.(string) $dtprod->descripcion.'</td>';
            $html.='            <td>'.(string) $dtprod->precioUnitario.'</td>';
            $html.='            <td>0.00</td>';
            $html.='            <td>0.00</td>';
            $html.='            <td>'.(string) $dtprod->descuento.'</td>';
            $html.='            <td>'.(string) $dtprod->precioTotalSinImpuesto.'</td>';
            $html.='        </tr>';
        }

        $html.='    </table>';
        $html.='</div>';
        $html.='<br>';
        $html.='<div>';
        $html.='    <div style="float:left;width: 58%;margin-right: 15px;">';
        $html.='        <div class="bordeCuadrado">';
        $html.='            <br>  <b class="infoAdicional">Información Adicional</b><br><br>';
        $html.='            <table width="100%">';
        
        foreach ($infoAdicional->campoAdicional as $value) {
            $html.='                <tr>';
            $html.='                    <td>'.(string)$value['nombre'].': </td>';
            $html.='                    <td>'.(string)$value.'</td>';
            $html.='                </tr>';
        }
        
        $html.='            </table>';
        $html.='        </div>';
        $html.='        <br>';
        $html.='        <table width="100%" class="bordeTabla">';
        $html.='            <tr>';
        $html.='                <td>Forma de Pago</td>';
        $html.='                <td>Total</td>';
        $html.='                <td>Plazo</td>';
        $html.='                <td>Tiempo</td>';
        $html.='            </tr>';

        foreach ($pagos->pago as $value) {
            $html.='            <tr>';
            $html.='                <td>'.$value->formaPago.'</td>';
            $html.='                <td>'.$value->total.'</td>';
            $html.='                <td>'.$value->plazo.'</td>';
            $html.='                <td>'.$value->unidadTiempo.'</td>';
            $html.='            </tr>';
        }

        $html.='        </table>';
        $html.='    </div>';

        //Valores totales
        $subtotal12 = 0.00;
        $subtotal0 = 0.00;
        $subtotalNoObj = 0.00;
        $subtotalExento = 0.00;
        $ivatotal = 0.00;
        
        foreach ($totalConImpuestos->totalImpuesto as $value) {
            if ($value->codigo == 2) {
                switch ($value->codigoPorcentaje) {
                    case 0:
                        $subtotal0 += (float) $value->baseImponible;
                        $ivatotal += (float) $value->valor;
                        break;
                    case 2:
                        $subtotal12 += (float) $value->baseImponible;
                        $ivatotal += (float) $value->valor;
                        break;
                    case 6:
                        $subtotalNoObj += (float) $value->baseImponible;
                        $ivatotal += (float) $value->valor;
                        break;
                    case 7:
                        $subtotalExento += (float) $value->baseImponible;
                        $ivatotal += (float) $value->valor;
                        break;
                }
            }
        }
        
        //$html.='    <div style="float:left;width: 38%;margin-left: 480px;">';
        $html.='    <div style="float:rigth;width: 38%;margin-left: 490px;">';
        //$html.='      <table width="80%" class="bordeTablaLeft">';
        $html.='        <table width="80%" class="bordeTablaLeft">';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL 12%</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$subtotal12, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL IVA 0%</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$subtotal0, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL NO OBJETO IVA</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$subtotalNoObj, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL EXENTO IVA</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$subtotalExento, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL SIN IMPUESTOS</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$totalSinImpuestos, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>DESCUENTO</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$totalDescuento, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>ICE</td>';
        $html.='                <td class="txtDerecha">0.00</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>IVA 12%</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$ivatotal, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>IRBPNR</td>';
        $html.='                <td class="txtDerecha">0.00</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>PROPINA</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$propina, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>VALOR TOTAL</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$importeTotal, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='        </table>';
        $html.='    </div>';
        $html.='</div>';
        $html.='<div style="clear:both"></div>';
        $html.='<page_footer>
                    <table id="footer">
                        <tr class="fila">
                            <td>
                                <span>Comprobante Electrónico creado en www.portal.dtiware.com</span>
                            </td>
                        </tr>
                    </table>
                </page_footer>';
        $html.='</body>';
        $html.='</html>';
        // Instanciamos un objeto de la clase DOMPDF.
        $pdf = new \Dompdf\Dompdf();
        // Definimos el tamaño y orientación del papel que queremos.
        $pdf->set_paper("A4", "portrait");
        // Cargamos el contenido HTML.
        $pdf->load_html($html,'UTF-8');
        // Renderizamos el documento PDF.
        $pdf->render();
        // Enviamos el fichero PDF al navegador.
        //$pdf->stream('FicheroEjemplo.pdf'); //Asignamos Nombre Fijo
        //$pdf->stream(); //Nombre por defecto
        // Mostramos el fichero sin descargar en el navegador  (1 = download and 0 = preview)
        //$pdf->stream($pedido.".pdf", array("Attachment" => 1));
        //Eliminar si Existe
        if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.".pdf"))
        {
            unlink(PATH_FIRMADAS.$nro_clave_acceso.".pdf");
        }
        //Guardar en el servidor el archivo
        file_put_contents(PATH_FIRMADAS.$nro_clave_acceso.".pdf", $pdf->output());

    }
    
    public function getpdfsriR($nro_clave_acceso,$param=array())
    {
        if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.'.xml')) {
            $xml = simplexml_load_file(PATH_FIRMADAS.$nro_clave_acceso.'.xml');
            $razonSocial = (string) $xml->infoTributaria->razonSocial;
            $dirMatriz = (string) $xml->infoTributaria->dirMatriz;
            $ambiente = (string) $xml->infoTributaria->ambiente;
            $dirEstablecimiento = (string) $xml->infoCompRetencion->dirEstablecimiento;
            $contribuyenteEspecial = (string) $xml->infoCompRetencion->contribuyenteEspecial;
            $obligadoContabilidad = (string) $xml->infoCompRetencion->obligadoContabilidad;
            $clave_acceso = (string) $xml->infoTributaria->claveAcceso;
            $ruc = (string) $xml->infoTributaria->ruc;
            $estab = (string) $xml->infoTributaria->estab;
            $ptoEmi = (string) $xml->infoTributaria->ptoEmi;
            $secuencial = (string) $xml->infoTributaria->secuencial;
            $razonSocialSujetoRetenido = (string) $xml->infoCompRetencion->razonSocialSujetoRetenido;
            $identificacionSujetoRetenido = (string) $xml->infoCompRetencion->identificacionSujetoRetenido;
            $fechaEmision = (string) $xml->infoCompRetencion->fechaEmision;
            $periodoFiscal = (string) $xml->infoCompRetencion->periodoFiscal;
            $detalles = $xml->impuestos->impuesto;
            $infoAdicional = $xml->infoAdicional;
        }
        else {
            exit('No existe el XML.');
        }
        
        $generator = new \barcode_generator();
        $image = $generator->render_image('code-128', $clave_acceso, array(
            'ts'=>2,
            'th'=>12,
            'w'=>300,
            'h'=>70,
        ));
        $save = "public/images/".$clave_acceso.".jpg";
        imagejpeg($image, $save);
        //imagepng($image, $save);
        imagedestroy($image);
        
        //Consultar logo
        $empre = new Entidades\Sis00100($this->adapter,$param);
        $dtempre = $empre->getMulti("ruc", $ruc);
        
        $html ='<!DOCTYPE html>';
        $html.='<html>';
        $html.='<head>';
        $html.='<meta charset="utf-8">';
        $html.='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html.='<title>Facturacion Electronica</title>';
        $html.='<style type="text/css">';
        $html.='body {font-family: Delicious, sans-serif;font-size: 9px;}';
        $html.='.bordeLinea {float:left;width: 50%;}';
        $html.='.borde {border-radius: 20px 20px 20px 20px;-moz-border-radius: 20px 20px 20px 20px;-webkit-border-radius: 20px 20px 20px 20px;border: 1px solid #000000;}';
        $html.='.bordeCuadrado{border: 1px solid #000000;}';
        $html.='label {margin-left: 20px;padding-bottom: 30px;padding-top: 30px;}';
        $html.='.bordeTabla{border-collapse: collapse;text-align: center;}';
        $html.='.bordeTablaLeft{border-collapse: collapse;}';
        $html.='.bordeTabla tr td{border: 1px solid black;}';
        $html.='.bordeTablaLeft tr td{border: 1px solid black;}';
        $html.='.imgBarcode{width:300px;height:50px;margin-left:15px}';
        $html.='.imgLogo{width:200px;margin-left: 60px;  margin-top: 10px;max-height: 120px;}';
        $html.='.txtDerecha{text-align: right;}';
        $html.='.infoAdicional{margin-left:15px}';
        $html.='td {padding: 2px;}';
        $html.='#footer {padding-top:5px 0; border-top: 1px solid; width:100%; position: fixed; left: 0; bottom: 0;}
                #footer .fila td {text-align:left; width:100%;}
                #footer .fila td span {font-size: 10px; color: #334373;}';
        $html.='</style>';
        $html.='</head>';
        $html.='<body>';
        $html.='    <div>';
        $html.='        <div style="float:left;width: 48%;margin-right: 15px;">';
        $html.='        <div style="height: 130px">';
        $html.='            <img alt="SIN LOGO" class="imgLogo" src="'.PATH_IMG_PDF.$dtempre['logo'].'" /><br><br><br><br>';
        $html.='        </div>';
        $html.='        <div class="borde" style="height: 140px">';
        $html.='            <br><label>'.$razonSocial.'</label><br><br>';
        $html.='            <label>Dir Matriz: '.$dirMatriz.'</label><br><br>';
        $html.='            <label>Dir Sucursal: '.$dirEstablecimiento.'</label><br><br>';
        if (strlen($contribuyenteEspecial)>0)
            $html.='            <label>Contribuyente Especial Nro '.$contribuyenteEspecial.'</label><br>';
        if (strlen($obligadoContabilidad)>0)
            $html.='            <label>OBLIGADO A LLEVAR CONTABILIDAD: '.$obligadoContabilidad.'</label><br><br>';
        $html.='        </div>';
        $html.='    </div>';
        $html.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
        $html.='        <br><b><label style="font-size:12px;">R.U.C.: '.$ruc.'</label></b><br><br>';
        $html.='        <b><label style="font-size:14px;">COMPROBANTE DE RETENCION</label></b><br><br>';
        $html.='        <label>No. '.$estab.'-'.$ptoEmi.'-'.$secuencial.'</label><br><br>';
        $html.='        <label>NÚMERO DE AUTORIZACIÓN:</label><br><br>';
        $html.='        <label style="font-size:8px;">'.$clave_acceso.'</label><br><br>';
        if ($ambiente == 1){
            $html.='        <label>AMBIENTE: PRUEBAS</label><br><br>';
        }
        else {
            $html.='        <label>AMBIENTE: PRODUCCION</label><br><br>';
        }
        $html.='        <label>EMISIÓN: NORMAL</label><br><br>';
        $html.='        <b><label>CLAVE DE ACCESO:</label></b><br>';
        $html.='        <img alt="ERROR" class="imgBarcode" src="'.PATH_IMG_PDF.'public/images/'.$clave_acceso.'.jpg" /><br><br><br><br>';
        $html.='    </div>';
        $html.='</div>';
        $html.='<div style="clear:both"></div>';
        $html.='<br>';
        $html.='<div class="bordeCuadrado">';
        $html.='    <table width="100%">';
        $html.='        <tr>';
        $html.='            <td><b>Razón Social / Nombres y Apellidos:</b> '.$razonSocialSujetoRetenido.'</td>';
        $html.='            <td></td>';
        $html.='            <td><b>Identificación:</b> '.$identificacionSujetoRetenido.'</td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td><b>Fecha Emisión:</b> '.$fechaEmision.'</td>';
        $html.='            <td></td>';
        $html.='            <td></td>';
        $html.='        </tr>';
        $html.='    </table>';
        $html.='</div>';
        $html.='<br>';
        $html.='<div>';
        $html.='    <table width="100%" class="bordeTabla">';
        $html.='        <tr>';
        $html.='            <td>Comprobante</td>';
        $html.='            <td>Número</td>';
        $html.='            <td>Fecha Emisión</td>';
        $html.='            <td>Ejercicio Fiscal</td>';
        $html.='            <td>Base Imponible para la Retención</td>';
        $html.='            <td>IMPUESTO</td>';
        $html.='            <td>Porcentaje Retención</td>';
        $html.='            <td>Valor Retenido</td>';
        $html.='        </tr>';
        
        $total = 0;
        
        foreach ($detalles as $dtprod) {
            switch ((string) $dtprod->codDocSustento) {
                case '01':
                    $codigosustento = 'FACTURA';
                    break;
                case '03':
                    $codigosustento = 'LIQ COMPRAS';
                    break;
            }

            switch ((string) $dtprod->codigo) {
                case 1:
                    $codigoimpuesto = 'RENTA';
                    break;
                case 2:
                    $codigoimpuesto = 'IVA';
                    break;
                case 6:
                    $codigoimpuesto = 'ISD';
                    break;
            }
            
            $html.='        <tr>';
            $html.='            <td>'.$codigosustento.'</td>';
            $html.='            <td>'.(string) $dtprod->numDocSustento.'</td>';
            $html.='            <td>'.(string) $dtprod->fechaEmisionDocSustento.'</td>';
            $html.='            <td>'.(string) $periodoFiscal.'</td>';
            $html.='            <td>'.(string) $dtprod->baseImponible.'</td>';
            $html.='            <td>'.$codigoimpuesto.'</td>';
            $html.='            <td>'.(string) $dtprod->porcentajeRetener.'</td>';
            $html.='            <td>'.(string) $dtprod->valorRetenido.'</td>';
            $html.='        </tr>';
            
            $total += (float) $dtprod->valorRetenido;
        }

        $html.='    </table>';
        $html.='</div>';
        $html.='<br>';
        $html.='<div>';
        $html.='    <div style="float:left;width: 58%;margin-right: 15px;">';
        $html.='        <div class="bordeCuadrado">';
        $html.='            <br>  <b class="infoAdicional">Información Adicional</b><br><br>';
        $html.='            <table width="100%">';
        
        foreach ($infoAdicional->campoAdicional as $value) {
            $html.='                <tr>';
            $html.='                    <td>'.(string)$value['nombre'].': </td>';
            $html.='                    <td>'.(string)$value.'</td>';
            $html.='                </tr>';
        }

        $html.='            </table>';
        $html.='        </div>';
        $html.='        <br>';
        $html.='    </div>';

        //$html.='    <div style="float:left;width: 38%;margin-left: 480px;">';
        $html.='    <div style="float:rigth;width: 38%;margin-left: 490px;">';
        //$html.='      <table width="80%" class="bordeTablaLeft">';
        $html.='        <table width="80%" class="bordeTablaLeft">';
        $html.='            <tr>';
        $html.='                <td>Total Retenido</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$total, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='        </table>';
        $html.='    </div>';
        $html.='</div>';
        $html.='<div style="clear:both"></div>';
        $html.='<page_footer>
                    <table id="footer">
                        <tr class="fila">
                            <td>
                                <span>Comprobante Electrónico creado en www.portal.dtiware.com</span>
                            </td>
                        </tr>
                    </table>
                </page_footer>';
        $html.='</body>';
        $html.='</html>';
        // Instanciamos un objeto de la clase DOMPDF.
        $pdf = new \Dompdf\Dompdf();
        // Definimos el tamaño y orientación del papel que queremos.
        $pdf->set_paper("A4", "portrait");
        // Cargamos el contenido HTML.
        $pdf->load_html($html,'UTF-8');
        // Renderizamos el documento PDF.
        $pdf->render();
        // Enviamos el fichero PDF al navegador.
        //$pdf->stream('FicheroEjemplo.pdf'); //Asignamos Nombre Fijo
        //$pdf->stream(); //Nombre por defecto
        // Mostramos el fichero sin descargar en el navegador  (1 = download and 0 = preview)
        //$pdf->stream($pedido.".pdf", array("Attachment" => 1));
        if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.".pdf"))
        {
            unlink(PATH_FIRMADAS.$nro_clave_acceso.".pdf");
        }
        //Guardar en el servidor el archivo
        file_put_contents(PATH_FIRMADAS.$nro_clave_acceso.".pdf", $pdf->output());
    }
    
    public function getpdfsriNC($nro_clave_acceso,$param=array())
    {

        if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.'.xml')) {
            $xml = simplexml_load_file(PATH_FIRMADAS.$nro_clave_acceso.'.xml');
            $clave_acceso = (string) $xml->infoTributaria->claveAcceso;
            $razonSocial = (string) $xml->infoTributaria->razonSocial;
            $dirMatriz = (string) $xml->infoTributaria->dirMatriz;
            $dirEstablecimiento = (string) $xml->infoNotaCredito->dirEstablecimiento;
            $contribuyenteEspecial = (string) $xml->infoNotaCredito->contribuyenteEspecial;
            $obligadoContabilidad = (string) $xml->infoNotaCredito->obligadoContabilidad;
            $ruc = (string) $xml->infoTributaria->ruc;
            $estab = (string) $xml->infoTributaria->estab;
            $ptoEmi = (string) $xml->infoTributaria->ptoEmi;
            $secuencial = (string) $xml->infoTributaria->secuencial;
            $ambiente = (string) $xml->infoTributaria->ambiente;
            $razonSocialComprador = (string) $xml->infoNotaCredito->razonSocialComprador;
            $fechaEmisionDocSustento = (string) $xml->infoNotaCredito->fechaEmisionDocSustento;
            $identificacionComprador = (string) $xml->infoNotaCredito->identificacionComprador;
            $fechaEmision = (string) $xml->infoNotaCredito->fechaEmision;
            $numDocModificado = (string) $xml->infoNotaCredito->numDocModificado;
            $motivo = (string) $xml->infoNotaCredito->motivo;
            $totalSinImpuestos = (string) $xml->infoNotaCredito->totalSinImpuestos;
            $valorIVA = (string) $xml->infoNotaCredito->totalConImpuestos->totalImpuesto->valor;
            $importeTotal = (string) $xml->infoNotaCredito->valorModificacion;
            $detalles = $xml->detalles->detalle;
            $totalConImpuestos = $xml->infoNotaCredito->totalConImpuestos;
            $infoAdicional = $xml->infoAdicional;
        } else {
            exit('No existe el XML.');
        }
        
        $generator = new \barcode_generator();
        $image = $generator->render_image('code-128', $clave_acceso, array(
            'ts'=>2,
            'th'=>12,
            'w'=>300,
            'h'=>70,
        ));
        $save = "public/images/".$clave_acceso.".jpg";
        imagejpeg($image, $save);
        //imagepng($image, $save);
        imagedestroy($image);
        
        //Consultar logo
        $empre = new Entidades\Sis00100($this->adapter,$param);
        $dtempre = $empre->getMulti("ruc", $ruc);
        
        $html ='<!DOCTYPE html>';
        $html.='<html>';
        $html.='<head>';
        $html.='<meta charset="utf-8">';
        $html.='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html.='<title>Facturacion Electronica</title>';
        $html.='<style type="text/css">';
        $html.='body {font-family: Delicious, sans-serif;font-size: 9px;}';
        $html.='.bordeLinea {float:left;width: 50%;}';
        $html.='.borde {border-radius: 20px 20px 20px 20px;-moz-border-radius: 20px 20px 20px 20px;-webkit-border-radius: 20px 20px 20px 20px;border: 1px solid #000000;}';
        $html.='.bordeCuadrado{border: 1px solid #000000;}';
        $html.='label {margin-left: 20px;padding-bottom: 30px;padding-top: 30px;}';
        $html.='.bordeTabla{border-collapse: collapse;text-align: center;}';
        $html.='.bordeTablaLeft{border-collapse: collapse;}';
        $html.='.bordeTabla tr td{border: 1px solid black;}';
        $html.='.bordeTablaLeft tr td{border: 1px solid black;}';
        $html.='.imgBarcode{width:300px;height:50px;margin-left:15px}';
        $html.='.imgLogo{width:200px;margin-left: 60px;  margin-top: 10px;max-height: 120px;}';
        $html.='.txtDerecha{text-align: right;}';
        $html.='.infoAdicional{margin-left:15px}';
        $html.='td {padding: 2px;}';
        $html.='#footer {padding-top:5px 0; border-top: 1px solid; width:100%; position: fixed; left: 0; bottom: 0;}
                #footer .fila td {text-align:left; width:100%;}
                #footer .fila td span {font-size: 10px; color: #334373;}';
        $html.='</style>';
        $html.='</head>';
        $html.='<body>';
        $html.='    <div>';
        $html.='        <div style="float:left;width: 48%;margin-right: 15px;">';
        $html.='        <div style="height: 130px">';
        $html.='            <img alt="SIN LOGO" class="imgLogo" src="'.PATH_IMG_PDF.$dtempre['logo'].'" /><br><br><br><br>';
        $html.='        </div>';
        $html.='        <div class="borde" style="height: 140px">';
        $html.='            <br><label>'.$razonSocial.'</label><br><br>';
        $html.='            <label>Dir Matriz: '.$dirMatriz.'</label><br><br>';
        $html.='            <label>Dir Sucursal: '.$dirEstablecimiento.'</label><br><br>';
        if (strlen($contribuyenteEspecial)>0) 
            $html.='            <label>Contribuyente Especial Nro '.$contribuyenteEspecial.'</label><br>';
        if (strlen($obligadoContabilidad)>0)
            $html.='            <label>OBLIGADO A LLEVAR CONTABILIDAD: '.$obligadoContabilidad.'</label><br><br>';
        $html.='        </div>';
        $html.='    </div>';
        $html.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
        $html.='        <br><b><label style="font-size:12px;">R.U.C.: '.$ruc.'</label></b><br><br>';
        $html.='        <b><label style="font-size:14px;">N O T A   D E   C R É D I T O</label></b><br><br>';
        $html.='        <label>No. '.$estab.'-'.$ptoEmi.'-'.$secuencial.'</label><br><br>';
        $html.='        <label>NÚMERO DE AUTORIZACIÓN:</label><br><br>';
        $html.='        <label style="font-size:8px;">'.$clave_acceso.'</label><br><br>';
        if ($ambiente == 1){
            $html.='        <label>AMBIENTE: PRUEBAS</label><br><br>';
        }
        else {
            $html.='        <label>AMBIENTE: PRODUCCION</label><br><br>';
        }
        $html.='        <label>EMISIÓN: NORMAL</label><br><br>';
        $html.='        <b><label>CLAVE DE ACCESO:</label></b><br>';
        $html.='        <img alt="ERROR" class="imgBarcode" src="'.PATH_IMG_PDF.'public/images/'.$clave_acceso.'.jpg" /><br><br><br><br>';
        $html.='    </div>';
        $html.='</div>';
        $html.='<div style="clear:both"></div>';
        $html.='<br>';
        $html.='<div class="bordeCuadrado">';
        $html.='    <table width="100%">';
        $html.='        <tr>';
        $html.='            <td><b>Razón Social / Nombres y Apellidos:</b> '.$razonSocialComprador.'</td>';
        $html.='            <td></td>';
        $html.='            <td><b>Identificación:</b> '.$identificacionComprador.'</td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td><b>Fecha Emisión:</b> '.$fechaEmision.'</td>';
        $html.='            <td></td>';
        $html.='            <td></td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td colspan="3"><hr></td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td>Comprobante que se modifica:</td>';
        $html.='            <td>FACTURA</td>';
        $html.='            <td>'.$numDocModificado.'</td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td>Fecha Emisión (Comprobante a modificar):</td>';
        $html.='            <td>'.$fechaEmisionDocSustento.'</td>';
        $html.='            <td></td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td>Razón de Modificación:</td>';
        $html.='            <td>'.$motivo.'</td>';
        $html.='            <td></td>';
        $html.='        </tr>';
        $html.='    </table>';
        $html.='</div>';
        $html.='<br>';
        $html.='<div>';
        $html.='    <table width="100%" class="bordeTabla">';
        $html.='        <tr>';
        $html.='            <td>Código</td>';
        $html.='            <td>Código Auxiliar</td>';
        $html.='            <td>Cantidad</td>';
        $html.='            <td>Descripción</td>';
        $html.='            <td>Descuento</td>';
        $html.='            <td>Precio Unitario</td>';
        $html.='            <td>Precio Total</td>';
        $html.='        </tr>';
        
        $totalDescuento = 0.00;
        if (isset($detalles->descripcion)) {
            $html.='        <tr>';
            $html.='            <td>'.(string) $detalles->codigoInterno.'</td>';
            $html.='            <td></td>';
            $html.='            <td>'.(string) $detalles->cantidad.'</td>';
            $html.='            <td>'.(string) $detalles->descripcion.'</td>';
            $html.='            <td>'.(string) $detalles->descuento.'</td>';
            $html.='            <td>'.(string) $detalles->precioUnitario.'</td>';
            $html.='            <td>'.(string) $detalles->precioTotalSinImpuesto.'</td>';
            $html.='        </tr>';
            $totalDescuento+=$detalles->descuento;
        }else{
            foreach ($detalles as $dtprod) {
                $html.='        <tr>';
                $html.='            <td>'.(string) $dtprod->codigoInterno.'</td>';
                $html.='            <td></td>';
                $html.='            <td>'.(string) $dtprod->cantidad.'</td>';
                $html.='            <td>'.(string) $dtprod->descripcion.'</td>';
                $html.='            <td>'.(string) $dtprod->descuento.'</td>';
                $html.='            <td>'.(string) $dtprod->precioUnitario.'</td>';
                $html.='            <td>'.(string) $dtprod->precioTotalSinImpuesto.'</td>';
                $html.='        </tr>';
                $totalDescuento+=$dtprod->descuento;
            }
        }

        $html.='    </table>';
        $html.='</div>';
        $html.='<br>';
        $html.='<div>';
        $html.='    <div style="float:left;width: 58%;margin-right: 15px;">';
        $html.='        <div class="bordeCuadrado">';
        $html.='            <br>  <b class="infoAdicional">Información Adicional</b><br><br>';
        $html.='            <table width="100%">';
        
        foreach ($infoAdicional->campoAdicional as $value) {
            $html.='                <tr>';
            $html.='                    <td>'.(string)$value['nombre'].': </td>';
            $html.='                    <td>'.(string)$value.'</td>';
            $html.='                </tr>';
        }
        
        $html.='            </table>';
        $html.='        </div>';
        $html.='        <br>';
        $html.='    </div>';
        
        //Valores totales
        $subtotal12 = 0.00;
        $subtotal0 = 0.00;
        $subtotalNoObj = 0.00;
        $subtotalExento = 0.00;
        $ivatotal = 0.00;
        
        foreach ($totalConImpuestos->totalImpuesto as $value) {
            if ($value->codigo == 2) {
                switch ($value->codigoPorcentaje) {
                    case 0:
                        $subtotal0 += (float) $value->baseImponible;
                        $ivatotal += (float) $value->valor;
                        break;
                    case 2:
                        $subtotal12 += (float) $value->baseImponible;
                        $ivatotal += (float) $value->valor;
                        break;
                    case 6:
                        $subtotalNoObj += (float) $value->baseImponible;
                        $ivatotal += (float) $value->valor;
                        break;
                    case 7:
                        $subtotalExento += (float) $value->baseImponible;
                        $ivatotal += (float) $value->valor;
                        break;
                }
            }
        }
        
        //$html.='    <div style="float:left;width: 38%;margin-left: 480px;">';
        $html.='    <div style="float:rigth;width: 38%;margin-left: 490px;">';
        //$html.='      <table width="80%" class="bordeTablaLeft">';
        $html.='        <table width="80%" class="bordeTablaLeft">';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL 12%</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$subtotal12, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL 0%</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$subtotal0, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL NO OBJETO IVA</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$subtotalNoObj, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL EXENTO IVA</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$subtotalExento, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL SIN IMPUESTOS</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$totalSinImpuestos, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>DESCUENTO</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$totalDescuento, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>ICE</td>';
        $html.='                <td class="txtDerecha">0.00</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>IVA 12%</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$ivatotal, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>IRBPNR</td>';
        $html.='                <td class="txtDerecha">0.00</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>VALOR TOTAL</td>';
        $html.='                <td class="txtDerecha">'.number_format((float)$importeTotal, 2, '.', '').'</td>';
        $html.='            </tr>';
        $html.='        </table>';
        $html.='    </div>';
        $html.='</div>';
        $html.='<div style="clear:both"></div>';
        $html.='<page_footer>
                    <table id="footer">
                        <tr class="fila">
                            <td>
                                <span>Comprobante Electrónico creado en www.portal.dtiware.com</span>
                            </td>
                        </tr>
                    </table>
                </page_footer>';
        $html.='</body>';
        $html.='</html>';
        // Instanciamos un objeto de la clase DOMPDF.
        $pdf = new \Dompdf\Dompdf();
        // Definimos el tamaño y orientación del papel que queremos.
        $pdf->set_paper("A4", "portrait");
        // Cargamos el contenido HTML.
        $pdf->load_html($html,'UTF-8');
        // Renderizamos el documento PDF.
        $pdf->render();
        // Enviamos el fichero PDF al navegador.
        //$pdf->stream('FicheroEjemplo.pdf'); //Asignamos Nombre Fijo
        //$pdf->stream(); //Nombre por defecto
        // Mostramos el fichero sin descargar en el navegador  (1 = download and 0 = preview)
        //$pdf->stream($pedido.".pdf", array("Attachment" => 1));
        if (file_exists(PATH_FIRMADAS.$nro_clave_acceso.".pdf"))
        {
            unlink(PATH_FIRMADAS.$nro_clave_acceso.".pdf");
        }
        //Guardar en el servidor el archivo
        file_put_contents(PATH_FIRMADAS.$nro_clave_acceso.".pdf", $pdf->output());
    }
    
    public function downloadXML($param=array())
    {
        if (isset($param)) {
            header("Content-disposition: attachment; filename=$param.xml");
            header("Content-type: text/xml");
            readfile(PATH_FIRMADAS."$param.xml");
        }
    }
    
    public function downloadPDF($param=array())
    {
        if (isset($param)) {
            header("Content-disposition: attachment; filename=$param.pdf");
            header("Content-type: application/pdf");
            readfile(PATH_FIRMADAS.$param.".pdf");
        }
    }
    
    public function downloadPDFNA($param=array())
    {
        if (isset($param)) {
            header("Content-disposition: attachment; filename=$param.pdf");
            header("Content-type: application/pdf");
            readfile(PATH_FILES.$param.".pdf");
        }
    }
    
    /**
     * MANEJO DE PRUEBAS
     */
    
    public function prueba4()
    {
        $this->getValidadRucFirma('public/fe/firmas/1891772234001.p12','1234','1891780792001');
    }
    
    public function prueba3()
    {
        $entidad = new Entidades\Sis00060($this->adapter);
        $idModulo = $entidad->getMulti('nombre', 'Empresa');

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
    }
    
    public function prueba2()
    {
        $this->getpdfsriF('2806201801180166780700120010020000000020000000219');
    }
    
    public function prueba()
    {
        //Corremos Script de Inicio
        $fichero = 'docs/scripts/script_empresa.sql';  // Ruta al fichero que vas a cargar.
        $conx = mysqli_connect(BD_HOST,BD_USER,BD_PASS, "dti_mlopez") or die(json_encode(array('status' => 'ERROR', 'descripcion' => 'Error de Conexion BD Usuario.')));
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
                mysqli_query($conx, $temp) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($conx))));
                // Limpiamos sentencia temporal
                $temp = '';
            }
        }
        die();
        $conx->close();
    }
    
    /*
     * MANEJA CREACION DE CAMPOS ADICIONALES
     */
    
    public function adicionalArticulos($param=array())
    {
        //Validar si no esta logueado.
        if (!isset($_SESSION["empresa"])) $this->redirect("default","selectempresa");
        if (!isset($_SESSION["usuario"])) $this->redirect("default","login");
        
        if (isset($param['id']))
        {
            $cuenta = new \Entidades\sis40130($this->adapter);
            $cuenta->deleteMulti('id', $param['id']);
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40120($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmNewFormulario'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmNewFormulario'),'orden');
            }
            $formulario =$formClientes->getForm();

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'seguridad/jsonNewCampo',
                'data'=>"{'tipo': tipo,'titulo':titulo,'placeholder':placeholder,'accionSql': accionSql}",
                'ok'=>'goTablePaginacion(1)'
            ));
            $datos_campo = $dti_ajax->getAjax();

            \dti_core::set("script", "<script type='text/javascript'>
                        function setJsonCampos(accionSql=''){
                            //Agregar Validaciones
                            Swal.fire({
                                title: 'Desea Agregar?',
                                text: 'Esta seguro que desea agregar la columna!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Agregar',
                                showLoaderOnConfirm: true,
                                preConfirm: function() {
                                    return new Promise(function(resolve) {
                                        var tipo,titulo,placeholder;
                                        tipo = document.getElementById('txtsis40131id').value;
                                        titulo = document.getElementById('txttitulo').value;
                                        placeholder = document.getElementById('txtplaceholder').value;

                                        if (titulo != '' && placeholder != '' && tipo > 0)
                                        {
                                            ".$datos_campo."
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

            //POner la tabla de las cuentas
            $tabla = new \dti_table();
            $tabla->setIdtable('tb_campos');
            $tabla->setTitulo('Lista de Campos Creados');
            $tabla->setColumnas('id-,titulo,tipo,placeholder');
                $tabla->setEtiquetas('id-,Titulo,Tipo,Placeholder');
            $tabla->setFiltro(true,'goTablePaginacion','seguridad','buscarDAinventario');

            //#################################################
            //Boton Regresar
            //#################################################
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'seguridad/index',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngroup->setGroupButtons(array(
                    'id'=>'saveCampo',
                    'swal'=>true,
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'clic'=>'setJsonCampos("Insert");',
                ));

            $btngrp = $btngroup->getGroupButtons();

            $contenedor = globalFunctions::renderizar($this->website,array(
                'section'=>array(
                    'manual_titulo'=>array(
                        'titulo'=>'Crear Campos para Datos Adicionales',
                        'layout'=>$btngrp['layout'].$formulario.$tabla->gettable('paginacion'),
                    ),
                )
            ),$this->login_empresa);

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Crear Datos Adicionales",
                'script'=>$btngrp['script'],
                'modal'=>$btngrp['modal'],
            ));
        }
    }
}
