<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <img src="/public/images/onlineforms/institutes/amrita/logo2.jpg" alt="" />
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: Masters in Business Administration <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2014";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2016";}?></div>  
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
        <?php }?>
	    
	    <li>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
            	<label>Name of Candidate: </label>
                <div class="form-details"><?php echo $userName; ?></div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Gender: </label>
                    <div class="form-details"><?=$gender?></div>
                </div>
            
            	<div class="colums-width">
                    <label>Date Of Birth: </label>
                    <div class="form-details"><?=$dateOfBirth;?></div>
                </div>
            </li>
 
	   
	    <?php
		      $startDate = getStandardDate($dateOfBirth);
		      $endDate = date('Y-m-d');
		      $totalDuration = getTimeDifference($startDate,$endDate);
		      $ageMonth = ($totalDuration['months']<0)?0:$totalDuration['months']%12;
		      $ageYear = ($totalDuration['years']<0)?0:$totalDuration['years'];
	    ?>

            <li>
            	<div class="colums-width" >
                <label>Age: </label>
		<div class="preff-cont" style="margin-bottom: 0px;"><?=intVal($ageYearAmrita)?> Yrs</div>
                <div class="preff-cont" style="margin-bottom: 0px;"><?=intVal($ageMonthAmrita)?> Months</div>
                </div>
            
            	<div class="colums-width">
                    <label>Religion: </label>
                    <div class="form-details"><?=$religion;?></div>
                </div>
            </li>
	    
	    <li>
            	<div class="colums-width">
                    <label>Community / Caste: </label>
                    <div class="form-details"><?=$categoryAmrita;?></div>
                </div>
            
            	<div class="colums-width">
                    <label>Mother Tongue: </label>
                    <div class="form-details"><?=$motherTongueAmrita;?></div>
                </div>
            </li>
	    <li>
            	<div class="colums-width">
                    <label>Blood Group: </label>
                    <div class="form-details"><?=$bloodGroupAmrita;?></div>
                </div>
            </li>
	    
	    <li>
            	<h3 class="form-title">Contact Information</h3>
            	<label>Mailing Address Line 1: </label>
                <div class="form-details">
		<?php if($ChouseNumber) echo $ChouseNumber.', ';
			    if($CstreetName) echo $CstreetName.', ';?></div>
            </li>
            <li>
		<label>Mailing Address Line 2: </label>
		<div class="form-details"><?=$Carea;?></div>
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
                    <label>Telephone No: (including STD Code):</label>
                    <?php if($landlineISDCode) $isd = $landlineISDCode.'-';else $isd ='';?>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $isd.$std.$landlineNumber; ?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>Mobile No:</label>
                    <div class="form-details">&nbsp;<?=$mobileISDCode.'-'.$mobileNumber?></div>
                </div>
            </li>
            <li>
            	<label>Permanent Address Line 1: </label>
                <div class="form-details">
		<?php if($houseNumber) echo $houseNumber.', ';
			    if($streetName) echo $streetName.', ';?></div>
            </li>
            <li>
		<label>Permanent Address Line 2: </label>
		<div class="form-details"><?=$area;?></div>
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
            	<div class="colums-width">
                    <label>Email:</label>
                    <div class="form-details"><?=$email;?></div>
                </div>
            	
                <div class="colums-width">
                    <label>Alternate Email:</label>
                    <div class="form-details"><?=$altEmail;?></div>
                </div>
            </li>

	    <li>
	    <h3 class="form-title">FAMILY DETAILS</h3>
            	<div >
                    <label>Father's / Guardian's Name &amp; Occupation :</label>
                    <div class="form-details"><?php echo $fatherName; echo ($fatherOccupation!='')?', '.$fatherOccupation:'';?></div>
                </div>
            	<div class="spacer15 clearFix"></div>
                <div>
                    <label>Mother's Name &amp; Occupation :</label>
                    <div class="form-details"><?php echo $MotherName; echo ($MotherOccupation!='')?', '.$MotherOccupation:'';?></div>
                </div>
            </li>
	    <li>
            	<div class="colums-width">
                    <label>Father's Age: </label>
                    <div class="form-details"><?=$fatherAgeAmrita;?></div>
                </div>
            
            	<div class="colums-width">
                    <label>Mother's Age: </label>
                    <div class="form-details"><?=$motherAgeAmrita;?></div>
                </div>
            </li>
            
           
    	    <?php $testName = explode(',',$testNamesAmrita);?>
	    <?php if(in_array('CAT',$testName)):?>
	    <li>
	    <h3 class="form-title">TEST REGN NO Date & Score</h3>
            	<div class="colums-width">
                    <label>CAT Registration No:</label>
                    <div class="form-details"><?=$catRollNumberAdditional; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>CAT Date:</label>
                    <div class="form-details"><?=$catDateOfExaminationAdditional; ?></div>
                </div>
            </li>
	    <li>
		<div class="colums-width">
                    <label>CAT Score:</label>
                    <div class="form-details"><?=$catScoreAdditional; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>CAT Percentile:</label>
                    <div class="form-details"><?=$catPercentileAdditional; ?></div>
                </div>
            </li>
	    <?php endif;?>
	     <?php if(in_array('MAT',$testName)):?>
	    <li>
            	<div class="colums-width">
                    <label>MAT Registration No:</label>
                    <div class="form-details"><?=$matRollNumberAdditional; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>MAT Date:</label>
                    <div class="form-details"><?=$matDateOfExaminationAdditional; ?></div>
                </div>
            </li>

	    
	     <li>
		<div class="colums-width">
                    <label>MAT Score:</label>
                    <div class="form-details"><?=$matScoreAdditional; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>MAT Percentile:</label>
                    <div class="form-details"><?=$matPercentileAdditional; ?></div>
                </div>
            </li>
	    <?php endif;?>
	     <?php if(in_array('XAT',$testName)):?>
	    <li>
            	<div class="colums-width">
                    <label>XAT Registration No:</label>
                    <div class="form-details"><?=$xatRollNumberAdditional; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>XAT Date:</label>
                    <div class="form-details"><?=$xatDateOfExaminationAdditional; ?></div>
                </div>
            </li>

	    
	    <li>
		<div class="colums-width">
                    <label>XAT Score:</label>
                    <div class="form-details"><?=$xatScoreAdditional; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>XAT Percentile:</label>
                    <div class="form-details"><?=$xatPercentileAdditional; ?></div>
                </div>
            </li>
	    <?php endif;?>
	     <?php if(in_array('GRE',$testName)):?>
	    <li>
            	<div class="colums-width">
                    <label>GRE Registration No:</label>
                    <div class="form-details"><?=$greRegnNoAmrita; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>GRE Date:</label>
                    <div class="form-details"><?=$greDateAmrita; ?></div>
                </div>
            </li>
	    <li>
            	<label>GRE Score:</label>
                <div class="form-details"><?=$greScoreAmrita; ?></div>
            </li>
	    <?php endif;?>
	     <?php if(in_array('GMAT',$testName)):?>
	    <li>
            	<div class="colums-width">
                    <label>GMAT Registration No:</label>
                    <div class="form-details"><?=$gmatRollNumberAdditional; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>GMAT Date:</label>
                    <div class="form-details"><?=$gmatDateOfExaminationAdditional; ?></div>
                </div>
            </li>
	    
	    <li>
            	<label>GMAT Socre:</label>
                <div class="form-details"><?=$gmatScoreAdditional; ?></div>
            </li>
	    <?php endif;?>
            <li>
            	<li>
            	<h3 class="form-title">Choice of ASB Centre for the Personal Interview (Please tick one)</h3>
                <div class="preff-cont">Ahmedabad <span class="option-box"><?php if($preferredGDPILocation=='30'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">Bengaluru <span class="option-box"><?php if($preferredGDPILocation=='278'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">Bhubaneshwar <span class="option-box"><?php if($preferredGDPILocation=='912'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">Chennai <span class="option-box"><?php if($preferredGDPILocation=='64'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">Coimbatore <span class="option-box"><?php if($preferredGDPILocation=='67'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">Hyderabad <span class="option-box"><?php if($preferredGDPILocation=='702'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">Kochi <span class="option-box"><?php if($preferredGDPILocation=='127'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                
                <div class="preff-cont">Kolkata <span class="option-box"><?php if($preferredGDPILocation=='130'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">Lucknow <span class="option-box"><?php if($preferredGDPILocation=='138'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">Mumbai <span class="option-box"><?php if($preferredGDPILocation=='151'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                <div class="preff-cont">New Delhi <span class="option-box"><?php if($preferredGDPILocation=='74'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
            </li>
	    
	    <li>
	    <li>
	    <h3 class="form-title">PREFERENCE OF CAMPUSES - <strong style="font-weight:normal">(Write 1-4 in the boxes, 1 indicating first choice)</strong></h3>
	    <div class="preff-cont">Amritapuri <span class="option-box">
				<?php
					if($pref1Amrita=='Amritapuri') echo "1";
					else if($pref2Amrita=='Amritapuri') echo "2";
					else if($pref3Amrita=='Amritapuri') echo "3";
					else if($pref4Amrita=='Amritapuri') echo "4";
				  ?></span></div>
	    <div class="preff-cont">Bengaluru <span class="option-box"><?php
					if($pref1Amrita=='Bengaluru') echo "1";
					else if($pref2Amrita=='Bengaluru') echo "2";
					else if($pref3Amrita=='Bengaluru') echo "3";
					else if($pref4Amrita=='Bengaluru') echo "4";
				  ?></span></div>
	    <div class="preff-cont">Coimbatore <span class="option-box"> <?php
					if($pref1Amrita=='Coimbatore') echo "1";
					else if($pref2Amrita=='Coimbatore') echo "2";
					else if($pref3Amrita=='Coimbatore') echo "3";
					else if($pref4Amrita=='Coimbatore') echo "4";
				  ?></span></div>
	    <div class="preff-cont">Kochi <span class="option-box"><?php
					if($pref1Amrita=='Kochi') echo "1";
					else if($pref2Amrita=='Kochi') echo "2";
					else if($pref3Amrita=='Kochi') echo "3";
					else if($pref4Amrita=='Kochi') echo "4";
				  ?></span></div>
	</li>
	    <li>
            	<h3 class="form-title">Residential Status Preferred:</h3>
                <div class="preff-cont">Residential <span class="option-box"><?php if($residentAmrita=='Residential') echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />"; ?></span></div>
                <div class="preff-cont">Day scholar* <span class="option-box"><?php if($residentAmrita=='Day scholar') echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />"; ?></span></div>
            </li>

            
            <li>
            	<h3 class="form-title">Academic Details</h3>
				 <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                              <th>Stage</th>
                              <th>Name of<br />Examination</th>
                              <th>Year of<br />Passing</th>
                              <th>No.of/<br />Attempts</th>
                              <th>Branch/Major<br />Specialisation</th>
                              <th>Examining/<br />Certifying/<br />Authority</th>
                              <th>Educational<br />Institution</th>
                              <th>Percentage/<br />G.P.A</th>
                              <th>Division</th>
                          </tr>
                            
                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">SSC/Equivalent</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$class10ExaminationName?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$class10Year?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$class10AttemptsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class10SubjectsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class10Board?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;<?=$class10School?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$class10Percentage?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$class10DivisionAmrita?></div></td>
                          </tr>
                            
                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">HSC/Equivalent</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$class12ExaminationName?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$class12Year?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$class12AttemptsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class12SubjectsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class12Board?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;<?=$class12School?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$class12Percentage?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$class12DivisionAmrita?></div></td>
                          </tr>
                            
                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">U.G. Degree I Yr.</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$graduationExaminationName?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$gradYear1PassingAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$gradYear1AttemptsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$gradYear1SubjectsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationBoard?></div></td>
                              <td rowspan="4" valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;<?=$graduationSchool?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$gradYear1PercentageAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$gradYear1DivisionAmrita?></div></td>
                          </tr>
                            
                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">U.G. Degree II Yr.</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$graduationExaminationName?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$gradYear2PassingAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$gradYear2AttemptsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$gradYear2SubjectsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationBoard?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$gradYear2PercentageAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$gradYear2DivisionAmrita?></div></td>
                          </tr>
                            
                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">U.G. Degree III Yr.</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$graduationExaminationName?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$gradYear3PassingAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$gradYear3AttemptsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$gradYear3SubjectsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationBoard?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$gradYear3PercentageAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$gradYear3DivisionAmrita?></div></td>
                          </tr>
                            
                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">U.G. Degree IV Yr.</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$graduationExaminationName?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$gradYear4PassingAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$gradYear4AttemptsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$gradYear4SubjectsAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationBoard?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$gradYear4PercentageAmrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$gradYear4DivisionAmrita?></div></td>
                          </tr>


				<!-- Block to show PG course/Other courses row if it is available -->
				<?php
				$otherCourseShown = false;
				$countOfPGCourses = 0;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					?>
					<?php if( ${'otherCoursePGCheck_mul_'.$i} == '1' ){ $countOfPGCourses++; ?>

					<tr>
					    <td height="50"><div class="formWordWrapper" style="width:120px">P.G. Degree</div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:80px"><?=${'graduationExaminationName_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'graduationYear_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'otherCourseAttempts_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'otherCourseSubjects_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationBoard_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:140px"><?=${'graduationSchool_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:60px"><?=${'otherCourseDivision_mul_'.$i}?></div></td>
					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->
                                <?php if($countOfPGCourses==0){ ?>
				      <tr>
					  <td height="50"><div class="formWordWrapper" style="width:120px">P.G. Degree</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
				      </tr>
                                <?php } ?>



				<!-- Block to show PG course/Other courses row if it is available -->
				<?php
				$otherCourseShown = false;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					?>
					<?php  if( ${'otherCoursePGCheck_mul_'.$i} != '1' ){ $otherCourseShown = true; ?>
					<tr>
					    <td>
						<div class="formWordWrapper" style="width:120px">Other qualifications<div class="clearFix spacer10"></div><div class="clearFix spacer10"></div></div>
					    </td>
					    <td valign="top"><div class="formWordWrapper" style="width:80px"><?=${'graduationExaminationName_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:60px"><?=${'graduationYear_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:60px"><?=${'otherCourseAttempts_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'otherCourseSubjects_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationBoard_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:140px"><?=${'graduationSchool_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:70px"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:60px"><?=${'otherCourseDivision_mul_'.$i}?></div></td>
					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->

                                <?php if(!$otherCourseShown){ ?>
				      <tr>
					  <td>
					      <div class="formWordWrapper" style="width:120px">Other qualifications<div class="clearFix spacer10"></div><div class="clearFix spacer10"></div></div>
					  </td>
					  <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
				      </tr>
                                <?php } ?>
                            

                      </table>
		<div class="spacer5 clearFix"></div>
                      <p>* Students who are living with their parents in the city where the campus is located can avail this facility, if they are located within 30 km from campus.</p>
                    <div class="spacer15 clearFix"></div>
                

            </li>
	    <li>
            	<div>
                    <label>Please account for breaks in your academic career, if any: </label>
                    <div class="form-details"><?=$breakAmrita?></div>
                </div>
            </li>
	    
	     <li>
            	<div>
		<h3 class="form-title">Meritorious Achievements : <span style="font-weight:normal;">Academic/Professional Awards/Medals/Prizes/Scholarship/Certificates/Honours, etc.,</span> </h3>
                    <label><br/>
		    <span style="font-weight:normal;">(For co-curricular activities, please see <strong>MAJOR CO-CURRICULAR ACTIVITIES</strong> section)</span><br/>
		    <span style="font-weight:normal;">(Please enclose attested copies)</span></label>
                    
                </div>
		<div class="clearFix spacer10"></div>
                    <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                              <th>No.</th>
                              <th>Name of Award</th>
                              <th>Awarding Institution</th>
                              <th>Level</th>
                              <th>Basis of Award</th>
                              <th>Year</th>
                          </tr>
                            
                          <tr>
                              <td align="center" height="40"><div class="formWordWrapper" style="width:40px">1</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$awardName1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$awardInst1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$awardLevel1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$awardBasis1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$awardYear1Amrita?></div></td>
                          </tr>
                          
                          <tr>
                              <td align="center" height="40"><div class="formWordWrapper" style="width:40px">2</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$awardName2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$awardInst2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$awardLevel2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$awardBasis2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$awardYear2Amrita?></div></td>
                          </tr>
                          
                          <tr>
                              <td align="center" height="40"><div class="formWordWrapper" style="width:40px">3</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$awardName3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$awardInst3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$awardLevel3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$awardBasis3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$awardYear3Amrita?></div></td>
                          </tr>
                          
                          <tr>
                              <td align="center" height="40"><div class="formWordWrapper" style="width:40px">4</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$awardName4Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$awardInst4Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$awardLevel4Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$awardBasis4Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center">&nbsp;<?=$awardYear4Amrita?></div></td>
                          </tr>
                          
                      </table>
            </li>
            <li>
            	<h3 class="form-title">WORK EXPERIENCE <strong style="font-weight:normal">( Please furnish a certificate from the last employer)</strong></h3>
				<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                              <th>No.</th>
                              <th>Name of Orgn.</th>
                              <th>Annual <br />Turnover <br />(Rs. Lakhs)</th>
                              <th>Size of<br /> Orgn <br />(No.of employees)</th>
                              <th>Category <br />(F,A,O)*</th>
                              <th>Designation</th>
                              <th>Reporting to <br />(Designation)</th>
                              <th>Nature of <br />duties</th>
                              <th>Period <br />From -To <br />(in months)</th>
                              <th>Total <br />(monthly) <br />Salary</th>
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
				      $designation = ${'weDesignation'.$mulSuffix};
				      $natureOfWork = ${'weRoles'.$mulSuffix};
				      if($companyName || $designation){ $workExGiven = true;$total++; ?>

					    <tr>
						<td align="center" height="50"><div class="formWordWrapper" style="width:30px"><?=$total?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?php echo $companyName; ?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=${'orgnTurnoverAmrita'.$otherSuffix}?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=${'orgnSizeAmrita'.$otherSuffix}?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:70px">&nbsp;<?php
										    if(strpos(${'orgnCategoryAmrita'.$otherSuffix},'Full')!==false) echo "F";
										    else if(strpos(${'orgnCategoryAmrita'.$otherSuffix},'Vocational')!==false) echo "A";
										    else if(strpos(${'orgnCategoryAmrita'.$otherSuffix},'ther')!==false) echo ${'orgnOtherAmrita'.$otherSuffix};
										    ?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$designation?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=${'orgnReportingAmrita'.$otherSuffix}?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$natureOfWork?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;<?php
										    if($durationFrom) {
											    $startDate = getStandardDate($durationFrom);
											    $endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
											    $totalDuration = getTimeDifference($startDate,$endDate);
											    echo ($totalDuration['months']<0)?0:$totalDuration['months'];
										    }
										    ?></div></td>
						<td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=${'orgnSalaryAmrita'.$otherSuffix}?></div></td>
					    </tr>

			    <?php }} ?>
			    
			    <?php  
				  for($j=$total; $j<3; $j++){ ?>
				    <tr>
					<td align="center" height="50"><div class="formWordWrapper" style="width:30px"><?=$j+1?></div></td>
					<td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
				    </tr>
			    <?php } ?>
                            
                      </table>
                      <div class="clearFix spacer10"></div>
                      <div style="float:left; width:200px; padding-left:30px">*F : Full Time</div>
                      <div style="float:left; width:270px;">A : Apprenticeship/Vocational</div>
                      <div style="float:left; width:200px;">O : Other (please specify)</div>
                

            </li>
	    
	    <li>
            	<h3 class="form-title">MAJOR CO-CURRICULAR ACTIVITIES</h3>
				<div class="clearFix spacer10"></div>
                    <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                              <th rowspan="2">No.</th>
                              <th rowspan="2">Activity</th>
                              <th rowspan="2">Role</th>
                              <th rowspan="2">Level</th>
                              <th colspan="2">Year</th>
                              <th rowspan="2">Honours<br />(if any)</th>
                              <th rowspan="2">Remarks</th>
                          </tr>
                          <tr>
                              <th>From</th>
                              <th>To</th>
                          </tr>
                            
                          <tr>
                              <td align="center" height="50"><div class="formWordWrapper" style="width:30px">1</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$activity1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$role1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?php echo ($level1Amrita!='' && $level1Amrita!='Others')?$level1Amrita:$levelOther1Amrita;?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:50px; text-align:center ">&nbsp;<?=$yearFrom1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:50px; text-align:center">&nbsp;<?=$yearTo1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$honour1Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$remarks1Amrita?></div></td>
                          </tr>
                          
                          <tr>
                              <td align="center" height="50"><div class="formWordWrapper" style="width:30px">2</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$activity2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$role2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?php echo ($level2Amrita!='' && $level2Amrita!='Others')?$level2Amrita:$levelOther2Amrita;?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:50px; text-align:center">&nbsp;<?=$yearFrom2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:50px; text-align:center">&nbsp;<?=$yearTo2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$honour2Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$remarks2Amrita?></div></td>
                          </tr>
                          
                          <tr>
                              <td align="center" height="50"><div class="formWordWrapper" style="width:30px">3</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$activity3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$role3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?php echo ($level3Amrita!='' && $level3Amrita!='Others')?$level3Amrita:$levelOther3Amrita;?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:50px; text-align:center">&nbsp;<?=$yearFrom3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:50px; text-align:center">&nbsp;<?=$yearTo3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$honour3Amrita?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$remarks3Amrita?></div></td>
                          </tr>
                      </table>
                

            </li>
	    <li>
            	<h3 class="form-title">PLEASE FURNISH TWO REFERENCES <strong style="font-weight:normal">(SHOULD NOT BE CLOSE RELATIVES)</strong></h3>
		<div class="clearFix spacer5"></div>
		<p>ASB may contact the referees, if necessary.</p>
		<div class="clearFix spacer10"></div>
		
		<div class="colums-width">
		<strong>1st Reference:</strong>
		<div class="clearFix spacer10"></div>
                    <label>Name:</label> <div class="form-details"><?=$ref1NameAmrita?></div>

                </div>
            	<strong>2nd Reference:</strong>
		<div class="spacer10"></div>
                <div class="colums-width">
		    <label>Name:</label><div class="form-details"><?=$ref2NameAmrita; ?></div>

                </div>
	
		<div class="clearFix spacer10"></div>
		<div class="colums-width">
                    <label>Occupation:</label> <div class="form-details"><?=$ref1OccupationAmrita; ?></div>

                </div>
            	
                <div class="colums-width">
		    <label>Occupation:</label><div class="form-details"><?=$ref2OccupationAmrita; ?></div>

                </div>
	
		<div class="clearFix spacer10"></div>
		<div class="colums-width">
                    <label>Address:</label> <div class="form-details"><?=$ref1AddressAmrita; ?></div>

                </div>
            	
                <div class="colums-width">
		    <label>Address:</label><div class="form-details"><?=$ref2AddressAmrita; ?></div>

                </div>
	
		<div class="clearFix spacer10"></div>
		<div class="colums-width">
                    <label>Pin:</label> <div class="form-details"><?=$ref1PinAmrita; ?></div>

                </div>

		
    		<div class="colums-width">
                    <label>Pin:</label> <div class="form-details"><?=$ref2PinAmrita?></div>

                </div>
            	<div class="clearFix spacer10"></div>
                <div class="colums-width">
		    <label>STD:</label><div class="form-details"><?=$ref1STDAmrita; ?></div>

                </div>
		
		<div class="colums-width">
                    <label>STD:</label> <div class="form-details"><?=$ref2STDAmrita; ?></div>

                </div>
            	<div class="clearFix spacer10"></div>
                <div class="colums-width">
		    <label>Tel:</label><div class="form-details"><?=$ref1PhoneAmrita; ?></div>

                </div>
	
		
		<div class="colums-width">
                    <label>Tel:</label> <div class="form-details"><?=$ref2PhoneAmrita; ?></div>

                </div>
            	<div class="clearFix spacer10"></div>
                <div class="colums-width">
		    <label>e-mail:</label><div class="form-details"><?=$ref1EmailAmrita; ?></div>

                </div>
		<div>
                    <label>e-mail:</label> <div class="form-details"><?=$ref2EmailAmrita; ?></div>

                </div>

	    </li>
	     <li style="margin-bottom:10px">
		<h3 class="form-title upperCase">REFLECTIVE ESSAYS <strong style="font-weight:normal">(if you called for the interview process then you have to answer (hand written) these subjective questions and bring it to the interview venue)</strong></h3>
			<label style="font-weight:bold; width:700px"><span>H.</span><strong style="font-weight:normal">How will you introduce yourself to your classmates when you join the MBA class of <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2014";}?> at ASB?</strong></label>
		<div class="clearFix spacer5"></div>
		<p style="padding-left:30px"><?//=$introduceAmrita?></p>
	    </li>
		<li>
		<label style="font-weight:bold; width:700px"><span>I.</span><strong style="font-weight:normal">What are your career objectives and what do you need to learn at ASB to accomplish them?</strong></label>
		<div class="clearFix spacer5"></div>
		<p style="padding-left:30px"><?//=$careerAmrita?></p>
		</li>
		<li>
			<label style="font-weight:bold; width:700px"><span>J.</span><strong style="font-weight:normal">Tell us about a mistake you made in your life and what you learned from that experience?</strong></label>
			<div class="clearFix spacer5"></div>
			<p style="padding-left:30px"><?//=$mistakeAmrita?></p>
                </li>

	    <!--<li style="margin-bottom:10px">
                  	<h3 class="form-title">REFLECTIVE ESSAYS (H TO J) USE ADDITIONAL SHEETS</h3>
                    <div class="clearFix spacer10"></div>
                    <strong>Write Reflective essays on the topics given below in about 400 words for each question:</strong>
                    </li>
                    
                    <li>
                  		<label style="font-weight:bold; width:700px"><span>H.</span><strong style="font-weight:normal">How will you introduce yourself to your classmates when you join the MBA class of <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2012";}?> at ASB?</strong></label>
                        <div class="clearFix spacer5"></div>
                        <p style="padding-left:30px"><?//=$introduceAmrita?></p>
                    </li>

		    
                    
                    <li>
                  		<label style="font-weight:bold; width:700px"><span>I.</span><strong style="font-weight:normal">What are your career objectives and what do you need to learn at ASB to accomplish them?</strong></label>
                        <div class="clearFix spacer5"></div>
                        <p style="padding-left:30px"><?//=$careerAmrita?></p>
                    </li>
		    
                    
                    <li>
                  		<label style="font-weight:bold; width:700px"><span>J.</span><strong style="font-weight:normal">Tell us about a mistake you made in your life and what you learned from that experience?</strong></label>
                        <div class="clearFix spacer5"></div>
                        <p style="padding-left:30px"><?//=$mistakeAmrita?></p>
                    </li>
		    -->
		    
		     <li>
                  		<label style="font-weight:bold; width:700px"><span>K.</span><strong style="font-weight:normal">How did you come to know of <strong>ASB?</strong></strong></label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
                            <label>CAT Coaching Centres :</label>
			    <div class="preff-cont">IMS <span class="option-box"><?php if(strpos($coachingAmrita,'IMS') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                            <div class="preff-cont">T.I.M.E. <span class="option-box"><?php if(strpos($coachingAmrita,'T.I') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			    <div class="preff-cont">PT Education <span class="option-box"><?php if(strpos($coachingAmrita,'PT') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                            <div class="preff-cont">Career Launcher <span class="option-box"><?php if(strpos($coachingAmrita,'Launcher') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			    <div class="preff-cont">Other &nbsp;&nbsp;&nbsp;(Please specify): <?=$coachingOtherAmrita?></div>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
                            <label>CAT Coaching Centres :</label>
			    <div class="preff-cont">Hindustan Times <span class="option-box"><?php if(strpos($newspaperAmrita,'Hindustan') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			    <div class="preff-cont">Indian Express <span class="option-box"><?php if(strpos($newspaperAmrita,'Express') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                            <div class="preff-cont">Times of India <span class="option-box"><?php if(strpos($newspaperAmrita,'of India') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			    <div class="preff-cont">The Hindu <span class="option-box"><?php if(strpos($newspaperAmrita,'The Hindu') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                            <div class="preff-cont">Telegraph <span class="option-box"><?php if(strpos($newspaperAmrita,'Telegraph') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
			<label>Internet :</label>
			<div class="preff-cont">Shiksha.com <span class="option-box"><?php if(strpos($internetAmrita,'Shiksha') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">Cool avenues.com <span class="option-box"><?php if(strpos($internetAmrita,'Cool') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                        <div class="preff-cont">AIMA <span class="option-box"><?php if(strpos($internetAmrita,'AIMA') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">Amrita <span class="option-box"><?php if(strpos($internetAmrita,'Amrita') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">IIMs <span class="option-box"><?php if(strpos($internetAmrita,'IIMs') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
                        <div class="preff-cont">MBA Universe <span class="option-box"><?php if(strpos($internetAmrita,'Universe') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">Other :<span>&nbsp;<?=$internetOtherAmrita?></span></div>

                        <div class="spacer15 clearFix"></div>
                        
			<div class="preff-cont">Alumni <span class="option-box"><?php if(strpos($otherSourcesAmrita,'Alumni') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">Amrita University <span class="option-box"><?php if(strpos($otherSourcesAmrita,'Amrita') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">Friends <span class="option-box"><?php if(strpos($otherSourcesAmrita,'Friends') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">M.A. MATH <span class="option-box"><?php if(strpos($otherSourcesAmrita,'MATH') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">Relatives <span class="option-box"><?php if(strpos($otherSourcesAmrita,'Relatives') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span></div>
			<div class="preff-cont">Other (Please specify) :<span>&nbsp;<?=$otherSourcesOtherAmrita?></span></div>
                        </div>
                    </li>
	    
            <li>
                  <h3 class="form-title">DECLARATION</h3>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                        	All entries made in the application form are true to the best of my knowledge and belief. I am willing to produce original
certificates on demand at any time. I also undertake that I shall abide by the rules and regulations of ASB and the University.
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
		    
		    <li>
                    	<div class="spacer15 clearFix"></div>
                  	<h3 class="form-title">UNDERTAKING</h3>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
                        <div class="flLt">I</div> <div style="width:325px; float: left; border-bottom: 1px solid #000"><span>&nbsp;&nbsp;<?=$fatherName?></span></div> 
			<?php if(isset($firstName) && $firstName!=''){ ?>
			<div class="flLt">father/<strike>mother</strike>/<strike>guardian</strike> of</div>	
			<?php }else{ ?>
			<div class="flLt">father/mother/guardian of</div>	
			<?php } ?>
			<div style="width:325px; float: left; border-bottom: 1px solid #000"><span>&nbsp;&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span></div>
                        <div class="flLt">do</div>
                        <div class="flLt">hereby accept responsibility for good conduct of my 
			<?php if(isset($firstName) && $firstName!='' && $gender=='MALE'){ ?>
			son/<strike>daughter</strike>/<strike>ward</strike>
			<?php } else if(isset($firstName) && $firstName!='' && $gender=='FEMALE'){ ?>
			<strike>son</strike>/daughter/<strike>ward</strike>
			<?php }else{ ?>
			son/daughter/ward 
			<?php } ?>
			during the entire period of the<br />course, both inside and outside the campus.</div>
                        
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
                                <p>&nbsp;<?=$fatherName?></p>
                                <div>Signature of Parent/Guardian</div>
                             </div>
                        </div>
                    </li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>
