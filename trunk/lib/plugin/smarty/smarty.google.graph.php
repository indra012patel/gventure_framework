<?php
function smarty_block_gventure_graph($params, $content, &$smarty, &$repeat)
{
	if(isset($params["function"]))
                $func=$params["function"];
	else
		$func="drawChart";

    	if(!$repeat){
        	if (isset($content)) {
		$str="<!-- GVenture graph start here -->\n";
            	$str.="<script type=\"text/javascript\" src=\"https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart','geochart','line']}]}\"></script>\n";
            	$str.="<script language=\"javascript\" type=\"text/javascript\">\n";
		//$str.="\tgoogle.load(\"visualization\", \"1\", {packages: ['corechart', 'line', 'geochart','table']});\n";
		$str.="\tgoogle.setOnLoadCallback(".$func.");\n";
		$str.="\tfunction ".$func."() {";
		$str.=$content;
		$str.="\n\t}\n";
		$str.="\n</script>\n";
		$str.="<!-- GVenture graph end here -->\n";
            	return $str;
        }
    }
}
function smarty_function_pie($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["option"]))
		$option=$params["option"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(is_array($data))
	{
		foreach($data as $k=>$v)
			$record.="['".$k."', ".$v."],";
		$record=rtrim($record,',');
	}else
		$record="[]";
	$html.="\n\t\tvar data_".$id." = new google.visualization.DataTable();\n";
	foreach($header as $val)
		$html.="\t\tdata_".$id.".addColumn('".$val["type"]."', '".$val["label"]."');\n";
		
	$html.="\t\tdata_".$id.".addRows([".$record."]);\n";

	$html.="\t\tvar options_".$id." = {is3D: true}\n";
	$html.="\t\tvar chart_".$id." = new google.visualization.PieChart(document.getElementById('".$id."'));\n";
	$html.="\t\tchart_".$id.".draw(data_".$id.", options_".$id.");\n";
    return $html;
}

function smarty_function_table($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["title"]))
		$title=$params["title"];
	if(isset($params["subtitle"]))
		$subtitle=$params["subtitle"];
	if(isset($params["type"]))
		$type=$params["type"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(is_array($data))
	{
		foreach($data as $k=>$v){
			$tmp=explode("-",$v);
			if(count($tmp)==1)
				$record.="['".$k."', ".$v."],";
			if(count($tmp)==2)
			{
				if($tmp[1]==0)
					$record.="['".$k."', ".$tmp[0].", false],";
				else
					$record.="['".$k."', ".$tmp[0].", true],";
			}
		}
		$record=rtrim($record,',');
	}else
		$record="[]";
	$html.="\n\t\tvar data_".$id." = new google.visualization.DataTable();\n";
	foreach($header as $val)
		$html.="\t\tdata_".$id.".addColumn('".$val["type"]."', '".$val["label"]."');\n";
	
	$html.="\t\tdata_".$id.".addRows([".$record."]);\n";
		
	$html.="\t\tvar ".$id."_chart = new google.visualization.Table(document.getElementById('".$id."'));\n";
	$html.="\t\t".$id."_chart.draw(data_".$id.", null);\n";
    	return $html;
}

function smarty_function_geo($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["title"]))
		$title=$params["title"];
	if(isset($params["subtitle"]))
		$subtitle=$params["subtitle"];
	if(isset($params["type"]))
		$type=$params["type"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(is_array($data))
	{
		foreach($data as $k=>$v){
			$tmp=explode("-",$v);
			if(count($tmp)==1)
				$record.="['".$k."', ".$v."],";
			if(count($tmp)==2)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1]."],";
		}
		$record=rtrim($record,',');
	}else
		return "";
	$html.="\n\t\tvar data_".$id." = new google.visualization.DataTable();\n";
	foreach($header as $val)
		$html.="\t\tdata_".$id.".addColumn('".$val["type"]."', '".$val["label"]."');\n";
	
	$html.="\t\tdata_".$id.".addRows([".$record."]);\n";
		
	$html.="\t\tvar ".$id."_options = {};\n";
	$html.="\t\tvar ".$id."_chart = new google.visualization.GeoChart(document.getElementById('".$id."'));\n";
	$html.="\t\t".$id."_chart.draw(data_".$id.",  ".$id."_options);\n";
    	return $html;
}

