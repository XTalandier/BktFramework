<?php
/**
 * Description of Header
 *
 * @author Xavier TALANDIER
 */
class Bkt_Header {
	private $_scripts = array();
	private $_css     = array();
	private $_meta    = array();
	private $_title   = '';
	
	/**
	 * Set title
	 * @param String $title
	 */
	public function setTitle($title){
		$this->_title = $title;
	}
	/**
	 * Add script file
	 * @param String $script
	 */
	public function addScript($script){
		array_push($this->_scripts, $script);
	}
	/**
	 * Add css file
	 * @param String $css
	 */
	public function addCss($css){
		array_push($this->_css, $css);
	}
	/**
	 * Add meta
	 * @param String $name
	 * @param String $value
	 */
	public function addMeta($name , $value){
		array_push($this->_meta, array('name' => $name , 'value' => $value));
	}
	
	public function __toString() {
		$html = '';
		$n    = count($this->_scripts);
		for($i = 0 ; $i < $n ; $i++){
			$html.= '<script type="text/javascript" src="'.$this->_scripts[$i].'"></script>';
		}
		$n    = count($this->_css);
		for($i = 0 ; $i < $n ; $i++){
			$html.= '<link rel="stylesheet" href="'.$this->_css[$i].'" />';
		}
		$n    = count($this->_meta);
		for($i = 0 ; $i < $n ; $i++){
			$html.= '<meta name="'.$this->_meta[$i]['name'].'" src="'.$this->_meta[$i]['value'].'" />';
		}
		$html.= '<title>'.Bkt_Config::$_conf->params->title.($this->_title == '' ? '' : ' - ').$this->_title.'</title>';
		return $html;
	}
}
