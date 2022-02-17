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
		
		if($reportType == 'cumulative'){
			echo "<span style=\"color:#848484;font-size:16px;\"> Cumulative Summary </span>";
		}
		else{
			echo $clientNames[$userId]."<span style=\"color:#848484;font-size:16px;\"> Lead Report </span>";
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
						<th>Lead Genie Name</th>
						<th>Lead allocated to genie</th>
						<?php if($salesUser == 'Admin'){ ?>
						<th>Leads Matched</th>
						<?php } ?>
						<th>Credits consumed by Genie(s) in time period</th>
						
					</tr>
					
					<?php
					$searchAgentCount = 0;
					foreach($values as $searchAgentId=>$value){
					?>
					
					<?php echo ($searchAgentCount%2 == 0) ? '<tr>' : '<tr class="alt-bg">'; ?>
						<td><?php echo (($reportType == 'cumulative') ? 'All Selected Agents' : $searchAgentsMap[$userId][$searchAgentId]['name']); ?></td>
						<td><?=$value['leadsAllocated'];?></td>
						<?php if($salesUser == 'Admin'){ ?>
						<td><?=$value['leadsMatched'];?></td>
						<?php } ?>
						<td><?=$value['credits'];?></td>
					</tr>
					<?php
					$searchAgentCount++;
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
