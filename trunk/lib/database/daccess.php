<?php
abstract class DataAcess
{
	protected $database;
	protected $data;
	protected $query;
	protected $nonquery;
	
	public function __construct($type, $host, $user, $passwd, $database, $port)
	{
		if(strtolower($type)=="mysqli")
			$this->database= new Mysqli();
		else if(strtolower($type)=="mysql")
			$this->database= new Mysql();
		else
		{
			$this->database=null;
			return false;
		}

		$this->database->username=$user;
		$this->database->password=$passwd;
		$this->database->host=$host;
		$this->database->port=$port;
		$this->database->database=$database;

		$this->query = new Query($this->database);
		$this->nonquery = new NonQuery($this->database);

		return true;
	}

    public function __set($key,$val)
	{
		if(strlen($key)>0)
			$this->data[strtolower($key)]=$val;
		else
			echo "Invalid variable is setting";
	}
    
    public abstract function __get($key);
    
    public abstract function Add();

    public abstract function Update();

    public abstract function Delete();

    public abstract function Search();
}
?>