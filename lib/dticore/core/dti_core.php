<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 05/05/2017
 * Version: 1.0.1
 *    */

class dti_core {
    
    protected static $data;
    
    public static function set($name, $value){
        if (isset(self::$data[$name])) {
            self::$data[$name] .= $value;
        }else{
            self::$data[$name] = $value;
        }
    }
    
    public static function get(){
        return self::$data;
    }
}