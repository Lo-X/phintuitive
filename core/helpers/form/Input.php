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



/*
*	Generic class for form elements/fields
*/
class Input {

	protected $name;
	protected $model;
	protected $type;
	protected $value = '';
	protected $class = 'input';
	protected $id = '';
	protected $label = '';
	protected $placeholder = '';
	protected $maxlength = 0;
	protected $values;


	public function __construct($name, $model, $options = array())
	{
		$this->name = $name;
		$this->model = $model;

		$this->setOptions($options);
	}

	public function setOptions($options)
	{
		if(isset($options['type']))
			$this->type = $options['type'];
		else
			$this->type = 'text';

		if(isset($options['value']))
			$this->value = $options['value'];

		if(isset($options['class']))
			$this->class = $options['class'];
		else if($this->type == 'submit')
			$this->class = 'submit';

		if(isset($options['id']))
			$this->id = $options['id'];
		else
			$this->id = $this->name;

		if(isset($options['label']))
			$this->label = $options['label'];
		else
			$this->label = ucfirst($this->name);
			

		if(isset($options['placeholder']))
			$this->placeholder = $options['placeholder'];

		if(isset($options['maxlength']))
			$this->maxlength = $options['maxlength'];

		if(isset($options['values']) && is_array($options['values']))
			$this->values = $options['values'];

		if(!empty($options['errorMessage']))
		{
			$this->class .= ' error';
			$this->errorMessage = $options['errorMessage'];
		}
	}


	public function tag()
	{
		$tag = '<div class="'.$this->class.'">';

		switch($this->type)
		{
			case 'textarea':
				if($this->label)
					$tag .= '<label for="'.$this->id.'">'.$this->label.'</label>';
				$tag .= '<textarea name="'.$this->model.'['.$this->name.']" id="'.$this->id.'" placeholder="'.$this->placeholder.'">'.$this->value.'</textarea>';
			break;

			case 'radio':
				if(is_array($this->values))
				{
					foreach ($this->values as $label => $value) {
						$tag .= '<input type="radio" name="'.$this->model.'['.$this->name.']" id="'.$this->id.'.'.$value.'" value="'.$value.'"';
						if($value == $this->value)
							$tag .= ' checked';
						$tag .= ' /> ';
						$tag .= '<label for="'.$this->id.'.'.$value.'">'.$label.'</label> <br />';
					}
				}
			break;

			case 'checkbox':
				$tag .= '<input type="checkbox" name="'.$this->model.'['.$this->name.']" id="'.$this->id.'"';
				if($this->value)
					$tag .= ' value="'.$this->value.'" checked';
				$tag .= ' /> ';
				$tag .= '<label for="'.$this->id.'">'.$this->label.'</label>';
			break;

			case 'select':
				if(is_array($this->values))
				{
					$tag .= '<label for="'.$this->id.'">'.$this->label.'</label> ';
					$tag .= '<select name="'.$this->model.'['.$this->name.']" id="'.$this->id.'">';

					if(!empty($this->placeholder))
						$tag .= '<option>---'.$this->placeholder.'---</option>';

					foreach ($this->values as $label => $value) {
						$tag .= '<option value="'.$value.'"';
						if($value == $this->value)
							$tag .= ' selected';
						$tag .= '>'.$label.'</option>';
					}
					$tag .= '</select>';
				}
			break;

			case 'submit':
				$tag .= '<input type="submit" id="'.$this->id.'" value="'.$this->value.'" />';
			break;

			case 'hidden':
				$tag = '<input type="hidden" name="'.$this->model.'['.$this->name.']" id="'.$this->id.'" value="'.$this->value.'" />';
			break;

			default:
				$tag .= '<label for="'.$this->id.'">'.$this->label.'</label>';
				$tag .= '<input type="'.$this->type.'" name="'.$this->model.'['.$this->name.']" id="'.$this->id.'"';
				if(!empty($this->value))
					$tag .= ' value="'.$this->value.'"';
				if(!empty($this->placeholder))
					$tag .= ' placeholder="'.$this->placeholder.'"';
				$tag .= ' />';
			break;
		}

		if(isset($this->errorMessage))
			$tag .= '<span class="errorMessage">'.$this->errorMessage.'</span>';
		
		if($this->type != 'hidden')
			$tag .= '</div>';

		return $tag;
	}
	
	
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}
	
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}
	
	public function setMaxlength($n) {
		$this->maxlength = $n;
		return $this;
	}
	
	public function setHtmlBefore($html) {
		$this->html_before = $html;
		return $this;
	}
	
	public function setHtmlAfter($html) {
		$this->html_after = $html;
		return $this;
	}
	
	public function setPlaceholder($placeholder) {
		$this->placeholder = $placeholder;
		return $this;
	}
	
	
	
	public function name() 		{ return $this->name; }
	public function value() 		{ return $this->value; }
	public function cssclass()	{ return $this->class; }
	public function id()		{ return $this->id; }
	public function label()		{ return $this->label; }
	public function placeholder()	{ return $this->placeholder; }
	public function htmlBefore()	{ return $this->html_before; }
	public function htmlAfter()	{ return $this->html_after; }
	public function isNumeric()	{ return false; }
}
