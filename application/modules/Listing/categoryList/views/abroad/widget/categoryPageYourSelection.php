<?php
$display = "";
$isZeroResultCombination = $noOfUniversities == 0? 1 : 0;
/*
 * Hide the your selection section if no filter is selected
*/
if(empty($appliedFilters) ||
   //exam page case
   	( 
    	($isZeroResultCombination ==1 && $acceptedExamName != '' && count($appliedFilters['exam'])>0 && count($appliedFilters) == 1) // exam page + zero results + no other filter applied
    	||
    	($snapshotOnlyPostFilteration == 1 && count($appliedFilters) == 1) // only snapshot case visible + certain filter applied(via url params) that is not among applicable filters
    ) ||
    
    (
    		count($appliedFilters) == 1 										// only 1 filter type has been applied
    		&& count($appliedFilters['moreoption'] == 1) 						// and only 1 of filter type moreoption has been applied
    		&& reset($appliedFilters['moreoption']) == "DEGREE_COURSE"			// and that filter type is the exclude filter
    		&& (
				$moreOptionsForFilters['DEGREE_COURSE'] == ""					// and the exclude filter is actually not present in more options
			|| $categoryPageRequest->checkIfCertDiplomaCountryCatPage())			// OR we dont keep it selected if the countries are canada or NZ
	)
  )
{
	$display = "none";
}
$appliedFilters['moreoption'] = array_unique($appliedFilters['moreoption']);
?>
<div id="yourSelection" class="selection-criteria clearwidth" style="display:<?=$display?>">
	<div class="selection-list">
		<label><strong>Your Selection:</strong></label>
		<div class="selected-items">
		<?php
		/*
		 * Show all selected filters 
		*/
		foreach($appliedFilters["fees"] as $feesId)
		{
			if( array_key_exists($feesId, $userAppliedFeeRangesForFilter) || $isZeroResultCombination )
				echo "<p>".$feeRangesForFilter[$feesId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('fee','".$feesId."')\" referername=\"fee\" refererValue=\"".$feesId."\">&times;</a></p>";
			else
				echo "<p class='disabled-item'>".$feeRangesForFilter[$feesId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"fee\" refererValue=\"".$feesId."\" >&times;</a></p>";
		}
		if($categoryPageRequest->isExamCategoryPage()){
			$examId = reset($appliedFilters["exam"]);
			$examName = $examsForFilter[$examId];
			$maxExamScore = explode("--",reset($appliedFilters["examsScore"]));
			$maxExamScore = $maxExamScore[1];
			$minExamScore = explode("--",reset($appliedFilters["examsMinScore"]));
			$minExamScore = $minExamScore[1];
			if(!$this->input->is_ajax_request() && $categoryPageRequest->isExamCategoryPageWithScore){
				$maxExamScore = $categoryPageRequest->examScore[1];
				$minExamScore = $categoryPageRequest->examScore[0];
			}
			$examRange = '';
			if(!empty($minExamScore)){$examRange.='<span id="cpysmes">'.$minExamScore."</span>";}else{ if(!empty($maxExamScore)){$examRange.= "<span id='cpysas'></span>upto ";}}
			if(!empty($minExamScore) && !empty($maxExamScore)){$examRange.=" - ";}
			if(!empty($maxExamScore)){$examRange.='<span id="cpyses">'.$maxExamScore.'</span>';}else{if(!empty($minExamScore)){$examRange.=" <span id='cpysas'></span>and above";}}
			if(empty($examRange)){$examRange = "<span id='cpysas'></span>All scores";}
			echo "<p class='disabled-item'>".$examName." (".$examRange.") <a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"exam\" refererValue=\"".$examId."\" >&times;</a></p>";
		}else{
			$examsScoreValue = array();
			if(!empty($appliedFilters["examsScore"])){
				foreach($appliedFilters["examsScore"] as $key=>$val){
					$val = explode('--',$val);
					//if(($naKey = array_search($val[1], $userAppliedExamsScoreForFilters[$val[2]])) !== false){
					$examsScoreValue[$val[0]] = " (".$val[1]." and below) ";
					//}
				}
			}

			foreach($appliedFilters["exam"] as $examId)
			{
				if(
				   (array_key_exists($examId, $userAppliedExamsForFilters) || $isZeroResultCombination) &&
				   !$categoryPageRequest->isExamCategoryPage()
				   )
					echo "<p>".$examsForFilter[$examId].$examsScoreValue[$examsForFilter[$examId]]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('exam',".$examId.")\" referername=\"exam\" refererValue=\"".$examId."\">&times;</a></p>";
				else
					echo "<p class='disabled-item'>".$examsForFilter[$examId].$examsScoreValue[$examsForFilter[$examId]]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"exam\" refererValue=\"".$examId."\" >&times;</a></p>";
			}
		}
		foreach($appliedFilters["specialization"] as $specializationId)
		{
			if( array_key_exists($specializationId, $userAppliedSpecializationForFilters) || $isZeroResultCombination )
				echo "<p>".$specializationForFilters[$specializationId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('course',".$specializationId.")\" referername=\"course\" refererValue=\"".$specializationId."\">&times;</a></p>";
			else
				echo "<p class='disabled-item'>".$specializationForFilters[$specializationId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"course\" refererValue=\"".$specializationId."\" >&times;</a></p>";
		}
			
		foreach($appliedFilters["moreoption"] as $moreoptionId)
		{
			if( array_key_exists($moreoptionId, $userAppliedMoreOptionsForFilters) || $isZeroResultCombination ){
				if(!empty($moreOptionsForFilters[$moreoptionId])){
					echo "<p>".$moreOptionsForFilters[$moreoptionId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('moreopt','".$moreoptionId."')\" referername=\"moreopt\" refererValue=\"".$moreoptionId."\">&times;</a></p>";
				}
			}
			else{
				if(!empty($moreOptionsForFilters[$moreoptionId])){
					echo "<p class='disabled-item'>".$moreOptionsForFilters[$moreoptionId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"moreopt\" refererValue=\"".$moreoptionId."\">&times;</a></p>";
				}
			}
		}
			
		foreach($appliedFilters["country"] as $countryId)
		{
			if( array_key_exists($countryId, $userAppliedCountryForFilters) || $isZeroResultCombination )
				echo "<p>".$countriesForFilters[$countryId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('countryList','".$countryId."')\" referername=\"countryList\" refererValue=\"".$countryId."\">&times;</a></p>";
			else
				echo "<p class='disabled-item'>".$countriesForFilters[$countryId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"countryList\" refererValue=\"".$countryId."\">&times;</a></p>";
		}
			
		foreach($appliedFilters["state"] as $stateId)
		{
			if( array_key_exists($stateId, $userAppliedStateForFilters) || $isZeroResultCombination )
				echo "<p>".$statesForFilters[$stateId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('stateList','".$stateId."')\" referername=\"stateList\" refererValue=\"".$stateId."\">&times;</a></p>";
			else
				echo "<p class='disabled-item'>".$statesForFilters[$stateId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"stateList\" refererValue=\"".$stateId."\">&times;</a></p>";
		}
		
		foreach($appliedFilters["city"] as $cityId)
		{
			if( array_key_exists($cityId, $userAppliedCitiesForFilters) || $isZeroResultCombination )
				echo "<p>".$citiesForFilters[$cityId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('cityList','".$cityId."')\" referername=\"cityList\" refererValue=\"".$cityId."\">&times;</a></p>";
			else
				echo "<p class='disabled-item'>".$citiesForFilters[$cityId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"cityList\" refererValue=\"".$cityId."\">&times;</a></p>";
		}
		
		?>
		</div>
	</div>
	<div class="flRt" id="changeFilters" style="display: none"><a href="javascript:void(0);" onclick="slowScrollToTop('filterBox', 50);">Change filters</a></div>
</div>
