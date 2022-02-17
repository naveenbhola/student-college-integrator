<ul>
	<?php 
	$mode = $fields['educationType']->getValues(array('baseCoursesIds'=>$baseCourses));
	Global $educationTypePriorities;
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

			<li>
			<div class="Customcheckbox nav-checkBx cP">
				<input type="checkbox" class="pLevel et_<?php echo $regFormId; ?>" id="mode_<?php echo $value; ?>_<?php echo $regFormId; ?>" name="educationType[]" value="<?php echo $value; ?>" norest = 'yes'>
				<label for="mode_<?php echo $value; ?>_<?php echo $regFormId; ?>"><?php echo $educationTypePriorities[$value]; ?></label>
			</div>
		</li>
	<?php	} ?>
	<!-- <?php //foreach($mode as $key=>$modeValue){ ?>
		<li>
			<div class="Customcheckbox nav-checkBx cP">
				<input type="checkbox" class="pLevel et_<?php //echo $regFormId; ?>" id="mode_<?php //echo $key; ?>_<?php //echo $regFormId; ?>" name="educationType[]" value="<?php //echo $key; ?>" norest = 'yes'>
				<label for="mode_<?php// echo $key; ?>_<?php //echo $regFormId; ?>"><?php //echo $modeValue['name']; ?></label>
			</div>
			<?php //if(!empty($modeValue['children'])){ ?>
				<div class="nav-cont">
					<ul class="lyr-sblst2">
						<?php //foreach ($modeValue['children'] as $keyId => $value) { ?>
							<li>
								<div class="Customcheckbox nav-checkBx">
								<input type="checkbox" class="cLevel et_<?php //echo $regFormId; ?>" id="mode_<?php //echo $keyId; ?>" value="<?php //echo $keyId; ?>" name="educationType[]" parentId="mode_<?php //echo $key; ?>_<?php //echo $regFormId; ?>" norest = 'yes'>
									<label for="mode_<?php //echo $keyId; ?>"><?php //echo $value; ?></label>
								</div>
							</li>
							<?php //} ?>
						</ul>
					</div>
					<?php //} ?>

				</li>
				<?php //} ?> -->
			</ul>