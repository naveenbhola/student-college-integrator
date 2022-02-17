<?php
if($entityName == 'generic'){
    $classForTabs = '';
}else {
    $classForTabs = 'tab';
}
?>
<div class="form-details">
                <?php $this->load->view('CollegeReviewForm/collegeReviewHeaderGuidelines',array("pageType" => $pageType));?>

					<p class="sectionalHeading topBorder"></p>
                    <ul>
                        <li class="clear-width">
                            <div class="flLt dummy_autosuggest" id="dummy_autosuggest_1">
                                <label>College Name</label>
                                <p>Enter the college name you're about to review</p>
					            <input required="true" validate="validateReviewFields" caption="College Name" type="text"  class="text-width"  <?php if($instituteName != '') { echo "readonly";} else { echo 'onclick="showAutosuggestReviewForm(1);"  onfocus="showAutosuggestReviewForm(1);"'; }?> id="dummy_input" autocomplete='off'  value="<?php echo htmlspecialchars($instituteName);?>"  minlength="1" maxlength="200" required="true" validate="validateReviewFields" caption="College Name"/>
					            <div style="display:none;">
                                <div class="errorMsg" id="dummy_input_error" style="*float:left"></div>
                                <div class="errorMsg" id="institute_error_1" style="*float:left"></div>
                                </div>
                                <?php if($instituteName == '') { ?>
                                    <div id="search-college-layer1" class="suggestion-box" style="display: none;"></div>
                                <?php } ?>
                            </div>
                            
                            <input type="hidden" name="suggested_institutes[]" id="suggested_institutes_1" value="<?php echo htmlspecialchars($instituteIdentifier);?>" /> 			  

                            <div class="flRt <?=$classForTabs?>" id="locationDiv">
                                <label id="locLabel">Location</label>
                                <p id="locHelpText">Select the city in which your college is located. For eg: Mumbai, Agra, Noida etc</p>
                                <select class="select-width" id="location_1" name="location[]" onchange="loadCourses(1);" required="true" validate="validateSelect" caption="your Location"></select>
                            	<div style="display:none;" id="loc_main"><div class="errorMsg" id="location_1_error" style="*float:left"></div></div>  
                            </div>
                        </li>
                                    
                        <li class="clear-width">
                            <div class="flLt <?=$classForTabs?>" id="courseDiv">
                                <label id="courseLabel">Course</label>
                                <p id="courseHelpText">Select the course along with the specialization</p>
    					       
    						    <select class="select-width" onchange="displayRating('course_1','<?php echo $rateSectionHeading?>'); clearRecommendFlag(); checkCourses(1); getCourseCampusURL(this.value,this);" name="course[]" id="course_1" required="true" validate="validateSelect" caption="your Course"  >    						        
    						    </select>
    					                    
                                <div style="display:none;" id="course_main">
                                    <div class="errorMsg" id="course_1_error" style="*float:left"></div>
                                </div>
                            </div>
                            
                            <?php 
                                $years = range(2025,2013);
                                $yearArr = array_combine($years,$years);
                            ?>
                                
                            <div class="flRt">
                            	<label>Year of Completion</label>
                                   	<p>Select the year when you completed the course.</p>
                                    <p>If you're currently studying, please enter the year when you will complete the course. </p>
                                    <select class="select-width" required="true" validate="validateSelect" caption="Year of Completion" id="yearOfGraduation" name="yearOfGraduation[]">
                                        <option value="">Select</option>
                                        
                                        <?php foreach($yearArr as $key=>$value){ ?>
                                            <option value="<?php echo $key;?>" <?php if($yearOfGraduation==$key){echo "selected";} ?>><?php echo $value;?></option>
                                        <?php } ?>
                                        
                                    </select>
                                    
                                <div style="display:none;">
                                    <div class="errorMsg" id="yearOfGraduation_error" style="*float:left"></div>
                                </div>      
                            </div>
                        </li>
                    </ul>
                    <?php if($pageType ==''){ ?>
                        <p>Increase your chances of getting Rs. 100 paytm cash by giving detailed feedback on each of the below sections.</p>
                    <?php }?>

                     <div class="camp-review-sec clear-width" id="camp-review-sec">
                                    <p class="form-title" style="margin-bottom:0;">Placements <span class="least">(At least 3-4 lines)</span> <span>*</span></p>    
                                    <p style="display:block;">Provide the information about your batch and course (or of your passing out batch)</p>
                                    <div class="flex-row">
                                        <div>
                                            <ol start="1" class="numericList">
                                                <li>What percent of students were placed in your course?</li>
                                                <li>What was the highest, lowest and average salary offered?</li>
                                                <li>Top Recruiting companies for your course?</li>
                                            </ol>
                                        </div>
                                        <div>
                                            <ol start="4" class="numericList">
                                                <li>Top Roles offered in your course?</li>
                                                <li>What was the highest, lowest and the average package offered in your course?</li>
                                                <li>What percent of students got internship from your course and in which companies?</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <!--p style="display:block;">What percent of students got internship from college? What was the highest, lowest and the average stipend offered during internship?<br>Is there a placement cell? How efficient or useful is it?</p-->

                                    <div class="write-review-sec">
                                        <textarea id="placementDescription" name="placementDescription" class="write-textarea2" caption="review about your College Placements" validate="validateReviewFields" required="true" minlength="250" maxlength="2500"><?php echo $placementDescription;?></textarea>
                                        <div style="display:none;"><div class="errorMsg" id="placementDescription_error" style="*float:left"></div></div>
                                        <p class="fieldHintText">(Minimum <label id="placementDescription_count">250</label> characters)</p>
                                    </div>
                                 </div>

                    <div class="camp-review-sec clear-width" id="camp-review-sec">
                        <p class="form-title" style="margin-bottom:0;">Infrastructure for your Course & Hostel<span class="least">(At least 3-4 lines)</span> <span>*</span></p>
                        <div class="flex-row">
                            <div>
                                <ol start="1" class="numericList">
                                    <li>Describe the facilities and infrastructure available for your course/department (e.g. Wi-Fi, Labs, Classrooms, Library)?</li>
                                </ol>
                            </div>
                            <div>
                                <ol start="2" class="numericList">
                                    <li>What is/was the quality of the facilities in your hostel, the quality of food available in the mess and canteen, medical facilities, sports and games?</li>
                                </ol>
                            </div>
                        </div>    
                        <div class="write-review-sec">
                            <textarea id="infraDescription" name="infraDescription" class="write-textarea2" caption="review about your College Infrastructure" validate="validateReviewFields" required="true" minlength="250" maxlength="2500"><?php echo $infraDescription;?></textarea>
                            <div style="display:none;"><div class="errorMsg" id="infraDescription_error" style="*float:left"></div></div>
                            <p class="fieldHintText">(Minimum <label id="infraDescription_count">250</label> characters)</p>
                        </div>
                    </div>

                    <div class="camp-review-sec clear-width" id="camp-review-sec">
                        <p class="form-title" style="margin-bottom:0;">Faculty & Course Curriculum for your course <span class="least">(At least 3-4 lines)</span> <span>*</span></p>    
                        <div>
                            <ol class="numericList">
                                <li>Were the teachers helpful, qualified, and knowledgeable? How was the teaching quality?</li>
                                <li>Is this course curriculum relevant? Does it make the students industry ready?</li>
                            </ol>
                        </div>
                        <div class="write-review-sec">
                            <textarea id="facultyDescription" name="facultyDescription" class="write-textarea2" caption="review about your College Faculty" validate="validateReviewFields" required="true" minlength="250" maxlength="2500"><?php echo $facultyDescription;?></textarea>
                            <div style="display:none;"><div class="errorMsg" id="facultyDescription_error" style="*float:left"></div></div>
                            <p class="fieldHintText">(Minimum <label id="facultyDescription_count">250</label> characters)</p>
                        </div>
                    </div>

                    <div class="camp-review-sec clear-width" id="camp-review-sec">
                    <p class="form-title" style="margin-bottom:0;">Other Details for your course</p>
                    <div>
                        <ol class="numericList">
                            <li>Why did you choose this course? What are the best things about your course? </li>
                            <li>What things you wish would be better/improve about your course?</li>
                            <li>Events, Fest, Campus Crowd, Connectivity, Campus Surroundings, Scholarship, Extracurricular Activities, etc.</li>
                        </ol>
                    </div>
                        <div class="write-review-sec">
                        	<textarea id="review-ugcollege" name="reviewDescription" class="write-textarea2" caption="review about your <?php if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){  echo 'College';}else{ echo 'Undergraduate College';} ?>" validate="validateReviewFields" minlength="0" maxlength="2500"><?php echo $reviewDescription;?></textarea>
                            <div style="display:none;"><div class="errorMsg" id="review-ugcollege_error" style="*float:left"></div></div>
                        </div>
                    </div>

                    <div class="camp-review-sec clear-width" id="camp-review-sec">
                        <p class="form-title" style="margin-bottom:0;">Title of Review <span>*</span></p> 
                        <p style="display:block;">Give a short headline that summarizes your college review. e.g. Absolutely dissatisfied because of faculty, good placements in 2015, really good infrastructure and facilities etc.</p>
                        <div class="write-review-sec">
                        <input id="titleReview" class="text-width" value="<?php echo $reviewTitle; ?>" validate="validateReviewFields" name="titleReview" maxlength="100" minlength="25" caption="Title of the Review" required="true" type="text">
                        <div style="display:none;"><div class="errorMsg" id="titleReview_error" style="*float:left"></div></div>
                        <p class="fieldHintText">(Minimum <label id="titleReview_count">25</label> characters)</p>
                        </div>
					</div>

                <?php $this->load->view('CollegeReviewForm/reviewRatingForm',array('rateSectionHeading' => $rateSectionHeading)); ?>
                
                    <div id="recommend" style="margin-bottom:10px;">
                    <ul>
                        <li>
                            <p class="l-txt form-title" style="margin-bottom:5px;">Would you recommend others to take admission in your college? <span>*</span></p>
                            <input type="radio" class="recommendCollegeFlag" <?php if(isset($recommendCollegeFlag) && $recommendCollegeFlag=='YES'){ echo "checked"; } ?> name="recommendCollegeFlag" value="YES"/> Yes
                            <input type="radio" class="recommendCollegeFlag" <?php if(isset($recommendCollegeFlag) && $recommendCollegeFlag=='NO'){ echo "checked"; } ?> name="recommendCollegeFlag" value="NO"/> No
                            <div style="display:none;"><div class="errorMsg" id="recommend_error" style="*float:left"></div></div>
                        </li>

                        
                        <li class="clear-width  camp-review-sec">
                            <div class="flLt">
                             <label>What was/is the approximate Total Fees charged by your College for this course? <span>*</span></label>
                             <p>1. Please write below the Total Fees which includes Tuition, Hostel, Library, Admission fees, Mess and any other charges (e.g Rs 150000)<br/>
                                2. Please enter numbers only 
                              </p>
                                <input type="text" class="text-width" value="" id="fees" name="fees" maxlength="20" minlength="1" validate="validateFeesReviews" caption="Fees" required="true" placeholder="e.g 150000">
                              <div style="display: none;">
                                    <div class="errorMsg" id="fees_error" style="*float:left; width:280px">You can’t leave this empty.</div>
                                </div>  
                              </div>
                              
                        </li>
                    </ul>
                    </div>                
                                 
                
