<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">
            <div class="inst-logo"><img src="/public/images/onlineforms/institutes/kiams/logo.gif" alt="" /></div>
            <div class="inst-name">
                <h5>KIRLOSKAR INSTITUTE OF ADVANCED <br />MANAGEMENT STUDIES</h5>
                <!--<p>No. 1, Chamundi Hill Road, Siddarthanagar Post, Mysore – 570011,Karnataka, India.
    Phone :91-821-2429722/2429161,Fax: 91-821-2425557, Website: www.sdmimd.ac.in</p>-->
            </div>
            <div class="clearFix spacer10"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: PGDM <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2014";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2016";}?></div>  
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
		    <img width="195" height="180" src="<?php echo $profileImage; ?>" border=0 />
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
        <?php } ?>
        	<li>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
            	<label>Name of the Applicant: </label>
                <div class="form-details">&nbsp;<?php echo $userName; ?></div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Father's Name: </label>
                    <div class="form-details">&nbsp;<?=$fatherName?></div>
                </div>
                
                <div class="colums-width">
                    <label>Mother's Name: </label>
                    <div class="form-details">&nbsp;<?=$MotherName?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Father's Occupation: </label>
                    <div class="form-details">&nbsp;<?=$fatherOccupation?></div>
                </div>
            
            	<div class="colums-width">
                    <label>Mother's Occupation: </label>
                    <div class="form-details">&nbsp;<?=$MotherOccupation?></div>
                </div>
            </li>
            
            <li>
            	<label>CAT : Reg No:SR: </label>
                <div class="form-details">&nbsp;<?=$catRollNumberAdditional; ?></div>
            </li>
            
            <li>
            	<label>XAT ID:</label>
                <div class="form-details">&nbsp;<?=$xatRollNumberAdditional; ?></div>
            </li>
            
            <li>
            	<label>CMAT : Roll NO:</label>
                <div class="form-details">&nbsp;<?=$cmatRollNumberAdditional; ?></div>
            </li>

            <li>
                <label>GMAT : Roll NO:</label>
                <div class="form-details">&nbsp;<?=$gmatRollNumberAdditional; ?></div>
            </li>

            <li>
		<div class="flLt" style="width:248px;">
		    <label>MAT : Roll NO:</label>
		    <div class="form-details">&nbsp;<?=$matRollNumberAdditional; ?></div>
		</div>
		<div class="flLt" style="width:248px;">
		    <label>Form NO:</label>
		    <div class="form-details">&nbsp;<?=$matFormnoAdditionalKIAMS; ?></div>
		</div>
            </li>

            <li>
            	<div class="colums-width" style="width:250px">
                    <label>Date of Birth:</label>
                    <div class="form-details">&nbsp;<?=$dateOfBirth;?></div>
                </div>
            	
                <div class="colums-width" style="width:197px">
                    <label>Age:</label>
                    <div class="form-details">&nbsp;<?=$ageKIAMS;?></div>
                </div>
                
                <div class="colums-width" style="width:227px">
                    <label>Gender M/F:</label>
                    <div class="form-details">&nbsp;<?=$gender?></div>
                </div>
            </li>
            
            <li>
            	<h3 class="form-title">Contact Information</h3>
            	<label>Address for  Communication:</label>
                <div class="form-details">&nbsp;<?php if($ChouseNumber) echo $ChouseNumber.', ';
									if($CstreetName) echo $CstreetName.', ';
                                   if($Carea) echo $Carea.', '; 
                                    ?></div>
            </li>
            
            <li>
            	<div style="width:250px; float:left">
                    <label>City:</label>
                    <div class="form-details"><?=$Ccity;?></div>
                </div>
            
            	<div style="width:197px; float:left">
                    <label>State:</label>
                    <div class="form-details">&nbsp;<?=$Cstate;?></div>
                </div>
            	
                <div style="width:227px; float:left">
                    <label>Pin:</label>
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
            	<label>Permanent Address:</label>
                <div class="form-details">&nbsp;<?php if($houseNumber) echo $houseNumber.', ';
									if($streetName) echo $streetName.', ';
                                    if($area) echo $area.', ';
                                    ?></div>
            </li>
            
            <li>
            	<div style="width:250px; float:left">
                    <label>City:</label>
                    <div class="form-details"><?=$city;?></div>
                </div>
            	
                <div style="width:197px; float:left">
                    <label>State:</label>
                    <div class="form-details">&nbsp;<?=$state;?></div>
                </div>
            	
                <div style="width:227px; float:left">
                    <label>Pin:</label>
                    <div class="form-details"><?=$pincode; ?></div>
                </div>
            </li>
            
            <li>
            	<label>Email Id (Registered with CAT/XAT/CMAT):</label>
                <div class="form-details">&nbsp;<?=$email;?></div>
            </li>
            
            <li>
            	<h3 class="form-title">Academic Details</h3>
				<table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
                  	<th width="150">Examination</th>
                    <th width="230">School/College/ University<br />Specify CBSE/ICSE/State/Others</th>
                    <th width="100">Name of <br />the Degree</th>
                    <th width="90">Marks (%) / <br />Grade Point</th>
                    <th width="90">Specialization</th>
                    <th width="75">Year of <br />Passing</th>
                  </tr>
                  
                  <tr>
                    <td width="150" valign="top"><div style="width:150px">10th / Matriculation</div></td>
                    <td width="230" valign="top"><div style="width:230px"><?php if($class10School){ ?><?=$class10School?> , <?=$class10Board?><?php } ?></div></td>
                    <td width="100" valign="top"><div style="width:100px"><?=$class10ExaminationName?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=$class10Percentage?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=$class10SubjectsKIAMS?></div></td>
                    <td width="75" valign="top"><div style="width:75px"><?=$class10Year?></div></td>
                  </tr>
                  <tr>
                    <td valign="top"><div style="width:150px">12th / PUC <br /> or equivalent</div></td>
                    <td width="230" valign="top"><div style="width:230px"><?php if($class12School){ ?><?=$class12School?> , <?=$class12Board?><?php } ?></div></td>
                    <td width="100" valign="top"><div style="width:100px"><?=$class12ExaminationName?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=$class12Percentage?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=$class12SubjectsKIAMS?></div></td>
                    <td width="75" valign="top"><div style="width:75px"><?=$class12Year?></div></td>
                  </tr>
                  
                  <tr>
                    <td valign="top"><div style="width:150px">Bachelor's Degree<br />(If not yet completed give latest Marks & date of completion)
