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

	public function __construct(Request $request, Model $model = null)
	{
		parent::__construct($request);

		$this->request = $request;
		if(!$request)	return;

		// Form auto construction

		// Get the model
		if($model)
		{
			$this->model = $model;
			// Compare data fields and model fields and Auto create needed fields
			$this->_getFieldsWithModel();
		}
		else
		{
			if($this->_getModel())
			{
				// Compare data fields and model fields and Auto create needed fields
				$this->_getFieldsWithModel();
			}
		}
	}

	private function _getModel()
	{
		if(strrpos($this->request->controller, 's') == strlen($this->request->controller)-1)
			$modelName = ucfirst(substr($this->request->controller, 0, -1));
		else
			$modelName = ucfirst($this->request->controller);

		if(App::load($modelName)) 
		{
			$this->model = new $modelName;
			return true;
		}
		else return false;
	}


	private function _getFieldsWithModel()
	{
		foreach ($this->model->fields as $field) {
			$this->fields[$field] = $this->_getFieldByName($field);
			if(array_key_exists($field, $this->request->data))
			{
				$this->fields[$field]->setValue($this->request->data[$field]);
			}
		}

		if(array_key_exists('id', $this->fields))
		{
			if($this->fields['id']->value() == '')
			{
				unset($this->fields['id']);
				$this->name = 'Add'.get_class($this->model);
			}
			else
			{
				$this->name = 'Edit'.get_class($this->model);
			}
		}
		else
			$this->name = 'Add'.get_class($this->model);
	}

	private function _getFieldByName($name)
	{
		switch($name)
		{
			case 'content':
				return new Input($name, array('type' => 'textarea', 'errorMessage' => '', 'value' => '', 'rules' => array()));
			break;

			case 'id';
				return new Input($name, array('type' => 'hidden', 'errorMessage' => '', 'value' => '', 'rules' => array()));
			break;

			case 'name':
			case 'title':
				return new Input($name, array('type' => 'text', 'errorMessage' => '', 'value' => '', 'rules' => array()));
			break;

			case 'password':
			case 'passwd':
			case 'pass':
				return new Input($name, array('type' => 'password', 'errorMessage' => '', 'value' => '', 'rules' => array()));
			break;

			case 'email':
			case 'mail':
				return new Input($name, array('type' => 'email', 'errorMessage' => '', 'value' => '', 'rules' => array('rule' => 'email')));
			break;

			case 'url':
			case 'site':
			case 'website':
			case 'link':
				return new Input($name, array('type' => 'url', 'errorMessage' => '', 'value' => '', 'rules' => array('rule' => 'url')));
			break;

			case 'online':
			case 'show':
				return new Input($name, array('type' => 'checkbox', 'errorMessage' => '', 'value' => '', 'rules' => array()));
			break;

			case 'type':
			case 'category':
				return new Input($name, array('type' => 'select', 'errorMessage' => '', 'value' => '', 'rules' => array()));
			break;


			default:
				return new Input($name, array('type' => 'text', 'errorMessage' => '', 'value' => '', 'rules' => array()));
			break;
		}
	}



	public function start($method = 'POST', $action = '', $name = null)
	{
		if($name)
			$this->name = $name;

		echo '<div class="form">';
		echo '	<form method="'.$method.'"';
		echo empty($action) ? '' : 'action="'.$action.'"';
		echo 'name="'.$this->name.'">';
	}

	public function end($submitValue = 'Post')
	{
		$sub = new Input('submit', array('type' => 'submit', 'value'=>$submitValue));
		echo $sub->tag();
		echo '</form></div>';
	}

	public function add($name, $options = array())
	{
		// If we already automatically set that field, we update it with options
		if(isset($this->fields[$name]))
		{
			if(!empty($options))
				$this->fields[$name]->setOptions($options);
		}
		else  // Else we create it
		{
			$this->fields[$name] = new Input($name, $options);
		}

		echo $this->fields[$name]->tag();
	}

}