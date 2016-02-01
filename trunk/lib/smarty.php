<?php
include_once("config/config.php");
include_once(SMARTY_DIR . 'Smarty.class.php');
include_once('recaptchalib.php');
include_once("smarty/smarty.gridview.php");
include_once("smarty/smarty.gridview2.php");
include_once("smarty/smarty.jquery.php");
include_once("smarty/smarty.input.php");
include_once("smarty/smarty.input2.php");
include_once("smarty/smarty.ajax.php");
include_once("smarty/smarty.sticker.php");
include_once("smarty/smarty.validation.php");
include_once("smarty/smarty.google.graph.php");
include_once("smarty/smarty.js.php");
include_once("common.lib.php");
//require_once('smarty/country.php');


class Gswitch_Smarty extends Smarty {
    function __construct($template, $config, $cache) {
      parent::__construct();
      $this->template_dir = APP_DIR.$template;
      $this->compile_dir = APP_DIR.TEMP_DIR;
      $this->config_dir = APP_DIR.$config;
      $this->cache_dir = APP_DIR.$cache;
    }
}

function smarty_function_seourl($params, &$smarty)
{
	if(isset($params["keyword1"]) && isset($params["keyword2"]))
		return HTTP_HOST.$params["keyword1"]."/".$params["keyword2"]."/".$params["module"]."/".$params["action"].".html";

	if(isset($params["keyword1"]))
		return HTTP_HOST.$params["keyword1"]."/".$params["module"]."/".$params["action"].".html";
	else
		return HTTP_HOST."/".$params["module"]."/".$params["action"].".html";
}

function smarty_function_encode($params, &$smarty)
{
	return urlencode(base64_encode(time().":".($params['string']).":".GV_URL_ENCODER_KEY));
}

function smarty_function_recaptcha($params, &$smarty)
{
	$error = null;
        return "<div class=\"captcha\">".recaptcha_get_html($params['publickey'], $error)."</div>";
}

function smarty_function_captcha($params, &$smarty)
{
	if(isset($params["name"]))
		$name=$params["name"];
	else
		$name="captcha";
	if(isset($params["id"]))
		$id=$params["id"];
	else
		$id="captcha-text";
	$html="<div class=\"captcha\"><img src=\"lib/captcha.php\" id=\"captcha\" /><br/> <a href=\"#\" onclick=\"document.getElementById('captcha').src='lib/captcha.php?'+Math.random(); document.getElementById('$id').focus();\" id=\"change-image\">Reload</a><br/><br/><input type=\"text\" name=\"$name\" id=\"$id\" /></div><br/>";
        return $html;
}

function smarty_function_captcha_ajax($params, &$smarty)
{
	if(isset($params["name"]))
                $name=$params["name"];
        else
                $name="captcha";
        if(isset($params["id"]))
                $id=$params["id"];
        else
                $id="captcha-text";
        $html="<div class=\"captcha\"><img src=\"lib/captcha.php\" id=\"captcha\" /><br/><a id=\"reload\" href=\"#\">Reload</a><br/><input type=\"text\" name=\"$name\" id=\"$id\" /></div><br/>";
        return $html;
}

function smarty_function_captcha_js($params, &$smarty)
{
	$html="\$('#reload').click(function() {\n";
	$html.="\t\t\t\$.ajax({\n";
        $html.="\t\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
        $html.="\t\t\t\t\tdocument.getElementById('captcha').src='lib/captcha.php?'+Math.random();\n";
        $html.="\t\t\t\t},\n";
        $html.="\t\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
	$html.="\t\t\t\t\t\$('#captcha').hide(0);\n";
	$html.="\t\t\t\t}\n";
	$html.="\t\t\t})\n";
	$html.="\t\treturn false;\n";
	$html.="\t});\n";
	return $html;
}


function smarty_block_gventure_detailview($params, $content, &$smarty, &$repeat)
{
        if(isset($params["name"]))
                $name=$params["name"];
        if(isset($params["css"]))
                $css=$params["css"];
        if(isset($params["label"]))
                $label=$params["label"];
        if(isset($params["cellspacing"]))
                $cellspacing=$params["cellspacing"];
        if(isset($params["cellpadding"]))
                $cellpadding=$params["cellpadding"];
        if(isset($params["data"]))
                $data=$params["data"];
        $html="<fieldset id=\"$name\">\n";
        if(isset($label))
                $html.="\t<legend>$label</legend>\n";
        if(isset($css))
                $html.="<table cellpadding=\"".$cellpadding."\" cellspacing=\"".$cellspacing."\" class=\"".$css."\" id=\"".$name."\" name=\"".$name."\" align=\"center\">";
        else
                $html.="<table cellpadding=\"".$cellpadding."\" cellspacing=\"".$cellspacing."\" id=\"".$name."\" name=\"".$name."\">";

	if(count($data)==0){
		$html.="<tr><td colspan=\"2\" align=\"center\">No Data Found</td></tr>";
        	$html.="</table>";
		return $html;
	}
	else
		$html.="<tbody><tr>";

        if(!$repeat){
        	if(isset($content)) {
			$tmp=explode("[",$content);
			$str="";
			foreach($tmp as $val)
			{
				$s=explode("]", $val);
				if(isset($s[1]))
					$str.=$data[$s[0]].$s[1]."&nbsp;";
				else
					$str.=$val;
				
			}
                        $html.= $str;
                }
        }
	$html.="</tr></tbody>";
        $html.="</table>";
        $html.="</fieldset>";
        return $html;
}

function smarty_function_pre($params, &$smarty)
{
        if(isset($params["header"]))
		$label=$params["header"];
        if(isset($params["label"]))
		$label=$params["label"];
        if(isset($params["value"]))
		$field=$params["value"];
        if(isset($params["field"]))
		$field=$params["field"];
        if(isset($params["datafield"]))
		$field=$params["datafield"];
	return "<tr><td>".$label."[".$field."]</td></tr>";
}

function smarty_function_column($params, &$smarty)
{
        if(isset($params["header"]))
		$label=$params["header"];
        if(isset($params["label"]))
		$label=$params["label"];
        if(isset($params["value"]))
		$field=$params["value"];
        if(isset($params["field"]))
		$field=$params["field"];
        if(isset($params["datafield"]))
		$field=$params["datafield"];
	    return "<tr><th>$label</th><td>[".$field."]</td></tr>";
}

?>
