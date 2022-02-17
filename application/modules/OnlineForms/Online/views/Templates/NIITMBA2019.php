<script >

	function showDependentStream(obj){
		var key = obj;
		if (key == ''){
			$("NIIT_stream").innerHTML = '<option selected value = "">Select</option>';
			return;
		}
		if(key == '14'){
			$("NIIT_stream").innerHTML = '<option value = "MG" <?php if ($NIIT_stream == "MG") echo "selected"; ?> >Management (MG)</option>';
			changeDual(0);
		}
		else if (key == '11'){
			$("NIIT_stream").innerHTML = '<option selected value ="">Select</option><option value = "CS" <?php if ($NIIT_stream == "CS") echo "selected"; ?> >Computer Science (SC)</option>';
			
			changeDual(0);
		}
		else{
			$("NIIT_stream").innerHTML = '<option selected value ="">Select</option><option value = "BT" <?php if ($NIIT_stream == "BT") echo "selected"; ?> >Biotechnology (BT)</option><option value = "CSE" <?php if ($NIIT_stream == "CSE") echo "selected"; ?>>Computer Science and Engineering (CSE)</option><option value = "ECE" <?php if ($NIIT_stream == "ECE") echo "selected"; ?>>Electronics and Communication Engineering (ECE)</option>';
			if (key == '2'){
				changeDual(1);
			}
			else{
				changeDual(0);
			}
		}
	}

	function changeDual(obj){
		dualObject = document.forms["OnlineForm"].elements["NIIT_Isdual"];
		dualObject.setAttribute("value",obj.toString());
	}

</script>
<div class='formChildWrapper'>
	<div id="instructions" style="font: normal 12px Arial, Helvetica, Sans Sarif; padding: 12px;">
	    <h3 style="margin: 0;background: transparent;padding: 0px;">Eligibility</h3>
	    <ul>
			<li>
			1) ≥ 50% marks or equivalent in Class X in five subjects (Compulsory subjects - English, Mathematics, Science and Social Studies. The fifth subject can be indicative as per applicant’s choice ). The board can be CBSE/ICSE/any other board in India or an equivalent board if from a country outside India
