<?php
$locationClusters = $facets['location'];
$courseTypeClusters = $facets['course_type'];
$courseLevelClusters = $facets['course_level'];
$countryCount = count($locationClusters);
$clustersUpdated = array();
$countryflag = true;
if($countryCount > 1){ //If location facets has more than one country
	foreach($locationClusters as $countryId => $countryData){
		foreach($countryData as $key => $value){
			if($key == "cities"){
				if($countryflag){
					foreach($value as $cityId => $zonesData){ //More than one country so hide the zone in it
						$locationClusters[$countryId]['cities'][$cityId]['zones'] = array();
					}
					$countryflag = false;
				} else {
					$locationClusters[$countryId]['cities'] = array();
				}
			}
		}
	}
} else if($countryCount == 1){
	reset($locationClusters);
	$countryId = key($locationClusters);
	if(array_key_exists('cities', $locationClusters[$countryId])){
		$citiesData = $locationClusters[$countryId]['cities'];
		if(count($citiesData) == 1){ //If there is only one city
			foreach($citiesData as $cityId => $zonesData){
				foreach($zonesData as $key => $data){
					if($key == "zones"){
						$zonesCount = count($data);
						if($zonesCount == 1){ //If there is only single zone in facets
							//Do no update in clusters array show all localities
						} else { //If there are multiple zones in facets, show only zone
							foreach($data as $localityData){
								$locationClusters[$countryId]['cities'][$cityId]['zones'][$localityData['id']]['localities'] = array();
							}
						}
					}
				}
			}
		} else { //If there are multiple cities in facets, make the zones array empty
			foreach($citiesData as $cityId => $data){
				$locationClusters[$countryId]['cities'][$cityId]['zones'] = array();
			}
		}
	}
}
$citiCount = 0;
$zonesCount = 0;
$localityCount = 0;
foreach($locationClusters as $countryId => $countryData){
	$cities = $countryData['cities'];
	$citiCount += count($cities);
	foreach($cities as $cityId => $cityData){
		$zones = $cityData['zones'];
		$zonesCount += count($zones);
		foreach($zones as $zoneId => $zoneData){
			$localities = $zoneData['localities'];
			$localityCount += count($localities);
		}
	}
}

$ulHeightStyle = "height:125px;";
if($citiCount + $zonesCount + $localityCount <= 5){
	$ulHeightStyle = "height:auto;";
}

$queryString = convertArrayToQueryString($_REQUEST);
$currentURL = $_SERVER['SCRIPT_URI'] . "?" . $queryString;

$locationParams = array('city_id', 'locality_id', 'country_id', 'zone_id');
$selectedLocation = "all";
$selectedLocationValue = "";

$countryIdUrlParam = $_REQUEST['country_id'];
if(isset($countryIdUrlParam) && !empty($countryIdUrlParam)){
	$selectedLocation = "country_id";
	$selectedLocationValue = $countryIdUrlParam;
}

$cityIdUrlParam = $_REQUEST['city_id'];
if(isset($cityIdUrlParam) && !empty($cityIdUrlParam)){
	$selectedLocation = "city_id";
	$selectedLocationValue = $cityIdUrlParam;
}

$zoneIdUrlParam = $_REQUEST['zone_id'];
if(isset($zoneIdUrlParam) && !empty($zoneIdUrlParam)){
	$selectedLocation = "zone_id";
	$selectedLocationValue = $zoneIdUrlParam;
}

$localityIdUrlParam = $_REQUEST['locality_id'];
if(isset($localityIdUrlParam) && !empty($localityIdUrlParam)){
	$selectedLocation = "locality_id";
	$selectedLocationValue = $localityIdUrlParam;
}

$courseTypeValue = "all";
$courseLevelValue = "all";

$courseTypeUrlParam = $_REQUEST['course_type'];
if(isset($courseTypeUrlParam) && !empty($courseTypeUrlParam)){
	$courseTypeValue = $courseTypeUrlParam;
}

$courseValueUrlParam = $_REQUEST['course_value'];
if(isset($courseValueUrlParam) && !empty($courseValueUrlParam)){
	$courseLevelValue = $courseValueUrlParam;
}

