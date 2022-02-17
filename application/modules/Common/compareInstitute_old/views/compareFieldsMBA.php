		<tr id="row1">
			<td width="165" valign="top">
			    <div class="compare-items" style="position:relative"><label>Comparative Rank <i class="compare-sprite info-icon" onmouseover="$j('#rankTooltip').show();" onmouseout="$j('#rankTooltip').hide();"></i></label>
				    
				<div class="compare-tooltip" style="display: none;" id="rankTooltip">
				    <i class="compare-sprite tooltip-arr"></i>
				    <p>Ranking of India's top 100 MBA colleges from published sources</p>
				</div>
    
			    </div>
			</td>
			<?php
			$j = 0;$k = 0;
			foreach($institutes as $institute){
				$k++;
				$course = $institute->getFlagshipCourse();
				if(NEW_RANKING_PAGE && !empty($rankings[$course->getId()])) {
					$j++;?>
					<td width="165" valign="top" align="center" <?php if($k==4){echo "class='last'";} ?>>
                        <div class="compare-items">
                            <ul>
								<?php
								$i = 0;
								foreach($rankings[$course->getId()] as $sourceRank) {
									$class = "";
									if($i == count($rankings[$course->getId()]) -1) {
										$class = "class='last'";
									} ?>
									<li <?php echo $class; ?> >
										<span><?php if($sourceRank['rank'] != 'NA') { ?>
												<a href="<?php echo $sourceRank['ranking_page_url']; ?>">
											<?php }
												echo $sourceRank['source_name'];
											if($sourceRank['rank'] != 'NA') { ?> </a> <?php } ?>
										</span>
										<a href="<?php echo $sourceRank['ranking_page_url']; ?>" class="round-rank"><?php echo $sourceRank['rank']; ?></a>
									</li>
								<?php $i++; } ?>
                            </ul>
                        </div>
                    </td>
				<?php }
				else if(!NEW_RANKING_PAGE && isset($rankings[$course->getId()]['course_rank']) && $rankings[$course->getId()]['course_rank']>0){
					$j++;?>
					<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
						<div class="compare-items">
						<span class="rank-nmbr"><?=$rankings[$course->getId()]['course_rank']?> <i class="compare-sprite rank-icon"></i></span>
						<?php if($rankings[$course->getId()]['ranking_page_text']!=''){ echo "<div style='color: grey; font-size: 11px;margin-top:5px;'>(in ".$rankings[$course->getId()]['ranking_page_text'].")</div>";} ?>
						</div>
					</td>
				<?php }
				else { ?>
					<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
						<strong style="font-size:22px; color:#828282">-</strong>
					</td>
				<?php }
			}
			if($j == 0){
				echo '<td id="hidetr_1"/></td>';
			}
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $k; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					echo '>&nbsp;</td>';
				}
			}
			?>
		</tr>

               
                <tr id="row2">
                    <td width="165" valign="top">
                        <div class="compare-items" style="position:relative"><label style="margin-bottom:15px">Alumni Salary (INR) <i class="compare-sprite info-icon" onmouseover="$j('#alumniSalaryTooltip').show();" onmouseout="$j('#alumniSalaryTooltip').hide();"></i></label>
				
                            <div class="compare-tooltip" style="display: none; left:107px;" id="alumniSalaryTooltip">
                        		<i class="compare-sprite tooltip-arr"></i>
                                <p>The annual salary of alumni from these institutes</p>
                            </div>
				
                        <p class="source-link">Data Source : </p><i class="compare-sprite sml-naukri-logo"></i>
                        </div>
                    </td>
                    <td valign="top" colspan="4" class="graph-box">
			
			<?php
			    $instituteArray = array();
			    foreach($institutes as $institute){
				$course = $institute->getFlagshipCourse();
				$instituteId = $course->getInstId();
				array_push($instituteArray,$instituteId);
			    }
                            echo Modules::run('compareInstitute/compareInstitutes/createBarChart', $instituteArray);
			?>
                    </td>
		</tr>

                <tr id="row10">
			<td width="165" valign="top">
			    <div class="compare-items" style="position:relative"><label style="margin-bottom:15px">Alumni Employed At <i class="compare-sprite info-icon" onmouseover="$j('#alumniEmployed').show();" onmouseout="$j('#alumniEmployed').hide();"></i></label>
				    
				<div class="compare-tooltip" style="display: none; left:110px;" id="alumniEmployed">
					    <i class="compare-sprite tooltip-arr"></i>
				    <p>Companies where the alumni currently work</p>
				</div>
				    
			    <p class="source-link">Data Source : </p><i class="compare-sprite sml-naukri-logo"></i>
			    </div>
			</td>

			<?php
			$j = 0;$k = 0;
			foreach($institutes as $institute){
				$k++;
				$course = $institute->getFlagshipCourse();				
				if(isset($naukri_employees_data[$course->getId()]) && count($naukri_employees_data[$course->getId()]) > 0 ){
					$j++;
			?>
			<td valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <div class="compare-items">
				<?php
				    $x = 0;
				    foreach ($naukri_employees_data[$course->getId()] as $company){
					if($x == 5){
					    echo "<div id='viewMoreLink".$course->getId()."'><a href='javascript:void(0)' onClick='unhideCompanies(".$course->getId().");'>+ View More</a></div>";
					    echo "<div id='companyNames".$course->getId()."' style='display:none;'>";
					}
				?>
				<div style="background:url(/public/images/co_dot.jpg) left 6px no-repeat;padding-left:8px;margin-bottom:2px;">
					<p class="percentile" style="margin-bottom: 0px;"><?php echo $company['comp_label']; ?></p><br/>
				</div>
				<?php $x++; }
				if($x>=5){ echo "</div>";}
				?>
			    </div>
			</td>
			<?php }else{ ?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <strong style="font-size:22px; color:#828282">-</strong>
			</td>
			<?php } }
			if($j == 0){
				echo '<td id="hidetr_10"/></td>';
			}
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $k; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					echo '>&nbsp;</td>';
				}
			}
			?>
		</tr>

		<tr id="row3">
			<td width="165" valign="top">
			    <div class="compare-items"><label>Exam Required &<br />
						Cut-off</label></div>
			</td>
			<?php
			$j = 0;$z = 0;
			global $exam_weightage_array;
			foreach($institutes as $institute){
				$z++;
				$course = $institute->getFlagshipCourse();
				$eligibilities = $course->getEligibilityExams();
				$eligibilitiesArr = array();
				
				foreach($eligibilities as $k=>$v)
				{
				    if($eligibilities[$k]->getMarksType()=='percentile' && !in_array($eligibilities[$k]->getAcronym(),$GLOBALS['MBA_EXAMS_REQUIRED_SCORES']))
				    {
					array_push($eligibilitiesArr,array('Acronym'=>$eligibilities[$k]->getAcronym(), 'Percentile'=>$eligibilities[$k]->getMarks(), 'Weightage'=>$exam_weightage_array[$eligibilities[$k]->getAcronym()], 'MarksUnit'=>'%tile'));
				    }
				    else if($eligibilities[$k]->getMarksType()=='total_marks' && in_array($eligibilities[$k]->getAcronym(),$GLOBALS['MBA_EXAMS_REQUIRED_SCORES']))
				    {
					array_push($eligibilitiesArr,array('Acronym'=>$eligibilities[$k]->getAcronym(), 'Percentile'=>$eligibilities[$k]->getMarks(), 'Weightage'=>$exam_weightage_array[$eligibilities[$k]->getAcronym()], 'MarksUnit'=>'Marks'));
				    }
				}
				//sort by exam priority order(weightage)
				usort($eligibilitiesArr,function($a, $b)
				{
				    return ($a['Weightage'] < $b['Weightage']);
				});
				$eligibilities = $eligibilitiesArr;
				if(count($eligibilities)>0)
				{
				    $widgetData['eligibility'] = array('completeEligilibilityData'=>$eligibilities,
								       'examRequiredHTML'=>'');
				    foreach($eligibilities as $k=>$eligibility)
				    {
    					$widgetData['eligibility']['examRequiredHTML'] .= "<p class='percentile'>".$eligibility['Acronym']." ".($eligibility['Percentile']>0?"<span>".$eligibility['Percentile']."</span>":"").($eligibility['Percentile']>0 && $eligibility['Percentile']!="No Exam Required" ?" ".$eligibility['MarksUnit']:"")."</p>";
				    }
				    $numAvailableCourseWidgets++;
				}
				else
				{
				    $widgetData['eligibility'] = 0;
				}
				
				if($widgetData['eligibility'] != 0){
					$j++;
			?>
			<td align="center" valign="top" <?php if($z==4){echo "class='last'";} ?>>
			    <div class="compare-items">
				<?php
					echo $widgetData['eligibility']['examRequiredHTML'];
				?>
			    </div>
			</td>
			<?php }else{ ?>
			<td align="center" valign="top" <?php if($z==4){echo "class='last'";} ?>>
			    <strong style="font-size:22px; color:#828282">-</strong>
			</td>
			<?php } }
			if($j == 0){
				echo '<td id="hidetr_3"/></td>';
			}
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $z; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					echo '>&nbsp;</td>';
				}
			}
			?>
		</tr>

		<tr id="row4">
			<td width="165" valign="top">
			    <div class="compare-items"><label>Course Fees</label></div>
			</td>
			<?php
			$j = 0;$k = 0;
			foreach($institutes as $institute){
				$k++;
				$course = $institute->getFlagshipCourse();
				if($course->getFees()->getValue()){
					$j++;
					
					if($course->getFees()->getValue() >= 100000)
					{
					    $feevalue =round(($course->getFees()->getValue()/100000),1);
					    $feeunit  = ($course->getFees()->getValue() == 100000)?"Lac":"Lacs";
					}
					else
					{
					    $feevalue = moneyFormatIndia($course->getFees()->getValue());
					    $feeunit  = "";
					}
				    ?>
					
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <div class="compare-items">
				<p class="percentile"><?=$course->getFees()->getCurrency()?> <span><?=$feevalue?></span> <?=$feeunit?></p>
			    </div>
			</td>
			<?php }else{ ?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <strong style="font-size:22px; color:#828282">-</strong>
			</td>
			<?php } }
			if($j == 0){
				echo '<td id="hidetr_4"/></td>';
			}
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $k; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					echo '>&nbsp;</td>';
				}
			}
			?>
		</tr>
                
                
		<tr id="row5">
			<td width="165" valign="top">
			    <div class="compare-items"><label>Affiliation</label></div>
			</td>
			<?php
			$j = 0;$k = 0;
			foreach($institutes as $institute){
				$k++;
				$course = $institute->getFlagshipCourse();
				$affiliations = $course->getAffiliations();
				foreach($affiliations as $affiliation) {
					$Affiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);
				}
				if($Affiliations[0]){
					$j++;
			?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <div class="compare-items" style="font-size: 14px;color:#565656;">
				<i class="compare-sprite tick-icon"></i><?=$Affiliations[0]?>
			    </div>
			</td>
			<?php }else{ ?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <strong style="font-size:22px; color:#828282">-</strong>
			</td>
			<?php }
			    unset($Affiliations);
			}
			if($j == 0){
				echo '<td id="hidetr_5"/></td>';
			}
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $k; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					echo '>&nbsp;</td>';
				}
			}
			?>
		</tr>

		<?php
		    $this->load->view('compareBackupFieldsMBA');
		?>

		<script>
		//Hide all the Backup fields
		for(var i=10; i<15 ; i++){
			if($("row"+i)){
				$('row'+i).style.display = 'none';
			}
		}

		for(var i=0; i<10 ; i++){
			//Field Id 1-9 can be main fields for the MBA comparison
			//Field Id 10 and onwards are Backup fields for MBA
			//If any of the Main fields is getting hidden, we will show the Backup field as per priority			
			if($("hidetr_"+i)){
				$('row'+i).style.display = 'none';
				activateBackupField();
			}
		}
		
		function activateBackupField() {
		    var backupFieldActivated = 10;
		    for(var z=backupFieldActivated; z<15 ; z++){
			    if( $("row"+z) && !($('hidetr_'+z)) && $('row'+z).style.display == 'none' ){
				    $('row'+z).style.display = '';				    
				    break;
			    }
			    
		    }		    		    
		}
		
		</script>
		
		
