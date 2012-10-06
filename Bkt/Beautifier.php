<?php
class Bkt_Beautifier {
	public static function monetaire ($in , $monnaie = '&euro;'){
		$expl   = explode('.', $in);
		$entier = '';
		$s = strlen($expl[0]);
		for($i = 1 ; $i <= $s ; $i++){
			$entier = ($i % 3 == 0  ? ' ' : '').substr($expl[0], $s - $i , 1).$entier;
		}
		$decimal = (int) isset($expl[1]) ? $expl[1] : '0';
		if($decimal == 0){
			$decimal = '00';
		}elseif($decimal < 10){
			$decimal = $decimal.'0';
		}
		return $entier.','.$decimal.' '.$monnaie;
	}
	
	public static function nullVal($in , $default = 'N/C'){
		if(is_null($in) || $in == '' || $in == 0){
			$in = $default;
		}
		return $in;
	}
	
}