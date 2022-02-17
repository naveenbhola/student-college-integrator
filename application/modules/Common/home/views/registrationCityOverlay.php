<style>
.quesAnsBullets{
background-image: none;
background: none;
};
</style>
<?php 
global $countries;
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $cityTier1 = $categoryClient->getCitiesInTier($appId,1,2);
        $cityTier2 = $categoryClient->getCitiesInTier($appId,2,2);
        $cityTier3 = $categoryClient->getCitiesInTier($appId,0,2);
?>
<div id="userPreferenceCategoryCity" name="userPreferenceCategoryCity" style="width:375px;border:1px solid #c4c4c6;background:#ffffff;display:none;position:absolute">
	<div style="background:#FFF" id="overlayLocationHolder" name="overlayLocationHolder">
		<div>
			<?php
			foreach($countries as $name=>$value)
			{
				if(($value['id'] == '2'))
				{ ?> 
					<div id="subCountry_<?php echo $value['id']; ?>">
						<div id="countryCities_<?php echo $value['id']; ?>">
							<div id="overlayCityHolder" name="overlayCityHolder">																				
								<div style="padding:10px">
									<!--Start_MetroCities-->
									<div style="float:left;width:150px">
										<div class="mar_full_10p bld" style="padding-bottom:10px"> Metro Cities </div>										
										<div style="width:100%;list-style:none;line-height:20px;padding-left:10px">
										<?php 
											foreach($cityTier1 as $city)
											{
												if($city['cityId'] != $selectedCity)
												{
													echo "<div class='' id=\"subCity_".$city['cityId']."\" style=\"width:110px\"><input id=\"subCityInput_".$city['cityId']."\" type='checkbox' name='cties[]' value='".$city['cityId']."' tag='".$city['cityName']."''/><span style='font-size:12px;color:#0066dd;'>".$city['cityName']."</span></div>";
												}
												else
												{
													echo "<div class='aselected' id=\"subCity_".$city['cityId']."\" style=\"width:110px\"><a href=\"#\" onClick='selectCity(\"".$city['cityName']."\",\"".$city['cityId']."\",\"".$i."\");return false;'><span style='font-size:12px;color:#0066dd;'>".$city['cityName']."</span></a></div>";
												}
											}
										?>
										</div>
									</div>
									<!--End_MetroCities-->
									<!--Start_Non_MetroCities-->
									<div style="float:left; width:250px">
										<div class="mar_full_10p bld" style="padding-bottom:10px"> Non-Metro Cities </div>
										<div style="height:190px;overflow:auto">												
											<div id="cityli" style="list-style:none;line-height:20px;padding-left:10px">
												<?php 
												foreach($cityTier2 as $city)
												{
													if($city['cityId'] != $selectedCity)
													{
														echo "<div class='' id=\"subCity_".$city['cityId']."\" style=\"\"><input id=\"subCityInput_".$city['cityId']."\" type='checkbox' name='cties[]' value='".$city['cityId']."' tag='".$city['cityName']."'/><span style='font-size:12px;color:#0066dd;'>".$city['cityName']."</span></div>";
													}
													else
													{
														echo "<div class='aselected' id=\"subCity_".$city['cityId']."\" style=\"width:100px\"><a href=\"#\" onClick='selectCity(\"".$city['cityName']."\",\"".$city['cityId']."\",\"".$i."\");return false;'><span style='font-size:12px;color:#0066dd;'>".$city['cityName']."</span></a></div>";
													}
												}
												?>
											</div>
                                            <div id="cityli1" style="list-style:none;line-height:20px;padding-left:10px;diplay:none"></div>
										</div>
									</div>
									<!--Start_Non_MetroCities-->
									<div style="clear:left;font-size:1px;height:1px;overflow:hidden">&nbsp;</div>
								</div>
							</div>
							<div id="changeCityList" class="txt_align_r" style="margin-right:55px;display:none"><span class="plusSign1" style="">&nbsp;</span><a id="moreCitiesLink" href="javascript:void(0);" style="position:relative;top:1px;font-size:11px" onClick="createMoreCity(this);return false;">More cities</a></div>
						</div>
					</div> 		
			<?php } } ?>
		</div>
        <div align="center" style="padding-bottom:15px"><input type="button" class="okBtn" onClick="getDataFromCityLayer();" value="OK" /></div>
	</div>
</div>
