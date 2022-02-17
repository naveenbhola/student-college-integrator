<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>


<style>
.reviewTitleBox{margin-top:30px;}
.alternateeven{
	background-color: #EFF6FF;
    clear: both;
    margin: 20px 0px;
    overflow: hidden;
    padding: 6px 12px;
	font-size:16px;
	font-weight:bold;
}
.Contentbold{
	font-weight:bold;
}
td{
	font-size:13px;
}
</style>
<link href="/public/css/onlineforms/skyline/form-preview.css" rel="stylesheet" type="text/css"/>        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:left">Application No. <?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:75%;margin-left:10px">
                <img src="/public/images/onlineforms/institutes/balaji/logo2.jpg" alt="Balaji Institute of Modern Management" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">Sri Balaji Society</h2>
				<div style="text-align:center;margin-left:20px">
				S. No. 55/2-7, Tathawade,<br/>
				Off Mumbai-Bangalore bypass, Pune - 411 033<br/>
				Maharashtra, India<br/>
				Tel.:  +91 9673338787<br/>
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
            Online Application: 2015-17
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
                <?php //if($profile_data['gender']):?>
		    <li>
                        <label>Email:</label>
                        <span><?php echo $email;?></span>
                    </li>
		    <li>
                        <label>Age:</label>
			<?php if(isset($Balaji_ageInYear) && $Balaji_ageInYear!=''){?>
                        <span><?php echo $Balaji_ageInYear;?>years <?php echo $Balaji_ageInMonth;?>months</span>
			<?php } ?>
                    </li>
		    <li>
                        <label>Nationality:</label>
                        <span><?php echo $nationality;?></span>
                    </li>
                    
                    <li>
                        <label>Category:</label>
                        <span><?php echo $Balaji_category;?></span>
                    </li>
                    <li>
                        <label>Married:</label>
                        <span><?php echo $Balaji_marriedStatus;?></span>
                    </li>
                    <li>
                        <label>Physically Handicapped:</label>
                        <span><?php echo $Balaji_phyHandi;?></span>
                    </li>
		    
		    
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['dateOfBirth']):?>
                    
                    <?php //endif;
                    //if($profile_data['maritalStatus']):?>
		    <li>
                        <label> Date of birth:</label>
                        <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
                    </li>
		    
		    <li>
                        <label>Gender:</label>
                        <span><?php echo $gender;?></span>
                    </li>
		    
		    <li>
                        <label>Religion:</label>
                        <span><?php echo $religion;?></span>
                    </li>
		    
		    <li>
                        <label>Mother Tongue:</label>
                        <span><?php echo $motherTongue;?></span>
                    </li>
		    
		     <li>
                        <label>Alternate Email:</label>
                        <span><?php echo $altEmail;?></span>
                    </li>
		     
		      <li>
			<?php if(isset($Balaji_phyHandiAttach) && $Balaji_phyHandiAttach!=''){ ?>
                        <label>Have Disability Certificate:</label>
                        <span><?php echo $Balaji_phyHandiAttach;?></span>
			<?php } ?>
                    </li>
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
                        <label>Additional:</label>
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
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['streetName']):?>
                    <li>
                        <label>Street:</label>
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
                        <label>phone Number:</label>
                        <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
                    </li>
                </ul>
            </div>
            
            <div class="spacer5 clearFix"></div>
            <h3>Present Address(for Correspondence):</h3>
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
                        <label>Additional:</label>
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
                    <li>
                        <label>Mobile Number:</label>
                        <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                    </li>
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['CstreetName']):?>
                    <li>
                        <label>Street:</label>
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
                        <label>Phone Number:</label>
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
                <?php //if($profile_data['fatherName']):?>
                    <li>
                        <label>Father's Name:</label>
                        <span><?php echo $fatherName;?></span>
                    </li>
		    <li>
                        <label>Father's Organisation Name:</label>
                        <span><?php echo $Balaji_fatherOrgName;?></span>
                    </li>
		    <?php if(isset($Balaji_fatherResiPhone) && $Balaji_fatherResiPhone!=''){ ?>
		    <li>
                        <label>Father's Residence Phone:</label>
                        <span><?php echo $Balaji_fatherResiPhone;?></span>
                    </li>
		    <?php } ?>
		    
		    
                    <?php //endif; endif;?>
                </ul>
                
                <ul class="reviewChildRightCol">
                  <?php //if($profile_data['MotherName']):?>
		    <li>
                        <label>Father's Occupation:</label>
                        <span><?php echo $Balaji_fatherOcc;?></span>
                    </li>
		    <?php if(isset($Balaji_fatherMobile) && $Balaji_fatherMobile!=''){ ?>
		    <li>
                        <label>Father's Mobile:</label>
                        <span><?php echo $Balaji_fatherMobile;?></span>
                    </li>
		    <?php } ?>
		    <?php if(isset($Balaji_fatherOffPhone) && $Balaji_fatherOffPhone!=''){ ?>
		    <li>
                        <label>Father's Office Phone:</label>
                        <span><?php echo $Balaji_fatherOffPhone;?></span>
                    </li>
		    <?php } ?>
                    
                    <?php //if($profile_data['MotherOccupation']):?>
                   
                </ul>
	</div>
	<div class="reviewLeftCol" style="margin-top:10px; ">
                <ul class="reviewChildLeftCol">
                <?php //if($profile_data['fatherName']):?>
                    <li>
                        <label>Mother's Name:</label>
                        <span><?php echo $MotherName;?></span>
                    </li>
                    <li>
                        <label>Family's Annual Income:</label>
                        <span><?php echo $Balaji_annualIncome;?></span>
                    </li>

                </ul>
                
                <ul class="reviewChildRightCol">
                  <?php //if($profile_data['MotherName']):?>
		    <li>
                        <label>Mother's Occupation:</label>
                        <span><?php echo $Balaji_motherOcc;?></span>
                    </li>
		    <li>
                        <label>Source of Income:</label>
                        <span><?php echo $Balaji_incomeSource;?></span>
                    </li>
                   
                </ul>
        </div>
	</div>
	<?php if($Balaji_fatheroffStreet!='' || $Balaji_fatheroffCArea!='' ||  $Balaji_fatheroffCity!='' || $Balaji_fatheroffState!='' || $Balaji_fatheroffPincode!=''){?>
	<div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Father's Office Address:</strong>
            </div>
	<div class="reviewLeftCol" style="margin-bottom:10px;">
                <ul class="reviewChildLeftCol">
                <?php //if($profile_data['ChouseNumber']):?>
		    <?php if(isset($Balaji_fatheroffStreet) && $Balaji_fatheroffStreet!=''){ ?>
                    <li>
                        <label>Street:</label>
                        <span><?php echo $Balaji_fatheroffStreet;?></span>
                    </li>
		    <?php } ?>
                    <?php //endif;
                   // if($profile_data['Carea']):?>
		   <?php if(isset($Balaji_fatheroffCity) && $Balaji_fatheroffCity!=''){ ?>
                    <li>
                        <label>City:</label>
                        <span><?php echo $Balaji_fatheroffCity;?></span>
                    </li>
		    <?php } ?>
		   
                    <?php //endif;
                    //if($profile_data['Ccountry']):
                    ?>
		    <?php if(isset($Balaji_fatheroffCountry) && $Balaji_fatheroffCountry!=''){ ?>
                    <li>
                        <label>Country:</label>
                        <span><?php echo $Balaji_fatheroffCountry;?></span>
                    </li>
		    <?php } ?>
                    <?php //endif;
                    //if($profile_data['Ccity']):
                    ?>
		</ul>
		
		<ul class="reviewChildRightCol">
		    <?php if(isset($Balaji_fatheroffCArea) && $Balaji_fatheroffCArea!=''){ ?>
                    <li>
                        <label>Additional:</label>
                        <span><?php echo $Balaji_fatheroffCArea;?></span>
                    </li>
		    <?php } ?>
		    
		    <?php if(isset($Balaji_fatheroffState) && $Balaji_fatheroffState!=''){ ?>
		    <li>
                        <label>State:</label>
                        <span><?php echo $Balaji_fatheroffState;?></span>
                    </li>
		    <?php } ?>
		    
		    <?php if(isset($Balaji_fatheroffPincode) && $Balaji_fatheroffPincode!=''){ ?>
		    <li>
                        <label>Postal Code:</label>
                        <span><?php echo $Balaji_fatheroffPincode;?></span>
                    </li>
		    <?php } ?>
                    <?php //endif;?>
                    
                </ul>
		
            </div>
	</div>
	<?php } ?>
	
