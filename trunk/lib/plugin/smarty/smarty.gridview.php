<?php
require_once("gridview.php");
function getExtension($str)
{
	 $i = strrpos($str,".");
	 if (!$i) { return ""; } 

	 $l = strlen($str) - $i;
	 $ext = substr($str,$i+1,$l);
	 return $ext;
} 
function smarty_block_gventure_gridview($params, $content, &$smarty, &$repeat)
{
	if(isset($params["name"]))
		$name=$params["name"];
	if(isset($params["id"]))
		$id=$params["id"];
	else
		$id=$name;
	if(isset($params["css"]))
		$css=$params["css"];
	
	$html="<table class=\"".$css."\" name=\"".$name."\" id=\"".$id."\">";
	
	if(!$repeat){
        if (isset($content)) {
			$html.= $content;
		}
	}
	$html.="</table>";
	return $html;
}

function smarty_function_gridview_header($params, &$smarty)
{
	$header=$params["header"];
	if(isset($params["css"]))
		$css=" class=\"".$params["css"]."\"";
	if(isset($params["roles"]))
		$roles=$params["roles"];
	if(isset($params["sno"]))
		$sno=$params["sno"];		
	
	$html="<thead>\n<tr".$css.">";
	if($sno)
		$html.="<th class=\"left\">S. No.</th>";	
	foreach($header as $key=>$val)
	{
		$key=strtolower($key);
		if($key=="import" || $key=="export" || $key=="add" || $key=="edit" || $key=="status" || $key=="delete" || $key=="approve" || $key=="download" || $key=="pdf" || $key=="flag" )
		{
			if(roles_URL($roles,$module, $key))
				$html.="<th>".$val."</th>\n";
		}
		else
			$html.="<th>".$val."</th>\n";
	}
	$html.="</tr>\n</thead>\n";

	return $html;
}

