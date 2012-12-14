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
	static $cacheLayout = false;
	
	static $databases = array(
			'default' => array(
						'host' 		=> 	'localhost',
						'database'	=>	'phintuitive',
						'login'		=>	'root',
						'password'	=>	'',
						'prefix'	=>	''
				)
	);

	static $email = 'contact@loicboutter.fr';
	static $emailName = 'PhIntuitive';

// @todo : To change with some kind of localisation class (used in helpers/form/Rule.php)
	static $phoneRegexp = '!^([0-9]{2}(\.){0,1}){5}$!';

	static $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
	static $mounths = array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
	static $dateFormat = 'd/m/Y H:i:s';
	static $stringDateFormat = '{day} %d {mounth}';

}