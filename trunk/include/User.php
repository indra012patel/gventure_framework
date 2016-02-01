<?php
include_once("Management.php");
final class User extends Management
{
	public function GetTariff()
	{
		if(LEVEL<=5)
			$this->sql="(SELECT u.`idtariff`,'Base Tariff' `name` FROM `user` u WHERE id=".USERID.") UNION (SELECT t.`id`,`name` FROM tariff_name t JOIN tariff_user tu ON t.id=tu.id_tariffname WHERE iduser=".USERID.")";
		else
			$this->sql="SELECT t.`id` `idtariff`,`name` FROM tariff_name t join tariff_user tu on t.id=tu.id_tariffname WHERE iduser=".USERID."";
		$data=parent::Custom();
		foreach($data as $val)
			$tmp[$val["idtariff"]]=$val["name"];
		return $tmp;	
	}
	public function Profile($template)
	{
		$this->table="user";
		$this->datafield=array("id","username","password","balance","unicode");
		$this->key="id";
		$this->data["keyvalue"]=USERID;
		$this->Detail($template);
		$this->sql="SELECT u.`username`, t.`tariffname`, (SELECT `abbr` FROM `tariff_type` WHERE id=tt.`tariff_type`) `tariff_type`, tt.`prefix`, tt.`idtariff` FROM `user` u JOIN `user_tariff` tt ON u.id=tt.iduser JOIN `tariff` t ON t.idtariff=tt.idtariff WHERE id=".USERID;
		$data=parent::custom();
		$this->smarty->assign('userData',$data);
	}
	//function for reseller creating user
	public function AddUser($template="")
	{
		parent::AddNew($template);
		$this->view->Options("opttariff", $this->GetTariff());
		$this->view->Options("optivr", $this->option("ivr", "name", array(), "id"));
		$this->view->assign("userlevel", LEVEL);
	}
	
	public function SaveUser()
	{
		$unicode=$this->get_unicode();
		if(LEVEL<4 || $this->data["reqdata"]["ivr"] == '-1' || empty($this->data["reqdata"]["ivr"])){
			$this->sql="SELECT `idivr` FROM `user` WHERE `id`=".USERID;
			$ivr=parent::custom();
			$idivr=$ivr[0]['idivr'];
		}else{
			$idivr=$this->data["reqdata"]["ivr"];
		}
		if(isset($this->data["reqdata"]["apply"]))
			$apply=0;
		else
			$apply=1;
		$data=array("username"=>$this->data["reqdata"]["username"],"password"=>$this->data["reqdata"]["password"],"idparent"=>USERID,"type"=>1,"balance"=>$this->data["reqdata"]["bal"],"recharge_balance"=>$this->data["reqdata"]["bal"],"idivr"=>$idivr,"level"=>LEVEL-1,"unicode"=>$unicode,"flag"=>1,"idtariff"=>$this->data["reqdata"]["tariff"],"apply_setting"=>$apply);
		parent::Save($data);
		$id=$this->last;
		if(LOGINTYPE!=0)
		{
			$this->sql="UPDATE `user` SET `balance`=`balance`-".$this->data['reqdata']['bal'].",`recharge_balance`=`recharge_balance`-".$this->data['reqdata']['bal']." WHERE `id`='".USERID."'";
			parent::custom();
		}		
		//Add Tariff in user_tariff table
		/* $tmp1=$this->data["reqdata"]["tariff"];
		$this->sql="select idtariff,tariff_type from tariff where id_tariffname=".$this->data["reqdata"]["tariff"]."";
		$tariff=parent::custom();
		$sql="INSERT INTO user_tariff(`iduser`,`idtariff`,`tariff_type`,`prefix`) values";
		$q="";
		foreach($tariff as $val)
		{
			if($val["tariff_type"]==1)
				$col='with_prefix';
			if($val["tariff_type"]==2)
				$col='without_prefix';	
			$this->sql="SELECT $col as `col` FROM switch_settings";
			$data=parent::custom();
			$q.="('".$id."','".$val["idtariff"]."','".$val["tariff_type"]."','".$data[0]['col']."'),";		
		}
		$q=rtrim($q,',');
		$this->sql=$sql.$q;
		parent::custom(); */
		
		//add balance to payment table
		$payment_date=date("Y-m-d H:i:s");
		$desc="Payment for account setup  on ".$payment_date." of balance ".$this->data["reqdata"]["username"];
		$balance=array("iduser"=>$id,"amount"=>$this->data['reqdata']['bal'],"payment_date"=>$payment_date,"description"=>$desc,"usertype"=>1,"type"=>1,"flag"=>1,"created_by"=>USERID);
		parent::RawSave('payment', $balance, 'id');
		if(LOGINTYPE==0)
			$accountcode=$id;
		else	
			$accountcode=$this->get_account_code($id);
		parent::RawUpdate("user",array("accountcode"=>$accountcode),$id,"id");
		$this->response();
	}
	
