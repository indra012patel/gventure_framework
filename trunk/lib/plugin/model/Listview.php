<?php
require_once("Operation.php");
final class Model_ListView extends Operation
{
	public function prepare()
	{
		
		$this->logger->info("ListView function prepare in Operation");
		$ret=true;

		if(!isset($this->table))
		{
			$this->logger->error("Table is not defined properly.");
			$ret=false;
		}

		if(!isset($this->data["field"]))
		{
			$this->logger->error("Field is not defined properly.");
			$ret=false;
		}

		if(!isset($this->data["filter"]))
		{
			$this->logger->error("Filter is not defined properly.");
			$ret=false;
		}
		$this->data["_status"]=$ret;

		return $ret;
	}

	public function execute()
	{
		
		$this->logger->info("ListView function execute in Operation");
		$data=$this->modellink->ListView($this->table, $this->data["field"], $this->data["filter"]);

		if(is_array($data))
		{
			$this->logger->debug("ListView function data is successfully fetched");
			$this->data["data"]=$data;
			$this->data["_status"]=true;
			return $data;
		}
		else
		{
			$this->logger->debug("ListView function data is not fetched");
			$this->data["_status"]=false;
		}
	}
}
?>