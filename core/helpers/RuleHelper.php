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





class RuleHelper extends Helper {


	public static function check($value, $rules)
	{
		if(isset($rules['required']) && empty($value))
			return false;

		if(isset($rules['minLength']) && strlen($value) < $rules['minLength'])
			return false;

		if(isset($rules['maxLength']) && strlen($value) > $rules['maxLength'])
			return false;
					
		if(isset($rules['minValue']) && $value < $rules['minValue'])
			return false;

		if(isset($rules['maxValue']) && $value > $rules['maxValue'])
			return false;

		if(isset($rules['rule']))
		{
			switch($rules['rule'])
			{
				case 'alphaNumeric':
					if(!preg_match('!^([a-zA-Z0-9]+)$!', $value))
						return false;
				break;

				case 'phone':
					if(!preg_match(Config::$phoneRegexp, $value))
						return false;
				break;

				case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL))
						return false;
				break;

				case 'number':
				case 'numeric':
					if(!is_numeric($value))
						return false;
				break;

				case 'ip':
					if(!filter_var($value, FILTER_VALIDATE_IP))
						return false;
				break;

				case 'url':
					if(!filter_var($value, FILTER_VALIDATE_URL))
						return false;
				break;

				default:
					if(!preg_match('!^'.$rules['rule'].'$!', $value))
						return false;
				break;
			}
		}

		return true;
	}
}