<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="locationOverlayClose" data-rel="back" href="javascript:void(0);" onclick="clearCPAutoSuggestor();"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
        <h3>Select Localities and Cities</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap of-hide" style="height: 100%">
    <div class="search-option2" id="autoSuggestRefine">
    	
            <div id="searchbox2">
            	<span class="icon-search"></span>
                <input id="search" type="text" placeholder="Enter city name" onkeyup="locationCPAutoSuggest(this.value);" autocomplete="off">
                <i class="icon-cl" onClick="clearCPAutoSuggestor();">&times;</i>
            </div>
        
    </div>

 <div class="content-child2 clearfix" style="padding:0 0.7em;">
  <section id="loc-section">   
     <nav id="side-nav" class="loc-nav">
		    <ul style="margin-bottom: 45px;">
			<li style="cursor:pointer;" class="active" onClick="showHide('layer-list-ul-2');" id="layer-list-ul-2Menu"><span>Localities</span></li>
			<li style="cursor:pointer;" onClick="showHide('layer-list-ul');" id="layer-list-ulMenu"><span>Cities</span></li>
			<li style="cursor:pointer;" onClick="showHide('selectedbyu');" id="selectedbyuMenu">Selected By You (<strong id="count"></strong>)</li>
		    </ul>
    </nav>

    <div id="locality_list_div" > Loading...</div>

    <div id="location_list_div" style="display: none;"> Loading...</div>

     <div id="selected" style="display: none;"> Loading..</div>
 
  </section>
</div>
</section>
<?php
global $filters;
global $appliedFilters;
$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();
$localityFilterValues = $filters['locality']->getFilteredValues();
$cityFilterValues = $filters['city']->getFilteredValues();
$count= 0;
if(isset($appliedFilters['city']) && count($appliedFilters['city'])>0){
	$count += count($appliedFilters['city']);
}
if(isset($appliedFilters['locality']) && count($appliedFilters['locality'])>0){
	$count += count($appliedFilters['locality']);
}
?>


<a id="lDButton" class="refine-btn" href="javascript:void(0);" onclick="locationLayerDone();"><span class="icon-done"><i></i></span> Done</a>
<script>
    function _scrollUp() {
	setTimeout(function(){$('html, body').animate({ scrollTop: 10 });$('html, body').animate({ scrollTop: 0 });},1000);
    }
    
jQuery(document).ready(function(){
/*window.onscroll = function() { 
	jQuery('#lDButton').css({position: 'fixed', left: '0px', bottom: '0px', width:'100%'});
}*/
/*
var randomnumber=Math.floor(Math.random()*11000);
jQuery.ajax({
    url: reorderFilterLocationListURL,type: "POST",data: "r="+randomnumber,
    success: function(result){
	var HTML=jQuery.parseJSON(result);
	$('#location_list_div').html(HTML.all);
	$('#selected').html(HTML.selected);
	
	if(HTML.locality && HTML.locality!=''){
	    $('#locality_list_div').html(HTML.locality);
	}
	else{
	    $('#locality_list_div').hide();
	    $('#location_list_div').show();
	    $('#layer-list-ul-2Menu').hide();
	    $('#layer-list-ul-2Menu').attr('class','');
	    $('#layer-list-ulMenu').attr('class','active');
	}
    }
});*/
});

function locationLayerDone(){
    //Calculate the number of checked checkboxes inside Selected by you tab.
    locationIdsCount = 0;
    $('#selectedbyu input:checked').each(function() {
	locationIdsCount++;
    });
    selectCPLocation();
}

var categorypageKey  = "<?=$request->getPageKey()?>";

idSelected='';
function showHide(id){        
	var arr=new Array("layer-list-ul","selectedbyu","layer-list-ul-2");
	window.jQuery("#"+id+'Menu').addClass('active').siblings().removeClass('active');
	for (var i = 0; i < arr.length; i++) {
	    if(arr[i]==id ){
		window.jQuery("#"+arr[i]).show();
	    }else{
		window.jQuery("#"+arr[i]).hide();
	    }
	   }
	clearAutoSuggestor(id);
	if(id=="selectedbyu"){
               jQuery("#location_list_div").hide();
               jQuery("#locality_list_div").hide();
	       jQuery("#selected").show();
	       idSelected= "selectedbyu";
	    if(locationIdsCount==0){ 
		    jQuery("#autoSuggestRefine").hide();
	    }else{   jQuery("#autoSuggestRefine").show(); }
	}
	else if(id=="layer-list-ul"){
	    jQuery("#location_list_div").show();
            jQuery("#locality_list_div").hide();
	    jQuery("#selected").hide();
	    jQuery("#autoSuggestRefine").show();
	    idSelected='';
	}
	else{
	    jQuery("#location_list_div").hide();
            jQuery("#locality_list_div").show();
	    jQuery("#selected").hide();
	    jQuery("#autoSuggestRefine").show();
	    idSelected='';
	}
}

