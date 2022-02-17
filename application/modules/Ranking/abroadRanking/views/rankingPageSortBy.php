<div class="sort-section flRt" id="sortSection">
	<?php if($noOfCourses != 0) {
		$enabledExams = $userAppliedfilters['exams']->getFilteredValues();
		if(empty($appliedFilters["exam"])) {
			$examName = reset($examsForFilter);
		} else {
			$selectedFiltersArray = json_decode($filterSelectionOrder, true);
			$enabledOrder = array_intersect_key($selectedFiltersArray['exam'], $enabledExams);
			$checkedEnabledOrder = array_intersect_key($enabledOrder, array_flip($appliedFilters["exam"]));
			asort($checkedEnabledOrder);
			$examId = key($checkedEnabledOrder);
			$examName = $examsForFilter[$examId];
		}
		switch($sortingCriteria['sortBy']){
			case 'rank':
				$selected_rank = 'selected';
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
		<select class="sort-select" id="rankingSorter" onchange="/*setGATrackingForStudyAbroad('sortBy');*/ sortCourses();">
			<option <?=$selected_featured?> value="rank_ASC">Rank</option>
			<option <?=$selected_fees_asc?> value="fees_ASC">Low to high 1st year total fees</option>
			<option <?=$selected_fees_desc?> value="fees_DESC">High to low 1st year total fees</option>
			<?php
			if(in_array($examName, $enabledExams))
			{
				//This Check is mainly for CAE exam for which values are alphabet so sorting will be reverse
				if($rankingLibObj->checkExamValuesAlphabet(array_search($examName, $enabledExams))){
				?>
				<option <?=$selected_exam_asc?> value="exam_DESC_<?=$examName?>">Low to high <?=$examName?> exam score</option>
				<option <?=$selected_exam_desc?> value="exam_ASC_<?=$examName?>">High to low <?=$examName?> exam score</option>
			        <?php }else{ ?>
				<option <?=$selected_exam_asc?> value="exam_ASC_<?=$examName?>">Low to high <?=$examName?> exam score</option>
				<option <?=$selected_exam_desc?> value="exam_DESC_<?=$examName?>">High to low <?=$examName?> exam score</option>
			        <?php	}
			} ?>
		</select>
	<?php } ?>
</div>