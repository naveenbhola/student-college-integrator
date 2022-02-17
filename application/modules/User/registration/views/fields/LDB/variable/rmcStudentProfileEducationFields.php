<!-- This is the 10th section only -->

<?php
$fields = $fieldObj;
if(isset($fields['UG']['tenthBoard'])){?>
	<li>
		<?php if(isset($fields['UG']['tenthBoard'])){ ?>
				<label>Class 10th board</label>
				<div class="student-form-fields" <?php echo $registrationHelper->getBlockCustomAttributes('tenthBoard'); ?>>
					<select name="tenthBoard" class="universal-select cms-field" id="tenthBoard_<?php echo $regFormId; ?>" <?php     echo $registrationHelper->getFieldCustomAttributes('tenthBoard'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].getTenthMarksAccToBoard(this.value);">
						<option value="">Select <?php echo $fields['UG']['tenthBoard']->getCaption(); ?></option>
						<?php foreach($fields['UG']['tenthBoard']->getValues() as $key => $value) { ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<div class="errorMsg" id="tenthBoard_error_<?php echo $regFormId; ?>"></div>
				</div>
		<?php } ?>
	</li>
	<li>
		<?php if(isset($fields['UG']['tenthmarks'])){ ?>
				<label>Class 10th marks</label>
				<div class="student-form-fields" <?php echo $registrationHelper->getBlockCustomAttributes('tenthmarks'); ?>>
					<select name="tenthmarks" class="universal-select cms-field" id="tenthmarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthmarks'); ?>>
						<option value="">Select 10th Marks or CGPA</option>
					</select>
				</div>
				<div>
					<div class="errorMsg" id="tenthmarks_error_<?php echo $regFormId; ?>"></div>
				</div>
		<?php } ?>
	</li>
	<li>
		<?php if(isset($fields['UG']['currentClass'])){ 
			$additionalDetails = $candidateObj->getUserAdditionalInfo();
			if(is_object($additionalDetails)){
			  $currentClass = $additionalDetails->getCurrentClass();
			}
			?>
				<label><?php echo $fields['UG']['currentClass']->getLabel(); ?></label>
				<div class="student-form-fields" <?php echo $registrationHelper->getBlockCustomAttributes('currentClass'); ?>>
					<select name="currentClass" class="universal-select cms-field" id="currentClass_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('currentClass'); ?>>
						<option value=""><?php echo $fields['UG']['currentClass']->getCaption(); ?></option>
						<?php foreach($fields['UG']['currentClass']->getValues() as $key => $value) { ?>
							<option value="<?php echo $key; ?>" <?php if($currentClass == $key){ echo 'selected="selected"';}?>><?php echo $value; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<div class="errorMsg" id="currentClass_error_<?php echo $regFormId; ?>"></div>
				</div>
		<?php } ?>
	</li>
	<li>
		<?php if(isset($fields['UG']['currentSchool'])){ 
			$additionalDetails = $candidateObj->getUserAdditionalInfo();
			if(is_object($additionalDetails)){
			  $currentSchool = htmlentities($additionalDetails->getCurrentSchool());
			}
			?>
				<label><?php echo $fields['UG']['currentSchool']->getLabel(); ?></label>
				<div class="student-form-fields" <?php echo $registrationHelper->getBlockCustomAttributes('currentSchool'); ?>>
					<input name="currentSchool" class="universal-select cms-field" id="currentSchool_<?php echo $regFormId; ?>" maxlength="100" <?php echo $registrationHelper->getFieldCustomAttributes('currentSchool'); ?> value="<?php echo $currentSchool;?>" >
				</div>
				<div>
					<div class="errorMsg" id="currentSchool_error_<?php echo $regFormId; ?>"></div>
				</div>
		<?php } ?>
	</li>
	<li>
		<?php if(isset($fields['UG']['CurrentSubjects'])){ ?>
			<?php $valuetoshow = $fields['UG']['CurrentSubjects']->getValues();?>
				<label>Class Current Subjects</label>
				<div class="student-form-fields" <?php echo $registrationHelper->getBlockCustomAttributes('CurrentSubjects'); ?>>
					<div class="custom-dropdown" style="border:none;">
						<div id="10thSubjectLayer" style="width:200px !important;color: #999;padding-left: 6px;padding-top:6px;" class="select-overlap"  onclick="showHideTenthSubjectOverlay();"></div>
						<div id="tenthSubjectOverlay" class="select-opt-layer" style="width: 280px; top: 26px ! important; display:none;">
							<div class="scrollbar1" id="">
								<div class="scrollbar" style="height: 100px;">
									<div class="track" style="height: 100px;">
										<div class="thumb" style="top: 0px; height: 45px;">
											<div class="end"></div>
										</div>
									</div>
								</div>
								<div class="viewport" style="height: 100px;">
									<div class="overview" style="top:0px;">
										<ul class="hm-refine">
											<?php foreach($valuetoshow as $key=>$val){ ?>
												<li>
													<div class="hm-refine-opt">
														<input type="checkbox" name="CurrentSubjects[]" value="<?=htmlentities($val)?>" class="subcatCheckbox flLt" id="<?=$key?>_<?php echo $regFormId; ?>">
														<label for="<?=$key?>_<?php echo $regFormId; ?>" onclick="" style="cursor:pointer;">
															<span class="common-sprite"></span>
															<?=$val?>
														</label>
													</div>
												</li>
											<?php } ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<select name="CurrentSubject" class="universal-select cms-field streamSelectClass" id="CurrentSubjects_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('CurrentSubjects'); ?>>
						<option value="">Select Subjects</option>
						<option value="1"></option>
					</select>
				</div>
				<div><div class="errorMsg" id="CurrentSubjects_error_<?php echo $regFormId; ?>"></div></div>
		<?php } ?>
	</li>
<?php } ?>
<?php if(isset($candidateData['education']['Graduation'])){
$pgMarks = $candidateData['education']['Graduation']['marks'];
$pgStream = $candidateData['education']['Graduation']['stream'];
}
$workEx2 = $candidateData['workXP'];
$workExPresent = strlen($workEx2);
if(isset($fields['PG']['graduationStream'])){ ?>
	<li>
		<?php if(isset($fields['PG']['graduationStream'])){ ?>
				<label class="flLt additional-detail-label">Graduation stream:</label>
				<div class="student-form-fields" <?php echo $registrationHelper->getBlockCustomAttributes('graduationStream'); ?>>
					<select style="width:205px;" name="graduationStream" class="universal-select signup-select" id="graduationStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStream'); ?>>
						<option value="">Select Graduation stream</option>
						<?php foreach($fields['PG']['graduationStream']->getValues() as $key => $value) { ?>
							<option value="<?php echo $key; ?>" <?= ($pgStream==$key)?'selected="selected"':''?> ><?php echo $value; ?></option>
						<?php } ?>
					</select>
				</div>
				<div><div class="errorMsg" id="graduationStream_error_<?php echo $regFormId; ?>"></div></div>
		<?php } ?>
	</li>
	<li>
		<?php if(isset($fields['PG']['graduationMarks'])){ ?>
				<label class="flLt additional-detail-label">Graduation Percentage:</label>
				<div class="student-form-fields" <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
					<select style="width:205px;" name="graduationMarks" class="universal-select signup-select" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?>>
						<option value="">select graduation Marks</option>
						<?php $valuetoshow = $fields['PG']['graduationMarks']->getValues(); ?>
						<?php foreach($valuetoshow as $key => $value) { ?>
							<option value="<?php echo $key; ?>" <?= ($pgMarks==$key)?'selected="selected"':''?>><?php echo $value; ?></option>
						<?php } ?>
					</select>
				</div>
				<div><div class="errorMsg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div></div>
		<?php } ?>
		
	</li>
	<li>
		<?php if(isset($fields['PG']['workExperience'])){ ?>
				<label class="flLt additional-detail-label">Work Experience:</label>
				<div class="student-form-fields" <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
					<select style="width:205px;" name="workExperience" class="universal-select signup-select" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?>>
						<option value="">Select work Experience</option>
						<?php foreach($fields['PG']['workExperience']->getSAValues() as $key => $value) { ?>
							<option value="<?php echo $key; ?>" <?= ($workExPresent >0 && $workEx2==$key)?'selected="selected"':''?>><?php echo $value; ?></option>
						<?php } ?>
					</select>
				</div>
				<div><div class="errorMsg" id="workExperience_error_<?php echo $regFormId; ?>"></div></div>
		<?php } ?>
		
	</li>
<?php } ?>

<?php if(isset($fields['UG']['tenthBoard'])){?>
<script>
	function initializeTenthSubject(str){
		$j("#tenthSubjectOverlay").toggle();
		$j('.scrollbar1').tinyscrollbar();
		$j("#tenthSubjectOverlay").toggle();

		$j(document).mouseup(function (e)
        {
            var container = $j("#tenthSubjectOverlay");
            var containerDD = $j("#CurrentSubjects_block_"+formId);

            if (!container.is(e.target) && !containerDD.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0 && containerDD.has(e.target).length === 0) // ... nor a descendant of the container
            {
                if(container.css('display') === 'block')
                    showHideTenthSubjectOverlay(str)
            }
        });
	}
	
	function showHideTenthSubjectOverlay(){
		$j('#tenthSubjectOverlay').toggle();
		if ($j('#tenthSubjectOverlay').is(":hidden")) {
			shikshaUserRegistrationForm[FormIdBottom].validateTenthStream(callbackForTenthSubjects);
		}
	}
	
	function callbackForTenthSubjects(){
		$j('#CurrentSubjects_'+FormIdBottom).trigger('blur');
	}
	var choices = ['<?php echo implode("','", $fields['UG']['currentSchool']->getValues());?>'];
</script>
<?php } ?>
