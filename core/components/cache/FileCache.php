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

require_once('Cachable.php');

class FileCache implements Cachable
{
	protected $filepath;
	protected $duration = 1;
	protected $filters = array();


	public function write($content)
	{
		return file_put_contents($this->filepath, $content);
	}

	public function upToDate($outdate = null)
	{
		if(!file_exists($this->filepath))
			return false;

		// Lifetime in minutes
		$duration = $this->duration;
		if($outdate)
			$duration = $outdate;

		$lifetime = (time() - filemtime($this->filepath)) / 60;

		if($lifetime > $duration)
			return false;

		return true;
	}

	public function read()
	{
		return $this->_applyFilters(file_get_contents($this->filepath));
	}

	private function _applyFilters($content)
	{
		foreach ($this->filters as $key => $value) {
			$content = preg_replace('!{{'.$key.'}}!isU', $value, $content);
		}
		return $content;
	}

	public function delete()
	{
		if(file_exists($this->filepath))
			unlink($this->filepath);
	}

	public function clear($filter)
	{
		$files = glob(App::cache().'/*');
		debug($files);
		foreach ($files as $file) {
			if(preg_match('!'.$filter.'$!', $file))
				unlink($file);
		}
	}

	
	public function addFilters($filters)
	{
		$this->filters = array_merge($this->filters, $filters);
	}

	
}