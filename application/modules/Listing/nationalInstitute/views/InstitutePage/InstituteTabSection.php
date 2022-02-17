<?php
    $anaWidgetHtml = $anaWidget['html'];
    $reviewWidgetHtml = trim($reviewWidget['html']);

    $GA_Tap_On_Navigation_Colleges = 'COLLEGE_NAVIGATION';
    $GA_Tap_On_Navigation_Courses = 'COURSE_NAVIGATION';
    $GA_Tap_On_Navigation_High = 'HIGHLIGHTS_NAVIGATION';
    $GA_Tap_On_Navigation_Fac = 'FACILITIES_NAVIGATION';
    $GA_Tap_On_Navigation_Review = 'REVIEW_NAVIGATION';
    $GA_Tap_On_Navigation_AnA = 'QnA_NAVIGATION';
    $GA_Tap_On_Navigation_Gallery = 'GALLERY_NAVIGATION';
    $GA_Tap_On_Navigation_Scholr = 'SCHOLARSHIP_NAVIGATION';
    $GA_Tap_On_Navigation_Cutoff = 'CUTOFF_NAVIGATION';
    $GA_Tap_On_Navigation_Faculty = 'FACULTY_NAVIGATION';
    $GA_Tap_On_Navigation_Event = 'EVENT_NAVIGATION';
    $GA_Tap_On_Navigation_Article = 'ARTICLE_NAVIGATION';
    $GA_Tap_On_Navigation_Admission = 'ADMISSION_EXAM_NAVIGATION';
    $GA_Tap_On_Navigation_Contact  = 'CONTACT_NAVIGATION';
  ?>
<a class="left-slide" style="display:none;"><i></i></a>
<ul class="head-ul nav-bar-list">

    <?php  
              if(!empty($coursesWidgetData['allCourses'])){ ?>
          <li><a elementtofocus="course-offer" ga-attr="<?php echo $GA_Tap_On_Navigation_Courses;?>">Courses & Fees<sub class="sub-cl"><?php echo $coursesWidgetData['totalCourseCount'];?></sub></a></li>
      <?php } if(!empty($reviewWidgetHtml)) { ?>
                        <li><a elementtofocus="review" ga-attr="<?=$GA_Tap_On_Navigation_Review?>">Reviews<sub class="sub-cl"></sub></a></li>
      <?php } if(!empty($admissionInfo) || (!empty($examList) && count($examList) > 0)) {?>
                <li><a elementtofocus="admissionsection-offer" ga-attr="<?=$GA_Tap_On_Navigation_Admission?>"><?php echo empty($admissionInfo)?'Exams':'Admissions & Cut-Offs';?></a></li>
      <?php } if(!empty($highlights)){?>
          <li><a elementtofocus="highlights" ga-attr="<?=$GA_Tap_On_Navigation_High?>">Highlights</a></li>
       <?php } if(!empty($facilities['facilities'])){?>
          <li><a elementtofocus="amenities" ga-attr="<?=$GA_Tap_On_Navigation_Fac?>">Facilities</a></li>
       <?php }  if($anaWidget['count'] > 1) { ?>
                <li><a elementtofocus="ana" ga-attr="<?=$GA_Tap_On_Navigation_AnA?>">Q&A
                <sub class="sub-cl"></sub></a></li>
          <?php } ?>
       <?php if(!empty($galleryWidget)) {?>
          <li><a elementtofocus="gallery" ga-attr="<?=$GA_Tap_On_Navigation_Gallery?>">Gallery <sub class="sub-cl"><?=$galleryWidget['totalCount']?></sub></a></li>
       <?php } if(!empty($scholarships)){?>
          <li><a elementtofocus="scholar" ga-attr="<?=$GA_Tap_On_Navigation_Scholr?>">Scholarships</a></li>
        <?php } if(!empty($cutOffData)){?>
           <li><a elementtofocus="clgcutoff" ga-attr="<?=$GA_Tap_On_Navigation_Cutoff?>">Cut-Offs</a></li>
       <?php } if(!empty($faculty)) {?>
          <li><a elementtofocus="faculty" ga-attr="<?=$GA_Tap_On_Navigation_Faculty?>">Faculty</a></li>
       <?php } if(!empty($events)){?>
          <li><a elementtofocus="events" ga-attr="<?=$GA_Tap_On_Navigation_Event?>">Events</a></li>
       <?php } if(!empty($articleWidget)){?>
          <li><a elementtofocus="articles" ga-attr="<?php echo $GA_Tap_On_Navigation_Article;?>">Articles<sub class="sub-cl"><?=$articleWidget['totalCount']?></sub></a></li>
       <?php }
       $contactWidget = trim($contactWidget);
        if(!empty($contactWidget)){?>
          <li><a elementtofocus="contact" ga-attr="<?php echo $GA_Tap_On_Navigation_Contact;?>">Contact</a></li>
       <?php } 
       if($collegesWidgetData['topInstituteData'] || $collegesWidgetData['providesAffiliaion']){
            if($collegesWidgetData['topInstituteData']){?>
              <li><a elementtofocus="colleges-offer" ga-attr="<?=$GA_Tap_On_Navigation_Colleges?>"><?php echo $collegesWidgetData['tabText'];?><sub class="sub-cl"><?=$collegesWidgetData['instituteCount']?></a></li>
              <?php }else{ ?>
      <?php } 
        } 
       ?>
</ul>
   
<a class="right-slide" style="display:none;"><i></i></a>