function smarty_function_gridview_ACLdata($params, &$smarty)
{
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["keyfield"]))
		$keyfield=$params["keyfield"];
	if(isset($params["field"]))
		$field=$params["field"];
	if(isset($params["counter"]))
		$counter=$params["counter"]+1;
	if(isset($params["limit"]))
		$limit=$params["limit"];
	if(isset($params["data"]))
		$data=$params["data"];
	
	if(isset($params["edit"]))
		$edit=$params["edit"];
	if(isset($params["editmethod"]))
		$editmethod=$params["editmethod"];
	if(isset($params["view"]))
		$view=$params["view"];		
	if(isset($params["viewmethod"]))
		$viewmethod=$params["viewmethod"];
	if(isset($params["viewmethod1"]))
		$viewmethod1=$params["viewmethod1"];
	if(isset($params["viewmethod2"]))
		$viewmethod2=$params["viewmethod2"];
	if(isset($params["viewmethod3"]))
		$viewmethod3=$params["viewmethod3"];	
	if(isset($params["delete"]))
		$delete=$params["delete"];
	if(isset($params["deletemethod"]))
		$deletemethod=$params["deletemethod"];
	if(isset($params["settingmethod"]))
		$settingmethod=$params["settingmethod"];	
	if(isset($params["approvemethod"]))
		$approvemethod=$params["approvemethod"];
	if(isset($params["import"]))
		$import=$params["import"];		
	if(isset($params["export"]))
		$export=$params["export"];		
	if(isset($params["addbutton"]))
		$addbutton=$params["addbutton"];		
	if(isset($params["method1img"]))
		$method1img=$params["method1img"];
	if(isset($params["method1label"]))
		$method1label=$params["method1label"];
		
	if(isset($params["rowcss"]))
		$rcss=$params["rowcss"];
	if(isset($params["altrowcss"]))
		$altcss=$params["altrowcss"];
	if(isset($params["datarows"]))
		$datarows=$params["datarows"];
	if(isset($params["datacols"]))
		$datacols=$params["datacols"];
	if(isset($params["idenable"]))
		$tridenable=$params["idenable"];
	if(isset($params["ajax"]))
		$ajax=$params["ajax"];
	if(isset($params["deleteajax"]))
		$deleteajax=$params["deleteajax"];
	if(isset($params["addmethod"]))
		$addmethod=$params["addmethod"];
	if(isset($params["inlineview"]))
		$inlineview=$params["inlineview"];
	if(isset($params["inline"]))
		$inline=$params["inline"];	
	if(isset($params["inlineedit"]))
		$inlineedit=$params["inlineedit"];	
	if(isset($params["href_field"]))
		$href_field=$params["href_field"];
	if(isset($params["href_action"]))
		$href_action=$params["href_action"];
	if(isset($params["href_module"]))
		$href_module=$params["href_module"];
	if(isset($params["href_key"]))
		$href_key=$params["href_key"];
	if(isset($params["sno"]))
		$sno=$params["sno"];
	if(isset($params["check"]))
		$check=$params["check"];
	if(isset($params["roles"]))
		$roles=$params["roles"];
	if(isset($params["flag"]))
		$flag=$params["flag"];
	if(isset($params["ch"]))
		$ch=$params["ch"];
	if(isset($params["audio"]))
		$audio=$params["audio"];
	if(isset($params["record"]))
		$record=$params["record"];
	if(isset($params["sno"]))
		$sno=$params["sno"];
		
	
	$_url="";
	if(isset($session))
		$_url.="session=".$session."&";
	if(isset($module))
		$_url.="module=".encode($module)."&";
		
	if($datarows==0)
		return ;//"<tbody><tr><td colspan=\"".$datacols."\" align=\"center\">No Data Found</td></tr>";
	else
	$html="<tbody>";	
	$td_idcount=1;
	
	for($i=0; $i<$limit; $i++)
	{
		$val=$data[$counter-1];
		if(count($val)==0)
			break;
		if(isset($rcss) && isset($altcss)){
			if($counter%2)
				$html.="<tr class=\"".$rcss."\">";
			else
				$html.="<tr class=\"".$altcss."\">";
		}

		if(isset($rcss)){
			if($tridenable)
				$html.="<tr id=".$td_idcount." class=\"".$rcss."\">";
			else
				$html.="<tr class=\"".$rcss."\">";
		}
		else{
			if($tridenable)
				$html.="<tr id=".$td_idcount.">";
			else
				$html.="<tr>";
		}
		if($sno)
			$html.="<td>".($counter++)."</td>";
		else	
			$counter++;	
		
		foreach($field as $fd)
		{
			if(strtolower($fd)=="flag")
			{
				if(isset($flag))
				{
					if(roles_URL($roles,$module, "flag"))
					{
						if($val[$fd]==1)
							$html.="<td class=\"center\"><a class=\"view\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($flag)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\"><span class=\"label label-success\">Active</span></td>";
						elseif($val[$fd]==2)
							$html.="<td class=\"center\"><a class=\"view1\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($flag)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\"><span class=\"label label-warning\">Pending</span></td>";
						else
							$html.="<td class=\"center\"><a class=\"view1\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($flag)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\"><span class=\"label label-important\">Blocked</span></td>";	
					}
				}
				
				continue;
			}
			else if(strtolower($fd)==$href_field)
				$html.="<td><a href=\"index.php?session=".$session."&module=".encode($href_module)."&action=".encode($href_action)."&key=".encode($val[$href_key])."&ch=".encode($ch)."\">".$val[$fd]."</a></td>";
			else if(strtolower($fd)==$audio)
				$html.="<td><audio controls><source src=\"".UPLOAD_DIR.$val[$fd]."\" type=\"audio/mp3\"></audio></td>";
			else if(strtolower($fd)==$record)
				$html.="<td><audio controls><source src=\"".RECORDING_DIR.$val[$fd]."\" type=\"audio/mp3\"></audio></td>";		
			else
				$html.="<td>".$val[$fd]."</td>";
		}
		if(isset($approvemethod) || isset($import) || isset($export) || isset($addbutton) || isset($viewmethod) || isset($editmethod) || isset($deletemethod))
		{
			$html.="<td class=\"center\">";
		}
		if(isset($approvemethod))
		{
			if(roles_URL($roles,$module, $approvemethod))
			{
			
				$html.="<a class=\"btn btn-success\" name=\"approve\" id=\"button_".$counter."\"href=\"index.php?".$_url."action=".encode($approvemethod)."&key=".encode($val[$keyfield])."\" title=\"Approve\"><i class=\"halflings-icon white ok\"></i></a>";
			}
		}
		if(isset($import))
		{
			if(roles_URL($roles,$module, $import))
			{
				$html.="<a class=\"btn btn-info\" name=\"import\" href=\"#\" onClick=\"javascript:window.open('index.php?session=".$session."&module=".encode($module)."&action=".encode($import). "&key=".encode($val[$keyfield]). "', 'pop1win', 'toolbar=no scrollbars=yes,width=700,height=400')\" title=\"Import\"><i class=\"halflings-icon white upload\"></i>  </a>";
			}
		}
		
		if(isset($export))
		{
			if(roles_URL($roles,$module, $export))
			{
				$html.="<a class=\"btn btn-info\" name=\"export\" href=\"index.php?".$_url."action=".encode($export)."&key=".encode($val[$keyfield])."\" title=\"Export\"><i class=\"halflings-icon white download\"></i> </a>";
			}
		}
		
		if(isset($addbutton))
		{
			if(roles_URL($roles,$module, $addbutton))
			{
				if(isset($params["popup"])) // add a variable to set popup model box
					$html.="<a class=\"btn btn-setting\" name=\"add\" href=\"#\" rel=\"".encode($val[$keyfield])."\" title=\"Add\"><i class=\"halflings-icon white usd\"></i></a>";
				else if(isset($params["modal"]))
					$str.="\t\t<a href=\"#\" class=\"btn-setting\" rel=\"".encode($val[$keyfield])."\" ><i class=\"halflings-icon wrench\"></i></a>\n";
				else
					$html.="<a class=\"btn btn-info\" name=\"add\" href=\"#\" onClick=\"javascript:window.open('index.php?session=".$session."&module=".encode($module)."&action=".encode($addbutton). "&key=".encode($val[$keyfield]). "', 'pop1win', 'toolbar=no scrollbars=yes,width=700,height=400')\" title=\"Add\"><i class=\"halflings-icon white usd\"></i></a>";
			}
		}
		
		if(isset($viewmethod))
		{
			if(roles_URL($roles,$module, $viewmethod))
			{
				if($inlineview)
					$html.="<a class=\"btn btn-success\" name=\"edit\" href=\"#\" rel=\"".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"View\"><i class=\"halflings-icon white zoom-in\"></i> </a>";
				else	
					$html.="<a class=\"btn btn-success\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"View\"><i class=\"halflings-icon white zoom-in\"></i> </a>";
			}
		}
		
		if(isset($viewmethod1))
		{
			if(roles_URL($roles,$module, $viewmethod1))
			{
				$html.="<a class=\"btn btn-info\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod1)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"View\"><i class=\"icon-bar-chart\"></i> </a>";
			}
		}
		
		if(isset($viewmethod2))
		{
			if(roles_URL($roles,$module, $viewmethod2))
			{	
				$html.="<a class=\"btn btn-danger\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod2)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Recording\"><i class=\"halflings-icon white volume-up\"></i> </a>";
			}
		}
		if(isset($viewmethod3))
		{
			if(roles_URL($roles,$module, $viewmethod3))
			{	
				$html.="<a class=\"btn btn-warning\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod3)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Transfer\"><i class=\" halflings-icon white share\"></i> </a>";
			}
		}
		//print_r($role);die;
		if(isset($editmethod))
		{
			if(roles_URL($roles,$module,$editmethod))
			{
				//print_r($role);die;
				if($inlineedit)
					$html.="<a class=\"btn btn-info\" name=\"edit\" href=\"#\" rel=\"".encode($val[$keyfield])."\" title=\"Edit\"><i class=\"halflings-icon white edit\"></i></a>";
				else
					$html.="<a class=\"btn btn-info\" name=\"edit\" href=\"index.php?".$_url."action=".encode($editmethod)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Edit\"><i class=\"halflings-icon white edit\"></i></a>";
			}
		}
		
		if(isset($settingmethod))
		{
			if(roles_URL($roles,$module, $settingmethod))
			{
				$html.="<a class=\"btn btn-warning\" name=\"Setting\" id=\"button_".$counter."\"href=\"index.php?".$_url."action=".encode($settingmethod)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Settings\"><i class=\"halflings-icon lock white trash\"></i></a>";
			}
		}
		
		if(isset($deletemethod))
		{
			if(roles_URL($roles,$module, $deletemethod))
			{
				$html.="<a class=\"btn btn-danger\" name=\"delete\" id=\"button_".$counter."\"href=\"index.php?".$_url."action=".encode($deletemethod)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Delete\"><i class=\"halflings-icon white trash\"></i></a>";
			}
		}
		
		if(isset($approvemethod) || isset($import) || isset($export) || isset($addbutton) || isset($viewmethod) || isset($editmethod) || isset($deletemethod))
		{
			$html.="</td>\n";
		}
		$td_idcount++;
	}
	$html.="</tr></tbody>\n";
	return $html;
}

