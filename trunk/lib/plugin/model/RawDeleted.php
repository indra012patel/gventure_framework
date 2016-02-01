<?php
require_once("Operation.php");
final class Model_RawDeleted extends Operation
{
	public function prepare()
	{
		$ret=true;

		if(!isset($this->table))
		{
			$this->logger->error("Model_Deleted, Table is not defined properly.");
			$ret=false;
		}
		if(!isset($this->data["filter"]))
		{
			$this->logger->error("Model_Deleted, Filter is not defined properly.");
			$ret=false;
		}
		$this->data["_status"]=$ret;
		return $ret;
	}

	public function execute()
	{
		return $this->modellink->DeleteVal($this->table, $this->data["filter"]);
	}
}
?>