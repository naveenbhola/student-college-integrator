<script >

	function showDependentStream(obj){
		var key = obj;
		if (key == ''){
			$("NIITBTECH_stream").innerHTML = '<option selected value = "">Select</option>';
			return;
		}
		if(key == '14'){
			$("NIITBTECH_stream").innerHTML = '<option selected value = "">Select</option><option value = "MG" <?php if ($NIITBTECH_stream == "MG") echo "selected"; ?> >Management (MG)</option>';
			changeDual(0);
		}
		else if (key == '11'){
			$("NIITBTECH_stream").innerHTML = '<option selected value ="">Select</option><option value = "CS" <?php if ($NIITBTECH_stream == "CS") echo "selected"; ?> >Computer Science (SC)</option>';
			
			changeDual(0);
		}
		else{
			$("NIITBTECH_stream").innerHTML = '<option selected value ="">Select</option><option value = "BT" <?php if ($NIITBTECH_stream == "BT") echo "selected"; ?> >Biotechnology (BT)</option><option value = "CSE" <?php if ($NIITBTECH_stream == "CSE") echo "selected"; ?>>Computer Science and Engineering (CSE)</option><option value = "ECE" <?php if ($NIITBTECH_stream == "ECE") echo "selected"; ?>>Electronics and Communication Engineering (ECE)</option>';
			if (key == '2'){
				changeDual(1);
			}
			else{
				changeDual(0);
			}
		}
	}

	function changeDual(obj){
		document.getElementById("NIITBTECH_Isdual").value = obj;
	}

