<?php
class DataLink extends DataAccess
{
	public function Detail($table, $field, $filter)
	{
		$this->dbobj->tablename=$table;
		foreach($field as $k=>$v)
		{
			$f="field".$k;
			$this->dbobj->$f=$v;
		}
		$this->dbobj->filter=$filter;
		$data=$this->dbobj->RowView();
		$this->dbobj->clearquery();
		return $data;
	}
	
	public function UpdateDetail($table, $field, $filter)
	{
		$this->dbobj->tablename=$table;
		foreach($field as $k=>$v)
			$this->dbobj->$k=$v;
		$this->dbobj->filter=$filter;
		$data=$this->dbobj->Update();
		$this->dbobj->clearnonquery();
		return $data;
	}
	
	public function Save($table, $field)
	{
		$this->dbobj->tablename=$table;
		foreach($field as $k=>$v)
			$this->dbobj->$k=$v;

		$data=$this->dbobj->Add();
		$this->dbobj->clearnonquery();
		return $data;
	}
	
	public function ListView($table, $field, $filter)
	{
		$this->dbobj->tablename=$table;
		foreach($field as $k=>$v)
		{
			$f="field".$k;
			$this->dbobj->$f=$v;
		}
		$this->dbobj->filter=$filter;
		$data=$this->dbobj->GridView();
		$this->dbobj->clearquery();
		return $data;
	}
	
	public function Remove($table, $filter)
	{
		$this->dbobj->tablename=$table;
		$this->dbobj->deleted=1;
		$this->dbobj->filter=$filter;
		$data=$this->dbobj->Update();
		$this->dbobj->clearnonquery();
		return $data;
	}
	

	public function DeleteVal($table, $filter)
	{
		$this->dbobj->tablename=$table;
		$this->dbobj->filter=$filter;
		$data=$this->dbobj->Delete();
		$this->dbobj->clearnonquery();
		return $data;
	}

	public function Options($table, $field, $filter, $key="")
	{
		$this->dbobj->tablename=$table;
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
}
?>