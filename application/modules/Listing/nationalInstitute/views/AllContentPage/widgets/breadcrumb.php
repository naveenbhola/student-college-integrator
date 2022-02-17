<?php
	if($instituteObj->getType() == 'university')
		$breadCollegeOrUnivText = "Universities";
	else
		$breadCollegeOrUnivText = "Colleges";
?>
<div class="new-row">
<div class="breadcrumb3">
    <span class="home"><a href="<?=SHIKSHA_HOME;?>">Home</a></span>
    <span class="breadcrumb-arrow">›</span>
    <span><?php echo $breadCollegeOrUnivText;?></span>
    <span class="breadcrumb-arrow">›</span>
    <span><a href="<?php echo $instituteUrl;?>"><?php echo htmlentities($instituteNameWithLocation);?></a></span>
    <span class="breadcrumb-arrow">›</span>
    <span><?php echo $sectionText;?></span>
</div>
</div>