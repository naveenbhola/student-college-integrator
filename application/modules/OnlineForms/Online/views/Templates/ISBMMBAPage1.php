<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RollNumberAdditional",key+"PercentileAdditional",key+"RankAdditional");
	if(obj && $(key)){
	      if( obj.checked == false ){
		    $(key).style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key).style.display = '';
		    //Set the required paramters when any Exam is shown
		    setExamFields(objects1);
	      }
	}
  }

  function setExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
		if(document.getElementById(objectsArr[i])){
			document.getElementById(objectsArr[i]).setAttribute('required','true');
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }

  function resetExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
		if(document.getElementById(objectsArr[i])){
			document.getElementById(objectsArr[i]).removeAttribute('required');
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }
  
  
//  function changeISBMCourse(obj,reset){
//	if(typeof(reset) == 'undefined'){
//		reset = 1;
//	}
//	$('campusType').style.display = '';
//	if(reset){
//		var elements = document.getElementsByName("ISBM_campus[]");
//		$('ISBM_program').value = '';
//		for(var i=0;elements[i];i++){
//			elements[i].checked = false;
//		}
//    }
//	if(obj.value == "PGDM Colleges (Bangalore, Pune)"){
//		$('programType').style.display = 'none';
//		$('ISBM_program_error').innerHTML = '';
//		$('ISBM_program_error').parentNode.style.display = 'none';
//		$('ISBM_program').removeAttribute('required');
//		$('campusType3').style.display = '';
//		$('campusType4').style.display = '';
//		$('campusType0').style.display = 'none';
//		$('campusType1').style.display = 'none';
//		$('campusType2').style.display = 'none';
//		
//	}else{
//		$('programType').style.display = '';
//		$('ISBM_program_error').innerHTML = '';
//		$('ISBM_program_error').parentNode.style.display = 'none';
//		$('ISBM_program').setAttribute('required','true');
//		$('campusType0').style.display = '';
//		$('campusType1').style.display = '';
//		$('campusType2').style.display = '';
//		$('campusType3').style.display = 'none';
//		$('campusType4').style.display = 'none';
//	}
//  }

function disabilityDetail(obj){
 
	if (obj.value == "Yes") {
	  $('mentionDisability').style.display = '';
	  document.getElementById("ISBM_DisabilityDetail").setAttribute('required','true');
	}
	else
	{
	  $('mentionDisability').style.display = 'none';

	}
  
}

