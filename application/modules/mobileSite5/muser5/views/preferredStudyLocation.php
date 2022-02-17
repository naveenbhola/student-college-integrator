<?php

if(is_object($fields['preferredStudyLocation'])) { 
	$residenceLocationValues = $fields['preferredStudyLocation']->getValues();
}
$tier1Cities = $residenceLocationValues['tier1Cities'];
$tier2Cities = $residenceLocationValues['tier2Cities'];
$tier3Cities = $residenceLocationValues['tier3Cities'];
$statesList  = $residenceLocationValues['statesList'];
?>
<?php
function printPreferredStudyLocationInputs($id,$name,$registrationHelper,$regFormId,$count,$type) {
?>	
	<li id ="SRO_SRF_<?php echo $type.$count;?>">
		<label for="<?php echo $type.$count;?>" id="L_<?php echo $type.$count;?>"><input id="<?php echo $type.$count;?>" type="checkbox" name="preferredStudyLocation[]" class="preferredStudyLocation_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocation'); ?> value="<?php echo $id; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"  onChange='queueThisLocation(this);'/>&nbsp;
			<span id ="SRF_<?php echo $type.$count;?>">
				<?php echo $name; ?>
			</span>	
		</label>
	</li>
<?php
}
?>
<script>locationType='mcities';</script>
<div id='preferredStudyLocationLayer_<?php echo $regFormId; ?>'>
<div id='preferredStudyLocationLayerInner_<?php echo $regFormId; ?>'>
<div>
	<header id="page-header" class="clearfix">
        <div class="head-group">
            <a id="preferredStudyLocationsOverlayClose" href="javascript:void(0); onclick=clearAutoSuggestor();" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>
            <h3>Preferred Study Location(s)</h3>
        </div>
    </header>

    <section class="content-wrap2 of-hide" style="margin-bottom:0">
    <div id="autoSuggestRefine" class="search-option2" style="padding-top:12px">
			<div id="searchbox2">
               <span class="icon-search"></span>
               <input id="search" type="text" placeholder="Enter location name" onkeyup="locationAutoSuggestSRF(this.value);" autocomplete="off">
               <i class="icon-cl" onClick="clearAutoSuggestor();">&times;</i>
          	</div>
		</div>
      <div class="content-child2 clearfix" style="padding:0 0.7em;">
        <section id="loc-section">
		<nav id="side-nav" class="loc-nav">
		    <ul style="margin-bottom: 45px;">
			<li style="cursor:pointer;" onClick="showHide('state');" id="stateMenu"><span>States</span></li>
			<li style="cursor:pointer;" class="active" onClick="showHide('mcities');" id="mcitiesMenu">Metro Cities</li>
			<li style="cursor:pointer;" onClick="showHide('ocities');" id="ocitiesMenu">Other Cities</li>
			<li style="cursor:pointer;" onClick="showHide('selectedbyu');" id="selectedbyuMenu">Selected By You(<strong id="count"></strong>)</li>
		    </ul>
		</nav>
                
        <ul class="location-list location-list2" id="state" style="display:none;">
		       <?php
	$i=1;
        foreach($statesList as $stateId => $stateDetails) {
                        printPreferredStudyLocationInputs('S:'.$stateId,$stateDetails['name'],$registrationHelper,$regFormId,$i,'state');
			$i++;
                }
                ?>
		 <li>
                <label for="">
                </label>
	        </li>
        </ul>
	<ul class="location-list location-list2" id="mcities">
	    <?php
		$j=1;
                foreach($tier1Cities as $list) {
                        printPreferredStudyLocationInputs('C:'.$list['cityId'],$list['cityName'],$registrationHelper,$regFormId,$j,'mcity');
			$j++;
	        }
            ?>
		<li>
                <label for="">
                </label>
                </li>
        </ul>
	<ul class="location-list location-list2" id="ocities" style="display:none;">
                        <?php
			$l = 1;
                        foreach($tier2Cities as $list) {
                                printPreferredStudyLocationInputs('C:'.$list['cityId'],$list['cityName'],$registrationHelper,$regFormId,$l,'ocity');
				$l++;
            }
                        foreach($tier3Cities as $list) {
                                printPreferredStudyLocationInputs('C:'.$list['cityId'],$list['cityName'],$registrationHelper,$regFormId,$l,'ocity');
				$l++;
            }
                        ?>
	<li>
                <label for="">
                </label>
                </li>
	</ul>
	   <ul class="location-list location-list2" id="selectedbyu" style="display:none;">
	 
        </ul>
        </section>     
	</div>
    