</div></td>
                    <td width="230" valign="top"><div style="width:230px"><?php if($graduationSchool){ ?><?=$graduationSchool?> , <?=$graduationBoard?><?php } ?></div></td>
                    <td width="100" valign="top"><div style="width:100px"><?=$graduationExaminationName?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=$graduationPercentage?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=$gradSubjectsKIAMS?></div></td>
                    <td width="75" valign="top"><div style="width:75px"><?=$graduationYear?></div></td>
                  </tr>
		<?php
		$otherCourseShown = false;
		for($i=1;$i<=4;$i++){
		if(${'graduationExaminationName_mul_'.$i}){ $otherCourseShown = true;
		?>
                  <tr>
                    <td valign="top"><div style="width:150px">Any other Qualification/s</div></td>
                    <td width="230" valign="top"><div style="width:230px"><?=${'graduationBoard_mul_'.$i}?> , <?=${'graduationSchool_mul_'.$i}?></div></td>
                    <td width="100" valign="top"><div style="width:100px"><?=${'graduationExaminationName_mul_'.$i}?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=${'graduationPercentage_mul_'.$i}?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=${'otherCourseSubject_mul_'.$i}?></div></td>
                    <td width="75" valign="top"><div style="width:75px"><?=${'graduationYear_mul_'.$i}?></div></td>
                  </tr>
		<?php }
		} ?>
                
	
		<?php if(!$otherCourseShown){ ?>
		  <tr>
                    <td valign="top"><div style="width:150px">Any other Qualification/s</div></td>
                    <td width="230" valign="top"><div style="width:230px"></div></td>
                    <td width="100" valign="top"><div style="width:100px"></div></td>
                    <td width="90" valign="top"><div style="width:90px"></div></td>
                    <td width="90" valign="top"><div style="width:90px"></div></td>
                    <td width="75" valign="top"><div style="width:75px"></div></td>
                  </tr>
		<?php } ?>        
                </table>

            </li>
            <li>
            	<h3 class="form-title">Work Experience:</h3>
                                                 <div class="preff-cont">Yes <span class="option-box"><?php
                                                                       if($expKIAMS=="YES") echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />";
                                                                                                             ?></span></div>
                                                                                                                             <div class="preff-cont">No <span class="option-box"><?php
                                                                                                                                                   if($expKIAMS=="NO") echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />";
                                                                                                                                                                                         ?></span>
                                                                                                                                                                                         </div>
