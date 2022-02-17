<?php
  $anaWidgetHtml = trim($anaWidget['html']);
  $reviewWidgetHtml = trim($reviewWidget['html']);
?>

 <div class="nav-col nav-bar color-w ovf-y sticky-pos m-5top">
   <div class="">
       <ul class="nav-cell">
        <?php if($collegesWidgetData['topInstituteData'] || $collegesWidgetData['providesAffiliaion']){?>
          <li class="tab-cell"><a href="#college" class="pad8 block color-6 f14"><?php echo $collegesWidgetData['tabText'];?></a></li>
        <?php } if(!empty($coursesWidgetData['allCourses'])){ ?>
              <li class="tab-cell"><a href="#course" class="pad8 block color-6 f14">Courses (<?php echo $coursesWidgetData['totalCourseCount'];?>)</a></li>
          <?php } if(!empty($admissionInfo) || (!empty($examList) && count($examList) > 0)) {?>
                <li class="tab-cell"><a href="#exam" class="pad8 block color-6 f14"><?php echo empty($admissionInfo)?'Exams':'Admissions';?></a></li>
          <?php } if(!empty($highlights)){?>
              <li class="tab-cell"><a href="#highlight" class="pad8 block color-6 f14">Highlights</a></li>
           <?php } if(!empty($facilities['facilities'])){?>
              <li class="tab-cell"><a href="#facility" class="pad8 block color-6 f14">Facilities</a></li>
           <?php } if(!empty($reviewWidgetHtml)){?>
              <li class="tab-cell"><a href="#review" class="pad8 block color-6 f14">Reviews</a></li>
           <?php } if(!empty($anaWidgetHtml)){?>
              <li class="tab-cell"><a href="#qna" class="pad8 block color-6 f14">Q&A</a></li>
           <?php } if(trim($galleryWidget)) {?>
              <li class="tab-cell"><a href="#gallery" class="pad8 block color-6 f14">Gallery</a></li>
           <?php } if(!empty($scholarships)){?>
              <li class="tab-cell"><a href="#scholarship" class="pad8 block color-6 f14">Scholarship</a></li>
           <?php } if(!empty($faculty)) {?>
              <li class="tab-cell"><a href="#faculty" class="pad8 block color-6 f14">Faculty</a></li>
           <?php } if(!empty($events)){?>
              <li class="tab-cell"><a href="#event" class="pad8 block color-6 f14">Events</a></li>
           <?php } if(trim($articleWidget)){?>
              <li class="tab-cell"><a href="#article" class="pad8 block color-6 f14">Articles</a></li>
            <?php } if(trim($contactWidget)){?>
              <li class="tab-cell"><a href="#contact" class="pad8 block color-6 f14">Contacts</a></li>
           <?php }
        ?>
       </ul>
   </div>
</div>