   	<!--AIM Form Preview Starts here-->
	<link href="/public/css/onlineforms/cmd/CMD_styles.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
    	<div class="previewHeader"> 
        	<div class="instLogoBox"><img src="/public/images/onlineforms/institutes/cmd/logo.gif" alt="" /></div>
            <div class="courseNameDetails">
            	<p><strong>CENTRE FOR MANAGEMENT DEVELOPMENT<br />MODINAGAR-201204</strong></p>
                <p>APPLICATION FORM FOR ADMISSION<br />
POST GRADUATE DIPLOMA IN MANAGEMENT (PGDM)<br />
(RECOGNISED BY ALL INDIA COUNCIL FOR TECHNICAL EDUCATION, GOVERNMENT OF INDIA)<br />
</p>
				
            </div>
      
            <div class="spacer5 clearFix"></div>
        </div>
        
        <div class="previewBody">
        	<div style="width:300px; float:left; padding:120px 0 0 20px">
			<?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  		<div class="applicationFormEditLink"><strong class="editFormLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			<?php } ?>
            <div class="spacer10 clearFix"></div>
            <p>Please Tick at the appropriate place</p>
            </div>
            <div class="picBox">
		<?php if($profileImage) { ?>
		    <img width="155" height="180" src="<?php echo $profileImage; ?>" border=0 />
		<?php } ?>
	    </div>
            
            <div class="formRows">
                <div class="clearFix spacer10"></div>
                <ul>
                    <li>
                    	<label><span>1.</span>Full Name Mr. /Ms.</label>
                        <div class="previewFieldBox" style="width:745px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$firstName); ?>
				    <?php if($middleName && $middleName!=''){ echo displayFormDataInBoxes(10,' '.$middleName); } else {echo displayFormDataInBoxes(10,'          ');} ?>
				    <?php echo displayFormDataInBoxes(9,' '.$lastName); ?>
                                </tr>
                            </table>
                            <div class="spacer5 clearFix"></div>
                        <div style="float:left; font-size:12px">First Name</div>
                        <div style="padding-left:262px; float:left; font-size:12px">Middle Name</div>
                        <div style="padding-left:170px; float:left; font-size:12px">Surname</div>
                        </div>
                        <div class="clearFix"></div>
                    </li>
                 </ul>
               <div class="clearFix"></div>
            </div>
        	
            <div class="formRows">
                <ul>
			<?php
			list($dobDay,$dobMonth,$dobYear) = explode('/',$dateOfBirth);
			?>
                    <li>
                    	<label style="width:208px"><span>2.</span>Date of Birth (DD-MM-YYYY)</label>
                        <div class="previewFieldBox" style="width:55px">
                            <table width="45" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $dobDay[0];?></td>
                                    <td><?php echo $dobDay[1];?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="previewFieldBox" style="width:55px">
                            <table width="45" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $dobMonth[0];?></td>
                                    <td><?php echo $dobMonth[1];?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="previewFieldBox" style="width:120px">
							<table width="90" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $dobYear[0];?></td>
                                    <td><?php echo $dobYear[1];?></td>
                                    <td><?php echo $dobYear[2];?></td>
                                    <td><?php echo $dobYear[3];?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="clearFix spacer15"></div>
                    </li>
                    
                    <li>
                    	<label><span>3.</span>Mailing Address</label>
                        <div class="previewFieldBox" style="width:745px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php 
					$CFirstString = '';
					if($ChouseNumber) $CFirstString .= $ChouseNumber.' ';
					if($CstreetName) $CFirstString .= $CstreetName.' ';
					if($Carea) $CFirstString .= $Carea.' ';
					if($Ccity) $CFirstString .= $Ccity.' ';
					if($Cstate) $CFirstString .= $Cstate.' ';
					echo displayFormDataInBoxes(31,$CFirstString); 
				     ?>
                                </tr>
                            </table>
                            <div class="spacer15 clearFix"></div>
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(31,substr($CFirstString,31)); ?>	
                                </tr>
                            </table>
                        </div>
                        <div class="spacer15 clearFix"></div>
                        <label><span>&nbsp;</span>PIN Code</label>
                        <div class="previewFieldBox" style="width:148px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(6,$Cpincode); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label><span>&nbsp;</span>Tel No.</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$mobileNumber); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label><span>&nbsp;</span>Fax No:</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$mailFaxCMD); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label style="width:60px"><span>&nbsp;</span>Email:</label>
                        <div class="previewFieldBox2" style="width:500px">
                            <span>&nbsp;<?=$email?></span>
                        </div>
                        <div class="clearFix spacer15"></div>
                    </li>
                    
                    <li>
                    	<label><span>4.</span>Permanent Address</label>
                        <div class="previewFieldBox" style="width:745px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php 
					$FirstString = '';
					if($houseNumber) $FirstString .= $houseNumber.' ';
					if($streetName) $FirstString .= $streetName.' ';
					if($area) $FirstString .= $area.' ';
					if($city) $FirstString .= $city.' ';
					if($state) $FirstString .= $state.' ';
					echo displayFormDataInBoxes(31,$FirstString); 
				     ?>
                                </tr>
                            </table>
                            <div class="spacer15 clearFix"></div>
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(31,substr($FirstString,31)); ?>	
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label><span>&nbsp;</span>Telephone No. Resi.</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$landlineSTDCode.$landlineNumber); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label><span>&nbsp;</span>Off :</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$officePhoneCMD); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label><span>&nbsp;</span>Fax No:</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$officeFaxCMD); ?>
                                </tr>
                            </table>
                        </div>
                        <div class="clearFix spacer15"></div>
                    </li>
                    
                    <li>
                    	<label style="width:170px"><span>5.</span>Father's/Husband's Name</label>
                        <div class="previewFieldBox" style="width:710px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(31,$fatherName); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label style="width:170px"><span>&nbsp;</span>Profession/Business</label>
                        <div class="previewFieldBox" style="width:710px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(31,$fatherOccupation); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label style="width:170px"><span>&nbsp;</span>Designation</label>
                        <div class="previewFieldBox" style="width:600px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(25,$fatherDesignation); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label style="width:170px"><span>&nbsp;</span>Name of Organization</label>
                        <div class="previewFieldBox" style="width:710px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(31,$orgnNameCMD); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label style="width:170px"><span>&nbsp;</span>Office Address</label>
                        <div class="previewFieldBox" style="width:710px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
					<?php echo displayFormDataInBoxes(31,$officeAddressCMD); ?>	
                                </tr>
                            </table>
                            <div class="spacer15 clearFix"></div>
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
					<?php echo displayFormDataInBoxes(31,substr($officeAddressCMD,31)); ?>	
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <div style="width:400px; float:left">
                        <label style="width:65px"><span>&nbsp;</span>Tel No:</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$telNoCMD); ?>
                                </tr>
                            </table>
                        </div>
                        </div>
                        
                        <div style="width:450px; float:right">
                        <label style="width:68px"><span>&nbsp;</span>Fax No:</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$faxNoCMD); ?>
                                   
                                </tr>
                            </table>
                        </div>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label style="width:60px"><span>&nbsp;</span>Email:</label>
                        <div class="previewFieldBox2" style="width:500px">
                            <span>&nbsp;<?=$emailFatherCMD?></span>
                        </div>
                        <div class="clearFix spacer15"></div>
                    </li>
                    
                    <li>
                    	<label style="width:250px; padding:0 25px 0 20px; text-align:right"><span style="text-align:left">Nationality</span> :</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$nationality); ?>
                                </tr>
                            </table>
                        </div>
                        <div class="clearFix"></div>
                    </li>
                    
                    <li>
                    	<label style="width:250px; padding:8px 25px 0 20px; text-align:right"><span style="text-align:left">Are you an NRI ?</span> :</label>
                        <div class="formColumns3" style="width:200px;">
                            <div class="previewFieldBox" style="width:30px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td><?php if($nriCMD=='Yes') echo "<img src='/public/images/onlineforms/institutes/cmd/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
                            <span>Yes</span>
                        </div>
                        
                        <div class="formColumns3" style="width:100px;">
                            <div class="previewFieldBox" style="width:30px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td><?php if($nriCMD=='No') echo "<img src='/public/images/onlineforms/institutes/cmd/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
                            <span>No</span>
                        </div>
                        <div class="clearFix"></div>
                    </li>
                    
                    <li>
                    	<label style="width:250px; padding:0 25px 0 20px; text-align:right"><span style="text-align:left">If yes, in which country you reside ?</span> :</label>
                        <div class="previewFieldBox" style="width:296px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(12,$Ccountry); ?>
                                </tr>
                            </table>
                        </div>
                        <div class="clearFix"></div>
                    </li>
                    
                    <li>
                    	<label style="width:250px; padding:8px 25px 0 20px; text-align:right"><span style="text-align:left">Are you employed ?</span> :</label>
                        <div class="formColumns3" style="width:200px;">
                            <div class="previewFieldBox" style="width:30px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td><?php if($employedCMD=='Yes') echo "<img src='/public/images/onlineforms/institutes/cmd/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
                            <span>Yes</span>
                        </div>
                        
                        <div class="formColumns3" style="width:100px;">
                            <div class="previewFieldBox" style="width:30px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td><?php if($employedCMD=='No') echo "<img src='/public/images/onlineforms/institutes/cmd/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
                            <span>No</span>
                        </div>
                        <div class="clearFix"></div>
                    </li>
                    
			<?php
			list($dobDay,$dobMonth,$dobYear) = explode('/',$sinceWhenWorkingCMD);
			?>
                    <li>
                    	<label style="width:250px; padding:0 25px 0 20px; text-align:right"><span style="text-align:left">If yes, since when ?</span> :</label>
                        <div class="previewFieldBox" style="width:55px">
                            <table width="45" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $dobDay[0];?></td>
                                    <td><?php echo $dobDay[1];?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="previewFieldBox" style="width:55px">
                            <table width="45" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $dobMonth[0];?></td>
                                    <td><?php echo $dobMonth[1];?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="previewFieldBox" style="width:120px">
							<table width="90" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
                                    <td><?php echo $dobYear[0];?></td>
                                    <td><?php echo $dobYear[1];?></td>
                                    <td><?php echo $dobYear[2];?></td>
                                    <td><?php echo $dobYear[3];?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="clearFix"></div>
                    </li>

                    <li>
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
			    $addressCompany = ${'orgnAddressCMD'.$otherSuffix};

			    if($companyName || $designation){ $workExGiven = true; $total++; 
				$nameAddressString = ($addressCompany!='')?$companyName.", ".$addressCompany:$companyName;
			    ?>
                    
                        <?php if($i>0){ ?><div class="spacer10 clearFix"></div><?php } ?>
                    	<label style="width:250px; padding:0 25px 0 20px; text-align:right"><span style="text-align:left">Name and Address of the Organization</span> :</label>
                        <div class="previewFieldBox" style="width:595px;">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(23,$nameAddressString); ?>
                                </tr>
                            </table>
                            <div class="spacer10 clearFix"></div>
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(23,substr($nameAddressString,23)); ?>
                                </tr>
                            </table>
                            <div class="spacer10 clearFix"></div>
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(23,substr($nameAddressString,46)); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer10 clearFix"></div>
                        <label style="width:250px; padding:0 25px 0 20px; text-align:right"><span style="text-align:left">Your Designation</span> :</label>
                        <div class="previewFieldBox" style="width:595px;">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(23,$designation); ?>
                                </tr>
                            </table>
                        </div>
			<?php }} ?>

			<?php 
			      for($i=$total; $i<1; $i++){ ?>
                    	<label style="width:250px; padding:0 25px 0 20px; text-align:right"><span style="text-align:left">Name and Address of the Organization</span> :</label>
                        <div class="previewFieldBox" style="width:595px;">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(23,''); ?>
                                </tr>
                            </table>
                            <div class="spacer10 clearFix"></div>
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(23,''); ?>
                                </tr>
                            </table>
                            <div class="spacer10 clearFix"></div>
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(23,''); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer10 clearFix"></div>
                        <label style="width:250px; padding:0 25px 0 20px; text-align:right"><span style="text-align:left">Your Designation</span> :</label>
                        <div class="previewFieldBox" style="width:595px;">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(23,''); ?>
                                </tr>
                            </table>
                        </div>
			<?php } ?>
                        
                        <div class="spacer10 clearFix"></div>
                        <label style="width:250px; padding:0 25px 0 20px; text-align:right"><span style="text-align:left">Total Experience (No. of yrs.)</span> :</label>
                        <div class="previewFieldBox" style="width:55px">
                            <table width="45" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php echo displayFormDataInBoxes(2,$totalExpCMD); ?>
                                </tr>
                            </table>
                        </div>
                        <div class="spacer10 clearFix"></div>
                        <label style="width:250px; padding:8px 25px 0 20px; text-align:right;"><span style="text-align:left">Do you require Hostel Accommodation ?</span> :</label>
                        <div class="formColumns3" style="width:200px;">
                            <div class="previewFieldBox" style="width:30px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td><?php if($hostelCMD=='Yes') echo "<img src='/public/images/onlineforms/institutes/cmd/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
                            <span>Yes</span>
                        </div>
                        
                        <div class="formColumns3" style="width:100px;">
                            <div class="previewFieldBox" style="width:30px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td><?php if($hostelCMD=='No') echo "<img src='/public/images/onlineforms/institutes/cmd/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
                            <span>No</span>
                        </div>
                        <div class="clearFix"></div>
                    </li>
                    
                    
                    <li>
                    	<label style="width:500px; padding:8px 25px 0 20px; text-align:right;"><span style="text-align:left">Academic Qualification (Tenth Standard onwards):</span> </label>
                        <div class="spacer10 clearFix"></div>
                        <div style="padding-left:20px">
                            <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                                <tr>
                                    <th valign="top" width="170">Examination/Course<br />(Mention Stream of<br />Study) Science, Commerce/<br />Arts/Engg. Pharmacy)</th>
                                    <th valign="top" width="80">Year of<br />Passing</th>
                                    <th valign="top"  width="160">School/<br />College<br />Attended</th>
                                    <th valign="top"  width="180">Board/<br />University</th>
                                    <th valign="top"  width="100">% Of<br />Marks<br />Obtained/Grade</th>
                                    <th valign="top"  width="90">Area of<br />Specialisation/<br />Subjects,<br />If any</th>
                                </tr>
                                
                                <tr>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$class10ExaminationName?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$class10Year?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$class10School?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$class10Board?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class10Percentage?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px"><?=$class10SubjectsCMD?></div></td>
                                </tr>
                                
                                <tr>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$class12ExaminationName?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$class12Year?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$class12School?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$class12Board?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class12Percentage?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px"><?=$class12SubjectsCMD?></div></td>
                                </tr>
                                
                                <tr>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationExaminationName?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:80px">&nbsp;<?=$graduationYear?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$graduationSchool?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$graduationBoard?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationPercentage?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px"><?=$gradSubjectsCMD?></div></td>
                                </tr>

				<?php
				for($i=1;$i<=4;$i++){
					if(${'graduationExaminationName_mul_'.$i}){ 
					?>

					<tr>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:170px">&nbsp;<?=${'graduationExaminationName_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:80px">&nbsp;<?=${'graduationYear_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=${'graduationSchool_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:180px">&nbsp;<?=${'graduationBoard_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?=${'graduationPercentage_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px"><?=${'otherCourseSubject_mul_'.$i}?></div></td>
					</tr>
				<?php }
				} ?>

                            </table>
                            <div class="spacer5 clearFix"></div>
                            <p>(Attested copies of mark sheets of the degree/graduation should be enclosed with the Application From.)</p>
                            
                            <div class="spacer10 clearFix"></div>
                            <div class="formColumns2">
                                <label style="padding-top:2px; width:auto">Special Achievements (If Any)</label>
                                <div class="previewFieldBox2" style="width:600px;">
                                    <span>&nbsp;<?=$acheivementsCMD?></span>
                                </div>
                            </div>
                            
                            <div class="spacer15 clearFix"></div>
                            <div class="formColumns2">
                                <label style="padding-top:2px; width:auto">Extra Curricular Activities/Hobbies</label>
                                <div class="previewFieldBox2" style="width:578px;">
                                    <span>&nbsp;<?=$extraCMD?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearFix"></div>
                    </li>
                    
                    <li style="font-size:14px; font-style:italic; font-weight:bold; padding:20px 0 5px 20px">Kindly Attach a note not exceeding 150 words as to why you want to join the programme of PGDM.
                    <div class="clearFix"></div>
                    </li>
		    <li style="padding:0px 0 10px 20px">
			<div class="previewFieldBox2" style="width:783px;">
			    <span>&nbsp;<?=$whyJoinCMD?></span>
			</div>
            <div class="clearFix"></div>
                    </li>

                    <li class="declaration">
                    	<p>DECLARATION BY APPLICANT</p>
                        <div>
I declare that the particulars given above are correct to the best of my knowledge and belief. I understand that
admission on the basis of incorrect and misleading information shall be cancelled at any stage. I will, on admission,
submit to the rules and discipline of CMD. I hold myself responsible for the dues and prompt payment of fees, if
selected. I have noted that fees once paid are not refundable, under any circumstances.</div>
<div class="clearFix"></div>
                    </li>
                    
                   <li>
                   		<div class="clearFix spacer25"></div>
                        <div class="clearFix spacer25"></div>
                   		<div class="formColumns2" style="padding-left:20px; width:200px;">
                        	<p>
				<?php
                                       if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
                                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                                         }
                                ?>
				</p>
                            <div class="clearFix spacer5"></div>
                            <div>Date:</div>
                        </div>
                        
                        <div class="formColumns2" style="padding-left:50px; width:270px; text-align:center">
                        	<div class="previewFieldBox2" style="width:100%">
                            	<span>&nbsp;<?=$fatherName?></span>
                            </div>
                            <div class="clearFix spacer5"></div>
                            <div>Signature of Father/Guardian</div>
                        </div>
                        
                        <div class="formColumns2" style="padding-left:50px; width:270px; text-align:center">
                        	<div class="previewFieldBox2" style="width:100%">
                            	<span>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
                            </div>
                            <div class="clearFix spacer5"></div>
                            <div>Signature of Applicant</div>
                        </div>
                        <div class="clearFix spacer25"></div>
                   </li>
                    
                 </ul>
               <div class="clearFix"></div>
            </div>
         </div>
    </div>
    <!--CMD Form Preview Ends here-->
