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
	<div style="float:right">Application Ref. No. <?=$instituteInfo['sessionYear']?>/<?=$instituteInfo['courseTitle']?>/S<?=$instituteSpecId?></div>
            <div class="inst-name" style="width:97%;">
                <div class="float_L"><img src="/public/images/onlineforms/institutes/indra/logo2_2015.gif" alt="" /></div>
		<div class="">
		    <p style="font-size:18px;line-height:18px;">
			Shree Chanakya Education Society,
			<br/>Indira School of Business Studies,
			</br>85/5-A, Tathawade, New Pune-Mumbai Highway, Pune - 411033,
			<br/> Maharashtra, India.
			<br/>Ph: 8087428511, E-Mail: pgdm.admissions@indiraiimp.edu.in
		    </p>
		</div>
            </div>
	
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>  
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
	</div>
	
	<div class="clearFix spacer10"></div>
        <div class="appForm-box" style="width: 98%">APPLICATION FORM FOR PGDM 2015-2017</div>
    
    
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
	    
	   		
			
	<?php
		$testsArray = explode(",",$Indra_testNames);
		//_p($testsArray);die;
	?>	
				
	<h3 class="form-title">TESTS:</h3>
	<?php if(in_array("CAT",$testsArray)): ?>
	<ul>			
	<li>
		
		<div class="colums-width">
		    <label>CAT Registration No:</label>
		    <span><?php echo $catRollNumberAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>CAT Date:</label>
		    <span><?php echo $catDateOfExaminationAdditional;?></span>
		</div>
		<?php endif; ?>
	</li>
	<li>
		<?php if(in_array("CAT",$testsArray)): ?>
		<div class="colums-width">
		    <label>CAT Score:</label>
		    <span><?php echo $catScoreAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>CAT Percentile:</label>
		    <span><?php echo $catPercentileAdditional;?></span>
		</div>
		
	</li>
	</ul>
	<?php endif; ?>
	
	
	<?php if(in_array("XAT",$testsArray)): ?>
	<ul>
	<li>	
		<div class="colums-width">
		    <label>XAT Registration No:</label>
		    <span><?php echo $xatRollNumberAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>XAT Date:</label>
		    <span><?php echo $xatDateOfExaminationAdditional;?></span>
		</div>
		<?php endif; ?>
	</li>
	<li>
		<?php if(in_array("XAT",$testsArray)): ?>
		<div class="colums-width">
		    <label>XAT Score:</label>
		    <span><?php echo $xatScoreAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>XAT Percentile:</label>
		    <span><?php echo $xatPercentileAdditional;?></span>
		</div>
	</li>
	</ul>
	<?php endif; ?>
	
	<?php if(in_array("ATMA",$testsArray)): ?>
	<ul>
	<li>	
		<div class="colums-width">
		    <label>ATMA Registration No:</label>
		    <span><?php echo $atmaRollNumberAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>ATMA Date:</label>
		    <span><?php echo $atmaDateOfExaminationAdditional;?></span>
		</div>
	</li>
	<li>
		<?php if(in_array("ATMA",$testsArray)): ?>
		<div class="colums-width">
		    <label>ATMA Score:</label>
		    <span><?php echo $atmaScoreAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>ATMA Percentile:</label>
		    <span><?php echo $atmaPercentileAdditional;?></span>
		</div>
		<?php endif; ?>
	</li>
	</ul>
	<?php endif; ?>
	
	<?php if(in_array("MAT",$testsArray)): ?>
	<ul>
	<li>
		<div class="colums-width">
		    <label>MAT Registration No:</label>
		    <span><?php echo $matRollNumberAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>MAT Date:</label>
		    <span><?php echo $matDateOfExaminationAdditional;?></span>
		</div>
		<?php endif; ?>
	</li>
	<li>
		<?php if(in_array("MAT",$testsArray)): ?>
		<div class="colums-width">
		    <label>MAT Score:</label>
		    <span><?php echo $matScoreAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>MAT Percentile:</label>
		    <span><?php echo $matPercentileAdditional;?></span>
		</div>
		
	</li>
	</ul>
	<?php endif; ?>
	
	<?php if(in_array("CMAT",$testsArray)): ?>
	<ul>
	<li>
		
		<div class="colums-width">
		    <label>CMAT Registration No:</label>
		    <span><?php echo $cmatRollNumberAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>CMAT Date:</label>
		    <span><?php echo $cmatDateOfExaminationAdditional;?></span>
		</div>
		<?php endif; ?>
	</li>
	<li>
		<?php if(in_array("CMAT",$testsArray)): ?>
		<div class="colums-width">
		    <label>CMAT Score:</label>
		    <span><?php echo $cmatScoreAdditional;?></span>
		    
		</div>
		
		<div class="colums-width">
		    <label>CMAT Percentile:</label>
		    <span><?php echo $cmatPercentileIndra;?></span>
		</div>
		
	</li>
	</ul>
	<?php endif; ?>			
	
	
	
	
	<!--Work Exp Info Starts here-->

	
        <li>
                <h3 class="form-title">Work Experience:</h3>
		<div class="colums-width">
                        <label>Company Name:</label>
                        <div style="width:290px; float:left"><?php echo $weCompanyName;?></div>
		</div>
                        	
                <div class="colums-width">
			<label class="timePeriodLabel3">Designation:</label>
			<div style="width:290px; float:left"><?php echo $weDesignation;?></div>
		</div>    
	</li>
	<li>
                <div class="colums-width">
                    	<label>Location:</label>
                        <div style="width:290px; float:left"><?php echo $weLocation;?></div>
                </div> 
                    
                <div class="colums-width">
			<label class="timePeriodLabel3">Time Period:</label>
			<?php if($weTimePeriod):?>
                        <input type="checkbox" checked="checked" disabled="disabled" /> I currently work here<br />
                        <?php else:?>
                        <input type="checkbox" disabled="disabled" /> I currently work here<br />
                        <?php endif;?>
                        <div class="workExpDetails3">
                        	<span>From: <?php if(!empty($weFrom)) {echo date('F Y',strtotime(getStandardDate($weFrom)));}?></span>
                        	<span>&nbsp;&nbsp;-</span>
				<span class="mL10">Till: <?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('F Y',strtotime(getStandardDate($weTill)));}} else {echo "Date";}?></span>
                        </div>
		</div>
        </li>
        
     
     <div class="spacer20 clearFix"></div>
     <div class="rolesResponsiblity">
     	<h3>Roles &amp; Responsibilities:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim($weRoles));?></li>
            </ul>
        </div>
    </div>
    <?php //if(!empty($wecompany_array)):?>
    <?php //$count_company = count($wecompany_array);
	for($i=1;$i<=3;$i++):?>
    <?php if(!empty(${'weCompanyName_mul_'.$i})):?>
   
     <!--Work Exp Info Ends here-->
     
    <?php endif;endfor;//endif;?>
    

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
