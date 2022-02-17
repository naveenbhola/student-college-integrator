<ul class="bro-form-list customInputs" style="margin-bottom:0px; padding: 10px 15px;">
<li style="margin-bottom: 10px;">
<div><strong>A. Study Preferences</strong></div>
</li>
<li>
<?php
    if(isset($fields['examTaken'])){

    		//we need only abroad exam here so remove all other custom or national exam
	    	$examsAbroadMasterList = $fields['examsAbroad']->getValues();
	    	$abroadExamNameList = array_map(function($a){ return $a['name'];},$examsAbroadMasterList);
	    	foreach ($abroadShortRegistrationData['examsAbroad'] as $key => $value) 
	    	{
	    		if(! in_array($key, $abroadExamNameList)){
	    			unset($abroadShortRegistrationData['examsAbroad'][$key]);
	    		}
	    	}

            if(!empty($abroadShortRegistrationData['examsAbroad'])) {
                $examTaken = 'yes';
            }
            else if(!empty($abroadShortRegistrationData['passport']) && $abroadShortRegistrationData['bookedExamDate'] !=1) {
                $examTaken = 'no';
            }
			else if($abroadShortRegistrationData['bookedExamDate'] ==1)
			{
			    $examTaken = 'bookedExamDate';
			}
	    
	    /* if examtaken was choosen as no user can always have the option to change it to yes & add exam scores
	     * however in case of exam taken already choosen as yes, user cannot change it back to no
	     * hence in such a case the radio button for exam taken will be disabled
	     */
	    if($examTaken == 'yes')
	    {
			$disabledRadio = 'disabled = "disabled"';
	    }
?>
	    <div class="flLt signUp-child-wrap bro-column-1" style= "padding:0">
			<div <?php echo $registrationHelper->getBlockCustomAttributes('examTaken'); ?>>
			    <div class="flLt" style="padding-left:0; font-size:12px !important;">
				<div style="margin-bottom:5px;">Have you given any study abroad exam?</div>
				<div class="columns">
				    <input type="radio" id="examTaken_yes_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" <?=($disabledRadio)?>>
				    <label for="examTaken_yes_<?php echo $regFormId; ?>">
					<span class="common-sprite"></span>
					<p style="margin-top:0"><strong style="font-size:12px !important;">Yes</strong></p>
				    </label>
				</div>
				<div class="columns">
				    <input type="radio" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" <?=($disabledRadio)?>>
				    <label for="examTaken_no_<?php echo $regFormId; ?>">
					<span class="common-sprite"></span>
					<p style="margin-top:0"><strong style="font-size:12px !important;">No</strong></p>
				    </label>
				</div>
				<div class="columns" style="width:135px;">
					<input type="radio" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="bookedExamDate" <?php if(!empty($examTaken) && $examTaken == 'bookedExamDate') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" <?=($disabledRadio)?>>
					<label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>">
						<span class="common-sprite"></span>
						<p style="margin-top:0"><strong style="font-size:12px !important;"><?php echo $fields['bookedExamDate']->getLabel(); ?></strong></p>
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
            
        
<?php
    }
    if(isset($fields['examsAbroad'])){
            $visible = 'No';
            $style = 'display:none;margin-bottom:0;';
            if(!empty($abroadShortRegistrationData['examsAbroad'])) {
                $examsAbroad = $abroadShortRegistrationData['examsAbroad'];
                $visible = 'Yes';
                $style = 'display:block;margin-bottom:0;';
            }
?>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad', '', array('visible' => $visible, 'style' => $style)); ?>>
                
		    <p class="mb8" style="font-size:13px;">Select & enter your exam score</p>
                    <ul class="customInputs-large">
                        <?php
				global $examGrades;
				global $examFloat;
				$count = 0;
				$display = false;
				
				$examsAbroadMasterList = $fields['examsAbroad']->getValues();
				foreach($examsAbroadMasterList as $examId => $exam) {
				    $count++;
				    if(!empty($examsAbroad) && isset($examsAbroad[$exam['name']])) {
					if($count > 4) {
					    $display = true;
					    break;
					}
				    }
				}
				
				if($display) {
				    $displayExam = 'display:block;';
				    $displayMore = 'display:none;';
				}
				else {
				    $displayExam = 'display:none;';
				    $displayMore = 'display:block;';
				}
				
                                $count = 0;
                                foreach($examsAbroadMasterList as $examId => $exam) {
                                    $count++;
				    $attrChecked = '';
				    $display = '';
				    $checked = '';
				    $disabled = '';
				    $value = 'Score';
				    $labelFor = 'exam_'.$exam['name'].'_'.$regFormId;
				    if(!empty($examsAbroad) && isset($examsAbroad[$exam['name']])) {
					$checked = 'filled = "1"';
					if($checked != '')
					{
						$disabled = 'disabled="disabled"';
					}
					else{
						$disabled = '';
					}
					
					if(isset($examGrades[$exam['name']])) {
					    $value = $examGrades[$exam['name']][(int)$examsAbroad[$exam['name']]];
					}
					else {
					    $value = $examsAbroad[$exam['name']];
					    if($examFloat[$exam['name']] !== TRUE) {
						$value = (int)$value;
					    }
					}
					
					$labelFor = '';
				    }

				    if($count % 2 == 1) { echo '<li id="examAbroadBlock_'.$regFormId.'_'.(($count+1)/2).'">'; } 
                        ?>
                                        <div <?php if($count % 2 == 1) { echo 'class="flLt mR30 examBlock-width"'; } else { echo 'class="flLt examBlock-width"'; } ?>>
                                            <div class="flLt exam-name">
                                                <input type="checkbox" name="exams[]" class="examsAbroad_<?php echo $regFormId; ?>" <?php echo $attrChecked; ?> <?=($disabled)?> id="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"  value="<?php echo $exam['name']; ?>" <?php echo $checked; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examsAbroad'); ?>>
                                                <label for="<?php echo $labelFor; ?>"><span class="common-sprite" <?php echo $attrChecked; ?>></span><?php echo $exam['name']; ?></label>
                                            </div>
                                            <input type="text" id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>" exam="<?php echo $exam['name']; ?>" class="universal-text text-width disable-field" disabled="disabled" maxlength="4" minscore="<?php echo $exam['minScore']; ?>" maxscore="<?php echo $exam['maxScore']; ?>" range="<?php echo $exam['range']; ?>" caption="<?php echo $exam['name']; ?> score" default="Score" value="<?php echo $value; ?>" regfieldid="<?php echo $exam['name']; ?>_score" name="<?php echo $exam['name']; ?>_score" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);">
					    <input type="hidden" id="<?php echo $exam['name']; ?>_scoreType_<?php echo $regFormId; ?>" regfieldid="<?php echo $exam['name']; ?>_scoreType" name="<?php echo $exam['name']; ?>_scoreType" value="<?php echo $exam['scoreType']; ?>">
					    <div class="clearfix">
						<div class="errorMsg" id="<?php echo $exam['name']; ?>_score_error_<?php echo $regFormId; ?>"></div>
					    </div>
                                        </div>
                        <?php
				    if($count % 2 == 0) { echo '</li>'; }
                                }
                        ?>
                    </ul>
                    <div class="clearfix"></div>
		    <div>
			<div id="examsAbroad_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
		    </div>
                
            </div>
            
<?php
    }
    if(isset($fields['passport']) && $passport != 'yes'){
            $visible = 'No';
            $style = 'display:none;padding-left:0; font-size:12px !important;';
            if(!empty($abroadShortRegistrationData['passport'])) {
                $passport = $abroadShortRegistrationData['passport'];
                if($examTaken == 'no') {
                    $visible = 'Yes';
                    $style = 'display:block;margin-bottom:0;';
                }
            }
	    if(!($formData['userId']>0 && $passport != "")){
?>
            
            <div class="signUp-child-wrap" style='<?php if(!$formData['userId']){ ?>display:none;<?php } ?>	padding-left:0; font-size:12px !important;' <?php echo $registrationHelper->getBlockCustomAttributes('passport', array('visible' => $visible, 'style' => $style)); ?>>
			<p class="mb8" style="font-size:13px;color:#333;">Do you have a valid passport?</p>
			<div class="columns">
			    <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
			    <label for="passport_yes_<?php echo $regFormId; ?>">
				<span class="common-sprite"></span>
				<p style="margin-top:0"><strong style="font-size:12px !important;">Yes</strong></p>
			    </label>
			</div>
			<div class="columns">
			    <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
			    <label for="passport_no_<?php echo $regFormId; ?>">
				<span class="common-sprite"></span>
				<p style="margin-top:0"><strong style="font-size:12px !important;">No</strong></p>
			    </label>
			</div>
                        <div class="clearfix"></div>
			<div>
			    <div id="passport_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
			</div>
                    
            </div>
	    <?php }} ?> 
            
        </div>
