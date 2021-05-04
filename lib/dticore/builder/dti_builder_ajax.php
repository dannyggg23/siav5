<?php

/*
 * Titulo: Creador Codigo Ajax.
 * Author: Gabriel Reyes
 * Fecha: 18/04/2017
 * Version: 3.0.0
 *    */

class dti_builder_ajax {

    private static $ctlVariables,$script,$ajax;

    /**
     * Función: Crea Codigo Ajax
     * 
     * @param array $dt Ej: 'data'=>'id=1&crud=2',
     *                      'ok'=>'si es correcto que hacemos',
     *                      'error'=>'si es incorrecto que hacemos',
     */
    function setAjax($dt=array()){
        $data = "";
        $ok = "";
        $error = "";
        $imprimir = "";
        $url = "url:'".CONTROLADOR_DEFECTO."/".ACCION_DEFECTO."',";
        
        if (isset($dt['url'])) { $url = "url:'".$dt['url']."',"; }
        if (isset($dt['data'])) { $data = "data: ".$dt['data'].","; }
        if (isset($dt['ok'])) { $ok = $dt['ok'].";"; }
        if (isset($dt['error'])) { $error = $dt['error'].";"; }
        if (isset($dt['imprimir'])) { if (strlen($dt['imprimir'])>0) {$imprimir = "if (data.Imprimir.length > 0) {".$dt['imprimir']."} else {".$ok."}"; }}
        if (isset($dt['echo']))
        { 
            $datatype = "";
            $sucess="$('.outer_div".$dt['echo']."').html(data).fadeIn('slow');$('#loader".$dt['echo']."').html('');".$ok."";
        }
        else
        { 
            $datatype = "dataType: 'json',";
            if (strlen($imprimir)>0)
            {
                $sucess="if (data.status == 'OK') {
                        ".$imprimir."
                    }else{
                        Swal.fire('Error!', data.descripcion+'!', 'error');
                        ".$error."
                    }";
            }
            else
            {
                $sucess="if (data.status == 'OK') {
                        ".$imprimir."
                        Swal.fire('CORRECTO!', data.descripcion+'!', 'success');
                        ".$ok."
                    }else{
                        Swal.fire('Error!', data.descripcion+'!', 'error');
                        ".$error."
                    }";
            }
        }
        
        $redirect = '';
        if (isset($dt['redirect'])) { $redirect = strlen($dt['redirect'])>0?$dt['redirect'].";":''; }
        
        if (strlen($redirect)>0) {
            self::$ajax .= "location.href='".$redirect."'/?".$dt['data'];
        }
        else {
            self::$ajax .= "$.ajax({
                            ".$url."
                            type: 'post',
                            ".$data."
                            ".$datatype."
                            success:function(data){
                                ".$sucess."
                            }
                        }).fail(function( jqXHR, textStatus, errorThrown ) {
                             if ( console && console.log ) {
                                Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                             }
                        });";
        }
    }

    public function __construct()
    {
        //Limpiamos las variables para volver a llamar
        self::$script = '';
        self::$ajax = '';
        //Cargas Css/Js/Script Obligatorios
        if (!isset(self::$ctlVariables)) {
            //$variables = new \dti_core("panel");
            self::$ctlVariables = 0;
        }
    }

    public function getAjax()
    {
        return self::$ajax;
    }
}
