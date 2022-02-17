<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:88%">
                <img src="/public/images/onlineforms/institutes/aim/logo4.gif" alt="" />
				<div style="float:right">
				<h2 style="font-size:20px;">ASIA-PACIFIC INSTITUTE OF MANAGEMENT</h2>
				<div style="text-align:left;margin-left:20px">
					3 & 4 Institutional Area, Jasola, New Delhi - 110019<br/>					
					Phone: 011 - 42094800, 816, 883, 886
				</div>
				</div>
		
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: PGDM <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2015";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2017";}?></div>  
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
    </div>
    <div class="spacer15 clearFix"></div>
    <div id="custom-form-content">
    	<ul>
        <?php if( $instituteSpecId!=''){?>
        <li>
        <label>Form Id: </label>
                        <div class="form-details"><?php echo 'S'.$instituteSpecId; ?></div>
        </li>
        <?php } ?>
		<li>
            	<h3 class="form-title">AICTE Approved Programmes</h3>
		       	<div class="spacer10 clearFix"></div>
        	<p>(Please indicate your choice of Programmes in order of preference)</p>
        	<div class="spacer10 clearFix"></div>
            <p>The Admissions committee reserves the right to offer any other programme to the candidate, in case of non availability
of seats in the preferred programme. In this regard, the decision of the Admissions Committee will be final and binding.</p>
			<div class="spacer10 clearFix"></div>
			<h4>2 Year Full-Time</h4>
                <ul>
                    <li>
			<div class="preff-cont">- Post Graduate Diploma in Management: PGDM <span class="option-box"><?php echo $prefPGDM; ?></span></div>
                    </li>
                    <li>
			<div class="preff-cont">- Post Graduate Diploma in Management- Marketing: PGDM - Mkt. <span class="option-box"><?php echo $prefPGDMMkt; ?></span></div>
                    </li>
                    <li>
			<div class="preff-cont">- Post Graduate Diploma in Management- International Business: PGDM - IB <span class="option-box"><?php echo $prefPGDMIB; ?></span></div>
                    </li>

                    <li>
                        <div class="preff-cont">- Post Graduate Diploma in Management- Banking &amp; Financial Services: PGDM - BFS <span class="option-box"><?php echo $prefPGDMBFS; ?></span></div>
                    </li>
                </ul>
                <div class="spacer5 clearFix"></div>
                <h4>3 Year Part-Time</h4>
                <ul>
		    <li>
			<div class="preff-cont">- Executive Post Graduate Diploma in Management: EX PGDM <span class="option-box"><?php echo $prefPGDMEx; ?></span></div>
                    </li>
                    
                     <li>
			<div class="preff-cont">- Executive Post Graduate Diploma in Management - Marketing: EX PGDM - Mkt.<span class="option-box"><?php echo $prefPGDMExMkt; ?></span></div>
                    </li>
                    
                </ul>
	</li>

        	<li>
            	<h3 class="form-title"> Personal Details:</h3>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
		<div class="colums-width">
		    	<label>Name of Candidate: </label>
		        <div class="form-details"><?php echo $salutationAIM;?>&nbsp;<?php echo $userName; ?></div>
		</div>
            	<div class="colums-width">
                    <label>Date Of Birth: </label>
                    <div class="form-details"><?=$dateOfBirth;?></div>
                </div>
            </li>
            
            <li>
		<div class="colums-width">
                    <label>Blood Group: </label>
                    <div class="form-details"><?=$bloodGroupAIM;?></div>
                </div>
		<div class="colums-width">
                    <label>Religion: </label>
                    <div class="form-details"><?=$religion;?></div>
                </div>
            </li>
	   <li>
		<div class="colums-width">
                    <label>Caste: </label>
                    <div class="form-details"><?=$casteAIM;?></div>
                </div>
		<div class="colums-width">
                    <label>Nationality: </label>
                    <div class="form-details"><?=$nationality;?></div>
                </div>
            </li>
            
            <li>
            	<h3 class="form-title">Contact Information</h3>
            	<label>Mailing Address: </label>
                <div class="form-details"><?php if($ChouseNumber) echo $ChouseNumber.', ';
									if($CstreetName) echo $CstreetName.', '.$Carea;?></div>
            </li>
            
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City:</label>
                    <div class="form-details"><?=$Ccity;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$Cstate;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$Cpincode; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
            	<label>Mobile No.:</label>
                <div class="form-details"><?php echo $mobileISDCode.$mobileNumber; ?></div>
 		</div>
            	<div class="colums-width">
                    <label>Email:</label>
                    <div class="form-details"><?=$email;?></div>
                </div>
            </li>

	<li>
            	<label>Permanent Address: </label>
                <div class="form-details"><?php $address = $houseNumber;
								if($streetName) $address .= ', '.$streetName;
								if($area) $address .= ', '.$area;echo $address;?></div>
            </li>
            
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City:</label>
                    <div class="form-details"><?=$city;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$state;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$pincode; ?></div>
                </div>
            </li>
              <li>
            	<h3 class="form-title">Family Information:</h3>
		<div class="colums-width">
            	<label>Father's/Husband's Name:</label>
                <div class="form-details"><?php echo $fatherHusbandNameAIM; ?></div>
 		</div>
            	<div class="colums-width">
                    <label>Tel. No.:</label>
                    <div class="form-details"><?=$fatherTelephoneNumber;?></div>
                </div>
	     </li>
	     <li>
		<div class="colums-width">
                    <label>Mobile No.:</label>
                    <div class="form-details"><?=$fatherMobileNumberAIM;?></div>
                </div>
		<div class="colums-width">
            	<label>E-mail:</label>
                <div class="form-details"><?php echo $fatherEmailAIM; ?></div>
 		</div>
	     </li>

	    <li>
		<div class="colums-width">
                    <label>Mother's Name:</label>
                    <div class="form-details"><?=$MotherName;?></div>
                </div>
		<div class="colums-width">
            	<label>Mother's Profession:</label>
                <div class="form-details"><?php echo $MotherOccupation; ?></div>
 		</div>
	     </li>
	     <li>
		<div class="colums-width">
                    <label>Mobile No.:</label>
                    <div class="form-details"><?=$motherMobileAIM;?></div>
                </div>
	     </li>
           
            <li>
            	<div>
                    <label>Father's/Mother's/Husband's Profession/Designation:</label>
                    <div class="form-details"><?=$fmhProfessionDesignationAIM;?></div>
                </div>
            </li>
	    <li>
            	<div>
                    <label>Father's/Mother's/Husband's Organisation & Address:</label>
                    <div class="form-details"><?php $fatherOrganizationAIM = htmlspecialchars_decode($fatherOrganizationAIM); echo $fatherOrganizationAIM; ?></div>
                </div>
            </li>
	    <li>
            	<div class="colums-width">
                    <label>Tel./ Mobile No:</label>
                    <div class="form-details"><?=$fatherOrganizationMobileAIM;?></div>
                </div>
            	
                <div class="colums-width">
                    <label>E-mail:</label>
                    <div class="form-details"><?=$fatherOrganizationEmailAIM;?></div>
                </div>
            </li>

	    <li>
            	<div class="colums-width">
                    <label>Annual Family Income (Gross):</label>
                    <div class="form-details"><?=$annualIncomeFamily;?></div>
                </div>
 
            </li>
            
            <li>
            	<h3 class="form-title">CAT/GMAT/MAT/XAT Exam Details</h3>
            	<div class="colums-width">
                    <label>CAT SCORE (Percentile):</label>
                    <div class="form-details"><?=$catScoreAIM;?></div>
                </div>
            	
                <div class="colums-width">
                    <label>Enrolment No:</label>
                    <div class="form-details"><?=$catRollNumberAIM;?></div>
                </div>
            </li>

	    <li>
            	<div class="colums-width">
                    <label>GMAT SCORE (Composite):</label>
                    <div class="form-details"><?=$gmatScoreAIM;?></div>
                </div>
            	
                <div class="colums-width">
                    <label>Enrolment No:</label>
                    <div class="form-details"><?=$gmatRollNumberAIM;?></div>
                </div>
            </li>

	    <li>
            	<div class="colums-width">
                    <label>MAT SCORE (Composite):</label>
                    <div class="form-details"><?=$matScoreAIM;?></div>
                </div>
            	
                <div class="colums-width">
                    <label>Enrolment No:</label>
                    <div class="form-details"><?=$matRollNumberAIM;?></div>
                </div>
            </li>
	    <li>
            	<div class="colums-width">
                    <label>XAT SCORE (Composite):</label>
                    <div class="form-details"><?=$xatScoreAIM;?></div>
                </div>
            	
                <div class="colums-width">
                    <label>Enrolment No:</label>
                    <div class="form-details"><?=$xatRollNumberAIM;?></div>
                </div>
            </li>
	    <li>
            	<div class="colums-width">
                    <label>CMAT SCORE (Composite):</label>
                    <div class="form-details"><?=$cmatScoreAdditional;?></div>
                </div>
            	
                <div class="colums-width">
                    <label>Enrolment No:</label>
                    <div class="form-details"><?=$cmatRollNumberAdditional;?></div>
                </div>
            </li>
	    <li>
            	<div class="colums-width">
                    <label>Graduation Stream / Percentage:</label>
                    <div class="form-details"><?php echo $graduationExaminationName.' / '.$graduationPercentage; ?></div>
                </div>
            </li>
            	
            <li>
            	<h3 class="form-title">Academic Qualifications</h3>
		<table cellpadding="8" width="100%" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    <tr>
                        <th><div style="width:150px">Examination</div></th>
                        <th colspan="2"><div class="word-wrap" style="width:115px">Year<br /><div style="text-align:left; padding-left:5px; float:left">From</div> <div style="text-align:right; padding-right:20px; float:right">To</div></div></th>
                        <th><div style="width:150px">Name of School/College<br />&amp; Location</div></th>
                        <th><div style="width:100px">Board/<br />University</div></th>
                        <th><div style="width:80px">Subjects<br />Studied</div></th>
                        <th><div style="width:70px">% of<br />Marks</div></th>
                        <th><div style="width:50px">Division<br />Awarded</div></th>
                    </tr>
                    <tr>
                    	<td valign="top"><div class="word-wrap" style="width:150px">Secondary (10)</div></td>
                        <td valign="top" width="50">&nbsp;<?php echo $class10YearFromAIM; ?></td>
                        <td valign="top" width="50">&nbsp;<?php echo $class10Year; ?></td>
                        <td valign="top"><div class="word-wrap" style="width:150px">&nbsp;<?php echo $class10School; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:100px">&nbsp;<?php echo $class10Board; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px">&nbsp;<?php echo $class10SubjectsAIM; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:70px">&nbsp;<?php echo $class10Percentage; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:50px">&nbsp;<?php echo $class10DivisionAIM; ?></div></td>
                    </tr>
                    <tr>
						<td valign="top">Secondary (10+2)</td>
                        <td valign="top" width="50">&nbsp;<?php echo $class12YearFromAIM; ?></td>
                        <td valign="top" width="50">&nbsp;<?php echo $class12Year; ?></td>
                        <td valign="top"><div class="word-wrap" style="width:150px">&nbsp;<?php echo $class12School; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:100px">&nbsp;<?php echo $class12Board; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px">&nbsp;<?php echo $class12SubjectsAIM; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:70px">&nbsp;<?php echo $class12Percentage; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:50px">&nbsp;<?php echo $class12DivisionAIM; ?></div></td>
                    </tr>
                    <tr>
                    	<td colspan="8">
                        <p style="float:left;">Graduation-Degree (B.E./B.Tech./B.Sc./B.Com./B.A./BCA/BBA/BBM/any other) :</p><span style="width:180px; border-bottom:1px solid #000; float:left; padding:0 10px">&nbsp;<?php echo $graduationExaminationName; ?></span>
						<p style="float:left;">
						<?php if($typeOfGraduationDegree == 'Pass') echo "<span style='text-decoration:line-through; color:#666;'>Hons</span>"; else echo "Hons"; ?>/
						<?php if($typeOfGraduationDegree == 'Hons') echo "<span style='text-decoration:line-through; color:#666;'>Pass</span>"; else echo "Pass"; ?>
						</p>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td valign="top">If Completed</td>
			<?php if($graduationYearFromAIM){ ?>
                        <td valign="top" width="50">&nbsp;<?php echo $graduationYearFromAIM; ?></td>
                        <td valign="top" width="50">&nbsp;<?php echo $graduationYear; ?></td>
                        <td valign="top"><div class="word-wrap" style="width:150px">&nbsp;<?php echo $graduationSchool; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:100px">&nbsp;<?php echo $graduationBoard; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px">&nbsp;<?php echo $graduationSubjectsAIM; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:70px">&nbsp;<?php echo $graduationPercentage; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:50px">&nbsp;<?php echo $graduationDivisionAIM; ?></div></td>
			<?php }else{ ?>
                        <td valign="top" width="50">&nbsp;</td>
                        <td valign="top" width="50">&nbsp;</td>
                        <td valign="top"><div class="word-wrap" style="width:150px">&nbsp;</div></td>
                        <td valign="top"><div class="word-wrap" style="width:100px">&nbsp;</div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px">&nbsp;</div></td>
                        <td valign="top"><div class="word-wrap" style="width:70px">&nbsp;</div></td>
                        <td valign="top"><div class="word-wrap" style="width:50px">&nbsp;</div></td>
			<?php } ?>
                    </tr>
					<?php
					for($i=1;$i<=4;$i++) {
						$headingLabel = $i==1?'If not Completed Year 1':'Year '.$i;
					?>
                    <tr>
                    	<td valign="top" <?php if($i>1) echo 'align="right"'; ?>><?php echo $headingLabel; ?></td>
			<?php if(${'graduationYear'.$i.'FromAIM'}){ ?>
                        <td valign="top" width="50">&nbsp;<?php echo ${'graduationYear'.$i.'FromAIM'}; ?></td>
                        <td valign="top" width="50">&nbsp;<?php echo ${'graduationYear'.$i.'FromAIM'}; ?></td>
                        <td valign="top"><div class="word-wrap" style="width:150px">&nbsp;<?php echo $graduationSchool; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:100px">&nbsp;<?php echo $graduationBoard; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px">&nbsp;<?php echo ${'graduationYear'.$i.'SubjectsAIM'}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:70px">&nbsp;<?php echo ${'graduationYear'.$i.'MarksAIM'}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:50px">&nbsp;<?php echo ${'graduationYear'.$i.'DivisionAIM'}; ?></div></td>
			<?php }else{ ?>
                        <td valign="top" width="50">&nbsp;</td>
                        <td valign="top" width="50">&nbsp;</td>
                        <td valign="top"><div class="word-wrap" style="width:150px">&nbsp;</div></td>
                        <td valign="top"><div class="word-wrap" style="width:100px">&nbsp;</div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px">&nbsp;</div></td>
                        <td valign="top"><div class="word-wrap" style="width:70px">&nbsp;</div></td>
                        <td valign="top"><div class="word-wrap" style="width:50px">&nbsp;</div></td>
			<?php } ?>

                    </tr>
					<?php
					}
					?>
                    <tr>
                    	<td align="right" colspan="6">Total Aggregate of Marks (as on date)</td>
                        <td>&nbsp;<?php echo $graduationAggregateMarksAIM; ?></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
		<div class="clearFix spacer5"></div>
                <div class="AIMstudentsNote">
                    <div style="float:left;width:50px;">Note: </div>
                    <div style="float:left;width:830px;">
                        <p>1) &nbsp;&nbsp;If you are awarded grade points please convert them into percentage of marks and indicate</p>
                        <p>2) &nbsp;&nbsp;If there is a formula given by University for conversion it may please be stated.</p>
                        <p>3) &nbsp;&nbsp;If you are a rank holder, indicate the same with the class /division awarded.</p>
                    </div>
                </div>
               <div class="clearFix"></div>
		</li>
		<li>
                <h3 class="form-title">Academic Qualifications (Post Graduation):</h3>
                <table cellpadding="8" width="100%" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    <tr>
                        <th width="150">Examination</th>
                        <th width="80" colspan="2"><div class="word-wrap" style="width:80px">Year<br /><div style="text-align:left; padding-left:5px; float:left">From</div> <div style="text-align:right; float:right">To</div></div></th>
                        <th width="150">Name of School/College<br />&amp; Location</th>
                        <th width="100">Board/<br />University</th>
                        <th width="100">Subjects<br />Studied</th>
                        <th width="40">% of<br />Marks</th>
                        <th width="130">Division<br />From To Awarded</th>
                    </tr>
					<?php
					$pgFound = FALSE;
					for($i=1;$i<=4;$i++) {
						if(${'graduationExaminationName_mul_'.$i} && ${'isPG_mul_'.$i}) {
							$pgFound = TRUE;
					?>
						<tr>
							<td valign="top"><div class="word-wrap" style="width:150px; overflow:hidden">&nbsp;<?php echo ${'graduationExaminationName_mul_'.$i}; ?></div></td>
							<td valign="top"><div class="word-wrap" style="width:40px; overflow:hidden">&nbsp;<?php echo ${'pgYearFrom_mul_'.$i}; ?></div></td>
							<td valign="top"><div class="word-wrap" style="width:40px; overflow:hidden">&nbsp;<?php echo ${'graduationYear_mul_'.$i}; ?></div></td>
							<td valign="top"><div class="word-wrap" style="width:150px; overflow:hidden">&nbsp;<?php echo ${'graduationSchool_mul_'.$i}; ?></div></td>
							<td valign="top"><div class="word-wrap" style="width:100px; overflow:hidden">&nbsp;<?php echo ${'graduationBoard_mul_'.$i}; ?></div></td>
							<td valign="top"><div class="word-wrap" style="width:100px; overflow:hidden">&nbsp;<?php echo ${'pgSubjects_mul_'.$i}; ?></div></td>
							<td valign="top"><div class="word-wrap" style="width:40px; overflow:hidden">&nbsp;<?php echo ${'graduationPercentage_mul_'.$i}; ?></div></td>
							<td valign="top"><div class="word-wrap" style="width:130px; overflow:hidden">&nbsp;<?php echo ${'pgDivision_mul_'.$i}; ?></div></td>
						</tr>
					<?php
						}
					}
					
					if(!$pgFound) {
						for($i=1;$i<=2;$i++) {
					?>
						<tr>
							<td>&nbsp;</td>
							<td width="50">&nbsp;</td>
							<td width="50">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					<?php
						}
					}
					?>
                </table>
		

            </li>
            
            <li>
            	<h3 class="form-title">Experience details, if any, from latest to earliest:</h3>
                <table cellpadding="8" width="100%" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    <tr>
                        <th width="220">Name &amp; Address of Organisation</th>
                        <th width="180">Designation</th>
                        <th width="80">From</th>
                        <th width="80">To</th>
                        <th width="140">Nature of Work</th>
                        <th width="85">Salary Drawn</th>
                    </tr>
                    <tr>
                    	<td valign="top"><div class="word-wrap" style="width:220px;overflow:hidden">&nbsp;<?php echo $weCompanyName; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:180px;overflow:hidden">&nbsp;<?php echo $weDesignation; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden">&nbsp;<?php echo $weFrom; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden">&nbsp;<?php echo $weTimePeriod?'Till date':$weTill; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:140px;overflow:hidden"><?php echo $weRoles; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:85px;overflow:hidden">&nbsp;<?php echo $salaryAIM; ?></div></td>
                    </tr>
					<?php
					for($i=1;$i<=2;$i++){
						if(${'weCompanyName_mul_'.$i}) {
					?>
                    <tr>
                    	<td valign="top"><div class="word-wrap" style="width:220px;overflow:hidden">&nbsp;<?php echo ${'weCompanyName_mul_'.$i}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:180px;overflow:hidden">&nbsp;<?php echo ${'weDesignation_mul_'.$i}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden">&nbsp;<?php echo ${'weFrom_mul_'.$i}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden">&nbsp;<?php echo ${'weTimePeriod_mul_'.$i}?'Till date':${'weTill_mul_'.$i}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:140px;overflow:hidden"><?php echo ${'weRoles_mul_'.$i}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:85px;overflow:hidden">&nbsp;<?php echo ${'salaryAIM_mul_'.$i}; ?></div></td>
                    </tr>
					<?php
						}
					}
					?>
                </table>
                <div style="font-size:12px; padding-top:5px"><em>Please attach attested copies of work experience certificates.</em></div>
                <div class="clearFix"></div>
            </li>
	    <li>
		<h3 class="form-title">Extra Curricular Activities/Sports (with details of prizes won, if any):</h3>
                <div class="spacer10 clearFix"></div>
                <div style="float:right;width:865px">
                    <div class="previewFieldBox" style="width:100%;float:left;">
                        <span><?php echo $extraCurricularAIM; ?></span>
                    </div>
                </div>
	   </li>
	   <li>
		<h3 class="form-title">Why do you want a career in Management and why do you think you are suitable for it? (Answer in 50 words):</h3>
                <div class="spacer10 clearFix"></div>
                <div style="float:right;width:865px">
                    <div class="previewFieldBox" style="width:100%;float:left;">
                        <span><?php echo $careerEssayAIM; ?></span>
                    </div>
                </div>
	   </li>

	   <li>
		<h3 class="form-title">Only for sponsored candidates:</h3>
                <div class="spacer10 clearFix"></div>
                		<div style="width:20px;float:left">-</div>
                    <div style="float:right;width:830px">My organization is sponsoring me for this programme. A sponsorship letter is attached.