<?php
		if(isset($fields['whenPlanToGo'])){
            if(!empty($abroadShortRegistrationData['whenPlanToGo'])) {
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
			$disabled = '';
			$disabledClass = '';
?>        
            
           
                    <div class="flRt bro-column-1" <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
                        <div class="custom-dropdown">
                            <select name="whenPlanToGo" class="universal-select signup-select <?=($disabledClass)?>" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" <?=$disabled?>>
                                <option value="">When you plan to start your course?</option>
                            <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                                <option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($whenPlanToGo) && ($plannedToGoValue == $whenPlanToGo)) echo 'selected="selected"'; ?>><?php echo $plannedToGoText; ?></option>
			    <?php } ?>
                            </select>
                        </div>
                        <div>
                            <div class="errorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
<?php  } ?>                    
</li>

<!---Country HTML STARTS HERE-->
<li>
<?php
if(isset($fields['destinationCountry'])){
        if(!empty($destinationCountry)) {
            $ischecked = $destinationCountry;
        }
	global $studyAbroadPopularCountries;
	$otherCountries = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
	$fullCountryNameList = $studyAbroadPopularCountries; // add popular countries to the list of all countries
	foreach($otherCountries as $otherCountry)
	{ // add other countries as well
	    $fullCountryNameList[$otherCountry->getId()] = $otherCountry->getName();
	}
          //$ischecked = 5; //todo: dynamic
	  if(count($userPreferredDestinations)>=3)
	  {
	    $fields['destinationCountry']->setMandatory(0);
	  }
	  foreach($userPreferredDestinations as $userPreferredDestination){ // each country saved by user
	    if(in_array($fullCountryNameList[$userPreferredDestination],$fullCountryNameList)){
			if(in_array($destinationCountry,$userPreferredDestinations))
			{	// if  it is among 
				    $destinationCountryName = '';
			}
			else{
				    //$comma = $comma==","?$comma:"";
				    $comma  = ",";
			}
	    }
	    else{
			$comma = ",";
	    }
	  }
	  //$comma = ",";
	  foreach($userPreferredDestinations as $loc){
	    $destinationCountryName .= $comma.$fullCountryNameList[$loc];
	    $comma = ",";
}

if(count($userPreferredDestinations)<3){  ?>
            <div class="study-destination clearwidth" <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry','background:#ffffff;'); ?>>
                <p style= "margin:0px; font-size:13px;">Preferred study destination : <span id="twoStepCountrySelectOptionInlineBrochure"><?php echo $destinationCountryName; ?></span> <a class="font-11 add-more-btn" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].nameOfCountriesSelectedInlineBrochure();" href="javascript:void(0);" id="twoStepCountrySelectInlineBrochure">+ add more</a></p>
                
                <div id="twoStepChooseCourseCountryDropDownInlineBrochure" style="position: relative; left: 0; top: 0; width: 100%; background:rgba(0,0,0,0);">
                        
                        <?php $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')); //print_r($desiredCourses);exit;?>
                        
                            <div class="select-opt-layer" style="display:none; width: 435px; z-index: 99;right:0 !important; left:auto; top: 0px !important;" id="twoStepCountryDropdownLayerInlineBrochure" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelectedInlineBrochure(undefined,1);">
                            
                            <div class="scrollbar1" id="twoStepChooseCourseCountryLayerScrollbarInlineBrochure">
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
                                    <div class="overview" style="top: 0px;" id = "twoStepCountryDropdownContInlineBrochure">
                                        <p class="font-9"><strong>TOP COUNTRIES</strong></p>
                                        <ol>
                                            <li>
						<?php 
						foreach($studyAbroadPopularCountries as $key => $popularCountry){?>
                                                    <div class="country-flag-cont <?php if($ischecked==$key || in_array($key,$userPreferredDestinations)) echo 'active'; ?>  <?php if($ischecked!=$key) {echo ' countryClick';}?>" countryName='<?php echo $popularCountry; ?>'>
                                                        <div class="flag-main-box">
                                                            <p class="clearfix"><span class="flags flLt <?php echo str_replace(' ', '', strtolower($popularCountry)); ?>"></span>
                                                            <strong><?php echo $popularCountry; ?></strong></p>
                                                        </div>
                                                        <div class="flag-chkbox">
                                                            <input class="countryLabel" type="checkbox" <?=(in_array($key,$userPreferredDestinations) ?'':'name="destinationCountry[]"' )?> id="<?php echo $popularCountry; ?>-flag" value="<?php echo (/*$ischecked==$key || */in_array($key,$userPreferredDestinations)?'':$key); ?>" <?php if($ischecked==$key || in_array($key,$userPreferredDestinations)) echo 'checked="checked" disabled="disabled"'; ?>>
                                                            <label for="<?php echo $popularCountry; ?>-flag" ><span class="common-sprite"></span></label>
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
                                                if($liCount%3 == 1) {echo '<li>';} ?>
                                                    <div class="flLt country-width <?php if($ischecked==$country->getId() || in_array($country->getId(),$userPreferredDestinations)) echo 'active'; ?> <?php if($ischecked!=$country->getId()){ echo ' countryClick'; } ?>" countryName='<?php echo $country->getName();?>'>
                                                        <input class="countryLabel" type="checkbox" <?=(in_array($country->getId(),$userPreferredDestinations) ?'':'name="destinationCountry[]"' )?> id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" value="<?php echo $country->getId(); ?>" <?php if($ischecked==$country->getId() || in_array($country->getId(),$userPreferredDestinations)) echo 'checked="checked" disabled="disabled"'; ?>>
                                                        <label for="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" class="font-10"><span class="common-sprite"></span> <?php echo $country->getName();?></label>
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
                                    <a href="JavaScript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelectedInlineBrochure();" class="button-style" style="padding: 6px 30px;margin-left:35px;">OK</a>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div style="color: red; display: none;" class="errorMsg" id="twoStepCourseCountryLayer_error<?php echo $regFormId ?>"></div>
                        </div>
			</div>
                
            </div>
            <div class="clearfix"></div>
<?php } } ?>
</li>
<!---Country HTML ENDS HERE-->

