<?php
class Where {
	private $sql = ' WHERE ';
	public function __construct($where){
		$this->sql.= $where;
	}

	public function __toString(){
		return $this->sql;
	}
}