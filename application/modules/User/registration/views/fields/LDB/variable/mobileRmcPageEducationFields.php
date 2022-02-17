<?php $fields = $fieldObj; ?>
<ul class="form-display customInputs">
<!-- This is the 10th section only -->
<?php if(isset($fields['UG']['tenthBoard'])){ ?>
	<script>var rmcTenthSection = true;</script>
	<?php if(isset($fields['UG']['currentClass'])){ ?>
	<li style="margin-bottom:10px;">
		<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('currentClass'); ?>>
			<select name="currentClass" class="universal-select universal-select-2" data-enhance = "false" id="currentClass_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('currentClass'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
				<option value=""><?php echo $fields['UG']['currentClass']->getCaption(); ?></option>
				<?php foreach($fields['UG']['currentClass']->getValues() as $key => $value) { ?>
					<option value="<?php echo $key; ?>" <?php if(!empty($currentClass) && ($currentClass == $value)) echo 'selected'; ?>><?php echo $value; ?></option>
				<?php } ?>
			</select>
			<div style="display:none;">
				<div class="errorMsg error-msg" id="currentClass_error_<?php echo $regFormId; ?>"></div>
			</div>
		</div>
		<div class="clearfix"></div>
	</li>
	<?php } ?>

	<?php if(isset($fields['UG']['tenthBoard'])){ ?>
	<li>
		<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('tenthBoard'); ?>>
			<select name="tenthBoard" class="universal-select universal-select-2" data-enhance = "false" id="tenthBoard_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthBoard'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].getTenthMarksAccToBoard(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
				<option value="">Class <?php echo $fields['UG']['tenthBoard']->getCaption(); ?></option>
				<?php foreach($fields['UG']['tenthBoard']->getValues() as $tenthBoardValue => $tenthBoardText) { ?>
					<option value="<?php echo $tenthBoardValue; ?>" <?php if(!empty($tenthBoard) && ($tenthBoard == $whenPlanToGo)) echo 'selected'; ?>><?php echo $tenthBoardText; ?></option>
				<?php } ?>
			</select>
			<div style="display:none;">
				<div class="errorMsg error-msg" id="tenthBoard_error_<?php echo $regFormId; ?>"></div>
			</div>
		</div>
	</li>
	<?php } ?>
		
	<?php if(isset($fields['UG']['tenthmarks'])){ ?>
	<li>
		<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('tenthmarks'); ?>>
			<select name="tenthmarks" class="universal-select universal-select-2" data-enhance = "false" id="tenthmarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthmarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
				<option value="">Select <?php echo $fields['UG']['tenthmarks']->getCaption(); ?></option>
				<?php $valuetoshow = $fields['UG']['tenthmarks']->getValues(); ?>
				<?php foreach($valuetoshow['CBSE'] as $tenthMarksValue => $tenthMarksText) { ?>
					<option value="<?php echo $tenthMarksValue ; ?>" ><?php echo $tenthMarksText; ?></option>
				<?php } ?>
			</select>
			<div style="display:none;">
				<div class="errorMsg error-msg" id="tenthmarks_error_<?php echo $regFormId; ?>"></div>
			</div>
		</div>
		<div class="clearfix"></div>
	</li>
	<?php } ?>

	<?php if(isset($fields['UG']['currentSchool'])){ ?>
	<li style="margin-bottom:10px;">
		<div <?php echo $registrationHelper->getBlockCustomAttributes('currentSchool'); ?>>
			<input type="text" name="currentSchool" id="currentSchool_<?php echo $regFormId; ?>" maxlength="100" placeholder="<?php echo $fields['UG']['currentSchool']->getLabel(); ?>" class="universal-txt" data-enhance = "false" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" <?php echo $registrationHelper->getFieldCustomAttributes('currentSchool'); ?> />
			<div style="display:none;">
				<div class="errorMsg error-msg" id="currentSchool_error_<?php echo $regFormId; ?>"></div>
			</div>
		</div>
		<div class="clearfix"></div>
	</li>
	<?php } ?>
	
	<?php if(isset($fields['UG']['CurrentSubjects'])){ ?>
	<li style="position: relative;margin-bottom:10px;">
		<?php $valuetoshow = $fields['UG']['CurrentSubjects']->getValues();?>
		<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('CurrentSubjects'); ?>>
			<div id="10thSubjectLayer" style="width:100%;color: #999;" class="drop-overlay"  onclick="showHideTenthSubjectOverlay();"></div>
			<select name="CurrentSubject" class="universal-select universal-select-2 _streamSelectClass" data-enhance = "false" id="CurrentSubjects_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('CurrentSubjects'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onclick="showHideTenthSubjectOverlay(event);">
				<option value="">Select <?php echo $fields['UG']['CurrentSubjects']->getCaption(); ?></option>
				<option value="1"></option>
			</select>
		</div>
		<div id="tenthSubjectOverlay" class="customDropLayer customInputs" style="display:none;" data-enhance="false">
			<strong class="drop-title">You can select multiple subjects here</strong>
			<div class="drop-layer-cont">
				<ul>
					<?php foreach ($valuetoshow as $key => $val) { ?>
					<li>
						<div>
							<input type="checkbox" name="CurrentSubjects[]" id="<?=$key?>_<?=($regFormId)?>" value="<?=htmlentities($val)?>" onclick="toggleCheckbox(event,this);">
							<label data-enhance = "false" for="<?=$key?>_<?=($regFormId)?>" style = "cursor:pointer;">
								<span class="sprite flLt"></span>
								<p title="<?=$val?>"><?=(formatArticleTitle($val,17))?></p>
							</label>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div><div class="errorMsg error-msg" id="CurrentSubjects_error_<?php echo $regFormId; ?>"></div></div>
		<div class="clearfix"></div>
	</li>
	<?php } ?>


<?php } ?>
<!-- This is the Graduation Section Only -->
<?php if(isset($fields['PG']['graduationStream'])){ ?>
	<script>var rmcTenthSection = false;</script>
		<?php if(isset($fields['PG']['graduationStream'])){ ?>
		<li>
			<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('graduationStream'); ?>>
				<select name="graduationStream" class="universal-select universal-select-2" data-enhance = "false" id="graduationStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStream'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
					<option value="">Graduation stream</option>
					<?php foreach($fields['PG']['graduationStream']->getValues() as $gradStreamValue => $gradStreamText) { ?>
						<option value="<?php echo $gradStreamValue; ?>" <?php if(!empty($tenthBoard) && ($tenthBoard == $whenPlanToGo)) echo 'selected'; ?>><?php echo $gradStreamText; ?></option>
					<?php } ?>
				</select>
				<div style="display:none;">
					<div class="errorMsg error-msg" id="graduationStream_error_<?php echo $regFormId; ?>"></div>
				</div>
			</div>
		</li>
		<?php } ?>

		<?php if(isset($fields['PG']['graduationMarks'])){ ?>
		<li>
			<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
				<select name="graduationMarks" class="universal-select universal-select-2" data-enhance = "false" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
					<option value="">Graduation Percentage</option>
					<?php $valuetoshow = $fields['PG']['graduationMarks']->getValues(); ?>
					<?php foreach($valuetoshow as $gradMarksValue => $gradMarksText) { ?>
						<option value="<?php echo $gradMarksValue; ?>"><?php echo $gradMarksText; ?></option>
					<?php } ?>
				</select>
				<div style="display:none;">
					<div class="errorMsg error-msg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div>
				</div>
			</div>
			<div class="clearfix"></div>
		</li>
		<?php } ?>
		
	
		<?php if(isset($fields['PG']['workExperience'])){ ?>
		<li style="margin-bottom:10px;">
			<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
				<select name="workExperience" class="universal-select universal-select-2" data-enhance = "false" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
					<option value="">Work Experience</option>
					<?php $valuetoshow = $fields['PG']['workExperience']->getSAValues(); ?>
					<?php foreach($valuetoshow as $workExpValue => $workExpText) { ?>
						<option value="<?php echo $workExpValue; ?>"><?php echo $workExpText; ?></option>
					<?php } ?>
				</select>
				<div style="display:none;">
					<div class="errorMsg error-msg" id="workExperience_error_<?php echo $regFormId; ?>"></div>
				</div>
			</div>
			<div class="clearfix"></div>
		</li>
		<?php } ?>
		
<?php } ?>
</ul>

<?php if(isset($fields['UG']['tenthBoard'])){?>
<script>
	function initializeTenthSubject(str){
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

	function callbackForTenthSubjects(){
		$j('#CurrentSubjects_<?=($regFormId)?>').trigger('blur');
	}
	
	initializeTenthSubject('<?php echo $regFormId; ?>');
	var choices = ['<?php echo implode("','", $fields['UG']['currentSchool']->getValues());?>'];
</script>
<?php $this->load->view('registration/fields/LDB/variable/currentSchoolSuggestor');?>
<?php  } ?>
<script type="text/javascript">
function showHideTenthSubjectOverlay(){
		var forceHide = (typeof arguments[0] != 'undefined' && arguments[0]== true?arguments[0]:false);
		if (forceHide) {
			$j('#tenthSubjectOverlay').hide();
		}
		else{
			$j('#tenthSubjectOverlay').toggle();
		}
		if ($j('#tenthSubjectOverlay').is(":hidden")) {
			shikshaUserRegistrationForm['<?=($regFormId)?>'].validateTenthStream(callbackForTenthSubjects);
		}
	}	
</script>