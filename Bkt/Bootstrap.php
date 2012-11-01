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
 * @package   Bkt
 * @copyright  Copyright (c) 2010-2011 Xavier Talandier. (xavier.talandier@gmail.com)
 * @license   New BSD License
 */
$url = str_replace('www/index.php' , '' , $_SERVER['SCRIPT_FILENAME']).'application/';
function __autoload($class_name) {
	global $url;
	//echo $class_name.'<br />';
	$expl = explode('_' , $class_name);
	// Application Class
	if(file_exists($url.str_replace('_' , '/' , $class_name).'.php')){
		require_once($url.str_replace('_' , '/' , $class_name).'.php');
	
	// Framework class
	}elseif(file_exists(dirname(dirname(__FILE__)).'/'.str_replace('_' , '/' , $class_name).'.php')){
		require_once dirname(dirname(__FILE__)).'/'.str_replace('_' , '/' , $class_name).'.php';

	// Application module ?
	}else{
		$expl = explode('_' , $class_name);
		if(strtolower($expl[0]) == 'module'){
		}
		$fichier = $url.str_replace('_' , '/' , $class_name).'.php';
		if(file_exists($fichier)){
			require_once($fichier);
		}else{
			$fichier = dirname(__FILE__).'/untitled/'.$expl[0].'.php';
			if(file_exists($fichier)){
				require_once $fichier;
			}
		}
	}
}

class Bkt_Bootstrap {

	public function __construct(){}
	public static   $requestParams;
	public static $instance;
	/**
	 * Exécute l'application
	 */
	public function run(){
		session_start();
		global $url;
		Bkt_Bootstrap::$instance = $this;
		$parts = explode('/', $_SERVER['REQUEST_URI']);
		array_shift($parts);
		// Enleve le premier element
		Bkt_Application::setUrl($url);
		// Initialisation de la console
		Bkt_Console::init($url.'logs/erreurs.html');
		// Chargement du fichier de configuration
		Bkt_Config::init($url.'/config.xml');
		// Initialise la base de données
		Bkt_Db::init();
		//Bkt_Template::$_type = Bkt_Config::$_conf->template->type;
		// Qui sont de la forme controller/action/var1/val1/var2/val2/...
		$request_t = str_replace(str_replace('index.php' , '' , $_SERVER['SCRIPT_NAME']) , '' , $_SERVER['REQUEST_URI']);       
        $request_array = array(
			'ismodule'   => false ,
			'controller' => $parts[0] == '' ? 'Index' : trim(ucfirst(strtolower($parts[0]))) ,
			'action'     => strtolower(isset($parts[1]) && trim($parts[1]) != '' ? $parts[1] : 'index') ,
			'view'       => strtolower(isset($parts[1]) && trim($parts[1]) != '' ? $parts[1] : 'index') ,
			'layout'     => new Bkt_Template($url.'Layout/normal.'.Bkt_Config::$_conf->template->extension) ,
			'variables'  => array() ,
		);
		for($i = 2 ; $i < count($parts) ; $i+= 2){
			$request_array['variables'][$parts[$i]] = isset($parts[$i + 1]) ? $parts[$i + 1] : '';
		}
		// Création de l'objet représentatif de la requéte
		$request = new Bkt_Request($request_array);
		// On fait appel é la fonction de pré-affichage
		$request = $this->preDispatch($request);

		self::$requestParams = $request->variables;
		// On va créer le controller
		$objet = Bkt_Factory::factory('Controller_'.$request->controller.'Controller');
		if($objet === false){
			// C'est un module ?
			$objet = Bkt_Factory::factory('Module_'.$request->controller.'_'.$request->controller.'Controller');
			$request->ismodule = true;
			if($objet === false){
				print_r($objet);
				$request->ismodule = false;
				$objet = Bkt_Factory::factory('Controller_ErreurController');
				$request->action     = 'erreur404';
				$request->view       = 'erreur404';
				$request->controller = 'Erreur';
			}
		}
		$objet->params  = $request->variables;
		$objet->request = $request;
		// On lui envoie les différents templates: Layout & Vue
		$objet->layout       = $request->layout;
		$objet->layout->base = Bkt_Config::$_conf->params->base;
		if($request->ismodule){
			$objet->view = new Bkt_Template($url.'/Module/'.$request->controller.'/Views/'.$request->view.'.'.Bkt_Config::$_conf->template->extension);
		}else {
			$objet->view = new Bkt_Template($url.'Views/'.strtolower($request->controller).'/'.$request->view.'.'.Bkt_Config::$_conf->template->extension);
		}
		// On initialise le controller
		$objet->init();
		// On fait appel é l'action demandé
		try{
			if(method_exists($objet , $request->action.'Action')){
				call_user_func(
					array(
						$objet ,
						$request->action.'Action'
					)
				);
			}else{
				$objet = new Controller_ErreurController();
				$objet->layout = $request->layout;
				$objet->view   = new Bkt_Template($url.'View/erreur/erreur404.'.Bkt_Config::$_conf->template->extension);
				$objet->erreur404Action($request);
				//$objet->layout = new Bkt_Template($url.'Layout/erreur.'.Bkt_Config::$_conf->template->extension);
				//$objet->erreurAction($e);
				//echo '404';//'<pre>'.print_r($e , true).'</pre>';
				//exit;
			}
		}catch(Bkt_Exeption $e){
			//$objet = new Controller_ErreurController();
			//$objet->layout = new Bkt_Template($url.'Layout/erreur.'.Bkt_Config::$_conf->template->extension);
			//$objet->view   = new Bkt_Template($url.'View/erreur/erreur.'.Bkt_Config::$_conf->template->extension);
			//$objet->erreurAction($e);
			echo '<pre>'.print_r($e , true).'</pre>';
			exit;
		}
		
		if(is_null($objet->layout)){
			echo $objet->view->getString();
		}else{
			// On insére la vue dans le layout
			$objet->layout->content = $objet->view->getString();
			// On affiche le layout
			echo $objet->layout->getString();
		}
		// Ferme la connexion é la base de données
		Bkt_Db::close();
		// Appel la fonction de fin d'exécution
		$this->endDispatch();
	}
	
	/**
	 * Méthode de pré-affichage.
	 * Elle doit etre surchargé
	 */
	public function preDispatch(Bkt_Request &$request){
		return $request;
	}
	
	/**
	 * Méthode de fin
	 * Elle doit etre surchargé
	 */
	public function endDispatch(){}

	public static function changeLayout($layout){
		global $url;
		self::$instance->layout = new Bkt_Template($url."Layout/$layout.".Bkt_Config::$_conf->template->extension);
	}
	
	public static function getParam($item , $default = null , $index = null){
		if(isset(self::$requestParams[$item])){
			if(strpos(self::$requestParams[$item], ',') && $index !== false){
				$expl = explode(',' , self::$requestParams[$item]);
				return is_null($index) ? $expl : $expl[$index];
			}else{
				return is_array($default) ? array(self::$requestParams[$item]) : self::$requestParams[$item];
			}
		}else{
			return $default;
		}
	}
	
	public function route($route , $controller , $action , Bkt_Request &$request){
		return $request;
	}
}