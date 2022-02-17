<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
<form id="default_type1_unified_form" method="post" novalidate="novalidate" name="default_type1_unified_form" onsubmit="$('save_button_unifiedregistration').disabled = true;if(!ShikshaUnifiedRegistarion.validateUnifiedIndiaForm(this)) {$('save_button_unifiedregistration').disabled = false; return false;}new Ajax.Request('/user/Userregistration/UnifiedRegistrationUserOperation/seccodehome',{onSuccess:function(request){javascript:ShikshaUnifiedRegistarion.actionAfterSubmit(request.responseText,page_identifier_unified);}, evalScripts:true, parameters:Form.serialize(this)}); return false;">
 <!-- flagfirsttime hidden field updated after form submitting -->
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
    <input type="hidden" id='tracking_keyid' name='tracking_keyid' value='406' />
<div style="width:675px;margin:0 auto">
        <div class="blkRound">
            <div class="bluRound">
                <span class="float_R" ><img src="/public/images/fbArw.gif" border="0" class="pointer" onclick="ShikshaUnifiedRegistarion.actionAfterUnifiedLayerClicked(true,'true','is_unified_overlay1_clicked', 'true',page_identifier_unified);"/></span>
                <span class="title">Complete your Shiksha.com profile</span>
                <div class="clear_B"></div>
            </div>
            <div class="whtRound" style="padding:10px 20px">
                <div class="wdh100">
                    <div class="mb10">Hi <label class="OrgangeFont  bld" id="display_username_unifiedregistration" ></label>, your request has been submitted. We have sent you the requested information and an auto-generated password at your email id for you to access your Shiksha account.</div>
                    <div class="wdh100">
                        <div class="clear_B">&nbsp;</div>
                        <div class="float_L" style="width:400px">
                            <div class="wdh100">
                                <div class="mb10"><strong>Please complete your profile and get maxium benefits at Shiksha.com</strong></div>
                                <?php if(!$category_unified):?>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">Education Interest<b class="redcolor">*</b>: &nbsp; </div>
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
                                    <div class="float_L w165 tar">Desired Course<b class="redcolor">*</b>: &nbsp; </div>
                                    <div class="float_L">
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
                                    <div class="float_L w165 tar">When do you plan to start<b class="redcolor">*</b>: &nbsp; </div>
                                    <div class="float_L">
                                        <div><select style="width:210px" id="when_plan_start_unifiedregistration" caption="when you plan to start the course" validate="validateSelect" required="true" name="plan"><option value="">Select</option><?php echo $when_you_plan_start; ?></select></div>
                                    <div class="errorMsg">
                                        <div id="when_plan_start_unifiedregistration_error" class="errorMsg w165" style="width:208px"></div>
                                        </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">Residence Location<b class="redcolor">*</b>: &nbsp; </div>
                                    <div class="float_L">
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
                                    <div class="float_L w165 tar">Age: &nbsp; </div>
                                    <div class="float_L">
                                        <div><input type="text" id = "quickage_unifiedregistration" name = "quickage" type="text" minlength = "2" maxlength = "2" value="" validate = "validateAge" caption = "age" style="width:30px"/></div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="errorMsg"><div style="position:relative;left:165px;top:-7px;width:240px;"id= "quickage_unifiedregistration_error" class="errorMsg"></div></div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">Gender: &nbsp; </div>
                                    <div class="float_L">
                                        <div style="position:relative;top:-3px"><input type="radio" name = "quickgender" id = "Female_unifiedregistration" value = "Female" style="position:relative;top:1px;" /> Female &nbsp; <input type="radio" name = "quickgender" id = "Male_unifiedregistration" value = "Male" style="position:relative;top:1px;" /> Male</div>
                                    <div class="errorMsg" style=""><div id= "quickgender_error" class="errorMsg"></div></div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="wdh100 mb10">
                                    <div style="margin-top: 2px; display: none; line-height: 15px;" class="errorPlace">
                                    <div id="cAgree_unifiedregistration_error" class="errorMsg" style="margin-left: 165px;"></div>
                                    </div>
                                </div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">&nbsp;</div>
                                    <div class="float_L">
                                        <div><input id="save_button_unifiedregistration" type="submit" class="fbBtn" value="Save" uniqueattr="UnifiedLayer1SaveButton"/> &nbsp; <a href="#" onclick="ShikshaUnifiedRegistarion.actionAfterUnifiedLayerClicked(true,'true','is_unified_overlay1_clicked', 'true',page_identifier_unified);" >Later</a></div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <div class="float_R" style="width:205px">
                            <div class="wdh100">
                                <div class="rBBx">
                                    <ul class="rndBlts">
                                        <li class="mb8">
                                            <strong>Get contacted:</strong><br />
                                            Let institute contact you directly basis your preference
                                        </li>
                                        <li class="mb8">
                                            <strong>Customized advice:</strong><br />
                                            Get personalized expert career counselling
                                        </li>
                                        <li class="mb8">
                                            <strong>Free alerts:</strong><br />
                                            Get alerts for all important dates &amp; events of your choice
                                        </li>
                                    </ul>
                                    <div class="Fnt11">
                                        Registering at <strong>Shiksha.com</strong> is like offloading all your worries with a trusted friend and guide.<br/> Try it and feel the difference!                                    </div>
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
<div id="helpbubble1_unifiedregistration" style="position: absolute; left: 553px; top: 417px; z-index: 10000;display:none;">
<div class="clear_L">&nbsp;</div>
<div style="background-color: rgb(242, 228, 171); padding: 10px; width: 150px;" id="hintbox1_unifiedregistration" class="float_L normaltxt_11p_blk_verdana"></div>
<div class="float_L"><img src="/public/images/help_bubble_r.gif"></div>
<div class="clear_L">&nbsp;</div>
</div>
<script>
try {
var first_name_unified = "<?php if(is_array($userData['0']))echo addslashes($userData['0']['firstname'])?>";
var isLogged = '<?php echo $logged; ?>';
var currentPageName = '<?php echo $pagename; ?>';
var FLAG_LOCAL_COURSE_FORM_SELECTION = 4;
var desired_course_array_unifiedregistration = new Array();
var when_plan_start_array_unifiedregistration = new Array();
var when_plan_start_array_local_unifiedregistration = new Array();
desired_course_array_unifiedregistration[0] = 'study_mode_block_unifiedregistration';
desired_course_array_unifiedregistration[1] = 'preferredstudylocation_unifiedregistration';
desired_course_array_unifiedregistration[2] = 'degree_preference_block_unifiedregistration';
when_plan_start_array_unifiedregistration[0] = 'showUGSection_unifiedregistration';
when_plan_start_array_local_unifiedregistration[0] = 'showUGSection_unifiedregistration';
when_plan_start_array_local_unifiedregistration[1] = 'PreferredLocalities_unifiedregistration';
document.getElementById('display_username_unifiedregistration').innerHTML = truncateString(first_name_unified,80);
document.getElementById('display_username_unifiedregistration').setAttribute("title",first_name_unified);
ajax_loadContent('unifiedLocationLayer_ajax','/user/UnifiedRegistration/loadFormUsingAjax/location');
addOnBlurValidate($('default_type1_unified_form'));
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
$('input_prefid_unified').value = unified_registration_ldb_user_pref_id;
</script>
