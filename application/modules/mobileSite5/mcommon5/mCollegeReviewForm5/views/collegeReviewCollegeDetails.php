<section class="review-form content-wrap2 clearfix <?php echo ($pageType=='campusRep'?"new-wrap":"")?>">
<div class="review-title" id="review-title" style="color: #000000 !important;">Review your college in 2 steps:</div>
<div class="review-steps <?php echo ($pageType=='campusRep'?"clearfix":"")?>" id="review-steps">
    <div class="review-boxes">
        <div class="r-step-1 active" id="r-step-1">
            <i class="point"><span class="sprite tick-mark"></span></i>
            <p>Step 1.<br>
            Review your college
            </p>
        </div>
    </div>

    <div class="review-boxes" style="width:20px;">
        <i class="sprite step-arrow2"></i>
    </div>
                        
    <div class="review-boxes">
        <div class="r-step-2" id="r-step-2">
            <i class="point"></i>
            <p>Step 2.<br>
            Your personal details
            </p>
        </div>
    </div>
</div>

<div id="collegeReviewInfo" class="college-review-info">
    <div class="clearfix para-list" style="font-size:14px; color:#000">
        <p>Your honest & detailed review on Placements Infrastructure and Facilities will help thousands of students make an informed college decision.
            <?php if($pageType==''){?> You will get Rs. 100 in your Paytm wallet within 30 days if your review is accepted by us.<?php }?></p>
        <!--p>To increase your chances of having your review accepted on Shiksha, make your review HONEST and DETAILED by giving specifics on Placements, Internships, Infrastructure, Curriculum and Faculty.</p-->
        <p class="form-titl"><b style="display:block;margin-bottom:5px;">We will accept your review if:</b>
            1. It is descriptive and contains useful information.<br>
            2. You have not copied text from anywhere else.<br>
            3. Don’t use junk characters, SMS language, slang or abusive 
    words in your review<br>
            4. Your personal & contact details are correct and authentic<br>
        </p>
        <?php if($pageType==''){?>
            <p>We will not pay Rs. 100 Paytm cashback if your review is rejected by our moderation team on grounds of any of the above</p>
        <?php }?>
    </div>
    <ol class="form-item clearfix ol-list reset-styles">
        <li style="position: relative">
            <?php if($pageType=='campusRep'){?>
                <label class="li-label">College Name</span></label>
                <p class="reviwe--lable_txt">Enter the college name you're about to review</p>
                <div>
                    <input style="padding:8px 4px 8px 6px" type="text" name="keyword"  minlength="1" required="true" minlength="1" maxlength="200"  validate="validateStr" caption="College Name"  autocomplete="off" <?php if($instituteName != '') { echo "readonly";} else { echo 'onfocus="this.hasFocus=true;" onblur="this.hasFocus=false;" id="keywordSuggest"'; }?> value="<?php echo htmlspecialchars($instituteName);?>"/>
            <?php }else{?>
            <div>
                <label class="li-label">College Name</label>
                <input style="padding:8px 4px 8px 6px" type="text" name="keyword"  minlength="1" required="true"minlength="1" maxlength="200"  validate="validateStr" caption="College Name"  autocomplete="off" <?php if($instituteName != '') { echo "readonly";} else { echo 'onfocus="this.hasFocus=true;" onblur="this.hasFocus=false;" id="keywordSuggest"'; }?> value="<?php echo htmlspecialchars($instituteName);?>"/>
            <?php }?>
            </div>

            <ul id="suggestions_container"></ul>
            <input type="hidden" id="suggestedInstitutes" name="suggestedInstitutes[]" value="<?php echo htmlspecialchars($instituteIdentifier);?>" />
            <div style="display:none;"><div class="errorMsg" id="keywordSuggest_error" style="*float:left"></div></div>
            <!--div class="clearfix" style="font-size:11px; color:#666">
                <span class="flLt">Enter the college name you're about to review</span>
            </div-->
        </li>
        <li class="initial_hide">
          <label class="li-label">Location</label>
            <div id="location"><select onchange="loadCourses();" id="location_input" name="location[]" required="true" validate="validateSelect" caption="Location"></select>
            </div>
            <div style="display:none;" id="loc_main"><div class="errorMsg" id="location_input_error" style="*float:left"></div></div>
            <div class="clearfix" style="font-size:11px; color:#666">
             <span class="flLt">Select the city in which your college is located. For eg:Mumbai, Agra, Noida etc</span>
            </div>
        </li>
        <li class="initial_hide">
            <label class="li-label">Course</label>
            <div id="course"><select name="course[]" id="course_input" required="true" validate="validateSelect" caption="Course" onchange="$('input[name=recommendCollegeFlag]').attr('checked',false); displayRating('course','<?php echo $rateSectionHeading?>'); "></select></div>
            <div style="display:none;" id="course_main"><div class="errorMsg" id="course_input_error" style="*float:left"></div></div>                        
            <div class="clearfix" style="font-size:11px; color:#666">
                <span class="flLt">Select the course along with the specialization</span>
            </div>
        </li>
        <?php
            $years = range(2025,2013);
            $yearArr = array_combine($years,$years);
        ?>
        <li>
            <label class="li-label">Year of Completion</label>
            <p class="reviwe--lable_txt">Select the year when you completed the course.
                If you're currently studying, please enter the year 
                when you will complete the course. </p>
            <select required="true" validate="validateSelect" caption="Year of Completion" id="yearOfGraduation" name="yearOfGraduation[]">
                <option value="">Year of Completion</option>
                    <?php foreach($yearArr as $key=>$value){?>
                        <option value="<?php echo $key;?>" <?php if($yearOfGraduation==$key){echo "selected";} ?>><?php echo $value;?></option>
                    <?php } ?>    
            </select>
            <div style="display:none;"><div class="errorMsg" id="yearOfGraduation_error" style="*float:left"></div></div>
            <!--div class="clearfix" style="font-size:11px; color:#666">
                <span class="flLt" style="display:block">Select the year when you completed the course.<br/>If you're currently studying, please enter the year when you will complete the course. </span>
            </div-->
        </li>
        <?php if($pageType==''){?>
            <li>
                <div class="clearfix" style="font-size:11px; color:#666">
                    Increase your chances of getting Rs. 100 paytm cash by giving detailed feedback on each of the below sections.
                </div>
            </li>
        <?php }?>
        <li>
            <label class="li-label">Placements <span class="star-r">*</span><span class="ligt--txt">(At least 3-4 times)</span> </label>
            <p class="reviwe--lable_txt">Provide the information about your batch and course (or of your passing out batch) <br/>
                1. What percent of students were placed in your course?  <br/>
                2. What was the highest, lowest and average salary offered? <br/>
                3. Top Recruiting companies for your course?<br/>
                4. Top Roles offered in your course?<br/>
                5. What was the highest, lowest and the average package offered in your course?<br/>
                6. What percent of students got internship from your course and in which companies?
            </p>
            <textarea  class="review-area" id="placementDescription" name="placementDescription" class="write-textarea2" caption="review about your college placements" validate="validateStr" required="true" minlength="250" maxlength="2500"><?php echo $placementDescription;?></textarea>
            <div style="display:none;"><div class="errorMsg" id="placementDescription_error" style="*float:left"></div></div>
            <div class="clearfix" style="font-size:11px; color:#666">
                <span class="flLt fieldHintText">(Minimum <span id="placementDescription_count">250</span> characters)</span><br>
                <!--span class="flLt" style="display:block">How were placements for your batch and your senior batch? What per cent of students were placed? What was the highest, lowest and average salary offered? Which all companies visited the campus? What kind of roles were offered to the students?</span>
                <span class="flLt" style="display:block">What percent of students got internship from college? What was the highest, lowest and the average stipend offered during internship? Is there a placement cell? How efficient or useful is it?</span--->
            </div>
        </li>
        <li>
            <label class="li-label">Infrastructure for your Course & Hostel <span class="star-r">*</span> <span>(At least 3-4 lines)</span></label>
            <p class="reviwe--lable_txt">
                1. Describe the facilities and infrastructure available for your course/department (e.g. Wi-Fi, Labs, Classrooms, Library)? <br />
                2. What is/was the quality of the facilities in your hostel, the quality of food available in the mess and canteen, medical facilities, sports and games?
            </p>
            <textarea  class="review-area" id="infraDescription" name="infraDescription" class="write-textarea2" caption="review about your college infrastructure" validate="validateStr" required="true" minlength="250" maxlength="2500"><?php echo $infraDescription;?></textarea>
            <div style="display:none;"><div class="errorMsg" id="infraDescription_error" style="*float:left"></div></div>
            <div class="clearfix" style="font-size:11px; color:#666">
                <span class="flLt fieldHintText">(Minimum <span id="infraDescription_count">250</span> characters)</span><br>
                    <!--span class="flLt">Describe the facilities (e.g. Wi-Fi, Labs, Classrooms, Library, Medical Facilities, Sports and Games etc.) available in the campus. What is the quality of labs and classrooms? What is the quality of food available in the canteen? How are hostel rooms? Did you face any infrastructure related issues during your tenure at the college?</span-->
            </div>
        </li>
        <li>
            <label class="li-label">Faculty & Course Curriculum for your course<span class="star-r">*</span> <span>(At least 3-4 lines)</span></label>
            <p class="reviwe--lable_txt">
                1. Were the teachers helpful, qualified, and knowledgeable? How was the teaching quality? <br/>
                2. Is this course curriculum relevant? Does it make the students industry ready?
            </p>
            <textarea class="review-area" id="facultyDescription" name="facultyDescription" class="write-textarea2" caption="review about your college faculty" validate="validateStr" required="true" minlength="250" maxlength="2500"><?php echo $facultyDescription;?></textarea>
            <div style="display:none;"><div class="errorMsg" id="facultyDescription_error" style="*float:left"></div></div>
            <div class="clearfix" style="font-size:11px; color:#666">
                <span class="flLt fieldHintText">(Minimum <span id="facultyDescription_count">250</span> characters)</span><br>
                    <!--span class="flLt">Were the teachers helpful, qualified, and knowledgeable? How was the teaching quality? What teaching methods were used? What was the student to faculty ratio? Did you get industry exposure? Kindly provide the details. Was the course curriculum useful?</span-->
            </div>
        </li>
        <li>
            <label class="li-label">Other Details for your course</label>
            <p class="reviwe--lable_txt">
                1. Why did you choose this course? What are the best things about your course? <br/>
                2. What things you wish would be better/improve about your course? <br/>
                3. Events, Fest, Campus Crowd, Connectivity, Campus Surroundings, Scholarship, Extracurricular Activities, etc.
            </p>
            <textarea class="review-area" id="review-ugcollege" name="reviewDescription" class="write-textarea2" caption="review about your college" validate="validateStr" minlength="0" maxlength="2500"><?php echo $reviewDescription;?></textarea>
            <div style="display:none;"><div class="errorMsg" id="review-ugcollege_error" style="*float:left"></div></div>
            <div class="clearfix" style="font-size:11px; color:#666">
                <!--span class="flLt">(E.g. - Events, Fest, Campus Crowd, Connectivity, Campus Surroundings, Scholarship, Extracurricular Activities, etc.)</span-->
            </div>
        </li>
        <li>
            <label class="li-label">Title of Review <span class="star-r">*</span></label>
            <p class="reviwe--lable_txt">
                Give a short headline that summarizes your college review. e.g. Absolutely dissatisfied because of faculty, good placements in 2015, really good infrastructure and facilities etc.
            </p>
            <div><input style="padding:8px 4px 8px 6px" type="text" value="<?php echo $reviewTitle;?>" required="true" id='titleReview' name='titleReview'   minlength="25" maxlength="100" validate="validateStr" caption="Title of the Review" class="reviewTitle"></div>

            <!--- <input style="padding:8px 4px 8px 6px" type="text" name="keyword"  minlength="1" required="true" minlength="10" maxlength="100" id="titleReview" name="titleReview" mahak="yes" value="<?php //echo $reviewTitle; ?>" validate="validateStr" caption="Title of the Review" autocomplete="off"/> -->
            <div style="display:none;"><div class="errorMsg" id="titleReview_error" style="*float:left"></div></div>
            <div class="clearfix" style="font-size:11px; color:#666">
            <span class="flLt fieldHintText">(Minimum <span id="titleReview_count">25</span> characters)</span><!--br>
                <span class="flLt">Give a short headline that summarizes your college review. e.g. Absolutely dissatisfied because of faculty, good placements in 2015, really good infrastructure and facilities etc.</span-->
            </div>
        </li>
    </ol>
    <?php $this->load->view('ratingParameters'); ?>
    <div class="recommend-row" data-enhance="false">
        <div class="li-label"> <strong class="headingText">Would you recommend others to take admission in your college? </strong><span class="star-r">*</span></div>
        <label><input type="radio" class="recommendCollegeFlag" <?php if((isset($recommendCollegeFlag) && $recommendCollegeFlag=='YES')){ echo "checked"; } ?> name="recommendCollegeFlag" value="YES"/> Yes</label> 
        <label><input type="radio" class="recommendCollegeFlag" <?php if(isset($recommendCollegeFlag) && $recommendCollegeFlag=='NO'){ echo "checked"; } ?> name="recommendCollegeFlag" value="NO" /> No</label>
    </div>
    <div class="recommend-row">
    <ol class="form-item">
        <li class="clear-width  camp-review-sec">
            <div class="flLt">
                <div class="li-label"> 
                    <strong class="headingText">What was/is the approximate Total Fees charged by your College for this course? </strong><span class="star-r">*</span>
                </div>
                <p class="reviwe--lable_txt clearfix">
                    <span class="flLt"> 1. Please write below the Total Fees which includes Tuition, Hostel, Library, Admission fees, Mess and any other charges (e.g Rs 150000)</span>
                    <br/>
                    <span class="flLt" style="margin-bottom: 10px;">2. Please enter numbers only</span>
                </p>
                <div class="ui-input-text">
                    <input type="text" class="reviewTitle" value="" id="fees" name="fees" maxlength="20" minlength="1" validate="validateFeesReviews" caption="Fees" required="true" placeholder="e.g 150000">
                </div>
                <div style="display: none;">
                    <div class="errorMsg" id="fees_error" style="*float:left; width:280px">You can’t leave this empty.</div>
                </div>
            </div>

        </li>
    </ol>
    </div>
    <?php if($pageType=='campusRep'){?>
        <?php if($reviewId>0 && $reviewerId == 0) { ?>
            <input data-enhance="false" type="button" class="button yellow" value="Submit Review" style="width:100%; margin-top:20px" onclick="if(validateFields(document.getElementById('reviewForm'))!=true){ checkFirstStepValidation();return false;}else{if(checkFirstStepValidation()!=true){ return false;}else{storeReviewData(false);return false;}}">
        <?php } else { ?>
            <input data-enhance="false" type="button" class="button yellow" value="Save & Continue" style="width:100%; margin-top:20px" onclick="if(validateFields(document.getElementById('reviewForm'))!=true){ checkFirstStepValidation();return false;}else{if(checkFirstStepValidation()!=true){ return false;}else{saveContinue(this);}}">
        <?php } ?>
    <?php }else{?>
        <input data-enhance="false" type="button" class="button yellow" value="Save & Continue" style="width:100%; margin-top:20px" onclick="uncheckRadioButton();if(validateFields(document.getElementById('reviewForm'))!=true){ checkFirstStepValidation();return false;}else{if(checkFirstStepValidation()!=true){ return false;}else{saveContinue(this);}}">
    <?php }?>
</div>
