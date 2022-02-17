<?php if(count($showCourseReviews) > 0) { ?>
<div class="other-details-wrap clear-width course-reviews-section" id="course-reviews-sec">
    <h5 class="mb14">College Reviews</h5>
    <p class="review-sub-head">Rating is based on actual reviews of students who studied at this college</p>
    <ul class="course-ranking-list">
        <?php 
        foreach($showCourseReviews as $courseId=>$reviewDetails) { 
            $course = $institute->getCourse($courseId);
        ?>
            <li>
                <a href="<?php echo $course->getURL();?>#course-reviews" title="<?php echo $course->getName();?>"><?php echo $course->getName();?></a>
                <div class="ranking-bg"><?php echo $reviewDetails['overallAverageRating'];?><sub>/<?php echo $reviewDetails['ratingParamCount'];?></sub></div>
            </li>
        <?php 
        } ?>
    </ul>
</div>
<?php } ?>
