<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Ref. No. <?=$instituteInfo['sessionYear']?>/<?="B.Tech"?>/S<?=$instituteSpecId?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name">
                <img src="<?=$instituteInfo['logo_link']?>" alt="<?=$instituteInfo['institute_name']?>" style="float:left" />
				<div class="inst-place">
				<h2 style="font-size:20px;"><?=$instituteInfo['institute_name']?></h2>
				<div style="text-align:left;">
                    Off Hennur, Bagalur Main Road Chagalatti<br>
					Bangalore 562149<br>
					Karnataka, India<br>
                    Ph: 080-25426977/ 25426988/ 25427700<br>
                    E-Mail: admissions@cmr.edu.in<br>
                    Website: https://www.cmr.edu.in<br>
				</div>
				</div>
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
    </div>
    <div class="spacer15 clearFix"></div>
	<div class="appForm-box" style="width:98%">
				APPLICATION FORM FOR ADMISSION IN B.Tech <?=$instituteInfo['sessionYear']?>-<?=($instituteInfo['sessionYear']+2)?> BATCH
			</div>
        
        <div class="spacer15 clearFix"></div>
            <div class="reviewTitleBox">
                <strong>Personal Details:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>-->
            </div>
            <div class="reviewLeftCol">
                <div class="formGreyBox">
                    <ul>
                        <li>
                           <?php //if($profile_data['firstName']):?>
                            <div class="personalInfoCol">
                                <label>First Name:</label>
                                <span><?php echo $firstName;?></span>
                            </div>
                            <?php //endif;?>
                            <?php //if($profile_data['middleName']):?>
                            <div class="personalInfoCol">
                                <label>Middle Name:</label>
                                <span><?php if(empty($middleName)) {echo "&nbsp;&nbsp;&nbsp;&nbsp;-";} else {echo $middleName;}?></span>
                            </div>
                            <?php //endif;?>
                            <?php //if($profile_data['lastName']):?>
                            <div class="personalInfoCol">
                                <label>Last Name:</label>
                                <span><?php echo $lastName;?></span>
                            </div>
                            <?php //endif;?>
                            <div class="clearFix"></div>
                        </li>
                    </ul>
                </div>
                
                <div class="spacer20 clearFix"></div>
                <ul class="reviewChildLeftCol">
                <?php //if($profile_data['gender']):?>
                    <li>
                        <label>Gender:</label>
                        <span><?php echo $gender;?></span>
                    </li>
                    <?php //endif;?>
                    <?php //if($profile_data['email']):?>
                    <li>
                        <label>Email Address:</label>
                        <span><?php echo $email;?></span>
                    </li>
                    <?php //endif;?>
                    <li>
                        <label>Course Applied For:</label>
                        <span><?php echo $courseName;?></span>
                    </li>
                    <?php //if($profile_data['applicationCategory']):?>
                    <li>
                        <label>Application Category:</label>
                        <span><?php echo $applicationCategory;?></span>
                    </li>
                    <?php //endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['dateOfBirth']):?>
                    <li>
                        <label> Date of birth:</label>
                        <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['altEmail']):?>
                    <li>
                        <label>Alternate Email:</label>
                        <span><?php echo $altEmail;?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['maritalStatus']):?> 
                    <li>
                        <label>Marital Status:</label>
                        <span><?php echo $maritalStatus;?></span>
                    </li>
                    <?php //endif;
                    // if($profile_data['nationality']):?>
                    <li>
                        <label>Nationality:</label>
                        <span><?php echo $nationality;?></span>
                    </li>
                    <?php //endif;
                    // if($profile_data['religion']):?>
                    <li>
                        <label>Religion:</label>
                        <span><?php echo $religion;?></span>
                    </li>
                    <?php //endif;?>
                </ul>
            </div>
            <!--<div class="profilePic">
                <img width="195" height="192" src="<?php echo $profileImage;?>" alt="Profile Pic" />
            </div>-->
            <div class="picBox" style="border:none"></div>
        </div>
        <!--Personal Info Ends here-->
        
        <!--Contact Info Starts here-->
        <div class="contactInfoSection">
            <div class="reviewTitleBox">
                <strong>Contact Information:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/2/editProfile">Edit</a>-->
            </div>
        	<h3>Permanent Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                <?php //if($profile_data['houseNumber']):?>
                    <li>
                        <label>House Number:</label>
                        <span><?php echo $houseNumber;?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['area']):?>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo $area;?></span>
                    </li>
                    <?php //endif;
                    // if($profile_data['country']):?>
                    <li>
                        <label>Country:</label>
                        <span><?php echo $country;?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['city']):?>
                    <li>
                        <label>City:</label>
                        <span><?php echo $city;?></span>
                    </li>
                    <?php //endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['streetName']):?>
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo $streetName;?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['state']):?>
                    <li>
                        <label>State:</label>
                        <span><?php echo $state;?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['pincode']):?>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo $pincode;?></span>
                    </li>
                    <?php //endif;
                    // if($profile_data['landlineNumber']):?>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['mobileNumber']):?>
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
                    <?php //endif;?>
                </ul>
            </div>
            
            <div class="spacer5 clearFix"></div>
            <h3>Correspondence Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                <?php //if($profile_data['ChouseNumber']):?>
                    <li>
                        <label>House Number:</label>
                        <span><?php echo $ChouseNumber;?></span>
                    </li>
                    <?php //endif;
                   // if($profile_data['Carea']):?>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo $Carea;?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['Ccountry']):
                    ?>
                    <li>
                        <label>Country:</label>
                        <span><?php echo $Ccountry;?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['Ccity']):
                    ?>
                    <li>
                        <label>City:</label>
                        <span><?php echo $Ccity;?></span>
                    </li>
                    <?php //endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['CstreetName']):?>
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo $CstreetName;?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['Cstate']):
                    ?>
                    <li>
                        <label>State:</label>
                        <span><?php echo $Cstate;?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['Cpincode']):
                    ?>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo $Cpincode;?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['landlineNumber']):
                    ?>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['mobileNumber']):
                    ?>
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
                    <?php //endif;?>
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
                <?php //if($profile_data['fatherName']):?>
                    <li>
                        <label>Father's Name:</label>
                        <span><?php echo $fatherName;?></span>
                    </li>
                    <?php 
                    //if($profile_data['fatherOccupation']):
                    ?>
                    <li>
                        <label>Occupation:</label>
                        <span><?php echo $fatherOccupation;?></span>
                    </li>
                    <?php //endif;
                   // if($profile_data['fatherDesignation']):
                    ?>
                    <li>
                        <label>Designation:</label>
                        <span><?php echo $fatherDesignation;?></span>
                    </li>
                    <?php //endif; endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                  <?php //if($profile_data['MotherName']):?>
                    <li>
                        <label>Mother's Name:</label>
                        <span><?php echo $MotherName;?></span>
                    </li>
                    <?php //if($profile_data['MotherOccupation']):?>
                    <li>
                        <label>Occupation:</label>
                        <span><?php echo $MotherOccupation;?></span>
                    </li>
                    <?php //endif;
                   // if($profile_data['MotherDesignation']):
                    ?>
                    <li>
                        <label>Designation:</label>
                        <span><?php echo $MotherDesignation;?></span>
                    </li>
                    <?php // endif;endif;?>
                </ul>
            </div>
        </div>
        <!--Family Info Ends here-->
        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Education Information:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
        	<h3 style="padding-left:12px">Education History:</h3>
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
                            <span class="educationCol educationColFirst word-wrap"><?php echo $class10ExaminationName;?></span>
                            <span class="educationCol word-wrap"><?php echo $class10School;?></span>
                            <span class="educationCol word-wrap"><?php echo $class10Board;?></span>
                            <span class="educationYearCol"><?php echo $class10Year;?></span>
                            <span class="educationSmallCol"><?php echo $class10Percentage;?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 12<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst word-wrap"><?php echo $class12ExaminationName;?></span>
                            <span class="educationCol word-wrap"><?php echo $class12School;?></span>
                            <span class="educationCol word-wrap"><?php echo $class12Board;?></span>
                            <span class="educationYearCol"><?php echo $class12Year;?></span>
                            <span class="educationSmallCol"><?php echo $class12Percentage;?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong>Graduation</strong></label>
                            <span class="educationCol educationColFirst word-wrap"><?php echo $graduationExaminationName;?></span>
                            <span class="educationCol word-wrap"><?php echo $graduationSchool;?></span>
                            <span class="educationCol word-wrap"><?php echo $graduationBoard;?></span>
                            <span class="educationYearCol"><?php echo $graduationYear;?></span>
                            <span class="educationSmallCol"><?php echo $graduationPercentage;?></span>
						</div>
                    </li>
                    <?php //if(!empty($exam_array)):?>
                    <?php //$count_exam = count($exam_array); 
			for($j=1;$j<=4;$j++):?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong><?php echo "Additional$j"?></strong></label>
                            <span class="educationCol educationColFirst word-wrap"><?php echo ${'graduationExaminationName_mul_'.$j};?></span>
                            <span class="educationCol word-wrap"><?php echo ${'graduationSchool_mul_'.$j};?></span>
                            <span class="educationCol word-wrap"><?php echo ${'graduationBoard_mul_'.$j};?></span>
                            <span class="educationYearCol"><?php echo ${'graduationYear_mul_'.$j};?></span>
                            <span class="educationSmallCol"><?php echo ${'graduationPercentage_mul_'.$j};?></span>
						</div>
                    </li>
                    <?php endif;endfor; //endif;?>
                </ul>
                <div class="clearFix"></div>
             </div>
	     <div class="spacer20 clearFix"></div>
     
             <div class="spacer25 clearFix"></div>
	     <?php $this->load->view('Online/testExamDisplayUG','array("profile_data"=>$profile_data)');?>	    

        </div>
        <!--Education Info Ends here-->
        
    </div>



