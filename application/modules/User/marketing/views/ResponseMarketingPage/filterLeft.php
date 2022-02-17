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
<?php if(count($durationFilterValues) > 1){?>
	<div style="float:left;">
		<h6>Duration</h6>
	</div>
	<?php if(count($durationTypes)> 1){?>
		<div style="float:right; margin-right:10px;">
			<a href="#" class="showMoreLink" id="moreLink" onclick="moreDurations();return false;">+ More</a>
			<a href="#" class="showMoreLink" id="lessLink" onclick="lessDurations();return false;" style="display:none;">- Less</a>
		</div>
	<?php } ?>
	<div class="clearFix"></div>
	<ul>	
	<?php
	foreach($durationFilterValues as $filter){
		$checked = '';
		if($appliedFilters == false){
			//$checked = "checked";	
		}
		elseif(in_array($filter,$appliedFilters['duration'])){
			$checked = "checked";
		}
	?>
		<li><input type="checkbox" <?=$checked?> name="duration[]" value = "<?=$filter?>" /> <?=$filter?></li>
	<?php } ?>
	</ul>

    <?php if(count($durationTypes)> 1){
?>
<div id="moredurations" style="margin-bottom: 5px; display:<?php if($appliedFilters['durationRange']) echo 'block'; else echo 'none';  ?>">
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
}
?>
<?php if(count($examFilterValues) > 1){ ?>
	<h6>Exams Accepted</h6>
	<ul>
	<?php foreach($examFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['exams'])){
						$checked = "checked";
			}
			?>
			<li><input type="checkbox" <?=$checked?> name="exams[]" value = "<?=$filter?>"/> <?=$filter?></li>
	<?php } ?>
	</ul>
<?php 
}
?>

<?php if(count($AIMARatingFilterValues) > 1){ ?>
	<h6>AIMA Rating</h6>
	<ul>
	<?php foreach($AIMARatingFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['AIMARating'])){
						$checked = "checked";
			}
			?>
		<li><input type="checkbox" <?=$checked?> name="AIMARating[]" value = "<?=$filter?>"/> <?=$filter?></li>
	<?php } ?>
	</ul>
<?php 
}
?>

<?php if(count($modeFilterValues) > 1){ ?>
	<h6>Mode</h6>
	<ul>
	<?php foreach($modeFilterValues as $filter){
			$checked = '';
			if($appliedFilters == false){
						//$checked = "checked";	
			}
			elseif(in_array($filter,$appliedFilters['mode'])){
						$checked = "checked";
			}
			?>
		<li><input type="checkbox" <?=$checked?> name="mode[]" value = "<?=$filter?>"/> <?=$filter?></li>
	<?php } ?>
	</ul>
<?php 
}
?>