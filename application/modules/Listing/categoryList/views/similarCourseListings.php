<?php
		$count = $resultSetOffset;
		//$localityArray = array();
		foreach($institutes as $institute) {
			$count++;
			$selectedCourseId = "";
			
				
				$course = $formattedcourses[$institute->getId()];
			
			
			global $appliedFilters;
			if($request){
				$appliedFilters = $request->getAppliedFilters();
				$course->setCurrentLocations($request);
			}
			//error_log("========== Instance : ".get_class($course)." =============");
			
			$displayLocation = $course->getCurrentMainLocation();
			$courseLocations = $course->getCurrentLocations();
			if($appliedFilters){
				foreach($courseLocations as $location){
					$localityId = $location->getLocality()?$location->getLocality()->getId():0;
					if(in_array($localityId,$appliedFilters['locality'])){
						$displayLocation = $location;
						break;
					}
					if(in_array($location->getCity()->getId(),$appliedFilters['city'])){
						$displayLocation = $location;
						break;
					}
				}
			}
			if(!$courseLocations || count($courseLocations) == 0){
				$courseLocations = $course->getLocations();
		}
			if(!$displayLocation){
				$displayLocation = $course->getMainLocation();
			}
			$courses = $institute->getCourses();
			$additionalURLParams = "";
			if($request){
				if(count($course->getLocations()) > 1){
					if($request->getCityId() > 1){
						$additionalURLParams = "?city=".$displayLocation->getCity()->getId();
						if($request->getLocalityId()){
							$additionalURLParams .= "&locality=".$request->getLocalityId();
						}
					}
					$course->setAdditionalURLParams($additionalURLParams);
					$institute->setAdditionalURLParams($additionalURLParams);
				}
			}
			$track_url = ""; 
		        if($recommendationPage) {
				$onClickAction = "processActivityTrack(".$appliedCourse.", ".$course->getId().", ".$appliedInstitute.", 'LP_Reco_Viewed', 'LP_Reco_ShowRecoLayer', 'NULL', '".$course->getURL()."', event);";
				$recomd_course_id = "";
				foreach($applied_data as $temp_id) {
					$recomd_course_id = $temp_id;
				}
                        	$track_url = 'onclick="trackEventByCategory('.'\'ShowRecoPage_Reco\''.','.'\'Course_'.$recomd_course_id.'\','.'\'replacesomethinghere\''.')"'; 
			}		
?>
		<li id="instituteDetail<?php echo $institute->getId(); ?>" elementtofocus="course<?=$course->getId()?>">
			<div class="instituteListsDetails">
			<?php if(!$recommendationPage) { ?>	
				<div class="checkCol">
					<?php
						if($institute->isSticky() || $institute->isMain()){
							echo '<span class="checkIcon"></span>';
						}
					?>	
				</div>
			<?php } ?>	
				<div class="collegeDetailCol">
					<?php if($alsoOnShiksha){?>
					<script>
					var track_tocken = universal_page_type+'_'+universal_page_type_id;
					</script>
					<div class="college-title"><a trackEventByCategory('Listingpage_Reco',track_tocken,'<?php echo 'Bottom_'.$count.'_'.'Insti'.'_'.$institute->getId();?>');" href="<?php echo $course->getURL();?>" title="<?php echo html_escape($institute->getName()); ?>"><?php echo html_escape($institute->getName()); ?>, </a> <span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span></div>
					<?php }else{
                                        $temp_track_url = "";				   
                                        if($track_url) {
						$temp_track_url = $track_url;
						$temp_track_url = str_replace(
									    "replacesomethinghere",
									    "Bottom_".$count."_institute_".$institute->getId(),
                                                                            $temp_track_url
                                                                  );
						$temp_track_url = substr_replace($temp_track_url, $onClickAction.' ', 9, 0);
					}
                                        ?>
					<!--<h3><a <?php echo $temp_track_url;?> href=<?=(($course->getOrder()==1)?'"'.$institute->getURL().'"':'"'.$course->getURL().'" rel="nofollow"')?> title="<?php echo html_escape($institute->getName()); ?>"><?php echo html_escape($institute->getName()); ?>, </a> <span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span></h3>-->
					<h3><a <?php echo $temp_track_url;?> href="<?=$course->getURL();?>" oncontextmenu="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', '', <?=$course->getId()?>);" onclick="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', '', <?=$course->getId()?>);" title="<?php echo html_escape($institute->getName()); ?>"><?php echo html_escape($institute->getName()); ?>, </a> <span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span></h3>
					<?php } ?>
					<?php $newCourse = clone $course; echo Modules::run('listing/ListingPageWidgets/seeAllBranches',$newCourse,$courseLocations,"yes"); ?>
					<em><?php echo trim($institute->getUsp())?'"'.$institute->getUsp().'"':''; ?></em>
					<div class="collegeDetailsWrapper">
						<div class="collegePic">
							<?php
								if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL()){ ?>
									<a href="<?=$course->getURL()?>"><img oncontextmenu="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', '', <?=$course->getId()?>);" onclick="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', '', <?=$course->getId()?>); '<?=$onClickAction?>'" src="<?=$institute->getMainHeaderImage()->getThumbURL()?>" width="118" alt="'.html_escape($institute->getName()).'" title="'.html_escape($institute->getName()).'"/></a>
								<?php }else{ ?>
									<a href="<?=$course->getURL()?>"><img oncontextmenu="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', '', <?=$course->getId()?>);" onclick="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', '', <?=$course->getId()?>); <?=$onClickAction?>" src="/public/images/avatar.gif" alt="<?=html_escape($institute->getName())?>" title="<?=html_escape($institute->getName())?>"/></a>
								<?php }
							?>
						        <?php
								if($validateuser != 'false') {
									if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin'  || $validateuser[0]['usergroup'] == 'saCMS'){
										if(is_object($course)){
												if($course->isPaid()){
													echo '<div style="margin-top: 4px;"><label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label></div>';
												}else{
													echo '<div style="margin-top: 4px;"><label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label></div>';	
												}
										}
									}
								}
						        ?>
						</div>
						<div class="collegeDescription" style="min-height: 120px;">
							<?php if($institute->getAIMARating()) { ?>
							<div class="aimaRating"  onmouseover="catPageToolTip('aima','',this,30,-10);" onmouseout="hidetip();">
								<span>AIMA Rating:</span>
								<span class="ratingBox"><?=$institute->getAIMARating()?></span>
								<img align="absmiddle" src="/public/images/question-icons2.gif"/>
							</div>
							<?php } ?>
							<?php if($institute->getAlumniRating()) { ?>
							<div class="alumniRating">
								<span>Alumni Rating:</span>
								<span>
									<?php
									$i = 1;
									while($i <= $institute->getAlumniRating()){
									?>
										<img border="0" src="/public/images/nlt_str_full.gif">
									<?php
										$i++;
									}
									?>
								</span>
								<span class="rateNum">&nbsp;<?=$institute->getAlumniRating()?>/5</span>
							</div>
							<?php } ?>
							<div class="clearFix spacer10"></div>
							<?php 
							$temp_track_url = "";				   
				                        if($track_url) {
								$temp_track_url = $track_url;
								$temp_track_url = str_replace(
									"replacesomethinghere",
									"Bottom_".$count."_course_".$course->getId(),
		                                                	$temp_track_url
		                                          	);
							}
							$temp_track_url = substr_replace($temp_track_url, $onClickAction.' ', 9, 0);
							?>
							<strong><a <?php if($alsoOnShiksha) :?>onclick="trackEventByCategory('Listingpage_Reco',track_tocken,'<?php echo 'Bottom_'.$count.'_'.'Course'.'_'.$course->getId();?>');" <?php endif;?> <?php echo $temp_track_url;?> href="<?php echo $course->getURL(); ?>" oncontextmenu="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', '', <?=$course->getId()?>);" onclick="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', '', <?=$course->getId()?>);" ><?php echo html_escape($course->getName()); ?></a>
							<span>
								- <?php echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; ?>
								<?php echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():""); ?>
								<?php echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():""); ?>
							</span></strong>
							<div class="feeStructure">
								<?php
								$sortingCriteria  = array();
								if($request){
										$sortingCriteria   = $request->getSortingCriteria();
										$pageSubCategoryId = $request->getSubCategoryId();
								}
								$resultsSortBy = false;
								if(!empty($sortingCriteria) && array_key_exists('sortBy', $sortingCriteria)){
										$resultsSortBy = $sortingCriteria['sortBy'];
								}
								$exams = $course->getEligibilityExams();
								$feesFilters = $appliedFilters['fees'];
								
								if($course->getFees($displayLocation->getLocationId())->getValue()) {
										$highlightClassName = "";
										if( (!empty($feesFilters) || $resultsSortBy == "fees") && in_array($pageSubCategoryId, $subcategoriesChoosenForRNR)){
												$highlightClassName = "highlight-filter-values";
										}
										?>
										<label>Fees: </label> <span class="<?php echo $highlightClassName;?>"><?=$course->getFees($displayLocation->getLocationId())?></span> 
										<?php
								} else {
								?>
									<label>Fees: </label> <span>Not Available</span>
								<?php
								}
								$courseExams = $appliedFilters['courseexams'];
								$filterSelectedExams = array();
								$filterSelectedExamsRange = array();
								foreach($courseExams as $exam){
										$explode = explode("_", $exam);
										if(!empty($explode)){
												$filterExam = $explode[0];
												$filterSelectedExams[] = $filterExam;
												$filterSelectedExamsRange[$filterExam] = $explode[1];
										}
								}
								if(count($exams) > 0){
									echo '</div><div class="feeStructure">';
								}
								if(count($exams) > 0){ 
									if($institute->getInstituteType() == "Test_Preparatory_Institute"){
								?>
									<label>Exams Prepared for: </label> <span>
								<?php
									}else{
								?>
									<label>Eligibility: </label> <span>
								<?php
									}
									$examAcronyms = array();
									foreach($exams as $exam) {
										$highlightClassName = "";
										$filteredExamFlag = false;
										//check if the exam marks type is correct or not.
										if($request){
												$pageSubCategoryId = $request->getSubCategoryId();
										}
										$examMarksTypeValid = isExamMarksTypeValid($pageSubCategoryId, $exam->getAcronym(), $exam->getMarksType());
										$examMarksTypeValid = TRUE;
										if($examMarksTypeValid){
												if(in_array($exam->getAcronym(), $filterSelectedExams)){
														$satisfies = doesExamScoreSatisfiesScoreRange($exam->getAcronym(), $exam->getMarks(), $exam_list['MBA'], $exam_list['Engineering'], $filterSelectedExamsRange);
														if($satisfies){
																$filteredExamFlag = true;
														}
												}
												$sorterSatifiesCondition = doesExamIncludedInSorters($exam->getAcronym(), $sortingCriteria);
												if($filteredExamFlag || $sorterSatifiesCondition){
														if($exam->getMarks() > 0){
																$examAcronyms[] = "<b class='highlight-filter-values'>" . $exam->getAcronym() . "(" . $exam->getMarks() . ")</b>";
														} else {
																$examAcronyms[] = "<b class='highlight-filter-values'>" . $exam->getAcronym() . "</b>";
														}		
												} else {
														if($exam->getMarks() > 0){
																$examAcronyms[] = $exam->getAcronym() . "(" . $exam->getMarks() . ")";
														} else {
																$examAcronyms[] = $exam->getAcronym();
														}		
												}
										}
									}
									echo implode(', ',$examAcronyms); ?>
									</span>
								<?php } ?>
							</div>
							
							<div style="margin-top:3px;">
								<?php
								$approvalsAndAffiliations = array();
								
								$approvals = $course->getApprovals();
								$degreePrefApplied = $appliedFilters['degreePref'];
								foreach($approvals as $approval) {
									$outString = "";
									if(in_array($approval,array('aicte','ugc','dec'))){
										if(in_array($approval, $degreePrefApplied)){
												$outString = "<span onmouseover=\"catPageToolTip('".$approval."','',this,0,-5);\" onmouseout=\"hidetip();\"><b class='highlight-filter-values'>".str_ireplace(' ','&nbsp;',langStr('approval_'.$approval)).'</b>&nbsp;<img align="absmiddle" src="/public/images/question-icons2.gif"/>'."</span>";
										} else {
											$outString = "<span onmouseover=\"catPageToolTip('".$approval."','',this,0,-5);\" onmouseout=\"hidetip();\">".str_ireplace(' ','&nbsp;',langStr('approval_'.$approval)).'&nbsp;<img align="absmiddle" src="/public/images/question-icons2.gif"/>'."</span>";	
										}
									} else {
										$outString = "<span>".langStr('approval_'.$approval)."</span>";
									}
									$approvalsAndAffiliations[] = $outString;
								}
								
								$affiliations = $course->getAffiliations();
								foreach($affiliations as $affiliation) {
									$approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);	
								}
		
								echo implode(', ',$approvalsAndAffiliations);
								?>
							</div>
							
							<?php
							if(count($salientFeatures = $course->getSalientFeatures(4))){
							?>
							<ul class="facilities">
							<?php
								foreach($salientFeatures as $sf){
							?>
									<li><span></span><strong><?=str_ireplace(" ","&nbsp;",langStr('feature_'.$sf->getName().'_'.$sf->getValue()))?></strong></li>
							<?php
								}
							?>
							</ul>
							<?php
							}
							?>
							<div class="spacer10 clearFix"></div>
                                                        <?php

		    if($course->isPaid() || $brochureURL[$course->getId()] != ''){
			        echo '<input type = "hidden" id = "course'.$course->getId().'BrochureURL" value="'.$brochureURL[$course->getId()].'">';
		    }
			?>
				<div class="compareInstBox">						
						<div id="applyCategoryPageConfirmation<?php echo $institute->getId(); ?>" class="recom-aply-row" style="margin-bottom: 8px;<?php if(in_array($course->getId(),$recommendationsApplied)) echo "display:block;"; ?>" >
								<i class="thnx-icon" style="margin: 0; float: left"></i>
								<p style="margin:0 0 0 23px;float: none; color: inherit">E-brochure successfully mailed.</p>
						</div>
				<?php
				$styleStmtDownloadBrochure 	= "";
				$styleStmtSimilarInstBtn 	= "";
				if(in_array($course->getId(),$recommendationsApplied)) {
						$styleStmtDownloadBrochure .= 'cursor:default;';
				}
				if(!($course->isPaid() || $brochureURL[$course->getId()] != '')){
						$styleStmtDownloadBrochure .= 'display:none;';
						$styleStmtSimilarInstBtn .= "margin-left:0px;display:inline;";
						
				}
				?>
						<input id="categoryPageApplyButton<?php echo $institute->getId(); ?>" class="<?php if(in_array($course->getId(),$recommendationsApplied)) { echo 'orangeButtonStyle_disabled'; } else { echo 'orangeButtonStyle'; } ?>" style="<?=$styleStmtDownloadBrochure?>"
								type="button" value="Download E-Brochure" title="Download E-Brochure" <?php if(!in_array($course->getId(),$recommendationsApplied)) { ?> onClick = "if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;}return multipleCourseApplyForCategoryPage(<?php echo $institute->getId()?>,'CategoryPageApplyRegisterButton',this,<?=$course->getId()?>);" <?php } ?>/>
						<a href="/similar-institutes-<?=$institute->getId()?>-<?=$course->getId()?>" onclick="viewSimilarInsttDbTrack(<?=$course->getId()?>, <?=$institute->getId()?>, 'SimilarInstitute', 'ViewSimilarInstitute');" oncontextmenu="viewSimilarInsttDbTrack(<?=$course->getId()?>, <?=$institute->getId()?>, 'SimilarInstitute', 'ViewSimilarInstitute');" class="view-smlr-btn orangeButtonStyle" style="<?=$styleStmtSimilarInstBtn?>">View Similar Institutes</a>
				</div>
						</div>
					   
					</div>
				</div>
			</div>
		<?php
			if($course->isPaid() || $brochureURL[$course->getId()] != ''){
		?>
		<!-- Hidden Div for Apply Now -->
		<div id="institute<?=$institute->getId()?>name" style="display:none"><?=html_escape($institute->getName())?>, <?=$displayLocation->getCity()->getName();?></div>
		<select id="applynow<?php echo $institute->getId()?>" style="display:none">
				<?php
					foreach($courses as $applyCourse){
						$applyCourse->setCurrentLocations($request);
						$courseLocations = $applyCourse->getCurrentLocations();
						//$localityArray[$applyCourse->getId()] = getLocationsCityWise($courseLocations);
				?>
						<option title="<?php echo html_escape($applyCourse->getName()); ?>" value="<?php echo $applyCourse->getId(); ?>" <?php echo ($selectedCourseId == $applyCourse->getId()) ? 'selected="selected"' : ''; ?>><?php echo html_escape($applyCourse->getName()); ?></option>
				<?php
					}
				?>
		</select>
		<div style="display:none">
			<?php
				foreach($courses as $applyCourse){
			?>
				<input type="hidden" name="compare<?php echo $institute->getId();?>-<?=$course->getId()?>list[]"  value= "<?=$applyCourse->getId()?>" />
			<?php	
				}
			?>
		</div>
		<?php
			}
		?>
		
		<?php if($recommendationPage && !$alsoOnShiksha): ?>
			<input type='hidden' id='params<?php echo $institute->getId(); ?>' value='<?php echo html_escape(getParametersForApply($validateuser,$course,$responsecity,$responselocality)); ?>' />
			<input type='hidden' id='reco_params<?php echo $institute->getId(); ?>' value='<?php echo html_escape(getParametersForApply($validateuser,$course,$responsecity,$responselocality)); ?>' />
		<?php endif; ?>
		<!-- Hidden Div for Apply Now -->
		
		<div id="recommendation_inline<?php echo $institute->getId();?>" style="display:none; float: left; width: 100%; margin-top:20px;"></div>
		</li>	
		
		
		<?php
		
		
	}
?>
<script>
		var localityArray = <?php echo json_encode($localityArray);?>;
		var listings_with_localities = <?php echo $listings_with_localities; ?>;
		var custom_localities = [];
		for(i in localityArray)
		{
				custom_localities[i] = localityArray[i];
		}
		for(i in listings_with_localities)
		{
				custom_localities[i] = listings_with_localities[i];
		}
</script>