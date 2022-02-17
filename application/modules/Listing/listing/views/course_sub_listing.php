<!--add_college_course_form_start-->
<?php
    global $maxVideos;
    global $maxPhotos;
    global $maxDocs;
    global $featuredLogo;
    global $featuredPanel;

    $maxVideos =3;
    $maxPhotos =3;
    $maxDocs =3;
    $featuredLogo = "Yes";
    $featuredPanel ="Yes";

?>

<?php
   $attribute = array('name' => 'courseListing','id'=> 'courseListing', 'method' => 'post');
   echo form_open_multipart('listing/Listing/addCollegeCourse',$attribute);
?>
<?php
   $style = "display:block";
   if ($hasColleges == 1)
   $style = "display:block";
?>
<script>
   var completeCategoryTree = eval(<?php echo $completeCategoryTree; ?>);
   var cal = new CalendarPopup("calendardiv");
   cal.offsetX = 20;
   cal.offsetY = 0;
</script>
<!--Start_Course_Listing_Form-->
<!--<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Add Institute & Course Details</span></div>
   <div class="lineSpace_5">&nbsp;</div>
   <div class="grayLine"></div>
</div>-->

<?php if($usergroup != "cms" || $onBehalfOf=="true"){
      $this->load->view('listing/packSelection');
   }
?>

<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">What would you like to do?</span></div>
   <div class="grayLine mar_top_5p"></div>
   <div class="lineSpace_6">&nbsp;</div>
</div>

<div class="row">
   <div style="width:100%">
      <div class="normaltxt_11p_blk_verdana" style="width:100%">
	 <span style="position:relative; top:1px"><input type="radio" checked="true" id="add_type" name="add_type" value="new" onClick="hideelement('existing_college');addCollegeJS();" /></span><span>Add Course to New University/ Institutes:</span><br />
	 <div class="lineSpace_2">&nbsp;</div>
	 <span style="position:relative; top:1px"><input type="radio" id="add_type" name="add_type" value="existing" onClick="showelement('existing_college');removeCollegeJS();" /></span><span>Add Course to Your University/ Institutes:</span>
	 <div id="existing_college" style="display:none">
	 <div class="row">
		<div>
			<div class="r1 bld">Country:</div>
			<div class="r2">
			   <select onchange="getCitiesWithCollege('si_cities','si_country');" id="si_country" validate="validateSelect" minlength="1" maxlength="100" caption="Country" >
					<option value="">Select Country</option>
					<?php
						foreach($country_list as $country) :
							$countryId = $country['countryID'];
							$countryName = $country['countryName'];
							if($countryId == 1) { continue; }
								$selected = "";
							if($countryId == 2) { $selected = "selected='selected'"; }
				 	?>
					<option value="<?php echo $countryId; ?>" <?php //echo $selected; ?>><?php echo $countryName; ?></option>
				 <?php endforeach; ?>
				</select>
			</div>
			<div class="clear_L"></div>
		</div>
	</div>
	<div class="row errorPlace">
	 	<div class="r1">&nbsp;</div>
	 	<div class="r2 errorMsg" id="si_country_error" ></div>
	 	<div class="clear_L"></div>
	</div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
		<div>
			<div>
				<div class="r1 bld">City:</div>
				<div class="r2">
					<select onchange="getInstitutesForCityList();" id="si_cities" style="width:150px" validate="validateSelect" minlength="1" maxlength="100" caption="City">
					<option value="">Select City</option>
					</select>
				</div>
				<div class="clear_L"></div>
			</div>
		</div>
	</div>
	<div class="row errorPlace">
	 	<div class="r1">&nbsp;</div>
	 	<div class="r2 errorMsg" id="si_cities_error" ></div>
	 	<div class="clear_L"></div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
	<div class="row">
		<div>
			<div>
				<div class="r1 bld">College/Institute:</div>
				<div class="r2">
					<select id="si_colleges" name="existing_college" style="width:200px;" validate="validateSelect"  minlength="1" maxlength="1000" caption="College/Institute" >
					<option value="">Select College/Institute</option>
					</select>
				</div>
				<div class="clear_L"></div>
			</div>
		</div>
	</div>
	<div class="row errorPlace">
	 	<div class="r1">&nbsp;</div>
	 	<div class="r2 errorMsg" id="si_colleges_error" ></div>
	 	<div class="clear_L"></div>
	</div>
	</div>
	 <!--<select id="existing_college" name="existing_college" style="display:none;margin-left:10px;width:150px;" >
	    <option selected="selected" value="0" >Select University/College</option>
	    <?php foreach($institutes as $institute): ?>
	    <option value="<?php echo $institute['type_id']; ?>"><?php $str=$institute['Title']; if (strlen($str) > 50) $str=substr($str,0,47)."..."; echo $str; ?></option>
	    <?php endforeach; ?>
	 </select>-->
      </div>
   </div>
   <div class="row errorPlace">
      <div class="errorMsg" id="existing_college_error" style="padding:5px 10px 0px 5px" /></div>
   </div>
