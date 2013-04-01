<?php
/*
*	PhIntuitive - Fast websites development framework
*	Copyright 2012, Boutter Loïc - http://loicboutter.fr
*
*	Licensed under The MIT License
*	Redistributions of files must retain the above copyright notice.
*
*	@copyright Copyright 2012, Boutter Loïc - http://loicboutter.fr
*	@author Boutter Loïc
*	@version 2.0.0
*/

// We define some usefull constants
define ('HOST', 'http://'.$_SERVER['HTTP_HOST'].'/'.Config::installationPath());	// The domain url
define ('THEME_DIR', 'themes/'.Config::theme());	// The theme directory name
define ('CORE_DIR', 'core');						// The core directory name
define ('APP_DIR', 'app');							// The app directory name
define ('HELPERS_DIR', 'helpers');					// The helpers directory name
define ('COMPONENTS_DIR', 'components');			// The components directory name
define ('JS_DIR', 'js');							// The JS folder directory name




/**
 *	The App class provide useful methods to reach folder or specific files.
 *	It also allow you to include specific Controllers, Models, Components or Helpers.
 *	You doesn't need to know exactly the folder architecture since App does it for you
 *
 *	@see Router
 */
class App
{

	/**
	 * This is a useful method to load any kind of classes you need (Controllers, Models, Components or Helpers).
	 * This will include (php: require_once) the element you need
	 *
	 * @param string $name The name of the element you need to include without the completion element (ie: if you want to load ApplesController of module apples, call App::load('Apples', 'controller'); - Same for components and helpers ). Beware of the string case !
	 * @param string $type The type of element you want to load, possible values are : controller, model, component, helper
	 * @return That method returns true if the requested element has been loaded, else return false
	 */
	public static function load($name, $type = 'model')
	{
		// Avoid case problems
		$type = strtolower($type);


		// Switch on type to know what the user want
		switch($type)
		{
			case 'controller':
				// Add the 'Controller' in the name
				$name = ucfirst($name.'Controller');

				// Get the controller filepath
				$file = App::appDir().'/controllers/'.$name.'.php';
				
				// Include it or die
				if(file_exists($file)) {
					require_once($file);
					return true;
				}
				else
					return false;
			break;

			case 'model':
				// First caracter is upper case
				$name = ucfirst($name);

				// Get the model filepath
				$file = App::appDir().'/models/'.$name.'.php';
				
				// Include it or die
				if(file_exists($file)) {
					require_once($file);
					return true;
				}
				else
					return false;
			break;

			case 'component';
				// Add the 'Component' in the name
				$name = ucfirst($name.'Component');

				// Get the component filepath
				$file = App::components().'/'.$name.'.php';

				// Include it or die
				if(file_exists($file)) {
					require_once($file);
					return true;
				}
				else
					return false;
			break;

			case 'helper':
				// Add the 'Helper' in the name
				$name = ucfirst($name.'Helper');

				// Get the helper filepath
				$file = App::helpers().'/'.$name.'.php';

				// Include it or die
				if(file_exists($file)) {
					require_once($file);
					return true;
				}
				else
					return false;
			break;

			default:break;
		}
	}


	/**
	 * Returns the filepath to the view corresponding to the given parameters module and view
	 *
	 * @param string $module The module from Request object
	 * @param string $view   The view (action name) you want to expose
	 * @return Reurns the filepath to the view
	 */
	public static function getView($module, $view)
	{
		return App::appDir().'/views/'.$module.'/'.$view.'.php';
	}

	/**
	 * Returns the filepath to the layout corresponding to the given parameter layout
	 *
	 * @param string $layout The layout you want to expose
	 * @return Reurns the filepath to the layout
	 */
	public static function getLayout($layout)
	{
		return App::themes().'/'.$layout.'.php';
	}


	/**
	 *	Return the host url without te final slash
	 *	
	 * 	@return The host url, ie: http://localhost
	 */
	public static function host()
	{
		return HOST;
	}

	/**
	 *	Returns the root folder
	 *
	 *	@return The root folder, ie: /
	 */
	public static function root()
	{
		return '/';
	}

	/**
	*	Returns the core directory name
	*
	*	@return Default: core
	*/
	public static function coreDir()
	{
		return CORE_DIR;
	}

	/**
	*	Returns the core directory name
	*
	*	@return Default: core
	*/
	public static function core()
	{
		return CORE_DIR;
	}

	/**
	*	Returns the app directory name
	*
	*	@return Default: app
	*/
	public static function appDir()
	{
		return APP_DIR;
	}

	/**
	*	Returns the cache directory name
	*
	*	@return Default: cache
	*/
	public static function cacheDir()
	{
		return App::appDir().'/cache';
	}

	/**
	*	Returns the cache directory name
	*
	*	@return Default: cache
	*/
	public static function cache()
	{
		return App::appDir().'/cache';
	}

	/**
	*	Returns the helpers directory name
	*
	*	@return Default: core/helpers
	*/
	public static function helpersDir()
	{
		return App::core().'/'.HELPERS_DIR;
	}

	/**
	*	Returns the helpers directory name
	*
	*	@return Default: core/helpers
	*/
	public static function helpers()
	{
		return App::core().'/'.HELPERS_DIR;
	}

	/**
	*	Returns the components directory name
	*
	*	@return Default: core/components
	*/
	public static function componentsDir()
	{
		return App::core().'/'.COMPONENTS_DIR;
	}

	/**
	*	Returns the components directory name
	*
	*	@return Default: core/components
	*/
	public static function components()
	{
		return App::core().'/'.COMPONENTS_DIR;
	}

	/**
	*	Returns the theme directory name as it's set in Config class
	*
	*	@return Default: themes/default
	*	@see Config::$theme
	*/
	public static function themesDir()
	{
		return THEME_DIR;
	}

	/**
	*	Returns the theme directory name as it's set in Config class
	*
	*	@return Default: themes/default
	*	@see Config::$theme
	*/
	public static function themes()
	{
		return THEME_DIR;
	}

	/**
	*	Returns the javascript directory name
	*
	*	@return Default: js
	*/
	public static function jsDir()
	{
		return JS_DIR;
	}

	/**
	*	Returns the javascript directory name
	*
	*	@return Default: js
	*/
	public static function js()
	{
		return JS_DIR;
	}


}