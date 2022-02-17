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
<div id="showUGSection">
<!-- UG Fields start -->
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Status<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
        <div>
            <input onclick="ug_status();" blurMethod="ug_status();" name="Completed" id="ug_Completed" type="radio" value="completed" caption="Completed" <?php echo $userarray['UGcompleted']?> />Completed &nbsp;<input onclick="ug_status();" blurMethod="ug_status();" name="Completed" id="ug_Pursuing" type="radio" value="Pursuing" caption="Pursuing" <?php echo $userarray['UGongoing']?>/>Pursuing
        </div>
        <div>
            <div class="errorMsg" id="ug_status_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
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
        <input type="text" caption="name" required="1" validate="validateDisplayName" minlength="1" maxlength="100" tip="displayname_id" size="30" id="firstname" style="width:150px" name="firstname" class="txt_1" value = "<?php echo $userarray['name']?>"/>
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
            tip="email_idM" id="email" name="email" class="txt_1" value = "<?php echo $userarray['email']?>"/>
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
        required="1" blurMethod='removetip();' size="30" maxlength="10" minlength="10" id="mobile" name="mobile" class="txt_1" value = "<?php echo $userarray['mobile']?>" />
        </div>
        <div>
            <div class="errorMsg" id="mobile_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>