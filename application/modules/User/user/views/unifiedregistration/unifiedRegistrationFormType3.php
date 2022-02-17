<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
<form novalidate="novalidate" id="default_type3_unified_form" method="POST" name="default_type1_unified_form" onsubmit="$('save_button_unifiedregistration').disabled = true;if(!ShikshaUnifiedRegistarion.validateUnifiedAbroadForm(this)) {$('save_button_unifiedregistration').disabled = false; return false;} new Ajax.Request('/user/Userregistration/UnifiedRegistrationUserOperationAbroad/seccodehome',{onSuccess:function(request){javascript:ShikshaUnifiedRegistarion.actionAfterSubmit(request.responseText,page_identifier_unified);}, evalScripts:true, parameters:Form.serialize(this)}); return false;">
<input type = "hidden" name = "input_prefid_unified" id = "input_prefid_unified" value =
""/>
    <input type = "hidden" name = "input_widget_identifier_unified" id = "input_widget_identifier_unified" value =
""/>
    <input type = "hidden" name = "flagfirsttime" id = "flagfirsttime_unifiedregistration" value = ""/>
    <input type = "hidden" name = "resolutionreg" id = "resolutionreg_unifiedregistration" value = ""/>
    <input type = "hidden" name = "refererreg" id = "refererreg_unifiedregistration" value = ""/>
    <input type = "hidden" name = "mCityList" id = "mCityList_unifiedregistration" value = ""/>
    <input type = "hidden" name = "mCityListName" id = "mCityListName_unifiedregistration" value = ""/>
    <input type = "hidden" name = "mCountryList" id = "mCountryList_unifiedregistration" value = ""/>
    <input type = "hidden" name = "mCountryListName" id = "mCountryListName_unifiedregistration" value = ""/>
    <input type = "hidden" name = "mPageName" id = "mPageName_unifiedregistration" value = "<?php echo $pageName?>"/>
    <input type = "hidden" name = "mcourse" id = "mcourse_unifiedregistration" value = "<?php echo $course?>"/>
    <input type = "hidden" name = "loginflagreg" id = "loginflagreg_unifiedregistration" value = ""/>
    <input type = "hidden" name = "loginactionreg" id = "loginactionreg_unifiedregistration" value = ""/>
    <input type="hidden" id='tracking_keyid' name='tracking_keyid' value='406' />
