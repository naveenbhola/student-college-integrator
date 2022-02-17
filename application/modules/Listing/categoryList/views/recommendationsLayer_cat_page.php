
<?php

if(count($recommendations) == 0) {
	return false;
}

if($recommendationsExist) {
    $numOfSlides = ceil($numberOfRecommendations / 3);
}    
?>

<div class="similat-institutes clear-width">
<i class="cate-new-sprite pointer"></i>
<div class="similat-inst-head">
	<a id="close_reco_div_<?php echo $uniqId;?>" href="javascript:void(0);" class="common-sprite close" onclick="$j(this).parent().parent().hide('slow');"></a>
	<div class="cate-ins-title flLt">Students who showed interest in this institute also looked at:</div>
	<div class="clearFix"></div>
</div>
<div class="cate-ins-detail">
	<div class="category-head">
		<div class="category-list-table">
			<div class="category-list-row">
				<div class="category-list-col" style="width:330px;">
					Institutes & Courses
				</div>
				<div class="category-list-col" style="width:130px;">
					<p class="flLt">Fees (INR)</p>
				</div>
				<div class="category-list-col" style="width:130px;">
					<p class="flLt">Eligibility</p>
				</div>
				<div class="category-list-col" style="width:120px;">
					Photos & Videos
				</div>
				
				
			</div>
		 </div>	
	</div>
	<div style="width:722px;overflow:hidden;">
	<ul style="position:relative;<?php  echo 'width:'.($numOfSlides*722).'px';?>" id="slideContainer<?php echo $uniqId; ?>">
<?php

