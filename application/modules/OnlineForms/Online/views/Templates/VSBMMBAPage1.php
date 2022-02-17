<div class='formChildWrapper'>
	<div class='formSection'>
		 <ul>
			<li style="width:100%">
				<h3 class="upperCase">Entrance Test Appeared</h3>
                        <div class="clearFix"></div>
                        
                        <div class="additionalInfoLeftCol" style="width:100%">
			<label>Qualifying examinations:&nbsp;</label>
			<div class='fieldBoxLarge' style="width:600px">
                        <input name='courseVSBM[]' value='CAT' id='courseVSBM0' type="checkbox" onClick="toggleExamDetails('cat','0');" validate="validateCheckedGroup"   required="true"   caption="exam" /> CAT &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name='courseVSBM[]' value='MAT' id="courseVSBM1" type="checkbox" onClick="toggleExamDetails('mat','1');" validate="validateCheckedGroup"   required="true"   caption="exam"  /> MAT &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name='courseVSBM[]' value='XAT' id="courseVSBM2" type="checkbox" onClick="toggleExamDetails('xat','2');" validate="validateCheckedGroup"   required="true"   caption="exam" /> XAT &nbsp;&nbsp;&nbsp;&nbsp;  
                        <input name='courseVSBM[]'  value='CMAT' id="courseVSBM3" type="checkbox" onClick="toggleExamDetails('cmat','3');" validate="validateCheckedGroup"   required="true"   caption="exam" /> CMAT &nbsp;&nbsp;&nbsp;&nbsp;   
			<div class="clearFix"></div>
                    	<div style='display:none'><div class='errorMsg' id= 'courseVSBM_error'></div></div>
                    </div>

		   
			</div>
			</li>
			<li id="catDetails" style="display:none;">
				<h3>CAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date'/>
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
			
			
                <div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="Score"   minlength="2"   maxlength="10"    tip="Mention your score for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
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
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"         tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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

			<li id="matDetails" style="display:none;">
				<h3>MAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='matDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($matDateOfExaminationAdditional) && $matDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("matDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $matDateOfExaminationAdditional );  ?>";
				      document.getElementById("matDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'   validate="validateFloat"    caption="Score"   minlength="2"   maxlength="10"    tip="Mention your score for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"       tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter <b>NA.</b>"   value=''    allowNA='true'/>
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
			<li id="xatDetails" style="display:none;">
				<h3>XAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');"  caption='date' />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='xatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($xatDateOfExaminationAdditional) && $xatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $xatDateOfExaminationAdditional );  ?>";
				      document.getElementById("xatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"    caption="Score"   minlength="2"   maxlength="10"    tip="Mention your score for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
				      document.getElementById("xatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  allowNA='true'  />
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

			

			<li id="cmatDetails" style="display:none">
				<h3>CMAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='cmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($cmatDateOfExaminationAdditional) && $cmatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $cmatDateOfExaminationAdditional );  ?>";
				      document.getElementById("cmatDateOfExaminationAdditional").style.color = "";
				  </script>

				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
                <div class="spacer15 clearFix"></div>
			<div class='additionalInfoLeftCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat"    caption="Score"   minlength="2"   maxlength="10"    tip="Mention your score for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
				      document.getElementById("cmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatPercentileAdditionalVSBM' id='cmatPercentileAdditionalVSBM'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($cmatPercentileAdditionalVSBM) && $cmatPercentileAdditionalVSBM!=""){ ?>
				  <script>
				      document.getElementById("cmatPercentileAdditionalVSBM").value = "<?php echo str_replace("\n", '\n', $cmatPercentileAdditionalVSBM );  ?>";
				      document.getElementById("cmatPercentileAdditionalVSBM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatPercentileAdditionalVSBM_error'></div></div>
				</div>
				</div>
			</li>

			<?php if($action != 'updateScore'):?>
			
			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<h3 class="upperCase">Other</h3>
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
			<select style="width:115px" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
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
				<h3 class="upperCase">Disclaimer</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">			
a) I agree to abide by Admission team decisions on all matters regarding the application and have gone through all the rule and regulations of the college. In case of any discrepancy Institute has full right to cancel my candidature.
<div style='margin-top:10px;margin-bottom:10px;'>b) I will abide by all the rules & regulations of the college and maintain the discipline in all the respects. I also undertake that I will be attending the classes regularly & punctually and in case my attendance falls short, I shall not be allowed to appear in the university examinations.</div>
c) I undertake that I will maintain the disciple of the college and will have cordial relations with the other students, the office staff and faculty members.
<div style='margin-top:10px;margin-bottom:10px;'>d) I undertake to produce the final year graduation mark sheets latest by 31st October 2013, falling which institute will be free to cancel my admission, and in any case documents submitted/filled by me, found forged or are not accepted by the university on any ground, I will not claim any damage or any other claim against the college in any form.</div>
e) I agree that if admission is granted to me for employability program, I shall have to sign an Agreement/Bond with institution for employment after successful completion of the program.
<div style='margin-top:10px;margin-bottom:5px;'>I have not been convicted of any criminal offence nor have been released on bail in connection with a criminal case. No FIR or any such has been launched/Filled against me by any party.</div>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' caption='Please agree to terms stated above'  validate="validateChecked" checked  required="true"   name='VSBM_agreeToTerms[]' id='VSBM_agreeToTerms0'   value=''    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($VSBM_agreeToTerms) && $VSBM_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["VSBM_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$VSBM_agreeToTerms);
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
				
				<div style='display:none'><div class='errorMsg' id= 'VSBM_agreeToTerms0_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php endif; ?>
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


