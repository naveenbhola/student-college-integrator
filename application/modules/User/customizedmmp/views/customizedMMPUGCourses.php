<?php
$an_array = json_decode($userCompleteDetails,true);
$return_array = array();
foreach ($an_array as $key => $val) break;
$return_array[] = $val;
$data = $return_array;
$userarray = json_decode($userDataToShow,true);
if(isset($userarray['name']))
$value = "update";
else
$value = "insert";
?>

<style type="text/css">

.preferred_city_overlay_btn {
        width:150px;
        background:url(/public/images/hpBtn.png) no-repeat scroll left -196px transparent;
        height:19px;
}
.preferred_city_overlay_btn_ug {
        width:150px;background:url(/public/images/hpBtn.png) no-repeat scroll left -196px transparent;height:19px;
}

</style>

<!-- hidden field to identify it is a it Graduation Coures page -->
<input type = "hidden" name = "mpagename" id = "mpagename" value = "graduate_course"/>

<div id="study_mode_block" >
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Mode<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div style="position:relative;*left:-4px">
			<div style="float:left;">
				<input style="margin-left:0px;height:auto;" blurMethod="check_mode();" type="checkbox" id="mode_full" name="mode[]" value="full_time" <?php //echo $userarray['fulltime']?>>
			</div>
			<div style="float:left;margin-top:3px;">
				Full Time	
			</div>
			<div style="float:left;">
				<input style="height:auto;" blurMethod="check_mode();"  id="mode_part" type="checkbox" name="mode[]" value="part_time" <?php //echo $userarray['parttime']?>>
			</div>
			<div style="float:left;margin-top:3px;">
				Part Time
			</div>
		</div>
		<div style="clear:both;"></div>
        <div style="float:left;">
            <div class="errorMsg" id="mode_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>

<div class="lineSpace_10" id="study_mode_block_ls">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Preferred Study Location(s)<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div style="width:100%">
            <div style="*margin-left:3px; width:100%;float:left;">
                <div class="float_L preferred_city_overlay_btn_ug" onclick="trackEventByGA('PreferredStudyLocationClick','PreferredStudy cities overlay clicked');showPreferedCityOverlay(this);">
                    <div id="marketingPreferedCity" style="color:#000;position:relative;top:2px">&nbsp;Select</div>
                </div>
            </div>
        </div>
        <div>
            <script>
            document.getElementById("marketingPreferedCity").innerHTML= "&nbsp;Select";document.getElementById("mCityList").value = "";
            </script>
            <div class="errorMsg" id="preferedLoc_error" style="*margin-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<?php
if ((count($userCompleteDetails) > 0) && (isset($validateuser[0]['firstname']))) {
	$userCompleteDetails = json_decode($userCompleteDetails,true);
	foreach($userCompleteDetails as $userCompleteDetails) {
		$userCompleteDetails = $userCompleteDetails;
	}
	//echo "<pre>";
	//print_r($userCompleteDetails);
	//echo "</pre>";
    $age = $userCompleteDetails['age'];
    $gender = $userCompleteDetails['gender'];
    foreach ($userCompleteDetails['EducationData'] as $xii_data) {
	if ( $xii_data['Level'] == '12' ) {
	    $Marks = $xii_data['Marks'];
	    $MarksType = $xii_data['MarksType'];
	    $Name = $xii_data['Name'];
	    $date = $xii_data['CourseCompletionDate'];
	    list($y_c, $m_c, $d_c) = explode('-', $date);
	    $date = $y_c;
	}
    }
    $otherdetails = $userCompleteDetails['PrefData'][0]['UserDetail'];
}
?>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Stream<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
			<div style="float:left;">
				<input style="margin-left:0px;height:auto;" name="science_stream" blurmethod="xii_stream_check();" onclick="xii_stream_check();" <?php //if ($Name == 'science') { echo "checked"; } ?> id="science_stream" type="radio" value="science_stream" />
			</div>
			<div style="float:left;margin-top:3px;">
				Science&nbsp;
			</div>
			<div style="float:left;">
				<input style="height:auto;" blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php //if ($Name == 'arts') { echo "checked"; } ?> id="science_arts" type="radio" value="science_arts"  />
			</div>
			<div style="float:left;margin-top:3px;">
				Arts&nbsp;
			</div>
			<div style="float:left;">	
				<input style="height:auto;" blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php //if ($Name == 'commerce') { echo "checked"; } ?> id="science_commerce" type="radio" value="science_commerce"  />
			</div>
			<div style="float:left;margin-top:3px;">
				Commerce&nbsp;
			</div>
		</div>
		<div style="clear:both;"></div>
		<div class="errorPlace" style="float:left;">
			<div id= "science_commerce_error" class="errorMsg"></div>
		</div>
		<div class="errorPlace" style="float:left;">
			<div id= "science_arts_error" class="errorMsg"></div>
		</div>
		<div class="errorPlace" style="float:left;">
			<div id= "science_stream_error" class="errorMsg"></div>
		</div>
	</div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>

