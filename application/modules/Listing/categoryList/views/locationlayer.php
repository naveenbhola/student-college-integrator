<?php
	$displayflag = '';
	if($request->getCityId() == 1 && $request->getStateId() == 1){
		if(isset($_COOKIE['userCityPreference'])){
			$location = explode(":",$_COOKIE['userCityPreference']);
			if($location[0] > 1 || $location[1] > 1){
				$displayflag = '';
			}else{
				$displayflag = 'none';
			}
		}else{
			$displayflag = '';
		}
	}else{
		$displayflag = 'none';
	}
	$appliedFilters = $request->getAppliedFilters();
	 if(count($institutes) <= 0 && count($dynamicLocationList['cities'])>0 && (!(isset($appliedFilters) && count($appliedFilters)>0) || ($zeroResultFlag && $zero_result_categorypage_count <=0))){
		$displayflag = '';
		$noInstitutesPage = 1;
	}
	
	if($displayflag == '')
	{
		$mouseover = '';
		$mouseout = '';
		$js = 1;
	}
	else
	{
		$mouseover = "this.style.display=''; overlayHackLayerForIE('locationlayer', document.getElementById('locationlayer'));" ;
		$mouseout = "dissolveOverlayHackForIE();this.style.display='none'";
	}
	
	if(in_array($request->getCategoryId(),array(11,14))){
		$showAbroad = 'none';
		$width = "336";
		$statesWidth = "130px";
		$breakTitle = "<br/>";
		$left = "11px";
	}else{
		$showAbroad = '';
		$width = "500";
		$statesWidth = "160px";
		$headerwidth = "470px";
		$breakTitle = "";
		$left = "27px";
	}
	

	global $cityList;
	global $otherCityList;
	global $allCities;
	global $states;
	$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
	for($j = 1; $j<=count($cityList); $j++){
		foreach($cityList[$j] as $key=>$city){
			if(!in_array($cityList[$j][$key]->getId(),$dynamicLocationList['cities'])){
				unset($cityList[$j][$key]);
			}
		}
	}
	$otherCityList = $cityList[3];
	
	usort($otherCityList,sortEntitiesByName);
	$states = $locationRepository->getStatesByCountry(2);
	foreach($states as $key=>$state){
		if(!in_array($state->getId(),$dynamicLocationList['states'])){
			unset($states[$key]);
		}
	}
	$tier1Height = (count($cityList[1])*20);
	$tier2Height = (count($cityList[2])*20);
	$tier3Height = (count($cityList[3])*20);
	
	if((($tier1Height + $tier2Height + $tier3Height) > 340) && $tier2Height!=0){ // Many Cities
	   $tier2Height = min((340-$tier1Height),$tier2Height);
	   $tier3Height = $tier2Height;
	   $showMoreLabel = "";
	   $displayTier3 = "none";
	   $totalHeight = 70 + $tier1Height + $tier2Height;
	   foreach($cityList[2] as $city) {
			$otherCityList[] = $city;
		}
	}else{ //Not Many Cities
		$tier3Height = min((340-$tier1Height-$tier2Height),$tier3Height);
		$showMoreLabel = "none";
		$displayTier3 = "";
		$totalHeight = 70 + $tier1Height + $tier2Height + $tier3Height;
	}

	
	function sortEntitiesByName($a,$b){
		return $a->getName()>$b->getName();
	}
