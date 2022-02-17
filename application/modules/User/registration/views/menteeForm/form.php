<?php
$isLoggedIn = false;
if($formData['userId'] > 0) {
    $isLoggedIn = true;
}
?>
<form action="/registration/Registration/register" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<ul>
    <li>
    <div class="enroll-detail-col">
            <label class="dafault-pointer">First Name <span style="color:#ff0000;">*</span></label>
            <input type="text" class="enroll-detailField" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" minlength="1" validate="validateDisplayName" maxlength="50" caption="first Name" profanity="1"  required = "true">
             <div class="menterror"><div id="firstName_<?php echo $regFormId; ?>_error"></div></div>
    </div>
    <div class="enroll-detail-col">
            <label class="dafault-pointer">Last Name <span style="color:#ff0000;">*</span></label>
            <input type="text" class="enroll-detailField" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" class="predictor-textfield" minlength="1" validate="validateDisplayName" maxlength="50" caption="last Name" profanity="1" required = "true">
            <div class="menterror"><div id="lastName_<?php echo $regFormId; ?>_error"></div></div>
    </div>
    <div class="enroll-detail-col last">
            <label class="dafault-pointer">Personal Email ID <span style="color:#ff0000;">*</span></label>
            <input type="text" class="enroll-detailField" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" maxlength="125" validate="validateEmail" caption="email id" required = "true" autocomplete="off">
            <div class="menterror" id="email_mnt<?php echo $regFormId; ?>"><div id="email_<?php echo $regFormId; ?>_error"></div></div>
    </div>
</li>
<li>
    <div class="enroll-detail-col">
            <label class="dafault-pointer">Current City <span style="color:#ff0000;">*</span></label>
            <?php
                                        $objn = new \registration\libraries\FieldValueSources\ResidenceCityLocality;
                                        $city = $objn->getValues();
                                        $values = $city;
                                        ?>
                                        <span id="residentCityParentDiv_<?php echo $regFormId; ?>" >
                                        <select class="mentor-select-field-2" id="residenceCity_<?php echo $regFormId; ?>" caption="city of residence" label="Residence Location" mandatory="1" regfieldid="residenceCity" name="residenceCity" validate="validateSelect" required = "true">
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

        
            <div class="menterror"><div id="residenceCity_<?php echo $regFormId; ?>_error"></div></div>
                    </span>
                    <span id="emptyResidentCity_<?php echo $regFormId; ?>" style="display:none;">
                 <select class="mentor-select-field-2" >
                    <option value="-1" > City not required </option>
                 </select>
             </span>
    </div>

    <div class="enroll-detail-col">

<?php
                $isdCode = new \registration\libraries\FieldValueSources\IsdCode;
                $isdCode = $isdCode->getValues();
                $countryISD = \registration\libraries\RegistrationHelper::getUserCountryByIP(); 

        ?>
                <div class="flLt" style="width:43%">
                    <label>Country Code <span style="color:#ff0000;">*</span></label>
                    <select style="width:104px;" class="mentor-select-field-2" required="true" validate="validateSelect" caption="ISD Code" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" onchange="changeMobileFieldmaxLength(this.value, 'mobile_<?php echo $regFormId; ?>');shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMenteeForm(this.value);">
                                       
                    <?php foreach($isdCode as $key=>$value){ ?>
                        <option value="<?php echo $key; ?>" <?php if($countryISD == $key){ echo 'selected';} ?>> <?php echo $value; ?></option>
                    <?php } ?>
                                        
                    </select>
                    <div style="display:none;"><div class="errorMsg" id="isdCode_<?php echo $regFormId; ?>_error" style="*float:left"></div></div>
                </div>
                <div class="flRt" style="width:57%;">
                    <label class="dafault-pointer">Mobile Number <span style="color:#ff0000;">*</span></label>
                    <input type="text" class="enroll-detailField" name="mobile" id="mobile_<?php echo $regFormId; ?>" regFieldId="mobile" maxlength="10" validate="validateMobileInteger" minlength="10" caption="mobile no" required = "true" autocomplete="off" style="width:146px; padding:6px 3px;">
                    <div class="menterror"><div id="mobile_<?php echo $regFormId; ?>_error"></div></div>
                </div>
    </div>
</li>

                            <input type="hidden" value="2" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>">
                            <input type="hidden" value="52" name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>">
                            <input type="hidden" value="<?php echo date('Y');?>" name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>">
                            <input type="hidden" value="" name="exams[]" id="JEE Mains_<?php echo $regFormId; ?>">
                            <input type="hidden" value="" name="JEE_MAINS_scoreType" value="Rank">
                            
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
<input type='hidden' id='referrer' name='referrer' value='<?php echo SHIKSHA_HOME;?>/mentors-engineering-exams-colleges-courses'/>
<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
<input type='hidden' id='isMR' name='isMR' value='YES' />
<input type='hidden' id='isCompareEmail' name='isCompareEmail' value='<?php echo $isCompareEmail; ?>' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value="true"/>
<input type="hidden" id="tracking_keyid" name="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
</form>
<?php $this->load->view('registration/common/jsInitialization'); ?>
</div>
<script>	
    try{
	 addOnBlurValidate(document.getElementById('registrationForm_<?php echo $regFormId; ?>'));
	} catch (ex) {
	}
</script>