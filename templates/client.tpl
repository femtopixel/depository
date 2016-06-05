<div id='users'>
	<h3>
		{if $add eq 1}Ajouter un client{/if}
		{if $add ne 1}Modifier un client{/if}
	</h3>
	<form method='post' action='./post/client.post.php?id_user={$id}'  onsubmit='return(verifForm(this));'>
		<label>Raison sociale</label><input type='text' name='raison' value="{$raison}" /><br/>
		{if $add eq 1}
			<label>UID</label><input type='text' name='uid'/><br/>
			<label>GID</label><input type='text' name='gid'/><br/>
		{/if}
		<label>Repertoire de base</label><input type='text' name='dir' value="{$dir}" /><br/>
		<label>Actif</label><input type='checkbox' name='actif' {if $actif}checked{/if} /><br/><br/>
		{if $add eq 1}
			<label>Login Administrateur</label><input type='text' name='login' /><br/>
			<label>Password Administrateur</label><input type='password' name='password1' /><br/>
			<label>Comfirmer Password Administrateur</label><input type='password' name='password2' /><br/>
		{/if}
		<br/><br/>
		<input type='submit' value='Envoyer' />
	</form>
</div>