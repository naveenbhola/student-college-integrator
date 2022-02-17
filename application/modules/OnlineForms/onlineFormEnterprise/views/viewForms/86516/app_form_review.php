<?php $data = $onlineFormEnterpriseInfo['instituteInfo']['instituteInfo'][0][0];?>
<?php $userInfo = $onlineFormEnterpriseInfo['profile_data'];?>
<input type="hidden" name="instituteId" id="instituteId" value="<?php echo $data['institute_id'];?>">
<?php $info = $onlineFormEnterpriseInfo['instituteInfo']['instituteDetails'][0][0];?>
<?php $formId = isset($_REQUEST['formId'])?$_REQUEST['formId']:''?>
<?php $userId = isset($_REQUEST['userId'])?$_REQUEST['userId']:''?>
<div id="gdpiDateNew" style="display:none;"><?php echo date("Y-m-d",strtotime($info['GDPIDate']));?></div>
<div id="gdpiPlaceNew" style="display:none;"><?php echo $info['GDPILocation'];?></div>
<div style="display:none;" id="requestDocumentContent"><?php echo $info['documentsRequired'];?></div>
<div style="display:none;" id="requestPhotographContent"><?php echo $info['imageSpecifications'];?></div>
<div id="UserFormId" style="display:none;"><?php echo $formId .'-'.$userId;?></div>
<div id="singleUserFormId" style="display:none;"></div>
<?php $this->load>view('Online/FormTemplate/course120633'); ?>
<div id="appsFormWrapper">
	<!--Starts: breadcrumb-->
    <div id="breadcrumb">
    	<ul>
        	<li><a href="#" title="Application Forms">Application Forms</a></li>
            <li class="last"><?php echo $data['institute_name'];?></li>
        </ul>
    </div>
    <!--Ends: breadcrumb-->
    <div id="appsFormInnerWrapper">
    <div id="appsFormHeader">
        <div class="appsFormHeaderChild">
        <div class="appsLogo"><a href="#"><img src="<?php echo SHIKSHA_HOME?>/public/images/onlineforms/amity-logo.gif" alt="Amity" /></a></div>
        <div class="appsDetails">
            <h3> <?php echo $data['institute_name'];?> Application Form-2011</h3>
            <div class="formNumb">
                <div class="formNumbRt">
                    <div class="formNumbMid">
                        Form No.:<br /><span>7682908</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!--Instructions Starts here-->
        <div id="instructions">
            <h3>INSTRUCTIONS</h3>
            <ul>
                <li>1) Fees for filling up the application form is Rs.1100/-.</li>
                <li>2) Pay Online or take print out and send with DD.</li>
                <li>3) Track your application status online.</li>
            </ul>
        </div>
        <!--Instructions Ends here-->
    </div>
    <div class="clearFix"></div>
    <!--Contents Starts here-->
	<div id="contentWrapper">
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
            <div class="reviewTitleBox">
                <strong>Personal Details:</strong>
            </div>
        
            <div class="reviewLeftCol">
                <div class="formGreyBox">
                    <ul>
                        <li>
                            <div class="personalInfoCol">
                                <label>First Name:</label>
                                <span><?php echo  $userInfo['firstName'];?></span>
                            </div>
                            
                            <div class="personalInfoCol">
                                <label>Middle Name:</label>
                                <span><?php echo  $userInfo['middleName'];?></span>
                            </div>
                            
                            <div class="personalInfoCol">
                                <label>Last Name:</label>
                                <span><?php echo  $userInfo['lastName'];?></span>
                            </div>
                            <div class="clearFix"></div>
                        </li>
                    </ul>
                </div>
                
                <div class="spacer20 clearFix"></div>
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Gender:</label>
                        <span><?php echo  $userInfo['gender'];?></span>
                    </li>
                    <li>
                        <label>Email Address:</label>
                        <span><?php echo  $userInfo['email'];?></span>
                    </li>
                    <li>
                        <label>Course Applied For:</label>
                        <span><?php echo  $userInfo['courseTitle'];?></span>
                    </li>
                    <li>
                        <label>Application Category:</label>
                        <span><?php echo  $userInfo['applicationCategory'];?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label> Date of birth:</label>
                        <span><?php echo  $userInfo['dateOfBirth'];?></span>
                    </li>
                    <li>
                        <label>Alternate Email:</label>
                        <span><?php echo  $userInfo['altEmail'];?></span>
                    </li>
                    <li>
                        <label>Marital Status:</label>
                        <span><?php echo  $userInfo['maritalStatus'];?></span>
                    </li>
                    <li>
                        <label>Nationality:</label>
                        <span><?php echo  $userInfo['nationality'];?></span>
                    </li>
                    <li>
                        <label>Religion:</label>
                        <span><?php echo  $userInfo['religion'];?></span>
                    </li>
                </ul>
            </div>
            <div class="profilePic">
                <img src="<?php //echo  $userInfo['profileImage'];?>" alt="Profile Pic" />
            </div>
        </div>
        <!--Personal Info Ends here-->
        
        <!--Contact Info Starts here-->
        <div class="contactInfoSection">
            <div class="reviewTitleBox">
                <strong>Contact Information:</strong>
            </div>
        	<h3 class="mL15">Permanent Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>House Number:</label>
                        <span><?php echo  $userInfo['houseNumber'];?></span>
                    </li>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo  $userInfo['area'];?></span>
                    </li>
                    <li>
                        <label>Country:</label>
                        <span><?php echo  $userInfo['country'];?></span>
                    </li>
                    <li>
                        <label>City:</label>
                        <span><?php echo  $userInfo['city'];?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo  $userInfo['streetName'];?></span>
                    </li>
                    <li>
                        <label>State:</label>
                        <span><?php echo  $userInfo['state'];?></span>
                    </li>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo  $userInfo['pincode'];?></span>
                    </li>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo  $userInfo['landlineISDCode'];?>-'pranjul'</span>
                    </li>
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo  $userInfo['mobileISDCode'];?>-<?php echo  $userInfo['mobileNumber'];?></span>
                    </li>
                </ul>
            </div>
            
            <div class="spacer5 clearFix"></div>
            <h3 class="mL15">Correspondence Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>House Number:</label>
                        <span><?php echo  $userInfo['ChouseNumber'];?></span>
                    </li>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo  $userInfo['Carea'];?></span>
                    </li>
                    <li>
                        <label>Country:</label>
                        <span><?php echo  $userInfo['Ccountry'];?></span>
                    </li>
                    <li>
                        <label>City:</label>
                        <span><?php echo  $userInfo['Ccity'];?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo  $userInfo['CstreetName'];?></span>
                    </li>
                    <li>
                        <label>State:</label>
                        <span><?php echo  $userInfo['Cstate'];?></span>
                    </li>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo  $userInfo['Cpincode'];?></span>
                    </li>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo  $userInfo['landlineISDCode'];?>-<?php echo  'pranjul';?></span>
                    </li>
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo  $userInfo['mobileISDCode'];?>-<?php echo  $userInfo['mobileNumber'];?></span>
                    </li>
                </ul>
            </div>
            
        </div>
        <!--Contact Info Ends here-->
        
        <!--Family Info Starts here-->
    	<div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Family Information:</strong>
            </div>
        
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Father's Name:</label>
                        <span><?php echo  $userInfo['fatherName'];?></span>
                    </li>
                    <li>
                        <label>Occupation:</label>
                        <span><?php echo  $userInfo['fatherOccupation'];?></span>
                    </li>
                    <li>
                        <label>Designation:</label>
                        <span><?php echo  $userInfo['fatherDesignation'];?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Mother's Name:</label>
                        <span><?php echo  $userInfo['MotherName'];?></span>
                    </li>
                    <li>
                        <label>Occupation:</label>
                        <span><?php echo  $userInfo['MotherOccupation'];?></span>
                    </li>
                    <li>
                        <label>Designation:</label>
                        <span><?php echo  $userInfo['MotherDesignation'];?></span>
                    </li>
                </ul>
            </div>
        </div>
        <!--Family Info Ends here-->
        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Education Information:</strong>
            </div>
        	<h3 style="margin-left:5px">Education History:</h3>
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
                            <span class="educationCol educationColFirst"><?php echo  $userInfo['class10ExaminationName'];?></span>
                            <span class="educationCol"><?php echo  $userInfo['class10ExaminationName'];?></span>
                            <span class="educationCol"><?php echo  $userInfo['class10Board'];?></span>
                            <span class="educationYearCol"><?php echo  $userInfo['class10Year'];?></span>
                            <span class="educationSmallCol"><?php echo  $userInfo['class10Percentage'];?>%</span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 12<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst"><?php echo  $userInfo['class12ExaminationName'];?></span>
                            <span class="educationCol"><?php echo  $userInfo['class12School'];?></span>
                            <span class="educationCol"><?php echo  $userInfo['class12Board'];?></span>
                            <span class="educationYearCol"><?php echo  $userInfo['class12Year'];?></span>
                            <span class="educationSmallCol"><?php echo  $userInfo['class12Percentage'];?>%</span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong>Graduation</strong></label>
                            <span class="educationCol educationColFirst"><?php echo  $userInfo['graduationExaminationName'];?></span>
                            <span class="educationCol"><?php echo  $userInfo['graduationSchool'];?></span>
                            <span class="educationCol"><?php echo  $userInfo['graduationBoard'];?></span>
                            <span class="educationYearCol"><?php echo  $userInfo['graduationYear'];?></span>
                            <span class="educationSmallCol"><?php echo  $userInfo['graduationPercentage'];?>%</span>
						</div>
                    </li>
                </ul>
                <div class="clearFix"></div>
             </div>
             <div class="spacer25 clearFix"></div>
             <h3 style="margin-left:5px">Qualifying Examination:</h3>
             <div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
                    	<p class="educationCol widthAuto">CAT Score Details</p>
                    </div>
                   
                <ul class="qualifyingDetails">
                	<li>
                        <div class="formColumns">
                            <label><strong>Date of examination:</strong></label>
                            <span><?php echo  $userInfo['catDateOfExamination'];?></span>
						</div>
                        
                        <div class="formColumns">
                            <label><strong>Score:</strong></label>
                            <span><?php echo  $userInfo['catScore'];?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
                            <label><strong>Roll Number:</strong></label>
                            <span><?php echo  $userInfo['catRollNumber'];?></span>
						</div>
                        
                        <div class="formColumns">
                            <label><strong>Percentile:</strong></label>
                            <span><?php echo  $userInfo['catPercentile'];?>%</span>
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
            </div>
        
            <div class="widthAuto">
                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Company Name:</label>
                            <span><?php echo  $userInfo['weCompanyName'];?></span>
						</div>
                        	
                    	<div class="formColumns">
                    	<label>Designation:</label>
                        <span><?php echo  $userInfo['weDesignation'];?></span>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <span><?php echo  $userInfo['weLocation'];?></span>
                    </div> 
                    
                    <div class="formColumns">
						<label class="timePeriodLabel">Time Period:</label>
                        <input type="checkbox" checked="checked" /> <?php echo  $userInfo['weTimePeriod'];?><br />
                        <div class="workExpDetails">
                        	<span>From: <?php echo  $userInfo['weFrom'];?></span>
                            <span class="mL14">Till: <?php echo  $userInfo['weTill'];?></span>
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
                <li><?php echo  $userInfo['weRoles'];?></li>
            </ul>
        </div>
    </div>
    
    <div class="spacer20 clearFix"></div>
     
     <div class="comments">
     	<h3>Comments:</h3>
        
        <div class="commentBox">
        	<ul>
            	<li><p>Candidate is interesting with strong credentials. But lacks industry experience can be 
