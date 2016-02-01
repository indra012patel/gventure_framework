<?php
require_once("Operation.php");
final class Login extends Operation
{
	public function prepare()
	{
		$ret=true;

		if(!isset($this->table))
		{
			$this->logger->error("Table is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["success"]))
		{
			$this->logger->error("Success method is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["failure"]))
		{
			$this->logger->error("Failure method is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["data"]))
		{
			$this->logger->error("Data is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["filter"]))
		{
			$this->logger->error("Filter is not defined properly.");
			$ret=false;
		}

		return $ret;
	}

	public function execute()
	{
		return $this->modellink->Update($this->table, $this->data["data"], $this->data["filter"]);
	}
}
?>