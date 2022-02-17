<table class="jsErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
	<tbody>
<?		$i=1;
		$width = array('4%','34%','32%','5%','5%','5%','10%');
		$headingArr = array("#","ErrorMessage","jsPath", "LineNo","ColNo", "Occurences", "OccurenceTime");
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
			echo "<td>".((($pageNumber-1)*$rows)+$key+1)."</td>";
			echo "<td>".html_escape($value['errmsg'])."</td>";
			echo "<td>".html_escape($value['jsPath'])."</td>";
			echo "<td>".html_escape($value['line_num'])."</td>";
			echo "<td>".html_escape($value['col_num'])."</td>";
			echo "<td><a href='javascript:void(0);' onclick=\"showURLList('".str_replace('\'', '\\\'', $value['errmsg'])."','".$value['jsPath']."', '".$value['line_num']."', '".$value['col_num']."', '".$filters['fromdate']."', '".$filters['todate']."', '".$filters['module']."'); return false;\">".html_escape($value['Occurences'])."</td>";
			echo "<td>".html_escape($value['OccurenceTime'])."</td>";

			echo "</tr>";
			
		}
?>
	</tbody>
	</table>

<?php
$totalPages = $totalResults/$rows;
$totalPages = ceil($totalPages);

$paginationLimit=10;
$startPag=1;
$endPag= $totalPages < $paginationLimit ? $totalPages : $paginationLimit;

if($totalPages > $paginationLimit){
	$startPag = $pageNumber - floor(($paginationLimit/2));
	$startPag = $startPag < 1 ? 1 : $startPag;

	$endPag = $pageNumber + floor(($paginationLimit/2));
	$endPag = $endPag > $totalPages ? $totalPages : $endPag;
}
?>
<div class="n-pagination">
 <ul class="pagniatn-ul">
 	<?php if($pageNumber != 1){ ?>
 	<li><a class="pagination-arw" onclick="updateReport(<?php echo $pageNumber-1;?>);">❮</a></li>
 	<?php 
 	}
 	 for ($i=$startPag; $i<=$endPag; $i++) {
	?>
		<li class="<?php echo $pageNumber==$i ? 'actvpage' : '';?>" onclick="updateReport(<?php echo $i;?>);"><a style="color:#5b5757"><?php echo $i;?></a></li> 
	<?php 		
 	}
 	?>
 	<?php if($pageNumber != $totalPages){ ?>
 	<li><a class="pagination-arw" onclick="updateReport(<?php echo $pageNumber+1;?>);">❯</a></li>
 	<?php } ?>
  </ul>			      
</div>

<script>
function showURLList(message, jsPath, line_num, col_num,fromdate, todate, module)
{
    $("body").addClass("noscroll");
	$("#voverlay").show();
	$("#vdialog_inner").css('top', $('body').scrollTop()+20);
	$("#vdialog").show();
	$("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
    //chartTypeData = controller+"___"+method;
    $.ajax({
	    	data: { "msg" : message , "jsPath":jsPath,"date" : fromdate, "todate" : todate, "module" : module,"dashboardType" : "<?php echo $dashboard;?>", "lineNum" : line_num,"col_num":col_num},
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

