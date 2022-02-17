<?php
$headerComponents = array(
        'js'=>array('homePage'),
	    'css'=>array('shiksha_common','online-styles'),
        'jsFooter'=>array('ana_common','user','common'),
        'title'	=>	'Application Form - Dashboard',
        'metaDescription' => '',
        'metaKeywords'	=>'',
        'product' => 'online',
        'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
        );   
?>
<?php $this->load->view('common/header', $headerComponents); ?>
<div id="appsFormWrapper">
	<!--Starts: breadcrumb-->
    <?php echo $breadCrumbHTML;?>
    <!--Ends: breadcrumb-->
    <div id="contentWrapper">
    <!--Starts: Left Column-->
    <div id="appsLeftCol">
    	<ul>
        	<li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/index';?>" title="Home">Home</a></li>
            <li class="active wCurve">My Profile</li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyDocuments/index';?>" title="My Documents">My Documents</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyForms/index';?>" title="My forms">My Forms</a></li>
        </ul>
    </div>
    <!--Ends: Left Column-->
    
    <div id="appsRightCol">
    	<?php if(!empty($validateuser[0]['displayname'])):?><h2 class="welcome">Welcome <span><?php echo $display_name = isset($validateuser[0]['displayname'])? $validateuser[0]['displayname']:"";?></span></h2><?php endif;?>
        <h3 class="profileMsg">Your Profile is <?php
        $count_diff = count($diff_array);
        if($count_diff > 0 ) {
            echo $profile_data['percentComplete'];
        }else if($percentComplete == 0 || empty($onlineFormId)){
            echo '0';
        }else{ 
            echo '100';
        } ?>% complete</h3>
        
        <div class="progressCont">
        	<div class="progressbar"  style="width:<?php if($count_diff > 0 ){echo $profile_data['percentComplete'];}else if($percentComplete == 0 || empty($onlineFormId)){echo '0';}else{echo '100';}?>%"></div>
        </div>

        <?php if($count_diff>0):?>
        <div class="profileTips">
        	<h3>Please complete remaining information about your profile</h3>
            <ul>
            <?php //foreach ($page_unfield_field_array as $key=>$arr):?>
                <!--li>Provide your <?php echo $arr;?></li-->
                <?php //endforeach;?>
            </ul>
        </div>
        <?php endif;?>
    </div>
    <div class="spacer25 clearFix"></div>
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
            <div class="reviewTitleBox">
                <strong>Personal Details:</strong>
                <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1/editProfile" title="Edit">Edit</a>
            </div>
            <div class="reviewLeftCol">
                <div class="formGreyBox">
                    <ul>
                        <li>
                           <?php //if($profile_data['firstName']):?>
                            <div class="personalInfoCol">
                                <label>First Name:</label>
                                <span><?php echo $profile_data['firstName'];?></span>
                            </div>
                            <?php //endif;?>
                            <?php //if($profile_data['middleName']):?>
                            <div class="personalInfoCol">
                                <label>Middle Name:</label>
                                <span><?php if(empty($profile_data['middleName'])) {echo "&nbsp;&nbsp;&nbsp;&nbsp;-";} else {echo $profile_data['middleName'];}?></span>
                            </div>
                            <?php //endif;?>
                            <?php //if($profile_data['lastName']):?>
                            <div class="personalInfoCol">
                                <label>Last Name:</label>
                                <span><?php echo $profile_data['lastName'];?></span>
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
                        <span><?php echo $profile_data['gender'];?></span>
                    </li>
                    <?php //endif;?>
                    <?php //if($profile_data['email']):?>
                    <li>
                        <label>Email Address:</label>
                        <span><?php echo $profile_data['email'];?></span>
                    </li>
                    <?php //endif;?>
                    <li>
                        <label>Course Applied For:</label>
                        <span>Master of Business Administration</span>
                    </li>
                    <?php //if($profile_data['applicationCategory']):?>
                    <li>
                        <label>Application Category:</label>
                        <span><?php echo $profile_data['applicationCategory'];?></span>
                    </li>
					<li>
                        <label>Age:</label>
                        <span><?php echo $profile_data['age'];?></span>
                    </li>
					
					<li>
                        <label>Blood Group:</label>
                        <span><?php echo $profile_data['bloodGroup'];?></span>
                    </li>
					
                    <?php //endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['dateOfBirth']):?>
                    <li>
                        <label> Date of birth:</label>
                        <span><?php echo str_replace("/","-",$profile_data['dateOfBirth']);?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['altEmail']):?>
                    <li>
                        <label>Alternate Email:</label>
                        <span><?php echo $profile_data['altEmail'];?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['maritalStatus']):?> 
                    <li>
                        <label>Marital Status:</label>
                        <span><?php echo $profile_data['maritalStatus'];?></span>
                    </li>
                    <?php //endif;
                    // if($profile_data['nationality']):?>
                    <li>
                        <label>Nationality:</label>
                        <span><?php echo $profile_data['nationality'];?></span>
                    </li>
                    <?php //endif;
                    // if($profile_data['religion']):?>
                    <li>
                        <label>Religion:</label>
                        <span><?php echo $profile_data['religion'];?></span>
                    </li>
					
				
					<li>
                        <label>Mother Tongue:</label>
                        <span><?php echo $profile_data['motherTongue'];?></span>
                    </li>
                    <?php //endif;?>
                </ul>
            </div>
            <div class="profilePic">
                <img width="195" height="192" src="<?php echo $profile_data['profileImage'];?>" alt="Profile Pic" />
            </div>
        </div>
        <!--Personal Info Ends here-->
        
        <!--Contact Info Starts here-->
        <div class="contactInfoSection">
            <div class="reviewTitleBox">
                <strong>Contact Information:</strong>
                <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/2/editProfile">Edit</a>
            </div>
        	<h3>Permanent Address:</h3>
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                <?php //if($profile_data['houseNumber']):?>
                    <li>
                        <label>House Number:</label>
                        <span><?php echo $profile_data['houseNumber'];?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['area']):?>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo $profile_data['area'];?></span>
                    </li>
                    <?php //endif;
                    // if($profile_data['country']):?>
                    <li>
                        <label>Country:</label>
                        <span><?php echo $profile_data['country'];?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['city']):?>
                    <li>
                        <label>City:</label>
                        <span><?php echo $profile_data['city'];?></span>
                    </li>
                    <?php //endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['streetName']):?>
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo $profile_data['streetName'];?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['state']):?>
                    <li>
                        <label>State:</label>
                        <span><?php echo $profile_data['state'];?></span>
                    </li>
                    <?php //endif;
                     //if($profile_data['pincode']):?>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo $profile_data['pincode'];?></span>
                    </li>
                    <?php //endif;
                    // if($profile_data['landlineNumber']):?>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo $profile_data['landlineISDCode'].'-'.$profile_data['landlineSTDCode'].'-'.$profile_data['landlineNumber'];?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['mobileNumber']):?>
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $profile_data['mobileISDCode'].'-'.$profile_data['mobileNumber'];?></span>
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
                        <span><?php echo $profile_data['ChouseNumber'];?></span>
                    </li>
                    <?php //endif;
                   // if($profile_data['Carea']):?>
                    <li>
                        <label>Area/Locality:</label>
                        <span><?php echo $profile_data['Carea'];?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['Ccountry']):
                    ?>
                    <li>
                        <label>Country:</label>
                        <span><?php echo $profile_data['Ccountry'];?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['Ccity']):
                    ?>
                    <li>
                        <label>City:</label>
                        <span><?php echo $profile_data['Ccity'];?></span>
                    </li>
                    <?php //endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['CstreetName']):?>
                    <li>
                        <label>Street Name:</label>
                        <span><?php echo $profile_data['CstreetName'];?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['Cstate']):
                    ?>
                    <li>
                        <label>State:</label>
                        <span><?php echo $profile_data['Cstate'];?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['Cpincode']):
                    ?>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo $profile_data['Cpincode'];?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['landlineNumber']):
                    ?>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo $profile_data['landlineISDCode'].'-'.$profile_data['landlineSTDCode'].'-'.$profile_data['landlineNumber'];?></span>
                    </li>
                    <?php //endif;
                    //if($profile_data['mobileNumber']):
                    ?>
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $profile_data['mobileISDCode'].'-'.$profile_data['mobileNumber'];?></span>
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
                        <span><?php echo $profile_data['fatherName'];?></span>
                    </li>
                    <?php 
                    //if($profile_data['fatherOccupation']):
                    ?>
                    <li>
                        <label>Occupation:</label>
                        <span><?php echo $profile_data['fatherOccupation'];?></span>
                    </li>
                    <?php //endif;
                   // if($profile_data['fatherDesignation']):
                    ?>
                    <li>
                        <label>Designation:</label>
                        <span><?php echo $profile_data['fatherDesignation'];?></span>
                    </li>
                    <?php //endif; endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                  <?php //if($profile_data['MotherName']):?>
                    <li>
                        <label>Mother's Name:</label>
                        <span><?php echo $profile_data['MotherName'];?></span>
                    </li>
                    <?php //if($profile_data['MotherOccupation']):?>
                    <li>
                        <label>Occupation:</label>
                        <span><?php echo $profile_data['MotherOccupation'];?></span>
                    </li>
                    <?php //endif;
                   // if($profile_data['MotherDesignation']):
                    ?>
                    <li>
                        <label>Designation:</label>
                        <span><?php echo $profile_data['MotherDesignation'];?></span>
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
                <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>
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
                            <span class="educationCol educationColFirst word-wrap"><?php echo $profile_data['class10ExaminationName'];?></span>
                            <span class="educationCol word-wrap"><?php echo $profile_data['class10School'];?></span>
                            <span class="educationCol word-wrap"><?php echo $profile_data['class10Board'];?></span>
                            <span class="educationYearCol"><?php echo $profile_data['class10Year'];?></span>
                            <span class="educationSmallCol"><?php echo $profile_data['class10Percentage'];?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 12<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst word-wrap"><?php echo $profile_data['class12ExaminationName'];?></span>
                            <span class="educationCol word-wrap"><?php echo $profile_data['class12School'];?></span>
                            <span class="educationCol word-wrap"><?php echo $profile_data['class12Board'];?></span>
                            <span class="educationYearCol"><?php echo $profile_data['class12Year'];?></span>
                            <span class="educationSmallCol"><?php echo $profile_data['class12Percentage'];?></span>
						</div>
                    </li>
