<?php 
  $GA_Tap_On_Browse_Course = 'BIP_TAG';
  $GA_Tap_On_Browse_Stream = 'SIP_TAG';
  $GA_Tap_On_View_More_BCourse = 'VIEW_MORE_BROWSE_COURSE';
  $GA_Tap_On_View_More_BStream = 'VIEW_MORE_BROWSE_STREAM';
  $courseText = $coursesWidgetData['coursesShownCount'] > 1 ? 'Courses' : 'Course';
  $feesText = $coursesWidgetData['coursesShownCount'] > 1 ? '& Fees' : '& Fee';

if($coursesWidgetData['allCourses']){
?>

   <h2 class="head-1 gap"><?php echo $courseText." ".$feesText;?> <span class="para-6">(Showing <?php echo $coursesWidgetData['coursesShownCount']." of ".$coursesWidgetData['totalCourseCount']." ".$courseText?>)</span></h2>
<div class="pad-lt">
         <div class="">
</div>
<!-- <div class="course-search">
    <input type="name" placeholder="Find Course" class="" />
</div> -->

</div>
<?php
}

$showBaseCourseBrowseSection = false;
$showStreamBrowseSection = false;
if($coursesWidgetData['baseCourseObjects'] && count($coursesWidgetData['baseCourseObjects']) > 0){
  $showBaseCourseBrowseSection = true;
}
if($coursesWidgetData['streamObjects'] && count($coursesWidgetData['streamObjects']) > 0){
  $showStreamBrowseSection = true;  
}

if(($showBaseCourseBrowseSection || $showStreamBrowseSection) && count($coursesWidgetData['allCourses']) > 0){
  $allCoursePageUrlAlias = $this->config->item('FIELD_ALIAS');
?>
<!--browse by courses-->
<div class="brwse-data">
<?php
  if($showBaseCourseBrowseSection){
?>
  <div class="browse-course-col listingTuple" id="browseByCourseSec" >
  <div class="brs-crs-cont">
      <div class="browse-head default">Browse by Courses</div>
  </div>
      <div class="c-ofr-widget">
<?php
  foreach ($coursesWidgetData['baseCourseObjects'] as $baseCourseObj) {

    $currentCityId        = $currentLocationObj->getCityId();
    $currentLocalityId    = $currentLocationObj->getLocalityId();
    $cityQueryParam       = $allCoursePageUrlAlias['city'];
    $localityQueryParam   = $allCoursePageUrlAlias['locality'];

    // retain base course, location params to the all course page
    $queryParams   = array();
    $baseCourseUrlData = array('id' => $baseCourseObj->getId(), 'name' => $baseCourseObj->getName());
    $allCourseChildUrl = $this->allCoursesPageLib->getUrlForAppliedFilters(array('base_course' => $baseCourseUrlData), $instituteObj);
    // if($listing_type == 'institute'){
    //     if($currentCityId){
    //       $queryParams[] = $cityQueryParam."[]=".$currentCityId;
    //     }
    //     if($currentLocalityId){
    //       $queryParams[] = $localityQueryParam."[]=".$currentLocalityId;
    //     }
    // }

    $url = $allCourseChildUrl;
    if(!empty($queryParams)){
      $url .= "?".implode("&", $queryParams);
    }
?>
    <div class="courses-offeredbtn c-ofr-lnk" ga-optlabel='Click_BIP_Tag'  ga-attr="<?=$GA_Tap_On_Browse_Course?>"><a target="_blank" href="<?php echo $url;?>"><?php echo htmlentities($baseCourseObj->getName()); ?></a></div>
<?php  
  }
 
?>
<?php /*
  <div class="link courses-offeredbtn shw-all-btn" ga-attr="<?=$GA_Tap_On_View_More_BCourse?>" onclick="showAllCoursesLink(this);">view more</div>
  <?php */ ?>
  </div>
  </div>  
<?php 
  }
if($showStreamBrowseSection){
?>
<div class="browse-course-col" id="streamByCourseSec">
  <div class="brs-crs-cont">
      <div class="browse-head default">Browse by Streams</div>
  </div>
      <div class="c-ofr-widget">
<?php
  foreach ($coursesWidgetData['streamObjects'] as $streamObj) {

    $currentCityId      = $currentLocationObj->getCityId();
    $currentLocalityId  = $currentLocationObj->getLocalityId();
    $cityQueryParam     = $allCoursePageUrlAlias['city'];
    $localityQueryParam = $allCoursePageUrlAlias['locality'];

    // retain stream, location params to the all course page
    $queryParams   = array();
    $streamUrlData = array('id' => $streamObj->getId(), 'name' => $streamObj->getName());
    $allCourseChildUrl = $this->allCoursesPageLib->getUrlForAppliedFilters(array('stream' => $streamUrlData), $instituteObj);

    // if($listing_type == 'institute'){
    //     if($currentCityId){
    //       $queryParams[] = $cityQueryParam."[]=".$currentCityId;
    //     }
    //     if($currentLocalityId){
    //       $queryParams[] = $localityQueryParam."[]=".$currentLocalityId;
    //     }
    // }

    $url = $allCourseChildUrl;
    if(!empty($queryParams)){
      $url .= "?".implode("&", $queryParams);
    }
?>
    <div class="courses-offeredbtn c-ofr-lnk"  ga-optlabel='Click_SIP_Tag' ga-attr="<?=$GA_Tap_On_Browse_Stream?>"><a target="_blank" href="<?php echo $url;?>"><?php echo htmlentities($streamObj->getName()); ?></a></div>
<?php  
  }
?>
    <?php /*
  <div class="link courses-offeredbtn shw-all-btn" ga-attr="<?=$GA_Tap_On_View_More_BStream?>" onclick="showAllCoursesLink(this);">view more</div>
    <?php */ ?>
  </div>
  </div>  
<?php
}
?>
</div>
<?php
}
?>