function smarty_function_gg2column($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["title"]))
		$title=$params["title"];
	if(isset($params["subtitle"]))
		$subtitle=$params["subtitle"];
	if(isset($params["type"]))
		$type=$params["type"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(isset($params["vaxis1"]))
		$vaxis1=$params["vaxis1"];
	if(isset($params["vaxis2"]))
		$vaxis2=$params["vaxis2"];
	if(isset($params["haxis"]))
		$haxis=$params["haxis"];
	if(is_array($data))
	{
		foreach($data as $k=>$v){
			$tmp=explode("-",$v);
			if(count($tmp)==1)
				$record.="['".$k."', ".$v."],";
			if(count($tmp)==2)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1]."],";
			if(count($tmp)==3)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1].", ".$tmp[2]."],";
			if(count($tmp)==4)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1].", ".$tmp[2].", ".$tmp[3]."],";
		}
		$record=rtrim($record,',');
	}else
		return "";
	$html.="\n\t\tvar data_".$id." = new google.visualization.DataTable();\n";
	foreach($header as $val)
		$html.="\t\tdata_".$id.".addColumn('".$val["type"]."', '".$val["label"]."');\n";
	
	$html.="\t\tdata_".$id.".addRows([".$record."]);\n";
		
	$html.="\t\tvar ".$id."_options = {hAxis: {title: '".$haxis."'}, title: '".$title."', vAxes: {0: {title: '".$vaxis1."'},1: {title: '".$vaxis2."'}}, series: { 0:{targetAxisIndex: 0}, 1:{targetAxisIndex: 1}}};\n";
	$html.="\t\tvar ".$id."_chart = new google.visualization.ColumnChart(document.getElementById('".$id."'));\n";
	$html.="\t\t".$id."_chart.draw(data_".$id.",  ".$id."_options);\n";
    	return $html;
}

function smarty_function_ggcolumn($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["title"]))
		$title=$params["title"];
	if(isset($params["subtitle"]))
		$subtitle=$params["subtitle"];
	if(isset($params["type"]))
		$type=$params["type"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(isset($params["vaxis"]))
		$vaxis=$params["vaxis"];
	if(isset($params["haxis"]))
		$haxis=$params["haxis"];
	if(is_array($data))
	{
		foreach($data as $k=>$v){
			$tmp=explode("-",$v);
			if(count($tmp)==1)
				$record.="['".$k."', ".$v."],";
			if(count($tmp)==2)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1]."],";
			if(count($tmp)==3)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1].", ".$tmp[2]."],";
			if(count($tmp)==4)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1].", ".$tmp[2].", ".$tmp[3]."],";
		}
		$record=rtrim($record,',');
	}else
		return "";
	$html.="\n\t\tvar data_".$id." = new google.visualization.DataTable();\n";
	foreach($header as $val)
		$html.="\t\tdata_".$id.".addColumn('".$val["type"]."', '".$val["label"]."');\n";
	
	$html.="\t\tdata_".$id.".addRows([".$record."]);\n";
		
	$html.="\t\tvar ".$id."_options = {hAxis: {title: '".$haxis."'}, vAxis: {title: '".$vaxis."'}, title: '".$title."'};\n";
	$html.="\t\tvar ".$id."_chart = new google.visualization.ColumnChart(document.getElementById('".$id."'));\n";
	$html.="\t\t".$id."_chart.draw(data_".$id.",  ".$id."_options);\n";
    	return $html;
}

