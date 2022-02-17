<script>
    function checkTestScore(obj){
        var key = obj.value.toLowerCase();
        var objects1 = new Array(key+"ScoreAdditional",key+"PercentileAdditional");

        if(obj){
            if( obj.checked == false ){
                $(key+'1').style.display = 'none';
                $(key+'2').style.display = 'none';
                //Set the required paramters when any Exam is hidden
                resetExamFields(objects1);
            }
            else{
                $(key+'1').style.display = '';
                $(key+'2').style.display = '';
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

    function resetExamFields(objectsArr){
        for(i=0;i<objectsArr.length;i++){
            document.getElementById(objectsArr[i]).removeAttribute('required');
            document.getElementById(objectsArr[i]+'_error').innerHTML = '';
            document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
        }
    }

</script>


<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>


            <li>
                <h3 class="upperCase">Qualifying Examination</h3>
                <div class='additionalInfoLeftCol' style="width:800px">
                    <label>TESTS: </label>
                    <div class='fieldBoxLarge' style="width:400px">
                        <input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='testNamesJaypee[]' id='testNamesJaypee0'   value='CAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                        <input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='testNamesJaypee[]' id='testNamesJaypee1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
                        <?php if(isset($testNamesJaypee) && $testNamesJaypee!=""){ ?>
                        <script>
                            objCheckBoxes = document.forms["OnlineForm"].elements["testNamesJaypee[]"];
                            var countCheckBoxes = objCheckBoxes.length;
                            for(var i = 0; i < countCheckBoxes; i++){
                                objCheckBoxes[i].checked = false;

                                <?php $arr = explode(",",$testNamesJaypee);
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

                        <div style='display:none'><div class='errorMsg' id= 'testNamesJaypee_error'></div></div>
                    </div>
                </div>
            </li>

            <li id="cat1" style="display:none;">
                <div class='additionalInfoLeftCol'>
                    <label>CAT Composite/Total Score: </label>
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
                        <input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' />
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

            <li id="cat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                    <label>CAT Result Valid Upto: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='catValidUptoJaypee' id='catValidUptoJaypee' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date upto which exam results are valid. If the date is not available, leave this field blank."     onmouseover="showTipOnline('Mention the date upto which exam results are valid. If the date is not available, leave this field blank.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catValidUptoJaypee'),'catValidUptoJaypee_dateImg','dd/MM/yyyy');" />
                        &nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='catValidUptoJaypee_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catValidUptoJaypee'),'catValidUptoJaypee_dateImg','dd/MM/yyyy'); return false;" />
                        <?php if(isset($catValidUptoJaypee) && $catValidUptoJaypee!=""){ ?>
                        <script>
                            document.getElementById("catValidUptoJaypee").value = "<?php echo str_replace("\n", '\n', $catValidUptoJaypee );  ?>";
                            document.getElementById("catValidUptoJaypee").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'catValidUptoJaypee_error'></div></div>
                    </div>
                </div>
            </li>
            <?php
            if(isset($testNamesJaypee) && $testNamesJaypee!="" && strpos($testNamesJaypee,'CAT')!==false){ ?>
                <script>
                    checkTestScore(document.getElementById('testNamesJaypee0'));
                </script>
                <?php
            }
            ?>

            <li id="mat1" style="display:none;">
                <div class='additionalInfoLeftCol'>
                    <label>MAT Composite/Total Score: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='matScoreAdditional' id='matScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
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
                    <label>MAT Percentile: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' />
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

            <li id="mat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                    <label>MAT Result Valid Upto: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='matValidUptoJaypee' id='matValidUptoJaypee' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date upto which exam results are valid. If the date is not available, leave this field blank."     onmouseover="showTipOnline('Mention the date upto which exam results are valid. If the date is not available, leave this field blank.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matValidUptoJaypee'),'matValidUptoJaypee_dateImg','dd/MM/yyyy');" />
                        &nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='matValidUptoJaypee_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matValidUptoJaypee'),'matValidUptoJaypee_dateImg','dd/MM/yyyy'); return false;" />
                        <?php if(isset($matValidUptoJaypee) && $matValidUptoJaypee!=""){ ?>
                        <script>
                            document.getElementById("matValidUptoJaypee").value = "<?php echo str_replace("\n", '\n', $matValidUptoJaypee );  ?>";
                            document.getElementById("matValidUptoJaypee").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'matValidUptoJaypee_error'></div></div>
                    </div>
                </div>
            </li>
            <?php
            if(isset($testNamesJaypee) && $testNamesJaypee!=""){
                $testsArray = explode(",",$testNamesJaypee);
                if(in_array("MAT",$testsArray)){  ?>
                <script>
                    checkTestScore(document.getElementById('testNamesJaypee1'));
                </script>
                    <?php
                }
            }
            ?>


            <?php if($action != 'updateScore'):?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Centre Preference: </label>
				<div class='fieldBoxLarge'>
				<select style="width:100px;" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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

			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
                    I declare that all my statements made in this application for admission are correct and complete. I also understand that I have
                    read the notes above and the submission of application does not automatically qualify me for GD/PI. The Application Fee is
                    non-refundable under any circumstances.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='Jaypee_agreeToTerms[]' id='Jaypee_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($Jaypee_agreeToTerms) && $Jaypee_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Jaypee_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Jaypee_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'Jaypee_agreeToTerms0_error'></div></div>
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


  </script>