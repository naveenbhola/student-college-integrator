   	<!--VELS Form Preview Starts here-->
	<link href="/public/css/onlineforms/mitsob/MITSOB_styles.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
			      <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  <strong class="editFormLink applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
			      <?php } ?>
    	<div class="previewHeader">
        	<div class="formNumb">
            <p>Form No.: </p> 
            <div style="width:170px" class="previewFieldBox2">
                <span style="padding:0">&nbsp;<?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></span>
            </div>
            </div>
        </div>
        
        <div class="clearFix"></div>
        <div class="form-inst">
            <span>APPLICATION FORM</span><br />
            <strong>POST GRADUATE DIPLOMA IN MANAGEMENT</strong><br />
            <b>2 Year Full Time Programs Approved By A.I.C.T.E.</b>
        </div>
        <div class="clearFix"></div>
        
        <div class="previewBody">
        	<div class="sections">
            	<div class="courses">
                	<ul>
                    	<li>PGDM</li>
                    	<li>PGDM – Human Resources</li>    
                    </ul>
                    <ul class="courses-items">
                    	<li>PGDM – Finance</li>
                        <li>PGDM – Marketing</li>
                    </ul>
                </div>
                <div class="spacer10 clearFix"></div>
                <div class="instructions">
                <strong>Important Instructions :</strong>
                <ul>
                	<li>Form should be filled in by candidate only in clean legible handwriting.</li>
                    <li>Form should be filled in block letters only (except e-mail). Leave one block between each word. </li>
                    <li>Application should be submitted, complete in all aspects along with the application fees of Rs. 900/- by cash or Demand Draft Favoring MITSOB, payable at Pune.</li>
                    <li>Incomplete forms shall not be accepted.</li>
                    <li>Please mention correct address on envelope (as indicated on last page of this form)</li>
                </ul>
                </div>
                
                <div class="picBox">
			<?php if($profileImage) { ?>
				<img width="195" height="217" src="<?php echo $profileImage; ?>" />
			<?php } ?>
		</div>
            </div>
        
        	<div class="sections">
            	
                <div class="formRows">
            		<ul>
                    	<li class="name-block">
                        	<label>Name</label>
                            <label style="width:278px">Middle Name</label>
                            <label class="last">Surname</label>
                            <div class="previewFieldBox">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
						<?php
						$name = $firstName;
						if($middleName) { $name .= ' '.$middleName; }
						if($lastName) { $name .= ' '.$lastName; }
						for($i=0;$i<26;$i++) {
						?>	
                            <td><?php echo displayDataInForm($name[$i]); ?></td>
						<?php
						}
						?>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="name-note">(As per the previous statement of marks)</div>
                        </li>
                        <li>
                        	<label>Mothers Name</label>
                            <div class="previewFieldBox" style="width:450px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<?php echo displayFormDataInBoxes(16,$MotherName); ?>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        
			<?php list($dd,$mm,$yy) = explode('/',$dateOfBirth); ?>
                        <li>
                        	<div class="form-left">
                        	<label>Date of Birth</label>
                            <div class="previewFieldBox" style="width:60px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<td><?php echo $dd[0]; ?></td>
					<td><?php echo $dd[1]; ?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox" style="width:60px; margin-left:20px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<td><?php echo $mm[0]; ?></td>
					<td><?php echo $mm[1]; ?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox" style="width:120px; margin-left:20px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<td><?php echo $yy[0]; ?></td>
					<td><?php echo $yy[1]; ?></td>
					<td><?php echo $yy[2]; ?></td>
					<td><?php echo $yy[3]; ?></td>
                                    </tr>
                                </table>
                            </div>
                            </div>
                            
                            <div class="form-right">
                            	<div style="width:140px; float:left">
                                    <label>Age</label>
                                    <div class="previewFieldBox" style="width:60px">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                            <tr>
                                                <td><?php echo $ageMITSOB[0];?></td>
                                                <td><?php echo $ageMITSOB[1];?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div style="width:120px; float:left">
                                    <label>Yrs.</label>
                                    <div class="previewFieldBox" style="width:30px; padding-top:6px"></div>
                                </div>
                            </div>
                        </li>
                        
                        <li>
                        	<div class="form-left">
                        	
                            <div style="width:170px; float:left">
                            <label style="width:60px">Sex:</label>
                            <div class="previewFieldBox" style="width:80px;">
                            	<label>Male</label>
                                <div class="previewFieldBox" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td><?php if($gender=='MALE') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                            </div>
                            
                           <div class="previewFieldBox" style="width:100px;">
                            	<label>Female</label>
                                <div class="previewFieldBox" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td><?php if($gender=='FEMALE') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                           </div>
                           
                           <div class="form-right">
                           	<div style="width:220px; float:left">
                            <label style="width:105px">Marital Status:</label>
                            <div class="previewFieldBox" style="width:92px;">
                            	<label>Married</label>
                                <div class="previewFieldBox" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td><?php if($maritalStatus=='MARRIED') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                            </div>
                            
                           <div class="previewFieldBox" style="width:120px;">
                            	<label>Unmarried</label>
                                <div class="previewFieldBox" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td><?php if($maritalStatus=='SINGLE') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                           </div>
                        </li>
                        
                        <li>
                        	<div class="form-left">
                        		<label>Nationality</label>
                            	<div class="previewFieldBox" style="width:260px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <?php echo displayFormDataInBoxes(8,$nationality); ?>
                                        </tr>
                                    </table>
                                </div>
                           </div>
                           
                           <div class="form-right">
                           	<label style="width:53px">Religion</label>
                            	<div class="previewFieldBox" style="width:362px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <?php echo displayFormDataInBoxes(14,$religion); ?>
                                        </tr>
                                    </table>
                                </div>
                           </div>
                        </li>
                        
                        <li>
                        	<div class="form-left">
                        		<label>Category</label>
                            	<div class="previewFieldBox" style="width:260px;">
                                    &nbsp;
                                </div>
                           </div>
                           
                           <div class="form-right">
                           	<label style="width:53px">Caste</label>
                            	<div class="previewFieldBox" style="width:362px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <?php echo displayFormDataInBoxes(14,$casteMITSOB); ?>
                                        </tr>
                                    </table>
                                </div>
                           </div>
                           <div class="clearFix spacer10"></div>
                           <div style="width:100px; float:left">
                            	<label>Open</label>
                                <div class="previewFieldBox" style="width:32px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?php if($categoryMITSOB=='Open') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                        </tr>
                                    </table>
                            	</div>
                            </div>
                            
                            <div style="width:75px; float:left">
                            	<label>SC</label>
                                <div class="previewFieldBox" style="width:32px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?php if($categoryMITSOB=='SC') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                        </tr>
                                    </table>
                            	</div>
                            </div>
                            
                            <div style="width:75px; float:left">
                            	<label>ST</label>
                                <div class="previewFieldBox" style="width:32px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?php if($categoryMITSOB=='ST') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                        </tr>
                                    </table>
                            	</div>
                            </div>
                            
                            <div style="width:75px; float:left">
                            	<label>NT</label>
                                <div class="previewFieldBox" style="width:32px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?php if($categoryMITSOB=='NT') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                        </tr>
                                    </table>
                            	</div>
                            </div>
                            
                            <div style="width:90px; float:left">
                            	<label>OBC</label>
                                <div class="previewFieldBox" style="width:32px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?php if($categoryMITSOB=='OBC') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                        </tr>
                                    </table>
                            	</div>
                            </div>
                            
                            <div style="width:110px; float:left">
                            	<label>OTHERS</label>
                                <div class="previewFieldBox" style="width:32px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?php if($categoryMITSOB=='Others') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0/>'; else echo '&nbsp;';?></td>
                                        </tr>
                                    </table>
                            	</div>
                            </div>
                        </li>
                        
                        <li>
							<label>Email (Compulsory):</label>
                            <div class="previewFieldBox2" style="width:400px;">
                            	<span>&nbsp;<?=$email?></span>
							</div>
                        </li>
                        
                        <li>
                        	<div class="form-left" style="width:410px">
                        		<label>Mobile No.</label>
                            	<div class="previewFieldBox" style="width:290px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <?php echo displayFormDataInBoxes(10,$mobileNumber); ?>
                                        </tr>
                                    </table>
                                </div>
                           </div>
                           
                           <div class="form-right" style="width:436px">
                           	<label style="width:155px;padding-right:5px;">Emergency Contact No. <br /><span>(Parent, Local Guardian)</span></label>
                            	<div class="previewFieldBox" style="width:270px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <?php echo displayFormDataInBoxes(10,$emergencyMITSOB); ?>
                                        </tr>
                                    </table>
                                </div>
                           </div>
                        </li>
                        
                        <li>
                        	<label>Correspondence Address</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php
					  $Caddress = $ChouseNumber;
					  if($CstreetName) $Caddress .= ', '.$CstreetName;
					  if($Carea) $Caddress .= ', '.$Carea;
					  ?>
					  <?php echo displayFormDataInBoxes(28,substr($Caddress,0,28)); ?>
                                    </tr>
                                    <tr><td colspan="28" style="border-left:solid 1px #fff; border-right:solid 1px #fff; height:20px"> </td></tr>
                                    <tr>
					  <?php echo displayFormDataInBoxes(28,substr($Caddress,28,28)); ?>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        
                        <li>
                        	<div class="form-left" style="width:606px">
                        	<label>City</label>
                        	<div class="previewFieldBox" style="width:555px">
                            	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php echo displayFormDataInBoxes(19,$Ccity); ?>
                                    </tr>
                                 </table>
                            </div>
                            </div>
                            
                            <div class="form-right" style="width:240px">
                        	<label>Pin Code</label>
                        	<div class="previewFieldBox" style="width:171px">
                            	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php echo displayFormDataInBoxes(6,$Cpincode); ?>
                                    </tr>
                                 </table>
                            </div>
                            </div>
                        </li>
                        
                        <li>
                        	<div class="form-left" style="width:410px">
                        	<label>State</label>
                        	<div class="previewFieldBox" style="width:350px">
                            	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php echo displayFormDataInBoxes(13,$Cstate); ?>
                                    </tr>
                                 </table>
                            </div>
                            </div>
                            
                            <div class="form-right" style="width:435px">
                        	<label>Phone (With STD Code)</label>
                        	<div class="previewFieldBox" style="width:267px">
                            	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php $phoneNumber = ($landlineSTDCode=='')?$landlineNumber:$landlineSTDCode."-".$landlineNumber;?>
					  <?php echo displayFormDataInBoxes(11,$phoneNumber); ?>
                                    </tr>
                                 </table>
                            </div>
                            </div>
                        </li>
                        
                         <li style="padding-top:50px;">
                        	<label>Permanent Address (<strong>Post Selection communication will be sent to this address</strong>)</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php
					  $address = $houseNumber;
					  if($streetName) $address .= ', '.$streetName;
					  if($area) $address .= ', '.$area;
					  ?>
					  <?php echo displayFormDataInBoxes(28,substr($address,0,28)); ?>
                                    </tr>
                                    <tr><td colspan="28" style="border-left:solid 1px #fff; border-right:solid 1px #fff; height:20px"> </td></tr>
                                    <tr>
					  <?php echo displayFormDataInBoxes(28,substr($address,28,28)); ?>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        
                        <li>
                        	<div class="form-left" style="width:606px">
                        	<label>City</label>
                        	<div class="previewFieldBox" style="width:555px">
                            	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php echo displayFormDataInBoxes(19,$city); ?>
                                    </tr>
                                 </table>
                            </div>
                            </div>
                            
                            <div class="form-right" style="width:240px">
                        	<label>Pin Code</label>
                        	<div class="previewFieldBox" style="width:171px">
                            	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php echo displayFormDataInBoxes(6,$pincode); ?>
                                    </tr>
                                 </table>
                            </div>
                            </div>
                        </li>
                        
                        <li>
                        	<div class="form-left" style="width:410px">
                        	<label>State</label>
                        	<div class="previewFieldBox" style="width:350px">
                            	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php echo displayFormDataInBoxes(13,$state); ?>
                                    </tr>
                                 </table>
                            </div>
                            </div>
                            
                            <div class="form-right" style="width:435px">
                        	<label>Phone (With STD Code)</label>
                        	<div class="previewFieldBox" style="width:267px">
                            	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php $phoneNumber = ($landlineSTDCode=='')?$landlineNumber:$landlineSTDCode."-".$landlineNumber;?>
					  <?php echo displayFormDataInBoxes(11,$phoneNumber); ?>
                                    </tr>
                                 </table>
                            </div>
                            </div>
                        </li>
                    </ul>
               <div class="clearFix"></div>
            </div>
            </div>
            
        	<div class="sections">
            	
                <div class="formRows">
            		<ul>
                    	<li>
                        	<label><strong>Occupation of Parents (In brief)</strong></label>
                            <div class="spacer15 clearFix"></div>
                            <div class="">
                                <table width="100%" cellpadding="10" cellspacing="0" border="1" bordercolor="#000000" class="educationTable2">
                                    <tr>
                                        <th valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:180px">Education</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:180px">Occupation</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:180px">Annual Income</div></th>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">Father</div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$fatherEduMITSOB?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$fatherOccupation?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$fatherIncomeMITSOB?></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">Mother</div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$motherEduMITSOB?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$MotherOccupation?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$motherIncomeMITSOB?></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">Brother / Sister</div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$broEduMITSOB?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$broOccupationMITSOB?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:180px">&nbsp;<?=$broIncomeMITSOB?></div></td>
                                    </tr>
                                </table>
                            </div>
                        </li>
                    </ul>
               <div class="clearFix"></div>
            </div>
            </div>

            <div class="sections">
            	<strong>How did you know about the course?</strong>
                <div class="spacer20 clearFix"></div>
            	<ul class="know-lists">
                	<li><p>Through press advertisement (Newspaper, Magzine)</p> <div class="previewFieldBox2" style="width:300px; float:left"><span style="padding-top:0px">&nbsp;<?=$pressAdMITSOB?></span></div></li>
                    <li><p>You visit to the Institute</p> <?php if($instituteVisitMITSOB=='1') echo "<img border=0 src='/public/images/onlineforms/institutes/mitsob/tick-icn.gif' />";?></li>
                    <li><p>Word of mouth (Please Specify)</p> <div class="previewFieldBox2" style="width:300px; float:left"><span style="padding-top:0px">&nbsp;<?=$mouthMITSOB?></span></div></li>
                    <li><p>Website</p> <div class="previewFieldBox2" style="width:300px; float:left"><span style="padding-top:0px">&nbsp;<?=$websiteMITSOB?></span></div></li>
                    <li><p>Others (Please Specify)</p> <div class="previewFieldBox2" style="width:300px; float:left"><span style="padding-top:0px">&nbsp;<?=$otherMITSOB?></span></div></li>
                </ul>
            </div>
            
            <div class="sections">
            	<strong>Why would you choose to study at MIT – SOB? What are your career plans ?<br />(Max. 50 words)</strong>
                <div class="clearFix spacer10"></div>
		<?php $wordLimit = 122; ?>
            	<div style="border:1px solid #333; border-radius:15px; padding:20px; *height:0.01%">
                	<div class="previewFieldBox2"><span>&nbsp;<?php echo substr($chooseMITSOB,0,$wordLimit); ?></span></div>
                    <div class="clearFix spacer20"></div>
                    <div class="previewFieldBox2"><span>&nbsp;<?php echo substr($chooseMITSOB,$wordLimit,$wordLimit); ?></span></div>
                    <div class="clearFix spacer20"></div>
                    <div class="previewFieldBox2"><span>&nbsp;<?php echo substr($chooseMITSOB,$wordLimit*2,$wordLimit); ?></span></div>
                    <div class="clearFix spacer20"></div>
                    <div class="previewFieldBox2"><span>&nbsp;<?php echo substr($chooseMITSOB,$wordLimit*3,$wordLimit); ?></span></div>
                    <div class="clearFix spacer20"></div>
                    <div class="previewFieldBox2"><span>&nbsp;<?php echo substr($chooseMITSOB,$wordLimit*4,$wordLimit); ?></span></div>
                    <div class="clearFix spacer20"></div>
                    <div class="previewFieldBox2"><span>&nbsp;<?php echo substr($chooseMITSOB,$wordLimit*5,$wordLimit); ?></span></div>
                    <div class="clearFix spacer20"></div>
                    <div class="previewFieldBox2"><span>&nbsp;<?php echo substr($chooseMITSOB,$wordLimit*6,$wordLimit); ?></span></div>
                    <div class="clearFix spacer20"></div>
                    <div class="clearFix"></div>
                </div>
            </div>
            
        	<div class="sections">
            	<div class="formRows">
            		<ul>
                    	<li>
                        	<label><strong>Academic Details</strong></label>
                            <div class="spacer15 clearFix"></div>
                            <div class="">
                                <table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#000000" class="educationTable2">
                                    <tr>
                                        <th rowspan="2" valign="middle" width="170"><div class="formWordWrapper" style="width:170px">Qualification</div></th>
                                        <th rowspan="2" valign="middle" width="170"><div class="formWordWrapper" style="width:170px">Name of<br />University/ Board</div></th>
                                        <th rowspan="2" valign="middle" width="170"><div class="formWordWrapper" style="width:170px">Name of<br />Institution/College</div></th>
                                        <th colspan="2" valign="top" width="120"><div class="formWordWrapper" style="width:120px">Marks</div></th>
                                        <th rowspan="2" valign="middle" width="100"><div class="formWordWrapper" style="width:100px">Year of <br />Passing</div></th>
                                    </tr>
                                    
                                    <tr>
                                        <th width="65"><div class="formWordWrapper">Obtained</div></th>
                                        <th width="65"><div class="formWordWrapper">Out of</div></th>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>10<sup>th</sup> Std.</strong></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$class10Board?></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$class10School?></div></td>										
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$class10MarksMITSOB?></div></td>
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$class10TotalMarksMITSOB?></div></td>
                                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class10Year?></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>12<sup>th</sup> Std.</strong></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$class12Board?></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$class12School?></div></td>										
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$class12MarksMITSOB?></div></td>
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$class12TotalMarksMITSOB?></div></td>
                                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$class12Year?></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>Graduation / Degree <br />(Ist Year)</strong></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationBoard?></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationSchool?></div></td>										
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear1MarksMITSOB?></div></td>
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear1TotalMarksMITSOB?></div></td>
                                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationYear?></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>Graduation / Degree <br />(2nd Year)</strong></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationBoard?></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationSchool?></div></td>										
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear2MarksMITSOB?></div></td>
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear2TotalMarksMITSOB?></div></td>
                                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationYear?></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>Graduation / Degree <br />(3rd Year)</strong></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationBoard?></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationSchool?></div></td>										
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear3MarksMITSOB?></div></td>
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear3TotalMarksMITSOB?></div></td>
                                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationYear?></div></td>
                                    </tr>
                                    
				    <?php if(isset($gradYear4MarksMITSOB) && $gradYear4MarksMITSOB!=''){ ?>
                                    <tr>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>Graduation / Degree <br />(4th Year)</strong></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationBoard?></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationSchool?></div></td>										
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear4MarksMITSOB?></div></td>
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear4TotalMarksMITSOB?></div></td>
                                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationYear?></div></td>
                                    </tr>
				    <?php } ?>

				    <?php if(isset($gradYear5MarksMITSOB) && $gradYear5MarksMITSOB!=''){ ?>
                                    <tr>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>Graduation / Degree <br />(4th Year)</strong></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationBoard?></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=$graduationSchool?></div></td>										
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear5MarksMITSOB?></div></td>
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=$gradYear5TotalMarksMITSOB?></div></td>
                                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?=$graduationYear?></div></td>
                                    </tr>
				    <?php } ?>

				    <!-- Block to show PG course/Other courses row if it is available -->
				    <?php
				    $otherCourseShown = false;
				    $countOfPGCourses = 0;
				    for($i=1;$i<=4;$i++){ 
					    if(${'graduationExaminationName_mul_'.$i}){ $countOfPGCourses++; 
					    ?>
					    <tr>
						<?php if( ${'isPG_mul_'.$i} == '1' ){ ?>
						    <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>Post Graduation<br/>(Degree if any)</strong></div></td>
						<?php }else{ $otherCourseShown = true;?>
						    <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>Other course<br/>(If any)</strong></div></td>
						<?php } ?>
						<td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=${'graduationBoard_mul_'.$i}?></div></td>
						<td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;<?=${'graduationSchool_mul_'.$i}?></div></td>										
						<td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=${'otherCourseMarks_mul_'.$i}?></div></td>
						<td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;<?=${'otherCoursetMarks_mul_'.$i}?></div></td>
						<td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?=${'graduationYear_mul_'.$i}?></div></td>

					    </tr>
				    <?php }
				    } ?>
				    <!-- Block End to show PG course/Other courses row -->

				    <?php if($countOfPGCourses==0){ ?>
                                    <tr>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px"><strong>Post Graduation <br />(Degree if any)</strong></div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;</div></td>
                                        <td valign="top" width="170"><div class="formWordWrapper" style="width:170px">&nbsp;</div></td>										
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;</div></td>
                                        <td valign="top" width="60"><div class="formWordWrapper" style="width:65px">&nbsp;</div></td>
                                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                    </tr>
				    <?php } ?>
                                      
                                </table>
                            </div>
                        </li>
                    </ul>
               <div class="clearFix"></div>
            </div>
            </div>
            
            <div class="sections">
            	<div class="formRows">
            		<ul>
                    	<li>
                   			<label><strong>Details of appearance at CAT/ MAT/ CET/ XAT/ ATMA (Enclose Score Card Copy)</strong></label>
                            <div class="clearFix spacer20"></div>
                            
                            <div class="apprearance-details">
                            	<label>Exam</label>
                                <div class="previewFieldBox" style="width:120px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?=$examMITSOB?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="apprearance-details">
                            	<label>Year</label>
                                <div class="previewFieldBox" style="width:120px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?=$yearEntranceMITSOB?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="apprearance-details">
                            	<label>Score</label>
                                <div class="previewFieldBox" style="width:120px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?=$scoreMITSOB?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="apprearance-details" style="width:220px">
                            	<label>Percentile</label>
                                <div class="previewFieldBox" style="width:120px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;<?=$percentileMITSOB?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                        </li>
                        
                        <li>
                        	<div class="course-option-title"><strong>CHOICE OF COURSE </strong>(Compulsory)</div>
                            <div class="course-option-block">
                            	<div class="opt-col1"><strong>Course</strong></div>
                                <div class="opt-col2"><strong>Applied</strong><br />(To be filled by Candidate)</div>
                                <div class="opt-col3"><strong>Alloted</strong><br />(Office use only)</div>
                                <div class="clearFix"></div>
                            </div>
                            
                            <div class="course-option-block2">
                            	<div class="opt-col1"><strong>PGDM</strong></div>
                                <div class="opt-col2"><div class="check-box">
				    <?php
					if($programMITSOB1=='PGDM') echo "1";
					else if($programMITSOB2=='PGDM') echo "2";
					else if($programMITSOB3=='PGDM') echo "3";
					else if($programMITSOB4=='PGDM') echo "4";
				    ?>
				</div></div>
                                <div class="opt-col3"><div class="check-box"></div></div>
                            </div>
                            
                            <div class="course-option-block2">
                            	<div class="opt-col1"><strong>PGDM - </strong> Marketing</div>
                                <div class="opt-col2"><div class="check-box">
				    <?php
					if(strpos($programMITSOB1,'Marketing')!==false) echo "1";
					else if(strpos($programMITSOB2,'Marketing')!==false) echo "2";
					else if(strpos($programMITSOB3,'Marketing')!==false) echo "3";
					else if(strpos($programMITSOB4,'Marketing')!==false) echo "4";
				    ?>
				</div></div>
                                <div class="opt-col3"><div class="check-box"></div></div>
                            </div>
                            
                            <div class="course-option-block2">
                            	<div class="opt-col1"><strong>PGDM - </strong> Finance</div>
                                <div class="opt-col2"><div class="check-box">
				    <?php
					if(strpos($programMITSOB1,'Finance')!==false) echo "1";
					else if(strpos($programMITSOB2,'Finance')!==false) echo "2";
					else if(strpos($programMITSOB3,'Finance')!==false) echo "3";
					else if(strpos($programMITSOB4,'Finance')!==false) echo "4";
				    ?>
				</div></div>
                                <div class="opt-col3"><div class="check-box"></div></div>
                            </div>
                            
                            <div class="course-option-block2">
                            	<div class="opt-col1"><strong>PGDM - </strong> Human Resources</div>
                                <div class="opt-col2"><div class="check-box">
				    <?php
					if(strpos($programMITSOB1,'Human')!==false) echo "1";
					else if(strpos($programMITSOB2,'Human')!==false) echo "2";
					else if(strpos($programMITSOB3,'Human')!==false) echo "3";
					else if(strpos($programMITSOB4,'Human')!==false) echo "4";
				    ?>
				</div></div>
                                <div class="opt-col3"><div class="check-box"></div></div>
                            </div>
                            <div class="clearFix"></div>
                            <div style="color:#F00; padding-top:20px; font-weight:normal">
                            	* Candidate can choose minimum 1 and up to 4 courses in order of preference e.g. '1' for first preference, '2' for second preference and '3' for third preference & '4' for fourth preference. Total score obtained by candidate will be compared against score required for allotment of course, taking into account preferences mentioned above.
                            </div>
                        </li>
                     </ul>
                 </div>
             </div>
			
            <?php $name = ($middleName=='')?$firstName." ".$lastName:$firstName." ".$middleName." ".$lastName;?>
            <div class="sections">
            	<div class="formRows">
            		<ul>
                    	<li class="admit-card">
                        	<div class="admit-card-left">
                            	<h5>POST GRADUATE DIPLOMA IN MANAGEMENT<br />
                                <strong>ADMIT CARD</strong> (Office Copy)
								</h5>
                                <ul class="admit-rows">
                                	<li>
                                    	<label>Name of Candidate:</label>
                                        <div class="previewFieldBox2" style="width:255px"><span>&nbsp;<?php echo substr($name,0,20);?></span></div>
                                        <div class="spacer15 clearFix"></div>
                                        <div class="previewFieldBox2" style="width:389px"><span>&nbsp;<?php echo substr($name,20);?></span></div>
                                    </li>
                                    
                                    <li>
                                    	<label>Date of Examination:</label>
                                        <div class="previewFieldBox2" style="width:248px"><span>&nbsp;</span></div>
                                    </li>
                                    
                                    <li>
                                    	<label>Verified by:</label>
                                        <div class="previewFieldBox2" style="width:307px"><span>&nbsp;</span></div>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="admit-card-right">
                            	<div class="logo-cont"><img src="/public/images/onlineforms/institutes/mitsob/logo.jpg" alt="" /></div>
                                <div class="card-details">
                                	<h5>MIT SCHOOL OF BUSINESS<br />
                                    <span>(APPROVED BY A.I.C.T.E)</span><br />
									<b>POST GRADUATE DIPLOMA IN MANAGEMENT</b><br />
									<strong>ADMIT CARD</strong>
									</h5>
                                </div>
                                <div class="clearFix spacer10"></div>
                                <div style="width:280px; float:left">
                                	<ul class="admit-rows">
                                	<li>
                                    	<label>Name:</label>
                                        <div class="previewFieldBox2" style="width:220px"><span>&nbsp;<?php echo substr($name,0,20);?></span></div>
                                        <div class="spacer15 clearFix"></div>
                                        <div class="previewFieldBox2" style="width:270px"><span>&nbsp;<?php echo substr($name,20);?></span></div>
                                    </li>
                                    
                                    <li>
                                    	<label>Date of Exam:</label>
                                        <div class="previewFieldBox2" style="width:170px"><span>&nbsp;</span></div>
                                    </li>
                                    
                                    <li>
                                    	<label>Students Signature:</label>
                                        <div class="previewFieldBox2" style="width:133px"><span>&nbsp;<?php echo substr($name,0,15);?></span></div>
                                    </li>
                                    <li>
                                    	<label>Roll No.:</label>
                                        <div class="previewFieldBox2" style="width:203px"><span>&nbsp;</span></div>
                                    </li>
                                    
                                </ul>
                                </div>
                                <div class="card-pic">
				  <?php if($profileImage) { ?>
					  <img width="195" height="217" src="<?php echo $profileImage; ?>" />
				  <?php }?>
				</div>
                                <div class="clearFix"></div>
                                <div>
                                    <label>Authorised sign.</label>
                                    <div class="previewFieldBox2" style="width:310px"><span>&nbsp;</span></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="sections">
            	<table width="100%" cellpadding="8" cellspacing="0" border="1" bordercolor="#000000" class="educationTable3">
                    <tr>
                        <th valign="top" width="190"><div class="formWordWrapper" style="width:190px">position</div></td>
                        <th valign="top" width="190"><div class="formWordWrapper" style="width:190px">organisation</div></th>
                        <th valign="top" width="180"><div class="formWordWrapper" style="width:180px">Location</div></th>										
                        <th valign="top" width="100"><div class="formWordWrapper" style="width:100px">From</div></th>
                        <th valign="top" width="100"><div class="formWordWrapper" style="width:100px">To</div></th>
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
		      $location = ${'weLocation'.$mulSuffix};

		      if($companyName || $designation){ $workExGiven = true; $total++; ?>

                    <tr>
                        <td valign="top" width="190"><div class="formWordWrapper" style="width:190px">&nbsp;<?php echo $designation; ?></div></td>
                        <td valign="top" width="190"><div class="formWordWrapper" style="width:190px">&nbsp;<?php echo $companyName; ?></div></td>
                        <td valign="top" width="180"><div class="formWordWrapper" style="width:180px">&nbsp;<?php echo $location; ?></div></td>										
                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?php echo $durationFrom;?></div></td>
                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;<?php echo $durationTo;?></div></td>
                    </tr>
	    <?php }} ?>

	    <?php for($j=$total; $j<2; $j++){ ?>
		    <tr>
                        <td valign="top" width="190"><div class="formWordWrapper" style="width:190px">&nbsp;</div></td>
                        <td valign="top" width="190"><div class="formWordWrapper" style="width:190px">&nbsp;</div></td>
                        <td valign="top" width="180"><div class="formWordWrapper" style="width:180px">&nbsp;</div></td>										
                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                        <td valign="top" width="100"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                    </tr>
	      <?php } ?>

                </table>
            </div>
             
             <div class="sections">
             	<div class="declaration-col">
                	<div class="declaration-title">Declaration:</div>
                    <p>I have read and understood details of PGDM Program mentioned in institutes brochure. I hereby declare that all statements made in this application is true, complete and correct. In the event of any information being found false or incorrect or ineligibility being detected before or after selection, action may be taken by the Institute as deemed fit against me.</p>
                    <div class="spacer15 clearFix"></div>
                    <div style="width:190px; float:left; padding:10px 10px 0 0">
                        <span class="flLt" style="padding:5px 3px 0 0">Date : </span>
                        <div class="previewFieldBox2" style="width:140px">
			<span>&nbsp;
					<?php
					      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
						      echo date("d/m/Y", strtotime($paymentDetails['date']));
						}
					?>
			</span></div>
                    </div>
                    
                    <div style="width:225px; float:left;">
                    	<div>&nbsp;<?=$name?></div>
                        <span>Signature of the Candidate</span>
                    </div>
                </div>

			  <?php if(is_array($paymentDetails)){
			      if($paymentDetails['mode']=='Offline'){
			      $mode = 'Draft';
			      $draftNumber = $paymentDetails['draftNumber'];
			      $draftDate = date("d/m/Y", strtotime($paymentDetails['draftDate']));
			  }else if($paymentDetails['mode']=='Online'){
			      $mode = 'Cash';
			      $draftNumber = '';
			      $draftDate = date("d/m/Y", strtotime($paymentDetails['date']));
			  }} ?>
                
                <div class="pay-col">
                	<div class="declaration-title">Mode of Payment:</div>
                    <ul>
                        <li><label>Cash</label>
                        	<div class="pay-details">
                        		<div class="check-box-small"><?php if($mode=='Cash') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0 />';?></div>
                            	<div style="float:left; padding-left:30px">
                                	<span class="flLt" style="padding:5px 12px 0 0">Demand Draft</span> 
                                    <div class="check-box-small"><?php if($mode=='Draft') echo '<img src="/public/images/onlineforms/institutes/mitsob/tick-icn.gif" border=0 />';?></div>
                                </div>
                        	</div>
                        </li>
                        
                        <li><label>DD No.</label>
                        	<div class="pay-details">
                                	<div class="previewFieldBox" style="width:95%">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <?php echo displayFormDataInBoxes(6,$draftNumber); ?>
                                    </tr>
                                </table>
                            </div>
                        	</div>
                        </li>
                        
                        <li><label>Receipt No.</label>
                        	<div class="pay-details">
                                	<div class="previewFieldBox" style="width:95%">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        	</div>
                        </li>
                        
                        <li><label style="width:auto; padding-right:10px">Accounts <br />Signature</label>
                            	<div class="previewFieldBox2" style="width:280px; margin-top:15px; padding-top:3px"><span>&nbsp;</span></div>
                        </li>
                    </ul>
                </div>
                
             </div>
             
             <div class="sections">
             	<div class="declaration-col">
                	<div class="declaration-title">Please attach copies of the following documents</div>
                    <div class="spacer15 clearFix"></div>
                    
                    <div class="doc-attached">
                    	<strong>Document <span>(Attested)</span></strong>
                    </div>
                    <div class="remarks">
                    	<strong>Remarks</strong>
                    </div>
                    <div class="spacer15 clearFix"></div>
                    <div class="doc-attached">
                    	<label>Photocopy of 10th Std. Mark Sheet</label>
                    </div>
                    <div class="remarks">
                    	<div class="remark-options">
                        	<div class="check-option"></div>
                            <span>Yes</span>
                        </div>
                        <div class="remark-options" style="float:right">
                        	<div class="check-option"></div>
                            <span>No</span>
                        </div>
                    </div>
                    
                    <div class="spacer15 clearFix"></div>
                    <div class="doc-attached">
                    	<label>Photocopy of 12th Std. Marksheet</label>
                    </div>
                    <div class="remarks">
                    	<div class="remark-options">
                        	<div class="check-option"></div>
                            <span>Yes</span>
                        </div>
                        <div class="remark-options" style="float:right">
                        	<div class="check-option"></div>
                            <span>No</span>
                        </div>
                    </div>
                    
                    <div class="spacer15 clearFix"></div>
                    <div class="doc-attached">
                    	<label>Photocopy of Graduation Marksheet </label>
                    </div>
                    <div class="remarks">
                    	<div class="remark-options">
                        	<div class="check-option"></div>
                            <span>Yes</span>
                        </div>
                        <div class="remark-options" style="float:right">
                        	<div class="check-option"></div>
                            <span>No</span>
                        </div>
                    </div>
                    
                    <div class="spacer15 clearFix"></div>
                    <div class="doc-attached">
                    	<label>Photocopy of Post Graduation Marksheet </label>
                    </div>
                    <div class="remarks">
                    	<div class="remark-options">
                        	<div class="check-option"></div>
                            <span>Yes</span>
                        </div>
                        <div class="remark-options" style="float:right">
                        	<div class="check-option"></div>
                            <span>No</span>
                        </div>
                    </div>
                    
                    <div class="spacer15 clearFix"></div>
                    <div class="doc-attached">
                    	<label>Photocopy of Work Experience (If Applicable)</label>
                    </div>
                    <div class="remarks">
                    	<div class="remark-options">
                        	<div class="check-option"></div>
                            <span>Yes</span>
                        </div>
                        <div class="remark-options" style="float:right">
                        	<div class="check-option"></div>
                            <span>No</span>
                        </div>
                    </div>
                    
                    <div class="spacer15 clearFix"></div>
                    <div class="doc-attached">
                    	<label>Photocopy of MAT/ CAT/ MS-CET Scorecard</label>
                    </div>
                    <div class="remarks">
                    	<div class="remark-options">
                        	<div class="check-option"></div>
                            <span>Yes</span>
                        </div>
                        <div class="remark-options" style="float:right">
                        	<div class="check-option"></div>
                            <span>No</span>
                        </div>
                    </div>
                    
                    <div class="spacer15 clearFix"></div>
                    <div class="doc-attached">
                    	<label>Photocopy of Identity Proof (Compulsory)</label>
                    </div>
                    <div class="remarks">
                    	<div class="remark-options">
                        	<div class="check-option"></div>
                            <span>Yes</span>
                        </div>
                        <div class="remark-options" style="float:right">
                        	<div class="check-option"></div>
                            <span>No</span>
                        </div>
                    </div>
                </div>
                
                <div class="pay-col">
                	<div class="declaration-title">For Office Use Only</div>
                    <ul>
                    	<li style="padding-top:20px"><label style="width:90%">Date of Written Exam, Extempore & PI</label>
                        <div class="clearFix spacer5"></div>
                        	<div class="pay-details" style="width:100%">
                              <div class="previewFieldBox" style="width:60px; padding-right:12px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                            	<div class="previewFieldBox" style="width:60px; padding-right:12px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox" style="width:120px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        	</div>
                        </li>
                    	
                        <li style="padding-top:30px">
                        	<div class="declaration-title">CBT (Computer based test)</div>
                            <div class="clearFix"></div>
                            <div class="formColumns" style="width:100px">
                                <label style="width:auto; padding-right:10px">Yes</label> 
                                <div class="check-box-small"></div>
                            </div>
                            
                            <div class="formColumns" style="width:100px">
                                <label style="width:auto; padding-right:10px">No</label> 
                                <div class="check-box-small"></div>
                            </div>
                        </li>
                        
                        <li>
                        	<div class="spacer10 clearFix"></div>
                        	<label style="width:auto; padding-right:10px">Enrollment No.</label>
                            <div class="previewFieldBox2" style="width:267px;"><span>&nbsp;</span></div>
                        </li>
                        
                        <li style="padding-top:30px">
                        
                        	<div style="width:170px; float:left; text-align:center">
                                <div class="previewFieldBox2"><span>&nbsp;</span></div>
                                <div class="clearFix"></div>
                                <strong>Signature<br />Asst. Registrar</strong>
                            </div>
                            
                            <div style="width:170px; float:right; text-align:center">
                                <div class="previewFieldBox2"><span>&nbsp;</span></div>
                                <div class="clearFix"></div>
                                <strong>Signature<br />Director</strong>
                            </div>
                        </li>
                    </ul>
                </div>
                
             </div>
             
             <div class="sections" style="margin-bottom:0">
            	<div class="formRows">
            		<ul>
                    	<li class="admit-card" style="margin-bottom:0">
                        	<div class="admit-card-left">
                            	<h5 style="font-size:22px; text-transform:uppercase; text-align:left; padding-top:20px"><strong>INSTRUCTIONS</strong></h5>
                                <ul class="inst-items">
                                	<li>Candidate should posses Admit Card during examination and produce the same if required by the Invigilator. No candidate will be allowed to appear for the exam without Admit Card.</li>
                                    <li>This Admit Card is valid for only one appearance at MET.</li>
                                    <li>For any assistance, please contact Admission Office on <br />the following numbers:<br /><br />
                                    <strong>09922487669 / 09922487671 / 8605003969</strong>
									</li>
                                </ul>
                            </div>
                            
                            <div class="admit-card-right">
                            	<div style="text-align:center"><img src="/public/images/onlineforms/institutes/mitsob/footer-logo.jpg" alt="" /></div>
                                <div class="clearFix spacer10"></div>
                                <p style="padding-left:30px; line-height:20px; padding-bottom:15px">
                                	MIT Campus, Paud Road, Kothrud, Pune – 411 038. (India)<br />
									Tel.: (office)+91-20-30273602/01 Fax : +91-20-25442770<br />
									E-mail : pgdmsob@gmail.com &nbsp;&nbsp; Website: www.mitsob.net
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        	<div class="clearFix"></div>    
          </div>
    <div class="clearFix spacer10"></div>
    
    
    </div>
    </div>
    <!--VELS Form Preview Ends here-->
