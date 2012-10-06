<?php
class Bkt_Smarty {
	private $_smarty;
	private $_template;
	public function __construct($template , $debugging = false , $caching = false){
		$this->_smarty = new Smarty();
		$this->_smarty->debugging      = $debugging;
		$this->_smarty->caching        = $caching;
		$this->_smarty->cache_lifetime = 120;
		$this->_template               = $template;
		$this->_smarty->cache_dir      = Bkt_Config::$_conf->template->cache;
		echo Bkt_Config::$_conf->template->cache;
	}
	public static function getInstance($template , $debugging = false , $caching = false){
		return new Bkt_Smarty($template , $debugging , $caching);
	}
	public function __set($item , $value){
		$this->_smarty->assign($item , $value);
	}
	public function getHTML(){
		ob_start();
		$this->_smarty->display($this->_template);
		$html = ob_get_contents();
		ob_clean();
		return $html;
	}
}

