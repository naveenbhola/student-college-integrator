<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
<style>
.educationCol, .educationSmallCol, .educationYearCol {    float: left;    padding: 5px 11px 5px 0;    width: 142px;}
body {color: #000000;font: 13px Arial,Helvetica,Sans Sarif;}
</style>
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Form No. <?php if(isset($instituteSpecId) && $instituteSpecId!=''){echo $instituteSpecId;} ?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:85%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/niu/logo2.gif" alt="Noida International University" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">Noida International University</h2>
				<div style="text-align:left;margin-left:20px">
					309 Jaipuria Plaza, D-Block, Sector 26, Noida, 201301<br/>
					Tel.: 8467878805, 8467878838<br/>
					E-Mail: Admissions@niu.ac.in<br>
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
				Bachelor of Technology (B. Tech.)
			</div>
        
        <div class="spacer15 clearFix"></div>
           
                <ul class="reviewChildLeftCol">

                    <li>
                        <label>Course Name:</label>
                        <span>B. Tech</span>
                    </li>

                    <li>
                        <label>Name of the Candidate:</label>
                        <span><?php echo $firstName;?><?php if(empty($middleName)) {echo "&nbsp;";} else {echo '&nbsp;'.$middleName;}?><?php echo '&nbsp;'.$lastName;?></span>
                    </li>

                    <li>
                        <label>Father's Name:</label>
                        <span><?php echo $fatherName;?></span>
                    </li>
                    <li>
                        <label>Physical Standard:</label>
                        <span>Height <?=$NIU_HeightFeet;?> ft. <?=$NIU_HeightInch;?> Inch</span>
                    </li>
                    <li>
                        <label>Gender:</label>
                        <span><?=$gender;?></span>
                    </li>
                    <li>
                        <label>Nationality:</label>
                        <span><?=$NIU_Nationality;?></span>
                    </li>		    
                </ul>

		<ul class="reviewChildRightCol">
                    <li>
                        <label>&nbsp;</label>
                        <span>&nbsp;</span>
                    </li>
                    <li>
                        <label>&nbsp;</label>
                        <span>&nbsp;</span>
                    </li>
                    <li>
                        <label>Mother's Name:</label>
                        <span><?php echo $MotherName;?></span>
                    </li>
                    <li>
                        <label>&nbsp;</label>
                        <span>&nbsp;</span>
                    </li>
                    <li>
                        <label>Category:</label>
                        <span><?=$NIU_Category;?></span>
                    </li>
		</ul>

                 <div class="spacer20 clearFix"></div>
		 

	    <div class="reviewTitleBox">
                <strong>Foreign National Information:</strong>
            </div>
		<ul class="reviewChildLeftCol">

			<li>
                        <label>Passport No.:</label>
                        <span><?=$NIU_passportNumber; ?></span>
                    </li>
                   
                    <li>
                        <label>Valid Till:</label>
                        <span><?php echo $NIU_passportTillDate; ?></span>
                    </li>
                    <li>
                        <label>Time period for non-Indian citizens who are living in India:</label>
                        <span><?=$NIU_timePeriodYear;?> Years <?=$NIU_timePeriodMonth?> Months</span>
                    </li>
                    <li>
                        <label>Exam Details:</label>
                        <span><?php echo $NIU_ExamTaken; ?></span>
                    </li>
		    <?php if(isset($NIU_ExamTaken) && $NIU_ExamTaken!='' && strpos($NIU_ExamTaken,'TOEFL')!==false){ ?>
                    <li>
                        <label>TOEFL Exam Roll No.:</label>
                        <span><?php echo $NIU_ExamRollNumber; ?></span>
                    </li>
		    <?php } ?>
		    <?php if(isset($NIU_ExamTaken) && $NIU_ExamTaken!='' && strpos($NIU_ExamTaken,'IELTS')!==false){ ?>
                    <li>
                        <label>IELTS Exam Roll No.:</label>
                        <span><?php echo $NIU_ExamRollNumberIELTS; ?></span>
                    </li>
		    <?php } ?>
		    <?php if(isset($NIU_ExamTaken) && $NIU_ExamTaken!='' && strpos($NIU_ExamTaken,'SAT')!==false){ ?>
                    <li>
                        <label>SAT Exam Roll No.:</label>
                        <span><?php echo $NIU_ExamRollNumberSAT; ?></span>
                    </li>
		    <?php } ?>
				
                </ul>
		<ul class="reviewChildRightCol">
                    <li>
                        <label>Issued Date:</label>
                        <span><?php echo $NIU_passportIssueDate;?></span>
                    </li>
                    <li>
                        <label>Issuing Country:</label>
                        <span><?php echo $NIU_issuingCountry;?></span>
                    </li>
                    <li>
                        <label>&nbsp;</label>
                        <span>&nbsp;</span>
                    </li>
                    <li>
                        <label>Visa Type:</label>
                        <span><?php echo $NIU_visaType; ?></span>
                    </li>
                    <li>
                        <label>&nbsp;</label>
                        <span>&nbsp;</span>
                    </li>
		    <?php if(isset($NIU_ExamTaken) && $NIU_ExamTaken!='' && strpos($NIU_ExamTaken,'TOEFL')!==false){ ?>
                    <li>
                        <label>TOEFL Exam Score:</label>
                        <span><?php echo $NIU_ExamScore; ?></span>
                    </li>
		    <?php } ?>
		    <?php if(isset($NIU_ExamTaken) && $NIU_ExamTaken!='' && strpos($NIU_ExamTaken,'IELTS')!==false){ ?>
                    <li>
                        <label>IELTS Exam Score:</label>
                        <span><?php echo $NIU_ExamScoreIELTS; ?></span>
                    </li>
		    <?php } ?>
		    <?php if(isset($NIU_ExamTaken) && $NIU_ExamTaken!='' && strpos($NIU_ExamTaken,'SAT')!==false){ ?>
                    <li>
                        <label>SAT Exam Score:</label>
                        <span><?php echo $NIU_ExamScoreSAT; ?></span>
                    </li>
		    <?php } ?>
		</ul>

				  
	<div class="spacer20 clearFix"></div>

	    <div class="reviewTitleBox">
                <strong>Personal Information:</strong>
            </div>
				
		<ul class="reviewChildLeftCol">

			<li>
                        <label>Admission Category:</label>
                        <span><?=$NIU_AdmissionCategory; ?></span>
                    </li>
                   
                    <li>
                        <label>Date of Birth:</label>
                        <span><?php echo str_replace("/","-",$dateOfBirth); ?></span>
                    </li>
				
                </ul>
		<ul class="reviewChildRightCol">
                    <li>
                        <label>Blood Group:</label>
                        <span><?php echo $bloodGroup;?></span>
                    </li>
		</ul>
				
	 <div class="spacer20 clearFix"></div>

	<!-- Address Section -->	 
	  <div class="reviewTitleBox">
                <strong>Correspondence Address:</strong>
            </div>
				  
				<ul class="reviewChildLeftCol">

                    <li>
						 <?php
					  $Caddress = $ChouseNumber;
					  if($CstreetName) $Caddress .= ', '.$CstreetName;
					  if($Carea) $Caddress .= ', '.$Carea;
				?>
                        <label>Address:</label>
                        <span><?php echo $Caddress; ?></span>
                    </li>
				</ul>
				<div class="clearFix"></div>
				
				<ul class="reviewChildLeftCol">
                   
                    <li>
                        <label>State:</label>
                        <span><?php echo $Cstate; ?></span>
                    </li>

					
					<li>
                        <label>Country:</label>
                        <span><?php echo $Ccountry; ?></span>
                    </li>
					
                </ul>
				
				<ul class="reviewChildRightCol">

                    <li>
                        <label>Pin Code:</label>
                        <span><?php echo $Cpincode; ?></span>
                    </li>
				
                </ul>
				

		<div class="spacer20 clearFix"></div>

	  <div class="reviewTitleBox">
                <strong>Permanent Address:</strong>
            </div>
				  
				<ul class="reviewChildLeftCol">

                    <li>
						 <?php
					  $address = $houseNumber;
					  if($streetName) $address .= ', '.$streetName;
					  if($area) $address .= ', '.$area;
				?>
                        <label>Address:</label>
                        <span><?php echo $address; ?></span>
                    </li>
				</ul>
				<div class="clearFix"></div>
				<ul class="reviewChildLeftCol">

                    <li>
                        <label>Pin Code:</label>
                        <span><?php echo $pincode; ?></span>
                    </li>

					
					<li>
                        <label>Tel. (Residence):</label>
                        <span><?php $phoneNumber = ($landlineSTDCode=='')?$landlineNumber:$landlineSTDCode."-".$landlineNumber;?>
					  <?php echo $phoneNumber; ?></span>
                    </li>
					
					<li>
                        <label>Mobile:</label>
                        <span><?php echo $mobileNumber;?></span>
                    </li>
					
					<li>
                        <label>E-mail:</label>
                        <span><?php echo $email; ?></span>
                    </li>
					
                </ul>
				
				<ul class="reviewChildRightCol">

                   
                    <li>
                        <label>State:</label>
                        <span><?php echo $state; ?></span>
                    </li>

					
					<li>
                        <label>Country:</label>
                        <span><?php echo $country; ?></span>
                    </li>
					
					<li>
                        <label>&nbsp;</label>
                        <span>&nbsp;</span>
                    </li>
									
                </ul>
	<!-- Address Section -->	 

				
	<div class="spacer20 clearFix"></div>


	<!-- Other details section -->
	<div class="reviewTitleBox">
                <strong>Hostel Details:</strong>
        </div>
				  
	<ul class="reviewChildLeftCol">
	    <li>
		<label>Hostel Accomodation:</label>
		<span><?php echo $NIU_hostel; ?></span>
	    </li>
	    <li>
		<label>Transaport Required:</label>
		<span><?php echo $NIU_transportRequired; ?></span>
	    </li>
	</ul>
			
	<ul class="reviewChildRightCol">
	    <li>
		<label>Type of Accomodation:</label>
		<span><?php echo $NIU_AC_NONAC; ?></span>
	    </li>
	</ul>
	<!-- Other details section -->
				
				
	<div class="spacer20 clearFix"></div>
				
				
	<!-- Exam details section -->
	  <div class="reviewTitleBox">
		<strong>Pre-qualifying Tests:</strong>
	</div>
	  
	  
	  <table style="border:1px solid #000" width="100%">
	<tr>
		<td width='30%'>
			<b>Examination</b>
		</td>
		<td width='30%'>
			<b>Date</b>
		</td>
		<td width='20%'>
			<b>Roll/ID</b>
		</td>
		<td width='20%'>
			<b>Score/Rank Obtained</b>
		</td>
	</tr>
	
	<tr>
		<td>
			JEE Main
		</td>
		<td>
			<?=$jeeDateOfExaminationAdditional?>
		</td>
		<td>
			<?=$jeeRollNumberAdditional?>
		</td>
		<td>
			<?=$jeeScoreAdditional?>
		</td>
	</tr>
	
	<tr>
		<td>
			JEE (Advanced)
		</td>
		<td>
			<?=$NIU_jeeAdvancedDate?>
		</td>
		<td>
			<?=$NIU_jeeAdvancedRegistrationNumber?>
		</td>
		<td>
			<?=$NIU_jeeAdvancedScore?>
		</td>
	</tr>
	
	<tr>
		<td>
			UPTU
		</td>
		<td>
			<?=$NIU_UPTUDate?>
		</td>
		<td>
			<?=$NIU_UPTURegistrationNumber?>
		</td>
		<td>
		    <?=$NIU_UPTURank?>
		</td>
	</tr>
	
	<tr>
		<td>
			<?=$NIU_OtherExamName?>
		</td>
		<td>
			<?=$NIU_OtherDate?>
		</td>
		<td>
			<?=$NIU_OtherRegistrationNumber?>
		</td>
		<td>
			<?=$NIU_OtherRank?>
		</td>
	</tr>
	</table>				  
<!-- Exam details section -->
				
<div class="spacer20 clearFix"></div>

        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Educational qualifications:</strong>
            </div>

            	<div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
                    	<p class="educationCol word-wrap">Name of the Examination/Degree</p>
                        <p class="educationCol word-wrap">Board/University</p>
                        <p class="educationYearCol">Passing Year</p>
                        <p class="educationCol word-wrap">Subjects</p>
			<p class="educationCol word-wrap">School/College Attended</p>
                        <p class="educationSmallCol">%age/ Div</p>
                    </div>
                   
                <ul>
                	<li>
                        <div class="formAutoColumns" style=" padding: 5px 10px 0;">
                             <span class="educationCol word-wrap">High School/Equivalent</span>
                            <span class="educationCol word-wrap"><?php echo $class10Board;?></span>
                            <span class="educationYearCol"><?php echo $class10Year;?></span>
			    <span class="educationCol word-wrap"><?php echo $NIU_10thSubjects;?></span>
                            <span class="educationCol word-wrap"><?php echo $class10School;?></span>
                            <span class="educationSmallCol"><?php echo $class10Percentage;?></span>
			</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns" style=" padding: 5px 10px 0;">
                             <span class="educationCol word-wrap">Intermediate/Equivalent</span>
                            <span class="educationCol word-wrap"><?php echo $class12Board;?></span>
                            <span class="educationYearCol"><?php echo $class12Year;?></span>
			    <span class="educationCol word-wrap"><?php echo $NIU_12thSubjects;?></span>
                            <span class="educationCol word-wrap"><?php echo $class12School;?></span>
                            <span class="educationSmallCol"><?php echo $class12Percentage;?></span>
			</div>
                    </li>
		    
					      <?php //$count_exam = count($exam_array); 
			for($j=1;$j<=4;$j++):?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
                    <li>
                        <div class="formAutoColumns" style=" padding: 5px 10px 0;">
                            <span class="educationCol word-wrap"><?php echo ${'graduationExaminationName_mul_'.$j};?></span>
                            <span class="educationCol word-wrap"><?php echo ${'graduationBoard_mul_'.$j};?></span>
			    <span class="educationYearCol"><?php echo ${'graduationYear_mul_'.$j};?></span>
                            <span class="educationCol word-wrap"><?php echo ${'NIU_mul_'.$j.'_Subjects'};?></span>
                            <span class="educationCol word-wrap"><?php echo ${'graduationSchool_mul_'.$j};?></span>
                            <span class="educationSmallCol"><?php echo ${'graduationPercentage_mul_'.$j};?></span>
			</div>
                    </li>
                    <?php endif;endfor; //endif;?>		    
					                
                </ul>
	</div>
        <!--Education Info Starts here-->
				
	<div class="spacer20 clearFix"></div>
				 				 

	<!-- Activities details section -->
	<div id="responsibilityList">
		<label style="font-weight:bold; width:700px">Have you been awarded any Scholarship? If yes, please give details</label>
		<div class="spacer15 clearFix"></div>
		<div style="padding-left:33px">
		<?php echo $NIU_scholarship; ?>
		</div>
        </div>

	<div class="reviewTitleBox">
		<strong>Awards/Prizes:</strong>
	</div>
	  
	  
	<table style="border:1px solid #000" width="100%">
	<tr>
		<td width='40%'>
			<b>&nbsp;Award</b>
		</td>
		<td width='25%'>
			<b>&nbsp;Year</b>
		</td>
		<td width='35%'>
			<b>&nbsp;For</b>
		</td>
	</tr>
	
	<tr>
		<td>
			&nbsp;<?=$NIU_awards1Name?>
		</td>
		<td>
			&nbsp;<?=$NIU_awards1Year?>
		</td>
		<td>
			&nbsp;<?=$NIU_awards1For?>
		</td>
	</tr>

	<tr>
		<td>
			&nbsp;<?=$NIU_awards2Name?>
		</td>
		<td>
			&nbsp;<?=$NIU_awards2Year?>
		</td>
		<td>
			&nbsp;<?=$NIU_awards2For?>
		</td>
	</tr>
	</table>
<div class="spacer20 clearFix"></div>
	
	  <div class="reviewTitleBox">
		<strong>Activity Acheivements:</strong>
	</div>
	  
	  
	<table style="border:1px solid #000" width="100%">
	<tr>
		<td width='40%'>
			<b>&nbsp;Name of Activity</b>
		</td>
		<td width='25%'>
			<b>&nbsp;Period of Participation</b>
		</td>
		<td width='35%'>
			<b>&nbsp;Position</b>
		</td>
	</tr>
	
	<tr>
		<td>
			&nbsp;<?=$NIU_activity1Name?>
		</td>
		<td>
			&nbsp;<?=$NIU_activity1Period?>
		</td>
		<td>
			&nbsp;<?=$NIU_activity1Position?>
		</td>
	</tr>

	<tr>
		<td>
			&nbsp;<?=$NIU_activity2Name?>
		</td>
		<td>
			&nbsp;<?=$NIU_activity2Period?>
		</td>
		<td>
			&nbsp;<?=$NIU_activity2Position?>
		</td>
	</tr>
	</table>				  
	
	<!-- Activities details section -->


	<div class="spacer20 clearFix"></div>
				
				
	<!-- Family Information Starts -->                
	<div class="reviewTitleBox">
                <strong>Family Information</strong>
        </div>
				  

	<ul class="reviewChildLeftCol">
                    <li>
                        <label>&nbsp;</label>
                        <span><b>Father</b></span>
                    </li>

		<li>
                        <label>Full Name:</label>
                        <span>&nbsp;<?=$fatherName?></span>
                </li>

		<li>
                        <label>Education level:</label>
                        <span>&nbsp;<?php echo $NIU_fatherEducation;?></span>
                    </li>
					
		<li>
                        <label>Occupation:</label>
                        <span>&nbsp;<?php echo $fatherOccupation;?></span>
                    </li>

			<li>
                        <label>Annual Income (Rs):</label>
                        <span>&nbsp;<?php echo $NIU_fatherIncome;?></span>
                    </li>
			<li>
                        <label>Contact Details:</label>
                        <span>&nbsp;<?php echo $NIU_fatherMobile;?></span>
                    </li>
			<li>
                        <label>E-mail Address:</label>
                        <span>&nbsp;<?php echo $NIU_fatherEmail;?></span>
                    </li>
                </ul>
				
		<ul class="reviewChildRightCol">

                   
                    <li>
                        <span><b>Mother</b></span>
                    </li>

					
		<li>
                        <span>&nbsp;<?php echo $MotherName; ?></span>
                    </li>

			<li>
                        <span>&nbsp;<?php echo $NIU_motherEducation;?></span>
                    </li>
					
					<li>
                        <span>&nbsp;<?=$MotherOccupation?></span>
                    </li>
			<li>
                        <span>&nbsp;<?php echo $NIU_motherIncome;?></span>
                    </li>

			<li>
                        <span>&nbsp;<?php echo $NIU_motherMobile;?></span>
                    </li>
			<li>
                        <span>&nbsp;<?php echo $NIU_motherEmail;?></span>
                    </li>
					
                </ul>
				
	<div class="spacer20 clearFix"></div>
	<!-- Family Information end -->        


	<!-- Other Information Starts -->                
	<div class="reviewTitleBox">
                <strong>Other Information</strong>
        </div>
				  

	<ul class="reviewChildLeftCol">
                    <li>
                        <label>Do you suffer from any Chronic Ailment?</label>
                        <span><?=$NIU_Ailment?></span>
                    </li>

		<li>
                        <label>Have you ever been Suspended, Dismissed or put on academic probation at any School or College?</label>
                        <span><?=$NIU_AcademicProbation?></span>
                </li>
                </ul>
				
		<ul class="reviewChildRightCol">

                   
                    <li>
                        <label>Details:</label>
                        <span><?=$NIU_AilmentDetails?></span>
                    </li>

                    <li>
                        <label>Details:</label>
                        <span><?=$NIU_AcademicProbationDetails?></span>
                    </li>
					
                </ul>
				
	<div class="spacer20 clearFix"></div>
	<!-- Other Information end -->        		

	<!-- Payment Information Starts -->
	<?php if(is_array($paymentDetails)){ ?>
	<?php if($paymentDetails['mode']=='Offline'){ ?>
		<?php $mode = 'Demand Draft'; $amount = $paymentDetails['amount']; $bank=$paymentDetails['bankName'];?>
		<?php $draftNo = $paymentDetails['draftNumber'];?>
		<?php if(strtotime($paymentDetails['draftDate']) && $paymentDetails['draftDate']!='0000-00-00') $draftDate = date("Y-m-d", strtotime($paymentDetails['draftDate'])); else $draftDate = '';?>
	<?php }else if($paymentDetails['mode']=='Online'){ ?>
		<?php $mode = 'Credit Card/Net Banking'; $amount = $paymentDetails['amount']; $bank='';?>
		<?php $draftNo =  $paymentDetails['orderId'];?>
		<?php if(strtotime($paymentDetails['date']) && $paymentDetails['date']!='0000-00-00') $draftDate =  date("Y-m-d", strtotime($paymentDetails['date'])); else $draftDate = '';?>
	<?php }
	}else { ?>
		<?php $mode = ''; $amount =  '';?>
		<?php $draftNo =  '';?>
		<?php $draftDate =  ''; $bank ='';?>
	<?php } ?>
	
	<div class="reviewTitleBox">
                <strong>Payment Information</strong>
        </div>
				  

	<ul class="reviewChildLeftCol">
                    <li>
                        <label>Mode:</label>
                        <span><?=$mode?></span>
                    </li>

		<li>
                        <label>DD No.:</label>
                        <span><?=$draftNo?></span>
                </li>
                </ul>
				
		<ul class="reviewChildRightCol">

                   
                    <li>
                        <label>Bank:</label>
                        <span><?=$bank?></span>
                    </li>

                    <li>
                        <label>Date:</label>
                        <span><?=$draftDate?></span>
                    </li>
					
                </ul>
				
	<div class="spacer20 clearFix"></div>
	<!-- Payment Information end -->        		


	<!-- Form II section -->
        <div class="reviewTitleBox">
                <strong>Form II</strong>
	</div>
	<div id="responsibilityList">
		<label style="font-weight:bold; width:700px">What are your personal and professional goals and how will the course applied for by you at Noida International University help you to accomplish the same?</label>
		<div class="spacer15 clearFix"></div>
		<div style="padding-left:33px">
		<?php echo $NIU_personalGoals; ?>
		</div>
        </div>
	<div id="responsibilityList">
		<label style="font-weight:bold; width:700px">Name and describe your two biggest strengths and weaknesses.</label>
		<div class="spacer15 clearFix"></div>
		<div style="padding-left:33px">
		<?php echo $NIU_strengths; ?>
		</div>
        </div>
	<div id="responsibilityList">
		<label style="font-weight:bold; width:700px">How do you intend to engage, enrich and evolve the core culture of NIU?</label>
		<div class="spacer15 clearFix"></div>
		<div style="padding-left:33px">
		<?php echo $NIU_culture; ?>
		</div>
        </div>
	<div id="responsibilityList">
		<label style="font-weight:bold; width:700px">What does "Revolutionizing Experience" mean to you?</label>
		<div class="spacer15 clearFix"></div>
		<div style="padding-left:33px">
		<?php echo $NIU_experience; ?>
		</div>
        </div>
	<!-- Form II section -->
	     
	     
  <div class="spacer20 clearFix"></div>

        </div>

      <div class="spacer20 clearFix"></div>

            
	  <div class="reviewTitleBox">
                <strong>Declaration:</strong>
	</div>
		
   
	<div id="responsibilityList">
	<li>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
1. I have read and understood the full requirements of the course, eligibility criteria, terms and conditions and
other important information as indicated in the Prospectus/website.<br/>
2. I confirm that the information furnished by me in this Application Form is true to the best of my knowledge. I
understand that any false or misleading statement given by me may lead to the cancellation of admission or
expulsion fromthe course at any stage.<br/>
3. I undertake to abide by the rules and regulations of the NIU College of Engineering and Design as
prescribed from time to time. If I violate at any point of time any of the stipulated rules and regulations, then the
University is free to initiate appropriate disciplinary action against me.
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
					      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
						      echo date("d/m/Y", strtotime($paymentDetails['date']));
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


