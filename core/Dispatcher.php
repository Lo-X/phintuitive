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
 * @brief This is the most important class with Request and Router even if you will never use it directly.
 * @details This class create and initialize a Request that will be send through your application.
 *
 * @see Request, Router
 */
class Dispatcher {
	
	var $request; ///< The Request object
	
	/**
	 * Creates and initialize a Request object
	 */
	public function __construct() {
		// Creates the Request
		$this->request = new Request();

		// Parses the requested url and extract pre-defined Routes, or extract param informations (controller, action, prefix, params)
		Router::parse($this->request);

		// Attempt to load the controller given by the url
		$controller = $this->loadController();

		// If there is a prefix, we reset the action to 'prefix_action'
		if($this->request->prefix)
			$this->request->action = $this->request->prefix.'_'.$this->request->action;

		// Check if the Controller has the requested action
		if(!in_array($this->request->action, array_diff(get_class_methods($controller), get_class_methods('Controller'))))
		{
			// If the controller want to use the default 404 view
			if($controller->useDefault404)
				$this->missing('The Controller <b>'.$this->request->controller.'</b> does not have <i>'.$this->request->action.'</i> action !'); // It has not !
			else
				$this->error('The Controller <b>'.$this->request->controller.'</b> does not have <i>'.$this->request->action.'</i> action !'); // It has not !
		}
			
		// If everyting is fine, we call the Controller action method with given params
		$controller->beforeAction();
		call_user_func_array(array($controller, $this->request->action), $this->request->params);
		$controller->afterAction();


		// Expose the view
		$controller->beforeRender();
		$controller->render($this->request->action);
	}
	
	/**
	* Returns a 404 error to the visitor, when the controller+action he asked does not exist.
	*/
	public function error($error) 
	{
		$controller = new Controller($this->request);
		$controller->e404($error);
	}
	
	private function missing($error) 
	{
		$controller = new Controller($this->request);
		header("HTTP/1.0 404 Not Found");
		$layout = App::layouts().'404.php';
		$content_for_layout = $error;
		debug($this->request);
		require($layout);
		die();
	}
	

	/**
	 * Attempt to load the controller the Request object contains
	 *
	 * @return If succeed, returns an instance on the Controller
	 */
	private function loadController()
	{
		// Extract controller name
		$ctrlname = $this->request->controller;

		// Try to load the controller, if it does not exists -> e404
		if(!App::load($ctrlname, 'controller'))
			$this->missing("The page you attempted to reach does not exist !");

		$ctrlname = $ctrlname.'Controller';

		// Return the new Controller instance
		return  new $ctrlname($this->request);
	}
	
}


?>