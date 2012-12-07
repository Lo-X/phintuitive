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
*	class Input
*	@brief Classe générique des éléments d'un formulaire.
*/
class Input {
	// Attributs du champ
	protected $name;
	protected $value = '';
	protected $class = 'phinput';
	protected $id = '';
	protected $label = '';
	protected $placeholder = '';
	protected $maxlength = 0;
	// Attributs de manipulation
	protected $required = false;
	// Attributs pour l'affichage
	protected $html_before = '';
	protected $html_after = '<br />';
	
	
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
	
	public function setRequired($bool) {
		$this->required = $bool;
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
	public function required()	{ return $this->required; }
	public function htmlBefore()	{ return $this->html_before; }
	public function htmlAfter()	{ return $this->html_after; }
	public function isNumeric()	{ return false; }
}


/***********************************************************************************************************/

/*
*	class InputText
*	@brief Crée un champ dynamique de type text
*/
class InputText extends Input {
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="text" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" placeholder="'.$this->placeholder.'"';
		if($this->maxlength > 0)	echo ' maxlength="'.$this->maxlength.'"';
		echo ' />';
		echo $this->html_after;
	}
}

/***********************************************************************************************************/

/*
*	class InputEmail
*	@brief Crée un champ dynamique de type email (HTML 5)
*/
class InputEmail extends Input {
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="email" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" placeholder="'.$this->placeholder.'"';
		if($this->maxlength > 0)	echo ' maxlength="'.$this->maxlength.'"';
		echo ' />';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputUrl
*	@brief Crée un champ dynamique de type url (HTML 5)
*/
class InputUrl extends Input {
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="url" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" placeholder="'.$this->placeholder.'"';
		if($this->maxlength > 0)	echo ' maxlength="'.$this->maxlength.'"';
		echo ' />';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputTel
*	@brief Crée un champ dynamique de type tel (HTML 5)
*/
class InputTel extends Input {
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="tel" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" placeholder="'.$this->placeholder.'"';
		if($this->maxlength > 0)	echo ' maxlength="'.$this->maxlength.'"';
		echo ' />';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputDate
*	@brief Crée un champ dynamique de type date (HTML 5)
*/
class InputDate extends Input {
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="date" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" placeholder="'.$this->placeholder.'"';
		if($this->maxlength > 0)	echo ' maxlength="'.$this->maxlength.'"';
		echo ' />';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputSearch
*	@brief Crée un champ dynamique de type search (HTML 5)
*/
class InputSearch extends Input {
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="search" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" placeholder="'.$this->placeholder.'"';
		if($this->maxlength > 0)	echo ' maxlength="'.$this->maxlength.'"';
		echo ' />';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputNumber
*	@brief Crée un champ dynamique de type number (HTML 5)
*/
class InputNumber extends Input {
	
	protected $min = 0;
	protected $max = 100;
	protected $step = 1;
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function setMin($min) {
		$this->min = $min;
		return $this;
	}
	
	public function setMax($max) {
		$this->max = $max;
		return $this;
	}
	
	public function setStep($step) {
		$this->step = $step;
		return $this;
	}
	
	public function isNumeric() { return true; }
	public function min() { return $this->min; }
	public function max() { return $this->max; }
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="number" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" min="'.$this->min.'" max="'.$this->max.'" step="'.$this->step.'" />';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputRange
*	@brief Crée un champ dynamique de type range (HTML 5)
*/
class InputRange extends Input {
	
	protected $min = 0;
	protected $max = 100;
	protected $step = 1;
	
	public function setMin($min) {
		$this->min = $min;
		return $this;
	}
	
	public function setMax($max) {
		$this->max = $max;
		return $this;
	}
	
	public function setStep($step) {
		$this->step = $step;
		return $this;
	}
	
