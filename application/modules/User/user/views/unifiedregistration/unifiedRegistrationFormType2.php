<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
<form id="default_type2_unified_form" method="POST" novalidate="novalidate" name="default_type2_unified_form" onsubmit="$('save_button_unifiedregistration').disabled = true;if(!ShikshaUnifiedRegistarion.validateUnifiedIndiaForm(this)) {$('save_button_unifiedregistration').disabled = false; return false;} new Ajax.Request('/user/Userregistration/UnifiedRegistrationUserOperation/seccodehome',{onSuccess:function(request){javascript:ShikshaUnifiedRegistarion.dbResponseHandler(request.responseText,page_identifier_unified);}, evalScripts:true, parameters:Form.serialize(this)}); return false;">
<input type = "hidden" name = "input_prefid_unified" id = "input_prefid_unified" value =
""/>
<input type = "hidden" name = "input_widget_identifier_unified" id = "input_widget_identifier_unified" value =
""/>
<input type = "hidden" name = "flagfirsttime" id = "flagfirsttime_unifiedregistration" value =
""/>
    <input type = "hidden" name = "resolutionreg" id = "resolutionreg_unifiedregistration" value =
""/>
    <input type = "hidden" name = "refererreg" id = "refererreg_unifiedregistration" value = ""/>
    <input type = "hidden" name = "mCityList" id = "mCityList_unifiedregistration" value = ""/>
    <input type = "hidden" name = "mCityListName" id = "mCityListName_unifiedregistration" value =
""/>
    <input type = "hidden" name = "mCountryList" id = "mCountryList_unifiedregistration" value =
""/>
    <input type = "hidden" name = "mCountryListName" id = "mCountryListName_unifiedregistration"
value = ""/>
    <input type = "hidden" name = "loginflagreg" id = "loginflagreg_unifiedregistration" value =
""/>
    <input type = "hidden" name = "loginactionreg" id = "loginactionreg_unifiedregistration" value =
""/>
    <!-- required field .. identify between update or insert -->
    <input type = "hidden" name = "mupdateflag" id = "mupdateflag_unifiedregistration" value = "<?php echo $value;?>"/>
    <!-- required filed .. identify pagename -->
    <input type = "hidden" name = "marketingpagename" id = "marketingpagename_unifiedregistration" value = "<?php echo $pagename; ?>"/>
    <input type = "hidden" name = "categoryId" id ="categoryId_unifiedregistration" value=""/>
    <input type = "hidden" name = "subCategoryId" id = "subCategoryId_unifiedregistration" value=""/>
    <input type = "hidden" name = "desiredCourse" id ="desiredCourse_unifiedregistration" value=""/>
    <!--below is used for conversion tracking purpose -->
    <input type="hidden" name="tracking_keyid" id="tracking_keyid_unifiedregistration" value="<?=$trackingPageKeyId?>">
<div style="width: 550px; margin: 0pt auto;">
        <div class="blkRound">

