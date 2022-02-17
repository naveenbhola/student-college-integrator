<?php
	foreach($fields as $fieldId => $field) {
		switch($fieldId) {
			case 'residenceCity': ?>
				<li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?> data-enhance="false">
				    <div>
				    	<div class="reg-dropdwn ssLayerWOG" id="residenceCity_<?php echo $regFormId; ?>_input" mandatory="0">
				            <span class="textHolder">Current City</span>
				        	<i class="rightPointer"></i>
				        </div>

				        <div style="display:none" class="select-Class">
				        	<select name="residenceCity" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?>>
				                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Current City', 'isNational' => true)); ?>
				            </select>
				        </div>
				        <div>
				        	<div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
				        </div>
				    </div>
				</li>
		<?php break; 

			case 'residenceCityLocality': ?>
				<li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCityLocality'); ?> data-enhance="false">
				    <div>
				    	<div class="reg-dropdwn ssLayerWOG" id="residenceCityLocality_<?php echo $regFormId; ?>_input">
				            <span class="textHolder">Current City</span>
				        	<i class="rightPointer"></i>
				        </div>

				        <div style="display:none" class="select-Class">
				 			<select id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalitiesNew(this);">
					            <?php $this->load->view('registration/common/dropdowns/residenceLocality'); ?>
					        </select>
				        </div>
				        <div>
				        	<div class="errorMsg" id="residenceCityLocality_error_<?php echo $regFormId; ?>"></div>
				        </div>
				    </div>
				</li>
			<?php break;

			case 'residenceLocality': ?>
				<li <?php echo $registrationHelper->getBlockCustomAttributes('residenceLocality','display:none;'); ?> data-enhance="false">
				    <div>
				    	<div class="reg-dropdwn ssLayerWOG" id="residenceLocality_<?php echo $regFormId; ?>_input">
				        	<span class="textHolder">Locality</span>
				        	<i class="rightPointer"></i>
				        </div>
				        <div style="display:none" class="select-Class">
				            <select id="residenceLocality_<?php echo $regFormId; ?>" name="residenceLocality" class="residenceLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceLocality'); ?>>
				                <option value="">Select Area</option>
				            </select>
				        </div>
				        <div>
				            <div class="errorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>"></div>
				        </div>
				    </div>
				</li>
			<?php break; 

			case 'xiiYear': ?>
				<li <?php echo $registrationHelper->getBlockCustomAttributes('xiiYear'); ?> data-enhance="false">
				    <div>
				        <div class="reg-dropdwn ssLayer" id="xiiYear_<?php echo $regFormId; ?>_input" mandatory="1">
				            <span class="textHolder">XII Completion Year</span>
				            <i class="rightPointer"></i>
				        </div>

				        <div style="display:none" class="select-Class">
				            <select name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiYear'); ?>>
				                <option value="">XII Completion Year</option>
				                <?php foreach($fields['xiiYear']->getValues() as $xiiYear) { ?>
				                    <option value='<?php echo $xiiYear; ?>'><?php echo $xiiYear; ?></option>
				                <?php } ?>  
				            </select>
				        </div>

				        <div>
				            <div class="errorMsg" id="xiiYear_error_<?php echo $regFormId; ?>"></div>
				        </div>
				    </div>
				</li>
			<?php break; 

			case 'graduationCompletionYear': ?>
				<li <?php echo $registrationHelper->getBlockCustomAttributes('graduationCompletionYear'); ?> data-enhance="false">
				    <div>
				    	<div class="reg-dropdwn ssLayer" id="graduationCompletionYear_<?php echo $regFormId; ?>_input" mandatory="1">
				            <span class="textHolder">Graduation Completion Year</span>
				        	<i class="rightPointer"></i>
				        </div>

				        <div style="display:none" class="select-Class">
				        	<select name="graduationCompletionYear" id="graduationCompletionYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationCompletionYear'); ?>>
				                <option value="">Graduation Completion Year</option>
				                <?php foreach($fields['graduationCompletionYear']->getValues() as $graduationCompletionYear) { ?>
				                    <option value='<?php echo $graduationCompletionYear; ?>'><?php echo $graduationCompletionYear; ?></option>
				                <?php } ?>	
				            </select>
				        </div>

				        <div>
				            <div class="errorMsg" id="graduationCompletionYear_error_<?php echo $regFormId; ?>"></div>
				        </div>

				        <div>
                    		<div class="errorMsg" id="graduationCompletionDate_error_<?php echo $regFormId; ?>"></div>
            			</div>

				    </div>
				</li>
			<?php break; 

			case 'exams': 
				$listExams = $fields['exams']->getValues();
				$numExams = count($listExams['featured']) + count($listExams['others']);  

				if($numExams>0) {  
					$listExams = $fields['exams']->getValues();
				?>

				<li <?php echo $registrationHelper->getBlockCustomAttributes('exams'); ?> data-enhance="false">
				    <div>
				    	<div class="reg-dropdwn examLayerButton" id="examLayerButton_<?php echo $regFormId; ?>_input">
				    		<span class="textHolder">Exams Taken</span>
				        	<i class="rightPointer"></i>
				        </div>

				    </div>


		            <div style="display:none" id="examSelect">

		                <div class="graylayer"></div>
		                <div class="text-show fixedCountry">
		                	<i class="close">×</i>
		                  	<div class="customSelectCountry multiselect editable" id="custom-slct2">                                   
		                        
		                    	<div class="option-wrapper show">

			                        <ul class="drpdwn-ul">
										<?php

										foreach($listExams as $exams) {
                    						foreach($exams as $examId => $exam) {
                    							if($examId != 'NOEXAM') {

										?>

					                             	<li>
					                                 	<div class="Customcheckbox">

		 													<input id="<?php echo $examId.'_'.$regFormId; ?>" type='checkbox' name="exams[]" class="exams_<?php echo $regFormId; ?> examCheckbox" <?php echo $registrationHelper->getFieldCustomAttributes('exams'); ?> value="<?php echo $examId; ?>" />                    		
		 													<label for="<?php echo $examId.'_'.$regFormId; ?>" class="examCheckboxLabel"><?php echo $exam->getDisplayNameForUser(); ?></label>

					                                 	</div>
					                             	</li>

					                             	<?php if($exam->getScoreType()) { ?>
					                             	<li class="score-box" style="display:none" id="<?php echo $examId;?>">
		 												<?php if($exam->hasNameBox()) { ?>												

				                                        <div class="flLt" id='examName_<?php echo $examId; ?>_<?php echo $regFormId; ?>' style="width:49%;">

				                                            <input type='text' mandatory="1" class="NewReg-field examScore" value='Exam Name' default="Exam Name" caption="Exam Name" maxlength="20" regFieldId="<?php echo $examId; ?>_nameBox" id="<?php echo $examId; ?>_nameBox" name="otherExamName" />
				                                            <div>
			                                                <div examName="<?php echo $examId; ?>_nameBox" class="errorMsg examScoreError" style="line-height:20px;"></div>
			                                            </div>


				                                        </div>
				                                        <div class="flRt" style="width:49%;">
				                                   			<input type='text' class="NewReg-field examScore" value='Enter <?php echo $exam->getScoreType(); ?>' default="Enter <?php echo $exam->getScoreType(); ?>" caption="<?php echo $exam->getScoreType() ?>" maxlength="5" regFieldId="<?php echo $examId; ?>_score" id="<?php echo $examId; ?>_score" name="<?php echo $examId; ?>_score" minscore="<?php echo $exam->getMinScore(); ?>" maxscore="<?php echo $exam->getMaxScore(); ?>" validateExamScore='1' />

				                                   			<div>
		                                                	<div examName="<?php echo $examId; ?>" class="errorMsg examScoreError" style="line-height:20px;"></div>
		                                            	</div>

				                                   		</div>
				                                   		<div class="clearfix"></div>


				                                        
				                                        <?php } else { ?>

				                                        <input type='text' class="NewReg-field examScore" value='Enter <?php echo $exam->getScoreType(); ?>' default="Enter <?php echo $exam->getScoreType(); ?>" caption="<?php echo $exam->getScoreType() ?>" maxlength="5" regFieldId="<?php echo $examId; ?>_score" id="<?php echo $examId; ?>_score" name="<?php echo $examId; ?>_score" minscore="<?php echo $exam->getMinScore(); ?>" maxscore="<?php echo $exam->getMaxScore(); ?>" validateExamScore='1' />

				                                        <div>
		                                                	<div examName="<?php echo $examId; ?>" class="errorMsg examScoreError" style="line-height:20px;"></div>
		                                            	</div>

				                                        <?php } ?>

			                                           
					                             	</li>

			                             	<?php 	} 
			                             		}	
			                             	} 
			                        	} ?>

                      				</ul>
	                      
		                      		
		                      	</div>

		                      	<div class="done-btn"><input type="button" id="examSave" class="green-btn" value="Done"></div>

		                    </div>
		                </div>

		            </div>
				</li>
			<?php 
				}
			break; 
		}
	} 
?>