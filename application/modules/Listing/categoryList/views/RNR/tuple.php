<?php
    $tracking_keyid = DESKTOP_NL_CTPG_TUPLE_DEB;
    $course                = $institute->getFlagshipCourse();
    $courses               = $institute->getCourses();
    $courseListForBrochure = array();

    $institute_id = $institute->getId();
    foreach($coursesBasicDetails[$institute_id] as $coursesArray){
        $courseListForBrochure[$coursesArray['course_id']] = $coursesArray['course_name'];
    }

    if($request){
        $appliedFilters = $request->getAppliedFilters();
        $course->setCurrentLocations($request);
    }
    
    $courseLocations = $course->getLocations();
    $displayLocation = ($course->getCurrentMainLocation() != null) ? $course->getCurrentMainLocation() : $course->getMainLocation();
    // if(!$courseLocations || count($courseLocations) == 0) {
    //     $courseLocations = $course->getLocations();
    // }
    
    if($appliedFilters){
        foreach($courseLocations as $location){
            $localityId = $location->getLocality()?$location->getLocality()->getId():0;
            if(in_array($localityId,$appliedFilters['locality'])) {
                $displayLocation = $location;
                break;
            }
            if(in_array($location->getCity()->getId(),$appliedFilters['city'])) {
                $displayLocation = $location;
                break;
            }
            if(in_array($location->getState()->getId(),$appliedFilters['state'])) {
                $displayLocation = $location;
                break;
            }
        }
    }

    //processing exam name and its score
    $exams = $course->getEligibilityExams();
    $marksFormat = array(
                  'percentile' => "%tile",
                  'percentage' => "%tage",
                  'total_marks' => "marks",
                  'rank'        =>"rank"
              );
    $examAcronyms = array();
    if(!empty($exams)){
        foreach($exams as $exam) {
            if ($exam->getMarks() > 0) {
                $examAcronyms[$exam->getAcronym()] =   ':'.$exam->getMarks().'<span> '.$marksFormat[$exam->getMarksType()].'</span>';
            } else {
                $examAcronyms[$exam->getAcronym()] = '';
            }
        }
    } else {
        $examAcronyms = array('N/A' => '');
    }
    //processing exam ends here
    
    $instituteId = $institute->getId();
    if($course->getFees($displayLocation->getLocationId()) != '') {        
        $fees = $course->getFees($displayLocation->getLocationId());
        $feesAmount = $fees->getValue();
        $feesCurrency = $fees->getCurrency();
        if($feesCurrency == 'USD') {
            $courseFees = $AbroadListingCommonLib->formatMoneyAmount($feesAmount,2,1);
        }
        else {
            $courseFees = $AbroadListingCommonLib->formatMoneyAmount($feesAmount,1,1);
        }
    }
    else {
        $courseFees = 'N/A';
    }
    
    
    $similarCourses = $courses;
    unset($similarCourses[0]);//disable flagship course from repeating
    

	$RNRVisitedListingIds = $_COOKIE['RNR_VIL'];
	$RNRVisitedListingIds = explode(",", $RNRVisitedListingIds);
	$categoryPageTupleStyle = "margin-bottom:10px;";
	if(in_array($instituteId, $RNRVisitedListingIds)) {
		$categoryPageTupleStyle = "margin-bottom:3px;";
	}
	$data['instituteName'] = $institute->getName();	  
	$style = "";
	$id = "";
	global $isShortlistWidgetVisible;
	if($isShortlistWidgetVisible) {
   	$style = "style='margin-top:257px'";
   	 $id = "id='shortListNextTuple' isAnimated='false'";
   	$isShortlistWidgetVisible = false;
   }
	?>




