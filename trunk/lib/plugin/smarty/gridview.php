<?php
abstract class GridView
{
	protected $data;
	protected $counter;
	protected $logger;

	public function __set($key, $val)
	{
		$key=strtolower($key);
		$this->data[$key]=$val;
		$this->logger = logger::getRootlogger();
	}

	public function __get($key)
	{
		$key=strtolower($key);
		return $this->data[$key];
	}
	  
    public abstract function prepareHTML();
	
}

class DataView extends GridView
{
	private $html;
	private $js;
	
	public function __construct($session, $module, $keyfield="")
	{
		$this->data["session"]=$session;
		$this->data["module"]=$module;
		$this->data["keyfield"]=$keyfield;
		$this->counter=0;
		$this->html="";
		$this->js="";
	}
	
	private function prepareURL()
	{
		$_url="";
		if(isset($this->data["session"]))
			$_url.="session=".$this->data["session"]."&";
		if(isset($this->data["module"]))
			$_url.="module=".encode($this->data["module"])."&";
		
		return $_url;
	}
	
	public function prepareimgHTML()
	{
		$url=$this->prepareURL();
		/*if($this->data["datarows"]==0)
			$this->html .="<tbody><tr><td colspan=\"".$this->data["datacols"]."\" align=\"center\">No Data Found</td></tr>";
		*/	
		$td_idcount=0;
		
		for($i=0; $i<$this->data["limit"]; $i++)
		{
			
			$val=$this->data["values"][$this->data["counter"]+$td_idcount-1];
						
			if(count($val)==0)
				break;
			$this->html.="\n\t\t<div class=\"box\">\n";
			$this->html.="<a href=\"index.php?".$url."&action=".encode($this->data["viewmethod"])."&key=".encode($val[$this->data["keyfield"]])."\"><div class=\"mage\"><img src=\"data:image/png;base64,". base64_encode($val["product_image"])."\" border=\"0\" alt=\"\" width=\"".$this->data["imgwidth"]."\" height=\"".$this->data["imgheight"]."\"/>\n";
			$this->html.="\t\t\t</div>\n";
			$this->html.="\t\t\t<div class=\"cont-data\">\n";
			foreach($this->data["field"] as $fd)
			{
				if(strtolower($fd)=="product_image")
					continue;
				else
					$this->html .="\t\t\t<p>".$val[$fd]."</p>\n";
			}
			$this->html.="\t\t\t</div>\n";
			
			$this->html.="<a href=\"index.php?".$url."&action=".encode($this->data["editmethod"])."&key=".encode($val[$this->data["keyfield"]])."\"><img src=\"images/update1.png\" border=\"none\"/></a>";
			$this->html.="<a class=\"delete\" title=\"Delete\" name=\"edit\" href=\"#\" rel=\"".encode($val[$this->data["keyfield"]])."\"><img src=\"images/delete1.png\" border=\"none\"/></a>";
			$this->html.="\t\t</div>\n";
			$td_idcount++;
		}
		
		//$this->html .="</tr></tbody>\n";	
	}
	
	
	public function prepareHTML()
	{
		$url=$this->prepareURL();
		if($this->data["datarows"]==0)
			$this->html .="<tbody><tr><td colspan=\"".$this->data["datacols"]."\" align=\"center\">No Data Found</td></tr>";
			
		$td_idcount=0;
		
		for($i=0; $i<$this->data["limit"]; $i++)
		{
			
			$val=$this->data["values"][$this->data["counter"]+$td_idcount-1];
						
			if(count($val)==0)
				break;
				
			if(isset($this->data["rcss"]) && isset($this->data["altcss"]))
			{
				if($this->data["counter"]%2)
					$this->html .="<tr class=\"".$this->data["rcss"]."\">";
					//$this->html .="<img src=\"images/usb.png\" border=\"0\" alt="" />";
				else
					$this->html .="<tr class=\"".$this->data["altcss"]."\">";
			}

			if(isset($this->data["rcss"])){
				if($this->data["tridenable"])
					$this->html .="<tr id=".$this->data["td_idcount"]." class=\"".$this->data["rcss"]."\">";
				else
					$this->html .="<tr class=\"".$this->data["rcss"]."\">";
			}
			else
			{
				if($this->data["tridenable"])
					$this->html .="<tr id=".$this->data["td_idcount"].">";
				else
					$this->html .="<tr>";
			}
			//$this->html .="<img src=\"images/usb.png\" border=\"0\" alt="" />";
			foreach($this->data["field"] as $fd)
			{
				if(strtolower($fd)=="status")
				{
					if($val[$fd]==1)
						$this->html .="<td><a href=\"".$url."&action=".encode("block")."&key=".encode($val[$this->data["keyfield"]])."\"><img src=\"images/active.jpg\" border=\"none\" alt=\"Status\"/></a></td>";
					else
						$this->html .="<td><a href=\"".$url."&action=".encode("active")."&key=".encode($val[$this->data["keyfield"]])."\"><img src=\"images/inactive.jpg\" border=\"none\" alt=\"Status\"/></a></td>";
					continue;
				}
				else
					$this->html .="<td>".$val[$fd]."</td>";
			}
			
			if(isset($this->data["approvemethod"]))
			{
				
				$this->html .="<td align=\"center\"><a class=\"approve\" title=\"Approve\" name=\"edit\" href=\"#\" rel=\"".encode($val[$this->data["keyfield"]])."\"><img src=\"images/approve.png\" border=\"none\"/></a>";
			}
			
			if(isset($this->data["viewmethod"]))
			{
				
				$this->html .="<td align=\"center\"><a class=\"view\" title=\"View\" name=\"edit\" href=\"#\" rel=\"".encode($val[$this->data["keyfield"]])."\"><img src=\"images/view1.png\" border=\"none\"/></a>";
			}
			
			if(isset($this->data["editmethod"]))
			{
				$this->html.="<a href=\"index.php?".$url."&action=".encode($this->data["editmethod"])."&key=".encode($val[$this->data["keyfield"]])."\"><img src=\"images/update1.png\" border=\"none\"/></a>";
			}
			
			if(isset($this->data["deletemethod"]))
			{
				$this->html .="<a class=\"delete\" name=\"delete\" title=\"Delete\" href=\"#\" rel=\"".encode($val[$this->data["keyfield"]])."\"><img src=\"images/delete1.png\" border=\"none\"/></a></td>";
			}
			
			$td_idcount++;
		}
		
		$this->html .="</tr></tbody>\n";	
	}
	
