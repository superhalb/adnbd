<?php

include("adndb.php");
$adndb = new AdnDB();

if(isset($_REQUEST['id']))
{
	$profile = $adndb->getProfile($_REQUEST['id']);
	$adndb->delete($profile);
}
?>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="language" content="es" />
    <link href='./stylesheet.css' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
    <title>ADN-DB: Alta / Modificacion Datos</title>
</head>
<body>
<div id="menu">
<?php if(isset($profile))
{
?>
<a href="alta.php"><div class="btn">Nuevo perfil</div></a>
<?php
}
?>
<a href="index.php"><div class="btn">Buscar</div></a>
<br>
</div>
<p>Perfil eliminado</p>
<p><sub><?php echo $adndb->version; ?></sub></p>
</body>
</html>