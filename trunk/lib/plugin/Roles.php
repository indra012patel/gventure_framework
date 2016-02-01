<?php
final class Roles
{
	private $data;
	private $roles;
	
	public function __construct($roles, $logger)
	{
		$this->logger = $logger;
		$this->logger->info(__FILE__." : Roles Constructor function");
		$roles=array("admin", "reseller", "user");

		if(defined(SESSION_KEY))
			$query->data["key"]=SESSION_KEY;
		else 
			$this->defaultPage();
	}
	
	public function defaultPage()
	{
		$this->logger->info(__FILE__." : Roles defaulPage function");
		if(defined('DEFAULT_PAGE'))
			header("location:".HTTP_HOST.DEFAULT_PAGE);
		else
			header("location:".HTTP_HOST."login.html");
	}
	
	public function __set($key, $val)
	{
		$this->logger->info(__FILE__." : Roles Setter function $key=>$val");
		$this->data[strtolower($key)]=$val;
	}
	
	public function __get($key)
	{
		$this->logger->info(__FILE__." : Roles Getter function $key=>".$this->data[strtolower($key)]);
		return $this->data[strtolower($key)];
	}
}
?>
