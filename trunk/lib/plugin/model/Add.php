<?php
require_once("Operation.php");
final class Model_Add extends Operation
{
	public function prepare()
	{
		$ret=true;

		if(!isset($this->table))
		{
			$this->logger->error("Table/Field is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["data"]))
		{
			$this->logger->error("Data is not defined properly.");
			$ret=false;
		}
		$this->data["_status"]=$ret;
		return $ret;
	}

	public function execute()
	{
		$this->data["_status"]=$this->modellink->Save($this->table, $this->data["data"]);
	}
}
?>