<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>

            <div id="custom-form-header">
                <div class="app-left-box">
                    <div style="float:right">Form No. <?=$instituteSpecId?></div>
                    <div class="clearFix spacer10"></div>
                    <div class="inst-name" style="width:87%;margin-left:0px">
                        <img src="/public/images/onlineforms/institutes/empi/logo2.jpg" alt="EMPI Business School" style="float:left" />
                        <div style="float:right">
                            <h2 style="font-size:20px;">EMPI Business School</h2>
                            <div style="text-align:center;">
                                An ISO 9001:2008 Certified Institution<br>
                                EMPI Business School , Camp P.O.: CSKM Educational Complex,<br>Satbari, Chattarpur, New Delhi - 110074<br>
                                Email: admission@empi.ac.in<br>
                                Tel: 91-8800010971, 8800010972
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
        Registration Form: Post Graduate Diploma in Management (PGDM)-2013-15
			</div>

            <div class="spacer15 clearFix"></div>

        <div class="spacer15 clearFix"></div>
            <div class="reviewTitleBox">
                <strong>Personal Details:</strong>
            </div>
            
			<ul class="reviewChildLeftCol">
                <li>
                    <label style="font-weight: bold;font-size:13px;">Applicant's Title:</label>
                    <span style="width:300px;font-size:13px;""><?php echo $EMPITitle;?></span>
                </li>
            </ul>

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
                        <label>Father/Guardian's Title:</label>
                        <span><?php echo $EMPIGuardianTitle;?></span>
                    </li>
                    <li>
                        <label>Date of birth:</label>
                        <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
                    </li>
                    <li>
                        <label>Marital Status:</label>
                        <span><?php echo $maritalStatus;?></span>
                    </li>

                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Father/Guardian's Name:</label>
                        <span><?php if($EMPIGuardian=='Father'){ echo $fatherName;} else {echo $MotherName;};?></span>
                    </li>
                    <li>
                        <label>Age:</label>
                        <span><?php echo $EMPIAge;?></span>
                    </li>
                    <li>
                        <label>Category:</label>
                        <span><?php echo $EMPICategory;?></span>
                    </li>
                </ul>
            </div>
            <div class="picBox" style="border:none"></div>
        </div>
        <!--Personal Info Ends here-->
        
        <!--Contact Info Starts here-->
        <div class="contactInfoSection">
            <div class="reviewTitleBox">
                <strong>Contact Information:</strong>
            </div>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Native Place:</label>
                        <span><?php echo $EMPINativePlace;?></span>
                    </li>
                    <li>
                        <label>Country:</label>
                        <span><?php echo $country;?></span>
                    </li>
                </ul>

                <ul class="reviewChildRightCol">
                    <li>
                        <label>State:</label>
                        <span><?php echo $state;?></span>
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

		<!-- Exams Start here -->
		<?php
		$testsArray = explode(",",$EMPITestNames);
		?>
		<div class="familyInfoSection">
			<div class="reviewTitleBox">
				<strong>Pre-qualifying Test (s) Taken:</strong>
			</div>

			<div class="reviewLeftCol"   style="width:900px !important">

				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">CAT Score:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catScoreAdditional;?><?php endif; ?></span>
					</li>

					<li>
						<label style="width:150px !important">CAT Percentile:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catPercentileAdditional;?><?php endif; ?></span>
					</li>

				</ul>


				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">XAT Score:</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatScoreAdditional;?><?php endif; ?></span>
					</li>

					<li>
						<label style="width:150px !important">XAT Percentile:</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatPercentileAdditional;?><?php endif; ?></span>
					</li>

				</ul>

				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">MAT Score:</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matScoreAdditional;?><?php endif; ?></span>
					</li>

					<li>
						<label style="width:150px !important">MAT Percentile:</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matPercentileAdditional;?><?php endif; ?></span>
					</li>

				</ul>

				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">ATMA Score:</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $atmaScoreAdditional;?><?php endif; ?></span>
					</li>

					<li>
						<label style="width:150px !important">ATMA Percentile:</label>
						<span><?php if(in_array("ATMA",$testsArray)): ?><?php echo $atmaPercentileAdditional;?><?php endif; ?></span>
					</li>

				</ul>


				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">CMAT Score:</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatScoreAdditional;?><?php endif; ?></span>
					</li>

					<li>
						<label style="width:150px !important">CMAT Percentile:</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatPercentileEMPI;?><?php endif; ?></span>
					</li>

				</ul>


			</div>
		</div>
		<!-- Exams End here -->

		

        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Academic Details:</strong>
            </div>
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
    
            <div class="spacer10 clearFix"></div>    
            <div class="reviewLeftCol">
              <ul class="reviewChildLeftCol">
                   <li>
                       <label>Graduation Completed ?:</label>
                       <span><?php echo $EMPIGraduationCompleted;?></span>
                   </li>
              </ul>
            </div>

	     <div class="spacer20 clearFix"></div>
    

        </div>
        <!--Education Info Ends here-->
	

		<?php
			if($EMPIReferredBy=='Coaching Center'){
				$EMPIName = $EMPICoachingName;
				$EMPIEmail = $EMPICoachingEmail;
				$EMPIAddress = $EMPICoachingAddress;
				$EMPITel = $EMPICoachingTel;
			}
		?>
        <!--Other Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Other Details:</strong>
            </div>

            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                     <label>Work Experience in Year(s):</label>
                     <span><?php echo $EMPIWorkExperience;?></span>
                    </li>

                    <li>
                        <label>Referred By:</label>
                        <span><?php echo $EMPIReferredBy;?></span>
                    </li>
                    <li>
                        <label>Name:</label>
                        <span><?php echo $EMPIName;?></span>
                    </li>
                    <li>
                        <label>Email:</label>
                        <span><?php echo $EMPIEmail;?></span>
                    </li>
                    <li>
                        <label>Other Source:</label>
                        <span><?php echo $EMPIOtherSource;?></span>
                    </li>
				
				</ul>

                <ul class="reviewChildRightCol">
                    <li style="visibility:hidden;">
                        <label>1</label>
                       <span>1</span>
                    </li>

                    <li style="visibility:hidden;">
                        <label>2</label>
                        <span>2</span>
                    </li>
                    <li>
                        <label>Tel.:</label>
                        <span><?php echo $EMPITel;?></span>
                    </li>
                    <li>
                        <label>Address:</label>
                        <span><?php echo $EMPIAddress;?></span>
                    </li>
                </ul>
			
			</div>
	     <div class="spacer20 clearFix"></div>
    
        </div>
        <!--Other Info Ends here-->

		<!-- Payment details Start here -->
		<?php if(is_array($paymentDetails)){ ?>
		<?php if($paymentDetails['mode']=='Offline'){ ?>
			<?php $mode = 'Demand Draft'; $amount = $paymentDetails['amount']; ?>
			<?php $draftNo = $paymentDetails['draftNumber'];?>
			<?php if(strtotime($paymentDetails['draftDate'])) $draftDate = date("Y-m-d", strtotime($paymentDetails['draftDate'])); else $draftDate = '';?>
		<?php }else if($paymentDetails['mode']=='Online'){ ?>
			<?php $mode = 'Credit Card/Net Banking'; $amount = $paymentDetails['amount'];?>
			<?php $draftNo =  $paymentDetails['orderId'];?>
			<?php if(strtotime($paymentDetails['date'])) $draftDate =  date("Y-m-d", strtotime($paymentDetails['date'])); else $draftDate = '';?>
		<?php }
		}else { ?>
			<?php $mode = ''; $amount =  '';?>
			<?php $draftNo =  '';?>
			<?php $draftDate =  '';?>
		<?php } ?>

    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Payment Information:</strong>
            </div>

            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Mode:</label>
                        <span><?php echo $mode;?></span>
                    </li>

                    <li>
                        <label>Receipt / Transaction / Draft Number:</label>
                        <span><?php echo $draftNo;?></span>
                    </li>
				</ul>

                <ul class="reviewChildRightCol">
                    <li>
                        <label>Amount:</label>
                        <span><?php echo $amount;?></span>
                    </li>
                    <li>
                        <label>Date (YYYY-MM-DD):</label>
                        <span><?=$draftDate?></span>
                    </li>
                </ul>
			
			</div>
	     <div class="spacer20 clearFix"></div>
    
        </div>
		<!-- Payment details End here -->





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


	<div class="rolesResponsiblity">

	<div id="responsibilityList">
	<li>
                  		<label style="font-weight:bold; width:700px">DECLARATION:</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                            I declare that the information furnished by me is my true personal response.
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


