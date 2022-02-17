<?php
if($recommendationsExist) {
    $numOfSlides = ceil($numberOfRecommendations / 3);
?>

<div class="recommend-head"> 
	<h2 class="flLt">Apply to your preferred <?php if($domainSubCatId == MBA_SUBCAT_ID) echo 'MBA'; elseif($domainSubCatId == ENGINEERING_SUBCAT_ID) echo 'Engineering'; ?> colleges</h2>

	<div class="next-rev">
	    <a id="prev<?php echo $uniqId; ?>" href="javascript:void(0)" class="prev-icon" onclick="slideLeft('<?php echo $uniqId; ?>', false);"></a>
	    <a id="next<?php echo $uniqId; ?>" uniqueattr="Recommendations/NextPage" href="javascript:void(0)" class="next-icon-active" onclick="slideRight('<?php echo $uniqId; ?>', false);"></a> 
	</div>
	<div class="clearFix"></div>
    </div>


<div class="recommend-items newRecommendToCrwl" style="width:670px;" >
<h3 style="margin-left: 15px;margin-bottom: 10px;">
    <?php
    if($recommendationsWidgetFlag)
	echo "Want to apply to more colleges? See similar options";
    else
	echo "Complete your application".($numberOfRecommendations == 1 ? '' : 's')." - ".$numberOfRecommendations." ".($numberOfRecommendations == 1 ? 'is' : 'are')." pending";
    ?>
</h3>
<ul id="slideContainer<?php echo $uniqId; ?>" style="width:2010px;position: relative; left:0px;">
	<?php
	foreach($recommendations as $institute) {
		$course = $institute->getFlagshipCourse();
		$instituteId = $institute->getId();
		$courseId = $course->getId();
		$instituteFullName = $institute->getName();
		$courseFullName = $course->getName();
		$instituteName = strlen($instituteFullName) > 40 ? substr($instituteFullName, 0, 40).'...' : $instituteFullName;
		$courseName = strlen($courseFullName) > 45 ? substr($courseFullName, 0, 45).'...' : $courseFullName;
		$instituteHeaderImage = $institute->getMainHeaderImage();
		$instituteThumbURL = $instituteHeaderImage->getThumbURL();
		$courseURL = $course->getURL();
		$instituteURL = $institute->getURL();
		
		if($pageCityId) {
			$courseLocations = $course->getLocations();
			$flg = false;
			foreach($courseLocations as $courseLocation) {
				if($courseLocation->getCity()->getId() == $pageCityId) {
					$mainLocation = $courseLocation;
					$flg = true;
				}
			}
			if(!$flg) {
				foreach($courseLocations as $courseLocation) {
					if($courseLocation->getState()->getId() == $pageStateId) {
						$mainLocation = $courseLocation;
					}
				}
			}
			if(!$mainLocation) {
				$mainLocation = $course->getMainLocation();
			}
		}
		else {
			$mainLocation = $course->getMainLocation();
			
		}
		
		$mainLocationId = $mainLocation->getLocationId();
		$mainCity = $mainLocation->getCity();
		$mainCityName = $mainCity->getName();
		$mainCityId = $mainCity->getId();
		$mainLocality = $mainLocation->getLocality();
		$mainLocalityId = $mainLocality ? $mainLocality->getId() : 0;
		$mainLocalityId = intval($mainLocalityId);
		$exams = $course->getEligibilityExams();
		$cutoff = '';
		foreach($exams as $exam) {
		$examName = $exam->getAcronym();
		$marks = $exam->getMarks();
		if($marks != 0) {
			$cutoff .= $examName.' ('.$marks.'), ';
		}
		}
		$cutoff = substr($cutoff, 0, -2);
		$duration = $course->getDuration()->__toString();
		$applicationFees = $onlineFormData[$courseId]['fees'];
		$applicationDeadline = $onlineFormData[$courseId]['last_date'] ? date("d M y",strtotime($onlineFormData[$courseId]['last_date'])) : '';
		$completeOnlineFormLink = SHIKSHA_HOME.$institutes_autorization_details_array[$courseId]['seo_url'];
		
		$target = '';
	?>
	<li  style="width:207px;float: left;" onmouseover="highlightOnlineFormButton(<?php echo $instituteId; ?>);" onmouseout="removeRequestButtonHighlight(<?php echo $instituteId; ?>);">
	<div class="inst-height">
		<div class="inst-pic">
		<?php
			if($instituteHeaderImage && $instituteThumbURL) {
			echo '<a href="'.$instituteURL.'" rel="nofollow" '.$target.'><img src="'.$instituteThumbURL.'" width="118" alt="'.html_escape($instituteFullName).'" title="'.html_escape($instituteFullName).'" onmouseover="underlineCourseLink('.$instituteId.');" onmouseout="removeCourseLinkUnderline('.$instituteId.');"/></a>';
			}
			else {
			echo '<a href="'.$instituteURL.'" rel="nofollow" '.$target.'><img src="/public/images/avatar.gif" alt="'.html_escape($instituteFullName).'" title="'.html_escape($instituteFullName).'" onmouseover="underlineCourseLink('.$instituteId.');" onmouseout="removeCourseLinkUnderline('.$instituteId.');"/></a>';
			}
		?>
		</div>
		<p class="inst-name" title="<?php echo $instituteFullName; ?>"><a href="<?php echo $instituteURL; ?>" style="color: inherit;"><?php echo $instituteName ?></a></p>
		<p><strong><?php echo $mainCityName; ?></strong></p>
		<p><a id="courseLink_<?php echo $instituteId; ?>" href="<?php echo $courseURL; ?>" title="<?php echo $courseFullName; ?>" <?php echo $target; ?> onmouseover="underlineCourseLink(<?php echo $instituteId; ?>);" onmouseout="removeCourseLinkUnderline(<?php echo $instituteId; ?>);"><?php echo $courseName ?></a></p>
		<?php
			if(!empty($cutoff) && $recommendationsWidgetFlag) {
			    echo '<p><strong>Eligibility:</strong> '.$cutoff.'</p>';
			}
			if(!empty($applicationFees)) {
			    echo '<p><strong>Application Fees:</strong> INR '.$applicationFees.'</p>';
			}
			if(!empty($applicationDeadline)) {
			    echo '<p class="deadline-text"><strong>Application Deadline:</strong> '.$applicationDeadline.'</p>';
			}
			$percentCompletionBoxWidth = 45;
			if(!$recommendationsWidgetFlag && isset($percentCompletion[$courseId])){
			    $percentageCompletion = ($percentCompletion[$courseId]/100)*$percentCompletionBoxWidth;
			    echo '<p><strong class="flLt">Completion Status:</strong>
				<div class="completion-bar" style="width:'.$percentCompletionBoxWidth.'px; margin:4px 0 0 6px;">
				    <div class="completion-bar-percent" style="width:'.$percentageCompletion.'px"></div> 
				</div>
				<p class="percent-box" style="margin-left: 7px;margin-top: 2px; width:27px;">'.$percentCompletion[$courseId].'%</p>
			    </p>';
			}
		if($institutes_autorization_details_array[$courseId])
		{
		?>
		<a id="complete_online_form<?=$instituteId?>" onclick="gaTrackEventCustom('NATIONAL_COURSE_PAGE', 'Online_Form_Widget', '<?php  echo($recommendationsWidgetFlag ? "Apply_Now" : "Complete_Now");?>', event, '<?=$completeOnlineFormLink?>');" href="<?php echo $completeOnlineFormLink;?>" class='white-button'><?php  echo($recommendationsWidgetFlag ? "Apply Now" : "Complete Now");?></a>
		<?php
		}
		?>
	</div>
	
	<?php
		if(($course->isPaid() || $brochureURL[$courseId] != '') && $recommendationsWidgetFlag) {
	?>
	<a id="apply_button<?php echo $instituteId; ?>" href="javascript:void(0)" class="<?php
		if(in_array($courseId,$recommendationsApplied)) {
			echo 'white-disabled white-button';
		}
		else {
			echo 'white-button';
		} ?>" onClick = " <?php
		if(in_array($courseId,$recommendationsApplied)) {
			echo 'return false;';
		}
		else {    //true for all cases
                    if($userInfo != 'false'){
                        $customCallback = 'recoLayerCallback';
                        $customParams = base64_encode(json_encode(array('institute_id' => $instituteId, 'course_id'=>$courseId, 'sourcePage'=>$widget,'clickedCourseId'=>$appliedCourse->getId(),'clickedInstituteId'=>$appliedCourse->getInstId())));
		        echo 'makeResponse('.$instituteId.', \''.base64_encode($instituteName).'\', '.$courseId.', \''.base64_encode($courseName).'\', \''.$customCallback.'\', \'OF_Request_E-Brochure\', \'online_form_reco\',\''.$customParams.'\',\''.DESKTOP_NL_LP_COURSE_BELLY_APPLYONLINE_RECO_DEB.'\');';
			
                    }else{
                        $customParams = '';
			echo 'makeResponse('.$instituteId.', \''.base64_encode($instituteName).'\', '.$courseId.', \''.base64_encode($courseName).'\', undefined, \'OF_Request_E-Brochure\', \'online_form_reco\',\''.$customParams.'\',\''.DESKTOP_NL_LP_COURSE_BELLY_APPLYONLINE_RECO_DEB.'\');';
                    }
		}
		 ?>">Download E-Brochure</a>
	
	<div id="apply_confirmation<?php echo $instituteId; ?>" class="recom-aply-row" <?php if(in_array($courseId,$recommendationsApplied)) echo "style='display:block;'"; ?>>
	    <i class="thnx-icon" style="margin: 0; float: left"></i>
	    <p style="margin:0 0 0 23px;float: none; color: inherit">E-brochure successfully mailed.</p>
	</div>
	<input type="hidden" id="apply_status<?php echo $instituteId; ?>" value="<?php if(in_array($courseId,$recommendationsApplied)) echo '1'; else echo '0'; ?>"/>
	<input type="hidden" id="params<?php echo $instituteId; ?>" value="<?php echo html_escape(getParametersForApply($userInfo,$course,$mainCityId,$mainLocalityId)); ?>"/>
	
	<input type="hidden" id="reco_params_city_<?php echo $instituteId; ?>" value="<?php echo $mainCityId; ?>" />
	<input type="hidden" id="reco_params_locality_<?php echo $instituteId; ?>" value="<?php echo $mainLocalityId; ?>" />
	
	  <!-----------------Add--to-compare--tool--------------------------->
			    
	   <p style="margin-top:4px;">
<input onclick="setCompareCookie('<?php echo $comparetrackingPageKeyId;?>');updateAddCompareList('compare<?=$instituteId;?>-<?=$courseId;?>','',$j(this).prop('checked'));checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_RECOMMENDATIONS_LAYER_PAGE');" type="checkbox" name="compare" id="compare<?=$instituteId;?>-<?=$courseId;?>" class="compare<?=$instituteId;?>-<?=$courseId;?>" value="<?php echo $instituteId.'::'.' '.'::'.$instituteThumbURL.'::'.htmlspecialchars(html_escape($instituteName)).', '.$mainCityName.'::'.$courseId.'::'.$courseURL;?>"/>
<a href="javascript:void(0);" onclick="checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_RECOMMENDATIONS_LAYER_PAGE');toggleCompareCheckbox('compare<?php echo $instituteId;?>-<?=$courseId;?>');
setCompareCookie('<?php echo $comparetrackingPageKeyId;?>');updateAddCompareList('compare<?php echo $instituteId;?>-<?=$courseId;?>');
return false;" id="compare<?php echo $instituteId;?>-<?=$courseId;?>lable" class="compare<?php echo $instituteId;?>-<?=$courseId;?>lable">Add to Compare</a>
	   </p>
	   <div style="display:none">
	   <input type="hidden" name="compare<?php echo $instituteId;?>-<?=$courseId;?>list[]"  value= "<?=$courseId;?>" /></div>
	   
	   <!--------------------compare tool--end--------------------------->
	
	
	<?php
		}
	?>
	</li>
	<?php
	}
	// to right align the "other online applications" 
	if($numberOfRecommendations%3 == 1){
	    echo "<li></li>";    
	}
	if($numberOfRecommendations%3 != 0)
	{
?>
	<li>
	<div class="explore-app-box">
	    <i class="sprite-bg flLt web-icon"></i>
	    <a href="<?php echo $onlineFormHomePageNewUrl;?>" style="display: block; margin-left:10px; width: 122px; float: left;">Explore Other Online Applications</a>
	    <i class="sprite-bg explore-next-arrw"></i>
	    </div>
	</li>

<?php
	}
	?>
</ul>
</div>
<?php if($widget == 'CP_Reco_divLayer') { ?>
	<div class="layer-bot clear-width">
        <div class="slide-bullets">
            <ul>
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
        <div class="next-rev">
            <a id="prev<?php echo $uniqId; ?>" class="prev-icon" href="javascript:void(0)" onclick="slideLeft('<?php echo $uniqId; ?>', true);"></a>
            <a id="next<?php echo $uniqId; ?>" uniqueattr="Recommendations/NextPage" class="next-icon-active" href="javascript:void(0)" onclick="slideRight('<?php echo $uniqId; ?>', true);"></a>  
        </div>
        <div class="clearFix"></div>
    </div>
	</div>
<?php
    }
}
else if(!$recommendationsExist && $widget == 'CP_Reco_popupLayer') {
?>
<div style="text-align:center;">
    <input type="button" value="Ok" title="Ok" class="orange-button" onclick="window.location.reload();">
</div>
<?php
}
if($widget == 'CP_Reco_popupLayer') {
?>
	</div>
    </div>
</div>
<?php } ?>

<script>
<?php if($widget == 'CP_Reco_popupLayer') { ?>	
$j(document).keyup(function(e) {
    if(e.keyCode == 27) {
	window.location.reload();
    }
});
<?php
}

if($recommendationsExist) {?>

//Commented out GA Tracking

<?php
    if($widget == 'CP_Reco_popupLayer') {
?>
    //pageTracker._setCustomVar(1, "GATrackingVariable", 'CP_Reco_Load_popupLayer', 1);
    //pageTracker._trackPageview();
    pushCustomVariable('CP_Reco_Load_popupLayer');
<?php
    }
    else if($widget == 'CP_Reco_divLayer') {
?>
    //pageTracker._setCustomVar(1, "GATrackingVariable", 'CP_Reco_Load_divLayer', 1);
    //pageTracker._trackPageview();
    pushCustomVariable('CP_Reco_Load_divLayer');
<?php
    }
?>

var slideWidth = <?php echo $widget == 'CP_Reco_divLayer' ? 620 : 670; ?>;
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

<?php
}
?>
</script>
