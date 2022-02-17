<ul>
	<?php 
	global $educationTypePriorities;
	$mode = $fields['educationType']->getValues(array('baseCoursesIds'=>$baseCourses));
	foreach ($mode as $key => $value) {
		$tmpModeArr[$key] = $value['name'];
		if(!empty($value['children'])){
			foreach ($value['children'] as $id => $name) {
				$tmpModeArr[$id] = $name;
			}
		}
	}

	$eduType = array_intersect(array_keys($educationTypePriorities), array_keys($tmpModeArr));

	foreach ($eduType as $key => $value) { ?>
		<div class="subStrmGrp">
			<input type="checkbox" class="pLevel et_<?php echo $regFormId; ?>" id="mode_<?php echo $value; ?>_<?php echo $regFormId; ?>" name="educationType[]" value="<?php echo $value; ?>" norest = 'yes' label="<?php echo $educationTypePriorities[$value]; ?>">
		</div>
	<?php } ?>
	<!-- <?php //foreach($mode as $key=>$modeValue){ ?>
		<div class="subStrmGrp">
			<input type="checkbox" class="pLevel et_<?php //echo $regFormId; ?>" id="mode_<?php //echo $key; ?>_<?php //echo $regFormId; ?>" name="educationType[]" value="<?php //echo $key; ?>" norest = 'yes' label="<?php //echo $modeValue['name']; ?>">
			<?php //if(!empty($modeValue['children'])){ ?>
				<div class="child">
					<?php //foreach ($modeValue['children'] as $keyId => $value) { ?>
						<input type="checkbox" class="cLevel et_<?php //echo $regFormId; ?>" id="mode_<?php //echo $keyId; ?>" value="<?php //echo $keyId; ?>" name="educationType[]" parentId="mode_<?php //echo $key; ?>_<?php //echo $regFormId; ?>" norest = 'yes' label="<?php //echo $value; ?>">
					<?php //} ?>
				</div>
			<?php //} ?>
		</div>
	<?php //} ?>
 -->