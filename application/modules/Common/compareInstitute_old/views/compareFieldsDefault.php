
        <?php $isSAComparePage = 0; ?>
        
	<?php if($institutes && count($institutes)>0){ ?>
	
	<tr id="row1">
		<td width="165" valign="top"><div class="compare-items"><label>AIIMA Rating</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			if($institute->getAIMARating()){
				$j++;
		?>
		<td width="165" align="center" valign="top" <?php if($k==4){echo "class='last'";} ?> >
			<div class="compare-items" style="font-size: 14px;color:#565656;"><span class="ratingBox"><?=$institute->getAIMARating()?></span></div>
		</td>
		<?php }else{ ?>
		<td width="165" align="center" valign="top" <?php if($k==4){echo "class='last'";} ?> ><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_1"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
        
	<tr id="row2">
		<td width="165" valign="top"><div class="compare-items"><label>Alumni Rating</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			if($institute->getAlumniRating()){
				$j++;
		?>
		<td width="165" align="center" valign="top" <?php if($k==4){echo "class='last'";} ?> >
			<span>
				<?php
				$i = 1;
				while($i <= $institute->getAlumniRating()){
				?>
					<img border="0" src="/public/images/nlt_str_full.gif">
				<?php
					$i++;
				}
				?>
			</span>
			<span class="rateNum" style="font-size: 14px;color:#565656;">&nbsp;<?=$institute->getAlumniRating()?>/5</span>
		</td>
		<?php }else{ ?>
		<td width="165" align="center" valign="top" <?php if($k==4){echo "class='last'";} ?> ><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_2"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
        
	<tr id="row17">
		<td valign="top"><div class="compare-items"><label>Course Duration</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			$k++;
			if($course->getDuration() && $course->getDuration()!=""){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?> ><div class="compare-items" style="font-size: 14px;color:#565656;"><?=$course->getDuration()?></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?> ><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_17"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
        
	<tr id="row18">
		<td valign="top"><div class="compare-items"><label>Mode of Study</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->getCourseType()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items" style="font-size: 14px;color:#565656;"><?=$course->getCourseType()?></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_18"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
        
	<tr id="row19">
		<td valign="top"><div class="compare-items"><label>Affiliated To</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			$affiliations = $course->getAffiliations();
			if(count($affiliations) > 0){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
		<div class="compare-items" style="font-size: 14px;color:#565656;">
		<?php
		foreach($affiliations as $affiliation) {
			echo '<div class="co_dot">'.langStr('affiliation_'.$affiliation[0],$affiliation[1])."</div>";
		}
		?>
		</div>
		</td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_19"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
        
	<tr id="row3">
		<td valign="top"><div class="compare-items"><label>Average Salary (p.a.)</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->getAverageSalary()){
			$j++;
		?>
		<?php $salArray = $course->getSalary();?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items" style="font-size: 14px;color:#565656;"><?php echo  $salArray['currency']." ".number_format($course->getAverageSalary()/100000,2)." Lacs "; ?></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_3"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
                
	<tr id="row4">
		<td valign="top"><div class="compare-items"><label>Top Recruiting Companies</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if(($recruitingCompanies = $course->getRecruitingCompanies())&&
			   (!(count($recruitingCompanies) == 1 && !$recruitingCompanies[0]->getName()))){
				$j++;
		?>
		<td valign="top" <?php if($k==4){echo "class='last'";} ?>>
			<div class="compare-items" style="font-size: 14px;color:#565656;">
			<?php
				$cCount = 0;
				foreach($recruitingCompanies as $company){
					if($cCount>=10) break;
					if($cCount == 5){
					    echo "<div id='viewMoreLink".$course->getId()."'><a href='javascript:void(0)' onClick='unhideCompanies(".$course->getId().");'>+ View More</a></div>";
					    echo "<div id='companyNames".$course->getId()."' style='display:none;'>";
					}
			?>
			<div style="background:url(/public/images/co_dot.jpg) left 6px no-repeat;padding-left:8px;margin-bottom:2px"><?=$company->getName();?></div>
			<?php
				$cCount++;
				}
				if($cCount>=5){ echo "</div>";}
			?>
			</div>
		</td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_4"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>

	<tr id="row5">
		<td valign="top"><div class="compare-items"><label>AICTE Approved</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->isAICTEApproved()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items" ><i class="compare-sprite tick-icon"></i></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_5"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>         
	</tr>
        
	<tr id="row6">
		<td valign="top"><div class="compare-items"><label>UGC Recognised</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->isUGCRecognised()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items" ><i class="compare-sprite tick-icon"></i></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_6"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>     
	</tr>
        
	<tr id="row20">
		<td valign="top"><div class="compare-items"><label>DEC Approved</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->isDECApproved()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items"><i class="compare-sprite tick-icon"></i></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_20"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>     
	</tr>
        
	<tr id="row7">
		<td valign="top"><div class="compare-items"><label>Fees</label></div></td>
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
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_7"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
        
	<tr id="row8">
		<td valign="top"><div class="compare-items"><label>Eligibility</label></div></td>
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
					$widgetData['eligibility']['examRequiredHTML'] .= "<div class='co_dot' style='margin-bottom:5px;'>".$eligibility['Acronym']." ".($eligibility['Percentile']>0?$eligibility['Percentile']:"").($eligibility['Percentile']>0 && $eligibility['Percentile']!="No Exam Required" ?" ".$eligibility['MarksUnit']:"")."</div>";
					if($k!=(count($eligibilities)-1))
					{
					    //$widgetData['eligibility']['examRequiredHTML'] .= "<br/>";
					}
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
		<td align="center" valign="top" <?php if($z==4){echo "class='last'";} ?> class="compare-items" style="font-size: 15px;color:#565656;">
		<?php
		echo $widgetData['eligibility']['examRequiredHTML'];
		if($course->getOtherEligibilityCriteria()!=''){
			echo '<div class="co_dot" style="margin-bottom:5px;">'.$course->getOtherEligibilityCriteria().'</div>';
		}
		?>
		</td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($z==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_8"/></td>';
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
        
	<tr id="row9">
		<td valign="top"><div class="compare-items"><label>Dual Degree</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($sf = $course->offersDualDegree()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items" style="font-size: 14px;color:#565656;"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_9"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?> 
	</tr>

        <?php if($isSAComparePage == 0){ ?>	
	<tr id="row10">
		<td valign="top"><div class="compare-items"><label>Foreign Travel</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($sf = $course->offersForeignTravel()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?> style="font-size: 14px;color:#565656;"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></td>
		<?php }elseif($sf = $course->offersForeignExchange()){
			$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?> style="font-size: 14px;color:#565656;"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></td>
		<?php }else{ ?>
		<td valign="top" align="center"><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_10"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
	<tr id="row11">
		<td valign="top"><div class="compare-items"><label>Free Laptop</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->providesFreeLaptop()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items"><i class="compare-sprite tick-icon"></i></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_11"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top"  style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>  
	</tr>
        <?php } // End of if($isSAComparePage==0). ?>
        
	<tr id="row12">
		<td valign="top"><div class="compare-items"><label>In-Campus Hostel </label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->providesHostelAccomodation()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items"><i class="compare-sprite tick-icon"></i></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_12"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?> 
	</tr>
        
        <?php if($isSAComparePage==0){ ?>
	<tr id="row13">
		<td valign="top"><div class="compare-items"><label>Transport Facility</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->providesTransportFacility()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items"><i class="compare-sprite tick-icon"></i></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
			if($j == 0){
			echo '<td id="hidetr_13"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?> 
	</tr>
        
	<tr id="row14">
		<td valign="top"><div class="compare-items"><label>Wifi Campus</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->hasWifiCampus()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items"><i class="compare-sprite tick-icon"></i></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
			if($j == 0){
			echo '<td id="hidetr_14"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?>
	</tr>
        
	<tr id="row15">
		<td valign="top"><div class="compare-items"><label>AC Campus</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($course->hasACCampus()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items"><i class="compare-sprite tick-icon"></i></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
			if($j == 0){
			echo '<td id="hidetr_15"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?> 
	</tr>
        
	<tr id="row16">
		<td valign="top"><div class="compare-items"><label>Free Training</label></div></td>
		<?php
		$j = 0;$k = 0;
		foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			if($sf = $course->getFreeTrainingProgram()){
				$j++;
		?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items" style="font-size: 14px;color:#565656;"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div></td>
		<?php }else{ ?>
		<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
		<?php } }
			if($j == 0){
			echo '<td id="hidetr_16"/></td>';
		}
		if($j<4){	//Case when Compare tool has less than 4 courses to compare
			for ($x = $k+1; $x <=4; $x++){
				echo '<td width="165" align="center" valign="top" style="border:0px;" ';
				if($x==4){echo "class='last'";}
				echo '>&nbsp;</td>';
			}
		}
		?> 
	</tr>
        <?php } // End of if($isSAComparePage==0).

	if($isSAComparePage == 1){ 
        ?>
	<tr id="row21">
		<td valign="top"><div class="compare-items"><label>Starts in</label></div></td>
			<?php
				$j = 0;$k = 0;
				foreach($institutes as $institute){
					$k++;
					$course = $institute->getFlagshipCourse();
					$sf = $course->getDateOfCommencement();
					if($sf){
						$j++;
			?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><div class="compare-items"><?=date('F, Y',strtotime($sf))?></div></td>
			<?php }else{ ?>
						<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>><strong style="font-size:22px; color:#828282">-</strong></td>
					<?php } 
				}
				if($j == 0){
					echo '<td id="hidetr_21"/></td>';
				}
				if($j<4){	//Case when Compare tool has less than 4 courses to compare
					for ($x = $k+1; $x <=4; $x++){
						echo '<td width="165" align="center" valign="top" style="border:0px;" ';
						if($x==4){echo "class='last'";}
						echo '>&nbsp;</td>';
					}
				}
			?> 
	</tr>
        <?php }?>

	<?php } ?>
<script>
for(var i=0; i<22 ; i++){
        if($("hidetr_"+i)){
                $('row'+i).style.display = 'none';
        }
}    
</script>