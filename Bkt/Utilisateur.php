<?php
class Bkt_Utilisateur extends Bkt_Table {
	protected $_name    = '';
	protected $_primary = '';
	private   $_user    = null;

	const _GUEST_ = -1;
	const _USER_  = 1;
	const _ADMIN_ = 2;
	
	public function __construct($user){
		$this->_name    = Bkt_Config::$_conf->acl->table;
		$this->_primary = Bkt_Config::$_conf->acl->table;
		$this->_user    = $user;
	}
	public static function login($login , $passwd , $hash =  null){
		//print_r(Bkt_Config::$_conf->acl);
		$foo = Bkt_Table::getInstance(Bkt_Config::$_conf->acl->table)->executeS(Bkt_Query2::getInstance()
			->Select('*')
			->From(Bkt_Config::$_conf->acl->table)
			->Where(array(
				Bkt_Config::$_conf->acl->login.'="'.$login.'"' ,
				Bkt_Config::$_conf->acl->passwd.'="'.(is_null($hash) ? $passwd : $hash($passwd)) .'"'
			))
		);
		if(count($foo) > 0){
			Bkt_Storage::getInstance()->utilisateur = new Bkt_Utilisateur($foo[0]);
			return true;
		}else{
			return false;
		}
	}
	
	public function __get($k){
		return $this->_user->$k;
	}
	
	public static function user(){
		return Bkt_Storage::getInstance()->utilisateur;
	}
	
	public static function role(){
		$stat = Bkt_Config::$_conf->acl->role;
		if(Bkt_Utilisateur::isOnline()){
			return Bkt_Storage::getInstance()->utilisateur->$role;
		}else{
			return -1;
		}
	}
	
	public static function isOnline(){
		if(is_a(Bkt_Storage::getInstance()->utilisateur, 'Bkt_Utilisateur')){
			return true;
		}else{
			return false;
		}
	}
	
	public static function logout(){
		unset(Bkt_Storage::getInstance()->utilisateur);
		//Bkt_Storage::kill('utilisateur');
	}

}
