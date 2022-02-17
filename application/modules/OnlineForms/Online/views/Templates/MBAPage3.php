		<div class="formChildWrapper">
        	<div class="formSection">
            	<div class="spacer10 clearFix"></div>
                <h2 class="noBorder">Education History</h2>
                <div class="educationHistoryBlock">
                	<div class="educationTitle">
			    <p class="educationCol educationHisTitleColFirst">Examination Name <a href="javascript:void(0);" class="qualifyHelp" onmouseover="showTipOnline('In this column, mention the name of examination or degree. For example, if you did 10th from CBSE, enter AISSE as name of examination. If you did B.Sc. in graduation, enter B.Sc. For the exact name of examination, refer your marksheet / certificate / degree.',this);" onmouseout="hidetip();">&nbsp;</a></p>
			    <p class="educationCol">School/ Institute</p>
			    <p class="educationCol">Board/ University</p>
			    <p class="educationYearCol">Year</p>
			    <p class="educationSmallCol">Percentage/ Grade</p>
			</div>
                   
                <ul>
                	<li>
                        <div class="formAutoColumns">
                            <label>Class 10<sup>th</sup></label>

			    <div class="educationCol educationColFirst">
				  <div class="float_L">
				  <input class="educationtextbox" type='text' name='class10ExaminationName' id='class10ExaminationName'  validate="validateStr"   required="true"   caption="Examination Name"   minlength="2"   maxlength="50"   value='Like AISSE'   default = 'Like AISSE' onfocus='checkTextElementOnTransition(this,"focus"); showTipOnline("For 10th and 12th board, enter the name of examination. For example, 10th Board from CBSE is called AISSE. For details of name of the examination, refer your marksheet / degree / certificate.",this);' onblur='checkTextElementOnTransition(this,"blur");' />
					    <script>
						document.getElementById("class10ExaminationName").style.color = "#ADA6AD";
					    </script>
			    <?php if(isset($class10ExaminationName) && $class10ExaminationName!=""){ ?>
					    <script>
						document.getElementById("class10ExaminationName").value = "<?php echo str_replace("\n", '\n', $class10ExaminationName );  ?>";
						document.getElementById("class10ExaminationName").style.color = "";
					    </script>
					  <?php } ?>
				  <div style='display:none'><div class='errorMsg' id= 'class10ExaminationName_error'></div></div>
				  </div>
			    </div>


			    <div class="educationCol">
				  <div class="float_L">
				  <input class="educationtextbox" type='text' name='class10School' id='class10School'  validate="validateStr"   required="true"   caption="School/Institute"   minlength="2"   maxlength="150"     tip="Mention the name of the school, institute, college or educational institution from where you did your course."   value=''  />
				  <?php if(isset($class10School) && $class10School!=""){ ?>
						  <script>
						      document.getElementById("class10School").value = "<?php echo str_replace("\n", '\n', $class10School );  ?>";
						      document.getElementById("class10School").style.color = "";
						  </script>
						<?php } ?>
				  <div style='display:none'><div class='errorMsg' id= 'class10School_error'></div></div>
				  </div>
			    </div>


			    <div class="educationCol">
				  <div class="float_L">
					<?php
						createStructuredField('class10Board','Mention the name of the Board (CBSE, ICSE or any State Board of India) or University to which your course is affiliated.','Board',2,150,'true',160,$class10Board,$board_array);
					?>
				  </div>
			    </div>

			    <div class="educationYearCol">
			    <div class="float_L">
			    <input class="textboxYear" type='text' name='class10Year' id='class10Year'  validate="validateInteger"   required="true"   caption="Year"   minlength="4"   maxlength="4"     tip="Mention the year in which you completed your course."   value=''  />
			    <?php if(isset($class10Year) && $class10Year!=""){ ?>
					    <script>
						document.getElementById("class10Year").value = "<?php echo str_replace("\n", '\n', $class10Year );  ?>";
						document.getElementById("class10Year").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'class10Year_error'></div></div>
			    </div>
			    </div>

			    <div class="educationSmallCol">
				  <div class="float_L">
				  <input class="textboxYear" type='text' name='class10Percentage' id='class10Percentage'  validate="validateStr"   required="true"   caption="Percentage"   minlength="1"   maxlength="4"     tip="Mention the overall percentage or grade that you scored in the exams here."   value=''  />
				  <?php if(isset($class10Percentage) && $class10Percentage!=""){ ?>
						  <script>
						      document.getElementById("class10Percentage").value = "<?php echo str_replace("\n", '\n', $class10Percentage );  ?>";
						      document.getElementById("class10Percentage").style.color = "";
						  </script>
						<?php } ?>
				  <div style='display:none'><div class='errorMsg' id= 'class10Percentage_error'></div></div>
				  </div>
			    </div>

			</div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
                            <label>Class 12<sup>th</sup></label>

			    <div class="educationCol educationColFirst">
				<div class="float_L">
				<input class="educationtextbox" type='text' name='class12ExaminationName' id='class12ExaminationName'  validate="validateStr"   required="true"   caption="Examination Name"   minlength="2"   maxlength="50"      value='Like SSSE'   default = 'Like SSSE' onfocus='checkTextElementOnTransition(this,"focus"); showTipOnline("For 10th and 12th board, enter the name of examination. For example, 10th Board from CBSE is called AISSE. For details of name of the examination, refer your marksheet / degree / certificate.",this);' onblur='checkTextElementOnTransition(this,"blur");' /><script>
						document.getElementById("class12ExaminationName").style.color = "#ADA6AD";
					    </script>
					    <?php if(isset($class12ExaminationName) && $class12ExaminationName!=""){ ?>
					    <script>
						document.getElementById("class12ExaminationName").value = "<?php echo str_replace("\n", '\n', $class12ExaminationName );  ?>";
						document.getElementById("class12ExaminationName").style.color = "";
					    </script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'class12ExaminationName_error'></div></div>
				</div>
			    </div>

			    <div class="educationCol">
				<div class="float_L">
				<input class="educationtextbox" type='text' name='class12School' id='class12School'  validate="validateStr"   required="true"   caption="School/Institute"   minlength="2"   maxlength="150"     tip="Mention the name of the school, institute, college or educational institution from where you did your course."   value=''  />
				<?php if(isset($class12School) && $class12School!=""){ ?>
						<script>
						    document.getElementById("class12School").value = "<?php echo str_replace("\n", '\n', $class12School );  ?>";
						    document.getElementById("class12School").style.color = "";
						</script>
					      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'class12School_error'></div></div>
				</div>
			    </div>

			    <div class="educationCol">
				<div class="float_L">
					<?php
						createStructuredField('class12Board','Mention the name of the Board (CBSE, ICSE or any State Board of India) or University to which your course is affiliated.','Board',2,150,'true',160,$class12Board,$board_array);
					?>
				</div>
			    </div>

			    <div class="educationYearCol">
				<div class="float_L">
				<input class="textboxYear" type='text' name='class12Year' id='class12Year'  validate="validateInteger"   required="true"   caption="Year"   minlength="4"   maxlength="4"     tip="Mention the year in which you completed your course."   value=''  />
				<?php if(isset($class12Year) && $class12Year!=""){ ?>
						<script>
						    document.getElementById("class12Year").value = "<?php echo str_replace("\n", '\n', $class12Year );  ?>";
						    document.getElementById("class12Year").style.color = "";
						</script>
					      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'class12Year_error'></div></div>
				</div>
			    </div>

			    <div class="educationSmallCol">
				<div class="float_L">
				<input class="textboxYear" type='text' name='class12Percentage' id='class12Percentage'  validate="validateStr"   required="true"   caption="Percentage"   minlength="1"   maxlength="4"     tip="Mention the overall percentage or grade that you scored in the exams here. Please enter NA in 12th marks section if your result is awaited."   value=''  />
				<?php if(isset($class12Percentage) && $class12Percentage!=""){ ?>
						<script>
						    document.getElementById("class12Percentage").value = "<?php echo str_replace("\n", '\n', $class12Percentage );  ?>";
						    document.getElementById("class12Percentage").style.color = "";
						</script>
					      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'class12Percentage_error'></div></div>
				</div>
			    </div>
			    
			</div>
                    </li>
<?php
	if($this->courselevelmanager->getCurrentLevel() == "PG"){
?>
                    <li>
                        <div class="formAutoColumns">
                        	<label class="paddTop7">Graduation</label>

				<div id='1'>

				<div class="educationCol educationColFirst">
                                      <div class="float_L">
                                        <?php
                                                createStructuredField('graduationExaminationName','For 10th and 12th board, enter the name of examination. For example, 10th Board from CBSE is called AISSE. For details of name of the examination, refer your marksheet / degree / certificate.','Examination Name',2,50,'true',160,$graduationExaminationName,$graduation_exam_array);
                                        ?>
                                      </div>
				</div>

				<div class="educationCol">
				      <div class="float_L">
				      <input type='text' class="educationtextbox"  name='graduationSchool' id='graduationSchool'  validate="validateStr"   required="true"   caption="Institute"   minlength="2"   maxlength="150"     tip="Mention the name of the school, institute, college or educational institution from where you did your course."   value=''  />
				      <?php if(isset($graduationSchool) && $graduationSchool!=""){ ?>
						      <script>
							  document.getElementById("graduationSchool").value = "<?php echo str_replace("\n", '\n', $graduationSchool );  ?>";
							  document.getElementById("graduationSchool").style.color = "";
						      </script>
						    <?php } ?>
				      <div style='display:none'><div class='errorMsg' id= 'graduationSchool_error'></div></div>
				      </div>
				</div>

				<div class="educationCol">
				      <div class="float_L">
					<?php
						createStructuredField('graduationBoard','Mention the name of the University, Board (CBSE, ICSE or any State Board of India) to which your course is affiliated.','Institute',2,150,'true',160,$graduationBoard,$universities_array);
					?>
				      </div>
				</div>

				<div class="educationYearCol">
				      <div class="float_L">
				      <input class="textboxYear" type='text' name='graduationYear' id='graduationYear'  validate="validateInteger"  caption="Year"   minlength="4"   maxlength="4"  required="true"   tip="Mention the year in which you completed your course. If you are still pursuing your graduation, enter the expected year of passing."   value=''  />
				      <?php if(isset($graduationYear) && $graduationYear!=""){ ?>
						      <script>
							  document.getElementById("graduationYear").value = "<?php echo str_replace("\n", '\n', $graduationYear );  ?>";
							  document.getElementById("graduationYear").style.color = "";
						      </script>
						    <?php } ?>
				      <div style='display:none'><div class='errorMsg' id= 'graduationYear_error'></div></div>
				      </div>
				</div>

				<div class="educationSmallCol">
				      <div class="float_L">
				      <input class="textboxYear" type='text' name='graduationPercentage' id='graduationPercentage'  validate="validateStr"     caption="Percentage"   minlength="1"   maxlength="4"     tip="Mention the overall percentage or grade that you scored in the exams here. If you are still pursuing your graduation,fill the percentage till the last attempted semester."   value=''  />
				      <?php if(isset($graduationPercentage) && $graduationPercentage!=""){ ?>
						      <script>
							  document.getElementById("graduationPercentage").value = "<?php echo str_replace("\n", '\n', $graduationPercentage );  ?>";
							  document.getElementById("graduationPercentage").style.color = "";
						      </script>
						    <?php } ?>
				      <div style='display:none'><div class='errorMsg' id= 'graduationPercentage_error'></div></div>
				      </div>
				</div>

				</div>

			</div>
                    </li>
<?php
	}else{
?>
 <li id='1' style="display:none"></li>
<?php
	}
?>

                    <li id='11' style="display:none">
                        <div class="formAutoColumns">
                        	<label class="paddTop7"></label>

				<div>

				<div class="educationCol educationColFirst">
					<div class="float_L">
					<input class="educationtextbox"  type='text' name='graduationExaminationName_mul_1' id='graduationExaminationName_mul_1'  validate="validateStr"    caption="Examination Name"   minlength="2"   maxlength="50"       />
					<?php if(isset($graduationExaminationName_mul_1) && $graduationExaminationName_mul_1!=""){ ?>
							<script>
							    document.getElementById("graduationExaminationName_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName_mul_1 );  ?>";
							    document.getElementById("graduationExaminationName_mul_1").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_mul_1_error'></div></div>
					</div>
				</div>

				<div class="educationCol">
					<div class="float_L">
					<input type='text' class="educationtextbox"  name='graduationSchool_mul_1' id='graduationSchool_mul_1'  validate="validateStr"   caption="Institute"   minlength="2"   maxlength="150"    value=''  />
					<?php if(isset($graduationSchool_mul_1) && $graduationSchool_mul_1!=""){ ?>
							<script>
							    document.getElementById("graduationSchool_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationSchool_mul_1 );  ?>";
							    document.getElementById("graduationSchool_mul_1").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationSchool_mul_1_error'></div></div>
					</div>
				</div>

				<div class="educationCol">
					<div class="float_L">
					<?php
						createStructuredField('graduationBoard_mul_1','','Institute',2,150,'false',160,$graduationBoard_mul_1,$universities_array);
					?>
					</div>
				</div>

				<div class="educationYearCol">
					<div class="float_L">
					<input class="textboxYear" type='text' name='graduationYear_mul_1' id='graduationYear_mul_1'  validate="validateInteger"  caption="Year"   minlength="4"   maxlength="4"    value=''  />
					<?php if(isset($graduationYear_mul_1) && $graduationYear_mul_1!=""){ ?>
							<script>
							    document.getElementById("graduationYear_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationYear_mul_1 );  ?>";
							    document.getElementById("graduationYear_mul_1").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationYear_mul_1_error'></div></div>
					</div>
				</div>

				<div class="educationSmallCol">
					<div class="float_L">
					<input class="textboxYear" type='text' name='graduationPercentage_mul_1' id='graduationPercentage_mul_1'  validate="validateStr"   caption="Percentage"   minlength="1"   maxlength="4"    value=''  />
					<?php if(isset($graduationPercentage_mul_1) && $graduationPercentage_mul_1!=""){ ?>
							<script>
							    document.getElementById("graduationPercentage_mul_1").value = "<?php echo str_replace("\n", '\n', $graduationPercentage_mul_1 );  ?>";
							    document.getElementById("graduationPercentage_mul_1").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationPercentage_mul_1_error'></div></div>
					</div>
				</div>

				</div>

				<?php if(isset($graduationExaminationName_mul_1) && $graduationExaminationName_mul_1!=""){ ?>
				  <script>
				      document.getElementById("graduationExaminationName_mul_1").parentNode.parentNode.parentNode.parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?>
			</div>
                    </li>

                    <li  id='12' style="display:none">
                        <div class="formAutoColumns">
                        	<label class="paddTop7"></label>

				<div>

				<div class="educationCol educationColFirst">
					<div class="float_L">
					<input class="educationtextbox"  type='text' name='graduationExaminationName_mul_2' id='graduationExaminationName_mul_2'  validate="validateStr"    caption="Examination Name"   minlength="2"   maxlength="50"       />
					<?php if(isset($graduationExaminationName_mul_2) && $graduationExaminationName_mul_2!=""){ ?>
							<script>
							    document.getElementById("graduationExaminationName_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName_mul_2 );  ?>";
							    document.getElementById("graduationExaminationName_mul_2").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_mul_2_error'></div></div>
					</div>
				</div>

				<div class="educationCol">
					<div class="float_L">
					<input type='text' class="educationtextbox"  name='graduationSchool_mul_2' id='graduationSchool_mul_2'  validate="validateStr"   caption="Institute"   minlength="2"   maxlength="150"    value=''  />
					<?php if(isset($graduationSchool_mul_2) && $graduationSchool_mul_2!=""){ ?>
							<script>
							    document.getElementById("graduationSchool_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationSchool_mul_2 );  ?>";
							    document.getElementById("graduationSchool_mul_2").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationSchool_mul_2_error'></div></div>
					</div>
				</div>

				<div class="educationCol">
					<div class="float_L">
					<?php
						createStructuredField('graduationBoard_mul_2','','Institute',2,150,'false',160,$graduationBoard_mul_2,$universities_array);
					?>
					</div>
				</div>

				<div class="educationYearCol">
					<div class="float_L">
					<input class="textboxYear" type='text' name='graduationYear_mul_2' id='graduationYear_mul_2'  validate="validateInteger"  caption="Year"   minlength="4"   maxlength="4"    value=''  />
					<?php if(isset($graduationYear_mul_2) && $graduationYear_mul_2!=""){ ?>
							<script>
							    document.getElementById("graduationYear_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationYear_mul_2 );  ?>";
							    document.getElementById("graduationYear_mul_2").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationYear_mul_2_error'></div></div>
					</div>
				</div>

				<div class="educationSmallCol">
					<div class="float_L">
					<input class="textboxYear" type='text' name='graduationPercentage_mul_2' id='graduationPercentage_mul_2'  validate="validateStr"   caption="Percentage"   minlength="1"   maxlength="4"    value=''  />
					<?php if(isset($graduationPercentage_mul_2) && $graduationPercentage_mul_2!=""){ ?>
							<script>
							    document.getElementById("graduationPercentage_mul_2").value = "<?php echo str_replace("\n", '\n', $graduationPercentage_mul_2 );  ?>";
							    document.getElementById("graduationPercentage_mul_2").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationPercentage_mul_2_error'></div></div>
					</div>
				</div>

				</div>

				<?php if(isset($graduationExaminationName_mul_2) && $graduationExaminationName_mul_2!=""){ ?>
				  <script>
				      document.getElementById("graduationExaminationName_mul_2").parentNode.parentNode.parentNode.parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?>
			</div>
                    </li>


                    <li  id='13' style="display:none">
                        <div class="formAutoColumns">
                        	<label class="paddTop7"></label>

				<div>

				<div class="educationCol educationColFirst">
				      <div class="float_L">
				      <input class="educationtextbox"  type='text' name='graduationExaminationName_mul_3' id='graduationExaminationName_mul_3'  validate="validateStr"    caption="Examination Name"   minlength="2"   maxlength="50"       />
				      <?php if(isset($graduationExaminationName_mul_3) && $graduationExaminationName_mul_3!=""){ ?>
						      <script>
							  document.getElementById("graduationExaminationName_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName_mul_3 );  ?>";
							  document.getElementById("graduationExaminationName_mul_3").style.color = "";
						      </script>
						    <?php } ?>
				      <div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_mul_3_error'></div></div>
				      </div>
				</div>

				<div class="educationCol">
				      <div class="float_L">
				      <input type='text' class="educationtextbox"  name='graduationSchool_mul_3' id='graduationSchool_mul_3'  validate="validateStr"   caption="Institute"   minlength="2"   maxlength="150"    value=''  />
				      <?php if(isset($graduationSchool_mul_3) && $graduationSchool_mul_3!=""){ ?>
						      <script>
							  document.getElementById("graduationSchool_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationSchool_mul_3 );  ?>";
							  document.getElementById("graduationSchool_mul_3").style.color = "";
						      </script>
						    <?php } ?>
				      <div style='display:none'><div class='errorMsg' id= 'graduationSchool_mul_3_error'></div></div>
				      </div>
				</div>

				<div class="educationCol">
				      <div class="float_L">
					<?php
						createStructuredField('graduationBoard_mul_3','','Institute',2,150,'false',160,$graduationBoard_mul_3,$universities_array);
					?>
				      </div>
				</div>

				<div class="educationYearCol">
				      <div class="float_L">
				      <input class="textboxYear" type='text' name='graduationYear_mul_3' id='graduationYear_mul_3'  validate="validateInteger"  caption="Year"   minlength="4"   maxlength="4"    value=''  />
				      <?php if(isset($graduationYear_mul_3) && $graduationYear_mul_3!=""){ ?>
						      <script>
							  document.getElementById("graduationYear_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationYear_mul_3 );  ?>";
							  document.getElementById("graduationYear_mul_3").style.color = "";
						      </script>
						    <?php } ?>
				      <div style='display:none'><div class='errorMsg' id= 'graduationYear_mul_3_error'></div></div>
				      </div>
				</div>

				<div class="educationSmallCol">
				      <div class="float_L">
				      <input class="textboxYear" type='text' name='graduationPercentage_mul_3' id='graduationPercentage_mul_3'  validate="validateStr"   caption="Percentage"   minlength="1"   maxlength="4"    value=''  />
				      <?php if(isset($graduationPercentage_mul_3) && $graduationPercentage_mul_3!=""){ ?>
						      <script>
							  document.getElementById("graduationPercentage_mul_3").value = "<?php echo str_replace("\n", '\n', $graduationPercentage_mul_3 );  ?>";
							  document.getElementById("graduationPercentage_mul_3").style.color = "";
						      </script>
						    <?php } ?>
				      <div style='display:none'><div class='errorMsg' id= 'graduationPercentage_mul_3_error'></div></div>
				      </div>
				</div>

				</div>

				<?php if(isset($graduationExaminationName_mul_3) && $graduationExaminationName_mul_3!=""){ ?>
				  <script>
				      document.getElementById("graduationExaminationName_mul_3").parentNode.parentNode.parentNode.parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?>
			</div>
                    </li>

                    <li  id='14' style="display:none">
                        <div class="formAutoColumns">
                        	<label class="paddTop7"></label>

				<div>

				<div class="educationCol educationColFirst">
					<div class="float_L">
					<input class="educationtextbox"  type='text' name='graduationExaminationName_mul_4' id='graduationExaminationName_mul_4'  validate="validateStr"    caption="Examination Name"   minlength="2"   maxlength="50"       />
					<?php if(isset($graduationExaminationName_mul_4) && $graduationExaminationName_mul_4!=""){ ?>
							<script>
							    document.getElementById("graduationExaminationName_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationExaminationName_mul_4 );  ?>";
							    document.getElementById("graduationExaminationName_mul_4").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationExaminationName_mul_4_error'></div></div>
					</div>
				</div>

				<div class="educationCol">
					<div class="float_L">
					<input type='text' class="educationtextbox"  name='graduationSchool_mul_4' id='graduationSchool_mul_4'  validate="validateStr"   caption="Institute"   minlength="2"   maxlength="150"    value=''  />
					<?php if(isset($graduationSchool_mul_4) && $graduationSchool_mul_4!=""){ ?>
							<script>
							    document.getElementById("graduationSchool_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationSchool_mul_4 );  ?>";
							    document.getElementById("graduationSchool_mul_4").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationSchool_mul_4_error'></div></div>
					</div>
				</div>

				<div class="educationCol">
					<div class="float_L">
					<?php
						createStructuredField('graduationBoard_mul_4','','Institute',2,150,'false',160,$graduationBoard_mul_4,$universities_array);
					?>
					</div>
				</div>

				<div class="educationYearCol">
					<div class="float_L">
					<input class="textboxYear" type='text' name='graduationYear_mul_4' id='graduationYear_mul_4'  validate="validateInteger"  caption="Year"   minlength="4"   maxlength="4"    value=''  />
					<?php if(isset($graduationYear_mul_4) && $graduationYear_mul_4!=""){ ?>
							<script>
							    document.getElementById("graduationYear_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationYear_mul_4 );  ?>";
							    document.getElementById("graduationYear_mul_4").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationYear_mul_4_error'></div></div>
					</div>
				</div>

				<div class="educationSmallCol">
					<div class="float_L">
					<input class="textboxYear" type='text' name='graduationPercentage_mul_4' id='graduationPercentage_mul_4'  validate="validateStr"   caption="Percentage"   minlength="1"   maxlength="4"    value=''  />
					<?php if(isset($graduationPercentage_mul_4) && $graduationPercentage_mul_4!=""){ ?>
							<script>
							    document.getElementById("graduationPercentage_mul_4").value = "<?php echo str_replace("\n", '\n', $graduationPercentage_mul_4 );  ?>";
							    document.getElementById("graduationPercentage_mul_4").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationPercentage_mul_4_error'></div></div>
					</div>
				</div>

				</div>

				<?php if(isset($graduationExaminationName_mul_4) && $graduationExaminationName_mul_4!=""){ ?>
				  <script>
				      document.getElementById("graduationExaminationName_mul_4").parentNode.parentNode.parentNode.parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?>
			</div>
                    </li>


		    <li class="addMore" id="educationAddMore"><a onmouseover="showTipOnline('If you have done any other Diploma, PG, or PG Diploma course, add its details to your profile by clicking on this button.',this);" onmouseout="hidetip();" id='showMore1' href='javascript:void(0);' onClick='showMoreGroups(1,4);'>+ Add More</a></li>
		    <?php if( (isset($graduationExaminationName_mul_1) && $graduationExaminationName_mul_1!="") && (isset($graduationExaminationName_mul_2) && $graduationExaminationName_mul_2!="") && (isset($graduationExaminationName_mul_3) && $graduationExaminationName_mul_3!="") && (isset($graduationExaminationName_mul_4) && $graduationExaminationName_mul_4!="") ){ ?>
		      <script>
			  document.getElementById("educationAddMore").style.display = "none";
		      </script>
		    <?php } ?>

                </ul>
                <div class="clearFix"></div>
             </div>
            </div>

<?php
	if($this->courselevelmanager->getCurrentLevel() == "PG"){
?>
            <div class="formSection">
            	<div class="spacer20 clearFix"></div>
            	<div class="qualificationBox" style="width: 950px;" >
		     <div style="width:950px;">
			<div class="float_L" style="width:170px;">
				<strong>Qualifying Examination:</strong> <a href="javascript:void(0);" class="qualifyHelp" onmouseover="showTipOnline('Choose the appropriate qualifying examination to enter your score details. You can select multiple examination if you have appeared in them. If you are yet to appear in the examination, enter your roll number and examination date.',this);" onmouseout="hidetip();">&nbsp;</a>&nbsp;&nbsp;
			</div>
			<div class="float_R" style="width:780px;line-height:25px;">
				<div class="float_L" style="width:85px;"><input id="catDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('cat');" /> CAT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="matDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('mat');" /> MAT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="gmatDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('gmat');"/> GMAT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="xatDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('xat');" /> XAT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="atmaDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('atma');" /> ATMA &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="cmatDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('cmat');" /> CMAT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="iiftDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('iift');" /> IIFT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="nmatDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('nmat');" /> NMAT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="irmaDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('irma');"/> IRMA &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="clear_B">&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="snapDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('snap');" /> SNAP &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="ibsatDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('ibsat');" /> IBSAT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="tissDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('tiss');" /> TISS &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="gcetDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('gcet');" /> GCET &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="tancetDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('tancet');" /> TANCET &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="icetDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('icet');"/> ICET &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="mhcetDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('mhcet');" /> MH-CET &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="kmatDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('kmat');" /> KMAT &nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div class="float_L" style="width:85px;"><input id="upseeDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('upsee');" /> UPSEE</div>
				<div class="clear_B">&nbsp;</div>
			</div>
			<div class="clear_B">&nbsp;</div>
                    </div>

		<?php
			function createExamHTML($examType,$dateVal,$scoreVal,$rollNumberVal,$percentileVal,$rankVal,$showRank='false'){
				echo '<div class="formGreyBox scoreDetails" id="'.$examType.'Details" style="display:none;">';
					if($examType=='mhcet')
						$examName = 'MH-CET';
					else
						$examName = strtoupper($examType);
					echo '<h3>'.$examName.' Score Details</h3>';
					echo '<ul>';
						echo '<li>';
						echo '<div class="scoreDetailsLftCol">';
							echo '<label>Date of Examination: </label>';
		
							echo '<div class="float_L">';
							      if(isset($dateVal) && $dateVal!=""){
								      echo "<input value= '".str_replace("\n", '\n', $dateVal )."' class='calenderFields' type='text' name='".$examType."DateOfExamination' id='".$examType."DateOfExamination' readonly maxlength='10' minlength='1' caption='date' validate='validateDateForms' onmouseover=\"showTipOnline('Choose the date on which the examination was conducted.',this);\" onmouseout = \"hidetip();\"   onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$examType."DateOfExamination'),'".$examType."DateOfExamination_dateImg','dd/MM/yyyy');\" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='".$examType."DateOfExamination_dateImg' onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$examType."DateOfExamination'),'".$examType."DateOfExamination_dateImg','dd/MM/yyyy'); return false;\" />";
								}
								else{
								      echo "<input class='calenderFields' type='text' name='".$examType."DateOfExamination' id='".$examType."DateOfExamination' readonly maxlength='10' minlength='1' caption='date' validate='validateDateForms' onmouseover=\"showTipOnline('Choose the date on which the examination was conducted.',this);\" onmouseout = \"hidetip();\"   onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$examType."DateOfExamination'),'".$examType."DateOfExamination_dateImg','dd/MM/yyyy');\" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='".$examType."DateOfExamination_dateImg' onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$examType."DateOfExamination'),'".$examType."DateOfExamination_dateImg','dd/MM/yyyy'); return false;\" />";								
								}
							      echo "<div style='display:none'><div class='errorMsg' id= '".$examType."DateOfExamination_error'></div></div>";
							echo '</div>';
						echo '</div>';
		
						echo '<div class="scoreDetailsRtCol">';
							echo '<label>Score: </label>';
							echo '<div class="float_L" style="width:95px;">';							
							if(isset($scoreVal) && $scoreVal!="" && $scoreVal !== '0.0'){
								echo '<input allowNA="true" class="textboxSmaller" type="text" name="'.$examType.'Score" id="'.$examType.'Score"  validate="validateInteger"    caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, leave this field blank."   value="'.str_replace("\n", '\n', $scoreVal ).'"  />';
							}
							else{
								echo '<input allowNA="true" class="textboxSmaller" type="text" name="'.$examType.'Score" id="'.$examType.'Score"  validate="validateInteger"    caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, leave this field blank."   value=""  />';						
							}
							echo "<div style='display:none'><div class='errorMsg' id= '".$examType."Score_error'></div></div>";
							echo '</div>';
						echo '</div>';
					    echo '</li>';
					    
					    echo '<li class="lastRows">';
						echo '<div class="scoreDetailsLftCol">';
						    echo '<label>Roll Number: </label>';
						    echo '<div class="fieldBoxSmall" >';
						    if(isset($rollNumberVal) && $rollNumberVal!=""){
							    echo '<input class="textboxRollNumb" type="text" name="'.$examType.'RollNumber" id="'.$examType.'RollNumber"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam."   value="'.str_replace("\n", '\n', $rollNumberVal ).'"  />';
						    }
						    else{
							    echo '<input class="textboxRollNumb" type="text" name="'.$examType.'RollNumber" id="'.$examType.'RollNumber"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam."   value=""  />';
						    }
						    echo "<div style='display:none'><div class='errorMsg' id= '".$examType."RollNumber_error'></div></div>";
						    echo '</div>';
						echo '</div>';
						if($showRank=='false'){
						echo '<div class="scoreDetailsRtCol">';
						    echo '<label>Percentile: </label>';
						    echo '<div class="float_L" style="width:95px;">';
						    if(isset($percentileVal) && $percentileVal!=""){
							    echo '<input allowNA="true" class="textboxSmaller"  type="text" name="'.$examType.'Percentile" id="'.$examType.'Percentile"  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don\'t know your percentile, you can leave this field blank."   value="'.str_replace("\n", '\n', $percentileVal ).'"  />';
						    }
						    else{
							    echo '<input allowNA="true" class="textboxSmaller"  type="text" name="'.$examType.'Percentile" id="'.$examType.'Percentile"  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don\'t know your percentile, you can leave this field blank."   value=""  />';							
						    }
						    echo '<div style="display:none"><div class="errorMsg" id= "'.$examType.'Percentile_error"></div></div>';
						    echo '</div>';
						echo '</div>';
						}
						else{
						echo '<div class="scoreDetailsRtCol">';
						    echo '<label>Rank: </label>';
						    echo '<div class="float_L" style="width:95px;">';
						    if(isset($rankVal) && $rankVal!=""){
							    echo '<input class="textboxSmaller"  type="text" name="'.$examType.'Rank" id="'.$examType.'Rank"  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your Rank in the exam. If you don\'t know your rank, you can leave this field blank."   value="'.str_replace("\n", '\n', $rankVal) .'"  />';
						    }
						    else{
							    echo '<input class="textboxSmaller"  type="text" name="'.$examType.'Rank" id="'.$examType.'Rank"  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your Rank in the exam. If you don\'t know your rank, you can leave this field blank."   value=""  />';						
						    }
						    echo '<div style="display:none"><div class="errorMsg" id= "'.$examType.'Rank_error"></div></div>';
						    echo '</div>';
						echo '</div>';
						}
						
					    echo '</li>';
					echo '</ul>';
					echo '<div class="clearFix"></div>';
				    echo '</div>';
			}
		?>
		
                    <!--Srore Details Begins-->
                    <?php createExamHTML('cat',$catDateOfExamination,$catScore,$catRollNumber,$catPercentile); ?>
                    <?php createExamHTML('mat',$matDateOfExamination,$matScore,$matRollNumber,$matPercentile); ?>
                    <?php createExamHTML('gmat',$gmatDateOfExamination,$gmatScore,$gmatRollNumber,$gmatPercentile); ?>
                    <!--Srore Details Ends-->

                    <!--Srore Details Begins-->
                    <div class="formGreyBox scoreDetails" id="xatDetails" style="display:none;">
                    	<h3>XAT Score Details</h3>
                        <ul>
                        	<li>
                            	<div class="scoreDetailsLftCol">
					<label>Date of Examination: </label>
					<div class="float_L" >
					<input class="calenderFields" type='text' name='xatDateOfExamination' id='xatDateOfExamination' readonly maxlength='10'   minlength='1' caption='date' validate='validateDateForms'      onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExamination'),'xatDateOfExamination_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='xatDateOfExamination_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExamination'),'xatDateOfExamination_dateImg','dd/MM/yyyy'); return false;" />
					<?php if(isset($xatDateOfExamination) && $xatDateOfExamination!=""){ ?>
							<script>
							    document.getElementById("xatDateOfExamination").value = "<?php echo str_replace("\n", '\n', $xatDateOfExamination );  ?>";
							    document.getElementById("xatDateOfExamination").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'xatDateOfExamination_error'></div></div>
					</div>
                                </div>

                                <div class="scoreDetailsRtCol">
					<label>Score: </label>
					<div class="float_L" style="width:95px;">
					<input allowNA="true" class="textboxSmaller" type='text' name='xatScore' id='xatScore'  validate="validateInteger"    caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, leave this field blank."   value=''  />
					<?php if(isset($xatScore) && $xatScore!="" && $xatScore !== '0.0'){ ?>
							<script>
							    document.getElementById("xatScore").value = "<?php echo str_replace("\n", '\n', $xatScore );  ?>";
							    document.getElementById("xatScore").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'xatScore_error'></div></div>
					</div>
                                </div>
                            </li>
                            
                            <li class="lastRows">
                            	<div class="scoreDetailsLftCol">
				    <label>Roll Number: </label>
				    <div class="fieldBoxSmall">
				    <input class="textboxRollNumb" type='text' name='xatRollNumberG' id='xatRollNumberG'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam."   value=''  />
				    <?php if(isset($xatRollNumberG) && $xatRollNumberG!=""){ ?>
						    <script>
							document.getElementById("xatRollNumberG").value = "<?php echo str_replace("\n", '\n', $xatRollNumberG );  ?>";
							document.getElementById("xatRollNumberG").style.color = "";
						    </script>
						  <?php } ?>
				    <div style='display:none'><div class='errorMsg' id= 'xatRollNumberG_error'></div></div>                                	
				    </div>
                                </div>
                                <div class="scoreDetailsRtCol">
				    <label>Percentile: </label>
				    <div class="float_L" style="width:95px;">
				    <input allowNA="true" class="textboxSmaller"  type='text' name='xatPercentile' id='xatPercentile'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don\'t know your percentile, you can leave this field blank."   value=''  />
				    <?php if(isset($xatPercentile) && $xatPercentile!=""){ ?>
						    <script>
							document.getElementById("xatPercentile").value = "<?php echo str_replace("\n", '\n', $xatPercentile );  ?>";
							document.getElementById("xatPercentile").style.color = "";
						    </script>
						  <?php } ?>
				    <div style='display:none'><div class='errorMsg' id= 'xatPercentile_error'></div></div>
				    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="clearFix"></div>
                    </div>
                    <!--Srore Details Ends-->

                    <!--Srore Details Begins-->
                    <?php createExamHTML('atma',$atmaDateOfExamination,$atmaScore,$atmaRollNumber,$atmaPercentile); ?>
                    <?php createExamHTML('cmat',$cmatDateOfExamination,$cmatScore,$cmatRollNumber,'',$cmatRank,'true'); ?>
                    <?php createExamHTML('iift',$iiftDateOfExamination,$iiftScore,$iiftRollNumber,$iiftPercentile); ?>
                    <?php createExamHTML('nmat',$nmatDateOfExamination,$nmatScore,$nmatRollNumber,$nmatPercentile); ?>
                    <?php createExamHTML('irma',$irmaDateOfExamination,$irmaScore,$irmaRollNumber,$irmaPercentile); ?>
                    <?php createExamHTML('snap',$snapDateOfExamination,$snapScore,$snapRollNumber,$snapPercentile); ?>
                    <?php createExamHTML('ibsat',$ibsatDateOfExamination,$ibsatScore,$ibsatRollNumber,$ibsatPercentile); ?>
                    <?php createExamHTML('tiss',$tissDateOfExamination,$tissScore,$tissRollNumber,$tissPercentile); ?>
                    <?php createExamHTML('gcet',$gcetDateOfExamination,$gcetScore,$gcetRollNumber,$gcetPercentile); ?>
                    <?php createExamHTML('tancet',$tancetDateOfExamination,$tancetScore,$tancetRollNumber,$tancetPercentile); ?>
                    <?php createExamHTML('icet',$icetDateOfExamination,$icetScore,$icetRollNumber,$icetPercentile); ?>
                    <?php createExamHTML('mhcet',$mhcetDateOfExamination,$mhcetScore,$mhcetRollNumber,'',$mhcetRank,'true'); ?>
                    <?php createExamHTML('kmat',$kmatDateOfExamination,$kmatScore,$kmatRollNumber,$kmatPercentile); ?>
                    <?php createExamHTML('upsee',$upseeDateOfExamination,$upseeScore,$upseeRollNumber,$upseePercentile); ?>
		    
                </div>
            </div>


            <div class="formSection">
            	<div class="spacer40 clearFix"></div>
                <h2>Work Experience <span class="optionalTxt">(Optional)</span></h2>
                <div class="spacer10 clearFix"></div>
                <ul>
		    <div id='2'>
                    <li>
                        <div class="formColumns">
			    			<label class="labelWidth155">Company Name: </label>
                            <div class="fieldBoxLarger">
                            <input class="textboxLarger" type='text' name='weCompanyName' id='weCompanyName'  validate="validateStr"    caption="Company Name"  minlength="2" maxlength="100" tip="Mention the name of your present company or the company you have worked last here."   value=''  />
			    <?php if(isset($weCompanyName) && $weCompanyName!=""){ ?>
					    <script>
						document.getElementById("weCompanyName").value = "<?php echo str_replace("\n", '\n', $weCompanyName );  ?>";
						document.getElementById("weCompanyName").style.color = "";
					    </script>
					  <?php } ?>
			    	<div style='display:none'><div class='errorMsg' id= 'weCompanyName_error'></div></div>
                    </div>
                        </div>
                        <div class="formColumns">
			    			<label class="labelWidth130">Designation: </label>
                			<div class="fieldBoxLarger">
				                <input class="textboxLarger" type='text' name='weDesignation' id='weDesignation'  validate="validateStr"    caption="Designation"   minlength="2"   maxlength="50" tip="Mention your last or most recent designation at the company here."   value=''  />
			    <?php if(isset($weDesignation) && $weDesignation!=""){ ?>
					    <script>
						document.getElementById("weDesignation").value = "<?php echo str_replace("\n", '\n', $weDesignation );  ?>";
						document.getElementById("weDesignation").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'weDesignation_error'></div></div>
                        </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
			    			<label class="labelWidth155">Location: </label>
                			<div class="fieldBoxLarger">
                				<input class="textboxLarge" type='text' name='weLocation' id='weLocation'  validate="validateStr"    caption="Company Location"   minlength="2"   maxlength="100"     tip="Mention where your company is located."   value=''  />
			    <?php if(isset($weLocation) && $weLocation!=""){ ?>
					    <script>
						document.getElementById("weLocation").value = "<?php echo str_replace("\n", '\n', $weLocation );  ?>";
						document.getElementById("weLocation").style.color = "";
					    </script>
					  <?php } ?>
					    <div style='display:none'><div class='errorMsg' id= 'weLocation_error'></div></div>
                        </div>
                        </div>
                        <div class="formColumns">


			    <label class="timePeriodLabel2">Time Period:</label>
			    <input type='checkbox' name='weTimePeriod[]' id='weTimePeriod0' value='I currently work here' onclick='toggleTillDate(this,"weTill");'></input>I currently work here&nbsp;&nbsp;
			    <?php if(isset($weTimePeriod) && $weTimePeriod!=""){ ?>
					    <script>
						//objCheckBoxes = document.forms["OnlineForm"].elements["weTimePeriod[]"];
						objCheckBoxes = document.getElementsByName("weTimePeriod[]");
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

                            <div class="workExpDetails2">
                                <span class="timeSep">From</span> 
                                <span class="calenderBox">
				    <input class="calenderFields" type='text' name='weFrom' id='weFrom' readonly maxlength='10'         onmouseover="showTipOnline('Choose the date on which you joined the company.',this);" onmouseout = "hidetip();"    onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weFrom'),'weFrom_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='weFrom_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weFrom'),'weFrom_dateImg','dd/MM/yyyy'); return false;" />
				    <?php if(isset($weFrom) && $weFrom!=""){ ?>
						    <script>
							document.getElementById("weFrom").value = "<?php echo str_replace("\n", '\n', $weFrom );  ?>";
							document.getElementById("weFrom").style.color = "";
						    </script>
						  <?php } ?>
				    <div style='display:none'><div class='errorMsg' id= 'weFrom_error'></div></div>
                                 </span>
                                 
				<span class="timeSep" id='weTillLabel'>Till <?php if(isset($weTimePeriod) && $weTimePeriod!=""){ echo 'date';} ?></span> 
				<span class="calenderBox" id='weTillArea' <?php if(isset($weTimePeriod) && $weTimePeriod!=""){ echo 'style="display:none;"';} ?>>

					<input class="calenderFields" type='text' name='weTill' id='weTill' readonly maxlength='10'       onmouseover="showTipOnline('Choose the date on which you left the company. If you are still working in the company, leave this field blank.',this);" onmouseout = "hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weTill'),'weTill_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='weTill_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weTill'),'weTill_dateImg','dd/MM/yyyy'); return false;" />
					<?php if(isset($weTill) && $weTill!=""){ ?>
							<script>
							    document.getElementById("weTill").value = "<?php echo str_replace("\n", '\n', $weTill );  ?>";
							    document.getElementById("weTill").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'weTill_error'></div></div>

                                </span>
                            </div>
			</div>
                    </li>
                    
                    <li class="roleResponseItem">
                        <div class="formColumns">
			    <label class="labelWidth155">Roles &amp; Responsibilities: </label>
                <div class="fieldBoxLarger">
                <textarea rows="2" cols="3" class="textAreaMedium" name='weRoles' id='weRoles'  validate="validateStr"    caption="Roles and Responsibilities"   minlength="2"   maxlength="500"     tip="Mention your key responsibilities and nature of your work at the company here."   ></textarea>
			    <?php if(isset($weRoles) && $weRoles!=""){ ?>
					    <script>
						document.getElementById("weRoles").value = "<?php echo str_replace("\n", '\n', $weRoles );  ?>";
						document.getElementById("weRoles").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'weRoles_error'></div></div>
                        </div>
                        </div>
                    </li>
                    </div>


		    <div id='21' style="display:none">
                    <li>
                        <div class="formColumns">
			    <label class="labelWidth155">Company Name: </label>
                <div class="fieldBoxLarger">
                <input class="textboxLarger" type='text' name='weCompanyName_mul_1' id='weCompanyName_mul_1'  validate="validateStr"    caption="Company Name"   minlength="2"   maxlength="100"    value=''  />
			    <?php if(isset($weCompanyName_mul_1) && $weCompanyName_mul_1!=""){ ?>
					    <script>
						document.getElementById("weCompanyName_mul_1").value = "<?php echo str_replace("\n", '\n', $weCompanyName_mul_1 );  ?>";
						document.getElementById("weCompanyName_mul_1").style.color = "";
					    </script>
					  <?php } ?>
			    		<div style='display:none'><div class='errorMsg' id= 'weCompanyName_mul_1_error'></div></div>
                        </div>
                        </div>
                        <div class="formColumns">
			    <label class="labelWidth130">Designation: </label>
                <div class="fieldBoxLarger">
                <input class="textboxLarger" type='text' name='weDesignation_mul_1' id='weDesignation_mul_1'  validate="validateStr"    caption="Designation"   minlength="2"   maxlength="50"    value=''  />
			    <?php if(isset($weDesignation_mul_1) && $weDesignation_mul_1!=""){ ?>
					    <script>
						document.getElementById("weDesignation_mul_1").value = "<?php echo str_replace("\n", '\n', $weDesignation_mul_1 );  ?>";
						document.getElementById("weDesignation_mul_1").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'weDesignation_mul_1_error'></div></div>
                        </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
			    <label class="labelWidth155">Location: </label>
                <div class="fieldBoxLarger">
                <input class="textboxLarge" type='text' name='weLocation_mul_1' id='weLocation_mul_1'  validate="validateStr"    caption="Company Location"   minlength="2"   maxlength="100"      value=''  />
			    <?php if(isset($weLocation_mul_1) && $weLocation_mul_1!=""){ ?>
					    <script>
						document.getElementById("weLocation_mul_1").value = "<?php echo str_replace("\n", '\n', $weLocation_mul_1 );  ?>";
						document.getElementById("weLocation_mul_1").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'weLocation_mul_1_error'></div></div>
                        </div>
                        </div>
                        <div class="formColumns">


			    <label class="timePeriodLabel2">Time Period:</label>
			    <input type='checkbox' name='weTimePeriod_mul_1[]' id='weTimePeriod_mul_10'   value='I currently work here' onclick='toggleTillDate(this,"weTill_mul_1");' ></input>I currently work here&nbsp;&nbsp;
			    <?php if(isset($weTimePeriod_mul_1) && $weTimePeriod_mul_1!=""){ ?>
					    <script>
						//objCheckBoxes = document.forms["OnlineForm"].elements["weTimePeriod_mul_1[]"];
						objCheckBoxes = document.getElementsByName("weTimePeriod_mul_1[]");
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

                            <div class="workExpDetails2">
                                <span class="timeSep">From</span> 
                                <span class="calenderBox">
				    <input class="calenderFields" type='text' name='weFrom_mul_1' id='weFrom_mul_1' readonly maxlength='10'    onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weFrom_mul_1'),'weFrom_mul_1_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='weFrom_mul_1_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weFrom_mul_1'),'weFrom_mul_1_dateImg','dd/MM/yyyy'); return false;" />
				    <?php if(isset($weFrom_mul_1) && $weFrom_mul_1!=""){ ?>
						    <script>
							document.getElementById("weFrom_mul_1").value = "<?php echo str_replace("\n", '\n', $weFrom_mul_1 );  ?>";
							document.getElementById("weFrom_mul_1").style.color = "";
						    </script>
						  <?php } ?>
				    <div style='display:none'><div class='errorMsg' id= 'weFrom_mul_1_error'></div></div>
                                 </span>
                                 
                <span class="timeSep" id='weTill_mul_1Label'>Till <?php if(isset($weTimePeriod_mul_1) && $weTimePeriod_mul_1!=""){ echo 'date';} ?></span> 
				<span class="calenderBox" id='weTill_mul_1Area' <?php if(isset($weTimePeriod_mul_1) && $weTimePeriod_mul_1!=""){ echo 'style="display:none;"';} ?>>

					<input class="calenderFields" type='text' name='weTill_mul_1' id='weTill_mul_1' readonly maxlength='10'     onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weTill_mul_1'),'weTill_mul_1_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='weTill_mul_1_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weTill_mul_1'),'weTill_mul_1_dateImg','dd/MM/yyyy'); return false;" />
					<?php if(isset($weTill_mul_1) && $weTill_mul_1!=""){ ?>
							<script>
							    document.getElementById("weTill_mul_1").value = "<?php echo str_replace("\n", '\n', $weTill_mul_1 );  ?>";
							    document.getElementById("weTill_mul_1").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'weTill_mul_1_error'></div></div>

                                </span>
                            </div>
			</div>
                    </li>
                    
                    <li class="roleResponseItem">
                        <div class="formColumns">
			    <label class="labelWidth155">Roles &amp; Responsibilities: </label>
                <div class="fieldBoxLarger">
                <textarea rows="2" cols="3" class="textAreaMedium" name='weRoles_mul_1' id='weRoles_mul_1'  validate="validateStr"    caption="Roles and Responsibilities"   minlength="2"   maxlength="500"     ></textarea>
			    <?php if(isset($weRoles_mul_1) && $weRoles_mul_1!=""){ ?>
					    <script>
						document.getElementById("weRoles_mul_1").value = "<?php echo str_replace("\n", '\n', $weRoles_mul_1 );  ?>";
						document.getElementById("weRoles_mul_1").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'weRoles_mul_1_error'></div></div>
                        </div>
                        </div>
                    </li>
                    </div>
		    <?php if(isset($weCompanyName_mul_1) && $weCompanyName_mul_1!=""){ ?>
				  <script>
				      document.getElementById("weCompanyName_mul_1").parentNode.parentNode.parentNode.parentNode.style.display = "";
				  </script>
		    <?php } ?>

		    <div id='22' style="display:none">
                    <li>
                        <div class="formColumns">
			    <label class="labelWidth155">Company Name: </label>
                <div class="fieldBoxLarger">
                <input class="textboxLarger" type='text' name='weCompanyName_mul_2' id='weCompanyName_mul_2'  validate="validateStr"    caption="Company Name"   minlength="2"   maxlength="100"    value=''  />
			    <?php if(isset($weCompanyName_mul_2) && $weCompanyName_mul_2!=""){ ?>
					    <script>
						document.getElementById("weCompanyName_mul_2").value = "<?php echo str_replace("\n", '\n', $weCompanyName_mul_2 );  ?>";
						document.getElementById("weCompanyName_mul_2").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'weCompanyName_mul_2_error'></div></div>
                        </div>
                        </div>
                        <div class="formColumns">
			    <label class="labelWidth130">Designation: </label>
                <div class="fieldBoxLarger">
                <input class="textboxLarger" type='text' name='weDesignation_mul_2' id='weDesignation_mul_2'  validate="validateStr"    caption="Designation"   minlength="2"   maxlength="50"    value=''  />
			    <?php if(isset($weDesignation_mul_2) && $weDesignation_mul_2!=""){ ?>
					    <script>
						document.getElementById("weDesignation_mul_2").value = "<?php echo str_replace("\n", '\n', $weDesignation_mul_2 );  ?>";
						document.getElementById("weDesignation_mul_2").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'weDesignation_mul_2_error'></div></div>
                        </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
			    <label class="labelWidth155">Location: </label>
                <div class="fieldBoxLarger">
                <input class="textboxLarge" type='text' name='weLocation_mul_2' id='weLocation_mul_2'  validate="validateStr"    caption="Company Location"   minlength="2"   maxlength="100"      value=''  />
			    <?php if(isset($weLocation_mul_2) && $weLocation_mul_2!=""){ ?>
					    <script>
						document.getElementById("weLocation_mul_2").value = "<?php echo str_replace("\n", '\n', $weLocation_mul_2 );  ?>";
						document.getElementById("weLocation_mul_2").style.color = "";
					    </script>
					  <?php } ?>
			    		<div style='display:none'><div class='errorMsg' id= 'weLocation_mul_2_error'></div></div>
                        </div>
                        </div>
                        <div class="formColumns">


			    <label class="timePeriodLabel2">Time Period:</label>
			    <input type='checkbox' name='weTimePeriod_mul_2[]' id='weTimePeriod_mul_20'   value='I currently work here' onclick='toggleTillDate(this,"weTill_mul_2");' ></input>I currently work here&nbsp;&nbsp;
			    <?php if(isset($weTimePeriod_mul_2) && $weTimePeriod_mul_2!=""){ ?>
					    <script>
						//objCheckBoxes = document.forms["OnlineForm"].elements["weTimePeriod_mul_2[]"];
						objCheckBoxes = document.getElementsByName("weTimePeriod_mul_2[]");
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

                            <div class="workExpDetails2">
                                <span class="timeSep">From</span> 
                                <span class="calenderBox">
				    <input class="calenderFields" type='text' name='weFrom_mul_2' id='weFrom_mul_2' readonly maxlength='10'    onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weFrom_mul_2'),'weFrom_mul_2_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='weFrom_mul_2_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weFrom_mul_2'),'weFrom_mul_2_dateImg','dd/MM/yyyy'); return false;" />
				    <?php if(isset($weFrom_mul_2) && $weFrom_mul_2!=""){ ?>
						    <script>
							document.getElementById("weFrom_mul_2").value = "<?php echo str_replace("\n", '\n', $weFrom_mul_2 );  ?>";
							document.getElementById("weFrom_mul_2").style.color = "";
						    </script>
						  <?php } ?>
				    <div style='display:none'><div class='errorMsg' id= 'weFrom_mul_2_error'></div></div>
                                 </span>
                                 
                <span class="timeSep" id='weTill_mul_2Label'>Till <?php if(isset($weTimePeriod_mul_2) && $weTimePeriod_mul_2!=""){ echo 'date';} ?></span> 
				<span class="calenderBox" id='weTill_mul_2Area' <?php if(isset($weTimePeriod_mul_2) && $weTimePeriod_mul_2!=""){ echo 'style="display:none;"';} ?>>

					<input class="calenderFields" type='text' name='weTill_mul_2' id='weTill_mul_2' readonly maxlength='10'     onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weTill_mul_2'),'weTill_mul_2_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='weTill_mul_2_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('weTill_mul_2'),'weTill_mul_2_dateImg','dd/MM/yyyy'); return false;" />
					<?php if(isset($weTill_mul_2) && $weTill_mul_2!=""){ ?>
							<script>
							    document.getElementById("weTill_mul_2").value = "<?php echo str_replace("\n", '\n', $weTill_mul_2 );  ?>";
							    document.getElementById("weTill_mul_2").style.color = "";
							</script>
						      <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'weTill_mul_2_error'></div></div>

                                </span>
                            </div>
			</div>
                    </li>
                    
                    <li class="roleResponseItem">
                        <div class="formColumns">
			    <label class="labelWidth155">Roles &amp; Responsibilities: </label>
                <div class="fieldBoxLarger">
                <textarea rows="2" cols="3" class="textAreaMedium" name='weRoles_mul_2' id='weRoles_mul_2'  validate="validateStr"    caption="Roles and Responsibilities"   minlength="2"   maxlength="500"     ></textarea>
			    <?php if(isset($weRoles_mul_2) && $weRoles_mul_2!=""){ ?>
					    <script>
						document.getElementById("weRoles_mul_2").value = "<?php echo str_replace("\n", '\n', $weRoles_mul_2 );  ?>";
						document.getElementById("weRoles_mul_2").style.color = "";
					    </script>
					  <?php } ?>
			    		<div style='display:none'><div class='errorMsg' id= 'weRoles_mul_2_error'></div></div>
                        </div>
                        </div>
                    </li>
                    </div>
		    <?php if(isset($weCompanyName_mul_2) && $weCompanyName_mul_2!=""){ ?>
				  <script>
				      document.getElementById("weCompanyName_mul_2").parentNode.parentNode.parentNode.parentNode.style.display = "";
				  </script>
		    <?php } ?>

                    <li class="addMore" style="margin-bottom:0" id="workExAddMore"><a onmouseover="showTipOnline('If you have done jobs previously too, add its details to your profile by clicking on this button. Fill in the details of the second last job, then the third last job and so on…',this);" onmouseout="hidetip();" id='showMore2' href='javascript:void(0);' onClick='showMoreGroups(2,2);'>+ Add More</a></li>
		    <?php if( (isset($weCompanyName_mul_1) && $weCompanyName_mul_1!="") && (isset($weCompanyName_mul_2) && $weCompanyName_mul_2!="") ){ ?>
				  <script>
				      document.getElementById("workExAddMore").style.display = "none";
				  </script>
		    <?php } ?>
                </ul>
            </div>
<?php
	}else{
?>

<div class="formSection">
            	<div class="spacer20 clearFix"></div>
            	<div class="qualificationBox" style="width: 950px;" >
		     <div style="width:950px;">
			<div class="float_L" style="width:170px;">
				<strong>Qualifying Examination:</strong> <a href="javascript:void(0);" class="qualifyHelp" onmouseover="showTipOnline('Choose the appropriate qualifying examination to enter your score details. You can select multiple examination if you have appeared in them. If you are yet to appear in the examination, enter your roll number and examination date.',this);" onmouseout="hidetip();">&nbsp;</a>&nbsp;&nbsp;
			</div>
			<div class="float_R" style="width:780px;line-height:25px;">
				<div class="float_L" style="width:85px;"><input id="jeeDetailsCheckbox" type="checkbox" onClick="toggleExamDetails('jee');" /> JEE Mains &nbsp;&nbsp;&nbsp;&nbsp;</div>
				
				<div class="clear_B">&nbsp;</div>
			</div>
			<div class="clear_B">&nbsp;</div>
                    </div>

		<?php
			function createExamHTML($examType,$dateVal,$ScoreVal,$rollNumberVal,$percentileVal){
				echo '<div class="formGreyBox scoreDetails" id="'.$examType.'Details" style="display:none;">';
					
					$examName = strtoupper($examType);
					echo '<h3>'.$examName.' Score Details</h3>';
					echo '<ul>';
						echo '<li>';
						echo '<div class="scoreDetailsLftCol">';
							echo '<label>Date of Examination: </label>';
		
							echo '<div class="float_L">';
							      if(isset($dateVal) && $dateVal!=""){
								      echo "<input value= '".str_replace("\n", '\n', $dateVal )."' class='calenderFields' type='text' name='".$examType."DateOfExamination' id='".$examType."DateOfExamination' readonly maxlength='10' minlength='1' caption='date' validate='validateDateForms' onmouseover=\"showTipOnline('Choose the date on which the examination was conducted.',this);\" onmouseout = \"hidetip();\"   onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$examType."DateOfExamination'),'".$examType."DateOfExamination_dateImg','dd/MM/yyyy');\" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='".$examType."DateOfExamination_dateImg' onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$examType."DateOfExamination'),'".$examType."DateOfExamination_dateImg','dd/MM/yyyy'); return false;\" />";
								}
								else{
								      echo "<input class='calenderFields' type='text' name='".$examType."DateOfExamination' id='".$examType."DateOfExamination' readonly maxlength='10' minlength='1' caption='date' validate='validateDateForms' onmouseover=\"showTipOnline('Choose the date on which the examination was conducted.',this);\" onmouseout = \"hidetip();\"   onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$examType."DateOfExamination'),'".$examType."DateOfExamination_dateImg','dd/MM/yyyy');\" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='".$examType."DateOfExamination_dateImg' onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$examType."DateOfExamination'),'".$examType."DateOfExamination_dateImg','dd/MM/yyyy'); return false;\" />";								
								}
							      echo "<div style='display:none'><div class='errorMsg' id= '".$examType."DateOfExamination_error'></div></div>";
							echo '</div>';
						echo '</div>';
		
					    echo '</li>';
					    
					    echo '<li>';
						echo '<div class="scoreDetailsLftCol">';
						    echo '<label>Roll Number: </label>';
						    echo '<div class="fieldBoxSmall" >';
						    if(isset($rollNumberVal) && $rollNumberVal!=""){
							    echo '<input class="textboxRollNumb" type="text" name="'.$examType.'RollNumber" id="'.$examType.'RollNumber"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam."   value="'.str_replace("\n", '\n', $rollNumberVal ).'"  />';
						    }
						    else{
							    echo '<input class="textboxRollNumb" type="text" name="'.$examType.'RollNumber" id="'.$examType.'RollNumber"  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam."   value=""  />';
						    }
						    echo "<div style='display:none'><div class='errorMsg' id= '".$examType."RollNumber_error'></div></div>";
						    echo '</div>';
						echo '</div>';
						echo '</li>';
					    
					    echo '<li class="lastRows">';
						echo '<div class="scoreDetailsLftCol">';
						    echo '<label>Paper 1 Marks: </label>';
						    echo '<div class="float_L" style="width:95px;">';
							    echo '<input class="textboxSmaller"  type="text" name="'.$examType.'Score" id="'.$examType.'Score"  validate="validateInteger"    caption="Paper 1 Marks"   minlength="1"   maxlength="7"     tip="Mention your Paper 1 Marks in the exam. If you don\'t know your Paper 1 Marks, you can leave this field blank."   value="'.$ScoreVal.'"  />';
						    echo '<div style="display:none"><div class="errorMsg" id= "'.$examType.'Score_error"></div></div>';
						    echo '</div>';
						echo '</div>';
						
						
					    echo '</li>';
					echo '</ul>';
					echo '<div class="clearFix"></div>';
				    echo '</div>';
			}
		?>
		
                    <!--Srore Details Begins-->
                    <?php createExamHTML('jee',$jeeDateOfExamination,$jeeScore,$jeeRollNumber,'','','true'); ?>
                 
                </div>
            </div>

<?php
	}
?>    
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

function toggleTillDate(cb,iden)
{
	if(cb.checked) {
		$(iden).value = '';
		$(iden+'Label').innerHTML = 'Till date';
		$(iden+'Area').style.display = 'none';
	}
	else {
		$(iden+'Label').innerHTML = 'Till';
		$(iden+'Area').style.display = '';
	}
}

  </script>

<script>
function toggleExamDetails(id){

	if(id == "cat" || id == "mat" || id == "gmat" || id == "atma" || id == "iift" || id == "nmat" || id == "irma" || id == "snap" || id == "ibsat" || id == "tiss" || id == "gcet" || id == "tancet" || id == "icet" || id == "kmat" || id == "upsee"){
	    var objects1 = new Array(id+"DateOfExamination",id+"RollNumber",id+"Score",id+"Percentile");
	}
	else if(id == "cmat" || id=='mhcet'){
	    var objects1 = new Array(id+"DateOfExamination",id+"RollNumber",id+"Score",id+"Rank");
	}
	else if(id == "xat"){
	    var objects1 = new Array(id+"DateOfExamination",id+"RollNumberG",id+"Score",id+"Percentile");
	}
	else if(id == "jee"){
	    var objects1 = new Array(id+"DateOfExamination",id+"RollNumber",id+"Score");
	}

	cb = $(id+'DetailsCheckbox');
	if(cb.checked) {
		if($(id+'Details')) {
			$(id+'Details').style.display = '';
		}
		//Set the required paramters when any Exam is shown
		setExamFields(objects1);
	}
	else {
		if($(id+'Details')) {
			$(id+'Details').style.display = 'none';
		}
		if($(id+'DateOfExamination')) {
			$(id+'DateOfExamination').value = '';
		}
		if($(id+'Score')) {
			$(id+'Score').value = '';
		}
		if($(id+'RollNumber')) {
			$(id+'RollNumber').value = '';
		}
		if($(id+'Percentile')) {
			$(id+'Percentile').value = '';
		}
		if($(id+'Rank')) {
			$(id+'Rank').value = '';
		}
		//Set the required paramters when any Exam is hidden
		resetExamFields(objects1);
	}
}

function setExamFields(objectsArr){
      for(i=0;i<objectsArr.length;i++){
	  if(objectsArr[i].indexOf('Score')<0 && objectsArr[i].indexOf('Percentile')<0 && objectsArr[i].indexOf('Rank')<0)
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

<?php
if( (isset($xatDateOfExamination) && $xatDateOfExamination!='') ||  (isset($xatScore) && $xatScore!="") || (isset($xatPercentile) && $xatPercentile!="") || (isset($xatRollNumberG) && $xatRollNumberG!="")){
      echo "<script>if(document.getElementById('xatDetailsCheckbox')) { document.getElementById('xatDetailsCheckbox').checked = true; toggleExamDetails('xat'); } </script>";
}
if( (isset($cmatDateOfExamination) && $cmatDateOfExamination!='') ||  (isset($cmatScore) && $cmatScore!="") || (isset($cmatRank) && $cmatRank!="") || (isset($cmatRollNumber) && $cmatRollNumber!="")){
      echo "<script>if(document.getElementById('cmatDetailsCheckbox')) { document.getElementById('cmatDetailsCheckbox').checked = true; toggleExamDetails('cmat'); } </script>";
}
if( (isset($jeeDateOfExamination) && $jeeDateOfExamination!='') || (isset($jeeScore) && $jeeScore!="") || (isset($jeeRollNumber) && $jeeRollNumber!="")){
      echo "<script>if(document.getElementById('jeeDetailsCheckbox')) { document.getElementById('jeeDetailsCheckbox').checked = true; toggleExamDetails('jee'); } </script>";
}
if( (isset($mhcetDateOfExamination) && $mhcetDateOfExamination!='') ||  (isset($mhcetScore) && $mhcetScore!="") || (isset($mhcetRank) && $mhcetRank!="") || (isset($mhcetRollNumber) && $mhcetRollNumber!="")){
      echo "<script>if(document.getElementById('mhcetDetailsCheckbox')) { document.getElementById('mhcetDetailsCheckbox').checked = true; toggleExamDetails('mhcet'); } </script>";
}
$otherExams = array('cat','mat','gmat','atma','iift','nmat','irma','snap','ibsat','tiss','gcet','tancet','icet','kmat','upsee');
foreach ($otherExams as $otherExam){
	$dateVar = $otherExam."DateOfExamination";
	$ScoreVar = $otherExam."Score";
	$PercentileVar = $otherExam."Percentile";
	$rollNumberVar = $otherExam."RollNumber";
	if( (isset($$dateVar) && $$dateVar!='') ||  (isset($$ScoreVar) && $$ScoreVar!="") || (isset($$PercentileVar) && $$PercentileVar!="") || (isset($$rollNumberVar) && $$rollNumberVar!="")){
	      echo "<script>if(document.getElementById('".$otherExam."DetailsCheckbox')) { document.getElementById('".$otherExam."DetailsCheckbox').checked = true; toggleExamDetails('".$otherExam."'); } </script>";
	}
}

?>
