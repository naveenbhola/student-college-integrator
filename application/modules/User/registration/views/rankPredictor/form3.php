<?php
$isLoggedIn = false;
if($formData['userId'] > 0) {
    $isLoggedIn = true;
}
?>
<div style="text-align: left !important;">
<form action="/registration/Registration/register" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<ul>
<li>
                            	<div class="predictor-column flLt">
                                	<label><?php echo $rpConfig[$examName]['inputField']['score']['label'];?> </label><br />
                                	<input type="text" class="predictor-textfield" id="rankPredictor_Score<?php echo $regFormId; ?>" name="rankPredictor_Score" maxlength="<?php echo $rpConfig[$examName]['inputField']['score']['maxLength'];?>" minlength="<?php echo $rpConfig[$examName]['inputField']['score']['minLength'];?>" <?php if($rpConfig[$examName]['inputField']['score']['isAllowedDecimal'] == 'YES'){?> validate="validateFloat" onkeyup="manageFloat($j(this));" <?php }else{?> validate="validateInteger" <?php }?> caption="valid score" required = "true"/>
				    <div style="display:none;" id="scrError"><div class="predictor-errmsg" id="rankPredictor_Score<?php echo $regFormId; ?>_error"></div></div>
                                </div>
				
				<div class="predictor-column flRt" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity',''); ?>>
                                	<label>Current City</label><br />
                                	<?php
                                        $objn = new \registration\libraries\FieldValueSources\ResidenceCityLocality;
                                        $city = $objn->getValues();
                                        $values = $city;
                                        ?>
                                        <span id="residentCityParentDiv_<?php echo $regFormId; ?>" >
                                        <select class="predictor-select-field" id="residenceCityLocality_<?php echo $regFormId; ?>" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" caption="city of residence" label="Residence Location" mandatory="1" regfieldid="residenceCityLocality" name="residenceCityLocality" validate="validateSelect" required = "true">
					    <option value="">Select City</option>
                                    	<?php
					$virtualCities = $values['virtualCities'];
					foreach ($values['virtualCities'] as $virtualCity) {
					    echo '<optgroup label="'.$virtualCity['name'].'">';
					    foreach ($virtualCity['cities'] as $city) {
						if ($city['virtualCityId'] != $city['city_id']) {
						    $selected = '';
						    if (($residenceCity == $city['city_id']) && ($mmpFormId)) {
							$selected = 'selected = "selected"';
						    }
						    echo '<option '.$selected.' value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
						}
					    }
					    echo '</optgroup>';
					}
				    
					echo '<optgroup label="Metro Cities">';
					foreach ($values['metroCities'] as $city) {
					    $selected = '';
					    if (($residenceCity == $city['cityId']) && ($mmpFormId)) {
						$selected = 'selected = "selected"';
					    }
					    echo '<option '.$selected.' value="'.$city['cityId'].'">'.$city['cityName'].'</option>';
					}
					echo '</optgroup>';
				    
					foreach ($values['stateCities'] as $stateCitiesData) {
					    echo '<optgroup label="'.$stateCitiesData['StateName'].'">';
					    foreach ($stateCitiesData['cityMap'] as $city) {
						$selected = '';
						if (($residenceCity == $city['CityId']) && ($mmpFormId)) {
						    $selected = 'selected = "selected"';
						}
						echo '<option '.$selected.' value="'.$city['CityId'].'">'.$city['CityName'].'</option>';
					    }
					    echo '</optgroup>';
					}
					?>
                                         </select>
                                <div><div id="residenceCityLocality_<?php echo $regFormId; ?>_error" class="predictor-errmsg"></div></div>
                            </span>
                            <span id="emptyResidentCity_<?php echo $regFormId; ?>" style="display:none;">
                             <select class="predictor-select-field" >
                                <option value="-1" > City not required </option>
                             </select>
                                </div>
                                <div class="clearFix"></div>
                            </li>
                            <li>
				<div class="predictor-column flLt" <?php echo $registrationHelper->getBlockCustomAttributes('firstName', ''); ?>>
                                	<label>First Name </label><br />
                                        <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" class="predictor-textfield" minlength="1" validate="validateDisplayName" maxlength="50" caption="first Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"  required = "true" />
					<div><div class="predictor-errmsg" id="firstName_<?php echo $regFormId; ?>_error"></div></div>
                                </div>
				
                            	<div class="predictor-column flRt" <?php echo $registrationHelper->getBlockCustomAttributes('lastName', ''); ?>>
                                	<label>Last Name</label><br />                                        
                                         <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" class="predictor-textfield" minlength="1" validate="validateDisplayName" maxlength="50" caption="last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"  required = "true"/>
                                        
				    <div><div class="predictor-errmsg" id="lastName_<?php echo $regFormId; ?>_error"></div></div>
                                </div>
                                <div class="clearFix"></div>
                            </li>
                            <li>
				<div class="predictor-column flLt" <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
                                	<label>Email ID</label><br />	
                                        <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="predictor-textfield" maxlength="125" validate="validateEmail" caption="email id" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" <?php if($context == 'registerResponseLPR' || $context == 'default' || $context == 'askQuestionBottom') { ?> onblur="registration_context = '<?php echo $context;?>';shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : false; shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?php } else { ?> onblur="if(typeof(registration_context) != 'undefined' && registration_context == 'MMP') { delete registration_context; }shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?php } ?>  required = "true" autocomplete="off"/>
                                        
				    <div id="email_rpr<?php echo $regFormId; ?>"><div class="predictor-errmsg" id="email_<?php echo $regFormId; ?>_error"></div></div>
                                </div>
				
                            	<div class="predictor-column flRt">
                                    <div style="width:48%" class='flLt' <?php echo $registrationHelper->getBlockCustomAttributes('isdCode','');?>>

                            <?php 
                                $isdCode = new \registration\libraries\FieldValueSources\IsdCode;
                                $isdCode = $isdCode->getValues();
                                $userCountry = \registration\libraries\RegistrationHelper::getUserCountryByIP(); 

                            ?>

                            <label>Country Code </label><br />
                            <select class="predictor-select-field" style="width:100%" name="isdCode" id="isdCode_<?php echo $regFormId; ?>" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" required="true" validate="validateSelect" caption="ISD Code" onmouseover="showTipOnline('Please select your ISD Code',this);" onmouseout="hidetip();" onchange="changeMobileFieldmaxLength(this.value, 'mobile_<?php echo $regFormId; ?>');shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMenteeForm(this.value);">
                                               
                            <?php foreach($isdCode as $key=>$value){ ?>
                                <option value="<?php echo $key; ?>" <?php if($countryISD == $key){ echo 'selected';} ?>> <?php echo $value; ?></option>
                            <?php } ?>
                                                
                            </select>
                            <div style="display:none;"><div class="errorMsg" id="isdCode_<?php echo $regFormId; ?>_error" style="*float:left"></div></div>
                        </div>
                        <div style="width:48%" class='flRt' <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
                                	<label>Mobile No.</label><br />
                                        <input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" regFieldId="mobile" class="predictor-textfield" maxlength="10" validate="validateMobileInteger" minlength="10" caption="mobile no" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"  required = "true" autocomplete="off"/>
                                        <div><div class="predictor-errmsg" id="mobile_<?php echo $regFormId; ?>_error"></div></div>
                                </div>
                                <div class="clearFix"></div>
                            </div>
                            </li>

                            <li>
                
                <div style="position:relative;" class="predictor-column flLt" <?php echo $registrationHelper->getBlockCustomAttributes('password', ''); ?>>
                    <label>Password</label><br />  
                    <div class="predictor-textfield ph">                                      
                        <input type="password" class="pred-passFld" name="password" caption="password" id="password_<?php echo $regFormId; ?>" regFieldId="password" validate="validateStr" minlength="6" maxlength="25" mandatory="1" <?php echo $registrationHelper->getFieldCustomAttributes('password'); ?> required="true" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" style="color:#111111;" />
                    </div>
                    <p class="hideTextPredictor" onclick="toggleShowPassword(this);">Show</p>               
                    <div>
                        <div class="predictor-errmsg" id="password_<?php echo $regFormId; ?>_error"></div>
                    </div>
                </div>
                
                <div class="clearFix"></div>

            </li>

                            <li style="text-align: center !important;">
                            	<a href="javascript:void(0);" class="predict-rank-btn" id="registrationSubmit_<?php echo $regFormId; ?>" onclick="$j('#registrationSubmit_<?php echo $regFormId; ?>').addClass('disabled');if(validateFields($('registrationForm_<?php echo $regFormId; ?>')) != true){$j('#registrationSubmit_<?php echo $regFormId; ?>').removeClass('disabled');return false;}else{if(validateScore('<?php echo $regFormId;?>') !=true){$j('#registrationSubmit_<?php echo $regFormId; ?>').removeClass('disabled');return false;}else{rankNewUser('<?php echo $regFormId; ?>');}}">Predict <?php echo $rpConfig[$examName]['examName'];?> Rank</a>
                            </li>
                            
                            <!-- <input type="hidden" value="<?php // echo $rpConfig[$examName]['fieldOfInterest'];?>" name="fieldOfInterest" id="fieldOfInterest_<?php // echo $regFormId; ?>">
                            <input type="hidden" value="<?php // echo $rpConfig[$examName]['desiredCourse'];?>" name="desiredCourse" id="desiredCourse_<?php // echo $regFormId; ?>"> -->
                            <input type="hidden" value="<?php echo $rpConfig[$examName]['stream'];?>" name="stream" id="stream_<?php echo $regFormId; ?>">
                            <input type="hidden" value="<?php echo $rpConfig[$examName]['baseCourse'];?>" name="baseCourses[]" id="baseCourseField_<?php echo $regFormId; ?>">
                            <input type="hidden" value="<?php echo $rpConfig[$examName]['educationType'];?>" name="educationType[]" id="educationType_<?php echo $regFormId; ?>">
                            <input type="hidden" value="2015" name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>">
                            <input type="hidden" value="<?php echo $rpConfig[$examName]['examFieldList'];?>" name="exams[]" id="<?php echo $rpConfig[$examName]['examFieldListId'];?>_<?php echo $regFormId; ?>">
                            <input type="hidden" name="<?php echo $rpConfig[$examName]['examFieldList'];?>_scoreType" value="Rank">
                            <input type="hidden" name="flow" value="course">
                            
</ul>

<?php
    if($userIdOffline){
        echo "<input type='hidden' id='userId' name='userId' value='$userId' />";
        echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
    }
?>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $customRegistrationSource ? $customRegistrationSource : "MARKETING_FORM"; ?>' />
<input type='hidden' id='referrer' name='referrer' value='<?php echo SHIKSHA_HOME;?>/<?php echo $examName;?>-rank-predictor'/>
<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
<input type='hidden' id='isMR' name='isMR' value='YES' />
<input type='hidden' id='isCompareEmail' name='isCompareEmail' value='<?php echo $isCompareEmail; ?>' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value="true"/>
<input type="hidden" id="tracking_keyid_<?php echo $regFormId?>" name="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
</form>
<?php $this->load->view('registration/common/jsInitialization'); ?>
</div>
<script>	
    try{
	 addOnBlurValidate(document.getElementById('registrationForm_<?php echo $regFormId; ?>'));
	} catch (ex) {
	}
</script>
