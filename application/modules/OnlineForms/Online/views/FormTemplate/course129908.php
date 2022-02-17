   	<!--Amrita Form Preview Starts here-->
	<link href="/public/css/onlineforms/amrita/amrita_styles.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
			      
    	<div class="previewHeader">
        	<div class="previewChildHeader">
        	<div class="instLogoBox"><img src="/public/images/onlineforms/institutes/amrita/logo.gif" alt="" /></div>
            <div class="courseNameDetails">
            	<p>
               	<img src="/public/images/onlineforms/institutes/amrita/inst-name.gif" alt="" />
                <strong>Amrita School of Business</strong><br />
				<span>Amritanagar P.O., Ettimadai, Coimbatore - 641 112.</span>
                </p>
            </div>
            <div class="spacer5 clearFix"></div>
            <div class="batch-details">MASTER OF BUSINESS ADMINISTRATION (MBA)<br /><span><?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2012";}?> - <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2014";}?> BATCH - APPLICATION FORM</span></div>
            </div>
            <div class="picBox">
			<?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?>
	    </div>            
        </div>
        <div class="clearFix"></div>
        <div class="previewBody">
        	<div class="clearFix"></div>
        	<?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  <strong class="applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
			      <?php } ?>
                  <div class="clearFix spacer5"></div>
        	<div class="contTxt">TO BE RECEIVED LATEST BY JANUARY 31, <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2012";}?>.</div>
            <div class="formNumb">
            	<span class="flLt">Form No.</span>
            	<div style="width:140px;" class="previewFieldBox2">
                	<span>&nbsp;<?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></span>
                </div>
            </div>
            <div class="clearFix spacer10"></div>
            <p style="font-size:16px">Tick the appropriate box and provide the registration number, test date, score (if available) Refer to item #5 in instruction sheet.</p>
            
            <div class="formRows">
                <div class="clearFix spacer15"></div>
                <ul>
                    <li style="margin-bottom:10px">
                    	<label style="line-height:22px; font-size:18px; width:110px">TEST <img style="vertical-align:text-bottom" src="/public/images/onlineforms/institutes/amrita/arrow.gif" alt="" /> <br />REGN NO<br />Date &amp; Score</label>
                        <div class="previewFieldBox" style="width:745px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="educationTable2">
                            	<tr>
                                	<th>CAT <?php if(strpos($testNamesAmrita,'CAT') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></th>
                                    <th>MAT <?php $tests = explode(',',$testNamesAmrita); foreach ($tests as $test){ if($test=='MAT'){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";}} ?></th>
                                    <th>XAT <?php if(strpos($testNamesAmrita,'XAT') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></th>
                                    <th>GRE <?php if(strpos($testNamesAmrita,'GRE') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></th>
                                    <th>GMAT <?php if(strpos($testNamesAmrita,'GMAT') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></th>
                                
                                </tr>
                                <tr>
                                    <td valign="top" height="100">
                                    	<div class="entranceDetails"><?php if(strpos($testNamesAmrita,'CAT') !== false){ echo $catRollNumberAdditional."<br/>".$catDateOfExaminationAdditional."<br/>".$catScoreAdditional;} ?></div>
                                    </td>
                                    <td valign="top">
                                    	<div class="entranceDetails"><?php $tests = explode(',',$testNamesAmrita); 
foreach ($tests as $test){
if($test=='MAT'){ echo $matRollNumberAdditional."<br/>".$matDateOfExaminationAdditional."<br/>".$matScoreAdditional;} 
}
?>
					</div>
                                    </td>
                                    <td valign="top">
                                    	<div class="entranceDetails"><?php if(strpos($testNamesAmrita,'XAT') !== false){ echo $xatRegnNoAmrita."<br/>".$xatDateAmrita."<br/>".$xatScoreAmrita;} ?></div>
                                    </td>
                                    <td valign="top">
                                    	<div class="entranceDetails"><?php if(strpos($testNamesAmrita,'GRE') !== false){ echo $greRegnNoAmrita."<br/>".$greDateAmrita."<br/>".$greScoreAmrita;} ?></div>
                                    </td>
                                    <td valign="top">
                                    	<div class="entranceDetails"><?php if(strpos($testNamesAmrita,'GMAT') !== false){ echo $gmatRollNumberAdditional."<br/>".$gmatDateOfExaminationAdditional."<br/>".$gmatScoreAdditional;} ?></div>
                                    </td>
                                </tr>
                            </table>
                            <div class="spacer5 clearFix"></div>
                        </div>
                        
                    </li>
                 </ul>
               <div class="clearFix"></div>
            </div>
        	
            <div class="formRows">
                <ul>
                	<li>
                    	<label style="width:465px; padding:20px 0px 0 0px; text-align:right; font-size:16px; font-weight:bold; text-align:left">Choice of ASB Centre for the Personal Interview (Please tick one) :</label>
                        <div class="choiceBox">
                            <p>Ahmadabad</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='30'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>Bengaluru</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='278'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spacer10 clearFix"></div>
                        <div class="choiceBox">
                            <p>Bhubaneshwar</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='912'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>Chennai</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='64'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>Coimbatore</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='67'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>Hyderabad</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='702'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>Kochi</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='127'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>Kolkata</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='130'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>Lucknow</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='138'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>Mumbai</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='151'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="choiceBox">
                            <p>New Delhi</p>
                            <div class="clearFix spacer5"></div>
                            <table align="center" width="30" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
                                    <td><?php if($preferredGDPILocation=='10223'){echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></td>
                                </tr>
                            </table>
                        </div>
                    </li>
                
                    <li>
                    	<label style="font-weight:bold; width:500px"><span>A.</span>PERSONAL DATA</label>
                        <div class="spacer10 clearFix"></div>
                        <label style="width:500px"><span>A-1</span>Name in BLOCK LETTERS (As per your UG certificate)</label>
                        <div class="spacer10 clearFix"></div>
                        <div class="previewFieldBox" style="width:850px; padding-left:40px">
                            <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                <tr>
				    <?php $name = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; echo displayFormDataInBoxes(29,$name); ?>
                                </tr>
                            </table>
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
                        	<label style="width:70px"><span>A-2</span>Age:</label>
                            <div class="previewFieldBox" style="width:100px">
                                <div class="checkBox"><?=number_format($ageYear,0)?></div>
                                <div class="checkBoxLabel">Yrs</div>
                            </div>
                            
                            <div class="previewFieldBox" style="width:100px">
                                <div class="checkBox"><?=$ageMonth?></div>
                                <div class="checkBoxLabel">Months</div>
                            </div>

			<?php
			list($dobDay,$dobMonth,$dobYear) = explode('/',$dateOfBirth);
			?>
                        
                            <div class="previewFieldBox" style="width:300px;">
                                <label style="width:auto; padding-top:4px">Date of Birth</label>
                                <div class="previewFieldBox" style="width:158px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<td><?php echo $dobDay[0];?></td>
					<td><?php echo $dobDay[1];?></td>
					<td><?php echo $dobMonth[0];?></td>
					<td><?php echo $dobMonth[1];?></td>
					<td><?php echo $dobYear[2];?></td>
					<td><?php echo $dobYear[3];?></td>
                                    </tr>
                                </table>
                            </div>
                        	<div class="clearFix"></div>
                        	<div class="" style="padding-left:88px; width:150px;">D&nbsp;&nbsp;&nbsp;&nbsp;D&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;&nbsp;Y&nbsp;&nbsp;&nbsp;&nbsp;Y</div>
                        	</div>
                        
                            <div class="previewFieldBox" style="width:140px;">
                                <label style="width:auto">Sex:</label>
                                <div class="previewFieldBox" style="width:90px">
				    <?php if(isset($gender) && $gender!=''){ ?>
                                    <div class="checkBox"><?php echo ($gender=='MALE')?'M':'<strike>M</strike>'?></div>
                                    <div class="checkBox"><?php echo ($gender=='FEMALE')?'F':'<strike>F</strike>'?></div>
				    <?php }else{ ?>
                                    <div class="checkBox">M</div>
                                    <div class="checkBox">F</div>
				    <?php } ?>
                                </div>
                            </div>
                        
                            <div class="previewFieldBox" style="width:150px;">
                                <div class="previewFieldBox" style="width:103px">
                                    <div class="checkBox" style="margin:0 30px 0 3px"><?php if($maritalStatus=='SINGLE') echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />"; ?></div>
                                    <div class="checkBox"><?php if($maritalStatus=='MARRIED') echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                            <div class="clearFix"></div>
                        	<div class="">Single &nbsp;&nbsp;&nbsp; Married</div>
                        </div>
                    </li>
                    
                    	<li>
                        	<label style="width:95px"><span>A-3</span>Religion:</label>
                            <div class="previewFieldBox" style="width:200px">
                                <span>&nbsp;<?=$religion?></span>
                            </div>
                            
                            <div class="previewFieldBox" style="width:400px; padding-left:40px">
                                <label style="width:auto; padding-top:4px">Community / Caste: </label>
                                <div class="previewFieldBox" style="width:200px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
					<?php if(isset($categoryAmrita) && $categoryAmrita!=''){ ?>
					<td><?php echo ($categoryAmrita=="OC")?"OC":"<strike>OC</strike>";?></td>
					<td><?php echo ($categoryAmrita=="BC")?"BC":"<strike>BC</strike>";?></td>
					<td><?php echo ($categoryAmrita=="OBC")?"OBC":"<strike>OBC</strike>";?></td>
					<td><?php echo ($categoryAmrita=="MBC")?"MBC":"<strike>MBC</strike>";?></td>
					<td><?php echo ($categoryAmrita=="SC")?"SC":"<strike>SC</strike>";?></td>
					<td><?php echo ($categoryAmrita=="ST")?"ST":"<strike>ST</strike>";?></td>
					<?php }else{ ?>
                                        <td>OC</td>
                                        <td>BC</td>
                                        <td>OBC</td>
                                        <td>MBC</td>
                                        <td>SC</td>
                                        <td>ST</td>
					<?php } ?>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
						<div class="spacer15 clearFix"></div>
                        <div class="formColumns2" style="width:400px;">
                        	<label style="width:auto; padding-left:20px"><span>&nbsp;</span>Mother Tongue:</label>
                            <div class="previewFieldBox" style="width:250px">
                            	<span>&nbsp;<?=$motherTongueAmrita?></span>
                            </div>
                        </div>
                        
                        <div class="formColumns2" style="width:400px;">
                        	<label style="width:auto; padding-left:20px"><span>&nbsp;</span>Blood Group:</label>
                            <div class="previewFieldBox" style="width:150px">
                            	<span>&nbsp;<?=$bloodGroupAmrita?></span>
                            </div>
                        </div>
                    </li>
                    
                    	<li>
                        	<label style="width:95px; font-weight:bold"><span>A-4</span>Address:</label>
                            <div class="clearFix spacer5"></div>
                            <label style="width:105px;"><span>(a)&nbsp;&nbsp;</span>Permanent:</label>
                            <div class="addressBox">
                                <div class="previewFieldBox2">
                                    <span>&nbsp;<?php echo $houseNumber;
									if($streetName) echo ', '.$streetName;
									if($area) echo ', '.$area;
									if($city) echo ', '.$city;
								?></span>
                                </div>
                                <div class="clearFix spacer5"></div>
                                <div class="previewFieldBox2">
                                    <span>&nbsp;<?php 	if($state) echo $state;
									if($country) echo ', '.$country;?>
				    </span>
                                </div>
                                
                                <div class="clearFix spacer15"></div>
                            	<div class="formColumns2" style="width:250px;">
                                    <label style="width:auto; padding-top:4px">Pin</label>
                                    <div class="formColumns2" style="width:180px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					  <?php echo displayFormDataInBoxes(6,$pincode); ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            	<div class="formColumns2" style="width:230px;">
                                    <label style="width:auto; padding-top:4px">STD</label>
                                    <div class="formColumns2" style="width:150px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(5,$landlineSTDCode); ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            	<div class="formColumns2" style="width:280px;">
                                    <label style="width:auto; padding-top:4px">Tel</label>
                                    <div class="formColumns2" style="width:250px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(8,$landlineNumber); ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        	<div class="clearFix spacer20"></div>
                            <label style="width:105px;"><span>(b)&nbsp;&nbsp;</span>Mailing:</label>
                            <div class="addressBox">
                                <div class="previewFieldBox2">
                                    <span>&nbsp;<?php echo $ChouseNumber;
									if($CstreetName) echo ', '.$CstreetName;
									if($Carea) echo ', '.$Carea;
									if($Ccity) echo ', '.$Ccity;
								?></span>
                                </div>
                                <div class="clearFix spacer5"></div>
                                <div class="previewFieldBox2">
                                    <span>&nbsp;<?php 	if($Cstate) echo $Cstate;
									if($Ccountry) echo ', '.$Ccountry;?>
				    </span>
                                </div>
                                
                                <div class="clearFix spacer15"></div>
                            	<div class="formColumns2" style="width:250px;">
                                    <label style="width:auto; padding-top:4px">Pin</label>
                                    <div class="formColumns2" style="width:180px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					  <?php echo displayFormDataInBoxes(6,$Cpincode); ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            	<div class="formColumns2" style="width:230px;">
                                    <label style="width:auto; padding-top:4px">STD</label>
                                    <div class="formColumns2" style="width:150px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(5,$landlineSTDCode); ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            	<div class="formColumns2" style="width:280px;">
                                    <label style="width:auto; padding-top:4px">Tel</label>
                                    <div class="formColumns2" style="width:250px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(8,$landlineNumber); ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="clearFix spacer15"></div>
                            	<div class="formColumns2" style="width:400px;">
                                    <label style="width:auto">e-mail</label>
                                    <div class="previewFieldBox2" style="width:340px">
                                    <span>&nbsp;<?=$email?></span>
                                </div>
                            </div>
                            
                            	<div class="formColumns2" style="width:360px;">
                                    <label style="width:auto; padding-top:4px">Cell No.</label>
                                    <div class="formColumns2" style="width:300px">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(10,$mobileNumber); ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                    	<label style="font-weight:bold; width:500px"><span>B.</span>FAMILY DETAILS</label>
                        <div class="spacer10 clearFix"></div>
                        <label style="width:285px"><span>B-1</span>Father's / Guardian's Name &amp; Occupation :</label>
                        <div class="previewFieldBox2" style="width:593px;">
                        	<span>&nbsp;<?php echo $fatherName; echo ($fatherOccupation!='')?', '.$fatherOccupation:'';?></span>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <label style="width:285px"><span>B-2</span>Mother's Name &amp; Occupation :</label>
                        <div class="previewFieldBox2" style="width:593px;">
                        	<span>&nbsp;<?php echo $MotherName; echo ($MotherOccupation!='')?', '.$MotherOccupation:'';?></span>
                        </div>
                    </li>
                    
                    <li>
                    	<label style="font-weight:bold; width:700px"><span>C.</span>PREFERENCE OF CAMPUSES - <strong style="font-weight:normal">(Write 1-4 in the boxes, 1 indicating first choice)</strong></label>
                        <div class="spacer10 clearFix"></div>
                        <div class="" style="padding-left:30px">
                        	<div class="preffCol">
                                <div class="checkBoxLabel2">Amritapuri</div>
                                <div class="checkBox">
				      <?php
					    if($pref1Amrita=='Amritapuri') echo "1";
					    else if($pref2Amrita=='Amritapuri') echo "2";
					    else if($pref3Amrita=='Amritapuri') echo "3";
					    else if($pref4Amrita=='Amritapuri') echo "4";
				      ?>
				</div>
                            </div>
                            
                            <div class="preffCol">
                                <div class="checkBoxLabel2">Bengaluru</div>
                                <div class="checkBox">
				      <?php
					    if($pref1Amrita=='Bengaluru') echo "1";
					    else if($pref2Amrita=='Bengaluru') echo "2";
					    else if($pref3Amrita=='Bengaluru') echo "3";
					    else if($pref4Amrita=='Bengaluru') echo "4";
				      ?>
				</div>
                            </div>
                            
                            <div class="preffCol">
                                <div class="checkBoxLabel2">Coimbatore</div>
                                <div class="checkBox">
				      <?php
					    if($pref1Amrita=='Coimbatore') echo "1";
					    else if($pref2Amrita=='Coimbatore') echo "2";
					    else if($pref3Amrita=='Coimbatore') echo "3";
					    else if($pref4Amrita=='Coimbatore') echo "4";
				      ?>
				</div>
                            </div>
                            
                            <div class="preffCol">
                                <div class="checkBoxLabel2">Kochi</div>
                                <div class="checkBox">
				      <?php
					    if($pref1Amrita=='Kochi') echo "1";
					    else if($pref2Amrita=='Kochi') echo "2";
					    else if($pref3Amrita=='Kochi') echo "3";
					    else if($pref4Amrita=='Kochi') echo "4";
				      ?>
				</div>
                            </div>
                            
                            <div class="spacer20 clearFix"></div>
                            <div class="spacer10 clearFix"></div>
                            <strong style="font-size:16px">Residential Status Preferred:</strong>
                        	<div class="spacer10 clearFix"></div>
                            <div class="preffCol">
                                <div class="checkBoxLabel2">Residential</div>
                                <div class="checkBox"><?php if($residentAmrita=='Residential') echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />"; ?></div>
                            </div>
                            
                            <div class="preffCol">
                                <div class="checkBoxLabel2">Day scholar*</div>
                                <div class="checkBox"><?php if($residentAmrita=='Day scholar') echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />"; ?></div>
                            </div>
                        </div>
                    </li>
                    
                  <li>
                  	<label style="font-weight:bold; width:500px"><span>D.</span>ACADEMIC PERFORMANCE</label>
                    <div class="spacer10 clearFix"></div>
                    <label style="width:700px"><span>D-1</span>Education (Please enclose attested copies of all mark sheets and degree certificate received.)</label>
                    <div class="spacer10 clearFix"></div>
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
                    <label style="width:700px"><span>D-2</span>Please account for breaks in your academic career, if any.</label>
                    <div class="spacer5 clearFix"></div>
                    <div style="padding-left:35px">&nbsp;<?=$breakAmrita?></div>
                    
                    <div class="spacer15 clearFix"></div>
                    <label style="width:700px"><span>D-3</span>Meritorious Achievements : Academic/Professional Awards/Medals/Prizes/Scholarship/Certificates/Honours, etc.,</label>
                    <div class="spacer5 clearFix"></div>
                    <div style="padding-left:35px; float:left">(For co-curricular activities, use section F )<br /><br />
					(Please enclose attested copies)</div>
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
                  	<label style="font-weight:bold; width:500px"><span>E.</span>WORK EXPERIENCE <strong style="font-weight:normal">( Please furnish a certificate from the last employer)</strong></label>
                    <div class="clearFix spacer10"></div>
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
                  	<label style="font-weight:bold; width:500px"><span>F.</span>MAJOR CO-CURRICULAR ACTIVITIES</label>
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
                  		<label style="font-weight:bold; width:700px"><span>G.</span>PLEASE FURNISH TWO REFERENCES <strong style="font-weight:normal">(SHOULD NOT BE CLOSE RELATIVES)</strong></label>
                    	<div class="clearFix spacer5"></div>
                    	<p style="padding-left:50px">ASB may contact the referees, if necessary.</p>
                    	<div class="clearFix spacer10"></div>
                        
                        <div class="referenceLftCol">
                        	<p>(1)</p>
                            <ul>
                            	<li>
                                	<label>Name</label>
                                    <div class="previewFieldBox2 previewFieldWidth">
                                    	<span>&nbsp;<?=$ref1NameAmrita?></span>
                                    </div>
                                </li>
                                
                                <li>
                                	<label>Occupation</label>
                                    <div class="previewFieldBox2 previewFieldWidth">
                                    	<span>&nbsp;<?=$ref1OccupationAmrita?></span>
                                    </div>
                                </li>
                                
                                <li>
                                	<label>Address</label>
                                    <div class="previewFieldBox2 previewFieldWidth">
                                    	<span>&nbsp;<?=substr($ref1AddressAmrita,0,35)?></span>
                                        <div class="spacer10 clearFix"></div>
                                    	<span>&nbsp;<?=substr($ref1AddressAmrita,35,35)?></span>
                                        <div class="spacer10 clearFix"></div>
                                    	<span>&nbsp;<?=substr($ref1AddressAmrita,70)?></span>
                                    </div>
                                </li>
                                
                                <li>
                                	<div class="clearFix spacer5"></div>
                                	<label style="text-align:right; padding-right:15px; padding-top:4px">Pin</label>
                                    <div class="previewFieldBox2" style="width:188px">
                                    	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(6,$ref1PinAmrita); ?>
                                        </tr>
                                    </table>
                                    </div>
                                </li>
                                
                                <li>
                                	<div class="clearFix spacer5"></div>
                                	<div class="formColumns2" style="width:190px">
                                    	<label style="width:30px; padding-top:4px">STD</label>
                                    	<table width="148" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(5,$ref1STDAmrita); ?>
                                        </tr>
                                    </table>
                                    </div>
                                    
                                    <div class="formColumns2" style="width:248px">
                                    	<label style="width:20px; padding-top:4px">Tel</label>
                                    	<table width="220" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(6,$ref1PhoneAmrita); ?>
                                        </tr>
                                    </table>
                                    </div>
                                </li>
                                <li>
                                	<div class="spacer5 clearFix"></div>
                                	<label>e-mail:</label>
                                    <div class="previewFieldBox2 previewFieldWidth">
                                    	<span>&nbsp;<?=$ref1EmailAmrita?></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="referenceRtCol">
                        	<p>(2)</p>
                            <ul>
                            	<li>
                                	<label>Name</label>
                                    <div class="previewFieldBox2 previewFieldWidth">
                                    	<span>&nbsp;<?=$ref2NameAmrita?></span>
                                    </div>
                                </li>
                                
                                <li>
                                	<label>Occupation</label>
                                    <div class="previewFieldBox2 previewFieldWidth">
                                    	<span>&nbsp;<?=$ref2OccupationAmrita?></span>
                                    </div>
                                </li>
                                
                                <li>
                                	<label>Address</label>
                                    <div class="previewFieldBox2 previewFieldWidth">
                                    	<span>&nbsp;<?=substr($ref1AddressAmrita,0,35)?></span>
                                        <div class="spacer10 clearFix"></div>
                                    	<span>&nbsp;<?=substr($ref1AddressAmrita,35,35)?></span>
                                        <div class="spacer10 clearFix"></div>
                                    	<span>&nbsp;<?=substr($ref1AddressAmrita,70)?></span>
                                    </div>
                                </li>
                                
                                <li>
                                	<div class="clearFix spacer5"></div>
                                	<label style="text-align:right; padding-right:15px; padding-top:4px">Pin</label>
                                    <div class="previewFieldBox2" style="width:188px">
                                    	<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(6,$ref2PinAmrita); ?>
                                        </tr>
                                    </table>
                                    </div>
                                </li>
                                <li>
                                	<div class="clearFix spacer5"></div>
                                	<div class="formColumns2" style="width:190px">
                                    	<label style="width:30px; padding-top:4px">STD</label>
                                    	<table width="148" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(5,$ref2STDAmrita); ?>
                                        </tr>
                                    </table>
                                    </div>
                                    
                                    <div class="formColumns2" style="width:248px">
                                    	<label style="width:20px; padding-top:4px">Tel</label>
                                    	<table width="220" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                        <tr>
					      <?php echo displayFormDataInBoxes(6,$ref2PhoneAmrita); ?>
                                        </tr>
                                    </table>
                                    </div>
                                </li>
                                
                                <li>
                                	<div class="spacer5 clearFix"></div>
                                	<label>e-mail:</label>
                                    <div class="previewFieldBox2 previewFieldWidth">
                                    	<span>&nbsp;<?=$ref2EmailAmrita?></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                   	
                    <li style="margin-bottom:10px">
                  	<label style="font-weight:bold; width:885px; text-align:center">REFLECTIVE ESSAYS (H TO J)<br /><strong style="font-weight:normal">USE ADDITIONAL SHEETS</strong>
</label>
                    <div class="clearFix spacer10"></div>
                    <strong>Write Reflective essays on the topics given below in about 400 words for each question:</strong>
                    </li>
                    
                    <li>
                  		<label style="font-weight:bold; width:700px"><span>H.</span><strong style="font-weight:normal">How will you introduce yourself to your classmates when you join the MBA class of <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2012";}?> at ASB?</strong></label>
                        <div class="clearFix spacer5"></div>
                        <p style="padding-left:30px"><?=$introduceAmrita?></p>
                    </li>

		    
                    
                    <li>
                  		<label style="font-weight:bold; width:700px"><span>I.</span><strong style="font-weight:normal">What are your career objectives and what do you need to learn at ASB to accomplish them?</strong></label>
                        <div class="clearFix spacer5"></div>
                        <p style="padding-left:30px"><?=$careerAmrita?></p>
                    </li>
		    
                    
                    <li>
                  		<label style="font-weight:bold; width:700px"><span>J.</span><strong style="font-weight:normal">Tell us about a mistake you made in your life and what you learned from that experience?</strong></label>
                        <div class="clearFix spacer5"></div>
                        <p style="padding-left:30px"><?=$mistakeAmrita?></p>
                    </li>
		    
                    
                    <li>
                  		<label style="font-weight:bold; width:700px"><span>K.</span><strong style="font-weight:normal">How did you come to know of <strong>ASB?</strong></strong></label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
                        	<label>CAT Coaching Centres :</label>
                            <div class="preffCol2">
                                <div class="checkBox"><?php if(strpos($coachingAmrita,'IMS') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                                <div class="checkBoxLabel">IMS</div>
                            </div>
                            
                            <div class="preffCol2">
                                <div class="checkBox"><?php if(strpos($coachingAmrita,'T.I') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                                <div class="checkBoxLabel">T.I.M.E.</div>
                            </div>
                            
                            <div class="preffCol2">
                                <div class="checkBox"><?php if(strpos($coachingAmrita,'PT') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                                <div class="checkBoxLabel">PT Education</div>
                            </div>
                            
                            <div class="preffCol2" style="padding-right:50px">
                                <div class="checkBox"><?php if(strpos($coachingAmrita,'Launcher') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                                <div class="checkBoxLabel">Career Launcher</div>
                            </div>
                            
                            <div class="preffCol2">
                                <div class="checkBoxLabel">Other &nbsp;&nbsp;&nbsp;(Please specify): <?=$coachingOtherAmrita?></div>
				
                            </div>
                            
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
                        	<label style="width:auto">Newspaper :</label>
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Hindustan Times</div>
                                <div class="checkBox"><?php if(strpos($newspaperAmrita,'Hindustan') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Indian Express</div>
                                <div class="checkBox"><?php if(strpos($newspaperAmrita,'Express') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Times of India</div>
                                <div class="checkBox"><?php if(strpos($newspaperAmrita,'of India') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">The Hindu</div>
                                <div class="checkBox"><?php if(strpos($newspaperAmrita,'The Hindu') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Telegraph</div>
                                <div class="checkBox"><?php if(strpos($newspaperAmrita,'Telegraph') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
                        	<label style="width:auto">Internet :</label>
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Shiksha.com</div>
                                <div class="checkBox"><?php if(strpos($internetAmrita,'Shiksha') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>

                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Cool avenues.com</div>
                                <div class="checkBox"><?php if(strpos($internetAmrita,'Cool') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">AIMA</div>
                                <div class="checkBox"><?php if(strpos($internetAmrita,'AIMA') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Amrita</div>
                                <div class="checkBox"><?php if(strpos($internetAmrita,'Amrita') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">IIMs</div>
                                <div class="checkBox"><?php if(strpos($internetAmrita,'IIMs') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">MBA Universe</div>
                                <div class="checkBox"><?php if(strpos($internetAmrita,'Universe') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">PagalGuy</div>
                                <div class="checkBox"><?php if(strpos($internetAmrita,'pagal') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Other</div>
                                <div class="previewFieldBox" style="width:125px"><span>&nbsp;<?=$internetOtherAmrita?></span></div>
                            </div>
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
                        	<div class="preffCol3">
                            	<div class="checkBoxLabel2">Alumni</div>
                                <div class="checkBox"><?php if(strpos($otherSourcesAmrita,'Alumni') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Amrita University</div>
                                <div class="checkBox"><?php if(strpos($otherSourcesAmrita,'Amrita') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Friends</div>
                                <div class="checkBox"><?php if(strpos($otherSourcesAmrita,'Friends') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">M.A. MATH</div>
                                <div class="checkBox"><?php if(strpos($otherSourcesAmrita,'MATH') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Relatives</div>
                                <div class="checkBox"><?php if(strpos($otherSourcesAmrita,'Relatives') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></div>
                            </div>
                            
                            <div class="preffCol3">
                            	<div class="checkBoxLabel2">Other (Please specify)</div>
                                <div class="previewFieldBox" style="width:170px"><span>&nbsp;<?=$otherSourcesOtherAmrita?></span></div>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                  		<label style="font-weight:bold; width:700px"><span>L.</span>DECLARATION:</label>
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
                  		<label style="font-weight:bold; width:700px"><span>M.</span>UNDERTAKING:</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:35px">
                        <div class="flLt">I</div> <div class="previewFieldBox2" style="width:325px"><span>&nbsp;&nbsp;<?=$fatherName?></span></div> 
			<?php if(isset($firstName) && $firstName!=''){ ?>
			<div class="flLt">father/<strike>mother</strike>/<strike>guardian</strike> of</div>	
			<?php }else{ ?>
			<div class="flLt">father/mother/guardian of</div>	
			<?php } ?>
			<div class="previewFieldBox2" style="width:325px"><span>&nbsp;&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span></div>
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
               <div class="clearFix"></div>
            </div>
         </div>
    </div>
    <!--Amrita Form Preview Ends here-->
