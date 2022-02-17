<?php
$an_array = json_decode($userCompleteDetails,true);
$return_array = array();
foreach ($an_array as $key => $val) break;
$return_array[] = $val;
$data = $return_array;
$userarray = json_decode($userDataToShow,true);
if(isset($userarray['name']) && !empty($userarray['name']))
$value = "update";
else
$value = "insert";
if (!empty($userName)) {
    $userarray['name'] = $userName;
    if ($value == 'insert') {
	$userarray['email'] = $userEmail;
    }
    $userarray['mobile'] = $userContactno;
}
?>
<input type = "hidden" name = "mpagename" id = "mpagename" value = "marketingPage"/>
<input type = "hidden" name = "planDate" id = "planDate" value = "<?php echo date('Y-m-d 00:00:00',time()+(86400*365)); ?>"/>
<div id="study_mode_block" style="display:none;">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Mode<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
        <div style="position:relative;*left:-4px">
        <input blurMethod="check_mode();" type="checkbox" id="mode_full" name="mode[]" value="full_time" <?php echo $userarray['fulltime']?>> Full Time
        <input blurMethod="check_mode();"  id="mode_part" type="checkbox" name="mode[]" value="part_time" <?php echo $userarray['parttime']?>> Part Time
        </div>
        <div>
            <div class="errorMsg" id="mode_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
<div class="lineSpace_10" id="study_mode_block_ls">&nbsp;</div>
</div>
<div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Preferred Study Location(s)<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
        <div style="float:left;width:100%">
            <div style="*margin-left:3px">
                <div class="float_L" style="width:150px;background:url(/public/images/bgDropDwn.gif) no-repeat left top;height:19px" onclick="trackEventByGA('PreferredStudyLocationClick','PreferredStudy cities overlay clicked');abc(this);showUGSection();">
                    <div id="marketingPreferedCity" style="position:relative;top:2px">&nbsp;Select</div>
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
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div id="degree_preference_block" style="display:none;">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Degree Preference<b class="redcolor">*</b>: </div>
    </div>
    <div style="margin-left:177px">
        <div>
            <input onclick="check_degree_preference1();"  type="checkbox" name="degree_preference[]" id="pref_deg_aicte" value="aicte_approved" <?php echo $userarray['AICTE']?> style="*margin-left:-3px"> AICTE Approved
            <input onclick="check_degree_preference1();"  type="checkbox" id="pref_deg_ugc" name="degree_preference[]" value="ugc_approved" <?php echo $userarray['UGC']?> > UGC Approved<br>
            <input onclick="check_degree_preference1();"  type="checkbox" id="pref_deg_inter" name="degree_preference[]" value="international" <?php echo $userarray['International']?>>International Degree
            <input onclick="check_degree_preference();" type="checkbox" id="pref_deg_any" name="degree_preference[]" value="any" <?php echo $userarray['Anydegree']?>>No Preference
        </div>
        <div>
            <div class="errorMsg" id="degree_preference_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
<div class="lineSpace_10" id="degree_preference_block_ls">&nbsp;</div>
</div>
<div id="planToStartDiv" style="display:none;">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
        <div>
            <select style="font-size:11px;width:190px" name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start"><option value="">Select</option><?php echo $when_you_plan_start; ?></select>
        </div>
        <div>
            <div class="errorMsg" id="when_plan_start_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
<div id="showUGSection" style="display:none;">
<!-- UG Fields start -->
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
<div id="gradDetailsDiv" style="display:none;">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
        <div>
            <select style="font-size:11px;width:150px" name = "ug_detials_courses" onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" validate = "validateSelect" required = "true" caption = "UG course" id="ug_detials_courses">
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
<div class="lineSpace_10">&nbsp;</div>
</div>
<div id="completion_date_block" style="display:none;">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Completion Date<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
    <div>
    <?php
        if (isset($CourseCompletionDate)) {
            list($y_c, $m_c, $d_c) = explode('-', $CourseCompletionDate);
        }
        $str ='<select tip="" validate = "validateSelect" required = "true" caption = "expected date of course completion" id="com_year_month" name="com_year_month" >';
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
    <select  tip="" blurMethod="validate_Completion_Date();" caption = "year" id="com_year_year" name="com_year_year">
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
<div id="completion_date_block_ls" class="lineSpace_10">&nbsp;</div>
</div>
<!--<div id="city_where_you_studied_block">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">City Where You Studied:</div>
    </div>
    <div style="margin-left:177px">
    <div>
    <select style = "width:200px" id = "citiesofeducation" name = "citiesofeducation" onChange= "loadinstitutes(this.value,1,'institute_name');" validate = "validateSelect" caption = "your city of education" unrestricted = "true">
    <option value="">Select</option>
    <?php
    /*
    $count = count($CitiesWithCollege);
    for ($i = 0 ; $i < $count; $i++) {
        if ((isset($ug_city_id)) && ($ug_city_id == $CitiesWithCollege[$i]['cityID'])) {
            $flag_city_studied = "selected";
        } else {
            $flag_city_studied = "";
        }
        if ($CitiesWithCollege[$i]['cityID'] !== '10247') {
            echo "<option ".$flag_city_studied." value='".$CitiesWithCollege[$i]['cityID']."'>".$CitiesWithCollege[$i]['cityName']."</option>";
        }
    }
        if ($ug_city_id == '10247') {
            $flag_city_studied = "selected";
        }
    echo "<option ".$flag_city_studied." value='10247'>Others</option>";
    */
    ?>
    </select>
    </div>
        <div>
            <div class="errorMsg" id="citiesofeducation_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10" id="city_where_you_studied_block_ls">&nbsp;</div>
