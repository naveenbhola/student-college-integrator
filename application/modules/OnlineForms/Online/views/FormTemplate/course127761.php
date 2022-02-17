<style>
@media print
 {
.breakings {page-break-after: left}
.afterBreak{page-break-after:always}

 }
 </style>
    <link href="/public/css/onlineforms/ipe/styles.css" rel="stylesheet" type="text/css"/>
	<div class="formPreviewMain">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
    	<div class="previewHeader">
        	<div class="instLogoBox"><img src="/public/images/onlineforms/institutes/ipe/logo.gif" alt="" /></div>
            <div class="courseNameDetails">
            	<p>Application for Admission to the<br />
				<strong>POST-GRADUATE PROGRAMMES</strong><br />
				<span>(PGDM, PGDM-RM, PGDM-BIF, PGDM-IB, PGDM-BT, Exe. PGDM)</span>
                </p>
            </div>
            
            <!--<div class="srNumBox">
            	<span>Form No:</span>
                <p>Appsoy6716287gyh</p>
            </div> -->
            
        </div>
        
        <div class="previewBody">
        <div class="formInstruction">
        	<h4>Please read the Prospectus and the instructions given below carefully before filling in the details.</h4>
            <ul>
            	<li>Fill in all the details legibly. The form should be complete in all respects.</li>
                <li>Do not enclose any testimonials along with the form.</li>
                <li>All certificates (in original) should be produced at the time of Group Discussion and Interview.</li>
                <li>Incomplete forms will not be considered.</li>
                <li>Preferences for the Programmes should be clearly indicated.</li>
                <li>Candidates downloading application from the website need to send the filled in
