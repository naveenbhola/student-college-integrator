<?php 
    $GA_Tap_On_Pop_Course = 'POPULAR_COURSE';
    $GA_Tap_On_Feat_Course = 'FEATURED_COURSE';
    $GA_Tap_On_View_All_Course = 'VIEW_ALL_POPULAR_COURSES';

    $univClass = "";
    if($listing_type == 'university'){
      $univClass = 'university-courses';
    }
?>
<div class="new-row <?php echo $univClass;?>">
  <div class="courses-offered listingTuple" id="course-offer">
<?php
  
  $courseNameLimit = 60;
  $collegeNameLimit = 60;
  if($coursesWidgetData['featuredCourseList']){
?>
<!--all courses--> 
        <div class="group-card gap no__pad">        
        <?php 
          $this->load->view('InstitutePage/BrowseCourseWidget'); 
          ?>
         <div class="cmn-cl">
        <?php
      
          foreach ($coursesWidgetData['popularCourseList'] as $popularCourseId) {
            
              $courseObj = $coursesWidgetData['allCourses'][$popularCourseId];
        
              $seoUrl = '';
              if($courseObj){
                $extraInfo = getCourseTupleExtraData($courseObj);
                
                $seoUrl = $courseObj->getURL();
                $seoUrl = $seoUrl ? $seoUrl : '#';
                
                $courseName = $courseObj->getName();
                $courseNameTrimmedVersion = strlen($courseName) > $courseNameLimit ? substr($courseName, 0, $courseNameLimit)."..." : $courseName;
                $offeredByInstitute = $courseObj->getOfferedByShortName();
                $offeredByInstitute = trim($offeredByInstitute);
                $offeredByLimitedText = "";
                if($offeredByInstitute)
                  $offeredByLimitedText = strlen($offeredByInstitute) > $collegeNameLimit ? substr($offeredByInstitute, 0, $collegeNameLimit)."..." : $offeredByInstitute;
        ?>
          <div class="col-md-4">
            <a href="<?php echo $seoUrl;?>" target="_blank" ga-attr="<?=$GA_Tap_On_Pop_Course?>">
             <div class="college-block lt-col">
              <div class="offered-name">
               <p class="para-1" title="<?php echo htmlentities($courseName);?>"><?php echo htmlentities($courseNameTrimmedVersion); ?></p>
                <?php if($listing_type == 'university' && $offeredByLimitedText){ ?>
                  <p class="of-txts" style="margin-top: 8px;" title="<?php echo "Offered by ".htmlentities($offeredByInstitute);?>"><?php echo "Offered by ".htmlentities($offeredByLimitedText);?></p>
                <?php } ?>
             </div>
             <div class="extra_info n-b">
               <?php echo $extraInfo;?>
             </div>
             <div class="extra_info n-b">
                <?php
                if(!empty($courseWidgetData['courseReviewRatingData'][$popularCourseId]) && $courseWidgetData['courseReviewRatingData'][$popularCourseId]['aggregateRating']['averageRating'] > 0){ 
                
                  $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array( 'aggregateReviewsData' => $courseWidgetData['courseReviewRatingData'][$popularCourseId],'reviewsCount' => $courseObj->getReviewCount(),'dontHover' => 1, 'dontRedirect' => 1));
                }
                ?>
             </div>
               <i class="arr__after"></i>
             </div>
             </a>
          </div>
        <?php
              }
          }

          if($coursesWidgetData['featuredCourseList']){
           
          foreach ($coursesWidgetData['featuredCourseList'] as $featuredCourseId) {

          $courseObj = $coursesWidgetData['allCourses'][$featuredCourseId];
          if($courseObj){

            $extraInfo = getCourseTupleExtraData($courseObj);

            $seoUrl = $courseObj->getURL();
            $seoUrl = $seoUrl ? $seoUrl : '#';

            $courseName = $courseObj->getName();
            $courseNameTrimmedVersion = strlen($courseName) > $courseNameLimit ? substr($courseName, 0, $courseNameLimit)."..." : $courseName;
            $offeredByInstitute = $courseObj->getOfferedByShortName();
            $offeredByInstitute = trim($offeredByInstitute);
            $offeredByLimitedText = "";
              if($offeredByInstitute)
                $offeredByLimitedText = strlen($offeredByInstitute) > $collegeNameLimit ? substr($offeredByInstitute, 0, $collegeNameLimit)."..." : $offeredByInstitute;
        ?>
           <div class="col-md-4">
           <a href="<?php echo $seoUrl;?>" target="_blank" ga-attr="<?=$GA_Tap_On_Feat_Course?>">
            <div class="college-block lt-col">
              <div class="offered-name">
               <p class="para-1" title="<?php echo htmlentities($courseName);?>"><?php echo htmlentities($courseNameTrimmedVersion); ?></p>
               <?php if($listing_type == 'university' && $offeredByLimitedText){ ?>
                  <p class="of-txts" style="margin-top: 8px;" title="<?php echo "Offered by ".htmlentities($offeredByInstitute);?>"><?php echo "Offered by ".htmlentities($offeredByLimitedText);?></p>
                <?php } ?>
             </div>
              <div class="extra_info n-b">
                <?php echo $extraInfo;?>
             </div>
             <div class="extra_info n-b">
                <?php
                if(!empty($courseWidgetData['courseReviewRatingData'][$featuredCourseId]) && $courseWidgetData['courseReviewRatingData'][$featuredCourseId]['aggregateRating']['averageRating'] > 0){ 
                
                  $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array( 'aggregateReviewsData' => $courseWidgetData['courseReviewRatingData'][$featuredCourseId],'reviewsCount' => $courseObj->getReviewCount(),'dontHover' => 1, 'dontRedirect' => 1));
                }
                ?>
              </div>
               <i class="arr__after"></i>
            </div>
           </a>
           </div>
        <?php
          }
        }
      }

          $remaingCourses = count($coursesWidgetData['remainingCourses']);
          if($remaingCourses){
            $currentCityId         = $currentLocationObj->getCityId();
            $currentLocalityId     = $currentLocationObj->getLocalityId();
            $allCoursePageUrlAlias = $this->config->item('FIELD_ALIAS');
            $cityQueryParam        = $allCoursePageUrlAlias['city'];
            $localityQueryParam    = $allCoursePageUrlAlias['locality'];

            // retain location params to the all course page
            $queryParams   = array();
            if($listing_type == 'institute'){
                if($currentCityId){
                  $queryParams[] = $cityQueryParam."[]=".$currentCityId;
                }
                if($currentLocalityId){
                  $queryParams[] = $localityQueryParam."[]=".$currentLocalityId;
                }
            }

            $url = $allCoursePageUrl;
            if(!empty($queryParams)){
              $url .= "?".implode("&", $queryParams);
            }
        ?>
          <div class="col-md-4">
             <div class="college-block lt-col t-cntr">
              <a class="btn-secondary btn-medium cmp-btn" ga-attr="<?=$GA_Tap_On_View_All_Course?>" href="<?php echo $url;?>" target="_blank">View All <?php echo $coursesWidgetData['totalCourseCount'];?> Courses</a>
             </div>
          </div>
        <?php
          }
        ?>
         </div>
        </div>
<?php 
}
?>
</div>
</div>
