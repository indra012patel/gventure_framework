<?php
require_once("database/MysqlManager.php");
require_once("database/MysqliManager.php");
final class NonQuery
{
	private $connection;
	private $data;
	private $sql;
	private $logger;
	private $last;

	public function NonQuery($database)
	{
		$this->connection=$database;
		$this->logger = logger::getRootlogger();
		$this->logger->info("Calling Constructor in NonQuery");		
	}

	public function __set($key,$val)
	{
		$this->logger->info("Calling Setter in NonQuery $key=>$val");
		if(strlen($key)>0)
		{
				$this->logger->debug("Setter method is Set :".$key." => ".$val);
			$this->data[$key]=$val;
		}
		else
			$this->logger->error( "Invalid variable is setting");
	}

	public function __get($key)
	{
		$this->logger->info("Calling Getter in Query $key=>".$this->data[$key]);
		if(strtolower($key)=="status")
			return $this->status($this->data["filter"]);
			
		if(strtolower($key)=="last")
			return $this->last;

		return $this->data[$key];
	}
	
	//Type = INSERT/UPDATE/DELETE
	//Filter = Which selected set will be operated
	public function prepare()
	{
		$tmp1="";
		$tmp2="";
		$tmp3="";
		
		$this->logger->info("Calling prepare function in NonQuery");
		$this->logger->debug("Your table is set to :".$this->data["table"]);
		if(isset($this->data["_table"]))
		{								
			foreach($this->data as $key=>$val)
			{
				if($key != "_table" && $key != "_type" && $key != "filter"){
					if(strtoupper($this->data["_type"])=="INSERT")
					{
						$tmp1.="`".$key."`,";
						$tmp2.="'".$val."',";
					}
					else
						$tmp1.="`".$key."`='".$val."',";
				}
			}
			$this->logger->debug("Your table field is set to :".$tmp1);
			if(isset($tmp1))
				$tmp1=substr($tmp1,0,-1);
				
			$this->logger->debug("Your value is set to :".$tmp2);
			if(isset($tmp2))
				$tmp2=substr($tmp2,0,-1);

			if(isset($this->data["filter"]))
			{
				foreach($this->data["filter"] as $key=>$val)
				{
					$tmp3.="`".$key."`='".$val."' and ";
				}
			}

			$this->logger->debug("Your filter is set to :".$tmp3);
			if(isset($tmp2))
				$tmp3=substr($tmp3,0,-4);
			
			switch(strtoupper($this->data["_type"]))
			{
				case 'INSERT':
					$this->sql="INSERT INTO `".$this->data["_table"]."` (".$tmp1.") VALUES (".$tmp2.")";
					break;
				case 'UPDATE':
					$this->sql="UPDATE `".$this->data["_table"]."` SET ".$tmp1." WHERE (".$tmp3.")";
					break;
				case 'DELETE':
					$this->sql="DELETE FROM `".$this->data["_table"]."` WHERE (".$tmp3.")";
					break;
				case 'STATUS':
				{
					$this->sql="UPDATE FROM `".$this->data["_table"]."` WHERE (".$tmp3.")";
					break;
				}
			}
		}
	}

	public function raw_prepare($sql)
	{
		//$this->logger->debug($sql);
		$this->sql=$sql;
	}

	public function Execute()
	{
		$this->logger->info("Calling Execute function in NonQuery");
		$this->connection->Open();
		$status=$this->connection->NonExecute($this->sql);
		if(strtoupper($this->data["_type"])=="INSERT")
		{
			$this->last=$this->connection->last;
		}
		$this->connection->Close();
		
		foreach($this->data as $key=>$val)
			unset($this->data[$key]);
		foreach($this->filter as $key=>$val)
			unset($this->filter[$key]);
		unset($this->sql);
		$this->logger->info("End of Calling Execute function in NonQuery");
		return $status;
	}
}
?>