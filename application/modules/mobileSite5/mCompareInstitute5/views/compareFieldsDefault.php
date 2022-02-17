<?php $isSAComparePage = 0; ?>
<script>var fieldSet = new Array();</script>
<tr id="row1_H">
	<td colspan="2" class="compare-title"><h2>AIIMA Rank</h2></td>
</tr>
<tr id="row1_C" align="center"> 
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	foreach($institutes as $institute){
	    $k++;
	    if($institute->getAIMARating()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><span><?=$institute->getAIMARating()?></span><i class="sprite college-rank-icon"></i></div>
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
<tr id="row2_H">
	<td colspan="2" class="compare-title"><h2>Alumni Rating</h2></td>
</tr>
<tr id="row2_C" align="center">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	foreach($institutes as $institute){
	    $k++;
	    if($institute->getAlumniRating()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
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
		    <span class="rateNum" >&nbsp;<?=$institute->getAlumniRating()?>/5</span>
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
		echo "<script>fieldSet.push('2');</script>";
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
<tr id="row3_H">
	<td colspan="2" class="compare-title"><h2>Course Duration</h2></td>
</tr>
<tr id="row3_C" align="center">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->getDuration() && $course->getDuration()!=""){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><span><?=$course->getDuration()?></span></div>
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
		echo "<script>fieldSet.push('3');</script>";
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
	
<tr id="row4_H">
	<td colspan="2" class="compare-title"><h2>Mode of Study</h2></td>
</tr>
<tr id="row4_C" align="center">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->getCourseType()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><span><?=$course->getCourseType()?></span></div>
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
		echo "<script>fieldSet.push('4');</script>";
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

<tr id="row5_H">
	<td colspan="2" class="compare-title"><h2>Affiliated To</h2></td>
</tr>
<tr id="row5_C" align="center">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    $affiliations = $course->getAffiliations();
	    if(count($affiliations) > 0){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <?php
			foreach($affiliations as $affiliation) {
			    echo '<div class="">'.langStr('affiliation_'.$affiliation[0],$affiliation[1])."</div>";
			}
		    ?>
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
		echo "<script>fieldSet.push('5');</script>";
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

<tr id="row6_H">
	<td colspan="2" class="compare-title"><h2>Average Salary (p.a.)</h2></td>
</tr>
<tr id="row6_C" align="center">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->getAverageSalary()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><span><?php echo  $salArray['currency']." ".number_format($course->getAverageSalary()/100000,2)." Lacs "; ?></span></div>
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

</tr> 	

<tr id="row7_H">
	<td colspan="2" class="compare-title"><h2>Top Recruiting Companies</h2></td>
</tr>
<tr id="row7_C">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if(($recruitingCompanies = $course->getRecruitingCompanies()) && (!(count($recruitingCompanies) == 1 && !$recruitingCompanies[0]->getName()))){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>" style="text-align:left;">
		    <div class="college-rank">
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

<tr id="row8_H">
	<td colspan="2" class="compare-title"><h2>AICTE Approved</h2></td>
</tr>
<tr id="row8_C" align="center">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->isAICTEApproved()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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
		echo "<script>fieldSet.push('8');</script>";
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

</tr>

<tr id="row9_H">
	<td colspan="2" class="compare-title"><h2>UGC Recognised</h2></td>
</tr>
<tr id="row9_C" align="center">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->isUGCRecognised()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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
		echo "<script>fieldSet.push('9');</script>";
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

<tr id="row10_H">
	<td colspan="2" class="compare-title"><h2>DEC Approved</h2></td>
</tr>
<tr id="row10_C" align="center">
    <?php
    $j = 0;$k = 0;
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->isUGCRecognised()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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

<tr id="row11_H">
	<td colspan="2" class="compare-title"><h2>Fees</h2></td>
</tr>
<tr id="row11_C" align="center">
    <?php
    $j = 0;$k = 0;
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
		echo "<script>fieldSet.push('11');</script>";
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


<tr id="row12_H">
	<td colspan="2" class="compare-title"><h2>Eligibility</h2></td>
</tr>
<tr id="row12_C" align="center">
    <?php
    
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$z = 0;
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
		<td class="<?php echo ($z<$compare_count_max)?'border-right':'';?>">
		<?php
		echo $widgetData['eligibility']['examRequiredHTML'];
		if($course->getOtherEligibilityCriteria()!=''){
			echo '<div class="" style="margin-bottom:5px;">'.$course->getOtherEligibilityCriteria().'</div>';
		}
		?>
		</td>
		<?php
	    }else{
		?>
		<td class="<?php echo ($z<$compare_count_max)?'border-right':'';?>">
		    <strong style="font-size:22px; color:#828282">-</strong>
		</td>
		<?php
	    }
	}
	if($j==0){
		echo "<script>fieldSet.push('12');</script>";
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

<tr id="row13_H">
	<td colspan="2" class="compare-title"><h2>Dual Degree</h2></td>
</tr>
<tr id="row13_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($sf = $course->offersDualDegree()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div>
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
		echo "<script>fieldSet.push('13');</script>";
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

<?php if($isSAComparePage == 0){ ?>
<tr id="row14_H">
	<td colspan="2" class="compare-title"><h2>Foreign Travel</h2></td>
</tr>
<tr id="row14_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($sf = $course->offersForeignTravel()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div>
		</td>
		<?php
	    }elseif($sf = $course->offersForeignExchange())
	    {
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div>
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
		echo "<script>fieldSet.push('14');</script>";
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

<tr id="row15_H">
	<td colspan="2" class="compare-title"><h2>Free Laptop</h2></td>
</tr>
<tr id="row15_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->providesFreeLaptop()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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
		echo "<script>fieldSet.push('15');</script>";
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

<?php } ?>

<tr id="row16_H">
	<td colspan="2" class="compare-title"><h2>In-Campus Hostel</h2></td>
</tr>
<tr id="row16_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->providesHostelAccomodation()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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
		echo "<script>fieldSet.push('16');</script>";
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

<?php if($isSAComparePage==0){ ?>
<tr id="row17_H">
	<td colspan="2" class="compare-title"><h2>Transport Facility</h2></td>
</tr>
<tr id="row17_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->providesTransportFacility()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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
		echo "<script>fieldSet.push('17');</script>";
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

<tr id="row18_H">
	<td colspan="2" class="compare-title"><h2>Wifi Campus</h2></td>
</tr>
<tr id="row18_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->hasWifiCampus()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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
		echo "<script>fieldSet.push('18');</script>";
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

<tr id="row19_H">
	<td colspan="2" class="compare-title"><h2>AC Campus</h2></td>
</tr>
<tr id="row19_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->hasACCampus()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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
		echo "<script>fieldSet.push('19');</script>";
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

<tr id="row20_H">
	<td colspan="2" class="compare-title"><h2>Free Training</h2></td>
</tr>
<tr id="row20_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->getFreeTrainingProgram()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><i class="sprite approved-icon"></i></div>
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
		echo "<script>fieldSet.push('20');</script>";
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

<tr id="row21_H">
	<td colspan="2" class="compare-title"><h2>Starts in</h2></td>
</tr>
<tr id="row21_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    $sf = $course->getDateOfCommencement();
	    if($sf){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><?=date('F, Y',strtotime($sf))?></div>
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
		echo "<script>fieldSet.push('21');</script>";
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
<?php } ?>

<script>
for(var i=0; i<fieldSet.length ; i++){
        $('#row'+fieldSet[i]+'_H').hide();
	$('#row'+fieldSet[i]+'_C').hide();
        $('#row'+fieldSet[i]+'_F').hide();
}    
</script>