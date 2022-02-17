<div class="ih">
	<?php $unMappedSpec = $mappedHierarchies['hierarchies'][0]; unset($mappedHierarchies['hierarchies'][0]); ?>
	<input type="hidden" value="<?php echo $mappedHierarchies['stream']; ?>" name="stream" id="stream_<?php echo $regFormId; ?>" regformid="<?php echo $regFormId; ?>" />

	<?php foreach($mappedHierarchies['hierarchies'] as $substream => $specializations){ ?>
	<div class="ssGroup">
		<input type="checkbox" value="<?php echo $substream; ?>" name="subStream[]" id="subStream_<?php echo $substream.'_'.$regFormId; ?>" class="pLevel" checked="checked"/>
		<div class="childGrp">
			<?php foreach($specializations as $key => $specId){ ?>
			<input type="checkbox" value="<?php echo $specId; ?>" id="spec_<?php echo $specId.'_'.$regFormId; ?>" class="cLevel" name="specializations[]" checked="checked" parentId="subStream_<?php echo $substream.'_'.$regFormId; ?>"/>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<div class="unmppedSpec">
		<?php foreach($unMappedSpec as $key => $specId){ ?>
		<input type="checkbox" value="<?php echo $specId; ?>" id="spec_unmp_<?php echo $specId.'_'.$regFormId; ?>" class="unmp cLevel" name="specializations[]" checked="checked" />
		<?php } ?>
	</div>
</div>

<div id="baseCoursesValues">
<?php foreach ($baseCourse as $id) { ?>
	<input type="hidden" name="baseCourses[]" value="<?php echo $id; ?>" id="baseCourse_<?php echo $id; ?>" checked="checked">
<?php } ?>
</div>


<?php foreach ($mode as $id) { ?>
	<input type="hidden" id="mode_<?php echo $id; ?>" value="<?php echo $id; ?>" name="educationType[]" checked="checked">
<?php } ?>

<input type="hidden" name="listing_type" value="exam" id="listing_type_<?php echo $regFormId; ?>">

<!-- Level Field -->
<?php if(count($level) > 1){ 
		global $examLevelPriorities;
		$levels = array_intersect($examLevelPriorities, $level);
	?>
	<div style="position:relative;">
		<div class="reg-form signup-fld invalid ssLayer" layerFor="level" regFormId="<?php echo $regFormId; ?>" regfieldid="level" id="level_block_<?php echo $regFormId; ?>" type="layer" label="Level">
			<div class="ngPlaceholder">Level of study you're interested in</div>
			<div class="multiinput" id="level_input_<?php echo $regFormId; ?>"> </div>
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text">Please Enter Level.</div>
			</div>
		</div>

		<div class="cusLayer ctmScroll layerHtml ih">

		</div>
		
		<div class="ih sValue">
			<select id="level_<?php echo $regFormId; ?>" class="lvFld" regformid="<?php echo $regFormId; ?>" name="level" mandatory="1" caption="Level">
					<option value="-1">Level</option>
				<?php foreach($levels as $id => $name){ 
						if($name == 'None') $name = 'Certificate'; ?>
						<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
<?php } else { 
	$key = array_keys($level);
?>
	<input type="hidden" value="<?php echo $key[0]; ?>" class="lvFld" regformid="<?php echo $regFormId; ?>" name="level" id="level_<?php echo $regFormId; ?>" />
<?php } ?>
