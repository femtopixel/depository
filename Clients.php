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
if (!isset($_SESSION['super_admin'])) /* Si on est pas superadmin alors on a pas les droits */
{
	header ('Location:./ShowMessage.php?mess=droit');
	die ("<script type='text/javascript'>window.location = './ShowMessage.php?mess=droit';</script>");
}

$db = new database();

$sql = 'SELECT * FROM T_CLIENTS';

$result = $db->parse($sql);
$select = '<option value="0">Ajouter un client</option>';
foreach ($result as $info)
{
	$select .= "<option value='{$info['client_id']}'>{$info['raison']}</option>";
}
echo $hed;
$smarty->assign('select', $select);
$smarty->display('clients.tpl');

require_once('foot.php');
?>