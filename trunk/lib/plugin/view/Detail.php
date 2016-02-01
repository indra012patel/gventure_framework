<?php
require_once("UserInterface.php");
final class View_Detail extends UserInterface
{
	public function prepare()
	{
		$ret=true;
		
		$this->logger->info("Calling prepare function in Detail.");
		if(isset($this->data["data"]))
			$this->smarty->assign("data", $this->data["data"]);
		else 
		{
			$this->logger->error("View_Detail Data is not defined");
			$ret=false;
		}
			
		if(isset($this->data["key"]))
			$this->smarty->assign("keyfield", $this->data["key"]);
		else 
		{
			$this->logger->error("View_Detail Keyfield is not defined");
			$ret=false;
		}
				
		if(!isset($this->data["template"]))
		{
			$this->logger->error("View_Detail Template is not defined");
			$ret=false;
		}
			
		$this->data["_status"]=$ret;
		return $ret;
	}
}
?>