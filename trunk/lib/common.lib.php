<?php
include_once("database/Query.php");
include_once("database/NonQuery.php");
include_once("config/roles.config.php");

	
function encode($x) {
    return urlencode(base64_encode(time().":".$x));
}

function roles_URL($roles,$module, $action)
{
	if($roles[LOGINTYPE][$module][$action]==1)
		return true;
	else
		return false;
}

function roles_HREFURL($roles, $module, $action)
{
	if($roles[LOGINTYPE][$module][$action]==1)
		return true;
	else
		return false;
}

function decode($x, $logger,$key="") {
    $y = base64_decode(urldecode($x));
    $tmp=explode(":", $y);

	if($key!="module" || $key!="action" || $key!=="type")
	    $logger->debug("Decode current value ($key) : ".$tmp[1]);

	if($tmp[1]!="login"){
		if((time()-$tmp[0])>APP_TIMEOUT)
			return false;
	}
    return $tmp[1];
}

function login($table, $field, $filter, $success, $failure, $logger)
{		
		$logger->info("calling login function in common.lib.php file");
		$db = new MysqlManager();
		$query = new Query($db);
		$query->table=$table;
		$query->type="select";
		if(defined('SESSION_KEY'))
			$query->field1=SESSION_KEY." userid";
		else 
		{
			$logger->error("Session key is not define, check your  config".SESSION_KEY);
			if(defined('DEFAULT_PAGE'))
				header("location:".HTTP_HOST.DEFAULT_PAGE);
			else
				header("location:".HTTP_HOST."login.html");
			exit();
		}
		
		if(defined('USERTYPE'))
			$query->field2=USERTYPE." usertype";
		else 
		{
			$logger->info("Usertype field is not define, you might making solution multiple usertype");
		}		
		$i=3;
		// if count condition  for $field and for filter check
		foreach ($field as $val){
			$temp= "field".$i;
			$query->$temp=$val;
			$i++;
		}

		$query->filter=$filter;
		$query->prepare();
		$values=$query->Row();
		//print_r($values);die;
		logger_print_array($logger, $values);
		
		if(count($values)>1)
		{
			$str="Admin autentication return data";
			foreach($values as $key=>$val)
			{
				$str.=$key."=>".$val;
			}
			$logger->debug($str);
			
			$session=md5(time().$username.$password.GV_PASS_ENCODER_KEY);
			//session_register($session);
			
			$_SESSION[$session]=$values;
		
			header("location:".HTTP_HOST."index.php?session=".$session."&module=".encode($success["module"])."&action=".encode($success["action"]));
			exit();
		}
		else
		{
			$logger->debug("Invalid Login");
			header('WWW-Authenticate: Basic realm="Login Failed"');
			header('HTTP/1.0 401 Unauthorized');
			header("location:".HTTP_HOST."index.php?session=authentication_failed&module=".encode($failure["module"])."&action=".encode($failure["action"]));
		}                                                                  
}

// function login($table, $field, $filter, $success, $failure, $logger)
// {		
		// $logger->info("calling login function in common.lib.php file");
		// $db = new MysqlManager();
		// $query = new Query($db);
		// $query->table=$table;
		// $query->type="select";
		
		// if(defined('SESSION_KEY'))
			// $query->field1=SESSION_KEY." userid";
		// else 
		// {
			// $logger->error("Session key is not define, check your  config".SESSION_KEY);
			// if(defined('DEFAULT_PAGE'))
				// header("location:".HTTP_HOST.DEFAULT_PAGE);
			// else
				// header("location:".HTTP_HOST."login.html");
			// exit();
		// }
		
		// if(defined('USERTYPE'))
			// $query->field2=USERTYPE." usertype";
		// else 
		// {
			// $logger->info("Usertype field is not define, you might making solution multiple usertype");
		// }		
		// $i=3;
		//if count condition  for $field and for filter check
		// foreach ($field as $val){
			// $temp= "field".$i;
			// $query->$temp=$val;
			// $i++;
		// }

		// $query->filter=$filter;
		// $query->prepare();
		// $values=$query->Row();
		// logger_print_array($logger, $values);
		
		// if(count($values)>1)
		// {
			// $str="Admin autentication return data";
			// foreach($values as $key=>$val)
			// {
				// $str.=$key."=>".$val;
			// }
			// $logger->debug($str);
			
			// $session=md5(time().$username.$password.GV_PASS_ENCODER_KEY);
			// session_register($session);
			
			// $_SESSION[$session]=$values;
		
			// $headers=apache_request_headers();
			// $val=GetDomain($values["iddomain"],$values["idadmin"],$values["userid"],$values["usertype"],$logger);
			// if(is_array($val))
			// {
				// if($_SERVER["HTTP_HOST"]==$val['domain_name']) 
					// header("location:".HTTP_HOST."index.php?session=".$session."&module=".encode($success["module"])."&action=".encode($success["action"]));
				// else
					// header("location:login.html");
			// }
			// else
				// header("location:".HTTP_HOST."index.php?session=".$session."&module=".encode($success["module"])."&action=".encode($success["action"]));
   		
   		// exit();
		// }
		// else
		// {
			// $logger->debug("Invalid Login");
			// header('WWW-Authenticate: Basic realm="Login Failed"');
			// header('HTTP/1.0 401 Unauthorized');
			// header("location:".HTTP_HOST."index.php?session=authentication_failed&module=".encode($failure["module"])."&action=".encode($failure["action"]));
			
		// }                                                                   
