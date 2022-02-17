<div><H3><?php echo count($data);?>  Results found</H3></div>
<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
	<tbody>
<?php
		$i=1;
		$width = array('5%','15%','5%','5%','7%','50%','12%');
		$headingArr = array("#","Transaction Id","Status Code", "Host", "Request Method", "Request URL", "Request Time");
		echo "<tr>";
		foreach($headingArr as $value)
		{
			echo "<th width='".$width[$i-1]."'>";
			//echo "<th style='width:".$width[$i-1]."'>";
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
			echo "<td>".($value['transaction_id'])."</td>";
			echo "<td>".($value['status_code'])."</td>";
			echo "<td>".($value['host'])."</td>";
			echo "<td>".($value['request_method'])."</td>";
			echo "<td>".($value['request_uri'])."</td>";
			echo "<td>".($value['request_time'])."</td>";
			echo "</tr>";
			
		}
?>
	</tbody>
	</table>

