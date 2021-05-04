<?php defined('BASEPATH') or exit('No se permite acceso directo'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="/resources/images/DTiAlta.ico"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<title>404 Pagina no encontrada</title>
<style type="text/css">

body {
	background-color: #fff;
	margin: 20px;
	font: 13px Helvetica, Arial, sans-serif;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 24px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
        font-size: 16px;
}
</style>
</head>
<body>
    <div id="container">
        <h1>404 Página no encontrada</h1>
        <?php if (!isset($error)) { ?>
        <p>No tiene permisos para ver esta página. <br> ó La página solicitada no se ha encontrado.<br> ó no tiene permisos para ver este contenido.<br> Comuniquese con soporte@dtiware.com.</p>
        <div class="container text-center">
            <a href="<?php echo $path_inicio; ?>">Ir a inicio</a>
            <br>
            <br>
            <a href="<?php echo $path_salir; ?>">Salir</a>
        </div>
        <?php } else { ?>
        <div class="container text-center">
        <?php echo $error; ?>
        </div>
        <?php } ?>
    </div>
</body>
</html>