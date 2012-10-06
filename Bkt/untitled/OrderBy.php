<?php
class OrderBy {
	private $sql = ' ORDER BY ';
	public function __construct($champ , $ordre = 'ASC'){
		$this->sql.= "$champ $ordre ";
	}
	public function __toString(){
		return $this->sql;
	}
}