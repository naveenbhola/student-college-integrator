<li>
	<label>Destination Country:</label>
	<div class="flLt">
		<div>
			<input type="text" readonly="readonly" onclick="dropdiv('prefjlocation','preflocarea', 'prefiframe2')" class="dropdownin" value="Selected Location(0)" style="width: 293px;" id="prefjlocation" name="prefjlocation" />
		</div>
		<!--Start_OverlayDiv-->
		<div id="prefiframe2" class="iframe" style="display: none;"><iframe style="height: 310px; border: none; _width: 315px;" class="modalcss1"></iframe></div>
		<div style="display: none; height: 310px; width: 303px; z-index: 100" class="dropdiv" id="preflocarea">
			<div>
			<?php
				foreach($regions as $regionId => $region) {
					foreach($region as $countryId =>  $country) {
						$destinationCountryList[$countryId] = $country;
					}
				}
				uasort($destinationCountryList,function($dc1,$dc2) {
					return strcasecmp($dc1['name'],$dc2['name']);
				});
					
				foreach($destinationCountryList as $countryId => $country) {
					if($country['name']){
			?>	
					<div style="display:block;padding-left:<?php echo $paddingLeft; ?>" region="<?php echo $regionId; ?>">
					    <input type="checkbox" id="country_<?php echo $countryId; ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>$countryId))); ?>" prefLOCCititesName="<?php echo strip_tags($country['name']); ?>" onClick="prefLOCSingleCheckBox($('country_<?php echo $countryId; ?>'))"/> 
					    <?php echo $country['name']; ?>
					</div>
			<?php
					}
				}
			?>
			
			<!--?php
			//	$regionIdInUse = '';
			//	foreach($regions as $regionId => $region) {
			//		foreach($region as $countryId =>  $country) {
			//			$countryName = $country['name'];
			//			$regionName = $country['regionname'];
			//			$paddingLeft = '20px';
			//			if($regionId == '') {
			//				$paddingLeft = '5px';
			//				$countryName = '<b>'. $countryName .'</b>';
			//			}
			//			if($regionId !== '' && $regionId != $regionIdInUse) {
			//				$regionIdInUse = $regionId;
			//?>
							<div style="display: block; padding-left: 5px">
								<input type="checkbox" value="<!?php echo $regionId; ?>" id="region_<!?php echo $regionId; ?>" name="region_<!?php echo $regionId; ?>" onClick="toggleRegionCountries(this)" />
								<b><!?php echo $regionName; ?></b>
							</div>
			<!--?php
						}
			?>
						<div style="display:block;padding-left:<!?php echo $paddingLeft; ?>" region="< ?php echo $regionId; ?>">
							<input type="checkbox" id="country_< ?php echo $countryId; ?>" name="prefLocArr[]" value="< ?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>$countryId))); ?>" prefLOCCititesName="< ?php echo strip_tags($countryName); ?>" onClick="prefLOCSingleCheckBox(this)" />
							< ?php echo $countryName; ?>
						</div>
			<!--?php
					}
				}
			?-->
			</div>
		</div>
		<!--End_OverlayDiv-->
		<div style="width: 435px; margin-top: 3px">
			<div style="border: 1px solid #e8e6e7; background: #fffbff; padding: 5px">
				<div class="txt_align_r" style="padding-bottom: 5px">[&nbsp;<a href="#" onClick="clearAllPrefLocation();return(false);" style="font-size: 11px">Remove all</a>&nbsp;]</div>
				<div id="cmsSearch_PrefLoc"></div>
			</div>
		</div>
	</div>
</li>
