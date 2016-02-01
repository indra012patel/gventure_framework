<?php
require_once("UserInterface.php");
final class View_Update extends UserInterface
{
	public function prepare()
	{
		$ret=true;

		$this->logger->info("Calling prepare function in Update.");
		if(isset($this->data["data"]))
		{
			$this->smarty->assign("data", $this->data["data"]);
		}
		else 
		{
			$this->logger->debug("Data is not defined");
			$ret=false;
		}
			
		if(isset($this->data["key"]))
		{
			$this->smarty->assign("keyfield", $this->data["key"]);
		}
		else 
		{
			$this->logger->debug("Keyfield is not defined");
			$ret=false;
		}
			
		if(!isset($this->data["template"]))
		{
			$this->logger->debug("Template is not defined");
			$ret=false;
		}
		
		if(!isset($this->data["action"]))
		{
		$this->logger->debug("Form Action is not defined properly.");
		$ret=false;
		}
		
		$this->data["_status"]=$ret;
		
		$this->logger->info("End of Calling prepare function in Update.");
		return $ret;
	}
}
?>
