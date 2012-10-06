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
 * @package   Bkt_Traduction
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Trad {
	/**
	 * Traduit une chaine
	 * @param $item
	 * @return unknown_type
	 */
	public static function t($fichier , $item , $otherFile = false){
		global $url;
		if($otherFile !== false){
			$fichier = $otherFile;
		}
		$fichier = str_replace($url , '' , $fichier );
		$fichier = str_replace('bkt.stream://' , '' , $fichier );
		echo "Traduire '$item' du fichier '$fichier'";
	}
}