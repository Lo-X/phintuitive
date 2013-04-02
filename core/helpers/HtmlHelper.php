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
 * HTML Helper is useful to add html tags easily.
 *
 * You can have a lot of "head" tags with HtmlHelper methods and a few "body" tags like "img" and "a". You do not need to worry about the path for links, 
 * the Router will automatically be called and will generate correct links.
 */
class HtmlHelper extends Helper
{
	protected $doctypes = array('html5'		   => '<!DOCTYPE html>',
								'xhtml-strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
								);

	protected $tags = array('meta' => '<meta %s />',
							'link' => '<a href="%s"%s>%s</a>',
							'image'=> '<img src="%s"%s />',
							'css'  => '<link rel="stylesheet" type="text/css" href="%s" />',
							'charset' => '<meta http-equiv="Content-Type" content="text/html; charset=%s" />',
							'js'   => '<script type="text/javascript" src="%s"></script>',
							'jquery' => '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/%s/jquery.min.js"></script>');



	public function doctype($type = 'html5')
	{
		return $this->doctypes[$type]."\n";
	}

	public function charset($type = 'utf-8')
	{
		return sprintf($this->tags['charset'], $type)."\n";
	}

	public function robots($content)
	{
		$meta = 'name="robots" content="'.$content.'"';
		return sprintf($this->tags['meta'], $meta)."\n";
	}

	public function description($description)
	{
		$meta = 'name="description" content="'.$description.'"';
		return sprintf($this->tags['meta'], $meta)."\n";
	}

	public function keywords($keywords)
	{
		$meta = 'name="keywords" content="'.$keywords.'"';
		return sprintf($this->tags['meta'], $meta)."\n";
	}

	public function css($file)
	{
		if(preg_match('!http://!', $file))
			return sprintf($this->tags['css'], $file)."\n";

		if($file[0] == '/')
			return sprintf($this->tags['css'], App::host().$file)."\n";

		return sprintf($this->tags['css'], App::host().'/'.App::themes().'/'.$file)."\n";
	}

	public function jQuery($version = '1.8')
	{
		return sprintf($this->tags['jquery'], $version)."\n";
	}

	public function js($file)
	{
		if(preg_match('!http://!', $file))
			return sprintf($this->tags['js'], $file)."\n";

		if($file[0] == '/')
			return sprintf($this->tags['css'], App::host().$file)."\n";

		return sprintf($this->tags['css'], App::host().'/themes/js/'.$file)."\n";
	}

	public function link($url, $title = null, $target = null, $confirm = null)
	{
		if(preg_match('!http://!', $url))
			$url = $url;
		elseif($url[0] == '/')
			$url = App::host().$url;
		else
			$url = Router::url($url);

		$others = '';
		if($target)	$others .= ' target="'.$target.'"';
		if($confirm)
		{
			$confirm = str_replace("'", "\'", $confirm);
			$confirm = str_replace('"', '\"', $confirm);
			$others .= ' onclick="return confirm(\''.$confirm.'\')"';
		}


		return sprintf($this->tags['link'], $url, $others, $title);
	}

	public function img($url, $alt = null, $title = null)
	{
		if(preg_match('!http://!', $url))
			$url = $url;
		elseif($url[0] == '/')
			$url = App::host().$url;
		else
			$url = Router::url($url);

		$others = '';
		if($alt)	$others .= ' alt="'.$alt.'"';
		if($title)  $others .= ' title="'.$title.'"';


		return sprintf($this->tags['image'], $url, $others);
	}


}