<div style="width:675px;margin:0 auto">
        <div class="blkRound">
            <div class="bluRound">
                <span class="float_R" ><img src="/public/images/fbArw.gif" border="0" class="pointer" onclick="ShikshaUnifiedRegistarion.actionAfterUnifiedLayerClicked(true,'true','is_unified_overlay3_clicked', 'true',page_identifier_unified);" /></span>
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
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">Field of Interest<b class="redcolor">*</b>: &nbsp; </div>
                                    <div class="float_L">
                                        <div><select id="fieldOfInterest_unifiedregistration" name="board_id" style="width:210px" validate="validateSelect" required="true" caption="desired education of interest">
                                        <option value="">Select</option>
                                        <?php
                                       foreach($categories as $categoryId => $categoryName) {
                                            if ($categoryId != 14) {
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
                                <div class="wdh100 mb10">
                                <div class="float_L w165 tar">Desired Graduation Level<b class="redcolor">*</b>: &nbsp; </div>
                                <div class="float_L">
                                    <div style="position: relative; top: -3px;">
                                    <input value="ug" name="desiredCourseLevel" id="desiredCourseLevel_ug_unifiedregistration" type="radio" style="position: relative; top: 1px;" onclick="ShikshaUnifiedRegistarion.toggleFormForDesiredCourseLevelAbroad('ug');" blurmethod="testAbroadGraduationLevel();"> Graduation &nbsp;
                                   <input  value="pg" name="desiredCourseLevel" id="desiredCourseLevel_pg_unifiedregistration" type="radio" style="position: relative; top: 1px;" onclick="ShikshaUnifiedRegistarion.toggleFormForDesiredCourseLevelAbroad('pg');" blurmethod="testAbroadGraduationLevel();"> Post Graduation</div>
                                <div class="errorMsg">
                                <div id="desiredCourseLevel_unifiedregistration_error" class="errorMsg" style="padding-left:5px;"></div>
                                </div>
                                </div>
                                <div class="clear_L">&nbsp;</div>
                              </div>
                                 <?php $this->load->view('user/unifiedregistration/xii_details_field');?>
                                 <?php $this->load->view('user/unifiedregistration/graduation_details_field');?>
                                    <div class="float_L w165 tar">Destination Country(s)<b class="redcolor">*</b>: &nbsp; </div>
                                    <div style="margin-left:169px;margin-bottom:7px;">
                                    <div style="float:left;width:100%">
                                        <div class="float_L" style="width:150px;background:url(/public/images/bgDropDwn.gif) no-repeat left top;height:19px" onclick="ShikshaUnifiedRegistarion.showDestinationCountry(this, true);">
                                        <div style="font-size: 13px;" id="studyPreferedCountry_unifiedregistration">&nbsp;Select</div>
                                       <div class="clear_L withClear">&nbsp;</div>
                                     </div>
                                    </div>
                                     <div>
                                       <div class="errorPlace">
                                       <div id="studyPreferedCountry_unifiedregistration_error" style="*margin-left:4px"  class="errorMsg" ></div>
                                      </div>
                                     </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">When do you plan to go?<b class="redcolor">*</b>: &nbsp; </div>
                                    <div class="float_L">
                                        <div><select style="width:210px" id="when_plan_start_unifiedregistration" caption="when you plan to start the course" validate="validateSelect" required="true" name="plan" onchange="if(this.value!='') {$('howplantofund_unifiedregistration').style.display = 'block';
$('examtakenblock_unifiedregistration').style.display = 'block';$('UserFundsOwn_unifiedregistration').focus();} else
{$('howplantofund_unifiedregistration').style.display = 'none';$('examtakenblock_unifiedregistration').style.display = 'none';}">

                                             <?php
						$array= array(
						'' => 'Select',
						date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))) => date("Y"),
						date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y") +1)) => date("Y") + 1,
						date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y") +2)) => 'Later',
						);
					    $selected = $data[0]['PrefData'][0]['TimeOfStart'];
					    foreach ($array as $key => $value)  {
							if ($selected == $key ) {
							    $selected_string = "selected";
							} else {
							    $selected_string = "";
							}
						echo '<option '.$selected_string.' value="'.$key.'">'.$value.'</option>';
					    }
					    ?>
                                     </select></div>
                                    <div class="errorMsg">
                                        <div  id="when_plan_start_unifiedregistration_error" class="errorMsg w165" style="width:208px"></div>
                                    </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php $this->load->view('user/unifiedregistration/source_of_funding_field');?>
                                <?php $this->load->view('user/unifiedregistration/exam_taken_field');?>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">Residence Location<b class="redcolor">*</b>: &nbsp; </div>
                                    <div class="float_L">
                                        <div><select style="width:210px"  name="citiesofresidence1" id="citiesofquickreg_unifiedregistration" blurMethod = "ShikshaUnifiedRegistarion.validate_residencecombo();" required="true" caption="specify your city of residence"><option value="">Select</option>
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
                                <div class="wdh100 mb10">
                                    <div class="mlr10 bgcolor_gray pf10">
                                        <div class="Fnt11 mb7">Would you like partner consultants to call you &amp; assist with process:</div>
                                        <div style="margin-left: 145px;">
                                            <div>
                                               <select id="suitableCallPref_unifiedregistration" name="suitableCallPref" style="width: 210px;">
                                                <option value="">Select</option>
						<option value="1">Yes, Call anytime</option>
						<option value="2">Yes, Call me in the morning</option>
						<option value="3">Yes, Call me in the evening</option>
						<option value="0">No</option>
                                              </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">Age: &nbsp; </div>
                                    <div class="float_L">
                                        <div><input type="text" id = "quickage_unifiedregistration" name = "quickage" type="text" minlength = "2" maxlength = "2" value="" validate = "validateAge" caption = "age" style="width:30px"/></div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <div class="errorMsg"><div id= "quickage_unifiedregistration_error" class="errorMsg" style="position:relative;left:165px;top:-7px;"></div></div>
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
                                    <div id="cAgree_unifiedregistration_error" class="errorMsg" style="margin-left: 163px;"></div>
                                    </div>
                                </div>
                                <div class="wdh100 mb10">
                                    <div class="float_L w165 tar">&nbsp;</div>
                                    <div class="float_L">
                                        <div><input id="save_button_unifiedregistration" type="submit" class="fbBtn" value="Save" uniqueattr="UnifiedLayer3SaveButton"/> &nbsp; <a href="#" onclick="ShikshaUnifiedRegistarion.actionAfterUnifiedLayerClicked(true,'true','is_unified_overlay3_clicked', 'true',page_identifier_unified);" >Later</a></div>
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
<div id="helpbubble1_unifiedregistration" style="position: absolute; left: 553px; top: 417px; z-index: 10000;display:none;">
<div class="clear_L">&nbsp;</div>
<div style="background-color: rgb(242, 228, 171); padding: 10px; width: 150px;" id="hintbox1_unifiedregistration" class="float_L normaltxt_11p_blk_verdana"></div>
<div class="float_L"><img src="/public/images/help_bubble_r.gif"></div>
<div class="clear_L">&nbsp;</div>
</div>
<?php $this->load->view('user/unifiedregistration/studyCountryOverlay.php');?>
<script>
try {
var first_name_unified = "<?php if(is_array($userData['0']))echo addslashes($userData['0']['firstname'])?>";
document.getElementById('display_username_unifiedregistration').innerHTML = truncateString(first_name_unified,80);
document.getElementById('display_username_unifiedregistration').setAttribute("title",first_name_unified);
addOnBlurValidate($('default_type3_unified_form'));
} catch(e) {
alert(e);
}
function testAbroadGraduationLevel() {
if (!$('desiredCourseLevel_ug_unifiedregistration').checked && !$('desiredCourseLevel_pg_unifiedregistration').checked) {
	$('desiredCourseLevel_unifiedregistration_error').innerHTML = 'Please select the desired course level';
	$('desiredCourseLevel_unifiedregistration_error').parentNode.style.display = 'inline';
}
}
$('input_widget_identifier_unified').value = unified_widget_identifier;
$('input_prefid_unified').value = unified_registration_ldb_user_pref_id;
</script>
