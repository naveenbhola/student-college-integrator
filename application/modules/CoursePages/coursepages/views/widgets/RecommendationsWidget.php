<?php
    $data = $widgets_data['recommendationsWidget'];
    $numOfSlides = $data['numOfSlides'];
    $uniqId = $data['uniqId'];
    $recommendationsApplied = $data['recommendationsApplied'];
    $courseData = $data['courseData'];
    $brochureURL = $data['brochureURL'];
?>

<div <?php echo $cssClass; ?> id="<?php echo $widgetObj->getWidgetKey().'Container'; ?>">
    <div class="recommend-course-head">
        <h2 class="flLt"><?php echo $data['widgetHeading']; ?></h2>
        <p>Recommendation based on Colleges you viewed</p>
        <div class="next-rev">
            <a id="prev<?php echo $uniqId; ?>" class="prev-icon" href="javascript:void(0)" onclick="slideLeft('<?php echo $uniqId; ?>', true);"></a>
            <a uniqueattr="Recommendations/NextPage" id="next<?php echo $uniqId; ?>" class="next-icon-active" href="javascript:void(0)" onclick="slideRight('<?php echo $uniqId; ?>', true);"></a>  
        </div>
        <div class="clearFix"></div>
    </div>
    
    <div id="RecommendationWidgetLoad" class="recommend-course-details" style="width: 460px; overflow: hidden;">
        <div id="slideContainer<?php echo $uniqId; ?>" style="width:1380px; position: relative; left:0px;">
                <?php
                    $count = 1;
                    $courseIDArray = array();
                    foreach($courseData as $instituteId => $courseInfo) {
                    	$courseIDArray[] = $courseInfo['courseId'];
                    }
                    if(count($courseIDArray) > 0) {
                    $this->national_course_lib 	= $this->load->library('listing/NationalCourseLib');
                    $reviewsData = $this->national_course_lib->getCourseReviewsData($courseIDArray);
                    $reviewsData = $this->national_course_lib->getCollegeReviewsByCriteria($reviewsData);
                    }
                   
                    foreach($courseData as $instituteId => $courseInfo) {
                        $courseId = $courseInfo['courseId'];
                        $isPaid = $courseInfo['isPaid'];
                        $instituteFullName = $courseInfo['instituteFullName'];
                        $courseFullName = $courseInfo['courseFullName'];
                        $instituteName = $courseInfo['instituteName'];
                        $courseName = $courseInfo['courseName'];
                        $instituteHeaderImage = $courseInfo['instituteHeaderImage'];
                        $instituteThumbURL = $courseInfo['instituteThumbURL'];
                        $courseURL = $courseInfo['courseURL'];
                        $mainCityName = $courseInfo['mainCityName'];
                        $mainCityId = $courseInfo['mainCityId'];
                        $mainLocalityId = $courseInfo['mainLocalityId'];
                        $cutoff = $courseInfo['eligibility'];
                        $fees = $courseInfo['fees'];
                        $ranking = $courseInfo['ranking'];
                        $duration = $courseInfo['duration'];
                        $mode = $courseInfo['mode'];
                        $algo = $courseInfo['algo'];
                        $onReqBroClickAction = $courseInfo['onReqBroClickAction'];
                        $onActivityTrackAction = $courseInfo['onActivityTrackAction'];
                        $paramsForApply = $courseInfo['paramsForApply'];
                        
                        if(($count - 1) % 3 == 0) {
                            echo '<div class="recomend-courseList"><ul>';
                        }
                ?>
                    <li style="height:172px;" onmouseover="highlightRequestButton(<?php echo $instituteId; ?>);" onmouseout="removeRequestButtonHighlight(<?php echo $instituteId; ?>);">
                        <div class="flLt inst-figure">
                            <?php
                                if($instituteHeaderImage && $instituteThumbURL) {
                                    echo '<a href="'.$courseURL.'" rel="nofollow" '.$target.'><img src="'.$instituteThumbURL.'" width="118" alt="'.html_escape($instituteFullName).'" title="'.html_escape($instituteFullName).'" onmouseover="underlineCourseLink('.$instituteId.');" onmouseout="removeCourseLinkUnderline('.$instituteId.');" '.$onActivityTrackAction.'/></a>';
                                }
                                else {
                                    echo '<a href="'.$courseURL.'" rel="nofollow" '.$target.'><img src="/public/images/avatar.gif" alt="'.html_escape($instituteFullName).'" title="'.html_escape($instituteFullName).'" onmouseover="underlineCourseLink('.$instituteId.');" onmouseout="removeCourseLinkUnderline('.$instituteId.');" '.$onActivityTrackAction.'/></a>';
                                }
                            ?>
                        </div>
                        <div class="recommend-course-child">
                            <p class="font-14" title="<?php echo $instituteFullName; ?>"><?php echo $instituteName ?></p>
                            <p class="dark-gray"><?php echo $mainCityName; ?></p>
                            <p><a id="courseLink_<?php echo $instituteId; ?>" href="<?php echo $courseURL; ?>" title="<?php echo $courseFullName; ?>" onmouseover="underlineCourseLink(<?php echo $instituteId; ?>);" onmouseout="removeCourseLinkUnderline(<?php echo $instituteId; ?>);" <?php echo $onActivityTrackAction; ?>><?php echo $courseName ?></a></p>
                            
                            <?php
                                if(!empty($cutoff)) {
                                    echo '<p class="dark-gray"> Eligibility: '.$cutoff.'</p>';
                                }
                                else if(!empty($fees)) {
                                    echo '<p class="dark-gray"> Fees: '.$fees.'</p>';
                                }
                                else if(!empty($ranking)) {
                                    echo '<p class="dark-gray"> Ranking: '.$ranking.'</p>';
                                }
                                else if(!empty($duration)) {
                                    echo '<p class="dark-gray"> Duration: '.$duration.'</p>';
                                }
                                else if(!empty($mode)) {
                                    echo '<p class="dark-gray"> Mode: '.$mode.'</p>';
                                }
                            ?>
                        <?php 
                        if(isset($reviewsData[$courseId]['overallRating'])) {?>    
                        <div class="clear-width" style="position:relative">    
                        <div class="flLt avg-rating-title">Alumni Rating: </div>
                        <div onmouseover="showReviewsToolTip(this);" onmouseout="hideReviewsToolTip();" class="ranking-bg"><?=$reviewsData[$courseId]['overallRating']; ?><sub>/5</sub></div>
                         
                        </div>
                        <?php }?>
                            <?php

                                
                                if($isPaid == 1 || $brochureURL[$courseId] != '') {
                            ?>
                                <p><a id="apply_button<?php echo $instituteId; ?>" href="javascript:void(0)" <?php if(in_array($courseId,$recommendationsApplied)) { echo 'class = "white-disabled white-button" onclick = "return false;"'; } else { echo 'class = "white-button" '.$onReqBroClickAction; } ?>>Download E-Brochure</a></p>
                                <div id="apply_confirmation<?php echo $instituteId; ?>" class="req-thnx" <?php if(in_array($courseId,$recommendationsApplied)) { echo "style='display:block;'"; } ?>>
                                    <i class="tick-icon"></i>
                                    <p style="float:left;">E-brochure successfully mailed</p>
                                </div>
                                <input type="hidden" id="apply_status<?php echo $instituteId; ?>" value="<?php if(in_array($courseId,$recommendationsApplied)) echo '1'; else echo '0'; ?>"/>
                                <input type="hidden" id="params<?php echo $instituteId; ?>" value='<?php echo $paramsForApply; ?>'/>
                            <?php
                                }
                            ?>
                        </div>
                     </li>
                <?php
                        if($count % 3 == 0) {
                            echo '</ul></div>';
                        }
                        $count++;
                    }
                    if(($count - 1) % 3 != 0) {
                        echo '</ul></div>';
                    }
                ?>
            </div>
            <div class="clearFix"></div>
        
    </div>
    <div class="recommend-slider">
        <ul class="slider-control slider-control2">            
            <?php
		if($numOfSlides == 1) {
		    echo '<li id="recoSliderButton1'.$uniqId.'" class="active" onclick="changeSlide(1, \''.$uniqId.'\', true);"></li>';
		}
                else if($numOfSlides == 2){
		    echo '<li id="recoSliderButton1'.$uniqId.'" class="active" onclick="changeSlide(1, \''.$uniqId.'\', true);"></li><li id="recoSliderButton2'.$uniqId.'" onclick="changeSlide(2, \''.$uniqId.'\', true);"></li>';
		}
		else if($numOfSlides == 3){
		    echo '<li id="recoSliderButton1'.$uniqId.'" class="active" onclick="changeSlide(1, \''.$uniqId.'\', true);"></li><li id="recoSliderButton2'.$uniqId.'" onclick="changeSlide(2, \''.$uniqId.'\', true);"></li><li id="recoSliderButton3'.$uniqId.'" onclick="changeSlide(3, \''.$uniqId.'\', true);"></li>';
		}
	    ?>
        </ul>
    </div>


   
 </div>

<script>
function showReviewsToolTip(thisObj) {
	
	$j('.avgRating-tooltip').css(
		
		{'top':$j(thisObj).offset().top+27+'px',
		'left': $j(thisObj).offset().left-95+'px'}
		);
		$j('.avgRating-tooltip').show();
}

function hideReviewsToolTip() {
	$j('.avgRating-tooltip').hide();
	}

	    
var slideWidth = 460;
if (typeof(numSlides) == 'undefined') {
    numSlides = {};
    currentSlide = {};
    firstSlide = {};
    lastSlide = {};
}

numSlides['<?php echo $uniqId; ?>'] = <?php echo $numOfSlides; ?>;
currentSlide['<?php echo $uniqId; ?>'] = 0;
firstSlide['<?php echo $uniqId; ?>'] = 0;
lastSlide['<?php echo $uniqId; ?>'] = (numSlides['<?php echo $uniqId; ?>'] - 1) * (-1);

if (numSlides['<?php echo $uniqId; ?>'] == 1) {
    $('prev<?php echo $uniqId; ?>').className = 'prev-icon';
    $('next<?php echo $uniqId; ?>').className = 'next-icon';
}

</script>