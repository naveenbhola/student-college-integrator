    	<!--Additional Info Starts here-->
    	<div class="additionalDetailsSection">
            <div class="reviewTitleBox">
                <strong>Additional Details:</strong>
		<?php if($showEdit=='true'){ ?><a onClick="setCookie('redirectViewForm','true');" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>/1/4" title="Edit">edit</a><?php  } ?>
            </div>

            <div class="additionalInfoCol">
                <ul class="reviewChildLeftCol2">
		    <?php if(isset($spouseName) && $spouseName!=''){ ?> 
                    <li>
                        <label>Spouse Name:</label>
                        <span><?php echo $spouseName;?></span>
                    </li>
		    <?php } ?>

                    <li>
                        <label>Blood Group:</label>
                        <span><?php echo $bloodGroup;?></span>
                    </li>

		    <?php if(isset($motherMobileNumber) && $motherMobileNumber!=''){ ?> 
                    <li>
                        <label>Mother Mobile Number:</label>
                        <span><?php echo $motherMobileNumber;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($officeAddress) && $officeAddress!=''){ ?> 
                    <li>
                        <label>Office Address of Head of Family:</label>
                        <span><?php echo $officeAddress;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($officeAddressPincode) && $officeAddressPincode!=''){ ?> 
                    <li>
                        <label>Pincode:</label>
                        <span><?php echo $officeAddressPincode;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($hobbies) && $hobbies!=''){ ?> 
                    <li>
                        <label>Extra Curricular Interests/ Hobbies:</label>
                        <span><?php echo $hobbies;?></span>
                    </li>
		    <?php } ?>

                </ul>
                
                <ul class="reviewChildRightCol2">
		    <?php if(isset($medicalHistory) && $medicalHistory!=''){ ?> 
                    <li>
                        <label>Medical History:</label>
                        <span><?php echo $medicalHistory;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($fatherMobileNumber) && $fatherMobileNumber!=''){ ?> 
                    <li>
                        <label>Father Mobile Number:</label>
                        <span><?php echo $fatherMobileNumber;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($annualIncome) && $annualIncome!=''){ ?> 
                    <li>
                        <label>Annual Family Income (in Rs.):</label>
                        <span><?php echo $annualIncome;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($computerProficiency) && $computerProficiency!=''){ ?> 
                    <li>
                        <label>Proficiency in Computers:</label>
                        <span><?php echo $computerProficiency;?></span>
                    </li>
		    <?php } ?>

                </ul>
            </div>

	    <?php if( (isset($language1) && $language1!='') || (isset($language2) && $language2!='') || (isset($language3) && $language3!='')){ ?>
	    <div class="clearFix"></div>
        <div class="educationHistoryBlock" style="background:none">
		    <div class="educationTitle">
			<p class="educationCol educationHisTitleColFirst">Language</p>
			<p class="educationCol">Reading</p>
			<p class="educationCol">Writing</p>
			<p class="educationYearCol">Speaking</p>
		    </div>

		    <?php for($i=1;$i<=3;$i++){ 
			  $variableL = "language".$i;
			  $variableR = "reading".$i;
			  $variableW = "writing".$i;
			  $variableS = "speaking".$i;
		      ?>
		      <?php if( (isset($$variableL) && $$variableL!='') ){ ?>
			  <li>
			      <div class="formAutoColumns" style="padding-left:95px;">
				  <span class="educationCol educationColFirst"><?php echo $$variableL; ?></span>
				  <span class="educationCol"><?php echo $$variableR; ?></span>
				  <span class="educationCol"><?php echo $$variableW; ?></span>
				  <span class="educationYearCol"><?php echo $$variableS; ?></span>
			      </div>
			      <div class="clear_B"></div>
			  </li>
		     <?php } } ?>
	    </div>
	    <?php }  ?>

            <div class="additionalInfoCol">
                <ul>
		    <?php if(isset($totalWorkEx) && $totalWorkEx!=''){ ?> 
                    <li>
                        <label>Total Work Experience (in months):</label>
                        <span><?php echo $totalWorkEx;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($numatDate) && $numatDate!=''){ ?> 
                    <li>
                        <label>Date when you would like to take NUMAT:</label>
                        <span><?php echo $numatDate;?></span>
                    </li>
		    <?php } ?>

            <?php if(isset($employerAddress) && $employerAddress!=''){ ?> 
                    <li>
                        <label>Address of Current Employer:</label>
                        <span><?php echo $employerAddress;?></span>
                    </li>
		    <?php } ?>
               
            <?php if(isset($knowAboutUniversity) && $knowAboutUniversity!=''){ ?> 
            <li>
		    <label>How did you get to know about NIIT UNIVERSITY?:</label>
            <span>
		    <?php $knowArray = explode(',',$knowAboutUniversity); 
			  foreach ($knowArray as $val){
		    ?>
                    
                        <?php echo $val; ?>:
			<?php 
			    if( (strpos($val,'News')!==false) && $newspaperText!='') echo $newspaperText.'<br/>';
			    else if( (strpos($val,'Mag')!==false) && $magazineText!='') echo $magazineText.'<br/>';
			    if( (strpos($val,'Coach')!==false) && $coachingText!='') echo $coachingText.'<br/>';
			    if( (strpos($val,'Inter')!==false) && $internetText!='') echo $internetText.'<br/>';
			    if( (strpos($val,'Relat')!==false) && $relativeText!='') echo $relativeText.'<br/>';
			    if( (strpos($val,'Other')!==false) && $otherText!='') echo $otherText;
			?>
		    <?php } ?></span>
                    </li><?php } ?>
			
            <?php if(isset($statementOfPurpose) && $statementOfPurpose!=''){ ?> 
                    <li>
                        <label>Statement of purpose:</label>
                        <span><?php echo $statementOfPurpose;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($personsInfluenced) && $personsInfluenced!=''){ ?> 
                    <li>
                        <label>Persons who have influenced/impressed you the most:</label>
                        <span><?php echo $personsInfluenced;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($adverseSituation) && $adverseSituation!=''){ ?> 
                    <li>
                        <label>Adverse situation in your life:</label>
                        <span><?php echo $adverseSituation;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($academicHighlight) && $academicHighlights!=''){ ?> 
                    <li>
                        <label>Highlights of your academic career:</label>
                        <span><?php echo $academicHighlights;?></span>
                    </li>
		    <?php } ?>

		    <?php if(isset($workExDetails) && $workExDetails!=''){ ?> 
                    <li>
                        <label>Work experience:</label>
                        <span><?php echo $workExDetails;?></span>
                    </li>
		    <?php } ?>

                </ul>
            </div>

        
        </div>
        <!--Additional Info Ends here-->
