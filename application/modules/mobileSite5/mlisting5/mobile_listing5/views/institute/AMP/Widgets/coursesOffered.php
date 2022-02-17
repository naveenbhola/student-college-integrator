<?php

  $GA_Tap_On_Pop_Course = 'POPULAR_COURSE';
  $GA_Tap_On_Feat_Course = 'FEATURED_COURSE';
  $GA_Tap_On_View_All_Course = 'VIEW_ALL_COURSES';
  $GA_Tap_On_BIP_TAG = 'BIP_Tag';
  $GA_Tap_On_SIP_TAG= 'SIP_Tag';


if($coursesWidgetData['allCourses']){

    $courseText = $coursesWidgetData['coursesShownCount'] > 1 ? 'Courses' : 'Course';

    $streamSpecializationText = "";
    $courseNameLimit = 90;
    $instituteNameLimit = 90;

    if($coursesWidgetData['streamObjects']){
        $streamCount = count($coursesWidgetData['streamObjects']);
        $streamSpecializationText.= " in <strong>".$streamCount." stream".($streamCount > 1 ? 's' : '')."</strong>";
    }

    if($coursesWidgetData['specializationIds']){
        if($streamSpecializationText)
            $streamSpecializationText.= " and ";
        $specializationCount = count($coursesWidgetData['specializationIds']);
        $streamSpecializationText.= " <strong>".$specializationCount." specialization".($specializationCount > 1 ? 's' : '')."</strong>";
    }
?>
         <section>
           <div class="data-card m-btm">
               <h2 class="color-3 f16 heading-gap font-w6">Courses & Fees</h2>
               <p class="color-3 f11 m-5btm p-l11">This college offers <strong class="font-w6"><?php echo $coursesWidgetData['totalCourseCount']." ".$courseText;?></strong><?php echo $streamSpecializationText;?></p>
                <?php 
                if($coursesWidgetData['featuredCourseList']){
                ?>


            <div class="card-cmn color-w">

            <?php 
            
            $showBaseCourseBrowseSection = false;
            $showStreamBrowseSection = false;

            if($coursesWidgetData['baseCourseObjects'] && count($coursesWidgetData['baseCourseObjects']) > 1){
                $showBaseCourseBrowseSection = true;
            }
            if($coursesWidgetData['streamObjects'] && count($coursesWidgetData['streamObjects']) > 1){
                $showStreamBrowseSection = true;  
            }


            if(($showBaseCourseBrowseSection || $showStreamBrowseSection) && count($coursesWidgetData['remainingCourses']) > 0){

                $allCoursePageUrlAlias = $this->config->item('FIELD_ALIAS');
                ?>
                <div>
                    <?php
                    
                    if($showBaseCourseBrowseSection){
                        ?>
                        <div class="brws-div">
                            <strong>Browse By Courses</strong>
                            <div>
                                <div class="browse-row" id="lessTags">
                                    <?php
                                    foreach ($coursesWidgetData['baseCourseObjects'] as $baseCourseObj) {
                                        $currentCityId        = $currentLocationObj->getCityId();
                                        $currentLocalityId    = $currentLocationObj->getLocalityId();
                                        $cityQueryParam       = $allCoursePageUrlAlias['city'];
                                        $localityQueryParam   = $allCoursePageUrlAlias['locality'];

                                        $baseCourseUrlData = array('id' => $baseCourseObj->getId(), 'name' => $baseCourseObj->getName());
                                        $allCourseChildUrl = $this->allCoursesPageLib->getUrlForAppliedFilters(array('base_course' => $baseCourseUrlData), $instituteObj);

                                        // retain base course, location params to the all course page
                                        $queryParams   = array();
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
                                        <a class="a-lnk ga-analytic" data-vars-event-name="<?=$GA_Tap_On_BIP_TAG;?>" href="<?php echo $url;?>" target="_blank"><?php echo htmlentities($baseCourseObj->getName()); ?></a>
                                        <?php
                                    }
                                    ?>
                                    <!-- <a href="javascript:void(0);" id="viewAllTags" class="link">View More</a> -->
                                </div>
                            </div>  
                        </div>
                        <?php 
                    }
                   
                    if($showStreamBrowseSection){
                        ?>
                        <div class="brws-div">
                            <strong>Browse By Streams</strong>
                            <div>
                                <div class="browse-row" id="lessTags">
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
                                        <a class="a-lnk ga-analytic" data-vars-event-name="<?=$GA_Tap_On_SIP_TAG;?>" href="<?php echo $url;?>" target="_blank"><?php echo htmlentities($streamObj->getName()); ?></a>
                                        <?php
                                    }
                                    ?>
                                    <!-- <a href="javascript:void(0);" id="viewAllTags" class="link">View More</a> -->
                                </div>
                            </div>  
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }

            if($coursesWidgetData['popularCourseList']){
            ?>





                
                   <h3 class="color-6 font-w6 f14 padb clg-pl-subHead  m-btm">Popular Courses</h3>
                    <ul class="course-li">
                    <?php
                  foreach ($coursesWidgetData['popularCourseList'] as $popularCourseId) {

                    $courseObj = $coursesWidgetData['allCourses'][$popularCourseId];
                    
                    if(empty($courseObj)){
                        continue;
                    }
                    $seoUrl = '';
                    $seoUrl = $courseObj->getURL();
                    $seoUrl = $seoUrl ? $seoUrl : '#';

                    $courseExtraData = getExtraInfo($courseObj, true);

                    $courseName = $courseObj->getName();
                    $courseNameTrimmedVersion = strlen($courseName) > $courseNameLimit ? substr($courseName, 0, $courseNameLimit)."..." : $courseName;

                    $feesHTML = "";
                    if($courseExtraData['fees'])
                        $feesHTML = "<i class='rupee-icn'>".$courseExtraData['feesUnit']."</i> ".$courseExtraData['fees'];

                    $offeredByInstitute = $courseObj->getOfferedByShortName();
                    $offeredByInstitute = trim($offeredByInstitute);
                    $offeredByInstituteTrimmedVersion = strlen($offeredByInstitute) > $instituteNameLimit ? substr($offeredByInstitute, 0, $instituteNameLimit)."..." : $offeredByInstitute;
                  ?>
                       <li class="ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Pop_Course;?>">
                         <a class="block" href="<?php echo $seoUrl;?>">
                          <div class="table">
                            <span title="<?php echo htmlentities($courseName);?>" class="f14 color-3 tab-cell v-mdl l-20 font-w6 crs-titl"><?php echo htmlentities($courseNameTrimmedVersion);?>
                              <span class="agg-review">
                              <?php
                              if(!empty($courseWidgetData['courseReviewRatingData'][$popularCourseId]) && $courseWidgetData['courseReviewRatingData'][$popularCourseId]['aggregateRating']['averageRating'] > 0){ ?>
                                <div class="new_rating">
                                  <?php
                                $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidgetAmp", array( 'aggregateReviewsData' => $courseWidgetData['courseReviewRatingData'][$popularCourseId],'reviewsCount' => $courseObj->getReviewCount(),'dontHover' => 1, 'dontRedirect' => 1 ,'id' =>'course'.$courseObj->getId() ));
                                ?>
                              </div>
                              <?php
                              }
                              ?>
                              </span>
                            </span> 
                            <span class="tab-cell f13 color-3 crs-inf font-w6 t-right v-mdl"><?php echo $feesHTML;?></span>
                          </div>
                          <?php if($offeredByInstitute && $listing_type == 'university'){ ?>
                        <div class="ofr-by"><?php echo "Offered by ".htmlentities($offeredByInstituteTrimmedVersion);?></div>
                    <?php } ?>
                        </a>
                      </li>
                      <?php
                }
            ?>
                    </ul>
                     <?php
                }
            ?>
                </div>

           </div>
           <?php
           }?>
           <div class="data-card m-btm">
                <?php
                if($coursesWidgetData['featuredCourseList']){
                ?>
                <div class="card-cmn color-w">
                   <h3 class="color-6 font-w6 f14 padb border-btm1 m-btm">Featured Courses</h3>
                    <ul class="course-li">
                    <?php
                  foreach ($coursesWidgetData['featuredCourseList'] as $popularCourseId) {

                    $courseObj = $coursesWidgetData['allCourses'][$popularCourseId];
                    
                    if(empty($courseObj)){
                        continue;
                    }
                    $seoUrl = '';
                    $seoUrl = $courseObj->getURL();
                    $seoUrl = $seoUrl ? $seoUrl : '#';

                    $courseExtraData = getExtraInfo($courseObj, true);

                    $courseName = $courseObj->getName();
                    $courseNameTrimmedVersion = strlen($courseName) > $courseNameLimit ? substr($courseName, 0, $courseNameLimit)."..." : $courseName;

                    $feesHTML = "";
                    if($courseExtraData['fees'])
                        $feesHTML = "<i class='rupee-icn'>".$courseExtraData['feesUnit']."</i> ".$courseExtraData['fees'];

                    $offeredByInstitute = $courseObj->getOfferedByShortName();
                    $offeredByInstitute = trim($offeredByInstitute);
                    $offeredByInstituteTrimmedVersion = strlen($offeredByInstitute) > $instituteNameLimit ? substr($offeredByInstitute, 0, $instituteNameLimit)."..." : $offeredByInstitute;
                  ?>
                       <li class="ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Feat_Course;?>">
                         <a class="block" href="<?php echo $seoUrl;?>">
                          <div class="table">
                            <span title="<?php echo htmlentities($courseName);?>" class="f13 color-3 tab-cell v-mdl l-20 font-w6 crs-titl"><?php echo htmlentities($courseNameTrimmedVersion);?></span>
                            <span class="tab-cell f13 color-3 crs-inf font-w6 t-right v-mdl"><?php echo $feesHTML;?></span>
                          </div>
                          <?php if($offeredByInstitute && $listing_type == 'university'){ ?>
                        <div class="ofr-by"><?php echo "Offered by ".htmlentities($offeredByInstituteTrimmedVersion);?></div>
                    <?php } ?>
                        </a>
                      </li>
                      <?php
                }
            ?>
                    </ul>
                </div>
                         
           </div>
           <?php
        }?>
        <?php 
        $remaingCourses = count($coursesWidgetData['remainingCourses']);
        if($remaingCourses){

            $remaingCoursesText    = "View all ".$coursesWidgetData['totalCourseCount']." course".($coursesWidgetData['totalCourseCount'] > 1 ? 's' : '');
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
        <a href="<?php echo $url;?>" class="btn btn-secondary color-w color-b f14 font-w6 pos-rl top-minus ga-analytic" data-vars-event-name="<?=$GA_Tap_On_View_All_Course;?>"><?php echo $remaingCoursesText;?></a>
        <?php
        }
        ?>                 
        </section>

<?php 
}
?>