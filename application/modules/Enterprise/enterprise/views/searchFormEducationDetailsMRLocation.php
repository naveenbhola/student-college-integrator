<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Preferred Location:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
				<input type="hidden" id="MRLocCSV" name="MRLocCSV">
				<input type="hidden" id="MRTotalCities" name="MRTotalCities" value="<?php echo $totalCity; ?>" >
				<div><input type="text" readonly="readonly" onclick="dropdiv('mrjlocation','mrlocarea', 'mriframe2')" class="dropdownin" value="Selected Location(0)" style="width: 293px;" id="mrjlocation" name="mrjlocation"/></div>				
				<!--Start_OverlayDiv-->
				<div id="mriframe2" class="iframe" style="display:none;"><iframe style="height:310px;border: none;_width:315px;" class="modalcss1"></iframe></div>
				<div style="display:none;height:310px;width:303px;z-index:100" class="dropdiv" id="mrlocarea">
				<?php
				if(isset($isMatchedResponse)){
					echo '<div><input type="checkbox" id="selectAllMRCities" name="selextAllMRCities" onClick="selectAllMRCities();"> <b>ALL</b></div>';
				}
				
				echo '<div><input type="checkbox" id="mrcmsMetroCities" class="MRCitiesList" name="mrcmsMetroCities" onClick="mrLOCcheckAll(this);checkAllSelected();"> <b>Metropolitian Cities</b></div>';
				echo '<div id="mrcmsMetroCities_1">';
				foreach($cityList_tier1 as $list) { ?>
                             <div style="display:block;padding-left:18px"><input type="checkbox" id="<?php echo "mrCities_m".$list['cityId']; ?>" name="MRLocArr[]" value="<?php echo $list['cityId']; ?>" mrLOCCititesName="<?php echo $list['cityName']; ?>" onClick="MRLocSingleCheckBox(this)"> <?php echo $list['cityName']; ?></div>
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
									<div><input type="checkbox" id="<?php echo $list2['StateName']; ?>"  class="MRCitiesList"  name="<?php if($list2['StateName'] != 'Daman & Diu') echo $list2['StateName']; ?>" onClick="mrLOCcheckAll(this); MRLocCheckAllSelected();"> <b> <?php echo $list2['StateName']; ?></b></div>			
									<?php 
									//echo '<div><input type="checkbox" class="CurrCitiesList" id="'.$list2['StateName'].'" name="'.$list2['StateName'].'" onClick="currLOCcheckAll(this)"> <b>'.$list2['StateName'].'</b></div>';									
									echo '<div id="'.$list2['StateName'].'_1">';
									foreach($list2['cityMap'] as $list3)
									{?>
										<div style="display:block;padding-left:18px"><input type="checkbox" id="<?php echo "mrCities".$list3['CityId']; ?>" name="MRLocArr[]" value="<?php echo $list3['CityId']; ?>" mrLOCCititesName="<?php echo $list3['CityName']; ?>" onClick="MRLocSingleCheckBox(this)"> <?php echo $list3['CityName']; ?></div>
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
                    	<div class="txt_align_r" style="padding-bottom:5px">[&nbsp;<a href="#" id='mrremoveAllLink' onClick="clearAllMRLocation();return(false);" style="font-size:11px">Remove all</a>&nbsp;]</div>
                        <div id="mrcmsSearch_MRLoc">
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

var totMRLocCities='0';
var AllFlag =0
var AllTempHTML = '';
function selectAllMRCities() {
	
	if ($('selectAllMRCities').checked) {
		AllFlag =1;
		$j('.MRCitiesList').prop('checked', true);
		$j( ".MRCitiesList" ).trigger( "onclick" );
		document.getElementById('mrcmsSearch_MRLoc').innerHTML = AllTempHTML;
		AllFlag = 0;
	}else{
		$j( "#mrremoveAllLink" ).trigger( "onclick" );
		AllTempHTML = '';
	}	
}
function clearAllMRLocation()
{
	totMRLocCities='0';
    document.getElementById('mrcmsSearch_MRLoc').innerHTML="";
    document.getElementById('MRLocCSV').value = '';
	var parentObj = document.getElementById('mrlocarea');
	var childsObj=parentObj.getElementsByTagName('input');
	for(i=0; i<childsObj.length; i++){	
		document.getElementById(childsObj[i].id).checked = false;				
	}
	document.getElementById('mrjlocation').value = "Selected Location(0)";
    return false;
}
function appendMRCity(mrLOCCheckBoxId)
{	
	var selectedCityId = document.getElementById(mrLOCCheckBoxId).value;
	var selectedCityName = document.getElementById(mrLOCCheckBoxId).getAttribute('mrLOCCititesName');
	if(selectedCityId != '')
    {
        var tempHTML = document.getElementById('mrcmsSearch_MRLoc').innerHTML;
        tempHTML +=   '<a href="#" onClick="removeMRCity(this,\''+selectedCityId+'\');return false;" id="mrloc_'+selectedCityId+'">'+selectedCityName+'<img src="/public/images/cmsSearch_cross.gif" border="0" /><input type="hidden" id="hiddenMRCity_'+selectedCityId+'" name="hiddenMRCity[]" value="'+selectedCityName+'" /></a>';       
        if (AllFlag == 1) {
		AllTempHTML += tempHTML;
	}else{
		document.getElementById('mrcmsSearch_MRLoc').innerHTML = tempHTML;
	}
    }

    var cityContainer = document.getElementById(mrLOCCheckBoxId).parentNode.parentNode;
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
function removeMRCity(obj,selectedCityId)
{
    obj.parentNode.removeChild(obj);
    var elementDiv = document.getElementById('hiddenMRCity_'+selectedCityId);
    if(elementDiv)
    {
        elementDiv.parentNode.removeChild(elementDiv);
    }
    if(document.getElementById('mrCities_m'+selectedCityId) && document.getElementById('mrCities_m'+selectedCityId).checked){		
        document.getElementById('mrCities_m'+selectedCityId).checked = false;
        if(document.getElementById("mrcmsMetroCities").checked)
        {
            document.getElementById("mrcmsMetroCities").checked = false;
        }
        totMRLocCities--;
        document.getElementById('mrjlocation').value = "Selected Location("+totMRLocCities+")";
    } else {		
        document.getElementById('mrCities'+selectedCityId).checked = false;
        parentDivIntermediate = document.getElementById('mrCities'+selectedCityId).parentNode.parentNode;
        parentDivName = parentDivIntermediate.id.split("_");
        if(document.getElementById(parentDivName[0]))
        {
            document.getElementById(parentDivName[0]).checked = false;
        }
        totMRLocCities--;
        document.getElementById('mrjlocation').value = "Selected Location("+totMRLocCities+")";
    }
    if ($j('#selectAllMRCities').length) {
	if (totMRLocCities < totalCountCities) {
	    $j('#selectAllMRCities').prop('checked', false);
	}
    }
    return;
}
function mrLOCcheckAll(objCheck){
    //try{
	if(objCheck.type == 'checkbox') {
	var cname = objCheck.id+"_1";
	var par = document.getElementById(cname);	
	var childs=par.getElementsByTagName('input');
	

	if(document.getElementById(objCheck.id).checked){
		if(totMRLocCities==50 && isMatchedResponse == 0){
			document.getElementById(objCheck.id).checked = false;
			alert("Only 50 Locations can be selected at one go!");
		} else {
            for(i=0; i<childs.length; i++){
				if(document.getElementById(childs[i].id).checked != true && (totMRLocCities < 50 || isMatchedResponse == 1)){
					document.getElementById(childs[i].id).checked = true;
					appendMRCity(childs[i].id);				
					totMRLocCities = parseInt(totMRLocCities)+1;
                    if(totMRLocCities > 50 && isMatchedResponse ==0) 
                    {
                        removeMRCity(document.getElementById('mrloc_'+ childs[i].value), childs[i].value);
                        break;
                    }
                }
			}
		}
	} else {
		for(var i=0; i<childs.length; i++){
             if(childs[i].checked == true) {
                 removeMRCity(document.getElementById('mrloc_'+childs[i].value), childs[i].value);
                 childs[i].checked = false;
             }
		}
	}
	document.getElementById('mrjlocation').value = "Selected Location("+totMRLocCities+")";
	}
//    }catch(e) {
//	// alert(e);
//    }
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
            $('dim_bg').style.width = body.offsetWidth + 'px'
        }
    }
    if($('genOverlay').scrollHeight < body.offsetHeight) {
        $('genOverlay').style.left = divX + 'px';
        //$('genOverlay').style.top = divY + 'px';
    } else {
        $('genOverlay').style.left = divX + 'px';
        //$('genOverlay').style.top =  '100px';
        $('dim_bg').style.height = ($('genOverlay').scrollHeight + 100)+'px'
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
function MRLocSingleCheckBox(elementObj){
    //try{
	//console.log(elementObj);
	if(elementObj.type == 'checkbox') {
	var mrLocSingleFlag = true;

	if(totMRLocCities==50 && isMatchedResponse ==0){
		if(document.getElementById(elementObj.id).checked){
			document.getElementById(elementObj.id).checked = false;
			alert("Only 50 Locations can be selected at one go!");
			mrLocSingleFlag = false;
		}
	}
	if(mrLocSingleFlag){
		console.log(elementObj);
		if(document.getElementById(elementObj.id).checked){
			totMRLocCities = parseInt(totMRLocCities)+1;
			document.getElementById('mrjlocation').value = "Selected Location("+totMRLocCities+")";
			appendMRCity(elementObj.id);
        } else {			
            document.getElementById(elementObj.id).checked = true;
			removeMRCity(document.getElementById('mrloc_'+document.getElementById(elementObj.id).value), document.getElementById(elementObj.id).value);
		}
	}
	
	if (totMRLocCities < totalCountCities) {
		$j('#selectAllMRCities').prop('checked', false);
	}else{
		$j('#selectAllMRCities').prop('checked', true);
	}
	}
//    }catch(e){
//	console.log(e);
//    }
}

function MRLocSyncLocationsForOverlays(source, destination) {
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

function MRLocCheckAllSelected(){
	if ($j('#selectAllMRCities').length) {
		if (totMRLocCities < totalCountCities) {
			$j('#selectAllMRCities').prop('checked', false);
		}else{
			$j('#selectAllMRCities').prop('checked', true);
		}
	}
}
</script>