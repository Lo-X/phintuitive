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

require_once('cache/FileCache.php');

class CacheLayoutComponent extends FileCache
{
	protected $duration = 10;


	public function __construct($layout)
	{
		$this->filepath = App::cache().'/'.Config::$theme.'_'.$layout.'.layout.cache';
	}

	public function setDuration($duration)
	{
		$this->duration = $duration;
	}

	public function setLayout($layout)
	{
		$this->filepath = App::cache().'/'.Config::$theme.'_'.$layout.'.layout.cache';
	}

	public function clear($filter = null)
	{
		$filter = $this->request->module.'_';
		parent::clear($filter);
	}

}