	public function EditUser($template="")
	{
		parent::Edit($template);
		$this->view->assign("userlevel", LEVEL);
		$this->view->Options("opttariff", $this->GetTariff());
	}
	
	public function UpdateUser()
	{	
		/* if($this->data["reqdata"]["tariff"]!=$this->data["reqdata"]["oldtariff"])
		{
			$this->sql="DELETE FROM user_tariff WHERE `iduser`=".$this->data["keyvalue"];
			parent::custom();
			$this->sql="select idtariff,tariff_type from tariff where id_tariffname=".$this->data["reqdata"]["tariff"]."";
			$tariff=parent::custom();
			$sql="INSERT INTO user_tariff(`iduser`,`idtariff`,`id_tariffname`,`tariff_type`,`prefix`) values";
			$q="";
			foreach($tariff as $val)
			{
				if($val["tariff_type"]==1)
					$col='with_prefix';
				if($val["tariff_type"]==2)
					$col='without_prefix';	
				$this->sql="SELECT $col as `col` FROM switch_settings";
				$data=parent::custom();
				$q.="('".$this->data["keyvalue"]."','".$val["idtariff"]."','".$this->data["reqdata"]["tariff"]."','".$val["tariff_type"]."','".$data[0]['col']."'),";		
			}
			$q=rtrim($q,',');
			$this->sql=$sql.$q;
			parent::custom();
		} */
		if(isset($this->data["reqdata"]["apply_setting"]))
			$apply=0;
		else
			$apply=1;
		$this->data["reqdata"]=array("username"=>$this->data["reqdata"]["username"],"password"=>$this->data["reqdata"]["password"],"idtariff"=>$this->data["reqdata"]["tariff"],"apply_setting"=>$apply);
		parent::Update();
	}
	
	public function UserListView($template="")
	{	
		$this->sql="SELECT u.id,u.username, u.password, round(u.balance,4) balance,round(u.recharge_balance,4) recharge_balance, u.type, u.flag, u.unicode,t.name `tariff` FROM user u LEFT JOIN tariff_name t  ON u.idtariff=t.id WHERE idparent=".USERID."";
		parent::RawListView($template);
		$this->view->assign('method','add');
		$this->view->assign('method_name','Add User');
		$this->view->assign('editmethod','edituser');
		$this->view->assign('deletemethod','delete');
		$this->smarty->assign("level",LEVEL);
		$this->smarty->assign("filter",$this->data["reqdata"]);
	}
	
	
	public function AddPayment($template="")
	{
		parent::Edit($template);
	}
	//payment done by parent
	public function SavePayment()
	{
		$this->table="payment";
		$user=$this->data['keyvalue'];
		$payment_date=date("Y-m-d H:i:s");
		$data=array("iduser"=>$user,"amount"=>$this->data['reqdata']['amount'],"payment_date"=>$payment_date,"description"=>$this->data['reqdata']['desc'],"usertype"=>1,"type"=>1,"flag"=>1,"created_by"=>USERID);
		if(!empty($this->data['reqdata']['amount']))
		{
			$this->sql="UPDATE `user` SET `balance`=`balance`+".$this->data['reqdata']['amount'].",`recharge_balance`=`recharge_balance`+".$this->data['reqdata']['amount']." WHERE `id`='".$user."'";
			parent::custom();
			if(LOGINTYPE!=0)
			{
				$this->sql="UPDATE `user` SET `balance`=`balance`-".$this->data['reqdata']['amount']." ,`recharge_balance`=`recharge_balance`-".$this->data['reqdata']['amount']." WHERE `id`='".USERID."'";
				parent::custom();
			}	
		}
		parent::Save($data);
		header("location:".HTTP_HOST."index.php?session=".$this->data["session"]."&module=".encode("user")."&action=".encode("list"));
	}
	
	
	public function EditProfile($template)
	{
		$this->data["keyvalue"]=$this->data["sessdata"]["userid"];
		parent::Edit($template);
		$this->view->Options("optuser", $this->Option("user","username",array(),"iduser"));
	}
	