<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Std. XII Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
	<select style="width:50%;" validate = "validateSelect" required="true" caption = " XII completion year" id="10_com_year_year" id="10_com_year_year" name="10_com_year_year">
	<option value="">Year</option>
	<?php
	for($i= (date("Y")); $i >= 1990; $i--) {
	    if ($i == $date) {
		echo "<option value='$i'>".$i."</option>";
		//echo "<option  selected value='$i'>".$i."</option>";
	    } else {
		echo "<option value='$i'>".$i."</option>";
	    }
	}
	?>
	</select>
	<select style="width:45%;" required="true" caption="XII marks" style="font-size:11px;width:100px" name = "10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks"><option value="">Marks</option>
	<?php
	for ($i = 100; $i >= 33; $i--) {
	    if ($Marks == $i) {
		//echo "<option selected value='".$i."'>" . $i . "%</option>";
		echo "<option value='".$i."'>" . $i . "%</option>";
	    } else {
		echo "<option value='".$i."'>" . $i . "%</option>";
	    }
	}
	?>
	</select>
	</div>
        <div>
			<div class="errorMsg" id= "10_com_year_year_error"  style="*margin-left:4px"></div>
		</div>
		<div>
			<div class="errorMsg" id= "10_ug_detials_courses_marks_error" style="*margin-left:4px">
		</div>
	</div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div  class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
            <select style="font-size:11px;width:90%;" name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start"><option value="">Select</option><?php echo $when_you_plan_start; ?></select>
        </div>
        <div>
            <div class="errorMsg" id="when_plan_start_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">First Name<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
        <input type="text" caption="first name" default="Your First Name" required="1" validate="validateDisplayName" minlength="1" maxlength="50" size="30" id="firstname" style="width:65%" name="firstname" class="txt_1" value = "<?php //if($logged=="No" && isset($userName)) { echo $userName; } else { echo $userarray['name']; } ?>"/>
        </div>
        <div>
            <div class="errorMsg" id="firstname_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Last Name<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
        <input type="text" caption="last name" default="Your Last Name" required="1" validate="validateDisplayName" minlength="1" maxlength="50" size="30" id="lastname" style="width:65%" name="lastname" class="txt_1" value = "<?php //if($logged=="No" && isset($userName)) { echo $userName; } else { echo $userarray['name']; } ?>"/>
        </div>
        <div>
            <div class="errorMsg" id="lastname_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
        <div class="float_L" style="width:35%;line-height:18px">
            <div class="txt_align_r" style="padding-right:5px">Email<b class="redcolor">*</b>:</div>
        </div>
        <div style="width:63%;float:right;text-align:left;">
            <div>
            <input type="text" validate="validateEmail" caption="email address" <?php echo $userarray['emailenable']?> required="1" maxlength="125" style="width:65%;"
            id="email" name="email" class="txt_1" value = "<?php  //if($logged=="No" && isset($userEmail)) { echo $userEmail; } else { echo $userarray['email']; } ?>"/>
            </div>
            <div>
                <div class="errorMsg" id="email_error" style="*padding-left:4px"></div>
            </div>
        </div>
        <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
    <div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Mobile<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
		<!--validate="validateMMPMobileNo"-->
        <input style="width: 65%;" type="text" caption="mobile number" 
        required="1" blurMethod='removetip();' size="30" maxlength="10" minlength="10" id="mobile" name="mobile" class="txt_1" value = "<?php //if($logged=="No" && isset($userContactno)) { echo $userContactno; } else { echo $userarray['mobile']; } ?>" />
        </div>
		<div>
            <div class="errorMsg" id="mobile_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
    <div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Residence Location<b class="redcolor">*</b>:</div>
    </div>
    <div style="float:right;width:63%;text-align:left;">
    <div>
        <select  caption = "city of residence" validate = 'validateSelect' required = 'true'  style = "width:65%;" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg" name = "citiesofquickreg" blurMethod = "validate_combo();" >
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
                <option <?php //echo $optionSelectedStr; ?> value="<?php echo $list['cityId']; ?>"><?php echo $list['cityName'];?></option>
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
                            <option <?php // echo $optionSelectedStr; ?> value="<?php echo $list3['CityId']; ?>"><?php echo $list3['CityName'];?></option>
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
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<script>
<?php
    if((isset($userarray['name'])) && (!empty($userarray['name']))) {
        $userlocpref = $data[0]['PrefData'][0]['LocationPref'];
        $str = '';
        $num_cities = count($userlocpref);
        foreach ($userlocpref as $str_array) {
            $str .= $str_array['CountryId'] . ":";
            $str .= $str_array['StateId'] . ":";
            $str .= $str_array['CityId'] ;
            $str .= ",";
        }
?>
    document.getElementById("marketingPreferedCity").innerHTML= <?php echo "'Selected (".$num_cities.")'";?>;
    document.getElementById("mCityList").value = "<?php echo $str; ?>";
<?
    }
?>
</script>
