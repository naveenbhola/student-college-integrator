<?php foreach($recommendedCourses as $recommendedCourse){ ?>
    <li class="<?=($recommendedCourse['courseId'])?>"> 
        <a href="Javascript:void(0);"  onclick="addRemoveFromCompare('<?=($recommendedCourse['courseId'])?>',compareOverlayTrackingKeyId,<?=$recommendedCoursesTrackingId?>,'new');" class="add-tag-title"> <span class="add-tag">[ Add ] </span> <?=(formatArticleTitle(htmlentities($recommendedCourse['universityName']),30))?></a>, <span class="span-clr"><?=(htmlentities($recommendedCourse['countryName']))?></span>
    </li>
<?php } ?>