<script>
var BRONZE_LISTINGS_BASE_PRODUCT_ID = '<?php echo BRONZE_LISTINGS_BASE_PRODUCT_ID;?>';
var GOLD_SL_LISTINGS_BASE_PRODUCT_ID = '<?php echo GOLD_SL_LISTINGS_BASE_PRODUCT_ID;?>';
var GOLD_ML_LISTINGS_BASE_PRODUCT_ID = '<?php echo GOLD_ML_LISTINGS_BASE_PRODUCT_ID?>';
	var SITE_URL = '<?php echo base_url() ."/";?>';
	var cal = new CalendarPopup("calendardiv");
    var currentTime = new Date();
    var month = currentTime.getMonth() + 1;
    var day = currentTime.getDate() - 1 ;
    var year = currentTime.getFullYear();
    var dateStr = month + '/' + day + '/' + year;
    cal.offsetX = 20;
    cal.offsetY = 0;
</script>
<script>
	function performActionForResponse(responseText) {
        //alert(responseText);
		responseText = eval("("+responseText +")");
		if(typeof responseText.Success != 'undefined' && responseText.Success != '') {
			window.location = responseText.Success;
		}else {
			try{document.getElementById('save_add').disabled = false;} catch (e) {}
			try{document.getElementById('save_continue').disabled = false;} catch(e) {}
			try{document.getElementById('save_preview').disabled = false;} catch(e) {}			
			for(var failIssue in responseText.Fail) {
				var errorContainer = 'common_error';
				switch(failIssue) {
					case 'applicationDoc' :
						errorContainer = 'course_app_form_1_error';
						document.getElementById('course_app_form_1').focus();
						break;
                                        case 'c_brochure_panel' :
						errorContainer = 'c_brochure_panel_error';
                                                $('correct_above_error').style.display = "block"; 
                                                $('correct_above_error').innerHTML = "Please scroll up and correct the fields marked in Red!";
						//$('c_brochure_panel_error').focus();
						break;
                                                
                                        case "location_issue" :
						errorContainer = 'location_table_error';
                                                $('correct_above_error').innerHTML  = "Please scroll up and correct the fields marked in Red!";
                                                $('correct_above_error').style.display = 'inline';
						break;
					
                                        case "subscription_issue" :
						errorContainer = 'subs_unselect_error';
                                                $('correct_above_error').innerHTML  = "Please scroll up and correct the fields marked in Red!";
                                                $('correct_above_error').style.display = 'inline';
						removeExpiredSubscription(responseText.subscription_id);
						break;
					
				}
				document.getElementById(errorContainer).innerHTML = responseText.Fail[failIssue];
				document.getElementById(errorContainer).style.display = '';
				document.getElementById(errorContainer).parentNode.style.display = '';
			}
		}
	}
</script>
<div style="display:none" align="center">
	<div id="common_error" class="errorMsg bld fontSize_14p" style="display:none" align="center"></div>
	<div class="lineSpace_10">&nbsp;</div>
</div>

<form action="<?php echo $formPostUrl; ?>" name="courseListing" id="courseListing" method="post" enctype="multipart/form-data">
<?php
if($locations[0]['country_id'] == 2) {
    $isAbroadListing = 0;
    $maxMobileFieldLength = 10;
    $minMobileFieldLength = 10;
} else {
    $isAbroadListing = 1;
    $maxMobileFieldLength = 20;
    $minMobileFieldLength = 8;
} ?>
	<input type="hidden" name="isAbroadListing" id="isAbroadListing" value="<?=$isAbroadListing?>"/>
	<input type="hidden" name="onBehalfOf" value="<?php echo $onBehalfOf; ?>"/>
	<input type="hidden" name="clientId" value="<?php echo $clientId; ?>"/>
        <input type="hidden" name="upgradeCourseForm" value="<?php echo $upgradeCourseForm;?>"/>
	<input type="hidden" id="h_courseId" name="courseId" value="<?php echo $courseId; ?>"/>
	<input type="hidden" id="h_instituteId" name="instituteId" value="<?php echo $institute_id; ?>"/>
	<input type="hidden" id="h_instituteLocation" name="instituteLocation" value="<?php echo $institute_location; ?>"/>
	<input type="hidden" id="h_instituteName" name="instituteName" value="<?php echo $institute_name; ?>"/>
	<input type="hidden" id="h_dateFormSubmission" name="dateFormSubmissionOlder" value="<?php echo $date_form_submission; ?>"/>
	<input type="hidden" id="h_dateResultDeclaration" name="dateResultDeclarationOlder" value="<?php echo $date_result_declaration; ?>"/>
	<input type="hidden" id="h_dateCourseComencement" name="dateCourseComencementOlder" value="<?php echo $date_course_comencement; ?>"/>
	<input type="hidden" name="nextAction" id="nextAction" value=""/>
	<input type="hidden" name="previewAction" id="previewAction" value=""/>
	<input type="hidden" name="flow" value="<?php echo $flow; ?>"/>
	<input type="hidden" id="applicationForm_removed" name="applicationForm_removed" value="0"/>
	<input name="course_level" id="course_level_degree" type="hidden" value="Exam Preparation"/>
	<input type="hidden" name="courseType" id="courseType" value="testPrep"/>
	<input type="hidden" name="courseSubmitDate" id="courseSubmitDate" value="<?=$submitDate?>"/>
	<input type="hidden" name="courseViewCount" id="courseViewCount" value="<?=$viewCount?>"/>
	<input type="hidden" name="no_Of_Past_Paid_Views" id="no_Of_Past_Paid_Views" value="<?=$no_Of_Past_Paid_Views?>"/>
	<input type="hidden" name="no_Of_Past_Free_Views" id="no_Of_Past_Free_Views" value="<?=$no_Of_Past_Free_Views?>"/>
	<div class="spacer10 clearFix"></div>
	&nbsp;&nbsp;&nbsp;This course is being <?php echo ($flow=='add') ? 'posted':'edited'; ?>
	<?php if ($onBehalfOf == 'true'){
		if ($clientId != '') { ?>
			for Client named: <b><?php echo $clientDetails['displayname']; ?></b> with email id: <b><?php echo $clientDetails['email']; ?> </b>
<div class="spacer10 clearFix"></div>
				<?php }
	}
	$institute_name = str_replace(array('"'),array("&quot;"),$institute_name) ;
	?>
	<script>
		document.getElementById('inst_title').innerHTML = "<?php echo $institute_name ;?>";
		document.getElementById('inst_title_location').innerHTML = ', <?php echo $institute_location ;?>';
	</script>

