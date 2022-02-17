<div id="add_location" style="display:none">
	<div class="row">
		<div>
			<div class="r1 bld">Country:</div>
			<div class="r2">
			   <select onchange="getCitiesWithCollege('si_cities','si_country');" id="si_country" validate="validateSelect" minlength="1" maxlength="100" caption="Country" >
					<option value="">Select Country</option>
					<?php
						foreach($country_list as $country) :
							$countryId = $country['countryID'];
							$countryName = $country['countryName'];
							if($countryId == 1) { continue; }
								$selected = "";
							if($countryId == 2) { $selected = "selected='selected'"; }
				 	?>
					<option value="<?php echo $countryId; ?>" <?php //echo $selected; ?>><?php echo $countryName; ?></option>
				 <?php endforeach; ?>
				</select>
			</div>
			<div class="clear_L"></div>
		</div>		
	</div>
	<div class="row errorPlace">
	 	<div class="r1">&nbsp;</div>
	 	<div class="r2 errorMsg" id="si_country_error" ></div>
	 	<div class="clear_L"></div>
	</div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
		<div>
			<div>
				<div class="r1 bld">City:</div>
				<div class="r2">
				   <select onchange="getInstitutesForCityList();" id="si_cities" style="width:150px" validate="validateSelect" minlength="1" maxlength="100" caption="City">
					<option value="">Select City</option>
					</select>
				</div>
				<div class="clear_L"></div>
			</div>
		</div>
	</div>
	<div class="row errorPlace">
	 	<div class="r1">&nbsp;</div>
	 	<div class="r2 errorMsg" id="si_cities_error" ></div>
	 	<div class="clear_L"></div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
	<div class="row">
		<div>
			<div>
				<div class="r1 bld">College/Institute:</div>
				<div class="r2">
				   <select id="si_colleges" style="width:200px;" validate="validateSelect"  minlength="1" maxlength="1000" caption="College/Institute" >
					<option value="">Select College/Institute</option>
					</select>
				</div>
				<div class="clear_L"></div>
			</div>
		</div>
	</div>
	<div class="row errorPlace">
	 	<div class="r1">&nbsp;</div>
	 	<div class="r2 errorMsg" id="si_colleges_error" ></div>
	 	<div class="clear_L"></div>
	</div>
	<div class="row errorPlace">
	 	<div class="r1">&nbsp;</div>
	 	<div class="r2 errorMsg" id="add_institute_error" ></div>
	 	<div class="clear_L"></div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
	<div class="row">
		<div class="r1"><input type="button" onClick="addInstitute();" value="Ok"></div>
		<div class="r2"><input type="button" onClick="hideInstitute();" value="Cancel"></div>
	</div>
	<div class="clear_L">&nbsp;</div>							
</div>