<?php
	if($this->courselevelmanager->getCurrentLevel() == "PG"){
?>
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong>Graduation</strong></label>
                            <span class="educationCol educationColFirst word-wrap"><?php echo $profile_data['graduationExaminationName'];?></span>
                            <span class="educationCol word-wrap"><?php echo $profile_data['graduationSchool'];?></span>
                            <span class="educationCol word-wrap"><?php echo $profile_data['graduationBoard'];?></span>
                            <span class="educationYearCol"><?php echo $profile_data['graduationYear'];?></span>
                            <span class="educationSmallCol"><?php echo $profile_data['graduationPercentage'];?></span>
						</div>
                    </li>
<?php
	}
?>
                    <?php if(!empty($exam_array)):?>
                    <?php $count_exam = count($exam_array); for($j=1;$j<=$count_exam;$j++):?>
                    <?php if(!empty($profile_data['graduationExaminationName_mul_'.$j])):?>
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong><?php echo "Additional$j"?></strong></label>
                            <span class="educationCol educationColFirst word-wrap"><?php echo $profile_data['graduationExaminationName_mul_'.$j];?></span>
                            <span class="educationCol word-wrap"><?php echo $profile_data['graduationSchool_mul_'.$j];?></span>
                            <span class="educationCol word-wrap"><?php echo $profile_data['graduationBoard_mul_'.$j];?></span>
                            <span class="educationYearCol"><?php echo $profile_data['graduationYear_mul_'.$j];?></span>
                            <span class="educationSmallCol"><?php echo $profile_data['graduationPercentage_mul_'.$j];?></span>
						</div>
                    </li>
                    <?php endif;endfor; endif;?>
                </ul>
                <div class="clearFix"></div>
             </div>
             <div class="spacer25 clearFix"></div>

	     <?php
		 
		 if($this->courselevelmanager->getCurrentLevel() == "PG"){
			$this->load->view('Online/testExamDisplay','array("profile_data"=>$profile_data)');
		 }else{
			$this->load->view('Online/testExamDisplayUG','array("profile_data"=>$profile_data)');
		 }
		 
		 
		 ?>	    

        </div>
        <!--Education Info Ends here-->
