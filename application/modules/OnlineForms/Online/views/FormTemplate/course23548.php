<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <img src="/public/images/onlineforms/institutes/nia/logo2.jpg" alt="" />
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">National Insurance Academy- Application Form: PGDM <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} ?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2017";}?></div>  
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
                <div class="form-details"><?php echo $lastName." ".$firstName." ".$middleName; ?></div>
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
	    <li>
		<div class="colums-width">
                    <label>Category: </label>
                    <div class="form-details"><?=$categoryNIA;?></div>
                </div>
            </li>
	   
	   
	    <li>
            	<h3 class="form-title">Contact Details</h3>
            	<label>Permanent Address: </label>
                <div class="form-details">
		<?php if($houseNumber) echo $houseNumber.', ';
			    if($streetName) echo $streetName.', '.$area;?></div>
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

                <div style="width:227px;margin-top: 25px;">
                    <label>Country:</label>
                    <div class="form-details"><?=$country; ?></div>
                </div>
            </li>
	    
            <li>
            	<div class="colums-width">
                    <label>Email:</label>
                    <div class="form-details"><?=$email;?></div>
                </div>
            	<div class="colums-width">
                     <label>Phone with STD Code:</label>
                     <div class="form-details"><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></div>
		</div>
            </li>

	    <li>
            	<label>Correspondence Address: </label>
                <div class="form-details">
		<?php if($ChouseNumber) echo $ChouseNumber.', ';
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

                <div style="width:227px;margin-top: 25px;">
                    <label>Country:</label>
                    <div class="form-details"><?=$Ccountry; ?></div>
                </div>
            </li>
	    
	    <li>
	    <h3 class="form-title">Family Details</h3>
            	<div >
                    <label>Name of Father / Guardian :</label>
                    <div class="form-details"><?php echo $gaurdianFirstNameNIA?></div>
                </div>
            </li>
	    <li>
		 <div>
                    <label>Name of Mother :</label>
                    <div class="form-details"><?php echo $motherFirstNameNIA;?></div>
                </div>
	    </li>
	    <li>
		 <div>
                    <label>Relationship with Guardian :</label>
                    <div class="form-details"><?php echo $gaurdianRelationshipNIA;?></div>
                </div>
	    </li>
	    <li>
		 <div>
                    <label>Father’s / Guardian’s Occupation :</label>
                    <div class="form-details"><?php echo $gaurdianOccupationNIA;?></div>
                </div>
	    </li>
    	    
            <li>
            	<h3 class="form-title">Education</h3>
				 <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" style="border-collapse: collapse;">
                          <tr>
                              <th>#</th>
                              <th>Board or<br />University</th>
                              <th>Institute</th>
                              <th>Year of passing</th>
                              <th>Percentage Marks</th>
                              <th>Course</th>
                          </tr>

                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">Class 10</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class10Board;?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$class10School?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class10Year?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;<?=$class10Percentage?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$class10ExaminationName?></div></td>
                          </tr>

                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">Class 12 or Diploma</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class12Board?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$class12School?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class12Year?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;<?=$class12Percentage?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$class12ExaminationName?></div></td>
                          </tr>
                                                      
                          <tr>
                              <td height="50"><div class="formWordWrapper" style="width:120px">Graduation</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationBoard?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center">&nbsp;<?=$graduationSchool?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationYear?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;<?=$graduationPercentage?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$graduationExaminationName?></div></td>
                          </tr>


				<!-- Block to show PG course/Other courses row if it is available -->
				<?php
				$otherCourseShown = false;
				$countOfPGCourses = 0;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					 if(${'pgDegreeCheck_mul_'.$i}=='Yes'){$pgDegreeType = 'Post Graduation';}else{$pgDegreeType='Other';}
					 if( ${'pgDegreeCheck_mul_'.$i} == 'Yes' ){ $countOfPGCourses++; ?>

					<tr>
					    <td height="50"><div class="formWordWrapper" style="width:120px"><?=$pgDegreeType;?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationBoard_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'graduationSchool_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationYear_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:140px"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:80px"><?=${'graduationExaminationName_mul_'.$i}?></div></td>
					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->
                                <?php if($countOfPGCourses==0){ ?>
				      <tr>
					  <td height="50"><div class="formWordWrapper" style="width:120px">Post Graduation</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
				      </tr>
                                <?php } ?>



				<!-- Block to show PG course/Other courses row if it is available -->
				<?php
				$otherCourseShown = false;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					if(${'pgDegreeCheck_mul_'.$i}=='Yes'){$pgDegreeType = 'Post Graduation';}else{$pgDegreeType='Other';}					
					if( ${'pgDegreeCheck_mul_'.$i} != 'Yes' ){ $otherCourseShown = true; ?>
					<tr>
					    <td height="50"><div class="formWordWrapper" style="width:120px"><?=$pgDegreeType;?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationBoard_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'graduationSchool_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationYear_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:140px"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:80px"><?=${'graduationExaminationName_mul_'.$i}?></div></td>
					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->

                                <?php if(!$otherCourseShown){ ?>
				      <tr>
					  <td>
					      <div class="formWordWrapper" style="width:120px">Other<div class="clearFix spacer10"></div><div class="clearFix spacer10"></div></div>
					  </td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
				      </tr>
                                <?php } ?>
                            

                      </table>
                    <div class="spacer15 clearFix"></div>
                

            </li>

	   <li>
		<h3 class="form-title">Exam</strong></h3>
               <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                              <th>Examination</th>
                              <th>Registration No./ Form No.</th>
                              <th>Rank (If Applicable)</th>
                              <th>Validity till MM/YY</th>
                          </tr>
                            
                          <tr>
                              <td valign="top"><div class="formWordWrapper" style="width:260px">&nbsp;CAT Nov 2014</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:260px">&nbsp;<?=$catRollNumberAdditional?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$catPercentileScoreRank?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$catExamValidityNIA?></div></td>
                          </tr>
                           <tr>
                              <td valign="top"><div class="formWordWrapper" style="width:260px">&nbsp;CMAT Feb/Sep 2014</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:260px">&nbsp;<?=$cmatRollNumberFeb2014NIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$cmatPercentileNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$cmatExamValidityNIA?></div></td>
                          </tr>

			  <tr>
                              <td valign="top"><div class="formWordWrapper" style="width:260px">&nbsp;CMAT Feb 2015</div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:260px">&nbsp;<?=$cmatRollNumberFeb2015NIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$cmatPercentileFeb2015NIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$cmatExamValidityFeb2015NIA?></div></td>
                          </tr>
                      </table>
	   </li>
	    
	     <li>
            	<div>
		<h3 class="form-title">Work</h3>
                </div>
		<div class="clearFix spacer10"></div>
                    <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                              <th>Organization Name</th>
			      <th>Location</th>
                              <th>Designation</th>
                              <th>Months Worked</th>
                          </tr>
                            
                          <tr>
                              <td valign="top"><div class="formWordWrapper" style="width:130px">&nbsp;<?php echo $weCompanyName;?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:130px">&nbsp;<?php echo $weLocation;?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$weDesignation?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=$durationNIA_mul_0?></div></td>
                          </tr>
                           <?php
                		for($i=1;$i<3;$i++):?>
                          <tr>
                              <td valign="top"><div class="formWordWrapper" style="width:130px">&nbsp;<?=${'weCompanyName_mul_'.$i}?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:130px">&nbsp;<?=${'weLocation_mul_'.$i}?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:160px">&nbsp;<?=${'weDesignation_mul_'.$i}?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;<?=${'durationNIA_mul_'.$i}?></div></td>
                          </tr>
				  <?php endfor;?>
                      </table>
            </li>
	    <li>
		<h3 class="form-title">Honors / Distinctions you have received (Optional)</h3>
                <div class="spacer10 clearFix"></div>
                <div style="float:right;width:865px">
                    <div class="previewFieldBox" style="width:100%;float:left;">
                        <span><?php echo $honorsdistinctionsNIA; ?></span>
                    </div>
                </div>
	   </li>

	    <li>
		<h3 class="form-title">References : <strong style="font-weight:normal;font-style:italic;">(Provide the names and positions of two persons who will submit references on your behalf)</strong></h3>
                <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                              <th>Name of Referee</th>
                              <th>Organization</th>
			      <th>Position</th>
                              <th>Contact No</th>
                              <th>Email</th>
                          </tr>
                            
                          <tr>
                              <td valign="top"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$ref1NameNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$ref1OrganizationNameNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$ref1DesignationNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$ref1TelephoneNoNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$ref1EmailNIA?></div></td>
                          </tr>
                          <tr>
                              <td valign="top"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$ref2NameNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$ref2OrganizationNameNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$ref2DesignationNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$ref2TelephoneNoNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$ref2EmailNIA?></div></td>
                          </tr>
                          <tr>
                              <td valign="top"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$ref3NameNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$ref3OrganizationNameNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:120px">&nbsp;<?=$ref3DesignationNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$ref3TelephoneNoNIA?></div></td>
                              <td valign="top"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$ref3EmailNIA?></div></td>
                          </tr>
                      </table>
	   </li>

	  <li>
		<h3 class="form-title">Personal Statement: <strong style="font-weight:normal;font-style:italic;">(Explain in 250-300 words, why you want to join this course)</strong></h3>
		<div class="spacer10 clearFix"></div>
                <div style="float:right;width:865px">
                    <div class="previewFieldBox" style="width:100%;float:left;">
                        <span><?php echo $personalStatementNIA; ?></span>
                    </div>
                </div>
	  </li>
	  <li>
		<label>Do you have any previous knowledge/qualification in insurance sector?:</label>
                <div class="preff-cont"><span><?=$qualificationNIA?></span></div>
	  </li>
	  <li>
		<label>Are your parents or sibilings working in insurance industry (Insurance company, Insurance brokers, agents etc)? *:</label>
                <div class="preff-cont"><span><?=$workingInsuranceNIA?></span></div>
	  </li>
	  <li>
		<label>Is your work-experience related to insurance industry?:</label>
                <div class="preff-cont"><span><?=$workExInsuranceNIA?></span></div>
	  </li>
	  <li>
		<h3 class="form-title">Professional Qualifications and Certifications (CA/ACII/Actuaries/LOMA/III/NCFM/ etc) - Optional</h3>
		<div class="spacer10 clearFix"></div>
                <div style="float:right;width:865px">
                    <div class="previewFieldBox" style="width:100%;float:left;">
                        <span><?php echo $pqcNIA; ?></span>
                    </div>
                </div>
	  </li>
	  
	  <li>
		<label>I came to know about this programme through :</label>
                <div class="preff-cont">NIA Website: <span class="option-box"><?php if($heardFromNIAWebsite == '1'){?><img src='/public/images/onlineforms/institutes/nia/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Newspaper Advertisement in: <span class="option-box"><?php if($heardFromNewspaperAds == '1'){?><img src='/public/images/onlineforms/institutes/nia/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">MBA Portals: <span class="option-box"><?php if($heardFromMBAPortal == '1'){?><img src='/public/images/onlineforms/institutes/nia/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Friends and Relatives: <span class="option-box"><?php if($heardFromFrndsAndRel == '1'){?><img src='/public/images/onlineforms/institutes/nia/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Coaching Institute: <span class="option-box"><?php if($heardFromCoachingInstitute == '1'){?><img src='/public/images/onlineforms/institutes/nia/tick-icn.gif' border=0 /><?php } ?></span></div>
		<div class="preff-cont">Any other please specify: <span class="option-box"><?php if($heardFromOtherSource == '1'){?><img src='/public/images/onlineforms/institutes/nia/tick-icn.gif' border=0 /><?php } ?></span></div><div style="width:303px; float: left; border-bottom: 1px solid #000"><span>&nbsp;<?php if($heardFromOtherSource == '1'){ echo $heardFromOtherSourceField;}?></span></div>
	  </li>
	  
	  <li>
		<h3 class="form-title">Signature image</h3>
		<div class="spacer10 clearFix"></div>
                <div style="float:right;width:865px">
                    <div class="previewFieldBox" style="width:100%;float:left;">
                        <img src='<?php echo $signatureImageNIA; ?>' border=0>
                    </div>
                </div>
	  </li>
          
            <li>
                  <h3 class="form-title">DECLARATION</h3>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                        	I affirm that all the above statements are true and correct to the best of my knowledge. I understand any false or
misleading statement may constitute grounds for denial of admission or later expulsion. I have read and understood the details given in the Information Brochure.
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
