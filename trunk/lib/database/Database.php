<?php
abstract class Database
{
	protected $data;
	protected $link;

    public abstract function __set($key,$val);
    
    public abstract function __get($key);
    
    public abstract function Open();

    public abstract function Close();

    public abstract function Execute($sql);

	public abstract function Row($sql);

    public abstract function NonExecute($sql);
	
	public abstract function DataSet($sql);
	
	public abstract function status($values);
}
?>
