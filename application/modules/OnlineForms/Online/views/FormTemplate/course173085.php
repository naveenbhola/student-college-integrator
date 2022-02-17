<style>
.reviewChildLeftCol1{
    float: left;
    width: 700px;
}
.reviewChildLeftCol1 span{

    width: 500px;
}
</style>
<?php
function getCourseByPref($pref){
	switch($pref){
		case "Not Preferred": return $pref;
		case 1: return "Computer Science & Engineering(CSE)";
		case 2: return "Electronics & Communication Engineering (ECE)";
		case 3: return "Mechanical Engineering (ME)";
		case 4: return "Information Technology (IT)";
		case 5: return "Civil Engineering (CIVIL)";
		case 6: return "Electrical & Electronics Engineering (EEE)";
	}
}
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
		<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box">
			<div style="float:right">Application Ref. No.<?=$instituteSpecId?></div>
			 <div class="clearFix spacer10"></div>
            <div class="inst-name" style="width:80%;margin-left:0px">
                <img src="/public/images/onlineforms/institutes/ITM/logo2.jpg" alt="ITM's University" style="float:left" />
				<div style="float:right">
				<h2 style="font-size:20px;">ITM University</h2>
				<div style="text-align:left;margin-left:20px">
					Sector-23A, Gurgaon, 122017<br/>
					India<br/>
					E-Mail: onlineadmissions@itmindia.edu<br>
					Website: www.itmindia.edu<br>
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
				APPLICATION FORM FOR ADMISSION TO B.TECH PROGRAMES FOR ACADEMIC SESSION 2013-2014
			</div>
        
        <div class="spacer15 clearFix"></div>
            <div class="reviewLeftCol">
				<ul class="reviewChildLeftCol1 reviewChildLeftCol">

                    <li>
                        <label>Name of the Applicant:</label>
                        <span><?=$firstName.' '.$middleName.' '.$lastName?></span>
                    </li>

                    <li>
                        <label>Father's Name:</label>
                        <span><?php echo $fatherName;?></span>
                    </li>

					
					<li>
                        <label>Mother's Name:</label>
                        <span><?php echo $MotherName;?></span>
                    </li>
                    <li>
                        <label>Correspondence Address</label>
                        <span></span>
                    </li>

                </ul>
				<div class="spacer5 clearFix"></div>
                 <?php
					  $Caddress = $ChouseNumber;
					  if($CstreetName) $Caddress .= ', '.$CstreetName;
					  if($Carea) $Caddress .= ', '.$Carea;
				?>
                <ul class="reviewChildLeftCol">

                    <li>
                        <label>Address:</label>
                        <span><?php echo $Caddress;?></span>
                    </li>

                    <li>
                        <label>City:</label>
                        <span><?php echo $Ccity;?></span>
                    </li>
					<li>
                        <label>State:</label>
                        <span><?php echo $Cstate;?></span>
                    </li>

					
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['dateOfBirth']):?>
                    <li>
                        <label>Email:</label>
                        <span><?php echo $email;?></span>
                    </li>
                    <li>
                        <label>Mobile No:</label>
                        <span><?php echo $mobileNumber;?></span>
                    </li>
                    <li>
                        <label>Telephone No. with STD Code:</label>
                        <span> <?php $phoneNumber = ($landlineSTDCode=='')?$landlineNumber:$landlineSTDCode."-".$landlineNumber;?>
					  <?php echo $phoneNumber; ?></span>
                    </li>
					<li>
                        <label>Pin Code:</label>
                        <span><?php echo $Cpincode;?></span>
                    </li>
                   
                </ul>
				<div class="spacer5 clearFix"></div>
				<ul class="reviewChildLeftCol">
                    <li>
                        <label>Permanent Address</label>
                        <span></span>
                    </li>
                </ul>
				 <?php
					  $address = $houseNumber;
					  if($streetName) $address .= ', '.$streetName;
					  if($area) $address .= ', '.$area;
				?>
                <ul class="reviewChildLeftCol">

                    <li>
                        <label>Address:</label>
                        <span><?php echo $address;?></span>
                    </li>

                    <li>
                        <label>City:</label>
                        <span><?php echo $city;?></span>
                    </li>
					<li>
                        <label>State:</label>
                        <span><?php echo $state;?></span>
                    </li>

					
                </ul>
                
                <ul class="reviewChildRightCol">
                <?php //if($profile_data['dateOfBirth']):?>
                    <li>
                        <label>Email:</label>
                        <span><?php echo $email;?></span>
                    </li>
                    <li>
                        <label>Mobile No:</label>
                        <span><?php echo $mobileNumber;?></span>
                    </li>
                    <li>
                        <label>Telephone No. with STD Code:</label>
                        <span> <?php $phoneNumber = ($landlineSTDCode=='')?$landlineNumber:$landlineSTDCode."-".$landlineNumber;?>
					  <?php echo $phoneNumber; ?></span>
                    </li>
					<li>
                        <label>Pin Code:</label>
                        <span><?php echo $pincode;?></span>
                    </li>
                   
                </ul>
				<div class="spacer5 clearFix"></div>
				<ul class="reviewChildLeftCol">

                    <li>
                        <label>Date of Birth:</label>
                        <span><?php echo str_replace("/","-",$dateOfBirth);?></span>
                    </li>

                    <li>
                        <label>Age as on 31/12/2013:</label>
                        <span><?php echo $ITM_Age;?></span>
                    </li>

					
                </ul>
                
                <ul class="reviewChildRightCol">
                    <li>
                        <label>Gender:</label>
                        <span><?php echo $gender;?></span>
                    </li>
                    <li>
                        <label>Annual Family Income:</label>
                        <span><?php echo $ITM_annualIncome;?></span>
                    </li>
                   
                </ul>
				 <div class="spacer5 clearFix" style="width:100%;border-bottom:1px solid #aaa;"></div>
				 <div class="spacer5 clearFix"></div>
				 <ul class="reviewChildLeftCol">

                    <li>
                        <label>Qualifying Exams (10+2):</label>
                        <span><?=$ITM_12thboard?></span>
                    </li>
					
					<li>
                        <label>10+2 or equivalent Results</label>
                        <span></span>
                    </li>
				 </ul>
				  <ul class="reviewChildLeftCol">
					<li>
						<label>10+2 Roll No.: </label>
						<span><?php echo $ITM_12thRollNumber; ?></span>
					</li>
					<li>
						<label>Maximum Marks Obtained:</label>
						<span><?php echo $ITM_12thMaxMarks; ?></span>
					</li>
				  </ul>
				  <ul class="reviewChildRightCol">
					<li>
						<label>Marks obtained:</label>
						<span><?php echo $ITM_12thTotalMarks; ?></span>
					</li>
					<li>
						<label>Aggregate % marks (All Subject):</label>
						<span><?php echo $ITM_12thAggregate; ?></span>
					</li>
                </ul>
								<table border="1" width="100%">
					<tr>
						<td>Subject</td>
						<td>Maths</td>
						<td>Physics</td>
						<td>Chemistry</td>
						<td>English</td>
						<td>Total of 4 Subjects</td>
					</tr>
					<tr>
						<td>Maximum Marks</td>
						<td>
							<?=$ITM_MaxMarksMaths?>
						</td>
						<td>
							<?=$ITM_MaxMarksPhysics?>
						</td>
						<td>
							<?=$ITM_MaxMarksChemistry?>
						</td>
						<td>
							<?=$ITM_MaxMarksEnglish?>
						</td>
						<td>
							<?=$ITM_MaxMarksTotal?>
						</td>
					</tr>
					<tr>
						<td>Marks Obtained</td>
						<td>
							<?=$ITM_ObtainedMarksMaths?>
						</td>
						<td>
							<?=$ITM_ObtainedMarksPhysics?>
						</td>
						<td>
							<?=$ITM_ObtainedMarksChemistry?>
						</td>
						<td>
							<?=$ITM_ObtainedMarksEnglish?>
						</td>
						<td>
							<?=$ITM_ObtainedMarksTotal?>
						</td>
					</tr>
					
				</table>
				<div class="spacer5 clearFix"></div>
				<ul class="reviewChildLeftCol">
					<li>
						<label>JEE Main 2013 Paper -1 Details</label>
						<span></span>
					</li>

				</ul>
				<div class="spacer5 clearFix"></div>
				<ul class="reviewChildLeftCol">
					<li>
						<label>Date of Examination: </label>
						<span><?php echo $jeeDateOfExaminationAdditional; ?></span>
					</li>

					<li>
						<label>Paper 1 Marks: </label>
						<span><?php echo $jeeScoreAdditional; ?></span>
					</li>
				</ul>
				<ul class="reviewChildRightCol">
					<li>
						<label>Roll Number: </label>
						<span><?php echo $jeeRollNumberAdditional; ?></span>
					</li>
				</ul>
				<div class="spacer5 clearFix" style="width:100%;border-bottom:1px solid #aaa;"></div>
				<div class="spacer5 clearFix"></div>
				<ul class="reviewChildLeftCol1 reviewChildLeftCol">
					<li>
						<label>Category under which admission is sought:</label>
						<span><?=$ITM_category?></span>
					</li>
					<li>
						<label>Have you ever been convicted for any Criminal Offence:</label>
						<span><?=$ITM_criminal?($ITM_criminal=='Y'?'Yes':'No'):''?></span>
					</li>
					<li>
						<label>Is there any case pending against you before a Court/Police/ School/ University:</label>
						<span><?=$ITM_casePending?($ITM_casePending=='Y'?'Yes':'No'):''?></span>
					</li>
					<li>
						<label>How did you get information about ITM University, Gurgaon:</label>
						<span><?=str_ireplace('Others',$ITM_howKnowOthers,$ITM_howKnow)?></span>
					</li>
					
					<li>
						<div class="spacer5 clearFix"></div>
						<label>Course Preference</label>
						<span></span>
					</li>
					<li>
						
							<label>First Preference: </label>
							<span><?php echo getCourseByPref($ITM_coursePref1); ?></span>
						
					</li>
				
					<li>
						
							<label>Second Preference: </label>
							<span><?php echo getCourseByPref($ITM_coursePref2); ?></span>
						
					</li>
				
					<li>
						
							<label>Third Preference: </label>
							<span><?php echo getCourseByPref($ITM_coursePref3); ?></span>
						
					</li>
				
					<li>
						
							<label>Fourth Preference: </label>
							<span><?php echo getCourseByPref($ITM_coursePref4); ?></span>
						
					</li>
				
					<li>
						
							<label>Fifth Preference: </label>
							<span><?php echo getCourseByPref($ITM_coursePref5); ?></span>
						
					</li>
				
					<li>
						
							<label>Sixth Preference: </label>
							<span><?php echo getCourseByPref($ITM_coursePref6); ?></span>
						
					</li>
					<li>
							<label>Are you interested in dual degree in Mechanical Engineering(ME)?: </label>
							<span><?=$ITM_dualDegree?($ITM_dualDegree=='Y'?'Yes':'No'):''?></span>
					</li>
					

				</ul>
				
            </div>
          
            <div class="picBox" style="border:none"></div>
        </div>
       
   
	<div id="responsibilityList">
	<li>
                  		<label style="font-weight:bold; width:700px">DECLARATION:</label>
                        <div class="spacer15 clearFix"></div>
                        <div style="padding-left:33px">
                        	1. I, hereby declare that all the particulars stated in this Application Form are true to the best of my knowledge and belief. 
<br/>2. I also affirm that I have read in detail the Admission Policy & Selection Procedure 2013 of ITM University including its fee structure and refund & cancellation policy before submitting this application and agree to unconditionally abide by the same. 
<br/>3. I understand that the decision of the University is final with regard to my admission. 
<br/>4. I promise to abide by the rules and regulations of the University as existing and as would be amended from time to time. 
<br/>5. If it is proved that I was admitted on false particulars and / or documents provided by me or my antecedents prove that my continuance in this University is not desirable, the University shall have the right to expel me from the University, besides being liable for legal action against me, at my cost. 
<br/>6. I agree that all disputes are subject to the jurisdiction of the court at Gurgaon only. 
<br/>7. I also understand that a student from a University / Board not recognized by CBSE/AICTE/UGC/AIU/MHRD or any other statutory body of Government of India shall not be eligible for admission.
		
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
		</div>
    </div>

