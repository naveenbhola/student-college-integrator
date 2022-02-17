<?php
//_p($formData);
//_p($loggedInUserData);
//_p($abroadShortRegistrationData);
//_p($formData['destinationCountry']);
?>
<script>
var formId = '<?= $regFormId; ?>';
var courseLevel = "<?= $formData['desiredGraduationLevel']?>";
var destinationCountry = <?= json_encode($formData['destinationCountry']);?>;
var profilePage = true;
</script>
<div class="account-detail-widget customInputs">
				<div class="account-detail-wideget-head">Personal information</div>
				<ul>
					<li>
						<?php
						if(isset($fields['firstName'])){
								$firstName = 'First Name';
								$disabled = '';
								if(!empty($abroadShortRegistrationData['firstName'])) {
									$firstName = $abroadShortRegistrationData['firstName'];
									//$disabled = 'disabled="disabled"';
								}
						?>
						<div class="account-setting-fields flLt" <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?>>
							<label>First name</label>
							<input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="universal-text signup-txtfield" minlength="1" maxlength="50" default="First Name" value="<?php echo $firstName; ?>" <?php echo $disabled; ?> profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"  />
							<div><div class="errorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div></div>
						</div>
						<?php } 
						if(isset($fields['lastName'])){
								$lastName = 'Last Name';
								$disabled = '';
								if(!empty($abroadShortRegistrationData['lastName'])) {
									$lastName = $abroadShortRegistrationData['lastName'];
									//$disabled = 'disabled="disabled"';
								}
						?>
						<div class="account-setting-fields flRt" <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
							<label>Last name</label>
							<input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="universal-text signup-txtfield" minlength="1" maxlength="50" default="Last Name" value="<?php echo $lastName; ?>" <?php echo $disabled; ?> profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
							<div><div class="errorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div></div>
						</div>
						<div class="clearfix"></div>
						<?php } ?>
					</li>
					<li>
				<?php
		if(isset($fields['isdCode'])){
            $ISDCode = 'Country Code';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['isdCode'])) {
                $isdCode = $abroadShortRegistrationData['isdCode'];
                //$disabled = 'disabled="disabled"';
            }
            $ISDCodeValues = $fields['isdCode']->getValues();
            ?>

            <div  class="flLt account-setting-fields " style="width:43%;">
            	<div class"flLt" style="width:42%; float:left; margin-right:2%;"  <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?>>
					<div style="padding-bottom:5px;">
						<span>Country Code</span>
					</div>
					<div class="custom-dropdown signup-txtwidth" style="width:100%;">
	                    <select class="universal-select signup-select"  required="true" validate="validateSelect" caption="ISD Code" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">                                       
	                      <?php foreach($ISDCodeValues as $key=>$value){ ?>
	                            <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
	                     <?php } ?>
	                                        
	                    </select>
                	</div>
					<div>
						<div class="errorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
					</div>
				</div>

		<?php }
						if(isset($fields['mobile'])){
								$mobile = 'Mobile No.';
								$disabled = '';
								if(!empty($abroadShortRegistrationData['mobile'])) {
									$mobile = $abroadShortRegistrationData['mobile'];
									//$disabled = 'disabled="disabled"';
								}
						?>
						<div class="flLt" style="float:left;width:56%" <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
							<label>Mobile no.</label>
							<input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="universal-text signup-txtfield" maxlength="10" minlength="10" default="Mobile No." value="<?php echo $mobile; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
							<div><div class="errorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div></div>
						</div>
						</div>
						<?php }
						
						if(isset($fields['email'])){
									$email = 'Email Id';
									$disabled = '';
									if(!empty($abroadShortRegistrationData['email'])) {
										$email = $abroadShortRegistrationData['email'];
										$disabled = 'disabled="disabled"';
										
										//in case of loggedinuser set contactByConsultant field visible
										//$fields['contactByConsultant']->setVisible('Yes');
										//$fields['contactByConsultant']->setMandatory('Yes');
									}
						?>
						<div class="account-setting-fields flRt">
							<label>Email ID</label>
							<p class="accnt-setting-mail-info"><?php echo $email; ?></p>
							<input type="hidden" name="email" id="email_<?php echo $regFormId; ?>" value="<?php echo $email; ?>" />
							<div><div class="errorMsg" id="email_error_<?php echo $regFormId; ?>"></div></div>		
						</div>
						<div class="clearfix"></div>
						<?php   }?>
					</li>
					<?php if(isset($fields['residenceCity'])){
						$userCity = $formData['residenceCity'];
						?>
					<li style="margin-bottom:0;" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
						<div class="account-setting-fields flLt">
							<label>Current city</label>
							<div class="custom-dropdown">
								<select name="residenceCity" class="universal-select signup-select" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
									<?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical','formData'=>array('residenceCity'=>$userCity))); ?>
								</select>
						   </div>
						   <div><div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div></div>
						</div>
						<div class="clearfix"></div>
					</li>
					<?php } ?>
				</ul>
