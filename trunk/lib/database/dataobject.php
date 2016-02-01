<?php
final class DataAcess
{
	protected $database;
	protected $data;
	protected $query;
	protected $nonquery;
	protected $tablename;
	protected $filter;
	protected $sql;
	protected $logger;
	
	public function __construct($type)
	{
		$this->logger = logger::getRootlogger();
		if(strtolower($type)=="mysqli")
			$this->database= new Mysqli();
		else if(strtolower($type)=="mysql")
			$this->database= new MysqlManager();
		else
		{
			$this->database=null;
			return false;
		}

		$this->query = new Query($this->database);
		$this->nonquery = new NonQuery($this->database);

		return true;
	}

    public function __set($key, $val)
	{
		if($key=="filter")
		{
			$this->filter=$val;
			return;
		}
		if($key=="sql")
		{
			$this->sql=$val;
			return;
		}
	
		if($key=="table")
		{
			$this->tablename=$val;
			return;
		}
		if(strlen($key)>0)
			$this->data[strtolower($key)]=$val;
		else
			echo "Invalid variable is setting";
	}
    
	public function getrow()
	{
		$this->logger->debug("Data Rows : ".$this->query->datarows);
		return $this->query->datarows;
	}

	public function getlastid()
	{
		return $this->nonquery->last;
	}

	public function clearnonquery()
	{
		$this->logger->error("Clearing all the current nonquery variables");
		$this->table="";
		$this->sql="";
		$this->filter=array();
		$this->data=array();
		//unset($this->nonquery);
		//$this->nonquery = new NonQuery($this->database);
	}

	public function clearquery()
	{
		$this->logger->error("Clearing all the current query variables");
		$this->table="";
		$this->sql="";
		$this->filter=array();
		$this->data=array();
		//unset($this->query);
		//$this->query = new Query($this->database);
	}

	public function runquery()
	{
		$this->query->raw_prepare($this->sql);
		return $this->query->DataSet();
	}

    public function Add()
	{
		$this->nonquery->_table=$this->tablename;
		$this->nonquery->_type="insert";
		foreach($this->data as $key=>$val)
		{
			$this->nonquery->$key=$val;
		}

		$this->nonquery->prepare();
		$status=$this->nonquery->Execute();

		return $status;
	}

    public function Update()
	{
		$this->nonquery->_table=$this->tablename;
		$this->nonquery->_type="update";
		foreach($this->data as $key=>$val)
		{
			$this->nonquery->$key=$val;
		}
		$this->nonquery->filter=$this->filter;
		$this->nonquery->prepare();
		return $this->nonquery->Execute();
	}

    public function Delete()
	{
		$this->nonquery->_table=$this->tablename;
		$this->nonquery->_type="delete";
		foreach($this->data as $key=>$val)
		{
			$this->nonquery->$key=$val;
		}
		$this->nonquery->filter=$this->filter;
		$this->nonquery->prepare();
		return $this->nonquery->Execute();
	}

	public function RowView()
	{
		$this->query->table=$this->tablename;
		$this->query->type="select";
		$i=0;
		foreach($this->data as $key=>$val)
		{
			$tmp="field".$i;
			$this->query->$tmp=$val;
			$i++;
		}
		$this->query->filter=$this->filter;
		$this->query->prepare();
		
		return $this->query->Row();
	}

    public function DataView()
	{
		$this->query->table=$this->tablename;
		$this->query->type="select";
		$i=0;
		foreach($this->data as $key=>$val)
		{
			$tmp="field".$i;
			$this->query->$tmp=$val;
			$i++;
		}
		$this->query->filter=$this->filter;
		$this->query->prepare();
		return $this->query->Execute();
	}

	public function GridView()
	{
		$this->query->table=$this->tablename;
		$this->query->type="select";
		foreach($this->data as $key=>$val)
		{
			$this->query->$key=$val;
		}
		$this->query->filter=$this->filter;

		$this->query->prepare();
		return $this->query->DataSet();
	}

