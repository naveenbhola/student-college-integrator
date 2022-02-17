<?php
    $courseTitle    = htmlentities($courseObj->getName());
    $courseDuration = $courseObj->getDuration();
    $universityURL  = "";
    $universityName = "";
    if($departmentObj && $courseObj->getUniversityType() !='college')
    {
        $departmentURL  = $departmentObj->getURL();
        $departmentURL  = $departmentURL ? $departmentURL : "#";
        $departmentName = htmlentities($departmentObj->getName());
    }
    
?>
<div id="course-title" class="course-title clearwidth" style="position:relative">
    <h1><?=$courseTitle?></h1>
	<script>
		var rmcPageTitle = "<?=base64_encode($courseObj->getName())?>";
	</script>
</div>