<style>
@media print
 {
.breakings {page-break-after: left}

 }
</style>

<?php $valuePrefix = '&nbsp;'; ?>
<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />

<!--Skyline Form Preview Starts here-->
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <div class="float_L"><img src="/public/images/onlineforms/institutes/skyline/skyline-logo.gif" alt="" /></div>
		<div class="">
		    <p style="font-size:27px;">
		    <strong>SKYLINE BUSINESS SCHOOL</strong><br />
		    </p>
		</div>
            </div>
		<ul style="float:left; width:100%; margin-top:10px;">
		<li>
			<label style="float:left;"><strong>Form Id. : </strong></label>
			<div class="form-details"><?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></div>

			</li>
		</ul>
<div class="clearFix"></div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">APPLICATION FORM FOR  MBA + PGDM 2014</div>  
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
    </div>
    <div class="spacer15 clearFix"></div>
    <div id="custom-form-content">
    	<ul>
            <li> 
            	<div class="colums-width">
                    <label>Student's Name : </label>
                    <div class="form-details"><?=$firstName?><?=$valuePrefix?><?php echo $middleName.' '.$lastName; ?></div>
                </div>
		<div class="colums-width">
		    <label>Nationality : </label>
		    <div class="form-details"><?=$nationality?></div>
		</div>
            </li>

           <li> 
            	<div class="colums-width">
                    <label>Date of Birth : </label>
                    <div class="form-details"><?=$dateOfBirth?></div>
                </div>
		<div class="colums-width">
		    <label>Email Id : </label>
		    <div class="form-details"><?=$email?></div>
		</div>
            </li>



            <li>
            	<h3 class="form-title">Communication Information</h3>
            	<label>Address (for communication) : </label>
                <div class="form-details"><?php if($ChouseNumber) echo $ChouseNumber.', ';
						if($CstreetName) echo $CstreetName.', ';
						if($Carea) echo $Carea;?>
		</div>
            </li>
            
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City :</label>
                    <div class="form-details"><?=$Ccity;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State :</label>
                    <div class="form-details"><?=$Cstate;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code :</label>
                    <div class="form-details"><?=$Cpincode; ?></div>
                </div>
            </li>

            <li>
                <div class="colums-width">
                    <label>Country :</label>
                    <div class="form-details">&nbsp;<?php echo $Ccountry; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Tel No. :</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $std.$landlineNumber; ?></div>
               	</div>
            	<div class="colums-width">
                    <label>Mobile Number :</label>
                    <div class="form-details">&nbsp;<?php if($mobileISDCode) echo $mobileISDCode.'-'; echo $mobileNumber;?></div>
                </div>
            </li>

            <li>
            	<h3 class="form-title">Information about Father</h3>
            	<div class="colums-width">
                    <label>Father's Name : </label>
                    <div class="form-details"><?=$fatherName?></div>
                </div>
            	<div class="colums-width">
                    <label>Father's Profession/Designation : </label>
                    <div class="form-details"><?=$fatherOccupationSkyline?></div>
                </div>
            </li>
            <li>
            	<div class="colums-width">
                    <label>Father's Name of Organisation : </label>
                    <div class="form-details"><?=$fatherOrganization?></div>
                </div>
            	<div class="colums-width">
                    <label>Father's Mobile No. : </label>
                    <div class="form-details"><?=$fatherMobileNumberSkyline?></div>
                </div>
            </li>
            <li>
            	<div class="colums-width">
                    <label>Father's Email Id : </label>
                    <div class="form-details"><?=$fatherEmailId?></div>
                </div>
            </li>

            <li>
            	<h3 class="form-title">Information about Mother</h3>
            	<div class="colums-width">
                    <label>Mother's Name : </label>
                    <div class="form-details"><?=$MotherName?></div>
                </div>
            	<div class="colums-width">
                    <label>Mother's Profession/Designation : </label>
                    <div class="form-details"><?=$MotherOccupation?></div>
                </div>
            </li>
            <li>
            	<div class="colums-width">
                    <label>Mother's Name of Organisation : </label>
                    <div class="form-details"><?=$MotherOrganization?></div>
                </div>
            	<div class="colums-width">
                    <label>Mother's Mobile No. : </label>
                    <div class="form-details"><?=$MotherMobileNumberSkyline?></div>
                </div>
            </li>
            <li>
            	<div class="colums-width">
                    <label>Mother's Email Id : </label>
                    <div class="form-details"><?=$MotherEmailId?></div>
                </div>
            </li>
            
            <li>
            	<h3 class="form-title">Permanent Address Information</h3>
            	<label>Permanent Address : </label>
                <div class="form-details"><?php if($houseNumber) echo $houseNumber.', ';
						if($streetName) echo $streetName.', ';
						if($area) echo $area;?>
		</div>
            </li>
            
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City :</label>
                    <div class="form-details"><?=$city;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State :</label>
                    <div class="form-details"><?=$state;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code :</label>
                    <div class="form-details"><?=$pincode; ?></div>
                </div>
            </li>

            <li>
                <div class="colums-width">
                    <label>Country :</label>
                    <div class="form-details">&nbsp;<?php echo $country; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Tel No. :</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $std.$landlineNumber; ?></div>
               	</div>
            </li>

            <li>
            	<h3 class="form-title">Guardian Information</h3>
            	<div>
                    <label>If Outstation student then Delhi Guardian's Name &amp; Address :</label>
                    <div class="form-details">&nbsp;<?php echo $GudardianNameAndAddress; ?></div>
               	</div>
            </li>


            <li>
            	<div class="colums-width" style="width:230px;">
                    <label>Tel. No. :</label>
                    <div class="form-details"><?=$GudardianTelephone;?></div>
                </div>
            	<div class="colums-width" style="width:240px;">
                    <label>Mobile No. :</label>
                    <div class="form-details"><?=$GudardianMobile;?></div>
                </div>
            	
                <div class="colums-width" style="width:350px;">
                    <label>E-mail Id :</label>
                    <div class="form-details"><?=$GudardianEmail; ?></div>
                </div>
            </li>


	    <li>
            	<h3 class="form-title">Education (List your School / College / Professional qualifications, starting from Class X)</h3>
		<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" style="border: 1px solid #000000;border-collapse: collapse;">
		    <tr>
			<th align="center" valign="top">Institute / School / College</th>
			<th align="center" valign="top">Qualification<br />eg. X, XII, BA, B.Com, any other</th>
			<th align="center" width="200" style="margin:0; padding:0">
			    <table width="100%" cellpadding="7" cellspacing="0" border="0">
				<tr>
    				    <td style="border-bottom:1px solid #000000" align="center" colspan="2">Period</td>
				</tr>
				<tr>
				    <td width="50%" align="center" style="border-right:1px solid #000000">From</td>
				    <td align="center" width="50%">To</td>
				</tr>
			    </table>
			</th>
			<th align="center" valign="top">Awarding Body / Board / University</th>
			<th align="center" valign="top">Percentage Marks (Agg)</th>			
		    </tr>

		    <?php
		    for($i=4;$i>=1;$i--){
			    if(${'graduationExaminationName_mul_'.$i}){
			    ?>
			    <tr>
				    <td align="center" valign="top">&nbsp;<?=${'graduationSchool_mul_'.$i}?></td>
				    <td align="center" valign="top"><?=${'graduationExaminationName_mul_'.$i}?></td>
				    <td align="center" valign="top"><?php echo ${'otherCourseStartDate_mul_'.$i}." - ".${'otherCourseEndDate_mul_'.$i}; ?></td>
				    <td align="center" valign="top"><?=${'graduationBoard_mul_'.$i}?></td>
				    <td align="center" valign="top" align="center"><?=${'graduationPercentage_mul_'.$i}?></td>
			    </tr>	
			    <?php
			    }
		    }
		    ?>

		    <?php if($graduationExaminationName) { ?>
		    <tr>
			<td align="center" valign="top">&nbsp;<?=$graduationSchool?></td>
			<td align="center" valign="top"><?php echo $graduationExaminationName; ?></td>
			<td align="center" valign="top"><?php if($graduationStartDate && $graduationStartDate!='') { echo $graduationStartDate." - ".$graduationEndDate; } ?></td>
			<td align="center" valign="top"><?=$graduationBoard?></td>
			<td align="center" valign="top" align="center"><?=$graduationPercentage?></td>
		    </tr>
		    <?php } ?> 

		    <tr>
			<td align="center" valign="top">&nbsp;<?=$class12School?></td>
			<td align="center" valign="top"><?php echo $class12ExaminationName; ?></td>
			<td align="center" valign="top"><?php if($class12StartDate && $class12StartDate!='') { echo $class12StartDate." - ".$class12EndDate; } ?></td>
			<td align="center" valign="top"><?=$class12Board?></td>
			<td align="center" valign="top" align="center"><?=$class12Percentage?></td>
		    </tr>
		    
		    <tr>
			<td align="center" valign="top">&nbsp;<?=$class10School?></td>
			<td align="center" valign="top"><?php echo $class10ExaminationName; ?></td>
			<td align="center" valign="top"><?php if($class10StartDate && $class10StartDate!='') { echo $class10StartDate." - ".$class10EndDate; } ?></td>
			<td align="center" valign="top"><?=$class10Board?></td>
			<td align="center" valign="top" align="center"><?=$class10Percentage?></td>
		    </tr>							

                </table>
	    </li>


            <li>
            	<h3 class="form-title">QUALIFYING EXAMINATION :(If applicable)</h3>
	    </li>
	    
	    <?php if(strpos($testNamesSkyline,'CAT') !== false){ ?>
	    <li id="cat1" >
            	<div class="colums-width">
                    <label>CAT Date of Examination : </label>
                    <div class="form-details"><?=$catDateOfExaminationAdditional?></div>
                </div>
            	<div class="colums-width">
                    <label>CAT Roll Number : </label>
                    <div class="form-details"><?=$catRollNumberAdditional?></div>
                </div>
            </li>

            <li id="cat2" >
            	<div class="colums-width">
                    <label>CAT Score : </label>
                    <div class="form-details"><?=$catScoreAdditional?></div>
                </div>
            	<div class="colums-width">
                    <label>CAT Percentile : </label>
                    <div class="form-details"><?=$catPercentileAdditional?></div>
                </div>
            </li>
	    <?php } ?>

	    <?php $tests = explode(',',$testNamesSkyline); foreach ($tests as $test){ if($test=='MAT'){ ?>
	    <li id="mat1">
            	<div class="colums-width">
                    <label>MAT Date of Examination : </label>
                    <div class="form-details"><?=$matDateOfExaminationAdditional?></div>
                </div>
            	<div class="colums-width">
                    <label>MAT Roll Number : </label>
                    <div class="form-details"><?=$matRollNumberAdditional?></div>
                </div>
            </li>

            <li id="mat2">
            	<div class="colums-width">
                    <label>MAT Score : </label>
                    <div class="form-details"><?=$matScoreAdditional?></div>
                </div>
            	<div class="colums-width">
                    <label>MAT Percentile : </label>
                    <div class="form-details"><?=$matPercentileAdditional?></div>
                </div>
            </li>
	    <?php } } ?>
	    
	    
	    <?php if(strpos($testNamesSkyline,'CMAT') !== false){ ?>
	    <li id="cmat1" >
            	<div class="colums-width">
                    <label>CMAT Date of Examination : </label>
                    <div class="form-details"><?=$cmatDateOfExaminationAdditional?></div>
                </div>
            	<div class="colums-width">
                    <label>CMAT Roll Number : </label>
                    <div class="form-details"><?=$cmatRollNumberAdditional?></div>
                </div>
            </li>

            <li id="cmat2" >
            	<div class="colums-width">
                    <label>CMAT Score : </label>
                    <div class="form-details"><?=$cmatScoreAdditional?></div>
                </div>
            	<div class="colums-width">
                    <label>CMAT Rank : </label>
                    <div class="form-details"><?=$cmatRankAdditional?></div>
                </div>
            </li>
	    <?php } ?>
	    
    	    <?php if(strpos($testNamesSkyline,'XAT') !== false){ ?>
	    <li id="xat1" >
            	<div class="colums-width">
                    <label>XAT Date of Examination : </label>
                    <div class="form-details"><?=$xatDateOfExaminationAdditional?></div>
                </div>
            	<div class="colums-width">
                    <label>XAT Roll Number : </label>
                    <div class="form-details"><?=$xatRollNumberAdditional?></div>
                </div>
            </li>

            <li id="xat2" >
            	<div class="colums-width">
                    <label>XAT Score : </label>
                    <div class="form-details"><?=$xatScoreAdditional?></div>
                </div>
            	<div class="colums-width">
                    <label>XAT Percentile : </label>
                    <div class="form-details"><?=$xatPercentileAdditional?></div>
                </div>
            </li>
	    <?php } ?>
	    
	    
            <li>
            	<h3 class="form-title">Work Experience (If applicable)</h3>
	    </li>    
	    <?php
	      $workExGiven = false;
	      $total = 0;
	      for($i=0; $i<4; $i++){

		  //$mulSuffix = $i>0?'_mul_'.$i:'';
		  $mulSuffix = $i>0?'_mul_'.$i:'';
		  $otherSuffix = '_mul_'.$i;
		  $companyName = ${'weCompanyName'.$mulSuffix};
		  $durationFrom = ${'weFrom'.$mulSuffix};
		  $durationTo = trim(${'weTimePeriod'.$mulSuffix})?'Till date':${'weTill'.$mulSuffix};
		  $designation = ${'weDesignation'.$mulSuffix};
		  $natureOfWork = ${'weRoles'.$mulSuffix};
		  if($companyName || $designation){ $workExGiven = true;$total++; ?>
		      
            <li <?php if($i>0){echo "style='border-top: 1px solid;'";} ?>>
            	<div class="colums-width" <?php if($i>0){echo "style='margin-top:7px;'";} ?>>
                    <label><?=$companyName?> Present Position : </label>
                    <div class="form-details"><?=$designation?>
		    </div>
                </div>
            	<div class="colums-width" <?php if($i>0){echo "style='margin-top:7px;'";} ?>>
                    <label><?=$companyName?> Date of appointment : </label>
                    <div class="form-details"><?=${'dateOfAppointment'.$otherSuffix}?></div>
                </div>
            </li>
	    
            <li>
            	<div class="colums-width">
                    <label>Name and address of employer : </label>
                    <div class="form-details"><?=$companyName?>, <?=${'addressOfEmployer'.$otherSuffix}?>
		    </div>
                </div>
            </li>
  
            <li>
            	<div class="colums-width">
                    <label><?=$companyName?> Tel. No. : </label>
                    <div class="form-details"><?=${'employerTelephoneNumber'.$otherSuffix}?></div>
                </div>
            	<div class="colums-width">
                    <label><?=$companyName?> Fax : </label>
                    <div class="form-details"><?=${'employerFaxNumber'.$otherSuffix}?></div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label><?=$companyName?> E-mail Id : </label>
                    <div class="form-details"><?=${'employerEmailId'.$otherSuffix}?></div>
                </div>
	    </li>
	    <?php }} ?>

        <?php if($total>0){ ?>
	    <li>
            	<div class="colums-width">
                    <label>Total no. of years work experience : </label>
                    <div class="form-details"><?=$totalYearsOfExperience?> <?php if($totalYearsOfExperience && strtolower($totalYearsOfExperience) != 'na') echo 'yrs'; ?></div>
                </div>
            </li>
        <?php } ?>

            <li>
            	<h3 class="form-title">Please state briefly why you want to join this course</h3>
            	<div>
                    <div class="form-details"><?=nl2br($whyJoinCourse)?></div>
                </div>
            </li>
    
            <li>
            	<h3 class="form-title">Please state briefly why you want to join skyline business school</h3>
            	<div>
                    <div class="form-details"><?=nl2br($whyJoinSkyline)?></div>
                </div>
            </li>

            <li>
            	<h3 class="form-title">General</h3>
            	<div>
                    <label>Sources of information about skyline : </label>
                    <div class="form-details"><?=$sourceOfInformation?></div>
                </div>
            </li>

            <li>
            	<div>
                    <label>Preferred GD/Interview location : </label>
                    <div class="form-details"><?=$gdpiLocation?></div>
                </div>
            </li>

             <li>
            	<div>
                    <label> GD/Interview Date : </label>
                    <div class="form-details"><?=$Skyline_GDPI1_Date?></div>
                </div>
            </li>

           

	     
            <li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I, &nbsp; <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
		, hereby certify that the information stated in this application form is true and correct to the best of my knowledge and that nothing has been concealed therein. I have read and understood the conditions mentioned clearly. The degree/diploma/certificate will be awarded to me only on the successful completion of the course requirements and the examination of the course applied for.  Only enrolling for the course does not make me eligible for the award.<br/><br/>I understand that in case any information submitted by me is found to be incorrect or not fully furnished by original documents at any point of time, either during the course, or after the completion of the relevant course, it would lead to cancellation of enrolment/dropping my name from the mentioned course without any fee refund. Furthermore, once I am enrolled in the course, I declare that I shall continue to attend and pay the fee as mentioned in the specific fee structure.
                <div class="spacer15 clearFix"></div>
                <label>Place:</label>
                <div class="form-details"><?php if(isset($firstName) && $firstName!='') {echo $Ccity;} ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label>Date:</label>
                <div class="form-details"><?php
                                       if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
                                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                                         }
                                ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label>Signature of the Candidate:</label>
                <div class="form-details"><?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></div>
            </li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>

