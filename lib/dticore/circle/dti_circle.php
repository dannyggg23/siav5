<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 13/04/2017
 * Version: 3.0.0
 *    */

class dti_circle {

    private static $circle,$circulos,$ctlVariables;

    function setCircle($url,$icono,$descripcion){
        self::$circulos[] = array(
            "url"=>$url,
            "icono"=>$icono,
            "descripcion"=>$descripcion,
        );
    }

    public function __construct() {
        self::$circle = "<div class='row'>";
        //Cargas Css/Js/Script Obligatorios
        if (!isset(self::$ctlVariables)) {
            dti_core::set('css', '<link href="public/css/componentes/circle/circlemenu.css" rel="stylesheet" type="text/css"/>');
            self::$ctlVariables = 0;
        }
    }

    private function buildcircle(){
        foreach (self::$circulos as $cir) {
            self::$circle .= "<div class='col-lg-3 col-md-3 col-sm-3'>
                                <div class='circle circle3'>
                                    <a href='".$cir["url"]."'><h2><i class='fa ".$cir["icono"]."'></i><p>".$cir["descripcion"]."</p></h2></a>
                                </div>
                              </div>";
        }
    }

    public function getCircle(){
        $this->buildcircle();
        self::$circle .= "</div>";
        return self::$circle;
    }
}
