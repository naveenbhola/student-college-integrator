<form method="post" autocomplete="off"  onSubmit="$('subm').disabled = true;
if(!sendITReqInfo(this)) {$('subm').disabled = false; return false; } new
Ajax.Request('<?php echo $formPostUrl;
?>',{onSuccess:function(request){javascript:newuserresponse(request.responseText
);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" novalidate="novalidate"
id = "frm1" name = "frm1">
<?php
$data = json_decode($userCompleteDetails,true);
$userarray = json_decode($userDataToShow,true);
if(isset($userarray['name']))
$value = "update";
else
$value = "insert";
?>
    <!-- flagfirsttime hidden field updated after form submitting -->
    <input type = "hidden" name = "flagfirsttime" id = "flagfirsttime" value =
""/>
    <input type = "hidden" name = "resolutionreg" id = "resolutionreg" value =
""/>
    <input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
    <input type = "hidden" name = "mCityList" id = "mCityList" value = ""/>
    <input type = "hidden" name = "mCityListName" id = "mCityListName" value =
""/>
    <input type = "hidden" name = "mCountryList" id = "mCountryList" value =
""/>
    <input type = "hidden" name = "mCountryListName" id = "mCountryListName"
value = ""/>
    <input type = "hidden" name = "loginflagreg" id = "loginflagreg" value =
""/>
    <input type = "hidden" name = "loginactionreg" id = "loginactionreg" value =
""/>
    <!-- required field .. identify between update or insert -->
    <input type = "hidden" name = "mupdateflag" id = "mupdateflag" value =
"<?php echo $value;?>"/>
    <!-- required filed .. identify pagename -->
    <input type = "hidden" name = "marketingpagename" id = "marketingpagename"