considered for waitlist - by Prof.Sharma</p>
				<em>Added at 20-10-11, 12:34 PM.</em>
			</li>
            <li><p>Candidate appeared to be good in GD/PI, can be offered a seat - by Prof.Mohan</p>
				<em>Added at 20-10-11, 12:34 PM.</em>
			</li>
            <li><textarea rows="1" cols="3">Write a comment</textarea></li>
            </ul>
        </div>
     </div>
    
     <div class="clearFix spacer15"></div>
     <div class="buttonBlock buttonWrapper">
            	<input type="button" value="Draft Received" class="entOrangeButton" id="1"/> &nbsp;
                <input type="button" value="Request Photographs" class="entOrangeButton" id="2"/> &nbsp;
                <input type="button" value="Request Documents" class="entOrangeButton" id="3"/> &nbsp;
               <div class="spacer10 clearAll" ></div>
				<input type="button" value="Confirm Acceptance" class="entOrangeButton" id="4"/> &nbsp;
                <input type="button" value="Update GD/PI" class="entOrangeButton" id="5"/> &nbsp;
                <input type="button" value="Reject Application" class="entOrangeButton" id="6"/>&nbsp;
                <input type="button" value="Shortlist Application" class="entOrangeButton" id="18"/>
           <div class="spacer10 clearAll" ></div>
                <input type="button" value="Cancel Application" class="cancelButton" id="17"/>

            </div>
     
    </div>
    <!--Contents Ends here-->
	
	<div class="clearFix"></div>
   </div>
</div>
