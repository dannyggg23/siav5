<?php

/*
 * Titulo: Creador de BoxQuick.
 * Author: Gabriel Reyes
 * Fecha: 04/05/2017
 * Version: 3.0.1
 *    */

class dti_box {
    
    private static $id,$color,$icono,$titulo,$subtitulo,$url,$descripcion,$ctlVariables;
    
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
    
    function getId() {
        return self::$id;
    }

    function setId($id) {
        self::$id = $id;
    }
   
    public function __construct() {
        if (!isset(self::$ctlVariables)) {
            dti_core::set('css', "<link href='public/css/componentes/box/boxstyle.css' rel='stylesheet' type='text/css'/>");
            self::$ctlVariables = 0;
        }
    }
    
    public function getbox($version=1){
        switch ($version) {
            case 1:
                $box = '<div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <a id="'.$this->getId().'" href="'.$this->getUrl().'" class="btn btn-'.$this->getColor().' btn-lg" role="button">
                        <span class="fa '.$this->getIcono().' fa-lg"></span>
                    </a><br/>'.$this->getTitulo().'
                </div>';
                break;
            case 2:
                $box = '<div class="col margin col-xs-4 col-md-1 col-lg-1">
                            <a href="'.$this->getUrl().'" class="btn btn-'.$this->getColor().' btn-sm" role="button" title="'.$this->getTitulo().'">
                                <span class="fa '.$this->getIcono().' fa-lg"></span>
                            </a>
                        </div>';
                break;
            case 3:
                $box = '<div class="col margin">
                            <a href="'.$this->getUrl().'" class="btn btn-'.$this->getColor().' btn-sm" role="button" title="'.$this->getTitulo().'">
                                <span class="fa '.$this->getIcono().' fa-lg"></span>
                            </a>
                        </div>';
                break;
        }
        return $box;
    }
}
