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

include_once ('Input.php');

class Form {
	
	private $inputs = array();
	private $html_before = '';
	private $html_after = '';
	private $method = 'POST';
	private $action = '';
	private $id = '';
	
	/*
	*	@brief Permet de créer un formulaire
		@param $action Si l'action est une autre page php, il faut
					l'indiquer en paramètre. Sinon laisser vide
	*/
	public function __construct($method = 'POST', $action = '', $id = '')
	{
		$this->method = $method;
		$this->action = $action;
		$this->id 	  = $id;
	}
	
	/*
	* Permet d'afficher le formulaire sur la page appelante
	*/
	public function show()
	{
		echo '<div class="phform"><form method="'.$this->method.'" ';
		if(!empty($this->action))	echo 'action="'.$this->action.'"';
		if(!empty($this->id))		echo 'id="'.$this->id.'"';
		echo '>';
		
		foreach($this->inputs as $input)
			$input->show();
		
		echo '</form></div>';
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}

	public function setAction($action)
	{
		$this->action = $action;
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	
	/*
	*	Ajoute un champ au formulaire
	*/
	public function add(Input $input) {
		if(isset($this->inputs[$input->id()])){
			echo '<p><b>[PhForm] Attention, il y a deux champs avec le m&ecirc;me attribut name : '. $input->name() .' !</b></p>';
			die();
		}
		else
			$this->inputs[$input->id()] = $input; 
		
		return $input;
	}
	
	/*
	*	Vérifie que le formulaire a bien été rempli
	*	Note: Necessite PhP 5.2.0
	*/
	public function isvalid($POST) {
		if(empty($POST))
			return false;

		$this->handle($POST);

		$bool = true;
		foreach($this->inputs as $input)
		{
			// Si le champ est dans le formulaire envoyé
			if(isset($POST[$input->name()]))
			{
				// Si le champ est numérique (de type number ou range), il faut vérifier qu'il s'agit d'un nombre
				if(get_class($input) == "InputNumber" || get_class($input) == "InputRange") {
					if(!is_numeric($POST[$input->name()]) || $POST[$input->name()] > $input->max() || $POST[$input->name()] < $input->min()) {
						$input->setHtmlBefore('<div class="phwrongformat">'.$input->htmlBefore());
						$input->setHtmlAfter($input->htmlAfter().' <span class="phwrongformattext phwrongformatnumber"></span></div>');
						$bool = false;
					}
					else if($input->required() && empty($POST[$input->name()]) && $POST[$input->name()] != 0) {
						$input->setHtmlBefore('<div class="phmissing">'.$input->htmlBefore());
						$input->setHtmlAfter($input->htmlAfter().' <span class="phmissingtext"></span></div>');
						$bool = false;
					}
				}
				// Si le champ est un email, on vérifie qu'il s'agit d'un email
				else if(get_class($input) == "InputEmail") {
					if(!filter_var($POST[$input->name()], FILTER_VALIDATE_EMAIL)) {
						$input->setHtmlBefore('<div class="phwrongformat">'.$input->htmlBefore());
						$input->setHtmlAfter($input->htmlAfter().' <span class="phwrongformattext phwrongformattextemail"></span></div>');
						$bool = false;
					}
				}
				// Si le champ est une url, on vérifie qu'il s'agit d'une url
				else if(get_class($input) == "InputUrl") {
					if(!filter_var($POST[$input->name()], FILTER_VALIDATE_URL)) {
						$input->setHtmlBefore('<div class="phwrongformat">'.$input->htmlBefore());
						$input->setHtmlAfter($input->htmlAfter().' <span class="phwrongformattext phwrongformattexturl"></span></div>');
						$bool = false;
					}
				}
				// Si le champ est un tel, on vérifie qu'il s'agit d'un tel
				else if(get_class($input) == "InputTel") {
					if(!preg_match('!([0-9]{2}(\.){0,1}){5}!', $POST[$input->name()])) {
						$input->setHtmlBefore('<div class="phwrongformat">'.$input->htmlBefore());
						$input->setHtmlAfter($input->htmlAfter().' <span class="phwrongformattext phwrongformattexturl"></span></div>');
						$bool = false;
					}
				}
				// Par défaut
				else if($input->required() && empty($POST[$input->name()])) {
					$input->setHtmlBefore('<div class="phmissing">'.$input->htmlBefore());
					$input->setHtmlAfter($input->htmlAfter().' <span class="phmissingtext"></span></div>');
					$bool = false;
				}
			}
			// Le champ n'est pas dans le formulaire envoyé, on vérifie tout de meme s'il est obligatoire 
			// car les radio par exemple, ne sont pas dans $_POST si non cochés
			else if($input->required()) {
				$input->setHtmlBefore('<div class="phmissing">'.$input->htmlBefore());
				$input->setHtmlAfter($input->htmlAfter().' <span class="phmissingtext"></span></div>');
				$bool = false;
			}
		}
			
		return $bool;
	}
	
	
	/*
	*	Rempli les champs avec les valeurs du formulaire déjà envoyé
	*/
	public function handle($POST) {
		if(empty($POST))	return;
			
		foreach($this->inputs as $input)
		{
			if(isset($POST[$input->name()])) {
				$input->setValue($POST[$input->name()]);
			}
		}
	}
	
}