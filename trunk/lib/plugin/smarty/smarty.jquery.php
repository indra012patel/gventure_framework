<?php
function smarty_block_gventure_jquery($params, $content, &$smarty, &$repeat)
{
    if(!$repeat){
        if (isset($content)) {
            $lang = $params['lang'];
			$str="<script language=\"javascript\" type=\"text/javascript\">\n";
			$str.="\t$(document).ready(function() {\n";
			$str.=$content;
			$str.="\t});\n";
			$str.="</script>";
            return $str;
        }
    }
}

function smarty_function_jquery_checkuser($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["username"]))
		$username=$params["username"];
	if(isset($params["minimum_character"]))
		$minimum_character=$params["minimum_character"];
	if(isset($params["message"]))
		$message=$params["message"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["type"]))
		$type=$params["type"];
		
	$html="\$('#".$id."').keyup(function(){ \n";
	//$html.="alert(\$('#".$id."').val().length);\n";
	$html.="if(\$('#".$id."').val().length>".$minimum_character."){\n";
    $html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	$str="";
	if(isset($session))
		$str.="session=".$session;
	if(isset($module))
		$str.="&module=".$module;
	if(isset($action))
		$str.="&action=".$action;
	if(strlen($str)>0)
		$html.="\t\t\tdata: \"".$str."&username=\"+\$('#".$id."').val(),\n";
	else
		$html.="\t\t\tdata: \"username=\"+\$('#".$id."').val(),\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
	$html.="\t\t\t\t\$(\"#".$message."\").text(data).show(500);\n";
	$html.="\t\t\t},\n\n";
	$html.="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
	$html.="\t\t\t\t\t\$(\"#".$message."\").text(\"username is not availabile\")\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t})\n";
	$html.="\t\treturn false;\n";
	$html.="\t} else \n";
	$html.="\t\t\t\t\t\$(\"#".$message."\").text(\"username is not availabile\")\n";
	$html.="\t}).keydown(function(event) {\n
			\t\tif (event.which == 13) {\n
				\t\t\tevent.preventDefault();\n
			\t\t}  \n
		\t});\n";
	
	return $html;
}

function smarty_function_jquery_datetime($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["format"]))
		$format=$params["format"];
	if(isset($params["setdate"]))
		$setdate=$params["setdate"];
	if(isset($params["class"]))
		$class=$params["class"];
		
	$html="\t\$(function(){ \n";
	if(isset($class))
	{
		//change for dynamically populated elements of html with date format
		$html.="\t\t\t\$('body').on('focus',\".".$class."\",function(){\n";
		$html.="\t\t\t\$(this).datepicker({\n\t\t\t\tchangeMonth: true,\n\t\t\t\tchangeYear: true,\n\t\t\tdateFormat:'".$format."'});});\n";
	}
	else
		$html.="\t\t\t\$(\"#".$id."\" ).datepicker({\n\t\t\t\tchangeMonth: true,\n\t\t\t\tchangeYear: true\n\t\t\t});\n"; 
	if(isset($format))
		$html.="\t\t\t\$(\"#format\" ).change(function() {\n\t\t\t\t\$(\"#".$class."\" ).datepicker(\"option\",\"dateFormat\", '".$format."' );\n\t\t\t});\n";
	if(isset($setdate))
		$html.="\t\t\t\$(\"#".$id."\" ).datepicker(\"setDate\" , ".$setdate." )\n";
	$html.="\t\t});\n";
	
	return $html;
}


function smarty_function_datepick($params, &$smarty)
{
 if(isset($params["id"]))
  $id=$params["id"];
 if(isset($params["format"]))
  $format=$params["format"];
 if(isset($params["mintime"]))
  $mintime=$params["mintime"];
 if(isset($params["maxtime"]))
  $maxtime=$params["maxtime"];
 if(isset($params["rangeselect"]))
  $rangeselect=$params["rangeselect"];
 if(isset($params["monthtoshow"]))
  $months=$params["monthtoshow"];
 if(isset($params["separator"]))
  $separator=$params["separator"];
 if(isset($params["class"]))
		$class=$params["class"];
		
	$html="\t\$(function(){ \n";
	/* if(isset($class))
	{
		$html.="\t\t\t\$('body').on('focus',\".".$class."\",function(){\n";
		$html.="\t\t\t\$(this).datepicker({\n";
	}
	else */
	$html.="\t\t\t$('#".$id."').datepick({\n";
 if(isset($rangeselect))
  $html.="\t\t\trangeSelect: ".$rangeselect.", \n";
 if(isset($months))
  $html.="\t\t\tmonthsToShow: ".$months.", \n";
 if(isset($mintime))
  $html.="\t\t\tminDate: '".$mintime."', \n";
 if(isset($maxtime))
  $html.="\t\t\tmaxDate: '".$maxtime."',\n";
 if(isset($format))
  $html.="\t\t\tdateFormat: '".$format."', \n";
 if(isset($separator))
  $html.="\t\t\trangeSeparator: ' ".$separator." ', \n";
 $html.="\t\t\t})\n";
 $html.="\t\t});\n";
 return $html;
}

