<div class='formChildWrapper'>
	<div class='formSection'>
		
		<ul>
			<li>
				<h3 class="upperCase">Personal Information</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Aadhaar Number: <span style="color: red;">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Aadhaar_NumberADYPUBTECH2019' id='Aadhaar_NumberADYPUBTECH2019'  validate="validateInteger"   required="true"   caption="Aadhaar Number"   minlength="1"   maxlength="12"      value=''   />
				<?php if(isset($Aadhaar_NumberADYPUBTECH2019) && $Aadhaar_NumberADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Aadhaar_NumberADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Aadhaar_NumberADYPUBTECH2019 );  ?>";
				      document.getElementById("Aadhaar_NumberADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Aadhaar_NumberADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>
		
			<li>
				<h3 class="upperCase">Family Information</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Annual Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Father_Annual_IncomeADYPUBTECH2019' id='Father_Annual_IncomeADYPUBTECH2019'  validate="validateInteger"    caption="Father's Annual Income"   minlength="1"   maxlength="10"      value=''   />
				<?php if(isset($Father_Annual_IncomeADYPUBTECH2019) && $Father_Annual_IncomeADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Father_Annual_IncomeADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Father_Annual_IncomeADYPUBTECH2019 );  ?>";
				      document.getElementById("Father_Annual_IncomeADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Father_Annual_IncomeADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Annual Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Mother_Annual_IncomeADYPUBTECH2019' id='Mother_Annual_IncomeADYPUBTECH2019'  validate="validateInteger"    caption="Mother's Annual Income"   minlength="1"   maxlength="10"      value=''   />
				<?php if(isset($Mother_Annual_IncomeADYPUBTECH2019) && $Mother_Annual_IncomeADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Mother_Annual_IncomeADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Mother_Annual_IncomeADYPUBTECH2019 );  ?>";
				      document.getElementById("Mother_Annual_IncomeADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Mother_Annual_IncomeADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Graduation Information</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Graduation Percentage marks (Applicable only in case of CGPA/Grades): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Graduation_PercentageADYPUBTECH2019' id='Graduation_PercentageADYPUBTECH2019'  validate="validateFloat"    caption="Graduation percentage marks"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($Graduation_PercentageADYPUBTECH2019) && $Graduation_PercentageADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Graduation_PercentageADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Graduation_PercentageADYPUBTECH2019 );  ?>";
				      document.getElementById("Graduation_PercentageADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Graduation_PercentageADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Document in support of Graduation Grade to Percentage conversion: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='Document_Grade_Percent_ConversionADYPUBTECH2019'          />
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				<input type='hidden' name='Document_Grade_Percent_ConversionADYPUBTECH2019Valid' value=''>
				<div style='display:none'><div class='errorMsg' id= 'Document_Grade_Percent_ConversionADYPUBTECH2019_error'></div></div>
				<label id="grad_file" style="width: max-content">
					<?php if(isset($Document_Grade_Percent_ConversionADYPUBTECH2019) && $Document_Grade_Percent_ConversionADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("grad_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Course Information</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Applied For: <span style="color: red;">*</span></label>
				<div class='fieldBoxLarge'>
				<select name='Course_AppliedADYPUBTECH2019' id='Course_AppliedADYPUBTECH2019'      validate="validateSelect"   required="true"   caption="Course Applied For"  ><option value='' selected>Select</option><option value=' B.Tech. in Mechatronics Engineering' > B.Tech. in Mechatronics Engineering</option><option value=' B.Tech. in Biomedical Engineering' > B.Tech. in Biomedical Engineering</option><option value=' B.Tech. in Computer Engineering' > B.Tech. in Computer Engineering</option><option value=' B.Tech. in Biotechnology Engineering' > B.Tech. in Biotechnology Engineering</option><option value=' B.Tech. in Mechanical Engineering' > B.Tech. in Mechanical Engineering</option></select>
				<?php if(isset($Course_AppliedADYPUBTECH2019) && $Course_AppliedADYPUBTECH2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Course_AppliedADYPUBTECH2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Course_AppliedADYPUBTECH2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Course_AppliedADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<h3 class="upperCase">Awards and Honours</h3>
			</li>
			<li>
				<h3 class="lowerCase">AWARD 1</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Year_1ADYPUBTECH2019' id='Year_1ADYPUBTECH2019'  validate="validateInteger"    caption="Year"   minlength="4"   maxlength="4"      value=''   />
				<?php if(isset($Year_1ADYPUBTECH2019) && $Year_1ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Year_1ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Year_1ADYPUBTECH2019 );  ?>";
				      document.getElementById("Year_1ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Year_1ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Name of the Award: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Award_Name_1ADYPUBTECH2019' id='Award_Name_1ADYPUBTECH2019'  validate="validateStr"    caption="Name of the Award"   minlength="1"   maxlength="500"      value=''   />
				<?php if(isset($Award_Name_1ADYPUBTECH2019) && $Award_Name_1ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Award_Name_1ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Award_Name_1ADYPUBTECH2019 );  ?>";
				      document.getElementById("Award_Name_1ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Award_Name_1ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Awarding_Institution_1ADYPUBTECH2019' id='Awarding_Institution_1ADYPUBTECH2019'  validate="validateStr"    caption="Awarding Institution"   minlength="1"   maxlength="500"      value=''   />
				<?php if(isset($Awarding_Institution_1ADYPUBTECH2019) && $Awarding_Institution_1ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Awarding_Institution_1ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Awarding_Institution_1ADYPUBTECH2019 );  ?>";
				      document.getElementById("Awarding_Institution_1ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Awarding_Institution_1ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Level (International, National, State): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Level_1ADYPUBTECH2019' id='Level_1ADYPUBTECH2019'  validate="validateStr"    caption="Level"   minlength="1"   maxlength="500"      value=''   />
				<?php if(isset($Level_1ADYPUBTECH2019) && $Level_1ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Level_1ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Level_1ADYPUBTECH2019 );  ?>";
				      document.getElementById("Level_1ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Level_1ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Remarks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Remarks_1ADYPUBTECH2019' id='Remarks_1ADYPUBTECH2019'  validate="validateStr"    caption="Remarks"   minlength="1"   maxlength="500"      value=''   />
				<?php if(isset($Remarks_1ADYPUBTECH2019) && $Remarks_1ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Remarks_1ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Remarks_1ADYPUBTECH2019 );  ?>";
				      document.getElementById("Remarks_1ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Remarks_1ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<h3 class="lowerCase">AWARD 2</h3>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Year_2ADYPUBTECH2019' id='Year_2ADYPUBTECH2019'  validate="validateInteger"    caption="Year"   minlength="4"   maxlength="4"      value=''   />
				<?php if(isset($Year_2ADYPUBTECH2019) && $Year_2ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Year_2ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Year_2ADYPUBTECH2019 );  ?>";
				      document.getElementById("Year_2ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Year_2ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Name of the Award: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Award_Name_2ADYPUBTECH2019' id='Award_Name_2ADYPUBTECH2019'  validate="validateStr"    caption="Name of the Award"   minlength="1"   maxlength="500"      value=''   />
				<?php if(isset($Award_Name_2ADYPUBTECH2019) && $Award_Name_2ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Award_Name_2ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Award_Name_2ADYPUBTECH2019 );  ?>";
				      document.getElementById("Award_Name_2ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Award_Name_2ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Awarding_Institution_2ADYPUBTECH2019' id='Awarding_Institution_2ADYPUBTECH2019'  validate="validateStr"    caption="Awarding Institution"   minlength="1"   maxlength="500"      value=''   />
				<?php if(isset($Awarding_Institution_2ADYPUBTECH2019) && $Awarding_Institution_2ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Awarding_Institution_2ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Awarding_Institution_2ADYPUBTECH2019 );  ?>";
				      document.getElementById("Awarding_Institution_2ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Awarding_Institution_2ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Level (International, National, State): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Level_2ADYPUBTECH2019' id='Level_2ADYPUBTECH2019'  validate="validateStr"    caption="Level"   minlength="1"   maxlength="500"      value=''   />
				<?php if(isset($Level_2ADYPUBTECH2019) && $Level_2ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Level_2ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Level_2ADYPUBTECH2019 );  ?>";
				      document.getElementById("Level_2ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Level_2ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Remarks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Remarks_2ADYPUBTECH2019' id='Remarks_2ADYPUBTECH2019'  validate="validateStr"    caption="Remarks"   minlength="1"   maxlength="500"      value=''   />
				<?php if(isset($Remarks_2ADYPUBTECH2019) && $Remarks_2ADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Remarks_2ADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Remarks_2ADYPUBTECH2019 );  ?>";
				      document.getElementById("Remarks_2ADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Remarks_2ADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Others</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Why would you choose to study at ADYPU? What are your career plans? (Max. 50 words): </label>
				<div class='fieldBoxLarge'>
				<textarea name='Study_at_ADYPUADYPUBTECH2019' id='Study_at_ADYPUADYPUBTECH2019'  validate="validateStr"    caption="Fill up"   minlength="1"   maxlength="3200"       ></textarea>
				<?php if(isset($Study_at_ADYPUADYPUBTECH2019) && $Study_at_ADYPUADYPUBTECH2019!=""){ ?>
				  <script>
				      document.getElementById("Study_at_ADYPUADYPUBTECH2019").value = "<?php echo str_replace("\n", '\n', $Study_at_ADYPUADYPUBTECH2019 );  ?>";
				      document.getElementById("Study_at_ADYPUADYPUBTECH2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Study_at_ADYPUADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>How do you come to know about us?: </label>
				<div class='fieldBoxLarge'>
				<span class="dflt-chkcbox">	
				<input type='checkbox'  caption="these"   name='Know_about_ADYPUADYPUBTECH2019[]' id='Know_about_ADYPUADYPUBTECH20191'   value='Newspaper'></input><span >Newspaper</span>&nbsp;&nbsp;
				</span>
				<span class="dflt-chkcbox"><input type='checkbox'    caption="these"   name='Know_about_ADYPUADYPUBTECH2019[]' id='Know_about_ADYPUADYPUBTECH20192'   value=' Friends'    ></input><span > Friends</span>&nbsp;&nbsp;
				</span>
				<span class="dflt-chkcbox"><input type='checkbox'    caption="these"   name='Know_about_ADYPUADYPUBTECH2019[]' id='Know_about_ADYPUADYPUBTECH20193'   value=' Website'    ></input><span > Website</span>&nbsp;&nbsp;</span>
				<span class="dflt-chkcbox"><input type='checkbox'    caption="these"   name='Know_about_ADYPUADYPUBTECH2019[]' id='Know_about_ADYPUADYPUBTECH20194'   value=' Others'    ></input><span > Others</span>&nbsp;&nbsp;</span>
				<?php if(isset($Know_about_ADYPUADYPUBTECH2019) && $Know_about_ADYPUADYPUBTECH2019!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Know_about_ADYPUADYPUBTECH2019[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Know_about_ADYPUADYPUBTECH2019);
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
				
				<div style='display:none'><div class='errorMsg' id= 'Know_about_ADYPUADYPUBTECH2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3>Declaration</h3>
				<div class='fieldBoxLarge' style="width: 100% ; color: #666666; font-style: italic;">
					<ul>
						<li>
						All entries made in the application form are true to the best of my knowledge and belief. I am willing to produce original certificates on demand at any time. I also undertake that I shall abide by the rules and regulations of Ajeenkya DY Patil University.(ADYPU).
						</li>
					</ul>
				</div>

				<div class="additionalInfoLeftCol">

					<label style="width: 100%; text-align: left;">
						
						<input type='checkbox' validate='validateChecked'  required="true" caption ='Please accept the terms'   name='TermsADYPUBTECH2019[]' id='TermsADYPUBTECH2019'   value='1'  checked  ></input>
							I agree to the above terms and conditions
						<span ></span>&nbsp;&nbsp;
				
				<?php if(isset($ADYPUBTECH2019_Terms) && $ADYPUBTECH2019_Terms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["TermsADYPUBTECH2019[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$TermsADYPUBTECH2019);
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
				
					<div style='display:none'>
						<div class='errorMsg' id= 'TermsADYPUBTECH2019_error'>
							
						</div>
					</div>
				</label>
				</div>
			</li>

		</ul>
	</div>
</div>


<style>
	.dflt-chkcbox {
	    display: inline-block;
	    margin: 0px 5px 5px 0px;
	}
  	#appsFormWrapper .fieldBoxLarge select{width: 214px;background: #fff;border: 1px solid #ccc;padding: 4px 10px}
  	#appsFormWrapper .fieldBoxLarge input[type='text']{border-radius: 2px;width: 73%;}
  	#appsFormWrapper .fieldBoxSmall input[type='text']{padding: 5px 2px;border-radius: 2px;}
  	#appsFormWrapper .formChildWrapper h3.upperCase, .formSection ul li h3{background: #f1f1f1;margin: 0 -10px;padding: 10px 15px;}
  	#appsFormWrapper h3.upperCase{    padding: 10px 10px;
    background: #f1f1f1;
    margin: 0 -10px;}
    #appsFormWrapper .formChildWrapper h3.lowerCase, .formSection ul li h3{font-size: 14px;
    background: none;margin: 0 -10px;padding: 10px 15px;}
  	#appsFormWrapper h3.lowerCase{    padding: 10px 10px;
    margin: 0 -10px;}
    .additionalInfoLeftCol label span{color: red;}
    .additionalInfoLeftCol label span{color: red;}
    .errorMsg{pointer-events: none;}
    .fieldBoxLarge input[type="radio"], .fieldBoxLarge input[type="checkbox"] {
    position: relative;top: 2px;}
  </style>
