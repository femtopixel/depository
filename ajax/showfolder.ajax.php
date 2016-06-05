<?php
session_start();
require_once ('../classes/database.class.php');
require_once ('../classes/client.class.php');
require_once ('../classes/user.class.php');
require_once ('../SuperAdmin.php');

$imageext = Array('JPEG', 'GIF', 'PNG', 'BMP', 'TIFF', 'JPG'); 		/* Ajouter ici des extensions image en UPPERCASE */
$textext = Array('HTM', 'HTML', 'DHTML', 'XHTML', 'XML', 'PHP', 'TXT', 'CSS', 'JS', 'VBS');	/* Ajouter ici des extensions text en UPPERCASE */

if (!isset($_SESSION['id']))
{
	header('Location:../ShowMessage.php?mess=notlogged');
	die('<script type=\'text/javascript\'>window.location = \'../ShowMessage.php?mess=notlogged\';</script>');
}
$user = new user($_SESSION['id']);

if (!isset($_POST['folder']))
	if (isset($_SESSION['sessiondir']))
		$_POST['folder'] = $_SESSION['sessiondir'];

if (isset($_POST['folder']))
{

	$_POST['folder'] = canonicalize($_POST['folder']);
	$_POST['folder'] .= ($_POST['folder'][strlen($_POST['folder']) - 1] == '/') ? '' : '/';
	
	$client = $user->getClient();
	$mydir = canonicalize(($user->getAdmin()) ? $client->getDir() : $user->getDir());
	if ($_SESSION['id'] == 0)
    $mydir = $config['superadmin']['path'];
	$dir = $mydir . $_POST['folder'];
	$dir = ereg_replace('[\/\\]+', '/', $dir);
	
	$_SESSION['sessiondir'] = canonicalize($_POST['folder']);
	if (is_dir($dir))
	{
		if ($dh = opendir($dir))
		{
			echo "<h1> &gt; ".str_replace('/', ' /', $_POST['folder'])."</h1>";
			while (($file = readdir($dh)) !== false)
			{
				$file2 = htmlentities($file);
				
				$dir2 = str_replace($mydir, '/', $dir);
				$dir2 = ereg_replace('[\/\\]+', '/', $dir2);
				
				$repared = repare($dir2.$file);
				
				if (filetype($dir . $file) == 'dir')
				{
									
					if ($file != '.' && ($dir2.$file) != '/..')
					{
						echo "<span id=\"$repared\" class='folder'><a id=\"link$repared\" href=\"javascript:void(0);\" onclick='getDir(\"$repared\");'>$file2</a>";
						if ($file != '..')
						{
							echo "&nbsp;&nbsp;";
							echo "<a href='javascript:void(0);' id=\"rename$repared\"><img src='./images/rename.gif' /></a>";
							echo "&nbsp;&nbsp;";
							echo "<a href='javascript:void(0);' id=\"delete$repared\"><img src='./images/redcross.png' /></a>";
						}
						echo "</span>";
						echo "<script type='text/javascript'>startFolderDrop(\"$repared\");</script>";
						if ($file != '..')
						{
							echo "<script type='text/javascript'>startFileDrag(\"$repared\"); activateRenameFilename(\"$repared\");  activateDeleteFilename(\"$repared\");</script>";
						}
					}	
					else if ($file == '.')
						echo "<a href=\"javascript:void(0);\" onclick='getDir(\"$repared\");'>Rafraichir le dossier</a><br/>";
				}
				else
				{
					$size = formatbytes(filesize("$dir$file"));
					echo "<span id=\"$repared\" class='file'><span id=\"link$repared\">" . $file2 . '</span>' . " ($size)";
					echo "&nbsp;&nbsp;";
					echo "<a href='javascript:void(0);' id=\"rename$repared\"><img src='./images/rename.gif' /></a>";
					
					$ext = explode('.', strrev($file));
					$ext = strtoupper(strrev($ext[0]));
													
					if (in_array($ext, $imageext))
					{
						echo "&nbsp;&nbsp;";
						echo "<a href='javascript:void(0);' id=\"show$repared\" title=\"$file2\"><img src='./images/image.gif' /></a>";
					}
					if (in_array($ext, $textext))
					{
						echo "&nbsp;&nbsp;";
						echo "<a href='javascript:void(0);' id=\"edit$repared\" ><img src='./images/edit.png' /></a>";
					}
					
					echo "&nbsp;&nbsp;";
					echo "<a href='javascript:void(0);' id=\"save$repared\"><img src='./images/floppy.gif' /></a>";
					echo "&nbsp;&nbsp;";
					echo "<a href='javascript:void(0);' id=\"delete$repared\"><img src='./images/redcross.png' /></a>";
					echo '<br/></span>';
					echo "<script type='text/javascript'>startFileDrag(\"$repared\"); activateRenameFilename(\"$repared\"); activateSaveFilename(\"$repared\"); activateDeleteFilename(\"$repared\"); ";
					if (in_array($ext, $imageext))
						echo "activateShowFilename(\"$repared\"); ";
					if (in_array($ext, $textext))
						echo "activateEditFilename(\"$repared\"); ";
					echo "</script>";
				}
			}
			closedir($dh);
		}
		echo "<input type='hidden' value='{$_POST['folder']}' id='mydir' />";
	}
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


function    formatbytes($val, $digits = 3, $mode = "SI", $bB = "o"){ //$mode == "SI"|"IEC", $bB == "b"|"B"
        $si = array("", "k", "M", "G", "T", "P", "E", "Z", "Y");
        $iec = array("", "Ki", "Mi", "Gi", "Ti", "Pi", "Ei", "Zi", "Yi");
        switch(strtoupper($mode)) {
            case "SI" : $factor = 1000; $symbols = $si; break;
            case "IEC" : $factor = 1024; $symbols = $iec; break;
            default : $factor = 1000; $symbols = $si; break;
        }
        switch($bB) {
            case "b" : $val *= 8; break;
            default : $bB = "o"; break;
        }
        for($i=0;$i<count($symbols)-1 && $val>=$factor;$i++)
            $val /= $factor;
        $p = strpos($val, ".");
        if($p !== false && $p > $digits) $val = round($val);
        elseif($p !== false) $val = round($val, $digits-$p);
        return round($val, $digits) . " " . $symbols[$i] . $bB;
    }
    
function repare($str){
	return (rawurlencode(str_replace('&amp;', '&', $str)));
}
?>