<?php
abstract class UserInterface
{
	protected $data;
	protected $logger;
	protected $smarty;
	private $module;
	private $action;
	private $session;
	private $row;
	
	public function __construct($smarty, $logger)
	{
		$this->logger = $logger;	
		$this->logger->info("UserInterface Function constructor in UserInterface");
		$this->smarty=$smarty;
		$this->row=ROW;
		//this SESSTABLE is used for navigation of VoIP switch
		$smarty->assign("LOGINTYPE", LOGINTYPE);
		/*if(defined('SESSION'))
			$this->session=SESSION;
		else 
			$this->logger->debug("UserInterface Session is not defined");	
			
		if(defined('MODULE'))
			$this->module=MODULE;
		else 
			$this->logger->debug("UserInterface Module is not defined");	
		if(defined('ACTION'))
			$this->action=ACTION;
		else 
			$this->logger->debug("UserInterface Action is not defined"); */
		
	}
	
	public function __set($key, $val)
	{
		$this->logger->info("UserInterface Setting fields $key=>$val in Operation");
		if($key=="action")
			$this->action=$val;
		if($key=="module")
			$this->module=$val;
		if($key=="session")
			$this->session=$val;

		$this->data[strtolower($key)]=$val;
	}

	public function __unset($key)
	{
		if($key=="template")
			unset($this->template);

		if(strlen($key)>0)
			unset($this->data[strtolower($key)]);
		else
			$this->logger->debug("UserInterface Invalid variable is setting");
	}
	
	public function __get($key)
	{
		if(strlen($key)>0)
			return ($this->data[strtolower($key)]);
		else if($key=="status")
			return $this->data["_status"];
		else
			$this->logger->debug("UserInterface Invalid variable is setting");
	}
	
	public function __isset($key)
	{
		if(strlen($key)>0)
			return isset($this->data[strtolower($key)]);
		else
			$this->logger->debug("UserInterface Invalid variable is setting");
	}

	public abstract function prepare();
	
	public function Options($field, $data)
	{
		$this->logger->debug("Calling Option box in UserInterface");
		if(is_array($data) && strlen($field)>0) 
			$this->smarty->assign($field, $data);
		else 
			$this->logger->error("Check Field/Data is not properly defined");
	}

	public function assign($field, $value)
	{
		$this->logger->debug("Calling Assign in UserInterface");
		if(strlen($field)>0 && is_array($value))
		{
			if(count($value)>0)
				$this->smarty->assign($field, $value);
		}
		else if(strlen($value)>0 && strlen($field)>0) 
			$this->smarty->assign($field, $value);
		else 
			$this->logger->error("Check Field/Value is not properly defined");
	}

	public function execute($roles)
	{
		$this->logger->info("Calling execute function in UserInterface");
		$this->logger->debug("UserInterface Executing View ...");
		
		if(!$this->data["_status"])
		{
			$this->logger->debug("UserInterface View is not prepare properly");
			return $this->data["_status"];
		}
			
		if(!isset($this->template)){
			$this->logger->debug("UserInterface View is not properly defined");
			header("HTTP/1.0 404 Not Found");
			return;
		}
		$this->smarty->assign("USERTITLE", $_SESSION["USERTITLE"]);
		$this->smarty->assign("ROLES", $roles);
		$this->smarty->assign("CSS", $_SESSION["CSS"]);
		$this->smarty->assign("THEME", $_SESSION["THEME"]);
		$this->smarty->assign("RESPONSIVE", $_SESSION["RESPONSIVE"]);
		$this->smarty->assign("TABLE",$_SESSION["TABLE"]);
		$this->smarty->assign("COLOR", $_SESSION["COLOR"]);
		$this->smarty->assign("FORM", $_SESSION["FORM"]);
		$this->smarty->assign("BANNER",$_SESSION["BANNER"]);
		$this->smarty->assign("LOGO",$_SESSION["LOGO"]);
		$this->smarty->assign("PARENTID",PARENTID);
		
		$this->smarty->assign("BALANCE",BALANCE);
		$this->smarty->assign("LOGIN",LOGIN);
		
		
		$this->smarty->assign("limit", $this->row);		
		$this->smarty->assign("page", $this->data["page"]);
		$this->smarty->assign("counter", ($this->data["page"]-1)*$this->row);

		$this->smarty->assign("pagename", $this->data["pagename"]);
		$this->smarty->assign("pagetitle", TITLE." : ".$this->data["pagetitle"]);
		$this->smarty->assign("panel", $this->data["basetpl"]);
		$this->smarty->assign("hostname", HTTP_HOST);
		$this->smarty->assign("module", $this->module);
		$this->smarty->assign("action", $this->action);
		$this->smarty->assign("usertype",$this->usertype);
		$this->smarty->assign("userid", $this->userid);
		$this->smarty->assign("session", $this->session);
		$this->smarty->assign("sess_data", $_SESSION[$this->session]);
		$this->smarty->assign("formaction", $this->formaction);
		//custom variable witch is define in file placed in directory config/smarty.config.php 
		$this->smarty->assign("gv_globel", unserialize(GV_VAR));
		
		$this->logger->debug("UserInterface View : ".$this->template.", SESSION : ".$this->session.", Module : ".$this->module.", Action : ".$this->action.", Usertype : ".$this->usertype);
		if(isset($this->data["basetpl"]))
		{
			$this->smarty->assign("basetpl", $this->data["basetpl"]);
			$this->smarty->display("view/".$this->module."/".$this->template);
		}
		else{
			//$this->smarty->display("view/".$this->template);
			$this->smarty->display("view/".DEFAULT_TEMP);
		}
		$this->logger->info("End of Calling execute function in UserInterface");
		
	}
}
?>
