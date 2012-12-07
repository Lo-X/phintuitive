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


// First of all we need the configurations
require ('config.php');

// Then we need the App class
require ('core/App.php');

// If we are in debug mode, we use a constant to known how fast (or slow) the page generation is
if(Config::$debug)
	define ('BEGIN_TIME', microtime(true));


// Start the Session
session_start();




// This will include all core files that we need to continue
require (App::core().'/includes.php');



/*
	Router section

	Here are the Router prefixes and connections for your controllers.
*/
require ('routes.php');



// The Dispatcher will create the Request, that will contain prefix controller, action, POST informations, etc...
// and then call the requested Controller action and rendering.
new Dispatcher();


