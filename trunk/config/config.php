<?php
// +----------------------------------------------------------------------+
// | SO Framework                                                         |
// +----------------------------------------------------------------------+
// | Copyright (c) 2012-2016                                              |
// +----------------------------------------------------------------------+

/* 
* host file contains the detail of the domain name which is pointed to this application
*/
include("host.php");

/* **************************************************************************** */

/* 
* We have define memory limit up to 100 MB. You can also change it from php configuration file e.g. [.ini]  
*/
ini_set('memory_limit', '100M');

/* **************************************************************************** */

/* 
* Your Current version of framework.
*/
define("VERSION", "1.0.0");

/* **************************************************************************** */

/* 
* This is your default page title
*/

define("TITLE", "SO LAB");

/* **************************************************************************** */

/* 
* You have to define your theme name here. It is by default Metro.
*/
define("THEME", "Metro");

/* **************************************************************************** */

/* 
* upload is the default directory where you can save your uploaded files from application. You have to give read and write permission to this directory.
*/
define("UPLOAD_DIR", "upload/");
/* **************************************************************************** */

/* 
* You must have assign an type for login user. It means you login table must have a column which define the type of user. So that you can give him permission to access and privilege.
*/
define("USERTYPE", "type");

/* **************************************************************************** */

/* 
* You can set your email id for mailing purpose.
*/
define("Email", "");

/* **************************************************************************** */

/* 
* Creating Logs under logs/ directory. 
*/
define("LOG4PHP_CONFIGURATION", "conf/appender.properties");
/* **************************************************************************** */

/* 
* Number of rows by default is set to 2000. it is display into data table. You can use limit keywords to increase the limit while running your database query.
*/
define("ROW", "2000");

/* **************************************************************************** */

/* 
* this is url encoder and encoder key. PLEASE DONT USE CHARACTER ":" IN ENCRYPTION KEY
*/
define("GV_PASS_ENCODER_KEY", "");
define("GV_URL_ENCODER_KEY", "%#BALOS%#"); 

/* **************************************************************************** */

/* 
* Smarty engine directory. 
*/
define('SMARTY_DIR', APP_DIR.'lib/view/');

/* **************************************************************************** */

/* 
* Application default timeout is set by default to 3600 [60 minutes]
*/
define('APP_TIMEOUT','3600');

/* **************************************************************************** */

/* 
* Default page and default temp file is define.
*/
define('DEFAULT_PAGE','login.html');
define('DEFAULT_TEMP','default.tpl');

/* **************************************************************************** */

/* 
* SESSION_KEY is by default set to id.
*/
define('SESSION_KEY','id');
/* **************************************************************************** */

/* 
* /tmp directory must have permission for write. this directory contains mtp file of smarty template engine.
*/
define("TEMP_DIR","tmp/");

/* **************************************************************************** */

/* 
* Custom variable witch is define in file placed in directory config/smarty.config.php 
*/
include_once("smarty.config.php");
define('GV_VAR', serialize($gv_var));

/* **************************************************************************** */

/* 
* Include database configuration file.	
*/

include_once("dbconfig.php");

/* **************************************************************************** */

/* 
* Include common library file. The function contains this file can be access from anywhere directly. 	
*/
include_once("common.lib.php");

/* **************************************************************************** */

/* 
* Include controller file.	
*/
include_once("Controller.php");
/* **************************************************************************** */

/* 
* Include logger class to implement debug, error and fatal log feature. 
*/
require_once("Logger.php");
Logger::configure(LOG4PHP_CONFIGURATION);
$logger = logger::getRootlogger();
/* **************************************************************************** */
?>