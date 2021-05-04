<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 18/04/2017
 * Version: 3.0.0
 *    */

class dti_builder_modal {

    private static $ctlVariables,$script,$modal;

    /**
     * Función: Crea grupo de botones
     * 
     * @param array $dt Ej: 'id'=>'idModal',
     *                      'tipo'=>'msg/search/crud/loading'
     *                      'titulo'=>'Guardar',
                            'mensaje'=>'Desea Guardar el Cliente?',
                            'btn'=>array(['titulo'=>'SI','comandos'=>'setJsonCliente();'],
                                        ['titulo'=>'NO','comandos'=>'']),
     */
    function setModal($dt){
        switch ($dt['tipo']) {
            case 'msg':
                
                $header = "<h2 class='modal-title' id='exampleModalLabel'>".$dt['titulo']."</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>";
                
                $body = "<form class='form-horizontal'>
                            <div class='form-group'>
                                <div class='col-sm-6'>
                                  <h3>".$dt['mensaje']."</h3>
                                </div>
                            </div>
                        </form>";
                
                $footer = "";
                foreach ($dt['btn'] as $key => $value)
                {
                    switch ($value['accion']) {
                        case 'close':
                            $footer .= "<button type='button' class='btn btn-secondary btn-lg' data-dismiss='modal'>".$value['titulo']."</button>";
                            break;
                        default:
                            $footer .= "<button type='button' class='btn btn-primary btn-lg' onclick='".$value["accion"]."'>".$value['titulo']."</button>";
                            break;
                    }
                }
                self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='".$dt["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        ".$body."
                                  </div>
                                  <div class='modal-footer'>
                                        ".$footer."
                                  </div>
                                </div>
                          </div>
                        </div>";
                break;
            case 'loading':
                
                $header = "<h2 class='modal-title' id='exampleModalLabel'>Cargando...</h2>";
                
                $body = "<form class='form-horizontal'>
                            <div class='form-group'>
                                <div class='col-sm-6'>
                                    <img src='public/skins/negro/loading.GIF' />
                                </div>
                            </div>
                        </form>";
                
                self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal modal_loading fade' id='loading' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        ".$body."
                                  </div>
                                </div>
                          </div>
                        </div>";
                break;
            case 'edit':
                
                $header = "<h2 class='modal-title' id='exampleModalLabel'>".$dt['titulo']."</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>";
                
                $footer = "";
                foreach ($dt['btn'] as $key => $value)
                {
                    switch ($value['accion']) {
                        case 'close':
                            $footer .= "<button type='button' class='btn btn-secondary btn-lg' data-dismiss='modal'>".$value['titulo']."</button>";
                            break;
                        default:
                            $footer .= "<button type='button' class='btn btn-primary btn-lg' onclick='".$value["accion"]."'>".$value['titulo']."</button>";
                            break;
                    }
                }
                self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='".$dt["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        ".$dt['mensaje']."
                                  </div>
                                  <div class='modal-footer'>
                                        ".$footer."
                                  </div>
                                </div>
                          </div>
                        </div>";
                break;
            case 'search':
                
                $header = "<h2 class='modal-title' id='exampleModalLabel'>".$dt['titulo']."</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>";
                
                $footer = "";
                foreach ($dt['btn'] as $key => $value)
                {
                    switch ($value['accion']) {
                        case 'close':
                            $footer .= "<button type='button' class='btn btn-secondary btn-lg' data-dismiss='modal'>".$value['titulo']."</button>";
                            break;
                        default:
                            $footer .= "<button type='button' class='btn btn-primary btn-lg' onclick='".$value["accion"]."'>".$value['titulo']."</button>";
                            break;
                    }
                }
                if (isset($dt['btn_search'])) {
                    if ($dt['btn_search']=='1') {
                        self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='".$dt["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        <form class='form-horizontal'>
                                            <div class='form-group row'>
                                                <div class='col-sm-6'>
                                                  <input type='text' class='form-control' id='txtsearch".$dt["id"]."' placeholder='Digite para Buscar'>
                                                </div>
                                                <div class='col-sm-6'>
                                                    <button type='button' class='btn btn-default' onclick='goSearch".$dt["id"]."(1)'><span class='glyphicon glyphicon-search'></span> Buscar</button>
                                                </div>
                                            </div>
                                        </form>
                                        ".$dt['mensaje']."
                                  </div>
                                  <div class='modal-footer'>
                                        ".$footer."
                                  </div>
                                </div>
                          </div>
                        </div>";
                    }
                    else {
                        self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='".$dt["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        <form class='form-horizontal'>
                                            <div class='form-group row'>
                                                <div class='col-sm-6'>
                                                  <input type='text' class='form-control' id='txtsearch".$dt["id"]."' placeholder='Digite para Buscar' onkeyup='goSearch".$dt["id"]."(1)'>
                                                </div>
                                                <!--<div class='col-sm-6'>
                                                    <button type='button' class='btn btn-default' onclick='goSearch".$dt["id"]."(1)'><span class='glyphicon glyphicon-search'></span> Buscar</button>
                                                </div>-->
                                            </div>
                                        </form>
                                        ".$dt['mensaje']."
                                  </div>
                                  <div class='modal-footer'>
                                        ".$footer."
                                  </div>
                                </div>
                          </div>
                        </div>";
                    }
                }
                else {
                    self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='".$dt["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        <form class='form-horizontal'>
                                            <div class='form-group row'>
                                                <div class='col-sm-6'>
                                                  <input type='text' class='form-control' id='txtsearch".$dt["id"]."' placeholder='Digite para Buscar' onkeyup='goSearch".$dt["id"]."(1)'>
                                                </div>
                                                <!--<div class='col-sm-6'>
                                                    <button type='button' class='btn btn-default' onclick='goSearch".$dt["id"]."(1)'><span class='glyphicon glyphicon-search'></span> Buscar</button>
                                                </div>-->
                                            </div>
                                        </form>
                                        ".$dt['mensaje']."
                                  </div>
                                  <div class='modal-footer'>
                                        ".$footer."
                                  </div>
                                </div>
                          </div>
                        </div>";
                }
                
                $dti_ajax = new dti_builder_ajax();
                $dti_ajax->setAjax(array(
                    'echo'=>$dt["id"],
                    'url'=>$dt["url"],
                    'data'=>$dt["json"]["data"],
                ));
                $id_limpio = substr($dt['id'], 5, strlen($dt['id']));
                //var search= $('#txtsearch".$dt["id"]."').val();
                if (isset($dt['json']['antes']))
                {
                    $script = "<script type='text/javascript'>
                                function goSearch".$dt["id"]."(page){
                                    var search= document.getElementById('txtsearch".$dt["id"]."').value;
                                    ".$dt['json']['antes']."
                                    ".$dti_ajax->getAjax()."
                                }
                                
                                $('.form-control-clear').click(function() {
                                    document.getElementById('".$id_limpio."').value = '0';
                                    $('#lbl".$id_limpio."').html('');
                                });</script>";
                }
                else
                {
                    $script = "<script type='text/javascript'>
                                function goSearch".$dt["id"]."(page){
                                    var search= document.getElementById('txtsearch".$dt["id"]."').value;
                                    ".$dti_ajax->getAjax()."
                                }
                                
                                $('.form-control-clear').click(function() {
                                  document.getElementById('".$id_limpio."').value = '0';
                                  $('#lbl".$id_limpio."').html('');
                                });</script>";
                }
                
                self::$modal .= $script;
                break;
            case 'form':
                
                /*MODAL DEL FORM*/
                $modal_build = new dti_builder_modal();
                $modal_build->setModal(array(
                    'id'=>$dt['modal'],
                    'tipo'=>'edit',
                    'titulo'=>'Nuevo Registro',
                    'mensaje'=>"<div id='loader".$dt['modal']."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                                <div class='outer_div".$dt['modal']."'></div>",
                    'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
                ));
                $modal = $modal_build->getModal();
                
                $header = "<h2 class='modal-title' id='exampleModalLabel'>".$dt['titulo']."</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <button class='btn btn-primary btn_nuevo btn-sm pull-right ".$dt['modal']."' data-toggle='modal' data-target='#".$dt['modal']."'><span class='fa fa-edit'></span>Nuevo</button>
                                <span aria-hidden='true'>&times;</span>
                            </button>";
                
                $footer = "";
                foreach ($dt['btn'] as $key => $value)
                {
                    switch ($value['accion']) {
                        case 'close':
                            $footer .= "<button type='button' class='btn btn-secondary btn-lg' data-dismiss='modal'>".$value['titulo']."</button>";
                            break;
                        default:
                            $footer .= "<button type='button' class='btn btn-primary btn-lg' onclick='".$value["accion"]."'>".$value['titulo']."</button>";
                            break;
                    }
                }
                self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='".$dt["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        ".$dt['mensaje']."
                                  </div>
                                  <div class='modal-footer'>
                                        ".$footer."
                                  </div>
                                </div>
                          </div>
                        </div>".$modal;
                break;
            case 'hist':
                $header = "<h2 class='modal-title' id='exampleModalLabel'>".$dt['titulo']."</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>";
                
                $footer = "";
                foreach ($dt['btn'] as $key => $value)
                {
                    switch ($value['accion']) {
                        case 'close':
                            $footer .= "<button type='button' class='btn btn-secondary btn-lg' data-dismiss='modal'>".$value['titulo']."</button>";
                            break;
                        default:
                            $footer .= "<button type='button' class='btn btn-primary btn-lg' onclick='".$value["accion"]."'>".$value['titulo']."</button>";
                            break;
                    }
                }
                self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='".$dt["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-hist' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        ".$dt['mensaje']."
                                  </div>
                                  <div class='modal-footer'>
                                        ".$footer."
                                  </div>
                                </div>
                          </div>
                        </div>";
                break;
            case 'view':
                
                $header = "<h2 class='modal-title' id='exampleModalLabel'>".$dt['titulo']."</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>";
                
                $footer = "";
                foreach ($dt['btn'] as $key => $value)
                {
                    switch ($value['accion']) {
                        case 'close':
                            $footer .= "<button type='button' class='btn btn-secondary btn-lg' data-dismiss='modal'>".$value['titulo']."</button>";
                            break;
                        default:
                            $footer .= "<button type='button' class='btn btn-primary btn-lg' onclick='".$value["accion"]."'>".$value['titulo']."</button>";
                            break;
                    }
                }
                self::$modal = "<!-- Modal -->
                        <div data-backdrop='static' data-keyboard='false' class='modal fade bs-example-modal-lg' id='".$dt["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                          <div class='modal-dialog modal-lg modal-normal' role='document'>
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                        ".$header."
                                  </div>
                                  <div class='modal-body'>
                                        ".$dt['mensaje']."
                                  </div>
                                  <div class='modal-footer'>
                                        ".$footer."
                                  </div>
                                </div>
                          </div>
                        </div>";
                
                $dti_ajax = new dti_builder_ajax();
                $dti_ajax->setAjax(array(
                    'echo'=>$dt["id"],
                    'url'=>$dt["url"],
                    'data'=>$dt["json"]["data"],
                ));
                //var search= $('#txtsearch".$dt["id"]."').val();
                if (isset($dt['json']['antes']))
                {
                    $script = "<script type='text/javascript'>
                                $('#".$dt["id"]."').on('shown.bs.modal', function (e) {
                                    ".$dt['json']['antes']."
                                    ".$dti_ajax->getAjax()."
                                });</script>";
                }
                else
                {
                    $script = "<script type='text/javascript'>
                                $('#".$dt["id"]."').on('shown.bs.modal', function (e) {
                                    ".$dti_ajax->getAjax()."
                                });</script>";
                }
                
                self::$modal .= $script;
                break;
        }
    }

    public function __construct()
    {
        //Limpiamos las variables para volver a llamar
        self::$script = '';
        self::$modal = '';
        //Cargas Css/Js/Script Obligatorios
        if (!isset(self::$ctlVariables)) {
            //$variables = new \dti_core("panel");
            self::$ctlVariables = 0;
        }
    }

    public function getModal()
    {
        return self::$modal;
    }
}
