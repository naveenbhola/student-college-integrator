<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <img src="/public/images/onlineforms/institutes/mica/logo2_2014.gif" alt="Mudra Institute of Communications, Ahmedabad (MICA) Application Form-PGDM" />
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?> 
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: PGDM <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2015";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2017";}?></div>  
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
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
                        <label>Religion:</label>
                        <span><?php echo $religion;?></span>
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
                    
                    <?php //endif;?>
                </ul>
            </div>
            <!--<div class="profilePic">
                <img width="195" height="192" src="<?php echo $profileImage;?>" alt="Profile Pic" />
            </div>-->
            <div class="picBox" style="border:none"></div>
        </div>
        <!--Personal Info Ends here--> <div class="reviewTitleBox">
                <strong>Additional Personal Information:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>-->
            </div>
	    
	      <ul class="reviewChildLeftCol">
                <?php //if($profile_data['gender']):?>
                    <li>
                        <label>Annual Income of Parents (Individually or combined)/ Guardian:</label>
                        <span><?=$incomeMICA; ?></span>
                    </li>
                    <?php //endif;?>
                    <?php //if($profile_data['email']):?>
                    <li><?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
                        <label>Name of Candidate: </label>
                        <span><?php echo $salutationMICA.'. '.$userName;?></span>
                    </li>
                    <?php //endif;?>
                   
                    <?php //if($profile_data['applicationCategory']):?>
                    <li>
                        <label>Age:</label>
                        <span><?php echo $ageMICA; ?></span>
                    </li>
                    <?php //endif;?>
                </ul>
            
		  <ul class="reviewChildRightCol">
	    <?php
	    if($legalGuardianMICA=='Father'){
	    ?>
	 
	    <li>
                    <label>Qualification of Father: </label>
                    <span><?=$qualificationGuardianMICA;?></span>
               
	    </li>
	    <?php 
	    }else if($legalGuardianMICA=='Mother'){
	    ?>  
	    <li>
                    <label>Qualification of Mother: </label>
                    <span><?=$qualificationGuardianMICA;?></span>
	    </li>
	<?php
	    }else{ ?>
	    <li>
		
                    <label>Name of Guardian: </label>
                    <span><?=$guardianNameMICA;?></span>
	    </li>
	    <li>
                    <label>Occupation of Guardian: </label>
                    <span><?=$occupationGuardianMICA;?></span>
              
	    </li>
	    <li>
	
                    <label>Qualification of Guardian: </label>
                    <span><?=$qualificationGuardianMICA;?></span>
             
	    </li>
	    
	    <?php }
	    ?>
 </ul>
 </div>
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
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
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
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
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
                    <?php //endif;?>
                </ul>
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
                    	<p class="educationCol educationTitleColFirst" style="width: 170px">Course name</p>
                        <p class="educationCol" style="width: 170px">School/ Institute</p>
                        <p class="educationCol" style="width: 170px">Board/ University</p>
                        <p class="educationYearCol" style="width: 52px">Year</p>
                        <p class="educationSmallCol" style="width: 80px">Percentage/ Grade</p>
			<p class="educationCol" style="width: 125px; padding-right: 0">Major Subject/Discipline</p>
			
                    </div>
                   
                <ul>
                	<li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 10<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst word-wrap" style="width: 170px"><?php echo $class10ExaminationName;?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo $class10School;?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo $class10Board;?></span>
                            <span class="educationYearCol" style="width: 52px"><?php echo $class10Year;?></span>
                            <span class="educationSmallCol" style="width: 80px"><?php echo $class10Percentage;?></span>
			     <span class="educationSmallCol" style="width: 125px; padding-right: 0"></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 12<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst word-wrap" style="width: 170px"><?php echo $class12ExaminationName;?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo $class12School;?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo $class12Board;?></span>
                            <span class="educationYearCol" style="width: 52px"><?php echo $class12Year;?></span>
                            <span class="educationSmallCol" style="width: 80px"><?php echo $class12Percentage;?></span>
			     <span class="educationSmallCol" style="width: 125px;padding-right:0"></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong>Graduation</strong></label>
                            <span class="educationCol educationColFirst word-wrap" style="width: 170px"><?php echo $graduationExaminationName;?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo $graduationSchool;?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo $graduationBoard;?></span>
                            <span class="educationYearCol" style="width: 52px"><?php echo $graduationYear;?></span>
                            <span class="educationSmallCol" style="width: 80px"><?php echo $graduationPercentage;?></span>
			    <span class="educationSmallCol" style="width: 125px;padding-right:0"><?php echo $gradCourseSpecializationMICA?></span>
						</div>
                    </li>
                    <?php //if(!empty($exam_array)):?>
                    <?php //$count_exam = count($exam_array); 
			for($j=1;$j<=4;$j++):?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
		     <?php if(${'pgDegreeMICA_mul_'.$j}=='Yes'){$pgDegreeType = 'PG Degree';}else{$pgDegreeType='Any other Qualification/s';} ?>
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong><?php echo "Additional$j"?></strong></label>
                            <span class="educationCol educationColFirst word-wrap" style="width: 170px"><?php echo $pgDegreeType;?> :<?php echo ${'graduationExaminationName_mul_'.$j};?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo ${'graduationSchool_mul_'.$j};?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo ${'graduationBoard_mul_'.$j};?></span>
                            <span class="educationYearCol" style="width: 52px"><?php echo ${'graduationYear_mul_'.$j};?></span>
                            <span class="educationSmallCol" style="width: 80px"><?php echo ${'graduationPercentage_mul_'.$j};?></span>
			     <span class="educationSmallCol" style="width: 125px;padding-right: 0"><?php echo ${'otherCourseSpecializationMICA_mul_'.$j};?></span>
						</div>
                    </li>
                    <?php endif;endfor; //endif;?>
                </ul>
                <div class="clearFix"></div>
             </div>
	     <div class="spacer20 clearFix"></div>
    

        </div>
        <!--Education Info Ends here-->
        
        <!--Work Exp Info Starts here-->
    	<div class="workInfoSection">
        	<div class="reviewTitleBox">
                <strong>Work Experience:</strong>
            </div>  <?php if(${'experienceCheckMICA'}!='No'): ?>
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
                            <label>Annual Income:</label>
                            <div style="width:290px; float:left"><?php echo $annualSalaryMICA;?></div>
						</div>
                        	
                    	<div class="formColumns">
                    	<label class="timePeriodLabel3">Experience in Months:</label>
                        <div style="width:290px; float:left"><?php echo $experienceMICA;?></div>
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
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim($weRoles));?></li>
            </ul>
        </div> 
    </div>   <?php endif;?>
    <?php //if(!empty($wecompany_array)):?>
    <?php //$count_company = count($wecompany_array);
	for($i=1;$i<=3;$i++):?>
    <?php if((!empty(${'weCompanyName_mul_'.$i})) && ${'experienceCheckMICA_mul_'.$i}!='No'):?>
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
                            <label>Annual Income:</label>
                            <span><?php echo ${'annualSalaryMICA_mul_'.$i};?></span>
						</div>
                        	
                    	<div class="formColumns">
                    	<label class="timePeriodLabel3">Experience in Months:</label>
                        <span><?php echo ${'experienceMICA_mul_'.$i};?></span>
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
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></li>
            </ul>
        </div>
    </div>
    <?php endif;endfor;//endif;?>
   </div> 
   
    
	<div class="familyInfoSection">
			<div class="reviewTitleBox">
				<strong>GD/PI Location:</strong>
			</div>
			<div id="responsibilityList">
            <ul>
                <li><?=$gdpiLocation?></li>
            </ul>
        </div>
	</div>
	

	<?php
		$testsArray = explode(",",$testNamesMICA);  
	?>
	<div class="familyInfoSection">
			<div class="reviewTitleBox">
				<strong>Entrance Test Appeared:</strong>
			</div>
			<?php if(in_array("CAT",$testsArray)): ?>
			<div class="educationBlock"  style="margin-bottom: 15px">
				<ul class="qualifyingDetails">
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CAT 2014 Registration Number:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catRollNumberAdditional;?><?php endif; ?></span>
					</div>
					</li>
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CAT 2014 Quantitative Ability and Data Interpretation:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $qadiCATScoreMICA;?><?php endif; ?></span>
						</div>
						
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CAT 2014 QADI Percentile: </label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $qadiCATPercentileMICA;?><?php endif; ?></span>
						</div>
					</li>
					
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CAT 2014 Verbal Ability and Logical Reasoning:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $valrCATScoreMICA;?><?php endif; ?></span>
						</div>
				
					
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CAT 2014 VALR Percentile: </label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $valrCATPercentileMICA;?><?php endif; ?></span>
						</div>
					</li>
					
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CAT 2014 Total Score:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catScoreAdditional;?><?php endif; ?></span>
						</div>
						
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CAT 2014 Total Percentile:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catPercentileAdditional;?><?php endif; ?></span>
						</div>
					</li>
				
				</ul>
			</div>
			<?php endif; ?>
			<?php if(in_array("GMAT",$testsArray)): ?>
				<div class="educationBlock"  style="margin-bottom: 15px">
		
				<ul class="qualifyingDetails">
					<li>
							<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">GMAT Test Date :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $gmatDateOfExaminationAdditional;?><?php endif; ?></span>
							</div>
					</li>
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Verbal :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $verbalGMATScoreMICA;?><?php endif; ?></span>
						</div>
						
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Verbal Percentage :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $verbalGMATPercentMICA;?><?php endif; ?></span>
						</div>
					</li>
				
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Quantitative :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $quantGMATScoreMICA;?><?php endif; ?></span>
						</div>
							<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Quantitative Percentage :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $quantGMATPercentMICA;?><?php endif; ?></span>
							</div>
					</li>
				
			
					<li>
							<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Analytical Writing :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $analyticalGMATScoreMICA;?><?php endif; ?></span>
							</div>
							<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Analytical Writing Percentage :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $analyticalGMATPercentMICA;?><?php endif; ?></span>
					
							</div>
					</li>
				
				
					<li>
							<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Integrated Reasoning :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $reasoningGMATScoreMICA;?><?php endif; ?></span>
							</div>
					
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Integrated Reasoning Percentage :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $reasoningGMATPercentMICA;?><?php endif; ?></span>
						</div>
					</li>
				
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Total :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $gmatScoreAdditional;?><?php endif; ?></span>
						</div>
					
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Total Percentage :</label>
						<span><?php if(in_array("GMAT",$testsArray)): ?><?php echo $gmatPercentileAdditional;?><?php endif; ?></span>
						</div>
					</li>
				
				</ul>
			</div>
			<?php endif; ?>
			<?php if(in_array("XAT",$testsArray)): ?>
			<div class="educationBlock"  style="margin-bottom: 15px">
				<ul class="qualifyingDetails">
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 ID:</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatRollNumberAdditional;?><?php endif; ?></span>
					</div>
					</li>
					
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 Quantitative Ability :</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $quantXATScore2013MICA;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 Quantitative Ability Percentile :</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $quantXATPercent2013MICA;?><?php endif; ?></span>
					</div>
					</li>
				
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 English Language and Logical Reasoning :</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $ellrMATScore2013MICA;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 ELLR Percentile :</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $ellrMATPercent2013MICA;?><?php endif; ?></span>
					</div>
					</li>
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 Decision Making :</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $decsskillsMATScore2013MICA;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 Decision Making Percentile :</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $decsMATPercent2013MICA;?><?php endif; ?></span>
					</div>
					</li>
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 Total :</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatScoreAdditional;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2014 Total Percentile :</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatPercentileAdditional;?><?php endif; ?></span>
					</div>
					</li>
				
				</ul>
			</div>
			<?php endif; ?>
			<?php if(in_array("XAT2014",$testsArray)): ?>
			<div class="educationBlock"  style="margin-bottom: 15px">
				<ul class="qualifyingDetails">
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 ID:</label>
						<span><?php echo $Xat2014Id;?></span>
					</div>
					</li>
					
				<!---
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 Quantitative Ability :</label>
						<span><?php echo $quantXATScore2014MICA;?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 Quantitative Ability Percentile :</label>
						<span><?php echo $quantXATPercent2014MICA;?></span>
					</div>
					</li>
				
			
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 English Language and Logical Reasoning :</label>
						<span><?php echo $ellrMATScore2014MICA;?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 ELLR Percentile :</label>
						<span><?php echo $ellrMATPercent2014MICA;?></span>
					</div>
					</li>
				
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 Decision Making :</label>
						<span><?php echo $decsskillsMATScore2014MICA;?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 Decision Making Percentile :</label>
						<span><?php echo $decsMATPercent2014MICA;?></span>
					
					</div>
					</li>
				
			
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 Total :</label>
						<span><?php echo $XATScore2014MICA;?></span>
						</div>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">XAT 2015 Total Percentile :</label>
						<span><?php echo $XATPercent2014MICA;?></span>
						</div>
					</li>
				
				</ul>
			-->
			</div>
			
			<?php endif; ?>
			<?php if(in_array("MAT",$testsArray)): ?>
		<div class="educationBlock"  style="margin-bottom: 15px">
				<ul class="qualifyingDetails">
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">MAT FORM NUMBER:</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $formMATnumberMICA;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ROLL NUMBER :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matRollNumberAdditional;?><?php endif; ?></span>
					</div>
					</li>
				
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">MAT 2014 TEST DATE :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matDateOfExaminationAdditional;?><?php endif; ?></span>
						</div>
					</li>
				
			
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">MAT 2014 Language Comprehension :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $langMATcomprehensionScoreMICA;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">MAT 2014 Language Comprehension Percentage :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $langMATcomprehensionPercentageMICA;?><?php endif; ?></span>
					</div>
					</li>
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Mathematical Skills :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $mathskillsMATScoreMICA;?><?php endif; ?></span>
						</div>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Mathematical Skills Percentage :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $mathskillsMATPercentMICA;?><?php endif; ?></span>
						</div>
					</li>
				
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Data Analysis and Sufficiency :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $DASMATScoreMICA;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">DAS Percentage :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $DASMATPercentMICA;?><?php endif; ?></span>
					</div>
					</li>
				
				
					<li>
					<div class="formColumns">
 						<label style="width:330px !important; font-weight:bold !important">Intelligence and Critical Reasoning :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $reasoningMATScoreMICA;?><?php endif; ?></span>
					
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ICR Percentage :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $reasoningMATPercentileMICA;?><?php endif; ?></span>
					</div>
				</li>
				
				
					<li>
					<div class="formColumns">
 						<label style="width:330px !important; font-weight:bold !important">Indian and Global Environment :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $IGEMATScoreMICA;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">IGE Percentage :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $IGEMATPercentMICA;?><?php endif; ?></span>
					
					</div>
					</li>
				
				
					<li>
					<div class="formColumns">
 						<label style="width:330px !important; font-weight:bold !important">Composite Score :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matScoreAdditional;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Composite Score Percentage :</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matPercentileAdditional;?><?php endif; ?></span>
					
					</div>
				</li>
				
				</ul>
			</div>
			<?php endif; ?>
			<?php if(in_array("CMAT",$testsArray)): ?>
			<div class="educationBlock"  style="margin-bottom: 15px">
				<ul class="qualifyingDetails">
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CMAT Roll No:</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatRollNumberAdditional;?><?php endif; ?></span>
						</div>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CMAT Rank:</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatRankAdditional;?><?php endif; ?></span>
						</div>
					</li>
					
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">CMAT Date of Exam:</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatDateOfExaminationAdditional;?><?php endif; ?></span>
						</div>
				
					</li>
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Quantitative Techniques and Data Interpretation :</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $quantCMATScoreMICA;?><?php endif; ?></span>
						</div>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Logical Reasoning :</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $logicalCMATReasoningMICA;?><?php endif; ?></span>
						</div>
					</li>
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Language Comprehension :</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $langCMATScoreMICA;?><?php endif; ?></span>
						</div>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">General Awareness :</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $generalCMATScoreMICA;?><?php endif; ?></span>
						</div>
					</li>
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">Total Score :</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatScore;?><?php endif; ?></span>
					</div>
					</li>
				</ul>
			</div>
			<?php endif; ?>
			<?php if(in_array("ATMA",$testsArray)): ?>
			<div class="educationBlock"  >
				<ul class="qualifyingDetails">
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Roll No:</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $atmaRollNumberAdditional;?><?php endif; ?></span>
						</div>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Test Date:</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $atmaDateOfExaminationAdditional;?><?php endif; ?></span>
						</div>
					</li>
				
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Quantitative :</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $quantATMAcoreMICA;?><?php endif; ?></span>
						</div>
						<div class="formColumns">
					
						<label style="width:330px !important; font-weight:bold !important">ATMA Quantitative Percentile :</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $quantATMAPercentMICA;?><?php endif; ?></span>
					</div>
					</li>
				
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Verbal :</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $verbalATMAScoreMICA;?><?php endif; ?></span>
						</div>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Verbal Percentile :</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $verbalATMAPercentMICA;?><?php endif; ?></span>
						</div>
						</li>
				
				
					<li>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Analytical :</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $analyticalATMAScoreMICA;?><?php endif; ?></span>
					</div>
					<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Analytical Percentile:</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $analyticalATMAPercentMICA;?><?php endif; ?></span>
					</div>
					</li>
				
					<li>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Total Scaled Score :</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $atmaScoreAdditional;?><?php endif; ?></span>
						</div>
						<div class="formColumns">
						<label style="width:330px !important; font-weight:bold !important">ATMA Total Scaled Percentile:</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $atmaPercentileAdditional;?><?php endif; ?></span>
						</div>
					</li>
				
				</ul>
			</div>
			<?php endif; ?>
	
		
		
		<div class="familyInfoSection">
			<div class="reviewTitleBox">
				<strong>Additional Educational Fields:</strong>
			</div>
			
			<div class="educationBlock" style="margin-bottom: 15px" >
				<ul class="qualifyingDetails">
					<li>
					<div class="formColumns" style="width: 900px">
						<label style="width:150px !important; font-weight:bold !important">Class 10th School Address:</label>
						<span style="width: 740px"><?php echo $schooladdress10MICA;?></span>
					</div>
					</li>
					
				
					<li>
						<div class="formColumns">
						<label style="width:150px  !important; font-weight:bold !important">City:</label>
						<span><?php echo $schoolcity10MICA;?></span>
						</div>
						<div class="formColumns">
						<label style="width:150px !important; font-weight:bold !important">Pincode:</label>
						<span><?php echo $pincode10MICA;?></span>
						</div>
					</li>
				</ul>
			</div>
			
			<div class="educationBlock" style="margin-bottom: 15px">
				<ul class="qualifyingDetails">
					<li>
						<div class="formColumns" style="width: 900px">
						<label style="width:150px !important; font-weight:bold !important">Class 12th School Address:</label>
						<span style="width: 740px"><?php echo $schooladdress12MICA;?></span>
						</div>
					</li>
			
					<li>
						<div class="formColumns">
						<label style="width:150px !important; font-weight:bold !important">City:</label>
						<span><?php echo $schoolcity12MICA;?></span>
						</div>
						
						<div class="formColumns">
						<label style="width:150px !important; font-weight:bold !important">Pincode:</label>
						<span><?php echo $pincode12MICA;?></span>
						</div>
					</li>
				</ul>
			</div>
			
			<div class="educationBlock" style="margin-bottom: 15px" >
				<ul class="qualifyingDetails">
					<li>
						<div class="formColumns" style="width: 900px">
						<label style="width:150px !important; font-weight:bold !important">Graduation College Address:</label>
						<span style="width: 740px"><?php echo $graduationaddressMICA;?></span>
						</div>
					</li>
					
					<li>
						<div class="formColumns">
						<label style="width:150px !important; font-weight:bold !important">City:</label>
						<span><?php echo $graduationcityMICA;?></span>
						</div>
						<div class="formColumns">
						<label style="width:150px !important; font-weight:bold !important">Pincode:</label>
						<span><?php echo $graduationPincodeMICA;?></span>
						</div>
					</li>
				</ul>
			</div>
			
			
			
			<div class="educationBlock" style="margin-bottom: 15px" >
				<ul class="qualifyingDetails">
					<li>
						<div class="formColumns" style="width: 900px">
						<label style="width:150px !important; font-weight:bold !important">PG/Masters College Address:</label>
						<span style="width:740px;"><?php echo $PGaddressMICA;?></span>
						</div>
					</li>
				
					<li>
						<div class="formColumns">
						<label style="width:150px !important; font-weight:bold !important">City:</label>
						<span><?php echo $PGcityMICA;?></span>
						</div>
						<div class="formColumns">
						<label style="width:150px !important; font-weight:bold !important">Pincode:</label>
						<span><?php echo $PGPincodeMICA;?></span>
						</div>
					</li>
				</ul>
			</div>
		
	<div id="custom-form-content">
			<div class="reviewTitleBox">
				<strong>Other Details</strong>
			</div>				
			