<div class="familyInfoSection">
<ul>
<?php if(isset($Balaji_brotherDetail) && $Balaji_brotherDetail!=''){ ?> 	
 <li>
		<h3 class="form-title">Brothers' Details</h3>
                <div class="spacer10 clearFix"></div>
                <div style="float:left;width:100%">
                    <div class="previewFieldBox" style="width:100%;float:left;margin-bottom:20px;">
                        <span style="border-bottom: none;"><?php echo $Balaji_brotherDetail; ?></span>
                    </div>
                </div>
</li>
 <?php } ?>
 
 <?php if(isset($Balaji_sisDetail) && $Balaji_sisDetail!=''){ ?>
  <li>
		<h3 class="form-title">Sister's Details</h3>
                <div class="spacer10 clearFix"></div>
                <div style="float:left;width:100%">
                    <div class="previewFieldBox" style="width:100%;float:left;margin-bottom:20px;">
                        <span style="border-bottom: none;"><?php echo $Balaji_sisDetail; ?></span>
                    </div>
                </div>
             </li>
	<?php } ?>
	</ul>
</div>

<?php


for($i=1;$i<=13;$i++){
	$courseArray[${'Balaji_coursePref'.$i}] = $i; 
}


?>	    
<table width="100%" cellspacing="3" cellpadding="0" border="0" class="c1" id="PersonalDetails16" style="margin-bottom:30px;">
		<tbody><tr width="100%">
			<td nowrap="nowrap" colspan="9" class="alternateeven">
      <font class="bodyXtitleXtxtDuplicate">Preference in ascending order only (01 to 13) for Management Courses</font>
    </td>
		</tr>
		<?php if(isset($Balaji_coursePref1) && $Balaji_coursePref1!='') { ?>
		<tr id="TestPreference_ddlPreference4">
			<td width="4%" class="Contentbold" align="center" >1</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
		<?=$Balaji_coursePref1;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref2) && $Balaji_coursePref2!='') { ?>
		<tr id="TestPreference_ddlPreference5">
			<td width="4%" class="Contentbold" align="center">2</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
    <?=$Balaji_coursePref2;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref3) && $Balaji_coursePref3!='') { ?>
		<tr id="TestPreference_ddlPreference6">
			<td width="4%" class="Contentbold" align="center">3</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
     <?=$Balaji_coursePref3;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref4) && $Balaji_coursePref4!='') { ?>
		<tr id="TestPreference_ddlPreference7">
			<td width="4%" class="Contentbold" align="center">4</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
      <?=$Balaji_coursePref4;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref5) && $Balaji_coursePref5!='') { ?>
		<tr id="TestPreference_ddlPreference8">
			<td width="4%" class="Contentbold" align="center">5</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
      <?=$Balaji_coursePref5?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref6) && $Balaji_coursePref6!='') { ?>
		<tr id="TestPreference_ddlPreference9">
			<td width="4%" class="Contentbold" align="center">6</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
        <?=$Balaji_coursePref6;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref7) && $Balaji_coursePref7!='') { ?>
		<tr id="TestPreference_ddlPreference10">
			<td width="4%" class="Contentbold" align="center">7</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
        <?=$Balaji_coursePref7;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref8) && $Balaji_coursePref8!='') { ?>
		<tr id="TestPreference_ddlPreference11">
			<td width="4%" class="Contentbold" align="center">8</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
        <?=$Balaji_coursePref8;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref9) && $Balaji_coursePref9!='') { ?>
		<tr id="TestPreference_ddlPreference12">
			<td width="4%" class="Contentbold" align="center">9</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
       <?=$Balaji_coursePref9;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref10) && $Balaji_coursePref10!='') { ?>
		<tr id="TestPreference_ddlPreference13">
			<td width="4%" class="Contentbold" align="center">10</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
        <?=$Balaji_coursePref10;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref11) && $Balaji_coursePref11!='') { ?>
		<tr id="TestPreference_ddlPreference14">
			<td width="4%" class="Contentbold" align="center">11</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
        <?=$Balaji_coursePref11;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref12) && $Balaji_coursePref12!='') { ?>
		<tr id="TestPreference_ddlPreference15">
			<td width="4%" class="Contentbold" align="center">12</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
       <?=$Balaji_coursePref12;?>
    </td>
		</tr>
		<?php } ?>
		<?php if(isset($Balaji_coursePref13) && $Balaji_coursePref13!='') { ?>
		<tr id="TestPreference_ddlPreference16">
			<td width="4%" class="Contentbold" align="center">13</td>
			<td width="2%">
      <b>:   </b>
    </td>
			<td width="23%">
        <?=$Balaji_coursePref13;?>
    </td>
		</tr>
		<?php } ?>
	</tbody></table>

