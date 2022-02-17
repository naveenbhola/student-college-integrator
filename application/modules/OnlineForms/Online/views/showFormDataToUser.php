<?php
				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				   $headerComponents = array(
				      'css'	=>	array('online-styles','header','raised_all','mainStyle'),
				      'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','prototype','CalendarPopup','imageUpload'),
				      'title'	=>	'',
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>'',	
				      'metaKeywords'	=>'',
				      'product'	=>'online',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false
				   );

   $this->load->view('common/header', $headerComponents);

?>


<div id="appsFormWrapper">
    <!--Starts: breadcrumb-->
    <?php $this->load->view('Online/showBreadCrumbs'); ?>
    <!--Ends: breadcrumb-->

    <div id="appsFormInnerWrapper">

    <!--Starts: Institute Header -->
    <?php $this->load->view('Online/instituteHeader'); ?>
    <!--Ends: Institute Header-->

    <!--Contents Starts here-->
	<div id="contentWrapper">
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">

            <div class="reviewTitleBox">
                <strong>Personal Details:</strong>
                <a href="#" title="Edit">Edit</a>
            </div>
        
            <div class="reviewLeftCol">
                <div class="formGreyBox">
                    <ul>
                        <li>
                            <div class="personalInfoCol">
                                <label>First Name:</label>
                                <span><?php echo $firstName;?></span>
                            </div>
                            
			    <?php if(isset($middleName) && $middleName!=''){ ?>
                            <div class="personalInfoCol">
                                <label>Middle Name:</label>
                                <span><?php echo $middleName;?></span>
                            </div>
			    <?php } ?>
                            
			    <?php if(isset($lastName) && $lastName!=''){ ?>
                            <div class="personalInfoCol">
                                <label>Last Name:</label>
                                <span><?php echo $lastName;?></span>
                            </div>
			    <?php } ?>
                            <div class="clearFix"></div>
                        </li>
                    </ul>

                </div>
                
                <div class="spacer20 clearFix"></div>
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Gender:</label>
                        <span><?php echo $gender;?></span>
                    </li>

                    <li>
                        <label>Email Address:</label>
                        <span><?php echo $email;?></span>
                    </li>

                    <li>
                        <label>Course Applied For:</label>
                        <span><?php echo $courseName;?></span>
                    </li>

                    <li>
                        <label>Application Category:</label>
                        <span><?php echo $applicationCategory;?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label> Date of birth:</label>
                        <span><?php echo $dateOfBirth; ?></span>
                    </li>

		    <?php if(isset($altEmail) && $altEmail!=''){ ?>
                    <li>
                        <label>Alternate Email:</label>
                        <span><?php echo $altEmail;?></span>
                    </li>
		    <?php } ?>

                    <li>
                        <label>Marital Status:</label>
                        <span><?php echo $maritalStatus;?></span>
                    </li>

                    <li>
                        <label>Nationality:</label>
                        <span><?php echo $nationality;?></span>
                    </li>

		    <?php if(isset($religion) && $religion!=''){ ?>
                    <li>
                        <label>Religion:</label>
                        <span><?php echo $religion;?></span>
                    </li>
		    <?php } ?>
                </ul>
            </div>

            <div class="profilePic">
                <img src="<?php echo getMediumImage($profileImage);?>" alt="Profile Pic" />
            </div>
        </div>
        <!--Personal Info Ends here-->
        
        <!--Contact Info Starts here-->
        <div class="contactInfoSection">
            <div class="reviewTitleBox">
                <strong>Contact Information:</strong>
                <a href="#">Edit</a>

            </div>
        	<h3>Permanent Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>House Number:</label>
                        <span>112</span>

                    </li>
                    <li>
                        <label>Area/Locality:</label>
                        <span>Shankar Gali</span>
                    </li>
                    <li>
                        <label>Country:</label>

                        <span>India</span>
                    </li>
                    <li>
                        <label>City:</label>
                        <span>Delhi</span>
                    </li>
                </ul>

                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Street Name:</label>
                        <span>Shankar Gali</span>
                    </li>
                    <li>
                        <label>State:</label>

                        <span>Delhi</span>
                    </li>
                    <li>
                        <label>Pincode:</label>
                        <span>110005</span>
                    </li>
                    <li>

                        <label>Landline:</label>
                        <span>011-25763779</span>
                    </li>
                    <li>
                        <label>Mobile Number:</label>
                        <span>09911123456</span>
                    </li>

                </ul>
            </div>
            
            <div class="spacer5 clearFix"></div>
            <h3>Correspondence Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>House Number:</label>

                        <span>112</span>
                    </li>
                    <li>
                        <label>Area/Locality:</label>
                        <span>Shankar Gali</span>
                    </li>
                    <li>

                        <label>Country:</label>
                        <span>India</span>
                    </li>
                    <li>
                        <label>City:</label>
                        <span>Delhi</span>
                    </li>

                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Street Name:</label>
                        <span>Shankar Gali</span>
                    </li>
                    <li>
                        <label>State:</label>

                        <span>Delhi</span>
                    </li>
                    <li>
                        <label>Pincode:</label>
                        <span>110005</span>
                    </li>
                    <li>

                        <label>Landline:</label>
                        <span>011-25763779</span>
                    </li>
                    <li>
                        <label>Mobile Number:</label>
                        <span>09911123456</span>
                    </li>

                </ul>
            </div>
            
        </div>
        <!--Contact Info Ends here-->
        
        <!--Family Info Starts here-->
    	<div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Family Information:</strong>

                <a href="#" title="Edit">Edit</a>
            </div>
        
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Father's Name:</label>
                        <span>Amar Uppal</span>

                    </li>
                    <li>
                        <label>Occupation:</label>
                        <span>Service</span>
                    </li>
                    <li>
                        <label>Designation:</label>

                        <span>Bank Manager</span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Mother's Name:</label>
                        <span>Shalini Uppal</span>

                    </li>
                    <li>
                        <label>Occupation:</label>
                        <span>Housewife</span>
                    </li>
                    <li>
                        <label>Designation:</label>

                        <span>-</span>
                    </li>
                </ul>
            </div>
        </div>
        <!--Family Info Ends here-->
        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">

            <div class="reviewTitleBox">
                <strong>Education Information:</strong>
                <a href="#" title="Edit">Edit</a>
            </div>
        	<h3>Education History:</h3>
            	<div class="educationBlock">
                	<div class="educationTitle educationTitleBg">

                    	<p class="educationCol educationTitleColFirst">Course name</p>
                        <p class="educationCol">School/ Institute</p>
                        <p class="educationCol">Board/ University</p>
                        <p class="educationYearCol">Year</p>
                        <p class="educationSmallCol">Percentage/ Grade</p>
                    </div>

                   
                <ul>
                	<li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 10<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst">Commerce</span>
                            <span class="educationCol">Amity Internation School</span>
                            <span class="educationCol">CBSE</span>

                            <span class="educationYearCol">1999</span>
                            <span class="educationSmallCol">90%</span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 12<sup>th</sup></strong></label>

                            <span class="educationCol educationColFirst">Commerce</span>
                            <span class="educationCol">Amity Internation School</span>
                            <span class="educationCol">CBSE</span>
                            <span class="educationYearCol">1999</span>
                            <span class="educationSmallCol">90%</span>
						</div>

                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong>Graduation</strong></label>
                            <span class="educationCol educationColFirst">Commerce</span>
                            <span class="educationCol">Amity Internation School</span>
                            <span class="educationCol">CBSE</span>

                            <span class="educationYearCol">1999</span>
                            <span class="educationSmallCol">90%</span>
						</div>
                    </li>
                </ul>
                <div class="clearFix"></div>
             </div>
             <div class="spacer25 clearFix"></div>

             <h3>Qualifying Examination:</h3>
             <div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
                    	<p class="educationCol widthAuto">CAT Score Details</p>
                    </div>
                   
                <ul class="qualifyingDetails">
                	<li>
                        <div class="formColumns">

                            <label><strong>Date of examination:</strong></label>
                            <span>28-11-11</span>
						</div>
                        
                        <div class="formColumns">
                            <label><strong>Score:</strong></label>
                            <span>1000</span>
						</div>

                    </li>
                    
                    <li>
                        <div class="formColumns">
                            <label><strong>Roll Number:</strong></label>
                            <span>2345678900</span>
						</div>
                        
                        <div class="formColumns">
                            <label><strong>Percentile:</strong></label>

                            <span>70%</span>
						</div>
                    </li>
                </ul>
                <div class="clearFix"></div>
             </div>
        </div>
        <!--Education Info Ends here-->

        
        <!--Work Exp Info Starts here-->
    	<div class="workInfoSection">

            <div class="reviewTitleBox">
                <strong>Work Experience:</strong>
                <a href="#" title="Edit">Edit</a>
            </div>
        
            <div class="widthAuto">

                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Company Name:</label>
                            <span>ICICI Bank</span>
						</div>
                        	
                    	<div class="formColumns">
                    	<label>Designation:</label>

                        <span>Manager</span>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <span>Noida</span>
                    </div> 
                    
                    <div class="formColumns">

						<label class="timePeriodLabel">Time Period:</label>
                        <input type="checkbox" checked="checked" /> I currently work here<br />
                        <div class="workExpDetails">
                        	<span>From: December 2006</span>
                            <span class="mL14">Till: December 2011</span>
                        </div>

					</div>
                 </li>
              </ul>
          </div>

     </div>
     <!--Work Exp Info Ends here-->
     
     <div class="spacer20 clearFix"></div>
     
     <div class="rolesResponsiblity">

     	<h3>Roles &amp; Responsibilities:</h3>
        <div id="responsibilityList">
            <ul>
                <li>1) To ensure the punctuality and regular attendance of the staff.</li>
                <li>2) To appraise the performance of the Branch staff in a timely and fair manner and ensure safekeeping of the filled Appraisal forms</li>
                <li>3) To ensure proper housekeeping, safety and security of the FPA India properties.</li>
                <li>4) To keep the custody of the duplicate keys.</li>
            </ul>
        </div>
    </div>
     <div class="spacer15 clearFix"></div>
     <div class="buttonWrapper">
     	<div class="buttonsAligner2">
                <input type="button" class="confirmApplyButton" value="Confirm &amp; Apply" title="Confirm &amp; Apply" />

            </div>
     </div>
     
    </div>
    <!--Contents Ends here-->
	
	<div class="clearFix"></div>
   </div>
</div>

<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 
