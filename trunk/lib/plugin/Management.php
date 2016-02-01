<?php
include_once("model/Add.php");
include_once("model/Update.php");
include_once("model/Listview.php");
include_once("model/Detail.php");
include_once("model/Deleted.php");
include_once("model/RawDeleted.php");
include_once("model/Remove.php");
include_once("model/Option.php");
include_once("model/RawListview.php");
include_once("model/Save.php");
include_once("view/Add.php");
include_once("view/Update.php");
include_once("view/Listview.php");
include_once("view/Detail.php");

class Management
{
	protected $model;
	protected $view;
	protected $dbtype;
	protected $data;
	protected $smarty;
	protected $template;
	protected $logger;
	protected $rawsave;
	protected $viewdata;
	private $savestatus;
	private $counter;
	private $roles;
		
	public function __construct($dbtype, $logger)
	{
		$this->logger = $logger;
		$this->logger->debug(__FILE__." : Management Constructor function");
		$this->dbtype=$dbtype;
		$this->savestatus=true;
		$this->counter=1;
		$this->viewdata=array(array());
		//$this->smarty=$smarty;
	}

	public function __set($key, $val)
	{
		$tmp=explode("_",$key);
		
		if(count($tmp)==1)
		{
			if(strtolower($key)=="view")
			{
				$this->smarty=$val;
				return;
			}
		
			//$this->logger->debug(__FILE__." : Management Setter function : $key $val.");
			$this->data[strtolower($key)]=$val;
		}
	
		if(strtolower($tmp[0])==="template")
		{
			if(count($val)>0)
				$this->template=$val;
		}
		
		if(strtolower($tmp[0])==="template")
			$this->template[strtolower($tmp[1])]=$val;
		
		if(strtolower($key)=="roles")
			$this->roles=$val;
	}
	
	public function __get($key)
	{
		$this->logger->debug(__FILE__." : Management Getter function : $key=>".$this->model->last);	
		if(strtolower($key)=="last")
			return $this->model->last;
		
		return;
	}
	
	public function Option($table, $field, $filter=array(), $key="")
	{
		$this->logger->debug(__FILE__." : Option function execute in Management.");
		$optmodel = new Model_Option($this->dbtype, $this->logger);
		if(strlen($table)>0)
			$optmodel->table=$table;
		else
			$this->logger->error(__FILE__." : Option function table is not defined in Management.");
			
		if(strlen($field)>0)
			$optmodel->_field=$field;
		else
			$this->logger->error(__FILE__." : Option function field is not defined in Management.");
		
		if(is_array($filter))
			$optmodel->filter=$filter;
		else
			$optmodel->filter=array();
			
		if(strlen($key)>0)
			$optmodel->_key=$key;
		else
			$optmodel->_key="";
		
		if($optmodel->prepare())
		{
			return $optmodel->execute();          
		}
		else
			return array();
	}
	
	public function AddNew($template="", $field=array())
	{
		$this->view = new View_Add($this->smarty, $this->logger);
		if(strlen($template) > 0) 
			$this->view->template=$template;
		else
			$this->view->template=$this->data["add"];
					
		$this->view->session=$this->data["session"];
		$this->view->module=$this->data["module"];
		$this->view->action=$this->data["action"];
		$this->view->basetpl=$this->data["basetpl"];
		$this->view->formaction=$this->data["formaction"];
	}

	public function execute()
	{
		if($this->view->prepare())
		{
			$this->view->execute($this->roles);
		}
	}
	
	public function response()
	{
		$this->RollBack();
		
		$this->logger->info(__FILE__." : Calling response function in Management.");
		if($this->savestatus)
			header("location:index.php?session=".$this->data['session']."&module=".encode($this->data['module'])."&action=".encode($this->data["success"]));
		else
		{
			if(isset($this->data["key"]))
				header("location:index.php?session=".$this->data['session']."&module=".encode($this->data['module'])."&action=".encode($this->data["failure"])."&key=".encode($this->data["key"]));
			else
				header("location:index.php?session=".$this->data['session']."&module=".encode($this->data['module'])."&action=".encode($this->data["failure"]));
		}
		exit;
	}
	
