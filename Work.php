<?php
require_once('head.php');

$smarty->assign('dir', ((isset($_GET['dir']) && $_GET['dir'] != '') ? rawurlencode($_GET['dir']) : '/'));

$smarty->display('work.tpl');

require_once('foot.php');
?>