<tr class="collegeListSec first flagClassForAjaxContent">
<?php $j =1; 
foreach ($institutes as $key => $institute) {
$instituteName = ($institute->getShortName() == '') ? $institute->getName() : $institute->getShortName();	
if(strlen($instituteName) > 100){
	$instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($instituteName));
	$instStr .= "...";
}else{
	$instStr = html_escape($instituteName);
}

if(strlen($instStr) > 50){
	$instStr = substr($instStr, 0 , 47).'...';
}
?>
<td <?php if($j<$compare_count_max) {?> class = "border-right" <?php } ?> lang="en">
	<a href="javascript:void(0);" class="close-link" onClick="removeCollege('<?=$j?>');">&times;</a>
	<a class="cc-clg-title" instituteId="<?=$institute->getId()?>" type="<?=$institute->getType()?>" href="<?=$institute->getURL()?>" id="instituteName<?php echo $j; ?>" title="<?=htmlspecialchars($instituteName)?>"><?php echo  $instStr; ?></a>
</td>
<?php $j++;
}
    // $filled_compares--;
$empty_compares = 1;
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
foreach ($institutes as $key => $institute){?>
<td <?php if($j<$compare_count_max) {?> class = "border-right" <?php } ?>>
<div class="show-year">
	<p class="estd-year"><?php if($yoe = $institute->getEstablishedYear()){ echo "Establishment in ".$yoe;}?></p>
</div>
</td>
<?php $j++;}?>
</tr>

<tr class="collegeListSec flagClassForAjaxContent">
<?php 
$j =1;
foreach ($institutes as $key => $institute){?>
<td <?php if($j<$compare_count_max) {?> class = "border-right" <?php } ?>>
	<div class="show-loc">
		<span class="get-loc"><i class="icon-location" id = ""></i><?php echo $institute->getMainLocation()->getCityName();?></span>
	</div>
</td>
<?php $j++;}?>
</tr>
<tr>
<?php 
if($filled_compares != 2){
	echo '<td class="border-right"></td>';
}
?>
<?php 
$makeDisable = false;
if($empty_compares > 0)
{
	for($e = $filled_compares+1; $e<=$compare_count_max; $e++)
	{
		?>
		<td class="<?php echo ($e<$compare_count_max)?'border-right':'';?>" <?php if(!$makeDisable){ ?>id="newInstituteSection<?=$e?>"<?php } ?>>
			<div id="searchContainerDiv<?=$e?>" class="home-search">
				<div class="full-width">
				   <a onclick="alert('Please select a course');" href="javascript:;" compareBox="<?=$e?>" class="addACollegeButton cc-btn <?php if($e == 2 && $filled_compares == 0){ ?> disabled-btn <?php }?>">Add A College</a>
				</div>
			</div>
	    </td>
		<?php
		$makeDisable = true;
	}
}
?>
</tr>