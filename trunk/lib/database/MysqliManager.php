<?php
require_once("database/Database.php");

final class MysqliManager extends Database
{
	private $logger;
	public function __construct()
	{
		$this->logger = logger::getRootlogger();
		$this->logger->info("Calling Constructor in MysqliManager");
		$this->data["username"]=DATABASE_USER;
		$this->data["password"]=DATABASE_PASS;
		$this->data["host"]=DATABASE_HOST;
		$this->data["port"]=DATABASE_PORT;
		$this->data["database"]=DATABASE_NAME;
	}

    //This getter setter for username, password, host, database, link, last_id
	public function __set($key,$val)
	{
		$key=strtolower($key);
		$this->logger->info("Calling Setter in MysqliManager $key=>$val");
		if($key=="username" || $key=="password" || $key=="host" || $key=="database" || $key == "port")
			$this->data[$key]=$val;
		else
			$this->logger->error( "Invalid variable is setting");
	}

	public function __get($key)
	{
		$this->logger->info("Calling Getter in MysqliManager $key=>".$this->data[$key]);
		if($key=="username" || $key=="password" || $key=="host" || $key=="database" || $kye=="last_id")
			return $this->data[$key];
		else
			return;
	}

    public function Open()
    {
        $this->logger->info("Calling Open function in MysqliManager");
		if (is_null($this->data["database"])) 
            $this->logger->fatal("MySQL database not selected"); 
        if (is_null($this->data["host"])) 
        {
            $this->logger->fatal("MySQL hostname not set");
            exit();
        } 

        $this->link = new mysqli($this->data["host"], $this->data["username"], $this->data["password"], $this->data["database"], $this->data["port"]); 

        if ($this->link->connect_errno) 
			$this->logger->fatal("Could not connect to database(".$this->data["host"]."). Check your username(".$this->data["username"].") and password(".$this->data["password"].") then try again.  Mysql error ".$this->link->connect_errno." : ".$this->link->connect_error."\n"); 

		$this->logger->info("End of Calling Open function in MysqliManager");
    }

    public function Close()
    {
		$this->logger->info("Calling Close function in MysqliManager"); 
        mysqli_close($this->link); 
        $this->link = null;
    }

    public function Execute($sql)
    {
		$this->logger->info("Calling Execute function in MysqliManager");
		$this->logger->debug($sql);
        if ($this->link === false) { 
            $this->logger->fatal("No Database Connection Found.  Mysql error ".mysql_errno()." : ".mysql_error()."");
            exit(); 
        } 

        $result = $this->link->query($sql); 
        if ($result === false) { 
            return NULL;
        } 
		
        if($this->link->num_rows>0){
			$count=0;
			while($rows=$this->link->fetch_row($result)){
				$data[$count++]=$rows;
			}
		}
		$this->logger->info("End of Calling Execute function in MysqliManager");
		if(isset($data))
			return $data; 
		else
			return NULL; 
    }

public function DataSet($sql)
    {
		$this->logger->debug("Calling Dataset function in MysqliManager");
		$this->logger->debug($sql);
        if ($this->link === false) 
		{ 
			$this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error()."");
			exit(); 
      } 

		$result = $this->link->query($sql);
        if ($result === false)
		{ 
			$this->logger->error("Mysql query error ".mysql_errno()." : ".mysql_error().""); 
            return NULL;
        } 
		$this->data["datarows"]=$this->link->num_rows;
		$this->logger->debug("Rows selected from database : ".$this->data["datarows"]);
		
		if($this->data["datarows"]>0)
		{
			$count=0;
			while($rows=$this->link->fetch_assoc($result))
			{
				$data[$count++]=$rows;
			}
		}
		$this->logger->debug("End of Calling Dataset function in MysqliManager");
		if(isset($data))
			return $data; 
		else
		{
			$this->logger->error("No data found");
			return NULL; 
		}
    }
	

	public function Row($sql)
	{
		$this->logger->info("Calling Row function in MysqliManager");
		$this->logger->debug($sql);
        if ($this->link === false) { 
			$this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error()."");
			exit(); 
        } 

		$result = $this->link->query($sql); 
        if ($result === false) { 
			$this->logger->error("Mysql query error ".mysql_errno()." : ".mysql_error().""); 
            return NULL;
        } 

		if($result->num_rows==1){
			$data=$row = $this->link->fetch_assoc($result);
		}
		$this->logger->info("End of Calling Row function in MysqliManager");
		if(isset($data))
			return $data; 
		else
			return NULL; 
	}

    public function NonExecute($sql)
    {
		$this->logger->info("Calling NonExecute function in MysqliManager");
		$this->logger->debug($sql);
		if ($this->link === false) { 
            $this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error()."");
            exit(); 
        } 

        $result = $this->link->query($sql); 
        if ($result === false) { 
            return false;
        } 
		else
			$this->data["last_id"] =$this->link->insert_id(); 

        $this->logger->info("End of Calling NonExecute function in MysqliManager");
		return $result;
    }

	public function status($values)
	{
		$this->logger->info("Calling status function in MysqliManager");
		$this->logger->debug($sql);
		$sql="SELECT `status` FROM `users` WHERE `username`='".$values["filter"]."'";
		if ($this->link === false) { 
            $this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error()."");
            exit(); 
        } 

        $result = $this->link->mysqli_query($sql); 
        if ($result === false) { 
            return NULL;
        } 
		
        if($this->link->mysqli_num_rows($result)>0){
			$count=0;
			while($rows=$this->link->mysqli_fetch_row($result)){
				$data[$count++]=$rows;
			}
		}
		$this->logger->info("End of Calling status function in MysqliManager");
		if(isset($data))
			return $data; 
		else
			return NULL; 
	}
}
?>