</div>
<div class="lineSpace_20">&nbsp;</div>

<div id="college_details_main">
   <div id="college_details" style="<?php echo $style;?>">
      <?php $this->load->view('listing/college_details'); ?>
   </div>
</div>


<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Course Details</span></div>
   <div class="grayLine mar_top_5p"></div>
</div>

<div class="lineSpace_25">&nbsp;</div>
<div class="row">
   <div style="display: inline; float:left; width:100%">
      <div class="r1 bld">&nbsp;</div>
      <div class="r2">All field marked with <span class="redcolor">*</span> are compulsory to fill in</div>
   </div>
</div>
<div class="lineSpace_25">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Course Name:<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2">
	    <input type="text" name="c_course_title" id="c_course_title" validate="validateStr" maxlength="100" minlength="2" tip="course_title" class="w62_per" required="true" caption="Course Title"/>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="c_course_title_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Level:&nbsp;</div>
	 <div class="r2">
	    <select id="course_level" name="course_level" style="width:200px" onChange="checkCourseLevel();" tip="course_level" >
	       <option value="">Select Level</option>
	       <option value="Under Graduate Degree">Under Graduate Degree</option>
	       <option value="Post Graduate Degree">Post Graduate Degree</option>
	       <option value="Post Graduate Diploma">Post Graduate Diploma</option>
	       <option value="Doctrate/PhD">Doctrate/PhD</option>
	       <option value="Post Doctrate/ Post PhD">Post Doctrate/ Post PhD</option>
	       <option value="Diploma">Diploma</option>
	       <option value="Other">Other</option>
	    </select>
	    <input type="text" id="other_course_level" style="display:none" name="other_course_level" />
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="course_level_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Type of Course:&nbsp;</div>
	 <div class="r2">
	    <select id="course_type" name="course_type" style="width:200px" tip="course_type" onchange="checkForExamPrep(this);" >
	       <option value="">Select Type</option>
	       <option value="Exam Preparation">Exam Preparation</option>
	       <option value="Part time">Part time</option>
	       <option value="Full time">Full time</option>
	       <option value="Correspondence course">Correspondence course</option>
	       <option value="Certification">Certification</option>
	    </select>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="course_type_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div id="examPrepContent" style="display:none;">
<div class="lineSpace_13">&nbsp;</div>
<?php
    $examSelectPanelAttribs = array('examSelectComboName'=>'examPrepRelatedExams', 'examSelectComboCaption'=>'Coaching for Exams');
    $this->load->view('common/examSelectPanel', $examSelectPanelAttribs);
?>
</div>
<script>
function checkForExamPrep(courseTypeObj) {
    if(!courseTypeObj) { return; }
    if(courseTypeObj.value === 'Exam Preparation') {
        document.getElementById('examPrepContent').style.display = 'block';
    } else {
        document.getElementById('examPrepContent').style.display = 'none';
    }
}
</script>
<div class="lineSpace_13">&nbsp;</div>
<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Category:<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2">
	    <div id="c_categories_combo"></div>
	    <!--<select name="c_categories[]" id="c_categories" size="8" multiple ifvalidate="validateCatCombo" minlength="1" maxlength="10"  style="width:200px;">
	    </select>-->
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="c_categories_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Course Description:&nbsp;</div>
	 <div class="r2">
	    <textarea type="text" name="c_overview" id="c_overview" minlength="0" validate="validateStr" maxlength="5000" class="w62_per mceEditor" style="height:130px" caption="Description" /></textarea>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="c_overview_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Course Duration:<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2">
	    <input type="text" id="c_duration1" name="c_duration1" validate="validateStr" maxlength="5" minlength="1" class="mar_right_1p" style="width:120px" tip="course_duration" required="true" caption="Course Duration" />
	    <select id="c_duration2" name="c_duration2">
	       <option value="Years">Years</option>
	       <option value="Months">Months</option>
	       <option value="Weeks">Weeks</option>
	       <option value="Days">Days</option>
	       <option value="Hours">Hours</option>
	    </select>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="c_duration1_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<?php if ($usergroup == "cms"){ ?>
