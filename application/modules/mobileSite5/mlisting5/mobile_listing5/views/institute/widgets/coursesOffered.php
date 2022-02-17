<?php 
    $GA_Tap_On_Pop_Course = 'POPULAR_COURSE';
    $GA_Tap_On_Feat_Course = 'FEATURED_COURSE';
    $GA_Tap_On_View_All_Course = 'VIEW_ALL_COURSES';
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
<div id="course" class="listingTuple">
<div class="normal-card crs-widget" >
        <h2 class="head-L2">Courses Offered</h2>
        <p class="head-L5 c-ofrd">This college offers <strong><?php echo $coursesWidgetData['totalCourseCount']." ".$courseText;?></strong><?php echo $streamSpecializationText;?></p>
    </div>
    <div class="crs-tpl">
    <?php 
        if($coursesWidgetData['popularCourseList']){
    ?>
        <div class="lcard">
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
                    //die('qqqqqqqqqqqq'); 
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
                                        if($listing_type == 'institute'){
                                            if($currentCityId){
                                              $queryParams[] = $cityQueryParam."[]=".$currentCityId;
                                            }
                                            if($currentLocalityId){
                                              $queryParams[] = $localityQueryParam."[]=".$currentLocalityId;
                                            }
                                        }

                                        $url = $allCourseChildUrl;
                                        if(!empty($queryParams)){
                                          $url .= "?".implode("&", $queryParams);
                                        }
                                        ?>
                                        <a class="a-lnk" href="<?php echo $url;?>" target="_blank" ga-attr="<?=$GA_Tap_On_Browse_Course?>"><?php echo htmlentities($baseCourseObj->getName()); ?></a>
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

                                        if($listing_type == 'institute'){
                                            if($currentCityId){
                                              $queryParams[] = $cityQueryParam."[]=".$currentCityId;
                                            }
                                            if($currentLocalityId){
                                              $queryParams[] = $localityQueryParam."[]=".$currentLocalityId;
                                            }
                                        }

                                        $url = $allCourseChildUrl;
                                        if(!empty($queryParams)){
                                          $url .= "?".implode("&", $queryParams);
                                        }
                                        ?>
                                        <a class="a-lnk" href="<?php echo $url;?>" target="_blank" ga-attr="<?=$GA_Tap_On_Browse_Stream?>"><?php echo htmlentities($streamObj->getName()); ?></a>
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
            ?>
            <h3 class="clg-pl-subHead">Popular Courses</h3>
            <ul class="pop-crsList">
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
                <li ga-attr="<?=$GA_Tap_On_Pop_Course;?>">
                    <a href="<?php echo $seoUrl;?>">
                    <div class="crs-title">
                    
                        <span title="<?php echo htmlentities($courseName);?>"><?php echo htmlentities($courseNameTrimmedVersion);?>
                            <span class="agg-review">
                            <?php
                            if(!empty($courseWidgetData['courseReviewRatingData'][$popularCourseId]) && $courseWidgetData['courseReviewRatingData'][$popularCourseId]['aggregateRating']['averageRating'] > 0){ 
                
                                $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array( 'aggregateReviewsData' => $courseWidgetData['courseReviewRatingData'][$popularCourseId],'reviewsCount' => $courseObj->getReviewCount(),'dontHover' => 1, 'dontRedirect' => 1));
                            }
                            ?>
                            </span>    
                        </span>
                        <span class="crs-info"><?php echo $feesHTML;?></span>
                        

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
        <?php 
        }
        if($coursesWidgetData['featuredCourseList']){
        ?>
        <div class="lcard">
            <h3 class="clg-pl-subHead">Featured Courses</h3>

            <ul class="pop-crsList">
             <?php
                foreach ($coursesWidgetData['featuredCourseList'] as $popularCourseId) {

                    $courseObj       = $coursesWidgetData['allCourses'][$popularCourseId];

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
                <li ga-attr="<?=$GA_Tap_On_Feat_Course;?>">
                    <a href="<?php echo $seoUrl;?>">
                    <div class="crs-title">
                        <span title="<?php echo htmlentities($courseName);?>"><?php echo htmlentities($courseNameTrimmedVersion);?></span>
                        <span class="crs-info"><?php echo $feesHTML;?></span>
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
        <?php
        }

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
        <a href="<?php echo $url;?>" class="btn-mob-blue" ga-attr="<?=$GA_Tap_On_View_All_Course;?>"><?php echo $remaingCoursesText;?></a>
        <?php
        }
        ?>
</div>
</div>
<?php 
}
?>
