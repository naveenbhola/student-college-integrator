<?php if($widget == 'CP_Reco_popupLayer') { ?>
<div class="layer-outer">
    <div class="layer-title">
	<div style="float:left; width:685px;">
	    <h4><?php echo ($isRankingPage==1?"Request":"Download");?> E-Brochure for <?php echo $appliedCourse->getName(); ?> - <?php echo $appliedCourse->getInstituteName(); ?></h4>

        </div>
	    <a title="Close" class="close" href="javascript:void(0)" onClick="window.location.reload();"></a>
    </div>
<div class="clearFix"></div>
    <div class="layer-contents">
	<div class="recommended-section">
	    <div class="recom-tnx-row">
		<?php if($brochureAvailable == "YES"){ ?>
		    <i class="thnx-icon"></i>Thank you for your request. Your E-brochure has been successfully mailed to you.
		<?php } else {?>
		    Sorry, brochure is currently not available.
		<?php } ?>
		
		<?php if($brochureUrl!="") { ?>
		</br><b><a href="<?=$brochureUrl?>" target ="_blank">View brochure now &gt;</a></b>
		<?php } ?>
		
	    </div>
<?php } ?>
		
<?php

if($recommendationsExist) {
    $numOfSlides = ceil($numberOfRecommendations / 3);
?>

<?php if($widget == 'CP_Reco_divLayer') { ?>

    <div class="recommended-section req-bro-box"> 
    <i class="pointer"></i>
	<div class="recommend-head lyaer-head" style="background:none; padding:0"> 
	    <h2 class="flLt">Students who showed interest in this <?php echo $collegeOrInstituteRNR;?> also looked at:</h2>
	    
	    <div class="next-rev">
		<a href="javascript:void(0)" class="cl-icon" onclick="closeInlineRecommendation(<?php echo $appliedCourse->getInstId(); ?>);"></a>
	    </div>
	    <div class="clearFix"></div>
	    <?php if($brochureUrl!="") { ?>
		</br><b><a href="<?=$brochureUrl?>" target ="_blank">View brochure now &gt;</a></b>
	    <?php } ?>
	</div>

<?php } else { ?>

<?php if($widget == 'LP_Reco_AlsoviewLayer') { ?>

<div class="recommend-head"> 
	<h2 class="flLt">Students who viewed this <?php echo $collegeOrInstituteRNR;?> also viewed</h2>	
<?php } else if($widget == 'LP_Reco_SimilarInstiLayer') { ?>

<div class="recommend-head"> 
	<h2 class="flLt">Other <?php echo $collegeOrInstituteRNR;?> in <?php echo $seedCourseCity; ?> offering <?php echo $seedCourseLDBCourse; ?></h2>
<?php } else { ?>

<div class="recommend-head lyaer-head"> 
	<h2 class="flLt">Students who showed interest in this <?php echo $collegeOrInstituteRNR;?> also looked at</h2>
<?php
    }
?>
	<div class="next-rev">
	    <a id="prev<?php echo $uniqId; ?>" href="javascript:void(0)" class="prev-icon" onclick="slideLeft('<?php echo $uniqId; ?>', false);"></a>
	    <a id="next<?php echo $uniqId; ?>" uniqueattr="Recommendations/NextPage" href="javascript:void(0)" class="next-icon-active" onclick="slideRight('<?php echo $uniqId; ?>', false);"></a> 
	</div>
	<div class="clearFix"></div>
    </div>
<?php
}
?>

<div class="recommend-items newRecommendToCrwl" style="<?php if($widget == 'CP_Reco_divLayer') { echo "width:620px;"; } else { echo "width:670px;"; } ?>">
<ul id="slideContainer<?php echo $uniqId; ?>" style="<?php if($widget == 'CP_Reco_divLayer') { echo "width:1860px;"; } else { echo "width:2010px;"; } ?> position: relative; left:0px;">
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
		$fees = $course->getFees($mainLocationId)->__toString();
		$ranking = $course->getRanking()->__toString();
		$duration = $course->getDuration()->__toString();
		$mode = $course->getCourseType();
		$onClickAction = $appliedCourse->getId().", ".$course->getId().", ".$appliedCourse->getInstId();
		if($widget == 'CP_Reco_popupLayer' || $widget == 'CP_Reco_divLayer') {
		    $onClickAction .= ", 'CP_Reco_Viewed', '".$widget."', 'also_viewed'";
		} else if($widget == 'LP_Reco_AlsoviewLayer') {
		    $onClickAction .= ", 'LP_Reco_Viewed', '".$widget."', 'also_viewed'";
		} else if($widget == 'LP_Reco_SimilarInstiLayer') {
		    $onClickAction .= ", 'LP_Reco_Viewed', '".$widget."', 'similar_institutes'";
		}
		
		$target = '';
		if($widget == 'CP_Reco_popupLayer' || $widget == 'CP_Reco_divLayer') {
		    $target = 'target="_blank"';
		}
	?>
	<li  style="<?php if($widget != 'CP_Reco_divLayer') { echo "width:207px;"; } ?> float: left;" onmouseover="highlightRequestButton(<?php echo $instituteId; ?>);" onmouseout="removeRequestButtonHighlight(<?php echo $instituteId; ?>);">
	<div class="inst-height">
		<div class="inst-pic">
		<?php
			$onClickAction .= ", '".$instituteURL."', event";
			if($instituteHeaderImage && $instituteThumbURL) {
			echo '<a href="'.$instituteURL.'" rel="nofollow" '.$target.'><img src="'.$instituteThumbURL.'" width="118" alt="'.html_escape($instituteFullName).'" title="'.html_escape($instituteFullName).'" onmouseover="underlineCourseLink('.$instituteId.');" onmouseout="removeCourseLinkUnderline('.$instituteId.');" onclick="'.($recoAlgo ? "trackEventByCategory('Reco-".$courseSubcat."','Click','".$recoAlgo."');" : "").' processActivityTrack('.$onClickAction.');"/></a>';
			}
			else {
			echo '<a href="'.$instituteURL.'" rel="nofollow" '.$target.'><img src="/public/images/avatar.gif" alt="'.html_escape($instituteFullName).'" title="'.html_escape($instituteFullName).'" onmouseover="underlineCourseLink('.$instituteId.');" onmouseout="removeCourseLinkUnderline('.$instituteId.');" onclick="'.($recoAlgo ? "trackEventByCategory('Reco-".$courseSubcat."','Click','".$recoAlgo."');" : "").' processActivityTrack('.$onClickAction.');"/></a>';
			}
		?>
		</div>
		<p class="inst-name" title="<?php echo $instituteFullName; ?>"><a href="<?php echo $instituteURL; ?>" style="color: inherit;"><?php echo $instituteName ?></a></p>
		<p><strong><?php echo $mainCityName; ?></strong></p>
		<?php $onClickAction .= ", '".$courseURL."', event"; ?>
		<p><a id="courseLink_<?php echo $instituteId; ?>" href="<?php echo $courseURL; ?>" title="<?php echo $courseFullName; ?>" <?php echo $target; ?> onmouseover="underlineCourseLink(<?php echo $instituteId; ?>);" onmouseout="removeCourseLinkUnderline(<?php echo $instituteId; ?>);" onclick="<?php if($recoAlgo) { echo "trackEventByCategory('Reco-".$courseSubcat."','Click','".$recoAlgo."');"; } ?>  processActivityTrack(<?php echo $onClickAction; ?>);"><?php echo $courseName ?></a></p>
		<?php
			if(!empty($cutoff)) {
			echo '<p><strong>Eligibility</strong>: '.$cutoff.'</p>';
			}
			else if(!empty($fees)) {
			echo '<p><strong>Fees</strong>: '.$fees.'</p>';
			}
			else if(!empty($ranking)) {
			echo '<p><strong>Ranking</strong>: '.$ranking.'</p>';
			}
			else if(!empty($duration)) {
			echo '<p><strong>Duration</strong>: '.$duration.'</p>';
			}
			else if(!empty($mode)) {
			echo '<p><strong>Mode</strong>: '.$mode.'</p>';
			}
		?>
	</div>
	<?php
		if($course->isPaid() || $brochureURL[$courseId] != '') {
	?>
	<a id="apply_button<?php echo $instituteId; ?>" href="javascript:void(0)" class="<?php
		if(in_array($courseId,$recommendationsApplied)) {
			echo 'white-disabled white-button';
		}
		else {
			echo 'white-button';
		} ?>" onClick = "<?php if($recoAlgo) { echo "trackEventByCategory('Reco-".$courseSubcat."','RequestEBrochure','".$recoAlgo."');"; } ?> <?php
		if(in_array($courseId,$recommendationsApplied)) {
			echo 'return false;';
		}
		else if($userInfo == 'false' || ($widget == 'LP_Reco_AlsoviewLayer' || $widget == 'LP_Reco_SimilarInstiLayer')) {    //true for all cases
			if($userInfo != 'false'){
				$customCallback = 'recoLayerCallback';
				$customParams = base64_encode(json_encode(array('institute_id' => $instituteId, 'course_id'=>$courseId, 'sourcePage'=>$widget,'clickedCourseId'=>$appliedCourse->getId(),'clickedInstituteId'=>$appliedCourse->getInstId())));
				if($widget == 'LP_Reco_AlsoviewLayer') {
			    	echo 'makeResponse('.$instituteId.', \''.base64_encode($instituteName).'\', '.$courseId.', \''.base64_encode($courseName).'\', \''.$customCallback.'\', \'LP_Reco_AlsoviewLayer\', \'also_viewed\',\''.$customParams.'\', '. $tracking_keyid . ');';
				}
				else if($widget == 'LP_Reco_SimilarInstiLayer') {
			    	echo 'makeResponse('.$instituteId.', \''.base64_encode($instituteName).'\', '.$courseId.', \''.base64_encode($courseName).'\', \''.$customCallback.'\', \'LP_Reco_SimilarInstiLayer\', \'similar_institutes\',\''.$customParams.'\', '. $tracking_keyid . ');';
				}
			}else{
				$customParams = '';
				if($widget == 'LP_Reco_AlsoviewLayer') {
			    	echo 'makeResponse('.$instituteId.', \''.base64_encode($instituteName).'\', '.$courseId.', \''.base64_encode($courseName).'\', undefined, \'LP_Reco_AlsoviewLayer\', \'also_viewed\',\''.$customParams.'\', '. $tracking_keyid . ');';
				}
				else if($widget == 'LP_Reco_SimilarInstiLayer') {
			    	echo 'makeResponse('.$instituteId.', \''.base64_encode($instituteName).'\', '.$courseId.', \''.base64_encode($courseName).'\', undefined, \'LP_Reco_SimilarInstiLayer\', \'similar_institutes\',\''.$customParams.'\', '. $tracking_keyid . ');';
				}
			}

//                        if($userInfo != 'false'){
//                            echo 'recoLayerCallback('.$instituteId.', '.$courseId.', '.$mainCityId.', '.$mainLocalityId.', '.$mainCityId.', \''.$widget.'\', '.$appliedCourse->getId().', '.$appliedCourse->getInstId().');';
//                        }
		}
//		else if($widget == 'LP_Reco_AlsoviewLayer' || $widget == 'LP_Reco_SimilarInstiLayer') {
//			echo 'doAjaxApplyListings('.$instituteId.', '.$courseId.', '.$mainCityId.', '.$mainLocalityId.', '.$mainCityId.', \''.$widget.'\', '.$appliedCourse->getId().', '.$appliedCourse->getInstId().');';
//		}
		else {
			if($actionType == '') {
				echo 'window.L_tracking_keyid='.$tracking_keyid.'; doAjaxApply('.$instituteId.', '.$courseId.', '.$mainCityId.', '.$mainLocalityId.', '.$mainCityId.', \''.$widget.'\', '.$appliedCourse->getId().', '.$appliedCourse->getInstId().');';
			}
			else {
				echo 'window.L_tracking_keyid='.$tracking_keyid.'; doAjaxApply('.$instituteId.', '.$courseId.', '.$mainCityId.', '.$mainLocalityId.', '.$mainCityId.', \''.$actionType.'\', '.$appliedCourse->getId().', '.$appliedCourse->getInstId().');';
			}
		} ?>">Download E-Brochure</a>
	
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

//<?php
//    if($widget == 'CP_Reco_popupLayer') {
//?>
//    pageTracker._setCustomVar(1, "GATrackingVariable", 'CP_Reco_Load_popupLayer', 1);
//    pageTracker._trackPageview();
//<?php
//    }
//    else if($widget == 'CP_Reco_divLayer') {
//?>
//    pageTracker._setCustomVar(1, "GATrackingVariable", 'CP_Reco_Load_divLayer', 1);
//    pageTracker._trackPageview();
//<?php
//    }
//?>

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


<?php
if($widget == 'CP_Reco_popupLayer' || $widget == 'CP_Reco_divLayer') {
?>
<!--
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
var google_conversion_id = 1053765138;
var google_conversion_label = "CZKhCNqKzwgQkty89gM";
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
</script>

<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1053765138/?value=1.000000&amp;label=CZKhCNqKzwgQkty89gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
<?php
}
?>
<script> // for trackEventByGA
var currentPageName= 'Recommendations Layer Page';
<?php
if($recoAlgo) {
	echo "trackEventByCategory('Reco-".$courseSubcat."','RecommendationShown','".$recoAlgo."');";
}
?>
</script>
