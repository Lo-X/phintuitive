<?php
/*
*    PhIntuitive - Fast websites development framework
*    Copyright 2013, Boutter Loïc - http://loicboutter.fr
*
*    Licensed under The MIT License
*    Redistributions of files must retain the above copyright notice.
*
*    @copyright Copyright 2013, Boutter Loïc - http://loicboutter.fr
*    @author Boutter Loïc
*    @version 2.0.0
*/

function debug($var)
{
	if(!Config::debug())	return;
	
	$debug = debug_backtrace();
	echo '<p><a><strong>'.$debug[0]['file'].'</strong> line '.$debug[0]['line'].'</a></p>';
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function callstack()
{
	if(!Config::debug())	return;
	
	$debug = debug_backtrace();
	echo '<p><a><strong>Call stack :</strong></a></p>';
	echo '<pre>';
	foreach ($debug as $call) {
		echo $call['function'].'	in 	'.$call['file'].' 	line 	'.$call['line'].'
';
	}
	echo '</pre>';
}

/**
* Create a valid slug from a string
*
* @return The slug, formated with the delimiter you want
*/
function createSlug($str, $delimiter = '-') {
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
    $slug = strtolower(trim($slug, '-'));
    $slug = preg_replace("/[\/_|+ -]+/", $delimiter, $slug);
 
    return $slug;
}

/**
 * Author : phpdotnet
 * Permet de trier un array complexe selon le champ voulu
 *
 *
 * @param $array tableau à trier
 * @param $on le champ sur lequel trier
 * @param $order ordre de tri : SORT_ASC ou SORT_DESC
 */
function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}