<?php 
    $anaWidgetHtml = trim($anaWidget['html']);
    $reviewWidgetHtml = trim($reviewWidget['html']);
    $GA_Tap_On_Navigation_Courses_Fees = 'COURSE_FEES_NAVIGATION';
    $GA_Tap_On_Navigation_Review = 'REVIEW_NAVIGATION';
    $GA_Tap_On_Navigation_AnA = 'QnA_NAVIGATION';
    $GA_Tap_On_Navigation_Scholr = 'SCHOLARSHIP_NAVIGATION';
    $GA_Tap_On_Navigation_Cutoff = 'CUTOFF_NAVIGATION';
    $GA_Tap_On_Navigation_Article = 'ARTICLE_NAVIGATION';
    $GA_Tap_On_Navigation_Admission_Cutoff = 'ADMISSION_CUTOFF_NAVIGATION';
  ?>
<div class="clg-nav">
    <div class="clg-navList">
        <ul>
          <?php if(!empty($coursesWidgetData['allCourses'])){ ?>
              <li><a class = "ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Navigation_Courses_Fees;?>" href = "<?php echo $allCoursePageUrl;?>">Courses & Fees</a></li>
          <?php } if(!empty($reviewWidgetHtml)){?>
             <li><a class = "ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Navigation_Review;?>" href = "<?php echo $all_review_url;?>">Reviews<span></span></a></li>    
          <?php } if(!empty($admissionInfo) || (!empty($examList) && count($examList) > 0)) {?>
                <li><a class = "ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Navigation_Admission_Cutoff;?>" href="<?php echo $admissionPageUrl;?>">Admissions & Cut-Offs</a></li>
           <?php } if(!empty($anaWidgetHtml)){?>
              <li><a class = "ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Navigation_AnA;?>"
                href="<?php echo $allQuestionURL;?>">Q&A<span></span></a></li>
           <?php } if(!empty($scholarships)){?>
              <li><a class = "ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Navigation_Scholr;?>" href="<?php echo $instituteObj->getAllContentPageUrl('scholarships');?>">Scholarships</a></li>
           <?php } if(!empty($cutOffData)){?>
              <li><a class = "ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Navigation_Cutoff?>" href="<?php echo $cutOffData['cutOffUrl']; ?>">Cut-Offs</a></li>   
           <?php } if(trim($articleWidget)){?>
              <li><a class = "ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Navigation_Article;?>" href ="<?php echo $all_article_url?>">Articles<span></span></a></li>
           <?php } ?>
        </ul>
    </div>
</div>
