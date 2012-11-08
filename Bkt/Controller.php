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
 * @package   Bkt_Controller
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Controller {
	
	/**
	 * @var Bkt_Template
	 */
	public $view   = null;
	/**
	 * Le layout
	 * @var unknown_type
	 */
	public $layout = null;
	
	public $params  = null;
	public $request = null;
	
	public static $_base = '';
	
	/**
	 *
	 * @var Bkt_Header
	 */
	public $header;
	
	
	/**
	 * Initialisation du controller
	 */

	public function __construct(){
		self::$_base = Bkt_Config::$_conf->params->base;
		$this->header = new Bkt_Header();
	}
	public function init(){}

	/**
	 * Redirige vers une page
	 * @param string $url
	 */
	public function redirect($url){
		header('Location: '.self::$_base.$url);
	}
	
	public function processAjax(){
		exit;
	}
	
	/**
	 * Retourne un parametre
	 * @param string $param Paramètre à retourner
	 * @param variant $default Retour par défault
	 */
	public function getParam($param , $default = false){
		return isset($this->params[$param]) ? $this->params[$param] : $default;
	}
}