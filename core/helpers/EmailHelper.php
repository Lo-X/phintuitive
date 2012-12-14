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



class EmailHelper extends Helper {


	public $fromEmail = '';
	public $fromName = '';
	public $to = '';
	public $subject = '';
	public $message = '';

	public function __construct(Request $request = null)
	{
		parent::__construct($request);

		$this->fromEmail = Config::$email;
		$this->fromName = Config::$emailName;
		$this->to = Config::$email;
		$this->subject = 'Contact';
	}

	public function send()
	{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: '.$this->fromName.' <'.$this->fromEmail.'>' . "\r\n";

		// Envoi
		return mail($this->to, $this->subject, $this->message, $headers);
	}

	public function from($name, $email)
	{
		$this->fromName = $name;
		$this->fromEmail = $email;
	}

	public function to($email)
	{
		$this->to = $email;
	}

	public function subject($subject)
	{
		$this->subject = $subject;
	}

	public function message($text)
	{
		$this->message = $text;
	}


}