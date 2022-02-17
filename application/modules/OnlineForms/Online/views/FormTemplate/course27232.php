<link href="/public/css/onlineforms/sinhgad/SBS_styles.css" rel="stylesheet" type="text/css"/>
<style>
 strong.detail-head{float: left; display: block; width: 40px; text-align: center;}
 .detail-info {margin-left:40px;}
 .detail-info label{width: 100% !important; text-align: left !important; margin-bottom: 4px;}
</style>
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
	 <div class="appdetail-headsFormBg">
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
        	                 	
            	<p>Form No.: 
            	<span>&nbsp;<?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></p></span>
            
            <div class="clearFix"></div>
            
            <div class="courseNameDetails">
	            <img src="/public/images/onlineforms/institutes/CIMP/logo1.gif" alt="" />
	        <p>Chandragupt Institute of Management Patna</p>
                <p style="font-size:16px; font-weight:normal; margin-bottom:5px">(Approved by AICTE, Ministry of HRD, Govt. of India)</p>
		<p style="font-size:18px;">Tel: 0612-3223405, 2200489, 2230518, 2232418, 2233488</p>
		<p style="font-size:18px;">Email:admission@cimp.ac.in, Website:www.cimp.ac.in</p>
		<p style="font-size:18px;"><strong>Application for Admission to Post Graduate Diploma in Management (2015-17)</strong></p>

	    </div>
            
            <div class="clearFix"></div>
            <!--<div class="form-inst">
            	<strong>The application form has to be filled in clearly and legibly in your own handwriting.</strong><br />
				(Incomplete forms may be rejected.)
            </div>
            <div class="spacer5 clearFix"></div>-->
        </div>
         <div class="clearFix"></div>
           <div class="spacer25 clearFix"></div>

        <div class="clearFix"></div>
          <div class="spacer25 clearFix"></div>
            <div class="reviewTitleBox">
                <strong>Personal Data:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>-->
            </div>
            <div class="reviewLeftCol">
                <div class="formGreyBox">
                    <ul>
                        <li class="clear-width" style="margin-bottom:5px;">
                           <?php //if($profile_data['firstName']):?>
			   <div class="personalInfoCol">
                                <label>Salutation:</label>
                                <span><?php echo $CIMP_salutation;?></span>
                            </div>
			</li>
			<li>
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
                            <div class="personalInfoCol" >
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
		    <li>		
                        <label>Nationality:</label>
                        <span><?php echo $nationality;?></span>
                    </li>
		    <li>
                        <label>Category:</label>
                        <span><?php echo $CIMP_category;?></span>
                    </li>
		    <li>
                        <label>Disability:</br>(Attach Certificate)</label>
                        <span><?php echo str_replace(',',', ',$CIMP_person_disability);?></span>
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
                   
                    <?php //endif;
                    // if($profile_data['religion']):?>
                    <li>
                        <label>Caste:</label>
                        <span><?php echo $religion;?></span>
                    </li>
		    
		    
                    <?php //endif;?>
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
                <strong>Address(In block letter)</strong>
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
					
					<li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
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
                    
                    <?php //endif;?>
                </ul>
            </div>
            
            <div class="spacer5 clearFix"></div>
            <h3>Mailing Address(Any change in address must be notified to the Institute immediately):</h3>
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
					 <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
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
                        <label>Qualification:</label>
                        <span><?php echo $CIMP_fatherQual;?></span>
                    </li>
		    
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
                        <label>Qualification:</label>
                        <span><?php echo $CIMP_motherQual;?></span>
                    </li>
		    
		    
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
		<ul class="reviewChildLeftCol">
		  <li>
                        <label>Family Yearly Income:</label>
                        <span><?php echo $CIMP_yearlyincome;?></span>
                    </li>
		</ul>
            </div>
        </div>
        <!--Family Info Ends here-->
        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Education Details:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
            	<div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
			<p class="educationSmallCol" style="width:70px;">class</p>
			<p class="educationCol" style="width:150px;">Name of Institute</p>
			<p class="educationYearCol" style="width:90px;">Year of Passing</p>
			<p class="educationYearCol" style="width:90px;">Medium of Instruction</p>
                        <p class="educationCol" style="width:150px;">Board/ University</p>
			<p class="educationCol" style="width:150px;">Major Subjects<br/>/Stream</p>
                        <p class="educationSmallCol" style="width:85px;">% of Marks/<br/> Grade</p>
			<p class="educationSmallCol" style="width:45px;">Class/ Division</p>
			
                    </div>
                   
                <ul>
                      <li>
                        <div class="formAutoColumns">
                            <label class="labelBg" style="width:70px;"><strong style="text-align: center; display: block;">X</strong></label>
			    <span class="educationCol word-wrap" style="width:150px;"><?php echo $class10School;?></span>
			    <span class="educationYearCol" style="width:90px;"><?php echo $class10Year;?></span>
			    <span class="educationYearCol" style="width:90px;"><?php echo $CIMP_medium10;?></span>
			    <span class="educationCol word-wrap" style="width:145px;"><?php echo $class10Board;?></span>
                            <span class="educationCol educationColFirst word-wrap" style="width:145px;"><?php echo $CIMP_stream10;?></span>
                            <span class="educationSmallCol" style="width:85px;"><?php echo $class10Percentage;?></span>
			    <span class="educationSmallCol" style="width:45px;"><?php echo $CIMP_division10;?></span>
			</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg" style="width:70px;"><strong style="text-align: center; display: block;">XII</strong></label>
			    <span class="educationCol word-wrap" style="width:150px;"><?php echo $class12School;?></span>
			    <span class="educationYearCol" style="width:90px;"><?php echo $class12Year;?></span>
			    <span class="educationYearCol" style="width:90px;"><?php echo $CIMP_medium12;?></span>
			    <span class="educationCol word-wrap" style="width:145px;"><?php echo $class12Board;?></span>
                            <span class="educationCol educationColFirst word-wrap" style="width:145px;"><?php echo $CIMP_stream12;?></span>
                            <span class="educationSmallCol" style="width:85px;"><?php echo $class12Percentage;?></span>
			    <span class="educationSmallCol" style="width:45px;"><?php echo $CIMP_division12;?></span>
			</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg paddTop4" style="width:70px;"><strong style="text-align: center; display: block;">Graduation</strong></label>
			    <span class="educationCol word-wrap" style="width:150px;"><?php echo $graduationSchool;?></span>
			    <span class="educationYearCol" style="width:90px;"><?php echo $graduationYear;?></span>
			    <span class="educationYearCol" style="width:90px;"><?php echo $CIMP_mediumGrad;?></span>
			    <span class="educationCol word-wrap" style="width:145px;"><?php echo $graduationBoard;?></span>
                            <span class="educationCol educationColFirst word-wrap" style="width:145px;"><?php echo $CIMP_streamGrad;?></span>
                            <span class="educationSmallCol" style="width:85px;"><?php echo $graduationPercentage;?></span>
			    <span class="educationSmallCol" style="width:45px;"><?php echo $CIMP_divisionGrad;?></span>
			</div>
                    </li>
                    <?php //if(!empty($exam_array)):?>
                    <?php //$count_exam = count($exam_array); 
			for($j=1;$j<=4;$j++):?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg paddTop4" style="width:70px;"><strong style="text-align: center; display: block;"><?php echo "Additional$j"?></strong></label>
			    <span class="educationCol word-wrap" style="width:150px;"><?php echo ${'graduationSchool_mul_'.$j};?></span>
			    <span class="educationYearCol" style="width:90px;"><?php echo ${'graduationYear_mul_'.$j};?></span>
			    <span class="educationYearCol" style="width:90px;"></span>
			    <span class="educationCol word-wrap" style="width:145px;"><?php echo ${'graduationBoard_mul_'.$j};?></span>
                            <span class="educationCol educationColFirst word-wrap" style="width:150px;"><?php echo ${'graduationExaminationName_mul_'.$j};?></span>
                            <span class="educationSmallCol" style="width:85px;"><?php echo ${'graduationPercentage_mul_'.$j};?></span>
			    <span class="educationSmallCol" style="width:45px;"></span>
			</div>
                    </li>
                    <?php endif;endfor; //endif;?>
                </ul>
                </div>
	     <div class="spacer20 clearFix"></div>
     
             <div class="spacer25 clearFix"></div>
	  <div class="familyInfoSection">
			<div class="reviewTitleBox">
				<strong>Details of CAT 2014 (Photocopy of the Admit Card/ Test score must be attached to the form)</strong>
			</div>
			<?php
		$testsArray = explode(",",$CIMP_testNames);
			?>

                        <div class="reviewLeftCol"   style="width:900px !important">
				
				
				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:20px">
					<li>
						<label style="width:150px !important">CAT Registration No:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catRollNumberAdditional;?><?php endif; ?></span>
					</li>
					
					
					<li>
						<label style="width:150px !important">CAT Percentile:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catPercentileAdditional;?><?php endif; ?></span>
					</li>
				
				</ul>	
			
			</div>
			
		      </div>

		</div>
        
        <!--Work Exp Info Starts here-->
    	<div class="workInfoSection" style="padding-top:0;">
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
						<label class="timePeriodLabel3">Date:</label>
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
     	<h3>Responsibility:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim($weRoles));?></li>
            </ul>
        </div>
    </div>
    <?php //if(!empty($wecompany_array)):?>
    <?php //$count_company = count($wecompany_array);
	for($i=1;$i<=3;$i++):?>
    <?php if(!empty(${'weCompanyName_mul_'.$i})):?>
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
			 <label class="timePeriodLabel3">Date:</label>
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
     	<h3>Responsibility:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></li>
            </ul>
        </div>
    </div>
    <?php endif;endfor;//endif;?>
   </div> 
    </div>
	
	<div class="familyInfoSection">
		 <div class="reviewTitleBox">
			<strong>Choice of Interview Centre:</strong>
		 </div>
		 <div class="reviewLeftCol"   style="width:900px !important">
		      <ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:20px">
			   <li>
			       <label style="width:150px !important">1st Preference:</label>
			       <span><?php echo $gdpiLocation;?></span>
			    </li>
			   
			   <li>
			       <label style="width:150px !important">2nd Preference:</label>
			       <span><?php echo $pref2CIMP;?></span>
			    </li>
			   
			   <li>
			       <label style="width:150px !important">3rd Preference:</label>
			       <span><?php echo $pref3CIMP;?></span>
			    </li>
			   
			   <li>
			       <label style="width:150px !important">4th Preference:</label>
			       <span><?php echo $pref4CIMP;?></span>
			    </li>
		     </ul>
	       </div>
        </div>
	
	<div class="familyInfoSection">
		    <div class="reviewTitleBox">
			<strong>Other Details:</strong>
		 </div>
		   <div class="reviewLeftCol"   style="width:900px !important">
		    <ul class="reviewChildLeftCol"  style="width:100% !important;margin-bottom:20px">
	   
			  <li>
			   <strong class="detail-head">1.</strong>
			   <div class="detail-info">
			       <label>Describe how a management career is in line with your aims in life?</label><br/>
			       <p><?php echo $CIMP_career;?></p>
			   </div>
			  </li>
			  
			  <li>
			   <strong class="detail-head">2.</strong>
			   <div class="detail-info">
			     <label>Why would you choose CIMP for your PGDM?</label>
			     <p><?php echo $CIMP_choice;?></p>
			   </div>
			   
			  </li>
			  
			  <li>
			    <strong class="detail-head">3.</strong>
			   <div class="detail-info">
				       <label>If you become a manager, what will be your contribution to society?</label>
				       <p><?php echo $CIMP_societycontribution;?></p>
			   </div>
			  </li>
			  
			  
			  
		    </ul>
	 
		   </div>	   
	 </div>
	
	<div class="familyInfoSection">
		    <div class="reviewTitleBox">
			<strong>How did you get to know about Chandragupt Institute of Management, Patna?