(Please use format B for sponsored candidates given in the last page. The letter must be on the company letterhead
&amp; signed by a competent authority)</div>
					<div style="text-align:center; padding:5px 0; clear:left;clear:both;">OR</div>
                    <div style="width:20px;float:left;">-</div>
                    <div style="float:right;width:830px">A No Objection Certificate from my employers is attached.
(Please use format A for EX PGDM candidates given in the last page. The letter must be on the company letterhead
&amp; signed by a competent authority)</div>
	   </li>
            <li>
		<h3 class="form-title">Do you require Hostel accommodation?</h3>
		<div class="preff-cont">Yes: <span class="option-box"><?php if($isHostelRequiredAIM == 'Yes'){?><img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">No: <span class="option-box"><?php if($isHostelRequiredAIM == 'No'){?><img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php } ?></span></div>
            </li>

            <li>
		<h3 class="form-title"> Do you require Educational Loan?</h3>
		<div class="preff-cont">Yes: <span class="option-box"><?php if($isEducationLoadRequired == 'Yes'){?><img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">No: <span class="option-box"><?php if($isEducationLoadRequired == 'No'){?><img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php } ?></span></div>
            </li>

	    <li>
		<h3 class="form-title"> How did you come to know about Asia-Pacific Institute of Management.</h3>
		<div class="clearFix spacer10"></div>
                <div style="width:180px;float:left;">
                	Through Advertisement :
                </div>
                <div style="float:left;width:700px;">
                    <div>
                        <div class="option-box" style="float:left;margin-right:10px"><?php if($newsPaper){?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="padding-top:2px;float:left;">Newspaper</span>
                        <div style="width:490px; float: left; border-bottom: 1px solid #000">
                            <span>&nbsp;<?php echo $newsPaperDesc; ?></span>
                        </div>
                        <span style="float:left;">(Please Specify)</span>
                    </div>
                    
                    <div class="spacer10 clearFix"></div>

		    <div>
                        <div class="option-box" style="float:left;margin-right:10px;"><?php if($magazine){?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="padding-top:2px;float:left;">Magazine</span>
                        <div style="width:500px; float: left; border-bottom: 1px solid #000">
                            <span>&nbsp;<?php echo $magazineDesc; ?></span>
                        </div>
                        <span style="float:left;">(Please Specify)</span>
                    </div>
                    <div class="spacer10 clearFix"></div>
		    <div>
                        <div class="option-box" style="float:left;margin-right:10px;"><?php if($onlineWWW){?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="padding-top:2px;float:left;">Online: www</span>
                        <div style="width:483px; float: left; border-bottom: 1px solid #000">
                            <span>&nbsp;<?php echo $onlineWWWDesc; ?></span>
                        </div>
                        <span style="float:left;">(Please Specify)</span>
                    </div>

                    <div class="spacer10 clearFix"></div>
		  <div>
                        <div class="option-box" style="float:left;margin-right:10px;"><?php if($television){?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="padding-top:2px;float:left;">TV</span>
                        <div style="width:542px; float: left; border-bottom: 1px solid #000">
                            <span>&nbsp;<?php echo $televisionDesc; ?></span>
                        </div>
                        <span style="float:left;">(Please Specify)</span>
                    </div>
                   
                    <div class="spacer10 clearFix"></div>
		    <div>
                        <div class="option-box" style="float:left;margin-right:10px;"><?php if($radio){?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="padding-top:2px;float:left;">Radio</span>
                        <div style="width:522px; float: left; border-bottom: 1px solid #000">
                            <span>&nbsp;<?php echo $radioDesc; ?></span>
                        </div>
                        <span style="float:left;">(Please Specify)</span>
                    </div>
                    <div class="spacer10 clearFix"></div>
		   <div>
                        <div class="option-box" style="float:left;margin-right:10px;"><?php if($hoarding){?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="padding-top:2px;float:left;">Hoarding</span>
                        <div style="width:501px; float: left; border-bottom: 1px solid #000">
                            <span>&nbsp;<?php echo $hoardingDesc; ?></span>
                        </div>
                        <span style="float:left;">(Please Specify)</span>
                    </div>
                </div>
            </li>
	    <li>
	        <div style="width:180px;float:left;">
                	Reference :
                </div>
		<div style="width:700px;float:left;">
                	<div style="width:180px; float:left">
                        <div class="option-box"><?php if($heardFromParent) {?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="float:left;" style="padding-top:2px">Parent</span>
                    </div>
                    
                    <div style="width:180px; float:left">
                        <div class="option-box"><?php if($heardFromFriends) {?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="float:left;" style="padding-top:2px">Friends</span>
                    </div>
                    
                    <div style="width:300px; float:left">
                        <div class="option-box"><?php if($heardFromAlmuni) {?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="float:left;" style="padding-top:2px">Alumni/Current Student of Asia-Pacific</span>
                    </div>
                    <div class="spacer10 clearFix"></div>
                    <div style="width:180px; float:left">
                        <div class="option-box"><?php if($heardFromPeers) {?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="float:left;" style="padding-top:2px">Peers/Colleagues</span>
                    </div>
                    
                    <div style="width:180px; float:left">
                        <div class="option-box"><?php if($heardFromOthers) {?> <img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 /><?php }?></div> 
                        <span style="float:left;" style="padding-top:2px">Others</span>
                    </div>
                </div>		
	    </li>
   	    <li>
		<h3 class="form-title"> Kindly provide two industry / corporate references</h3>
		<div style="width:440px; float:left;">
                        	<p>(1)</p>
                            <ul>
                            	<li>
					<div class="colums-width">
					<label>Name: </label>
					<div class="form-details"><?=$ref1Name;?></div>
					</div>
                                </li>
                                
                                <li>
                                	<div class="colums-width">
					<label>Designation: </label>
					<div class="form-details"><?=$ref1Designation;?></div>
					</div>
                                </li>
                                
                                <li>
                                	<div class="colums-width">
					<label>Organization: </label>
					<div class="form-details"><?=$ref1Organization;?></div>
					</div>
                                </li>
                                
                                <li>
                                	<div class="colums-width">
					<label>Mobile No: </label>
					<div class="form-details"><?=$ref1MobileNumber;?></div>
					</div>
                                </li>
                            </ul>
                        </div>
                        
                        <div style="width:440px; float:left;">
                        	<p>(2)</p>
                            <ul>

                            	<li>
					<div class="colums-width">
					<label>Name: </label>
					<div class="form-details"><?=$ref2Name;?></div>
					</div>
                                </li>
                                
                                <li>
                                	<div class="colums-width">
					<label>Designation: </label>
					<div class="form-details"><?=$ref2Designation;?></div>
					</div>
                                </li>
                                
                                <li>
                                	<div class="colums-width">
					<label>Organization: </label>
					<div class="form-details"><?=$ref2Organization;?></div>
					</div>
                                </li>
                                
                                <li>
                                	<div class="colums-width">
					<label>Mobile No: </label>
					<div class="form-details"><?=$ref2MobileNumber;?></div>
					</div>
                                </li>
                             </ul>
                        </div>
	    </li>		
            <li>
            	<h3 class="form-title">Declaration</h3>
            	<p>I declare that the particulars given above are correct to the best of my knowledge and belief. If, at any stage it is found that
any of the information provided is incorrect then I will withdraw from the programme.</p>
				<div class="clearFix spacer5"></div>
                <ul style="padding-left:20px; margin:0">
                	<li style="list-style:disc !important; padding:0 0 5px 5px !important; display:list-item !important; float:none; width:auto !important">I accept that, on admission I shall be responsible for all my dues and for making timely payment of fees.</li>
                    <li style="list-style:disc !important; padding:0 0 5px 5px !important; display:list-item !important; float:none; width:auto !important">All disputes are subject to the jurisdiction of the competent court of Delhi only.</li>
                </ul>
                
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
