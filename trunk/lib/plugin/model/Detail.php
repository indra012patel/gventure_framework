<?php
require_once("Operation.php");
final class Model_Detail extends Operation
{
public function prepare()
	{
		
		$this->logger->info("Detail function prepare in Operation");
		$ret=true;

		$this->logger->info("Calling prepare function in Detail.");
		if(!isset($this->table))
		{
			$this->logger->error("Detail Table is not defined properly.");
			$ret=false;
		}

		if(!isset($this->data["field"]))
		{
			$this->logger->error("Detail Field is not defined properly.");
			$ret=false;
		}

		if(!isset($this->data["filter"]))
		{
			$this->logger->error("Detail Filter is not defined properly.");
			$ret=false;
		}
		$this->data["_status"]=$ret;

		$this->logger->info("End of Calling prepare function in Detail.");
		return $ret;
	}

	public function execute()
	{
		$this->logger->info("Calling execute function in Detail.");
		$this->logger->debug("Table is :".$this->table);
		$this->logger->debug("Fields are :".$this->data["field"]);
		$this->logger->debug("Filters are :".$this->data["filter"]);
		$data=$this->modellink->Detail($this->table, $this->data["field"], $this->data["filter"]);
		$this->logger->debug("Detail function data is successfully fetched : ".$this->modellink->rows);
		if($this->modellink->rows>0)
		{
			$this->logger->debug("Detail function data is successfully fetched");
			$this->data["data"]=$data;
			$this->data["_status"]=true;
			$this->logger->info("End of Calling execute function in Detail.");
			return $data;
		}
		else
		{
			$this->logger->debug("Detail function data is not fetched");
			$this->data["_status"]=false;
			$this->logger->info("End of Calling execute function in Detail.");
			return NULL;
		}
	}
}
?>