<div id="college_name_block">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">College Name:</div>
    </div>
    <div style="margin-left:177px">
        <div>
        <select style = "width:250px" id="institute_name" name = "schoolCombo" validate = "validateSelect" caption = "College">
        <?php
	/*
        if ($ug_institute_id == '-1') {
            echo '<option selected value="-1">Other</option>';
        } elseif ((isset($ug_institute_name)) && (isset($ug_institute_id))) {
            echo '<option selected value = "'.$ug_institute_id.'">'.$ug_institute_name.'</option>';
        } else {
	*/
        ?>
        <option value = "">Select</option>
        <?php
        /*
	}
	*/
        ?>
        </select>
        <span id="ajax-loader-display" style="display:none;"><img src="/public/images/working.gif" border="0" /></span>
        </div>
        <div>
            <div class="errorMsg" id="institute_name_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10" id="college_name_block_ls">&nbsp;</div>-->
<div id="exams_taken_block" style="display:none;">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Exams Taken:</div>
    </div>
    <div style="margin-left:177px">
        <div style="position:relative;*left:-4px">
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
        </div>
        <div>
            <div class="errorMsg" id="ExamsTaken_cat_error" style="*padding-left:4px"></div>
        </div>
        <div>
            <div class="errorMsg" id="ExamsTaken_mat_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div class="lineSpace_10" id="exams_taken_block_ls">&nbsp;</div>
</div>
<div id="work_experience_block">
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Work Experience<b class="redcolor">*</b>:</div>
    </div>
    <div style="margin-left:177px">
        <div>
        <select caption="your years of experiece" required="1" validate="validateSelect" name="ExperienceCombo" id="ExperienceCombo" tip="work_ex" style="width: 150px;">
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
<!-- UG Fields Close -->
<div class="lineSpace_10" id="work_experience_block_ls">&nbsp;</div>
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
        required="1" validate="validateMobileInteger" size="30" maxlength="10" minlength="10" id="mobile" name="mobile" class="txt_1" value = "<?php echo $userarray['mobile']?>" blurMethod="removetip();" />
        </div>
        <div>
            <div class="errorMsg" id="mobile_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
    <div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:175px;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Residence Location<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left:177px">
    <div>
        <select  validate = 'validateSelect' required = 'true'  style = "width:150px" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg" name = "citiesofquickreg" blurMethod = "validate_combo();" >
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
<script>
function onLoadingThisCampaign()
{
	if(document.getElementById('marketingpagename').value=='campaign3' || document.getElementById('marketingpagename').value=='campaign4' || document.getElementById('marketingpagename').value=='campaign5' || document.getElementById('marketingpagename').value=='campaign6' || document.getElementById('marketingpagename').value=='campaign7' || document.getElementById('marketingpagename').value=='campaign8' || document.getElementById('marketingpagename').value=='campaign9' || document.getElementById('marketingpagename').value=='campaign10'){
	$('desiredCourseDiv').style.display='none';
	$('study_mode_block').style.display='none';
	$('degree_preference_block').style.display='none';
	$('planToStartDiv').style.display='none';
	$('exams_taken_block').style.display='none';
	$('work_experience_block').style.display='none';
	document.getElementById('ExperienceCombo').removeAttribute('required');
	$('showUGSection').style.display='block';
	$('homesubCategories').value='2';
	selectWhenPlanStart();
	$('pref_deg_any').checked=true;
	$('mode_full').checked=true;
	} else{
		$('desiredCourseDiv').style.display='block';
		$('study_mode_block').style.display='block';
		$('degree_preference_block').style.display='block';
		$('planToStartDiv').style.display='block';
		$('exams_taken_block').style.display='block';
		$('work_experience_block').style.display='block';
	}
}
onLoadingThisCampaign();

function selectWhenPlanStart()
{
	var selectedIndex = 0;
	var comboBox = document.getElementById('when_plan_start');
	var valueToSelect = $('planDate').value;
	for(var i=0; i < comboBox.options.length; i++) {
		comboBox.options[i].removeAttribute('selected');
		if(comboBox.options[i].value == valueToSelect) {
		   comboBox.options[i].setAttribute('selected',true);
		   comboBox.options[i].selected = true;
		   selectedIndex = i;
		}
	}
	comboBox.selectedIndex = selectedIndex;
}

function removetip(){
        if (document.getElementById('helpbubble1')) {
                    document.getElementById('helpbubble1').style.display='none';
                        }
            var other= document.getElementById('mobile').value;
            var objErr = document.getElementById('mobile_error');
                msg = validateMobileInteger(other,'mobile number',10,10,1);
                if(msg!==true)
                        {
                                objErr.innerHTML = msg;
                                    objErr.parentNode.style.display = 'inline';
                                    return false;
                                        }
                    else
                            {
                                    objErr.innerHTML = '';
                                        objErr.parentNode.style.display = 'none';
                                        return true;
                                            }
}

</script>
