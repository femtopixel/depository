<?php
ob_start();
require_once('head.php');
$hed = ob_get_contents();
ob_end_clean();
if (!isset($_SESSION['super_admin'])) /* Si on est pas super admin, on a pas acces a la fonction */
{
	header ('Location:./ShowMessage.php?mess=droit');
	die ("<script type='text/javascript'>window.location = './ShowMessage.php?mess=droit';</script>");
}

echo $hed;
$smarty->assign('add', (is_numeric($_GET['id_user']) && $_GET['id_user'] > 0) ? 0 : 1);
$client = new client($_GET['id_user']);

$smarty->assign('raison', $client->getRaison());
$smarty->assign('dir', ($client->getDir() != '') ? str_replace('././', '', $client->getDir()) : getcwd());
$smarty->assign('id', (is_numeric($_GET['id_user'])) ? $_GET['id_user'] : '');
$smarty->assign('actif', $client->getActif());
if (!is_numeric($_GET['id_user']))
	$smarty->assign('actif', 1);
$smarty->display('client.tpl');
require_once('foot.php');
?>
