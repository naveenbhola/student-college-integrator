<!-- hidden field to identify it is a it course page -->
<input type = "hidden" name = "mpagename" id = "mpagename" value = "it_courses"/>
<input type="hidden" name ="local_course_type" value = "ug"/>
<div style="padding-bottom:10px;">
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div>
            <select style="font-size:11px;width:211px" name = "plan" blurMethod="ShikshaUnifiedRegistarion.when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start_unifiedregistration" onchange="ShikshaUnifiedRegistarion.setFieldsVisibilityOrder(when_plan_start_array_local_unifiedregistration,this.value);"><option value="">Select</option>
            <?php
            $array= array(
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))) => 'Immediately',
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m")+2,date("d"),date("Y"))) => 'Within 2 Months',
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m")+3,date("d"),date("Y"))) => 'Within 3 Months',
                '0000-00-00 00:00:00' => 'Not Sure'
            );
            foreach ($array as $key => $value)  {
                echo '<option value="'.$key.'">'.$value.'</option>';
            }
            ?>
            </select>
        </div>
        <div>
            <div class="errorMsg" id="when_plan_start_unifiedregistration_error" style="padding-left:5px;*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div id="showUGSection_unifiedregistration" style="display:none;">
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Stream<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div style="position: relative; left: -5px;"><input name="science_stream" blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" id="science_stream_unifiedregistration" type="radio" value="science_stream" />Science&nbsp;<input blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" name="science_stream"
	    id="science_arts_unifiedregistration" type="radio" value="science_arts"  />Arts&nbsp;<input blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" name="science_stream"id="science_commerce_unifiedregistration" type="radio" value="science_commerce"  />Commerce</div>
		<div class="errorPlace" style=""><div id= "science_commerce_unifiedregistration_error" class="errorMsg" style="padding-left:7px"></div></div>
		<div class="errorPlace" style=""><div id= "science_arts_unifiedregistration_error" class="errorMsg" style="padding-left:7px"></div></div>
		<div class="errorPlace" style=""><div id= "science_stream_unifiedregistration_error" class="errorMsg" style="padding-left:7px"></div></div>
	</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>

<div id="completion_date_block_ls_unifiedregistration" class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div>
	<select  validate = "validateSelect" required="true" caption = " XII completion year" id="10_com_year_year_unifiedregistration" name="10_com_year_year">
	<option value="">Year</option>
	<?php
	for($i= (date("Y")); $i >= 1990; $i--) {
	    if ($i == $date) {
		echo "<option  selected value='$i'>".$i."</option>";
	    } else {
		echo "<option value='$i'>".$i."</option>";
	    }                                    
	}
	?>
	</select>
	<select required="true" caption="XII marks" style="font-size:11px;width:100px" name = "10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks_unifiedregistration"><option value="">Marks</option>
	<?php
	for ($i = 100; $i >= 33; $i--) {
	    if ($Marks == $i) {
		echo "<option selected value='".$i."'>" . $i . "%</option>";
	    } else {
		echo "<option value='".$i."'>" . $i . "%</option>";
	    }                                    
	}
	?>
	</select>
	</div>
        <div>
	    <div class="errorMsg" id= "10_com_year_year_unifiedregistration_error"  style="padding-left:8px;*margin-left:-2px"></div>
	</div>
        <div>
            <div class="errorMsg" id= "10_ug_detials_courses_marks_unifiedregistration_error" style="padding-left:8px;*margin-left:-2px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
<div id="PreferredLocalities_unifiedregistration" style="display:none">
<div style="padding-left:40px"><b>Preferred Localities for Studies</b></div>
<div class="lineSpace_5">&nbsp;</div>
<!--Start_perference 1 and perference 2 main Div -->
<div>
    <div>
	<div class="float_L" style="width:190px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 1: </div></div>
	<div style="margin-left:190px">
	    <div><select style="width:109px" onchange="ShikshaUnifiedRegistarion.load_preference_localities(this);" id="perference1_unifiedregistration" name="perferencecity[]"><option value="" style="width:109px;">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:109px;" id="perferencelocality1_unifiedregistration" name="perferencelocality[]"><option value="">Select Area</option></select></div>
	    <div><div class="errorMsg" id="perference1_unifiedregistration_error" style="*padding-left:4px;"></div></div>
	</div>
	<div class="clear_L withClear">&nbsp;</div>
    </div>
    <div id="perference2_block_unifiedregistration">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:190px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 2: </div></div>
	    <div style="margin-left:190px">
		<div><select style="width:109px" onchange="ShikshaUnifiedRegistarion.load_preference_localities(this);" id="perference2_unifiedregistration" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:109px" id="perferencelocality2_unifiedregistration" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference2_unifiedregistration_error" style="*padding-left:4px;"></div></div>
	    </div>
	    <div class="clear_L withClear">&nbsp;</div>
	</div>
    </div>
    <div id="perference3_block_unifiedregistration" style="display:none;">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:190px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 3: </div></div>
	    <div style="margin-left:190px">
		<div><select style="width:109px" onchange="ShikshaUnifiedRegistarion.load_preference_localities(this);" id="perference3_unifiedregistration" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:109px"  id="perferencelocality3_unifiedregistration" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference3_unifiedregistration_error" style="*padding-left:4px;"></div></div>
	    </div>
	    <div class="clear_L withClear">&nbsp;</div>
	</div>
    </div>
    <div id="perference4_block_unifiedregistration" style="display:none;">
	<div class="lineSpace_7">&nbsp;</div>
	<div>
	    <div class="float_L" style="width:190px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preference 4: </div></div>
	    <div style="margin-left:190px">
		<div><select style="width:109px" onchange="ShikshaUnifiedRegistarion.load_preference_localities(this);" id="perference4_unifiedregistration" name="perferencecity[]"><option value="">Select City</option><?php echo $select_city_list;?></select>&nbsp;&nbsp;<select style="width:109px"  id="perferencelocality4_unifiedregistration" name="perferencelocality[]" ><option value="">Select Area</option></select></div>
		<div><div class="errorMsg" id="perference4_unifiedregistration_error" style="*padding-left:4px;"></div></div>
	    </div>
	    <div class="clear_L withClear">&nbsp;</div>
	</div>
    </div>
</div>
<!--End_perference 1 and perference 2 main Div -->
<!--Start_Hyperlink for add another perference -->
<div class="lineSpace_10">&nbsp;</div>
<div id="action_block_unifiedregistration">
    <div class="float_L">&nbsp;</div>
    <div style="margin-left:190px">
	<div><a onclick="ShikshaUnifiedRegistarion.addMorePreference();" href="javascript:void(0);" >+ Add another preference</a></div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div><div class="errorMsg" id="perference5_unifiedregistration_error" style="*padding-left:4px;margin-left:167px;"></div></div>
<div class="lineSpace_10">&nbsp;</div>
<!--End_Hyperlink for add another perference -->
</div>
    <div id="residenceLocation_unifiedregistration" style="display:none;">
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Residence Location<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left:190px">
    <div>
        <select  caption = "specify your city of residence" style = "width:150px" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg_unifiedregistration" name = "citiesofquickreg" blurMethod = "ShikshaUnifiedRegistarion.validate_residencecombo();" >
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
        <div class="errorMsg" id="citiesofquickreg_unifiedregistration_error" style="padding-left:7px;*padding-left:4px"></div>
    </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
