<div id='users'>
	<h3>
		Modifier un utilisateur
	</h3>
	<form method='get' action='./User.php'>
		<select id='id_user' name='id_user'>
			{$select}
		</select>
		<br/>
		<br/>
		<input type='submit' value='Envoyer' />
		<input type='button' value='Supprimer' onclick="deleteUser($('id_user').value);"/>
	</form>
</div>