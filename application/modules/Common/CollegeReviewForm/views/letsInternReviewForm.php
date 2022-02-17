<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
$formData = array();
$formData['title'] = "Share your College experiences with others – Shiksha.com";
$formData['description'] = "Let others know about your college by rating and submitting the reviews. Share your college experiences with others to help them decide on selecting best college and course.";
if(isset($validateuser[0]['cookiestr'])) {
	$cookieStr = $validateuser[0]['cookiestr'];
	$cookieArray = explode('|',$cookieStr);
	$formData['email'] = $cookieArray[0];
	$formData['firstname'] = htmlspecialchars($validateuser[0]["firstname"]);
	$formData['lastname'] = htmlspecialchars($validateuser[0]["lastname"]);
	$formData['mobile'] = htmlspecialchars($validateuser[0]["mobile"]);
}else {
	$formData['email'] = "";
	$formData['firstname'] = "";
	$formData['lastname'] = "";
	$formData['mobile'] = "";
}
$showSteps = 'YES';
$collegeHeadingText = 'UG College Details';
$reviewWrap = '';
$aboutCollegeHeadingText = 'Write about your UG College';
$anonymousPosition = 'top';
$submitButtonPosition = 'top';
$showDescriptionTextAsSubHeading = 'YES';
$showPersonalSection = 'NO';
//$descriptionText = "Describe your college to someone who has never heard of it. You know your college best and know what's important - faculty, placement, infrastructure. Talk about the good, the bad and the missing. You could also write about what's interesting - eating joints, the student crowd, college fests. And anything else that you think prospective students need to know.";
$buttonText  = "Submit";
if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){
		$showSteps = 'NO';
		$collegeHeadingText = 'Your Current / Previous College Details';
		$reviewWrap  = 'review-wrap';
		$aboutCollegeHeadingText = 'Describe Your College';
		$anonymousPosition = 'bottom';
		$showDescriptionTextAsSubHeading = 'NO';
		//$descriptionText = "Describe your college to some one who has never heard of it. The good, bad and the missing (You know your college best. You could write about what's important - faculty, placement, infrastructure. You could write about what's interesting - eating joints, the student crowd, college fests. And anything else that you think prospective students need to know.)";
		$submitButtonPosition = 'bottom';
		$showPersonalSection = 'YES';
		$rateSectionHeading = "Rate Your College On The Following Parameters";
		$landingPageUrl = SHIKSHA_HOME;
		$buttonText  = "Submit Review";
}

		$this->load->view('CollegeReviewForm/reviewFormHeader',$formData);?>
			<div id="popupBasic" class="verification-share-layer" style="display: none; width:800px; position:fixed; z-index:1;margin:auto; padding:2px 25px;">
				<?php $this->load->view('CollegeReviewForm/submitLayer'); ?>


		<script>
            var title = "<?php echo $rateSectionHeading; ?>";
            setReviewRatingTitle(title);
         </script> 

		</div>
        
        <!-- Google Code for College Review Visitors Without Incentive -->
        <!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
        <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 1053765138;
        var google_conversion_label = "Umb8CJad_14Qkty89gM";
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
        </script>
        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
        </script>
        <noscript>
        <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1053765138/?value=1.00&amp;currency_code=INR&amp;label=Umb8CJad_14Qkty89gM&amp;guid=ON&amp;script=0"/>
        </div>
        </noscript>

		<div id="popupBasicBack">	
		</div>
            <div id="header">
            <div id="logo-section" style="padding:0px">
            <center>
            <span>
            <img border="0" title="Shiksha.com" alt="Shiksha.com" src="/public/images/desktopLogo.png" style="margin:0 0 10px 5px;vertical-align: middle;"></span>
            <div style="display:inline-block;position:relative;">
               <span style="font-size: 14px;position:relative;bottom:-14px;">&</span>
                <span style="position:relative;bottom:-14px;"><img border="0" title="Letsintern.com" alt="Letsintern.com" src="/public/images/campusAmbassador/letsintern-logo.jpg" style="margin:0 0 10px 5px;vertical-align: middle;"></span>
                <span style="font-size: 14px;position:relative;bottom:-14px;">initiative</span>
            </div>
            </center>
            </div>
            </div>

                <div id="connect-wrapp" style="padding:0;">
        <form id="reviewForm" action="/CollegeReviewForm/CollegeReviewForm/submitReviewData"  accept-charset="utf-8" method="post" enctype="multipart/form-data"  novalidate="novalidate" name="reviewForm">
		    <input name="isShikshaInst" id="isShikshaInst" value="<?php if(isset($isShikshaInstitute) && $isShikshaInstitute!=''){ echo $isShikshaInstitute;}else{ echo 'NO';}?>" type="hidden" />
		    <input id="qualification_str" value="1" type="hidden" />
            <input value="<?php echo $reviewId;?>" type="hidden" name="reviewId"/>
		    <input id="formName" name="formName" value="<?php if(isset($reviewFormName) && $reviewFormName!=''){ echo $reviewFormName;}else{ echo "reviewForm"; } ?>" type="hidden"/>
		    <input id="userId" value="<?php echo $userId;?>" type="hidden" name="userId"/>
		    <input id="landingPageUrl" value="<?php echo $landingPageUrl;?>" type="hidden"/>
		    <input id="reviewerId" value="<?php echo $reviewerId;?>" type="hidden"  name="reviewerId"/>
		    <?php if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){ ?>
		    <input id="reviewSource" value="utm_source=letsintern&utm_medium=partner&utm_campaign=letsinternreviews" type="hidden"  name="reviewSource"/>
		    <?php } ?>
                        <?php
			if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){
				$this->load->view('CollegeReviewForm/headerSection');
			}else{
				$this->load->view('CA/campusRepOnBoardHeader');
			}
                        ?>

                        <div class="connect-form clear-width <?php  echo $reviewWrap; ?>" <?php if($showSteps=='NO'){ ?>style="margin:0;"<?php } ?>>
			<?php if($showSteps=='YES'){ ?>
                            <div class="form-steps">
                            	<a href="#"><span>1</span>Tell US about yourself</a>
                                <a class="campus-sprite  step-border"></a>
                                <a href="#" class="active"><span>2</span>Review your UG College </a>
                            </div>
		        <?php } ?>
                            <div class="form-details">
                            	<p class="form-title"><?php echo $collegeHeadingText;?></p>
                                <ul>
                                	<li class="clear-width">
                                        <div class="flLt dummy_autosuggest" id="dummy_autosuggest_1">
                                        <label>College Name</label>
					<input type="text"  class="text-width" id="dummy_input"  onclick="showAutosuggest(1);" value="<?php echo htmlspecialchars($instituteName);?>"/>
					<div style="display:none;"><div class="errorMsg" id="institute_error_1" style="*float:left"></div></div>
                                        </div>
                                        <input type="hidden" name="suggested_institutes[]" id="suggested_institutes_1" value="<?php echo htmlspecialchars($instituteIdentifier);?>" /> 			  

                                        <div class="flRt" id="locationDiv">
                                        <label id="locLabel">Location</label>
					<?php if($isShikshaInstitute=='YES'){ ?>
                                        <select class="select-width" onmouseover="showTipOnline('Please click on the institute location from the drop-down menu',this);" onmouseout="hidetip();" id="location_1" name="location[]" onchange="loadCourses(1);" required="true" validate="validateSelect" caption="your Location"><option value="">Select </option>
					<?php foreach($locationList as $key=>$value){ ?>
					<option value="<?php echo $value['location_id'];?>" <?php if($selectedlocationId==$value['location_id']){ echo 'selected';}?> ><?php echo $value['location_name'];?></option>
					<?php } ?>
					</select>
					<?php }else{ ?>
                                        <input type="text"  minlength="1" maxlength="200" class="text-width" onmouseover="showTipOnline('Please enter college location',this);" onmouseout="hidetip();" id="location_1" name="location[]"required="true" validate="validateStr" caption="Location" value="<?php echo htmlspecialchars($locationName);?>"/>
					<?php } ?>
                                        <div style="display:none;" id="loc_main"><div class="errorMsg" id="location_1_error" style="*float:left"></div></div>  
                                        </div>
                                    </li>
                                    
                                    <li class="clear-width">
                                    	<div class="flLt" id="courseDiv">
                                        <label id="courseLabel">Course</label>
					<?php if($isShikshaInstitute=='YES'){ ?>
						<select class="select-width" onchange="displayRating('course_1','<?php echo $rateSectionHeading?>'); displayMotivation('course_1'); checkCourses(1); getCourseCampusURL(this.value,this);" onmouseover="showTipOnline('Please click on the course name from the drop-down menu',this);" onmouseout="hidetip();" name="course[]" id="course_1" required="true" validate="validateSelect" caption="your Course"  ><option value="">Select</option>
						<?php foreach($courseList as $key=>$value){ ?>
						<option value="<?php echo $value['courseId'];?>" <?php if($selectedCourseId==$value['courseId']){ echo 'selected';}?> ><?php echo $value['courseName'];?></option>
						<?php } ?>
						</select>
					<?php }else{ ?>
						<input type="text" minlength="1" maxlength="200" class="text-width" onmouseover="showTipOnline('Please enter course name',this);" onmouseout="hidetip();" name="course[]" id="course_1" required="true" validate="validateStr" caption="Course"  value="<?php echo htmlspecialchars($courseName);?>" >
					<?php } ?>
                                        
                                        <div style="display:none;" id="course_main"><div class="errorMsg" id="course_1_error" style="*float:left"></div></div>
                                        </div>
                                        <?php $years = range(2020,2001);
                                        $yearArr = array_combine($years,$years);
                                        ?>
                                        <div class="flRt">
                                    		<label>Year of Graduation</label>
                                        	<select class="select-width" required="true" validate="validateSelect" caption="Year of Graduation" id="yearOfGraduation" name="yearOfGraduation[]" onmouseover="showTipOnline('Please select your Year of Graduation',this);" onmouseout="hidetip();">
                                            	<option value="">Select</option>
                                                <?php foreach($yearArr as $key=>$value){
                                                ?>
                                                <option value="<?php echo $key;?>" <?php if($yearOfGraduation==$key){echo "selected";} ?>><?php echo $value;?></option>
                                                <?php
                                                } ?>
                                            </select>
                                            <div style="display:none;"><div class="errorMsg" id="yearOfGraduation_error" style="*float:left"></div></div>      
                                        </div>
                                    </li>
                                 </ul>
                                 <div class="camp-review-sec clear-width" id="camp-review-sec">
                                 	<p class="form-title" style="margin-bottom:0;"><?php echo $aboutCollegeHeadingText;?></p>
				<?php if($anonymousPosition =='top'){ ?>
                                    <!--<ul>
                                    	<li class="clear-width">
                                        	<input type="checkbox" class="flLt" name="anonymous" value="YES" id="anonymousFlag"/>
                                            <div class="" style="margin-left:18px;">Post this review as anonymous.(The reviewer name will not be published with the review)  </div>
                                        </li>
                                    </ul>-->
				    <?php } ?>
                                    <!--<p class="review-desc">Describe your college to some one who has never heard of it. The good, bad and the missing (You know your college best. You could write about what's important - faculty, placement, infrastructure. You could write about what's interesting - eating joints, the student crowd, college fests. And anything else that you think prospective students need to know.)</p>-->
					<?php if($showDescriptionTextAsSubHeading=='YES'){ /* ?>
					<p class="review-desc"><?php echo $descriptionText;?></p><?php */ } ?>
                                    <p style="display:block;"><a href="javascript:void(0);" onclick="showSlide();" id="viewSampleReviews" style="display:inline-block;line-height: 100%;background: #fff; cursor: pointer;padding-top: 12px">What to write?</a></p>
                                         <div class="review-slider" style="display:none;" id="review-help-text">
                                        <div style="width:497px; overflow: hidden;">
                                            <ul style="width:3500px; position: relative; left:0px;">
						<li style="float: left; width:497px;">
						  <p class="review-title">What to write?</p>
						  <!-- <p>< ?php echo $descriptionText;?></p> -->
                                    <p>Describe your college to someone who needs help to make a college decision. Give the true picture of your college - talk about the good, the bad and the missing stuff which you think should be known to education seekers.<br/><br/>
                                    You may write about:<br/>
                                    <b>Infrastructure</b> – State of the campus (and neighborhood), classrooms, library, labs, sports and hostel facility etc. available<br/>
                                    <b>Placements</b> – Availability of placement assistance, Percentage of students placed, Average / highest / lowest salary packages offered, Names of companies visited for campus recruitment, Type of jobs offered etc.<br/>
                                    <b>Faculty</b> – Level of accomplishment and industry exposure of college and visiting faculty, Teaching methodology etc.<br/><br/>
                                    You could also include anything else that you think prospective students need to know.
                                    </p>
                                                </li>
                                                <div class="clearFix"></div>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="write-review-sec">
                                    	<textarea id="review-ugcollege" name="reviewDescription" class="write-textarea2" caption="review about your <?php if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){  echo 'College';}else{ echo 'Undergraduate College';} ?>" validate="validateStr" required="true" minlength="250" maxlength="10000"><?php echo $reviewDescription;?></textarea>
                                        <div style="display:none;"><div class="errorMsg" id="review-ugcollege_error" style="*float:left"></div></div>
                                    </div>
                                 </div>
                                 
                 <?php $this->load->view('CollegeReviewForm/reviewRatingForm',array('rateSectionHeading' => $rateSectionHeading)); ?>
                
                <div style="margin-bottom:10px;">
                    <li>
                        <p class="form-title" style="margin-bottom:5px;">Would you recommend this college to others? <span>*</span></p>
                        <input type="radio" class="recommendCollegeFlag" <?php if(isset($recommendCollegeFlag) && $recommendCollegeFlag=='YES'){ echo "checked"; } ?> name="recommendCollegeFlag" value="YES"/> Yes
                        <input type="radio" class="recommendCollegeFlag" <?php if(isset($recommendCollegeFlag) && $recommendCollegeFlag=='NO'){ echo "checked"; } ?> name="recommendCollegeFlag" value="NO"/> No
                    </li>
                </div>                 
                   
                <?php $this->load->view('CollegeReviewForm/motivationForm',array('motivationFactor' => $motivationFactor)); ?>                 
                                 
                                 <?php if($submitButtonPosition == 'top'){ ?>
                                 <a style="height: 42px;" href="javascript:void(0);" class="orange-button continue-btn" onclick="doValidation();removeHelpTextInForm($('reviewForm'));if(validateFields($('reviewForm')) != true){ validateReviewForm($('reviewForm')); validationFailed(); return false;} if( validateReviewForm($('reviewForm')) != true){validationFailed(); return false;}  storeReviewData($('reviewForm')); return false;" id="reviewSubmitButton">Submit <i class="campus-sprite  continue-icon"></i></a>
                                  &nbsp;<span id="waitingDiv" style="display:none"><img src='/public/images/working.gif' border=0 align=""></span>
                                 <div class="clearFix"></div>
				<?php } ?>
                                 </div>
                        </div>
				<?php if($showPersonalSection == 'YES'){ ?>
				<div class="connect-form clear-width" style="margin-top:10px;">
                            	<div class="form-details">
                                    <p class="form-title">Personal Details</p>
                                    <p style="margin-bottom:15px;">Knowing more about you generates confidence in the students about the credibility of the review. Sharing your personal details helps us let you make changes to your college review, if you wish to do so anytime later. </p>
				    <div style="position:relative;">
                                    <ul>
                                       <li class="clear-width">
                                    	<div class="flLt">
                                    		<label>First Name <span>*</span></label>
                                        	<input type="text" class="text-width" value="<?php echo $firstname;?>" id='firstname' name='firstname'  maxlength="50" minlength="2" validate="validateFirstLastName" caption="First Name" required = "true" />
						<div style="display:none;"><div class="errorMsg" id="firstname_error" style="*float:left; width:280px"></div></div>  
                                        </div>
                                        <div class="flRt">
                                        <label>Last Name <span>*</span></label>
                                        	<input type="text" class="text-width" value="<?php echo $lastname;?>" id='lastname' name='lastname'  maxlength="50" minlength="2" validate="validateFirstLastName" caption="Last Name" required = "true" />
						<div style="display:none"><div class="errorMsg" id="lastname_error" style="*float:left; width:280px"></div></div>  
						</div>
						</li>
						<li class="clear-width">
						<div class="flLt">
                                    		<label>Personal Email ID <span>*</span></label>
                                        	<?php if($email!=''):?>
						<input type="text" class="text-width" readonly value="<?php echo htmlspecialchars($email);?>" id = "email" name = "email" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125"  />
						<?php else:?>
						<input type="text" class="text-width" value="<?php echo htmlspecialchars($email);?>" id = "email" name = "email" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" />
						<?php endif;?>
						<div style="display:none;"><div class="errorMsg" id="email_error" style="*float:left"></div></div>
                                        </div>
                                    </li>
                                    <li class="clear-width">
                                    	<div class="flLt">
                                    		<label>LinkedIn Profile URL</label>
                                        	<input type="text" class="text-width" name='linkedInURL' value="<?php echo $linkedInURL;?>" id='linkedInURL' validate="validateSocial" minlength="3"  maxlength="200" caption="LinkedIn URL" />
						<div style="display:none;"><div class="errorMsg" id="linkedInURL_error" style="*margin-left:3px;"></div></div>
                                        </div>
                                    </li>
                                     <li class="clear-width">
                                    	<div class="flLt">
                                    		<label>Facebook Profile URL</label>
                                        	<input type="text" class="text-width"  value="<?php echo $facebookURL;?>" name='facebookURL' id='facebookURL' validate="validateSocial" minlength="3" maxlength="200" caption="Facebook profile URL" />
						<div style="display:none;"><div class="errorMsg" id="facebookURL_error" style="*margin-left:3px;"></div></div>
                                        </div>
                                    </li>
                                     <li class="clear-width">
                                    	<div class="flLt">
                                            <div style="width:25%; margin-right:10px;" class="flLt">
                                        <label>Country Code</label>
                                        <select style="width:100%;" class="select-width" required="true" validate="validateSelect" caption="ISD Code" id="isdCode" name="isdCode" onchange="changeMobileFieldmaxLength(this.value,'mobile');">
                                        
                                        <?php foreach($isdCode as $key=>$value){ ?>
                                            <option value="<?php echo $key; ?>"> <?php echo $value; ?> </option>
                                        <?php } ?>
                                        
                                    </select>
                                <div style="display:none;"><div class="errorMsg" id="isdCode_error" style="*float:left"></div></div>   
                                    </div>
                                
                                    <div style="float:left; width:31%;">
                                    		<label>Mobile Number</label>
                                        	<input type="text" class="text-width" value="<?php echo $mobile;?>" id = "mobile" name = "mobile" validate = "validateMobileInteger" maxlength = "10" minlength = "10" caption = "mobile" style="width:130%"/>
						<div style="display:none;"><div class="errorMsg" id="mobile_error" style="*float:left"></div></div>
                                        </div>
                                    </div>
                                    </li>
                                    <!--<li class="clear-width">
                                    	<div>
                                        	<input type="checkbox" class="flLt" name="anonymous" value="YES" id="anonymousFlag"/>
                                    		<p style="margin-left:5px; padding-top:2px;" class="flLt">Post this review as anonymous.<br />
												(Your name will not be published with the review.)</p>
                                        </div>
                                    </li>-->
				    
                                    </ul>
				     <div class="camp-tooltip personal-tooltip flRt">
                                        <i class="campus-sprite  tooltip-pointer"></i>
                                       <span id="personalinfo_group">
						Please enter atleast one of these: <ul style="margin-top:10px;"><li style="margin-bottom: 3px;margin-left: 20px;list-style: disc;">Your LinkedIn Profile URL</li><li  style="margin-bottom: 3px;margin-left: 20px;list-style: disc;">Your Facebook Profile URL</li><li  style="margin-bottom: 3px;margin-left: 20px;list-style: disc;">Your Mobile Number</li></ul>
					</span> </div>
				</div>
				<?php if($submitButtonPosition == 'bottom'){ ?>
                                 <a style="margin:20px 0 0 155px !important; height:43px;" href="javascript:void(0);" class="orange-button continue-btn" onclick="doValidation();removeHelpTextInForm($('reviewForm'));if(validateFields($('reviewForm')) != true){ validateReviewForm('collegeReviewRating'); validationFailed(); return false;} if( validateReviewForm('collegeReviewRating') != true){validationFailed(); return false;}  storeReviewData($('reviewForm'),false); return false;" id="reviewSubmitButton"><?php echo $buttonText;?> <i class="campus-sprite  continue-icon"></i></a>
                                  &nbsp;<span id="waitingDiv" style="display:none"><img src='/public/images/working.gif' border=0 align=""></span>
					
				<div class="clear-width" style="margin-top:20px;">
					<p><a href="#" onclick="$j('#privacy_content').show(); return false;">Are you concerned about Privacy? </a></p>
					<div id="privacy_content" class="privacy-content" style="display:none;">
						<p>Your name will bring credibility to the review you've just written. We recommend you post it like that. However, we do have an option to hide your name in case you're not comfortable.</p><br />
						<p><input type="checkbox" class="flLt" name="anonymous" value="YES" id="anonymousFlag"/> &nbsp;Post as anonymous</p>
					</div>
				</div>
				  
				<div class="clearFix"></div>
				 <p style="position: relative;margin-top:20px">Reviews will be selected by internal editorial Shiksha team within 30 days. Every selected review will win a Paytm cashback worth Rs. 100. If you don’t receive even after successful selection of your review then you can write to us on shiksha.cafe@gmail.com and we will get back to you as soon as possible.</p>
				<div class="clearFix"></div>
				
				<?php } ?>
                            </div>
                            </div>
		            <?php } ?>
                </form>
                </div>
                
                <div class="clearFix"></div>
        
