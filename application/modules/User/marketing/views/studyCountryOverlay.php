<style>.quesAnsBullets{background-image: none;background: none}</style>
<style>.quesAnsBullets{background-image: none;background: none}</style>

<div id="userPreferenceCountry" name="userPreferenceCountry" style="width:535px;border:1px solid #c4c4c6;background-color:#ffffff;display:none;position:absolute;">
	<div class="brd" style="margin-top:-1px;*margin-top:-9px;margin-top:0;">
		<div style="margin-left:5px;background:#FFF" id="overlayLocationHolder" name="overlayLocationHolder">
			<div>
				<div id="subCountry_<?php echo $value['id']; ?>" style="margin-bottom:5px">
					<div id="countryCities_<?php echo $value['id']; ?>">
						<div id="overlayCityHolder" name="overlayCityHolder">
								<div class="lineSpace_12">&nbsp;</div>
								<div style="height:260px;overflow:hidden" id="countryOverlayForStudyAbroad">
								            <ul class="ctryContent float_L" style="width:25%">
                                <?php
				    $seperateLists = array(2,3,4);
                                    $regionIdInUse = null;
                                    foreach($regions as $regionId => $region) {
                                        foreach($region as $countryId =>  $country) {
                                            $countryName = $country['name'];
                                            $regionName = $country['regionname'];
                                            $paddingLeft = 'padding-left:25px';
                                            $tagName = $regionName;
                                            if($regionId == '') {
                                                $paddingLeft = '';
                                                $tagName = $countryName;
                                                $countryName = $countryName;
                                            }
                                            ?>
                                            <?php                                            
                                            if($regionId !== '' && $regionId != $regionIdInUse) {
                                                if(in_array($regionId, $seperateLists)){
                                                    ?>
                                                        </ul>
                                                        <ul class="ctryContent float_L" style="width:25%">
                                                    <?php
                                                }
                                                $regionIdInUse = $regionId;
                                ?>
                                    <li>
                                        <input type="checkbox" name='region_<?php echo $regionId; ?>' id="region_<?php echo $regionId; ?>" value="<?php echo $regionId;?>" onclick="toggleRegionCountries($('region_<?php echo $regionId; ?>'));"/>
                                        <span style='font-size:12px;color:#000000;padding-left:5px'><b><?php echo $regionName; ?></b></span>
                                    </li>
                                <?php
                                            }
                                ?>
                                    <li style="<?php echo $paddingLeft; ?>" region="<?php echo $regionId?>">
                                        <input id="countryInput_<?php echo $countryId ?>" type="checkbox" name="ctry[]" value="<?php echo $countryId;?>" onclick="toggleRegionForCountry($('countryInput_<?php echo $countryId ?>'));" tag="<?php echo $tagName; ?>"/>
                                        <span style='font-size:12px;color:#0066DD;padding-left:5px;'><?php echo $countryName; ?></span>
                                    </li>
                                <?php
                                        }
                                    }
                                ?>
								</ul>
                                    <div class="clear_L"></div>
								</div>
						</div>                                            
					</div>
					<div class="lineSpace_10">&nbsp;</div>
				</div>
			</div>
		</div>
		<div align="center"><input type="button" class="okBtn" onClick="getDataFromCityLayer1();" value="OK" /></div>
		<div class="lineSpace_10"> &nbsp; </div>
	</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