	public function isNumeric() { return true; }
	public function min() { return $this->min; }
	public function max() { return $this->max; }
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="range" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" min="'.$this->min.'" max="'.$this->max.'" step="'.$this->step.'"  onchange="document.getElementById(\'rc'.$this->id.'\').textContent=value" /> <span class="phrangelabel" id="rc'.$this->id.'">'.$this->value.'</span>';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputPassword
*	@brief Crée un champ dynamique de type password
*/
class InputPassword extends Input {
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtext';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="password" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" placeholder="'.$this->placeholder.'"';
		if($this->maxlength > 0)	echo ' maxlength="'.$this->maxlength.'"';
		echo ' />';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputTextarea
*	@brief Crée un champ dynamique de type textarea
*/
class InputTextarea extends Input {
	protected $rows = 10;
	protected $cols = 110;
	
	public function setRows($rows) {
		$this->rows = $rows;
		return $this;
	}
	
	public function setCols($cols) {
		$this->cols = $cols;
		return $this;
	}
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phtextarea';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<textarea class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" rows="'.$this->rows.'" cols="'.$this->cols.'" placeholder="'.$this->placeholder.'">'.$this->value.'</textarea>';
		echo $this->html_after;
	}
}



/***********************************************************************************************************/

/*
*	class InputCheckbox
*	@brief Crée un champ dynamique de type checkbox
*/
class InputCheckbox extends Input {
	
	static $num = 1;
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $checked = false) {
		$this->name = $name;
		if($label)	$this->label = $label;
		$this->value = $checked;
		
		$this->id = 'phinput'.$name.self::$num;
		$this->class = 'phcheck';
		
		self::$num++;
	}
	
	public function show() {
		echo $this->html_before;
		echo '<input type="checkbox" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'"';
		if($this->value)	echo ' checked="checked"';
		echo ' value="1" />';
		echo '<label class="phlabelafter" for="'. $this->id .'">'.$this->label.'</label> ';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputRadio
*	@brief Crée un champ dynamique de type radio
*/
class InputRadio extends Input {
	
	static $num = 1;
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null, $value = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		if($value)$this->value = $value;
		
		$this->id = 'phinput'.$name.self::$num;
		$this->class = 'phradio';
		
		self::$num++;
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<input type="radio" class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" />';
		echo $this->html_after;
	}
}


/***********************************************************************************************************/

/*
*	class InputSelect
*	@brief Crée un champ dynamique de type select
*/
class InputSelect extends Input {
	
	protected $options = array();
	
	public function addOption($value, $label) {
		$this->options[$value] = $label;
		
		return $this;
	}
	
	/*
	*	Constructeur
	*/
	public function __construct($name, $label = null) {
		$this->name = $name;
		if($label)	$this->label = $label;
		
		$this->id = 'phinput'.$name;
		$this->class .= ' phselect';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<label class="phlabel" for="'. $this->id .'">'.$this->label.'</label> ';
		echo '<select class="'.$this->class.'" id="'.$this->id.'" name="'.$this->name.'">';
		foreach($this->options as $k => $v) {
			echo '<option value="'.$k.'"';
			if($k == $this->value)	echo ' selected';
			echo '>'.$v.'</option>';
		}
		echo '</select>';
		echo $this->html_after;
	}
}



/***********************************************************************************************************/

/*
*	class InputSubmit
*	@brief Crée un champ dynamique de type submit
*/
class InputSubmit extends Input {
	/*
	*	Constructeur
	*/
	public function __construct($value, $name = null) {
		$this->value = $value;
		if(!$name)$this->name = $value;
		else $this->name = $name;
		$this->id = 'phinput'.$this->name;
		$this->class .= ' phsubmit phbutton';
	}
	
	public function show() {
		echo $this->html_before;
		echo '<input type="submit" class="'.$this->class.'" value="'.$this->value.'" />';
		echo $this->html_after;
	}
}



/***********************************************************************************************************/

/*
*	class InputHidden
*	@brief Crée un champ dynamique de type hidden
*/
class InputHidden extends Input {
	/*
	*	Constructeur
	*/
	public function __construct($name, $value) {
		$this->value = $value;
		$this->name = $name;
		$this->id = 'phinput'.$this->name;
	}
	
	public function show() {
		echo '<input type="hidden" value="'.$this->value.'" name="'.$this->name.'" id="'.$this->id.'" />';
	}
}