<div id="custom-form-content" class="reviewLeftCol widthAuto">
 <div class="mb15 clearspace">
    <h3 class="preview_title" style="margin-top: 20px;">Course Details</h3>
	<ul class="adjust_ul">

		<li style="width: 100%;">
			<div class="colums-width" style="width: 100%;">
				<label>Courses selected: </label>
				<div class="form-details" style="width: 100%;"><?php echo $clientCoursesCMRBtech2018; ?></div>
			</div>
		</li>
    </ul>
   </div>

 <div class="mb15 clearspace">
    <h3 class="preview_title">Personal Information</h3>
    <ul class="adjust_ul">
	
		<li>
			<div class="colums-width">
				<label>Domicile: </label>
				<div class="form-details"><?php echo $DomicileCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Passport Number: </label>
				<div class="form-details"><?php echo $PassportNumberCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Date of Issue: </label>
				<div class="form-details"><?php echo $dateOfIssuePassportCMRBtech2018; ?></div>
			</div>
		</li>

		<li>
			<div class="colums-width">
				<label>Caste: </label>
				<div class="form-details"><?php echo $CasteCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Place of Birth: </label>
				<div class="form-details"><?php echo $PlaceOfBirthCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Citizenship: </label>
				<div class="form-details"><?php echo $PlaceOfIssueCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Expiry Date: </label>
				<div class="form-details"><?php echo $ExpiryDateCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Place of Issue: </label>
				<div class="form-details"><?php echo $CitizenShipCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Alternate Contact Number: </label>
				<div class="form-details"><?php echo $AlternateMobileNumberCMRBtech2018; ?></div>
			</div>
		</li>


       </ul> 
     </div>

 <div class="mb15 clearspace">
    <h3 class="preview_title">Parents Information</h3>
    <ul class="adjust_ul">
	
		<li>
			<div class="colums-width">
				<label>Father's Email: </label>
				<div class="form-details" style="word-break: break-word;"><?php echo $FathersEmailIdCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Father's Qualification: </label>
				<div class="form-details"><?php echo $FatherQualificationCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Father's Annual Income (in Rs. Monthly): </label>
				<div class="form-details"><?php echo $FatherAnnualIncomeCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Father's Mobile: </label>
				<div class="form-details"><?php echo $FathersPhoneNumberCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Mother's Email: </label>
				<div class="form-details" style="word-break: break-word;"><?php echo $MothersEmailIdCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Mother's Qualification: </label>
				<div class="form-details" style="word-break: break-word;"><?php echo $MotherQualificationCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Mother's Mobile: </label>
				<div class="form-details"><?php echo $MothersPhoneNumberCMRBtech2018; ?></div>
			</div>
		</li>
    

        </ul>
       </div>
 
 <div class="mb15 clearspace">
    <h3 class="preview_title">Guardian Information</h3>
    <ul class="adjust_ul">

		<li>
			<div class="colums-width">
				<label>Guardian Name: </label>
				<div class="form-details"><?php echo $EmergencyContactNameCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Relationship with Student: </label>
				<div class="form-details"><?php echo $EmergencyRelationshipCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Guardian's Email: </label>
				<div class="form-details" style="word-break: break-word;"><?php echo $AlternateEmailCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Guardian's Address: </label>
				<div class="form-details"><?php echo $Address1CMRBtech2018; ?></div>
			</div>
		</li>

        <li>
            <div class="colums-width">
                <label>Guardian's Town/City: </label>
                <div class="form-details"><?php if($CityCMRBtech2018=='Select'){echo "";}else{echo $CityCMRBtech2018;} ?></div>
            </div>
        </li>
	
		<li>
			<div class="colums-width">
				<label>Pincode: </label>
				<div class="form-details"><?php echo $PincodeCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Guardian's Mobile: </label>
				<div class="form-details"><?php echo $EmergencyPhoneNumberCMRBtech2018; ?></div>
			</div>
		</li>

      </ul>
  </div>


 <div class="mb15 clearspace">
    <h3 class="preview_title">10th/12th Details</h3>
    <ul class="adjust_ul">
	
		<li>
			<div class="colums-width">
				<label>Mode of Study(10th): </label>
				<div class="form-details"><?php echo $ModeOfStudySSLCCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Registration Number(10th): </label>
				<div class="form-details"><?php echo $RegistrationNumber1CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Is your result out?(12th): </label>
				<div class="form-details"><?php echo $IsXIIResultOutCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Mode of Study(12th): </label>
				<div class="form-details"><?php echo $ModeOfStudyXIICMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Registration Number(12th): </label>
				<div class="form-details"><?php echo $RegistrationNumber2CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Evaluation Type (Grade/Percentage): </label>
				<div class="form-details"><?php echo $EvaluationTypeXIICMRBtech2018; ?></div>
			</div>
		</li>
	

    <div style="display: <?php if ($IsXIIResultOutCMRBtech2018 == 'Yes') echo 'block' ; else echo 'none'; ?>">
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject 1 (12th): </label>
				<div class="form-details"><?php echo $SubjectXII1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks 1 (12th): </label>
				<div class="form-details"><?php echo $MaxMarkXII1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks/Grade Obtained 1 (12th): </label>
				<div class="form-details"><?php echo $MarksXII1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage Obtained 1 (12th): </label>
				<div class="form-details"><?php echo $PercentageXII1CMRBtech2018; ?></div>
			</div>
		</li>
	

		<li class="flex-li">
			<div class="colums-width">
				<label>Subject 2 (12th): </label>
				<div class="form-details"><?php echo $SubjectXII2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks 2 (12th): </label>
				<div class="form-details"><?php echo $MaxMarkXII2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks/Grade Obtained 2 (12th): </label>
				<div class="form-details"><?php echo $MarksXII2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage Obtained 2 (12th): </label>
				<div class="form-details"><?php echo $PercentageXII2CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject 3 (12th): </label>
				<div class="form-details"><?php echo $SubjectXII3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks 3 (12th): </label>
				<div class="form-details"><?php echo $MaxMarkXII3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks/Grade Obtained 3 (12th): </label>
				<div class="form-details"><?php echo $MarksXII3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage Obtained 3 (12th): </label>
				<div class="form-details"><?php echo $PercentageXII3CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject 4 (12th): </label>
				<div class="form-details"><?php echo $SubjectXII4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks 4 (12th): </label>
				<div class="form-details"><?php echo $MaxMarkXII4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks/Grade Obtained 4 (12th): </label>
				<div class="form-details"><?php echo $MarksXII4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage Obtained 4 (12th): </label>
				<div class="form-details"><?php echo $PercentageXII4CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject 5 (12th): </label>
				<div class="form-details"><?php echo $SubjectXII5CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks 5 (12th): </label>
				<div class="form-details"><?php echo $MaxMarkXII5CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks/Grade Obtained 5 (12th): </label>
				<div class="form-details"><?php echo $MarksXII5CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage Obtained 5 (12th): </label>
				<div class="form-details"><?php echo $PercentageXII5CMRBtech2018; ?></div>
			</div>
		</li>
    </div>
      </ul>
  </div>

 <div class="mb15 clearspace">
    <h3 class="preview_title">Graduation Details</h3>
    <ul class="adjust_ul">
	
		<li>
			<div class="colums-width">
				<label>Do you have Graduation/Diploma details?: </label>
				<div class="form-details"><?php echo $IsDegreeDeplomaDoneCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Degree or Diploma: </label>
				<div class="form-details"><?php if($IsDegreeDeplomaDoneCMRBtech2018=='No'){echo "";}else{ echo $GraduationTypeCMRBtech2018;} ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Name of Degree/Diploma: </label>
				<div class="form-details"><?php echo $GraduationNameCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Area of Specialization: </label>
				<div class="form-details"><?php echo $GraduationSpecializatonCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Name of College: </label>
				<div class="form-details"><?php echo $GraduationInstituteNameCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Name of University/Technical Board: </label>
				<div class="form-details"><?php echo $GraduationUniversityNameCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Year of Completion: </label>
				<div class="form-details"><?php if($GraduationYearOfPassingCMRBtech2018=='Select'){echo "";}else{echo $GraduationYearOfPassingCMRBtech2018;} ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Mode of Study: </label>
				<div class="form-details"><?php if($GraduationModeOfStudyCMRBtech2018=='Select'){echo "";}else{echo $GraduationModeOfStudyCMRBtech2018;} ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Examination Type: </label>
				<div class="form-details"><?php if($ExaminationTypeCMRBtech2018=='Select'){echo "";}else{echo $ExaminationTypeCMRBtech2018;} ?></div>
			</div>
		</li>
	
        <div style=" display : <?php if($ExaminationTypeCMRBtech2018=='Year'){echo "block";}else{echo 'none';} ?>" >
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Year 1): </label>
				<div class="form-details"><?php echo $SubjectDegreeYear1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Year 1): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeYear1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Year 1): </label>
				<div class="form-details"><?php echo $MarksDegreeYear1CMRBtech2018; ?></div>
			</div>
	
			<div class="colums-width">
				<label>Percentage (Year 1): </label>
				<div class="form-details"><?php echo $PercentageDegreeYear1CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Year 2): </label>
				<div class="form-details"><?php echo $SubjectDegreeYear2CMRBtech2018; ?></div>
			</div>
	
			<div class="colums-width">
				<label>Max Marks (Year 2): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeYear2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Year 2): </label>
				<div class="form-details"><?php echo $MarksDegreeYear2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Year 2): </label>
				<div class="form-details"><?php echo $PercentageDegreeYear2CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Year 3): </label>
				<div class="form-details"><?php echo $SubjectDegreeYear3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Year 3): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeYear3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Year 3): </label>
				<div class="form-details"><?php echo $MarksDegreeYear3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Year 3): </label>
				<div class="form-details"><?php echo $PercentageDegreeYear3CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Year 4): </label>
				<div class="form-details"><?php echo $SubjectDegreeYear4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Year 4): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeYear4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Year 4): </label>
				<div class="form-details"><?php echo $MarksDegreeYear4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Year 4): </label>
				<div class="form-details"><?php echo $PercentageDegreeYear4CMRBtech2018; ?></div>
			</div>
		</li>
	   </div>


       <div style=" display : <?php if($ExaminationTypeCMRBtech2018=='Semester'){echo "block";}else{echo 'none';} ?>" >
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 1): </label>
				<div class="form-details"><?php echo $SubjectDegreeSem1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 1): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeSem1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 1): </label>
				<div class="form-details"><?php echo $MarksDegreeSem1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 1): </label>
				<div class="form-details"><?php echo $PercentageDegreeSem1CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 2): </label>
				<div class="form-details"><?php echo $SubjectDegreeSem2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 2): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeSem2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 2): </label>
				<div class="form-details"><?php echo $MarksDegreeSem2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 2): </label>
				<div class="form-details"><?php echo $PercentageDegreeSem2CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 3): </label>
				<div class="form-details"><?php echo $SubjectDegreeSem3CMRBtech2018; ?></div>
			</div>
	
			<div class="colums-width">
				<label>Max Marks (Semester 3): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeSem3CMRBtech2018; ?></div>
			</div>
	
			<div class="colums-width">
				<label>Marks Obtained (Semester 3): </label>
				<div class="form-details"><?php echo $MarksDegreeSem3CMRBtech2018; ?></div>
			</div>
	
			<div class="colums-width">
				<label>Percentage (Semester 3): </label>
				<div class="form-details"><?php echo $PercentageDegreeSem3CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 4): </label>
				<div class="form-details"><?php echo $SubjectDegreeSem4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 4): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeSem4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 4): </label>
				<div class="form-details"><?php echo $MarksDegreeSem4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 4): </label>
				<div class="form-details"><?php echo $PercentageDegreeSem4CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 5): </label>
				<div class="form-details"><?php echo $SubjectDegreeSem5CMRBtech2018; ?></div>
			</div>
		
        	<div class="colums-width">
				<label>Max Marks (Semester 5): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeSem5CMRBtech2018; ?></div>
			</div>

			<div class="colums-width">
				<label>Marks Obtained (Semester 5): </label>
				<div class="form-details"><?php echo $MarksDegreeSem5CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 5): </label>
				<div class="form-details"><?php echo $PercentageDegreeSem5CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 6): </label>
				<div class="form-details"><?php echo $SubjectDegreeSem6CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 6): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeSem6CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 6): </label>
				<div class="form-details"><?php echo $MarksDegreeSem6CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 6): </label>
				<div class="form-details"><?php echo $PercentageDegreeSem6CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 7): </label>
				<div class="form-details"><?php echo $SubjectDegreeSem7CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 7): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeSem7CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 7): </label>
				<div class="form-details"><?php echo $MarksDegreeSem7CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 7): </label>
				<div class="form-details"><?php echo $PercentageDegreeSem7CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 8): </label>
				<div class="form-details"><?php echo $SubjectDegreeSem8CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 8): </label>
				<div class="form-details"><?php echo $MaxMarkDegreeSem8CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 8): </label>
				<div class="form-details"><?php echo $MarksDegreeSem8CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 8): </label>
				<div class="form-details"><?php echo $PercentageDegreeSem8CMRBtech2018; ?></div>
			</div>
		</li>

    </div>
      </ul>
  </div>


  <div class="mb15 clearspace">
    <h3 class="preview_title">Post Graduation Details</h3>
    <ul class="adjust_ul">
	
		<li>
			<div class="colums-width">
				<label>Do you have Post Graduation/Diploma details?: </label>
				<div class="form-details"><?php echo $IsPGDegreeDeplomaDoneCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Degree or Diploma: </label>
				<div class="form-details"><?php if($IsPGDegreeDeplomaDoneCMRBtech2018=='No'){echo "";}else{echo $PostGraduationTypeCMRBtech2018;} ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Name of Degree/Diploma: </label>
				<div class="form-details"><?php echo $PostGraduationNameCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Area of Specialization: </label>
				<div class="form-details"><?php echo $PGSpecializatonCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Name of College: </label>
				<div class="form-details"><?php echo $PGInstituteNameCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Name of University/Technical Board: </label>
				<div class="form-details"><?php echo $PGUniversityNameCMRBtech2018; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Year of Completion: </label>
				<div class="form-details"><?php if($PGYearOfPassingCMRBtech2018=='Select'){echo "";}else{echo $PGYearOfPassingCMRBtech2018;} ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Mode of Study: </label>
				<div class="form-details"><?php if($PGModeOfStudyCMRBtech2018=='Select'){echo "";}else{echo $PGModeOfStudyCMRBtech2018;} ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Examination Type: </label>
				<div class="form-details"><?php if($PGExaminationTypeCMRBtech2018=='Select'){echo "";}else{echo $PGExaminationTypeCMRBtech2018;} ?></div>
			</div>
		</li>
	

        <div style=" display : <?php if($PGExaminationTypeCMRBtech2018=='Year'){echo "block";}else{echo 'none';} ?>" >
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Year 1): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeYear1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Year 1): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeYear1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Year 1): </label>
				<div class="form-details"><?php echo $MarksPGDegreeYear1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Year 1): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeYear1CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Year 2): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeYear2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Year 2): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeYear2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Year 2): </label>
				<div class="form-details"><?php echo $MarksPGDegreeYear2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Year 2): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeYear2CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Year 3): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeYear3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Year 3): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeYear3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Year 3): </label>
				<div class="form-details"><?php echo $MarksPGDegreeYear3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Year 3): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeYear3CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Year 4): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeYear4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Year 4): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeYear4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Year 4): </label>
				<div class="form-details"><?php echo $MarksPGDegreeYear4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Year 4): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeYear4CMRBtech2018; ?></div>
			</div>
		</li>
	   </div>


       <div style=" display : <?php if($PGExaminationTypeCMRBtech2018=='Semester'){echo "block";}else{echo 'none';} ?>" >
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 1): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeSem1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 1): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeSem1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 1): </label>
				<div class="form-details"><?php echo $MarksPGDegreeSem1CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 1): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeSem1CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 2): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeSem2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 2): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeSem2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 2): </label>
				<div class="form-details"><?php echo $MarksPGDegreeSem2CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 2): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeSem2CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 3): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeSem3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 3): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeSem3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 3): </label>
				<div class="form-details"><?php echo $MarksPGDegreeSem3CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 3): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeSem3CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 4): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeSem4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 4): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeSem4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 4): </label>
				<div class="form-details"><?php echo $MarksPGDegreeSem4CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 4): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeSem4CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 5): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeSem5CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 5): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeSem5CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 5): </label>
				<div class="form-details"><?php echo $MarksPGDegreeSem5CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 5): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeSem5CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 6): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeSem6CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 6): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeSem6CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 6): </label>
				<div class="form-details"><?php echo $MarksPGDegreeSem6CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 6): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeSem6CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 7): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeSem7CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 7): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeSem7CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 7): </label>
				<div class="form-details"><?php echo $MarksPGDegreeSem7CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 7): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeSem7CMRBtech2018; ?></div>
			</div>
		</li>
	
		<li class="flex-li">
			<div class="colums-width">
				<label>Subject (Semester 8): </label>
				<div class="form-details"><?php echo $SubjectPGDegreeSem8CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Max Marks (Semester 8): </label>
				<div class="form-details"><?php echo $MaxMarkPGDegreeSem8CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Marks Obtained (Semester 8): </label>
				<div class="form-details"><?php echo $MarksPGDegreeSem8CMRBtech2018; ?></div>
			</div>
		
			<div class="colums-width">
				<label>Percentage (Semester 8): </label>
				<div class="form-details"><?php echo $PercentagePGDegreeSem8CMRBtech2018; ?></div>
			</div>
		</li>
	   </div>
	</ul>