<div style="margin:0 10px">
    <div style="margin:0 10px">
        <?php if ($successResponse){ $boxStyle="block"; } else{ $boxStyle ="none"; }?>
        <div class="lineSpace_10">&nbsp;</div>
        <div style="margin-bottom:10px;background:#fffdd6;border:1px solid #facb9d;line-height:30px;display:<?php echo $boxStyle; ?>" id="confirmationDiv">
            <!--<img src="/public/images/xImg.jpg" align="right" style="margin:10px 10px 0 0" />-->
            &nbsp; &nbsp; &nbsp;<b>Success! </b><?php echo $successResponseText; ?>
        </div>

        <?php if((($usergroup != "cms" || $onBehalfOf=="true") && $flow=='add')|| ($flow=='upgrade')){
                $this->load->view('listing_forms/packsInPage');
            }?>

        <div class="lineSpace_10">&nbsp;</div>
        <div class="row">
            <span style="float:right;padding-top:3px">All field marked with <span class="redcolor fontSize_13p">*</span> are compulsory to fill in</span>
            <span class="formHeader"><a class="formHeader" name="main" style="text-decoration:none" >Course Details</a></span>
            <div class="line_1"></div>
        </div>
        <div style="line-height:16px">&nbsp;</div>
        <div>
			<?php $this->load->view('listing_forms/courseListCourseForm'); ?>
            <div style="margin-right:235px">
                <div class="row float_L">
                    <div class="row">
                        <div class="row1"><b>Course Name<span class="redcolor fontSize_13p">*</span><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'c_course_name_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
			<div id="c_course_name_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('c_course_name_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Maximum 100 characters .</li>
					<li>No deviation from name, prefix, suffix as provided by the institute.</li>
					
					</ul>
			</div>
                        <div class="row2">
                            <input type="text" profanity="true" blurMethod="check_Course_name(this);" value="<?=stripslashes($coursettl)?>" name="c_course_title" id="course_title" validate="validateStr" required="true" maxlength="100" minlength="1" style="width:250px" tip="course_title" caption="Course Name" />
                            <div style="display:none"><div class="errorMsg" id="course_title_error"></div></div>
                        </div>
                    </div>
                    <div style="line-height:9px;clear:both">&nbsp;</div>
                    <div class="row">
                        <div class="row1"><b>Mode of learning<span class="redcolor fontSize_13p">*</span>:</b></div>
                        <div class="row2">
                            <select blurMethod="check_Course_name(this);" name="c_modeOfLearning" style="width:165px" id="c_modeOfLearn" validate="validateSelect"
                            required=true minlength="1" maxlength="100" caption="Mode of Learning" tip="mode_of_learning">
                              <option value="">Select</option>
                                <option value="classroom" <?php if ($course_type == 'classroom'): ?> Selected <?php endif; ?> >Classroom</option>
                              	<option value="correspondence" <?php if ($course_type == 'correspondence'): ?> Selected <?php endif; ?> >Correspondence</option>
							    <option value="test-series" <?php if ($course_type == 'test-series'): ?> Selected <?php endif; ?> >Test Series</option>
							    <option value="E-learning" <?php if ($course_type == 'E-learning'): ?> Selected <?php endif; ?> >E-learning</option>
                            </select>
                            <div style="display:none"><div class="errorMsg" id="c_modeOfLearn_error"></div></div>
                        </div>
                    </div>
                    <div style="line-height:9px;clear:both">&nbsp;</div>
                    <!--<div class="row">
						<div id="examPrepContent" style="display:none;margin-left:80px">
						<?php
							//$examSelectPanelAttribs = array('examSelectComboName'=>'examPrepRelatedExams', 'examSelectComboCaption'=>'Coaching for Exams', 'examSelected' => $tests_preparation, 'otherExam'=>($tests_preparation_other!='[]' ? $tests_preparation_other : ''));
							//$this->load->view('common/examSelectPanel', $examSelectPanelAttribs);
						?>
						</div>
					</div>-->
					<div class="row">
                        <div class="row1" style="font-size:1px">&nbsp;</div>
                        <div class="row2">
							<div style="display:none"><div class="errorMsg" id="courseLevel_error" ></div></div>
                        </div>
                    </div>
                    <div style="line-height:2px;clear:both">&nbsp;</div>
					 <?php $this->load->view('listing_forms/courseCrudLDBMapping'); ?>
					<div style="line-height:9px;clear:both">&nbsp;</div>
					<div class="row">
                    	<div class="row1 Fnt13"><b>Exams Prepared for<span class="redcolor fontSize_13p">*</span><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'Entrance_by_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
			<div id="Entrance_by_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('Entrance_by_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					
					
					<li>Can add up to 5 Exams in one course.</li>
					</ul>
			</div>
                        <div class="row2">
                            <div>
                            	<select leftposition="200" style="width:155px" name='c_entranceExam_1' id="c_entranceExam_1" tip="exams_prepared_for" onChange="javascript: $('c_entranceExamMarks_1_error').parentNode.style.display = 'none';">
					                    <option value=''>Select</option>
                                        <?php
                                        
											$string = '';
											foreach ($itcourseslist as $key=>$value)
											{

												foreach($value as $index=>$main)
												{
                                                                                                    
													if($courseExams[0]['examId'] == $main['child']['blogId']){
                                                                                                            
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'" selected="selected">'.$main['child']['acronym'].'</option>';
													}else{
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'">'.$main['child']['acronym'].'</option>';
													}
												}
													$string .=  "<optgroup label='". $main['title'] ."'>". $string1."</optgroup>";
													$string1 = "";
											}
											echo $string;
										?>
			                    </select>&nbsp;&nbsp;
                            	No. of practice tests offered:&nbsp;&nbsp;
								<input leftposition="50" type="text" style="width: 55px;" name="c_practiceTestsOffered_1" id="c_practiceTestsOffered_1" value="<?php echo $courseExams[0]['practiceTestsOffered']; ?>" tip="no_of_practice_tests_offered" onChange="javascript: $('c_entranceExamMarks_1_error').parentNode.style.display = 'none';">&nbsp;&nbsp;
                            </div>
                            <div style="display: none;"><div class="errorMsg" id="c_entranceExamMarks_1_error">show</div></div>
                        </div>
						<div class="row2">
                            <div>
                            	<select leftposition="200" style="width: 155px;" name="c_entranceExam_2" id="c_entranceExam_2" tip="exams_prepared_for" onChange="javascript: $('c_entranceExamMarks_2_error').parentNode.style.display = 'none';">
                            		<option value="-1">Select</option>
                            		<?php
											$string = '';
											foreach ($itcourseslist as $key=>$value)
											{
												foreach($value as $index=>$main)
												{
													if($courseExams[1]['examId'] == $main['child']['blogId']){
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'" selected="selected">'.$main['child']['acronym'].'</
													option>';
													}else{
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'">'.$main['child']['acronym'].'</
													option>';
													}
												}
													$string .=  "<optgroup label='". $main['title'] ."'>". $string1."</optgroup>";
													$string1 = "";
											}
											echo $string;
										?>
                            	</select>&nbsp;&nbsp;
                            		No. of practice tests offered:&nbsp;&nbsp;
                            	<input leftposition="50" type="text" style="width: 55px;" name="c_practiceTestsOffered_2" id="c_practiceTestsOffered_2" value="<?php echo $courseExams[1]['practiceTestsOffered']; ?>" tip="no_of_practice_tests_offered" onChange="javascript: $('c_entranceExamMarks_2_error').parentNode.style.display = 'none';">&nbsp;&nbsp;
                            </div>
                            <div style="display: none;"><div class="errorMsg" id="c_entranceExamMarks_2_error">show</div></div>
                            <?php if($flow!='edit'):?><div class="mt5 Fnt11" id="show_exam_options"><a href="javascript:void(0)" onclick="showMoreOptions()">+ Add More Exams</a></div><?php endif;?>
                        </div>
                        <div id="more_exam_options" <?php if($flow!='edit'):?>style="display:none"<?php endif;?>>
	                        <div class="row2">
	                            <div>
	                            	<select leftposition="200" style="width: 155px;" name="c_entranceExam_3" id="c_entranceExam_3" tip="exams_prepared_for" onChange="javascript: $('c_entranceExamMarks_3_error').parentNode.style.display = 'none';">
	                            		<option value="-1">Select</option>
	                            		<?php
											$string = '';
											foreach ($itcourseslist as $key=>$value)
											{
												foreach($value as $index=>$main)
												{
													if($courseExams[2]['examId'] == $main['child']['blogId']){
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'" selected="selected">'.$main['child']['acronym'].'</
													option>';
													}else{
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'">'.$main['child']['acronym'].'</
													option>';
													}
												}
													$string .=  "<optgroup label='". $main['title'] ."'>". $string1."</optgroup>";
													$string1 = "";
											}
											echo $string;
										?>
	                            	</select>&nbsp;&nbsp;
	                            		No. of practice tests offered:&nbsp;&nbsp;
                            		<input type="text" style="width: 55px;" name="c_practiceTestsOffered_3" id="c_practiceTestsOffered_3" value="<?php echo $courseExams[2]['practiceTestsOffered']; ?>" tip="no_of_practice_tests_offered" onChange="javascript: $('c_entranceExamMarks_3_error').parentNode.style.display = 'none';">&nbsp;&nbsp;
	                            </div>
	                            <div style="display: none;"><div class="errorMsg" id="c_entranceExamMarks_3_error">show</div></div>
	                        </div>
	                        <div class="row2">
	                            <div>
	                            	<select leftposition="200" style="width: 155px;" name="c_entranceExam_4" id="c_entranceExam_4" tip="exams_prepared_for" onChange="javascript: $('c_entranceExamMarks_4_error').parentNode.style.display = 'none';">
	                            		<option value="-1">Select</option>
	                            		<?php
											$string = '';
											foreach ($itcourseslist as $key=>$value)
											{
												foreach($value as $index=>$main)
												{
													if($courseExams[3]['examId'] == $main['child']['blogId']){
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'" selected="selected">'.$main['child']['acronym'].'</
													option>';
													}else{
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'">'.$main['child']['acronym'].'</
													option>';
													}
												}
													$string .=  "<optgroup label='". $main['title'] ."'>". $string1."</optgroup>";
													$string1 = "";
											}
											echo $string;
										?>
	                            	</select>&nbsp;&nbsp;
	                            		No. of practice tests offered:&nbsp;&nbsp;
                            		<input leftposition="50" type="text" style="width: 55px;" name="c_practiceTestsOffered_4" id="c_practiceTestsOffered_4" value="<?php echo $courseExams[3]['practiceTestsOffered']; ?>" tip="no_of_practice_tests_offered" onChange="javascript: $('c_entranceExamMarks_4_error').parentNode.style.display = 'none';">&nbsp;&nbsp;
	                            </div>
	                            <div style="display: none;"><div class="errorMsg" id="c_entranceExamMarks_4_error">show</div></div>
	                        </div>
	                        <div class="row2">
	                            <div>
	                            	<select leftposition="200" style="width: 155px;" name="c_entranceExam_5" id="c_entranceExam_5" tip="exams_prepared_for" onChange="javascript: $('c_entranceExamMarks_5_error').parentNode.style.display = 'none';">
	                            		<option value="-1">Select</option>
	                            		<?php
											$string = '';
											foreach ($itcourseslist as $key=>$value)
											{
												foreach($value as $index=>$main)
												{
													if($courseExams[4]['examId'] == $main['child']['blogId']){
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'" selected="selected">'.$main['child']['acronym'].'</
													option>';
													}else{
														$string1 .= '<option ddtype="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'">'.$main['child']['acronym'].'</
													option>';
													}
												}
													$string .=  "<optgroup label='". $main['title'] ."'>". $string1."</optgroup>";
													$string1 = "";
											}
											echo $string;
										?>
	                            	</select>&nbsp;&nbsp;
	                            		No. of practice tests offered:&nbsp;&nbsp;
                            		<input leftposition="50" type="text" style="width: 55px;" name="c_practiceTestsOffered_5" id="c_practiceTestsOffered_5" value="<?php echo $courseExams[4]['practiceTestsOffered']; ?>" tip="no_of_practice_tests_offered" onChange="javascript: $('c_entranceExamMarks_5_error').parentNode.style.display = 'none';">&nbsp;&nbsp;
	                            </div>
	                            <div style="display: none;"><div class="errorMsg" id="c_entranceExamMarks_5_error">show</div></div>
	                        </div>
                        </div>
                    </div>
                    <div style="line-height:9px;clear:both">&nbsp;</div>

                    <div class="row">
                        <div class="row1 Fnt13"><b>Duration<span class="redcolor fontSize_13p">*</span><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'duration_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
			<div id="duration_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('duration_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Get the accurate information on the same.</li>
					</ul>
			</div>	
                        <div class="row2">
                            <input style="width: 119px;" name="duration_val" value="<?=$duration_value?>" id="duration_val" type="text" validate="validateInteger" required="true" maxlength="3" minlength="1" tip="course_duration" caption="Course Duration" leftPosition="90" />
                            <select name="duration_type" style="width: 73px;">
                                <option value="Year" <?php if ($duration_unit == "Year") { echo "selected";} ?> >Year</option>
                                <option value="Months" <?php if ($duration_unit == "Months") { echo "selected"; } ?> >Months</option>
                                <option value="Weeks" <?php if ($duration_unit == "Weeks") { echo "selected"; } ?> >Weeks</option>
                                <option value="Days" <?php if ($duration_unit == "Days") { echo "selected"; } ?> >Days</option>
                                <option value="Hours" <?php if ($duration_unit == "Hours") { echo "selected"; } ?> >Hours</option>
                            </select>
                            <div style="display:none"><div class="errorMsg" id="duration_val_error"></div></div>
                        </div>
                    </div>
                    <div style="line-height:9px;clear:both">&nbsp;</div>
                    <div class="row">
                        <div class="row1 Fnt13"><b>Course fees<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'course_fee_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div><?php global $currencyAttributesArray; $len = count($currencyAttributesArray); ?>
			<div id="course_fee_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('course_fee_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>If this information is available then it should be as accurate as possible.</li>
					<li>It should be only entire ???course??? fees, not for just one year/semester.</li>
					<li>If it is a mandatory accommodation facility, then only add the hostel fees as with course fees (Total of both should be displayed).</li>
					<li>If required add a ???Custom Wiki??? as ???Fees Description??? mentioning every possible bifurcation of fees (accommodation/exam/projects fees etc).</li>
					<li>If the entire course fee is estimated based on the first year fee, a disclaimer would be shown with the text <?php echo FEES_DISCLAIMER_TEXT; ?>.</li>

					</ul>
			</div>
                        <div class="row2">
                            <input name="c_fees_amount" value="<?=$fees_value?>" id="c_fees_amount" type="text" validate="validateInteger"  maxlength="10" minlength="0" style="width:119px;" tip="course_fees" caption="course fees" leftPosition="90" />
                            <select name="c_fees_currency" style="width:70px;position:relative;top:1px" id="c_fees_currency"><?php
                            for($i = 0; $i < $len; $i++) {
                            ?>
                                 <option value="<?=$currencyAttributesArray[$i]['currencyType']?>" <?php if ($fees_unit == $currencyAttributesArray[$i]['currencyType']) { echo "selected";} ?> ><?php echo $currencyAttributesArray[$i]['currencySymbol']; ?></option>

                                <?php
                            }   // End of for($i = 0; $i < $len; $i++).
                                ?>                                                                                                 

