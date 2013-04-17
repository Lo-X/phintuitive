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



// Apply roues
require (ROOT.APP_DIR.'config/routes.php');

/**
 * @brief The Router is the core of the website, with the Dispatcher and Request
 * @details The Router class has two main goals : 
 *  - parse url to valid Request objects by taking account of Routes and Prefixes
 *  - parse url with named parameters (Routes) or with unhanged prefixes to a 'clickable' url
 * You can add Routes with Router::connect() method and add prefixes with Router::prefix() method
 *
 * @see Dispatcher, Request
 */
class Router {
	
	static $routes = array();
	static $prefixes = array();
	
	

	/**
	 * This method sets the routing prefixes.
	 * For example you can have a prefix for admin actions by using: Router::prefix('admin', 'admin'); so the URL of type '/admin/controller/action/' will be internally converted in 'controller/admin_action/' so the method Controller::admin_action() will be called.
	 *
	 * @param string $url 	 The keyword to match in the url
	 * @param string $prefix The prefix that will be applied to actions
	 */
	static function prefix($url, $prefix)
	{
		self::$prefixes[$url] = $prefix;
	}
	
	

	/**
	 * This method parses an url and extract useful informations in the given Request object.
	 * The Controller, Action, Parameters and prefixes are gonna be extracted by taking set Routes and Prefixes into consideration
	 *
	 * @param string $url 	   The url to parse
	 * @param Request $request The Request object that will be filled
	 */
	static function parse($request)
	{
 		// If the installation path isn't the root, we remove it from url
 		if(Config::installationPath() != '')
 			$request->url = str_replace(Config::installationPath(), '', $request->url);

		// Remove slashes at begining and end of the url
		$request->url = trim($request->url, '/');

		
		// Rewirte the url with routes (and named parameters)
		foreach(Router::$routes as $v)
		{			
			if(preg_match($v['catcher'], $request->url, $match))
			{
				$request->controller = $v['controller'];
				$request->module = $v['controller'];
				$request->action = $v['action'];
				$request->params = array();
				foreach($v['params'] as $k => $v)
					$request->params[$k] = $match[$k];

				return $request;
			}
		}
		

		// If the URL does not belong to a Route, we just extract parameters and prefixes

		// Parameters are separated by slashes, so we explode the url into an array
		$params = explode('/', $request->url);
		
		// Handle prefixes
		if(in_array($params[0], array_keys(self::$prefixes)))
		{
			$request->prefix = self::$prefixes[$params[0]];
			array_shift($params);
		}
		
		// Initialisation of the Request object
		// Get module and controller
		$request->module = empty($params[0]) ? 'content' : $params[0];
		$request->controller = empty($params[0]) ? 'content' : $params[0];
		// Get action
		$request->action	= isset($params[1]) ? $params[1] : 'index';
		// Get action parameters
		$request->params = array_slice($params, 2);

		return true;
	}
	
	

	/**
	 * Creates a Route by connecting an (visitor) eye friendly URL to a classic 'controller/action/params/'' URL. 
	 * You have to define Routes in the file /routes.php
	 * 
	 * The user URL is the URL that the visitor should see. You can add variables into that url with ':MyVariable'
	 * The redirection URL should be the classic 'controller/action/params/' URL. To match the variables of the user URL, you have to use REGEX and placeholders. IE: if you want to match MyVariable (an int), you can use: 'mycontroller/myaction/MyVariable:([0-9]+)' 
	 *
	 * Here is a complete example :
	 * @code 
	 *  // In /routes.php : Will redirect URL of type 'post/slug-id' to controller PostsController, action view and params id and slug
	 *	Router::connect('post/:slug-:id', 'posts/view/id:([0-9]+)/slug:([a-zA-Z0-9\-]+)');
	 *
	 *
	 *  // In /modules/posts/PostsController.php : We add an action to get everything
	 *  public function view($id, $slug) {
	 *  	// code here...
	 *  }
	 * @endcode
	 *
	 * @param string $userUrl	The user URL (the URL type that the visitor should see)
	 * @param string $redirUrl	The redirection URL (should be the classic 'controller/action/params/' URL with REGEXes)
	 */
	static function connect($userUrl, $redirUrl)
	{
		$r = array();
		$r['params'] = array();
		$r['redir'] = $userUrl;
		$r['origin'] = preg_replace('!([a-z0-9]+):([^\/]+)!', '${1}:(?P<${1}>${2})', $redirUrl);
		$r['origin'] = '!'.str_replace('/', '\/', $r['origin']).'!';
		
		$params = explode('/', $redirUrl);
		foreach($params as $k => $v)
		{
			if(strpos($v, ':')) {
				$p = explode(':', $v);
				$r['params'][$p[0]] = $p[1];
			}
			else {
				if($k == 0)
					$r['controller'] = $v;
				else if($k == 1)
					$r['action'] = $v;
			}
		}
		
		$r['catcher'] = $userUrl;
		foreach($r['params'] as $k => $v)
		{
			$r['catcher'] = str_replace(":$k", "(?P<$k>$v)", $r['catcher']);
		}
		$r['catcher'] = '!'.str_replace('/', '\/', $r['catcher']).'!';
		
		self::$routes[] = $r;
	}
	
	


	/**
	 * This is a useful method to get a valid and visitor friendly url, parsed from an explicit url with Routes and Prefixes
	 *
	 *
	 * @param string $url The url to parse
	 * @return A parsed url
	 */
	static function url($url)
	{
		// If array controller, actions, params
		if(is_array($url)) {
			$ctrl = isset($url['controller']) ? $url['controller'] : 'content';
			$action = isset($url['action']) ? $url['action'] : 'index';
			$params = array();
			foreach ($url as $key => $value) {
				if($key != 'controller' && $key != 'action') {
					$params[] = $value;
				}
			}
			return App::host().$ctrl.'/'.$action.'/'.implode('/', $params);
		}


		// Reformated urls
		foreach(self::$routes as $v)
		{
			if(preg_match($v['origin'], $url, $match))
			{
				foreach($match as $k => $value)
				{
					if(!is_numeric($k))
					{
						$v['redir'] = preg_replace("/:$k/", $value, $v['redir']);
					}
				}
				return App::host().$v['redir'];
			}
		}
		
		// Prefixes
		foreach(self::$prefixes as $k => $v)
		{
			if(strpos($url, $v) === 0)
				$url = str_replace($v, $k, $url);
		}
		return App::host().$url;
	}
}


?>