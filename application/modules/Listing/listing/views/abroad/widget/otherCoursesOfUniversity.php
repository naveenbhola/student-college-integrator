<?php if(!empty($similarCoursesData)) { ?>
<div class="other-course-box clearwidth">
    <h3>Other Courses at this university</h3>
    <ul class="gray-list-item">
        <?php foreach( $similarCoursesData as $similarCourseObj ) { ?>
        <li><a href="<?=$similarCourseObj["url"]?>" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'rightColumn', 'otherCourses');"><?=htmlentities($similarCourseObj["name"])?></a></li>
        <?php } ?>
    </ul>
     <a href="<?=($universityCourseSectionUrl ? $universityCourseSectionUrl : "#")?>" class="flRt">More &gt;</a> 
</div>
<?php } ?>