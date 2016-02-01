<?php
require_once("model/ModelAccess.php");

class ModelLink extends ModelAccess
{
	public function Detail($table="", $field=array(), $filter=array())
	{
		if(strlen($table)>0)
			$this->dbobj->table=$table;
			
		if(isset($this->dbobj->table))
		{
			if(count($field)>0)
			{
				foreach($field as $k=>$v)
				{
					$f="field".$k;
					$this->dbobj->$f=$v;
				}
			}
			else
				return array();
				
			$this->dbobj->filter=$filter;
			$data=$this->dbobj->RowView();
			$this->data["row"]=$this->dbobj->rows;
			$this->dbobj->clearquery();
			return $data;
		}
		else
			return array();
	}
	
	public function Update($table="", $field=array(), $filter=array())
	{
		if(strlen($table)>0)
			$this->dbobj->table=$table;
		
		if(isset($this->dbobj->table))
		{
			if(count($field)>0)
			{
				foreach($field as $k=>$v)
					$this->dbobj->$k=$v;
			}
			else
				return false;
			
			if(count($filter)>0)
				$this->dbobj->filter=$filter;
			else
				return false;
				
			$data=$this->dbobj->Update();
			$this->dbobj->clearnonquery();
			return $data;
		}
		else
			return false;
	}
	
	public function Save($table="", $field=array())
	{
		if(strlen($table)>0)
			$this->dbobj->table=$table;
			
		if(isset($this->dbobj->table))
		{
			if(count($field)>0)
			{
				foreach($field as $k=>$v)
					$this->dbobj->$k=$v;
			}
			else
				return false;

			$data=$this->dbobj->Add();
			$this->last=$this->dbobj->getlastid();
			$this->dbobj->clearnonquery();
			return $data;
		}
		else
			return false;
	}

	
	public function ListView($table="", $field=array(), $filter=array())
	{
		if(strlen($table)>0)
			$this->dbobj->table=$table;
			
		if(isset($this->dbobj->table))
		{
			if(count($field)>0)
			{
				foreach($field as $k=>$v)
				{
					$f="field".$k;
					$this->dbobj->$f=$v;
				}
			}
			else
				return array();
				
			$this->dbobj->filter=$filter;
			$data=$this->dbobj->GridView();
			$this->data["row"]=$this->dbobj->rows;
			$this->dbobj->clearquery();
			return $data;
		}
		else
			return array();
	}
	
	public function Remove($table="", $filter=array())
	{
		if(strlen($table)>0)
			$this->dbobj->table=$table;
			
		if(isset($this->dbobj->table))
		{
			$this->dbobj->deleted=1;
			$this->dbobj->filter=$filter;
			$data=$this->dbobj->Update();
			$this->dbobj->clearnonquery();
			return $data;
		}
		else
			return false; 
	}

	public function Status($table="", $field, $filter=array())
	{
		if(strlen($table)>0)
			$this->dbobj->table=$table;
			
		if(isset($this->dbobj->table))
		{
			$key=array_keys($field);
			$this->dbobj->$key[0]=$field[$key[0]];
			$this->dbobj->filter=$filter;
			$data=$this->dbobj->Update();
			$this->dbobj->clearnonquery();
			return $data;
		}
		else
			return false; 
	}
	

	public function DeleteVal($table="", $filter=array())
	{
		if(strlen($table)>0)
			$this->dbobj->table=$table;
			
		if(isset($this->dbobj->table))
		{
			$this->dbobj->filter=$filter;
			$data=$this->dbobj->Delete();
			$this->dbobj->clearnonquery();
			return $data;
		}
		else
			return false;
	}

	public function Options($table="", $field=array(), $filter=array(), $key="")
	{
		if(strlen($table)>0)
			$this->dbobj->table=$table;
		
		if(isset($this->dbobj->table))
		{
			if(strlen($key)==0){
				$this->dbobj->field1="id";
			}
			else
				$this->dbobj->field1=$key;
			$this->dbobj->field2=$field;
			if($filter!="")
				$this->dbobj->filter=$filter;
			$data=$this->dbobj->GridView();
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
			$this->dbobj->clearquery();
			return $data;
		}
		else
			return array();
	}
	
	public function RawGridView($sql)
	{
		$data=$this->dbobj->RawGridView($sql);
		$this->data["row"]=$this->dbobj->rows;
		return $data;
	}
}
?>
