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
 * @package   Bkt_Template
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Template {

	public static $_type = 'normal'; // OR SMARTY
	
	/**
	 * @var $_fichier string Nom du fichier template
	 */
	private $_fichier = null;
	/**
	 * @var $_html string Contenue du template apr�s compilation
	 */
	private $_html    = '';
	/**
	 * @var $_datas array Param�tres
	 */
	private $_datas   = array();
	
	/**
	 * Constructeur
	 * @param string $fichier Nom du fichier � utiliser
	 */
	public function __construct($fichier){
		$this->_fichier = $fichier;
	}
	
	public static function getInstance($fichier){
		return new Bkt_Template(($fichier));
	}

	/**
	 * Conversion de l'objet en String: Compile le template
	 * @return string HTML
	 */
	public function getString(){
		if(self::$_type == 'smarty'){
			$smarty = Bkt_Smarty::getInstance($this->_fichier);
			foreach ($this->_datas as $k => $v ) {
				$smarty-> $k = $v;
			}
			return $smarty->getHTML();
		}else{
			$aa = new Bkt_Stream();
			ob_start();
			include 'bkt.stream://'.$this->_fichier;
			$this->_html = ob_get_contents();
			ob_clean();
			ob_end_flush ();
			return $this->_html;
		}
	}
	/**
	 * Getteur des param�tres du template
	 * @param string $item Nom du param�tre
	 */
	public function __get($item){
		return isset($this->_datas[$item]) ? $this->_datas[$item] : '';
	}
	/**
	 * Setteur des param�tres du template
	 * @param string $item Nom du param�tre
	 * @param mixed $value Valeur du param�tre
	 */
	public function __set($item , $value){
		if(isset($this->_datas[$item]) && is_array($this->_datas[$item])){
			array_push($this->_datas[$item] , $value);
		}else{
			$this->_datas[$item] = $value;
		}
	}
	
	public function __toString(){
		return $this->getString();
	}
}