	function prepare_delete()
	{
		$this->js .="$(\".delete\").click(function() {\n";
		$this->js .="\t\t\t\tif (confirm(\"Do u want to delete\")){\n";
		$this->js .="\t\tvar key=\$(this).attr(\"rel\");\n";
		$this->js .="\t\t\$('#".$this->data["waiting"]."').show(500);\n";
		$this->js .="\t\t\$('#".$this->data["datashow"]."').hide(0);\n";
		$this->js .="\t\t\$('#".$this->data["message"]."').hide(0);\n\n";
		$this->js .="\t\t\$.ajax({\n";
		$this->js .="\t\t\ttype : \"POST\",\n";
		$this->js .="\t\t\turl : \"index.php\",\n";
		$this->js .="\t\t\tdata: \"session=".$this->data["session"]."&module=".encode($this->data["module"])."&action=".encode($this->data["deletemethod"])."&key=\"+key,\n";
		$this->js .="\t\t\tcache: false,\n\n";
		$this->js .="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#".$this->data["datashow"]."').html(data);\n";
		$this->js .="\t\t\$('#".$this->data["datashow"]."').show(500);\n";
		$this->js .="\t\t\t\t\$('#".$this->data["message"]."').show(500);\n";
		$this->js .="\t\t\t},\n\n";
		$this->js .="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
		$this->js .="\t\t\t\tif(XMLHttpRequest.status==404) {\n";
		$this->js .="\t\t\t\t\twindow.location.href=\"index.php\";\n";
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#".$this->data["message"]."').removeClass().addClass(\"error\")\n";
		$this->js .="\t\t\t\t.text('There was an error.').show(500);\n";
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t})\n";
		$this->js .="\t\t\t};\n";
		$this->js .="\t\treturn false;\n";	
		$this->js .="\t\t});\n";
	}
	
	function prepare_approve()
	{
		$this->js .="$(\".approve\").click(function() {\n";
		$this->js .="\t\t\t\tif (confirm(\"Do u want to Approve\")){\n";
		$this->js .="\t\tvar key=\$(this).attr(\"rel\");\n";
		$this->js .="\t\t\$('#".$this->data["waiting"]."').show(500);\n";
		$this->js .="\t\t\$('#".$this->data["datashow"]."').hide(0);\n";
		$this->js .="\t\t\$('#".$this->data["message"]."').hide(0);\n\n";
		$this->js .="\t\t\$.ajax({\n";
		$this->js .="\t\t\ttype : \"POST\",\n";
		$this->js .="\t\t\turl : \"index.php\",\n";
		$this->js .="\t\t\tdata: \"session=".$this->data["session"]."&module=".encode($this->data["module"])."&action=".encode($this->data["deletemethod"])."&key=\"+key,\n";
		$this->js .="\t\t\tcache: false,\n\n";
		$this->js .="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#".$this->data["datashow"]."').html(data);\n";
		$this->js .="\t\t\$('#".$this->data["datashow"]."').show(500);\n";
		$this->js .="\t\t\t\t\$('#".$this->data["message"]."').show(500);\n";
		$this->js .="\t\t\t},\n\n";
		$this->js .="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
		$this->js .="\t\t\t\tif(XMLHttpRequest.status==404) {\n";
		$this->js .="\t\t\t\t\twindow.location.href=\"index.php\";\n";
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#".$this->data["message"]."').removeClass().addClass(\"error\")\n";
		$this->js .="\t\t\t\t.text('There was an error.').show(500);\n";
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t})\n";
		$this->js .="\t\t\t};\n";
		$this->js .="\t\treturn false;\n";	
		$this->js .="\t\t});\n";
		
	}
	
	function prepare_update($dialog, $height, $width)
	{
		$this->js .="$(\".edit\").click(function() {\n";
		$this->js .="\t\tvar key=\$(this).attr(\"rel\");\n";
		$this->js .="\t\t\$('#".$this->data["waiting"]."').show(500);\n";
		$this->js .="\t\t\$('#".$this->data["message"]."').hide(0);\n\n";
		$this->js .="\t\t\$.ajax({\n";
		$this->js .="\t\t\ttype : \"POST\",\n";
		$this->js .="\t\t\turl : \"index.php\",\n";
		$this->js .="\t\t\tdata: \"session=".$this->data["session"]."&module=".encode($this->data["module"])."&action=".encode($this->data["editmethod"])."&key=\"+key,\n";
		$this->js .="\t\t\tcache: false,\n\n";
		$this->js .="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#viewdata').attr(\"title\",\"".$this->data["title"]."\");\n";
		$this->js .="\t\t\t\$('#viewdata').dialog({modal: true, height: ".$this->data["height"].", width: ".$this->data["width"]." });\n";
		$this->js .="\t\t\t\t\$('#viewdata').html(data);\n";
		$this->js .="\t\t\t\$('#viewdata').show(500);\n";
		$this->js .="\t\t\t},\n\n";
		
		$this->js .="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
		$this->js .="\t\t\t\tif(XMLHttpRequest.status==404) {\n";
		$this->js .="\t\t\t\$('#viewdata').dialog(\"close\");\n"; 
		$this->js .="\t\t\t\t\twindow.location.href=\"index.php\";\n"; 
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#".$this->data["message"]."').removeClass().addClass(\"error\")\n";
		$this->js .="\t\t\t\t.text('There was an error.').show(500);\n";
		$this->js .="\t\t\t\$('#".$this->data["message"]."').dialog(\"close\");\n"; 
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t})\n";
		$this->js .="\t\treturn false;\n";
		$this->js .="\t\t});\n";
	}
	
	function prepare_view($dialog, $height, $width)
	{
		$this->js .="$(\".view\").click(function() {\n";
		$this->js .="\t\tvar key=\$(this).attr(\"rel\");\n";
		$this->js .="\t\t\$('#".$this->data["waiting"]."').show(500);\n";
		$this->js .="\t\t\$('#".$this->data["message"]."').hide(0);\n\n";
		$this->js .="\t\t\$.ajax({\n";
		$this->js .="\t\t\ttype : \"POST\",\n";
		$this->js .="\t\t\turl : \"index.php\",\n";
		$this->js .="\t\t\tdata: \"session=".$this->data["session"]."&module=".encode($this->data["module"])."&action=".encode($this->data["viewmethod"])."&key=\"+key,\n";
		$this->js .="\t\t\tcache: false,\n\n";
		$this->js .="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#viewdata').attr(\"title\",\"".$this->data["viewtitle"]."\");\n";
		$this->js .="\t\t\t\$('#viewdata').dialog({modal: true, height: ".$this->data["height"].", width: ".$this->data["width"]." });\n";
		$this->js .="\t\t\t\t\$('#viewdata').html(data);\n";
		$this->js .="\t\t\t\$('#viewdata').show(500);\n";
		$this->js .="\t\t\t},\n\n";
		$this->js .="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
		$this->js .="\t\t\t\tif(XMLHttpRequest.status==404) {\n";
		$this->js .="\t\t\t\t\twindow.location.href=\"index.php\";\n";
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#".$this->data["message"]."').removeClass().addClass(\"error\")\n";
		$this->js .="\t\t\t\t.text('There was an error.').show(500);\n";
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t})\n";
		$this->js .="\t\treturn false;\n";
		$this->js .="\t\t});\n";
	}
	
	function prepare_add($button, $dialog, $height, $width)
	{
		/*$this->js .="$(\".add\").click(function() {\n";
		//$this->js .="\t\tvar key=\$(this).attr(\"rel\");\n";
		//$this->js .="\t\t\$('#".$this->data["waiting"]."').show(500);\n";
		//$this->js .="\t\t\$('#".$this->data["message"]."').hide(0);\n\n";
		$this->js .="\t\t\$.ajax({\n";
		$this->js .="\t\t\ttype : \"POST\",\n";
		$this->js .="\t\t\turl : \"index.php\",\n";
		$this->js .="\t\t\tdata: \"session=".$this->data["session"]."&module=".encode($this->data["module"])."&action=".encode($this->data["addmethod"])."\n";
		$this->js .="\t\t\tcache: false,\n\n";
		$this->js .="\t\t\tsuccess : function(data, textStatus, jqXHR){\n";
		$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		$this->js .="\t\t\t\t\$('#viewdata').attr(\"title\",\"".$this->data["updatetitle"]."\");\n";
		$this->js .="\t\t\t\$('#viewdata').dialog({modal: true, height: ".$this->data["height"].", width: ".$this->data["width"]." });\n";
		//$this->js .="\t\t\t\t\$('#viewdata').html(data);\n";
		//$this->js .="\t\t\t\$('#viewdata').show(500);\n";
		$this->js .="\t\t\t},\n\n";
		$this->js .="\t\t\terror : function(XMLHttpRequest, textStatus, errorThrown) {\n";
		$this->js .="\t\t\t\tif(XMLHttpRequest.status==404) {\n";
		$this->js .="\t\t\t\t\twindow.location.href=\"index.php\";\n";
		$this->js .="\t\t\t\t}\n";
		//$this->js .="\t\t\t\t\$('#".$this->data["waiting"]."').hide(500);\n";
		//$this->js .="\t\t\t\t\$('#".$this->data["message"]."').removeClass().addClass(\"error\")\n";
		//$this->js .="\t\t\t\t.text('There was an error.').show(500);\n";
		$this->js .="\t\t\t\t}\n";
		$this->js .="\t\t\t})\n";
		$this->js .="\t\treturn false;\n";
		$this->js .="\t\t});\n";*/
	}
	
	function getHTML()
	{
		return $this->html;
	}
	
	function getJS()
	{
		return $this->js;
	}
}
?>