<?php
global $filter_list_title;
global $filter_list_source;
global $filters;
global $appliedFilters;
$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();
$count = 0;
if(isset($appliedFilters['city']) && count($appliedFilters['city'])>0){
	$count += count($appliedFilters['city']);
}
if(isset($appliedFilters['state']) && count($appliedFilters['state'])>0){
	$count += count($appliedFilters['state']);
}
if(isset($appliedFilters['country']) && count($appliedFilters['country'])>0){
	$count += count($appliedFilters['country']);
}
$filter_list_title='Countries';
if($filters['country'] && count($filters['country']->getFilteredValues()) > 1){
                        $filter_list_source = "country";
                        $filter_list_title = "Countries";
} else if(is_array($topFilteredCitieslist) && count($topFilteredCitieslist) > 1){
                        $filter_list_source = "topFilteredCities";
                        $filter_list_title = "State & Top Cities";
} else if($filters['city'] && count($filters['city']->getFilteredValues()) > 1){
                        $filter_list_source = "city";
                        $filter_list_title = "State & Cities";
} else {
                        $locationDataFlag = 0;
                        $filter_list_title='';
}

?>
<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="locationOverlayClose" href="javascript:void(0);" onclick="clearCPAutoSuggestor(); closeSecondOverlay();"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>   	
        <h3>Select <?=$filter_list_title?></h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap of-hide" style="height: 100%">
    <div class="search-option2" id="autoSuggestRefine">
        <div id="searchbox2">
        	<span class="icon-search"></span>
        	<input id="search" type="text" placeholder="Enter Name" onkeyup="locationCPAutoSuggest(this.value);" autocomplete="off">
        	<i class="icon-cl" onClick="clearCPAutoSuggestor();">&times;</i>
        </div>
    </div>
   
        <div class="content-child2 clearfix" style="padding:0 0.7em;">
    <section id="loc-section">
	    <nav id="side-nav" class="loc-nav">
			    <ul style="margin-bottom: 45px;">
				<li style="cursor:pointer;" class="active" onClick="showHide('layer-list-ul');" id="layer-list-ulMenu"><span>All Locations</span></li>
				<li style="cursor:pointer;" onClick="showHide('selectedbyu');" id="selectedbyuMenu">Selected By You (<strong id="count"></strong>)</li>
			    </ul>
	    </nav>
	    <div id="location_list_div" > Loading...</div>
	    <div id="selected"> Loading..</div>

    </section>
    </div>
</section>

<a id="lDButton" class="refine-btn" href="javascript:void(0);" onclick="locationLayerDone();"><span class="icon-done"><i></i></span> Done</a>
<script>
jQuery(document).ready(function(){
window.onscroll = function() { 
	jQuery('#lDButton').css({position: 'fixed', left: '0px', bottom: '0px', width:'100%'});
}
});
var locationIdsCount = <?=$count?>;
total_selected_mobile=0;
idSelected='';

function showHide(id){         
	var arr=new Array("mcities","selectedbyu");
	window.jQuery("#"+id+'Menu').addClass('active').siblings().removeClass('active');
	for (var i = 0; i < arr.length; i++) {
	    if(arr[i]==id ){
		window.jQuery("#"+arr[i]).show();
	    }else{
		window.jQuery("#"+arr[i]).hide();
	    }
	    //Do something
	   }	
	clearAutoSuggestor(id);
	if(id=="selectedbyu"){
	         idSelected= "selectedbyu";
		  jQuery("#selected").show();
		 jQuery("#location_list_div").hide();
	    if(locationIdsCount==0){
		    jQuery("#autoSuggestRefine").hide();
	    }else{jQuery("#autoSuggestRefine").show();}
	}
	else{
	     jQuery("#selected").hide();
             jQuery("#location_list_div").show();
	     jQuery("#autoSuggestRefine").show();
	     idSelected='';
	}
}

chkdLocationQueueArray = new Array();
var countCL=<?=$count?>;
var idSelected='';
jQuery("#count").html(countCL);
function queueThisLocation(elm) {
	i=0;
	jQuery("#selectedbyuMenu").show();
	if (elm.checked) {
		if(chkdLocationQueueArray.indexOf(jQuery.trim(elm.value))==-1)
			chkdLocationQueueArray.unshift(jQuery.trim(elm.value));	 
		countCL++;
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
		jQuery("#selectedbyu").append(parent_new);
		jQuery("#"+newId).attr("name","selected_country[]");
		jQuery("#last").remove();
		jQuery("#last_selected").remove();
		jQuery("#location_list_ul").append('<li id = "last"><label for=""></label></li>');
		jQuery("#selectedbyu").append('<li id="last_selected"><label for=""></label></li>');
		if(jQuery('#'+old_input_id).attr('checked')){
                         jQuery("#"+newId).attr('checked','true');
                }

	} else {
		//jQuery("#last").hide();
		var index = chkdLocationQueueArray.indexOf(elm.id);
        	chkdLocationQueueArray.splice(index, 1);
		countCL--;
		var ele_id = elm.getAttribute('id');
		if(ele_id.indexOf('new') != -1) {
			var id = ele_id.substr(0,ele_id.length-4);
			jQuery("#"+id).attr('checked',false);
			var parent_node = elm.parentNode.parentNode;
			parent_node.parentNode.removeChild(parent_node);
		}else {
			var id = ele_id + '_new';
			var elem = document.getElementById(id);
			var parent_node = elem.parentNode.parentNode;
			parent_node.parentNode.removeChild(parent_node);
			
		}
    	}
	total_selected_mobile = countCL;
	jQuery("#count").html(countCL);
	if(total_selected_mobile==0 && idSelected=="selectedbyu"){
		jQuery("#autoSuggestRefine").hide();}else{jQuery("#autoSuggestRefine").show();}
}
if(countCL==0)
	    jQuery("#selectedbyuMenu").hide();
else
	    jQuery("#selectedbyuMenu").show();
	    
function clearAutoSuggestor(layerId){
        jQuery('#search').val('');
        locationCPAutoSuggest('');
}
function locationLayerDone(){
    //Calculate the number of checked checkboxes inside Selected by you tab.
    locationIdsCount = 0;
    $('#selectedbyu input:checked').each(function() {
	locationIdsCount++;
    });
    selectCPLocation();
}
</script>
