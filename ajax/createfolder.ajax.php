<?php
session_start();
require_once('../classes/database.class.php');
require_once('../classes/client.class.php');
require_once('../classes/user.class.php');

if (!isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
{
	header('Location:../ShowMessage.php?mess=notlogged');
	die("<script type='text/javascript'>window.location = './ShowMessage.php?mess=notlogged';</script>");
}
if (!isset($_SESSION['sessiondir']) || $_SESSION['sessiondir'] == '')
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
	
	$_SESSION['sessiondir'] = canonicalize($_SESSION['sessiondir']);
	
	$mydir .= $_SESSION['sessiondir'];
	$mydir = ereg_replace('[\/\\]+', '/', $mydir);
	
	$value = canonicalize(utf8dec($_POST['value']));
	if ($value[strlen($value) - 1] == '/')
		$value[strlen($value) - 1] = '\0';
	$value = ereg_replace('[\/\\]+', '', $value);
			
	$folder = $mydir . $value;
	$folder = ereg_replace('[\/\\]+', '/', $folder);
		
	mkdir($folder);
		
	die($folder);
}
die($_POST['value']);

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

/**
 * Removes the directory and all its contents.
 *
 * @param string the directory name to remove
 * @param boolean whether to just empty the given directory, without deleting the given directory.
 * @return boolean True/False whether the directory was deleted.
 */
function deleteDirectory($dirname,$only_empty=false) {
    if (!is_dir($dirname))
        return false;
    $dscan = array(realpath($dirname));
    $darr = array();
    while (!empty($dscan)) {
        $dcur = array_pop($dscan);
        $darr[] = $dcur;
        if ($d=opendir($dcur)) {
            while ($f=readdir($d)) {
                if ($f=='.' || $f=='..')
                    continue;
                $f=$dcur.'/'.$f;
                if (is_dir($f))
                    $dscan[] = $f;
                else
                    unlink($f);
            }
            closedir($d);
        }
    }
    $i_until = ($only_empty)? 1 : 0;
    for ($i=count($darr)-1; $i>=$i_until; $i--) {
        echo "\nDeleting '".$darr[$i]."' ... ";
        if (rmdir($darr[$i]))
            echo "ok";
        else
            echo "FAIL";
    }
    return (($only_empty)? (count(scandir)<=2) : (!is_dir($dirname)));
}

function utf8dec ( $s_String )
{
	$s_String = html_entity_decode(htmlentities($s_String." ", ENT_COMPAT, 'UTF-8'));
	return substr($s_String, 0, strlen($s_String)-1);
}
?>