application form to the Admissions Officer, IPE, Hyderabad alongwith a DD for
Rs.750/- drawn in favour of Institute of Public Enterprise, payable at Hyderabad.</li>
            </ul>
        </div>
        <?php if($profileImage) { ?>
		<div class="picBox">
			<img width="195" height="192" src="<?php echo $profileImage; ?>" />
		</div>
		<?php } ?>
        
        <div class="spacer10 clearFix"></div>
		<?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
		<strong class="editFormLink applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
		<div class="spacer5 clearFix"></div>
		<?php } ?>
        <h5><strong>1.</strong>Personal Information</h5>
        <div class="clearFix"></div>
        <div class="formRows">
        	<ul>
            	<li>
					<label>Name :</label>
                    <div class="previewFieldBox">
                    	<span>&nbsp;<?php echo $firstName.' '.$middleName.' '.$lastName; ?></span>
                    </div>
                    <div class="clearFix">(as mentioned in 10 <sup>th</sup> Class Certificate)</div>    
					
                 </li>
                 
                 <li>
					<div class="previewFieldBox" style="width:702px;">
                    	<label>Date of Birth :</label>
                    	<div class="previewFieldBox" style="width:300px;">
                        	<span>&nbsp;<?php echo $dateOfBirth; ?></span>
                        </div>
                    </div>
                    
                    <div class="previewFieldBox" style="width:200px;">
                    	<label>Sex : </label>
                        <div class="previewFieldBox" style="width:160px;">
                        	<span>&nbsp;<?php echo $gender; ?></span>
                        </div>
                    </div>
                 </li>
                 
                 <li>
                  	<label>Whether belongs to SC/ST/OBC/Others :</label>
                    <div class="previewFieldBox" style="width:620px">
                    	<span>&nbsp;<?php echo $applicationCategory; ?></span>
                    </div>
                 </li>
                 
                 <li>
					<label>Mailing Address :</label>
                    	<div class="previewFieldBox" style="width:779px">
                    	<span>&nbsp;<?php echo $ChouseNumber;
									if($CstreetName) echo ', '.$CstreetName;
									if($Carea) echo ', '.$Carea;
								?></span>
                    </div>
                  </li>
				 
				 <li>
                    	<div class="previewFieldBox" style="width:905px">
                    	<span>&nbsp;<?php 
									if($Ccity) echo $Ccity;
									if($Cstate) echo ', '.$Cstate;
									if($Ccountry) echo ', '.$Ccountry;
								?></span>
                    </div>
                  </li>
                  
                 <li>
                 	<div class="previewFieldBox" style="width:195px">
                  		<label>Pin :</label>
                        <div class="previewFieldBox" style="width:150px">
                    		<span>&nbsp;<?php echo $Cpincode; ?></span>
                        </div>
                    </div>
                    
                    <div class="previewFieldBox" style="width:290px">
                        <label>Phone :</label>
                            <div class="previewFieldBox" style="width:225px">
                                <span>&nbsp;<?php echo $mobileISDCode.'-'.$mobileNumber; ?></span>
                            </div>
                        </div>
                    
                    <div class="previewFieldBox" style="width:414px">
                        <label>Email :</label>
                            <div class="previewFieldBox" style="width:358px">
                                <span>&nbsp;<?php echo $email; ?></span>
                            </div>
                        </div>
                 </li>
                 
                 <li>
					<label>Permanent Address :</label>
                    <div class="previewFieldBox" style="width:757px">
                        <span>&nbsp;<?php echo $houseNumber;
									if($streetName) echo ', '.$streetName;
									if($area) echo ', '.$area;
								?></span>
                    </div>
                    <div class="spacer10 clearFix"></div>
                    <div class="previewFieldBox" style="width:654px">
                        <span>&nbsp;<?php 
									if($city) echo $city;
									if($state) echo ', '.$state;
									if($country) echo ', '.$country;
									if($pincode) echo ' PIN - '.$pincode;
								?></span>
                    </div>
                    
                    <div class="previewFieldBox" style="width:250px">
                            <label>Phone :</label>
                            <div class="previewFieldBox" style="width:189px">
                        	<span>&nbsp;<?php echo $landlineNumber?$landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber:''; ?></span>
                        </div>
                    </div>
                  </li>
                 
                 <li>
                  	<div class="formColumns2" style="width:450px">
                        <label>Father's Name :</label>
                        <div class="previewFieldBox" style="width:330px">
                            <span>&nbsp;<?php echo $fatherName; ?></span>
                        </div>
                    </div>
                    
                    <div class="formColumns2" style="width:455px">
                        <label>Qualification :</label>
                        <div class="previewFieldBox" style="width:348px">
                            <span>&nbsp;<?php echo $fatherQualificationIPE; ?></span>
                        </div>
                    </div>
                 </li>
                 
                 <li>
					<label>Occupation :</label>
                    <div class="previewFieldBox" style="width:808px">
                    	<span>&nbsp;<?php echo $fatherOccupation; ?></span>
                    </div>
                  </li>
                  
                  <li>
                  	<div class="formColumns2" style="width:450px">
                        <label>Mothers's Name :</label>
                        <div class="previewFieldBox" style="width:318px">
                            <span>&nbsp;<?php echo $MotherName; ?></span>
                        </div>
                    </div>
                    
                    <div class="formColumns2" style="width:455px">
                        <label>Qualification :</label>
                        <div class="previewFieldBox" style="width:347px">
                            <span>&nbsp;<?php echo $motherQualificationIPE; ?></span>
                        </div>
                    </div>
                 </li>
                 
                 <li>
					<label>Occupation :</label>
                    <div class="previewFieldBox" style="width:807px">
                    	<span>&nbsp;<?php echo $MotherOccupation; ?></span>
                    </div>
                  </li>
              </ul>
              <div class="clearFix"></div>
		   </div>
    
    	<h5 style="margin-bottom:5px !important"><strong>2.</strong>Admission Test (CAT/MAT/XAT/ATMA) <span>(Enclose a copy of score card)</span></h5>
        <div class="clearFix"></div>
        <div class="formRows">
        	<ul>
            	<li>
					<label>Candidate's Name (as given in the Test Application) :</label>
                    <div class="previewFieldBox" style="width:100%">
                    	<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                            <tr>
								<?php for($i=0;$i<26;$i++): ?>
									<td><?php echo $candidateName[$i]; ?></td>	
								<?php endfor; ?>
                            </tr>
                            
                            <tr>
								<?php for($i=26;$i<52;$i++): ?>
									<td><?php echo $candidateName[$i]; ?></td>	
								<?php endfor; ?>
                            </tr>
                        </table>
					</div>
                 </li>
                 
                 <li>
                 	<label style="padding-top:5px; width:175px">CAT Registration No. : </label>
                    <div class="previewFieldBox" style="width:685px">
                    	<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                            <tr>
								<?php for($i=0;$i<20;$i++): ?>
								<td><?php echo $catRollNumberIPE[$i]; ?></td>
								<?php endfor; ?>
                            </tr>
                        </table>
					</div>
                 </li>
				 
				  <li>
                 	<label style="padding-top:5px; width:175px">MAT Registration No. : </label>
                    <div class="previewFieldBox" style="width:685px">
                    	<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                            <tr>
								<?php for($i=0;$i<20;$i++): ?>
								<td><?php echo $matRollNumberIPE[$i]; ?></td>
								<?php endfor; ?>
                            </tr>
                        </table>
					</div>
                 </li>
				  
				   <li>
                 	<label style="padding-top:5px; width:175px">XAT Registration No. : </label>
                    <div class="previewFieldBox" style="width:685px">
                    	<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                            <tr>
								<?php for($i=0;$i<20;$i++): ?>
								<td><?php echo $xatRollNumberIPE[$i]; ?></td>
								<?php endfor; ?>
                            </tr>
                        </table>
					</div>
                 </li>
				   
				    <li>
                 	<label style="padding-top:5px; width:175px">ATMA Registration No. : </label>
                    <div class="previewFieldBox" style="width:685px">
                    	<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                            <tr>
								<?php for($i=0;$i<20;$i++): ?>
								<td><?php echo $atmaRollNumberIPE[$i]; ?></td>
								<?php endfor; ?>
                            </tr>
                        </table>
					</div>
                 </li>
              </ul>
         </div>
         <div class="breakHere"></div>
        <?php
		$programPref = array();
		for($i=1;$i<=6;$i++){
			$programPref[${'progPrfe'.$i}] = $i;	
		}
		?>
        <div class="breakings"></div>
        <h5><strong>3.</strong>Programmes (Preferences to be indicated by writing 1,2,3,4,5,6)</h5>
        <div class="clearFix"></div>
        <div class="formRows">
        	<ul>
            	<li>
                	<div class="programPreferenceBox">
                        <label style="padding-top:5px; width:90px">PGDM :</label>
                        <div class="previewFieldBox" style="width:34px">
                            <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $programPref['PGDM']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="programPreferenceBox">
                        <label style="padding-top:5px; width:90px">PGDM-RM :</label>
                        <div class="previewFieldBox" style="width:34px">
                            <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $programPref['PGDM-RM']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="programPreferenceBox">
                        <label style="padding-top:5px; width:90px">PGDM-BIF :</label>
                        <div class="previewFieldBox" style="width:34px">
                            <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $programPref['PGDM-BIF']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="spacer10 clearFix"></div>
                    <div class="programPreferenceBox">
                        <label style="padding-top:5px; width:90px">PGDM-IB :</label>
                        <div class="previewFieldBox" style="width:34px">
                            <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $programPref['PGDM-IB']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="programPreferenceBox">
                        <label style="padding-top:5px; width:90px">PGDM-BT :</label>
                        <div class="previewFieldBox" style="width:34px">
                            <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $programPref['PGDM-BT']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="programPreferenceBox">
                        <label style="padding-top:5px; width:90px">Exe. PGDM :</label>
                        <div class="previewFieldBox" style="width:34px">
                            <table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $programPref['Exe. PGDM']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                 </li>
              </ul>
         </div>
		
        <h5><strong>4.</strong>Qualifications <span>(From 10th onwards)</span></h5>
        <div class="clearFix"></div>
        <div class="formRows">
        	<ul>
            	<li>
                	<table width="100%" cellpadding="7" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    	<tr>
                        	<th align="center" width="200">Name of the <br />Course</th>
                            <th align="center" width="200">Board/<br />University</th>
                            <th align="center" width="130">Medium of <br />Instruction</th>
                            <th align="center" width="80">Year of <br />Passing</th>
                            <th align="center" width="50">Class/<br />Div.</th>
                            <th align="center" width="50">% of <br />Marks</th>
                        </tr>
                        <tr>
                        	<td>&nbsp;<?php echo $class10ExaminationName; ?></td>
                            <td>&nbsp;<?php echo $class10Board; ?></td>
                            <td>&nbsp;<?php echo $class10Medium; ?></td>
                            <td>&nbsp;<?php echo $class10Year; ?></td>
                            <td>&nbsp;<?php echo $class10Division; ?></td>
                            <td>&nbsp;<?php echo $class10Percentage; ?></td>
                        </tr>
                        <tr>
                        	<td>&nbsp;<?php echo $class12ExaminationName; ?></td>
                            <td>&nbsp;<?php echo $class12Board; ?></td>
                            <td>&nbsp;<?php echo $class12Medium; ?></td>
                            <td>&nbsp;<?php echo $class12Year; ?></td>
                            <td>&nbsp;<?php echo $class12Division; ?></td>
                            <td>&nbsp;<?php echo $class12Percentage; ?></td>
                        </tr>
                        <tr>
                        	<td>&nbsp;<?php echo $graduationExaminationName; ?></td>
                            <td>&nbsp;<?php echo $graduationBoard; ?></td>
                            <td>&nbsp;<?php echo $graduationMedium; ?></td>
                            <td>&nbsp;<?php echo $graduationYear; ?></td>
                            <td>&nbsp;<?php echo $graduationDivision; ?></td>
                            <td>&nbsp;<?php echo $graduationPercentage; ?></td>
                        </tr>
						
						<?php
						for($i=1;$i<=4;$i++) {
							if(${'graduationExaminationName_mul_'.$i}) {
						?>
                        <tr>
                        	<td><?php echo ${'graduationExaminationName_mul_'.$i}; ?></td>
                            <td><?php echo ${'graduationBoard_mul_'.$i}; ?></td>
                            <td><?php echo ${'otherCoursesMedium_mul_'.$i}; ?></td>
                            <td><?php echo ${'graduationYear_mul_'.$i}; ?></td>
                            <td><?php echo ${'otherCoursesDivision_mul_'.$i}; ?></td>
                            <td><?php echo ${'graduationPercentage_mul_'.$i}; ?></td>
                        </tr>
						<?php
							}
						}
						?>
                    </table>
                </li>
                </ul>
                </div>
                
		<h5><strong>5.</strong>Work Experience</h5>
        <div class="clearFix"></div>
        <div class="formRows">
        	<ul>
            	<li>
					<label>Name of the Organisation :</label>
                    <div class="previewFieldBox" style="width:715px">
                    	<span>&nbsp;<?php echo $weCompanyName; ?></span>
                    </div>
				</li>
                
                <li>
                  	<div class="formColumns2" style="width:500px">
                        <label>Designation :</label>
                        <div class="previewFieldBox" style="width:380px">
                            <span>&nbsp;<?php echo $weDesignation; ?></span>
                        </div>
                    </div>
                    
                    <div class="formColumns2" style="width:405px">
                        <label>No. of Years of Service :</label>
                        <div class="previewFieldBox" style="width:233px">
                            <span>&nbsp;<?php
							if($weFrom) {
								$dateTo = ($weTimePeriod || !$weTill)?date('Y-m-d'):getStandardDate($weTill);
								$timeDiff = getTimeDifference(getStandardDate($weFrom),$dateTo);
								
								$numOfMonths = intval($timeDiff['months']);
								if($numOfMonths > 0){
									echo $timeDiff['years'].' ('.$numOfMonths.' Month'.($numOfMonths>1?'s':'').')';
								}
								else {
									echo '0';
								}
							}
							?>
							</span>
                        </div>
                    </div>
                 </li>
                 
                 <li>
					<label>Nature of Work :</label>
                    <div class="previewFieldBox" style="width:782px">
                    	<span>&nbsp;<?php echo $weRoles; ?></span>
                    </div>
				</li>
              </ul>
              <div class="clearFix"></div>
		   </div>
		<?php
		for($i=1;$i<=2;$i++) {
			if(${'weCompanyName_mul_'.$i}) {
		?>
		<div class="formRows">
        	<ul>
            	<li>
					<label>Name of the Organisation :</label>
                    <div class="previewFieldBox" style="width:715px">
                    	<span>&nbsp;<?php echo ${'weCompanyName_mul_'.$i}; ?></span>
                    </div>
				</li>
                
                <li>
                  	<div class="formColumns2" style="width:500px">
                        <label>Designation :</label>
                        <div class="previewFieldBox" style="width:380px">
                            <span>&nbsp;<?php echo ${'weDesignation_mul_'.$i}; ?></span>
                        </div>
                    </div>
                    
                    <div class="formColumns2" style="width:405px">
                        <label>No. of Years of Service :</label>
                        <div class="previewFieldBox" style="width:233px">
                            <span>&nbsp;<?php
							if(${'weFrom_mul_'.$i}) {
								$dateTo = (${'weTimePeriod_mul_'.$i} || !${'weTill_mul_'.$i})?date('Y-m-d'):getStandardDate(${'weTill_mul_'.$i});
								$timeDiff = getTimeDifference(getStandardDate(${'weFrom_mul_'.$i}),$dateTo);
								
								$numOfMonths = intval($timeDiff['months']);
								if($numOfMonths > 0){
									echo $timeDiff['years'].' ('.$numOfMonths.' Month'.($numOfMonths>1?'s':'').')';
								}
								else {
									echo '0';
								}
							}
							?>
							</span>
                        </div>
                    </div>
                 </li>
                 
                 <li>
					<label>Nature of Work :</label>
                    <div class="previewFieldBox" style="width:782px">
                    	<span>&nbsp;<?php echo ${'weRoles_mul_'.$i}; ?></span>
                    </div>
				</li>
              </ul>
              <div class="clearFix"></div>
		   </div>
		<?php
			}
		}
		?>
           
        <h5><strong>6.</strong>HOW DID YOU COME TO KNOW ABOUT IPE? <span>(for statistical purposes only)</span></h5>
        <div class="clearFix"></div>
        <div class="formRows">
        	<ul>
            	<li>
					<label>Newspaper Advertisement :</label>
                    <div class="previewFieldBox" style="width:500px">
                    	<span>&nbsp;<?php echo $heardInNewspaper; ?></span>
                    </div>
                    <div class="formColumns2">(name of the paper)</div>
				</li>
                
                <li>
					<label>Business magazines :</label>
                    <div class="previewFieldBox" style="width:544px">
                    	<span>&nbsp;<?php echo $heardInBusinessMagazine; ?></span>
                    </div>
                    <div class="formColumns2">(name of the magazine)</div>
				</li>
                
                <li>
					<label>Coaching Centres :</label>
                    <div class="previewFieldBox" style="width:559px">
                    	<span>&nbsp;<?php echo $heardInCoachingCentre; ?></span>
                    </div>
                    <div class="formColumns2">(name of the centre)</div>
				</li>
                
                <li>
					<label>IPE Alumini :</label>
                    <div class="previewFieldBox" style="width:599px">
                    	<span>&nbsp;<?php echo $heardFromIPEAlmuni; ?></span>
                    </div>
                    <div class="formColumns2">(name of the alumini)</div>
				</li>
                
                <li>
					<label>Friends :</label>
                    <div class="previewFieldBox" style="width:628px">
                    	<span>&nbsp;<?php echo $heardFromFriends; ?></span>
                    </div>
				</li>
                
                <li>
					<label>Any other, specify :</label>
                    <div class="previewFieldBox" style="width:770px">
                    	<span>&nbsp;<?php echo $heardFromAnyOther; ?></span>
                    </div>
				</li>
                
              </ul>
              <div class="clearFix"></div>
		   </div>
       	
        <h5><strong>7.</strong>REGISTRATION FEE DETAILS</span></h5>
        <div class="clearFix"></div>
        <div class="formRows">
        	<ul>
				<?php
				if(isset($paymentDetails['mode']) && $paymentDetails['mode'] == 'Online' && $paymentDetails['status'] == 'Success') {
				?>
				<li>
                	<div class="formColumns2" style="width:222px">
                        <label>Order Value :</label>
                        <div class="previewFieldBox" style="width:120px">
                            <span>&nbsp;INR <?php echo $paymentDetails['amount']; ?></span>
                        </div>
                    </div>
                    
                    <div class="formColumns2" style="width:265px">
                        <label>Payment Mode :</label>
                        <div class="previewFieldBox" style="width:140px">
                            <span>&nbsp;Online Payment</span>
                        </div>
                    </div>
                    
                    <div class="formColumns2" style="width:410px">
                        <label>Transaction ID  :</label>
                        <div class="previewFieldBox" style="width:290px">
                            <span>&nbsp;<?php echo $paymentDetails['orderId']; ?></span>
                        </div>
                    </div>
					<div class="spacer10 clearFix"></div>
					<div class="formColumns2" style="width:170px">
                        <label>Date  :</label>
                        <div class="previewFieldBox" style="width:100px">
                            <span>&nbsp;<?php echo date("d/m/Y",strtotime($paymentDetails['date'])); ?></span>
                        </div>
                    </div>
                </li>
				<?php
				}
				else {
				?>
            	<li>
                	<div class="formColumns2" style="width:360px">
                        <label>DD No. :</label>
                        <div class="previewFieldBox" style="width:285px">
                            <span>&nbsp;<?php echo $paymentDetails['draftNumber']; ?></span>
                        </div>
                    </div>
                    
                    <div class="formColumns2" style="width:225px">
                        <label>Dated :</label>
                        <div class="previewFieldBox" style="width:160px">
                            <span>&nbsp;<?php if($paymentDetails['draftDate'] && $paymentDetails['draftDate']!= '0000-00-00') echo date('d/m/Y',strtotime($paymentDetails['draftDate'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="formColumns2" style="width:317px">
                        <label>Drawn on  :</label>
                        <div class="previewFieldBox" style="width:233px">
                            <span>&nbsp;<?php echo $paymentDetails['bankName']; ?></span>
                        </div>
                    </div>
                </li>
				<?php
				}
				?>
             </ul>
         </div>
         
         <div class="clearFix"></div>
         <div class="formRows">
         	<ul>
            	<li>
                	<span>I,</span> <span style="border-bottom:1px solid #000000; padding:0 50px">&nbsp;<?php echo $firstName.' '.$middleName.' '.$lastName; ?></span> <span>the undersigned, affirm that the information    furnished above is correct to the best of my knowledge and belief, and that I will accept as final and binding, the decision of Institute of Public Enterprise regarding my admission to the Post Graduate Programme. If any information provided by me is found to be false or incorrect at a later date, I will be held solely responsible for the legal and other Consequences.</span>
                    
                </li>
            </ul>
         </div>
         
         <div class="clearFix spacer10"></div>
         <div class="formRows">
         	<div class="flRt">
            	<p><?php echo $firstName.' '.$middleName.' '.$lastName; ?></p>
            	<p style="text-align:right">Signature of the Candidate</p>
            </div>
            <div class="clearFix spacer10"></div>
            <div class="flLt">
            	<label style="width:60px; float:left">Date :</label>
                <div class="previewFieldBox" style="width:200px">
                    <span>&nbsp;<?php
				if($paymentDetails['mode'] == 'Offline' || ($paymentDetails['mode'] == 'Online' && $paymentDetails['status'] == 'Success')) {
					echo date("d/m/Y",strtotime($paymentDetails['date']));
				}
				?></span>
                </div>
            </div>
            
            <div class="clearFix spacer5"></div>
            <div class="flLt" style="padding-top:20px">
            	<label style="width:60px; float:left">Place :</label>
                <div class="previewFieldBox" style="width:200px;">
                    <span>&nbsp;<?php echo $Ccity; ?></span>
                </div>
            </div>
            
            <div class="flRt">
            	<p><?php echo $fatherName; ?></p>
                <p style="text-align:right">Signature of the Parent/Guardian</p>
            </div>
         </div>
    </div>
    </div>
    <div class="afterBreak"></div>