</script>

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class=upperCase'>Personal Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Height: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ISBM_height' id='ISBM_height'  validate="validateFloat"   required="true"   caption="height"   minlength="1"   maxlength="10"     tip="Enter your height in Feet"   value=''   />
				<?php if(isset($ISBM_height) && $ISBM_height!=""){ ?>
				  <script>
				      document.getElementById("ISBM_height").value = "<?php echo str_replace("\n", '\n', $ISBM_height );  ?>";
				      document.getElementById("ISBM_height").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_height_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Weight: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ISBM_weight' id='ISBM_weight'  validate="validateFloat"   required="true"   caption="weight"   minlength="2"   maxlength="10"     tip="Enter your Weight in Kilograms"   value=''   />
				<?php if(isset($ISBM_weight) && $ISBM_weight!=""){ ?>
				  <script>
				      document.getElementById("ISBM_weight").value = "<?php echo str_replace("\n", '\n', $ISBM_weight );  ?>";
				      document.getElementById("ISBM_weight").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_weight_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Any major ailment of sickness: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ISBM_ailment' id='ISBM_ailment'  validate="validateStr"  caption="ailment"   minlength="1"   maxlength="100" required='true'    tip="If you have any major ailment or sickness, let the institute know about it. If not, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($ISBM_ailment) && $ISBM_ailment!=""){ ?>
				  <script>
				      document.getElementById("ISBM_ailment").value = "<?php echo str_replace("\n", '\n', $ISBM_ailment); ?>";
				      document.getElementById("ISBM_ailment").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_ailment_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Blood group: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ISBM_bloodGroup' id='ISBM_bloodGroup'  validate="validateStr"   required="true"   caption="blood group"   minlength="1"   maxlength="20"     tip="Please enter your blood group. If you do not know your blood group, just enter <b>NA</b>"   value=''    allowNA = 'true' />
				<?php if(isset($ISBM_bloodGroup) && $ISBM_bloodGroup!=""){ ?>
				  <script>
				      document.getElementById("ISBM_bloodGroup").value = "<?php echo str_replace("\n", '\n', $ISBM_bloodGroup );  ?>";
				      document.getElementById("ISBM_bloodGroup").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_bloodGroup_error'></div></div>
				</div>
				</div	
			</li>
			
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Person with Disability: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' onchange="disabilityDetail(this);" validate="validateCheckedGroup" required="true" caption="yes or no" name='ISBM_Disability' id='ISBM_Disability0' value='Yes' title="Are you a person with disability?" onmouseover="showTipOnline('Please select yes,if you are a person with disability.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select yes,if you are a person with disability.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' onchange="disabilityDetail(this);" validate="validateCheckedGroup" required="true" caption="yes or no" name='ISBM_Disability' id='ISBM_Disability1' value='No' title="Are you a person with disability?" onmouseover="showTipOnline('Please select yes,if you are a person with disability.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select yes,if you are a person with disability.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($ISBM_Disability) && $ISBM_Disability!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["ISBM_Disability"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $ISBM_Disability;?>") {
						      radioObj[i].checked = true;
						      disabilityDetailObj = radioObj[i];
					      }
				      }
				      
				  </script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_Disability_error'></div></div>
				</div>
				</div>
				
				
				<div id= 'mentionDisability' class='additionalInfoRightCol' style="display:none">
				<label>If yes, Mention:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ISBM_DisabilityDetail' id='ISBM_DisabilityDetail' tip="Fill the details of your disability." validate='validateStr' title="Details of Disability" caption="Disability" minlength="2" maxlength="30" value='' />
				<?php if(isset($ISBM_DisabilityDetail) && $ISBM_DisabilityDetail!="") { ?>
				<script>
				    document.getElementById("ISBM_DisabilityDetail").value = "<?php echo str_replace("\n",'\n', $ISBM_DisabilityDetail); ?>";
				    document.getElementById("ISBM_DisabilityDetail").style.color="";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_DisabilityDetail_error'></div></div>
				</div>
				</div>
			</li>

      		
	
	
			
				
<!--		<li>		<div class='additionalInfoLeftCol' style="width:700px">
				<label>Select Campus Type: </label>
				<div class='fieldBoxLarge'  style="width:350px">
				<input type='radio' onchange="changeISBMCourse(this);"  validate="validateCheckedGroup"   required="true"   caption="campus type"  name='ISBM_campusType' id='ISBM_campusType0'   value='PGDM Colleges (Bangalore, Pune)'   title="Select Campus Type"   onmouseover="showTipOnline('Please select the campus types. ISB&M has two different campus types distinguished by their approval status.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the campus types. ISB&M has two different campus types distinguished by their approval status.',this);" onmouseout="hidetip();" >PGDM Colleges (Bangalore, Pune)</span>&nbsp;&nbsp;
				<br/><input type='radio'  onchange="changeISBMCourse(this);"  validate="validateCheckedGroup"   required="true"   caption="campus type"  name='ISBM_campusType' id='ISBM_campusType1'   value='PGDBM Colleges (Gurgaon, Pune, Kolkata)'    title="Select Campus Type"   onmouseover="showTipOnline('Please select the campus types. ISB&M has two different campus types distinguished by their approval status.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the campus types. ISB&M has two different campus types distinguished by their approval status.',this);" onmouseout="hidetip();" >PGDBM Colleges (Gurgaon, Pune, Kolkata)</span>&nbsp;&nbsp;
				<?php if(isset($ISBM_campusType) && $ISBM_campusType!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["ISBM_campusType"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $ISBM_campusType;?>") {
						      radioObj[i].checked = true;
							  changeISBMCourseObj = radioObj[i];
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_campusType_error'></div></div>
				</div>
				</div>
			</li>

			<li id="campusType" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Select the Campus(es)/Course(s) of your choice: </label>
				<div class='fieldBoxLarge'>
				<div id="campusType0"><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the campus of your choice"   name='ISBM_campus[]' id='ISBM_campus0'   value='ISB&M Pune(Viman Nagar)'  title="Select the campus of your choice"   onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" >ISB&M Pune(Viman Nagar)</span>&nbsp;&nbsp;
				</div><div id="campusType1"><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the campus of your choice"   name='ISBM_campus[]' id='ISBM_campus1'   value='ISB&M Kolkata'    title="Select the campus of your choice"   onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" >ISB&M Kolkata</span>&nbsp;&nbsp;
				</div><div id="campusType2"><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the campus of your choice"   name='ISBM_campus[]' id='ISBM_campus2'   value='ISB&M Gurgaon'    title="Select the campus of your choice"   onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" >ISB&M Gurgaon</span>&nbsp;&nbsp;
				</div><div id="campusType3"><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the campus of your choice"   name='ISBM_campus[]' id='ISBM_campus3'   value='ISB&M Bangalore'    title="Select the campus of your choice"   onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" >ISB&M Bangalore</span>&nbsp;&nbsp;
				</div><div id="campusType4"><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the campus of your choice"   name='ISBM_campus[]' id='ISBM_campus4'   value='ISB&M Pune(Nande)'    title="Select the campus of your choice"   onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the campuses you are applying to. You will be selected to the campuses that you have applied to. Multiple campuses can be selected.',this);" onmouseout="hidetip();" >ISB&M Pune(Nande)</span>&nbsp;&nbsp;
				</div>
				

				<?php if(isset($ISBM_campus) && $ISBM_campus!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["ISBM_campus[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$ISBM_campus);
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
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_campus_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<b>Note:</b> Fee for one course is Rs.1100, for two courses is Rs.1300 and for three courses is Rs.1500.
				</div>
			</li>

			<li id="programType" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Select Programme of your choice: </label>
				<div class='fieldBoxLarge'>
				<select name='ISBM_program' id='ISBM_program' maxlength="1500" minlength="1" caption="programme" validate="validateSelect" tip="Select the program that you are interested in. Not all programs are available at all the campuses. The Supply Chain and Operations Management Programme is only available for Engineering graduates in Pune and Gurgaon campuses."   title="Select Programme of your choice"    onmouseover="showTipOnline('Select the program that you are interested in. Not all programs are available at all the campuses. The Supply Chain and Operations Management Programme is only available for Engineering graduates in Pune and Gurgaon campuses.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Business Management Programme'>Business Management Programme</option><option value='Human Resource Management Programme' >Human Resource Management Programme</option><option value='Supply Chain and Operations Management Programme (Only at Pune and Gurgaon campuses)' >Supply Chain and Operations Management Programme (Only at Pune and Gurgaon campuses)</option></select>
				<?php if(isset($ISBM_program) && $ISBM_program!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ISBM_program"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ISBM_program;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_program_error'></div></div>
				</div>
				</div>
			</li>-->
			
			<li>
				<h3 class='upperCase'>Specialization</h3>
				<div class="additionalInfoLeftCol" style="width:930px;">
				<label>Specialization:</label>
				<div class="fieldBoxLarge" style="width:620px;">
				<div><input type='checkbox' onclick="" validate="validateCheckedGroup" required="true" caption="specialization" name='ISBM_specialization[]' id='ISBM_specialization0' value="Marketing" onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.', this);" onmouseout="hidetip();" >Marketing</span>&nbsp;&nbsp;
				</div><div><input type='checkbox' onclick="" validate="validateCheckedGroup" required="true" caption="specialization" name='ISBM_specialization[]' id='ISBM_specialization1' value="Finance" onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.',this);" onmouseout="hidetip();" >Finance</span>&nbsp;&nbsp;
				</div><div><input type='checkbox' onclick="" validate="validateCheckedGroup" required="true" caption="specialization" name='ISBM_specialization[]' id='ISBM_specialization2' value="Human Resource (HR)" onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for your primary career interest',this);" onmouseout="hidetip();" >Human Resource(HR)</span>&nbsp;&nbsp;
				</div><div><input type='checkbox' onclick="" validate="validateCheckedGroup" required="true" caption="specialization" name='ISBM_specialization[]' id='ISBM_specialization3' value="Insurance & Risk Management (IRM)" onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.',this);" onmouseout="hidetip();" >Insurance & Risk Management(IRM)</span>&nbsp;&nbsp;
				</div><div><input type='checkbox' onclick="" validate="validateCheckedGroup" required="true" caption="specialization" name='ISBM_specialization[]' id='ISBM_specialization4' value="Supply Chain & Operations Management (SCOM)" onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for your primary career interest.',this);" onmouseout="hidetip();" >Supply Chain & Operations Management(SCOM)</span>&nbsp;&nbsp;
				</div>
				<?php if(isset($ISBM_specialization) && $ISBM_specialization!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["ISBM_specialization[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$ISBM_specialization);
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
				<div style='display:none'><div class='errorMsg' id='ISBM_specialization_error'></div></div>
				</div>
				</div>
			</li>
			
			
			
			<li>
				<h3 class='upperCase'>Educational Details(others)</h3>
				<div class='additionalInfoLeftCol'>
				<label>10th Std.Subjects:</label>
				<div class='fieldBoxLarge'>
				<input class='textBoxLarge' type='text' name='ISBM_subject10' id='ISBM_subject10' validate="validateStr" caption="10th standard subjects" minlength='4' maxlength='30' required="true" tip="Please enter your class 10th subjects." value='' />
				<?php if(isset($ISBM_subject10) && $ISBM_subject10!="") { ?>
				<script>
				    document.getElementById("ISBM_subject10").value = "<?php echo str_replace('\n',"\n",  $ISBM_subject10); ?>";
				    document.getElementById("ISBM_subject10").style.color = "";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_subject10_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>10th Std. Class/Grade:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ISBM_rank10' id='ISBM_rank10' validate="validateInteger" caption="class/grade in 10th standard" minlenth='1' maxlength='10' required="true" tip="Please enter your class 10th class/grade." value='' />
				<?php if(isset($ISBM_rank10) && $ISBM_rank10!="") { ?>
				<script>
				    document.getElementById("ISBM_rank10").value = "<?php echo str_replace("\n",'\n', $ISBM_rank10); ?>";
				    document.getElementById("ISBM_rank10").style.color = "";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_rank10_error'></div></div>
				</div>
				</div>
			      
			</li>
			
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>12th Std. Subjects:</label>
				<div class='fieldBoxLarge'>
				<input class='textBoxLarge' type='text' name="ISBM_subject12" id="ISBM_subject12" validate="validateStr" caption="12th standard subjects" minlength='4' maxlength='30' required="true" tip="Please enter your class 12th subjects." value='' />
				<?php if(isset($ISBM_subject12) && $ISBM_subject12!="") { ?>
				<script>
				    document.getElementById("ISBM_subject12").value = "<?php echo str_replace('\n',"\n",  $ISBM_subject12); ?>";
				    document.getElementById("ISBM_subject12").style.color = "";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_subject12_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>12th Std. Class/Grade:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name="ISBM_rank12" id="ISBM_rank12" validate="validateInteger" caption="class/grade in 12th standard" minlenth='1' maxlength='10' required="true" tip="Please enter your class 12th class/grade." value='' />
				<?php if(isset($ISBM_rank12) && $ISBM_rank12!="") { ?>
				<script>
				    document.getElementById("ISBM_rank12").value = "<?php echo str_replace("\n",'\n', $ISBM_rank12); ?>";
				    document.getElementById("ISBM_rank12").style.color = "";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_rank12_error'></div></div>
				</div>
				</div>
				
			</li>
			
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Graduation Subjects:</label>
				<div class='fieldBoxLarge'>
				<input class='textBoxLarge' type='text' name="ISBM_subjectGrad" id="ISBM_subjectGrad" validate="validateStr" caption="graduation subjects" minlength='1' maxlength='30' required="true" tip="Please enter your graduation subjects." value='' />
				<?php if(isset($ISBM_subjectGrad) && $ISBM_subjectGrad!="") { ?>
				<script>
				    document.getElementById("ISBM_subjectGrad").value = "<?php echo str_replace('\n',"\n",  $ISBM_subjectGrad); ?>";
				    document.getElementById("ISBM_subjectGrad").style.color = "";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_subjectGrad_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Graduation Class/Grade:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name="ISBM_rankGrad" id="ISBM_rankGrad" validate="validateInteger" caption="class/grade in graduation" minlenth='1' maxlength='10' required="true" tip="Please enter your graduation class/grade." value=''/>
				<?php if(isset($ISBM_rankGrad) && $ISBM_rankGrad!="") { ?>
				<script>
				    document.getElementById("ISBM_rankGrad").value = "<?php echo str_replace("\n",'\n', $ISBM_rankGrad); ?>";
				    document.getElementById("ISBM_rankGrad").style.color = "";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_rankGrad_error'></div></div>
				</div>
				</div>
				
			</li>
			
			
			<?php
			$graduationCourseName = 'Graduation';
			$graduationYear = '';
			$otherCourses = array();
			$otherCourseYears = array();
	
			if(is_array($educationDetails)) {
			    foreach($educationDetails as $educationDetail) {
				if($educationDetail['value']) {
				    if($educationDetail['fieldName'] == 'graduationExaminationName') {
					$graduationCourseName = $educationDetail['value'];
				      }
				    else if($educationDetail['fieldName'] == 'graduationYear') {
				    	$graduationYear = $educationDetail['value'];
				      }
				    else {
					  for($i=1;$i<=4;$i++) {
						if($educationDetail['fieldName'] == 'graduationExaminationName_mul_'.$i) {
							$otherCourses[$i] = $educationDetail['value'];
						}
						else if($educationDetail['fieldName'] == 'graduationYear_mul_'.$i) {
							$otherCourseYears[$i] = $educationDetail['value'];
						}
					   }
					}	
				}
			  }
			}
			
			$i=0;
			    if(count($otherCourses)>0) { 
				    foreach($otherCourses as $otherCourseId => $otherCourseName) {
					    $pgCheck = 'otherCoursePGCheck_mul_'.$otherCourseId;
					    $pgCheckVal = $$pgCheck;
					    $soa = 'otherCourseSoa_mul_'.$otherCourseId;
					    $soaVal = $$soa;
					    $Subjects = 'otherCourseSubjects_mul_'.$otherCourseId;
					    $SubjectsVal = $$Subjects;
					    $gradePg = 'otherCourseGradePg_mul_'.$otherCourseId;
					    $gradePgVal = $$gradePg;
					    
					    $i++;
    
			?>
			    
			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $otherCourseName;?>Subjects: </label>
				<div class='fieldBoxLarge'>
				<input class='textBoxLarge' type='text' name='<?php echo $Subjects;?>' id='<?php echo $Subjects;?>'    validate="validateStr"   required="true"   caption="<?php echo $otherCourseName;?> subjects"   minlength="1"   maxlength="50"          tip=" Please enter your <?=html_escape($otherCourseName);?> subjects"   value=''   allowNA='yes'/>
				<?php if(isset($SubjectsVal) && $SubjectsVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $Subjects;?>").value = "<?php echo str_replace("\n", '\n', $SubjectsVal );  ?>";
				      document.getElementById("<?php echo $Subjects;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $Subjects;?>_error'></div></div>
				</div>
				</div>
				
				<div class="additionalInfoRightCol">
				<label style='font-weight:normal'><?php echo $otherCourseName;?> Class/Grade:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $gradePg;?>' id='<?php echo $gradePg;?>' tip="Please enter your <?=html_escape($otherCourseName);?> class/grade." required="true" validate="validateInteger" minlength="1" maxlength="5"  caption='Class/Grade'/>
				<?php if(isset($gradePgVal) && $gradePgVal!=""){ ?>
				<script>
				      document.getElementById("<?php echo $gradePg;?>").value = "<?php echo str_replace("\n", '\n', $gradePgVal );  ?>";
				</script>	
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id='<?php echo $gradePg;?>_error'></div></div>
				</div>
				</div>
			</li>
			<?php } } ?>
			<?php endif;?>
			
			<li>
				<h3 class='upperCase'>TESTS</h3>
				<div class='additionalInfoLeftCol' style="width:800px">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:400px">
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='ISBM_testNames[]' id='ISBM_testNames0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='ISBM_testNames[]' id='ISBM_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input type='checkbox' onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='ISBM_testNames[]' id='ISBM_testNames2'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='ISBM_testNames[]' id='ISBM_testNames3'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='ISBM_testNames[]' id='ISBM_testNames4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				
				<?php if(isset($ISBM_testNames) && $ISBM_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["ISBM_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$ISBM_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_testNames_error'></div></div>
				</div>
				</div>
			</li>

			
		
			<li id="cat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   required="true"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''  allowNA="true" />
				<?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
				      document.getElementById("catRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile  in the exam. If you don't know your percentile, enter NA."   value=''   allowNA="true"  />
				<?php if(isset($catPercentileAdditional) && $catPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("catPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $catPercentileAdditional );  ?>";
				      document.getElementById("catPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="mat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>MAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'   required="true"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
				<?php if(isset($matRollNumberAdditional) && $matRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("matRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $matRollNumberAdditional );  ?>";
				      document.getElementById("matRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matRollNumberAdditional_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>MAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile  in the exam. If you don't know your percentile, enter NA."   value=''   allowNA="true"  />
				<?php if(isset($matPercentileAdditional) && $matPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("matPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $matPercentileAdditional );  ?>";
				      document.getElementById("matPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="xat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>XAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   required="true"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
				<?php if(isset($xatRollNumberAdditional) && $xatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAdditional );  ?>";
				      document.getElementById("xatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatRollNumberAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>XAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'   required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile  in the exam. If you don't know your percentile, enter NA."   value=''   allowNA="true"  />
				<?php if(isset($xatPercentileAdditional) && $xatPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $xatPercentileAdditional );  ?>";
				      document.getElementById("xatPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="atma" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>ATMA Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
				<?php if(isset($atmaRollNumberAdditional) && $atmaRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $atmaRollNumberAdditional );  ?>";
				      document.getElementById("atmaRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaRollNumberAdditional_error'></div></div>
				</div>
				</div>
			<div class='additionalInfoRightCol'>
				<label>ATMA Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'         validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile  in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''   allowNA="true"  />
				<?php if(isset($atmaPercentileAdditional) && $atmaPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $atmaPercentileAdditional );  ?>";
				      document.getElementById("atmaPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaPercentileAdditional_error'></div></div>
				</div>
				</div>
				
			</li>

			<li id="cmat" style="display:none">
				
				<div class='additionalInfoLeftCol'>
				<label>CMAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
				<?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional );  ?>";
				      document.getElementById("cmatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>CMAT Rank: </label>
				<div class='fieldBoxLarge'>
				<input  allowNA="true"  type='text' name='cmatRankAdditional' id='cmatRankAdditional'   validate="validateFloat"    caption="Rank"   minlength="1"   maxlength="50"     tip="Mention your Rank in the exam. If you don't know your rank, enter <b>NA</b>."   value=''   />
				<?php if(isset($cmatRankAdditional) && $cmatRankAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRankAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRankAdditional );  ?>";
				      document.getElementById("cmatRankAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRankAdditional_error'></div></div>
				</div>
				</div>
			</li>
			

			
			<?php if($action != 'updateScore'):?>
			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value="<?php echo $gdpiLocation['city_id']; ?>"><?php echo $gdpiLocation['city_name']; ?></option>
				<?php endforeach; ?>
				</select>
				<?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=""){ ?>
				<script>
				var selObj = document.getElementById("preferredGDPILocation"); 
				var A= selObj.options, L= A.length;
				while(L){
					if (A[--L].value== "<?php echo $preferredGDPILocation;?>"){
					selObj.selectedIndex= L;
					L= 0;
					}
				}
				</script>
				  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'preferredGDPILocation_error'></div></div>
				</div>
			</li>
			<?php endif; ?>
			<li>
				<h3 class=upperCase'>About ISB&M</h3>
				<div class='additionalInfoLeftCol'>
				<label>How did you hear about ISB&M?: </label>
				<div class='fieldBoxLarge'>
				<div><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='ISBM_howHear[]' id='ISBM_howHear0'   value='Print Media'   title="How did you hear about ISB&M?"   onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" >Print Media</span>&nbsp;&nbsp;
				</div><div><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='ISBM_howHear[]' id='ISBM_howHear1'   value='Friends'    title="How did you hear about ISB&M?"   onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" >Friends</span>&nbsp;&nbsp;
				</div><div><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='ISBM_howHear[]' id='ISBM_howHear2'   value='Coaching Center'    title="How did you hear about ISB&M?"   onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" >Coaching Center</span>&nbsp;&nbsp;
				</div><div><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='ISBM_howHear[]' id='ISBM_howHear3'   value='Shiksha.com'    title="How did you hear about ISB&M?"   onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" >Shiksha.com</span>&nbsp;&nbsp;
				</div><div><input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='ISBM_howHear[]' id='ISBM_howHear4'   value='Other Sources'    title="How did you hear about ISB&M?"   onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select how did you hear about ISB&M, if you are unsure select other sources.',this);" onmouseout="hidetip();" >Other Sources</span>&nbsp;&nbsp;
				</div>
				<?php if(isset($ISBM_howHear) && $ISBM_howHear!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["ISBM_howHear[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$ISBM_howHear);
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
				
				<div style='display:none'><div class='errorMsg' id= 'ISBM_howHear_error'></div></div>
				</div>
				</div>
			
			      <div class='additionalInfoRightCol' style="width:400px;">
			      <label>Why do you think ISB&M is the best option?:</label>
			      <div class='fieldBoxLarge' style="width: 200px;">
			      <textarea  style="width:100%; height:100px;" type='text' name="ISBM_BestOption" id="ISBM_BestOption" validate="validateStr" minlength='1' maxlength='500' caption="reason for ISBM as the best option" required="true" tip="Why ISBM is the best option for you?" value=''></textarea>
				<?php if(isset($ISBM_BestOption) && $ISBM_BestOption != "") { ?>
				<script>
				      document.getElementById("ISBM_BestOption").value = "<?php echo str_replace("/n",'/n', $ISBM_BestOption); ?>";
				      document.getElementById("ISBM_BestOption").style.color = "";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_BestOption_error'></div></div>
				</div>
				</div>

			</li>
			
			
			<li>
			      <h3 class='uppercase'>Work Experience</h3>
			      <div class='additionalInfoLeftCol'>
			      <label>What are your career growth expectations?</label>
			      <div class='fieldBoxLarge'>
			      <textarea type='text' name="ISBM_careerExpect" id="ISBM_careerExpect" validate="validateStr" required="true" caption="career growth expectation" minlength='1' maxlength='500' tip="Please enter your career growth expectations." value=''></textarea>
			      <?php if(isset($ISBM_careerExpect) &&  $ISBM_careerExpect!='') { ?>
			      <script>
				    document.getElementById("ISBM_careerExpect").value ="<?php echo str_replace("/n", '/n', $ISBM_careerExpect);?>";
				    document.getElementById("ISBM_careerExpect").style.color='';
			      </script>
			      <?php } ?>
			      <div style='display:none'><div class='errorMsg' id='ISBM_careerExpect_error'></div></div>
			      </div>
			      </div>
			</li>
			
			
			<li>
				<h3 class='upperCase'>Career Goals</h3>
				<div class='additionalInfoLeftCol'>
				<label>What are your long-term objectives in life?:</label>
				<div class='fieldBoxLarge' style="width: 200px;">
				<textarea style="width: 100%; height:100px;" name="ISBM_longTermGoal" id="ISBM_longTermGoal" validate="validateStr" required="true" minlength='1' maxlength='500' caption="long-term objectives" tip="Please describe your long term objectives in life." value=''></textarea>
				<?php if(isset($ISBM_longTermGoal) && $ISBM_longTermGoal !="") { ?>
				<script>
				      document.getElementById("ISBM_longTermGoal").value = "<?php echo str_replace("/n",'/n', $ISBM_longTermGoal); ?>";
				      document.getElementById("ISBM_longTermGoal").style.color="";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_longTermGoal_error'></div></div>
				</div>
				</div>
				
				
				<div class='additionalInfoRightCol' style="width:400px;">
				<label>Where do you see yourself five years from now?:</label>
				<div class='fieldBoxLarge' style="width: 200px;">
				<textarea style="width: 100%; height:100px;" name="ISBM_fiveYearVision" id="ISBM_fiveYearVision" validate="validateStr" required="true" minlength='1' maxlength='500' caption="five years vision" tip="Please describe where do you see yourself five years from now." value=''></textarea>
				<?php if(isset($ISBM_fiveYearVision) && $ISBM_fiveYearVision !="") { ?>
				<script>
				      document.getElementById("ISBM_fiveYearVision").value = "<?php echo str_replace("/n",'/n', $ISBM_fiveYearVision); ?>";
				      document.getElementById("ISBM_fiveYearVision").style.color="";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_fiveYearVision_error'></div></div>
				</div>
				</div>
				
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>What qualities do you have which will make you a committed and responsible professional in corporate field?:</label>
				<div class='fieldBoxLarge' style="width: 200px;">
				<textarea style="width: 100%; height:100px;" name="ISBM_qualities" id="ISBM_qualities" validate="validateStr" required="true" minlength='1' maxlength='500' caption="qualities do you possess" tip="Please describe what qualities you have which will make you a committed and responsible professional in corporate field." value=''></textarea>
				<?php if(isset($ISBM_qualities) && $ISBM_qualities !="") { ?>
				<script>
				      document.getElementById("ISBM_qualities").value = "<?php echo str_replace("/n",'/n', $ISBM_qualities); ?>";
				      document.getElementById("ISBM_qualities").style.color="";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_qualities_error'></div></div>
				</div>
				</div>
				
				
				<div class='additionalInfoRightCol' style="width:400px;">
				<label>Mention your strengths and weaknesses:</label>
				<div class='fieldBoxLarge' style="width: 200px;">
				<textarea style="width: 100%; height:100px;" name="ISBM_StrenghtWeakness" id="ISBM_StrenghtWeakness" validate="validateStr" required="true" minlength='1' maxlength='500' caption="strengths & weaknesses" tip="Please mention your strengths and weaknesses." value=''></textarea>
				<?php if(isset($ISBM_StrenghtWeakness) && $ISBM_StrenghtWeakness !="") { ?>
				<script>
				      document.getElementById("ISBM_StrenghtWeakness").value = "<?php echo str_replace("/n",'/n', $ISBM_StrenghtWeakness); ?>";
				      document.getElementById("ISBM_StrenghtWeakness").style.color="";
				</script>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'ISBM_StrenghtWeakness_error'></div></div>
				</div>
				</div>	
			</li>
			
			
			<li>
				<h3 class=upperCase'>Undertaking</h3>
				<label style="font-weight:normal; padding-top:0">Undertaking:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					<ul>
						<li>1) I hereby submit to the jurisdiction of the Pune court in the event of any dispute.I have carefully noted the rules and process of admission as given in the prospectus which I am required to follow and shall in matters of interpretation; accept the decision given by the Director in this respect as final and binding.</li>
						<li>2) I shall conduct myself as per the rules and norms of ISB&M, failing which I shall not approach the Director for any concession in this regard and shall be liable to be debarred from the Institute. Manual of Policy will be provided at the time of admission.</li>
						<li>3) I have also read, understood and accepted the code and conduct of the Institute and shall take note of all communication put from time to time.</li>
					</ul>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateCheckedGroup" checked   required="true"   caption="Please check to accept terms"   name='ISBM_agreeToTerms[]' id='ISBM_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ><span>&nbsp;&nbsp;
				<?php if(isset($ISBM_agreeToTerms) && $ISBM_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["ISBM_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$ISBM_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'ISBM_agreeToTerms_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php endif;?>

		</ul>
	</div>
</div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
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
	for(var j=0; j<5; j++){
		checkTestScore(document.getElementById('ISBM_testNames'+j));
	}
	//changeISBMCourse(changeISBMCourseObj,0);
	disabilityDetail(disabilityDetailObj);
  </script>
