<?php
require_once("database/MysqlManager.php");
require_once("database/MysqliManager.php");

final class Query
{
	private $connection;
	private $data;
	private $sql;
	private $logger;

	public function Query($database)
	{
		$this->connection=$database;
		$this->logger = logger::getRootlogger();
		$this->logger->info("Calling Constructor in Query");
	}

	public function __set($key,$val)
	{
		$this->logger->info("Calling Setter in Query $key=>$val");
		if(strlen($key)>0)
		{
			$this->logger->debug("Setter method is Set :".$key." => ".$val);
			$this->data[strtolower($key)]=$val;
		}
		else
		{
			$this->logger->error("Invalid variable is setting");
		}
		$this->logger->info("End of Calling setter method in Query");
	}

	public function __get($key)
	{
		$this->logger->info("Calling Getter in Query $key=>".$this->data[$key]);
		if(strtolower($key)=="status")
			return $this->status($this->data["filter"]);
		if(strtolower($key)=="datarows")
			return $this->connection->datarows;
		return $this->data[strtolower($key)];
	}
	
	//Filter = Which selected set will be operated
	public function prepare()
	{
		$tmp1="";
		$tmp3="";
		$this->logger->info("Calling prepare function in Query");
		$this->logger->debug("Your table is set to :".$this->data["table"]);
		if(isset($this->data["table"]))
		{
			foreach($this->data as $key=>$val)
			{
				 if(substr($key,0,5) == "field"){
                                 	$dbfl=explode(" ", $val);
                                 	
		                        if(count($dbfl)==1)
		                         	$tmp1.="`".$val."`,";
		                        else 
		                        	$tmp1.="`".$dbfl[0]."` `".$dbfl[1]."`,";
                                 }
			}

			if(isset($tmp1))
				$tmp1=substr($tmp1,0,-1);

			if(isset($this->data["filter"]))
			{
				foreach($this->data["filter"] as $key=>$val)
				{
					$tmp3.="`".$key."`='".$val."' and ";
				}
			}

			$tmp3=substr($tmp3,0,-4);
			if($this->data["filter"]!=null)
				$this->sql="SELECT ".$tmp1." FROM `".$this->data["table"]."` WHERE ".$tmp3;
			else
				$this->sql="SELECT ".$tmp1." FROM `".$this->data["table"]."`";

			if(isset($this->data["order"]))
			{
				$this->sql=$this->sql." order by ".$this->data["order"];
			}

			if(isset($this->data["group"]))
			{
				$this->sql=$this->sql." group by ".$this->data["group"];
			}

			if(isset($this->data["start"]))
			{
				$this->sql=$this->sql." limit ".$this->data["start"].",".$this->data["limit"];
			}
			else{
				if(isset($this->data["limit"]))
				{
					$this->sql=$this->sql." limit ".$this->data["limit"];
				}
			}
		}
		$this->logger->info("End of Calling prepare function in Query");
	}

	public function prepareleftjoin($table, $field, $common)
	{
		$tmp1="";
		$tmp3="";
		$this->logger->info("Calling prepareleftjoin function in Query");
		$this->logger->debug("Your table is set to :".$this->data["table"]);
		if(isset($this->data["table"]))
		{
			foreach($this->data as $key=>$val)
			{
				if(substr($key,0,5) == "field"){
					$tmp1.="tbl1`".$val."`,";
				}
			}
			
			foreach($field as $val)
			{
				$tmp1.="tbl2`".$val."`,";
			}

			if(isset($tmp1))
				$tmp1=substr($tmp1,0,-1);

			$this->logger->debug("Total No of fields are :".$tmp1);
			if(isset($this->data["filter"]))
			{
				foreach($this->data["filter"] as $key=>$val)
				{
					$tmp3.="tbl1`".$key."`='".$val."' and ";
				}
			}

			$tmp3=substr($tmp3,0,-4);

			if($this->data["filter"]!=null)
				$this->sql="SELECT ".$tmp1." FROM `".$this->data["table"]."` `tbl1` LEFT JOIN `".$table."` `tbl2` ON tbl1.".$common[0]."=tbl2.".$common[1]." WHERE ".$tmp3;
			else
				$this->sql="SELECT ".$tmp1." FROM `".$this->data["table"]."` `tbl1` LEFT JOIN `".$table."` `tbl2` ON tbl1.".$common[0]."=tbl2.".$common[1];

			if(isset($this->data["order"]))
			{
				$this->sql=$this->sql." order by ".$this->data["order"];
			}

			if(isset($this->data["group"]))
			{
				$this->sql=$this->sql." group by ".$this->data["group"];
			}

			if(isset($this->data["limit"]))
			{
				$this->sql=$this->sql." limit ".$this->data["limit"];
			}
		}
		$this->logger->info("End of Calling prepareleftjoin function in Query");
	}

	public function raw_prepare($sql)
	{
		//$this->logger->debug($sql);
		$this->sql=$sql;
	}

	public function Execute()
	{
		$this->logger->info("Calling Execute function in Query");
		$this->connection->Open();
		$data=$this->connection->Execute($this->sql);
		$this->connection->Close();
		foreach($this->data as $key=>$val)
			unset($this->data[$key]);
		foreach($this->filter as $key=>$val)
			unset($this->filter[$key]);
		$this->logger->info("End of Calling Execute function in Query");
		return $data;
	}

	public function DataSet()
	{
		$this->logger->info("Calling DataSet function in Query");
		$this->connection->Open();
		$data=$this->connection->DataSet($this->sql);
		$this->data["datarows"]=$this->connection->datarows;
		$this->connection->Close();
		foreach($this->data as $key=>$val)
			unset($this->data[$key]);
			
		if(isset($this->filter)){
			foreach($this->filter as $key=>$val)
				unset($this->filter[$key]);
		}
		$this->logger->info("End of Calling DataSet function in Query");
		return $data;
	}

	public function Row()
	{
		$this->logger->info("Calling Row function in Query");
		$this->connection->Open();
		$data=$this->connection->Row($this->sql);
		$this->connection->Close();
		foreach($this->data as $key=>$val)
			unset($this->data[$key]);
			
		if(isset($this->filter)){
			foreach($this->filter as $key=>$val)
				unset($this->filter[$key]);
		}
		unset($this->sql);
		$this->logger->info("End of Calling Row function in Query");
		return $data;
	}
}
?>