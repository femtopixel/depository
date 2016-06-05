<?php
session_start();
ob_start();
require_once('head.php');
$hed = ob_get_contents();
ob_end_clean();

if (isset($_SESSION['id']))
{
	header('Location:./Work.php');
	die ("<script type='text/javascript'>window.location = './Work.php';</script>");
}
	
$error = '';
if (isset($_POST['login']) && isset($_POST['password']))
{
	$user = new user();
	if (($_POST['login'] == $config['superadmin']['login']) && ($_POST['password'] == $config['superadmin']['password'])) /*verifie si c'est le super admin */
	{
		$_SESSION['id'] = 0;
		$_SESSION['login'] = 'SuperAdmin';
		$_SESSION['super_admin'] = 1;
		header('Location:./Work.php');
		die ("<script type='text/javascript'>window.location = './Work.php';</script>");
	}
	else if ($user->Connect($_POST['login'], $_POST['password']))
	{
		$_SESSION['id'] = $user->getID();
		if ($user->getAdmin())
			$_SESSION['admin'] = 1;
		$_SESSION['login'] = $user->getLogin();
		$_SESSION['dir'] = $user->getDir();
		header('Location:./Work.php');
		die ("<script type='text/javascript'>window.location = './Work.php';</script>");
	}
	else
	{
		$error = 'Login / password failed';
	}
}

echo $hed;
$smarty->assign('error', $error);
$smarty->display('login.tpl');
require_once('foot.php');
?>