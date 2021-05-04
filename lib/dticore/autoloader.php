<?php

//Registra las clases necesarias para la funcion de cada Controlador
spl_autoload_register(function($clase){
    //Agrega Configuracion de Formularios
    $ruta = "lib/dticore/layout/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/login/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/builder/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/core/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/circle/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/acordion/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/table/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/step/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/panel/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/boxquick/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/box/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
    $ruta = "lib/dticore/dtife/".str_replace("\\", "/", $clase).".php";
    if (is_readable($ruta)) require_once $ruta;
});
