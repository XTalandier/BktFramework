<?php
class Limit {
	private $sql = ' LIMIT ';
	public function __construct($offset , $nombre = 0){
		$this->sql.= "$offset ".($nombre > 0 ? ','.$nombre : '');
	}
	public function __toString(){
		return $this->sql;
	}
}