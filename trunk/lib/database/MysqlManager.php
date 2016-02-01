<?php 
include_once("database/Database.php");

final class MysqlManager extends Database
{
	private $logger;
	//This getter setter for username, password, host, database, link, last_id
	public function __construct()
	{
		$this->logger = logger::getRootlogger();
		$this->logger->info("Calling Constructor in MysqlManager");
		$this->data["username"]=DATABASE_USER;
		$this->data["password"]=DATABASE_PASS;
		$this->data["host"]=DATABASE_HOST;
		$this->data["port"]=DATABASE_PORT;
		$this->data["database"]=DATABASE_NAME;
		$this->data["last"]=0;
	}

	public function __set($key, $val)
	{
		$this->logger->info("Calling Setter in MysqlManager $key=>$val");
		$key=strtolower($key);
		if($key=="username" || $key=="password" || $key=="host" || $key=="database" || $key=="port")
			$this->data[$key]=$val;
		else
			$this->logger->error("Invalid variable is setting");
	}

	public function __get($key)
	{
		$key=strtolower($key);
		$this->logger->info("Calling Getter in MysqlManager $key=>".$this->data[$key]); 
		if($key == "username" || $key == "password" || $key == "host" || $key == "database" || $key == "last" || $key=="port" || $key == "datarows")
			return $this->data[$key];
		else
			return;
	}

    public function Open()
    {
        $this->logger->info("Calling Open function in MysqlManager");
		if (is_null($this->data["database"])) 
		{
			$this->logger->fatal("MySQL database not selected, Error : ".mysql_errno().", Description :".mysql_error());
			exit();
		}
			
        if (is_null($this->data["host"])) 
		{
			$this->logger->fatal("MySQL hostname not set, Error : ".mysql_errno().", Description :".mysql_error());
			exit();
		}
			
		if($this->data["port"]=="3306" || $this->data["port"]=="0")
			$this->link = mysql_connect($this->data["host"], $this->data["username"], $this->data["password"]); 
		else
	        $this->link = mysql_connect($this->data["host"].":".$this->data["port"], $this->data["username"], $this->data["password"]); 

        if ($this->link === false) 
		{
			$this->logger->fatal("Could not connect to database(".$this->data["host"]."). Check your username(".$this->data["username"]."), password(".$this->data["password"].") and database port (".$this->data["port"].") then try again. Mysql error ".mysql_errno()." : ".mysql_error()."");
			exit();
		}	
			
        if (!mysql_select_db($this->data["database"], $this->link)) { 
			$this->logger->fatal("Could not select database(".$this->data["database"]."), Error : ".mysql_errno().", Description :".mysql_error());
			exit();
		}
		$this->logger->info("End of Calling Open function in MysqlManager");
    }

    public function Close()
    {
		$this->logger->info("Calling Close function in MysqlManager");
        mysql_close($this->link); 
        unset($this->link);
    }

    public function Execute($sql)
    {
		$this->logger->info("Calling Execute function in MysqlManager");
		$this->logger->debug("YOUR DATABASE QUERY: ".$sql);
        if ($this->link === false) 
		{ 
			$this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error().""); 
			exit();
        } 

		$result = mysql_query($sql, $this->link); 
        if ($result === false)
		{ 
			$this->logger->error("Mysql query error ".mysql_errno()." : ".mysql_error().""); 
            return NULL;
        } 
		$this->data["datarows"]=mysql_num_rows($result);
		$this->logger->debug("Rows selected from database : ".$this->data["datarows"]."");
		if(mysql_num_rows($result)>0)
		{
			$count=0;
			while($rows=mysql_fetch_row($result))
			{
				$data[$count++]=$rows;
			}
		}
		$this->logger->info("End of Calling Execute function in MysqlManager");
		if(isset($data))
			return $data; 
		else
			return NULL; 
    }

	public function DataSet($sql)
    {
		$this->logger->info("Calling Dataset function in MysqlManager");
		$this->logger->debug("YOUR DATABASE QUERY: ".$sql);
        if ($this->link === false) 
		{ 
			$this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error().""); 
			exit();
        } 

		$result = mysql_query($sql, $this->link);
        if ($result === false)
		{ 
			$this->logger->error("Mysql query error ".mysql_errno()." : ".mysql_error().""); 
            return NULL;
        } 
		$this->data["datarows"]=mysql_num_rows($result);
		$this->logger->debug("Rows selected from database : ".$this->data["datarows"]."");
		
		if($this->data["datarows"]>0)
		{
			$count=0;
			while($rows=mysql_fetch_assoc($result))
			{
				$data[$count++]=$rows;
			}
		}
		$this->logger->info("End of Calling Dataset function in MysqlManager");
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
		$this->logger->info("Calling Row function in MysqlManager");
		$this->logger->debug("YOUR DATABASE QUERY: ".$sql);
		if ($this->link === false) { 
			$this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error()."."); 
			exit();
		} 

		$result = mysql_query($sql, $this->link); 
        if ($result === false) { 
			$this->logger->error("Mysql query error ".mysql_errno()." : ".mysql_error()."."); 
        		return NULL;
        } 

		$data = mysql_fetch_assoc($result);
		$this->data["datarows"]=count($data);
		$this->logger->debug("No of Rows return from Row function is ".count($data).".");
		$this->logger->info("End of Calling Row function in MysqlManager");
		if(isset($data))
			return $data; 
		else
		{
			$this->logger->debug("Null Data return");
			return NULL; 
		}
	}

    public function NonExecute($sql)
    {
		$this->logger->info("Calling NonExecute function in MysqlManager");
		$this->logger->debug("YOUR DATABASE QUERY: ".$sql);
        if ($this->link === false) { 
			$this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error().""); 
			exit();
		} 

		$result = mysql_query($sql, $this->link); 
		
		if ($result === false) { 
			$this->logger->debug("Cannot execute query ".$sql." Mysql error ".mysql_errno()." : ".mysql_error());
			return false;
		} 
		else
		{
			$id=mysql_insert_id();
			if($id>0)
				$this->data["last"] = mysql_insert_id();
			else
			{
				$row_change=mysql_affected_rows();
				if($row_change==0)
					return false;
			}			
		}
		$this->logger->info("End of Calling NonExecute function in MysqlManager");
        return $result;
    }

	public function status($values)
	{
		$this->logger->info("Calling status function in MysqlManager");
		$sql="SELECT `status` FROM `user` WHERE `username`='".$values["filter"]."'";
		$this->logger->debug("YOUR DATABASE QUERY: ".$sql);
        if ($this->link === false) { 
            $this->logger->fatal("No Database Connection Found. Mysql error ".mysql_errno()." : ".mysql_error().""); 
			exit();
        } 

		$result = mysql_query($sql, $this->link); 
        if ($result === false) { 
            return NULL;
        } 

        if(mysql_num_rows($result)>0){
			$count=0;
			while($rows=mysql_fetch_row($result)){
				$data[$count++]=$rows;
			}
		}
		$this->logger->info("End of Calling status function in MysqlManager");
		if(isset($data))
			return $data; 
		else
			return NULL; 
	}
}
?>