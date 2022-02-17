	<!--<div class="row">
		<div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Location</span></div>
		<div class="grayLine mar_top_5p"></div>							
	</div>-->
	<script>var noOfLocations = 1;</script>
	<div id="location_main_div">
	<?php for($i=1; $i<=10;$i++):?>
	<div id="location_indi<?php echo $i;?>" <?php echo $i==1?$str="":$str="style=\"display:none\""; echo $str; ?> >
	<div class="lineSpace_13">&nbsp;</div>
	<div class="row">
		<div>
			<div class="r1 bld">Country:<span class="redcolor">*</span></div>
			<div class="r2">
				<select <?php echo $i==1?$str="name=\"i_country_id[]\"":$str=""; echo $str; ?> id="c_country<?php echo $i;?>" onChange="getCitiesForCountryListAnother(<?php echo $i;?>);" style="width:100px" validate="validateSelect" required="<?php echo $i==1?$str="true":$str="";echo $str; ?>" minlength="1" maxlength="100" caption="Country" >
					<option value="">Select Country</option>
					<?php
						foreach($country_list as $country) :
							$countryId = $country['countryID'];
							$countryName = $country['countryName'];
							if($countryId == 1) { continue; }
								$selected = "";
							if($countryId == 2) { $selected = "selected='selected'"; }
				 ?>
						<option value="<?php echo $countryId; ?>" <?php // echo $selected; ?>><?php echo $countryName; ?></option>
				 <?php endforeach; ?>
				</select>
			</div>
			<div class="clear_L"></div>
		</div>		
	</div>
	<div class="row errorPlace">
	 	<div class="r1">&nbsp;</div>
	 	<div class="r2 errorMsg" id="c_country<?php echo $i;?>_error" ></div>
	 	<div class="clear_L"></div>
	</div>
	 
   <div class="lineSpace_13">&nbsp;</div>
   <div class="row">
		<div>
			<div>
				<div class="r1 bld">City:<span class="redcolor">*</span></div>
				<div class="r2">
					<select <?php echo $i==1?$str="name=\"i_city_id[]\"":$str=""; echo $str; ?> id="c_cities<?php echo $i;?>" onChange="showOtherOptions(this);" style="width:125px" validate="validateSelect" required="<?php echo $i==1?$str="true":$str="";echo $str; ?>" minlength="1" maxlength="100" caption="City"></select>
					<input type="text" validate="validateStr" maxlength="100" minlength="3" name="cities<?php echo $i;?>_other" id="c_cities<?php echo $i;?>_other" value="" style="display:none" caption="City" />
				</div>
				<div class="clear_L"></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div class="r2 errorMsg"  id="c_cities<?php echo $i;?>_other_error"></div> 
				<div class="clear_L"></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div class="r2 errorMsg"  id="c_cities<?php echo $i;?>_error"></div> 
				<div class="clear_L"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
	
	<div class="row">
		<div>
			<div>
				<div class="r1 bld">Address:</div>
				<div class="r2">
				   <textarea <?php echo $i==1?$str="name=\"address[]\"":$str=""; echo $str; ?> id="address<?php echo $i;?>" style="height:30px;" class="w62_per" minlength="0" maxlength="500" caption="Address" ></textarea>
					<?php if ($i!=1): ?>
					<a onclick="removeLocation(<?php echo $i; ?>);" href="javascript:void(0);" > Remove</a>
					<?php endif;?>
				</div>
				<div class="clear_L"></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div class="r2 errorMsg"  id="address<?php echo $i;?>_error"></div> 
				<div class="clear_L"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
	</div>
	<?php endfor; ?>
	</div>

	<div class="row" id="another_location">
		<div>
			<div>
				<div class="r1 bld">&nbsp;</div>
				<div class="r2">
					<a onclick="addLocation();" href="javascript:void(0);" >Add Another</a>
				</div>
				<div class="clear_L"></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
                                <div class="r2 errorMsg"  id="another_location_error"></div> 
				<div class="clear_L"></div>
			</div>
		</div>
	</div>
<div class="lineSpace_20">&nbsp;</div>	
