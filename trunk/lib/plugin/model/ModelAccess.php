<?php
require_once("model/ModelObject.php");
abstract class ModelAccess
{
	protected $dbobj;
	protected $data;
	protected $logger;
	protected $last;
	
	public function __construct($type, $logger)
	{
		$this->logger=$logger;
		$this->logger->info("Function constructor in ModelAccess");
		$this->dbobj=new ModelObject($type);
		$this->logger = logger::getRootlogger();
		return true;
	}

    public function __set($key, $val)
	{
		$this->logger->info("Setting fields $key=>$val in ModelAccess");
		if($key=="filter")
		{
			$this->dbobj->filter=$val;
			return;
		}
		if($key=="sql")
		{
			$this->dbobj->sql=$sql;
			return;
		}
		if(strlen($key)>0)
			$this->dbobj->data[strtolower($key)]=$val;
		else
			echo "Invalid variable is setting";
	}
	
	public function __unset($key)
	{
		if($key=="filter")
		{
			unset($this->dbobj->filter);
			return;
		}
		if($key=="sql")
		{
			unset($this->dbobj->sql);
			return;
		}
		if(strlen($key)>0)
			unset($this->dbobj->data[strtolower($key)]);
		else
			echo "Invalid variable is setting";
	}
	
	public function __get($key)
	{
		
		if($key=="rows")
		{
			$this->logger->info("ModelAccess Getting fields $key=>". $this->data["row"]);
			return $this->data["row"];
		}
		else if($key=="last")
			return $this->last;
		else
		{
			$this->logger->info("ModelAccess Getting fields $key=>". $this->dbobj->data[strtolower($key)]);
			return ($this->dbobj->data[strtolower($key)]);
		}
	}
	
	public function __isset($key)
	{
		if($key=="filter")
		{
			return isset($this->dbobj->filter);
		}
		if($key=="sql")
		{
			return isset($this->dbobj->sql);
		}
		if(strlen($key)>0)
			return isset($this->dbobj->data[strtolower($key)]);
		else
			echo "Invalid variable is setting";
	}
    

/***************************************************************************************************
Bellow is Detail() method, it is used to retrieve information from database table. It takes 3 argument
param 1: table-name Data Type STRING
param 2: table-column Data Type  ARRAY
param 3: key Data Type MIXED(INT, STRING)

****************************************************************************************************/
  

    	public abstract function Detail($table="", $field=array(), $filter=array());

/***************************************************************************************************
Bellow is UpdateDetail() method, it is used to update information of database table. It takes 3 argument
param 1: table-name Data Type STRING
param 2: table-column Data Type  ARRAY
param 3: key Data Type MIXED(INT, STRING)
****************************************************************************************************/

    	public abstract function Update($table="", $field=array(), $filter=array());

/***************************************************************************************************
Bellow is Save() method, it is used to insert information into database table. It takes 2 argument
param 1: table-name Data Type STRING
param 2: table-column Data Type  ARRAY

****************************************************************************************************/

    	public abstract function Save($table="", $field=array());


/***************************************************************************************************
Bellow is ListView() method, it is used to retrieve information from database table. It takes 3 argument
param 1: table-name Data Type STRING
param 2: table-column Data Type  ARRAY
param 3: key Data Type MIXED(INT, STRING)
****************************************************************************************************/

    	public abstract function ListView($table="", $field=array(), $filter=array());

/***************************************************************************************************
Bellow is Remove() method, it is used to remove information of database table. It takes 2 argument
param 1: table-name Data Type STRING
param 2: key Data Type MIXED(INT, STRING)
****************************************************************************************************/
		
		public abstract function Remove($table="", $filter=array());

/***************************************************************************************************
Bellow is DeleteVal() method, it is used to delete information from database table. It takes 2 argument
param 1: table-name Data Type STRING
param 2: key Data Type MIXED(INT, STRING)
****************************************************************************************************/

		public abstract function DeleteVal($table="", $filter=array());

/***************************************************************************************************
Bellow is Options() method, it is used to retrieve information from database table. It takes 4 argument
param 1: table-name Data Type STRING
param 2: table-column Data Type  ARRAY
param 3: key Data Type MIXED(INT, STRING)
param 4: optional parameter
This is a special function it is used for print HTML input form <select></select> tag. 
****************************************************************************************************/
		
		public abstract function Options($table="", $field=array(), $filter=array(), $key="");
		
		public abstract function RawGridView($sql);
}
?>
