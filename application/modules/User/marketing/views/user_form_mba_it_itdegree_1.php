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

<input type = "hidden" name = "mpagename" id = "mpagename" value = "it_degree"/>
<div>
<div id="study_mode_block" style="width: 100%; display: block">
    <div>
        <div>Mode<b class="redcolor">*</b>:</div>
    </div>
    <div>
        <div>
        <input blurMethod="check_mode();" type="checkbox" id="mode_full" name="mode[]" value="full_time" <?php echo $userarray['fulltime']?>> Full Time
        <input blurMethod="check_mode();"  id="mode_part" type="checkbox" name="mode[]" value="part_time" <?php echo $userarray['parttime']?>> Part Time
	</div>
        <div>
            <div class="errorMsg" id="mode_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    
    <div>Preferred Study Location(s)<b class="redcolor">*</b>:</div>
    
    <div style="width: 100%; display: block; position: relative">
        <div style="width:150px;background:url(/public/images/bgDropDwn.gif) no-repeat left top;height:19px; width: 100%; display: block" onclick="trackEventByGA('PreferredStudyLocationClick','PreferredStudy cities overlay clicked');abc1(this);">
                    <div id="marketingPreferedCity" style="position: relative;top: 1px; width: 100%; display: block; ">&nbsp;Select</div>
       
                </div>
        
        
            <script>
            document.getElementById("marketingPreferedCity").innerHTML= "&nbsp;Select";document.getElementById("mCityList").value = "";
            </script>
            <div>            <div class="errorMsg" id="preferedLoc_error" style="*margin-left:4px"></div></div>

        
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10" style="clear: both">&nbsp;</div>
<div>
    <div>
        <div>When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div>
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
    </div>
<div class="clear_L ht_5">&nbsp;</div>
<div id="showUGSection">
<!-- UG Fields start -->
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div>
        <div>Graduation Status<b class="redcolor">*</b>:</div>
    </div>

    <div style="">
        <div>
            <input onclick="ug_status();" blurMethod="ug_status();" name="Completed" id="ug_Completed" type="radio" value="completed" caption="Completed" <?php echo $userarray['UGcompleted']?> />Completed &nbsp;<input onclick="ug_status();" blurMethod="ug_status();" name="Completed" id="ug_Pursuing" type="radio" value="Pursuing" caption="Pursuing" <?php echo $userarray['UGongoing']?>/>Pursuing
        </div>
        <div>
            <div class="errorMsg" id="ug_status_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L ht_5">&nbsp;</div>

</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div>
        <div>Graduation Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="">
        <div>
            <select style="font-size:11px;width:150px" onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" name = "ug_detials_courses" validate = "validateSelect" required = "true" caption = "graduation course" id="ug_detials_courses">

            <option value="">Select</option>
            <?php echo $course_lists; ?></select>

            <select tip="ug_completed" blurMethod="ug_detials_courses_marks();" style="font-size:11px;" name = "ug_detials_courses_marks" validate = "validateSelect" required = "true" caption = "marks" id="ug_detials_courses_marks"><option value="">Marks</option>
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
            ?></select>

        </div>
        <div>
            <div class="errorMsg" id="ug_detials_courses_error" style="padding-left:4px"></div>
        </div>
        <div>
        <div class="errorMsg" id="ug_detials_courses_marks_error" style="padding-left:4px"></div>
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
    <div class="clear_L ht_5">&nbsp;</div>

</div>

<div class="lineSpace_10">&nbsp;</div>
<div id="completion_date_block">
    <div>
        <div>Completion Date<b class="redcolor">*</b>:</div>
    </div>
    <div>
    <div>
    <?php
        if (isset($CourseCompletionDate)) {
            list($y_c, $m_c, $d_c) = explode('-', $CourseCompletionDate);
        }
        $str ='<select tip="" validate = "validateSelect" required = "true" caption = "expected date of course completion" id="com_year_month" name="com_year_month"  style="font-size:11px;">';
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
    </select>
    <select  tip="" blurMethod="validate_Completion_Date();" caption = "year" id="com_year_year" name="com_year_year" style="font-size:11px;">
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
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div id="work_experience_block">
    <div>
        <div>Work Experience<b class="redcolor">*</b>:</div>
    </div>
    <div>
        <div>
        <select caption="your years of experience" required="1" validate="validateSelect" name="ExperienceCombo" id="ExperienceCombo" tip="work_ex" style="font-size:11px;width: 150px;">
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
</div>
<div class="lineSpace_10">&nbsp;</div>
<!--End_Hyperlink for add another perference -->
<div>
    <div>
        <div>Name<b class="redcolor">*</b> : </div>

    </div>
    <div>
        <div>
        <input type="text" caption="name" required="1" validate="validateDisplayName" minlength="1" maxlength="100" tip="displayname_id" size="30" id="firstname" style="width:150px" name="firstname" class="txt_1" value = ""/>
        </div>
        <div>
            <div class="errorMsg" id="firstname_error" style="*padding-left:4px"></div>
        </div>
    </div>

    <div class="clear_L ht_5">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>


<div>
        <div>
            <div>Email<b class="redcolor">*</b> : </div>
        </div>

        <div style="">
            <div>
            <input type="text" validate="validateEmail" caption="email address" <?php echo $userarray['emailenable']?>  required="1" maxlength="125" style="width: 150px"
            tip="email_idM" id="email" name="email" class="txt_1" value = "<?php echo $userarray['email']?>"/>
            </div>
            <div>
                <div class="errorMsg" id="email_error" style="*padding-left:4px"></div>
            </div>
        </div>
        <div class="clear_L ht_5">&nbsp;</div>

</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div>
        <div class="" style="padding-right:5px">Mobile<b class="redcolor">*</b> : </div>
    </div>
    <div style="">
        <div>

        <input style="width: 150px;" type="text" caption="mobile number" tip="mobile_numM"
        required="1" size="30" blurMethod='removetip();' maxlength="10" minlength="10" id="mobile" name="mobile" class="txt_1" value = "<?php echo $userarray['mobile']?>" />
        </div>
        <div>
            <div class="errorMsg" id="mobile_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L ht_5">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div>
        <div>Residence Location<b class="redcolor">*</b> : </div>
    </div>
    <div>
    <div>
        <select  caption = "city of residence" validate = 'validateSelect' required = 'true'  style = "width:150px;font-size:11px;" id ="citiesofquickreg" name = "citiesofquickreg" blurMethod = "validate_combo();" >
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

<div class="lineSpace_10">&nbsp;</div>	    

</div>
