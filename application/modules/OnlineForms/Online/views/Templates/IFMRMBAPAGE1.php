<script>
    
    function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	if(obj.value == "CAT")
        { 
	    var objects1 = new Array(key+"RollNumberAdditional",key+"DateOfExaminationAdditional",key+"_verbal_score_ifmr",key+"_verbal_Percentile_ifmr",key+"_quant_score_ifmr",key+"_quant_Percentile_ifmr",key+"ScoreAdditional",key+"PercentileAdditional");
	    
	}
	
	if(obj.value == "XAT")
        { 
	    var objects1 = new Array(key+"RollNumberAdditional",key+"DateOfExaminationAdditional",key+"_va_Percentile",key+"_ra_Percentile",key+"_di_Percentile",key+"PercentileAdditional");
	    
	}
	
	if(obj.value == "CMAT")
        { 
	    var objects1 = new Array(key+"RollNumberAdditional",key+"DateOfExaminationAdditional",key+"_quant_di",key+"_logical_reasoning",key+"_lang_comprehension",key+"_general_awareness",key+"ScoreAdditional");
	    
	}
	
	if(obj.value == "GMAT" || obj.value == "GRE")
        { 
	    var objects1 = new Array(key+"RollNumberAdditional",key+"DateOfExaminationAdditional",key+"_verbal",key+"_verbal_score",key+"_quant",key+"_quant_score",key+"_analytical",key+"_analytical_score",key+"ScoreAdditional",key+"PercentileAdditional");
	    
	}
	
	
	

        if(obj)
        {
	    if( obj.checked == false )
            {
		$(key+'1').style.display = 'none';
		$(key+'2').style.display = 'none';
		$(key+'3').style.display = 'none';
		if ($(key+'4') != null){
		    $(key+'4').style.display = 'none';
		}
		if ($(key+'5') != null){
		    $(key+'5').style.display = 'none';
		}
		resetExamFields(objects1);
	      }
	    else{
		$(key+'1').style.display = '';
		$(key+'2').style.display = '';
		$(key+'3').style.display = '';
		if ($(key+'4') != null){
		    $(key+'4').style.display = '';
		}
		if ($(key+'5') != null){
		    $(key+'5').style.display = '';
		}
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
</script>

<div class="formChildWrapper">
    <div class="formSection">
    <ul>
        <li>
             <h3 class='upperCase'>Qualifying Entrance Examination</h3>
            <div class='additionalInfoLeftCol'  style="width:1015px;">
	    <label>Aptitude Test Appeared: </label>
	    <div class='fieldBoxLarge'  style="width:641px;">
                <input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesIFMR[]' id='testNamesIFMR0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                <input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesIFMR[]' id='testNamesIFMR1'   value='XAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
                <input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesIFMR[]' id='testNamesIFMR2'   value='CMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
                <input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesIFMR[]' id='testNamesIFMR3'   value='GRE'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >GRE</span>&nbsp;&nbsp;
                <input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesIFMR[]' id='testNamesIFMR4'   value='GMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
                <?php if(isset($testNamesIFMR) && $testNamesIFMR!=""){ ?>
                <script>
                    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesIFMR[]"];
                    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++)
                    {
                        objCheckBoxes[i].checked = false;
                        <?php $arr = explode(",",$testNamesIFMR);
                            for($x=0;$x<count($arr);$x++){ ?>
			    if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>")
                            {
				objCheckBoxes[i].checked = true;
			    }
                        <?php
                            }
                        ?>
                    }
                </script>
                <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'testNamesIFMR_error'></div></div>
            </div>
	    </div>
        </li>
        
        
        <li id="cat1" style="display:none">
            
            <div class='additionalInfoLeftCol'>
	    <label>CAT(2014) Regn. No: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   validate="validateStr"   caption="roll number"   minlength="2"   maxlength="50"        tip="Mention your registration number for the exam 2014. If you have not appeared for this examination enter NA."   value=''  allowNA="true" />
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
	    <label>Date Taken: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
	
	
	<li id="cat2" style="display:none">
            <div class='additionalInfoLeftCol'>
	    <label>CAT VA & LR Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='cat_verbal_score_ifmr' id='cat_verbal_score_ifmr'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in verbal and logical reasoning. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
		<?php if(isset($cat_verbal_score_ifmr) && $cat_verbal_score_ifmr!=""){ ?>
		<script>
		    document.getElementById("cat_verbal_score_ifmr").value = "<?php echo str_replace("\n", '\n', $cat_verbal_score_ifmr );  ?>";
		    document.getElementById("cat_verbal_score_ifmr").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'cat_verbal_score_ifmr_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>CAT VA & LR Percentile: </label>
	    <div class='fieldBoxLarge'>
                <input type='text' name='cat_verbal_Percentile_ifmr' id='cat_verbal_Percentile_ifmr'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"   tip="Mention your percentile in verbal and logical reasoning. If you don't know your percentile, enter NA."   value=''  />
                <?php if(isset($cat_verbal_Percentile_ifmr) && $cat_verbal_Percentile_ifmr!=""){ ?>
                <script>
                    document.getElementById("cat_verbal_Percentile_ifmr").value = "<?php echo str_replace("\n", '\n', $cat_verbal_Percentile_ifmr );  ?>";
		    document.getElementById("cat_verbal_Percentile_ifmr").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'cat_verbal_Percentile_ifmr_error'></div></div>
	    </div>
	    </div>
        </li>
		
	<li id="cat3" style="display:none">
            <div class='additionalInfoLeftCol'>
	    <label>CAT QA & DI Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='cat_quant_score_ifmr' id='cat_quant_score_ifmr'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in Quant and Data interpretation. If the exam authorities have only declared percentiles for the exam, enter NA." allowNA="true"   value=''  />
		<?php if(isset($cat_quant_score_ifmr) && $cat_quant_score_ifmr!=""){ ?>
		<script>
		    document.getElementById("cat_quant_score_ifmr").value = "<?php echo str_replace("\n", '\n', $cat_quant_score_ifmr );  ?>";
		    document.getElementById("cat_quant_score_ifmr").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'cat_quant_score_ifmr_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>CAT QA & DI Percentile: </label>
	    <div class='fieldBoxLarge'>
                <input type='text' name='cat_quant_Percentile_ifmr' id='cat_quant_Percentile_ifmr'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"   tip="Mention your percentile in Quant and Data interpretation . If you don't know your percentile, enter NA."   value=''  />
                <?php if(isset($cat_quant_Percentile_ifmr) && $cat_quant_Percentile_ifmr!=""){ ?>
                <script>
                    document.getElementById("cat_quant_Percentile_ifmr").value = "<?php echo str_replace("\n", '\n', $cat_quant_Percentile_ifmr );  ?>";
		    document.getElementById("cat_quant_Percentile_ifmr").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'cat_quant_Percentile_ifmr_error'></div></div>
	    </div>
	    </div>
        </li>
        
        			
	<li id="cat4" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
            <div class='additionalInfoLeftCol'>
	    <label>Total Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"   tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
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
	    <label>Total Percentile: </label>
	    <div class='fieldBoxLarge'>
                <input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"  tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
        

	    
	    
	<li id="xat1" style="display:none;">
	    
	<div class='additionalInfoLeftCol'>
	<label>XAT(2015) REGN NO: </label>
	<div class='fieldBoxLarge'>
	    <input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Registration number for the exam 2015.If you do not have the roll number, enter NA."  allowNA='true'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   value=''   />
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
	<label>XAT Date: </label>
	<div class='fieldBoxLarge'>
	    <input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"   tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
	</li>
	
	<li id="xat2" style="display:none; border-bottom:1px ;">
	    <div class='additionalInfoLeftCol'>
	    <label>VA(english) Percentile: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='xat_va_Percentile' id='xat_va_Percentile'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"       tip="Mention your verbal percentile in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
		<?php if(isset($xat_va_Percentile) && $xat_va_Percentile!=""){ ?>
		<script>
		    document.getElementById("xat_va_Percentile").value = "<?php echo str_replace("\n", '\n', $xat_va_Percentile );  ?>";
		    document.getElementById("xat_va_Percentile").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'xat_va_Percentile_error'></div></div>
	    </div>
	    </div>
				
	    <div class="additionalInfoRightCol">
	    <label>RA(Decision Making) Percentile: </label>
	    <div class='float_L'>
		<input type='text' name='xat_ra_Percentile' id='xat_ra_Percentile'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"   tip="Mention your percentile of decision making part. If you don't know your percentile, enter NA."   value=''  />
		<?php if(isset($xat_ra_Percentile) && $xat_ra_Percentile!=""){ ?>
		<script>
		    document.getElementById("xat_ra_Percentile").value = "<?php echo str_replace("\n", '\n', $xat_ra_Percentile );  ?>";
		    document.getElementById("xat_ra_Percentile").style.color = "";
		</script>
		<?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'xat_ra_Percentile_error'></div></div>
	    </div>
	    </div>
	</li>

	<li id="xat3" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
	    <div class='additionalInfoLeftCol'>
	    <label>DI(quant) Percentile: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='xat_di_Percentile' id='xat_di_Percentile'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"    tip="Mention your Di percentile in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
		<?php if(isset($xat_di_Percentile) && $xat_di_Percentile!=""){ ?>
		<script>
		    document.getElementById("xat_di_Percentile").value = "<?php echo str_replace("\n", '\n', $xat_di_Percentile );  ?>";
		    document.getElementById("xat_di_Percentile").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'xat_di_Percentile_error'></div></div>
	    </div>
	    </div>
				
	    <div class="additionalInfoRightCol">
	    <label>Total Percentile: </label>
	    <div class='float_L'>
		<input class="text"  type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."  value=''  />
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
		
				

	
	<li id="cmat1" style="display:none;">
	    
	    <div class="additionalInfoLeftCol">
            <label>CMAT REGN NO: </label>
            <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your registration number for the CMAT(sept.2014) exam. If you do not have the roll number, enter NA"  value=''  />
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
	    <label>CMAT Date: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"   tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
	</li>
	
	
	<li id="cmat2" style="display:none;">
	    <div class='additionalInfoLeftCol'>
	    <label>Quant & Data Interpretation:</label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='cmat_qaunt_di' id='cmat_qaunt_di'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in quant and data interpretation. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA = 'true' />
		<?php if(isset($cmat_qaunt_di) && $cmat_qaunt_di!=""){ ?>
		<script>
		    document.getElementById("cmat_qaunt_di").value = "<?php echo str_replace("\n", '\n', $cmat_qaunt_di );  ?>";
		    document.getElementById("cmat_qaunt_di").style.color = "";
		</script>
		<?php } ?>
				
	    <div style='display:none'><div class='errorMsg' id= 'cmat_qaunt_di_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label> Logical Reasoning:</label>
	    <div class='fieldBoxLarge'>
	    <input type='text' name='cmat_logical_reasoning' id='cmat_logical_reasoning'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention your score in logical reasoning. If you don't know your percentile, you can leave this field blank, enter NA."   value=''  allowNA = 'true' />
	    <?php if(isset($cmat_logical_reasoning) && $cmat_logical_reasoning!=""){ ?>
	    <script>
		document.getElementById("cmat_logical_reasoning").value = "<?php echo str_replace("\n", '\n', $cmat_logical_reasoning);  ?>";
		document.getElementById("cmat_logical_reasoning").style.color = "";
	    </script>
	    <?php } ?>
				
	    <div style='display:none'><div class='errorMsg' id= 'cmat_logical_reasoning_error'></div></div>
	    </div>
	    </div>
	</li>
		
	
	<li id="cmat3" style="display:none;">
	    <div class='additionalInfoLeftCol'>
	    <label>Language Comprehension:</label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='cmat_lang_comprehension' id='cmat_lang_comprehension'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in language comprehension. If the exam authorities have only declared percentiles for the exam, enter NA."  value=''    allowNA = 'true' />
		<?php if(isset($cmat_lang_comprehension) && $cmat_lang_comprehension!=""){ ?>
		<script>
		    document.getElementById("cmat_lang_comprehension").value = "<?php echo str_replace("\n", '\n', $cmat_lang_comprehension);  ?>";
		    document.getElementById("cmat_lang_comprehension").style.color = "";
		</script>
		<?php } ?>
				
	    <div style='display:none'><div class='errorMsg' id= 'cmat_lang_comprehension_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>General Awareness:</label>
	    <div class='fieldBoxLarge'>
	    <input type='text' name='cmat_general_awareness' id='cmat_general_awareness'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention your score in general awareness. If you don't know your percentile, you can leave this field blank, enter NA."  value=''    allowNA = 'true' />
	    <?php if(isset($cmat_general_awareness) && $cmat_general_awareness!=""){ ?>
	    <script>
		document.getElementById("cmat_general_awareness").value = "<?php echo str_replace("\n", '\n', $cmat_general_awareness);  ?>";
		document.getElementById("cmat_general_awareness").style.color = "";
	    </script>
	    <?php } ?>
				
	    <div style='display:none'><div class='errorMsg' id= 'cmat_general_awareness_error'></div></div>
	    </div>
	    </div>
	</li>
	
	
	<li id="cmat4" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
	    <div class='additionalInfoLeftCol'>
	    <label>Total Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA = 'true' />
		<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
		<script>
		    document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
		    document.getElementById("cmatScoreAdditional").style.color = "";
		</script>
		<?php } ?>
				
	    <div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
	    </div>
	    </div>
	</li>


	    
	<li id="gre1" style="display:none;">
	    <div class='additionalInfoLeftCol'>
	    <label>GRE REGN NO: </label>
	    <div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='greRollNumberAdditional' id='greRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     tip="Mention your Registration number for the exam taken in last five years.If you do not have the roll number, enter NA." allowNA='true' value=''   />
		<?php if(isset($greRollNumberAdditional) && $greRollNumberAdditional!=""){ ?>
		<script>
		    document.getElementById("greRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $greRollNumberAdditional );  ?>";
		    document.getElementById("greRollNumberAdditional").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'greRollNumberAdditional_error'></div></div>
	    </div>
	    </div>

	    <div class='additionalInfoRightCol'>
	    <label>GRE Date: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='greDateOfExaminationAdditional' id='greDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"  tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('greDateOfExaminationAdditional'),'greDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
		&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='greDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('greDateOfExaminationAdditional'),'greDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
		<?php if(isset($greDateOfExaminationAdditional) && $greDateOfExaminationAdditional!=""){ ?>
		<script>
		    document.getElementById("greDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $greDateOfExaminationAdditional );  ?>";
		    document.getElementById("greDateOfExaminationAdditional").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'greDateOfExaminationAdditional_error'></div></div>
	    </div>
	    </div>
	</li>
	
	<li id="gre2" style="display:none; ">
	    <div class='additionalInfoLeftCol'>
	    <label>GRE Verbal: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gre_verbal' id='gre_verbal'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"        tip="Mention your percentile in verbal section. If you dont know your percentile for this section, enter NA."   value='' allowNA="true" />
		<?php if(isset($gre_verbal) && $gre_verbal!=""){ ?>
		<script>
		    document.getElementById("gre_verbal").value = "<?php echo str_replace("\n", '\n', $gre_verbal );  ?>";
		    document.getElementById("gre_verbal").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gre_verbal_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>GRE Verbal Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gre_verbal_score' id='gre_verbal_score'  validate="validateFloat" caption="score"   minlength="1"   maxlength="5"     tip="Mention your verbal score in the exam. If you don't know your score, you can leave this field blank, enter NA."   value=''   allowNA = 'true' />
		<?php if(isset($gre_verbal_score) && $gre_verbal_score!=""){ ?>
		<script>
		    document.getElementById("gre_verbal_score").value = "<?php echo str_replace("\n", '\n', $gre_verbal_score );  ?>";
		    document.getElementById("gre_verbal_score").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gre_verbal_score_error'></div></div>
	    </div>
	    </div>
	</li>
	
	<li id="gre3" style="display:none; ">
	    <div class='additionalInfoLeftCol'>
	    <label>GRE Quantitative: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gre_quant' id='gre_quant'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"        tip="Mention your percentile in quantitative section. If you don't know percentile for this section, enter NA."   value='' allowNA="true" />
		<?php if(isset($gre_quant) && $gre_quant!=""){ ?>
		<script>
		    document.getElementById("gre_quant").value = "<?php echo str_replace("\n", '\n', $gre_quant );  ?>";
		    document.getElementById("gre_quant").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gre_quant_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>GRE Quantitative Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gre_quant_score' id='gre_quant_score'  validate="validateFloat" caption="score"   minlength="1"   maxlength="5"     tip="Mention your quantitative score in the exam. If you don't know your score, you can leave this field blank, enter NA."   value=''   allowNA = 'true' />
		<?php if(isset($gre_quant_score) && $gre_quant_score!=""){ ?>
		<script>
		    document.getElementById("gre_quant_score").value = "<?php echo str_replace("\n", '\n', $gre_quant_score );  ?>";
		    document.getElementById("gre_quant_score").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gre_quant_score_error'></div></div>
	    </div>
	    </div>
	</li>
	
	
	<li id="gre4" style="display:none; ">
	    <div class='additionalInfoLeftCol'>
	    <label>GRE Analytical Writing: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gre_analytical' id='gre_analytical'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"        tip="Mention your percentile in analytical section. If you don't know your percentile for this section, enter NA."   value=''  allowNA="true" />
		<?php if(isset($gre_analytical) && $gre_analytical!=""){ ?>
		<script>
		    document.getElementById("gre_analytical").value = "<?php echo str_replace("\n", '\n', $gre_analytical);  ?>";
		    document.getElementById("gre_analytical").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gre_analytical_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>GRE Analytical Writing Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gre_analytical_score' id='gre_analytical_score'  validate="validateFloat" caption="score"   minlength="1"   maxlength="5"     tip="Mention your analytical score in the exam. If you don't know your score, you can leave this field blank, enter NA."   value=''  allowNA = 'true' />
		<?php if(isset($gre_analytical_score) && $gre_analytical_score!=""){ ?>
		<script>
		    document.getElementById("gre_analytical_score").value = "<?php echo str_replace("\n", '\n', $gre_analytical_score );  ?>";
		    document.getElementById("gre_analytical_score").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gre_analytical_score_error'></div></div>
	    </div>
	    </div>
	</li>
	
	<li id="gre5" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px; ">
	    <div class='additionalInfoLeftCol'>
	    <label>GRE Total Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='greScoreAdditional' id='greScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  value=''  allowNA="true" />
		<?php if(isset($greScoreAdditional) && $greScoreAdditional!=""){ ?>
		<script>
		    document.getElementById("greScoreAdditional").value = "<?php echo str_replace("\n", '\n', $greScoreAdditional );  ?>";
		    document.getElementById("greScoreAdditional").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'greScoreAdditional_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>GRE Total Percentile: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='grePercentileAdditional' id='grePercentileAdditional'  validate="validateFloat" caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''  allowNA = 'true' />
		<?php if(isset($grePercentileAdditional) && $grePercentileAdditional!=""){ ?>
		<script>
		    document.getElementById("grePercentileAdditional").value = "<?php echo str_replace("\n", '\n', $grePercentileAdditional );  ?>";
		    document.getElementById("grePercentileAdditional").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'grePercentileAdditional_error'></div></div>
	    </div>
	    </div>
	</li>
			
	    
	<li id="gmat1" style="display:none;">
	    <div class='additionalInfoLeftCol'>
	    <label>GMAT REGN NO: </label>
	    <div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     tip="Mention your Registration number for the exam taken in last five years.If you do not have the roll number, enter NA." allowNA='true' value=''   />
		<?php if(isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!=""){ ?>
		<script>
		    document.getElementById("gmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAdditional );  ?>";
		    document.getElementById("gmatRollNumberAdditional").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAdditional_error'></div></div>
	    </div>
	    </div>

	    <div class='additionalInfoRightCol'>
	    <label>GMAT Date: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"   tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
		&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='gmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
		<?php if(isset($gmatDateOfExaminationAdditional) && $gmatDateOfExaminationAdditional!=""){ ?>
		<script>
		    document.getElementById("gmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $gmatDateOfExaminationAdditional );  ?>";
		    document.getElementById("gmatDateOfExaminationAdditional").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmatDateOfExaminationAdditional_error'></div></div>
	    </div>
	    </div>
	</li>
	
	<li id="gmat2" style="display:none; ">
	    <div class='additionalInfoLeftCol'>
	    <label>GMAT Verbal: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmat_verbal' id='gmat_verbal'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in verbal section. If you don't know percentiles for this section, enter NA."   value=''  allowNA="true" />
		<?php if(isset($gmat_verbal) && $gmat_verbal!=""){ ?>
		<script>
		    document.getElementById("gmat_verbal").value = "<?php echo str_replace("\n", '\n', $gmat_verbal );  ?>";
		    document.getElementById("gmat_verbal").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmat_verbal_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>GMAT Verbal Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmat_verbal_score' id='gmat_verbal_score'  validate="validateFloat" caption="score"   minlength="1"   maxlength="5"     tip="Mention your verbal score in the exam. If you don't know your score, you can leave this field blank, enter NA."   value=''  allowNA = 'true' />
		<?php if(isset($gmat_verbal_score) && $gmat_verbal_score!=""){ ?>
		<script>
		    document.getElementById("gmat_verbal_score").value = "<?php echo str_replace("\n", '\n', $gmat_verbal_score );  ?>";
		    document.getElementById("gmat_verbal_score").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmat_verbal_score_error'></div></div>
	    </div>
	    </div>
	</li>
	
	<li id="gmat3" style="display:none; ">
	    <div class='additionalInfoLeftCol'>
	    <label>GMAT Quantitative: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmat_quant' id='gmat_quant'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"        tip="Mention your percentile in quantitative section. If you don't know percentile for this section, enter NA.."   value=''   allowNA="true" />
		<?php if(isset($gmat_quant) && $gmat_quant!=""){ ?>
		<script>
		    document.getElementById("gmat_quant").value = "<?php echo str_replace("\n", '\n', $gmat_quant );  ?>";
		    document.getElementById("gmat_quant").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmat_quant_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>GMAT Quantitative Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmat_quant_score' id='gmat_quant_score'  validate="validateFloat" caption="score"   minlength="1"   maxlength="5"     tip="Mention your Quantitative score in the exam. If you don't know your score, you can leave this field blank, enter NA."   value=''     allowNA = 'true' />
		<?php if(isset($gmat_quant_score) && $gmat_quant_score!=""){ ?>
		<script>
		    document.getElementById("gmat_quant_score").value = "<?php echo str_replace("\n", '\n', $gmat_quant_score );  ?>";
		    document.getElementById("gmat_quant_score").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmat_quant_score_error'></div></div>
	    </div>
	    </div>
	</li>
	
	
	<li id="gmat4" style="display:none; ">
	    <div class='additionalInfoLeftCol'>
	    <label>GMAT Analytical Writing: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmat_analytical' id='gmat_analytical'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in analytical section. If you don't know your percentile for this section, enter NA."   value=''  allowNA="true" />
		<?php if(isset($gmat_analytical) && $gmat_analytical!=""){ ?>
		<script>
		    document.getElementById("gmat_analytical").value = "<?php echo str_replace("\n", '\n', $gmat_analytical );  ?>";
		    document.getElementById("gmat_analytical").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmat_analytical_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>GMAT Analytical Writing Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmat_analytical_score' id='gmat_analytical_score'  validate="validateFloat" caption="score"   minlength="1"   maxlength="5"     tip="Mention your analytical score in the exam. If you don't know your score, you can leave this field blank, enter NA."   value=''   allowNA = 'true' />
		<?php if(isset($gmat_analytical_score) && $gmat_analytical_score!=""){ ?>
		<script>
		    document.getElementById("gmat_analytical_score").value = "<?php echo str_replace("\n", '\n', $gmat_analytical_score );  ?>";
		    document.getElementById("gmat_analytical_score").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmat_analytical_score_error'></div></div>
	    </div>
	    </div>
	</li>
	
	<li id="gmat5" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px; ">
	    <div class='additionalInfoLeftCol'>
	    <label>GMAT Total Score: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  value=''  allowNA="true" />
		<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
		<script>
		    document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
		    document.getElementById("gmatScoreAdditional").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
	    </div>
	    </div>
				
	    <div class='additionalInfoRightCol'>
	    <label>GMAT Total Percentile: </label>
	    <div class='fieldBoxLarge'>
		<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat" caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''   allowNA = 'true' />
		<?php if(isset($gmatPercentileAdditional) && $gmatPercentileAdditional!=""){ ?>
		<script>
		    document.getElementById("gmatPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $gmatPercentileAdditional );  ?>";
		    document.getElementById("gmatPercentileAdditional").style.color = "";
		</script>
		<?php } ?>
				
		<div style='display:none'><div class='errorMsg' id= 'gmatPercentileAdditional_error'></div></div>
	    </div>
	    </div>
	</li>
			
        
    <?php if($action != 'updateScore'):?>
    
	<li>
            <h3 class="upperCase">Educational Information(Additional)</h3>
        </li>
	<li>
	    <div class="additionalInfoLeftCol">
		<label style='font-weight:normal'>Major Subjects in 10th Class:</label>
		<div class='fieldBoxLarge'>
		    <input class="textboxlarge" type='text' name='subjects_10_ifmr' id='subjects_10_ifmr' onmouseover="showTipOnline('Enter your major subjects in 10th standard.',this);" onmouseout='hidetip();' validate="validateStr" minlength="2" maxlength="50" required='true' caption='Major subjects in 10th class' tip="Enter your major subjects in 10th standard">
		    <?php if(isset($subjects_10_ifmr) && $subjects_10_ifmr!=""){ ?>
		    <script>
		        document.getElementById("subjects_10_ifmr").value = "<?php echo str_replace("\n", '\n', $subjects_10_ifmr );  ?>";
		        document.getElementById("subjects_10_ifmr").style.color = "";
		    </script>
		    <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'subjects_10_ifmr_error'></div></div>
		</div>
 	    </div>
	    
	    <div class="additionalInfoRightCol">
		<label style='font-weight:normal'>Year(in which you started your 10th standard):</label>
		<div class='fieldBoxLarge'>
		    <input type='text' name='year_10_ifmr' id='year_10_ifmr' onmouseover="showTipOnline('Mention the year in which you started your 10th standard.',this);" onmouseout='hidetip();' validate="validateInteger" minlength="4" maxlength="4" required='true' caption='Year' tip="Mention the year in which you started your 10th standard.">
		    <?php if(isset($year_10_ifmr) && $year_10_ifmr!=""){ ?>
		    <script>
		        document.getElementById("year_10_ifmr").value = "<?php echo str_replace("\n", '\n', $year_10_ifmr );  ?>";
		        document.getElementById("year_10_ifmr").style.color = "";
		    </script>
		    <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'year_10_ifmr_error'></div></div>
		</div>
 	    </div>
	
	</li>
	
	<li>
	    <div class="additionalInfoLeftCol">
		<label style='font-weight:normal'>Major Subjects in 12th Class:</label>
		<div class='fieldBoxLarge'>
		    <input class="textboxlarge" type='text' name='subjects_12_ifmr' id='subjects_12_ifmr' onmouseover="showTipOnline('Enter your major subjects in 12th standard.',this);" onmouseout='hidetip();' validate="validateStr" minlength="2" maxlength="50" required='true' caption='Major subjects in 12th class' tip="Enter your major subjects in 12th standard.">
		    <?php if(isset($subjects_12_ifmr) && $subjects_12_ifmr!=""){ ?>
		    <script>
		        document.getElementById("subjects_12_ifmr").value = "<?php echo str_replace("\n", '\n', $subjects_12_ifmr );  ?>";
		        document.getElementById("subjects_12_ifmr").style.color = "";
		    </script>
		    <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'subjects_12_ifmr_error'></div></div>
		</div>
 	    </div>
	    
	    <div class="additionalInfoRightCol">
		<label style='font-weight:normal'>Year(in which you started your 12th standard):</label>
		<div class='fieldBoxLarge'>
		    <input type='text' name='year_12_ifmr' id='year_12_ifmr' onmouseover="showTipOnline('Mention the year in which you started your 12th standard.',this);" onmouseout='hidetip();' validate="validateInteger" minlength="4" maxlength="4" required='true' caption='Year' tip="Mention the year in which you started your 12th standard.">
		    <?php if(isset($year_12_ifmr) && $year_12_ifmr!=""){ ?>
		    <script>
		        document.getElementById("year_12_ifmr").value = "<?php echo str_replace("\n", '\n', $year_12_ifmr );  ?>";
		        document.getElementById("year_12_ifmr").style.color = "";
		    </script>
		    <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'year_12_ifmr_error'></div></div>
		</div>
 	    </div>
	    
	</li>
	
	<li>
	    
	    <div class="additionalInfoLeftCol">
		<label style='font-weight:normal'>Major Subjects in Graduation:</label>
		<div class='fieldBoxLarge'>
		    <input class="textboxlarge" type='text' name='subjects_UG_ifmr' id='subjects_UG_ifmr' onmouseover="showTipOnline('Enter your major subjects in graduation.',this);" onmouseout='hidetip();' validate="validateStr" minlength="2" maxlength="50" required='true' caption='Major subjects in graduation' tip="Enter your major subjects in graduation.">
		    <?php if(isset($subjects_UG_ifmr) && $subjects_UG_ifmr!=""){ ?>
		    <script>
		        document.getElementById("subjects_UG_ifmr").value = "<?php echo str_replace("\n", '\n', $subjects_UG_ifmr );  ?>";
		        document.getElementById("subjects_UG_ifmr").style.color = "";
		    </script>
		    <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'subjects_UG_ifmr_error'></div></div>
		</div>
 	    </div>
	    
	    <div class="additionalInfoRightCol">
		<label style='font-weight:normal'>Year(in which you started your Graduation):</label>
		<div class='fieldBoxLarge'>
		    <input type='text' name='year_UG_ifmr' id='year_UG_ifmr' onmouseover="showTipOnline('Mention the year in which you started your graduation.',this);" onmouseout='hidetip();' validate="validateInteger" minlength="4" maxlength="4" required='true' caption='Year' tip="Mention the year in which you started your graduation.">
		    <?php if(isset($year_UG_ifmr) && $year_UG_ifmr!=""){ ?>
		    <script>
		        document.getElementById("year_UG_ifmr").value = "<?php echo str_replace("\n", '\n', $year_UG_ifmr );  ?>";
		        document.getElementById("year_UG_ifmr").style.color = "";
		    </script>	
		    <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'year_UG_ifmr_error'></div></div>
		</div>
 	    </div>
	</li>
	
	<li>
				<div class='additionalInfoLeftCol'>
				<label>Are you a rank holder in graduation?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"  required="true"   caption="yes or no"  name='ifmr_rankGrad' id='ifmr_rankGrad0'   value='No' title="rank"   onmouseover="showTipOnline('Are you a rank holder in graduation? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Are you a rank holder in graduation? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='ifmr_rankGrad' id='ifmr_rankGrad1'   value='Yes' title="rank"   onmouseover="showTipOnline('Are you a rank holder in graduation? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Are you a rank holder in graduation? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<?php if(isset($ifmr_rankGrad) && $ifmr_rankGrad!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["ifmr_rankGrad"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $ifmr_rankGrad;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ifmr_rankGrad_error'></div></div>
				</div>
				</div>
				
				<div class="additionalInfoRightCol">
				<label style='font-weight:normal'>If Yes, enter your Rank:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ifmr_rankDetail' id='ifmr_rankDetail' onmouseover="showTipOnline('Enter the rank in graduation.',this);" onmouseout='hidetip();' validate="validateInteger" minlength="1" maxlength="5"  caption='Rank'>
					<?php if(isset($ifmr_rankDetail) && $ifmr_rankDetail!=""){ ?>
					<script>
					    document.getElementById("ifmr_rankDetail").value = "<?php echo str_replace("\n", '\n', $ifmr_rankDetail);  ?>";
		   		    </script>	
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'ifmr_rankDetail_error'></div></div>
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
					    $majorSub = 'otherCourseMajorSub_mul_'.$otherCourseId;
					    $majorSubVal = $$majorSub;
					    $rankPg = 'otherCourseRankPg_mul_'.$otherCourseId;
					    $rankPgVal = $$rankPg;
					    $rankDetailPg = 'otherCourseRankDetailPg_mul_'.$otherCourseId;
					    $rankDetailPgVal = $$rankDetailPg;
					    
					    $i++;
    
			    ?>
			    
	    <li>
				
				
				<div class='additionalInfoLeftCol'>
				<label> <?php echo $otherCourseName;?> Major Subjects </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $majorSub;?>' id='<?php echo $majorSub;?>'    validate="validateStr"   required="true"   caption="<?php echo $otherCourseName;?>: State"   minlength="1"   maxlength="50"          tip="Enter the major subjects in <?=html_escape($otherCourseName);?>"   value=''   allowNA='yes'/>
				<?php if(isset($majorSubVal) && $majorSubVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $majorSub;?>").value = "<?php echo str_replace("\n", '\n', $majorSubVal );  ?>";
				      document.getElementById("<?php echo $majorSub;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $majorSub;?>_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label> <?php echo $otherCourseName;?> Starting Year </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $soa;?>' id='<?php echo $soa;?>'    validate="validateInteger"   required="true"   caption="<?php echo $otherCourseName;?>: Starting Year"   minlength="4"   maxlength="4"          tip="Enter the starting year of <?=html_escape($otherCourseName);?>"   value=''   allowNA='yes'/>
				<?php if(isset($soaVal) && $soaVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $soa;?>").value = "<?php echo str_replace("\n", '\n', $soaVal );  ?>";
				      document.getElementById("<?php echo $soa;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $soa;?>_error'></div></div>
				</div>
				</div>
	    </li>
	    
	    	<li>
				<div class='additionalInfoLeftCol'>
				<label>Are you a rank holder in <?php echo $otherCourseName;?>?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"  required="true"   caption="yes or no"  name='<?php echo $rankPg;?>' id='<?php echo $rankPg.'0'; ?>'   value='No' title="rank"   onmouseover="showTipOnline('Are you a rank holder in <?=html_escape($otherCourseName);?>? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Are you a rank holder in <?=html_escape($otherCourseName);?>? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='<?php echo $rankPg;?>' id='<?php echo $rankPg.'1'; ?>'   value='Yes' title="rank"   onmouseover="showTipOnline('Are you a rank holder in <?=html_escape($otherCourseName);?>? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Are you a rank holder in <?=html_escape($otherCourseName);?>? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<?php if(isset($rankPgVal) && $rankPgVal!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["<?php echo $rankPg;?>"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $rankPgVal;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $rankPg;?>_error'></div></div>
				</div>
				</div>
				
				<div class="additionalInfoRightCol">
				<label style='font-weight:normal'>If Yes, enter your Rank:</label>
				    <div class='fieldBoxLarge'>
					<input type='text' name='<?php echo $rankDetailPg;?>' id='<?php echo $rankDetailPg;?>' onmouseover="showTipOnline('Enter the rank in <?=html_escape($otherCourseName);?>.',this);" onmouseout='hidetip();' validate="validateInteger" minlength="1" maxlength="5"  caption='Rank'>
					<?php if(isset($rankDetailPgVal) && $rankDetailPgVal!=""){ ?>
					<script>
					    document.getElementById("<?php echo $rankDetailPg;?>").value = "<?php echo str_replace("\n", '\n', $rankDetailPgVal );  ?>";
		   		    </script>	
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= '<?php echo $rankDetailPg;?>_error'></div></div>
				    </div>
				</div>
	</li>
	    
	    <?php }} ?>
	    
	    
	<li>
            <h3 class="upperCase">Work Experience</h3>
        </li>
	<li>
				<div class='additionalInfoLeftCol'>
				<label>Do you have work experience?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Imfr_workExp' id='Imfr_workExp0'   value='No' title="Do you have any Work Experience?"   onmouseover="showTipOnline('Do you have any Work Experience? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you have any Work Experience? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Imfr_workExp' id='Imfr_workExp1'   value='Yes' title="Do you have any Work Experience?"   onmouseover="showTipOnline('Do you have any Work Experience? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you have any Work Experience? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<?php if(isset($Imfr_workExp) && $Imfr_workExp!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Imfr_workExp"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Imfr_workExp;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Imfr_workExp_error'></div></div>
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
					$workExpInMonthName = 'workExpInMonth'.$workCompanyKey;
					$workExpInMonthValue = $$workExpInMonthName;
					$workExpTotalInMonthName = 'workExpTotalInMonth'.$workCompanyKey;
					$workExpTotalInMonthValue = $$workExpTotalInMonthName;
					$j++;
					
			?>


		      <li >
				<div class='additionalInfoLeftCol'>
				<label>No. of months at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $workExpTotalInMonthName;?>' id='<?php echo $workExpTotalInMonthName;?>'  validate="validateInteger" minlength="1" maxlength="10" caption="Number of months at <?php echo $workCompany; ?>"    tip="Enter the number of months at  <?php echo $workCompany; ?>"   value=''   required="true" allowNA='true'/>
				<?php if(isset($workExpTotalInMonthValue) && $workExpTotalInMonthValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpTotalInMonthName;?>").value = "<?php echo str_replace("\n", '\n',$workExpTotalInMonthValue );  ?>";
				      document.getElementById("<?php echo $workExpTotalInMonthName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpTotalInMonthName;?>_error'></div></div>
				</div>
				</div>
				
				
		    </li>
		    
		    <?php }} ?>
		    
	
        <li>
            <h3 class="upperCase">Additional Information</h3>
        </li>
        <li>
            <div class="additionalInfoLeftCol">
            <label style='font-weight:normal'>Preferred Interview Location:</label>
            <div class='fieldBoxLarge'>
            <select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
	    <option value=''>Select</option>
	    <?php foreach($gdpiLocations as $gdpiLocation): ?>
		    <option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
	    <?php endforeach; ?>
	    </select>
	    <?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=""){ ?>
	    <script>
		var selObj = document.getElementById("preferredGDPILocation"); 
		var A= selObj.options, L= A.length;
		while(L)
                {
			if (A[--L].value== "<?php echo $preferredGDPILocation;?>"){
			selObj.selectedIndex= L;
			L= 0;
			}
		}
	    </script>
            <?php } ?>
	    <div style='display:none'><div class='errorMsg' id= 'preferredGDPILocation_error'></div></div>
	    </div>
            </div
        </li>
        
        <li>
	    <div class='additionalInfoLeftCol' style="width:950px;">
                <label style='font-weight:normal'>Extra Curricular Activities, Interests and Hobbies: </label>
                <div class='fieldBoxLarge' style="width:640px;">
	        <textarea style="width:60%; height:80px;" name='ifmr_hobbies' id='ifmr_hobbies'  validate="validateStr"    caption="Hobbies and Interest"   minlength="10"   maxlength="2000"     tip="Enter your extra-curricular activities,hobbies and interests. If you DO NOT have hobbies or interests, just enter <b>NA</b>."   title="Hobbies and Interest"    allowNA = 'true' ></textarea>
	        <?php if(isset($ifmr_hobbies) && $ifmr_hobbies!=""){ ?>
	        <script>
		    document.getElementById("ifmr_hobbies").value = "<?php echo str_replace("\n", '\n', $ifmr_hobbies );  ?>";
                    document.getElementById("ifmr_hobbies").style.color = "";
		</script>
		<?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'ifmr_hobbies_error'></div></div>
		</div>
	    </div>
        </li>
	
	<li>
	    <h3 class="uppercase">Referernces</h3>
	    <li>
		<div class="additionalInfoLeftCol">
		    <label style='font-weight:normal'><b>Reference 1</b></label>
		</div>
		<div class='additionalInfoRightCol'>
		    <label style='font-weight:normal'><b>Reference 2</b></label>
		</div>
	    </li>
	
	<li>
	    <div class="additionalInfoLeftCol">
		    <label style='font-weight:normal; float: left;'>Name :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference1_name' id='reference1_name' validate='validateStr' caption='Reference name' minlength='1' maxlength='200' tip='Enter the name of first reference.' required="true" value='' />
			<?php if(isset($reference1_name) && $reference1_name!=""){ ?>
			<script>
			    document.getElementById("reference1_name").value = "<?php echo str_replace("\n", '\n', $reference1_name );  ?>";
			    document.getElementById("reference1_name").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference1_name_error'></div></div>
		    </div>
	    </div>
	    <div class='additionalInfoRightCol'>
		    <label style='font-weight:normal; float: left;'>Name :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_name' id='reference2_name' validate='validateStr' caption='Reference name' minlength='1' maxlength='200' tip='Enter the name of second reference.' required="true" value='' />
			<?php if(isset($reference2_name) && $reference2_name!=""){ ?>
			<script>
			    document.getElementById("reference2_name").value = "<?php echo str_replace("\n", '\n', $reference2_name );  ?>";
			    document.getElementById("reference2_name").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference2_name_error'></div></div>
		    </div>
	    </div>
	</li>
	
	<li>
	    <div class='additionalInfoLeftCol'>
		    <label style='font-weight:normal; float: left;'>Designation :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference1_designation' id='reference1_designation' validate='validateStr' caption='Reference designation' minlength='1' maxlength='200' required="true" tip='Enter the designation of first reference.' value='' />
			<?php if(isset($reference1_designation) && $reference1_designation!=""){ ?>
			<script>
			    document.getElementById("reference1_designation").value = "<?php echo str_replace("\n", '\n', $reference1_designation );  ?>";
			    document.getElementById("reference1_designation").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference1_designation_error'></div></div>
		    </div>
	    </div>
	    <div class='additionalInfoRightCol'>
		    <label style='font-weight:normal; float: left;'>Designation :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_designation' id='reference2_designation' validate='validateStr' caption='Reference designation' minlength='1' maxlength='200' required="true" tip='Enter the designation of second reference.' value='' />
			<?php if(isset($reference2_designation) && $reference2_designation!=""){ ?>
			<script>
			    document.getElementById("reference2_designation").value = "<?php echo str_replace("\n", '\n', $reference2_designation );  ?>";
			    document.getElementById("reference2_designation").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference2_designation_error'></div></div>
		    </div>
	    </div>
	</li>
	
	<li>
	    <div class='additionalInfoLeftCol'>
		     <label style='font-weight:normal; float: left;'>Company/Institution :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference1_workplace' id='reference1_workplace' validate='validateStr' caption='Reference workplace' minlength='1' maxlength='200' required="true" tip='Enter the workplace of first reference.' value='' />
			<?php if(isset($reference1_workplace) && $reference1_workplace!=""){ ?>
			<script>
			    document.getElementById("reference1_workplace").value = "<?php echo str_replace("\n", '\n', $reference1_workplace );  ?>";
			    document.getElementById("reference1_workplace").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference1_workplace_error'></div></div>
		    </div>
	    </div>
	    <div class='additionalInfoRightCol'>
		    <label style='font-weight:normal; float: left;'>Company/Institution :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_workplace' id='reference2_workplace' validate='validateStr' caption='Reference workplace' minlength='1' maxlength='200' required="true" tip='Enter the workplace of second reference.' value='' />
			<?php if(isset($reference2_workplace) && $reference2_workplace!=""){ ?>
			<script>
			    document.getElementById("reference2_workplace").value = "<?php echo str_replace("\n", '\n', $reference2_workplace );  ?>";
			    document.getElementById("reference2_workplace").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference2_workplace_error'></div></div>
		    </div>
	    </div>
	</li>
	
	<li>
	    <div class='additionalInfoLeftCol'>
		    <label style='font-weight:normal; float: left;'>Address :</label>
		    <div class='fieldboxLarge'>
			<input style="height:40px;" type='text' name= 'reference1_address' id='reference1_address' validate='validateStr' caption='Reference address' minlength='1' maxlength='200' tip='Enter the address of first reference.' required="true" value='' />
			<?php if(isset($reference1_address) && $reference1_address!=""){ ?>
			<script>
			    document.getElementById("reference1_address").value = "<?php echo str_replace("\n", '\n', $reference1_address );  ?>";
			    document.getElementById("reference1_address").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference1_address_error'></div></div>
		    </div>
	    </div>
	    <div class='additionalInfoRightCol'>
		    <label style='font-weight:normal; float: left;'>Address :</label>
		    <div class='fieldboxLarge'>
			<input style="height:40px;" type='text' name= 'reference2_address' id='reference2_address' validate='validateStr' caption='Reference address' minlength='1' maxlength='200' tip='Enter the address of second reference.' required="true" value='' />
			<?php if(isset($reference2_address) && $reference2_address!=""){ ?>
			<script>
			    document.getElementById("reference2_address").value = "<?php echo str_replace("\n", '\n', $reference2_address );  ?>";
			    document.getElementById("reference2_address").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference2_address_error'></div></div>
		    </div>
	    </div>
	</li>
	
	<li>
	    <div class='additionalInfoLeftCol'>
		    <label style='font-weight:normal'>Phone No :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference1_phone' id='reference1_phone' validate='validateInteger' caption='Reference phone' minlength='4' maxlength='11' tip='Enter the phone number of first reference.' value='' />
			<?php if(isset($reference1_phone) && $reference1_phone!=""){ ?>
			<script>
			    document.getElementById("reference1_phone").value = "<?php echo str_replace("\n", '\n', $reference1_phone );  ?>";
			    document.getElementById("reference1_phone").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference1_phone_error'></div></div>
		    </div>
	    </div>
	    <div class='additionalInfoRightCol'>
		    <label style='font-weight:normal'>Phone No :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_phone' id='reference2_phone' validate='validateInteger' caption='Reference phone' minlength='4' maxlength='11' tip='Enter the phone number of second reference.' value='' />
			<?php if(isset($reference2_phone) && $reference2_phone!=""){ ?>
			<script>
			    document.getElementById("reference2_phone").value = "<?php echo str_replace("\n", '\n', $reference2_phone );  ?>";
			    document.getElementById("reference2_phone").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference2_phone_error'></div></div>
		    </div>
	    </div>
	</li>
	
	<li>
	    <div class='additionalInfoLeftCol'>
		    <label style='font-weight:normal; float: left;'>Mobile No :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference1_mobile' id='reference1_mobile' validate='validateMobileInteger' caption='Reference mobile' minlength='10' maxlength='10' tip='Enter the mobile number of first reference.' required="true" value='' />
			<?php if(isset($reference1_mobile) && $reference1_mobile!=""){ ?>
			<script>
			    document.getElementById("reference1_mobile").value = "<?php echo str_replace("\n", '\n', $reference1_mobile );  ?>";
			    document.getElementById("reference1_mobile").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference1_mobile_error'></div></div>
		    </div>
	    </div>
	    <div class='additionalInfoRightCol'>
		    <label style='font-weight:normal; float: left;'>Mobile No :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_mobile' id='reference2_mobile' validate='validateMobileInteger' caption='Reference mobile' minlength='10' maxlength='10' tip='Enter the mobile number of second reference.' required="true" value='' />
			<?php if(isset($reference2_mobile) && $reference2_mobile!=""){ ?>
			<script>
			    document.getElementById("reference2_mobile").value = "<?php echo str_replace("\n", '\n', $reference2_mobile );  ?>";
			    document.getElementById("reference2_mobile").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference2_mobile_error'></div></div>
		    </div>
	    </div>
	</li>
	
	
        <li>
	    <div class='additionalInfoLeftCol'>
		    <label style='font-weight:normal; float: left;'>Email :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference1_email' id='reference1_email' validate='validateEmail' caption='Email' minlength='1' maxlength='200' required="true" tip='Enter the email of first reference.' value='' />
			<?php if(isset($reference1_email) && $reference1_email!=""){ ?>
			<script>
			    document.getElementById("reference1_email").value = "<?php echo str_replace("\n", '\n', $reference1_email);  ?>";
			    document.getElementById("reference1_email").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference1_email_error'></div></div>
		    </div>
	    </div>
	    <div class='additionalInfoRightCol'>
		    <label style='font-weight:normal; float: left;'>Email :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_email' id='reference2_email' validate='validateEmail' caption='Email' minlength='1' maxlength='200' required="true" tip='Enter the email of second reference.' value='' />
			<?php if(isset($reference2_email) && $reference2_email!=""){ ?>
			<script>
			    document.getElementById("reference2_email").value = "<?php echo str_replace("\n", '\n', $reference2_email);  ?>";
			    document.getElementById("reference2_email").style.color = "";
			</script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'reference2_email_error'></div></div>
		    </div>
	    </div>
	</li>	
		    
<!--		    <label style='font-weight:normal'><b>Reference 2</b></label>
		    <div class='additionalInfoRightCol'>
		    <label style='font-weight:normal'>Name :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_name' id='reference2_name' validate='validateStr' caption='Reference name' minlength='1' maxlength='200' tip='Enter the name of first reference.' value='' />
		    </div>
		    <label style='font-weight:normal'>Designation :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_designation' id='reference2_designation' validate='validateStr' caption='Reference designation' minlength='1' maxlength='200' tip='Enter the designation of first reference.' value='' />
		    </div>
		     <label style='font-weight:normal'>Company/Institution :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_workplace' id='reference2_workplace' validate='validateStr' caption='Reference workplace' minlength='1' maxlength='200' tip='Enter the workplace of first reference.' value='' />
		    </div>
		    <label style='font-weight:normal'>Address :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_address' id='reference2_address' validate='validateStr' caption='Reference address' minlength='1' maxlength='200' tip='Enter the address of first reference.' value='' />
		    </div>
		    <label style='font-weight:normal'>Phone No :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_phone' id='reference2_phone' validate='validateStr' caption='Reference phone' minlength='1' maxlength='200' tip='Enter the phone number of first reference.' value='' />
		    </div>
		    <label style='font-weight:normal'>Mobile No :</label>
		    <div class='fieldboxLarge'>
			<input type='text' name= 'reference2_mobile' id='reference2_mobile' validate='validateStr' caption='Reference mobile' minlength='1' maxlength='200' tip='Enter the mobile number of first reference.' value='' />
		    </div>-->
	<!--    </div>
	</li-->
        
        <li>
	    <h3 class="uppercase">Essay</h3>
            <div class='additionalInfoLeftCol' style="width:950px;">
            <label style='font-weight:normal'>Essay: </label>
            <div class='fieldBoxLarge' style="width:640px;">
            <textarea  style="width:60%; height:80px;" name='essay_ifmr' id='essay_ifmr' validate="validateStr" caption='Essay' minlength='10' maxlength='2000' tip='In about 300 words, describe how you decided on your career choice. Tell us how this career choice fits in with your strengths and interests.' title='Essay'></textarea>
            <?php if(isset($essay_ifmr) && $essay_ifmr!=""){ ?>
            <script>
	       document.getElementById("essay_ifmr").value = "<?php echo str_replace("\n", '\n', $essay_ifmr );  ?>";
	       document.getElementById("essay_ifmr").style.color = "";
	    </script>
	    <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'essay_ifmr_error'></div></div>
            </div>
            </div>
        </li>
       
           			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">

 </span>I, hereby declare that the particulars given in the application form are true and correct and will be supported by original
documents when asked for. I am also aware that in the event of any information being found incorrect or misleading my candidature shall
be liable to cancellation by the Institute at any time.

				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='Ifmr_agreeToTerms[]' id='Ifmr_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($Ifmr_agreeToTerms) && $Ifmr_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Ifmr_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Ifmr_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'Ifmr_agreeToTerms0_error'></div></div>
				</div>
				</div>
			</li>
			 <?php endif; ?>
   
    </ul>
    </div>
</div>



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
  <?php } ?>
  
  <script>
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
	;	while(L){
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
		checkTestScore(document.getElementById('testNamesIFMR'+j));
	}
	
</script>   