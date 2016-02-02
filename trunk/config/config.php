<?php
// +----------------------------------------------------------------------+
// | GV Framework                                                         |
// +----------------------------------------------------------------------+
// | Copyright (c) 2012-2016 G Venture Technology Pvt. Ltd.               |
// +----------------------------------------------------------------------+

	if(SERVER=="WINNT"){
		define('APP_DIR', str_replace('\\','/',getcwd())."/");
		//define('APP_DIR', 'C:/wamp/www/gvframe/ver1/cdr/trunk/');
		ini_set('include_path', ini_get('include_path') .";".APP_DIR."lib/view;".APP_DIR."lib/plugin;".APP_DIR."lib;".APP_DIR."lib/logger;".APP_DIR."config;".APP_DIR."include;".APP_DIR."feature;");
		define("HTTP_HOST", "http://localhost/cms/mygithub/gventure_framework/trunk/");
	}
	else{
		define('APP_DIR', str_replace('\\','/',getcwd())."/");
		//define('APP_DIR', '/opt/lampp/htdocs/gvframe/trunk/');
		ini_set('include_path', ini_get('include_path') .":".APP_DIR."lib/view:".APP_DIR."lib:".APP_DIR."lib/logger:".APP_DIR."config:".APP_DIR."include:".APP_DIR."lib/plugin:");
			define("HTTP_HOST", "http://localhost/trunk/");
	}
	$timezone = "Asia/Kolkata"; 
	
	if(function_exists('date_default_timezone_set')) 
		date_default_timezone_set($timezone); 
		
	define("VERSION", "0.1.0");
	define("TITLE", "Voip Bussiness Directory");
	define("THEME", "Metro");
	ini_set('memory_limit', '100M');
	define("UPLOAD_DIR", "upload/");
	define("USERTYPE", "type");
	define("Email", "support@gventure.net");
	define("LOG4PHP_CONFIGURATION", "conf/appender.properties");
	define("COMPANY", "2");
	define("SOUND", "sound/");
	define("THEME", "METRO");
	define("EVENT", "1");
	define("ROW", "2000");
	define("GV_PASS_ENCODER_KEY", "GventureCallnow");
	define("GV_URL_ENCODER_KEY", "%#GventureCallnow%#");// PLEASE DONT USE CHARACTER ":" IN ENCRYPTION KEY
	define('SMARTY_DIR', APP_DIR.'lib/view/');
	define('APP_TIMEOUT','3600');
	define('DEFAULT_PAGE','login.html');
	define('DEFAULT_TEMP','default.tpl');
	define('SESSION_KEY','id');
	define("TEMP_DIR","tmp/");
	
	include_once("dbconfig.php");
	include_once("common.lib.php");
	
	//include_once("paypal_pro.inc.php");
	//include_once("payflow.inc.php");
	//include_once("pagination.class.php");
	include_once("Controller.php");
	require_once("Logger.php");
	Logger::configure(LOG4PHP_CONFIGURATION);
	$logger = logger::getRootlogger();
?>