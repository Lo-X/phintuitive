<?php
/**
 *	PhIntuitive - Fast websites development framework
 *	Copyright 2013, Boutter Loïc - http://loicboutter.fr
 *
 *	Licensed under The MIT License
 *	Redistributions of files must retain the above copyright notice.
 *
 *	@copyright Copyright 2013, Boutter Loïc - http://loicboutter.fr
 *	@author Boutter Loïc
 *	@since 2.0.0
 */



/**
 * @brief This is the base class for all Controllers of your application.
 * @details Each action should have its method in your Controller, with correct parameters.
 * Plus, you have to implement the method index() because it will be call by default when no action is specified.
 */
class Controller {
	
	public 		$request;				///< The Request object that has ben used to create the Controller
	private 	$vars = array();		///< An array of variables that will be given to the view
	protected 	$uses = array();		///< An array of Model names that the Controller needs. A model corresponding to the controller will automatically be 											 added if exist
	protected	$helpers = array('Html');///< An array of helpers you may need in the view. These will be automatically added to the controller
	protected	$components = array();	///< An array of components the Controller need to work. These will be automatically added
	public 		$layout = 'layout';		///< The layout used by the Controller, by default: layout
	private 	$rendered = false;		///< A boolean used to know if the view has already been exposed or not
	public 		$useDefault404 = true;	///< A boolean used to know if the default 404 (if true) or the module view should be use in case of not found action
	protected 	$cacheDuration = 30;	///< Cache duration in minutes
	protected 	$cacheActions;			///< Array containing each action and its cache duration. If empty and $this->Cache is set, it will cache all actions 											 at default duration
	

	/**
	 * The Controller constructor is very important. If your derived class has a constructor, please don't forget to call Controller constructor ! 
	 * This constructor will initialize a lot of things, such as a Session, the layout if there's a prefix in the url, the models (default and those defined in $uses)
	 *
	 * @param Request $request The Request object that permit to construct/init the Controller
	 */
	public function __construct($request)
	{
		$this->request = $request;
		
		// If there's the admin prefix, we change the layout for the admin panel layout
		if($this->request->prefix == 'admin') {	
			$this->layout = 'admin';
		}

		
		// Loading Models

		// Load the Model the controller need, begining with the default one :
		if(strrpos($this->request->controller, 's') == strlen($this->request->controller)-1)
			$modelName = ucfirst(substr($this->request->controller, 0, -1));
		else
			$modelName = ucfirst($this->request->controller);

		// Try to load the default Model, it can be normal (but unusual) it does not exists
		if(App::load($modelName))
			$this->$modelName = new $modelName;

		// Load all Models
		foreach ($this->uses as $modelName) {
			if(!App::load($modelName) && Config::debug())
			{
				exit('Try to load Model '.$modelName.' that does not exists !');
			}

			// Instances models in controller attributes
			if(!isset($this->$modelName))
			$this->$modelName = new $modelName;
		}
			



		// Loading helpers
		foreach ($this->helpers as $helperName) {
			if(!App::load($helperName, 'helper') && Config::debug())
				exit('Try to load Helper '.$helperName.' that does not exists !');
			$helperClass = $helperName.'Helper';
			$this->$helperName = new $helperClass($this->request);
		}




		// Loading components
		foreach ($this->components as $componentName) {
			if(!App::load($componentName, 'component') && Config::debug())
				exit('Try to load Component '.$componentName.' that does not exists !');

			// Instances components in controller attributes
			$componentName = ucfirst($componentName);
			$class = $componentName.'Component';
			if(!isset($this->$componentName))
				$this->$componentName = new $class($this->request);
		}

		// Cache view settings
		if(isset($this->Cache))
		{
			$this->Cache->setDuration($this->cacheDuration);
		}

		// Cache layout settings
		if(Config::get('cacheLayouts'))
		{
			App::load('CacheLayout', 'component');
			$this->CacheLayout = new CacheLayoutComponent($this->layout);
		}
	}

	
	/**
	 * Hook method, you can override it to do what you want before the action is called
	 * ie: you can check the Authentification
	 */
	public function beforeAction()
	{

	}

	/**
	 * Hook method, you can override it to do what you want after the action has been called
	 */
	public function afterAction()
	{

	}

	/**
	 * Hook method, you can override it to do what you want before the view is exposed
	 */
	public function beforeRender()
	{

	}
	
