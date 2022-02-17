<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Ref. No. 2015/Post Graduate Diploma in Management/S<?php echo $instituteSpecId;?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:80%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/itm/logo2.gif" alt="ITM Business School" style="float:left" />
				<div style="margin-left:289px;">
				<h2 style="font-size:20px;margin-left:-66px;">ITM Business School</h2>
				<div style="text-align:left;margin-left:20px">
					1001, Platinum Techno Park<br>
					Plot No. 17-18, Sector 30A, Vashi<br>
					Navi Mumbai 400 703 <br>
					1800-209-9727<br>
					E-Mail: pgdm.admissions@itm.edu<br>
					
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
				APPLICATION FORM FOR ADMISSION IN <?=$instituteInfo['courseTitle']?> <?=$instituteInfo['sessionYear']?>-<?=($instituteInfo['sessionYear']+2)?> BATCH
			</div>
        
        <div class="spacer15 clearFix"></div>
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
                <li>
				<div class="colums-width">
				<label style="float:left;">Facebook ID: </label>
				<div class="form-details" style="margin-left:180px; width:600px;"><?php echo $facebookIdITM; ?></div>
			</div>
			</li>
			<li>
			<div class="colums-width">
				<label style="float:left;">Twitter ID: </label>
				<div class="form-details" style="margin-left:180px; width:600px;"><?php echo $twitterIdITM; ?></div>
			</div>
		</li>
	
			</ul>
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
			
                    <?php //endif;?>
                    <li>
                        <label>Course Applied For:</label>
                        <span>Post Graduate Diploma in Information Technology Management</span>
                    </li>
                    <?php //if($profile_data['applicationCategory']):?>
                    <li>
                        <label>Application Category:</label>
                        <span><?php echo $applicationCategory;?></span>
                    </li>
                    <?php //endif;?>
					<li>
				<label>Parent's/Guardian's Email: </label>
				<span><?php echo $parentEmailITM; ?></span>
	</li>
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
            <div class="picBox" style="border:none"></div>
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
				
				<li>
			<div class="colums-width">
				<label style="margin-top:10px; width:209px !important"><strong>Graduate Institute City/Town: </strong></label>
				<div class="form-details" style="padding-top:11px;"><?php echo $graduationCityITM; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label style="margin-top:10px; width:254px !important"><strong>Higher Secondary School  City/Town: </strong></label>
				<div class="form-details" style="padding-top:11px;"><?php echo $class12CityITM; ?></div>
			</div>
		</li>

		<li>
			<div class="colums-width">
				<label style="width:209px !important"><strong>School  City/Town: </strong></label>
				<div class="form-details"><?php echo $class10CityITM; ?></div>
			</div>
		</li>
			<div class="clearFix"></div>
             </div>
	     <div class="spacer20 clearFix"></div>
     
             <div class="spacer25 clearFix"></div>

			<div class="reviewTitleBox"><strong>Choice of Specialization and Campus:</strong></div>
						  
<ul style="margin-bottom:5px; width:100%; float:left;">
			
			 <div class="reviewLeftCol widthAuto">
