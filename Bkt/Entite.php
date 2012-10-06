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
 * @package   Bkt_Entite
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Entite extends Bkt_Table implements Bkt_IEntite {
	/**
	 * @var string Nom de la table
	 */
	protected $_name    = '';
	/**
	 * @var string Clé primaire
	 */
	protected $_primary = '';
	/**
	 * @var array Liste des champs de la table
	 */
	protected $_champs  = array();

	/*
	 * @var Doit on oui ou non checker la validité des données?
	 */
	protected $_validate = false;
	private   $_validateur = null;
	/**
	 * Constructeur
	 * @param $id Si non nul, Exécute une requete SELECT
	 */
	public function __construct($id = null, $name = null , $primary = null , $check = null){
		// Pour la création d'entité à la volée
		if(!is_null($name)){
			$this->_name = $name;
		}
		if(!is_null($primary)){
			$this->_primary = $primary;
		}
		$checktable = true;
		if(!is_null($id)) {
			$foo = $this->select('*' , $this->_primary.' = "'.self::escape($id).'"');
			if(count($foo) > 0){
				foreach ($foo[0] as $k => $v){
					array_push($this->_champs , $k);
					$this->$k = $v;
				}
				$checktable = false;
			}
		}
		if(!is_null($check)){
			$checktable = $check;
		}
		// Liste les champs de la table
		if($checktable){
			$foo = $this->executeS('SHOW COLUMNS FROM '.$this->_name);
			foreach ($foo as $k){
				$champ = $k->Field;
				$this->$champ = null;
			}
		}
		if($this->_validate){
			$data = $this->executeS('select column_name, column_comment from information_schema.columns where table_name="'.$this->_name.'"');
			$this->_validateur = new Bkt_Evalidateur($data);
		}
	}
	
	/**
	 * Récupéres les champs de la table
	 * @return array
	 */
	public function getChamps(){
		return $this->_champs;
	}

	/**
	 * Enregistre une ligne
	 * @param $forcer_insert
	 * @return void
	 */
	public function save($forcer_insert = false){
		$array = array();
		foreach($this->_champs as $item){
			if($item != $this->_primary || $forcer_insert){
				$array[$item] = $this->$item;
			}
		}
		if($this->_validate){
			$this->_validateur->isValid($array);
			exit;
		}
		if($this->{$this->_primary} > 0 && !$forcer_insert){
			$this->update($array , $this->{$this->_primary});
		}else{
			$last_id = $this->insert($array , $forcer_insert);
			if(!$forcer_insert){
				$this->{$this->_primary} = $last_id;
			}
		}
		return $this->{$this->_primary};
	}
	/**
	 * Carche une ligne avec un objet
	 * @param $objet Objet
	 * @return void
	 */
	public function fill($objet){
		foreach($this->_champs as $item){
			$this->{$item} = $objet->{$item};
		}
	}

	public static function getIt(){
		return new self(null , null , null , false);
	}

	public static function getInstance($id , $name , $primary){
		return new self($id , $name , $primary);
	}
	
	public function getId(){
		$primary = $this->_primary;
		return $this->$primary;
	}
	
	/**
	 * Getteur
	 * @param string $item
	 * @return mixed
	 */
	public function __get($item){
		return isset($this->{$item}) ? $this->{$item} : null;
	}
	/**
	 * Setteur
	 * @param string $item
	 * @param mixed $value
	 */
	public function __set($item , $value){
		if($item != ''){
			if(!isset($this->_champs[$item])){
				array_push($this->_champs , $item);
			}
			$this->{$item} = $value;
		}
	}
}
