<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Ref. No. <?=$instituteInfo['sessionYear']?>/<?=$instituteInfo['courseTitle']?>/S<?=$instituteSpecId?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:80%;margin-left:0px">
                <img src="<?=$instituteInfo['logo_link']?>" alt="<?=$instituteInfo['institute_name']?>" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;"><?=$instituteInfo['institute_name']?></h2>
				<div style="text-align:left;margin-left:20px">
					Old Mahabalipuram Road,<br> 
                    Kalavakkam, Kanchipuram District Off <br>
                    Chennai 603110<br>
					Ph: 044-27426-9774<br>
					E-Mail: mba.admissions@ssn.edu.in<br>
					Website: www.ssn.edu.in<br>
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
				APPLICATION FORM FOR ADMISSION IN <?=$instituteInfo['courseTitle']?> <?=$instituteInfo['sessionYear']?>-<?=($instituteInfo['sessionYear']+2)?> BATCH
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
	     <?php $this->load->view('Online/testExamDisplay','array("profile_data"=>$profile_data)');?>	    

        </div>
        <!--Education Info Ends here-->
        
        <!--Work Exp Info Starts here-->
    	<div class="workInfoSection">
        	<div class="reviewTitleBox">
                <strong>Work Experience:</strong>
            </div>
            <div class="reviewLeftCol widthAuto">
                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Company Name:</label>
                            <div style="width:290px; float:left"><?php echo $weCompanyName;?></div>
						</div>
                        	
                    	<div class="formColumns">
                    	<label class="timePeriodLabel3">Designation:</label>
                        <div style="width:290px; float:left"><?php echo $weDesignation;?></div>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <div style="width:290px; float:left"><?php echo $weLocation;?></div>
                    </div> 
                    
                    <div class="formColumns">
						<label class="timePeriodLabel3">Time Period:</label>
						<?php if($weTimePeriod):?>
                         <input type="checkbox" checked="checked" disabled="disabled" /> I currently work here<br />
                         <?php else:?>
                         <input type="checkbox" disabled="disabled" /> I currently work here<br />
                         <?php endif;?>
                        <div class="workExpDetails3">
                        	<span>From: <?php if(!empty($weFrom)) {echo date('F Y',strtotime(getStandardDate($weFrom)));}?></span>
                        	<span>&nbsp;&nbsp;-</span>
                            <span class="mL10">Till: <?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('F Y',strtotime(getStandardDate($weTill)));}} else {echo "Date";}?></span>
                        </div>
					</div>
                 </li>
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="spacer20 clearFix"></div>
     <div class="rolesResponsiblity">
     	<h3>Roles &amp; Responsibilities:</h3>
        
            <ul>
                <li><?php echo nl2br(trim($weRoles));?></li>
            </ul>
        
    </div>
    <?php //if(!empty($wecompany_array)):?>
    <?php //$count_company = count($wecompany_array);
	for($i=1;$i<=3;$i++):?>

    <?php if(!empty(${'weCompanyName_mul_'.$i})):?>
    <hr style="color: #707070">
    <div class="reviewLeftCol widthAuto">
                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Company Name:</label>
                            <span><?php echo ${'weCompanyName_mul_'.$i};?></span>
						</div>
                        	
                    	<div class="formColumns">
                    	<label class="timePeriodLabel3">Designation:</label>
                        <span><?php echo ${'weDesignation_mul_'.$i};?></span>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <span><?php echo ${'weLocation_mul_'.$i}?></span>
                    </div> 
                    
                    <div class="formColumns">
						<label class="timePeriodLabel3">Time Period:</label>
						<?php if(${'weTimePeriod_mul_'.$i}):?>
                         <input type="checkbox" checked="checked" disabled="disabled" /> I currently work here<br />
                         <?php else:?>
                         <input type="checkbox" disabled="disabled" /> I currently work here<br />
                         <?php endif;?>
                        <div class="workExpDetails3">
                        	<span>From: <?php if(!empty(${'weFrom_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></span>
                        	<span>&nbsp;&nbsp;-</span>
                            <span class="mL10">Till: <?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo "Date";}?></span>
                        </div>
					</div>
                 </li>
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="spacer20 clearFix"></div>
     <div class="rolesResponsiblity">
        <h3>Roles &amp; Responsibilities:</h3>
        
            <ul>
                <li><?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></li>
            </ul>
        
    </div>
     
    <?php endif;endfor;//endif;?>
   </div> 
    </div>

	<div class="rolesResponsiblity" style="display: none;">
     	<div class="reviewTitleBox"><strong>Preferred GD/Interview Centre</strong>
        </div>
        <div id="responsibilityList">
		  <?php echo $gdpiLocation; ?>
        </div>
    </div>

	



<div id="custom-form-content">
            <h3 class="form-title">TESTS</h3>
    <ul class="adjust_ul">

			
	   
		<li>
			<div class="colums-width">
				<label>TESTS: </label>
				<div class="form-details" style="width:650px"><?php 
                $exams = explode(',',$SSN_testNames);
                $displayExam = array();
                foreach ($exams as $exam ){
                    if ($exam == 'SSN_other'){
                        $displayExam[] = $SSN_otherExamName;
                    }
                    else{
                        $displayExam[] = $exam;
                    }
                }
                $exams = implode(',',$displayExam);
                echo $exams;
                 ?></div>
			</div>
		</li>
	
        <li class="heading_li">CAT</li>
		<li>
			<div class="colums-width">
				<label>Date of Examination: </label>
				<div class="form-details"><?php echo $catDateOfExaminationAdd; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Score: </label>
				<div class="form-details"><?php echo $catScoreAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Roll Number: </label>
				<div class="form-details"><?php echo $catRollNumberAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Percentile: </label>
				<div class="form-details"><?php echo $catPercentileAdditional; ?></div>
			</div>
		</li>
	

        <li class="heading_li">MAT</li>
		<li>
			<div class="colums-width">
				<label>Date of Examination: </label>
				<div class="form-details"><?php echo $matDateOfExaminationAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Score: </label>
				<div class="form-details"><?php echo $matScoreAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Roll Number: </label>
				<div class="form-details"><?php echo $matRollNumberAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Percentile: </label>
				<div class="form-details"><?php echo $matPercentileAdditional; ?></div>
			</div>
		</li>
	   

        <li class="heading_li">CMAT</li>
		<li>
			<div class="colums-width">
				<label>Score: </label>
				<div class="form-details"><?php echo $cmatScoreAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Date of Examination: </label>
				<div class="form-details"><?php echo $cmatDateOfExaminationAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Rank: </label>
				<div class="form-details"><?php echo $cmatRankAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Roll Number: </label>
				<div class="form-details"><?php echo $cmatRollNumberAdditional; ?></div>
			</div>
		</li>
	

        <li class="heading_li">XAT</li>
		<li>
			<div class="colums-width">
				<label>Date of Examination: </label>
				<div class="form-details"><?php echo $xatDateOfExaminationAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Score: </label>
				<div class="form-details"><?php echo $xatScoreAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Roll Number: </label>
				<div class="form-details"><?php echo $xatRollNumberAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Percentile: </label>
				<div class="form-details"><?php echo $xatPercentileAdditional; ?></div>
			</div>
		</li>
	   
        <li class="heading_li">TANCET</li>
		<li>
			<div class="colums-width">
				<label>Date of Examination: </label>
				<div class="form-details"><?php echo $tancetDateOfExaminationAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Score: </label>
				<div class="form-details"><?php echo $tancetScoreAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Roll Number: </label>
				<div class="form-details"><?php echo $tancetRollNumberAdditional; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label>Percentile: </label>
				<div class="form-details"><?php echo $tancetPercentileAdditional; ?></div>
			</div>
		</li>
	   
        <li class="heading_li">ATMA</li>
        <li>
            <div class="colums-width">
                <label>Score: </label>
                <div class="form-details"><?php echo $atmaScoreAdditional; ?></div>
            </div>
        </li>
    
        <li>
            <div class="colums-width">
                <label>Percentile: </label>
                <div class="form-details"><?php echo $atmaPercentileAdditional; ?></div>
            </div>
        </li>
    
        <li>
            <div class="colums-width">
                <label>Roll Number: </label>
                <div class="form-details"><?php echo $atmaRollNumberAdditional; ?></div>
            </div>
        </li>
    
        <li>
            <div class="colums-width">
                <label>Date of Examination: </label>
                <div class="form-details"><?php echo $atmaDateOfExaminationAdditional; ?></div>
            </div>
        </li>
        <li class="heading_li">Other Exams</li>
        <li>
            <div class="colums-width">
                <label>Entrance Test: </label>
                <div class="form-details"><?php echo $SSN_otherExamName; ?></div>
            </div>
        </li>
        <li>
            <div class="colums-width">
                <label>Score: </label>
                <div class="form-details"><?php echo $SSN_otherScoreAdditional; ?></div>
            </div>
        </li>
       <li>
            <div class="colums-width">
                <label>Percentile: </label>
                <div class="form-details"><?php echo $SSN_otherPercentileAdditional; ?></div>
            </div>
        </li>

        <li>
            <div class="colums-width">
                <label>Roll Number: </label>
                <div class="form-details"><?php echo $SSN_otherRollNumberAdditional; ?></div>
            </div>
        </li>

       
        <li>
            <div class="colums-width">
                <label>Date of Examination: </label>
                 <div class="form-details"><?php echo $SSN_otherDateOfExaminationAdd; ?></div>
            </div>
        </li>



        <div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>More Personal Information: </strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
		<li>
			<div class="colums-width">
				<label>Annual Family Income: </label>
				<div class="form-details"><?php echo $SSN_familyIncome; ?></div>
			</div>
		</li>

        <li>
            <div class="colums-width">
                <label>Aadhar Number: </label>
                <div class="form-details"><?php echo $SSN_AadharNumber; ?></div>
            </div>
        </li>   
	   <li>
            <div class="colums-width">
                <label>Community: </label>
                <div class="form-details"><?php echo $SSN_category; ?></div>
            </div>
        </li>

        <li>
            <div class="colums-width">
                <label>Parent's Mobile: </label>
                <div class="form-details"><?php echo $SSN_FatherMotherMobile; ?></div>
            </div>
        </li>
    </div>


    <div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Academic Information: </strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
        <li>
            <div class="colums-width">
                <label>Qualifying exam: </label>
                <div class="form-details"><?php echo $SSN_passed; ?></div>
            </div>
        </li>
	   
       <li>
            <div class="colums-width">
                <label>Course name: </label>
                <div class="form-details"><?php echo $SSN_courseName; ?></div>
            </div>
        </li>
    
        <li>
            <div class="colums-width">
                <label>Course type: </label>
                <div class="form-details"><?php echo $SSN_courseType; ?></div>
            </div>
        </li>
          <li>
            <div class="colums-width">
                <label>UG Arrears: </label>
                <div class="form-details"><?php echo $SSN_arrearGrad; ?></div>
            </div>
        </li>

        <li>
            <div class="colums-width">
                <label>Specialization in Graduation: </label>
                <div class="form-details"><?php echo $SSN_gradSpec; ?></div>
            </div>
        </li>
    
        <li>
            <div class="colums-width">
                <label>UG Discipline/Branch: </label>
                <div class="form-details"><?php echo $SSN_disciplineGrad; ?></div>
            </div>
        </li>
    
        
    
        <li>
            <div class="colums-width">
                <label>Name of other course: </label>
                <div class="form-details"><?php echo $SSN_otherCourse; ?></div>
            </div>
        </li>
    
    </div>


 <div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Work Experience: </strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
        <li>
            <div class="colums-width">
                <label>Total Experience (in years): </label>
                <div class="form-details"><?php echo $SSN_totalExp; ?></div>
            </div>
        </li>
    </div>

         <div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Extra-curricular/co-curricular/Sports Achievement: </strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
        <li style="width: 100%">
            <div class="colums-width" style="width: 100%">
                <label style="width:auto;padding: 0px;text-align: left;">1. </label>
                <div class="form-details" style="width: 100%"><?php echo $SSN_1extraCurr; ?></div>
            </div>
        </li>
    
        <li style="width: 100%">
            <div class="colums-width" style="width: 100%">
                <label style="width:auto;padding: 0px;text-align: left;">2. </label>
                <div class="form-details" style="width: 100%"><?php echo $SSN_2extraCurr; ?></div>
            </div>
        </li>
    
        <li style="width: 100%">
            <div class="colums-width" style="width: 100%"> 
                <label style="width:auto;padding: 0px;text-align: left;">3. </label>
                <div class="form-details" style="width: 100%"><?php echo $SSN_3extraCurr; ?></div>
            </div>
        </li>
    
        <li style="width: 100%">
            <div class="colums-width" style="width: 100%">
                <label style="width:auto;padding: 0px;text-align: left;">4. </label>
                <div class="form-details" style="width: 100%"><?php echo $SSN_4extraCurr; ?></div>
            </div>
        </li>
        </div>


         <div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Project Work In UG: </strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
        <li style="width: 100%;">
            <div class="colums-width" style="width: 100%;">
                <label style="width: 100%;text-align:left;">1.Title of the Project: </label>
                <div class="form-details" style="width: 100%;"><?php echo $SSN_projTitle; ?></div>
            </div>
        </li>
        <li style="width: 100%;">
            <div class="colums-width" style="width: 100%;">
                <label style="width: 100%;text-align:left;">1.Description of the Project: </label>
                <div class="form-details" style="width: 100%;"><?php echo $SSN_projDesc; ?></div>
            </div>
        </li>

        <li style="width: 100%;" style="width: 100%;">
            <div class="colums-width">
                <label style="width: 100%;text-align:left;">2.Title of the Project: </label>
                <div class="form-details" style="width: 100%;"><?php echo $SSN_projTitle2; ?></div>
            </div>
        </li>
        <li style="width: 100%;">
            <div class="colums-width" style="width: 100%;">
                <label style="width: 100%;text-align:left;">2.Description of the Project: </label>
                <div class="form-details" style="width: 100%;"><?php echo $SSN_projDesc2; ?></div>
            </div>
        </li>
       </div>


          <div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Other Details: </strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>

    	<li style="width:100%">
			<div class="colums-width"  style="width:100%">
				<label style="width:100%;padding: 0 0 5px;text-align: left;">Why do you want to do an MBA? What do you know about MBA program? : </label>
				<div class="form-details" style="width:100%;"><?php echo $SSN_Other1; ?></div>
			</div>
		</li>
        <li style="width:100%">
            <div class="colums-width"  style="width:100%">
                <label style="width:100%;padding: 0 0 5px;text-align: left;">Please support your application with any other information that you feel is required.: </label>
                <div class="form-details" style="width:100%;"><?php echo $SSN_Other2; ?></div>
            </div>
        </li>
	   </div>
	</ul>
<div>
<div id="responsibilityList">
    <li>
                        <label style="font-weight:bold; width:700px">DECLARATION:</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                            All entries made in the application form are true to the best of my knowledge and belief. I am willing to produce original
certificates on demand at any time. I also undertake that I shall abide by the rules and regulations of SSN SoM.
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
                        }else{
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
    </div>

<style type="text/css">
   #custom-form-content ul.adjust_ul li{width: 49%;float: left;margin-right: 8px;margin-bottom: 15px;}
   #custom-form-content ul.adjust_ul li label{width:  215px;float:left;text-align: right;}
   #custom-form-content .form-details{width: 430px;}
   .reviewChildLeftCol{margin-right: 10px;}
   .reviewChildLeftCol span{word-wrap: break-word;}
   .reviewLeftCol{width: 769px;}
   #custom-form-content ul.adjust_ul li.heading_li {
    width: 100%;
    background: #f1f1f1;
    padding: 6px 0px;
    text-indent: 15px;
    font-weight: 600;
}
</style>
