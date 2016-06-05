<?php
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
	private $_db;
		
	/**
	* Fonction qui permet l'initialisation à la base de données
  	* @return Rien
  	*/
	public function open_db_cnx()
	{
		$this->_db = mysql_connect($config['database']['url'], $config['database']['login'], $config['database']['password'], NULL, 65536);
		mysql_select_db($config['database']['database'], $this->_db);
	}
	
	/**
	* Fonction qui permet la cloture de la connexion à la base de données
  	* @return Rien
  	*/
	public function close_db_cnx()
	{
		mysql_close($this->_db);
	}
	
	
	/**
  	* Fonction parse qui permet de recuperer un tableau contenant les informations demandées par la requete
  	* @param string la requete a envoyer a la BDD
  	* @return Array Tableau contenant les informations demandées
  	*/
	function parse($sql)
	{
		if ($sql != "")
		{	
			//echo $sql."<br/><br/>";
			$this->open_db_cnx();
			$raw = mysql_query($sql, $this->_db) or die (mysql_error($this->_db));
			$result = Array();
			if (!empty($raw))
			{
				while ($row = mysql_fetch_array($raw))
				{
					array_push($result, $row);
				}
				mysql_free_result($raw);
			}
			$this->close_db_cnx();		
			return ($result);
		}
	}
	
	/**
  	* Fonction permettant d'envoyer une requete a la base de donnee sans retour (comme les delete ou les update)
  	* @param string la requete a envoyer
  	* @return bool Booleen true si la requete est bien passé false sinon
  	*/
	function send($sql)
	{
		if ($sql != "")
		{
			$this->open_db_cnx();
			//echo ($sql . "<br/><br/>");
			$res = mysql_query($sql, $this->_db) or die(mysql_error($this->_db));
			$this->close_db_cnx();
			return ($res);
		}
	}
}
?>