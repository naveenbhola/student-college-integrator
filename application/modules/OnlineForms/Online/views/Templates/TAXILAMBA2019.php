<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	
			<li>
					<h3 class="upperCase">Post Graduation Details (Optional)</h3>
			</li>
	
			<li>
				<div class='additionalInfoLeftCol'>
				<label>PG Stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PG_StreamTAXILAMBA2019' id='PG_StreamTAXILAMBA2019'  validate="validateStr"    caption="Stream Of Post Graduation"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($PG_StreamTAXILAMBA2019) && $PG_StreamTAXILAMBA2019!=""){ ?>
				  <script>
				      document.getElementById("PG_StreamTAXILAMBA2019").value = "<?php echo str_replace("\n", '\n', $PG_StreamTAXILAMBA2019 );  ?>";
				      document.getElementById("PG_StreamTAXILAMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PG_StreamTAXILAMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>PG College/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PG_College_UniversityTAXILAMBA2019' id='PG_College_UniversityTAXILAMBA2019'  validate="validateStr"    caption="Name of the university, college or educational institution of Post Graduation"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($PG_College_UniversityTAXILAMBA2019) && $PG_College_UniversityTAXILAMBA2019!=""){ ?>
				  <script>
				      document.getElementById("PG_College_UniversityTAXILAMBA2019").value = "<?php echo str_replace("\n", '\n', $PG_College_UniversityTAXILAMBA2019 );  ?>";
				      document.getElementById("PG_College_UniversityTAXILAMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PG_College_UniversityTAXILAMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>PG Year: 
				<span class="imageSizeInfo">If you are still pursuing your post-graduation, enter the expected year of passing.</span></label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PG_YearTAXILAMBA2019' id='PG_YearTAXILAMBA2019'  validate="validateInteger"    caption="Post Graduation Year"   minlength="4"   maxlength="4"      value=''   />
				<?php if(isset($PG_YearTAXILAMBA2019) && $PG_YearTAXILAMBA2019!=""){ ?>
				  <script>
				      document.getElementById("PG_YearTAXILAMBA2019").value = "<?php echo str_replace("\n", '\n', $PG_YearTAXILAMBA2019 );  ?>";
				      document.getElementById("PG_YearTAXILAMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PG_YearTAXILAMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>PG Result:
					<span class="imageSizeInfo"> If you are still pursuing your post-graduation,fill the percentage till the last attempted semester.</span></label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PG_ResultTAXILAMBA2019' id='PG_ResultTAXILAMBA2019'  validate="validateStr"    caption="Percentage or Grade obtained in Post Graduation"   minlength="1"   maxlength="4"      value=''   />
				<?php if(isset($PG_ResultTAXILAMBA2019) && $PG_ResultTAXILAMBA2019!=""){ ?>
				  <script>
				      document.getElementById("PG_ResultTAXILAMBA2019").value = "<?php echo str_replace("\n", '\n', $PG_ResultTAXILAMBA2019 );  ?>";
				      document.getElementById("PG_ResultTAXILAMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PG_ResultTAXILAMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Statement Of Purpose:
					<span class="imageSizeInfo">(Must contain atleast 150 characters)</span></label>
				<div class='fieldBoxLarge'>
				<textarea name='SOPTAXILAMBA2019' id='SOPTAXILAMBA2019'  validate="validateStr"    caption="Statement of Purpose"   minlength="150"   maxlength="3200"       ></textarea>
				<?php if(isset($SOPTAXILAMBA2019) && $SOPTAXILAMBA2019!=""){ ?>
				  <script>
				      document.getElementById("SOPTAXILAMBA2019").value = "<?php echo str_replace("\n", '\n', $SOPTAXILAMBA2019 );  ?>";
				      document.getElementById("SOPTAXILAMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SOPTAXILAMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3>Declaration</h3>
				<div class="fieldBoxLarge" style="width: 100% ; color: #666666; font-style: italic;">
					<ul>
						<li>
							All entries made in the application form are true to the best of my knowledge and belief. I am willing to produce original certificates on demand at any time. I also undertake that I shall abide by the rules and regulations of Taxila Business School, Jaipur.
						</li>
					</ul>
				</div>

				<div class="additionalInfoLeftCol">

					<label style="width: 100%; text-align: left;">
						<input type='checkbox' validate='validateChecked'  required="true" caption ='Please accept the terms'   name='TAXILA_Terms[]' id='TAXILA_Terms'   value='1'  checked   onmouseout="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();">
							
						</input>I agree to the above terms and conditions
						<span onmouseout="showTipOnline('Please check to accept terms',this);"
						 onmouseout="hidetip()"></span>&nbsp;&nbsp;
				
				<?php if(isset($TAXILA_Terms) && $TAXILA_Terms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["TAXILA_Terms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$TAXILA_Terms);
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
							<div class='errorMsg' id= 'TAXILA_Terms_error'>
								
							</div>
						</div>
					</label>
				</div>
			</li>


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

    <style>
  	#appsFormWrapper .fieldBoxLarge select{width: 214px;background: #fff;border: 1px solid #ccc;padding: 4px 0px}
  	#appsFormWrapper .fieldBoxLarge input[type='text']{border-radius: 2px;width: 73%;}
  	#appsFormWrapper .fieldBoxSmall input[type='text']{padding: 5px 2px;border-radius: 2px;}
  	#appsFormWrapper .formChildWrapper h3.upperCase, .formSection ul li h3{background: #f1f1f1;margin: 0 -10px;padding: 10px 15px;}
  	#appsFormWrapper h3.upperCase{    padding: 10px 10px;
    background: #f1f1f1;
    margin: 0 -10px;}

    .errorMsg{pointer-events: none;}
    .fieldBoxLarge input[type="radio"], .fieldBoxLarge input[type="checkbox"] {
    position: relative;top: 2px;}
  </style>
