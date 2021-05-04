<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 13/04/2017
 * Version: 3.0.0
 *    */

class dti_acordion {

    private static $acordion,$circulos,$ctlVariables;

    /**
     * Crear Acordiones
     * @param array $datos (titulo,descripcion,atras,siguiente,finalizar)
     */
    function setAcordion($datos){
        self::$circulos[] = $datos;
    }

    public function __construct() {
        self::$acordion = "<div id='accordion'>";
        //Cargas Css/Js/Script Obligatorios
        if (!isset(self::$ctlVariables)) {
            //dti_core::set('css', '<link href="public/css/componentes/circle/circlemenu.css" rel="stylesheet" type="text/css"/>');
            self::$ctlVariables = 0;
        }
    }

    private function buildAcordion(){
        $nivel = 1;
        
         self::$acordion .= '<!-- Grid row -->
                                <div class="row gradient-background d-flex justify-content-center">

                                <!-- Grid column -->
                                <div class="col-md-12 col-xl-12 py-5">

                                    <!--Accordion wrapper-->
                                    <div class="accordion accordion-2" id="accordionEx7" role="tablist" aria-multiselectable="true">';
        
        foreach (self::$circulos as $dato) {
            //Verificar si se declara botones
            if (isset($dato["atras"])) { $botonAtras = "<a data-toggle='collapse' data-parent='#accordionEx7' href='#collapse".($nivel-1)."' class='btn btn-info'>Atras</a>"; } else {$botonAtras = "";}
            if (isset($dato["siguiente"])) { $botonSiguiente = "<a data-toggle='collapse' data-parent='#accordionEx7' href='#collapse".($nivel+1)."' class='btn btn-info'>Siguiente</a>"; } else {$botonSiguiente="";}
            if (isset($dato["finalizar"])) { $botonFinalizar = "<button class='btn btn-success center-block' onclick='".$dato["finalizar"]."'>Finalizar</button>"; } else { $botonFinalizar = "";}
            if ($nivel == 1) { $abrir = "true"; $ocultar = ''; $show='show'; } else { $abrir = "false"; $ocultar = 'class="collapsed"'; $show=''; }
            //Armar Acordion
            self::$acordion .= '<!-- Accordion card -->
                                <div class="card">

                                    <!-- Card header -->
                                    <div class="card-header rgba-stylish-strong z-depth-1 mb-1" role="tab" id="heading'.$nivel.'">
                                        <a '.$ocultar.' data-toggle="collapse" data-parent="#accordionEx7" href="#collapse'.$nivel.'" aria-expanded="'.$abrir.'" aria-controls="collapse'.$nivel.'">
                                            <h5 class="mb-0 white-text text-uppercase font-thin">
                                                '.$dato["titulo"].' <i class="fa fa-angle-down rotate-icon"></i>
                                            </h5>
                                        </a>
                                    </div>

                                    <!-- Card body -->
                                    <div id="collapse'.$nivel.'" class="collapse '.$show.'" role="tabpanel" aria-labelledby="heading'.$nivel.'" data-parent="#accordionEx7">
                                        <div class="card-body mb-1 rgba-grey-light white-text">
                                            '.$dato["descripcion"].'
                                            <br>
                                            '.$botonAtras.' '.$botonSiguiente.' '.$botonFinalizar.'
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <!-- Accordion card -->';
            $nivel ++;
        }
        self::$acordion .= '</div>
                        <!--/.Accordion wrapper-->

                    </div>
                    <!-- Grid column -->
                    
                </div>
                <!-- Grid row -->';
    }

    public function getAcordion(){
        $this->buildAcordion();
        self::$acordion .= "</div>";
        return self::$acordion;
    }
}
