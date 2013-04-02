<?php
/*
*	PhIntuitive - Fast websites development framework
*	Copyright 2013, Boutter Loïc - http://loicboutter.fr
*
*	Licensed under The MIT License
*	Redistributions of files must retain the above copyright notice.
*
*	@copyright Copyright 2013, Boutter Loïc - http://loicboutter.fr
*	@author Boutter Loïc
*	@version 2.0.0
*/


/**
* This function is used by Model class to compute the name of the relation table from two other tables
* The rule is : tables are in low case, plural, by alphabetical order, separated by an underscore
*
* @param $table1 The first table name in the relationship
* @param $table2 The second table name in the relationship
* @return Returns the name of the relation table
*/
function computeRelationTable($table1, $table2) {
	$relationTable = '';
	($table1 < $table2)? $relationTable = $table1.'_'.$table2 : $relationTable = $table2.'_'.$table1;
	return $relationTable;
}


/**
* @brief A model is the representation of a table in the database.
* @details It contains the structure of that table, relationships and methods to add/modify/create data.
*/
class Model {

	protected $db = 'default'; ///< The database to use to retrieve that table

	protected $table = false; ///< The name of the table, automatically deduced from the class name or manually modified by user
	protected $name = false; ///< A shortcut for the class Name


	protected $primaryKey = 'id'; ///< The name of the primary key field in the table
	protected $fields = array(); ///< An array containing table fields name
	protected $lastInsertId = null; ///< The value of the last inserted ID

	protected $rules = array(); ///< An array of rules used to validate data before insertion/update in database


	// Relationships
	protected $hasOne = array(); ///< You can add other tables to that array, the OTHER table contains the foreign key (User has one Profile => Profile.user_id)
	protected $hasMany = array(); ///< You can add other tables to that array, the OTHER table contains the foreign key (Author has many Article => Article.author_id)
	protected $belongsTo = array(); ///< You can add other tables to that array, the CURRENT table contains the foreign key (Article belongs to Author => Article.author_id)
	protected $hasAndBelongsToMany = array(); ///< You can add other tables to that array to add n<-->n relation with another table (Article HABTM Tags => {articles_tags.id, articles_tags.article_id, articles_tags.tag_id})
	protected $useRecursion = true; ///< The recursion is the ability to search for data in related tables. If set to true, this recursion will be done automagically (can take several SQL request, depending on the relation between tables). Else only the data from the Model table will be collected. Default: true
	private   $_all_fields = array();


	// Connections
	static $connections = array();



	/**
	* Construct a Model by deducing as much parameters automatically as possible
	* @param $beeingInspected This parameters is set to true only when a Model wants to create another Model, without wanting it to create dependent Models as well.
	*/
	public function __construct($beeingInspected = false) {
		// Open the database to establish a connection
		$this->_openDB();

		// Deduce the table name, if not set by user
		if($this->table == false)
			$this->table = strtolower(get_class($this)).'s';

		// Deduce the class name, if not set by user
		if($this->name == false)
			$this->name = get_class($this);


		// Add the primary key to fields, if fields are empty
		if(empty($this->fields))
			$this->fields[] = $this->primaryKey;


		// Creation of other models that we depend on
		if(!$beeingInspected)
			$this->_inspect();


		// Creation of validator
		App::load('Rule', 'helper');
		$this->Validator = new RuleHelper();
	}



	/**
	* This method allow you to execute code before the SQL work to be done. 
	* You can access the options that has been given to the model, alter them, but you MUST return them back. Else it will be considered as an empty array.
	* @param $options The options given for the find call and SQL query
	* @return Mandatory: you must return a valid array of options
	*/
	public function beforeFind($options) {
		return $options;
	}


	/**
	* This method allow you to execute code after a SQL work and before the results to be returned to Controller.
	* You can alter results but you MUST return them back. Else there will be no results at all.
	* @param $result The SQL query results, formated.
	* @return Mandatory: you must return valid results
	*/
	public function afterFind($result) {
		return $result;
	}


