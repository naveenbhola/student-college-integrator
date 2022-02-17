<script>
function updateRegTracker(id)   
{
    $('regTracker').src = '/registration/Tracker/newTracker/'+id+'/<?php echo $pageId; ?>?d='+Math.floor((Math.random()*10000)+1);
}
</script>
<img src="/registration/Tracker/newTracker/oldFormLoad1/<?php echo $pageId; ?>?d=<?php echo rand(1,100000); ?>" id="regTracker" />

<form method="post" novalidate="novalidate" autocomplete="off"  onSubmit=" $('subm').disabled = true;
if(!sendITReqInfo(this)) { $('subm').disabled = false; return false; } new
Ajax.Request('<?php echo $formPostUrl;
?>',{onSuccess:function(request){javascript:newuserresponse(request.responseText
);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" id =
"frm1" name = "frm1">
<?php
global $testPrepCourses_requires_12th_marks;
$local = FALSE;
$data = json_decode($userCompleteDetails,true);
$userarray = json_decode($userDataToShow,true);
// logic to find first course in desired course dropdown
if(!empty($managementcourses[0])) {
	$first_course = $managementcourses[0];
	if($first_course['SpecializationId']!=20)
	$local = TRUE;
} else if(!empty($itcourseslist)) {
	foreach($itcourseslist as $it_course) {
	   $first_course = $it_course[0];
	   break;
	}
}
if(!empty($userarray['name'])){
$value = "update";
}
else {
$value = "insert";
}
?>
	<input type = "hidden" name = "mmpPageId" id = "mmpPageId" value = "<?php echo $pageId; ?>" />
	<input type = "hidden" name = "pageReferer" id = "pageReferer" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />

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
value = "<?php echo $pagetype; ?>"/>
<input type="hidden" name="redirectionURL" value="<?php echo base64_encode($config_data_array['destination_url']);?>"/>
<input type="hidden" name="categoryId" id="categoryId" value=""/>
<input type="hidden" name="subCategoryId" id="subCategoryId" value=""/>
<input type="hidden" name="desiredCourse" id="desiredCourse" value=""/>
    <div style="width:100%">
        <div style="padding-left:20px">
            <div class="OrgangeFont bld" style="font-size:20px;padding-top:2px"><?php echo $config_data_array['form_heading']?></div><div style="padding-top:2px">We need a few details from you to create your free Shiksha account & to get the relevant institutes contact you.</div>
        </div>
        <div class="lineSpace_10">&nbsp;</div>
        <script>
        var testprepflag = "<?php if ( $pagetype  == 'testpreppage' ) { echo "testprep"; } else { echo "none";}?>";
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
                 //alert('dssds');
		<?php
		foreach($data as $data1) {
			$data1 = $data1;
		}
		if((isset($userarray['name'])) && (!empty($userarray['name'])))
{
		    $userlocpref = $data1['PrefData'][0]['LocationPref'];
		    //print_r($userlocpref);
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
	    	 try{
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
	    	 } catch(e) {
	 			//alert(e);
			    }
	    }

	     /* API that enter default value in drop downs logged-in case */
	    function selectComboBoxValue(comboboxId,valuetoselect,atttocompare) {
	    try{
		if (document.getElementById(comboboxId)) {
		    var output=document.getElementById(comboboxId).options;
		    for(var i=0;i<output.length;i++) {
			if(output[i].value == valuetoselect){
			    output[i].selected=true;
			}
		    }
		}
	     } catch(e) {
	 			//alert(e);
		    }
	    }
	    /* API to load html form from ajax */
            function ajax_form_loader(url) {
                var mysack = new sack();
                mysack.requestFile = url;
                mysack.method = 'POST';
                mysack.onLoading = function() { showloader(); };
                mysack.onCompletion = function() {
                    document.getElementById('marketing_form_html_it').innerHTML= "";
                    var response = mysack.response;
		    document.getElementById('marketing_form_html_it').innerHTML= response;
		    // set exam taken block for MBA/PGDM
		    if(document.getElementById('homesubCategories').value==2 ||
				    document.getElementById('homesubCategories').value==24) {
		      if($('exams_taken_block')) { document.getElementById('exams_taken_block').style.display="block"; }

			    }
		    // set work experince and residence location fields for distance MBA,
		    // that is why we are making below funny check :)
		    if (in_array_js($('homesubCategories').value,

['24','25','26','27','29','30','31','32','33','34','727','728','729','730','731','732','733','734','710','711'])) {
			         if(document.getElementById('work_experience_block')) {
                	document.getElementById('work_experience_block').style.display="block";
                	document.getElementById('ExperienceCombo').setAttribute('validate','validateSelect');
             	    document.getElementById('ExperienceCombo').setAttribute('required','1');
			         }
// 			         if(document.getElementById('residenceLocation')) {
// 		                	document.getElementById('residenceLocation').style.display="block";
// 		                	document.getElementById('citiesofquickreg').setAttribute('validate','validateSelect');
// 		             	    document.getElementById('citiesofquickreg').setAttribute('required','1');
// 					         }
	               }
                    closeMessage();
                    addOnBlurValidate(document.getElementById('frm1'));
                    addOnFocusToopTip1(document.getElementById('frm1'));
                };
                mysack.runAJAX();
            }
	    /* ajax_form_loader() */
            function actionDesiredCourseDD(id) {
                try{
		var courseOptions =document.getElementById('homesubCategories').options;
		var selectindex = courseOptions.selectedIndex;

		//////////////////////////////////////////////////////////////////
		/* GLOBAL FUNCTION THAT CACHE AJAX OBJECT AND RETURN VALUE FROM ARRAY */
		enableCache = true;
		
		if (testprepflag == 'testprep')
		{
		  if ( in_array_js($('homesubCategories').value,<?php echo
		    php_array_to_js_array($testPrepCourses_requires_12th_marks); ?>) )
		  {
		    $('showUGSection').innerHTML = '';

ajax_loadContent_customized('xii_main_div','/multipleMarketingPage/Marketing/ajaxform_mba/show_xii_field');
		    $('xii_main_div').style.display = 'block';
		    $('showUGSection').style.display = 'none';
		    FLAG_TESTPREP_XII_COURSE_SELECTION = true;
		  }
		  else
		  {
		    $('xii_main_div').innerHTML = '';

ajax_loadContent_customized('showUGSection','/multipleMarketingPage/Marketing/ajaxform_mba/show_grad_field');
		    $('showUGSection').style.display = 'block';
		    $('xii_main_div').style.display = 'none';
		    FLAG_TESTPREP_XII_COURSE_SELECTION = false;
		  }
		  
		}
		///////////////////////////////////////////////////////////////////
		if ( (courseOptions[selectindex].getAttribute("CourseReach") =='local')||(courseOptions[selectindex].getAttribute("CourseReach") == '')) {
                    if(FLAG_LOCAL_COURSE_FORM_SELECTION != 0) {
                        // load it course form

         ajax_form_loader('/multipleMarketingPage/Marketing/ajaxform_mba/itcourse');

			// reset counter
			privateCounter = 1;
                    }
			FLAG_LOCAL_COURSE_FORM_SELECTION  = 0;
			// set work experince and residence location fields for distance MBA, that is why we are making below funny check :)
			if (in_array_js($('homesubCategories').value,

['24','25','26','27','29','30','31','32','33','34','727','728','729','730','731','732','733','734','710','711'])) {
                    if(document.getElementById('work_experience_block')) {
                	document.getElementById('work_experience_block').style.display="block";
                	document.getElementById('ExperienceCombo').setAttribute('validate','validateSelect');
             	    document.getElementById('ExperienceCombo').setAttribute('required','1');
                    }
//                     if(document.getElementById('residenceLocation')) {
// 	                	document.getElementById('residenceLocation').style.display="block";
// 	                	document.getElementById('citiesofquickreg').setAttribute('validate','validateSelect');
// 	             	    document.getElementById('citiesofquickreg').setAttribute('required','1');
// 	            }
               } else {
                   if(document.getElementById('work_experience_block')) {
                	   document.getElementById('ExperienceCombo').removeAttribute('validate',' ');
                	   document.getElementById('ExperienceCombo').removeAttribute('required',' ');
            	       document.getElementById('work_experience_block').style.display="none";
                   }
                   if(document.getElementById('residenceLocation')) {
                	   document.getElementById('citiesofquickreg').removeAttribute('validate',' ');
                	   document.getElementById('citiesofquickreg').removeAttribute('required',' ');
            	       document.getElementById('residenceLocation').style.display="none";
                   }
            	   }
                } else if((courseOptions[selectindex].getAttribute("CourseReach") =='national') && (courseOptions[selectindex].getAttribute("CourseLevel1") =='PG')) {
                    if (FLAG_LOCAL_COURSE_FORM_SELECTION != 1) {
                        FLAG_LOCAL_COURSE_FORM_SELECTION  = 1;
                        // load normal form from AJAX ajax should be cachable
          ajax_form_loader('/multipleMarketingPage/Marketing/ajaxform_mba/itdegree');

			changeSelectionPreferredStudyLocation();
                    }
                } else if((courseOptions[selectindex].getAttribute("CourseReach") ==
                'national') && (courseOptions[selectindex].getAttribute("CourseLevel1") ==
                'UG')) {
                    if (FLAG_LOCAL_COURSE_FORM_SELECTION != 2) {
                        FLAG_LOCAL_COURSE_FORM_SELECTION  = 2;

            ajax_form_loader('/multipleMarketingPage/Marketing/ajaxform_mba/graduate_course');

			changeSelectionPreferredStudyLocation();
                }
		fillCombo();
		checkboxselection();
           }
		document.getElementById('categoryId').value = courseOptions[selectindex].getAttribute('categoryid');
		document.getElementById('subCategoryId').value = courseOptions[selectindex].getAttribute('groupid');
		document.getElementById('desiredCourse').value = courseOptions[selectindex].value;
                if(courseOptions[selectindex].value == 2 || courseOptions[selectindex].value==24) {
		  if($('exams_taken_block')) { document.getElementById('exams_taken_block').style.display="block";}
               } else {
		 if($('exams_taken_block')) { document.getElementById('exams_taken_block').style.display="none";}
                   }
                }catch(e) {
	 			//alert(e);
		    }
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
	if($pagetype=='indianpage') {
	$string1 = '';
	foreach($managementcourses as $management_course) {
		if(strtolower($management_course['SpecializationName']) != 'all') {
		$management_course['CourseName'] = $management_course['SpecializationName'];
		}
		// populate desired course dropdown for MBA courses
		if(isset($management_course)&& !empty($management_course['CourseName'])){
			//strange logic to reset specialization id for MBA/PGDM course
			if($management_course['SpecializationId']==20)
			$management_course['SpecializationId'] = 2;
			// check made for distance MBA, set it as local
		if ( $management_course['CourseReach'] == 'national' ) {
			if ($management_course['CourseLevel1'] == 'UG') {
				$string1 .='<option CourseReach="national" CourseLevel1="UG"
				title="'.$management_course['CourseName'].'"  '.$selected_string.'
				groupid="'.$management_course['groupId'].'"
				categoryid="'.$management_course['CategoryId'].'"
				value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
				option>';
			} else if ($management_course['CourseLevel1'] == 'PG' ) {
				$string1 .='<option CourseReach="national" CourseLevel1="PG"
				title="'.$management_course['CourseName'].'"  '.$selected_string.'
				groupid="'.$management_course['groupId'].'"
				categoryid="'.$management_course['CategoryId'].'"
				value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
				option>';
			 }
			 // check made for distance MBA, set it as local
			} else if($management_course['CourseReach'] == 'local' ||  $management_course['SpecializationId']!=2)  {
				$string1 .='<option CourseReach="local"
				title="'.$management_course['CourseName'].'"  '.$selected_string.'
				groupid="'.$management_course['groupId'].'"
				categoryid="'.$management_course['CategoryId'].'"
				value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
				option>';
			}
		}
		}
	foreach ($itcourseslist as $groupId => $value) {
	    $string2 = $groupName = '';
	    usort($value, "cmp");
	    foreach ($value as $finalArray) {
		    if ( $finalArray['CourseReach'] == 'national') {
			if ($finalArray['CourseLevel1'] == 'UG') {
				// change Distance BCA's course reach as local
			    if ($finalArray['CourseName'] == 'Distance BCA') {
						$string2 .='<option CourseReach="local" CourseLevel1="UG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
				} else {
						$string2 .='<option CourseReach="national" CourseLevel1="UG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
				}
			} else if ($finalArray['CourseLevel1'] == 'PG') {
				// change Distance MCA 's course reach as local
			    if ($finalArray['CourseName'] == 'Distance MCA') {
						$string2 .='<option CourseReach="local" CourseLevel1="PG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
				} else {
						$string2 .='<option CourseReach="national" CourseLevel1="PG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
				}
			 }
			} else if($finalArray['CourseReach'] == 'local')  {
				$string2 .='<option CourseReach="local"
				title="'.$finalArray['CourseName'].'"  '.$selected_string.'
				groupid="'.$finalArray['groupId'].'"
				categoryid="'.$finalArray['CategoryId'].'"
				value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
				option>';
			}
	    }
		    $groupName =  $finalArray['groupName'];
		    $CourseLevel1 = $finalArray['CourseLevel1'] ;
		    $level = $finalArray['CourseLevel'] ;
		      if($groupName != '') {
		    $string .= '<optgroup label="'. $groupName .'">'. $string2
			.'</optgroup>';
		      } else {
		    $string .=$string2;
	        }
		    //echo $level . '====' .$groupName . '====' . $CourseLevel1 . '==== <br />';
	    }
	$string = $string1.$string;
	} else if($pagetype='testpreppage') {
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
		$dd_name = "testPrep_blogid";
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
		/* we don't have any db based identifier for PG , UG and localcources :( */
	    $userdata = array();
	    foreach ($data as $userdata) {
	    	$userdata =$userdata;
	    }
	    //echo "<pre>";
	    //print_r($userdata);
	    //echo "</pre>";
		if(!empty($userdata['PrefData'][0]['SpecializationPref'][0]['CourseReach']) &&
($logged != "No")) {
	//set Distance MBA as local, that is why below mentioned funny check is made :)
		    if(($userdata['PrefData'][0]['SpecializationPref'][0]['SpecializationId']>=25 && $userdata['PrefData'][0]['SpecializationPref'][0]['SpecializationId']<=34) || $userdata['PrefData'][0]['SpecializationPref'][0]['CourseReach'] == 'local') {
//echo "hi";
$this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_courses');
		    ?>
		    <script>
			    FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
			</script>
		     <?php }else if(($userdata['PrefData'][0]['SpecializationPref'][0]['CourseReach']=='national') &&
		    ($userdata['PrefData'][0]['SpecializationPref'][0]['CourseLevel1']=='PG')) {
		    	//echo "hefrerr";
$this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_degree');
		    ?>
			<script>
			changeSelectionPreferredStudyLocation();
			    FLAG_LOCAL_COURSE_FORM_SELECTION = 1;
			</script>
		    <?php
		    }else if(($userdata['PrefData'][0]['SpecializationPref'][0]['CourseReach']=='national') &&
		    ($userdata['PrefData'][0]['SpecializationPref'][0]['CourseLevel1']=='UG')) {
		    	//echo "here23";
            $this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_ug_courses');
		    ?>
			<script>
			changeSelectionPreferredStudyLocation();
			    FLAG_LOCAL_COURSE_FORM_SELECTION = 2;
			</script>
		    <?php
		    }
} else if($logged=='No') {
	if(($first_course['CourseReach']=='national')&& ($first_course['CourseLevel1']=='PG') && $local==FALSE) {
		$this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_degree');
	?>
	       <script>
			changeSelectionPreferredStudyLocation();
			FLAG_LOCAL_COURSE_FORM_SELECTION = 1;
			</script>
<?php }
else if(($first_course['CourseReach']=='national')&& ($first_course['CourseLevel1']=='UG') && $local==FALSE) {
		$this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_ug_courses');
?>
           <script>
			changeSelectionPreferredStudyLocation();
			FLAG_LOCAL_COURSE_FORM_SELECTION = 2;
			</script>
<?php }
else { ?>
            <script>
                changeSelectionPreferredStudyLocation();
			    FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
			</script>
<?php
	$this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_courses');
}

}

else { ?>
            <script>
                changeSelectionPreferredStudyLocation();
			    FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
			</script>
<?php
	$this->load->view('multipleMarketingPage/user_form_multipleMarketingPage_courses');
}
?>
	    </div>
	    </div>
            <!-- captcha section -->
            <?php if($logged=="No" && !isCaptchaFreeReferer($_SERVER['HTTP_REFERER'])) { ?>
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
                I agree to the <a href="javascript:" onclick="popitup('/shikshaHelp/ShikshaHelp/termCondition');">terms of services</a> and <a
href="javascript:" onclick="popitup('/shikshaHelp/ShikshaHelp/privacyPolicy');">privacy policy</a>
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
                    <div><input type="submit" id="subm" value="Submit"
class="continueBtn" <?php if($logged!="No") echo 'disabled = "true"'; ?> uniqueattr="MultipleMarkeingPageLayer1Submit"/></div>
                </div>
                <div class="clear_L withClear">&nbsp;</div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            <div
style="clear:left;font-size:1px;background:#F4F4F4">&nbsp;</div>
        </div>
</form>
<script id="action_after_loading_ajax_html_form">
/* Need to add possible DDs */
fillCombo();
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>">
</script>
<script>
var TRACKING_CUSTOM_VAR_MARKETING_FORM = "multiplemarketingpage";
if(typeof(setCustomizedVariableForTheWidget) == "function") {
if (window.addEventListener){
	window.addEventListener('click', setCustomizedVariableForTheWidget, false);
} else if (window.attachEvent){
	document.attachEvent('onclick', setCustomizedVariableForTheWidget);
}
}
</script>
