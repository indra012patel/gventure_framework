<?php
function smarty_function_URL($params, &$smarty)
{
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["hiddenkey"]))
		$hiddenkey=$params["hiddenkey"];	
	if(isset($params["label"]))
		$label=$params["label"];
	if(isset($params["roles"]))
		$roles=$params["roles"];
	if(isset($params["popup"]))
		$popup=$params["popup"];
	if(isset($params["width"]))
		$width=$params["width"];
	if(isset($params["height"]))
		$height=$params["height"];
	if(isset($params["class"]))
		$class=$params["class"];
	if(isset($params["ch"]))
		$ch=$params["ch"];	
	if(isset($params["tooltip"]))
		$tooltip=$params["tooltip"];	
	
	if(roles_HREFURL($roles, $module, $action))
	{
		if(isset($popup))
			$str.="<a class=\"".$class."\"  href=\"#\" onClick=\"javascript:window.open('index.php?session=$session&module=".encode($module)."&action=".encode($action)."&key=".encode($hiddenkey)."', 'pop1win', 'toolbar=no scrollbars=yes,width=".$width.",height=".$height."')\">".$label."</a>";
		else if(isset($tooltip))
			$str.="<a class=\"$class\" data-rel=\"tooltip\" data-original-title=\"".$tooltip."\" href=\"index.php?session=$session&module=".encode($module)."&action=".encode($action)."&key=".encode($hiddenkey)."&ch=".encode($ch)."\">".$label."</a>";
		else	
			$str.="<a class=\"$class\" href=\"index.php?session=$session&module=".encode($module)."&action=".encode($action)."&key=".encode($hiddenkey)."&ch=".encode($ch)."\">".$label."</a>";
	}
	
	return $str;
}

