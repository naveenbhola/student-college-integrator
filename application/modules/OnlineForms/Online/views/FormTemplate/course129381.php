   	<!--VELS Form Preview Starts here-->
	<link href="/public/css/onlineforms/vels/VELS_styles.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
    	<div class="previewHeader">
        	<div class="formNumb">
            	<p>Application No.: </p>
            	<span>&nbsp;<?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></span>
            </div>
			      <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  <strong class="editFormLink applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
			      <?php } ?>
            <div class="clearFix spacer10"></div>
        	<div class="instLogoBox"><img src="/public/images/onlineforms/institutes/vels/logo.jpg" alt="" /></div>
            <div class="clearFix"></div>
            <div class="courseNameDetails">
            	<p>VEL'S Institute of Science, Technology and Advanced Studies <strong>(VISTAS)</strong></p>
				<span>(Established under section 3 of the UGC Act, 1956)</span><br />
				Reg. Office : Velan Nagar P.V. Vaithiyalingam Road Pallavaram Chennai - 600 117 Tamil Nadu, India
                
            </div>
            <div class="spacer5 clearFix"></div>
        </div>
        <div class="clearFix"></div>
        <div class="previewBody">
        	
            <div class="formRows">
            	<div style="width:705px; float:left;">
                	<div class="batch-details">APPLICATION FORM FOR ADMISSION<br />
                    <span>(Write in CAPITAL Letters only)</span></div>
                    <div class="spacer5 clearFix"></div>
                    <ul>
                    	<li>
                        	<label style="padding-top:0px">Enrollment No.<br />
                            <strong>(For Office use only)</strong>
							</label>
                            <div class="previewFieldBox" style="width:308px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        
                        <li>
                        	<label>Academic Year</label>
                            <div class="previewFieldBox" style="width:285px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td><strong>2</strong></td>
                                        <td><strong>0</strong></td>
                                        <td><strong>1</strong></td>
                                        <td><strong>2</strong></td>
                                        <td><strong>-</strong></td>
                                        <td><strong>2</strong></td>
                                        <td><strong>0</strong></td>
                                        <td><strong>1</strong></td>
                                        <td><strong>4</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        
                        <li>
                        	<label>Course applied for<br />
                            <strong style="padding-left:30px">(<img src="/public/images/onlineforms/institutes/vels/tick-icn.gif" alt="Tick" /> Tick)</strong>
							</label>
                            <div class="previewFieldBox" style="width:250px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<?php if(isset($firstName) && $firstName!=''){ ?>
                                        <td style="width:40px"><strike>UG</strike></td>
                                        <td style="width:40px">PG</td>
                                        <td style="width:auto"><strike>Diploma</strike></td>
                                        <td style="width:auto"><strike>Research</strike></td>
					<?php }else{ ?>
                                        <td style="width:40px">UG</td>
                                        <td style="width:40px">PG</td>
                                        <td style="width:auto">Diploma</td>
                                        <td style="width:auto">Research</td>
					<?php } ?>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="formColumns2" style="width:100px; padding-left:30px">
                                <label style="width:auto">Full-time</label>
                                <div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td><?php if(isset($firstName) && $firstName!=''){ ?><img src="/public/images/onlineforms/institutes/vels/tick-icn.gif" /><?php } ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="formColumns2" style="width:100px; padding-left:30px">
                                <label style="width:auto">Part-time</label>
                                <div class="previewFieldBox" style="width:33px;">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="clearFix"></div>
                </div>
                
                <div class="picBox">
		    <?php if($profileImage) { ?>
			<img width="175" height="192" src="<?php echo $profileImage; ?>" />
		    <?php } ?>
                </div>
               <div class="clearFix"></div>
            </div>
        	
            <div class="formRows">
            	<ul>
                    <li>
                        <label style="padding-top:0px">Subject applied for<br />
                        <strong>(Specify the Major)</strong>
                        </label>
						<div style="float:left; width:720px;">
                        <div class="checkCols" style="padding-left:0">
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if(strpos($coursesVELS,'HR')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
	                    <label>&nbsp;MBA in HR, Marketing, Finance, Systems & Production</label>
                        </div>
                        <div class="clearFix spacer10"></div>
                        <div class="checkCols" style="padding-left:0">
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if(strpos($coursesVELS,'Shipping')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
	                    <label>&nbsp;MBA in Logistics & Shipping Management (in association with IIL)</label>
                        </div>
                        <div class="clearFix spacer10"></div>
                        <div class="checkCols" style="padding-left:0">
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if(strpos($coursesVELS,'Supply')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
	                    <label>&nbsp;MBA in Logistics & Supply Chain Management (in association with CII)</label>
                        </div>
                        <div class="clearFix spacer10"></div>
                        <div class="checkCols" style="padding-left:0">
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if(strpos($coursesVELS,'Tourism')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
	                    <label>&nbsp;MBA in International Travel & Tourism Management (in association with Kuoni Academy)</label>
                        </div>
						</div>
                    </li>
                    
                    <li>
                        <label style="width:200px">Language opted for Part – I</label>
                        <label style="width:auto">For UG Courses</label>
                        <div class="previewFieldBox" style="width:400px;">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </div>
                    </li>
                    
                    <li>
                        <label style="width:98%"><span>1.</span>Name of the Applicant as in the Birth Certificate or Marks statement of XII Standard.</label>
                        <div class="clearFix spacer5"></div>
                        <div class="previewFieldBox2">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				    <?php $name = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; echo displayFormDataInBoxes(26,$name); ?>
                                </tr>
                            </table>
                        </div>
                    </li>
                    
                    <li>
                    	<div class="formPersonalDetails">
                            <label><span>2.</span>Sex:</label>
                            <label>Male</label>
                            <label>Female</label>
                            <div class="clearFix spacer15"></div>
                            <div class="previewFieldBox2" style="width:56px; padding-left:15px; padding-top:5px">(<img src="/public/images/onlineforms/institutes/vels/tick-icn.gif" /> Tick)</div>
                            <div class="previewFieldBox2" style="width:32px; padding-left:16px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($gender=='MALE') echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="previewFieldBox2" style="width:32px; padding-left:65px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($gender=='FEMALE') echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
			<?php
			list($dobDay,$dobMonth,$dobYear) = explode('/',$dateOfBirth);
			?>
                        <div class="formPersonalDetails" style="width:420px">
                            <label style="width:100% !important"><span>3.</span>Date of Birth &amp; Age</label>
                            <div class="clearFix"></div>
                            
                            <div class="previewFieldBox2" style="width:65px; padding-left:5px">
                            	<span>Date</span>
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td><?php echo $dobDay[0];?></td>
					<td><?php echo $dobDay[1];?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox2" style="width:65px; padding-left:15px">
                            	<span>Month</span>
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<td><?php echo $dobMonth[0];?></td>
					<td><?php echo $dobMonth[1];?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox2" style="width:130px; padding-left:15px">
                            	<span>Year</span>
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<td><?php echo $dobYear[0];?></td>
					<td><?php echo $dobYear[1];?></td>
					<td><?php echo $dobYear[2];?></td>
					<td><?php echo $dobYear[3];?></td>
                                    </tr>
                                </table>
                            </div>

			    <?php
					  if(isset($dateOfBirth) && $dateOfBirth!=''){
					  $startDate = getStandardDate($dateOfBirth);
					  $endDate = date('Y-m-d');
					  $totalDuration = getTimeDifference($startDate,$endDate);
					  $ageYear = ($totalDuration['years']<0)?0:$totalDuration['years'];
					  }
					  else
					  $ageYear = '';
			    ?>                        
                            
                            <div class="previewFieldBox2" style="width:65px; padding-left:15px">
                            	<span>Age</span>
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td><?=$ageYear[0]?></td>
                                        <td><?=$ageYear[1]?></td>
                                    </tr>
                                </table>
                            </div>
                            
                        </div>
                        
                        <div class="formPersonalDetails" style="width:175px; float:right;">
                            <label style="width:100% !important"><span>4.</span>Blood Group</label>
                            <div class="clearFix spacer15"></div>
                            <div class="previewFieldBox2" style="width:165px; padding-left:5px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td>&nbsp;<?=$bloodGroupVELS?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                    	<div class="formColumns2" style="width:400px">
                            <label style="width:auto"><span>5.</span>a) &nbsp; Nationality</label>
                            <div class="previewFieldBox2" style="width:233px; padding-left:10px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?=$nationality?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="formColumns2" style="width:400px; float:right">
                            <label style="width:auto">b) &nbsp; Mother Tongue</label>
                            <div class="previewFieldBox2" style="width:260px; padding-left:10px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?=$motherTongueVELS?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                        <label style="width:200px"><span>&nbsp;</span>&nbsp;&nbsp;&nbsp;c) &nbsp; Religion : (<img src="/public/images/onlineforms/institutes/vels/tick-icn.gif" /> Tick)</label>
                        <div class="checkCols">
                        	<label>Hindu</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if(strpos($religionVELS,'Hindu')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>Christian</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if(strpos($religionVELS,'Christian')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>Muslim</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if(strpos($religionVELS,'Muslim')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>Others</label>
                            <div class="formColumns2" style="width:247px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td>&nbsp;<?=$otherReligionVELS?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                        <label style="width:200px"><span>&nbsp;</span>&nbsp;&nbsp;&nbsp;d) &nbsp; Community : (<img src="/public/images/onlineforms/institutes/vels/tick-icn.gif" /> Tick)</label>
                        <div class="checkCols" style="padding-left:41px">
                        	<label>OC</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($communityVELS=='OC'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>BC</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($communityVELS=='BC'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>OBC</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($communityVELS=='OBC'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>MBC</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($communityVELS=='MBC'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>DNC</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($communityVELS=='DNC'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>SC</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($communityVELS=='SC'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="checkCols">
                        	<label>ST</label>
                            <div class="formColumns2" style="width:32px;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>&nbsp;<?php if($communityVELS=='ST'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                    	<div class="formColumns2" style="width:460px">
                            <label style="width:auto"><span>&nbsp;</span>&nbsp;&nbsp;&nbsp;e) &nbsp; Caste :</label>
                            <div class="previewFieldBox2" style="width:330px; padding-left:10px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?=$casteVELS?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="formColumns2" style="width:430px; float:right">
                            <label style="width:auto">f) &nbsp; State :</label>
                            <div class="previewFieldBox2" style="width:287px; padding-left:10px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?=$Cstate?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                    	<div class="formColumns2">
                            <label style="width:auto"><span>&nbsp;</span>&nbsp;&nbsp;&nbsp;g) &nbsp; For Foreign Students : Nationality</label>
                            <div class="previewFieldBox2" style="width:320px; padding-left:10px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?php echo $nationality; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                    	<div class="formColumns2" style="width:445px">
                            <label style="width:auto; padding-left:60px">Passport Number</label>
                            <div class="previewFieldBox2" style="width:200px; padding-left:10px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($passportVELS!='')?$passportVELS:'NA';}?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="formColumns2" style="width:240px;">
                            <label style="width:auto">Visa Period</label>
                            <div class="previewFieldBox2" style="width:120px; padding-left:10px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($visaPeriodVELS!='')?$visaPeriodVELS:'NA';}?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="formColumns2" style="width:183px; float:right">
                            <label style="width:auto">Valid till</label>
                            <div class="previewFieldBox2" style="width:120px; padding-left:10px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($validTillVELS!='')?$validTillVELS:'NA';}?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                    	<label><span>6.</span>Father's Name</label>
                        <div class="previewFieldBox2" style="width:720px;">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				      <?php echo displayFormDataInBoxes(22,$fatherName); ?>
                                </tr>
                            </table>
                        </div>
                    </li>
                    
                    <li>
                    	<label><span>7.</span>Mother's Name</label>
                        <div class="previewFieldBox2" style="width:720px;">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				      <?php echo displayFormDataInBoxes(22,$MotherName); ?>
                                </tr>
                            </table>
                        </div>
                    </li>





			<?php
			list($dobDay,$dobMonth,$dobYear) = explode('/',$fatherDOBVELS);
			?>
                    <li>
                    	<div class="formColumns2" style="width:430px">
                            <label style="width:auto"><span>8.</span>a) &nbsp; Father's Date of Birth &amp; Age</label>
                            <div class="clearFix spacer10"></div>
                            <div class="formPersonalDetails" style="width:435px; padding-left:26px">
                                <div class="previewFieldBox2" style="width:65px;">
                                    <span>Date</span>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <td><?php echo $dobDay[0];?></td>
					    <td><?php echo $dobDay[1];?></td>
                                        </tr>
                                    </table>
                                </div>
                            
                                <div class="previewFieldBox2" style="width:65px; padding-left:15px">
                                    <span>Month</span>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <td><?php echo $dobMonth[0];?></td>
					    <td><?php echo $dobMonth[1];?></td>
                                        </tr>
                                    </table>
                                </div>
                            
                                <div class="previewFieldBox2" style="width:130px; padding-left:15px">
                                    <span>Year</span>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <td><?php echo $dobYear[0];?></td>
					    <td><?php echo $dobYear[1];?></td>
					    <td><?php echo $dobYear[2];?></td>
					    <td><?php echo $dobYear[3];?></td>
                                        </tr>
                                    </table>
                                </div>

			    <?php

					  if(isset($fatherDOBVELS) && $fatherDOBVELS!=''){
					  $startDate = getStandardDate($fatherDOBVELS);
					  $endDate = date('Y-m-d');
					  $totalDuration = getTimeDifference($startDate,$endDate);
					  $ageYear = ($totalDuration['years']<0)?0:$totalDuration['years'];
					  }
					  else
					  $ageYear = '';
			    ?>                        
                            
                                <div class="previewFieldBox2" style="width:65px; padding-left:15px">
                                    <span>Age</span>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                        <td><?=$ageYear[0]?></td>
                                        <td><?=$ageYear[1]?></td>
                                        </tr>
                                    </table>
                                </div>
                            
                        	</div>
                        </div>
                        
			<?php
			list($dobDay,$dobMonth,$dobYear) = explode('/',$motherDOBVELS);
			?>
                        <div class="formColumns2" style="width:420px; float:right">
                            <label style="width:auto"><span>&nbsp;</span>b) &nbsp; Mother's Date of Birth &amp; Age</label>
                            <div class="clearFix spacer10"></div>
                            <div class="formPersonalDetails" style="width:400px;">
                                <div class="previewFieldBox2" style="width:65px;">
                                    <span>Date</span>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <td><?php echo $dobDay[0];?></td>
					    <td><?php echo $dobDay[1];?></td>
                                        </tr>
                                    </table>
                                </div>
                            
                                <div class="previewFieldBox2" style="width:65px; padding-left:15px">
                                    <span>Month</span>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					    <td><?php echo $dobMonth[0];?></td>
					    <td><?php echo $dobMonth[1];?></td>
                                        </tr>
                                    </table>
                                </div>
                            
                                <div class="previewFieldBox2" style="width:130px; padding-left:15px">
                                    <span>Year</span>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					<td><?php echo $dobYear[0];?></td>
					<td><?php echo $dobYear[1];?></td>
					<td><?php echo $dobYear[2];?></td>
					<td><?php echo $dobYear[3];?></td>
                                        </tr>
                                    </table>
                                </div>
			    <?php
					  if(isset($motherDOBVELS) && $motherDOBVELS!=''){
					  $startDate = getStandardDate($motherDOBVELS);
					  $endDate = date('Y-m-d');
					  $totalDuration = getTimeDifference($startDate,$endDate);
					  $ageYear = ($totalDuration['years']<0)?0:$totalDuration['years'];
					  }
					  else
					  $ageYear = '';
			    ?>                        
                            
                                <div class="previewFieldBox2" style="width:65px; padding-left:15px">
                                    <span>Age</span>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
                                        <td><?=$ageYear[0]?></td>
                                        <td><?=$ageYear[1]?></td>
                                        </tr>
                                    </table>
                                </div>
                            
                        	</div>
                        </div>
                    </li>
                    
                    <li>
                    	<label style="width:100%"><span>9.</span>Father's Employment Details</label>
                        <div class="spacer10 clearFix"></div>
                        <div>
                            <label style="width:auto; padding-left:22px">a) &nbsp; Employed : (<img src="/public/images/onlineforms/institutes/vels/tick-icn.gif" /> Tick)</label>
                            
                            <div class="formColumns2">
                                <label style="width:auto; padding-left:30px">YES</label>
                                <div class="previewFieldBox2" style="width:32px; padding-left:5px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <td>&nbsp;<?php if($fatherEmployedVELS=='Yes'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:auto; padding-left:30px">NO</label>
                                <div class="previewFieldBox2" style="width:32px; padding-left:5px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <td>&nbsp;<?php if($fatherEmployedVELS=='No'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                    </li>
                    
                    <li>
						<label style="width:auto; padding-left:22px">b) &nbsp; Occupation</label>
                        <div class="previewFieldBox2" style="width:400px; padding-left:5px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$fatherOccupation?></td>
                                </tr>
                            </table>
                        </div>
                    </li>
                    
                    <li>
						<label style="width:auto; padding-left:22px">b) &nbsp; Father's Annual Income</label>
                        <div class="previewFieldBox2" style="width:200px; padding-left:5px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$fatherIncomeVELS?></td>
                                </tr>
                            </table>
                        </div>
                    </li>
                    
		    <?php 
			  $streetName = ($streetName!='')?$streetName.' ':'';
			  $permAddress = $houseNumber.' '.$streetName.$city;
		    ?>
                    <li>
                        <label style="width:auto"><span>10.</span>Permanent Address (do not repeat name)</label>
                        <div class="clearFix spacer5"></div>
                        <div class="formColumns2" style="width:100%">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				      <?php echo displayFormDataInBoxes(27,substr($permAddress,0,27)); ?>
                                </tr>
                                <tr>
				      <?php echo displayFormDataInBoxes(27,substr($permAddress,27,27)); ?>
                                </tr>
                                <tr>
				      <?php echo displayFormDataInBoxes(27,substr($permAddress,54,27)); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="clearFix spacer15"></div>
                        <div class="formColumns2" style="width:310px">
                        <label style="width:auto">District</label>
                        <div class="formColumns2" style="width:225px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$area?></td>
                                </tr>
                           	</table>
                        </div>
                        </div>
                        
                        <div class="formColumns2">
                        <label style="width:auto">State</label>
                        <div class="formColumns2" style="width:250px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$state?></td>
                                </tr>
                           	</table>
                        </div>
                        </div>
                        
                        <div class="formColumns2" style="float:right">
                        <label style="width:auto">Pincode</label>
                        <div class="formColumns2" style="width:200px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr><?php echo displayFormDataInBoxes(6,$pincode); ?>
                                </tr>
                           	</table>
                        </div>
                        </div>
                    </li>

		    <?php 
			  $CstreetName = ($CstreetName!='')?$CstreetName.' ':'';
			  $corrAddress = $ChouseNumber.' '.$CstreetName.$Ccity;
		    ?>
                    
                    <li>
                        <label style="width:auto"><span>11.</span>Address for Correspondence – Parent/Guardian (do not repeat name)</label>
                        <div class="clearFix spacer5"></div>
                        <div class="formColumns2" style="width:100%">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				      <?php echo displayFormDataInBoxes(27,substr($corrAddress,0,27)); ?>
                                </tr>
                                <tr>
				      <?php echo displayFormDataInBoxes(27,substr($corrAddress,27,27)); ?>
                                </tr>
                                <tr>
				      <?php echo displayFormDataInBoxes(27,substr($corrAddress,54,27)); ?>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="clearFix spacer15"></div>
                        <div class="formColumns2" style="width:310px">
                        <label style="width:55px">District</label>
                        <div class="formColumns2" style="width:225px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$Carea?></td>
                                </tr>
                           	</table>
                        </div>
                        </div>
                        
                        <div class="formColumns2">
                        <label style="width:auto">State</label>
                        <div class="formColumns2" style="width:250px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$Cstate?></td>
                                </tr>
                           	</table>
                        </div>
                        </div>
                        
                        <div class="formColumns2" style="float:right">
                        <label style="width:auto">Pincode</label>
                        <div class="formColumns2" style="width:200px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                <tr>
				      <?php echo displayFormDataInBoxes(6,$Cpincode); ?>
                                </tr>
                           	</table>
                        </div>
                        </div>
                        
                        <div class="clearFix spacer15"></div>
                        <div class="formColumns2" style="width:396px">
                        <label style="width:55px">Country</label>
                        <div class="formColumns2" style="width:285px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$Ccountry?></td>
                                </tr>
                           	</table>
                        </div>
                        </div>
                        
                        <div class="formColumns2">
                        <label style="width:auto">STD Code</label>
                        <div class="formColumns2" style="width:130px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$landlineSTDCode?></td>
                                </tr>
                           	</table>
                        </div>
                        </div>
                        
                        <div class="formColumns2" style="float:right">
                        <label style="width:auto">Phone</label>
                        <div class="formColumns2" style="width:200px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                <tr>
                                    <td style="text-align:left">&nbsp;<?=$landlineNumber?></td>
                                </tr>
                           	</table>
                        </div>
                        </div>
                        
                        <div class="clearFix spacer15"></div>
                        <div class="formColumns2" style="width:396px">
                        	<label style="width:55px">Mobile</label>
                            <div class="formColumns2" style="width:285px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
				      					<td style="text-align:left">&nbsp;<?=$mobileNumber?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="formColumns2">
                            <label style="width:auto">E.Mail </label>
                            <div class="formColumns2" style="width:450px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable2">
                                    <tr>
                                        <td style="text-align:left">&nbsp;<?=$email?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                    	<label style="width:175px"><span>12.</span>Are you Handicapped:</label>
                        
                        <div class="formColumns2">
                            <label style="width:auto; padding-left:30px">YES</label>
                            <div class="previewFieldBox2" style="width:32px; padding-left:5px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if($handicappedVELS=='Yes'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                            
                        <div class="formColumns2">
                            <label style="width:auto; padding-left:30px">NO</label>
                            <div class="previewFieldBox2" style="width:32px; padding-left:5px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if($handicappedVELS=='No'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="flLt" style="padding:7px 0 0 15px">(Certificate should be attached)</div>
                        </div>
                    </li>

                    <li>
                        <label style="width:auto"><span>13.</span>Details of Qualifying Exams</label>
                        <div class="clearFix spacer5"></div>
                        <div class="formColumns2" style="width:100%">
                            <table width="100%" cellpadding="8" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                                <tr>
                                    <th>S. No</th>
                                    <th>Name of the <br />Qualifying Exam.</th>
                                    <th>Date of Examination</th>
                                    <th>Roll Number</th>
                                    <th>Score</th>
                                    <th>Percentile</th>
                                </tr>
                                
                                <tr>
                                	<td valign="top" align="center"><div class="formWordWrapper" style="width:40px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo "1";} ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo "CAT";} ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ ?><?php echo ($catDateOfExaminationAdditional!='')?$catDateOfExaminationAdditional:'NA';?><?php } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($catRollNumberAdditional!='')?$catRollNumberAdditional:'NA'; } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($catScoreAdditional!='')?$catScoreAdditional:'NA'; } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($catPercentileAdditional!='')?$catPercentileAdditional:'NA'; } ?></div></td>
                                </tr>
                                
                                <tr>
                                	<td valign="top" align="center"><div class="formWordWrapper" style="width:40px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo "2";} ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo "MAT";} ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ ?><?php echo ($matDateOfExaminationAdditional!='')?$matDateOfExaminationAdditional:'NA';?><?php } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($matRollNumberAdditional!='')?$matRollNumberAdditional:'NA'; } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($matScoreAdditional!='')?$matScoreAdditional:'NA'; } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo ($matPercentileAdditional!='')?$matPercentileAdditional:'NA'; } ?></div></td>
                                </tr>
                                
                            </table>
                        </div>
                    </li>
                    
                    <li>
                        <label style="width:auto"><span>14.</span>Details of Educational Qualification (From X Standard onwards)</label>
                        <div class="clearFix spacer5"></div>
                        <div class="formColumns2" style="width:100%">
                            <table width="100%" cellpadding="8" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                                <tr>
                                    <th>S. No</th>
                                    <th>Name of the <br />Qualifying Exam.</th>
                                    <th>Month &amp; Year <br />of passing</th>
                                    <th>Name of the School/ <br />College Studied</th>
                                    <th>Name of the <br />University/Board</th>
                                    <th>Certificate <br />No</th>
                                    <th>% <br />Obtained</th>
                                </tr>
                                
                                <tr>
                                	<td valign="top" align="center"><div class="formWordWrapper" style="width:40px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo "1";} ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$class10ExaminationName?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ ?><?=$class10MonthVELS.", ".$class10Year?><?php } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$class10School?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$class10Board?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$class10CertNoVELS?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:70px">&nbsp;<?=$class10Percentage?></div></td>
                                </tr>
                                
                                <tr>
                                	<td valign="top" align="center"><div class="formWordWrapper" style="width:40px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo "2";} ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$class12ExaminationName?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ ?><?=$class12MonthVELS.", ".$class12Year?><?php } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$class12School?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$class12Board?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$class12CertNoVELS?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:70px">&nbsp;<?=$class12Percentage?></div></td>
                                </tr>
                                
                                <tr>
                                	<td valign="top" align="center"><div class="formWordWrapper" style="width:40px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ echo "3";} ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?=$graduationExaminationName?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?php if(isset($firstName) && $firstName!=''){ ?><?=$gradMonthVELS.", ".$graduationYear?><?php } ?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$graduationSchool?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=$graduationBoard?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px">&nbsp;<?=$gradCertNoVELS?></div></td>
                                    <td valign="top" align="center"><div class="formWordWrapper" style="width:70px">&nbsp;<?=$graduationPercentage?></div></td>
                                </tr>


				<?php
				$countOfPGCourses = 0;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					$countOfPGCourses++; ?>
					<tr>
						<td valign="top" align="center"><div class="formWordWrapper" style="width:40px">&nbsp;<?php echo $countOfPGCourses+3;?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?=${'graduationExaminationName_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;<?=${'otherCoursePGMonth_mul_'.$i}.", ".${'graduationYear_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=${'graduationSchool_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;<?=${'graduationBoard_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px">&nbsp;<?=${'otherCourseNumber_mul_'.$i}?></div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:70px">&nbsp;<?=${'graduationPercentage_mul_'.$i}?></div></td>
					</tr>
				<?php }
				} ?>
				<!-- Block End to show PG course/Other courses row -->
                                <?php for($i=$countOfPGCourses;$i<3;$i++){ ?>
					<tr>
						<td valign="top" align="center"><div class="formWordWrapper" style="width:40px">&nbsp;</div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;</div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;</div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:160px">&nbsp;</div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:90px">&nbsp;</div></td>
					    <td valign="top" align="center"><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
					</tr>
                                <?php } ?>

                                
                            </table>
                        </div>
                    </li>
                    
                    <li>
                    	<label style="width:260px"><span>15.</span>Your participation in Extra <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Curricular Activities:</label>
                        <div class="formColumns2" style="width:130px">
                            <label style="width:130px">Sports &amp; Games</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox2" style="width:32px; padding-left:35px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if(strpos($participationVELS,'Sports')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                            
                        <div class="formColumns2" style="width:100px">
                            <label style="width:90px">N.C.C</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox2" style="width:32px; padding-left:2px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if(strpos($participationVELS,'NCC')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="formColumns2" style="width:100px">
                            <label style="width:90px">N.S.S.</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox2" style="width:32px; padding-left:2px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if(strpos($participationVELS,'NSS')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="formColumns2" style="width:100px">
                            <label style="width:90px">Scouts</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox2" style="width:32px; padding-left:6px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if(strpos($participationVELS,'Sco')!==false){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="formColumns2" style="width:90px">
                            <label style="width:80px; padding-left:6px">Nill</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox2" style="width:32px; padding-left:0px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if(isset($firstName) && $firstName!='' && $participationVELS==''){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                    	<label style="width:230px"><span>16.</span>Do you require Hostel Facility:</label>
                        
                        <div class="formColumns2">
                            <label style="width:auto; padding-left:30px">YES</label>
                            <div class="previewFieldBox2" style="width:32px; padding-left:5px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if($hostelVELS=='Yes'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                            
                        <div class="formColumns2">
                            <label style="width:auto; padding-left:30px">NO</label>
                            <div class="previewFieldBox2" style="width:32px; padding-left:5px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					  <td>&nbsp;<?php if($hostelVELS=='No'){ echo "<img src='/public/images/onlineforms/institutes/vels/tick-icn.gif' border=0/>";} ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="clearFix spacer5"></div>
                        <div style="padding-left:30px">(Students who require Hostel Accommodation should submit a separate Application From to Warden) </div>
                    </li>
                </ul>
            </div>
            <div class="breakVels" style="display:none"></div>
            <div class="formRows">
            	<ul>
                    <li style="text-align:center; font-size:16px; padding-top:20px">
                        <strong>DECLARATION</strong>
                    </li>
                    <li style="line-height:20px">
						<span class="flLt">I,</span> <span style="border-bottom:dotted 1px #000; width:300px; float:left">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span> hereby affirm that the particulars given in this application form are true and correct to the <div class="clearFix"></div>
best of my knowledge. If it is found at any stage that there is suppression, distortion, incorrect or false statement of data, I am aware of the fact that this may lead to my dismissal from the University and I would also be liable to make good any loss that may be caused to covert action. I also agree that I would lose all rights and claims consequently whatsoever. I further state that I shall not partake in any strike, demonstration or political activity. I agree that all disputes are subject to the jurisdiction of the court in Chennai only.
                    </li>
                    <li style="margin-bottom:0">
                    	<div class="formDateBox">
                        	<span>Place: &nbsp;</span>
                            <span>&nbsp;<?php if(isset($firstName) && $firstName!='') {echo $Cstate;} ?></span>
                        </div>
                        <div class="spacer15 clearFix"></div>
                        <div class="formDateBox">
			    <span>Date: &nbsp;</span>
                            <span>&nbsp;					<?php
					      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
						      echo date("d/m/Y", strtotime($paymentDetails['date']));
						}
					?>
			    </span>
                        </div>
                    </li>
                    <li>
                    	<div class="formSignBox">
                        	<div class="previewFieldBox" style="width:100%"><span>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span></div>
                            <div class="spacer5 clearFix"></div>
                            <p>Signature of the applicant</p>
                        </div>
                    </li>
                    
                    <li>
                    	<ul>
                        	<li>If admitted to be bound by the rules and regulations now in force and those that will be made from time to time. We will make good the loss of damage to the property of the institution caused by us.</li>
                            <li>We also promise that we will do nothing either inside or outside the institution that will interfere with its discipline.</li>
                            <li>We accept that all the decisions of the authorities in all matters of training, conduct, process, of examinations and discipline.</li>
                            <li>We promise to abide by the rules and regulations of your University.</li>
                            <li>WE FURTHER ACCEPT THAT IF I/MY SON/DAUGHTER WISHES TO LEAVE THE INSTITUTION IN THE MIDDLE OF TE COURSE, WE WILL PAY TUITION FEE FOR THE FULL COURSE BEFORE THE ISSUE OF THE TRANSFER CERTIFICATE AND OTHER CERTIFICATES.</li>
                        </ul>
                    </li>
                    
                    <li style="margin-bottom:0">
                    	<div class="formDateBox">
                        	<span>Place: &nbsp;</span>
                            <span>&nbsp;<?php if(isset($firstName) && $firstName!='') {echo $Cstate;} ?></span>
                        </div>
                        <div class="spacer15 clearFix"></div>
                        <div class="formDateBox">
                        	<span>Date: &nbsp;</span>
                            <span>&nbsp;					<?php
					      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
						      echo date("d/m/Y", strtotime($paymentDetails['date']));
						}
					?>
			    </span>
                        </div>
                    </li>
                    <li>
                    	<div class="formSignBox" style="width:500px">
                        	<div class="previewFieldBox" style="width:100%">
				    <span>&nbsp;
					<?php if(strpos($agreeToTermsParentsVELS,'other')!==false) echo $MotherName;
					      if(strpos($agreeToTermsParentsVELS,'ather')!==false && strpos($agreeToTermsParentsVELS,'other')!==false) echo ',&nbsp;';
					      if(strpos($agreeToTermsParentsVELS,'ather')!==false) echo $fatherName;
					?>
				    </span>
				</div>
                            <div class="spacer5 clearFix"></div>
                            <p>Signature of the Parent / Gaurdian</p>
                        </div>
                        
                        <div class="formSignBox" style="padding-right:20px">
                        	<div class="previewFieldBox" style="width:100%"><span>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span></div>
                            <div class="spacer5 clearFix"></div>
                            <p>Signature of the Applicant</p>
                        </div>
                    </li>
                    
                    <li>
                    	<label style="width:100%">Details of Photo copies of the certificates to be submitted by the candidate at the time of Admission</label>
                        <div class="spacer5 clearFix"></div>
                        <table width="100%" cellpadding="8" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                            <tr>
                                <th>S. No.</th>
                                <th>Particulars of Certificate</th>
                                <th>Certificate SI. No.</th>
                                <th>Reg. Number/Month &amp; <br />Year of Passing</th>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">1.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">10th Std. Mark Sheet(s)</div></td>
                                <td valign="top" align="center"><div class="formWordWrapper" style="width:250px">&nbsp;<?=$class10CertNoVELS?></div></td>
                                <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?php echo $class10MonthVELS; if($class10Year!='') echo ', '.$class10Year;?></div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">2.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">H.S.C or Equivalent Mark Sheet(s)</div></td>
                                <td valign="top" align="center"><div class="formWordWrapper" style="width:250px">&nbsp;<?=$class12CertNoVELS?></div></td>
                                <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?php echo $class12MonthVELS; if($class12Year!='') echo ', '.$class12Year;?></div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">3.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">Degree Mark Sheet........ Nos.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:150px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">4.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">Provisional Certificate</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:150px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">5.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">Degree Certificate</div></td>
                                <td valign="top" align="center"><div class="formWordWrapper" style="width:250px">&nbsp;<?=$gradCertNoVELS?></div></td>
                                <td valign="top" align="center"><div class="formWordWrapper" style="width:150px">&nbsp;<?php echo $gradMonthVELS; if($graduationYear!='') echo ', '.$graduationYear;?></div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">6.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">Migration Certificate</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:150px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">7.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">Transfer Certificate</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:150px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">8.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">Community Certificate</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:150px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">9.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">Other Certificate(s) if, any...</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:150px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:50px">10.</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:350px">Self-address stamped Envelope -2 (Rs.10 each)</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
                                <td valign="top" align="left"><div class="formWordWrapper" style="width:150px">&nbsp;</div></td>
                            </tr>
                        </table>
                    </li>
                    
                </ul>
             </div>
         	<div class="clearFix"></div>    
          </div>
         </div>
    <div class="clearFix spacer10"></div>
    <div class="ackknowBox">
    	<div class="title"><strong>ACKNOWLEDGEMENT</strong> (for Office use only)</div>
		<div class="clearFix spacer10"></div>
        <div style="width:400px; float:left">
        	<span class="flLt">Received your Application No</span>
        	<div class="previewFieldBox3" style="width:208px"><span>&nbsp;</span></div>
        </div>
        <div style="width:523px; float:left">
        	<span class="flLt">for Admission to the course</span>
        	<div class="previewFieldBox3" style="width:345px"><span>&nbsp;</span></div>
        </div>
        <div class="clearFix spacer15"></div>
        <div style="width:523px; float:left">
        	<span class="flLt">With Registration No</span>
        	<div class="previewFieldBox3" style="width:200px"><span>&nbsp;</span></div>
        </div>
        <div class="clearFix spacer15"></div>
        <div style="width:300px; text-align:center; float:right;">
            <p>&nbsp;</p>
            <div class="spacer5 clearFix"></div>
            <p>Authorised Signatory</p>
        </div>
    	<div class="clearFix spacer10"></div>
    </div>
    <div class="clearFix"></div>
    </div>
    <div class="clearFix"></div>
    </div>
    <div class="clearFix"></div>
    <!--VELS Form Preview Ends here-->
