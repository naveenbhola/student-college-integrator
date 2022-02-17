<?php
    // user pref & exam taken values
    $showWhenPlanToGo       = $userStartTimePrefWithExamsTaken['showWhenPlanToGo'];
    $showExams              = $userStartTimePrefWithExamsTaken['showExams'];
    $whenPlanToGoValues     = $userStartTimePrefWithExamsTaken['whenPlanToGoValues'];
    $studyAbroadExams       = $userStartTimePrefWithExamsTaken['studyAbroadExams'];
    $studyAbroadExamScores  = $userStartTimePrefWithExamsTaken['studyAbroadExamScores'];
    $examScoreType          = $userStartTimePrefWithExamsTaken['examScoreType'];
    $rangeStep		    = $userStartTimePrefWithExamsTaken['rangeStep'];
    $timeOfStart	    = $userStartTimePrefWithExamsTaken['timeOfStart'];
    $passport	    	    = $userStartTimePrefWithExamsTaken['passport'];
    
    if($showExams === NULL || $showExams === "true" || $showExams === true || $showExams === 1){
	if($passport != ""){
	    $showExams = false;
	}
	else{
	    $showExams = true;
	}
    }
    else{
        $showExams = false;
    }
    if($showWhenPlanToGo === NULL || $showWhenPlanToGo === "true" || $showWhenPlanToGo === true || $showWhenPlanToGo === 1){ 
        $showWhenPlanToGo = true;
    }
    else{
        $showWhenPlanToGo = false;
    }

    //already loggedin userdata
    if($userData != "false"){
	$firstname  = $userData[0]['firstname'];
	$lastname   = $userData[0]['lastname'];
	$mobile     = $userData[0]['mobile'];
	$cookiestr  = $userData[0]['cookiestr'];
	$loggedIn   = "true";
        // no need for form validations
        $required   = 'required=false';
        // no need to show the fields if when to start & education were both filled , show otherwise
        if($showWhenPlanToGo == false && $showExams == false)
        {
            $extraFieldsUnavailable = 1;
            $elemDisplay= 'display:none';
            $disabled = ' disabled="disabled" ';
	    $disabledClass = ' disable-field ';
        }
        else{
            $elemDisplay = '';
            $disabled = ' disabled="disabled" ';
	    $disabledClass = ' disable-field ';
        }
        $globeTop   = 'style="top:30px;"';
    }
    else{
            $loggedIn = "false";
            $required = 'required=true';
            $elemDisplay= '';
            $globeTop   = 'style="top:80px;"';
    }
    // prepare title for the layer
    $layerTitle = "Register to download brochure ";
    // based on source page the title will be different
    switch($sourcePage)
    {
        case 'course'       : $layerTitle .= 'for this course';//.$courseName;
                              $listingType = $sourcePage;
                              $listingTypeId = $courseId;
			      $globeTop   = 'style="top:80px;"';
                              $pageType   = $sourcePage.'_';
                            break;
        case 'university'   : //$layerTitle .= 'for '.$universityName;
                              $listingType = $sourcePage;
                              $listingTypeId = $universityId;
                              $pageType   = $sourcePage.'_';
                            break;
        case 'department'   : //$layerTitle .= 'for courses in '.$departmentName;
                              $listingTypeId = $sourcePage;
                              $pageType   = $sourcePage.'_';
                            break;
        case 'category'     : //$layerTitle .= 'from '.$universityName; ... later
                              $layerTitle .= 'for this course';//.$courseName;
                              $listingType = 'course';// on category page download functionality is similar to that of course page
                              $listingTypeId = $courseId;
                              $globeTop   = 'style="top:80px;"';
                              $pageType   = '';
                            break;
                default     : // works for recommendations also that appear after a certain course's brochure is downloaded
                              $layerTitle .= 'for this course';//.$courseName;
                              $listingType = 'course';
                              $listingTypeId = $courseId;
                              if($sourcePage == 'recommendation')
                              {
                                $globeTop   = 'style="top:80px;"';
                              }
			      break;
            
    }
