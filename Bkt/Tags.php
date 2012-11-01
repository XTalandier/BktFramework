<?php
/**
 * Description of Tags
 *
 * @author Xavier TALANDIER
 */
class Bkt_Tags {
	
	/**
	 * Parse a string
	 * Transform <bkt:text:txtemail placeholder="email" /> 
	 * To : <input type="text" name="txtemail" id="txtemail"  placeholder="email"  value="" />
	 * @param string $data Data to parse
	 * @return string Parsed data
	 */
	public static function parse($data){
		preg_match_all('/<bkt:([a-zA-Z]*):([a-zA-Z]*)(.*)\/>/i', $data, $result);
		if(!isset($result[0][0])){
			return $data;
		}
		$n = count($result[0]);
		for($i = 0 ; $i < $n ; $i++){
			$type   = $result[1][$i];
			$name   = $result[2][$i];
			$params = $result[3][$i];
			$value  = isset($_POST[$name]) ? $_POST[$name] : '';
			$tag = sprintf('<input type="%s" name="%s" id="%s" %s value="%s" />' , $type , $name , $name , $params , $value);
			$data = str_replace($result[0][$i], $tag, $data);
		}
		return $data;
	}
}

?>