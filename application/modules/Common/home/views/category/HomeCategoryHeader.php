<?php
	$displayName = isset($categoryData['displayName']) ? $categoryData['displayName'] : '';
	$rightImage = isset($categoryData['rightImage']) ? $categoryData['rightImage'] : '/public/images/aeroplane.jpg/public/images/aeroplane.jpg';
	$leftImage = isset($categoryData['leftImage']) ? $categoryData['leftImage'] : '/public/images/aeroplane.jpg/public/images/aeroplane.jpg';
	global $countryNameSelected;
	$countryNameSelected = '';
?>
			
			<div class="inline-l row">
				<div class="outerBorderWithBg">


					<div style="background:url(<?php echo $leftImage; ?>); background-repeat:no-repeat; background-position:left bottom;padding-left:152px;height:72px">
						<div style="line-height:5px">&nbsp;</div>
						<div id="categoryHeaderTitle">
							<h1><span style="font-size:18px;font-weight:normal"><?php echo $displayName; ?>&nbsp;&nbsp;</span></h1>
						</div>
						<div style="line-height:5px">&nbsp;</div>
						<div class="bld">Refine by: &nbsp; 
							<select style="width:125px" id="country" name="countrySelect" onchange="updateCategoryPageForCountry();">
								<?php
									foreach($country_list as $country) {
										$countryId = $country['countryID'];
										$countryName = $country['countryName'];
										if( $countryName == 'All') {
											 $countryName = 'All Countries';
											 $countryId = $allCountriesId;
										}
										if($categoryData['page'] == 'FOREIGN_PAGE' && $countryId==2) {
											continue;
										}
										$selected = $countryId == $countrySelected ? 'selected' :'';
										if($countryId == $countrySelected) {
											$countryNameSelected = $countryName;
										}
								?>
								<option <?php echo $selected; ?>  value="<?php echo  $countryId; ?>"><?php echo $countryName; ?></option>
								<?php
									}
								?>
							</select>
							&nbsp;
							<select name="cities" id="cities" onchange="checkCity(this, 'updateCategoryPageForCity');" style="display:none;">
							</select>
							<input type="hidden" id="cities_other"/>

						</div>
						<div style="line-height:5px">&nbsp;</div>


					</div>
				</div>
				<?php
						if(stristr($displayName, 'Foreign') && is_array($country_list)) {
				?>
				<div class="lineSpace_5">&nbsp;</div>
				<div style="border:1px solid #E9EDF0; height:32px">
					<img src="/public/images/flag1.gif" alt="Australia" style="margin-right:20px;margin-left:5px; cursor:pointer" onClick="updateCountryForHomePage(5);"/>
					<img src="/public/images/flag2.gif" alt="Canada" style="margin-right:20px; cursor:pointer" onClick="updateCountryForHomePage(8);" />
					<img src="/public/images/flag3.gif" alt="New Zealand" style="margin-right:20px; cursor:pointer" onClick="updateCountryForHomePage(7);" />
					<img src="/public/images/flag4.gif" alt="Singapore" style="margin-right:20px; cursor:pointer" onClick="updateCountryForHomePage(6);" />
					<img src="/public/images/flag5.gif" alt="United Kingdom" style="margin-right:20px; cursor:pointer" onClick="updateCountryForHomePage(4);" />
					<img src="/public/images/flag6.gif" alt="United States" style="margin-right:20px; cursor:pointer" onClick="updateCountryForHomePage(3);" />
					<img src="/public/images/flag7.gif" alt="Germany" style="margin-right:20px; cursor:pointer" onClick="updateCountryForHomePage(9);" />
				</div>
				<?php	
					}
				?>
			</div>
			<div class="lineSpace_2">&nbsp;</div>			
<input type="hidden" id="selectedCategoryId" value="<?php echo $categoryId; ?>"/>