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
	<select name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start">
    	<option value="">When do you plan to start ?</option>
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
        <div>
    	<div class="errorMsg" id="when_plan_start_error"></div>
    	</div>
</div>
<div id="study_mode_block" style="display: none" >
<div class="spacer10 clearFix"></div>
    <label>Mode:</label>
    <div class="formCont">
    	<input blurMethod="check_mode();" type="checkbox" id="mode_full" name="mode[]" value="full_time" <?php echo $userarray['fulltime']?>> Full Time &nbsp;&nbsp;
        <input blurMethod="check_mode();"  id="mode_part" type="checkbox" name="mode[]" value="part_time" <?php echo $userarray['parttime']?>> Part Time
	</div>
    <div class="clearFix spacer5"></div>
    <div>
    <div class="errorMsg" id="mode_error"></div>
    </div>
</div>
<!--<div id="preferedLoc_error" style="display :none;"></div>-->
<!-- UG Fields start -->

<div id="showUGSection" style="display:none">
<div class="spacer10 clearFix"></div>
<div style="display: none">

    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Status: <b style="color:red;">*</b></div>
    </div>
    <div >
        <div>
            <input onclick="document.getElementById('completion_date_block').style.display='block';document.getElementById('graduationDetails').style.display='block';ug_status();" blurMethod="document.getElementById('completion_date_block').style.display='block';document.getElementById('graduationDetails').style.display='block';ug_status();" name="Completed" id="ug_Completed" type="radio" value="completed" caption="Completed" <?php echo $userarray['UGcompleted']?> />Completed &nbsp;<input onclick="document.getElementById('completion_date_block').style.display='block';document.getElementById('graduationDetails').style.display='block';ug_status();" blurMethod="document.getElementById('completion_date_block').style.display='block';document.getElementById('graduationDetails').style.display='block';ug_status();" name="Completed" id="ug_Pursuing" type="radio" value="Pursuing" caption="Pursuing" <?php echo $userarray['UGongoing']?>/>Pursuing
        </div>
        <div>
            <div class="errorMsg" id="ug_status_error" style="*padding-left:4px"></div>
        </div>
    </div>
</div>
<div id="graduationDetails" style="display: none">
<div class="spacer10 clearFix"></div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Details:<b style="color:red;">*</b></div>
    </div>
    <div >
        <div>
            <select style="font-size:11px;width:150px" onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" name = "ug_detials_courses" validate = "validateSelect" required = "true" caption = "graduation course" id="ug_detials_courses">
            <option value="">Select</option>
            <?php echo $course_lists; ?>
            </select>
            <select tip="<?php echo (!empty($userarray['UGongoing'])?"ug_passed":"ug_completed");?>" blurMethod="ug_detials_courses_marks();" style="font-size:11px;" name = "ug_detials_courses_marks" validate = "validateSelect" required = "true" caption = "marks" id="ug_detials_courses_marks"><option value="">Marks</option>
            <?php
                for ($i = 100; $i >= 33; $i--) {
                    if (isset($ug_marks)) {
                        if ($ug_marks == $i) {
                            $flag_marks = "selected";
                        } else {
                            $flag_marks = "";
                        }
                    }
                    echo "<option ".$flag_marks." value='".$i."'>" . $i . "%</option>";
                }
            ?>
            </select>
        </div>
        <div>
            <div class="errorMsg" id="ug_detials_courses_error" style="*padding-left:4px"></div>
        </div>
        <div>
        <div class="errorMsg" id="ug_detials_courses_marks_error" style="*padding-left:4px"></div>
        </div>
            <?php
            if ($userarray['UGongoing'] == 'checked') {
            ?>
            <script>
                document.getElementById('ug_detials_courses_marks').style.display = "none";
                document.getElementById('ug_detials_courses_marks_error').style.display = "none";
            </script>
            <?php
            }
            ?>
    </div>
</div>
<div id="completion_date_block" style="display: none">
<div class="spacer10 clearFix"></div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Completion Date:<b style="color:red;">*</b></div>
    </div>
    <div >
    <div>
    <?php
        if (isset($CourseCompletionDate)) {
            list($y_c, $m_c, $d_c) = explode('-', $CourseCompletionDate);
        }
        $str ='<select style="font-size: 11px;" tip="" validate = "validateSelect" required = "true" caption = "expected date of course completion" id="com_year_month" name="com_year_month" >';
        $str.='<option value="">Month</option>';
        $this_month = $m_c;
        $months=range(1,12);
        foreach($months as $month){
        $month = sprintf("%02d",$month);
        $str.=' <option value="'.$month.'"';
        if($month==$this_month) $str.=' selected="selected"';
        $str.='>'.date('M',mktime(0,0,0,$month,1,2006)).'</option>'."\n";
        }
        $str.='</select>'."\n";
        echo $str;
    ?>
    <select  style="font-size: 11px;" tip="" blurMethod="validate_Completion_Date();" caption = "year" id="com_year_year" name="com_year_year">
    <option value="">Year</option>
    <?php
        for($i= date("Y")+5; $i>= 1950; $i--) {
            if ($y_c == $i) {
                echo "<option selected value='$i'>".$i."</option>";
            } else {
                echo "<option  value='$i'>".$i."</option>";
            }
        }
    ?>
    </select>
    </div>
        <div>
            <div class="errorMsg" id="com_year_month_error" style="*padding-left:4px"></div>
        </div>
        <div>
            <div class="errorMsg" id="com_year_year_error" style="*padding-left:4px"></div>
        </div>
    </div>
    