<!--                                <option value="INR" <?php if ($fees_unit == "INR") { echo "selected";} ?> >INR</option>
                                <option value="USD" <?php if ($fees_unit == "USD") { echo "selected";} ?> >USD</option>
                                <option value="AUD" <?php if ($fees_unit == "AUD") { echo "selected";} ?> >AUD</option>
                                <option value="CAD" <?php if ($fees_unit == "CAD") { echo "selected";} ?> >CAD</option>
                                <option value="SGD" <?php if ($fees_unit == "SGD") { echo "selected";} ?> >SGD</option>
                                <option value="GBP" <?php if ($fees_unit == "GBP") { echo "selected";} ?> >GBP</option>
                                <option value="NZD" <?php if ($fees_unit == "NZD") { echo "selected";} ?> >NZD</option>-->
                            </select>
							<span title="One year fees disclaimer">Show Disclaimer ?</span><input name="c_fees_disclaimer" type="checkbox" id="c_fees_disclaimer" onchange="this.value = this.checked === true ? 1 : 0; " <?php echo $fees_disclaimer == 1 ? 'checked' : ''; ?> value="<?php echo $fees_disclaimer == 1 ? 1 : ''; ?>"/>
                            <div style="display:none"><div class="errorMsg" id="c_fees_amount_error"></div></div>
                        </div>
                    </div>
                    <div style="line-height:9px;clear:both">&nbsp;</div>
                    <div class="row">
                        <div class="row1"><b>Important Dates</b></div>
                        <div class="row2">
                            <div>
                                <div class="float_L" style="width:100px;margin-right:15px">Form Submission</div>
                                <div class="float_L" style="width:130px;margin-right:15px">Declaration of Results</div>
                                <div class="float_L" style="width:140px">Course Commencement</div>
                                <div style="line-height:1px;clear:both">&nbsp;</div>
                            </div>
                            <div style="height:35px">
                                <div class="float_L" style="width:110px;margin-right:10px">
                                    <input readonly profanity="true" name="date_form_submission" value="<?php if( isset($date_form_submission) && ($date_form_submission !== '0000-00-00 00:00:00') && (stripos($date_form_submission,'1970') === false)) { echo date("d-m-Y",strtotime($date_form_submission)) ; } ?>" type="text" class="inputBorderGray" id="date_form_submission" validate="validateDateCourse"  maxlength="10" minlength="0" tip="date_form_submission" caption="form submission date" leftPosition="300" onClick="cal.select($('date_form_submission'),'dfs','dd-MM-yyyy');" style="width:70px" /> <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="dfs" onClick="cal.select($('date_form_submission'),'dfs','dd-MM-yyyy');" />
                                </div>
                                <div class="float_L" style="width:130px;margin-right:10px">
                                    <input  readonly profanity="true" name="date_result_declare" value="<?php if( isset($date_result_declaration) && ($date_result_declaration !== '0000-00-00 00:00:00') && (stripos($date_result_declaration,'1970') === false)) { echo date("d-m-Y",strtotime($date_result_declaration)) ; } ?>" type="text" class="inputBorderGray" id="date_result_declare" validate="validateDateCourse"  maxlength="10" minlength="0" tip="date_result_declare" caption="result declaration date" leftPosition="250" onClick="cal.select($('date_result_declare'),'drc','dd-MM-yyyy');" style="width:70px" />
                                    <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="drc" onClick="cal.select($('date_result_declare'),'drc','dd-MM-yyyy');" />
                                </div>
                                <div class="float_L" style="width:140px">
                                    <input  readonly profanity="true" name="date_course_commence" value="<?php if( isset($date_course_comencement) && ($date_course_comencement !== '0000-00-00 00:00:00') && (stripos($date_course_comencement,'1970') === false)) { echo date("d-m-Y",strtotime($date_course_comencement)) ; } ?>" type="text" class="inputBorderGray" id="date_course_commence" validate="validateDateCourse"  maxlength="10" minlength="0" tip="date_course_commence" caption="course commence date" leftPosition="100" topposition = "50" onClick="cal.select($('date_course_commence'),'dcc','dd-MM-yyyy');" style="width:70px" />
                                    <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="dcc" onClick="cal.select($('date_course_commence'),'dcc','dd-MM-yyyy');" />
                                </div>
                                <div style="line-height:1px;clear:both">&nbsp;</div>
                            </div>
                            <div style="display:none"><div class="errorMsg" id="date_form_submission_error"></div></div>
                            <div style="display:none"><div class="errorMsg" id="date_result_declare_error"></div></div>
                            <div style="display:none"><div class="errorMsg" id="date_course_commence_error"></div></div>
                            <div style="display:none"><div class="errorMsg" id="common_date_error"></div></div>
                        </div>
                    </div>
                    <div style="line-height:9px;clear:both">&nbsp;</div>
                    <div class="row">
                        <div class="row1"><b>Application Form<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'Application_by_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></b></div>
			<div id="Application_by_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('Application_by_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					<li>Application form for course is a completely different application/product altogether. They basically are for MBA online application. We don???t have to worry about it.</li>
					
					<li>If required in future then Upload- Required application form if available online.</li>
					<li>Upload brochure (PDF/JPEG/MS Word file) ??? Upload institutes brochure if Available.</li>
					</ul>
			</div>
                        <div class="row2">
                            <div>
                                <?php
                                $logo_style = '';
                                if (isset($application_form_url) && ($application_form_url != '') ) {
                                    $logo_style = 'display:none;';
                                    ?>
                                    <?php
                                    if ($form_upload == 'yes') {
                                        preg_match('/[^?]*/', $application_form_url, $matches);
                                        $string = $matches[0];
                                        #split the string by the literal dot in the filename
                                        $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
                                        #get the last dot position
                                        $lastdot = $pattern[count($pattern)-1][1];
                                        #now extract the filename using the basename function
                                        $filename = basename(substr($string, 0, $lastdot-1));

                                        preg_match('/[^?]*/', $application_form_url, $matches);
                                        $string = $matches[0];

                                        $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
                                        if(count($pattern) > 1)
                                        {
                                            $filenamepart = $pattern[count($pattern)-1][0];
                                            preg_match('/[^?]*/', $filenamepart, $matches);

                                        }
                                        echo "<div id='insti_logo_fetched_upload_p'>".$filename . '.' . $matches[0]."</div>";
                                        ?>
                                        <a href="/enterprise/ShowForms/downloadfile/<?php echo base64_encode(htmlentities(urlencode($application_form_url), ENT_QUOTES));?>" id="insti_logo_fetched_upload" >
                                    <?php
                                        echo '[Download]';
                                        echo "</a>";
                                    } elseif ($form_upload == 'no') {
                                        echo "<div id='insti_logo_fetched_upload_p'>" .$application_form_url."</div>";
                                        ?>
                                        <a onclick="javascript:window.open('<?php echo (strpos($application_form_url,'://') > -1) ? html_entity_decode(urldecode($application_form_url)) : 'http://'. html_entity_decode(urldecode($application_form_url)) ; ?>','','resizable=yes,scrollbars=yes,status=yes');" href="javascript:void(0)" id="insti_logo_fetched_upload" >
                                    <?php
                                        echo '[View]';
                                        echo "</a>";
                                    }
                                    ?>
                                    <div id="logo_anchor_upload" style="display:inline;"> <a onclick="removeApplicationDoc('<?php echo $courseId; ?>','upload');" href="javascript:void(0);" > [Remove]</a> </div>
                                    <?php
                                    }
                                ?>
                                
								<div id="course_app_form" style="<?php echo $logo_style; ?>" >
                                    <input id="app_frm_1" name="applicationForm" type="radio" <?php if (isset($form_upload) && ($form_upload == 'yes') ) { echo "checked"; } ?> value="upload"/> <b>Upload</b> &nbsp;
                                    <input name="course_app_form[]" id="course_app_form_1" type="file" tip="course_upload_form" leftPosition="75"/>
                                    <div style="display:none;margin-top:10px;margin-left:85px;"><div class="errorMsg" id="course_app_form_1_error"></div></div>
                                    <div style="line-height:9px">&nbsp;</div>

                                    <input id="app_frm_2" name="applicationForm" type="radio" <?php  if (isset($form_upload) && ($form_upload == 'no')) { echo "checked"; } ?> value="url"/> <b>Specify URL</b> &nbsp;
                                    <input profanity="true" name="course_form_url" id="course_form_url" type="text" value="<?php echo ( (($application_form_url != '') && (isset($form_upload) && ($form_upload == 'no')))?$application_form_url:''); ?>" validate="checkURL"  maxlength="500" tip="course_form_link" caption="course form url" leftPosition="15" class="inputBorderGray" style="width:170px" />
                                    <a title="Click here to clear application form." href="javascript:void(0);" id="clear_selection_application_form"  onClick="clear_selection_application_form();"> Clear selection</a>
                                    <div style="display:none"><div class="errorMsg" id="course_form_url_error"></div></div>
								</div>
								
                            </div>
                        </div>
                    </div> 
                    <div style="line-height:9px;clear:both">&nbsp;</div>
		    <?php if(!empty($course_request_brochure_link)):?> 	
		    <div class="row" style="position:relative;left:279px;bottom:5px;"> 
			<a onclick="removeEbrochure(this);" href="javascript:void(0);" >[Remove brochure and admission year]</a>
			<input type="hidden" value="" id="request_brochure_link_delete" name="request_brochure_link_delete"/>
	 	    </div>	
		    <?php endif;?>	
                    <div class="row" id="BrochurePanel_row">
					<div style="float:left;width:265px;padding-right:10px;line-height:20px"><div class="txt_align_r bld"><span id="collBrochurePanel">Upload Brochure (PDF/Jpeg/MS word file):<br/><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'UploadBrochure_by_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span> </span></div></div>
					<div id="UploadBrochure_by_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('UploadBrochure_by_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					<li>Upload institutes brochure if Available.</li>
					</ul>
					</div>
					<div style="float:left;width:350px">
						<div id="BrochurePanel">
						       <?php if(!empty($course_request_brochure_link)):?>
								<div><a href="<?php echo $course_request_brochure_link;?>" target="blank">View uploaded file</a></div>
								<?php endif;?>
							<div style="position:relative">
								<div><input type="file" name="c_brochure_panel[]" id="c_brochure_panel" leftPosition="75" value="" size="14" /></div>
							</div>
						</div>
						<div><div class="errorMsg" style="display:none" id="c_brochure_panel_error"></div></div>
						<div style="padding-top: 10px;">
						<select name="c_brochure_panel_year" id="c_brochure_panel_year">
							<option value="0">Select admission year</option>
							<?php 
							$current_year = date("Y");
							$prev_year = $current_year-1;
							$next_year = $current_year+1;							
							?>
							<option value="<?php echo $prev_year;?>" <?php if($prev_year == $course_request_brochure_year): ?> selected <?php endif;?>>Previous year</option>
							<option value="<?php echo $current_year;?>" <?php if($current_year == $course_request_brochure_year): ?> selected <?php endif;?>>Current year</option>
							<option value="<?php echo $next_year;?>" <?php if($next_year == $course_request_brochure_year): ?> selected <?php endif;?>>Next year</option>
						</select>
						</div>
						<div><div class="errorMsg" style="display:none" id="c_brochure_panel_year_error">Please select year of admission to upload brochure.</div></div>
					</div>
					<div class="clearFix"></div>
				</div>
                    <div style="line-height:9px;clear:both">&nbsp;</div>
					<?php
						$instiContactId ='';
						foreach($instiContacts as $key){
							$instiContactId = $key['contact_details_id'];

								if (empty($contact_name) && !isset($contact_name)) {        $contact_name = $key['contactInfo']['contact_person'];}
								if (empty($contact_main_phone) && !isset($contact_main_phone)) {
									$contact_main_phone = $key['contactInfo']['contact_main_phone'];
								}
								if (empty($contact_cell) && !isset($contact_cell) ) {
									$contact_cell = $key['contactInfo']['contact_cell'];
								}
								if (empty($contact_email) && !isset($contact_email) ) {
									$contact_email = $key['contactInfo']['contact_email'];
								}

							if($key['status']=='live'){
								break;
							}
						}
						$sameAsInstiFlag = false;
						if($instiContactId == $contact_details_id){
							$sameAsInstiFlag = true;
						}
					?>
					<input type="hidden" name="contact_details_id" value="<?php echo $instiContactId; ?>"/>
					<div style="line-height: 20px;">&nbsp;</div>
					<div class="formHeader"><a class="formHeader" id="Contacts_Details" name="contact" href="javascript:void(0);" onClick="return  replaceInnerHTML(this,'hideContacts_Details','Contact Person Details');" style="text-decoration:none">+ Contact Person Details</a> &nbsp; <span style="color:#666666;font-size:12px">(Please specify if its different from the Institute contact details)</span><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'Contacts_Details_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></div>
					<div id="Contacts_Details_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('Contacts_Details_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					<li>To be filled only if course specific contact details are available.</li>
					</ul>
					</div>	
					<div class="row" style="display:none;" id="hideContacts_Details">
						<div class="line_1"></div>
						<div style="line-height:9px;clear:both">&nbsp;</div>
						<div class="row">
							<div class="row1"><b>Name of person:</b></div>
							<div class="row2">
								<input profanity="true" name="contact_name" value="<?php echo $contact_name; ?>" id="contact_name" type="text" validate="validateStr"  maxlength="100" minlength="5" tip="course_contact_name" caption="Person Name"  style="width:180px" />
								<div style="display:none"><div class="errorMsg" id="contact_name_error"></div></div>
							</div>
						</div>
						<div style="line-height:9px;clear:both">&nbsp;</div>
						<div class="row">
							<div class="row1"><b>Main Phone:</b></div>
							<div class="row2">
								<input profanity="true" name="contact_phone" value="<?php echo $contact_main_phone; ?>" id="contact_phone" type="text" validate="validateext"  maxlength="15" minlength="10" tip="course_contact_phone" caption="phone number"  style="width:180px" />
								<div style="display:none"><div class="errorMsg" id="contact_phone_error"></div></div>
							</div>
						</div>
						<div style="line-height:9px;clear:both">&nbsp;</div>
						<div class="row">
							<div class="row1"><b>Mobile number:</b></div>
							<div class="row2">
								<input name="contact_mobile" value="<?php echo $contact_cell; ?>" id="contact_mobile" type="text" maxlength="<?=$maxMobileFieldLength?>" minlength="<?=$minMobileFieldLength?>" tip="course_contact_mobile" caption="mobile number"  style="width:180px" />
								<div style="display:none"><div class="errorMsg" id="contact_mobile_error"></div></div>
							</div>
						</div>
						<div style="line-height:9px;clear:both">&nbsp;</div>
						<div class="row">
							<div class="row1"><b>Email address:</b></div>
							<div class="row2">
								<input profanity="true" name="contact_email" value="<?php echo $contact_email; ?>" id="contact_email" type="text" validate="validateEmail"  maxlength="125" minlength="0" caption="contact email"  style="width:180px" />
								<div style="display:none"><div class="errorMsg" id="contact_email_error">show</div></div>
							</div>
						</div>
						<div style="line-height:9px;clear:both">&nbsp;</div>
					</div>
					
                    </div>
            </div>
					<?php $this->load->view('listing_forms/courseLocationContactForm'); ?>

					<div class="formHeader" style="margin:0 10px"><a class="formHeader" name="wikicontent" style="text-decoration:none">Additional Details <span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'Additional_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></a></div>
					  <div id="Additional_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('Additional_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Additional Details ??? Options for offerings like ???Morning/ Evening/ Weekend classes???.</li>	
				
					</ul>
			</div>
					<div class="line_1" style="margin:0 10px"></div>
					<div style="line-height:9px;clear:both">&nbsp;</div>
					<div class="row">
						<div class="row1"><b>Morning classes offered:</b></div>
						<div class="row2">
							<span><input leftposition="100" id="morningClasses" name="c_morningClasses[]" type="radio" <?php if ($morningClasses == 'yes'): ?> checked <?php endif; ?> value="yes" tip="morning_classes_offered" caption="Morning Classes Offered" > Yes &nbsp; </span>
							<span><input leftposition="40" id="morningClasses" name="c_morningClasses[]" type="radio" <?php if ($morningClasses == 'no'): ?> checked <?php endif; ?> value="no" tip="morning_classes_offered" caption="Morning Classes Offered" /> No &nbsp;</span>
							<div style="display:none"><div class="errorMsg" id="morningClasses_error"></div></div>
						</div>
					</div>
					<div style="line-height:9px;clear:both">&nbsp;</div>
					<div class="row">
						<div class="row1"><b>Evening classes offered:</b></div>
						<div class="row2">
							<span><input leftposition="100" id="eveningClasses" name="c_eveningClasses[]" type="radio" <?php if ($eveningClasses == 'yes'): ?> checked <?php endif; ?> value="yes" tip="evening_classes_offered" caption="Evening Classes Offered" > Yes &nbsp; </span>
							<span><input leftposition="40" id="eveningClasses" name="c_eveningClasses[]" type="radio" <?php if ($eveningClasses == 'no'): ?> checked <?php endif; ?> value="no" tip="evening_classes_offered" caption="Evening Classes Offered" /> No &nbsp;</span>
							<div style="display:none"><div class="errorMsg" id="eveningClasses_error"></div></div>
						</div>
					</div>
					<div style="line-height:9px;clear:both">&nbsp;</div>
					<div class="row">
						<div class="row1"><b>Weekend classes offered:</b></div>
						<div class="row2">
							<span><input leftposition="100" id="weekendClasses" name="c_weekendClasses[]" type="radio" <?php if ($weekendClasses == 'yes'): ?> checked <?php endif; ?> value="yes" tip="weekend_classes_offered" caption="Evening Classes Offered" > Yes &nbsp; </span>
							<span><input leftposition="40" id="weekendClasses" name="c_weekendClasses[]" type="radio" <?php if ($weekendClasses == 'no'): ?> checked <?php endif; ?> value="no" tip="weekend_classes_offered" caption="Evening Classes Offered" /> No &nbsp;</span>
							<div style="display:none"><div class="errorMsg" id="weekendClasses_error"></div></div>
						</div>
					</div>
				
				<script>replaceInnerHTML($('Contacts_Details'),'hideContacts_Details','Contact Person Details');</script>

				<div class="formHeader"><a class="formHeader" name="wikicontent" style="text-decoration:none">Additional Course details</a></div>
                <div class="spacer5 clearFix"></div>
				<div class="line_1"></div>
				<div class="spacer10 clearFix"></div>
				<!-- wikki start -->
				<?php $this->load->view('listing_forms/WikkiContentDetails',array('type_of_check'=>'course')); ?>
				<!-- wikki end -->
				<div style="margin-left:100px">
					<div style="margin-top:20px"><b>- Create a custom section<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'create_custom_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></b></div>
					<div id="create_custom_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('create_custom_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>If anything extra from the sections above is required, then we can create a ???Custom Session??? .</li>
					<li>Point to Remember ???</br> 
					<p style="text-decoration:underline;">Ordering Rule of Wikis : ???Course Description??? will come first and then the ???Custom Wikis??? if any, otherwise they will appear in the order they are listed on the server page.
					</p>
