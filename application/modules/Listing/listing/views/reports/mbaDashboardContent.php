<tbody>
<?		$i=1;
		$width = array('2%','38%','15%','15%','15%','15%');
		$headingArr = array("#","Period","Registration", "Last Year Reg", "Response", "Last Year Resp");
		foreach($headingArr as $value)
		{
			echo "<th width='".$width[$i-1]."' style='word-break: break-all;background: none repeat scroll 0 0 #D7D6D6;border: 1px solid #898383;'>";
			echo $value;
			echo "</th>";
			$i++;
		}
		
		if(empty($startDate) && empty($endDate)){
			echo "<tr><td colspan=".count($headingArr)."><i>Please select a period first.</i></td></tr>";
		}else{
			$i = 1;
			echo "<tr>";
			echo "<td>".$i++."</td>";
			echo "<td>".date("d M Y",strtotime($startDate))." - ".date("d M Y",strtotime($endDate))."</td>";
			echo "<td>".$reportData['reg_count_current']."</td>";
			echo "<td>".$reportData['reg_count_previous']."</td>";
			echo "<td>".$reportData['response_count_current']."</td>";
			echo "<td>".$reportData['response_count_previous']."</td>";
			echo "</tr>";
		}
		
?>
	</tbody>