	/**
	 * Expose (show) the asked view corresponding to the module
	 */
	public function render($view) {
		if($this->rendered)	return false;
		
		// Get view and layout
		$view = App::getView($this->request->module, $view);
		$layout = App::getLayout($this->layout);

		// If the view does not exist, go to e404
		if(!is_file($view)) {
			$this->e404('Try to load a view '.$view.' that does not exist !');
		}

		// Extract variables that has been set by the controller
		extract($this->vars);


		// Is the view in cache ?
		$d_cache = $this->_doCache();
		if($d_cache)
		{
			// If the view is not in cache (or out of date)
			if(!$this->Cache->upToDate($d_cache))
			{
				ob_start();
				require($view);
				// Set the famous $content_for_layout variable
				$content = ob_get_clean();

				// Put the view in cache if possible
				$this->Cache->write($content);
			}

			$content_for_layout = $this->Cache->read();
		}
		else 	// If the user don't want cache
		{
			ob_start();
			require($view);
			// Set the famous $content_for_layout variable
			$content_for_layout = ob_get_clean();
		}

		

		// Cache layout settings
		if(Config::get('cacheLayouts'))
		{
			$this->CacheLayout->setLayout($this->layout);
			$this->CacheLayout->addFilters(array('content_for_layout' => $content_for_layout));

			// If the layout is not in cache (or out of date)
			if(!$this->CacheLayout->upToDate(Config::get('cacheLayouts')))
			{
				ob_start();
				require($layout);
				// Set the famous $content_for_layout variable
				$content = ob_get_clean();

				// Put the view in cache if possible
				$this->CacheLayout->write($content);
			}

			echo $this->CacheLayout->read();
		}
		else
		{
			// The layout is not cached
			require($layout);
		}
		
		$this->rendered = true;
	}

	private function _doCache()
	{
		if(isset($this->Cache))
		{
			if(isset($this->cacheActions))
			{
				if(array_key_exists($this->request->action, $this->cacheActions))
					return $this->cacheActions[$this->request->action];
				else 
					return 0;
			}
			return $this->cacheDuration;
		}

		return 0;
	}
	
	
	/**
	 * Expose the module 404 view page with a 404 Not Found header and a default message
	 *
	 * @param string $msg The message youwant to print on the view
	 */
	public function e404($msg)
	{
		header("HTTP/1.0 404 Not Found");
		$this->set('message', $msg);
		if(file_exists(App::getView($this->request->module, '405'))) {
			$this->render('404');
		} else {
			$message = 'Big problem dude ! The 404 view of controller <i>'.$this->request->controller.'</i> does not exist !';
			$this->set('message', $message);
			ob_start();
			require(ROOT.APP_DIR.'views/404.php');
			// Set the famous $content_for_layout variable
			$content_for_layout = ob_get_clean();
			require(App::getLayout($this->layout));
			$this->rendered = true;
		}

		die();
	}
	
	
	/**
	 * This method is used to 'give' variables to the view. 
	 * If the key is between {{ key }}  the variable will be gven to the view at rendering,
	 * else it wll be given as a php variable that you can manipulate as you wish
	 *
	 * @param string $key   The future name of the variable (ie: 'apple' will become $apple)
	 * @param mixed  $value The value of the variable. It's a mixed value (array, string, int, float, ...).
	 */
	public function set($key, $value = null)
	{
		if($key[0] == '{' && $key[1] == '{' && isset($this->Cache))
		{
			$key = str_replace('{', '', $key);
			$key = str_replace('}', '', $key);
			$this->Cache->addFilters(array($key => $value));
		}
		else if(is_array($key))
		{
			$this->vars += $key;
		}
		else
		{
			$this->vars[$key] = $value;
		}	
	}
	
	
	/**
	 * This method is usefull if your action does not expose a view but the view of another action. 
	 * This does a simple php header redirection.
	 *
	 * @param string $url  The URL you want to go to. It will be parsed by the Router
	 * @param int    $code The header code, if you want to use one. For example you can use 301 if the page has moved permanently
	 */
	public function redirect($url, $code = null) {
		if($code == 301) {
			header("HTTP/1.1 301 Moved Permanently");
		}
		$this->rendered = true;
		header("Location: ".Router::url($url));
	}
	
}
?>