</div>


<div class="account-detail-widget customInputs">
				<div class="account-detail-wideget-head">Education details</div>
				<div class="edu-detail-fields">
					<ul>	 
					<li style="margin-bottom:0;">
					<div id='fieldOfInterest_block_parent_<?php echo $regFormId; ?>'>
					<?php  if(isset($fields['fieldOfInterest'])){?> 
						<div class="account-setting-fields flLt" <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
							<label>Primary course of interest</label>
							<div class="custom-dropdown">
								<select class="universal-select signup-select" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
									<option value="">Which course do you want to pursue?</option>
									<optgroup label="Choose a popular course">
									<?php $desiredCourses = $fields['abroadDesiredCourse']->getValues();
										foreach($desiredCourses as $course) {
												if($course['SpecializationId'] == $formData['desiredCourse']) {
														$selected = 'selected="selected"';
												} else {
														$selected = '';
												} ?>
										<option value="<?=$course['SpecializationId']?>" <?= $selected;?>><?=$course['CourseName']?></option>
								<?php } ?>
									 </optgroup>
									 <optgroup label="Or Choose a stream">
									<?php foreach($fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')) as $categoryId => $categoryName) {
												if($categoryId == $formData['fieldOfInterest']) {
														$selected = 'selected="selected"';
												} else {
														$selected = '';
												} 
												?>
										<option value="<?php echo $categoryId; ?>" <?= $selected;?>><?php echo $categoryName['name']; ?></option>
									<?php } ?>
									 </optgroup>
								</select>
						   </div>
							<div><div class="errorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div></div>
						</div>
						<?php } ?>
						<?php if(isset($fields['abroadSpecialization'])){ ?>
						<div class="account-setting-fields flRt" id='abroadSpecialization_block_<?php echo $regFormId; ?>' <?php echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization'); ?>>
							<label>Course Specialization</label>
							<div class="custom-dropdown">
								<select name="abroadSpecialization" class="universal-select signup-select" id="abroadSpecialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
									<option>Select specialization (optional)</option>
								</select>
						   </div>
							<div>
									<div class="errorMsg" id="abroadSpecialization_error_<?php echo $regFormId; ?>"></div>
						    </div>
						</div>
						<?php }?>
						<div class="clearfix"></div>
					<?php if(isset($fields['abroadDesiredCourse'])){
						echo "<input type='hidden' id='abroadDesiredCourseHidden' name='desiredCourse' value= '' />";
						}
							if(isset($fields['desiredGraduationLevel'])){
									if($formData['desiredGraduationLevel']!=''){
										$visible = 'Yes';
									   $style = 'display:block;margin-bottom:0;';
									}else{
										$visible = 'No';
									    $style = 'display:none;margin-bottom:0;';
									}
						?>  
						<div style="padding:0; margin-top:8px;" class="flRt signUp-child-wrap">
						<div <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel',array('visible' => $visible,'style'=>$style)); ?>>
							<p class="mb4">Choose level of study</p>
							<div class="form-sec" id="twoStepLevelOfStudy" style="margin-bottom: 0">
								<div class="field-child-wrap">
								<?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) {
									$checked = '';
									//_p($desiredGraduationLevelText['CourseName']);
									if(strtolower($desiredGraduationLevelText['CourseName']) == strtolower($formData['desiredGraduationLevel'])){
											$checked = 'checked="checked"';	
									}
									
									?>
									<div class="columns <?=$desiredGraduationLevelText['CourseName'].'-margin' ?>">
										<input type="radio" id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" onclick="this.blur();" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateSpecializedCourses();">
										<label for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>">
											<span class="common-sprite"></span>
											<p><strong style="font-size:12px !important;"><?=$desiredGraduationLevelText['CourseName']?></strong></p>
										</label>
									</div>
								<?php } ?>
								</div>
								<div class="clearFix"></div>
								<div>
									<div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
								</div>
							</div>               
						</div>
						</div>
						<div class="clearfix"></div>
						<?php } ?>
						</div>
					</li>
				</ul>
				</div>
				<ul style="margin-bottom:0">
					<li>
					<?php if(isset($fields['destinationCountry'])){?>
						<div class="account-setting-fields flLt signup-txtwidth">
						<div <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
							  <div id="twoStepChooseCourseCountryDropDown" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();" style="position: relative; left: 0; top: 0; width: 100%; background:rgba(0,0,0,0);">
							  
										  <?php $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')); //print_r($desiredCourses);exit;?>
										  <label>Country of interest</label>
										  <div class="custom-dropdown">
													  <div id="countryNameDiv" class="select-overlap" style="width:300px !important;"></div>
													  <select class="universal-select signup-select" id="twoStepCountrySelect">
																  <option id="twoStepCountrySelectOption">Which country do you want to study?</option>
													  </select>
										  </div>          
										  <div class="select-opt-layer" style="display:none; width: 435px; z-index: 99;left:0 !important; right:auto; top: 45px !important;" id="twoStepCountryDropdownLayer" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();">
													  <div class="scrollbar1" id="twoStepChooseCourseCountryLayerScrollbar">
																  <div class="scrollbar courseCountryScrollbarHeight disable" style="height: 145px;">
																			  <div class="track courseCountryScrollbarHeight" style="height: 145px;">
																						  <div class="thumb" style="top: 0px; height: 145px;">
																									  <div class="end"></div>
																						  </div>
																			  </div>
																  </div>
																  <style>
																			  .flags {background: url("/public/images/flags-sprite5.png") no-repeat scroll 0 0; display: inline-block; height: 35px; width: 53px; }
																  </style>
																  <div class="viewport courseCountryScrollbarHeight" style="height: 145px;">
																			  <div class="overview" style="top: 0px;" id = "twoStepCountryDropdownCont">
																						  <p class="font-9"><strong>TOP COUNTRIES</strong></p>
																						  <ol>
																									  <li>
																												  <?php
																												  global $studyAbroadPopularCountries;
																												  foreach($studyAbroadPopularCountries as $key => $popularCountry){
																												  ?>
																															  <div class="country-flag-cont" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected(this); ">
																																		  <div class="flag-main-box">
																																					  <p class="clearfix">
																																								  <span class="flags flLt <?php echo str_replace(' ', '', strtolower($popularCountry)); ?>">
																																								  <!--img src="/public/images/abroadCountryFlags/<?php echo $popularCountry; ?>.gif"-->
																																								  </span>
																																								  <strong><?php echo $popularCountry; ?></strong>
																																					  </p>
																																		  </div>
																																		  <div class="flag-chkbox">
																																					  <input type="checkbox" name="destinationCountry[]" id="<?php echo $popularCountry; ?>-flag" value="<?php echo $key; ?>">
																																					  <label for="<?php echo $popularCountry; ?>-flag" onclick="return false;"><span class="common-sprite"></span></label>
																																		  </div>
																															  </div>
								  
																												  <?php } ?>
																									  </li>
																						  </ol>
																						  <p class="font-9 clearfix" style="margin-bottom:15px"><strong>OTHER COUNTRIES</strong></p> 
																						  <ul class="customInputs-large">
																									  <?php 
																									  $liCount = 0;
																									  $countryCount = count($destinationCountry); 
																									  foreach ($destinationCountry as $key => $country) {
																												  if($country->getId() == 1) {
																															  continue;
																												  }
																												  if(!in_array($country->getName(),$studyAbroadPopularCountries)){
																															  $liCount++; 
																															  if($liCount%3 == 1) {echo '<li>';}
																									  ?>	
																															  <div class="flLt country-width" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected(this);">
																																		  <input type="checkbox" name="destinationCountry[]" id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" value="<?php echo $country->getId(); ?>" >
																																		  <label for="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" class="font-10" onclick="return false;"><span class="common-sprite"></span> <?php echo $country->getName();?></label>
																															  </div>
																															  <?php if($liCount == $countryCount || $liCount%3 == 0) {echo '</li>';} 
																												  }
																									  } ?>
																						  </ul>
																			  </div>
																  </div>
													  </div>
								  
													  <div style="margin: 8px 0 0px 0;border-top: 1px solid #ccc;">
																  <p class="font-11 choose-CountryTitle">Choose up to 3 countries
																			  <a href="JavaScript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();" class="button-style" style="padding: 6px 30px;margin-left:35px;">OK</a>
																  </p>
													  </div>
										  </div>
										  <div>
													  <div style="color: red; display: none;" class="errorMsg" id="twoStepCourseCountryLayer_error<?php echo $regFormId ?>"></div>
										  </div>
							  </div>
				  </div>
				  <div class="clearfix"></div>
				  </div>
			      <?php }?>

						<?php        
							if(isset($fields['whenPlanToGo'])){
									if(!empty($abroadShortRegistrationData['whenPlanToGo'])) {
										//$creationDate = strtotime($abroadShortRegistrationData['creationDate']);
										//$creationDate = (int)date('Y', $creationDate);
										 if(date('m',strtotime('now')) > 9) {
										 	$creationDate = (int)date('Y', time());
											$whenPlanToGo = strtotime($abroadShortRegistrationData['whenPlanToGo']);
											$whenPlanToGo = (int)date('Y', $whenPlanToGo);
							
											// _p($whenPlanToGo); die;
											if($creationDate + 1 == $whenPlanToGo) {
												$whenPlanToGo = 'in1Year';
											}else if($creationDate + 2 == $whenPlanToGo) {
												$whenPlanToGo = 'in2Years';
											}
											else {
												$whenPlanToGo = 'later';
											}
										 }else{

											$creationDate = (int)date('Y', time());
											$whenPlanToGo = strtotime($abroadShortRegistrationData['whenPlanToGo']);
											$whenPlanToGo = (int)date('Y', $whenPlanToGo);
							
											if($creationDate == $whenPlanToGo) {
												$whenPlanToGo = 'thisYear';
											}
											else if($creationDate + 1 == $whenPlanToGo) {
												$whenPlanToGo = 'in1Year';
											}
											else {
												$whenPlanToGo = 'later';
											}
										}		
									}
						?>
						<div class="account-setting-fields flRt">
							<label>Planing to study abroad in</label>
							<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
								<select name="whenPlanToGo" class="universal-select signup-select" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                                <option value="">When do you plan to start?</option>
									<?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
									<option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($whenPlanToGo) && ($plannedToGoValue == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
									<?php } ?>
                            </select>
						   </div>
							<div><div class="errorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div></div>
						</div>
						<?php }	?>
						<div class="clearfix"></div>
					</li>
					<li>
						<?php
						//this check is for if we delete any exam or No is selected for any exam given
						$examsAbroad = $abroadShortRegistrationData['examsAbroad'];
						$examsAbroadMasterList = $fields['examsAbroad']->getValues();
						
						$abroadExamNameList = array_map(function($a){ return $a['name'];},$examsAbroadMasterList);
						$examAbroadWithScoreGreterThanZero = array();
						foreach($examsAbroad as $examName => $examScore)
						{
								if(!in_array($examName,$abroadExamNameList))
								{
									unset($examsAbroad[$examName]);
						        }
								elseif($examScore !='' && $examScore >=0)
								{
									if($examName !='IELTS'){
									   $examAbroadWithScoreGreterThanZero[$examName]=(int)$examScore;
									}else{
									   $examAbroadWithScoreGreterThanZero[$examName]= $examScore;			
									}
								}	
						}
						//because now we consider only exam which have score greater than zero
						$examsAbroad = $examAbroadWithScoreGreterThanZero;
						
						
						if(isset($fields['examTaken'])){
								if(!empty($abroadShortRegistrationData['examsAbroad']) && count($examsAbroad)>0) {
									$examTaken = 'yes';
									$abroadExamFlag = 1;
								}
								else if(!empty($abroadShortRegistrationData['passport']) && $abroadShortRegistrationData['bookedExamDate'] !=1) {
									$examTaken = 'no';
									$abroadExamFlag = 0;
								}else if($abroadShortRegistrationData['bookedExamDate']==1){
									$examTaken = 'bookedExamDate';
									$abroadExamFlag = -1;
								}
						?>
						<div class="flLt">
							<div <?php echo $registrationHelper->getBlockCustomAttributes('examTaken'); ?>>
								<div class="flLt signUp-child-wrap" style="padding-left:0; font-size:12px !important;">
									<p class="mb8">Have you given any study abroad exam?</p>
									<div class="columns">
										<input type="radio" id="examTaken_yes_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
										<label for="examTaken_yes_<?php echo $regFormId; ?>">
											<span class="common-sprite"></span>
											<p><strong style="font-size:12px !important;">Yes</strong></p>
										</label>
									</div>
									<div class="columns">
										<input type="radio" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
										<label for="examTaken_no_<?php echo $regFormId; ?>">
											<span class="common-sprite"></span>
											<p><strong style="font-size:12px !important;">No</strong></p>
										</label>
									</div>
									<div class="columns" style="width:135px;">
												<input type="radio" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="bookedExamDate" <?php if(!empty($examTaken) && $examTaken == 'bookedExamDate') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
												<label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>">
													<span class="common-sprite"></span>
													<p><strong style="font-size:12px !important;"><?php echo $fields['bookedExamDate']->getLabel(); ?></strong></p>
												</label>
								    </div>
									<input type="hidden" name="bookedExamDate" id="bookedExamDate_<?php echo $regFormId; ?>" value="<?php echo ($examTaken=='bookedExamDate'?1:0); ?>"/>
									<div class="clearfix"></div>
									<div>
										<div class="errorMsg" id="examTaken_error_<?php echo $regFormId; ?>"></div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
									<?php } ?>
									<?php if(isset($fields['passport'])){
									$visible = 'No';
									$style = 'display:none;padding-left:0;font-size:12px !important;';
									if(!empty($abroadShortRegistrationData['passport'])) {
										$passport = $abroadShortRegistrationData['passport'];
										if($examTaken == 'no') {
											$visible = 'Yes';
											$style = 'display:block;margin-bottom:0;';
										}
									}
									?>
									<div class="account-setting-fields setting-child-wrap flLt" style="padding-left:0px;width: 100%;" <?php echo $registrationHelper->getBlockCustomAttributes('passport', array('visible' => $visible, 'style' => $style)); ?>>
									<label style="margin-bottom:8px;">Do you have a passport?</label>
									   <div class="columns">
										<input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
										<label for="passport_yes_<?php echo $regFormId; ?>">
											<span class="common-sprite"></span>
											<p><strong style="font-size:12px !important;">Yes</strong></p>
										</label>
									</div>
									<div class="columns">
										<input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
										<label for="passport_no_<?php echo $regFormId; ?>">
											<span class="common-sprite"></span>
											<p><strong style="font-size:12px !important;">No</strong></p>
										</label>
									</div>
									<div class="clearfix"></div>
									<div>
											<div id="passport_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
									</div>
								   </div>
							   
						<?php  } ?>
						</div>	
						<?php
									if(isset($fields['examsAbroad'])){
											$visible = 'No';
											$style = 'display:none;margin-bottom:0;width:100%;';
											if(!empty($abroadShortRegistrationData['examsAbroad'])) {
												$visible = 'Yes';
												$style = 'display:block;margin-bottom:0;width:100%;';
											}
							//_p($examsAbroad);
							//die;
						    ?>
						<div class="account-setting-fields flRt">
						<div class="account-setting-fields" <?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad', '', array('visible' => $visible, 'style' => $style)); ?>>
							<label>Given any study abroad exam?</label>
									<?php
									global $examGrades;
									global $examFloat;
									$count = 0;
									$display = false;
									
									$examListByName = array();
									foreach($examsAbroadMasterList as $id=>$value){
										if($value['scoreType']=='grades'){
											$examListByName[$value['name']] = explode(',',$value['range']);		
										}
											
									}
									$count = 0;
									if($examsAbroad !='' && count($examsAbroad) >0){?>
									
									<input type="hidden" name="examTobeDeleted" value='<?php  echo json_encode(array_keys($examsAbroad))?>' />	
									<?php foreach($examsAbroad as $examName => $examScore)
									{
												if(in_array($examName,array_keys($examListByName))){
														$keyOfrange = ((int)$examScore)-1;	
														$examScore = $examListByName[$examName][$keyOfrange];
												}
									$scoreType = '';			
									?>
									<div class="accnt-setting-exam-col examContainer">
									   <p class="editlink" class="accnt-setting-mail-info"><strong><?= $examName.": ".$examScore;?></strong> <a href="javascript:void(0);" onclick="editExam('examDetail_<?= $count?>','<?= $count?>','<?php echo $regFormId; ?>');" class="setting-edit-link">Edit</a></p>
										<div class="examdetailcontainer" id="examDetail_<?= $count?>" style="display: none;">
												<div class="custom-dropdown flLt" style="width:50%; margin-right:5px;">
												   <select examid="<?= $count?>" name="exams[]" class="universal-select" id="exam_<?= $count; ?>_<?php echo $regFormId; ?>" style="padding:5px;"  onchange="manageExamScoreField('<?= $count; ?>','<?php echo $regFormId; ?>');">
													  <option value="">Select Exam</option>
													  <?php foreach($examsAbroadMasterList as $key=>$examDetail){?>
													   <option value="<?= $examDetail['name'];?>" <?php if(strtolower($examDetail['name'])==strtolower($examName)){ echo 'selected="selected"';$scoreType=$examDetail['scoreType']; }?> maxscore="<?= $examDetail['maxScore'];?>" minscore="<?= $examDetail['minScore'];?>" range="<?= $examDetail['range'];?>" scoretype="<?= $examDetail['scoreType'];?>"><?= $examDetail['name'];?></option>
													  <?php } ?>
												   </select>
											   </div>
												<div class="flLt" style="width:38%;">
													<input type="text" id="<?= $count;?>_score_<?php echo $regFormId; ?>" name="<?= $examName; ?>_score" exam="<?= $examName; ?>" class="universal-text examScore" maxlength="5" minscore="" maxscore="" range="" caption="<?php echo $examName; ?> score" default="Exam Score" value="<?php echo $examScore; ?>" onblur="" onfocus="">
													<input type="hidden" id="<?php echo $count; ?>_scoreType_<?php echo $regFormId; ?>" name="<?php echo $examName; ?>_scoreType" value="<?= $scoreType;?>">
												</div>
												<div class="remove-icon-2"><a href="javascript:void(0);" onclick="removeExamBlock(this);">&times;</a></div>
												<div class="clear">
														 <div class="errorMsg" id="<?php echo $count; ?>_score_error_<?php echo $regFormId; ?>"></div>
												</div>
												<div class="clearfix"></div>
									   </div>
									</div>		
									<?php $count++;}}
									else{
												?>
									<div class="accnt-setting-exam-col examContainer">
										<div class="examdetailcontainer" id="examDetail_<?= $count?>">
												<div class="custom-dropdown flLt" style="width:50%; margin-right:5px;">
												   <select examid="<?= $count?>" name="exams[]" class="universal-select" id="exam_<?= $count; ?>_<?php echo $regFormId; ?>" style="padding:5px;" <?php echo $registrationHelper->getFieldCustomAttributes('examsAbroad'); ?> onchange="manageExamScoreField('<?= $count; ?>','<?php echo $regFormId; ?>');">
													  <option value="">Select Exam</option>
													  <?php foreach($examsAbroadMasterList as $key=>$examDetail){?>
													   <option value="<?= $examDetail['name'];?>"  maxscore="<?= $examDetail['maxScore'];?>" minscore="<?= $examDetail['minScore'];?>" range="<?= $examDetail['range'];?>" scoretype="<?= $examDetail['scoreType'];?>"><?= $examDetail['name'];?></option>
													  <?php } ?>
												   </select>
											   </div>
												<div class="flLt" style="width:38%;">
													<input type="text" id="<?= $count;?>_score_<?php echo $regFormId; ?>" disabled="disabled" placeholder="Exam Score" exam="" class="universal-text examScore disable-field" maxlength="5" minscore="" maxscore="" range="" caption="" default="Exam Score" value="" name="" onblur="" onfocus="">
													<input type="hidden" id="<?php echo $count; ?>_scoreType_<?php echo $regFormId; ?>"  name="" value="">
												</div>
												<div class="remove-icon-2"><a href="javascript:void(0);" onclick="removeExamBlock(this);">&times;</a></div>
												<div class="clear">
														 <div class="errorMsg" id="<?php echo $count; ?>_score_error_<?php echo $regFormId; ?>"></div>
												</div>
												<div class="clearfix"></div>
									   </div>
									</div>
									<?php } ?>
									<div class="clearfix"></div>
								<div>
									<div id="examsAbroad_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
								</div>
								<a style="<?=(count($examsAbroad)>=6)?'display:none;':""; ?>;width:80px;" href="javascript:void(0);" onclick="addAnotherExam('<?php echo $regFormId; ?>');" class="font-11 add-another-link">add another</a>		
								</div>			
					<?php } ?>
							
						</div>
						<div class="clearfix"></div>
					</li>
				 </ul>
			</div>
<script>
		var abroadExamFlag	 = "<?= $examTaken;?>";
</script>
