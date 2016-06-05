<?php
ob_start();
require_once('head.php');
$hed = ob_get_contents();
ob_end_clean();

if (!isset($_SESSION['id']))
{
	header ('Location:./ShowMessage.php?mess=notlogged');
	die ("<script type='text/javascript'>window.location = './ShowMessage.php?mess=notlogged';</script>");
}
$myid = (is_numeric($_GET['id_user'])) ? $_GET['id_user'] : '';
if (!isset($_SESSION['admin']) && !isset($_SESSION['super_admin'])) /* Si on est ni admin ni superadmin alors on modifie que nos infos */
{
	header ('Location:./User.php?id_user='.$_SESSION['id']);
	die ("<script type='text/javascript'>window.location = './User.php?id_user={$_SESSION['id']}';</script>");
}

$db = new database();

$sql = '';
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

$result = $db->parse($sql);
$select = '<option value="0">Ajouter un utilisateur</option>';
foreach ($result as $info)
{
	$select .= "<option value='{$info['user_id']}'>{$info['user']}</option>";
}
echo $hed;
$smarty->assign('select', $select);
$smarty->display('users.tpl');

require_once('foot.php');
?>