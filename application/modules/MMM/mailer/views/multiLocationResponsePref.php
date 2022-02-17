<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
   <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta name="content-language" content="EN" />
   <link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
   <link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
   <meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
   <title><?php echo $headerComponents['title']; ?></title>
   
   <?php foreach($headerComponents['css'] as $cssFile) { ?>
   <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
   <?php } ?>
   
   <?php foreach($headerComponents['js'] as $jsFile) { ?>
   <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
   <?php } ?>
   </head>
   
   <body style="margin: 0; overflow: hidden;">
   <iframe src="<?php echo $courseURL; ?>" scrolling="no" frameborder='no' style="position: absolute; left: 0; top: 0; width: 100%; height: 100%;"></iframe> 
   <?php
   $hasCustomizedCity = false;
   $numberOfCities = 0;
   $numberOfCitiesWithLocalities = 0;
   
   if(key($instituteCityIds) > 0 && count($instituteCityIds) > 0) {
      $hasCustomizedCity = true;
   }
   
   if($hasCustomizedCity) {
      $numberOfCities = count($instituteCityIds);
      foreach($instituteCityIds as $cityId => $value) {
	 if(count($courseLocations['localities'][$cityId]) > 0) {
	    $numberOfCitiesWithLocalities++;
	 }
      }
   }
   else {
      $numberOfCities = count($courseLocations['cities']);
      $numberOfCitiesWithLocalities = count($courseLocations['localities']);      
   }
   
   $hasCitiesWithLocalities = 0;
   if($numberOfCitiesWithLocalities > 0) {
      $hasCitiesWithLocalities = 1;
   }
   
   $hasOtherCities = 0;
   if($numberOfCities - $numberOfCitiesWithLocalities > 0) {
      $hasOtherCities = 1;
   }
   
   ?>
      <iframe frameborder="0" container="genListingsOverlay" style="width: 100%; height: 100%; position: absolute; display: block; top: 0px; left: 0px; opacity: 0.4; z-index: 1000; background-color: rgb(0, 0, 0);" src="about:blank" name="iframe_div" id="iframe_div" scrolling="no" allowtransparency="true"></iframe>
      <div style="display: none; width: 665px; left: 311.5px; top: 75px;" class="management-layer Overlay" id="genListingsOverlay">
	 <div id="listingsOverlayContent">
	    <div class="multi-loc-layer">
	       <div style="margin-top: 6px" class="layer-head">
		  <div class="layer-title-txt"><?php echo $courseName; ?> is available at the following branches</div>
	       </div>
	       <div id="seeallbranches_layer_link" class="scrollbar1">
		  <div id="outer_scrollbar_link" class="scrollbar" style="visibility: hidden;">
		     <div class="track" >
			<div class="thumb" >
			   <div class="end"></div>
			</div>
		     </div>
		  </div>
		  <div id="viewport_outer_link" class="viewport">
		     <div id="overview_outer_link" class="overview">
			<?php if($hasCitiesWithLocalities) { ?>
			<ul class="branch-list">
			   <?php
			      foreach($courseLocations['localities'] as $cityId => $locations) {
				 if($hasCustomizedCity && !$instituteCityIds[$cityId]) {
				    continue;
				 }
				 
				 $numOfLocations = count($locations);
			   ?>
			   <li>
			      <strong><?php echo $courseLocations['cities'][$cityId]; ?></strong>
			      <p>
				 <a id="anchor_id<?php echo $cityId; ?>" onclick="showCityLocations(<?php echo $cityId; ?>, <?php echo $numOfLocations; ?>);return false;" href="javascript:void(0);"><?php echo $numOfLocations; ?> Locality(s) <i class="sprite-bg blue-rt-icon"></i></a>
			      </p>
			   </li>
			   <?php
			      }
			   ?>
			</ul>
			<?php
			      }
			      if($hasOtherCities) {
			?>
			<ul class="branch-list">
			   
			   <?php
			      if($numberOfCitiesWithLocalities > 0) {
				 echo '<p>Other Cities</p>';
			      }
			      
			      foreach($courseLocations['cities'] as $cityId => $cityName) {
				 if($hasCustomizedCity && !$instituteCityIds[$cityId]) {
				    continue;
				 }
				 
				 if(empty($courseLocations['localities'][$cityId])) {
				    $URL = $responseURL.'/'.$cityId;
				    echo '<li><a href="'.$URL.'">'.$cityName.'</a></li>';
				 }
			      }
			   ?>
			</ul>
			<?php } ?>
		     </div>
		  </div>
		  
		  <?php
		     if($hasCitiesWithLocalities) {
			foreach($courseLocations['localities'] as $cityId => $locations) {
			   if($hasCustomizedCity && !$instituteCityIds[$cityId]) {
			      continue;
			   }
			   
		  ?>
		  <div id="multilocation_layer_link<?php echo $cityId; ?>" class="locality-layer scrollbar1" onmouseleave="(this.style.display='none');" style="display: none;">
		     <div id="scrollbar_link<?php echo $cityId; ?>" style="display: none; visibility: visible;" class="scrollbar">
			<div class="track"> 
			   <div class="thumb">
			      <div class="end"></div>
			   </div>
			</div>
		     </div>
		     <div id="viewport_link<?php echo $cityId; ?>" class="viewport">
			<div id="overview_link<?php echo $cityId; ?>" class="overview">
			   <p><?php echo count($locations); ?> Locality(s) <i class="sprite-bg gray-dwn-icon"></i></p>
			   <ul>
			      <?php foreach($locations as $locationId => $locationName) { ?>
			      <li><a href="<?php echo $responseURL.'/'.$cityId.'/'.$locationId; ?>"><?php echo $locationName; ?></a></li>
			      <?php } ?>
			   </ul>
			</div>
		     </div> 
		  </div>
		  <?php
			}
		     }
		  ?>
	       </div>
	       <div class="clearFix"></div>
	    </div>
	 </div>
      </div>
   </body>
