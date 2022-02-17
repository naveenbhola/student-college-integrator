<?php
//print_r($country_state_city_list);

?>
<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Current Location:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
				<input type="hidden" id="CurLocCSV" name="CurLocCSV">
				<input type="hidden" id="totalCities" name="totalCities" value="<?php echo $totalCity; ?>" >
				<div><input type="text" readonly="readonly" onclick="dropdiv('jlocation','locarea', 'iframe2')" class="dropdownin" value="Selected Location(0)" style="width: 293px;" id="jlocation" name="jlocation"/></div>				
				<!--Start_OverlayDiv-->
				<div id="iframe2" class="iframe" style="display:none;"><iframe style="height:310px;border: none;_width:315px;" class="modalcss1"></iframe></div>
				<div style="display:none;height:310px;width:303px;z-index:100" class="dropdiv" id="locarea">
				<?php
				if(isset($isMatchedResponse)){
					echo '<div><input type="checkbox" id="selectAllCurrCities" name="selextAllCurrCities" onClick="selectAllCurrCities();"> <b>ALL</b></div>';
				}
				
				echo '<div><input type="checkbox" id="cmsMetroCities" class="CurrCitiesList" name="cmsMetroCities" onClick="currLOCcheckAll(this);checkAllSelected();"> <b>Metropolitian Cities</b></div>';
				echo '<div id="cmsMetroCities_1">';
				foreach($cityList_tier1 as $list) { ?>
                             <div style="display:block;padding-left:18px"><input type="checkbox" id="<?php echo "currCities_m".$list['cityId']; ?>" name="CurLocArr[]" value="<?php echo $list['cityId']; ?>" currLOCCititesName="<?php echo $list['cityName']; ?>" onClick="currLOCSingleCheckBox(this)"> <?php echo $list['cityName']; ?></div>
                        <?php }
						echo '</div>';		
                    ?>
					<?php
						foreach($country_state_city_list as $list)
						{
							if($list['CountryId'] == 2)
							{
							   foreach($list['stateMap'] as $list2)
							   {
								?>
									<div><input type="checkbox" id="<?php echo $list2['StateName']; ?>"  class="CurrCitiesList"  name="<?php if($list2['StateName'] != 'Daman & Diu') echo $list2['StateName']; ?>" onClick="currLOCcheckAll(this); checkAllSelected();"> <b> <?php echo $list2['StateName']; ?></b></div>			
									<?php 
									//echo '<div><input type="checkbox" class="CurrCitiesList" id="'.$list2['StateName'].'" name="'.$list2['StateName'].'" onClick="currLOCcheckAll(this)"> <b>'.$list2['StateName'].'</b></div>';									
									echo '<div id="'.$list2['StateName'].'_1">';
									foreach($list2['cityMap'] as $list3)
									{?>
										<div style="display:block;padding-left:18px"><input type="checkbox" id="<?php echo "currCities".$list3['CityId']; ?>" name="CurLocArr[]" value="<?php echo $list3['CityId']; ?>" currLOCCititesName="<?php echo $list3['CityName']; ?>" onClick="currLOCSingleCheckBox(this)"> <?php echo $list3['CityName']; ?></div>
									<?php }
									echo '</div>';
							   }
							}
						}
					?>
				</div>
				<!--End_OverlayDiv-->
				
                <div style="width:435px;margin-top:3px">
                	<div style="border:1px solid #e8e6e7;background:#fffbff;padding:5px">
                    	<div class="txt_align_r" style="padding-bottom:5px">[&nbsp;<a href="#" id='removeAllLink' onClick="clearAllCurrLocation();return(false);" style="font-size:11px">Remove all</a>&nbsp;]</div>
                        <div id="cmsSearch_CurrentLoc">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:6px">&nbsp;</div>
<script>
	var isMatchedResponse =0;
<?php if(isset($isMatchedResponse)){ ?>
	isMatchedResponse =1;
 <?php }
 if(isset($totalCity)){
 ?>
 var totalCountCities = <?php echo $totalCity; ?>;
<?php }else{ ?>
var totalCountCities = 0;
<?php } ?>

