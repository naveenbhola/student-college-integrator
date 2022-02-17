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




	<div id="smart-content">
	<?php
        
		foreach($merged as $userId=>$data){
	?>
		<h2>
		<?php
		
		if($userId == 0){
		    echo "<span style=\"color:#848484;font-size:16px;\"> Cumulative Summary </span>";
		    
		}else{
			echo "<span style=\"color:#848484;font-size:16px;\"> Porting Report Of </span>".$clientNames[$userId];	
			}
		?>
		</h2>
		<?php
		foreach($data as $period=>$content){
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
						<th> Porting Agent</th>
						<?php if($portingType == 'lead') { ?>
						<th> Leads Ported </th>
						<?php } else if($portingType == 'response') { ?>
						<th> Responses Ported </th>
                        <?php } ?>                   
					</tr>
                                        
                                        <?php
                                        $portingCount = 0;
                                        foreach($content['portings'] as $portingname=>$count){
                                        ?>
					
					<?php echo ($portingCount%2 == 0) ? '<tr>' : '<tr class="alt-bg">'; ?>
						<td><?php echo (empty($portingname) ? '' : $portingname); ?></td>
						<td><?php echo (empty($count) ? '' : $count); ?></td>
						
					</tr>
					<?php
                                        $portingCount++;
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
	