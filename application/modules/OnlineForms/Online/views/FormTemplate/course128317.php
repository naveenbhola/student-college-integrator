   	<style>
	label{font-weight:bold !important}
	</style>
	<link href="/public/css/onlineforms/kiits/KIITS_styles.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
    	<div class="previewHeader">
        	<div class="formNumb">
        		<div class="previewFieldBox" style="width:100%;">
                   Application Form No : KSOM/13/<?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?>
                </div>    	
            	<div class="clearFix spacer15"></div>    
                <p></p>
            </div>
        	
            <div class="instLogoBox"><img src="/public/images/onlineforms/institutes/kiits/logo2.gif" alt="" /></div>
            
            <div class="clearFix spacer10"></div>
            
            <div class="courseNameDetails">
            	<p>KIIT School of Management, Bhubaneswar<br />APPLICATION FORM</p>
				<span>Two -year full time residential MBA Programme</span></div>
            <div class="picBox"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
            
            
            <div class="spacer5 clearFix"></div>
            <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  <strong class="applicationFormEditLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
			      <?php } ?>
        </div>
        
        <div class="clearFix"></div>
        <div class="previewBody">
        	<div>
            	<div class="reviewTitleBox">
					<strong>SECTION A: Centre & Scores</strong>
				</div>
               <div class="reviewTitleBox">
					<strong>Order of preference (1 for first preference to 5 for last preference) for Centre for Group Discussion & Personal Interview (GD & PI)
					</strong>
			   </div>
                <div class="options-box">
                    <label>Bangalore</label>
                    <div style="width:40px; padding-left:5px" class="previewFieldBox2">
                        <table cellspacing="0" cellpadding="4" bordercolor="#000000" border="1" width="100%" class="boxTable">
                            <tr>
                                <td>
                                          
                                           <?php
					    if($preferredGDPILocation=='278') echo "1";
					    else if($pref2KIITS=='Bangalore') echo "2";
					    else if($pref3KIITS=='Bangalore') echo "3";
					    else if($pref4KIITS=='Bangalore') echo "4";
					    else if($pref5KIITS=='Bangalore') echo "5";
				      ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="options-box">
                    <label>Bhubaneswar</label>
                    <div style="width:40px; padding-left:5px" class="previewFieldBox2">
                        <table cellspacing="0" cellpadding="4" bordercolor="#000000" border="1" width="100%" class="boxTable">
                            <tr>
                                <td><?php
					    if($preferredGDPILocation=='912') echo "1";
					    else if($pref2KIITS=='Bhubaneswar') echo "2";
					    else if($pref3KIITS=='Bhubaneswar') echo "3";
					    else if($pref4KIITS=='Bhubaneswar') echo "4";
					    else if($pref5KIITS=='Bhubaneswar') echo "5";
				      ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="options-box">
                    <label>Delhi</label>
                    <div style="width:40px; padding-left:5px" class="previewFieldBox2">
                        <table cellspacing="0" cellpadding="4" bordercolor="#000000" border="1" width="100%" class="boxTable">
                            <tr>
                                <td><?php
					    if($preferredGDPILocation=='74') echo "1";
					    else if($pref2KIITS=='Delhi') echo "2";
					    else if($pref3KIITS=='Delhi') echo "3";
					    else if($pref4KIITS=='Delhi') echo "4";
					    else if($pref5KIITS=='Delhi') echo "5";
				      ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
               
                <div class="options-box">
                    <label>Kolkata</label>
                    <div style="width:40px; padding-left:5px" class="previewFieldBox2">
                        <table cellspacing="0" cellpadding="4" bordercolor="#000000" border="1" width="100%" class="boxTable">
                            <tr>
                                <td><?php
					    if($preferredGDPILocation=='130') echo "1";
					    else if($pref2KIITS=='Kolkata') echo "2";
					    else if($pref3KIITS=='Kolkata') echo "3";
					    else if($pref4KIITS=='Kolkata') echo "4";
					    else if($pref5KIITS=='Kolkata') echo "5";
				      ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="options-box">
                    <label>Mumbai</label>
                    <div style="width:40px; padding-left:5px" class="previewFieldBox2">
                        <table cellspacing="0" cellpadding="4" bordercolor="#000000" border="1" width="100%" class="boxTable">
                            <tr>
                                <td><?php
					    if($preferredGDPILocation=='151') echo "1";
					    else if($pref2KIITS=='Mumbai') echo "2";
					    else if($pref3KIITS=='Mumbai') echo "3";
					    else if($pref4KIITS=='Mumbai') echo "4";
					    else if($pref5KIITS=='Mumbai') echo "5";
				      ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="clearFix spacer15"></div>
			<div>
				<div>
                <div class="reviewTitleBox">
					<strong>Tests Scores</strong>
				</div>
				<div class="formRows">
            		<ul>
						<?php
							$testsArray = explode(",",$KIITS_testNames);
						?>
                    	<li>
                        	<label style="width:100px">Examination:</label>
                            <div class="previewFieldBox" style="width:130px">
                                <span style="padding:6px 5px 0 0px; float:left; border:0 none">CAT</span>
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if(in_array("CAT",$testsArray)){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox" style="width:130px">
                                <span style="padding:6px 5px 0 0px; float:left; border:0 none">MAT</span>
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if(in_array("MAT",$testsArray)){
     echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";
}?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox" style="width:130px">
                                <span style="padding:6px 5px 0 0px; float:left; border:0 none">XAT</span>
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if( in_array("XAT",$testsArray)){
     echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";
}?></td>
                                    </tr>
                                </table>
                            </div>

                           <div class="previewFieldBox" style="width:130px">
                                <span style="padding:6px 5px 0 0px; float:left; border:0 none">CMAT</span>
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if( in_array("CMAT",$testsArray)){
     echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";
}?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox" style="width:130px">
                                <span style="padding:6px 5px 0 0px; float:left; border:0 none">GMAT</span>
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if( in_array("GMAT",$testsArray)){
       echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";
}?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox" style="width:130px">
                                <span style="padding:6px 5px 0 0px; float:left; border:0 none">ATMA</span>
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if(in_array("ATMA",$testsArray)){
      echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";
}?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="previewFieldBox" style="width:130px">
                                <span style="padding:6px 5px 0 0px; float:left; border:0 none">KIITEE MGT</span>
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if(in_array("KIITEE",$testsArray)){
      echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";
}?></td>
                                    </tr>
                                </table>
                            </div>



                            
                        </li>
                        <?php if( in_array("CAT",$testsArray)){?>
                        <li>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:228px">Composite CAT score / Percentile</label>
                                <div class="previewFieldBox3" style="width:185px">
                                     <span><?php echo $catScoreAdditional;?><?php if(isset($catScoreAdditional)) echo '/'; ?><?php echo $catPercentileAdditional;?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:185px">CAT ID / Regn. No / Roll No</label>
                                <div class="previewFieldBox3" style="width:260px">
                                    <span><?php echo $catRollNumberAdditional;?></span>
                                </div>
                            </div>
                        </li>
			<?php } ?>
			<?php if(in_array("MAT",$testsArray)){
?>				<li>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:228px">Composite MAT score / Percentile</label>
                                <div class="previewFieldBox3" style="width:185px">
                                    <span><?php echo $matScoreAdditional;?><?php if(isset($matScoreAdditional)) echo '/'; ?><?php echo $matPercentileAdditional;?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:185px">MAT ID / Regn. No / Roll No</label>
                                <div class="previewFieldBox3" style="width:260px">
                                    <span><?php echo $matRollNumberAdditional;?></span>
                                </div>
                            </div>
                        </li>


			<?php }?>

                       <?php if(in_array("CMAT",$testsArray)){
?>				<li>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:228px">Composite CMAT score / Rank</label>
                                <div class="previewFieldBox3" style="width:185px">
                                    <span><?php echo $cmatScoreAdditional;?><?php if(isset($cmatScoreAdditional)) echo '/'; ?><?php echo $cmatRankAdditional;?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:185px">CMAT ID / Regn. No / Roll No</label>
                                <div class="previewFieldBox3" style="width:260px">
                                    <span><?php echo $cmatRollNumberAdditional;?></span>
                                </div>
                            </div>
                        </li>


			<?php }?>

			<?php 
			if( in_array("GMAT",$testsArray)){?>				<li>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:228px">Composite GMAT score / Percentile</label>
                                <div class="previewFieldBox3" style="width:185px">
                                    <span><?php echo $gmatScoreAdditional;?><?php if(isset($gmatScoreAdditional)) echo '/'; ?><?php echo $gmatPercentileAdditional;?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:185px">GMAT ID / Regn. No / Roll No</label>
                                <div class="previewFieldBox3" style="width:260px">
                                    <span><?php echo $gmatRollNumberAdditional;?></span>
                                </div>
                            </div>
                        </li>

			<?php } ?>

			<?php if( in_array("XAT",$testsArray)){?>
				<li>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:228px">Composite XAT score / Percentile</label>
                                <div class="previewFieldBox3" style="width:185px">
                                    <span><?php echo $xatScoreKIITS;?><?php if(isset($xatScoreKIITS)) echo '/'; ?><?php echo $xatPercentileKIITS;?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:185px">XAT ID / Regn. No / Roll No</label>
                                <div class="previewFieldBox3" style="width:260px">
                                    <span><?php echo $xatRegnNoKIITS;?></span>
                                </div>
                            </div>
                        </li>
			<?php } ?>

			<?php if(in_array("ATMA",$testsArray)){?>
				<li>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:228px">Composite ATMA score / Percentile</label>
                                <div class="previewFieldBox3" style="width:185px">
                                    <span><?php echo $atmaScoreKIITS;?><?php if(isset($atmaScoreKIITS)) echo '/'; ?><?php echo $atmaPercentileKIITS;?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:185px">ATMA ID / Regn. No / Roll No</label>
                                <div class="previewFieldBox3" style="width:260px">
                                    <span><?php echo $atmaRegnNoKIITS;?></span>
                                </div>
                            </div>
                        </li>
			<?php } ?>

                       <?php if(in_array("KIITEE",$testsArray)){?>
				<li>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:228px">Composite KIITEE MGT score / Percentile</label>
                                <div class="previewFieldBox3" style="width:185px">
                                    <span><?php echo $kiiteeScoreKIITS;?><?php if(isset($kiiteeScoreKIITS)) echo '/'; ?><?php echo $kiiteePercentileKIITS;?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:185px">KIITEE MGT ID / Regn. No / Roll No</label>
                                <div class="previewFieldBox3" style="width:260px">
                                    <span><?php echo $kiiteeRegnNoKIITS;?></span>
                                </div>
                            </div>
                        </li>
			<?php } ?>



			
			<li>
				<div class="reviewTitleBox">
					<strong>SECTION B: Key Personal Information</strong>
				</div>
			</li>
		
                        <li>
                        	<label>Full Name</label>
                            <div class="previewFieldBox3">
                                <span><?php $name = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; echo $name; ?></span>
                            </div>
                        </li>
                        
                        <li>
                        	<label>Father's Name</label>
                            <div class="previewFieldBox3">
                                <span><?php echo $fatherName; ?></span>
                            </div>
                        </li>
                        
                        <li>
                        	<label>Mother's Name</label>
                            <div class="previewFieldBox3">
                                <span><?php echo $MotherName; ?></span>
                            </div>
                        </li>
						
						<li>
                        	<label>Date of Birth</label>
                            <div class="previewFieldBox3">
                                <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
                            </div>
                        </li>
						
					
                        
						<li>
                        	<label>Gender</label>
                            <div class="previewFieldBox3">
                                <div class="previewFieldBox" style="width:120px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>Male</td>
                                        <td><?php if($gender=='MALE'){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="previewFieldBox" style="width:120px; margin-left:50px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <td>Female</td>
                                        <td><?php if($gender=='FEMALE'){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                    </tr>
                                </table>
                            </div>
                            </div>
                        </li>
						
						
                        <li>
                        	<label>Marital Status</label>
                            <div class="previewFieldBox3">
                                <span><?php echo $maritalStatus;?></span>
                            </div>
                        </li>
						
						<li>
                        	<label>Category</label>
                            <div class="previewFieldBox" style="width:420px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr><?php if(!isset($categoryKIITS)) $categoryKIITS = $applicationCategory;?>
					<?php if(isset($categoryKIITS) && $categoryKIITS!=''){ ?>
                                        <td>GENERAL</td>
                                        <td><?php if($categoryKIITS=="GENERAL"){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                        <td>OBC</td>
                                        <td><?php if($categoryKIITS=="OBC"){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                        <td>SC</td>
                                        <td><?php if($categoryKIITS=="SC"){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                        <td>ST</td>
                                        <td><?php if($categoryKIITS=="ST"){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
					<?php }else{?>
					 <td>GENERAL</td>
                                        <td>&nbsp;</td>
                                        <td>OBC</td>
                                        <td>&nbsp;</td>
                                        <td>SC</td>
                                        <td>&nbsp;</td>
                                        <td>ST</td>
                                        <td>&nbsp;</td>
					<?php } ?>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        
                        <li>
                        	<label>Nationality</label>
                            <div class="previewFieldBox3">
                                <span><?php echo $nationality;?></span>
                            </div>
                        </li>
						
						<li>
                        	<label>Religion</label>
                            <div class="previewFieldBox3">
                                <span><?php echo $religion;?></span>
                            </div>
                        </li>
						<li>
                        	<label>Mother Tongue</label>
                            <div class="previewFieldBox3">
                                <span><?php echo $KIITS_motherTongue;?></span>
                            </div>
                        </li>
						
						<li>	<?php 	$str='';
					if($streetName){
						$str .= $ChouseNumber.' '.$CstreetName;
					}else{
						$str .= $ChouseNumber;
					}
					if($area){
						$str .= ' '.$Carea;
					}
					if($city){
						$str .= ' '.$Ccity;
					}
								?>
                        	<label style="width:600px;">Address for Correspondence</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox3" style="width:100%">
                                <span><?=$str?></span>
                            </div>
                        </li>
                        
                        <li>
                        	<label>Pincode</label>
                        	<div class="previewFieldBox3">
                                <span><?php echo $Cpincode; ?></span>
                            </div>
                        </li>
												
						<li>	<?php 	$str='';
					if($streetName){
						$str .= $houseNumber.' '.$streetName;
					}else{
						$str .= $houseNumber;
					}
					if($area){
						$str .= ' '.$area;
					}
					if($city){
						$str .= ' '.$city;
					}
								?>
                        	<label style="width:600px;">Permanent Address</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox3" style="width:100%">
                                <span><?=$str?></span>
                            </div>
                        </li>
                        
                        <li>
                        	<label>Pincode</label>
                        	<div class="previewFieldBox3">
                                <span><?php echo $pincode; ?></span>
                            </div>
                        </li>
						
						
						<li>
                        	<label>E-mail Address</label>
                            <div class="previewFieldBox3">
                                <span><?php echo $email; ?></span>
                            </div>
                        </li>
                        
                        <li>	<?php $phoneNumber= ($landlineSTDCode!='')?$landlineSTDCode.$landlineNumber:$landlineNumber;?>
                        	<div style="float:left">
                        	<label style="width:auto">Tel. No.<br />(With STD Code).</label>
                            <div class="previewFieldBox" style="width:320px; margin-top:13px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <?php echo displayFormDataInBoxes(11,$phoneNumber); ?>
                                        
                                    </tr>
                                </table>
                            </div>
                            </div>
                            <div style="margin-left:20px; float:left">
                        	<label style="width:auto; padding-top:21px">Mobile No.</label>
                            <div class="previewFieldBox" style="width:300px; margin-top:13px">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
                                    <tr>
                                        <?php echo displayFormDataInBoxes(10,$mobileNumber);?>
                                    </tr>
                                </table>
                            </div>
                            </div>
                            
                            
                        </li>
						
						<li>
                        	<label style="width:200px">Occupation of Parents</label>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:auto">Father</label>
                                <div class="previewFieldBox3" style="width:250px">
                                    <span><?=$fatherOccupation?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:auto">Mother</label>
                                <div class="previewFieldBox3" style="width:250px">
                                    <span><?=$MotherOccupation?></span>
                                </div>
                            </div>
                        </li>
                        
                       
                        
                        <li>
                        	<label style="width:278px">Total Annual Household Income (gross)</label>
                        	<div class="previewFieldBox3" style="width:602px">
                            	<span>Rs. <?php echo $incomeKIITS;?></span>
                            </div>
                        </li>
						
						<li>
                        	<label style="width:330px">Do you plan to avail a study loan for the course</label>
                            <div class="previewFieldBox" style="width:150px">
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if($studyLoanKIITS=='Yes'){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                    </tr>
                                </table>
                                <span style="padding:6px 0 0 5px; float:left; border:0 none">YES</span>
                            </div>
                            
                            <div class="previewFieldBox" style="width:150px">
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if($studyLoanKIITS=='No'){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                    </tr>
                                </table>
                                <span style="padding:6px 0 0 5px; float:left; border:0 none">NO</span>
                            </div>
                        </li>
						
						
                        
                        
                        
                        
                         <li>
                        	<label style="width:340px">In case of emergency, we can contact <?php if($salutationKIITS == 'Mr') echo "Mr."; else echo "<span style='text-decoration:line-through; color:#666;'>Mr.</span>"; ?>/<?php if($salutationKIITS == 'Ms') echo "Ms."; else echo "<span style='text-decoration:line-through; color:#666;'>Ms.</span>"; ?>/<?php if($salutationKIITS == 'Mrs') echo "Mrs."; else echo "<span style='text-decoration:line-through; color:#666;'>Mrs.</span>"; ?></label>
                        	<div class="previewFieldBox3" style="width:538px">
                            	<span>&nbsp;<?=$emergencyContactKIITS;?></span>
                            </div>
                        </li>
						 
						
                        
                        <li>
                        	<div class="formColumns2" style="padding-right:20px">
                                <label style="width:auto">Residence (phone no)</label>
                                <div class="previewFieldBox3" style="width:160px">
                                    <span>&nbsp;<?=$residencePhoneKIITS?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:auto">(Office phone no)</label>
                                <div class="previewFieldBox3" style="width:160px">
                                    <span>&nbsp;<?=$officePhoneKIITS?></span>
                                </div>
                            </div>
                            <div class="formColumns2">
                                <label style="width:auto">Relation with you</label>
                                <div class="previewFieldBox3" style="width:165px">
                                    <span>&nbsp;<?=$contactRelationKIITS;?></span>
                                </div>
                            </div>
                        </li>
						
						 <li>
                        	<label style="width:260px">Whether Physically Handicapped</label>
                            <div class="previewFieldBox" style="width:150px">
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if($physicallyHandicappedKIITS=='Yes'){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                    </tr>
                                </table>
                                <span style="padding:6px 0 0 5px; float:left; border:0 none">YES</span>
                            </div>
                            
                            <div class="previewFieldBox" style="width:150px">
                                <table width="35" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="boxTable" style="float:left">
                                    <tr>
                                        <td><?php if($physicallyHandicappedKIITS=='No'){echo "<img src='/public/images/onlineforms/institutes/kiits/tick-icn.gif' border=0 />";}?></td>
                                    </tr>
                                </table>
                                <span style="padding:6px 0 0 5px; float:left; border:0 none">NO</span>
                            </div>
                            
                            <div class="spacer5 clearFix"></div>
			    <?php if($physicallyHandicappedKIITS=='Yes'){?>
                            <div style="margin-left:100px">
                                <label style="width:auto">Details (if Yes)&nbsp; </label>
                                <div class="previewFieldBox3" style="width:680px">
                                    <span>&nbsp;<?=$handicapKIITS?></span>
                                </div>
                            </div>
			    <?php } ?>
                        </li>
						 
						 
						<li>
                        	<label>Blood Group</label>
                        	<div class="previewFieldBox3">
								<span><?=$KIITS_bloodGroup;?></span>
                            </div>
                        </li>
						
						<li>
                        	<label style="width:490px;">If you have participated in any sports/games at State/National level, give details</label>
                        	<div class="previewFieldBox3" style="width:393px">
								<span><?=$achievementKIITS;?></span>
                            </div>
                        </li>
                        
                        
                        <li>
                        	<label style="width:308px">Details of Application fee paid : Drawee Bank</label>
                        	<div class="formColumns2" style="padding-right:20px">
                                <div class="previewFieldBox3" style="width:200px">
                                    <span>&nbsp;<?php echo $paymentDetails['bankName']; ?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2">
                                <label style="width:auto">DD No</label>
                                <div class="previewFieldBox3" style="width:152px">
                                    <span>&nbsp;<?php echo $paymentDetails['draftNumber']; ?></span>
                                </div>
                            </div>
                            <div class="formColumns2">
                                <label style="width:auto">Date</label>
                                <div class="previewFieldBox3" style="width:112px">
                                    <span>&nbsp;<?php if($paymentDetails['draftDate'] && $paymentDetails['draftDate']!= '0000-00-00') echo date('d/m/Y',strtotime($paymentDetails['draftDate'])); ?></span>
                                </div>
                            </div>
                        </li>
						
						<li>
						<div class="reviewTitleBox">
							<strong>SECTION C: Education & Experience</strong>
						</div>
						</li>
						
						<li>
                        	<label style="width:600px; padding:0">Educational Details</label>
                            <div class="spacer20 clearFix"></div>
                            <div class="">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="educationTable2">
                                    <tr>
                                        <th valign="top"><div class="formWordWrapper" style="width:150px">Name of the<br />Examination</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:150px">Board/<br />University</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:150px">Name of the<br />College/ Institute</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:115px">Year of Passing</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:110px">Stream</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:90px">Class</div></th>
                                        <th valign="top"><div class="formWordWrapper" style="width:60px">% of<br />Marks*</div></th>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px">i. Matriculation/<br />Secondary School<br />Examination</div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=$class10Board;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=$class10School;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:115px"><?=$class10Year;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:110px"><?=$class10StreamKIITS;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$class10DivisonKIITS;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:60px"><?=$class10Percentage;?></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px">ii. +2/Higher<br />Secondary/<br />Equivalent</div></td>
					<td valign="top"><div class="formWordWrapper" style="width:150px"><?=$class12Board;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=$class12School;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:115px"><?=$class12Year;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:110px"><?=$class12StreamKIITS;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$class12DivisonKIITS;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:60px"><?=$class12Percentage;?></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" height="55"><div class="formWordWrapper" style="width:150px">iii. Degree (<?=$graduationExaminationName;?>)</div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=$graduationBoard;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=$graduationSchool;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:115px"><?=$graduationYear;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:110px"><?=$gradStreamKIITS;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$gradDivisonKIITS;?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:60px"><?=$graduationPercentage;?></div></td>
                                    </tr>
                                    
                                    <!--<tr>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px">iv. Please indicate if<br />you have appeared in<br />any Degree exam.<br />and result not<br />declared</div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:115px"></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:110px"></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:90px"></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:60px"></div></td>
                                    </tr>-->
                                    <?php
					$romanArr = array('iv','v','vi','vii');
					$j=0;
					for($i=1;$i<=4;$i++){
					if(${'graduationExaminationName_mul_'.$i}){
					if(${'otherCoursePGCheck_mul_'.$i}){ 
					?>
                                    <tr>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=$romanArr[$j];?>. Post Graduate Degree(<?=${'graduationExaminationName_mul_'.$i}?>)</div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=${'graduationBoard_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=${'graduationSchool_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:115px"><?=${'graduationYear_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:110px"><?=${'otherCourseStream_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:90px"><?=${'otherCourseDivisionORClass_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:60px"><?=${'graduationPercentage_mul_'.$i};?></div></td>
                                    </tr>
                                    <?php $j++;}else{ ?>
					
                                    <tr>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=$romanArr[$j];?>. Other Degree (<?=${'graduationExaminationName_mul_'.$i}?>)</div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=${'graduationBoard_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:150px"><?=${'graduationSchool_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:115px"><?=${'graduationYear_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:110px"><?=${'otherCourseStream_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:90px"><?=${'otherCourseDivisionORClass_mul_'.$i};?></div></td>
                                        <td valign="top"><div class="formWordWrapper" style="width:60px"><?=${'graduationPercentage_mul_'.$i};?></div></td>
                                    </tr>
				    <?php $j++;}}
				   } ?>
                                </table>
                            </div>
                            <div class="spacer10 clearFix"></div>
                           
                        </li>
						
						<li>	
                    
                        	<label style="width:600px; padding:0">Work Experience</label>
                            <div class="spacer15 clearFix"></div>
                            <div class="">
				
                                <table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#000" class="educationTable2">
                                  <tr>
                                    <td valign="top" rowspan="2"><div class="formWordWrapper" style="width:200px"><strong>Name of the<br />Organization</strong></div></td>
                                    <td valign="top" align="center" colspan="2"><strong>Duration</strong></td>
                                    <td rowspan="2" valign="top"><div class="formWordWrapper" style="width:190px"><strong>Designation</strong></div></td>
                                    <td rowspan="2" valign="top"><div class="formWordWrapper" style="width:230px"><strong>Experience (including remuneration)</strong><div></td>
                                  </tr>
                                  <tr>
                                    <td valign="top" width="120"><strong>From<br />(Month/Year)</strong></td>
                                    <td  valign="top"><strong>To (Month/Year)</strong></td>
                                  </tr>
				 <?php 
				$workExGiven = false;
				$total = 0;
				for($i=3; $i>=0; $i--){
				    $startDate = '';
				    //$mulSuffix = $i>0?'_mul_'.$i:'';
				    $mulSuffix = $i>0?'_mul_'.$i:'';
				    $otherSuffix = '_mul_'.$i;
				    $companyName = ${'weCompanyName'.$mulSuffix};
				    $durationFrom = ${'weFrom'.$mulSuffix};
				    $durationTo = trim(${'weTimePeriod'.$mulSuffix})?'Till date':${'weTill'.$mulSuffix};
				    $designation = ${'weDesignation'.$mulSuffix};
				    $natureOfWork = ${'weRoles'.$mulSuffix};
				    $addressCompany = ${'orgnAddressCMD'.$otherSuffix};
				    //$annualSalaryKIITS1 = ${'annualSalaryKIITS'.(($mulSuffix=='_mul_1')?'':$mulSuffix)};
				    $annualSalaryKIITS1 = ${'annualSalaryKIITS'.$mulSuffix};
					
					//_p(${'annualSalaryKIITS'.(($mulSuffix=='_mul_1')?'':$mulSuffix)});
				    if($companyName || $designation){ $workExGiven = true; $total++; 
					$nameAddressString = ($addressCompany!='')?$companyName.", ".$addressCompany:$companyName;
							if($durationFrom) {
							    $startDate = getStandardDate($durationFrom);
							}
							$endDate = ($durationTo == 'Till date')?'Till date':getStandardDate($durationTo);
							if($endDate=='Till date' || trim($endDate)=='--'){
								$endDate1 = date('Y-m-d');
							}
							else{
								$endDate1 = $endDate;
							}
							//$totalDuration = getTimeDifference($startDate,$endDate);
							$date1 = new DateTime($startDate);
							$date2 = new DateTime($endDate1);

							$diff = $date1->diff($date2);

							$totalDuration = (($diff->format('%y') * 12) + $diff->format('%m'))
							    //echo ($totalDuration['months']<0)?0:$totalDuration['months'];
				    ?>
                                  <tr>
                                    <td><?=$companyName;?></td>
                                    <td><?php if(trim($startDate)==''){echo '';}else{echo date("m/Y",strtotime($startDate));}?></td>
                                    <td><?php if(trim($endDate)=='--'){echo '';}else{if($endDate!='Till date'){ echo date("m/Y",strtotime($endDate));}else{ echo 'Till date';}}?></td>
                                    <td><?=$designation;?></td>
                                    <td><?=($totalDuration<0)?0:$totalDuration.'&nbsp; Month(s), &nbsp;INR&nbsp;'.$annualSalaryKIITS1.'&nbsp;Salary';?></td>
                                  </tr>
                                  


				<?php }}?>
                                </table>
				
                            </div>
                        </li>
                        <li>
						<div class="reviewTitleBox">
							<strong>SECTION D: Other Information</strong>
						</div>
						</li>
						<li>
							<label style="width:600px;">How do you expect MBA to benefit you?</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox3" style="width:100%">
                                <span><?=$essayKIITS?></span>
                            </div>
                        </li>
						<li>
							<label style="width:600px;">Give an example of something that you did well. Why do you think you succeeded?</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox3" style="width:100%">
                                <span><?=$KIITS_essay1?></span>
                            </div>
                        </li>
						<li>
							<label style="width:600px;">Give an example of something you have failed in. Why did you fail?</label>
                            <div class="clearFix spacer5"></div>
                            <div class="previewFieldBox3" style="width:100%">
                                <span><?=$KIITS_essay2?></span>
                            </div>
                        </li>
                    </ul>
               <div class="clearFix"></div>
            </div>
            </div>
			</div>

            
            <div class="formRows">
				
            
				<div class="reviewTitleBox">
					<strong>Declaration</strong>
				</div>
			
                
                <p>I certify that the information furnished in this application is true to the best of my knowledge. My application
may be rejected and admission shall be cancelled if any information provided herein is found to be incorrect at
any time during or after admission.</p>
				<div class="clearFix spacer10"></div>
				<ul>
                <li>
                        	<label style="width:205px">Signature of the candidate</label>
                        	<div class="formColumns2" style="padding-right:10px">
                                <div class="previewFieldBox3" style="width:250px">
                                    <span>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
                                </div>
                            </div>
                            
                            <div class="formColumns2" style="padding-right:10px">
                                <label style="width:auto">Place</label>
                                <div class="previewFieldBox3" style="width:160px">
                                    <span>&nbsp;<?php if(isset($firstName) && $firstName!='') {echo $Cstate;} ?></span>
                                </div>
                            </div>
                            <div class="formColumns2">
                                <label style="width:auto">Date</label>
                                <div class="previewFieldBox3" style="width:160px">
                                    <span>&nbsp;<?php
					      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
						      echo date("d/m/Y", strtotime($paymentDetails['date']));
						}
					?></span>
                                </div>
                            </div>
                        </li>
                </ul>
            </div>
            
		</div>
		</div>
	</div>
