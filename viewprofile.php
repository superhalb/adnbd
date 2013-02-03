<?php

include("adndb.php");
$adndb = new AdnDB();

if(isset($_REQUEST['id']))
{
	$profile = $adndb->getProfile($_REQUEST['id']);
}
else
{
	echo "Error, no hay perfil seleccionado";
	exit;
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
    <title>ADN-DB: Mostrar Perfil</title>
</head>
<body>
<div id="menu">
<a href="alta.php"><div class="btn">Nuevo perfil</div></a>
<a href="index.php"><div class="btn">Buscar</div></a>
<br>
</div>

<form class="formLayout2" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $profile['_id']; ?>">
<p>Perfil</p>
<label>Nombre</label>
<span><?php echo $profile['name']; ?></span><br>
<label>Apellidos</label>
<span><?php echo $profile['surname']; ?></span><br>
<label>Provincia</label>
<span><?php echo $adndb->provStr[$profile['prov']]; ?></span><br>
<label>CCAA</label>
<span><?php echo $adndb->ccaaStr[$profile['ccaa']]; ?></span><br>
<label>Relacion</label>
<span><?php echo $adndb->relStr[$profile['rel']]; ?></span><br>
<label>Fecha</label>
<span><?php echo $profile['date']; ?></span><br>
<label>Contacto</label>
<span><?php echo $profile['contact']; ?></span><br>
<label></label>
<table>
<tr>
<th>Marcador</th><th>Alelo 1</th><th>Alelo 2</th>
</tr>
<?php
	foreach(array_keys($profile['strs']) as $str)
	{
		echo "<tr><td>" . $str . "</td><td>" . $profile['strs'][$str]['al1']. "</td><td>" .$profile['strs'][$str]['al2']. "</td></tr>\n";
	} 
?>
</table>
<label></label>
</form>
<div id="submenu">
<a href="modify.php?id=<?php echo $profile['_id']?>"><div class="btn">Modificar Marcadores</div></a><br>
<a href="alta.php?id=<?php echo $profile['_id']?>"><div class="btn">Modificar Datos</div></a><br>
<a href="checkbd.php?id=<?php echo $profile['_id']?>"><div class="btn">Cotejar con base de datos</div></a><br>
<a href="javascript:confirmar()"><div class="btn">Eliminar perfil</div></a><br>
</div>
<p><sub><?php echo $adndb->version; ?></sub></p>
<script type="text/javascript">
function confirmar() {
	var r = confirm("Realmente deseas borrar este perfi?");
	if(r) {
		window.location = "/baja.php?id=<?php echo $profile['_id']?>";
	}
}
</script>
</body>
</html>
