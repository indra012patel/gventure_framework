<?php
require_once("Operation.php");
final class Model_Status extends Operation
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
		if(!isset($this->data["fieldstatus"]))
		{
			$this->logger->debug("Filter is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["valuestatus"]))
		{
			$this->logger->debug("Filter is not defined properly.");
			$ret=false;
		}
		$this->data["_status"]=$ret;

		return $ret;
	}

	public function execute()
	{
		return $this->modellink->Status($this->table, array($this->data["fieldstatus"]=>$this->data["valuestatus"]), $this->data["filter"]);
	}
}
?>