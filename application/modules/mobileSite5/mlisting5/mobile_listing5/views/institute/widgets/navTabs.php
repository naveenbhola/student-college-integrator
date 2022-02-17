<?php 
    $anaWidgetHtml = trim($anaWidget['html']);
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
<div class="clg-nav">
    <div class="clg-navList">
        <ul>
          <?php if($collegesWidgetData['topInstituteData'] || $collegesWidgetData['providesAffiliaion']){ ?>
          <li><a elementtofocus="colleges-offer" href="javascript:void(0);" ga-attr="<?=$GA_Tap_On_Navigation_Colleges;?>"><?php echo $collegesWidgetData['tabText'];?><span></span></a></li>
          <?php } if(!empty($coursesWidgetData['allCourses'])){ ?>
              <li><a elementtofocus="course" ga-attr="<?=$GA_Tap_On_Navigation_Courses;?>">Courses (<?php echo $coursesWidgetData['totalCourseCount'];?>)</a></li>
          <?php } if(!empty($reviewWidgetHtml)){?>
             <li><a elementtofocus="reviews" ga-attr="<?=$GA_Tap_On_Navigation_Review;?>">Reviews<span></span></a></li>    
          <?php } if(!empty($admissionInfo) || (!empty($examList) && count($examList) > 0)) {?>
                <li><a elementtofocus="admissionsection-offer" href="javascript:void(0);" ga-attr="<?=$GA_Tap_On_Navigation_Admission;?>"><?php echo empty($admissionInfo)?'Exams':'Admissions';?></a></li>
          <?php } if(!empty($highlights)){?>
              <li><a elementtofocus="highlights" ga-attr="<?=$GA_Tap_On_Navigation_High;?>">Highlights</a></li>
           <?php } if(!empty($facilities['facilities'])){?>
              <li><a elementtofocus="amenities" ga-attr="<?=$GA_Tap_On_Navigation_Fac;?>">Facilities</a></li>
           <?php } if(!empty($anaWidgetHtml)){?>
              <li><a elementtofocus="qna" ga-attr="<?=$GA_Tap_On_Navigation_AnA;?>">Q&A<span></span></a></li>
           <?php } if(trim($galleryWidget)) {?>
              <li><a elementtofocus="gallery" ga-attr="<?=$GA_Tap_On_Navigation_Gallery;?>">Gallery<span></span></a></li>
           <?php } if(!empty($scholarships)){?>
              <li><a elementtofocus="scholarship" ga-attr="<?=$GA_Tap_On_Navigation_Scholr;?>">Scholarships</a></li>
           <?php } if(!empty($cutOffData)){?>
              <li><a elementtofocus="clgcutoff" ga-attr="<?=$GA_Tap_On_Navigation_Cutoff?>">Cut-Offs</a></li>   
           <?php } if(!empty($faculty)) {?>
              <li><a elementtofocus="faculty" ga-attr="<?=$GA_Tap_On_Navigation_Faculty;?>">Faculty</a></li>
           <?php } if(!empty($events)){?>
              <li><a elementtofocus="events" ga-attr="<?=$GA_Tap_On_Navigation_Event;?>">Events</a></li>
           <?php } if(trim($articleWidget)){?>
              <li><a elementtofocus="articles" ga-attr="<?=$GA_Tap_On_Navigation_Article;?>">Articles<span></span></a></li>
            <?php } if(trim($contactWidget)){?>
              <li><a elementtofocus="contact" ga-attr="<?=$GA_Tap_On_Navigation_Contact;?>">Contact</a></li>
           <?php } ?>
        </ul>
    </div>
</div>