<?php
function checkInterviewCenter($cityName,$first,$second,$third){
	if($cityName == $first) return '1';
	if($cityName == $second) return '2';
	if($cityName == $third) return '3';
	return '';	
}
?>
<style>
	.preff-cont{width : 119px;}
	.preff-cont .option-box{float: right;}
</style>
<div id="custom-form-main">
	<div id="custom-form-header">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
                        <div style="float:right">Application Ref. No. <?=$instituteSpecId?></div>
                         <div class="clearFix spacer10"></div>

    	<div class="app-left-box">
            <div class="inst-logo"><img src="/public/images/onlineforms/institutes/sdm/logoSmall.jpg" alt="" /></div>
            <div class="inst-name">
                <h5>Shri Dharmasthala Manjunatheshwara<br />Institute for Management Development</h5>
                <p>No. 1, Chamundi Hill Road, Siddarthanagar Post, Mysore – 570011,Karnataka, India.<br/>
    Phone :91-821-2429722/2429161,Fax: 91-821-2425557, Website: www.sdmimd.ac.in</p>
            </div>
            <div class="clearFix spacer10"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
	    <div class="appForm-box">Application Form: PGDM 2015-17</div>  
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
    </div>
    <div class="spacer15 clearFix"></div>
    <div id="custom-form-content">
    	<ul>
        	<li>
            	<label>Name: </label>
                <div class="form-details">
			<span><?php echo $firstName;?></span>
			<span><?php if(empty($middleName)) {echo "&nbsp;";} else {echo "&nbsp;".$middleName."&nbsp;";}?></span>
			<span><?php echo $lastName;?></span>
		</div>
            </li>
            
            <li>
            	<label>Parent Name: </label>
                <div class="form-details"><?=$fatherName?></div>
            </li>
            
            <li>
            	<label>Registration No. CAT/XAT/GMAT/CMAT:</label>
		<div class="clearBoth">&nbsp;</div>
                <div class="form-details">
			<?php if($catRollNumberAdditional!='' && strpos($testNamesSDM,'CAT')!==false){ echo "<br/>CAT Registration No.: ".$catRollNumberAdditional; }?>
			<?php if($xatRollNumberAdditional!='' && strpos($testNamesSDM,'XAT')!==false) echo "<br/>XAT Registration No.: ".$xatRollNumberAdditional; ?>
			<?php if($gmatRollNumberAdditional!='' && strpos($testNamesSDM,'GMAT')!==false) echo "<br/>GMAT Registration No.: ".$gmatRollNumberAdditional; ?>
			<?php if($cmatRollNumberAdditional!='' && strpos($testNamesSDM,'CMAT')!==false) echo "<br/>CMAT Registration No.: ".$cmatRollNumberAdditional; ?>
		</div>
            </li>
            
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>Date of Birth:</label>
                    <div class="form-details"><?php if($dateOfBirth){
                        $dateArr = explode('/',$dateOfBirth);
                        echo $dateArr[0]."-".$dateArr[1]."-".$dateArr[2];
                        } ?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>Age:</label>
                    <div class="form-details"><?=$age?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>Gender:</label>
                    <div class="form-details"><?=$gender?></div>
                </div>
            </li>

            <li>
                <label>Passport No / Voter id No./Aadhar Card No / Driving Licence No./ PAN No.: </label>
                <div class="form-details"><?=$idNumberSDM?></div>
            </li>
			
			
            <li>
                <h3 class="form-title">Contact Information</h3>
                <label>Address for Correspondence:</label>
                <div class="form-details"><?php echo $ChouseNumber;?><?php if($CstreetName!='') echo ", ".$CstreetName;?><?php if($Carea!='') echo ", ".$Carea;?></div>
            </li>

            <li>
                <div class="colums-width" style="width:220px;">
                    <label>City:</label>
                    <div class="form-details"><?php echo $Ccity;?></div>
                </div>

                <div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?php echo $Cstate;?></div>
                </div>

                <div class="colums-width" style="width:227px;">
                    <label>Pin:</label>
                    <div class="form-details"><?php echo $Cpincode;?></div>
                </div>
            </li>

            <li>
                <div class="colums-width">
                    <label>Phone /Parent's Contact No.:</label>
                    <div class="form-details"><?=$contactNoSDM?></div>
                </div>

                <div class="colums-width">
                    <label>Applicant's Mobile No:</label>
                    <div class="form-details"><?php if($mobileNumber!='') echo $mobileISDCode.'-'.$mobileNumber;?></div>
                </div>
            </li>

            <li>
                <label>Email id (Registered with CAT/XAT):</label>
                <div class="form-details"><div class="clearFix"></div><?php if($catEmailId!='') echo "<br/>Email id registered with CAT: ".$catEmailId; if($xatEmailId!='') echo "<br/>Email id registered with XAT: ".$xatEmailId; ?></div>
            </li>

            
            <li>
            	<h3 class="form-title">Academic Details</h3>
				<table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
                  	<th width="150">Examination</th>
                    <th width="230">School/College/ University<br />Specify CBSE/ICSE/State/Others</th>
                    <th width="100">Name of <br />Degree</th>
                    <th width="90">Marks (%) / <br />Grade Point</th>
                    <th width="90">Specialization</th>
                    <th width="75">Year of <br />Passing</th>
                  </tr>
                  
                  <tr>
                    <td width="150" valign="top"><div style="width:150px">10th / Matriculation</div></td>
		    <?php if($class10School){ ?>
                    <td width="230" valign="top"><div style="width:230px"><?=$class10School?> - <?=$class10Board?></div></td>
		    <?php }else{ ?>
                    <td width="230" valign="top"><div style="width:230px"></div></td>
		    <?php } ?>
                    <td width="100" valign="top"><div style="width:100px">NA</div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=$class10Percentage?></div></td>
                    <td width="90" valign="top"><div style="width:90px">NA</div></td>
                    <td width="75" valign="top"><div style="width:75px"><?=$class10Year?></div></td>
                  </tr>
                  <tr>
                    <td><div style="width:150px">II PUC / +2 <br />/ Intermediate</div></td>
		    <?php if($class12School){ ?>
                    <td width="230" valign="top"><div style="width:230px"><?=$class12School?> - <?=$class12Board?></div></td>
		    <?php }else{ ?>
                    <td width="230" valign="top"><div style="width:230px"></div></td>
		    <?php } ?>
                    <td width="100" valign="top"><div style="width:100px">NA</div></td>
                    <td width="90" valign="top"><div style="width:90px"><?=$class12Percentage?></div></td>
                    <td width="90" valign="top"><div style="width:90px">NA</div></td>
                    <td width="75" valign="top"><div style="width:75px"><?=$class12Year?></div></td>
                  </tr>
                  
                  <tr>
                    <td><div style="width:150px">Degree Final /<br /> Cumulative Marks</div></td>
		    <?php if($graduationSchool){ ?>
                    <td width="230" valign="top"><div style="width:230px"><?php echo $graduationSchool;?> - <?php echo $graduationBoard;?></div></td>
		    <?php }else{ ?>
                    <td width="230" valign="top"><div style="width:230px"></div></td>
		    <?php } ?>
                    <td width="100" valign="top"><div style="width:100px"><?php echo $graduationExaminationName;?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?php echo $graduationPercentage;?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?php echo $graduationSpecialization;?></div></td>
                    <td width="75" valign="top"><div style="width:75px"><?php echo $graduationYear;?></div></td>
                  </tr>
		<?php
		    $show = false;
		    for($j=1;$j<=4;$j++):?>
		<?php if(!empty(${'graduationExaminationName_mul_'.$j})):
			$show = true;
		?>
                  <tr>
                    <td><div style="width:150px">Post-Graduation/Others</div></td>
                    <td width="230" valign="top"><div style="width:230px"><?php echo ${'graduationSchool_mul_'.$j};?> - <?php echo ${'graduationBoard_mul_'.$j};?></div></td>
                    <td width="100" valign="top"><div style="width:100px"><?php echo ${'graduationExaminationName_mul_'.$j};?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?php echo ${'graduationPercentage_mul_'.$j};?></div></td>
                    <td width="90" valign="top"><div style="width:90px"><?php echo ${'otherCourseSpecialization_mul_'.$j};?></div></td>
                    <td width="75" valign="top"><div style="width:75px"><?php echo ${'graduationYear_mul_'.$j};?></div></td>
                  </tr>
		<?php endif;endfor;?>
		<?php if(!$show){ ?>
                  <tr>
                    <td><div style="width:150px">Post-Graduation/Others</div></td>
                    <td width="230" valign="top"><div style="width:230px"></div></td>
                    <td width="100" valign="top"><div style="width:100px"></div></td>
                    <td width="90" valign="top"><div style="width:90px"></div></td>
                    <td width="90" valign="top"><div style="width:90px"></div></td>
                    <td width="75" valign="top"><div style="width:75px"></div></td>
                  </tr>
		<?php } ?>
                </table>

            </li>

	<?php 
	$workExGiven = false;
	$totalDuration = array();
	$totalDuration['months'] = 0;
	for($i=0; $i<4; $i++){
		$mulSuffix = $i>0?'_mul_'.$i:'';
		$otherSuffix = '_mul_'.$i;
		$durationFrom = ${'weFrom'.$mulSuffix};
		$durationTo = trim(${'weTimePeriod'.$mulSuffix})?'Till date':${'weTill'.$mulSuffix};
		if($durationFrom) {
			$startDate = getStandardDate($durationFrom);
			$endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
			$totalDuration = getTimeDifference($startDate,$endDate);
			
		}
	} ?>            
            <li>
            	<label>Work Experience in months:</label>
                <div class="form-details"><?php if($Cpincode){if($workExSDM!='') echo $workExSDM; else echo "N/A";}?></div>
            </li>
            
            <li>
            	<h3 class="form-title">Extracurricular Activities</h3>
                <div class="form-details"><?=$extraCurricular_SDM?></div>
            </li>

            <li>
            	<h3 class="form-title">Preference for Interview Centre (1,2,3)</h3>
                <div class="preff-cont">Ahmedabad <span class="option-box"><?php echo checkInterviewCenter('30',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Bangalore <span class="option-box"><?php echo checkInterviewCenter('278',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Chennai <span class="option-box"><?php echo checkInterviewCenter('64',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Hyderabad <span class="option-box"><?php echo checkInterviewCenter('702',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Kochi <span class="option-box"><?php echo checkInterviewCenter('127',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Bhopal<span class="option-box"><?php echo checkInterviewCenter('55',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Bhubaneswar <span class="option-box"><?php echo checkInterviewCenter('912',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
				<div class="preff-cont">Coimbatore<span class="option-box"><?php echo checkInterviewCenter('67',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
				<div class="preff-cont">Dharwad <span class="option-box"><?php echo checkInterviewCenter('78',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
				<div class="preff-cont">Patna <span class="option-box"><?php echo checkInterviewCenter('171',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>

				<div class="preff-cont">Kolkata <span class="option-box"><?php echo checkInterviewCenter('130',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>

                <div class="preff-cont">Lucknow <span class="option-box"><?php echo checkInterviewCenter('138',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Pune <span class="option-box"><?php echo checkInterviewCenter('174',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Mysore <span class="option-box"><?php echo checkInterviewCenter('153',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">New Delhi <span class="option-box"><?php echo checkInterviewCenter('74',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                
                <div class="preff-cont">Mumbai <span class="option-box"><?php echo checkInterviewCenter('151',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
                <div class="preff-cont">Jaipur <span class="option-box"><?php echo checkInterviewCenter('109',$preferredGDPILocation,$gdpiLocation2,$gdpiLocation3); ?></span></div>
            </li>
            
            <li>
            	<h3 class="form-title">DECLARATION</h3>
                <label>I hereby state that all the above details are true to the best of my knowledge and I will produce proof as relevant if asked.</label>
                <div class="form-details"></div>
                
                <div class="spacer15 clearFix"></div>
                <label>Place:</label>
                <div class="form-details"><?php if(isset($firstName) && $firstName!='') {echo $Cstate;} ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label>Date:</label>
                <div class="form-details">&nbsp;
						<?php
						      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
							      echo date("d/m/Y", strtotime($paymentDetails['date']));
							}
						?>
		</div>
                
                <div class="spacer15 clearFix"></div>
                <label>Signature of the Candidate:</label>
                <div class="form-details">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></div>
                <div class="spacer15 clearFix"></div>
            </li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>