<ul>
			<li>
			<div class="colums-width">
				<strong><label  style="float:left; width:165px;">Choice of specialization:</strong> </label>
				<?php
                                         //$this->config->load('itm_config.php');
					 //$optGroupName=$this->config->item('itmMbaSpecializationMapping');
					 //error_log("abcd :: optGroup".print_r($optGroupName,true));
					$itmMbaSpecializationMapping = array("ITM-Business School" => array("Finanace","Marketing","Human Resource Management",
									                                "Supply Chain and Operations Management",
													"Information Technology","Marketing & Digital Media",
													"Pharma Management","AgriBusiness Management"),
									"ITM- Global Leadership Centre" => array("Retail Management & Marketing",
													        "Human Resources Management","International Business"),
									"ITM- Institute of Financial" => array("Financial Markets"));
					 foreach($itmMbaSpecializationMapping  as $index=>$val)
					 {
						foreach($val as $key=>$value)
						{
							/*error_log("abcd :: key ====".$index);
							error_log("abcd :: val===".$value)*/;
							if($value == $specializations_ITM)
							{
								$preferred_specialization = $index ." - ".$value;
							}
						}
					 }
				?>
				
				<div class="form-details"><?php echo $preferred_specialization; ?></div>
				
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<strong><label style="float:left; width:125px;">Choice of campus: </strong></label>
				<div class="form-details"><?php echo $campus1_ITM; ?></div>
			</div>
			</div>
		</li>
	</ul>		 
			 
			 
			 
			 
			  <div class="reviewTitleBox"><strong>Entrance Exam Details:</strong></div>
	   <?php 
	$testsArray = explode(",",$testNamesITM);
	
	if(in_array("CAT",$testsArray)){ ?>
	<div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
	<li>
			
			<div class="clearFix spacer5"></div>
			<strong>CAT 2013:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$catRollNumberAdditional;?></div>
			</div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$xatDateOfExaminationAdditional;?></div>
			</div>
	   
			
			
			
	   </li>
	   <li>
				
				<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Score:</label>
			    <div class="form-details">&nbsp;<?=$catScoreAdditional;?></div>
			</div>
		

			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$catPercentileAdditional;?></div>
			</div>
			<div class="clearFix"></div>
	   </li>
	   <li>
			<div class="colums-width">
				<label style="float:left; font-weight:bold;">CAT Results Awaited: </label>
				<div class="form-details"><?php echo $catResultITM; ?></div>
			</div>
		</li>
		
		</div>
	
		
		</li>
		
		
		
</ul>
	
	</div>
			<?php } 
	if(in_array("XAT",$testsArray)){ ?>    
	    <div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
	
		<li>
			<div class="clearFix spacer5"></div>
			<strong>XAT 2014:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$xatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$xatDateOfExaminationAdditional;?></div>
			</div>
	   </li></ul>
	   <li>
			<div class="colums-width" >
			    <label style="float:left; font-weight:bold;">Score:</label>
			    <div class="form-details">&nbsp;<?=$xatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$xatPercentileAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width" style="margin-bottom:4px;">
				<label style="float:left; font-weight:bold;">XAT Results Awaited: </label>
				<div class="form-details"><?php echo $xatResultITM; ?></div>
			</div>
			</div>
			
		</li>
</ul>
		<?php } 
	if(in_array("MAT",$testsArray)){ ?>    
	    <div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
	
		
		<li>
			<div class="clearFix spacer5"></div>
			<strong>MAT:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$matRollNumberAdditional;?></div>
			</div>

			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$matDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Score:</label>
			    <div class="form-details">&nbsp;<?=$matScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$matPercentileAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width" style="margin-bottom:4px;">
				<label style="float:left; font-weight:bold;">MAT Results Awaited: </label>
				<div class="form-details"><?php echo $matResultITM; ?></div>
			</div>
</div>	
	</li>
 </ul>
 <?php } 
	
	if(in_array("GMAT",$testsArray)){ ?>   
	   <div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
	
	   
	   <li>
			<div class="clearFix spacer5"></div>
			<strong>GMAT:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$gmatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$gmatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Score:</label>
			    <div class="form-details">&nbsp;<?=$gmatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gmatPercentileAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width" style="margin-bottom:4px;">
				<label style="float:left; font-weight:bold;">GMAT Results Awaited: </label>
				<div class="form-details"><?php echo $gmatResultITM; ?></div>
			</div>
</div>	
	</li>
</ul>
	<?php }
	if(in_array("CMAT",$testsArray)){ ?>   
<div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
		 

	 <li>
			<div class="clearFix spacer5"></div>
			<strong>CMAT:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$cmatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$cmatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Score:</label>
			    <div class="form-details">&nbsp;<?=$cmatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$cmatPercentileAdditionalITM;?></div>
			</div>
	   </li>
	    <li>
			<div class="colums-width" style="margin-bottom:4px;">
				<label style="float:left; font-weight:bold;">CMAT Results Awaited: </label>
				<div class="form-details"><?php echo $cmatResultITM; ?></div>
			</div>
</div>	

	</li>
</ul>
	<?php }  
	  if(in_array("MHCET",$testsArray)){ ?>   
<div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
		 

	 <li>
			<div class="clearFix spacer5"></div>
			<strong>MHCET:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$mhcetRollNumberAdditional;?></div>
			</div>

			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$mhcetDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>


			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$mhcetPercentileAdditionalITM;?></div>
			</div>
	   </li>
	    <li>
			<div class="colums-width">
				<label style="float:left; font-weight:bold;">MHCET Results Awaited: </label>
				<div class="form-details"><?php echo $mhcetResultITM; ?></div>
			</div>
</div>	
	</li>
</ul>
	<?php }  



if(in_array("ICET",$testsArray)){ ?>   

<div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
	
	 
	 <li>
			<div class="clearFix spacer5"></div>
			<strong>ICET:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$icetRollNumberAdditional;?></div>
			</div>

			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$icetDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			

			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$icetPercentileAdditional;?></div>
			</div>
	   </li>
	    <li>
			<div class="colums-width">
				<label style="float:left; font-weight:bold;">ICET Results Awaited: </label>
				<div class="form-details"><?php echo $icetResultITM; ?></div>
			</div>
	
	</li>
</div>
</ul>
	
	<?php } 

