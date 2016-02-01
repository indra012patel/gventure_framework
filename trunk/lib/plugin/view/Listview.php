<?php
require_once("UserInterface.php");
final class View_ListView extends UserInterface
{
	public function prepare()
	{
		$ret=true;
		

		$this->logger->info("Calling prepare function in ListView.");
		if(isset($this->data["header"]))
		{
			$this->smarty->assign("header", $this->data["header"]);
			$this->smarty->assign("datacols", count($this->data["header"])+1);
		}
		else 
		{
			$this->logger->error("View_ListView Header are not defined");
			$ret=false;
		}
		
		if(isset($this->data["field"]))
			$this->smarty->assign("field", $this->data["field"]);
		else
		{
			$this->logger->error("View_ListView Fields are not defined");
			$ret=false;
		}
		
		if($this->data["data"])
			$this->smarty->assign("data", $this->data["data"]);
		else 
		{
			$this->logger->error("View_ListView Data is not defined");
			$ret=false;
		}
			
		if(isset($this->data["key"]))
			$this->smarty->assign("keyfield", $this->data["key"]);
		else 
		{
			$this->logger->error("View_ListView Keyfield is not defined");
			$ret=false;
		}
		
		if($this->data["row"]>=0)
			$this->smarty->assign("row", $this->data["row"]);
		else 
		{
			$this->logger->error("View_ListView Row is not defined");
			$ret=false;
		}
		if($this->data["page"]>0)
			$this->smarty->assign("page", $this->data["page"]);
		else 
		{
			$this->logger->error("View_ListView Page is not defined");
			$ret=false;
		}
				
		if(!isset($this->data["template"]))
		{
			$this->logger->error("View_ListView Template is not defined");
			$ret=false;
		}
		$this->data["_status"]=$ret;
		return $ret;
	}
}
?>
