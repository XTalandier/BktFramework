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
 * @package   Bkt_Console
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Console {
	/**
	 * @var string URL du fichier d'érreur
	 */
	private static $_fichier = 'logs/erreurs.html';
	/**
	 * Initilalise la console
	 * @param $fichier Fichier de log
	 */
	public static function init($fichier = ''){
		return false;
		if($fichier != ''){
			self::$_fichier = $fichier;
		}
		if(filesize(self::$_fichier) > 10421772){
			copy(self::$_fichier , self::$_fichier.'-'.time());
			unlink(self::$_fichier);
			file_put_contents(self::$_fichier , '');
		}
	}

	/**
	 * Ecrit une information
	 * @param $message string Message à écrire
	 * @param $trace Tableau à afficher
	 */
	public static function info($message , $trace = array()){
		//self::write('INFO' , '#3399FF' , $message , $trace);
	}
	
	/**
	 * Ecrit une Erreur
	 * @param $message string Message à écrire
	 * @param $trace Tableau à afficher
	 */
	public static function erreur($message , $trace = array()){
		self::write('INFO' , 'red' , $message , $trace);
	}
	
	/**
	 * Ecrit dans le log
	 * @param $level Niveau du message
	 * @param $couleur Couleur du message
	 * @param $message Message
	 * @param $trace Tableau a afficher
	 */
	private static function write($level , $couleur , $message , $trace = array()){
		try{
			/*
			$f = implode('' , file(self::$_fichier));
			$fp = fopen(self::$_fichier , 'w');
			fputs($fp , "<p style='color:$couleur;'>[$level]".date('d/m/Y H:i:s')."\n<br>".$message."<br />");
			fputs($fp , '<u>trace :</u><pre>'.print_r($trace , true)."</pre></p><hr>\n\n");
			fputs($fp , $f);
			fclose($fp);
			*/
		}catch(Exeption $e){
			print_r($e);
		}
	}
	
}