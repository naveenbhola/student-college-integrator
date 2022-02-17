<?php
$destinationCountryValues = $fields['destinationCountry']->getValues();
$regions = $destinationCountryValues['regions'];
$countries = $destinationCountryValues['countries'];
?>
<div id='destinationCountryLayer_<?php echo $regFormId; ?>'>
<div id='destinationCountryLayerInner_<?php echo $regFormId; ?>'>
<div>
	<header id="page-header" class="clearfix">
        <div class="head-group">
            <a id="destinationCountryOverlayClose" href="javascript:void(0); onclick=clearAutoSuggestorC();" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>
            <h3>Destination Country(s)</h3>
        </div>
    </header>
	

		<section class="content-wrap2 of-hide" style="margin-bottom:0">
		
		<div id="autoSuggestRefineC" class="search-option2" style="padding-top:12px">
			<div id="searchbox2">
               <span class="icon-search"></span>
               <input id="search" type="text" placeholder="Enter location name" onkeyup="locationAutoSuggestSRFC(this.value);" autocomplete="off">
               <i class="icon-cl" onClick="clearAutoSuggestorC();">&times;</i>
          	</div>
		</div>
		
		<div class="content-child2 clearfix" style="padding:0 0.7em;">
			<section id="loc-section">
			<nav id="side-nav" class="loc-nav">
				<ul style="margin-bottom: 45px;">
				<li style="cursor:pointer;" onClick="showHideC('dcl_popular_countries');" id="dcl_popular_countriesMenu" class="active"><span>Popular Countries</span></li>	
				<?php foreach($regions as $region) { ?>
					<li style="cursor:pointer;" onClick="showHideC('dcl_<?php echo $region->getId(); ?>');" id="dcl_<?php echo $region->getId(); ?>Menu"><span><?php echo $region->getName(); ?></span></li>
				<?php } ?>
				<li style="cursor:pointer;" onClick="showHideC('dcl_selectedbyu');" id="dcl_selectedbyuMenu">Selected By You(<strong id="countSA"></strong>)</li>
				</ul>
			</nav>
			
			<ul class="location-list location-list2" id="dcl_popular_countries" style="display:block;">
				<?php foreach($countries[0] as $country) { ?>
					<li id ="li_dcl_country_<?php echo $country->getId();?>">
						<label for="country_<?php echo $country->getId();?>" id="label_dcl_country_<?php echo $country->getId();?>">
						<input id="country_<?php echo $country->getId(); ?>" type="checkbox" name="destinationCountry[]" class="destinationCountry_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('destinationCountry'); ?> value="<?php echo $country->getId(); ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"  onChange='queueThisLocationC(this);'/>&nbsp;
							<span id ="name_dcl_country_<?php echo $country->getId();?>">
								<?php echo $country->getName(); ?>
							</span>	
						</label>
					</li>
				<?php } ?>
				<li>
					<label for=""></label>
				</li>
			</ul>
			
			<?php foreach($regions as $region) { ?>
				<ul class="location-list location-list2" id="dcl_<?php echo $region->getId(); ?>" style="display:none;">
				<?php foreach($countries[$region->getId()] as $country) { ?>
					<li id ="li_dcl_country_<?php echo $country->getId();?>">
						<label for="country_<?php echo $country->getId();?>" id="label_dcl_country_<?php echo $country->getId();?>">
						<input id="country_<?php echo $country->getId(); ?>" type="checkbox" name="destinationCountry[]" class="destinationCountry_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('destinationCountry'); ?> value="<?php echo $country->getId(); ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"  onChange='queueThisLocationC(this);'/>&nbsp;
							<span id ="name_dcl_country_<?php echo $country->getId();?>">
								<?php echo $country->getName(); ?>
							</span>	
						</label>
					</li>
				<?php } ?>
				<li>
					<label for=""></label>
				</li>
				</ul>
			<?php } ?>
			
			<ul class="location-list location-list2" id="dcl_selectedbyu" style="display:none;">
			</ul>
			</section>     
		</div>
	</section>

	<a id="doneAbroadButton" class="refine-btn box-shadow" style="" href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setDestinationCountries();window.jQuery('#destinationCountryOverlayClose').click();setLocationsC();">
    <span class="icon-done"><i></i></span>&nbsp;Done</a>
</div>
</div>
</div>

<script>
jQuery(document).ready(function(){
    window.onscroll = function() { 
        jQuery('#doneAbroadButton').css({position: 'fixed', left: '0px', bottom: '0px', width:'100%'});
    }
});