function smarty_function_gventure_valid($params, &$smarty)
{
	if(isset($params["name"]))
		$name=$params["name"];
	if(isset($params["label"]))
		$label=$params["label"];
	if(isset($params["type"]))
		$type=strtolower($params["type"]);
	if(isset($params["rule"]))
		$rule=strtolower($params["rule"]);
	if(isset($params["length"]))
		$min=$params["length"];
	if(isset($params["maxlength"]))
		$max=$params["maxlength"];	
	if(isset($params["field"]))
		$field=$params["field"];

	$error=$params["error"];
	$low_valid=$params["min_valid"];
	$high_valid=$params["max_valid"];
	$high=$params["max_valid"];
	$low=$params["min_valid"];

	$str="";

	if($type=="text"){
		$str.="rule:\t\t\t$name : {\n";
		if(strtolower($rule)=="required")
			$str.="\t\t\t\t\trequired: true,\n";
		else
			$str.="\t\t\t\t\trequired: false,\n";
		if(isset($min))
			$str.="\t\t\t\t\tminlength:  ".$min.",\n";
		if(isset($max))	
			$str.="\t\t\t\t\tmaxlength:  ".$max."\n";
		$str.="\t\t\t\t},\n&&";

		$str.="\t\t\t\t$name: {\n";
		if(strtolower($rule)=="required"){
			if(strlen($error)>0)
				$str.="\t\t\t\t\trequired: \"$error\",\n";
			else
				$str.="\t\t\t\t\trequired: \"The $label is required field, please enter valid value\",\n";
		}

		if(strlen($valid)>0)
		{
			if(isset($min))
				$str.="\t\t\t\t\tminlength: \"$valid\",\n";
			if(isset($max))	
				$str.="\t\t\t\t\tmaxlength: \"$valid\"\n";
		}	
		else
		{
			if(isset($min))
				$str.="\t\t\t\t\tminlength: \"Please enter $label with minimum character $min\",\n";
			if(isset($max))	
				$str.="\t\t\t\t\tmaxlength: \"Please enter $label with maximum character $max\"\n";
			$str.="\t\t\t\t},";
		}
	}
	else if($type=="email"){
		$str.="rule:\t\t\t$name : {\n";
		$str.="\t\t\t\t\temail: true,\n";
		if(strtolower($rule)=="required")
			$str.="\t\t\t\t\trequired: true,\n";
		else
			$str.="\t\t\t\t\trequired: false,\n";
		$str.="\t\t\t\t},\n&&";

		$str.="\t\t\t\t$name: {\n";
		if(strtolower($rule)=="required"){
			if(strlen($error)>0)
				$str.="\t\t\t\t\trequired: \"$error\",\n";
			else
				$str.="\t\t\t\t\trequired: \"The $name is required field, please enter valid value\",\n";
		}

		if(strlen($valid)>0)
			$str.="\t\t\t\t\temail: \"$valid\"\n";
		else
			$str.="\t\t\t\t\temail: \"Please enter valid $name with proper email format\"\n";
		$str.="\t\t\t\t},";
	}
	else if($type=="number"){
		$str.="rule:\t\t\t$name : {\n";
		$str.="\t\t\t\t\tnumber: true,\n";
		if(strtolower($rule)=="required")
			$str.="\t\t\t\t\trequired: true,\n";
		else
			$str.="\t\t\t\t\trequired: false,\n";
		if(isset($low))
			$str.="\t\t\t\t\tmin:  ".$low.",\n";
		if(isset($high))
			$str.="\t\t\t\t\tmax:  ".$high."\n";
		$str.="\t\t\t\t},\n&&";

		$str.="\t\t\t\t$name: {\n";
		if(strtolower($rule)=="required"){
			if(strlen($error)>0)
				$str.="\t\t\t\t\trequired: \"$error\",\n";
			else
				$str.="\t\t\t\t\trequired: \"The $name is required field, please enter valid value\",\n";
		}

		if(strlen($low_valid)>0)
			$str.="\t\t\t\t\tmin: \"$low_valid\"\n";
		else
			$str.="\t\t\t\t\tmin: \"Entered value in $name is less then $low\",\n";
		if(strlen($high_valid)<0)
			$str.="\t\t\t\t\tmax: \"$high_valid\"\n";
		else
			$str.="\t\t\t\t\tmax: \"Entered value in $name is greater then $high\"\n";
		$str.="\t\t\t\t},";
	}
	else if($type=="digits"){
		$str.="rule:\t\t\t$name : {\n";
		$str.="\t\t\t\t\tdigits: true,\n";
		if(strtolower($rule)=="required")
			$str.="\t\t\t\t\trequired: true,\n";
		else
			$str.="\t\t\t\t\trequired: false,\n";
		if(isset($low))
			$str.="\t\t\t\t\tmin:  ".$low.",\n";
		if(isset($high))
			$str.="\t\t\t\t\tmax:  ".$high."\n";
		$str.="\t\t\t\t},\n&&";

		$str.="\t\t\t\t$name: {\n";
		if(strtolower($rule)=="required"){
			if(strlen($error)>0)
				$str.="\t\t\t\t\trequired: \"$error\",\n";
			else
				$str.="\t\t\t\t\trequired: \"The $name is required field, please enter valid value\",\n";
		}

		if(strlen($low_valid)>0)
			$str.="\t\t\t\t\tmin: \"$low_valid\"\n";
		else
			$str.="\t\t\t\t\tmin: \"Entered value in $name is less then $low\",\n";
		if(strlen($high_valid)>0)
			$str.="\t\t\t\t\tmax: \"$high_valid\"\n";
		else
			$str.="\t\t\t\t\tmax: \"Entered value in $name is greater then $high\"\n";
		$str.="\t\t\t\t},";
	}
	else if($type=="equalto"){
		$str.="rule:\t\t\t$name : {\n";
		$str.="\t\t\t\t\trequired: true,\n";
		$str.="\t\t\t\t\tequalTo: \"#".$field."\"\n";
		$str.="\t\t\t\t},\n&&";

		$str.="\t\t\t\t$name: {\n";
		if(strlen($error)>0)
			$str.="\t\t\t\t\trequired: \"$error\",\n";
		else
			$str.="\t\t\t\t\trequired: \"The $name is required field, please enter valid value\",\n";
		if(strlen($valid)>0)
			$str.="\t\t\t\t\tequalto: \"$valid\"\n";
		else
			$str.="\t\t\t\t\tequalto: \"Please enter $name same as $field\"\n";
		$str.="\t\t\t\t},";
	}
	else if($type=="select"){
		$str.="rule:\t\t\t$name : {\n";
		if(strtolower($rule)=="required")
			$str.="\t\t\t\t\tselectcheck: true\n";
		else
			$str.="\t\t\t\t\trequired: false\n";
		$str.="\t\t\t\t},\n&&";
	

		$str.="\t\t\t\t$name: {\n";
		if(strlen($error)>0 || strlen($error)<0 )
			$str.="\t\t\t\t\trequired: \"$error\",\n";
		else
			$str.="\t\t\t\t\trequired: \"The $label is required field, please choose valid option\"\n";
		/* if(strlen($valid)>0)
			$str.="\t\t\t\t\trequired: \"$error\",\n";
		else
			$str.="\t\t\t\t\trequired: \"Please enter $name same as $field\",\n"; */
		$str.="\t\t\t\t},";
	}
	
	else if($type=="file"){
		$str.="rule:\t\t\t$name : {\n";
		$str.="\t\t\t\t\trequired: true\n";
		$str.="\t\t\t\t},\n&&";

		$str.="\t\t\t\t$name: {\n";
		if($_FILES["file"]["error"] > 0)
			$str.="\t\t\t\t\trequired: \"$error\",\n";
		else
			$str.="\t\t\t\t\trequired: \"The $name is required field, please enter valid value\",\n";
		if($_FILES["file"]["error"] > 0)
			$str.="\t\t\t\t\tequalto: \"$valid\"\n";
		else
			$str.="\t\t\t\t\tequalto: \"Please enter $name same as $field\"\n";
		$str.="\t\t\t\t},";
	}
	else if($type=="ipv4"){
		$str.="rule:\t\t\t$name : {\n";
		$str.="\t\t\t\t\tipv4: true,\n";
		if(strtolower($rule)=="required")
			$str.="\t\t\t\t\trequired: true,\n";
		else
			$str.="\t\t\t\t\trequired: false,\n";
		$str.="\t\t\t\t},\n&&";

		$str.="\t\t\t\t$name: {\n";
		if(strtolower($rule)=="required"){
			if(strlen($error)>0)
				$str.="\t\t\t\t\trequired: \"$error\",\n";
			else
				$str.="\t\t\t\t\trequired: \"The $label is required field, please enter valid ipv4 formate \",\n";
		}

		if(strlen($valid)>0)
			$str.="\t\t\t\t\tipv4: \"$valid\"\n";
		else
			$str.="\t\t\t\t\tipv4: \"Please enter valid $label with proper IP format\"\n";
		$str.="\t\t\t\t},";
	}

	return $str;
}

