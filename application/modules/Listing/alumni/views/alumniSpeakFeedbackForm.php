

<?php 
		$headerComponents = array(
								'css'	=>	array(
											'header',
											'mainStyle',
											'common_new',
											'raised_all',
											'footer'
										),
								'js'	=>	array(
											'header',
											'common',
											'user',
											'ratings',
										),
								'title'	=>	'Shiksha :: Alumni Feedback',
								'metaDescription' => 'Alumni Feedback',
								'metaKeywords'	=>'ALumni Feedback',
							);
                    $this->load->view('common/homepage_simple', $headerComponents); 
?>
        <div style="margin:0 10px;">
            <div align="right" class="bld">
                Institute ID : <span class="OrgangeFont"><?php echo $instituteId; ?></span>
            </div>
            <div class="raised_skyWithBG"> 
                <b class="b2"></b><b class="b3"></b><b class="b4"></b>
                <form method="post" onsubmit="return validateFormForAlumni(this);" id="ratingForm" action="/alumni/AlumniSpeakFeedBack/postFeedback" novalidate>
                <div class="boxcontent_skyWithBG">
                    <div style="margin:0 10px">
                        <div class="lineSpace_5">&nbsp;</div>               
                            <div class="row">
                                <div class="formHeader" style="font-size:18px"><img src="/public/images/askDiscussionSearchIcon.gif" align="absmiddle" />Alumni Speak</div>
                                <div style="font-size:20px;padding:5px 0"><?php echo $instituteName; ?></div>
                                <div>
                                    Dear <b><?php echo $email; ?></b><br />
                                    Since you are an alumnus of <b class="OrgangeFont"><?php echo $instituteName; ?></b>, we are glad to invite you to share your experience at <?php echo $instituteName; ?> with all those prospective students who are considering your alma mater as an institution of choice. Please use the form below to give your feedback:
                                </div>
                            </div>
                            <?php
                                foreach($criterias as $criteria) {
                                    $criteriaId = $criteria['criteria_id'];
                                    $criteriaName = $criteria['criteria_name'];
                                    $criteriaDescription = $criteria['criteria_description'];
                            ?>
                            <div id="criteriaBox">
                                <input type="hidden" id="<?php echo 'criteriaId'. $criteriaId; ?>" name="criteriaRating[]" value="0"/>
                                <input type="hidden" id="<?php echo 'criteriaId'. $criteriaId; ?>" name="criteriaId[]" value="<?php echo $criteriaId; ?>"/>
                                <div style="line-height:16px">&nbsp;</div>
                                <div class="alumniBg bld"><?php echo $criteriaName ?></div>
                                <div style="line-height:10px">&nbsp;</div>
                                <div>
                                    <div class="row float_L">
                                        <div class="row"> 
                                            <div class="row1">
                                                <div style="font-size:9px;line-height:12px">&nbsp;</div>
                                                <div><b>Rating:</b></div>
                                            </div>
                                            <div class="row2">
                                                <div class="normaltxt_11p_blk float_L" onmouseout="selectRating('criteriaId<?php echo $criteriaId; ?>');">
                                                    <?php 
                                                    for($starCount=1; $starCount<=MAX_RATING_SCALE;$starCount++){
                                                        if($starCount==1) {
                                                            $className = 'gold_star_bg';
                                                        } else {
                                                            $className = 'gray_star_bg';
                                                        }
                                                            $className = 'gray_star_bg';
                                                ?>
                                                    <label class="<?php echo $className; ?>" title="<?php echo constant('RATING_STAR_'. $starCount); ?>" onmouseover="performRating(document.getElementById('<?php echo 'criteriaId'.$criteriaId .'_'. $starCount;?>'));" onclick="doRating(document.getElementById('<?php echo 'criteriaId'.$criteriaId .'_'. $starCount;?>'));" style="cursor:pointer">
                                                        <span onclick="doRating(this);"  onmouseover="performRating(this);" class="ratingCount" id="<?php echo 'criteriaId'.$criteriaId .'_'. $starCount;?>">&nbsp;
                                                            <?php // echo $starCount; ?>
                                                        </span>
                                                    </label>
                                            <?php } ?>
                                            </div>
                                        </div>
                                        <div style="line-height:3px;clear:both">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="row1"><b>Description:</b></div>
                                    <div class="row2">
                                        <div><textarea style="width:400px;height:100px" class="grayFont" onfocus="this.value= this.value == this.getAttribute('default') ? '' : this.value; this.className='';" default="<?php echo $criteriaDescription; ?>" blurMethod="checkTextArea(this)" id="criteria_description_<?php echo $criteriaId; ?>" name="criteria_description[]" maxlength="2000" minlength="50" validate="validateStr" caption="Description" onkeyup="textKey(this);"><?php echo $criteriaDescription; ?></textarea></div>
                                        <div style="*margin-left:3px;"><span id="criteria_description_<?php echo $criteriaId; ?>_counter">0</span> out of 2000 characters (Min 50 characters)
                                        </div>
                                        <div style="display:none"><div class="errorMsg" id="criteria_description_<?php echo $criteriaId; ?>_error" style="*margin-left:3px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div style="line-height:9px;clear:both">&nbsp;</div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <div style="display:none" align="center" class="row"><div class="row1">&nbsp;</div><div class="errorMsg row2 bld" id="criteria_error"></div></div>

                    <!--Start_personalDetails-->
                    <div style="line-height:5px">&nbsp;</div>
                    <div class="alumniBg bld">Personal Details</div>
                    <div style="line-height:10px">&nbsp;</div>
                    <div>                    	
                        <div>
                        	<div class="row float_L">
                                    <div class="row">
                                        <div class="row1"><b>Name:</b></div>
                                        <div class="row2">
                                        <input type="text" class="inputBorder" style="width:180px" id="name" name="name" maxlength="50" validate="validateStr" caption="name" required="true" minlength="5" />
                                        <div style="display:none"><div class="errorMsg" id="name_error" style="*margin-left:3px;"></div></div>
                                        </div>
                                    </div>
                                    <div style="line-height:3px;clear:both">&nbsp;</div>
                                    <div class="row">
                                        <div class="row1"><b>Course Completed:</b></div>
                                        <div class="row2">
						<div style="float:left;width:200px;">
								<select onChange="javascript: toggleOtherCourseTxtbox();" class="inputBorder" style="width:180px" id="institute_courses" name="institute_courses" caption="Course of the Institute" required="true" validate="validateStr" minlength="0" maxlength="250">
								    <option value=''>Select</option>	
								<?php
								foreach($instituteCourses as $courseId => $courseName) { ?>
									<option value="<?php echo $courseId; ?>"><?php echo $courseName; ?></option>
								<?php }
								?><option value="-1">Other</option></select>	
						</div>
						<div style="float:left;width:190px;display:none;" id="otherTextBoxContainer">	
<input type="text" style="width:250px" id="course_completed" name="course_completed" maxlength="250" validate="validateStr" caption="Institute's Other Course name" class="grayFont" onfocus="this.value= this.value == this.getAttribute('default') ? '' : this.value; this.className='';" default="Please enter other Course Name" value="Please enter other Course Name" blurMethod="checkTextArea(this)" />
						</div>
						<div style="display:none; float:left;width:100%;"><div class="errorMsg" id="institute_courses_error"></div></div>
						<div style="display:none; float:left;width:100%;"><div class="errorMsg" id="course_completed_error"></div></div>
					</div>
                                                                                
                                    </div>
                                    <div style="line-height:3px;clear:both">&nbsp;</div>
                                    <div class="row">
                                        <div class="row1"><b>Year of Completion:</b></div>
                                        <div class="row2">
                                        <select class="inputBorder" style="width:180px" id="course_comp_year" name="course_comp_year" caption="year of completion" required="true" validate="validateStr" minlength="4" maxlength="4">
                                            <option value=''>Select</option>
                                            <?php
                                                $currentYear = date('Y');
                                                for($year = $currentYear;$year >1960 ; $year--) {
                                            ?>
                                             <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                            <?php
                                               }
                                            ?>
                                        </select>
                                        <div style="display:none"><div class="errorMsg" id="course_comp_year_error"></div></div>
                                        </div>
                                    </div>
                                    <div style="line-height:3px;clear:both">&nbsp;</div>
                                    <div class="row">
                                        <div class="row1"><b>Current Organization:</b></div>
                                        <div class="row2">
                                        <input type="text" class="inputBorder" style="width:180px" id="organisation" name="organisation" maxlength="100" minlength="2" validate="validateStr" caption="current organisation"/>
                                        <div style="display:none"><div class="errorMsg" id="organisation_error"></div></div>
                                        </div>
                                    </div>
                                    <div style="line-height:3px;clear:both">&nbsp;</div> 
                                    <div class="row">
                                        <div class="row1"><b>Current Designation:</b></div>
                                        <div class="row2">
                                        <input type="text" class="inputBorder" style="width:180px" id="designation" name="designation" minlength="2" maxlength="100" validate="validateStr" caption="current designation"/>
                                        <div style="display:none"><div class="errorMsg" id="designation_error"></div></div>
                                        </div>
                                    </div>
                                    <div style="line-height:3px;clear:both">&nbsp;</div>
                                    <div class="row">
                                        <div class="row1"><b>Email:</b></div>
                                        <div class="row2">
                                        <div><?php echo $email; ?></div>
                                        <div style="display:none"><div class="errorMsg"></div></div>
                                        </div>
                                    </div>
                                    <div style="line-height:3px;clear:both">&nbsp;</div>

                                    <div class="row">
					  <div style="display: inline; float:left; width:100%">
                                            <div class="r1">&nbsp;</div>
                                            <div class="r2"><input type="checkbox" value="1" id="legalFlag" name="legalFlag" checked/>&nbsp;I agree to being identified with my review which shall displayed on Shiksha.com</div>
                                            <div style="display:none">
					      <div class="r1">&nbsp;</div>
					      <div id="legalFlag_error" class="errorMsg r2"></div>
                                            </div>
					  </div>
					  <div style="line-height:5px;">&nbsp;</div>
                                    </div>

                                     <div class="row">
						                <div style="display: inline; float:left; width:100%">
                                            <div class="r1">&nbsp;</div>
                                            <div class="r2"><input type="checkbox" value="1" id="showOnShikshaFlag" name="showOnShikshaFlag" />&nbsp;Don't show my name on Shiksha.com</div>
                                            <div style="display:none"><div class="errorMsg" id="showOnShikshaFlag_error"></div></div>
                                        </div>
							            <div class="clear_L"></div>
                                    </div>
                                    <div style="line-height:5px;clear:both">&nbsp;</div>


                                    <div class="row">
					  <div style="display: inline; float:left; width:100%">
                                            <div class="r1">&nbsp;</div>
                                            <div class="r2"><input type="checkbox" value="0" id="cAgree" name="cAgree"/>&nbsp;
					    I agree to the 
					    <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and 
					    <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
					    </div>
                                            <div style="display:none">
					      <div class="r1">&nbsp;</div>
					      <div id="cAgree_error" class="errorMsg r2"></div>
                                            </div>
					  </div>
					  <div style="line-height:5px;">&nbsp;</div>
                                    </div>


					<div class="row">
						<div style="display: inline; float:left; width:100%">
							<div class="r1">&nbsp;</div>
                            <div class="r2">Type in the character you see in the image</div>
                        </div>    
						<div style="display: inline; float:left; width:100%">
							<div class="r1">&nbsp;</div>
				          	<div class="r2">
								<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>" width="100" height="40"  id="eventCaptacha" align="absmiddle" caption = "Security Code"/>
								 &nbsp;
								<input type="text" name="eventSecurityCode" id="eventSecurityCode" maxlength = "5" required = "1" style="width:60px;"/>
				            </div>
							<div class="clear_L"></div>
							<div class="row errorPlace" id="asdcnsdjancjkd">
								<div class="lineSpace_5">&nbsp;</div>
								<div class="r1">&nbsp;</div>
								<div id="eventSecurityCode_error" class="errorMsg r2"></div>
								<div class="clear_L"></div>
							</div>
						</div>
					</div>
                                    <div style="line-height:3px;clear:both">&nbsp;</div>
                            </div>
                        </div>
                        <div style="line-height:1px;clear:both">&nbsp;</div>
                    </div>
                    <!--End_personalDetails-->                      

                        <div class="spacer10 clearFix"></div>
                        <div class="line_1"></div>
                        <div class="spacer10 clearFix"></div>
                        <div align="center"><input type="submit" value="Submit" /></div>
                        <div class="spacer10 clearFix"></div>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $email; ?>" name="email"/>
                <input type="hidden" value="<?php echo $instituteId; ?>" name="instituteId"/>
                <input type="hidden" value="<?php echo $instituteName; ?>" name="instituteName"/>
                <input type="hidden" value="<?php echo $mailerId; ?>" name="mailerId"/>
                <input type="hidden" value="<?php echo $templateId; ?>" name="templateId"/>
                </form>
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
            </div>
            <div class="spacer15 clearFix"></div>
        </div>
		

<?php
$this->load->view('common/footer');
?>
<script>
function toggleOtherCourseTxtbox() {
		var e = document.getElementById("institute_courses");
		value = e.options[e.selectedIndex].value;
		if (value == -1) {
			document.getElementById("otherTextBoxContainer").style.display = 'block';
		} else {
			document.getElementById("otherTextBoxContainer").style.display = 'none';
			if(document.getElementById("course_completed_error").parentNode.style.display == 'inline') {
				document.getElementById("course_completed_error").parentNode.style.display = 'none';
			}
		}
}

function validateYear(value, caption,minLength, maxLength) {
    value = parseInt(value);
    var today = new Date();
    var currentYear = 1900 + parseInt(today.getYear());
    if(value <1960 || value > currentYear) {
        return 'Please ensure that the '+ caption +' should be between 1960 and ' + currentYear;
    }
    return true;
}
function validateFormForAlumni(formObj) {
    var reviewFlag = checkForEmptyReview();
    var validateFieldFlag = validateFields(formObj);

    var validateCaptchaFlag = validateCaptchaForAlumni(formObj);
    var nameValueCheck = document.getElementById('name').value;
    if(nameValueCheck == "") {
        document.getElementById('name_error').parentNode.style.display = '';
        document.getElementById('name_error').innerHTML = '&nbsp; Please enter your name to continue.';
    } else {
        document.getElementById('name_error').parentNode.style.display = 'none';
        document.getElementById('name_error').innerHTML = '';
    }
    var legalFlagCheck = document.getElementById('legalFlag').checked;
    if(legalFlagCheck !== true) {
        document.getElementById('legalFlag_error').parentNode.style.display = '';
        document.getElementById('legalFlag_error').innerHTML = '&nbsp; Please check the above checkbox to continue.';
    } else {
        document.getElementById('legalFlag_error').parentNode.style.display = 'none';
        document.getElementById('legalFlag_error').innerHTML = '';
    }
    var cAgreeCheck = document.getElementById('cAgree').checked;
    if(cAgreeCheck !== true) {
        document.getElementById('cAgree_error').parentNode.style.display = '';
        document.getElementById('cAgree_error').innerHTML = '&nbsp; Please agree to Terms & Conditions.';
    } else {
        document.getElementById('cAgree_error').parentNode.style.display = 'none';
        document.getElementById('cAgree_error').innerHTML = '';
    }
    
    if(document.getElementById("institute_courses").options[document.getElementById("institute_courses").selectedIndex].value == -1) {
		// alert("Len = "+document.getElementById("course_completed").value.length);
		// alert("val = "+document.getElementById("course_completed").default.value);
		if(document.getElementById("course_completed").value.length <= 0 || document.getElementById("course_completed").value == "Please enter other Course Name") {
			document.getElementById("course_completed_error").parentNode.style.display = 'inline';
			document.getElementById("course_completed_error").innerHTML = "Please enter the Other Course Name.";
			validateFieldFlag = false;
		}
    }

    formSubmitFlag = (reviewFlag && validateFieldFlag && legalFlagCheck && cAgreeCheck && nameValueCheck);
    return (formSubmitFlag && validateCaptchaFlag);
}

function validateCaptchaForAlumni(objForm){
    
    if(document.getElementById('eventSecurityCode').value == "") {
        document.getElementById('eventSecurityCode_error').parentNode.style.display = 'inline';
        document.getElementById('eventSecurityCode_error').innerHTML = "Please enter the Security Code as shown in the image.";
        return false;
    }
    var xmlHttp = getXMLHTTPObject();
    xmlHttp.onreadystatechange=function() {
        if(xmlHttp.readyState==4) {         
            var result = eval("eval("+xmlHttp.responseText+")");        
            executeFormActionForCaptcha(result, objForm);
        }
    };

    var eventSecurityCode = document.getElementById('eventSecurityCode').value;    
    eventSecurityCode = eventSecurityCode.toString();
    eventSecurityCode = escapeHTML(eventSecurityCode);

    var url = '/events/Events/validateCaptcha' + '/' + eventSecurityCode;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader("Content-length", 0);
    xmlHttp.setRequestHeader("Connection", "close");
    xmlHttp.send(null);
    return false;
}

function executeFormActionForCaptcha(result, objForm){
    if(result.captchResult != "") {
        document.getElementById('eventSecurityCode_error').parentNode.style.display = 'inline';
        document.getElementById('eventSecurityCode_error').innerHTML = "";
        if(formSubmitFlag) {
            objForm.submit();
            return true;
        } else {
            return false;
        }
    } else { 
        reloadCaptcha("eventCaptacha");
        document.getElementById('eventSecurityCode_error').parentNode.style.display = 'inline';
        document.getElementById('eventSecurityCode_error').innerHTML = "";
        if(result.captchResult == "") {
            document.getElementById('eventSecurityCode_error').innerHTML = "Please enter the Security Code as shown in the image.";
            return false;
        }
    }   
}
function checkTextArea(obj){
    if(trim(obj.value) == '') { 
        obj.value = obj.getAttribute('default');
        obj.className = 'grayFont';
    }
}

function checkForEmptyReview() {
    clearErrorMessage();
    var reviewElements = document.getElementsByTagName('textarea');
    var reviewElementsCount = 0;
    var reviewElement;
    var reviewFlag= false;
    var minCharFlag = false;
    var criteria_id;
    while(reviewElement = reviewElements[reviewElementsCount++]) 
    {
        if(trim(reviewElement.value) != reviewElement.getAttribute('default') && trim(reviewElement.value) !='')
	{
            document.getElementById('criteria_error').innerHTML = '';
            document.getElementById('criteria_error').parentNode.style.display = 'none';
            reviewFlag = true;
	    if(reviewElement.value.length>=50)
		minCharFlag = true;
	    else
		criteria_id = reviewElementsCount;
	}
	else
            reviewElement.value = '';
    }
    if(!reviewFlag) {
        document.getElementById('criteria_error').innerHTML = 'Please review at least one of the sections above!';
        document.getElementById('criteria_error').parentNode.style.display = '';
        document.getElementById('criteria_error').scrollIntoView();
    }
    else if(!minCharFlag) {
        document.getElementById('criteria_description_'+criteria_id+'_error').innerHTML = 'Please enter atleast 50 characters in the review above.';
        document.getElementById('criteria_description_'+criteria_id+'_error').parentNode.style.display = 'inline';
        document.getElementById('criteria_description_'+criteria_id+'_error').scrollIntoView();
    }

    var submitFlag = (reviewFlag && minCharFlag);
    return submitFlag;
}

function clearErrorMessage()
{
    var reviewElementsCount = 1;
    while(reviewElement = document.getElementById('criteria_description_'+reviewElementsCount+'_error')) 
    {
	reviewElementsCount++;
        reviewElement.innerHTML = '';
        reviewElement.parentNode.style.display = '';
     }
}

function trim(str) {
try{
    if(str && typeof(str) == 'string'){
        return str.replace(/^\s*|\s*$/g,"");
    } else {
        return '';
    }
} catch(e) { return str;  } 
}

addOnBlurValidate(document.getElementById('ratingForm'));
</script>
