<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];

?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:100%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/isbm/logo2.gif" alt="International School of Business Media" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">International School of Business & Media(ISB&M)</h2>
				<div style="text-align:left;margin-left:20px">
					S. No. 44/1, 44 1/2, Nande Village, Pashan-Sus Road <br>
					Taluka Mulshi, Pune 412115<br>
					Tel: +91 7757029571,<br>
					E-Mail: admissions@isbm.ac.in<br>
					Website: www.isbm.ac.in<br>
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
		APPLICATION FORM FOR ADMISSION IN PGDM/PGDBM 2015-2017 BATCH
	</div>
	<div class="spacer15 clearFix"></div>
    <div id="custom-form-content">
	<ul>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
				   <strong>ISB&M Programme is Dual Specialization Programme. Choose your primary career interest:</strong>
		    </div>
		</li>
		<li>
			<div>
				<label>Specialization: </label>
				<div class="form-details"><?php echo $ISBM_specialization; ?></div>
			</div>
		</li>
	
		<!--<li>
			<div class="colums-width" style="width:100%">
				<label>Campus(es)/Courses(es) : </label>
				<div class="form-details"><?php echo $ISBM_campus; ?></div>
			</div>
		</li>-->
		<?php
		//	if($ISBM_program || $ISBM_campusType == "" ){
		//?>
		<!--//<li>
		//	<div class="colums-width" style="width:100%">
		//		<label>Programme: </label>
		//		<div class="form-details"><?php echo $ISBM_program; ?></div>
		//	</div>
		//</li>-->
		<?php
		//	}
		//?>
		
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
                <strong>SECTION A:</strong>
			</div>
		</li>
		
		<li>
			<div class="colums-width" style="width:100%">
				<label>Choice of Centre for Group Discussion (GD) and Personal Interview (PI): </label>
				<div class="form-details"><?php echo $gdpiLocation; ?></div>
			</div>
		</li>
		
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="colums-width" style="width:100%">
				<label>Identification Number and Score of:</label>
			</div>
		</li>
		<li>
			<?php 
			$testsArray = explode(",",$ISBM_testNames);
			?>
			<div class="colums-width" style="width:100%">
				<table border="1" width="100%" cellpadding="5" cellspacing="0">
					<tr>
						<td width="150">&nbsp;</td>
						<td align="center">CAT</td>
						<td align="center">MAT</td>
						<td align="center">XAT</td>
						<td align="center">ATMA</td>
						<td align="center">CMAT</td>
						
					</tr>
					<tr>
						<td>Identification Number</td>
						<td align="center"><?php if(in_array("CAT",$testsArray)) {echo $catRollNumberAdditional;} ?></td>
						<td align="center"><?php if(in_array("MAT",$testsArray)) {echo $matRollNumberAdditional;} ?></td>
						<td align="center"><?php if(in_array("XAT",$testsArray)) {echo $xatRollNumberAdditional; }?></td>
						<td align="center"><?php if(in_array("ATMA",$testsArray)) {echo $atmaRollNumberAdditional; }?></td>
						<td align="center"><?php if(in_array("CMAT",$testsArray)) {echo $cmatRollNumberAdditional; }?></td>
						
					</tr>
					<tr>
						<td>Score in Percentile</td>
						<td align="center"><?php if(in_array("CAT",$testsArray)) {echo $catPercentileAdditional;} ?></td>
						<td align="center"><?php if(in_array("MAT",$testsArray)) {echo $matPercentileAdditional;}?></td>
						<td align="center"><?php if(in_array("XAT",$testsArray)) {echo $xatPercentileAdditional;} ?></td>
						<td align="center"><?php if(in_array("ATMA",$testsArray)) {echo $atmaPercentileAdditional;} ?></td>
						<td align="center"><?php if(in_array("CMAT",$testsArray)) {echo $cmatRankAdditional;} ?></td>
						
					</tr>
				</table>
			</div>
		</li>
		
		<li>
			<hr/>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
                <strong>SECTION B:</strong>
			</div>
		</li>
		
		
		
	</ul>
	</div>
        <div class="spacer15 clearFix"></div>
            <div class="reviewTitleBox">
                <strong>Personal Information:</strong>
            </div>
            <div class="reviewLeftCol"  style="width:900px">
                <div>
                    <ul>
                        <li>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>First Name:</label>
                                <span><?php echo $firstName.' '.$middleName;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:400px">
                                <label>Surname:</label>
                                <span><?php echo $lastName;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
                        </li>
						<li>
                            <div class="personalInfoCol"  style="width:700px">
                                <label>Father's/Guardian's Name:</label>
                                <span><?php echo $fatherName;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						<li>
                            <div class="personalInfoCol"  style="width:700px">
                                <label>Mother's Name:</label>
                                <span><?php echo $MotherName;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						<li>
                            <div class="personalInfoCol" style="width:700px">
                                <label>Professional Background of Parent/Guardian:</label>
                                <span><?php echo $fatherOccupation;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
			<li>
                            <div class="personalInfoCol" style="width:200px">
                                <label>Date of birth:</label>
                                <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
                            </div>
			    <div class="personalInfoCol" style="width:200px">
                                <label>Height:</label>
                                <span><?php echo $ISBM_height; ?></span>
                            </div>
			    <div class="personalInfoCol" style="width:200px">
                                <label>Weight:</label>
                                <span><?php echo $ISBM_weight; ?></span>
                            </div>
			    <div class="personalInfoCol" style="width:200px">
                                <label>Blood Group:</label>
                                <span><?php echo $ISBM_bloodGroup; ?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
			<li>
                            <div class="personalInfoCol" style="width:400px">
                                <label>Any Major Ailment or Sickness:</label>
                                <span><?php echo $ISBM_ailment;?></span>
                            </div>
			    <div class="personalInfoCol" style="width:400px">
                                <label>Nationality:</label>
                                <span><?php echo $nationality;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
			<li>
			    <div class="personalInfoCol" style="width:400px;">
				<label>Person with Disability:</label>
				<span><?php echo $ISBM_Disability;?></span>
			    </div>
			    <?php if($ISBM_Disability == 'Yes') {?>
			    <div class="personalInfoCol" style="width:400px;">
				<label>If yes, mention:</label>
				<span><?php echo $ISBM_DisabilityDetail;?></label>
			    </div>
			    <?php } ?>
			    <div class="spacer10 clearFix"></div>
			</li>
			<li>
                            <div class="personalInfoCol" style="width:400px">
                                <label>Are you a member of SC/ST/OBC:</label>
				<?php if($applicationCategory == 'SC' || $applicationCategory == 'ST' || $applicationCategory == 'OBC') { ?>
					<span><?php echo 'Yes';?></span>
				<?php } else { ?>
					<span><?php echo 'No';?></span>
				<?php } ?>
                            </div>
			    <?php if($applicationCategory == 'SC' || $applicationCategory == 'ST' || $applicationCategory == 'OBC') { ?>
			    <div class="personalInfoCol" style="width:400px">
                                <label>If yes, mention:</label>
                                <span><?php echo $applicationCategory;?></span>
                            </div>
			    <?php } ?>
                            <div class="spacer10 clearFix"></div>
                        </li>
                    </ul>
                </div>
                
                <div class="spacer20 clearFix"></div>
                   
		</div>
			
			<div class="reviewTitleBox">
                <strong>Contact Information:</strong>
            </div>
            <div class="reviewLeftCol"  style="width:900px">
                <div>
                    <ul>
						<li>
                            <div class="personalInfoCol" style="width:800px">
                                <label>Present Address:</label>
                                <span><?php echo $ChouseNumber.' '.$CstreetName.' '.$Carea;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
						<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>City/Village:</label>
                                <span><?php echo $Ccity;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>State:</label>
                                <span><?php echo $Cstate;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>Pin Code:</label>
                                <span><?php echo $Cpincode;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
						<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>Tel. (with STD Code):</label>
                                <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>Mobile Number:</label>
                                <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>E-mail:</label>
                                <span><?php echo $email;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
						<li>
                            <div class="personalInfoCol" style="width:800px">
                                <label>Permanent Address:</label>
                                <span><?php echo $houseNumber.' '.$streetName.' '.$area;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
						
						<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>City/Village:</label>
                                <span><?php echo $city;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>State:</label>
                                <span><?php echo $state;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>Pin Code:</label>
                                <span><?php echo $pincode;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
						<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>Tel. (with STD Code):</label>
                                <span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>Mobile Number:</label>
                                <span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
                            </div>
							<div class="personalInfoCol" style="width:300px">
                                <label>E-mail:</label>
                                <span><?php echo $email;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
			</ul>
			</div>
				</div>
				 <div class="spacer10 clearFix"></div>
		<ul style="width:947px">
		<li>
			 <div class="spacer10 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong>SECTION C: Education Background </strong><span> (Attach photocopies of the certificates)</span>
			</div>
		</li>
		<li>
            	<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
		    <tr>
			<td style="width:100px;">Course</td>
			<td style="width:140px;">University/Board</td>
                    	<td style="width:140px;">Institute</td>
                        <td style="width:60px;">Year of Passing</td>
			<td style="width:140px;">Subjects</td>
                        <td style="width:60px;">Marks*(%)</td>
			<td style="width:60px;">Class/Grade</td>
                    </tr>
		    <tr>
			<td>Std. 10</td>
			<td><?php echo $class10Board;?></td>
                    	<td><?php echo $class10School;?></td>
                        <td><?php echo $class10Year;?></td>
			<td><?php echo $ISBM_subject10;?></td>
                        <td><?php echo $class10Percentage;?></td>
			<td><?php echo $ISBM_rank10; ?></td>
                    </tr>
		    <tr>
			<td>Std. 12</td>
			<td><?php echo $class12Board;?></td>
                    	<td><?php echo $class12School;?></td>
                        <td><?php echo $class12Year;?></td>
			<td><?php echo $ISBM_subject12;?></td>
                        <td><?php echo $class12Percentage;?></td>
			<td><?php echo $ISBM_rank12; ?></td>
                    </tr>
		    <tr>
			<td><?php echo $graduationExaminationName?$graduationExaminationName:"Graduation";?>**</td>
			<td><?php echo $graduationBoard;?></td>
                        <td><?php echo $graduationSchool;?></td>
                        <td><?php echo $graduationYear;?></td>
			<td><?php echo $ISBM_subjectGrad;?></td>
                        <td><?php echo $graduationPercentage;?></td>
			<td><?php echo $ISBM_rankGrad; ?></td>
                    </tr>
		<?php 
		for($j=1;$j<=4;$j++):?>
		<?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
		    <tr>
			<td><?php echo ${'graduationExaminationName_mul_'.$j};?></td>
			<td><?php echo ${'graduationBoard_mul_'.$j};?></td>
                        <td><?php echo ${'graduationSchool_mul_'.$j};?></td>
                        <td><?php echo ${'graduationYear_mul_'.$j};?></td>
			<td><?php echo ${'otherCourseSubjects_mul_'.$j};?></td>
                        <td><?php echo ${'graduationPercentage_mul_'.$j};?></td>
			<td><?php echo ${'otherCourseGradePg_mul_'.$j}; ?></td>
                    </tr>
			<?php endif;endfor; ?>
		</table>
		</li>
		<li><div class="spacer20 clearFix"></div>
			*For Graduation, please give aggregate of mark of all years. If appearing for final year, mention aggregate of 1st & 2nd.</li>
		<li>** Mention BA, B.Sc., B.Com., BBA, B.Tech., B.E. (Mechanical, Electrical)etc.