<div class="layer-title">		
<a href="javascript:void(0);" title="Close" class="close" onclick="ShikshaUnifiedRegistarion.actionAfterUnifiedLayerClicked(true,'true','is_unified_overlay2_clicked', 'true',page_identifier_unified);"></a>
<h4>Join now for free</h4>

            </div>
            <div class="whtRound">
                <div class="wdh100">
                    <div class="wdh100 mb15">
                        <div class="float_L Fnt14"><strong>New Users, Register Free!</strong></div>
                        <?php if($logged != 'Yes'):?><div class="float_R"><a onclick="messageObj.close();oristate1();" href="javascript:void(0);">Existing Users, Sign In</a></div><?php endif;?>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="wdh100">
                        <div class="clear_B">&nbsp;</div>
                        <div class="float_L">
                            <div class="wdh100">
                                <?php if($logged == 'No'):?>
                                <div class="wdh100 mb10">
                                <div class="float_L w165 tar" style="width:190px">First Name<b class="redcolor">*</b>: &nbsp; </div>
                                <div style="margin-left:190px;">
                                    <div><input id="firstname_unifiedregistration"  name="firstname" maxlength="50" minlength="1" validate="validateDisplayName" required="1" caption="first name" type="text" style="width: 200px;" class="olyTxtBx"></div>
                                    <div style="display: inline;">
                                    <div style="" id="firstname_unifiedregistration_error" class="errorMsg"></div>
                                     </div>
                                </div>
                                <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="wdh100 mb10">
                                <div class="float_L w165 tar" style="width:190px">Last Name<b class="redcolor">*</b>: &nbsp; </div>
                                <div style="margin-left:190px;">
                                    <div><input id="lastname_unifiedregistration"  name="lastname" maxlength="50" minlength="1" validate="validateDisplayName" required="1" caption="last name" type="text" style="width: 200px;" class="olyTxtBx"></div>
                                    <div style="display: inline;">
                                    <div style="" id="lastname_unifiedregistration_error" class="errorMsg"></div>
                                     </div>
                                </div>
                                <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="wdh100 mb10">
                                <div class="float_L w165 tar" style="width:190px">Email<b class="redcolor">*</b>: &nbsp; </div>
                                <div style="margin-left:190px;">
                                    <div><input  name="email" id="email_unifiedregistration" maxlength="125" required="1" caption="email address" validate="validateEmail" type="text" style="width: 200px;" class="olyTxtBx"></div>
                                <div style="display: inline;">
            			<div style="" id="email_unifiedregistration_error" class="errorMsg"></div>
        			</div>
                                </div>
                                <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="wdh100 mb10">
                                <div class="float_L w165 tar" style="width:190px">Mobile<b class="redcolor">*</b>: &nbsp; </div>
                                <div style="margin-left:190px;">
                                    <div><input  validate= "validateMobileInteger"value="" name="mobile" id="mobile_unifiedregistration" minlength="10" maxlength="10" required="1"  caption="mobile number" type="text" style="width: 200px;" class="olyTxtBx"></div>
                                <div style="display: inline;">
            			<div style="width:214px;*margin-bottom:-13px;" id="mobile_unifiedregistration_error" class="errorMsg"></div>
        			</div>
                                </div>
                                <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php endif;?>
                                <?php if(!$category_unified):?>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar" style="width:190px">Education Interest<b class="redcolor">*</b>: &nbsp; </div>
                                    <div class="float_L">
                                        <div><select id="fieldOfInterest_unifiedregistration" name="board_id" style="width:210px" validate="validateSelect" required="true" caption="desired education of interest" onchange="ShikshaUnifiedRegistarion.populateDesiredCourseCombo(this);">
                                        <option value="">Select</option>
                                        <?php
                                       foreach($categories as $categoryId => $categoryName) {
                                            if($categoryId !=14) {
                                            		echo "<option value='". $categoryId."'>". $categoryName ."</option>";
                                            }
                                        }
                                       ?>
                                        </select></div>
                                        <div class="errorMsg">
                                        <div style="" id="fieldOfInterest_unifiedregistration_error" class="errorMsg"></div>
                                        </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php else: ?>
                                <script>ShikshaUnifiedRegistarion.populateDesiredCourseCombo('<?php echo $category_unified?>')</script>
                                <?php endif;?>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar" style="width:190px">Desired Course<b class="redcolor">*</b>: &nbsp; </div>
                                    <div style="margin-left:190px;">
                                        <div id="desired_course_unifiedregistration_parent"><select style="width:210px"  required="true" caption="the desired course" validate="validateSelect" id="homesubCategories_unifiedregistration" name=""><option value="">Select</option></select></div>
                                    <div class="errorMsg">
                                        <div style="" id="homesubCategories_unifiedregistration_error" class="errorMsg"></div>
                                        </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <!-- switch form div starts here -->
                               <div id="unifiedregistration_switch_form_div">
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar" style="width:190px">When do you plan to start ?<b class="redcolor">*</b>: &nbsp; </div>
                                    <div style="margin-left:190px;">
                                        <div><select style="width:210px" id="when_plan_start_unifiedregistration" caption="when you plan to start the course" validate="validateSelect" required="true" name="plan"><option value="">Select</option><?php echo $when_you_plan_start; ?></select></div>
                                    <div class="errorMsg">
                                        <div  id="when_plan_start_unifiedregistration_error" class="errorMsg w165" style="width: 208px;*margin-bottom:-13px;"></div>
                                        </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar" style="width:190px">Residence Location<b class="redcolor">*</b>: &nbsp; </div>
                                    <div style="margin-left:190px;">
                                        <div><select style="width:210px" name="citiesofquickreg" id="citiesofquickreg_unifiedregistration" blurMethod = "ShikshaUnifiedRegistarion.validate_residencecombo();" validate="validateSelect" required="true" caption="specify your city of residence"><option value="">Select</option>
			   <?php
			    $optionSelectedStr = '';
			    if ( isset($data[0]['city']) ) {
				$userSelectedCity = $data[0]['city'];
			    }
			    foreach($cityTier1 as $list) {
				if ($userSelectedCity == $list['cityId']) {
				    $optionSelectedStr = "selected";
				} else { $optionSelectedStr = ''; }
			    ?>
				<option <?php echo $optionSelectedStr; ?> value="<?php echo $list['cityId']; ?>"><?php echo $list['cityName'];?></option>
			    <?php
			    }
			    $optionSelectedStr = '';
			    ?>
			    <?php
			    foreach($country_state_city_list as $list)
			    {
				if($list['CountryId'] == 2)
				{
				    foreach($list['stateMap'] as $list2)
				    {
				        echo '<OPTGROUP LABEL="'.$list2['StateName'].'">';
				        foreach($list2['cityMap'] as $list3)
				        {
				            if ($userSelectedCity == $list3['CityId']) {
				                $optionSelectedStr = "selected";
				            } else { $optionSelectedStr = ''; }
				        ?>
				            <option <?php echo $optionSelectedStr; ?> value="<?php echo $list3['CityId']; ?>"><?php echo $list3['CityName'];?></option>
				        <?php
				        }
				    }
				}
			    }
			    ?>
				                       </select></div>
                                        <div class="errorMsg">
                                        <div style="" id="citiesofquickreg_unifiedregistration_error" class="errorMsg"></div>
                                        </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                </div>
                                <!-- switch form div ends here -->
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar" style="width:190px">Age: &nbsp; </div>
                                    <div style="margin-left:190px;">
                                        <div><input type="text" id = "quickage_unifiedregistration" name = "quickage" type="text" minlength = "2" maxlength = "2" value="" validate = "validateAge" caption = "age" style="width:30px"/></div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="errorMsg"><div id= "quickage_unifiedregistration_error" class="errorMsg" style="position:relative;left:165px;top:-7px;"></div></div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar" style="width:190px">Gender: &nbsp; </div>
                                    <div style="margin-left:190px;">
                                        <div style="position:relative;top:-3px"><input type="radio" name = "quickgender" id = "Female_unifiedregistration" value = "Female" style="position:relative;top:1px;" /> Female &nbsp; <input type="radio" name = "quickgender" id = "Male_unifiedregistration" value = "Male" style="position:relative;top:1px;" /> Male</div>
                                    <div class="errorMsg" style=""><div id= "quickgender_error" class="errorMsg"></div></div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php if($logged == 'No'):?>
                                <div class="wdh100 mb10">
                                <div class="ml46">
                                    <div class="mb5">Type the characters you see in the image below</div>
                                    <div><img id="securecode_unified" align="absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&