<?php
	if($this->courselevelmanager->getCurrentLevel() == "PG"){
?>
        <!--Work Exp Info Starts here-->
    	<div class="workInfoSection">
        	<div class="reviewTitleBox">
                <strong>Work Experience:</strong>
            </div>
            <div class="reviewLeftCol widthAuto">
                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Company Name:</label>
                            <div style="width:290px; float:left"><?php echo $profile_data['weCompanyName']?></div>
						</div>
                        	
                    	<div class="formColumns">
                    	<label class="timePeriodLabel3">Designation:</label>
                        <div style="width:290px; float:left"><?php echo $profile_data['weDesignation']?></div>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <div style="width:290px; float:left"><?php echo $profile_data['weLocation']?></div>
                    </div> 
                    
                    <div class="formColumns">
						<label class="timePeriodLabel3">Time Period:</label>
						<?php if($profile_data['weTimePeriod']):?>
                         <input type="checkbox" checked="checked" disabled="disabled" /> I currently work here<br />
                         <?php else:?>
                         <input type="checkbox" disabled="disabled" /> I currently work here<br />
                         <?php endif;?>
                        <div class="workExpDetails3">
                        	<span>From: <?php if(!empty($profile_data['weFrom'])) {echo date('F Y',strtotime(getStandardDate($profile_data['weFrom'])));}?></span>
                        	<span>&nbsp;&nbsp;-</span>
                            <span class="mL10">Till: <?php if(!$profile_data['weTimePeriod']) {if(!empty($profile_data['weTill'])) {echo date('F Y',strtotime(getStandardDate($profile_data['weTill'])));}} else {echo "Date";}?></span>
                        </div>
					</div>
                 </li>
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="spacer20 clearFix"></div>
     <div class="rolesResponsiblity">
     	<h3>Roles &amp; Responsibilities:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim($profile_data['weRoles']));?></li>
            </ul>
        </div>
    </div>
    <?php if(!empty($wecompany_array)):?>
    <?php $count_company = count($wecompany_array);for($i=1;$i<=$count_company;$i++):?>
    <?php if(!empty($profile_data['weCompanyName_mul_'.$i])):?>
    <div class="reviewLeftCol widthAuto">
                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Company Name:</label>
                            <span><?php echo $profile_data['weCompanyName_mul_'.$i]?></span>
						</div>
                        	
                    	<div class="formColumns">
                    	<label class="timePeriodLabel3">Designation:</label>
                        <span><?php echo $profile_data['weDesignation_mul_'.$i]?></span>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <span><?php echo $profile_data['weLocation_mul_'.$i]?></span>
                    </div> 
                    
                    <div class="formColumns">
						<label class="timePeriodLabel3">Time Period:</label>
						<?php if($profile_data['weTimePeriod_mul_'.$i]):?>
                         <input type="checkbox" checked="checked" disabled="disabled" /> I currently work here<br />
                         <?php else:?>
                         <input type="checkbox" disabled="disabled" /> I currently work here<br />
                         <?php endif;?>
                        <div class="workExpDetails3">
                        	<span>From: <?php if(!empty($profile_data['weFrom_mul_'.$i])) {echo date('F Y',strtotime(getStandardDate($profile_data['weFrom_mul_'.$i])));}?></span>
                        	<span>&nbsp;&nbsp;-</span>
                            <span class="mL10">Till: <?php if(!$profile_data['weTimePeriod_mul_'.$i]) {if(!empty($profile_data['weTill_mul_'.$i])) {echo date('F Y',strtotime(getStandardDate($profile_data['weTill_mul_'.$i])));} } else {echo "Date";}?></span>
                        </div>
					</div>
                 </li>
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="spacer20 clearFix"></div>
     <div class="rolesResponsiblity">
     	<h3>Roles &amp; Responsibilities:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim($profile_data['weRoles_mul_'.$i]));?></li>
            </ul>
        </div>
    </div>
    <?php endif;endfor;endif;?>

   </div>
<?php
	}
?>
    </div>
</div>
<div class="spacer20 clearFix"></div>
<?php $this->load->view('common/footerNew'); ?>
