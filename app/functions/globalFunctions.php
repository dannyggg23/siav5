<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* MANEJA LAS FUNCIONES GLOBALES DE LA APP
*/
class globalFunctions
{
    public static function validateController($controller)
    {
        if(!is_file(PATH_CONTROLLERS . strtolower($controller) . "/Controller/{$controller}Controller.php"))
            return false;
        return true;
    }

    public static function validateMethodController($controller, $method)
    {
        if(!method_exists($controller, $method))
            return false;
        return true;
    }
    
    /*
     * Function: Crea todo el codigo que va dentro de los id de los div
     */
    public static function renderizar($website,$datos,$empresa=array())
    {
        //Convertir en variables los datos enviados.
        foreach ($datos as $id_assoc => $valor){
            ${$id_assoc}=$valor;
        }
        
        $layout = new dti_layout($website);

        //Declaramos Variables Vacias y limpiar variables.
        $datosUsuario = "";
        $section_contents_tpl['dti_area_content'] = "";
        //$section_contents_tpl['nomUsuario']

        // <<<<< Llenar Template Css/js >>>>>
        if (isset($core))
        {
            $template = globalFunctions::templateAction($website["template_core"]);
            dti_core::set("css","<link href='resources/template/".$website['template_core']."/style.css' rel='stylesheet' type='text/css'/>");
            dti_core::set("script","<script src='resources/template/".$website['template_core']."/template.js' type='text/javascript'></script>");
            if (isset($_SESSION["usuarioCore"]))
            {
                $section_contents_tpl['dti_menu'] = $layout->menuAction($website["template_portal"],1,'core');
            }
        }
        else
        {
            $template = globalFunctions::templateAction($website["template_portal"]);
            dti_core::set("css","<link href='resources/template/".$website['template_portal']."/style.css' rel='stylesheet' type='text/css'/>");
            dti_core::set("script","<script src='resources/template/".$website['template_portal']."/template.js' type='text/javascript'></script>");
            if (isset($_SESSION["usuario"]))
            {
                if (isset($modulo))
                {
                    $section_contents_tpl['dti_menu'] = $layout->menuAction($website["template_portal"],0,'',$modulo);
                    // <<<<< miniMenu >>>>>
                    $section_contents_tpl['dti_titulo'] = $layout->minimenuAction();
                }
                else
                {
                    $section_contents_tpl['dti_menu'] = $layout->menuAction($website["template_portal"]);
                }
            }
        }
        
        if (isset($empresa['id']))
        {
            // <<<<< Header >>>>>
            $section_contents_tpl['dti_logo'] = globalFunctions::headerAction($website["template_portal"],$empresa["logo"]);
        }
        else
        {
            // <<<<< Header >>>>>
            $section_contents_tpl['dti_logo'] = globalFunctions::headerAction($website["template_portal"],$website["logo"]);
        }
        // <<<<< footer >>>>>
        $section_contents_tpl['dti_footer'] = globalFunctions::footerAction($website["copyright"],$website["template_portal"]);
        // <<<<< Bienvenida >>>>>
        if (isset($_SESSION["usuario"]))
        {
            $section_contents_tpl['dti_usuario'] = "Bienvenido, ".$_SESSION["usuario"];
        }
        else
        {
            $section_contents_tpl['dti_usuario'] = "Bienvenido, Invitado";
        }
        // <<<<< Telefono >>>>>
        //$section_contents_tpl['dti_telefono'] = $this->telefonoAction($this->website["template_portal"],$this->website["telefono"]);
        //$section_contents_tpl['dti_contact'] = $this->telefonoAction($this->website["template_portal"],$this->website["telefono"]);

        // Total Notificaciones
        //$section_contents_tpl['dti_notificacion'] = $this->notificacionTotal();
        //$section_contents_tpl['dti_notificaciones'] = $this->notificacionDetallado();

        // <<<<< Dti Menu Datos en caso que tenga >>>>>
        if (!isset($core)){
            $section_contents_tpl['dti_mnudt'] = $layout->mnuPerfil();
            $section_contents_tpl['nomUsuario']=$_SESSION['usuario'].' - '.$_SESSION['bodUsuario'];

        }
                
        /*
         * MANEJO DE SECCIONES
         */
        foreach ($section as $key => $value) {
            switch ($key) {
                case 'privado':
                    if (!isset($_SESSION["user"])) {
                        $section_contents_tpl["dti_area_content"] = "<section class='row'>". $this->loginAction($value) . "</section>";
                    }
                    break;
                case 'loginClasic':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $this->loginAction($value) . "</section>";
                    break;
                case 'titulo':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $this->tituloAction($value) . "</section>";
                    break;
                case 'tabla':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'circle':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'boxquick':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'form':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'panel':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'box':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'minislider':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'slider':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'catalogo':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'subirdoc':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'crearPDF':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'crearEXCEL':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'login':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'grafico':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'regresar':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>".$this->btnlinkAction($value) . "</section>";
                    break;
                case 'layout':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'layout_section':
                    if(isset($layout_section_id)){
                        $section_contents_tpl["dti_area_content"] .= "<section id='".$layout_section_id."' class='row dti_section'>". $value . "</section>";
                    }else{
                        $section_contents_tpl["dti_area_content"] .= "<section class='row dti_section'>". $value . "</section>";
                    }
                    break;
                case 'layout_header':
                    if(isset($layout_header_id)){
                        $section_contents_tpl["dti_area_content"] .= "<section id='".$layout_header_id."' class='row col-lg-12 col-md-12'>". $value . "</section>";
                    }
                    else{
                        $section_contents_tpl["dti_area_content"] .= "<section class='row col-lg-12 col-md-12'>". $value . "</section>";
                    }
                        
                    break;
                case 'breadcrumbs':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'manual':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'manual2':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
                case 'manual_section':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row dti_section'>". $value . "</section>";
                    break;
                case 'manual_titulo':
                    dti_core::set("css","<link href='public/css/componentes/table/tablestyle.css' rel='stylesheet' type='text/css'/>");

                    if (isset($value['btn']) && isset($value['url']))
                    {
                        $btnnuevo = '<button class="btn btn-primary btn_nuevo btn-sm pull-right '.$value['btn'].'" data-toggle="modal" data-target="#'.$value['btn'].'"><span class="fa fa-edit"></span>Nuevo</button>';
                        $modal_build = new dti_builder_modal();
                        $modal_build->setModal(array(
                            'id'=>$value['btn'],
                            'tipo'=>'edit',
                            'titulo'=>'Nuevo Registro',
                            'mensaje'=>"<div id='loader".$value['btn']."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                        <div class='outer_div".$value['btn']."'></div>",
                            'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
                        ));
                        $modal = $modal_build->getModal();

                        dti_core::set("modal", $modal);
                        $script = "<script>
                                    $(function() {
                                        $(document).on('click','.".$value['btn']."',function(e){
                                            $('#loader".$value['btn']."').fadeIn('slow');
                                             $.ajax({
                                                    url:'".$value['url']."',
                                                    data: 'panel=true',
                                                    type: 'post',
                                                    dataType: 'json',
                                                    beforeSend: function(objeto){
                                                        $('#loader".$value['btn']."').html(\"<img src='public/images/ajax-loader.gif'> Cargando...\");
                                                    },
                                                    success:function(data){
                                                        $('.outer_div".$value['btn']."').html(data.layout).fadeIn('slow');
                                                        $('#loader".$value['btn']."').html('');
                                                        $('._MODAL_').html(data.modal).fadeIn('slow');
                                                        $('._SCRIPT_').html(data.script).fadeIn('slow');
                                                    }
                                                });
                                         });
                                    });
                                    </script>";
                        dti_core::set("script", $script);
                        $section_contents_tpl["dti_area_content"] .= "<section class='row dti_section'><div class='card card-plain'><div class='card-header' data-background-color='purple'><h4 class='title'>".$btnnuevo."".$value['titulo']."</h4></div><div class='card-content table-responsive'>". $value['layout'] . "</div></section>";
                    }
                    else
                    {
                        $section_contents_tpl["dti_area_content"] .= "<section class='row dti_section'><div class='card card-plain'><div class='card-header' data-background-color='purple'><h4 class='title'>".$value['titulo']."</h4></div><div class='card-content table-responsive'>". $value['layout'] . "</div></section>";
                    }
                    break;
                case 'prueba':
                    $section_contents_tpl["dti_area_content"] .= "<section class='row'>". $value . "</section>";
                    break;
            }
        }
        //Insertar en los id de los div el codigo que necesitamos
        $render = globalFunctions::renderizarTotal($template,$section_contents_tpl);
        return $render;
    }
    
    /**
     * Funcion: Insertar dentro de los id el codigo necesario
     * 
     * @param array $render estructura del template
     * @param array $section_contents_tpl contenidos a actualizarse
     * @return string html para mostrar en la vista
     */
    private static function renderizarTotal($render='',$section_contents_tpl='')
    {
        if ($render) {
            foreach ($section_contents_tpl as $key => $section_content)
            {
                $view = '';
                if($section_content)
                {
                    //last find of section_name
                    $pos_occ = strpos($render, $key);
                    if($pos_occ)
                    {
                        //next part of string after are_name as id
                        $render_part = substr($render, $pos_occ);
                        //then find >
                        $pos_grt = strpos($render_part, '>');
                        //next part of string after >
                        $render_part = substr($render_part, $pos_grt);
                        //then find </
                        $pos_lss = strpos($render_part, '</');
                        //next part of string after </
                        $render_part = substr($render_part, $pos_lss);

                        $view = substr_replace($render, $section_content, ($pos_occ+$pos_grt+1));
                        $view.= $render_part;
                        $render = $view;
                    }
                }
            }
        }
        return $render;
    }
    
    private static function templateAction($template)
    {
        /********
	 * template render
	 */
        $render = "";
        $webtemplate = fopen("resources/template/".$template."/template.php", "r");
        if($webtemplate)
        {
                //Output a line of the file until the end is reached
                while(!feof($webtemplate))
                {
                        $render.= fgets($webtemplate);
                }
                fclose($webtemplate);
        }
        return $render;
    }
    
    private static function headerAction($template,$header)
    {
        switch ($template) {
            case 'template1':
                $headerAction = "<a class='navbar-brand' href='".CONTROLADOR_DEFECTO."/index'><img style='max-width:100px; height: 40px !important; margin-top: -7px;' src='".$header."' alt='logo'></a>";
                break;
            case 'template2':
                $headerAction = "<img style='max-width:100px; height: 40px !important; margin-top: -7px;' src='".$header."'>";
                break;
            case 'template3':
                $headerAction = "<a class='navbar-brand' href='".CONTROLADOR_DEFECTO."/index'><img style='max-width:100px; height: 40px !important; margin-top: -7px;' src='".$header."' alt='logo'></a>";
                break;
            case 'template4':
                $headerAction = "<a class='navbar-brand' href='".CONTROLADOR_DEFECTO."/index'><img style='max-width:100px; height: 40px !important; margin-top: -7px;' src='".$header."' alt='logo'></a>";
                break;
            case 'template5':
                $headerAction = "<a class='navbar-brand' href='".CONTROLADOR_DEFECTO."/index' style='width: 100%;'><img style='max-width:200px;margin-top: -12px;' src='".$header."' alt=''></a>";
                break;
            case 'template6':
                $headerAction = "<a class='navbar-brand' href='".CONTROLADOR_DEFECTO."/index'><img style='width:200px;margin-top: -12px;' src='".$header."' alt=''></a>";
                //$headerAction = "<a class='navbar-brand' href='".CONTROLADOR_DEFECTO."/index'><img style='max-width:100px; height: 40px !important; margin-top: -7px;' src='".$header."' alt='logo'></a>";
                break;
        }
        return $headerAction;
    }
    
    private static function footerAction($footer,$template="template5")
    {
        $footerAction = "<div class='container'>
        <p class='pull-left'> &copy; <a href='http://www.dtiware.com' target='_blank'>".$footer."</a> ".date('Y')." </p>
        <!--<div class='pull-right'>
            <ul class='social'>
                <li> <a href='#'> <i class=' fa fa-facebook'> </i> </a> </li>
                <li> <a href='#'> <i class='fa fa-twitter'> </i> </a> </li>
                <li> <a href='#'> <i class='fa fa-google-plus'> </i> </a> </li>
                <li> <a href='#'> <i class='fa fa-pinterest'> </i> </a> </li>
                <li> <a href='#'> <i class='fa fa-youtube'> </i> </a> </li>
            </ul>
        </div>-->
    </div> <!-- container footer -->";
        switch ($template) {
            case 'template1':
                $footerAction = $footerAction;
                break;
            case 'template2':
                $footerAction = $footerAction;
                break;
            case 'template3':
                $footerAction = $footerAction;
                break;
            case 'template4':
                $footerAction = $footerAction;
                break;
            case 'template5':
                $footerAction = $footerAction;
                break;
            case 'template6':
                $footerAction = $footer;
                //$headerAction = $footerAction;
                break;
        }       
        return $footerAction;
    }
    
    /**
     * Funcion: Valida si devuelve datos bidimencionales
     * 
     * @param array $array array a validar
     * @return boolean es bidimencial true o false
     */
    public static function es_bidimensional($array)
    {
        if (!is_array($array)) return false;

        foreach ($array as $elemento) {
          if (!is_array($elemento)) return false; 
          foreach ($elemento as $elem) {
            if (is_array($elem)) return false;
          }
        }

        return true;
    }
    
    /**
     * Funcion: Pagina la informacion de las tablas
     * 
     * @param type $reload
     * @param int $page numero de pagina
     * @param int $tpages total de paginas
     * @param type $adjacents
     * @param string $funcion funcion que va hacer al presionar
     * @return string devuelve el html
     */
    public static function paginate($page, $tpages, $adjacents,$funcion) 
    {
	$prevlabel = "&lsaquo; Anterior";
	$nextlabel = "Siguiente &rsaquo;";
	$out = '<nav aria-label="Pagination"><ul class="pagination pagination-large">';
	
	// previous label

	if($page==1) {
		$out.= "<li class='page-item disabled'><span><a class='page-link'>$prevlabel</a></span></li>";
	} else if($page==2) {
		$out.= "<li class='page-item'><span><a class='page-link' href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
	}else {
		$out.= "<li class='page-item'><span><a class='page-link' href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";

	}
	
	// first label
	if($page>($adjacents+1)) {
		$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
	}
	// interval
	if($page>($adjacents+2)) {
		$out.= "<li class='page-item'><a class='page-link'>...</a></li>";
	}

	// pages

	$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	for($i=$pmin; $i<=$pmax; $i++) {
		if($i==$page) {
			$out.= "<li class='page-item active'><a class='page-link'>$i</a></li>";
		}else if($i==1) {
			$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
		}else {
			$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
		}
	}

	// interval

	if($page<($tpages-$adjacents-1)) {
		$out.= "<li class='page-item'><a class='page-link'>...</a></li>";
	}

	// last

	if($page<($tpages-$adjacents)) {
		$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
	}

	// next

	if($page<$tpages) {
		$out.= "<li class='page-item'><span><a class='page-link' href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
	}else {
		$out.= "<li class='page-item disabled'><span><a class='page-link'>$nextlabel</a></span></li>";
	}
	
	$out.= "</ul></nav>";
	return $out;
    }
    
    /**
     * Funcion: Quita las tildes de una cadena
     * 
     * @param string $cadena cadena a limpiar tildes
     * @return string Cadena limpia de tildes
     */
    public static function quitarTildes($cadena)
    {
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }
    
    /**
     * Función: Regresa el mensaje construido
     * verde(correcto),rojo(error),amarillo(advertencia),
     * azul(informacion)
     * 
     * @param string $tipo verde,rojo,amarillo,azul
     * @param string $titulo titulo del mensaje
     * @param string $detalle detalle del mensaje
     * @param string $version version 
     * @return string codigo html del mensaje
     */
    public static function getMensaje($tipo,$titulo,$detalle,$version='1')
    {
        $html = '';
        switch ($version) {
            case '1':
                switch ($tipo) {
                    case 'verde':
                        $html = 'Swal.fire("'.$titulo.'!", "'.$detalle.'!", "success");';
                        break;
                    case 'rojo':
                        $html = 'Swal.fire("'.$titulo.'!", "'.$detalle.'!", "error");';
                        break;
                    case 'amarillo':
                        $html = 'Swal.fire("'.$titulo.'!", "'.$detalle.'!", "warning");';
                        break;
                    case 'azul':
                        $html = 'Swal.fire("'.$titulo.'!", "'.$detalle.'!", "info");';
                        break;
                }
                break;
        }
        return $html;
    }
    
    /**
     * Funcion: Llama o Ejecuta los WebService
     * Soporta: POST - PUT - GET - DELETE
     * 
     * @param string $method POST - GET - DELETE - PUT
     * @param string $url Url a ejecutarse
     * @param array $data data a enviarse
     * @return string or json
     */
    public static function callREST($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        // Optional Authentication:
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
    
    public static function getMsgConfirmJS($dt)
    {
        $btn = '';
        foreach ($dt['btn'] as $key => $value) {
            $btn .= '{ label: \''.$value['titulo'].'\', action: function(dialog) { '.$value['comandos'].' dialog.close(); } } ,';
        }
        //eliminar la ultima coma
        if (strlen($btn) > 0 ) $btn = substr($btn, 0,strlen($btn)-1);
        
        $mensaje = '<script>
                        function '.$dt['funcion'].'() {
                            BootstrapDialog.show({
                                        title: \''.$dt['titulo'].'\',
                                        message: \''.$dt['mensaje'].'\',
                                        closable: false,
                                        buttons: ['.$btn.']
                                    });
                        }
                    </script>';
        return $mensaje;
    }
    
    /**
     * Funcion: Quita espacios de la cadena
     * 
     * @param string $cadena cadena a limpiar espacio
     * @return string Cadena limpia de espacios
     */
    public static function quitarEstacios($cadena)
    {
        $cad = ltrim(rtrim($cadena));
        $no_permitidas= array (" ");
        $permitidas= array ("");
        $texto = str_replace($no_permitidas, $permitidas ,$cad);
        return $texto;
    }
    
    public static function limpiarVariables($cadena)
    {
        $cad = ltrim(rtrim($cadena));
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("","\\0","\\n", "\\r", "\'", '\"', "\\Z");
        $txt = strip_tags(utf8_decode(str_replace($search, $replace, $cad)));
        return $txt;
    }
    
    public static function validarCedulaEcuador($cedula)
    {
        if (strlen($cedula) == 10)
        {
            $total = 0;
            $longitud = strlen($cedula);
            $longcheck = $longitud - 1;

            for($i = 0; $i < $longcheck; $i++)
            {
                if ($i%2 == 0)
                {
                    $aux = substr($cedula,$i,1) * 2;
                    if ($aux > 9) $aux -= 9;
                    $total += $aux;
                }
                else
                {
                    $total += (int)(substr($cedula,$i,1));
                }
            }
            
            $total = $total % 10 ? 10 - $total % 10 : 0;
            
            if (substr($cedula,-1,1) == $total)
            {
                return "Cedula Correcta";
            }
            else
            {
                return "Cedula Erronea";
            }
        }
        else
        {
            return "Cedula Erronea";
        }
    }
    
    public static function validarCorreo($correo)
    {
      return (false !== strpos($correo, "@") && false !== strpos($correo, "."));
    }
    
    public static function convertirFechaTexto($fecha,$tipo='')
    {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        switch ($tipo) {
            case 'periodo':
                return "Período ".$nombreMes." de ".$anio;
                break;
            default:
                return $numeroDia." de ".$nombreMes." de ".$anio;
                break;
        }
    }
    
    public static function getDistribuciones($param=array())
    {
        /*fin40040id/documento*/
        switch ($param['fin40040id']) {
            case 'FC':
                //Inicializar Variables
                $documento = new \Entidades\Cp10200($param['adapter']);
                $detalle = new Entidades\Cp10300($param['adapter']);
                $valrecibimiento = new \Models\Cp10300Model($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $modal_asiento = new Models\Fin20100Model($param['adapter']);
                $inventario = new \Models\Inv00000Model($param['adapter']);
                $cuentaInventario = new Models\Inv00011Model($param['adapter']);
                
                //Datos PROVEEDOR
                $proveedor = new \Entidades\Cp00000($param['adapter']);
                
                $dtproveedor = $documento->getMulti('documento', $param['documento']);
                $dtprove = $proveedor->getMulti('codigo', $dtproveedor['cp00000id']);
                
                //Cuentas Match de Impuestos
                $planes = new \Models\Fin40080Model($param['adapter']);

                //Cuenta Proveedores
                $cuenta = new Models\Cp00011Model($param['adapter']);
                
                //Cuenta Inventario
                $dtdetalle = $detalle->getMulti('documento', $param['documento']);

                if (globalFunctions::es_bidimensional($dtdetalle))
                {
                    $cuenta1 = 0;
                    $cuenta2 = 0;
                    $cuenta3 = 0;
                    $cuenta4 = 0;
                    $cuenta5 = 0;
                    $cuenta6 = 0;
                    foreach ($dtdetalle as $value)
                    {
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                        $dtmatch = $planes->getMatchImpuesto($dtprove['fin40060id'],$producto['fin40060idcompras']);

                        if (globalFunctions::es_bidimensional($dtmatch) && $dtproveedor['cp40000id']!='04')
                        {
                            foreach ($dtmatch as $matchValue)
                            {
                                //Sacamos la cuenta
                                $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                                $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);

                                //Validar porcentaje positivo o negativo
                                if ($dtcuentaImpuesto['porcentaje']>0)
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FC', $cuentaBasadoEn['fin00000id']);
                                        
                                        if(!isset($dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']])) {
                                            $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']] = 0;
                                        }
                                        
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            if ($dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]==0)
                                            {
                                                $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            }
                                        }
                                        else if ($existeCuenta_anterior['numrows']==0)
                                        {
                                            $asiento->setModulo(4);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                            $asiento->save();
                                            $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                    }
                                    else
                                    {
                                        if(!isset($dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']])) {
                                            $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']] = 0;
                                        }
                                        
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            if ($dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]==0)
                                            {
                                                $asiento->updateMultiColum('debito', number_format((float)((($value['costo_unitario']*$value['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+((($value['costo_unitario']*$value['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            }
                                        }
                                        else if ($existeCuenta_anterior['numrows']==0)
                                        {
                                            $asiento->setModulo(4);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(number_format((float)((($value['costo_unitario']*$value['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''));
                                            $asiento->save();
                                            $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                    }
                                }
                                else
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FC', $cuentaBasadoEn['fin00000id']);
                                        
                                        if(!isset($dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']])) {
                                            $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']] = 0;
                                        }
                                        
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            if ($dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]==0)
                                            {
                                                $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            }
                                        }
                                        else if ($existeCuenta_anterior['numrows']==0)
                                        {
                                            $asiento->setModulo(4);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                            $asiento->save();
                                            $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                    }
                                    else
                                    {
                                        if(!isset($dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']])) {
                                            $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']] = 0;
                                        }
                                        
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            if ($dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]==0)
                                            {
                                                $asiento->updateMultiColum('credito', number_format((float)(((($value['costo_unitario']*$value['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+((($value['costo_unitario']*$value['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            }
                                        }
                                        else if ($existeCuenta_anterior['numrows']==0)
                                        {
                                            $asiento->setModulo(4);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito(number_format((float)((($value['costo_unitario']*$value['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''));
                                            $asiento->save();
                                            $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                    }
                                }
                            }
                        }

                        //Tiene Recibimiento
                        $tiene_rcb = $valrecibimiento->valRecibimiento($param['documento'],$param['empresa']);
                        if ($tiene_rcb['numrows']>0)//Tiene Recibimiento
                        {
                            $dtcuenta = $cuenta->getCuentaProveedor($dtproveedor['cp00000id'], 2);
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                            $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                            if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                if ($cuenta5==0)
                                {
                                    $asiento->updateMultiColum('debito', number_format((float)(($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $cuenta5++;
                                }
                                else
                                {
                                    $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                }
                            }
                            else if ($existeCuenta_anterior['numrows']==0)
                            {
                                $asiento->setModulo(4);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('FC');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''));
                                $asiento->save();
                                $cuenta5++;
                            }
                        }
                        else
                        {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                            $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                            if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                if ($cuenta6==0)
                                {
                                    $asiento->updateMultiColum('debito', number_format((float)(($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $cuenta6++;
                                }
                                else
                                {
                                    $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                }
                            }
                            else if ($existeCuenta_anterior['numrows']==0)
                            {
                                $asiento->setModulo(4);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('FC');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''));
                                $asiento->save();
                                $cuenta6++;
                            }
                        }
                    }
                }
                else if (isset($dtdetalle['id']))
                {
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                    $dtmatch = $planes->getMatchImpuesto($dtprove['fin40060id'],$producto['fin40060idcompras']);
                    
                    $cuenta1 = 0;
                    $cuenta2 = 0;
                    $cuenta3 = 0;
                    $cuenta4 = 0;
                    $cuenta5 = 0;
                    $cuenta6 = 0;
                    if (globalFunctions::es_bidimensional($dtmatch) && $dtproveedor['cp40000id']!='04')
                    {
                        foreach ($dtmatch as $matchValue)
                        {
                            //Sacamos la cuenta
                            $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                            $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);

                            //Validar porcentaje positivo o negativo
                            if ($dtcuentaImpuesto['porcentaje']>0)
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FC', $cuentaBasadoEn['fin00000id']);
                                    
                                    if(!isset($dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']])) {
                                        $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']] = 0;
                                    }
                                    
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]==0)
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        }
                                    }
                                    else if ($existeCuenta_anterior['numrows']==0)
                                    {
                                        $asiento->setModulo(4);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                        $asiento->save();
                                        $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]++;
                                    }
                                }
                                else
                                {
                                    if(!isset($dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']])) {
                                        $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']] = 0;
                                    }
                                    
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]==0)
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        }
                                    }
                                    else if ($existeCuenta_anterior['numrows']==0)
                                    {
                                        if (number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', '') > 0) {
                                            $asiento->setModulo(4);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                                            $asiento->save();
                                            $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FC', $cuentaBasadoEn['fin00000id']);
                                    
                                    if(!isset($dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']])) {
                                        $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']] = 0;
                                    }
                                    
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]==0)
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        }
                                    }
                                    else if ($existeCuenta_anterior['numrows']==0)
                                    {
                                        $asiento->setModulo(4);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                        $asiento->save();
                                        $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]++;
                                    }
                                }
                                else
                                {
                                    if(!isset($dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']])) {
                                        $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']] = 0;
                                    }
                                    
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]==0)
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)(((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        }
                                    }
                                    else if ($existeCuenta_anterior['numrows']==0)
                                    {
                                        if (number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', '') > 0) {
                                            $asiento->setModulo(4);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito(number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                                            $asiento->save();
                                            $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else if (isset($dtmatch['fin40070id']) && $dtproveedor['cp40000id']!='04')
                    {
                        //Sacamos la cuenta
                        $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                        $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($dtmatch['fin40070id']);

                        //Validar porcentaje positivo o negativo
                        if ($dtcuentaImpuesto['porcentaje']>0)
                        {
                            //ver si tiene basado en
                            if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                            {
                                //Sacar el valor de la cuenta
                                $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FC', $cuentaBasadoEn['fin00000id']);
                                
                                if(!isset($dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']])) {
                                    $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']] = 0;
                                }
                                
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    if ($dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]==0)
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    }
                                }
                                else if ($existeCuenta_anterior['numrows']==0)
                                {
                                    $asiento->setModulo(4);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('FC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                    $asiento->save();
                                    $dtiCuentaImp1[$dtcuentaImpuesto['fin00000id']]++;
                                }
                            }
                            else
                            {
                                if(!isset($dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']])) {
                                    $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']] = 0;
                                }
                                
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    if ($dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]==0)
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    }
                                }
                                else if ($existeCuenta_anterior['numrows']==0)
                                {
                                    if (number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', '') > 0) {
                                        $asiento->setModulo(4);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                                        $asiento->save();
                                        $dtiCuentaImp2[$dtcuentaImpuesto['fin00000id']]++;
                                    }
                                }
                            }
                        }
                        else
                        {
                            if ($dtcuentaImpuesto['porcentaje'] < 0) {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FC', $cuentaBasadoEn['fin00000id']);

                                    if(!isset($dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']])) {
                                        $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']] = 0;
                                    }

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]==0)
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        }
                                    }
                                    else if ($existeCuenta_anterior['numrows']==0)
                                    {
                                        if (number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', '') > 0) {
                                            $asiento->setModulo(4);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                            $asiento->setFin40070id($dtmatch['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                            $asiento->save();
                                            $dtiCuentaImp3[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                    }
                                }
                                else
                                {
                                    if(!isset($dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']])) {
                                        $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']] = 0;
                                    }

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        if ($dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]==0)
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)(((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                        }
                                    }
                                    else if ($existeCuenta_anterior['numrows']==0)
                                    {
                                        $asiento->setModulo(4);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(number_format((float)((($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                                        $asiento->save();
                                        $dtiCuentaImp4[$dtcuentaImpuesto['fin00000id']]++;
                                    }
                                }
                            }
                        }
                    }
                    
                    //Tiene Recibimiento
                    $tiene_rcb = $valrecibimiento->valRecibimiento($param['documento'],$param['empresa']);

                    if ($tiene_rcb['numrows']>0)//Tiene Recibimiento
                    {
                        $dtcuenta = $cuenta->getCuentaProveedor($dtproveedor['cp00000id'], 2);
                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                        $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                        if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                            if ($cuenta5==0)
                            {
                                $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                $cuenta5++;
                            }
                            else
                            {
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                            }
                        }
                        else if ($existeCuenta_anterior['numrows']==0)
                        {
                            $asiento->setModulo(4);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('FC');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                            $asiento->save();
                            $cuenta5++;
                        }
                    }
                    else
                    {
                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                        $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                        if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                            if ($cuenta6==0)
                            {
                                $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                $cuenta6++;
                            }
                            else
                            {
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                            }
                        }
                        else if ($existeCuenta_anterior['numrows']==0)
                        {
                            $asiento->setModulo(4);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('FC');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                            $asiento->save();
                            $cuenta6++;
                        }
                    }
                }
                
                //Agregar cuenta de Proveedores Nacionales
                $dtcuenta = $cuenta->getCuentaProveedor($dtproveedor['cp00000id'], 1);
                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                $proveedoresNacionales = $modal_asiento->getDiferenciaDistribucion($param['documento'], 'FC',$dtcuenta['fin00000id']);
                $debito=0;
                if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                {
                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                    if ($debito==0)
                    {
                        $asiento->updateMultiColum('credito', number_format((float)($proveedoresNacionales['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                        $asiento->updateMultiColum('baseimponible', number_format((float)($proveedoresNacionales['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                        $debito++;
                    }
                    else
                    {
                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+$proveedoresNacionales['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                        $asiento->updateMultiColum('baseimponible', number_format((float)($proveedoresNacionales['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                    }
                }
                else if ($existeCuenta_anterior['numrows']==0)
                {
                    $asiento->setModulo(4);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('FC');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setFin40070id('');
                    $asiento->setDebito(0);
                    $asiento->setCredito(number_format((float)($proveedoresNacionales['total']), 2, '.', ''));
                    $asiento->setBaseimponible(number_format((float)($proveedoresNacionales['total']), 2, '.', ''));
                    $asiento->save();
                    $debito++;
                }
                
                break;
            case 'DC':
                //Inicializar Variables
                $documento = new \Entidades\Cp10210($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $modal_asiento = new Models\Fin20100Model($param['adapter']);
                $cuentaInventario = new Models\Inv00011Model($param['adapter']);
                
                //Datos PROVEEDOR
                $proveedor = new \Entidades\Cp00000($param['adapter']);
                
                $dtdocumento = $documento->getMulti('documento', $param['documento']);
                $dtprove = $proveedor->getMulti('codigo', $dtdocumento['cp00000id']);

                //Cuentas Match de Impuestos
                $planes = new \Models\Fin40080Model($param['adapter']);

                //Cuenta Proveedores
                $cuenta = new Models\Cp00011Model($param['adapter']);

                $cuenta1 = 0;
                $cuenta2 = 0;
                $cuenta3 = 0;
                $cuenta4 = 0;
                $cuenta5 = 0;
                $cuenta6 = 0;
                
                //Poner todos las Retenciones de IVA y RENTA
                $dtprove = $proveedor->getMulti('codigo', $dtprove['codigo']);
                $dtmatch = $planes->getMatchImpuesto($dtprove['fin40060id'],$dtdocumento['fin40060id']);

                if (globalFunctions::es_bidimensional($dtmatch) && $dtdocumento['cp40000id']!='04')
                {
                    foreach ($dtmatch as $matchValue)
                    {
                        //Sacamos la cuenta
                        $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                        $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);

                        //Validar porcentaje positivo o negativo
                        if ($dtcuentaImpuesto['porcentaje']>0)
                        {
                            //ver si tiene basado en
                            if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                            {
                                //Sacar el valor de la cuenta
                                $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'DC', $cuentaBasadoEn['fin00000id']);

                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    if ($cuenta1==0)
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $cuenta1++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    }
                                }
                                else if ($existeCuenta_anterior['numrows']==0)
                                {
                                    $asiento->setModulo(4);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('DC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id($matchValue['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta1++;
                                }
                            }
                            else
                            {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    if ($cuenta2==0)
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtdocumento['subtotal']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $cuenta2++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtdocumento['subtotal'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    }
                                }
                                else if ($existeCuenta_anterior['numrows']==0)
                                {
                                    $asiento->setModulo(4);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('DC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(number_format((float)((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id($matchValue['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtdocumento['subtotal']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta2++;
                                }
                            }
                        }
                        else
                        {
                            //ver si tiene basado en
                            if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                            {
                                //Sacar el valor de la cuenta
                                $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'DC', $cuentaBasadoEn['fin00000id']);

                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    if ($cuenta3==0)
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $cuenta3++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    }
                                }
                                else if ($existeCuenta_anterior['numrows']==0)
                                {
                                    $asiento->setModulo(4);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('DC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                    $asiento->setFin40070id($matchValue['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta3++;
                                }
                            }
                            else
                            {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    if ($cuenta4==0)
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)(((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtdocumento['subtotal']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $cuenta4++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtdocumento['subtotal'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    }
                                }
                                else if ($existeCuenta_anterior['numrows']==0)
                                {
                                    $asiento->setModulo(4);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('DC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(number_format((float)((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                    $asiento->setFin40070id($matchValue['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtdocumento['subtotal']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta4++;
                                }
                            }
                        }
                    }
                }
                else if (isset($dtmatch['fin40070id']) && $dtdocumento['cp40000id']!='04')
                {
                    //Sacamos la cuenta
                    $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                    $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($dtmatch['fin40070id']);

                    //Validar porcentaje positivo o negativo
                    if ($dtcuentaImpuesto['porcentaje']>0)
                    {
                        //ver si tiene basado en
                        if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                        {
                            //Sacar el valor de la cuenta
                            $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                            $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'DC', $cuentaBasadoEn['fin00000id']);

                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                if ($cuenta1==0)
                                {
                                    $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $cuenta1++;
                                }
                                else
                                {
                                    $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                }
                            }
                            else if ($existeCuenta_anterior['numrows']==0)
                            {
                                $asiento->setModulo(4);
                                $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                $asiento->setFin40040id('DC');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id($dtmatch['fin40070id']);
                                $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                $asiento->save();
                                $cuenta1++;
                            }
                        }
                        else
                        {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                if ($cuenta2==0)
                                {
                                    $asiento->updateMultiColum('debito', number_format((float)((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtdocumento['subtotal']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $cuenta2++;
                                }
                                else
                                {
                                    $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtdocumento['subtotal'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                }
                            }
                            else if ($existeCuenta_anterior['numrows']==0)
                            {
                                $asiento->setModulo(4);
                                $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                $asiento->setFin40040id('DC');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id($dtmatch['fin40070id']);
                                $asiento->setBaseimponible(number_format((float)($dtdocumento['subtotal']), 2, '.', ''));
                                $asiento->save();
                                $cuenta2++;
                            }
                        }
                    }
                    else
                    {
                        //ver si tiene basado en
                        if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                        {
                            //Sacar el valor de la cuenta
                            $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                            $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'DC', $cuentaBasadoEn['fin00000id']);

                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                if ($cuenta3==0)
                                {
                                    $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $cuenta3++;
                                }
                                else
                                {
                                    $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                }
                            }
                            else if ($existeCuenta_anterior['numrows']==0)
                            {
                                $asiento->setModulo(4);
                                $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                $asiento->setFin40040id('DC');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(0);
                                $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                $asiento->setFin40070id($dtmatch['fin40070id']);
                                $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                $asiento->save();
                                $cuenta3++;
                            }
                        }
                        else
                        {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                if ($cuenta4==0)
                                {
                                    $asiento->updateMultiColum('credito', number_format((float)(((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtdocumento['subtotal']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $cuenta4++;
                                }
                                else
                                {
                                    $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtdocumento['subtotal'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                                }
                            }
                            else if ($existeCuenta_anterior['numrows']==0)
                            {
                                $asiento->setModulo(4);
                                $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                $asiento->setFin40040id('DC');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(0);
                                $asiento->setCredito(number_format((float)((($dtdocumento['subtotal'])*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                $asiento->setFin40070id($dtmatch['fin40070id']);
                                $asiento->setBaseimponible(number_format((float)($dtdocumento['subtotal']), 2, '.', ''));
                                $asiento->save();
                                $cuenta4++;
                            }
                        }
                    }
                }
                
                if ($dtdocumento['cp40000id']!='04') {
                    //Agregar cuenta de Proveedores Nacionales
                    $dtcuenta = $cuenta->getCuentaProveedor($dtdocumento['cp00000id'], 1);
                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    $monto_renta = $modal_asiento->getRetenciones($param['documento'],'DC');

                    $debito=0;
                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        if ($debito==0)
                        {
                            $asiento->updateMultiColum('credito', number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $debito++;
                        }
                        else
                        {
                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+$dtdocumento['total']-$monto_renta['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        }
                    }
                    else if ($existeCuenta_anterior['numrows']==0)
                    {
                        $asiento->setModulo(4);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('DC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setFin40070id('');
                        $asiento->setDebito(0);
                        $asiento->setCredito(number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''));
                        $asiento->setBaseimponible(number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''));
                        $asiento->save();
                        $debito++;
                    }

                    //Poner en el debito la diferencia de valores
                    //Cuenta de Articulo
                    $dtcuenta = $cuentaInventario->getCuentaProductoId($dtdocumento['inv00000id'], 1);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    $proveedoresNacionales = $modal_asiento->getDiferenciaDistribucion($param['documento'], 'DC',$dtcuenta['fin00000id']);
                    $total_proveedores = $proveedoresNacionales['total']>0?$proveedoresNacionales['total']:$proveedoresNacionales['total']*(-1);
                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    $debito=0;
                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        if ($debito==0)
                        {
                            $asiento->updateMultiColum('debito', number_format((float)($total_proveedores), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($total_proveedores), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $debito++;
                        }
                        else
                        {
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+$total_proveedores), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($total_proveedores), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        }
                    }
                    else if ($existeCuenta_anterior['numrows']==0)
                    {
                        $asiento->setModulo(4);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('DC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setFin40070id('');
                        $asiento->setDebito(number_format((float)($total_proveedores), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setBaseimponible(number_format((float)($total_proveedores), 2, '.', ''));
                        $asiento->save();
                        $debito++;
                    }

                    //Si no tiene documento agregar el iva
                    if ($dtdocumento['inv00000id'] == '-1') {
                        $cuadrado = $modal_asiento->getDiferenciaDistribucion($param['documento'], 'DC', 0);
                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', 100000, 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        if ($cuadrado['total']<>0 && $existeCuenta['numrows']==0) {
                            $valorcuadre = 0;
                            if ($dtdocumento['impuesto'] < 0) {
                                $valorcuadre = $dtdocumento['impuesto']*(-1);
                            }
                            else {
                                $valorcuadre = $dtdocumento['impuesto'];
                            }

                            $asiento->setModulo(4);
                            $asiento->setFin00000id(100000);
                            $asiento->setFin40040id('DC');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setFin40070id('');
                            $asiento->setDebito(number_format((float)($valorcuadre), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setBaseimponible(number_format((float)($valorcuadre), 2, '.', ''));
                            $asiento->save();
                        }
                    }

                    //SI no esta cuadrada poner en el debito o credito el monto con cuenta 0
                    $cuadrado = $modal_asiento->getDiferenciaDistribucion($param['documento'], 'DC', 0);
                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', 0, 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    if ($cuadrado['total']<>0 && $existeCuenta['numrows']==0) {
                        $valorcuadre = 0;
                        if ($cuadrado['total'] < 0) {
                            $valorcuadre = $cuadrado['total']*(-1);
                        }
                        else {
                            $valorcuadre = $cuadrado['total'];
                        }

                        $asiento->setModulo(4);
                        $asiento->setFin00000id(0);
                        $asiento->setFin40040id('DC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setFin40070id('');
                        $asiento->setDebito(number_format((float)($valorcuadre), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setBaseimponible(number_format((float)($valorcuadre), 2, '.', ''));
                        $asiento->save();
                    }
                }
                else {
                    //Agregar cuenta de Proveedores Nacionales
                    $dtcuenta = $cuenta->getCuentaProveedor($dtdocumento['cp00000id'], 1);
                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    $monto_renta = $modal_asiento->getRetenciones($param['documento'],'DC');

                    $debito=0;
                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        if ($debito==0)
                        {
                            $asiento->updateMultiColum('debito', number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $debito++;
                        }
                        else
                        {
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+$dtdocumento['total']-$monto_renta['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        }
                    }
                    else if ($existeCuenta_anterior['numrows']==0)
                    {
                        $asiento->setModulo(4);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('DC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setFin40070id('');
                        $asiento->setDebito(number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setBaseimponible(number_format((float)($dtdocumento['total']-$monto_renta['total']), 2, '.', ''));
                        $asiento->save();
                        $debito++;
                    }

                    //Poner en el debito la diferencia de valores
                    //Cuenta de Articulo
                    $dtcuenta = $cuentaInventario->getCuentaProductoId($dtdocumento['inv00000id'], 1);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    $proveedoresNacionales = $modal_asiento->getDiferenciaDistribucion($param['documento'], 'DC',$dtcuenta['fin00000id']);
                    $total_proveedores = $proveedoresNacionales['total']>0?$proveedoresNacionales['total']:$proveedoresNacionales['total']*(-1);
                    $existeCuenta_anterior = $asiento->getCountMulti('id', 'fin00000id_anterior', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    $debito=0;
                    if ($existeCuenta['numrows']>0 && $existeCuenta_anterior['numrows']==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        if ($debito==0)
                        {
                            $asiento->updateMultiColum('credito', number_format((float)($total_proveedores), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($total_proveedores), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $debito++;
                        }
                        else
                        {
                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['debito']+$total_proveedores), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($total_proveedores), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        }
                    }
                    else if ($existeCuenta_anterior['numrows']==0)
                    {
                        $asiento->setModulo(4);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('DC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setFin40070id('');
                        $asiento->setDebito(0);
                        $asiento->setCredito(number_format((float)($total_proveedores), 2, '.', ''));
                        $asiento->setBaseimponible(number_format((float)($total_proveedores), 2, '.', ''));
                        $asiento->save();
                        $debito++;
                    }

                    //Si no tiene documento agregar el iva
                    if ($dtdocumento['inv00000id'] == '-1') {
                        $cuadrado = $modal_asiento->getDiferenciaDistribucion($param['documento'], 'DC', 0);
                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', 100000, 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                        if ($cuadrado['total']<>0 && $existeCuenta['numrows']==0) {
                            $valorcuadre = 0;
                            if ($dtdocumento['impuesto'] < 0) {
                                $valorcuadre = $dtdocumento['impuesto']*(-1);
                            }
                            else {
                                $valorcuadre = $dtdocumento['impuesto'];
                            }

                            $asiento->setModulo(4);
                            $asiento->setFin00000id(100000);
                            $asiento->setFin40040id('DC');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setFin40070id('');
                            $asiento->setDebito(0);
                            $asiento->setCredito(number_format((float)($valorcuadre), 2, '.', ''));
                            $asiento->setBaseimponible(number_format((float)($valorcuadre), 2, '.', ''));
                            $asiento->save();
                        }
                    }

                    //SI no esta cuadrada poner en el debito o credito el monto con cuenta 0
                    $cuadrado = $modal_asiento->getDiferenciaDistribucion($param['documento'], 'DC', 0);
                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', 0, 'documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                    if ($cuadrado['total']<>0 && $existeCuenta['numrows']==0) {
                        $valorcuadre = 0;
                        if ($cuadrado['total'] < 0) {
                            $valorcuadre = $cuadrado['total']*(-1);
                        }
                        else {
                            $valorcuadre = $cuadrado['total'];
                        }

                        $asiento->setModulo(4);
                        $asiento->setFin00000id(0);
                        $asiento->setFin40040id('DC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setFin40070id('');
                        $asiento->setDebito(0);
                        $asiento->setCredito(number_format((float)($valorcuadre), 2, '.', ''));
                        $asiento->setBaseimponible(number_format((float)($valorcuadre), 2, '.', ''));
                        $asiento->save();
                    }
                }
                break;
            case 'ERCB':
                //Inicializar Variables
                $documento = new \Entidades\Com30200($param['adapter']);
                $detalle = new Entidades\Com30300($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $inventario = new \Models\Inv00000Model($param['adapter']);
                $cuentaInventario = new Models\Inv00011Model($param['adapter']);
                //Datos PROVEEDOR
                $proveedor = new \Entidades\Cp00000($param['adapter']);
                $dtproveedor = $documento->getMulti('documento', $param['documento']);
                //Cuenta Proveedores
                $cuenta = new Models\Cp00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaProveedor($dtproveedor['cp00000id'], 2);
                
                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                if ($existeCuenta['numrows']>0)
                {
                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                    $asiento->updateMultiColum('credito', number_format((float)($dtproveedor['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                    $asiento->updateMultiColum('baseimponible', number_format((float)(0), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                }
                else
                {
                    $asiento->setModulo(4);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('ERCB');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(0);
                    $asiento->setCredito(number_format((float)($dtproveedor['total']), 2, '.', ''));
                    $asiento->setBaseimponible(0);
                    $asiento->setFin40070id('');
                    $asiento->save();
                }
                //Cuenta Inventario
                $dtdetalle = $detalle->getMulti('documento', $param['documento']);
                
                if (globalFunctions::es_bidimensional($dtdetalle))
                {
                    $debito = 0;
                    foreach ($dtdetalle as $value)
                    {
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);
                        
                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                        if ($existeCuenta['numrows']>0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            if ($debito==0)
                            {
                                $asiento->updateMultiColum('debito', number_format((float)(($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                                $asiento->updateMultiColum('baseimponible', number_format((float)(0), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                                $debito++;
                            }
                            else
                            {
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo_unitario']*$value['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                                $asiento->updateMultiColum('baseimponible', number_format((float)(0), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            }
                        }
                        else
                        {
                            $asiento->setModulo(4);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('ERCB');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($value['costo_unitario']*$value['cantidad_recibida']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $debito++;
                        }
                    }
                }
                else if (isset($dtdetalle['id']))
                {
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);
                    
                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                    if ($existeCuenta['numrows']>0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                        $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                        $asiento->updateMultiColum('baseimponible', number_format((float)(0), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                    }
                    else
                    {
                        $asiento->setModulo(4);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('ERCB');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(number_format((float)($dtdetalle['costo_unitario']*$dtdetalle['cantidad_recibida']), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(number_format((float)(0), 2, '.', ''));
                        $asiento->save();
                    }
                }
                
                //Validar si tiene costo de descarga
                $costo_descarga = new \Entidades\Com20000($param['adapter']);
                $dtcosto_descarga = $costo_descarga->getCountMulti('id', 'documento', $param['documento']);
                if ($dtcosto_descarga['numrows']>0)
                {
                    //Recorremos todos los costos de descarga mayores a 0
                    $dtcosto_descarga = $costo_descarga->getMulti('documento', $param['documento']);
                    if (globalFunctions::es_bidimensional($dtcosto_descarga))
                    {
                        foreach ($dtcosto_descarga as $value_descarga) {
                            //Sacamos la cuenta contable   
                            $cuenta_costo_descarga = new \Entidades\Com40000($param['adapter']);
                            $dtcuenta_costo_descarga = $cuenta_costo_descarga->getMulti('id', $value_descarga['com40000id']);
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta_costo_descarga['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            if ($existeCuenta['numrows']>0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta_costo_descarga['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+$value_descarga['monto']), 2, '.', ''), 'fin00000id', $dtcuenta_costo_descarga['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            }
                            else
                            {
                                $asiento->setModulo(4);
                                $asiento->setFin00000id($dtcuenta_costo_descarga['fin00000id']);
                                $asiento->setFin40040id('ERCB');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(0);
                                $asiento->setCredito(number_format((float)($value_descarga['monto']), 2, '.', ''));
                                $asiento->setBaseimponible(0);
                                $asiento->setFin40070id('');
                                $asiento->save();
                            }
                            $dtdetalle = $detalle->getByTop1('documento', $param['documento']);
                            
                            //Agregamos el valos de productos
                            $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                            $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            if ($existeCuenta['numrows']>0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value_descarga['monto'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                                $asiento->updateMultiColum('baseimponible', number_format((float)(0), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            }
                            else
                            {
                                $asiento->setModulo(4);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('ERCB');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)($value_descarga['monto']), 2, '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(number_format((float)(0), 2, '.', ''));
                                $asiento->save();
                            }
                        }
                    }
                    else if (isset($dtcosto_descarga['id']))
                    {
                        //Sacamos la cuenta contable   
                        $cuenta_costo_descarga = new \Entidades\Com40000($param['adapter']);
                        $dtcuenta_costo_descarga = $cuenta_costo_descarga->getMulti('id', $dtcosto_descarga['com40000id']);
                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta_costo_descarga['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                        if ($existeCuenta['numrows']>0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta_costo_descarga['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+$dtcosto_descarga['monto']), 2, '.', ''), 'fin00000id', $dtcuenta_costo_descarga['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                        }
                        else
                        {
                            $asiento->setModulo(4);
                            $asiento->setFin00000id($dtcuenta_costo_descarga['fin00000id']);
                            $asiento->setFin40040id('ERCB');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(0);
                            $asiento->setCredito(number_format((float)($dtcosto_descarga['monto']), 2, '.', ''));
                            $asiento->setBaseimponible(0);
                            $asiento->setFin40070id('');
                            $asiento->save();
                        }
                        $dtdetalle = $detalle->getByTop1('documento', $param['documento']);
                        
                        //Agregamos el valos de productos
                        $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                        if ($existeCuenta['numrows']>0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtcosto_descarga['monto'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                            $asiento->updateMultiColum('baseimponible', number_format((float)(0), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                        }
                        else
                        {
                            $asiento->setModulo(4);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('ERCB');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($dtcosto_descarga['monto']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(number_format((float)(0), 2, '.', ''));
                            $asiento->save();
                        }
                    }
                }
                break;
            case 'FV':
                //Inicializar Variables
                $documento = new \Entidades\Cc10000($param['adapter']);
                $detalle = new Entidades\Cc10010($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $modal_asiento = new Models\Fin20100Model($param['adapter']);
                $inventario = new \Models\Inv00000Model($param['adapter']);
                $cuentaInventario = new Models\Inv00011Model($param['adapter']);
                $cliente = new \Entidades\Cc00000($param['adapter']);
                $planes = new \Models\Fin40080Model($param['adapter']);
                //Datos Cliente
                $dtcliente = $documento->getMulti('id', $param['documento']);
                $dtcli = $cliente->getMulti('codigo', $dtcliente['cc00000id']);
                //Cuenta Clientes
                $cuenta = new Models\Cc00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaCliente($dtcliente['cc00000id'], 1);

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                if ($existeCuenta['numrows']>0)
                {
                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                    $asiento->updateMultiColum('debito', number_format((float)($dtcliente['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtcliente['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                }
                else
                {
                    $asiento->setModulo(3);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('FV');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setCredito(0);
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->save();
                }
                //Cuenta Inventario
                $dtdetalle = $detalle->getMulti('cc10000id', $param['documento']);
                
                $cuentasTipo4 = 0;
                if (globalFunctions::es_bidimensional($dtdetalle))
                {
                    $debito = 0;
                    $cuenta100=0;
                    $cuenta101=0;
                    $cuenta102=0;
                    $cuenta103=0;
                    $cuenta1 = 0;
                    $cuenta2 = 0;
                    $cuenta3 = 0;
                    $cuenta4 = 0;
                    $cuenta5 = 0;
                    $cuenta6 = 0;
                    $cuentasTipo4 = 0;
                    
                    foreach ($dtdetalle as $value)
                    {
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtmatch = $planes->getMatchImpuesto($dtcli['fin40060id'],$producto['fin40060idventas']);
                        
                        if (globalFunctions::es_bidimensional($dtmatch))
                        {
                            foreach ($dtmatch as $matchValue)
                            {
                                //Sacamos la cuenta
                                $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                                $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);
                                
                                //Validar porcentaje positivo o negativo
                                if ($dtcuentaImpuesto['porcentaje']>0)
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FV', $cuentaBasadoEn['fin00000id']);

                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            if ($cuenta1==0)
                                            {
                                                $asiento->updateMultiColum('credito', (($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $cuenta1++;
                                            }
                                            else
                                            {
                                            $asiento->updateMultiColum('credito', ($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito((($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100));
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta1++;
                                        }
                                    }
                                    else
                                    {
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', '');
                                            if ($cuenta2==0)
                                            {
                                                $asiento->updateMultiColum('credito',(($montodebito*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $cuenta2++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('credito', ($dtasiento['credito']+(($montodebito*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            }
                                        }
                                        else
                                        {
                                            $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', '');
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito((($montodebito*$dtcuentaImpuesto['porcentaje'])/100));
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta2++;
                                        }
                                    }
                                }
                                else
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '' )
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FV', $cuentaBasadoEn['fin00000id']);

                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            if ($cuenta3==0)
                                            {
                                                $asiento->updateMultiColum('debito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $cuenta3++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta3++;
                                        }
                                    }
                                    else
                                    {
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', '');
                                            if ($cuenta4==0)
                                            {
                                                $asiento->updateMultiColum('debito', ((($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $cuenta4++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            }
                                        }
                                        else
                                        {
                                            $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', '');
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('FV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito((($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta4++;
                                        }
                                    }
                                }
                            }
                        }
                        else if(isset($dtmatch['fin40070id']))
                        {    
                            //Sacamos la cuenta
                            $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                            $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($dtmatch['fin40070id']);
                            
                            //Validar porcentaje positivo o negativo
                            if ($dtcuentaImpuesto['porcentaje']>0)
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '' )
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FV', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        if ($cuenta1==0)
                                        {
                                            $asiento->updateMultiColum('credito', (($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta1++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', ($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100));
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta1++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                        if ($cuenta2==0)
                                        {
                                            $asiento->updateMultiColum('credito', (($montodebito*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta2++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', $dtasiento['credito']+(($montodebito*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }
                                    }
                                    else
                                    {
                                        $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito((($montodebito*$dtcuentaImpuesto['porcentaje'])/100));
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta2++;
                                    }
                                }
                            }
                            else
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FV', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        if ($cuenta3==0)
                                        {
                                            $asiento->updateMultiColum('debito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta3++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta3++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                        if ($cuenta4==0)
                                        {
                                            $asiento->updateMultiColum('debito', ((($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta4++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }
                                    }
                                    else
                                    {
                                        $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100);
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta4++;
                                    }
                                }
                            }
                        }
                        
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);
                        
                        if (isset($dtcuenta['fin00000id']))
                        {
                            if ($dtcuenta['fin00000id'] !== '-1') {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    if ($cuenta100 == 0)
                                    {
                                        if (number_format((float)(($value['costo']*$value['cantidad'])), $_SESSION['DTI_DECIMALVEN'], '.', '') > 0) {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('credito', number_format((float)(($value['costo']*$value['cantidad'])), $_SESSION['DTI_DECIMALVEN'], '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta100++;
                                        }
                                    }
                                    else
                                    {
                                        if (number_format((float)(($value['costo']*$value['cantidad'])), $_SESSION['DTI_DECIMALVEN'], '.', '') > 0) {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('credito', number_format((float)(($dtasiento['credito']+($value['costo']*$value['cantidad']))), $_SESSION['DTI_DECIMALVEN'], '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }                                    }
                                }
                                else
                                {
                                    if (number_format((float)(($value['costo']*$value['cantidad'])), $_SESSION['DTI_DECIMALVEN'], '.', '') > 0) {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(number_format((float)($value['costo']*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', ''));
                                        $asiento->setFin40070id('');
                                        $asiento->setBaseimponible(0);
                                        $asiento->save();
                                        $cuenta100++;
                                    }
                                }
                            }
                        }
                        
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 3);

                        if (isset($dtcuenta['fin00000id']))
                        {
                            if ($dtcuenta['fin00000id'] !== '-1') {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    if ($cuenta101==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('debito', number_format((float)(($value['costo']*$value['cantidad'])), $_SESSION['DTI_DECIMALVEN'], '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $cuenta101++;
                                    }
                                    else
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo']*$value['cantidad'])), $_SESSION['DTI_DECIMALVEN'], '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    }
                                }
                                else
                                {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                                    $asiento->setFin40040id('FV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(number_format((float)($value['costo']*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', ''));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id('');
                                    $asiento->setBaseimponible(0);
                                    $asiento->save();
                                    $cuenta101++;
                                }
                            }
                        }
                        
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 4);

                        if (isset($dtcuenta['fin00000id']))
                        {
                            if ($dtcuenta['fin00000id'] !== '-1') {
                                
                                if(!isset($dtiCuenta4[$dtcuenta['fin00000id']])) {
                                    $dtiCuenta4[$dtcuenta['fin00000id']] = 0;
                                    $cuentasTipo4++;
                                }
                                
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $montodebito = number_format((float)(($value['precio'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                    if ($dtiCuenta4[$dtcuenta['fin00000id']]==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('credito', (($montodebito)), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $dtiCuenta4[$dtcuenta['fin00000id']]++;
                                    }
                                    else
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('credito', ($dtasiento['credito']+($montodebito)), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    }
                                }
                                else
                                {
                                    $montodebito = number_format((float)(($value['precio'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                                    $asiento->setFin40040id('FV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(($montodebito));
                                    $asiento->setFin40070id('');
                                    $asiento->setBaseimponible(0);
                                    $asiento->save();
                                    $dtiCuenta4[$dtcuenta['fin00000id']]++;
                                }
                            }
                        }
                        
                        if ($value['descuento'] > 0)
                        {
                            $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 5);

                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                            if ($existeCuenta['numrows']>0)
                            {
                                $montodebito = number_format((float)(($value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                if ($cuenta103==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    $asiento->updateMultiColum('debito', $montodebito, 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    $cuenta103++;
                                }
                                else
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    $asiento->updateMultiColum('debito', $dtasiento['debito']+($montodebito), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                }
                            }
                            else
                            {
                                $montodebito = number_format((float)(($value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                $asiento->setModulo(3);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('FV');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito($montodebito);
                                $asiento->setCredito(0);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                                $cuenta103++;
                            }
                        }
                    }
                }
                else if (isset($dtdetalle['id']))
                {
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtmatch = $planes->getMatchImpuesto($dtcli['fin40060id'],$producto['fin40060idventas']);
                        
                    $cuenta1 = 0;
                    $cuenta2 = 0;
                    $cuenta3 = 0;
                    $cuenta4 = 0;
                    $cuenta5 = 0;
                    $cuenta6 = 0;
                    $cuentasTipo4 = 0;
                    
                    if (globalFunctions::es_bidimensional($dtmatch))
                    {
                        foreach ($dtmatch as $matchValue)
                        {
                            //Sacamos la cuenta
                            $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                            $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);

                            //Validar porcentaje positivo o negativo
                            if ($dtcuentaImpuesto['porcentaje']>0)
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FV', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        if ($cuenta1==0)
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta1++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtsumasiento['credito'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta1++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', '');
                                        if ($cuenta2==0)
                                        {
                                            $asiento->updateMultiColum('credito', ((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta2++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', ($dtasiento['credito']+((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }
                                    }
                                    else
                                    {
                                        $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', '');
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100));
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta2++;
                                    }
                                }
                            }
                            else
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FV', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        if ($cuenta3==0)
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta3++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtsumasiento['debito'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta3++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', '');
                                        if ($cuenta4==0)
                                        {
                                            $asiento->updateMultiColum('debito', (((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $cuenta4++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', ($dtasiento['debito']+((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        }
                                    }
                                    else
                                    {
                                        $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', '');
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('FV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta4++;
                                    }
                                }
                            }
                        }
                    }
                    else if(isset($dtmatch['fin40070id']))
                    {
                        //Sacamos la cuenta
                        $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                        $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($dtmatch['fin40070id']);

                        //Validar porcentaje positivo o negativo
                        if ($dtcuentaImpuesto['porcentaje']>0)
                        {
                            //ver si tiene basado en
                            if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                            {
                                //Sacar el valor de la cuenta
                                $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FV', $cuentaBasadoEn['fin00000id']);

                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($cuenta1==0)
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $cuenta1++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtsumasiento['credito'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    }
                                }
                                else
                                {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('FV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta1++;
                                }
                            }
                            else {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                    if ($cuenta2==0)
                                    {
                                        $asiento->updateMultiColum('credito', ((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $cuenta2++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('credito', ($dtasiento['credito']+((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    }
                                }
                                else
                                {
                                    $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('FV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100));
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad'])));
                                    $asiento->save();
                                    $cuenta2++;
                                }
                            }
                        }
                        else
                        {
                            //ver si tiene basado en
                            if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                            {
                                //Sacar el valor de la cuenta
                                $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'FV', $cuentaBasadoEn['fin00000id']);

                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    if ($cuenta3==0)
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $cuenta3++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    }
                                }
                                else
                                {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('FV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta3++;
                                }
                            }
                            else
                            {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                    if ($cuenta4==0)
                                    {
                                        $asiento->updateMultiColum('debito', ((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $cuenta4++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('debito',($dtasiento['debito']+((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    }
                                }
                                else
                                {
                                    $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('FV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta4++;
                                }
                            }
                        }
                    }
                    
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);
                    
                    if (isset($dtcuenta['fin00000id']))
                    {
                        if ($dtcuenta['fin00000id'] !== '-1') {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                            if ($existeCuenta['numrows']>0)
                            {
                                if (number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', '') > 0) {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                    $asiento->updateMultiColum('credito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                }
                            }
                            else
                            {
                                if (number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', '') > 0) {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                                    $asiento->setFin40040id('FV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', ''));
                                    $asiento->setFin40070id('');
                                    $asiento->setBaseimponible(0);
                                    $asiento->save();
                                }
                            }
                        }
                    }
                    
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 3);

                    if (isset($dtcuenta['fin00000id']))
                    {
                        if ($dtcuenta['fin00000id'] !== '-1') {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                            if ($existeCuenta['numrows']>0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                            }
                            else
                            {
                                $asiento->setModulo(3);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('FV');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                            }
                        }
                    }
                    
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 4);
                    
                    if (isset($dtcuenta['fin00000id']))
                    {
                        if ($dtcuenta['fin00000id'] !== '-1') {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                            if ($existeCuenta['numrows']>0)
                            {
                                $montodebito = number_format((float)(($dtdetalle['precio'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                $asiento->updateMultiColum('credito', ($montodebito), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                            }
                            else
                            {
                                $montodebito = number_format((float)(($dtdetalle['precio'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                $asiento->setModulo(3);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('FV');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(0);
                                $asiento->setCredito($montodebito);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                            }
                        }
                    }
                    
                    if ($dtdetalle['descuento'] > 0)
                    {
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 5);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                        if ($existeCuenta['numrows']>0)
                        {
                            $montodebito = number_format((float)(($dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                            $asiento->updateMultiColum('debito', $montodebito, 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                        }
                        else
                        {
                            $montodebito = number_format((float)(($dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                            $asiento->setModulo(3);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('FV');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito($montodebito);
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                        }
                    }
                }
                //Normalizar a 2 Decimales
                $normalizar = $asiento->getMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                if (globalFunctions::es_bidimensional($normalizar))
                {
                    foreach ($normalizar as $normali) 
                    {
                        $asiento->updateMultiColum('credito', number_format((float)($normali['credito']), 2, '.', ''), 'id',$normali['id']);
                        $asiento->updateMultiColum('debito', number_format((float)($normali['debito']), 2, '.', ''), 'id',$normali['id']);
                    }
                }
                else if (isset($normalizar['id']))
                {
                    $asiento->updateMultiColum('credito', number_format((float)($normalizar['credito']), 2, '.', ''), 'id',$normalizar['id']);
                    $asiento->updateMultiColum('debito', number_format((float)($normalizar['debito']), 2, '.', ''), 'id',$normalizar['id']);
                }
                //Disminuir el centavo por problema de 2 cuentas en ventas
                if ($cuentasTipo4 > 1) {
                    $cuentaCambiar = 0;
                    //Ver si cuadra o falla por 1 centavo
                    $valdistribucion = new \Models\Fin20100Model($param['adapter']);
                    //Validar las Distribuciones
                    $valordistribucion = $valdistribucion->getDiferenciaDistribucion($param['documento'], 'FV', 0);
                    if ($valordistribucion['total'] == -0.01){
                        //Disminuir un centavo a la segunda opcion
                        foreach ($dtiCuenta4 as $key => $value) {
                            if ($cuentaCambiar == 1) {
                                $normalizar = $asiento->getMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','FV','fin00000id',$key);
                                $asiento->updateMultiColum('credito', number_format((float)($normalizar['credito']+$valordistribucion['total']), 2, '.', ''), 'id',$normalizar['id']);
                            }
                            $cuentaCambiar++;
                        }
                    }
                }
            break;
            case 'PV':
                //Inicializar Variables
                $documento = new \Entidades\Cc10000($param['adapter']);
                $detalle = new Entidades\Cc10010($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $modal_asiento = new Models\Fin20100Model($param['adapter']);
                $inventario = new \Models\Inv00000Model($param['adapter']);
                $cuentaInventario = new Models\Inv00011Model($param['adapter']);
                $cliente = new \Entidades\Cc00000($param['adapter']);
                $planes = new \Models\Fin40080Model($param['adapter']);
                //Datos Cliente
                $dtcliente = $documento->getMulti('id', $param['documento']);
                $dtcli = $cliente->getMulti('codigo', $dtcliente['cc00000id']);
                //Cuenta Clientes
                $cuenta = new Models\Cc00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaCliente($dtcliente['cc00000id'], 1);

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                if ($existeCuenta['numrows']>0)
                {
                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                    $asiento->updateMultiColum('debito', number_format((float)($dtcliente['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtcliente['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                }
                else
                {
                    $asiento->setModulo(3);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('PV');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setCredito(0);
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->save();
                }
                //Cuenta Inventario
                $dtdetalle = $detalle->getMulti('cc10000id', $param['documento']);
                
                //Cuenta de nota de venta
                $generalCC = new \Entidades\Cc40500($param['adapter']);
                $cuentaPV = $generalCC->getMulti('configuracion', 'ID_CUENTA_PV')['valor'];
                
                $credito_iva = 0;
                $baseimponible_iva = 0;
                if (globalFunctions::es_bidimensional($dtdetalle))
                {
                    $debito = 0;
                    $cuenta100=0;
                    $cuenta101=0;
                    $cuenta102=0;
                    $cuenta103=0;
                    $cuenta1 = 0;
                    $cuenta2 = 0;
                    $cuenta3 = 0;
                    $cuenta4 = 0;
                    $cuenta5 = 0;
                    $cuenta6 = 0;
                    
                    foreach ($dtdetalle as $value)
                    {
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtmatch = $planes->getMatchImpuesto($dtcli['fin40060id'],$producto['fin40060idventas']);
                        
                        if ($dtcliente['ivapedido']==0) {
                            if (globalFunctions::es_bidimensional($dtmatch))
                            {
                                foreach ($dtmatch as $matchValue)
                                {
                                    //Sacamos la cuenta
                                    $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                                    $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);

                                    //Validar porcentaje positivo o negativo
                                    if ($dtcuentaImpuesto['porcentaje']>0)
                                    {
                                        //ver si tiene basado en
                                        if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                        {
                                            //Sacar el valor de la cuenta
                                            $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                            $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'PV', $cuentaBasadoEn['fin00000id']);
                                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            if ($existeCuenta['numrows']>0)
                                            {
                                                $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                if ($cuenta1==0)
                                                {
                                                    $asiento->updateMultiColum('credito', (($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $cuenta1++;
                                                }
                                                else
                                                {
                                                    $asiento->updateMultiColum('credito', ($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                }
                                            }
                                            else
                                            {
                                                $asiento->setModulo(3);
                                                $asiento->setFin00000id($cuentaPV);
                                                $asiento->setFin40040id('PV');
                                                $asiento->setEmpresa($param['empresa']);
                                                $asiento->setDocumento($param['documento']);
                                                $asiento->setDebito(0);
                                                $asiento->setCredito((($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100));
                                                $asiento->setFin40070id($matchValue['fin40070id']);
                                                $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                                $asiento->save();
                                                $cuenta1++;
                                            }
                                        }
                                        else
                                        {
                                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            if ($existeCuenta['numrows']>0)
                                            {
                                                $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                                if ($cuenta102==0)
                                                {
                                                    $asiento->updateMultiColum('credito',(($montodebito*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $asiento->updateMultiColum('baseimponible', number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $cuenta102++;
                                                }
                                                else
                                                {
                                                    $asiento->updateMultiColum('credito', ($dtasiento['credito']+(($montodebito*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$montodebito), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                }
                                            }
                                            else
                                            {
                                                $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                                $asiento->setModulo(3);
                                                $asiento->setFin00000id($cuentaPV);
                                                $asiento->setFin40040id('PV');
                                                $asiento->setEmpresa($param['empresa']);
                                                $asiento->setDocumento($param['documento']);
                                                $asiento->setDebito(0);
                                                $asiento->setCredito((($montodebito*$dtcuentaImpuesto['porcentaje'])/100));
                                                $asiento->setFin40070id($matchValue['fin40070id']);
                                                $asiento->setBaseimponible(number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), 2, '.', ''));
                                                $asiento->save();
                                                $cuenta102++;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        //ver si tiene basado en
                                        if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '' )
                                        {
                                            //Sacar el valor de la cuenta
                                            $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                            $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'PV', $cuentaBasadoEn['fin00000id']);

                                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            if ($existeCuenta['numrows']>0)
                                            {
                                                $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                if ($cuenta3==0)
                                                {
                                                    $asiento->updateMultiColum('debito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $cuenta3++;
                                                }
                                                else
                                                {
                                                    $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                }
                                            }
                                            else
                                            {
                                                $asiento->setModulo(3);
                                                $asiento->setFin00000id($cuentaPV);
                                                $asiento->setFin40040id('PV');
                                                $asiento->setEmpresa($param['empresa']);
                                                $asiento->setDocumento($param['documento']);
                                                $asiento->setDebito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                                $asiento->setCredito(0);
                                                $asiento->setFin40070id($matchValue['fin40070id']);
                                                $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                                $asiento->save();
                                                $cuenta3++;
                                            }
                                        }
                                        else
                                        {
                                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            if ($existeCuenta['numrows']>0)
                                            {
                                                $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                                if ($cuenta102==0)
                                                {
                                                    $asiento->updateMultiColum('debito', ((($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $cuenta102++;
                                                }
                                                else
                                                {
                                                    $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                }
                                            }
                                            else
                                            {
                                                $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                                $asiento->setModulo(3);
                                                $asiento->setFin00000id($cuentaPV);
                                                $asiento->setFin40040id('PV');
                                                $asiento->setEmpresa($param['empresa']);
                                                $asiento->setDocumento($param['documento']);
                                                $asiento->setDebito((($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                                $asiento->setCredito(0);
                                                $asiento->setFin40070id($matchValue['fin40070id']);
                                                $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                                $asiento->save();
                                                $cuenta102++;
                                            }
                                        }
                                    }
                                }
                            }
                            else if(isset($dtmatch['fin40070id']))
                            {    
                                //Sacamos la cuenta
                                $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                                $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($dtmatch['fin40070id']);

                                //Validar porcentaje positivo o negativo
                                if ($dtcuentaImpuesto['porcentaje']>0)
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '' )
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'PV', $cuentaBasadoEn['fin00000id']);

                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            if ($cuenta1==0)
                                            {
                                                $asiento->updateMultiColum('credito', (($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $cuenta1++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('credito', ($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($cuentaPV);
                                            $asiento->setFin40040id('PV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100));
                                            $asiento->setFin40070id($dtmatch['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta1++;
                                        }
                                    }
                                    else
                                    {
                                        $montodebito = (($value['precio']-$value['descuento'])*$value['cantidad']);
                                        $credito_iva += (($montodebito*$dtcuentaImpuesto['porcentaje'])/100);
                                        $baseimponible_iva += number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                    }
                                }
                                else
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'PV', $cuentaBasadoEn['fin00000id']);

                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            if ($cuenta3==0)
                                            {
                                                $asiento->updateMultiColum('debito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $cuenta3++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($cuentaPV);
                                            $asiento->setFin40040id('PV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($dtmatch['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta3++;
                                        }
                                    }
                                    else
                                    {
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                            if ($cuenta4==0)
                                            {
                                                $asiento->updateMultiColum('debito', ((($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $cuenta4++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            }
                                        }
                                        else
                                        {
                                            $montodebito = number_format((float)(($value['precio']-$value['descuento'])*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($cuentaPV);
                                            $asiento->setFin40040id('PV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(($montodebito*$dtcuentaImpuesto['porcentaje']*(-1))/100);
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($dtmatch['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta4++;
                                        }
                                    }
                                }
                            }
                        }
                        
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);
                        
                        if (isset($dtcuenta['fin00000id']))
                        {
                            if ($dtcuenta['fin00000id'] !== '-1') {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    if ($cuenta100 == 0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $asiento->updateMultiColum('credito', number_format((float)(($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $cuenta100++;
                                    }
                                    else
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $asiento->updateMultiColum('credito', number_format((float)(($dtasiento['credito']+($value['costo']*$value['cantidad']))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    }
                                }
                                else
                                {
                                    if (number_format((float)(($value['costo']*$value['cantidad'])), $_SESSION['DTI_DECIMALVEN'], '.', '') > 0) {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                                        $asiento->setFin40040id('PV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(number_format((float)($value['costo']*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', ''));
                                        $asiento->setFin40070id('');
                                        $asiento->setBaseimponible(0);
                                        $asiento->save();
                                        $cuenta100++;
                                    }
                                }
                            }
                        }
                        
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 3);

                        if (isset($dtcuenta['fin00000id']))
                        {
                            if ($dtcuenta['fin00000id'] !== '-1') {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    if ($cuenta101==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $asiento->updateMultiColum('debito', number_format((float)(($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $cuenta101++;
                                    }
                                    else
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    }
                                }
                                else
                                {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                                    $asiento->setFin40040id('PV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(number_format((float)($value['costo']*$value['cantidad']), 2, '.', ''));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id('');
                                    $asiento->setBaseimponible(0);
                                    $asiento->save();
                                    $cuenta101++;
                                }
                            }
                        }
                        
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 4);

                        if (isset($cuentaPV))
                        {
                            if ($cuentaPV !== '-1') {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $montodebito = (($value['precio']-$value['descuento'])*$value['cantidad']);
                                    if ($cuenta102==0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $asiento->updateMultiColum('credito', (($montodebito)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $cuenta102++;
                                    }
                                    else
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $asiento->updateMultiColum('credito', ($dtasiento['credito']+($montodebito)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    }
                                }
                                else
                                {
                                    $montodebito = (($value['precio']-$value['descuento'])*$value['cantidad']);
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($cuentaPV);
                                    $asiento->setFin40040id('PV');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(($montodebito));
                                    $asiento->setFin40070id('');
                                    $asiento->setBaseimponible(0);
                                    $asiento->save();
                                    $cuenta102++;
                                }
                            }
                        }
                        
                        if ($value['descuento'] > 0)
                        {
                            $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 5);

                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                            if ($existeCuenta['numrows']>0)
                            {
                                if ($cuenta103==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    $asiento->updateMultiColum('debito', number_format((float)(($value['descuento']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    $cuenta103++;
                                }
                                else
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['descuento']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                }
                            }
                            else
                            {
                                $asiento->setModulo(3);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('PV');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)($value['descuento']*$value['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                                $cuenta103++;
                            }
                        }
                    }
                }
                else if (isset($dtdetalle['id']))
                {
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtmatch = $planes->getMatchImpuesto($dtcli['fin40060id'],$producto['fin40060idventas']);
                        
                    $cuenta1 = 0;
                    $cuenta2 = 0;
                    $cuenta3 = 0;
                    $cuenta4 = 0;
                    $cuenta5 = 0;
                    $cuenta6 = 0;
                    
                    if ($dtcliente['ivapedido']==0) {
                        if (globalFunctions::es_bidimensional($dtmatch))
                        {
                            foreach ($dtmatch as $matchValue)
                            {
                                //Sacamos la cuenta
                                $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                                $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);

                                //Validar porcentaje positivo o negativo
                                if ($dtcuentaImpuesto['porcentaje']>0)
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'PV', $cuentaBasadoEn['fin00000id']);

                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            if ($cuenta1==0)
                                            {
                                                $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $cuenta1++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtsumasiento['credito'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                                            $asiento->setFin40040id('PV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta1++;
                                        }
                                    }
                                    else
                                    {
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', '');
                                            if ($cuenta2==0)
                                            {
                                                $asiento->updateMultiColum('credito', ((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $cuenta2++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('credito', ($dtasiento['credito']+((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            }
                                        }
                                        else
                                        {
                                            $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', '');
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                                            $asiento->setFin40040id('PV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito(((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100));
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta2++;
                                        }
                                    }
                                }
                                else
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'PV', $cuentaBasadoEn['fin00000id']);

                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PVPV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                                            if ($cuenta3==0)
                                            {
                                                $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $cuenta3++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtsumasiento['debito'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                                            $asiento->setFin40040id('PV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta3++;
                                        }
                                    }
                                    else
                                    {
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', '');
                                            if ($cuenta4==0)
                                            {
                                                $asiento->updateMultiColum('debito', (((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $cuenta4++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', ($dtasiento['debito']+((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            }
                                        }
                                        else
                                        {
                                            $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', '');
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                                            $asiento->setFin40040id('PV');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta4++;
                                        }
                                    }
                                }
                            }
                        }
                        else if(isset($dtmatch['fin40070id']))
                        {
                            //Sacamos la cuenta
                            $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                            $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($dtmatch['fin40070id']);

                            //Validar porcentaje positivo o negativo
                            if ($dtcuentaImpuesto['porcentaje']>0)
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'PV', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        if ($cuenta1==0)
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $cuenta1++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtsumasiento['credito'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                                        $asiento->setFin40040id('PV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta1++;
                                    }
                                }
                                else {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                        if ($cuenta2==0)
                                        {
                                            $asiento->updateMultiColum('credito', ((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $cuenta2++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', ($dtasiento['credito']+((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        }
                                    }
                                    else
                                    {
                                        $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($cuentaPV);
                                        $asiento->setFin40040id('PV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(((($montodebito)*$dtcuentaImpuesto['porcentaje'])/100));
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad'])));
                                        $asiento->save();
                                        $cuenta2++;
                                    }
                                }
                            }
                            else
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'PV', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        if ($cuenta3==0)
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $cuenta3++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                                        $asiento->setFin40040id('PV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta3++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                        if ($cuenta4==0)
                                        {
                                            $asiento->updateMultiColum('debito', ((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $cuenta4++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito',($dtasiento['debito']+((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                        }
                                    }
                                    else
                                    {
                                        $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($cuentaPV);
                                        $asiento->setFin40040id('PV');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(((($montodebito)*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta4++;
                                    }
                                }
                            }
                        }
                    }
                    
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);
                    
                    if (isset($dtcuenta['fin00000id']))
                    {
                        if ($dtcuenta['fin00000id'] !== '-1') {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                            if ($existeCuenta['numrows']>0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                $asiento->updateMultiColum('credito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                            }
                            else
                            {
                                $asiento->setModulo(3);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('PV');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(0);
                                $asiento->setCredito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', ''));
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                            }
                        }
                    }
                    
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 3);

                    if (isset($dtcuenta['fin00000id']))
                    {
                        if ($dtcuenta['fin00000id'] !== '-1') {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                            if ($existeCuenta['numrows']>0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                            }
                            else
                            {
                                $asiento->setModulo(3);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('PV');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                            }
                        }
                    }
                    
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 4);
                    
                    if (isset($dtcuenta['fin00000id']))
                    {
                        if ($dtcuenta['fin00000id'] !== '-1') {
                            $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                            if ($existeCuenta['numrows']>0)
                            {
                                $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                                $asiento->updateMultiColum('credito', ($montodebito), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                            }
                            else
                            {
                                $montodebito = number_format((float)(($dtdetalle['precio']-$dtdetalle['descuento'])*$dtdetalle['cantidad']), $_SESSION['DTI_DECIMALVEN'], '.', '');
                                $asiento->setModulo(3);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('PV');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(0);
                                $asiento->setCredito($montodebito);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                            }
                        }
                    }
                    
                    if ($dtdetalle['descuento'] > 0)
                    {
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 5);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                        if ($existeCuenta['numrows']>0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                            $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['descuento']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                        }
                        else
                        {
                            $asiento->setModulo(3);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('PV');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($dtdetalle['descuento']*$dtdetalle['cantidad']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                        }
                    }
                }
                //Normalizar a 2 Decimales
                $normalizar = $asiento->getMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                if (globalFunctions::es_bidimensional($normalizar))
                {
                    foreach ($normalizar as $normali) 
                    {
                        $asiento->updateMultiColum('credito', number_format((float)($normali['credito']), 2, '.', ''), 'id',$normali['id']);
                        $asiento->updateMultiColum('debito', number_format((float)($normali['debito']), 2, '.', ''), 'id',$normali['id']);
                    }
                }
                else if (isset($normalizar['id']))
                {
                    $asiento->updateMultiColum('credito', number_format((float)($normalizar['credito']), 2, '.', ''), 'id',$normalizar['id']);
                    $asiento->updateMultiColum('debito', number_format((float)($normalizar['debito']), 2, '.', ''), 'id',$normalizar['id']);
                }
                //AGregar valor de IVA
                if (isset($cuentaPV))
                {
                    if ($cuentaPV !== '-1' && $credito_iva > 0) {
                        $dtasiento = $asiento->getMulti('fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                        $asiento->updateMultiColum('credito', ($dtasiento['credito']+(number_format((float)($credito_iva), 2, '.', ''))), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                        $asiento->updateMultiColum('baseimponible', ($dtasiento['baseimponible']+(number_format((float)($baseimponible_iva), 2, '.', ''))), 'fin00000id', $cuentaPV, 'documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                    }
                }
                //Normalizar a 2 Decimales
                $normalizar = $asiento->getMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                if (globalFunctions::es_bidimensional($normalizar))
                {
                    foreach ($normalizar as $normali) 
                    {
                        $asiento->updateMultiColum('credito', number_format((float)($normali['credito']), 2, '.', ''), 'id',$normali['id']);
                        $asiento->updateMultiColum('debito', number_format((float)($normali['debito']), 2, '.', ''), 'id',$normali['id']);
                    }
                }
                else if (isset($normalizar['id']))
                {
                    $asiento->updateMultiColum('credito', number_format((float)($normalizar['credito']), 2, '.', ''), 'id',$normalizar['id']);
                    $asiento->updateMultiColum('debito', number_format((float)($normalizar['debito']), 2, '.', ''), 'id',$normalizar['id']);
                }
            break;
            case 'RET':
                //Inicializar Variables
                $documento = new \Entidades\Cc10100($param['adapter']);
                $detalle = new Entidades\Cc10200($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $cliente = new \Entidades\Cc00000($param['adapter']);
                $planes = new \Models\Fin40080Model($param['adapter']);
                //Datos Cliente
                $dtcliente = $documento->getMulti('documento', $param['documento']);
                //Cuenta Clientes
                $cuenta = new Models\Cc00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaCliente($dtcliente['cc00000id'], 1);

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                if ($existeCuenta['numrows']>0)
                {
                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                    $asiento->updateMultiColum('credito', number_format((float)($dtcliente['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtcliente['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                }
                else
                {
                    $asiento->setModulo(3);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('RET');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(0);
                    $asiento->setCredito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->save();
                }
                
                $impuestos = $detalle->getMulti('documento', $param['documento']);
                
                $cuentaDetalle = new Entidades\Fin40070($param['adapter']);
                
                
                if (globalFunctions::es_bidimensional($impuestos))
                {
                    foreach ($impuestos as $value) {
                        $dtCuentaDetalle = $cuentaDetalle->getMulti('detalle', $value['fin40070id']);
                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtCuentaDetalle['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                        if ($existeCuenta['numrows']>0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                            $asiento->updateMultiColum('debito', number_format((float)($value['total']), 2, '.', ''), 'fin00000id', $dtCuentaDetalle['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                            $asiento->updateMultiColum('baseimponible', number_format((float)($value['total']), 2, '.', ''), 'fin00000id', $dtCuentaDetalle['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                        }
                        else
                        {
                            $asiento->setModulo(3);
                            $asiento->setFin00000id($dtCuentaDetalle['fin00000id']);
                            $asiento->setFin40040id('RET');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($value['total']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(number_format((float)($value['total']), 2, '.', ''));
                            $asiento->save();
                        }
                    }
                }
                else if (isset($impuestos['id']))
                {
                    $dtCuentaDetalle = $cuentaDetalle->getMulti('detalle', $impuestos['fin40070id']);
                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtCuentaDetalle['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                    if ($existeCuenta['numrows']>0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                        $asiento->updateMultiColum('debito', number_format((float)($impuestos['total']), 2, '.', ''), 'fin00000id', $dtCuentaDetalle['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                        $asiento->updateMultiColum('baseimponible', number_format((float)($impuestos['total']), 2, '.', ''), 'fin00000id', $dtCuentaDetalle['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                    }
                    else
                    {
                        $asiento->setModulo(3);
                        $asiento->setFin00000id($dtCuentaDetalle['fin00000id']);
                        $asiento->setFin40040id('RET');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(number_format((float)($impuestos['total']), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(number_format((float)($impuestos['total']), 2, '.', ''));
                        $asiento->save();
                    }
                }
                break;
            case 'NC':
                //Inicializar Variables
                $documento = new \Entidades\Cc10000($param['adapter']);
                $detalle = new Entidades\Cc10010($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $modal_asiento = new Models\Fin20100Model($param['adapter']);
                $inventario = new \Models\Inv00000Model($param['adapter']);
                $cuentaInventario = new Models\Inv00011Model($param['adapter']);
                $cliente = new \Entidades\Cc00000($param['adapter']);
                $planes = new \Models\Fin40080Model($param['adapter']);
                //Datos Cliente
                $dtcliente = $documento->getMulti('id', $param['documento']);;
                $dtcli = $cliente->getMulti('codigo', $dtcliente['cc00000id']);
                //Cuenta Clientes
                $cuenta = new Models\Cc00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaCliente($dtcliente['cc00000id'], 1);

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                if ($existeCuenta['numrows']>0)
                {
                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                    $asiento->updateMultiColum('credito', number_format((float)($dtcliente['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                    $asiento->updateMultiColum('baseimponible', number_format((float)($dtcliente['total']), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                }
                else
                {
                    $asiento->setModulo(3);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('NC');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(0);
                    $asiento->setCredito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->save();
                }
                //Cuenta Inventario
                $dtdetalle = $detalle->getMulti('cc10000id', $param['documento']);
                
                if (globalFunctions::es_bidimensional($dtdetalle))
                {
                    $debito = 0;
                    $cuenta200 = 0;
                    $cuenta201 = 0;
                    $cuenta202 = 0;
                    $cuenta1 = 0;
                    $cuenta2 = 0;
                    $cuenta3 = 0;
                    $cuenta4 = 0;
                    $cuenta5 = 0;
                    $cuenta6 = 0;
                    foreach ($dtdetalle as $value)
                    {
                        
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtmatch = $planes->getMatchImpuesto($dtcli['fin40060id'],$producto['fin40060idventas']);
                       
                        if (globalFunctions::es_bidimensional($dtmatch)) {
                            foreach ($dtmatch as $matchValue)
                            {
                                //Sacamos la cuenta
                                $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                                $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);

                                //Validar porcentaje positivo o negativo
                                if ($dtcuentaImpuesto['porcentaje']>0)
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'NC', $cuentaBasadoEn['fin00000id']);

                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            if ($cuenta1==0)
                                            {
                                                $asiento->updateMultiColum('debito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $cuenta1++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('NC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta1++;
                                        }
                                    }
                                    else
                                    {
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            if ($cuenta2==0)
                                            {
                                                $asiento->updateMultiColum('debito', ((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($value['precio']*$value['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $cuenta2++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', ($dtasiento['debito']+((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($value['precio']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('NC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($value['precio']*$value['cantidad']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta2++;
                                        }
                                    }
                                }
                                else
                                {
                                    //ver si tiene basado en
                                    if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                    {
                                        //Sacar el valor de la cuenta
                                        $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                        $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'NC', $cuentaBasadoEn['fin00000id']);

                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            if ($cuenta3==0)
                                            {
                                                $asiento->updateMultiColum('debito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $cuenta3++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+$dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            }
                                        }
                                        else
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('NC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                            $asiento->setCredito(0);
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta3++;
                                        }
                                    }
                                    else
                                    {
                                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($existeCuenta['numrows']>0)
                                        {
                                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            if ($cuenta4==0)
                                            {
                                                $asiento->updateMultiColum('credito', (((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $cuenta4++;
                                            }
                                            else
                                            {
                                                $asiento->updateMultiColum('credito', ($dtasiento['debito']+((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                                $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            }
                                        }
                                        else    
                                        {
                                            $asiento->setModulo(3);
                                            $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                            $asiento->setFin40040id('NC');
                                            $asiento->setEmpresa($param['empresa']);
                                            $asiento->setDocumento($param['documento']);
                                            $asiento->setDebito(0);
                                            $asiento->setCredito(((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                            $asiento->setFin40070id($matchValue['fin40070id']);
                                            $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                            $asiento->save();
                                            $cuenta4++;
                                        }
                                    }
                                }
                            }
                        } 
                        elseif (isset($dtmatch['fin40070id'])) {
                            //Sacamos la cuenta
                            $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                            $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($dtmatch['fin40070id']);
                            
                            //Validar porcentaje positivo o negativo
                            if ($dtcuentaImpuesto['porcentaje']>0)
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'NC', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($cuenta1==0)
                                        {
                                            $asiento->updateMultiColum('debito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $cuenta1++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('NC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta1++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($cuenta2==0)
                                        {
                                            $asiento->updateMultiColum('debito', ((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($value['precio']*$value['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $cuenta2++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', ($dtasiento['debito']+((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($value['precio']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('NC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($value['precio']*$value['cantidad']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta2++;
                                    }
                                }
                            }
                            else
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'NC', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($cuenta3==0)
                                        {
                                            $asiento->updateMultiColum('debito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $cuenta3++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', ($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('NC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito((($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta3++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($cuenta4==0)
                                        {
                                            $asiento->updateMultiColum('credito', (((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $cuenta4++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito',($dtasiento['debito']+((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('NC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(((($value['precio']*$value['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                        $asiento->setFin40070id($dtmatch['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta4++;
                                    }
                                }
                            }
                        }
                        
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                        if ($existeCuenta['numrows']>0)
                        {
                            if ($cuenta200==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $asiento->updateMultiColum('debito', number_format((float)(($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $cuenta200++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            }
                        }
                        else
                        {
                            $asiento->setModulo(3);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('NC');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($value['costo']*$value['cantidad']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta200++;
                        }

                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 3);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                        if ($existeCuenta['numrows']>0)
                        {
                            if ($cuenta201==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $asiento->updateMultiColum('credito', number_format((float)(($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $cuenta201++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            }
                        }
                        else
                        {
                            $asiento->setModulo(3);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('NC');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(0);
                            $asiento->setCredito(number_format((float)($value['costo']*$value['cantidad']), 2, '.', ''));
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta201++;
                        }

                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 6);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                        if ($existeCuenta['numrows']>0)
                        {
                            if ($cuenta202==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $asiento->updateMultiColum('debito', number_format((float)(($value['precio']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $cuenta202++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['precio']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            }
                        }
                        else
                        {
                            $asiento->setModulo(3);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('NC');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($value['precio']*$value['cantidad']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta202++;
                        }
                    }
                }
                else if (isset($dtdetalle['id']))
                {
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtmatch = $planes->getMatchImpuesto($dtcli['fin40060id'],$producto['fin40060idventas']);
                    $cuenta1 = 0;
                    $cuenta2 = 0;
                    $cuenta3 = 0;
                    $cuenta4 = 0;
                    $cuenta5 = 0;
                    $cuenta6 = 0;
                    $cuenta200 = 0;
                    $cuenta201 = 0;
                    $cuenta202 = 0;
                    
                    if (globalFunctions::es_bidimensional($dtmatch))
                    {
                        foreach ($dtmatch as $matchValue)
                        {
                            //Sacamos la cuenta
                            $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                            $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($matchValue['fin40070id']);

                            //Validar porcentaje positivo o negativo
                            if ($dtcuentaImpuesto['porcentaje']>0)
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'NC', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($cuenta1==0)
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $cuenta1++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtsumasiento['debito'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('NC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta1++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($cuenta2==0)
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $cuenta2++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtdetalle['precio']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('NC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(number_format((float)((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                        $asiento->setCredito(0);
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta2++;
                                    }
                                }
                            }
                            else
                            {
                                //ver si tiene basado en
                                if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                                {
                                    //Sacar el valor de la cuenta
                                    $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                    $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'NC', $cuentaBasadoEn['fin00000id']);

                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($cuenta3==0)
                                        {
                                            $asiento->updateMultiColum('credito', (($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $cuenta3++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', ($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtsumasiento['credito'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('NC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito((($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta3++;
                                    }
                                }
                                else
                                {
                                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($existeCuenta['numrows']>0)
                                    {
                                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        if ($cuenta4==0)
                                        {
                                            $asiento->updateMultiColum('credito', (((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $cuenta4++;
                                        }
                                        else
                                        {
                                            $asiento->updateMultiColum('credito', ($dtasiento['credito']+((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                            $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+($dtdetalle['precio']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        }
                                    }
                                    else
                                    {
                                        $asiento->setModulo(3);
                                        $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                        $asiento->setFin40040id('NC');
                                        $asiento->setEmpresa($param['empresa']);
                                        $asiento->setDocumento($param['documento']);
                                        $asiento->setDebito(0);
                                        $asiento->setCredito(((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100));
                                        $asiento->setFin40070id($matchValue['fin40070id']);
                                        $asiento->setBaseimponible(number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''));
                                        $asiento->save();
                                        $cuenta4++;
                                    }
                                }
                            }
                        }
                    }
                    else if(isset($dtmatch['fin40070id']))
                    {
                        //Sacamos la cuenta
                        $cuentaPlanImpuesto = new \Models\Fin40070Model($param['adapter']);
                        $dtcuentaImpuesto = $cuentaPlanImpuesto->getCuentaPlan($dtmatch['fin40070id']);

                        //Validar porcentaje positivo o negativo
                        if ($dtcuentaImpuesto['porcentaje']>0)
                        {
                            //ver si tiene basado en
                            if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                            {
                                //Sacar el valor de la cuenta
                                $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'NC', $cuentaBasadoEn['fin00000id']);

                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($cuenta1==0)
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['debito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $cuenta1++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtsumasiento['debito'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    }
                                }
                                else
                                {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('NC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(number_format((float)(($dtsumasiento['debito']*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtsumasiento['debito']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta1++;
                                }
                            }
                            else
                            {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($cuenta2==0)
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $cuenta2++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['debito']+($dtdetalle['precio']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    }
                                }
                                else
                                {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('NC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(number_format((float)((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje'])/100), 2, '.', ''));
                                    $asiento->setCredito(0);
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad'])));
                                    $asiento->save();
                                    $cuenta2++;
                                }
                            }
                        }
                        else
                        {
                            //ver si tiene basado en
                            if ($dtcuentaImpuesto['fin40070id'] !== '-1' && $dtcuentaImpuesto['fin40070id'] !== '')
                            {
                                //Sacar el valor de la cuenta
                                $cuentaBasadoEn = $cuentaPlanImpuesto->getCuentaPlan($dtcuentaImpuesto['fin40070id']);
                                $dtsumasiento = $modal_asiento->getSumDistribucion($param['documento'], 'NC', $cuentaBasadoEn['fin00000id']);

                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($cuenta3==0)
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $cuenta3++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['credito']+$dtsumasiento['credito']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    }
                                }
                                else
                                {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('NC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(number_format((float)(($dtsumasiento['credito']*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtsumasiento['credito']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta3++;
                                }
                            }
                            else
                            {
                                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                if ($existeCuenta['numrows']>0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    if ($cuenta4==0)
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $cuenta4++;
                                    }
                                    else
                                    {
                                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100)), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                        $asiento->updateMultiColum('baseimponible', number_format((float)($dtasiento['baseimponible']+$dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''), 'fin00000id', $dtcuentaImpuesto['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                                    }
                                }
                                else
                                {
                                    $asiento->setModulo(3);
                                    $asiento->setFin00000id($dtcuentaImpuesto['fin00000id']);
                                    $asiento->setFin40040id('NC');
                                    $asiento->setEmpresa($param['empresa']);
                                    $asiento->setDocumento($param['documento']);
                                    $asiento->setDebito(0);
                                    $asiento->setCredito(number_format((float)((($dtdetalle['precio']*$dtdetalle['cantidad'])*$dtcuentaImpuesto['porcentaje']*(-1))/100), 2, '.', ''));
                                    $asiento->setFin40070id($dtmatch['fin40070id']);
                                    $asiento->setBaseimponible(number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''));
                                    $asiento->save();
                                    $cuenta4++;
                                }
                            }
                        }
                    }
                    
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta200==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $cuenta200++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                        }
                    }
                    else
                    {
                        $asiento->setModulo(3);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('NC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(0);
                        $asiento->save();
                        $cuenta200++;
                    }
                    
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 3);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta201==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $asiento->updateMultiColum('credito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $cuenta201++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                        }
                    }
                    else
                    {
                        $asiento->setModulo(3);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('NC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(0);
                        $asiento->setCredito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', ''));
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(0);
                        $asiento->save();
                        $cuenta201++;
                    }
                    
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 6);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta202==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['precio']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $cuenta202++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtdetalle['precio']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                        }
                    }
                    else
                    {
                        $asiento->setModulo(3);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('NC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(number_format((float)($dtdetalle['precio']*$dtdetalle['cantidad']), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(0);
                        $asiento->save();
                        $cuenta202++;
                    }
                }
                
                //Normalizar a 2 Decimales
                $normalizar = $asiento->getMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                if (globalFunctions::es_bidimensional($normalizar))
                {
                    foreach ($normalizar as $normali) 
                    {
                        $asiento->updateMultiColum('credito', number_format((float)($normali['credito']), 2, '.', ''), 'id',$normali['id']);
                        $asiento->updateMultiColum('debito', number_format((float)($normali['debito']), 2, '.', ''), 'id',$normali['id']);
                    }
                }
                else if (isset($normalizar['id']))
                {
                    $asiento->updateMultiColum('credito', number_format((float)($normalizar['credito']), 2, '.', ''), 'id',$normalizar['id']);
                    $asiento->updateMultiColum('debito', number_format((float)($normalizar['debito']), 2, '.', ''), 'id',$normalizar['id']);
                }
            break;
            case 'AJP':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $inventario = new \Models\Inv00000Model($param['adapter']);
                $cuentaInventario = new Models\Inv00011Model($param['adapter']);
                $detalle = new Entidades\Inv10200($param['adapter']);
                
                $dtdetalle = $detalle->getMulti('documento', $param['documento'],'fin40040id','AJP');
                
                $cuenta1 = 0;
                $cuenta2 = 0;

                if (globalFunctions::es_bidimensional($dtdetalle))
                {
                    foreach ($dtdetalle as $value) {
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','debito','0');

                        if ($existeCuenta['numrows']>0)
                        {
                            if ($cuenta1==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                $asiento->updateMultiColum('credito', number_format((float)(($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','debito','0');
                                $cuenta1++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','debito','0');
                            }
                        }
                        else
                        {
                            $asiento->setModulo(7);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('AJP');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(0);
                            $asiento->setCredito(number_format((float)($value['costo']*$value['cantidad']), 2, '.', ''));
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta1++;
                        }

                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 7);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','credito','0');
                        if ($existeCuenta['numrows']>0)
                        {
                            if ($cuenta2 == 0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                $asiento->updateMultiColum('debito', number_format((float)(($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','credito','0');
                                $cuenta2++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','credito','0');
                            }
                        }
                        else
                        {
                            //Validar Si ya una cuenta
                            $validar_cuenta = new Models\Fin20100Model($param['adapter']);
                            $exista_cuenta = $validar_cuenta->getCountResul($param['documento'], 'AJP');
                            
                            if ($exista_cuenta['numrows'] == 1)
                            {
                                $asiento->setModulo(7);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('AJP');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(number_format((float)($value['costo']*$value['cantidad']), 2, '.', ''));
                                $asiento->setCredito(0);
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                                $cuenta2++;
                            }
                            else
                            {
                                //Cuenta que ocupa ese lugar
                                $cuenta_remplazo = $asiento->getMulti('documento', $param['documento'], 'modulo', 7, 'fin40040id', 'AJP','credito','0');

                                if ($cuenta2 == 0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                    $asiento->updateMultiColum('debito', number_format((float)(($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','credito','0');
                                    $cuenta2++; 
                                }
                                else
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                    $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo']*$value['cantidad'])), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','credito','0');
                                }
                            }
                        }
                    }
                }
                else if(isset($dtdetalle['documento']))
                {
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta1==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                            $asiento->updateMultiColum('credito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','debito','0');
                            $cuenta1++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','debito','0');
                        }
                    }
                    else
                    {
                        $asiento->setModulo(7);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('AJP');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(0);
                        $asiento->setCredito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', ''));
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(0);
                        $asiento->save();
                        $cuenta1++;
                    }
                    
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 7);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta2==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                            $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','credito','0');
                            $cuenta2++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP','credito','0');
                        }
                    }
                    else
                    {
                        //Validar Si ya una cuenta
                        $validar_cuenta = new Models\Fin20100Model($param['adapter']);
                        $exista_cuenta = $validar_cuenta->getCountResul($param['documento'], 'AJP');

                        if ($exista_cuenta['numrows'] == 1)
                        {
                            $asiento->setModulo(7);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('AJP');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta2++;
                        }
                        else
                        {
                            //Cuenta que ocupa ese lugar
                            $cuenta_remplazo = $asiento->getMulti('documento', $param['documento'], 'modulo', 7, 'fin40040id', 'AJP', 'credito', '0');
                                                        
                            if ($cuenta2==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                $cuenta2++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtdetalle['costo']*$dtdetalle['cantidad'])), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                            }
                        }
                    }
                }
                break;
            case 'AJN':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $inventario = new \Models\Inv00000Model($param['adapter']);
                $cuentaInventario = new Models\Inv00011Model($param['adapter']);
                $detalle = new Entidades\Inv10200($param['adapter']);
                
                $dtdetalle = $detalle->getMulti('documento', $param['documento'],'fin40040id','AJN');
                
                $cuenta1 = 0;
                $cuenta2 = 0;
                
                if (globalFunctions::es_bidimensional($dtdetalle))
                {
                    foreach ($dtdetalle as $value) {
                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                        if ($existeCuenta['numrows']>0)
                        {
                            if ($cuenta1==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                $asiento->updateMultiColum('debito', number_format((float)(($value['costo']*$value['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                $cuenta1++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($value['costo']*$value['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                            }
                        }
                        else
                        {
                            $asiento->setModulo(7);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('AJN');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($value['costo']*$value['cantidad']*(-1)), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta1++;
                        }

                        $producto = $inventario->getPlanImpuesto($value['inv00000id']);
                        $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 8);

                        $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                        if ($existeCuenta['numrows']>0)
                        {
                            if ($cuenta2==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                $asiento->updateMultiColum('credito', number_format((float)(($value['costo']*$value['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                $cuenta2++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($value['costo']*$value['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                            }
                        }
                        else
                        {
                            //Validar Si ya una cuenta
                            $validar_cuenta = new Models\Fin20100Model($param['adapter']);
                            $exista_cuenta = $validar_cuenta->getCountResul($param['documento'], 'AJN');
                        
                            if ($exista_cuenta['numrows']==1)
                            {
                                $asiento->setModulo(7);
                                $asiento->setFin00000id($dtcuenta['fin00000id']);
                                $asiento->setFin40040id('AJN');
                                $asiento->setEmpresa($param['empresa']);
                                $asiento->setDocumento($param['documento']);
                                $asiento->setDebito(0);
                                $asiento->setCredito(number_format((float)($value['costo']*$value['cantidad']*(-1)), 2, '.', ''));
                                $asiento->setFin40070id('');
                                $asiento->setBaseimponible(0);
                                $asiento->save();
                                $cuenta2++;
                            }
                            else
                            {
                                //Cuenta que ocupa ese lugar
                                $cuenta_remplazo = $asiento->getMulti('documento', $param['documento'], 'modulo', 7, 'fin40040id', 'AJN', 'debito', '0');
                                
                                if ($cuenta2==0)
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                    $asiento->updateMultiColum('credito', number_format((float)(($value['costo']*$value['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                    $cuenta2++;
                                }
                                else
                                {
                                    $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                    $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($value['costo']*$value['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                }
                            }
                        }
                    }
                }
                else if(isset($dtdetalle['documento']))
                {
                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 1);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN','credito');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta1==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                            $asiento->updateMultiColum('debito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN','credito','0');
                            $cuenta1++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtdetalle['costo']*$dtdetalle['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN','credito','0');
                        }
                    }
                    else
                    {
                        $asiento->setModulo(7);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('AJN');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']*(-1)), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(0);
                        $asiento->save();
                        $cuenta1++;
                    }

                    $producto = $inventario->getPlanImpuesto($dtdetalle['inv00000id']);
                    $dtcuenta = $cuentaInventario->getCuentaProducto($producto['codigo'], 8);

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta2==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                            $asiento->updateMultiColum('credito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN','debito','0');
                            $cuenta2++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtdetalle['costo']*$dtdetalle['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN','debito','0');
                        }
                    }
                    else
                    {
                        //Validar Si ya una cuenta
                        $validar_cuenta = new Models\Fin20100Model($param['adapter']);
                        $exista_cuenta = $validar_cuenta->getCountResul($param['documento'], 'AJN');
                        
                        if ($exista_cuenta['numrows']==1)
                        {
                            $asiento->setModulo(7);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('AJN');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(0);
                            $asiento->setCredito(number_format((float)($dtdetalle['costo']*$dtdetalle['cantidad']*(-1)), 2, '.', ''));
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta2++;
                        }
                        else
                        {
                            //Cuenta que ocupa ese lugar
                            $cuenta_remplazo = $asiento->getMulti('documento', $param['documento'], 'modulo', 7, 'fin40040id', 'AJN','debito','0');
                            
                            if ($cuenta2==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                $asiento->updateMultiColum('credito', number_format((float)(($dtdetalle['costo']*$dtdetalle['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN','debito','0');
                                $cuenta2++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtdetalle['costo']*$dtdetalle['cantidad']*(-1))), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 7,'fin40040id','AJN','debito','0');
                            }
                        }
                    }
                }
                break;
            case 'ECXC':
                $documento = new \Entidades\Cc20300($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $cliente = new \Entidades\Cc00000($param['adapter']);
                
                $dtcliente = $documento->getMulti('documento', $param['documento']);;
                $dtcli = $cliente->getMulti('codigo', $dtcliente['cc00000id']);
                
                //Validar si no es Anticipo
                if ($dtcliente['anticipo'] == 1)
                {
                    //Cuenta Clientes
                    $cuenta = new Models\Cc00011Model($param['adapter']);
                    $dtcuenta = $cuenta->getCuentaCliente($dtcliente['cc00000id'], 2);
                    
                    $cuenta1=0;

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta1==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                            $asiento->updateMultiColum('credito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                            $cuenta1++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                        }
                    }
                    else
                    {
                        //Validar Si ya una cuenta
                        $validar_cuenta = new Models\Fin20100Model($param['adapter']);
                        $exista_cuenta = $validar_cuenta->getCountResul($param['documento'], 'ECXC');
                        
                        $cuenta_remplazo = $asiento->getMulti('documento', $param['documento'], 'modulo', 9, 'fin40040id', 'ECXC', 'debito', '0');
                        
                        if ($exista_cuenta['numrows']==0 && !isset($cuenta_remplazo['fin00000id']))
                        {
                            $asiento->setModulo(9);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('ECXC');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(0);
                            $asiento->setCredito(number_format((float)($dtcliente['total']), 2, '.', ''));
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta1++;
                        }
                        else
                        {
                            if ($cuenta1==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                                $asiento->updateMultiColum('credito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                                $cuenta1++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                                $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                            }
                        }
                        
                    }
                }
                else
                {
                     //Cuenta Clientes
                    $cuenta = new Models\Cc00011Model($param['adapter']);
                    $dtcuenta = $cuenta->getCuentaCliente($dtcliente['cc00000id'], 1);

                    $cuenta1=0;

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta1==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                            $asiento->updateMultiColum('credito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                            $cuenta1++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                            $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                        }
                    }
                    else
                    {
                        $asiento->setModulo(9);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('ECXC');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(0);
                        $asiento->setCredito(number_format((float)(($dtcliente['total'])), 2, '.', ''));
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(0);
                        $asiento->save();
                        $cuenta1++;
                    }
                }

                //Agregar la cuenta de la chequera
                $chequera = new Models\Fin00010Model($param['adapter']);
                $dtcuenta = $chequera->getCuentaChequera($dtcliente['fin00010id']);
                
                $cuenta1=0;

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');

                if ($existeCuenta['numrows']>0)
                {
                    if ($cuenta1==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                        $asiento->updateMultiColum('debito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                        $cuenta1++;
                    }
                    else
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                    }
                }
                else
                {
                    $asiento->setModulo(9);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('ECXC');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setCredito(0);
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(0);
                    $asiento->save();
                    $cuenta1++;
                }
                
            break;
            case 'ACXC':
                $documento = new \Entidades\Cc30300($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $cliente = new \Entidades\Cc00000($param['adapter']);
                
                $dtcliente = $documento->getMulti('documento', $param['documento']);;
                $dtcli = $cliente->getMulti('codigo', $dtcliente['cc00000id']);
                
                //Cuenta Clientes
                $cuenta = new Models\Cc00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaCliente($dtcliente['cc00000id'], 1);

                $cuenta1=0;

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                if ($existeCuenta['numrows']>0)
                {
                    if ($cuenta1==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                        $asiento->updateMultiColum('credito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                        $cuenta1++;
                    }
                    else
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                    }
                }
                else
                {
                    $asiento->setModulo(9);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('ACXC');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(0);
                    $asiento->setCredito(number_format((float)(($dtcliente['total'])), 2, '.', ''));
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(0);
                    $asiento->save();
                    $cuenta1++;
                }

                $cuenta = new Models\Cc00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaCliente($dtcliente['cc00000id'], 3);

                $cuenta2=0;

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                if ($existeCuenta['numrows']>0)
                {
                    if ($cuenta2==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                        $asiento->updateMultiColum('debito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                        $cuenta2++;
                    }
                    else
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXC');
                    }
                }
                else
                {
                    $asiento->setModulo(9);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('ACXC');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setCredito(0);
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(0);
                    $asiento->save();
                    $cuenta2++;
                }
                
            break;
            case 'ECXP':
                $documento = new \Entidades\Cp20300($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $cliente = new \Entidades\Cp00000($param['adapter']);
                
                $dtcliente = $documento->getMulti('documento', $param['documento']);
                $dtcli = $cliente->getMulti('codigo', $dtcliente['cp00000id']);
                
                //Validar si no es Anticipo
                if ($dtcliente['anticipo'] == 1)
                {
                    //Cuenta Clientes
                    $cuenta = new Models\Cp00011Model($param['adapter']);
                    $dtcuenta = $cuenta->getCuentaProveedor($dtcliente['cp00000id'], 3);
                    
                    $cuenta1=0;

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta1==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                            $asiento->updateMultiColum('debito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                            $cuenta1++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                        }
                    }
                    else
                    {
                        //Validar Si ya una cuenta
                        $validar_cuenta = new Models\Fin20100Model($param['adapter']);
                        $exista_cuenta = $validar_cuenta->getCountResul($param['documento'], 'ECXP');
                        
                        $cuenta_remplazo = $asiento->getMulti('documento', $param['documento'], 'modulo', 9, 'fin40040id', 'ECXP', 'debito', '0');
                        
                        if ($exista_cuenta['numrows']==0 && !isset($cuenta_remplazo['fin00000id']))
                        {
                            $asiento->setModulo(9);
                            $asiento->setFin00000id($dtcuenta['fin00000id']);
                            $asiento->setFin40040id('ECXP');
                            $asiento->setEmpresa($param['empresa']);
                            $asiento->setDocumento($param['documento']);
                            $asiento->setDebito(number_format((float)($dtcliente['total']), 2, '.', ''));
                            $asiento->setCredito(0);
                            $asiento->setFin40070id('');
                            $asiento->setBaseimponible(0);
                            $asiento->save();
                            $cuenta1++;
                        }
                        else
                        {
                            if ($cuenta1==0)
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                                $asiento->updateMultiColum('debito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                                $cuenta1++;
                            }
                            else
                            {
                                $dtasiento = $asiento->getMulti('fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                                $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $cuenta_remplazo['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                            }
                        }
                    }
                }
                else
                {
                     //Cuenta Clientes
                    $cuenta = new Models\Cp00011Model($param['adapter']);
                    $dtcuenta = $cuenta->getCuentaProveedor($dtcliente['cp00000id'], 1);

                    $cuenta1=0;

                    $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                    if ($existeCuenta['numrows']>0)
                    {
                        if ($cuenta1==0)
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                            $asiento->updateMultiColum('debito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                            $cuenta1++;
                        }
                        else
                        {
                            $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                            $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                        }
                    }
                    else
                    {
                        $asiento->setModulo(9);
                        $asiento->setFin00000id($dtcuenta['fin00000id']);
                        $asiento->setFin40040id('ECXP');
                        $asiento->setEmpresa($param['empresa']);
                        $asiento->setDocumento($param['documento']);
                        $asiento->setDebito(number_format((float)(($dtcliente['total'])), 2, '.', ''));
                        $asiento->setCredito(0);
                        $asiento->setFin40070id('');
                        $asiento->setBaseimponible(0);
                        $asiento->save();
                        $cuenta1++;
                    }
                }

                //Agregar la cuenta de la chequera
                $chequera = new Models\Fin00010Model($param['adapter']);
                $dtcuenta = $chequera->getCuentaChequera($dtcliente['fin00010id']);
                
                $cuenta1=0;

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');

                if ($existeCuenta['numrows']>0)
                {
                    if ($cuenta1==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                        $asiento->updateMultiColum('credito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                        $cuenta1++;
                    }
                    else
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                    }
                }
                else
                {
                    $asiento->setModulo(9);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('ECXP');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(0);
                    $asiento->setCredito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(0);
                    $asiento->save();
                    $cuenta1++;
                }
                
            break;
            case 'ACXP':
                $documento = new \Entidades\Cp30301($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);
                $cliente = new \Entidades\Cp00000($param['adapter']);
                
                $dtcliente = $documento->getMulti('documento', $param['documento']);
                $dtcli = $cliente->getMulti('codigo', $dtcliente['cp00000id']);

                //Cuenta Clientes
                $cuenta = new Models\Cp00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaProveedor($dtcliente['cp00000id'], 3);

                $cuenta1=0;

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                if ($existeCuenta['numrows']>0)
                {
                    if ($cuenta1==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                        $asiento->updateMultiColum('debito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                        $cuenta1++;
                    }
                    else
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                    }
                }
                else
                {
                    $asiento->setModulo(9);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('ACXP');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setCredito(0);
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(0);
                    $asiento->save();
                    $cuenta1++;
                }

                $cuenta = new Models\Cp00011Model($param['adapter']);
                $dtcuenta = $cuenta->getCuentaProveedor($dtcliente['cp00000id'], 1);

                $cuenta2=0;

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                if ($existeCuenta['numrows']>0)
                {
                    if ($cuenta2==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                        $asiento->updateMultiColum('credito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                        $cuenta2++;
                    }
                    else
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000id'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','ACXP');
                    }
                }
                else
                {
                    $asiento->setModulo(9);
                    $asiento->setFin00000id($dtcuenta['fin00000id']);
                    $asiento->setFin40040id('ACXP');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(0);
                    $asiento->setCredito(number_format((float)(($dtcliente['total'])), 2, '.', ''));
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(0);
                    $asiento->save();
                    $cuenta2++;
                }
                
            break;
            case 'TB':
                $documento = new \Entidades\Ban10000($param['adapter']);
                $asiento = new Entidades\Fin20100($param['adapter']);

                //Cuenta Bancos
                $dtcuenta = $cuenta->getMulti('documento', $param['documento']);

                $cuenta1=0;

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000desde'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                if ($existeCuenta['numrows']>0)
                {
                    if ($cuenta1==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000desde'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                        $asiento->updateMultiColum('debito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000desde'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                        $cuenta1++;
                    }
                    else
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000desde'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                        $asiento->updateMultiColum('debito', number_format((float)($dtasiento['debito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000desde'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                    }
                }
                else
                {
                    $asiento->setModulo(9);
                    $asiento->setFin00000id($dtcuenta['fin00000desde']);
                    $asiento->setFin40040id('TB');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(number_format((float)($dtcliente['total']), 2, '.', ''));
                    $asiento->setCredito(0);
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(0);
                    $asiento->save();
                    $cuenta1++;
                }

                $cuenta2=0;

                $existeCuenta = $asiento->getCountMulti('id', 'fin00000id', $dtcuenta['fin00000hasta'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                if ($existeCuenta['numrows']>0)
                {
                    if ($cuenta2==0)
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000hasta'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                        $asiento->updateMultiColum('credito', number_format((float)(($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000hasta'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                        $cuenta2++;
                    }
                    else
                    {
                        $dtasiento = $asiento->getMulti('fin00000id', $dtcuenta['fin00000hasta'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                        $asiento->updateMultiColum('credito', number_format((float)($dtasiento['credito']+($dtcliente['total'])), 2, '.', ''), 'fin00000id', $dtcuenta['fin00000hasta'], 'documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                    }
                }
                else
                {
                    $asiento->setModulo(9);
                    $asiento->setFin00000id($dtcuenta['fin00000hasta']);
                    $asiento->setFin40040id('TB');
                    $asiento->setEmpresa($param['empresa']);
                    $asiento->setDocumento($param['documento']);
                    $asiento->setDebito(0);
                    $asiento->setCredito(number_format((float)(($dtcliente['total'])), 2, '.', ''));
                    $asiento->setFin40070id('');
                    $asiento->setBaseimponible(0);
                    $asiento->save();
                    $cuenta2++;
                }
                
            break;
        }
    }
    
    public static function deleteDistribuciones($param=array())
    {
        /*fin40040id/documento*/
        switch ($param['fin40040id']) {
            case 'FC':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 4,'fin40040id','FC');
                break;
            case 'DC':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 4,'fin40040id','DC');
                break;
            case 'ERCB':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 4,'fin40040id','ERCB');
                break;
            case 'FV':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','FV');
                break;
            case 'PV':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','PV');
                break;
            case 'RET':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','RET');
                break;
            case 'NC':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 3,'fin40040id','NC');
                break;
            case 'AJP':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 7,'fin40040id','AJP');
                break;
            case 'AJN':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 7,'fin40040id','AJN');
                break;
            case 'ECXC':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 9,'fin40040id','ECXC');
                break;
            case 'ECXP':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 9,'fin40040id','ECXP');
                break;
            case 'TB':
                $asiento = new Entidades\Fin20100($param['adapter']);
                $asiento->deleteMulti('documento', $param['documento'], 'modulo', 9,'fin40040id','TB');
                break;
        }
    }
    
    /**
     * Generar el HTML y los tamaños del cheque
     * @param type NombredeCheque
     */
    public static function generarCHQ($banco)
    {
        $html='';
        $p1=0;
        $p2=0;
        $p3=0;
        $p4=0;
        //recibe nombre de cheque
        switch ($banco) {
            case 'PICHINCHA':
                $p1=0;
                $p2=0;
                $p3=360;
                $p4=360;
                //Creamos el PDF
                $html ='<!DOCTYPE html>';
                $html .='<html>';
                $html .='<head>';
                $html .='<meta charset="utf-8">';
                $html .='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
                $html .='<title>Certificado Emetrope</title>';
                $html .='<style type="text/css">';
                $html .='@page {margin-top: 5.92em;margin-left: 7.11em;margin-right: 7.11em;}';
                $html .='body {font-family: Delicious, sans-serif;font-size: 12px;}';
                $html .='p {text-align : justify;line-height: 1.5em;}';
                $html .='.titulo {text-align : center !important;font-size: 14px;text-decoration: underline;}';
                $html .='</style>   ';
                $html .='</head>';
                $html .='<body> ';
                $html .='<br><br><br>';
                $html .='<p class="titulo"><b>CHEQUE</b></p><br><br>';
                $html .='<p>Atentamente,</p><br><br>';
                $html .='<p>Opt. Miguel Reyes Pico</p><br><br>';
                $html .='</body>';
                $html .='</html>';
                break;
            case 'GUAYAQUIL':
                $p1=0;
                $p2=0;
                $p3=360;
                $p4=360;
                //Creamos el PDF
                $html ='<!DOCTYPE html>';
                $html .='<html>';
                $html .='<head>';
                $html .='<meta charset="utf-8">';
                $html .='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
                $html .='<title>Certificado Emetrope</title>';
                $html .='<style type="text/css">';
                $html .='@page {margin-top: 5.92em;margin-left: 7.11em;margin-right: 7.11em;}';
                $html .='body {font-family: Delicious, sans-serif;font-size: 12px;}';
                $html .='p {text-align : justify;line-height: 1.5em;}';
                $html .='.titulo {text-align : center !important;font-size: 14px;text-decoration: underline;}';
                $html .='</style>   ';
                $html .='</head>';
                $html .='<body> ';
                $html .='<br><br><br>';
                $html .='<p class="titulo"><b>CHEQUE</b></p><br><br>';
                $html .='<p>Atentamente,</p><br><br>';
                $html .='<p>Opt. Miguel Reyes Pico</p><br><br>';
                $html .='</body>';
                $html .='</html>';
                break;
            case 'PACIFICO':
                $p1=0;
                $p2=0;
                $p3=360;
                $p4=160;
                //Creamos el PDF
                $html ='<!DOCTYPE html>';
                $html .='<html>';
                $html .='<head>';
                $html .='<meta charset="utf-8">';
                $html .='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
                $html .='<title>Certificado Emetrope</title>';
                $html .='<style type="text/css">';
                $html .='@page {margin-top: 0em;margin-left: 7.11em;margin-right: 7.11em;}';
                $html .='body {font-family: Delicious, sans-serif;font-size: 12px;}';
                $html .='p {text-align : justify;line-height: 1.5em;}';
                $html .='.titulo {text-align : center !important;font-size: 14px;text-decoration: underline;}';
                $html .='</style>   ';
                $html .='</head>';
                $html .='<body> ';
                $html .='<p class="titulo"><b>CHEQUE</b></p>';
                $html .='<p>Atentamente,</p>';
                $html .='<p>Opt. Miguel Reyes Pico</p>';
                $html .='</body>';
                $html .='</html>';
                break;
            default:
                return array(
                    'status'=>'ERROR',
                    'descripcion'=>'No Disponemos el Cheque del Banco Solicitado'
                );
                break;
        }
        //Regresa el html y los tamaños del papel
        return array(
                    'status'=>'OK',
                    'descripcion'=>'Datos Generados',
                    'html'=>$html,
                    'p1'=>$p1,
                    'p2'=>$p2,
                    'p3'=>$p3,
                    'p4'=>$p4
                );
    }
    
    public static function calcCostoDescarga($param=array())
    {
        //Buscar el Factor de Distribucion
        $mrecibimiento = new Entidades\Com30200($param['adapter']);
        $drecibimiento = new Entidades\Com30300($param['adapter']);
        $costo_descarga = new \Entidades\Com20000($param['adapter']);
        $inventario = new Entidades\Inv00000($param['adapter']);
        
        $total_rcb = $mrecibimiento->getMulti('documento', $param['documento']);
        $total_costo_descarga = $costo_descarga->getSumMulti('monto','documento', $param['documento']);
        
        $factor = (($total_costo_descarga['total']+$total_rcb['total'])/$total_rcb['total']);
        
        //Recorrer Detalle
        $dtdetalle = $drecibimiento->getMulti('documento', $param['documento']);
        if (globalFunctions::es_bidimensional($dtdetalle))
        {
            foreach ($dtdetalle as $value) {
                $drecibimiento->updateMultiColum('fob', $value['costo_unitario'], 'id',$value['id']);
                //cif anterior
                $dtinventario = $inventario->getMulti('id', $value['inv00000id']);
                
                $new_cif = $value['cif'];
                if (strlen($dtinventario['cif']>0))
                {
                    $new_cif = number_format((float)($value['costo_unitario']*$factor), $param['DTI_DECIMALCOM'], '.', '');
                }
                //promedio anterior
                if (strlen($dtinventario['promedio']>0))
                {
                    $drecibimiento->updateMultiColum('promedio', number_format((float)(($value['cif']*$new_cif)/2), $param['DTI_DECIMALCOM'], '.', ''), 'id',$value['id']);
                }
                else
                {
                    $drecibimiento->updateMultiColum('promedio', number_format((float)($new_cif), $param['DTI_DECIMALCOM'], '.', ''), 'id',$value['id']);
                }
                $drecibimiento->updateMultiColum('cif', number_format((float)($value['costo_unitario']*$factor), $param['DTI_DECIMALCOM'], '.', ''), 'id',$value['id']);
            }
        }
        else if (isset($dtdetalle['id']))
        {
            $drecibimiento->updateMultiColum('fob', $dtdetalle['costo_unitario'], 'id',$dtdetalle['id']);
            //cif anterior
            $dtinventario = $inventario->getMulti('id', $dtdetalle['inv00000id']);
            
            $new_cif = $dtdetalle['cif'];
            if (strlen($dtinventario['cif']>0))
            {
                $new_cif = number_format((float)($dtdetalle['costo_unitario']*$factor), $param['DTI_DECIMALCOM'], '.', '');
            }
            //promedio anterior
            if (strlen($dtinventario['promedio']>0))
            {
                $drecibimiento->updateMultiColum('promedio', number_format((float)(($dtdetalle['cif']*$new_cif)/2), $param['DTI_DECIMALCOM'], '.', ''), 'id',$dtdetalle['id']);
            }
            else
            {   
                $drecibimiento->updateMultiColum('promedio', number_format((float)($new_cif), $param['DTI_DECIMALCOM'], '.', ''), 'id',$dtdetalle['id']);
            }
            $drecibimiento->updateMultiColum('cif', number_format((float)($dtdetalle['costo_unitario']*$factor), $param['DTI_DECIMALCOM'], '.', ''), 'id',$dtdetalle['id']);
        }
    }
    
    public static function getUltimoDiaMes($elAnio,$elMes)
    {
        return date("d",(mktime(0,0,0,$elMes+1,1,$elAnio)-1));
    }
    
    public static function calcModulo11($xmlObj,$ambiente)
    {
        $fecha = (string) $xmlObj->infoFactura->fechaEmision;
        $a1 = substr($fecha, 0, 1);
        $a2 = substr($fecha, 1, 1);
        $a3 = substr($fecha, 3, 1);
        $a4 = substr($fecha, 4, 1);
        $a5 = substr($fecha, 6, 1);
        $a6 = substr($fecha, 7, 1);
        $a7 = substr($fecha, 8, 1);
        $a8 = substr($fecha, 9, 1);
        $tipoComprobante = (string) $xmlObj->infoTributaria->codDoc;
        $a9 = substr($tipoComprobante, 0, 1);
        $a10 = substr($tipoComprobante, 1, 1);
        $ruc = (string) $xmlObj->infoTributaria->ruc;
        $a11 = substr($ruc, 0, 1);
        $a12 = substr($ruc, 1, 1);
        $a13 = substr($ruc, 2, 1);
        $a14 = substr($ruc, 3, 1);
        $a15 = substr($ruc, 4, 1);
        $a16 = substr($ruc, 5, 1);
        $a17 = substr($ruc, 6, 1);
        $a18 = substr($ruc, 7, 1);
        $a19 = substr($ruc, 8, 1);
        $a20 = substr($ruc, 9, 1);
        $a21 = substr($ruc, 10, 1);
        $a22 = substr($ruc, 11, 1);
        $a23 = substr($ruc, 12, 1);
        $a24 = $ambiente;
        $establecimiento = (string) $xmlObj->infoTributaria->estab;
        $a25 = substr($establecimiento, 0, 1);
        $a26 = substr($establecimiento, 1, 1);
        $a27 = substr($establecimiento, 2, 1);
        $ptoEmi = (string) $xmlObj->infoTributaria->ptoEmi;
        $a28 = substr($ptoEmi, 0, 1);
        $a29 = substr($ptoEmi, 1, 1);
        $a30 = substr($ptoEmi, 2, 1);
        $secuencial = (string) $xmlObj->infoTributaria->secuencial;
        $a31 = substr($secuencial, 0, 1);
        $a32 = substr($secuencial, 1, 1);
        $a33 = substr($secuencial, 2, 1);
        $a34 = substr($secuencial, 3, 1);
        $a35 = substr($secuencial, 4, 1);
        $a36 = substr($secuencial, 5, 1);
        $a37 = substr($secuencial, 6, 1);
        $a38 = substr($secuencial, 7, 1);
        $a39 = substr($secuencial, 8, 1);
        //secuencial
        $a40 = substr($secuencial, 1, 1);
        $a41 = substr($secuencial, 2, 1);
        $a42 = substr($secuencial, 3, 1);
        $a43 = substr($secuencial, 4, 1);
        $a44 = substr($secuencial, 5, 1);
        $a45 = substr($secuencial, 6, 1);
        $a46 = substr($secuencial, 7, 1);
        $a47 = substr($secuencial, 8, 1);
        $a48 = 1;
        //Calcular
        $sumatotal = 0;
        $sumatotal += $a1 * 7;
        $sumatotal += $a2 * 6;
        $sumatotal += $a3 * 5;
        $sumatotal += $a4 * 4;
        $sumatotal += $a5 * 3;
        $sumatotal += $a6 * 2;
        $sumatotal += $a7 * 7;
        $sumatotal += $a8 * 6;
        $sumatotal += $a9 * 5;
        $sumatotal += $a10 * 4;
        $sumatotal += $a11 * 3;
        $sumatotal += $a12 * 2;
        $sumatotal += $a13 * 7;
        $sumatotal += $a14 * 6;
        $sumatotal += $a15 * 5;
        $sumatotal += $a16 * 4;
        $sumatotal += $a17 * 3;
        $sumatotal += $a18 * 2;
        $sumatotal += $a19 * 7;
        $sumatotal += $a20 * 6;
        $sumatotal += $a21 * 5;
        $sumatotal += $a22 * 4;
        $sumatotal += $a23 * 3;
        $sumatotal += $a24 * 2;
        $sumatotal += $a25 * 7;
        $sumatotal += $a26 * 6;
        $sumatotal += $a27 * 5;
        $sumatotal += $a28 * 4;
        $sumatotal += $a29 * 3;
        $sumatotal += $a30 * 2;
        $sumatotal += $a31 * 7;
        $sumatotal += $a32 * 6;
        $sumatotal += $a33 * 5;
        $sumatotal += $a34 * 4;
        $sumatotal += $a35 * 3;
        $sumatotal += $a36 * 2;
        $sumatotal += $a37 * 7;
        $sumatotal += $a38 * 6;
        $sumatotal += $a39 * 5;
        $sumatotal += $a40 * 4;
        $sumatotal += $a41 * 3;
        $sumatotal += $a42 * 2;
        $sumatotal += $a43 * 7;
        $sumatotal += $a44 * 6;
        $sumatotal += $a45 * 5;
        $sumatotal += $a46 * 4;
        $sumatotal += $a47 * 3;
        $sumatotal += $a48 * 2;
        $sumatotal = $sumatotal % 11;
        $valorBuscado = 11 - $sumatotal;
        switch ($valorBuscado) {
            case 11:
                $valorBuscado = 0;
                break;
            case 10:
                $valorBuscado = 1;
                break;
        }
        return substr($fecha, 0, 2).substr($fecha, 3, 2).substr($fecha, 6, 4).$tipoComprobante.$ruc.$ambiente.$establecimiento.$ptoEmi.$secuencial.substr($secuencial, 1, 8).$a48.$valorBuscado;
    }
    
    /**
     * crear la claveAcceso apartir de un array
     * @param type $param fechaEmision / codDoc / ruc / estab
     * / ptoEmi / secuencial
     * @return type string clave de acceso
     */
    public static function calcModulo11Manual($param=array())
    {
        $fecha = $param['fechaEmision'];
        $a1 = substr($fecha, 0, 1);
        $a2 = substr($fecha, 1, 1);
        $a3 = substr($fecha, 3, 1);
        $a4 = substr($fecha, 4, 1);
        $a5 = substr($fecha, 6, 1);
        $a6 = substr($fecha, 7, 1);
        $a7 = substr($fecha, 8, 1);
        $a8 = substr($fecha, 9, 1);
        $tipoComprobante = $param['codDoc'];
        $a9 = substr($tipoComprobante, 0, 1);
        $a10 = substr($tipoComprobante, 1, 1);
        $ruc = $param['ruc'];
        $a11 = substr($ruc, 0, 1);
        $a12 = substr($ruc, 1, 1);
        $a13 = substr($ruc, 2, 1);
        $a14 = substr($ruc, 3, 1);
        $a15 = substr($ruc, 4, 1);
        $a16 = substr($ruc, 5, 1);
        $a17 = substr($ruc, 6, 1);
        $a18 = substr($ruc, 7, 1);
        $a19 = substr($ruc, 8, 1);
        $a20 = substr($ruc, 9, 1);
        $a21 = substr($ruc, 10, 1);
        $a22 = substr($ruc, 11, 1);
        $a23 = substr($ruc, 12, 1);
        $a24 = $param['ambiente'];
        $establecimiento = $param['estab'];
        $a25 = substr($establecimiento, 0, 1);
        $a26 = substr($establecimiento, 1, 1);
        $a27 = substr($establecimiento, 2, 1);
        $ptoEmi = $param['ptoEmi'];
        $a28 = substr($ptoEmi, 0, 1);
        $a29 = substr($ptoEmi, 1, 1);
        $a30 = substr($ptoEmi, 2, 1);
        $secuencial = $param['secuencial'];
        $a31 = substr($secuencial, 0, 1);
        $a32 = substr($secuencial, 1, 1);
        $a33 = substr($secuencial, 2, 1);
        $a34 = substr($secuencial, 3, 1);
        $a35 = substr($secuencial, 4, 1);
        $a36 = substr($secuencial, 5, 1);
        $a37 = substr($secuencial, 6, 1);
        $a38 = substr($secuencial, 7, 1);
        $a39 = substr($secuencial, 8, 1);
        //secuencial
        $a40 = substr($secuencial, 1, 1);
        $a41 = substr($secuencial, 2, 1);
        $a42 = substr($secuencial, 3, 1);
        $a43 = substr($secuencial, 4, 1);
        $a44 = substr($secuencial, 5, 1);
        $a45 = substr($secuencial, 6, 1);
        $a46 = substr($secuencial, 7, 1);
        $a47 = substr($secuencial, 8, 1);
        $a48 = 1;
        //Calcular
        $sumatotal = 0;
        $sumatotal += $a1 * 7;
        $sumatotal += $a2 * 6;
        $sumatotal += $a3 * 5;
        $sumatotal += $a4 * 4;
        $sumatotal += $a5 * 3;
        $sumatotal += $a6 * 2;
        $sumatotal += $a7 * 7;
        $sumatotal += $a8 * 6;
        $sumatotal += $a9 * 5;
        $sumatotal += $a10 * 4;
        $sumatotal += $a11 * 3;
        $sumatotal += $a12 * 2;
        $sumatotal += $a13 * 7;
        $sumatotal += $a14 * 6;
        $sumatotal += $a15 * 5;
        $sumatotal += $a16 * 4;
        $sumatotal += $a17 * 3;
        $sumatotal += $a18 * 2;
        $sumatotal += $a19 * 7;
        $sumatotal += $a20 * 6;
        $sumatotal += $a21 * 5;
        $sumatotal += $a22 * 4;
        $sumatotal += $a23 * 3;
        $sumatotal += $a24 * 2;
        $sumatotal += $a25 * 7;
        $sumatotal += $a26 * 6;
        $sumatotal += $a27 * 5;
        $sumatotal += $a28 * 4;
        $sumatotal += $a29 * 3;
        $sumatotal += $a30 * 2;
        $sumatotal += $a31 * 7;
        $sumatotal += $a32 * 6;
        $sumatotal += $a33 * 5;
        $sumatotal += $a34 * 4;
        $sumatotal += $a35 * 3;
        $sumatotal += $a36 * 2;
        $sumatotal += $a37 * 7;
        $sumatotal += $a38 * 6;
        $sumatotal += $a39 * 5;
        $sumatotal += $a40 * 4;
        $sumatotal += $a41 * 3;
        $sumatotal += $a42 * 2;
        $sumatotal += $a43 * 7;
        $sumatotal += $a44 * 6;
        $sumatotal += $a45 * 5;
        $sumatotal += $a46 * 4;
        $sumatotal += $a47 * 3;
        $sumatotal += $a48 * 2;
        $sumatotal = $sumatotal % 11;
        $valorBuscado = 11 - $sumatotal;
        switch ($valorBuscado) {
            case 11:
                $valorBuscado = 0;
                break;
            case 10:
                $valorBuscado = 1;
                break;
        }
        return substr($fecha, 0, 2).substr($fecha, 3, 2).substr($fecha, 6, 4).$tipoComprobante.$ruc.$param['ambiente'].$establecimiento.$ptoEmi.$secuencial.substr($secuencial, 1, 8).$a48.$valorBuscado;
    }
    
    public static function esDecimal($numero)
    {
        return is_numeric($numero) && floor($numero) != $numero;
    }
    
    /**
     * Crea nuevos cobros
     * @param type $param array 
     * cliente / empresa / usuario / total / formapagoid / 
     * comentario / descripcion / fecha / chequeraid / adapter
     */
    public static function newCobroVentas($param)
    {
        try
        {
            //Realizar el cobro
            $secuencial = new Models\Cc40100Model($param['adapter']);
            $dtsecuencial = $secuencial->getObtenerNum('COBROS VENTAS');

            $secuencial->autocommit();

            $cobro = new \Entidades\Cc20300($param['adapter']);
            $cobro->setCc00000id($param['cliente']);
            $cobro->setDocumento($dtsecuencial['secuencia']);
            $cobro->setEmpresa($param['empresa']);
            $cobro->setUsuario($param['usuario']);
            $cobro->setTotal($param['total']);
            $cobro->setCc40040id($param['formapagoid']); /*Efectivo*/
            $cobro->setComentario($param['comentario']);
            $cobro->setDescripcion($param['descripcion']);
            $cobro->setFecha($param['fecha']);
            $cobro->setFin00010id($param['chequeraid']); /*Chequera*/
            $cobro->save();

            $numDocumento = $dtsecuencial['secuencia'];

            $newsecuencial = (int) $dtsecuencial['secuencia'];
            $newsecuencial += 1;
            switch (strlen($newsecuencial))
            {
                case 1:
                    $newsecuencial = '00000000'.$newsecuencial;
                    break;
                case 2:
                    $newsecuencial = '0000000'.$newsecuencial;
                    break;
                case 3:
                    $newsecuencial = '000000'.$newsecuencial;
                    break;
                case 4:
                    $newsecuencial = '00000'.$newsecuencial;
                    break;
                case 5:
                    $newsecuencial = '0000'.$newsecuencial;
                    break;
                case 6:
                    $newsecuencial = '000'.$newsecuencial;
                    break;
                case 7:
                    $newsecuencial = '00'.$newsecuencial;
                    break;
                case 8:
                    $newsecuencial = '0'.$newsecuencial;
                    break;
            }

            $secuencial->updateMultiColum('secuencia', $newsecuencial, 'tipo', 'COBROS VENTAS');

            //Ingresamos en tablas de Aplicación
            $aplicar = new Entidades\Cc20100($param['adapter']);
            $aplicar->setDocumento($dtsecuencial['secuencia']);
            $aplicar->setEmpresa($param['empresa']);
            $aplicar->setFecha($param['fecha']);
            $aplicar->setPendiente($param['total']);
            $aplicar->setTipo('ECXC');
            $aplicar->setTotal($param['total']);
            $aplicar->setUsuario($param['usuario']);
            $aplicar->setCc00000id($param['cliente']);
            $aplicar->save();

            $valdistribucion = new \Models\Fin20100Model($param['adapter']);
            // **** Tiene comprobantes comprados 
            globalFunctions::getDistribuciones(array(
                    'adapter'=>$param['adapter'],
                    'empresa'=>$param['empresa'],
                    'fin40040id'=>'ECXC',
                    'documento'=>$dtsecuencial['secuencia'],
                ));           
             //Validar las Distribuciones
            $valordistribucion = $valdistribucion->getDiferenciaDistribucion($dtsecuencial['secuencia'], 'ECXC', 0);
            if ($valordistribucion['total']<>0)
            {
                //Enviar a la FIN10000
                //Creamos el asiento en contabilidad
                $mgeneral = new Entidades\Fin10000($param['adapter']);
                $dgeneral = new Entidades\Fin10010($param['adapter']);
                $distribucion = new Entidades\Fin20100($param['adapter']);
                $diarioNum = new \Models\Fin10000Model($param['adapter']);

                $dtdistri = $distribucion->getMulti('modulo', 9, 'fin40040id','ECXC','documento',$dtsecuencial['secuencia']);
                $dtdiario = $diarioNum->getNumDiario();

                $mgeneral->setDiario($dtdiario['diario']);
                $mgeneral->setEmpresa($param['empresa']);
                $mgeneral->setFechaReversion($param['fecha']);
                $mgeneral->setFechaTransaccion($param['fecha']);
                $mgeneral->setFin40040id('ECXC');
                $mgeneral->setFin40050id(1);
                $mgeneral->setReferencia($dtsecuencial['secuencia']);
                $mgeneral->setUsuario($param['usuario']);
                $mgeneral->save();

                $dtmgeneral = $mgeneral->getMulti('diario', $dtdiario['diario']);

                if (globalFunctions::es_bidimensional($dtdistri))
                {
                    foreach ($dtdistri as $value)
                    {
                        $dgeneral->setDiario($dtdiario['diario']);
                        $dgeneral->setEmpresa($param['empresa']);
                        $dgeneral->setFechaReversion($param['fecha']);
                        $dgeneral->setFechaTransaccion($param['fecha']);
                        $dgeneral->setFin00000id($value['fin00000id']);
                        $dgeneral->setFin10000id($dtmgeneral['id']);
                        $dgeneral->setReferencia($dtsecuencial['secuencia']);
                        $dgeneral->setUsuario($param['usuario']);
                        $dgeneral->setDebito($value['debito']);
                        $dgeneral->setCredito($value['credito']);
                        $dgeneral->save();
                    }
                }
                else if (isset($dtdistri['id']))
                {
                    $dgeneral->setDiario($dtdiario['diario']);
                    $dgeneral->setEmpresa($param['empresa']);
                    $dgeneral->setFechaReversion($param['fecha']);
                    $dgeneral->setFechaTransaccion($param['fecha']);
                    $dgeneral->setFin00000id($dtdistri['fin00000id']);
                    $dgeneral->setFin10000id($dtmgeneral['id']);
                    $dgeneral->setReferencia($dtsecuencial['secuencia']);
                    $dgeneral->setUsuario($param['usuario']);
                    $dgeneral->setDebito($dtdistri['debito']);
                    $dgeneral->setCredito($dtdistri['credito']);
                    $dgeneral->save();
                }
            }
            else
            {
                //Enviar a la FIN20000
                //Creamos el asiento en contabilidad
                $contabilizar = new Entidades\Fin20000($param['adapter']);
                $distribucion = new Entidades\Fin20100($param['adapter']);
                $diarioNum = new \Models\Fin10000Model($param['adapter']);

                $dtdistri = $distribucion->getMulti('modulo', 9, 'fin40040id','ECXC','documento',$dtsecuencial['secuencia']);
                $dtdiario = $diarioNum->getNumDiario();

                if (globalFunctions::es_bidimensional($dtdistri))
                {
                    foreach ($dtdistri as $value)
                    {
                        $contabilizar->setAnio(date("Y",strtotime($param['fecha'])));
                        $contabilizar->setCredito($value['credito']);
                        $contabilizar->setDebito($value['debito']);
                        $contabilizar->setDiario($dtdiario['diario']);
                        $contabilizar->setEmpresa($param['empresa']);
                        $contabilizar->setFechaReversion($param['fecha']);
                        $contabilizar->setFechaTransaccion($param['fecha']);
                        $contabilizar->setFin00000id($value['fin00000id']);
                        $contabilizar->setFin40040id('ECXC');
                        $contabilizar->setFin40050id(1);
                        $contabilizar->setPeriodo(date("n",strtotime($param['fecha'])));
                        $contabilizar->setReferencia($dtsecuencial['secuencia']);
                        $contabilizar->setUsuario($param['usuario']);
                        $contabilizar->save();
                    }
                }
                else if (isset($dtdistri['id']))
                {
                    $contabilizar->setAnio(date("Y",strtotime($param['fecha'])));
                    $contabilizar->setCredito($dtdistri['credito']);
                    $contabilizar->setDebito($dtdistri['debito']);
                    $contabilizar->setDiario($dtdiario['diario']);
                    $contabilizar->setEmpresa($param['empresa']);
                    $contabilizar->setFechaReversion($param['fecha']);
                    $contabilizar->setFechaTransaccion($param['fecha']);
                    $contabilizar->setFin00000id($dtdistri['fin00000id']);
                    $contabilizar->setFin40040id('ECXC');
                    $contabilizar->setFin40050id(1);
                    $contabilizar->setPeriodo(date("n",strtotime($param['fecha'])));
                    $contabilizar->setReferencia($dtsecuencial['secuencia']);
                    $contabilizar->setUsuario($param['usuario']);
                    $contabilizar->save();
                }
            }

            //Pasar a la tabla Historica
            $historica = new \Entidades\Cc30300($param['adapter']);
            $historica->setAnticipo(0);
            $historica->setCc00000id($param['cliente']);
            $historica->setCc40040id($param['formapagoid']);
            $historica->setComentario($param['comentario']);
            $historica->setDescripcion($param['descripcion']);
            $historica->setDocumento($dtsecuencial['secuencia']);
            $historica->setEmpresa($param['empresa']);
            $historica->setFecha($param['fecha']);
            $historica->setFin00010id($param['chequeraid']);
            $historica->setTotal($param['total']);
            $historica->setUsuario($param['usuario']);
            $historica->save();

            //Eliminamos de la transaccional
            $cobro->deleteMulti('documento', $dtsecuencial['secuencia']);

            $cobro->commit();
            
            return 'OK';
        }
        catch (Exception $ex)
        {
            return $ex->getMessage();
        }
    }
    
    /**
     * Aplica los cobros faltantes de un documento factura o pedido
     * @param type $param array
     * adapter / cliente 
     */
    public static function setAplicarCobro($param)
    {
        try
        {
            $aplicacion = new \Models\Cc20200Model($param['adapter']);
            $dt_poraplicar = $aplicacion->getCobroXAplicar($param['cliente']);
            if (globalFunctions::es_bidimensional($dt_poraplicar))
            {
                foreach ($dt_poraplicar as $value)
                {
                    //Aplicar solo el valor que tiene pendiente la factura
                    $monto_pendiente = $aplicacion->getMontoFactura($value['docventa']);
                    if (isset($monto_pendiente['pendiente']))
                    {
                        if ($value['pendiente'] <= $monto_pendiente['pendiente'])
                        {
                            //Enlazar la factura con el cobro
                            $daplicar = new Entidades\Cc20200($param['adapter']);
                            $daplicar->setDoccruce($value['doccruce']);
                            $daplicar->setDocventa($value['docventa']);
                            $daplicar->setEmpresa($value['empresa']);
                            $daplicar->setFechacruce($value['fecha']);
                            $daplicar->setTipocruce($value['tipocruce']);
                            $daplicar->setTipoventa($value['tipoventa']);
                            $daplicar->setUsuario($value['usuario']);
                            $daplicar->setValorcruce($value['pendiente']);
                            $daplicar->save();
                        }
                        else
                        {
                            //Enlazar la factura con el cobro
                            $daplicar = new Entidades\Cc20200($param['adapter']);
                            $daplicar->setDoccruce($value['doccruce']);
                            $daplicar->setDocventa($value['docventa']);
                            $daplicar->setEmpresa($value['empresa']);
                            $daplicar->setFechacruce($value['fecha']);
                            $daplicar->setTipocruce($value['tipocruce']);
                            $daplicar->setTipoventa($value['tipoventa']);
                            $daplicar->setUsuario($value['usuario']);
                            $daplicar->setValorcruce($monto_pendiente['pendiente']);
                            $daplicar->save();
                        }
                    }
                }
            }
            else if (isset($dt_poraplicar['id']))
            {
                //Aplicar solo el valor que tiene pendiente la factura
                $monto_pendiente = $aplicacion->getMontoFactura($dt_poraplicar['docventa']);
                if (isset($monto_pendiente['pendiente']))
                {
                    if ($dt_poraplicar['pendiente'] <= $monto_pendiente['pendiente'])
                    {
                        //Enlazar la factura con el cobro
                        $daplicar = new Entidades\Cc20200($param['adapter']);
                        $daplicar->setDoccruce($dt_poraplicar['doccruce']);
                        $daplicar->setDocventa($dt_poraplicar['docventa']);
                        $daplicar->setEmpresa($dt_poraplicar['empresa']);
                        $daplicar->setFechacruce($dt_poraplicar['fecha']);
                        $daplicar->setTipocruce($dt_poraplicar['tipocruce']);
                        $daplicar->setTipoventa($dt_poraplicar['tipoventa']);
                        $daplicar->setUsuario($dt_poraplicar['usuario']);
                        $daplicar->setValorcruce($dt_poraplicar['pendiente']);
                        $daplicar->save();
                    }
                    else
                    {
                        //Enlazar la factura con el cobro
                        $daplicar = new Entidades\Cc20200($param['adapter']);
                        $daplicar->setDoccruce($dt_poraplicar['doccruce']);
                        $daplicar->setDocventa($dt_poraplicar['docventa']);
                        $daplicar->setEmpresa($dt_poraplicar['empresa']);
                        $daplicar->setFechacruce($dt_poraplicar['fecha']);
                        $daplicar->setTipocruce($dt_poraplicar['tipocruce']);
                        $daplicar->setTipoventa($dt_poraplicar['tipoventa']);
                        $daplicar->setUsuario($dt_poraplicar['usuario']);
                        $daplicar->setValorcruce($monto_pendiente['pendiente']);
                        $daplicar->save();
                    }
                }
            }
            return 'OK';
        }
        catch (Exception $ex)
        {
            return $ex->getMessage();
        }
    }
}
