<link href="/public/css/onlineforms/jgiidea/JGIiDEA_styles.css" rel="stylesheet" type="text/css"/>
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
            <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  <strong class="applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
			      <?php } ?>
    	<div class="previewHeader">
        	<!--<div class="formNumb">
        		<div class="previewFieldBox" style="width:100%;">
                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>    	
            	<div class="clearFix spacer15"></div>    
                <p>Index Number (for official use only)</p>
            </div>-->
        	
            
            <div class="clearFix"></div>
            
		<div id="custom-form-header">
		<div class="app-left-box">
				<div style="float:right">Form No. <?php if(isset($instituteSpecId) && $instituteSpecId!=''){echo $instituteSpecId;} ?></div>
				 <div class="clearFix spacer10"></div>
		    <div class="inst-name" style="width:78%;margin-left:0px">
			<img src="/public/images/onlineforms/institutes/ansal/logo2.gif" alt="Ansal University" style="float:left" />
					<div style="float:right">
					<h2 style="font-size:30px;">Ansal University</h2>
					<div style="text-align:center;font-size:20px;">
					Programme Registration Form <br/>Session 2013-14
					</div>
					</div>
		    </div>
		    <div class="clearFix spacer15"></div>
		    
		</div>
	    </div>
    
            <div class="clearFix"></div>
            <!--<div class="form-inst">
            	<strong>The application form has to be filled in clearly and legibly in your own handwriting.</strong><br />
				(Incomplete forms may be rejected.)
            </div>
            <div class="spacer5 clearFix"></div>-->
        </div>
        
        <div class="clearFix"></div>
            <div class="reviewTitleBox">
                <strong>Personal Details:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>-->
            </div>
            <div class="reviewLeftCol">
                <div class="formGreyBox">
                    <ul>
                        <li>
                            <div class="personalInfoCol">
                                <label>First Name:</label>
                                <span><?php echo $firstName;?></span>
                            </div>
                            <div class="personalInfoCol">
                                <label>Middle Name:</label>
                                <span><?php if(empty($middleName)) {echo "&nbsp;&nbsp;&nbsp;&nbsp;-";} else {echo $middleName;}?></span>
                            </div>
                            <div class="personalInfoCol">
                                <label>Last Name:</label>
                                <span><?php echo $lastName;?></span>
                            </div>
                            <div class="clearFix"></div>
                        </li>
                    </ul>
                </div>
                
                <div class="spacer20 clearFix"></div>

                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Gender:</label>
                        <span><?php echo $gender;?></span>
                    </li>
                    <li>
                        <label>Email Address:</label>
                        <span><?php echo $email;?></span>
                    </li>
                    <li>
                        <label>Nationality:</label>
                        <span><?php echo $nationality;?></span>
                    </li>
                    <li>
                        <label>Application Category:</label>
                        <span><?php echo $applicationCategory;?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label> Date of birth:</label>
                        <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
                    </li>

                    <li>
                        <label>Alternate Email:</label>
                        <span><?php echo $altEmail;?></span>
                    </li>

                    <li>
                        <label>Marital Status:</label>
                        <span><?php echo $maritalStatus;?></span>
                    </li>

                    <li>
                        <label>Religion:</label>
                        <span><?php echo $religion;?></span>
                    </li>
                </ul>
            </div>
            <!--<div class="profilePic">
                <img width="195" height="192" src="<?php echo $profileImage;?>" alt="Profile Pic" />
            </div>-->
            <div class="picBox"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
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
                    <li>
                        <label>House Number:</label>
                        <span><?php echo $houseNumber;?></span>
                    </li>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo $area;?></span>
                    </li>
                    <li>
                        <label>Country:</label>
                        <span><?php echo $country;?></span>
                    </li>
                    <li>
                        <label>City:</label>
                        <span><?php echo $city;?></span>
                    </li>

                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo $streetName;?></span>
                    </li>

                    <li>
                        <label>State:</label>
                        <span><?php echo $state;?></span>
                    </li>

                    <li>
                        <label>Pincode:</label>
                        <span><?php echo $pincode;?></span>
                    </li>

                    <li>
                        <label>Landline:</label>
                        <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
                    </li>
                </ul>
            </div>
            
            <div class="spacer5 clearFix"></div>
            <h3>Correspondence Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>House Number:</label>
                        <span><?php echo $ChouseNumber;?></span>
                    </li>

                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo $Carea;?></span>
                    </li>

                    <li>
                        <label>Country:</label>
                        <span><?php echo $Ccountry;?></span>
                    </li>

                    <li>
                        <label>City:</label>
                        <span><?php echo $Ccity;?></span>
                    </li>

                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo $CstreetName;?></span>
                    </li>

                    <li>
                        <label>State:</label>
                        <span><?php echo $Cstate;?></span>
                    </li>

                    <li>
                        <label>Pincode:</label>
                        <span><?php echo $Cpincode;?></span>
                    </li>

                    <li>
                        <label>Landline:</label>
                        <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
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
                        <span><?php echo $fatherName;?></span>
                    </li>

                    <li>
                        <label>Occupation:</label>
                        <span><?php echo $fatherOccupation;?></span>
                    </li>

                    <li>
                        <label>Designation:</label>
                        <span><?php echo $fatherDesignation;?></span>
                    </li>

                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Mother's Name:</label>
                        <span><?php echo $MotherName;?></span>
                    </li>

                    <li>
                        <label>Occupation:</label>
                        <span><?php echo $MotherOccupation;?></span>
                    </li>

                    <li>
                        <label>Designation:</label>
                        <span><?php echo $MotherDesignation;?></span>
                    </li>
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
     
        </div>
        <!--Education Info Ends here-->

    	<div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Qualifying Examination:</strong>
            </div>
        
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>JEE Exam Date:</label>
                        <span><?php echo $jeeDateOfExaminationAdditional;?></span>
                    </li>
                    <li>
                        <label>AUEE Exam Date:</label>
                        <span><?php echo $ANSAL_AUEEDate;?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>JEE Score:</label>
                        <span><?php echo $jeeScoreAdditional;?></span>
                    </li>
                    <li>
                        <label>AUEE Score:</label>
                        <span><?php echo $ANSAL_AUEEScore;?></span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="spacer20 clearFix"></div>

	<div class="rolesResponsiblity">
     	<div class="reviewTitleBox"><strong>Interview/Councelling Location</strong></div>
        <div id="responsibilityList">
                                        <?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=''){ ?>
                                        <?php if($preferredGDPILocation=='95') echo "Gurgaon"; ?>
                                        <?php } ?>

        </div>
	</div>
	 <div class="spacer20 clearFix"></div>

<div class="rolesResponsiblity">
     	

	<div id="responsibilityList">
	<li>
					<label style="font-weight:bold; width:700px">DECLARATION:</label>
				<div class="spacer15 clearFix"></div>
				<div style="padding-left:33px">

By submitting the application, the applicant certify and declares that<br/>
the information provided in the application form is complete and accurate. The applicant/ candidate understand and agree that misrepresentation or omission of facts will justify the denial of admission, cancellation of admission or any other action against the applicant/ candidate. The applicant/ candidate is aware that the Admission granted on the basis of the incorrect/ false information and misrepresentation will ipso facto be declared null and void. In all matters relating to the admissions, the decision of Ansal University will be final and binding on the applicant. The applicant/ candidate has read and do hereby consent to the Terms & Conditions as mentioned.

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


