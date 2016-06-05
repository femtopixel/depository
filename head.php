<?php
session_start();
require_once('./classes/database.class.php');
require_once('./classes/client.class.php');
require_once('./classes/user.class.php');
require_once('SuperAdmin.php');
require_once('./library/Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = './templates';
$smarty->compile_dir = './templates/compile';

$user = (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) ? new user($_SESSION['id']) : 0;

$smarty->assign('issuperadmin', (isset($_SESSION['super_admin']) && is_numeric($_SESSION['super_admin'])) ? 1 : 0);
$smarty->assign('userlogged', (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) ? 1 : 0);
$smarty->assign('login', $_SESSION['login']);
$smarty->assign('adminmail', htmlentities($config['superadmin']['mail']));
$smarty->display('head.tpl');
?>