<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
	<tbody>
<?php
		$i=1;
		$width = array('4%','28%','19%','5%','12%','10%','6%','7%','10%');
		$headingArr = array("#","ErrorMessage","sourceFile", "LineNo", "URL", "ErrorClass", "ErrorCode", "Occurences", "OccurenceTime");
		echo "<tr>";
		foreach($headingArr as $value)
		{
			echo "<th width='".$width[$i-1]."'>";
			echo $value;
			echo "</th>";
			$i++;
		}
		echo "</tr>";

		$i=1;

		if(empty($data))
		{
			echo "<tr><td colspan='".count($headingArr)."'><i>No Data Found</i></td></tr>";
		}

		foreach($data as $key=>$value)
		{
			echo "<tr>";
			echo "<td>".($key+1)."</td>";
			echo "<td>".html_escape($value['exception_msg'])."</td>";
			echo "<td>".html_escape($value['source_file'])."</td>";
			echo "<td>".html_escape($value['line_num'])."</td>";
			echo "<td>".html_escape($value['url'])."</td>";
			echo "<td>".html_escape($value['error_class'])."</td>";
			echo "<td>".html_escape($value['error_code'])."</td>";
			echo "<td><a href='javascript:void(0);' onclick=\"showURLList('".$value['module_name']."','".$value['controller_name']."', '".$value['method_name']."', '".$filters['fromdate']."', '".$filters['todate']."', '".$filters['module']."', '".$value['source_file']."', '".$value['line_num']."'); return false;\">".html_escape($value['Occurences'])."</a></td>";
			echo "<td>".html_escape($value['OccurenceTime'])."</td>";

			echo "</tr>";
			
		}
?>
	</tbody>
	</table>
<script>
function showURLList(module, controller, method, fromdate, todate, module, sourceFile, lineNum)
{
    $("body").addClass("noscroll");
	$("#voverlay").show();
	$("#vdialog_inner").css('top', $('body').scrollTop()+20);
	$("#vdialog").show();
	$("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
    chartTypeData = controller+"___"+method;
    $.ajax({
	    	data: { "charttype" : chartTypeData , "date" : fromdate, "todate" : todate, "module" : module,"dashboardType" : "<?php echo $dashboard;?>", "sourceFile" : sourceFile, "lineNum" : lineNum},
	    	url : "/AppMonitor/Dashboard/showTopUrls",
	    	cache : false,
	    	method : "POST",
	    	beforeSend : function(){
	    	}
	    }).done(function(res){
			$("#vdialog_content").html(res);
	    });
}
</script>

