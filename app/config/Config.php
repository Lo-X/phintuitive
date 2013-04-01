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
* @brief This class handle configurations options. The configurations are set in "hard" into many files in the app/config/ folder.
* @details This class mainly hae two methods that allow you to set/modify configurations options or to get them back.
* To add you own configuration file, you need to add it into the app/config/ folder and then require it at the end of the app/config/Config.php file.
*/
class Config {

	private static $configurations = array(); ///< Array containing all configurations

	/**
	* This method allow anyone to set/modify a configuration option to any value. 
	* For further information about configuration options, please read the documentation.
	*
	* @param $option The option to set/modify (ie: 'debug')
	* @param $value The value for the given option. Can be a raw value or an array.
	*/
	public static set($option, $value) {
		$configurations[$option] = $value;
	}


	/**
	* This method allow anyone to get a value for the given configuration option.
	* For further information about configuration options, please read the documentation.
	* 
	* @param $option The option you want its value (ie: 'debug')
	*/
	public static get($option) {
		if(in_array($option, $configurations)) {
			return $configurations[$option];
		}


		echo '<p><b>Beware : </b> You request the configuration "<i>'.$config.'</i>" that hasn\'t been set ! Empty value returned !</p>';
		return '';
	}


}

// Configuration files needed by PhIntuitive
require('core.php');
require('database.php');
require('localization.php');

// Here you can add your own configuration files :