<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Tags:&nbsp;</div>
	 <div class="r2">
	    <input type="text" name="c_course_tags" id="c_course_tags" validate="validateStr" maxlength="200" minlength="2" tip="course_tags" class="w62_per" caption="Course Tags"/>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="c_course_tags_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>
<?php } ?>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Course Start Date:&nbsp;</div>
	 <div class="r2">
             <input type="text" name="course_start_date" validate="validateDate" id="course_start_date" style="width:175px" readonly onclick="cal = new CalendarPopup('calendardiv');cal.select($('course_start_date'),'sd','yyyy-MM-dd');" />
	    <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal = new CalendarPopup('calendardiv');cal.select($('course_start_date'),'sd','yyyy-MM-dd');" />
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="course_start_date_error"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Course End Date:&nbsp;</div>
	 <div class="r2">
             <input type="text" name="course_end_date" validate="validateDate" id="course_end_date" style="width:175px" readonly onClick="if(fillStartDateFirst()){cal = new CalendarPopup('calendardiv');disableDatesTill(cal,document.getElementById('course_start_date').value);cal.select($('course_end_date'),'ed','yyyy-MM-dd');}" />
	    <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="if(fillStartDateFirst()){cal = new CalendarPopup('calendardiv');disableDatesTill(cal,document.getElementById('course_start_date').value);cal.select($('course_end_date'),'ed','yyyy-MM-dd');}" />
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="course_end_date_error"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Course Fees:&nbsp;</div>
	 <div class="r2">
	    <input type="text" id="c_fees_amount" name="c_fees_amount" validate="validateStr" maxlength="20" minlength="0" class="mar_right_1p" style="width:95px" tip="course_fees" caption="Course Fees" />
	    <select id="c_fees_currency" name="c_fees_currency">
	       <option value="INR">INR</option>
	       <option value="USD">USD</option>
	       <option value="AUD">AUD</option>
	       <option value="CAD">CAD</option>
	       <option value="SGD">SGD</option>
	       <option value="GBP">GBP</option>
	       <option value="NZD">NZD</option>
	    </select>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="errorMsg" id="c_fees_amount_error"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">&nbsp;</div>
	 <div class="r2">
	    <input type="checkbox" id="chk_fees_other" name="chk_fees_other" onclick="showHideElement('c_fees_other');" />Other<br />
	    <textarea id="c_fees_other" name="c_fees_other" style="display:none" validate="validateStr" maxlength="500" minlength="0" class="w62_per" caption="Fees" ></textarea>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="errorMsg" id="c_fees_other_error"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div class="r1 bld">Course Eligibility:&nbsp;</div>
      <div class="r2">
	    <div class="lefttd">Minimum Qualification</div>
	    <div class="righttd">
	       <select id="eligibility1" name="eligibility1">
			  <option value="">Select</option>
			  <option value="X">Class X</option>
			  <option value="XII">Class XII</option>
			  <option value="Graduate">Graduation</option>
			  <option value="Post-Graduate">PostGraduation</option>
	       </select>
	    </div>
	    <div class="clear_L lineSpace_10">&nbsp;</div>
	    <div class="lefttd">Marks</div>
	    <div class="righttd">
	       <input type="text" id="c_elig_marks" name="c_elig_marks" style="width:50px" />
	    </div>
	    <div class="clear_L lineSpace_10">&nbsp;</div>
	    <div class="lefttd">Min Exp (in yrs)</div>
	    <div class="righttd">
	       <select id="eligibility3" name="eligibility3">
			  <option value="">Select</option>
			  <option value="1 year">1</option>
			  <option value="2 years">2</option>
			  <option value="3 years">3</option>
			  <option value="4 years">4</option>
			  <option value="5 years">5</option>
	       </select>
	    </div>
	    <div class="clear_L lineSpace_10">&nbsp;</div>
	    <div class="lefttd">Max Exp (in yrs)</div>
	    <div class="righttd">
	       <select id="eligibility4" name="eligibility4">
			  <option value="">Select</option>
			  <option value="1 year">1</option>
			  <option value="2 years">2</option>
			  <option value="3 years">3</option>
			  <option value="4 years">4</option>
			  <option value="5 years">5</option>
	       </select>
	    </div>
	    <div class="clear_L lineSpace_10">&nbsp;</div>
	    <div class="lefttd">
	       <input type="checkbox" value="chk_elig_others" name="chk_elig_others" value="yes" onclick="showHideElement('other_elig');">Others
	    </div>
		<div>
		  	<textarea name="other_elig" id="other_elig" maxlength="1000" validate="validateStr" minlength="5" style="display:none;" class="" caption="Eligibility" ></textarea>
		</div>
		 <div class="clear_L row errorPlace">
		 	 <div class="errorMsg" id="other_elig_error"></div>
	 	 </div>
	 </div>
      </div>
      <div class="clear_L"></div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Course Syllabus/Contents:&nbsp;</div>
	 <div class="r2">
	    <textarea type="text" name="c_contents" id="c_contents" validate="validateStr"  minlength="0" maxlength="5000" minlength="0" class="w62_per mceEditor" style="height:130px" caption="Syallabus/Contents" /></textarea>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="c_contents_error"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<!--<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Tests Required:&nbsp;</div>
	 <div class="r2">
	    <div class="row">
	       <div class="float_L" style="width:80px"><span style="position:relative; top:2px"><input type="checkbox" value="TOEFL" name="c_test[]"></span>TOEFL</div>
	       <div class="float_L"><span style="position:relative; top:2px"><input type="checkbox" value="IELTS" name="c_test[]"></span>IELTS</div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row">
	       <div class="float_L" style="width:80px"><span style="position:relative; top:2px"><input type="checkbox" value="GRE" name="c_test[]"></span>GRE</div>
	       <div class="float_L"><span style="position:relative; top:2px"><input type="checkbox" value="CAT" name="c_test[]"></span>CAT</div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row">
	       <div class="float_L" style="width:80px"><span style="position:relative; top:2px"><input type="checkbox" value="MAT" name="c_test[]"></span>MAT</div>
	       <div class="float_L"><span style="position:relative; top:2px"><input type="checkbox" value="SAT" name="c_test[]"></span>SAT</div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="">
	       <span style="position:relative; top:2px"><input type="checkbox" value="Others" name="c_test[]" onclick="showHideElement('other_test');"></span>Others
	       <div style="position:relative; top:2px">
		  <textarea name="other_test" id="other_test" validate="validateStr" validate="validateStr" maxlength="250" minlength="5" style="display:none;" class="w62_per" caption="Other Tests" ></textarea></div></div>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="other_test_error"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>
