	<?php 
	
	if(count($merged) > 0) {
		if($reportType == 'cumulative'){
			echo "<span style=\"color:#848484;font-size:14px;\">
			Time Periods having zero count will not be displayed.
			</span></br>";
		}
		else {
			echo "<span style=\"padding-left:25px; color:#FF0000;font-size:14px;\">
			Clients having zero credits consumed in selected duration will not be displayed.
			</span></br>";
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
			echo "<span style=\"color:#848484;font-size:16px;\"> Credit Report Of </span>".$clientNames[$userId];	
			}
		?>
		</h2>
		<?php
		foreach($data as $period=>$value){
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
						<th> Credits Consumed </th>
						<th> Total Credits Remaining (Now) </th>
						<?php if($reportType != 'cumulative'){ ?>
						<th> Sales Executive Name </th>
						<?php } ?>
					</tr>
					<tr class="alt-bg">
						<td><?php echo (empty($value['creditsConsumed']) ? 0 : $value['creditsConsumed']); ?></td>
						<td><?php echo (empty($value['creditsLeft']) ? 0 : $value['creditsLeft']); ?></td>
						<?php if($reportType != 'cumulative'){ ?>
						<td><?php echo $executiveNames[$clientExecutiveMapping[$userId]]; ?></td>
						<?php } ?>
					</tr>
					
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
