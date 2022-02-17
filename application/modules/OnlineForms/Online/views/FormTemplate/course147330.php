<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <img src="/public/images/onlineforms/institutes/alliance/logo2.jpg" alt="" />
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: MBA 2015-2017</div>  
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
                        <div class="clearFix spacer5"></div>    	
			<div class="colums-width" style="width: 748px;">
		    	<label>Course Applied For:Master of Business Administration (MBA) </label>
		</div>  		
                          <div class="clearFix"></div>
            </li>

	    <li>
<h3 class="form-title">Applicant's Information</h3>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
		<div class="colums-width">
            	<label>Name of Candidate: </label>
                <div class="form-details"><?php echo $userName; ?></div>
		</div>
  
            	<div class="colums-width">
                    <label>Gender: </label>
                    <div class="form-details"><?=$gender?></div>
                </div>
           <li> 
            	<div class="colums-width">
                    <label>Date Of Birth: </label>
                    <div class="form-details"><?=$dateOfBirth;?></div>
                </div>
		<div class="colums-width">
            	<label>Blood Group: </label>
                <div class="form-details"><?=$bloodGroupAlliance?></div>
		</div>
            </li>

	   <li> 
            	<div>
                    <label>Category: </label>
                    <div class="form-details">
 
<div class="preff-cont">SC <span class="option-box"><?php if($preferredGDPILocation=='30'){echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">ST <span class="option-box"><?php if(strpos($applicationCategory,'SC') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">Others <span class="option-box"><?php if(strpos($applicationCategory,'OBC') !== false || strpos($applicationCategory,'DEFENCE') !== false || strpos($applicationCategory,'GENERAL') !== false || strpos($applicationCategory,'HANDICAPPED') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>

	</div>
                </div>
		
		<div>
                    <label style="margin-left:122px;">Session: </label>
		    <div class="form-details">
 
<div class="preff-cont">Spring(Jan) <span class="option-box"><?php if($sessionAlliance=='Jan'){echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">Fall(July) <span class="option-box"><?php if($sessionAlliance=='July'){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>

	</div>
                </div>
		
		
		
		
            </li>

	   <li> 
            	<div>
                    <label>Nationality: </label>
                    <div class="form-details"> <div class="preff-cont"><span class="option-box"><?php if(strpos($nationalityAlliance,'INDIAN') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span>&nbsp;Indian </div>
<div class="preff-cont"><span class="option-box"><?php if(strpos($nationalityAlliance,'NRI') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span>&nbsp;NRI </div>
<div class="preff-cont"><span class="option-box"><?php if(strpos($nationalityAlliance,'Foreign') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span>&nbsp;Foreign National (Citizenship) &nbsp;<span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php if(strpos($nationalityAlliance,'INDIAN') === false){ echo $nDetailsAlliance;} ?></span></div></div>
                </div>
            </li>
		
		
		
		
		
		
		
		
		
	     
            <li>
            	<h3 class="form-title">Applicant's contact details for correspondence</h3>
            	<label>Address Line 1: </label>
                <div class="form-details"><?php echo $ChouseNumber;
									if($CstreetName) echo ', '.$CstreetName;
									//if($Cstate) echo ', '.$Cstate;
									//if($Ccountry) echo ', '.$Ccountry;
								?></div>
            </li>
            <li>
                                            <label>Address Line 2: </label>
                                                                                                                <div class="form-details"><?php if($Carea) echo $Carea; ?></div>
                                                                                                                            </li>
            <li>
                                            <label>Address Line 3: </label>
                                                                                                                <div class="form-details"><?php if($Ccity) echo $Ccity; ?></div>
                                                                                                                            </li>
            
            <li>
            	 <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$Cpincode; ?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$Cstate;?></div>
                </div>
            	 <div class="colums-width" style="width:227px;">
                    <label>Country:</label>
                    <div class="form-details"><?=$Ccountry?></div>
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
		<h3 class="form-title">Parent's contact details</h3>
            	<div class="colums-width">
                    <label>Father's Name: </label>
                    <div class="form-details"><?=$fatherName?></div>
                </div>
		<div class="colums-width">
            	<label>Occupation: </label>
                <div class="form-details"><?=$fatherOccupation?></div>
		</div>
            </li>
	    <li> 
            	<div class="colums-width">
                    <label>Father's Organization: </label>
                    <div class="form-details"><?=$fatherCompanyAlliance?></div>
                </div>
		<div class="colums-width">
            	<label>Designation: </label>
                <div class="form-details"><?=$fatherDesignation?></div>
		</div>
            </li>
	    <li> 
            	<div class="colums-width">
                    <label>Father's Email: </label>
                    <div class="form-details"><?=$fatherEmailAlliance?></div>
                </div>
		<div class="colums-width">
            	<label>Mobile: </label>
                <div class="form-details"><?=$fatherMobileNumberAlliacne?></div>
		</div>
            </li>

	  
	    <li> 
            	<div class="colums-width">
                    <label>Mothers's Name: </label>
                    <div class="form-details"><?=$MotherName?></div>
                </div>
		<div class="colums-width">
            	<label>Occupation: </label>
                <div class="form-details"><?=$MotherOccupation;?></div>
		</div>
            </li>
		<li> 
            	<div class="colums-width">
                    <label>Mother's Organization: </label>
                    <div class="form-details"><?=$motherCompanyAlliance?></div>
                </div>
		<div class="colums-width">
            	<label>Designation: </label>
                <div class="form-details"><?=$MotherDesignation?></div>
		</div>
            </li>
	    <li> 
            	<div class="colums-width">
                    <label>Mother's Email: </label>
                    <div class="form-details"><?=$motherEmailAlliance?></div>
                </div>
		<div class="colums-width">
            	<label>Mobile: </label>
                <div class="form-details"><?=$motherMobileNumberAlliacne?></div>
		</div>
            </li>


	   <li> 
		<div>
            	<label>Telephone (Office): </label>
                <div class="form-details"><?=$fatherNoAlliance?></div>
		</div>
            </li>

	    <li>
            	<label>Residential Address Line 1: </label>
                <div class="form-details"><?php echo $houseNumber;
									if($streetName) echo ', '.$streetName;
									if($area) echo ', '.$area;
									//if($Cstate) echo ', '.$Cstate;
									//if($Ccountry) echo ', '.$Ccountry;
								?></div>
            </li>
            <li>
		    <label>Residential Address Line 2: </label>
                <div class="form-details"><?php if($city) echo $city.", "; echo $pincode; ?></div>
            </li>

            <li>
            	 <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$pincode; ?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$state;?></div>
                </div>
            	 <div class="colums-width" style="width:227px;">
                    <label>Country:</label>
                    <div class="form-details"><?=$country?></div>
                </div>
               
            </li>
            
            <li>
            	<h3 class="form-title">Education qualifications</h3>
		<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" style="border-collapse:collapse;">
                            	<tr>
                                	<th>Name of <br />Examination/Degree</th>
                                    <th>Name of <br />Institution</th>
                                    <th>Name of <br />University/Board</th>
                                    <th>State</th>
                                    <th>Year of <br />passing</th>
                                    <th>Percentage<br />Year/Semester</th>
                                    <th>Main subject</th>
                                    <th>Mode of study</th>
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
						  <td valign="top">
						  <div style="width:120px; text-align:center">
							  Post graduate degree<br />
												  (if any, please specify)
						      <div class="spacer15 clearFix"></div>
						      <div class="previewFieldBox" style="width:100%">
							  <div class="formWordWrapper" style="width:120px; text-align:left; float:left">
								  <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?=${'graduationExaminationName_mul_'.$i}?></span>
							  </div>
						      </div>
						  </div>
					      </td>
					      <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=${'graduationSchool_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=${'graduationBoard_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=${'otherCourseState_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'graduationYear_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=${'otherCourseSubjects_mul_'.$i}?></div></td>
					      <td valign="top">
						  <div style="width:100px;">
							<span style="font-size:12px"><?=${'otherCourseMode_mul_'.$i}?></span>
						  </div>
					      </td>

					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->
                                <?php if($countOfPGCourses==0){ ?>
                                <tr>
                                	<td valign="top">
                                    	<div style="width:120px; text-align:center">
                                        	Post graduate degree<br />
											(if any, please specify)
                                            <div class="spacer15 clearFix"></div>
                                            <div class="previewFieldBox" style="width:100%">
                                            	<div class="formWordWrapper" style="width:120px; text-align:left; float:left">
                                            		<span>&nbsp;</span>
                                            	</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
                                    		<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Full-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
                                    	</div>
                                    </td>
                                </tr>
                                <?php } ?>

                                <tr>
                                	<td valign="top">
                                    	<div style="width:120px; text-align:center">
                                        	Bachelor's degree<br />
											(Please specify)
                                            <div class="spacer15 clearFix"></div>
                                            <div class="previewFieldBox" style="width:100%">
                                            	<div class="formWordWrapper" style="width:120px; text-align:left; float:left">
                                            		<span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?=$graduationExaminationName?></span>
                                            	</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$graduationSchool?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$graduationBoard?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=$gradStateAlliance?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=$graduationYear?></div></td>
                                    <td valign="top"><div style="width:115px;">
                                    	<table width="100%" cellpadding="4" border="1" bordercolor="#000000" cellspacing="0" style="border-collapse:collapse;">
                                        	<tr>
                                            	<td width="40">I</td>
                                                <td width="60" style="text-align:center"><?=$gradMarksSem1Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>II</td>
                                                <td style="text-align:center"><?=$gradMarksSem2Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>III</td>
                                                <td style="text-align:center"><?=$gradMarksSem3Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>IV</td>
                                                <td style="text-align:center"><?=$gradMarksSem4Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>V</td>
                                                <td style="text-align:center"><?=$gradMarksSem5Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>VI</td>
                                                <td style="text-align:center"><?=$gradMarksSem6Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>VII</td>
                                                <td style="text-align:center"><?=$gradMarksSem7Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>VIII</td>
                                                <td style="text-align:center"><?=$gradMarksSem8Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td style="font-size:11px">Aggregate</td>
                                                <td style="text-align:center"><?=$graduationPercentage?></td>
                                            </tr>
                                        </table>
                                    </div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=$gradSubjectsAlliance?></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
						        <?php if(isset($gradModeAlliance)): ?>
                                			<span style="font-size:12px"><?=$gradModeAlliance;?></span>
							<?php else: ?>
				<div style="margin-right:2px"></div>
                                			<span style="font-size:12px">Full-time</span>
                                    		
                                            <div class="clearFix spacer10"></div>   
                                        	<div style="margin-right:2px"></div>
                                			<span style="font-size:12px;">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div style="margin-right:2px"></div>
                                			<span  style="font-size:12px">Correspondence</span>
							<?php endif;?>
                                    	</div>
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td>
                                    	<div style="width:120px; text-align:center">
                                        	XII
										</div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$class12School?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$class12Board?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=$class12StateAlliance?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=$class12Year?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"><?=$class12Percentage?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=$class12SubjectsAlliance?></div></td>
                                    <td valign="top">

                                    	<div style="width:100px;">
				<?php if(isset($class12ModeAlliance)): ?>
                                			<span style="font-size:12px"><?=$class12ModeAlliance;?></span>
							<?php else: ?>
				<div style="margin-right:2px"></div>
                                			<span style="font-size:12px">Full-time</span>
                                    		
                                            <div class="clearFix spacer10"></div>   
                                        	<div style="margin-right:2px"></div>
                                			<span style="font-size:12px;">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div style="margin-right:2px"></div>
                                			<span  style="font-size:12px">Correspondence</span>
							<?php endif;?>
                                    	</div>
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td>
                                    	<div style="width:120px; text-align:center">
                                        	X
										</div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$class10School?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$class10Board?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=$class10StateAlliance?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=$class10Year?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"><?=$class10Percentage?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=$class10SubjectsAlliance?></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
				<?php if(isset($class10ModeAlliance)): ?>
                                			<span style="font-size:12px"><?=$class10ModeAlliance;?></span>
							<?php else: ?>
				<div style="margin-right:2px"></div>
                                			<span style="font-size:12px">Full-time</span>
                                    		
                                            <div class="clearFix spacer10"></div>   
                                        	<div style="margin-right:2px"></div>
                                			<span style="font-size:12px;">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div style="margin-right:2px"></div>
                                			<span  style="font-size:12px">Correspondence</span>
							<?php endif;?>
                                    	</div>
                                    </td>
                                </tr>





				<!-- Block to show PG course/Other courses row if it is available -->
				<?php
				$otherCourseShown = false;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					?>
					<?php if( ${'otherCoursePGCheck_mul_'.$i} != '1' ){ $otherCourseShown = true; ?>
					<tr>
						<td valign="top">
						  <div style="width:120px; text-align:center">
						      Other professional<br />qualifications
						      <div class="spacer15 clearFix"></div>
						      <div class="previewFieldBox" style="width:100%">
							 
							<div class="formWordWrapper" style="width:120px; text-align:left; float:left">
												    		<span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?=${'graduationExaminationName_mul_'.$i}?></span>
		                                    	</div>
						      </div>
						  </div>
					      </td>
					      <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=${'graduationSchool_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=${'graduationBoard_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=${'otherCourseState_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'graduationYear_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=${'otherCourseSubjects_mul_'.$i}?></div></td>
					      <td valign="top">
						  <div style="width:100px;">
								  <span style="font-size:12px"><?=${'otherCourseMode_mul_'.$i}?></span>
						  </div>
					      </td>

					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->

                                <?php if(!$otherCourseShown){ ?>
                                <tr>
                                	<td>
                                    	<div style="width:120px; text-align:center">
                                        	Other professional<br />qualifications
										</div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
                                    		<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Full-time</span>
                                    		
                                            <div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px;">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
                                    	</div>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                            </table>
                <div class="spacer20 clearFix"></div>
                

            </li>
            <li>
            	<h3 class="form-title">Aptitude test score</h3>
		<div style="text-align:center">GMAT/CAT/XAT/MAT/ATMA/CMAT/KMAT</div>
            	<div class="spacer15 clearFix"></div>
		<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" style="border-collapse:collapse;">
                            	<tr>
                            		<th>Test</th>
                                    <th>Date</th>
                                    <th>Percentile</th>
                                    <th>Composite score</th>    
                                </tr>

				<?php
				    $total = 0;
				    $tests = explode(",",$testsAlliance);
				    $dateTest = $percentileTest = $scoreTest = '';
				    foreach ($tests as $test){ 	
					  $total++;
						if($test == 'GMAT' || $test == 'MAT' || $test == 'CAT' || $test == 'XAT' || $test == 'ATMA'){
						$test = strtolower($test);
						$testdate = $test.'DateOfExaminationAdditional';
						$testperc = $test.'PercentileAdditional';
						$testscore = $test.'ScoreAdditional';
					  }
						 else  if($test == 'CMAT' ){
						$test = strtolower($test);
						$testdate = $test.'DateOfExaminationAdditional';
						$testperc = $test.'PercentileAdditionalAlliance';
						$testscore = $test.'ScoreAdditional';
					  }
					  
					   else  if($test == 'KMAT' ){
						$test = strtolower($test);
						$testdate = $test.'DateOfExaminationAdditional';
						$testperc = $test.'PercentileAdditional';
						$testscore = $test.'ScoreAdditional';
					  }
					  
					  
					  

					  $dateTest = $$testdate;
					  $percentileTest = $$testperc;
					  $scoreTest = $$testscore
				?>
                                <tr>
				    <td valign="top" align="center" width="250"><div class="formWordWrapper" style="width:250px; text-align:center">&nbsp;<?php echo strtoupper($test);?></div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;<?=$dateTest?></div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;<?=$percentileTest?></div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;<?=$scoreTest?></div></td>
                                </tr>
				<?php } ?>

				<?php for($i=$total; $i<4;$i++ ){ ?>
                                <tr>
				    <td valign="top" align="center" width="250"><div class="formWordWrapper" style="width:250px; text-align:center">&nbsp;</div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;</div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;</div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;</div></td>
                                </tr>
				<?php } ?>
                            </table>
                <div class="spacer20 clearFix"></div>
                

            </li>

	     <li>
            	<h3 class="form-title">Statement of purpose</h3>
		<div class="clearFix spacer10"></div>
                    <ul>
                        <li class="purposeStatement">
                    		<span style="width:30px;font-szie:14px;float:left;">a.</span>What motivates you to apply to the Alliance School of Business, Alliance University?
                            <div class="spacer5 clearFix"></div>
                            <p style="padding-left:32px;padding-bottom:15px;"><?=$motivatesAlliance?></p>
                        </li>
                        
                        <li class="purposeStatement">
                    	<span style="width:30px;font-szie:14px;float:left;">b.</span>What is your career vision and why is this choice meaningful to you?
                            <div class="spacer5 clearFix"></div>
                            <p style="padding-left:32px;padding-bottom:15px;"><?=$careerVisionAlliance?></p>
                        </li>
                        
                    </ul>
		
	     </li>
	     
            <li>
            	<h3 class="form-title">Employment history <span style="font-weight:normal;">(Please give your full-time employment history to date or other relevant professional experiences)</span></h3>
            	
                        	<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" style="border-collapse:collapse;">
                            	<tr>
                            		<th rowspan="2">Name and address of the organisation</th>
                                    <th colspan="3">Work experience</th>
                                    <th rowspan="2">Designation and responsibilities</th>
                                </tr>
                                <tr>
                            		<th>From</th>
                                    <th>To</th>
                                    <th>In months</th>
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
				      $addressCompany = ${'orgnAddressAlliance'.$otherSuffix};
				      if($companyName || $designation){ $workExGiven = true;$total++; ?>

					    <tr>
						<td valign="top" width="300"><div class="formWordWrapper" style="width:300px"><?php echo $companyName .", ".$addressCompany; ?></div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo $durationFrom;?></div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px;  text-align:center"><?php echo $durationTo;?></div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php
										    if($durationFrom) {
											    $startDate = getStandardDate($durationFrom);
											    $endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
											    $totalDuration = getTimeDifference($startDate,$endDate);
											    echo ($totalDuration['months']<0)?0:$totalDuration['months'];
										    }
										    ?></div></td>
						<td valign="top" width="250"><div class="formWordWrapper" style="width:250px"><?php echo $designation.", ".$natureOfWork;?></div></td>
					    </tr>

			    <?php }} ?>
			    
			    <?php  
				  for($j=$total; $j<3; $j++){ ?>
					    <tr>
						<td valign="top" width="300"><div class="formWordWrapper" style="width:300px">&nbsp;</div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center">&nbsp;</div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center">&nbsp;</div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center">&nbsp;</div></td>
						<td valign="top" width="2500"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
					    </tr>
			    <?php } ?>
                                
                            </table>
            </li>
	    
	    
	<li>
	<h3 class="form-title">Do you need hostel accommodation?</h3>
<div class="preff-cont">Yes <span class="option-box"><?php if(strpos($hostelAlliance,'Yes') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">No <span class="option-box"><?php if(strpos($hostelAlliance,'No') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
		</li>

<?php /* if($coursesAlliance=='MBA' || !isset($coursesAlliance)):  */?>
<li>
	<h3 class="form-title">GD/PI location</h3>
<div class="preff-cont">Bangalore <span class="option-box"><?php if($preferredGDPILocation=='278'){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">Delhi <span class="option-box"><?php if($preferredGDPILocation=='74'){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">Kolkata <span class="option-box"><?php if($preferredGDPILocation=='130'){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">Lucknow <span class="option-box"><?php if($preferredGDPILocation=='138'){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">Ranchi <span class="option-box"><?php if($preferredGDPILocation=='180'){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>

		</li>
<?php /*endif; */?>


              	
<li>
	<h3 class="form-title">Declaration</h3>
<ul>
                        <li>
                    		<ul>
                            	<li>I have read and understood the full requirements of the course, eligibility criteria, terms and conditions and other important information as indicated in the prospectus.</li>
                                <li>I confirm that the information furnished by me in this Application Form is true to the best of my knowledge. I understand that any false or misleading information given by me may lead to the cancellation of admission or expulsion from the course at any stage.</li>
                                <li>I undertake to abide by the rules and regulations of Alliance University School of Business as prescribed from time to time. If I violate at any given point of time any of the stipulated rules and regulations, the University is free to initiate appropriate disciplinary action against me.</li>
                            </ul>
                            <div class="clearFix spacer10"></div>
                            <div class="flLt formWordWrapper" style="width:400px;">Signature: <span>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span></div>
                            <div class="flRt" style="width:130px;">Date: 
			    <span>
				<?php
                                       if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
                                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                                         }
                                ?>
			    </span>
			    </div>
                            <div class="clearFix"></div>
                        </li>

<!---
<li>
	<h3 class="form-title">Application fee</h3>
<ul>
                        <li>
                        	Enclose along with your completed application form a non-refundable processing fee of Rs. 1,000/- in the form of a crossed demand draft in favour of "Alliance University" payable at Bangalore.
                    		<div class="clearFix"></div>
                        </li>
<li>
	<h3 class="form-title">Payment Details</h3>
<strong style="font-size:18px">Application form fee</strong>
                    <div class="clearFix spacer15"></div>
                	<ul>

			<?php if(is_array($paymentDetails)){ ?>
			<?php if($paymentDetails['mode']=='Offline'){ ?>
			    <?php $bankName = $paymentDetails['bankName'];?>
			    <?php $draftNo = $paymentDetails['draftNumber'];?>
			    <?php if(strtotime($paymentDetails['draftDate'])) $draftDate = date("d/m/Y", strtotime($paymentDetails['draftDate'])); else $draftDate = '';?>
			<?php }else if($paymentDetails['mode']=='Online'){ ?>
			    <?php $bankName =  $paymentDetails['bankName'];?>
			    <?php $draftNo =  $paymentDetails['orderId'];?>
			    <?php if(strtotime($paymentDetails['date'])) $draftDate =  date("d/m/Y", strtotime($paymentDetails['date'])); else $draftDate = '';?>
			<?php }
			}else { ?>
			    <?php $bankName =  '';?>
			    <?php $draftNo =  '';?>
			    <?php $draftDate =  '';?>
			<?php } ?>

                        <li>
                        	
<li>DD No.: &nbsp;<span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?=$draftNo?></span></li>
<li>Date: &nbsp;<span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?=$draftDate?></span></li>
<li>Bank: &nbsp;<span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?=$bankName?></span></li>

                            <div class="clearFix spacer10"></div>
                            <p>Amount: Rs. 1,000/-</p>
                    		<div class="clearFix"></div>
                	</ul>
</li>
                	</ul>
</li>
                	</ul>
</li>
			-->
        </ul>
    </div>
    <div class="clearFix"></div>
</div>
