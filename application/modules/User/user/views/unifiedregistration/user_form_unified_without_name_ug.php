<!-- hidden field to identify it is a it Graduation Courses page -->
<input type = "hidden" name = "mpagename" id = "mpagename" value = "graduate_course"/>

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
            <div class="errorMsg" id="mode_unifiedregistration_error" style="padding-left: 12px;*padding-left:4px;"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
<div class="lineSpace_10" id="study_mode_block_ls_unifiedregistration">&nbsp;</div>
</div>
<div id="preferredstudylocation_unifiedregistration" style="display:none">
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Preferred Study Location(s)<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
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
<div class="lineSpace_10">&nbsp;</div>
</div>
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div>
            <select style="font-size:11px;width:212px" name = "plan" blurMethod="ShikshaUnifiedRegistarion.when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start_unifiedregistration" onchange="ShikshaUnifiedRegistarion.setFieldsVisibilityOrder(when_plan_start_array_unifiedregistration,this.value);"><option value="">Select</option><?php echo $when_you_plan_start; ?></select>
        </div>
        <div>
            <div class="errorMsg" id="when_plan_start_unifiedregistration_error" style="padding-left: 8px;*margin-left:-3px;"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div  class="lineSpace_10">&nbsp;</div>
<div id="showUGSection_unifiedregistration" style="display:none;">
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Stream<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div style="position: relative; left: -5px;"><input name="science_stream" blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" id="science_stream_unifiedregistration" type="radio" value="science_stream" />Science&nbsp;<input blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" name="science_stream" id="science_arts_unifiedregistration" type="radio" value="science_arts"  />Arts&nbsp;<input blurmethod="ShikshaUnifiedRegistarion.xii_stream_check();" onclick="ShikshaUnifiedRegistarion.xii_stream_check();" name="science_stream" id="science_commerce_unifiedregistration" type="radio" value="science_commerce"  />Commerce</div>
		<div class="errorPlace" style=""><div id= "science_commerce_unifiedregistration_error" style ="padding-left: 7px;" class="errorMsg"></div></div>
		<div class="errorPlace" style=""><div id= "science_arts_unifiedregistration_error" style ="padding-left: 7px;" class="errorMsg"></div></div>
		<div class="errorPlace" style=""><div id= "science_stream_unifiedregistration_error" style ="padding-left: 7px;" class="errorMsg"></div></div>
	</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:190px">
        <div>
	<select  validate = "validateSelect" style="width:107px"required="true" caption = " XII completion year" id="10_com_year_year_unifiedregistration" name="10_com_year_year">
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
	    <div class="errorMsg" id= "10_com_year_year_unifiedregistration_error"  style=" padding-left: 7px;*margin-left:-2px;"></div>
	</div>
        <div>
            <div class="errorMsg" id= "10_ug_detials_courses_marks_unifiedregistration_error" style="padding-left: 7px;*margin-left:-2px;"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div  class="lineSpace_10">&nbsp;</div>
</div>
<div>
    <div class="float_L" style="width:190px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Residence Location<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left:190px">
    <div>
        <select  caption = "specify your city of residence" validate = 'validateSelect' required = 'true'  style = "width:211px" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg_unifiedregistration" name = "citiesofquickreg" blurMethod = "ShikshaUnifiedRegistarion.validate_residencecombo();" >
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
        <div class="errorMsg" id="citiesofquickreg_unifiedregistration_error" style="padding-left:7px;*padding-left:4px;"></div>
    </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
