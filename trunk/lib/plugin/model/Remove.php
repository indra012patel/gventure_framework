<?php
require_once("Operation.php");
final class Model_Remove extends Operation
{
	public function prepare()
	{
		$ret=true;

		if(!isset($this->table))
		{
			$this->logger->debug("Table/Field is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["filter"]))
		{
			$this->logger->debug("Filter is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["module"]))
		{
			$this->logger->error("Model_Deleted, Module is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["action"]))
		{
			$this->logger->error("Model_Deleted, Action is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["session"]))
		{
			$this->logger->error("Model_Deleted, Session is not defined properly.");
			$ret=false;
		}
		$this->data["_status"]=$ret;

		return $ret;
	}

	public function execute()
	{
		return $this->modellink->Status($this->table, array("deleted"=>1), $this->data["filter"]);
	}
}
?>