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
 * @package   Bkt_Objet
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Objet {
	/**
	 * @var array Permet de stocker les �l�ments
	 */
	private $_datas = array();
	private $_iterator = false;
	/**
	 * Constructeur
	 * @param array $array permet de stocker par d�faut des �l�ments
	 * @param bool $iterator Si TRUE, alors on pourra parcourir les �l�ments de l'objet
	 */
	public function __construct(array $array = array() , $iterator = false){
		$this->_datas    = $array;
		$this->_iterator = $iterator;
	}
	
	/**
	 * Getteur
	 * @param string $item
	 * @return mixed
	 */
	public function __get($item){
		if($this->_iterator){
			return isset($this->{$item}) ? $this->{$item} : null;
		}else{
			return isset($this->_datas[$item]) ? $this->_datas[$item] : null;
		}
	}
	/**
	 * Setteur
	 * @param string $item
	 * @param mixed $value
	 */
	public function __set($item , $value){
		$this->_datas[$item] = $value;
		if($this->_iterator){
			$this->{$item} = $value;
		}
	}
	/**
	 * Cast une valeur
	 * @param $type Cast automatique
	 * @param $valeur Valeur à caster
	 * @return variant Valeur castée
	 */
	public static function cast($type , $valeur){
		if($type === true){
			if(is_int($valeur)){
				return (int)$valeur;
			}elseif(is_numeric($valeur)){
				return (double)$valeur;
			}elseif(is_bool($valeur)){
				return (bool)$valeur;
			}elseif(is_string($valeur)){
				return (string)$valeur;
			}else{
				return $valeur;
			}
		}else{
			switch($type){
				case 'double':
					return (double)$valeur;
					break;
				case 'integer':
					return (integer)$valeur;
					break;
				case 'string':
					return (string)$valeur;
					break;
				default:
					return $valeur;
			}
		}
	}


	/**
	 * Conversion de l'objet en Array
	 * @return array
	 */
	public function toArray(){
		$output = array();
		foreach($this->_datas as $k => $v){
			$output[$k] = $this->{$k};
		}
		return $output;
	}
	
	/**
	 * Conversion de l'objet en XML
	 * @return string XML
	 */
	public function toXML(){
		$xml = "<item>";
		foreach($this->_datas as $k => $v){
			$xml.= "<$k><![CDATA[".$this->{$k}."]]></$k>";
		}
		return $xml."</item>";
	}

}