<table width="100%" cellspacing="3" cellpadding="0" border="0" class="c1" id="PersonalDetails27" style="margin-bottom:30px";>
		<tbody><tr width="100%">
			<td nowrap="nowrap" colspan="9" class="alternateeven">
      <font class="bodyXtitleXtxtDuplicate">Centres for group discussion and personal interview</font>
    </td>
		</tr>
		<tr id="TestPreference_ddlPreference1">
			<td width="23%" class="Contentbold">Centre Preference 1<span style="color:Red;display:none;" id="req_ddlPreference1"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
    <?=$gdpiLocation?>
    </td>
   
        <td width="23%" class="Contentbold"><span style="color:Red;display:none;" id="req_ddlPreference2"></span></td>
                        <td width="4%">
    </td>
                        <td nowrap="nowrap">
                </tr>

       </tr>
		<?php if(isset($Balaji_GDPI2) && $Balaji_GDPI2!='') {?>
                <tr id="TestPreference_dd2Preference1">

			<td width="23%" class="Contentbold">Centre Preference 2<span style="color:Red;display:none;" id="req_ddlPreference3"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td nowrap="nowrap">
    <?php echo $Balaji_GDPI2; ?>
    </td>

               <td width="23%" class="Contentbold"> <span style="color:Red;display:none;" id="req_ddlPreference4"></span></td>
                        <td width="4%">
    </td>
                        <td nowrap="nowrap">
    </td>

		</tr>
		<?php } ?>
		<?php if(isset($Balaji_GDPI3) && $Balaji_GDPI3!='') {?>
		<tr id="TestPreference_ddlPreference3">
			<td width="23%" class="Contentbold">Centre Preference 3<span style="color:Red;display:none;" id="req_ddlPreference6"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
     <?php echo $Balaji_GDPI3; ?>
    </td>
              <td width="23%" class="Contentbold"> <span style="color:Red;display:none;" id="req_ddlPreference5"></span></td>
                        <td width="4%">
      
    </td>
                        <td nowrap="nowrap">
    </td>

		</tr>
		<?php } ?>
	</tbody></table>