</html>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>

<script>
$(window).load(function($j) {
   if($('#seeallbranches_layer_link').length > 0) {
      $('#genListingsOverlay').css('display','inline');
      var overviewHeight = $('#seeallbranches_layer_link').find(".overview").height();
      if(overviewHeight < 365) {
	 if($('#seeallbranches_layer_link').find("#outer_scrollbar_link").length > 0) {
	    $('#seeallbranches_layer_link').find("#outer_scrollbar_link").remove();
	    $('#seeallbranches_layer_link').find("#viewport_outer_link").removeClass("viewport");
	    $('#seeallbranches_layer_link').find("#overview_outer_link").removeClass("overview");
	 }
      }
      else {
         $('#seeallbranches_layer_link').find(".viewport").height(365);
	 $('#outer_scrollbar_link').css('visibility','visible');
	 $("#seeallbranches_layer_link").tinyscrollbar();
      }
   }
});

function showCityLocations(city_id, count) {
   var anchor_id = 'anchor_id' + city_id;
   var div_id = 'multilocation_layer_link' + city_id;
   var scrollbar_id = 'scrollbar_link' + city_id;
   var viewport_id = 'viewport_link' + city_id;
   var overview_id = 'overview_link' + city_id;
   
   var div_position = $("#"+anchor_id).offset();
   $("#"+div_id).position(div_position);
   $("#"+div_id).css({'top':div_position.top - 78,'left':div_position.left - 315,'z-index':1000005 });
   $("#"+div_id).show();
   
   if(count > 4) {
      document.getElementById(overview_id).style.position = "absolute";
      document.getElementById(viewport_id).style.height = "120px";
      
      $("#"+scrollbar_id).show();
      $("#"+div_id).tinyscrollbar();
   }
   else {
      document.getElementById(viewport_id).style.height = "auto";
      document.getElementById(overview_id).style.position = "static";
   }
}

</script>