</script>
<div class='formChildWrapper'>
	<div id="instructions" style="font: normal 12px Arial, Helvetica, Sans Sarif; padding: 12px;">
	    <h3 style="margin: 0;background: transparent;padding: 0px;">Eligibility</h3>
	    <ul>
			<li>
			1. ≥60% marks or equivalent in Class X in five subjects (Compulsory subjects - English, Mathematics, Science and Social Studies. The fifth subject can be indicative as per applicant’s choice.). The board can be CBSE/ICSE/any other board in India or an equivalent board if from a country outside India.
			</li>
	        <li>
	    	2. ≥60% marks in Class XII in five subjects (Compulsory subjects - Physics, Chemistry, Mathematics or Biology (PCM/PCB) & English. The fifth subject can be indicative as per applicant’s choice.). The board can be any recognized board in India or an equivalent board if from a country outside India. In case of ISC board if an applicant has taken four subjects, aggregate of four subjects will be considered.
	        </li>
			<li>
			3. ≥60% marks in PCM in Class XII if opting for CSE or ECE streams, ≥60% marks in PCB or PCM or PCMB in Class XII if choosing BT and ≥60% marks in PCB in Class XII, if opting for Integrated MSc CS.
			</li>       
	        <li>
	    	4.Should be appearing in the board examination in March/April 2019 or should have the necessary mark sheets if you have appeared in 2017 or 2018. 
	    	</li>
			<li>
			5. Should have JEE Main/BITSAT/SAT/NEET/Any other State Engineering Entrance Examination’s rank/score otherwise applicant will be required to appear in the NIIT University Engineering Test (NUET).</li>
			<li>
			6. Applicants, who are appearing either for the Cambridge, IGCSE examination (A Level) or International Baccalaureate (IB) with a minimum of five subjects, including Physics, Chemistry and Mathematics and/or Biology, admission would be granted based on the predicted score.
			</li>
			<li>
			7. Applicant should have scored ≥ Grade 4 in IB Board or minimum grade C in Cambridge IGCSE Board.</li>
	    </ul>
	</div>
	<div class='formSection'>
		<ul>
	
			
				<li>
					<h3 class="upperCase">Preference</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Program:<span>*</span> </label>
				<div class='fieldBoxLarge'>

					<select validate="validateSelect"  onchange="showDependentStream(this.value)"  required="true"   minlength ="1" maxlength  = "100" caption="Programme"  name='NIITBTECH_programme' id = 'NIITBTECH_programme' value = >
						<option value = ''>Select</option>
						<option value = '1' <?php if ($NIITBTECH_programme == '1') echo "selected" ?> >4 year B.Tech.</option>
						<option value = '2'  <?php if ($NIITBTECH_programme == '2') echo "selected" ?>>5 year Integrated M.Tech.</option>
						<!-- <option value = '11' <?php if ($NIITBTECH_programme == '11') echo "selected" ?> >4 year M.Sc (CS)</option>	 -->
						<!-- <option value = '14'  <?php if ($NIITBTECH_programme == '14') echo "selected" ?>>4 year Integrated MBA</option> -->
					</select>
				
				<div style='display:none'>
					<div class='errorMsg' id= 'NIITBTECH_programme_error'>						
					</div>
				</div>
				</div>
				</div>
			</li>

			<li style="display: none;">
				<div class='additionalInfoLeftCol'>
				<label>Dual Degree: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_Isdual' id='NIITBTECH_Isdual'  validate="validateInteger"    caption="Dual degree"   minlength="1"   maxlength="1"      value=''   />
				<?php if(isset($NIITBTECH_Isdual) && $NIITBTECH_Isdual!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_Isdual").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_Isdual );  ?>";
				      document.getElementById("NIITBTECH_Isdual").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_Isdual_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label id = 'streamShow' >Stream:<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<div id = 'SelectStream' style="display: block;">
				<select validate="validateSelect"  required="true"   caption="Stream" minlength = 1 maxlength = 40 name='NIITBTECH_stream' id='NIITBTECH_stream'   value=''>
					
				</select>
				</div>
				

				<?php if(isset($NIITBTECH_stream) && $NIITBTECH_stream!=""){ ?>
				  <script>			     
				     	showDependentStream(<?php echo $NIITBTECH_programme ?>);
				  </script>
				<?php } ?> 
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_stream_error'></div></div>
				</div>
				</div>
			</li>

			<li>
					<h3 class="upperCase">More Educational Details</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Xth Marks (Obtained):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_10Marks' id='NIITBTECH_10Marks'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIITBTECH_10Marks) && $NIITBTECH_10Marks!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_10Marks").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_10Marks );  ?>";
				      document.getElementById("NIITBTECH_10Marks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_10Marks_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Xth Marks (Total):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_10MarksTotal' id='NIITBTECH_10MarksTotal'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIITBTECH_10MarksTotal) && $NIITBTECH_10MarksTotal!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_10MarksTotal").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_10MarksTotal );  ?>";
				      document.getElementById("NIITBTECH_10MarksTotal").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_10MarksTotal_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth Marks (Obtained):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_12Marks' id='NIITBTECH_12Marks'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIITBTECH_12Marks) && $NIITBTECH_12Marks!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_12Marks").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_12Marks );  ?>";
				      document.getElementById("NIITBTECH_12Marks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_12Marks_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth Marks (Total):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_12MarksTotal' id='NIITBTECH_12MarksTotal'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIITBTECH_12MarksTotal) && $NIITBTECH_12MarksTotal!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_12MarksTotal").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_12MarksTotal );  ?>";
				      document.getElementById("NIITBTECH_12MarksTotal").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_12MarksTotal_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth PCM/PCB Marks (Obtained):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_PCMBMarks' id='NIITBTECH_PCMBMarks'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIITBTECH_PCMBMarks) && $NIITBTECH_PCMBMarks!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_PCMBMarks").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_PCMBMarks );  ?>";
				      document.getElementById("NIITBTECH_PCMBMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_PCMBMarks_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth PCM/PCB Marks (Total):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_PCMBMarksTotal' id='NIITBTECH_PCMBMarksTotal'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIITBTECH_PCMBMarksTotal) && $NIITBTECH_PCMBMarksTotal!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_PCMBMarksTotal").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_PCMBMarksTotal );  ?>";
				      document.getElementById("NIITBTECH_PCMBMarksTotal").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_PCMBMarksTotal_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">XIIth School Information</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>School's Address:<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<textarea  name='NIITBTECH_12SchoolAddress' id='NIITBTECH_12SchoolAddress'  validate="validateStr"   required="true"   caption="Address"   minlength="0"   maxlength="150"      value='' >  </textarea>
				<?php if(isset($NIITBTECH_12SchoolAddress) && $NIITBTECH_12SchoolAddress!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_12SchoolAddress").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_12SchoolAddress );  ?>";
				      document.getElementById("NIITBTECH_12SchoolAddress").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_12SchoolAddress_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>School Branch:<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_12branch' id='NIITBTECH_12branch'  validate="validateStr"   required="true"   caption="Branch"   minlength="0"   maxlength="50"      value=''   />
				<?php if(isset($NIITBTECH_12branch) && $NIITBTECH_12branch!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_12branch").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_12branch );  ?>";
				      document.getElementById("NIITBTECH_12branch").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_12branch_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>School code: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIITBTECH_schoolCode' id='NIITBTECH_schoolCode'  validate="validateStr"   caption="School Code"   minlength="0"   maxlength="20"      value=''   />
				<?php if(isset($NIITBTECH_schoolCode) && $NIITBTECH_schoolCode!=""){ ?>
				  <script>
				      document.getElementById("NIITBTECH_schoolCode").value = "<?php echo str_replace("\n", '\n', $NIITBTECH_schoolCode );  ?>";
				      document.getElementById("NIITBTECH_schoolCode").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIITBTECH_schoolCode_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3>Declaration</h3>
				<div class='fieldBoxLarge' style="width: 100% ; color: #666666; font-style: italic;">
					<ul>
						<li>
							I have gone through and understood the admission and fees related policies of NIIT University (also available at <a href="www.niituniversity.in">www.niituniversity.in</a>). I agree that I shall abide by all the terms and conditions laid therein. I declare that all information in my application form (in all the parts and annexures) is complete , factually correct, and honestly presented. I agree to the condition that if any information or statement is found to be incorrect, my admission will stand automatically cancelled, and other suitable action will be taken by the university, if deemed necessary by the university.
						</li>
					</ul>
				</div>

				<div class="additionalInfoLeftCol">

					<label style="width: 100%; text-align: left;">
						
						<input type='checkbox' validate='validateChecked'  required="true" caption ='Please accept the terms'   name='NIITBTECH_Terms[]' id='NIITBTECH_Terms'   value='1'  checked  ></input>
							I agree to the above terms and conditions
						<span ></span>&nbsp;&nbsp;
				
				<?php if(isset($NIITBTECH_Terms) && $NIITBTECH_Terms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["NIITBTECH_Terms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$NIITBTECH_Terms);
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
						<div class='errorMsg' id= 'NIITBTECH_Terms_error'>
							
						</div>
					</div>
				</label>
				</div>
			</li>

		</ul>
	</div>
</div>

<style>
  	#appsFormWrapper .fieldBoxLarge select{width: 214px;background: #fff;border: 1px solid #ccc;padding: 4px 10px}
  	#appsFormWrapper .fieldBoxLarge input[type='text']{border-radius: 2px;width: 73%;}
  	#appsFormWrapper .fieldBoxSmall input[type='text']{padding: 5px 2px;border-radius: 2px;}
  	#appsFormWrapper .formChildWrapper h3.upperCase, .formSection ul li h3{background: #f1f1f1;margin: 0 -10px;padding: 10px 15px;}
  	#appsFormWrapper h3.upperCase{    padding: 10px 10px;
    background: #f1f1f1;
    margin: 0 -10px;}
    .additionalInfoLeftCol label span{color: red;}
    .additionalInfoLeftCol label span{color: red;}
    .errorMsg{pointer-events: none;}
    .fieldBoxLarge input[type="radio"], .fieldBoxLarge input[type="checkbox"] {
    position: relative;top: 2px;}
  </style>
