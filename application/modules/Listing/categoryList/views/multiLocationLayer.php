<?php
	$displayflag = '';
	// First check if all city page
	if($request->getCityId() == 1 && $request->getStateId() == 1 && !(in_array($request->getSubCategoryId(), array(23,56)) && $request->getHideLocationLayer() == 1)){
		// More than 1 location should be selected
		$userMultiLocationsPreference = json_decode(base64_decode($_COOKIE['userMultiLocPref-MainCat-'.$request->getCategoryId()]));
		//if(count($request->getUserPreferredLocationOrder()) > 1){
		if((in_array($request->getSubCategoryId(), array(23,56)) && count($userMultiLocationsPreference) > 0) || count($userMultiLocationsPreference) > 1){
			// Layer not required
			$displayflag = 'none';
		}else{
			// Layer required
			$displayflag = '';
		}
	}else{
		// Layer not required
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
		$displayMultiLocationWindowFlag = 1;
	}
	else
	{
		$mouseover = "this.style.display=''; overlayHackLayerForIE('multiLocationLayer', document.getElementById('multiLocationLayer'));" ;
		$mouseout = "if(alertExceptionToShowLocationLayer == false){dissolveOverlayHackForIE();this.style.display='none'}";
	}
	
	global $cityList;
	global $otherCityList;
	global $allCities;
	global $states;
	global $cityStateMixed;
	$cityStateMixed = array();
	$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
	for($j = 1; $j<=count($cityList); $j++){
		foreach($cityList[$j] as $key=>$city){
			if(!in_array($cityList[$j][$key]->getId(),$dynamicLocationList['cities'])){
				unset($cityList[$j][$key]);
			}
		}
	}
	
	$otherCityList = array_merge($cityList[2],$cityList[3]);
	usort($otherCityList,sortEntitiesByName);
	
	global $popularCitiesCategoryPage;
	$dynamicPopularCityList = array();
	if(in_array($request->getSubCategoryId(), array(23,56))) {
		$popularCityList = array_keys($popularCitiesCategoryPage[$request->getSubCategoryId()]);
		foreach($popularCityList as $popCityId) {
			if(in_array($popCityId, $dynamicLocationList['cities'])) {
				$dynamicPopularCityList[$popCityId] = $popularCitiesCategoryPage[$request->getSubCategoryId()][$popCityId];
			}
		}
	}
	
	$states = $locationRepository->getStatesByCountry(2);
	foreach($states as $key=>$state){
		if(!in_array($state->getId(),$dynamicLocationList['states'])){
			unset($states[$key]);
		}
	}
	
	function sortEntitiesByName($a,$b){
		return $a->getName()>$b->getName();
	}
