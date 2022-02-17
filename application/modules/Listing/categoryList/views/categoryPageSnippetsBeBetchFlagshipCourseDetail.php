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
							<strong><a <?php if($alsoOnShiksha) :?>onclick="trackEventByCategory('Listingpage_Reco',track_tocken,'<?php echo 'Bottom_'.$count.'_'.'Course'.'_'.$course->getId();?>');" <?php endif;?> <?php echo $temp_track_url;?> href="<?php echo $course->getURL(); ?>"><?php echo html_escape($course->getName()); ?></a>
							</strong>
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
							    $salientFeatures = array(); // Disabling Salient Features
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
					<div class="compareInstBox">
					<?php 
					if($similarCourseCount)
					{	
					  $moreBranchesText = $similarCourseCount == 1 ? "1 more branch" : $similarCourseCount." more branches";
						?>
					<a href = 'javascript:void(0);' id ='moreBranches_<?=$institute->getId()?>' class='smlr-course-btn' style='margin-top:0' onclick = "toggleSimilarCoursesTuples(<?=$institute->getId()?>);trackEventByGA('NATIONAL_BE-BTECH_CATEGORY_PAGE','SEE_MORE_BRANCHES');" > 
									<i style='display:block; float:left' class='plus-icon2'></i> <?=$moreBranchesText?></a>
					<?php }
					?>
					<?php if(!$recommendationPage && ($course->isPaid() || $brochureURL[$course->getId()] != '')) { ?>
						<!--Add-to-compare--->
						<p>
<input onclick="updateCompareText('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');
updateAddCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_CATEGORY_PAGE');" type="checkbox" name="compare" id="compare<?php echo $institute->getId();?>-<?=$course->getId()?>" class="compare<?php echo $institute->getId();?>-<?=$course->getId()?>" value="<?php echo $institute->getId().'::'.' '.'::'.($institute->getMainHeaderImage()?$institute->getMainHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($institute->getName())).', '.$displayLocation->getCity()->getName().'::'.$course->getId().'::'.$course->getURL();?>"/>
<a  href="javascript:void(0);" onclick="checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_CATEGORY_PAGE');toggleCompareCheckbox('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');
updateAddCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');
return false;" id="compare<?php echo $institute->getId();?>-<?=$course->getId()?>lable" class="compare<?php echo $institute->getId();?>-<?=$course->getId()?>lable">Add to Compare</a>
						</p>
					        <!--end---->
                                         <?php } ?> 
					</div>
		
	</div>