function showHideC(id){
	window.jQuery("#"+id+'Menu').addClass('active').siblings().removeClass('active');
	
	window.jQuery("#dcl_popular_countries").hide();
	window.jQuery("#dcl_selectedbyu").hide();
	<?php foreach($regions as $region) { ?>
		window.jQuery("#dcl_<?php echo $region->getId(); ?>").hide();
	<?php } ?>
	
	window.jQuery("#"+id).show();
	
	clearAutoSuggestorC(id);
	if(id=="dcl_selectedbyu"){
		idSelected= "dcl_selectedbyu";
		if(total_selected_mobile_sa==0){
			jQuery("#autoSuggestRefine").hide();}else{jQuery("#autoSuggestRefine").show();
		}
	}
	else{
		jQuery("#autoSuggestRefine").show();
		idSelected='';
	}
}
chkdLocationQueueArrayC = new Array();
var countSA=0;
jQuery("#countSA").html(countSA);
var idSelected='';
function queueThisLocationC(elm) {
	i=0;
	jQuery("#dcl_selectedbyuMenu").show();
	if (elm.checked) {
		if(chkdLocationQueueArrayC.indexOf(jQuery.trim(elm.value))==-1)
			chkdLocationQueueArrayC.unshift(jQuery.trim(elm.value));	 
		countSA++;
		checkedElementId=elm.getAttribute('id');
		var parent_old = elm.parentNode.parentNode;
		var parent_new = parent_old.cloneNode(true);
		var childs = parent_new.getElementsByTagName("label");
		var lable_child = childs[0];
		var input_ele = lable_child.getElementsByTagName("input");
		var span_ele = lable_child.getElementsByTagName("span");
		parent_new.id=parent_new.id+'_new';
		lable_child.id=lable_child.id+'_new';
		old_input_id=input_ele[0].id;
		input_ele[0].id=input_ele[0].id+'_new';
		newId=input_ele[0].id;
		span_ele[0].id=span_ele[0].id+'_new';
		jQuery("#dcl_selectedbyu").append(parent_new);
		jQuery("#lastC").remove();
		jQuery("#dcl_selectedbyu").append('<li id="lastC"><label for=""></label></li>');
		if(jQuery('#'+old_input_id).attr('checked')){
            jQuery("#"+newId).attr('checked','true');
        }
	} else {
		var index = chkdLocationQueueArrayC.indexOf(elm.id);
        	chkdLocationQueueArrayC.splice(index, 1);
		countSA--;
		var ele_id = elm.getAttribute('id');
		if(ele_id.indexOf('new') != -1) {
			var id = ele_id.substr(0,ele_id.length-4);
			var elem = document.getElementById(id);
			jQuery("#"+id).attr('checked', false)
			var parent_node = elm.parentNode.parentNode;
			parent_node.parentNode.removeChild(parent_node);
			
		}else {
			var id = ele_id + '_new';
			var elem = document.getElementById(id);
			var parent_node = elem.parentNode.parentNode;
			parent_node.parentNode.removeChild(parent_node);
			
		}
    }
	
	total_selected_mobile_sa = countSA;
	if(total_selected_mobile_sa==0 && idSelected=="dcl_selectedbyu"){
		jQuery("#autoSuggestRefineC").hide();
	}
	else {
		jQuery("#autoSuggestRefineC").show();
	}
	jQuery("#countSA").html(countSA);
}

if(countSA==0)
	    jQuery("#dcl_selectedbyuMenu").hide();
else
	    jQuery("#dcl_selectedbyuMenu").show();
	    
function setLocationsC(){
	window.jQuery('#destinationCountry<?php echo $regFormId; ?>').val(chkdLocationQueueArrayC);
}

function removeSelectedLocationsOnRefiningSA(){
      var w = document.getElementsByTagName('input'); 
      for(var i = 0; i < w.length; i++){ 
        if(w[i].type=='checkbox'){ 
	  if(w[i].checked)
            w[i].checked = 'false'; 
          }
	}
	window.jQuery("#dcl_selectedbyu").html('');
	total_selected_mobile_sa=0;
	countSA=0;
	showHideC("dcl_popular_countries");
	$j('#countSA').html('0');
}
function locationAutoSuggestSRFC(searchedString){ 
	var showSuggestionsCount = 10;
	var highPriorityArray = new Array();
	var lowPriorityArray = new Array();
	var a=0, b=0, c=0;
	if(searchedString!=''){
		console.log(searchedString);
		jQuery('span[id^="name_dcl_country_"]').each( function(ind) {
				var countryName = jQuery.trim(jQuery(this).html());
				var idArr = jQuery(this).attr('id').split('_');
				var countryId = idArr[idArr.length-1];
				if(countryName.toLowerCase().indexOf(searchedString.toLowerCase())===0){
						highPriorityArray[a] = new Array();
						highPriorityArray[a]['name'] = countryName;
						highPriorityArray[a]['id'] = countryId;
						a++;
				}
				if(countryName.toLowerCase().indexOf(searchedString.toLowerCase())>0){
						lowPriorityArray[b] = new Array();
						lowPriorityArray[b]['name'] = countryName;
						lowPriorityArray[b]['id'] = countryId;
						b++;
				}
		});
		jQuery("li[id*='li_dcl_country_']").hide();
	}
	else{
		jQuery("li[id*='li_dcl_country_']").show();
	}
	for(var i=0,x=showSuggestionsCount; i<highPriorityArray.length && i<x; i++) {
		jQuery('#li_dcl_country_'+highPriorityArray[i]['id']).show();
	}
	if(i<10){
		for(var j=i,k=showSuggestionsCount; j<lowPriorityArray.length ; j++) {
			jQuery('#li_dcl_country_'+lowPriorityArray[j]['id']).show();
		}
	}
}
function clearAutoSuggestorC(layerId){
	jQuery('#search').val('');
	if(layerId=='layer-list-ul')
	   locationAutoSuggest('',layerId);
	else
	   locationAutoSuggestSRFC('');
}
</script>