<?php
	$this->load->view('common/footer');
?>
<script>
var innoExcelScript = "<?php echo INNO_EXCEL_SCRIPT; ?>";
var flagToCheckIfAtleastOnSelect = false;		
</script>
<?php

if(isset($moneyRating) && $moneyRating!=''){
?>
<script>
markStarRating('<?php echo $moneyRating;?>','worthmoney');		
</script>
<?php
}
if(isset($crowdCampusRating) && $crowdCampusRating!=''){
?>
<script>
markStarRating('<?php echo $crowdCampusRating;?>','cclife');		
</script>
<?php
}
if(isset($avgSalaryPlacementRating) && $avgSalaryPlacementRating!=''){
?>
<script>
markStarRating('<?php echo $avgSalaryPlacementRating;?>','avgSalPlace');		
</script>
<?php
}
if(isset($campusFacilitiesRating) && $campusFacilitiesRating!=''){
?>
<script>
markStarRating('<?php echo $campusFacilitiesRating;?>','camFac');		
</script>
<?php
}
if(isset($facultyRating) && $facultyRating!=''){
?>
<script>
markStarRating('<?php echo $facultyRating;?>','faculty');		
</script>
<?php
}
if($motivationFactor!=''){ ?>
<script>
	$j('#motivationFactor').val('<?php echo $motivationFactor;?>');
</script>
<?php
}
/*if(isset($crowdCampusLife) && $crowdCampusLife=='YES'){
?>
<script>flagToCheckIfAtleastOnSelect = true;$j('#crowdCampusLife').attr('checked','true').trigger('click').attr('checked','true');</script>
<?php
}
if(isset($salaryPlacement) && $salaryPlacement=='YES'){
?>
<script>flagToCheckIfAtleastOnSelect = true;$j('#salaryPlacement').attr('checked','true').trigger('click').attr('checked','true');;</script>
<?php
}
if(isset($campusFacilities) && $campusFacilities=='YES'){
?>
<script>flagToCheckIfAtleastOnSelect = true;$j('#campusFacilities').attr('checked','true').trigger('click').attr('checked','true');</script>
<?php
}
if(isset($otherReason) && $otherReason!=''){
?>
<script>flagToCheckIfAtleastOnSelect = true;$j('#otherOption').attr('checked','true').trigger('click').attr('checked','true');</script>
<?php
} */
if($anonymousFlag=='YES'){
?>
<script>
$j('#anonymousFlag').attr('checked','true');
</script>
<?php
}
?>
<script>
		$j('#footer').hide();
    try{
	//	addOnFocusToopTipOnline(document.getElementById('CAProfileForm'));
		//addOnFocusToopTipOnline(document.getElementById('qualificationForm_1'));
	    addOnBlurValidate(document.getElementById('reviewForm'));
	} catch (ex) {
	}
</script>
<style>
    .suggestion-box{color:red;position: absolute;background: #fff;z-index: 99;border:1px solid #ccc;width:280px;border-width: 0px 1px 1px 1px; padding: 0px;}
    .suggestion-box  li{padding: 10px 16px 6px; border-bottom: 1px solid #F7F7F7;margin-bottom: 0px !important}
    .suggestion-box .suggestion-box-active-option {background: #F9F9F9 none repeat scroll 0% 0% !important;color: #000;list-style: outside none none;}
    .suggestion-box li span {display: block;color: #999;font-size: 12px;font-weight: 400;line-height: 20px;}
    .suggestion-box  li .suggestion-box-normal-option {background: #FFF none repeat scroll 0% 0%;}
</style>