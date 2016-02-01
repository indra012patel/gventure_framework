<?php
function smarty_block_gventure_js($params, $content, &$smarty, &$repeat)
{
	if(isset($params["name"]))
		$name=$params["name"];
    // only output on the closing tag
    if(!$repeat){
        if (isset($content)) {
			$tmp=explode("rule:",$content);
			foreach($tmp as $val)
			{
				$t=explode("&&", $val);
				$cont["rule"].=$t[0];
				$cont["message"].=$t[1];
			}
            $lang = $params['lang'];
			$str="<script language=\"javascript\" type=\"text/javascript\">\n";
			$str.="\t$(document).ready(function() {\n";
			$str.="\t\t$(\"#$name\").validate({\n";
			$str.="\t\t\trules: {\n";
			$str.= $cont["rule"];
			$str.="\t\t\t\t},\n";
			$str.="\t\t\tmessages: {\n";
			$str.= $cont["message"];
			$str.="\t\t\t},\n";
			$str.="\t\t\terrorElement: \"span\",\n\t\t\t\tfocusInvalid: true,\n";
			$str.="\t\t});\n";
			$str.="\tjQuery.validator.addMethod('selectcheck', function (value) {\n";
			$str.="\t\treturn (value != '-1');\n";
			$str.="\t\t}, \"This field is required\");";
			$str.="\t});\n";
			$str.="</script>";

            // do some intelligent translation thing here with $content
            return $str;
        }
    }
}

function smarty_block_gventure_javascript($params, $content, &$smarty, &$repeat)
{
	if(isset($params["name"]))
		$name=$params["name"];
    // only output on the closing tag
    if(!$repeat){
        if (isset($content)) {
			$str="<script language=\"javascript\" type=\"text/javascript\">\n";
			$str.= $content;
			$str.="</script>";
            return $str;
        }
    }
}

function smarty_function_autorefresh($params, &$smarty)
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
	if(isset($params["timer"]))
		$timer=$params["timer"];
		
	if(isset($params["hidediv"]))
		$hidediv=$params["hidediv"];
	else
		$hidediv=true;
	$html="setInterval(\"".$module.$action."()\",$timer);\n";
	$html.="\tfunction ".$module.$action."() {\n";
	if(isset($class))
		$html.="\t\tvar key=\$(this).attr(\"rel\");\n";

	$html.="\t\t\$(\"#waiting\").show(500);\n";
	if(!$hidediv)
		$html.="\t\t\$(\"#".$div."\").hide(0);\n";
		
	$html.="\t\t\$(\"#message\").hide(0);\n\n";
	$html.="\t\t\$.ajax({\n";
	$html.="\t\t\ttype : \"POST\",\n";
	$html.="\t\t\turl : \"index.php\",\n";
	if(isset($class))
		$html.="\t\t\tdata: \"session=".$session."&module=".encode($module)."&action=".encode($action)."&key=\"+key,\n";
	else
		$html.="\t\t\tdata: \"session=".$session."&module=".encode($module)."&action=".encode($action)."\",\n";
	$html.="\t\t\tcache: false,\n\n";
	$html.="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
	$html.="\t\t\t\t\$(\"#waiting\").hide(500);\n";
	$html.="\t\t\t\t\$(\"#".$div."\").html(data);\n";
	$html.="\t\t\t\t\$(\"#".$div."\").show(500);\n";
	
	$html.="\t\t\t},\n\n";
	$html.="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
	$html.="\t\t\t\tif(XMLHttpRequest.status==404) {\n";
	$html.="\t\t\t\t\twindow.location.href=\"index.php\";\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t\tif(XMLHttpRequest.status==408) {\n";
	$html.="\t\t\t\t\twindow.location.href=\"index.php\";\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t\t\$(\"#waiting\").hide(500);\n";
	$html.="\t\t\t\t\$(\"#message\").removeClass().addClass(\"error\")\n";
	$html.="\t\t\t\t.text('There was an error.').show(500);\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t})\n";
	$html.="\t\treturn false;\n";
	$html.="\t\t}\n";

	return $html;
}

?>