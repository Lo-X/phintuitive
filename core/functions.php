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
	if(Config::$debug <= 0)	return;
	
	$debug = debug_backtrace();
	echo '<p><a><strong>'.$debug[0]['file'].'</strong> ligne '.$debug[0]['line'].'</a></p>';
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function toDate($string) {
	// Transforme une string date format SQL (anglais) en quelque chose de plus lisible (français) en retirant l'heure
	$array = array();
	$copy = $string;
	$string = substr($string, 0, -9);
	$array = explode('-', $string);

	if(count($array) == 1) return;
	
	$txt = $array[2].'/'.$array[1].'/'.$array[0];
	
	return $txt;
}

function toEnglishDate($string) {
	// Transforme une string date format français en anglais
	$array = array();
	$copy = $string;
	$array = explode('/', $string);

	if(count($array) == 1) return $copy;
	
	$txt = $array[2].'-'.$array[1].'-'.$array[0];
	
	return $txt;
}

function toDateWithHour($string) {
	// Transforme une string date format SQL (anglais) en quelque chose de plus lisible (français) en retirant l'heure
	$date = strtotime($string);
	
	return date('d/m/Y H:i:s', $date);
}

function toTextDate($date)
{
	$r = "";
	$date = strtotime($date);
	$days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
	$mounths = array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');

	$r = $days[date('N', $date)-1];
	$r .= ' '.date('d', $date);
	$r .= ' '.$mounths[(date('n', $date)-1)];

	return $r;
}

function toReadableMinAge($age)
{
	if($age == 0)
		return 'Tout public';

	return 'D&egrave;s '.$age.' ans';
}

function toReadablePrice($price)
{
	if($price == 0)
		return 'Gratuit';

	return $price.' &euro;';
}

function toMiniCalendarDate($date)
{
	$mounths = array('JAN', 'FEV', 'MARS', 'AVR', 'MAI', 'JUIN', 'JUIL', 'AOUT', 'SEPT', 'OCT', 'NOV', 'DEC');
	
	$r = '<span class="mounth">'.$mounths[date('n', strtotime($date))-1].'</span> ';
	$r .= '<span class="day">'.date('d', strtotime($date)).'</span>';
	return $r;
}

function send_mail($to, $subject, $message)
{
	// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: Espace Culturel Jean Ferrat <noreply@espacecultureljeanferrat.fr>' . "\r\n";

	// Envoi
	mail($to, $subject, $message, $headers);
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

/*
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

/**
* @brief Parse un post pour l'arrêter à la coupure
* @param $post Un array contenant toutes les informations en base sur l'article (au moins : content, slug, id)
*/
function phi_parse_post($post)
{
	if(!is_array($post))	return false;
	// Parse le contenu d'un post, le coupe si on trouve un pagebreak et ajoute un permalink pour lire l'article entier
	if(preg_match('#<p><!-- pagebreak --></p>#', $post['content']))
	{
		$excerpt = explode('<p><!-- pagebreak --></p>', $post['content']);
		$excerpt[0] .= '<br /><p align="right"><a href="'.HOST.'/post/'.$post['slug'].'-'.$post['id'].'">Lire la suite &gt;</a></p>';
		return $excerpt[0];
	}
	return $post['content'];
}

/**
* @brief Génère l'adresse du permalink d'un post
* @param $post Un array contenant toutes les informations en base sur l'article (au moins : slug, id)
*/
function phi_permalink($post)
{
	if(!is_array($post))	return false;
	return HOST.'/post/'.$post['slug'].'-'.$post['id'];
}


/**
* @brief Utile pour savoir si le client courrant possède les droits d'administration, sans avoir passer par la classe Session
* @return True si le client est admin, false sinon
*/
function phi_is_admin()
{
	$session = new Session;
	return $session->isAdmin();
}

/**
* @brief Utile pour savoir si le client courrant possède les droits de modération, sans avoir passer par la classe Session
* @return True si le client est modérateur, false sinon
*/
function phi_is_mod()
{
	$session = new Session;
	return $session->isMod();
}

/**
* @brief Retourne le menu demandé en paramètre (1 requête SQL)
* @param $menuid L'ID du menu à afficher
* @return Le menu à echo
*/
function phi_menu($menuid)
{
	// Appel et création du modèle :
	require_once('modules/menus/models/Menu.php');
	$model = new MenuElement;
	$elements = $model->find(array('conditions' => "menuid='$menuid' ORDER BY position ASC"));

	// Création du retour
	$page = $_SERVER['REQUEST_URI'];
	$menu = '<nav class="nav">';

	foreach ($elements as $elem) {
		if($page == '/'.$elem['link'])
			$menu .= '<span class="navelem navcurrent"><a href="'.Router::url($elem['link']).'">'.$elem['name'].'</a></span>';
		else
			$menu .= '<span class="navelem"><a href="'.Router::url($elem['link']).'">'.$elem['name'].'</a></span>';
	}

	$menu .= '</nav>';
	return $menu;
}

/**
* @brief Retourne le menu demandé en paramètre (1 requête SQL)
* @param $menuid L'ID du menu à afficher
* @return Le menu à echo
*/
function phi_menu_list($menuid)
{
	// Appel et création du modèle :
	require_once('modules/menus/models/Menu.php');
	$model = new MenuElement;
	$elements = $model->find(array('conditions' => "menuid='$menuid' ORDER BY position ASC"));

	// Création du retour
	$page = $_SERVER['REQUEST_URI'];
	$menu = '<nav class="nav" id="menu">';
	$menu .= '<ul>';
	foreach ($elements as $elem) {
		if($page == '/'.$elem['link'])
			$menu .= '<li><a href="'.Router::url($elem['link']).'">'.$elem['name'].'</a></li>';
		else
			$menu .= '<li><a href="'.Router::url($elem['link']).'">'.$elem['name'].'</a></li>';
	}
	$menu .= '</ul>';
	$menu .= '</nav>';
	return $menu;
}