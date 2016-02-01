<?php
define('SERVER', strtoupper(PHP_OS)); 

//define('APP_DIR', dirname(__FILE__)."\\");
ini_set('display_errors',0);
include_once("config/config.php");
include_once("config/roles.config.php");
include_once("config/module_setting.php");
include_once("include/Dashboard.php");

$ctrl=new Controller();
$ctrl->PreLoad($roles);
define("LOGINTYPE", $ctrl->usertype);
define("USERID", $ctrl->userid);
define("LEVEL", $ctrl->level);
//print($ctrl->usertype);print(USERID);

require_once("module/admin.php");

foreach($roles[$ctrl->usertype] as $key=>$val)
{
	foreach($val as $k=>$v)
	{
		if($v==1)
			$module_setting[$key][$k]=$config[$key][$k];
	}
}
//print_r($module_setting);
$ctrl->Module($obj);
$ctrl->LoadModule();
$ctrl->ModuleSetting("filter", $filter);
$ctrl->ModuleSetting("table", $table);
$ctrl->ModuleSetting("key", $key1);
$ctrl->ModuleSetting("basetpl", $basetpl);
$ctrl->ModuleSetting("datafield", $datafield);
$ctrl->ModuleSetting("field", $field);
$ctrl->ModuleSetting("header", $header);
$ctrl->setting=$module_setting[$ctrl->module];
$ctrl->roles=$roles;

//  Prepare and execute function detached from cases 
if($ctrl->prepare())
	 $ctrl->execute();
	

?>