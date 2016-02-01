<?php
require_once("view/View.php");

class ViewLink extends View
{

/***************************************************************************************************
Bellow is Add() method, where all the attribute(template, session, module, action etc.) is set into
 definer. if any attribute s not set then logger throw an error with respected attribute.
****************************************************************************************************/

	public function Add()
	{
		if(!isset($this->template))
			$this->logger->debug("Template is not defined");	
	}

/***************************************************************************************************
Bellow is Details() method, where all the attribute(template, session, module, action etc.) is set into
 definer or assign. if any attribute s not set then logger throw an error with respected attribute.
****************************************************************************************************/
	
	public function Detail()
	{			
		if(isset($this->viewdata["data"]))
			$this->smarty->assign("data", $this->viewdata["data"]);
		else 
			$this->logger->debug("Data is not defined");
			
		if(!isset($this->template))
			$this->logger->debug("Template is not defined");
	}

/***************************************************************************************************
Bellow is Edit() method, where all the attribute(template, session, module, action etc.) is set into
 definer or assign. if any attribute s not set then logger throw an error with respected attribute.
****************************************************************************************************/
	
	public function Edit()
	{	
		if(isset($this->viewdata["data"]))
			$this->smarty->assign("data", $this->viewdata["data"]);
		else 
			$this->logger->debug("Data is not defined");
			
		if(isset($this->viewdata["key"]))
			$this->smarty->assign("keyfield", $this->viewdata["key"]);
		else 
			$this->logger->debug("Keyfield is not defined");
			
		if(!isset($this->template))
			$this->logger->debug("Template is not defined");	
		
	}

/***************************************************************************************************
Bellow is ListView() method, where all the attribute(template, session, module, action etc.) is set into
 definer or assign. if any attribute s not set then logger throw an error with respected attribute.
****************************************************************************************************/

	public function ListView()
	{		
		if(isset($this->viewdata["header"]))
		{
			$this->smarty->assign("header", $this->viewdata["header"]);
			$this->smarty->assign("datacols", count($this->viewdata["header"])+1);
		}
		else 
			$this->logger->debug("Header are not defined");
		
		if(isset($this->viewdata["field"]))
			$this->smarty->assign("field", $this->viewdata["field"]);
		else 
			$this->logger->debug("Fields are not defined");
		
		if(isset($this->viewdata["data"]))
			$this->smarty->assign("data", $this->viewdata["data"]);
		else 
			$this->logger->debug("Data is not defined");
			
		if(isset($this->viewdata["key"]))
			$this->smarty->assign("keyfield", $this->viewdata["key"]);
		else 
			$this->logger->debug("Keyfield is not defined");
		
		if(isset($this->viewdata["row"]))
			$this->smarty->assign("row", $this->viewdata["row"]);
		else 
			$this->logger->debug("Row is not defined");
				
		if(!isset($this->template))
			$this->logger->debug("Template is not defined");	
	}

/***************************************************************************************************
Below is ExecuteUrl() method, where all the attribute($module, $action) is set
into definer or assign. if any attribute s not set then logger throw an error with respected attribute.
****************************************************************************************************/
	
	public function ExecuteUrl($module, $action)
	{
		if(!empty($module) && !empty($action))
			return HTTP_HOST."index.php?session=".$this->session."&module=".encode($module)."&action=".encode($action);
		else
			return HTTP_HOST."index.tpl";
	}

/***************************************************************************************************
Bellow is CreateView() method, where all the attribute(template, session, module, action etc.) is set
into definer or assign. if any attribute s not set then logger throw an error with respected attribute.
****************************************************************************************************/
	
	public function CreateView()
	{
		if(!isset($this->template)){
			$this->logger->debug("View is not properly defined");
			header("HTTP/1.0 404 Not Found");
			return;
		}
		
		$this->smarty->assign("limit", ROW);
		$this->smarty->assign("page", $this->viewdata["page"]);
		$this->smarty->assign("counter", ($this->viewdata["page"]-1)*25);

		$this->smarty->assign("pagename", $this->viewdata["pagename"]);
		$this->smarty->assign("pagetitle", TITLE." : ".$this->viewdata["pagetitle"]);
		$this->smarty->assign("panel", $this->viewdata["basetpl"]);
		$this->smarty->assign("hostname", HTTP_HOST);
		
				
		$this->logger->debug("Base Template : ".$this->viewdata["basetpl"].", View : ".$this->template.", SESSION : ".$this->session.", Module : ".$this->module.", Action : ".$this->action);
		if(isset($this->viewdata["basetpl"]))
			$this->smarty->display("view/".$this->module."/".$this->template);
		else{
			$this->smarty->display("view/".$this->template);
		}
	}
}
?>