function smarty_function_buttongridview($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["timeout"]))
		$timeout=$params["timeout"];
	if(isset($params["attribute"]))
		$attribute=$params["attribute"];
	
	$html="\t\t\t\$('#".$id."').click(function() {\n";
	$html.="\t\t\t\twindow.open($('#".$id."').attr(\"".$attribute."\"));\n";
	$html.="\t\t\t\tvar t=setTimeout(window.location.reload(),".$timeout.");\n";
	$html.="\t\t\t});\n";
	return $html;
}

function smarty_function_checkboxtotext($params, &$smarty)
{
	if(isset($params["source"]))
		$source=$params["source"];
	if(isset($params["target"]))
		$target=$params["target"];
	$html="\t\t\t\$('.".$source."').change(function() {\n";
	$html.="\t\t\t\tvar values = $('.".$source.":checked').map(function() {\n";
	$html.="\t\t\t\treturn this.value;\n";
	$html.="\t\t\t}).get().join('');\n";
	$html.="\t\t\t$('.".$target."').val(values);\n";
	$html.="\t\t\t})\n";
	return $html;
}
function smarty_function_dropdownhide($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["num"]))
	$num=$params["num"];
	$html="\t\t\t\$('.".$id."').blur(function() {\n";
	$html.="\t\t\t\tvar name = this.options[this.selectedIndex].value;\n";
	//$html.="\t\t\t\tif(name==0){\n";
	//$html.="\t\t\t\t	alert(\"plese select one value.\");\n";
	//$html.="\t\t\t\t}\n";
	$html.="\t\t\t\t if(name==".$num."){\n";
	$html.="\t\t\t\t$('.".$id." option[value=".$num."]').attr('disabled', true);\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t\t})\n";
	return $html;
}

function smarty_function_jquerydelete($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["message"]))
		$message=$params["message"];
	$html="\t\t\t\$('.".$id."').click(function(){\n";
    $html.="\t\t\t\tif (!confirm(\"".$message."\")){\n";
    $html.="\t\t\t\treturn false;\n";
    $html.="\t\t\t\t}\n";
	$html.="\t\t\t});\n";
	return $html;
}


function smarty_function_gventure_jmethod($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["class"]))
		$class=$params["class"];
	if(isset($params["form"]))
		$form=$params["form"];
	if(isset($params["div"]))
		$div=$params["div"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["type"]))
		$type=$params["type"];
	if(isset($params["autoclose"]))
		$autoclose=$params["autoclose"];
	if(isset($params["contentdiv"]))
		$contentdiv=$params["contentdiv"];
	if(isset($params["hidediv"]))
		$hidediv=$params["hidediv"];
	else
		$hidediv=true;
	
	if(isset($id))
		$html="$(\"#".$id."\").click(function() {\n";
	else
		$html="$(\".".$class."\").click(function() {\n";
	if(isset($class))
		$html.="\t\tvar key=\$(this).attr(\"rel\");\n";

	$html.="\t\t\$(\"#waiting\").show(500);\n";
	if(!$hidediv)
		$html.="\t\t\$(\"#".$div."\").hide(0);\n";
		
	$html.="\t\t\$(\"#message\").hide(0);\n\n";
	$html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	if(isset($form)){
		$html.="\t\t\tdata: $(\"#".$form."\").serialize()";
		if(isset($key))
			$html.="+\"&key=".encode($key)."\"";
		if(isset($data))
			$html.="+\"&".$data."\"";
		$html.=",\n";
	}
	else{
		if(isset($class))
			$html.="\t\t\tdata: \"session=".$session."&module=".encode($module)."&action=".encode($action)."&key=\"+key,\n";
		else
			$html.="\t\t\tdata: \"session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	}
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
	$html.="\t\t\t\t\$(\"#waiting\").hide(500);\n";
	switch($type)
	{
		case 'url_redirect':
			$html.="\t\t\t\twindow.location.href=(data);\n";
		break;
		case 'url_reload':
			$html.="\t\t\t\twindow.location.reload();\n";
		break;
		case 'data':
			$html.="\t\t\t\t\$(\"#".$div."\").html(data);\n";
		break;
		default:
			$html.="\t\t\t\t\$(\"#".$div."\").html(data);\n";
		break;
	}
	$html.="\t\t\t\t\$(\"#".$div."\").show(500);\n";
	
	if($autoclose)
		$html.="\t\t\t\$(\"#".$contentdiv."\").dialog(\"close\");\n";
		
	$html.="\t\t\t},\n\n";
	$html.="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
	$html.="\t\t\t\tif(XMLHttpRequest.status==404) {\n";
	$html.="\t\t\t\t\twindow.location.href=\"index.php\";\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t\t\$(\"#waiting\").hide(500);\n";
	$html.="\t\t\t\t\$(\"#message\").removeClass().addClass(\"error\")\n";
	$html.="\t\t\t\t.text('There was an error.').show(500);\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t})\n";
	$html.="\t\treturn false;\n";
	$html.="\t\t});\n";

	return $html;
}

