<?php
    $prefix = '';
    if(!isset($userData)) {
        $userData = $validateuser;
    }
    if(is_array($userData) && !empty($userData[0]['userid'])) {
        $logged = 'Yes';
    } else {
        $logged = 'No';
    }
    $url = site_url('user/Userregistration/userfromMarketingPage/'.$prefix.'seccodehome');
    $userId = $userEmail =  $userMobile = $userName = '';
    if($logged == "Yes") {
        $userId = $userData[0]['userid'];
        $userName = $userData[0]['firstname']." ".$userData[0]['lastname'];
        $cookieStr = $userData[0]['cookiestr'];
        $userEmail = substr($cookieStr,0,strpos($cookieStr,'|'));
        if(strlen($userData[0]['mobile']) == 10) {
            $userMobile = $userData[0]['mobile']; 
        }
    }
?>
    <!--Start_OuterBorder-->
        <input type = "hidden" name = "resolutionreg" id = "resolutionreg" value = ""/>
        <input type = "hidden" value="2" id="country<?php echo $prefix;?>"/>
        <input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
        <input type = "hidden" name = "mCityList" id = "mCityList" value = ""/>
        <input type = "hidden" name = "mCityListName" id = "mCityListName" value = ""/>
		<input type = "hidden" name = "mCountryList" id = "mCountryList" value = ""/>
        <input type = "hidden" name = "mCountryListName" id = "mCountryListName" value = ""/>
        <input type = "hidden" name = "mPageName" id = "mPageName" value = "<?php echo $pagename?>"/>
        <input type = "hidden" name = "mcourse" id = "mcourse" value = "<?php echo $course?>"/>
			<!--Form_Start-->
        <div style="width:100%">
			<!-- START: Dynamic Fields to be populated according to the page -->
            <?php $this->load->view($includeFile);?>
			<!-- END: Dynamic Fields to be populated according to the page -->
		    <!-- Fixed Fields -->


            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Your Name:<b class="redcolor">*</b>&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <input type="text" id = "<?php echo $prefix; ?>homename" name = "homename" class = "fontSize_11p" validate = "validateDisplayName" maxlength = "25" minlength = "3" required = "true" caption = "name" style = "width:150px;height:15px;font-size:12px;font-family:arial" value="<?php echo $userName; ?>"/>
                    </div>
                    <div>
                        <div class="errorMsg" id= "<?php echo $prefix; ?>homename_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Mobile:<b class="redcolor">*</b>&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <input type="text" id = "<?php echo $prefix; ?>homephone" name = "homephone" validate = "validateMobileInteger" required = "true" caption = "mobile number so that we can send you alerts for important dates" tip="mobile_numM" maxlength="10" maxlength1="10" minlength = "10" style="width:150px;height:15px;font-size:12px;font-family:arial" value="<?php echo $userMobile; ?>"/>
                    </div>
                    <div>
                        <div class="errorMsg" id= "<?php echo $prefix; ?>homephone_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Residence Location:<b class="redcolor">*</b>&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <select style = "width:150px;font-size:12px;font-family:arial" class = "normaltxt_11p_blk_arial fontSize_12p" id = "cities<?php echo $prefix; ?>" name = "citiesofresidence1" validate = "validateSelect" required = "true" caption = "your city of residence">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div>
                        <div class="errorMsg" id= "cities<?php echo $prefix; ?>_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Your Highest Qualification:<b class="redcolor">*</b>&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <select class = "normaltxt_11p_blk_arial fontSize_11p" style = "width:150px;font-size:12px;font-family:arial" id = "<?php echo $prefix; ?>homehighesteducationlevel" name = "homehighesteducationlevel" validate = "validateSelect" required = "true" caption = "your highest education">
                        </select>
                    </div>
                    <div>
                        <div class="errorMsg" id= "<?php echo $prefix; ?>homehighesteducationlevel_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Age:&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <input id = "<?php echo $prefix?>homeYOB" name = "homeYOB" type="text" minlength = "2" maxlength = "2" validate = "validateAge" caption = "age field" style="width:20px;font-size:12px;font-family:arial"  value="<?php if($logged == "Yes" ) if($userData[0]['age']!='0') {echo $userData[0]['age'];}?>"/>
                    </div>
                    <div>
                        <div class="errorMsg" id= "<?php echo $prefix; ?>homeYOB_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Gender:&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <input type="radio" name="homegender" id="<?php echo $prefix?>Male" value="Male"/>Male
                        <input type="radio" name="homegender" id ="<?php echo $prefix?>Female" value="Female"/>Female
                    </div>
                    <div>
                        <div class="errorMsg" id= "homegender_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

            <?php if($logged=="No") {?>
            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Your Email Id:<b class="redcolor">*</b>&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <input type="text" id = "<?php echo $prefix; ?>homeemail" name = "homeemail" value="<?php echo $userEmail; ?>" validate = "validateEmail" required = "true" caption = "email id" maxlength = "125" style = "width:150px;height:15px;font-size:12px;font-family:arial" tip="email_idM" />
                    </div>
                    <div>
                        <div class="errorMsg" id= "<?php echo $prefix; ?>homeemail_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Create Password:<b class="redcolor">*</b>&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <input type="password" id = "<?php echo $prefix; ?>homepassword" name = "homepassword" value = "<?php echo $userPassword; ?>" validate = "validateStr" required = "true" caption = "password" minlength = "5"  maxlength = "20" style = "width:150px;height:15px;font-size:11px;"/>
                    </div>
                    <div>
                        <div class="errorMsg" id= "<?php echo $prefix; ?>homepassword_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

            <div style="margin-bottom:10px">
                <div class="leftColumn">
                    <div class="txt_align_r">Confirm Password:<b class="redcolor">*</b>&nbsp;</div>
                </div>
                <div class="rightColumn">
                    <div>
                        <input type="password" caption="Password again" required="1" blurmethod="validateConfirmPassword('<?php echo $prefix; ?>homepassword','<?php echo $prefix; ?>confirmpassword')" validate="validateStr" id="<?php echo $prefix; ?>confirmpassword" name="<?php echo $prefix; ?>confirmpassword" style = "width:150px;height:15px;font-size:11px;" minlength = "5"  maxlength = "20" />
                    </div>
                    <div>
                        <div class="errorMsg" id= "<?php echo $prefix; ?>confirmpassword_error"></div>
                    </div>    
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>

           <?php } else {?>
            <input type="hidden" id = "<?php echo $prefix; ?>homeemail" value="<?php echo $userEmail;?>"/>
            <input type="hidden" id = "<?php echo $prefix; ?>userId" value="<?php echo $userId;?>"/>
            <?php }?>

            <div style="margin:6px 0 10px 0">
                <div style="padding-left:5px">Type in the characters you see in the picture below</div>
                <div style="padding-left:5px">
                    <img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=<?php echo $prefix; ?>seccodehome" width="100" height="34"  id = "<?php echo $prefix; ?>secureCode"/>
                    <input type="text" style="margin-left:20px;width:135px;height:15px;font-size:12px;font-family:arial" name = "homesecurityCode" id = "<?php echo $prefix; ?>homesecurityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
                </div>
                <div style="padding-left:5px">
                    <div class="errorMsg" id= "<?php echo $prefix; ?>homesecurityCode_error"></div>
                </div>
                <div style="padding-top:13px">
                    <input type="checkbox" name="cAgree" id="<?php echo $prefix; ?>cAgree" />
                    I agree to the 
                    <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and 
                    <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
                </div>
                <div>
                    <div class="errorMsg" id= "<?php echo $prefix; ?>cAgree_error"></div>
                </div>
                <div style="padding:12px 0  7px 0" align="center">
                    <input type="submit" class="homeShik_SubmitBtn" id='subm' onclick="return sendReqInfo<?php echo $prefix;?>(this.form) && trackEventByGA('SubmitClick','HOMEPAGE_LEAD_PANEL_SUBMIT_BTN');" value="Submit" />
                </div>
            </div>
       </div>
    <!--End_Form_Start-->
                        <!--Start_OuterBorder-->
<?php global $citiesforRegistration; ?>
<script>
                getEducationLevel('<?php echo $prefix; ?>homehighesteducationlevel','',1,'reqInfo');
                if("<?echo $logged; ?>"== "Yes") {
                    var highestQualificationId = document.getElementById("<?php if($logged == "Yes") echo $prefix; ?>homehighesteducationlevel");
                    for(var kl=0;kl<highestQualificationId.options.length;kl++) {
                        if(highestQualificationId.options[kl].title=="<?php  if($logged == "Yes") echo $userData[0]['degree']?>") {
                            highestQualificationId.options[kl].selected=true;
                            break;

                        }
                    }
                }
<?php if($logged=="Yes") {if($userData[0]['gender']=="Male") {
    echo "document.getElementById('".$prefix."Male').checked='true';";
}
}?>
<?php if($logged=="Yes") {if($userData[0]['gender']=="Female") {
    echo "document.getElementById('".$prefix."Female').checked='true';";
}
}?>

</script>
