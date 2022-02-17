<div id='examLayerIframeContainer_<?php echo $regFormId; ?>' class="regLayerIframe">
	<iframe scrolling="no" id="examLayerIframe_<?php echo $regFormId; ?>" src="about:blank" frameborder="0" class='exam-layer-iframe'></iframe>
</div>

<div id='examLayer_<?php echo $regFormId; ?>' class="regLayer" layerId='exam' style="position: relative;top:-250px; right:35px;">
	<div id='examLayerInner_<?php echo $regFormId; ?>' class='exam-layer-inner'>		
		<div class="city-layer-main" style="padding:0;  box-shadow: 2px 2px 2px #cccccc;">
			<div class="city-layer-col" style='width:265px !important; padding:15px 10px 10px 10px; color:#000;' id="examLayerBox_<?php echo $regFormId; ?>">
			<?php
				$listExams = $fields['exams']->getValues();
				$numExams = count($listExams['featured']) + count($listExams['others']);
				
				$height = 38 * $numExams;
				if($height > 235) {
					$height = 235;
				}
				
				if($numExams < 5){
					echo "<ul style='height:auto' class='lstItems'>";
				} else {
					echo "<ul style='height:".$height."px;' class='lstItems'>";
				}
					
				$examBlockCount = 1;
				foreach($listExams as $exams) {
					foreach($exams as $examId => $exam) {
			?>
					<li>
						<div class='exam-layer-cb'>
							<input <?php if(in_array($examId, $formData['exams'])) { echo 'checked="checked"'; } ?> type='checkbox' name="exams[]" class="exams_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('exams'); ?> value="<?php echo $examId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamDetails(this,'<?php echo $examId; ?>');" />
						</div>
							
						<div class='exam-layer-name' <?php if($examId == 'NOEXAM') { echo 'style="width:85%;"';}?>><?php echo $exam->getDisplayNameForUser(); ?></div>
						
						<?php if($exam->getScoreType()) { ?>
						
						<div class='exam-layer-details-box' id='examDetails_<?php echo $examId; ?>_<?php echo $regFormId; ?>' <?php if($exam->hasNameBox()) { echo 'style="width:100%;"';} ?>>
							<input type='hidden' value='<?php echo $exam->getScoreType(); ?>' name="<?php echo $examId; ?>_scoreType" />
							<?php if($exam->hasNameBox()) { ?>
							<div class='exam-layer-score-box' id='examName_<?php echo $examId; ?>_<?php echo $regFormId; ?>' style="width:35%;margin-left:25px;" >
								<input style="width:70px; float: left;color:#999999" type='text' class='exam-layer-score-field' value='Exam Name' default="Exam Name" caption="Exam Name" maxlength="20" regFieldId="<?php echo $examId; ?>_nameBox" id="<?php echo $examId; ?>_nameBox_<?php echo $regFormId; ?>" name="otherExamName" mandatory="1" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
								
							</div>
							<?php } ?>
							
							<div class='exam-layer-score-box' id='examScore_<?php echo $examId; ?>_<?php echo $regFormId; ?>' <?php if($exam->hasNameBox()) { echo 'style="width:53%; margin-left:0"';} else { echo 'style=""';} ?>>
								<input <?php if($exam->hasNameBox()) { ?> style="width:125px;" <?php } ?> type='text' class='exam-layer-score-field'" value='Enter <?php echo $exam->getScoreType(); ?>' default="Enter <?php echo $exam->getScoreType(); ?>" caption="<?php echo $exam->getScoreType() ?>" maxlength="5" regFieldId="<?php echo $examId; ?>_score" id="<?php echo $examId; ?>_score_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_score" minscore="<?php echo $exam->getMinScore(); ?>" maxscore="<?php echo $exam->getMaxScore(); ?>" validateExamScore='1' onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
							</div>
							
							<?php if($exam->hasPassingYear()) { ?>
							<div class='exam-layer-year-box' id='examYear_<?php echo $examId; ?>_<?php echo $regFormId; ?>'>
								<select class='exam-layer-year-select' caption="year appeared in" regFieldId="<?php echo $examId; ?>_year" id="<?php echo $examId; ?>_year_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_year" mandatory='1' onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
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
							
						</div>
						
						<?php if($exam->hasNameBox()) { ?>
						<div class='exam-layer-error-msgbox' style="margin-left:25px;float:left;">
							<div id="<?php echo $examId; ?>_nameBox_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
						</div>
						<div class='exam-layer-error-msgbox' style="margin-left:25px;float:left;">
							<div id="<?php echo $examId; ?>_score_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
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
						
					<?php } ?>
					</li>
				<?php } ?>
						
					<?php if($examBlockCount++ < count($listExams)) { ?>
					<li>
						<div class='exam-layer-separator'>&nbsp;</div>
					</li>
					<?php } ?>
				<?php } ?>		
				</ul>
			</div>
			
			<div class="clearFix"></div>
			
			<div align="center" class="city-layer-btn-exams">
				<input type="button" class="orange-button" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setSelectedExams();updateTinyScrollBar();" value="OK" title="OK" />
			</div>
		</div>
	</div>	
</div>