$chunked_recommendation = array_chunk($recommendations,$numOfSlides);
foreach($chunked_recommendation as $recommendations) {	
?>
<li style="width:722px">	
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
//_P($exams);
$cutoff = '';
$exams_list = array();
foreach($exams as $exam) {
$examName = $exam->getAcronym();
$marks = $exam->getMarks();
$marks_type = $exam->getMarksType();
if($marks > 0) {
	$exams_list[] = "<li>".$examName.": ".$marks." <span>".str_replace(array("percen","total_"),array("%",""),$marks_type)."</span></li>";	
} else {
	$exams_list[] = "<li>".$examName."</li>";
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
$onClickAction .= ", '".$courseURL."', event";

$target = '';
if($widget == 'CP_Reco_popupLayer' || $widget == 'CP_Reco_divLayer') {
	$target = 'target="_blank"';
}
		
?>	
		<div class="category-tupple clear-width <?=($course->isPaid() ? 'paid-tuple-bg' : '')?>">
		<div class="category-list-table">
			<div class="category-list-row">
				<div class="category-list-col" style="width:330px;">
					<p class="institute-title"><a href="<?php echo $courseURL; ?>" style="color:#4B4B4B !important;" title="<?php echo $instituteFullName; ?>" <?php if($recoAlgo) { echo "onclick=\"trackEventByCategory('Reco-".$courseSubcat."','Click','".$recoAlgo."');\""; } ?>><?php echo $instituteName ?></a><span>, <?php echo $mainCityName; ?></span></p>
					<p class="font-12"><a id="courseLink_<?php echo $instituteId; ?>" href="<?php echo $courseURL; ?>" title="<?php echo $courseFullName; ?>" <?php echo $target; ?> onmouseover="underlineCourseLink(<?php echo $instituteId; ?>);" onmouseout="removeCourseLinkUnderline(<?php echo $instituteId; ?>);" onclick="<?php if($recoAlgo) { echo "trackEventByCategory('Reco-".$courseSubcat."','Click','".$recoAlgo."');"; } ?> processActivityTrack(<?php echo $onClickAction; ?>);"><?php echo $courseName ?></a></p>
				</div>			    
				<div class="category-list-col" style="width:130px;">
					<?php echo $fees?$fees:"Not available";?>
				</div>
				<div class="category-list-col" style="width:130px;">
					<ul class="eligible-list">
						<?php 
							echo count($exams_list)>0?implode("",$exams_list):"Not available";
						?>
					</ul>
				</div>
				<div class="category-list-col" style="width:120px;text-align:center;">
					<?php
						    $photosCount = $institute->getPhotoCount() + $institute->getVideoCount();
                                                    if($photosCount > 0) { 
						    if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL() && $course->isPaid()){
							echo '<a href="'.$course->getURL().'#photoVideoForNationalPage"><img style="height:63px;width:76px;" src="'.$institute->getMainHeaderImage()->getThumbURL().'" alt="'.html_escape($institute->getName()).'" title="'.html_escape($institute->getName()).'"/></a>';
								}else if($course->isPaid()){
									echo '<a href="'.$course->getURL().'#photoVideoForNationalPage"><img style="height:63px;width:76px;" src="/public/images/avatar.gif" alt="'.html_escape($institute->getName()).'" title="'.html_escape($institute->getName()).'"/></a>';
						    }
						    if(!$course->isPaid())
						    {
						    ?>
                                                    <i class="cate-new-sprite photo-icon"></i>
						    <?php
						    }
						    ?>
                                                    <a class="photo-link" style="<?=$course->isPaid() ? 'margin:5px 0px 0px 0px;':'text-align:left;'?>" href="<?php echo $course->getURL().'#photoVideoForNationalPage';?>">
                                                        <?php echo $photosCount;?> Photo<?=($photosCount > 1)? 's' : ''?> available
                                                    </a>
                                        <?php } else{
                                                echo 'N/A';
			                } ?>
				</div>
			</div>
			
		</div>
		<div class="action-row">
			<div class="download-btn-col flRt">
				<div style="height: 23px">
				   <div id="apply_confirmation<?php echo $instituteId; ?>" class="recom-aply-row" <?php if(in_array($courseId,$recommendationsApplied))  {echo "style='display:block;'";} else {echo "style='display:none;'";} ?>>
					 <p class="success-msg">
						<i class="cate-new-sprite dwnld-mark-icon"></i>E-brochure successfully mailed.
					 </p>
				  </div>
			    </div>
				
				<a id="apply_button<?php echo $instituteId; ?>" style="font-weight: normal !important; background:#fbfbfb; padding:5px 6px; color:#818181 !important; -moz-border-radius:none !important; -webkit-border-radius:none !important; border-radius:none !important; border:1px solid #d1d1d1 !important;" href="javascript:void(0)" class="<?php
				if(in_array($courseId,$recommendationsApplied)) {
					echo 'download-btn disabled';
				}
				else {
					echo 'download-btn';
				} ?>" onClick = "window.L_tracking_keyid=<?php echo isset($tracking_keyid)? $tracking_keyid : 0; ?>; <?php if($recoAlgo) { echo "trackEventByCategory('Reco-".$courseSubcat."','RequestEBrochure','".$recoAlgo."');"; } ?>  <?php
				if(in_array($courseId,$recommendationsApplied)) {
					echo 'return false;';
				}
				else if($userInfo == 'false') {
					if($widget == 'LP_Reco_AlsoviewLayer') {
						echo 'makeResponse('.$instituteId.', \''.base64_encode($instituteName).'\', '.$courseId.', \''.base64_encode($courseName).'\', undefined, \'LP_Reco_AlsoviewLayer\', \'also_viewed\');';
					}
					else if($widget == 'LP_Reco_SimilarInstiLayer') {
						echo 'makeResponse('.$instituteId.', \''.base64_encode($instituteName).'\', '.$courseId.', \''.base64_encode($courseName).'\', undefined, \'LP_Reco_SimilarInstiLayer\', \'similar_institutes\');';
					}
				}
				else if($widget == 'LP_Reco_AlsoviewLayer' || $widget == 'LP_Reco_SimilarInstiLayer') {
					echo 'doAjaxApplyListings('.$instituteId.', '.$courseId.', '.$mainCityId.', '.$mainLocalityId.', '.$mainCityId.', \''.$widget.'\', '.$appliedCourse->getId().', '.$appliedCourse->getInstId().');';
				}
				else {
					echo 'doAjaxApply('.$instituteId.', '.$courseId.', '.$mainCityId.', '.$mainLocalityId.', '.$mainCityId.', \''.$widget.'\', '.$appliedCourse->getId().', '.$appliedCourse->getInstId().');';
				} ?>">
				<i class="common-sprite bro-sml-icon"></i>
				Download E-Brochure
				</a>
				<?php
				if($categoryPage_SubCat == 23){
					if(in_array($courseId, $shortlistedCoursesOfUser)) {
						$courseShortlistedStatus = 1;
					}
				?>
				<!--a href="#" class="download-btn">Download E-Brochure</a-->
				<a style="padding:4px 12px 6px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;" isLastViewed = "<?=$lastViewedCourse == $courseId ? 'true' : 'false' ?>" onmouseover="showShortlistInfoBox(this);" onmouseout="hideShortlistInfoBox();"  href="javascript:void(0);" onclick="<?=($courseShortlistedStatus == 1 ? 'return false;' : '')?>  if(isShortListingInProgress) { return false;}  globalShortlistParams = {courseId: <?=$courseId?>, pageType: 'ND_CategoryReco', buttonId: '', shortlistCallback: 'shortlistCallbackForCtpgTuple',tracking_keyid:'<?=DESKTOP_NL_CTPG_TUPLE_SHORTLIST_RECO?>'}; shortListAnimation(this, function() { if(isShortListingInProgress) { return false;} shikshaUserRegistration.showShortlistRegistrationLayer({courseId: <?=$courseId?>, source: 'ND_CategoryReco', layerHeading: '', layerTitle: 'Please register to shortlist more colleges'})}); gaTrackEventCustom('CATEGORY_PAGE_RNR', 'ShortList', '<?=$course->getId()?>', this, '');" class="shrtlist-btn <?="shrt".$courseId?> <?=$courseShortlistedStatus == 1 ? 'shortlist-disable' :'' ?> "><i class="common-sprite shrtlist-star-icon"></i><span class="btn-label"><?=$courseShortlistedStatus == 1 ? 'Shortlisted' :'Shortlist' ?></span></a>    
				<br/> 
				<a target="_blank" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>" class="<?="shrt-view-link".$courseId?>" style="font-size:12px; margin:8px 0 0 0; display:<?=$courseShortlistedStatus == 1 ? 'block' : 'none' ?>; float:right">View my shortlist</a>
			<?php
			$courseShortlistedStatus = 0;
				}
			?>
			</div>
		</div>
	<input type="hidden" id="apply_status<?php echo $instituteId; ?>" value="<?php if(in_array($courseId,$recommendationsApplied)) echo '1'; else echo '0'; ?>"/>
	<input type="hidden" id="params<?php echo $instituteId; ?>" value="<?php echo html_escape(getParametersForApply($userInfo,$course,$mainCityId,$mainLocalityId)); ?>"/>
	
	<input type="hidden" id="reco_params_city_<?php echo $instituteId; ?>" value="<?php echo $mainCityId; ?>" />
	<input type="hidden" id="reco_params_locality_<?php echo $instituteId; ?>" value="<?php echo $mainLocalityId; ?>" />
	</div>
<?php 
} ?>
</li>
<?php 
}
?>	 
   </ul>
   </div>

    <?php if($numOfSlides>1):?>
	<div class="cate-ins-slider">
		<ul class="ins-slider">
			<?php for($i=0;$i<$numOfSlides;$i++):?> 
			<li><a id="<?php echo 'reco_button_'.$uniqId.'_'.$i;?>" href="javascript:void(0);" onclick="animateRecoRight(this,'button','<?php echo ($i);?>','#prev<?php echo $uniqId;?>','#next<?php echo $uniqId;?>','<?php echo $numOfSlides;?>','<?php echo ($numOfSlides*722); ?>','#slideContainer<?php echo $uniqId; ?>','<?php echo $uniqId;?>')" <?php if($i == 0) {echo 'class="active"';} ?>><?php echo $i;?></a></li>
			<?php endfor;?>
		</ul>
		<div class="next-prev-slider">
			<a  id="prev<?php echo $uniqId;?>" href="javascript:void(0);"  class="prev-box" onclick="animateRecoLeft(this,'#prev<?php echo $uniqId;?>','#next<?php echo $uniqId;?>','<?php echo $numOfSlides;?>','<?php echo ($numOfSlides*722); ?>','#slideContainer<?php echo $uniqId; ?>','<?php echo $uniqId;?>');">
				<i class="cate-new-sprite prev-icon"></i>
			</a>
			<a id="next<?php echo $uniqId; ?>" href="javascript:void(0);"  class="nxt-box active" onclick="animateRecoRight(this,'normal',0,'#prev<?php echo $uniqId;?>','#next<?php echo $uniqId;?>','<?php echo $numOfSlides;?>','<?php echo ($numOfSlides*722); ?>','#slideContainer<?php echo $uniqId; ?>','<?php echo $uniqId;?>');">
				<i  class="cate-new-sprite nxt-icon-active"></i>
			</a>
		</div>
	</div>
   <?php endif;?>
</div>
</div>

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
    pushCustomVariable('CP_Reco_Load_popupLayer');
<?php
    }
    else if($widget == 'CP_Reco_divLayer') {
?>
    pushCustomVariable('CP_Reco_Load_divLayer');
<?php
    }
?>
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
var current_reco_div_id = 'close_reco_div_<?php echo $uniqId;?>';
$j('a[id^="close_reco_div_"]').each(function() {
      //_gaq.push(['_trackPageview', '/external/pagename']);
      var id = $j(this).attr('id');  
      if(id == current_reco_div_id) {			
			//$j('body').scrollTo('#'+current_reco_div_id);
			$j('html, body').animate({	
				scrollTop: $j('#'+current_reco_div_id).offset().top
			}, 500);
	  } else {
			$j(this).click();
	  }
});

<?php
if($recoAlgo) {
	echo "trackEventByCategory('Reco-".$courseSubcat."','RecommendationShown','".$recoAlgo."');";
}
?>
</script>