-->

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Selection Criterion:&nbsp;</div>
	 <div class="r2">
	    <textarea type="text" name="c_selection_criteria" validate="validateStr" id="c_selection_criteria" maxlength="5000" minlength="0" class="w62_per mceEditor" style="height:130px;" caption="Selection Criteria" /></textarea>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="c_selection_criteria_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_20">&nbsp;</div>
<?php

    $examRequiredSelectPanelAttribs = array('examSelectComboName'=>'c_test', 'examSelectComboCaption'=>'Tests Required');
    $this->load->view('common/examSelectPanel', $examRequiredSelectPanelAttribs);
?>
<div class="lineSpace_20">&nbsp;</div>

<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Invite People to Write Email Testimonials</span></div>
   <div class="grayLine mar_top_5p"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Enter Email ID:&nbsp;</div>
	 <div class="r2">
	    <textarea type="text" name="i_emailids" id="i_emailids" validate="multiEmail" maxlength="1000" minlength="0" class="w62_per" caption="Emails" tip="testi_emails"/></textarea>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="i_emailids_error"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_20">&nbsp;</div>


<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Other Services</span></div>
   <div class="grayLine mar_top_5p"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Placement Services:&nbsp;</div>
	 <div class="r2">
	    <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" value="yes" onClick="document.getElementById('c_placements_desc').style.display='inline';"/></span> Yes
	    <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" value="no" onClick="document.getElementById('c_placements_desc').style.display='none';"/></span> No
	    <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" checked="checked"  value="N.A." onClick="document.getElementById('c_placements_desc').style.display='none';"/></span> Info not available
	    <div class="lineSpace_5">&nbsp;</div>
	    <!--<input id="c_placements_desc" name="c_placements_desc" type="text" validate="validateStr" value="" maxlength="30" minlength="0" style="display:none;width:200px" />-->
	    <textarea id="c_placements_desc" name="c_placements_desc" validate="validateStr" maxlength="1000" minlength="0" style="display:none;" class="w62_per" caption="Placement Description"></textarea>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div id="c_placements_desc_error" class="r2 errorMsg"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Scholarships/ Financial Aid available:&nbsp;</div>
	 <div class="r2">
	    <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="yes" onClick="document.getElementById('c_schol_exams').style.display='inline';"/></span> Yes
	    <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="no" onClick="document.getElementById('c_schol_exams').style.display='none';" /></span> No
	    <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="N.A." onClick="document.getElementById('c_schol_exams').style.display='none';" checked="checked"/></span> Info not available
	    <div class="lineSpace_5">&nbsp;</div>
	    <textarea type="text" name="c_schol_exams" id="c_schol_exams" validate="validateStr" maxlength="1000" minlength="0" style="display:none;" class="w62_per"  caption="Scholarships/ Financial Aid" /></textarea>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div id="c_schol_exams_error" class="r2 errorMsg"></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div class="r1 bld">Course Hostel Facility:&nbsp;</div>
      <div class="r2">
	 <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="Yes"/></span> Yes
	 <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="No" /></span> No
	 <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="N.A." checked="checked" /></span> Info not available
      </div>
      <div class="clear_L"></div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div id="admin_details_main">
   <div id="admin_details" style="<?php echo $style;?>">
      <div class="row">
          <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Institute Contact Details (Enter the institute contact details to receive emails and phone enquiries from the interested candidates.)</span></div>
	 <div class="grayLine mar_top_5p"></div>
      </div>
      <div class="lineSpace_13">&nbsp;</div>

      <div class="row">
	 <div>
	    <div>
	       <div class="r1 bld">Name:&nbsp;</div>
	       <div class="r2">
		  <input type="text" name="c_cordinator_name" id="c_cordinator_name"  validate="validateStr" maxlength="100" minlength="5" style="width:200px" caption="Name"/>
	       </div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row errorPlace">
	       <div class="r1">&nbsp;</div>
	       <div class="r2 errorMsg" id="c_cordinator_name_error"></div>
	       <div class="clear_L"></div>
	    </div>
	 </div>
      </div>
      <div class="lineSpace_13">&nbsp;</div>

      <div class="row">
	 <div>
	    <div>
	       <div class="r1 bld">Contact No:&nbsp;</div>
	       <div class="r2">
		  <input type="text" name="c_cordinator_no" id="c_cordinator_no" maxlength="100" minlength="5" style="width:200px" caption="Contact Number" />
	       </div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row errorPlace">
	       <div class="r1">&nbsp;</div>
	       <div class="r2 errorMsg" id="c_cordinator_no_error"></div>
	       <div class="clear_L"></div>
	    </div>
	 </div>
      </div>
      <div class="lineSpace_13">&nbsp;</div>

      <div class="row">
	 <div>
	    <div>
	       <div class="r1 bld">Email:&nbsp;</div>
	       <div class="r2">
		  <input type="text" name="c_cordinator_email" id="c_cordinator_email" validate="validateEmail" maxlength="125" minlength="0" style="width:200px" caption="Email"/>
	       </div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row errorPlace">
	       <div class="r1">&nbsp;</div>
	       <div class="r2 errorMsg" id="c_cordinator_email_error" ></div>
	       <div class="clear_L"></div>
	    </div>
	 </div>
      </div>
      <div class="lineSpace_13">&nbsp;</div>

      <div class="row">
	 <div>
	    <div>
	       <div class="r1 bld">Website Address:&nbsp;</div>
	       <div class="r2">
                   <input type="text" name="c_website" id="c_website" maxlength="1000" minlength="0" style="width:200px" caption="Institute Website Address" validate="validateUrl"/>
	       </div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row errorPlace">
	       <div class="r1">&nbsp;</div>
	       <div class="r2 errorMsg" id="c_website_error" ></div>
	       <div class="clear_L"></div>
	    </div>
	 </div>
      </div>
      <div class="lineSpace_20">&nbsp;</div>

   </div>
