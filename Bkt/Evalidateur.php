<?php
class Bkt_Evalidateur{
	private $_champs  = array();
	private $_erreurs = array();
	
	public function __construct($data) {
		$validateurs = array();
		foreach($data as $k => $v){
			if($v->column_comment != ''){
				$expl       = explode(';' , $v->column_comment);
				$validateurs[$v->column_name] = array();
				for($i = 0; $i < count($expl);$i++){
					$validateur = array();
					if(strpos($expl[$i], ':') !== false){
						$expl2 = explode(':', $expl[$i]);
						$validateur['fonction']  = $expl2[0];
						array_shift($expl2);
						$validateur['arguments'] = array_shift($expl2);
					}else{
						$validateur['fonction'] = $expl[$i];
					}
					array_push($validateurs[$v->column_name], new Bkt_Objet($validateur));
				}
			}
		}
		$this->_champs = $validateurs;
	}

	public function isValid($champs){
		//print_r($this->_champs);
		foreach($champs as $k => $v){
			if(isset($this->_champs[$k])){
				for($i = 0; $i < count($this->_champs[$k]);$i++){
					$methode = '_validate_'.$this->_champs[$k][$i]->fonction;
					$args    = $this->_champs[$k][$i]->arguments;
					$validation = $this->{$methode}($v , $args);
					if($validation !== true){
						echo "Erreur sur le champs $k ! <br />\n";
					}
				}
				echo "$k => $v \n";
			}
		}

		exit;
	}
	
	private function _validate_alpha($data){
		return Bkt_Validateur::validateur_alpha($data);
	}
	private function _validate_max($data , $args){
		return strlen($data) <= $args[0];
	}
	private function _validate_min($data , $args){
		return strlen($data) >= $args[0];
	}
	private function _validate_email($data){
		return Bkt_Validateur::validateur_email($data);
	}
	private function _validate_num($data){
		return Bkt_Validateur::validateur_num($data);
	}
	private function _validate_date($data){
		return Bkt_Validateur::validateur_isDate($data);
	}
	
	
}