randomkey=<?php echo rand(); ?>&secvariable=seccodehome">&nbsp;&nbsp;&nbsp;<input caption="Security Code" required="1" minlength="5" maxlength="5" validate="validateSecurityCode" id="homesecurityCode_unifiedregistration" name="homesecurityCode" type="text">
                                     <div style="display: inline;" class="errorPlace">
                                    <div id="homesecurityCode_unifiedregistration_error" class="errorMsg" style="margin-left: 106px;"></div>
                                    </div>
                                   </div>
                                    <div class="lineSpace_10">&nbsp;</div>
                                    <div><input type="checkbox" id="cAgree_unifiedregistration"> I agree to the <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition');" href="javascript:void(0);">terms of services</a> and <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy');" href="javascript:void(0);">privacy policy</a></div>
                                </div>
                            </div>
                             <?php endif;?>
                               <div class="wdh100 mb10">
                                    <div style="margin-top: 2px; display: none; line-height: 15px;" class="errorPlace">
                                    <div id="cAgree_unifiedregistration_error" class="errorMsg" style="margin-left: 6px;"></div>
                                    </div>
                                </div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">&nbsp;</div>
                                    <div class="float_L">
                                        <input type="submit" id="save_button_unifiedregistration" value="Join now for free" class="fbBtn" uniqueattr="UnifiedLayer2JoinNowForFreeButton">
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="unifiedLocationLayer_ajax"></div>
<div id="helpbubble1_unifiedregistration" style="position: absolute; left: 45px; top: 81px; z-index: 10000;display:none;">
<div class="clear_L">&nbsp;</div>
<div style="background-color: rgb(242, 228, 171); padding: 10px; width: 150px;" id="hintbox1_unifiedregistration" class="float_L normaltxt_11p_blk_verdana"></div>
<div class="float_L"><img src="/public/images/help_bubble_r.gif"></div>
<div class="clear_L">&nbsp;</div>
</div>
<script>
try {
var isLogged = '<?php echo $logged; ?>';
var FLAG_LOCAL_COURSE_FORM_SELECTION = 4;
var desired_course_array_unifiedregistration = new Array();
var when_plan_start_array_unifiedregistration = new Array();
var when_plan_start_array_local_unifiedregistration = new Array();
var currentPageName = '<?php echo $pagename; ?>';
desired_course_array_unifiedregistration[0] = 'study_mode_block_unifiedregistration';
desired_course_array_unifiedregistration[1] = 'preferredstudylocation_unifiedregistration';
desired_course_array_unifiedregistration[2] = 'degree_preference_block_unifiedregistration';
when_plan_start_array_unifiedregistration[0] = 'showUGSection_unifiedregistration';
when_plan_start_array_local_unifiedregistration[0] = 'showUGSection_unifiedregistration';
when_plan_start_array_local_unifiedregistration[1] = 'PreferredLocalities_unifiedregistration';
ajax_loadContent('unifiedLocationLayer_ajax','/user/UnifiedRegistration/loadFormUsingAjax/location');
addOnBlurValidate($('default_type2_unified_form'));
} catch(e) {
alert(e);
}
function trackEventByGA(eventAction,eventLabel) {
        try {
	if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
	    pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
	}
	return true;
	} catch(e) {
		alert(e)
	}
}
$('input_widget_identifier_unified').value = unified_widget_identifier;
if(isLogged == 'Yes') {
	$('cAgree_unifiedregistration_error').style.marginLeft = '165px';
}
$('input_prefid_unified').value = unified_registration_ldb_user_pref_id;
if($('DHTMLSuite_modalBox_contentDiv')) {
	$('DHTMLSuite_modalBox_contentDiv').style.top = "0px";
}
</script>
