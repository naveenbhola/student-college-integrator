<div class="formChildWrapper">
	<div class="formSection">
    	<ul>
    	<?php if($action != 'updateScore'):?>
			<li>
            	<label>Spouse Name (if applicable) : </label>
                <div class='float_L'>
                	<input type='text' name='spouseName' id='spouseName'  validate="validateStr"    caption="Spouse Name"   minlength="3"   maxlength="50"     tip="Enter the name of your spouse (if applicable)"   value='' class="textboxLarge" />
				<?php if(isset($spouseName) && $spouseName!=""){ ?>
                        <script>
                            document.getElementById("spouseName").value = "<?php echo str_replace("\n", '\n', $spouseName );  ?>";
                            document.getElementById("spouseName").style.color = "";
                        </script>
              <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'spouseName_error'></div></div>
            </div>
		</li>

		<li>
			<div class='float_L' style="width:520px; font-size:14px">
                <label>Medical History if any : </label>
                <div class='fieldBoxLarge'>
                    <input type='text' name='medicalHistory' id='medicalHistory'  validate="validateStr"    caption="Medical History"   minlength="3"   maxlength="250"     tip="Medical History "   value='' class="textboxLarge" />
                    <?php if(isset($medicalHistory) && $medicalHistory!=""){ ?>
                            <script>
                                document.getElementById("medicalHistory").value = "<?php echo str_replace("\n", '\n', $medicalHistory );  ?>";
                                document.getElementById("medicalHistory").style.color = "";
                            </script>
                    <?php } ?>
                    <div style='display:none'><div class='errorMsg' id= 'medicalHistory_error'></div></div>
                </div>
            </div>
			
            <div class='float_L'>
                <label style="width: 180px; font-size:14px">Blood Group : </label>
                <div class="fieldBoxMed">
                	<input class="textboxSmall" type='text' name='bloodGroup' id='bloodGroup'  validate="validateStr"   required="true"   caption="Blood Group"   minlength="2"   maxlength="4"  tip="Blood Group"   value='' style="width:50px" />
                <?php if(isset($bloodGroup) && $bloodGroup!=""){ ?>
                <script>
                    document.getElementById("bloodGroup").value = "<?php echo str_replace("\n", '\n', $bloodGroup );  ?>";
                    document.getElementById("bloodGroup").style.color = "";
                </script>
                <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'bloodGroup_error'></div></div>
            </div>
        </div>
	</li>

	<li>
    	<div class='float_L' style="width:520px; font-size:14px">
            <label>Fathers Mobile Number : </label>
            <div class='fieldBoxMed'>
                <input type='text' name='fatherMobileNumber' id='fatherMobileNumber'  validate="validateMobileInteger"    caption="Father Mobile Number"   minlength="8"   maxlength="15"     tip="Enter your Fathers Mobile Number"   value='' class="textboxMedium" />
                <?php if(isset($fatherMobileNumber) && $fatherMobileNumber!=""){ ?>
                    <script>
                        document.getElementById("fatherMobileNumber").value = "<?php echo str_replace("\n", '\n', $fatherMobileNumber );  ?>";
                        document.getElementById("fatherMobileNumber").style.color = "";
                    </script>
                <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'fatherMobileNumber_error'></div></div>
            </div>
        </div>
        
    	<div class='float_L' style="width:385px">
        	<label style="width:180px; font-size:14px">Mothers Mobile Number : </label>
        	<div class='fieldBoxMed'><input type='text' name='motherMobileNumber' id='motherMobileNumber'  validate="validateMobileInteger"  caption="Mother Mobile Number" minlength="8"   maxlength="15" tip="Enter your Mothers Mobile Number"   value='' class="textboxMedium" />
			  <?php if(isset($motherMobileNumber) && $motherMobileNumber!=""){ ?>
                <script>
                    document.getElementById("motherMobileNumber").value = "<?php echo str_replace("\n", '\n', $motherMobileNumber );  ?>";
                    document.getElementById("motherMobileNumber").style.color = "";
                </script>
              <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'motherMobileNumber_error'></div></div>
        </div>
    	</div>
	</li>

	<li>
    	<label>Office Address of Head of the Family : </label>
        <div class='float_L'>
        	<textarea name='officeAddress' id='officeAddress'  validate="validateStr"  caption="Office Address"  minlength="3"   maxlength="250" tip="Enter the Office Address of Head of the Family (Father / Mother/ Guardian)" ></textarea>
			<?php if(isset($officeAddress) && $officeAddress!=""){ ?>
			<script>
                document.getElementById("officeAddress").value = "<?php echo str_replace("\n", '\n', $officeAddress );  ?>";
                document.getElementById("officeAddress").style.color = "";
            </script>
	      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'officeAddress_error'></div></div>
		</div>
	</li>

	<li>
    	<label>Pincode : </label>
        <div class='float_L'>
        	<input class="textboxSmall" type='text' name='officeAddressPincode' id='officeAddressPincode'  validate="validateInteger"    caption="Pincode"   minlength="5"   maxlength="8"     tip="Enter the Pincode of Office Address of Head of the Family (Father / Mother/ Guardian)"   />
			<?php if(isset($officeAddressPincode) && $officeAddressPincode!=""){ ?>
            <script>
                document.getElementById("officeAddressPincode").value = "<?php echo str_replace("\n", '\n', $officeAddressPincode );  ?>";
                document.getElementById("officeAddressPincode").style.color = "";
            </script>
	      	<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'officeAddressPincode_error'></div></div>
		</div>
	</li>

	<li>
    	<label>Proficiency in Computers : </label><div class='float_L'><input type='radio' name='computerProficiency' id='computerProficiency0'   value='Low'  checked ></input>Low&nbsp;&nbsp;<input type='radio' name='computerProficiency' id='computerProficiency1'   value='Medium'   ></input>Medium&nbsp;&nbsp;<input type='radio' name='computerProficiency' id='computerProficiency2'   value='High'   ></input>High&nbsp;&nbsp;
		<?php if(isset($computerProficiency) && $computerProficiency!=""){ ?>
			<script>
                radioObj = document.forms["OnlineForm"].elements["computerProficiency"];
                var radioLength = radioObj.length;
                for(var i = 0; i < radioLength; i++) {
                    radioObj[i].checked = false;
                    if(radioObj[i].value == "<?php echo $computerProficiency;?>") {
                        radioObj[i].checked = true;
                    }
                }
            </script>
	    <?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'computerProficiency_error'></div></div>
		</div>
	</li>

	<li>
    	<label>Extra Curricular Interests/ Hobbies : </label>
        <div class='float_L'>
			<textarea name='hobbies' id='hobbies'  tip="Enter your Extra Curricular Interests/ Hobbies"  rows=2 ></textarea>
			<?php if(isset($hobbies) && $hobbies!=""){ ?>
			<script>
                document.getElementById("hobbies").value = "<?php echo str_replace("\n", '\n', $hobbies );  ?>";
                document.getElementById("hobbies").style.color = "";
            </script>
	      	<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'hobbies_error'></div></div>
		</div>
	</li>

	<li>
    	<label>Annual Family Income (in Rs.) : </label>
        <div class='float_L'>
        	<input type='radio' name='annualIncome' id='annualIncome0'   value='<3L'  checked ></input><3L&nbsp;&nbsp;<input type='radio' name='annualIncome' id='annualIncome1'   value='3-6L'   ></input>3-6L&nbsp;&nbsp;<input type='radio' name='annualIncome' id='annualIncome2'   value='6-10L'   ></input>6-10L&nbsp;&nbsp;<input type='radio' name='annualIncome' id='annualIncome3'   value='10-15L'   ></input>10-15L&nbsp;&nbsp;<input type='radio' name='annualIncome' id='annualIncome4'   value='15-25L'   ></input>15-25L&nbsp;&nbsp;<input type='radio' name='annualIncome' id='annualIncome5'   value='>25L'   ></input>>25L&nbsp;&nbsp;
			<?php if(isset($annualIncome) && $annualIncome!=""){ ?>
			<script>
                radioObj = document.forms["OnlineForm"].elements["annualIncome"];
                var radioLength = radioObj.length;
                for(var i = 0; i < radioLength; i++) {
                    radioObj[i].checked = false;
                    if(radioObj[i].value == "<?php echo $annualIncome;?>") {
                        radioObj[i].checked = true;
                    }
                }
            </script>
	      	<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'annualIncome_error'></div></div>
		</div>
	</li>
	<li>
    	<div class="languageBlock">
            <div class="languageTitle">
                <p class="languageCol languageTitleColFirst">Language</p>
                <p class="languageCol">Reading</p>
                <p class="languageCol">Writing</p>
                <p class="languageCol">Speaking</p>
            </div>
         
    <ul>
    
	<li>
    	<div class='languageCol'>
			<input type='text' name='language1' id='language1'  validate="validateAlphabetic"    caption="Language"   minlength="2"   maxlength="50" tip="Enter the Language like English, Hindi"   value='' class="langTxtField"  />
			<?php if(isset($language1) && $language1!=""){ ?>
				<script>
                document.getElementById("language1").value = "<?php echo str_replace("\n", '\n', $language1 );  ?>";
                document.getElementById("language1").style.color = "";
            	</script>
            <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'language1_error'></div></div>
		</div>
		
        <div class='languageCol'>
        	<input type='text' name='reading1' id='reading1'  validate="validateAlphabetic"    caption="Reading"   minlength="2"   maxlength="10"     tip="Enter your reading skills in this language" value='' class="langTxtField" />
			<?php if(isset($reading1) && $reading1!=""){ ?>
				<script>
					document.getElementById("reading1").value = "<?php echo str_replace("\n", '\n', $reading1 );  ?>";
					document.getElementById("reading1").style.color = "";
				</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reading1_error'></div></div>
		</div>
				
		<div class='languageCol'>
        	<input type='text' name='writing1' id='writing1'  validate="validateAlphabetic"  caption="Writing"  minlength="2"  maxlength="10"  tip="Enter your Writing skills in this language"  value='' class="langTxtField" />
			<?php if(isset($writing1) && $writing1!=""){ ?>
				<script>
					document.getElementById("writing1").value = "<?php echo str_replace("\n", '\n', $writing1 );  ?>";
					document.getElementById("writing1").style.color = "";
				</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'writing1_error'></div></div>
		</div>
		
        <div class='languageCol'>
        	<input type='text' name='speaking1' id='speaking1'  validate="validateAlphabetic"    caption="Speaking"   minlength="2" maxlength="10"  tip="Enter your speaking skills in this language"   value='' class="langTxtField" />
			<?php if(isset($speaking1) && $speaking1!=""){ ?>
				<script>
                    document.getElementById("speaking1").value = "<?php echo str_replace("\n", '\n', $speaking1 );  ?>";
                    document.getElementById("speaking1").style.color = "";
                </script>
            <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'speaking1_error'></div></div>
		</div>
        
	</li>
    
    <li>
		<div class='languageCol'>
			<input type='text' name='language2' id='language2'  validate="validateAlphabetic"    caption="Language"   minlength="2"   maxlength="50"     tip="Enter the Language like English, Hindi"   value='' class="langTxtField" />
			<?php if(isset($language2) && $language2!=""){ ?>
				<script>
                    document.getElementById("language2").value = "<?php echo str_replace("\n", '\n', $language2 );  ?>";
                    document.getElementById("language2").style.color = "";
                </script>
            <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'language2_error'></div></div>
		</div>
		
        <div class='languageCol'>
        	<input type='text' name='reading2' id='reading2'  validate="validateAlphabetic"    caption="Reading"   minlength="2"   maxlength="10"     tip="Enter your reading skills in this language"   value='' class="langTxtField" />
			<?php if(isset($reading2) && $reading2!=""){ ?>
				<script>
                    document.getElementById("reading2").value = "<?php echo str_replace("\n", '\n', $reading2 );  ?>";
                    document.getElementById("reading2").style.color = "";
                </script>
            <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reading2_error'></div></div>
		</div>
		
        <div class='languageCol'>
        	<input type='text' name='writing2' id='writing2'  validate="validateAlphabetic"    caption="Writing"   minlength="2"   maxlength="10"     tip="Enter your Writing skills in this language"   value='' class="langTxtField" />
			<?php if(isset($writing2) && $writing2!=""){ ?>
				<script>
                    document.getElementById("writing2").value = "<?php echo str_replace("\n", '\n', $writing2 );  ?>";
                    document.getElementById("writing2").style.color = "";
                </script>
             <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'writing2_error'></div></div>
		</div>
		
        <div class='languageCol'>
        	<input type='text' name='speaking2' id='speaking2'  validate="validateAlphabetic"    caption="Speaking"   minlength="2"   maxlength="10"     tip="Enter your speaking skills in this language" value='' class="langTxtField" />
			<?php if(isset($speaking2) && $speaking2!=""){ ?>
				<script>
                    document.getElementById("speaking2").value = "<?php echo str_replace("\n", '\n', $speaking2 );  ?>";
                    document.getElementById("speaking2").style.color = "";
                </script>
            <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'speaking2_error'></div></div>
		</div>
	</li>
    
    <li>
		<div class='languageCol'>
			<input type='text' name='language3' id='language3'  validate="validateAlphabetic"    caption="Language"   minlength="2"   maxlength="50"     tip="Enter the Language like English, Hindi"   value='' class="langTxtField" />
			<?php if(isset($language3) && $language3!=""){ ?>
				<script>
                    document.getElementById("language3").value = "<?php echo str_replace("\n", '\n', $language3 );  ?>";
                    document.getElementById("language3").style.color = "";
                </script>
             <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'language3_error'></div></div>
		</div>
		
        <div class='languageCol'>
        	<input type='text' name='reading3' id='reading3'  validate="validateAlphabetic"    caption="Reading"   minlength="2"   maxlength="10"     tip="Enter your reading skills in this language"   value='' class="langTxtField" />
			<?php if(isset($reading3) && $reading3!=""){ ?>
                <script>
                    document.getElementById("reading3").value = "<?php echo str_replace("\n", '\n', $reading3 );  ?>";
                    document.getElementById("reading3").style.color = "";
                </script>
            <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'reading3_error'></div></div>
        </div>
	
        <div class='languageCol'>
            <input type='text' name='writing3' id='writing3'  validate="validateAlphabetic"    caption="Writing"   minlength="2"   maxlength="10"     tip="Enter your Writing skills in this language"   value='' class="langTxtField" />
            <?php if(isset($writing3) && $writing3!=""){ ?>
                <script>
                    document.getElementById("writing3").value = "<?php echo str_replace("\n", '\n', $writing3 );  ?>";
                    document.getElementById("writing3").style.color = "";
                </script>
            <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'writing3_error'></div></div>
        </div>
        
        <div class='languageCol'>
            <input type='text' name='speaking3' id='speaking3'  validate="validateAlphabetic"  caption="Speaking"  minlength="2"   maxlength="10"     tip="Enter your speaking skills in this language"   value='' class="langTxtField" />
            <?php if(isset($speaking3) && $speaking3!=""){ ?>
                <script>
                    document.getElementById("speaking3").value = "<?php echo str_replace("\n", '\n', $speaking3 );  ?>";
                    document.getElementById("speaking3").style.color = "";
                </script>
            <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'speaking3_error'></div></div>
        </div>
    </li>
	</ul>
    <div class="clearFix"></div>
    </div>
    </li>
    
    <li>
    	<label>Total Work Experience (in months) : </label>
        <div class='float_L'>
        	<input type='text' name='totalWorkEx' id='totalWorkEx'  validate="validateInteger"    caption="Work Experience"   minlength="1"   maxlength="3"     tip="Enter your Total Work Experience (No. of months)"   value=''  />
			<?php if(isset($totalWorkEx) && $totalWorkEx!=""){ ?>
                <script>
                    document.getElementById("totalWorkEx").value = "<?php echo str_replace("\n", '\n', $totalWorkEx );  ?>";
                    document.getElementById("totalWorkEx").style.color = "";
                </script>
            <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'totalWorkEx_error'></div></div>
        </div>
	</li>
    
    <li>
    	<label>Address of Current Employer (if applicable) : </label>
        <div class='float_L'>
        	<textarea name='employerAddress' id='employerAddress' tip="Enter the Address of Current Employer (if applicable)"></textarea>
			<?php if(isset($employerAddress) && $employerAddress!=""){ ?>
				<script>
                    document.getElementById("employerAddress").value = "<?php echo str_replace("\n", '\n', $employerAddress );  ?>";
                    document.getElementById("employerAddress").style.color = "";
                </script>
            <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'employerAddress_error'></div></div>
		</div>
	</li>
   <?php endif;?>
	<li>
    	<label>Date when you would like to take NUMAT : </label>
        <div class='float_L'>
		<input type='text' name='numatDate' id='numatDate' readonly maxlength='10'  tip="Date when you would like to take NUMAT"    default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('numatDate'),'numatDate_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='numatDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('numatDate'),'numatDate_dateImg','dd/MM/yyyy'); return false;" />
			<script>
                document.getElementById("numatDate").style.color = "#ADA6AD";
            </script>
            <?php if(isset($numatDate) && $numatDate!=""){ ?>
                <script>
                    document.getElementById("numatDate").value = "<?php echo str_replace("\n", '\n', $numatDate );  ?>";
                    document.getElementById("numatDate").style.color = "";
                </script>
            <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'numatDate_error'></div></div>
        </div>
	</li>
    
    <li>
    	<label>Aptitude Test Percentile (if applicable) :</label>
		<div class='float_L' style="padding: 0 5px; width:100px">
        	<strong>CAT : </strong> <input style="width: 50px" type='text' name='catPercentileNIIT' id='catPercentileNIIT'  validate="validateFloat"    caption="CAT Percentile"   minlength="1"   maxlength="4"     tip="CAT Aptitude Test Percentile (if applicable)"   value=''  />
			<?php if(isset($catPercentileNIIT) && $catPercentileNIIT!=""){ ?>
				<script>
                    document.getElementById("catPercentileNIIT").value = "<?php echo str_replace("\n", '\n', $catPercentileNIIT );  ?>";
                    document.getElementById("catPercentileNIIT").style.color = "";
                </script>
	      	<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'catPercentileNIIT_error'></div></div>
		</div>

		<div class='float_L' style="padding: 0 10px; width:100px"><strong>MAT : </strong> <input style="width: 50px" type='text' name='matPercentileNIIT' id='matPercentileNIIT'  validate="validateFloat"    caption="MAT Percentile"   minlength="1"   maxlength="4"     tip="MAT Aptitude Test Percentile (if applicable)"   value=''  />
			<?php if(isset($matPercentileNIIT) && $matPercentileNIIT!=""){ ?>
			<script>
                document.getElementById("matPercentileNIIT").value = "<?php echo str_replace("\n", '\n', $matPercentileNIIT );  ?>";
                document.getElementById("matPercentileNIIT").style.color = "";
            </script>
	      	<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'matPercentileNIIT_error'></div></div>
		</div>

		<div class='float_L' style="padding: 0 10px; width:100px"><strong>XAT : </strong> <input style="width: 50px" type='text' name='xatPercentileNIIT' id='xatPercentileNIIT'  validate="validateFloat"    caption="XAT Percentile"   minlength="1"   maxlength="4"     tip="XAT Aptitude Test Percentile (if applicable)"   value=''  />
			<?php if(isset($xatPercentileNIIT) && $xatPercentileNIIT!=""){ ?>
				<script>
                    document.getElementById("xatPercentileNIIT").value = "<?php echo str_replace("\n", '\n', $xatPercentileNIIT );  ?>";
                    document.getElementById("xatPercentileNIIT").style.color = "";
                </script>
	      	<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'xatPercentileNIIT_error'></div></div>
		</div>

		<div class='float_L' style="padding: 0 10px; width:100px"><strong>GMAT :</strong> <input style="width: 50px" type='text' name='gmatPercentileNIIT' id='gmatPercentileNIIT'  validate="validateFloat"    caption="GMAT Percentile"   minlength="1"   maxlength="4"     tip="GMAT Aptitude Test Percentile (if applicable)"   value=''  />
			<?php if(isset($gmatPercentileNIIT) && $gmatPercentileNIIT!=""){ ?>
			<script>
                document.getElementById("gmatPercentileNIIT").value = "<?php echo str_replace("\n", '\n', $gmatPercentileNIIT );  ?>";
                document.getElementById("gmatPercentileNIIT").style.color = "";
            </script>
	      	<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'gmatPercentileNIIT_error'></div></div>
		</div>
	</li>
    <?php if($action != 'updateScore'):?>
    <li>
    	<label>How did you get to know about NIIT UNIVERSITY? : </label>
      	<div class='float_L'>
			<div class="aboutLabel">
		    	<span><input type='checkbox' name='knowAboutUniversity[]' id='knowAboutUniversity0'   value='Newspaper' onClick="enableWhyChoose(this,'newspaperText');" ></input>Newspaper</span> <strong>Name:</strong>
              </div>
            <div class="fieldBoxLarge">
              	<input type='text' name='newspaperText' id='newspaperText'  tip="Enter the Newspaper name"  class="textboxLarge" value=''  disabled/>
              	<div style='display:none'><div class='errorMsg' id= 'newspaperText_error'></div></div>
            </div>
			<div class='clear_B spacer15'></div>

			<div class="aboutLabel">
			      <span><input type='checkbox' name='knowAboutUniversity[]' id='knowAboutUniversity1'   value='Magazine'  onClick="enableWhyChoose(this,'magazineText');" ></input>Magazine</span><strong>Name:</strong> 
              </div>
            
            <div class="fieldBoxLarge">
              	<input type='text' name='magazineText' id='magazineText' tip="Enter the Magazine name" value='' class="textboxLarge" disabled/>
		      	<div style='display:none'><div class='errorMsg' id= 'magazineText_error'></div></div>
              </div>
			<div class='clear_B spacer15'></div>

            <div class="aboutLabel">
		      		<span><input type='checkbox' name='knowAboutUniversity[]' id='knowAboutUniversity2'   value='Coaching Institute' onClick="enableWhyChoose(this,'coachingText');"  ></input>Coaching Institute </span><strong>Name:</strong>
              </div>
              
            <div class="fieldBoxLarge">
              	<input type='text' name='coachingText' id='coachingText' tip="Enter the Coaching Institute name"   value='' class="textboxLarge" disabled/>
		      	<div style='display:none'><div class='errorMsg' id= 'coachingText_error'></div></div>
              </div>
			<div class='clear_B spacer15'></div>
			
            <div class="aboutLabel">
		      	<span><input type='checkbox' name='knowAboutUniversity[]' id='knowAboutUniversity3'   value='Internet'  onClick="enableWhyChoose(this,'internetText');" ></input>Internet</span><strong>Website:</strong>
                </div>
                
            <div class="fieldBoxLarge">
                    <input type='text' name='internetText' id='internetText' tip="Enter the Website name"   value='' class="textboxLarge" disabled />
		      		<div style='display:none'><div class='errorMsg' id= 'internetText_error'></div></div>
              </div>
			<div class='clear_B spacer15'></div>
            
            <div class="aboutLabel">
		      		<span><input type='checkbox' name='knowAboutUniversity[]' id='knowAboutUniversity4'   value='Friends/Relatives'  onClick="enableWhyChoose(this,'relativeText');" ></input>Friends/Relatives </span><strong>Name:</strong>
                </div>
            <div class="fieldBoxLarge">
                    <input type='text' name='relativeText' id='relativeText' tip="Enter the Friend/Relative name"   value='' class="textboxLarge" disabled />
		      		<div style='display:none'><div class='errorMsg' id= 'relativeText_error'></div></div>
                </div>
			<div class='clear_B spacer15'></div>
            
            <div class="aboutLabel">
		      	<span><input type='checkbox' name='knowAboutUniversity[]' id='knowAboutUniversity5'   value='Any Other' onClick="enableWhyChoose(this,'otherText');"  ></input>Any Other </span><strong>Specify:</strong>
                </div>
                
            <div class="fieldBoxLarge">
                    <input type='text' name='otherText' id='otherText' tip="Any other, please specify"   value='' class="textboxLarge" disabled />
		      		<div style='display:none'><div class='errorMsg' id= 'otherText_error'></div></div>
              	</div>
			<div class='clear_B'></div>
		</div>
      	<div class='clear_B'></div>
	</li>

<?php if(isset($knowAboutUniversity) && $knowAboutUniversity!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["knowAboutUniversity[]"];
		    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$knowAboutUniversity);
				    for($x=0;$x<count($arr);$x++){ ?>
					  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
						  objCheckBoxes[i].checked = true;
					  }
			      <?php
				    }
			      ?>
		    }
		</script>
	      <?php } ?>

<?php if(isset($newspaperText) && $newspaperText!=""){ ?>
		<script>
		    document.getElementById("newspaperText").value = "<?php echo str_replace("\n", '\n', $newspaperText );  ?>";
		    document.getElementById("newspaperText").style.color = "";
		    document.getElementById("newspaperText").disabled = false;
		</script>
	      <?php } ?>


<?php if(isset($magazineText) && $magazineText!=""){ ?>
		<script>
		    document.getElementById("magazineText").value = "<?php echo str_replace("\n", '\n', $magazineText );  ?>";
		    document.getElementById("magazineText").style.color = "";
		    document.getElementById("magazineText").disabled = false;
		</script>
	      <?php } ?>

<?php if(isset($coachingText) && $coachingText!=""){ ?>
		<script>
		    document.getElementById("coachingText").value = "<?php echo str_replace("\n", '\n', $coachingText );  ?>";
		    document.getElementById("coachingText").style.color = "";
		    document.getElementById("coachingText").disabled = false;
		</script>
	      <?php } ?>

<?php if(isset($internetText) && $internetText!=""){ ?>
		<script>
		    document.getElementById("internetText").value = "<?php echo str_replace("\n", '\n', $internetText );  ?>";
		    document.getElementById("internetText").style.color = "";
		    document.getElementById("internetText").disabled = false;
		</script>
	      <?php } ?>

<?php if(isset($relativeText) && $relativeText!=""){ ?>
		<script>
		    document.getElementById("relativeText").value = "<?php echo str_replace("\n", '\n', $relativeText );  ?>";
		    document.getElementById("relativeText").style.color = "";
		    document.getElementById("relativeText").disabled = false;
		</script>
	      <?php } ?>

<?php if(isset($otherText) && $otherText!=""){ ?>
		<script>
		    document.getElementById("otherText").value = "<?php echo str_replace("\n", '\n', $otherText );  ?>";
		    document.getElementById("otherText").style.color = "";
		    document.getElementById("otherText").disabled = false;
		</script>
	      <?php } ?>


	<li>
    	<label>STATEMENT OF PURPOSE : </label>
        <div class='float_L'>
			<textarea style="width: 500px;" name='statementOfPurpose' id='statementOfPurpose'  validate="validateStr"   required="true"   caption="Statement of Purpose"   minlength="2"   maxlength="2500"     tip="In this space, please write your Statement of Purpose for wishing to undertake MBA program. Your answer will be used to assess your suitability for the program. Do not exceed the space provided. Typically, your answer should include your reasons for doing this program, how the program relates to your background and experience so far and what you wish to achieve after completing this program."   ></textarea>
			<?php if(isset($statementOfPurpose) && $statementOfPurpose!=""){ ?>
			<script>
                document.getElementById("statementOfPurpose").value = "<?php echo str_replace("\n", '\n', $statementOfPurpose );  ?>";
                document.getElementById("statementOfPurpose").style.color = "";
            </script>
	      	<?php } ?>
			<div class="imageSizeInfo">(Maximum 2500 characters)</div>
			<div style='display:none'><div class='errorMsg' id= 'statementOfPurpose_error'></div></div>
		</div>
	</li>

	<li>
    	<label>OTHER STATEMENTS : </label>
        <div class='float_L'>
        	<textarea style="width: 500px;" name='personsInfluenced' id='personsInfluenced'  validate="validateStr"   required="true"   caption="Other Statement"   minlength="2" maxlength="1500" tip="In the space given below, mention the names of two persons who have influenced/impressed you the most and also mention the reasons."   ></textarea>
			<?php if(isset($personsInfluenced) && $personsInfluenced!=""){ ?>
				<script>
                    document.getElementById("personsInfluenced").value = "<?php echo str_replace("\n", '\n', $personsInfluenced );  ?>";
                    document.getElementById("personsInfluenced").style.color = "";
                </script>
            <?php } ?>
			<div class="imageSizeInfo">(Maximum 1500 characters)</div>
			<div style='display:none'><div class='errorMsg' id= 'personsInfluenced_error'></div></div>
		</div>
	</li>

	<li>
    	<label></label>
        <div class='float_L'>
        	<textarea style="width: 500px;" name='adverseSituation' id='adverseSituation'  validate="validateStr" required="true" caption="Other Statement" minlength="2"   maxlength="1500" tip="In the space given below, mention one adverse situation in your life that you had to face and how you managed that situation."   ></textarea>
			<?php if(isset($adverseSituation) && $adverseSituation!=""){ ?>
			<script>
                document.getElementById("adverseSituation").value = "<?php echo str_replace("\n", '\n', $adverseSituation );  ?>";
                document.getElementById("adverseSituation").style.color = "";
            </script>
	      	<?php } ?>
			<div class="imageSizeInfo">(Maximum 1500 characters)</div>
			<div style='display:none'><div class='errorMsg' id= 'adverseSituation_error'></div></div>
		</div>
	</li>

	<li>
    	<label></label>
        <div class='float_L'>
        	<textarea style="width: 500px;" name='academicHighlights' id='academicHighlights'  validate="validateStr"   required="true"   caption="Other Statement"   minlength="2" maxlength="1500" tip="In the space below, please mention highlights of your academic career including awards, scholarships or any other relevant details."   ></textarea>
			<?php if(isset($academicHighlights) && $academicHighlights!=""){ ?>
			<script>
                document.getElementById("academicHighlights").value = "<?php echo str_replace("\n", '\n', $academicHighlights );  ?>";
                document.getElementById("academicHighlights").style.color = "";
            </script>
	      	<?php } ?>
			<div class="imageSizeInfo">(Maximum 1500 characters)</div>
			<div style='display:none'><div class='errorMsg' id= 'academicHighlights_error'></div></div>
		</div>
	</li>

	<li>
    	<label></label>
        <div class='float_L'>
        	<textarea style="width: 500px;" name='workExDetails' id='workExDetails'  validate="validateStr" caption="Other Statement"   minlength="2" maxlength="1500" tip="In the space given below, please mention highlights, achievements, clarifications or additional comments about your work experience."   ></textarea>
			<?php if(isset($workExDetails) && $workExDetails!=""){ ?>
			<script>
                document.getElementById("workExDetails").value = "<?php echo str_replace("\n", '\n', $workExDetails );  ?>";
                document.getElementById("workExDetails").style.color = "";
            </script>
	      	<?php } ?>
            <div class="imageSizeInfo">(Maximum 1500 characters)</div>
            <div style='display:none'><div class='errorMsg' id= 'workExDetails_error'></div></div>
		</div>
	</li>
	<?php endif;?>
</ul>


<script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
    <script>
	var selObj = document.getElementById("city"); 
	if(selObj){
	      var A= selObj.options, L= A.length;
	      while(L){
		  L = L-1;
		  if (A[L].innerHTML == "<?php echo $city;?>" || A[L].value == "<?php echo $city;?>"){
		      selObj.selectedIndex= L;
		      L= 0;
		  }
	      }
	}
    </script>
  <?php } ?><?php if(isset($Ccity) && $Ccity!=""){ ?>
    <script>
	var selObj = document.getElementById("Ccity"); 
	if(selObj){
	      var A= selObj.options, L= A.length;
	      while(L){
		  L = L-1;
		  if (A[L].innerHTML == "<?php echo $Ccity;?>" || A[L].value == "<?php echo $Ccity;?>"){
		      selObj.selectedIndex= L;
		      L= 0;
		  }
	      }
	}
    </script>
  <?php } ?><script>
  function showMoreGroups(groupId,maxAllowed){
	if(document.getElementById(groupId)){
	      for(i=1;i<=maxAllowed;i++){
		  if(document.getElementById(String(groupId)+String(i)).style.display == 'none'){
		      document.getElementById(String(groupId)+String(i)).style.display = '';
		      break;
		  }
	      }
	      if(i==maxAllowed){
		  document.getElementById('showMore'+groupId).style.display = 'none';
	      }
	}
  }if(document.getElementById('courseCode')){
	document.getElementById('courseCode').readonly = true;
  }function copyAddressFields(){
	if(document.getElementById('city') && document.getElementById('Ccity')){
		document.getElementById('ChouseNumber').value = document.getElementById('houseNumber').value;
		document.getElementById('CstreetName').value = document.getElementById('streetName').value;
		document.getElementById('Carea').value = document.getElementById('area').value;
		document.getElementById('Cpincode').value = document.getElementById('pincode').value;

		var sel = document.getElementById('country');
		var countrySelected = sel.options[sel.selectedIndex].value;
		var selObj = document.getElementById('Ccountry'); 
		var A= selObj.options, L= A.length;
		while(L){
		    if (A[--L].value== countrySelected){
			selObj.selectedIndex= L;
			L= 0;
		    }
		}

		getCitiesForCountryOnlineCorrespondence("",false,"",false);

		var sel = document.getElementById('city');
		var citySelected = sel.options[sel.selectedIndex].value;
		var selObj = document.getElementById('Ccity'); 
		var A= selObj.options, L= A.length;
		while(L){
		    if (A[--L].value== citySelected){
			selObj.selectedIndex= L;
			L= 0;
		    }
		}

	}
  }

  function enableWhyChoose(checkId,objName){
	if(checkId.checked == true){
		$(objName).disabled = false;
	}
	else{
		$(objName).disabled = true;
		$(objName).value = '';
	}
  }
  </script>

</div>

</div>