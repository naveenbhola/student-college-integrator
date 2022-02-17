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
    <div id="breadcrumb">
    	<ul>
        	<li><a href="<?=$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']?>" title="Application Forms">Application Forms</a></li>
        	<li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyForms/index';?>" title="My Forms">My Forms</a></li>
            <li class="last"><?php echo $form_details[0]['institute_name'];?></li>
        </ul>
    </div>
    <!--Ends: breadcrumb-->
    <div id="appsFormInnerWrapper">
    <div id="appsFormHeader">
        <div class="appsFormHeaderChild">
        <div class="appsLogo"><a href="#"><img src="/public/images/amity-logo.gif" alt="Amity" /></a></div>
        <div class="appsDetails">
            <h3> <?php echo $form_details[0]['institute_name'];?> Application Form-2012</h3>
            <div class="formNumb">
                <div class="formNumbRt">
                    <div class="formNumbMid">
                        Form No.:<br /><span><?php echo $form_details[0]['onlineFormId'];?></span>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!--Instructions Starts here-->
        <div id="instructions">
            <h3>INSTRUCTIONS</h3>
            <ul>
                <li>1) Fees for filling up the application form is Rs.<?php echo $form_details[0]['fees'];?>/-</li>
                <li>2) Pay Online or take print out and send with DD</li>
                <li>3) Track your application status onlIne.</li>
            </ul>
            
            <div class="helpBox">
                <span>
                <a href="javascript:void(0);" onclick="showHelpLayer();"class="helpIcon" title="Help">Help</a>
                <a href="javascript:void(0);" onclick="showFaqLayer();" class="faqsIcon" title="Faqs">Faqs</a>
                </span>
            </div>
        </div>
        <!--Instructions Ends here-->
    </div>
    <div class="clearFix"></div>
    <!--Contents Starts here-->
	<div id="contentWrapper">
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
            <div class="reviewTitleBox">
                <strong>Personal Details:</strong>
                <?php if($showEdit == 'true'):?>
                <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/1" title="Edit">Edit</a>
                <?php endif;?>
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
                                <span><?php echo $profile_data['middleName'];?></span>
                            </div>
                            <?php // endif;?>
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
                   // if($profile_data['maritalStatus']):?> 
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
                     //if($profile_data['religion']):?>
                    <li>
                        <label>Religion:</label>
                        <span><?php echo $profile_data['religion'];?></span>
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
                 <?php if($showEdit == 'true'):?>
                <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/2">Edit</a>
                <?php endif;?>
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
                     //if($profile_data['country']):?>
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
                        <span><?php echo $profile_data['landlineISDCode'].'-'.$profile_data['landlineNumber'];?></span>
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
                   // if($profile_data['Ccountry']):
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
                   // if($profile_data['Cpincode']):
                    ?>
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo $profile_data['Cpincode'];?></span>
                    </li>
                    <?php // endif;
                    //if($profile_data['landlineNumber']):
                    ?>
                    <li>
                        <label>Landline:</label>
                        <span><?php echo $profile_data['landlineISDCode'].'-'.$profile_data['landlineNumber'];?></span>
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
                    ///if($profile_data['fatherDesignation']):
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
                    <?php //endif;endif;?>
                </ul>
            </div>
        </div>
        <!--Family Info Ends here-->
        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Education Information:</strong>
                <?php if($showEdit == 'true'):?>
                <a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3" title="Edit">Edit</a>
                <?php endif;?>
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
                            <span class="educationCol educationColFirst"><?php echo $profile_data['class10ExaminationName'];?></span>
                            <span class="educationCol"><?php echo $profile_data['class10School'];?></span>
                            <span class="educationCol"><?php echo $profile_data['class10Board'];?></span>
                            <span class="educationYearCol"><?php echo $profile_data['class10Year'];?></span>
                            <span class="educationSmallCol"><?php echo $profile_data['class10Percentage'];?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 12<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst"><?php echo $profile_data['class12ExaminationName'];?></span>
                            <span class="educationCol"><?php echo $profile_data['class12School'];?></span>
                            <span class="educationCol"><?php echo $profile_data['class12Board'];?></span>
                            <span class="educationYearCol"><?php echo $profile_data['class12Year'];?></span>
                            <span class="educationSmallCol"><?php echo $profile_data['class12Percentage'];?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                        	<label class="labelBg paddTop4"><strong>Graduation</strong></label>
                            <span class="educationCol educationColFirst"><?php echo $profile_data['graduationExaminationName'];?></span>
                            <span class="educationCol"><?php echo $profile_data['graduationSchool'];?></span>
                            <span class="educationCol"><?php echo $profile_data['graduationBoard'];?></span>
                            <span class="educationYearCol"><?php echo $profile_data['graduationYear'];?></span>
                            <span class="educationSmallCol"><?php echo $profile_data['graduationPercentage'];?></span>
						</div>
                    </li>
                </ul>
                <div class="clearFix"></div>
             </div>
             <div class="spacer25 clearFix"></div>
             <h3 style="padding-left:12px">Qualifying Examination:</h3>
             <div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
                    	<p class="educationCol widthAuto">CAT Score Details</p>
                    </div>
                   
                <ul class="qualifyingDetails">
                	<li>
                        <div class="formColumns">
                            <label><strong>Date of examination:</strong></label>
                            <span><?php echo $profile_data['catDateOfExamination'];?></span>
						</div>
                        
                        <div class="formColumns">
                            <label><strong>Score:</strong></label>
                            <span><?php echo $profile_data['catScore'];?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
                            <label><strong>Roll Number:</strong></label>
                            <span><?php echo $profile_data['catRollNumber'];?></span>
						</div>
                        
                        <div class="formColumns">
                            <label><strong>Percentile:</strong></label>
                            <span><?php echo $profile_data['catPercentile'];?></span>
						</div>
                    </li>
                </ul>
                <div class="clearFix"></div>
             </div>
        </div>
        <!--Education Info Ends here-->
        
        <!--Work Exp Info Starts here-->
    	<?php //if($profile_data['weCompanyName']):?>
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
                       <input type="checkbox" <?php if($profile_data['weFrom'] && $profile_data['weTill']) {echo "disabled";} else {echo "checked";}?>/> I currently work here<br />
                        <div class="workExpDetails3">
                        	<span>From: <?php echo date('F Y',strtotime(getStandardDate($profile_data['weFrom'])));?></span>
                        	<span>&nbsp;&nbsp;-</span>
                            <span class="mL10">Till: <?php echo date('F Y',strtotime(getStandardDate($profile_data['weTill'])));?></span>
                        </div>
					</div>
                 </li>
              </ul>
          </div>
	</div>
	<?php //endif;?>
     <!--Work Exp Info Ends here-->
     
     <div class="spacer20 clearFix"></div>
     
     <?php //if($profile_data['weRoles']):?>
     <div class="rolesResponsiblity">
     	<h3>Roles &amp; Responsibilities:</h3>
        <div id="responsibilityList">
            <ul>
                <li>1) <?php echo $profile_data['weRoles'];?>.</li>
            </ul>
        </div>
    </div>
    <?php //endif;?>
     <div class="spacer15 clearFix"></div>
     <?php
     // Display the Institutes custom view form data
	    $this->load->view('Online/FormTemplate/course'.$courseId);
	?>
    </div>
    <!--Contents Ends here-->
	
	<div class="clearFix"></div>
   </div>
</div>
<?php $this->load->view('common/footerNew'); ?>
<script type="text/javascript">
window.onload= function(){
try {	
var print = '<?php echo $_REQUEST['print'];?>';
if(print) {
	window.print();
}
} catch(e) {
	///
}
}
</script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormCommon"); ?>"></script>
