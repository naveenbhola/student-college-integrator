<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <img src="/public/images/onlineforms/institutes/imi/logo2.jpg" alt="" />
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: PGDM <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2012";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2014";}?></div>  
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
        <?php } ?>
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
            
            <li>
            	<h3 class="form-title">Contact Information</h3>
            	<label>Mailing Address Line 1: </label>
                <div class="form-details"><?php if($ChouseNumber) echo $ChouseNumber.', ';
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
            	<label>Phone No.:</label>
                <div class="form-details"><?php echo ($landlineSTDCode!='')?$landlineSTDCode.'-'.$landlineNumber:$landlineNumber; ?></div>
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
            	<div class="colums-width">
                    <label>Category:</label>
                    <div class="form-details"><?=$categoryIMI;?></div>
                </div>
            	
                <div class="colums-width">
                    <label>Course Name:</label>
                    <div class="form-details"><?=$courseIMI;?></div>
                </div>
            </li>
            
            <li>
            	<label>CAT Registration No (as given in your CAT Admin card):</label>
                <div class="form-details"><?=$catRollNumberAdditional; ?></div>
            </li>
            
            <li>
            	<label>GMAT ID No.:</label>
                <div class="form-details"><?=$gmatRollNumberAdditional; ?></div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Date of GMAT Test:</label>
                    <div class="form-details"><?=$gmatDateOfExaminationAdditional; ?></div>
                </div>
            	
                <div class="colums-width">
                    <label>GMAT Score:</label>
                    <div class="form-details"><?=$gmatScoreAdditional; ?></div>
                </div>
            </li>
            <li>
            	<div class="colums-width">
                    <label>Ist choice of Interview Center:</label>
                    <div class="form-details">
		    <?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=''){ ?>
               <?php if($preferredGDPILocation=='30') echo "Ahmedabad";?>
               <?php if($preferredGDPILocation=='278') echo "Bangalore";?>
               <?php if($preferredGDPILocation=='63') echo "Chandigarh";?>
               <?php if($preferredGDPILocation=='64') echo "Chennai";?>
               <?php if($preferredGDPILocation=='74') echo "Delhi";?>
               <?php if($preferredGDPILocation=='96') echo "Guwahati";?>
               <?php if($preferredGDPILocation=='130') echo "Kolkata";?>
               <?php if($preferredGDPILocation=='151') echo "Mumbai";?>
               <?php if($preferredGDPILocation=='702') echo "Hyderabad";?>
               <?php if($preferredGDPILocation=='912') echo "Bhubaneswar";?>
            <?php }?>
		    </div>
                </div>
            	<div class="colums-width">
                    <label>IInd choice of Interview Center:</label>
                    <div class="form-details">
                   <?php if($pref2IMI=='30') echo "Ahmedabad";?>
                   <?php if($pref2IMI=='278') echo "Bangalore";?>
                   <?php if($pref2IMI=='63') echo "Chandigarh";?>
                   <?php if($pref2IMI=='64') echo "Chennai";?>
                   <?php if($pref2IMI=='74') echo "Delhi";?>
                   <?php if($pref2IMI=='96') echo "Guwahati";?>
                   <?php if($pref2IMI=='130') echo "Kolkata";?>
                   <?php if($pref2IMI=='151') echo "Mumbai";?>
                   <?php if($pref2IMI=='702') echo "Hyderabad";?>
                   <?php if($pref2IMI=='912') echo "Bhubaneswar";?>
                    </div>
                </div>
            </li>
            
            <li>
            	<label>Hostel Accommodation:</label>
                <div class="form-details"><?=$hostelIMI;?></div>
            </li>
            
            <li>
            	<h3 class="form-title">Academic Details</h3>
				<table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
                  	<th width="150">Examination</th>
                    <th width="100">Board</th>
                    <th width="200">Name of School</th>
                    <th width="70">Year</th>
                    <th width="70">Marks</th>
                    <th width="100">Medium of Instruction</th>
                  </tr>
                  
                  <tr>
                    <td width="150" valign="top"><div style="width:150px">Class X</div></td>
                    <td width="100" valign="top"><div style="width:230px"><?=$class10Board;?></div></td>
                    <td width="200" valign="top"><div style="width:100px"><?=$class10School?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?=$class10Year?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?=$class10Percentage?></div></td>
                    <td width="100" valign="top"><div style="width:75px"><?=$class10MediumIMI;?></div></td>
                  </tr>
                                    
                  <tr>
                    <td><div style="width:150px">Class XII</div></td>
                    <td width="100" valign="top"><div style="width:230px"><?=$class12Board;?></div></td>
                    <td width="200" valign="top"><div style="width:100px"><?=$class12School;?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?=$class12Year;?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?=$class12Percentage;?></div></td>
                    <td width="100" valign="top"><div style="width:75px"><?=$class12MediumIMI;?></div></td>
                  </tr>
		   <tr>
                    <td><div style="width:150px">Name of the Degree: <?php echo $graduationExaminationName;?></div></td>
                    <td width="100" valign="top"><div style="width:230px"><?php echo $graduationBoard;?></div></td>
                    <td width="200" valign="top"><div style="width:100px"><?php echo $graduationSchool;?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?php echo $graduationYear;?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?php echo $graduationPercentage;?></div></td>
                    <td width="100" valign="top"><div style="width:75px"><?php echo $gradMediumIMI;?></div></td>
                  </tr>
		  <?php for($j=1;$j<=4;$j++):?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
                  <tr>
                    <td><div style="width:150px">Any other Qualification/s : <?php echo ${'graduationExaminationName_mul_'.$j};?></div></td>
                    <td width="100" valign="top"><div style="width:230px"><?php echo ${'graduationBoard_mul_'.$j};?></div></td>
                    <td width="200" valign="top"><div style="width:100px"><?php echo ${'graduationSchool_mul_'.$j};?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?php echo ${'graduationYear_mul_'.$j};?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?php echo ${'graduationPercentage_mul_'.$j};?></div></td>
                    <td width="100" valign="top"><div style="width:75px"><?php echo ${'otherCourseMedium_mul_'.$j};?></div></td>
                  </tr>
		  <?php endif;endfor; //endif;?>
                </table>
                <div class="spacer20 clearFix"></div>
                <strong>Additional Academic Details:</strong>
                <div class="spacer5 clearFix"></div>
                <table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                <tr>
                <th width="200">Examination</th>
                <th width="200">University Type</th>
                <th width="200">Mode</th>
                <th width="100">Stream</th>
                </tr>
                <tr>
                <td width="200" valign="top"><div style="width:150px">Class XII</div></td>
                <td width="200" valign="top"><div style="width:230px">Not Applicable</div></td>
                <td width="200" valign="top"><div style="width:100px">Not Applicable</div></td>
                <td width="100" valign="top"><div style="width:90px"><?=$class12SpecIMI;?></div></td>
                </tr>
                <tr>
                <td width="200" valign="top"><div style="width:150px">Graduation Course</div></td>
                <td width="200" valign="top"><div style="width:230px"><?=$gradCollegeTypeIMI;?></div></td>
                <td width="200" valign="top"><div style="width:100px"><?=$gradModeIMI;?></div></td>
                <td width="100" valign="top"><div style="width:90px"><?php echo $graduationExaminationName;?></div></td>
                </tr>
                 <?php for($j=1;$j<=4;$j++):?>
                                     <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
                    <tr>
                    <td width="200" valign="top"><div style="width:150px">Other Course <?=$j;?></div></td>
                    <td width="200" valign="top"><div style="width:230px"><?=${'otherCourseCollegeType_mul_'.$j}?></div></td>
                    <td width="200" valign="top"><div style="width:100px"><?=${'otherCourseMode_mul_'.$j}?>
                    <td width="100" valign="top"><div style="width:90px"><?=${'graduationExaminationName_mul_'.$j}?></div></td>
                    </tr>
                 <?php endif;endfor; //endif;?>
                </table>

            </li>
            
            <li>
            	<h3 class="form-title">Work Experience:</h3>
                <table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
                  	<th width="300">Name of the current employer/ last employer</th>
                    <th width="200">Work Experience as on 1st March 2013</th>
                  </tr>
                 <?php $flag = 'false';?> 
                 <?php for($i=2; $i>=0; $i--){ ?>
                <?php $mulSuffix = $i>0?'_mul_'.$i:'';
                      $otherSuffix = '_mul_'.$i;
                      $companyName = ${'weCompanyName'.$mulSuffix};
                     //i( $companyName!='' && $flag=='false'){
                       //weCompanyName = $companyName;
                        //lag = 'true';
                     //}
                     if(${'weTimePeriod'.$mulSuffix}!='' && $flag=='false'){
                            $weCompName = $companyName;
                        $flag = 'true';
                     }
                 ?>
                 <?php } ?>
                  <tr>
                    <td width="300" valign="top"><div style="width:150px"><?php echo $weCompName;?></div></td>
                    <td width="200" valign="top"><div style="width:230px"><?php echo $workExIMI;?></div></td>
                  </tr>
                </table>
            </li>
            
            <li>
            	<h3 class="form-title">Declaration</h3>
            	<p>I certfiy that the particulars given by me are true to the best of my knowledge and belief. I understand that IMI, India will have the right to ask me to withdraw from the programme if any discrepancies are found in the information furnished. I will also abide by the general discipline and norms of conduct during the programme. </p>
                
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
            </li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>