</li>
		
		<li>
			 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong>SECTION D: Work Experience: </strong><span>(Will be required to furnish proof of work experience certificate)</span>
			</div>
		</li>
		
		<li>
			
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
				<tr>
					<td style="width:20px;">Sr. No</td>
					<td style="width:80px;">Company</td>
					<td style="width:60px;">From</td>
					<td style="width:60px;">To</td>
					<td style="width:180px;">Position held/KRAs</td>
					<td style="width:80px;">Location</td>
				</tr>
				<tr>
					<td><?=$weCompanyName?'1.':''?></td>
					<td><?=$weCompanyName?></td>
					<td><?php if(!empty($weFrom)) {echo date('F Y',strtotime(getStandardDate($weFrom)));}?></td>
					<td><?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('F Y',strtotime(getStandardDate($weTill)));}} else {echo "Current";}?></td>
					<td>Position held: <?=$weDesignation?><br>KRAs: <?php echo nl2br(trim($weRoles));?></td>
					<td><?php echo trim($weLocation);?></td>
				</tr>
				<?php 
					for($i=1;$i<=3;$i++):?>
					<?php if(!empty(${'weCompanyName_mul_'.$i})):?>
				<tr>
					<td><?=($i+1)?></td>
					<td><?php echo ${'weCompanyName_mul_'.$i};?></td>
					<td><?php if(!empty(${'weFrom_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></td>
					<td><?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo "Current";}?></td>
					<td>Position held: <?=${'weDesignation_mul_'.$i}?><br>KRAs: <?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></td>
					<td><?php echo trim(${'weLocation_mul_'.$i});?></td>
				</tr>
				<?php endif;endfor;?>
			</table>
		</li>
		
		<li>
			<div class="spacer20 clearFix"></div>
			<div class="personalInfoCol" style="width:900px;">
			<label>What are your career growth expectations?:</label>
			<span><?php echo $ISBM_careerExpect;?></span>
			</div>
			
		</li>
		
		
		<li>
			 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong>SECTION E:</strong>
			</div>
		</li>
		<li>
		 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong>About ISB&M:</strong>
			</div>
		</li>
		<li>
			<div class="personalInfoCol" style="width:900px">
				<label>How did you hear about ISB&M?:</label>
				<span><?php echo $ISBM_howHear;?></span>
			</div>
		</li>
		
		<li>
			<div class="spacer20 clearFix"></div>
			<div class="personalInfoCol" style="width:900px">
				<label>Why do you think ISB&M is the best option?:</label>
				<span><?php echo $ISBM_BestOption;?></span>
			</div>
		</li>
		
		<li>
		 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong>Career Goals:</strong>
			</div>
		</li>
		
		<li>
			<div class="spacer20 clearFix"></div>
			<div class="personalInfoCol" style="width:900px">
				<label>What are your long-term objectives in life?:</label>
				<span><?php echo $ISBM_longTermGoal;?></span>
			</div>
		</li>
		
		<li>
			<div class="spacer20 clearFix"></div>
			<div class="personalInfoCol" style="width:900px">
				<label>Where do you see yourself five years from now?:</label>
				<span><?php echo $ISBM_fiveYearVision;?></span>
			</div>
		</li>
		
		<li
			<div class="spacer20 clearFix"></div>
			<div class="personalInfoCol" style="width:900px">
				<label>Mention your strengths and weaknesses:</label>
				<span><?php echo $ISBM_StrenghtWeakness;?></span>
			</div>
		</li>
		
		<li>
			<div class="spacer20 clearFix"></div>
			<div class="personalInfoCol" style="width:900px">
				<label>What qualities do you have which will make you a committed and responsible professional in corporate field?:</label>
				<span><?php echo $ISBM_qualities;?></span>
			</div>
		</li>
		
		<!--<li>
		 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong>Payment Details:</strong>
			</div>
		</li>-->
		
		<?php if(is_array($paymentDetails)){
			      if($paymentDetails['mode']=='Offline'){
			      $mode = 'Demand Draft';
			      $draftNumber = $paymentDetails['draftNumber'];
				  if($paymentDetails['draftDate'] != "0000-00-00"){
					$draftDate = date("d/m/Y", strtotime($paymentDetails['draftDate']));
				  }
			  }else if($paymentDetails['mode']=='Online'){
			      $mode = 'Online';
			      $draftNumber = '';
			      $draftDate = date("d/m/Y", strtotime($paymentDetails['date']));
		}}?>
		
		<!--<li>
			<?php
			if($mode == "Online"){
			?>
			<div class="personalInfoCol" style="width:900px">
				<label>Paid Online</label>
			</div>
			<?php
			}else{
			?>
			<div class="personalInfoCol" style="width:300px">
				<label>Demand Draft No.:</label>
				<span><?php echo $draftNumber;?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Drawn on (Bank):</label>
				<span><?php echo $paymentDetails['bankName'];?></span>
			</div>
			<div class="personalInfoCol" style="width:300px">
				<label>Demand Draft Date:</label>
				<span><?php echo $draftDate;?></span>
			</div>
			<?php
			}
			?>
		</li>-->
		
		</ul>
		 <div class="spacer20 clearFix"></div>
				<div class="reviewTitleBox" style="width:924px">
                <strong>Undertaking:</strong>
				</div>
				<div class="reviewLeftCol"  style="width:900px">
					<div>
						<ul>
							<li>
                            <div class="personalInfoCol" style="width:900px">
							<ul>
								<li style="margin-bottom:5px;">1) I hereby submit to the jurisdiction of the Pune court in the event of any dispute.I have carefully noted the rules and process of admission as given in the prospectus which I am required to follow and shall in matters of interpretation; accept the decision given by the Director in this respect as final and binding.</li>
								<li style="margin-bottom:5px;">2) I shall conduct myself as per the rules and norms of ISB&M, failing which I shall not approach the Director for any concession in this regard and shall be liable to be debarred from the Institute. Manual of Policy will be provided at the time of admission.</li>
								<li style="margin-bottom:5px;">3) I have also read, understood and accepted the code and conduct of the Institute and shall take note of all communication put from time to time.</li>
							</ul>
							</div>
                            <div class="spacer10 clearFix"></div>
							</li>
							
							<li>
								<div class="personalInfoCol" style="width:500px">
									<label style="width:auto; padding-top:0">Date :</label>
                                    <span>&nbsp;
										<?php
											  if( isset($paymentDetails['date'])  ){
												  echo date("d/m/Y", strtotime($paymentDetails['date']));
											}
										?>
									</span>
								</div>
								<div class="personalInfoCol" style="width:400px">
									<label style="width:auto; padding-top:0">Signature of the Applicant :</label>
									<span><?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?>
									</span>
								</div>
								<div class="spacer10 clearFix"></div>
							</li>
						
						</ul>
					</div>
				</div>
			</div>
		
	

