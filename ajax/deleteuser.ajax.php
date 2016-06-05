<?php
session_start();
require_once('../SuperAdmin.php');
require_once('../classes/database.class.php');
require_once('../classes/client.class.php');
require_once('../classes/user.class.php');

if (!isset($_SESSION['id'])) /* Verifie la connexion du client */
{
	header('Location:../ShowMessage.php?mess=notlogged');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=notlogged';</script>");
}

$db = new database();

if (isset($_SESSION['super_admin'])) /* Verifie si on est super admin ... */
{
	$sql = "SELECT * FROM FTP_USERS";
}
else if (isset($_SESSION['admin'])) /* .. admin ... */
{
	$myuser = new user($_SESSION['id']);
	$myclient = $myuser->getClient();
	
	$sql = "SELECT * FROM FTP_USERS WHERE (admin = '0' && client_id = '".$myclient->getId()."')";
}
else /* ... ou client */
{
	$sql = "SELECT * FROM FTP_USERS WHERE user_id = 0";
}

$result = $db->parse($sql);

$autorise = Array(); 
foreach ($result as $info) /* on recuperre les ID des user qui ont le droit d'etre modifie en fonction des droits */
	$autorise[$info['user_id']] = 1;

$id_user = (is_numeric($_POST['id_user']) ? $_POST['id_user'] : 0);
if (!isset($autorise[$id_user])) /* Si l'id demande n'est pas accessible avec les droits qu'on a alors on affiche l'erreur */
{
	header('Location:../ShowMessage.php?mess=droit');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=droit';</script>");
}

$sql = "DELETE FROM FTP_USERS WHERE user_id = '$id_user'";
$db->send($sql);

header('Location:../Users.php'); /* Retourne a l'interface d'admin de modification du user */
die ("<script type='text/javascript'>window.location = '../Users.php';</script>");
?>