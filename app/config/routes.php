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


// Prefixes
	/*
		You can comment/uncomment/modify this prefix to set the admin methods prefix
		This can avoid some hack attempts by hidding the real path to your admin panel.
	*/
	Router::prefix('cockpit', 'admin');


// Connections
	/*
	This connect a well (eye-visitor) formated URL for the website root
	*/
	//Router::connect('^$', 'posts/index/');


	/*
	This connect a well (eye-visitor) formated URL for posts
	*/
	Router::connect('post/:slug-:id', 'posts/view/id:([0-9]+)/slug:([a-zA-Z0-9\-]+)');

	/*
	Helo World !
	*/
	//Router::connect('helloworld/:hello-:world', 'tests/toto/hello:([a-zA-Z0-9\-]+)/world:([a-zA-Z0-9\-]+)');