<ul >
    <?php $source = explode(',',$sourceMICA)?>
	     <li >
            	
		<label>How did you come to know about MICA: </label>
                <div class="form-details"><?php echo str_replace(',Others','',$sourceMICA);?>
		</div>
		
	     </li>
	     <?php if(in_array('Others',$source)):?>
	     <li >
		<label>if other, please specify: </label>
                <div class="form-details"><?php echo str_replace(',Others','',$otherSourceMICA);?>
		</div>
		
	     </li>
	     <?php endif;?>
	     <?php $publication = explode(',',$sourcePublicationMICA)?>
	     <li>
		<label>In what publication did you read the MICA admissions announcement: </label>
                <div class="form-details"><?php echo str_replace(',Others','',$sourcePublicationMICA);?>
		</div>
		
	     </li>
	     <?php if(in_array('Others',$publication)):?>
	     <li >
		<label>if other, please specify: </label>
                <div class="form-details"><?php echo str_replace(',Others','',$otherPublicationSourceMICA);?>
		</div>
		
	     </li>
	     <?php endif;?>
	 
            <li >
            	<div class="reviewTitleBox" >
				<strong>Declaration</strong>
			</div>	
            	
		<div  style="float: left; width: 100%">I, &nbsp; <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span> , certify that the information furnished in this application is true to the best of my knowledge. My application may be rejected and admission shall be cancelled if any information provided herein is found to be incorrect at any time during or after admission.Detailed Terms and Conditions can be found <a href="/public/onlineforms_TnC/mica/tnc.doc" target="_blank">Here.</a></div>
		
                
                <div class="spacer15 clearFix"></div>
                <label  >Place:</label>
                <div class="form-details" ><?php if(isset($firstName) && $firstName!='') {echo $Ccity;} ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label >Date:</label>
                <div class="form-details"><?php
                                       if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
                                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                                         }
                                ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label >Signature of the Candidate:</label>
                <div class="form-details"><?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></div>
            </li>
        </ul>
</div>
   </div> </div>