function smarty_function_gridview_ACdata($params, &$smarty)
{
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["keyfield"]))
		$keyfield=$params["keyfield"];
	if(isset($params["field"]))
		$field=$params["field"];
	if(isset($params["counter"]))
		$counter=$params["counter"]+1;
	if(isset($params["limit"]))
		$limit=$params["limit"];
	if(isset($params["data"]))
		$data=$params["data"];
	
	if(isset($params["edit"]))
		$edit=$params["edit"];
	if(isset($params["editmethod"]))
		$editmethod=$params["editmethod"];
	if(isset($params["view"]))
		$view=$params["view"];		
	if(isset($params["viewmethod"]))
		$viewmethod=$params["viewmethod"];
	if(isset($params["viewmethod1"]))
		$viewmethod1=$params["viewmethod1"];
	if(isset($params["viewmethod2"]))
		$viewmethod2=$params["viewmethod2"];
	if(isset($params["viewmethod3"]))
		$viewmethod3=$params["viewmethod3"];	
	if(isset($params["delete"]))
		$delete=$params["delete"];
	if(isset($params["deletemethod"]))
		$deletemethod=$params["deletemethod"];
	if(isset($params["settingmethod"]))
		$settingmethod=$params["settingmethod"];	
	if(isset($params["approvemethod"]))
		$approvemethod=$params["approvemethod"];
	if(isset($params["import"]))
		$import=$params["import"];		
	if(isset($params["export"]))
		$export=$params["export"];		
	if(isset($params["addbutton"]))
		$addbutton=$params["addbutton"];		
	if(isset($params["method1img"]))
		$method1img=$params["method1img"];
	if(isset($params["method1label"]))
		$method1label=$params["method1label"];
		
	if(isset($params["rowcss"]))
		$rcss=$params["rowcss"];
	if(isset($params["altrowcss"]))
		$altcss=$params["altrowcss"];
	if(isset($params["datarows"]))
		$datarows=$params["datarows"];
	if(isset($params["datacols"]))
		$datacols=$params["datacols"];
	if(isset($params["idenable"]))
		$tridenable=$params["idenable"];
	if(isset($params["ajax"]))
		$ajax=$params["ajax"];
	if(isset($params["deleteajax"]))
		$deleteajax=$params["deleteajax"];
	if(isset($params["addmethod"]))
		$addmethod=$params["addmethod"];
	if(isset($params["inlineview"]))
		$inlineview=$params["inlineview"];
	if(isset($params["inline"]))
		$inline=$params["inline"];	
	if(isset($params["inlineedit"]))
		$inlineedit=$params["inlineedit"];	
	if(isset($params["href_field"]))
		$href_field=$params["href_field"];
	if(isset($params["href_action"]))
		$href_action=$params["href_action"];
	if(isset($params["href_module"]))
		$href_module=$params["href_module"];
	if(isset($params["href_key"]))
		$href_key=$params["href_key"];
	if(isset($params["sno"]))
		$sno=$params["sno"];
	if(isset($params["check"]))
		$check=$params["check"];
	if(isset($params["roles"]))
		$roles=$params["roles"];
	if(isset($params["flag"]))
		$flag=$params["flag"];
	if(isset($params["ch"]))
		$ch=$params["ch"];
	if(isset($params["audio"]))
		$audio=$params["audio"];
	if(isset($params["record"]))
		$record=$params["record"];
	if(isset($params["sno"]))
		$sno=$params["sno"];
		
	
	$_url="";
	if(isset($session))
		$_url.="session=".$session."&";
	if(isset($module))
		$_url.="module=".encode($module)."&";
		
	if($datarows==0)
		return ;//"<tbody><tr><td colspan=\"".$datacols."\" align=\"center\">No Data Found</td></tr>";
	else
	$html="<tbody>";	
	$td_idcount=1;
	
	for($i=0; $i<$limit; $i++)
	{
		$val=$data[$counter-1];
		if(count($val)==0)
			break;
		if(isset($rcss) && isset($altcss)){
			if($counter%2)
				$html.="<tr class=\"".$rcss."\">";
			else
				$html.="<tr class=\"".$altcss."\">";
		}

		if(isset($rcss)){
			if($tridenable)
				$html.="<tr id=".$td_idcount." class=\"".$rcss."\">";
			else
				$html.="<tr class=\"".$rcss."\">";
		}
		else{
			if($tridenable)
				$html.="<tr id=".$td_idcount.">";
			else
				$html.="<tr>";
		}
		if($sno)
			$html.="<td>".($counter++)."</td>";
		else	
			$counter++;	
		
		foreach($field as $fd)
		{
			if(strtolower($fd)=="flag")
			{
				if(isset($flag))
				{
					if(roles_URL($roles,$module, "flag"))
					{
						if($val[$fd]==1)
							$html.="<td class=\"center\"><a class=\"view\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($flag)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\"><span class=\"label label-success\">Active</span></td>";
						elseif($val[$fd]==2)
							$html.="<td class=\"center\"><a class=\"view1\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($flag)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\"><span class=\"label label-warning\">Pending</span></td>";
						else
							$html.="<td class=\"center\"><a class=\"view1\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($flag)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\"><span class=\"label label-important\">Blocked</span></td>";	
					}
				}
				
				continue;
			}
			else if(strtolower($fd)==$href_field)
				$html.="<td><a href=\"index.php?session=".$session."&module=".encode($href_module)."&action=".encode($href_action)."&key=".encode($val[$href_key])."\">".$val[$fd]."</a></td>";
			else if(strtolower($fd)==$audio)
				$html.="<td><audio controls><source src=\"".UPLOAD_DIR.$val[$fd]."\" type=\"audio/mp3\"></audio></td>";
			else if(strtolower($fd)==$record)
				$html.="<td><audio controls><source src=\"".RECORDING_DIR.$val[$fd]."\" type=\"audio/mp3\"></audio></td>";		
			else
				$html.="<td>".$val[$fd]."</td>";
		}
		if(isset($approvemethod) || isset($import) || isset($export) || isset($addbutton) || isset($viewmethod) || isset($editmethod) || isset($deletemethod))
		{
			$html.="<td class=\"center\">";
		}
		if(isset($approvemethod))
		{
			if(roles_URL($roles,$module, $approvemethod))
			{
			
				$html.="<a class=\"btn btn-success\" name=\"approve\" id=\"button_".$counter."\"href=\"index.php?".$_url."action=".encode($approvemethod)."&key=".encode($val[$keyfield])."\" title=\"Approve\"><i class=\"halflings-icon white ok\"></i></a>";
			}
		}
		if(isset($import))
		{
			if(roles_URL($roles,$module, $import))
			{
				$html.="<a class=\"btn btn-info\" name=\"import\" href=\"#\" onClick=\"javascript:window.open('index.php?session=".$session."&module=".encode($module)."&action=".encode($import). "&key=".encode($val[$keyfield]). "', 'pop1win', 'toolbar=no scrollbars=yes,width=700,height=400')\" title=\"Import\"><i class=\"halflings-icon white upload\"></i>  </a>";
			}
		}
		
		if(isset($export))
		{
			if(roles_URL($roles,$module, $export))
			{
				$html.="<a class=\"btn btn-info\" name=\"export\" href=\"index.php?".$_url."action=".encode($export)."&key=".encode($val[$keyfield])."\" title=\"Export\"><i class=\"halflings-icon white download\"></i> </a>";
			}
		}
		
		if(isset($addbutton))
		{
			if(roles_URL($roles,$module, $addbutton))
			{
				if(isset($params["popup"])) // add a variable to set popup model box
					$html.="<a class=\"btn btn-setting\" name=\"add\" href=\"#\" rel=\"".encode($val[$keyfield])."\" title=\"Add\"><i class=\"halflings-icon white usd\"></i></a>";
				else if(isset($params["modal"]))
					$str.="\t\t<a href=\"#\" class=\"btn-setting\" rel=\"".encode($val[$keyfield])."\" ><i class=\"halflings-icon wrench\"></i></a>\n";
				else
					$html.="<a class=\"btn btn-info\" name=\"add\" href=\"#\" onClick=\"javascript:window.open('index.php?session=".$session."&module=".encode($module)."&action=".encode($addbutton). "&key=".encode($val[$keyfield]). "', 'pop1win', 'toolbar=no scrollbars=yes,width=700,height=400')\" title=\"Add\"><i class=\"halflings-icon white usd\"></i></a>";
			}
		}
		
		if(isset($viewmethod))
		{
			if(roles_URL($roles,$module, $viewmethod))
			{
				if($inlineview)
					$html.="<a class=\"btn btn-success\" name=\"edit\" href=\"#\" rel=\"".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"View\"><i class=\"halflings-icon white zoom-in\"></i> </a>";
				else	
					$html.="<a class=\"btn btn-success\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"View\"><i class=\"halflings-icon white zoom-in\"></i> </a>";
			}
		}
		
		if(isset($viewmethod1))
		{
			if(roles_URL($roles,$module, $viewmethod1))
			{
				$html.="<a class=\"btn btn-info\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod1)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"View\"><i class=\"icon-bar-chart\"></i> </a>";
			}
		}
		
		if(isset($viewmethod2))
		{
			if(roles_URL($roles,$module, $viewmethod2))
			{	
				$html.="<a class=\"btn btn-danger\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod2)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Recording\"><i class=\"halflings-icon white volume-up\"></i> </a>";
			}
		}
		if(isset($viewmethod3))
		{
			if(roles_URL($roles,$module, $viewmethod3))
			{	
				$html.="<a class=\"btn btn-warning\" id=\"button_".$counter."\ name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod3)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Transfer\"><i class=\" halflings-icon white share\"></i> </a>";
			}
		}
		//print_r($role);die;
		if(isset($editmethod))
		{
			if(roles_URL($roles,$module,$editmethod))
			{
				//print_r($role);die;
				if($inlineedit)
					$html.="<a class=\"btn btn-info\" name=\"edit\" href=\"#\" rel=\"".encode($val[$keyfield])."\" title=\"Edit\"><i class=\"halflings-icon white edit\"></i></a>";
				else
					$html.="<a class=\"btn btn-info\" name=\"edit\" href=\"index.php?".$_url."action=".encode($editmethod)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Edit\"><i class=\"halflings-icon white edit\"></i></a>";
			}
		}
		
		if(isset($settingmethod))
		{
			if(roles_URL($roles,$module, $settingmethod))
			{
				$html.="<a class=\"btn btn-warning\" name=\"Setting\" id=\"button_".$counter."\"href=\"index.php?".$_url."action=".encode($settingmethod)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Settings\"><i class=\"halflings-icon lock white trash\"></i></a>";
			}
		}
		
		if(isset($deletemethod))
		{
			if(roles_URL($roles,$module, $deletemethod))
			{
				$html.="<a class=\"btn btn-danger\" name=\"delete\" id=\"button_".$counter."\"href=\"index.php?".$_url."action=".encode($deletemethod)."&key=".encode($val[$keyfield])."&ch=".encode($ch)."\" title=\"Delete\"><i class=\"halflings-icon white trash\"></i></a>";
			}
		}
		
		if(isset($approvemethod) || isset($import) || isset($export) || isset($addbutton) || isset($viewmethod) || isset($editmethod) || isset($deletemethod))
		{
			$html.="</td>\n";
		}
		$td_idcount++;
	}
	$html.="</tr></tbody>\n";
	return $html;
}


