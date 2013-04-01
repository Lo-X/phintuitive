<?php
/*
*	PhIntuitive - Fast websites development framework
*	Copyright 2012, Boutter Loïc - http://loicboutter.fr
*
*	Licensed under The MIT License
*	Redistributions of files must retain the above copyright notice.
*
*	@copyright Copyright 2013, Boutter Loïc - http://loicboutter.fr
*	@author Boutter Loïc
*	@version 2.0.0
*/


/**
* The databases array contains the different configurations of your SQL database (host, login, password, etc.).
* In fact there is an array for each configuration you need. The only mandatory one is 'default'.
*/
$databases = array(
			'default' => array(
						'host' 		=> 	'localhost',
						'database'	=>	'phintuitive',
						'login'		=>	'root',
						'password'	=>	'',
						'prefix'	=>	''
				)
	);

Config::set('databases', $databases);
