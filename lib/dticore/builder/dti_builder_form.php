<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 16/04/2017
 * Version: 3.0.0
 *
 */
class dti_builder_form extends EntidadBase
{

    private static $ctlVariables,$form,$elementos,$recorrer,$modal,$script,$css,$scriptGeneral;
    public $adapter;

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        if (isset($_SESSION['bdcliente']))
        {
            $table=$_SESSION['bdcliente'].'.sis40130';
        }
        else
        {
            $table='sis40030';
        }
	parent::__construct($table,$adapter);
        if (!isset(self::$ctlVariables)) {
            self::$css = "  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css' />
                            <link href='public/css/componentes/filestyle.css' rel='stylesheet' type='text/css'/>
                            <link href='public/css/componentes/checkboxstyle.css' rel='stylesheet' type='text/css'/>
                            <link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css' rel='stylesheet'/>";

            self::$scriptGeneral="  <script src='public/js/componentes/file/bootstrap-filestyle.min.js' type='text/javascript'></script>
                                    <script src='public/js/componentes/file/dti_file.js' type='text/javascript'></script>
                                    <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js'></script>
                                    <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js.map'></script>
                                    <script src='https://cdnjs.cloudflare.com/ajax/libs/webshim/1.16.0/minified/polyfiller.js'></script>
                                    <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>
                                    <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.es.min.js'></script>";
            self::$ctlVariables = 0;
        }
    }

    public function setForm($maestro,$ordenarX,$get='',$crud='')
    {
        self::$recorrer = 0;
        self::$elementos = "";
        self::$modal = "";
        self::$script = "";
        $cssValidacion = '';
        $cssForm = '';
        //Validar si tiene validaciones
        if ($maestro['validacion'] == 1) {
            $cssValidacion = 'ws-validate';
        }
        //Validar si tiene validaciones
        if (strlen($maestro['css']) > 1) {
            $cssForm = $maestro['css'];
        }
        //Validar si es con formulario
        if ($maestro['conform'] == 1) {
            //Si es con formulario
            self::$form = "<form id='frm".$maestro['entidad']."' name='frm".$maestro['entidad']."' action='".$maestro['accion']."' class='".$cssForm.$cssValidacion." ws-validate' method='".$maestro['metodo']."' enctype='".$maestro['encrypt']."'><div class='form-body'><div class='card-body'>";
        }else{
            //No es con formulario
            self::$form = "";
        }
        //Armar los elementos
        if ($maestro['formulario'] == 'frmDAinventario')
        {
            $campo_adi = new Models\Sis40120Model($this->adapter);
            $detalle = $campo_adi->ejecutarProcedure('call GetFrmDatosAdicionales ("'.$_SESSION['bdcliente'].'","frmDAinventario")');
        }
        else
        {
            $detalle = $this->getByOrderBy('idform', $maestro['formulario'],$ordenarX);
        }
        if (globalFunctions::es_bidimensional($detalle)) {
            foreach ($detalle as $key => $componente) {
                $this->addElement($maestro['columnas'],$maestro['version'],$componente,$get,$crud,$maestro['entidad'],$maestro['colid'],$maestro['colid2']);
            }
        }else{
            $this->addElement($maestro['columnas'],$maestro['version'],$detalle,$get,$crud,$maestro['entidad'],$maestro['colid'],$maestro['colid2']);
        }
    }

    public function getForm()
    {
        if (self::$recorrer <> 0) $this->agrupacion("FIN",'1');
        $formulario = self::$form.self::$elementos."</form> </div></div>".self::$modal.self::$script.self::$css.self::$scriptGeneral;
        return $formulario;
    }

    public function addElement($columnas,$version,$detalle,$get='',$crud='',$entidad='',$columnid='',$columnid2='')
    {
        if (self::$recorrer == $columnas) $this->agrupacion("FIN",$version);
        if (self::$recorrer == 0) $this->agrupacion("INI",$version);
        switch ($detalle['tipo']) {
            case 'time':
                self::$elementos .= $this->newTime($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'text':
                self::$elementos .= $this->newText($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'textarea':
                self::$elementos .= $this->newTextarea($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'email':
                self::$elementos .= $this->newText($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'number':
                self::$elementos .= $this->newText($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'password':
                self::$elementos .= $this->newText($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'checkbox':
                self::$elementos .= $this->newCheckbox($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'date':
                self::$elementos .= $this->newDate($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'file':
                self::$elementos .= $this->newFile($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'select':
                self::$elementos .= $this->newSelect($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
            case 'hidden':
                self::$elementos .= $this->newHidden($columnas,$detalle,$version,$get,$crud,$entidad,$columnid,$columnid2);
                self::$recorrer++;
                break;
        }
    }

    private function agrupacion($dato,$version)
    {
        if ($dato == "INI") {
            switch ($version) {
                case '1':
                    self::$elementos .= "<div class='row'>";
                    break;
                case '2':
                    self::$elementos .= "<div class='form-group row mat-div'>";
                    break;
            }
        }else{
            self::$elementos .= "</div>";
            self::$recorrer = 0;
        }
    }

    private function newText($columnas,$datos,$version,$get='',$crud='',$endidad='',$columnid='',$columnid2='')
    {
        //Declaracion de Variables
        switch ($columnas) {
            case 1:
                $componente = "<div class='col-md-12 col-xs-12'><div class='form-group row'>";
                break;
            default:
                $componente = "<div class='col-md-6 col-xs-12'><div class='form-group row'>";
                break;
        }
        $readonly = "";
        $requerido = "";
        $errorText = "";
        $errorControl = "";
        $valor = "";
        $linklabel = "";
        $minimo = "";
        $maximo = "";
        $labeldet = ""; /*Muestra el detalle de un input con valparam3*/
        //Verificar si tiene minimo y maximo
        if (strlen($datos['minlegth']) > 0 || $datos['tipo'] == 'number')
        {
            if ($datos['tipo'] == 'number'&& $datos['minlegth'] >= 0)
            {
                $minimo = 'min="'.$datos['minlegth'].'"';
            }
            else if($datos['minlegth'] > 0)
            {
                $minimo = 'minlength="'.$datos['minlegth'].'"';
            }
        }
        if (strlen($datos['maxlegth']) > 0 || $datos['tipo'] == 'number')
        {
            if ($datos['tipo'] == 'number' && $datos['maxlegth'] > 0)
            {
                $maximo = 'max="'.$datos['maxlegth'].'"';
            }
            else if($datos['maxlegth'] > 0)
            {
                $maximo = 'maxlength="'.$datos['maxlegth'].'"';
            }
        }
        
        //POner Valores en caso de que tenga id o crud
        if (isset($get['edit']) && strlen($endidad) > 1)
        {
            $model = 'Entidades\\'.ucwords($endidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get['edit'],$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get['edit']);
        }
        else if (strlen($get)>0 && strlen($endidad) > 1)
        {
            $model = 'Entidades\\'.ucwords($endidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get,$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get);
        }
        
        if (isset($get['edit']))
        {
            //Solo lectura en caso de que tenga activado
            if (($datos['readonlyid'] == 1 && strlen($get['edit'])>0) || ($datos['readonlycrud'] == 1 && strlen($crud)>1)) {
                $readonly = "readonly";
            }
        }
        else
        {
            //Solo lectura en caso de que tenga activado
            if (($datos['readonlyid'] == 1 && strlen($get)>0) || ($datos['readonlycrud'] == 1 && strlen($crud)>1)) {
                $readonly = "readonly";
            }
        }
        $valorLink = '';
        
        if ($datos['readonly'] == 1) $readonly = "readonly";
        if ($datos['requerido'] == 1) $requerido = "required = 'true'";
        if (strlen($datos['errortext']) > 1) $errorText = "title='".$datos['errortext']."'";
        if (strlen($datos['errorcontrol']) > 1) $errorControl = "pattern='".$datos['errorcontrol']."'";
        if (strlen($datos['valor']) > 0) $valor = "value = '".$datos['valor']."'";
        if (isset($get['edit']))
        {
            $valor = "value = '".$dtget[$datos['bdd']]."'";
            $valorLink = $dtget[$datos['bdd']];
            if ($datos['valparam3'] != '') {
                switch ($datos['bdd']) {
                    case 'fin00000id':
                        if (strlen($dtget[$datos['bdd']]) > 0) {
                            $tablasecundaria = new Entidades\Fin00000($this->adapter);
                            $dttablasecundaria = $tablasecundaria->getMulti('id',$dtget[$datos['bdd']]);
                            if (isset($dttablasecundaria[$datos['valparam3']])) {
                                $labeldet = "<label id='lbl".$datos['nameid']."' class='control-label'>".$dttablasecundaria[$datos['valparam3']]."</label>";
                            }
                        }
                        break;
                }
            }
            //Si tiene mascara aplicar la mascara
            if (strlen($datos['mascara'])>0)
            {
                if (stristr($datos['mascara'], '.mask("000000000000000.00", {reverse: true});'))
                {
                    $valor = "value = '".number_format((float)$dtget[$datos['bdd']], 2, '.', '')."'";
                }
            }
        }
        else if (strlen($get)>0)
        {
            //Tener cuidado en esta linea ya que no va a mostrar error cuando no exista el campo
            if (isset($dtget[$datos['bdd']]))
            {
                $valor = "value = '".$dtget[$datos['bdd']]."'";
                $valorLink = $dtget[$datos['bdd']];
                if ($datos['valparam3'] != '') {
                    switch ($datos['bdd']) {
                        case 'fin00000id':
                            $tablasecundaria = new Entidades\Fin00000($this->adapter);
                            $dttablasecundaria = $tablasecundaria->getMulti('id',$dtget[$datos['bdd']]);
                            if (isset($dttablasecundaria[$datos['valparam3']])) {
                               $labeldet = "<label id='lbl".$datos['nameid']."' class='control-label'>".$dttablasecundaria[$datos['valparam3']]."</label>"; 
                            }
                            break;
                    }
                }
                //Si tiene mascara aplicar la mascara
                if (strlen($datos['mascara'])>0)
                {
                    if (stristr($datos['mascara'], '.mask("000000000000000.00", {reverse: true});'))
                    {
                        $valor = "value = '".number_format((float)$dtget[$datos['bdd']], 2, '.', '')."'";
                    }
                }
            }
        }

        if (strlen($datos['linkbutton']) > 1) {
            if ($datos['linktipo'] == 'FORM')
            {
                $linklabel = "<a target='_blank' id='idlink' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' href='".$datos['linkbutton']."/".$datos['linkparametro']."/$valorLink'><label class='control-label'>".$datos['titulo']."</label></a>";
            }
            if ($datos['linktipo'] == 'EDIT')
            {
                $linklabel = "<a class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' data-toggle='modal' data-target='#model".$datos['linkid']."'><label class='control-label'>".$datos['titulo']."</label></a>";
                self::$modal .= $this->newModal($datos);
            }
            else
            {
                $linklabel = "<a class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' data-toggle='modal' data-target='#model".$datos['nameid']."'><label for = '".$datos['nameid']."' class='control-label'>".$datos['titulo']."</label></a>";
                self::$modal .= $this->newModal($datos,$get);
            }
        }
        else{
            $linklabel = "<label for = '".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>";
        }
        
        $mayusculas = '';
        if (isset($datos['mayusculas'])) {
            if ($datos['mayusculas'] == 1) {
                $mayusculas = 'onkeyup="mayus(this);"';
            }
        }
        
        switch ($version) {
            case '1':
                if (strlen($datos['icono']) > 1)
                {
                    $componente .= $linklabel . //"<label for = '".$datos['nameid']."' class='col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label'>".$datos['titulo']."</label>
                                "<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <div class='input-group has-clear'>
                                        <input aria-describedby='btn".$datos['nameid']."' type='".$datos['tipo']."' class='".$datos['css']."' id='".$datos['nameid']."' name='".$datos['nameid']."' placeholder='".$datos['placeholder']."' ".$readonly." ".$requerido." ".$errorText." ".$errorControl." ".$valor." ".$minimo." ".$maximo." />";
                        if (strlen($datos['modal']) > 1)
                        {
                           
                            if (strlen($datos['valparam'])>1)
                            {
                                $modal_build = new dti_builder_modal();
                                $modal_build->setModal(array(
                                    'id'=>'model'.$datos['nameid'],
                                    'tipo'=>'search',
                                    'titulo'=>$datos['titulo'],
                                    'url'=>$datos['controller'].'/'.$datos['nomparam'],
                                    'json'=>array(
                                                'antes'=>"var id = document.getElementById('".$datos['valparam']."').value;",
                                                'data'=>"{'search':search,'id':id,'page':page,'accion':'".$datos['accion']."'}",
                                            ),
                                    'mensaje'=>"<div id='loadermodel".$datos['nameid']."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                                <div class='outer_divmodel".$datos['nameid']."'></div>",
                                    'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
                                ));
                                self::$modal .= $modal_build->getModal();
                                $componente .= "<span class='form-control-clear input-group-addon btn btn-primary fa fa-remove'></span>";
                                $componente .= "<span class='input-group-addon mat-img' id='btn".$datos['nameid']."' data-toggle='modal' data-target='#model".$datos['nameid']."' type='button'><span class='".$datos['icono']."'></span></span>";
                            }
                            else
                            {
                                $modal_build = new dti_builder_modal();
                                $modal_build->setModal(array(
                                    'id'=>'model'.$datos['nameid'],
                                    'tipo'=>'search',
                                    'titulo'=>$datos['titulo'],
                                    'url'=>$datos['controller'].'/'.$datos['nomparam'],
                                    'json'=>array(
                                                'data'=>"{'search':search,'page':page,'accion':'".$datos['accion']."'}",
                                            ),
                                    'mensaje'=>"<div id='loadermodel".$datos['nameid']."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                                <div class='outer_divmodel".$datos['nameid']."'></div>",
                                    'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
                                ));
                                self::$modal .= $modal_build->getModal();
                                $componente .= "<span class='form-control-clear input-group-addon btn btn-primary fa fa-remove'></span>";
                                $componente .= "<span class='input-group-addon mat-img' id='btn".$datos['nameid']."' data-toggle='modal' data-target='#model".$datos['nameid']."' type='button'><span class='".$datos['icono']."'></span></span>";
                            }
                        }
                        else{
                            $componente .= "<a class='input-group-addon mat-img' id='basic-".$datos['nameid']."'><i class='".$datos['icono']."'></i></a>";
                        }
                    $componente .= $labeldet."</div>
                                </div>";
                }
                else
                {
                    $componente .= $linklabel . //"<label for = '".$datos['nameid']."' class='col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label'>".$datos['titulo']."</label>
                                "<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <input type='".$datos['tipo']."' class='".$datos['css']."' id='".$datos['nameid']."' name='".$datos['nameid']."' ".$mayusculas." placeholder='".$datos['placeholder']."' ".$readonly." ".$requerido." ".$errorText." ".$errorControl." ".$valor." ".$minimo." ".$maximo." />".$labeldet."
                                </div>";
                }
                break;
            case '2':
                if (strlen($datos['icono']) > 1) {
                    $componente .= "<label for='".$datos['nameid']."' class='mat-label'>".$datos['titulo']."</label>
                                <div class='input-group'>
                                    <input type='".$datos['tipo']."' id='".$datos['nameid']."' name='".$datos['nameid']."' class='mat-input' aria-describedby='basic-".$datos['nameid']."' ".$readonly." ".$requerido." ".$errorText." ".$errorControl." ".$valor." ".$minimo." ".$maximo." >";
                        if (strlen($datos['modal']) > 1) {
                            $modal_build = new dti_builder_modal();
                            $modal_build->setModal(array(
                                'id'=>'model'.$datos['nameid'],
                                'tipo'=>'search',
                                'titulo'=>$datos['titulo'],
                                'url'=>$datos['controller'].'/'.$datos['nomparam'],
                                'json'=>array(
                                            'antes'=>"var id = document.getElementById('".$datos['valparam']."').value;",
                                            'data'=>"{'search':search,'id':id,'page':page,'accion':'".$datos['accion']."'}",
                                        ),
                                'mensaje'=>"<div id='loadermodel".$datos['nameid']."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                            <div class='outer_divmodel".$datos['nameid']."'></div>",
                                'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
                            ));
                            self::$modal .= $modal_build->getModal();
                            $componente .= "<span class='input-group-addon mat-img' id='btn".$datos['nameid']."' data-toggle='modal' data-target='#model".$datos['nameid']."' type='button'><span class='".$datos['icono']."'></span></span>";
                        }else{
                            $componente .= "<a class='input-group-addon mat-img' id='basic-".$datos['nameid']."'><i class='".$datos['icono']."'></i></a>";
                        }
                    $componente .= $labeldet."</div>";
                }else{
                    $componente .= "<label for='".$datos['nameid']."' class='mat-label'>".$datos['titulo']."</label>
                                <div class='input-group'>
                                    <input type='".$datos['tipo']."' id='".$datos['nameid']."' name='".$datos['nameid']."' ".$mayusculas." class='mat-input' aria-describedby='basic-".$datos['nameid']."' ".$readonly." ".$requerido." ".$errorText." ".$errorControl." ".$valor." ".$minimo." ".$maximo." >".$labeldet."
                                </div>";
                }
                break;
        }
        
        if (isset($datos['mascara']))
        {
            $componente .= '<script type="text/javascript">
                        $(document).ready(function($){
                            '.$datos['mascara'].'
                        });
                    </script>';
        }
        
        return $componente.'</div></div>';
    }

    private function newTextarea($columnas,$datos,$version,$get='',$crud='',$endidad='',$columnid='',$columnid2='')
    {
        $componente = "";
        $readonly = "";
        $requerido = "";
        $errorText = "";
        $errorControl = "";
        $valor = "";
        if ($datos['readonly'] == 1) $readonly = "readonly";
        if ($datos['requerido'] == 1) $requerido = "required = 'true'";
        if (strlen($datos['errortext']) > 1) $errorText = "title='".$datos['errortext']."'";
        if (strlen($datos['errorcontrol']) > 1) $errorControl = "pattern='".$datos['errorcontrol']."'";
        if (strlen($datos['valor']) > 1) $valor = "value = '".$datos['valor']."'";
        switch ($version) {
            case '1':
                if (strlen($datos['icono']) > 1) {
                    $componente .= "<label for = '".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                                <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <div class='input-group'>
                                        <textarea rows='4' cols='50' class='".$datos['css']."' id='".$datos['nameid']."' name='".$datos['nameid']."' placeholder='".$datos['placeholder']."' ".$readonly." ".$requerido." ".$errorText." ".$errorControl." > ".$valor." </textarea>";
                       if (strlen($datos['modal']) > 1) {
                            self::$modal .= $this->newModal($datos);
                            $componente .= "<span class='input-group-addon mat-img' id='btn".$datos['nameid']."' data-toggle='modal' data-target='#model".$datos['nameid']."' type='button'><span class='".$datos['icono']."'></span></span>";
                        }else{
                            $componente .= "<a class='input-group-addon mat-img' id='basic-".$datos['nameid']."'><i class='".$datos['icono']."'></i></a>";
                        }
                    $componente .= "</div>
                                </div>";
                }else{
                    $componente .= "<label for = '".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                                <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <textarea rows='4' cols='50' class='".$datos['css']."' id='".$datos['nameid']."' name='".$datos['nameid']."' placeholder='".$datos['placeholder']."' ".$readonly." ".$requerido." ".$errorText." ".$errorControl."> ".$valor." </textarea>
                                </div>";
                }
                break;
            case '2':
                if (strlen($datos['icono']) > 1) {
                    $componente .= "<label for='".$datos['nameid']."' class='mat-label'>".$datos['titulo']."</label>
                                <div class='input-group'>
                                    <textarea rows='4' cols='50' id='".$datos['nameid']."' name='".$datos['nameid']."' class='mat-input' aria-describedby='basic-".$datos['nameid']."' ".$readonly." ".$requerido." ".$errorText." ".$errorControl." > ".$valor." </textarea>";
                        if (strlen($datos['modal']) > 1) {
                            self::$modal .= $this->newModal($datos);
                            $componente .= "<span class='input-group-addon mat-img' id='btn".$datos['nameid']."' data-toggle='modal' data-target='#model".$datos['nameid']."' type='button'><span class='".$datos['icono']."'></span></span>";
                        }else{
                            $componente .= "<a class='input-group-addon mat-img' id='basic-".$datos['nameid']."'><i class='".$datos['icono']."'></i></a>";
                        }
                    $componente .= "</div>";
                }else{
                    $componente .= "<label for='".$datos['nameid']."' class='mat-label'>".$datos['titulo']."</label>
                                <div class='input-group'>
                                    <textarea rows='4' cols='50' id='".$datos['nameid']."' name='".$datos['nameid']."' class='mat-input' aria-describedby='basic-".$datos['nameid']."' ".$readonly." ".$requerido." ".$errorText." ".$errorControl."> ".$valor." </textarea>
                                </div>";
                }
                break;
        }
        return $componente;
    }

    private function newModal($datos,$get='')
    {
        $post = '';
        $accion = '';
        switch ($datos['linktipo'])
        {
            case 'MODAL':
                $modal = "<!-- Modal FORM -->
                    <div class='modal fade bs-example-modal-lg' id='model".$datos['nameid']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                      <div class='modal-dialog modal-lg' role='document'>
                            <div class='modal-content'>
                              <div class='modal-header'>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                    <h4 class='modal-title' id='myModalLabel'>".$datos['titulo']."</h4>
                              </div>
                              <div class='modal-body'>
                                    <div id='loader".$datos['nameid']."' style='position: absolute;	text-align: center; top: 55px; width: 100%;display:none;'></div><!-- Carga gif animado -->
                                    <div class='outer_div".$datos['nameid']."' ></div><!-- Datos ajax Final -->
                              </div>
                              <div class='modal-footer'>
                                    <button type='button' class='btn btn-primary btn-small' data-dismiss='modal'>Cerrar</button>
                              </div>
                            </div>
                      </div>
                    </div>";
                if (strlen($get)>1) {
                    $post = ',id: '.$get.'';
                }
                if (strlen($datos['accion']) > 1) {
                    $accion = ',accion: '.$datos['accion'].'';
                }
                self::$script .= '<script type="text/javascript">$(document).ready(function(){ '.$datos['nameid'].'(); });
                                        function '.$datos['nameid'].'(){
                                        var q= $("#'.$datos['parametro2'].'").val();
                                        $("#loader'.$datos['nameid'].'").fadeIn("slow");
                                        $.ajax({
                                                //Escogemos la URL donde vamos a buscar.
                                                url:"'.CONTROLADOR_DEFECTO.'/'.$datos['linkparametro'].'/",
                                                //Escogemos el metodo de envio en esta caso POST.
                                                data:{val: q '.$post.' '.$accion.'},
                                                type: "post",
                                                //Mostramos una imagen y la palabra cargando mientras espera.
                                                beforeSend: function(){
                                                    $("#loader'.$datos['nameid'].'").html("<img src=\'public/images/ajax-loader.gif\'> Cargando...");
                                                },
                                                //Una vez que termino mostramos los datos y limpiamos el cargando.
                                                success:function(data){
                                                    $(".outer_div'.$datos['nameid'].'").html(data).fadeIn("slow");
                                                    $("#loader'.$datos['nameid'].'").html("");
                                                }
                                            });
                                    }</script>';
                break;
            case 'EDIT':
                $modal = "<!-- Modal -->
                <div class='modal fade bs-example-modal-lg' id='model".$datos['linkid']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                  <div class='modal-dialog modal-lg' role='document'>
                        <div class='modal-content'>
                          <div class='modal-header'>
                            <h4 class='modal-title' id='myModalLabel'>".$datos['titulo']."</h4>                                
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                          </div>
                          <div class='modal-body'>
                                <div id='loader".$datos['linkid']."' style='position: absolute;	text-align: center; top: 55px; width: 100%;display:none;'></div><!-- Carga gif animado -->
                                <div class='outer_div".$datos['linkid']."' ></div><!-- Datos ajax Final -->
                          </div>
                          <div class='modal-footer'>
                                <button type='button' class='btn btn-primary btn-small' data-dismiss='modal'>Cerrar</button>
                          </div>
                        </div>
                  </div>
                </div>";
                $modal .= "<script type='text/javascript'>$(document).ready(function(){ 
                                $('#model".$datos['linkid']."').on('show.bs.modal', function () {
                                    var edit = document.getElementById('".$datos['nameid']."').value;
                                    $.ajax({
                                        //Escogemos la URL donde vamos a buscar.
                                        url:'".$datos['linkbutton']."/".$datos['linkparametro']."/',
                                        //Escogemos el metodo de envio en esta caso POST.
                                        data:{'edit': edit,'panel': true,'url':'".$datos['linkurl']."'},
                                        type: 'post',
                                        dataType: 'json',
                                        //Mostramos una imagen y la palabra cargando mientras espera.
                                        beforeSend: function(){
                                            $('#loader".$datos['linkid']."').html('<img src=\'public/images/ajax-loader.gif\'> Cargando...');
                                        },
                                        //Una vez que termino mostramos los datos y limpiamos el cargando.
                                        success:function(data){
                                            $('.outer_div".$datos['linkid']."').html(data.layout).fadeIn('slow');
                                            $('#loader".$datos['linkid']."').html('');
                                            $('._MODAL_').html(data.modal).fadeIn('slow');
                                            $('._SCRIPT_').html(data.script).fadeIn('slow');
                                        }
                                    });
                                });
                            });</script>";
                break;
            default:
                $modal = "<!-- Modal -->
                <div class='modal fade bs-example-modal-lg' id='model".$datos['nameid']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                  <div class='modal-dialog modal-lg' role='document'>
                        <div class='modal-content'>
                          <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                <h4 class='modal-title' id='myModalLabel'>".$datos['titulo']."</h4>
                          </div>
                          <div class='modal-body'>
                                <div class='input-group row'>
                                    <div class='col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                        <input type='text' class='form-control' id='q".$datos['nameid']."' placeholder='Buscar ".$datos['titulo']."' onkeyup='".$datos['modal']."(1)'>
                                    </div>
                                    <div class='col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                        <button type='button' class='btn btn-info' onclick='".$datos['modal']."(1)'><span class='glyphicon glyphicon-search'></span> Buscar</button>
                                    </div>
                                </div>
                                <div id='loader".$datos['nameid']."' style='position: absolute;	text-align: center; top: 55px; width: 100%;display:none;'></div><!-- Carga gif animado -->
                                <div class='outer_div".$datos['nameid']."' ></div><!-- Datos ajax Final -->
                          </div>
                          <div class='modal-footer'>
                                <button type='button' class='btn btn-primary btn-small' data-dismiss='modal'>Cerrar</button>
                          </div>
                        </div>
                  </div>
                </div>";
                if (strlen($datos['accion']) > 1)
                {
                    $accion = ',accion: "'.$datos['accion'].'"';
                }
                if (strlen($datos['nomparam2']) > 1)
                {
                    self::$script .= '<script type="text/javascript"> $(document).ready(function(){
                                    //Declaramos que por defecto ya busque datos.
                                    '.$datos['modal'].'(1);
                                });
                                
                                    function '.$datos['modal'].'(page=1){
                                        //Cojemos la variable.
                                        var q= $("#q'.$datos['nameid'].'").val();
                                        var w= $("#'.$datos['parametro2'].'").val();
                                        $("#loader'.$datos['nameid'].'").fadeIn("slow");
                                        $.ajax({
                                                //Escogemos la URL donde vamos a buscar.
                                                url:"'.CONTROLADOR_DEFECTO.'/'.$datos['parametro'].'/",
                                                //Envialos los parametros.
                                                data: {page: page,q: q,w: w '.$accion.'},
                                                //Escogemos el metodo de envio en esta caso POST.
                                                type: "post",
                                                //Mostramos una imagen y la palabra cargando mientras espera.
                                                beforeSend: function(){
                                                    $("#loader'.$datos['nameid'].'").html("<img src=\'public/images/ajax-loader.gif\'> Cargando...");
                                                },
                                                //Una vez que termino mostramos los datos y limpiamos el cargando.
                                                success:function(data){
                                                    $(".outer_div'.$datos['nameid'].'").html(data).fadeIn("slow");
                                                    $("#loader'.$datos['nameid'].'").html("");
                                                }
                                            });
                                    }</script>';
                }
                else
                {
                    self::$script .= '<script>function '.$datos['modal'].'(page=1){
                                        //Cojemos la variable.
                                        var q= $("#q'.$datos['nameid'].'").val();
                                        $("#loader'.$datos['nameid'].'").fadeIn("slow");
                                        $.ajax({
                                                //Escogemos la URL donde vamos a buscar.
                                                url:"'.CONTROLADOR_DEFECTO.'/'.$datos['nomparam'].'/",
                                                //Envialos los parametros.
                                                data: {page: page,q: q '.$accion.'},
                                                //Escogemos el metodo de envio en esta caso POST.
                                                type: "post",
                                                //Mostramos una imagen y la palabra cargando mientras espera.
                                                beforeSend: function(){
                                                    $("#loader'.$datos['nameid'].'").html("<img src=\'public/images/ajax-loader.gif\'> Cargando...");
                                                },
                                                //Una vez que termino mostramos los datos y limpiamos el cargando.
                                                success:function(data){
                                                    $(".outer_div'.$datos['nameid'].'").html(data).fadeIn("slow");
                                                    $("#loader'.$datos['nameid'].'").html("");
                                                }
                                            });
                                    }</script>';
                }
                break;
        }
        return $modal;
    }

    private function newHidden($columnas,$datos,$version,$get='',$crud='',$endidad='',$columnid='',$columnid2='')
    {
        $componente = "";
        $valor = "";
        if (strlen($datos['valor']) > 1) $valor = "value = '".$datos['valor']."'";
        if (isset($get['edit']) && strlen($endidad) > 1)
        {
            $model = 'Entidades\\'.ucwords($endidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get['edit'],$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get['edit']);
            $valor = "value = '".$dtget[$datos['bdd']]."'";
        }
        else if (strlen($get)>0 && strlen($endidad) > 1)
        {
            $model = 'Entidades\\'.ucwords($endidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get,$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get);
            $valor = "value = '".$dtget[$datos['bdd']]."'";
        }

        switch ($version) {
            case '1':
                $componente .= "<input type='".$datos['tipo']."' class='".$datos['css']."' id='".$datos['nameid']."' name='".$datos['nameid']."' placeholder='".$datos['placeholder']."' ".$valor." />";
                break;
            case '2':
                $componente .= "<input type='".$datos['tipo']."' id='".$datos['nameid']."' name='".$datos['nameid']."' class='mat-input' aria-describedby='basic-".$datos['nameid']."' ".$valor." >";
                break;
        }
        return $componente;
    }

    private function newCheckbox($columnas,$datos,$version,$get='',$crud='',$endidad='',$columnid='',$columnid2='')
    {
        $componente = "";
        $valor = "";
        $check = "";
        if (strlen($datos['valor']) == '1') $check = "checked";
         //POner Valores en caso de que tenga id o crud
        if (isset($get['edit']) && strlen($endidad) > 1)
        {
            $model = '\\Entidades\\'.ucwords($endidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get['edit'],$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get['edit']);
            if ($dtget[$datos['bdd']] == "SI") {
                $check = "checked";
            }else if ($dtget[$datos['bdd']] == "1") {
                $check = "checked";
            }ELSE{
                $check = "";
            }
        }
        else if (strlen($get)>0 && strlen($endidad) > 1)
        {
            $model = '\\Entidades\\'.ucwords($endidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get,$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get);
            if ($dtget[$datos['bdd']] == "SI")
            {
                $check = "checked";
            }
            else if ($dtget[$datos['bdd']] == "1")
            {
                $check = "checked";
            }
            else
            {
                $check = "";
            }
        }
        switch ($version) {
            case '2':
                $componente = "<label ".$datos['nameid']." class='col-lg-2 col-md-2 col-sm-6 col-xs-12 control-label'>".$datos['titulo']."</label>
                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                    <label class='dti_switch'>
                                    <input class='switch-input' type='".$datos['tipo']."' />
                                    <span class='switch-label' ".$check."></span> <span class='switch-handle'></span></label>
                                </div>";
                break;
            case '1':
                $componente = "<label for='".$datos['nameid']."' class='col-lg-2 col-md-2 col-sm-6 col-xs-12 control-label'>".$datos['titulo']."</label>
                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12 checkbox-success'>
                                    <input id='".$datos['nameid']."' name='".$datos['nameid']."' type='".$datos['tipo']."' ".$check.">
                                </div>";
                break;
        }
        return $componente;
    }

    private function newDate($columnas,$datos,$version,$get='',$crud='',$endidad='',$columnid='',$columnid2='')
    {
        $defaultFecha = "";
        $defaultFechaJS = '$("#'.$datos['nameid'].'").datepicker("setDate", new Date());';
        if (isset($get['edit']) && strlen($endidad) > 1)
        {
            $model = '\\Entidades\\'.ucwords($endidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get['edit'],$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get['edit']);
            $defaultFecha = substr($dtget[$datos['bdd']],0,10);
            if (strtotime('2018-01-01')<=strtotime($defaultFecha))
            {
                $defaultFechaJS = '';
            }
        }
        else if (strlen($get)>0 && strlen($endidad) > 1)
        {
            $model = '\\Entidades\\'.ucwords($endidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get,$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get);
            $defaultFecha = substr($dtget[$datos['bdd']],0,10);
            if (strtotime('2018-01-01')<=strtotime($defaultFecha))
            {
                $defaultFechaJS = '';
            }
        }
        $componente = "<div class='col-md-6 col-xs-12'><div class='form-group row'>";
        $componente .= "<label for='".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                        <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                            <div class='input-group'>
                                <input class='form-control' data-date-format='yyyy-mm-dd' id='".$datos['nameid']."' name='".$datos['nameid']."' value='".$defaultFecha."'>
                            </div>
                       </div>";
        
        $startDate = '';
        
        if (isset($datos['nomparam']))
        {
            if ($datos['nomparam'] == 'startDate' && $datos['valparam'])
            {
                $startDate = 'startDate: "'.$datos['valparam'].'",';
            }
            else
            {
                $startDate = 'startDate: "-15d",';
            }
        }
        else
        {
            $startDate = 'startDate: "-15d",';
        }
        
        $endDate = '';
        
        if (isset($datos['nomparam2']))
        {
            if ($datos['nomparam2'] == 'endDate' && $datos['valparam2'])
            {
                $endDate = 'endDate: "'.$datos['valparam2'].'",';
            }
            else
            {
                $endDate = 'endDate: "+1d",';
            }
        }
        else
        {
            $endDate = 'endDate: "+1d",';
        }
        
        if (isset($datos['nomparam3']))
        {
            if ($datos['nomparam3'] == 'datesDisabled' && $datos['valparam3'])
            {
                $datesDisabled = 'datesDisabled: "'.$datos['valparam3'].'",';
            }
            else
            {
                $datesDisabled = '';
            }
        }
        else
        {
            $datesDisabled = '';
        }
        
        $componente .= '<script type="text/javascript">
                        $("#'.$datos['nameid'].'").datepicker({
                            weekStart: 1,
                            autoclose: true,
                            language: "es",
                            todayHighlight: true,
                            '.$startDate.'
                            '.$endDate.'
                            '.$datesDisabled.'
                        });
                        '.$defaultFechaJS.'
                    </script>';
                    
        return $componente."</div></div>";
    }
    
    private function newTime($columnas,$datos,$version,$get='',$crud='',$endidad='',$columnid='',$columnid2='')
    {
        $componente = "<div class='col-md-6 col-xs-12'><div class='form-group row'>";
        $componente .= "<label for='".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                        <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                            <div class='input-group'>
                                <input type='".$datos['tipo']."' class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'>
                            </div>
                       </div>";
        
//        $componente .= '<script type="text/javascript">
//                        $(document).ready(function($){
//                            //$("#'.$datos['nameid'].'").mask("00:00:00");
//                        });
//                    </script>';

        return $componente."</div></div>";
    }
    
    private function newFile($columnas,$datos,$version,$get='',$crud='',$endidad='',$columnid='',$columnid2='')
    {
        $componente = "<div class='col-md-6 col-xs-12'><div class='form-group row'>";
        switch ($version) {
            case '1':
                
                $value = "";
                $ruta = "";
                if (isset($get['edit']) && strlen($endidad) > 1)
                {
                    $model = 'Entidades\\'.ucwords($endidad);
                    $mget = new $model($this->adapter);
                    if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get['edit'],$columnid2,$crud);
                    else $dtget = $mget->getByTop1($columnid,$get['edit']);
                    if (strlen($dtget[$datos['bdd']])>0) {
                        $ruta = "src='".$dtget[$datos['bdd']]."'";
                        $value = "".$dtget[$datos['bdd']]."";
                    }
                }
                else if (strlen($get)>0 && strlen($endidad) > 1)
                {
                    $model = 'Entidades\\'.ucwords($endidad);
                    $mget = new $model($this->adapter);
                    if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get,$columnid2,$crud);
                    else $dtget = $mget->getByTop1($columnid,$get);
                    if (strlen($dtget[$datos['bdd']])>0) {
                        $ruta = "src='".$dtget[$datos['bdd']]."'";
                        $value = "".$dtget[$datos['bdd']]."";
                    }
                }
                
                $componente .= "<label ".$datos['nameid']." class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                            <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                <input type='".$datos['tipo']."' id='".$datos['nameid']."' class='filestyle' data-text='Buscar Archivo' data-buttonBefore='true' data-placeholder='".$value."' data-buttonText='Seleccionar ".$datos['titulo']."' data-buttonName='btn-primary' >
                            </div>";
                            if ($datos['nomparam'] == 'vistaprevia' && $datos['valparam'] == 'SI') {
                                $componente .= "<img id='img".$datos['nameid']."' width='100px' height='100px' ".$ruta."/>";
                            }
                
                $componente .= "<script>
                                    $(document).ready( function() {

                                        function readURL(input,img) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function (e) {
                                                    $('#'+img).attr('src', e.target.result);
                                                }

                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }

                                        $('#". $datos['nameid']."').change(function(){
                                            readURL(this,'img".$datos['nameid']."');
                                        });
                                    });
                                </script>";
                
                break;
        }
        return $componente.'</div></div>';
    }

    private function newSelect($columnas,$datos,$version,$get='',$crud='',$entidad='',$columnid='',$columnid2='')
    {
        $valDefecto = '';
        $valDefectoPadre = '';
        switch ($columnas) {
            case 1:
                $componente = "<div class='col-md-12 col-xs-12'><div class='form-group row'>";
                break;
            default:
                $componente = "<div class='col-md-6 col-xs-12'><div class='form-group row'>";
                break;
        }
        $script = '';
        $padre = '';
        $param2 = '';
        $valorLink = '';
        //POner Valores en caso de que tenga id o crud
        if (isset($get['edit']) && strlen($entidad) > 1)
        {
            $model = 'Entidades\\'.ucwords($entidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get['edit'],$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get['edit']);
            
            if (strlen($datos['nomparam3'])>0)
            {
                $valDefecto = ', "getaux" : "'.$dtget[$datos['nomparam3']].'", "get" : "'.$dtget[$datos['bdd']].'"';
            }
            else
            {
                $valDefecto = ', "get" : "'.$dtget[$datos['bdd']].'"';
            }
        }
        else if (strlen($get)>0 && strlen($entidad) > 1)
        {
            $model = 'Entidades\\'.ucwords($entidad);
            $mget = new $model($this->adapter);
            if (strlen($crud)>1) $dtget = $mget->getMulti($columnid,$get,$columnid2,$crud);
            else $dtget = $mget->getByTop1($columnid,$get);

            if (strlen($datos['nomparam3'])>0)
            {
                $valDefecto = ', "getaux" : "'.$dtget[$datos['nomparam3']].'", "get" : "'.$dtget[$datos['bdd']].'"';
            }
            else
            {
                $valDefecto = ', "get" : "'.$dtget[$datos['bdd']].'"';
            }
        }
        
        if (strlen($datos['linkbutton']) > 1)
        {
            if ($datos['linktipo'] == 'FORM')
            {
                $linklabel = "<a target='_blank' id='idlink' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' href='".$datos['linkbutton']."/".$datos['linkparametro']."/$valorLink'><label class='control-label'>".$datos['titulo']."</label></a>";
            }
            else if ($datos['linktipo'] == 'EDIT')
            {
                $linklabel = "<a class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' data-toggle='modal' data-target='#model".$datos['linkid']."'><label class='control-label'>".$datos['titulo']."</label></a>";
                self::$modal .= $this->newModal($datos);
            }
            else
            {
                $linklabel = "<a class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' data-toggle='modal' data-target='#model".$datos['nameid']."'><label for = '".$datos['nameid']."' class='control-label'>".$datos['titulo']."</label></a>";
                self::$modal .= $this->newModal($datos,$get);
            }
        }
        else
        {
            $linklabel = "<label for = '".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>";
        }
        
        if (strlen($datos['titulo']) > 0)
        {
            $componente .= $linklabel . "
                            <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                <select class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'></select>
                                    
                            </div>";
        }
        else
        {
            $componente .= "<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                <select class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'></select>
                            </div>";
        }
        
        //Valor por defecto
        if (strlen($valDefecto)==0 && strlen($datos['valor']) > 0)
        {
            $valDefecto = ', "get" : "'.$datos['valor'].'"';
        }
        
        //Where por defecto
        if (strlen($datos['nomparam2']) > 0)
        {
            $padre = ', "opcion" : "'.$datos['nomparam2'].'"';
        }
        
        //Validar si tiene padre
        if (strlen($datos['nomparam']) > 0)
        {
            $padre = ', "opcion" : "'.$datos['nomparam'].'"';
            
            if (strlen($datos['valparam2']) > 0) {
                $padre = '';
            }
            if (strlen($datos['valparam2']) > 0) {
                $param2 = '$("#'.trim($datos['valparam2']).'").html(response).fadeIn();';
            }
            //'.$valDefectoPadre.'
            self::$script .= '<script type="text/javascript">  $("#'.$datos['nameid'].'").change(function(){
                            var padre = $(this);
                            if($(this).val() != "")
                            {
                                $.ajax({
                                    data: {"modelo" : "'.trim($datos['nomparam']).'" , "parametro": $(this).val()  },
                                    url: "'.$datos['controller'].'/getSelect", type:  "POST",
                                    success: function(response) { $("#'.trim($datos['valparam']).'").html(response).fadeIn(); '.$param2.' }
                                });
                            }
                        })</script>';
        }
        self::$script .= ' <script type="text/javascript"> $(document).ready(function() { $.ajax({ url: "'.$datos['controller'].'/getSelect",  data: {"modelo" : "'.$datos['combobox'].'" '.$padre.' '.$valDefecto.'},type: "post", success: function(response) { $("#'.$datos['nameid'].'").html(response).fadeIn(); } }); }); </script>';
        
        return $componente.'</div></div>';
    }
    
    public function createSelect($datos)
    {
        if (isset($datos['simple']))
        {
            $componente = '';
            $script = '';
            $valdefecto = '';
            if (isset($datos['valor'])) {
                $valdefecto = ', "get" : "'.$datos['valor'].'"';
            }
            if (strlen($datos['titulo']) > 0) {
                $componente .= "<label for = '".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                                <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <select class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'></select>
                                </div>";
            }else{
                $componente .= "<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <select class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'></select>
                                </div>";
            }
            dti_core::set('script', ' <script type="text/javascript"> $(document).ready(function() { $.ajax({ url: "'.$datos['controller'].'/getSelect",  data: {"modelo" : "'.$datos['combobox'].'" '.$valdefecto.'},type: "post", success: function(response) { $("#'.$datos['nameid'].'").html(response).fadeIn(); } }); }); </script>');
        
            return $componente;
        }
        elseif (isset($datos['simple_html']))
        {
            $componente = '';
            $script = '';
            $valdefecto = '';
            if (isset($datos['valor'])) {
                $valdefecto = ', "get" : "'.$datos['valor'].'"';
            }
            if (strlen($datos['titulo']) > 0) {
                $componente .= "<label for = '".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                                <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <select class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'></select>
                                </div>";
            }else{
                $componente .= "<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <select class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'></select>
                                </div>";
            }
            $componente .= ' <script type="text/javascript"> $(document).ready(function() { $.ajax({ url: "'.$datos['controller'].'/getSelect",  data: {"modelo" : "'.$datos['combobox'].'" '.$valdefecto.'},type: "post", success: function(response) { $("#'.$datos['nameid'].'").html(response).fadeIn(); } }); }); </script>';
        
            return $componente;
        }
        else
        {
            $componente = "<div class='col-md-6 col-xs-12'><div class='form-group row'>";
            $script = '';
            if (strlen($datos['titulo']) > 0) {
                $componente .= "<label for = '".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                                <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <select class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'></select>
                                </div>";
            }else{
                $componente .= "<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                                    <select class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."'></select>
                                </div>";
            }
            dti_core::set('script', ' <script type="text/javascript"> $(document).ready(function() { $.ajax({ url: "'.$datos['controller'].'/getSelect",  data: {"modelo" : "'.$datos['combobox'].'"},type: "post", success: function(response) { $("#'.$datos['nameid'].'").html(response).fadeIn(); } }); }); </script>');
        
            return $componente.'</div></div>';
        }
    }
    
    public function createFile($datos)
    {
        $componente = "<div class='col-md-6 col-xs-12'><div class='form-group row'>";
        $componente .= "<label ".$datos['nameid']." class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                           <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                               <input type='".$datos['tipo']."' id='".$datos['nameid']."' class='filestyle' data-text='Buscar Archivo' data-buttonBefore='true' data-placeholder='".$datos['titulo']."' data-buttonText='Seleccionar ".$datos['titulo']."' data-buttonName='btn-primary' >
                           </div>";
        
        return $componente.'</div></div>';
    }
    
    public function createTextSearch($datos)
    {
        $modal='';
        $readonly = '';
        if (isset($datos['readonly'])) {
            $readonly = "readonly='true'";
        }
        if (isset($datos['simple'])) {
            $componente = "<div class='col-md-12 col-xs-12'><div class='form-group row'>";
        } else {
            $componente = "<div class='col-md-6 col-xs-12'><div class='form-group row'>";
        }
        if (strlen($datos['icono']) > 1) 
        {
            $componente .= "<label for = '".$datos['nameid']."' class='col-lg-4 col-md-4 col-sm-12 col-xs-12 control-label'>".$datos['titulo']."</label>
                        <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                            <div class='input-group'>
                                <input aria-describedby='btn".$datos['nameid']."' type='text' class='form-control' id='".$datos['nameid']."' name='".$datos['nameid']."' placeholder='".$datos['placeholder']."' ".$readonly." />";
                if (strlen($datos['modal']) > 0)
                {
                    $modal_build = new dti_builder_modal();
                    $modal_build->setModal(array(
                        'id'=>'model'.$datos['nameid'],
                        'tipo'=>'search',
                        'titulo'=>$datos['titulo'],
                        'url'=>$datos['controller'].'/'.$datos['nomparam'],
                        'json'=>array(
                                    'data'=>"{'search':search,'page':page,'accion':'".$datos['accion']."'}",
                                ),
                        'mensaje'=>"<div id='loadermodel".$datos['nameid']."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                    <div class='outer_divmodel".$datos['nameid']."'></div>",
                        'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
                    ));
                    $modal = $modal_build->getModal();
                    $componente .= "<span class='input-group-addon mat-img' id='btn".$datos['nameid']."' data-toggle='modal' data-target='#model".$datos['nameid']."' type='button'><span class='".$datos['icono']."'></span></span>";
                }
                else{
                    $componente .= "<a class='input-group-addon mat-img' id='basic-".$datos['nameid']."'><i class='".$datos['icono']."'></i></a>";
                }
            $componente .= "</div>
                        </div>";
        }
        
        return $componente.'</div></div>'.$modal;
    }
    
}
