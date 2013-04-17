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
 * @brief This is the most important class with Dispatcher and Router.
 * @details A Request contains everything you need to know about what led the user to the the final view
 *
 * @see Dispatcher, Router
 */
class Request {
	
	public $url;			///< Contains the URL asked by the visitor
	public $prefix = '';	///< Contains the prefix used. The prefix is before the controller and the action
	
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