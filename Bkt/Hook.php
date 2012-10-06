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
 * @package   Bkt_Hook
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Hook {
	/**
	 * @var Array Variables pour le hook
	 */	
	public $_params = array();
	/**
	 * @var Bkt_Template Template du hook
	 */
	public $view   = '';

	/**
	 * Création d'un Hook 
	 * @param $hook Nom du hook
	 * @param $params Paramètres du hook
	 * @return string Template exécuté
	 */
	public static function Hook($hook , $params = array() , $return = false){
		$objet = Bkt_Factory::factory('Hook_'.ucfirst(strtolower($hook)));
		$foo = $objet->init($params);
		if(isset($objet->view)){
			return $objet->view->getString(false);
		}else{
			return $foo;
		}
	}
	
	public function initDbFromHook($f){
		Bkt_Config::init(dirname(dirname($f)).'/config.xml');
		// Initialise la base de données
		Bkt_Db::init();
	}
}


