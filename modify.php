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
if(isset($_REQUEST['strremove']))
{
	unset($profile['strs'][$_REQUEST['strremove']]);
	$adndb->updateProfile($profile);
} 
else if(isset($_POST['submit']))
{
	$str = strtoupper($_POST['str']);
	$al1 = $_POST['al1'];
	$al2 = $_POST['al2'];
	
	if($al1>$al2)
	{
		$warning = "Advertencia: $str/$al1/$al2<br>.<sub>Alelo 1 mayor que alelo 2, ordenados $str/$al2/$al1</sub>";
		$temp = $al2;
		$al2 = $al1;
		$al1 = $temp;
	}
	
	if(in_array($str,array_keys($frec)))
	{
		/*
		if(!in_array($al1,array_keys($frec[$str])))
		{
			$error = "Error: $str/$al1/$al2<br><br><sub>El valor del alelo 1 = $al1 es incompatible con marcador $str</sub>";
			$error = $error . "<br><sub> Valores posibles para el marcador $str: ";
			$coma = false;
			foreach(array_keys($frec[$str]) as $pval)
			{
				if($coma) 
					$error = $error . ", ";
				$error = $error . $pval;
				$coma = true;
			}
			$error = $error . "<sub>";
		}
		else if(!in_array($al2,array_keys($frec[$str])))
		{
			$error = "Error: $str/$al1/$al2<br><br><sub>El valor del alelo 2 = $al2 es incompatible con marcador $str</sub>";
			$error = $error . "<br><sub> Valores posibles para el marcador $str: ";
			$coma = false;
			foreach(array_keys($frec[$str]) as $pval)
			{
				if($coma) 
					$error = $error . ", ";
				$error = $error . $pval;
				$coma = true;
			}
			$error = $error . "<sub>";
		}
		else
		*/
		{
			$profile['strs'][$str] = array(
					'al1'=>$al1,
					'al2'=>$al2
					);
			$adndb->updateProfile($profile);
		}
	}
	else
	{
		$error = "Error: $str/$al1/$al2<br><br><sub>Marcador $str desconocido</sub>";
	}
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
    <title>ADN-DB: Modificacion Marcadores</title>
</head>
<body>
<body>
<div id="menu">
<a href="alta.php"><div class="btn">Nuevo perfil</div></a>
<a href="index.php"><div class="btn">Buscar</div></a>
<br>
</div>

<?php
	if(isset($warning))
	{
		echo "<p class=\"warning\">$warning</p>";
	}
	if(isset($error))
	{
		echo "<p class=\"error\">$error</p>";
	}
?>
<form class="formLayout2" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $profile['_id']; ?>">
<p>Modificar Marcadores</p>
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
<table>
<tr>
<th>Marcador</th><th>Alelo 1</th><th>Alelo 2</th><th></th>
</tr>
<?php
	foreach(array_keys($profile['strs']) as $str)
	{
		echo "<tr><td>" . $str . "</td><td>" . $profile['strs'][$str]['al1']. "</td><td>" .$profile['strs'][$str]['al2']. "</td><td><a href='" . $_SERVER['PHP_SELF'] ."?strremove=" . $str. "&id=" . $profile['_id'] . "'>[-]</a></td></tr>\n";
	} 
?>
<tr>
<td><input name="str" autofocus="autofocus"></td>
<td><input name="al1"></td>
<td><input name="al2"></td>
<td><button type="submit" name="submit">+</button></td>
</tr>
</table>
<label></label>
<br>
</form>
<div id="submenu">
<a href="checkbd.php?id=<?php echo $profile['_id']?>"><div class='btn'>Finalizar</div></a>
</div>
<p><sub><?php echo $adndb->version; ?></sub></p>

</body>
</html>