<li><div class="form-seprator"></div></li>
<!--Section 1 Ends Here-->



<!--Section 2 Starts Here-->
<li style="margin-bottom: 10px;"><div><strong>B. Personal Info</strong></div></li>
<li>
<?php
if(isset($fields['email'])){
            $email = 'Email Id';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['email'])) {
                $email = $abroadShortRegistrationData['email'];
                $disabled = 'disabled="disabled"';
		$disabledClass = "disable-field";
            }
	    else{
   	        $disabled = '';
		$disabledClass = '';
	    }
?>

			<div class="flLt bro-column-1" <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
			    <input type="text" name="email" id="email_<?php echo $regFormId; ?>" class="universal-text signup-txtfield <?=($disabledClass)?>" maxlength="125" default="Email Id" value="<?php echo $email; ?>" <?php echo $disabled; ?> <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this)" style= "color:#999999;<?=($disabledStyle)?>"/>
			    <div>
				<div class="errorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
			    </div>
			</div>

<?php  }?>

<?php
if(isset($fields['isdCode'])){
            $ISDCode = 'isdCode.';
            $disabled = '';
            $disabledClass = "";
            if(!empty($abroadShortRegistrationData['isdCode'])) {
                $isdCode = $abroadShortRegistrationData['isdCode'];
                $disabled = 'disabled="disabled"';
                $disabledClass = "disable-field";
            }
            $ISDCodeValues = $fields['isdCode']->getValues();
            ?>
            <div class="flRt" style="width:49%;">
            <div class="flLt signup-txtwidth" style="width:42%;" <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?>>
                <div class="custom-dropdown" style="width:100%;">
                    <select class="universal-select signup-select <?=($disabledClass)?>" required="true" validate="validateSelect" caption="ISD Code" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" <?php echo $disabled; ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">                                       
                    	<?php foreach($ISDCodeValues as $key=>$value){ ?>
                            <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected'; error_log("==shiksha== matched");} ?>> <?php echo $value; ?> </option>
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
            $disabledClass = "";
            if(!empty($abroadShortRegistrationData['mobile'])) {
                $mobile = $abroadShortRegistrationData['mobile'];
                $disabled = 'disabled="disabled"';
                $disabledClass = "disable-field";
            }
?>
            <div style="width:56%;" class="flRt signup-txtwidth" <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
		<input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="universal-text signup-txtfield <?=($disabledClass)?>" maxlength="10" minlength="10" default="Mobile No." value="<?php echo $mobile; ?>" <?php echo $disabled; ?> <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
                <div>
                    <div class="errorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                </div>
	    </div>
            <div class="clearfix"></div>
        </div>     
<?php  }?>
 </li>

<li>
<?php 
    if(isset($fields['firstName'])){
            $firstName = 'First Name';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['firstName'])) {
                $firstName = $abroadShortRegistrationData['firstName'];
                $disabled = 'disabled="disabled"';
		$disabledClass = "disable-field";
            }else{
   	        $disabled = '';
		$disabledClass = '';
	    }