?>
<div class="multi-loc-layer" id='multiLocationLayer' style="display: <?php echo $displayflag;?>;position: fixed;z-index:1300;" onmouseover="focusInMultiLayer =true;<?php echo $mouseover;?>" onmouseout="focusInMultiLayer=false;<?php //echo $mouseout;?>">
	<div class="title">
    	Select single or multiple cities or states to view institutes in the selected locations
    </div>
    <div class="selection-criteria clear-width" style="display: none;">
	        <label>Your selection:</label>
            <div class="selected-items" id="selectedItems">
        </div>
    </div>
    
    <div class="multi-loc-content customInputs clear-width">
    	
            <div class="columns flLt">
                <strong>Metro Cities</strong>
                <div class="loc-col-detail">
                    <div class="loc-search">
                    	<i class="common-sprite loc-search-icon"></i>
                        <input type="text" id="metroCitySearchBox" onkeyup="filterList(this);" />
			<span class="filterClear" style="display:none;" onclick="turnOffMultiLocationFiltering('metroCitySearchBox');">&times;</span>
                    </div>
                   
                        
                        <div class="viewport" style="overflow: hidden;height:201px;">
                            <div class="overview">
                                <ul class="multi-city-list" id="metroCitySearchBoxContainer">
				<?php 
                                  foreach($cityList[1] as $city) {
					
					$cityStateMixedTupple = array();
					$cityStateMixedTupple['location_type']= 'city';
					$cityStateMixedTupple['cityId']= $city->getId();
					$cityStateMixedTupple['stateId']= $city->getStateId();
					$cityStateMixedTupple['name']= $city->getName();
					$cityStateMixed[$city->getName()] = $cityStateMixedTupple;
					?>
				    <li>
                                        <input type="checkbox" id="city<?php echo $city->getId();?>" name="city" value="<?php echo $city->getId();?>" onchange="processLocation(this,'metroCity');">
                                        <label for="city<?php echo $city->getId();?>">
                                            <span class="common-sprite"></span><p><?php echo $city->getName(); ?></p>
                                        </label>
                                    </li>
				<?php }    ?>
                                </ul>
                            </div>
                        </div>
                 
                </div>
            </div>
            <div class="columns flLt">
                <strong>All Cities</strong>
                <div class="loc-col-detail">
                    <div class="loc-search">
                    	<i class="common-sprite loc-search-icon"></i>
                        <input type="text" id="citySearchBox" onkeyup="filterList(this);"/>
						<span class="filterClear" style="display:none;" onclick="turnOffMultiLocationFiltering('citySearchBox');">&times;</span>
                    </div>
                    <div class="scrollbar1">
                        <div class="scrollbar" style="height:201px;">	
                            <div class="track">
                                <div class="thumb"></div>
                            </div>
                        </div>
                        <div class="viewport" style="overflow: hidden;height:201px;">
                            <div class="overview" id="citySearchBoxContainer">
								<?php if(in_array($request->getSubCategoryId(), array(23,56))) { ?>
									<strong class="pop-city-title">Popular Cities</strong>
									<ul class="multi-city-list">
										<?php foreach($dynamicPopularCityList as $popCityId => $popCityName) { ?>
											<li>
												<input type="checkbox" id="city<?php echo $popCityId;?>" name="city" value="<?php echo $popCityId;?>" onchange="processLocation(this,'city');">
												<label for="city<?php echo $popCityId;?>">
													<span class="common-sprite"></span><p><?php echo $popCityName; ?></p>
												</label>
											</li>
										<?php } ?>
									</ul>
									<strong class="pop-city-title">All Cities</strong>
								<?php } ?>
                                <ul class="multi-city-list">
                                 <?php 
                                  foreach($otherCityList as $city) {
									$cityStateMixedTupple = array();
									$cityStateMixedTupple['location_type']= 'city';
									$cityStateMixedTupple['cityId']= $city->getId();
									$cityStateMixedTupple['stateId']= $city->getStateId();
									$cityStateMixedTupple['name']= $city->getName();
									$cityStateMixed[$city->getName()] = $cityStateMixedTupple;
									
									if(in_array($city->getId(), $popularCityList)) {
										continue;
									} ?>
									<li>
                                        <input type="checkbox" id="city<?php echo $city->getId();?>" name="city" value="<?php echo $city->getId();?>" onchange="processLocation(this,'city');">
                                        <label for="city<?php echo $city->getId();?>">
                                            <span class="common-sprite"></span><p><?php echo $city->getName(); ?></p>
                                        </label>
                                    </li>
				<?php }    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns flLt last">
                <strong>States in India</strong>
                <div class="loc-col-detail">
                    <div class="loc-search">
                    	<i class="common-sprite loc-search-icon"></i>
                        <input type="text" id="stateSearchBox" onkeyup="filterList(this);"/>
			<span class="filterClear" style="display:none;" onclick="turnOffMultiLocationFiltering('stateSearchBox');">&times;</span>
                    </div>
                    <div class="scrollbar1">
                        <div class="scrollbar">	
                            <div class="track" style="height:201px;">
                                <div class="thumb"></div>
                            </div>
                        </div>
                        <div class="viewport" style="overflow: hidden;height:201px;">
                            <div class="overview" id="stateSection">
                                <ul class="multi-city-list" id="stateSearchBoxContainer">
				   <?php
					$usefullStates = array();
					global $EXCLUDED_STATES_IN_LOCATION_LAYER;	
					foreach($states as $state){
					if(in_array($state->getId(),$EXCLUDED_STATES_IN_LOCATION_LAYER)){//Hiding Delhi State
						continue;
					}else{
						$usefullStates[] = $state;
						$cityStateMixedTupple = array();
						$cityStateMixedTupple['location_type']= 'state';
						$cityStateMixedTupple['stateId']= $state->getId();
						$cityStateMixedTupple['name']= $state->getName();
						$cityStateMixed[$state->getName()] = $cityStateMixedTupple;
					}
				   ?>
                                    <li>
					<input type="checkbox" id="state<?php echo $state->getId();?>" value="<?php echo $state->getId();?>" name="state" onchange="processLocation(this,'state');">
					<label for="state<?php echo $state->getId();?>">
					    <span class="common-sprite"></span><p><?php echo $state->getName(); ?></p>
					</label>
                                    </li>
				    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
 <?php
ksort($cityStateMixed);
$mixedList2 = $locationRepository->groupLocationByAlphabet($cityStateMixed);
 ?>       
        <div class="more-cities clear-width">
    		<a href="javaScript:void(0);" onclick="multiLocationShowHideCityState();">[<span id="mutlicityExpandText">+</span>] Cities and States</a>
            <div class="more-city-block" id="moreCityAlphabetSection" style="display:none;">
            	<div class="explore-alphabatically clear-width" style="margin-bottom:10px;">
			<ul>
                    	<?php
			$count = 0;
			$urlRequest = clone $request;
			foreach($mixedList2 as $key=>$mixval){
			$count++;
			?>
			<li><a href="javaScript:void(0);" onclick="changeCityStateAlphabet(this,'<?php echo $key;?>');" <?php if($count ==1) {echo 'class="active"';}?>><?php echo strtoupper($key);?></a></li>
			<?php } ?>
			</ul>
                </div>
		<div class="scrollbar1 clear-width">
			<div class="scrollbar">
			  <div style="height:95px" class="track">
			    <div class="thumb"></div>
			  </div>
			</div>
			<div class="viewport" style="overflow: hidden;height:95px;">
				<div class="overview">
         <?php
	 $count = 0;
	 foreach($mixedList2 as $key=>$mix){
		$count++;
		?>       
		<div class="footer-city-details" id="alphabetContainer<?php echo $key;?>" style="<?php if($count !=1) {echo 'display:none;';}?>">
                    <?php
		    $last1 =$last2=$last3=$last4=$last5 ='';
		    if(count($mix) < 5){
			$a = 'last'.count($mix);
			$$a='last';
		    }
		    $tuppleCount= 0;
		    $cityStateblock1 = '<div class="city-col '.$last1.'"><ul>';
		    $cityStateblock2 = '<div class="city-col '.$last2.'"><ul>';
		    $cityStateblock3 = '<div class="city-col '.$last3.'"><ul>';
		    $cityStateblock4 = '<div class="city-col '.$last4.'"><ul>';
		    $cityStateblock5 = '<div class="city-col last"><ul>';
		    $blockCloseHtml = ' </ul></div>';
		    foreach($mix as $tupple)
		    {
			if($tupple['location_type']=='city'){
			$urlRequest->setData(array('cityId'=>$tupple['cityId'],'stateId'=>$tupple['stateId'],'localityId'=>0,'zoneId'=>0), 0);
			$linkText = '<li><a id="location_c_'.$tupple['cityId'].'" href="'.$urlRequest->getURL().'" title = "'.$tupple['name'].'">'.$tupple['name'].'</a></li>';	
			}
			elseif($tupple['location_type']=='state'){
			 $urlRequest->setData(array('cityId'=>1,'stateId'=>$tupple['stateId'],'localityId'=>0,'zoneId'=>0), 0);
			 $linkText = '<li><a id="location_s_'.$tupple['stateId'].'" href="'.$urlRequest->getURL().'" title="'.$tupple['name'].'">'.$tupple['name'].'</a></li>';	
			}
			
		
			$tuppleCount++;
			switch($tuppleCount%5)
			{
				case 1:
				$cityStateblock1 .= $linkText;
				break;
				
				case 2:
				$cityStateblock2 .= $linkText;
				break;
				
				case 3:
				$cityStateblock3 .= $linkText;
				break;
				
				case 4:
				$cityStateblock4 .= $linkText;
				break;
				
				case 0:
				$cityStateblock5 .= $linkText;
				break;
			}
		    }
		   $cityStateblock1.= $blockCloseHtml;
		   $cityStateblock2.= $blockCloseHtml;
		   $cityStateblock3.= $blockCloseHtml;
		   $cityStateblock4.= $blockCloseHtml;
		   $cityStateblock5.= $blockCloseHtml;
		  
		  echo $cityStateblock1.$cityStateblock2.$cityStateblock3.$cityStateblock4.$cityStateblock5;
		    ?>    
	    </div>
	 <?php } ?>	
            </div>
		</div>
		</div>
	    </div>
    	</div>
        <div class="error-msg clear-width" id="multiLocationErrorMsg" style="display: none;">
        Please select at least one location to view institutes.
        </div>
        <div class="proceed-btn-col">
                <a href="javascript:void(0)" class="proceed-btn" onclick="processSearchedArrays()">Proceed</a>
            </div>
            <div class="clearFix"></div>
        </div>    
    <div class="clearFix"></div>
</div>
<script>
	<?php if($displayMultiLocationWindowFlag == 1){
	?>
	$('dim_bg').style.display = 'inline';

	var body = document.body,
    html = document.documentElement;
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight ); 

	var isIE6 = (navigator.userAgent.toLowerCase().substr(25,6)=="msie 6") ? true : false;
	height = height + 170; 
	if(isIE6){
		$('dim_bg').style.height = height+'px';//'2000px';
	}else{
		$('dim_bg').style.height = height+'px'; //'10000px';
	}
	$('dim_bg').style.zIndex = 1100;

	if(document.getElementById("changeLocationdiv")) {
	$('changeLocationdiv').style.visibility='hidden';
	}
	var divX = parseInt(screen.width/2)-(630/2);
	var divY = parseInt(screen.height/2)-351;
	$('multiLocationLayer').style.left = (divX) +  'px';
	//$('multiLocationLayer').style.top = (divY) + 'px';
	$('multiLocationLayer').style.top = 33 + 'px';
	<?php
	}
	
	$urlRequest->setData(array('cityId'=>1,'stateId'=>1,'localityId'=>0,'zoneId'=>0));
	$allCityPageUrl = $urlRequest->getURL();
	
	$separator = "?";
	if (strpos($allCityPageUrl,"?")!=false)
		$separator = "&";
	if(!array_key_exists("exam", $_GET))
		$allCityPageUrl .= $separator.$_SERVER['QUERY_STRING'];
	?>
	var allCityPageUrl = '<?=$allCityPageUrl?>';
	setCookie("userCityPreference","<?=$request->getCityId()?>:<?=$request->getStateId()?>:2");
</script>
