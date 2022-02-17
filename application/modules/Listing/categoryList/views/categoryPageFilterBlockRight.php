<?php
global $filters;
global $appliedFilters;
$localityFilterValues = $filters['locality']->getFilteredValues();
$cityFilterValues = $filters['city']->getFilteredValues();
//$cityFilterValues = $localityFilterValues = array();

$CI_INSTANCE->config->load('categoryPageConfig');
$subcategoriesForRnR = $CI_INSTANCE->config->item('CP_SUB_CATEGORY_NAME_LIST');
?>
<?php 	$resetRequest = clone $request;
	$resetKeyRequest = clone $request;
	if($resetRequest->getLocalityId() >= 0)
	{
		$resetRequest->setData(array('localityId'=>0));
		$resetPageKey = $resetRequest->getPageKey();
	}
	else if($resetRequest->getCityId() > 1)
	{
		$resetRequest->setData(array('cityId'=>1));
		$resetPageKey = $resetRequest->getPageKey();
	}
	else if($resetRequest->getStateId() > 1)
	{
		$resetRequest->setData(array('stateId'=>1));
		$resetPageKey = $resetRequest->getPageKey();
	}
	else
		$resetPageKey = $resetRequest->getPageKey();
?>

<?php

$userMultiLocationsPreference = json_decode(base64_decode($_COOKIE['userMultiLocPref-MainCat-'.$request->getCategoryId()]));


