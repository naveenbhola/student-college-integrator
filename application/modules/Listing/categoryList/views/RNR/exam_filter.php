<?php
// get the global values
global $filters;
global $appliedFilters;
global $MBA_SCORE_RANGE;
global $MBA_SCORE_RANGE_CMAT;
global $MBA_SCORE_RANGE_GMAT;
global $MBA_PERCENTILE_RANGE_MAT;
global $MBA_PERCENTILE_RANGE_XAT;
global $MBA_PERCENTILE_RANGE_NMAT;
global $ENGINEERING_EXAMS_REQUIRED_SCORES;
global $MBA_EXAMS_REQUIRED_SCORES;
global $MBA_NO_OPTION_EXAMS;

// fetch Parent and current exam filter values from exam filter object(s)
$courseExamFilteredValues 	= $filters['courseexams'] ?$filters['courseexams']->getFilteredValues() : array();
$ApplicableCourseExams 		= array_keys($solrFacetValues['course_RnR_valid_exams_current']);
?>

<!-- Exams filter Starts-->
<div class="filter-blocks clear" id="examFilterSection">
	<div class="mb10">
		<h3 class="filter-title flLt" style="margin-bottom:0;">
		<i class="cate-new-sprite exam-icon"></i>Exams Accepted</h3>
		<a class="flRt" href="javascript:void(0);" onclick="resetCategoryFilter('examFilterSection');"><i class="cate-new-sprite reset-icon"></i>Reset</a>
		<div class="clearfix"></div>
	</div>
	<div class="customInputs">
		<div class="scrollbar1" id="courseExamFilterScrollbar">
			<div class="scrollbar">
					<div class="track">
							<div class="thumb"></div>
					</div>
			</div>
			<div class="viewport" style="height:145px;overflow:hidden;" >
				<div class="overview">
					<ul class="refine-list">
                                                
						<?php
						    $pageSubCategoryId 		= $request->getSubcategoryId();
                                                    $examNamesInFilters 	= array_keys($courseExamFilteredValues);
                                                    $validExamFilterCount 	= 0;
                                                    $categoryExams 		= array();
						    
						    //  get the exam list for the current subcategory
                                                    if($pageSubCategoryId == 23){
							if(isset($exam_list['MBA'])){
									$categoryExams 	 = $exam_list['MBA'];
									$categoryExams[] = "GMAT";
							}
                                                    }
						    else if($pageSubCategoryId == 56){
							if(isset($exam_list['Engineering'])){
									$categoryExams = $exam_list['Engineering'];
							}
                                                    }
						    else {
							$categoryExams = array();
                                                    }
                                                    
						    // populate the array for selected courseexam names and score
                                                    $appliedCourseExamNames 	= array();
                                                    $appliedCourseScoreRange 	= array();
						    foreach($appliedFilters['courseexams'] as $examRange){
                                                            if(!empty($examRange)){
                                                                    $examRangeData = explode("_", $examRange);
                                                                    if(count($examRangeData) > 1) {
									$tempExamName  = $examRangeData[0];
									$tempExamRange = $examRangeData[1];
									$appliedCourseExamNames[] = $tempExamName;
									$appliedCourseScoreRange[$tempExamName] = $tempExamRange;
                                                                    }
                                                            }
                                                    }
                                                    
                                                    foreach($courseExamFilteredValues as $examName => $filterValues)
                                                    {
                                                            $disabled = '';
                                                            $urlExamName = $request->getExamName();
							    
							    // do not show the exams that are not applicable for this subcategory
                                                            if(!in_array($examName, $categoryExams)){
                                                                            continue;
                                                            }

							    // determine the checked state of the checkboxes
                                                            $checked = '';
                                                            $examScoreContDisplayStyle = "display:none;";
                                                            if(in_array($examName, $appliedCourseExamNames) || ($examName == $urlExamName && empty($appliedFilters['courseexams']) && !is_array($appliedFilters['courseexams']))){
                                                                            $examScoreContDisplayStyle = "display:block;";
                                                                            $checked = "checked";
                                                            }
                                                            else{
                                                                            $checked = "";
                                                            }
    
							    // determine the disabled state of the checkbox
                                                            if(!in_array($examName, $ApplicableCourseExams))
                                                                            $disabled = 'disabled';
    
                                                            $countVal = $solrFacetValues['course_RnR_valid_exams_current'][$examName] ? $solrFacetValues['course_RnR_valid_exams_current'][$examName] : 0;
                                                            // hide the score/rank input if exam is disabled
                                                            if($disabled)
                                                                     $examScoreContDisplayStyle = "display:none;";
						?>
						<li>
							<input type="checkbox" id="exam_cb_<?=trim($examName)?>" <?=$checked?> <?=$disabled?> name="courseexams[]" value="<?=$examName?>" onclick="applyRnRFiltersOnCategoryPages();" autocomplete="off">
							<label for="exam_cb_<?=trim($examName)?>">
									<span class="common-sprite"></span><p><?=$examName?> <em>(<?=$countVal?>)</em></p>
							</label>
									<?php
										if($pageSubCategoryId == 23){
											
										if(in_array($examName, $MBA_NO_OPTION_EXAMS)){
												$examScoreContDisplayStyle = "display:none;";
										}
										$placeHolderText = "Cut-off percentile";
										$defaultDropdownValue = "Any";
										if(in_array($examName, $MBA_EXAMS_REQUIRED_SCORES)){
												$placeHolderText = "Cut-off score";
										}
									?>
										<select style="width:137px;<?php echo $examScoreContDisplayStyle;?>" id="courseexamdd_<?php echo trim($examName);?>" onchange="onExamRangeChange('<?php echo trim($examName);?>'); applyRnRFiltersOnCategoryPages();" class="score-select">
												<option value="0" <?php echo (isset($appliedCourseScoreRange[$examName]) && $appliedCourseScoreRange[$examName] == 0) ? "selected" : ""; ?>><?php echo $placeHolderText;?></option>
									<?php
												$scoreRanges = $MBA_SCORE_RANGE;
												if($examName == "CMAT"){
													$scoreRanges = $MBA_SCORE_RANGE_CMAT;
												} else if($examName == "GMAT"){
													$scoreRanges = $MBA_SCORE_RANGE_GMAT;
												} else if($examName == "MAT"){
													$scoreRanges = $MBA_PERCENTILE_RANGE_MAT;
												} else if($examName == "XAT") {
													$scoreRanges = $MBA_PERCENTILE_RANGE_XAT; 
												} else if($examName == "NMAT"){
													$scoreRanges = $MBA_PERCENTILE_RANGE_NMAT;
												}
												foreach($scoreRanges as $key => $value){
									?>
												<option value="<?php echo $value;?>" <?php echo (!empty($appliedCourseScoreRange[$examName]) && $appliedCourseScoreRange[$examName] == $value) ? "selected" : ""; ?> ><?php echo $key;?></option>
									<?php
												}
									?>
										</select>
									<?php
										} else if($pageSubCategoryId == 56){
											
										$placeHolderText = "Enter Rank";
										if(in_array(trim($examName), $ENGINEERING_EXAMS_REQUIRED_SCORES)){
												$placeHolderText = "Enter Score";
										}
									?>
										<div class="exam-filterText" style="<?php echo $examScoreContDisplayStyle;?>">
											<input type="text" id="courseexamtextbox_<?php echo trim($examName);?>" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" onKeyPress = "checkEnterEvent(event);" default="<?php echo $placeHolderText;?>" value="<?php echo (!empty($appliedCourseScoreRange[$examName])) ? $appliedCourseScoreRange[$examName] : $placeHolderText; ?>"></input>
											<a href="javascript:void(0);" class="filter-btn flLt" onclick="applyRnRFiltersOnCategoryPages();">Go</a>
										</div>
									<?php
										}
									?>
							
						</li>
						<?php
						}
						?>
						
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Exams filter Ends -->