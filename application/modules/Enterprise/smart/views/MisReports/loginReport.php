<?php
	if(count($merged) > 0) {
	
		if($reportType != 'cumulative' && $reportType != 'summary'){
		}
		else {
			echo "<span style=\"padding-left:25px; color:#FF0000;font-size:14px;\">
			Intervals having zero count will not be displayed.
			</span>";
		}
		?>



<div id="content-child-wrap">
	<!--Code Starts from here-->
	<div id="smart-content">
	
	<?php
	
		
		foreach($merged as $userId=>$data){
	?>
		<h2>
		<?php
		
		if($userId == 0){
		    echo "<span style=\"color:#848484;font-size:16px;\"> Cumulative Summary </span>";
		    
		}else{
			echo $clientNames[$userId]."<span style=\"color:#848484;font-size:16px;\"> Login and Session Report </span>";	
			}
		?>
		</h2>
		<?php
		foreach($data as $period=>$values){
			list($periodType,$periodStartDate,$periodEndDate) = explode(';',$period);
		?>
		<div class="ent-box full-width">
			<h5>
			<?php echo $periodType."( ";
			echo date("M j Y",strtotime($periodStartDate));
			if($periodEndDate) {
				echo " - ".date("M j Y",strtotime($periodEndDate));
			}
			echo " )";
			?>
			</h5>
			<div class="ent-content" style="height: auto">
				<table class="table-style" cellpadding="0" cellspacing="0">
					
					<tr>
						<th> Login Date (dd:mm:yyyy) </th>
						<th> Login Time (hh:mm:ss) </th>
						<th> Login IP </th>
						<th> Login Location </th>
						<th> Session Established Time </th>
						<?php if($reportType != 'cumulative'){ ?>
						<th> Sales Executive Name </th>
						<?php } ?>
					</tr>
					<?php
					$LoginCount = 0;
					foreach($values as $value){
						
					?>
					<?php echo ($LoginCount%2 == 0) ? '<tr>' : '<tr class="alt-bg">'; ?>
						<td><?php echo (empty($value['activityTime']) ? 0 : date('d-m-Y',strtotime($value['activityTime']))); ?></td>
						<td><?php echo (empty($value['activityTime']) ? 0 : date('h:i:s A',strtotime($value['activityTime']))); ?></td>
						<td><?php echo (empty($value['ipAddress']) ? 0 : $value['ipAddress']); ?></td>
						<td><?php echo (empty($value['location']) ? '' : $value['location']); ?></td>
						<td><?php echo $value['sessionTime']; ?></td>
						<?php if($reportType != 'cumulative'){ ?>
						<td><?php echo ($LoginCount != 0 ? '' : $executiveNames[$clientExecutiveMapping[$userId]]); ?></td>
		
					<?php } ?>
					</tr>
					<?php
					$LoginCount++;
					}
					?>
					
				</table>
			</div>
		</div>
		<?php
		}
		}
	}
	else {
		echo "<br>";
		echo "<span style=\" padding-left:40px; color:#000000;font-size:14px;\">
		No data to show in report.
		</span>";
	
	}
	?>
	<div class="lineSpace_25">&nbsp; </div>
	<div class="lineSpace_25">&nbsp; </div>
	<div class="lineSpace_25">&nbsp; </div>
	<div class="lineSpace_25">&nbsp; </div>
	</div>
	<!--Code Ends here-->
</div>
