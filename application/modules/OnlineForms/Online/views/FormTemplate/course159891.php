<style>
@media print
 {
.breakings {page-break-after: left}

 }
</style>

<?php $valuePrefix = '&nbsp;'; ?>
<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />

<!--Indira Form Preview Starts here--> 
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <div class="float_L"><img src="/public/images/onlineforms/institutes/indra/logo.gif" alt="" /></div>
		<div class="">
		    <p style="font-size:18px;line-height:23px;">
				    Shree Chanakya Education Society,<br />
				    ICAP Department,<br />
				    Tathawade,  Pune - 411033<br />	
		    </p>
		</div>
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">APPLICATION FORM FOR PGDM 2013-2015</div>  
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
        <label>Serial No. : </label>
                        <div class="form-details"><?php echo $instituteSpecId; ?></div>
        </li>
        <?php }?>

            <li>
            	<div class="colums-width" style="width:300px;">
                    <label>First Name :</label>
                    <div class="form-details"><?=$firstName;?></div>
                </div>
            	<div class="colums-width" style="width:250px;">
                    <label>Middle Name :</label>
                    <div class="form-details"><?=$middleName;?></div>
                </div>
            	
                <div class="colums-width" style="width:300px;">
                    <label>Last Name :</label>
                    <div class="form-details"><?=$lastName; ?></div>
                </div>
            </li>
	
           <li> 
            	<div class="colums-width">
                    <label>Date of Birth : </label>
                    <div class="form-details"><?php echo str_replace("/","-",$dateOfBirth);?></div>
                </div>
		<div class="colums-width">
		    <label>Category : </label>
		    <div class="form-details"><?=$categoryIndra?></div>
		</div>
            </li>

           <li> 
            	<div class="colums-width">
                    <label>Gender : </label>
                    <div class="form-details"><?=$gender?></div>
                </div>
            </li>

	    <li>
		<h3 class="form-title">Education Details</h3>
		<div class="colums-width">
		    <label>Qualifying Degree Status :</label>
		    <div class="form-details">&nbsp;<?=$qualifyDegreeIndra?></div>
		</div>
		
		<div class="colums-width">
		    <label>Highest Qualification :</label>
		    <div class="form-details">&nbsp;<?=$highestQIndra?></div>
		</div>
	    </li>
	    
	    <li>
		<div class="colums-width">
		    <label>Education Type :</label>
		    <div class="form-details">&nbsp;<?=$eduTypeIndra?></div>
		</div>
		<?php if($otherEducationIndra!=''){ ?>
		<div class="colums-width">
		    <label>Specify Others :</label>
		    <div class="form-details">&nbsp;<?=$otherEducationIndra?></div>
		</div>
		<?php } ?>
	    </li>
		
	    <li>
		<div class="colums-width">
		    <label>Aggregate % on Qualifying degree :</label>
		    <div class="form-details">&nbsp;<?=$qualifyDegreePercentageIndra?></div>
		</div>
		
		<div class="colums-width">
		    <label>University Name :</label>
		    <div class="form-details">&nbsp;<?=$qualifyDegreeUniversityIndra?></div>
		</div>
	    </li>
		
	    <li>
		<div class="colums-width">
		    <label>Twelfth Marks (%) : </label>
		    <div class="form-details"><?=$class12Percentage?></div>
		</div>
		
		<div class="colums-width">
		    <label>Tenth Marks (%) : </label>
		    <div class="form-details" ><?=$class10Percentage?></div>
		</div>
	    </li>
	    

            <li>
		<h3 class="form-title">Present Address</h3>
		<label>Address Line 1:</label>
		<div style="width:829px"><span>&nbsp;<?php echo $houseNumber;
								if($area) echo ', '.$area;
							?></span></div>
            </li>

	    <li>
		<label>Address Line 2:</label>
		<div style="width:829px"><span>&nbsp;<?php if($streetName) echo $streetName; ?></span></div>
	    </li>
	    
	    <li>
		<label>Address Line 3:</label>
		<div style="width:829px"><span>&nbsp;</span></div>
	    </li>
            	    
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City :</label>
                    <div class="form-details"><?=$city;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State :</label>
                    <div class="form-details"><?=$state;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code :</label>
                    <div class="form-details"><?=$pincode; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Phone Number :</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $std.$landlineNumber; ?></div>
               	</div>
            	<div class="colums-width">
                    <label>Mobile Number :</label>
                    <div class="form-details">&nbsp;<?php if($mobileISDCode) echo $mobileISDCode.'-'; echo $mobileNumber;?></div>
                </div>
            </li>

	    <li>
		<div class="colums-width">
		    <label>Primary Email:</label>
		    <div class="form-details">&nbsp;<?=$email?></div>
		</div>
		
		<div class="colums-width">
		    <label>Retype Primary Email :</label>
		    <div class="form-details">&nbsp;<?=$email?></div>
		</div>
	    </li>

           
            <li>
		<h3 class="form-title">Correspondence Address</h3>
		<label>Address Line 1:</label>
		<div style="width:829px"><span>&nbsp;<?php echo $ChouseNumber;
								if($Carea) echo ', '.$Carea;
							?></span></div>
            </li>

	    <li>
		<label>Address Line 2:</label>
		<div style="width:829px"><span>&nbsp;<?php if($CstreetName) echo $CstreetName; ?></span></div>
	    </li>
	    
	    <li>
		<label>Address Line 3:</label>
		<div style="width:829px"><span>&nbsp;</span></div>
	    </li>
            	    
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City :</label>
                    <div class="form-details"><?=$Ccity;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State :</label>
                    <div class="form-details"><?=$Cstate;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code :</label>
                    <div class="form-details"><?=$Cpincode; ?></div>
                </div>
            </li>

            <li>
		<h3 class="form-title">Test City Details</h3>
                <label>Test City :</label>
                <div style="width:872px"><span>&nbsp;<?=$gdpiLocation?></span></div>
            </li>

            <li>
		<h3 class="form-title">GD/PI Dates</h3>
                <label>GD/PI Appearing Date :</label>
                <div style="width:872px">&nbsp;<?php if(isset($gdpiDateIndra) && $gdpiDateIndra!=''){ echo date('d M, Y',strtotime($gdpiDateIndra)); }?></div>
            </li>



	    <li>
		<h3 class="form-title">Other Details</h3>
		<div class="colums-width">
		    <label>How did you get to know about ICAP :</label>
		    <div class="form-details">&nbsp;<?=$knowIndra?></div>
		</div>
		<?php if($newspaperDetailsIndra!=''){ ?>		
		<div class="colums-width">
		    <label>If Newspaper Specify :</label>
		    <div class="form-details">&nbsp;<?=$newspaperDetailsIndra?></div>
		</div>
		<?php } ?>
	    </li>

	    <li>
		<div>
		    <label>Qualifying Test Status :</label>
		    <div class="form-details">&nbsp;<?=$testStatusIndra?></div>
		</div>	
	    </li>

	    <?php if($testStatusIndra!='To Appear'){ ?>
	    <li>
		<div class="colums-width">
		    <label>Appeared Test Name :</label>
		    <div class="form-details">&nbsp;<?=$testNameIndra?></div>
		</div>
		
		<div class="colums-width">
		    <label>Percentile Score :</label>
		    <div class="form-details">&nbsp;<?=$percentileIndra?></div>
		</div>
	    </li>
	    <?php } ?>

            <li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I, &nbsp; <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
		, hereby certify that the information furnished in the Application Form is complete, accurate and true. I have carefully read the contents of the Brochure.
                
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
<!--Indira Form Preview Ends here-->
