<div data-role="content" style="background:#e6e6dc">
	<div data-enhance="false">
		<section class="">
			<section class="clearfix">
				<section class="content-section">
					<?php foreach($shortlistedCourses as $courseId => $course) {					
						$displayLocation = ($course->getMainLocation() != null) ? $course->getMainLocation()->getCityName() : '';
						
						//processing exam name and its score
						//$exams = $course->getEligibilityExams();
						$exams = $course->getEligibility(array('general'));
    					$exams = $exams['general'];
					    $marksFormat = array(
					                  'percentile' => "%ile",
					                  'percentage' => "%",
					                  'score/marks' =>  "marks",
					                  'rank'        =>"rank"
					              );
					    $examAcronyms = array();
					    $allExams = $course->allExams;
					    if(!empty($allExams)){
					        if(!empty($exams)){
					            foreach($exams as $exam) {
					                if ($exam->getValue() > 0) {
					                    $examAcronyms[$exam->getExamName()] = ': '.$exam->getValue().' '.$marksFormat[$exam->getUnit()];
					                    unset($allExams[$exam->getExamName()]);
					                } else {
					                    $examAcronyms[$exam->getExamName()] = '';
					                }
					            }
					        }
					        if(count($allExams) > 0){
					            foreach ($allExams as $exam) {
					                $examAcronyms[$exam] = '';
					            }
					        }
					    }else {
					        $examAcronyms = array('N/A' => '');
					    }
					        ?>

						<section class="listing-tupple" id="row_<?=$courseId?>">
							 <a href="javascript:void(0)" course-option=''  class="listing-menu"><span>&#8226;</span><span>&#8226;</span><span>&#8226;</span></a>
							<div class="courseInfoToPass" style="display:none;">
								<courseId><?=$courseId;?></courseId>
								<instituteId><?=$course->getinstituteId();?></instituteId>
								<instituteName><?=base64_encode($course->getInstituteName());?></instituteName>
								<tracking_keyid_DEB><?=MOBILE_NL_SHORTLIST_HOME_TUPLE_SETLAYER_DEB?></tracking_keyid_DEB>
							</div>
							<aside class="listing-cont">
								<strong><?php echo html_escape($course->getInstituteName()); ?> <span><?php echo html_escape($displayLocation); ?></span></strong>
								<p class="course-name"><?php echo html_escape($course->getName());?></p>
								<ul class="shortlist-details">
									<li style="margin-bottom:0px;">
										<label>Exams Accepted</label>
										<?php
											$examNameList = "";
											foreach($examAcronyms as $examName => $score) {
												$examNameList = $examName.$score.", ".$examNameList;
											}
											$examNameList = rtrim($examNameList, ', ');
										?>
										<p><?php echo $examNameList; ?></p>
									</li>
									
								</ul>
  								<?php if(in_array($courseId,$downloadEBrochureApplied)){  ?>
              							<p class="ebroucherSucss" id="ebroucherSuccess_<?=$courseId?>"><i class="msprite green-msg-icn"></i>E-Brochure has been successfully e-mailed</p>
									  <?php } ?>
							</aside>
							
							<nav class="listing-nav">
								<ul>
									<?php if(in_array($course->getId(), $coursesWithPlacementData)){?>
									<li><a onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'shortlist_tuple_tabs', 'placement', event, '/mobile_myShortlist5/MyShortlistMobile/showCourseDetailsTabs/<?php echo $courseId ?>');" href="/mobile_myShortlist5/MyShortlistMobile/showCourseDetailsTabs/<?php echo $courseId ?>">Placements</a></li>
									<li><span>&#8226;</span></li>
									<?php }?>				
									<?php if($course->getReviewCount() > 0){?>
									<li><a onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'shortlist_tuple_tabs', 'reviews', event, '/mobile_myShortlist5/MyShortlistMobile/showCourseDetailsTabs/<?php echo $courseId ?>/reviews');" href="/mobile_myShortlist5/MyShortlistMobile/showCourseDetailsTabs/<?php echo $courseId ?>/reviews">Reviews</a></li>
									<li><span>&#8226;</span></li>
									<?php }?>
									<li><a onclick="populateQuesDiscLayer('question','530');setHiddenParamsForAsk('<?php echo $courseId;?>','<?php echo $course->getinstituteId();?>')" data-inline="true" data-rel="dialog" data-transition="fade" href="#questionPostingLayerOneDiv">Ask</a></li>
									<li><span>&#8226;</span></li>
									<li><a onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'shortlist_tuple_tabs', 'notes', event, '/mobile_myShortlist5/MyShortlistMobile/showCourseDetailsTabs/<?php echo $courseId ?>/notes');" href="/mobile_myShortlist5/MyShortlistMobile/showCourseDetailsTabs/<?php echo $courseId ?>/notes">Notes</a></li>
								</ul>
							</nav>
						</section>
      
					<?php } ?>
	          		<input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP">
	          		<input type="hidden" id="instituteIdQP" name="instituteIdQP">
	          		<input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="Asked_Question_On_Listing_MOB">
	          		<input type="hidden" id="listingTypeQP" name="listingTypeQP" value="institute">

					<div id="addMoreColgNonSticky" class="add-btn-row non-sticky"><a onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'add_more_colleges', '', event, '/mobile_myShortlist5/MyShortlistMobile/myShortlist?addMoreColg=1');" href="/mobile_myShortlist5/MyShortlistMobile/myShortlist?addMoreColg=1" class="add-btn"><big>+</big> Add more colleges</a></div>
				</section>
			</section>
        </section>
        
        <?php $this->load->view('mcommon5/footerLinks'); ?>
    </div>
</div>
<div id="addMoreColgSticky" class="add-btn-row sticky"><a onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'add_more_colleges', '', event, '/mobile_myShortlist5/MyShortlistMobile/myShortlist?addMoreColg=1');" href="/mobile_myShortlist5/MyShortlistMobile/myShortlist?addMoreColg=1" class="add-btn"><big>+</big> Add more colleges</a></div>
<script>
	var inititalScrollVal = $(window).scrollTop();	
	var nonStickyDivTop = $('#addMoreColgNonSticky').offset().top;
	$(document).bind('scroll', function() {
		var scrollTopVal = $(window).scrollTop();
		var nonStickyDivTop = $('#addMoreColgNonSticky').offset().top;
		var stickyDivTop = $('#addMoreColgSticky').offset().top;
		var footerTop = $('#page-footer').offset().top;
		
		if((!hamburgerFlag && typeof(hamburgerFlag) != 'undefined') && (!rightHamburgerFlag && typeof(rightHamburgerFlag) != 'undefined')) {
			if(inititalScrollVal < scrollTopVal && stickyDivTop < nonStickyDivTop) {
				$('#addMoreColgSticky').css('visibility', 'visible');
			}else {
				$('#addMoreColgSticky').css('visibility', 'hidden');
			}
			inititalScrollVal = scrollTopVal;
		}
		else {
			$('#addMoreColgSticky').css('visibility', 'hidden');
		}
	});
</script>
 	