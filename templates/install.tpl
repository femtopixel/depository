<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Depository</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	
	<script type='text/javascript' src='./javascript/prototype.js'></script>
	<script type='text/javascript' src='./javascript/scriptaculous.js'></script>
	<script type="text/javascript" src="./javascript/effects.js"> </script>
	<script type="text/javascript" src="./javascript/window.js"> </script>
	<script type="text/javascript" src="./javascript/application.js"> </script> 
	<script type='text/javascript' src='./javascript/library.js'></script>
	
	<link href="./javascript/themes/default.css" rel="stylesheet" type="text/css" ></link>
	<link href="./javascript/themes/alphacube.css" rel="stylesheet" type="text/css" ></link>
	<link rel="stylesheet" type="text/css" href="./css/style.css" />
	<link rel="stylesheet" href="./css/template.css" type="text/css" media="screen,projection" />
</head>
 
<body>

<div id='messagebox' style="display:none">
	<h1><u>Installation</u></h1>
	<br/>
	<div>
		Ceci est l'interface d'installation de Depository. Merci de supprimer ce fichier &agrave; la fin de son utilisation
	</div>
</div>

<div id="wrapper">
<div id="innerwrapper">

		<div id="header">
		
				<h1><a href="javascript:help();">Depository</a></h1>
				
				<ul id="nav">
				
						{if $ok ne 1}
							<li><a href="install.php" accesskey='i'><em>I</em>nstallation</a></li>
						{else}
							<li><a href="index.php" accesskey='i'><em>I</em>ndex</a></li>
						{/if}
						
				</ul>
		</div>
		<div id="users">
			{if $ok ne 1}
			<form method='post' action='#' onsubmit='return(verifForm(this));'>
			<label>Url du serveur MySQL</label><input type='text' name='bddurl' value='localhost'/><br/>
			<label>Nom de l'utilisateur de connexion a la BDD</label><input type='text' name='bddlogin' /><br/>
			<label>Password de connexion a la BDD</label><input type='password' name='bddpassword' /><br/>
			<label>Nom de la base de donnee</label><input type='text' name='bddname' /><br/>
			<label>Login du SuperAdmin</label><input type='text' name='superadminlogin' /><br/>
			<label>Password du SuperAdmin</label><input type='password' name='superadminpassword' /><br/>
			<label>Mail du SuperAdmin</label><input type='text' name='superadminmail' /><br/>
			<br/>
			<br/>
			<input type='submit' value='Installer' />
			</form>
			{else}
			Depository est install&eacute; correctement. Merci de supprimer le fichier 'install.php' de facon &agrave; pouvoir utiliser l'application.
			{/if}			
		</div>
		{if $ok ne 1}
		<script type='text/javascript'>help();</script>
		{/if}