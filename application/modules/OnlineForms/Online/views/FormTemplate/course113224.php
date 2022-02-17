   	<!--TAPMI Form Preview Starts here-->
	<link href="/public/css/onlineforms/tapmi/styles.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<div class="previewTetx" style="margin-bottom:5px;">This is how your form will be viewed by the institute</div>
                              <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
          <strong class="editFormLink applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
                              <?php } ?>

    	<div class="previewHeader" style="margin-top:5px;">
        	
        	<div class="headerLeft">
        	<div class="instLogoBox"><img src="/public/images/onlineforms/institutes/tapmi/logo.gif" alt="" /></div>
            <div class="courseNameDetails">
				<div class="courseNameBox">
                	<p class="formTitle">T A PAI MANAGEMENT INSTITUTE, MANIPAL</p>
            		<div class="srNumBox">
                	Application for Admission to <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2012";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2014";}?>
                    <div class="spacer10 clearFix"></div>
                    <span>Application No: <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2012";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2014";}?>_<?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></span>
                </div>        
                </div>
            </div>
            <div class="clearFix"></div>
            
            </div>
            <?php if($profileImage) { ?>
            <div class="picBox">
            	<img width="195" height="192" src="<?php echo $profileImage; ?>" />
            </div>
			<?php } ?>
        </div>
       	
       	<div class="previewBody">
        		<h2>1 . Applicant's Personal Details</h2>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    <tr>
                        <td width="200">Name</td>
                        <td colspan="3"><?php echo $firstName.' '.$middleName.' '.$lastName; ?> </td>
                    </tr>
                    
                    <tr>
                        <td>Gender</td>
                        <td width="250"><?php echo $gender; ?></td>
                        <td width="250">Date of Birth</td>
                        <td><?php echo $dateOfBirth; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Mother Tongue</td>
                        <td><?php echo $motherTongue; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Test Appeared for</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>CAT Registration No.</td>
                        <td width="250"><?php echo $catRollNumberAdditional; ?></td>
                        <td width="250"></td>
                        <td></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Test Appeared for</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>XAT Registration No.</td>
                        <td width="250"><?php echo $xatRollNumber; ?></td>
                        <td width="250"></td>
                        <td></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Test Appeared for</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>GRE Registration No.</td>
                        <td width="250"><?php echo $greRegistrationNumber; ?></td>
                        <td width="250">Total Score</td>
                        <td><?php echo $greScore; ?></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Test Appeared for</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>GMAT Registration No.</td>
                        <td width="250"><?php echo $gmatRollNumberAdditional; ?></td>
                        <td width="250">Total Score</td>
                        <td><?php echo $gmatScoreAdditional; ?></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Test Appeared for</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>MAT Registration No.</td>
                        <td width="250"><?php echo $matRollNumberAdditional; ?></td>
                        <td width="250"></td>
                        <td></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Preferred Center for GD/PI</td>
                            <td colspan="3"><?php echo $gdpiLocation; ?></td>
                        </tr>
                    </thead>
                </table>
            	<div class="spacer25 clearFix"></div>
                
				<h2>2. Passport Details</h2>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    <tr>
                        <td width="200">Passport Number</td>
                        <td><?php echo $passportNumber; ?></td>
                    </tr>
                    <tr>
                        <td width="200">Issued by</td>
                        <td><?php echo $passportIssuedBy; ?></td>
                    </tr>
                    <tr>
                        <td width="200">Date of Expiry</td>
                        <td><?php echo $passportDateOfExpiry; ?></td>
                    </tr>
                </table>
                
				<?php
				if($nationality != 'INDIAN') {
				?>
				
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">PIO Card Details</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>PIO Card No.</td>
                        <td width="250"><?php echo $pioCardNumber; ?></td>
                        <td width="250">Date of Issue</td>
                        <td><?php echo $pioDateOfIssue; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Issuing Authority</td>
                        <td width="250"><?php echo $pioIssuingAuthority; ?></td>
                        <td width="250">Date of Expiry</td>
                        <td><?php echo $pioDateOfExpiry; ?></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Domicile Details in India</td>
                            <td></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td width="200">Address</td>
                        <td><?php echo $addressInIndia; ?></td>
                    </tr>
                    <tr>
                        <td style="height:60px; line-height:22px" width="200">Current Country of<br />Residence Since</td>
                        <td><?php echo $currentCountry; ?></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Visa to Visit India Details</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>Visa No.</td>
                        <td width="250"><?php echo $visaNumber; ?></td>
                        <td width="250">Period of Validity</td>
                        <td><?php echo $visaValidTill; ?></td>
                    </tr>
                </table>
                <?php
				}
				?>
            
				<div class="spacer25 clearFix"></div>
        		<h2>3. Communication Address</h2>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    <tr>
                        <td width="200">Address Line 1</td>
                        <td colspan="3"><?php echo $ChouseNumber.' '.$CstreetName; ?></td>
                    </tr>
                    
					<tr>
                        <td width="200">Address Line 2</td>
                        <td colspan="3"><?php echo $Carea; ?></td>
                    </tr>
					
                    <tr>
                        <td>City</td>
                        <td width="250"><?php echo $Ccity; ?></td>
                        <td width="250">State</td>
                        <td><?php echo $Cstate; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Pin Code</td>
                        <td width="250"><?php echo $Cpincode; ?></td>
                        <td width="250"></td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td>Tel. No.</td>
                        <td width="250"><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber; ?></td>
                        <td width="250">Mobile</td>
                        <td><?php echo $mobileISDCode.'-'.$mobileNumber; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">E-mail Address</td>
                        <td colspan="3"><?php echo $email; ?></td>
                    </tr>
                    <tr>
                        <td width="200">Alternate E-mail Address</td>
                        <td colspan="3"><?php echo $altEmail; ?></td>
                    </tr>
                </table>
            
				<div class="spacer25 clearFix"></div>
        		<h2>4. Family Details</h2>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    <tr>
                        <td width="200">Father's / Mother's Name</td>
                        <td colspan="3"><?php echo $fatherName; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Occupation</td>
                        <td colspan="3"><?php echo $fatherOccupation; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Address Line 1</td>
                        <td colspan="3"><?php echo $houseNumber.' '.$streetName; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Address Line 2</td>
                        <td colspan="3"><?php echo $area; ?></td>
                    </tr>
                    
                    <tr>
                        <td>City</td>
                        <td width="250"><?php echo $city; ?></td>
                        <td width="250">State</td>
                        <td><?php echo $state; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Pin Code</td>
                        <td width="250"><?php echo $pincode; ?></td>
                        <td width="250"></td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td>Tel. No.</td>
                        <td width="250"><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber; ?></td>
                        <td width="250">Mobile</td>
                        <td><?php echo $mobileISDCode.'-'.$mobileNumber; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">E-mail Address</td>
                        <td colspan="3"><?php echo $fatherEmailAddress; ?></td>
                    </tr>
                    <tr>
                        <td width="200">Alternate E-mail Address</td>
                        <td colspan="3"><?php echo $altEmail; ?></td>
                    </tr>
                </table>
            
				<div class="spacer25 clearFix"></div>
        		<h2>5. Qualifications</h2>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Class X</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td width="200">Board / University</td>
                        <td colspan="3"><?php echo $class10Board; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Institution Studied</td>
                        <td colspan="3"><?php echo $class10School; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Year of Passing</td>
                        <td colspan="3"><?php echo $class10Year; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Aggregate Marks</td>
                        <td width="250"><?php echo $class10AggregateMarks; ?></td>
                        <td width="250">Grades</td>
                        <td><?php echo $class10Percentage; ?></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Class XII</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td width="200">Board / University</td>
                        <td colspan="3"><?php echo $class12Board; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Institution Studied</td>
                        <td colspan="3"><?php echo $class12School; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Year of Passing</td>
                        <td colspan="3"><?php echo $class12Year; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Aggregate Marks</td>
                        <td width="250"><?php echo $class12AggregateMarks; ?></td>
                        <td width="250">Grades</td>
                        <td><?php echo $class12Percentage; ?></td>
                    </tr>
                </table>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">Bachelor's Degree</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>Qualifications</td>
                        <td width="250"><?php echo $graduationExaminationName; ?></td>
                        <td width="250">Mode of Study</td>
                        <td><?php echo $graduationCourseMode; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Duration</td>
                        <td width="250"><?php echo $graduationCourseDuration; ?> Years</td>
                        <td width="250">Major Subjects/Branch</td>
                        <td><?php echo $graduationCourseStream; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Board / University</td>
                        <td colspan="3"><?php echo $graduationBoard; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Institution Studied</td>
                        <td colspan="3"><?php echo $graduationSchool; ?></td>
                    </tr>
                    <tr>
                        <td>Year of Passing</td>
                        <td width="250"><?php echo $graduationYear; ?></td>
                        <td width="250">Status</td>
                        <td><?php echo $graduationCourseStatus; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Aggregate Marks</td>
                        <td width="250"><?php echo $graduationAggregateMarks; ?></td>
                        <td width="250">Grades</td>
                        <td><?php echo $graduationPercentage; ?></td>
                    </tr>
                </table>
				
				<?php
				for($i=1;$i<=4;$i++) {
					if(${'graduationExaminationName_mul_'.$i}) {
				?>
                
                <div class="spacer10 clearFix"></div>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200"><?php echo ${'graduationExaminationName_mul_'.$i}; ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>Qualifications</td>
                        <td width="250"><?php echo ${'graduationExaminationName_mul_'.$i}; ?></td>
                        <td width="250">Mode of Study</td>
                        <td><?php echo ${'otherCoursesCourseMode_mul_'.$i}; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Duration</td>
                        <td width="250"><?php echo ${'otherCoursesCourseMode_mul_'.$i}; ?></td>
                        <td width="250">Major Subjects/Branch</td>
                        <td><?php echo ${'otherCoursesCourseStream_mul_'.$i}; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Board / University</td>
                        <td colspan="3"><?php echo ${'graduationBoard_mul_'.$i}; ?></td>
                    </tr>
                    
                    <tr>
                        <td width="200">Institution Studied</td>
                        <td colspan="3"><?php echo ${'graduationSchool_mul_'.$i}; ?></td>
                    </tr>
                    <tr>
                        <td>Year of Passing</td>
                        <td width="250"><?php echo ${'graduationYear_mul_'.$i}; ?></td>
                        <td width="250">Status</td>
                        <td><?php echo ${'otherCoursesCourseStatus_mul_'.$i}; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Aggregate Marks</td>
                        <td width="250"><?php echo ${'otherCoursesAggregateMarks_mul_'.$i}; ?></td>
                        <td width="250">Grades</td>
                        <td><?php echo ${'graduationPercentage_mul_'.$i}; ?></td>
                    </tr>
                </table>
				
				<?php
					}
				}
				?>
                <div class="spacer25 clearFix"></div>
            
        		<h2>6. Work Experience</h2>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200">1. Work Experience</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>Company</td>
                        <td width="250"><?php echo $weCompanyName; ?></td>
                        <td width="250">Designation</td>
                        <td><?php echo $weDesignation; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Responsibilities</td>
                        <td width="250"><?php echo $weRoles; ?></td>
                        <td width="250">Total Experience</td>
                        <td>
						<?php
							$dateTo = $weTimePeriod?date('Y-m-d'):getStandardDate($weTill);
							$timeDiff = getTimeDifference(getStandardDate($weFrom),$dateTo);
							echo $timeDiff['months'];
						?> Month<?php echo intval($timeDiff['months'])>1?'s':''; ?></td>
                    </tr>
                    <tr>
                        <td>From Date</td>
                        <td width="250"><?php echo $weFrom; ?></td>
                        <td width="250">To Date</td>
                        <td><?php echo $weTimePeriod?'Till date':$weTill; ?></td>
                    </tr>
                </table>
            
				<?php
				for($i=1;$i<=2;$i++) {
					if(${'weCompanyName_mul_'.$i}) {
				?>		
				<div class="spacer10 clearFix"></div>
			
                <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                	<thead>
                        <tr>
                            <td width="200"><?php echo $i+1; ?>. Work Experience</td>
                            <td colspan="3"></td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>Company</td>
                        <td width="250"><?php echo ${'weCompanyName_mul_'.$i}; ?></td>
                        <td width="250">Designation</td>
                        <td><?php echo ${'weDesignation_mul_'.$i}; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Responsibilities</td>
                        <td width="250"><?php echo ${'weRoles_mul_'.$i}; ?></td>
                        <td width="250">Total Experience</td>
                        <td>
						<?php
							$dateTo = ${'weTimePeriod_mul_'.$i}?date('Y-m-d'):getStandardDate(${'weTill_mul_'.$i});
							$timeDiff = getTimeDifference(getStandardDate(${'weFrom_mul_'.$i}),$dateTo);
							echo $timeDiff['months'];
						?> Month<?php echo intval($timeDiff['months'])>1?'s':''; ?></td>
						</td>
                    </tr>
                    <tr>
                        <td>From Date</td>
                        <td width="250"><?php echo ${'weFrom_mul_'.$i}; ?></td>
                        <td width="250">To Date</td>
                        <td><?php echo ${'weTimePeriod_mul_'.$i}?'Till date':${'weTill_mul_'.$i}; ?></td>
                    </tr>
                </table>
				<?php
					}
				}
				?>
				<div class="spacer25 clearFix"></div>
            
            <div class="formRow">
        		<h2>7. Particulars of extra curricular activities <span>(Including hobbies &amp; interests you consider relevant to a managerial
career)</span></h2>
				<div class="activitiesDetails"><?php echo $extraCurricular; ?></div>
            </div>
            <div class="spacer25 clearFix"></div>
            <div class="formRow">
        		<h2>8. Major Achievement/Accomplishments in life</h2>
				<div class="activitiesDetails"><?php echo $achievementsTapmi; ?></div>
            </div>
            <div class="spacer25 clearFix"></div>
            <div class="formRow">
        		<h2>9. Any other relevant information you think is necessary to be conveyed</h2>
				<div class="activitiesDetails"><?php echo $otherRelevantInfo; ?></div>
            </div>
            <div class="spacer25 clearFix"></div>
            <div class="formRow">
        		<h2>10. Did you stay in a hostel during your studies?</h2>
				<div class="activitiesDetails"><?php echo $stayedInHostel; ?></div>
            </div>
            <div class="spacer25 clearFix"></div>
            <div class="formRow">
        		<h2>11. Declaration</h2>
				<div>
                I hereby declare that the information given by me in this application is correct to the best of my knowledge and belief.
                </div>
            </div>
            <div class="spacer25 clearFix"></div>
        </div>
        
    </div>
    <div class="clearFix"></div>
    </div>