<?php if($expKIAMS=="YES" || !isset($expKIAMS)){?><div class="preff-cont"> Number of Months :&nbsp;<span><?=$experienceMonthKIAMS;?></span></div>                                                                                                                                                                                                     
<?php } ?>
 <div class="spacer5 clearFix"></div>
  <strong>Details of Work Experience (if applicable)</strong>
                  <div class="spacer5 clearFix"></div>

                <table  border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
                  	<th width="250"><div style="width:250px;">Name of the Company/<br />Organisation</div></th>
                    <th width="230"><div style="width:230px;">Details of work experience<br />Employment from & to date</div.</th>
                    <th width="150"><div style="width:150px;">Designation</div></th>
                    <th width="220"><div style="width:220px;">Responsibilities</div></th>
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
                $durationTo = trim(${'weTill'.$mulSuffix})?${'weTill'.$mulSuffix}:'Till Date';
			    $designation = ${'weDesignation'.$mulSuffix};
			    $natureOfWork = ${'weRoles'.$mulSuffix};

			    if($companyName || $designation){ $workExGiven = true; $total++; ?>
                  <tr>
                    <td width="250" valign="top"><div style="width:250px"><?php echo $companyName; ?></div></td>
                    <td width="230" valign="top"><div style="width:230px">
                    	From: <?php echo $durationFrom; ?>
                        <br />
						To: <?php echo $durationTo; ?>
                    </div></td>
                    <td width="150" valign="top"><div style="width:150px"><?php echo $designation; ?></div></td>
                    <td width="220" valign="top"><div style="width:220px"><?php echo $natureOfWork; ?></div></td>
                  </tr>
		  <?php }} ?>
                </table>
            </li>
            
            <li style="margin-top:20px;">
            	<label>Past Achievements:</label> Please list a <strong>max</strong> of five of your achievements. (academic, non academic, personal, etc.) Attach extra sheet if applicable.
                <table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
                  	<th width="50">Sl.No</th>
                    <th width="">Achievements</th>
                  </tr>
                  
                  <tr>
                    <td width="50" align="center" valign="top"><div style="width:50px">1.</div></td>
                    <td width="" valign="top"><div style="width:810px"><?=$achievements1KIAMS?></div></td>
                  </tr>
                  
                  <tr>
                    <td width="50" valign="top" align="center"><div style="width:50px">2.</div></td>
                    <td width="" valign="top"><div style="width:810px"><?=$achievements2KIAMS?></div></td>
                  </tr>
                  
                  <tr>
                    <td width="50" valign="top" align="center"><div style="width:50px">3.</div></td>
                    <td width="" valign="top"><div style="width:810px"><?=$achievements3KIAMS?></div></td>
                  </tr>
                  
                  <tr>
                    <td width="50" valign="top" align="center"><div style="width:50px">4.</div></td>
                    <td width="" valign="top"><div style="width:810px"><?=$achievements4KIAMS?></div></td>
                  </tr>
                  
                  <tr>
                    <td width="50" valign="top" align="center"><div style="width:50px">5.</div></td>
                    <td width="" valign="top"><div style="width:810px"><?=$achievements5KIAMS?></div></td>
                  </tr>
                </table>
            </li>
            
            <li>
            	<h3 class="form-title">Your Preference of Centre for Group Work / Interview (please tick any one) :</h3>
                <div class="preff-cont">Delhi <span class="option-box"><?php if($preferredGDPILocation=="74"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Lucknow <span class="option-box"><?php if($preferredGDPILocation=="138"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Indore <span class="option-box"><?php if($preferredGDPILocation=="106"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Kolkata <span class="option-box"><?php if($preferredGDPILocation=="130"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Bhubaneswar <span class="option-box"><?php if($preferredGDPILocation=="912"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Ahmedabad <span class="option-box"><?php if($preferredGDPILocation=="30"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Hyderabad <span class="option-box"><?php if($preferredGDPILocation=="702"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                
                <div class="preff-cont">Bangalore <span class="option-box"><?php if($preferredGDPILocation=="278"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Harihar <span class="option-box"><?php if($preferredGDPILocation=="10524"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Pune <span class="option-box"><?php if($preferredGDPILocation=="174"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
                <div class="preff-cont">Kochi <span class="option-box"><?php if($preferredGDPILocation=="127"){ echo "<img src='/public/images/onlineforms/institutes/kiams/tick-icn.gif' border=0 />"; } ?></span></div>
            </li>
            
            <li>
            	<h3 class="form-title">Preference of Study Campus (Rank in order of preference):</h3>
                <div class="preff-cont">Harihar <span class="option-box"><?php
				      if($pref1KIAMS=="Harihar") echo "1";
				      else if($pref2KIAMS=="Harihar") echo "2";
				?></span></div>
                <div class="preff-cont">Pune <span class="option-box"><?php
				      if($pref1KIAMS=="Pune") echo "1";
				      else if($pref2KIAMS=="Pune") echo "2";
				?></span></div>
            </li>
            
            <li>
            	<p>I confirm that to the best of my knowledge the information contained in this application is true and accurate. I have gone through the contents of the Prospectus and agree to all the conditions stipulated therein and if admitted, will also abide by the rules and regulations of KIAMS as may be in force from time to time.</p>
                
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
                <div class="spacer15 clearFix"></div>
                
            </li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>

