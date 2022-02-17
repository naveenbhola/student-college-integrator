<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Destination Country:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
                    <input type="hidden" id="prefLocCSV" name="prefLocCSV" onchange="appendPrefCountry()"/>
					<div><input type="text" readonly="readonly" onclick="dropdiv('prefjlocation','preflocarea', 'prefiframe2')" class="dropdownin" value="Selected Location(0)" style="width: 293px;" id="prefjlocation" name="prefjlocation"/></div>
					<div id="prefiframe2" class="iframe" style="display:none;"><iframe style="height:310px;border: none;_width:315px;" class="modalcss1"></iframe></div>
					<div style="display:none;height:310px;width:303px;z-index:100" class="dropdiv" id="preflocarea">
						<div>
							
								<!--div style="display:block;">
									<input type="checkbox" id="country_all" onClick="selectAllDestinationCountries(this)"/> 
									<b>All</b>
								</div-->
							
                                <?php
								
								$destinationCountryList = array();
								foreach($regions as $regionId => $region) {
									foreach($region as $countryId =>  $country) {
										$destinationCountryList[$countryId] = $country;
									}
								}
								
								uasort($destinationCountryList,function($dc1,$dc2) {
									return strcasecmp($dc1['name'],$dc2['name']);
								});
								
								foreach($destinationCountryList as $countryId => $country) {
								?>	
									<div style="display:block;padding-left:<?php echo $paddingLeft; ?>" region="<?php echo $regionId; ?>">
                                    <input type="checkbox" id="country_<?php echo $countryId; ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>$countryId))); ?>" prefLOCCititesName="<?php echo strip_tags($country['name']); ?>" onClick="prefLOCSingleCheckBox($('country_<?php echo $countryId; ?>'))"/> 
                                    <?php echo $country['name']; ?>
                                </div>
								
								<?php
								}
								
								//_p($destinationCountryList);
								
								/*
                                    $regionIdInUse = '';
                                    foreach($regions as $regionId => $region) {
                                        foreach($region as $countryId =>  $country) {
                                            $countryName = $country['name'];
                                            $regionName = $country['regionname'];
                                            $paddingLeft = '20px';
                                            if($regionId == '') {
                                                $paddingLeft = '5px';
                                                $countryName = '<b>'. $countryName .'</b>';
                                            }
                                            if($regionId !== '' && $regionId != $regionIdInUse) {
                                                $regionIdInUse = $regionId;
                                ?>
								<div style="display:block;padding-left:5px">
                                    <input type="checkbox" value="<?php echo $regionId; ?>" id="region_<?php echo $regionId; ?>" name="region_<?php echo $regionId; ?>" onClick="toggleRegionCountries($('region_<?php echo $regionId; ?>'))"/> 
                                    <b><?php echo $regionName; ?></b>
                                </div>
                                <?php
                                            }
                                ?>
								<div style="display:block;padding-left:<?php echo $paddingLeft; ?>" region="<?php echo $regionId; ?>">
                                    <input type="checkbox" id="country_<?php echo $countryId; ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>$countryId))); ?>" prefLOCCititesName="<?php echo strip_tags($countryName); ?>" onClick="prefLOCSingleCheckBox($('country_<?php echo $countryId; ?>'))"/> 
                                    <?php echo $countryName; ?>
                                </div>
                                <?php
                                        }
                                    }
                                */    
                                ?>
						</div>
						<div>
						</div>
					</div>
                </div>
                <div style="width:435px;margin-top:3px">
                	<div style="border:1px solid #e8e6e7;background:#fffbff;padding:5px">
                    	<div class="txt_align_r" style="padding-bottom:5px">[&nbsp;<a href="#" onClick="clearAllPrefLocation();return(false);" style="font-size:11px">Remove all</a>&nbsp;]</div>
                        <div id="cmsSearch_PrefLoc">
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
var totprefLocCities='0';
function clearAllPrefLocation() {
    totprefLocCities='0';
    $('cmsSearch_PrefLoc').innerHTML="";
    $('prefLocCSV').value = '';
	var parentObj = $('preflocarea');
	var childsObj=parentObj.getElementsByTagName('input');
	for(i=0; i<childsObj.length; i++){	
		$(childsObj[i].id).checked = false;				
	}
	$('prefjlocation').value = "Selected Location(0)";
    return false;
}
function appendPrefCity(prefLOCCheckBoxId) {	
	var selectedCityId = prefLOCCheckBoxId;
	var selectedCityName = document.getElementById(prefLOCCheckBoxId).getAttribute('prefLOCCititesName');
	if(selectedCityId != '') {
        var tempHTML = document.getElementById('cmsSearch_PrefLoc').innerHTML;
        tempHTML +=   '<a href="#" onClick="removePrefCity(this,\''+selectedCityId+'\');return false;" id="prefloc_'+selectedCityId+'">'+selectedCityName+'<img src="/public/images/cmsSearch_cross.gif" border="0" /><input type="hidden" id="hiddenPrefCity_'+selectedCityId+'" name="hiddenpreferedCity[]" value="'+selectedCityName+'" /></a>';       
        $('cmsSearch_PrefLoc').innerHTML = tempHTML;
        totprefLocCities = parseInt(totprefLocCities)+1;
        $('prefjlocation').value = "Selected Location("+totprefLocCities+")";
    }
	return true;
}
function removePrefCity(obj,selectedCityId) {
    if(!obj) return false;
    obj.parentNode.removeChild(obj);
    totprefLocCities =parseInt(totprefLocCities)-1;
    var elementDiv = $('hiddenPrefCity_'+selectedCityId);
    if(elementDiv) {
        elementDiv.parentNode.removeChild(elementDiv);
    }
    var country = $(selectedCityId);
    if(country){
        country.checked = false;
        //toggleRegionForCountry(country, false);
    }
    $('prefjlocation').value = "Selected Location("+totprefLocCities+")";
    return true;
}

