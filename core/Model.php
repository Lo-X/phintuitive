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


class Model {
	
	public $db = 'default';
	
	public $table = false;
	public $name  = false;
	public $primaryKey = 'id';
	public $lastInsertId = null;
	protected $fields = array();
	protected $hasOne = array();
	protected $belongsTo = array();
	protected $hasMany = array();
	protected $hasAndBelongsToMany = array();
	protected $validate = array();
	private $_fields = array();
	private $_all_fields = array();

	static $connections = array();
	
	public function __construct($inspected = false)
	{
		// Open database
		$this->_openDB();

		// Initialisation
		if($this->table == false)
			$this->table = strtolower(get_class($this)).'s';

		if($this->name == false)
			$this->name = get_class($this);

		if(empty($this->fields))
			$this->fields[] = $this->primaryKey;

		foreach ($this->fields as $field) {
			$this->_fields[$this->name][$this->name.'_'.$field] = $field;
		}
			


		// Creation of other models
		if(!$inspected)
			$this->_inspect();
	}

	private function _openDB()
	{
		// Connection to database
		$conf = Config::$databases[$this->db];
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

	private function _inspect()
	{
		// Creation of other models
		$dependences = array_merge($this->hasOne, $this->belongsTo, $this->hasMany, $this->hasAndBelongsToMany);
		foreach ($dependences as $m) {
			App::load($m);
			$this->$m = new $m(true);
			$this->_all_fields[$this->$m->name] = current($this->$m->_fields);
		}
	}



	public function describe($tablename)
	{
		$pdo = Model::$connections[$this->db];
		$sql = 'DESCRIBE '.$tablename;

		$pre = $pdo->prepare($sql);
		$pre->execute();
		$result = $pre->fetchAll();
		return $result;
	}

	private function _doSelect($sql)
	{
		$options['fields'] = array();

		foreach (array_merge($this->_fields, $this->_all_fields) as $name => $one) {
			foreach ($one as $alias => $field) {
				$options['fields'][] = $name.'.'.$field.' AS '.$alias;
			}
		}

		$fields = implode(', ', $options['fields']);
		$sql .= 'SELECT '.$fields.' FROM '.$this->table.' as '.$this->name.'';
		return $sql;
	}

	private function _doSelectHABTM($sql, $tables, $fields)
	{
		foreach (array_merge($this->_fields, $fields) as $name => $one) {
			foreach ($one as $alias => $field) {
				$options['fields'][] = $name.'.'.$field.' AS '.$alias;
			}
		}
		$table = $tables[0].'_'.$tables[1];

		$fields = implode(', ', $options['fields']);
		$sql .= 'SELECT '.$fields.' FROM '.$table.' as L';
		return $sql;
	}

	private function _doJoin($sql)
	{
		// Joins
		foreach ($this->_all_fields as $name => $one) {
			$sql .= ' JOIN '.$this->$name->table.' '.$name.' ON '.$this->name.'.'.$this->primaryKey.'='.$name.'.'.strtolower($this->name).'_'.$this->primaryKey;
		}

		return $sql;
	}

	private function _doJoinHABTM($sql, $tables, $fields)
	{
		// Joins
		$jointable = $tables[0].'_'.$tables[1];
		$sql .= ' JOIN '.$this->table.' '.$this->name.' ON '.$this->name.'.'.$this->primaryKey.'=L.'.$this->name.'_'.$this->primaryKey;
		foreach ($fields as $name => $one) {
			$sql .= ' JOIN '.$this->$name->table.' '.$this->$name->name.' ON '.$this->$name->name.'.'.$this->$name->primaryKey.'=L.'.$this->$name->name.'_'.$this->$name->primaryKey;
		}

		return $sql;
	}

	private function _doJoinBelongs($sql)
	{
		// Joins
		foreach ($this->_all_fields as $name => $one) {
			$sql .= ' JOIN '.$this->$name->table.' '.$name.' ON '.$this->name.'.'.$this->$name->name.'_'.$this->$name->primaryKey.'='.$name.'.'.$this->$name->primaryKey;
		}

		return $sql;
	}

	private function _doWhere($sql, $options)
	{
		// Conditions
		if(isset($options['conditions']))
		{
			$sql .= ' WHERE ';
			if(!is_array($options['conditions']))
				$sql .= $options['conditions'];
			else
			{
				$cond = array();
				foreach($options['conditions'] as $k => $v)
				{
					if(!is_numeric($v))
						$v = "'".mysql_escape_string($v)."'";
					$cond[] = "$k=$v";
				}
				$sql .= implode(' AND ', $cond);
			}
		}

		return $sql;
	}

	private function _doOrderBy($sql, $options)
	{
		// Order By
		if(isset($options['orderBy'])) {
			$sql .= ' ORDER BY '.$options['orderBy'];
		}

		return $sql;
	}

	private function _doLimit($sql, $options)
	{
		// Limit
		if(isset($options['limit'])) {
			if(isset($options['offset']))
				$sql .= ' LIMIT '.$options['offset'].','.$options['limit'];
			else $sql .= ' LIMIT 0,'.$options['limit'];
		}

		return $sql;
	}

	private function _formatHasOne($query_result)
	{
		$result = array();
		// Foreach sql result as index => element
		foreach ($query_result as $n => $elem) {
			// Foreach of our fields as alias => field
			foreach (current($this->_fields) as $alias => $field) {
				// The index->OurName->field = valueOf(element->alias)
				$result[$n][$this->name][$field] = $elem[$alias];
			}
			foreach ($this->_all_fields as $depname => $dep) {
				foreach ($dep as $alias => $field) {
					$result[$n][$depname][$field] = $elem[$alias];
				}
			}
		}

		return $result;
	}

	private function _formatHasMany($query_result)
	{
		$result = array();
		// Foreach sql result as index => element
		foreach ($query_result as $n => $elem) {
			// Foreach of our fields as alias => field
			foreach (current($this->_fields) as $alias => $field) {
				// The index->field = valueOf(element->alias)
				$result[$elem[$this->name.'_'.$this->primaryKey]][$this->name][$field] = $elem[$alias];
			}
			foreach ($this->_all_fields as $depname => $dep) {
				foreach ($dep as $alias => $field) {
					$result[$elem[$this->name.'_'.$this->primaryKey]][$depname][$elem[$depname.'_'.$this->$depname->primaryKey]][$field] = $elem[$alias];
				}
			}
		}

		return $result;
	}

	private function _formatCommon($query_result)
	{
		$result = array();
		// Foreach sql result as index => element
		foreach ($query_result as $n => $elem) {
			// Foreach of our fields as alias => field
			foreach (current($this->_fields) as $alias => $field) {
				// The index->OurName->field = valueOf(element->alias)
				$result[$elem[$this->name.'_'.$this->primaryKey]][$field] = $elem[$alias];
			}
			foreach ($this->_all_fields as $depname => $dep) {
				foreach ($dep as $alias => $field) {
					$result[$elem[$this->name.'_'.$this->primaryKey]][$depname][$elem[$depname.'_'.$this->$depname->primaryKey]][$field] = $elem[$alias];
				}
			}
		}

		return $result;
	}



	public function find($options = array())
	{
		$options = $this->beforeFind($options);
		$r = array();

		if(!empty($this->hasAndBelongsToMany))
		{
			$r = $this->findHABTM($options);
		}
		elseif(!empty($this->hasMany))
		{
			$r = $this->findHasMany($options);
		}
			
		elseif(!empty($this->hasOne))
		{
			$r = $this->findHasOne($options);
		}
			
		elseif(!empty($this->belongsTo))
		{
			$r = $this->findBelongsTo($options);
		}
		else 	// else, it's a common find
			$r = $this->findCommon($options);
			

		
		return $this->afterFind($r);
	}



	private function findHABTM($options)
	{
		$pdo = Model::$connections[$this->db];

		$other = $this->hasAndBelongsToMany[0];
		$tables = array($this->table, $this->$other->table);
		sort($tables);
		$fields[$this->$other->name] = $this->_all_fields[$this->$other->name];

		$sql = '';
		$sql = $this->_doSelectHABTM($sql, $tables, $fields);
		$sql = $this->_doJoinHABTM($sql, $tables, $fields);
		$sql = $this->_doWhere($sql, $options);
		$sql = $this->_doOrderBy($sql, $options);
		$sql = $this->_doLimit($sql, $options);
		
		$pre = $pdo->prepare($sql);
		$pre->execute();
		
		$res = $pre->fetchAll(PDO::FETCH_ASSOC);
		return $this->_formatHasMany($res);
	}

	private function findHasOne($options)
	{
		$pdo = Model::$connections[$this->db];

		$sql = '';
		$sql = $this->_doSelect($sql);
		$sql = $this->_doJoin($sql);
		$sql = $this->_doWhere($sql, $options);
		$sql = $this->_doOrderBy($sql, $options);
		$sql = $this->_doLimit($sql, $options);
		
		$pre = $pdo->prepare($sql);
		$pre->execute();
		
		$res = $pre->fetchAll(PDO::FETCH_ASSOC);
		return $this->_formatHasOne($res);
	}

	private function findHasMany($options)
	{
		$pdo = Model::$connections[$this->db];

		$sql = '';
		$sql = $this->_doSelect($sql);
		$sql = $this->_doJoin($sql);
		$sql = $this->_doWhere($sql, $options);
		$sql = $this->_doOrderBy($sql, $options);
		$sql = $this->_doLimit($sql, $options);
		
		$pre = $pdo->prepare($sql);
		$pre->execute();
		
		$res = $pre->fetchAll(PDO::FETCH_ASSOC);
		return $this->_formatHasMany($res);
	}

	private function findBelongsTo($options)
	{
		$pdo = Model::$connections[$this->db];

		$sql = '';
		$sql = $this->_doSelect($sql);
		$sql = $this->_doJoinBelongs($sql);
		$sql = $this->_doWhere($sql, $options);
		$sql = $this->_doOrderBy($sql, $options);
		$sql = $this->_doLimit($sql, $options);
		
		$pre = $pdo->prepare($sql);
		$pre->execute();
		
		$res = $pre->fetchAll(PDO::FETCH_ASSOC);
		return $this->_formatHasOne($res);
	}

	private function findCommon($options)
	{
		$pdo = Model::$connections[$this->db];

		$sql = '';
		$sql = $this->_doSelect($sql);
		$sql = $this->_doWhere($sql, $options);
		$sql = $this->_doOrderBy($sql, $options);
		$sql = $this->_doLimit($sql, $options);
		
		$pre = $pdo->prepare($sql);
		$pre->execute();
		
		$res = $pre->fetchAll(PDO::FETCH_ASSOC);
		return $this->_formatCommon($res);
	}



	
	public function count($options)
	{
		$pdo = Model::$connections[$this->db];
		$sql = 'SELECT COUNT(*) FROM '.$this->table.' as '. $this->name.'';
		
		// Conditions
		if(isset($req['conditions']))
		{
			$sql .= ' WHERE ';
			if(!is_array($req['conditions']))
				$sql .= $req['conditions'];
			else
			{
				$cond = array();
				foreach($req['conditions'] as $k => $v)
				{
					if(!is_numeric($v))
						$v = "'".mysql_escape_string($v)."'";
					$cond[] = "$k=$v";
				}
				$sql .= implode(' AND ', $cond);
			}
		}
		
		$pre = $pdo->prepare($sql);
		$pre->execute();
		
		return current(current($pre->fetchAll(PDO::FETCH_BOTH)));
	}
	
	/*
	
	public function save($data)
	{
		$pdo = Model::$connections[$this->db];
		$key = $this->primaryKey;
		$sql = "";
		// On rempli un tableau avec les champs passés
		$fields = array();
		$d = array();
		foreach($data as $k => $v) {
			$fields[] = "$k=:$k";
			$d[":$k"] = $v;
		}
		
		// S'il s'agit d'une UPDATE
		if(isset($data[$key]) && !empty($data[$key])) {
			$sql .= 'UPDATE '.$this->table.' SET '.implode(', ', $fields).' WHERE '.$key.'='.$data[$key];
		}
		else { // Sinon il s'agit d'un INSERT
			$sql .= 'INSERT INTO '.$this->table.' SET '.implode(', ', $fields).'';
		}
		
		// Modification de la BDD
		$pre = $pdo->prepare($sql);
		$bool = $pre->execute($d);
		$this->lastInsertId = $pdo->lastInsertId();
		return $bool;
	}
	
	public function lastInsertId() {
		return $this->lastInsertId;
	}
	
	public function delete($id) {
		$pdo = Model::$connections[$this->db];
		$sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey}=$id";
		$pdo->query($sql);


		$sql = "OPTIMIZE TABLE {$this->table}";
		$pdo->query($sql);
	}*/



	public function beforeFind($options)
	{
		return $options;
	}

	public function afterFind($result)
	{
		return $result;
	}

	public function beforeSave() { }
	public function afterSave() { }

	public function beforeDelete() { }
	public function afterDelete() { }
	
	
}