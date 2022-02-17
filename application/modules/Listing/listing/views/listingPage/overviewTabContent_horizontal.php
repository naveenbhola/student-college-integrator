<div class="desc-details-wrap">
<ul>
<?php if($courseComplete->getDescriptionAttributes()){ ?>
<li>
	<h2 class="description">Course Details</h2>
	<div class="section-cont mbot-0 accordion courseDesc" id="courseDesc">
<?php if($pageType == 'institute') {
	//ob_start(); 
}
?>
	<?php
			$courseDescriptions = array();
			foreach($courseComplete->getDescriptionAttributes() as $attribute){
				
				$contentTitle = $attribute->getName();
				if($contentTitle == "Course Description"){
					$courseDescription = array($attribute);
				}else{
					$courseDescriptions[] = $attribute;
				}
			}
			if($courseDescription){
				$courseDescriptions = array_merge($courseDescription,$courseDescriptions);
			}
			foreach($courseDescriptions as $attribute){
				
				$contentTitle = $attribute->getName();
				if(strlen($contentTitle)>103){
					$contentTitle = preg_replace('/\s+?(\S+)?$/', '', substr($contentTitle, 0, 100))."...";
				}
			?>
			<h3 class="desc-title-box pointer-cursor">
				<span class="sprite-bg closed-arrow"></span>
				<strong title="<?=$attribute->getName()?>"><?=$contentTitle?></strong>
			</h3>
			<div class="course-details">
				<?php
					$summary = new tidy();
					$summary->parseString($attribute->getValue(),array('show-body-only'=>true),'utf8');
					$summary->cleanRepair();
				?>
				<?=$summary?>
			</div>
	<?php
		}
	?>
<?php if($pageType == 'institute') {
 //$pageContent = ob_get_contents();
  //ob_end_clean();
?>
<script>
	//$('courseDesc').innerHTML = base64_decode("<?=base64_encode($pageContent)?>");
</script>
<?php
}
?>
	</div>
</li>
<?php } ?>
<?php if($instituteComplete->getDescriptionAttributes()){ ?>
<li>
	<h2 class="description" style="padding-top:50px;">Details About <?php echo $institute->getName();?></h2>
	<div class="section-cont mbot-0 accordion instituteDesc" id="instituteDesc">
<?php if($pageType != 'institute') {
	//ob_start(); 
}
?>
	<?php
			$instituteDescriptions = array();
			foreach($instituteComplete->getDescriptionAttributes() as $attribute){
				$contentTitle = $attribute->getName();
				if($contentTitle == "Institute Description"){
					$instituteDescription = array($attribute);
				}else{
					$instituteDescriptions[] = $attribute;
				}
			}
			if($instituteDescription){
				$instituteDescriptions = array_merge($instituteDescription,$instituteDescriptions);
			}
			foreach($instituteDescriptions as $attribute){
				
				$contentTitle = $attribute->getName();
				if(strlen($contentTitle)>103){
					$contentTitle = preg_replace('/\s+?(\S+)?$/', '', substr($contentTitle, 0, 100))."...";
				}
			?>
			<h3 class="desc-title-box pointer-cursor">
				<span class="sprite-bg closed-arrow"></span>
				<strong title="<?=$attribute->getName()?>"><?=$contentTitle?></strong>
			</h3>
			<div class="course-details">
				<?php
					$summary = new tidy();
					$summary->parseString((trim($attribute->getValue())),array('show-body-only'=>true),'utf8');
					$summary->cleanRepair();
				?>
				<?=$summary?>
			</div>
	<?php
		}
		if($pageType == 'institute'){
	?>
		<h3 class="desc-title-box pointer-cursor">
				<span class="sprite-bg closed-arrow"></span>
				<strong title="Related questions about this institute">Related questions about this institute</strong>
		</h3>
		<div class="course-details">
			<!-- Content -->
			<?php echo Modules::run('CafeBuzz/ListingPageAnA/getInstituteRelatedQuestionDetails', $institute); ?>
		</div>
	<?php
		}
	?>
<?php if($pageType != 'institute') {
 //$pageContent = ob_get_contents();
  //ob_end_clean();
?>
<script>
	//$('instituteDesc').innerHTML = base64_decode("<?=base64_encode(utf8_decode($pageContent))?>");
</script>
<?php
}
?>
	</div>
</li>
<?php } ?>
</ul>	
</div>