<?php  
$souceInfo=explode(",",$Balaji_sourceInfo);
?>
<div class="familyInfoSection">
<ul>
<li>
	<h3 class="form-title">Source Of Information</h3>
                <div class="preff-cont">Newspaper advertisement: <span class="option-box"><?php if(in_array('Newspaper advertisement',$souceInfo)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Magazine: <span class="option-box"><?php if(in_array('Magazine',$souceInfo)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Internet: <span class="option-box"><?php if(in_array('Internet',$souceInfo)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Alumni: <span class="option-box"><?php if(in_array('Alumni',$souceInfo)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Current Students: <span class="option-box"><?php if(in_array('Current Students',$souceInfo)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Parental Reference: <span class="option-box"><?php if(in_array('Parental Reference',$souceInfo)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Coaching Classes: <span class="option-box"><?php if(in_array('Coaching Classes',$souceInfo)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Any other source: <span class="option-box"><?php if(in_array('Any other source',$souceInfo)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>

</li>
</ul>
</div>


<div class="familyInfoSection">
            
	<div class="reviewLeftCol" style="margin-bottom:10px;">
                <ul class="reviewChildLeftCol">
               
		    <?php if(isset($Balaji_nameOfNewspaper) && $Balaji_nameOfNewspaper!=''){ ?>
                    <li>
                        <label>Name of the Newspaper:</label>
                        <span><?php echo $Balaji_nameOfNewspaper;?></span>
                    </li>
		    <?php } ?>
		     
		     <?php if(isset($Balaji_nameOfMagazine) && $Balaji_nameOfMagazine!=''){ ?>
		    <li>
                        <label>Name of Magazine:</label>
                        <span><?php echo $Balaji_nameOfMagazine;?></span>
                    </li>
		    <?php } ?>
		    
                    
		</ul>
		
		<ul class="reviewChildRightCol">
		    <?php if(isset($Balaji_nameOfOther) && $Balaji_nameOfOther!=''){ ?>
		    <li>
                        <label>Any Other Source:</label>
                        <span><?php echo $Balaji_nameOfOther;?></span>
                    </li>
		    <?php } ?>
		    
		    <?php if(isset($Balaji_nameOfCoachingClass) && $Balaji_nameOfCoachingClass!=''){ ?>
		    <li>
                        <label>Name of Coaching Classes:</label>
                        <span><?php echo $Balaji_nameOfCoachingClass;?></span>
                    </li>
		    <?php } ?>
	    
                    
                </ul>
		
            </div>
	</div>


<?php
$proofAdd=explode(",",$Balaji_proofOfAdd);
?>

<div class="familyInfoSection">
<ul>
<li>
	<h3 class="form-title">Valid Proof of Address</h3>
                <div class="preff-cont">Driving Licence: <span class="option-box"><?php if(in_array('Driving Licence',$proofAdd)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Passport: <span class="option-box"><?php if(in_array('Passport',$proofAdd)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Voter's Id: <span class="option-box"><?php if(in_array('Voter\'s Id',$proofAdd)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Aadhar Card: <span class="option-box"><?php if(in_array('Aadhar Card',$proofAdd)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Ration card: <span class="option-box"><?php if(in_array('Ration card',$proofAdd)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Electricity Bill: <span class="option-box"><?php if(in_array('Electricity Bill',$proofAdd)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Lic Bond: <span class="option-box"><?php if(in_array('Lic Bond',$proofAdd)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Any Other Govt. Authorised Documents: <span class="option-box"><?php if(in_array('Any Other Govt. Authorised Documents',$proofAdd)){?><img src='/public/images/onlineforms/institutes/balaji/tick-icn.gif' border=0 /><?php } ?></span></div>

</li>
<li>
	<div class="reviewLeftCol" style="margin-bottom:30px";>
			<div class="formGreyBox">
			    <ul>
				<li>
				    <div class="personalInfoCol">
					<label>Industry Sponsored:</label>
					<span><?php echo $Balaji_sponsored;?></span>
				    </div>
				    
				    <div class="personalInfoCol">
					<label>Management Quota:</label>
					<span><?php echo $Balaji_mgtQuota;?></span>
				    </div>
				    <div class="clearFix"></div>
				</li>
			    </ul>
			</div>
	</div>
</li>

<?php if(isset($Balaji_hobby) && $Balaji_hobby!=''){?>
 <li>
		<h3 class="form-title">Hobbies and Interests</h3>
                <div class="spacer10 clearFix"></div>
                <div style="float:left;width:100%">
                    <div class="previewFieldBox" style="width:100%;float:left;">
                        <span style="border-bottom: none;"><?php echo $Balaji_hobby; ?></span>
                    </div>
                </div>
</li>
 <?php } ?>
 
</ul>
</div>
        <!--Family Info Ends here-->
        
        <!--Education Info Starts here-->
    	<div class="educationInfoSection">
            <div class="reviewTitleBox">
                <strong>Education Information:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
        	<h3 style="padding-left:12px">Education Std. X/XII:</h3>
            	<div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
                    	<p class="educationCol educationTitleColFirst">School/College/Institute</p>
                        <p class="educationCol">Board</p>
                        <p class="educationCol">Year of Completion</p>
                        <p class="educationYearCol">Final Grade/Marks in %</p>
                        <p class="educationSmallCol">Medium of Study</p>
			</div>
                   
                <ul>
                	<li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 10<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst word-wrap"><?php echo $class10School;?></span>
                            <span class="educationCol word-wrap"><?php echo $class10Board;?></span>
                            <span class="educationCol word-wrap"><?php echo $class10Year;?></span>
                            <span class="educationYearCol"><?php echo $class10Percentage;?></span>
                            <span class="educationSmallCol"><?php echo $Balaji_MediumX; ?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label class="labelBg"><strong>Class 12<sup>th</sup></strong></label>
                            <span class="educationCol educationColFirst word-wrap"><?php echo $class12School;?></span>
                            <span class="educationCol word-wrap"><?php echo $class12Board;?></span>
                            <span class="educationCol word-wrap"><?php echo $class12Year;?></span>
                            <span class="educationYearCol"><?php echo $class12Percentage;?></span>
                            <span class="educationSmallCol"><?php echo $Balaji_MediumXII;?></span>
						</div>
                    </li>
                    
                </ul>
                <div class="clearFix"></div>
		</div>
             </div>
	       

	
	<div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Graduation Details:</strong>
            </div>
	    
	    <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Graduation College/Institute Name :</label>
                        <span><?php echo $graduationSchool;?></span>
                    </li>
		    <li>
                        <label>Graduation Degree :</label>
                        <span><?php echo $Balaji_gradDegree;?></span>
                    </li>
		    <li>
			<?php if(isset($Balaji_grad3Specialization) && $Balaji_grad3Specialization!='') { ?>
				<label>Specialization in 3 years degree :</label>
				<span><?php echo $Balaji_grad3Specialization;?></span>
			<?php } else {?>
				<label>Specialization in 4 years degree :</label>
				<span><?php echo $Balaji_grad4Specialization;?></span>
			<?php } ?>
                    </li>
                    
                    
		    
                </ul>
                
                <ul class="reviewChildRightCol">
                   <li>
                        <label>Graduation University Name:</label>
                        <span><?php echo $Balaji_gradUniv;?></span>
                    </li>
		   
		   <li>
                        <label>Graduation Degree Duration:</label>
                        <span><?php echo $Balaji_gradDegreeDuration;?></span>
                    </li>
		   <li>
                        <label>Medium of study upto Graduation:</label>
                        <span><?php echo $Balaji_gradMedium;?></span>
                    </li>
                   
                </ul>
	    </div>
        
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Have you passed your Bachelor's degree from a recognized University? :</label>
                        <span><?php echo $Balaji_gradRecogUniversity;?></span>
                    </li>
                    
                    
		    
                </ul>
                
                <ul class="reviewChildRightCol">
                   <li>
                        <label>Are you studying in the final year of Bachelor's Degree now?:</label>
                        <span><?php echo $Balaji_studyFinalYear;?></span>
                    </li>
                   
                </ul>
		
            </div>
	    </div>
	    
	    <div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Graduation Marks:</strong>
            </div>
	    
	    <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
			<li><label style="font-size:14px;">Graduation 1st year</label></li>
                    <li>
                        <label>Year of Passing:</label>
                        <span><?php echo $Balaji_grad1YearPassing;?></span>
                    </li>
		    <li><label style="font-size:14px;">Graduation 2nd year</label></li>
		    <li>
                        <label>Year of Passing:</label>
                        <span><?php echo $Balaji_grad2YearPassing;?></span>
                    </li>
		    <li><label style="font-size:14px;">Graduation 3rd year</label></li>
		    <li>
                        <label>Year of Passing:</label>
                        <span><?php echo $Balaji_grad3YearPassing;?></span>
                    </li>
		    <?php if(isset($Balaji_grad4YearPassing) && $Balaji_grad4YearPassing!=''){?>
		    <li><label style="font-size:14px;">Graduation 4th year</label></li>	    
		    <li>
                        <label>Year of Passing:</label>
                        <span><?php echo $Balaji_grad4YearPassing;?></span>
                    </li>
		    <?php } ?>
                    
                    
		    
                </ul>
                
                <ul class="reviewChildRightCol">
		   <li></li>
		   <li></li>
                   <li>
                        <label>Percentage Marks:</label>
                        <span><?php echo $Balaji_grad1YearMarks;?></span>
                    </li>
		   
		   <li></li>
		    <li>
                        <label>Percentage Marks:</label>
                        <span><?php echo $Balaji_grad2YearMarks;?></span>
                    </li>
		    
		    <li></li>
		     <li>
                        <label>Percentage Marks:</label>
                        <span><?php echo $Balaji_grad3YearMarks;?></span>
                    </li>
		     <li></li>
		     <?php if(isset($Balaji_grad4YearMarks) && $Balaji_grad4YearMarks != ''){?>
		      <li>
                        <label>Percentage Marks:</label>
                        <span><?php echo $Balaji_grad4YearMarks;?></span>
                    </li>
                    <?php } ?>
                </ul>
		
            </div>
        </div>
	
	<?php if( $Balaji_postGradMarks!='' || $Balaji_postGradInst!='' || $Balaji_postGradYear!='' || $Balaji_postGradUniv!='' || $Balaji_postGradDegree!=''){ ?>
	<div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Post Graduation Details:</strong>
            </div>
        
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
		<?php if(isset($Balaji_postGradInst) && $Balaji_postGradInst!=''){ ?>
                    <li>
                        <label>Institution:</label>
                        <span><?php echo $Balaji_postGradInst;?></span>
                    </li>
		<?php } ?>
                <?php if(isset($Balaji_postGradYear) && $Balaji_postGradYear!=''){ ?>   
                    <li>
                        <label>Year of Completion:</label>
                        <span><?php echo $Balaji_postGradYear;?></span>
                    </li>
		 <?php } ?>
		 <?php if(isset($Balaji_postGradMarks) && $Balaji_postGradMarks!=''){ ?>
		    <li>
                        <label>Final Grade/Marks in % :</label>
                        <span><?php echo $Balaji_postGradMarks;?></span>
                    </li>
		<?php } ?>
                </ul>
                
                <ul class="reviewChildRightCol">
		<?php if(isset($Balaji_postGradUniv) && $Balaji_postGradUniv!=''){ ?>
                   <li>
                        <label>University:</label>
                        <span><?php echo $Balaji_postGradUniv;?></span>
                    </li>
		<?php } ?>
		<?php if(isset($Balaji_postGradDegree) && $Balaji_postGradDegree!=''){ ?>
		    <li>
                        <label>Degree:</label>
                        <span><?php echo $Balaji_postGradDegree;?></span>
                    </li>
                 <?php } ?>  
                </ul>
		
            </div>
        </div>
	<?php } ?>
	
	<div class="educationInfoSection">
	  <?php if(!empty(${'graduationExaminationName_mul_1'})):?>
            <div class="reviewTitleBox">
                <strong>Other Examination:</strong>
                <!--<a href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId?>/1/3/editProfile" title="Edit">Edit</a>-->
            </div>
        	<h3 style="padding-left:12px"></h3>
            	<div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
                    	<p class="educationCol educationTitleColFirst" style="padding-left:0 !important; width:110px;">Course Title</p>
                        <p class="educationCol">School/College/Institute</p>
                        <p class="educationCol" style="width:200px;">Board/University</p>
                        <p class="educationYearCol">Year</p>
                        <p class="educationSmallCol" style="width: 150px">Degree/Certificate</p>
			<p class="educationCol" style="width: 125px; ">Final Grade/Marks in %/Score</p>
                    </div>
		<?php endif; ?>
			<?php 
			for($j=1;$j<=4;$j++):?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
                   
                <ul>
                	
                    
                    <li>
                        <div class="formAutoColumns">
                            
                            <span class="educationCol educationColFirst word-wrap" style="width: 110px"><?php echo ${'graduationExaminationName_mul_'.$j};?></span>
                            <span class="educationCol word-wrap" style="width: 170px"><?php echo ${'graduationSchool_mul_'.$j};?></span>
                            <span class="educationCol word-wrap" style="width: 210px"><?php echo ${'graduationBoard_mul_'.$j};?></span>
                            <span class="educationYearCol" style="width: 90px"><?php echo ${'graduationYear_mul_'.$j};?></span>
                            <span class="educationSmallCol" style="width: 150px"><?php echo ${'otherCoursePGDegree_mul_'.$j};?></span>
			     <span class="educationSmallCol" style="width: 125px;text-align: center;"><?php echo ${'graduationPercentage_mul_'.$j};?></span>
						</div>
                    </li>
                    <?php endif;endfor; //endif;?>
                </ul>
                <div class="clearFix"></div>
             </div>
	    </div>
	     

        

        <!--Education Info Ends here-->

        <!--Work Exp Info Starts here-->
	
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
                                      $workExpInMonthValue=${'workExpInMonth'.$otherSuffix};
                                      $workExpNatureOfWorkValue=${'workExpNatureOfWork'.$otherSuffix};
				      $workExpTotalPayValue=${'workExpTotalPay'.$otherSuffix};
				      $workExpYearMonthValue=${'workExpYearMonth'.$otherSuffix};
                                      	      
				      
				      if($workCompany || $designation){$workExGiven = true;$total++; ?>
<div class="workInfoSection">
        <div class="reviewTitleBox">
                <strong>Work Experience:</strong>
	</div>
            <div class="reviewLeftCol widthAuto">
                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Name of Organization:</label>
                            <div style="width:290px; float:left"><?php echo $weCompanyName;?></div>
						</div>
                        	
                    	<div class="formColumns">
                    	<label class="timePeriodLabel3">Designation:</label>
                        <div style="width:290px; float:left"><?php echo $weDesignation;?></div>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <div style="width:290px; float:left"><?php echo $weLocation;?></div>
                    </div> 
                    
                    <div class="formColumns">
						<label class="timePeriodLabel3">Duration:</label>
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
		 <li>
			<div class="formColumns">
                            <label>Total Pay:</label>
                            <span><?php echo ${'workExpTotalPay_mul_'.$i};?></span>
			</div>
                        	
                    	<div class="formColumns">
			     <label class="timePeriodLabel3">Total Work Experience:</label>
			     <span><?php echo ${'workExpInMonth_mul_'.$i};?> <?php echo ${'workExpYearMonth_mul_'.$i};?></span>
                    </div>    
                 </li>
		 <li>
                    	
                        <div class="formColumns" style="width:98%">
                            <label>Nature of Work:</label>
                            <span><?php echo ${'workExpNatureOfWork_mul_'.$i};?></span>
			</div>	
                        
                 </li>
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="clearFix"></div>
     <div class="rolesResponsiblity">
     	<h3>Roles &amp; Responsibilities:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim($weRoles));?></li>
            </ul>
        </div>
     </div>
    
	  <div class="spacer10 clearFix"></div>
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
		 <li>
			
			<div class="formColumns">
                            <label>Total Pay:</label>
                            <span><?php echo ${'workExpTotalPay_mul_'.$i};?></span>
			</div>
                        	
                    	<div class="formColumns">
			     <label class="timePeriodLabel3">Total Work Experience:</label>
			     <span><?php echo ${'workExpInMonth_mul_'.$i};?> <?php echo ${'workExpYearMonth_mul_'.$i};?></span>
                    </div>    
                 </li>
		 <li>
                    	<div class="formColumns" style="width:98%">
                            <label>Nature of Work:</label>
                            <span><?php echo ${'workExpNatureOfWork_mul_'.$i};?></span>
			</div>
                        	
                        
                 </li>
		 
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="clearFix"></div>
     <div class="rolesResponsiblity">
     	<h3>Roles &amp; Responsibilities:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></li>
            </ul>
        </div>
    </div>
	<div class="spacer10 clearFix"></div>
    <?php endif;endfor;//endif;?>
   </div>
<?php }} ?>

<?php 
	$testsArray = explode(",",$Balaji_testNames);
	if(in_array("CAT",$testsArray)){ ?>
	
       <table width="100%" cellspacing="3" cellpadding="0" border="0" class="c1" id="PersonalDetails25">
		<tbody>
			<tr width="100%">
			<td nowrap="nowrap" colspan="9" class="alternateeven">
      <font class="bodyXtitleXtxtDuplicate">CAT </font>
    </td>
		</tr>
		<tr id="TestPreference_txtF24">
			<td width="23%" class="Contentbold">Center Code </td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
      <?=$Balaji_CATCenterCode?>
    </td>
			<td width="23%" class="Contentbold">Test Regn. No.</td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td nowrap="nowrap">
     <?php echo $catRollNumberAdditional; ?>
    </td>
		</tr>
		<tr id="TestPreference_txtF22">
			<td width="23%" class="Contentbold">Percentile<span style="color:Red;display:none;" id="rang_txtF22"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
      <?php echo $catPercentileAdditional; ?>
    </td>
		</tr>
	</tbody></table>
       
	<?php } if(in_array("MAT",$testsArray)){ ?> 
<table width="100%" cellspacing="3" cellpadding="0" border="0" class="c1" id="PersonalDetails26">
		<tbody>
			<tr width="100%">
			<td nowrap="nowrap" colspan="9" class="alternateeven">
      <font class="bodyXtitleXtxtDuplicate">MAT </font>
    </td>
		</tr>
		<tr id="TestPreference_txtF20">
			<td width="23%" class="Contentbold">Date(eg.DD/MM/YYYY)</td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
     <?php echo $matDateOfExaminationAdditional; ?>
    </td>
			<td width="23%" class="Contentbold">Roll No</td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td nowrap="nowrap">
      <?php echo $matRollNumberAdditional; ?>
    </td>
		</tr>
		<tr id="TestPreference_txtF19">
			<td width="23%" class="Contentbold">Percentile<span style="color:Red;display:none;" id="rang_txtF19"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
     <?php echo $matPercentileAdditional; ?>
    </td>
		</tr>
	</tbody></table>

	<?php } if(in_array("CMAT",$testsArray)){ ?> 
<table width="100%" cellspacing="3" cellpadding="0" border="0" class="c1" id="PersonalDetails268">
		<tbody>
			<tr width="100%">
			<td nowrap="nowrap" colspan="9" class="alternateeven">
      <font class="bodyXtitleXtxtDuplicate">CMAT </font>
    </td>
		</tr>
		<tr id="TestPreference_txtF8">
			<td width="23%" class="Contentbold">Test Regn. No.</td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
     <?=$cmatRollNumberAdditional?>
    </td>
			<td width="23%" class="Contentbold">Score<span style="color:Red;display:none;" id="reg_txtF10"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td nowrap="nowrap">
     <?php echo $cmatScoreAdditional; ?>
    </td>
		</tr>
		<tr id="TestPreference_txtSignature">
			<td width="23%" class="Contentbold">Rank<span style="color:Red;display:none;" id="rang_txtSignature"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
     <?php echo $cmatRankAdditional; ?>
    </td>
		</tr>
	</tbody></table>

<?php } if(in_array("XAT",$testsArray)){ ?> 

<table width="100%" cellspacing="3" cellpadding="0" border="0" class="c1" id="PersonalDetails271">
		<tbody>
			<tr width="100%">
			<td nowrap="nowrap" colspan="9" class="alternateeven">
      <font class="bodyXtitleXtxtDuplicate">XAT</font>
    </td>
		</tr>
		<tr id="TestPreference_txtF81">
			<td width="23%" class="Contentbold">Test Regn. No.</td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
     <?=$xatRollNumberAdditional;?>
    </td>
			<td width="23%" class="Contentbold">Center Code<span style="color:Red;display:none;" id="reg_txtF108"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td nowrap="nowrap">
     <?php echo $Balaji_XATCenterCode; ?>
    </td>
		</tr>
		<tr id="TestPreference_txtSignature8">
			<td width="23%" class="Contentbold">Percentile<span style="color:Red;display:none;" id="rang_txtSignature8"></span></td>
			<td width="4%">
      <b>:   </b>
    </td>
			<td width="23%">
     <?php echo $xatPercentileAdditional; ?>
    </td>
		</tr>
	</tbody>
</table>
<?php } ?>

<?php if($Balaji_1extraCurrAchievement!='' || $Balaji_1extraCurryear!='' || $Balaji_1extraCurrLevel!='' || $Balaji_1extraCurrtraits!='' || $Balaji_2extraCurrAchievement!='' || $Balaji_2extraCurryear!='' || $Balaji_2extraCurrLevel!='' || $Balaji_2extraCurrtraits!='' || $Balaji_3extraCurrAchievement!='' || $Balaji_3extraCurryear!='' || $Balaji_3extraCurrLevel!='' || $Balaji_3extraCurrtraits!='') { ?>
<div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Extra curricular Activities:</strong>
            </div>
            
            <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
		    <?php if(isset($Balaji_1extraCurrAchievement) && $Balaji_1extraCurrAchievement!='') { ?>
                    <li>
                        <label>Achievement 1:</label>
                        <span><?php echo $Balaji_1extraCurrAchievement; ?></span>
                    </li>
		    <?php } ?>
                    
		    <?php if(isset($Balaji_1extraCurryear) && $Balaji_1extraCurryear!='') { ?>
                    <li>
                        <label>Year:</label>
                        <span><?php echo $Balaji_1extraCurryear;?></span>
                    </li>
		    <?php }?>
		    
                </ul>
                
                <ul class="reviewChildRightCol">
		   <?php if(isset($Balaji_1extraCurrLevel) && $Balaji_1extraCurrLevel!='') { ?>
                   <li>
                        <label>Level:</label>
                        <span><?php echo $Balaji_1extraCurrLevel;?></span>
                    </li>
		   <?php } ?>
		   <?php if(isset($Balaji_1extraCurrtraits) && $Balaji_1extraCurrtraits!='') { ?>
		   <li>
                        <label>Personal Traits:</label>
                        <span><?php echo $Balaji_1extraCurrtraits;?></span>
                    </li>
                   <?php } ?>
                </ul>
		
            </div>
	    <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
		    <?php if(isset($Balaji_2extraCurrAchievement) && $Balaji_2extraCurrAchievement!='') { ?>
                    <li>
                        <label>Achievement 2:</label>
                        <span><?php echo $Balaji_2extraCurrAchievement; ?></span>
                    </li>
                    <?php }?>
		    
		    <?php if(isset($Balaji_2extraCurryear) && $Balaji_2extraCurryear!='') { ?>
                    <li>
                        <label>Year:</label>
                        <span><?php echo $Balaji_2extraCurryear;?></span>
                    </li>
		    <?php } ?>
		    
                </ul>
                
                <ul class="reviewChildRightCol">
		   <?php if(isset($Balaji_2extraCurrLevel) && $Balaji_2extraCurrLevel!='') { ?>
                   <li>
                        <label>Level:</label>
                        <span><?php echo $Balaji_2extraCurrLevel;?></span>
                    </li>
		   
		   <?php } ?>
		   <?php if(isset($Balaji_2extraCurrtraits) && $Balaji_2extraCurrtraits!='') { ?>
		   <li>
                        <label>Personal Traits:</label>
                        <span><?php echo $Balaji_2extraCurrtraits;?></span>
                    </li>
                   <?php } ?>
                </ul>
		
            </div>
	    
	    <div class="reviewLeftCol">
                <ul class="reviewChildLeftCol">
		    <?php if(isset($Balaji_3extraCurrAchievement) && $Balaji_3extraCurrAchievement!='') { ?>
                    <li>
                        <label>Achievement 3:</label>
                        <span><?php echo $Balaji_3extraCurrAchievement; ?></span>
                    </li>
                    <?php } ?>
		    <?php if(isset($Balaji_3extraCurryear) && $Balaji_3extraCurryear!='') { ?>
                    <li>
                        <label>Year:</label>
                        <span><?php echo $Balaji_3extraCurryear;?></span>
                    </li>
		    <?php } ?>
		    
                </ul>
                
                <ul class="reviewChildRightCol">
		   <?php if(isset($Balaji_3extraCurrLevel) && $Balaji_3extraCurrLevel!='') { ?>	
                   <li>
                        <label>Level:</label>
                        <span><?php echo $Balaji_3extraCurrLevel;?></span>
                    </li>
		   <?php } ?>
		   <?php if(isset($Balaji_3extraCurrtraits) && $Balaji_3extraCurrtraits!='') { ?>
		   <li>
                        <label>Personal Traits:</label>
                        <span><?php echo $Balaji_3extraCurrtraits;?></span>
                    </li>
                   <?php } ?>
                </ul>
		
            </div>
	    
</div>
<?php } ?>

<div class="familyInfoSection">
            <div class="reviewTitleBox">
                <strong>Other:</strong>
            </div>
        
            <div class="reviewLeftCol" style="margin-bottom:20px";>
                <ul class="reviewChildLeftCol">
                    <li>
                        <label>Outstanding Academic Performer:</label>
                        <span><?php echo $Balaji_outAcademicPerf; ?></span>
                    </li>
                    
                    <li>
                        <label>Private/external/distance learning mode student:</label>
                        <span><?php echo $Balaji_learningModeStudent;?></span>
                    </li>
		    <li>
                        <label>Have Gap in Education?:</label>
                        <span><?php echo $Balaji_gapInEdu;?></span>
                    </li>
		    
		    
                </ul>
                
                <ul class="reviewChildRightCol">
		  <li></li>
		  <li></li>
		  <li></li>
		  <?php if(isset($Balaji_learningMode) && $Balaji_learningMode!=''){ ?>		
                   <li>
                        <label>Learning Mode Course:</label>
                        <span><?php echo $Balaji_learningMode;?></span>
                    </li>
		   <?php } else{?>
		   <li>
                        <label></label>
                        <span></span>
                    </li>
		   <?php } ?>
		   <?php if(isset($Balaji_gapReason) && $Balaji_gapReason!=''){ ?>	
		   <li>
                        <label>Reason for Gap in Education:</label>
                        <span><?php echo $Balaji_gapReason;?></span>
                    </li>
		   <?php } ?>
                   
                </ul>
		
            </div>
</div>
	
	<div id="responsibilityList">
		
                  	<label style="font-weight:bold; width:700px">DECLARATION:</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                            I declare that all my statements made in this application for admission are correct and complete. I also understand that I have
                            read the notes above and the submission of application does not automatically qualify me for GD/PI. The Application Fee is
                            non-refundable under any circumstances.
							<div class="clearFix spacer25"></div>
							<div style="float:left; width:300px;">
                                <div class="formColumns2" style="width:250px;">
                                    <label style="width:auto; padding-top:0">Place :</label>
                                    <span>&nbsp;<?php if(isset($firstName) && $firstName!='') {echo $Cstate;} ?></span>
                                </div>
                                <div class="clearFix spacer10"></div>
                                
                                <div class="formColumns2" style="width:250px;">
                                    <label style="width:auto; padding-top:0">Date :</label>
                                    <span><?php echo $paymentDetails['date']?date("d/m/Y", strtotime($paymentDetails['date'])):""?></span>
                                </div>
                            </div>
                            
                            <div style="float:right; width:500px; text-align:right">
                                <p>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></p>
                                <div>Signature of the Applicant</div>
                             </div>
                        </div>
                   
	</div>

