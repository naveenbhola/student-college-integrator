<div id="rebSec" class="ask-section">
<?php 
foreach($institutes as $institute){
	$className = "";
	$course = $institute->getFlagshipCourse();
	if($_COOKIE["applied_".$course->getId()] == 1){
		$className = "requested-e-bro";
	}else{
		$className = "";
	}?>
	<td>
	<?php if($brochureURL->getCourseBrochure($course->getId())){
		if($className == "requested-e-bro"){?>
		<p class = "eb-sent">E-brochure Sent</p>
	<?php
	}else{
	?>
		<div id= "bottomREB_<?php echo $course->getId(); ?>">
		<a onclick=" ApplyNowCourse('<?php echo $institute->getId(); ?>','<?php echo base64_encode(htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()));?>','<?php echo $course->getId(); ?>','<?php echo base64_encode(htmlspecialchars($course->getName())); ?>','<?=$course->getURL()?>',683);" value="Download E-brochure" class="new-dwn-btn" href="javascript:;"><i class="cmpre-sprite ic-ebrocher"></i>Download E-Brochure</a>
		</div>


	<?php
	}
	?>
<?php }else{?>
<script>document.getElementById('rebSec').style.display="none";
document.getElementById('CTAsec').style.display="none";
</script>
<?php } ?> 
</td>
<?php }?>
</div>