	public function JoinDataView($table, $field, $join, $filter)
	{
		$tbla="";
		foreach($this->data as $val)
		{
			$tbla.="`tbla`.`".$val."`, ";
		}
		$tbla=substr($tbla,0,-2);
		$tbla="";
		foreach($field as $val)
		{
			$tblb.="`tblb`.`".$val."`, ";
		}
		$tblb=substr($tblb,0,-2);
		$filtera="";
		foreach($this->filter as $key=>$val){
			$filtera.="`tblb`.`".$key."`='".$val."' AND ";
		}
		$filtera=substr($filtera,0,-5);
		$filterb="";
		foreach($filter as $key=>$val){
			$filterb.="`tblb`.`".$key."`='".$val."' AND ";
		}
		$filtera=substr($filtera,0,-5);
		
		$this->query->raw_prepare("SELECT ".$tbla.", ".$tblb." FROM `".$this->tablename."` tbla LEFT JOIN `".$table."` tblb ON `tbla`.`".$join[0]."`=`tblb`.`".$join[1]."` WHERE ".$filtera." AND ".$filterb);
		return $this->query->Execute();
	}

	public function RawGridView($sql)
	{
		$this->query->raw_prepare($sql);
		return $this->query->DataSet();
	}
	
/*	public function Detail($table, $field, $filter)
	{
		$this->tablename=$table;
		foreach($field as $k=>$v)
		{
			$f="field".$k;
			$this->$f=$v;
		}
		$this->filter=$filter;
		$data=$this->RowView();
		$this->clearquery();
		return $data;
	}
	public function UpdateDetail($table, $field, $filter)
	{
		$this->tablename=$table;
		foreach($field as $k=>$v)
			$this->$k=$v;
		$this->filter=$filter;
		$data=$this->Update();
		$this->clearnonquery();
		return $data;
	}
	public function Save($table, $field)
	{
		$this->tablename=$table;
		foreach($field as $k=>$v)
			$this->$k=$v;

		$data=$this->Add();
		$this->clearnonquery();
		return $data;
	}
	public function ListView($table, $field, $filter)
	{
		$this->tablename=$table;
		foreach($field as $k=>$v)
		{
			$f="field".$k;
			$this->$f=$v;
		}
		$this->filter=$filter;
		$data=$this->GridView();
		$this->clearquery();
		return $data;
	}
	public function PermissionView($table, $field, $filter)
	{
		$this->tablename=$table;
		foreach($field as $k=>$v)
			$this->$k=$v;
		$this->filter=$filter;
		$data=$this->GridView();
		$this->clearquery();
		return $data;
	}
	
	public function Remove($table, $filter)
	{
		$this->tablename=$table;
		$this->deleted=1;
		$this->filter=$filter;
		$data=$this->Update();
		$this->clearnonquery();
		return $data;
	}
	
	public function MakeDate($date, $type)
	{
		$start=explode("/", $date);
		if($type == "start")
			return strtotime(trim($start[2])."-".trim($start[0])."-".trim($start[1])." 00:00:00");
		else
			return strtotime(trim($start[2])."-".trim($start[0])."-".trim($start[1])." 23:59:59");
	}

	public function DeleteVal($table, $filter)
	{
		$this->tablename=$table;
		$this->filter=$filter;
		$data=$this->Delete();
		$this->clearnonquery();
		return $data;
	}

	public function Options($table, $field, $filter, $key="")
	{
		$this->tablename=$table;
		if(strlen($key)==0){
			$this->field1="id";
		}
		else
			$this->field1=$key;
		$this->field2=$field;
		if($filter!="")
			$this->filter=$filter;
		$data=$this->GridView();
		if(strlen($key)==0){
			foreach($data as $val)
				$value[$val["id"]]=$val[$field];
		}
		else
		{
			foreach($data as $val)
				$value[$val[$key]]=$val[$field];
		}
		$data=$value;
		$this->clearquery();
		return $data;
	}

	public function SQL($sql)
	{
		$this->sql=$sql;
		
		$data=$this->runquery();
		$this->clearquery();
		return $data;
	}
	
	public function login($username, $password)
	{
		$this->tablename="users";
		$this->field1="id";
		$this->field2="company";
		$this->field3="contact";
		$this->field4="minutes";
		
		$this->filter=array("username"=>$username,"password"=>$password);
		$values=$this->RowView();
		if(count($values)>0)
		{
				$session=md5(time().$username.$password."GVENTUREWORKSFORYOU");
				session_register($session);
				$field=array_merge($values, array("userid"=>$values["id"]));
 				$_SESSION[$session]=$field;
				return HTTP_HOST."index.php?session=".$session."&module=".encode("dialer")."&action=".encode("lead");
		}
		else
		{
				return HTTP_HOST."index.php?session=authentication_failed&module=".encode("website")."&action=".encode("login");
		}
		return HTTP_HOST."index.php";
	} */
}
?>
