<?php
session_start();
require_once('../classes/database.class.php');
require_once('../classes/client.class.php');
require_once('../classes/user.class.php');
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

$user = new user($_SESSION['id']);
$client = $user->getClient();
$mydir = canonicalize(($user->getAdmin()) ? $client->getDir() : $user->getDir());
$mydir .= canonicalize($_SESSION['sessiondir']);
$mydir = ereg_replace('[\/\\]+', '/', $mydir);
if (isset($_FILES['file']) && !$_FILES['file']['error'])
{
	move_uploaded_file($_FILES['file']['tmp_name'], str_replace('//', '/', $mydir . '/' . $_FILES['file']['name']));
}
header('Location:../Work.php?dir='.rawurlencode(canonicalize($_SESSION['sessiondir'])));
die("<script type='text/javascript'>window.location = '../Work.php?dir=".rawurlencode(canonicalize($_SESSION['sessiondir']))."';</script>");

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

function dircopy($srcdir, $dstdir, $verbose = false) {
  $num = 0;
  if(!is_dir($dstdir)) mkdir($dstdir);
  if($curdir = opendir($srcdir)) {
    while($file = readdir($curdir)) {
      if($file != '.' && $file != '..') {
        $srcfile = $srcdir . '\\' . $file;
        $dstfile = $dstdir . '\\' . $file;
        if(is_file($srcfile)) {
          if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;
          if($ow > 0) {
            if($verbose) echo "Copying '$srcfile' to '$dstfile'...";
            if(copy($srcfile, $dstfile)) {
              touch($dstfile, filemtime($srcfile)); $num++;
              if($verbose) echo "OK\n";
            }
            else echo "Error: File '$srcfile' could not be copied!\n";
          }                  
        }
        else if(is_dir($srcfile)) {
          $num += dircopy($srcfile, $dstfile, $verbose);
        }
      }
    }
    closedir($curdir);
  }
  return $num;
}
?>