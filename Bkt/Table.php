<?php
/**
 * BKT
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to xavier.talandier@gmail.com so we can send you a copy immediately.
 *
 * @category  Bkt
 * @package   Bkt_Table
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Table extends Bkt_Db {
	protected $_name    = '';
	protected $_primary = '';
	protected $_count   = 0;

	public function __construct($name = null , $primary = null){
		if(!is_null($name)){
			$this->_name = $name;
		}
		if(!is_null($primary)){
			$this->_primary = $primary;
		}
	}
	
	/**
	 * Selectionne des enregistrements
	 * @param  array $champs champs é selectionner
	 * @return array 
	 */
	public function select($champs = '*' , $where = '' , $orderby = '' , $limit = ''){
		if(!$champs instanceof Bkt_Query){
			$sql = "SELECT ".self::array2string($champs)." FROM $this->_name".
			($where == '' ? '' : ' WHERE '.$where).
			($orderby == '' ? '' : ' ORDER BY '.$orderby).
			($limit == '' ? '' : ' LIMIT '.$limit);
		}
		return $this->executeS($sql);
	}

	/**
	 * Insére un enregistrement
	 * @param array $data tableau champs => valeur
	 */
	public function insert($data , $withprimary = false){
		$champs  = array();
		$valeurs = array();
		foreach($data as $k => $v){
			array_push($champs  , $k);
			array_push($valeurs , self::escape($v));
		}
		$sql = "INSERT INTO $this->_name (".self::array2string($champs , '`').") VALUES (".self::array2string($valeurs , '"').")";
		return $this->execute($sql);
	}
	
	public function delete($id){
		$sql = "DELETE FROM $this->_name WHERE $this->_primary = '".mysql_escape_string($id)."'";
		//echo $sql;
		$this->execute($sql);
	}
	
	/**
	 * Teste si une entrée est unique ou non dans la table
	 * @param string $champ
	 * @param mixed $valeur
	 */
	public function isUnique($champ , $valeur){
		$foo = $this->select($this->_primary , new Where($champ.' = "'.self::escape($valeur).'"'));
		return (count($foo) == 0);
	}
	
	/**
	 * Met é jours un ou des enregistrements
	 * @param array $data tableau champs => valeur
	 * @param mixed $id   Valeur de la clé primaire pour le update
	 */
	public function update($data , $id = ''){
		$datas = array();
		foreach($data as $k => $v){
			if(self::escape($v) == ''){
				array_push($datas , '`'.$k.'` = null');
			}else{
				array_push($datas , '`'.$k.'` = "'.self::escape($v).'"');
			}
		}
		$sql = "UPDATE $this->_name SET ".self::array2string($datas).($id == '' ? '' : (new Where($this->_primary.'="'.$id.'"')));
		$this->execute($sql);
	}

	/**
	 * Exécute une requéte et retoune les enregistrements associées
	 * @param string $sql Requéte SQL
	 * @return array
	 */
	public function executeS($sql , $juste1value = false , $count = false){
		Bkt_Console::info($sql);
		$res = mysql_query($sql);
		if($count){
			$foo = mysql_query('SELECT FOUND_ROWS() as nb;');
			$aa  = mysql_fetch_assoc($foo);
			$this->_count = $aa['nb'];
		}
		$ret = array();
		if (!$res){
			echo $sql."<hr>".mysql_error();
			Bkt_Console::erreur('Erreur de requéte SQL:'.$sql.'<br />' , array(
				'numero'  => mysql_errno() ,
				'message' => mysql_error()
			));
			throw new Bkt_Exeption('Erreur dans la requete SQL :'.$sql);
			return array();
		}
		while (($ligne = mysql_fetch_assoc($res)) !== false) {
			$foo = new Bkt_Objet(array() , true);
			foreach ($ligne as $k => $v){
				$foo->$k = $v;
			}
			array_push($ret , $foo);
		}
		if(count($ret) >= 1 && $juste1value !== false){
			$ret = $ret[0]->$juste1value ;
		}
		mysql_free_result($res);
		return $ret;
	}
	
	public function getCount(){
		return $this->_count;
	}

	/**
	 * Exécute une requéte
	 * @param string $sql Requéte SQL
	 */
	public function execute($sql){
		Bkt_Console::info($sql);
		//echo "\n\n\n\nSQL: $sql\n\n\n";
		$res = mysql_query($sql);
		//echo 'ici';
		//exit;
		if (!$res){
			Bkt_Console::erreur('Erreur de requéte SQL:'.$sql.'<br />' , array(
				'numero'  => mysql_errno() ,
				'message' => mysql_error()
			));
			throw new Bkt_Exeption('Erreur dans la requete SQL :'.$sql);
		}
		return mysql_insert_id(self::$_link);
	}
	
	public function count(){
		$foo = $this->executeS('SELECT FOUND_ROWS() as nb;');
		return $foo[0]->nb;
	}

	public function __toString(){
		return $this->_name;
	}
	
	/**
	 * Linéarise un tableau
	 * @param array $array   Tableau é linéariser
	 * @param string $escape Entoure les champs
	 */
	private static function array2string($array , $escape = ''){
		if(!is_array($array)){
			return $array;
		}
		$ret = '';
		$n   = count($array);
		for($i = 0 ; $i < $n ; $i++){
			$ret.= $array[$i] == '' ? 'null, ' :$escape.$array[$i].$escape.', ';
		}
		return substr($ret , 0 , strlen($ret) - 2);
	}

	/**
	 * Echappe une chaine de caractéres
	 * @param string $str Chaine é échapper
	 * @return string 
	 */
	public static function escape($str){
		return trim(mysql_escape_string(strip_tags($str)));
	}
	public static function unescape($str){
		$strs = array('\r' => "\r" , '\n' => "\n" , '\'' , "'" , '\"' , '"');
		foreach ($strs as $k => $v){
			$str = str_replace($k, $v, $str);
		}
		return trim($str);
	}
	
	public static function getInstance($name = null , $primary = null){
		return new self($name , $primary);
	}
}