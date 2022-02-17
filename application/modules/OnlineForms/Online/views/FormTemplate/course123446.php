<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Ref. No. 2015/PGDM/S<?php if(!empty($instituteSpecId)) {echo$instituteSpecId;} else {echo $onlineFormId;} ?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:75%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/cbs/logo2.jpg" alt="CBS" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">CALCUTTA BUSINESS SCHOOL</h2>
				<div style="text-align:left;margin-left:20px">
					Campus: Diamond Harbor Road, Bishnupur,<br>
					24 Parganas (S) - 743503 WB India<br>
					Ph: 033-2420 5200 / 5225, Fax: 2282 6350<br>
					E-Mail: admission@calcuttabusinessschool.org<br>
					Website: www.calcuttabusinessschool.org<br>
				</div>
				</div>
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">APPLICATION FORM FOR ADMISSION IN PGDM 2015-17 BATCH</div>  
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
                        <div class="form-details"> S<?php echo $instituteSpecId; ?></div>
        </li>
        <?php }?>
	    
	    <li>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
		<div>
            	<label>Name of Candidate: </label>
                <div class="form-details"><?php echo $userName; ?></div>
		</div>
		<div class="spacer20 clearFix"></div>
	    </li>

            <li>
            	<h3 class="form-title">Contact Information</h3>
            	<label>Residential: </label>
                <div class="form-details"><?php if($houseNumber) echo $houseNumber.', ';
						if($streetName) echo $streetName.', ';
						if($area) echo $area;?>
				</div>
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
                    <label>PIN:</label>
                    <div class="form-details"><?=$pincode; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Phone:</label>
                    <?php if($landlineNumber){if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
					<?php if($landlineISDCode) $isd = $landlineISDCode.'-';else $isd='';?>
                    <div class="form-details">&nbsp;<?php echo $isd.$std.$landlineNumber; ?></div>
					<?php } ?>
               	</div>
				<div class="colums-width">
                    <label>Mobile:</label>
					<?php if($mobileISDCode) $isd = $mobileISDCode.'-';else $isd='';?>
                    <div class="form-details">&nbsp;<?php echo $isd.$mobileNumber; ?></div>
               	</div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Email:</label>
                    <div class="form-details">&nbsp;<?=$email;?></div>
                </div>
            </li>
	            


            <li>
				<div class="spacer10 clearFix"></div>
            	<label>For Correspondence: </label>
                <div class="form-details"><?php if($ChouseNumber) echo $ChouseNumber.', ';
						if($CstreetName) echo $CstreetName.', ';
						if($Carea) echo $Carea;?>
				</div>
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
                    <label>PIN:</label>
                    <div class="form-details"><?=$Cpincode; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Phone:</label>
                    <?php if($landlineNumber){if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
					<?php if($landlineISDCode) $isd = $landlineISDCode.'-';else $isd='';?>
                    <div class="form-details">&nbsp;<?php echo $isd.$std.$landlineNumber; ?></div>
					<?php } ?>
               	</div>
				<div class="colums-width">
                    <label>Mobile:</label>
					<?php if($mobileISDCode) $isd = $mobileISDCode.'-';else $isd='';?>
                    <div class="form-details">&nbsp;<?php echo $isd.$mobileNumber; ?></div>
               	</div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Email:</label>
                    <div class="form-details">&nbsp;<?=$email;?></div>
                </div>
				<div class="spacer20 clearFix"></div>
            </li>
	            

		
		<li>
            	<h3 class="form-title">Personal Information</h3>
		</li>
		<li>
				<div class="colums-width">
                    <label>Father's name: </label>
                    <div class="form-details"><?=$fathernameCBS;?></div>
                </div>
            	<div class="colums-width">
                    <label>Date Of Birth: </label>
                    <div class="form-details"><?=$dateOfBirth;?></div>
                </div>
           </li> 

           <li> 
				<div class="colums-width">
					<label>Nationality: </label>
					<div class="form-details"><?php echo $nationality; ?></div>
				</div>
				
				<div class="colums-width">
					<label>Religion: </label>
					<div class="form-details"><?=$religion ?></div>
				</div>
            </li>

            <li>
				<div class="colums-width">
                    <label>Sex: </label>
                    <div class="form-details"><?=$gender?></div>
                </div>
            	<div class="colums-width">
                    <label>Marital Status: </label>
                    <div class="form-details"><?=$maritalStatus?></div>
                </div>
				<div class="spacer20 clearFix"></div>
            </li>
      
            
            <li>
            	<h3 class="form-title">Academic Record</h3>
				<strong>Pre-Bachelor Degree Examination</strong>
				<table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
					<th width="10%">Examination</th>
					<th width="30%">Name of the School / College / Institution</th>
					<th width="30%">Board / University</th>
                    <th width="10%">Year of Passing</th>
					<th width="10%">Medium of Instruction</th>
                    <th width="10%">% of Marks/Division</th>
                  </tr>
                  <tr>
					<td>10th</td>
					<td><?=$class10School?></td>
                    <td><?php if($class10Board) echo $class10Board; else echo "&nbsp;";?></td>
                    <td><?=$class10Year;?></td>
					<td><?=$class10thmediumOfInstructionCBS?></td>
                    <td><?=$class10Percentage;?></td>
                  </tr>
                  <tr>
					<td>12th</td>
					<td><?=$class12School?></td>
                    <td><?php if($class12Board) echo $class12Board; else echo "&nbsp;";?></td>
                    <td><?=$class12Year;?></td>
					<td><?=$class12thmediumOfInstructionCBS?></td>
                    <td><?=$class12Percentage;?></td>
                  </tr>
                </table>
				<div class="spacer20 clearFix"></div>
				<strong>Bachelor Degree Examination</strong>
				<table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
					<th width="10%">Examination</th>
					<th width="30%">Name of the School / College / Institution</th>
					<th width="30%">Board / University</th>
                    <th width="10%">Year of Passing</th>
					<th width="10%">Medium of Instruction</th>
                    <th width="10%">% of Marks/Division</th>
                  </tr>
                  <tr>
					<td><div style="width:60px;word-wrap:break-word; float:left"><?=$graduationExaminationName?></div></td>
					<td><?=$graduationSchool?></td>
                    <td><?php if($graduationBoard) echo $graduationBoard; else echo "&nbsp;";?></td>
                    <td><?=$graduationYear;?></td>
					<td><?=$gradmediumOfInstructionCBS?></td>
                    <td><?=$graduationPercentage;?></td>
                  </tr>
                </table>
                <div class="spacer20 clearFix"></div>
				<?php  
					for($j=1;$j<=4;$j++){?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})){?>
					 <?php if(!$display){$display = 1; ?>
					<strong>Master Degree Examination</strong>
				<table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
					<th width="10%">Examination</th>
					<th width="30%">Name of the School / College / Institution</th>
					<th width="30%">Board / University</th>
                    <th width="10%">Year of Passing</th>
					<th width="10%">Medium of Instruction</th>
                    <th width="10%">% of Marks/Division</th>
                  </tr>
				  <?php } ?>
                  <tr>
					<td><div style="width:60px;word-wrap:break-word; float:left"><?=${'graduationExaminationName_mul_'.$j}?></div></td>
					<td><?=${'graduationSchool_mul_'.$j}?></td>
                    <td><?php if(${'graduationBoard_mul_'.$j}) echo ${'graduationBoard_mul_'.$j}; else echo "&nbsp;";?></td>
                    <td><?=${'graduationYear_mul_'.$j};?></td>
					<td><?=${'otherCourseMedium_mul_'.$j}?></td>
                    <td><?=${'graduationPercentage_mul_'.$j};?></td>
                  </tr>
				 
              
				<?php }} ?>
				<?php if($display){ ?>
                </table>
				  <div class="spacer20 clearFix"></div>
				 <?php }else{
				?>
					<strong>Master Degree Examination</strong>
					<table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
						<tr>
						  <th width="10%">Examination</th>
						  <th width="30%">Name of the School / College / Institution</th>
						  <th width="30%">Board / University</th>
						  <th width="10%">Year of Passing</th>
						  <th width="10%">Medium of Instruction</th>
						  <th width="10%">% of Marks/Division</th>
						</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>
					</table>
					<div class="spacer20 clearFix"></div>
				<?php
				 }if(!$professionalDegreeCBS){
					$professionalDegreeCBS = "&nbsp;<br>........................................................................................................";
				 }
				?>
				  <strong>Professional Degree / Diploma (If Completed)</strong>
				  <div><?=$professionalDegreeCBS?></div>
				 <div class="spacer20 clearFix"></div>
            </li>

            <li>
            	<h3 class="form-title">Work Experience</h3>
            	<table cellpadding="8" width="100%" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                    <tr>
                        <th width="26%">Employer's Name & Address</th>
                        <th width="10%">Date of Joining</th>
                        <th width="10%">Date of Leaving</th>
                        <th width="15%">Designation</th>
                        <th width="19%">Job Profile</th>
			<th width="20%">Company Website</th>
                    </tr>
                    <tr>
                    	<td valign="top"><div class="word-wrap" style="width:180px;overflow:hidden">&nbsp;<?php echo $weCompanyName; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden">&nbsp;<?php echo $weFrom; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden">&nbsp;<?php echo $weTimePeriod?'Till date':$weTill; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:100px;overflow:hidden">&nbsp;<?php echo $weDesignation; ?></div></td>
						<td valign="top"><div class="word-wrap" style="width:85px;overflow:hidden"><?=$jobProfileCBS_mul_0?></div></td>
			<td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden"><?=$workExpWebsite_mul_0?></div></td>
					</tr>
					<?php
					for($i=1;$i<=2;$i++){
						if(${'weCompanyName_mul_'.$i}) {
					?>
                    <tr>
                    	<td valign="top"><div class="word-wrap" style="width:220px;overflow:hidden">&nbsp;<?php echo ${'weCompanyName_mul_'.$i}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden">&nbsp;<?php echo ${'weFrom_mul_'.$i}; ?></div></td>
                        <td valign="top"><div class="word-wrap" style="width:80px;overflow:hidden">&nbsp;<?php echo ${'weTimePeriod_mul_'.$i}?'Till date':${'weTill_mul_'.$i}; ?></div></td>
						<td valign="top"><div class="word-wrap" style="width:180px;overflow:hidden">&nbsp;<?php echo ${'weDesignation_mul_'.$i}; ?></div></td>
						<td valign="top"><div class="word-wrap" style="width:180px;overflow:hidden"><?=${'workExpWebsite_mul_'.$i}?></div></td>
					</tr>
					<?php
						}
					}
					?>
                </table>
				<div class="spacer20 clearFix"></div>
            </li>

            <li>
				<h3 class="form-title">Examination Scores</h3>
				(Please attach Self-attested Photocopy of your GMAT / CAT / MAT Score)
				<div class="spacer10 clearFix"></div>
            	<div class="colums-width">
                    <label>GMAT Score:</label>
                    <div class="form-details">&nbsp;<?=$gmatScoreAdditional?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>GMAT Examination Date:</label>
                    <div class="form-details">&nbsp;<?=$gmatDateOfExaminationAdditional?></div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>CAT Score:</label>
                    <div class="form-details">&nbsp;<?=$catScoreAdditional?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>CAT Examination Date:</label>
                    <div class="form-details">&nbsp;<?=$catDateOfExaminationAdditional?></div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>MAT Score:</label>
                    <div class="form-details">&nbsp;<?=$matScoreAdditional?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>MAT Examination Date:</label>
                    <div class="form-details">&nbsp;<?=$matDateOfExaminationAdditional?></div>
                </div>
				<div class="spacer20 clearFix"></div>
            </li>

            <li>
				<h3 class="form-title">Family Background</h3>
		<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
            <tr>
                <th width="15%">&nbsp;</th>
                <th width="25%">Name</th>
                <th width="10%">Age</th>
                <th width="25%">Education</th>
				<th width="25%">Occupation</th>
            </tr>
            <tr>
                <td valign="top">Father</td>
                <td valign="top">&nbsp;<?=$fathernameCBS?></td>
                <td valign="top">&nbsp;<?=$fatherAgeCBS?></td>
                <td valign="top">&nbsp;<?=$fatherEducationCBS?></td>
				<td valign="top">&nbsp;<?=$fatherOccupatonCBS?></td>
            </tr>
            <tr>
                <td valign="top">Mother</td>
                <td valign="top">&nbsp;<?=$mothernameCBS?></td>
                <td valign="top">&nbsp;<?=$motherAgeCBS?></td>
                <td valign="top">&nbsp;<?=$motherEducationCBS?></td>
				<td valign="top">&nbsp;<?=$motherOccupationCBS?></td>
            </tr>
			<tr>
                <td valign="top">Husband/Wife</td>
				<td valign="top">&nbsp;<?=$husbandWifenameCBS?></td>
                <td valign="top">&nbsp;<?=$husbandWifeageCBS?></td>
                <td valign="top">&nbsp;<?=$husbandWifeeducationCBS?></td>
				<td valign="top">&nbsp;<?=$husbandWifeoccupationCBS?></td>
            </tr>
			<tr>
                <td valign="top">Brother(s)</td>
				<td valign="top">&nbsp;<?=$brothersnameCBS?></td>
                <td valign="top">&nbsp;<?=$brothersageCBS?></td>
                <td valign="top">&nbsp;<?=$brotherseducationCBS?></td>
				<td valign="top">&nbsp;<?=$brothersoccupationCBS?></td>
            </tr>
			<tr>
                <td valign="top">Sister(s)</td>
                <td valign="top">&nbsp;<?=$sistersnameCBS?></td>
                <td valign="top">&nbsp;<?=$sistersageCBS?></td>
                <td valign="top">&nbsp;<?=$sisterseducationCBS?></td>
				<td valign="top">&nbsp;<?=$sistersoccupationCBS?></td>
            </tr>
        </table>
		<div class="spacer20 clearFix"></div>
		Family Income <strong>Rs. <?=$familyIncome_CBS?></strong> per annum.
		<div class="spacer20 clearFix"></div>
			</li>
	    
            <li>
            	<h3 class="form-title">Scholarship / Award Received / Any other relevant information</h3>
			<label>Have you received any scholarships / awards or do you have any special achievement in academics over the years ? Mention in brief below. Attach testimonials:</label>
			<div class="form-details"><?php echo $awardsCBS; ?></div>
				<div class="spacer20 clearFix"></div>
			</li>
			<li>
				<label>What have been other interests and/or extra-curricular or co-curricular activities over the years? Write in brief below. Mention if you have won any award/certificate in any such activity and also  if you have  achieved anything  significant. Attach testimonials:</label>
			<div class="form-details"><?php echo $interestCBS; ?></div>
				<div class="spacer20 clearFix"></div>
			</li>
			<li>
				<label>Write a short essary about  your career objectives, how would a Management Diploma help you to achieve your career objectives and why do you want to join the PGDM program at Calcutta Business School:</label>
			<div class="form-details"><?php echo $objectivesCBS; ?></div>
				<div class="spacer20 clearFix"></div>
			</li>
          
			<li>
				<h3 class="form-title">Three References</h3>
			<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
            <tr>
                <th width="22%">Name, Designation & Organization</th>
                <th width="20%">Address</th>
                <th width="13%">Phone No.</th>
                <th width="20%">Email</th>
		<th width="25%">How do you know him/her?</th>
            </tr>
            <tr>
                <td valign="top"><?=$ref1NameCBS?></td>
                <td valign="top">&nbsp;<?=$ref1AddressCBS?></td>
                <td valign="top">&nbsp;<?=$ref1PhoneCBS?></td>
		<td valign="top">&nbsp;<?=$ref1EmailCBS?></td>
		<td valign="top">&nbsp;<?=$ref1sourceCBS?></td>
            </tr>
	    <tr>
                <td valign="top"><?=$ref2NameCBS?></td>
                <td valign="top">&nbsp;<?=$ref2AddressCBS?></td>
                <td valign="top">&nbsp;<?=$ref2PhoneCBS?></td>
		<td valign="top">&nbsp;<?=$ref2EmailCBS?></td>
		<td valign="top">&nbsp;<?=$ref2sourceCBS?></td>
            </tr>
	    <tr>
                <td valign="top"><?=$ref3NameCBS?></td>
                <td valign="top">&nbsp;<?=$ref3AddressCBS?></td>
                <td valign="top">&nbsp;<?=$ref3PhoneCBS?></td>
		<td valign="top">&nbsp;<?=$ref3EmailCBS?></td>
		<td valign="top">&nbsp;<?=$ref3sourceCBS?></td>
            </tr>
			</table>
				<div class="spacer20 clearFix"></div>
			</li>
	 