chkdLocationQueueArray = new Array();
var countCL=<?=$count?>;
jQuery("#count").html(countCL);
var idSelected='';
function queueThisLocation(elm,regionType) {
	jQuery("#selectedbyuMenu").show();
	i=0;
	if (elm.checked){
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
		lable_child.style.padding='';
		old_input_id=input_ele[0].id;
		input_ele[0].id=input_ele[0].id+'_new';
		new_input_id=input_ele[0].id;
		span_ele[0].id=span_ele[0].id+'_new';
		jQuery("#selectedbyu").append(parent_new);
		jQuery("#"+new_input_id).attr('name','selected_city[]');
		jQuery("#last_selected").remove();
		jQuery("#last").remove();
		jQuery("#layer-list-ul").append('<li id ="last"><label for =""></label></li>');
		jQuery("#selectedbyu").append('<li id="last_selected"><label for=""></label></li>');
		 if(jQuery('#'+old_input_id).attr('checked')){
                          jQuery("#"+new_input_id).attr('checked','true');
                  }	
	} else {
		var index = chkdLocationQueueArray.indexOf(elm.id);
        	chkdLocationQueueArray.splice(index, 1);
	//	countCL--;
		var ele_id = elm.getAttribute('id');
		if(ele_id.indexOf('new') != -1) {
			var id = ele_id.substr(0,ele_id.length-4);
			var elem = document.getElementById(id);
			jQuery("#"+id).attr('checked', false)
			var parent_node = elm.parentNode.parentNode;
			if(parent_node.parentNode)
			    parent_node.parentNode.removeChild(parent_node);
			countCL--;
			
		}else {
			var id = ele_id + '_new';
			var elem = document.getElementById(id);
			var parent_node = elem.parentNode.parentNode;
			if(parent_node.parentNode)
			    parent_node.parentNode.removeChild(parent_node);		
			if(regionType!='zone'){countCL--;}
		}
    	}
	//Calculate the number of checked checkboxes inside Selected by you tab.
	locationIdsCount = 0;
	$('#selectedbyu input:checked').each(function() {
	    locationIdsCount++;
	});
	jQuery("#count").html(countCL);
	if(locationIdsCount==0 && idSelected=="selectedbyu"){
		jQuery("#autoSuggestRefine").hide();
	}else{
		jQuery("#autoSuggestRefine").show();
	}
}
var locationIdsCount = <?=$count?>;
if(countCL==0)
	    jQuery("#selectedbyuMenu").hide();
else
	    jQuery("#selectedbyuMenu").show();
	    
function clearAutoSuggestor(layerId){
	jQuery('#search').val('');
	locationCPAutoSuggest('');
}

function applyFullZone(zone){
	//If the Zone is checked/unchecked, check/uncheck all the localities
	var localityCheckBoxes = $('input.zonelocality'+zone.toString());
        for(element in localityCheckBoxes){
	    if(document.getElementById('zone' + zone.toString()).checked){	//If zone is checked, check all the Localities which are not yet checked
		if(!localityCheckBoxes[element].checked){
		    localityCheckBoxes[element].checked = document.getElementById('zone' + zone.toString()).checked;
		    if(localityCheckBoxes[element].id){
			queueThisLocation(localityCheckBoxes[element],'zone');
		    }
		}		
	    }
	    else{	//If zone is unchecked, uncheck all the localities which are already checked
		if(localityCheckBoxes[element].checked){
		    localityCheckBoxes[element].checked = document.getElementById('zone' + zone.toString()).checked;
		    if(localityCheckBoxes[element].id){
			queueThisLocation(localityCheckBoxes[element],'zone');
		    }
		}		
	    }	    
        }
}

function applyLocality(zone, localityId){
	//In case if Localities are clicked, please check as soon as all localities are checked, we will check the Zone also
	var localityCheckBoxes = $('input.zonelocality'+zone.toString());
	var checkedLocalities = 0;
        var localityChecked = false;
	for(element in localityCheckBoxes){
	    if(localityCheckBoxes[element].checked){
			    if(localityId == localityCheckBoxes[element].value){
				    localityChecked = true;
			    }
		checkedLocalities++;
	    }
	}
	if(checkedLocalities == localityCheckBoxes.length){
	    $('#zone'+zone.toString()).attr('checked', true);
	} else {
	    $('#zone'+zone.toString()).attr('checked', false);
	}
	//queueThisLocation( document.getElementById('zone'+zone.toString()) );
}

</script>
