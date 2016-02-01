<?php
switch($ctrl->action) {
	case 'authen':
	{
		login('user', array("username","level","balance","idtariff","accountcode"), 
		array("username"=>$ctrl->username, "password"=>$ctrl->password), 
		array("module"=>"user","action" => "list" ), 
		array("module"=>"login","action" => "login"),
		$logger);
		
		break;
	}
	case 'login':
	{
		define("VIEW", "login.tpl");
		$ctrl->view->assign("module", $ctrl->module);
		$ctrl->view->assign("action", $ctrl->action);
		$logger->debug("View : ".VIEW);
		$ctrl->view->display("view/".VIEW);
		break;
	}
	default:
	{
		header("location:login.html");
	}
}
?>
