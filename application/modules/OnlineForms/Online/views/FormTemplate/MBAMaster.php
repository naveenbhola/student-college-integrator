    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
            <div class="reviewTitleBox">
                <strong>Personal Details:</strong>
		<?php if($showEdit=='true'){ ?><a onClick="setCookie('redirectViewForm','true');" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/1" title="Edit">edit</a><?php  } ?>
            </div>
        
            <div class="reviewLeftCol">
                <div class="formGreyBox">
                    <ul>
                        <li>
                            <div class="personalInfoCol">
                                <label>First Name:</label>
                                <span><?php echo (isset($firstName) && $firstName!='')?$firstName:'';?></span>
                            </div>
                            
                            <div class="personalInfoCol">
                                <label>Middle Name:</label>
                                <span><?php echo (isset($middleName) && $middleName!='')?$middleName:'';?></span>
                            </div>
                            
                            <div class="personalInfoCol">
                                <label>Last Name:</label>
                                <span><?php echo (isset($lastName) && $lastName!='')?$lastName:'';?></span>
                            </div>
                            <div class="clearFix"></div>
                        </li>
                    </ul>
                </div>
                
                <div class="spacer20 clearFix"></div>
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Gender:</label>
                        <span><?php echo (isset($gender) && $gender!='')?$gender:'';?></span>
                    </li>
                    <li>
                        <label>Email Address:</label>
                        <span><?php echo (isset($email) && $email!='')?$email:'';?></span>
                    </li>
		    <?php if(isset($courseName) && $courseName!=''){ ?>
                    <li>
                        <label>Course Applied For:</label>
                        <span><?php echo $courseName;?></span>
                    </li>
		    <?php } ?>
                    <li>
                        <label>Application Category:</label>
                        <span><?php echo (isset($applicationCategory) && $applicationCategory!='')?$applicationCategory:'';?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Date of birth:</label>
                        <span><?php echo (isset($dateOfBirth) && $dateOfBirth!='')?$dateOfBirth:'';?></span>
                    </li>
                    <li>
                        <label>Alternate Email:</label>
                        <span><?php echo (isset($altEmail) && $altEmail!='')?$altEmail:'';?></span>
                    </li>
                    <li>
                        <label>Marital Status:</label>
                        <span><?php echo (isset($maritalStatus) && $maritalStatus!='')?$maritalStatus:'';?></span>
                    </li>
                    <li>
                        <label>Nationality:</label>
                        <span><?php echo (isset($nationality) && $nationality!='')?$nationality:'';?></span>
                    </li>
                    <li>
                        <label>Religion:</label>
                        <span><?php echo (isset($religion) && $religion!='')?$religion:'';?></span>
                    </li>
                </ul>
            </div>
            <div class="profilePic">
                <img src="<?php echo (isset($profileImage) && $profileImage!='')?getMediumImage($profileImage):'';?>" alt="Profile Pic" />
            </div>
        </div>
        <!--Personal Info Ends here-->
        
        <!--Contact Info Starts here-->
        <div class="contactInfoSection">
            <div class="reviewTitleBox">
                <strong>Contact Information:</strong>
		<?php if($showEdit=='true'){ ?><a onClick="setCookie('redirectViewForm','true');" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/2" title="Edit">edit</a><?php  } ?>
            </div>
        	<h3 class="mL15">Permanent Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>House Number:</label>
                        <span><?php echo (isset($houseNumber) && $houseNumber!='')?$houseNumber:'';?></span>
                    </li>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo (isset($area) && $area!='')?$area:'';?></span>
                    </li>
                    <li>
                        <label>Country:</label>
                        <span><?php echo (isset($country) && $country!='')?$country:'';?></span>
                    </li>
                    <li>
                        <label>City:</label>
                        <span><?php echo (isset($city) && $city!='')?$city:'';?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo (isset($streetName) && $streetName!='')?$streetName:'';?></span>
                    </li>
                    <li>
                        <label>State:</label>
                        <span><?php echo (isset($state) && $state!='')?$state:'';?></span>
                    </li>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo (isset($pincode) && $pincode!='')?$pincode:'';?></span>
                    </li>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo (isset($landlineSTDCode) && $landlineSTDCode!='')?$landlineSTDCode:'';?>-<?php echo (isset($landlineNumber) && $landlineNumber!='')?$landlineNumber:'';?></span>
                    </li>
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo (isset($mobileISDCode) && $mobileISDCode!='')?$mobileISDCode:'';?>-<?php echo (isset($mobileNumber) && $mobileNumber!='')?$mobileNumber:'';?></span>
                    </li>
                </ul>
            </div>
            
            <div class="spacer5 clearFix"></div>
            <h3 class="mL15">Correspondence Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>House Number:</label>
                        <span><?php echo (isset($ChouseNumber) && $ChouseNumber!='')?$ChouseNumber:'';?></span>
                    </li>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo (isset($Carea) && $Carea!='')?$Carea:'';?></span>
                    </li>
                    <li>
                        <label>Country:</label>
                        <span><?php echo (isset($Ccountry) && $Ccountry!='')?$Ccountry:'';?></span>
                    </li>
                    <li>
                        <label>City:</label>
                        <span><?php echo (isset($Ccity) && $Ccity!='')?$Ccity:'';?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo (isset($CstreetName) && $CstreetName!='')?$CstreetName:'';?></span>
                    </li>
                    <li>
                        <label>State:</label>
                        <span><?php echo (isset($Cstate) && $Cstate!='')?$Cstate:'';?></span>
                    </li>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo (isset($Cpincode) && $Cpincode!='')?$Cpincode:'';?></span>
                    </li>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo (isset($landlineSTDCode) && $landlineSTDCode!='')?$landlineSTDCode:'';?>-<?php echo (isset($landlineNumber) && $landlineNumber!='')?$landlineNumber:'';?></span>
                    </li>
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo (isset($mobileISDCode) && $mobileISDCode!='')?$mobileISDCode:'';?>-<?php echo (isset($mobileNumber) && $mobileNumber!='')?$mobileNumber:'';?></span>
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
                        <span><?php echo (isset($fatherName) && $fatherName!='')?$fatherName:'';?></span>
                    </li>
                    <li>
                        <label>Occupation:</label>
                        <span><?php echo (isset($fatherOccupation) && $fatherOccupation!='')?$fatherOccupation:'';?></span>
                    </li>
                    <li>
                        <label>Designation:</label>
                        <span><?php echo (isset($fatherDesignation) && $fatherDesignation!='')?$fatherDesignation:'';?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Mother's Name:</label>
                        <span><?php echo (isset($MotherName) && $MotherName!='')?$MotherName:'';?></span>
                    </li>
                    <li>
                        <label>Occupation:</label>
                        <span><?php echo (isset($MotherOccupation) && $MotherOccupation!='')?$MotherOccupation:'';?></span>
                    </li>
                    <li>
                        <label>Designation:</label>
                        <span><?php echo (isset($MotherDesignation) && $MotherDesignation!='')?$MotherDesignation:'';?></span>
                    </li>
                </ul>
            </div>
        </div>
        <!--Family Info Ends here-->
        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Education Information:</strong>
		<?php if($showEdit=='true'){ ?><a onClick="setCookie('redirectViewForm','true');" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/3" title="Edit">edit</a><?php  } ?>
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
                            <span class="educationCol educationColFirst"><?php echo $class10ExaminationName; ?></span>
                            <span class="educationCol"><?php echo $class10School; ?></span>
                            <span class="educationCol"><?php echo $class10Board; ?></span>
                            <span class="educationYearCol"><?php echo $class10Year; ?></span>
                            <span class="educationSmallCol"><?php echo $class10Percentage; ?>%</span>
			</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 12<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst"><?php echo $class12ExaminationName; ?></span>
                            <span class="educationCol"><?php echo $class12School; ?></span>
                            <span class="educationCol"><?php echo $class12Board; ?></span>
                            <span class="educationYearCol"><?php echo $class12Year; ?></span>
                            <span class="educationSmallCol"><?php echo $class12Percentage; ?>%</span>
			</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                       	<label class="labelBg paddTop4"><strong>Graduation</strong></label>
                            <span class="educationCol educationColFirst"><?php echo $graduationExaminationName; ?></span>
                            <span class="educationCol"><?php echo $graduationSchool; ?></span>
                            <span class="educationCol"><?php echo $graduationBoard; ?></span>
                            <span class="educationYearCol"><?php echo $graduationYear; ?></span>
                            <span class="educationSmallCol"><?php echo $graduationPercentage; ?>%</span>
			</div>
                    </li>

		    <?php for($i=1;$i<=4;$i++){ 
			  $variableExamName = "graduationExaminationName_mul_".$i;
			  $variableSchoolName = "graduationSchool_mul_".$i;
			  $variableBoard = "graduationBoard_mul_".$i;
			  $variableYear = "graduationYear_mul_".$i;
			  $variablePercentage = "graduationPercentage_mul_".$i;
		      ?>
		     <?php if( (isset($$variableExamName) && $$variableExamName!='') || (isset($$variableSchoolName) && $$variableSchoolName!='') || (isset($$variableBoard) && $$variableBoard!='') ){ ?>
                    <li>
                        <div class="formAutoColumns">
			    <label class="labelBg paddTop4"><strong></strong></label>
                            <span class="educationCol educationColFirst"><?php echo $$variableExamName; ?></span>
                            <span class="educationCol"><?php echo $$variableSchoolName; ?></span>
                            <span class="educationCol"><?php echo $$variableBoard; ?></span>
                            <span class="educationYearCol"><?php echo $$variableYear; ?></span>
                            <span class="educationSmallCol"><?php echo $$variablePercentage; ?>%</span>
			</div>
                    </li>
		    <?php } ?>
		    <?php } ?>

                </ul>
                <div class="clearFix"></div>
             </div>
             <div class="spacer25 clearFix"></div>
             <h3 style="margin-left:5px">Qualifying Examination:</h3>

	     <?php $qualifyExamsArr = array('cat','mat','gmat');
		    for($i=0;$i<count($qualifyExamsArr);$i++){

			$variableDateOfExamination = $qualifyExamsArr[$i]."DateOfExamination";
			$variableScore = $qualifyExamsArr[$i]."Score";
			$variableRollNumber = $qualifyExamsArr[$i]."RollNumber";
			$variablePercentile = $qualifyExamsArr[$i]."Percentile";

		    if( (isset($$variableDateOfExamination) && $$variableDateOfExamination!='') || (isset($$variableScore) && $$variableScore!='') || (isset($$variableRollNumber) && $$variableRollNumber!='') || (isset($$variablePercentile) && $$variablePercentile!='') ) { ?>
             <div class="educationBlock">
		    <div class="educationTitle educationTitleBg">
			  <p class="educationCol widthAuto"><?php echo strtoupper($qualifyExamsArr[$i]);?> Score Details</p>
		    </div>
                   
		    <ul class="qualifyingDetails">
			    <li>
			    <div class="formColumns">
				<label><strong>Date of examination:</strong></label>
				<span><?php echo (isset($$variableDateOfExamination) && $$variableDateOfExamination!='')?$$variableDateOfExamination:'';?></span>
			    </div>
			    
			    <div class="formColumns">
				<label><strong>Score:</strong></label>
				<span><?php echo (isset($$variableScore) && $$variableScore!='')?$$variableScore:'';?></span>
			    </div>
			</li>
			
			<li>
			    <div class="formColumns">
				<label><strong>Roll Number:</strong></label>
				<span><?php echo (isset($$variableRollNumber) && $$variableRollNumber!='')?$$variableRollNumber:'';?></span>
			    </div>
			    
			    <div class="formColumns">
				<label><strong>Percentile:</strong></label>
				<span><?php echo (isset($$variablePercentile) && $$variablePercentile!='')?$$variablePercentile:'';?>%</span>
			    </div>
			</li>
		    </ul>
		    <div class="clearFix"></div>
             </div>
	     <?php } } ?>


        </div>
        <!--Education Info Ends here-->
        
        <!--Work Exp Info Starts here-->
    	<div class="workInfoSection" style="font-size:14px;">

            <div class="reviewTitleBox">
                <strong>Work Experience:</strong>
            </div>
        

	    <?php for($i=0;$i<=4;$i++){ 
		  if($i==0){
			$variableC = "weCompanyName";
			$variableD = "weDesignation";
			$variableL = "weLocation";
			$variableT = "weTimePeriod";
			$variableF = "weFrom";
			$variableR = "weRoles";
			$variableTi = "weTill";
		  }
		  else{
			$variableC = "weCompanyName_mul_".$i;
			$variableD = "weDesignation_mul_".$i;
			$variableL = "weLocation_mul_".$i;
			$variableT = "weTimePeriod_mul_".$i;
			$variableF = "weFrom_mul_".$i;
			$variableTi = "weTill_mul_".$i;
			$variableR = "weRoles_mul_".$i;
		  }
	      ?>
	      <?php if( (isset($$variableC) && $$variableC!='') || (isset($$variableD) && $$variableD!='') || (isset($$variableL) && $$variableL!='') ){ ?>


            <div class="widthAuto">
                <ul>
                	<li>
			    <div class="formColumns">
				<label>Company Name:</label>
				<span><?php echo $$variableC; ?></span>
			    </div>
			    <div class="formColumns">
				<label>Designation:</label>
				<span><?php echo $$variableD; ?></span>
			    </div>    
			</li>
                 
			<li>
			    <div class="formColumns">
				<label>Location:</label>
				<span><?php echo $$variableL; ?></span>
			    </div> 
			    
			    <div class="formColumns">
				<label class="timePeriodLabel">Time Period:</label>
				<?php if($$variableT == "I currently work here"){ ?>
				<input type="checkbox" checked="checked" /> I currently work here<br />
				<?php }else{ ?>
				<input type="checkbox" /> I currently work here<br />
				<?php } ?>
				<div class="workExpDetails">
					<span>From: <?php echo $$variableF; ?></span>
					<span class="mL14">Till: <?php echo $$variableTi; ?></span>
				</div>
			    </div>
			</li>
              </ul>
	    </div>
	    <div class="spacer10 clearFix"></div>
	    <div class="rolesResponsiblity">
		<h3>Roles &amp; Responsibilities:</h3>
		<div id="responsibilityList">
		    <ul>
			  <?php echo nl2br($$variableR); ?>
		    </ul>
		</div>
	    </div>
	    <div class="spacer10 clearFix"></div>

	    <?php } ?>
	    <?php } ?>


     </div>
     <!--Work Exp Info Ends here-->
     
    
    <div class="spacer20 clearFix"></div>
     