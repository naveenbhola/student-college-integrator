  	<?php $valuePrefix = '&nbsp;'; ?>
   	<!--LIBA Form Preview Starts here-->
	<link href="/public/css/onlineforms/liba/LIBA_styles.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
    	<div class="previewHeader">
        	<div class="instLogoBox" style="float:left"><img src="/public/images/onlineforms/institutes/liba/logo2New.gif" alt="" /></div>
            <div class="courseNameDetails" style="padding-top: 20px;padding-right: 100px;">
            	<p>LIBA <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2015";}?> Candidates Details</p>
            </div>
        	<div class="spacer5 clearFix"></div>    
			      <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  <strong class="editFormLink applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
			      <?php } ?>
			<div>
                <div class="spacer10 clearFix"></div>
            	<h5>Candidate's Photograph and Signature</h5>
        		<div class="spacer5 clearFix"></div>        
			<?php if($profileImage) { ?>
			<div class="picBox">
			    <img width="195" height="192" src="<?php echo $profileImage; ?>" />
			</div>
			<?php } ?>
			<?php if($signatureImageLIBA) { ?>
			<div class="signBox"><img src="<?php echo getSmallImage($signatureImageLIBA); ?>" /></div>
			<?php } ?>
            </div>
        </div>
        
        <div class="previewBody">
        <div class="spacer25 clearFix"></div>
        <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="130"><?php if($xatIdLIBA && $xatIdLIBA!='NA') echo "XAT ID"; if($xatIdLIBA && $catRollNumberAdditional && $xatIdLIBA!='NA' && $catRollNumberAdditional!='NA') echo " / "; if($catRollNumberAdditional && $catRollNumberAdditional!='NA') echo "CAT ID"; if(!($xatIdLIBA && $catRollNumberAdditional)) echo "XAT ID / CAT ID"; ?></th>
                <th width="130">LIBA ID </th>
                <th width="220">Candidate Name</th>
                <th width="110">Date of Birth</th>
                <th width="80">Gender</th>
                <th width="180">Registration Date</th>
                <th width="120">Marital Status</th>
            </tr>
            
            <tr>
                <td valign="top">&nbsp;<?php if($xatIdLIBA && $xatIdLIBA!='NA') echo $xatIdLIBA; if($xatIdLIBA && $catRollNumberAdditional && $xatIdLIBA!='NA' && $catRollNumberAdditional!='NA') echo " / "; if($catRollNumberAdditional && $catRollNumberAdditional!='NA') echo $catRollNumberAdditional; ?></td>
                <td valign="top">&nbsp;<?php echo ($instituteSpecId>0)?'LIBA'.$instituteSpecId:'';?></td>
                <td valign="top">&nbsp;<?=($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName?></td>
                <td valign="top">&nbsp;<?php if($dateOfBirth){ echo $dateOfBirth; }?></td>
                <td valign="top">&nbsp;<?=$gender?></td>
                <td valign="top">&nbsp;<?php
			if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
			      echo date("d/m/Y h:i:s a", strtotime($paymentDetails['date']));
			  }
		?></td>
                <td valign="top"><?=$maritalStatus?></td>
            </tr>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="130">Religion</th>
                <th width="130">Other Religion</th>
                <th width="130">Community</th>
                <th width="130">Other Community</th>
                <th width="100">Required Hostel</th>
            </tr>
            
            <tr>
                <td valign="top">&nbsp;<?=$religionLIBA?></td>
                <td valign="top">&nbsp;<?php if(isset($otherReligionLIBA) && $otherReligionLIBA!='') echo $otherReligionLIBA; else echo "-";?></td>
                <td valign="top">&nbsp;<?php echo $communityLIBA;?></td>
                <td valign="top">&nbsp;<?php if(isset($otherCommunityLIBA) && $otherCommunityLIBA!='') echo $otherCommunityLIBA; else echo "-";?></td>
                <td valign="top">&nbsp;<?=$hostelRequiredLIBA?></td>
            </tr>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="80%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="33%">Mother Toungue</th>
                <th width="33%">Domicile</th>
                <th width="33%">Dalit Candidate</th>
            </tr>
            <tr>
                <td valign="top">&nbsp;<?=$motherTongueLIBA?></td>
                <td valign="top">&nbsp;<?=$domicileLIBA?></td>
                <td valign="top">&nbsp;<?=$dalitLIBA?></td>
            </tr>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="60%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="50%">Annual Family Income</th>
                <th width="50%">Interview City</th>
            </tr>
            <tr>
                <td valign="top">&nbsp;<?=$familyIncomeLIBA?></td>
                <td valign="top">&nbsp;<?=$gdpiLocation?></td>
            </tr>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="130">Relation</th>
                <th width="150">Name</th>
                <th width="200">Organization</th>
                <th width="200">Designation</th>
            </tr>
            <tr>
                <td valign="top">Father</td>
                <td valign="top">&nbsp;<?=$fatherName?></td>
                <td valign="top">&nbsp;<?=$fatherOrganizationLIBA?></td>
                <td valign="top">&nbsp;<?=$fatherDesignationLIBA?></td>
            </tr>
            <tr>
                <td valign="top">Mother</td>
                <td valign="top">&nbsp;<?=$MotherName?></td>
                <td valign="top">&nbsp;<?=$motherOrganizationLIBA?></td>
                <td valign="top">&nbsp;<?=$motherDesignationLIBA?></td>
            </tr>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="33%">Correspondence Address 1</th>
                <th width="33%">Correspondence Address 2</th>
                <th width="34%">Correspondence Address 3</th>
            </tr>
            <tr>
                <td valign="top">&nbsp;<?php echo $ChouseNumber;
			if($CstreetName) echo ', '.$CstreetName; 
			?>
                <td valign="top">&nbsp;<?php if($Carea) echo $Carea;?></td>
                <td valign="top">&nbsp;</td>
            </tr>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="60%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="50%">City</th>
                <th width="50%">State</th>
            </tr>
            <tr>
                <td valign="top">&nbsp;<?=$Ccity?></td>
                <td valign="top">&nbsp;<?=$Cstate?></td>
            </tr>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="33%">Pin Code</th>
                <th width="33%">Phone No.</th>
                <th width="34%">Mobile No.</th>
            </tr>
            <tr>
                <td valign="top">&nbsp;<?=$Cpincode?></td>
                <td valign="top">&nbsp;<?php echo $landlineISDCode.'-'; 
			  echo (isset($landlineSTDCode) && $landlineSTDCode!='')?$landlineSTDCode.'-':'';
			  echo $landlineNumber; ?>
		</td>
                <td valign="top">&nbsp;<?php echo $mobileISDCode.'-'.$mobileNumber;?></td>
            </tr>
        </table>
        <div class="spacer20 clearFix"></div>
        <table width="60%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="50%">Primary Email</th>
                <th width="50%">Alternate Email</th>
            </tr>
            <tr>
                <td valign="top">&nbsp;<?=$email?></td>
                <td valign="top">&nbsp;<?=$altEmail?></td>
            </tr>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="60%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="50%">Stream Of Graduation</th>
                <th width="50%">Edu. Qualification</th>
            </tr>
            <tr>
                <td valign="top">&nbsp;<?=$gradStreamLIBA?></td>
                <td valign="top">&nbsp;<?=$graduationExaminationName?></td>
            </tr>

	    <?php
	    $otherCourseShown = false;
	    for($i=1;$i<=4;$i++){
		    if(${'graduationExaminationName_mul_'.$i}){
		    ?>
		    <tr>
			<td valign="top">&nbsp;<?=${'otherCourseStream_mul_'.$i};?></td>
			<td valign="top">&nbsp;<?=${'graduationExaminationName_mul_'.$i}?></td>
		    </tr>
	    <?php }
	    } ?>

        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="120">Qualification</th>
                <th width="180">Name Of Degree</th>
                <th width="200">Board / University</th>
                <th width="200">Institution</th>
                <th width="130">Completion Year</th>
                <th width="80">Marks %</th>
            </tr>
            <tr>
                <td valign="top">Class X*</td>
                <td valign="top">&nbsp;<?=$class10ExaminationName?></td>
                <td valign="top">&nbsp;<?=$class10Board?></td>
                <td valign="top">&nbsp;<?=$class10School?></td>
                <td valign="top">&nbsp;<?=$class10Year?></td>
                <td valign="top">&nbsp;<?=$class10Percentage?></td>
            </tr>
            <tr>
                <td valign="top">Class XII*</td>
                <td valign="top">&nbsp;<?=$class12ExaminationName?></td>
                <td valign="top">&nbsp;<?=$class12Board?></td>
                <td valign="top">&nbsp;<?=$class12School?></td>
                <td valign="top">&nbsp;<?=$class12Year?></td>
                <td valign="top">&nbsp;<?=$class12Percentage?></td>
            </tr>
            <tr>
                <td valign="top">Degree*</td>
                <td valign="top">&nbsp;<?=$graduationExaminationName?></td>
                <td valign="top">&nbsp;<?=$graduationBoard?></td>
                <td valign="top">&nbsp;<?=$graduationSchool?></td>
                <td valign="top">&nbsp;<?=$graduationYear?></td>
                <td valign="top">&nbsp;<?=$graduationPercentage?></td>
            </tr>
	    <?php
	    $otherCourseShown = false;
	    for($i=1;$i<=4;$i++){
		    if(${'graduationExaminationName_mul_'.$i}){
		    ?>
		    <tr>
			<td valign="top">Other*</td>
			<td valign="top">&nbsp;<?=${'graduationExaminationName_mul_'.$i}?></td>
			<td valign="top">&nbsp;<?=${'graduationBoard_mul_'.$i}?></td>
			<td valign="top">&nbsp;<?=${'graduationSchool_mul_'.$i}?></td>
			<td valign="top">&nbsp;<?=${'graduationYear_mul_'.$i}?></td>
			<td valign="top">&nbsp;<?=${'graduationPercentage_mul_'.$i}?></td>
		    </tr>
	    <?php }
	    } ?>
        </table>
        
        <div class="spacer20 clearFix"></div>
        <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="contentsTable">
            <tr>
                <th width="200">Designation</th>
                <th width="200">Organization</th>
                <th width="160">Duration in Months</th>
                <th width="90">From Date</th>
                <th width="90">To Date</th>
                <th width="110">Annual Salary</th>
            </tr>

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
		      if(trim(${'weTimePeriod'.$mulSuffix})){
			    $rolesVal = ${'weRoles'.$mulSuffix};
		      }
		      $designation = ${'weDesignation'.$mulSuffix};
		      $natureOfWork = ${'weRoles'.$mulSuffix};
		      $annualSalary = ${'annualSalaryLIBA'.$otherSuffix};

		      if($companyName || $designation){ $workExGiven = true; $total++; ?>
	    <tr>
		<td valign="top">&nbsp;<?php echo $designation; ?></td>
		<td valign="top">&nbsp;<?php echo $companyName; ?></td>
		<td valign="top">&nbsp;<?php
			if($durationFrom) {
				$startDate = getStandardDate($durationFrom);
				$endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
				$totalDuration = getTimeDifference($startDate,$endDate);
				echo ($totalDuration['months']<0)?0:$totalDuration['months'];
			}
			?></td>
		<td valign="top">&nbsp;<?php echo $durationFrom; ?></td>
		<td valign="top">&nbsp;<?php echo ($durationTo=='Till date')?'Till Date':$durationTo; ?></td>
		<td valign="top">&nbsp;<?php echo $annualSalary; ?></td>
	    </tr>
	    <?php }} ?>
				    
	    <?php 
		for($i=$total; $i<1; $i++){ ?>
			<tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
			</tr>
	    <?php } ?>

            
        </table>
	</div>
    </div>
    
	<!--LIBA Form Preview Ends here-->
