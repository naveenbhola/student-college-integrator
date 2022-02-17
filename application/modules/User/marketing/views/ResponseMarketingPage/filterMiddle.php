<?php
global $filters;
global $appliedFilters;
$durationFilterValues = $filters['duration'] ? $filters['duration']->getFilteredValues() : array();
$durationTypes = $filters['duration'] ? $filters['duration']->getDurationTypes() : array();
$examFilterValues = $filters['exams'] ? $filters['exams']->getFilteredValues() : array();
$modeFilterValues = $filters['mode'] ? $filters['mode']->getFilteredValues() : array();
$courseLevelFilterValues = $filters['courseLevel'] ? $filters['courseLevel']->getFilteredValues() : array();
$AIMARatingFilterValues = $filters['AIMARating'] ? $filters['AIMARating']->getFilteredValues() : array();
$classTimingFilterValues = $filters['classTimings'] ? $filters['classTimings']->getFilteredValues() : array();
$degreePrefFilterValues = $filters['degreePref'] ? $filters['degreePref']->getFilteredValues() : array();
?>

<?php if(count($courseLevelFilterValues) > 1){ ?>
	<h6>Course Level</h6>
	<ul>
	<?php foreach($courseLevelFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['courseLevel'])){
						$checked = "checked";
			}
			?>
	<li><input type="checkbox" <?=$checked?> name="courseLevel[]" value = "<?=$filter?>"/> <?=$filter?></li>
	<?php } ?>
</ul>
<?php 
}
?>

<?php if(count($degreePrefFilterValues) > 1){ ?>
	<h6>Degree Preference</h6>
	<ul>
	<?php foreach($degreePrefFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['degreePref'])){
						$checked = "checked";
			}
			?>
	<li><input type="checkbox" <?=$checked?> name="degreePref[]" value = "<?=$filter?>"/> <?=langStr('approval_'.$filter)?></li>
	<?php } ?>
	</ul>
<?php 
}
?>
<?php if(count($classTimingFilterValues) > 1){ ?>
	<h6>Class Timings</h6>
	<ul>
	<?php foreach($classTimingFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['classTimings'])){
						$checked = "checked";
			}
			?>
	<li><input type="checkbox" <?=$checked?> name="classTimings[]" value = "<?=$filter?>"/> <?=langStr($filter)?></li>
	<?php } ?>
</ul>
<?php 
}
?>
</ul>