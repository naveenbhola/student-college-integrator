<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];

?>
<style>
.reviewTitleBox{margin-top:30px;}
</style>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Form No. <?=$instituteSpecId?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:90%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/psg/logo2.jpg" alt="PSG Institute of Management (PSGIM)" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">PSG Institute of Management (PSGIM)</h2>
				<div style="text-align:left;margin-left:20px">
					P.B.No. 1668,    <br>
					Peelamedu,Coimbatore - 641 004,<br>
					India Phone: +91 422 - 4304400 / 2577252,<br>
					Fax: +91 422- 4304444 e-mail: info@psgim.ac.in <br>
					Website: www.psgim.ac.in<br>
				</div>
				</div>
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
    </div>
   <div class="spacer15 clearFix"></div>
	<div class="appForm-box" style="width:98%">
		APPLICATION FORM FOR THE BATCH OF 2014-2016
	</div>
	<div class="clearFix"></div>
    <div id="custom-form-content">
	<ul>
		<li>
			<div class="spacer15 clearFix"></div>
			<div class="reviewTitleBox">
				   <strong>Course Preference</strong>
		    </div>
		</li>
		<li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>Course(s) Applied for:</label>
				<span><?=$PSG_course?></span>
			</div>
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>Personal Details</strong>
			</div>
		</li>
		
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>1. Name:</label>
				<span><?php echo $firstName.' '.$middleName.' '.$lastName;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:400px">
				<label>2. Date of Birth:</label>
				<span><?php echo str_replace("/","-",$dateOfBirth);?></span>
			</div>
			<div class="personalInfoCol" style="width:400px">
				<label>3. Gender:</label>
				<span><?php echo $gender;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>4. Community:</label>
				<span><?php echo $PSG_community;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>5. Name & Occupation of the Parent/Guardian:</label>
				<span><?php echo $PSG_guardianName.', '.$PSG_guardianOccupation;?></span>
			</div>
		</li>
		
		
		<li>
			<div class="reviewTitleBox">
				 <strong>Communication Details</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>6. Address for communication:</label>
				<span><?php echo $ChouseNumber.' '.$CstreetName.' '.$Carea.', '.$Ccity.', '.$Cstate.' - '.$Cpincode ;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:400px">
				<label>Phone:</label>
				<span><?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber;?></span>
			</div>
			<div class="personalInfoCol" style="width:400px">
				<label>Mobile:</label>
				<span><?php echo $mobileISDCode.'-'.$mobileNumber;?></span>
			</div>
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label>Email:</label>
				<span><?php echo $email;?></span>
			</div>
		</li>
		
		
		<li>
			<div class="reviewTitleBox">
				 <strong>7. Academic Record</strong>
			</div>
		</li>
		
		<li>
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
					<tr>
						<td>Qualification</td>
                    	<td>Institution</td>
                        <td>Year of Passing</td>
                        <td>% of Marks</td>
                    </tr>
					<tr>
						<td>SSLC</td>

                    	<td><?php echo $class10School;?></td>
                        <td><?php echo $class10Year;?></td>
                        <td><?php echo $class10Percentage;?></td>
                    </tr>
					<tr>
						<td>H.Sc</td>
                    	<td><?php echo $class12School;?></td>
                        <td><?php echo $class12Year;?></td>
                        <td><?php echo $class12Percentage;?></td>
                    </tr>
					<tr>
						<td><?php echo $graduationExaminationName?$graduationExaminationName:"Graduation";?></td>
                        <td><?php echo $graduationSchool;?></td>
                        <td><?php echo $graduationYear;?></td>
                        <td><?php echo $graduationPercentage;?></td>
                    </tr>
					<?php 
						for($j=1;$j<=4;$j++):?>
					<?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
					<tr>
						<td><?php echo ${'graduationExaminationName_mul_'.$j};?></td>
                        <td><?php echo ${'graduationSchool_mul_'.$j};?></td>
                        <td><?php echo ${'graduationYear_mul_'.$j};?></td>
                        <td><?php echo ${'graduationPercentage_mul_'.$j};?></td>
                    </tr>
					<?php endif;endfor; ?>
				</table>
		</li>
		
		
		<li>
			<div class="reviewTitleBox">
				 <strong>8. Work Experience</strong>
			</div>
		</li>
		
		<li>
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%">
				<tr>
					
					<td>Company</td>
					<td>From</td>
					<td>To</td>
					<td>Position held/KRAs</td>
					<td>Location</td>
				</tr>
				<tr>
					
					<td><?=$weCompanyName?></td>
					<td><?php if(!empty($weFrom)) {echo date('F Y',strtotime(getStandardDate($weFrom)));}?></td>
					<td><?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('F Y',strtotime(getStandardDate($weTill)));}} else {echo "Current";}?></td>
					<td>Position held: <?=$weDesignation?><br>KRAs: <?php echo nl2br(trim($weRoles));?></td>
					<td><?php echo trim($weLocation);?></td>
				</tr>
				<?php 
					for($i=1;$i<=3;$i++):?>
					<?php if(!empty(${'weCompanyName_mul_'.$i})):?>
				<tr>
					
					<td><?php echo ${'weCompanyName_mul_'.$i};?></td>
					<td><?php if(!empty(${'weFrom_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></td>
					<td><?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo "Current";}?></td>
					<td>Position held: <?=${'weDesignation_mul_'.$i}?><br>KRAs: <?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></td>
					<td><?php echo trim(${'weLocation_mul_'.$i});?></td>
				</tr>
				<?php endif;endfor;?>
			</table>
		</li>
		
		<li>
			<div class="reviewTitleBox">
                <strong>9. Entrance Score (Marks)</strong>
			</div>
		</li>
                 <?php $testName = explode(',',$PSG_testNames);?>
	    
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:250px">
				<label>Percentile Score</label>
				<span></span>
			</div>
                        <?php if(in_array('CAT',$testName)):?>
			<div class="personalInfoCol" style="width:180px">
				<label>CAT:</label>
				<span><?php echo $catPercentileAdditional;?></span>
			</div>
                        <?php endif;?>
                         
                        <?php if(in_array('MAT',$testName)):?>
			<div class="personalInfoCol" style="width:180px">
				<label>MAT:</label>
				<span><?php echo $matPercentileAdditional;?></span>
			</div>

                        <?php endif;?>

                         <?php if(in_array('CMAT',$testName)):?>
			<div class="personalInfoCol" style="width:180px">
				<label>CMAT Rank:</label>
				<span><?php echo $cmatRankAdditional;?></span>
			</div>
                        <?php endif;?>
		</li>
		
		
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:250px">
				<label>Month & Year</label>
				<span></span>
			</div>
                        <?php if(in_array('CAT',$testName)):?>
			<div class="personalInfoCol" style="width:180px">
				<label>CAT:</label>
				<span><?php echo $catDateOfExaminationAdditional?date("F, Y",strtotime(str_replace("/","-",$catDateOfExaminationAdditional))):'';?></span>
			</div>
                        <?php endif;?>


                         <?php if(in_array('MAT',$testName)):?>
			<div class="personalInfoCol" style="width:180px">
				<label>MAT:</label>
				<span><?php echo $matDateOfExaminationAdditional?date("F, Y",strtotime(str_replace("/","-",$matDateOfExaminationAdditional))):'';?></span>
			</div>
                        <?php endif;?>

                         <?php if(in_array('CMAT',$testName)):?>
       			<div class="personalInfoCol" style="width:180px">
				<label>CMAT:</label>
				<span><?php echo $cmatDateOfExaminationAdditional?date("F, Y",strtotime(str_replace("/","-",$cmatDateOfExaminationAdditional))):'';?></span>
			</div>
                        <?php endif;?>
			
		</li>
		
		<li>
			<div class="reviewTitleBox">
				 <strong>10. Interview preferred centre</strong>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:900px">
				<label></label>
                <span><?php echo $gdpiLocation;?></span>
			</div>
		</li>
		
	
		<li>
			<div class="reviewTitleBox">
				 <strong>Declaration</strong>
			</div>
		</li>
		
		<li style="margin-left:10px">
			I declare that the particulars given above are true and correct to the best of my knowledge.
		</li>
		
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:500px">
				<label>Place:</label>
                <span><?php echo $Ccity;?></span>
			</div>
			<div class="personalInfoCol" style="width:400px">
				<label></label>
				<span style="float:right !important"><?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?><br></span>
			</div>
		</li>
		<li style="margin-left:10px">
			<div class="personalInfoCol" style="width:500px">
				<label>Date:</label>
                <span><?php echo $paymentDetails['date']?date("d/m/Y", strtotime($paymentDetails['date'])):""?></span>
			</div>
			<div class="personalInfoCol" style="width:400px">
				<label></label>
				<span style="float:right !important"><label>Signature of Candidate</label></span>
			</div>
		</li>
		
		
	</ul>
	</div>
	</div></div>
