<?php
include_once("lib/smarty.php");
//include_once("config/roles.config.php");

class Controller
{
	private $setting;
	private $management;
	private $data;
	private $logger;
	private $sess_data;
	private $view;
	private $reqdata;
	private $roles_config;
		
	public function __construct()
	{
		session_start();
		ob_start("ob_gzhandler");
		date_default_timezone_set("UTC");
		$this->logger = Logger::getRootLogger();
		$this->logger->debug("Controller Constructor function");
		$this->data["request"]=$_REQUEST;
	}
	
	public function roles($roles_config)
	{
		if($roles_config[$this->sess_data["usertype"]][$this->data["module"]][$this->data["action"]]==1)
			return true;
		else
			return false;
	}

	public function Module($module)
	{
		$this->management=$module;
	}

	public function ModuleSetting($key, $val)
	{
		$this->management->$key=$val;
	}
	
	public function __set($key, $val)
	{
		$this->logger->debug("Controller Setter function : $key $val.");		
		$this->data[strtolower($key)]=$val;
		if(strtolower($key)=="setting")
		{
			$this->setting=$val;
			return;
		}
		
		if(strtolower($key)=="roles")
		{
			$this->management->roles=$val;
			return;
		}
	}
	
	public function __get($key)
	{
		if(strtolower($key)=="usertype")
		{
			return $this->sess_data[$key];
		}
		if(strtolower($key)=="userid")
		{
			return $this->sess_data[$key];
		}
		if(strtolower($key)=="balance")
		{
			return $this->sess_data[$key];
		}
		if(strtolower($key)=="idadmin")
		{
			return $this->sess_data[$key];
		}
		if(strtolower($key)=="iddomain")
		{
			return $this->sess_data[$key];
		}
		if(strtolower($key)=="level")
		{
			return $this->sess_data[$key];
		}
		if(strtolower($key)=="idparent")
		{
			return $this->sess_data[$key];
		}
		if(strtolower($key)=="login")
		{
			return $this->sess_data[$key];
		}
		if(strtolower($key)=="view")
			return $this->view;
			
		$this->logger->debug("Controller Getter function : $key=>".$this->data[strtolower($key)]);
		
		if (isset($this->data[strtolower($key)]))
			return $this->data[strtolower($key)];
			
		if (isset($this->reqdata[strtolower($key)]))
			return $this->reqdata[strtolower($key)];
	}
	
	private function verifySession()
	{
		if(isset($this->data['session']))
		{
			if(strlen($this->data['session'])>0)
			{
				$this->logger->debug("Current Session : ".$this->data['session']);
			
				if(!verifysession($this->data['module'], $this->data['action'], $this->sess_data, $this->logger))
				{
					if($this->data['session']=="invalid_module" || $this->data['session']=="invalid_action" || $this->data['session']=="authentication_failed" || $this->data['session']=="invalid_session_access" || $this->data['session']=="user_logout")
					{
						$this->logger->debug("Current Module : ".$this->data['module'].", Action : ".$this->data['action']);
						return false; 
					}
				}
			}
		}			
	}
	
	private function SenitizeRequest()
	{
		if(isset($this->data["request"]["module"])){
			if(addslashes(trim($this->data["request"]["module"]))=="login")
				$mod="login";
			else
				$mod=decode(addslashes(trim($this->data["request"]["module"])),$this->logger, "module");

			if(strlen($mod)>0)
			{
				$this->data['module']=$mod;
			}
		}
		
		if(isset($this->data["request"]["action"])){
			if(addslashes(trim($this->data["request"]["action"]))=="login")
				$mod="login";
			else
				$mod=decode(addslashes(trim($this->data["request"]["action"])), $this->logger, "action");
				
			if(strlen($mod)>0)
			{
				$this->data['action']=$mod;
			}
		}
		
		if(isset($this->data["request"]["session"]))
		{
			$this->data['session']=addslashes(trim($this->data["request"]["session"]));
			
			if(is_array($_SESSION[$this->data['session']]))
			{
				foreach($_SESSION[$this->data['session']] as $key=>$val)
				{
					$this->sess_data[$key]=addslashes($val);
				}
			}
		}

		foreach($this->data["request"] as $key=>$val)
		{
			$key=strtolower($key);
			if($key=="module" || $key=="session" || $key=="action" || $key=="key" || $key=="type" || $key=="ch" || $key=="status" || $key=="error")
			{
					continue;
			}
			
			if($key=="phpsessid" || substr($key,0,-1)=="button" || $key=="submit")
				continue;
			$this->logger->debug("Controller managing request fields $key=>".$val);
			if(is_array($val))
			{
				foreach($val as $k=>$v)
					$this->reqdata[addslashes(trim($key))][$k]=addslashes(trim($v));
			}
			else
				$this->reqdata[addslashes(trim($key))]=addslashes(trim($val));
		}
			
		if(isset($this->data["request"]["key"]))
		{  
			$this->data["key"]=decode(addslashes(trim($this->data["request"]["key"])),$this->logger,"key");
		}

		if(isset($this->data["request"]["error"]))
		{  
			$this->data["error"]=decode(addslashes(trim($this->data["request"]["error"])),$this->logger,"error");
		}

		if(isset($this->data["request"]["type"]))
		{
			$this->data["type"]=decode(addslashes(trim($this->data["request"]["type"])),$this->logger,"type");
		}

		if(isset($this->data["request"]["ch"]))
		{
			$this->data["ch"]=decode(addslashes(trim($this->data["request"]["ch"])), $this->logger,"ch");
		}

		if(isset($this->data["request"]["status"]))
		{
			$this->data["status"]=decode(addslashes(trim($this->data["request"]["status"])), $this->logger,"status");
		}

		if(isset($this->data["request"]["page"]))
		{
			$this->data["page"]=decode(addslashes(trim($this->data["request"]["page"])), $this->logger, "page");
		}
		else
			$this->data["page"]=1;
	}