</li><li>
2) ≥ 50% marks in Class XII in any five subjects (Compulsory subject – English). The applicant could be from Science/ Commerce/ Humanities.
</li><li>
3) Should be appearing in the board examination in March/April 2019 or should have the necessary mark sheets if you have appeared in 2017 or 2018.
</li><li>
4) Applicant should have scored ≥ Grade 3 in IB Board or minimum grade D in Cambridge IGCSE Board
</li>	    </ul>
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

					<select validate="validateSelect"  onchange="showDependentStream(this.value)"  required="true"   minlength ="1" maxlength  = "100" caption="Programme"  name='NIIT_programme' id = 'NIIT_programme' value = >
						<!-- <option value = '1' <?php if ($NIIT_programme == '1') echo "selected" ?> >4 year B.Tech.</option>
						<option value = '2'  <?php if ($NIIT_programme == '2') echo "selected" ?>>5 year Integrated M.Tech.</option>
						<option value = '11' <?php if ($NIIT_programme == '11') echo "selected" ?> >4 year M.Sc (CS)</option> -->
						<option value = '14' selected>  4 year Integrated MBA</option>
					</select>
				
				<div style='display:none'>
					<div class='errorMsg' id= 'NIIT_programme_error'>						
					</div>
				</div>
				</div>
				</div>
			</li>

			<li style="display: none;">
				<div class='additionalInfoLeftCol'>
				<label>Dual Degree: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIIT_Isdual' id='NIIT_Isdual'  validate="validateInteger"    caption="Dual degree"   minlength="1"   maxlength="1"      value='0'   />
				<?php if(isset($NIIT_Isdual) && $NIIT_Isdual!=""){ ?>
				  <script>
				      document.getElementById("NIIT_Isdual").value = "<?php echo str_replace("\n", '\n', $NIIT_Isdual );  ?>";
				      document.getElementById("NIIT_Isdual").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_Isdual_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label id = 'streamShow' >Stream:<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<div id = 'SelectStream' style="display: block;">
				<select validate="validateSelect"  required="true"   caption="Stream" minlength = 1 maxlength = 40 name='NIIT_stream' id='NIIT_stream'   value=''>
					<option value = "MG" selected >Management (MG)</option>
				</select>
				</div>
				

				<?php if(isset($NIIT_stream) && $NIIT_stream!=""){ ?>
				  <script>			     
				     	showDependentStream(<?php echo $NIIT_programme ?>);
				  </script>
				<?php } ?> 
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_stream_error'></div></div>
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
				<input type='text' name='NIIT_10Marks' id='NIIT_10Marks'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIIT_10Marks) && $NIIT_10Marks!=""){ ?>
				  <script>
				      document.getElementById("NIIT_10Marks").value = "<?php echo str_replace("\n", '\n', $NIIT_10Marks );  ?>";
				      document.getElementById("NIIT_10Marks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_10Marks_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Xth Marks (Total):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIIT_10MarksTotal' id='NIIT_10MarksTotal'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIIT_10MarksTotal) && $NIIT_10MarksTotal!=""){ ?>
				  <script>
				      document.getElementById("NIIT_10MarksTotal").value = "<?php echo str_replace("\n", '\n', $NIIT_10MarksTotal );  ?>";
				      document.getElementById("NIIT_10MarksTotal").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_10MarksTotal_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth Marks (Obtained):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIIT_12Marks' id='NIIT_12Marks'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIIT_12Marks) && $NIIT_12Marks!=""){ ?>
				  <script>
				      document.getElementById("NIIT_12Marks").value = "<?php echo str_replace("\n", '\n', $NIIT_12Marks );  ?>";
				      document.getElementById("NIIT_12Marks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_12Marks_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth Marks (Total):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIIT_12MarksTotal' id='NIIT_12MarksTotal'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIIT_12MarksTotal) && $NIIT_12MarksTotal!=""){ ?>
				  <script>
				      document.getElementById("NIIT_12MarksTotal").value = "<?php echo str_replace("\n", '\n', $NIIT_12MarksTotal );  ?>";
				      document.getElementById("NIIT_12MarksTotal").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_12MarksTotal_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth PCM/PCB Marks (Obtained):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIIT_PCMBMarks' id='NIIT_PCMBMarks'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIIT_PCMBMarks) && $NIIT_PCMBMarks!=""){ ?>
				  <script>
				      document.getElementById("NIIT_PCMBMarks").value = "<?php echo str_replace("\n", '\n', $NIIT_PCMBMarks );  ?>";
				      document.getElementById("NIIT_PCMBMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_PCMBMarks_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth PCM/PCB Marks (Total):<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIIT_PCMBMarksTotal' id='NIIT_PCMBMarksTotal'  validate="validateInteger"   required="true"   caption="Marks"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($NIIT_PCMBMarksTotal) && $NIIT_PCMBMarksTotal!=""){ ?>
				  <script>
				      document.getElementById("NIIT_PCMBMarksTotal").value = "<?php echo str_replace("\n", '\n', $NIIT_PCMBMarksTotal );  ?>";
				      document.getElementById("NIIT_PCMBMarksTotal").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_PCMBMarksTotal_error'></div></div>
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
				<textarea  name='NIIT_12SchoolAddress' id='NIIT_12SchoolAddress'  validate="validateStr"   required="true"   caption="Address"   minlength="0"   maxlength="150"      value='' >  </textarea>
				<?php if(isset($NIIT_12SchoolAddress) && $NIIT_12SchoolAddress!=""){ ?>
				  <script>
				      document.getElementById("NIIT_12SchoolAddress").value = "<?php echo str_replace("\n", '\n', $NIIT_12SchoolAddress );  ?>";
				      document.getElementById("NIIT_12SchoolAddress").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_12SchoolAddress_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>School Branch:<span>*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIIT_12branch' id='NIIT_12branch'  validate="validateStr"   required="true"   caption="Branch"   minlength="0"   maxlength="50"      value=''   />
				<?php if(isset($NIIT_12branch) && $NIIT_12branch!=""){ ?>
				  <script>
				      document.getElementById("NIIT_12branch").value = "<?php echo str_replace("\n", '\n', $NIIT_12branch );  ?>";
				      document.getElementById("NIIT_12branch").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_12branch_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>School code: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIIT_schoolCode' id='NIIT_schoolCode'  validate="validateStr"  caption="School Code"   minlength="0"   maxlength="20"      value=''   />
				<?php if(isset($NIIT_schoolCode) && $NIIT_schoolCode!=""){ ?>
				  <script>
				      document.getElementById("NIIT_schoolCode").value = "<?php echo str_replace("\n", '\n', $NIIT_schoolCode );  ?>";
				      document.getElementById("NIIT_schoolCode").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIIT_schoolCode_error'></div></div>
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
						<input type='checkbox' validate='validateChecked'  required="true" caption ='Please accept the terms'   name='NIIT_Terms[]' id='NIIT_Terms'   value='1'  checked   onmouseout="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();">
							
						</input>I agree to the above terms and conditions
						<span onmouseout="showTipOnline('Please check to accept terms',this);"
						 onmouseout="hidetip()"></span>&nbsp;&nbsp;
				
				<?php if(isset($NIIT_Terms) && $NIIT_Terms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["NIIT_Terms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$NIIT_Terms);
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
							<div class='errorMsg' id= 'NIIT_Terms_error'>
								
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
    .additionalInfoRightCol label span{color: red;}
    .errorMsg{pointer-events: none;}
    .fieldBoxLarge input[type="radio"], .fieldBoxLarge input[type="checkbox"] {
    position: relative;top: 2px;}
  </style>
