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

class Config {

	static $debug = 1;
	static $theme = 'default';
	static $canCommentPosts = true;
	static $canCommentPages = false;
	static $cacheLayout = 30;
	
	static $databases = array(
			'default' => array(
						'host' 		=> 	'localhost',
						'database'	=>	'phintuitive',
						'login'		=>	'root',
						'password'	=>	'',
						'prefix'	=>	''
				)
	);

}