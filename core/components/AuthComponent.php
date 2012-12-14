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


class AuthComponent extends Component {

	public function setRank($rank)
	{
		$_SESSION['rank'] = $rank;
	}

	public function rank()
	{
		return $_SESSION['rank'];
	}

	public function allow($array)
	{
		if(!is_array($array))
			return ($this->rank() == $array);

		if(!in_array($this->rank(), $array))
			return false;

		return true;
	}

	public function deny($array)
	{
		if(!is_array($array))
			return ($this->rank() != $array);

		if(in_array($this->rank(), $array))
			return false;

		return true;
	}

}