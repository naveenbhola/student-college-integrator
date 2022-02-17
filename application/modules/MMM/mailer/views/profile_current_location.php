<li>
	<label>Current Location:</label>
	<div class="flLt">
		<div>
			<input type="text" readonly="readonly" onclick="dropdiv('jlocation','locarea', 'iframe2')" class="dropdownin" value="Selected Location(0)" style="width: 293px;" id="jlocation" name="jlocation"/>
		</div>
		<!--Start_OverlayDiv-->
		<div id="iframe2" class="iframe" style="display:none;"><iframe style="height:250px;border: none; width:315px;" class="modalcss1"></iframe></div>
		<div style="display:none;height:250px;width:303px;z-index:100" class="dropdiv" id="locarea">
			<div>
				<input type="checkbox" id="cmsMetroCities" name="cmsMetroCities" onClick="currLOCcheckAll(this)"> <b>Metropolitian Cities</b>
			</div>
			<div id="cmsMetroCities_1">
				<?php foreach($cityList_tier1 as $list) { ?>
						<div style="display:block;padding-left:10px">
							<input type="checkbox" id="<?php echo "currCities_m".$list['cityId']; ?>" name="CurLocArr[]" value="<?php echo $list['cityId']; ?>" currLOCCititesName="<?php echo $list['cityName']; ?>" onClick="currLOCSingleCheckBox(this)"> <?php echo $list['cityName']; ?>
						</div>
				<?php } ?>
			</div>
			<?php
				foreach($country_state_city_list as $list)
				{
					if($list['CountryId'] == 2)
					{
						foreach($list['stateMap'] as $list2)
						{
							echo '<div><input type="checkbox" id="'.$list2['StateName'].'" name="'.$list2['StateName'].'" onClick="currLOCcheckAll(this)"> <b>'.$list2['StateName'].'</b></div>';
							echo '<div id="'.$list2['StateName'].'_1">';
							foreach($list2['cityMap'] as $list3)
							{?>
								<div style="display:block;padding-left:10px"><input type="checkbox" id="<?php echo "currCities".$list3['CityId']; ?>" name="CurLocArr[]" value="<?php echo $list3['CityId']; ?>" currLOCCititesName="<?php echo $list3['CityName']; ?>" onClick="currLOCSingleCheckBox(this)"> <?php echo $list3['CityName']; ?></div>
							<?php }
							echo '</div>';
						}
					}
				}
			?>
		</div>
		<!--End_OverlayDiv-->
		<div style="width:435px;margin-top:3px">
			<div style="border:1px solid #e8e6e7;background:#fffbff;padding:5px">
				<div class="txt_align_r" style="padding-bottom:5px">[&nbsp;<a href="#" onClick="clearAllCurrLocation();return(false);" style="font-size:11px">Remove all</a>&nbsp;]</div>
				<div id="cmsSearch_CurrentLoc"></div>
			</div>
		</div>
	</div>
</li>