<?php if(count($showCourseReviews) > 0) { ?>


<h2 class="ques-title" style="background: none repeat scroll 0 0 #fcd146;">
     <a data-rel="back" onclick="$('#keywordSuggest').val('');" href="javascript:void(0);" style="float:left; display: inline-block;"><i class="back-arr" style="bottom:0;left:0; position: relative; top: 3px; float: left;"></i></a>
     <p style="margin-left:15px;"><?php if(strlen($instituteName)>30) { echo substr($instituteName,0,30)."..";} else{ echo $instituteName;}?></p></h2> 
<div style="box-shadow:none;" class="notify-details clearfix revwListBx layer-wrap">
     <p class="review-sub-head">Rating is based on actual reviews of students who studied at this college</p>
     <ul style="margin-top:15px; float: left; width:100%;" class="course-ranking-list">
          <?php 
               foreach($showCourseReviews as $courseId=>$ratingData) {
            $course = $courseRepo->find($courseId, array('basic', 'course_type_information'));
               
          ?>
         <li>
               <a href="<?php echo $instituteUrl;?>/reviews?course=<?php echo $courseId; ?>" title="<?php echo $course->getName();?>"><?php echo $course->getName();?></a>
               <div class="rv_ratng"><span><?php echo round($ratingData['overallAverageRating'],1);?><b>/ <?php echo $ratingData['ratingCount']; ?></b></span></div>
          </li>
         <?php }?>
</ul>
</div>
<?php }?>