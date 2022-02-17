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
<!-- hidden field to identify it is a it degree page -->
<input type = "hidden" name = "mpagename" id = "mpagename" value = "it_degree"/>

<div id="study_mode_block">
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
		<div>
			<div style="float:left;" class="errorMsg" id="mode_error" style="*padding-left:4px"></div>
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
        <div style="width:100%;">
            <div style="*margin-left:3px;width:100%;float:left;">
				<div class="float_L preferred_city_overlay_btn" onclick="trackEventByGA('PreferredStudyLocationClick','PreferredStudy cities overlay clicked');showPreferedCityOverlay(this);">
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
<div class="lineSpace_10" id="study_mode_block_ls">&nbsp;</div>
<div id="degree_preference_block" >
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Degree Preference<b class="redcolor">*</b>: </div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div style="float:left;">
			<div style="display:block;">
				<div style="float:left;">
					<input style="margin-left:0px;height:auto;" onclick="check_degree_preference1();"  blurMethod="check_degree_pref();" type="checkbox" name="degree_preference[]" id="pref_deg_aicte" value="aicte_approved" <?php //echo $userarray['AICTE']?> style="*margin-left:-3px">	
				</div>
				<div style="float:left;margin-top:3px;">
					AICTE Approved
				</div>
				<div style="float:left;">
					<input blurMethod="check_degree_pref();" style="height:auto;" onclick="check_degree_preference1();"  type="checkbox" id="pref_deg_ugc" name="degree_preference[]" value="ugc_approved" <?php //echo $userarray['UGC']?> >
				</div>
				<div style="float:left;margin-top:3px;">
					UGC Approved
				</div>
			</div>
			<div style="clear:both;"></div>
			<div style="display:block;">
				<div style="float:left;">
					<input blurMethod="check_degree_pref();" style="margin-left:0px;height:auto;" onclick="check_degree_preference1();"  type="checkbox" id="pref_deg_inter" name="degree_preference[]" value="international" <?php //echo $userarray['International']?>>
				</div>
				<div style="float:left;margin-top:3px;">
					International Degree
				</div>
				<div style="float:left;">
					<input blurMethod="check_degree_pref();" style="height:auto;" onclick="check_degree_preference();" type="checkbox" id="pref_deg_any" name="degree_preference[]" value="any" <?php //echo $userarray['Anydegree']?>>
				</div>
				<div style="float:left;margin-top:3px;">
					No Preference
				</div>
			</div>
        </div>
		<div style="clear:both;"></div>
        <div style="float:left;">
            <div class="errorMsg" id="degree_preference_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">When do you plan to start ?<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
            <select style="font-size:11px;width:90%" name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start"><option value="">Select</option><?php echo $when_you_plan_start; ?></select>
        </div>
        <div>
            <div class="errorMsg" id="when_plan_start_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>

