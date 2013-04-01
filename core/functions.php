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
 * Author : Grafikart   Website : www.grafikart.fr
 * Permet de cropper une image au format png/jpg et gif au format souhaité
 *
 * Si la largeur ou la hauteur est mise à 0 la dimension sera automatiquement calculé
 * de manière à garder le ratio de l'image
 *
 * @param string $img Fichier image d'origine (doit avoir une extension)
 * @param string $dest Fichier de destination (avec l'extension .jpg)
 * @param integer $largeur Largeur de l'image en sortie
 * @param integer $hauteur Hauteur de l'image en sortie
 */
function crop($img,$dest,$largeur=0,$hauteur=0){
        $dimension=getimagesize($img);
        $ratio = $dimension[0] / $dimension[1];
        // Création des miniatures
        if($largeur==0 && $hauteur==0){ $largeur = $dimension[0]; $hauteur = $dimension[1]; }
          else if($hauteur==0){ $hauteur = round($largeur / $ratio); }
        else if($largeur==0){ $largeur = round($hauteur * $ratio); }
  
        if($dimension[0]>($largeur/$hauteur)*$dimension[1] ){ $dimY=$hauteur; $dimX=round($hauteur*$dimension[0]/$dimension[1]); $decalX=($dimX-$largeur)/2; $decalY=0;}
        if($dimension[0]<($largeur/$hauteur)*$dimension[1]){ $dimX=$largeur; $dimY=round($largeur*$dimension[1]/$dimension[0]); $decalY=($dimY-$hauteur)/2; $decalX=0;}
        if($dimension[0]==($largeur/$hauteur)*$dimension[1]){ $dimX=$largeur; $dimY=$hauteur; $decalX=0; $decalY=0;}
        $miniature =imagecreatetruecolor ($largeur,$hauteur);
        $ext = end(explode('.',$img)); 
        if(in_array($ext,array('jpeg','jpg','JPG','JPEG'))){$image = imagecreatefromjpeg($img); }
        elseif(in_array($ext,array('png','PNG'))){$image = imagecreatefrompng($img); }
        elseif(in_array($ext,array('gif','GIF'))){$image = imagecreatefromgif($img); }
        else{ return false; }
        imagecopyresampled($miniature,$image,-$decalX,-$decalY,0,0,$dimX,$dimY,$dimension[0],$dimension[1]);
        imagejpeg($miniature,$dest,90);
          
        return true;
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