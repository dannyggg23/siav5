<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 05/05/2017
 * Version: 1.0.1
 *    */

class dti_builder_buttons {

    private static $groupbtn,$ctlVariables,$detalle,$confirmacion,$script,$modal;

    /**
     * Función: Crea grupo de botones
     * 
     * @param array $dt Ej: 'clic'=>'saveCliente',
                            'icono'=>'fa fa-usd',
                            'titulo'=>'Guardar',
                            'btntitulo'=>'Confirmar',
                            'btnmensaje'=>'Desea Guardar el Cliente?',
                            'btn'=>array(['titulo'=>'SI','comandos'=>'setJsonCliente();'],
                                        ['titulo'=>'NO','comandos'=>'']),
     */
    function setGroupButtons($dt){
        if (isset($dt['swal']))
        {
            self::$detalle .= '<li><button class=\'btn btns_build\' onclick=\''.$dt['clic'].'\' role=\'tab\' data-toggle=\'tab\'><i class="'.$dt['icono'].'"></i>'.$dt['titulo'].'</button></li>';
        }
        else if (isset($dt['enlace']))
        {
            self::$detalle .= '<li><button class="btn btns_build" onclick="location.href=\''.$dt['clic'].'\'" role="tab" data-toggle="tab"><i class="'.$dt['icono'].'"></i>'.$dt['titulo'].'</button></li>';
        }
        else if (isset($dt['funcionClick']))
        {
            if (isset($dt['id']))
            {
                self::$detalle .= '<li><button id="'.$dt['id'].'" class="btn btns_build" role="tab" data-toggle="tab"><i class="'.$dt['icono'].'"></i>'.$dt['titulo'].'</button></li>';
            }
            else
            {
                self::$detalle .= '<li><button class="btn btns_build" onclick="'.$dt['clic'].'" role="tab" data-toggle="tab"><i class="'.$dt['icono'].'"></i>'.$dt['titulo'].'</button></li>';
            }
        }
        else if(isset($dt['modal']))
        {
            if (isset($dt['outer']))
            {
                self::$detalle .= '<li><button class="btn btns_build '.$dt["id"].'" data-toggle="modal" data-target="#'.$dt["id"].'"><i class="'.$dt['icono'].'"></i>'.$dt['titulo'].'</button></li>';
                if (isset($dt['form']))
                {
                    $modal_build = new dti_builder_modal();
                    $modal_build->setModal(array(
                        'id'=>$dt["id"],
                        'tipo'=>'edit',
                        'titulo'=>$dt["titulo"],
                        'mensaje'=>"<div id='loader".$dt["id"]."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                    <div class='outer_div".$dt["id"]."'></div>",
                        'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
                    ));
                    $modal = $modal_build->getModal();
                    
                    //Validar si tiene parametro auxiliar
                    if (isset($dt['auxiliar']))
                    {
                        if (isset($dt['auxiliar2']))
                        {
                            $data = "data: {'panel':true,'url':'".$dt["url"]."','auxiliar':'".$dt["auxiliar"]."','auxiliar2':'".$dt["auxiliar2"]."'},";
                        }
                        else
                        {
                            $data = "data: {'panel':true,'url':'".$dt["url"]."','auxiliar':'".$dt["auxiliar"]."'},";
                        }
                    }
                    else
                    {
                        $data = "data: {'panel':true,'url':'".$dt["url"]."'},";
                    }
                    
                    if (isset($dt['dataType']))
                    {
                        $script = "<script>
                                $(function() {
                                    $(document).on('click','.".$dt["id"]."',function(e){
                                        $('#loader".$dt["id"]."').fadeIn('slow');
                                         $.ajax({
                                                url:'".$dt["controller"]."/".$dt["accion"]."',
                                                ".$data."
                                                type: 'post',
                                                dataType: 'json',
                                                beforeSend: function(objeto){
                                                    $('#loader".$dt["id"]."').html(\"<img src='public/images/ajax-loader.gif'> Cargando...\");
                                                },
                                                success:function(data){
                                                    $('.outer_div".$dt["id"]."').html(data.layout).fadeIn('slow');
                                                    $('#loader".$dt["id"]."').html('');
                                                    $('._MODAL_').html(data.modal).fadeIn('slow');
                                                    $('._SCRIPT_').html(data.script).fadeIn('slow');
                                                }
                                            });
                                     });
                                });
                                </script>";
                    }
                    else
                    {
                        $script = "<script>
                                $(function() {
                                    $(document).on('click','.".$dt["id"]."',function(e){
                                        $('#loader".$dt["id"]."').fadeIn('slow');
                                         $.ajax({
                                                url:'".$dt["controller"]."/".$dt["accion"]."',
                                                ".$data."
                                                type: 'post',
                                                beforeSend: function(objeto){
                                                    $('#loader".$dt["id"]."').html(\"<img src='public/images/ajax-loader.gif'> Cargando...\");
                                                },
                                                success:function(data){
                                                    $('.outer_div".$dt["id"]."').html(data).fadeIn('slow');
                                                    $('#loader".$dt["id"]."').html('');
                                                }
                                            });
                                     });
                                });
                                </script>";
                    }
                    self::$script .= $modal.$script;
                }
                else
                {
                    $param = "";
                    if (isset($dt["param"])) {
                        $param = "'param':'".$dt["param"]."',";
                    }
                    $btn_search = "";
                    if (isset($dt["search"])) {
                        $btn_search = $dt["search"];
                    }
                    if (isset($dt['auxiliar2']))
                    {
                        $data = "{'search':search,".$param."'id':id,'page':page,'auxiliar2':'".$dt['auxiliar2']."','accion':'".$dt['accion']."'}";
                    }
                    else
                    {
                        $data = "{'search':search,".$param."'id':id,'page':page,'accion':'".$dt['accion']."'}";
                    }
                    $modal = new dti_builder_modal();
                    $modal->setModal(array(
                            'id'=>$dt["id"],
                            'tipo'=>'search',
                            'titulo'=>$dt["titulo"],
                            'btn_search'=>$btn_search,
                            'url'=>$dt['controller'].'/'.$dt['funcion'],
                            'json'=>array(
                                        'antes'=>"var id = document.getElementById('".$dt["auxiliar"]."').value;",
                                        'data'=>$data,
                                    ),
                            'mensaje'=>"<div id='loader".$dt["id"]."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                        <div class='outer_div".$dt["id"]."'></div>",
                            'btn'=>$dt['btn'],
                        ));
                    self::$script .= $modal->getModal();
                }
            }
            else
            {
                self::$detalle .= '<li><button class="btn btns_build pull-right '.$dt["id"].'" data-toggle="modal" data-target="#'.$dt["id"].'" role="tab" data-toggle="tab"><i class="'.$dt['icono'].'"></i>'.$dt['titulo'].'</button></li>';
                $modal_build = new dti_builder_modal();
                $modal_build->setModal(array(
                    'id'=>$dt["id"],
                    'tipo'=>$dt["tipomodal"],
                    'titulo'=>$dt["titulo"],
                    'url'=>$dt["btnmensaje"],
                    'json'=>$dt["json"],
                    'mensaje'=>"<div id='loader".$dt["id"]."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                <div class='outer_div".$dt["id"]."'></div>",
                    'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
                ));
                self::$modal .= $modal_build->getModal();
            }
        }
        else
        {
            //Abre modal
            self::$detalle .= '<li><button class="btn btns_build" data-toggle="modal" data-target="#'.$dt["id"].'" role="tab" data-toggle="tab"><i class="'.$dt['icono'].'"></i>'.$dt['titulo'].'</button></li>';
            $modal = new dti_builder_modal();
            $modal->setModal(array(
                'id'=>$dt["id"],
                'tipo'=>'msg',
                'titulo'=>$dt["btntitulo"],
                'mensaje'=>$dt["btnmensaje"],
                'btn'=>$dt['btn'],
            ));
            self::$script .= $modal->getModal();
        }
    }

    public function __construct() {
        //Limpiamos las variables para volver a llamar
        self::$detalle = '';
        self::$script = '';
        self::$groupbtn = '';
        self::$confirmacion = '';
        self::$modal = '';
        //Cargas Css/Js/Script Obligatorios
        if (!isset(self::$ctlVariables)) {
            //$variables = new \dti_core("panel");
            self::$ctlVariables = 0;
        }
    }

    public function getGroupButtons(){
        self::$groupbtn .= '<div class="dti_section"><ul class="nav nav-pills nav-pills-warning" role="tablist">';
        self::$groupbtn .= self::$detalle;
        self::$groupbtn .= '</ul></div>';
        
        return array(
            'layout'=>self::$groupbtn,
            'script'=>self::$script,
            'modal'=>self::$modal,
        );
    }
}
