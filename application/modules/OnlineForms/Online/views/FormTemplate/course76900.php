<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];

?>
        
    	<!--Personal Info Starts here-->
<div class="personalDetailsSection">
	<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div style="float:right;">
		
			<div class="form-details"><label ><strong>Form Id. : </strong></label><?php if(!empty($instituteSpecId)) {echo 'EIM-SH-'.$instituteSpecId;} else {echo $onlineFormId;} ?></div></div>
         
	<div id="custom-form-header">
		<div class="app-left-box">
			 <div class="clearFix spacer10"></div>
				<div class="inst-name" style="width:85%;margin-left:0px">
				    <img src="/public/images/onlineforms/institutes/EIM/logo2.gif" alt="Eastern Institute of Management" style="float:left" />
					<div style="float:right">
						<h2 style="font-size:20px;">Eastern Institute of Management</h2>
						<div style="text-align:left;font-size:15px;margin-left:2px;">
							IISCO House, 7th Floor,<br>
							50 Jawaharlal Nehru Road, Kolkata 700 071<br>
							Phone: 033 6633 8620/21, 2282 5792<br>
							E-mail:eim@eim.ac.in<br>
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
		APPLICATION FORM FOR ADMISSION IN MBA 2015-2017 BATCH
		</div>
		<div class="spacer15 clearFix"></div>
	<div id="custom-form-content">

		<ul>
			<li>
				<div class="spacer15 clearFix"></div>
				<div class="reviewTitleBox">
				<strong>GD-PI Location:</strong>
				</div>
			</li>
			<li>
				
                            <div class="personalInfoCol"  style="width:300px">
                                <label>GD-PI Location:</label>
                                <span><?php echo 'Kolkata';?></span>
                            </div>
			<li>
				<div class="spacer15 clearFix"></div>
				<div class="reviewTitleBox">
				<strong>Test Scores:</strong>
				</div>
			</li>
			
			<?php 
			$testsArray = explode(",",$testNamesEIM);
			
			if(in_array("CAT",$testsArray)){ ?>
			<li>
			<label>CAT</label>
			</li>
			<li>
				
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Date:</label>
                                <span><?php echo $catDateOfExaminationAdditional;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Score:</label>
                                <span><?php echo $catScoreAdditional;?></span>
                            </div>
			    <div class="personalInfoCol"  style="width:300px">
                                <label>Percentile:</label>
                                <span><?php echo $catPercentileAdditional;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
			</li>
			<?php } if(in_array("MAT",$testsArray)){ ?>
			<li>
			<label>MAT</label>
			</li>
			<li>
				
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Date:</label>
                                <span><?php echo $matDateOfExaminationAdditional;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Score:</label>
                                <span><?php echo $matScoreAdditional;?></span>
                            </div>
			    <div class="personalInfoCol"  style="width:300px">
                                <label>Percentile:</label>
                                <span><?php echo $matPercentileAdditional;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
			</li>
			<?php } if(in_array("XAT",$testsArray)){ ?>
			<li>
			<label>XAT</label>
			</li>
			<li>
				
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Date:</label>
                                <span><?php echo $xatDateOfExaminationAdditional;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Score:</label>
                                <span><?php echo $xatScoreAdditional;?></span>
                            </div>
			    <div class="personalInfoCol"  style="width:300px">
                                <label>Percentile:</label>
                                <span><?php echo $xatPercentileAdditional;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
			</li>
			<?php } if(in_array("CMAT",$testsArray)){ ?>
			<li>
			<label>CMAT</label>
			</li>
			<li>
				
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Date:</label>
                                <span><?php echo $cmatDateOfExaminationAdditional;?></span>
                            </div>
                            <div class="personalInfoCol"  style="width:300px">
                                <label>Score:</label>
                                <span><?php echo $cmatScoreAdditional;?></span>
                            </div>
                             <div class="spacer10 clearFix"></div>
			</li>
			<?php } ?>
			<li>
				<div class="spacer15 clearFix"></div>
					<div class="reviewTitleBox">
					<strong>Applicant's Personal Details:</strong>
				</div>
			</li>
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
                            <div class="personalInfoCol" style="width:400px">
                                <label>Date of birth:</label>
                                <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
			    </div>
			    <div class="personalInfoCol" style="width:400px">
                                <label>Blood Group:</label>
                                <span><?php echo $bloodGroup; ?></span>
                            </div>
			</li>
			<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Address for Correspondence:</strong>
			</div>
		</li>
		<li>
                            <div class="personalInfoCol" style="width:800px">
                                <label>Address:</label>
                                <span><?php echo $ChouseNumber.' '.$CstreetName.' '.$Carea;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
			<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>City:</label>
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
                                <label>Phone (with STD Code):</label>
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
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>Permanent Address:</strong>
			</div>
		</li>
		<li>
                            <div class="personalInfoCol" style="width:800px">
                                <label>Address:</label>
                                <span><?php echo $ChouseNumber.' '.$CstreetName.' '.$Carea;?></span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                        </li>
						
			<li>
                            <div class="personalInfoCol" style="width:300px">
                                <label>City:</label>
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
                                <label>Phone (with STD Code):</label>
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
			 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong>Parents Detail:</strong>
			</div>
			</li>
			<li>
				<div class="personalInfoCol"  style="width:400px">
			                <label>Father's Name:</label>
			                <span><?php echo $fatherName;?></span>
		                </div>
				<div class="personalInfoCol"  style="width:400px">
				        <label>Occupation:</label>
				        <span><?php echo $fatherOccupation;?></span>
				</div>
			</li>
			<li>
				<div class="personalInfoCol"  style="width:400px">
				        <label>Mother's Name:</label>
				        <span><?php echo $MotherName;?></span>
                                </div>
				<div class="personalInfoCol"  style="width:400px">
				        <label>Occupation:</label>
				        <span><?php echo $MotherOccupation;?></span>
                                </div>
		</li>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
			<strong>EDUCATIONAL BACKGROUND (STARTING FROM CLASS X):</strong>
			</div>
		</li>
		<li>
            	<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
		    <tr>
			<td style="width:100px;">Course</td>
			<td style="width:140px;">University/Board</td>
                    	<td style="width:140px;">Institute</td>
                        <td style="width:60px;">Year of Passing</td>
			<td style="width:60px;">Marks(%)</td>
                    </tr>
		    <tr>
			<td>Std. 10</td>
			<td><?php echo $class10Board;?></td>
                    	<td><?php echo $class10School;?></td>
                        <td><?php echo $class10Year;?></td>
                        <td><?php echo $class10Percentage;?></td>
                    </tr>
		    <tr>
			<td>Std. 12</td>
			<td><?php echo $class12Board;?></td>
                    	<td><?php echo $class12School;?></td>
                        <td><?php echo $class12Year;?></td>
                        <td><?php echo $class12Percentage;?></td>

                    </tr>
		    <tr>
			<td><?php echo $graduationExaminationName;?></td>
			<td><?php echo $graduationBoard;?></td>
                        <td><?php echo $graduationSchool;?></td>
			<td><?php echo $graduationYear;?></td>
			<td><?php echo $graduationPercentage; ?></td>
                    </tr>
		    <?php 
		for($j=1;$j<=4;$j++):?>
		<?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
		    <tr>
			<td><?php echo ${'graduationExaminationName_mul_'.$j};?></td>
			<td><?php echo ${'graduationBoard_mul_'.$j};?></td>
                        <td><?php echo ${'graduationSchool_mul_'.$j};?></td>
			<td><?php echo ${'graduationYear_mul_'.$j};?></td>
			<td><?php echo ${'graduationPercentage_mul_'.$j}; ?></td>
                    </tr>
			<?php endif;endfor; ?>
		</table>
		</li>
		
		
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
                                      $workExpTotalInMonthValue=${'workExpTotalInMonth'.$otherSuffix};
                                      	      
				      
				      if($workCompany || $designation){$workExGiven = true;$total++; ?>
		<li>
			 <div class="spacer20 clearFix"></div>
			<div class="reviewTitleBox">
				 <strong> Work Experience: </strong>
			</div>
		</li>
		<li>
			
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
				<tr>
					<td style="width:80px;">Organisation</td>
					<td style="width:80px;">Designation</td>
					<td style="width:60px;">From</td>
					<td style="width:60px;">To</td>
					<td style="width:180px;">Nature of Work</td>
				</tr>
				<tr>
					<td><?=$weCompanyName?></td>
					<td><?=$weDesignation?></td>
					<td><?php if(!empty($weFrom)) {echo date('F Y',strtotime(getStandardDate($weFrom)));}?></td>
					<td><?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('F Y',strtotime(getStandardDate($weTill)));}} else {echo "Current";}?></td>
					<td><?php echo nl2br(trim($weRoles));?></td>
				</tr>
				<?php 
					for($i=1;$i<=3;$i++):?>
					<?php if(!empty(${'weCompanyName_mul_'.$i})):?>
				<tr>
					<td><?php echo ${'weCompanyName_mul_'.$i};?></td>
					<td><?=${'weDesignation_mul_'.$i}?></td>
					<td><?php if(!empty(${'weFrom_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></td>
					<td><?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo "Current";}?></td>
					<td><?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></td>
				</tr>
				
				<?php endif; endfor; ?>
			</table>
		</li>
		<?php } } ?>
		<li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I, &nbsp; <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
		, hereby declare that the above particulars furnished by me are correct to the best of my knowledge, and that I will, on being admitted, abide by the Rules and Code of Conduct of Eastern Institute of Management,Kolkata.<br>
		I hold myself responsible for any and all financial obligations towards the Institute, including payment of Fees.<br>

                
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
	</div>
</div>