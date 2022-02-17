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
<!-- hidden field to identify it is a it degree page -->
<input type = "hidden" name = "mpagename" id = "mpagename" value = "it_degree"/>
<div id="study_mode_block" >
    <label class="label2">Mode:</label>
    <div class="formCont">
		<input blurMethod="check_mode();" type="checkbox" id="mode_full" name="mode[]" value="full_time"> Full Time &nbsp;
        <input blurMethod="check_mode();"  id="mode_part" type="checkbox" name="mode[]" value="part_time"> Part Time
		<div><div class="errorMsg" id="mode_error"></div></div>
    </div>
</div>
<div>
<div class="spacer10 clearFix"></div>
    <div id ="marginDiv" style="float:left;width:100%">
		<div class="selectStyleDiv" onclick="trackEventByGA('PreferredStudyLocationClick','PreferredStudy cities overlay clicked');abc(this);">
        	<span class="selectStyleArrow"></span>
			<div id="marketingPreferedCity">Preferred Study Location(s)</div>
        </div>
    </div>      
	<div>
		<script>
        document.getElementById("marketingPreferedCity").innerHTML= "Preferred Study Location(s)";document.getElementById("mCityList").value = "";
        </script>
        <div>
        <div class="errorMsg" id="preferedLoc_error"></div>
         <div>
        </div>
    </div>
</div>
</div>
<div id="degree_preference_block" style="display: none">
<div class="spacer10 clearFix"></div>
	<label>Degree Preference</label>
    <div class="formCont">
		<input onclick="check_degree_preference1();"  type="checkbox" name="degree_preference[]" id="pref_deg_aicte" value="aicte_approved" <?php echo $userarray['AICTE']?>> AICTE Approved<br />

            <input onclick="check_degree_preference1();"  type="checkbox" id="pref_deg_ugc" name="degree_preference[]" value="ugc_approved" <?php echo $userarray['UGC']?> > UGC Approved<br />
            <input onclick="check_degree_preference1();"  type="checkbox" id="pref_deg_inter" name="degree_preference[]" value="international" <?php echo $userarray['International']?>> International Degree<br/>
            <input onclick="check_degree_preference();" type="checkbox" id="pref_deg_any" name="degree_preference[]" value="any" <?php echo $userarray['Anydegree']?>> No Preference
    <div class="clearFix"></div>
    <div><div class="errorMsg" id="degree_preference_error"></div></div>
    </div>
</div>

<div>
<div class="spacer10 clearFix"></div>
	<select name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start"><option value="">When do you plan to start ?</option><?php echo $when_you_plan_start; ?></select>
    <div>
    	<div class="errorMsg" id="when_plan_start_error"></div>
    </div>
</div>

<div id="showUGSection">
<div class="spacer10 clearFix"></div>
<!-- UG Fields start -->

<div>
    <label class="label2">Graduation Status:</label>

        <div class="formCont">
            <input onclick="document.getElementById('ug_detials_courses_marks').setAttribute('validate','validateSelect');document.getElementById('ug_detials_courses_marks').setAttribute('required','1');document.getElementById('completion_date_block').style.display='block';document.getElementById('graduationDetails').style.display='block';ug_status();" blurMethod="document.getElementById('completion_date_block').style.display='block';document.getElementById('graduationDetails').style.display='block';ug_status();" name="Completed" id="ug_Completed" type="radio" value="completed" caption="Completed" /> Completed &nbsp;<input onclick="document.getElementById('ug_detials_courses_marks').setAttribute('validate','');document.getElementById('ug_detials_courses_marks').removeAttribute('required');document.getElementById('completion_date_block').style.display='block';ug_status();document.getElementById('graduationDetails').style.display='block';" blurMethod="document.getElementById('completion_date_block').style.display='block';ug_status();document.getElementById('graduationDetails').style.display='block';" name="Completed" id="ug_Pursuing" type="radio" value="Pursuing" caption="Pursuing" /> Pursuing
        </div>
        <div>
            <div class="errorMsg" id="ug_status_error"></div>
        </div>

</div>


<div id="graduationDetails" style="display: none">
	<div class="clearFix spacer10"></div>
	<select name = "ug_detials_courses" onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" validate = "validateSelect" required = "true" caption = "graduation course" id="ug_detials_courses" style="margin-bottom:10px">
            <option value="">Graduation Details</option>
            <?php echo $course_lists; ?>
            </select>
            <div class="clearFix"></div>
            <select style="margin-bottom:10px" tip="<?php echo (!empty($userarray['UGongoing'])?"ug_passed":"ug_completed");?>" blurMethod="ug_detials_courses_marks();" name = "ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="ug_detials_courses_marks"><option value="">Marks</option>
            <?php
                for ($i = 100; $i >= 33; $i--) {
                    echo "<option ".$flag_marks." value='".$i."'>" . $i . "%</option>";
                }
            ?>
            </select>

        <div>
            <div class="errorMsg" id="ug_detials_courses_error"></div>
        </div>
        <div>
        <div class="errorMsg" id="ug_detials_courses_marks_error"></div>
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

