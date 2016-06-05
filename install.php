<?php
session_start();
require_once('./library/Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = './templates';
$smarty->compile_dir = './templates/compile';

$required = 1;
$securityArray = Array('bddurl',
					   'bddlogin',
					   'bddpassword',
					   'bddname',
					   'superadminlogin',
					   'superadminpassword',
					   'superadminmail');

foreach($securityArray as $value)
{
	if (!isset($_POST[$value]))
		$required = 0;
}

if ($required)
{
	$db = mysql_connect($_POST['bddurl'], $_POST['bddlogin'], $_POST['bddpassword'], NULL, 65536) or die('Erreur de connexion a la base de donnee');
	mysql_select_db($_POST['bddname'], $db) or die (mysql_error($db));
	
	$sql1 = "CREATE TABLE `FTP_USERS` (
  `user_id` bigint(20) NOT NULL auto_increment,
  `user` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `dir` varchar(255) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$sql2 = "CREATE TABLE `T_CLIENTS` (
  `client_id` bigint(20) NOT NULL auto_increment,
  `raison` mediumtext NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `uid_ref` bigint(20) NOT NULL,
  `gid_ref` bigint(20) NOT NULL,
  `dir_ref` varchar(255) NOT NULL,
  PRIMARY KEY  (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

	mysql_query($sql1, $db) or die (mysql_error($db));
	mysql_query($sql2, $db) or die (mysql_error($db));
	
	$file = fopen(getcwd().'/SuperAdmin.php', 'w');
	
	$_POST['superadminlogin'] = str_replace('"', '\"', stripslashes($_POST['superadminlogin']));
	$_POST['superadminpassword'] = str_replace('"', '\"', stripslashes($_POST['superadminpassword']));
	$_POST['superadminmail'] = str_replace('"', '\"', stripslashes($_POST['superadminmail']));
	
	$infile = "<?php
	".'$'."config['superadmin']['login'] = \"{$_POST['superadminlogin']}\";			/* login du superadmin */
	".'$'."config['superadmin']['password'] = \"{$_POST['superadminpassword']}\";		/* password du superadmin */
	".'$'."config['superadmin']['mail'] = \"{$_POST['superadminmail']}\";	/* mail du superadmin */
	".'$'."config['superadmin']['path'] = getcwd().'/../../';	/* path du superadmin */
?>";
	fputs($file, $infile);
	fclose($file);
	
	$file = fopen(getcwd().'/classes/database.class.php', 'w');
	$_POST['bddurl'] = str_replace('"', '\"', stripslashes($_POST['bddurl']));
	$_POST['bddlogin'] = str_replace('"', '\"', stripslashes($_POST['bddlogin']));
	$_POST['bddpassword'] = str_replace('"', '\"', stripslashes($_POST['bddpassword']));
	$_POST['bddname'] = str_replace('"', '\"', stripslashes($_POST['bddname']));
	
	$infile = "<?php
//error_reporting(0);

/**
 * Class Database
 *
 * Cette classe permet d'envoyer des requetes et recevoir les informations retournee par raport a la BDD en MySql
 * 
 * @package    database
 * @author     Jeremy MOULIN
 * @version    1.0
*/

Class database
{
	/**
  	* membre de ressource a la base de donnee
  	*/
	var ".'$'."_db;
		
	/**
	* Fonction qui permet l'initialisation à la base de données
  	* @return Rien
  	*/
	function open_db_cnx()
	{".'$'."this->_db = mysql_connect(\"{$_POST['bddurl']}\", \"{$_POST['bddlogin']}\", \"{$_POST['bddpassword']}\", NULL, 65536);
		mysql_select_db(\"{$_POST['bddname']}\", ".'$'."this->_db);
	}
	
	/**
	* Fonction qui permet la cloture de la connexion à la base de données
  	* @return Rien
  	*/
	function close_db_cnx()
	{
		mysql_close(".'$'."this->_db);
	}
		
	/**
  	* Fonction parse qui permet de recuperer un tableau contenant les informations demandées par la requete
  	* @param string la requete a envoyer a la BDD
  	* @return Array Tableau contenant les informations demandées
  	*/
	function parse(".'$'."sql)
	{
		if (".'$'."sql != \"\")
		{	
			//echo ".'$'."sql.\"<br/><br/>\";
			".'$'."this->open_db_cnx();
			".'$'."raw = mysql_query(".'$'."sql, ".'$'."this->_db) or die (mysql_error(".'$'."this->_db));
			".'$'."result = Array();
			if (!empty(".'$'."raw))
			{
				while (".'$'."row = mysql_fetch_array(".'$'."raw))
				{
					array_push(".'$'."result, ".'$'."row);
				}
				mysql_free_result(".'$'."raw);
			}
			".'$'."this->close_db_cnx();		
			return (".'$'."result);
		}
	}
	
	/**
  	* Fonction permettant d'envoyer une requete a la base de donnee sans retour (comme les delete ou les update)
  	* @param string la requete a envoyer
  	* @return bool Booleen true si la requete est bien passé false sinon
  	*/
	function send(".'$'."sql)
	{
		if (".'$'."sql != \"\")
		{
			".'$'."this->open_db_cnx();
			//echo (".'$'."sql . \"<br/><br/>\");
			".'$'."res = mysql_query(".'$'."sql, ".'$'."this->_db) or die(mysql_error(".'$'."this->_db));
			".'$'."this->close_db_cnx();
			return (".'$'."res);
		}
	}
}
?>";
	fputs($file, $infile);
	fclose($file);
	unlink(getcwd().'/install.php');
	$_SESSION['id'] = 0;
	$_SESSION['super_admin'] = 1;
	$ok = 1;
}
else
	$ok = 0;

$smarty->assign('ok', $ok);
$smarty->display('install.tpl');

require_once('foot.php');
?>
