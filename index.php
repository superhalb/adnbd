<?php

include("adndb.php");
$adndb = new AdnDB();

if(isset($_POST['submit']))
{
	$id = $_POST['id'];
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	$prov= $_POST['prov'];
	$ccaa= $_POST['ccaa'];
	$rel= $_POST['rel'];
	$date= $_POST['date'];
	$contact= $_POST['contact'];
	
	$profile = array
	(
		'name' => $name,
		'surname' => $surname,
		'prov' => $prov,
		'ccaa' => $ccaa,
		'rel' => $rel,
		'date' => $date,
		'contact' => $contact,
		'strs' => array()
	);
	
	$results = $adndb->search($profile);
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
    <title>ADN-DB: Busqueda</title>
</head>
<body>
<div id="menu">
<a href="alta.php"><div class="btn">Nuevo perfil</div></a>
<br>
</div>

<form class="formLayout" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<p>Busqueda</p>
<label>Nombre</label>
<input name="name" value="<?php if(isset($profile)){echo $profile['name'];} ?>"><br>
<label>Apellidos</label>
<input name="surname" value="<?php if(isset($profile)){echo $profile['surname'];} ?>"><br>
<label>Provincia</label>
<select name="prov">
<?php
	foreach(array_keys($adndb->provStr) as $key)
	{
	?>
		<option value="<?php echo $key; ?>" <?php if(isset($profile)){if($profile['prov']==$key) echo 'selected="selected"';} ?> ><?php echo $adndb->provStr[$key];?></option>
	<?php
	}
?>
</select><br>
<label>CCAA</label>
<select name="ccaa">
<?php
	foreach(array_keys($adndb->ccaaStr) as $key)
	{
	?>
		<option value="<?php echo $key; ?>" <?php if(isset($profile)){if($profile['ccaa']==$key) echo 'selected="selected"';} ?> ><?php echo $adndb->ccaaStr[$key];?></option>
	<?php
	}
?></select><br>
<label>Relacion</label>
<select name="rel">
<?php
	foreach(array_keys($adndb->relStr) as $key)
	{
	?>
		<option value="<?php echo $key; ?>" <?php if(isset($profile)){if($profile['rel']==$key) echo 'selected="selected"';} ?> ><?php echo $adndb->relStr[$key];?></option>
	<?php
	}
?></select><br>
<label>Fecha</label>
<input name="date" value="<?php if(isset($profile)){echo $profile['date'];} ?>"><br>
<label>Contacto</label>
<input name="contact" value="<?php if(isset($profile)){echo $profile['contact'];} ?>"><br>
<label></label>
<button type="submit" name="submit">Buscar</button><br>
</form>
<div id="submenu">
<table>
<?php
if(isset($results))
{
?>
<tr>
<th>Nombre</th><th>Apellidos</th><th></th>
</tr>
<?php
	foreach($results as $p)
	{
		echo '<tr><td>' . $p['name']. '</td><td>' . $p['surname']. '</td><td><a href="viewprofile.php?id=' . $p['_id'] . '">ver</a></td></tr>';
	}
}
?>
</table>
<br>
</div>
<p><sub><?php echo $adndb->version; ?></sub></p>
</body>
</html>