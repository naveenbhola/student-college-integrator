<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <img src="/public/images/onlineforms/institutes/fore/logo2.jpg" alt="" />
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: PGDM <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2013";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+1;} else{echo "2014";}?></div>  
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
                        <div class="form-details"><?php echo $instituteSpecId; ?></div>
        </li>
        <?php }?>
	    
	    <li>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
		<div class="colums-width">
            	<label>Name: </label>
                <div class="form-details"><?php echo $salutationMICA.'. '.$userName; ?></div>
		</div>
  
            	<div class="colums-width">
                    <label>Date Of Birth: </label>
                    <div class="form-details"><?=$dateOfBirth;?></div>
                </div>
           </li> 
           <li> 
            	<div class="colums-width">
                    <label>Gender: </label>
                    <div class="form-details"><?=$gender?></div>
                </div>
		<div class="colums-width">
			<label>Nationality: </label>
			<div class="form-details"><?php echo $nationality; ?></div>
		</div>
            </li>

            <li>
            	<h3 class="form-title">Course Information</h3>
            	<div class="colums-width">
                    <label>CAT Registration No.: </label>
                    <div class="form-details"><?=$catRollNumberAdditional?></div>
                </div>
		<div class="colums-width">
			<label>Course Code: </label>
			<div class="form-details"><?php echo $courseCodeFORE; ?></div>
		</div>
            </li>
            <li>
            	<div class="colums-width">
                    <label>Category: </label>
                    <div class="form-details"><?=$categoryFORE?></div>
                </div>
		<div class="colums-width">
			<label>Choice of Interview center: </label>
			<div class="form-details">&nbsp;
			<?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=''){ ?>
			    <?php if($preferredGDPILocation=='278') echo "Bangalore";?>
			    <?php if($preferredGDPILocation=='64') echo "Chennai";?>
			    <?php if($preferredGDPILocation=='74') echo "Delhi";?>
			    <?php if($preferredGDPILocation=='96') echo "Guwahati";?>
			    <?php if($preferredGDPILocation=='130') echo "Kolkata";?>
			    <?php if($preferredGDPILocation=='151') echo "Mumbai";?>
			 <?php }?>
			</div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>Hostel Accommodation Required: </label>
                    <div class="form-details"><?=$hostelFORE?></div>
                </div>
            </li>
      
            <li>
            	<h3 class="form-title">Communication Information</h3>
            	<label>Correspondence Address: </label>
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
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$Cpincode; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Tel. No. with STD Code:</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $std.$landlineNumber; ?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>Mobile Number:</label>
                    <div class="form-details">&nbsp;<?php if($mobileISDCode) echo $mobileISDCode.'-'; echo $mobileNumber;?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Email:</label>
                    <div class="form-details">&nbsp;<?=$email;?></div>
                </div>
            </li>
	            
            
            <li>
            	<h3 class="form-title">Class XII Academic details</h3>
		<table width="100%" border="1" cellspacing="0" cellpadding="5" class="form-tabular-data">
                  <tr>
		    <th width="150">Board</th>
                    <th width="100">Year of Passing</th>
                    <th width="200">Percentage of Marks</th>
                    <th width="70">Stream</th>
                  </tr>
                  
                  <tr>
                    <td width="100" valign="top"><div style="width:230px"><?php if($class12Board) echo $class12Board; else echo "&nbsp;";?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?=$class12Year;?></div></td>
                    <td width="70" valign="top"><div style="width:90px"><?=$class12Percentage;?></div></td>
                    <td width="100" valign="top"><div style="width:75px"><?=$class12StreamFORE?></div></td>
                  </tr>
                </table>
                <div class="spacer20 clearFix"></div>
            </li>

            <li>
            	<h3 class="form-title">Graduation acedemic details</h3>
            	<div class="colums-width">
                    <label>Academic Stream:</label>
                    <div class="form-details">&nbsp;<?php echo $gradStreamFORE; ?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>Subject:</label>
                    <div class="form-details">&nbsp;<?=$gradSubjectsFORE?></div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>Actual/Expected Year of Completion:</label>
                    <div class="form-details">&nbsp;<?=$graduationYear?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>Duration in Years:</label>
                    <div class="form-details">&nbsp;<?=$gradDurationFORE?></div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>Mode of Study:</label>
                    <div class="form-details">&nbsp;<?=$gradModeFORE?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>University Code:</label>
                    <div class="form-details">&nbsp;<?=$gradCodeFORE?></div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>Maximum Marks / CGPA:</label>
                    <div class="form-details">&nbsp;<?=$gradMaxMarksFORE?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>Marks / CGPA Obtained:</label>
                    <div class="form-details">&nbsp;<?=$gradMarksFORE?></div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>Overall Percentage:</label>
                    <div class="form-details">&nbsp;<?=$gradPercentageFORE?></div>
               	</div>
            </li>
	    
            <li>
            	<h3 class="form-title">Work experience details</h3>
            	<div class="colums-width">
                    <label>Work experience (in Months) as on 30/11/2012:</label>
                    <div class="form-details">&nbsp;<?=$workExFORE?></div>
               	</div>
            </li>
	    
	     <li>
            	<h3 class="form-title">Other details</h3>
		<label>How did you find out about FORE: </label>
                <div class="form-details"><?php echo $sourceFORE;?>
		</div>		
	     </li>
	     
            <li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I, &nbsp; <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
		, declare that the particulars given are correct to the best of my knowledge and belief. If at any stage it is found that any of the information is incorrect, I will withdraw from the programme and will not claim any refund.
                
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
