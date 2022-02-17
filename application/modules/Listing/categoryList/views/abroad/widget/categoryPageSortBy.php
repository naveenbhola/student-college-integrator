<div class="sort-section flRt" id="sortSection">
	<?php if(!$isZeroResultPage || $categoryPageRequest->isExamCategoryPage()) {
		if(is_object($userAppliedfilters['exams'])){
			$enabledExams = $userAppliedfilters['exams']->getFilteredValues();
		}else{
			$enabledExams = $userAppliedfilters['exams'];
		}

		if(empty($appliedFilters["exam"])) {
			if($categoryPageRequest->isExamCategoryPage()){
				$examName = $examsForFilter[reset(reset($appliedFilters["exam"]))];
			}else{
				foreach($examsForFilter as $examForFilter)
				{	// so we will check them against enabled exams, i.e. if the exam in the filter list is enabled..
					if(in_array($examForFilter,$enabledExams)!==false)
					{	// then it is used as sort option, otherwise we move to the next one
						$examName = $examForFilter;
						break;
					}
				}	
			}
		} else {
			$selectedFiltersArray = json_decode($filterSelectionOrder, true);
			$enabledOrder = array_intersect_key($selectedFiltersArray['exam'], $enabledExams);
			$checkedEnabledOrder = array_intersect_key($enabledOrder, array_flip($appliedFilters["exam"]));
			asort($checkedEnabledOrder);
			if($categoryPageRequest->isExamCategoryPage()){
				$examId = reset($appliedFilters["exam"]);
				$examName = $examsForFilter[$examId];
			}else{
				$examId = key($checkedEnabledOrder);
				$examName = $examsForFilter[$examId];
			}
		}
		switch($sortingCriteria['sortBy']){
			case 'viewCount':
				$selected_viewCount = 'selected';
				break;
			
			case 'fees':
				if($sortingCriteria['order'] == 'ASC') {
					$selected_fees_asc = 'selected';
				} else {
					$selected_fees_desc = 'selected';
				}
				break;
			
			case 'exam':
				if($sortingCriteria['order'] == 'ASC') {
					$selected_exam_asc = 'selected';
				} else {
					$selected_exam_desc = 'selected';
				}
				break;
				
			default:
				$selected_featured = 'selected';
		}
		?>
		<label>Sort By:</label>
		<select class="sort-select" id="categorySorter" onchange="setGATrackingForStudyAbroad('sortBy'); sortUniversities();">
			<option <?=$selected_featured?> value="none">Sponsored</option>
			<option <?=$selected_viewCount?> value="viewCount_DESC">Popularity</option>
			<option <?=$selected_fees_asc?> value="fees_ASC">Low to high 1st year total fees</option>
			<option <?=$selected_fees_desc?> value="fees_DESC">High to low 1st year total fees</option>
			<?php
				if(in_array($sortingCriteria['exam'], $enabledExams) ){ ?>
				<option <?php echo $selected_exam_asc; ?> value="exam_ASC_<?php echo $sortingCriteria['exam']; ?>">Low to high <?php echo $sortingCriteria['exam']; ?> exam score</option>
				<option <?php echo $selected_exam_desc; ?> value="exam_DESC_<?php echo $sortingCriteria['exam']; ?>">High to low <?php echo $sortingCriteria['exam']; ?> exam score</option>
			<?php } 
				else if(in_array($examName, $enabledExams) || $categoryPageRequest->isExamCategoryPage()) { ?>
				<option <?php echo $selected_exam_asc; ?> value="exam_ASC_<?php echo $examName; ?>">Low to high <?php echo $examName; ?> exam score</option>
				<option <?php echo $selected_exam_desc; ?> value="exam_DESC_<?php echo $examName; ?>">High to low <?php echo $examName; ?> exam score</option>
			<?php } ?>
		</select>
	<?php } ?>
</div>