value = "<?php echo $pagename; ?>"/>
    <div style="width:100%">
        <div style="padding-left:20px">
            <?php echo $config_data_array['TEXT_REGISTRATION_WIDGET'];?>
        </div>
        <div class="lineSpace_10">&nbsp;</div>
        <script>
             function checkboxselection() {
		    try{
		    <?php
		    if ($userarray['UGongoing'] == 'checked') {
		    ?>

document.getElementById('ug_detials_courses_marks').style.display = "none";

document.getElementById('ug_detials_courses_marks_error').style.display =
"none";
		    <?php
		    }
		    ?>
		    } catch(e) {
			// alert(e);
		    }
             }
             function changeSelectionPreferredStudyLocation() {
		<?php
		if((isset($userarray['name'])) && (!empty($userarray['name'])))
{
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
		    try{

document.getElementById("marketingPreferedCity").innerHTML= <?php echo
"'Selected (".$num_cities.")'";?>;
			document.getElementById("mCityList").value = "<?php echo
$str; ?>";
		    }catch(e) {
			//alert(e);
		    }
		<?
		}
	        ?>
	    }

	    function fillCombo() {
		if(isLogged) {
		    selectComboBoxValue('ug_detials_courses','<?php echo
$userarray['UGdetails']?>','value');
		    selectComboBoxValue('ug_detials_courses_marks','<?php echo
$userarray['UGmarks']?>','value');
		    selectComboBoxValue('citiesofeducation','<?php echo
$userarray['UGcity']?>','value');
		    selectComboBoxValue('com_year_month','<?php echo
$userarray['UGcompletionmonth']?>','value');
		    selectComboBoxValue('com_year_year','<?php echo
$userarray['UGcompletionyear']?>','value');
		    selectComboBoxValue('ExperienceCombo','<?php echo
$userarray['experience']?>','value');
		}
	    }

	     /* API that enter default value in drop downs logged-in case */
	    function selectComboBoxValue(comboboxId,valuetoselect,atttocompare)
{
		if (document.getElementById(comboboxId)) {
		    var output=document.getElementById(comboboxId).options;
		    for(var i=0;i<output.length;i++) {
			if(output[i].value == valuetoselect){
			    output[i].selected=true;
			}
		    }
		}
	    }
	    /* API to load html form from ajax */
            function ajax_form_loader(url) {
                var mysack = new sack();
                mysack.requestFile = url;
                mysack.method = 'POST';
                mysack.onLoading = function() { showloader(); };
                mysack.onCompletion = function() {
                    document.getElementById('marketing_form_html_it').innerHTML
= "";
                    var response = mysack.response;
		    document.getElementById('marketing_form_html_it').innerHTML
= response;
                    closeMessage();
                    addOnBlurValidate(document.getElementById('frm1'));
                    addOnFocusToopTip1(document.getElementById('frm1'));
                };
                mysack.runAJAX();
            }
	    /* ajax_form_loader() */
            function actionDesiredCourseDD(id) {
		var courseOptions =
document.getElementById('homesubCategories').options;
		var selectindex = courseOptions.selectedIndex;
		if ( (courseOptions[selectindex].getAttribute("ddtype") ==
'local')||(courseOptions[selectindex].getAttribute("ddtype") == '')) {
                    if(FLAG_LOCAL_COURSE_FORM_SELECTION != 0) {
                        // load it course form

ajax_form_loader('/marketing/Marketing/ajaxform_mba/itcourse');
			// reset counter
			privateCounter = 1;
                    }
			FLAG_LOCAL_COURSE_FORM_SELECTION  = 0;
                } else if(courseOptions[selectindex].getAttribute("ddtype") ==
'national') {
                    if (FLAG_LOCAL_COURSE_FORM_SELECTION != 1) {
                        FLAG_LOCAL_COURSE_FORM_SELECTION  = 1;
                        // load normal form from AJAX ajax should be cachable

ajax_form_loader('/marketing/Marketing/ajaxform_mba/itdegree');
			changeSelectionPreferredStudyLocation();
                    }
                } else if(courseOptions[selectindex].getAttribute("ddtype") ==
'graduate_course') {
                    if (FLAG_LOCAL_COURSE_FORM_SELECTION != 2) {
                        FLAG_LOCAL_COURSE_FORM_SELECTION  = 2;

ajax_form_loader('/marketing/Marketing/ajaxform_mba/graduate_course');
			changeSelectionPreferredStudyLocation();
                    }
                }
		fillCombo();
		checkboxselection();
           }
        </script>
	<?php
	//echo "<pre>";print_r($itcourseslist);echo "</pre>";
	function cmp($a, $b)
	{
	    $a = $a['CourseName'];
	    $b = $b['CourseName'];
	    if(substr($a,0,1) == "."){
		return 1;
	    }
	    if(substr($b,0,1) == "."){
		return -1;
	    }
	    return (strcmp($a,$b) < 0) ? -1 : 1;
	}
	foreach ($itcourseslist as $groupId => $value) {
	    $string2 = $groupName = '';
	    usort($value, "cmp");
	    foreach ($value as $finalArray) {
            if ($finalArray['SpecializationName'] == "All")
		    if ( $finalArray['CourseLevel'] == 'Degree') {
			if ($finalArray['CourseLevel1'] == 'UG') {
			    // change Distance BCA's course reach as local
			    if ($finalArray['CourseName'] == 'Distance BCA') {
				$string2 .='<option ddtype="local"
				title="'.$finalArray['CourseName'].'"  '.$selected_string.'
				value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
				option>';
			    } else {
				$CourseReach = $finalArray['CourseReach'];
				if ($CourseReach == 'national') {
				    $CourseReach = 'graduate_course';
				}
				$string2 .='<option ddtype="'. $CourseReach .'"
				title="'.$finalArray['CourseName'].'"  '.$selected_string.'
				value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
				option>';
			    }
			} else {
			    // change Distance MCA 's course reach as local
			    if ($finalArray['CourseName'] == 'Distance MCA') {
				$string2 .='<option ddtype="local"
				title="'.$finalArray['CourseName'].'"  '.$selected_string.'
				value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
				option>';
			    } else {
				$string2 .='<option ddtype="'.
				$finalArray['CourseReach'] .'" title="'.$finalArray['CourseName'].'"
				'.$selected_string.'
				value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
				option>';
			    }
			}
		    } else {
			    $string2 .='<option ddtype="'.
			    $finalArray['CourseReach'] .'" title="'.$finalArray['CourseName'].'"
			    '.$selected_string.'
			    value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
			    option>';
		    }
		    $groupName =  $finalArray['groupName'];
		    $CourseLevel1 = $finalArray['CourseLevel1'] ;
		    $level = $finalArray['CourseLevel'] ;
		    //echo $level . '====' .$groupName . '====' . $CourseLevel1 . '==== <br />';
	    }
	    if($groupName != '') {
		if ($level == 'Degree') {
		    if($CourseLevel1 == 'PG') {
			$string .= '<optgroup label="'. $groupName .'">'. $string2
			    .'</optgroup>';
		    } else if($CourseLevel1 == 'UG') {
			$string3 .= '<optgroup label="'. $groupName .'">'. $string2
			    .'</optgroup>';
		    }
		}
		if($level == 'Certification') {
		    $string4 .= '<optgroup label="'. $groupName .'">'. $string2
			.'</optgroup>';
		}
	    } else {
		$string .= $string2;
	    }
	}
	$string .= $string3.$string4;

	if ( $pagename == 'testprep')
	{
		$string = '';
		foreach ($itcourseslist as $key=>$value)
		{
			foreach($value as $index=>$main)
			{
				$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'">'.$main['child']['acronym'].'</
				option>';
			}
				$string .=  "<optgroup label='". $main['title'] ."'>". $string1."</optgroup>";
				$string1 = "";
		}
	}
	if ( $pagename == 'testprep')
	{
		$dd_name = "testPrep_blogid";
	}
	else
	{
		$dd_name = "homesubCategories";
	}
	?>
        <div>
            <div>
                <div class="float_L" style="width:175px;line-height:18px">
                    <div class="txt_align_r" style="padding-right:5px">Desired
Course<b class="redcolor">*</b>:</div>
                </div>
                <div id="subCategory" style="margin-left:177px">
                    <div>
                    <select onChange= "actionDesiredCourseDD(this.value);"
style="font-size:11px;width:240px" name = '<?php echo $dd_name; ?>' validate =
'validateSelect' required = 'true' caption = 'the desired course'
id='homesubCategories' style='width:170px;font-size:11px'>
                    <option value=''>Select</option>
                    <?php
			echo $string;
                    ?>
                    </select>
                    </div>
                    <div>
                        <div class="errorMsg" id="homesubCategories_error"
style="*padding-left:4px"></div>
                    </div>
                </div>
                <div class="clear_L withClear">&nbsp;</div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            <div id="marketing_form_html_it">
	    <?php
		/* we don't have any db based identifier for PG , UG and local
cources :( */
		if
(!empty($data[0]['PrefData'][0]['SpecializationPref'][0]['CourseReach']) &&
($logged != "No")) {
		    if
($data[0]['PrefData'][0]['SpecializationPref'][0]['CourseReach'] == 'local') {

$this->load->view('marketing/user_form_mba_it_itcourses');
		    } else {

$this->load->view('marketing/user_form_mba_it_itdegree');
		    ?>
			<script>
			    FLAG_LOCAL_COURSE_FORM_SELECTION = 1;
			</script>
		    <?php
		    }
		} else {
		    if ($pagename == 'science'|| $pagename == 'fashion_design') {

$this->load->view('marketing/user_form_mba_it_itdegree');
		    ?>
			<script>
			    FLAG_LOCAL_COURSE_FORM_SELECTION = 1;
			</script>
		    <?php
		    } else if ($pagename == 'bba') {
$this->load->view('marketing/user_form_mba_it_ug_courses');
		    ?>
			<script>
			    FLAG_LOCAL_COURSE_FORM_SELECTION = 2;
			</script>

		    <?php
		    } else  {

$this->load->view('marketing/user_form_mba_it_itcourses');
		    }
		}
	    ?>
	    </div>
	    </div>
            <!-- captcha section -->
            <?php if($logged=="No") { ?>
            <div style="padding:0 10px 0 20px">
                <div>Type in the character you see in the picture below</div>
                <div class="lineSpace_5">&nbsp;</div>
                <div>
                <img align = "absbottom"
src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&
randomkey=<?php echo rand(); ?>&secvariable=seccodehome" width="100" height="34"
 id = "secureCode"/>
                <input type="text"
blurMethod='trackEventByGA("EnterCaptcha","captcha entered by user");'
style="margin-left:56px;width:135px;height:15px;font-size:12px" name =
"homesecurityCode" id = "homesecurityCode" validate = "validateSecurityCode"
maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
                    <div class="errorPlace"
style="display:none;">
                        <div  style="margin-left:160px;*padding-left:4px"
class="errorMsg" id= "homesecurityCode_error">
                        </div>
                    </div>
                </div>
                <div class="lineSpace_5">&nbsp;</div>
                <div>
                <input type="checkbox" name="cAgree" id="cAgree" />
                I agree to the <a href="javascript:" onclick="return
popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a
href="javascript:" onclick="return
popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
                </div>
            </div>
                <?php } ?>
            <div class="errorPlace"
style="display:none;">
                <div class="errorMsg" id= "cAgree_error"
style="padding-left:24px"></div>
            </div>
	    <div class="lineSpace_10">&nbsp;</div>
            <!-- submit button section -->
            <div>
                <div class="float_L" style="width:140px;line-height:31px">
                    <div class="txt_align_r"
style="padding-right:5px">&nbsp;</div>
                </div>
                <div style="margin-left:142px">
                    <div><input uniqueattr="MarkeingPageLayer1Submit" type="submit" id="subm" value="Submit" class="continueBtn" <?php if($logged!="No") echo 'disabled = "true"'; ?>/></div>
                </div>
                <div class="clear_L withClear">&nbsp;</div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            <div
style="clear:left;font-size:1px;background:#F4F4F4">&nbsp;</div>
        </div>
    </div>
</form>
<script id="action_after_loading_ajax_html_form">
/* Need to add possible DDs */
fillCombo();
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
