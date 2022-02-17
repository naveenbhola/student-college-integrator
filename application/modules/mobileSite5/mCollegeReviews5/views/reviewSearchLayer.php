<?php if(count($showCourseReviews) > 0) { ?>
<div class="collge-revw-layer flLt">
  	<div class="collge-revw-head">
    	<p class="flLt" style="width:90%; overflow: hidden; height:32px;"><?php if(strlen($instituteName)>30) { echo substr($instituteName,0,30).'..';}else{ echo $instituteName;}?></p>
<a class="rvw-close-mark flRt" href="javascript:void(0);" onclick="closeSearchLayer();">&times;</a>
        <div class="clearFix"></div>
    </div>
    <div class="collge-rvw-detail" style="height:137px; margin:10px;margin-bottom:20px;<?php echo $isScroll?>">
    	<p><strong>Click on below links to read Reviews for these courses:</strong></p>
        <ul style="border:none; box-shadow:none; padding:20px 0 0; margin:0;" class="rvw-rating-list revwListBx2">
          <?php 
        foreach($showCourseReviews as $courseId=>$overallAverageRating) {
            $course = $courseRepo->find($courseId, array('basic', 'course_type_information'));
        ?>
            <li>
            	<a href="<?php echo $instituteUrl;?>/reviews?course=<?php echo $courseId; ?>" title="<?php echo $course->getName();?>"><?php echo $course->getName();?></a>
                <div class="rv_ratng"> Course Rating:<span><?php echo round($overallAverageRating['overallAverageRating'],1);?><b>/<?php echo $overallAverageRating['ratingCount'];?></b></span></div>
            </li>
        <?php 
        } ?>        
        </ul>
    </div>
</div>
<?php }?>
