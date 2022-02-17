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

<input type = "hidden" name = "mpagename" id = "mpagename" value = "graduate_course"/>
<div>
<div class="lineSpace_10" style="clear: both">&nbsp;</div>
<div id="study_mode_block" style=" width: 100%; display: block">
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
<div class="lineSpace_10" style="clear: both">&nbsp;</div>
<div>
    
    <div style="clear: both; width: 100%">Preferred Study Location(s)<b class="redcolor">*</b>:</div>
    
    <div style="width: 100%; display: block; position: relative">
        <div style="width:150px;background:url(/public/images/bgDropDwn.gif) no-repeat left top;height:19px; width: 100%; display: block" onclick="trackEventByGA('PreferredStudyLocationClick','PreferredStudy cities overlay clicked');abc1(this);">
                    <div id="marketingPreferedCity" style="position: relative;top: 1px; width: 100%; display: block; clear: left">&nbsp;Select</div>
       
                </div>
        
        
            <script>
            document.getElementById("marketingPreferedCity").innerHTML= "&nbsp;Select";document.getElementById("mCityList").value = "";
            </script>
            <div> <div class="errorMsg" id="preferedLoc_error" style="*margin-left:4px"></div></div>
        
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<?php
if ((count($userCompleteDetails) > 0) && (isset($validateuser[0]['firstname']))) {
    $age = $userCompleteDetails[0]['age'];
    $gender = $userCompleteDetails[0]['gender'];
    foreach ($userCompleteDetails[0]['EducationData'] as $xii_data) {
	if ( $xii_data['Level'] == '12' ) {
	    $Marks = $xii_data['Marks'];
	    $MarksType = $xii_data['MarksType'];
	    $Name = $xii_data['Name'];
	    $date = $xii_data['CourseCompletionDate'];
	    list($y_c, $m_c, $d_c) = explode('-', $date);
	    $date = $y_c;
	}
    }
    $otherdetails = $userCompleteDetails[0]['PrefData'][0]['UserDetail'];
}
?>
<div  class="lineSpace_10">&nbsp;</div>
<div>
    <div>
        <div>Std. XII Stream<b class="redcolor">*</b>:</div>
    </div>
    <div>
        <div><input name="science_stream" blurmethod="xii_stream_check();" onclick="xii_stream_check();" <?php if ($Name == 'science') { echo "checked"; } ?> id="science_stream" type="radio" value="science_stream" />Science&nbsp;<input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream"
	    <?php if ($Name == 'arts') { echo "checked"; } ?> id="science_arts" type="radio" value="science_arts"  />Arts&nbsp;<input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php if ($Name == 'commerce') { echo "checked"; } ?> id="science_commerce" type="radio" value="science_commerce"  />Commerce</div>
		<div class="errorPlace" style=""><div id= "science_commerce_error" class="errorMsg"></div></div>
		<div class="errorPlace" style=""><div id= "science_arts_error" class="errorMsg"></div></div>
		<div class="errorPlace" style=""><div id= "science_stream_error" class="errorMsg"></div></div>
	</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>
<div>
    <div>
        <div>Std. XII Details<b class="redcolor">*</b>:</div>
    </div>
    <div>
        <div>
	<select  validate = "validateSelect" required="true" caption = " XII completion year" id="10_com_year_year" id="10_com_year_year" name="10_com_year_year" style="font-size:11px;">
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
	<select required="true" caption="XII marks" style="font-size:11px;width:100px" name = "10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks"><option value="">Marks</option>
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
	    <div class="errorMsg" id= "10_com_year_year_error"  style="*margin-left:4px"></div>
	    <div class="errorMsg" id= "10_ug_detials_courses_marks_error" style="*margin-left:4px"></div>
	</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div  class="lineSpace_10">&nbsp;</div>
<div>
    <div>
        <div>When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div>
        <div>
            <select style="font-size:11px;width:190px" name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start"><option value="">Select</option><?php echo $when_you_plan_start; ?></select>
        </div>
        <div>
            <div class="errorMsg" id="when_plan_start_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>

<div class="lineSpace_10">&nbsp;</div>
<!--End_Hyperlink for add another perference -->
<div>
    <div>
        <div>Name<b class="redcolor">*</b> : </div>
    </div>
    <div style="">
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
        <div>Mobile<b class="redcolor">*</b> : </div>
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
