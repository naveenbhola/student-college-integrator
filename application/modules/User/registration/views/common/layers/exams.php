<div id='examLayerIframeContainer_<?php echo $regFormId; ?>' class="regLayerIframe">
	<iframe scrolling="no" id="examLayerIframe_<?php echo $regFormId; ?>" src="about:blank" frameborder="0" class='exam-layer-iframe'></iframe>
</div>
<?php if($mmpFormId){ ?>
<div id='examLayer_<?php echo $regFormId; ?>' class="regLayer" layerId='exam' <?php if($mmpData['display_on_page'] == 'normalmmp') { ?>style="width:372px;"<?php } else { ?>style="position: absolute;top:auto;width:372px;" <?php } ?> >
<div id='examLayerInner_<?php echo $regFormId; ?>' class='exam-layer-inner'>
		
<div class="city-layer-main" style="padding:0;  box-shadow: 2px 2px 2px #cccccc;">
	<div class="city-layer-col" style='width:355px !important;; padding:15px 10px 10px 10px; color:#000;' id="examLayerBox_<?php echo $regFormId; ?>">
<?php }else{ ?>
<div id='examLayer_<?php echo $regFormId; ?>' class="regLayer" layerId='exam' style="position: absolute;<?php if($context == 'registerResponseLPR'){echo 'top: auto;bottom:36px;';}else{ echo 'top: 31px;';} ?>">
<div id='examLayerInner_<?php echo $regFormId; ?>' class=''>

	<div class="city-layer-col customInputs" style='width:355px; padding:10px;' id="examLayerBox_<?php echo $regFormId; ?>">
<?php } ?>
		<?php
		$listExams = $fields['exams']->getValues();
		$numExams = count($listExams['featured']) + count($listExams['others']);
		
		$height = 38 * $numExams;
		if($height > 235) {
			$height = 235;
		}
		
		if($numExams < 5){
		echo "<ul style='height:auto'>";
		}else{
		echo "<ul style='height:".$height."px;'>";
		}
		
		$examBlockCount = 1;
		foreach($listExams as $exams) {
                    foreach($exams as $examId => $exam) {
                        if($mmpFormId){
                ?>
                        <li>
				<div class='exam-layer-cb'><input <?php if(in_array($examId, $formData['exams'])) { echo 'checked="checked"'; } ?> type='checkbox' name="exams[]" class="exams_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('exams'); ?> value="<?php echo $examId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamDetails(this,'<?php echo $examId; ?>');" /></div>
				
				<div class='exam-layer-name'><?php echo $exam->getDisplayNameForUser(); ?></div>
				
				<div class="clearFix"></div>
				
				<?php if($exam->getScoreType()) { ?>
				
				<div class='exam-layer-details-box' id='examDetails_<?php echo $examId; ?>_<?php echo $regFormId; ?>'>
					<input type='hidden' value='<?php echo $exam->getScoreType(); ?>' name="<?php echo $examId; ?>_scoreType" />
					<?php if($exam->hasNameBox()) { ?>
					<div class='exam-layer-score-box' id='examName_<?php echo $examId; ?>_<?php echo $regFormId; ?>' style="width:40%;" >
						<input style="width:120px; float: left" type='text' <?php if($mmpFormId) { echo "class='exam-layer-score-field'"; } else { echo "class='register-fields exam-layer-score-field-nonmmp'"; } ?> value='Exam Name' default="Exam Name" caption="Exam Name" maxlength="20" regFieldId="<?php echo $examId; ?>_nameBox" id="<?php echo $examId; ?>_nameBox_<?php echo $regFormId; ?>" name="otherExamName" mandatory="1" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
						<div class='exam-layer-error-msgbox' style="margin-left:0">
							<div id="<?php echo $examId; ?>_nameBox_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
						</div>
					</div>
					<?php } ?>
					<div class='exam-layer-score-box' id='examScore_<?php echo $examId; ?>_<?php echo $regFormId; ?>' <?php if($exam->hasNameBox()) { echo 'style="width:40%; margin-left:0"';} else { echo 'style="width:300px;"';} ?>>
						<input <?php if($exam->hasNameBox()) { ?> style="width:120px;margin-left:10px;" <?php } ?> type='text' <?php if($mmpFormId) { echo "class='exam-layer-score-field'"; } else { echo "class='register-fields exam-layer-score-field-nonmmp'"; } ?> value='Enter <?php echo $exam->getScoreType(); ?>' default="Enter <?php echo $exam->getScoreType(); ?>" caption="<?php echo $exam->getScoreType() ?>" maxlength="5" regFieldId="<?php echo $examId; ?>_score" id="<?php echo $examId; ?>_score_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_score" minscore="<?php echo $exam->getMinScore(); ?>" maxscore="<?php echo $exam->getMaxScore(); ?>" validateExamScore='1' onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
						<?php if($exam->hasNameBox()) { ?>
						<div class='exam-layer-error-msgbox' style="margin-left:12px">
							<div id="<?php echo $examId; ?>_score_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
						</div>
						<?php } ?>
					</div>
				
				<?php if($exam->hasPassingYear()) { ?>
				<div class='exam-layer-year-box' id='examYear_<?php echo $examId; ?>_<?php echo $regFormId; ?>'>
					<select <?php if($mmpFormId) { echo "class='exam-layer-year-select'"; } else { echo "class='exam-layer-year-select-nonmmp'"; } ?> caption="year appeared in" regFieldId="<?php echo $examId; ?>_year" id="<?php echo $examId; ?>_year_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_year" mandatory='1' onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
						<option value=''>Appeared in</option>
						<?php foreach($exam->getPassingYearValues() as $yk => $yv) { ?>
							<option value='<?php echo $yk; ?>'><?php echo $yv; ?></option>
						<?php } ?>
					</select>
				</div>
				
				<?php } ?>
				
				<div class="clearFix"></div>
				
				
				<?php if($exam->getState()) { ?>
				<div class='exam-layer-state-info'>
					<div id="<?php echo $examId; ?>_state_<?php echo $regFormId; ?>">
						Accepted for colleges in <?php echo $exam->getState(); ?>
					</div>
				</div>
				<?php } ?>
				
				<?php if(!$exam->hasNameBox()) { ?>
				<div class='exam-layer-error-msgbox'>
					<div id="<?php echo $examId; ?>_score_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
				</div>
				<?php } ?> 
				<div class='exam-layer-error-msgbox'>
					<div id="<?php echo $examId; ?>_year_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
				</div>
				
				</div>
				<?php } ?>
			</li>
            
                <?php
                        }else{
		?>	
			<li>
				<div class='flLt' style="padding-top:6px;">
                                    <input id="<?php echo $exam->getDisplayNameForUser().'_'.$regFormId; ?>" type='checkbox' name="exams[]" class="exams_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('exams'); ?> value="<?php echo $examId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamDetails(this,'<?php echo $examId; ?>');" />
                                    <label for="<?php echo $exam->getDisplayNameForUser().'_'.$regFormId; ?>" style="<?php if(!$exam->hasNameBox() && $examId != 'NOEXAM') { echo 'width:100px !important;';} if($exam->hasNameBox()) { echo 'width:80px !important';} if($examId == 'NOEXAM') { echo 'width:200px !important;';} ?>">
                                        <span class="common-sprite"></span>
                                        <p style="text-align:left;"><?php echo $exam->getDisplayNameForUser(); ?></p>
                                    </label>
                                </div>
				
                            <?php if($exam->getScoreType()) { ?>
                                
                                <div class='exam-layer-details-box' id='examDetails_<?php echo $examId; ?>_<?php echo $regFormId; ?>'>
                                    <input type='hidden' value='<?php echo $exam->getScoreType(); ?>' name="<?php echo $examId; ?>_scoreType" />

                                    <div class='other-exams' id='examScore_<?php echo $examId; ?>_<?php echo $regFormId; ?>' style="width:260px;">
                                        
                                        <?php if($exam->hasNameBox()) { ?>
                                        <div class="two-column-fields" style="padding:0 10px 0 0; width:40%;" id='examName_<?php echo $examId; ?>_<?php echo $regFormId; ?>'>

                                            <input type='text' mandatory="1" class="register-fields" value='Exam Name' default="Exam Name" caption="Exam Name" maxlength="20" regFieldId="<?php echo $examId; ?>_nameBox" id="<?php echo $examId; ?>_nameBox_<?php echo $regFormId; ?>" name="otherExamName" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
                                            
                                            <div>
                                                <div id="<?php echo $examId; ?>_nameBox_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="two-column-fields" <?php if($exam->hasNameBox()) { echo 'style="width:40%;"';} ?> >

                                            <input type='text' class="register-fields" value='Enter <?php echo $exam->getScoreType(); ?>' default="Enter <?php echo $exam->getScoreType(); ?>" caption="<?php echo $exam->getScoreType() ?>" maxlength="5" regFieldId="<?php echo $examId; ?>_score" id="<?php echo $examId; ?>_score_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_score" minscore="<?php echo $exam->getMinScore(); ?>" maxscore="<?php echo $exam->getMaxScore(); ?>" validateExamScore='1' onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
				
                                            <?php if($exam->getState()) { ?>
                                            <div class='exam-layer-state-info' style="margin-left:0px;width: 220px;">
                                                    <div id="<?php echo $examId; ?>_state_<?php echo $regFormId; ?>">
                                                            Accepted for colleges in <?php echo $exam->getState(); ?>
                                                    </div>
                                            </div>
                                            <?php } ?>

                                            <div>
                                                <div id="<?php echo $examId; ?>_score_error_<?php echo $regFormId; ?>" class="regErrorMsg" style="<?php if(!$exam->hasNameBox()) { echo 'width:220px;';} ?>"></div>
                                            </div>
                                        </div>
                                        
                                        <?php if($exam->hasPassingYear()) { ?>


                                        <div style="padding-left:10px;" class='two-column-fields' id='examYear_<?php echo $examId; ?>_<?php echo $regFormId; ?>'>
                                            <div class="custom-dropdown">
                                                <select caption="year appeared in" regFieldId="<?php echo $examId; ?>_year" id="<?php echo $examId; ?>_year_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_year" mandatory='1' onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                                                        <option value=''>Appeared in</option>
                                                        <?php foreach($exam->getPassingYearValues() as $yk => $yv) { ?>
                                                                <option value='<?php echo $yk; ?>'><?php echo $yv; ?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                            <div class=''>
                                                <div id="<?php echo $examId; ?>_year_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                                            </div>
                                        </div>

                                        <?php } ?>
                                        
                                        
                                    </div>
				</div>
                            <?php } ?>
			</li>
                        <?php } ?>
		<?php } ?>
		
		<?php if($examBlockCount++ < count($listExams)) { ?>
			<li><div class='exam-layer-separator'>&nbsp;</div></li>
		<?php } ?>
		
		<?php } ?>		
        </ul>
	</div>

    <div class="clearFix"></div>
	<div align="center" class="city-layer-btn-exams">
		<input type="button" class="orange-button" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setSelectedExams();" value="OK" title="OK" />
	</div>
<?php if($mmpFormId){ ?>
</div>
<?php } ?>
</div>	
</div>
