<div class="collegeDescription <?php if($count !=1) { echo 'top-ln-smlr';} ?>">
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

	</div>