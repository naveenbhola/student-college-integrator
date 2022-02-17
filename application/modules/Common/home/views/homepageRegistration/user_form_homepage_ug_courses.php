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
<!-- hidden field to identify it is a it Graduation Courses page -->
<input type = "hidden" name = "mpagename" id = "mpagename" value = "graduate_course"/>

<div id="study_mode_block" >
    <label class="label2">Mode:</label>
    <div class="formCont">
		<input blurMethod="check_mode();" type="checkbox" id="mode_full" name="mode[]" value="full_time"> Full Time &nbsp;
        <input blurMethod="check_mode();"  id="mode_part" type="checkbox" name="mode[]" value="part_time"> Part Time
        <div class="clearFix"></div>
         <div>
		<div class="errorMsg" id="mode_error"></div>
		</div>
    </div>
</div>

<div>
<div class="spacer10 clearFix"></div>
    <div id ="marginDiv"  style="float:left;width:100%">
    	<div class="selectStyleDiv" onclick="trackEventByGA('PreferredStudyLocationClick','PreferredStudy cities overlay clicked');abc(this);">
        	<span class="selectStyleArrow"></span>
            <div id="marketingPreferedCity">Preferred Study Location(s)</div>
        </div>
     </div>
        <div>
            <script>
            if(document.getElementById("marketingPreferedCity"))
            document.getElementById("marketingPreferedCity").innerHTML= "Preferred Study Location(s)";
            if(document.getElementById("mCityList"))
            document.getElementById("mCityList").value = "";
            </script>
             <div>
            <div class="errorMsg" id="preferedLoc_error"></div>
            </div>
        </div>
</div>
<?php
if ((count($userCompleteDetails) > 0) && (isset($validateuser[0]['firstname']))) {
	$userCompleteDetails = json_decode($userCompleteDetails,true);
	foreach($userCompleteDetails as $userCompleteDetails) {
		$userCompleteDetails = $userCompleteDetails;
	}
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
<div class="spacer10 clearFix"></div>
    <label class="label2">Std. XII Stream:</label>
    <div class="formCont">
    	<input name="science_stream" blurmethod="xii_stream_check();" onclick="xii_stream_check();" <?php if ($Name == 'science') { echo "checked"; } ?> id="science_stream" type="radio" value="science_stream" /> Science&nbsp; <input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream"
	    <?php if ($Name == 'arts') { echo "checked"; } ?> id="science_arts" type="radio" value="science_arts"  /> Arts&nbsp; <input blurmethod="xii_stream_check();" onclick="xii_stream_check();" name="science_stream" <?php if ($Name == 'commerce') { echo "checked"; } ?> id="science_commerce" type="radio" value="science_commerce"  /> Commerce
        <div class="clearFic"></div>
		<div class="errorPlace">
		 <div>
        	<div id= "science_commerce_error" class="errorMsg"></div></div>
        	 <div>
            <div id= "science_arts_error" class="errorMsg"></div></div>
             <div>
            <div id= "science_stream_error" class="errorMsg"></div></div>
		</div>
     </div>
</div>


<!--<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>-->
<div>
<div class="spacer10 clearFix"></div>
   <label>Std. XII Details:</label>
   <div class="formCont">
	<select  style="width:80px !important" validate = "validateSelect" required="true" caption = " XII completion year" id="10_com_year_year" name="10_com_year_year">
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
	</select> &nbsp; 
	<select style="width:80px !important" required="true" name = "10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks">	<option value="">Marks</option>
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
	<div class="clearFix"></div>
	 <div>
	<div class="errorMsg" id= "10_com_year_year_error"></div></div>
	 <div>
	<div class="errorMsg" id= "10_ug_detials_courses_marks_error"></div></div>
 </div>
</div>

<div>
<div class="spacer10 clearFix"></div>
	<select name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start"><option value="">When do you plan to start ?</option><?php echo $when_you_plan_start; ?></select>
    <div class="clearFix"></div>
     <div><div class="errorMsg" id="when_plan_start_error"></div></div>
</div>

<!--<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>-->

<div>
<div class="spacer10 clearFix"></div>
	<input class="form-txt-field" type="text" caption="name" required="1" validate="validateDisplayName" minlength="1" maxlength="100" tip="displayname_id" size="30" id="firstname" name="firstname" value = "<?php if($userarray['name']){echo $userarray['name'];} else {echo 'Your Name';}?>" onfocus="checkTextElementOnTransition(this,'focus')" default="Your Name"/>
	<div class="clearFix"></div>
	 <div>
    <div class="errorMsg" id="firstname_error"></div></div>
</div>

<div>
<div class="spacer10 clearFix"></div>
	<input class="form-txt-field" type="text" validate="validateEmail" caption="email address" <?php echo $userarray['emailenable']?> required="1" maxlength="125"             tip="email_idM" id="email" name="email" value = "<?php if($userarray['email']){echo $userarray['email'];} else {echo 'Email';}?>" onfocus="checkTextElementOnTransition(this,'focus')" default="Email"/>
    <div class="clearFix"></div>
     <div>
    <div class="errorMsg" id="email_error"></div></div>
</div>

<div>
<div class="spacer10 clearFix"></div>
	<input class="form-txt-field" type="text" caption="mobile number" tip="mobile_numM" required="1" blurMethod='removetip();' size="30" maxlength="10" minlength="10" id="mobile" name="mobile" value = "<?php if($userarray['mobile']){echo $userarray['mobile'];} else {echo 'Mobile No';}?>" onfocus="checkTextElementOnTransition(this,'focus')" default="Mobile"/>
    <div class="clearFix"></div>
     <div>
	<div class="errorMsg" id="mobile_error"></div></div>
</div>

<div>
<div class="spacer10 clearFix"></div>
	<select  caption = "city of residence" validate = 'validateSelect' required = 'true' id ="citiesofquickreg" name = "citiesofquickreg" blurMethod = "validate_combo();" >
            <option value="">Residence Location</option>
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

    <div class="clearFix"></div>
     <div>
    <div class="errorMsg" id="citiesofquickreg_error"></div></div>
</div>

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
    if(document.getElementById("marketingPreferedCity"))
    document.getElementById("marketingPreferedCity").innerHTML= <?php echo "'Selected (".$num_cities.")'";?>;
    if(document.getElementById("mCityList"))
    document.getElementById("mCityList").value = "<?php echo $str; ?>";
<?
    }
?>
</script>
