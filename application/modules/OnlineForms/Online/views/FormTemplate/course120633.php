   	<?php $valuePrefix = '&nbsp;'; ?>
	<!--Skyline Form Preview Starts here-->
	<link href="/public/css/onlineforms/skyline/form-preview.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
    	<div class="previewHeader">
        	<div class="previewFormTitle">
                <div class="instLogoBox"><img src="/public/images/onlineforms/institutes/skyline/skyline-logo.gif" alt="" /></div>
                <div class="instNameBox">
                	SKYLINE BUSINESS SCHOOL
                </div>
            </div>
            <?php if($profileImage) { ?>
            <div class="picBox">
            	<img width="195" height="192" src="<?php echo $profileImage; ?>" />
            </div>
			<?php } ?>
            
            <div class="courseNameDetails">
                	<div class="courseNameBox">Application Form For <span>MBA</span>
					<?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
                    <strong class="editFormLink applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
					<?php } ?>
                    </div>
                    <div class="srNumBox">Serial No. : <?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></div>
                </div>
            
        </div>
        <div class="previewBody">
        <h5>Personal Details</h5>
        <ul>
            <li>
            	<div class="previewLeftCol">
                    <label>Student's Name :</label>
                    <div class="previewFieldBox">
                        <span><?=$valuePrefix?><?=$firstName?></span>
                        <strong>First Name</strong>
                    </div>
                </div>
                
                <div class="previewRightCol">
                    <div class="previewFieldBoxRt">
                        <span><?=$valuePrefix?><?php echo $middleName.' '.$lastName; ?></span>
                        <strong>Last Name</strong>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="previewLeftCol">
                    <div class="previewSmallLeftCol">
                        <label>Nationality :</label>
                        <div class="previewSmallFieldBox">
                            <span><?=$valuePrefix?><?=$nationality?></span>
                        </div>
                    </div>
                    
                    <div class="previewSmallRightCol">
                        <label>Date of Birth :</label>
                        <div class="previewSmallFieldBox">
                            <span><?=$valuePrefix?><?=$dateOfBirth?></span>
                        </div>
                    </div>
                </div>
                
                <div class="previewRightCol">
                    <label>Email Id :</label>
                    <div class="previewFieldBox" style="width:367px">
                        <span><?=$valuePrefix?><?=$email?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="flLt">
                    <label>Address (for communication) :</label>
                    <div class="previewFieldBox" style="width:731px">
                        <span><?=$valuePrefix?><?php echo $ChouseNumber;
									if($CstreetName) echo ', '.$CstreetName;
									if($Carea) echo ', '.$Carea;
									if($Ccity) echo ', '.$Ccity;
									if($Cstate) echo ', '.$Cstate;
									if($Ccountry) echo ', '.$Ccountry;
								?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="previewLeftCol previewChild">
                    <div class="previewSmallLeftCol" style="width:209px">
                        <label>Pin No. :</label>
                        <div class="previewSmallFieldBox" style="width:144px">
                            <span><?=$valuePrefix?><?=$Cpincode?></span>
                        </div>
                    </div>
                    
                    <div class="previewSmallRightCol">
                        <label>Tel. No. :</label>
                        <div class="previewSmallFieldBox" style="width:179px">
                            <span><?=$valuePrefix?><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="previewRightCol">
                    <label>Mobile No. :</label>
                    <div class="previewFieldBox" style="width:347px">
                        <span><?=$valuePrefix?><?php echo $mobileISDCode.'-'.$mobileNumber; ?></span>
                    </div>
                </div>
             </li>
             
             <li>
            	<div class="previewLeftCol">
                    <label>Father's Name :</label>
                    <div class="previewFieldBox">
                        <span><?=$valuePrefix?><?=$fatherName?></span>
                    </div>
                </div>
               	<div class="previewRightCol">
                    <label>Father's Profession/Designation :</label>
                    <div class="previewFieldBox" style="width:197px">
                        <span><?=$valuePrefix?><?=$fatherOccupationSkyline?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="flLt" style="padding-left:40px">
                    <label>Name of Organisation :</label>
                    <div class="previewFieldBox" style="width:742px">
                        <span><?=$valuePrefix?><?=$fatherOrganization?></span>
                    </div>
                </div>
             </li>
             
             <li>
            	<div class="previewLeftCol previewChild">
                    <label>Mobile No. :</label>
                    <div class="previewFieldBox" style="width:374px">
                        <span><?=$valuePrefix?><?=$fatherMobileNumberSkyline?></span>
                    </div>
                </div>
               	<div class="previewRightCol">
                    <label>Email Id :</label>
                    <div class="previewFieldBox" style="width:367px">
                        <span><?=$valuePrefix?><?=$fatherEmailId?></span>
                    </div>
                </div>
             </li>
             
             
             <li>
            	<div class="previewLeftCol">
                    <label>Mother's Name :</label>
                    <div class="previewFieldBox">
                        <span><?=$valuePrefix?><?=$MotherName?></span>
                    </div>
                </div>
               	<div class="previewRightCol">
                    <label>Mother's Profession/Designation :</label>
                    <div class="previewFieldBox" style="width:192px">
                        <span><?=$valuePrefix?><?=$MotherOccupation?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="flLt" style="padding-left:40px">
                    <label>Name of Organisation :</label>
                    <div class="previewFieldBox" style="width:742px">
                        <span><?=$valuePrefix?><?=$MotherOrganization?></span>
                    </div>
                </div>
             </li>
             
             <li>
            	<div class="previewLeftCol previewChild">
                    <label>Mobile No. :</label>
                    <div class="previewFieldBox" style="width:374px">
                        <span><?=$valuePrefix?><?=$MotherMobileNumberSkyline?></span>

                    </div>
                </div>
               	<div class="previewRightCol">
                    <label>Email Id :</label>
                    <div class="previewFieldBox" style="width:368px">
                        <span><?=$valuePrefix?><?=$MotherEmailId?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="flLt">
                    <label>Permanent Address :</label>
                    <div class="previewFieldBox" style="width:795px">
                        <span><?=$valuePrefix?><?php echo $houseNumber;
									if($streetName) echo ', '.$streetName;
									if($area) echo ', '.$area;
									if($city) echo ', '.$city;
									if($state) echo ', '.$state;
									if($country) echo ', '.$country;
								?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="previewLeftCol previewChild">
					<label>Pin No. :</label>
                    <div class="previewSmallFieldBox" style="width:399px">
                        <span><?=$valuePrefix?><?=$pincode?></span>
                    </div>
                </div>
                
                <div class="previewRightCol">
                    <label>Tel. No. :</label>
                    <div class="previewSmallFieldBox" style="width:370px">
                        <span><?=$valuePrefix?><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber; ?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="flLt">
                    <label>If Outstation student then Delhi Guardian's Name &amp; Address :</label>
                    <div class="previewFieldBox" style="width:515px">
                        <span style="height:auto"><?=$valuePrefix?><?=$GudardianNameAndAddress?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="previewLeftCol previewChild">
                    <div class="previewSmallLeftCol" style="width:209px">
                        <label>Tel. No. :</label>
                        <div class="previewSmallFieldBox" style="width:144px">
                            <span><?=$valuePrefix?><?=$GudardianTelephone?></span>
                        </div>
                    </div>
                    
                    <div class="previewSmallRightCol">
                        <label>Mobile No. :</label>
                        <div class="previewSmallFieldBox" style="width:154px">
                            <span><?=$valuePrefix?><?=$GudardianMobile?></span>
                        </div>
                    </div>
                </div>
                
                <div class="previewRightCol">
                    <label>E-mail Id :</label>
                    <div class="previewFieldBox" style="width:362px">
                        <span><?=$valuePrefix?><?=$GudardianEmail?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<h5>Education <span>(List your School / College / Professional qualifications, starting from Class X)</span> :</h5>
                    <table width="100%" cellpadding="5" cellspacing="0" border="1" class="educationTable" bordercolor="#7a7a7a">
                    	<thead>
                        	<tr>
                            	<td valign="top">Institute <br />School / College</td>
                                <td valign="top">Qualification<br />eg. X, XII, BA, B.Com,<br />any other</td>
                                <td width="160" valign="top">
                                	<div>Period</div>
                                    <div class="spacer10 clearFix"></div>
                                    <div class="fromDate">From</div>
                                    <div class="toDate">To</div>
                                </td>
                                <td valign="top">
                                	Awarding Body / Board / <br />University
                                </td>
                                <td valign="top">Percentage<br />Marks (Agg)</td>
                            </tr>
                            </thead>
                            <tr>
                            	<td valign="top"><?=$class10School?></td>
                                <td valign="top"><?php echo $class10ExaminationName; ?></td>
                                <td valign="top">
                                	<div class="fromDate"><?=$class10StartDate?></div>
                                    <div class="toDate"><?=$class10EndDate?></div>
                                </td>
                                <td valign="top"><?=$class10Board?></td>
                                <td valign="top" align="center"><?=$class10Percentage?></td>
                            </tr>
                            
                            <tr>
                            	<td valign="top"><?=$class12School?></td>
                                <td valign="top"><?php echo $class12ExaminationName; ?></td>
                                <td valign="top">
                                	<div class="fromDate"><?=$class12StartDate?></div>
                                    <div class="toDate"><?=$class12EndDate?></div>
                                </td>
                                <td valign="top"><?=$class12Board?></td>
                                <td valign="top" align="center"><?=$class12Percentage?></td>
                            </tr>
							
							<?php if($graduationExaminationName) { ?>
							 <tr>
                            	<td valign="top"><?=$graduationSchool?></td>
                                <td valign="top"><?php echo $graduationExaminationName; ?></td>
                                <td valign="top">
                                	<div class="fromDate"><?=$graduationStartDate?></div>
                                    <div class="toDate"><?=$graduationEndDate?></div>
                                </td>
                                <td valign="top"><?=$graduationBoard?></td>
                                <td valign="top" align="center"><?=$graduationPercentage?></td>
                            </tr>
							<?php } ?> 
							<?php
							for($i=1;$i<=4;$i++){
								if(${'graduationExaminationName_mul_'.$i}){
								?>
							
								<tr>
									<td valign="top"><?=${'graduationSchool_mul_'.$i}?></td>
									<td valign="top"><?=${'graduationExaminationName_mul_'.$i}?></td>
									<td valign="top">
										<div class="fromDate"><?=${'otherCourseStartDate_mul_'.$i}?></div>
										<div class="toDate"><?=${'otherCourseEndDate_mul_'.$i}?></div>
									</td>
									<td valign="top"><?=${'graduationBoard_mul_'.$i}?></td>
									<td valign="top" align="center"><?=${'graduationPercentage_mul_'.$i}?></td>
								</tr>	
								
								<?php
								}
							}
							?> 
                    </table>
			</li>
		</ul>
		<ul>
            <li>
            	<h5>Qualifying Examination :<span>(If applicable)</span></h5>
                <h3 style="width:100%; float:left; margin-bottom:5px; text-transform:uppercase">CAT Score Details :</h3>
                <div class="clearFix spacer10"></div>
                
                <div class="previewLeftCol" style="width:285px">
					<label>Date of Examination :</label>
                    <div class="previewSmallFieldBox" style="width:125px">
                        <span><?=$valuePrefix?><?php echo $catDateOfExaminationSkyline; ?></span>
                    </div>
                </div>
                
                <div class="previewLeftCol" style="width:300px">
					<label>Roll Number :</label>
                    <div class="previewSmallFieldBox" style="width:200px">
                        <span><?=$valuePrefix?><?php echo $catRollNumberSkyline; ?></span>
                    </div>
                </div>
                
				<div class="previewLeftCol" style="width:170px">
                    <label>Score :</label>
                    <div class="previewSmallFieldBox" style="width:105px">
                        <span><?=$valuePrefix?><?php echo $catScoreSkyline; ?></span>
                    </div>
                </div>
				
                <div class="previewRightCol" style="width:185px">
                    <label>Percentile :</label>
                    <div class="previewSmallFieldBox" style="width:100px">
                        <span><?=$valuePrefix?><?php echo $catPercentileSkyline; ?></span>
                    </div>
                </div>				
             </li>
			
			
             <li>
                <h3 style="width:100%; float:left; margin-bottom:5px; text-transform:uppercase">MAT Score Details :</h3>
                <div class="clearFix spacer10"></div>
                
                <div class="previewLeftCol" style="width:285px">
					<label>Date of Examination :</label>
                    <div class="previewSmallFieldBox" style="width:125px">
                        <span><?=$valuePrefix?><?php echo $matDateOfExaminationSkyline; ?></span>
                    </div>
                </div>
                
                <div class="previewLeftCol" style="width:300px">
					<label>Roll Number :</label>
                    <div class="previewSmallFieldBox" style="width:200px">
                        <span><?=$valuePrefix?><?php echo $matRollNumberSkyline; ?></span>
                    </div>
                </div>
                
				<div class="previewLeftCol" style="width:170px">
                    <label>Score :</label>
                    <div class="previewSmallFieldBox" style="width:105px">
                        <span><?=$valuePrefix?><?php echo $matScoreSkyline; ?></span>
                    </div>
                </div>
				
                <div class="previewRightCol" style="width:185px">
                    <label>Percentile :</label>
                    <div class="previewSmallFieldBox" style="width:100px">
                        <span><?=$valuePrefix?><?php echo $matPercentileSkyline; ?></span>
                    </div>
                </div>				
             </li>
		
            <?php //_p($this->_ci_cached_vars); ?>
            <li>
            	<h5>Work Experience <span>(If applicable)</span></h5>
             	<div class="previewLeftCol">
					<label>Present Position :</label>
                    <div class="previewSmallFieldBox" style="width:369px">
                        <span><?=$valuePrefix?>
						<?php
						if($weTimePeriod) {
							echo $weCompanyName;
						}
						else if($weTimePeriod_mul_1) {
							echo $weCompanyName_mul_1;
						}
						else if($weTimePeriod_mul_2) {
							echo $weCompanyName_mul_2;
						}
						else {
							echo $weCompanyName;	
						}
						?></span>
                    </div>
                </div>
                
                <div class="previewRightCol">
                    <label>Date of appointment :</label>
                    <div class="previewSmallFieldBox" style="width:280px">
                        <span><?=$valuePrefix?><?=$dateOfAppointment?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="flLt">
                    <label>Name and address of employer :</label>
                    <div class="previewFieldBox" style="width:716px">
                        <span style="height:auto"><?=$valuePrefix?><?=nl2br($addressOfEmployer)?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="previewLeftCol">
                    <div class="previewSmallLeftCol">
                        <label>Tel. No. :</label>
                        <div class="previewSmallFieldBox" style="width:184px">
                            <span><?=$valuePrefix?><?=$employerTelephoneNumber?></span>
                        </div>
                    </div>
                    
                    <div class="previewSmallRightCol">
                        <label>Fax :</label>
                        <div class="previewSmallFieldBox" style="width:204px">
                            <span><?=$valuePrefix?><?=$employerFaxNumber?></span>
                        </div>
                    </div>
                </div>
                
                <div class="previewRightCol">
                    <label>E-mail Id :</label>
                    <div class="previewFieldBox" style="width:363px">
                        <span><?=$valuePrefix?><?=$employerEmailId?></span>
                    </div>
                </div>
             </li>
             
             <li>
             	<div class="flLt">
                    <label>Total no. of years work experience :</label>
                    <div class="previewFieldBox" style="width:690px">
                        <span><?=$valuePrefix?><?=$totalYearsOfExperience?> <?php if($totalYearsOfExperience && strtolower($totalYearsOfExperience) != 'na') echo 'yrs'; ?></span>
                    </div>
                </div>
             </li>
                
             <li>
             	<h5>General</h5>
                <label>Please state briefly why you want to join this course</label>
                <div class="spacer5 clearFix"></div>
                <div class="previewFieldBox" style="width:100%">
                	<span style="height:auto;"><?=$valuePrefix?><?=nl2br($whyJoinCourse)?></span>
                </div>
             </li>
             
             <li>
             	<label>Please state briefly why you want to join skyline business school</label>
                <div class="spacer5 clearFix"></div>
                <div class="previewFieldBox" style="width:100%">
                	<span style="height:auto;"><?=$valuePrefix?><?=nl2br($whyJoinSkyline)?></span>
                </div>
             </li>
             
             <li>
             	<label>Sources of information about skyline</label>
                <div class="spacer5 clearFix"></div>
                <div class="previewFieldBox" style="width:100%">
                	<span style="height:auto"><?=$valuePrefix?><?=$sourceOfInformation?></span>
                </div>
             </li>
             
             <li style="line-height:22px">
             	I <span style="border-bottom:dotted 1px #000; padding:0 20px"><?=$firstName.' '.$middleName.' '.$lastName?></span> hereby certify that the information stated in this application form is true and correct to the best of my knowledge and that nothing has been concealed therein. I have read and understood the conditions mentioned clearly. The degree/diploma/certificate will be awarded to me only on the successful completion of the course requirements and the examination of the course applied for.  Only enrolling for the course does not make me eligible for the award. 
			</li>
             
            <li>
             	<div class="flLt"><strong>Agreed By</strong> <span style="border-bottom:dotted 1px #000; padding:0 20px"><?=$firstName.' '.$middleName.' '.$lastName?></span></div>
                <div class="flRt"><?=date('d/m/Y')?></div>
             </li>
             
        </ul>
        </div>
    </div>
    <div class="clearFix"></div>
	<!--Skyline Form Preview Ends here-->
	<?php if($_REQUEST['print']): ?>
	<script>
		window.print();
	</script>
	<?php endif; ?>
