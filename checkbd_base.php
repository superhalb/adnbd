<?php

include("adndb.php");
$adndb = new AdnDB();
include("frecuencias.php");

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
<p>Cotejamiento con frecuencias <?php if($predeterminadas) echo "predeterminadas"; else echo "calculadas"; ?></p>
<table>
<?php

$results = $adndb->allProfiles();

if(isset($results))
{
?>
<tr>
<th>Nombre</th><th>Apellidos</th><th>W</th><th>Matchs</th><th></th>
</tr>
<?php
	$noRes = true;
	foreach($results as $p)
	{
		if($profile['_id']!=$p['_id'])
		{
			$comp = $adndb->comp($profile,$p);
			if($comp['checks']>0)
			{
				if($comp['matchs']/$comp['checks'] >= 0.8)
				{
					$noRes = false;
					echo '<tr><td>' . $p['name']. '</td><td>' . $p['surname']. '</td><td>' . number_format($comp['ipTotal'],5,',','.') . '%</td><td>' . $comp['matchs'] .'/'. $comp['checks'] . '</td><td><a href="viewprofile.php?id=' . $p['_id'] . '">ver</a></td></tr>';
				}
			}
		}
	}
	if($noRes)
		echo "<tr><td colspan=\"5\">Ningun resultado</td></tr>";
}
?>
</table>

<br>
<a href="modify.php?id=<?php echo $profile['_id']?>"><div class="btn">Modificar Marcadores</div></a><br>
<a href="alta.php?id=<?php echo $profile['_id']?>"><div class="btn">Modificar Datos</div></a><br>
<a href="checkbd.php?id=<?php echo $profile['_id']?>"><div class="btn">Volver a cotejar con frecuencias calculadas</div></a><br>
<a href="checkbddf.php?id=<?php echo $profile['_id']?>"><div class="btn">Volver a cotejar con frecuencias predeterminadas</div></a><br>
</div>
<p><sub><?php echo $adndb->version; ?></sub></p>

</body>
</html>
