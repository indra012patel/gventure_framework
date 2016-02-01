<?php
require_once("database/MysqlManager.php");
require_once("database/MysqliManager.php");
require_once("database/NonQuery.php");
require_once("database/Query.php");

class ModelObject
{
	private $database;
	private $data;
	private $query;
	private $nonquery;
	private $tablename;
	private $filter;
	private $sql;
	private $logger;
	
	public function __construct($type)
	{
		$this->logger = logger::getRootlogger();
		$this->logger->info("Calling Constructor in ModelObject");
		if(strtolower($type)=="mysqli")
			$this->database= new MysqliManager();
		else if(strtolower($type)=="mysql")
			$this->database= new MysqlManager();
		else
		{
			$this->database=null;
			return false;
		}

		$this->query = new Query($this->database);
		$this->nonquery = new NonQuery($this->database);

		return true;
	}

    public function __set($key, $val)
	{
		$this->logger->info("Calling Setter in ModelObject $key=>$val");
		if($key=="filter")
		{
			$this->filter=$val;
			return;
		}
		if($key=="sql")
		{
			$this->sql=$val;
			return;
		}
	
		if($key=="table")
		{
			$this->tablename=$val;
			return;
		}
		if(strlen($key)>0)
			$this->data[strtolower($key)]=$val;
		else
			echo "Invalid variable is setting";
	}
	
	public function __get($key)
	{
		$this->logger->info("Calling Getter in ModelObject $key=>".$this->data[$key]);
		if($key=="filter")
		{
			return $this->filter;
		}
		if($key=="rows")
		{
			$this->logger->info("ModelObject Getting fields rows=>".$this->query->datarows);
			return $this->query->datarows;
		}
		if($key=="last")
		{
			$this->logger->info("ModelObject Getting fields last=>".  $this->nonquery->last);
			return $this->getlastid();
		}
		if($key=="sql")
		{
			return $this->sql;
		}
	
		if($key=="table")
		{
			return $this->tablename;
		}
		if(strlen($key)>0)
			return $this->data[strtolower($key)];
	}
	
	public function __isset($key)
	{
		$this->logger->info("Calling __isset in ModelObject $key=>".$this->data[$key]);
		if($key=="filter")
		{
			return is_array($this->filter);
		}
		if($key=="sql")
		{
			return isset($this->sql);
		}
	
		if($key=="table")
		{
			return isset($this->tablename);
		}
		if(strlen($key)>0)
			return isset($this->data[strtolower($key)]);
	}
	
	public function __unset($key)
	{
		$this->logger->info("Calling __unset in ModelObject $key=>".$this->data[$key]);
		if($key=="filter")
		{
			unset($this->filter);
		}
		if($key=="sql")
		{
			unset($this->sql);
		}
	
		if($key=="table")
		{
			unset($this->tablename);
		}
		if(strlen($key)>0)
			unset($this->data[strtolower($key)]);
	}

	public function getrow()
	{
		$this->logger->info("Calling getrow function in ModelObject.");
		$this->logger->debug("Data Rows : ".$this->query->datarows);
		return $this->query->datarows;
	}

	public function getlastid()
	{
		$this->logger->info("Calling getlastid function in ModelObject.");
		$this->logger->debug("Last ID : ".$this->nonquery->last);
		return $this->nonquery->last;
	}

	public function clearnonquery()
	{
		$this->logger->info("Calling clearnonquery function in ModelObject.");
		$this->logger->error("Clearing all the current nonquery variables");
		$this->table="";
		$this->sql="";
		$this->filter=array();
		$this->data=array();
		//unset($this->nonquery);
		//$this->nonquery = new NonQuery($this->database);
	}

	public function clearquery()
	{
		$this->logger->info("Calling clearquery function in ModelObject.");
		$this->logger->error("Clearing all the current query variables");
		$this->table="";
		$this->sql="";
		$this->filter=array();
		$this->data=array();
		//unset($this->query);
		//$this->query = new Query($this->database);
	}

	public function runquery()
	{
		$this->logger->info("Calling runquery function in ModelObject.");
		$this->query->raw_prepare($this->sql);
		return $this->query->DataSet();
	}

