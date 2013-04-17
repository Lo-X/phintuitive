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
* Sets the installation path of PhIntuitive.
* This path is very important in case you do not install PhIntuitive at the root of your website
*/
Config::set('installationPath', 'phintuitive');

/**
* Sets if your poject is in debug mode or not. While in debug (value= true) mode, you can have errors or debug messages printed onto your website.
* To deactivate the debug mode, set debug to false.
*/
Config::set('debug', true);

/**
* Activate or deactivate the caching for layouts.
*/
Config::set('cacheLayouts', false);

/**
* This is the security salt used to register passwords in database or for sessions. You should modify this salt ONCE, at the installation of this framework.
*/
Config::set('securitySalt', '6mghwbckz8t5kqn6yywnoq4v313v&xa5tgobz5tr2p8owxe12v');

/**
* Sets what layout is the default layout you want to use. A layout can be changed in Controllers as well.
*/
Config::set('layout', 'default');

/**
* These configurations set information about your email. This is used by Email Helper in order to send you emails. You may want to modify these values.
*/
Config::set('email', 'contact@email.com');
Config::set('emailName', 'PhIntuitive');