?>
<div id = 'locationlayer' class="location-layer-cont" style = 'display:<?php echo $displayflag;?>' onmouseover="<?php echo $mouseover;?>" onmouseout="<?php echo $mouseout;?>">
	<!--Start_OverlayTitle-->
    <div class="layer-title" id="location-layer-title">Institutes available in these locations. <?=$breakTitle?>Choose a location to proceed</div>
    <!--End_OverlayTitle-->
    <!--Start_OverlayContent-->
    <div id = "maindivid">
                 <div class="location-layer-child">
                    <div class="list-cols" style="width:150px;border-right:1px solid #E2E2E2;padding-right:5px;">
                            <strong>Cities in India</strong>
                            <div class="list-cols-child" style="line-height:15px;padding-top:5px;">
								<?php
									$urlRequest = clone $request;
									foreach($cityList[1] as $city) {
										$urlRequest->setData(array('cityId'=>$city->getId(),'stateId'=>$city->getStateId(),'localityId'=>0,'zoneId'=>0));
								?>
									<div style="padding-bottom:5px;"><a onclick="goToCategoryPage(event, '<?=$urlRequest->getURL()?>', '<?php echo $city->getId();?>', 'city', '<?php echo $city->getStateId();?>');" href="<?=$urlRequest->getURL()?>" title = "<?php echo $city->getName(); ?>"><?php echo $city->getName(); ?></a></div>
								<?php
								}
								$urlRequest->setData(array("countryId"=>2 ,'cityId'=>1,'stateId'=>1,'localityId'=>0,'zoneId'=>0));
								?>
							<div style="padding-bottom:5px;"><a onclick="goToCategoryPage(event, '<?=$urlRequest->getURL()?>', '1', 'city', '<?php echo $city->getStateId();?>');" href="<?=$urlRequest->getURL()?>" title = "All Cities">All Cities</a></div>
                            </div>
					<?php if(count($cityList[2])){ ?>
							<div class="grayLine_1" style="margin:10px 40px 7px 0">&nbsp;</div>
					<?php } ?>
							<div style="<?php echo $tier2Height?("height:".($tier2Height)."px;"):"";?>overflow:auto;padding-top:5px;" id="tier-2-cities">
								<div class="list-cols-child" style="overflow:visible;line-height:15px">
								<?php
									foreach($cityList[2] as $city) {
										$urlRequest->setData(array('cityId'=>$city->getId(),'stateId'=>$city->getStateId(),'localityId'=>0,'zoneId'=>0));
								?>
									<div style="padding-bottom:5px;"><a onclick="goToCategoryPage(event, '<?=$urlRequest->getURL()?>', '<?php echo $city->getId();?>', 'city', '<?php echo $city->getStateId();?>');" href="<?=$urlRequest->getURL()?>" title = "<?php echo $city->getName(); ?>"><?php echo $city->getName(); ?></a></div>
								<?php
								}
								?>

								</div>
							</div>
					<?php if($displayTier3 == "" && count($otherCityList)){ ?>
							<div class="grayLine_1" style="margin:10px 40px 7px 0">&nbsp;</div>
					<?php } ?>
							<div style="height:<?=$tier3Height?>px;display:<?=$displayTier3?>;overflow:auto;padding-top:5px;" id="all-cities">
								<div class="list-cols-child" style="overflow:visible;line-height:15px">
								<?php
									foreach($otherCityList as $city) {
										$urlRequest->setData(array('cityId'=>$city->getId(),'stateId'=>$city->getStateId(),'localityId'=>0,'zoneId'=>0));
								?>
									 <div style="padding-bottom:5px;"><a onclick="goToCategoryPage(event, '<?=$urlRequest->getURL()?>', '<?php echo $city->getId();?>', 'city', '<?php echo $city->getStateId();?>');" href="<?=$urlRequest->getURL()?>" title = "<?php echo $city->getName(); ?>"><?php echo $city->getName(); ?></a></div>
								<?php
								}
								?>

								</div>
							</div>
							<div style="padding:3px 10px;">
							<div style="display:<?=$showMoreLabel?>;">
								<a id="more-cities" href="#" onclick="showmorecities(); return false;">[+] More</a>
								<a id="less-cities" href="#" style="display:none" onclick="showlesscities(); return false;">[-] Less</a>
							</div>
							</div>

                    </div>
                    <div class="list-cols" style="width:<?=$statesWidth?>">
                        <strong>States in India</strong>
                            <div style="padding-left:10px;padding-top:5px;height:<?=$totalHeight-36?>px;padding-bottom:10px;overflow:auto;">
                                <div style="overflow:visible;padding-right:5px;">
			<?php           
					global $EXCLUDED_STATES_IN_LOCATION_LAYER;	
					foreach($states as $state){
					if(in_array($state->getId(),$EXCLUDED_STATES_IN_LOCATION_LAYER)){//Hiding Delhi State
						continue;
					}
					$urlRequest->setData(array('cityId'=>1,'stateId'=>$state->getId(),'localityId'=>0,'zoneId'=>0));
			?>
                                    <div style="padding-bottom:5px;"><a onclick="goToCategoryPage(event, '<?=$urlRequest->getURL()?>', '<?php echo $state->getId();?>', 'state');" href="<?=$urlRequest->getURL()?>"><?php echo $state->getName(); ?></a></div>
                                    <?php } ?>
                                </div>
                            </div>

                    </div>
					<div  class="list-cols" style="height:<?=$totalHeight+10?>px;width:1px;border-left:1px solid #E2E2E2;display:<?=$showAbroad?>">&nbsp;</div>
                    <div class="list-cols" style="width:100px;display:<?=$showAbroad?>">
					
                            <strong>Abroad</strong>
                                <div style="padding-left:10px;padding-top:5px;padding-bottom:10px;overflow:auto;">
                                <div style="overflow:visible;">
                                
                                <?php $abroadCountryList = $locationRepository->getAbroadCountries();
                                  $abroadCountryList = array_slice($abroadCountryList,0, 21)?>
                                <?php foreach($abroadCountryList as $abroadCountry)
                                {
                                 if(!empty($abroadCountry))
                                 {
                                  echo "<div style='padding-bottom:5px;'><a href='".$abroadCategoryPageRequest->getURLForCountryPage($abroadCountry->getId())."'>".$abroadCountry->getName()."</a></div>";
                                 }
                                }
                                ?>
                                <?php /**
									$urlRequest = new $request;
									$urlRequest->setData(array('categoryId'=>$request->getCategoryId(),'subCategoryId'=>$categoryRepository->getCrossPromotionMappedCategory($request->getSubCategoryId())->getId()));
									$urlRequest->setData(array('countryId'=>1,'regionId'=>1));
									**/
								?>
							  </div>
							</div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
                 </div>
            </div>
    <!--End_OverlayContent-->
