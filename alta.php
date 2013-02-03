<?php

include("adndb.php");
$adndb = new AdnDB();

if(isset($_REQUEST['id']))
{
	$profile = $adndb->getProfile($_REQUEST['id']);
}

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
	
	if($id=="")
	{
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
		$adndb->add($profile);
	}
	else
	{
		$profile = $adndb->getProfile($id);
		$profile['name'] = $name;
		$profile['surname'] = $surname;
		$profile['prov'] = $prov;
		$profile['ccaa'] = $ccaa;
		$profile['rel'] = $rel;
		$profile['date'] = $date;
		$profile['contact'] = $contact;
		$adndb->updateProfile($profile);
	}
	
	
  echo '<meta http-equiv="Refresh" content="0;viewprofile.php?id=' . $profile['_id'] . '">';
	exit();
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

<form class="formLayout" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<p><?php if(isset($profile)) echo "Modificacion Datos"; else echo "Alta Nuevo Perfil"?></p>
<input type="hidden" name="id" value="<?php if(isset($profile)){echo $profile['_id'];} ?>"></input>
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
<button type="submit" name="submit">Guardar</button><br>
</form>
<div id="submenu">
<br>
</div>
<p><sub><?php echo $adndb->version; ?></sub></p>
</body>
</html>