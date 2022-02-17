<div class='formChildWrapper'>
	<div class='formSection common-list'>
		<ul>
	
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
					<h3 class="upperCase">Personal Information</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>How did you come to know FIIB?: </label>
				<div class='fieldBoxLarge'>
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20190'   value='Newspapers'  checked  ></input><span >Newspapers</span>
				</span>	
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20191'   value=' Magazines'    ></input><span > Magazines</span>
			    </span>
			    <span class="dflt-chkcbox">	
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20192'   value=' Radio'    ></input><span > Radio</span>
				</span>
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20193'   value=' Outdoor Hoardings'    ></input><span > Outdoor Hoardings</span>
			    </span>
			    <span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20194'   value=' Displays at Metro'    ></input><span > Displays at Metro</span>
				</span>
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20195'   value=' Internet'    ></input><span > Internet</span>
				</span>
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20196'   value=' CP Centers'    ></input><span > CP Centers</span>
				</span>
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20197'   value=' Google'    ></input><span > Google</span>
				</span>
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20198'   value=' Facebook'    ></input><span > Facebook</span>
				</span>
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA20199'   value=' FIIB website'    ></input><span > FIIB website</span>
				</span>
				<span class="dflt-chkcbox">
					<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA201910'   value=' Alumni'    ></input><span > Alumni</span>
				</span>
				<span class="dflt-chkcbox">
				<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA201911'   value=' Friend'    ></input><span > Friend</span></span>
				<span class="dflt-chkcbox">
				<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA201912'   value=' Emailer'    ></input><span > Emailer</span></span>
				<span class="dflt-chkcbox">
				<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA201913'   value=' Linked-in'    ></input><span > Linked-in</span></span>
				<span class="dflt-chkcbox">
				<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA201914'   value=' Ranking issue'    ></input><span > Ranking issue</span></span>
				<span class="dflt-chkcbox">
				<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA201915'   value=' Education web portal'    ></input><span > Education web portal</span></span>
				<span class="dflt-chkcbox">
				<input type='checkbox'      caption="At least one"   name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA201916'   value=' Competitive Exam bulletin'    ></input><span > Competitive Exam bulletin</span></span>
				<span class="dflt-chkcbox">
				<input type='checkbox'      caption="At least one" onclick="hideOthers('HowdidyoucometoknowFIIBFIIBMBA201917');"  name='HowdidyoucometoknowFIIBFIIBMBA2019[]' id='HowdidyoucometoknowFIIBFIIBMBA201917'   value=' Others'    ></input><span > Others</span></span>
				<?php if(isset($HowdidyoucometoknowFIIBFIIBMBA2019) && $HowdidyoucometoknowFIIBFIIBMBA2019!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["HowdidyoucometoknowFIIBFIIBMBA2019[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$HowdidyoucometoknowFIIBFIIBMBA2019);
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
				
				<div style='display:none'><div class='errorMsg' id= 'HowdidyoucometoknowFIIBFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li id="other" style="display: none;">
				<div class='additionalInfoLeftCol'>
				<label>Specify Name:<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SpecifyNameFIIBMBA2019' id='SpecifyNameFIIBMBA2019'  validate="validateStr"  caption="Specify Name"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($SpecifyNameFIIBMBA2019) && $SpecifyNameFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("SpecifyNameFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $SpecifyNameFIIBMBA2019 );  ?>";
				      document.getElementById("SpecifyNameFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SpecifyNameFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
					<h3 class="upperCase">Family Information</h3>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Father_OrganizationFIIBMBA2019' id='Father_OrganizationFIIBMBA2019'  validate="validateStr"    caption="Father's Organization"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Father_OrganizationFIIBMBA2019) && $Father_OrganizationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Father_OrganizationFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Father_OrganizationFIIBMBA2019 );  ?>";
				      document.getElementById("Father_OrganizationFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Father_OrganizationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Mother_OrganizationFIIBMBA2019' id='Mother_OrganizationFIIBMBA2019'  validate="validateStr"    caption="Mother's Organization"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Mother_OrganizationFIIBMBA2019) && $Mother_OrganizationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Mother_OrganizationFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Mother_OrganizationFIIBMBA2019 );  ?>";
				      document.getElementById("Mother_OrganizationFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Mother_OrganizationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Mobile no.:<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Father_MobileFIIBMBA2019' id='Father_MobileFIIBMBA2019'  validate="validateMobileInteger"   required="true"   caption="Father's Mobile no."   minlength="1"   maxlength="10"      value=''   />
				<?php if(isset($Father_MobileFIIBMBA2019) && $Father_MobileFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Father_MobileFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Father_MobileFIIBMBA2019 );  ?>";
				      document.getElementById("Father_MobileFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Father_MobileFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Mobile no.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Mother_MobileFIIBMBA2019' id='Mother_MobileFIIBMBA2019'  validate="validateMobileInteger"    caption="Mother's Mobile no."   minlength="1"   maxlength="10"      value=''   />
				<?php if(isset($Mother_MobileFIIBMBA2019) && $Mother_MobileFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Mother_MobileFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Mother_MobileFIIBMBA2019 );  ?>";
				      document.getElementById("Mother_MobileFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Mother_MobileFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
					<h3 class="upperCase">Education Details</h3>
			</li>

			<li>
					<h3 class="lowerCase">Xth</h3>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Stream:<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Stream_Std_XFIIBMBA2019' id='Stream_Std_XFIIBMBA2019'  validate="validateStr"   required="true"   caption="Stream"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Stream_Std_XFIIBMBA2019) && $Stream_Std_XFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Stream_Std_XFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Stream_Std_XFIIBMBA2019 );  ?>";
				      document.getElementById("Stream_Std_XFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Stream_Std_XFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Certificate/Awards: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Certificate_Awards_Std_XFIIBMBA2019' id='Certificate_Awards_Std_XFIIBMBA2019'  validate="validateStr"    caption="Certificate/Awards"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Certificate_Awards_Std_XFIIBMBA2019) && $Certificate_Awards_Std_XFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Certificate_Awards_Std_XFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Certificate_Awards_Std_XFIIBMBA2019 );  ?>";
				      document.getElementById("Certificate_Awards_Std_XFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Certificate_Awards_Std_XFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Xth Documents: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='Upload_Documents_Std_XFIIBMBA2019'          />
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				<input type='hidden' name='Upload_Documents_Std_XFIIBMBA2019Valid' value=''>
				<div style='display:none'><div class='errorMsg' id= 'Upload_Documents_Std_XFIIBMBA2019_error'></div></div>
				<label id="tenth_file">
					<?php if(isset($Upload_Documents_Std_XFIIBMBA2019) && $Upload_Documents_Std_XFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("tenth_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
					<h3 class="lowerCase">XIIth</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Stream:<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Stream_Std_XIIFIIBMBA2019' id='Stream_Std_XIIFIIBMBA2019'  validate="validateStr"   required="true"   caption="Stream"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Stream_Std_XIIFIIBMBA2019) && $Stream_Std_XIIFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Stream_Std_XIIFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Stream_Std_XIIFIIBMBA2019 );  ?>";
				      document.getElementById("Stream_Std_XIIFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Stream_Std_XIIFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Certificate/Awards: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Certificate_Awards_Std_XIIFIIBMBA2019' id='Certificate_Awards_Std_XIIFIIBMBA2019'  validate="validateStr"    caption="Certificate/Awards"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Certificate_Awards_Std_XIIFIIBMBA2019) && $Certificate_Awards_Std_XIIFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Certificate_Awards_Std_XIIFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Certificate_Awards_Std_XIIFIIBMBA2019 );  ?>";
				      document.getElementById("Certificate_Awards_Std_XIIFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Certificate_Awards_Std_XIIFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>XIIth Documents: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='Upload_Documents_Std_XIIFIIBMBA2019'          />
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				<input type='hidden' name='Upload_Documents_Std_XIIFIIBMBA2019Valid' value=''>
				<div style='display:none'><div class='errorMsg' id= 'Upload_Documents_Std_XIIFIIBMBA2019_error'></div></div>
				<label id="twelfth_file">
					<?php if(isset($Upload_Documents_Std_XIIFIIBMBA2019) && $Upload_Documents_Std_XIIFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("twelfth_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
					<h3 class="lowerCase">Graduation</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Certificate/Awards: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Certificate_Awards_GraduationFIIBMBA2019' id='Certificate_Awards_GraduationFIIBMBA2019'  validate="validateStr"    caption="Certificate/Awards"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Certificate_Awards_GraduationFIIBMBA2019) && $Certificate_Awards_GraduationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Certificate_Awards_GraduationFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Certificate_Awards_GraduationFIIBMBA2019 );  ?>";
				      document.getElementById("Certificate_Awards_GraduationFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Certificate_Awards_GraduationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Graduation Documents: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='Upload_Documents_GraduationFIIBMBA2019'          />
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				<input type='hidden' name='Upload_Documents_GraduationFIIBMBA2019Valid' value=''>
				<div style='display:none'><div class='errorMsg' id= 'Upload_Documents_GraduationFIIBMBA2019_error'></div></div>
				<label id="ug_file">
					<?php if(isset($Upload_Documents_GraduationFIIBMBA2019) && $Upload_Documents_GraduationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("ug_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
					<h3 class="lowerCase">Post Graduation</h3>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Stream: </label>
				<div class='fieldBoxLarge'>
				<select name='Stream_Post_GraduationFIIBMBA2019' id='Stream_Post_GraduationFIIBMBA2019'      validate="validateSelect"    caption="Stream"  ><option value='' selected>Select</option><option value='MA' > MA</option><option value='M COM' > M COM</option><option value='M Pharma' > M Pharma</option><option value='M.Sc' > M.Sc</option><option value='M.E' > M.E</option><option value=' M.Tech' > M.Tech</option></select>
				<?php if(isset($Stream_Post_GraduationFIIBMBA2019) && $Stream_Post_GraduationFIIBMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Stream_Post_GraduationFIIBMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Stream_Post_GraduationFIIBMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Stream_Post_GraduationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Institute: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Institution_Post_GraduationFIIBMBA2019' id='Institution_Post_GraduationFIIBMBA2019'  validate="validateStr"    caption="Institute"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Institution_Post_GraduationFIIBMBA2019) && $Institution_Post_GraduationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Institution_Post_GraduationFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Institution_Post_GraduationFIIBMBA2019 );  ?>";
				      document.getElementById("Institution_Post_GraduationFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Institution_Post_GraduationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Board_Post_GraduationFIIBMBA2019' id='Board_Post_GraduationFIIBMBA2019'  validate="validateStr"    caption="Board/University"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Board_Post_GraduationFIIBMBA2019) && $Board_Post_GraduationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Board_Post_GraduationFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Board_Post_GraduationFIIBMBA2019 );  ?>";
				      document.getElementById("Board_Post_GraduationFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Board_Post_GraduationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Year of Completion: </label>
				<div class='fieldBoxLarge'>
				<select name='YearofCompletion_Post_GraduationFIIBMBA2019' id='YearofCompletion_Post_GraduationFIIBMBA2019'      validate="validateSelect"    caption="Year of Completion"  ><option value='' selected>Select</option><option value=' 2019' > 2019</option><option value=' 2018' > 2018</option><option value=' 2017' > 2017</option><option value=' 2016' > 2016</option><option value=' 2015' > 2015</option><option value=' 2014' > 2014</option><option value=' 2013' > 2013</option><option value=' 2012' > 2012</option><option value=' 2011' > 2011</option><option value=' 2010' > 2010</option><option value=' 2009' > 2009</option><option value=' 2008' > 2008</option><option value=' 2007' > 2007</option><option value=' 2006' > 2006</option><option value=' 2005' > 2005</option><option value=' 2004' > 2004</option><option value=' 2003' > 2003</option><option value=' 2002' > 2002</option><option value=' 2001' > 2001</option><option value=' 2000' > 2000</option><option value=' 1999' > 1999</option><option value=' 1998' > 1998</option><option value=' 1997' > 1997</option><option value=' 1996' > 1996</option><option value=' 1995' > 1995</option><option value=' 1994' > 1994</option><option value=' 1993' > 1993</option><option value=' 1992' > 1992</option><option value=' 1991' > 1991</option><option value=' 1990' > 1990</option><option value=' 1989' > 1989</option><option value=' 1988' > 1988</option><option value=' 1987' > 1987</option><option value=' 1986' > 1986</option><option value=' 1985' > 1985</option><option value=' 1984' > 1984</option><option value=' 1983' > 1983</option><option value=' 1982' > 1982</option><option value=' 1981' > 1981</option><option value=' 1980' > 1980</option><option value=' 1979' > 1979</option><option value=' 1978' > 1978</option><option value=' 1977' > 1977</option><option value=' 1976' > 1976</option><option value=' 1975' > 1975</option><option value=' 1974' > 1974</option><option value=' 1973' > 1973</option><option value=' 1972' > 1972</option><option value=' 1971' > 1971</option></select>
				<?php if(isset($YearofCompletion_Post_GraduationFIIBMBA2019) && $YearofCompletion_Post_GraduationFIIBMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("YearofCompletion_Post_GraduationFIIBMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $YearofCompletion_Post_GraduationFIIBMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'YearofCompletion_Post_GraduationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Percentage_Post_GraduationFIIBMBA2019' id='Percentage_Post_GraduationFIIBMBA2019'  validate="validateFloat"    caption="Percentage"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($Percentage_Post_GraduationFIIBMBA2019) && $Percentage_Post_GraduationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Percentage_Post_GraduationFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Percentage_Post_GraduationFIIBMBA2019 );  ?>";
				      document.getElementById("Percentage_Post_GraduationFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Percentage_Post_GraduationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Certificate/Awards: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Certificate_Awards_Post_GraduationFIIBMBA2019' id='Certificate_Awards_Post_GraduationFIIBMBA2019'  validate="validateStr"    caption="Certificate/Awards"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Certificate_Awards_Post_GraduationFIIBMBA2019) && $Certificate_Awards_Post_GraduationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Certificate_Awards_Post_GraduationFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Certificate_Awards_Post_GraduationFIIBMBA2019 );  ?>";
				      document.getElementById("Certificate_Awards_Post_GraduationFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Certificate_Awards_Post_GraduationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
					<h3 class="lowerCase">Other</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Stream_OtherFIIBMBA2019' id='Stream_OtherFIIBMBA2019'  validate="validateStr"    caption="Stream"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Stream_OtherFIIBMBA2019) && $Stream_OtherFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Stream_OtherFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Stream_OtherFIIBMBA2019 );  ?>";
				      document.getElementById("Stream_OtherFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Stream_OtherFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Institute: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Institution_OtherFIIBMBA2019' id='Institution_OtherFIIBMBA2019'  validate="validateStr"    caption="Institute"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Institution_OtherFIIBMBA2019) && $Institution_OtherFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Institution_OtherFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Institution_OtherFIIBMBA2019 );  ?>";
				      document.getElementById("Institution_OtherFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Institution_OtherFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Board_OtherFIIBMBA2019' id='Board_OtherFIIBMBA2019'  validate="validateStr"    caption="Board/University"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Board_OtherFIIBMBA2019) && $Board_OtherFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Board_OtherFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Board_OtherFIIBMBA2019 );  ?>";
				      document.getElementById("Board_OtherFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Board_OtherFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Year of Completion: </label>
				<div class='fieldBoxLarge'>
				<select name='YearofCompletion_OtherFIIBMBA2019' id='YearofCompletion_OtherFIIBMBA2019'      validate="validateSelect"    caption="Year of Completion"  ><option value='' selected>Select</option><option value=' 2019' > 2019</option><option value=' 2018' > 2018</option><option value=' 2017' > 2017</option><option value=' 2016' > 2016</option><option value=' 2015' > 2015</option><option value=' 2014' > 2014</option><option value=' 2013' > 2013</option><option value=' 2012' > 2012</option><option value=' 2011' > 2011</option><option value=' 2010' > 2010</option><option value=' 2009' > 2009</option><option value=' 2008' > 2008</option><option value=' 2007' > 2007</option><option value=' 2006' > 2006</option><option value=' 2005' > 2005</option><option value=' 2004' > 2004</option><option value=' 2003' > 2003</option><option value=' 2002' > 2002</option><option value=' 2001' > 2001</option><option value=' 2000' > 2000</option><option value=' 1999' > 1999</option><option value=' 1998' > 1998</option><option value=' 1997' > 1997</option><option value=' 1996' > 1996</option><option value=' 1995' > 1995</option><option value=' 1994' > 1994</option><option value=' 1993' > 1993</option><option value=' 1992' > 1992</option><option value=' 1991' > 1991</option><option value=' 1990' > 1990</option><option value=' 1989' > 1989</option><option value=' 1988' > 1988</option><option value=' 1987' > 1987</option><option value=' 1986' > 1986</option><option value=' 1985' > 1985</option><option value=' 1984' > 1984</option><option value=' 1983' > 1983</option><option value=' 1982' > 1982</option><option value=' 1981' > 1981</option><option value=' 1980' > 1980</option><option value=' 1979' > 1979</option><option value=' 1978' > 1978</option><option value=' 1977' > 1977</option><option value=' 1976' > 1976</option><option value=' 1975' > 1975</option><option value=' 1974' > 1974</option><option value=' 1973' > 1973</option><option value=' 1972' > 1972</option><option value=' 1971' > 1971</option></select>
				<?php if(isset($YearofCompletion_OtherFIIBMBA2019) && $YearofCompletion_OtherFIIBMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("YearofCompletion_OtherFIIBMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $YearofCompletion_OtherFIIBMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'YearofCompletion_OtherFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Percentage_OtherFIIBMBA2019' id='Percentage_OtherFIIBMBA2019'  validate="validateFloat"    caption="Percentage"   minlength="1"   maxlength="3"      value=''   />
				<?php if(isset($Percentage_OtherFIIBMBA2019) && $Percentage_OtherFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Percentage_OtherFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Percentage_OtherFIIBMBA2019 );  ?>";
				      document.getElementById("Percentage_OtherFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Percentage_OtherFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Certificate/Awards: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Certificate_Awards_OtherFIIBMBA2019' id='Certificate_Awards_OtherFIIBMBA2019'  validate="validateStr"    caption="Certificate/Awards"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Certificate_Awards_OtherFIIBMBA2019) && $Certificate_Awards_OtherFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Certificate_Awards_OtherFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Certificate_Awards_OtherFIIBMBA2019 );  ?>";
				      document.getElementById("Certificate_Awards_OtherFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Certificate_Awards_OtherFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
					<h3 class="upperCase">Professional/Additional Qualifications (If any)</h3>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Qualification: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='QualificationFIIBMBA2019' id='QualificationFIIBMBA2019'  validate="validateStr"    caption="Qualification"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($QualificationFIIBMBA2019) && $QualificationFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("QualificationFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $QualificationFIIBMBA2019 );  ?>";
				      document.getElementById("QualificationFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'QualificationFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Institute: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='InstitutionFIIBMBA2019' id='InstitutionFIIBMBA2019'  validate="validateStr"    caption="Institute"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($InstitutionFIIBMBA2019) && $InstitutionFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("InstitutionFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $InstitutionFIIBMBA2019 );  ?>";
				      document.getElementById("InstitutionFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'InstitutionFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Board_UniversityFIIBMBA2019' id='Board_UniversityFIIBMBA2019'  validate="validateStr"    caption="Board/University"   minlength="1"   maxlength="255"      value=''   />
				<?php if(isset($Board_UniversityFIIBMBA2019) && $Board_UniversityFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("Board_UniversityFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $Board_UniversityFIIBMBA2019 );  ?>";
				      document.getElementById("Board_UniversityFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Board_UniversityFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Year of Completion: </label>
				<div class='fieldBoxLarge'>
				<select name='YearFIIBMBA2019' id='YearFIIBMBA2019'      validate="validateSelect"    caption="Year of Completion"  ><option value='' selected>Select</option><option value=' 2019' > 2019</option><option value=' 2018' > 2018</option><option value=' 2017' > 2017</option><option value=' 2016' > 2016</option><option value=' 2015' > 2015</option><option value=' 2014' > 2014</option><option value=' 2013' > 2013</option><option value=' 2012' > 2012</option><option value=' 2011' > 2011</option><option value=' 2010' > 2010</option><option value=' 2009' > 2009</option><option value=' 2008' > 2008</option><option value=' 2007' > 2007</option><option value=' 2006' > 2006</option><option value=' 2005' > 2005</option><option value=' 2004' > 2004</option><option value=' 2003' > 2003</option><option value=' 2002' > 2002</option><option value=' 2001' > 2001</option><option value=' 2000' > 2000</option><option value=' 1999' > 1999</option><option value=' 1998' > 1998</option><option value=' 1997' > 1997</option><option value=' 1996' > 1996</option><option value=' 1995' > 1995</option><option value=' 1994' > 1994</option><option value=' 1993' > 1993</option><option value=' 1992' > 1992</option><option value=' 1991' > 1991</option><option value=' 1990' > 1990</option><option value=' 1989' > 1989</option><option value=' 1988' > 1988</option><option value=' 1987' > 1987</option><option value=' 1986' > 1986</option><option value=' 1985' > 1985</option><option value=' 1984' > 1984</option><option value=' 1983' > 1983</option><option value=' 1982' > 1982</option><option value=' 1981' > 1981</option><option value=' 1980' > 1980</option><option value=' 1979' > 1979</option><option value=' 1978' > 1978</option><option value=' 1977' > 1977</option><option value=' 1976' > 1976</option><option value=' 1975' > 1975</option><option value=' 1974' > 1974</option><option value=' 1973' > 1973</option><option value=' 1972' > 1972</option><option value=' 1971' > 1971</option></select>
				<?php if(isset($YearFIIBMBA2019) && $YearFIIBMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("YearFIIBMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $YearFIIBMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'YearFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageFIIBMBA2019' id='PercentageFIIBMBA2019'  validate="validateFloat"    caption="Percentage"   minlength="1"   maxlength="4"      value=''   />
				<?php if(isset($PercentageFIIBMBA2019) && $PercentageFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("PercentageFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $PercentageFIIBMBA2019 );  ?>";
				      document.getElementById("PercentageFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
					<h3 class="upperCase">Disability/Long term Health Condition</h3>
			</li>

			<li>
				<label style="font-weight: bold;text-align: -webkit-auto;width: auto;">
					Please indicate below if you are suffering from any disability or long-term health condition.
				</label>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>I have a Disability: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='Disability_FlagFIIBMBA2019' id='Disability_FlagFIIBMBA20190'   value='No' onclick="hideDisability('No')"  checked  ></input><span >No</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='Disability_FlagFIIBMBA2019' id='Disability_FlagFIIBMBA20191'   value='Yes' onclick="hideDisability('Yes')"   ></input><span > Yes</span>&nbsp;&nbsp;
				<?php if(isset($Disability_FlagFIIBMBA2019) && $Disability_FlagFIIBMBA2019!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Disability_FlagFIIBMBA2019"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Disability_FlagFIIBMBA2019;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Disability_FlagFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li style="display: none;" id="description">
				<div class='additionalInfoLeftCol'>
				<label>Disability Detail: </label>
				<div class='fieldBoxLarge'>
				<textarea name='DisabilityDetailFIIBMBA2019' id='DisabilityDetailFIIBMBA2019'  validate="validateStr"    caption="Disability Detail"   minlength="1"   maxlength="3200"       ></textarea>
				<?php if(isset($DisabilityDetailFIIBMBA2019) && $DisabilityDetailFIIBMBA2019!=""){ ?>
				  <script>
				      document.getElementById("DisabilityDetailFIIBMBA2019").value = "<?php echo str_replace("\n", '\n', $DisabilityDetailFIIBMBA2019 );  ?>";
				      document.getElementById("DisabilityDetailFIIBMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'DisabilityDetailFIIBMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<label style="font-weight: normal;text-align: -webkit-auto;width: auto;">
					In case you answered 'Yes', to the above question, please explain the nature of your disability. Note, the following are commonly understood forms of disability -dyslexia, dyspraxia or AD(H)D; visual impairment uncorrected by glasses; serious hearing impairment; physical impairment or mobility issues; long standing mental health condition; long standing illness or health condition such as cancer, HIV, diabetes, chronic heart disease, or epilepsy; social/communication impairment such as Asperger's syndrome/other autistic spectrum disorder; or a disability, impairment or medical condition that is not listed here.
				</label>
			</li>

			<li>
				<h3>Declaration</h3>
				<div class='fieldBoxLarge' style="width: 100% ; color: #666666; font-style: italic;">
					<ul>
						<li>
						All entries made in the application form are true to the best of my knowledge and belief. I am willing to produce original certificates on demand at any time. I also undertake that I shall abide by the rules and regulations of Fortune Institute of International Business(FIIB).
						</li>
					</ul>
				</div>

				<div class="additionalInfoLeftCol">

					<label style="width: 100%; text-align: left;">
						
						<input type='checkbox' validate='validateChecked'  required="true" caption ='Please accept the terms'   name='FIIBMBA2019_Terms[]' id='FIIBMBA2019_Terms'   value='1'  checked  ></input>
							I agree to the above terms and conditions
						<span ></span>&nbsp;&nbsp;
				
				<?php if(isset($FIIBMBA2019_Terms) && $FIIBMBA2019_Terms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["FIIBMBA2019_Terms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$FIIBMBA2019_Terms);
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
						<div class='errorMsg' id= 'FIIBMBA2019_Terms_error'>
							
						</div>
					</div>
				</label>
				</div>
			</li>

		</ul>
	</div>
</div>

  <script>

  function hideOthers(id)
  {
  	var checked = document.getElementById(id).checked;
  	if(checked==true)
  	{
  		document.getElementById('SpecifyNameFIIBMBA2019').classList.remove("clearFields");
  		document.getElementById('other').setAttribute("style","display:block");
  		document.getElementById('SpecifyNameFIIBMBA2019').setAttribute('required','true');
  	}
  	else
  	{
  		document.getElementById('SpecifyNameFIIBMBA2019').classList.add("clearFields");
  		document.getElementById('other').setAttribute("style","display:none");
  		document.getElementById('SpecifyNameFIIBMBA2019').removeAttribute("required");
  	}
  }

  function hideDisability(str)
  {
  	if(str=="Yes")
  	{
  		document.getElementById('DisabilityDetailFIIBMBA2019').classList.remove("clearFields");
  		document.getElementById('description').setAttribute("style","display:block");
  	}
  	else
  	{
  		document.getElementById('DisabilityDetailFIIBMBA2019').classList.add("clearFields");
  		document.getElementById('description').setAttribute("style","display:none");
  	}
  }

  (function()
  {
  	hideOthers('HowdidyoucometoknowFIIBFIIBMBA201917');
  	hideDisability("<?php echo $Disability_FlagFIIBMBA2019;?>");

  })();

  </script>

  <style type="text/css">
  	.common-list ul li .additionalInfoLeftCol{width: 100%;}
	.common-list ul li .fieldBoxLarge{width: 55%;}
	.dflt-chkcbox {
	    display: inline-block;
	    margin: 0px 5px 5px 0px;
	}

	<style>
  	#appsFormWrapper .fieldBoxLarge select{width: 214px;background: #fff;border: 1px solid #ccc;padding: 4px 10px}
  	#appsFormWrapper .fieldBoxLarge input[type='text']{border-radius: 2px;width: 34%;}
  	#appsFormWrapper .fieldBoxSmall input[type='text']{padding: 5px 2px;border-radius: 2px;}
  	#appsFormWrapper .formChildWrapper h3.upperCase, .formSection ul li h3{background: #f1f1f1;margin: 0 -10px;padding: 10px 15px;}
  	#appsFormWrapper h3.upperCase{    padding: 10px 10px;
    background: #f1f1f1;
    margin: 0 -10px;}
    #appsFormWrapper .formChildWrapper h3.lowerCase, .formSection ul li h3{font-size: 14px;
    background: none;margin: 0 -10px;padding: 10px 15px;}
  	#appsFormWrapper h3.lowerCase{    padding: 10px 10px;
    margin: 0 -10px;}
    .additionalInfoLeftCol label span{color: red;}
    .additionalInfoLeftCol label span{color: red;}
    .errorMsg{pointer-events: none;}
    .fieldBoxLarge input[type="radio"], .fieldBoxLarge input[type="checkbox"] {
    position: relative;top: 2px;}
    #appsFormWrapper select, .layerContent select {
    padding: 1px;
    width: 35%;
    border: 1px solid #D6D6D6;
  </style>