    public function Add()
	{
		$this->logger->info("Calling Add function in ModelObject.");
		$this->nonquery->_table=$this->tablename;
		$this->nonquery->_type="insert";
		foreach($this->data as $key=>$val)
		{
			$this->nonquery->$key=$val;
		}

		$this->nonquery->prepare();
		$status=$this->nonquery->Execute();
		
		$this->logger->info("End of Calling Add function in ModelObject.");
		return $status;
	}

    public function Update()
	{
		$this->logger->info("Calling Update function in ModelObject.");
		$this->nonquery->_table=$this->tablename;
		$this->nonquery->_type="update";
		foreach($this->data as $key=>$val)
		{
			$this->nonquery->$key=$val;
		}
		$this->nonquery->filter=$this->filter;
		$this->nonquery->prepare();

		$this->logger->info("End of Calling Update function in ModelObject.");
		return $this->nonquery->Execute();
	}

    public function Delete()
	{
		$this->logger->info("Calling Delete function in ModelObject.");
		$this->nonquery->_table=$this->tablename;
		$this->nonquery->_type="delete";
		foreach($this->data as $key=>$val)
		{
			$this->nonquery->$key=$val;
		}
		$this->nonquery->filter=$this->filter;
		$this->nonquery->prepare();

		$this->logger->info("End of Calling Delete function in ModelObject.");
		return $this->nonquery->Execute();
	}

	public function RowView()
	{
		$this->logger->info("Calling RowView function in ModelObject.");
		$this->query->table=$this->tablename;
		$this->query->type="select";
		$i=0;
		foreach($this->data as $key=>$val)
		{
			$tmp="field".$i;
			$this->query->$tmp=$val;
			$i++;
		}
		$this->query->filter=$this->filter;
		$this->query->prepare();
		
		$this->logger->info("End of Calling Add function in ModelObject.");
		return $this->query->Row();
	}

    public function DataView()
	{
		$this->logger->info("Calling DataView function in ModelObject.");
		$this->query->table=$this->tablename;
		$this->query->type="select";
		$i=0;
		foreach($this->data as $key=>$val)
		{
			$tmp="field".$i;
			$this->query->$tmp=$val;
			$i++;
		}
		$this->query->filter=$this->filter;
		$this->query->prepare();
		
		$this->logger->info("End of Calling DataView function in ModelObject.");
		return $this->query->Execute();
	}

	public function GridView()
	{
		$this->logger->info("Calling GridView function in ModelObject.");
		$this->query->table=$this->tablename;
		$this->query->type="select";
		foreach($this->data as $key=>$val)
		{
			$this->query->$key=$val;
		}
		$this->query->filter=$this->filter;

		$this->query->prepare();

		$this->logger->info("End of Calling GridView function in ModelObject.");
		return $this->query->DataSet();
	}

	public function JoinDataView($table, $field, $join, $filter)
	{
		$tbla="";
		$this->logger->info("Calling JoinDataView function in ModelObject.");
		foreach($this->data as $val)
		{
			$tbla.="`tbla`.`".$val."`, ";
		}
		$tbla=substr($tbla,0,-2);
		$tbla="";
		foreach($field as $val)
		{
			$tblb.="`tblb`.`".$val."`, ";
		}
		$tblb=substr($tblb,0,-2);
		$filtera="";
		foreach($this->filter as $key=>$val){
			$filtera.="`tblb`.`".$key."`='".$val."' AND ";
		}
		$filtera=substr($filtera,0,-5);
		$filterb="";
		foreach($filter as $key=>$val){
			$filterb.="`tblb`.`".$key."`='".$val."' AND ";
		}
		$filtera=substr($filtera,0,-5);
		
		$this->query->raw_prepare("SELECT ".$tbla.", ".$tblb." FROM `".$this->tablename."` tbla LEFT JOIN `".$table."` tblb ON `tbla`.`".$join[0]."`=`tblb`.`".$join[1]."` WHERE ".$filtera." AND ".$filterb);
		return $this->query->Execute();
	}

	public function RawGridView($sql)
	{
		$this->query->raw_prepare($sql);
		return $this->query->DataSet();
	}

}
?>