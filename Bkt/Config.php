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
 * @package   Bkt_Config
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Config {
	public static $_conf = null;
	/**
	 * Charge le fichier de configuration
	 * @param string $fichier
	 */
	public static function init($fichier , $config = 'production'){
		$conf = new Bkt_Objet();
		$xml  = simplexml_load_file($fichier);
		$conf = $xml->xpath('/configs/config[@name="'.$config.'"]');
		foreach ($conf[0] as $item => $arr){
			$datas = array();
			foreach($arr->attributes() as $a => $b) {
				$datas['_'.$a] = Bkt_Objet::cast('string' , $b[0]);
			}			
			foreach ($arr  as $k => $v){
				$v = Bkt_Objet::cast('string' , $v);
				if(isset($datas[$k])){
					if(is_array($datas[$k])){
						array_push($datas[$k], $v);
					}else{
						$foo       = $datas[$k];
						$datas[$k] = array();
						array_push($datas[$k], $foo);
						array_push($datas[$k], $v);
					}
				}else{
					$datas[$k] = $v;
				}
			}
			$curItem = new Bkt_Objet($datas);
			self::$_conf->$item = $curItem;
		}
	}

	/**
	 * Récupère une configuration
	 * @param $item Configuration à récupérer
	 * @return variant
	 */
	
	public function __get($item){
		return self::$_conf->$item;
	}
	
}