</div>
<style>#locationlayer{width:<?=$width?>px;position:absolute;z-index:9999999 !important}</style>
<script>
	function setAllCitiesCookie(){
		setCookie("userCityPreference","1:1:2");
		return true;
	}
	function showmorecities(){
		$('more-cities').style.display = 'none';
		$('less-cities').style.display = '';
		$('tier-2-cities').style.display = 'none';
		$('all-cities').style.display = '';
	}
	function showlesscities(){
		$('more-cities').style.display = '';
		$('less-cities').style.display = 'none';
		$('tier-2-cities').style.display = '';
		$('all-cities').style.display = 'none';
	}
	setCookie("userCityPreference","<?=$request->getCityId()?>:<?=$request->getStateId()?>:2");
	<?php if($js == 1){
	?>
	$('dim_bg').style.display = 'inline';
	var isIE6 = (navigator.userAgent.toLowerCase().substr(25,6)=="msie 6") ? true : false;
	if(isIE6){
		$('dim_bg').style.height = '2000px';
	}else{
		$('dim_bg').style.height = '10000px';
	}
	$('dim_bg').style.zIndex = 1100;
	$('changeLocationdiv').style.visibility='hidden';
	var divX = parseInt(screen.width/2)-<?=$width/2?>;
	var divY = parseInt(screen.height/2)-200;
	$('locationlayer').style.left = (divX) +  'px';
	$('locationlayer').style.top = (divY) + 'px';
	<?php
	}
	?>
</script>
<?php
	if($noInstitutesPage){
		global $pageHeading;
		global $locationname;
?>
	<script>
		$('locationlayer').style.display='';
		overlayHackLayerForIE('locationlayer', document.getElementById('locationlayer'));
		var htmlText = '<table width="100%"><tr><td width="10%"><img src="/public/images/alert-image-withbg.jpg"></td>';
		htmlText += '<td width="80%"><div style="color:#FFF807">No institutes found for <?=$pageHeading?> in <?=$locationname?>. </div>';
		htmlText += '<div>Please select some other location below.</div></td>';
		htmlText += '<td width="10%" valign="top"><div class="cssSprite1 allShikCloseBtn" style="left:<?=$left?>;top:2px;right: 0px;" onclick="closeLocationLayer(); return false;"></div></td>';
		htmlText += '</tr></table>';
		$('location-layer-title').innerHTML = htmlText;
		function closeLocationLayer(){
			$('changeLocationdiv').style.visibility='visible';
			$('dim_bg').style.display = 'none';
			$('locationlayer').style.display = 'none';
			$('locationlayer').onmouseover = function(){this.style.display=''; overlayHackLayerForIE('locationlayer', document.getElementById('locationlayer'));}
			$('locationlayer').onmouseout = function(){dissolveOverlayHackForIE();this.style.display='none';}
		}
	</script>
	<style>
		#ad-displayBox{
			display:none !important;
		}
	</style>
<?php
	}
?>

<script>
	function goToCategoryPage(e, url, locationId, locationType, stateId) {
		var subCategoryId = '<?php echo $request->getSubCategoryId();?>';
		if(locationId == 1 && locationType == "city"){
			setAllCitiesCookie();
		}
		if(subCategoryId != 23){
			window.location.href = url;
			return;
		}
		if(!e){
			e = window.event;
		}
		if (e.cancelBubble) {
			e.cancelBubble = true;
		} else {
			if(e.stopPropagation) {
				e.stopPropagation();
			}
		}
		if(e.preventDefault){
			e.preventDefault();    
		}
		var filterCookie = "";
		switch(locationType){
			case 'city':
				if(locationId == 1){
					filterCookie = "filters-CATPAGE-3-23-1-1-1-2-0-none-none-none";
				} else {
					filterCookie = "filters-CATPAGE-3-23-1-0-" + locationId + "-" + stateId + "-2-0-none-none-none";
				}
				break;
			case 'state':
				filterCookie = "filters-CATPAGE-3-23-1-0-1" + "-" + locationId + "-2-0-none-none-none";
				break;
		}
		var examsString = getCookie('gnb_ex');
		var exams = [];
		if(examsString != "none" && examsString != "" && examsString.length > 0){
			var tempExams = examsString.split(":");
			for(var i=0; i < tempExams.length; i++){
				var ex = ShikshaHelper.trim(tempExams[i]);
				if(ex != ""){
					exams.push(ex);
				}
			}
		}
		if(exams.length > 0 && filterCookie != ""){
			setGNBExamsInFilterCookie(exams, filterCookie);
			setCookie('gnb_ex', 'none');
			if(typeof url != 'undefined'){
				window.location.href = url;
			}
		} else {
			if(typeof url != 'undefined'){
				window.location.href = url;
			}
		}
	}
</script>