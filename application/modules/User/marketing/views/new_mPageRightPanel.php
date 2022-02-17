<form method="post" autocomplete="off"  onsubmit="document.getElementById('subm').disabled = true;new Ajax.Request('<?php echo $formPostUrl; ?>',{onSuccess:function(request){javascript:newuserresponse(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" novalidate="novalidate" id = "frm1" name = "frm1">
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
if ($course == 'distancelearning') {
?>
<script>
FLAG_LOCAL_COURSE_FORM_SELECTION  = true;
</script>
<?php
}
?>
    <input type = "hidden" name = "flagfirsttime" id = "flagfirsttime" value = ""/>
    <input type = "hidden" name = "resolutionreg" id = "resolutionreg" value = ""/>
    <input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
    <input type = "hidden" name = "mCityList" id = "mCityList" value = ""/>
    <input type = "hidden" name = "mCityListName" id = "mCityListName" value = ""/>
    <input type = "hidden" name = "mCountryList" id = "mCountryList" value = ""/>
    <input type = "hidden" name = "mCountryListName" id = "mCountryListName" value = ""/>
    <input type = "hidden" name = "loginflagreg" id = "loginflagreg" value = ""/>
    <input type = "hidden" name = "loginactionreg" id = "loginactionreg" value = ""/>
    <input type = "hidden" name = "mupdateflag" id = "mupdateflag" value = "<?php echo $value;?>"/>
    <input type = "hidden" name = "marketingpagename" id = "marketingpagename" value = "<?php echo $pagename; ?>"/>
    <div style="width:100%">
        <div style="padding-left:20px">
            <div class="OrgangeFont bld" style="font-size:24px;padding-top:2px">Let Us Find a B-School for You</div>
            <div style="padding-top:2px">We need a few details from you to suggest you relevant institutes &amp; create your  free Shiksha account.</div>
        </div>
        <div class="lineSpace_10">&nbsp;</div>
        <script>
	    var displayNameForm,displayEmailForm,displayContactnoForm ;
            function selectComboBoxValue(comboboxId,valuetoselect,atttocompare) {
                if (document.getElementById(comboboxId)) {
		    var output=document.getElementById(comboboxId).options;
		    for(var i=0;i<output.length;i++) {
			if(output[i].value == valuetoselect){
			    output[i].selected=true;
			}
		    }
		}
            }
            function showUGSection()
            {
                document.getElementById('showUGSection').style.display = "";
            }
            function ajax_form_loader(url) {
                var mysack = new sack();
                mysack.requestFile = url;
                mysack.method = 'POST';
                mysack.onLoading = function() { showloader(); };
                mysack.onCompletion = function() {
                    document.getElementById('marketing_form_html').innerHTML = "";
                    var response = mysack.response;
                    document.getElementById('marketing_form_html').innerHTML = response;
                    closeMessage();
                    // if user select any local course
                    if (FLAG_LOCAL_COURSE_FORM_SELECTION) {
                        if(isUserLoggedIn)
                        {
                            selectComboBoxValue('ug_detials_courses','<?php echo $userarray['UGdetails']?>','value');
                            selectComboBoxValue('ug_detials_courses_marks','<?php echo $userarray['UGmarks']?>','value');

                        }
                        showUGSection();
                    } else {
			onLoadingThisCampaign();
                        if(isUserLoggedIn)
                        {
                            selectComboBoxValue('ug_detials_courses','<?php echo $userarray['UGdetails']?>','value');
                            selectComboBoxValue('ug_detials_courses_marks','<?php echo $userarray['UGmarks']?>','value');
                            selectComboBoxValue('citiesofeducation','<?php echo $userarray['UGcity']?>','value');
                            selectComboBoxValue('com_year_month','<?php echo $userarray['UGcompletionmonth']?>','value');
                            selectComboBoxValue('com_year_year','<?php echo $userarray['UGcompletionyear']?>','value');
                            selectComboBoxValue('institute_name','<?php echo $userarray['UGinstitute']?>','value');
                            loadinstitutes('<?php echo $userarray['UGcity']?>',1,'institute_name','<?php echo $userarray['UGinstitute']?>');
                        }
                        selectComboBoxValue('ExperienceCombo','<?php echo $userarray['experience']?>','value');
                    }
		    try {
		    document.getElementById('firstname').value = displayNameForm;
		    document.getElementById('email').value = displayEmailForm;
		    document.getElementById('mobile').value = displayContactnoForm;
		    }catch(e) {}
                    addOnBlurValidate(document.getElementById('frm1'));
                    addOnFocusToopTip1(document.getElementById('frm1'));
                };
                mysack.runAJAX();
		<?php
		if ($userarray['UGongoing'] == 'checked') {
		?>
		    document.getElementById('ug_detials_courses_marks').style.display = "none";
		    document.getElementById('ug_detials_courses_marks_error').style.display = "none";
		<?php
		}
		?>
            }
            function actionDistanceMBA(id) {
                // NEED TO UPDATE IT
		try{
		    displayNameForm = document.getElementById('firstname').value;
		    displayEmailForm = document.getElementById('email').value;
		    displayContactnoForm = document.getElementById('mobile').value;
		}catch(e) {}
                if ((id != '2')&&(id != '13')) {
                    if(!FLAG_LOCAL_COURSE_FORM_SELECTION) {
                        // load distance mba form from AJAX ajax should be cachable
                        ajax_form_loader('/marketing/Marketing/ajaxform_mba/false');
                    }
                    FLAG_LOCAL_COURSE_FORM_SELECTION  = true;
                } else {
                    if (FLAG_LOCAL_COURSE_FORM_SELECTION) {
                        FLAG_LOCAL_COURSE_FORM_SELECTION  = false;
                        // load normal form from AJAX ajax should be cachable
                        ajax_form_loader('/marketing/Marketing/ajaxform_mba/true');
                    }
                }
            }
        </script>
        <div>
            <div id="desiredCourseDiv" style="display:none;">
                <div class="float_L" style="width:175px;line-height:18px">
                    <div class="txt_align_r" style="padding-right:5px">Desired Course<b class="redcolor">*</b>:</div>
                </div>
                <div id="subCategory" style="margin-left:177px">
                    <div>
                    <select <?php if ($course != 'distancelearning') { ?> onChange= "actionDistanceMBA(this.value);" <?php } ?> style="font-size:11px;width:240px" name = 'homesubCategories' validate = 'validateSelect' required = 'true' caption = 'the desired course' id='homesubCategories' style='width:170px;font-size:11px'>
                    <option value=''>Select</option>
                    <?php
                    if ($org_type == 'distancelearning') {
                    foreach ($distance_course as $value) {
                                echo "<option value='".$value['SpecializationId']."'>".$value['SpecializationName']."</option>";
                        }
                    } else {
                    foreach ($newDesiredCourse_list as $value1){
                        echo "<option value='".$value1['SpecializationId']."'>".$value1['CourseName']."</option>";
                    }
                    }
                    ?>

                    </select>
                    </div>
                    <div>
                        <div class="errorMsg" id="homesubCategories_error" style="*padding-left:4px"></div>
                    </div>
                </div>
                <div class="clear_L withClear">&nbsp;</div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            <div id="marketing_form_html">
            <?php
            if ($course == 'distancelearning') {
                $this->load->view('marketing/mba_marketing_local_course_mode');
            } else {
                $this->load->view('marketing/mba_marketing_mode');
            }
            ?>
            </div>
            <!-- captcha section -->
            <?php if($logged=="No") { ?>
            <div style="padding:0 10px 0 20px">
                <div>Type in the character you see in the picture below</div>
                <div class="lineSpace_5">&nbsp;</div>
                <div>
                <img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodehome" width="100" height="34"  id = "secureCode"/>
                <input type="text" blurMethod='trackEventByGA("EnterCaptcha","captcha entered by user");' style="margin-left:56px;width:135px;height:15px;font-size:12px" name = "homesecurityCode" id = "homesecurityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
                    <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
                        <div  style="margin-left:160px;*padding-left:4px" class="errorMsg" id= "homesecurityCode_error">
                        </div>
                    </div>
                </div>
                <div class="lineSpace_5">&nbsp;</div>
                <div>
                <input type="checkbox" name="cAgree" id="cAgree" />
                I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
                </div>
            </div>
                <?php }?>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
                <div class="errorMsg" id= "cAgree_error" style="padding-left:24px"></div>
            </div>
            <!-- submit button section -->
            <div>
                <div class="float_L" style="width:140px;line-height:31px">
                    <div class="txt_align_r" style="padding-right:5px">&nbsp;</div>
                </div>
                <div style="margin-left:142px">
                    <div><input uniqueattr="MarkeingPageLayer1Submit" type="submit" id="subm" onclick="return sendReqInfo(this.form);" value="Submit" class="continueBtn" <?php if($logged!="No") echo 'disabled = "true"'; ?>/></div>
                </div>
                <div class="clear_L withClear">&nbsp;</div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            <div style="clear:left;font-size:1px;background:#F4F4F4">&nbsp;</div>
        </div>
    </div>
