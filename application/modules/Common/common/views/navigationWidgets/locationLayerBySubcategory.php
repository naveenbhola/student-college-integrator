<?php

$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
	for($j = 1; $j<=count($cityList); $j++){
		foreach($cityList[$j] as $key=>$city){
			if(!in_array($cityList[$j][$key]->getId(),$dynamicLocationList['cities'])){
				unset($cityList[$j][$key]);
			}
		}
	}
	$otherCityList = $cityList[3];
	
	usort($otherCityList,sortCitiesByName);
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

	
	function sortCitiesByName($a,$b){
		return $a->getName()>$b->getName();
	}
	
?>
<div class="nav-loc-layer">
	<h4>Select your prefered location</h4>
	<div class="city-cols" style="width:150px">
		<h5>Cities in India</h5>
		<ul>
			<?php
				$urlRequest = clone $requestWidget;
				foreach($cityList[1] as $city) {
					$urlRequest->setData(array('cityId'=>$city->getId(),'stateId'=>$city->getStateId(),'localityId'=>0,'zoneId'=>0));
			?>
				<li><a href="<?=$urlRequest->getURL()?>" title = "<?php echo $city->getName(); ?>"><?php echo $city->getName(); ?></a></li>
			<?php
			}
			$urlRequest->setData(array('cityId'=>1,'stateId'=>1,'localityId'=>0,'zoneId'=>0));
			?>
		<li><a href="<?=$urlRequest->getURL()?>" title = "All Cities" onclick="return setAllCitiesCookie();">All Cities</a></li>
		</ul>
		<?php if(count($cityList[2])){ ?>
		<div class="city-sep"></div>
		<?php } ?> 
		<ul class="scroll-city" style="<?php echo $tier2Height?("height:".($tier2Height)."px;"):"";?>" id="tier-2-cities-nav">
			<?php
				foreach($cityList[2] as $city) {
					$urlRequest->setData(array('cityId'=>$city->getId(),'stateId'=>$city->getStateId(),'localityId'=>0,'zoneId'=>0));
			?>
				<li><a href="<?=$urlRequest->getURL()?>" title = "<?php echo $city->getName(); ?>"><?php echo $city->getName(); ?></a></li>
			<?php
			}
			?>
		</ul>
		<?php if($displayTier3 == "" && count($otherCityList)){ ?>
		<div class="city-sep"></div>
		<?php } ?>
		<ul class="scroll-city" style="height:<?=$tier3Height?>px;display:<?=$displayTier3?>;" id="all-cities-nav">
			<?php
				foreach($otherCityList as $city) {
					$urlRequest->setData(array('cityId'=>$city->getId(),'stateId'=>$city->getStateId(),'localityId'=>0,'zoneId'=>0));
			?>
				 <li><a href="<?=$urlRequest->getURL()?>" title = "<?php echo $city->getName(); ?>"><?php echo $city->getName(); ?></a></li>
			<?php
			}
			?>

		</ul>
		
		<div style="display:<?=$showMoreLabel?>;padding:10px">
			<a id="more-cities-nav" href="#" onclick="showmorecitiesnav(); return false;">[+] More</a>
			<a id="less-cities-nav" href="#" style="display:none" onclick="showlesscitiesnav(); return false;">[-] Less</a>
		</div>
		
	</div>
	<div class="city-cols">
		<h5>States in India</h5>
		<ul class="scroll-city" style="height:<?=$totalHeight-36?>px;">
			<?php           
					global $EXCLUDED_STATES_IN_LOCATION_LAYER;	
					foreach($states as $state){
					if(in_array($state->getId(),$EXCLUDED_STATES_IN_LOCATION_LAYER)){//Hiding Delhi State
						continue;
					}
					$urlRequest->setData(array('cityId'=>1,'stateId'=>$state->getId(),'localityId'=>0,'zoneId'=>0));
			?>
			<li><a href="<?=$urlRequest->getURL()?>"><?php echo $state->getName(); ?></a></li>
			<?php } ?>
		</ul>
		
	</div>
	<div class="last-city-col">
		<h5>Abroad</h5>
		<ul>
		<?php
			$urlRequest = new $requestWidget;
			$urlRequest->setData(array('categoryId'=>$requestWidget->getCategoryId(),'subCategoryId'=>$categoryRepository->getCrossPromotionMappedCategory($requestWidget->getSubCategoryId())->getId()));
			$urlRequest->setData(array('countryId'=>1,'regionId'=>1));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">South East Asia</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>1,'regionId'=>2));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">Europe</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>1,'regionId'=>3));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">Middle East</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>1,'regionId'=>4));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">UK-Ireland</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>1,'regionId'=>5));
		?>
				 <li><a href="<?=$urlRequest->getURL()?>">New Zealand & Fiji</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>1,'regionId'=>6));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">Far East</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>1,'regionId'=>8));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">Africa</a></li>		
		<?php
			$urlRequest->setData(array('countryId'=>3,'regionId'=>0));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">USA</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>5,'regionId'=>0));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">Australia</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>8,'regionId'=>0));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">Canada</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>36,'regionId'=>0));
		?>
				<li><a href="<?=$urlRequest->getURL()?>">China</a></li>

		</ul>
		
	</div>
</div>

<script>
	function setAllCitiesCookie(){
		setCookie("userCityPreference","1:1:2");
		return true;
	}
	function showmorecitiesnav(){
		$('more-cities-nav').style.display = 'none';
		$('less-cities-nav').style.display = '';
		$('tier-2-cities-nav').style.display = 'none';
		$('all-cities-nav').style.display = '';
	}
	function showlesscitiesnav(){
		$('more-cities-nav').style.display = '';
		$('less-cities-nav').style.display = 'none';
		$('tier-2-cities-nav').style.display = '';
		$('all-cities-nav').style.display = 'none';
	}
</script>