	/**
	* This method allow you to execute code before the data is saved in database.
	* You can access and alter the data that is given to this method but you MUST return it back. Else, nothing will be saved at all.
	* @param $data The data that will be saved in database
	* @return Mandatory: you must return valid data
	*/
	public function beforeSave($data) {
		return $data;
	}


	/**
	* Gives you the last insered element's ID.
	* @return The last inserted ID
	*/
	public function lastInsertId() {
		return $this->lastInsertId;
	}


	/**
	* This method allow you to execute your own SQL query
	* @param $sql The string containing your SQL query
	* @return Returns non-formated results of your query
	*/
	public function rawQuery($sql) {
		$pdo = Model::$connections[$this->db];

		// Prepare and execute request
		$pre = $pdo->prepare($sql);
		$pre->execute();
		return $pre->fetchAll(PDO::FETCH_OBJ);
	}


	/**
	* Set whether or not the Model should use recursion. For more details about recursion, see protected $useRecursion attribute.
	* @param $bool True or False
	*/
	public function useRecursion($bool) {
		$this->useRecursion = $bool;
	}




	/**
	* This method is a multifunctional method that allow you to get data in the table, automatically resolving JOINs with associated tables.
	* You can find the first element ('first'), all of them ('all') or count them ('count'). Plus you can give options to control what's happening in the model.
	*
	* Options you can give are :
	* 	- conditions : condition ("WHERE" clause) in array, ie: array('MyModel.myfield' => 'value', 'MyModel.myotherfield' => 3)
	*	- fields : The fields you want to get back, in array, ie: array('myfield', 'MyModel.myotherfield', 'MyAssociatedModel.id')
	*	- order : The "ORDER BY" string, ie: 'mysortingfield DESC'
	*	- limit : The number of line to get from the table ("LIMIT" clause). See offset for the starting data to count from
	*	- offset : The offset in the "LIMIT" clause
	*
	* @param $what Can be 'first' (default), 'all' or 'count'
	* @param $options This is an array that can cointain a lot of information about how the query has to be done. See find() full description for more details.
	*/
	public function find($what = 'first', $options = array()) {
		$pdo = Model::$connections[$this->db];
		$sql = '';
		$results = array();

		// Call beforeFind hook with options
		$options = $this->beforeFind($options);

		// Switch on what, to construct the SQL request accordingly
		$count = false;
		switch($what) {
			case 'all':
			break;

			case 'count':
				$count = true;
			break;

			case 'first':
			default:
				$options['limit'] = 1;
				$options['offset'] = 0;
			break;
		}

		// Build request and handle the dependencies hasOne and belongsTo
		$sql .= $this->_doSelect($options, $count); // SELECT ...
		$sql .= $this->_doWhere($options);			// ... WHERE ...
		$sql .= $this->_doOrderBy($options);		// ... ORDER BY ...
		$sql .= $this->_doLimit($options);			// ... LIMIT ...

		// Prepare and execute request
		$pre = $pdo->prepare($sql);
		$pre->execute();
		$firstResult = $this->_formatSqlResults($pre->fetchAll(PDO::FETCH_OBJ));

		// Handle dependencies hasMany and HABTM, using a second query
		if($this->useRecursion) {
			if(!empty($this->hasMany)) $firstResult = $this->_hasMany($firstResult);
			if(!empty($this->hasAndBelongsToMany)) $firstResult = $this->_hasAndBelongsToMany($firstResult);
		}

		// Call afterFind hook with results
		$results = $this->afterFind($firstResult);

		// Return that object
		return $results;
	}




