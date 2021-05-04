<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 05/05/2017
 * Version: 1.0.1
 *    */

class dti_panel {

    private static $panel,$ctlVariables,$cabecera,$detalle,$activa;

    /**
     * Funcion para crear paneles
     * 
     * @param array $dtcolumna page,icono,titulo
     * @param string $dtdetalle html detalle
     */
    function setPanel($dtcolumna,$dtdetalle){
        if (self::$activa == 0) {
            self::$cabecera .= '<li class="nav-item"><a class="nav-link waves-light active" href="#'.$dtcolumna['page'].'" role="tab" data-toggle="tab"><i class="fa '.$dtcolumna['icono'].'"></i>  <span>'.$dtcolumna['titulo'].'</span></a></li>';
            self::$detalle .= '<div role="tabpanel" class="tab-pane fade in show active" id="'.$dtcolumna['page'].'">'.$dtdetalle.'</div>';
            self::$activa = 1;
        }else{
            self::$cabecera .= '<li class="nav-item"><a class="nav-link waves-light" href="#'.$dtcolumna['page'].'" role="tab" data-toggle="tab"><i class="fa '.$dtcolumna['icono'].'"></i>  <span>'.$dtcolumna['titulo'].'</span></a></li>';
            self::$detalle .= '<div role="tabpanel" class="tab-pane fade in show" id="'.$dtcolumna['page'].'">'.$dtdetalle.'</div>';
        }
    }

    public function __construct() {
        //Limpiamos las variables para volver a llamar
        self::$cabecera = '';
        self::$detalle = '';
        self::$panel = '';
        self::$activa = 0;
        //Cargas Css/Js/Script Obligatorios
        if (!isset(self::$ctlVariables)) {
            dti_core::set("css", "<link href='public/css/componentes/panel/panel.css' rel='stylesheet' type='text/css'/>");
            self::$ctlVariables = 0;
            self::$activa = 0;
        }
    }

    public function getPanel(){
        self::$panel .= '<div class="container">
                            <div class="row">
                              <div class="col-md-12">
                                <!-- Nav tabs -->
                                <div class="tabs-wrapper"> 
                                     <ul class="nav classic-tabs tabs-orange" role="tablist">';
        
        //Cabecera
        self::$panel .= self::$cabecera;
        
        self::$panel .= '</ul>
                        </div>
                        <!-- Tab panels -->
                        <div class="tab-content card">';
        
        //Detalle
        self::$panel .= self::$detalle;
        
        self::$panel .= '       </div>
                              </div>
                            </div>
                          </div>';
        
        return self::$panel;
    }
}
