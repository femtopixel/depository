<div id='users'>
	<h3>
		Modifier un client
	</h3>
	<form method='get' action='./Client.php'>
		<select id='id_user' name='id_user'>
			{$select}
		</select>
		<br/>
		<br/>
		<input type='submit' value='Envoyer' />
		<input type='button' value='Supprimer' onclick="deleteClient($('id_user').value);"/>
	</form>
</div>