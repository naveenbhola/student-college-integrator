<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Ref. No. IM<?=$instituteSpecId?></div>

            <div class="inst-name" style="width:100%">
                <img src="/public/images/onlineforms/institutes/nirma/logo.gif" alt="" />
            </div>
			<div class="inst-name" style="width:100%">
			<font size="4"> (www.nirmauni.ac.in/im)  </font>
			</div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: PGDM <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2014";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2016";}?></div>  
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
                        <div class="form-details"><?php echo $instituteSpecId; ?></div>
        </li>
        <?php }?>
	    <li>
        	<h3 class="form-title">Instructions</h3>
        	<ul>
            	<li>1. This form is an integral part of the admission process. Please provide correct information.
Admission granted on incorrect information will be <strong>ipso facto</strong> null and void.</li>
                <li>2. Fill in the application form legibly. Incomplete forms shall not be considered.</li>
                <li>3. All applicants are required to appear for the Common Admission Test (CAT-2014) conducted by the Indian Institutes of Management (IIM).</li>
                <li>4. This form should be sent only after you have received the CAT Admit card. An application form which does not indicate <strong>valid CAT Registration No. will not be considered</strong>.</li>
                <li>5.  In all matters regarding admission to the programme, the decision of the University will be final and binding on the applicant. No correspondence from the applicant with respect to his/her non-selection will be entertained. The admission process at the University shall be subject to the jurisdiction of the courts of Ahmedabad.</li>
                <li>6. The Admission process is subject to the conditions prescribed by the Government of Gujarat from time to time.</li>
            </ul>
	    </li>
	    <li>
                <label>CAT Registration No.</label> 
		<div class="form-details">SR<?=$valuePrefix?><?php echo $catRollNumberAdditional; ?> &nbsp;<span style="color:#666666; font-style:italic">(Please attach photocopy of CAT Admit card)</span></div>
                <div class="clearFix" style="font-size:1px; height:1px; overflow:hidden"></div>
	    </li>
	    <li>            	
		<h3 class="form-title">Personal Details</h3>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
            	<label>Name of Candidate: </label>
                <div class="form-details"><?php echo $firstName." ".$middleName." ".$lastName; ?></div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Gender: </label>
                    <div class="form-details"><?=$gender?></div>
                </div>
            
            	<div class="colums-width">
                    <label>Blood Group: </label>
                    <div class="form-details"><?=$valuePrefix?><?=$bloodGroupNirma?></div>
                </div>
            </li>

	    <li>
            	<div class="colums-width">
                    <label>Age (as on May 31, 2014): </label>
                    <div class="form-details"><?=$ageNirma?></div>
                </div>
            
            	<div class="colums-width">
                    <label>Date Of Birth: </label>
                    <div class="form-details"><?=$dateOfBirth;?></div>
                </div>
            </li>

	   <li>
            	<div class="colums-width">
                    <label>Nationality: </label>
                    <div class="form-details"><?=$nationality;?></div>
                </div>
            
            	<div class="colums-width">
                    <label>Caste (GEN/OBC/ST/SC): </label>
                    <div class="form-details"><?=$applicationCategory;?></div>
                </div>
            </li>

	   
	    
	   <li>
            	<label>Father's/ Guardian's Name: </label>
                <div class="form-details">
		<?=$valuePrefix?><?php echo $fatherName;?></div>
            </li>

   	    <li>
            	<label>Address (For Correspondence): </label>
                <div class="form-details">
		<?=$valuePrefix?><?php echo $ChouseNumber;
									if($CstreetName) echo ', '.$CstreetName;
									if($Carea) echo ', '.$Carea;
									//if($Cstate) echo ', '.$Cstate;
									//if($Ccountry) echo ', '.$Ccountry;
								?></div>
            </li>

	    <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City:</label>
                    <div class="form-details"><?=$city;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$pincode; ?></div>
                </div>

		<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$state;?></div>
                </div>
            </li>

	   <li>
            	<div class="colums-width">
                    <label>Telephone: </label>
                    <div class="form-details"><?=$valuePrefix?><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber; ?></div>
                </div>
            
            	<div class="colums-width">
                    <label>Mobile: </label>
                    <div class="form-details"><?=$valuePrefix?><?php echo $mobileISDCode.'-'.$mobileNumber; ?></div>
                </div>
            </li>

	    <li>
            	<div class="colums-width">
                    <label>E-mail (Block Letters) (1): </label>
                    <div class="form-details"><?=$email;?></div>
                </div>
            
            	<div class="colums-width">
                    <label>(2): </label>
                    <div class="form-details"><?=$altEmail;?></div>
                </div>
            </li>

	    <li>
	    <h3 class="form-title">EDUCATION</h3>
            	<span style="font-size:14px;font-weight:bold;margin-bottom:20px;padding-bottom:3px;"><strong style="float:left;width:60px;">1.</strong>School Education</span>
        <div class="spacer10 clearFix"></div>
        <div style="margin-bottom:5px;">
            <table width="100%" cellpadding="10" cellspacing="0" border="1" style="border-collapse:collapse;border:1px solid #000000;" bordercolor="#000000">
                <tr>
                    <th align="center" width="250px">Particular</th>
                    <th align="center" class="highlighted">S.S.C (X)</th>
                    <th align="center" class="highlighted">H.S.C (XII)</th>
                </tr>
                
                <tr>
                    <th>Board Name</th>
                    <td><?=$valuePrefix?><?=$class10Board?></td>
                    <td><?=$valuePrefix?><?=$class12Board?></td>
                </tr>
                
                <!--<tr>
                    <th>School Name</th>
                    <td><?=$valuePrefix?><?=$class10School?></td>
                    <td><?=$valuePrefix?><?=$class12School?></td>
                </tr>
                -->
                <tr>
                    <th>Stream (Science / Commerce)</th>
                    <td><?=$valuePrefix?><?=$class10streamNirma?></td>
                    <td><?=$valuePrefix?><?=$class12streamNirma?></td>
                </tr>
                
                <tr>
                    <th>Medium of Instruction</th>
                    <td><?=$valuePrefix?><?=$class10mediumNirma?></td>
                    <td><?=$valuePrefix?><?=$class12mediumNirma?></td>
                </tr>
                
                <!--<tr>
                    <th>Total Marks Obtained</th>
                    <td><?=$valuePrefix?><?=$class10totalMarksNirma?></td>
                    <td><?=$valuePrefix?><?=$class12totalMarksNirma?></td>
                </tr>
                
                <tr>
                    <th>Maximum Total Marks</th>
                    <td><?=$valuePrefix?><?=$class10maxMarksNirma?></td>
                    <td><?=$valuePrefix?><?=$class10maxMarksNirma?></td>
                </tr>-->
                
                <tr>
                    <th>Aggregate (%)</th>
                    <td><?=$valuePrefix?><?php echo $class10totalMarksNirma;?></td>
                    <td><?=$valuePrefix?><?php echo $class12totalMarksNirma;?></td>
                </tr>

		<tr>
                    <th>Year of Passing</th>
                    <td><?=$valuePrefix?><?php echo $class10Year;?></td>
                    <td><?=$valuePrefix?><?php echo $class12Year;?></td>
                </tr>
                
                <tr>
                    <th>Pass Attempt</th>
                    <td>
                    	<div style="float:left;font-weight:bold;width:60%">First ( <?php if($class10passAttemptNirma=='First'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                        <div style="float:left;font-weight:bold;width:auto;" style="width:auto">More ( <?php if($class10passAttemptNirma=='more'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                    </td>
                    <td>
                    	<div style="float:left;font-weight:bold;width:60%">First ( <?php if($class12passAttemptNirma=='First'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                        <div style="float:left;font-weight:bold;width:auto;">More ( <?php if($class12passAttemptNirma=='more'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                    </td>
                </tr>
            </table>
        </div>
            </li>
        <div class="spacer10 clearFix"></div>
	   <li>
		<span style="font-size:14px;font-weight:bold;margin-bottom:20px;padding-bottom:3px;"><strong style="float:left;width:60px;">2.</strong>College Education</span>
		<div class="spacer10 clearFix"></div>
		<div style="float:left;font-weight:bold;width:48%">Have you completed your Bachelor's Degree?</div>
		<div style="float:left;font-weight:bold;width:30%;"><strong style="float:left;font-weight:bold;width:auto; padding-right:67px">Yes</strong> ( <?php if($statusNirma=='Completed'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                        <div style="float:left;font-weight:bold;width:auto; padding-right:67px;"><strong style="margin-right:51px;">No</strong> ( <?php if($statusNirma=='Not Completed'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>		
		<div class="spacer10 clearFix"></div>
		<div style="margin-bottom:5px;">
			<table width="100%" cellpadding="10" cellspacing="0" border="1" style="border-collapse:collapse;border:1px solid #000000;" bordercolor="#000000">
                <tr>
                    <th width="220px">Name of the Graduation Degree <span style="font-weight:normal;">(Please mention the name of Degree such as B.Sc., B.Tech, B.com etc.)</span></th>
                    <td width="392"><?=$valuePrefix?><?=$graduationExaminationName?></td>
                </tr>
                
                <tr>
                    <th>Stream <span style="font-weight:normal;">(Science, Commerce, Arts, Mechanical, Electrical etc..)</span></th>
                    <td colspan="2"><?=$valuePrefix?><?=$streamNirma?></td>
                </tr>

                <tr>
                    <th>Mode of Study</th>
                    <td colspan="2"><?=$valuePrefix?><?=$valuePrefix?><?=$modeCourseNirma?></th>
                </tr>
                <!--<tr>
                    <th>College Name</th>
                    <td colspan="2"><?=$valuePrefix?><?=$graduationSchool?></th>
                </tr>
                -->
                <tr>
                    <th>University Name</th>
                    <td colspan="2"><?=$valuePrefix?><?=$graduationBoard?></th>
                </tr>
                
                <tr>
                    <th>Medium of Instruction</th>
                    <td colspan="2"><?=$valuePrefix?><?=$mediumNirma?></th>
                </tr>
                
                <!--<tr>
                    <th>Examination System</th>
                    <td colspan="2">
                    	<div style="float:left;font-weight:bold;width:60%;"><strong style="margin-right:75px; font-weight:normal">Annual</strong> ( <?php if($examSystemNirma=='Annual'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                        <div style="float:left;font-weight:bold;width:auto;"><strong style="margin-right:84px; font-weight:normal">Semester</strong> ( <?php if($examSystemNirma=='Semester'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                    </td>
                </tr>
                
                <tr>
                    <th>Status</th>
                    <td colspan="2">
                    	<div style="float:left;font-weight:bold;width:60%;"><strong style="margin-right:51px; font-weight:normal">Completed</strong> ( <?php if($statusNirma=='Completed'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                        <div style="float:left;font-weight:bold;width:auto;"><strong style="margin-right:51px; font-weight:normal">Not Completed</strong> ( <?php if($statusNirma=='Not Completed'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                    </td>
                </tr>
                
                <tr>
                    <th align="center">Year</th>
                    <td colspan="2" style="padding:0; margin:0">
                    	<div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:180px"><strong>First Semester<br />Aggregate Marks (%)</strong></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><strong>Second Semester<br />Aggregate Marks (%)</strong></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><strong>Overall Aggregate<br />Marks (%)</strong></div>
                        <div style="border:medium none;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:92px"><strong>Year of<br />Passing</strong></div>
                    </td>
                </tr>
                
                <tr>
                    <th>First Year</th>
                    <td colspan="2" style="padding:0; margin:0">
                    	<div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:180px"><?=$valuePrefix?><?=$firstYearFirstSemAggregateNirma."%"?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?=$firstYearSecondSemAggregateNirma."%"?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?=$firstYearOverallAggregateNirma."%"?></div>
                        <div style="border:medium none;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:92px"><?=$valuePrefix?><?=$firstYearpassingYearNirma?></div>
                    </td>
                </tr>
                
                <tr>
                    <th>Second Year</th>
                    <td colspan="2" style="padding:0; margin:0">
                    	<div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:180px"><?=$valuePrefix?><?=$secondYearFirstSemAggregateNirma."%"?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?=$secondYearSecondSemAggregateNirma."%"?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?=$secondYearOverallAggregateNirma."%"?></div>
                        <div style="border:medium none;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:92px"><?=$valuePrefix?><?=$secondYearpassingYearNirma?></div>
                    </td>
                </tr>
                
                <tr>
                    <th>Third Year</th>
                    <td colspan="2" style="padding:0; margin:0">
                    	<div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:180px"><?=$valuePrefix?><?=($thirdYearFirstSemAggregateNirma!='NA')?$thirdYearFirstSemAggregateNirma."%":'NA';?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?=($thirdYearSecondSemAggregateNirma!='NA')?$thirdYearSecondSemAggregateNirma."%":'NA';?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?=($thirdYearOverallAggregateNirma!='NA')?$thirdYearOverallAggregateNirma."%":'NA';?></div>
                        <div style="border:medium none;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:92px"><?=$valuePrefix?><?=$thirdYearpassingYearNirma?></div>
                    </td>
                </tr>
                
                <tr>
                    <th>Fourth Year</th>
                    <td colspan="2" style="padding:0; margin:0">
                    	<div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:180px"><?=$valuePrefix?><?= ($fourthYearFirstSemAggregateNirma!='NA')?$fourthYearFirstSemAggregateNirma."%":'NA';?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?= ($fourthYearSecondSemAggregateNirma!='NA')?$fourthYearSecondSemAggregateNirma."%":'NA';?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?= ($fourthYearOverallAggregateNirma!='NA')?$fourthYearOverallAggregateNirma."%":'NA';?></div>
                        <div style="border:medium none;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:92px"><?=$valuePrefix?><?=$fourthYearpassingYearNirma?></div>
                    </td>
                </tr>
                
                <tr>
                    <th>Fifth Year</th>
                    <td colspan="2" style="padding:0; margin:0">
                    	<div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:180px"><?=$valuePrefix?><?= ($fifthYearFirstSemAggregateNirma!='NA')?$fifthYearFirstSemAggregateNirma."%":'NA';?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?= ($fifthYearSecondSemAggregateNirma!='NA')?$fifthYearSecondSemAggregateNirma."%":'NA';?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><?=$valuePrefix?><?=($fifthYearOverallAggregateNirma!='NA')?$fifthYearOverallAggregateNirma."%":'NA';?></div>
                        <div style="border:medium none;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:92px"><?=$valuePrefix?><?=$fifthYearpassingYearNirma?></div>
                    </td>
                </tr>
                -->
		
                
                <tr>
                    <th valign="top">Year of Admission</th>
                    <td colspan="2" style="padding:0; margin:0">
                    	<div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:170px"><?php echo $graduationYearAdmissionNirma;?></div>
                        <div style="border-right:1px solid #000000;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:172px"><strong>Year of passing</strong></div>
                        <div style="border:medium none;float:left;font-weight:normal;height:32px;padding:6px;text-align:center;width:92px"><?php echo $graduationYear;?></div>
                    </td>
                </tr>
                <tr>
                    <th>Aggregate Percentage</th>
                    <td colspan="2"><?=$graduationAggPercentageNirma?></th>
                </tr>
                <tr>
                  <td colspan="4">
                   	<div style="float:left;font-weight:bold;width:48%">Have you passed all examinations in single attempt?</div>
			<div style="float:left;font-weight:bold;width:auto; padding-right:230px"><strong style="margin:0 20px 0 75px">Yes</strong> ( <?php if($passAttempNirma=='Yes'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
                        <div style="float:left;font-weight:bold;width:auto"><strong style="margin-right:20px">No</strong> ( <?php if($passAttempNirma=='No'){echo "<img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 />";} ?>)</div>
		<div class="spacer5 clearFix"></div>
      	        <p style="color:#666666;font-style:italic;">(Students who have passed all examinations (course-wise) in the first attempt only should indicate passing attempt as "First")</p>
                    </td>

                </tr>

            </table>

		</div>
		<div class="spacer5 clearFix"></div>
                <p style="color:#666666;font-style:italic;">Note: Final year students should indicate percentage of aggregate marks obtained in all the previous examinations.</p>
                    <div class="spacer15 clearFix"></div>
     	  </li>
	    <li>
		<span style="font-size:14px;font-weight:bold;margin-bottom:20px;padding-bottom:3px;"><strong style="float:left;width:60px;">3.</strong>Post Graduate or any other Professional Qualification such as CA, ICWA, ACS etc.</span>
		<div class="spacer10 clearFix"></div>
		<div style="margin-bottom:5px;">
			<?php
	$courseAvailable = false;
	for($i=1;$i<=4;$i++){
		if(${'graduationExaminationName_mul_'.$i}){ $courseAvailable = true;
		?>
        
		<div style="margin-bottom:5px;">
		    <table width="100%" cellpadding="10" cellspacing="0" border="1" class="educationTable" bordercolor="#000000">
                <tr>
                    <th width="250px">Name of the Degree</th>
                    <td align="left">
                        <div class="flLt"><?=${'graduationExaminationName_mul_'.$i}?></div>
                        <div class="flRt"><strong style="padding-right:80px">Mode : <?=${'otherCourseMode_mul_'.$i}?></strong></div>
                    </td>
                </tr>
                
                <tr>
                    <th>Stream (Science, Commerce, Arts,<br />Mechanical, Electrical etc..)</th>
                    <td><?=${'otherCourseStream_mul_'.$i}?></td>
                </tr>
                
                <tr>
                    <th>College Name</th>
                    <td><?=${'graduationSchool_mul_'.$i}?></th>
                </tr>
                
                <tr>
                    <th>University Name</th>
                    <td><?=${'graduationBoard_mul_'.$i}?></th>
                </tr>
                
                <tr>
                    <th>Year of Passing</th>
                    <td><?=${'graduationYear_mul_'.$i}?></th>
                </tr>
                
                <tr>
                    <th>Aggregate Marks Obtained (%)</th>
                    <td><?=${'graduationPercentage_mul_'.$i}?></th>
                </tr>
		    </table>
		</div>

	<?php }
	} ?>

	<?php
	if(!$courseAvailable){ ?>
    
		<div style="margin-bottom:5px;">
		    <table width="100%" cellpadding="10" cellspacing="0" border="1" style="1px solid #000000;border-collapse:collapse;" bordercolor="#000000">
			<tr>
			    <th width="250px">Name of the Degree</th>
			  <td align="right"><strong style="padding-right:80px">Mode : Full Time\Distance\Others</strong></td>
			</tr>
			
			<tr>
			    <th>Stream (Science, Commerce, Arts,<br />Mechanical, Electrical etc..)</th>
			    <td></td>
			</tr>
			
			<tr>
			    <th>College Name</th>
			    <td></td>
			</tr>
			
			<tr>
			    <th>University Name</th>
			    <td></td>
			</tr>
			
			<tr>
			    <th>Year of Passing</th>
			    <td></td>
			</tr>
			
			<tr>
			    <th>Aggregate Marks Obtained (%)</th>
			    <td></td>
			</tr>
		    </table>
		</div>
	<?php } ?>
        
        <div class="spacer25 clearFix"></div>
		</div>
	   </li>
	   <li>
	    	<h3 class="form-title">WORK EXPERIENCE (ONLY FULL-TIME JOB AFTER GRADUATION IN A PROFESSIONAL ORGANIZATION)</h3>
		<table width="100%" cellpadding="7" cellspacing="0" border="1" bordercolor="#000000" style="border-collapse:collapse;">
            	<tr>
                	<td class="highlighted" align="center" width="70">Sr. No.</td>
                    <td class="highlighted" align="center" width="335">Organisation</td>
                    <td class="highlighted" align="center" width="220">Position</td>
                    <td class="highlighted" align="left" width="280" style="margin:0; padding:0;">
                    	<table align="left" width="300px;" cellpadding="3" cellspacing="0" border="0" style="border-collapse:collapse;">
                        	<tr style="font-size:14px;height:30px">
				<td colspan="3" align="center" style="border-bottom:1px solid #000000; font-weight:bold; width:100%;font-size:14px;height:30px">Period (in months)</td></tr>
                            <tr>
                            	<td align="center" width="33%" style="border-right:1px solid #000000; font-weight:bold;font-size:14px;height:30px">From</td>
                                <td align="center" width="33%" style="border-right:1px solid #000000; font-weight:bold;font-size:14px;height:30px">To</td>
                                <td align="center" width="34%" style="font-weight:bold;font-size:14px;height:30px">Total</td>
                            </tr>
                        </table>
                    </td>
                </tr>

		<?php 
		      $workExGiven = false;
		      $total = 0;
		      for($i=0; $i<4; $i++){

			  //$mulSuffix = $i>0?'_mul_'.$i:'';
			  $mulSuffix = $i>0?'_mul_'.$i:'';
			  $companyName = ${'weCompanyName'.$mulSuffix};
			  $durationFrom = ${'weFrom'.$mulSuffix};
			  $durationTo = trim(${'weTimePeriod'.$mulSuffix})?'Till date':${'weTill'.$mulSuffix};
			  $designation = ${'weDesignation'.$mulSuffix};
			  $natureOfWork = ${'weRoles'.$mulSuffix};
			  $workExpNirma = ${'workExpNirma'.$i};
			  if($companyName || $designation){ $workExGiven = true; ?>
                <tr>
                	<td align="center"><?php echo ($i+1);?></td>
                    <td align="center"><?=$valuePrefix?><?php echo $companyName; ?></td>
                    <td align="center"><?=$valuePrefix?><?php echo $designation; ?></td>
                    <td align="left" style="margin:0; padding:0;">
                    	<table align="left" width="100%" cellpadding="3" cellspacing="0" border="0">
                        	<tr style="font-size:14px;height:30px">
                            	<td width="33%" align="center" style="border-right:1px solid #000000;font-size:14px;height:30px"><?=$valuePrefix?><?php echo $durationFrom;?></td>
                                <td width="33%" align="center" style="border-right:1px solid #000000;font-size:14px;height:30px"><?=$valuePrefix?><?php echo $durationTo;?></td>
                                <td width="34%" align="center" style=""><?php

                                                                        if($durationFrom) {
                                                                                $startDate = getStandardDate($durationFrom);
                                                                                $endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
                                                                                $totalDuration = getTimeDifference($startDate,$endDate);
                                                                               // ($totalDuration['months']<0)?0:$totalDuration['months'];
										$total += $totalDuration['months'];
										echo $workExpNirma;
                                                                        }
                                                                        ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
		<?php }} ?>
		
		<?php if(!$workExGiven){ 
		      for($i=0; $i<2; $i++){ ?>
                <tr>
                	<td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="left" style="margin:0; padding:0;">
                    	<table align="left" width="100%" cellpadding="3" cellspacing="0" border="0" >
                        	<tr style="font-size:14px;height:30px;">
                            	<td width="33%" align="center" style="border-right:1px solid #000000;font-size:14px;height:30px"></td>
                                <td width="33%" align="center" style="border-right:1px solid #000000;font-size:14px;height:30px"></td>
                                <td width="34%" align="center" style=""></td>
                            </tr>
                        </table>
                    </td>
                </tr>
		<?php }} ?>
                
                <tr>
               	  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                    <td align="left" style="margin:0; padding:0;">
                    	<table align="left" width="100%" cellpadding="3" cellspacing="0" border="0" class="educationTable2">
                        	<tr style="font-size:14px;height:30px;">
                            	<td align="left" width="64%" style="border-right:1px solid #000000;font-size:14px;height:30px;"><strong>Total Number of Months</strong></td>
                                <td width="33%" align="center" style="font-size:14px;height:30px;"><?php echo $totalExpNirma; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
	   </li>
	   <div class="clearFix spacer10"></div>
            <li>
		<h3 class="form-title">ARE YOU APPLYING IN THE CATEGORY OF FOREIGN NATIONALS/PERSONS OF INDIAN ORIGIN (PIO)/NON RESIDENT INDIANS (NRI) /NRI SPONSORED / CHILDREN OF INDIAN WORKERS OF GULF COUNTRIES AND SOUTH EAST ASIA?</h3>
		<div class="spacer15 clearFix"></div>
                <div class="preff-cont">Yes: <span class="option-box"></span></div>
		<div class="preff-cont">No: <span class="option-box"><img src='/public/images/onlineforms/institutes/nirma/tick-icn.gif' border=0 /></span></div>
	  </li>
    	    
	  <li>

            	<div class="colums-width">
                    <label>(1) Foreign Nation: </label>
                    <div class="form-details"><span class="option-box"></span></div>
                </div>
            
            	<div class="colums-width">
                    <label>(2)  Person of Indian Origin: </label>
                    <div class="form-details"><span class="option-box"></span></div>
                </div>
            </li>

	    <li>
            	<div class="colums-width">
                    <label>(3)  Non Resident Indian: </label>
                    <div class="form-details"><span class="option-box"></span></div>
                </div>
            
            	<div class="colums-width">
                    <label>(4)  NRI Sponsored: </label>
                    <div class="form-details"><span class="option-box"></span></div>
                </div>
            </li>

	    <li>
            	<div class="colums-width">
                    <label>(5)  Gulf Quota: </label>
                    <div class="form-details"><span class="option-box"></span></div>
                </div>
            </li>

	   <li>Please enclose relevant documents verifying your status.</li>
<!---
	   <li class="addressRows">
                 	<strong>Bank Details</strong>
                    <div class="spacer20 clearFix"></div>
                    
                    
                    <div style="width:250px;float:left;">
                        <label>D.D. No :</label>
                        <div style="width:180px;float:left;font-size:14px;">
                            <span style="border-bottom:1px solid #4F4F4F;display:block;padding-bottom:3px;">&nbsp;<?php if($paymentDetails['mode']=='Offline'){ ?><?php echo $paymentDetails['draftNumber'];?><?php }else if($paymentDetails['mode']=='Online'){ ?><?php echo $paymentDetails['orderId'];?><?php }else{ }?></span>
                        </div>
                    </div>
                    
                    <div style="width:410px;float:left;">
                        <label>Bank Name :</label>
                        <div style="width:306px;float:left;font-size:14px;">
                            <span style="border-bottom:1px solid #4F4F4F;display:block;padding-bottom:3px;">&nbsp;<?php if($paymentDetails['mode']=='Offline'){ ?><?php echo $paymentDetails['bankName'];?><?php }else if($paymentDetails['mode']=='Online'){ ?><?php echo $paymentDetails['bankName'];?><?php }else{ }?></span>
                        </div>
                    </div>
                    
                    <div style="width:222px;float:left;">
                    	<label>Date :</label>
                        <div style="width:174px;float:left;font-size:14px;">
                            <span style="border-bottom:1px solid #4F4F4F;display:block;padding-bottom:3px;">&nbsp;<?php if($paymentDetails['mode']=='Offline' && $paymentDetails['draftDate']!='0000-00-00'){ ?><?php echo date("d/m/Y", strtotime($paymentDetails['draftDate']));?><?php }else if($paymentDetails['mode']=='Online'){ ?><?php echo date("d/m/Y", strtotime($paymentDetails['date']));?><?php }else{ }?></span>
                        </div>
                    </div>
	</li>
	<li>
                	<div>
                        <strong>Note:</strong>
                        <ul style="float:none !important;list-style:disc outside none !important;margin-bottom:5px;margin-left:25px;width;auto !important;">
                            <li style="float:none !important;list-style:disc outside none !important;margin-bottom:5px;margin-left:25px;width;auto !important;">Candidates applying in a particular category required to enclose the document verifying their status to be
    eligible to consider in that category.</li>
                            <li style="float:none !important;list-style:disc outside none !important;margin-bottom:5px;margin-left:25px;width;auto !important;">Candidates applying through GMAT need to submit their GMAT score latest by February 28, <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2014";}?></li>
                            <li style="float:none !important;list-style:disc outside none !important;margin-bottom:5px;margin-left:25px;width;auto !important;">Only Candidates appearing for CAT are eligible to apply under <strong>NRI Sponsored</strong> Category</li>
                        </ul>
                    </div>
                </li>
            <!--<li>
            	<h3 class="form-title">EXTRA-CURRICULAR ACTIVITIES (IF ANY):</h3>
		<div class="clearFix spacer5"></div>
                <p style="padding-left:30px"><?=nl2br($extraCurricularNirma)?></p>
            </li>
	    
	    <li>
            	<h3 class="form-title">ACHIEVEMENTS (IF ANY):</h3>
		<div class="clearFix spacer5"></div>
                <p style="padding-left:30px"><?=nl2br($achievementsNirma)?></p>
            </li>
-->
	    <li>
		<h3 class="form-title">CHOICE OF INTERVIEW CENTRE (IF SHORT-LISTED):</h3>
		<div>Please mark clearly only one centre</div>
		<div class="clearFix spacer10"></div>
		<div class="preff-cont">Ahmadabad <span class="option-box"><?php if($preferredGDPILocation=='30'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
		<div class="preff-cont">Bangalore <span class="option-box"><?php if($preferredGDPILocation=='278'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
		<div class="preff-cont">Kolkata <span class="option-box"><?php if($preferredGDPILocation=='130'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
		<div class="preff-cont">New Delhi <span class="option-box"><?php if($preferredGDPILocation=='74'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
		<div class="preff-cont">Patna <span class="option-box"><?php if($preferredGDPILocation=='171'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
		<div class="preff-cont">Pune <span class="option-box"><?php if($preferredGDPILocation=='174'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
            	
		<div class="spacer25 clearFix"></div>
                <p>The University reserves the right to cancel any of the centres at its discretion.</p>
            </li>

            <li>
                  <h3 class="form-title">DECLARATION</h3>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                        	I have carefully read the instructions and agree to abide by the decision of the University regarding my selection to the programme. I certify that the information furnished in this application form is correct to the best of my knowledge and belief.
							<div class="clearFix spacer25"></div>
							<div style="float:left; width:300px;">
                                <div class="formColumns2" style="width:250px;">
                                    <label style="width:auto; padding-top:0">Place :</label>
                                    <span>&nbsp;<?php if(isset($firstName) && $firstName!='') {echo $Cstate;} ?></span>
                                </div>
                                <div class="clearFix spacer10"></div>
                                
                                <div class="formColumns2" style="width:250px;">
                                    <label style="width:auto; padding-top:0">Date :</label>
                                    <span>&nbsp;
					<?php
					      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
						      echo date("d/m/Y", strtotime($paymentDetails['date']));
						}
					?>
				    </span>
                                </div>
                            </div>
                            
                            <div style="float:right; width:500px; text-align:right">
                                <p>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></p>
                                <div>Signature of the Applicant</div>
                             </div>
                        </div>
                    </li>
		    
		           </li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>