</li>
					</ul>
					</div>	
					<div class="spacer5 clearFix"></div>
					<div id="wikki_parant_CYO">
						<div id="main_container_0">
							<div><input profanity="true" type="text" caption="Title" onFocus="clearText('wikkicontent_main_0')" validate="validateStr" maxlength="100" minlength="10" name="wikkicontent_main[]" id="wikkicontent_main_0" style="width:450px" value="<?php echo (($userFieldsArr[0]['caption'] == '')?'Enter Title':$userFieldsArr[0]['caption']); ?>"/><span><a onclick="removewikkicontent(0);" href="javascript:void(0);" style="font-size:12px;margin-left:10px;"> Remove</a></span></div>
							<div style="display:none"><div class="errorMsg" id="wikkicontent_main_0_error"></div></div>
							<div class="spacer10 clearFix"></div>
							<div><textarea profanity="true" class='mceEditor' caption="Description" validate="validateStr" maxlength="10000" minlength="0" name=wikkicontent_detail[] id="wikkicontent_detail_0" style="width:500px;height:100px;" class="w62_per" ><?php echo (($userFieldsArr[0]['attributeValue'] == '')?'Enter Description':$userFieldsArr[0]['attributeValue']); ?></textarea></div>
							<div style="display:none"><div class="errorMsg" id="wikkicontent_detail_0_error" ></div></div>
						</div>
					</div>
					<div id="add_multiple_wikki_content"></div>
						<?php
						if(count($userFieldsArr)>1) {
							foreach($userFieldsArr as $key => $val){
								if($key != 0) {
						?>
							<script>
								addwikkicontent(false);
							</script>
						<?php
								}
							}
						}
						?>
					<div><b><a id="addwikkicontent_flag" onclick="addwikkicontent(true);" href="javascript:void(0);" >+ Add more</a></b></div>
				</div>

			
            
            
            
            
            
			<div class="spacer10 clearFix"></div>
			<?php if($usergroup=='cms'){?>
				<div class="formHeader"><a class="formHeader" name="wikicontent" style="text-decoration:none">SEO Specifications
				<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'seo_spec_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>
				</a></div>
				<div id="seo_spec_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('seo_spec_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>We won???t have to touch this section. Once a listing is created, we will send it to SEO team and they will finalize it via this section.</li>
					</ul>
				</div>	 
                <div class="spacer5 clearFix"></div>
				<div class="line_1"></div>
				<div class="spacer10 clearFix"></div>
				<div class="row">
					<div style="float:left;width:275px;line-height:20px"><div class="txt_align_r bld">SEO listing url:&nbsp;</div></div>
					<div style="float:left;width:405px">
						<div>
							<textarea <?php if($flow=='edit'  || $flow=='upgrade'){echo "disabled='true'";}?> caption="seo url" maxlength="500" minlength="0"  class="mceNoEditor" id="listing_seo_url" name="listing_seo_url<?php if($flow=='edit'  || $flow=='upgrade'){echo "_disabled";}?>"><?php echo $seoListingUrl; ?></textarea>
							<?php if($flow=='edit'  || $flow=='upgrade'):?><input type="hidden" name="listing_seo_url" value="<?php echo $seoListingUrl; ?>"/><?php endif?>
						</div>
						<div style="display:none"><div class="errorMsg" id="listing_seo_url_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div class="spacer10 clearFix"></div>
				<div class="row">
					<div style="float:left;width:275px;line-height:20px"><div class="txt_align_r bld">SEO listing title:&nbsp;</div></div>
					<div style="float:left;width:405px">
						<div>
							<textarea caption="seo title" maxlength="500" minlength="0" class="mceNoEditor" id="listing_seo_title" name="listing_seo_title"><?php echo $seoListingTitle; ?></textarea>
							<div style="display:none"><div class="errorMsg" id="listing_seo_title_error"></div></div>
						</div>
						<div class="clearFix"></div>
					</div>
				</div>
				<div class="spacer10 clearFix"></div>
				<div class="row">
					<div style="float:left;width:275px;line-height:20px"><div class="txt_align_r bld">SEO listing description:&nbsp;</div></div>
					<div style="float:left;width:405px">
						<div>
							<textarea caption="seo description" maxlength="500" minlength="0" class="mceNoEditor" id="listing_seo_description" name="listing_seo_description"><?php echo $listingSeoDescription; ?></textarea>
						</div>
						<div style="display:none"><div class="errorMsg" id="listing_seo_description_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div class="spacer10 clearFix"></div>
				<div class="row">
					<div style="float:left;width:275px;line-height:20px"><div class="txt_align_r bld">SEO listing keywords:&nbsp;</div></div>
					<div style="float:left;width:405px">
						<div><textarea caption="seo keywords" maxlength="500" minlength="0" class="mceNoEditor" id="listing_seo_keywords" name="listing_seo_keywords"><?php echo $listingSeoKeywords; ?></textarea></div>
						<div style="display:none"><div class="errorMsg" id="listing_seo_keywords_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div class="spacer10 clearFix"></div>
			<?php } ?>
			<div id="correct_above_error" style="display:none;color:red;"></div>
            <div class="spacer10 clearFix"></div>
            <?php if($clientDetails['usergroup']=='cms'){ ?>
			<div class="line_1"></div>
			 <div class="row">
                        <div class="row1" style="width:200px; padding-right:5px;"><b>Source Type:</b></div>
                        <div class="row2" style="margin-left:200px;">
                                <?php global $sourceList;?>
                                <select name="c_source_type" id="c_source_type" onchange = "checkSourceType();">
                                        <option value="">Select</option>
                                        <?php foreach($sourceList AS $source):?>
                                        <option value="<?php echo $source;?>" <?php if($source == $source_type)echo "selected='selected'"?>><?php echo $source;?></option>
                                        <?php endforeach?>
                                </select>
                                <div style="display:none"><div class="errorMsg" id="c_source_type_error" ></div></div>
                        </div>
                </div>
                <div style="line-height:9px;clear:both">&nbsp;</div>


                <div class="row">
                        <div class="row1" style="width:200px; padding-right:5px;"><b>Source Name:</b></div>
                        <div class="row2" style="margin-left:200px;">
                                <input type="text" profanity="true" name="c_source_name" id="c_source_name" validate="validateStr" maxlength="100" style="width:350px" caption="Source Name" value="<?php echo $source_name; ?>" blurMethod="checkSourceType();"/>
                                <div style="display:none"><div class="errorMsg" id="c_source_name_error"></div></div>
                        </div>
                </div>
                <?php }?>
                <div style="line-height:9px;clear:both">&nbsp;</div>
		
		<!-- mandatory comment box section : starts-->
		<?php $this->load->view('listing_forms/mandatory_comments',array('userid'=>$userid,'listing_id'=>$course_id,'tab'=>'course')); ?>
		<!-- mandatory comment box section : ends-->

            <div class="spacer5 clearFix"></div>
			<div align="center">
				<div>
					<?php 
					if($status != 'deleted'){
						if($flow == 'edit'){
							?>
							<button class="btn-submit19" value="" id="save_preview"  type="button" onclick="javascript:$('previewAction').value =1;$('save_preview').disabled = true;if(validate_course_listing_form() == false){$('save_preview').disabled=false; return false;}" style="width:120px"><div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Save &amp; publish</p></div>
							</button>
							<?php
						}
						?>
						<button class="btn-submit19" value="" id="save_add" type="button" onclick="javascript:$('nextAction').value =1;$('save_add').disabled = true;if(validate_course_listing_form() == false){$('save_add').disabled=false; return false;}" style="width:190px"><div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Save &amp; Add Another Course</p></div>
						</button>
						<button class="btn-submit19" value="" id="save_continue"  type="button" onclick="javascript:$('nextAction').value =2;$('save_continue').disabled = true;if(validate_course_listing_form() == false){$('save_continue').disabled=false; return false;}" style="width:130px"><div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Save &amp; Continue</p></div>
						</button>
						<?php
					}
					else{
						?>
						<p>This course is already deleted. You cannot edit / publish it.</p>
						<?php
					}
					?>
					
					<?php if(count($coursesAlreadyAdded) >= 1) { ?>
					<button class="btn-submit39" value=""  type="button" onClick=" try{ ListingOnBeforeUnload.prompt = true;location.replace('<?php echo $skipActionUrl; ?>');} catch(err) {}"  style="width:146px"><div class="btn-submit39"><p style="padding: 15px 8px 15px 5px;color:#000; font-size:12px" class="btn-submit40">Skip To Media Page</p></div>
					</button>
					<?php } ?>
					<button class="btn-submit39" value=""  type="button" onClick=" try{ ListingOnBeforeUnload.prompt = true;location.replace('/enterprise/Enterprise/index/6');} catch(err) {}"    style="width:72px"><div class="btn-submit39"><p style="padding: 15px 8px 15px 5px;color:#000; font-size:12px" class="btn-submit40">Cancel</p></div>
					</button>
				</div>
				<div class="clear_L"></div>
			</div>
		</div>
	</div>
