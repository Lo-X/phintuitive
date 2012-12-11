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

require_once('form/Input.php');


class FormHelper extends Helper {

	private $name = '';

	public function __construct(Request $request)
	{
		parent::__construct($request);
		$this->request = $request;
	}


	public function set($data)
	{
		if(is_array($data))
			$this->data = current($data);
	}

	public function setErrors($errors)
	{
		if(is_array($errors))
			$this->errors = $errors;
	}


	private function _getFieldByName($name)
	{
		// If a value has been set
		$value = '';
		if(isset($this->data[$this->model][$name]))
			$value = $this->data[$this->model][$name];

		// If there's an error for that field
		$error = '';
		if(isset($this->errors[$name]))
			$error = $this->errors[$name];

		switch($name)
		{
			case 'content':
				return new Input($name, $this->model, array('type' => 'textarea', 'errorMessage' => $error, 'value' => $value, 'rules' => array()));
			break;

			case 'id';
				return new Input($name, $this->model, array('type' => 'hidden', 'errorMessage' => $error, 'value' => $value, 'rules' => array()));
			break;

			case 'name':
			case 'title':
				return new Input($name, $this->model, array('type' => 'text', 'errorMessage' => $error, 'value' => $value, 'rules' => array()));
			break;

			case 'password':
			case 'passwd':
			case 'pass':
				return new Input($name, $this->model, array('type' => 'password', 'errorMessage' => $error, 'value' => $value, 'rules' => array()));
			break;

			case 'email':
			case 'mail':
				return new Input($name, $this->model, array('type' => 'email', 'errorMessage' => $error, 'value' => $value, 'rules' => array('rule' => 'email')));
			break;

			case 'url':
			case 'site':
			case 'website':
			case 'link':
				return new Input($name, $this->model, array('type' => 'url', 'errorMessage' => $error, 'value' => $value, 'rules' => array('rule' => 'url')));
			break;

			case 'online':
			case 'show':
				return new Input($name, $this->model, array('type' => 'checkbox', 'errorMessage' => $error, 'value' => $value, 'rules' => array()));
			break;

			case 'type':
			case 'category':
				return new Input($name, $this->model, array('type' => 'select', 'errorMessage' => $error, 'value' => $value, 'rules' => array()));
			break;


			default:
				return new Input($name, $this->model, array('type' => 'text', 'errorMessage' => $error, 'value' => $value, 'rules' => array()));
			break;
		}
	}



	public function start($model = null, $method = 'post', $action = null, $name = null)
	{
		// Form auto construction
		// Get the model
		if($model)
		{
			$this->model = $model;
			if(isset($this->data))
			{
				if(isset($this->data[$model]['id'])) {
					$this->name = 'Edit'.$model;
					$this->action = strtolower($model).'s/edit/';
				}
				else {
					$this->name = 'Add'.$model;
					$this->action = strtolower($model).'s/add/';
				}
			}
		}
		else
			$this->model = 'POST';

		if($name)
			$this->name = $name;
		
		if($action)
			$this->action = $action;


		$r = '';
		$r .= '<div class="form">';
		$r .= '	<form method="'.$method.'"';
		$r .= empty($this->action) ? '' : 'action="'.$this->action.'"';
		$r .= 'name="'.$this->name.'">';
		return $r;
	}

	public function end($submitValue = 'Post')
	{
		$sub = new Input('submit', $this->model, array('type' => 'submit', 'value'=>$submitValue));
		$r = '';
		$r .= $sub->tag();
		$r .= '</form></div>';
		return $r;
	}

	public function add($name, $options = array())
	{
		// If there's no options, try to do an automatic thing
		if(empty($options))
		{
			$this->fields[$name] = $this->_getFieldByName($name);
		}
		else  // Else we create it with given options
		{
			$this->fields[$name] = new Input($name, $options);
		}

		$r = $this->fields[$name]->tag();
		return $r;
	}

}