function smarty_function_bar($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["title"]))
		$title=$params["title"];
	if(isset($params["subtitle"]))
		$subtitle=$params["subtitle"];
	if(isset($params["type"]))
		$type=$params["type"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["vaxis"]))
		$vaxis=$params["vaxis"];
	if(isset($params["haxis"]))
		$haxis=$params["haxis"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(is_array($data))
	{
		foreach($data as $k=>$v)
			$record.="['".$k."', ".$v."],";
		$record=rtrim($record,',');
	}else
		$record="[]";
	$html.="\n\t\tvar data_".$id." = new google.visualization.DataTable();\n";
	foreach($header as $val)
		$html.="\t\tdata_".$id.".addColumn('".$val["type"]."', '".$val["label"]."');\n";
	
	$html.="\t\tdata_".$id.".addRows([".$record."]);\n";
		
	$html.="\t\tvar ".$id."_options = {hAxis: {title: '".$haxis."'}, vAxis: {title: '".$vaxis."'}, title: '".$title."'};\n";
	$html.="\t\tvar ".$id."_chart = new google.visualization.BarChart(document.getElementById('".$id."'));\n";
	$html.="\t\t".$id."_chart.draw(data_".$id.",  ".$id."_options);\n";
    return $html;
}

function smarty_function_axis2line($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["title"]))
		$title=$params["title"];
	if(isset($params["subtitle"]))
		$subtitle=$params["subtitle"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(isset($params["width"]))
		$width=$params["width"];
	if(isset($params["height"]))
		$height=$params["height"];
	if(isset($params["vaxis1"]))
		$vaxis1=$params["vaxis1"];
	if(isset($params["vaxis2"]))
		$vaxis2=$params["vaxis2"];
	if(isset($params["haxis"]))
		$haxis=$params["haxis"];
	if(is_array($data))
	{
		$i=1;
		foreach($data as $k=>$v){
			$tmp=explode("-",$v);
			if(count($tmp)==1)
				$record.="['".$k."', ".$v."],";
			if(count($tmp)==2)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1]."],";
			$i++;
		}
		$record=rtrim($record,',');
	}else
		$record="[]";
	//$html="\tgoogle.load(\"visualization\", \"1.1\", {packages:[\"line\"]});\n";
	$html.="\n\t\tvar ".$id."_data = new google.visualization.DataTable();\n";
	if(is_array($header))
	{
		foreach($header as $val)
			$html.="\t\t".$id."_data.addColumn('".$val["type"]."', '".$val["label"]."');\n";
			//$html.="\t\t".$id."_data.addColumn('number', '".$val."');\n";
	}
	$html.="\t\t".$id."_data.addRows([".$record."]);\n";

	$html.="\t\tvar ".$id."_options = {hAxis: {title: '".$haxis."'}, vAxes: {0: {title: '".$vaxis1."'},1: {title: '".$vaxis2."'}}, title: '".$title."', legend: { position: 'bottom' }, series: { 0:{targetAxisIndex: 0}, 1:{targetAxisIndex: 1}}};\n";

	$html.="\t\tvar ".$id."_chart = new google.visualization.LineChart(document.getElementById('".$id."'));\n";
	$html.="\t\t".$id."_chart.draw(".$id."_data, ".$id."_options);\n";
    return $html;
}

