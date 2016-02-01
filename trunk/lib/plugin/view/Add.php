<?php
require_once("UserInterface.php");
final class View_Add extends UserInterface
{
	public function prepare()
	{
		$ret=true;

		$this->logger->info("Calling prepare function in Add.");
		if(!isset($this->data["template"]))
		{
			$this->logger->debug("Template is not defined properly.");
			$ret=false;
		}
		
	
		if(!isset($this->data["formaction"]))
		{
		$this->logger->debug("Form Action is not defined properly.");
		$ret=false;
		}
		
		$this->data["_status"]=$ret;
		$this->logger->info("End of Calling prepare function in Add.");
		return $ret;
	}
}
?>
