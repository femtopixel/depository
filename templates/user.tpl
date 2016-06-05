<div id='users'>
	<h3>
		{if $id lt 1}Ajouter un utilisateur{/if}
		{if $id gt 0 and $me ne 1}Modifier un utilisateur{/if}
		{if $id gt 0 and $me eq 1}Modifier mon mot de passe{/if}		
	</h3>
	<form method='post' action='./post/user.post.php?id_user={$id}' onsubmit='return(verifForm(this));'>
		<label>Login : </label><input type='text' name='login' value="{$login}" {if $me eq 1}readonly{/if} /><br/>
		<label>Password : </label><input name='password1' type='password' {if $id gt 0}lang='ignore'{/if}/><br/>
		<label>Confirmation Password : </label><input name='password2' type='password' {if $id gt 0}lang='ignore'{/if}/><br/>
		{if $superadmin}
			<label>Client : </label><select name='client_id'>{$select}</select><br/>
		{/if}
		{if $me ne 1}
			<label>Actif</label><input type='checkbox' name='actif' {if $actif}checked{/if} /><br/>
		{/if}
		{if $superadmin}
			<label>Admin</label><input type='checkbox' name='admin' {if $admin}checked{/if} /><br/>
		{/if}
		<br/>
		<input type='submit' value='Envoyer' />
	</form>
</div>