function smarty_function_line($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["title"]))
		$title=$params["title"];
	if(isset($params["subtitle"]))
		$subtitle=$params["subtitle"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(isset($params["width"]))
		$width=$params["width"];
	if(isset($params["height"]))
		$height=$params["height"];
	if(is_array($data))
	{
		$i=1;
		foreach($data as $k=>$v){
			$tmp=explode("-",$v);
			if(count($tmp)==1)
				$record.="['".$k."', ".$v."],";
			if(count($tmp)==2)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1]."],";
			if(count($tmp)==3)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1].", ".$tmp[2]."],";
			if(count($tmp)==4)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1].", ".$tmp[2].", ".$tmp[3]."],";
			if(count($tmp)==5)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1].", ".$tmp[2].", ".$tmp[3].", ".$tmp[4]."],";
			if(count($tmp)==6)
				$record.="['".$k."', ".$tmp[0].", ".$tmp[1].", ".$tmp[2].", ".$tmp[3].", ".$tmp[4].", ".$tmp[5]."],";
			$i++;
		}
		$record=rtrim($record,',');
	}else
		$record="[]";
	//$html="\tgoogle.load(\"visualization\", \"1.1\", {packages:[\"line\"]});\n";
	$html.="\n\t\tvar ".$id."_data = new google.visualization.DataTable();\n";
	if(is_array($header))
	{
		foreach($header as $val)
			$html.="\t\t".$id."_data.addColumn('".$val["type"]."', '".$val["label"]."');\n";
			//$html.="\t\t".$id."_data.addColumn('number', '".$val."');\n";
	}
	$html.="\t\t".$id."_data.addRows([".$record."]);\n";

	$html.="\t\tvar ".$id."_options = {hAxis: {title: 'Hour'}, vAxis: {title: 'Minutes'}, legend: { position: 'bottom' }, title: '".$title["title"]."'};\n";

	$html.="\t\tvar ".$id."_chart = new google.visualization.LineChart(document.getElementById('".$id."'));\n";
	$html.="\t\t".$id."_chart.draw(".$id."_data, ".$id."_options);\n";
    return $html;
}

function smarty_function_gauge($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["option"]))
		$option=$params["option"];
	if(isset($params["data"]))
		$data=$params["data"];
	if(is_array($data))
	{
		foreach($data as $k=>$v)
			$record.="['".$k."', ".$v."],";
		
		substr($record,0,-1);
	}else
		$record="[data is not array type]";
	$html="google.load(\"visualization\", \"1\", {packages:[\"gauge\"]});\n";
	$html.="\tgoogle.setOnLoadCallback(drawChart);\n";
	$html.="\tfunction drawChart() {\n";

	$html.="\t\tvar data = google.visualization.arrayToDataTable([\n";
	$html.="\t\t\t['label','value'],\n";
	$html.="\t\t\t".$record."\n";
	$html.="\t\t]);\n";

	$html.="\tvar options = {\n";
	if(is_array($option))
	{
		foreach($option as $key=>$val)
			$opt.=" ".$key.": ".$val.",";
		
		rtrim($opt,',');
		$html.="\t\t".$opt."\n";
	}else
		'no array data type';
	$html.="\t};\n";

	$html.="\tvar chart = new google.visualization.Gauge(document.getElementById('".$id."'));\n";

	$html.="\tchart.draw(data, options);\n";
	$html.="\t}\n";
	return $html;
}

function smarty_function_flotpie($params, &$smarty)
{
	if(isset($params["id"]))
		$id=$params["id"];
	if(isset($params["option"]))
		$option=$params["option"];
	if(isset($params["header"]))
		$header=$params["header"];
	if(isset($params["data"]))
		$data=$params["data"];
	$record="";
	if(is_array($data))
	{
		foreach($data as $k=>$v)
			$record.="{ label: '".$k."', data: ".$v." },";
		$record=rtrim($record,',');
	}else
		$record="[data is not array type]";
	$html.="\t\$(function(){\n";
	$html.="\t\t\tvar data=[".$record."];\n";
	$html.="\t\t\tvar options = {\n";
        $html.="\t\t\tseries: {\n";
        $html.="\t\t\tpie: {show: true, radius: 1, label: { show: true, radius: 3/4, background: {opacity: 0.5, color: '#000'}} }\n";
        $html.="\t\t\t},\n";
        $html.="\t\t\tlegend: {\n";
        $html.="\t\t\t show: false\n";
        $html.="\t\t\t}\n";
        $html.="\t\t\t};\n";
	$html.="\t\t\t\$.plot($(\"#".$id."\"), data, options);\n";
	$html.="\t\t});";
    return $html;
}

?>