// }


function logger_print_array($logger, $array)
{
	foreach($array as $key=>$val)
	{
		$logger->debug("$key => $val");
	}
}

function managedata($data, $logger)
{
	if(isset($data["module"])){
		if(addslashes(trim($data["module"]))=="login")
			$mod="login";
		else
			$mod=decode(addslashes(trim($data["module"])),$logger, "module");

		if(strlen($mod)>0)
		{
			$GLOBALS['module']=$mod;
		}
	}
	
	if(isset($data["action"])){
		if(addslashes(trim($data["action"]))=="login")
			$mod="login";
		else
			$mod=decode(addslashes(trim($data["action"])), $logger, "action");
			
		if(strlen($mod)>0)
		{
			$GLOBALS['action']=$mod;
		}
	}
	
	if(isset($data["session"]))
	{
		$GLOBALS['session']=addslashes(trim($data["session"]));
		if(is_array($_SESSION[$GLOBALS['session']]))
		{
			foreach($_SESSION[$GLOBALS['session']] as $key=>$val)
			{
				$sess_data[$key]=addslashes($val);
			}
		}
	}

	foreach($data as $key=>$val)
	{
		if($key=="module" || $key=="session" || $key=="action" || $key=="id" || $key=="type" || $key=="ch" || $key=="status" || $key=="error")
		{
				continue;
		}
		$__data[addslashes(trim($key))]=addslashes(trim($val));
	}
		
	if(isset($data["key"]))
	{  
		$__data["key"]=decode(addslashes(trim($data["key"])),$logger,"key");
	}

	if(isset($data["error"]))
	{  
		$__ERROR=decode(addslashes(trim($data["error"])),$logger,"error");
	}

	if(isset($data["type"]))
	{
		$__data["type"]=decode(addslashes(trim($data["type"])),$logger,"type");
	}

	if(isset($data["ch"]))
	{
		$__data["ch"]=decode(addslashes(trim($data["ch"])), $logger,"ch");
	}

	if(isset($data["status"]))
	{
		$__data["status"]=decode(addslashes(trim($data["status"])), $logger,"status");
	}

	if(isset($data["page"]))
	{
		$__data["page"]=decode(addslashes(trim($data["page"])), $logger, "page");
	}
	else
		$__data["page"]=1;
	
	return $__data;
}

function verifysession($module, $action, $data, $logger)
{
	$val=false;
	$logger->debug("Session verifying... with module $module");

	if(defined('SESSION_KEY')) {
		if(!isset($data[SESSION_KEY]))
			return $val; 
	} 
	else 
		return false ;
	$logger->debug("Session verified with module $module : ".$val);

	return true;
}


function MakeTime($date)
{
	$temp=explode(" ",$date);
	$start=explode("/", $temp[0]);
	return (trim($start[2])."-".trim($start[0])."-".trim($start[1])." ".trim($temp[1]).":00");
}

function MakeDate($date)
{
	$start=explode("/", $date);
	return (trim($start[2])."-".trim($start[0])."-".trim($start[1]));
}

function GetDomain($domain,$admin,$userid,$usertype,$logger)
{
	$db = new MysqlManager();
	$query = new Query($db);
	if($usertype==1)
		$sql="SELECT domain_name from domain where iddomain=".$domain."";
	if($usertype==2)
		$sql="SELECT domain_name from domain where iddomain=".$domain." and iduser=".$userid." and idadmin=".$admin."";
	$query->raw_prepare($sql);
	$val=$query->Row();
	$logger->debug("Domain for user is : ".$val);
	return $val;
}
?>
