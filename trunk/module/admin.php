<?php
switch ($ctrl->module)
{
	case "country":
	{
		require_once("Country.php");
		$obj=new Country("mysql", $logger);
		$filter=array();
		$table="country";
		$key1="idcountry";
		$basetpl="user.html";
		$datafield=array("idcountry","country","countryprefix");
		$field=array("country","countryprefix");
		$header=array("Country","Country Prefix","Action");
		break;
	}
	
	case "switchsetting":
	{
		require_once("SwitchSetting.php");
		$obj=new SwitchSetting("mysql", $logger);
		$filter=array();
		$table="switch_settings";
		$key1="idsettings";
		$basetpl="user.html";
		$datafield=array("idsettings","switch_ip","variation","max_time_duration", "min_time_duration");
		$field=array("switch_ip","variation","duration");
		$header=array("IP Addr","Variation(In Seconds)","Max / Min Time(In Seconds)","Action");
		break;
	}
	
	case "Permissions":
	{
		require_once("Permissions.php");
		$obj=new Permissions("mysql", $logger);
		$filter=array();
		$table="permission";
		$key1="id";
		$basetpl="user.html";
		$datafield=array("id","iduser","calls","asr_acd","client_usage","dest_usage","low_balance","hourly","daily","weekely","cdr");
		$field=array("iduser","calls","asr_acd","client_usage","dest_usage","low_balance","hourly","daily","weekely","cdr");
		$header=array("User","Calls","Asr/Acd","Client Usage","Destination Usage","Low Balance","Hourly","Daily","Weekly","Cdr","Action");
		break;
	}
	
	case "cdr":
	{ 	require_once("Report.php");
		$obj=new Report("mysql", $logger);
		$filter=array();
		$table="hourly_reports";
		$key1="";
		$basetpl="user.html";
		$datafield=array("date","time","licence","attempt","minutes","connect","busy","failed","asr","acd");
		$field=array("username","date","prefix","destination","minutes","attempts");
		$header=array("Reseller","Date","Prefix","Destination","Minutes","Attempts");
		
		break;
	}
	case "graphical":
	{ 	require_once("Graphical.php");
		$obj=new Graphical("mysql", $logger);
		$filter=array();
		$table="reports";
		$key1="";
		$basetpl="user.html";
		$datafield=array("date","time","licence","attempt","minutes","connect","busy","failed","asr","acd");
		$field=array("username","date","prefix","destination","minutes","attempts");
		$header=array("Reseller","Date","Prefix","Destination","Minutes","Attempts");
		break;
	}
	case "dashboard":
	{
		require_once("Dashboard.php");
		$obj=new Dashboard("mysql", $logger);
		$filter=array();
		$table="cdr";
		$key1="id";
		$basetpl="user.html";
		$datafield=array();
		$field=array();
		$header=array();
		break;
	}
	case "destination":
	{
		require_once("Destination.php");
		$obj=new Destination("mysql", $logger);
		$filter=array();
		$table="destination_group";
		$key1="iddestination_group";
		$basetpl="user.html";
		$datafield=array("iddestination_group","prefix","name","description","initial","pulse","grace");
		$field=array("name","dialcode");
		$header=array("Destination" ,"Destination Code","Edit");
		break;
	}
	
	case "supplier":
	{
		require_once("Supplier.php");
		$obj=new Supplier("mysql", $logger);
		$filter=array("deleted"=>0);
		$table="supplier";
		$key1="idsupplier";
		$basetpl="user.html";
		$datafield=array("idsupplier","username","idtariff","password","ipaddress","desc","created_date","updated_date","unicode");
		$field=array("username","tariff","ipaddress","prefix","unicode");
		$header=array("Name","Tariff","Ipaddress","Prefix","Unicode","Action");
		break;
	}
	
	case "payment":
	{
		require_once("Payment.php");
		$obj=new Payment("mysql", $logger);
		$filter=array();
		$table="payment";
		$key1="id";
		$basetpl="user.html";
		$datafield=array("id","amount","iduser","payment_date", "description");
		$field=array("amount","username","payment_date", "description");
		$header=array("Amount","User Name","Payment Date", "Description");
		
		break;
	}
	
	case "rate":
	{
		require_once("Rate.php");
		$obj=new Rate("mysql", $logger);
		$filter= array();
		$table="rates";
		$key1="idrate";
		$basetpl="user.html";
		$datafield=array("idrate","idtariff","rate","destination","initial","pulse","grace","prefix");
		$field=array("destination","prefix","rate","initial","pulse","grace");
		$header=array("Destination","Dest Code","Rate","Initial","Pulse","Grace","Action");
		
		break;
	}
	
	case "settings":
	{
		require_once("Settings.php");
		$obj=new Settings("mysql", $logger);
		$filter=array("iduser"=>USERID);
		$table="ivr";
		$key1="id";
		$basetpl="user.html";
		$datafield=array("id","welcome","start_hr","start_min","end_hr","end_min","after_hr","status");
		$field=array("welcome","start","end","after_hr","status");
		$header=array("Welcome","Start Time","End Time","After Hr","Status","Action");
		break;
	}
	
	case "sipuser":
	{
		require_once("SipUser.php");
		$obj=new SipUser("mysql", $logger);
		$filter=array();
		$table="sipuser";
		$key1="id";
		$basetpl="user.html";
		$datafield=array("id","name","secret","balance","tariff_type","idtariff","firstname","lastname","phone","email","host","address");
		$field=array("name","secret","balance","tariffname","ipaddr");
		$header=array("User name","Password","Balance","Tariff","Ip Address","Action");
		
		break;
	}
	
	case "tariff":
	{
		require_once("Tariff.php");
		$obj=new Tariff("mysql", $logger);
		$filter= array();
		$table="tariff_name";
		$key1="id";
		$basetpl="user.html";
		$datafield=array("id","name","desc");
		$field=array("name","desc");
		$header=array("Tariff","Description","Action");
		
		break;
	}
	
	case "user":
	{
		require_once("User.php");
		$obj=new User("mysql", $logger);
		$filter=array();
		$table="user";
		$key1="id";
		$basetpl="user.html";
		$datafield=array("id","idtariff","username","password","type","balance","flag","apply_setting");
		$field=array("username", "password","tariff","balance","unicode","flag");
		$header=array("UserName", "Password","Tariff", "Balance","Unicode","Flag","Action");
		
		break;
	}
	
	case "routing":
	{
		require_once("Routing.php");
		$obj=new Routing("mysql", $logger);
		$filter=array();
		$table="routing";
		$key1="id";
		$basetpl="user.html";
		$datafield=array("id","idclient","idsupplier","prefix","priority","tariff_type","status","dial_pattern");
		$field=array("client","tariff_type","prefix","dial_pattern","priority","supplier","ipaddress","flag");
		$header=array("Client","Tariff Type","Prefix","Destination Code","Priority","Supplier","IP","Flag","Action");
		break;
	}
	
	case "report_setting":
	{
		require_once("ReportSetting.php");
		$obj=new ReportSetting("mysql", $logger);
		$filter=array();
		$table="report_setting";
		$key1="id";
		$basetpl="user.html";
		$datafield=array("id","high_volume","low_volume");
		$field=array("high_volume","mid_volume","low_volume");
		$header=array("High volume","Mid Volume","Low Volume","Action");
		break;
	}
	
	
	default:
	{
		$ctrl->LoadModule();
		include_once("module/index.php");
		exit;
	}
}
?>