<h3 class="upperCase">Additional Education Fields: </h3>
                <ul>
		    <li>
                         <div class="formColumns">
			    <label>Class 10th School Address:</label>
			    <div class="fieldBoxLarge">
			    <input type='text' name='schooladdress10MICA' id='schooladdress10MICA'   class ="textboxLarge" required="true" validate="validateStr" caption="Class 10th address" minlength="2"   maxlength="100"  tip="Enter the address of your class 10th School."   value=''  />
			    <?php if(isset($schooladdress10MICA) && $schooladdress10MICA!=""){ ?>
				    <script>
					document.getElementById("schooladdress10MICA").value = "<?php echo str_replace("\n", '\n', $schooladdress10MICA );  ?>";
					document.getElementById("schooladdress10MICA").style.color = "";
				    </script>
				  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'schooladdress10MICA_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li style=" border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
			  <div class="formColumns">
				<label>City: </label>
				<div class="fieldBoxLarge">
					<input class="textboxLarge" type='text' name='schoolcity10MICA' id='schoolcity10MICA'  validate="validateStr"   required="true" caption="City"  minlength="2"   maxlength="25"     tip="Please type the City Name of your class 10th school."   value=''  />
					<?php if(isset($schoolcity10MICA) && $schoolcity10MICA!=""){ ?>
					<script>
					document.getElementById("schoolcity10MICA").value = "<?php echo str_replace("\n", '\n', $schoolcity10MICA);  ?>";
					document.getElementById("schoolcity10MICA").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'schoolcity10MICA_error'></div></div>
				</div>
                        </div>
                        <div class="formColumns">
			    <label>Pincode: </label>
			    <div class="fieldBoxSmall">
			    <input class="textboxSmall" type='text' name='pincode10MICA' id='pincode10MICA'  validate="validateInteger"   required="true"   caption="Pincode"   minlength="4"   maxlength="6"     tip="Please type in six-digit pin code of your class 10th school."   value=''  />
			    <?php if(isset($pincode10MICA) && $pincode10MICA!=""){ ?>
					    <script>
						document.getElementById("pincode10MICA").value = "<?php echo str_replace("\n", '\n', $pincode10MICA );  ?>";
						document.getElementById("pincode10MICA").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'pincode10MICA_error'></div></div>
			    </div>
                        </div>
                    </li>
                </ul>
		
		  <ul>
		    <li>
                         <div class="formColumns">
			    <label>Class 12th School Address:</label>
			    <div class="fieldBoxLarge">
			    <input type='text' name='schooladdress12MICA' id='schooladdress12MICA'   class ="textboxLarge" required="true" validate="validateStr" caption="Class 12th address" minlength="2"   maxlength="100"  tip="Enter the address of your class 12th School."   value=''  />
			    <?php if(isset($schooladdress12MICA) && $schooladdress12MICA!=""){ ?>
				    <script>
					document.getElementById("schooladdress12MICA").value = "<?php echo str_replace("\n", '\n', $schooladdress12MICA );  ?>";
					document.getElementById("schooladdress12MICA").style.color = "";
				    </script>
				  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'schooladdress12MICA_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li style=" border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
			  <div class="formColumns">
				<label>City: </label>
				<div class="fieldBoxLarge">
					<input class="textboxLarge" type='text' name='schoolcity12MICA' id='schoolcity12MICA'  validate="validateStr"   required="true" caption="City"  minlength="2"   maxlength="25"     tip="Please type the City Name of your class 12th school."   value=''  />
					<?php if(isset($schoolcity12MICA) && $schoolcity12MICA!=""){ ?>
					<script>
					document.getElementById("schoolcity12MICA").value = "<?php echo str_replace("\n", '\n', $schoolcity12MICA);  ?>";
					document.getElementById("schoolcity12MICA").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'schoolcity12MICA_error'></div></div>
				</div>
                        </div>
                        <div class="formColumns">
			    <label>Pincode: </label>
			    <div class="fieldBoxSmall">
			    <input class="textboxSmall" type='text' name='pincode12MICA' id='pincode12MICA'  validate="validateInteger"   required="true"   caption="Pincode"   minlength="4"   maxlength="8"     tip="Please type in six-digit pin code of your class 12th school."   value=''  />
			    <?php if(isset($pincode12MICA) && $pincode12MICA!=""){ ?>
					    <script>
						document.getElementById("pincode12MICA").value = "<?php echo str_replace("\n", '\n', $pincode12MICA );  ?>";
						document.getElementById("pincode12MICA").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'pincode12MICA_error'></div></div>
			    </div>
                        </div>
                    </li>
                </ul>
		
		  <ul>
		    <li>
                         <div class="formColumns">
			    <label>Graduation College Address:</label>
			    <div class="fieldBoxLarge">
			    <input type='text' name='graduationaddressMICA' id='graduationaddressMICA'   class ="textboxLarge" required="true" validate="validateStr" caption="Graduation College address" minlength="2"   maxlength="100"  tip="Enter the address of Graduation College."   value=''  />
			    <?php if(isset($graduationaddressMICA) && $graduationaddressMICA!=""){ ?>
				    <script>
					document.getElementById("graduationaddressMICA").value = "<?php echo str_replace("\n", '\n', $graduationaddressMICA );  ?>";
					document.getElementById("graduationaddressMICA").style.color = "";
				    </script>
				  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'graduationaddressMICA_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li style=" border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
			  <div class="formColumns">
				<label> City: </label>
				<div class="fieldBoxLarge">
					<input class="textboxLarge" type='text' name='graduationcityMICA' id='graduationcityMICA'  validate="validateStr"   required="true" caption="City"  minlength="2"   maxlength="25"     tip="Please type the City Name of your Graduation College."   value=''  />
					<?php if(isset($graduationcityMICA) && $graduationcityMICA!=""){ ?>
					<script>
					document.getElementById("graduationcityMICA").value = "<?php echo str_replace("\n", '\n', $graduationcityMICA);  ?>";
					document.getElementById("graduationcityMICA").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'graduationcityMICA_error'></div></div>
				</div>
                        </div>
                        <div class="formColumns">
			    <label>Pincode: </label>
			    <div class="fieldBoxSmall">
			    <input class="textboxSmall" type='text' name='graduationPincodeMICA' id='graduationPincodeMICA'  validate="validateInteger"   required="true"   caption="Pincode"   minlength="4"   maxlength="8"     tip="Please type in six-digit pin code of your Graduation College."   value=''  />
			    <?php if(isset($graduationPincodeMICA) && $graduationPincodeMICA!=""){ ?>
					    <script>
						document.getElementById("graduationPincodeMICA").value = "<?php echo str_replace("\n", '\n', $graduationPincodeMICA );  ?>";
						document.getElementById("graduationPincodeMICA").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'graduationPincodeMICA_error'></div></div>
			    </div>
                        </div>
                    </li>
                </ul>
		
		  <ul>
		    <li>
                         <div class="formColumns">
			    <label>PG/Masters College Address:</label>
			    <div class="fieldBoxLarge">
			    <input type='text' name='PGaddressMICA' id='PGaddressMICA'   class ="textboxLarge" validate="validateStr"  caption="PG College Address" minlength="2"   maxlength="100"  tip="Enter the address of your PG College.If this is not applicable in your case, just enter 'NA'."   value=''  />
			    <?php if(isset($PGaddressMICA) && $PGaddressMICA!=""){ ?>
				    <script>
					document.getElementById("PGaddressMICA").value = "<?php echo str_replace("\n", '\n', $PGaddressMICA );  ?>";
					document.getElementById("PGaddressMICA").style.color = "";
				    </script>
				  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'PGaddressMICA_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li style=" border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
			  <div class="formColumns">
				<label>  City: </label>
				<div class="fieldBoxLarge">
					<input class="textboxLarge" type='text' name='PGcityMICA' id='PGcityMICA'  validate="validateStr"  caption="City"  minlength="2"   maxlength="25"     tip="Please type the City Name of your PG/Masters College.If this is not applicable in your case, just enter 'NA'. "   value=''  />
					<?php if(isset($PGcityMICA) && $PGcityMICA!=""){ ?>
					<script>
					document.getElementById("PGcityMICA").value = "<?php echo str_replace("\n", '\n', $PGcityMICA);  ?>";
					document.getElementById("PGcityMICA").style.color = "";
					</script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'PGcityMICA_error'></div></div>
				</div>
                        </div>
                        <div class="formColumns">
			    <label>  Pincode: </label>
			    <div class="fieldBoxSmall">
			    <input class="textboxSmall" type='text' name='PGPincodeMICA' id='PGPincodeMICA'  validate="validateInteger"    caption="Pincode"   minlength="4"   maxlength="8"     tip="Please type in six-digit pin code of your PG/Masters College.If this is not applicable in your case, just enter 'NA'."   value=''  />
			    <?php if(isset($PGPincodeMICA) && $PGPincodeMICA!=""){ ?>
					    <script>
						document.getElementById("PGPincodeMICA").value = "<?php echo str_replace("\n", '\n', $PGPincodeMICA );  ?>";
						document.getElementById("PGPincodeMICA").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'PGPincodeMICA_error'></div></div>
			    </div>
                        </div>
                    </li>
                </ul>