function smarty_function_gventure_validate($params, &$smarty)
{
	if(isset($params["name"]))
		$name=$params["name"];
	if(isset($params["type"]))
		$type=strtolower($params["type"]);
	if(isset($params["rule"]))
		$rule=$params["rule"];
	if(isset($params["length"]))
		$min=$params["length"];
	if(isset($params["required"]))
		$required=$params["required"];
	if(isset($params["minlength"]))
		$minlength=$params["minlength"];
	$str="\n<script type=\"text/javascript\">\n";
	$str.="\t$(document).ready(function() {\n";
	$str.="\t\t$(\"#form\").validate({\n";
	$str.="\t\t\trules: {\n";
	$count=0;
	foreach($name as $val)
	{
		if(strtolower($rule[$count])=="required"){
			$str.="\t\t\t\t$val : {\n";
			$str.="\t\t\t\t\trequired: true,\n";
			$str.="\t\t\t\t\tminlength:  ".$min[$count].",\n";
			$str.="\t\t\t\t},\n";
		}
		$count++;
	}
	$str.="\t\t\t\t},\n";
	$str.="\t\t\tmessages: {\n";
	$count=0;
	foreach($name as $val)
	{
		if(strtolower($rule[$count])=="required"){
			$str.="\t\t\t\t$val : {\n";
			$str.="\t\t\t\t\trequired: \"".$required[$count]."\",\n";
			$str.="\t\t\t\t\tminlength:  \"".$minlength[$count]."\",\n";
			$str.="\t\t\t\t},\n";
		}
		$count++;
	}
	$str.="\t\t\t},\n";
	$str.="\t\t\terrorElement: \"span\",\n";
	$str.="\t\t});\n";
	$str.="\t});\n";
	$str.="</script>";
	return $str;
}

function smarty_function_gventure_valid_file($params, &$smarty)
{
	$str="";
	$str.="\t\t\t\tfunction validation(thisform){ \n";
	$str.="\t\t\t\t	with(thisform) {\n";
	$str.="\t\t\t\tif(validateFileExtension(file, \"valid_msg\", \"image files are only allowed!\",
			  new Array(\"jpg\",\"pdf\",\"jpeg\",\"gif\",\"png\")) == false) {\n";
	$str.="\t\t\t\t	return false;\n";
	$str.="\t\t\t\t	}\n";
	$str.="\t\t\t\t	if(validateFileSize(file,1048576, \"valid_msg\", \"Document size should be less than 1MB !\")==false)\n";
	$str.="\t\t\t\t	 {\n";
	$str.="\t\t\t\t	return false;\n";
	$str.="\t\t\t}\n";
	$str.="\t\t\t\t	 }\n";
	$str.="\t\t\t\t	}";
	return $str;
}


?>