</div>
</form>
<script>
var course_request_brochure_link = '<?php echo $course_request_brochure_link;?>';
<?php if($clientDetails['usergroup']=='cms'){ ?>
function checkSourceType(){
         if($('save_add_course')){
	 	$('save_add_course').disabled=false;
	 }
         if($('save_preview')){
                $('save_preview').disabled = false;
         }
	 if($('save_continue'))
         {
                 $('save_continue').disabled = false;
         }


	if($('c_source_name')!='' && $('c_source_type').value == 'Website'){
		if(validateURLNew($('c_source_name').value))
		{
			$('save_add').disabled=false;
			if($('save_continue'))
				$('save_continue').disabled=false;
			if($('save_preview'))
			{
				$('save_preview').disabled = false;
			}
			$('c_source_name_error').innerHTML = "";
			$('c_source_name_error').parentNode.style.display ='none';
							
			return true;
		}
		else{	
			$('c_source_name_error').parentNode.style.display ='block';
			$('c_source_name_error').innerHTML = "Please enter valid Website address";
			$('save_add').disabled=true;
			if($('save_continue'))
				$('save_continue').disabled=true;
			if($('save_preview')){
				$('save_preview').disabled = true;
			}
			return false;
		}
	}
	return true;
}
<?php }?>
	function hideInputBox(){
		var boxes = document.getElementsByName('boxes');
		for(var i=0;i<boxes.length;i++){
			boxes[i].style.display = 'none';
		}
	}

	function showInputBox(id){
		$(id).style.display = '';
	}
	function updateFormElem() {
		AIM.submit(document.getElementById('courseListing'), {'onStart' : startCallback, 'onComplete' : performActionForResponse});
	}
	if(document.all) {
		document.body.onload = updateFormElem;
	} else {
		updateFormElem();
	}

    <?php if ($course_level == 'Dual Degree'): ?>
        showelement('dual_degree_2');
        showelement('dual_degree_1');
    <?php elseif ($course_level == 'Diploma'): ?>
        showelement('diploma_1');
    <?php elseif ($course_level == 'Degree'): ?>
        showelement('sigle_degree_1');
    <?php elseif ($course_level == 'Exam Preparation'): ?>
        showelement('examPrepContent');
    <?php endif; ?>
	addOnBlurValidate(document.getElementById('courseListing'));
  	addOnFocusToopTip(document.getElementById('courseListing'));
