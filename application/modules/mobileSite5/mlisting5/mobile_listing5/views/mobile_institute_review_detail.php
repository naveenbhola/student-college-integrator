
<?php if(count($showCourseReviews) > 0) { ?>
    <h2 class="ques-title" id='ReviewSection'><p>College Reviews</p></h2>
    <div class="notify-details clearfix">
    <p class="review-sub-head">Rating is based on actual reviews of students who studied at this college</p>
    <ul class="course-ranking-list" style="margin-top:15px; float: left; width:100%;">
        <?php 
        foreach($showCourseReviews as $courseId=>$overallRating) { 
            $course = $institute->getCourse($courseId);
        ?>
            <li>
                <a href="<?php echo $course->getURL();?>?section=collegeReview" title="<?php echo $course->getName();?>"><?php echo $course->getName();?></a>
                <div class="ranking-bg"><strong><?php if(strpos($overallRating,'.')){echo $overallRating;} else{echo $overallRating.'.0';}?></strong><sub>/<?php echo $ratingCount[$courseId]; ?></sub></div>
            </li>
        <?php 
        } ?>
    </ul>
    </div>
<?php } ?>
