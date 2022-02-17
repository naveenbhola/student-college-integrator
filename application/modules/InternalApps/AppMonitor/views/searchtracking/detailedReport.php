<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
	<tbody>
		<?php 
			$i=1;
			$width = array('5%','23%','6%','10%','22%','5%',"7%",'12%');
			$headingArr = array("#","keyword","pageType", "criteriaApplied", "newKeyword", "count","device","OccurenceTime");
			if($reportType == 'criteriaReduction'){
				$headingArr[5] = 'isZRP';
			}
			echo "<tr>";
			foreach($headingArr as $value){
				echo "<th width='".$width[$i-1]."'>";
				echo $value;
				echo "</th>";
				$i++;
			}
			echo "</tr>";

			$i=1;

			if(empty($data)){
				echo "<tr><td colspan='".count($headingArr)."'><i>No Data Found</i></td></tr>";
			}

			foreach($data as $key=>$value){
				echo "<tr>";
				echo "<td>".($key+1)."</td>";
				echo "<td>".html_escape($value['keyword'])."</td>";
				echo "<td>".html_escape($value['pageType'])."</td>";
				echo "<td>".html_escape($value['criteriaApplied'])."</td>";
				echo "<td>".html_escape($value['newKeyword'])."</td>";
				if($reportType == 'criteriaReduction'){
					echo "<td>".html_escape($value['isZRP'])."</td>";
				}
				else{
					echo "<td><a href='javascript:void(0);' onclick='showFiltersList(\"".$value['searchIds']."\",\"".html_escape($value['keyword'])."\")'>".html_escape($value['count'])."</a></td>";
				}
				echo "<td>".html_escape($value['device'])."</td>";
				echo "<td>".html_escape($value['created'])."</td>";
				echo "</tr>";
			}
		?>
	</tbody>
</table>
<script>
	function showFiltersList(searchIds,keyword){
	    $("body").addClass("noscroll");
		$("#voverlay").show();
		$("#vdialog_inner").css('top', $('body').scrollTop()+20);
		$("#vdialog").show();
		$("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
		$.ajax({
			url:'/AppMonitor/SearchTracking/getFiltersListByIds',
			method : "POST",
			data : {'searchIds':searchIds,'fromDate':convertDateFormat($("#fromDatePicker").val()),'toDate':convertDateFormat($("#toDatePicker").val()),'keyword':keyword}
		}).done(function(res){
			$("#vdialog_content").html(res);
	    });
	}
</script>