</div>

<?php
/*
   if($flagMedia == 1) {
      echo "<input type='hidden' name='c_media_content' value='1'/>";
      $this->load->view('listing/media_content');
   }
   else{
      echo "<input type='hidden' name='c_media_content' value='0'/>";
   }
   */
?>
      <input type='hidden' name='c_media_content' value='1'/>
      <?php// $this->load->view('listing/media_content'); ?>
   <?php    $this->load->view('enterprise/cmsMediaContent'); ?>

<div class="lineSpace_13">&nbsp;</div>
<?php if ($usergroup != "cms"): ?>
<div class="row">
	 <div>
	    <div>
	       <div class="r1 bld">Type the characters you see in picture:<span class="redcolor">*</span></div>
	       <div class="r2">
		  			<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&secvariable=seccode&randomkey=<?php echo rand(); ?>" id="topicCaptcha"/><br />
                                        <input type="text" name="captcha_text" id="captcha_text" caption="Security Code" tip="secCode" >
	       </div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row errorPlace">
	       <div class="r1">&nbsp;</div>
	       <div class="r2 errorMsg" id="captcha_text_error"></div>
	       <div class="clear_L"></div>
	    </div>
	 </div>
</div>
<div class="lineSpace_13">&nbsp;</div>
<?php endif; ?>

