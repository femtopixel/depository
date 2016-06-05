<?php
session_start();
require_once('../SuperAdmin.php');
require_once('../classes/database.class.php');
require_once('../classes/client.class.php');
require_once('../classes/user.class.php');

if (($_POST['password1'] != $_POST['password2']) && ($_POST['password2'] != '')) /* Verifie les passwords */
{
	header('Location:../ShowMessage.php?mess=password');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=password';</script>");
}
if (!isset($_SESSION['id'])) /* Verifie la connexion du client */
{
	header('Location:../ShowMessage.php?mess=notlogged');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=notlogged';</script>");
}

$db = new database();
$mylogin = addslashes(stripslashes($_POST['login']));
$sql = "SELECT * FROM FTP_USERS WHERE user = '$mylogin'";
$result = $db->parse($sql);
if (count($result) && (!is_numeric($_GET['id_user']) || $_GET['id_user'] < 1)) /* Verifie que le user n'existe pas deja ... */
{
	header('Location:../ShowMessage.php?mess=pseudoexist');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=pseudoexist';</script>");
}

if (isset($_SESSION['super_admin'])) /* Verifie si on est super admin ... */
{
	$sql = "SELECT * FROM FTP_USERS";
}
else if (isset($_SESSION['admin'])) /* .. admin ... */
{
	$myuser = new user($_SESSION['id']);
	$myclient = $myuser->getClient();
	
	$sql = "SELECT * FROM FTP_USERS WHERE (admin = '0' && client_id = '".$myclient->getId()."') OR user_id = '{$_SESSION['id']}'";
}
else /* ... ou client */
{
	$sql = "SELECT * FROM FTP_USERS WHERE user_id = '{$_SESSION['id']}'";
}

$result = $db->parse($sql);

$autorise = Array(); 
foreach ($result as $info) /* on recuperre les ID des user qui ont le droit d'etre modifie en fonction des droits */
	$autorise[$info['user_id']] = 1;

if (isset($_SESSION['super_admin'])) /* Verifie si on est super admin ... */
	$autorise[0] = 1;
else if (isset($_SESSION['admin'])) /* .. admin ... */
	$autorise[0] = 1;
	
$id_user = (is_numeric($_GET['id_user']) ? $_GET['id_user'] : 0);
if (!isset($autorise[$id_user])) /* Si l'id demande n'est pas accessible avec les droits qu'on a alors on affiche l'erreur */
{
	header('Location:../ShowMessage.php?mess=droit');
	die ("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=droit';</script>");
}

if ($id_user == 0)
	$id_user = '';
$user = new user($id_user);

if ($_POST['login'] == $config['superadmin']['login'])
	$_POST['login'] .= '1';

if (($user->getId() != $_SESSION['id']) || (isset($_SESSION['super_admin']))) /* Si c'est pas toi ou si on est super admin*/
	$user->setLogin($_POST['login']); /* on change le login */
	
if ($_POST['password1'] != '')
	$user->setPassword($_POST['password1']); /* on change le password si il est different que celui envoye */
	
if (($user->getId() != $_SESSION['id'])  || (isset($_SESSION['super_admin']))) /* Si c'est pas toi ou si on est super admin*/
	$user->setActif(isset($_POST['actif']) ? 1 : 0); /* on change l'etat actif */

$myuser = new user($_SESSION['id']);
if (isset($_POST['client_id']))
	$user->setClientId($_POST['client_id']);
else
{
	if ($myuser->getAdmin())
	{
		$myclient = $myuser->getClient();
		$user->setClientId($myclient->getId());
	}
}

if (isset($_SESSION['super_admin']))
	$user->setAdmin(isset($_POST['admin']) ? 1 : 0);

$user->commit(); /* on enregistre */

header('Location:../User.php?id_user='.$user->getId()); /* Retourne a l'interface d'admin de modification du user */
die ("<script type='text/javascript'>window.location = '../User.php?id_user=".$user->getId()."';</script>");
?>