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
		return $this->doctypes[$type];
	}

	public function charset($type = 'utf-8')
	{
		return sprintf($this->tags['charset'], $type);
	}

	public function css($file)
	{
		if(preg_match('!http://!', $file))
			return sprintf($this->tags['css'], $file);

		if($file[0] == '/')
			return sprintf($this->tags['css'], App::host().$file);

		return sprintf($this->tags['css'], App::host().'/'.App::themes().'/'.$file);
	}

	public function jQuery($version = '1.8')
	{
		return sprintf($this->tags['jquery'], $version);
	}

	public function js($file)
	{
		if(preg_match('!http://!', $file))
			return sprintf($this->tags['js'], $file);

		if($file[0] == '/')
			return sprintf($this->tags['css'], App::host().$file);

		return sprintf($this->tags['css'], App::host().'/themes/js/'.$file);
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