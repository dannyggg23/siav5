<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class DtiwareController extends Controllers
{
    private $session,$conectar,$adapter,$layout,$website;
    
    public function __construct()
    {
        $this->session = new Session();
        $this->session->init();
        //Conexion a la base de datos
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        //Traemos los datos del portal configurados
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        //Cargamos el layout
        $this->layout = new dti_layout($this->website);
    }
    
    public function exec()
    {
        $this->login();
    }
    
    public function login($param=array())
    {
        //Borramos session
        $this->session->remove('usuario');
        $this->session->remove('empresa');
        $this->session->remove('bdcliente');
        $this->session->remove('establecimiento');
        $this->session->remove('rucEmpresa');
        $this->session->remove('bodegacc');
        $this->session->remove('DTI_SEGCUENTA');
        $this->session->remove('DTI_SEGSEPARADOR');
        $this->session->remove('DTI_DECIMALVEN');
        $this->session->remove('DTI_DECIMALCOM');
        $this->session->remove('DTI_CARACTERDECIMAL');
        $this->session->remove('DTI_CARACTERMILES');
        //Validar si ya esta logeado
        if (!empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","index");

        //Verificar si es un evento POST o GET;
        if (isset($param["usuario"]))
        {
            $usuario = $param["usuario"];
            $pass = sha1($param["pass"]);
            $model = new Models\Sis00020Model($this->adapter);
            $result = $model->getLogin($usuario, $pass);
            foreach ($result as $value) {
                $result = $value;
            }
            if ($result > 0) {
                //Variable de Session
                $this->session->add('usuarioCore', $usuario);
                echo 1;
            }else{
                echo globalFunctions::getMensaje('rojo', 'ERROR!', 'Usuario o Clave Erroneos, por favor vuelva a ingresar los datos', '1');
            }
        }
        else
        {
            $login = $this->layout->loginAction(array(
                'version'=>2,
                'controller'=>'dtiware',
                'accion'=>'login',
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$login,
                "titulo"=>"Ingresar al Sistema",
            ));
        }
    }
    
    public function logout()
    {
        $this->session->remove('usuarioCore');
        $this->redirect('dtiware','login');
    }
    
    public function index()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        $circle = new dti_circle();
        
        // Activar Permisos
        $seguridad = new Models\Sis20000Model($this->adapter);
        $datosseg = $seguridad->getPermisos($_SESSION["usuarioCore"]);
        
        if (globalFunctions::es_bidimensional($datosseg)) {
            foreach ($datosseg as $dato) {
                $circle->setCircle($dato["accion"], $dato["icono"], $dato["nombre"]);
            }
            $content = $circle->getCircle();
        }
        else if (isset($datosseg["id"])) {
            $circle->setCircle($datosseg["accion"], $datosseg["icono"], $datosseg["nombre"]);
            $content = $circle->getCircle();
        }
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual'=>$content,
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Bienvenido",
        ));
    }
    
    public function listportal()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        $datos = new Models\Sis00000Model($this->adapter);
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
            
            if(globalFunctions::es_bidimensional($datos)){
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table('paginacion');
                $tabla->setIdtable('tb_portal');
                $tabla->setTitulo('Lista de Portales');
                $tabla->setColumnas('nombre,description,telefono,website_url');
                $tabla->setEtiquetas('Nombre,Descripcion,Telefono,Url WebSite');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/portal',true,'mdleditPortal');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"])) {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_portal');
                $tabla->setTitulo('Lista de Portales');
                $tabla->setColumnas('nombre,description,telefono,website_url');
                $tabla->setEtiquetas('Nombre,Descripcion,Telefono,Url WebSite');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/portal',true,'mdleditPortal');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_portal');
                $tabla->setTitulo('Lista de Portales');
                $tabla->setColumnas('nombre,description,telefono,website_url');
                $tabla->setEtiquetas('Nombre,Descripcion,Telefono,Url WebSite');
                $tabla->setDatos(null);

                echo $tabla->gettable('Dpaginacion');
            }
        }
        else
        {
            if (isset($_POST['id']) || isset($_GET['id']))
            {
                if (ELIMINAR_JS == 0) {
                    try{
                            $delete = $datos->deleteById($_GET['id']);
                    } catch (Exception $ex) {
                            print_r($ex);
                    }
                    $this->redirect('dtiware','listportal');
                }else{
                    try{
                            $delete = $datos->deleteById($_POST['id']);
                            echo 'OK';
                    } catch (Exception $ex) {
                            echo $ex;
                    }
                }
            }
            else
            {
                $tabla = new \dti_table();
                $ds = $datos->getAll();
                $tabla->setIdtable('tb_portal');
                $tabla->setTitulo('Lista de Portales');
                $tabla->setColumnas('nombre,description,telefono,website_url');
                $tabla->setEtiquetas('Nombre,Descripcion,Telefono,Url WebSite');
                $tabla->setDatos($ds);
                $tabla->setEditar('dtiware/portal',true,'mdleditPortal');
                $tabla->setFiltro(true,'goTablePaginacion','dtiware','listportal');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'dtiware/index',
                        'enlace'=>true,
                        'icono'=>'fa fa-reply',
                        'titulo'=>'Regresar',
                        'btntitulo'=>'',
                        'btnmensaje'=>'',
                        'btn'=>array(),
                    ));

                //Llamamos a todos los script que vamos usar en los modals
                $dti_ajax = new dti_builder_ajax();
                $dti_ajax->setAjax(array(
                    'url'=>'dtiware/prueba',
                    'ok'=>'location.href ="dtiware/listportal"',
                ));

                dti_core::set('js', "<script type='text/javascript'>
                                function setJsonCliente(accionSql=''){
                                ".$dti_ajax->getAjax()."}</script>");
                
                $contenedor = globalFunctions::renderizar($this->website,array(
                    'core'=>true,
                    'section'=>array(
                        'manual'=>$btngroup->getGroupButtons()['layout'],
                        'layout_section'=>$tabla->gettable('paginacion'),
                    )
                ));

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Portales",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function portal($param=array())
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        if (isset($param['panel']))
        {
            //--Botones de Acciones--
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/listportal',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngroup->setGroupButtons(array(
                    'id'=>'savePortal',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Actualizar el Portal?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonCliente("Update");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));

            $btngrp = $btngroup->getGroupButtons();

            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortal'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortal'),'orden');
            }
            $formulario =$formClientes->getForm();

            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortalSMTP'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortalSMTP'),'orden');
            }
            $formulario2 = $formClientes->getForm();

            //--Acordion--
            $acordion = new dti_acordion();
            $acordion->setAcordion(array(
                'titulo'=>'Portal',
                'descripcion'=>$formulario,
                'siguiente'=>true
            ));
            $acordion->setAcordion(array(
                'titulo'=>'SMTP',
                'descripcion'=>$formulario2,
                'atras'=>true,
            ));
            $compo_acordion = $acordion->getAcordion();

            die(json_encode(array(
                'status' => 'OK', 
                'layout' => $btngrp['layout'].$compo_acordion, 
                'script'=>$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
        else
        {
            //--Botones de Acciones--
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/listportal',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngroup->setGroupButtons(array(
                    'id'=>'savePortal',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Actualizar el Portal?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonCliente("Update");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));

            $btngrp = $btngroup->getGroupButtons();

            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortal'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortal'),'orden');
            }
            $formulario =$formClientes->getForm();

            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortalSMTP'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortalSMTP'),'orden');
            }
            $formulario2 = $formClientes->getForm();

            //--Acordion--
            $acordion = new dti_acordion();
            $acordion->setAcordion(array(
                'titulo'=>'Portal',
                'descripcion'=>$formulario,
                'siguiente'=>true
            ));
            $acordion->setAcordion(array(
                'titulo'=>'SMTP',
                'descripcion'=>$formulario2,
                'atras'=>true,
            ));
            $compo_acordion = $acordion->getAcordion();

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'panel'=>$btngrp['layout'],
                    'manual_section'=>$compo_acordion,
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Bienvenido",
                'script'=>$btngrp['script'],
                'modal'=>$btngrp['modal'],
            ));
        }
    }
    
    public function builder()
    {
        if (isset($_POST["tabla"]))
        {
            try
            {
                sleep(1);
                $builder = new \dti_builder();
                $resultado = $builder->GetBuilder($_POST["tabla"],$_POST["controller"], $_POST["entidad"], $_POST["models"],$_POST["escribir"]);
                if ($resultado == 'OK')
                {
                    die(json_encode(array('status' => 'OK', 'descripcion' => 'Generado correctamente dentro de la carpeta temp.')));
                }
                else
                {
                    die(json_encode(array('status' => 'ERROR', 'descripcion' => $resultado)));
                }
            }
            catch (Exception $ex)
            {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => $ex)));
            }
        }
        else
        {
            //Validar si no esta logueado.
            if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
            
            //Variables Propias
            dti_core::set('css', '<link href="public/css/componentes/prism/prism.css" rel="stylesheet" type="text/css"/>');
            dti_core::set('js', '<script src="public/js/componentes/prism/prism.js" type="text/javascript"></script>');
            
            //--Botones de Acciones--
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/index',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngroup->setGroupButtons(array(
                    'id'=>'saveBuilder',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Generar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Crear Archivos?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonBuilder("Insert");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/builder',
                'data'=>"{'tabla': tabla,'controller':controller,'entidad':entidad,'models':models,'escribir':escribir,'accionSql': accionSql}",
                'ok'=>'location.href ="dtiware/builder"',
            ));
            
            \dti_core::set("script", "<script type='text/javascript'>
                    function setJsonBuilder(accionSql=''){
                        //Ocultar Modal de accion
                        $('#saveBuilder').modal('hide');
                        //Agregar Validaciones
                        var tabla,controller,entidad,models,escribir;
                        tabla = document.getElementById('txttabla').value;
                        controller = document.getElementById('txtcontroller').value;
                        entidad = document.getElementById('txtentidad').checked;
                        models = document.getElementById('txtmodels').checked;
                        escribir = document.getElementById('txtescribir').checked;
    
                        if (tabla != '' && controller != '')
                        {
                            ".$dti_ajax->getAjax()."
                        }
                        else{
                            result = '<div class=\'alert alert-dismissible alert-danger\'>';
                            result += '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
                            result += '<h4>Error!</h4>';
                            result += '<p><strong>Todos los campos son obligatorios.</strong></p>';
                            result += '</div>';
                            __('_AJAX_ERROR_').innerHTML = result;
                        }
                    }
                    </script>");
            
            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/builder',
                    'enlace'=>true,
                    'icono'=>'fa fa-book',
                    'titulo'=>'Borrar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngrp = $btngroup->getGroupButtons();

            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmCoreBuilder'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmCoreBuilder'),'orden');
            }
            $formulario =$formClientes->getForm();

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'panel'=>$btngrp['layout'],
                    'manual_titulo'=>array(
                        'titulo'=>'Crea Nuevo Builder',
                        'layout'=>$formulario,
                    ),
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Builder DtiCore",
                'script'=>$btngrp['script'],
                'modal'=>$btngrp['modal'],
            ));
        }
    }
    
    public function listperfiles()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        $datos = new Models\Sis00010Model($this->adapter);
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
            
            if(globalFunctions::es_bidimensional($datos)){
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table('paginacion');
                $tabla->setIdtable('tb_perfil');
                $tabla->setTitulo('Lista de Perfiles');
                $tabla->setColumnas('rol,activo');
                $tabla->setEtiquetas('Perfil,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/perfiles',true,'mdleditPerfil');
                $tabla->setNuevo('dtiware/perfiles',true,'mdlnewPerfil');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"])) {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_perfil');
                $tabla->setTitulo('Lista de Perfiles');
                $tabla->setColumnas('rol,activo');
                $tabla->setEtiquetas('Perfil,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/perfiles',true,'mdleditPerfil');
                $tabla->setNuevo('dtiware/perfiles',true,'mdlnewPerfil');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_perfil');
                $tabla->setTitulo('Lista de Perfiles');
                $tabla->setColumnas('rol,activo');
                $tabla->setEtiquetas('Perfil,Activo');
                $tabla->setDatos(null);

                echo $tabla->gettable('Dpaginacion');
            }
        }
        else
        {
            if (isset($_POST['id']) || isset($_GET['id']))
            {
                if (ELIMINAR_JS == 0) {
                    try{
                            $delete = $datos->deleteById($_GET['id']);
                    } catch (Exception $ex) {
                            print_r($ex);
                    }
                    $this->redirect('dtiware','listperfiles');
                }else{
                    try{
                            $delete = $datos->deleteById($_POST['id']);
                            echo 'OK';
                    } catch (Exception $ex) {
                            echo $ex;
                    }
                }
            }
            else
            {
                $tabla = new \dti_table();
                $ds = $datos->getAll();
                $tabla->setIdtable('tb_perfil');
                $tabla->setTitulo('Lista de Perfiles');
                $tabla->setColumnas('rol,activo');
                $tabla->setEtiquetas('Perfil,Activo');
                $tabla->setDatos($ds);
                $tabla->setEditar('dtiware/perfiles',true,'mdleditPerfil');
                $tabla->setNuevo('dtiware/perfiles',true,'mdlnewPerfil');
                $tabla->setFiltro(true,'goTablePaginacion','dtiware','listperfiles');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'dtiware/index',
                        'enlace'=>true,
                        'icono'=>'fa fa-reply',
                        'titulo'=>'Regresar',
                        'btntitulo'=>'',
                        'btnmensaje'=>'',
                        'btn'=>array(),
                    ));

                $contenedor = globalFunctions::renderizar($this->website,array(
                    'core'=>true,
                    'section'=>array(
                        'manual'=>$btngroup->getGroupButtons()['layout'],
                        'layout_section'=>$tabla->gettable('paginacion'),
                    )
                ));

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Perfiles",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function perfiles($param=array())
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        if (isset($param['panel']))
        {
            //--Botones de Acciones--
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/listperfiles',
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
                    'id'=>'savePerfil',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Actualizar el Perfil?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonPerfiles("Update");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));

                $btngroup->setGroupButtons(array(
                    'id'=>'savePermisos',
                    'modal'=>true,
                    'tipomodal'=>'search',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Permisos',
                    'btntitulo'=>'Asignar Permisos',
                    'btnmensaje'=>'dtiware/rolmodulos',
                    'json'=>array(
                        'antes'=>"var id = document.getElementById('txtid').value;",
                        'data'=>"{'search':search,'id':id,'page':page}",
                    ),
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Guardar el Perfil?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonPerfiles("Insert");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));
            }
            
            $btngrp = $btngroup->getGroupButtons();

            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePerfil'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePerfil'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            //Llamamos a todos los script que vamos usar en los modals
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/jsonperfiles',
                'data'=>"{'id':id,'rol':rol,'activo':activo,'accionSql':accionSql}",
                'ok'=>'location.href ="dtiware/listperfiles"',
            ));
            $ajax_jsonperfiles = $dti_ajax->getAjax();

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/jsonPermisos',
                'data'=>"{'modulo':modulo,'rol':rol,'page':page,'accionSql':'Insert'}",
                'ok'=>'goSearchsavePermisos(page)',
            ));
            $ajax_jsonperfilesadd = $dti_ajax->getAjax();
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/jsonPermisos',
                'data'=>"{'modulo':modulo,'rol':rol,'page':page,'accionSql':'Delete'}",
                'ok'=>'goSearchsavePermisos(page)',
            ));
            $ajax_jsonperfilesdel = $dti_ajax->getAjax();

            $script = "<script type='text/javascript'>
                            function setJsonPerfiles(accionSql=''){
                                //Ocultar Modal de accion
                                $('#savePerfil').modal('hide');
                                //Agregar Validaciones
                                var id,rol,activo;
                                id = document.getElementById('txtid').value;
                                rol = document.getElementById('txtrol').value;
                                activo = document.getElementById('txtactivo').checked;

                                if (rol != '')
                                {
                                    ".$ajax_jsonperfiles."
                                }
                                else
                                {
                                    result = '<div class=\'alert alert-dismissible alert-danger\'>';
                                    result += '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
                                    result += '<h4>Error!</h4>';
                                    result += '<p><strong>Todos los campos son obligatorios.</strong></p>';
                                    result += '</div>';
                                    __('_AJAX_ERROR_').innerHTML = result;
                                }
                            }
                            
                            $(document).ready(function(){
                                goSearchsavePermisos(1);
                            });

                            function goPermisosAdd(modulo,page,rol,accionSql='Insert'){
                                ".$ajax_jsonperfilesadd."
                            }
                            
                            function goPermisosDel(modulo,page,rol,accionSql='Delete'){
                                ".$ajax_jsonperfilesdel."
                            }
                            </script>";
            
            die(json_encode(array(
                'status' => 'OK', 
                'layout' => $btngrp['layout'].$formulario, 
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
        else
        {
            //--Botones de Acciones--
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/listportal',
                    'enlace'=>true,
                    'icono'=>'fa fa-reply',
                    'titulo'=>'Regresar',
                    'btntitulo'=>'',
                    'btnmensaje'=>'',
                    'btn'=>array(),
                ));

            $btngroup->setGroupButtons(array(
                    'id'=>'savePortal',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Actualizar el Portal?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonCliente("Update");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));

            $btngrp = $btngroup->getGroupButtons();

            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortal'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortal'),'orden');
            }
            $formulario =$formClientes->getForm();

            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (!empty($param)) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortalSMTP'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePortalSMTP'),'orden');
            }
            $formulario2 = $formClientes->getForm();

            //--Acordion--
            $acordion = new dti_acordion();
            $acordion->setAcordion(array(
                'titulo'=>'Portal',
                'descripcion'=>$formulario,
                'siguiente'=>true
            ));
            $acordion->setAcordion(array(
                'titulo'=>'SMTP',
                'descripcion'=>$formulario2,
                'atras'=>true,
            ));
            $compo_acordion = $acordion->getAcordion();

            $contenedor = globalFunctions::renderizar($this->website,array(
                'core'=>true,
                'section'=>array(
                    'panel'=>$btngrp['layout'],
                    'manual_section'=>$compo_acordion,
                )
            ));

            $this->render($this->website,__CLASS__,array(
                "layout"=>$contenedor,
                "titulo"=>"Perfiles",
                'script'=>$btngrp['script'],
                'modal'=>$btngrp['modal'],
            ));
        }
    }
    
    public function listusuarios()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        $datos = new Models\Sis00020Model($this->adapter);
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
            
            if(globalFunctions::es_bidimensional($datos)){
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table('paginacion');
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Usuarios');
                $tabla->setColumnas('nombre,apellido,correo,usuario,activo');
                $tabla->setEtiquetas('Nombre,Apellido,Correo,Usuario,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/usuarios',true,'mdleditUsuario');
                $tabla->setNuevo('dtiware/usuarios',true,'mdlnewUsuario');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"])) {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Usuarios');
                $tabla->setColumnas('nombre,apellido,correo,usuario,activo');
                $tabla->setEtiquetas('Nombre,Apellido,Correo,Usuario,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/usuarios',true,'mdleditUsuario');
                $tabla->setNuevo('dtiware/usuarios',true,'mdlnewUsuario');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Usuarios');
                $tabla->setColumnas('nombre,apellido,correo,usuario,activo');
                $tabla->setEtiquetas('Nombre,Apellido,Correo,Usuario,Activo');
                $tabla->setDatos(null);

                echo $tabla->gettable('Dpaginacion');
            }
        }
        else
        {
            if (isset($_POST['id']) || isset($_GET['id']))
            {
                if (ELIMINAR_JS == 0) {
                    try{
                            $delete = $datos->deleteById($_GET['id']);
                    } catch (Exception $ex) {
                            print_r($ex);
                    }
                    $this->redirect('dtiware','listusuarios');
                }else{
                    try{
                            $delete = $datos->deleteById($_POST['id']);
                            echo 'OK';
                    } catch (Exception $ex) {
                            echo $ex;
                    }
                }
            }
            else
            {
                $tabla = new \dti_table();
                $ds = $datos->getAll();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Usuarios');
                $tabla->setColumnas('nombre,apellido,correo,usuario,activo');
                $tabla->setEtiquetas('Nombre,Apellido,Correo,Usuario,Activo');
                $tabla->setDatos($ds);
                $tabla->setEditar('dtiware/usuarios',true,'mdleditUsuario');
                $tabla->setNuevo('dtiware/usuarios',true,'mdlnewUsuario');
                $tabla->setFiltro(true,'goTablePaginacion','dtiware','listusuarios');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'dtiware/index',
                        'enlace'=>true,
                        'icono'=>'fa fa-reply',
                        'titulo'=>'Regresar',
                        'btntitulo'=>'',
                        'btnmensaje'=>'',
                        'btn'=>array(),
                    ));

                $contenedor = globalFunctions::renderizar($this->website,array(
                    'core'=>true,
                    'section'=>array(
                        'manual'=>$btngroup->getGroupButtons()['layout'],
                        'layout_section'=>$tabla->gettable('paginacion'),
                    )
                ));

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Usuarios",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal']
                ));
            }
        }
    }
    
    public function usuarios($param=array())
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        if (isset($param['panel']))
        {
            //--Botones de Acciones--
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/listusuarios',
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
                    'id'=>'saveUsuario',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Actualizar el Usuario?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonUsuarios("Update");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'saveUsuario',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Guardar el Usuario?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonUsuarios("Insert");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));
            }
            

            $btngrp = $btngroup->getGroupButtons();

            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCoreUsuario'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCoreUsuario'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            //Llamamos a todos los script que vamos usar en los modals
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/jsonusuarios',
                'data'=>"{'id':id,'nombre':nombre,'apellido':apellido,'correo':correo,'usuario':usuario,'pass':pass,'rolid':rolid,'activo':activo,'accionSql':accionSql}",
                'ok'=>'location.href ="dtiware/listusuarios"',
            ));

            $script = "<script type='text/javascript'>
                            function setJsonUsuarios(accionSql=''){
                                //Ocultar Modal de accion
                                $('#saveUsuario').modal('hide');
                                //Agregar Validaciones
                                var id,nombre,apellido,correo,usuario,pass,activo,rolid;
                                id = document.getElementById('txtid').value;
                                nombre = document.getElementById('txtnombre').value;
                                apellido = document.getElementById('txtapellido').value;
                                correo = document.getElementById('txtcorreo').value;
                                usuario = document.getElementById('txtusuario').value;
                                pass = document.getElementById('txtpass').value;
                                rolid = document.getElementById('txtrolid').value;
                                activo = document.getElementById('txtactivo').checked;

                                if (nombre != '' && apellido != '' && correo != '' && usuario != '' && pass != '' && rolid > 0)
                                {
                                    ".$dti_ajax->getAjax()."
                                }
                                else
                                {
                                    result = '<div class=\'alert alert-dismissible alert-danger\'>';
                                    result += '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
                                    result += '<h4>Error!</h4>';
                                    result += '<p><strong>Todos los campos son obligatorios.</strong></p>';
                                    result += '</div>';
                                    __('_AJAX_ERROR_').innerHTML = result;
                                }
                            }</script>";
            
            die(json_encode(array(
                'status' => 'OK', 
                'layout' => $btngrp['layout'].$formulario, 
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
            
        }
        else
        {
            echo 'No tiene permisos para acceder.';
        }
    }
    
    public function rolmodulos()
    {
        //Limpiar la Variable
        $q = $_POST['search'];
        $id = $_POST['id'];
        $codigos = new Models\Sis00030Model($this->adapter);
        $numrows = $codigos->getCount();
        //Muchos Datos
        //Paginacion
        //las variables de paginación
        $page = (isset($_POST['page']) && !empty($_POST['page']))?$_POST['page']:1;
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Consultar Inventario
        $datos = $codigos->getPermisos($id,$q,$offset,$per_page);
        //$datos = $codigos->getPermisos();        
        if(globalFunctions::es_bidimensional($datos)){
            $total_pages = ceil($numrows["numrows"]/$per_page);
            $reload = 'dticore/perfiles/'.$id;
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Activo</th>
                        <th class='text-center' style="width: 36px;">Agregar</th>
                    </tr>
                    <?php
                    foreach($datos as $dato) {
                        ?>
                        <tr>
                            <td><?php echo $dato["nombre"]; ?></td>
                            <td><?php echo $dato["descripcion"]; ?></td>
                            <td><?php echo $dato["activo"]; ?></td>
                            <?php
                            if ($dato["temporal"]==1) {
                                ?>
                                <td class='text-center'><button type="button" class="btn btn-danger" onclick="goPermisosDel('<?php echo $dato["id"]; ?>',<?php echo $page; ?>,<?php echo $id; ?>)"><i class="fa fa-minus"></i></button></td>
                                <?php
                            }else{
                                ?>
                                <td class='text-center'><button type="button" class="btn btn-success" onclick="goPermisosAdd('<?php echo $dato["id"]; ?>',<?php echo $page; ?>,<?php echo $id; ?>)"><i class="fa fa-plus"></i></button></td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php if (count($datos) >= $per_page || strlen($q) == 0) { ?>
                        <div class="table-pagination pull-right">
                            <?php echo globalFunctions::paginate($page, $total_pages, $adjacents, 'goSearchsavePermisos'); //'goSearchsavePermisos' nombre con el que se ejecuta la funcion ?>
                        </div>
                <?php } ?>
            </div>
            <?php
        }
        else if (isset($datos["id"])) {
            $total_pages = ceil($numrows["numrows"]/$per_page);
            $reload = 'dticore/perfiles/'.$id;
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Activo</th>
                        <th class='text-center' style="width: 36px;">Agregar</th>
                    </tr>
                    <tr>
                        <td><?php echo $datos["nombre"]; ?></td>
                        <td><?php echo $datos["descripcion"]; ?></td>
                        <td><?php echo $datos["activo"]; ?></td>
                        <?php
                        if ($datos["temporal"]==1) {
                            ?>
                            <td class='text-center'><button type="button" class="btn btn-danger" onclick="goPermisosDel('<?php echo $datos["id"]; ?>',<?php echo $page; ?>,<?php echo $id; ?>)"><i class="fa fa-minus"></i></button></td>
                            <?php
                        }else{
                            ?>
                            <td class='text-center'><button type="button" class="btn btn-success" onclick="goPermisosAdd('<?php echo $datos["id"]; ?>',<?php echo $page; ?>,<?php echo $id; ?>)"><i class="fa fa-plus"></i></button></td>
                            <?php
                        }
                        ?>
                    </tr>
                </table>
            </div>
            <?php
        }
        else {
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Activo</th>
                        <th class='text-center' style="width: 36px;">Agregar</th>
                    </tr>
              </table>
            </div>
            <?php
        }
    }
    
    public function listperfilesexternos()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");

        $datos = new Models\Sis00040Model($this->adapter);
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
            
            if(globalFunctions::es_bidimensional($datos)){
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table('paginacion');
                $tabla->setIdtable('tb_perfil');
                $tabla->setTitulo('Lista de Perfiles');
                $tabla->setColumnas('rol,activo');
                $tabla->setEtiquetas('Perfil,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/perfilesexternos',true,'mdleditPerfil');
                $tabla->setNuevo('dtiware/perfilesexternos',true,'mdlnewPerfil');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"])) {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_perfil');
                $tabla->setTitulo('Lista de Perfiles');
                $tabla->setColumnas('rol,activo');
                $tabla->setEtiquetas('Perfil,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/perfilesexternos',true,'mdleditPerfil');
                $tabla->setNuevo('dtiware/perfilesexternos',true,'mdlnewPerfil');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_perfil');
                $tabla->setTitulo('Lista de Perfiles');
                $tabla->setColumnas('rol,activo');
                $tabla->setEtiquetas('Perfil,Activo');
                $tabla->setDatos(null);

                echo $tabla->gettable('Dpaginacion');
            }
        }
        else
        {
            if (isset($_POST['id']) || isset($_GET['id']))
            {
                if (ELIMINAR_JS == 0) {
                    try{
                            $delete = $datos->deleteById($_GET['id']);
                    } catch (Exception $ex) {
                            print_r($ex);
                    }
                    $this->redirect('dtiware','listperfilesexternos');
                }else{
                    try{
                            $delete = $datos->deleteById($_POST['id']);
                            echo 'OK';
                    } catch (Exception $ex) {
                            echo $ex;
                    }
                }
            }
            else
            {
                $tabla = new \dti_table();
                $ds = $datos->getAll();
                $tabla->setIdtable('tb_perfil');
                $tabla->setTitulo('Lista de Perfiles');
                $tabla->setColumnas('rol,activo');
                $tabla->setEtiquetas('Perfil,Activo');
                $tabla->setDatos($ds);
                $tabla->setEditar('dtiware/perfilesexternos',true,'mdleditPerfil');
                $tabla->setNuevo('dtiware/perfilesexternos',true,'mdlnewPerfil');
                $tabla->setFiltro(true,'goTablePaginacion','dtiware','listperfilesexternos');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'dtiware/index',
                        'enlace'=>true,
                        'icono'=>'fa fa-reply',
                        'titulo'=>'Regresar',
                        'btntitulo'=>'',
                        'btnmensaje'=>'',
                        'btn'=>array(),
                    ));

                $contenedor = globalFunctions::renderizar($this->website,array(
                    'core'=>true,
                    'section'=>array(
                        'manual'=>$btngroup->getGroupButtons()['layout'],
                        'layout_section'=>$tabla->gettable('paginacion'),
                    )
                ));

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Perfiles",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal'],
                ));
            }
        }
    }
    
    public function perfilesexternos($param=array())
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        if (isset($param['panel']))
        {
            //--Botones de Acciones--
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/listperfilesexternos',
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
                    'id'=>'savePerfil',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Actualizar el Perfil?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonPerfilesexternos("Update");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));

                $btngroup->setGroupButtons(array(
                    'id'=>'savePermisos',
                    'modal'=>true,
                    'tipomodal'=>'search',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Permisos',
                    'btntitulo'=>'Asignar Permisos',
                    'btnmensaje'=>'dtiware/rolmodulosexternos',
                    'json'=>array(
                        'antes'=>"var id = document.getElementById('txtid').value;",
                        'data'=>"{'search':search,'id':id,'page':page}",
                    ),
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'savePerfil',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Guardar el Perfil?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonPerfilesexternos("Insert");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));
            }
            
            $btngrp = $btngroup->getGroupButtons();

            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePerfilExt'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getBy('formulario', 'frmCorePerfilExt'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            //Llamamos a todos los script que vamos usar en los modals
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/jsonperfilesexternos',
                'data'=>"{'id':id,'rol':rol,'activo':activo,'accionSql':accionSql}",
                'ok'=>'location.href ="dtiware/listperfilesexternos"',
            ));
            $ajax_jsonperfiles = $dti_ajax->getAjax();

            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/jsonPermisosexternos',
                'data'=>"{'modulo':modulo,'rol':rol,'page':page,'accionSql':'Insert'}",
                'ok'=>'goSearchsavePermisos(page)',
            ));
            $ajax_jsonperfilesadd = $dti_ajax->getAjax();
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/jsonPermisosexternos',
                'data'=>"{'modulo':modulo,'rol':rol,'page':page,'accionSql':'Delete'}",
                'ok'=>'goSearchsavePermisos(page)',
            ));
            $ajax_jsonperfilesdel = $dti_ajax->getAjax();

            $script = "<script type='text/javascript'>
                            function setJsonPerfilesexternos(accionSql=''){
                                //Ocultar Modal de accion
                                $('#savePerfil').modal('hide');
                                //Agregar Validaciones
                                var id,rol,activo;
                                id = document.getElementById('txtid').value;
                                rol = document.getElementById('txtrol').value;
                                activo = document.getElementById('txtactivo').checked;

                                if (rol != '')
                                {
                                    ".$ajax_jsonperfiles."
                                }
                                else
                                {
                                    result = '<div class=\'alert alert-dismissible alert-danger\'>';
                                    result += '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
                                    result += '<h4>Error!</h4>';
                                    result += '<p><strong>Todos los campos son obligatorios.</strong></p>';
                                    result += '</div>';
                                    __('_AJAX_ERROR_').innerHTML = result;
                                }
                            }
                            
                            $(document).ready(function(){
                                goSearchsavePermisos(1);
                            });

                            function goPermisosAdd(modulo,page,rol,accionSql='Insert'){
                                ".$ajax_jsonperfilesadd."
                            }
                            
                            function goPermisosDel(modulo,page,rol,accionSql='Delete'){
                                ".$ajax_jsonperfilesdel."
                            }
                            </script>";
            
            die(json_encode(array(
                'status' => 'OK', 
                'layout' => $btngrp['layout'].$formulario, 
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
        }
        else
        {
            echo 'No tienes permisos para acceder.';
        }
    }
    
    public function rolmodulosexternos()
    {
        //Limpiar la Variable
        $q = $_POST['search'];
        $id = $_POST['id'];
        $codigos = new Models\Sis00060Model($this->adapter);
        $numrows = $codigos->getCount();
        //Muchos Datos
        //Paginacion
        //las variables de paginación
        $page = (isset($_POST['page']) && !empty($_POST['page']))?$_POST['page']:1;
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Consultar Inventario
        $datos = $codigos->getPermisos($id,$q,$offset,$per_page);
        if(globalFunctions::es_bidimensional($datos)){
            $total_pages = ceil($numrows["numrows"]/$per_page);
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Activo</th>
                        <th class='text-center' style="width: 36px;">Agregar</th>
                    </tr>
                    <?php
                    foreach($datos as $dato) {
                        ?>
                        <tr>
                            <td><?php echo $dato["nombre"]; ?></td>
                            <td><?php echo $dato["descripcion"]; ?></td>
                            <td><?php echo $dato["activo"]; ?></td>
                            <?php
                            if ($dato["temporal"]==1) {
                                ?>
                                <td class='text-center'><button type="button" class="btn btn-danger" onclick="goPermisosDel('<?php echo $dato["id"]; ?>',<?php echo $page; ?>,<?php echo $id; ?>)"><i class="fa fa-minus"></i></button></td>
                                <?php
                            }else{
                                ?>
                                <td class='text-center'><button type="button" class="btn btn-success" onclick="goPermisosAdd('<?php echo $dato["id"]; ?>',<?php echo $page; ?>,<?php echo $id; ?>)"><i class="fa fa-plus"></i></button></td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php if (count($datos) >= $per_page || strlen($q) == 0) { ?>
                        <div class="table-pagination pull-right">
                            <?php echo globalFunctions::paginate($page, $total_pages, $adjacents, 'goSearchsavePermisos'); //'goSearchsavePermisos' nombre con el que se ejecuta la funcion ?>
                        </div>
                <?php } ?>
            </div>
            <?php
        }
        else if (isset($datos["id"])) {
            $total_pages = ceil($numrows["numrows"]/$per_page);
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Activo</th>
                        <th class='text-center' style="width: 36px;">Agregar</th>
                    </tr>
                    <tr>
                        <td><?php echo $datos["nombre"]; ?></td>
                        <td><?php echo $datos["descripcion"]; ?></td>
                        <td><?php echo $datos["activo"]; ?></td>
                        <?php
                        if ($datos["temporal"]==1) {
                            ?>
                            <td class='text-center'><button type="button" class="btn btn-danger" onclick="goPermisosDel('<?php echo $datos["id"]; ?>',<?php echo $page; ?>,<?php echo $id; ?>)"><i class="fa fa-minus"></i></button></td>
                            <?php
                        }else{
                            ?>
                            <td class='text-center'><button type="button" class="btn btn-success" onclick="goPermisosAdd('<?php echo $datos["id"]; ?>',<?php echo $page; ?>,<?php echo $id; ?>)"><i class="fa fa-plus"></i></button></td>
                            <?php
                        }
                        ?>
                    </tr>
                </table>
            </div>
            <?php
        }
        else {
            ?>
            <div class="table-responsive">
              <table class="table">
                    <tr  class="warning">
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Activo</th>
                        <th class='text-center' style="width: 36px;">Agregar</th>
                    </tr>
              </table>
            </div>
            <?php
        }
    }
    
    public function listusuariosexternos()
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        $datos = new Models\Sis00050Model($this->adapter);
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
            
            if(globalFunctions::es_bidimensional($datos)){
                $total_pages = ceil($numrows["numrows"]/$per_page);
                
                $tabla = new \dti_table('paginacion');
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Clientes');
                $tabla->setColumnas('nombre,apellido,correo,usuario,activo');
                $tabla->setEtiquetas('Nombre,Apellido,Correo,Usuario,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/usuariosexternos',true,'mdleditUsuario');
                $tabla->setNuevo('dtiware/usuariosexternos',true,'mdlnewUsuario');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion( $page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else if (isset($datos["id"])) {
                $total_pages = ceil($numrows["numrows"]/$per_page);

                $tabla = new \dti_table();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Clientes');
                $tabla->setColumnas('nombre,apellido,correo,usuario,activo');
                $tabla->setEtiquetas('Nombre,Apellido,Correo,Usuario,Activo');
                $tabla->setDatos($datos);
                $tabla->setEditar('dtiware/usuariosexternos',true,'mdleditUsuario');
                $tabla->setNuevo('dtiware/usuariosexternos',true,'mdlnewUsuario');
                //$tabla->setEliminar('mies/listmies_comunidades');
                $tabla->setPaginacion($page, $total_pages, $adjacents,'goTablePaginacion');

                echo $tabla->gettable('Dpaginacion');
            }
            else {
                $tabla = new \dti_table();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Clientes');
                $tabla->setColumnas('nombre,apellido,correo,usuario,activo');
                $tabla->setEtiquetas('Nombre,Apellido,Correo,Usuario,Activo');
                $tabla->setDatos(null);

                echo $tabla->gettable('Dpaginacion');
            }
        }
        else
        {
            if (isset($_POST['id']) || isset($_GET['id']))
            {
                if (ELIMINAR_JS == 0) {
                    try{
                            $delete = $datos->deleteById($_GET['id']);
                    } catch (Exception $ex) {
                            print_r($ex);
                    }
                    $this->redirect('dtiware','listusuariosexternos');
                }else{
                    try{
                            $delete = $datos->deleteById($_POST['id']);
                            echo 'OK';
                    } catch (Exception $ex) {
                            echo $ex;
                    }
                }
            }
            else
            {
                $tabla = new \dti_table();
                $ds = $datos->getAll();
                $tabla->setIdtable('tb_usuarios');
                $tabla->setTitulo('Lista de Clientes');
                $tabla->setColumnas('nombre,apellido,correo,usuario,activo');
                $tabla->setEtiquetas('Nombre,Apellido,Correo,Usuario,Activo');
                $tabla->setDatos($ds);
                $tabla->setEditar('dtiware/usuariosexternos',true,'mdleditUsuario');
                $tabla->setNuevo('dtiware/usuariosexternos',true,'mdlnewUsuario');
                $tabla->setFiltro(true,'goTablePaginacion','dtiware','listusuariosexternos');
                
                //#################################################
                //Boton Regresar
                //#################################################
                $btngroup = new dti_builder_buttons();

                $btngroup->setGroupButtons(array(
                        'clic'=>'dtiware/index',
                        'enlace'=>true,
                        'icono'=>'fa fa-reply',
                        'titulo'=>'Regresar',
                        'btntitulo'=>'',
                        'btnmensaje'=>'',
                        'btn'=>array(),
                    ));

                $contenedor = globalFunctions::renderizar($this->website,array(
                    'core'=>true,
                    'section'=>array(
                        'manual'=>$btngroup->getGroupButtons()['layout'],
                        'layout_section'=>$tabla->gettable('paginacion'),
                    )
                ));

                $this->render($this->website,__CLASS__,array(
                    "layout"=>$contenedor,
                    "titulo"=>"Lista de Clientes",
                    'script'=>$btngroup->getGroupButtons()['script'],
                    'modal'=>$btngroup->getGroupButtons()['modal']
                ));
            }
        }
    }
    
    public function usuariosexternos($param=array())
    {
        //Validar si no esta logueado.
        if (empty($this->session->get('usuarioCore'))) $this->redirect("dtiware","login");
        
        if (isset($param['panel']))
        {
            //--Botones de Acciones--
            $btngroup = new dti_builder_buttons();

            $btngroup->setGroupButtons(array(
                    'clic'=>'dtiware/listusuariosexternos',
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
                    'id'=>'saveUsuario',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Actualizar el Usuario?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonUsuariosexternos("Update");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));
            }
            else
            {
                $btngroup->setGroupButtons(array(
                    'id'=>'saveUsuario',
                    'icono'=>'fa fa-floppy-o',
                    'titulo'=>'Guardar',
                    'btntitulo'=>'Confirmar',
                    'btnmensaje'=>'Desea Guardar el Usuario?',
                    'btn'=>array(['titulo'=>'SI','accion'=>'setJsonUsuariosexternos("Insert");'],
                                ['titulo'=>'NO','accion'=>'close']),
                ));
            }
            

            $btngrp = $btngroup->getGroupButtons();

            //--Formularios--
            $formClientes = new dti_builder_form($this->adapter);
            $maestro = new Entidades\Sis40020($this->adapter);
            if (isset($param['edit'])) {
                $formClientes->setForm($maestro->getMulti('formulario', 'frmCoreUsuarioExt'),'orden',$param);
            }else{
                $formClientes->setForm($maestro->getMulti('formulario', 'frmCoreUsuarioExt'),'orden');
            }
            $formulario =$formClientes->getForm();
            
            //Llamamos a todos los script que vamos usar en los modals
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'dtiware/jsonusuariosexternos',
                'data'=>"{'id':id,'nombre':nombre,'apellido':apellido,'correo':correo,'usuario':usuario,'pass':pass,'activo':activo,'accionSql':accionSql}",
                'ok'=>'location.href ="dtiware/listusuariosexternos"',
            ));

            $script = "<script type='text/javascript'>
                            function setJsonUsuariosexternos(accionSql=''){
                                //Ocultar Modal de accion
                                $('#saveUsuario').modal('hide');
                                //Agregar Validaciones
                                var id,nombre,apellido,correo,usuario,pass,activo,rolid;
                                id = document.getElementById('txtid').value;
                                nombre = document.getElementById('txtnombre').value;
                                apellido = document.getElementById('txtapellido').value;
                                correo = document.getElementById('txtcorreo').value;
                                usuario = document.getElementById('txtusuario').value;
                                pass = document.getElementById('txtpass').value;
                                activo = document.getElementById('txtactivo').checked;

                                if (nombre != '' && apellido != '' && correo != '' && usuario != '' && pass != '')
                                {
                                    ".$dti_ajax->getAjax()."
                                }
                                else
                                {
                                    result = '<div class=\'alert alert-dismissible alert-danger\'>';
                                    result += '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
                                    result += '<h4>Error!</h4>';
                                    result += '<p><strong>Todos los campos son obligatorios.</strong></p>';
                                    result += '</div>';
                                    __('_AJAX_ERROR_').innerHTML = result;
                                }
                            }</script>";
            
            die(json_encode(array(
                'status' => 'OK', 
                'layout' => $btngrp['layout'].$formulario, 
                'script'=>$script.$btngrp['script'],
                'modal'=>$btngrp['modal'],
            )));
            
        }
        else
        {
            echo 'No tiene permisos para acceder.';
        }
    }
    
    public function listInventario($param=array()) {
        if (!isset($_SESSION["usuarioCore"])) { $this->redirect("dtiware","login"); }

        //Btn Agrupados
        $btngroup = new dti_builder_buttons();

        if (isset($param['url'])) {
            $clic = $param['url'];
        } else {
            $clic = 'dtiware/index';
        }

        $btngroup->setGroupButtons(array(
            'clic'=>$clic,
            'enlace'=>true,
            'icono'=>'fa fa-reply',
            'titulo'=>'Regresar',
            'btntitulo'=>'',
            'btnmensaje'=>'',
            'btn'=>array(),
        ));

        $btngroup->setGroupButtons(array(
            'id'=>'formatoInventario',
            'enlace'=>true,
            'icono'=>'fa fa-file-o',
            'titulo'=>'Formato Inventarios',
            'clic'=>'public/formatos/FormatoInventarios.xlsx',
            'btn'=>array(),
        ));
        
        $btngroup->setGroupButtons(array(
            'id'=>'savePerfil',
            'swal'=>true,
            'icono'=>'fa fa-floppy-o',
            'titulo'=>'Cargar',
            'clic'=>'setJsonInventario("Insert");',
        ));

        //Agrupamos los botones
        $btngrp = $btngroup->getGroupButtons();

        $formClientes = new dti_builder_form($this->adapter);

        $formulario = $formClientes->createFile(array(
            'nameid'=>'excelInventario',
            'titulo'=>'Cargar Excel con el inventario',
            'tipo'=>'file',
        ));

        $selectPeriodo = $formClientes->createSelect(array(
            'titulo'=>'',
            'nameid'=>'cmbtipo',
            'combobox'=>'sis00060',
            'controller'=>'dtiware',
            'simple'=>true
        ));
        
        $script = "<script type='text/javascript'>
                function setJsonInventario(accionSql=''){
                    //Agregar Validaciones
                    Swal.fire({
                        title: 'Desea Cargar Inventario?',
                        text: 'Esta seguro que desea cargar el Inventario!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Cargar',
                        showLoaderOnConfirm: true,
                        preConfirm: function() {
                            return new Promise(function(resolve) {
                                var logo,cmbtipo;
                                cmbtipo = document.getElementById('cmbtipo').value;
                                var imagen_data = new FormData();
                                imagen_data.append('inventario',$('#excelInventario')[0].files[0]);
                                imagen_data.append('tipo',cmbtipo);
                                $.ajax({
                                    url: 'dtiware/jsonExcelInventario',
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
                                            Swal.fire('Correcto!', 'Precios Actualizados!', 'success');
                                            location.href = '".$clic."';
                                        }
                                    }
                                  });
                            })
                        },
                        allowOutsideClick: false
                    });
                }</script>";

        $notifi = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                            <h2>SUGERENCIAS</h2>
                            <b>*-* De subirse el formato que se proporsiona caso contrario el sistema no lo procesará.</b><br>
                    </div>';
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual'=>$notifi.$btngrp['layout'],
                'layout_section'=>$selectPeriodo.$formulario,
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Importar Articulos",
            'script'=>$script.$btngrp['script'],
            'modal'=>$btngrp['modal'],
        ));
    }
    
    public function listProveedor($param=array()) {
        if (!isset($_SESSION["usuarioCore"])) { $this->redirect("dtiware","login"); }

        //Btn Agrupados
        $btngroup = new dti_builder_buttons();

        if (isset($param['url'])) {
            $clic = $param['url'];
        } else {
            $clic = 'dtiware/index';
        }

        $btngroup->setGroupButtons(array(
            'clic'=>$clic,
            'enlace'=>true,
            'icono'=>'fa fa-reply',
            'titulo'=>'Regresar',
            'btntitulo'=>'',
            'btnmensaje'=>'',
            'btn'=>array(),
        ));

        $btngroup->setGroupButtons(array(
            'id'=>'formatoInventario',
            'enlace'=>true,
            'icono'=>'fa fa-file-o',
            'titulo'=>'Formato Proveedores',
            'clic'=>'public/formatos/FormatoProveedores.xlsx',
            'btn'=>array(),
        ));
        
        $btngroup->setGroupButtons(array(
            'id'=>'savePerfil',
            'swal'=>true,
            'icono'=>'fa fa-floppy-o',
            'titulo'=>'Cargar',
            'clic'=>'setJsonProveedores("Insert");',
        ));

        //Agrupamos los botones
        $btngrp = $btngroup->getGroupButtons();

        $formClientes = new dti_builder_form($this->adapter);

        $formulario = $formClientes->createFile(array(
            'nameid'=>'excelProveedor',
            'titulo'=>'Cargar Excel con los proveedores',
            'tipo'=>'file',
        ));

        $selectPeriodo = $formClientes->createSelect(array(
            'titulo'=>'',
            'nameid'=>'cmbtipo',
            'combobox'=>'sis00060',
            'controller'=>'dtiware',
            'simple'=>true
        ));
        
        $script = "<script type='text/javascript'>
                function setJsonProveedores(accionSql=''){
                    //Agregar Validaciones
                    Swal.fire({
                        title: 'Desea Cargar Proveedor?',
                        text: 'Esta seguro que desea cargar el proveedor!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Cargar',
                        showLoaderOnConfirm: true,
                        preConfirm: function() {
                            return new Promise(function(resolve) {
                                var logo,cmbtipo;
                                cmbtipo = document.getElementById('cmbtipo').value;
                                var imagen_data = new FormData();
                                imagen_data.append('proveedor',$('#excelProveedor')[0].files[0]);
                                imagen_data.append('tipo',cmbtipo);
                                $.ajax({
                                    url: 'dtiware/jsonExcelProveedor',
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
                                            Swal.fire('Correcto!', 'Precios Actualizados!', 'success');
                                            location.href = '".$clic."';
                                        }
                                    }
                                  });
                            })
                        },
                        allowOutsideClick: false
                    });
                }</script>";

        $notifi = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                            <h2>SUGERENCIAS</h2>
                            <b>*-* De subirse el formato que se proporsiona caso contrario el sistema no lo procesará.</b><br>
                    </div>';
        
        $contenedor = globalFunctions::renderizar($this->website,array(
            'core'=>true,
            'section'=>array(
                'manual'=>$notifi.$btngrp['layout'],
                'layout_section'=>$selectPeriodo.$formulario,
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Importar Proveedores",
            'script'=>$script.$btngrp['script'],
            'modal'=>$btngrp['modal'],
        ));
    }
    
    /**
     * MANEJO DE JSON
     */
    
    public function jsonPerfiles($param=array())
    {
        //Insertar
        if(isset($param['rol']) && $param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00010($this->adapter);
            $entidad->setRol($param['rol']);
            $entidad->setActivo($param['activo'] === 'true'?1:0);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if(isset($param['id']) && $param['accionSql'] == 'Update') 
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00010($this->adapter);
            $entidad->updateMultiColum('rol', $param['rol'],'id', $param['id']);
            $entidad->updateMultiColum('activo', $param['activo'] === 'true'?1:0,'id', $param['id']);
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if(isset($param['id']) && $param['accionSql'] == 'Delete')
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
    
    public function jsonUsuarios($param=array())
    {
        //Insertar
        if(isset($param['nombre']) && $param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00020($this->adapter);
            $entidad->setNombre($param['nombre']);
            $entidad->setApellido($param['apellido']);
            $entidad->setCorreo($param['correo']);
            $entidad->setUsuario($param['usuario']);
            $entidad->setPass($param['pass']);
            $entidad->setRolid($param['rolid']);
            $entidad->setActivo($param['activo'] === 'true'?1:0);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if(isset($param['id']) && $param['accionSql'] == 'Update') 
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00020($this->adapter);
            $entidad->updateMultiColum('nombre', $param['nombre'],'id', $param['id']);
            $entidad->updateMultiColum('apellido', $param['apellido'],'id', $param['id']);
            $entidad->updateMultiColum('correo', $param['correo'],'id', $param['id']);
            $entidad->updateMultiColum('usuario', $param['usuario'],'id', $param['id']);
            $entidad->updateMultiColum('pass', $param['pass'],'id', $param['id']);
            $entidad->updateMultiColum('rolid', $param['rolid'],'id', $param['id']);
            $entidad->updateMultiColum('activo', $param['activo'] === 'true'?1:0,'id', $param['id']);
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if(isset($param['id']) && $param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00020Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00020($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonPermisos($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis20000($this->adapter);
            $entidad->setModulesid($param['modulo']);
            $entidad->setRolid($param['rol']);
            //Guardamos
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
            $delete = new Entidades\Sis20000($this->adapter);
            $delete->deleteMulti('modulesid', $param['modulo'], 'rolid', $param['rol']);

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonPerfilesexternos($param=array())
    {
        //Insertar
        if(isset($param['rol']) && $param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00040($this->adapter);
            $entidad->setRol($param['rol']);
            $entidad->setActivo($param['activo'] === 'true'?1:0);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if(isset($param['id']) && $param['accionSql'] == 'Update') 
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00040($this->adapter);
            $entidad->updateMultiColum('rol', $param['rol'],'id', $param['id']);
            $entidad->updateMultiColum('activo', $param['activo'] === 'true'?1:0,'id', $param['id']);
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if(isset($param['id']) && $param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00040Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00040($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonPermisosexternos($param=array())
    {
        //Insertar
        if($param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis20010($this->adapter);
            $entidad->setModulesid($param['modulo']);
            $entidad->setRolid($param['rol']);
            //Guardamos
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
            $delete = new Entidades\Sis20010($this->adapter);
            $delete->deleteMulti('modulesid', $param['modulo'], 'rolid', $param['rol']);

            die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonUsuariosexternos($param=array())
    {
        //Insertar
        if(isset($param['nombre']) && $param['accionSql'] == 'Insert') {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00050($this->adapter);
            $entidad->setNombre($param['nombre']);
            $entidad->setApellido($param['apellido']);
            $entidad->setCorreo($param['correo']);
            $entidad->setUsuario($param['usuario']);
            $entidad->setPass($param['pass']);
            $entidad->setActivo($param['activo'] === 'true'?1:0);
            //Guardamos Clientes
            $entidad->save();
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Insertado Correctamente.')));
        }
        else if(isset($param['id']) && $param['accionSql'] == 'Update') 
        {
            //Insertar en la web transaccional
            $entidad = new Entidades\Sis00050($this->adapter);
            $entidad->updateMultiColum('nombre', $param['nombre'],'id', $param['id']);
            $entidad->updateMultiColum('apellido', $param['apellido'],'id', $param['id']);
            $entidad->updateMultiColum('correo', $param['correo'],'id', $param['id']);
            $entidad->updateMultiColum('usuario', $param['usuario'],'id', $param['id']);
            $entidad->updateMultiColum('pass', $param['pass'],'id', $param['id']);
            $entidad->updateMultiColum('activo', $param['activo'] === 'true'?1:0,'id', $param['id']);
            die(json_encode(array('status' => 'OK', 'descripcion' => 'Actualizado Correctamente.')));
        }
        else if(isset($param['id']) && $param['accionSql'] == 'Delete')
        {
            //Insertar en la web transaccional
            $clientes = new Models\Sis00050Model($this->adapter);
            $validar = $clientes->getTransacciones($param['id']);
            if ($validar['numrows'] > 0) {
                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No podemos Eliminar tiene transacciones.')));
            }else{
                $clientes = new Entidades\Sis00050($this->adapter);
                $clientes->deleteMulti('id', $param['id']);

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Eliminado Correctamente.')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'ERROR', 'descripcion' => 'No tiene permisos para ingresar.')));
        }
    }
    
    public function jsonExcelInventario($param=array())
    {
        //Guardamos que se acabo de instalar
        if (isset($_FILES["inventario"]["name"]))
        {
            //$ext = basename($_FILES["logo"]["type"]);
            $ext = substr($_FILES["inventario"]["name"], strpos($_FILES["inventario"]["name"],'.')+1, strlen($_FILES["inventario"]["name"]));
            if ($ext == "xls" || $ext == "xlsx")
            {
                $target_file = PATH_UPLOAD . $_FILES["inventario"]["name"].".".$ext;
                move_uploaded_file($_FILES["inventario"]["tmp_name"], $target_file);
                //Recorrer Excel
                require_once('lib/phpexcel/PHPExcel.php');
                require_once('lib/phpexcel/PHPExcel/Reader/Excel2007.php');

                // Cargando la hoja de cálculo
                $objReader = new \PHPExcel_Reader_Excel2007();
                $objPHPExcel = $objReader->load($target_file);
                $objFecha = new \PHPExcel_Shared_Date();
                // Asignar hoja de excel activa
                $objPHPExcel->setActiveSheetIndex(0);
                //Esta porcion de codigo es para mostrar el excel cargado en forma de tabla dentro del sistema
                $columnas = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
                $filas = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

                for ($k=2;$k<=$filas;$k++)
                {
                    switch ($param['tipo']) {
                        case 4: //Articulos
                            $bdcliente = $objPHPExcel->getActiveSheet()->getCell('A'.$k)->getCalculatedValue();
                            $codigo = $objPHPExcel->getActiveSheet()->getCell('B'.$k)->getCalculatedValue();
                            $descripcion = $objPHPExcel->getActiveSheet()->getCell('C'.$k)->getCalculatedValue();
                            $familia = $objPHPExcel->getActiveSheet()->getCell('D'.$k)->getCalculatedValue();
                            $unidad = $objPHPExcel->getActiveSheet()->getCell('E'.$k)->getCalculatedValue();
                            $empresa = $objPHPExcel->getActiveSheet()->getCell('F'.$k)->getCalculatedValue();
                            $marca = $objPHPExcel->getActiveSheet()->getCell('G'.$k)->getCalculatedValue();
                            $material = $objPHPExcel->getActiveSheet()->getCell('H'.$k)->getCalculatedValue();
                            $color = $objPHPExcel->getActiveSheet()->getCell('I'.$k)->getCalculatedValue();
                            
                            if (strlen($bdcliente)==0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' la base de datos del cliente es obligatoria.')));
                            }
                            
                            if (strlen($codigo)==0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' el codigo del articulo es obligatoria.')));
                            }
                            
                            $entidad = new Entidades\Inv00000($this->adapter,$bdcliente);
                            $val_articulo = $entidad->getCountMulti('id', 'codigo', $codigo);
                            //Validar si existe el producto
                            if ($val_articulo['numrows']>0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'EL articulo de la linea '.$k.' ya se encuentra creado.')));
                            }
                            
                            if (strlen($descripcion)==0 || strlen($familia)==0 || strlen($unidad)==0 || strlen($empresa)==0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' el los datos obligatorios no se encuentra.')));
                            }
                            
                            $entidad->autocommit();

                            $entidad->setCodigo($codigo);
                            $entidad->setDescripcion($descripcion);
                            $entidad->setInv00010id($familia);
                            $entidad->setInv40010id($unidad);
                            $entidad->setEmpresa($empresa);
                            $entidad->setMarca($marca);
                            $entidad->setMaterial($material);
                            $entidad->setColor($color);
                            $entidad->save();
                            
                            //id de producto insertado
                            $idinv = $entidad->getMulti('codigo', $codigo, 'descripcion',$descripcion);
                            
                            //Enlazamos a las bodegas
                            $bodega = new \Entidades\Inv00001($this->adapter,$bdcliente);
                            $bodprod = new \Entidades\Inv00002($this->adapter,$bdcliente);
                            $dtbodega = $bodega->getAll('bodega');

                            if (globalFunctions::es_bidimensional($dtbodega))
                            {
                                foreach ($dtbodega as $value) {
                                    $bodprod->setInv00000id($idinv['id']);
                                    $bodprod->setInv00001id($value['bodega']);
                                    $bodprod->setAsignado(0);
                                    $bodprod->setCantidad(0);
                                    $bodprod->save();
                                }
                            }
                            else if(isset($dtbodega['bodega']))
                            {
                                $bodprod->setInv00000id($idinv['id']);
                                $bodprod->setInv00001id($dtbodega['bodega']);
                                $bodprod->setAsignado(0);
                                $bodprod->setCantidad(0);
                                $bodprod->save();
                            }
                            break;
                        case 14: //Articulos Optica
                            die(json_encode(array('status' => 'Error', 'descripcion' => 'El tipo seleccionado falta programacion.')));
                            break;
                        case 17: //Articulos Automotriz
                            die(json_encode(array('status' => 'Error', 'descripcion' => 'El tipo seleccionado falta programacion.')));
                            break;
                        default:
                            die(json_encode(array('status' => 'Error', 'descripcion' => 'El tipo seleccionado no es soportado.')));
                            break;
                    }
                }
                
                $entidad->commit();

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Subido Con exito.')));
            }
            else
            {
                die(json_encode(array('status' => 'Error', 'descripcion' => 'No tiene formato correcto, formatos aceptados (xls,xlsx).')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'Error', 'descripcion' => 'No ah seleccionado ningun documento.')));
        }
    }
    
    public function jsonExcelProveedor($param=array())
    {
        //Guardamos que se acabo de instalar
        if (isset($_FILES["proveedor"]["name"]))
        {
            //$ext = basename($_FILES["logo"]["type"]);
            $ext = substr($_FILES["proveedor"]["name"], strpos($_FILES["proveedor"]["name"],'.')+1, strlen($_FILES["proveedor"]["name"]));
            if ($ext == "xls" || $ext == "xlsx")
            {
                $target_file = PATH_UPLOAD . $_FILES["proveedor"]["name"].".".$ext;
                move_uploaded_file($_FILES["proveedor"]["tmp_name"], $target_file);
                //Recorrer Excel
                require_once('lib/phpexcel/PHPExcel.php');
                require_once('lib/phpexcel/PHPExcel/Reader/Excel2007.php');

                // Cargando la hoja de cálculo
                $objReader = new \PHPExcel_Reader_Excel2007();
                $objPHPExcel = $objReader->load($target_file);
                $objFecha = new \PHPExcel_Shared_Date();
                // Asignar hoja de excel activa
                $objPHPExcel->setActiveSheetIndex(0);
                //Esta porcion de codigo es para mostrar el excel cargado en forma de tabla dentro del sistema
                $columnas = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
                $filas = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

                for ($k=2;$k<=$filas;$k++)
                {
                    switch ($param['tipo']) {
                        case 3: //Proveedores
                            $bdcliente = $objPHPExcel->getActiveSheet()->getCell('A'.$k)->getCalculatedValue();
                            $codigo = $objPHPExcel->getActiveSheet()->getCell('B'.$k)->getCalculatedValue();
                            $tipo = $objPHPExcel->getActiveSheet()->getCell('C'.$k)->getCalculatedValue();
                            $contribuyente = $objPHPExcel->getActiveSheet()->getCell('D'.$k)->getCalculatedValue();
                            $razonsocial = $objPHPExcel->getActiveSheet()->getCell('E'.$k)->getCalculatedValue();
                            $nombrecomercial = $objPHPExcel->getActiveSheet()->getCell('F'.$k)->getCalculatedValue();
                            $correo = $objPHPExcel->getActiveSheet()->getCell('G'.$k)->getCalculatedValue();
                            $contacto = $objPHPExcel->getActiveSheet()->getCell('H'.$k)->getCalculatedValue();
                            $direccion = $objPHPExcel->getActiveSheet()->getCell('I'.$k)->getCalculatedValue();
                            $provincia = $objPHPExcel->getActiveSheet()->getCell('J'.$k)->getCalculatedValue();
                            $canton = $objPHPExcel->getActiveSheet()->getCell('K'.$k)->getCalculatedValue();
                            $parroquia = $objPHPExcel->getActiveSheet()->getCell('L'.$k)->getCalculatedValue();
                            $telefono = $objPHPExcel->getActiveSheet()->getCell('M'.$k)->getCalculatedValue();
                            $celular = $objPHPExcel->getActiveSheet()->getCell('N'.$k)->getCalculatedValue();
                            $empresa = $objPHPExcel->getActiveSheet()->getCell('O'.$k)->getCalculatedValue();
                            $usuario = $objPHPExcel->getActiveSheet()->getCell('P'.$k)->getCalculatedValue();
                            $plan = $objPHPExcel->getActiveSheet()->getCell('Q'.$k)->getCalculatedValue(); 
                            $formapago = $objPHPExcel->getActiveSheet()->getCell('R'.$k)->getCalculatedValue(); 
                            
                            if (strlen($bdcliente)==0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' la base de datos del cliente es obligatoria.')));
                            }
                            
                            if (strlen($codigo)==0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' el codigo del articulo es obligatoria.')));
                            }
                            
                            $entidad = new Entidades\Cp00000($this->adapter,$bdcliente);
                            $val_articulo = $entidad->getCountMulti('id', 'codigo', $codigo);
                            //Validar si existe el producto
                            if ($val_articulo['numrows']>0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'El proveedor de la linea '.$k.' ya se encuentra creado.')));
                            }
                            
                            if (strlen($tipo)==0 || strlen($contribuyente)==0 || strlen($razonsocial)==0 || strlen($correo)==0 || strlen($plan)==0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' el los datos obligatorios no se encuentra.')));
                            }
                            
                            //Tipo
                            $sis40170 = new Entidades\Sis40170($this->adapter,$bdcliente);
                            $dtsis40170 = $sis40170->getMulti('documento', $tipo);
                            
                            if (!isset($dtsis40170['codigo'])) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' el tipo del proveedor es erroneo.')));
                            }
                            
                            //Contribuyente
                            $sis40180 = new Entidades\Sis40180($this->adapter,$bdcliente);
                            $dtsis40180 = $sis40180->getMulti('contribuyente', $contribuyente);
                            
                            if (!isset($dtsis40170['codigo'])) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' el contribuyente del proveedor es erroneo.')));
                            }
                            
                            //Plan
                            $fin40060 = new Entidades\Fin40060($this->adapter,$bdcliente);
                            $dtfin40060 = $fin40060->getCountMulti('plan', $plan);
                            
                            if ($dtfin40060['numrows']==0) {
                                die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' el plan de impuestos del proveedor es erroneo.')));
                            }
                            
                            //Forma  de Pago
                            if (strlen($formapago)>0) {
                                $cc40020 = new \Entidades\Cc40020($this->adapter,$bdcliente);
                                $dtcc40020 = $cc40020->getMulti('formapago', $plan);

                                if (isset($dtcc40020['id'])) {
                                    $formapago= $dtcc40020['id'];
                                } else {
                                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' la forma de pago del proveedor es erroneo.')));
                                }
                            } else {
                                $formapago = '-1';
                            }
                            
                            //Provincia
                            if (strlen($provincia)>0) {
                                $sis40190 = new Entidades\Sis40190($this->adapter,$bdcliente);
                                $dtsis40190 = $sis40190->getMulti('provincia', $provincia);

                                if (isset($dtsis40190['codigo'])) {
                                    $provincia= $dtsis40190['codigo'];
                                } else {
                                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' la provincia del proveedor es erroneo.')));
                                }
                            } else {
                                $provincia = '-1';
                            }
                            
                            //canton
                            if (strlen($canton)>0) {
                                $sis40200 = new Entidades\Sis40200($this->adapter,$bdcliente);
                                $dtsis40200 = $sis40200->getMulti('provinciaid', $provincia,'canton',$canton);

                                if (isset($dtsis40200['codigo'])) {
                                    $canton= $dtsis40200['codigo'];
                                } else {
                                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' el canton del proveedor es erroneo.')));
                                }
                            } else {
                                $canton = '-1';
                            }
                            
                            //parroquia
                            if (strlen($parroquia)>0) {
                                $sis40210 = new Entidades\Sis40210($this->adapter,$bdcliente);
                                $dtsis40210 = $sis40210->getMulti('parroquia', $parroquia,'cantonid',$canton);

                                if (isset($dtsis40210['codigo'])) {
                                    $parroquia= $dtsis40210['codigo'];
                                } else {
                                    die(json_encode(array('status' => 'ERROR', 'descripcion' => 'En la linea '.$k.' la parroquia del proveedor es erroneo.')));
                                }
                            } else {
                                $parroquia = '-1';
                            }
                            
                            $entidad->autocommit();

                            $entidad->setCodigo($codigo);
                            $entidad->setSis40170id($dtsis40170['codigo']);
                            $entidad->setSis40180id($dtsis40180['codigo']);
                            $entidad->setRazonsocial($razonsocial);
                            $entidad->setNombrecomercial($nombrecomercial);
                            $entidad->setCorreo($correo);
                            $entidad->setContacto($contacto);
                            $entidad->setDireccion($direccion);
                            $entidad->setSis40190id($provincia);
                            $entidad->setSis40200id($canton);
                            $entidad->setSis40210id($parroquia);
                            $entidad->setTelefono($telefono);
                            $entidad->setCelular($celular);
                            $entidad->setEmpresa($empresa);
                            $entidad->setUsuario($usuario);
                            $entidad->setFcreacion(date('Y-m-d'));
                            $entidad->setFin40060id($plan);
                            $entidad->setCc40020id($formapago);
                            $entidad->save();
                            
                            $tipos = new Entidades\Cp50001($this->adapter,$bdcliente);
                            $dttipos = $tipos->getAll();

                            if (globalFunctions::es_bidimensional($dttipos))
                            {
                                foreach ($dttipos as $value)
                                {
                                    //Cuenta por defecto
                                    $cuentaDefecto = new \Entidades\Fin00011($this->adapter);
                                    $dtcuentaDefecto = $cuentaDefecto->getMulti('sis00400id', 4,'tipo',$value['id']);

                                    $entidadCuenta = new Entidades\Cp00011($this->adapter);
                                    $entidadCuenta->setFin00000id($dtcuentaDefecto['fin00000id']);
                                    $entidadCuenta->setCp00000id($codigo);
                                    $entidadCuenta->setCp50001id($value['id']);
                                    $entidadCuenta->save();
                                }
                            }
                            else if (isset($dttipos['id']))
                            {
                                //Cuenta por defecto
                                $cuentaDefecto = new \Entidades\Fin00011($this->adapter);
                                $dtcuentaDefecto = $cuentaDefecto->getMulti('sis00400id', 4,'tipo',$dttipos['id']);

                                $entidadCuenta = new Entidades\Cp00011($this->adapter);
                                $entidadCuenta->setFin00000id($dtcuentaDefecto['fin00000id']);
                                $entidadCuenta->setCp00000id($codigo);
                                $entidadCuenta->setCp50001id($dttipos['id']);
                                $entidadCuenta->save();
                            }
                            break;
                        default:
                            die(json_encode(array('status' => 'Error', 'descripcion' => 'El tipo seleccionado no es soportado.')));
                            break;
                    }
                }
                
                $entidad->commit();

                die(json_encode(array('status' => 'OK', 'descripcion' => 'Subido Con exito.')));
            }
            else
            {
                die(json_encode(array('status' => 'Error', 'descripcion' => 'No tiene formato correcto, formatos aceptados (xls,xlsx).')));
            }
        }
        else
        {
            die(json_encode(array('status' => 'Error', 'descripcion' => 'No ah seleccionado ningun documento.')));
        }
    }
    
    /**
     * Funcion de LLenar Combobox Realizarla por Controller
     */
    public function getSelect()
    {
        try
        {
            $result = '';
            $valDefecto = '';
            if (isset($_POST['modelo'])) {
                if (isset($_POST['get'])) {
                    $valDefecto = 'selected="true" ';
                }
                $model = 'Models\\'.ucwords($_POST['modelo']).'Model';
                $select = new $model($this->adapter);
                if (isset($_POST['opcion']))
                {
                    if (isset($_POST['parametro']))
                    {
                        $datos = $select->getComboboxEdit($_POST['opcion'],$_POST['parametro']);
                    }
                    else
                    {
                        $datos = $select->getComboBox($_POST['opcion']);
                    }
                }
                else if (isset($_POST['parametro']))
                {
                    if ($_POST['modelo'] == 'sis40200' || $_POST['modelo'] == 'sis40210')
                    {
                        $datos = $select->getComboboxEdit($_POST['parametro']);
                    }
                    else
                    {
                        $datos = $select->getCombobox($_POST['parametro']);
                    }
                }
                else if (isset($_POST['get'])){
                    if (isset($_POST['getaux']))
                    {
                        $datos = $select->getComboBox($_POST['getaux']);   
                    }
                    else
                    {
                        $datos = $select->getComboBox($_POST['get']);
                    }
                }
                else $datos = $select->getCombobox('');
                
                $result .= '<option value="-1">Seleccionar</option>';
                //$result .= "<option data-tokens='Seleccionar' value='0' >Seleccionar</option>";
                if (globalFunctions::es_bidimensional($datos)) {
                    foreach ($datos as $key => $value) {
                        if (isset($_POST['get'])) {
                            if ($value["id"] == $_POST['get']) {
                                $result .= '<option value="'.$value["id"].'" '.$valDefecto.'>'.$value["nombre"].'</option>';
                            }else{
                                $result .= '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                            }
                        }else{
                            $result .= '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                        }
                        //$result .= "<option data-tokens='".$value["nombre"]."' value='".$value["id"]."' >".$value["nombre"]."</option>";
                    }
                }
                else if (isset($_POST['get'])) {
                    if ($datos["id"] == $_POST['get']) {
                        $result .= '<option value="'.$datos["id"].'" '.$valDefecto.'>'.$datos["nombre"].'</option>';
                    }else{
                        $result .= '<option value="'.$datos["id"].'">'.$datos["nombre"].'</option>';
                    }
                }else{
                    $result .= '<option value="'.$datos["id"].'">'.$datos["nombre"].'</option>';
                }
            }
            echo $result;
        }
        catch (Exception $ex)
        {
            echo ex;
        }
    }
    
    public function prueba()
    {
        sleep(1);
        die(json_encode(array('status' => 'OK', 'descripcion' => 'Datos Guardados Correctamente.')));
    }
}