function toggleExamDetails(id,num){
	
	cb = $('courseVSBM'+num);
	if(cb.checked) {
		if($(id+'Details')) {
			$(id+'Details').style.display = '';
			if(id=='cat' || id=='mat' || id=='gmat' || id=='xat' || id=='atma'){
				$(id+'ScoreAdditional').setAttribute('required','true');
				$(id+'PercentileAdditional').setAttribute('required','true');
				$(id+'DateOfExaminationAdditional').setAttribute('required','true');
			}else if(id=='cmat'){
				$(id+'ScoreAdditional').setAttribute('required','true');
				$(id+'PercentileAdditionalVSBM').setAttribute('required','true');
				$(id+'DateOfExaminationAdditional').setAttribute('required','true');
			}
		}
	}
	else {
		if($(id+'Details')) {
				$(id+'Details').style.display = 'none';
		}		
		if(id=='cat' || id=='mat' || id=='gmat' || id=='xat' || id=='atma'){		
				if($(id+'DateOfExaminationAdditional')) {
					$(id+'DateOfExaminationAdditional').value = '';
				}
				if($(id+'ScoreAdditional')) {
					$(id+'ScoreAdditional').value = '';
				}
				if($(id+'PercentileAdditional')) {
					$(id+'PercentileAdditional').value = '';
				}
				$(id+'ScoreAdditional').removeAttribute('required');
				$(id+'PercentileAdditional').removeAttribute('required');
				$(id+'DateOfExaminationAdditional').removeAttribute('required');
		}else if(id=='cmat'){
				if($(id+'DateOfExaminationAdditional')) {
					$(id+'DateOfExaminationAdditional').value = '';
				}
				if($(id+'ScoreAdditional')) {
					$(id+'ScoreAdditional').value = '';
				}
				if($(id+'PercentileAdditionalVSBM')) {
					$(id+'PercentileAdditionalVSBM').value = '';
				}
				$(id+'ScoreAdditional').removeAttribute('required');
				$(id+'PercentileAdditionalVSBM').removeAttribute('required');
				$(id+'DateOfExaminationAdditional').removeAttribute('required');
		}
	}
}
  </script>

<?php
if( (isset($catDateOfExaminationAdditional) && $catDateOfExaminationAdditional!='')  || (isset($catPercentileAdditional) && $catPercentileAdditional!="") || (isset($catScoreAdditional) && $catScoreAdditional!="")){
      echo "<script>document.getElementById('courseVSBM0').checked = true; toggleExamDetails('cat','0');</script>";
}
if( (isset($matDateOfExaminationAdditional) && $matDateOfExaminationAdditional!='')  || (isset($matPercentileAdditional) && $matPercentileAdditional!="") || (isset($matScoreAdditional) && $matScoreAdditional!="")){
      echo "<script>document.getElementById('courseVSBM1').checked = true; toggleExamDetails('mat','1');</script>";
}
if( (isset($xatDateOfExaminationAdditional) && $xatDateOfExaminationAdditional!='')  || (isset($xatPercentileAdditional) && $xatPercentileAdditional!="") || (isset($xatScoreAdditional) && $xatScoreAdditional!="")){
      echo "<script>document.getElementById('courseVSBM2').checked = true; toggleExamDetails('xat','2');</script>";
}
if( (isset($cmatDateOfExaminationAdditional) && $cmatDateOfExaminationAdditional!='')  || (isset($cmatPercentileAdditionalVSBM) && $cmatPercentileAdditionalVSBM!="") || (isset($cmatScoreAdditional) && $cmatScoreAdditional!="")){
      echo "<script>document.getElementById('courseVSBM3').checked = true; toggleExamDetails('cmat','3');</script>";
}
?>