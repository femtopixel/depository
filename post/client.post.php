<?php
session_start();
require_once('../SuperAdmin.php');
require_once('../classes/database.class.php');
require_once('../classes/client.class.php');
require_once('../classes/user.class.php');

if (!isset($_SESSION['super_admin'])) /* Si on est pas super admin, on a pas acces a la fonction */
{
	header ('Location:../ShowMessage.php?mess=droit');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=droit';</script>");
}
if (!is_numeric($_GET['id_user']) && ($_POST['password1'] != $_POST['password2'])) /* Si on ajoute mais que les passwords ne correspondent pas on affiche le message... */
{
	header ('Location:../ShowMessage.php?mess=password');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=password';</script>");
}
	
$db = new database();
$client = new client((is_numeric($_GET['id_user']) && $_GET['id_user'] > 0) ? $_GET['id_user'] : ''); /* Cree un nouveau client si on ajoute, sinon on modifie */

$client->setRaison($_POST['raison']); /* Change la raison du client */
$client->setActif(isset($_POST['actif']) ? 1 : 0); /* Change l'etat actif du client */

if ($_GET['id_user'] == 0) /* Si on ajoute */
{
	$client->setUid($_POST['uid']); /* Change l'UID du client */
	$client->setGid($_POST['gid']); /* Change le GID du client */
}

$client->setDir($_POST['dir']); /* Change le repertoire de depot du client */
$client->commit(); /* Enregistre les modifications du client */

if (!is_numeric($_GET['id_user']) || $_GET['id_user'] == 0) /* Si on ajoute */
{
	$user = new user(); /* on va creer un utilisateur */
	if ($_POST['login'] == $config['superadmin'])
		$_POST['login'] .= '1';
	$user->setLogin($_POST['login']); /* change le login de l'utilisateur */
	$user->setPassword($_POST['password1']); /* Change le password */
	$user->SetAdmin(1); /* Met le droit administrateur */
	$user->SetActif(1); /* Met l'etat actif sur l'utilisateur */
	$user->SetClientId($client->getId()); /* Lie l'utilisateur au client qui viens d'etre cree */
	$user->commit(); /* enregistre l'utilisateur */
}

header('Location:../Client.php?id_user='.$client->getId()); /* Retourne a l'interface d'admin de modification du client */
die ("<script type='text/javascript'>window.location = '../Client.php?id_user=".$client->getId()."';</script>");
?>