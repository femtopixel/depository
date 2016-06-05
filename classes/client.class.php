<?php
//error_reporting(0);
/**
 * Class Client
 *
 * Cette classe permet d'ajouter / modifier / supprimer un client en BDD
 * 
 * @package    client
 * @author     Jeremy MOULIN
 * @version    1.0
*/

Class client
{
	/**
  	* membre de ressource a la base de donnee
  	*/
	var $_db;
	/**
  	* membre de l'id du client
  	*/
	var $_id;
	/**
  	* membre de la raison du client
  	*/
	var $_raison;
	/**
  	* membre de l'etat actif du client
  	*/
	var $_actif;
	/**
  	* membre de l'uid du client
  	*/
	var $_uid;
	/**
  	* membre du gid du client
  	*/
	var $_gid;
	/**
  	* membre du repertoire de depot du client
  	*/
	var $_dir;
		
	/**
	* Fonction qui permet l'initialisation des membres de la classe a la construction
	* @param int Id du client
  	*/
	function client($id = '')
	{
		$this->_db = new database();
		$this->_id = '';
		
		if (is_numeric($id))
		{
			$sql = "SELECT * FROM T_CLIENTS WHERE client_id = '{$id}'";
			$result = $this->_db->parse($sql);
			if (count($result))
			{
				$this->_id = $result[0]['client_id'];
				$this->_raison = $result[0]['raison'];
				$this->_actif = $result[0]['actif'];
				$this->_uid = $result[0]['uid_ref'];
				$this->_gid = $result[0]['gid_ref'];
				$this->_dir = $result[0]['dir_ref'];
			}
		}
	}
	
	/**
  	* Fonction permettant de recupperer l'id du client
  	* @return int Id du client
  	*/
	function getId()
	{
		return ($this->_id);
	}
	/**
  	* Fonction permettant de recupperer la raison du client
  	* @return string raison du client
  	*/
	function getRaison()
	{
		return ($this->_raison);
	}
	/**
  	* Fonction permettant de recupperer l'etat actif du client
  	* @return bool Etat actif du client
  	*/
	function getActif()
	{
		return ($this->_actif);
	}
	/**
  	* Fonction permettant de recupperer l'uid du client
  	* @return int Uid du client
  	*/
	function getUid()
	{
		return ($this->_uid);
	}
	/**
  	* Fonction permettant de recupperer le gid du client
  	* @return int Gid du client
  	*/
	function getGid()
	{
		return ($this->_gid);
	}
	/**
  	* Fonction permettant de recupperer le repertoire du client
  	* @return string Repertoire du client
  	*/
	function getDir()
	{
		return ($this->_dir);
	}
		/**
  	* Fonction permettant de changer la raison du client
  	* @param string raison du client
  	*/
	function setRaison($value)
	{
		if ($value != '')
		{
			$this->_raison = addslashes($value);
		}
	}
	/**
  	* Fonction permettant de changer l'etat actif du client
  	* @param bool Etat actif du client
  	*/
	function setActif($value = 1)
	{
		$this->_actif = ($value == 1) ? 1 : 0;
	}
	/**
  	* Fonction permettant de changer l'uid du client
  	* @param int Uid du client
  	*/
	function setUid($value)
	{
		if (is_numeric($value))
			$this->_uid = $value;
	}
	/**
  	* Fonction permettant de changer le gid du client
  	* @param int Gid du client
  	*/
	function setGid($value)
	{
		if (is_numeric($value))
			$this->_gid = $value;
	}
	/**
  	* Fonction permettant de changer le repertoire du client
  	* @param string Repertoire du client
  	*/
	function setDir($value)
	{
		if ($value != '')
		{
			$value = str_replace('..', '', $value);
			$value = ereg_replace('[\/\\]+', '/', $value);
			$this->_dir = $value;
		}
		$this->_dir .= ($this->_dir[strlen($this->_dir) - 1] == '/') ? '././' : '/././';
	}
	/**
  	* Fonction permettant d'enregistrer les modifications du client en BDD
  	*/
	function commit()
	{
		if ($this->_dir != '')
		{
			if ($this->_id != '')
				$sql = "UPDATE T_CLIENTS SET raison = '{$this->_raison}', actif = '{$this->_actif}', uid_ref = '{$this->_uid}', gid_ref = '{$this->_gid}', dir_ref = '{$this->_dir}' WHERE client_id = '{$this->_id}'";
			else
				$sql = "INSERT INTO T_CLIENTS SET raison = '{$this->_raison}', actif = '{$this->_actif}', uid_ref = '{$this->_uid}', gid_ref = '{$this->_gid}', dir_ref = '{$this->_dir}'";
			
			$this->_db->send($sql);
			
			if ($this->_id == '')
			{
				$result = $this->_db->parse("SELECT MAX(client_id) as MAX FROM T_CLIENTS");
				$this->_id = $result[0]['MAX'];
			}
			mkdir($this->_dir);
		}
	}
}
?>