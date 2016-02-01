<?php
function smarty_block_gventure_form($params, $content, &$smarty, &$repeat)
{
	if(isset($params["name"]))
		$name=$params["name"];
	if(isset($params["class"]))
		$class=$params["class"];
	if(isset($params["label"]))
		$label=$params["label"];
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["action"]))
		$action=$params["action"];
	if(isset($params["enctype"]))
		$enctype=$params["enctype"];
	if(isset($params["key"]))
		$key=$params["key"];
    // only output on the closing tag
	if($enctype)
		$str="<form id=\"$name\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	else
		$str.="<form id=\"$name\" action=\"index.php\" method=\"post\">\n";
		
	if(isset($session))
		$str.="<input name=\"session\" id=\"session\" type=\"hidden\" value=\"$session\"/>\n";
	if(isset($module))
		$str.="<input name=\"module\" id=\"module\" type=\"hidden\" value=\"$module\"/>\n";
	if(isset($action))
		$str.="<input name=\"action\" id=\"action\" type=\"hidden\" value=\"$action\"/>\n";
	if(isset($key))
		$str.="<input name=\"key\" id=\"key\" type=\"hidden\" value=\"$key\"/>\n";
    if(!$repeat){
        if (isset($content)) {
			$str.="<fieldset id=\"".$name."_".$session."\">\n";
			$str.="\t<legend>$label</legend>\n";
			$str.= $content;
			$str.="</fieldset>\n";
        }
    }
	$str.="</form>";
	return $str;
}


