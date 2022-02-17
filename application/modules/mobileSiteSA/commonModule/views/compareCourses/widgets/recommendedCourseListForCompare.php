<?php foreach($recommendedCourses as $recommendedCourse){ ?>
    <li class="<?=($recommendedCourse['courseId'])?>"> 
        <a href="Javascript:void(0);"  onclick="addRemoveFromCompare('<?=($recommendedCourse['courseId'])?>',compareOverlayTrackingKeyId);" class="add-tag-title">[ + ] <?=(limitTextLength(htmlentities($recommendedCourse['universityName']),19))?></a>
    </li>
<?php } ?>