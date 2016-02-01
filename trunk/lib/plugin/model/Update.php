<?php
require_once("Operation.php");
final class Model_Update extends Operation
{
	public function prepare()
	{
		$ret=true;

		if(!isset($this->table))
		{
			$this->logger->debug("Table is not defined properly.");
			$ret=false;
		}
		/* if(!isset($this->data["success"]))
		{
			$this->logger->debug("Success method is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["failure"]))
		{
			$this->logger->debug("Failure method is not defined properly.");
			$ret=false;
		} */
		if(!isset($this->data["data"]))
		{
			$this->logger->debug("Data is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["filter"]))
		{
			$this->logger->debug("Filter is not defined properly.");
			$ret=false;
		}
		$this->data["_status"]=$ret;

		return $ret;
	}

	public function execute()
	{
		$this->data["_status"]=$this->modellink->Update($this->table, $this->data["data"], $this->data["filter"]);
	}
}
?>