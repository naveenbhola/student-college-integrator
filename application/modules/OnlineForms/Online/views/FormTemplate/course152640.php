<link href="/public/css/onlineforms/abbs/ABBS_styles.css" rel="stylesheet" type="text/css"/>
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
            
            <div class="courseNameDetails">
	            <img src="/public/images/onlineforms/institutes/quantum/logo2.gif" alt="" />
	        <p>Quantum School Of Business<br />APPLICATION FORM FOR MBA</p>
	    </div>

            <div class="appNo"><strong>
            	Application No.:
		  <span><?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></span></strong>
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
                        <label>Course Applied For:</label>
                        <span>Master of Business Administration</span>
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
                    <li>
                        <label>Religion:</label>
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
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
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
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
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
	     
     
             <div class="spacer25 clearFix"></div>
	          <div class="familyInfoSection">
			<div class="reviewTitleBox">
				<strong>Tests:</strong>
			</div>
			<?php
		           $testsArray = explode(",",$Quantum_testNames);
			  ?>
			  <div class="reviewLeftCol"   style="width:900px !important">
                             
                            	<?php if(in_array("CAT",$testsArray)): ?>			      
				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
                                      
					<li>
                                                
						<label style="width:150px !important">CAT Roll No:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catRollNumberAdditional;?><?php endif; ?></span>
					</li>
					
				      
				</ul>
                             
                               
				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">CAT Score:</label>
						<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catScoreAdditional;?><?php endif; ?></span>
					</li>
					
					
				
				</ul>
                             
                                <?php endif ?> 

                               <?php if(in_array("MAT",$testsArray)): ?>
                                                       
				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
  
					<li>
						<label style="width:150px !important">MAT Roll No:</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matRollNumberAdditional;?><?php endif; ?></span>
					</li>
					
					
				
				</ul>
		
				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">MAT Score:</label>
						<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matScoreAdditional;?><?php endif; ?></span>
					</li>
					
					
				
				</ul> 
                              <?php endif ?>
                           		       
                              <?php if(in_array("XAT",$testsArray)): ?>
                               <ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">XAT Roll No:</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatRollNumberAdditional;?><?php endif; ?></span>
					</li>
					
					
				
				</ul>
				
				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">XAT Score:</label>
						<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatScoreAdditional;?><?php endif; ?></span>
					</li>
					
				</ul>
                              <?php endif ?>
                             
                             <?php if(in_array("CMAT",$testsArray)): ?>				
				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">CMAT Roll No:</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatRollNumberAdditional;?><?php endif; ?></span>
					</li>
					
					
				
				</ul>
				
				<ul class="reviewChildLeftCol"  style="width:400px !important;margin-bottom:30px">
					<li>
						<label style="width:150px !important">CMAT Score:</label>
						<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatScoreAdditional;?><?php endif; ?></span>
					</li>
					
				</ul>
			      <?php endif ?>	
			 </div>
		</div>
        
        <!--Work Exp Info Starts here-->
    	<div class="workInfoSection">
                <?php if(isset($weCompanyName) && $weCompanyName!=""){ ?>
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
                <?php } ?>

                        <?php if(isset($weDesignation) && $weDesignation!=""){ ?>	
                    	<div class="formColumns">
                    	  <label class="timePeriodLabel3">Designation:</label>
                          <div style="width:290px; float:left"><?php echo $weDesignation;?></div>
                        </div>
                       <?php } ?> 
                 </li>
                 
                 <li><?php if(isset($weLocation) && $weLocation!=""){ ?>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <div style="width:290px; float:left"><?php echo $weLocation;?></div>
                    </div> 
                    <?php } ?>
                    
                    <div class="formColumns"><?php if(isset($weTimePeriod) && $weTimePeriod!=""){ ?>
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
                      <?php } ?>
		   </div>
                 </li>
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="spacer10 clearFix"></div>
     <div class="rolesResponsiblity">
        <?php if(isset($weRoles) && $weRoles!=""){ ?>
     	<h3>Roles &amp; Responsibilities:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim($weRoles));?></li>
            </ul>
        </div>
       <?php } ?>
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
     <div class="spacer10 clearFix"></div>
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
    </div>


	<div class="rolesResponsiblity">
     	<div class="reviewTitleBox"><strong>Preferred GD/Interview Centre</strong></div>
        <div id="responsibilityList">
            <table width="40%" cellpadding="7" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                                    <tr>
                                        <?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=''){ ?>
                                        <td align="center"><?php if($preferredGDPILocation=='73') echo "Dehradun"; else echo "<strike>Dehradun</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='182') echo "Roorkee"; else echo "<strike>Roorkee</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='10681') echo "Haldwani"; else echo "<strike>Haldwani</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='47') echo "Bareilly"; else echo "<strike>Bareilly</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='122') echo "Kanpur"; else echo "<strike>Kanpur</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='138') echo "Lucknow"; else echo "<strike>Lucknow</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='35') echo "Allahabad"; else echo "<strike>Allahabad</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='209') echo "Varanasi"; else echo "<strike>Varanasi</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='90') echo "Gorakhpur"; else echo "<strike>Gorakhpur</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='171') echo "Patna"; else echo "<strike>Patna</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='180') echo "Ranchi"; else echo "<strike>Ranchi</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='96') echo "Guwahati"; else echo "<strike>Guwahati</strike>";?></td>
                                        <td align="center"><?php if($preferredGDPILocation=='28') echo "Agartala"; else echo "<strike>Agartala</strike>";?></td></tr>
                                        <tr><td align="center"><?php if($preferredGDPILocation=='10682') echo "Bilaspur(CG)"; else echo "<strike>Bilaspur(CG)</strike>";?></td>
                                       
                                        <td colspan="1" align="center"><?php if($preferredGDPILocation=='176') echo "Raipur"; else echo "<strike>Raipur</strike>";?></td></tr>
                                        
                                        <?php }else{ ?>
                                        <td align="center">Dehradun</td>
                                        <td align="center">Kanpur</td>
                                        <td align="center">Haldwani</td>
                                        <td align="center">Roorkee</td>
                                        <td align="center">Bareilly</td>
                                        <td align="center">Lucknow</td>
                                        <td align="center">Allahabad</td>
                                        <td align="center">Varanasi</td>
                                        <td align="center">Gorakhpur</td>
                                        <td align="center">Patna</td>
                                        <td align="center">Ranchi</td>
                                        <td align="center">Guwahati</td>
                                        <td align="center">Agartala</td>
                                        <tr><td align="center">Bilaspur(CG)</td>
                                        <td align="center">Raipur</td></tr>
                                        
                                        <?php } ?>
                                    
                                </table>
                 </div>
        </div>


     <li>
       <div class="rolesResponsiblity">
     	<div class="reviewTitleBox"><strong>Undertaking</strong></div>
         <div id="responsibilityList">
                        <div style="padding-left:33px">I hereby untertake that the entries filled by me in the form are true to best of my knowledge and belief. I have secured 50% or more marks in Qualifying Examination for MBA for Registration.<BR />
                                I am aware of the fact that in case of any wrong declaration , my registration/admission will stand cancelled & fee will be forfeited and I will have no objection to the same, besides rendering meliable to such action as the school/university may deem proper.<BR />
                                It may kindly be noted that registration has no bearing on the admissions of the student, which solely depends upon the merit and/or availablity of vacant seats after the central counseling Attested photocopies of the certificates must be enclosed for the testmony of the eligibility, along with your rank/score card if any.<BR />
                        	
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
              </div>
          </div>
       </li>


      
	    
	          
   