<!--			<li>-->
<!--            	<h3 class="form-title">Career Objectives</h3>-->
<!--				<?=$objectivesCBS?>-->
<!--				<div class="spacer20 clearFix"></div>-->
<!--			</li>-->
            <li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I Solemnly declare that the information given above is true to the best of my knowledge. Any information given in the application form, if found incorrect on scrutiny, will render the application liable to rejection; admission, if granted, will stand cancelled. I have gone through the rules and process of admission as given in the prospectus. If admitted, I shall abide by the Rules & Regulations of Calcutta Business School (CBS) as at the time of my admission or as may be altered from time to time. I also affirm that I will not participate in/initiate any activity that may lead to disruption / damage the reputation and goodwill of the Calcutta Business School. I submit to the jurisdiction of the High Court of Calcutta, in the event of any dispute.
		</div>       
                <div class="spacer20 clearFix"></div>
				<div class="colums-width">
            
                <label>Date:</label>
                <div class="form-details">&nbsp;<?=$draftDate?></div>
				</div>
				<div class="colums-width">
				<p>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></p>
				</div>
		    </li>
			<li>
				<div class="colums-width">
				<label>Place:</label>
                <div class="form-details"><?php if(isset($firstName) && $firstName!='') {echo $Ccity;} ?></div>
				</div>
				<div class="colums-width">
					
				<label>(Signature of the Applicant)</label>
				</div>
			</li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>
