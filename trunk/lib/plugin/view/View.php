<?php
abstract class View
{
	protected $viewdata;
	protected $logger;
	protected $smarty;
	protected $module;
	protected $action;
	protected $session;
	protected $template;
	
	public function __construct($smarty, $basetpl="", $pagetitle="", $pagename="")
	{
		$this->logger = logger::getRootlogger();
		
		if(defined('SESSION'))
			$this->session=SESSION;
		else 
			$this->logger->debug("Session is not defined");	
		if(defined('MODULE'))
			$this->module=MODULE;
		else 
			$this->logger->debug("Module is not defined");	
		if(defined('ACTION'))
			$this->action=ACTION;
		else 
			$this->logger->debug("Action is not defined");

		$this->smarty=$smarty;
		
		$this->viewdata["pagetitle"]=$pagetitle;
		$this->viewdata["pagename"]=$pagename;
		
		
		
		if(strlen($basetpl)==0)
			$this->viewdata["basetpl"]="index.tpl";
		else
			$this->viewdata["basetpl"]=$basetpl;
	}
	
	public function __set($key, $val)
	{
		if($key=="header")
			$this->viewdata["header"]=$val;
		else if($key=="fields")
			$this->viewdata["field"]=$val;
		else if($key=="pagetitle")
			$this->viewdata["pagetitle"]=$val;
		else if($key=="pagename")
			$this->viewdata["pagename"]=$val;
		else if($key=="data")
			$this->viewdata["data"]=$val;
		else if($key=="template")
			$this->template=$val;
		else
			return;
	}

/***************************************************************************************************
Bellow is Add() method, witch is used to define template, session, logger, module, action for displaying add 
template so that end user enter their expected information into it. 
****************************************************************************************************/
	
	public abstract function Add();

/***************************************************************************************************
Bellow is Edit() method, witch is used to define template, session, logger, module, action, data, key for 
displaying edit template so that end user can update their information into it. 
****************************************************************************************************/
	
	public abstract function Edit();

/***************************************************************************************************
Bellow is Details() method, witch is used to define template, session, logger, module, action for 
displaying details template. It contains the detail information of respected page.
****************************************************************************************************/
	
	public abstract function Detail();

/***************************************************************************************************
Bellow is ListView() method, witch is used to define template, session, logger, module, row, action, 
header, fields for displaying list template. It contains the list of respected page.
****************************************************************************************************/
	
	public abstract function ListView();
	

	public abstract function ExecuteUrl($module, $action);

/***************************************************************************************************
Bellow is CreateView() method, witch is used manage front end of templates.
****************************************************************************************************/
	
	public abstract function CreateView();
}
?>
