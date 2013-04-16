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


if(!defined('HOST')) {
	define ('HOST', 'http://'.$_SERVER['HTTP_HOST'].'/');	// The domain url
}

if(!defined('ROOT')) {
	define ('ROOT', dirname(dirname(__FILE__)).'/');	// Phintuitive root
}

if(!defined('APP_DIR')) {
	define ('APP_DIR', ROOT.'app/');	// The app directory name
}

if(!defined('CORE_DIR')) {
	define ('CORE_DIR', ROOT.'core/');	// The core directory name
}

if(!defined('LIBS_DIR')) {
	define ('LIBS_DIR', ROOT.'libs/');	// The libs directory name
}



// First of all we need the configurations
require CORE_DIR.'Config.php';

// Then we need the App class
require CORE_DIR.'App.php';

// If we are in debug mode, we use a constant to known how fast (or slow) the page generation is
if(Config::debug()) define ('BEGIN_TIME', microtime(true));


// Start the Session
session_start();




// This will include all core files that we need to continue
require (App::core().'/includes.php');



// The Dispatcher will create the Request, that will contain prefix controller, action, POST informations, etc...
// and then call the requested Controller action and rendering.
new Dispatcher();

