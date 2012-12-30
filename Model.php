<?php
class Model{
	protected $table = "";
	
	protected $timestampable = false; // si ce flag est à true, il faut des champs created_at et updated_at
	protected $primary = array('id');

	protected $_wrapper;
	protected $_data = array();
	public function __construct()
	{
		$this->_wrapper = DataBase::instance();
	}

	public function error()
	{
		return $this->_wrapper->getErrorMessage();
	}

	public function setFromArray($data)
	{
	    foreach ($data as $columnName => $value) {
		   	$this->__set($columnName, $value);
	    }
	}

	public function __set($name, $value)
	{
		 $this->_data[$name] = $value;
	}

	public function __get($name)
	{
		if (!array_key_exists($name, $this->_data)) {
			throw new Exception("l'attribut $name n'existe pas.", 1);
        }
        return $this->_data[$name];
	}

	public function toArray()
	{
		return $this->_data;
	}

	public function find($id)
	{
		$data = $this->_wrapper->queryFirst("SELECT * FROM ".$this->table." WHERE id = :id",array('id' => $id));
		if(!empty($data[0])){
			$this->setFromArray($data[0]);
			return $data[0];
		}
		else{
			return false;
		}
	}

	public function findBy(array $params){
		return $this->_wrapper->select($this->table,$params);
	}

	public function fetchAll(){
		return $this->_wrapper->query("SELECT * FROM ".$this->table);
	}

	public function save($data = null)
	{
		if(is_array($data)){
			$this->setFromArray($data);
		}
		$where = array();
		foreach ($this->primary as $key) {
			if(array_key_exists($key, $this->_data)){
				$where[$key] = $this->_data[$key];
			}
		}
		if(count($where) == count($this->primary)){
			//Update
			$this->_wrapper->update($this->table,$this->_data,$where,$this->timestampable);
		}
		else{
			//Insert
			$this->_data[$this->primary[0]] = $this->_wrapper->insert($this->table,$this->_data,$this->timestampable);
		}
		return $this->_data[$this->primary[0]];
	}
}