	public function RawSave($table, $data, $key)
	{
		if(!$this->savestatus)
			return false;
			
		$this->logger->debug(__FILE__." : RawSave function execute in Management.");
		$rawsave = new Model_Save($this->dbtype, $this->logger);
		
		$rawsave->table=$table;
		$rawsave->data=$data;

		if($rawsave->prepare())
		{
			$status=$rawsave->execute();
			/* $this->rawsave["data".$this->counter]["last"]=$rawsave->last;
			$this->rawsave["data".$this->counter]["table"]=$table;
			$this->rawsave["data".$this->counter]["status"]=$rawsave->status;
			$this->rawsave["data".$this->counter]["keyfield"]=$key;
			$this->savestatus=$rawsave->status; */
			
			$this->rawsave["data".$this->counter]["last"]=$this->model->last;
			$this->rawsave["data".$this->counter]["table"]=$table;
			$this->rawsave["data".$this->counter]["status"]=$rawsave->status;
			$this->rawsave["data".$this->counter]["keyfield"]=$key;
			$this->savestatus=$rawsave->status;
			$this->counter++;
		}
		return $status;
	}
	
	// update for multiple table
	public function RawUpdate($table, $data, $key, $tble_column)
	{
		$this->logger->debug("RawUpdate function execute in Management.");
		$this->model = new Model_Update($this->dbtype, $this->logger);
		$this->model->table=$table;
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->key=$key;
		$this->view->formaction=$this->data["formaction"];
		$this->model->data=$data;

		$this->logger->debug("RawUpdate function execute in Management with ".$tble_column." and value ".$key);
		$a=explode(',',$tble_column);
		$b=explode(',',$key);
		$tmp="";
		if(count($a)==count($b)){
			$tmp=array_combine($a, $b);
		}else{
		$this->logger->error("RawUpdate function execute in management with ".$tble_column." and value ".$key);
		}
		$this->model->filter=$tmp;	
		if($this->model->prepare())
		{
			$this->model->execute();
			$this->savestatus=$this->model->status;
		}
		$this->logger->debug("End of RawUpdate function execute in Management.");
	}
	
	private function RollBack()
	{
		if($this->counter==1)
			return;
			
		for($i=1; $i<=($this->counter-1); $i++)
		{
			if(!$this->rawsave["data".$i]["status"])
				break;
		}
				
		$this->logger->debug("Current Counter :".$this->counter.", i=$i");
		
		if($i==$this->counter)
		{
			$this->savestatus=true;
			return;
		}
		else
			$this->savestatus=false;
			
		foreach($this->rawsave as $val)
		{
			if($val["status"])
			{
				$rollback = new Model_RawDeleted($this->dbtype, $this->logger);
				$rollback->table=$val["table"];
				$rollback->filter=array($val["keyfield"]=>$val["last"]);
				
				if($rollback->prepare())
					$rollback->execute();
			}
		}
	}
	
	public function Save($data=array())
	{
		if(!$this->savestatus)
			return false;
			
		$this->logger->debug("Save function execute in Management.");
		$this->model = new Model_Add($this->dbtype, $this->logger);
		$this->model->table=$this->data["table"];
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];

		if(count($data)>0)
			$this->model->data=$data;
		else
			$this->model->data=$this->data["reqdata"];

		if($this->model->prepare())
		{
			$status=$this->model->execute();
			if(count($data)>0)
			{
				$this->rawsave["data".$this->counter]["last"]=$this->model->last;
				$this->rawsave["data".$this->counter]["table"]=$this->data["table"];
				$this->rawsave["data".$this->counter]["status"]=$this->model->status;
				$this->rawsave["data".$this->counter]["keyfield"]=$this->data["key"];
				$this->counter++;
			}
			$this->savestatus=$this->model->status;
		}
		