if(in_array("TANCET",$testsArray)){ ?>   
<div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
		 

	 <li>
			<div class="clearFix spacer5"></div>
			<strong>TANCET:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$tancetRollNumberAdditional;?></div>
			</div>

			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$tancetDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>

			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$tancetPercentileAdditional;?></div>
			</div>
	   </li>
	    <li>
			<div class="colums-width">
				<label style="float:left; font-weight:bold;">TANCET Results Awaited: </label>
				<div class="form-details"><?php echo $tancetResultITM; ?></div>
			</div>
</div>	
	</li>
</ul>
	<?php } 
if(in_array("KMAT",$testsArray)){ ?>   
<div class="reviewLeftCol" style="width:100%; font-size:14px; margin-bottom:10px;">
	<ul>
		 
	 <li>
			<div class="clearFix spacer5"></div>
			<strong>KMAT:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$kmatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width" style="margin-bottom:4px;">
			    <label style="float:left; font-weight:bold;">Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$kmatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			

			<div class="colums-width">
			    <label style="float:left; font-weight:bold;">Percentile:</label>
			    <div class="form-details">&nbsp;<?=$kmatPercentileAdditional;?></div>
			</div>
	   </li>
	    <li>
			<div class="colums-width">
				<label style="float:left; font-weight:bold;">KMAT Results Awaited: </label>
				<div class="form-details"><?php echo $kmatResultITM; ?></div>
			</div>
	</div>
	</li>
</ul>
	<?php } ?> 
	 </div>


<h3 class="form-title"><strong>Work Experience</strong></h3>
			<div class="clearFix spacer10"></div>
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%; border-collapse:collapse">
				<tr>
					
					<td><strong>Organisation</strong></td>
					<td><strong>Designation</strong></td>
					<td><strong>From Date</strong></td>
					<td><strong>To Date</strong></td>
					</tr>
				
				<?php 
					for($i=3;$i>=1;$i--):?>
					<?php if(!empty(${'weCompanyName_mul_'.$i})):?>
				<tr>
					
					<td><?php echo ${'weCompanyName_mul_'.$i};?></td>
					<td><?php echo ${'weDesignation_mul_'.$i};?></td>
					<td><?php if(!empty(${'weFrom_mul_'.$i})) {echo date('d F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></td>
					<td><?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('d F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo date('d F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));}?></td>
					
				</tr>
				<?php endif;endfor;?>
				<tr>
					
					<td><?=$weCompanyName?></td>
					<td><?=$weDesignation?></td>
					<td><?php if(!empty($weFrom)) {echo date('d F Y',strtotime(getStandardDate($weFrom)));}?></td>
					<td><?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('d F Y',strtotime(getStandardDate($weTill)));}} else {echo date('d F Y',strtotime(getStandardDate($weTill)));}?></td>
					
				</tr>
			</table>
	<div class="workInfoSection">
	<div class="reviewLeftCol widthAuto">
		<ul>
			<li>
	 
							<div class="formColumns">
							<label>Total Work Experience in months:</label>
                            <div style="width:290px; float:left"><?php echo $totalWorkExITM;?></div>
							</div>
            </li>
			<li>
							<div class="formColumns">
                            <label style="float:left">Referer Information:</label>
                            <div style="width:521px;margin-left:180px;"><?php echo $referrerITM;?></div>
							</div>
			</li>
			
		</ul>
	  </div>
		</div>				
						
			<div class="workInfoSection">
        	<div class="reviewTitleBox">
                <strong>Bank Loan:</strong>
            </div>
			<div class="reviewLeftCol widthAuto">
		<ul>
			<li>
			<div class="colums-width">
				<label style="width:300px;">Will you be interested to avail bank loan: </label>
				<div class="form-details"><?php echo $bankLoanITM; ?></div>
			</div>
		</li>
	
		<li>
			<div class="colums-width">
				<label style="width:296px;">Shall we share your contact details with these Banks and Financial Institutions: </label>
				<div class="form-details"><?php echo $shareDetailsITM; ?></div>
			</div>
		</li>
		</ul>
		</div>
			</div>
			
		  
		  
		  
		  
	<div id="responsibilityList">
	<li>
                  		<label style="font-weight:bold; width:700px">DECLARATION:</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                        I certify that all information provided on this application is complete & accurate.I agree to familarize myself with all the rules and regulations of the programme set forth by the institute & abide by them. I would uphold the standards and respect the principles of the organization for higher learning.				
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



