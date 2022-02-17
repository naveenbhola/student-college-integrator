<?php
	if($categoryPageRequest->isExamCategoryPage())
	{
		$catPageTitle = $h1Title;
	}
	else{
		$catPageTitle = str_replace("in All Countries","Abroad",$catPageTitle);
	}
?>
<div id="course-title" class="course-title" style="position: relative;">
    <h1><?php echo $catPageTitle; ?></h1>
	<script>
		var rmcPageTitle = "<?php echo base64_encode(str_replace("in All Countries","Abroad",$catPageTitle)); ?>";
	</script>
    <div class="change-btn-rwap">
    <a href="JavaScript:void(0);" class="change-course" onclick="studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'changeCourseCountryLayer'); showAbroadOverlay('changeCourseCountryContDiv', 'Change Course/Country Preference');"> Change course / country <i class="cate-sprite change-arrow"></i></a>
    
    <div class="course-country-help" id="courseCountryHelp" style="display:<?php echo ($showGutterHelpText)?"":"none"; ?>">
        <i class="cate-sprite help-arrow-1"></i>
        <p>Use this button to change desired course or country</p>
        
    </div>
    </div>    
</div>