(Please specify the source)</strong>
		 </div>
		   <div class="reviewLeftCol"   style="width:925px !important">
		    <ul class="reviewChildLeftCol"  style="width:100% !important;margin-bottom:20px">
	   
			  <li>
				       <label>Alumni:</label>
				       <p><?php echo $CIMP_knowAlumni;?></p>
			  </li>
			  
			  <li>
				       <label>Friend/Relative/Parent:</label>
				       <p><?php echo $CIMP_knowFriend;?></p>
			   
			  </li>
			  
			  <li>
				       <label>Website:</label>
				       <p><?php echo $CIMP_knowWebsite;?></p>
			   
			  </li>
			  <li>
				       <label>Coaching Institute:</label>
				       <p><?php echo $CIMP_knowInstitute;?></p>
			   
			  </li>
			  <li>
				       <label>Newspaper:</label>
				       <p><?php echo $CIMP_knowNewspaper;?></p>
			   
			  </li>
			  <li>
				       <label>Magazine:</label>
				       <p><?php echo $CIMP_knowMagazine;?></p>
			   
			  </li>
			  <li>
				       <label>Other(s), Pls. specify:</label>
				       <p><?php echo $CIMP_knowOthers;?></p>
			   
			  </li>
			  
			  
			  
		    </ul>
	 
		   </div>	   
	 </div>
	

	<div id="responsibilityList">
	<li>
                  <label style="font-weight:bold; width:800px">DECLARATION:</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
		          <div class="spacer10 clearFix"></div>
			
        <ul>
        <li>I hereby declare that all particulars given by me in this application form are true to the best of my
knowledge and belief. I agree to abide by the decision of the institute authorities regarding my selection for the
program. Any misrepresentation of facts in this application form could result in cancellation of admission at a later date.</li>
        </ul>   
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