function smarty_function_gridview_data($params, &$smarty)
{
	if(isset($params["session"]))
		$session=$params["session"];
	if(isset($params["module"]))
		$module=$params["module"];
	if(isset($params["keyfield"]))
		$keyfield=$params["keyfield"];
	if(isset($params["field"]))
		$field=$params["field"];
	if(isset($params["counter"]))
		$counter=$params["counter"]+1;
	if(isset($params["limit"]))
		$limit=$params["limit"];
	if(isset($params["data"]))
		$data=$params["data"];
	
	if(isset($params["edit"]))
		$edit=$params["edit"];
	if(isset($params["editmethod"]))
		$editmethod=$params["editmethod"];
	if(isset($params["view"]))
		$view=$params["view"];		
	if(isset($params["viewmethod"]))
		$viewmethod=$params["viewmethod"];
	if(isset($params["delete"]))
		$delete=$params["delete"];
	if(isset($params["deletemethod"]))
		$deletemethod=$params["deletemethod"];	
	if(isset($params["approvemethod"]))
		$approvemethod=$params["approvemethod"];
	if(isset($params["import"]))
		$import=$params["import"];		
	if(isset($params["export"]))
		$export=$params["export"];		
	if(isset($params["addbutton"]))
		$addbutton=$params["addbutton"];		
	if(isset($params["method1img"]))
		$method1img=$params["method1img"];
	if(isset($params["method1label"]))
		$method1label=$params["method1label"];
		
	if(isset($params["rowcss"]))
		$rcss=$params["rowcss"];
	if(isset($params["altrowcss"]))
		$altcss=$params["altrowcss"];
	if(isset($params["datarows"]))
		$datarows=$params["datarows"];
	if(isset($params["datacols"]))
		$datacols=$params["datacols"];
	if(isset($params["idenable"]))
		$tridenable=$params["idenable"];
	if(isset($params["ajax"]))
		$tridenable=$params["ajax"];
	if(isset($params["addmethod"]))
		$addmethod=$params["addmethod"];
	if(isset($params["inlineview"]))
		$inlineview=$params["inlineview"];
	if(isset($params["inline"]))
		$inline=$params["inline"];	
	if(isset($params["inlineedit"]))
		$inlineedit=$params["inlineedit"];	
	if(isset($params["href_field"]))
		$href_field=$params["href_field"];
	if(isset($params["href_action"]))
		$href_action=$params["href_action"];
	if(isset($params["href_module"]))
		$href_module=$params["href_module"];
	if(isset($params["href_key"]))
		$href_key=$params["href_key"];
	if(isset($params["check"]))
		$check=$params["check"];	
	
	$_url="";
	if(isset($session))
		$_url.="session=".$session."&";
	if(isset($module))
		$_url.="module=".encode($module)."&";
		
	if($datarows==0)
		return "<tbody><tr><td colspan=\"".$datacols."\" align=\"center\">No Data Found</td></tr>";
	$td_idcount=1;
	
	for($i=0; $i<$limit; $i++)
	{
		$val=$data[$counter-1];
		if(count($val)==0)
			break;
		if(isset($rcss) && isset($altcss)){
			if($counter%2)
				$html.="<tr class=\"".$rcss."\">";
			else
				$html.="<tr class=\"".$altcss."\">";
		}

		if(isset($rcss)){
			if($tridenable)
				$html.="<tr id=".$td_idcount." class=\"".$rcss."\">";
			else
				$html.="<tr class=\"".$rcss."\">";
		}
		else{
			if($tridenable)
				$html.="<tr id=".$td_idcount.">";
			else
				$html.="<tr>";
		}
		
		if($check)
		{
		$counter++;
		$html.="<td><input name=\"checkbox[]\" type=\"checkbox\" class=\"check\"  id=\"check\" value=".$val[$keyfield]."></td>";
		}
		else
			$html.="<td>".($counter++)."</td>";
		foreach($field as $fd)
		{
			if(strtolower($fd)=="status")
			{
				if($val[$fd]==1)
					$html.="<td><img src=\"images/active.jpg\" border=\"none\" alt=\"active\"/></td>";
				else
					$html.="<td><img src=\"images/inactive.jpg\" border=\"none\" alt=\"inactive\"/></td>";
				continue;
			}
			else if(strtolower($fd)==$href_field)
				$html.="<td><a href=\"index.php?session=".$session."&module=".encode($href_module)."&action=".encode($href_action)."&key=".encode($val[$href_key])."\">".$val[$fd]."</a></td>";
			else
				$html.="<td>".$val[$fd]."</td>";
		}
		if(isset($approvemethod))
			{
				
				$html.="<td align=\"center\"><a class=\"approve\" name=\"approve\" id=\"button_".$counter."\"href=\"index.php?".$_url."action=".encode($approvemethod)."&key=".encode($val[$keyfield])."\"><img src=\"images/approve.png\" border=\"none\"/></a></td>";
			}
		
		if(isset($import))
		{
			if($ajax)
				$html.="<td align=\"center\"><a class=\"import\" name=\"import\" href=\"#\" rel=\"".encode($val[$keyfield])."\"><img src=\"images/edit.png\" border=\"none\" alt=\"import\"/></a></td>";	
			else
				$html.="<td align=\"center\"><a class=\"import\" name=\"import\" href=\"#\" onClick=\"javascript:window.open('index.php?session=".$session."&module=".encode($module)."&action=".encode($import). "&key=".encode($val[$keyfield]). "', 'pop1win', 'toolbar=no scrollbars=yes,width=700,height=400')\"><img src=\"images/import.png\" border=\"none\" alt=\"Import\"/></a></td>";
		}
		
		if(isset($export))
		{
			if($ajax)
				$html.="<td align=\"center\"><a class=\"export\" name=\"export\" href=\"#\" rel=\"".encode($val[$keyfield])."\"><img src=\"images/edit.png\" border=\"none\" alt=\"export\"/></a></td>";
			else
				$html.="<td align=\"center\"><a class=\"export\" name=\"export\" href=\"index.php?".$_url."action=".encode($export)."&key=".encode($val[$keyfield])."\"><img src=\"images/export.png\" border=\"none\" alt=\"Export\"/></a></td>";
		}
		
		if(isset($addbutton))
		{
			if($ajax)
				$html.="<td align=\"center\"><a class=\"add\" name=\"add\" href=\"#\" rel=\"".encode($val[$keyfield])."\"><img src=\"images/edit.png\" border=\"none\" alt=\"Add\"/></a></td>";
			else
				$html.="<td align=\"center\"><a class=\"add\" name=\"add\" href=\"#\" onClick=\"javascript:window.open('index.php?session=".$session."&module=".encode($module)."&action=".encode($addbutton). "&key=".encode($val[$keyfield]). "', 'pop1win', 'toolbar=no scrollbars=yes,width=700,height=400')\"><img src=\"images/add.png\" border=\"none\" alt=\"Add\"/></a></td>";
		}
		
		if(isset($viewmethod))
		{
			if($inlineview)
				$html.="<td align=\"center\"><a class=\"btnView\" name=\"edit\" href=\"#\" rel=\"".encode($val[$keyfield])."\"><img src=\"images/payment.png\" border=\"none\"/></a></td>";
			else	
			$html.="<td align=\"center\"><a class=\"view\" name=\"view\" href=\"index.php?".$_url."action=".encode($viewmethod)."&key=".encode($val[$keyfield])."\"><img src=\"images/view1.png\" border=\"none\" alt=\"View\"/></a></td>";
		}
		
		if(isset($editmethod))
		{
			if($ajax)
				$html.="<td align=\"center\"><a class=\"edit\" name=\"edit\" href=\"#\" rel=\"".encode($val[$keyfield])."\"><img src=\"images/edit.png\" border=\"none\"/></a></td>";
			if($inlineedit)
				$html.="<td align=\"center\"><a class=\"btnEdit\" name=\"edit\" href=\"#\" rel=\"".encode($val[$keyfield])."\"><img src=\"images/edit.png\" border=\"none\"/></a></td>";
			else
				$html.="<td align=\"center\"><a class=\"edit\" name=\"edit\" href=\"index.php?".$_url."action=".encode($editmethod)."&key=".encode($val[$keyfield])."\"><img src=\"images/edit.png\" border=\"none\"/></a></td>";			
		}	
		if(isset($deletemethod))
		{
			if($ajax)
				$html.="<td align=\"center\"><a class=\"delete\" name=\"delete\" href=\"#\" rel=\"".encode($val[$keyfield])."\"><img src=\"images/delete.png\" border=\"none\" alt=\"Delete\"/></a></td>";
			else
				$html.="<td align=\"center\"><a class=\"delete\" name=\"delete\" id=\"button_".$counter."\"href=\"index.php?".$_url."action=".encode($deletemethod)."&key=".encode($val[$keyfield])."\"><img src=\"images/delete.png\" border=\"none\" alt=\"Delete\"/></a></td>";
		}
		
		$td_idcount++;
	}
	$html.="</tr></tbody>\n";
	return $html;
}

