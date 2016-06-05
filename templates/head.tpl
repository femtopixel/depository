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
	<script type='text/javascript' src='./javascript/ajax.fileupload.js'></script>
	
	<link href="./javascript/themes/default.css" rel="stylesheet" type="text/css" ></link>
	<link href="./javascript/themes/alphacube.css" rel="stylesheet" type="text/css" ></link>
	<link rel="stylesheet" type="text/css" href="./css/style.css" />
	<link rel="stylesheet" href="./css/template.css" type="text/css" media="screen,projection" />
</head>
 
<body>
<div id='messagebox' style="display:none">
	<h1><u>Aide</u></h1>
	<br/>
	<div>
		Pour plus d'informations ou pour demander des administrateurs pour votre depository, merci de contacter<br/> <a href="mailto:{$adminmail}">{$adminmail}</a>
	</div>
</div>

<div id='texteditor' style="display:none"></div>

<div id="wrapper">
<div id="innerwrapper">

		<div id="header">
		
				<!--<form action="">
				<input value="Search" />
				</form>-->
				
				<h1><a href="javascript:help();">Depository</a></h1>
				
				<ul id="nav">
				
						<li><a href="index.php" accesskey='i'><em>I</em>ndex</a></li>
						
						{if $userlogged}
							{if $issuperadmin}
								<li><a href="Clients.php" accesskey='c'><em>C</em>lient</a></li>
							{/if}
							<li><a href="Users.php" accesskey='i'><em>U</em>tilisateur</a></li>
							<li><a href="delog.php" accesskey='d'><em>D</em>&eacute;connexion ({$login})</a></li>
						{/if}

				</ul>
				<!--
				<ul id="subnav">
				
						<li>Subnav:</li>
				
						<li><a href="index.html" accesskey="3"><em>3</em> Columns</a></li>
						
						<li><a href="twocolumns.html" accesskey="2" class="active"><em>2</em> Columns</a></li>
						
						<li><a href="#Intro"><em>I</em>ntro</a></li>
						
						<li><a href="#About"><em>A</em>bout</a></li>
						
						<li><a href="#Examples"><em>E</em>xamples</a></li>
						
						<li><a href="#Examples"><em>C</em>ontact</a></li>
				
				</ul>-->
		
		</div>
