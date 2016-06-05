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
	$myid = $_SESSION['id'];

$db = new database();
$user = new user($myid);
$client = $user->getClient();

$result = $db->parse("SELECT * FROM T_CLIENTS");
$select = '';
foreach ($result as $info)
{
	$select .= "<option value='{$info['client_id']}'";
	$select .= ($info['client_id'] == $client->getId()) ? "selected" : '';
	$select .= ">{$info['raison']}</option>";
}

$me = (($myid == $_SESSION['id']) && is_numeric($myid)) ? 1 : 0;
$me = (isset($_SESSION['super_admin'])) ? 0 : $me;

echo $hed;
$smarty->assign('select', $select);
$smarty->assign('admin', $user->getAdmin());
$smarty->assign('actif', (is_numeric($myid) && !$me && ($myid != 0)) ? $user->getActif() : 1);
$smarty->assign('login', $user->getLogin());
$smarty->assign('id', $myid);
$smarty->assign('me', $me);
$smarty->assign('superadmin', isset($_SESSION['super_admin']) ? 1 : 0);
$smarty->display('user.tpl');

require_once('foot.php');
?>