window.setTimeout('initTMCEEditor()',2000);
function myCustomInitInstance(ed) {
    if (tinymce.isIE || !tinymce.isGecko) {
        tinymce.dom.Event.add(ed.getWin(), 'focus', function(e) {
        try {
                if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                        tinyMCE.activeEditor.setContent('');
                        }
            } catch (ex) {
                // do it later
            }
        });
        tinymce.dom.Event.add(ed.getWin(), 'blur', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
    } else {
        tinymce.dom.Event.add(ed.getDoc(), 'focus', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
        tinymce.dom.Event.add(ed.getDoc(), 'blur', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
    }
}

function showMoreOptions(){
	$('more_exam_options').style.display = '';
	$('show_exam_options').style.display = 'none';
};

function initTMCEEditor(){
tinyMCE.init({ 
	mode : "textareas",
	theme : "advanced",
	browser_spellcheck : true ,
	theme_advanced_resizing : true,
    plugins : "jbimages,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
    theme_advanced_buttons1 : "bold,italic,underline,|,search,replace,|,bullist,numlist,|,undo,redo,|,link,unlink,image|,preview",
    theme_advanced_buttons2 : "jbimages,tablecontrols,|,sub,sup,|,charmap",
    theme_advanced_toolbar_location : "top",
		force_p_newlines : false,
		init_instance_callback: "myCustomInitInstance", 
		force_br_newlines : true,forced_root_block : '',editor_selector : "mceEditor", editor_deselector : "mceNoEditor",  setup : function(ed) {
		ed.onKeyUp.add(function(ed, e) {
            var text_limit = document.getElementById(tinyMCE.activeEditor.id).getAttribute('maxLength');
            var strip = (tinyMCE.activeEditor.getContent()).replace(/(<([^>]+)>)/ig,"");
            var text = "<b>"+strip.length + "</b>??out of <b>"+ text_limit + "</b> characters."
            if (text_limit != null) {
                if (strip.length > text_limit) {
                    document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                    document.getElementById(tinyMCE.activeEditor.id+'_error').innerHTML = "You have used <b>"+ strip.length + "</b> Characters. Please use a maximum of "+ text_limit +" characters.";
                    tinyMCE.execCommand('mceFocus', false, tinyMCE.activeEditor.id);
                    return false;
                } else {
                    document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                    document.getElementById(tinyMCE.activeEditor.id+'_error').innerHTML = text;
                }
            }
            var textBoxContent = trim(tinyMCE.activeEditor.getContent());
            textBoxContent = textBoxContent.replace(/[(\n)\r\t\"\']/g,' ');
            textBoxContent = textBoxContent.replace(/[^\x20-\x7E]/g,'');
            var profaneResponseWikki = isProfaneTinymce(stripHtml(textBoxContent));
            if(profaneResponseWikki !== false) {
                document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                document.getElementById(tinyMCE.activeEditor.id +'_error').innerHTML = 'Please do not use objected words ('+ profaneResponseWikki +') for the Description';
                return false;
            }
		});
		}});
}

  	var noOfwikkicontent = 0;
    <?php
    if(count($userFieldsArr)>1) {
        foreach($userFieldsArr as $key => $val){
            if($key != '0') {
            ?>
            document.getElementById('wikkicontent_main_<?php echo $key; ?>').value = '<?php echo jsspecialchars($val['caption']); ?>';
            document.getElementById('wikkicontent_detail_<?php echo $key; ?>').value = '<?php echo jsspecialchars($val['attributeValue']); ?>';
            <?php
            }
        }
    }
    ?>
function checkURL(value, caption) {
  var urlregex = new RegExp("^(http:\/\/|https:\/\/www.|ftp:\/\/www.|www.){1}(.\+)");
  if(value == '') {
        return  "Please enter the "+ caption +".";
  } else if(!urlregex.test(value)) {
    //alert('heree');
    return "Please enter "+ caption +" in correct format";
  }
  return true;
}
</script>
