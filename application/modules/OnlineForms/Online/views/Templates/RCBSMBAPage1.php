 <script>
	var CategoryDetailObj = [];
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	if(obj.value == "CAT"){
	var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	}
	if(obj.value == "CMATSept" || obj.value == "CMATFeb"){
	var objects1 = new Array("RCBS_"+key+"Rank","RCBS_"+key+"RollNumber","RCBS_"+key+"Percentile");
	}
	if (obj.value == "MATDec" || obj.value == "MATFeb"){
	var objects1 = new Array("RCBS_"+key+"FormNumber","RCBS_"+key+"RollNumber","RCBS_"+key+"Score","RCBS_"+key+"Percentile");
	}
	if(obj && $(key)){
	      if( obj.checked == false ){
		    $(key).style.display = 'none';
		    $(key+"1").style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key).style.display = '';
		    $(key+"1").style.display = '';
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
  
  function CategoryDetail(obj){
	//console.log(obj.value);
	//alert('dsfdsfdsf');
	$('BackwardClassDetail').style.display = 'none';
	$('PhysicalDetail').style.display = 'none';
	if (obj.value == "Other Backward Hindu") {
	  $('BackwardClassDetail').style.display = '';
	  $("RCBS_BackwardClassDetail").setAttribute('required','true');
	}
	if(obj.value == "Physically Challenged")
	{
		$('PhysicalDetail').style.display = '';
		$("RCBS_DisabilityDetail").setAttribute('required','true');
	}
}

function checkForMultipleCourse(obj){
	if (obj.value != '') {
	
		var rcbsArray = new Array();
		for(var i=1;i<=4;i++){
			if(i != parseInt(obj.id.replace("RCBS_course",""))){
				rcbsArray.push($('RCBS_course'+i).value);
			}
		}
		
		if(in_array(obj.value,rcbsArray)){
			$(obj.id+'_error').parentNode.style.display = '';
			$(obj.id+'_error').innerHTML = "<b>"+obj.value+"</b> is already selected at another preference. Please select another course."
			setTimeout(function(){
				obj.value = '';
			},300);
		}
	}	
	}
</script>
  
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updatescore'):?>
			<li>
				<h3 class='upperCase'>Course Selection</h3>
				
			</li>
				
					<!--<label>Rajagiri College of Social Sciences(Autonomous)</label>
					<input type='checkbox' validate='validateCheckedGroup' required='true' name="RCBS_course" id="RCBS_course0" value='MBA' onmouseover="showTipOnline('Tick the appropriate box for course selection.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for the course you want to apply.',this);" onmouseout="hidetip();" >MBA</span>&nbsp;&nbsp;>
					<input type='checkbox' validate='validateCheckedGroup' required='true' name="RCBS_course" id="RCBS_course1" value='MHRM' onmouseover="showTipOnline('Tick the appropriate box for course selection.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for the course you want to apply.',this);" onmouseout="hidetip();" >MHRM</span>&nbsp;&nbsp;>
					<label>Rajagiri Business School</label>
					<input type='checkbox' validate='validateCheckedGroup' required='true' name="RCBS_course" id="RCBS_course2" value='PGDM' onmouseover="showTipOnline('Tick the appropriate box for course selection.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for the course you want to apply.',this);" onmouseout="hidetip();" >PGDM</span>&nbsp;&nbsp;>
					<label>Rajagiri International Institute for Education and Research</label>
					<input type='checkbox' validate='validateCheckedGroup' required='true' name="RCBS_course" id="RCBS_course3" value='International MBA/Twinning MBA' onmouseover="showTipOnline('Tick the appropriate box for course selection.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box for the course you want to apply.',this);" onmouseout="hidetip();" >International MBA/Twinning MBA</span>&nbsp;&nbsp;>-->
				</li>List programmes (MBA / MHRM / PGDM / International MBA) applied for, in order of preference:</li>
				<div class="spacer10 clearFix"></div>
				<li>
					
					<div class='additionalInfoLeftCol' style="padding-left:306px;">
					<span>1:</span>
					<select name="RCBS_course1" id="RCBS_course1" onchange="checkForMultipleCourse(this);" required='true' onmouseover="showTipOnline('Select your 1st course preference.',this);" onmouseout='hidetip();' validate='validateSelect' caption='course preference'>
					<option value="">SELECT</option>
					<option value="MBA">MBA</option>
					<option value="MHRM">MHRM</option>
					<option value="PGDM">PGDM</option>
					<option value="International MBA/Twinning MBA">International MBA/Twinning MBA</option>
					</select>
					<?php if(isset($RCBS_course1) && $RCBS_course1!=""){ ?>
					<script>
					var selObj = document.getElementById("RCBS_course1"); 
					var A= selObj.options, L= A.length;
					while(L){
						if (A[--L].value== "<?php echo $RCBS_course1;?>"){
						selObj.selectedIndex= L;
						L= 0;
						}
					}
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_course1_error'></div></div>
				</div>	
			</li>
				
			<li>
				<div class='additionalInfoLeftCol' style="padding-left:306px;">
					<span>2:</span>
					<select name="RCBS_course2" id="RCBS_course2" onchange="checkForMultipleCourse(this);" required='true' onmouseover="showTipOnline('Select your 2nd course preference.',this);" onmouseout='hidetip();' validate='validateSelect' caption='course preference'>
					<option value="">SELECT</option>
					<option value="MBA">MBA</option>
					<option value="MHRM">MHRM</option>
					<option value="PGDM">PGDM</option>
					<option value="International MBA/Twinning MBA">International MBA/Twinning MBA</option>
					</select>
					<?php if(isset($RCBS_course2) && $RCBS_course2!=""){ ?>
					<script>
					var selObj = document.getElementById("RCBS_course2"); 
					var A= selObj.options, L= A.length;
					while(L){
						if (A[--L].value== "<?php echo $RCBS_course2;?>"){
						selObj.selectedIndex= L;
						L= 0;
						}
					}
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_course2_error'></div></div>
				</div>		
			</li>
			
			<li>
				<div class='additionalInfoLeftCol' style="padding-left:306px;">
					<span>3:</span>
					<select name="RCBS_course3" id="RCBS_course3" onchange="checkForMultipleCourse(this);" required='true' onmouseover="showTipOnline('Select your 3rd course preference.',this);" onmouseout='hidetip();' validate='validateSelect' caption='course preference'>
					<option value="">SELECT</option>
					<option value="MBA">MBA</option>
					<option value="MHRM">MHRM</option>
					<option value="PGDM">PGDM</option>
					<option value="International MBA/Twinning MBA">International MBA/Twinning MBA</option>
					</select>
					<?php if(isset($RCBS_course3) && $RCBS_course3!=""){ ?>
					<script>
					var selObj = document.getElementById("RCBS_course3"); 
					var A= selObj.options, L= A.length;
					while(L){
						if (A[--L].value== "<?php echo $RCBS_course3;?>"){
						selObj.selectedIndex= L;
						L= 0;
						}
					}
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_course3_error'></div></div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol' style="padding-left:306px;">
					<span>4:</span>
					<select name="RCBS_course4" id="RCBS_course4" onchange="checkForMultipleCourse(this);" required='true' onmouseover="showTipOnline('Select your 4th course preference.',this);" onmouseout='hidetip();' validate='validateSelect' caption='course preference'>
					<option value="">SELECT</option>
					<option value="MBA">MBA</option>
					<option value="MHRM">MHRM</option>
					<option value="PGDM">PGDM</option>
					<option value="International MBA/Twinning MBA">International MBA/Twinning MBA</option>
					</select>
					<?php if(isset($RCBS_course4) && $RCBS_course4!=""){ ?>
					<script>
					var selObj = document.getElementById("RCBS_course4"); 
					var A= selObj.options, L= A.length;
					while(L){
						if (A[--L].value== "<?php echo $RCBS_course4;?>"){
						selObj.selectedIndex= L;
						L= 0;
						}
					}
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_course4_error'></div></div>
				</div>		
			</li>
			<?php endif;?>
			
			<li>
				<h3 class='upperCase'>Test Scores</h3>
				<div class='additionalInfoLeftCol' style="width:800px">
					<label>TESTS: </label>
					<div class='fieldBoxLarge' style="width:400px">
					<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='RCBS_testNames[]' id='RCBS_testNames0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the roll number,percentile and score.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
					<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='RCBS_testNames[]' id='RCBS_testNames1'   value='MATDec'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" >MAT December 2014</span>&nbsp;&nbsp;
					<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='RCBS_testNames[]' id='RCBS_testNames2'   value='MATFeb'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" >MAT February 2015</span>&nbsp;&nbsp;
					<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='RCBS_testNames[]' id='RCBS_testNames3'   value='CMATSept'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" >CMAT September 2014</span>&nbsp;&nbsp;
					<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='RCBS_testNames[]' id='RCBS_testNames4'   value='CMATFeb'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, percentile and score.',this);" onmouseout="hidetip();" >CMAT February 2015</span>&nbsp;&nbsp;
					<?php if(isset($RCBS_testNames) && $RCBS_testNames!=""){ ?>
					<script>
					    objCheckBoxes = document.forms["OnlineForm"].elements["RCBS_testNames[]"];
					    var countCheckBoxes = objCheckBoxes.length;
					    for(var i = 0; i < countCheckBoxes; i++){
						      objCheckBoxes[i].checked = false;
	
						      <?php $arr = explode(",",$RCBS_testNames);
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
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_testNames_error'></div></div>
					</div>
				</div>
			</li>
			
			<li id="cat" style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>CAT Registration Number: </label>
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
					<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="6"    tip="Mention your percentile  in the exam. If you don't know your percentile, enter NA."   value=''   allowNA="true"  />
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
			<li id="cat1" style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>CAT Score: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='catScoreAdditional' id='catScoreAdditional'   required="true"  validate="validateFloat"    caption="Score"   minlength="1"   maxlength="5"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''  allowNA="true" />
					<?php if(isset($catScoreAdditional) && $catScoreAdditional!=""){ ?>
					  <script>
					      document.getElementById("catScoreAdditional").value = "<?php echo str_replace("\n", '\n', $catScoreAdditional );  ?>";
						document.getElementById("catScoreAdditional").style.color = "";
					  </script>
					<?php } ?>
				
					<div style='display:none'><div class='errorMsg' id= 'catScoreAdditional_error'></div></div>
					</div>
				</div>
			</li>
			
			<li id="matdec" style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>MAT Dec. Roll Number: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_matdecRollNumber' id='RCBS_matdecRollNumber'   required="true"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_matdecRollNumber) && $RCBS_matdecRollNumber!=""){ ?>
					  <script>
					      document.getElementById("RCBS_matdecRollNumber").value = "<?php echo str_replace("\n", '\n', $RCBS_matdecRollNumber );  ?>";
					      document.getElementById("RCBS_matdecRollNumber").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_matdecRollNumber_error'></div></div>
					</div>
				</div>
		
				<div class='additionalInfoRightCol'>
					<label>MAT Dec. Percentile: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_matdecPercentile' id='RCBS_matdecPercentile'   required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="6"    tip="Mention your percentile  in the exam. If you don't know your percentile, enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_matdecPercentile) && $RCBS_matdecPercentile!=""){ ?>
					  <script>
					      document.getElementById("RCBS_matdecPercentile").value = "<?php echo str_replace("\n", '\n', $RCBS_matdecPercentile );  ?>";
					      document.getElementById("RCBS_matdecPercentile").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_matdecPercentile_error'></div></div>
					</div>
				</div>
			</li>
			<li id="matdec1" style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>MAT Dec. Form Number: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_matdecFormNumber' id='RCBS_matdecFormNumber'   required="true"  validate="validateStr"    caption="Form Number"   minlength="2"   maxlength="50"     tip="Mention your form number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_matdecFormNumber) && $RCBS_matdecFormNumber!=""){ ?>
					  <script>
					      document.getElementById("RCBS_matdecFormNumber").value = "<?php echo str_replace("\n", '\n', $RCBS_matdecFormNumber );  ?>";
					      document.getElementById("RCBS_matdecFormNumber").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_matdecFormNumber_error'></div></div>
					</div>
				</div>
		
				<div class='additionalInfoRightCol'>
					<label>MAT Dec. Composite Score: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_matdecScore' id='RCBS_matdecScore'   required="true"        validate="validateFloat"    caption="Score"   minlength="1"   maxlength="6"    tip="Mention your score in the exam. If you don't know your score, enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_matdecScore) && $RCBS_matdecScore !=""){ ?>
					  <script>
					      document.getElementById("RCBS_matdecScore").value = "<?php echo str_replace("\n", '\n', $RCBS_matdecScore );  ?>";
					      document.getElementById("RCBS_matdecScore").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_matdecScore_error'></div></div>
					</div>
				</div>
			</li>
			
			<li id="matfeb" style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>MAT Feb. Roll Number: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_matfebRollNumber' id='RCBS_matfebRollNumber'   required="true"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_matfebRollNumber) && $RCBS_matfebRollNumber!=""){ ?>
					  <script>
					      document.getElementById("RCBS_matfebRollNumber").value = "<?php echo str_replace("\n", '\n', $RCBS_matfebRollNumber );  ?>";
					      document.getElementById("RCBS_matfebRollNumber").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_matfebRollNumber_error'></div></div>
					</div>
				</div>
		
				<div class='additionalInfoRightCol'>
					<label>MAT Feb. Percentile: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_matfebPercentile' id='RCBS_matfebPercentile'   required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="6"    tip="Mention your percentile  in the exam. If you don't know your percentile, enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_matfebPercentile) && $RCBS_matfebPercentile!=""){ ?>
					  <script>
					      document.getElementById("RCBS_matfebPercentile").value = "<?php echo str_replace("\n", '\n', $RCBS_matfebPercentile );  ?>";
					      document.getElementById("RCBS_matfebPercentile").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_matfebPercentile_error'></div></div>
					</div>
				</div>
			</li>
			<li id="matfeb1" style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>MAT Feb. Form Number: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_matfebFormNumber' id='RCBS_matfebFormNumber'   required="true"  validate="validateStr"    caption="Form Number"   minlength="2"   maxlength="50"     tip="Mention your form number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_matfebFormNumber) && $RCBS_matfebFormNumber!=""){ ?>
					  <script>
					      document.getElementById("RCBS_matfebFormNumber").value = "<?php echo str_replace("\n", '\n', $RCBS_matfebFormNumber );  ?>";
					      document.getElementById("RCBS_matfebFormNumber").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_matfebFormNumber_error'></div></div>
					</div>
				</div>
		
				<div class='additionalInfoRightCol'>
					<label>MAT Feb. Composite Score: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_matfebScore' id='RCBS_matfebScore'   required="true"        validate="validateFloat"    caption="Score"   minlength="1"   maxlength="5"    tip="Mention your score in the exam. If you don't know your score, enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_matfebScore) && $RCBS_matfebScore!=""){ ?>
					  <script>
					      document.getElementById("RCBS_matfebScore").value = "<?php echo str_replace("\n", '\n', $RCBS_matfebScore );  ?>";
					      document.getElementById("RCBS_matfebScore").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_matfebScore_error'></div></div>
					</div>
				</div>
			</li>
			
			
			<li id="cmatsept" style="display:none">
				
				<div class='additionalInfoLeftCol'>
					<label>CMAT Sept. Roll Number: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_cmatseptRollNumber' id='RCBS_cmatseptRollNumber'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_cmatseptRollNumber) && $RCBS_cmatseptRollNumber!=""){ ?>
					  <script>
					      document.getElementById("RCBS_cmatseptRollNumber").value = "<?php echo str_replace("\n", '\n', $RCBS_cmatseptRollNumber );  ?>";
					      document.getElementById("RCBS_cmatseptRollNumber").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_cmatseptRollNumber_error'></div></div>
					</div>
				</div>
				
				<div class='additionalInfoRightCol'>
					<label>CMAT Sept. Score: </label>
					<div class='fieldBoxLarge'>
					<input  allowNA="true"  type='text' name='RCBS_cmatseptPercentile' id='RCBS_cmatseptPercentile'   validate="validateFloat"    caption="Score"   minlength="1"   maxlength="10"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''   />
					<?php if(isset($RCBS_cmatseptPercentile) && $RCBS_cmatseptPercentile!=""){ ?>
					  <script>
					      document.getElementById("RCBS_cmatseptPercentile").value = "<?php echo str_replace("\n", '\n', $RCBS_cmatseptPercentile );  ?>";
					      document.getElementById("RCBS_cmatseptPercentile").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_cmatseptPercentile_error'></div></div>
					</div>
				</div>
			</li>
			
			<li id="cmatsept1" style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>CMAT Sept. Rank: </label>
					<div class='fieldBoxLarge'>
					<input  allowNA="true"  type='text' name='RCBS_cmatseptRank' id='RCBS_cmatseptRank'   validate="validateFloat"    caption="rank"   minlength="1"   maxlength="50"     tip="Mention your rank in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''   />
					<?php if(isset($RCBS_cmatseptRank) && $RCBS_cmatseptRank!=""){ ?>
					  <script>
					      document.getElementById("RCBS_cmatseptRank").value = "<?php echo str_replace("\n", '\n', $RCBS_cmatseptRank );  ?>";
					      document.getElementById("RCBS_cmatseptRank").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_cmatseptRank_error'></div></div>
					</div>
				</div>
			</li>
			
			<li id="cmatfeb" style="display:none">
				
				<div class='additionalInfoLeftCol'>
					<label>CMAT Feb. Roll Number: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_cmatfebRollNumber' id='RCBS_cmatfebRollNumber'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
					<?php if(isset($RCBS_cmatfebRollNumber) && $RCBS_cmatfebRollNumber!=""){ ?>
					  <script>
					      document.getElementById("RCBS_cmatfebRollNumber").value = "<?php echo str_replace("\n", '\n', $RCBS_cmatfebRollNumber);  ?>";
					      document.getElementById("RCBS_cmatfebRollNumber").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_cmatfebRollNumber_error'></div></div>
					</div>
				</div>
				
				<div class='additionalInfoRightCol'>
					<label>CMAT Feb. Score: </label>
					<div class='fieldBoxLarge'>
					<input  allowNA="true"  type='text' name='RCBS_cmatfebPercentile' id='RCBS_cmatfebPercentile'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Mention your score in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''   />
					<?php if(isset($RCBS_cmatfebPercentile) && $RCBS_cmatfebPercentile!=""){ ?>
					  <script>
					      document.getElementById("RCBS_cmatfebPercentile").value = "<?php echo str_replace("\n", '\n', $RCBS_cmatfebPercentile );  ?>";
					      document.getElementById("RCBS_cmatfebPercentile").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_cmatfebPercentile_error'></div></div>
					</div>
				</div>
			</li>
			
			<li id="cmatfeb1" style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>CMAT Feb. Rank: </label>
					<div class='fieldBoxLarge'>
					<input  allowNA="true"  type='text' name='RCBS_cmatfebRank' id='RCBS_cmatfebRank'   validate="validateFloat"    caption="rank"   minlength="1"   maxlength="50"     tip="Mention your rank in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''   />
					<?php if(isset($RCBS_cmatfebRank) && $RCBS_cmatfebRank!=""){ ?>
					  <script>
					      document.getElementById("RCBS_cmatfebRank").value = "<?php echo str_replace("\n", '\n', $RCBS_cmatfebRank );  ?>";
					      document.getElementById("RCBS_cmatfebRank").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'RCBS_cmatfebRank_error'></div></div>
					</div>
				</div>
			</li>
			<?php if($action != 'updatescore') { ?>
			<li>
				<h3 class='upperCase'>Personal Details</h3>
				<div class='additionalInfoLeftCol'>
					<label>State of Domicile:</label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_StateDomicile' id='RCBS_StateDomicile' required='true' validate='validateStr' caption="state of domicile" title="domicile state" tip="Please specify your state of domicile." minlength='1' maxlength='20' value=''>
					<?php if(isset($RCBS_StateDomicile) && $RCBS_StateDomicile != '') { ?>
					<script>
						document.getElementById("RCBS_StateDomicile").value = "<?php echo str_replace("\n", '\n', $RCBS_StateDomicile );  ?>";
						document.getElementById("RCBS_StateDomicile").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_StateDomicile_error'></div></div>
					</div>
				</div>
				<div class='additionalInfoRightCol'>
					<label>Place of Birth(specify district and state)</label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_PlaceofBirth' id='RCBS_PlaceofBirth' required='true' validate='validateStr' caption="place of birth" tip="Please specify your place of birth with district and state." minlength='1' maxlength='100' value='' title="birth place">
					<?php if(isset($RCBS_PlaceofBirth) && $RCBS_PlaceofBirth != '') { ?>
					<script>
						document.getElementById("RCBS_PlaceofBirth").value = "<?php echo str_replace("\n", '\n', $RCBS_PlaceofBirth );  ?>";
						document.getElementById("RCBS_PlaceofBirth").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_PlaceofBirth_error'></div></div>	
					</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
					<label>Caste:</label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_caste' id='RCBS_caste' required='true' validate='validateStr' value='' tip="Please enter your caste details." caption="caste" title="caste" minlength='1' maxlength='30'>
					<?php if(isset($RCBS_caste) && $RCBS_caste != '') { ?>
					<script>
						document.getElementById("RCBS_caste").value = "<?php echo str_replace("\n", '\n', $RCBS_caste );  ?>";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_caste_error'></div></div>	
					</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol' style="width:900px;">
					<label>Specific Categories:</label>
					 <div class='fieldBoxLarge'>
					<select name="RCBS_category" id="RCBS_category" required='true' onmouseover="showTipOnline('Please select the specific category you belong to.',this);" onmouseout='hidetip();' validate='validateSelect' caption='category'>
					<option value="">SELECT</option>
					<option value="General">General</option>
					<option value="OBC">OBC</option>
					<option value="SC">SC</option>
					<option value="ST">ST</option>
					<option value="OEC">OEC</option>
					<option value="Roman Catholic Syrian Christian(Syro Malabar)">Roman Catholic Syrian Christian(Syro Malabar)</option>
					</select>
					<?php if(isset($RCBS_category) && $RCBS_category!=""){ ?>
					<script>
					var selObj = document.getElementById("RCBS_category"); 
					var A= selObj.options, L= A.length;
					while(L){
						if (A[--L].value== "<?php echo $RCBS_category;?>"){
						selObj.selectedIndex= L;
						L= 0;
						}
					}
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_category_error'></div></div>
					</div>	
				</div>	
					
			</li>
			
			<li>
				<div class='additionalInfoLeftCol' style="width:600px;">
				<label>Socially and Educationally Backward classes:</label>
				<div class='fieldBoxLarge'>
				<select name="RCBS_BackwardClass" id="RCBS_BackwardClass" required='true' onchange="CategoryDetail(this);" onmouseover="showTipOnline('Please select the class you belong to.If not, select NA',this);" onmouseout='hidetip();' validate='validateSelect' caption='backward class'>
					<option value="">SELECT</option>
					<option value="Ezhava / Thiyya / Bhillava">Ezhava / Thiyya / Bhillava</option>
					<option value="Muslims">Muslims</option>
					<option value="Latin Catholic Other than Anglo Indians">Latin Catholic Other than Anglo Indians</option>
					<option value="Other Backward Hindu">Other Backward Hindu</option>
					<option value="Physically Challenged">Physically Challenged </option>
					<option value="NA">NA</option>
				</select>
					<?php if(isset($RCBS_BackwardClass) && $RCBS_BackwardClass!=""){ ?>
					<script>
					var selObj = document.getElementById("RCBS_BackwardClass"); 
					var A= selObj.options, L= A.length;
					while(L){
						if (A[--L].value== "<?php echo $RCBS_BackwardClass;?>"){
						selObj.selectedIndex= L;
						CategoryDetailObj=A[L];
						L= 0;
						}
					}
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_BackwardClass_error'></div></div>
				</div>
			<li>
				
				<div id='BackwardClassDetail' class='additionalInfoLeftCol' style="display:none;width:600px;">
				
					
					<label>Specify the OBH Category (Vania, Arya etc.):</label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_BackwardClassDetail' id='RCBS_BackwardClassDetail' tip="Please enter the OBH category." validate='validateStr' title="backward category" caption="OBH category" minlength="2" maxlength="30" value='' />
					
					<?php if(isset($RCBS_BackwardClassDetail) && $RCBS_BackwardClassDetail!="") { ?>
					<script>
					    document.getElementById("RCBS_BackwardClassDetail").value = "<?php echo str_replace("\n",'\n', $RCBS_BackwardClassDetail); ?>";
					    document.getElementById("RCBS_BackwardClassDetail").style.color="";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_BackwardClassDetail_error'></div></div>
					</div>
				</div>
				
				<div id='PhysicalDetail' class='additionalInfoLeftCol' style="display:none">	
					<label>(Specify) :</label>
					<div class='fieldBoxLarge'>
					<input type='text' name='RCBS_DisabilityDetail' id='RCBS_DisabilityDetail' tip="Please specify your disability." validate='validateStr' title="Details of Disability" caption="Disability" minlength="2" maxlength="50" value='' />
					<?php if(isset($RCBS_DisabilityDetail) && $RCBS_DisabilityDetail!="") { ?>
					<script>
					    document.getElementById("RCBS_DisabilityDetail").value = "<?php echo str_replace("\n",'\n', $RCBS_DisabilityDetail); ?>";
					    document.getElementById("RCBS_DisabilityDetail").style.color="";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_DisabilityDetail_error'></div></div>
					</div>
					
				</div>
			</li>
			
			<li>
				<h3 class='upperCase'>Address for correspondence (Others)</h3>
				<div class='additionalInfoLeftCol'>
					<label>District:</label>
					<div class='fieldBoxLarge'>
					<input type='text' name="RCBS_District" id="RCBS_District" value='' validate='validateStr' minlength='1' maxlength='10' required='true' tip="Please enter your district for correspondence address." caption="district" title="district">
					<?php if(isset($RCBS_District) && $RCBS_District != '') { ?>
					<script>
						document.getElementById("RCBS_District").value = "<?php echo str_replace("\n", '\n', $RCBS_District );  ?>";
						document.getElementById("RCBS_District").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_District_error'></div></div>
					</div>
				</div>
			
				
			<li>
				<h3 class='upperCase'>Parents Additional Information</h3>
				<div class='additionalInfoLeftCol'>
					<label>Parents Mobile:</label>
					<div class='fieldBoxLarge'>
					<input type='text' name="RCBS_ParentsMobile" id="RCBS_ParentsMobile" value='' validate='validateInteger' minlength='10' maxlength='10' required='true' tip="Please enter the mobile no. of your parents." caption="parents mobile no." title="mobile no.">
					<?php if(isset($RCBS_ParentsMobile) && $RCBS_ParentsMobile != '') { ?>
					<script>
						document.getElementById("RCBS_ParentsMobile").value = "<?php echo str_replace("\n", '\n', $RCBS_ParentsMobile );  ?>";
						document.getElementById("RCBS_ParentsMobile").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_ParentsMobile_error'></div></div>
					</div>
				</div>
				<div class='additionalInfoRightCol'>
					<label>Family Annual Income:</label>
					<div class='fieldBoxLarge'>
					<input type='text' name="RCBS_FamilyIncome" id="RCBS_FamilyIncome" value='' validate='validateFloat' minlength='1' maxlength='10' required='true' tip="Please enter your annual family income." caption="family annual income" title="income">
					<?php if(isset($RCBS_FamilyIncome) && $RCBS_FamilyIncome != '') { ?>
					<script>
						document.getElementById("RCBS_FamilyIncome").value = "<?php echo str_replace("\n", '\n', $RCBS_FamilyIncome );  ?>";
						document.getElementById("RCBS_FamilyIncome").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_FamilyIncome_error'></div></div>
					</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
					<label>E-mail ID:</label>
					<div class='fieldBoxLarge'>
					<input type='text' name="RCBS_ParentsEmail" id="RCBS_ParentsEmail" value='' validate='validateEmail' minlength='1' maxlength='30' required='true' tip="Please enter the email id of your parents." caption="parents email id" title="email">
					<?php if(isset($RCBS_ParentsEmail) && $RCBS_ParentsEmail != '') { ?>
					<script>
						document.getElementById("RCBS_ParentsEmail").value = "<?php echo str_replace("\n", '\n', $RCBS_ParentsEmail );  ?>";
						document.getElementById("RCBS_ParentsEmail").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_ParentsEmail_error'></div></div>
					</div>
				</div>
				
			</li>
			
			<li>
				<h3 class='upperCase'>Additional Educational Information</h3>
				<div class='additionalInfoLeftCol'>
					<label>Passing month(10th std.):</label>
					<div class='fieldBoxLarge'>
					<input type='text' name="RCBS_passingMonth10" id="RCBS_passingMonth10" value='' validate='validateStr' minlength='1' maxlength='10' required='true' tip="Please enter the passing month of your 10th standard." caption="10th std passing month" title="month">
					<?php if(isset($RCBS_passingMonth10) && $RCBS_passingMonth10 != '') { ?>
					<script>
						document.getElementById("RCBS_passingMonth10").value = "<?php echo str_replace("\n", '\n', $RCBS_passingMonth10 );  ?>";
						document.getElementById("RCBS_passingMonth10").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_passingMonth10_error'></div></div>
					</div>
				</div>
				<div class='additionalInfoRightCol'>
					<label>Passing month(12th std.):</label>
					<div class='fieldBoxLarge'>
					<input type='text' name="RCBS_passingMonth12" id="RCBS_passingMonth12" value='' validate='validateStr' minlength='1' maxlength='10' required='true' tip="Please enter the passing month of your 12th standard." caption="12th std passing month" title="month">
					<?php if(isset($RCBS_passingMonth12) && $RCBS_passingMonth12 != '') { ?>
					<script>
						document.getElementById("RCBS_passingMonth12").value = "<?php echo str_replace("\n", '\n', $RCBS_passingMonth12 );  ?>";
						document.getElementById("RCBS_passingMonth12").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_passingMonth12_error'></div></div>
					</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
					<label>Passing month(Graduation):</label>
					<div class='fieldBoxLarge'>
					<input type='text' name="RCBS_passingMonthGrad" id="RCBS_passingMonthGrad" value='' validate='validateStr' minlength='1' maxlength='10' required='true' tip="Please enter the passing month of your graduation." caption="graduation passing month" title="month">
					<?php if(isset($RCBS_passingMonthGrad) && $RCBS_passingMonthGrad != '') { ?>
					<script>
						document.getElementById("RCBS_passingMonthGrad").value = "<?php echo str_replace("\n", '\n', $RCBS_passingMonthGrad );  ?>";
						document.getElementById("RCBS_passingMonthGrad").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_passingMonthGrad_error'></div></div>
					</div>
				</div>
				<div class='additionalInfoRightCol'>
					<label>Graduation Specialization:</label>
					<div class='fieldBoxLarge'>
					<input type='text' name="RCBS_SpecializationGrad" id="RCBS_SpecializationGrad" value='' validate='validateStr' minlength='1' maxlength='30' required='true' tip="Please enter specialization of your graduation." caption="graduation specialization" title="specialization">
					<?php if(isset($RCBS_SpecializationGrad) && $RCBS_SpecializationGrad != '') { ?>
					<script>
						document.getElementById("RCBS_SpecializationGrad").value = "<?php echo str_replace("\n", '\n', $RCBS_SpecializationGrad );  ?>";
						document.getElementById("RCBS_SpecializationGrad").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_SpecializationGrad_error'></div></div>
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
	?>
	<?php
			    $i=0;
			    if(count($otherCourses)>0) { ?>
			    <?php
				    foreach($otherCourses as $otherCourseId => $otherCourseName) {
					    $pgCheck = 'otherCoursePGCheck_mul_'.$otherCourseId;
					    $pgCheckVal = $$pgCheck;
					    $passingMonth = 'otherCoursePassingMonth_mul_'.$otherCourseId;
					    $passingMonthVal = $$passingMonth;
					    $specialization = 'otherCourseSpecialization_mul_'.$otherCourseId;
					    $specializationVal = $$specialization;
					    
					    
					    $i++;
				    
			   ?>
			   
			<li>
				<div class='additionalInfoLeftCol'>
					<label>Passing month(<?php echo $otherCourseName;?>)</label>
					<div class='fieldBoxLarge'>
					<input type='text' name='<?php echo $passingMonth;?>' id='<?php echo $passingMonth;?>'    validate="validateStr"   required="true"   caption="<?php echo $otherCourseName;?>: passing month"   minlength="1"   maxlength="10"          tip="Please enter the passing month of your <?=html_escape($otherCourseName);?>"   value=''   allowNA='yes'/>
				<?php if(isset($passingMonthVal) && $passingMonthVal!=""){ ?>
					<script>
				         document.getElementById("<?php echo $passingMonth;?>").value = "<?php echo str_replace("\n", '\n', $passingMonthVal );  ?>";
				         document.getElementById("<?php echo $passingMonth;?>").style.color = "";
					</script>
				<?php } ?>
				
					<div style='display:none'><div class='errorMsg' id= '<?php echo $passingMonth;?>_error'></div></div>
					</div>
				</div>
				
				<div class='additionalInfoRightCol'>
					<label> <?php echo $otherCourseName;?> Specialization</label>
					<div class='fieldBoxLarge'>
					<input type='text' name='<?php echo $specialization;?>' id='<?php echo $specialization;?>'    validate="validateStr"   required="true"   caption="<?php echo $otherCourseName;?>: specialization"   minlength="1"   maxlength="30"          tip="Enter the specialization of <?=html_escape($otherCourseName);?>"   value=''   allowNA='yes'/>
					<?php if(isset($specializationVal) && $specializationVal!=""){ ?>
					  <script>
					      document.getElementById("<?php echo $specialization;?>").value = "<?php echo str_replace("\n", '\n', $specializationVal );  ?>";	
					      document.getElementById("<?php echo $specialization;?>").style.color = "";
					  </script>
					<?php } ?>
				
					<div style='display:none'><div class='errorMsg' id= '<?php echo $specialization;?>_error'></div></div>
					</div>
				</div>
			</li>
<?php }} ?>
			    
			<li>
				<h3 class='upperCase'>Achievements</h3>
				<div class='additionalInfoLeftCol' style="width:950px;">
					<label>Academic Achievements, if any:</label>
					<div class='fieldBoxLarge' >
					<textarea style="width: 100%; height:100px;" name='RCBS_achieveAcademic' id='RCBS_achieveAcademic' value='' caption="academic achievements" title="achievements" tip="Please enter your academic achievements."  validate='validateStr' minlength='0' maxlength='2000'></textarea>
					<?php if(isset($RCBS_achieveAcademic) && $RCBS_achieveAcademic != '') { ?>
					<script>
						document.getElementById("RCBS_achieveAcademic").value = "<?php echo str_replace("\n", '\n', $RCBS_achieveAcademic );  ?>";
						document.getElementById("RCBS_achieveAcademic").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_achieveAcademic_error'></div></div>
					</div>
				</div>						
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
					<label>Non Academic Achievements, if any:</label>
					<div class='fieldBoxLarge'>
					<textarea style="width: 100%; height:100px;" name='RCBS_achieveNonAcademic' id='RCBS_achieveNonAcademic' value='' caption="non academic achievements" title="achievements" tip="Please enter your non academic achievements."  validate='validateStr' minlength='0' maxlength='2000'></textarea>
					<?php if(isset($RCBS_achieveNonAcademic) && $RCBS_achieveNonAcademic != '') { ?>
					<script>
						document.getElementById("RCBS_achieveNonAcademic").value = "<?php echo str_replace("\n", '\n', $RCBS_achieveNonAcademic );  ?>";
						document.getElementById("RCBS_achieveNonAcademic").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_achieveNonAcademic_error'></div></div>
					</div>
				</div>						
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
					<label>Memberships in bodies (like NCC, NSS, and IEEE etc):</label>
					<div class='fieldBoxLarge'>
					<textarea style="width: 100%; height:100px;" name='RCBS_Membership' id='RCBS_Membership'  value='' caption="your membership in bodies(like NCC, NSS, and IEEE etc).If not, enter NA" title="membership" tip="Please describe if you are a member of following bodies(like NCC, NSS, and IEEE etc)."  validate='validateStr' minlength='0' maxlength='2000'></textarea>
					<?php if(isset($RCBS_Membership) && $RCBS_Membership != '') { ?>
					<script>
						document.getElementById("RCBS_Membership").value = "<?php echo str_replace("\n", '\n', $RCBS_Membership );  ?>";
						document.getElementById("RCBS_Membership").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'RCBS_Membership_error'></div></div>
					</div>
				</div>						
			</li>
			
			<li>
				<h3 class='upperCase'>Work Experience(others)</h3>
				<div class='additionalInfoLeftCol'>
					<label>Total work experience in months:</label>
					<div class='fieldBoxLarge'>
					<input  type='text' name='RCBS_workEX' id='RCBS_workEX'  validate="validateInteger" minlength="1" maxlength="10" caption="total number of months" tip="Enter total work experience in months."   value='' required="true" allowNA='true'/>
				<?php if(isset($RCBS_workEX) && $RCBS_workEX!=""){ ?>
				  <script>
				      document.getElementById("RCBS_workEX").value = "<?php echo str_replace("\n", '\n',$RCBS_workEX );  ?>";
				      document.getElementById("RCBS_workEX").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'RCBS_workEX_error'></div></div>
				</div>
				</div>
			</li>
				<?php
			$workCompanies = array();
			if(is_array($educationDetails)) {
				foreach($educationDetails as $educationDetail) {
					if($educationDetail['value']) {
						if($educationDetail['fieldName'] == 'weCompanyName') {
							$workCompanies['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weCompanyName_mul_'.$i) {
									$workCompanies['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
						if($educationDetail['fieldName'] == 'weFrom') {
							$workCompaniesExpFrom['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weFrom_mul_'.$i) {
									$workCompaniesExpFrom['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
				
						if($educationDetail['fieldName'] == 'weTill') {
							$workCompaniesExpTill['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weTill_mul_'.$i) {
									$workCompaniesExpTill['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
					}
				}
			}
			
	if(count($workCompanies) > 0) {
		           
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$workExpRemunerationName = 'workExpRemuneration'.$workCompanyKey;
					$workExpRemunerationValue = $$workExpRemunerationName;
					$j++;
					
			?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Monthly Remuneration at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $workExpRemunerationName;?>' id='<?php echo $workExpRemunerationName;?>'  validate="validateInteger" minlength="1" maxlength="10" caption="Number of months at <?php echo $workCompany; ?>"    tip="Enter your monthly remuneration at  <?php echo $workCompany; ?>"   value=''   required="true" allowNA='true'/>
				<?php if(isset($workExpRemunerationValue) && $workExpRemunerationValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpRemunerationName;?>").value = "<?php echo str_replace("\n", '\n',$workExpRemunerationValue );  ?>";
				      document.getElementById("<?php echo $workExpRemunerationName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpRemunerationName;?>_error'></div></div>
				</div>
				</div>
				
			</li>
			<?php }} ?>
			
			<li>
				
				<h3 class=upperCase'>Declaration of Student</h3>
				<label style="font-weight:normal; padding-top:0">Undertaking:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				I hereby undertake, that if I am admitted to the college, I will
abide by the rules and regulations of the college, and will do nothing either inside or outside the college that will interfere with its orderly working, discipline and reputation.</br>
I do affirm that all information furnished in this application is correct to the best of my knowledge and belief.</br>
				</div>
				<div class="spacer10 clearFix"></div>
			</li>
			<li>
				<div>
				<input type='checkbox'   required="true"    name='agreeToTermsRCBS' id='agreeToTermsRCBS'   value=''  checked  validate='validateChecked' caption='Please agree to the terms stated above'></input><span ></span>&nbsp;&nbsp;
				<label style="font-weight:normal;">I agree to the terms stated above: </label></div>
				
				
				<?php if(isset($agreeToTermsRCBS) && $agreeToTermsRCBS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsRCBS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$agreeToTermsRCBS);
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
				
				<div style="display:none;"><div class='errorMsg' id= 'agreeToTermsRCBS_error'></div></div>
				
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
<script>
for(var j=0; j<5; j++){
		checkTestScore(document.getElementById('RCBS_testNames'+j));
	}
	CategoryDetail(CategoryDetailObj);
  </script>