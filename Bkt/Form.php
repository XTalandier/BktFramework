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
 * @package   Bkt_Form
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Form {
	public  $_nom         = '';
	private $_validateurs = array();
	public  $value        = '';
	/**
	 * Constructeur
	 * @param string $nom        Nom du formulaire
	 * @param array $validateurs Liste des validateurs 
	 */
	public function __construct($nom , $validateurs = array()){
		$this->_nom         = $nom;
		$this->_validateurs = $validateurs;
		$this->value        = self::get($nom);
	}
	
	/**
	 * Valide le formulaire avec les validateurs passés par le constructeur
	 * @return bool
	 */
	public function isValid(){
		$valeur = self::get($this->_nom);
		$erreurs = array();
		foreach($this->_validateurs as $validateur){
			$valid = call_user_func_array('Bkt_Validateur::validateur_'.$validateur , array($valeur));
			if($valid !== true){
				array_push($erreurs , $valid);
			}
		}
		if(count($erreurs) == 0){
			return true;
		}
		return $erreurs;
	}
	
	/**
	 * Retourne un élément du formulaire GET ou POST
	 * @param string $item 
	 * @param mixed  $defaut
	 * @return mixed
	 */
	public static function get($item , $defaut = '' , $escape = true){
		if(isset($_GET[$item])){
			return $escape ? Bkt_Table::escape($_GET[$item]) : $_GET[$item];
		}elseif(isset($_POST[$item])){
			return $escape ? Bkt_Table::escape($_POST[$item]) : $_POST[$item];
		}elseif(isset($_FILES[$item])){
			return $_FILES[$item];
		}else{
			return $defaut;
		}
	}
	
	
	public function getFile($item){
		if(isset($_FILES[$item])){
			return $_FILES[$item];
		}else{
			return false;
		}
	}
	
	public function getFiles($item){
		if(isset($_FILES[$item])){
			$files = array();
			$n = count($_FILES[$item]['name']);
			for($i = 0; $i < $n ; $i++){
				$file = array();
				foreach ($_FILES[$item] as $k => $v){
					$file[$k] = $_FILES[$item][$k][$i];
				}
				array_push($files, new Bkt_Objet($file));
			}
			return $files;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Retourne un élément du formulaire GET ou POST
	 * @param string $item 
	 * @param mixed  $defaut
	 * @return mixed
	 */
	public static function set($item , $value , $method = 'get'){
		if(strtolower($method) == 'get'){
			$_GET[$item] = $value;
		}else{
			$_POST[$item] = $value;
		}
	}
	public function __toString(){
		return self::get($this->_nom);
	}
}