function prefLOCSingleCheckBox(elementObj, regionEscapeFlag){
    if(elementObj.getAttribute('checkedstatus') == elementObj.checked.toString()) return false;
    elementObj.setAttribute('checkedstatus', elementObj.checked.toString());
	if(elementObj.checked && ((regionEscapeFlag == null) || (regionEscapeFlag != null && regionEscapeFlag))){
        if(parseInt(totprefLocCities) == 25) {
            elementObj.checked = false;
            alert("You can select upto 25 countries only!");
        } else {
            appendPrefCity(elementObj.id);
            //toggleRegionForCountry(elementObj, regionEscapeFlag);
        }
	} else {
		removePrefCity(document.getElementById('prefloc_'+elementObj.id), elementObj.id);
	}
    return true;
}

function selectAllDestinationCountries(cb)
{
	var cbcountries = document.getElementsByName('prefLocArr[]');
	for(var i=0;i<cbcountries.length;i++) {
		if (cb.checked) {
			cbcountries[i].checked = 'checked';
			prefLOCSingleCheckBox(cbcountries[i]);
		}
		else {
			cbcountries[i].checked = '';			
		}
	}
}

function selectSingleDestinationCountry(cb)
{
	var cbcountries = document.getElementsByName('prefLocArr[]');
	var allChecked = true;
	
	for(var i=0;i<cbcountries.length;i++) {
		if (!cbcountries[i].checked) {
			allChecked = false;
			break;
		}
	}
	
	if (allChecked) {
		document.getElementById('country_all').checked = 'checked';
	}
	else {
		document.getElementById('country_all').checked = '';
	}
}

function toggleRegionForCountry(country, escapeFlag) {
    var regionId = country.parentNode.getAttribute('region');
    if(regionId == '' ) return;
    var region = $('region_'+ regionId);
    if(escapeFlag != null) {
        region.checked = escapeFlag;
        return true;
    }
    var countryHolder = region.parentNode;
    var regionFlag = false;
    while(countryHolder = countryHolder.nextSibling) {
        if(countryHolder.nodeName.toLowerCase() != 'div') continue;
        if(countryHolder.getAttribute('region') != regionId) break;
        var countryHolderChild = countryHolder.firstChild;
        do {
             if(countryHolderChild.nodeName.toLowerCase() != 'input') continue;
             if(countryHolderChild.type == 'checkbox') {
                if(!countryHolderChild.checked) {
                    countryHolder = countryHolder.parentNode.lastChild;
                    regionFlag = false;
                    break;
                }
                regionFlag = true;
             }
        } while(countryHolderChild = countryHolderChild.nextSibling);
    }
    region.checked = regionFlag;
}

function toggleRegionCountries(region) {
    if(region.getAttribute('checkedstatus') == region.checked.toString()) return false;
    region.setAttribute('checkedstatus', region.checked.toString());
    var regionId = region.value;
    var countryHolder = region.parentNode;
    while(countryHolder = countryHolder.nextSibling) {
        if(countryHolder.nodeName.toLowerCase() != 'div') continue;
        if(countryHolder.getAttribute('region') != regionId) break;
        var countryHolderChild = countryHolder.firstChild;
        do {
             if(countryHolderChild.nodeName.toLowerCase() != 'input') continue;
             if(countryHolderChild.type == 'checkbox') { 
                countryHolderChild.checked = region.checked;
                prefLOCSingleCheckBox(countryHolderChild, region.checked);
                break;
             }
        } while(countryHolderChild = countryHolderChild.nextSibling);
    }
}

</script>
