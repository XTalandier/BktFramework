<?php
class Bkt_Image {
	private $_image  = null;
	private $_width  = null;
	private $_height = null;
	
	public function __construct($image){
		$this->_image  = imagecreatefromjpeg($image);
		$this->_width  = imagesx($this->_image);
		$this->_height = imagesy($this->_image);
	}
	
	public static function getInstance($image){
		return new Bkt_Image($image);
	}
	
	public function width(){
		return $this->_width;
	}
	public function height(){
		return $this->_height;
	}
	
	public function mini($width = 150 , $height = 150){
		$image = imagecreatetruecolor($width, $height);
		imagecopyresized($image, $this->_image, 0, 0, 0, 0, $width, $height, $this->_width, $this->_height);
		$this->_image = $image;
		return $this;
	}
	public function save($fichier = null , $qualite = 100){
		if(is_null($fichier)){
			header('Content-Type : image/jpeg');
			imagejpeg($this->_image , null , $qualite);
		}else{
			imagejpeg($this->_image , $fichier , $qualite);
		}
	}
}