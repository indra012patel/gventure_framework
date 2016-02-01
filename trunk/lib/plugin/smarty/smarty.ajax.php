<?php
function smarty_function_ajax_opt_text($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["target"]))
		$target=$params["target"];
	if(isset($params["delegate"]))
		$delegate=$params["delegate"];
	if(isset($params["state"]))
		$state=$params["state"];	
	if(isset($params["field"]))
		$field=$params["field"];
	if(isset($params["class"]))
		$class=$params["class"];	
			
	if(isset($delegate))
		$html="\n\t$(document).on('change', '.".$class."', function()  {\n";
	else
		$html="\n\t\$(\"#".$id."\").change(function(){\n";
	if(isset($state))	
		$html.="\t\tvar state=$(this);\n";		
    $html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\tdataType : \"json\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	$html.="\t\t\tdata : \"option=\"+\$(this).val()+\"&session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
	//$html.="\t\t\t\tvar obj = jQuery.parseJSON(data);\n";
	//if(isset($field))
		//$html.="\t\t\t\t\$(\"#".$target."\").val(data.".$field.");\n";
	//else
	//	return "";
	if(isset($state))
	{
		//$html.="\t\t\t\t\$(state).parent().siblings().children('.".$target."').empty();\n";
		$html.="\t\t\t\t\$(state).parent().siblings().children('.".$target."').val(data);\n";
	}
	 else
	 {
		 $html.="\t\t\t\t\$(\"#".$target."\").empty();\n";
		 $html.="\t\t\t\t\$(\"#".$target."\").val(data);\n";
	 }	
	$html.="\t\t\t\t\$(\"#".$target."\").focus();\n";
	$html.="\t\t\t}\n";
	$html.="\t\t})\n";
	$html.="\t})\n";
	return $html;
}

function smarty_function_ajax_option($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["target"]))
		$target=$params["target"];
	if(isset($params["delegate"]))
		$delegate=$params["delegate"];
	if(isset($params["state"]))
		$state=$params["state"];
	if(isset($params["class"]))
		$class=$params["class"];
	
	if(isset($delegate))
		$html="\n\t$(document).on('change', '.".$class."', function()  {\n";
	else
		$html="\n\t\$(\"#".$id."\").change(function(){\n";
	if(isset($state))	
		$html.="\t\tvar state=$(this);\n";	
    $html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	$html.="\t\t\tdata : \"option=\"+\$(this).val()+\"&session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
    $html.="\t\t\t\tvar options = '<option value=\"-1\">Select One</option>';\n";
	$html.="\t\t\t\tvar result = \$.parseJSON(data);\n";
	$html.="\t\t\t\t\$.each(result, function(key, val) {\n";
	$html.="\t\t\t\t\toptions+='<option value=\"' + key + '\">' + val + '</option>';\n";
	$html.="\t\t\t\t});\n";
	if(isset($state))
	{
		$html.="\t\t\t\t\$(state).parent().siblings().children('.".$target."').empty();\n";
		$html.="\t\t\t\t\$(state).parent().siblings().children('.".$target."').append(options);\n";
	}
	 else
	 {
		 $html.="\t\t\t\t\$(\"#".$target."\").empty();\n";
		 $html.="\t\t\t\t\$(\"#".$target."\").append(options);\n";
	 }
	$html.="\t\t\t\t\$(\"#".$target."\").focus();\n";
	$html.="\t\t\t}\n";
	$html.="\t\t})\n";
	$html.="\t})\n";
	return $html;
}

