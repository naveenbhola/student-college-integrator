<?php
$an_array = json_decode($userCompleteDetails,true);
$return_array = array();
foreach ($an_array as $key => $val) break;
$return_array[] = $val;
$data = $return_array;
$userarray = json_decode($userDataToShow,true);
$otherdetails = $data[0]['PrefData'][0]['UserDetail'];
if(isset($userarray['name']))
$value = "update";
else
$value = "insert";
?>
<!-- hidden field to identify it is a it course page -->
<input type = "hidden" name = "mpagename" id = "mpagename" value = "it_courses"/>
<div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
        <div>
            <select style="font-size:11px;width:190px" name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start"><option value="">Select</option>
            <?php
            $array= array(
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))) => 'Immediately',
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m")+2,date("d"),date("Y"))) => 'Within 2 Months',
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m")+3,date("d"),date("Y"))) => 'Within 3 Months',
                '0000-00-00 00:00:00' => 'Not Sure'
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
            </select>
        </div>
        <div>
            <div class="errorMsg" id="when_plan_start_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<!-- START XII FIELDS -->
<div id="xii_main_div" style="display:none;">
</div>
<!-- END XII FIELDS -->
<!-- UG Fields start -->
<div id="showUGSection" style="display:block;">
<?php
$this->load->view('multipleMarketingPage/ug_details_forms_field');
?>
</div>
<!-- UG Fields end -->
<div class="lineSpace_10">&nbsp;</div>
    <div id="work_experience_block" style="display:none">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Work Experience<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
        <div>
        <select caption="your years of experience"  name="ExperienceCombo" id="ExperienceCombo" tip="work_ex" style="width: 150px;">
        <option value="" title="Select">Select</option>
        <?php echo $work_exp_combo;?>
        </select>
        </div>
        <div>
            <div class="errorMsg" id="ExperienceCombo_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div style="padding-left:40px"><b>Preferred Localities for Studies</b></div>
<div class="lineSpace_5">&nbsp;</div>
<!--Start_perference 1 and perference 2 main Div -->
<div>
    <div>
	<div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 1: </div></div>
	<div style="margin-left:177px">
	    <div><select style="width:157px" onchange="load_preference_localities(this);" id="perference1" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:120px;" id="perferencelocality1" name="perferencelocality[]"><option value="">Select Area</option></select></div>
	    <div><div class="errorMsg" id="perference1_error" style="*padding-left:4px;"></div></div>
	</div>
	<div class="clear_L withClear">&nbsp;</div>
    </div>
    <div id="perference2_block">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 2: </div></div>
	    <div style="margin-left:177px">
		<div><select style="width:157px" onchange="load_preference_localities(this);" id="perference2" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:120px" id="perferencelocality2" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference2_error" style="*padding-left:4px;"></div></div>
	    </div>
	    <div class="clear_L withClear">&nbsp;</div>
	</div>
    </div>
    <div id="perference3_block" style="display:none;">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 3: </div></div>
	    <div style="margin-left:177px">
		<div><select style="width:157px" onchange="load_preference_localities(this);" id="perference3" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:120px"  id="perferencelocality3" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference3_error" style="*padding-left:4px;"></div></div>
	    </div>
	    <div class="clear_L withClear">&nbsp;</div>
	</div>
    </div>
    <div id="perference4_block" style="display:none;">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 4: </div></div>
	    <div style="margin-left:177px">
		<div><select style="width:157px" onchange="load_preference_localities(this);" id="perference4" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:120px"  id="perferencelocality4" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference4_error" style="*padding-left:4px;"></div></div>
	    </div>
	    <div class="clear_L withClear">&nbsp;</div>
	</div>
    </div>
</div>
<!--End_perference 1 and perference 2 main Div -->
<!--Start_Hyperlink for add another perference -->
<div class="lineSpace_10">&nbsp;</div>
<div id="action_block">
    <div class="float_L">&nbsp;</div>
    <div style="margin-left:177px">
	<div><a onclick="addMorePreference();" href="javascript:void(0);" >+ Add another preference</a></div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div><div class="errorMsg" id="perference5_error" style="*padding-left:4px;margin-left:177px;"></div></div>
<div class="lineSpace_10">&nbsp;</div>
<!--End_Hyperlink for add another perference -->
<div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Name<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left:177px">
        <div>
        <input type="text" caption="name" required="1" validate="validateDisplayName" minlength="1" maxlength="100" tip="displayname_id" size="30" id="firstname" style="width:150px" name="firstname" class="txt_1" value = "<?php  if($logged=="No" && isset($userName)) { echo $userName; } else { echo $userarray['name']; } ?>"/>
        </div>
        <div>
            <div class="errorMsg" id="firstname_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>


<div>
        <div class="float_L" style="width:175px;line-height:18px">
            <div class="txt_align_r" style="padding-right:5px">Email<b class="redcolor">*</b> : </div>
        </div>
        <div style="margin-left:177px">
            <div>
            <input type="text" validate="validateEmail" caption="email address" <?php echo $userarray['emailenable']?> required="1" maxlength="125" style="width: 150px"
            tip="email_idM" id="email" name="email" class="txt_1" value = "<?php  if($logged=="No" && isset($userEmail)) { echo $userEmail; } else { echo $userarray['email']; } ?>"/>
            </div>
            <div>
                <div class="errorMsg" id="email_error" style="*padding-left:4px"></div>
            </div>
        </div>
        <div class="clear_L withClear">&nbsp;</div>
</div>
    <div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Mobile<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left:177px">
        <div>
        <input style="width: 150px;" type="text" caption="mobile number" tip="mobile_numM"
        required="1" blurMethod='removetip();' size="30" maxlength="10" minlength="10" id="mobile" name="mobile" class="txt_1" value = "<?php  if($logged=="No" && isset($userContactno)) { echo $userContactno; } else { echo $userarray['mobile']; } ?>" />
        </div>
        <div>
            <div class="errorMsg" id="mobile_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div id="residenceLocation" style="display:none;">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Residence Location<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left:177px">
    <div>
        <select  caption = "city of residence" style = "width:150px" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg" name = "citiesofquickreg" blurMethod = "validate_combo();" >
            <option value=""><b>Select City</b></option>
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
        </select>
    </div>
    <div>
        <div class="errorMsg" id="citiesofquickreg_error" style="*padding-left:4px"></div>
    </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
