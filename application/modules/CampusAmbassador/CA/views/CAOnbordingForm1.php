<form id="CAProfileForm" action="/CA/CampusAmbassador/submitCAprofileData" accept-charset="utf-8" method="post" enctype="multipart/form-data"  novalidate="novalidate" name="CAProfileForm" id="CAProfileForm">
    <input name="userid" value="<?php echo $userId?>" type="hidden" />
    <input name="inst_url" id="inst_url" value="<?php echo $landingPageUrl;?>" type="hidden" />
    <input name="edit" value="<?php echo $edit;?>" type="hidden" id="formStatusCA"/>
    <input id="qualification_str" value="1" type="hidden" />
    <input id="CASubCategoryId" value="23" type="hidden" />
    <input name="uniqueId" id="uniqueId" value="<?php echo $id;?>" type="hidden" />
    <input type='hidden' name='tracking_keyid' id='tracking_keyid' value='<?php echo $trackingPageKeyId?>'>
    <input id="CAProgramId" value="<?php echo $programId; ?>" name="programId" type="hidden" />
    <input id="CAProgramName" value="<?php echo $programName; ?>" type="hidden" />
    <input id="CAEntityId" value="<?php echo $entityId; ?>" type="hidden" />
    <input id="CAEntityName" value="<?php echo $entityName; ?>" type="hidden" />
    <div class="form-details">
        <p class="form-title">Personal Details  </p>
        <ul>
                <li class="clear-width">
                <div class="flLt">
                        <label>First Name <span>*</span> </label>
                        <input type="text" class="text-width" value="<?php echo $firstname;?>" onmouseover="showTipOnline('If your name is Ramesh Kumar Gupta, enter Ramesh as your first name',this);" onmouseout="hidetip();" id='quickfirstname_ForCA' name='quickfirstname_ForCA'  maxlength="50" minlength="1" validate="validateFirstLastName" caption="First Name" required = "true" /><div style="display:none;"><div class="errorMsg" id="quickfirstname_ForCA_error" style="*float:left; width:280px"></div></div>
                </div>
                <div class="flRt">
                        <label>Last Name <span>*</span></label>
                        <input type="text" class="text-width" value="<?php echo $lastname;?>" onmouseover="showTipOnline('If your name is Ramesh Kumar Gupta, enter Gupta as your last name',this);" onmouseout="hidetip();" id='quicklastname_ForCA' name='quicklastname_ForCA'  maxlength="50" minlength="1" validate="validateFirstLastName" caption="Last Name" required = "true" /><div style="display:none;"><div class="errorMsg" id="quicklastname_ForCA_error" style="*float:left; width:280px"></div></div>
                </div>
            </li>
            <li class="clear-width">
                <div class="flLt">
                        <label>Personal Email ID <span>*</span> </label>
                        <?php if($userId > 0):?>
                        <input type="text" class="text-width" disabled="true" value="<?php echo htmlspecialchars($email);?>" onmouseover="showTipOnline('Please enter an email ID which you update most frequently. All updates will be sent on this ID.',this);" onmouseout="hidetip();" id = "quickemail" name = "quickemail" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" blurMethod = "checkAvailability(this.value,'quickemail'); setTimeout('checkEmail()',1500); " />
                        <?php else:?>
                        <input type="text" class="text-width" value="<?php echo htmlspecialchars($email);?>" onmouseover="showTipOnline('Please enter an email ID which you update most frequently. All updates will be sent on this ID.',this);" onmouseout="hidetip();" id = "quickemail" name = "quickemail" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" blurMethod = "checkAvailability(this.value,'quickemail'); setTimeout('checkEmail()',1500); " />
                        <?php endif;?>
                        <div style="display:none;"><div class="errorMsg" id="quickemail_error" style="*float:left"></div></div>
                </div>
                <div class="flRt">
                        <label>Your Student Email ID </label>
                        <input type="text" class="text-width" value="<?php echo htmlspecialchars($studentEmail);?>" onmouseover="showTipOnline('Please enter email ID provided by your college, if available.',this);" onmouseout="hidetip();" id = "quickStudentEmail" name = "quickStudentEmail" validate = "validateEmail" caption = "student email address" maxlength = "125" /><div style="display:none;"><div class="errorMsg" id="quickStudentEmail_error" style="*float:left"></div></div>
                </div>
            </li>
            <li class="clear-width">
                <div style="width:18%;" class="flLt">
                    <label>Country Code <span>*</span></label>
                    <select style="width:100%;border-right:none" class="select-width" required="true" validate="validateSelect" caption="ISD Code" id="isdCode" name="isdCode" onmouseover="showTipOnline('Please select your ISD Code',this);" onmouseout="hidetip();" onchange="changeMobileFieldmaxLength(this.value, 'quickMobile_ForCA');">
                                       
                    <?php foreach($isdCode as $key=>$value){ ?>
                        <option value="<?php echo $key; ?>"> <?php echo $value; ?></option>
                    <?php } ?>
                                        
                    </select>
                <div style="display:none;"><div class="errorMsg" id="isdCode_error" style="float:left"></div></div>   
                </div>
                <div class="flLt" style="margin-right:30px;  width:31%;">
                        <label>Mobile Number <span>*</span> </label>
                        <input style="width:325px;height:41px" type="text" class="text-width" value="<?php echo $mobile;?>" onmouseover="showTipOnline('Please enter your correct mobile number. It will be used for all important communication with you.',this);" onmouseout="hidetip();" id = "quickMobile_ForCA" name = "quickMobile_ForCA" validate = "validateMobileInteger" required = "true" maxlength = "10" minlength = "10" caption = "mobile" />
                                <div style="display:none;"><div class="errorMsg" id="quickMobile_ForCA_error" style="float:left;width:100%"></div></div>
                </div>
                <div class="flLt">
                        <label>Profile Photo</label>
                        <?php if(isset($imageURL)) {
                        if($imageURL == '') {
                                $url = SHIKSHA_HOME."/public/images/photoNotAvailable.gif";
                        }else {
                                $url = $imageURL;
                        }
                        }
                        ?>
                        <?php if(isset($imageURL)):?>
                       <span>
                       <a target="_blank" href="<?php echo $url;?>" style="display: block">View Profile Image</a>
                       </span>
                       <?php endif;?>
                        <input type="file" name='userApplicationfile' onmouseover="showTipOnline('Please upload your colour, close-up photo, preferably in formals',this);" onmouseout="hidetip();" id="Imagebox" value="<?php echo $url;?>" />
        <div style='display:none;'><div class='errorMsg' id= 'Imagebox_err' style="*float:left;"></div></div>
                  </div>
            </li>
            <li class="clear-width">
                <div class="flLt">
                        <label>Address</label>
                        <textarea class="textarea-width" id = "quickAddress_ForCA" name = "quickAddress_ForCA"  maxlength="1000" minlength="10" validate="validateStr" caption = "address" rows="3" cols="25"><?php echo $main_address;?></textarea><div style="display:none;"><div class="errorMsg" id="quickAddress_ForCA_error" style="*float:left"></div></div>
                </div> 
                
            </li>
             <li class="clear-width">
                <div class="flLt">
                        <label>Facebook Profile URL</label>
                        <input type="text" class="text-width" onmouseover="showTipOnline('Please enter your Facebook Profile URL here.',this);" onmouseout="hidetip();" blurmethod="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="https://www.facebook.com/prateek" value="<?php echo ($facebookURL!='')?$facebookURL:'https://www.facebook.com/prateek';?>" name='facebookURL' id='facebookURL' validate="validateSocial" minlength="3" maxlength="200" caption="Facebook profile URL" /><div style="display:none;"><div class="errorMsg" id="facebookURL_error" style="*margin-left:3px;"></div></div>
                </div>
                <div class="flRt">
                        <label>LinkedIn Profile URL</label>
                        <input type="text" class="text-width" onmouseover="showTipOnline('Please enter your LinkedIn Profile URL here.',this);" onmouseout="hidetip();" value="<?php echo ($linkedInURL!='')?$linkedInURL:'https://www.linkedin.com/in/prateek';?>" name='linkedInURL' id='linkedInURL' validate="validateSocial" minlength="3" blurmethod="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="https://www.linkedin.com/in/prateek" maxlength="200" caption="LinkedIn URL" /><div style="display:none;"><div class="errorMsg" id="linkedInURL_error" style="*margin-left:3px;"></div></div>
                </div>
            </li>
             <!-- <li class="clear-width">
                <div>
                        <input type="checkbox" <?php echo (($hasASmartphone==1)?'checked="checked"':'');?> name="has_a_smartphone" id="has_a_smartphone" value="1" class="flLt"/>
                        <p class="flLt" style="margin-left:5px; padding-top:2px;">I have a smart phone</p>
                </div>
            </li> -->
        </ul>
        
        <p class="form-title">College Details</p>
        <ul>
                <li class="clear-width">
                <div class="flLt">
                        <label>College Name <span>*</span> </label>
                        <div class="dummy_autosuggest" id="dummy_autosuggest_1">
                            <?php if($userId>0 && $crIdExist=='yes'): ?>
                            <input type="text" value="<?php echo $mainEducationDetails[0]['insName']; ?>" disabled="disabled" class="text-width" onmouseover="showTipOnline('As you type the name of the college, relevant matches will be shown in drop-down menu. Please click on the name to select.',this);" onmouseout="hidetip();" id="dummy_input"/>
                            <?php else: ?>
                            <input type="text" class="text-width" onclick="if(typeof (showAutosuggestReviewForm) == 'function'){showAutosuggestReviewForm(1);}" onmouseover="showTipOnline('As you type the name of the college, relevant matches will be shown in drop-down menu. Please click on the name to select.',this);" onmouseout="hidetip();" autocomplete="off" id="dummy_input" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" validate="validateStr" minlength="1" maxlength="200" required="true" caption="College name" />
                            <?php endif; ?>
                            <div style="display:none;">
                                <div class="errorMsg" id="dummy_input_error" style="*float:left"></div>
                                <div class="errorMsg" id="institute_error_1" style="*float:left"></div>
                            </div>
                            <div id="search-college-layer1" class="suggestion-box" style="display: none;"></div>
                        </div>
                      <div id="first_sibling_1" style="display:none">  
                            <div class="" id="anaAutoSuggestor" style="position: relative;">
                                <input type="text" name="keywordSuggest" id="keywordSuggest" onmouseover="showTipOnline('As you type the name of the institute, relevant matches will be shown in drop-down. Please click on the name to select',this);" onmouseout="hidetip();"  class="text-width" autocomplete="off" default="Type Institute name..." value="Type Institute name..." onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" validate="validateStr" minlength="1" maxlength="200" required="true" caption="College name" />
                                <div style="display:none;"><div class="errorMsg" id="keywordSuggest_error" style="*float:left"></div></div>
                         
                            </div>	
                            <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="" />
                       </div>
                        <input type="hidden" name="suggested_institutes[]" id="suggested_institutes_1" value="" />
                </div>
                <div class="flRt">
                        <label>Location <span>*</span></label>
                        <?php if($userId>0 && $crIdExist=='yes'): ?>
                        <input type="text" class="select-width" disabled="disabled" value="<?php echo (($mainEducationDetails[0]['locName'])? $mainEducationDetails[0]['locName'].", ":"");?><?php echo (($mainEducationDetails[0]['cityName'])? $mainEducationDetails[0]['cityName'].", ":"");?><?php echo (($mainEducationDetails[0]['stateName'])? $mainEducationDetails[0]['stateName'].', ':"");?><?php echo $mainEducationDetails[0]['countryName'];?>">
                        <?php else: ?>
                        <select class="select-width" onmouseover="showTipOnline('Please click on the college location from the drop-down menu.',this);" onmouseout="hidetip();" id="location_1" name="location[]" onchange="loadCourses(1);" required="true" validate="validateSelect" caption="Location"><option value="">Select</option></select>
                        <?php endif; ?>
        <div style="display:none;" id="loc_main"><div class="errorMsg" id="location_1_error" style="*float:left"></div></div>
                </div>
            </li>
            
            <li class="clear-width">
                <div class="flLt">
                        <label>Course <span>*</span> </label>
                        <?php if($userId>0 && $crIdExist=='yes'): ?>
                        <input type="text" class="select-width" disabled="disabled" value="<?php echo $mainEducationDetails[0]['courseName']; ?>">
                        <?php else: ?>
                        <select class="select-width" onchange="checkCourses(1); getCourseCampusURL(this.value,this);" onmouseover="showTipOnline('Please click on the course name from the drop-down menu.',this);" onmouseout="hidetip();" name="course[]" id="course_1" required="true" validate="validateSelect" caption="Course"  ><option value="">Select</option></select>
                        <?php endif; ?>
                                        <div style="display:none;"><div class="errorMsg" id="course_1_error" style="*float:left"></div></div>
                </div>
                <div class="flRt">
                        <label>Year of Completion <span>*</span> </label>
                        <?php if($userId>0 && $crIdExist=='yes'): ?>
                        <input type="text" class="select-width" disabled="disabled" value="<?php echo $mainEducationDetails[0]['yearOfGrad']; ?>">
                        <?php else: ?>
                        <select class="select-width" onchange="" onmouseover="showTipOnline('Please click on your year of passing graduation',this);" onmouseout="hidetip();" name="yearOfGrad[]" id="year_1" required="true" validate="validateSelect" caption="Year of Completion"  ><option value="">Select</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                        <option value="2016">2016</option>
                        <option value="2015">2015</option>
                        <option value="2014">2014</option>
                        <option value="2013">2013</option>
                        </select>
                        <?php endif; ?>
                        <div style="display:none;"><div class="errorMsg" id="year_1_error" style="*float:left"></div></div>
                </div>
            </li>
            <?php if($userId<=0 || $userId==''){ ?>
        <li class="captcha-row">
            <p>Type in the character you see in the picture below</p>
            <img style="vertical-align:middle" src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeForCAReg" onabort="javascript:reloadCaptcha(this.id);" onClick="javascript:reloadCaptcha(this.id);" id = "registerCaptacha_ForCA" alt="" /> &nbsp; 
            <input type="text" class="universal-txt-field" id = "securityCode_ForCA" name = "securityCode_ForCA" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" style="width:140px; vertical-align:middle; padding:7px" />
            <div class="spacer10 clearFix"></div>
        
            <div style="padding-left:19px;"><div class="errorMsg" id="securityCode_ForCA_error"></div></div>
            <div class="errorPlace" style="margin-top:2px;float:left;width:100%;" >
                                            <div class="errorMsg" id = "quickagree_ForCA_error"></div>
                                    </div>
                                    
                                    <div style="display:none;float:left;width:100%;">
                                            <div class="errorMsg" id = "quickerror_ForCA"></div>
                                    </div>	
            
        </li>
        <?php } ?>
         </ul>
        <?php
        if($userId>0 && $crIdExist=='yes' && $profileStatus=='deleted')
        {
            echo '&nbsp;';
            //show overlay for 'rejected' profile
        }
        else
        {
        ?>
                <a style="height: 42px; margin:20px 0 0 0px !important;" href="javascript:void(0);" class="orange-button continue-btn" onclick="startValidating();removeHelpText($('CAProfileForm')); if(validateFields($('CAProfileForm')) != true){ validateCAForm($('CAProfileForm')); validationFail(); return false;} if( validateCAForm($('CAProfileForm')) != true){validationFail(); return false;}  storeCAData($('CAProfileForm')); return false;">Save & Continue <i class="campus-sprite  continue-icon"></i></a>&nbsp;<span id="waitingDiv" style="display:none"><img src="<?php echo SHIKSHA_HOME;?>/public/images/working.gif" border=0 align=""></span>
                <div class="clearFix"></div>
        <p class="tac" style="float:left">By clicking Save & Continue button, I agree to the <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">Terms and Conditions</a></p>
        <?php
        }
        ?> 
    </div>
</form>
