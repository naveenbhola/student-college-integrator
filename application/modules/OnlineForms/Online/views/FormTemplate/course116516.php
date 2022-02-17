<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];

?>
<style>
.reviewTitleBox{margin-top:30px;}
</style>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Form No. <?=$instituteSpecId?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:90%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/jaipuria/logo2.jpg" alt="JAIPURIA INSTITUTE OF MANAGEMENT" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">JAIPURIA INSTITUTE OF MANAGEMENT</h2>
				<div style="text-align:left;margin-left:20px">
					11/6B Shanti Chambers,  <br>
					Pusa Road, New Delhi 110 005,<br>
					P. +91 11 40088086, F. +91 11 25863255,<br>
					Toll Free: 1800 102 9990,<br>
					Website: www.jaipuria.ac.in<br>
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
		COMMON APPLICATION FORM FOR THE BATCH OF 2013-2015
	</div>
	<div class="clearFix"></div>
    <div id="custom-form-content">
	<ul>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
				   <strong>For admission to AICTE approved Two year full time Post Graduate Diploma in Management Programme <br>(Please indicate your preference for programmes in order of priority 1 being the top priority and 8 being the last priority)</strong>
		    </div>
		</li>
		<li>
			<div class="colums-width">
				<div class="form-details">
					<table border="0" cellpadding="0" cellspacing="10" width="100%">
						<tr>
							<td><label style="width:200px">PGDM</label></td>
							<td>
								<div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;&nbsp;<?=$Jaipuria_PGDM_Lucknow?></td>
                                        </tr>
                                    </table>
                                </div>
							</td>
							<td>Lucknow</td>
							<td>&nbsp;</td>
							<td>
								<div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;&nbsp;<?=$Jaipuria_PGDM_Noida?></td>
                                        </tr>
                                    </table>
                                </div>
							</td>
							<td>Noida</td>
							<td>&nbsp;</td>
							<td>
								<div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;&nbsp;<?=$Jaipuria_PGDM_Jaipur?></td>
                                        </tr>
                                    </table>
                                </div>
							</td>
							<td>Jaipur</td>
							<td>&nbsp;</td>
							<td>
								<div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;&nbsp;<?=$Jaipuria_PGDM_Indore?></td>
                                        </tr>
                                    </table>
                                </div>
							</td>
							<td>Indore</td>
						</tr>
						<tr>
							<td><label>PGDM (Financial Services)</label></td>
							<td>
								<div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;&nbsp;<?=$Jaipuria_PGDM_Financial_Sevices_Lucknow?></td>
                                        </tr>
                                    </table>
                                </div>
							</td>
							<td>Lucknow</td>
							<td colspan="9">&nbsp;</td>
						</tr>
						<tr>
							<td><label>PGDM (Retail Management)</label></td>
							<td>
								<div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;&nbsp;<?=$Jaipuria_PGDM_Retail_Management_Lucknow?></td>
                                        </tr>
                                    </table>
                                </div>
							</td>
							<td>Lucknow</td>
							<td colspan="9">&nbsp;</td>
						</tr>
						<tr>
							<td><label>PGDM (Marketing)</label></td>
							<td colspan="3">&nbsp;</td>
							<td>
								<div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;&nbsp;<?=$Jaipuria_PGDM_Marketing_Noida?></td>
                                        </tr>
                                    </table>
                                </div>
							</td>
							<td>Noida</td>
							<td colspan="6">&nbsp;</td>
						</tr>
						<tr>
							<td><label>PGDM (Service Management)</label></td>
							<td colspan="3">&nbsp;</td>
							<td>
								<div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;&nbsp;<?=$Jaipuria_PGDM_Services_Noida?></td>
                                        </tr>
                                    </table>
                                </div>
							</td>
							<td>Noida</td>
							<td colspan="6">&nbsp;</td>
						</tr>
					</table>
				</div>
			</div>
		</li>
	
		
		
		<li>
			<div class="reviewTitleBox">
                <strong>Details of Aptitude Test<br>(Photocopy of the Admit Card / Test score must be attached to the form)</strong>
			</div>
		</li>
		<?php $testsArray = explode(",",$Jaipuria_testNames); ?>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:150px">
				<label>Percentile Score</label>
				<span></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>CAT:</label>
				<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catPercentileAdditional;?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>MAT:</label>
				<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matPercentileAdditional;?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>CMAT Rank:</label>
				<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatRankAdditional;?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>XAT:</label>
				<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatPercentileAdditional;?><?php endif; ?></span>
			</div>
		</li>

		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:150px">
				<label>Registration No.</label>
				<span></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>CAT:</label>
				<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catRollNumberAdditional;?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>MAT:</label>
				<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matRollNumberAdditional;?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>CMAT:</label>
				<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatRollNumberAdditional;?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>XAT:</label>
				<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatRollNumberAdditional;?><?php endif; ?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:150px">
				<label>Month & Year</label>
				<span></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>CAT:</label>
				<span><?php if(in_array("CAT",$testsArray)): ?><?php echo $catDateOfExaminationAdditional?date("F, Y",strtotime(str_replace("/","-",$catDateOfExaminationAdditional))):'';?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>MAT:</label>
				<span><?php if(in_array("MAT",$testsArray)): ?><?php echo $matDateOfExaminationAdditional?date("F, Y",strtotime(str_replace("/","-",$matDateOfExaminationAdditional))):'';?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>CMAT:</label>
				<span><?php if(in_array("CMAT",$testsArray)): ?><?php echo $cmatDateOfExaminationAdditional?date("F, Y",strtotime(str_replace("/","-",$cmatDateOfExaminationAdditional))):'';?><?php endif; ?></span>
			</div>
			<div class="personalInfoCol" style="width:180px">
				<label>XAT:</label>
				<span><?php if(in_array("XAT",$testsArray)): ?><?php echo $xatDateOfExaminationAdditional?date("F, Y",strtotime(str_replace("/","-",$xatDateOfExaminationAdditional))):'';?><?php endif; ?></span>
			</div>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>1. Personal Data:</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:300px">
				<label>First Name:</label>
				<span><?php echo $firstName;?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Middle Name:</label>
				<span><?php if(empty($middleName)) {echo "&nbsp;&nbsp;&nbsp;&nbsp;-";} else {echo $middleName;}?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Last Name:</label>
				<span><?php echo $lastName;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:300px">
				<label>Date of Birth:</label>
				<span><?php echo str_replace("/","-",$dateOfBirth);?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Gender:</label>
				<span><?php echo $gender;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:600px">
				<label>Father's Name:</label>
                <span><?php echo $fatherName;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:600px">
				<label>Occupation:</label>
                <span><?php echo $fatherOccupation;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:600px">
				<label>Mother's Name:</label>
                <span><?php echo $MotherName;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:600px">
				<label>Occupation:</label>
                <span><?php echo $MotherOccupation;?></span>
			</div>
		</li>
		<li>
			<div class="reviewTitleBox">
				 <strong>2. Address:</strong>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>(a) Mailing Address (Any change in address must be notified to the Institute immediately):</label>
                <span><?php echo $ChouseNumber.' '.$CstreetName.' '.$Carea.' '.$Ccity;?></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:300px">
				<label>State:</label>
                <span><?php echo $Cstate;?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Pin Code:</label>
                <span><?php echo $Cpincode;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>(b) Permanent Address:</label>
                <span><?php echo $houseNumber.' '.$streetName.' '.$area.' '.$city;?></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:300px">
				<label>State:</label>
                <span><?php echo $state;?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Pin Code:</label>
                <span><?php echo $pincode;?></span>
			</div>
		</li>
		<li>
			<div class="reviewTitleBox">
				 <strong>3. Contact Details:</strong>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:300px">
				<label>Mobile Number:</label>
                <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Tel. (R): </label>
				<span><?php echo $Jaipuria_telRes; ?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Tel. (O): </label>
				<span><?php echo $Jaipuria_telOffice; ?></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>E-mail:</label>
                <span><?php echo $email;?></span>
			</div>
		</li>
		<li>
			<div class="reviewTitleBox">
				 <strong>4. Educational Qualifications: <br>(Please attach a photocopy of the Marksheets of all exams passed)</strong>
			</div>
		</li>
		<li>
            	<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
					<tr>
						<td>SL. No.</td>
						<td>Year of Passing</td>
                    	<td>Degree/Certificate</td>
                        <td>School/Institute</td>
                        <td>Board/University</td>
                        <td>Stream</td>
                        <td>% of Marks/Grades</td>
						<td>Class/Division</td>
                    </tr>
					<tr>
						<td>1.</td>
						<td><?php echo $graduationYear;?></td>
                    	<td>Graduation</td>
                        <td><?php echo $graduationSchool;?></td>
                        <td><?php echo $graduationBoard;?></td>
                        <td><?php echo $Jaipuria_gradStream;?></td>
                        <td><?php echo $graduationPercentage;?></td>
						<td><?php echo $Jaipuria_gradDiv;?></td>
                    </tr>
					<tr>
						<td>2.</td>
						<td><?php echo $class12Year;?></td>
                    	<td>XII</td>
                        <td><?php echo $class12School;?></td>
                        <td><?php echo $class12Board;?></td>
                        <td><?php echo $Jaipuria_12Stream;?></td>
                        <td><?php echo $class12Percentage;?></td>
						<td><?php echo $Jaipuria_12Div;?></td>
                    </tr>
					<tr>
						<td>3.</td>
						<td><?php echo $class10Year;?></td>
                    	<td>X</td>
                        <td><?php echo $class10School;?></td>
                        <td><?php echo $class10Board;?></td>
                        <td><?php echo $Jaipuria_10Stream;?></td>
                        <td><?php echo $class10Percentage;?></td>
						<td><?php echo $Jaipuria_10Div;?></td>
                    </tr>
				</table>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>5. Professional Qualification (e.g. C.A./ ICWA/ ICSI etc.)</strong>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
                <span><?php echo $Jaipuria_profQual;?></span>
			</div>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>6. Work Experience</strong>
			</div>
		</li>
		
		<li>
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
				<tr>
					<td colspan="2" style="text-align:center;width:25%">Dates</td>
					<td rowspan="2" style="text-align:center;width:25%">Organization</td>
					<td rowspan="2" style="text-align:center;width:25%">Designation</td>
					<td rowspan="2" style="text-align:center;width:25%">Responsibility</td>
				</tr>
				<tr>
					<td style="text-align:center;width:12.5%">From</td>
					<td style="text-align:center;width:12.5%">To</td>
				</tr>
				<tr>
					<td><?php if(!empty($weFrom)) {echo date('F Y',strtotime(getStandardDate($weFrom)));}?></td>
					<td><?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('F Y',strtotime(getStandardDate($weTill)));}} else {echo "Current";}?></td>
					<td><?=$weCompanyName?></td>
					<td><?=$weDesignation?></td>
					<td><?php echo nl2br(trim($weRoles));?></td>
				</tr>
				<?php 
					for($i=1;$i<=3;$i++):?>
					<?php if(!empty(${'weCompanyName_mul_'.$i})):?>
				<tr>
					<td><?php if(!empty(${'weFrom_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></td>
					<td><?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo "Current";}?></td>
					<td><?php echo ${'weCompanyName_mul_'.$i};?></td>
					<td><?php echo ${'weDesignation_mul_'.$i};?></td>
					<td><?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></td>
				</tr>
				<?php endif;endfor;?>
			</table>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>7. Academic/Professional Accomplishments (Awards/ Medals/ Prizes/ Scholarships/ Certificates/ Honours etc.)</strong>
			</div>
		</li>
		
		<li>
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
				<tr>
					<td style="text-align:center;width:40%">Name of Award</td>
					<td style="text-align:center;width:40%">Awarding Institution</td>
					<td style="text-align:center;width:20%">Year</td>
				</tr>
				<?php 
					for($i=1;$i<=3;$i++):?>
				<tr>
					<td style="text-align:center;width:40%"><?=${'Jaipuria_awardName'.$i}?></td>
					<td style="text-align:center;width:40%"><?=${'Jaipuria_awardInstitute'.$i}?></td>
					<td style="text-align:center;width:20%"><?=${'Jaipuria_awardYear'.$i}?></td>
				</tr>
					<?php endfor;?>
			</table>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>8. Extra-Curricular Activities/Hobbies</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<?=$Jaipuria_activities?>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>9. Why do you want to join Jaipuria?</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<?=$Jaipuria_whyJoin?>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>10. List at least two of your strengths and weakness:</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>(I) Strengths:</label>
                <span><?php echo $Jaipuria_strengths;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>(ii) Weaknesses:</label>
                <span><?php echo $Jaipuria_weakness;?></span>
			</div>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>11. What challenges do you foresee as a manager in the emerging global environment?</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<?=$Jaipuria_challenges?>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>12. Would you require hostel accommodation at Jaipuria? Hostel is compulsory for outstation students.</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<?=$Jaipuria_hostel?>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>13. "How did you find out about Jaipuria Institute of Management". Please specify the source.</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>a) Alumni:</label>
                <span><?php echo $Jaipuria_howAlumni;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>b) Friend/Relative/Parent:</label>
                <span><?php echo $Jaipuria_howFriendRelativeParent;?></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>c) Website:</label>
                <span><?php echo $Jaipuria_howWebsite;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>d) Coaching Institute:</label>
                <span><?php echo $Jaipuria_howCoachingInstitute;?></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>e) Newspaper:</label>
                <span><?php echo $Jaipuria_howNewspaper;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>f) Magazine:</label>
                <span><?php echo $Jaipuria_howMagazine;?></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>g) Other(s), Pls. specify:</label>
                <span><?php echo $Jaipuria_howOthers;?></span>
			</div>
		</li>
		<li>
			<div class="reviewTitleBox">
				 <strong>14. CA & PI CENTRE</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<?=$gdpiLocation?>
		</li>
		<?php if(is_array($paymentDetails)){
			      if($paymentDetails['mode']=='Offline'){
			      $mode = 'Demand Draft';
			      $draftNumber = $paymentDetails['draftNumber'];
				  if($paymentDetails['draftDate'] != "0000-00-00"){
					$draftDate = date("d/m/Y", strtotime($paymentDetails['draftDate']));
				  }
			  }else if($paymentDetails['mode']=='Online'){
			      $mode = 'Cash/Online';
			      $draftNumber = '';
			      $draftDate = date("d/m/Y", strtotime($paymentDetails['date']));
		}}?>
		<li>
			<div class="reviewTitleBox">
				 <strong>15. Mode of Payment</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<?=$mode?>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>16. Details of Payment</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>DD/ Cheque No.:</label>
                <span><?php echo $draftNumber;?></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:400px">
				<label>Date:</label>
                <span><?=$draftDate?></span>
			</div>
			<div class="personalInfoCol" style="width:400px">
				<label>Bank:</label>
                <span><?php echo $paymentDetails['bankName'];?></span>
			</div>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>17. Declaration</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			I certify that the particulars given by me are true to the best of my knowledge and belief. I understand that Jaipuria Institute of
Management will have the right to cancel my admission and ask me to withdraw from the programme if any discrepancies are
found in the information furnished. I will also abide by the general discipline and norms of conduct during the programme.
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>Place:</label>
                <span><?php echo $Ccity;?></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>Date:</label>
                <span><?php echo $paymentDetails['date']?date("d/m/Y", strtotime($paymentDetails['date'])):""?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>Enclosures:</label>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label></label>
                <span>1. Admit Card / Test score of CAT / MAT</span>
				<span style="float:right !important"><?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?><br></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label></label>
                <span>2. Mark Sheets of exams passed</span>
				<span style="float:right !important"><label>Signature of Candidate</label></span>
			</div>
		</li>
	</ul>
	</div>
	</div>
</div>
