<?php
require_once("ModelLink.php");

abstract class Operation
{
	protected $modellink;
	protected $data;
	protected $table;
	protected $sql;
	protected $logger;

	public function __construct($dbtype, $logger)
	{
		$this->logger = $logger;
		
		$this->data["_status"]=false;
		
		$this->logger->info(__file__ ." Calling constructor in Operation.php .");
		if($dbtype=="mysql")
		{
			$this->modellink = new ModelLink($dbtype, $this->logger);
			return true;
		}elseif($dbtype=="mysqli")
		{
			$this->modellink = new ModelLink($dbtype, $this->logger);
			return true;
		}
		else
			return false;
	}
	
	public function __set($key, $val)
	{
		
		//$this->logger->info("Operation Setting fields $key=>$val in Operation");
		
		if($key=="table")
			$this->table=$val;
			
		else if($key=="sql")
			$this->sql=$val;
			
		else if($key=="field")
		{
			if(is_array($val))
				$this->data["field"]=$val;
			else
				$this->logger->debug("Operation Field is not defined properly.");
		}
		else if($key=="filter")
		{
			if(is_array($val))
				$this->data["filter"]=$val;
			else
				$this->logger->debug("Operation Filter is not defined properly.");
		}
		else if($key=="data")
		{
			if(is_array($val))
				$this->data["data"]=$val;
			else
				$this->logger->debug("Operation Data is not defined properly.");
		}
		else
			$this->data[strtolower($key)]=$val;
	}

	public function __unset($key)
	{
		$this->logger->info("Calling __unset in Operation $key=>".$this->data[$key]);
		if($key=="table")
			unset($this->tablename);

		if(strlen($key)>0)
			unset($this->data[strtolower($key)]);
		else
			$this->logger->debug("Operation Invalid variable is setting");
	}
	
	public function __get($key)
	{
		if(strtolower($key)=="status")
		{
			$this->logger->info("Operation Getting fields $key=>".$this->data["_status"]." in Operation");
			return $this->data["_status"];
		}
		else if(strtolower($key)=="rows")
		{
			$this->logger->info("Operation Getting fields $key=>".$this->modellink->rows." in Operation");
			return $this->modellink->rows;
		}
		else if(strtolower($key)=="last")
		{
			$this->logger->info("Operation Getting fields $key=>".$this->modellink->last." in Operation");
			return $this->modellink->last;
		}
		else if(strlen($key)>0)
		{
			$this->logger->info("Operation Getting fields $key=>".$this->data[strtolower($key)]." in Operation");
			return ($this->data[strtolower($key)]); 
		}
		else
			$this->logger->debug("Operation Invalid variable is setting");
	}
	
	public function __isset($key)
	{
		$this->logger->info("Calling __isset in Operation $key=>".$this->data[$key]);
		if(strlen($key)>0)
			return isset($this->data[strtolower($key)]);
		else
			$this->logger->debug("Operation Invalid variable is setting");
	}

	public abstract function prepare();

	public abstract function execute();
}
?>