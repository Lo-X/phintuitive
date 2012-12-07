<?php


class ContentController extends Controller
{
	protected $components = array('Session');
	
	public function index()
	{
		$title = 'Welcome on PhIntuitive !';
		$content = 'Thank you for trying it !';
		
		$this->set('title', $title);
		$this->set('content', $content);
	}
}
