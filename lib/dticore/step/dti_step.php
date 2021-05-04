<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 05/05/2017
 * Version: 1.0.1
 *
 */
class dti_step {

    private static $ctlVariables,$step,$maestro,$detalle,$activo,$numStep;

    public function __construct() {
        self::$step = '';
        self::$maestro = '';
        if (!isset(self::$ctlVariables)) {
            dti_core::set('css',"<link href='public/css/componentes/step/smart_wizard.css' rel='stylesheet' type='text/css'/>");
            dti_core::set('css',"<link href='public/css/componentes/step/smart_wizard_theme_arrows.css' rel='stylesheet' type='text/css'/>");
            dti_core::set('script',"<script src='public/js/componentes/step/jquery.smartWizard.js' type='text/javascript'></script>");
            dti_core::set('script',"<script src='public/js/componentes/step/dti_step.js' type='text/javascript'></script>");
            self::$ctlVariables = 0;
            self::$numStep = 0;
        }
    }
    
    /**
     *
     * @param array $dt id,tip,icono,contenido,siguiente,anterior,saltar,fin,onclic
     */
    public function setStep($dt){
        $botones = '';
        self::$numStep++;//,\'next-btn'.self::$numStep.'\'
        if (isset($dt['cancelar'])) $botones .= '<button type="button" class="btn btn-danger" id="cancel-btn" onclick=\''.$dt['onclicCancel'].'\'>Cancelar</button>';
        if (isset($dt['anterior'])) $botones .= '<button type="button" class="btn btn-secondary" id="prev-btn'.self::$numStep.'">Anterior</button>';
        //if (isset($dt['saltar'])) $botones .= '<button type="button" class="btn btn-secondary" id="prev-btn">Saltar</button>';
        if (isset($dt['siguiente'])) $botones .= '<button type="button" class="btn btn-secondary" id="next-btn'.self::$numStep.'">Siguiente</button>';
        if (isset($dt['guardar'])) $botones .= '<button type="button" class="btn btn-secondary" id="next-save-btn'.self::$numStep.'" onclick=\''.$dt['onclic'].'\'>Guardar y Siguiente</button>';
        if (isset($dt['solo_guardar'])) $botones .= '<button type="button" class="btn btn-secondary" id="next-save-btn'.self::$numStep.'" onclick=\''.$dt['onclic'].'\'>Guardar</button>';
        if (isset($dt['fin'])) $botones .= '<button type="button" class="btn btn-primary" id="fin-btn" onclick=\''.$dt['onclic'].'\'>Finalizar</button>';
        if (isset($dt['solo_fin'])) $botones .= '<button type="button" class="btn btn-primary" id="fin-btn" onclick=\''.$dt['solo_fin'].'\'>Finalizar</button>';
        

        self::$maestro .= '<li>
                                <a href="#'.$dt['id'].'">Paso '.self::$numStep.'<br />
                                <small><i class="'.$dt['icono'].' fa-lg">'.$dt['tip'].'</i></small>
                                </a>
                            </li>';
        self::$detalle .= '<div id="'.$dt['id'].'" class="">
                            <h3 class="border-bottom border-gray pb-2">'.$dt['tip'].'</h3>
                            '.$dt['contenido'].'<br />
                            <ul class="btn_step pull-right">
                                '.$botones.'
                            </ul>
                        </div>';

    }

    public function getStep(){
        self::$step .= '<div id="smartwizard">
                        <ul>';
        //Agregar el Maestro
        self::$step .= self::$maestro;

        self::$step .= '</ul>
                       <div>';
        //Agregar el detalle
        self::$step .= self::$detalle;

        self::$step .= '</div>
                    </div>';

        return self::$step;
    }
}