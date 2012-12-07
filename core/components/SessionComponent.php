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


class SessionComponent extends Component {
	
	
	public function __construct($request = null)
	{
		
	}


	
	public function destroy()
	{
		// Suppression de toutes les variables et destruction de la session
		$_SESSION = array();
		session_destroy();
	}
	
	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	
	public function get($key)
	{
		if(isset($_SESSION[$key]))
			return $_SESSION[$key];
		else
			return false;
	}
	
	public function isLogged()
	{
		if(isset($_SESSION['login']))
			return true;
			
		return false;
	}
	
	public function isUser()
	{
		if(isset($_SESSION['rank']))
		{
			if($_SESSION['rank'] >= 1)	return true;
		}
		return false;
	}
	
	public function isMod()
	{
		if(isset($_SESSION['rank']))
		{
			if($_SESSION['rank'] >= 2)	return true;
		}
		return false;
	}
	
	public function isAdmin()
	{
		if(isset($_SESSION['rank']))
		{
			if($_SESSION['rank'] >= 3)	return true;
		}
		return false;
	}

	

	public function setFlash($text, $level = "error")
	{
		$_SESSION['flash-level'] = $level;
		$_SESSION['flash-content'] = $text;
	}

	public function flash()
	{
		if(isset($_SESSION['flash-content']))
			echo '<div class="alert alert-'.$_SESSION['flash-level'].'"><span class="close"><a href="#">x</a></span>'.$_SESSION['flash-content'].'</div>';
		unset($_SESSION['flash-content']);
		unset($_SESSION['flash-level']);
	}
	
}