?>
<script>
    var scoreRangeMap = {};
    <?php foreach($studyAbroadExamScores as $k=>$examScore){ ?>
        scoreRangeMap['<?=$k?>'] = ["<?=($examScore[0])?>","<?=($examScore[1])?>"];
    <?php } ?>
    var scoreType = {};
    <?php foreach($examScoreType as $k=>$scoreType){ ?>
        scoreType['<?=$k?>'] = "<?=($scoreType)?>";
    <?php } ?>
    
    <?php if($sourcePage == 'category') {
            global $examGrades;
        ?>
    	// examgrade mapping for grade based scores
        var examGrades = JSON.parse('<?=(json_encode($examGrades))?>');
    <?php } ?>

</script>
<div id = "downloadBrochureFormContainer" style="display:none;">
    <?php if($loggedIn!="true"){ ?>
    <p class="font-12 flRt" style="position:relative;top:-15px;"><a href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_PAGE', 'brochureForm_<?=$widget?>', 'login'); loadStudyAbroadForm({'isStudyAbroadPage':1},'/registration/Forms/showLoginLayer','loginFormContainer');engageDownloadBrochureWithLogin=1;">Already registered?</a></p>
    <?php } ?>
    <div class="abroad-layer-title">
	<?=($layerTitle)?>
    </div>
    
    <div class="abroad-layer-content clearfix" style="padding-left:0">
	<div class="flLt reg-benefit-col">
            <strong>Benefits of registering</strong>
            <div class="clearwidth">
                <div class="benefit-list clearwidth">
                    <strong><i class="common-sprite benefit-mark"></i>Free download</strong>
                    <ul>
                        <li>Thousands of course brochures</li>
                        <li>Student guides for countries & exams</li>
                    </ul>
                </div>
                <div class="benefit-list clearwidth">
                    <strong><i class="common-sprite benefit-mark"></i>Get personalized help</strong>
                    <ul>
                        <li>To find the right colleges for you</li>
                        <li>By shortlisting and revisiting courses</li>
                    </ul>
                </div>
                <div class="benefit-list clearwidth">
                    <strong><i class="common-sprite benefit-mark"></i>Stay up to date with</strong>
                    <ul>
                        <li>Admission deadlines</li>
                        <li>Latest on visa rules & scholarships</li>
                    </ul>
                </div>
            </div>
        </div><!-- END:: reg-benefit-col -->
	
	<div class="abroad-register-details-2 flRt" style="width:300px; margin:0 45px 0 10px;">
	    <div class="abroad-register-head clearFix">
		<p id="twoStepFormHeaderText" class="font-14 flLt">Please enter your details</p>
	    </div>
	    <form id="form_<?=$widget?>">
		<ul class="customInputs-large login-layer">
		    <li>
			<input name="contact_email_<?=$widget?>" id="contact_email_<?=$widget?>" <?=$disabled?> class="universal-text <?=$disabledClass?>" maxlength="100" default="Email" value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; }else{ echo "Email";} ?>" caption="Email" onclick ="keepCursorOnLeft(this);" onblur = "validateBrochureFormElement(this,'blur');" onfocus = "validateBrochureFormElement(this,'focus',1)" <?=($required)?> type="text" validate="validateEmail" profanity="true">
			<div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="contact_email_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div>
		    </li>
		    <li>
			<div class="flLt name-txtfield">
			    <input name="usr_first_name_<?=$widget?>" id="usr_first_name_<?=$widget?>" <?=$disabled?> class="universal-text <?=$disabledClass?>" minlength="1" maxlength="50" default="First Name" value="<?php echo $firstname?htmlentities($firstname):"First Name";?>" caption="First Name" onblur = "validateBrochureFormElement(this,'blur')" onfocus = "validateBrochureFormElement(this,'focus',1)" <?=($required)?> type="text" validate="validateDisplayName" profanity="true">
			    <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="usr_first_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div>
			</div>
			<div class="flRt name-txtfield" style="margin-left:10px;">
			    <input name="usr_last_name_<?=$widget?>" id="usr_last_name_<?=$widget?>" <?=($lastname == ''||$lastname == 'Last Name'?'':$disabled)?> class="universal-text <?=($lastname == ''||$mobile == 'Last Name'?'':$disabledClass)?>" minlength="1" maxlength="50" default="Last Name" value="<?php echo $lastname?htmlentities($lastname):"Last Name";?>" caption="Last Name" onblur = "validateBrochureFormElement(this,'blur')" onfocus = "validateBrochureFormElement(this,'focus',1)" <?=($required)?> type="text" validate="validateDisplayName" profanity="true">
			    <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="usr_last_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div>
			</div>
			<div class="clearfix"></div>
		    </li>
		    <li>
			<div class="flLt name-txtfield">
			    <input name="mobile_phone_<?=$widget?>" id="mobile_phone_<?=$widget?>" <?=($mobile == ''||$mobile == 'Mobile No.'?'':$disabled)?> class="universal-text <?=($mobile == ''||$mobile == 'Mobile No.'?'':$disabledClass)?>" maxlength="10" minlength="10" default="Mobile No." value="<?php echo $mobile?$mobile:"Mobile No.";?>" caption="Mobile" onblur = "validateBrochureFormElement(this,'blur')" onfocus = "validateBrochureFormElement(this,'focus',1)" <?=($required)?> type="text" validate="validateMobileInteger" profanity="true">
			    <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="mobile_phone_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div>
			</div>
			<div class="flRt name-txtfield">
			    <div class="custom-dropdown">
				<select name="whenPlanToGo_<?=$widget?>" id="whenPlanToGo_<?=$widget?>" <?=($showWhenPlanToGo?'':$disabled)?> class="universal-select <?=($showWhenPlanToGo?'':$disabledClass)?>" style="width:142px" caption="when you plan to start the course" onblur = "validateBrochureFormElement(this,'blur')" <?=($required)?> validate="validateSelect">
				    <option value="">Intake Year</option>
					<?php foreach($whenPlanToGoValues as $key => $value) { ?> 
					    <option value="<?php echo $key; ?>" <?=($value == $timeOfStart?"selected":"")?> ><?php echo $value; ?></option>
					<?php } ?>
	    			</select>
			    </div>
			    <div>
				<div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="whenPlanToGo_<?=$widget?>_error"></div>
			    </div>
			</div>
		    </li>
		    <?php if ($showExams && $passport=="") { ?>
		    <li style="margin-bottom:<?=($listingType=='course'?'10px':'0')?>">
			<div id="examTakenDiv" style="padding:0px;margin:0" class="exam-bg clearfix">
			    <div class="signUp-child-wrap clearfix" style="padding:4px 0 4px 10px">
				    <p class="mb8"> Have you given any study abroad exam?</p>
				<div class="columns">
				    <input id="examTaken_yes_<?=$widget?>" class="examTaken" name="examTaken_<?=$widget?>" value="yes" caption="whether you have given any exam" onclick="toggleExamScoreSection(this,'<?=$widget?>');" <?=($required)?> type="radio" validate = "validateRadioGroup">
				    <label for="examTaken_yes_<?=$widget?>">
					<span class="common-sprite"></span>
					<p><strong>Yes</strong></p>
				    </label>
				</div>
				<div class="columns">
				    <input id="examTaken_no_<?=$widget?>" class="examTaken" name="examTaken_<?=$widget?>" value="no" caption="whether you have given any exam" onclick="toggleExamScoreSection(this,'<?=$widget?>');" type="radio" validate = "validateRadioGroup">
				    <label for="examTaken_no_<?=$widget?>">
					<span class="common-sprite"></span>
					<p><strong>No</strong></p>
				    </label>
				</div>
			    </div>
			</div>
		    </li>
		    
		    <li id="exam_score_section_<?=$widget?>" style="display:none;margin-bottom:0;">
		    <div class="exam-bg clearfix" style=" border-top:0; margin:0">
			<p class="mb8">Select &amp; enter your exam score</p>
			<ul class="customInputs-large">
			    <?php $i=1; ?>
			    <li class="examAbroadBlockRow">
			    <?php foreach($studyAbroadExams as $key=>$studyAbroadExam) { ?>
			    
				<div class="flLt <?=($i%2==0?"":"mR30")?> examBlock-width">
				    <div class="flLt exam-name">
					<input name="exams_<?=$widget?>[]" class="examsAbroad_<?=$widget?>" id="exam_<?=($studyAbroadExam)?>_<?=$widget?>" value="<?=($studyAbroadExam)?>" label="Exams" caption="at least one exam" onclick="toggleAbroadExamScoreField(this,'<?=$widget?>');" type="checkbox">
					<label for="exam_<?=($studyAbroadExam)?>_<?=$widget?>"><span class="common-sprite"></span><?=($studyAbroadExam)?></label>
				    </div>
				    <input id="exam_score_<?=$i?>_<?=$widget?>" exam="<?=($studyAbroadExam)?>" class="universal-text text-width disable-field scoreFields" disabled="disabled" maxlength="4" minscore="<?=($studyAbroadExamScores[$studyAbroadExam][0])?>" maxscore="<?=($studyAbroadExamScores[$studyAbroadExam][1])?>" range="<?=$rangeStep[$studyAbroadExam]?>" caption="Score" default="Score" value="Score" name="<?=$studyAbroadExam?>_score" onfocus="if(this.value == 'Score') { this.value = ''; }" onblur="checkScoreNew(this,'<?=$widget?>'); blurScore('exam_score_<?=$i?>_<?=$widget?>')" type="text">
				    <input id="<?=($studyAbroadExam)?>_scoreType_<?=$widget?>" name="<?=($studyAbroadExam)?>_scoreType" value="marks" type="hidden">
				    <div class="clearfix">
					<div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="exam_score_<?=$i?>_<?=$widget?>_error"></div>
				    </div>
				</div>
			    <?php if($i%2==0){ ?>
			    </li>
			    
				<?php if($i ==4){ ?>    
				<a id="showMoreExams_<?=$widget?>" href="javascript:void(0);" class="flLt" style="display:block;" onclick="showAllExamSections(this);">+ Show More Exams</a>
				<?php } ?>
			    
			    <li class="examAbroadBlockRow" <?=($i>=4?'style="display:none;"':'')?>>
			    <?php } ?>
			    <?php $i++; } ?>
			    </li>
			</ul>
			<div>
			    <div id="examsAbroad_error_<?=$widget?>" class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" ></div>
			</div>
		    </div>
		    </li>
		    
		    
		    <li id = "passport_section_<?=$widget?>" style="display:none;margin-bottom:<?=($listingType=='course'?'10px;':'0')?>;">
			<div class="exam-bg clearfix" style="padding:0px;margin:0; border-top:0 none;">
			    <div class="signUp-child-wrap clearfix">
				<p class="mb8">Do you have a valid passport?</p>
				<div class="columns">
				    <input name="passport_<?=$widget?>" class="passport_<?=$widget?>" id="passport_yes_<?=$widget?>" onclick = "hidePassportErrorDiv('<?=$widget?>');" value="yes" caption="whether you have a vaild passport" type="radio" validate = "validateRadioGroup" required="true">
				    <label for="passport_yes_<?=$widget?>">
					<span class="common-sprite"></span>
					<p><strong>Yes</strong></p>
				    </label>
				</div>
				<div class="columns">
				    <input name="passport_<?=$widget?>" class="passport_<?=$widget?>" id="passport_no_<?=$widget?>" onclick = "hidePassportErrorDiv('<?=$widget?>');" value="no" caption="whether you have a vaild passport" type="radio" validate = "validateRadioGroup" required="true">
				    <label for="passport_no_<?=$widget?>">
					<span class="common-sprite"></span>
					<p><strong>No</strong></p>
				    </label>
				</div>
			    </div>
			    <div>
				<div id="passport_<?=$widget?>_error" class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" ></div>
			    </div>
			</div>
		    </li>
		    <?php } ?>
		    <div>
			<div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="examTaken_<?php echo $widget; ?>_error"></div>
		    </div>
		    <?php
			// show the list of courses for institute pages
			if( $listingType != 'course' && count($courseList)>0 ) 
			{
				$picklistData  = "";
				$picklistData .= "<li style='margin-top:15px;".($loggedIn!="true"?"margin-bottom:0px;":"")."'>";
				$picklistData .= "<div class = 'custom-dropdown'>";
				$picklistData .= '<select class="universal-select" style="padding:5px" caption="course" name="selected_course_'.$widget.'" id="selected_course_'.$widget.'" validate="validateSelect" onblur="validateBrochureFormElement(this,\'blur\')" '.$required.'>';
				$picklistData .= "<option value=''>Please Select Course</option>";
				foreach( $courseList as $course)
					$picklistData .= "<option value='".$course['course_id']."'>".$course['courseTitle']."</option>";
				$picklistData .= "</select>";
				$picklistData .= "</div>";
				$picklistData .= '<div class="errorMsg" style="color:#FF0000;font-size:11px;padding-left: 3px; clear: both; display: none" id="selected_course_'.$widget.'_error"></div>';
				$picklistData .= "</li>";
				echo $picklistData;
			}
		    ?>
		    <?php if($loggedIn!="true"){ ?>
		    <li style="margin-top:15px;">
			Type in the character you see in the picture below
			<div style="margin-top:10px;">
			    <img class="vam flLt" align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>" align="absbottom" width="100" height="34" id = "secureCode_<?=$widget?>"/>
			    <input type="text" alt="secret code" class="register-fields universal-text" style="margin-left:9px;width:120px;"  name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" required = "true" validate = "validateSecurityCode" maxlength = "5" minlength = "5"  caption = "the Security Code as shown in the image"/>
			    <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;padding-left:3px; clear:both;" id="homesecurityCode_<?=$widget?>_error"></div>
			    <div class="clearfix"></div>
			    <div>
				<div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;padding-left:3px; clear:both;" id="homesecurityCode_<?=$widget?>_error"></div>
			    </div>
			</div>
		    </li>
		    <?php } ?>
		    <li>
			<a href="javascript:void(0);" uniqueattr="SA_SESSION_ABROAD_PAGE/brochureDownloadForm_<?=$widget?>" class="big-button button-style" onclick="showProgressLayer();setTimeout(function () {submitBrochureDownloadForm('<?=$widget?>','layer');hideProgressLayer();}, 1000);" style="width:100%; text-align:center; font-size:18px; z-index: 888888;"><i class="common-sprite download-bro-icon"></i>Download Brochure</a>
		    </li>
		</ul>
		<!-- hidden  fields -->
		<input type = "hidden" id = "source_page_<?=$widget?>" name = "source_page_<?=$widget?>" value = "<?=$sourcePage?>"/>
		<input type = "hidden" id = "university_id_<?=$widget?>" name = "university_id_<?=$widget?>" value = "<?=$universityId?>"/>
		<input type = "hidden" id = "registration_source_<?=$widget?>" name = "registration_source_<?=$widget?>" value = "response_abroad_<?=$pageType?><?=$widget?>"/>
		<input type = "hidden" id = "listing_type_<?=$widget?>" name = "listing_type_<?=$widget?>" value = "<?=$listingType?>"/>
		<input type = "hidden" id = "listing_type_id_<?=$widget?>" name = "listing_type_id_<?=$widget?>" value = "<?=$listingTypeId?>"/>
	    </form>
	</div><!-- END : abroad-register-details-2 -->
    </div><!-- END : abroad-layer-content -->