	/**
	* The method save allow you to add or modify data in the database, automatically handling the belongsTo relationship (and only that one).
	* This method will INSERT data if the primary key isn't set in the data you give. Else it will update each field you give as data.
	*
	* Data should have this format :
	* @code
	* Array
	* (
	*     [Category] => Array
	*         (
	*             [title] => My New Category
	*         )
	* 
	*     [Post] => Array
	*         (
	*             [title] => My new post
	*             [content] => This is the new content for my new post !
	*         )
	* )
	* @endcode
	* Because in this example Post->belongsTo = Category, this will firstly save the new Category, then save the post and associate it with the category by adding/modifying the field $data[Post]['category_id'] :
	* @code
	* Array
	* (
	*     [Category] => Array
	*         (
	*             [title] => Utilisateurs
	*         )
	*
	*     [Post] => Array
	*         (
	*             [title] => Nouvel utilisateur
	*             [content] => This is the new content for my new post !
	*             [category_id] => 3
	*         )
	* )
	* @endcode
	*
	*
	* @param $data The data you want to save, well formated (see description)
	* @return A boolean : true if the data has been saved, false if not
	*/
	public function save($data) {
		$pdo = Model::$connections[$this->db];
		$key = $this->primaryKey;
		$sql = "";

		// Call beforeSave hook
		$data = $this->beforeSave($data);

		if(empty($data)) return;

		// Save associated Models
		if(count($data) > 1) {
			// we will need this Model specific data
			$mydata = $data[$this->name];
			debug($data);
			foreach ($data as $model => $otherdata) {
				if(in_array($model, $this->belongsTo)) {
					$this->$model->save(array($model => $otherdata));
					$data[$this->name][strtolower($this->$model->name).'_'.$this->$model->primaryKey] = $this->$model->lastInsertId();
				}
				debug($otherdata);
			}
			
			debug($data);
		}


		// We fill an array with fields
		$fields = array();
		$d = array();
		$data = $data[$this->name];
		if(empty($data)) return;


		/// Handle automatic fields
		// If we automatically set the created date
		if(in_array('created', $this->fields) && !(isset($data[$key]) && !empty($data[$key]))) {
			$data['created'] = 'NOW()';
		}
		else if(in_array('created', $this->fields) && (isset($data[$key]) && !empty($data[$key]))) {
			$data['updated'] = 'NOW()';
		}

		debug($data);


		foreach($data as $k => $v) {
			if($k != 'created' && $k != 'updated') {
				$fields[] = "$k=:$k";

				if(!is_numeric($v))
					$v = mysql_real_escape_string($v); // Security
				$d[":$k"] = $v;
			}
			else
				$fields[] = "$k=$v";
		}
		
		// If we need an UPDATE (the primary key field is set)
		if(isset($data[$key]) && !empty($data[$key])) {
			$sql .= 'UPDATE '.$this->table.' SET '.implode(', ', $fields).' WHERE '.$key.'='.$data[$key];
		}
		else { // Else INSERT
			$sql .= 'INSERT INTO '.$this->table.' SET '.implode(', ', $fields).'';
		}

		debug($sql);
		
		// Modification de la BDD
		$pre = $pdo->prepare($sql);
		$bool = $pre->execute($d);
		$this->lastInsertId = $pdo->lastInsertId();
		return $bool;
	}



	/**
	* Removes a line in the table, indexed by given ID. 
	* If this Model got hasOne, hasMany and/or hasAndBelongsToMany relationship, you can remove associated data as well. See parameter $recursive
	*
	* @param $id The id (value of primary key) that index the line you want to remove from database
	* @param $recursive If set to true, linked models that have a foreign key on the line you're about to remove will be removed as well
	*/
	public function delete($id, $recursive = false) {
		$pdo = Model::$connections[$this->db];

		if($recursive) {
			foreach ($this->hasOne as $model) {
				$sql = 'DELETE FROM '.$this->$model->table.' WHERE '.strtolower($this->name).'_'.$this->primaryKey.'='.$id;
				$pdo->query($sql);
				$sql = "OPTIMIZE TABLE {$this->$model->table}";
				$pdo->query($sql);
			}
			foreach ($this->hasMany as $model) {
				$sql = 'DELETE FROM '.$this->$model->table.' WHERE '.strtolower($this->name).'_'.$this->primaryKey.'='.$id;
				$pdo->query($sql);
				$sql = "OPTIMIZE TABLE {$this->$model->table}";
				$pdo->query($sql);
			}
			foreach ($this->hasAndBelongsToMany as $model) {
				$rtable = computeRelationTable($this->table, $this->$model->table);
				$sql = 'DELETE FROM '.$rtable.' WHERE '.strtolower($this->name).'_'.$this->primaryKey.'='.$id;
				$pdo->query($sql);
				$sql = "OPTIMIZE TABLE {$rtable}";
				$pdo->query($sql);
			}
		}

		$sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey}=$id";
		$pdo->query($sql);

