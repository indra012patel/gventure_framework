<?php 
class  Login 
{
	protected $table;
	protected $data;
	protected $logger;
	protected $success;
	protected $failure;
	private $db;
	private $query;
		
	public function __construct($logger)
	{
		$db = new MysqlManager();
		$this->query = new Query($db);
		$this->query->type="select";

		$this->logger = $logger;
		$this->logger->info(__FILE__." : Login Constructor function");

		if(defined(SESSION_KEY))
			$query->data["key"]=SESSION_KEY;
		else 
			$this->defaultPage();
	}

	public function defaultPage()
	{
		$this->logger->info(__FILE__." : Login defaulPage function");
		if(defined('DEFAULT_PAGE'))
			header("location:".HTTP_HOST.DEFAULT_PAGE);
		else
			header("location:".HTTP_HOST."login.html");
	}
	
	public function __set($key, $val)
	{
		$this->logger->info(__FILE__." : Login Setter function $key=>$val");
		if(strtolower($key)=="table")	
			$this->table=$val;
		else if(strtolower($key)=="success")	
			$this->success=$val;
		else if(strtolower($key)=="failure")	
			$this->failure=$val;
		else if(strtolower($key)=="field")	
			$this->data["field"]=$val;
		else if(strtolower($key)=="filter")	
			$this->data["filter"]=$val;
		else if(strtolower($key)=="userfield")	
			$this->data["userfield"]=$val;
		else if(strtolower($key)=="passfield")	
			$this->data["passfield"]=$val;
		else
			return;
	}

	public function prepare()
	{
		$ret=false;
		
		if(strlen($this->table)>0)
		{
			$this->query->table=$table;
			$ret=true;
		}
		
		foreach ($field as $val){
			$temp= "field".$i;
			$this->query->$temp=$val;
			$i++;
		}
		
		$this->query->filter=$this->data["filter"];
		$this->query->prepare();
		
		return $ret;
	}
	
	public function execute()
	{
		$values=$this->query->Execute();
		logger_print_array($logger, $values);
		
		if(count($values)>0)
		{
			$str="Admin autentication return data";
			$values=$values[0];
			foreach($values as $key=>$val)
			{
				$str.=$key."=>".$val;
			}
			$logger->debug($str);
			$session=md5(time().$this->data["filter"][$this->data["usrfield"]].$this->data["filter"][$this->data["passfield"]].GV_PASS_ENCODER_KEY);
			session_register($session);
				
 			$_SESSION[$session]=$values;
			$_SERVER["PHP_AUTH_USER"]=$this->data["filter"][$this->data["usrfield"]];
			$_SERVER["PHP_AUTH_PW"]=$this->data["filter"][$this->data["passfield"]];
			
			header("location:".HTTP_HOST."index.php?session=".$session."&module=".encode($success["module"])."&action=".encode($success["action"]));
		}
		else
		{
			$logger->debug(__FILE__." : Invalid Login");
			header('WWW-Authenticate: Basic realm="Login Failed"');
			header('HTTP/1.0 401 Unauthorized');
			header("location:".HTTP_HOST."index.php?session=authentication_failed&module=".encode($failure["module"])."&action=".encode($failure["action"]));
		}
	}
}

?>
