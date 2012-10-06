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
 * @package   Bkt_Formulaire
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Formulaire {
	/**
	 * @var Array Objets du formulaire
	 */
	private $_forms = array();
	/**
	 * Ajoute un élément au formulaire
	 * @param Bkt_Form $form Element
	 */
	public function add(Bkt_Form $form){
		array_push($this->_forms ,  $form);
	}

	/**
	 * Test si le formulaire est valide
	 * @return bool 
	 */
	public function isValid(){
		$erreurs = array();
		foreach($this->_forms as $form){
			$valid = $form->isValid();
			if($valid !== true){
				$erreurs[$form->_nom] = $valid;
			}
		}
		if(count($erreurs) == 0){
			return true;
		}
		return $erreurs;
	}
	
	/**
	 * Retourne TRUE si le formulaire a t post
	 */
	public static function isPost(){
		return (count($_POST) > 0 || count($_GET) > 0);
	}
}