<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	if(obj.value == "CAT" || obj.value == "XAT"){ 
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional",key+"ExamValidityNIA");
	    var objects2 = new Array(key+"PercentileScoreRank");
	}
	else if(obj.value == "CMAT"){
	    var objects1 = new Array(key+"RollNumberFeb2014NIA",key+"DateOfExaminationFeb2014NIA",key+"ScoreFeb2014NIA",key+"ExamValidityNIA",key+"PercentileNIA");   
	    var objects2 = new Array(key+"RollNumberFeb2015NIA",key+"DateOfExaminationFeb2015NIA",key+"ScoreFeb2015NIA",key+"ExamValidityFeb2015NIA",key+"PercentileFeb2015NIA",key+"RankFeb2015NIA",key+"RankFeb2014NIA",key+"RankSep2014NIA");

	}
	
	if(obj){
	      if( obj.checked == false ){
		    $(key+'1').style.display = 'none';
		    $(key+'2').style.display = 'none';
		    $(key+'3').style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1,objects2,key);
	      }
	      else{
		    $(key+'1').style.display = '';
		    $(key+'2').style.display = '';
		    $(key+'3').style.display = '';
		    //Set the required paramters when any Exam is shown
		    setExamFields(objects1);
	      }
	}
  }

  function setExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
	    document.getElementById(objectsArr[i]).setAttribute('required','true');
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
  }

  function resetExamFields(objectsArr,objectsArrNew,key1){
     if (key1=='cat') {
      for(i=0;i<objectsArr.length;i++){
	    document.getElementById(objectsArr[i]).removeAttribute('required');
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
     }else{
	for(i=0;i<objectsArr.length;i++){
	    document.getElementById(objectsArr[i]).removeAttribute('required');
	    document.getElementById(objectsArr[i]).value='';
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
     }
	for(i=0;i<objectsArrNew.length;i++){
	    document.getElementById(objectsArrNew[i]).value='';
	}
  }

</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	
			<li style="width:100%">
				<h3 class="upperCase">Qualifying Examinations : <span style="font-weight:normal;">CAT/CMAT (Indicate latest valid scores for the examination)</span></h3>
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>EXAMS: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesNIA[]' id='testNamesNIA0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;			
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesNIA[]' id='testNamesNIA1'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<?php if(isset($testNamesNIA) && $testNamesNIA!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesNIA[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$testNamesNIA);
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
				
				<div style='display:none'><div class='errorMsg' id= 'testNamesNIA_error'></div></div>
				</div>
				</div>
			</li>
			<li id="cat1" style="display:none;">
			  <h3 class="upperCase">CAT Nov 2014 Score:</h3>
				<div class='additionalInfoLeftCol'>
				<label>CAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
				<label>CAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='catDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($catDateOfExaminationAdditional) && $catDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("catDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $catDateOfExaminationAdditional );  ?>";
				      document.getElementById("catDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="cat2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
				<?php if(isset($catScoreAdditional) && $catScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("catScoreAdditional").value = "<?php echo str_replace("\n", '\n', $catScoreAdditional );  ?>";
				      document.getElementById("catScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
			<li id="cat3" style="display:none;  padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>Rank (If Applicable): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileScoreRank' id='catPercentileScoreRank'  tip="Please Enter CAT Exam Rank.If you don't know your rank, enter NA"   value=''  validate="validateStr" caption="rank" minlength="1" maxlength="7" allowNA="true"/>
				<?php if(isset($catPercentileScoreRank) && $catPercentileScoreRank!=""){ ?>
				  <script>
				      document.getElementById("catPercentileScoreRank").value = "<?php echo str_replace("\n", '\n', $catPercentileScoreRank );  ?>";
				      document.getElementById("catPercentileScoreRank").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catPercentileScoreRank_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Validity till MM/YY: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catExamValidityNIA' id='catExamValidityNIA'     tip="Please Enter CAT Exam validity till mm/yy"   value=''  validate="validateStr" caption="validity" minlength="5" maxlength="5"/>
				<?php if(isset($catExamValidityNIA) && $catExamValidityNIA!=""){ ?>
				  <script>
				      document.getElementById("catExamValidityNIA").value = "<?php echo str_replace("\n", '\n', $catExamValidityNIA );  ?>";
				      document.getElementById("catExamValidityNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catExamValidityNIA_error'></div></div>
				</div>
				</div>
				
			</li>
			<?php
			    if(isset($testNamesNIA) && $testNamesNIA!="" && strpos($testNamesNIA,'CAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesNIA0'));
			    </script>
			<?php
			    }
			?>
			
			
			
			
		<li id="cmat1" style="display:none;">
			<div class="clearFix"></div>
			<h3 class="upperCase">CMAT Sep 2014 Score:</h3>
			<div class="additionalInfoLeftCol" style="padding-bottom:20px;">
				<label style="font-weight:normal">CMAT Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatRollNumberFeb2014NIA' id='cmatRollNumberFeb2014NIA'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($cmatRollNumberFeb2014NIA) && $cmatRollNumberFeb2014NIA!=""){ ?>
						<script>
						document.getElementById("cmatRollNumberFeb2014NIA").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberFeb2014NIA );  ?>";
						document.getElementById("cmatRollNumberFeb2014NIA").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberFeb2014NIA_error'></div></div>
				</div>
			</div>
			<div class="additionalInfoRightCol">
				<label style="font-weight:normal">CMAT Date of Examination: </label>
				<div class='float_L' style="width:125px;">
				<input class="calenderFields" type='text' name='cmatDateOfExaminationFeb2014NIA' id='cmatDateOfExaminationFeb2014NIA' readonly maxlength='10'    validate="validateDateForms"  caption="date"           onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationFeb2014NIA'),'cmatDateOfExaminationFeb2014NIA_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='cmatDateOfExaminationFeb2014NIA_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationFeb2014NIA'),'cmatDateOfExaminationFeb2014NIA_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($cmatDateOfExaminationFeb2014NIA) && $cmatDateOfExaminationFeb2014NIA!=""){ ?>
						<script>
						document.getElementById("cmatDateOfExaminationFeb2014NIA").value = "<?php echo str_replace("\n", '\n', $cmatDateOfExaminationFeb2014NIA );  ?>";
						document.getElementById("cmatDateOfExaminationFeb2014NIA").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'cmatDateOfExaminationFeb2014NIA_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoLeftCol">
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
					<input class="textboxSmaller" type='text' name='cmatScoreFeb2014NIA' id='cmatScoreFeb2014NIA'  validate="validateFloat" allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
					<?php if(isset($cmatScoreFeb2014NIA) && $cmatScoreFeb2014NIA!=""){ ?>
							<script>
								document.getElementById("cmatScoreFeb2014NIA").value = "<?php echo str_replace("\n", '\n', $cmatScoreFeb2014NIA );  ?>";
								document.getElementById("cmatScoreFeb2014NIA").style.color = "";
							</script>
							  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatScoreFeb2014NIA_error'></div></div>
				</div>
			</div>
				

			<div class="additionalInfoRightCol">
				<label>CMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				   <input class="textboxSmaller"  type='text' name='cmatPercentileNIA' id='cmatPercentileNIA'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
					<?php if(isset($cmatPercentileNIA) && $cmatPercentileNIA!=""){ ?>
							<script>
							document.getElementById("cmatPercentileNIA").value = "<?php echo str_replace("\n", '\n', $cmatPercentileNIA );  ?>";
							document.getElementById("cmatPercentileNIA").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatPercentileNIA_error'></div></div>
				</div>
			      </div>
			
			
			
			
		</li>
		
		<li id="cmat2" style="display:none;" >
		  
		  <div class='additionalInfoLeftCol' style="padding-bottom:20px;">
				<label>Rank (If Applicable): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankFeb2014NIA' id='cmatRankFeb2014NIA'   tip="Please Enter CMAT Exam Rank.If you don't know your rank, enter NA"   value=''  validate="validateStr" caption="rank" minlength="1" maxlength="7" allowNA="true"/>
				<?php if(isset($cmatRankFeb2014NIA) && $cmatRankFeb2014NIA!=""){ ?>
				  <script>
				      document.getElementById("cmatRankFeb2014NIA").value = "<?php echo str_replace("\n", '\n', $cmatRankFeb2014NIA );  ?>";
				      document.getElementById("cmatRankFeb2014NIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRankFeb2014NIA_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Validity till MM/YY: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatExamValidityNIA' id='cmatExamValidityNIA'   tip="Please Enter CMAT Exam validity till mm/yy"   value=''  validate="validateStr" caption="validity" minlength="5" maxlength="5"/>
				<?php if(isset($cmatExamValidityNIA) && $cmatExamValidityNIA!=""){ ?>
				  <script>
				      document.getElementById("cmatExamValidityNIA").value = "<?php echo str_replace("\n", '\n', $cmatExamValidityNIA );  ?>";
				      document.getElementById("cmatExamValidityNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatExamValidityNIA_error'></div></div>
				</div>
				</div>
				
			<div class="clearFix"></div>
			<h3 class="upperCase">CMAT Feb 2015 Score:</h3>
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">CMAT Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatRollNumberFeb2015NIA' id='cmatRollNumberFeb2015NIA'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($cmatRollNumberFeb2015NIA) && $cmatRollNumberFeb2015NIA!=""){ ?>
						<script>
						document.getElementById("cmatRollNumberFeb2015NIA").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberFeb2015NIA );  ?>";
						document.getElementById("cmatRollNumberFeb2015NIA").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberFeb2015NIA_error'></div></div>
				</div>
			</div>
			<div class="additionalInfoRightCol">
				<label style="font-weight:normal">CMAT Date of Examination: </label>
				<div class='float_L' style="width:125px;">
				<input class="calenderFields" type='text' name='cmatDateOfExaminationFeb2015NIA' id='cmatDateOfExaminationFeb2015NIA' readonly maxlength='10'    validate="validateDateForms"  caption="date"           onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationFeb2015NIA'),'cmatDateOfExaminationFeb2015NIA_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='cmatDateOfExaminationFeb2015NIA_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationFeb2015NIA'),'cmatDateOfExaminationFeb2015NIA_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($cmatDateOfExaminationFeb2015NIA) && $cmatDateOfExaminationFeb2015NIA!=""){ ?>
						<script>
						document.getElementById("cmatDateOfExaminationFeb2015NIA").value = "<?php echo str_replace("\n", '\n', $cmatDateOfExaminationFeb2015NIA );  ?>";
						document.getElementById("cmatDateOfExaminationFeb2015NIA").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'cmatDateOfExaminationFeb2015NIA_error'></div></div>
				</div>
			</div>
			
		</li>
		<li id="cmat3" style="display:none;  padding-bottom:15px">
			
			
			<div class="additionalInfoLeftCol" style="padding-bottom:20px;">
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
					<input class="textboxSmaller" type='text' name='cmatScoreFeb2015NIA' id='cmatScoreFeb2015NIA'  validate="validateFloat" allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
					<?php if(isset($cmatScoreFeb2015NIA) && $cmatScoreFeb2015NIA!=""){ ?>
							<script>
								document.getElementById("cmatScoreFeb2015NIA").value = "<?php echo str_replace("\n", '\n', $cmatScoreFeb2015NIA );  ?>";
								document.getElementById("cmatScoreFeb2015NIA").style.color = "";
							</script>
							  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatScoreFeb2015NIA_error'></div></div>
				</div>
			</div>
				

			<div class="additionalInfoRightCol">
				<label>CMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				   <input class="textboxSmaller"  type='text' name='cmatPercentileFeb2015NIA' id='cmatPercentileFeb2015NIA'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
					<?php if(isset($cmatPercentileFeb2015NIA) && $cmatPercentileFeb2015NIA!=""){ ?>
							<script>
							document.getElementById("cmatPercentileFeb2015NIA").value = "<?php echo str_replace("\n", '\n', $cmatPercentileFeb2015NIA );  ?>";
							document.getElementById("cmatPercentileFeb2015NIA").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatPercentileFeb2015NIA_error'></div></div>
				</div>
			      </div>
			
			<div class='additionalInfoLeftCol'>
				<label>Rank (If Applicable): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankFeb2015NIA' id='cmatRankFeb2015NIA'   tip="Please Enter CMAT Exam Rank.If you don't know your rank, enter NA"   value=''  validate="validateStr" caption="rank" minlength="1" maxlength="7" allowNA="true"/>
				<?php if(isset($cmatRankFeb2015NIA) && $cmatRankFeb2015NIA!=""){ ?>
				  <script>
				      document.getElementById("cmatRankFeb2015NIA").value = "<?php echo str_replace("\n", '\n', $cmatRankFeb2015NIA );  ?>";
				      document.getElementById("cmatRankFeb2015NIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRankFeb2015NIA_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Validity till MM/YY: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatExamValidityFeb2015NIA' id='cmatExamValidityFeb2015NIA'   tip="Please Enter CMAT Exam validity till mm/yy"   value=''  validate="validateStr" caption="validity" minlength="5" maxlength="5"/>
				<?php if(isset($cmatExamValidityFeb2015NIA) && $cmatExamValidityFeb2015NIA!=""){ ?>
				  <script>
				      document.getElementById("cmatExamValidityFeb2015NIA").value = "<?php echo str_replace("\n", '\n', $cmatExamValidityFeb2015NIA );  ?>";
				      document.getElementById("cmatExamValidityFeb2015NIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatExamValidityFeb2015NIA_error'></div></div>
				</div>
				</div>
			</li>
		<?php
		    if(isset($testNamesNIA) && $testNamesNIA!="" && strpos($testNamesNIA,'CMAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('testNamesNIA1'));
		    </script>
		<?php
		    }
		?>
	                <?php if($action != 'updateScore'):?>
		      <li>
			<h3 class="upperCase">Personal Details:</h3>
				<div class='additionalInfoLeftCol'>
				<label>Category: </label>
				<div class='fieldBoxLarge'>
				<select  name='categoryNIA' id='categoryNIA'  style="width:120px;" tip="Select your category."  onmouseover="showTipOnline('Select your category.',this);" onmouseout="hidetip();" validate="validateSelect" required="true" caption="category" onchange="validateDateOfBirth(this)">
				  
				  <option value='' selected>Select</option><option value='General' >General</option><option value='SC' >SC</option><option value='ST' >ST</option><option value='DA' >DA</option><option value='OBC-NC' >OBC-NC</option>
				</select>
				<?php if(isset($categoryNIA) && $categoryNIA!=""){ ?>
			      <script>
				  var selObj = document.getElementById("categoryNIA"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $categoryNIA;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryNIA_error'></div></div>
				</div>
				</div>
			</li>	
			
		      <li>
		      
		      <label style="font-weight:normal">Date of Birth: </label>
			  <div class="fieldBoxSmall">
			  <input type='text' name='dateOfBirth' id='dateOfBirth' style="width:100px" readonly maxlength='10' validate="validateOnlineNIADate"  required="true" caption="Date of Birth" onmouseover="showTipOnline('Enter your Date of Birth',this);" onmouseout="hidetip();"   default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="hidetip(); var cal = new CalendarPopup('calendardiv'); cal.select($('dateOfBirth'),'dateOfBirth_dateImg','dd/MM/yyyy');" disabled/>&nbsp;
			  <img src='/public/images/eventIcon.gif' style='pointer-events: none; cursor:pointer' align='absmiddle' id='dateOfBirth_dateImg' onClick="hidetip(); var cal = new CalendarPopup('calendardiv'); cal.select($('dateOfBirth'),'dateOfBirth_dateImg','dd/MM/yyyy'); return false;" /><script>
					document.getElementById("dateOfBirth").style.color = "#ADA6AD";
				    </script>
		    <?php if(isset($dateOfBirth) && $dateOfBirth!=""){ ?>
				    <script>
					document.getElementById("dateOfBirth").value = "<?php echo str_replace("\n", '\n', $dateOfBirth );  ?>";
					document.getElementById("dateOfBirth").style.color = "";
				    </script>
				  <?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'dateOfBirth_error'></div></div>
			  </div>
		    </li>
			
			
		      

			<li>
				<h3 class="upperCase">Guardian Details:</h3>				
				<div class='additionalInfoLeftCol'>
				<label>Father's / Guardian's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gaurdianFirstNameNIA' id='gaurdianFirstNameNIA'   required="true"        tip="Enter the name of your Father/Guardian."   value=''  validate="validateStr"   required="true"   caption="Name"   minlength="2"   maxlength="50"  />
				<?php if(isset($gaurdianFirstNameNIA) && $gaurdianFirstNameNIA!=""){ ?>
				  <script>
				      document.getElementById("gaurdianFirstNameNIA").value = "<?php echo str_replace("\n", '\n', $gaurdianFirstNameNIA );  ?>";
				      document.getElementById("gaurdianFirstNameNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gaurdianFirstNameNIA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherFirstNameNIA' id='motherFirstNameNIA'   required="true"        tip="Please Enter Name."   value=''  validate="validateStr"   required="true"   caption="Name"   minlength="2"   maxlength="50" />
				<?php if(isset($motherFirstNameNIA) && $motherFirstNameNIA!=""){ ?>
				  <script>
				      document.getElementById("motherFirstNameNIA").value = "<?php echo str_replace("\n", '\n', $motherFirstNameNIA );  ?>";
				      document.getElementById("motherFirstNameNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherFirstNameNIA_error'></div></div>
				</div>
				</div>
				
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Relationship with Guardian: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gaurdianRelationshipNIA' id='gaurdianRelationshipNIA'   required="true"        tip="Please Enter the Relationship with Guardian.If not,enter NA."   value=''  validate="validateStr"   required="true"   caption="relationship with guardian"   minlength="2"   maxlength="50"  allowNA="true"/>
				<?php if(isset($gaurdianRelationshipNIA) && $gaurdianRelationshipNIA!=""){ ?>
				  <script>
				      document.getElementById("gaurdianRelationshipNIA").value = "<?php echo str_replace("\n", '\n', $gaurdianRelationshipNIA );  ?>";
				      document.getElementById("gaurdianRelationshipNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gaurdianRelationshipNIA_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Father's / Guardian's Occupation: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gaurdianOccupationNIA' id='gaurdianOccupationNIA'   required="true"        tip="Please Enter the Occupation."   value=''    caption="Occupation"   minlength="2"   maxlength="50" allowNA="true" validate="validateStr" />
				<?php if(isset($gaurdianOccupationNIA) && $gaurdianOccupationNIA!=""){ ?>
				  <script>
				      document.getElementById("gaurdianOccupationNIA").value = "<?php echo str_replace("\n", '\n', $gaurdianOccupationNIA );  ?>";
				      document.getElementById("gaurdianOccupationNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gaurdianOccupationNIA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Education Details:</h3>
				<div class='additionalInfoLeftCol'>
				<label>Subjects in class 10th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Specialization10thNIA' id='Specialization10thNIA'   tip="Enter the subjects studied by you in class 10th. Each subject name should be separated by a comma."    validate="validateStr"   required="true"   caption="Specialization"   onmouseover="showTipOnline('Enter the subjects studied by you in class 10th. Each subject name should be separated by a comma.',this);" onmouseout="hidetip();"    minlength="1"   maxlength="50"/>
				<?php if(isset($Specialization10thNIA) && $Specialization10thNIA!=""){ ?>
				  <script>
				      document.getElementById("Specialization10thNIA").value = "<?php echo str_replace("\n", '\n', $Specialization10thNIA );  ?>";
				      document.getElementById("Specialization10thNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Specialization10thNIA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Subjects in class 12th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Specialization12thNIA' id='Specialization12thNIA'   tip="Enter the subjects studied by you in class 12th. Each subject name should be separated by a comma."    validate="validateStr"   required="true"   caption="Specialization"   onmouseover="showTipOnline('Enter the subjects studied by you in class 12th. Each subject name should be separated by a comma.',this);" onmouseout="hidetip();"  minlength="1"   maxlength="50"/>
				<?php if(isset($Specialization12thNIA) && $Specialization12thNIA!=""){ ?>
				  <script>
				      document.getElementById("Specialization12thNIA").value = "<?php echo str_replace("\n", '\n', $Specialization12thNIA );  ?>";
				      document.getElementById("Specialization12thNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Specialization12thNIA_error'></div></div>
				</div>
				</div>
			</li>
  <?php
        // Find out graduation course name, if available
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

        if(isset($graduationEndDate) && $graduationYear!="") {
                $graduationYear = $graduationEndDate;
        }

    ?>
			<li>	
				<div class='additionalInfoLeftCol'>
				<label>Specialization in <?php echo $graduationCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationSpecializationNIA' id='graduationSpecializationNIA'   tip="Please enter the specialization or stream that you studies in your <?php echo $graduationCourseName;?>."    validate="validateStr"   required="true"   caption="Specialization"   onmouseover="showTipOnline('Please enter the specialization or stream that you studies in your <?php echo $graduationCourseName;?>.',this);" onmouseout="hidetip();"   minlength="1"   maxlength="50"/>
				<?php if(isset($graduationSpecializationNIA) && $graduationSpecializationNIA!=""){ ?>
				  <script>
				      document.getElementById("graduationSpecializationNIA").value = "<?php echo str_replace("\n", '\n', $graduationSpecializationNIA );  ?>";
				      document.getElementById("graduationSpecializationNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationSpecializationNIA_error'></div></div>
				</div>
				</div>
			</li>
 			<?php
                        $i=0;
                        if(count($otherCourses)>0) {
                                foreach($otherCourses as $otherCourseId => $otherCourseName) {
                                        $collegeSpecialization = 'otherCourseCollegeSpecialization_mul_'.$otherCourseId;
                                        $collegeSpecializationVal = $$collegeSpecialization;
					$pgDegreeCheck = 'pgDegreeCheck_mul_'.$otherCourseId;
					$pgDegreeCheckVal = $$pgDegreeCheck;
                                        $i++;

                        ?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Specialization in <?php echo $otherCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $collegeSpecialization;?>' id='<?php echo $collegeSpecialization;?>'   tip="Please enter the specialization or stream that you studies in <?php echo $otherCourseName;?>."    validate="validateStr"   required="true"   caption="Specialization"   onmouseover="showTipOnline('Please enter the specialization or stream that you studies in your <?php echo $otherCourseName;?>.',this);" onmouseout="hidetip();"  minlength="1"   maxlength="50"/>
				<?php if(isset($collegeSpecializationVal) && $collegeSpecializationVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $collegeSpecialization;?>").value = "<?php echo str_replace("\n", '\n', $collegeSpecializationVal );  ?>";
				      document.getElementById("<?php echo $collegeSpecialization;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $collegeSpecialization;?>_error'></div></div>
				</div>
				</div>

				 <div class='additionalInfoRightCol'>
                                <label>Is this a PG Degree? </label>
                                <div class='fieldBoxLarge'>
                                <input type='radio' name='<?php echo $pgDegreeCheck;?>' id='pgDegreeYes_<?php echo $i;?>'   value='Yes'  checked   onmouseover="showTipOnline('Please select Yes If you have PG Degree',this);" onmouseout="hidetip();"></input><span  onmouseover="showTipOnline('Please select Yes If you have PG Degree',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
                                <input type='radio' name='<?php echo $pgDegreeCheck;?>' id='pgDegreeNo_<?php echo $i;?>'   value='No'  onmouseover="showTipOnline('Please select No If you have PG Degree',this);" onmouseout="hidetip();"></input><span  onmouseover="showTipOnline('Please select No If you have PG Degree',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
                                <?php if(isset($pgDegreeCheckVal) && $pgDegreeCheckVal!=""){ ?>
                                  <script>
                                      radioObj = document.forms["OnlineForm"].elements['<?php echo $pgDegreeCheck;?>'];
                                      var radioLength = radioObj.length;
                                      for(var i = 0; i < radioLength; i++) {
                                              radioObj[i].checked = false;
                                              if(radioObj[i].value == "<?php echo $pgDegreeCheckVal;?>") {
                                                      radioObj[i].checked = true;
                                              }
                                      }
                                  </script>
                                <?php } ?>
                                </div>
                                </div>
			</li>
			  <?php
                                }
                        }
                        ?>

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
					}
				}
			}
			
			if(count($workCompanies) > 0) {
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$durationNIA = 'durationNIA'.$workCompanyKey;
					$durationNIAVal = $$durationNIA;
					$locationNIA = 'locationNIA'.$workCompanyKey;
					$locationNIAVal = $$locationNIA;
					$j++;

			?>			

			<li>    <?php if($j==1):?><h3 class="upperCase">Employment Details:</h3><?php endif;?>
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>Duration in <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' style="width:400px;">
				<input type='text' name='<?php echo $durationNIA;?>' id='<?php echo $durationNIA;?>'   required="true"        tip="Please Enter the duration in <?php echo $workCompany; ?>"   value='' validate="validateInteger" minlength="1" maxlength="10" caption="duration"  />  <span style=" color:#666666; font-style:italic">(in months)</span>
				<?php if(isset($durationNIAVal) && $durationNIAVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $durationNIA;?>").value = "<?php echo str_replace("\n", '\n', $durationNIAVal );  ?>";
				      document.getElementById("<?php echo $durationNIA;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $durationNIA;?>_error'></div></div>
				</div>
				</div>

			
				<!--<div style='display:none'><div class='errorMsg' id= '<?php echo $durationNIA;?>_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Location of <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' style="width:400px;">
				<input type='text' name='<?php echo $locationNIA;?>' id='<?php echo $locationNIA;?>'   required="true"        tip="Please Enter the location of <?php echo $workCompany; ?>"   value='' validate="validateStr" minlength="1" maxlength="100" caption="location"  /> 
				<?php if(isset($locationNIAVal) && $locationNIAVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $locationNIA;?>").value = "<?php echo str_replace("\n", '\n', $locationNIAVal );  ?>";
				      document.getElementById("<?php echo $locationNIA;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $locationNIA;?>_error'></div></div>
				</div>
				</div>-->
				
			</li>
			<?php
				}
			}
			?>

			<li style="width:100%">
				<h3 class="upperCase">Honors / Distinctions (Optional):</h3>
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Honors / Distinctions you have received: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='honorsdistinctionsNIA' id='honorsdistinctionsNIA'  tip="Please list all the honors or distinctions received by you during your academic years."  style="width:618px; height:74px; padding:5px"     ></textarea>
				<?php if(isset($honorsdistinctionsNIA) && $honorsdistinctionsNIA!=""){ ?>
				  <script>
				      document.getElementById("honorsdistinctionsNIA").value = "<?php echo str_replace("\n", '\n', $honorsdistinctionsNIA );  ?>";
				      document.getElementById("honorsdistinctionsNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'honorsdistinctionsNIA_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<h3 class="upperCase">References : <span style="font-weight:normal;">(Provide the names and positions of 3 persons who will submit references on your behalf)</span></h3>
                <div class="semesterDetailsBox">
                <strong>First:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1NameNIA' id='ref1NameNIA'  validate="validateStr"   required="true"   caption="name"   minlength="2"   maxlength="50"     tip="Enter Full Name of the reference"   value=''   />
				<?php if(isset($ref1NameNIA) && $ref1NameNIA!=""){ ?>
				  <script>
				      document.getElementById("ref1NameNIA").value = "<?php echo str_replace("\n", '\n', $ref1NameNIA );  ?>";
				      document.getElementById("ref1NameNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1NameNIA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Organization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1OrganizationNameNIA' id='ref1OrganizationNameNIA'  validate="validateStr"   required="true"   caption="organization"   minlength="2"   maxlength="50"     tip="Enter the organization name."   value=''   />
				<?php if(isset($ref1OrganizationNameNIA) && $ref1OrganizationNameNIA!=""){ ?>
				  <script>
				      document.getElementById("ref1OrganizationNameNIA").value = "<?php echo str_replace("\n", '\n', $ref1OrganizationNameNIA );  ?>";
				      document.getElementById("ref1OrganizationNameNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1OrganizationNameNIA_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Position: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1DesignationNIA' id='ref1DesignationNIA'  validate="validateStr"   required="true"   caption="position"   minlength="2"   maxlength="150"     tip="Enter the designation of this reference."   value=''   />
				<?php if(isset($ref1DesignationNIA) && $ref1DesignationNIA!=""){ ?>
				  <script>
				      document.getElementById("ref1DesignationNIA").value = "<?php echo str_replace("\n", '\n', $ref1DesignationNIA );  ?>";
				      document.getElementById("ref1DesignationNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1DesignationNIA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Tel. Nos: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1TelephoneNoNIA' id='ref1TelephoneNoNIA'  validate="validateInteger"   required="true"   caption="phone"   minlength="6"   maxlength="10"     tip="Enter the phone number of this reference"   value='' blurMethod="checkRefPhone(1);"   />
				<?php if(isset($ref1TelephoneNoNIA) && $ref1TelephoneNoNIA!=""){ ?>
				  <script>
				      document.getElementById("ref1TelephoneNoNIA").value = "<?php echo str_replace("\n", '\n', $ref1TelephoneNoNIA );  ?>";
				      document.getElementById("ref1TelephoneNoNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1TelephoneNoNIA_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>

				<div class='additionalInfoLeftCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1EmailNIA' id='ref1EmailNIA'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="200"     tip="Enter the email of this reference."   value=''   />
				<?php if(isset($ref1EmailNIA) && $ref1EmailNIA!=""){ ?>
				  <script>
				      document.getElementById("ref1EmailNIA").value = "<?php echo str_replace("\n", '\n', $ref1EmailNIA );  ?>";
				      document.getElementById("ref1EmailNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1EmailNIA_error'></div></div>
				</div>
				</div>
   
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<strong>Second:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2NameNIA' id='ref2NameNIA'  validate="validateStr"   required="true"   caption="name"   minlength="2"   maxlength="50"     tip="Enter Full Name of the reference"   value=''   />
				<?php if(isset($ref2NameNIA) && $ref2NameNIA!=""){ ?>
				  <script>
				      document.getElementById("ref2NameNIA").value = "<?php echo str_replace("\n", '\n', $ref2NameNIA );  ?>";
				      document.getElementById("ref2NameNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2NameNIA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Organization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2OrganizationNameNIA' id='ref2OrganizationNameNIA'  validate="validateStr"   required="true"   caption="organization"   minlength="2"   maxlength="50"     tip="Enter the organization name."   value=''   />
				<?php if(isset($ref2OrganizationNameNIA) && $ref2OrganizationNameNIA!=""){ ?>
				  <script>
				      document.getElementById("ref2OrganizationNameNIA").value = "<?php echo str_replace("\n", '\n', $ref2OrganizationNameNIA );  ?>";
				      document.getElementById("ref2OrganizationNameNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2OrganizationNameNIA_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Position: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2DesignationNIA' id='ref2DesignationNIA'  validate="validateStr"   required="true"   caption="position"   minlength="2"   maxlength="150"     tip="Enter the designation of this reference."   value=''   />
				<?php if(isset($ref2DesignationNIA) && $ref2DesignationNIA!=""){ ?>
				  <script>
				      document.getElementById("ref2DesignationNIA").value = "<?php echo str_replace("\n", '\n', $ref2DesignationNIA );  ?>";
				      document.getElementById("ref2DesignationNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2DesignationNIA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Tel. Nos: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2TelephoneNoNIA' id='ref2TelephoneNoNIA'  validate="validateInteger"   required="true"   caption="phone"   minlength="6"   maxlength="10"     tip="Enter the phone number of this reference"   value='' blurMethod="checkRefPhone(2);"  />
				<?php if(isset($ref2TelephoneNoNIA) && $ref2TelephoneNoNIA!=""){ ?>
				  <script>
				      document.getElementById("ref2TelephoneNoNIA").value = "<?php echo str_replace("\n", '\n', $ref2TelephoneNoNIA );  ?>";
				      document.getElementById("ref2TelephoneNoNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2TelephoneNoNIA_error'></div></div>
				</div>
				</div>

				<div class="clearFix spacer15"></div>

				<div class='additionalInfoLeftCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2EmailNIA' id='ref2EmailNIA'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="200"     tip="Enter the email of this reference."   value=''   />
				<?php if(isset($ref2EmailNIA) && $ref2EmailNIA!=""){ ?>
				  <script>
				      document.getElementById("ref2EmailNIA").value = "<?php echo str_replace("\n", '\n', $ref2EmailNIA );  ?>";
				      document.getElementById("ref2EmailNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2EmailNIA_error'></div></div>
				</div>
				</div>
				
               
                </div>
			</li>
			

			<li>
            	<div class="semesterDetailsBox">
				<strong>Third:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref3NameNIA' id='ref3NameNIA'  validate="validateStr"   required="true"   caption="name"   minlength="2"   maxlength="50"     tip="Enter Full Name of the reference"   value=''   />
				<?php if(isset($ref3NameNIA) && $ref3NameNIA!=""){ ?>
				  <script>
				      document.getElementById("ref3NameNIA").value = "<?php echo str_replace("\n", '\n', $ref3NameNIA );  ?>";
				      document.getElementById("ref3NameNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3NameNIA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Organization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref3OrganizationNameNIA' id='ref3OrganizationNameNIA'  validate="validateStr"   required="true"   caption="organization"   minlength="2"   maxlength="50"     tip="Enter the organization name."   value=''   />
				<?php if(isset($ref3OrganizationNameNIA) && $ref3OrganizationNameNIA!=""){ ?>
				  <script>
				      document.getElementById("ref3OrganizationNameNIA").value = "<?php echo str_replace("\n", '\n', $ref3OrganizationNameNIA );  ?>";
				      document.getElementById("ref3OrganizationNameNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3OrganizationNameNIA_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Position: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref3DesignationNIA' id='ref3DesignationNIA'  validate="validateStr"   required="true"   caption="position"   minlength="2"   maxlength="150"     tip="Enter the designation of this reference."   value=''   />
				<?php if(isset($ref3DesignationNIA) && $ref3DesignationNIA!=""){ ?>
				  <script>
				      document.getElementById("ref3DesignationNIA").value = "<?php echo str_replace("\n", '\n', $ref3DesignationNIA );  ?>";
				      document.getElementById("ref3DesignationNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3DesignationNIA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Tel. Nos: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref3TelephoneNoNIA' id='ref3TelephoneNoNIA'  validate="validateInteger"   required="true"   caption="phone"   minlength="6"   maxlength="10"     tip="Enter the phone number of this reference"   value='' blurMethod="checkRefPhone(2);"  />
				<?php if(isset($ref3TelephoneNoNIA) && $ref3TelephoneNoNIA!=""){ ?>
				  <script>
				      document.getElementById("ref3TelephoneNoNIA").value = "<?php echo str_replace("\n", '\n', $ref3TelephoneNoNIA );  ?>";
				      document.getElementById("ref3TelephoneNoNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3TelephoneNoNIA_error'></div></div>
				</div>
				</div>

				<div class="clearFix spacer15"></div>

				<div class='additionalInfoLeftCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref3EmailNIA' id='ref3EmailNIA'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="200"     tip="Enter the email of this reference."   value=''   />
				<?php if(isset($ref3EmailNIA) && $ref3EmailNIA!=""){ ?>
				  <script>
				      document.getElementById("ref3EmailNIA").value = "<?php echo str_replace("\n", '\n', $ref3EmailNIA );  ?>";
				      document.getElementById("ref3EmailNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3EmailNIA_error'></div></div>
				</div>
				</div>
				
               
                </div>
			</li>
			
			
			<li style="width:100%">
				<h3 class="upperCase">Personal Statement</h3>
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Personal Statement: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='personalStatementNIA' id='personalStatementNIA'   style="width:618px; height:74px; padding:5px"    tip="Explain in 250-300 words, why you want to join this course.."  validate="validateStr" caption="personal statement" minlength="250" maxlength="2000" required="true"></textarea>
				<?php if(isset($personalStatementNIA) && $personalStatementNIA!=""){ ?>
				  <script>
				      document.getElementById("personalStatementNIA").value = "<?php echo str_replace("\n", '\n', $personalStatementNIA );  ?>";
				      document.getElementById("personalStatementNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='personalStatementNIA_error'></div></div>
								<div class="spacer5 clearFix"></div>
				<p style=" color:#666666; font-style:italic">(Explain in 250-300 words, why you want to join this course.)</p>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>

			</li>

                        <li>
                                <h3 class=upperCase'>OTHERS</h3>
                                <label style='font-weight:normal'>Do you have any previous knowledge/qualification in insurance sector?: </label>
                                <div class='fieldBoxLarge'>
                                <select style="width:100px;" name='qualificationNIA' id='qualificationNIA' onmouseover="showTipOnline('Do you have any previous knowledge/qualification in insurance sector?',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="option">
                                <option value=''>Select</option>
                                <option value='Yes'>Yes</option>
                                <option value='No'>No</option>
                                </select>
                                <?php if(isset($qualificationNIA) && $qualificationNIA!=""){ ?>
                                <script>
                                var selObj = document.getElementById("qualificationNIA");
                                var A= selObj.options, L= A.length;
                                while(L){
                                        if (A[--L].value== "<?php echo $qualificationNIA;?>"){
                                        selObj.selectedIndex= L;
                                        L= 0;
                                        }
                                }
                                </script>
                                  <?php } ?>
                                <div style='display:none'><div class='errorMsg' id= 'qualificationNIA_error'></div></div>
                                </div>

                        </li>

                        <li>
                                <label style='font-weight:normal'>Are your parents or sibilings working in insurance industry (Insurance company, Insurance brokers, agents etc)? *: </label>
                                <div class='fieldBoxLarge'>
                                <select style="width:100px;" name='workingInsuranceNIA' id='workingInsuranceNIA' onmouseover="showTipOnline('Are your parents or sibilings working in insurance industry (Insurance company, Insurance brokers, agents etc)? *',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="option">
                                <option value=''>Select</option>
                                <option value='Yes'>Yes</option>
                                <option value='No'>No</option>
                                </select>
                                <?php if(isset($workingInsuranceNIA) && $workingInsuranceNIA!=""){ ?>
                                <script>
                                var selObj = document.getElementById("workingInsuranceNIA");
                                var A= selObj.options, L= A.length;
                                while(L){
                                        if (A[--L].value== "<?php echo $workingInsuranceNIA;?>"){
                                        selObj.selectedIndex= L;
                                        L= 0;
                                        }
                                }
                                </script>
                                  <?php } ?>
                                <div style='display:none'><div class='errorMsg' id= 'workingInsuranceNIA_error'></div></div>
                                </div>

                        </li>

                        <li>
                                <label style='font-weight:normal'>Is your work-experience related to insurance industry?: </label>
                                <div class='fieldBoxLarge'>
                                <select style="width:100px;" name='workExInsuranceNIA' id='workExInsuranceNIA' onmouseover="showTipOnline('Is your work-experience related to insurance industry?',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="option">
                                <option value=''>Select</option>
                                <option value='Yes'>Yes</option>
                                <option value='No'>No</option>
                                </select>
                                <?php if(isset($workExInsuranceNIA) && $workExInsuranceNIA!=""){ ?>
                                <script>
                                var selObj = document.getElementById("workExInsuranceNIA");
                                var A= selObj.options, L= A.length;
                                while(L){
                                        if (A[--L].value== "<?php echo $workExInsuranceNIA;?>"){
                                        selObj.selectedIndex= L;
                                        L= 0;
                                        }
                                }
                                </script>
                                  <?php } ?>
                                <div style='display:none'><div class='errorMsg' id= 'workExInsuranceNIA_error'></div></div>
                                </div>

                        </li>

			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Professional Qualifications and Certifications (CA/ACII/Actuaries/LOMA/III/NCFM/ etc) - Optional: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='pqcNIA' id='pqcNIA'   style="width:618px; height:74px; padding:5px"    tip="Please enter your Professional Qualifications and Certifications (CA/ACII/Actuaries/LOMA/III<br/>/NCFM/ etc) - Optional"  validate="validateStr" caption="Qualifications and Certifications" minlength="2" maxlength="2000" ></textarea>
				<?php if(isset($pqcNIA) && $pqcNIA!=""){ ?>
				  <script>
				      document.getElementById("pqcNIA").value = "<?php echo str_replace("\n", '\n', $pqcNIA );  ?>";
				      document.getElementById("pqcNIA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='pqcNIA_error'></div></div>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>
			</li>
			
			<li>
				<h3 class="upperCase">I came to know about this programme through</h3>
				<div class='additionalInfoLeftCol'>
				
				<div class="spacer5 clearFix"></div>
				<label>NIA Website: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'    name='heardFromNIAWebsite' id='heardFromNIAWebsite'   value='1' ></input><span ><span>&nbsp;&nbsp;

				<?php if(isset($heardFromNIAWebsite) && $heardFromNIAWebsite!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["heardFromNIAWebsite"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$heardFromNIAWebsite);
						    for($x=0;$x<count($arr);$x++){ ?>

							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				    }

				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromNIAWebsite_error'></div></div>
				</div>
				</div>
				</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Newspaper Advertisement in: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'   name='heardFromNewspaperAds' id='heardFromNewspaperAds'   value='1' ></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($heardFromNewspaperAds) && $heardFromNewspaperAds!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["heardFromNewspaperAds"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$heardFromNewspaperAds);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromNewspaperAds_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Friends and Relatives: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  name='heardFromFrndsAndRel' id='heardFromFrndsAndRel'   value='1' ></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($heardFromFrndsAndRel) && $heardFromFrndsAndRel!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["heardFromFrndsAndRel"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$heardFromFrndsAndRel);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromFrndsAndRel_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Coaching Institute: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'   name='heardFromCoachingInstitute' id='heardFromCoachingInstitute'   value='1' ></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($heardFromCoachingInstitute) && $heardFromCoachingInstitute!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["heardFromCoachingInstitute"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$heardFromCoachingInstitute);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromCoachingInstitute_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>MBA Portals: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'   name='heardFromMBAPortal' id='heardFromMBAPortal'   value='1' ></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($heardFromMBAPortal) && $heardFromMBAPortal!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["heardFromMBAPortal"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$heardFromMBAPortal);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromMBAPortal_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Any other please specify: </label>
				<div class='fieldBoxLarge' style="width:500px;">
				<span style="float:left;margin-right:20px;"><input type='checkbox'   name='heardFromOtherSource' id='heardFromOtherSource0'   value='1' onclick="toggleSourceOfInfo('heardFromOtherSource');"  ></input></span>



				<div class='fieldBoxLarge flLt' id="heardFromOtherSourceFieldLI" <?php if($heardFromOtherSource=='' || !isset($heardFromOtherSource)){?>style="display:none;"<?php }?>>
				<input type='text' name='heardFromOtherSourceField' id='heardFromOtherSourceField'   value=''  validate="validateStr"    caption="other source"   minlength="2"   maxlength="50"        tip="Please specify the source from where you heard about NIA." />
				<?php if(isset($heardFromOtherSourceField) && $heardFromOtherSourceField!=""){ ?>
				  <script>
				      document.getElementById("heardFromOtherSourceField").value = "<?php echo str_replace("\n", '\n', $heardFromOtherSourceField );  ?>";
				      document.getElementById("heardFromOtherSourceField").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromOtherSourceField_error'></div></div>
				</div>


				<?php if(isset($heardFromOtherSource) && $heardFromOtherSource!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["heardFromOtherSource"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$heardFromOtherSource);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromOtherSource_error'></div></div>
				</div>
				
				</div>
				<div style='display:none;margin-left:150px;'><div class="spacer10 clearFix"></div><div class='errorMsg' id= 'heardSourceField_error'></div></div>

			</li>

                        <li>
                                <div class='additionalInfoLeftCol' style="width:700px;">
                                <label>Upload a scanned copy of your signature: </label>
                                <div class='fieldBoxLarge' style="width:310px;">
                                <input type='file' name='userApplicationfile[]' id='signatureImageNIA'  size="30" required="true"  onmouseover="showTipOnline('Your signature is required with this form. If you do not have an electronic copy of your signature, sign on a paper and scan it. Then upload the scanned image file here.',this);"  onmouseout = "hidetip();" />
                                <input type='hidden' name='signatureImageNIAValid' value='extn:jpg,jpeg,png|size:5'>
                                <br/><span class="imageSizeInfo">(Image dimention :4.5 X 3.5 cm, Image Size : Maximum 2 MB)</span>
                                <div style='display:none'><div class='errorMsg' id= 'signatureImageNIA_error'></div></div>
                                </div>
                                </div>
                        </li>

		            	<div class="additionalInfoLeftCol" style="width:950px">
				<label style="font-weight:normal; padding-top:0">Disclaimer:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				I affirm that all the above statements are true and correct to the best of my knowledge. I understand any false or
misleading statement may constitute grounds for denial of admission or later expulsion. I have read and understood the details given in the Information Brochure.

				<div class="spacer10 clearFix"></div>
				<div>
				<input type='checkbox' name='agreeToTermsNIA' id='agreeToTermsNIA' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above">&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsNIA) && $agreeToTermsNIA!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsNIA"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsNIA);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsNIA_error'></div></div>


				</div>
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
 
 function checkRefPhone(objNumber){
      if(objNumber==1) sId = 2; else sId = 1;
      if($('ref'+objNumber+'TelephoneNoNIA').value == $('ref'+sId+'TelephoneNoNIA').value && $('ref'+objNumber+'TelephoneNoNIA').value!=''){
	  $('ref'+objNumber+'TelephoneNoNIA').value = '';
	  $('ref'+objNumber+'TelephoneNoNIA_error').innerHTML = 'The Phone number of references cannot be same';
	  $('ref'+objNumber+'TelephoneNoNIA_error').parentNode.style.display = '';
	  return false;
      }
      else{
	  $('ref'+objNumber+'TelephoneNoNIA_error').innerHTML = '';
	  $('ref'+objNumber+'TelephoneNoNIA_error').parentNode.style.display = 'none';
	  return true;
      }
  }

 function toggleSourceOfInfo(sourceKey){
	var cb = $(sourceKey+'0');
	if(cb.checked){
		$(sourceKey+'FieldLI').style.display = '';
		$(sourceKey+'Field').setAttribute('required','true');
        }
	else{
		$(sourceKey+'FieldLI').style.display = 'none';
		$(sourceKey+'Field').removeAttribute('required');
		$(sourceKey+'Field').value='';
	}
 }
 
 function validateDateOfBirth(obj){
    if (obj.value != '') {
      $("dateOfBirth").removeAttribute("disabled");
      $("dateOfBirth").value='';
      $("dateOfBirth_dateImg").style.pointerEvents="";
      $("dateOfBirth_error").innerHTML = '';
      $("dateOfBirth_error").parentNode.style.display = 'none';
    }else{
      $("dateOfBirth").setAttribute("disabled",'true');
      $("dateOfBirth").value='';
      $("dateOfBirth_dateImg").style.pointerEvents = "none";
      $("dateOfBirth_error").innerHTML = '';
      $("dateOfBirth_error").parentNode.style.display = 'none';
      
    }
  
 }
 
 if ($('categoryNIA').value != '') {
     $("dateOfBirth").removeAttribute("disabled");
     $("dateOfBirth_dateImg").style.pointerEvents = "";
 }
 
  </script>
<?php if($heardFromOtherSource=='1' && isset($heardFromOtherSource)){?>
<script>
	if($('heardFromOtherSourceField'))
	$('heardFromOtherSourceField').setAttribute('required','true');
</script>
<?php }?>