$flag_locality = 0;
$flag_city = 0;
if($filters['locality'] && $request->getCityId() != 1)
{
	if(count($localityFilterValues) > 0) {			
?>
	<ul>
	   <li>
		<strong class="flLt">Regions</strong>
		<?php $flag_locality = 1;?>
		<a class="flRt" id="localityReset" onclick="removeFilterCookiesOnReset('<?=$resetPageKey?>', 'locality');" style="display:none" href="<?=$resetRequest->getURL()?>">Reset</a>
	   </li>
	   <?php foreach($localityFilterValues as $zone=>$filter){?>
		<li>
			<label id="znlbl_<?php echo $zone;?>" style="display:block;">
				<input title="<?=$filter['name']?>" type="checkbox" id="zone<?=$zone?>" value="<?=$zone?>" onclick="applyFullZone('<?=$zone?>');"/>
					<?=$filter['name']?>
			</label>
			<label id="znlbl_span_<?php echo $zone;?>" style="display:none;">
				<span class="checkbox-disabled"></span> <?=$filter['name']?>
			</label>
		</li>
		<li>
			<ul>
						
			<?php foreach($filter['localities'] as $locality=>$filter1){ 
						$checked = '';
						$disabled = '';
						if(!$appliedFilters['locality']){
							$disabled = '';
						}
						else if(in_array($locality,$appliedFilters['locality'])){
							$checked = "checked";
							$disabled = '';
						}
						else{
							$checked = '';
							$disabled = 'disabled';
						}
						
						$urlRequest = clone $request;
						$urlLocalityId = $urlRequest->getLocalityId();
						if(!$urlLocalityId || $urlLocalityId == 0)
							$disabled = '';
						//$urlRequest->setData(array('localityId'=>$locality));
			?>
				<li>
					<label>
						<?php if($subcategoriesForRnR[$request->getSubCategoryId()]){
							if($disabled=='disabled') { ?>
								<input id="locality_<?=$filter1?>" title="<?=$filter1?>" type="checkbox" style="display:none" <?=$checked?> <?=$disabled?> value = "<?=$locality?>" class="zonelocality<?=$zone?>" name="locality[]" onclick="applyLocality('<?=$zone?>', '<?=$locality?>');" />
								<span class="checkbox-disabled"></span>	
								<a onclick="return false;" style="text-decoration:none;color:gray;cursor: default;"
								   href="#">
								   <?=$filter1?>
								</a>
							<?php }
							else { ?>
								<input id="locality_<?=$filter1?>" title="<?=$filter1?>" type="checkbox" <?=$checked?> <?=$disabled?> value = "<?=$locality?>" class="zonelocality<?=$zone?>" name="locality[]" onclick="applyLocality('<?=$zone?>', '<?=$locality?>');" />
									<a onclick="selectFilterCheckBoxes('locality_<?=$filter1?>'); applyLocality('<?=$zone?>', '<?=$locality?>'); return false;" style="text-decoration:none;color:black;cursor: default;"									
									   href="#">
									   <?=$filter1?>
									</a>
							<?php } ?>
						<?php } else { ?>
							<input title="<?=$filter1?>" type="checkbox" <?=$checked?> value = "<?=$locality?>" class="zonelocality<?=$zone?>" name="locality[]" onclick="applyLocality('<?=$zone?>', '<?=$locality?>');" /> <?=$filter1?>
						<?php } ?>
						<script>applyLocality('<?=$zone?>');</script>
					</label>
				</li>
			<?php } ?>
			</ul>
		</li>
		<?php if(in_array($zone,$appliedFilters['zone'])&&count($appliedFilters['locality'])<1){ ?>
		<script>
			$('zone<?=$zone?>').checked = true;
			applyFullZone('<?=$zone?>');
		</script>
		<?php } ?>
		
		<script>
			disableOrCheckZone('<?=$zone?>');
		</script>
	 <?php } ?>
	</ul>
<?php
	}
}
if($filters['city'] && !($request->getCityId()==1 && $request->getStateId()==1)){
	$cityFilterValues = $filters['city']->getFilteredValues();
	if(count($cityFilterValues) > 1){
?>
<ul>
	   <li>
		<strong class="flLt">Cities</strong>
		<?php if($flag_locality != 1) {
			$flag_city = 1; ?>
			<a class="flRt" id="cityReset" onclick="removeFilterCookiesOnReset('<?=$resetPageKey?>','city');" style="display:none" href="<?=$resetRequest->getURL()?>">Reset</a>
		<?php } ?>
		
	   </li>
	    <?php foreach($cityFilterValues as $key=>$city){
				if($key == $request->getCityId()){
					continue;
				}
				$checked = '';
				if(in_array($key,$appliedFilters['city'])){
					$checked = "checked";
				}
				$urlRequest = clone $request;
				//$urlRequest->setData(array('cityId'=>$key));
			?>
		<li>
			<label>
				<?php if($subcategoriesForRnR[$request->getSubCategoryId()]){ ?>
					<input id="city_<?=$city?>" title="<?=$city?>" type="checkbox" <?=$checked?> name="city[]" value="<?=$key?>"/>
						<a onclick="selectFilterCheckBoxes('city_<?=$city?>'); return false;" style="text-decoration:none;color:black;cursor: default;" href="#"> <?=$city?> </a>
				<?php } else { ?>
					<input title="<?=$city?>" type="checkbox" <?=$checked?> name="city[]" value="<?=$key?>"/> <?=$city?>

				<?php } ?>
			</label>
		</li>
		<?php } ?>
</ul>
<?php	
	}
}/*elseif($appliedFilters['city'] || $appliedFilters['state']){*/
elseif(count($userMultiLocationsPreference)>0){
	//$multiLocationsArr = $request->getUserPreferredLocationOrder();
	$stateArr = array();
	$cityArr = array();
	foreach($userMultiLocationsPreference as $locationPreference){
		$locData = explode("_",$locationPreference);
		if($locData[0]=='s'){
			array_push($stateArr,$locData[1]);
		}elseif($locData[0]=='c'){
			array_push($cityArr,$locData[1]);
		}
	}
	$statesForIndia	= $locationRepository->getStatesByCountry(2);
	$stateSelected = array();
	foreach($statesForIndia as $stateObj){
		//if(in_array($stateObj->getId(),$appliedFilters['state'])){
		if(in_array($stateObj->getId(),$stateArr)){
			$stateSelected[$stateObj->getId()] = $stateObj;
		}
	}
	$citiesSelected = array();
	//_p($cityArr);
	if(count($cityArr)>0){
		//$citiesSelected	= $locationRepository->findMultipleCities(array_unique($appliedFilters['city']));
		$citiesSelected	= $locationRepository->findMultipleCities(array_unique($cityArr));
	}
	
	$stateDisplay= array();
	$virtualCityMapping = array();
	$i = 0;
	foreach($stateSelected as $stateObj){
		// Avoid those states which are not in $dynamicLocationList['states']
		if(!in_array($stateObj->getId(),$dynamicLocationList['states'])){
			continue;
		}
		$citiesOfState = $locationRepository->getCitiesByState($stateObj->getId(),false);
		//Remove cities of state which are not in dynamicLocationList
		foreach($citiesOfState as $key=>$cityOfState){
			if(!in_array($cityOfState->getId(),$dynamicLocationList['cities'])){
				unset($citiesOfState[$key]);
			}
		}
		// Sort Cities of state alphabetically
		usort($citiesOfState,function($first,$second){
			return (strcasecmp($first->getName(),$second->getName()));
		});
		$stateDisplay[$i++] = array_merge(array($stateObj),$citiesOfState);
		// Remove City from citySelected array if both City and State are selected (ex. Uttar Pradesh and Lucknow, both are selected. Lucknow will come under Uttar Pradesh)
		foreach($citiesOfState as $cityObj){
			if(in_array($cityObj->getId(),array_keys($citiesSelected))){
				unset($citiesSelected[$cityObj->getId()]);
			}
		}
	}
	$i=0;
	$cityDisplay[] = array();
	// Remove subcity from citySelected array if both subcity and main-city are selected (ex. Delhi/NCR and Delhi, both are selected. Delhi will come under Delhi/NCR)
	foreach($citiesSelected as $cityObj){
		$citiesArray = $locationRepository->getCitiesByVirtualCity($cityObj->getId());
		$citiesKeys = array();
		// Only if more than 1 cities are mapped to current virtual city remove others
		if(count($citiesArray) > 1){
			foreach($citiesArray as $subCityObj){
				unset($citiesSelected[$subCityObj->getId()]);
			}
		}
	}
	foreach($citiesSelected as $cityObj){
		// Avoid those cities which are not in $dynamicLocationList['cities']
		if(!in_array($cityObj->getId(),$dynamicLocationList['cities'])){
			continue;
		}
		$cityDisplay[$i] = array();
		array_push($cityDisplay[$i],$cityObj);
		$subCityArray = $locationRepository->getCitiesByVirtualCity($cityObj->getId());
		usort($subCityArray,function($first,$second){
			return (strcasecmp($first->getName(),$second->getName()));
		});
		
		$subCity = array();
		foreach($subCityArray as $subCity){
			// Avoid those cities which are not in $dynamicLocationList['cities']
			if(!in_array($subCity->getId(),$dynamicLocationList['cities'])){
				continue;
			}
			$virtualCityMapping[$subCity->getId()] = $cityObj->getId();
			$zonesForCity	= $locationRepository->getZonesByCity($subCity->getId());
			usort($zonesForCity,function($first,$second){
				return (strcasecmp($first->getName(),$second->getName()));
			});
			$startIndexForThisSubcity = count($cityDisplay[$i]);
			if($cityObj->getId() != $subCity->getId()){
				array_push($cityDisplay[$i],$subCity);
			}
			foreach($zonesForCity as $zone){
				if(array_key_exists($zone->getId(),$localityFilterValues)){
					array_push($cityDisplay[$i],$zone);
					foreach($localityFilterValues[$zone->getId()]['localities'] as $key=>$value){
						array_push($cityDisplay[$i],$locationRepository->findLocality($key));
					}
				}
			}
			//special case for Delhi/NCR(ID:10223): Delhi(ID:74) with Zone/Locality will Come first then rest of the sub-cities.
			if($cityObj->getId() == 10223 && $subCity->getId()==74){
				$firstCity = array_slice($cityDisplay[$i],0,1);
				$remainingCity = array_slice($cityDisplay[$i],1,$startIndexForThisSubcity - 1);
				$locationsForCurrentCity = array_slice($cityDisplay[$i],$startIndexForThisSubcity - 1,count($cityDisplay[$i]));
				$cityDisplay[$i] = array();
				$cityDisplay[$i] = $firstCity + $locationsForCurrentCity;
				$cityDisplay[$i] = array_merge($cityDisplay[$i],$remainingCity);
			}
		}
		++$i;
	}
	
	// Sort States alphabetically
	usort($stateDisplay,function($first,$second){
		return (strcasecmp($first[0]->getName(),$second[0]->getName()));
	});
	// Sort Cities alphabetically
	usort($cityDisplay,function($first,$second){
		return (strcasecmp($first[0]->getName(),$second[0]->getName()));
	});
	$locationDisplay = array();
	$locationDisplay = $cityDisplay;
	
	// In main locationDisplay array : states are being pushed behind cities
	foreach($stateDisplay as $stateObj){
		array_push($locationDisplay,$stateObj);
	}
	
?>
<!--<ul>-->
	<?php
		//echo "count :".count($locationDisplay);
		for($i = 0; $i<count($locationDisplay);$i++){
			$locationArr = $locationDisplay[$i];
			$locationType = array("State","City","Zone","Locality");
			$hierarchyLevel = array();
			$stateId = '';
			$cityId = '';
			$zoneId = '';
			$localityId = '';
			foreach($locationArr as $locationObj){
				$checked = '';
				
				//if(in_array(get_class($locationObj),$locationType)){
					//echo "caught";die;
					$locationaName	= $locationObj->getName();
					$locationaValue	= $locationObj->getId();
					
					switch(get_class($locationObj)){
						case "State"	: //echo "caught again State";die;
								$locationType	= 'state';
								$locationDataStruct	= 'state[]';
								$checked = (in_array($locationaValue,$appliedFilters['state']))?'checked':'';
								//if($request->getPageNumberForPagination() > 1){
								//	$checked = (in_array($locationaValue,$appliedFilters['state']))?'checked':'';
								//}else{
								//	$checked = (in_array($locationaValue,$stateArr))?'checked':'';
								//}
								$stateId = $locationObj->getId();
								$cityID =  0;
								$zoneID =  0;
								$localityID =  0;
								$locationClass = $locationType;
								break;
						case "City"	: //echo "caught again City";die;
								$locationType	= 'city';
								$locationDataStruct	= 'city[]';
								$checked = (in_array($locationaValue,$appliedFilters['city']) || in_array($virtualCityMapping[$locationaValue],$appliedFilters['city']) || in_array($locationObj->getStateId(),$appliedFilters['state']))?'checked':'';
								//if($request->getPageNumberForPagination() > 1){
								//	$checked = (in_array($locationaValue,$appliedFilters['city']))?'checked':'';
								//}else{
								//	$checked = (in_array($locationaValue,$cityArr) || in_array($virtualCityMapping[$locationaValue],$cityArr) || in_array($locationObj->getStateId(),$stateArr))?'checked':'';
								//}
								$stateId = $locationObj->getStateId();
								$cityID =  $locationObj->getId();
								$zoneID =  0;
								$localityID =  0;
								$locationClass = $locationType;
								if($locationObj->getTier() == 1){
									$locationClass = 'metrocity';	
								}
								break;
						case "Zone"	: //echo "caught again Zone";die;
								$locationType	= 'zone';
								$locationDataStruct	= 'zone[]';
								$checked = (in_array($locationaValue,$appliedFilters['zone']) || in_array($virtualCityMapping[$locationObj->getCityId()],$appliedFilters['city']))?'checked':'';
								//if($request->getPageNumberForPagination() > 1){
								//	$checked = (in_array($locationaValue,$appliedFilters['zone']))?'checked':'';
								//}else{
								//	$checked = (in_array($locationaValue,$appliedFilters['zone']) || in_array($virtualCityMapping[$locationObj->getCityId()],$cityArr))?'checked':'';
								//}
								$stateId = $locationObj->getStateId();
								$cityID =  $locationObj->getCityId();
								$zoneID =  $locationObj->getId();;
								$localityID =  0;
								$locationClass = $locationType;
								break;
						case "Locality"	: //echo "caught again Locality";die;
								$locationType	= 'locality';
								$locationDataStruct	= 'locality[]';
								//$locationaValue = -1;//for testing
								$checked = (in_array($locationaValue,$appliedFilters['locality']) || in_array($locationObj->getZoneId(),$appliedFilters['zone']) || in_array($virtualCityMapping[$locationObj->getCityId()],$appliedFilters['city']) || in_array($locationObj->getStateId(),$appliedFilters['state']))?'checked':'';
								//if($request->getPageNumberForPagination() > 1){
								//	$checked = (in_array($locationaValue,$appliedFilters['locality']))?'checked':'';
								//}else{
								//	$checked = (in_array($locationaValue,$appliedFilters['locality']) || in_array($locationObj->getZoneId(),$appliedFilters['zone']) || in_array($virtualCityMapping[$locationObj->getCityId()],$cityArr) || in_array($locationObj->getStateId(),$stateArr))?'checked':'';
								//}
								$stateId = $locationObj->getStateId();
								$cityID =  $locationObj->getCityId();
								$zoneID =  $locationObj->getZoneId();
								$localityID =  $locationObj->getId();
								$locationClass = $locationType;
								break;
					}//die;
					
					if(in_array(get_class($locationObj),$hierarchyLevel)){
						if(array_search(get_class($locationObj),$hierarchyLevel) != count($hierarchyLevel)-1){
							$countHierarchy = count($hierarchyLevel);
							$lastLocatiobObj = array_pop($hierarchyLevel);
							while($lastLocatiobObj != get_class($locationObj)){
								if($lastLocatiobObj == "City" && $locationObj->getTier() == 1){
									break;
								}else{
							?>
								</li></ul>
								<!--<li><ul>-->
							<?php
								}
								$lastLocatiobObj = array_pop($hierarchyLevel);
							}
							if($locationObj instanceof City){
								if($locationObj->getTier() == 1){
									array_push($hierarchyLevel,"Metro_City");
								}else{
									array_push($hierarchyLevel,get_class($locationObj));
								}
							}else{
								array_push($hierarchyLevel,get_class($locationObj));
							}
							?>
								<!--</ul>--><!--</li>-->
								<li><!--<ul>-->
							<?php
						}else{?>
							</li><li>
						<?php
						}
					}else{
						if($locationObj instanceof City){
							if($locationObj->getTier() == 1){
								array_push($hierarchyLevel,"Metro_City");
							}else{
								array_push($hierarchyLevel,get_class($locationObj));
							}
						}else{
							array_push($hierarchyLevel,get_class($locationObj));
						}
						if(count($hierarchyLevel) == 1){
						?>
							<ul><li>
						<?php
						}else{
						?>
							<ul><li>
						<?php
						}
					}
					?>
					<!--<li>-->
					<label class=<?php echo $locationClass;?>>
						<input title="<?=$locationaName?>" <?=$checked?> type="checkbox" name="<?=$locationDataStruct?>" onclick="refineMultiLocationFilterSelection(this,<?='\''.$locationType.'\''.','.$stateId.','.$cityID.','.$zoneID.','.$localityID;?>)" value="<?=$locationaValue?>" locTypeId="<?=$locationType."_".$locationaValue?>"/><?=$locationaName?>
					</label>
					<!--</li>-->
					<?php	
				//}
			}
			//$countHierarchy = count($hierarchyLevel);
			foreach($hierarchyLevel as $obj){
				//if($countHierarchy == 1){
				?>
					</li></ul>
				<?php
				//}else{
			?>
				<!--</li></ul>-->
			<?php
				//}
			}
			
		}
	?>
<!--</ul>-->
<?php
}elseif($filters['city']){
	global $cityList;
	$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
	$cityFilterValues = $filters['city']->getFilteredValues();
	if($appliedFilters['city'] && count($appliedFilters['city'])>0){
		$displayMainCity = 'none';
		$displaySubCity = '';
	}else{
		$displayMainCity = '';
		$displaySubCity = 'none';
	}
	if(count($cityFilterValues) > 1){
?>
<ul>
	   <li>
		<strong class="flLt">Cities</strong>
		<?php if($flag_city != 1) { ?>
			<a class="flRt" id="cityTierWiseReset" onclick="removeFilterCookiesOnReset('<?=$resetPageKey?>','city');" style="display:none" href="<?=$resetRequest->getURL()?>">Reset</a>
		<?php } ?>
		
	   </li>
	    <?php foreach($cityList[1] as $city){
			if(array_search($city->getName(),$cityFilterValues)){
				$checked = '';
				if(in_array($city->getId(),$appliedFilters['city'])){
					$checked = "checked";
				}
				$urlRequest = clone $request;
				//$urlRequest->setData(array('cityId'=>$city->getId()));
		?>
		<li>
			<label>
				<?php if($subcategoriesForRnR[$request->getSubCategoryId()]){ ?>
					<input id="city_tier1_<?=$city->getName()?>" title="<?=$city->getName()?>" type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/>
						<a onclick="selectFilterCheckBoxes('city_tier1_<?=$city->getName()?>'); return false;" style="text-decoration:none;color:black;cursor: default;" href="#"><?=$city->getName()?></a>
				<?php } else { ?>
					<input title="<?=$city->getName()?>" type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/> <?=$city->getName()?>
				<?php } ?>
			</label>
		</li>
		<?php }} ?>
		<?php foreach($cityList[2] as $city){
			if(array_search($city->getName(),$cityFilterValues)){
				$checked = '';
				if(in_array($city->getId(),$appliedFilters['city'])){
					$checked = "checked";
				}
				$urlRequest = clone $request;
				//$urlRequest->setData(array('cityId'=>$city->getId()));
		?>
		<li>
			<label>
				<?php if($subcategoriesForRnR[$request->getSubCategoryId()]){ ?>
					<input id="city_tier2_<?=$city->getName()?>" title="<?=$city->getName()?>" type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/>
						<a onclick="selectFilterCheckBoxes('city_tier2_<?=$city->getName()?>'); return false;" style="text-decoration:none;color:black;cursor: default;" href="#"><?=$city->getName()?></a>
				<?php } else { ?>
					<input title="<?=$city->getName()?>" type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/> <?=$city->getName()?>
				<?php } ?>
			</label>
		</li>
		<?php }} ?>
		<li id="mainCityFilters" style="display:<?=$displayMainCity?>"><a href="javascript:void(0);" onclick="$('mainCityFilters').style.display='none';$('subCityFilters').style.display='';">[ + ] More</a></li>
</ul>
<ul id="subCityFilters" style="display:<?=$displaySubCity?>">
	    <?php foreach(($cityList[3]) as $city){
			if(array_search($city->getName(),$cityFilterValues)){
				$checked = '';
				if(in_array($city->getId(),$appliedFilters['city'])){
					$checked = "checked";
				}
				$urlRequest = clone $request;
				//$urlRequest->setData(array('cityId'=>$city->getId()));
		?>
		<li>
			<label>
				<?php if($subcategoriesForRnR[$request->getSubCategoryId()]){ ?>
					<input id="city_tier3_<?=$city->getName()?>" title="<?=$city->getName()?>" type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/>
						<a onclick="selectFilterCheckBoxes('city_tier3_<?=$city->getName()?>'); return false;" style="text-decoration:none;color:black;cursor: default;" href="#"><?=$city->getName()?></a>
				<?php } else { ?>
					<input title="<?=$city->getName()?>" type="checkbox" <?=$checked?> name="city[]" value="<?=$city->getId()?>"/> <?=$city->getName()?>
				<?php } ?>
			</label>
		</li>
		<?php }} ?>
</ul>
<?php	
	}
}
?>