var totCurLocCities='0';
var AllFlag =0
var AllTempHTML = '';
function selectAllCurrCities() {
	
	if ($('selectAllCurrCities').checked) {
		AllFlag =1;
		$j('.CurrCitiesList').prop('checked', true);
		$j( ".CurrCitiesList" ).trigger( "onclick" );
		document.getElementById('cmsSearch_CurrentLoc').innerHTML = AllTempHTML;
		AllFlag = 0;
	}else{
		$j( "#removeAllLink" ).trigger( "onclick" );
		AllTempHTML = '';
	}	
}
function clearAllCurrLocation()
{
	totCurLocCities='0';
    document.getElementById('cmsSearch_CurrentLoc').innerHTML="";
    document.getElementById('CurLocCSV').value = '';
	var parentObj = document.getElementById('locarea');
	var childsObj=parentObj.getElementsByTagName('input');
	for(i=0; i<childsObj.length; i++){	
		document.getElementById(childsObj[i].id).checked = false;				
	}
	document.getElementById('jlocation').value = "Selected Location(0)";
    return false;
}
function appendCurrCity(curLOCCheckBoxId)
{	
	var selectedCityId = document.getElementById(curLOCCheckBoxId).value;
	var selectedCityName = document.getElementById(curLOCCheckBoxId).getAttribute('currLOCCititesName');
	if(selectedCityId != '')
    {
        var tempHTML = document.getElementById('cmsSearch_CurrentLoc').innerHTML;
        tempHTML +=   '<a href="#" onClick="removeCurCity(this,\''+selectedCityId+'\');return false;" id="currloc_'+selectedCityId+'">'+selectedCityName+'<img src="/public/images/cmsSearch_cross.gif" border="0" /><input type="hidden" id="hiddenCurrCity_'+selectedCityId+'" name="hiddenCurrentCity[]" value="'+selectedCityName+'" /></a>';
        // tempHTML +=   '<a href="#" onClick="removeCurCity(this,\''+selectedCityId+'\');return false;" id="currloc_'+selectedCityId+'">'+selectedCityName+'<img src="/public/images/cmsSearch_cross.gif" border="0" /></a>';

        if (AllFlag == 1) {
		AllTempHTML += tempHTML;
	}else{
		document.getElementById('cmsSearch_CurrentLoc').innerHTML = tempHTML;
	}
    }

    var cityContainer = document.getElementById(curLOCCheckBoxId).parentNode.parentNode;
    var cities = cityContainer.getElementsByTagName('input');
    var cityIndex = 0;
    document.getElementById(cityContainer.id.replace('_1','')).checked = true;
    var city;
    while(city = cities[cityIndex++]) {
        if(city.type == 'checkbox' && city.checked == false) {
            document.getElementById(cityContainer.id.replace('_1','')).checked = false;
            break;
        }
    }
	return;
}
function removeCurCity(obj,selectedCityId)
{
    obj.parentNode.removeChild(obj);
    var elementDiv = document.getElementById('hiddenCurrCity_'+selectedCityId);
    if(elementDiv)
    {
        elementDiv.parentNode.removeChild(elementDiv);
    }
    if(document.getElementById('currCities_m'+selectedCityId) && document.getElementById('currCities_m'+selectedCityId).checked){		
        document.getElementById('currCities_m'+selectedCityId).checked = false;
        if(document.getElementById("cmsMetroCities").checked)
        {
            document.getElementById("cmsMetroCities").checked = false;
        }
        totCurLocCities--;
        document.getElementById('jlocation').value = "Selected Location("+totCurLocCities+")";
    } else {		
        document.getElementById('currCities'+selectedCityId).checked = false;
        parentDivIntermediate = document.getElementById('currCities'+selectedCityId).parentNode.parentNode;
        parentDivName = parentDivIntermediate.id.split("_");
        if(document.getElementById(parentDivName[0]))
        {
            document.getElementById(parentDivName[0]).checked = false;
        }
        totCurLocCities--;
        document.getElementById('jlocation').value = "Selected Location("+totCurLocCities+")";
    }
    if ($j('#selectAllCurrCities').length) {
	if (totCurLocCities < totalCountCities) {
	    $j('#selectAllCurrCities').prop('checked', false);
	}
    }
    return;
}
function currLOCcheckAll(objCheck){
    try{
	var cname = objCheck.id+"_1";
	var par = document.getElementById(cname);	
	var childs=par.getElementsByTagName('input');
	

	if(document.getElementById(objCheck.id).checked){
		if(totCurLocCities==50 && isMatchedResponse == 0){
			document.getElementById(objCheck.id).checked = false;
			alert("Only 50 Locations can be selected at one go!");
		} else {
            for(i=0; i<childs.length; i++){
				if(document.getElementById(childs[i].id).checked != true && (totCurLocCities < 50 || isMatchedResponse == 1)){
					document.getElementById(childs[i].id).checked = true;
					appendCurrCity(childs[i].id);				
					totCurLocCities = parseInt(totCurLocCities)+1;
                    if(totCurLocCities > 50 && isMatchedResponse ==0) 
                    {
                        removeCurCity(document.getElementById('currloc_'+ childs[i].value), childs[i].value);
                        break;
                    }
                }
			}
		}
	} else {
		for(var i=0; i<childs.length; i++){
             if(childs[i].checked == true) {
                 removeCurCity(document.getElementById('currloc_'+childs[i].value), childs[i].value);
                 childs[i].checked = false;
             }
		}
	}
	document.getElementById('jlocation').value = "Selected Location("+totCurLocCities+")";
    }catch(e) {
	// alert(e);
    }
}
function showLDBLocationOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent, modalLess, left, top) {
    if(trim(overlayContent) == '')
	return false;
    var body = document.getElementsByTagName('body')[0];
    $('overlayTitle').innerHTML = overlayTitle;
    if(trim(overlayTitle) == '') {
        $('overlayTitle').parentNode.style.display = 'none';
    } else {
        $('overlayTitle').parentNode.style.display = '';
    }
    $('genOverlay').style.width = overlayWidth + 'px';
    $('genOverlay').style.height = overlayHeight + 'px';
    $('genOverlayContents').innerHTML = overlayContent;
	// divX = document.body.offsetWidth/2 - 150;
    
	var divY = parseInt(screen.height)/2;
    var divX;
    if(typeof left != 'undefined') {
        divX = left;
    } else {
        divX = (parseInt(body.offsetWidth)/2) - (overlayWidth/2);
    }

    if(typeof top != 'undefined') {
        divY = top;
    } else {
        divY = parseInt(divY - parseInt(overlayHeight/2)) - 70;
    }
    h = document.body.scrollTop;
	var  h1 = document.documentElement.scrollTop;
    h = h1 > h ? h1 : h;
    divY = divY + h;
    if(typeof modalLess == 'undefined' || modalLess === false ) {
        $('dim_bg').style.height = body.scrollHeight + 'px';
        $('dim_bg').style.display = 'inline';
        if($('dim_bg').offsetWidth < body.offsetWidth) {
            $('dim_bg').style.width = body.offsetWidth + 'px';
        }
    }
    if($('genOverlay').scrollHeight < body.offsetHeight) {
        $('genOverlay').style.left = divX + 'px';
        //$('genOverlay').style.top = divY + 'px';
    } else {
        $('genOverlay').style.left = divX + 'px';
        //$('genOverlay').style.top =  '100px';
        $('dim_bg').style.height = ($('genOverlay').scrollHeight + 100)+'px';
        //window.scrollTo(divX,'100');
    }
    overlayHackLayerForIE('genOverlay', body);
	$('overlayCloseCross').className = 'cssSprite1 allShikCloseBtn';
    $('genOverlay').style.display = 'inline';
}
var currentlyShowing = null;
function dropdiv(textBoxId, divOverlayId, iframeId){
    objTextBoxId =  document.getElementById(textBoxId);
    objDivOverlayId =  document.getElementById(divOverlayId);
    //objIframeId =  document.getElementById(iframeId);
    if(document.getElementById('genOverlay').style.display !="inline" && currentlyShowing != divOverlayId){
        attachOutMouseClickEventForPage(document.getElementById('genOverlay'),'hideLocationLayerOverlay()');
        showLDBLocationOverlay(objTextBoxId.offsetWidth ,parseInt(objDivOverlayId.style.height),'',objDivOverlayId.innerHTML,true,obtainPostitionX(objTextBoxId)- 13,obtainPostitionY(objTextBoxId) - document.documentElement.scrollTop + 16);
		layerTopPos = obtainPostitionY(objTextBoxId)+18;
		$('genOverlay').style.top = layerTopPos + 'px';
        overlayHackLayerForIE('genOverlay',document.getElementById('genOverlayContents'));


        document.getElementById('genOverlayContents').innerHTML = '';;
        for(var contentNode, childCount=0; contentNode = objDivOverlayId.childNodes[childCount++];) {
            try{
                var contentNodeC =contentNode.cloneNode(true);
                document.getElementById('genOverlayContents').appendChild(contentNodeC);
            } catch(e) {}
        }
        syncLocationsForOverlays(document.getElementById(divOverlayId) , document.getElementById('genOverlayContents'));
        document.getElementById('genOverlayContents').scrollTop = 0;
        document.getElementById('genOverlayContents').style.border = '1px solid';
		document.getElementById('genOverlayContents').style.marginTop= '0px';
		document.getElementById('genOverlayContents').style.position= 'relative';
		document.getElementById('genOverlayContents').style.top= '-10px';
		document.getElementById('genOverlayContents').style.background= '#FFF';
        document.getElementById('genOverlayContents').style.height = (objDivOverlayId.style.height);
        document.getElementById('genOverlayContents').style.overflow= 'auto';
        currentlyShowing = divOverlayId;
    } else {
        hideLocationLayerOverlay();
    }
    /* HACK for IE :) */

    try {
        $("genOverlayTitleCross_hack").style.display = "none";
        $("genOverlayHolderDiv").style.border = 0;
    }catch(e) {}
}
function hideLocationLayerOverlay() {
        if(document.getElementById(currentlyShowing))
        document.getElementById(currentlyShowing).innerHTML = '';
        for(var contentNode, childCount=0; contentNode = document.getElementById('genOverlayContents').childNodes[childCount++];) {
            try{
            var contentNodeC =contentNode.cloneNode(true);
            document.getElementById(currentlyShowing).appendChild(contentNodeC);
            } catch(e) { alert(e); }
        }
        syncLocationsForOverlays(document.getElementById('genOverlayContents'), document.getElementById(currentlyShowing));
        currentlyShowing = '';
        hideOverlay();
}
function currLOCSingleCheckBox(elementObj){
    try{
	var curLocSingleFlag = true;
	if(totCurLocCities==50 && isMatchedResponse ==0){
		if(document.getElementById(elementObj.id).checked){
			document.getElementById(elementObj.id).checked = false;
			alert("Only 50 Locations can be selected at one go!");
			curLocSingleFlag = false;
		}
	}
	if(curLocSingleFlag){
		if(document.getElementById(elementObj.id).checked){
			totCurLocCities = parseInt(totCurLocCities)+1;
			document.getElementById('jlocation').value = "Selected Location("+totCurLocCities+")";
			appendCurrCity(elementObj.id);
        } else {			
            document.getElementById(elementObj.id).checked = true;
			removeCurCity(document.getElementById('currloc_'+document.getElementById(elementObj.id).value), document.getElementById(elementObj.id).value);
		}
	}
	
	if (totCurLocCities < totalCountCities) {
		$j('#selectAllCurrCities').prop('checked', false);
	}else{
		$j('#selectAllCurrCities').prop('checked', true);
	}
	
	
    }catch(e){
	// alert(e);
    }
}


function syncLocationsForOverlays(source, destination) {
    var sourceNodes = source.getElementsByTagName('input');
    var destinationNodes = destination.getElementsByTagName('input');
    for(var i=0; i< sourceNodes.length;i++)
    {
        if(sourceNodes[i].checked)
        {
            if(sourceNodes[i].id=destinationNodes[i].id)
            {
                if(!(destinationNodes[i].checked))
                {
                    destinationNodes[i].checked = true;
                }
            }
        }
    }

}

function checkAllSelected(){
	if ($j('#selectAllCurrCities').length) {
		if (totCurLocCities < totalCountCities) {
			$j('#selectAllCurrCities').prop('checked', false);
		}else{
			$j('#selectAllCurrCities').prop('checked', true);
		}
	}
}
</script>
