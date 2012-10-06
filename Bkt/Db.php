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
 * @package   Bkt_Db
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Db {
	protected static $_conf = null;
	protected static $_link = null;
	
	/**
	 * Initialise la connexion é la base de données
	 */
	public static function init(){
		self::$_conf = new Bkt_Objet();
		self::$_conf->db = Bkt_Config::$_conf->db;
		self::$_link     = mysql_connect(
			Bkt_Config::$_conf->db->host.':'.Bkt_Config::$_conf->db->port ,
			Bkt_Config::$_conf->db->user ,
			Bkt_Config::$_conf->db->pass
		);
		if(!self::$_link){
			die('ERREUR MYSQL');
		}
		mysql_select_db(Bkt_Config::$_conf->db->db , self::$_link);
	}
	
	public static function close(){
		//mysql_close(self::$_link);
	}
}