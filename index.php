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


define ('HOST', 'http://'.$_SERVER['HTTP_HOST'].'/');	// The domain url
define ('ROOT', dirname(__FILE__).'/');					// Phintuitive root
define ('APP_DIR', 'app/');						// The app directory name
define ('CORE_DIR', 'core/');						// The core directory name
define ('LIBS_DIR', 'libs/');						// The libs directory name


// We're at wrong place here, need to go into app/ dir
require ('app/index.php');
