<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Ref. No.<?=$instituteSpecId?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:90%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/alliance/logo2.jpg" alt="Alliance College of Engineering and Design" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">Alliance College of Engineering and Design</h2>
				<div style="text-align:left;margin-left:20px">
					Chikkahagade Cross, Chandapura-Anekal Main Road<br/>
					Anekal, Bangalore - 562 106, Karnataka, India<br/>
					Tel.: +91 80 30938050/1/2/3<br/>
					E-Mail: aced@alliance.edu.in<br>
					Website: www.ced.alliance.edu.in<br>
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
           
            <div class="reviewLeftCol" style="width:940px">
				 <div class="reviewTitleBox">
                <strong>Course Preference:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>-->
            </div>
                <div class="formGreyBox">
                    <ul>
                        <li>
                           <?php //if($profile_data['firstName']):?>
                            <div class="personalInfoCol">
                                <label>Year:</label>
                                <span>2013</span>
                            </div>
                            <?php //endif;?>
                            <?php //if($profile_data['middleName']):?>
                            <div class="personalInfoCol">
                                <label>Entry:</label>
                                <span><?=$Alliance_entryType?></span>
                            </div>
                            <div class="clearFix"></div>
                        </li>
                    </ul>
                </div>
                
                <div class="spacer20 clearFix"></div>
				
                <ul class="reviewChildLeftCol">

                    <li>
                        <label>First Preference:</label>
                        <span><?php echo $Alliance_coursePref1; ?></span>
                    </li>

                    <li>
                        <label>Second Preference:</label>
                        <span><?php echo $Alliance_coursePref2; ?></span>
                    </li>

					
					<li>
                        <label>Third Preference:</label>
                        <span><?php echo $Alliance_coursePref3; ?></span>
                    </li>
					
                </ul>
                 <div class="spacer20 clearFix"></div>
				  <div class="reviewTitleBox">
                <strong>Applicant's Information:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>-->
            </div>
				  
				<ul class="reviewChildLeftCol">

                    <li>
                        <label>Name:</label>
                        <span><?php echo $firstName.' '.$middleName.' '.$lastName; ?></span>
                    </li>
				</ul>
				<div class="clearFix"></div>
				<ul class="reviewChildLeftCol">

                    

                    <li>
                        <label>Gender:</label>
                        <span><?php echo $gender; ?></span>
                    </li>

					
					<li>
                        <label>Blood Group:</label>
                        <span><?php echo $bloodGroup; ?></span>
                    </li>
					
					<li>
                        <label>Nationality:</label>
                        <span><?php echo $Alliance_nationality; ?></span>
                    </li>
					
                </ul>
				
				<ul class="reviewChildRightCol">

                   
                    <li>
                        <label>Date of Birth:</label>
                        <span><?php echo str_replace("/","-",$dateOfBirth); ?></span>
                    </li>

					
					<li>
                        <label>Category:</label>
                        <span><?php echo $Alliance_category; ?></span>
                    </li>
					
					<li>
                        <label>Citizenship:</label>
                        <span><?php echo $Alliance_nationality=="FOREIGN NATIONAL"?$Alliance_citizenship:''; ?></span>
                    </li>
					
                </ul>
				
				 <div class="spacer20 clearFix"></div>
				  <div class="reviewTitleBox">
                <strong>Applicant's contact details for correspondence:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>-->
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
                        <label>Pin Code:</label>
                        <span><?php echo $Cpincode; ?></span>
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
                        <span><?php echo $Cstate; ?></span>
                    </li>

					
					<li>
                        <label>Country:</label>
                        <span><?php echo $Ccountry; ?></span>
                    </li>
					
					<li>
                        <label>&nbsp;</label>
                        <span>&nbsp;</span>
                    </li>
					
					<li>
                        <label>Alternative E-mail:</label>
                        <span><?php echo $altEmail; ?></span>
                    </li>
					
                </ul>
				
				<div class="spacer20 clearFix"></div>
				  <div class="reviewTitleBox">
                <strong>Parents' contact details</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>-->
            </div>
				  

				<ul class="reviewChildLeftCol">

                    <li>
                        <label>&nbsp;</label>
                        <span><b>Father</b></span>
                    </li>

					
					<li>
                        <label>Name:</label>
                        <span><?=$fatherName?></span>
                    </li>
					
					<li>
                        <label>Occupation:</label>
                        <span><?php echo $fatherOccupation;?></span>
                    </li>
					
					<li>
                        <label>Organization:</label>
                        <span><?php echo $Alliance_fatherOrganization; ?></span>
                    </li>
					
					<li>
                        <label>Designation:</label>
                        <span><?=$fatherDesignation?></span>
                    </li>
					
					<li>
                        <label>E-mail:</label>
                        <span><?php echo $Alliance_fatherEmail;?></span>
                    </li>
					
					<li>
                        <label>Mobile:</label>
                        <span><?php echo $Alliance_fatherMobile; ?></span>
                    </li>
					
                </ul>
				
				<ul class="reviewChildRightCol">

                   
                    <li>
                        <label>&nbsp;</label>
                        <span><b>Mother</b></span>
                    </li>

					
					<li>
                        <label>&nbsp;</label>
                        <span><?php echo $MotherName; ?></span>
                    </li>
					
					<li>
                        <label>&nbsp;</label>
                        <span><?=$MotherOccupation?></span>
                    </li>
					
					<li>
                        <label>&nbsp;</label>
                        <span><?php echo $Alliance_motherOrganization; ?></span>
                    </li>
					
					<li>
                        <label>&nbsp;</label>
                        <span><?php echo $MotherDesignation; ?></span>
                    </li>
					
					<li>
                        <label>&nbsp;</label>
                        <span><?=$Alliance_motherEmail?></span>
                    </li>
					
					<li>
                        <label>&nbsp;</label>
                        <span><?php echo $Alliance_motherMobile; ?></span>
                    </li>
							
					
                </ul>
				
				
					 <div class="clearFix"></div>
				 
				  
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
					

					
                </ul>
				
				<div class="spacer20 clearFix"></div>
            </div>
            <!--<div class="profilePic">
                <img width="195" height="192" src="<?php echo $profileImage;?>" alt="Profile Pic" />
            </div>-->
            <div class="picBox" style="border:none"></div>
        </div>
        <!--Personal Info Ends here-->
        
        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Educational qualifications:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>

            	<div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
                    	<p class="educationCol word-wrap">Name of the Examination/Degree</p>
                        <p class="educationCol word-wrap">Name of the Institution</p>
                        <p class="educationCol word-wrap">Name of the University/Board</p>
						<p class="educationYearCol">State</p>
                        <p class="educationYearCol">Year of Passing</p>
                        <p class="educationSmallCol">Percentage Scored</p>
                    </div>
                   
                <ul>
                	<li>
                        <div class="formAutoColumns" style=" padding: 5px 10px 0;">
                             <span class="educationCol word-wrap">X</span>
                            <span class="educationCol word-wrap"><?php echo $class10School;?></span>
                            <span class="educationCol word-wrap"><?php echo $class10Board;?></span>
							<span class="educationYearCol"><?php echo $Alliance_10thState;?></span>
                            <span class="educationYearCol"><?php echo $class10Year;?></span>
                            <span class="educationSmallCol"><?php echo $class10Percentage;?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns" style=" padding: 5px 10px 0;">
                            <span class="educationCol word-wrap">XII</span>
                            <span class="educationCol word-wrap"><?php echo $class12School;?></span>
                            <span class="educationCol word-wrap"><?php echo $class12Board;?></span>
							<span class="educationYearCol"><?php echo $Alliance_12thState;?></span>
                            <span class="educationYearCol"><?php echo $class12Year;?></span>
                            <span class="educationSmallCol"><?php echo $class12Percentage;?></span>
						</div>
                    </li>
					
					      <?php //$count_exam = count($exam_array); 
			for($j=1;$j<=4;$j++):?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
                    <li>
                        <div class="formAutoColumns" style=" padding: 5px 10px 0;">
                            <span class="educationCol word-wrap"><?php echo ${'graduationExaminationName_mul_'.$j};?></span>
                            <span class="educationCol word-wrap"><?php echo ${'graduationSchool_mul_'.$j};?></span>
                            <span class="educationCol word-wrap"><?php echo ${'graduationBoard_mul_'.$j};?></span>
                            <span class="educationYearCol"><?php echo ${'Alliance_mul_'.$j.'_State'};?></span>
							<span class="educationYearCol"><?php echo ${'graduationYear_mul_'.$j};?></span>
                            <span class="educationSmallCol"><?php echo ${'graduationPercentage_mul_'.$j};?></span>
						</div>
                    </li>
                    <?php endif;endfor; //endif;?>
                    
                  
                
                </ul>
				</div>
		
		   <div class="reviewLeftCol" style="width:940px">
				<div class="spacer20 clearFix"></div>
				<ul class="reviewChildLeftCol">

                    <li>
                        <label>10th Subjects:</label>
                        <span><?php echo $Alliance_10thSubjects; ?></span>
                    </li>

                   
                    <li>
                        <label>12th Subjects:</label>
                        <span>Details below in section "Class XII Subject details"</span>
                    </li>
					
					<?php
					for($i=1;$i<=4;$i++){
						if(${'graduationExaminationName_mul_'.$i}){
					?>
					<li>
                        <label><?=${'graduationExaminationName_mul_'.$i}?> Subjects:</label>
                        <span><?php echo${'Alliance_mul_'.$i.'_Subjects'}; ?></span>
                    </li>
					<?
					
						}
					}
					
					?>

                </ul>
                <div class="clearFix"></div>
				
				
				  <div class="reviewTitleBox">
                <strong>Class XII Subject details:</strong>
				</div>
				<table style="border:1px solid #000" width="100%">
				<tr>
					<td>
						Subject
					</td>
					<td>
						Max. Marks
					</td>
					<td>
						Marks Obtained
					</td>
					<td>
						%
					</td>
				</tr>
				<tr>
					<td>
						Physics
					</td>
					<td>
						<?=$Alliance_MaxMarksPhysics?>
					</td>
					<td>
						<?=$Alliance_ObtainedMarksPhysics?>
					</td>
					<td>
						<?=$Alliance_AggPhysics?>
					</td>

				</tr>
				<tr>
					<td>
						Mathematics
					</td>
					<td>
						<?=$Alliance_MaxMarksMaths?>
					</td>
					<td>
						<?=$Alliance_ObtainedMarksMaths?>
					</td>
					<td>
						<?=$Alliance_AggMaths?>
					</td>

				</tr>
				
				<tr>
					<td>
						<?=$Alliance_3rdSubject?$Alliance_3rdSubject:"Chemistry/Electronics<br/>/Computer Science"?>
					</td>
					<td>
						<?=$Alliance_MaxMarks3rdSubject?>
					</td>
					<td>
						<?=$Alliance_ObtainedMarks3rdSubject?>
					</td>
					<td>
						<?=$Alliance_Agg3rdSubject?>
					</td>

				</tr>
				
				<tr>
					<td>
						English
					</td>
					<td>
						<?=$Alliance_MaxMarksEnglish?>
					</td>
					<td>
						<?=$Alliance_ObtainedMarksEnglish?>
					</td>
					<td>
						<?=$Alliance_AggEnglish?>
					</td>

				</tr>
			</table>
				
				 <div class="spacer20 clearFix"></div>
				  <div class="reviewTitleBox">
                <strong>Entrance Examination Score:</strong>
				</div>
				  
				  
				  <table style="border:1px solid #000" width="100%">
				<tr>
					<td>
						Examination
					</td>
					<td>
						Date
					</td>
					<td>
						Registration Number<br/>(if any)
					</td>
					<td>
						Score/Rank Obtained<br/>(Attach copy of rank certificate)
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
						<?=$Alliance_jeeAdvancedDate?>
					</td>
					<td>
						<?=$Alliance_jeeAdvancedRegistrationNumber?>
					</td>
					<td>
						<?=$Alliance_jeeAdvancedScore?>
					</td>
				</tr>
				
				<tr>
					<td>
						AUEET
					</td>
					<td>
						<?=$Alliance_AUEETDate?>
					</td>
					<td>
						<?=$Alliance_AUEETRegistrationNumber?>
					</td>
					<td>
					    <?=$Alliance_AUEETRank?>
					</td>
				</tr>
				
				<tr>
					<td>
						Karnataka CET
					</td>
					<td>
						<?=$Alliance_KCETDate?>
					</td>
					<td>
						<?=$Alliance_KCETRegistrationNumber?>
					</td>
					<td>
						<?=$Alliance_KCETRank?>
					</td>
				</tr>
			</table>
				  
				   <div class="spacer20 clearFix"></div>
				  <div class="reviewTitleBox">
                <strong>Statement of purpose:</strong>
				</div>
<div id="responsibilityList">

                  		<label style="font-weight:bold; width:700px">What motivates you to apply to the Alliance College of Engineering and Design?</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
<?php echo $Alliance_motivation; ?>
		</div>
             </div>

<div id="responsibilityList">

                  		<label style="font-weight:bold; width:700px">What is your career vision and why is this choice meaningful to you?</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
<?php echo $Alliance_vision; ?>
		</div>
             </div>
  <div class="spacer20 clearFix"></div>
				  <div class="reviewTitleBox">
                <strong>Hostel accomodation:</strong>
				</div>
				  
				  <div id="responsibilityList">

                  		<label style="font-weight:bold; width:700px">Do you need hostel accommodation?</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
<?php echo $Alliance_hostel; ?>
		</div>
             </div>

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
3. I undertake to abide by the rules and regulations of the Alliance College of Engineering and Design as
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


