				<div id="countryCommonOverlay" style="border:1px solid #ABDB47 ;background-color:#E7FfB5; position:absolute;display:none;width:110px" onmouseover="javascript:this.style.display='inline';" onmouseout="this.style.display='none';">
					<?php 
						global $countryData;
						foreach($countryData as $countryId => $country) {
							$countryFlag = isset($country['flag']) ? $country['flag'] : '';
							$countryName = isset($country['name']) ? $country['name'] : '';
					?>
						<a href="/shiksha/country/<?php echo $countryId; ?>">
							<div style="line-height:20px;padding-left:5px"  >
								<?php echo $countryName; ?>
							</div>
						</a>
						<div style="border-top:1px solid #ABDB47;"></div>
						<div class="clear_L"></div>
					<?php } ?>
				</div>