</div>

</div>
<div id="work_experience_block" style="display:none">
<div class="spacer10 clearFix"></div>
	<select caption="your years of experience"  name="ExperienceCombo" id="ExperienceCombo" tip="work_ex" style="width: 150px;">
        <option value="" title="Select">Work Experience</option>
        <?php echo $work_exp_combo;?>
    </select>
    <div>
    <div class="errorMsg" id="ExperienceCombo_error" style="*padding-left:4px"></div>
    </div>
</div>
<div style="display:none;">
<div class="spacer10 clearFix"></div>
<div style="padding-left:40px"><b>Preferred Localities for Studies</b></div>
<div class="lineSpace_5">&nbsp;</div>
<!--Start_perference 1 and perference 2 main Div -->
<div>
    <div>
	<div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 1: </div></div>
	<div >
	    <div><select style="width:100px;font-size:11px;" onchange="load_preference_localities(this);" id="perference1" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:100px;font-size:11px;" id="perferencelocality1" name="perferencelocality[]"><option value="">Select Area</option></select></div>
	    <div><div class="errorMsg" id="perference1_error" style="*padding-left:4px;"></div></div>
	</div>
    </div>
    <div id="perference2_block" style="display:none;">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 2: </div></div>
	    <div >
		<div><select style="width:100px;font-size:11px;" onchange="load_preference_localities(this);" id="perference2" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:100px;font-size:11px;" id="perferencelocality2" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference2_error" style="*padding-left:4px;"></div></div>
	    </div>
	</div>
    </div>
    <div id="perference3_block" style="display:none;">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 3: </div></div>
	    <div >
		<div><select style="width:100px;font-size:11px;" onchange="load_preference_localities(this);" id="perference3" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:100px;font-size:11px;"  id="perferencelocality3" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference3_error" style="*padding-left:4px;"></div></div>
	    </div>
	</div>
    </div>
    <div id="perference4_block" style="display:none;">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 4: </div></div>
	    <div >
		<div><select style="width:100px;font-size:11px;" onchange="load_preference_localities(this);" id="perference4" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:100px;font-size:11px;"  id="perferencelocality4" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference4_error" style="*padding-left:4px;"></div></div>
	    </div>
	</div>
    </div>
</div>
<!--End_perference 1 and perference 2 main Div -->
<!--Start_Hyperlink for add another perference -->

<div id="action_block">
    <div class="float_L">&nbsp;</div>
    <div >
	<div><a onclick="addMorePreference();" href="javascript:void(0);" >+ Add another preference</a></div>
    </div>
</div>
<div><div class="errorMsg" id="perference5_error" style="*padding-left:4px;margin-left:177px;"></div></div>
</div>
<!--End_Hyperlink for add another perference -->
<div>
<div class="spacer10 clearFix"></div>
        <input type="text" caption="name" required="1" class="form-txt-field" validate="validateDisplayName" minlength="1" maxlength="100" tip="displayname_id" size="30" id="firstname" name="firstname" value = "Your Name" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Your Name" />
        <div>
            <div class="errorMsg" id="firstname_error" style="*padding-left:4px"></div>
        </div>
</div>
<div>
<div class="spacer10 clearFix"></div>
            <input type="text" validate="validateEmail" class="form-txt-field" caption="email address" <?php echo $userarray['emailenable']?> required="1" maxlength="125"  tip="email_idM" id="email" name="email"  value = "Email" onfocus="checkTextElementOnTransition(this,'focus')" default="Email"/>
            <div>
                <div class="errorMsg" id="email_error" style="*padding-left:4px"></div>
            </div>
</div>
<div>
<div class="spacer10 clearFix"></div>
        <input  type="text" caption="mobile number" tip="mobile_numM" class="form-txt-field" required="1" blurMethod='removetip();' size="30" maxlength="10" minlength="10" id="mobile" name="mobile"  value = "Mobile No"  onfocus="checkTextElementOnTransition(this,'focus')" default="Mobile"/>
        <div>
            <div class="errorMsg" id="mobile_error" style="*padding-left:4px"></div>
        </div>  
</div>
<div id="residenceLocation">
<div class="spacer10 clearFix"></div>
    <div>
    <div>
        <select  caption = "city of residence" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg" name = "citiesofquickreg" blurMethod = "validate_combo();" >
            <option value=""><b>Residence Location</b></option>
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
                <option <?php echo $optionSelectedStr; ?> value="http://localshikhsa.com/<?php echo $list['cityId']; ?>"><?php echo $list['cityName'];?></option>
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
    <div><div id="marketingPreferedCity" style="display:none;"></div></div>
    </div>
</div>
