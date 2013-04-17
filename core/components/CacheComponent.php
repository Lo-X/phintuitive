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



require_once('cache/FileCache.php');

class CacheComponent extends FileCache
{
	protected $duration = 1;
	private $request;


	public function __construct($request, $settings = array())
	{
		$this->request = $request;
		$this->filepath = $this->_filename();
	}

	public function setDuration($duration)
	{
		$this->duration = $duration;
	}

	public function clear($filter = null)
	{
		$filter = $this->request->module.'_';
		parent::clear($filter);
	}


	protected function _filename()
	{
		return App::cache().'/'.$this->request->module.'_'.$this->request->action.'.cache';
	}
}



