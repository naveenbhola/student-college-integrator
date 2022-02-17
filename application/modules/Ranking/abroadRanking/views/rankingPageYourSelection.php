<?php
$display = "";
$isZeroResultCombination = $noOfCourses == 0? 1 : 0;
/*
 * Hide the your selection section if no filter is selected
*/
if(empty($appliedFilters))
	$display = "none";
?>
<div id="yourSelection" class="selection-criteria clearwidth" style="display:<?=$display?>">
	<div class="selection-list">
		<label><strong>Your Selection:</strong></label>
		<div class="selected-items">
		<?php
		
		/* Show all selected filters 
		*/
		
		foreach($appliedFilters["fees"] as $feesId)
		{
			if( array_key_exists($feesId, $userAppliedFeeRangesForFilter) || $isZeroResultCombination )
				echo "<p>".$feeRangesForFilter[$feesId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('fee','".$feesId."')\" referername=\"fee\" refererValue=\"".$feesId."\">&times;</a></p>";
			else
				echo "<p class='disabled-item'>".$feeRangesForFilter[$feesId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"fee\" refererValue=\"".$feesId."\" >&times;</a></p>";
		}
	
		foreach($appliedFilters["exam"] as $examId)
		{
			if( array_key_exists($examId, $userAppliedExamsForFilters) || $isZeroResultCombination )
				echo "<p>".$examsForFilter[$examId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('exam',".$examId.")\" referername=\"exam\" refererValue=\"".$examId."\">&times;</a></p>";
			else
				echo "<p class='disabled-item'>".$examsForFilter[$examId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"exam\" refererValue=\"".$examId."\" >&times;</a></p>";
		}
		
		foreach($appliedFilters["specialization"] as $specializationId)
		{
			if( array_key_exists($specializationId, $userAppliedSpecializationForFilters) || $isZeroResultCombination )
				echo "<p>".$specializationForFilters[$specializationId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"removeYourSelection('course',".$specializationId.")\" referername=\"course\" refererValue=\"".$specializationId."\">&times;</a></p>";
			else
				echo "<p class='disabled-item'>".$specializationForFilters[$specializationId]."<a href=\"javascript:void(0);\" class=\"cross-icon\" onclick=\"return false;\" referername=\"course\" refererValue=\"".$specializationId."\" >&times;</a></p>";
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