</section>
	<a id="doneButton" class="refine-btn box-shadow" style="" href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setPreferredStudyLocations();window.jQuery('#preferredStudyLocationsOverlayClose').click();setLocations();">
    <span class="icon-done"><i></i></span> Done</a>
</div>
</div>
</div>
<script>
jQuery(document).ready(function(){
    window.onscroll = function() { 
            jQuery('#doneButton').css({position: 'fixed', left: '0px', bottom: '0px', width:'100%'});
    }
});

function showHide(id){
	var arr=new Array("state","mcities","ocities","selectedbyu");
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
	if(total_selected_mobile==0){
		jQuery("#autoSuggestRefine").hide();}else{jQuery("#autoSuggestRefine").show();}
	}
	else{
	jQuery("#autoSuggestRefine").show();
	idSelected='';
	}
}
chkdLocationQueueArray = new Array();
var count=0;
var idSelected='';
jQuery("#count").html(count);
function queueThisLocation(elm) {
	i=0;
	jQuery("#selectedbyuMenu").show();
	if (elm.checked) {
		if(chkdLocationQueueArray.indexOf(jQuery.trim(elm.value))==-1)
			chkdLocationQueueArray.unshift(jQuery.trim(elm.value));	 
		count++;
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
		jQuery("#last").remove();
		jQuery("#selectedbyu").append('<li id="last"><label for=""></label></li>');
		  if(jQuery('#'+old_input_id).attr('checked')){
                          jQuery("#"+newId).attr('checked','true');
                }
	} else {
		//jQuery("#last").hide();
		var index = chkdLocationQueueArray.indexOf(elm.id);
        	chkdLocationQueueArray.splice(index, 1);
		count--;
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
	
	total_selected_mobile = count;
	if(total_selected_mobile==0 && idSelected=="selectedbyu"){
		jQuery("#autoSuggestRefine").hide();
	}else{
		jQuery("#autoSuggestRefine").show();
	}
	jQuery("#count").html(count);
}
if(count==0)
	    jQuery("#selectedbyuMenu").hide();
else
	    jQuery("#selectedbyuMenu").show();
	    
function setLocations(){
	window.jQuery('#preferredStudyLocation').val(chkdLocationQueueArray);
}

function removeSelectedLocationsOnRefining(){
      var w = document.getElementsByTagName('input'); 
      for(var i = 0; i < w.length; i++){ 
        if(w[i].type=='checkbox'){ 
	  if(w[i].checked)
            w[i].checked = 'false'; 
          }
	}
	window.jQuery("#selectedbyu").html('');
	total_selected_mobile=0;
	count=0;
	showHide("mcities");
	jQuery("#count").html('0');
}
function locationAutoSuggestSRF(searchedString){ 
	var showSuggestionsCount = 10;
        var highPriorityArray = new Array();
        var lowPriorityArray = new Array();
        var a=0, b=0, c=0;
        if(searchedString!=''){
                jQuery('span[id^="SRF_"]').each( function(ind) {
                        var cityName = jQuery(this).html();
                        var cityId = jQuery(this).attr('id').split('N');
                        if(cityName.toLowerCase().indexOf(searchedString.toLowerCase())===0){
                                highPriorityArray[a] = new Array();
                                highPriorityArray[a]['name'] = cityName;
                                highPriorityArray[a]['id'] = cityId;
                                a++;
                        }
                        if(cityName.toLowerCase().indexOf(searchedString.toLowerCase())>0){
                                lowPriorityArray[b] = new Array();
                                lowPriorityArray[b]['name'] = cityName;
                                lowPriorityArray[b]['id'] = cityId;
                                b++;
                        }
                });	
                jQuery("li[id*='SRO_']").hide();
        }
        else{
                jQuery("li[id*='SRO_']").show();
        }
        for(var i=0,x=showSuggestionsCount; i<highPriorityArray.length && i<x; i++)     {
                jQuery('#SRO_'+highPriorityArray[i]['id']).show();
        }
        if(i<10){
                for(var j=i,k=showSuggestionsCount; j<lowPriorityArray.length ; j++)      {
			
                        jQuery('#SRO_'+lowPriorityArray[j]['id']).show();
                }
        }
}
function clearAutoSuggestor(layerId){
	jQuery('#search').val('');
	if(layerId=='layer-list-ul')
	   locationAutoSuggest('',layerId);
	else
	   locationAutoSuggestSRF('');
}
</script>
