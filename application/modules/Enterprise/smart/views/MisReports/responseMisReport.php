<?php	
	if(count($merged) > 0) {
   ?>

<div id="content-child-wrap">
	<div id="smart-content">
              <div  class="ent-content" style="height:auto; overflow-x:auto; border: 0 none">
                		
                        <table id="reportResult" width="1400px" class="table-style2" border="0" cellpadding="0" cellspacing="0" >
					<thead>
					<tr>
						<?php if($reportType == 'daily'){ ?>
						<th>Day</th>
						<?php } ?>
						<?php if($reportType == 'weekly'){ ?>
						<th>Week</th>
						<?php } ?>
						<?php if($reportType == 'monthly'){ ?>
						<th>Month</th>
						<?php } ?>
						<?php if($reportType == 'quarterly'){ ?>
						<th>Quarter</th>
						<?php } ?>
						<?php if($reportType == 'yearly'){ ?>
						<th>Year</th>
						<?php } ?>
						<th>Client Name</th>
						<th>Institute Name</th>

						<th>Course Name</th>
						<th>Response Count</th>
					</tr>
					</thead>
					<tbody>
            
                            <?php
                                $rowshade = true;
                                foreach($merged as $clientId=>$clientData){
                            	if(!$rowshade){
                                                $rowshade = true; 
                                            }else{
                                                $rowshade = false;
                                            }
					$clientVar = 0;
				    			foreach($clientData as $instituteId=>$courses){
				    			$instituteVar = 0;	
						foreach($courses as $courseTitle=>$courseData){
						foreach($courseData as $period=>$responses){
						    list($periodType,$periodStartDate,$periodEndDate) = explode(';',$period);
                                          
                                                
                            ?>
					
					<?php echo ($rowshade == true) ? '<tr>' : '<tr class="alt-bg">'; ?>
                                        <?php if($reportType == 'daily' || $reportType == 'weekly' || $reportType == 'monthly' || $reportType == 'quarterly' || $reportType == 'yearly'){ ?>
					<td>
                                                        <?php
                                            echo date("M j Y",strtotime($periodStartDate));
                                            if($periodEndDate) {
                                                    echo " - ".date("M j Y",strtotime($periodEndDate));
                                            }
                                          
                                            ?>
                                        </td>
					<?php } ?>
					
					
					<td><?php echo (($clientVar != 0) ? '' : $clientNames[$clientId]); ?></td>
					<td><?php echo (($instituteVar != 0) ? '' : $instituteTitleMap[$instituteId]['instituteTitle']); ?></td>
                                        <td><?php echo (empty($courseTitle) ? 0 : $courseInstituteMap[$courseTitle]['courseTitle']); ?></td>                                       
                                        <td><?php echo (empty($responses) ? 0 : $responses); ?></td>
					<?php
					$Totalresponses += $responses;
					?>
                                    </tr>                    
                    <?php				
                       $clientVar++;$instituteVar++;
                        }
			}
                    }
               } ?>
			<tr>
				<td style="font-size:16px; font-weight: bold;">TOTAL</td>
				<td></td>
				<td></td>
				 <?php if($reportType == 'daily' || $reportType == 'weekly' || $reportType == 'monthly' || $reportType == 'quarterly' || $reportType == 'yearly'){ ?>
				<td></td>
				<?php } ?>
				<td><?php echo (empty($Totalresponses) ? 0 : $Totalresponses); ?></td>
			</tr>
					</tbody>
	   <?php    
	}
	else {
		echo "<br>";
		echo "<span style=\" padding-left:40px; color:#000000;font-size:14px;\">
		No data to show in report.
		</span>";
	
	}
	?>
                                </table>                          
	</div></div>
        <div class="lineSpace_25">&nbsp; </div>
	<div class="lineSpace_25">&nbsp; </div>
	<div class="lineSpace_25">&nbsp; </div>
	<div class="lineSpace_25">&nbsp; </div>
	</div>