<div <?=$style?> <?=$id?> class="category-tupple clear-width <?=$course->isPaid() ? 'paid-tuple-bg' : ''?>"  onmouseover="$j(this).addClass('tupple-hover');$j('span.compare<?php echo "$instituteId-{$course->getId()}";?>').show();" onmouseout="$j(this).removeClass('tupple-hover');$j('span.compare<?php echo "$instituteId-{$course->getId()}";?>').hide();">
	<div class="category-list-table">
		<div class="category-list-row">
			<div class="category-list-col" style="width:317px; word-wrap:break-word; float: left;">
                <p class="institute-title">
                    <a class="institute-title-clr" href="<?php echo $institute->getURL();?>" title="<?=html_escape($institute->getName());?>"><?=html_escape($institute->getName());?></a><span>, <?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span>
                </p>
                <p class="font-16">
                    <a href="<?=$course->getURL();?>">
                        <?php echo html_escape($course->getName()); ?>
                    </a>
                </p>
                <?php
                 if(is_array($reviewsData[$course->getId()]) && isset($reviewsData[$course->getId()]['overallAverageRating'])) { ?>
                <div style="position:relative;margin-top:5px;">
                    <div class="flLt avg-rating-title">Alumni Rating: </div>
                    <div class="ranking-bg" onmouseover="showAvgPointerTip(this);" onmouseout="hideAvgPointerTip()"><?php echo $reviewsData[$course->getId()]['overallAverageRating']; ?><sub>/5</sub></div>
                </div>
                <?php } ?>
			</div>
                                    <div class="category-list-col" style="width:153px;">
					<?=$feesCurrency.' '.$courseFees;?>
				</div>
					<div class="category-list-col" style="width:140px;">
							<ul class="eligible-list">
                                                            <?php foreach($examAcronyms as $examName => $score) {
                                                            echo "<li>$examName $score</li>";
                                                             } ?>
							</ul>
				</div>
                                    
							
                                        <div class="category-list-col" style="width:120px;text-align:center;">
                                            <?php if(CP_HEADER_NAME == 'photos') { ?>
                                                    <?php $photosCount = getPhotosAndVideos($institute);
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
                                            <?php } else { ?>
                                                <span class="font-13">
                                                    <?=getFormSubmissionDate($course,$displayLocation);?>
                                                </span>
                                            <?php } ?>
					</div>
				</div>
                    <?php
                    if($request->getSubCategoryId() == 56) {
                    $similarCourseCount = count($similarCourses); 
                    if($similarCourseCount) { 
                        $moreBranchesText = $similarCourseCount == 1 ? "1 more branch" : $similarCourseCount." more branches"; ?>
                    <div class="mb10" style="margin-top:10px;"><a  href = 'javascript:void(0);' id ='moreBranches_<?=$institute->getId()?>' class='branch-btn' style='margin-top:0' onclick = "toggleSimilarCoursesTuples(<?=$institute->getId()?>);"><i class="cate-new-sprite branch-plus-icon"></i><?=$moreBranchesText?></a></div>
                    <?php }
                    }
                    ?>
		</div>
                <?php 
                if($request->getSubCategoryId() == 56) {
                    if($similarCourseCount > 0) {
                    echo "<div id = 'similarCourses_".$institute->getId()."' style = 'display :none'>";
                        foreach($similarCourses as $similarCourse) {
                    ?>
                    <div class="category-list-table cate-tuple-seperatr">
                                    <div class="category-list-row">
                                                    <div class="category-list-col" style="width:auto;">
                                                        <div style="width:317px">
                                                                    <p class="font-12"><a href="<?php echo $similarCourse->getURL(); ?>"><?php echo html_escape($similarCourse->getName()); ?></a></p>
                                                        </div>
                                                    </div>
                                                    <div class="category-list-col"  style="width:153px;">
                                                    <?php
                                                    if($similarCourse->getFees($displayLocation->getLocationId()) != '') {        
                                                        $similarFees = $similarCourse->getFees($displayLocation->getLocationId());
                                                        $similarFeesAmount = $similarFees->getValue();
                                                        $similarFeesCurrency = $similarFees->getCurrency();
                                                        if($similarFeesCurrency == 'USD') {
                                                            $similarCourseFees = $AbroadListingCommonLib->formatMoneyAmount($similarFeesAmount,2,1);
                                                        }
                                                        else {
                                                            $similarCourseFees = $AbroadListingCommonLib->formatMoneyAmount($similarFeesAmount,1,1);
                                                        }
                                                    }
                                                    else {
                                                        $similarCourseFees = 'N/A';
                                                    }
                                                    echo $similarFeesCurrency.' '.$similarCourseFees;
                                                    ?>
                                                    </div>
                                                    <div class="category-list-col">
                                                                    <ul class="eligible-list">
                                                                        <?php $similarExams = $similarCourse->getEligibilityExams();
                                                                                $similarExamAcronyms = array();
                                                                                if(!empty($similarExams)) {
                                                                                    foreach($similarExams as $similarExam) {
                                                                                        if ($similarExam->getMarks() > 0) {
                                                                                            $similarExamAcronyms[$similarExam->getAcronym()] =   ':'.$similarExam->getMarks().'<span> '.$marksFormat[$similarExam->getMarksType()].'</span>';
                                                                                        } else {
                                                                                            $similarExamAcronyms[$similarExam->getAcronym()] = '';
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    $similarExamAcronyms = array('N/A' => '');
                                                                                }
                                                                        ?>
                                                                           <?php foreach($similarExamAcronyms as $similarExamName => $similarExamScore) {
                                                                                    echo "<li>$similarExamName $similarExamScore</li>";
                                                                                  } 
                                                                            ?>
                                                                    </ul>
                                                    </div>
                                                    <div class="category-list-col" style="width:90px;">
                                                        <?php if(CP_HEADER_NAME == 'photos') { ?>
                                                            
                                                                <?php $photosCount = getPhotosAndVideos($institute);
                                                                if($photosCount > 0) { ?>
                                                                    <i class="cate-new-sprite photo-icon"></i>
                                                                    <a class="photo-link" href="<?php echo $similarCourse->getURL().'#photoVideoForNationalPage';?>">
                                                                        <?php echo $photosCount;?> available
                                                                    </a>
                                                                <?php } else{
                                                                    echo 'N/A';
                                                                } ?>
                                                        <?php } else { ?>
                                                            <span class="font-13">
                                                                <?=getFormSubmissionDate($course,$displayLocation);?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                    </div>
                    </div>
                    <?php 
                        }
                        echo '</div>';
                    }
                }
			
			$actionRowStyle = "";
			$showCoachMarks = false;
			if($_COOKIE['coach-marks'] != "1" && $request->getCityId() != 1 && $request->getCountryId() == 2) {
				if($total_tuples >= 2 && $row_number == 2){
					$showCoachMarks = true;
				} else if($total_tuples < 2 && $row_number == 1){
					$showCoachMarks = true;
				}
				$actionRowStyle = "position:relative";
			}	
            ?>
		<div class="action-row" style="<?php echo $actionRowStyle;?>" id="cp_ac_row_<?php echo $row_number;?>">
			<?php
			// To display coach-marks
			if($showCoachMarks && false){
				?>
				<div class="coachmark" id="coach-marks-cont">
					<div class="coach-img-3">
						<i class="coach-sprite left-bottom-arrow"></i>
						<p>Use this to Compare Colleges</p>
					</div>
					<div class="coach-img-4">
						<i class="coach-sprite right-bottom-arrow"></i>
						<p>Use this to download<br/>College brochure</p>
					</div>
					<div class="dismiss-text">
						<a href="javascript:void(0);" onclick="hideCoachMarks();">Click anywhere to start</a>
					</div>
				</div>
				<?php
			}
			?>
			<div class="action-links customInputs flLt"  onMouseOver="$j('span.compare<?php echo "$instituteId-{$course->getId()}";?>').show();" onMouseOut="$j('span.compare<?php echo "$instituteId-{$course->getId()}";?>').hide();">
                <?php if(!$recommendationPage && ($course->isPaid() || $brochureURL[$course->getId()] != '')) { ?>
					<input type="checkbox" id="compare<?php echo $instituteId.'-'.$course->getId();?>"  class="compare<?php echo $instituteId.'-'.$course->getId();?>" name="compare" value="<?php echo $institute->getId().'::'.' '.'::'.($institute->getMainHeaderImage()?$institute->getMainHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($institute->getName())).', '.$displayLocation->getCity()->getName().'::'.$course->getId().'::'.$course->getURL();?>" onclick="updateCompareText('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');setCompareCookie('<?php echo $comparetrackingPageKeyId;?>');updateAddCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');checkactiveStatusOnclick();"/>
					<label for="compare">
						<span class="common-sprite" style="position:relative; top:2px" onclick="checkactiveStatusOnclick();toggleCompareCheckbox('compare<?php echo $institute->getId();?>-<?=$course->getId()?>'); setCompareCookie('<?php echo $comparetrackingPageKeyId;?>');updateAddCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>'); return false;"></span>
						<a id="compare<?php echo "$instituteId-{$course->getId()}";?>lable" href="javascript:void(0);" onclick="checkactiveStatusOnclick();toggleCompareCheckbox('compare<?php echo $institute->getId();?>-<?=$course->getId()?>'); setCompareCookie('<?php echo $comparetrackingPageKeyId;?>');updateAddCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>'); return false;" class="compare<?php echo "$instituteId-{$course->getId()}";?>lable">Add to Compare</a>
					</label>
					<span>
						
					<?php
					/**
					  * Online Form Button
					  */
					?>
                    <?php if($coursesWithOnlineForm[$course->getId()] && $onlineApplicationCoursesUrl[$course->getId()]['seo_url'] != '') { ?>
                    &nbsp;|
					<?php if($onlineApplicationCoursesUrl[$course->getId()]['external'] == 'yes') { 


                        $seoURL = str_replace('<courseName>', strtolower(seo_url($course->getName(),'-',30)), $onlineApplicationCoursesUrl[$course->getId()]['seo_url']);
                        $seoURL = str_replace('<courseId>', $course->getId(), $seoURL);
                        $of_seo_url = ($seoURL!='') ? $seoURL : SHIKSHA_HOME.'/Online/OnlineForms/showOnlineForms/'.$course->getId();


                        ?>
							<a href="javascript:void(0);" onclick="gaTrackEventCustom('CATEGORY_PAGE_RNR', 'Apply_Online_External', '', this, '<?php echo $of_seo_url;?>?tracking_keyid=<?php echo $applyOnlinetrackingPageKeyId?>')">Apply Online</a>
					<?php } else { ?>
							<a href="javascript:void(0);" onclick="gaTrackEventCustom('CATEGORY_PAGE_RNR', 'Apply_Online', '', this, '<?php echo $onlineApplicationCoursesUrl[$course->getId()]['seo_url'];?>?tracking_keyid=<?php echo $applyOnlinetrackingPageKeyId?>')">Apply Online</a>
					<?php } ?>								
					<?php } ?>

					</span>
					<div style="display:none">
						<?php foreach($coursesBasicDetails[$institute_id] as $coursesArray){ ?>
								<input type="hidden" name="compare<?php echo $institute->getId();?>-<?=$coursesArray['course_id']?>list[]"  value= "<?=$coursesArray['course_id']?>" />
						<?php } ?>
					</div>
				<?php } ?>
            </div>
			
			<!-- Download e-brochure starts -->
			<?php if($course->isPaid() || $brochureURL[$course->getId()] != '') {
				//keep brochure url as a hidden parameter, to later check for its type(PDF/IMAGE) in js at the time of start download
                echo '<input type = "hidden" id = "course'.$course->getId().'BrochureURL" value="'.$brochureURL[$course->getId()].'">';
				if($recommendationPage && !$alsoOnShiksha) {
					if(!$sourcePage) {
						$sourcePage = 'CATEGORY_RECOMMENDATION_PAGE';
					} ?>
					<div class="apply_confirmation" id="apply_confirmation<?php echo $institute->getId(); ?>" <?php if(in_array($institute->getId(),$recommendationsApplied)) echo "style='display:block;'"; ?> >
						<p style="visibility:hidden;" class="success-msg">
							<i class="cate-new-sprite dwnld-mark-icon"></i>E-Brochure successfully mailed.
						</p>
						<input type='hidden' id="apply_status<?php echo $institute->getId(); ?>" value='<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "1"; else echo "0"; ?>' />
					</div>
					<div class="download-btn-col flRt" style="position:relative">
						<a href="javascript:void(0);" class="download-btn <?php if(in_array($institute->getId(),$recommendationsApplied)) echo " disabled"; ?>" style="font-weight: normal !important; background:#fbfbfb; padding:5px 6px; color:#818181 !important; -moz-border-radius:none !important; -webkit-border-radius:none !important; border-radius:none !important; border:1px solid #d1d1d1 !important;"
						   onclick = "doAjaxApplyListings('<?=$institute->getId()?>','<?=$course->getId()?>','<?=$displayLocation->getCity()->getId()?>','<?=$displayLocation->getLocality()->getId()?>','<?php echo $responsecity ?>','<?=$sourcePage?>','<?=$appliedCourse?>','<?=$appliedInstitute?>')"
						   id="categoryPageApplyButton<?=$institute->getId()?>"><i class="common-sprite bro-sml-icon"></i>Download E-Brochure</a>
				 <?php if($request->getSubCategoryId() == 23) { 
						$courseId = $course->getId();
						if(in_array($courseId, $shortlistedCoursesOfUser)) {
                          $courseShortlistedStatus = 1;
							}
							?>		   
				<a style="padding:4px 12px 6px" isLastViewed = "<?=$lastViewedCourse == $courseId ? 'true' : 'false' ?>" onmouseover="showShortlistInfoBox(this);" onmouseout="hideShortlistInfoBox();"  href="javascript:void(0);" onclick="<?=($courseShortlistedStatus == 1 ? 'return false;' : '')?>  if(isShortListingInProgress) { return false;}  globalShortlistParams = {courseId: <?=$courseId?>, pageType: 'ND_Category', buttonId: '', shortlistCallback: 'shortlistCallbackForCtpgTuple',tracking_keyid :'<?=DESKTOP_NL_CTPG_TUPLE_SHORTLIST?>'}; shortListAnimation(this, function() { if(isShortListingInProgress) { return false;} shikshaUserRegistration.showShortlistRegistrationLayer({courseId: <?=$courseId?>, source: 'ND_Category'}); gaTrackEventCustom('CATEGORY_PAGE_RNR', 'ShortList', '<?=$course->getId()?>', this, '');" class="shrtlist-btn <?="shrt".$courseId?> <?=$courseShortlistedStatus == 1 ? 'shortlist-disable' :'' ?> "><i class="common-sprite shrtlist-star-icon"></i><span class="btn-label"><?=$courseShortlistedStatus == 1 ? 'Shortlisted' :'Shortlist' ?></span></a>    
				<br/> 
				<a target="_blank" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>" class="<?="shrt-view-link".$courseId?>" style="font-size:12px; margin:8px 0 0 0; display:<?=$courseShortlistedStatus == 1 ? 'block' : 'none' ?>; float:right">View my shortlist</a>
                                                        	
					   <?php
					   if($lastViewedCourse == $courseId && $courseShortlistedStatus != 1) {
						$isShortlistWidgetVisible = true;
						$this->load->view('myShortlist/shortListOnLastViewedMsg',$data);
					   }
				 } ?>
					</div>
				<?php }
				elseif(!$recommendationPage) {
					if(in_array($course->getId(),$recommendationsApplied)) {
						$display = '';
						$style = 'style="cursor:default;"';
						$disabled = 'disabled';
					} else {
						$display = 'display:none;';
						$style = 'style="font-weight: normal !important; background:#fbfbfb; padding:5px 6px; color:#818181 !important; -moz-border-radius:none !important; -webkit-border-radius:none !important; border-radius:none !important; border:1px solid #d1d1d1 !important;"';
						$disabled = '';
					}
					?>
					<div class="download-btn-col flRt" style="position:relative">
						<div style="height: 23px">
							<div id="applyCategoryPageConfirmation<?php echo $institute->getId(); ?>" class="recom-aply-row" style="margin-bottom: 8px; <?=$display?>" >
								<p class="success-msg">
									<i class="cate-new-sprite dwnld-mark-icon"></i>E-Brochure successfully mailed.
								</p>
							</div>
						</div>
						<a href="javascript:void(0);" class="download-btn <?=$disabled?>" <?=$style?>
							<?php if(!in_array($course->getId(),$recommendationsApplied)) { ?> onclick = "window.L_tracking_keyid = <?=$tracking_keyid?>; multipleCourseApplyForCategoryPage(<?php echo $institute->getId()?>,'CategoryPageApplyRegisterButton',this, <?php echo $course->getId(); ?>,'<?=base64_encode(serialize($courseListForBrochure))?>');" <?php } ?>
							                                    id="categoryPageApplyButton<?=$institute->getId()?>"><i class="common-sprite bro-sml-icon"></i>Download E-Brochure</a>
					
					<?php if($request->getSubCategoryId() == 23) { 
						$courseId = $course->getId();
						if(in_array($courseId, $shortlistedCoursesOfUser)) {
                          $courseShortlistedStatus = 1;
							}
							?>		   
				<a style="padding:4px 12px 6px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;" isLastViewed = "<?=$lastViewedCourse == $courseId ? 'true' : 'false' ?>" onmouseover="showShortlistInfoBox(this);" onmouseout="hideShortlistInfoBox();" href="javascript:void(0);" onclick="<?=($courseShortlistedStatus == 1 ? 'return false;' : '')?>  if(isShortListingInProgress) { return false;}  globalShortlistParams = {courseId: <?=$courseId?>, pageType: 'ND_Category', buttonId: '', shortlistCallback: 'shortlistCallbackForCtpgTuple',tracking_keyid :'<?=DESKTOP_NL_CTPG_TUPLE_SHORTLIST?>'}; shortListAnimation(this, function() { if(isShortListingInProgress) { return false;} shikshaUserRegistration.showShortlistRegistrationLayer({courseId: <?=$courseId?>, source: 'ND_Category'})}); gaTrackEventCustom('CATEGORY_PAGE_RNR', 'ShortList', '<?=$course->getId()?>', this, '');" class="shrtlist-btn <?="shrt".$courseId?> <?=$courseShortlistedStatus == 1 ? 'shortlist-disable' :'' ?> "><i class="common-sprite shrtlist-star-icon"></i><span class="btn-label"><?=$courseShortlistedStatus == 1 ? 'Shortlisted' :'Shortlist' ?></span></a>    
				<br/> 
				<a target="_blank" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>" class="<?="shrt-view-link".$courseId?>" style="font-size:12px; margin:8px 0 0 0; display:<?=$courseShortlistedStatus == 1 ? 'block' : 'none' ?>; float:right">View my shortlist</a>
                
					   <?php 
					   if($lastViewedCourse == $courseId && $courseShortlistedStatus != 1) {
						$isShortlistWidgetVisible = true;
					   $this->load->view('myShortlist/shortListOnLastViewedMsg',$data);
					   }
					} ?>	</div>
				<?php }
			} 
                        else if($request->getSubCategoryId() == 23) { 
						$courseId = $course->getId();
						if(in_array($courseId, $shortlistedCoursesOfUser)) {
                          $courseShortlistedStatus = 1;
							}
							?>
                            <div class="download-btn-col flRt" style="position:relative">
                            <div style="height: 23px">
                            </div>
                                <a style="padding:4px 12px 6px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;" isLastViewed = "<?=$lastViewedCourse == $courseId ? 'true' : 'false' ?>" onmouseover="showShortlistInfoBox(this);" onmouseout="hideShortlistInfoBox();" href="javascript:void(0);" onclick="<?=($courseShortlistedStatus == 1 ? 'return false;' : '')?>  if(isShortListingInProgress) { return false;}  globalShortlistParams = {courseId: <?=$courseId?>, pageType: 'ND_Category', buttonId: '', shortlistCallback: 'shortlistCallbackForCtpgTuple',tracking_keyid :'<?=DESKTOP_NL_CTPG_TUPLE_SHORTLIST?>'}; shortListAnimation(this, function() { if(isShortListingInProgress) { return false;} shikshaUserRegistration.showShortlistRegistrationLayer({courseId: <?=$courseId?>, source: 'ND_Category'})}); gaTrackEventCustom('CATEGORY_PAGE_RNR', 'ShortList', '<?=$course->getId()?>', this, '');" class="shrtlist-btn <?="shrt".$courseId?> <?=$courseShortlistedStatus == 1 ? 'shortlist-disable' :'' ?> "><i class="common-sprite shrtlist-star-icon"></i><span class="btn-label"><?=$courseShortlistedStatus == 1 ? 'Shortlisted' :'Shortlist' ?></span></a>                            
                        <br/> 
						<a target="_blank" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>" class="<?="shrt-view-link".$courseId?>" style="font-size:12px; margin:8px 0 0 0; display:<?=$courseShortlistedStatus == 1 ? 'block' : 'none' ?>; float:right">View my shortlist</a>
                
                        </div>
                        <?php
                        if($lastViewedCourse == $courseId && $courseShortlistedStatus != 1) { 
						$isShortlistWidgetVisible = true;
                        $this->load->view('myShortlist/shortListOnLastViewedMsg',$data);
                        }
} ?>
			<!-- Download e-brochure ends -->
		</div>
</div>

<!-- Hidden Div for Apply Now Starts -->
<?php if($course->isPaid() || $brochureURL[$course->getId()] != '') { ?>
	<div id="institute<?=$institute->getId()?>name" style="display:none"><?=html_escape($institute->getName())?>, <?=$displayLocation->getCity()->getName();?></div>
	<select id="applynow<?php echo $institute->getId()?>" style="display:none">
		<?php 
            foreach($coursesBasicDetails[$institute_id] as $coursesArray){
            // foreach($courses as $applyCourse){
			// $applyCourse->setCurrentLocations($request);
			// $courseLocations = $applyCourse->getCurrentLocations();
            
			// $localityArray[$applyCourse->getId()] = getLocationsCityWise($courseLocations); 
            ?>
			<option title="<?php echo html_escape($coursesArray['course_name']); ?>" value="<?php echo $coursesArray['course_id']; ?>" <?php echo ($selectedCourseId == $coursesArray['course_id']) ? 'selected="selected"' : ''; ?>><?php echo html_escape($coursesArray['course_name']); ?></option>
		<?php } ?>
	</select>
	<div style="display:none">
		<?php foreach($coursesBasicDetails[$institute_id] as $coursesArray){ ?>
			<input type="hidden" name="compare<?php echo $institute->getId();?>-<?=$coursesArray['course_id']?>list[]"  value= "<?=$coursesArray['course_id']?>" />
		<?php } ?>
	</div>
<?php } ?>

<?php if($recommendationPage && !$alsoOnShiksha): ?>
	<input type='hidden' id='params<?php echo $institute->getId(); ?>' value='<?php echo html_escape(getParametersForApply($validateuser,$course,$responsecity,$responselocality)); ?>' />
	<input type='hidden' id='reco_params<?php echo $institute->getId(); ?>' value='<?php echo html_escape(getParametersForApply($validateuser,$course,$responsecity,$responselocality)); ?>' />
<?php endif; ?>
<!-- Hidden Div for Apply Now Ends -->

<div id="recommendation_inline<?php echo $institute->getId();?>" style="display:none; float: left; width: 100%; margin-top:20px;"></div>

<?php if(!$recommendationPage) { ?>
<script>
	compareDiv = 1;
</script>
<?php } ?>

<script>
	var subcatSameAsldbCourseCategoryPage = 1;
	localityArray = <?=json_encode($localityArray)?>;
	for(i in localityArray) {
		custom_localities[i] = localityArray[i];
	}
</script>

<?php
$RNRListingFeedbackCookieValues = $_COOKIE['RNR_LISTING_FB'];
$RNRListingFeedbackCookieValues = explode(",", $RNRListingFeedbackCookieValues);
$upVoteClass  = "cate-new-sprite vote-up";
$dwnVoteClass = "cate-new-sprite vote-dwn";
$thanksMsg = "Thanks for your feedback!";
$commentContDisplay = "none";
foreach($RNRListingFeedbackCookieValues as $cookieValue){
	$parts = explode(":", $cookieValue);
	if($parts[0] == $instituteId){
		if($parts[1] == 1){
			$upVoteClass  = "cate-new-sprite vote-up-active";
		} else {
			$dwnVoteClass = "cate-new-sprite vote-dwn-active";
		}
		$commentContDisplay = "block";
	}
}

$RNRVisitedListingIds = $_COOKIE['RNR_VIL'];
$RNRVisitedListingIds = explode(",", $RNRVisitedListingIds);
if(in_array($instituteId, $RNRVisitedListingIds) && false ) { // disabling feedback
?>
<div class="cate-help-info clear-width">
	<p>Was the information helpful?
			<a href="javascript:void(0);" onclick="rateListingFromRNRCatPage(<?php echo $instituteId;?>, 1);"><i class="<?php echo $upVoteClass;?>" id="voteup_<?php echo $instituteId;?>"></i>Yes</a>
			<a href="javascript:void(0);" onclick="rateListingFromRNRCatPage(<?php echo $instituteId;?>, 0);"><i class="<?php echo $dwnVoteClass;?>" id="votedown_<?php echo $instituteId;?>"></i>No</a>
	</p>
	<div class="comment-section" id="rate_comment_sec_<?php echo $instituteId;?>_cont" style="display: <?php echo $commentContDisplay;?>;">
		<p class="thanku-msg" id="rate_comment_sec_<?php echo $instituteId;?>_thanksmsg">Thanks for your feedback!</p>
		<div class="comment-box" id="rate_comment_sec_<?php echo $instituteId;?>" style="display: none;">
			<i class="cate-new-sprite cate-comment-arrw"></i>
			<textarea onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" class="commnt-textarea" name="textarea" id="rate_comment_sec_<?php echo $instituteId;?>_text" default="Please share your comments?">Please share your comments?</textarea>
			<input class="sbmt-btn" type="button" value="Submit" onclick="submitRateCommentCatPage(<?php echo $instituteId;?>);">
			<span id="rate_comment_sec_<?php echo $instituteId;?>_loader_span" style="display: none;"><img src="public/images/loader_small_size.gif"></span>
		</div>
		<div style="color:red;font-weight:normal;display: none;" id="rate_comment_sec_<?php echo $instituteId;?>_error_div">&nbsp;</div>
	</div>
</div>
<?php
}
?>
<script type="text/javascript">
    function compareInstitutes(id) {
        $j('#'+id).attr('checked',($j('#'+id+':checked').length > 0) ? false : true);
    }
</script>