function smarty_function_event($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["class"]))
		$class=$params["class"];
	if(isset($params["form"]))
		$form=$params["form"];
	if(isset($params["div"]))
		$div=$params["div"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["type"]))
		$type=$params["type"];
	if(isset($params["event"]))
		$event=$params["event"];
	if(isset($params["autoclose"]))
		$autoclose=$params["autoclose"];
	if(isset($params["contentdiv"]))
		$contentdiv=$params["contentdiv"];
	if(isset($params["timeout"]))
		$timeout=$params["timeout"];
		
	if(isset($params["hidden"]))
		$hidden=$params["hidden"];
	if(isset($params["hidediv"]))
		$hidediv=$params["hidediv"];
	if(isset($params["message"]))
		$message=$params["message"];	
	else
		$hidediv=true;
	
	if(isset($id))
		$html="$(\"#".$id."\").".$event."(function() {\n";
	else
		$html="$(\".".$class."\").".$event."(function() {\n";
	
	if(isset($class))
		$html.="\t\tvar key=\$(this).attr(\"rel\");\n";

	//It is used when form is post without submit button.
	if(!$hidden)
		$html.="\t\tvar data=$(\"#".$form."\").find(\"input,textarea,select,hidden\").not(\"[type=hidden]\").serialize();\n";
	
	$html.="\t\t\$(\"#waiting\").show(500);\n";
	if(!$hidediv)
		$html.="\t\t\$(\"#".$div."\").hide(0);\n";
		
	$html.="\t\t\$(\"#message\").hide(0);\n\n";
	$html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	if(isset($timeout))
	$html.="\t\t\ttimeout : $timeout,\n";
	if(isset($form)){
		
		if($hidden)
			$html.="\t\t\tdata: $(\"#".$form."\").serialize()";
		else
			$html.="\t\t\tdata: \"session=".$session."&module=".encode($module)."&action=".encode($action)."&\"+data";
		if(isset($key))
			$html.="+\"&key=".encode($key)."\"";
		if(isset($data))
			$html.="+\"&".$data."\"";
		$html.=",\n";
	}
	else{
		if(isset($class))
			$html.="\t\t\tdata: \"session=".$session."&module=".encode($module)."&action=".encode($action)."&key=\"+key,\n";
		else
			$html.="\t\t\tdata: \"session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	}
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
	$html.="\t\t\t\t\$(\"#waiting\").hide(500);\n";
	//$html.="\t\t\tvar response =data;\n";
	
	switch($type)
	{
		case 'url_redirect':
			$html.="\t\t\t\twindow.location.href=(data);\n";
		break;
		case 'url_reload':
			$html.="\t\t\t\twindow.location.reload();\n";
		break;
		case 'data':
			$html.="\t\t\t\t\$(\"#".$div."\").html(data);\n";
		break;
		case 'custom': //custom case to show custom actions
			$html.="\t\t\tif(response ==1){alert(\"".$message."\");}else{window.location.reload();};\n";
		break;
		default:
		{
			$html.="\t\t\t\tif($(\"#".$div."\").is(\"input\"))\n";
			$html.="\t\t\t\t\t\$(\"#".$div."\").val(data);\n";
			$html.="\t\t\t\t else\n";
			$html.="\t\t\t\t\t\$(\"#".$div."\").html(data);\n";

			break;
		}
	}
	$html.="\t\t\t\t\$(\"#".$div."\").show(500);\n";
	
	if($autoclose)
		$html.="\t\t\t\$(\"#".$contentdiv."\").dialog(\"close\");\n";
		
	$html.="\t\t\t},\n\n";
	$html.="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
	$html.="\t\t\t\tif(XMLHttpRequest.status==404) {\n";
	$html.="\t\t\t\t\twindow.location.href=\"index.php\";\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t\t\$(\"#waiting\").hide(500);\n";
	$html.="\t\t\t\t\$(\"#message\").removeClass().addClass(\"error\")\n";
	$html.="\t\t\t\t.text('There was an error.').show(500);\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t})\n";
	$html.="\t\treturn false;\n";
	$html.="\t\t});\n";

	return $html;
}
?>