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
* This is a regular expression that matches phone numbers in your country.
* Default: french phone numbers
*/
Config::set('phoneRegexp', '!^([0-9]{2}(\.){0,1}){5}$!');

/**
* This is an array containing week days, begining with 'Monday'.
* Default: french days
*/
Config::set('days', array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'));

/**
* This is an array containing the months, begining with 'january'.
* Default: french months
*/
Config::set('months', array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre'));

/**
* This is a string describing how the dates should be displayed. You can use php symbols and letters (see: php date documentation).
*/
Config::set('dateFormat', 'd/m/Y H:i:s');

/**
* In order to localize your website, you can alter that string to display plain text dates.
* Options : {day} is replaced by the day string, accordingly to the day array above,
*			{month} is replaced by the month string, accordingly to the month array above,
*			%d is replaced by the day number
*/
Config::set('stringDateFormat', '{day} %d {month}');