</div><!-- END : downloadBrochureFormContainer -->
<script>
    var isListCourseDataEmpty = <?=(empty($courseList)?1:0) ;?> ;
    /*
     * when user is logged in and both extra fields were filled then...
     * code flow can skip right to response creation / downloading brochure the following variable denotes that
     */
    var extraFieldsUnavailable= <?=($extraFieldsUnavailable==1?$extraFieldsUnavailable:0)?>;
    var engageDownloadBrochureWithLogin = 0;
    isUserLoggedIn = <?=$loggedIn?>;
        
	
    function submitBrochureDownloadForm(widget,submittedFrom)
    {
        var captchaResult;
        if(isListCourseDataEmpty!=1)
        {
            $j("#listing_type_"+widget).val('course');
            $j("#listing_type_id_"+widget).val($j('#selected_course_'+widget).val());
            $j("#listing_type_"+widget).val('course');
        }
        var flags = validateAbroadForm(widget);
		if (flags == false && submittedFrom == 'layer') {
			$j('#overlayContainer').show();
			return ;
		}
        if ($j(".errorMsg:visible").length>0) {
            hideProgressLayer();
            return ;
        }
        if (!isUserLoggedIn) {
            captchaResult = validateResponseCaptcha('homesecurityCode_'+widget,'secCodeIndex_'+widget,'secureCode_'+widget,widget);
            
        }
        else{
            captchaResult = true;
        }
        if(captchaResult ){
            $j("#homesecurityCode_"+widget+"_error").hide();
            // proceed to submission
            registerWithCreateResponseCall(widget);
            //return true;
		}
        else{
            hideProgressLayer();
            $j("#homesecurityCode_"+widget+"_error").html("Please enter the Security Code as shown in the image.");
            $j("#homesecurityCode_"+widget+"_error").show();
            //return false;
        }
    }
    // reset the engageDownloadBrochureWithLogin every time a layer closes
    $j(".close-icon").click(function(){engageDownloadBrochureWithLogin = 0;});
    $j(".close-icon").bind('click.closeTrackGA', { widget: '<?=$widget?>' }, brochureLayerCloseTracking);
    
</script>
