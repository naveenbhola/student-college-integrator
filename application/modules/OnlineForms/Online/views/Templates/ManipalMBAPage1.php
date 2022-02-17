<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>

			<?php if($action != 'updateScore'):?>

			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='float_L'>
			<select style="width:100px;" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
				  I hereby declare that all the particulars stated in this application form are true to the best of my knowledge and belief. I have read and understood all provisions of admissions and agree to abide by them. I also affirm that I fulfill the eligility requirements for the course/s applied. In event of submission of fraudulent, incorrect or untrue information or suppression or distortion of any fact like educational qualification, marks, nationality etc., I understand that my admission/degree is liable for cancellation. I further understand that my admission is purely provisional subject to the verification of the eligibility conditions.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsManipal' id='agreeToTermsManipal' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
				I agree to the terms stated above

			      <?php if(isset($agreeToTermsManipal) && $agreeToTermsManipal!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsManipal"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsManipal);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsManipal_error'></div></div>

				</div>
				</div>
			</li>

			<?php endif;?>
	

		</ul>
	</div>
</div>
