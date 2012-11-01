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
 * @package   Bkt_Validateur
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */

define('_VALIDATEUR_ALPHABET_ALPHA_'     , 'alpha');
define('_VALIDATEUR_ALPHABET_NUM_'       , 'num');
define('_VALIDATEUR_ALPHABET_ALPHANUM_'  , 'alphanum');
define('_VALIDATEUR_ALPHABET_ALPHANUMS_' , 'alphanums');
class Bkt_Validateur {

	/**
	 * Valide une chaine de caractère uniquement
	 * @param $valeur Chaine à valider
	 * @return bool
	 */
	public static function validateur_alpha($valeur){
		return self::validateur_alphabet('alpha' , $valeur);
	}
	/**
	 * Valide une chainte numérique
	 * @param $valeur Chaine à valider
	 * @return unknown_type
	 */
	public static function validateur_num($valeur){
		return self::validateur_alphabet('num' , $valeur);
	}
	/**
	 * Valide une chaine numérique & alphabétique
	 * @param $valeur Chaine à valider
	 * @return unknown_type
	 */
	public static function validateur_alphanum($valeur){
		return self::validateur_alphabet('alphanum' , $valeur);
	}
	
	/**
	 * Valide une chaine en fonction d'un alphabet
	 * @param string $alphabet Alphabet � utiliser: apha, num, alphanum, autre
	 * @param string $valeur
	 * @return bool
	 */
	public static function validateur_alphabet($alphabet , $valeur){
		switch($alphabet){
			case _VALIDATEUR_ALPHABET_ALPHA_:
				$alphabet = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN';
				break;
			case _VALIDATEUR_ALPHABET_ALPHANUM_:
				$alphabet = '1234567890';
				break;
			case _VALIDATEUR_ALPHABET_ALPHANUM_:
				$alphabet = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890';
				break;
			case _VALIDATEUR_ALPHABET_ALPHANUMS_:
				$alphabet = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890 .,_-';
				break;
		}
		$n = strlen($valeur);
		for($i = 0 ; $i < $n ;$i++){
			if(strpos($alphabet , substr($valeur , $i , 1)) === false){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Valide un email
	 * @param string $valeur Chaine � valider
	 * @return bool
	 */
	public static function validateur_email($valeur){
		if (!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/' , $valeur)) {
			return false;
		}
		return true;
	}
	
	/**
	 * Valide une chaine non vide
	 * @param string $valeur Chaine � valider
	 * @return bool
	 */
	public static function validateur_notEmpty($valeur){
		return !(trim($valeur) == '');
	}
	/**
	 * Vérifie qu'une chaine fait + de 5 caractères
	 * @param $valeur Chaine à valider
	 * @return bool
	 */
	public static function validateur_plusgrand5($valeur){
		return (strlen($valeur) > 5);
	}
	/**
	 * Vérifie qu'une chaine fait + de n caractères
	 * @param $valeur Chaine à valider
	 * @return bool
	 */
	public static function validateur_plusgrandN($valeur , $n){
		return (strlen($valeur) > $n);
	}
	
	/**
	 * Valide une chaine date
	 * @param $valeur Chaine à valider
	 * @return unknown_type
	 */
	public static function validateur_isDate($valeur){
		try{
			$aDate_parts = explode("/", $valeur);
			return count($aDate_parts) != 3 ? false : checkdate(
				$aDate_parts[1], // Month
				$aDate_parts[0], // Day
				$aDate_parts[2] // Year
			);
		}catch(Exeption $e){
			return false;
		}
	}
	
}