?>
<div id="search-refine">
	<h5>Refine institutes by</h5>
	<div class="refine-details" style="*height:0.01%;">
		<div class="refine-cols">
			<h6>Location</h6>
			<ul style="<?php echo $ulHeightStyle;?>">
				<?php
				if($selectedLocation == "all"){
					?>
					<li><strong>All</strong></li>
					<?php
				} else {
					?>
					<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'country_id', "", $locationParams);?>">All</a>
					<?php
				}
				$countryCount = count($locationClusters);
				foreach($locationClusters as $countryData){
					$countryId 	= $countryData['id'];
					$countryName = $countryData['name'];
					$countryCount = $countryData['value'];
				?>
					<li>
						<?php
						if($selectedLocation == "country_id"){
							if($selectedLocationValue == $countryId){
								?>
								<strong><?php echo $countryName;?> (<?php echo $countryCount;?>)</strong>
								<?php
							} else {
								?>
								<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'country_id', $countryId, $locationParams);?>"><?php echo $countryName;?> (<?php echo $countryCount;?>)</a>
								<?php
							}
						} else {
							?>
							<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'country_id', $countryId, $locationParams);?>"><?php echo $countryName;?> (<?php echo $countryCount;?>)</a>
							<?php
						}
						?>
					</li>
					<li>
						<?php
						$cities = $countryData['cities'];
						$toggleStyleFlag = true;
						$tempZones = array();
						foreach($cities as $city){
							$tempZones[$city['id']]	= count($city['zones']);
						}
						foreach($cities as $city){
							$cityId = $city['id'];
							$cityName = $city['name'];
							$cityCount = $city['value'];
							if(trim($cityName) == ""){
								$cityName = "Other";
							}
						?>
							<div class="loc-states">
								<p>
									<?php
									if($selectedLocation == "city_id"){
										if($selectedLocationValue == $cityId){
											?>
											<strong><?php echo $cityName;?> (<?php echo $cityCount;?>)</strong>
											<?php
										} else {
											?>
											<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'city_id', $cityId, $locationParams);?>">
												<?php echo $cityName;?> (<?php echo $cityCount;?>)
											</a>
											<?php
										}
									} else {
										?>
										<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'city_id', $cityId, $locationParams);?>">
											<?php echo $cityName;?> (<?php echo $cityCount;?>)
										</a>
										<?php
									}
									?>
								</p>
							</div>
							<!--<div id="city-container-<?php echo $cityId;?>">-->
							<?php
							$zones = $city['zones'];
							if(count(array_keys($tempZones)) == 1){ //If only this city has zones in it, else it means its not a city level query
								$tempLocalities = array();
								foreach($zones as $zone){
									$tempLocalities[$zone['id']]	= count($zone['localities']);
								}
								foreach($zones as $zone){
									$zoneId = $zone['id'];
									$zoneName = $zone['name'];
									if(trim($zoneName) == ""){
										$zoneName = "Other";
									}
									$zoneCount = $zone['value'];
								?>
										<div class="loc-zones">
											<span id='zone-container-<?php echo $zoneId;?>-head'></span>
											<p>
												<?php
												if($selectedLocation == "zone_id"){
													if($selectedLocationValue == $zoneId){
														?>
														<strong><?php echo $zoneName;?> (<?php echo $zoneCount;?>)</strong>
														<?php
													} else {
														?>
														<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'zone_id', $zoneId, $locationParams);?>">
															<?php echo $zoneName;?> (<?php echo $zoneCount;?>)
														</a>
														<?php
													}
												} else {
													?>
													<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'zone_id', $zoneId, $locationParams);?>">
														<?php echo $zoneName;?> (<?php echo $zoneCount;?>)
													</a>
													<?php
												}
												?>
											</p>
											<?php
											$localities = $zone['localities'];
											if(count($localities) > 0 && count(array_keys($tempLocalities)) == 1){
											?>
											<ol id="zone-container-<?php echo $zoneId;?>">
												<?php
												foreach($localities as $locality){
													$localityId = $locality['id'];
													$localityName = $locality['name'];
													if(trim($localityName) == ""){
														$localityName = "Other";
													}
													$localityCount = $locality['value'];
												?>
													<li>
														<?php
														if($selectedLocation == "locality_id"){
															if($selectedLocationValue == $localityId){
																?>
																<strong><?php echo $localityName;?> (<?php echo $localityCount;?>)</strong>
																<?php
															} else {
																?>
																<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'locality_id', $localityId, $locationParams);?>">
																	<?php echo $localityName;?> (<?php echo $localityCount;?>)
																</a>
																<?php
															}
														} else {
															?>
															<a href="<?php echo changeUrlParamAndEmptyParamList($currentURL, 'locality_id', $localityId, $locationParams);?>">
																<?php echo $localityName;?> (<?php echo $localityCount;?>)
															</a>
															<?php
														}
														?>
													</li>
												<?php
												}
												?>
											</ol>
											<?php
											}
											?>
										</div>
								<?php
								}
							}
						}
						?>
					</li>
				<?php
				}
				?>
			</ul>
		</div>
		
		<div class="refine-cols">
			<h6>Mode of Learning</h6>
			<ul style="<?php echo $ulHeightStyle;?>">
				<?php
				$selectedCourseTypeCluster = array();
				$otherCourseTypeCluster = array();
				if(array_key_exists('selected', $courseTypeClusters)){
					$selectedCourseTypeCluster =  $courseTypeClusters['selected'];
				}
				if(array_key_exists('others', $courseTypeClusters)){
					$otherCourseTypeCluster =  $courseTypeClusters['others'];
				}
				$firstDisplayList = $selectedCourseTypeCluster;
				$secondDisplayList = $otherCourseTypeCluster;
				if(array_key_exists('all', $otherCourseTypeCluster)){
					$firstDisplayList = $otherCourseTypeCluster;
					$secondDisplayList = $selectedCourseTypeCluster;
				}
				foreach($firstDisplayList as $key => $value){
					if($courseTypeValue == "all"){
						?>
						<li><strong><?php echo $value['name']?></strong></li>
						<?php
					} else {
						?>
						<li>
							<a href="<?php echo $value['url'];?>">
								<?php
								echo $value['name'];
								if($value['count'] != ""){
								?>
									(<?php echo $value['count'];?>)
								<?php
								}
								?>
							</a>
						</li>
						<?php
					}
				}
				
				foreach($secondDisplayList as $key => $value){
					if($courseTypeValue == $key){
						?>
						<li>
							<strong><?php echo $value['name']?></strong>
						</li>
						<?php
					} else {
						?>
						<li>
							<a href="<?php echo $value['url'];?>">
								<?php
								echo $value['name'];
								if($value['count'] != ""){
								?>
									(<?php echo $value['count'];?>)
								<?php
								}
								?>
							</a>
						</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
		
		<div class="refine-last-cols">
			<h6>Course Level</h6>
			<ul style="<?php echo $ulHeightStyle;?>">
				<?php
				$selectedCourseLevelCluster = array();
				$otherCourseLevelCluster = array();
				if(array_key_exists('selected', $courseLevelClusters)){
					$selectedCourseLevelCluster =  $courseLevelClusters['selected'];
				}
				if(array_key_exists('others', $courseLevelClusters)){
					$otherCourseLevelCluster =  $courseLevelClusters['others'];
				}
				$firstDisplayList = $selectedCourseLevelCluster;
				$secondDisplayList = $otherCourseLevelCluster;
				if(array_key_exists('all', $otherCourseLevelCluster)){
					$firstDisplayList = $otherCourseLevelCluster;
					$secondDisplayList = $selectedCourseLevelCluster;
				}
				
				foreach($firstDisplayList as $key => $value){
					if($courseLevelValue == "all"){
						?>
						<li><strong><?php echo $value['name']?></strong></li>
						<?php
					} else {
						?>
						<li>
							<a href="<?php echo $value['url'];?>">
								<?php
								echo $value['name'];
								if($value['count'] != ""){
								?>
									(<?php echo $value['count'];?>)
								<?php
								}
								?>
							</a>
						</li>
						<?php
					}
				}
				
				foreach($secondDisplayList as $key => $value){
					if($courseLevelValue == $key){
						?>
						<li>
							<strong><?php echo $value['name']?></strong>
						</li>
						<?php
					} else {
						?>
						<li>
							<a href="<?php echo $value['url'];?>">
								<?php
								echo $value['name'];
								if($value['count'] != ""){
								?>
									(<?php echo $value['count'];?>)
								<?php
								}
								?>
							</a>
						</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
	</div>
</div>
