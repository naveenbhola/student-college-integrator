<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="locationOverlayClose" href="javascript:void(0);" onclick="setTimeout(function() {clearAutoSuggestorForLocation('layer-list-ul');}, 1000);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
        <h3>Select Location</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap of-hide" style="height: 100%">
    <div class="search-option2" id="autoSuggestRefine">
    	
            <div id="searchbox2">
            	<span class="icon-search"></span>
                <input id="city-list-textbox" type="text" placeholder="Enter city name" onkeyup="cityStateAutoSuggest(this.value,'city');" autocomplete="off">
                <input id="state-list-textbox" type="text" placeholder="Enter state name" onkeyup="cityStateAutoSuggest(this.value,'state');" autocomplete="off" style="display:none;">
                <i class="icon-cl" onClick="clearAutoSuggestorForLocation();">&times;</i>
            </div>
        
    </div>

 <div class="content-child2 clearfix" style="padding:0 0.7em;">
  <section id="loc-section">   
     <nav id="side-nav" class="loc-nav">
		    <ul style="margin-bottom: 45px;">
			<li style="cursor:pointer;" class="active" onClick="showHideLocations('city-list');" id="city-list-Menu"><span>All Cities</span></li>
			<li style="cursor:pointer;" onClick="showHideLocations('state-list');" id="state-list-Menu"><span>States</span></li>
		    </ul>
     </nav>
	<?php
	    $cityListArray['cityNames'][$i]['Name'] = 'All Cities';
	    $cityListArray['cityNames'][$i]['Id'] = '1';
	    $topCityListArray['cityNames'][$i]['Name'] = 'All Cities';
	    $topCityListArray['cityNames'][$i]['Id'] = '1';
	    $i++;
	?>
	<?php foreach($cityList[1] as $city){
	    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
	    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
	    $topCityListArray['cityNames'][$i]['Name'] = $city->getName();
	    $topCityListArray['cityNames'][$i]['Id'] = $city->getId();
	    $i++;
	} 
	?>
	<ul class="location-list location-list2" id="city-list">
   		<li style="padding:8px 6px !important;font-size: 0.9em;background-color:#ebebeb;"  id="top_city_heading">&nbsp;Top Cities</li>
		<?php foreach( $topCityListArray['cityNames'] as $key=>$value){ ?>
		<li id="cLI<?php echo $value['Id'];?>"><label id ="L_cLI<?php echo $value['Id'];?>" onClick="getLocation('<?php echo $value['Id'];?>','city');">
	<!--<input name="selected_city[]" type="radio" value="<?php echo $value['Name'];?>_<?php echo $value['Id'];?>"/>-->
	 <span id="<?php echo 'cN'.$value['Id'];?>"><?php echo $value['Name'];?></span></label></li>
		<?php } ?>
	</ul>
 	

	<?php 
	foreach($cityList[2] as $city){
	    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
	    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
	    $otherCityListArray[$city->getName()][$i]['Name'] = $city->getName();
	    $otherCityListArray[$city->getName()][$i]['Id'] = $city->getId();
	    $i++;
	}

	foreach($cityList[3] as $city){
	    $cityListArray['cityNames'][$i]['Name'] = $city->getName();
	    $cityListArray['cityNames'][$i]['Id'] = $city->getId();
	    $otherCityListArray[$city->getName()][$i]['Name'] = $city->getName();
	    $otherCityListArray[$city->getName()][$i]['Id'] = $city->getId();
	    $i++;
	} 
	ksort($otherCityListArray);
	?>

	<ul class="location-list location-list2" id="other-city-list">
	<li style="padding:8px 6px !important;font-size: 0.9em;background-color:#ebebeb;" id="other_city_heading">&nbsp;Other Cities</li>
		<?php foreach( $otherCityListArray as $key=>$value){ ?>
			<?php foreach($value as $k=>$v){ ?>
		<li id="cLI<?php echo $v['Id'];?>"><label id ="L_cLI<?php echo $v['Id'];?>" onClick="getLocation('<?php echo $v['Id'];?>','city');">
		<!--<input name="selected_city[]" type="radio" value="<?php echo $value['Name'];?>_<?php echo $value['Id'];?>"/> -->
		<span id="<?php echo 'cN'.$v['Id'];?>"><?php echo $v['Name'];?></span></label></li>
			<?php } ?>
		<?php } ?>
		 <li href="javascript:void(0);" id="not-found-city-list" style="display:none;">
		 <label><span>No result found for this location.</span></label>
		 </li>
		<li>&nbsp;</li>
	</ul>
    <?php
	$states = $locationRepository->getStatesByCountry(2);
	global $EXCLUDED_STATES_IN_LOCATION_LAYER;
	foreach($states as $state){
	    if(in_array($state->getId(),$EXCLUDED_STATES_IN_LOCATION_LAYER)){//Hiding Delhi State
		    continue;
	    }
	    //$request->setData(array('cityId'=>1,'stateId'=>$state->getId(),'localityId'=>0,'zoneId'=>0));
	    $stateListArray['stateNames'][$i]['Name'] = $state->getName();
	    $stateListArray['stateNames'][$i]['Id'] = $state->getId();
	   // $stateListArray['stateNames'][$i]['Url'] = $request->getURL();
	    $i++;
	    
	}
    ?>
    <div id="state-list" style="display: none;">
	<ul class="location-list location-list2">
	<?php foreach( $stateListArray['stateNames'] as $key=>$value){ ?>
		<li id="sLI<?php echo $value['Id'];?>"><label id ="L_sLI<?php echo $value['Id'];?>" onClick="getLocation('<?php echo $value['Id'];?>','state');">
		<!--<input name="selected_state[]" type="radio" value="<?php echo $value['Name'];?>_<?php echo $value['Id'];?>"/> -->
		<span id="<?php echo 'sN'.$value['Id'];?>"><?php echo $value['Name'];?></span></label></li>
	<?php } ?>
		 <li href="javascript:void(0);" id="not-found-state-list" style="display:none;">
		 <label><span>No result found for this location.</span></label>
		 </li>
		 <li>&nbsp;</li>
	</ul>
    </div>

     <div id="selected" style="display: none;"> Loading..</div>
 
  </section>
</div>
</section>
<!--<a id="city-list-div-examButton" class="refine-btn" style="position:fixed;left:0px;bottom:0px;width:100%" href="javascript:void(0);" onClick="getLocation('<?php echo $value['Id'];?>');"><span class="icon-done"><i></i></span> Done</a>
<a id="state-list-div-examButton" style="display:none;" class="refine-btn" style="position:fixed;left:0px;bottom:0px;width:100%" href="javascript:void(0);" onClick="getLocation('<?php echo $value['Id'];?>','state');"><span class="icon-done"><i></i></span> Done</a>-->

<script>
function showHideLocations(id){
	var arr=new Array("city-list","state-list");
	window.jQuery("#"+id+'-Menu').addClass('active').siblings().removeClass('active');
	for (var i = 0; i < arr.length; i++) {
	    if(arr[i]==id){
		window.jQuery("#"+arr[i]).show();
		window.jQuery("#"+arr[i]+'-textbox').show();
		if(id=='city-list'){
			window.jQuery("#other-"+arr[i]).show();
		}
		//window.jQuery("#"+arr[i]+'-examButton').show();
	    }else{
		window.jQuery("#"+arr[i]).hide();
		window.jQuery("#"+arr[i]+'-textbox').hide();
		if(id=='state-list'){
			window.jQuery("#other-"+arr[i]).hide();
		}
		//window.jQuery("#"+arr[i]+'-examButton').hide();
	    }
	}
}
</script>
