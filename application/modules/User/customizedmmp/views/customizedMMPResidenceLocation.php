<div>
	<div class="float_L" style="width:35%;line-height:18px">
		<div class="txt_align_r" style="padding-right:5px">Residence Location<b class="redcolor">*</b>:</div>
	</div>
	<div style="float:right;width:63%;text-align:left;">
		<div id="residenceLoc">
			 <select style = "width:90%;" class = "normaltxt_11p_blk_arial fontSize_12p" id = "cities_studyAbroad" name = "citiesofresidence1" validate = "validateSelect" required = "true" caption = "your city of residence">
				<option value=""><b>Select City</b></option>
				<?php
					$optionSelectedStr = '';
					if ( isset($data[0]['city']) ) {
						$userSelectedCity = $data[0]['city'];
					}
					foreach($cityTier1 as $list) {
						if ($userSelectedCity == $list['cityId']) {
							$optionSelectedStr = "selected";
						} else { 
							$optionSelectedStr = ''; 
						}
						$optionSelectedStr = '';
				?>
						<option <?php echo $optionSelectedStr; ?> value="<?php echo $list['cityId']; ?>"><?php echo $list['cityName'];?></option>
				<?php
					}
					$optionSelectedStr = '';
				?>
				<?php
				foreach($country_state_city_list as $list) {
					if($list['CountryId'] == 2) {
						foreach($list['stateMap'] as $list2) {
							echo '<OPTGROUP LABEL="'.$list2['StateName'].'">';
							foreach($list2['cityMap'] as $list3) {
								if ($userSelectedCity == $list3['CityId']) {
									$optionSelectedStr = "selected";
								} else { $optionSelectedStr = ''; }
								
								$optionSelectedStr = '';
								?>
									<option <?php echo $optionSelectedStr; ?> value="<?php echo $list3['CityId']; ?>"><?php echo $list3['CityName'];?></option>
									<?php
							}
						}
					}
				}
				?>
			 </select>
		</div>
		<div class="clear_L withClear">&nbsp;</div>
		<div class="row">
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div class="errorMsg" id= "cities_studyAbroad_error"></div>
			</div>
		</div>
	</div>
	<div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>