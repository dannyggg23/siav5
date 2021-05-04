<?php defined('BASEPATH') or exit('No se permite acceso directo'); ?>
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <base href="<?php echo APP_URL ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="<?php if(isset($descripcion)) {echo $descripcion;} ?>">
        <meta name="author" content="<?php if(isset($autor)) {echo $autor;} ?>">
        <title><?php if(isset($titulo)) {echo $titulo;} ?></title>
        <link rel="Shortcut Icon" href="<?php if(isset($favicon)) {echo $favicon;} ?>" type="image/x-icon"/>
        <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.1.0/sandstone/bootstrap.min.css" rel="stylesheet" integrity="sha384-0SEzCkemOL0R1IOfjiayiYyt8BkqxwlmXBmFWMUNeG0BSo/XUh4xAF5ybf+Qr/4x" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js" integrity="sha384-lZmvU/TzxoIQIOD9yQDEpvxp6wEU32Fy0ckUgOH4EIlMOCdR823rg4+3gWRwnX1M" crossorigin="anonymous"></script>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css">
        <script src="<?php echo PATH_JS ?>globalfuntions.js" type="text/javascript"></script>
        <link href="public/skins/negro/style.css" rel="stylesheet" type="text/css"/>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.es.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <link href="public/loading/loading.css" rel="stylesheet" type="text/css" />
        <script src="public/loading/loading.js"></script>
        <!-- Css Declarados -->
        <?php if(isset($css)) {echo $css;} ?>
        <!-- Js Declarados -->
        <?php if(isset($js)) {echo $js;} ?>
        <!-- Fonts and icons -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body onload="deshabilitaRetroceso()">
        <div id='_AJAX_ERROR_' class="alert_top"></div>
        <div id='_AJAX_LOADING_' class=""></div>
        <?php if(isset($layout)) {echo $layout;} ?>
        <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
        <?php if(isset($modal)) {echo $modal;} ?>
        <?php if(isset($script)) {echo $script;} ?>
        <div class='_MODAL_'></div>
        <div class='_SCRIPT_'></div>
    </body>
</html>