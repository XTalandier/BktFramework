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
 * @package   Bkt_Query
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Query {
	private $_query = '';
	
	public function __construct(){
		$this->_query = '';
	}
	/**
	 * Retourne la requete SQL
	 * @return string SQL
	 */
	public function __toString(){
		return $this->_query;
	}
	/**
	 * Retourne une instance de l'objet
	 * @return Bkt_Query Instance de l'objet
	 */
	public static function getInstance(){
		return new Bkt_Query();
	}
	
	/**
	 * Clause SELECT
	 * @param mixed $champs
	 */
	public function Select($champs = '*'){
		$query = ' SELECT ';
		if(is_array($champs)){
			$n = count($champs);
			for($i = 0 ; $i < $n ; $i++){
				//SELECT table1.champs1 , table2.champs5 , ...
				if(is_array($champs[$i])){
					$query.= '`'.$champs[$i][0].'`.`'.$champs[$i][0].'` , ';
				// SELECT champs1 , champs2, ...
				}else{
					$query.= '`'.$champs[$i].'` , ';
				}
			}
			$this->_query.= substr($query , 0 , strlen($query) - 3);
		// SELECT champs...
		}else{
			$this->_query.= $query." $champs ";
		}
		return $this;
	}
	/**
	 * Clause FROM
	 * @param mixed $tables
	 */
	public function From($tables){
		$query = ' FROM ';
		if(count(func_num_args()) > 1 || is_array($tables)){
			$n = func_num_args();
			for($i = 0 ; $i < $n ; $i++){
				$args = func_get_args();
				$query.= '`'. $args[$i][0].'` '.(isset($args[$i][1]) ? $args[$i][1] : '').' , ';
			}
			$this->_query.= substr($query , 0 , strlen($query) - 3);
		}else{
			$this->_query.= $query." $tables ";
		}
		return $this;
	}
	
	/**
	 * Clause WHERE
	 * @param mixed $items
	 */
	public function Where($items){
		$query = ' WHERE ';
		if(is_array($items)){
			foreach($items as $item => $v){
				$query.= "`$item` $v[0] ".((isset($v[2]) && $v[2]) || (!isset($v[2]) ) ? '"'.mysql_escape_string($v[1]).'"' : $v[1])." AND ";
			}
			$this->_query.= substr($query , 0 , strlen($query) - 4);
		}else{
			$this->_query.= "$query $items ";
		}
		return $this;
	}
	
	public function LeftJoin($table , $cond){
		$query = ' LEFT JOIN  ';
		if(is_array($table)){
			$query.= "`$table[0]` $table[1] ";
		}else{
			$query.= "`$table[0]` ";
		}
		$query.= " ON ($cond) ";
		$this->_query.= $query;
		return $this;
	}
	
	public function Limit($offset , $count = ''){
		$this->_query.= ' LIMIT '.$offset.($count == '' ? '' : " , $count ");
		return $this;
	}
	
	public function Count($as = 'nb'){
		$this->_query = preg_replace('/SELECT (.*) FROM /i', 'SELECT COUNT(*) as '.$as.' FROM ', $this->_query);
		return $this;
	}

	public function OrderBy($champ , $direction = 'ASC'){
		if(is_array($champ)){
			$this->_query.= " ORDER BY `$champ[0]`.`$champ[1]` $direction ";
		}else{
			$this->_query.= " ORDER BY `$champ` $direction ";
		}
		return $this;
	}
	public function OrderByRAND(){
		$this->_query.= " ORDER BY RAND() ";
		return $this;
	}
	
	public function GroupBy($champ){
		$this->_query.= " GROUP BY $champ ";
		return $this;
	}
	
	/**
	 * Update statement
	 * @param string $table Table to update
	 */
	public function Update($table){
		$this->_query = "UPDATE $table ";
		return $this;
	}
	
	/**
	 * Set for updating
	 * @param array|string $champs Champs à mettre à jours
	 */
	public function SET($champs){
		if(is_array($champs)){
			$this->_query .= " SET ";
			$n = count($champs);
			for($i = 0 ; $i < $n ; $i++){
				$this->_query .= " $champs[$i],";
			}
			$this->_query = trim($this->_query , ',');
		}else{
			$this->_query .= " SET $champs ";
		}
		return $this;
	}
	
	/**
	 * Execute the query
	 * @param int $elem Element to return. null: all the query, int: The $ELEM element
	 * @return Array
	 */
	public function exec($elem = null){
		$ret = Bkt_Table::getInstance()->executeS($this);
		return is_null($elem) ? $ret : $ret[$elem];
	}
	/**
	 * Execute the query
	 * @param int $elem Element to return. null: all the query, int: The $ELEM element
	 * @return bool
	 */
	public function save(){
		return Bkt_Table::getInstance()->execute($this);
	}


}

