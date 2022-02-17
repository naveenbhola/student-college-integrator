<tr id="row11_H">
	<td colspan="2" class="compare-title"><h2>Course Duration</h2></td>
</tr>
<tr id="row11_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if(($course->getDuration() && $course->getDuration()!="") || $course->getCourseType()){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><p class="percentile"><?=$course->getDuration()?> <?=$course->getCourseType()?></p></div>
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
	<td colspan="2" class="compare-title"><h2>Form Submission Date</h2></td>
</tr>
<tr id="row12_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->getDateOfFormSubmission() && $course->getDateOfFormSubmission()!="0000-00-00 00:00:00"){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><span style="font-size:1.2em"><?=date("jS F, Y",strtotime($course->getDateOfFormSubmission()))?></span></div>
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
		echo "<script>fieldSet.push('12');</script>";
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

<tr id="row13_H">
	<td colspan="2" class="compare-title"><h2>Course Commencement Date</h2></td>
</tr>
<tr id="row13_C" align="center">
    <?php
    if($compare_count <= $compare_count_max)
    {
	$j = 0;$k = 0;
	foreach($institutes as $institute){
	    $k++;
	    $course = $institute->getFlagshipCourse();
	    if($course->getDateOfCourseComencement() && $course->getDateOfCourseComencement()!="0000-00-00 00:00:00"){
		$j++;
		?>
		<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
		    <div class="college-rank"><span style="font-size:1.2em"><?=date("jS F, Y",strtotime($course->getDateOfCourseComencement()))?></span></div>
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