		if(count($data)==0)
			$this->response();
		return $status;
	}
	
	public function UnBlock()
	{
		$this->logger->debug("Unblock function execute in Management.");
		$this->model = new Model_Status($this->dbtype, $this->logger);
		$this->model->table=$this->data["table"];
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->fieldstatus=$this->data["_field"];
		$this->model->valuestatus=$this->data["_value"];

		if($this->model->prepare())
		{
			$this->model->execute();
		}
	}

	public function Block()
	{
		$this->logger->debug("Block function execute in Management.");
		$this->model = new Model_Status($this->dbtype, $this->logger);
		$this->model->table=$this->data["table"];
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->fieldstatus=$this->data["_field"];
		$this->model->valuestatus=$this->data["_value"];

		if($this->model->prepare())
		{
			$status=$this->model->execute();
		}
		return $status;
	}
	
	public function RawDeleted($table, $key, $value)
	{
		$this->logger->debug("RawDeleted function execute in Management.");
		$this->model = new Model_Deleted($this->dbtype, $this->logger);
		$this->model->table=$table;
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		
		$this->logger->debug("RawDeleted function execute in Management with ".$value." and value ".$key);
		$tmp=array($value=>$key);
		$this->model->filter=$tmp;
		
		if($this->model->prepare())
		{
			$this->model->execute();
			$this->savestatus=$this->model->status;
		}
	}

	public function Deleted()
	{
		$this->logger->debug("Deleted function execute in Management.");
		$this->model = new Model_Deleted($this->dbtype, $this->logger);
		$this->model->table=$this->data["table"];
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		
		$this->logger->debug("Deleted function execute in Management with ".$this->data["key"]." and value ".$this->data["keyvalue"]);
		$tmp=array($this->data["key"]=>$this->data["keyvalue"]);
		if(count($filter)>0)
		{
			$temp=array_merge($filter, $tmp);
			$this->model->filter=$temp;
		}
		else
		{
			if(count($this->data["filter"])>0)
			{
				$temp=array_merge($filter, $tmp);
				$this->model->filter=$temp;
			}
			else 
				$this->model->filter=$tmp;
		}

		if($this->model->prepare())
		{
			$this->model->execute();
			$this->savestatus=$this->model->status;
		}
		$this->response();
	}

	public function Remove()
	{
		$this->logger->debug("Remove function execute in Management.");
		$this->model = new Model_Remove($this->dbtype, $this->logger);
		$this->model->table=$this->data["table"];
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		
		$this->logger->debug("Remove function execute in Management with ".$this->data["key"]." and value ".$this->data["keyvalue"]);
		$tmp=array($this->data["key"]=>$this->data["keyvalue"]);
		if(count($filter)>0)
		{
			$temp=array_merge($filter, $tmp);
			$this->model->filter=$temp;
		}
		else
		{
			if(count($this->data["filter"])>0)
			{
				$temp=array_merge($this->data["filter"], $tmp);
				$this->model->filter=$temp;
			}
			else 
				$this->model->filter=$tmp;		
		}

		if($this->model->prepare())
		{
			$this->model->execute();
			$this->savestatus=$this->model->status;
		}
		$this->response();
	}
	
	public function Edit($template="", $field=array(), $filter=array())
	{
		$this->logger->info("Edit function execute in Management with table".$this->data["table"]);
		$this->model = new Model_Detail($this->dbtype, $this->logger);
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->table=$this->data["table"];
		
		if(count($field)>0)
			$this->model->field=$field;
		else
			$this->model->field=$this->data["datafield"];
		$this->logger->debug("Edit function execute in Management with ".$this->data["key"]." and value ".$this->data["keyvalue"]);
		$tmp=array($this->data["key"]=>$this->data["keyvalue"]);
		
		if(count($filter)>0)
		{
			$temp=array_merge($filter, $tmp);
			$this->model->filter=$temp;
		}
		else
		{
			if(count($this->data["filter"])>0)
			{
				$temp=array_merge($this->data["filter"], $tmp);
				$this->model->filter=$temp;
			}
			else 
				$this->model->filter=$tmp;
		}

		if($this->model->prepare())
		{
			$edit_data=$this->model->execute();
		}
		
		if($this->model->status)
		{
			$this->view = new View_Update($this->smarty, $this->logger);
			
			if(strlen($template) > 0) 
				$this->view->template=$template;
			else
				$this->view->template=$this->data["edit"];
				
			$this->view->data=$edit_data;
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->basetpl=$this->data["basetpl"];
			$this->view->key=$this->data["keyvalue"];
			$this->view->formaction=$this->data["formaction"];
		}
	}
	
	public function RawEdit($template="", $result)
	{
			$this->logger->debug("RawEdit function execute in Management.");
			$this->view = new View_Update($this->smarty, $this->logger);
			
			if(strlen($template) > 0) 
				$this->view->template=$template;
			else
				$this->view->template=$this->data["edit"];
				
			$this->view->data=$result;
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->basetpl=$this->data["basetpl"];
			$this->view->key=$this->data["keyvalue"];
			$this->view->formaction=$this->data["formaction"];
			$this->logger->debug("End RawEdit function execute in Management.");
		
	}
	
   public function Update($data=array(), $filter=array())
	{
		$this->logger->debug("Update function execute in Management.");
		$this->model = new Model_Update($this->dbtype, $this->logger);
		$this->model->table=$this->data["table"];
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->key=$this->data["keyvalue"];
		$this->view->formaction=$this->data["formaction"];
		if(count($data)>0)
			$this->model->data=$field;
		else
			$this->model->data=$this->data["reqdata"];

		$this->logger->debug("Update function execute in Management with ".$this->data["key"]." and value ".$this->data["keyvalue"]);
		$tmp=array($this->data["key"]=>$this->data["keyvalue"]);		
		
		if(count($filter)>0)
		{
			$temp=array_merge($filter, $tmp);
			$this->model->filter=$temp;
		}
		else
		{
			if(count($this->data["filter"])>0)
			{
				$temp=array_merge($this->data["filter"], $tmp);
				$this->model->filter=$temp;
			}
			else 
				$this->model->filter=$tmp;	
		}

		if($this->model->prepare())
		{
			$this->model->execute();
			$this->savestatus=$this->model->status;
		}
		
		if(count($data)==0)
			$this->response();
	}
	
	public function Detail($template="", $field=array(), $filter=array())
	{
		$this->logger->info("Detail function execute in Management with table".$this->data["table"]);
		$this->model = new Model_Detail($this->dbtype, $this->logger);
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->table=$this->data["table"];
		$this->model->key=$this->data["keyvalue"];
		
		if(count($field)>0)
			$this->model->field=$field;
		else
			$this->model->field=$this->data["datafield"];
		
		$this->logger->debug("Detail function execute in Management with ".$this->data["key"]." and value ".$this->data["keyvalue"]);
		$tmp=array($this->data["key"]=>$this->data["keyvalue"]);		
		
		if(count($filter)>0)
		{
			$temp=array_merge($filter, $tmp);
			$this->model->filter=$temp;
		}
		else
		{
			if(count($this->data["filter"])>0)
			{
				$temp=array_merge($this->data["filter"], $tmp);
				$this->model->filter=$temp;
			}
			else 
				$this->model->filter=$tmp;	
		}

		if($this->model->prepare())
		{
			$det_data=$this->model->execute();
		}

		if($this->model->status)
		{
			$this->view = new View_Detail($this->smarty, $this->logger);
			$this->view->key=$this->data["key"];
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->basetpl=$this->data["basetpl"];
			
			if(strlen($template) > 0) 
				$this->view->template=$template;

			else
				$this->view->template=$this->data["detail"];
				
			$this->view->data=$det_data;
		}
	}
	
	public function ListView($template="", $field=array(), $filter=array())
	{
		$this->logger->info("ListView function execute in Management with table ".$this->data["table"]);
		$this->model = new Model_ListView($this->dbtype, $this->logger);
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->table=$this->data["table"];
		if(count($field)>0)
		{ 	
			$this->logger->debug("ListView function execute in Management with field argument.");
			$this->model->field=$field;
		}
		else
		{
			$this->logger->debug("ListView function execute in Management with set field.");
			$this->model->field=$this->data["datafield"];
		}
		
		if(count($filter)>0)
		{
				$this->logger->debug("ListView function execute in Management with filter argument.");	
				$this->model->filter=$filter;
		}		
		else
		{	
			$this->logger->debug("ListView function execute in Management with set filter.");
			if(count($this->data["filter"])>0)
				$this->model->filter=$this->data["filter"];
			else 
				$this->model->filter=array();
		}
	
		if($this->model->prepare())
		{
			$list_data=$this->model->execute();
			$this->logger->debug("ListView function execute in Management successful prepare operation. Current status".$this->model->status.", data : ".$this->model->data);
		}
		else 
		{
			$this->logger->debug("ListView function execute in Management failed prepare operation.");
		}

		if($this->model->status)
		{
			$this->logger->debug("ListView function execute in Management with Template ". (strlen($template) >0?$template:
			$this->template["list"]));
			$this->view = new View_ListView($this->smarty, $this->logger);
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->page=$this->data["page"];

			if(strlen($template) > 0) 
				$this->view->template=$template;

			else
				$this->view->template=$this->data["list"];

			$this->view->header=$this->data["header"];
			$this->view->field=$this->data["field"];
			$this->view->data=$this->model->data;
			$this->view->row=$this->model->rows;
			$this->view->key=$this->data["key"];
			$this->view->basetpl=$this->data["basetpl"];
		}	 	
		else 
		{
			$this->logger->debug("ListView function execute in Management operation status unsuccessful.");
			$this->view = new View_ListView($this->smarty, $this->logger);
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->page=$this->data["page"];

			if(strlen($template) > 0) 
				$this->view->template=$template;

			else
				$this->view->template=$this->data["list"];

			$this->view->header=$this->data["header"];
			$this->view->field=$this->data["field"];
			$this->view->data=array(array());
			$this->view->row=0;
			$this->view->key=$this->data["key"];
			$this->view->basetpl=$this->data["basetpl"];
		}
	}
	
	public function RawListView($template="")
	{
		$this->logger->info("RawListView function execute in Management with table ".$this->data["table"]);
		$this->model = new Model_RawListView($this->dbtype, $this->logger);
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->sql=$this->data["sql"];
	
		if($this->model->prepare())
		{
			$list_data=$this->model->execute();
			$this->logger->debug("RawListView function execute in Management successful prepare operation. Current status".$this->model->status.", data : ".$this->model->data);
		}
		else 
		{
			$this->logger->debug("RawListView function execute in Management failed prepare operation.");
		}

		if($this->model->status)
		{
			$this->logger->debug("RawListView function execute in Management with Template ". (strlen($template) >0?$template:
			$this->template["list"]));
			$this->view = new View_ListView($this->smarty, $this->logger);
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->page=$this->data["page"];

			if(strlen($template) > 0) 
				$this->view->template=$template;

			else
				$this->view->template=$this->data["list"];

			$this->view->header=$this->data["header"];
			$this->view->field=$this->data["field"];
			$this->view->data=$this->model->data;
			$this->view->row=$this->model->rows;
			$this->view->key=$this->data["key"];
			$this->view->basetpl=$this->data["basetpl"];
		}	 	
		else 
		{
			$this->logger->debug("RawListView function execute in Management operation status unsuccessful.");
			$this->view = new View_ListView($this->smarty, $this->logger);
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->page=$this->data["page"];

			if(strlen($template) > 0) 
				$this->view->template=$template;

			else
				$this->view->template=$this->data["list"];

			$this->view->header=$this->data["header"];
			$this->view->field=$this->data["field"];
			$this->view->data=array(array());
			$this->view->row=0;
			$this->view->key=$this->data["key"];
			$this->view->basetpl=$this->data["basetpl"];
		}
	}
	
	public function RawListViewNew($template="", $status=true)
	{
		$this->logger->info("RawListView function execute in Management with table ".$this->data["table"]);
		$this->model = new Model_RawListView($this->dbtype, $this->logger);
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->sql=$this->data["sql"];
	
		if($this->model->prepare() && $status)
		{
			$list_data=$this->model->execute();
			$this->logger->debug("RawListView function execute in Management successful prepare operation. Current status".$this->model->status.", data : ".$this->model->data);
		}
		else 
		{
			$this->logger->debug("RawListView function execute in Management failed prepare operation.");
		}

		if($this->model->status && $status)
		{
			$this->logger->debug("RawListView function execute in Management with Template ". (strlen($template) >0?$template:
			$this->template["list"]));
			$this->view = new View_ListView($this->smarty, $this->logger);
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->page=$this->data["page"];

			if(strlen($template) > 0) 
				$this->view->template=$template;

			else
				$this->view->template=$this->data["list"];

			$this->view->header=$this->data["header"];
			$this->view->field=$this->data["field"];
			$this->view->data=$this->model->data;
			$this->view->row=$this->model->rows;
			$this->view->key=$this->data["key"];
			$this->view->basetpl=$this->data["basetpl"];
		}	 	
		else 
		{
			$this->logger->debug("RawListView function execute in Management operation status unsuccessful.");
			$this->view = new View_ListView($this->smarty, $this->logger);
			$this->view->session=$this->data["session"];
			$this->view->module=$this->data["module"];
			$this->view->action=$this->data["action"];
			$this->view->page=$this->data["page"];

			if(strlen($template) > 0) 
				$this->view->template=$template;

			else
				$this->view->template=$this->data["list"];
			$this->view->header=$this->data["header"];
			$this->view->field=$this->data["field"];
			$this->view->data=$this->viewdata;
			$this->view->row=count($this->viewdata);
			$this->view->key=$this->data["key"];
			$this->view->basetpl=$this->data["basetpl"];
		}
	}
	
	public function custom()
	{
		$this->logger->debug("custom function execute in Management.php.");
		$this->model = new Model_RawListView($this->dbtype, $this->logger);
		$this->model->session=$this->data["session"];
		$this->model->module=$this->data["module"];
		$this->model->action=$this->data["action"];
		$this->model->sql=$this->data["sql"];
	
		if($this->model->prepare())
		{
			$list_data=$this->model->execute();
			$this->logger->debug("custom function execute in Management successful prepare operation. Current status".$this->model->status.", data : ".$this->model->data);
		}
		else 
		{
			$this->logger->debug("custom function execute in Management failed prepare operation.");
		}
		return $this->model->data;
	}
	
	public function CustomView($template="", $data)
	{
		$this->logger->debug("CustomTemplate function execute in Management with Template ". (strlen($template) >0?$template:$this->template["list"]));
		$this->view = new View_ListView($this->smarty, $this->logger);
		$this->view->session=$this->data["session"];
		$this->view->module=$this->data["module"];
		$this->view->action=$this->data["action"];
		$this->view->page=$this->data["page"];

		if(strlen($template) > 0) 
			$this->view->template=$template;

		else
			$this->view->template=$this->data["list"];
		
		$this->view->header=$this->data["header"];
		$this->view->field=$this->data["field"];
		$this->view->data=$data;
		$this->view->row=$this->model->rows;
		$this->view->key=$this->data["key"];
		$this->view->basetpl=$this->data["basetpl"];
	}

}
?>
