<?php
session_start();
require_once('../classes/database.class.php');
require_once('../classes/client.class.php');
require_once('../classes/user.class.php');
require_once('../library/Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates/compile';


if (!isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
{
	header('Location:../ShowMessage.php?mess=notlogged');
	die("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=notlogged';</script>");
}
if (!isset($_SESSION['sessiondir']) || $_SESSION['sessiondir'] == '')
{
	header('Location:../ShowMessage.php?mess=security');
	die("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=security';</script>");
}

if (!isset($_SESSION['myfile']) || $_SESSION['myfile'] == '')
{
	header('Location:../ShowMessage.php?mess=security');
	die("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=security';</script>");
}

if (isset($_POST['value']) && $_POST['value'] != '')
{
	$user = new user($_SESSION['id']);
	
	$client = $user->getClient();
	$mydir = canonicalize(($user->getAdmin()) ? $client->getDir() : $user->getDir());
	$mydir = ereg_replace('[\/\\]+', '/', $mydir);
	
	$value = canonicalize(utf8dec($_SESSION['myfile']));
	if ($value[strlen($value) - 1] == '/')
		$value[strlen($value) - 1] = '\0';
	
	$file = $mydir . $value;
	$file = ereg_replace('[\/\\]+', '/', $file);
	
	$handle = fopen($file, "w");
	fputs($handle, stripslashes(utf8dec($_POST['value'])));
	fclose($handle);
	
	$content = stripslashes(utf8dec($_POST['value']));
	
	$smarty->assign('nom', basename($file));
	$smarty->assign('content', htmlentites($content));
	$smarty->display('texteditor.tpl');
}
else
{
	header('Location:../ShowMessage.php?mess=security');
	die("<script type='text/javascript'>window.location = '../ShowMessage.php?mess=security';</script>");
}

function canonicalize($address) {
	$address = str_replace("\'", "'", $address);
    $address = ereg_replace('[\/\\]+', '/', $address);
    $address = explode('/', $address);
    $keys = array_keys($address, '..');
    foreach($keys as $keypos => $key) array_splice($address, $key - ($keypos * 2 + 1), 2);
    $address = implode('/', $address);
    $return = preg_replace(',([^.])\./,', '\1', $address);
    $return = str_replace('[.]+', '.', $return);
    $return = ereg_replace('[\/\\]+', '/', $return);
    return $return;
}

function utf8dec ( $s_String )
{
	$s_String = html_entity_decode(htmlentities($s_String." ", ENT_COMPAT, 'UTF-8'));
	return substr($s_String, 0, strlen($s_String)-1);
}
?>