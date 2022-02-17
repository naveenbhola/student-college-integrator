<li>
	<label>Preferred Location:</label>
	<div class="flLt">
		<div>
			<input type="text" readonly="readonly" onclick="dropdiv('prefjlocation','preflocarea', 'prefiframe2')" class="dropdownin" value="Selected Location(0)" style="width: 293px;" id="prefjlocation" name="prefjlocation"/>
		</div>
		<!--Start_OverlayDiv-->
		<div id="prefiframe2" class="iframe" style="display:none;"><iframe style="height:310px;border: none;_width:315px;" class="modalcss1"></iframe></div>
		<div style="display:none;height:310px;width:303px;z-index:100" class="dropdiv" id="preflocarea">
			<div>
			<?php
				foreach($country_state_city_list as $list)
				{
			?>
					<div style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>2))); ?>" prefLOCCititesName="<?php echo "Anywhere in ".$list['CountryName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo "Anywhere in ".$list['CountryName']; ?></div>
			<?php
					if($list['CountryId'] == 2)
					{
						foreach($list['stateMap'] as $list2)
						{
			?>
							<div style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>$list2['StateId'],'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>$list2['StateId'],'countryId'=>2))); ?>" prefLOCCititesName="<?php echo "Anywhere in ".$list2['StateName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo "Anywhere in ".$list2['StateName']; ?></div>
			<?php
						}
					}
				}
				$metroCityIdArray = array();
				foreach($cityList_tier1 as $list)
				{
			?>
					<div style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>$list['cityId'],'stateId'=>$list['stateId'],'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>$list['cityId'],'stateId'=>$list['stateId'],'countryId'=>2))); ?>" prefLOCCititesName="<?php echo $list['cityName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo $list['cityName']; ?></div>
			<?php
					array_push($metroCityIdArray,$list['cityId']);
				}
			?>
			</div>
			<div>
			<?php
				foreach($country_state_city_list as $list)
				{
					if($list['CountryId'] == 2)
					{
						foreach($list['stateMap'] as $list2)
						{
							foreach($list2['cityMap'] as $list3)
							{
								if($list3['Tier'] =='1' || $list3['Tier'] =='2')
								{
									if(!in_array($list3['CityId'],$metroCityIdArray))
									{
			?>
										<div style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>$list3['CityId'],'stateId'=>$list2['StateId'],'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>$list3['CityId'],'stateId'=>$list2['StateId'],'countryId'=>2))); ?>" prefLOCCititesName="<?php echo $list3['CityName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo $list3['CityName']; ?></div>
			<?php
									}
								}
							}
						}
					}
				}
			?>
			</div>
		</div>
		<!--End_OverlayDiv-->
		<div style="width:435px;margin-top:3px">
			<div style="border:1px solid #e8e6e7;background:#fffbff;padding:5px">
				<div class="txt_align_r" style="padding-bottom:5px">[&nbsp;<a href="#" onClick="clearAllPrefLocation();return(false);" style="font-size:11px">Remove all</a>&nbsp;]</div>
				<div id="cmsSearch_PrefLoc"></div>
			</div>
		</div>
	</div>
</li>