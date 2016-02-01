<?php
	
	$config["user"]=array(
		/* admin action */
		"add1"=>array("template"=>"add.html", "function"=>"AddAdminUser", "action"=>"save1"), 
		"save1"=>array("function"=>"SaveAdminUser", "success"=>"list1", "failure"=>"add1"),
		"edit"=>array("function"=>"EditAdminUser", "template"=>"edit.html", "action"=>"update"),
		"update"=>array("function"=>"UpdateAdminUser", "success"=>"list1", "failure"=>"list1"),
		"list1"=>array("template"=>"list.html", "function"=>"AdminListView"),
		"filter"=>array("template"=>"list.html", "function"=>"FilterListView"),
		"block"=>array("function"=>"Block","success"=>"list", "failure"=>"list"),
		/* reseller's action */
		"add"=>array("template"=>"add.html", "function"=>"AddUser", "action"=>"save"), 
		"save"=>array("function"=>"SaveUser", "success"=>"list", "failure"=>"add"),
		"updateuser"=>array("function"=>"UpdateUser", "success"=>"list", "failure"=>"list"),
		"edituser"=>array("function"=>"EditUser", "template"=>"edit.html", "action"=>"updateuser"),
		"list"=>array("template"=>"list.html", "function"=>"UserListView"), 
		/* common action for both */ 
		"savepayment"=>array("function"=>"SavePayment", "success"=>"list", "failure"=>"list"),
		"delete"=>array("function"=>"Deleted", "success"=>"list", "failure"=>"list"), 
		"detail"=>array("template"=>"detail.html", "function"=>"Profile"),
		"profile"=>array("template"=>"detail.html", "function"=>"Profile"),
		"password"=>array("template"=>"password.html", "function"=>"ChangePassword", "action"=>"savepassword"), 
		"savepassword"=>array("function"=>"SavePassword","success"=>"profile", "failure"=>"password"),
		"calls"=>array("template"=>"call.html", "function"=>"GetCurrentCall"), 
		"logout"=>array("function"=>"Logout", "success"=>"login", "failure"=>"profile"));
?>