?>
	   
			<div class="flLt bro-column-1" <?php echo $registrationHelper->getBlockCustomAttributes('firstName');?>>
			    <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="universal-text signup-txtfield <?=($disabledClass)?>" minlength="1" maxlength="50" default="First Name" value="<?php echo htmlentities($firstName); ?>" <?php echo $disabled; ?> profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
			    <div>
				<div class="errorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
			    </div>
			</div>
            
<?php
    }

    if(isset($fields['lastName'])){
            $lastName = 'Last Name';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['lastName'])) {
                $lastName = $abroadShortRegistrationData['lastName'];
                $disabled = 'disabled="disabled"';
		$disabledClass = "disable-field";
            }else{
   	        $disabled = '';
		$disabledClass = '';
	    }
?>
			<div  class="flLt bro-column-2" <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
			    <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="universal-text signup-txtfield <?=($disabledClass)?>" minlength="1" maxlength="50" default="Last Name" value="<?php echo htmlentities($lastName); ?>" <?php echo $disabled; ?> profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
			    <div>
				<div class="errorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
			    </div>
			</div>
			<div class="clearfix"></div>
<?php } ?>
</li>
<li>
<?php 
    if(isset($fields['residenceCity'])){
	    if($userCity)
	    {
			$disabled = 'disabled="disabled"';
			$disabledClass = "disable-field";
	    }
	    else{
   	        $disabled = '';
		$disabledClass = '';
	    }
?>
            
			<div class="flLt bro-column-1" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
			    <div class="custom-dropdown">
				<select name="residenceCity" class="universal-select signup-select <?=($disabledClass)?>" <?=($disabled)?> id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
				    <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical','formData'=>array('residenceCity'=>$userCity))); ?>
				</select>
			    </div>
			    <div>
				<div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
			    </div>
			</div>
			<div class="clearfix"></div>
	    
<?php } ?>
</li>
<?php 
    if(isset($fields['contactByConsultant'])){
        if($contactByConsultant !== 'yes'){
            $fields['contactByConsultant']->setVisible('Yes');
            $fields['contactByConsultant']->setMandatory('Yes');
        }
        //$contactByConsultant = 'yes'; //hardcoded
?>
        <li <?=($fields['contactByConsultant']->isVisible()=='Yes'?'':'style="display:none;"')?>>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('contactByConsultant'); ?>>
                <div class="flLt signUp-child-wrap" style="padding-left:0; font-size:12px !important;">
                    <p class="mb8">Do you want to be contacted by consultants?</p>
                    <div class="columns">
                        <input type="radio" id="contactByConsultant_yes_<?php echo $regFormId; ?>" class="contactByConsultant_<?php echo $regFormId; ?>" name="contactByConsultant" value="yes" <?php if(!empty($userContactByConsultant) && $userContactByConsultant == 'yes' || $contactByConsultant == "yes") echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <label for="contactByConsultant_yes_<?php echo $regFormId; ?>">
                            <span class="common-sprite"></span>
                            <p style = "margin-top:0px;"><strong style="font-size:12px !important;">Yes</strong></p>
                        </label>
                    </div>
                    <div class="columns">
                        <input type="radio" id="contactByConsultant_no_<?php echo $regFormId; ?>" class="contactByConsultant_<?php echo $regFormId; ?>" name="contactByConsultant" value="no" <?php if(!empty($userContactByConsultant) && $userContactByConsultant == 'no' || $contactByConsultant == "no") echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <label for="contactByConsultant_no_<?php echo $regFormId; ?>">
                            <span class="common-sprite"></span>
                            <p style = "margin-top:0px;"><strong style="font-size:12px !important;">No</strong></p>
                        </label>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <div class="errorMsg" id="contactByConsultant_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        

<?php 

    }
    
    if(isset($fields['fieldOfInterest']) && $fieldOfInterest){
        
?>  
            <input type="hidden" name='fieldOfInterest' value="<?php echo $fieldOfInterest; ?>" />
            
<?php
    }

    if(isset($fields['abroadDesiredCourse'])){
        
?>  
            <input type="hidden" name='desiredCourse' id='desiredCourseForResponseBottom' value="<?php echo $desiredCourse; ?>" />
            
<?php
    }
    
    if(isset($fields['desiredGraduationLevel']) && $desiredGraduationLevel){
?>  
            
        <input type="hidden" name='desiredGraduationLevel' value="<?php echo $desiredGraduationLevel; ?>" />
        
<?php
    }
