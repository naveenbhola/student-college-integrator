<script>var fieldSet = new Array();</script>
<tr id="row1_H">
	<td colspan="2" class="compare-title"><h2>Comparative Rank<span style="color:#a1a1a1; font-size:1.2em">*</span></h2></td>
</tr>
<tr id="row1_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
		if(NEW_RANKING_PAGE && !empty($rankings[$course->getId()])) {
			$j++;
			if($k==2){
				$class = "class = 'last'";
			} else {
				$class = "class = 'border-right'";
			}?>
			<td width="165" valign="top" align="center" <?php echo $class; ?>>
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
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><span style="font-size:1.2em"><?=$rankings[$course->getId()]['course_rank']?></span><i class="sprite college-rank-icon"></i></div>
			<?php if($rankings[$course->getId()]['ranking_page_text']!=''){ echo "<div style='color: grey; font-size: 11px;margin-top:5px;'>(in ".$rankings[$course->getId()]['ranking_page_text'].")</div>";} ?>		    
		</td>
		<?php
	    }
	    else{
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <strong style="font-size:22px; color:#828282">-</strong>
		</td>
		<?php
	    }
	}
	if($j==0){
		echo "<script>fieldSet.push('1');</script>";
	}
	if($j < $compare_count_max)
	{
	    for ($x = $k+1; $x <=$compare_count_max; $x++)
	    {
		?>
		<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
		<?php
	    }
	}
    }
    ?>
</tr>

<tr id="row1_F">
<td colspan="2" class="message">*Ranking of India's top 100 MBA colleges from published sources</td>

</tr>

<tr id="row2_H">
	<td colspan="2" class="compare-title" ><div style="position: relative;"><h2>Alumni Salary (INR)<span style="color:#a1a1a1; font-size:1.2em">*</span></h2>
	    
	</div>
	</td>
</tr>
<tr id="row2_C">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	$instArr = array();
	foreach($institutes as $institute){
	    $instArr[] = $institute->getId();
	    //$k++;
	    
	}
	echo Modules::run('mCompareInstitute5/compareInstitutes/mCreateSalaryChart', $instArr);
    }
    ?>
</tr>

<tr id="row2_F" style="position: relative;">
	
	<td colspan="2" class="message">
		<div class="data-source-col flRt">
			<span class="flLt" style="margin-right:5px;">Data Source :</span><i class="sprite data-source"></i>
		</div>
		<br/>
		<p>*The annual salary of alumni from these colleges</p>
		
		
		<!--<div class="data-source-col">
            	<p>Data Source:</p>
                <i class="sprite data-source"></i>
            </div>
		*The annual salary of alumni from these colleges--></td>
</tr>
<tr>
	<td colspan="2" class="compare-title">
		<?php $this->load->view('mNaukriTool5/widgets/careerCompassWidget',array('pageName'=>'COMPARE_COLLEGE')); ?>
	</td>
</tr>


<tr id="row10_H">
	<td colspan="2" class="compare-title"><h2>Alumni Employed At<span style="color:#a1a1a1; font-size:1.2em">*</span></h2></td>
</tr>
<tr id="row10_C">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	
	    $course = $institute->getFlagshipCourse();
	    if(isset($naukri_employees_data[$course->getId()]) && count($naukri_employees_data[$course->getId()]) > 0){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>" style="text-align:left;">
		    <div class="college-rank">
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
		<?php
	    }
	    else{
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <strong style="font-size:22px; color:#828282">-</strong>
		</td>
		<?php
	    }
	}
	if($j==0){
		echo "<script>fieldSet.push('10');</script>";
	}
	if($j < $compare_count_max)
	{
	    for ($x = $k+1; $x <=$compare_count_max; $x++)
	    {
		?>
		<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
		<?php
	    }
	}
    }
    ?>
</tr>
<tr id="row10_F">
	<td colspan="2" class="message"><div class="data-source-col flRt">
			<span class="flLt" style="margin-right:5px;">Data Source :</span><i class="sprite data-source"></i>
		</div>
		<br/>
		<p>*Companies where the alumni currently work</p></td>
</tr>


<tr id="row5_H">
	<td colspan="2" class="compare-title"><h2>Exam Required & Cut-off</h2></td>
