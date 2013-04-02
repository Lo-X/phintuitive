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
 * This is a useful class to format dates.
 *
 * There is a handy method to let you format any string date into another string date, plus two handy methods to create an SQL-formated date and to format a date into a easy readable string (that you an configure in the config.php file).
 */
class TimeHelper extends Helper {


	/**
	 * This function format or create a date in SQL-formated date ('Y-m-d H:i:s')
	 *
	 * @param timestamp $timestamp A timestamp (UNIX-date) that you can get with php function time(). If empty, take actual time as timestamp.
	 * @return Returns the SQL-formated date from timestamp
	 */
	public static function toSqlDate($timestamp = null)
	{
		if(!$timestamp)
			$timestamp = time();

		return date('Y-m-d H:i:s', $timestamp);
	}

	
	/**
	 * This function format or create a date in the format defined in config.php file.
	 *
	 * @param date $date A date (see php date formats) to change into the config format date. If empty, take actual date.
	 * @return Returns the formated date
	 */
	public static function toStringDate($date = null)
	{
		$r = Config::$stringDateFormat;
		if($date)
			$date = strtotime($date);
		else
			$date = time();

		$day = Config::$days[date('N', $date)-1];
		$mounth = Config::$mounths[(date('n', $date)-1)];

		$r = preg_replace('!{day}!', $day, $r);
		$r = preg_replace('!{mounth}!', $mounth, $r);
		$r = strftime($r, $date);

		return $r;
	}

	

	/**
	 * This function format a date from another date
	 *
	 * @param date $date The date tore-format
	 * @param string $format The php date format to apply (ie: d/m/Y)
	 * @return Returns the formated date
	 */
	public static function format($date, $format)
	{
		$date = strtotime($date);
		return date($format, $date);
	}

}