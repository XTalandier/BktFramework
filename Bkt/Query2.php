<?php
class Bkt_Query2 {
	private $_count   = false;
	private $_champs  = '';
	private $_table   = '';
	private $_join    = '';
	private $_where   = '';
	private $_limit   = '';
	private $_orderby = '';
	
	public static function getInstance($count = false){
		return new Bkt_Query2($count);
	}

	public function __construct($count = false){
		$this->_count = $count;
	}
	/**
	 * SELECT
	 * @param variant $params
	 */
	public function Select($params){
		$strSelect = '';
		// Select(champ1 , champ2 , champ3, ...);
		if(func_num_args() > 1){
			$params = func_get_args();
		}
		$n = count($params);
		for($i = 0 ; $i < $n ; $i++){
			if(is_array($params[$i])){
				// Dans le cas ou: table.champ as champ
				for($j = 0; $j < count($params[$i]) - 1 ; $j++){
					$strSelect.= '`'.$params[$i][$j].'`.';
				}
				$strSelect.= trim($strSelect , '.').' as `'.$params[$i][count($params[$i]) - 1].'`,';
			}else{
				$strSelect.= '`'.$params[$i].'`,';
			}
		}
		$this->_champs .= trim($strSelect , ',');
		return $this;
		//echo trim($strSelect , ',');
	}
	
	/**
	 * FROM
	 * @param variant $params
	 */
	public function From($params){
		if(is_array($params)){
			$this->_table .= '`'.$params[0].'` as `'.$params[1].'`';
		}else{
			$this->_table .= "`$params`";
		}
		return $this;
	}
	/**
	 * Jointures
	 * @param array  $table
	 * @param string $condition
	 * @param string $jointure
	 */
	public function Join($table , $condition , $jointure = ''){
		$this->_join .= $jointure.' JOIN `'.$table[0].'` '.$table[1].' ON ( '.$condition.' )';
		return $this;
	}
	public function LeftJoin($table , $condition){
		$this->Join($table, $condition , 'LEFT');
		return $this;
	}
	public function InnerJoin($table , $condition){
		$this->Join($table, $condition , 'INNER');
		return $this;
	}
	public function NaturalJoin($table , $condition){
		$this->Join($table, $condition , 'NATURAL');
		return $this;
	}
	
	public function Where($conditions){
		$this->_where.= trim(implode(' AND ', $conditions), 'AND ');
		return $this;
	}
	
	public function Limit($debut = 0 , $nb = 10){
		$this->_limit = trim("$debut , $nb" , ', ');
		return $this;
	}

	public function OrderBy($champ , $direction = 'ASC'){
		$this->_orderby = "$champ $direction";
		return $this;
	}
	
	
	public function __toString(){
		return "
SELECT ".($this->_count ? 'SQL_CALC_FOUND_ROWS' : '')." ".str_replace('`*`', '*', $this->_champs)."
FROM  $this->_table
$this->_join
".($this->_where   == '' ? '' : "WHERE $this->_where")."
".($this->_orderby == '' ? '' : "ORDER BY $this->_orderby")."
".($this->_limit   == '' ? '' : "LIMIT $this->_limit")."

";
	}
}

