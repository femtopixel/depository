<?php
//error_reporting(0);
/**
 * Class User
 *
 * Cette classe permet d'ajouter / modifier / supprimer un utilisateur d'un en BDD
 * 
 * @package    utilisateur
 * @author     Jeremy MOULIN
 * @version    1.0
*/

Class User
{
	/**
  	* membre de ressource a la base de donnee
  	*/
	var $_db;
	/**
  	* membre de l'id de l'utilisateur
  	*/
	var $_id;
	/**
  	* membre du login de l'utilisateur
  	*/
	var $_login;
	/**
  	* membre du mot de passe de l'utilisateur
  	*/
	var $_password;
	/**
  	* membre de l'objet client de l'utilisateur
  	*/
	var $_client;
	/**
  	* membre de l'id client de l'utilisateur
  	*/
	var $_client_id;
	/**
  	* membre de l'etat admin de l'utilisateur
  	*/
	var $_admin;
	/**
  	* membre de l'etat actif de l'utilisateur
  	*/
	var $_actif;
	/**
  	* membre du repertoire de depot de l'utilisateur
  	*/
	var $_dir;
		
	/**
	* Fonction qui permet l'initialisation des membres de la classe a la construction
	* @param int Id de l'utilisateur
  	*/
	function user($id = '')
	{
		$this->_db = new database();
		$this->_id = '';
		$this->_client = new client();
		
		if (is_numeric($id))
		{
			$sql = "SELECT * FROM FTP_USERS WHERE user_id = '{$id}'";
			$result = $this->_db->parse($sql);
			if (count($result))
			{
				$this->_id = $result[0]['user_id'];
				$this->_login = $result[0]['user'];
				$this->_password = $result[0]['password'];
				$this->_client_id = $result[0]['client_id'];
				$this->_client = new client($result[0]['client_id']);
				$this->_admin = $result[0]['admin'];
				if ($this->_admin)
					$this->_dir = $this->_client->getDir();
				else
					$this->_dir = substr($this->_client->getDir(), 0, strlen($this->_client->getDir()) - 4) . $this->_login . '/';
				$this->_actif = $result[0]['actif'];
			}
		}
	}
	
	/**
  	* Fonction permettant de recupperer l'id de l'utilisateur
  	* @return int Id de l'utilisateur
  	*/
	function getId()
	{
		return ($this->_id);
	}
	/**
  	* Fonction permettant de recupperer le login de l'utilisateur
  	* @return string login de l'utilisateur
  	*/
	function getLogin()
	{
		return ($this->_login);
	}
	/**
  	* Fonction permettant de recupperer l'etat actif de l'utilisateur
  	* @return bool Etat actif de l'utilisateur
  	*/
	function getActif()
	{
		return ($this->_actif);
	}
	/**
  	* Fonction permettant de recupperer l'etat admin de l'utilisateur
  	* @return bool Etat admin de l'utilisateur
  	*/
	function getAdmin()
	{
		return ($this->_admin);
	}
	/**
  	* Fonction permettant de recupperer le client de l'utilisateur
  	* @return objet Objet Client de l'utilisateur
  	*/
	function getClient()
	{
		return ($this->_client);
	}
	/**
  	* Fonction permettant de recupperer le repertoire de l'utilisateur
  	* @return string Repertoire de l'utilisateur
  	*/
	function getDir()
	{
		return (substr($this->_dir, 0, strlen($this->_dir) - 4));
	}
	
	/**
  	* Fonction permettant de changer la raison de l'utilisateur
  	* @param string raison de l'utilisateur
  	*/
	function setLogin($value)
	{
		if ($value != '')
		{
			$value = str_replace('/', '', $value);
			$value = str_replace('\\', '', $value);
			$this->_login = str_replace('\'', '\\\'', $value);
			
			if (!is_numeric($this->_id))
			{
        $res = $this->_db->parse("SELECT * FROM FTP_USERS WHERE user = '{$this->_login}'");
        
        if (count($res))
          $this->_login .= count($res) + 1;
			}
			
			if ($this->_admin)
				$this->_dir = $this->_client->getDir();
			else
				$this->_dir = substr($this->_client->getDir(), 0, strlen($this->_client->getDir()) - 4) . $this->_login . '/';
		}
	}
	/**
  	* Fonction permettant de changer l'etat actif de l'utilisateur
  	* @param bool Etat actif de l'utilisateur
  	*/
	function setActif($value = 1)
	{
		$this->_actif = ($value == 1) ? 1 : 0;
	}
	/**
  	* Fonction permettant de changer l'etat admin de l'utilisateur
  	* @param bool Etat admin de l'utilisateur
  	*/
	function setAdmin($value = 1)
	{
		$this->_admin = ($value == 1) ? 1 : 0;
		$this->setLogin($this->_login);
	}
	
	/**
  	* Fonction permettant de changer l'etat admin de l'utilisateur
  	* @param bool Etat admin de l'utilisateur
  	*/
	function setPassword($value)
	{
		if ($value != '')
		{
			$value = str_replace('\\\'', '\'', $value);
			$this->_password = md5($value);
		}
	}
	
	/**
  	* Fonction permettant de changer l'etat admin de l'utilisateur
  	* @param bool Etat admin de l'utilisateur
  	*/
	function setClientId($value)
	{
		if (is_numeric($value))
		{
			$this->_client_id = $value;
			$this->_client = new client($value);
			$this->setLogin($this->_login);
		}
	}
	
	/**
  	* Fonction permettant d'enregistrer les modifications de l'utilisateur en BDD
  	*/
	function commit()
	{
		if ($this->_login != '')
		{
			if ($this->_id != '')
				$sql = "UPDATE FTP_USERS SET user = '{$this->_login}', actif = '{$this->_actif}', client_id = '{$this->_client_id}', admin = '{$this->_admin}', dir = '{$this->_dir}', password = '{$this->_password}' WHERE user_id = '{$this->_id}'";
			else
				$sql = "INSERT INTO FTP_USERS SET user = '{$this->_login}', actif = '{$this->_actif}', client_id = '{$this->_client_id}', admin = '{$this->_admin}', dir = '{$this->_dir}', password = '{$this->_password}'";
			
			$this->_db->send($sql);
			
			if ($this->_id == '')
			{
				$result = $this->_db->parse("SELECT MAX(user_id) as MAX FROM FTP_USERS");
				$this->_id = $result[0]['MAX'];
			}
			
			if (!$this->_admin)
			{
				mkdir(substr($this->_dir, 0, strlen($this->_dir) - 4));
			}
		}
	}
	
	/**
  	* Fonction permettant de connecter un utilisateur
  	* @param string Login du user
  	* @param string Password du user
  	* @return bool 1 si ca a marche 0 sinon
  	*/
	function Connect($login, $password)
	{
		$password = str_replace('\\\'', '\'', $password);
		$password = str_replace('\'', '\\\'', $password);
		$password = md5($password);
		$login = str_replace('\\\'', '\'', $login);
		$login = str_replace('\'', '\\\'', $login);
		$result = $this->_db->parse("SELECT * FROM FTP_USERS fu, T_CLIENTS tc WHERE fu.client_id = tc.client_id AND fu.user = '$login' AND fu.password = '$password' AND fu.actif = '1' AND tc.actif = '1'");
		if (count($result))
		{
			$this->_id = $result[0]['user_id'];
			$this->_login = $result[0]['user'];
			$this->_password = $result[0]['password'];
			$this->_client_id = $result[0]['client_id'];
			$this->_client = new client($result[0]['client_id']);
			$this->_dir = $this->_client->getDir() . $this->_login . '/';
			$this->_admin = $result[0]['admin'];
			$this->_actif = $result[0]['actif'];
			return (1);
		}
		return (0);
	}
}
?>