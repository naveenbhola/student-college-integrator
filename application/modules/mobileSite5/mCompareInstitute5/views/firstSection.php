<tr class="collegeListSec first flagClassForAjaxContent">
<?php $j =1; 

foreach($courseIdArr as $courseId){
$course      = $courseObjs[$courseId];

if($showAcademicUnitSection){
	$instituteId = $academicUnitRawData[$courseId]['userSelectedInstitute'];
}else{
	$instituteId = $instIdArr[$courseId];
}

$institute   = $instituteObjs[$instituteId];
$instNameDisplay = ($institute->getShortName() != '') ? $institute->getShortName() : $institute->getName();
if(strlen($instNameDisplay) > 100){
	$instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($instNameDisplay));
	$instStr .= "...";
}else{
	$instStr = html_escape($instNameDisplay);
}
?>
<td <?php if($j<$compare_count_max) {?> class = "border-right" <?php } ?> lang="en">
	<a href="javascript:void(0);" class="close-link" onClick="removeCollege('<?=$j?>'); trackEventByGAMobile('MOBILE_REMOVE_A_COLLEGE_BUTTON_FROM_COMPARE');">&times;</a>
	<a class="cc-clg-title" instituteId="<?=$instituteId?>" href="<?=$course->getURL();?>" id="instituteName<?php echo $j; ?>" title="<?=htmlspecialchars($institute->getName())?>"><?php echo  $instStr; ?></a>
</td>
<?php $j++;
}
$makeDisable = false;
if($empty_compares > 0)
{
	for($e = $filled_compares+1; $e<=$compare_count_max; $e++)
	{
	?>
		<td rowspan="3" class="empty <?php echo ($e<$compare_count_max)?'border-right':'';?>" <?php if(!$makeDisable){ ?>id="newInstituteSection"<?php } ?>>
	    <div class="shortlist-number <?php echo ($makeDisable)?'disable-numb':''?>"><?=$e?></div>
	    </td>
	<?php
	$makeDisable = true;
	}
}
?>
</tr>

<tr class="collegeListSec flagClassForAjaxContent">
<?php
$j =1;
foreach($courseIdArr as $courseId) {
	$course      = $courseObjs[$courseId];
	$instituteId = $instIdArr[$courseId];
	$institute   = $instituteObjs[$instituteId];
?>
<td <?php if($j<$compare_count_max) {?> class = "border-right" <?php } ?>>
<div class="show-year">
	<p class="estd-year"><?php if($yoe = $institute->getEstablishedYear()){ echo "Establishment in ".$yoe;}?></p>
</div>
</td>
<?php $j++;
}?>
</tr>

<tr class="collegeListSec flagClassForAjaxContent">
<?php 
$j =1;
foreach($courseIdArr as $courseId) {
	$course      = $courseObjs[$courseId];
	$instituteId = $instIdArr[$courseId];
	$institute   = $instituteObjs[$instituteId];
?>
<td <?php if($j<$compare_count_max) {?> class = "border-right" <?php } ?>>
	<div class="show-loc">
		<span class="get-loc"><i class="icon-location" id = "<?=$courseId;?>"></i><?php echo $institute->getMainLocation() ?$institute->getMainLocation()->getCityName():'';?></span>
	</div>
</td>
<?php $j++;}?>
</tr>
