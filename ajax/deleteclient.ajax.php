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
if (!isset($_SESSION['super_admin'])) /* Verifie si on est super admin ... */
{
	header('Location:../ShowMessage.php?mess=droit');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=droit';</script>");
}

$db = new database();

$id_user = (is_numeric($_POST['id_user'])) ? $_POST['id_user'] : 0;

$sql = "DELETE FROM T_CLIENTS WHERE client_id = '$id_user'";
$db->send($sql);
$sql = "DELETE FROM FTP_USERS WHERE client_id = '$id_user'";
$db->send($sql);

header('Location:../Clients.php'); /* Retourne a l'interface d'admin de modification du user */
die ("<script type='text/javascript'>window.location = '../Clients.php';</script>");
?>