</form>
<script id="action_after_loading_ajax_html_form">
if(isUserLoggedIn)
{
    if (FLAG_LOCAL_COURSE_FORM_SELECTION) {
	selectComboBoxValue('ug_detials_courses','<?php echo $userarray['UGdetails']?>','value');
	selectComboBoxValue('ug_detials_courses_marks','<?php echo $userarray['UGmarks']?>','value');
    } else {
    selectComboBoxValue('ug_detials_courses','<?php echo $userarray['UGdetails']?>','value');
    selectComboBoxValue('ug_detials_courses_marks','<?php echo $userarray['UGmarks']?>','value');

    selectComboBoxValue('citiesofeducation','<?php echo $userarray['UGcity']?>','value');
	selectComboBoxValue('com_year_month','<?php echo $userarray['UGcompletionmonth']?>','value');
	selectComboBoxValue('com_year_year','<?php echo $userarray['UGcompletionyear']?>','value');
	selectComboBoxValue('institute_name','<?php echo $userarray['UGinstitute']?>','value');
	loadinstitutes('<?php echo $userarray['UGcity']?>',1,'institute_name','<?php echo $userarray['UGinstitute']?>');
    }
}
if (!FLAG_LOCAL_COURSE_FORM_SELECTION) {
    selectComboBoxValue('ExperienceCombo','<?php echo $userarray['experience']?>','value');
}
if (FLAG_LOCAL_COURSE_FORM_SELECTION) {
    showUGSection();
}
if (!FLAG_LOCAL_COURSE_FORM_SELECTION) {
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
    showUGSection();
    document.getElementById("marketingPreferedCity").innerHTML= <?php echo "'Selected (".$num_cities.")'";?>;
    document.getElementById("mCityList").value = "<?php echo $str; ?>";
<?
    }
?>
}
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>">
</script>
<script>
var TRACKING_CUSTOM_VAR_MARKETING_FORM = "marketingpage";
if(typeof(setCustomizedVariableForTheWidget) == "function") {
	if (window.addEventListener){
		window.addEventListener('click', setCustomizedVariableForTheWidget, false);
	} else if (window.attachEvent){
		document.attachEvent('onclick', setCustomizedVariableForTheWidget);
	}
}
</script>
