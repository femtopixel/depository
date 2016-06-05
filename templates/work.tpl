		<div id="sidebar">
			<div>
				<h2>Edition</h2>
				
				<h3>Copier</h3>
				
				<div class="news">
					<div id='copieur'>
						Glissez les &eacute;l&eacute;ments &agrave; copier ici...
					</div>
				</div>
				
				<h3>Couper</h3>
				
				<div class="news">
					<div id='coupeur'>
						Glissez les &eacute;l&eacute;ments &agrave; couper ici...
					</div>
				</div>
				
				<script type='text/javascript'>
					activateCopieur();
					activateCoupeur();
				</script>
			</div>
			<br/>
			<hr style='color:#FFFFFF; background:#FFFFFF;'/>
			<br/>
			<div>
				<h2>Dossier</h2>
				
				<h3>Cr&eacute;er un dossier</h3>
				
				<div class="news">
					<form method='post' action='javascript:void(0);' onsubmit='return(createFolder(this));'>
						<input type='text' name='folder' class='grey' onfocus='this.value = "";'/><br/>
						<input type='submit' value='Envoyer' class='mysubmit'/>
					</form>
				</div>
				
			</div>
			<br/>
			<hr style='color:#FFFFFF; background:#FFFFFF;'/>
			<br/>
			<div>
				<h2>Upload</h2>
				
				<h3>Uploader un fichier</h3>
				
				<div class="news">
					<div id='ajaxfileupload'></div>
					<script type='text/javascript'>
						activateAjaxFileUpload();
					</script>
				</div>
				
			</div>
		</div>
		
		
		
		<div id="contentnorightbar">
			
			<div id='work'></div>
			<div class='spacer'></div>
			<script type='text/javascript'>getDir('{$dir}');</script>
			
		</div>