<?php

	$mouseover = "this.style.display=''; overlayHackLayerForIE('locationlayerSA', document.getElementById('locationlayerSA'));" ;
	$mouseout = "dissolveOverlayHackForIE();this.style.display='none'";
	

	$locationBuilder = new LocationBuilder;
	$locationRepository = $locationBuilder->getLocationRepository();
	$countriesArray1 = $locationRepository->getCountriesByRegion($request->getRegionId());
	$countriesArray = array();
	foreach($countriesArray1 as $country){
		if(in_array($country->getId(),$dynamicLocationList['countries'])){
			$countriesArray[] = $country;
		}
	}
	if(!count($countriesArray) || count($countriesArray) <= 10){
		$width1 = "330px";
		$width2 = "100px";
	}else{
		$width1 = "380px";
		$width2 = "200px";
	}
	
?>
<div id = 'locationlayerSA' class="location-layer-cont" style = 'z-index:1000000;display:none;width:<?=$width1?>' onmouseover="<?php echo $mouseover;?>" onmouseout="<?php echo $mouseout;?>">
    	<!--Start_OverlayTitle-->
        <h3 class="layer-title">Please Choose Your Preferred Location</h3>
        <!--End_OverlayTitle-->
        <!--Start_OverlayContent-->
            <div id = "maindivid" class="location-layer-child">
                    <?php
					if(count($countriesArray)){
					?>
							<div class="list-cols">
								<strong><?=$categoryPage->getRegion()->getName()?></strong>
								<div class="list-cols-child">
											<ul style="width:<?=$width2?>">
												<li>
											<?php
											$urlRequest = clone $request;
											$i = 0;
											foreach($countriesArray as $country){
												$i++;
												$urlRequest->setData(array('countryId'=>$country->getId()));
												if($country->getId() != $request->getCountryId()){
											?>
													<a href="<?=$urlRequest->getURL()?>"><?=$country->getName()?></a><br/>
												<?php
													}else{
												?>
													<?=$country->getName()?><br/>
												<?php
											}
											
											if($i == ceil(count($countriesArray)/2) && count($countriesArray) > 10){
												?>
												</li><li>
											<?php
											}
											}
											?>
												</li>
											</ul>
								 </div>
							</div>					                    						
                    
						<?php 	$className = 'class="list-cols"';
						
							}else{     
						                
								$className = "";						
							
						 } ?>
						
						<div <?=$className?>>
						
						<strong><?php if(count($countriesArray)){ echo "Other Regions";}?></strong>
                        <div class="list-cols-child">
								<?php
									$urlRequest = clone $request;
									$urlRequest->setData(array('countryId'=>1,'regionId'=>1));
								
									if($request->getRegionId() != 1 || $request->getCountryId() != 1){
									
								?>
										<a href="<?=$urlRequest->getURL()?>">South East Asia</a><br/>
								<?php
									}
									$urlRequest->setData(array('countryId'=>1,'regionId'=>2));
									if($request->getRegionId() != 2 || $request->getCountryId() != 1){
								?>
										<a href="<?=$urlRequest->getURL()?>">Europe</a><br/>
								<?php
									}
									$urlRequest->setData(array('countryId'=>1,'regionId'=>3));
									
									if($request->getRegionId() != 3 || $request->getCountryId() != 1){
								?>
										<a href="<?=$urlRequest->getURL()?>">Middle East</a><br/>
								<?php
									}else{
								?>
										Middle East<br/>
								<?php
									}
									
									$urlRequest->setData(array('countryId'=>1,'regionId'=>4));
									if($request->getRegionId() != 4 || $request->getCountryId() != 1){
								?>
										<a href="<?=$urlRequest->getURL()?>">UK-Ireland</a><br/>
								<?php
									}
									
									$urlRequest->setData(array('countryId'=>1,'regionId'=>5));
									if($request->getRegionId() != 5 || $request->getCountryId() != 1){
								?>
										<a href="<?=$urlRequest->getURL()?>">New Zealand & Fiji</a><br/>
								<?php
									}
									
									$urlRequest->setData(array('countryId'=>1,'regionId'=>6));
									if($request->getRegionId() != 6 || $request->getCountryId() != 1){
								?>
										<a href="<?=$urlRequest->getURL()?>">Far East</a><br/>
								<?php
									}
									
									$urlRequest->setData(array('countryId'=>1,'regionId'=>8));
									if($request->getRegionId() != 8 || $request->getCountryId() != 1){
								?>
										<a href="<?=$urlRequest->getURL()?>">Africa</a><br/>
								<?php
									}
									
									$urlRequest->setData(array('countryId'=>3,'regionId'=>0));
									if($request->getCountryId() != 3){
								?>
										<a href="<?=$urlRequest->getURL()?>">USA</a><br/>
								<?php
									}
									$urlRequest->setData(array('countryId'=>5,'regionId'=>0));
									if($request->getCountryId() != 5){
								?>
										<a href="<?=$urlRequest->getURL()?>">Australia</a><br/>
								<?php
									}
									$urlRequest->setData(array('countryId'=>8,'regionId'=>0));
									if($request->getCountryId() != 8){
								?>
										<a href="<?=$urlRequest->getURL()?>">Canada</a><br/>
								<?php
									}
									$urlRequest->setData(array('countryId'=>36,'regionId'=>0));
									if($request->getCountryId() != 36){
								?>
										<a href="<?=$urlRequest->getURL()?>">China</a><br/>
								<?php
									}
								?>
								<div style="width:90%; margin:5px 0" class="grayLine_1">&nbsp;</div>
								<?php
									$urlRequest->setData(array('categoryId'=>$request->getCategoryId(),'subCategoryId'=>$categoryRepository->getCrossPromotionMappedCategory($request->getSubCategoryId())->getId()));
									$urlRequest->setData(array('countryId'=>2,'regionId'=>0));
								?>
										<a href="<?=$urlRequest->getURL()?>">India</a><div class = "spacer10 clearFix"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear_L">&nbsp;</div>

            </div>
        <!--End_OverlayContent-->
   
<script type="text/javascript">
    var h = document.body.scrollTop;
	var  h1 = document.documentElement.scrollTop;
    h = h1 > h ? h1 : h;
	var divY =  (parseInt((document.body.offsetHeight)/2)) + 90;
	var divX = (parseInt((document.body.offsetWidth)/2)) - 150;
    var posleft = (divX) +  'px';
    var postop = (divY) + 'px';
   // document.write("<style>#locationlayerSA{left:"+posleft+" !important;top:"+postop+"!important}</style>");
</script>
<!--</div>-->
<!--End_Overlay-->
