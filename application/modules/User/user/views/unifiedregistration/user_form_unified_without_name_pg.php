<!-- hidden field to identify it is a it degree page -->
<input type = "hidden" name = "mpagename" id = "mpagename" value = "it_degree"/>

<div id="study_mode_block_unifiedregistration" style="display:none;">
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Mode<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div style="position:relative;*left:-4px">
        <input blurMethod="ShikshaUnifiedRegistarion.check_mode();" type="checkbox" id="mode_full_unifiedregistration" name="mode[]" value="full_time"> Full Time
        <input blurMethod="ShikshaUnifiedRegistarion.check_mode();"  id="mode_part_unifiedregistration" type="checkbox" name="mode[]" value="part_time"> Part Time
	</div>
        <div>
            <div class="errorMsg" id="mode_unifiedregistration_error" style="padding-left: 12px;*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
<div class="lineSpace_10" id="study_mode_block_ls">&nbsp;</div>
</div>
<div id="preferredstudylocation_unifiedregistration" style="display:none;">
    <div class="float_L" style="width:190px;line-height:18px;">
        <div class="txt_align_r" style="padding-right:5px">Preferred Study Location(s)<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:195px">
        <div style="float:left;width:100%">
            <div style="">
               <div class="float_L" style="width:150px;background:url(/public/images/bgDropDwn.gif) no-repeat left top;height:19px;margin-left:-3px;" onclick="trackEventByGA('PreferredStudyLocationClick','PreferredStudy cities overlay clicked');ShikshaUnifiedRegistarion.abc(this);">
                    <div id="marketingPreferedCity_unifiedregistration" style="font-size:13px;">&nbsp;Select</div>
                </div>
            </div>
        </div>
        <div>
            <script>
            document.getElementById("marketingPreferedCity_unifiedregistration").innerHTML= "&nbsp;Select";document.getElementById("mCityList_unifiedregistration").value = "";
            </script>
            <div class="errorMsg" id="preferedLoc_unifiedregistration_error" style="*margin-left:-2px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div id="degree_preference_block_unifiedregistration" style="display:none;">
<div class="lineSpace_10">&nbsp;</div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Degree Preference<b class="redcolor">*</b>: </div>
    </div>
    <div style="margin-left:190px">
        <div>
            <input onclick="ShikshaUnifiedRegistarion.check_degree_preference1();"  type="checkbox" name="degree_preference[]" id="pref_deg_aicte_unifiedregistration" value="aicte_approved" style="*margin-left:-3px"> AICTE Approved
            <input onclick="ShikshaUnifiedRegistarion.check_degree_preference1();"  type="checkbox" id="pref_deg_ugc_unifiedregistration" name="degree_preference[]" value="ugc_approved"> UGC Approved<br><input onclick="ShikshaUnifiedRegistarion.check_degree_preference1();"  type="checkbox" id="pref_deg_inter_unifiedregistration" name="degree_preference[]" value="international" style="*margin-left:4px;">International Degree
            <input onclick="ShikshaUnifiedRegistarion.check_degree_preference();" type="checkbox" id="pref_deg_any_unifiedregistration" name="degree_preference[]" value="any">No Preference
        </div>
        <div>
            <div class="errorMsg" id="degree_preference_unifiedregistration_error" style="padding-left: 10px;*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div>
            <select style="font-size:11px;width:220px" name = "plan" blurMethod="ShikshaUnifiedRegistarion.when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start_unifiedregistration" onchange="ShikshaUnifiedRegistarion.setFieldsVisibilityOrder(when_plan_start_array_unifiedregistration,this.value);"><option value="">Select</option><?php echo $when_you_plan_start; ?></select>
        </div>
        <div>
            <div class="errorMsg" id="when_plan_start_unifiedregistration_error" style="margin-left:8px;*margin-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>

<div id="showUGSection_unifiedregistration" style="display:none;">
<!-- UG Fields start -->
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Status<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div style="position: relative; left: -5px;">
            <input onclick="ShikshaUnifiedRegistarion.ug_status_india();" blurMethod="ShikshaUnifiedRegistarion.ug_status_india();" name="Completed" id="ug_Completed_unifiedregistration" type="radio" value="completed" caption="Completed"/>Completed &nbsp;<input onclick="ShikshaUnifiedRegistarion.ug_status_india();" blurMethod="ShikshaUnifiedRegistarion.ug_status_india();" name="Completed" id="ug_Pursuing_unifiedregistration" type="radio" value="Pursuing" caption="Pursuing" />Pursuing
        </div>
        <div>
            <div class="errorMsg" id="ug_status_unifiedregistration_error" style="margin-left:7px;*padding-left:0px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div>
            <select style="font-size:11px;width:150px" name = "ug_detials_courses" onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" validate = "validateSelect" required = "true" caption = "your graduation course" id="ug_detials_courses_unifiedregistration">
            <option value="">Select</option>
            <?php echo $course_lists; ?>
            </select>
            <select  blurMethod="ShikshaUnifiedRegistarion.ug_detials_courses_marks();" style="font-size:11px;" name = "ug_detials_courses_marks" validate = "validateSelect" required = "true" caption = "marks" id="ug_detials_courses_marks_unifiedregistration"><option value="">Marks</option>
            <?php
                for ($i = 100; $i >= 33; $i--) {
                    echo "<option value='".$i."'>" . $i . "%</option>";
                }
            ?>
            </select>
        </div>
        <div>
            <div class="errorMsg" id="ug_detials_courses_unifiedregistration_error" style="padding-left: 5px;*padding-left:4px"></div>
        </div>
        <div>
        <div class="errorMsg" id="ug_detials_courses_marks_unifiedregistration_error" style="padding-left: 5px;*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div id="completion_date_block">
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Completion Date<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
    <div>
    <?php
        $str ='<select  style="width:151px;"validate = "validateSelect" required = "true" caption = "expected date of course completion" id="com_year_month_unifiedregistration" name="com_year_month" >';
        $str.='<option value="">Month</option>';
        $this_month = $m_c;
        $months=range(1,12);
        foreach($months as $month){
        $month = sprintf("%02d",$month);
        $str.=' <option value="'.$month.'"';
        $str.='>'.date('M',mktime(0,0,0,$month,1,2006)).'</option>'."\n";
        }
        $str.='</select>'."\n";
        echo $str;
    ?>
    </select>
    <select   blurMethod="ShikshaUnifiedRegistarion.validate_Completion_Date();" caption = "year" id="com_year_year_unifiedregistration" name="com_year_year">
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
            <div class="errorMsg" id="com_year_month_unifiedregistration_error" style="padding-left: 5px;*padding-left:4px"></div>
        </div>
        <div>
            <div class="errorMsg" id="com_year_year_unifiedregistration_error" style="padding-left: 5px;*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div id="completion_date_block_ls_unifiedregistration" class="lineSpace_10">&nbsp;</div>
<!-- UG Fields Close -->
<div class="lineSpace_10" id="work_experience_block_ls_unifiedregistration">&nbsp;</div>
<div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Residence Location<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left:190px">
    <div>
        <select  caption = "specify your city of residence" validate = 'validateSelect' required = 'true'  style = "width:224px" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg_unifiedregistration" name = "citiesofquickreg" blurMethod = "ShikshaUnifiedRegistarion.validate_residencecombo();" >
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
        <div class="errorMsg" id="citiesofquickreg_unifiedregistration_error" style="padding-left: 6px;*padding-left:4px"></div>
    </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
