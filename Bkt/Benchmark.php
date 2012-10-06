<?php
/**
 * Utilisation de la classe de benchmark :
 * $bm = new Bkt_Benchmark();
 * for($i = 0 ; $i < $n ; $i++){
 *    // Traitement...
 *	$bm->add($i);
 * }
 * print_r($bm->getBenchMark());
 */
class Bkt_Benchmark {
	private $data       = array();
	private $baseMemory = null;
	private $baseTime   = null;
	/**
	 * Initialise les variables du test
	 */
	private function __construct(){
		$this->baseMemory = memory_get_usage();
		$this->baseTime = time();
	}
	/**
	 * Met à jours les données du benchmark: mémoire, temps
	 * @param int $iteration Numéro de l'itération
	 */
	private function benchmark($iteration){
		array_push($this->data, array(
			$this->iteration ,
			(memory_get_usage() - $this->baseMemory) ,
			(time() - $this->baseTime)
		));
	}
	/**
	 * Retourne les données du benchmark
	 */
	private function getBenchMark(){
		return $this->data;
	}	
}