	public function UpdateProfile()
 	{
    	$data=array("username"=>$this->data["reqdata"]["username"], "password"=>$this->data["reqdata"]["password"]);
  		$this->data["keyvalue"]=$this->data["sessdata"]["userid"];
     	$this->data["reqdata"]=$data;
        parent::Update();
	}
	
	public function ChangePassword($template)
	{
		if(strlen($template)>0)
			$this->template_add=$template;
		
		if(isset($this->template["add"]))
			parent::AddNew($template);		
	}
	
	public function SavePassword()
	{
		$this->sql = "SELECT `password` FROM `user` WHERE `id`='".$this->data["sessdata"]["userid"]."'";
		$data = parent::custom();
		if($this->data["reqdata"]["oldpassword"]===$data[0]['password'])
		{
			$this->data["keyvalue"]=$this->data["sessdata"]["userid"];
			$this->data["reqdata"] = array("password"=>$this->data["reqdata"]["newpassword"]);
			parent::Update();	
		}
		else
			parent::response();		
	}
	
	public function check_account_code($key)
	{
		$this->sql="SELECT count(`id`) `count` FROM `user` WHERE accountcode=$key";
		$data = parent::custom();
		if($data[0]["count"]==0)
			return true;
		else
			return false;
	}
	
	public function get_account_code($id)
	{
		$this->sql="SELECT accountcode FROM user WHERE id='".USERID."'";
		$acc_code=parent::custom();
		$accountcode=$acc_code[0]["accountcode"].'-'.$id;
		return $accountcode;
	}
	
    public function Logout()
	{
		unset($this->data["sessdata"]["userid"]);
		unset($this->data['session']);
		unset($this->data['module']);
		unset($this->data['action']);
		session_destroy();
		header("location:".HTTP_HOST."login.html");
	}
	
	public function CheckUser($userid)
	{
		
	}
	public function GetUser($userid)
	{
		$this->sql="select id,flag,level from user where idparent='".$userid."' order by level desc";
		$user=parent::custom();
		if(empty($user))
			return;
		else
		{
			if($count>0 && count($child)>0)
				continue;
			else
			{
				$count=0;
				$child=array();
			}	
			foreach($user as $val)
			{
				$child[$count++]=$val;
				if($val["level"]!=1)
					$this->GetUser($val["id"]);
			}	
		}
		return $child;
	}
	
	public function Block()
	{
		$id="";
		$this->sql="select flag from user where id='".$this->data["keyvalue"]."'";
		$data=parent::custom();
		if($data[0]["flag"]==1)
			$flag=array("flag"=>0);
		else
			$flag=array("flag"=>1);
		parent::RawUpdate("user",$flag,$this->data["keyvalue"],"id");
		if(LEVEL>1)
		{
			$user=$this->GetUser($this->data["keyvalue"]);
			foreach($user as $val)
			{
				$this->sql="select `flag`,`id` from sipuser where `iduser`='".$this->data["keyvalue"]."'";
				$data=parent::custom();
					foreach($data as $val)
						$id.=$val["id"].",";
				$this->sql="UPDATE `sipuser` SET `flag`=".$flag." WHERE `id` IN (".substr($id,0,-1).")";
				parent::custom();
			}
		}
		else
		{
			$this->sql="select `flag`,`id` from sipuser where `iduser`='".$this->data["keyvalue"]."'";
			$data=parent::custom();
			foreach($data as $val)
				$id.=$val["id"].",";
			$this->sql="UPDATE `sipuser` SET `flag`=".$flag." WHERE `id` IN (".substr($id,0,-1).")";
			parent::custom();
		}
		$this->response();
	}
	public function get_unicode()
	{
		$status=false;
		while(!$status)
		{
			//$unicode = substr(md5(microtime()),rand(0,26),7);
			$char = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 3)), 0, 3);
			$num=rand(1000,9999);
			$unicode=$char.$num;
			$status=$this->check_unicode($unicode);
		}
		return $unicode;	
	}
	
	public function check_unicode($key)
	{
		$this->sql="SELECT count(*) as `count` FROM `user` WHERE `unicode`='$key'";
		$count=parent::custom();
		if($count[0]["count"]==0)
			return true;
		else
			return false;
	}
	public function GetCurrentCall($template)
	{
		if(LEVEL==6)
			$this->sql="SELECT count(*) as `count` from current_call";
		else	
			$this->sql="SELECT count(*) as `count` from current_call where r".LEVEL."=".USERID."";
		$call=parent::custom();	
		parent::RawListView($template);
		$this->view->assign("calls", $call[0]['count']);
	}	
}
?>