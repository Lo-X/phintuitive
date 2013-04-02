<?php
/*
*	PhIntuitive - Fast websites development framework
*	Copyright 2013, Boutter Loïc - http://loicboutter.fr
*
*	Licensed under The MIT License
*	Redistributions of files must retain the above copyright notice.
*
*	@copyright Copyright 2013, Boutter Loïc - http://loicboutter.fr
*	@author Boutter Loïc
*	@version 2.0.0
*/

class ContentController extends Controller {
	
	public function index()
	{
		$title = 'Welcome on PhIntuitive !';
		$content = 'Thank you for trying it !';

		$this->set('title', $title);
		$this->set('content', $content);	
	}
	
	
}
