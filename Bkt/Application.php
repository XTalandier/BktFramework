<?php
class Bkt_Application {
	private static $_url;
	
	public static function setUrl($url){
		self::$_url = $url;
	}
	
	public static function getViews($dir = ''){
		return self::$_url.'View/'.($dir == '' ? $dir : $dir.'/');
	}
	public static function getWWW($dir = ''){
		return dirname(self::$_url).'/www/';
	}
}