function smarty_function_gridview_footer($params, &$smarty)
{
	$field=$params["field"];
	$counter=$params["counter"]+1;
	$limit=$params["limit"];
	$data=$params["data"];
	$act=$params["action"];
	$css=$params["css"];
	$datarows=$params["datarows"];
	$datacols=$params["datacols"];

	foreach($field as $fd)
		$add_data[$fd]=0;

	for($i=0; $i<$limit; $i++)
	{
		$val=$data[$counter];
		if(count($val)==0)
			break;

		foreach($field as $fd)
		{
			if($act[$fd]=="sum" || $act[$fd]=="avg")
			{
				if(!isset($add_data[$fd]))
				{
					$add_data[$fd]=0;
				}
				$add_data[$fd]+=$val[$fd];
			}
		}
	}
	if(isset($css)){
		$html.="<tfoot><tr class=\"".$css."\">";
	}
	else
		$html.="<tfoot><tr>";
	if($datarows==0)
		$html.="<th class=\"bleft\"></th>";
	else
		$html.="<th class=\"bleft\">Total</th>";
	
	$couter=1;
	for($i=1; $i<$datacols; $i++)
	{
		if($datacols-1==$counter)
			$str="<th class=\"bright\">";
		else
			$str="<th>";
		if($add_data[$field[$i]]>0)
		{
			if($act[$fd]=="sum")
				$html.=$str.round($add_data[$fd], 2)."</th>";
			else if($act[$fd]=="avg")
				$html.=$str.round(($add_data[$fd])/$limit, 4)."</th>";
			else
				$html.=$str."&nbsp;</th>";
		}
		else
			$html.=$str."&nbsp;</th>";
		$counter++;
	}
	
	$html.="</tr></tfoot>\n";
	return $html;
}
?>