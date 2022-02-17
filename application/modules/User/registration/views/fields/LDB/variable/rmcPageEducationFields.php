<?php $fields = $fieldObj; ?>
<li>
	<div class="preferred-title">
		C. Education details
	</div>
	<div class="form-seprator"></div>
</li>
<!-- This is the 10th section only -->
<?php if(isset($fields['UG']['tenthBoard'])){ ?>
	<script>var rmcTenthSection = true;</script>
	<li>
		<?php if(isset($fields['UG']['tenthBoard'])){ ?>
			<div class="account-setting-fields flLt">
				<label>Class 10th board</label>
				<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('tenthBoard'); ?>>
					<select name="tenthBoard" class="universal-select signup-select" id="tenthBoard_<?php echo $regFormId; ?>" <?php     echo $registrationHelper->getFieldCustomAttributes('tenthBoard'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].getTenthMarksAccToBoard(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
						<option value="">Select <?php echo $fields['UG']['tenthBoard']->getCaption(); ?></option>
						<?php foreach($fields['UG']['tenthBoard']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
							<option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($tenthBoard) && ($tenthBoard == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<div class="errorMsg" id="tenthBoard_error_<?php echo $regFormId; ?>"></div>
				</div>
			</div>
		<?php } ?>
		<?php if(isset($fields['UG']['tenthmarks'])){ ?>
			<div class="account-setting-fields flRt">
				<label><?php echo $fields['UG']['tenthmarks']->getLabel(); ?></label>
				<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('tenthmarks'); ?>>
					<select name="tenthmarks" class="universal-select signup-select" id="tenthmarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthmarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
						<option value="">Select <?php echo $fields['UG']['tenthmarks']->getCaption(); ?></option>
						<?php $valuetoshow = $fields['UG']['tenthmarks']->getValues(); ?>
						<?php foreach($valuetoshow['CBSE'] as $plannedToGoValue => $plannedToGoText) { ?>
							<option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($tenthBoard) && ($tenthBoard == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<div class="errorMsg" id="tenthmarks_error_<?php echo $regFormId; ?>"></div>
				</div>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
	</li>
	<li>
		<?php if(isset($fields['UG']['currentClass'])){ ?>
			<div class="account-setting-fields flLt">
				<label><?php echo $fields['UG']['currentClass']->getLabel(); // fieldSettings/master ?></label>
				<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('currentClass'); ?>>
					<select name="currentClass" class="universal-select signup-select" id="currentClass_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('currentClass'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
						<option value=""><?php echo $fields['UG']['currentClass']->getCaption(); // fieldSettings/master ?></option>
						<?php $valuetoshow = $fields['UG']['currentClass']->getValues(); ?>
						<?php foreach($valuetoshow as $plannedToGoValue => $plannedToGoText) { ?>
							<option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($tenthBoard) && ($tenthBoard == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<div class="errorMsg" id="currentClass_error_<?php echo $regFormId; ?>"></div>
				</div>
			</div>
		<?php } ?>
		<?php if(isset($fields['UG']['currentSchool'])){ ?>
			<div class="account-setting-fields flRt">
				<label><?php echo $fields['UG']['currentSchool']->getLabel(); // fieldSettings/master ?></label>
				<div <?php echo $registrationHelper->getBlockCustomAttributes('currentSchool'); ?>>
					<input type="text" name="currentSchool" id="currentSchool_<?php echo $regFormId; ?>" class="universal-text signup-txtfield <?=($disabledClass)?>" maxlength="100" default="<?php echo $fields['UG']['currentSchool']->getLabel(); ?>" value="<?php echo $fields['UG']['currentSchool']->getLabel(); ?>" <?php echo $registrationHelper->getFieldCustomAttributes('currentSchool'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) && trim(this.value) !='' ? '' : shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this)" style= "color:#999999;"/>
				</div>
				<div>
					<div class="errorMsg" id="currentSchool_error_<?php echo $regFormId; ?>"></div>
				</div>
			</div>
		<?php } ?>
		</li>
		<li>
		<?php if(isset($fields['UG']['CurrentSubjects'])){ ?>
			<?php $valuetoshow = $fields['UG']['CurrentSubjects']->getValues();?>
			<div class="account-setting-fields flLt">
				<label><?php echo $fields['UG']['CurrentSubjects']->getLabel();?></label>
				<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('CurrentSubjects'); ?>>
					<div id="10thSubjectLayer" style="width:300px !important;color: #999;padding-left: 6px;padding-top:6px;" class="select-overlap"  onclick="showHideTenthSubjectOverlay();"></div>
					<select name="CurrentSubject" class="universal-select signup-select streamSelectClass" id="CurrentSubjects_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('CurrentSubjects'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
						<option value="">Select subjects</option>
						<option value="1"></option>
					</select>
					<div id="tenthSubjectOverlay" class="select-opt-layer" style="width: 280px; top: 22px ! important; display: block;left: -1px;display:none;">
						<div class="scrollbar1" id="">
							<div class="scrollbar" style="height: 180px;">
								<div class="track" style="height: 180px;">
									<div class="thumb" style="top: 0px; height: 45px;">
										<div class="end"></div>
									</div>
								</div>
							</div>
							<div class="viewport" style="height: 180px;">
								<div class="overview" style="top:0px;">
									<ul class="hm-refine">
										<?php foreach($valuetoshow as $key=>$val){ ?>
											<li style="margin-bottom:10px !important;">
												<div class="hm-refine-opt">
													<input type="checkbox" name="CurrentSubjects[]" value="<?=htmlentities($val)?>" class="subcatCheckbox" id="<?=$key?>_<?php echo $regFormId; ?>">
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
				<div><div class="errorMsg" id="CurrentSubjects_error_<?php echo $regFormId; ?>"></div></div>
			</div>
		<?php } ?>
		<div class="clearfix"></div>
	</li>
<?php } ?>
<!-- This is the Graduation Section Only -->
<?php if(isset($fields['PG']['graduationStream'])){ ?>
	<script>var rmcTenthSection = false;</script>
	<li>
		<?php if(isset($fields['PG']['graduationStream'])){ ?>
			<div class="account-setting-fields flLt">
				<label>Graduation stream</label>
				<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('graduationStream'); ?>>
					<select name="graduationStream" class="universal-select signup-select" id="graduationStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStream'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
						<option value="">Select Graduation stream</option>
						<?php foreach($fields['PG']['graduationStream']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
							<option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($tenthBoard) && ($tenthBoard == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
						<?php } ?>
					</select>
				</div>
				<div><div class="errorMsg" id="graduationStream_error_<?php echo $regFormId; ?>"></div></div>
			</div>
		<?php } ?>
		<?php if(isset($fields['PG']['graduationMarks'])){ ?>
			<div class="account-setting-fields flRt">
				<label>Graduation Percentage</label>
				<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
					<select name="graduationMarks" class="universal-select signup-select" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
						<option value="">select graduation Marks</option>
						<?php $valuetoshow = $fields['PG']['graduationMarks']->getValues(); ?>
						<?php foreach($valuetoshow as $plannedToGoValue => $plannedToGoText) { ?>
							<option value="<?php echo $plannedToGoValue; ?>" ><?php echo $plannedToGoText; ?></option>
						<?php } ?>
					</select>
				</div>
				<div><div class="errorMsg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div></div>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
		
	</li>
	<li>
		<?php if(isset($fields['PG']['workExperience'])){ ?>
			<div class="account-setting-fields flLt">
				<label>Work Experience</label>
				<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
					<select name="workExperience" class="universal-select signup-select" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
						<option value="">Select work Experience</option>
						<?php foreach($fields['PG']['workExperience']->getSAValues() as $plannedToGoValue => $plannedToGoText) { ?>
							<option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($tenthBoard) && ($tenthBoard == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
						<?php } ?>
					</select>
				</div>
				<div><div class="errorMsg" id="workExperience_error_<?php echo $regFormId; ?>"></div></div>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
		
	</li>
<?php } ?>
<?php if(isset($fields['UG']['tenthBoard'])){?>
<script>
	function initializeTenthSubject(str){
		if (!rmcTenthSection) {
			return ;
		}
		$j("#tenthSubjectOverlay").toggle();
		$j('.scrollbar1').tinyscrollbar();
		$j("#tenthSubjectOverlay").toggle();

		$j(document).mouseup(function (e)
        {
            var container = $j("#tenthSubjectOverlay");
            var containerDD = $j("#CurrentSubjects_block_"+str);

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
	
	initializeTenthSubject('<?php echo $regFormId; ?>');
	var choices = ['<?php echo implode("','", $fields['UG']['currentSchool']->getValues());?>'];
</script>
<?php $this->load->view('registration/fields/LDB/variable/currentSchoolSuggestor');?>
<?php } ?>