	public function PreLoad($roles_config)
	{
		$this->SenitizeRequest();

		if($this->roles($roles_config))
		{
			$this->verifySession();
		}
		else
		{
			$this->logger->debug("Current Module : ".$this->data['module'].", Action : ".$this->data['action']." is not allowed to user");
		}
	}

	public function LoadModule()
	{
		$this->management->view=$this->view=new Gswitch_Smarty("view", "config", "cache");
	}
	
	public function prepare()
	{
		$ret=true;
		$this->logger->debug("Prepare function executing in Controller.");
		
		if(!isset($this->data["session"]))
		{
			$this->logger->error("Controller Session is not defined properly.");
			$ret=false;
		}
				
		if(!isset($this->data["module"]))
		{
			$this->logger->error("Controller Module is not defined properly.");
			$ret=false;
		}
			
		if(!isset($this->data["action"]))
		{
			$this->logger->error("Controller Action is not defined properly.");
			$ret=false;
		}
		
		return $ret;
	}
	
	public function printsetting($setting)
	{
		// This function list all the functions, success, template of respected action for any module
		$str="";
		foreach($setting as $val)
			$str.="|".implode(",", $val);
			
		return $str;
	}
	
	public function execute()
	{
		$this->logger->debug("Executing function execute in Controller.");
		if(isset($this->data['session']))
			$this->management->session=$this->data['session'];
		if(isset($this->sess_data['usertype']))
			$this->management->usertype=$this->sess_data['usertype'];
		if(isset($this->sess_data['userid']))
			$this->management->userid=$this->sess_data['userid'];	
		if(isset($this->data['module']))
			$this->management->module=$this->data['module'];
		if(isset($this->data['action']))
			$this->management->action=$this->data['action'];
		if(count($this->sess_data)>0)
			$this->management->sessdata=$this->sess_data;
		if(isset($this->data['page']))
			$this->management->page=$this->data["page"];
		if(isset($this->data['type']))
			$this->management->type=$this->data["type"];
		if(isset($this->data['key']))
			$this->management->keyvalue=$this->data["key"];
		if(isset($this->data['type']))
			$this->management->error=$this->data["error"];
		if(isset($this->data['status']))
			$this->management->status=$this->data["status"];
		if(isset($this->data['ch']))
			$this->management->ch=$this->data["ch"];
		
		if(count($this->reqdata)>0)
			$this->management->reqdata=$this->reqdata;
			
		if(isset($this->setting[$this->data["action"]]["success"]))
			$this->management->success=$this->setting[$this->data["action"]]["success"];
		if(isset($this->setting[$this->data["action"]]["failure"]))
			$this->management->failure=$this->setting[$this->data["action"]]["failure"];
		if(isset($this->setting[$this->data["action"]]["action"]))
			$this->management->formaction=$this->setting[$this->data["action"]]["action"];
		if(isset($this->setting[$this->data["action"]]["pagetitle"]))
			$this->management->pagetitle=$this->setting[$this->data["action"]]["pagetitle"];
		
		$func=$this->setting[$this->data["action"]]["function"];
		if(empty($func)){
			$this->logger->debug("Execute function executing in Controller(".LOGINTYPE."). :".$this->printsetting($this->setting));
		}
		// condition for file check if exists in  module folder
		$assigned_tpl=$this->setting[$this->data["action"]]["template"];
		
		if(isset($this->setting[$this->data["action"]]["params"]))
			$params=$this->setting[$this->data["action"]]["params"];
		
		if(isset($this->setting[$this->data["action"]]["jquery"]))
			$jquery=$this->setting[$this->data["action"]]["jquery"];
		
		if(isset($params))
			$this->management->$func($assigned_tpl, $params);
		else
			$this->management->$func($assigned_tpl);
		$this->management->execute();
	}
}
?>