		$sql = "OPTIMIZE TABLE {$this->table}";
		$pdo->query($sql);
	}






	/**
	* Open the Database connection, given the use database in $db and the database configuration
	*/
	private function _openDB() {
		// Connection to database
		$conf = Config::databases()[$this->db];
		$this->prefix = $conf['prefix'];
		if(!empty($this->prefix))
			$this->table = $this->prefix.'_'.$this->table;
		if(isset(Model::$connections[$this->db]))	return;
		try {
			$pdo = new PDO('mysql:dbname='.$conf['database'].';host='.$conf['host'], 
							$conf['login'], $conf['password'], 
							array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			Model::$connections[$this->db] = $pdo;
		} catch (PDOException $e) {
			if(Config::$debug > 0)
				die($e->getMessage());
		}
	}


	/**
	* Auto-inspect all related tables, create a Model for them in order to get their fields
	*/
	private function _inspect() {
		// Creation of other models that we depend on
		$dependences = array_merge($this->hasOne, $this->belongsTo, $this->hasMany, $this->hasAndBelongsToMany);
		foreach ($dependences as $m) {
			if(App::load($m))
				$this->$m = new $m(true);
			else {
				echo '<p><b>The model '.$m.' has no file <i>'.$m.'Model.php</i> !</b></p><pre>';
				debug_print_backtrace();
				echo '</pre>';
			}
			$this->_all_fields[$this->$m->name] = current($this->$m->fields);
		}
	}


	/**
	* Construct the SELECT part of SQL query from given options and return that part of the query
	*/
	private function _doSelect($options, $count) {
		$sql = 'SELECT ';

		// If its a counting select
		if($count) {
			$sql .= 'COUNT('.$this->primaryKey.') as count';
		} else {
			// Else, what fields do we want to get back :
			if(!isset($options['fields'])) {
				$options['fields'] = $this->fields;
			}

			// Prefix everything that isn't prefixed yet
			array_walk($options['fields'], function(&$data) {
				if(strstr($data, '.') == false) {
					$data = $this->name.'.'.$data;
				}
			});

			$fields = implode(', ', $options['fields']);
			$sql .= $fields;
		}

		$sql .= ' FROM '.$this->table.' as '.$this->name.'';


		/// Handle dependencies (hasOne, hasMany, belongsTo, HABTM)
		// Belongs to : the current table contains the foreign key
		if(!empty($this->belongsTo) && $this->useRecursion) {
			foreach ($this->belongsTo as $rightModel) {
				$rightTable = $this->$rightModel->table;
				$sql .= ' LEFT JOIN '.$rightTable.' '.$rightModel.' ON '.$this->name.'.'.strtolower($this->$rightModel->name).'_'.$this->$rightModel->primaryKey.'='.$rightModel.'.'.$this->$rightModel->primaryKey;
			}
		}

		// HasOne : the distant table contains the forein key
		if(!empty($this->hasOne) && $this->useRecursion) {
			foreach ($this->hasOne as $rightModel) {
				$rightTable = $this->$rightModel->table;
				$sql .= ' LEFT JOIN '.$rightTable.' '.$rightModel.' ON '.$rightModel.'.'.strtolower($this->name).'_'.$this->primaryKey.'='.$this->name.'.'.$this->primaryKey;
			}
		}

		return $sql;
	}

	/**
	* Construct the WHERE part of the SQL query from given options and return that part of the query
	*/
	private function _doWhere($options) {
		$sql = '';

		if(isset($options['conditions'])) {
			$sql .= ' WHERE ';
			// If the condition is a raw string
			if(!is_array($options['conditions']))
				$sql .= $options['conditions'];
			// Else if it's an array, we extract conditions :
			else {
				$cond = array();
				foreach($options['conditions'] as $k => $v)
				{
					if(!is_numeric($v))
						$v = "'".mysql_real_escape_string($v)."'"; // Security
					$cond[] = "$k=$v";
				}
				$sql .= implode(' AND ', $cond);
			}
		}

		return $sql;
	}

	/**
	* Construct the ORDER BY part of the SQL query from given options and return that part of the query
	*/
	private function _doOrderBy($options)
	{
		$sql = '';
		// Order By
		if(isset($options['order'])) {
			$sql .= ' ORDER BY '.$options['order'];
		}

		return $sql;
	}

	/**
	* Construct the ORDER BY part of the SQL query from given options and return that part of the query
	*/
	private function _doLimit($options)
	{
		$sql = '';
		// Limit
		if(isset($options['limit'])) {
			if(isset($options['offset']))
				$sql .= ' LIMIT '.$options['offset'].','.$options['limit'];
			else $sql .= ' LIMIT 0,'.$options['limit'];
		}

		return $sql;
	}


	/**
	* Handle the hasMany relationship by doing other requests and formating results
	*/
	private function _hasMany($firstResult) {
		// HasMany :
		// Premier select sur la table/model principal avec les éventuelles jointures
		// second select sur la table associée WHERE tableAssociée.tablePrincipale_id IN (liste_id_recupérés_1ere_requete)
		// Manipuler les résultats pour contruire le tableau résultant

		$secondResult = array();
		$list = array();
		$id = $this->primaryKey;

		foreach ($firstResult as $data) {
			$data = current($data);
			$list[] = $data->$id;
		}
		$list = implode(',',$list);

		
		foreach ($this->hasMany as $rightModel) {
			// Set the conditions (WHERE clause)
			$options = array('conditions' => $rightModel.'.'.strtolower($this->name).'_'.$this->primaryKey.' IN ('.$list.')');
			// Find data
			$secondResult = $this->$rightModel->find('all', $options);
		}
		$secondResult = $this->_mergeResults($firstResult, $secondResult);
		

		return $secondResult;
	}


	private function _hasAndBelongsToMany($firstResult) {
		// HABTM :
		// Premier select sur la table/model principal avec les éventuelles jointures
		// second select de la table associée avec un JOIN (simple) ON tableLiaison.tablePrincipale_id IN (liste_id_récupérés_1ere_requete) AND tableLiaison.autreAssociée_id = tableAssociée.id
		// Manipuler les résultats pour contruire le tableau résultant

		$secondResult = array();
		$list = array();
		$id = $this->primaryKey;

		foreach ($firstResult as $data) {
			$data = current($data);
			$list[] = $data->$id;
		}
		$list = implode(',',$list);

		
		foreach ($this->hasAndBelongsToMany as $rightModel) {
			// Parsing relationship table :
			$relationTable = computeRelationTable($this->$rightModel->table, $this->table);

			// Request
			$sql = 'SELECT * FROM '.$this->$rightModel->table.' as '.$rightModel.' JOIN '.$relationTable.' as R ON R.'.strtolower($this->name).'_'.$this->primaryKey.' IN ('.$list.') AND R.'.strtolower($rightModel).'_'.$this->$rightModel->primaryKey.'='.$rightModel.'.'.$this->$rightModel->primaryKey;
			
			// Find data
			$secondResult = $this->_formatSqlResults($this->rawQuery($sql), $rightModel);
		}
		$secondResult = $this->_mergeResults($firstResult, $secondResult);

		return $secondResult;
	}


	/**
	* Merge/Format primary and secondary results. Used by _hasMany to return well formated results
	*/
	private function _mergeResults($firstResult, $secondResult) {
		if(empty($secondResult))	return $firstResult;
		
		// Foreach data, format result
		foreach ($secondResult as $data) {
			$dataHeader = key($data);
			$data = current($data);
			$pkey = strtolower($this->name).'_'.$this->primaryKey;
			$id = $data->$pkey;
			$firstResult[$id][$dataHeader][] = $data;
		}

		return $firstResult;
	}


	/**
	* Format common SQL results into a nice array
	*/
	private function _formatSqlResults($query_result, $ModelName = null)
	{
		$result = array();
		// Foreach sql result, fill the result array by id then model
		foreach ($query_result as $data) {
			$pkey = $this->primaryKey;
			$name = '';
			$ModelName ? $name = $ModelName : $name = $this->name;
			$result[$data->$pkey][$name] = $data;
		}

		return $result;
	}

}