<div id="showUGSection">
<!-- UG Fields start -->
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Status<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div style="display:block;">
			<div style="float:left;">
				<input style="margin-left:0px;height:auto;" onclick="ug_status(); customRemoveTipsForUGDetails();" blurMethod="ug_status(); customRemoveTipsForUGDetails();" name="Completed" id="ug_Completed" type="radio" value="completed" caption="Completed" <?php //echo $userarray['UGcompleted']?> />
			</div>
			<div style="float:left;margin-top:3px;">
				Completed &nbsp;
			</div>
			<div style="float:left;">
				<input style="margin-left:0px;height:auto;" onclick="ug_status();" blurMethod="ug_status();" name="Completed" id="ug_Pursuing" type="radio" value="Pursuing" caption="Pursuing" <?php //echo $userarray['UGongoing']?>/>
			</div>
			<div style="float:left;margin-top:3px;">
				Pursuing &nbsp;
			</div>
		</div>
		<div style="clear:both;"></div>
        <div style="float:left;">
            <div class="errorMsg" id="ug_status_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Graduation Details<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
            <select style="font-size:11px;width:50%;" name = "ug_detials_courses" onchange="trackEventByGA('CourseDropDownClick','course drop down clicked');" validate = "validateSelect" required = "true" caption = "graduation course" id="ug_detials_courses">
            <option value="">Select</option>
				<?php echo $course_lists; ?>
            </select>
			<!--validate = "validateSelect"-->
			<select blurMethod="ug_detials_courses_marks();" style="width:45%;font-size:11px;" name = "ug_detials_courses_marks" required = "true" caption = "marks" id="ug_detials_courses_marks">
				<option value="">Marks</option>
            <?php
                for ($i = 100; $i >= 33; $i--) {
                    if (isset($ug_marks)) {
                        if ($ug_marks == $i) {
                            $flag_marks = "selected";
                        } else {
                            $flag_marks = "";
                        }
                    }
					
					$flag_marks = "";
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
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div id="completion_date_block">
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Completion Date<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
    <div>
    <?php
        if (isset($CourseCompletionDate)) {
            list($y_c, $m_c, $d_c) = explode('-', $CourseCompletionDate);
        }
        $str ='<select style="font-size:11px;width:50%;" validate = "validateSelect" required = "true" caption = "expected date of course completion" id="com_year_month" name="com_year_month" >';
        $str.='<option value="">Month</option>';
        $this_month = $m_c;
        $months=range(1,12);
        foreach($months as $month){
        $month = sprintf("%02d",$month);
        $str.=' <option value="'.$month.'"';
        //if($month==$this_month) $str.=' selected="selected"';
		if($month==$this_month) $str.='';
        $str.='>'.date('M',mktime(0,0,0,$month,1,2006)).'</option>'."\n";
        }
        $str.='</select>'."\n";
        echo $str;
    ?>
    </select>
    <select blurMethod="validate_Completion_Date();" caption = "year" id="com_year_year" name="com_year_year">
    <option value="">Year</option>
    <?php
        for($i= date("Y")+5; $i>= 1950; $i--) {
            if ($y_c == $i) {
                //echo "<option selected value='$i'>".$i."</option>";
				echo "<option value='$i'>".$i."</option>";
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
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10" id="exams_taken_block_ls">&nbsp;</div>
<div id="exams_taken_block" style="<?php if(!empty($userarray['MAT']) || !empty($userarray['CAT'])): echo 'display:block'; else: echo 'display:none'; endif;?>">
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Exams Taken:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div style="position:relative;*left:-4px">
            <input style="margin-left:0px;" type="checkbox" onclick="showmarksbox('ExamsTaken_cat','ExamsTaken_cat_span');" id="ExamsTaken_cat" name="ExamsTaken[]" value="cat">CAT:
            <span style="display:none;" id="ExamsTaken_cat_span" ><input default="Percentile" onFocus="if(this.value =='Percentile'){this.value='';this.style.color = '#464645';}" onClick="if(this.value =='Percentile'){this.value='';this.style.color = '#464645';}" blurMethod="cat_exm_marks();" profanity="true" type="text" name="cat_exm_marks" id="cat_exm_marks" class="inputBorder" style="width:80px;color:#ada6ad;" value = "<?php echo (!empty($userarray['CAT']) ? $userarray['CAT'] : 'Percentile');?>"/></span>
            <input type="checkbox" onclick="showmarksbox('ExamsTaken_mat','ExamsTaken_mat_span');" id="ExamsTaken_mat" name="ExamsTaken[]" value="mat" >MAT:
            <span style="display:none;margin-top:5px;" id="ExamsTaken_mat_span"><input default="Percentile" onFocus="if(this.value =='Percentile'){this.value='';this.style.color = '#464645';}" onClick="if(this.value =='Percentile'){this.value='';this.style.color = '#464645';}" blurMethod="mat_exm_marks();" profanity="true" type="text" name="mat_exm_marks" id="mat_exm_marks" class="inputBorder" style="width:80px;color:#ada6ad;" value = "<?php echo (!empty($userarray['MAT'])?$userarray['MAT']:'Percentile');?>"/></span>
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
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div id="work_experience_block">
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Work Experience<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
        <select caption="your years of experience" required="1" validate="validateSelect" name="ExperienceCombo" id="ExperienceCombo" style="width:90%;">
        <option value="" title="Select">Select</option>
        <?php echo $work_exp_combo;?>
        </select>
        </div>
        <div>
            <div class="errorMsg" id="ExperienceCombo_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
</div>
<!-- UG Fields Close -->
<div class="lineSpace_10" id="work_experience_block_ls">&nbsp;</div>
<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">First Name<b class="redcolor">*</b>:</div>
    </div>
    <div style="width:63%;float:right;text-align:left;">
        <div>
        <input type="text" caption="first name" default="Your First Name" required="1" validate="validateDisplayName" minlength="1" maxlength="50" size="30" id="firstname" style="width:65%;" name="firstname" class="txt_1" value = "<?php //if($logged=="No" && isset($userName)) { echo $userName; } else { echo $userarray['name']; } ?>"/>
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
        <input type="text" caption="last name" default="Your Last Name" required="1" validate="validateDisplayName" minlength="1" maxlength="50" size="30" id="lastname" style="width:65%;" name="lastname" class="txt_1" value = "<?php //if($logged=="No" && isset($userName)) { echo $userName; } else { echo $userarray['name']; } ?>"/>
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
            <input type="text" validate="validateEmail" caption="email address" <?php //echo $userarray['emailenable']?> required="1" maxlength="125" style="width:65%;"
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
		
        <input style="width:65%;" type="text" caption="mobile number"
        blurMethod='removetip();' required="1" size="30" maxlength="10" minlength="10" id="mobile" name="mobile" class="txt_1" value = "<?php // if($logged=="No" && isset($userContactno)) { echo $userContactno; } else { echo $userarray['mobile']; } ?>" />
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
    <div style="width:63%;float:right;text-align:left;">
    <div>
        <select  caption = "city of residence" validate = 'validateSelect' required = 'true'  style = "width:90%;" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg" name = "citiesofquickreg" blurMethod = "validate_combo();" >
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
                            <option <?php //echo $optionSelectedStr; ?> value="<?php echo $list3['CityId']; ?>"><?php echo $list3['CityName'];?></option>
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