?>  
        <input type="hidden" id='isPaidBottom' name='isPaid' value="<?php echo $isPaid; ?>" />
        
<?php
    if(isset($fields['abroadSpecialization'])){
?>
        <input type="hidden" name='abroadSpecialization' id='abroadSpecializationForResponseBottom' value="<?php echo $specialization; ?>" />
        
<?php
    }
    
    if(isset($fields['destinationCountry'])){
        if(!empty($destinationCountry)) {
            $ischecked = $destinationCountry;
        }
	global $studyAbroadPopularCountries;//_p($userPreferredDestinations); <-- this are destinations user has saved
	// get other countries
	$otherCountries = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
	$fullCountryNameList = $studyAbroadPopularCountries; // add popular countries to the list of all countries
	foreach($otherCountries as $otherCountry)
	{ // add other countries as well
	    $fullCountryNameList[$otherCountry->getId()] = $otherCountry->getName();
	}
          //$ischecked = 5; //todo: dynamic
	  if(count($userPreferredDestinations)>=3)
	  {
	    $fields['destinationCountry']->setMandatory(0);
	  }
	  foreach($userPreferredDestinations as $userPreferredDestination){ // each country saved by user
	    if(in_array($fullCountryNameList[$userPreferredDestination],$fullCountryNameList)){
			if(in_array($destinationCountry,$userPreferredDestinations))
			{	// if  it is among 
				    $destinationCountryName = '';
			}
			else{
				    //$comma = $comma==","?$comma:"";
				    $comma  = ",";
			}
	    }
	    else{
			$comma = ",";
	    }
	  }
	  //$comma = ",";
	  foreach($userPreferredDestinations as $loc){
	    $destinationCountryName .= $comma.$fullCountryNameList[$loc];
	    $comma = ",";
	  }
?>
 </li>
 <li><div class="form-seprator"></div></li>
<!--Section 2 Ends Here-->



<!--Section 3 Starts Here-->
	
<li style="margin-bottom: 10px;">
<style>
 .bold-title{font-weight:bold;}
</style>
<div class="signup-fields clearwidth" style="margin-top:0; border: none;" id="saApplyArea_<?= $regFormId?>">

<script>
function showEducationFieldsByDesiredCourse()
{
			<?php  if($desiredCourse!=''){ ?>
					var postData = {'regFormId':'<?= $regFormId?>',
									'ldbCourseId':'<?= $desiredCourse;?>',
									'context':'oneStepAbroad'
									}
									
						$j.ajax({
						url : '/registration/Forms/loadSAFieldsByLDBCourse',
						type: 'POST',
						data : postData,
						success: function(response){
						  $j('#saApplyArea_<?= $regFormId?>').show();		
						  $j('#saApplyArea_<?= $regFormId?>').html(response);
						}
					  });
			<?php } ?>
}
</script>			


</div>		
</li>
<!--Section 3 Ends Here-->
 
<?php } ?>
</ul>

<div class="clearfix"></div>
