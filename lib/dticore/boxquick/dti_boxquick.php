<?php

/*
 * Titulo: Creador de BoxQuick.
 * Author: Gabriel Reyes
 * Fecha: 04/05/2017
 * Version: 3.0.1
 *    */

class dti_boxquick {
    
    private static $color,$icono,$titulo,$subtitulo,$url,$descripcion,$ctlVariables;
    
    private function getColor() {
        return self::$color;
    }

    private function getIcono() {
        return self::$icono;
    }

    private function getTitulo() {
        return self::$titulo;
    }

    private function getSubtitulo() {
        return self::$subtitulo;
    }

    private function getUrl() {
        return self::$url;
    }

    private function getDescripcion() {
        return self::$descripcion;
    }
    
    /**
     * DTI_BOXQUICK
     * //Colores v1 => orange / blue / green / red
     * //Colores v2 => dark-blue / green / orange / blue / red / purple
     * @param string $color 
     * 
     */
    function setColor($color) {
        self::$color = $color;
    }

    function setIcono($icono) {
        self::$icono = $icono;
    }

    function setTitulo($titulo) {
        self::$titulo = $titulo;
    }

    function setSubtitulo($subtitulo) {
        self::$subtitulo = $subtitulo;
    }

    function setUrl($url) {
        self::$url = $url;
    }

    function setDescripcion($descripcion) {
        self::$descripcion = $descripcion;
    }
    
    public function __construct() {
        if (!isset(self::$ctlVariables)) {
            dti_core::set('css', "<link href='public/css/componentes/boxquick/boxquickstyle.css' rel='stylesheet' type='text/css'/>");
            self::$ctlVariables = 0;
        }
    }
    
    public function getboxQuick($version='1'){
        switch ($version) {
            case '1':
                $boxQuick = "<!--<div class='row'>-->
                       <div class='col-lg-3 col-md-6 col-sm-6'>
                        <div class='card card-stats'>
                          <div class='card-header' data-background-color='".$this->getColor()."'>
                            <i class='fa ".$this->getIcono()."'></i>
                          </div>
                          <div class='card-content'>
                            <p class='category'>".$this->getTitulo()."</p>
                            <h3 class='title'>".$this->getSubtitulo()."<!--<small>GB</small>--></h3>
                          </div>
                          <div class='card-footer'>
                            <div class='stats'>
                              <!--<i class='material-icons'>date_range</i>--><a href='".$this->getUrl()."'>".$this->getDescripcion()."</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    <!--</div>-->";
                break;
            case '2':
                $boxQuick = '<div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="'.$this->getUrl().'">
                                <div class="circle-tile-heading '.$this->getColor().'">
                                    <i class="fa '.$this->getIcono().' fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content '.$this->getColor().'">
                                <div class="circle-tile-description text-faded">
                                    '.$this->getTitulo().'
                                </div>
                                <div class="circle-tile-number text-faded">
                                    '.$this->getSubtitulo().'
                                    <span id="sparklineA"></span>
                                </div>
                                <a href="'.$this->getUrl().'" class="circle-tile-footer">'.$this->getDescripcion().' <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>';
                break;
            case '3':
                if (strlen($this->getUrl())>0)
                {
                    $boxQuick = '<div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="circle-tile">
                            <div class="circle-tile-heading '.$this->getColor().'">
                                <i class="fa '.$this->getIcono().' fa-fw fa-3x"></i>
                            </div>
                            <div class="circle-tile-content-v3 '.$this->getColor().'">
                                <div class="circle-tile-description-v3 text-faded">
                                    '.$this->getTitulo().'
                                </div>
                                <div class="circle-tile-footer-cant text-faded">
                                    <a href="'.$this->getUrl().'">'.$this->getSubtitulo().'</a>
                                    <span id="sparklineA"></span>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                else
                {
                    $boxQuick = '<div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="circle-tile">
                            <div class="circle-tile-heading '.$this->getColor().'">
                                <i class="fa '.$this->getIcono().' fa-fw fa-3x"></i>
                            </div>
                            <div class="circle-tile-content-v3 '.$this->getColor().'">
                                <div class="circle-tile-description-v3 text-faded">
                                    '.$this->getTitulo().'
                                </div>
                                <div class="circle-tile-footer-cant text-faded">
                                    '.$this->getSubtitulo().'
                                    <span id="sparklineA"></span>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                break;
        }
        return $boxQuick;
    }
}
