<?php
global $filters;
global $appliedFilters;
$durationFilterValues = $filters['duration'] ? $filters['duration']->getFilteredValues() : array();
$durationTypes = $filters['duration'] ? $filters['duration']->getDurationTypes() : array();
$examFilterValues = $filters['exams'] ? $filters['exams']->getFilteredValues() : array();
$modeFilterValues = $filters['mode'] ? $filters['mode']->getFilteredValues() : array();
$courseLevelFilterValues = $filters['courseLevel'] ? $filters['courseLevel']->getFilteredValues() : array();
// call to check for pg and ug courses
if($request->isStudyAbroadPage()) {
	modify_courselevel_filter_values($courseLevelFilterValues);
}
$AIMARatingFilterValues = $filters['AIMARating'] ? $filters['AIMARating']->getFilteredValues() : array();
$classTimingFilterValues = $filters['classTimings'] ? $filters['classTimings']->getFilteredValues() : array();
$degreePrefFilterValues = $filters['degreePref'] ? $filters['degreePref']->getFilteredValues() : array();
$filterCount = 0;
if(count($durationFilterValues) > 1){
		$filterCount += 1;	
}
if(count($examFilterValues) > 1){
		$filterCount += 1;	
}
if(count($modeFilterValues) > 1){
		$filterCount += 1;	
}
if(count($courseLevelFilterValues) > 1){
		$filterCount += 1;	
}
if(count($AIMARatingFilterValues) > 1){
		$filterCount += 1;	
}
if(count($classTimingFilterValues) > 1){
		$filterCount += 1;	
}
if(count($degreePrefFilterValues) > 1){
		$filterCount += 1;	
}
$filterDisplayed = 0;
?>
<ul>
<?php if(count($durationFilterValues) > 1){?>
<li>
	<strong>Duration</strong>
	<?php if(count($durationTypes)> 1){?>
	<a href="#" class="showMoreLink" id="moreLink" onclick="moreDurations();return false;">+ More</a>
	<a href="#" class="showMoreLink" id="lessLink" onclick="lessDurations();return false;" style="display:none;">- Less</a>
	<?php } ?>
</li>
<li class="durationRow">
	<?php foreach($durationFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
				//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['duration'])){
				$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="duration[]" value = "<?=$filter?>" /> <?=$filter?></label></span>
	<?php } ?>
	<div class="clearFix"></div>
    <?php if(count($durationTypes)> 1){
?>
<div id="moredurations" style="display:<?php if($appliedFilters['durationRange']) echo 'block'; else echo 'none';  ?>">
	<div class="clearFix spacer5"></div>
	<select id="durationtype" onchange="changeDuration();">
		<option value="">Select</option>
		<?php
		foreach($durationTypes as $key=>$filter){
		?>
			<option value="<?=$key?>" <?php if($appliedFilters['durationRange'] && $appliedFilters['durationRange']['type'] == $key) echo "selected='selected'";?>><?=$key?></option>
		<?php
		}
		?>
	</select>
	&nbsp;
	<span id="durationvaluewrapper" style="display:inline; margin:0; width:auto">
	<select id="durationvalue">
		<option value="0">Select</option>
		<?php
		if($appliedFilters['durationRange']) {
			$durationRangeFilteredValues = $durationTypes;
			
			foreach($durationRangeFilteredValues[$appliedFilters['durationRange']['type']] as $fkey => $fvalue) {
				echo "<option value='".$fkey."' ".($appliedFilters['durationRange']['range'] == $fkey?"selected='selected'":"").">".$fvalue."</option>";
			}
		}
		?>
	</select>
	</span>
	
	<script>
		//var durationTypes = <?=json_encode($filters['duration']->getDurationTypes())?>;
		var durationTypes = {};
		<?php
		foreach($durationTypes as $durationType => $durationTypeValues) {
		?>
			var currentDurationTypeValues = new Array();
		<?php
			foreach($durationTypeValues as $durationValueKey => $durationValue) {
		?>		
				currentDurationTypeValues.push({"<?php echo $durationValueKey; ?>":"<?php echo $durationValue; ?>"});
		<?php
			}
		?>
			durationTypes["<?php echo $durationType; ?>"] = currentDurationTypeValues;
		<?php
		}
		if($appliedFilters['durationRange']) {
		?>
			moreDurations();
		<?php
		}
		?>
	</script>
	
</div>
<?php } ?>
</li>

<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul><ul>';
}
}
?>
<?php if(count($examFilterValues) > 1){ ?>
<li>
	<strong>Exams Accepted</strong>
</li>
<li class="durationRow">
	<?php foreach($examFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['exams'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="exams[]" value = "<?=$filter?>"/> <?=$filter?></label></span>
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul><ul>';
}
}
?>
<?php if(count($AIMARatingFilterValues) > 1){ ?>
<li>
	<div class="clearFix"></div>
	<strong>AIMA Rating</strong>
</li>
<li class="durationRow">
	<?php foreach($AIMARatingFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['AIMARating'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="AIMARating[]" value = "<?=$filter?>"/> <?=$filter?></label></span>
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul><ul>';
}
}
?>
<?php if(count($modeFilterValues) > 1){ ?>
<li>
	<strong>Mode</strong>
</li>
<li class="durationRow">
	<?php foreach($modeFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['mode'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="mode[]" value = "<?=$filter?>"/> <?=$filter?></label></span>
	<?php } ?>
    <div class="clearFix"></div>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul><ul>';
}
}
?>
<?php if(count($courseLevelFilterValues) > 1){ ?>
<li>
	<strong>Course Level</strong>
</li>
<li class="durationRow">
	<?php foreach($courseLevelFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['courseLevel'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="courseLevel[]" value = "<?=$filter?>"/> <?=$filter?></label></span>
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul><ul>';
}
}
?>

<?php if(count($degreePrefFilterValues) > 1){ ?>
<li>
	<div class="clearFix"></div>
	<strong>Degree Preference</strong>
</li>
<li class="durationRow">
	<?php foreach($degreePrefFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['degreePref'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="degreePref[]" value = "<?=$filter?>"/> <?=langStr('approval_'.$filter)?></label></span>
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul><ul>';
}
}
?>
<?php if(count($classTimingFilterValues) > 1){ ?>
<li>
	<div class="clearFix"></div>
	<strong>Class Timings</strong>
</li>
<li class="durationRow">
	<?php foreach($classTimingFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['classTimings'])){
						$checked = "checked";
			}
			?>
	<span><label><input type="checkbox" <?=$checked?> name="classTimings[]" value = "<?=$filter?>"/> <?=langStr($filter)?></label></span>
	<?php } ?>
</li>
<?php 
$filterDisplayed++;
if($filterDisplayed >= ceil($filterCount/2)){
			echo '</ul><ul>';
}
}
?>
</ul>
<?php
function modify_courselevel_filter_values(&$courseLevelFilterValues) {
	$ug_pg_phd_page_identifier = $_COOKIE['ug-pg-phd-catpage'];
	global $COURSELEVEL_TOBEHIDDEN_CONFIG;

	if(empty($ug_pg_phd_page_identifier)) {
		return true;
	}
	foreach($courseLevelFilterValues as $key =>$value) {
		if(!in_array($key,$COURSELEVEL_TOBEHIDDEN_CONFIG[$ug_pg_phd_page_identifier])) {
			unset($courseLevelFilterValues[$key]);
		}

	}
}
?>
