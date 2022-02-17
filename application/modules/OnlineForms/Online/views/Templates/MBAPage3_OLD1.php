
<div>Class 10th: <input type='text' name='class10ExaminationName' id='class10ExaminationName'   required="true"        tip="Examination Name"   value='Like AISSE'   default = 'Like AISSE' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></div><script>
		    document.getElementById("class10ExaminationName").style.color = "#ADA6AD";
		</script>
<?php if(isset($class10ExaminationName) && $class10ExaminationName!=""){ ?>
		<script>
		    document.getElementById("class10ExaminationName").value = "<?php echo str_replace("\n", '\n', $class10ExaminationName );  ?>";
		    document.getElementById("class10ExaminationName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class10ExaminationName_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='class10School' id='class10School'   required="true"        tip="Class 10th School Name"   value=''  /></div>
<?php if(isset($class10School) && $class10School!=""){ ?>
		<script>
		    document.getElementById("class10School").value = "<?php echo str_replace("\n", '\n', $class10School );  ?>";
		    document.getElementById("class10School").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class10School_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='class10Board' id='class10Board'   required="true"        tip="Enter class 10th Board name"   value=''  /></div>
<?php if(isset($class10Board) && $class10Board!=""){ ?>
		<script>
		    document.getElementById("class10Board").value = "<?php echo str_replace("\n", '\n', $class10Board );  ?>";
		    document.getElementById("class10Board").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class10Board_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='class10Year' id='class10Year'   required="true"        tip="Enter Class 10 Year"   value=''  /></div>
<?php if(isset($class10Year) && $class10Year!=""){ ?>
		<script>
		    document.getElementById("class10Year").value = "<?php echo str_replace("\n", '\n', $class10Year );  ?>";
		    document.getElementById("class10Year").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class10Year_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='class10Percentage' id='class10Percentage'   required="true"        tip="Enter class 10th Percentage"   value=''  /></div>
<?php if(isset($class10Percentage) && $class10Percentage!=""){ ?>
		<script>
		    document.getElementById("class10Percentage").value = "<?php echo str_replace("\n", '\n', $class10Percentage );  ?>";
		    document.getElementById("class10Percentage").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class10Percentage_error'></div></div>
<div class='lineSpace_10'></div>
<div>Class 12th: <input type='text' name='class12ExaminationName' id='class12ExaminationName'   required="true"        tip="Examination Name"   value='Like SSSE'   default = 'Like SSSE' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></div><script>
		    document.getElementById("class12ExaminationName").style.color = "#ADA6AD";
		</script>
<?php if(isset($class12ExaminationName) && $class12ExaminationName!=""){ ?>
		<script>
		    document.getElementById("class12ExaminationName").value = "<?php echo str_replace("\n", '\n', $class12ExaminationName );  ?>";
		    document.getElementById("class12ExaminationName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class12ExaminationName_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='class12School' id='class12School'   required="true"        tip="Class 12th School Name"   value=''  /></div>
<?php if(isset($class12School) && $class12School!=""){ ?>
		<script>
		    document.getElementById("class12School").value = "<?php echo str_replace("\n", '\n', $class12School );  ?>";
		    document.getElementById("class12School").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class12School_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='class12Board' id='class12Board'   required="true"        tip="Enter class 12th Board name"   value=''  /></div>
<?php if(isset($class12Board) && $class12Board!=""){ ?>
		<script>
		    document.getElementById("class12Board").value = "<?php echo str_replace("\n", '\n', $class12Board );  ?>";
		    document.getElementById("class12Board").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class12Board_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='class12Year' id='class12Year'   required="true"        tip="Enter Class 12th Year"   value=''  /></div>
<?php if(isset($class12Year) && $class12Year!=""){ ?>
		<script>
		    document.getElementById("class12Year").value = "<?php echo str_replace("\n", '\n', $class12Year );  ?>";
		    document.getElementById("class12Year").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class12Year_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='class12Percentage' id='class12Percentage'   required="true"        tip="Enter class 12th Percentage"   value=''  /></div>
<?php if(isset($class12Percentage) && $class12Percentage!=""){ ?>
		<script>
		    document.getElementById("class12Percentage").value = "<?php echo str_replace("\n", '\n', $class12Percentage );  ?>";
		    document.getElementById("class12Percentage").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class12Percentage_error'></div></div>
<div class='lineSpace_10'></div><div id='1'>
<div>Graduation Name: <input type='text' name='graduationExaminationName' id='graduationExaminationName'   required="true"        tip="Examination Name"   value='Like B.Com, BTech, BA'   default = 'Like B.Com, BTech, BA' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></div><script>
		    document.getElementById("graduationExaminationName").style.color = "#ADA6AD";
		</script>
<?php if(isset($graduationExaminationName) && $graduationExaminationName!=""){ ?>
		<script>
		    document.getElementById("graduationExaminationName").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName );  ?>";
		    document.getElementById("graduationExaminationName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationSchool' id='graduationSchool'   required="true"        tip="Graduation University Name"   value=''  /></div>
<?php if(isset($graduationSchool) && $graduationSchool!=""){ ?>
		<script>
		    document.getElementById("graduationSchool").value = "<?php echo str_replace("\n", '\n', $graduationSchool );  ?>";
		    document.getElementById("graduationSchool").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationSchool_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationBoard' id='graduationBoard'   required="true"        tip="Graduation Board name"   value=''  /></div>
<?php if(isset($graduationBoard) && $graduationBoard!=""){ ?>
		<script>
		    document.getElementById("graduationBoard").value = "<?php echo str_replace("\n", '\n', $graduationBoard );  ?>";
		    document.getElementById("graduationBoard").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationBoard_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationYear' id='graduationYear'   required="true"        tip="Enter Graduation Year"   value=''  /></div>
<?php if(isset($graduationYear) && $graduationYear!=""){ ?>
		<script>
		    document.getElementById("graduationYear").value = "<?php echo str_replace("\n", '\n', $graduationYear );  ?>";
		    document.getElementById("graduationYear").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationYear_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationPercentage' id='graduationPercentage'   required="true"        tip="Enter Graduation Percentage"   value=''  /></div>
<?php if(isset($graduationPercentage) && $graduationPercentage!=""){ ?>
		<script>
		    document.getElementById("graduationPercentage").value = "<?php echo str_replace("\n", '\n', $graduationPercentage );  ?>";
		    document.getElementById("graduationPercentage").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationPercentage_error'></div></div>
<div class='lineSpace_10'></div></div><div id='11' style='display:none'>
<div>Graduation Name: <input type='text' name='graduationExaminationName_mul_1' id='graduationExaminationName_mul_1'          value=''  /></div>
<?php if(isset($graduationExaminationName_mul_1) && $graduationExaminationName_mul_1!=""){ ?>
		<script>
		    document.getElementById("graduationExaminationName_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName_mul_1 );  ?>";
		    document.getElementById("graduationExaminationName_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationSchool_mul_1' id='graduationSchool_mul_1'          value=''  /></div>
<?php if(isset($graduationSchool_mul_1) && $graduationSchool_mul_1!=""){ ?>
		<script>
		    document.getElementById("graduationSchool_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationSchool_mul_1 );  ?>";
		    document.getElementById("graduationSchool_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationSchool_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationBoard_mul_1' id='graduationBoard_mul_1'          value=''  /></div>
<?php if(isset($graduationBoard_mul_1) && $graduationBoard_mul_1!=""){ ?>
		<script>
		    document.getElementById("graduationBoard_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationBoard_mul_1 );  ?>";
		    document.getElementById("graduationBoard_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationBoard_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationYear_mul_1' id='graduationYear_mul_1'          value=''  /></div>
<?php if(isset($graduationYear_mul_1) && $graduationYear_mul_1!=""){ ?>
		<script>
		    document.getElementById("graduationYear_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationYear_mul_1 );  ?>";
		    document.getElementById("graduationYear_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationYear_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationPercentage_mul_1' id='graduationPercentage_mul_1'          value=''  /></div>
<?php if(isset($graduationPercentage_mul_1) && $graduationPercentage_mul_1!=""){ ?>
		<script>
		    document.getElementById("graduationPercentage_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationPercentage_mul_1 );  ?>";
		    document.getElementById("graduationPercentage_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationPercentage_mul_1_error'></div></div>
<div class='lineSpace_10'></div></div><?php if(isset($graduationPercentage_mul_1) && $graduationPercentage_mul_1!=""){ ?>
				  <script>
				      document.getElementById("graduationPercentage_mul_1").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?><div id='12' style='display:none'>
<div>Graduation Name: <input type='text' name='graduationExaminationName_mul_2' id='graduationExaminationName_mul_2'          value=''  /></div>
<?php if(isset($graduationExaminationName_mul_2) && $graduationExaminationName_mul_2!=""){ ?>
		<script>
		    document.getElementById("graduationExaminationName_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName_mul_2 );  ?>";
		    document.getElementById("graduationExaminationName_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationSchool_mul_2' id='graduationSchool_mul_2'          value=''  /></div>
<?php if(isset($graduationSchool_mul_2) && $graduationSchool_mul_2!=""){ ?>
		<script>
		    document.getElementById("graduationSchool_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationSchool_mul_2 );  ?>";
		    document.getElementById("graduationSchool_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationSchool_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationBoard_mul_2' id='graduationBoard_mul_2'          value=''  /></div>
<?php if(isset($graduationBoard_mul_2) && $graduationBoard_mul_2!=""){ ?>
		<script>
		    document.getElementById("graduationBoard_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationBoard_mul_2 );  ?>";
		    document.getElementById("graduationBoard_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationBoard_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationYear_mul_2' id='graduationYear_mul_2'          value=''  /></div>
<?php if(isset($graduationYear_mul_2) && $graduationYear_mul_2!=""){ ?>
		<script>
		    document.getElementById("graduationYear_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationYear_mul_2 );  ?>";
		    document.getElementById("graduationYear_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationYear_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationPercentage_mul_2' id='graduationPercentage_mul_2'          value=''  /></div>
<?php if(isset($graduationPercentage_mul_2) && $graduationPercentage_mul_2!=""){ ?>
		<script>
		    document.getElementById("graduationPercentage_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationPercentage_mul_2 );  ?>";
		    document.getElementById("graduationPercentage_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationPercentage_mul_2_error'></div></div>
<div class='lineSpace_10'></div></div><?php if(isset($graduationPercentage_mul_2) && $graduationPercentage_mul_2!=""){ ?>
				  <script>
				      document.getElementById("graduationPercentage_mul_2").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?><div id='13' style='display:none'>
<div>Graduation Name: <input type='text' name='graduationExaminationName_mul_3' id='graduationExaminationName_mul_3'          value=''  /></div>
<?php if(isset($graduationExaminationName_mul_3) && $graduationExaminationName_mul_3!=""){ ?>
		<script>
		    document.getElementById("graduationExaminationName_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName_mul_3 );  ?>";
		    document.getElementById("graduationExaminationName_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationSchool_mul_3' id='graduationSchool_mul_3'          value=''  /></div>
<?php if(isset($graduationSchool_mul_3) && $graduationSchool_mul_3!=""){ ?>
		<script>
		    document.getElementById("graduationSchool_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationSchool_mul_3 );  ?>";
		    document.getElementById("graduationSchool_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationSchool_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationBoard_mul_3' id='graduationBoard_mul_3'          value=''  /></div>
<?php if(isset($graduationBoard_mul_3) && $graduationBoard_mul_3!=""){ ?>
		<script>
		    document.getElementById("graduationBoard_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationBoard_mul_3 );  ?>";
		    document.getElementById("graduationBoard_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationBoard_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationYear_mul_3' id='graduationYear_mul_3'          value=''  /></div>
<?php if(isset($graduationYear_mul_3) && $graduationYear_mul_3!=""){ ?>
		<script>
		    document.getElementById("graduationYear_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationYear_mul_3 );  ?>";
		    document.getElementById("graduationYear_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationYear_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationPercentage_mul_3' id='graduationPercentage_mul_3'          value=''  /></div>
<?php if(isset($graduationPercentage_mul_3) && $graduationPercentage_mul_3!=""){ ?>
		<script>
		    document.getElementById("graduationPercentage_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationPercentage_mul_3 );  ?>";
		    document.getElementById("graduationPercentage_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationPercentage_mul_3_error'></div></div>
<div class='lineSpace_10'></div></div><?php if(isset($graduationPercentage_mul_3) && $graduationPercentage_mul_3!=""){ ?>
				  <script>
				      document.getElementById("graduationPercentage_mul_3").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?><div id='14' style='display:none'>
<div>Graduation Name: <input type='text' name='graduationExaminationName_mul_4' id='graduationExaminationName_mul_4'          value=''  /></div>
<?php if(isset($graduationExaminationName_mul_4) && $graduationExaminationName_mul_4!=""){ ?>
		<script>
		    document.getElementById("graduationExaminationName_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName_mul_4 );  ?>";
		    document.getElementById("graduationExaminationName_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationSchool_mul_4' id='graduationSchool_mul_4'          value=''  /></div>
<?php if(isset($graduationSchool_mul_4) && $graduationSchool_mul_4!=""){ ?>
		<script>
		    document.getElementById("graduationSchool_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationSchool_mul_4 );  ?>";
		    document.getElementById("graduationSchool_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationSchool_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationBoard_mul_4' id='graduationBoard_mul_4'          value=''  /></div>
<?php if(isset($graduationBoard_mul_4) && $graduationBoard_mul_4!=""){ ?>
		<script>
		    document.getElementById("graduationBoard_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationBoard_mul_4 );  ?>";
		    document.getElementById("graduationBoard_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationBoard_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationYear_mul_4' id='graduationYear_mul_4'          value=''  /></div>
<?php if(isset($graduationYear_mul_4) && $graduationYear_mul_4!=""){ ?>
		<script>
		    document.getElementById("graduationYear_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationYear_mul_4 );  ?>";
		    document.getElementById("graduationYear_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationYear_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>: <input type='text' name='graduationPercentage_mul_4' id='graduationPercentage_mul_4'          value=''  /></div>
<?php if(isset($graduationPercentage_mul_4) && $graduationPercentage_mul_4!=""){ ?>
		<script>
		    document.getElementById("graduationPercentage_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationPercentage_mul_4 );  ?>";
		    document.getElementById("graduationPercentage_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationPercentage_mul_4_error'></div></div>
<div class='lineSpace_10'></div></div><?php if(isset($graduationPercentage_mul_4) && $graduationPercentage_mul_4!=""){ ?>
				  <script>
				      document.getElementById("graduationPercentage_mul_4").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?><a id='showMore1' href='javascript:void(0);' onClick='showMoreGroups(1,4);'>Add More</a>
<div>Date of Examination: <input type='text' name='catDateOfExamination' id='catDateOfExamination' readonly maxlength='10'   required="true"        tip="Enter Date of Examination"    onClick="cal.select($('catDateOfExamination'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calcatDateOfExamination = new CalendarPopup('calendardiv'); calcatDateOfExamination.select($('catDateOfExamination'),'sd','yyyy-MM-dd');" />
<?php if(isset($catDateOfExamination) && $catDateOfExamination!=""){ ?>
		<script>
		    document.getElementById("catDateOfExamination").value = "<?php echo str_replace("\n", '\n', $catDateOfExamination );  ?>";
		    document.getElementById("catDateOfExamination").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'catDateOfExamination_error'></div></div>
<div class='lineSpace_10'></div>
<div>Score: <input type='text' name='catScore' id='catScore'   required="true"        tip="Enter CAT Score"   value=''  /></div>
<?php if(isset($catScore) && $catScore!=""){ ?>
		<script>
		    document.getElementById("catScore").value = "<?php echo str_replace("\n", '\n', $catScore );  ?>";
		    document.getElementById("catScore").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'catScore_error'></div></div>
<div class='lineSpace_10'></div>
<div>Roll Number: <input type='text' name='catRollNumber' id='catRollNumber'   required="true"        tip="Enter CAT roll number"   value=''  /></div>
<?php if(isset($catRollNumber) && $catRollNumber!=""){ ?>
		<script>
		    document.getElementById("catRollNumber").value = "<?php echo str_replace("\n", '\n', $catRollNumber );  ?>";
		    document.getElementById("catRollNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'catRollNumber_error'></div></div>
<div class='lineSpace_10'></div>
<div>Percentile: <input type='text' name='catPercentile' id='catPercentile'   required="true"        tip="Enter CAT percentile"   value=''  /></div>
<?php if(isset($catPercentile) && $catPercentile!=""){ ?>
		<script>
		    document.getElementById("catPercentile").value = "<?php echo str_replace("\n", '\n', $catPercentile );  ?>";
		    document.getElementById("catPercentile").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'catPercentile_error'></div></div>
<div class='lineSpace_10'></div>
<div>Date of Examination: <input type='text' name='matDateOfExamination' id='matDateOfExamination' readonly maxlength='10'   required="true"        tip="Enter Date of Examination"    onClick="cal.select($('matDateOfExamination'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calmatDateOfExamination = new CalendarPopup('calendardiv'); calmatDateOfExamination.select($('matDateOfExamination'),'sd','yyyy-MM-dd');" />
<?php if(isset($matDateOfExamination) && $matDateOfExamination!=""){ ?>
		<script>
		    document.getElementById("matDateOfExamination").value = "<?php echo str_replace("\n", '\n', $matDateOfExamination );  ?>";
		    document.getElementById("matDateOfExamination").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'matDateOfExamination_error'></div></div>
<div class='lineSpace_10'></div>
<div>Score: <input type='text' name='matScore' id='matScore'   required="true"        tip="Enter MAT Score"   value=''  /></div>
<?php if(isset($matScore) && $matScore!=""){ ?>
		<script>
		    document.getElementById("matScore").value = "<?php echo str_replace("\n", '\n', $matScore );  ?>";
		    document.getElementById("matScore").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'matScore_error'></div></div>
<div class='lineSpace_10'></div>
<div>Roll Number: <input type='text' name='matRollNumber' id='matRollNumber'   required="true"        tip="Enter MAT roll number"   value=''  /></div>
<?php if(isset($matRollNumber) && $matRollNumber!=""){ ?>
		<script>
		    document.getElementById("matRollNumber").value = "<?php echo str_replace("\n", '\n', $matRollNumber );  ?>";
		    document.getElementById("matRollNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'matRollNumber_error'></div></div>
<div class='lineSpace_10'></div>
<div>Percentile: <input type='text' name='matPercentile' id='matPercentile'   required="true"        tip="Enter MAT percentile"   value=''  /></div>
<?php if(isset($matPercentile) && $matPercentile!=""){ ?>
		<script>
		    document.getElementById("matPercentile").value = "<?php echo str_replace("\n", '\n', $matPercentile );  ?>";
		    document.getElementById("matPercentile").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'matPercentile_error'></div></div>
<div class='lineSpace_10'></div>
<div>Date of Examination: <input type='text' name='gmatDateOfExamination' id='gmatDateOfExamination' readonly maxlength='10'   required="true"        tip="Enter Date of Examination"    onClick="cal.select($('gmatDateOfExamination'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calgmatDateOfExamination = new CalendarPopup('calendardiv'); calgmatDateOfExamination.select($('gmatDateOfExamination'),'sd','yyyy-MM-dd');" />
<?php if(isset($gmatDateOfExamination) && $gmatDateOfExamination!=""){ ?>
		<script>
		    document.getElementById("gmatDateOfExamination").value = "<?php echo str_replace("\n", '\n', $gmatDateOfExamination );  ?>";
		    document.getElementById("gmatDateOfExamination").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'gmatDateOfExamination_error'></div></div>
<div class='lineSpace_10'></div>
<div>Score: <input type='text' name='gmatScore' id='gmatScore'   required="true"        tip="Enter GMAT Score"   value=''  /></div>
<?php if(isset($gmatScore) && $gmatScore!=""){ ?>
		<script>
		    document.getElementById("gmatScore").value = "<?php echo str_replace("\n", '\n', $gmatScore );  ?>";
		    document.getElementById("gmatScore").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'gmatScore_error'></div></div>
<div class='lineSpace_10'></div>
<div>Roll Number: <input type='text' name='gmatRollNumber' id='gmatRollNumber'   required="true"        tip="Enter GMAT roll number"   value=''  /></div>
<?php if(isset($gmatRollNumber) && $gmatRollNumber!=""){ ?>
		<script>
		    document.getElementById("gmatRollNumber").value = "<?php echo str_replace("\n", '\n', $gmatRollNumber );  ?>";
		    document.getElementById("gmatRollNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'gmatRollNumber_error'></div></div>
<div class='lineSpace_10'></div>
<div>Percentile: <input type='text' name='gmatPercentile' id='gmatPercentile'   required="true"        tip="Enter GMAT percentile"   value=''  /></div>
<?php if(isset($gmatPercentile) && $gmatPercentile!=""){ ?>
		<script>
		    document.getElementById("gmatPercentile").value = "<?php echo str_replace("\n", '\n', $gmatPercentile );  ?>";
		    document.getElementById("gmatPercentile").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'gmatPercentile_error'></div></div>
<div class='lineSpace_10'></div><div id='2'>
<div>Company Name: <input type='text' name='weCompanyName' id='weCompanyName'   required="true"         value=''  /></div>
<?php if(isset($weCompanyName) && $weCompanyName!=""){ ?>
		<script>
		    document.getElementById("weCompanyName").value = "<?php echo str_replace("\n", '\n', $weCompanyName );  ?>";
		    document.getElementById("weCompanyName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weCompanyName_error'></div></div>
<div class='lineSpace_10'></div>
<div>Location: <input type='text' name='weLocation' id='weLocation'   required="true"        tip="Enter Company Location"   value=''  /></div>
<?php if(isset($weLocation) && $weLocation!=""){ ?>
		<script>
		    document.getElementById("weLocation").value = "<?php echo str_replace("\n", '\n', $weLocation );  ?>";
		    document.getElementById("weLocation").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weLocation_error'></div></div>
<div class='lineSpace_10'></div>
<div>Roles & Responsibilities: <textarea name='weRoles' id='weRoles'   required="true"         ></textarea></div>
<?php if(isset($weRoles) && $weRoles!=""){ ?>
		<script>
		    document.getElementById("weRoles").value = "<?php echo str_replace("\n", '\n', $weRoles );  ?>";
		    document.getElementById("weRoles").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weRoles_error'></div></div>
<div class='lineSpace_10'></div>
<div>Designation: <input type='text' name='weDesignation' id='weDesignation'   required="true"        tip="Enter your designation in the company"   value=''  /></div>
<?php if(isset($weDesignation) && $weDesignation!=""){ ?>
		<script>
		    document.getElementById("weDesignation").value = "<?php echo str_replace("\n", '\n', $weDesignation );  ?>";
		    document.getElementById("weDesignation").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weDesignation_error'></div></div>
<div class='lineSpace_10'></div>
<div>Time period: <input type='checkbox' name='weTimePeriod[]' id='weTimePeriod0'   value='I currently work here'  checked ></input>I currently work here&nbsp;&nbsp;</div>
<?php if(isset($weTimePeriod) && $weTimePeriod!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["weTimePeriod[]"];
		    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$weTimePeriod);
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
<div style='display:none'><div class='errorMsg' id= 'weTimePeriod_error'></div></div>
<div class='lineSpace_10'></div>
<div>From: <input type='text' name='weFrom' id='weFrom' readonly maxlength='10'   required="true"          onClick="cal.select($('weFrom'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweFrom = new CalendarPopup('calendardiv'); calweFrom.select($('weFrom'),'sd','yyyy-MM-dd');" />
<?php if(isset($weFrom) && $weFrom!=""){ ?>
		<script>
		    document.getElementById("weFrom").value = "<?php echo str_replace("\n", '\n', $weFrom );  ?>";
		    document.getElementById("weFrom").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weFrom_error'></div></div>
<div class='lineSpace_10'></div>
<div>Till: <input type='text' name='weTill' id='weTill' readonly maxlength='10'   required="true"          onClick="cal.select($('weTill'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweTill = new CalendarPopup('calendardiv'); calweTill.select($('weTill'),'sd','yyyy-MM-dd');" />
<?php if(isset($weTill) && $weTill!=""){ ?>
		<script>
		    document.getElementById("weTill").value = "<?php echo str_replace("\n", '\n', $weTill );  ?>";
		    document.getElementById("weTill").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weTill_error'></div></div>
<div class='lineSpace_10'></div></div><div id='21' style='display:none'>
<div>Company Name: <input type='text' name='weCompanyName_mul_1' id='weCompanyName_mul_1'          value=''  /></div>
<?php if(isset($weCompanyName_mul_1) && $weCompanyName_mul_1!=""){ ?>
		<script>
		    document.getElementById("weCompanyName_mul_1").value = "<?php echo str_replace("\n", '\n', $weCompanyName_mul_1 );  ?>";
		    document.getElementById("weCompanyName_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weCompanyName_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>Location: <input type='text' name='weLocation_mul_1' id='weLocation_mul_1'          value=''  /></div>
<?php if(isset($weLocation_mul_1) && $weLocation_mul_1!=""){ ?>
		<script>
		    document.getElementById("weLocation_mul_1").value = "<?php echo str_replace("\n", '\n', $weLocation_mul_1 );  ?>";
		    document.getElementById("weLocation_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weLocation_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>Roles & Responsibilities: <textarea name='weRoles_mul_1' id='weRoles_mul_1'          ></textarea></div>
<?php if(isset($weRoles_mul_1) && $weRoles_mul_1!=""){ ?>
		<script>
		    document.getElementById("weRoles_mul_1").value = "<?php echo str_replace("\n", '\n', $weRoles_mul_1 );  ?>";
		    document.getElementById("weRoles_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weRoles_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>Designation: <input type='text' name='weDesignation_mul_1' id='weDesignation_mul_1'          value=''  /></div>
<?php if(isset($weDesignation_mul_1) && $weDesignation_mul_1!=""){ ?>
		<script>
		    document.getElementById("weDesignation_mul_1").value = "<?php echo str_replace("\n", '\n', $weDesignation_mul_1 );  ?>";
		    document.getElementById("weDesignation_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weDesignation_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>Time period: <input type='checkbox' name='weTimePeriod_mul_1[]' id='weTimePeriod_mul_10'   value='I currently work here'   ></input>I currently work here&nbsp;&nbsp;</div>
<?php if(isset($weTimePeriod_mul_1) && $weTimePeriod_mul_1!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["weTimePeriod_mul_1[]"];
		    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$weTimePeriod_mul_1);
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
<div style='display:none'><div class='errorMsg' id= 'weTimePeriod_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>From: <input type='text' name='weFrom_mul_1' id='weFrom_mul_1' readonly maxlength='10'           onClick="cal.select($('weFrom_mul_1'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweFrom_mul_1 = new CalendarPopup('calendardiv'); calweFrom_mul_1.select($('weFrom_mul_1'),'sd','yyyy-MM-dd');" />
<?php if(isset($weFrom_mul_1) && $weFrom_mul_1!=""){ ?>
		<script>
		    document.getElementById("weFrom_mul_1").value = "<?php echo str_replace("\n", '\n', $weFrom_mul_1 );  ?>";
		    document.getElementById("weFrom_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weFrom_mul_1_error'></div></div>
<div class='lineSpace_10'></div>
<div>Till: <input type='text' name='weTill_mul_1' id='weTill_mul_1' readonly maxlength='10'           onClick="cal.select($('weTill_mul_1'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweTill_mul_1 = new CalendarPopup('calendardiv'); calweTill_mul_1.select($('weTill_mul_1'),'sd','yyyy-MM-dd');" />
<?php if(isset($weTill_mul_1) && $weTill_mul_1!=""){ ?>
		<script>
		    document.getElementById("weTill_mul_1").value = "<?php echo str_replace("\n", '\n', $weTill_mul_1 );  ?>";
		    document.getElementById("weTill_mul_1").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weTill_mul_1_error'></div></div>
<div class='lineSpace_10'></div></div><?php if(isset($weTill_mul_1) && $weTill_mul_1!=""){ ?>
				  <script>
				      document.getElementById("weTill_mul_1").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?><div id='22' style='display:none'>
<div>Company Name: <input type='text' name='weCompanyName_mul_2' id='weCompanyName_mul_2'          value=''  /></div>
<?php if(isset($weCompanyName_mul_2) && $weCompanyName_mul_2!=""){ ?>
		<script>
		    document.getElementById("weCompanyName_mul_2").value = "<?php echo str_replace("\n", '\n', $weCompanyName_mul_2 );  ?>";
		    document.getElementById("weCompanyName_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weCompanyName_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>Location: <input type='text' name='weLocation_mul_2' id='weLocation_mul_2'          value=''  /></div>
<?php if(isset($weLocation_mul_2) && $weLocation_mul_2!=""){ ?>
		<script>
		    document.getElementById("weLocation_mul_2").value = "<?php echo str_replace("\n", '\n', $weLocation_mul_2 );  ?>";
		    document.getElementById("weLocation_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weLocation_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>Roles & Responsibilities: <textarea name='weRoles_mul_2' id='weRoles_mul_2'          ></textarea></div>
<?php if(isset($weRoles_mul_2) && $weRoles_mul_2!=""){ ?>
		<script>
		    document.getElementById("weRoles_mul_2").value = "<?php echo str_replace("\n", '\n', $weRoles_mul_2 );  ?>";
		    document.getElementById("weRoles_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weRoles_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>Designation: <input type='text' name='weDesignation_mul_2' id='weDesignation_mul_2'          value=''  /></div>
<?php if(isset($weDesignation_mul_2) && $weDesignation_mul_2!=""){ ?>
		<script>
		    document.getElementById("weDesignation_mul_2").value = "<?php echo str_replace("\n", '\n', $weDesignation_mul_2 );  ?>";
		    document.getElementById("weDesignation_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weDesignation_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>Time period: <input type='checkbox' name='weTimePeriod_mul_2[]' id='weTimePeriod_mul_20'   value='I currently work here'   ></input>I currently work here&nbsp;&nbsp;</div>
<?php if(isset($weTimePeriod_mul_2) && $weTimePeriod_mul_2!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["weTimePeriod_mul_2[]"];
		    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$weTimePeriod_mul_2);
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
<div style='display:none'><div class='errorMsg' id= 'weTimePeriod_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>From: <input type='text' name='weFrom_mul_2' id='weFrom_mul_2' readonly maxlength='10'           onClick="cal.select($('weFrom_mul_2'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweFrom_mul_2 = new CalendarPopup('calendardiv'); calweFrom_mul_2.select($('weFrom_mul_2'),'sd','yyyy-MM-dd');" />
<?php if(isset($weFrom_mul_2) && $weFrom_mul_2!=""){ ?>
		<script>
		    document.getElementById("weFrom_mul_2").value = "<?php echo str_replace("\n", '\n', $weFrom_mul_2 );  ?>";
		    document.getElementById("weFrom_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weFrom_mul_2_error'></div></div>
<div class='lineSpace_10'></div>
<div>Till: <input type='text' name='weTill_mul_2' id='weTill_mul_2' readonly maxlength='10'           onClick="cal.select($('weTill_mul_2'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweTill_mul_2 = new CalendarPopup('calendardiv'); calweTill_mul_2.select($('weTill_mul_2'),'sd','yyyy-MM-dd');" />
<?php if(isset($weTill_mul_2) && $weTill_mul_2!=""){ ?>
		<script>
		    document.getElementById("weTill_mul_2").value = "<?php echo str_replace("\n", '\n', $weTill_mul_2 );  ?>";
		    document.getElementById("weTill_mul_2").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weTill_mul_2_error'></div></div>
<div class='lineSpace_10'></div></div><?php if(isset($weTill_mul_2) && $weTill_mul_2!=""){ ?>
				  <script>
				      document.getElementById("weTill_mul_2").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?><div id='23' style='display:none'>
<div>Company Name: <input type='text' name='weCompanyName_mul_3' id='weCompanyName_mul_3'          value=''  /></div>
<?php if(isset($weCompanyName_mul_3) && $weCompanyName_mul_3!=""){ ?>
		<script>
		    document.getElementById("weCompanyName_mul_3").value = "<?php echo str_replace("\n", '\n', $weCompanyName_mul_3 );  ?>";
		    document.getElementById("weCompanyName_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weCompanyName_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>Location: <input type='text' name='weLocation_mul_3' id='weLocation_mul_3'          value=''  /></div>
<?php if(isset($weLocation_mul_3) && $weLocation_mul_3!=""){ ?>
		<script>
		    document.getElementById("weLocation_mul_3").value = "<?php echo str_replace("\n", '\n', $weLocation_mul_3 );  ?>";
		    document.getElementById("weLocation_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weLocation_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>Roles & Responsibilities: <textarea name='weRoles_mul_3' id='weRoles_mul_3'          ></textarea></div>
<?php if(isset($weRoles_mul_3) && $weRoles_mul_3!=""){ ?>
		<script>
		    document.getElementById("weRoles_mul_3").value = "<?php echo str_replace("\n", '\n', $weRoles_mul_3 );  ?>";
		    document.getElementById("weRoles_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weRoles_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>Designation: <input type='text' name='weDesignation_mul_3' id='weDesignation_mul_3'          value=''  /></div>
<?php if(isset($weDesignation_mul_3) && $weDesignation_mul_3!=""){ ?>
		<script>
		    document.getElementById("weDesignation_mul_3").value = "<?php echo str_replace("\n", '\n', $weDesignation_mul_3 );  ?>";
		    document.getElementById("weDesignation_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weDesignation_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>Time period: <input type='checkbox' name='weTimePeriod_mul_3[]' id='weTimePeriod_mul_30'   value='I currently work here'   ></input>I currently work here&nbsp;&nbsp;</div>
<?php if(isset($weTimePeriod_mul_3) && $weTimePeriod_mul_3!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["weTimePeriod_mul_3[]"];
		    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$weTimePeriod_mul_3);
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
<div style='display:none'><div class='errorMsg' id= 'weTimePeriod_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>From: <input type='text' name='weFrom_mul_3' id='weFrom_mul_3' readonly maxlength='10'           onClick="cal.select($('weFrom_mul_3'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweFrom_mul_3 = new CalendarPopup('calendardiv'); calweFrom_mul_3.select($('weFrom_mul_3'),'sd','yyyy-MM-dd');" />
<?php if(isset($weFrom_mul_3) && $weFrom_mul_3!=""){ ?>
		<script>
		    document.getElementById("weFrom_mul_3").value = "<?php echo str_replace("\n", '\n', $weFrom_mul_3 );  ?>";
		    document.getElementById("weFrom_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weFrom_mul_3_error'></div></div>
<div class='lineSpace_10'></div>
<div>Till: <input type='text' name='weTill_mul_3' id='weTill_mul_3' readonly maxlength='10'           onClick="cal.select($('weTill_mul_3'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweTill_mul_3 = new CalendarPopup('calendardiv'); calweTill_mul_3.select($('weTill_mul_3'),'sd','yyyy-MM-dd');" />
<?php if(isset($weTill_mul_3) && $weTill_mul_3!=""){ ?>
		<script>
		    document.getElementById("weTill_mul_3").value = "<?php echo str_replace("\n", '\n', $weTill_mul_3 );  ?>";
		    document.getElementById("weTill_mul_3").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weTill_mul_3_error'></div></div>
<div class='lineSpace_10'></div></div><?php if(isset($weTill_mul_3) && $weTill_mul_3!=""){ ?>
				  <script>
				      document.getElementById("weTill_mul_3").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?><div id='24' style='display:none'>
<div>Company Name: <input type='text' name='weCompanyName_mul_4' id='weCompanyName_mul_4'          value=''  /></div>
<?php if(isset($weCompanyName_mul_4) && $weCompanyName_mul_4!=""){ ?>
		<script>
		    document.getElementById("weCompanyName_mul_4").value = "<?php echo str_replace("\n", '\n', $weCompanyName_mul_4 );  ?>";
		    document.getElementById("weCompanyName_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weCompanyName_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>Location: <input type='text' name='weLocation_mul_4' id='weLocation_mul_4'          value=''  /></div>
<?php if(isset($weLocation_mul_4) && $weLocation_mul_4!=""){ ?>
		<script>
		    document.getElementById("weLocation_mul_4").value = "<?php echo str_replace("\n", '\n', $weLocation_mul_4 );  ?>";
		    document.getElementById("weLocation_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weLocation_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>Roles & Responsibilities: <textarea name='weRoles_mul_4' id='weRoles_mul_4'          ></textarea></div>
<?php if(isset($weRoles_mul_4) && $weRoles_mul_4!=""){ ?>
		<script>
		    document.getElementById("weRoles_mul_4").value = "<?php echo str_replace("\n", '\n', $weRoles_mul_4 );  ?>";
		    document.getElementById("weRoles_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weRoles_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>Designation: <input type='text' name='weDesignation_mul_4' id='weDesignation_mul_4'          value=''  /></div>
<?php if(isset($weDesignation_mul_4) && $weDesignation_mul_4!=""){ ?>
		<script>
		    document.getElementById("weDesignation_mul_4").value = "<?php echo str_replace("\n", '\n', $weDesignation_mul_4 );  ?>";
		    document.getElementById("weDesignation_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weDesignation_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>Time period: <input type='checkbox' name='weTimePeriod_mul_4[]' id='weTimePeriod_mul_40'   value='I currently work here'   ></input>I currently work here&nbsp;&nbsp;</div>
<?php if(isset($weTimePeriod_mul_4) && $weTimePeriod_mul_4!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["weTimePeriod_mul_4[]"];
		    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$weTimePeriod_mul_4);
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
<div style='display:none'><div class='errorMsg' id= 'weTimePeriod_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>From: <input type='text' name='weFrom_mul_4' id='weFrom_mul_4' readonly maxlength='10'           onClick="cal.select($('weFrom_mul_4'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweFrom_mul_4 = new CalendarPopup('calendardiv'); calweFrom_mul_4.select($('weFrom_mul_4'),'sd','yyyy-MM-dd');" />
<?php if(isset($weFrom_mul_4) && $weFrom_mul_4!=""){ ?>
		<script>
		    document.getElementById("weFrom_mul_4").value = "<?php echo str_replace("\n", '\n', $weFrom_mul_4 );  ?>";
		    document.getElementById("weFrom_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weFrom_mul_4_error'></div></div>
<div class='lineSpace_10'></div>
<div>Till: <input type='text' name='weTill_mul_4' id='weTill_mul_4' readonly maxlength='10'           onClick="cal.select($('weTill_mul_4'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var calweTill_mul_4 = new CalendarPopup('calendardiv'); calweTill_mul_4.select($('weTill_mul_4'),'sd','yyyy-MM-dd');" />
<?php if(isset($weTill_mul_4) && $weTill_mul_4!=""){ ?>
		<script>
		    document.getElementById("weTill_mul_4").value = "<?php echo str_replace("\n", '\n', $weTill_mul_4 );  ?>";
		    document.getElementById("weTill_mul_4").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'weTill_mul_4_error'></div></div>
<div class='lineSpace_10'></div></div><?php if(isset($weTill_mul_4) && $weTill_mul_4!=""){ ?>
				  <script>
				      document.getElementById("weTill_mul_4").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?><a id='showMore2' href='javascript:void(0);' onClick='showMoreGroups(2,4);'>Add More</a><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
    <script>
	var selObj = document.getElementById("city"); 
	var A= selObj.options, L= A.length;
	while(L){
	    if (A[--L].innerHTML == "<?php echo $city;?>"){
		selObj.selectedIndex= L;
		L= 0;
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