<div id="completion_date_block" style="display: none;">
    <label>Completion Date:</label>
    <div class="formCont">
    <?php
        if (isset($CourseCompletionDate)) {
            list($y_c, $m_c, $d_c) = explode('-', $CourseCompletionDate);
        }
        $str ='<select style="width:80px !important" tip="" validate = "validateSelect" required = "true" caption = "expected date of course completion" id="com_year_month" name="com_year_month" >';
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
    &nbsp;<select  style="width:80px !important" tip="" blurMethod="validate_Completion_Date();" caption = "year" id="com_year_year" name="com_year_year">
    <option value="">Year</option>
    <?php
        for($i= date("Y")+5; $i>= 1950; $i--) {
            echo "<option  value='$i'>".$i."</option>";
        }
    ?>
    </select>

        <div>
            <div class="errorMsg" id="com_year_month_error"></div>
        </div>
        <div>
            <div class="errorMsg" id="com_year_year_error"></div>
        </div>
    </div>
</div>

<div id="exams_taken_block" style="<?php if(!empty($userarray['MAT']) || !empty($userarray['CAT'])): echo 'display:block'; else: echo 'display:none'; endif;?>">
	<div class="spacer10 clearFix"></div>
    <label class="label2">Exams Taken:</label>
	<div class="formCont">
            <input type="checkbox" onclick="showmarksbox('ExamsTaken_cat','ExamsTaken_cat_span');" id="ExamsTaken_cat" name="ExamsTaken[]" value="cat" <?php echo isset($userarray['CAT'])? 'checked':''?> >CAT:
            <span style="display:none;" id="ExamsTaken_cat_span" ><input default="Percentile" onClick="if(this.value =='Percentile'){this.value='';this.style.color = '';}" blurMethod="cat_exm_marks();" profanity="true" type="text" name="cat_exm_marks" id="cat_exm_marks" class="inputBorder" style="width:80px;color:#ada6ad;" value = "<?php echo (!empty($userarray['CAT']) ? $userarray['CAT'] : 'Percentile');?>"/></span>
            <input type="checkbox" onclick="showmarksbox('ExamsTaken_mat','ExamsTaken_mat_span');" id="ExamsTaken_mat" name="ExamsTaken[]" value="mat" <?php echo isset($userarray['MAT'])? 'checked':''?> >MAT:
            <span style="display:none;" id="ExamsTaken_mat_span" ><input default="Percentile" onClick="if(this.value =='Percentile'){this.value='';this.style.color = '';}" blurMethod="mat_exm_marks();" profanity="true" type="text" name="mat_exm_marks" id="mat_exm_marks" class="inputBorder" style="width:80px;color:#ada6ad;" value = "<?php echo (!empty($userarray['MAT'])?$userarray['MAT']:'Percentile');?>"/></span>
            <?php if( isset($userarray['CAT']) ) {
                    if( !empty($userarray['CAT'])) {
            ?>
            <script>
            document.getElementById('cat_exm_marks').style.color = "";
            </script>
            <?php } ?>
            <script>
            document.getElementById('ExamsTaken_cat_span').style.display = "";
            </script>
            <?php
            }
            ?>
            <?php if( isset($userarray['MAT']) ) {
                    if( !empty($userarray['MAT'])) {
            ?>
            <script>
            document.getElementById('mat_exm_marks').style.color = "";
            </script>
            <?php } ?>
            <script>
            document.getElementById('ExamsTaken_mat_span').style.display = "";
            </script>
            <?php
            }
            ?>
        
        <div>
            <div class="errorMsg" id="ExamsTaken_cat_error"></div>
        </div>
        <div>
            <div class="errorMsg" id="ExamsTaken_mat_error"></div>
        </div>

</div>
    </div>
</div>
<div id="work_experience_block">
    <div class="spacer10 clearFix"></div>
    <select caption="your years of experience" required="1" validate="validateSelect" name="ExperienceCombo" id="ExperienceCombo" tip="work_ex">
        <option value="" title="Select">Work Experience</option>
        <?php echo $work_exp_combo;?>
        </select>
        <div>
            <div class="errorMsg" id="ExperienceCombo_error"></div>
        </div>
</div>
<!-- UG Fields Close -->
<div>
<div class="spacer10 clearFix"></div>
	<input type="text" caption="name" required="1" validate="validateDisplayName" minlength="1" maxlength="100" tip="displayname_id" size="30" id="firstname"  name="firstname" class="form-txt-field" value = "Your Name" onfocus="checkTextElementOnTransition(this,'focus')" default="Your Name"/>
    <div>
    	<div class="errorMsg" id="firstname_error"></div>
    </div>
</div>

<div>
<div class="spacer10 clearFix"></div>
    <input type="text" validate="validateEmail" caption="email address" <?php echo $userarray['emailenable']?> required="1" maxlength="125" class="form-txt-field" tip="email_idM" id="email" name="email" value = "Email" onfocus="checkTextElementOnTransition(this,'focus')" default="Email"/>
	<div>
    	<div class="errorMsg" id="email_error"></div>
    </div>
</div>
    
<div>
<div class="spacer10 clearFix"></div>
    <input  type="text" caption="mobile number" tip="mobile_numM" required="1" blurMethod='removetip();' size="30" maxlength="10" minlength="10" id="mobile" name="mobile" class="form-txt-field" value = "Mobile No" onfocus="checkTextElementOnTransition(this,'focus')" default="Mobile"/>
	<div>
    	<div class="errorMsg" id="mobile_error"></div>
	</div>
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
    <div>
        <div class="errorMsg" id="citiesofquickreg_error" ></div>
    </div>
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
    document.getElementById("marketingPreferedCity").innerHTML= <?php echo "'Selected (".$num_cities.")'";?>;
    document.getElementById("mCityList").value = "<?php echo $str; ?>";
<?
    }
?>
</script>