function smarty_function_gventure_input($params, &$smarty)
{
	if(isset($params["label"]))
		$label=$params["label"];
	if(isset($params["name"]))
		$name=$params["name"];
	if(isset($params["id"]))
		$fieldid=$params["id"];
	else
		$fieldid=$name;
	if(isset($params["inline"]))
		$fieldbr='';
	else
		$fieldbr='<br/>';
	if(isset($params["placeholder"]))                 
	$placeholder=$params["placeholder"];	
	if(isset($params["type"]))
		$type=strtolower($params["type"]);
	if(isset($params["value"]))
		$value=$params["value"];
	if(isset($params["check"]))
		$check=$params["check"];	
	if(isset($params["class"]))
		$class=$params["class"];
	if(isset($params["options"]))
		$option=$params["options"];
	if(isset($params["readonly"]))
		$readonly=$params["readonly"];
	if(isset($params["suffix"]))
		$suffix=$params["suffix"];
	if(isset($params["prefix"]))
		$prefix=$params["prefix"];
	if(isset($params["start"]))
		$start=$params["start"];
	if(isset($params["end"]))
		$end=$params["end"];
	if(isset($params["count"]))
		$end=$start+$params["count"];
	if(isset($params["step"]))
		$step=$params["step"];
	if(isset($params["help"]))
		$help=$params["help"];
	if(isset($params["checked"]))
		$checked=$params["checked"];
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["multiple"]))
		$multiple=$params["multiple"];	
	if(isset($params["onblur"]))
		$onblur=$params["onblur"];
	if(isset($params["style"]))
		$style=$params["style"];
	
	switch($type){
	
		case 'hidden':
		{
			$html.="<input type=\"hidden\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\">".$fieldbr."\n";
			break;
		}
		case 'text':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly)
				$html.="<input type=\"text\" name=\"".$name."\" id=\"".$fieldid."\" class=\"".$check."\" value=\"".$value."\" readonly=\"true\" placeholder=\"".$placeholder."\" style=\"".$style."\">".$fieldbr."\n";
			else
				$html.="<input type=\"text\" name=\"".$name."\" id=\"".$fieldid."\"class=\"".$check."\" onblur=\"".$onblur."\" value=\"".$value."\" placeholder=\"".$placeholder."\" style=\"".$style."\">".$fieldbr."\n";
			break;
		}
		case 'number':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly)
				$html.="<input type=\"number\" name=\"".$name."\" id=\"".$fieldid."\" class=\"".$check."\" value=\"".$value."\" readonly=\"true\" placeholder=\"".$placeholder."\" style=\"".$style."\">".$fieldbr."\n";
			else
				$html.="<input type=\"number\" name=\"".$name."\" id=\"".$fieldid."\"class=\"".$check."\" onblur=\"".$onblur."\" value=\"".$value."\" placeholder=\"".$placeholder."\" style=\"".$style."\">".$fieldbr."\n";
			break;
		}
		
		case 'label':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}
				$html.="<span>".$value."</span>".$fieldbr;
			break;
		}
		case 'file':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			$html.="<input type=\"file\" name=\"".$name."\" id=\"".$fieldid."\">".$fieldbr."\n";
			break;
		}
		case 'email':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly)
				$html.="<input type=\"text\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" readonly=\"true\">".$fieldbr."\n";
			else
				$html.="<input type=\"text\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" />".$fieldbr."\n";
			break;
		}
		case 'phone':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly)
				$html.="<input type=\"text\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" readonly=\"true\" />".$fieldbr."\n";
			else
				$html.="<input type=\"text\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" />".$fieldbr."\n";
			break;
		}
		case 'password':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly)
				$html.="<input type=\"password\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" readonly=\"true\"  class=\"".$class."\" />".$fieldbr."\n";
			else
				$html.="<input type=\"password\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" class=\"".$class."\" />".$fieldbr."\n";
			break;
		}
		case 'multiline':
		{}
		case 'textarea':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly)
				$html.="<textarea name=\"".$name."\" id=\"".$fieldid."\" readonly style=\"".$style."\">".$value."</textarea>".$fieldbr."\n";
			else
				$html.="<textarea name=\"".$name."\" id=\"".$fieldid."\"placeholder=\"".$placeholder."\" style=\"".$style."\">".$value."</textarea>".$fieldbr."\n";/*add placeholder attrbute for text area*/
			break;
		}
		case 'multioption':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}
			
			$opt=explode("|", $option);

			if($readonly)
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" disabled=\"disabled\"  style=\"".$style."\">\n";
			else
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" style=\"".$style."\">\n";
			$html.="\t<option value=\"-1\">Select One</option>\n";
			foreach($opt as $val)
			{
				if($value==$val)
					$html.="\t<option value=\"".$val."\" selected>".$val."</option>\n";
				else
					$html.="\t<option value=\"".$val."\">".$val."</option>\n";
			}
			$html.="</select>".$fieldbr."";
			break;
		}
		case 'multiopt':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}
			
			$opt=explode("|", $option);

			if($readonly)
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" disabled=\"disabled\" style=\"".$style."\">\n";
			else
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" style=\"".$style."\">\n";
			$html.="\t<option value=\"-1\">Select One</option>\n";
			foreach($opt as $val)
			{
				$tmp=explode("-", $val);
				if($value==$tmp[0])
					$html.="\t<option value=\"".$tmp[0]."\" selected>".$tmp[1]."</option>\n";
				else
					$html.="\t<option value=\"".$tmp[0]."\">".$tmp[1]."</option>\n";
			}
			$html.="</select>".$fieldbr."";
			break;
		}
		case 'checkbox':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly){
				if($checked)
					$html.="<input type=\"checkbox\" name=\"".$name."\" id=\"".$fieldid."\" class=\"".$check."\"  value=\"".$value."\" readonly checked style=\"".$style."\"/>".$fieldbr."\n";
				else
					$html.="<input type=\"checkbox\" name=\"".$name."\" id=\"".$fieldid."\" class=\"".$check."\"  value=\"".$value."\" readonly style=\"".$style."\"/>".$fieldbr."\n";
			}
			else{
				if($checked)
					$html.="<input type=\"checkbox\" name=\"".$name."\" id=\"".$fieldid."\" class=\"".$check."\"  value=\"".$value."\" checked style=\"".$style."\"/>".$fieldbr."\n";
				else
					$html.="<input type=\"checkbox\" name=\"".$name."\" id=\"".$fieldid."\" class=\"".$check."\"  value=\"".$value."\" style=\"".$style."\"/>".$fieldbr."\n";
			}
			break;
		}
		case 'yesno':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if(isset($option)){
				if($value==$option[0])
					$html.="Yes<input type=\"radio\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$option[0]."\" checked style=\"".$style."\"/>";
				else
					$html.="Yes<input type=\"radio\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$option[0]."\" style=\"".$style."\"/>";
				if($value==$option[1])
					$html.="No<input type=\"radio\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$option[1]."\" checked style=\"".$style."\"/>".$fieldbr."\n";
				else
					$html.="No<input type=\"radio\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$option[1]."\" style=\"".$style."\"/>".$fieldbr."\n";
			}
			else{
				if(strtolower($value)=="yes")
					$html.="Yes<input type=\"radio\" name=\"".$name."\" id=\"".$fieldid."\" value=\"yes\" checked style=\"".$style."\"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				else
					$html.="Yes<input type=\"radio\" name=\"".$name."\" id=\"".$fieldid."\" value=\"yes\" style=\"".$style."\"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				if(strtolower($value)=="no")
					$html.="No<input type=\"radio\" name=\"".$name."\" id=\"".$fieldid."\" value=\"no\" checked/>".$fieldbr."\n";
				else
					$html.="No<input type=\"radio\" name=\"".$name."\" id=\"".$fieldid."\" value=\"no\" style=\"".$style."\"/>".$fieldbr."\n";
			}
			break;
		}
		case 'radio':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}
			$html.="<input type=\"radio\" name=\"".$name."\"";
			if(isset($id))
				$html.="id=\"".$id."\"";
			else
				$html.="id=\"".$name."\"";
			$html.="value=\"".$value."\"";
			if($readonly)
				$html.=" readonly";
			if($checked)
				$html.=" checked";
			$html.=" style=\"".$style."\"/>".$fieldbr."\n";
			break;
		}
		case 'select': 
		{ }
		case 'option':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly)
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" disabled=\"disabled\" style=\"".$style."\">\n";
			elseif($multiple)
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" class=\"select\" multiple=\"".$multiple."\" size=4 style=\"".$style."\">\n";
			elseif($class)
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" class=\"".$class."\" style=\"".$style."\">\n";
			else
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" class=\"select\" style=\"".$style."\">\n";
			$html.="\t<option value=\"\">Select One</option>\n";
			foreach($option as $key=>$val)
			{
				if($value==$key)
					$html.="\t<option value=\"".$key."\" selected>".$val."</option>\n";
				else
					$html.="\t<option value=\"".$key."\">".$val."</option>\n";
			}
			$html.="</select>".$fieldbr."";
			break;
		}
		case 'optionnum':
		{
			if(isset($label)){
				$html="<label for=\"".$name."\" ";
				if(isset($help))
					$html.="title=\"".$help."\"";
				$html.=">".$label."</label>";
			}

			if($readonly)
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" disabled=\"disabled\" style=\"".$style."\">\n";
			else
				$html.="<select name=\"".$name."\" id=\"".$fieldid."\" style=\"".$style."\">\n";
			if(isset($start) && isset($end) && isset($step)){
				$html.="\t<option value=\"-1\">Select One</option>\n";
				for($i=$start; $i<=$end; $i+=$step)
				{
					if($value==$i){
						if(isset($prefix)){
							$html.="\t<option value=\"".$i."\" selected>".$prefix." ".$i."</option>\n";
							continue;
						}
						if(isset($suffix)){
							$html.="\t<option value=\"".$i."\" selected>".$i." ".$suffix."</option>\n";
							continue;
						}
						$html.="\t<option value=\"".$i."\" selected>".$i."</option>\n";
					}
					else{
						if(isset($prefix)){
							$html.="\t<option value=\"".$i."\">".$prefix." ".$i."</option>\n";
							continue;
						}
						if(isset($suffix)){
							$html.="\t<option value=\"".$i."\">".$i." ".$suffix."</option>\n";
							continue;
						}
						$html.="\t<option value=\"".$i."\">".$i."</option>\n";
					}
				}
			}
			else if(isset($start) && isset($end)){
				$html.="\t<option value=\"-1\">Select One</option>\n";
				for($i=$start; $i<=$end; $i++)
				{
					if($value==$i){
						if(isset($prefix)){
							$html.="\t<option value=\"".$i."\" selected>".$prefix." ".$i."</option>\n";
							continue;
						}
						if(isset($suffix)){
							$html.="\t<option value=\"".$i."\" selected>".$i." ".$suffix."</option>\n";
							continue;
						}
						$html.="\t<option value=\"".$i."\" selected>".$i."</option>\n";
					}
					else{
						if(isset($prefix)){
							$html.="\t<option value=\"".$i."\">".$prefix." ".$i."</option>\n";
							continue;
						}
						if(isset($suffix)){
							$html.="\t<option value=\"".$i."\">".$i." ".$suffix."</option>\n";
							continue;
						}
						$html.="\t<option value=\"".$i."\">".$i."</option>\n";
					}
				}
			}
			$html.="</select>".$fieldbr."";
			break;
		}

		case 'submit':
		{
			if($readonly)
				$html="<input type=\"submit\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" readonly=\"true\" style=\"".$style."\">".$fieldbr."\n";
			else
				$html="<input type=\"submit\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" onsubmit=\"".$check."\" style=\"".$style."\">".$fieldbr."\n";
			break;
		}
		
		case 'button':
		{
			if($readonly)
				$html="<input type=\"button\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" readonly=\"true\" style=\"".$style."\">".$fieldbr."\n";
			else
				$html="<input type=\"button\" name=\"".$name."\" id=\"".$fieldid."\" value=\"".$value."\" style=\"".$style."\">".$fieldbr."\n";
			break;
		}
	}
	
	return $html;
}

?>