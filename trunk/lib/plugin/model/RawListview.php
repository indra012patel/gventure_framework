<?php
require_once("Operation.php");
final class Model_RawListView extends Operation
{
	public function prepare()
	{
		
		$this->logger->info("RawListView function prepare in Operation");
		$ret=true;

		$this->logger->info("Calling prepare function in RawListView.");
		if(!isset($this->sql) || strlen(trim($this->sql))==0)
		{
			$this->logger->error("SQL Query is not defined properly.");
			$ret=false;
		}

		$this->data["_status"]=$ret;

		$this->logger->info("End of Calling prepare function in RawListView.");
		return $ret;
	}

	public function execute()
	{
		
		$this->logger->info("RawListView function execute in Operation");
		$data=$this->modellink->RawGridView($this->sql);

		if(is_array($data))
		{
			$this->logger->debug("RawListView function data is successfully fetched");
			$this->data["data"]=$data;
			$this->data["_status"]=true;
			return $data;
		}
		else
		{
			$this->logger->debug("RawListView function data is not fetched");
			$this->data["_status"]=false;
		}
	}
}
?>