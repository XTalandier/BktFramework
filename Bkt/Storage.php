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
 * @package   Bkt_Storage
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
class Bkt_Storage {
	/**
	 * Getteur
	 * @param string $item
	 * @return mixed
	 */
	public function __get($item){
		return isset($_SESSION[$item]) ? $_SESSION[$item] : null;
	}
	/**
	 * Setteur
	 * @param string $item
	 * @param mixed $value
	 */
	public function __set($item , $value){
		@session_register($item);
		$_SESSION[$item] = $value;
	}
	
	public function __unset($item){
		@session_unregister($item);
		unset($_SESSION[$item]);
	}
	
	public static function kill($item){
		$_SESSION[$item] = null;
		unset($_SESSION[$item]);
	}
	
	public static function getInstance(){
		return new Bkt_Storage();
	}
}
