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

/**
 * This is an Helper for easy creation of Form based on Models.
 *
 * You can give standard fields name that will be auto-built with method add() or give a descriptions of the field, classes and rules you want to 
 * apply. You can give dat from a Model to the FormHelper so it will auto fill corresponding fields. You can also give it the invalid fields array 
 * from a Model and these errors will appear in the form.
 *
 * @see start(), add(), end(), set(), setErrors()
 * @code
 * <?php
 * // Creation of the form. Can be done in the Controller if you have data/error to give, or directly in the view
 * $Form = new FormHelper();
 *
 * // Start the form, with model Post
 * echo $Form->start('Post');
 *
 * // Add fields
 * echo $Form->add('title', array('rules' => array('required' => true)));
 * echo $Form->add('content');
 *
 * // End the form with submit ('Add') button
 * echo $Form->end('Add');
 * ?>
 * @endcode
 */
class FormHelper extends Helper {

	private $name = '';


	/**
	 * Give data to the form to display in the fields.
	 *
	 * Data should be in an array similar to :
	 * @code
	 * [{n}] = array
	 * (
	 *		[{Modelname}] = array
	 *		(
	 *			[field1] => value,
	 *			[field2] => value
	 *		)
	 * )
	 * @endcode
	 * Where {n} is any key, {Modelname} is the Model name. Note that Model automatically give that kind of array, well formated. You shouldn't have to 
	 * write your own.
	 *
	 * @param array $data The array of data
	 */
	public function set($data)
	{
		if(is_array($data))
			$this->data = current($data);
	}

	

	/**
	 * Give errors to the form so it can display them in front of corresponding fields
	 *
	 * You will have the err array from Models, with the method Model::invalidFields() like this :
	 * @code
	 * <?php
	 * $errors = $Model->invalidFields();
	 * $Form->setErrors($errors);
	 * ?>
	 * @endcode
	 *
	 * @param array $errors The errors array
	 */
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



	/**
	 * This starts a form with a div and form tags. The form tag attributes will be automatically computed but yo can control them with add() parameters.
	 * If you use the FormHelper with a Modelplease fill the first parameter : you have to specify the name of the Model (ie: Post).
	 *
	 * Here are the different attributes given to the form tag given the specidied parameters :
	 *
	 * Without any parameter, the form tag should look like : <form method="post">
	 *
	 * With the model (1st) parameter only [recommended], and : 
	 * 	- if the 'id' field (Model primary key) has been given : <form method="post" action="/{controller}/edit/" name="{Modelname}Edit">
	 *		- else : <form method="post" action="/{controller}/add/" name="{Modelname}Add">
	 * Where {controller} is the assumed controller name corresponding to the Model and Modelname the Model name.
	 *
	 * You can change the method, action and name with (respectively) the 3 next parameters.
	 *
	 * 
	 * To print the tags in HTML you need tu use explicitely echo : echo $form->start(Mymodelname);
	 *
	 * @param string $model The model name that is directly linked to the form
	 * @param string $method The method used to send the form (post or get), by default: post
	 * @param string $action The page the form will send the informations. Is computed by default if the model name is given (see description)
	 * @param string $name The form name
	 */
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

	
	/**
	 * This method is used to add a submit button and close the form tag (and the global form div).
	 *
	 * The parmeter you can give will be the text on the submit button. Note that unlike fields, the submit button is in a div with class submit.
	 * Default value for the submit button is 'Post'.
	 *
	 * If you do not want to use this method to have a submit button (ie: to have a js button), you will need to write it with html code and to close the form 
	 * by yourself (and do notforget to close the form div too).
	 *
	 * @param string $submitValue The value of the submit button (the text on it).
	 */
	public function end($submitValue = 'Post')
	{
		$sub = new Input('submit', $this->model, array('type' => 'submit', 'value'=>$submitValue));
		$r = '';
		$r .= $sub->tag();
		$r .= '</form></div>';
		return $r;
	}

	
	/**
	 * You can use this method to add a field to the form.
	 *
	 * Given the name of the field, its generation can be automatic. Indeed some keyword names are automatically associed to fields.
	 * 
	 * - 'content' => textarea
	 * - 'id' => hidden
	 * - 'name', 'title' => text
	 * - 'password', 'passwd', 'pass' => password
	 * - 'email', 'mail' => email (html5)
	 * - 'url', 'site', 'website', 'link' => url (html5)
	 * - 'online', 'show' => checkbox
	 * - 'type', 'category' => select (but without options)
	 * - Default : a text field
	 * 
	 *
	 * Of course, you can override this system and give attributes you want with the second parameter. Its a array with these possible values :
	 * 
	 * - 'type' => the input type or textarea or select
	 * - 'value' => the value of the field (will be automatically added if you've used the set() method)
	 * - 'class' => the class of the div around the field, by default: input
	 * - 'id' => the id of the field, by default the same as the name passed as parameter
	 * - 'label' => the field label, by default: the name passed as parameter with upper cased first caracter
	 * - 'placeholder' => the field placeholder
	 * - 'maxlength' => the max lenth the string in field should have
	 * - 'values' => this is specific to SELECT and RADIO fields. Its an associative array of {'value' => 'label'}
	 * - 'errorMessage' => The error message tht should be displayed if the field hasn't been correctly filled
	 * 
	 *
	 */
	public function add($name, $options = array())
	{
		// If there's no options, try to do an automatic thing
		if(empty($options))
		{
			$this->fields[$name] = $this->_getFieldByName($name);
		}
		else  // Else we create it with given options
		{
			if(isset($this->data[$this->model][$name]))
				$options['value'] = $this->data[$this->model][$name];
			$this->fields[$name] = new Input($name, $this->model, $options);
		}

		$r = $this->fields[$name]->tag();
		return $r;
	}

}