</div>
</div>
<div>
    <div id="responsibilityList">
    <li>
                        <label style="font-weight:bold; width:700px">DECLARATION:</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                            I do hereby confirm that the information provided above is true and correct. Further, in the event of my taking up admission at CMR University, I agree to abide by all the rules and regulations that may be in force at the institution.
                            <div class="clearFix spacer25"></div>
                            <div style="float:left; width:300px;">
                                <div class="formColumns2" style="width:250px;">
                                    <label style="width:auto; padding-top:0">Place :</label>
                                    <span>&nbsp;<?php if(isset($firstName) && $firstName!='') {echo $Cstate;} ?></span>
                                </div>
                                <div class="clearFix spacer10"></div>
                                
                                <div class="formColumns2" style="width:250px;">
                                    <label style="width:auto; padding-top:0">Date :</label>
                                    <span>&nbsp;
                    <?php
                          if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['status']=='Success' ) ) ){
                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                        }
                        else
                        {
                            echo date("d/m/Y");
                        }
                    ?>
                    </span>
                                </div>
                            </div>
                            
                            <div style="float:right; width:500px; text-align:right">
                                <p>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></p>
                                <div>Signature of the Applicant</div>
                             </div>
                        </div>
                    </li>
        </div>
<style type="text/css">
   #custom-form-content ul.adjust_ul li{width: 49%;float: left;margin-right: 8px;margin-bottom: 15px;}
   #custom-form-content ul.adjust_ul li label{width:  215px;float:left;text-align: right;}
   #custom-form-content .form-details{width: 430px;}
   .reviewChildLeftCol{margin-right: 10px;}
   .reviewChildLeftCol span{word-wrap: break-word;}
   .reviewLeftCol{width: 769px;}
   #appsFormWrapper h3.preview_title {
    padding: 10px 16px;
    margin: 0 -10px 15px;
    border-bottom: 1px solid #efefef;
    background: #f9f9f9;
    font-size: 14px;
    display: block;
    font-weight: bold;
}
.mb15{margin-bottom: 15px;}
.clearspace:before, .clearspace:after{content: '';display: table;}
.clearspace:after{clear: both;}
#custom-form-content ul.adjust_ul li.flex-li{    width: 100%;
    padding: 11px 0 0 0;
    display: flex;
    flex-direction: row;border-top: 1px solid #d8d8d8;justify-content: space-between;}
#custom-form-content ul.adjust_ul li.flex-li .colums-width{width: 25%;}
#custom-form-content ul.adjust_ul li.flex-li label{    width: 100%;display: block;text-align: left; float: none}
#custom-form-content ul.adjust_ul li.flex-li .form-details{    width: 100%; display: block;}
#custom-form-content ul.adjust_ul li.flex-li:last-child{border-bottom: 1px solid #d8d8d8;padding-bottom: 10px;}
</style>