function smarty_function_ajax_dataoption($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["target"]))
		$target=$params["target"];
	
	$fields=explode("|",$data);
			
	$html="\n\t\$(\"#".$id."\").change(function(){\n";
	$html.="\t\tvar str=\"\";\n";
	foreach($fields as $val)
		$html.="\t\tstr+=\"".$val."=\"+document.getElementById(\"".$val."\").value+\"&\";\n";
		
    $html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	$html.="\t\t\tdata : str+\"session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
    $html.="\t\t\t\tvar options = '<option value=\"-1\">Select One</option>';\n";
	$html.="\t\t\t\tvar result = \$.parseJSON(data);\n";
	$html.="\t\t\t\t\$.each(result, function(key, val) {\n";
	$html.="\t\t\t\t\toptions+='<option value=\"' + key + '\" cust_val=\"' + val + '\">' + val + '</option>';\n";
	$html.="\t\t\t\t});\n";
	$html.="\t\t\t\t\$(\"#".$target."\").empty();\n";
    $html.="\t\t\t\t\$(\"#".$target."\").append(options);\n";
	$html.="\t\t\t\t\$(\"#".$target."\").focus();\n";
	$html.="\t\t\t}\n";
	$html.="\t\t})\n";
	$html.="\t})\n";
	return $html;
}
function smarty_function_ajax_checkalready($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["target"]))
		$target=$params["target"];
			
	$html="\n\t\$(\"#".$id."\").change(function(){\n";
    $html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	$html.="\t\t\tdata : \"option=\"+\$(this).val()+\"&session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
    $html.="\t\t\t\tvar message='';\n";
	$html.="\t\t\t\tvar result = \$.parseJSON(data);\n";
	$html.="\t\t\t\t\$.each(result, function(key, val) {\n";
	$html.="\t\t\t\t\tmessage+='<label>' + val + '</label>';\n";
	$html.="\t\t\t\t});\n";
	$html.="\t\t\t\t\$(\"#".$target."\").empty();\n";
    $html.="\t\t\t\t\$(\"#".$target."\").append(message);\n";
	$html.="\t\t\t\t\$(\"#".$target."\").focus();\n";
	$html.="\t\t\t}\n";
	$html.="\t\t})\n";
	$html.="\t})\n";
	return $html;
}
function smarty_function_ajax_check($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["target"]))
		$target=$params["target"];
			
	$html="\n\t\$(\"#".$id."\").keyup(function(){\n";
    $html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	$html.="\t\t\tdata : \"option=\"+\$(this).val()+\"&session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
    $html.="\t\t\t\tvar message='';\n";
	$html.="\t\t\t\tvar result = \$.parseJSON(data);\n";
	$html.="\t\t\t\t\$.each(result, function(key, val) {\n";
	$html.="\t\t\t\t\tmessage+='<label>' + val + '</label>';\n";
	$html.="\t\t\t\t});\n";
	$html.="\t\t\t\t\$(\"#".$target."\").empty();\n";
    $html.="\t\t\t\t\$(\"#".$target."\").append(message);\n";
	$html.="\t\t\t\t\$(\"#".$target."\").focus();\n";
	$html.="\t\t\t}\n";
	$html.="\t\t})\n";
	$html.="\t})\n";
	return $html;
}
function smarty_function_ajax_optfilter($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["target"]))
		$target=$params["target"];
			
	$html="\n\t\$(\"#".$id."\").change(function(){\n";
    $html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	$html.="\t\t\tdata : \"option=\"+\$(this).val()+\"&session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
    $html.="\t\t\t\t\$(\"#".$target."\").empty();\n";
    $html.="\t\t\t\t\$(\"#".$target."\").append(data);\n";
	$html.="\t\t\t}\n";
	$html.="\t\t})\n";
	$html.="\t})\n";
	return $html;
}

function smarty_function_ajax_textfilter($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["key"]))
		$key=$params["key"];
	if(isset($params["target"]))
		$target=$params["target"];
			
	$html="\n\t\$(\"#".$id."\").keyup(function(){\n";
    $html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	$html.="\t\t\tdata : \"option=\"+\$(this).val()+\"&session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
    $html.="\t\t\t\t\$(\"#".$target."\").empty();\n";
    $html.="\t\t\t\t\$(\"#".$target."\").append(data);\n";
	$html.="\t\t\t}\n";
	$html.="\t\t})\n";
	$html.="\t})\n";
	return $html;
}

?>