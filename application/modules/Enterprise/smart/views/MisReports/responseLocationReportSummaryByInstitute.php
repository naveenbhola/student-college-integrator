<?php
    if(count($merged) > 0) {
   
        ?><div id="content-child-wrap">
            <div id="smart-content">
                <div class="ent-box full-width">
                   <div class="ent-content" style="height:auto; overflow-x:auto; border: 0 none">
                	<table id="reportResult" width="2000px" class="table-style2" border="0" cellpadding="0" cellspacing="0" >
                                <thead>
                                <tr>
                                    <th >Client Name</th>
                                    <th >Institute Name</th>
                                    <th >Response Location</th>
                                    <th >Response <br />Counts</th>
                                    <?php if (!empty($salesUser)) { ?>
                                    <th >Expected <br />Responses</th>
                                    <th >Date Range in which to achieve expected responses</th>
                                    <th >Suggested Daily Run Rate of Responses</th>
                                    <th >Achieved Run Rate in Selected Duration</th>
                                    <th >Shortfall</th>
                                    <?php } ?>
                                 </tr>
                                </thead>
                                <tbody>
                                <?php
                                $rowshade = false;
                                
                                foreach($merged as $clientId=>$clientData){
                                $clientVar = 0;                                
                                if(!$rowshade){
                                                $rowshade = true;
                                            }else{
                                                $rowshade = false;
                                            }

								foreach($clientData as $instituteId=>$locationWisedata){
                                $instituteVar = 0;
                                foreach($locationWisedata as $Location=>$data){                                          
                                ?>

                                <?php echo ($rowshade == true) ? '<tr>' : '<tr class="alt-bg">'; ?>
                                    <td><?php echo (($clientVar != 0) ? '' : $clientNames[$clientId]); ?></td>
                                    <td><?php echo (($instituteVar != 0) ? '' : $instituteTitleMap[$instituteId]['instituteTitle']); ?></td>
                                    <td><?php echo (empty($Location) ? 'Others' : $Location); ?></td>                                        
                                    <td><?php echo (empty($data['totalResponses']) ? 0 : $data['totalResponses']); ?></td>
                                    
                                    <?php if (!empty($salesUser) ) { ?>
                                    
                                    
                                    <td><?php echo (($instituteVar != 0) ? '' : $data['ExpectedResponses']); ?></td>
                                    <td><?php if($instituteVar == 0 && !empty($data['DateRange'])){
                                    list($periodType,$periodStartDate,$periodEndDate) = explode(';',$data['DateRange']);
                                     echo $periodType."( ";
                                            echo date("M j Y",strtotime($periodStartDate));
                                            if($periodEndDate) {
                                                    echo " - ".date("M j Y",strtotime($periodEndDate));
                                            }
                                            echo " )";
                                        }else{

                                            echo "";
                                        }
                                    
                                     ?></td>
                                    
                                    <td><?php echo (($instituteVar != 0) ? '' : $data['SuggestedDailyRunRateofResponses']); ?></td>
                                    <td><?php echo (($instituteVar != 0) ? '' : $data['AcheivededDailyRunRateofResponses']); ?></td>
                                    <td><?php echo (($instituteVar != 0) ? '' : $data['Shortfall']); ?></td>
                                   <?php
                                        if($instituteVar == 0){
                                                $TotalExpectations += $data['ExpectedResponses'];
                                                $TotalSuggestedDailyRunRateofResponses += $data['SuggestedDailyRunRateofResponses'];
                                                $TotalAcheivededDailyRunRateofResponses += $data['AcheivededDailyRunRateofResponses'];
                                                $TotalShortfall += $data['Shortfall'];
                                            }
                                   } ?>
                                </tr>
                                <?php
				            $Totalresponses += $data['totalResponses'];
				
                                $clientVar++;$instituteVar++;           
                         }  
                        }
                    }
            ?>
                   <tr>
				<td style="font-size:16px; font-weight: bold;">TOTAL</td>
				<td></td>
                                <td></td>
				<td><?php echo (empty($Totalresponses) ? 0 : $Totalresponses); ?></td>
				<?php if (!empty($salesUser)) { ?>
				<td><?php echo (empty($TotalExpectations) ? 0 : $TotalExpectations); ?></td>
				<td></td>
				<td><?php echo (empty($TotalSuggestedDailyRunRateofResponses) ? 0 : $TotalSuggestedDailyRunRateofResponses); ?></td>
				<td><?php echo (empty($TotalAcheivededDailyRunRateofResponses) ? 0 : $TotalAcheivededDailyRunRateofResponses); ?></td>
				<td><?php echo (empty($TotalShortfall) ? 0 : $TotalShortfall); ?></td>
				<?php } ?>
				</tr>
                    </tbody>
                    </table>
                    </div>
                </div>  
            </div>
          <?php       
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
</div>
