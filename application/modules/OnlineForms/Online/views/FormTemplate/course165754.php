<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];

?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div style="float:right;">
		
			<div class="form-details"><label ><strong>Form Id. : </strong></label><?php if(!empty($instituteSpecId)) {echo 'S'.$instituteSpecId;} else {echo $onlineFormId;} ?></div></div>
	<div id="custom-form-header">
    	<div class="app-left-box">
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:100%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/RCBS/logo2.gif" alt="Rajagiri Centre for Business Studies" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">Rajagiri Centre for Business Studies</h2>
				<div style="text-align:left;font-size:15px;margin-left:20px">
					Rajagiri Valley P.O.<br>
					Kakkanad, Cochin - 682039<br>
					E-mail - admission@rajagiri.edu<br>
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
		APPLICATION FORM FOR ADMISSION IN PGDM 2015-2017 BATCH
	</div>
	<div class="spacer15 clearFix"></div>
    <div id="custom-form-content">
	

	<ul>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Course Preference:</strong>
			</div>
		</li>
		<li>
			<div class="personalInfoCol" style="width:400px">
			<label>1:</label>
			<span><?php echo $RCBS_course1; ?></span>
			</div>
			<div class="personalInfoCol" style="width:400px">
			<label>2:</label>
			<span><?php echo $RCBS_course2; ?></span>
			</div>
		</li>
		<li>
			<div class="personalInfoCol" style="width:400px">
			<label>3:</label>
			<span><?php echo $RCBS_course3; ?></span>
			</div>
			<div class="personalInfoCol" style="width:400px">
			<label>4:</label>
			<span><?php echo $RCBS_course4; ?></span>
			</div>
		</li>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Applicant's Personal Details:</strong>
			</div>
		</li>
		<li>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>First Name:</label>
                                <span><?php echo $firstName.' '.$middleName;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Surname:</label>
                                <span><?php echo $lastName;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
                </li>
		<li>
                            <div class="personalInfoCol" style="width:200px">
                                <label>Date of birth:</label>
                                <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
                            </div>
			    <div class="personalInfoCol" style="width:200px">
                                <label>Gender:</label>
                                <span><?php echo $gender; ?></span>
                            </div>
			    <div class="personalInfoCol" style="width:200px">
                                <label>Marital Status:</label>
                                <span><?php echo $maritalStatus; ?></span>
                            </div>
			    <div class="personalInfoCol" style="width:200px">
                                <label>Blood Group:</label>
                                <span><?php echo $bloodGroup; ?></span>
                            </div>
		</li>
		<li>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Religion:</label>
                                <span><?php echo $religion;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Caste:</label>
                                <span><?php echo $RCBS_caste;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
                </li>
		<li>
			<div class="colums-width" style="width:100%">
				<label>Specific Category: </label>
				<div class="form-details"><?php echo $RCBS_category; ?></div>
			</div>
		</li>
		<li>
			<div class="colums-width" style="width:100%">
				<label>Socially and Educationally Backward Classes:</label>
				<div class="form-details"><?php echo $RCBS_BackwardClass; ?></div>
			</div>
		</li>
		<li>
			<?php if($RCBS_BackwardClass == 'Other Backward Hindu') {?>
			    <div class="personalInfoCol" style="width:800px;">
				<label>Specify the OBH Category (Vania, Arya etc.):</label>
				<span><?php echo $RCBS_BackwardClassDetail;?></label>
			    </div>
			    <?php } if($RCBS_BackwardClass == 'Physically Challenged') {?>
			    <div class="personalInfoCol" style="width:800px;">
				<label>Specify:</label>
				<span><?php echo $RCBS_DisabilityDetail;?></label>
			    </div>
			    <?php }?>
		</li>
		<li>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>State of Domicile:</label>
                                <span><?php echo $RCBS_StateDomicile;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Mother Tongue:</label>
                                <span><?php echo $motherTongue;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
                </li>
		<li>
			<div class="colums-width" style="width:100%">
				<label>Place of Birth (Specify District & State):</label>
				<div class="form-details"><?php echo $RCBS_PlaceofBirth; ?></div>
			</div>
		</li>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Address for Correspondence:</strong>
			</div>
		</li>
		<li>
                            <div class="personalInfoCol" style="width:600px">
                                <label>Address:</label>
                                <span><?php echo $ChouseNumber.' '.$CstreetName.' '.$Carea;?></span>
                            </div>
			    <div class="personalInfoCol" style="width:300px">
                                <label>District:</label>
                                <span><?php echo $RCBS_District;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
			<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>City:</label>
                                <span><?php echo $Ccity;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>State:</label>
                                <span><?php echo $Cstate;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>Pin Code:</label>
                                <span><?php echo $Cpincode;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
			<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>Phone (with STD Code):</label>
                                <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>Mobile Number:</label>
                                <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>E-mail:</label>
                                <span><?php echo $email;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
			<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Permanent Address:</strong>
			</div>
		</li>
		<li>
                            <div class="personalInfoCol" style="width:800px">
                                <label>Address:</label>
                                <span><?php echo $ChouseNumber.' '.$CstreetName.' '.$Carea;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
			<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>City:</label>
                                <span><?php echo $Ccity;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>State:</label>
                                <span><?php echo $Cstate;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>Pin Code:</label>
                                <span><?php echo $Cpincode;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
			<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>Phone (with STD Code):</label>
                                <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>Mobile Number:</label>
                                <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>E-mail:</label>
                                <span><?php echo $email;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Academic Record:</strong>
			</div>
		</li>
		<li>
            	<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
		    <tr>
			<td style="width:100px;">Course</td>
			<td style="width:140px;">University/Board</td>
                    	<td style="width:140px;">Institute</td>
                        <td style="width:60px;">Degree</td>
                        <td style="width:60px;">Month & Year of Passing</td>
			<td style="width:140px;">Specialization</td>
			<td style="width:60px;">Marks(%)</td>
                    </tr>
		    <tr>
			<td><?php echo "Graduation";?></td>
			<td><?php echo $graduationBoard;?></td>
                        <td><?php echo $graduationSchool;?></td>
			<td><?php echo $graduationExaminationName;?></td>
			<td><?php echo $RCBS_passingMonthGrad." & ".$graduationYear;?></td>
                        <td><?php echo $RCBS_SpecializationGrad;?></td>
			<td><?php echo $graduationPercentage; ?></td>
                    </tr>
		    <?php 
		for($j=1;$j<=4;$j++):?>
		<?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
		    <tr>
			<td><?php echo "Post Graduation/Other Courses";?></td>
			<td><?php echo ${'graduationBoard_mul_'.$j};?></td>
                        <td><?php echo ${'graduationSchool_mul_'.$j};?></td>
                        <td><?php echo ${'graduationExaminationName_mul_'.$j};?></td>
			<td><?php echo ${'otherCoursePassingMonth_mul_'.$j}.' & '.${'graduationYear_mul_'.$j};?></td>
                        <td><?php echo ${'otherCourseSpecialization_mul_'.$j};?></td>
			<td><?php echo ${'graduationPercentage_mul_'.$j}; ?></td>
                    </tr>
			<?php endif;endfor; ?>
		</table>
		</li>
		<li>
            	<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
		    <tr>
			<td style="width:100px;">Course</td>
			<td style="width:140px;">University/Board</td>
                    	<td style="width:140px;">Institute</td>
                        <td style="width:60px;">Month & Year of Passing</td>
			<td style="width:60px;">Marks(%)</td>
                    </tr>
		    <tr>
			<td>Std. 10</td>
			<td><?php echo $class10Board;?></td>
                    	<td><?php echo $class10School;?></td>
                        <td><?php echo $RCBS_passingMonth10." & ".$class10Year;?></td>
                        <td><?php echo $class10Percentage;?></td>
                    </tr>
		    <tr>
			<td>Std. 12</td>
			<td><?php echo $class12Board;?></td>
                    	<td><?php echo $class12School;?></td>
                        <td><?php echo $RCBS_passingMonth12." & ".$class12Year;?></td>
                        <td><?php echo $class12Percentage;?></td>

                    </tr>
		</table>
		</li>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Achievements:</strong>
			</div>
		</li>
		<li>
			<div class="colums-width" style="width:100%">
				<label>Academic Achievements, if any:</label>
				<div class="form-details"><?php echo $RCBS_achieveAcademic; ?></div>
			</div>
		</li>
		<li>
			<div class="colums-width" style="width:100%">
				<label>Non Academic Achievements, if any:</label>
				<div class="form-details"><?php echo $RCBS_achieveNonAcademic; ?></div>
			</div>
		</li>
		<li>
			<div class="colums-width" style="width:100%">
				<label>Memberships in bodies (like NCC, NSS, and IEEE etc):</label>
				<div class="form-details"><?php echo $RCBS_Membership; ?></div>
			</div>
		</li>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Test Scores:</strong>
			</div>
		</li>
			<?php 
			$testsArray = explode(",",$RCBS_testNames);
			
			if(in_array("CAT",$testsArray)){ ?>
			<li>
			<label>CAT (November 2014)</label>
			</li>
			<li>
				
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Regisration Number:</label>
                                <span><?php echo $catRollNumberAdditional;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Score:</label>
                                <span><?php echo $catScoreAdditional;?></span>
                            </div>
			    <div class="personalInfoCol"  style="width:300px">
                                <label>Percentile:</label>
                                <span><?php echo $catPercentileAdditional;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
			</li>
		<?php } if(in_array("MATDec",$testsArray)) { ?>
			<li>
			<label>MAT (December 2014)</label>
			</li>
			<li>
				<div class="personalInfoCol"  style="width:400px">
                                <label>Roll Number:</label>
                                <span><?php echo $RCBS_matdecRollNumber;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Form Number:</label>
                                <span><?php echo $RCBS_matdecFormNumber;?></span>
                            </div>
			</li>
			<li>
				<div class="personalInfoCol"  style="width:400px">
                                <label>Score:</label>
                                <span><?php echo $RCBS_matdecScore;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Percentile:</label>
                                <span><?php echo $RCBS_matdecPercentile;?></span>
                            </div>
			</li>
		<?php } if(in_array("MATFeb",$testsArray)) { ?>
			<li>
			<label>MAT (February 2015)</label>
			</li>
			<li>
				<div class="personalInfoCol"  style="width:400px">
                                <label>Roll Number:</label>
                                <span><?php echo $RCBS_matfebRollNumber;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Form Number:</label>
                                <span><?php echo $RCBS_matfebFormNumber;?></span>
                            </div>
			</li>
			<li>
				<div class="personalInfoCol"  style="width:400px">
                                <label>Score:</label>
                                <span><?php echo $RCBS_matfebScore;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Percentile:</label>
                                <span><?php echo $RCBS_matfebPercentile;?></span>
                            </div>
			</li>
			<?php } if(in_array("CMATSept",$testsArray)) { ?>
			<li>
			<label>CMAT (September 2014)</label>
			</li>
			<li>
				
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Roll Number:</label>
                                <span><?php echo $RCBS_cmatseptRollNumber;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Rank:</label>
                                <span><?php echo $RCBS_cmatseptRank;?></span>
                            </div>
			    <div class="personalInfoCol"  style="width:300px">
                                <label>Score:</label>
                                <span><?php echo $RCBS_cmatseptPercentile;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
			</li>
			<?php } if(in_array("CMATFeb",$testsArray)) { ?>
			<li>
			<label>CMAT (February 2015)</label>
			</li>
			<li>
				
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Roll Number:</label>
                                <span><?php echo $RCBS_cmatfebRollNumber;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Rank:</label>
                                <span><?php echo $RCBS_cmatfebRank;?></span>
                            </div>
			    <div class="personalInfoCol"  style="width:300px">
                                <label>Score:</label>
                                <span><?php echo $RCBS_cmatfebPercentile;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
			</li>
			
			<?php } ?>
			
			
			<li>
			 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong> Work Experience: </strong><span>(Mention the (last five only) full time / part time paid employment after graduation.
Do not include training / project work/ field work etc, done as an integral part of curricular requirements.)</span>
			</div>
		</li>
		<?php 
				  $workExGiven = false;
				  $total = 0;
				  for($i=0; $i<3; $i++){

				      $mulSuffix = $i>0?'_mul_'.$i:'';
				      $mulSuffix = $i>0?'_mul_'.$i:'';
				      $otherSuffix = '_mul_'.$i;
				      $workCompany = ${'weCompanyName'.$mulSuffix};
				      $workCompaniesExpFrom = ${'weFrom'.$mulSuffix};
				      $workCompaniesExpTo= trim(${'weTimePeriod'.$mulSuffix})?'Till date':${'weTill'.$mulSuffix};
				      $designation = ${'weDesignation'.$mulSuffix};       
                                      $workExpTotalInMonthValue=${'workExpTotalInMonth'.$otherSuffix};
                                      	      
				      
				      if($workCompany || $designation){$workExGiven = true;$total++; ?>
		<li>
			
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
				<tr>
					<td style="width:20px;">Sr. No</td>
					<td style="width:80px;">Organisation</td>
					<td style="width:80px;">Designation</td>
					<td style="width:60px;">From</td>
					<td style="width:60px;">To</td>
					<td style="width:180px;">Nature of Work</td>
					<td style="width:80px;">Monthly Remuneration</td>
				</tr>
				<tr>
					<td><?=$weCompanyName?'1.':''?></td>
					<td><?=$weCompanyName?></td>
					<td><?=$weDesignation?></td>
					<td><?php if(!empty($weFrom)) {echo date('F Y',strtotime(getStandardDate($weFrom)));}?></td>
					<td><?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('F Y',strtotime(getStandardDate($weTill)));}} else {echo "Current";}?></td>
					<td><?php echo nl2br(trim($weRoles));?></td>
					<td><?php echo ${'workExpRemuneration_mul_'.$i};?></td>
				</tr>
				<?php 
					for($i=1;$i<=3;$i++):?>
					<?php if(!empty(${'weCompanyName_mul_'.$i})):?>
				<tr>
					<td><?=($i+1)?></td>
					<td><?php echo ${'weCompanyName_mul_'.$i};?></td>
					<td><?=${'weDesignation_mul_'.$i}?></td>
					<td><?php if(!empty(${'weFrom_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></td>
					<td><?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo "Current";}?></td>
					<td><?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></td>
					<td><?php echo trim(${'workExpRemuneration_mul_'.$i});?></td>
				</tr>
				
				<?php endif; endfor; ?>
			</table>
		</li>
		<?php } } ?>
		<li>
			<div class="personalInfoCol"  style="width:800px">
                                <label>Total work experience in months:</label>
                                <span><?php echo $RCBS_workEX;?></span>
                            </div>
		</li>
		<li>
			 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong>Parents Detail:</strong>
			</div>
		</li>
		<li>
			<div class="personalInfoCol"  style="width:400px">
                                <label>Father's Name:</label>
                                <span><?php echo $fatherName;?></span>
                            </div>
			<div class="personalInfoCol"  style="width:400px">
                                <label>Occupation/Designation:</label>
                                <span><?php echo $fatherOccupation."/".$fatherDesignation;?></span>
                            </div>
		</li>
		<li>
			<div class="personalInfoCol"  style="width:400px">
                                <label>Mother's Name:</label>
                                <span><?php echo $MotherName;?></span>
                            </div>
			<div class="personalInfoCol"  style="width:400px">
                                <label>Occupation/Designation:</label>
                                <span><?php echo $MotherOccupation."/".$MotherDesignation;?></span>
                            </div>
		</li>
		<li>
			<div class="personalInfoCol"  style="width:400px">
                                <label>Parents Mobile:</label>
                                <span><?php echo $RCBS_ParentsMobile;?></span>
                            </div>
			<div class="personalInfoCol"  style="width:400px">
                                <label>Email ID:</label>
                                <span><?php echo $RCBS_ParentsEmail;?></span>
                            </div>
		</li>
		<li>
			<div class="personalInfoCol"  style="width:900px">
                                <label>Family Annual Income :</label>
                                <span><?php echo $RCBS_FamilyIncome;?></span>
                            </div>
		</li>
		<li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I, &nbsp; <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
		, hereby undertake, that if I am admitted to the college, I will abide by the rules and regulations of the college, and will do nothing either inside or outside the college that will interfere with its orderly working, discipline and reputation.
I do affirm that all information furnished in this application is correct to the best of my knowledge and belief.

                
                <div class="spacer15 clearFix"></div>
                <label>Place:</label>
                <div class="form-details"><?php if(isset($firstName) && $firstName!='') {echo $Ccity;} ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label>Date:</label>
                <div class="form-details"><?php
                                       if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
                                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                                         }
                                ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label>Signature of the Candidate:</label>
                <div class="form-details"><?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></div>
            </li>
	</ul>
    </div>
    </div>
</div>
