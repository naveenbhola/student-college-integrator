   	<!--Indira Form Preview Starts here--> 
	<link href="/public/css/onlineforms/indra/Indira_styles.css" rel="stylesheet" type="text/css"/>

    <div class="formPreviewMain">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
		<div class="previewHeader">
        	<div class="instLogoBox"><img src="/public/images/onlineforms/institutes/indra/logo.gif" alt="" /></div>
            <div class="courseNameDetails">
            	<p>
               	<strong>Indira School Of Business Studies</strong><br />
				Shree Chanakya Education Society,<br />
				ICAP Department,<br />
				Tathawade,  Pune - 411033<br />	
                </p>
            </div>
            <div class="clearFix"></div>         
        </div>
        <div class="clearFix"></div>      
        <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  <strong class="applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
			      <?php } ?>
		<div class="clearFix spacer5"></div>
       	<div class="previewBody">
        	<div class="formRows">
            	<div class="personalDetailsSection">
                <h3>Personal Details</h3>
            	<div class="reviewLeftCol">
                	<div class="formGreyBox">
                    	<ul>
                        	<li>
                        	<label style="padding-left:0 !important">Salutation:</label>
                        	<div class="previewFieldBox2" style="width:100px"><span>&nbsp;<?=$salutationIndra?></span></div>
                    		</li>
                            <div class="spacer15 clearFix"></div>
                        	<li>
                            	<div class="personalInfoCol">
                                	<label style="padding-left:0 !important">First Name:</label>
                                	<span><?=$firstName?></span>
                            	</div>
                                
                                <div class="personalInfoCol">
                                	<label  style="padding-left:0 !important">Middle Name:</label>
                                	<span><?=$middleName?></span>
                            	</div>
                                
                                <div class="personalInfoCol">
                                	<label  style="padding-left:0 !important">Last Name:</label>
                                	<span><?=$lastName?></span>
                            	</div>
                                <div class="clearFix"></div>
                        	</li>
                    	</ul>
                	</div>
                
                	<div class="spacer20 clearFix"></div>
                	<ul class="reviewChildLeftCol">
                    	
                    	<li>
                        	<label> Date of birth:</label>
                        	<div class="previewFieldBox2" style="width:256px"><span>&nbsp;<?php echo str_replace("/","-",$dateOfBirth);?></span></div>
                    	</li>
                        <li>
                        	<label>Category:</label>
                        	<div class="previewFieldBox2" style="width:276px"><span>&nbsp;<?=$categoryIndra?></span></div>
                        </li>
                     </ul>
                
                	<ul class="reviewChildRightCol">
                    	<li>
                        	<label>Gender:</label>
                        	<div class="previewFieldBox2" style="width:270px"><span>&nbsp;<?=$gender?></span></div>
                    	</li>
                     </ul>
	            </div>
            	<div class="picBox">
			<?php if($profileImage) { ?>
			    <img alt="Profile Pic"  width="195" height="192" src="<?php echo $profileImage; ?>" />
			<?php } ?>
            	</div>
			</div>
            	
                <div class="clearFix spacer10"></div>
                <div class="educationalFormSection">
                <h3>Educational Details</h3>
            	<ul>
                	<li>
                    	<div class="reviewFormLeft">
                            <label>Highest Qualification:</label>
                            <div class="previewFieldBox2" style="width:315px"><span>&nbsp;<?=$highestQIndra?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>Education Type:</label>
                            <div class="previewFieldBox2" style="width:350px"><span>&nbsp;<?=$eduTypeIndra?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>Aggregate % on Qualifying degree:</label>
                            <div class="previewFieldBox2" style="width:229px"><span>&nbsp;<?=$graduationPercentage?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>University Name:</label>
                            <div class="previewFieldBox2" style="width:344px"><span>&nbsp;<?=$graduationBoard?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>Twelfth Marks (%):</label>
                            <div class="previewFieldBox2" style="width:338px"><span>&nbsp;<?=$class12Percentage?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>	Tenth Marks (%):</label>
                            <div class="previewFieldBox2" style="width:349px"><span>&nbsp;<?=$class10Percentage?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                </ul>
                
                <div class="clearFix spacer10"></div>
                <h3>Present Address</h3>
            	<ul>
                	<li>
                    	<label>Address Line 1:</label>
                        <div class="previewFieldBox2" style="width:829px"><span>&nbsp;<?php echo $houseNumber;
									if($area) echo ', '.$area;
								?></span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<label>Address Line 2:</label>
                        <div class="previewFieldBox2" style="width:829px"><span>&nbsp;<?php if($streetName) echo $streetName; ?></span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<label>Address Line 3:</label>
                        <div class="previewFieldBox2" style="width:829px"><span>&nbsp;</span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>City:</label>
                            <div class="previewFieldBox2" style="width:418px"><span>&nbsp;<?php if($city) echo $city; ?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>State:</label>
                            <div class="previewFieldBox2" style="width:412px"><span>&nbsp;<?php if($state) echo $state; ?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>Pincode:</label>
                            <div class="previewFieldBox2" style="width:389px"><span>&nbsp;<?php if($pincode) echo $pincode; ?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>Phone Number:</label>
                            <div class="previewFieldBox2" style="width:345px"><span>&nbsp;<?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber; ?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>	Mobile Number:</label>
                            <div class="previewFieldBox2" style="width:348px"><span>&nbsp;<?=$mobileNumber;?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>Primary Email:</label>
                            <div class="previewFieldBox2" style="width:349px"><span>&nbsp;<?=$email?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>	Retype Primary Email :</label>
                            <div class="previewFieldBox2" style="width:302px"><span>&nbsp;<?=$email?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                </ul>

                <div class="clearFix spacer10"></div>
                <h3>Correspondence Address</h3>
            	<ul>
                	<li>
                    	<label>Address Line 1:</label>
                        <div class="previewFieldBox2" style="width:829px"><span>&nbsp;<?php echo $ChouseNumber;
									if($Carea) echo ', '.$Carea;
								?></span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<label>Address Line 2:</label>
                        <div class="previewFieldBox2" style="width:829px"><span>&nbsp;<?php if($CstreetName) echo $CstreetName; ?></span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<label>Address Line 3:</label>
                        <div class="previewFieldBox2" style="width:829px"><span>&nbsp;</span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>City:</label>
                            <div class="previewFieldBox2" style="width:418px"><span>&nbsp;<?php if($Ccity) echo $Ccity; ?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>State:</label>
                            <div class="previewFieldBox2" style="width:412px"><span>&nbsp;<?php if($Cstate) echo $Cstate; ?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>Pincode:</label>
                            <div class="previewFieldBox2" style="width:389px"><span>&nbsp;<?php if($Cpincode) echo $Cpincode; ?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                    
                </ul>
                
                <div class="clearFix spacer10"></div>
                <h3>Test City Details</h3>
            	<ul>
                	<li>
                    	<label>Test City:</label>
                        <div class="previewFieldBox2" style="width:872px"><span>&nbsp;<?=$gdpiLocation?></span></div>
                        <div class="clearFix"></div>
                	</li>
                </ul>
                
                <div class="clearFix spacer10"></div>
                <h3>GD/PI Dates</h3>
            	<ul>
                	<li>
                    	<label>GD/PI Appearing Date:</label>
                        <div class="previewFieldBox2" style="width:782px"><span>&nbsp;<?php echo date('d M, Y',strtotime($gdpiDateIndra));?></span></div>
                        <div class="clearFix"></div>
                	</li>
                </ul>
                
                <div class="clearFix spacer10"></div>
                <h3>Other Details</h3>
            	<ul>
                	<li>
                    	<div class="reviewFormLeft">
                            <label>How did you get to know about ICAP:</label>
                            <div class="previewFieldBox2" style="width:215px"><span>&nbsp;<?=$knowIndra?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>If Newspaper Specify:</label>
                            <div class="previewFieldBox2" style="width:309px"><span>&nbsp;<?=$newspaperDetailsIndra?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<div class="reviewFormLeft">
                            <label>Appeared Test Name:</label>
                            <div class="previewFieldBox2" style="width:318px"><span>&nbsp;<?=$testNameIndra?></span></div>
                        </div>
                        
                        <div class="reviewFormRight">
                            <label>Enter the percentile score of CAT,MAT, etc:</label>
                            <div class="previewFieldBox2" style="width:179px"><span>&nbsp;<?=$percentileIndra?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>

                    <li>
                    	<div class="reviewFormLeft" style="width: 600px;">
                            <label>Holds valid scores of more than 20 percentile for Appeared Test Name:</label>
                            <div class="previewFieldBox2" style="width:118px"><span>&nbsp;<?=$percentile20Indra?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                </ul>
                
                <div class="clearFix spacer10"></div>
                <h3>Institute Preference</h3>
            	<ul>
                	<li>
                    	<label>Preference 1:</label>
                        <div class="previewFieldBox2" style="width:840px"><span>&nbsp;<?=$pref1Indra?></span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<label>Preference 2:</label>
                        <div class="previewFieldBox2" style="width:840px"><span>&nbsp;<?=$pref2Indra?></span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                    <li>
                    	<label>Preference 3:</label>
                        <div class="previewFieldBox2" style="width:840px"><span>&nbsp;<?=$pref3Indra?></span></div>
                        <div class="clearFix"></div>
                	</li>
                    
                </ul>

                <div class="clearFix spacer10"></div>
                <h3>Course Preference for ISBS</h3>
            	<ul>

                	<li>
                    	<div class="reviewFormLeft" style="width:418px">
			    <label>PGDM 1st shift:</label>
                            <div class="previewFieldBox2" style="width:267px"><span>&nbsp;<?=$coursePref1Indra?></span></div>
                        </div>
                        
                        <div class="reviewFormRight" style="width:418px">
			    <label>PGDM 2nd shift:</label>
                            <div class="previewFieldBox2" style="width:296px"><span>&nbsp;<?=$coursePref2Indra?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>

                	<li>
                    	<div class="reviewFormLeft" style="width:418px">
			    <label>PGDM Marketing:</label>
                            <div class="previewFieldBox2" style="width:250px"><span>&nbsp;<?=$coursePref3Indra?></span></div>
                        </div>
                        
                        <div class="reviewFormRight" style="width:418px">
			    <label>PGDM International Business:</label>
                            <div class="previewFieldBox2" style="width:208px"><span>&nbsp;<?=$coursePref4Indra?></span></div>
                        </div>
                        <div class="clearFix"></div>
                	</li>
                    
                </ul>
                
                <div class="clearFix"></div>
			</div>
            
                	
               <div class="clearFix"></div>
            </div>
            
        </div>
        
    </div>
    <!--Indira Form Preview Ends here-->
