<?php
require_once("Operation.php");
final class Model_Option extends Operation
{
	public function prepare()
	{
		$ret=true;

		$this->logger->info("Calling prepare function in Option.");
		if(!isset($this->table))
		{
			$this->logger->debug("Table is not defined properly in Option.");
			$ret=false;
		}
		if(!isset($this->data["_field"]))
		{
			$this->logger->debug("Field is not defined properly in Option.");
			$ret=false;
		}
		if(!isset($this->data["filter"]))
		{
			$this->logger->debug("Filter is not defined properly in Option.");
			$ret=false;
		}
		if(!isset($this->data["_key"]))
		{
			$this->logger->debug("Key is not defined properly in Option.");
			$ret=false;
		}
		
		$this->data["_status"]=$ret;

		return $ret;
	}

	public function execute()
	{
		$this->logger->info("Option function execute in Operation");
		$data=$this->modellink->Options($this->table, $this->data["_field"], $this->data["filter"], $this->data["_key"]);

		if(is_array($data))
		{
			$this->logger->debug("Option function data is successfully fetched");
			$this->data["data"]=$data;
			$this->data["_status"]=true;
			return $data;
		}
		else
		{
			$this->logger->debug("Option function data is not fetched");
			$this->data["_status"]=false;
			return NULL;
		}
	}
}
?>