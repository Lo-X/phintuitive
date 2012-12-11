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

/**
 * This is the most important class with Dispatcher.
 * A Request contains everything you need to know about what led the user to the the final view
 */
class Request {
	
	public $url;
	public $prefix = '';
	
	public function __construct() {
		if(isset($_SERVER['REQUEST_URI']))
			$this->url = $_SERVER['REQUEST_URI'];
		else
			$this->url = '';

		if(isset($_POST))
			$this->data = $_POST;
		else
			$this->data = array();
	}
	
}


?>