</tr>
<tr id="row5_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
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
		$widgetData['eligibility']['examRequiredHTML'] .= "<p>".$eligibility['Acronym']." ".($eligibility['Percentile']>0?"<span>".$eligibility['Percentile']."</span>":"").($eligibility['Percentile']>0 && $eligibility['Percentile']!="No Exam Required" ?" ".$eligibility['MarksUnit']:"")."</p>";
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
		<td class="<?php echo ($z<$compare_count_max)?'border-right':'';?>">
		    <div class="compare-list">
		    <?php
			echo $widgetData['eligibility']['examRequiredHTML'];
		    ?>
		    </div>
		</td>
		<?php
	    }
	    else{
		?>
		<td class="<?php echo ($z<$compare_count_max)?'border-right':'';?>">
		    <strong style="font-size:22px; color:#828282">-</strong>
		</td>
		<?php
	    }
	}
	if($j==0){
		echo "<script>fieldSet.push('5');</script>";
	}
	if($j < $compare_count_max)
	{
	    for ($x = $z+1; $x <=$compare_count_max; $x++)
	    {
		?>
		<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
		<?php
	    }
	}
    }
    ?>
</tr>

<tr id="row6_H">
	<td colspan="2" class="compare-title"><h2>Course Fees</h2></td>
</tr>
<tr id="row6_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
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
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="compare-list"><p><strong style="font-size:0.8em; font-weight:normal"><?=$course->getFees()->getCurrency()?></strong> <span><?=$feevalue?> <?=$feeunit?></span></p></div>
		</td>
		<?php
	    }
	    else{
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <strong style="font-size:22px; color:#828282">-</strong>
		</td>
		<?php
	    }
	}
	if($j==0){
		echo "<script>fieldSet.push('6');</script>";
	}
	if($j < $compare_count_max)
	{
	    for ($x = $k+1; $x <=$compare_count_max; $x++)
	    {
		?>
		<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
		<?php
	    }
	}
    }
    ?>
</tr>

<tr id="row7_H">
	<td colspan="2" class="compare-title"><h2>Affiliation</h2></td>
</tr>
<tr id="row7_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    $affiliations = $course->getAffiliations();
	    $Affiliations = array();
	    foreach($affiliations as $affiliation) {
		$Affiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);
	    }
	    if($Affiliations[0]){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i><?=$Affiliations[0]?></div>
		</td>
		<?php
	    }
	    else{
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <strong style="font-size:22px; color:#828282">-</strong>
		</td>
		<?php
	    }
	}
	if($j==0){
		echo "<script>fieldSet.push('7');</script>";
	}
	if($j < $compare_count_max)
	{
	    for ($x = $k+1; $x <=$compare_count_max; $x++)
	    {
		?>
		<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
		<?php
	    }
	}
    }
    ?>
</tr>
	

		<?php
		    $this->load->view('compareBackupFieldsMBA');
		?>

		<script>
		
		function manageFields() {
			//Hide all the Backup fields
			for(var i=10; i<=13 ; i++){
				$('#row'+i+'_C').hide();
				$('#row'+i+'_H').hide();
				$('#row'+i+'_F').hide();
			}
	
			for(var i=0; i<10 ; i++){
				//Field Id 1-9 can be main fields for the MBA comparison
				//Field Id 10 and onwards are Backup fields for MBA
				//If any of the Main fields is getting hidden, we will show the Backup field as per priority
				if (fieldSet.indexOf(i.toString())>=0) {
					$('#row'+i+'_C').hide();
					$('#row'+i+'_H').hide();
					$('#row'+i+'_F').hide();
					activateBackupField();
				}
			}
		}
				
		function activateBackupField() {
		    var backupFieldActivated = 10;
		    for(var z=backupFieldActivated; z<15 ; z++){
				//Check that it is not visible and also it is not available in the field set
				if( fieldSet.indexOf(z.toString()) <= -1 && $('#row'+z+'_C').css('display')=='none' ){
					$('#row'+z+'_C').show();
					$('#row'+z+'_H').show();
					$('#row'+z+'_F').show();
					break;
				}
			    
		    }		    		    
		}
		
		if (!manageFieldCallMade) {
			manageFieldCallMade = true;
			manageFields();
		}
		</script>
		
		