<div id="correct_above_error" style="display:none;color:red;"></div><br/>

<input type="hidden" name="addCourse" value="1" />
<div class="lineSpace_15">&nbsp;</div>
<div style="display: inline; float:left; width:100%">
   <div class="buttr3">
      <button class="btn-submit7 w9" value="addCourse" type="button" onClick="validateCourseListing(this.form);">
	 <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit Listing</p></div>
      </button>
   </div>
<?php $redirectLocation = "/";
	if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
		$redirectLocation = $_SERVER['HTTP_REFERER'];
?>
   <div class="buttr2">
      <button class="btn-submit11 w4" value="cancel" type="button" onClick="location.replace('<?php echo $redirectLocation;?>');" >
	 <div class="btn-submit11"><p class="btn-submit12">Cancel</p></div>
      </button>
   </div>
   <div class="clear_L"></div>
</div>
<div class="clear_L"></div>
</form>
<!--End_Course_Listing_Form-->
<script>
   getCategories(false);
   document.courseListing.reset();
   addOnBlurValidate(document.getElementById('courseListing'));
   addOnFocusToopTip(document.getElementById('courseListing'));
   getCitiesForCountryListAnother(1);
   var formName = "courseListing";

   function resetForm()
   {
   		document.courseListing.reset();
   		hideelement('other_test','c_placements_desc','c_schol_exams','c_fees_other','other_elig','existing_college');
   		addCollegeJS();
   }

   function validateCourseListing(objForm)
   {
	 var duration_flag = checkCourseDuration();
	 var flag = validateFields(objForm);
	 var catCombo_flag = validateCatCombo('c_categories',10,1);
	 var f1 = validateExistingCollege();
	 var f2 = validateCourseType();
         if(flag==true && duration_flag==true && catCombo_flag==true && f1==true && f2==true){
             $('correct_above_error').innerHTML  = "";
             $('correct_above_error').style.display = 'none';
             <?php if ($usergroup != "cms") : ?>
                 validateCaptchaForListing();
             <?php  else : ?>
                 objForm.submit();
             <?php endif; ?>
         }else{
             $('correct_above_error').innerHTML  = "Please scroll up and correct the fields marked in Red!";
             $('correct_above_error').style.display = 'inline';
             return false;
         }

   }

   function fillStartDateFirst(){
           if(document.getElementById('course_start_date').value == ""){
                   document.getElementById('course_end_date_error').innerHTML = "Please fill Course Start date first!";
                   document.getElementById('course_end_date_error').parentNode.style.display = 'inline';
                   return false;
               }else{
                   document.getElementById('course_end_date_error').innerHTML = "";
                   document.getElementById('course_end_date_error').parentNode.style.display = 'none';
                   return true;
           }
   }

   function checkCourseDuration()
   {
	 if (document.getElementById('c_duration1').value == "")
	 {
	       document.getElementById('c_duration1_error').innerHTML = "Please select the course duration";
	       //document.getElementById('c_duration1_error').style.display = "inline";
	       document.getElementById('c_duration1_error').parentNode.style.display = 'inline';
	       return false;
	 }
	 else
	 {
	       document.getElementById('c_duration1_error').innerHTML = "";
	       //document.getElementById('c_duration1_error').style.display = "none";
	       document.getElementById('c_duration1_error').parentNode.style.display = 'none';
	       return true;
	 }

   }
   Event.observe(window, 'load', function () {
	 tinyMCE.init({ mode : "textareas", theme : "simple",editor_selector : "mceEditor", editor_deselector : "mceNoEditor"});
   });

   <?php if($usergroup == "cms" && $onBehalfOf!="true"){ ?>
   packSpecificChangesCMS("<?php echo $listingType; ?>");
   <?php  } ?>

   fillProfaneWordsBag();
</script>
