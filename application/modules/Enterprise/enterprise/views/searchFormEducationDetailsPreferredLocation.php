<div style="width:100%">
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">&nbsp;</div>
            </div>
        </div>
	<?php /*
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
                <div style="margin:7px 0">
                    <input type="radio" id="prefLocAndClause" name="prefLocClause" value="and"/> <b>AND</b>
                    <input type="radio" id="prefLocOrClause" name="prefLocClause" value="or"/> <b>OR</b>
                </div>
            </div>
        </div>
        */ ?>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
</div>
<div style="line-height:6px">&nbsp;</div>
<?php /* 
<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Preferred Location:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
                    <input type="hidden" id="prefLocCSV" name="prefLocCSV" onchange="appendPrefCity()">
					<div><input type="text" readonly="readonly" onclick="dropdiv('prefjlocation','preflocarea', 'prefiframe2')" class="dropdownin" value="Selected Location(0)" style="width: 293px;" id="prefjlocation" name="prefjlocation"/></div>
					<div id="prefiframe2" class="iframe" style="display:none;"><iframe style="height:310px;border: none;_width:315px;" class="modalcss1"></iframe></div>
					<div style="display:none;height:310px;width:303px;z-index:100" class="dropdiv" id="preflocarea">
						<div>
						<?php							
                            foreach($country_state_city_list as $list)
							{							
                            ?>
								<div style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>2))); ?>" prefLOCCititesName="<?php echo "Anywhere in ".$list['CountryName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo "Anywhere in ".$list['CountryName']; ?></div>
                                <?
                                if($list['CountryId'] == 2)
                                {
                                   foreach($list['stateMap'] as $list2)
                                   {
                                   ?>
                                       <div style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>$list2['StateId'],'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>$list2['StateId'],'countryId'=>2))); ?>" prefLOCCititesName="<?php echo "Anywhere in ".$list2['StateName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo "Anywhere in ".$list2['StateName']; ?></div>
                                   <? 
                                   }
                                }
                            }
                            $metroCityIdArray = array();
                            foreach($cityList_tier1 as $list) { ?>
                                <div style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>$list['cityId'],'stateId'=>$list['stateId'],'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>$list['cityId'],'stateId'=>$list['stateId'],'countryId'=>2))); ?>" prefLOCCititesName="<?php echo $list['cityName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo $list['cityName']; ?></div>
                           <?php 
                           array_push($metroCityIdArray,$list['cityId']);
                           }
                        ?>
						</div>
						<div>
						<?php							
                            foreach($country_state_city_list as $list)
                            {							
                            ?>
                                <!--<label style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>0,'stateId'=>0,'countryId'=>2))); ?>" prefLOCCititesName="<?php echo "Anywhere in ".$list['CountryName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo "Anywhere in ".$list['CountryName']; ?></label> -->
                                <?
                                if($list['CountryId'] == 2)
                                {
                                   foreach($list['stateMap'] as $list2)
                                   {
                                       foreach($list2['cityMap'] as $list3)
                                       {
                                           if($list3['Tier'] =='1' || $list3['Tier'] =='2')
                                           {
                                                if(!in_array($list3['CityId'],$metroCityIdArray))
                                                {
                                               ?>
                                                   <div style="display:block;padding-left:5px"><input type="checkbox" id="<?php echo base64_encode(json_encode(array('cityId'=>$list3['CityId'],'stateId'=>$list2['StateId'],'countryId'=>2))); ?>" name="prefLocArr[]" value="<?php echo base64_encode(json_encode(array('cityId'=>$list3['CityId'],'stateId'=>$list2['StateId'],'countryId'=>2))); ?>" prefLOCCititesName="<?php echo $list3['CityName']; ?>" onClick="prefLOCSingleCheckBox(this)"> <?php echo $list3['CityName']; ?></div>
                                                   <?
                                                }
                                           }
                                       }
                                   }
                                }
                            }							
                        ?>
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
       
        */ ?>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div> 
    <!--</div>                
</div>-->
 <div style="line-height:6px">&nbsp;</div>

<script>
var totprefLocCities='0';
function clearAllPrefLocation()
{
    totprefLocCities='0';
    document.getElementById('cmsSearch_PrefLoc').innerHTML="";
    document.getElementById('prefLocCSV').value = '';
	var parentObj = document.getElementById('preflocarea');
	var childsObj=parentObj.getElementsByTagName('input');
	for(i=0; i<childsObj.length; i++){	
		document.getElementById(childsObj[i].id).checked = false;				
	}
	document.getElementById('prefjlocation').value = "Selected Location(0)";
    return false;
}
function appendPrefCity(prefLOCCheckBoxId)
{	
	var selectedCityId = document.getElementById(prefLOCCheckBoxId).value;
	var selectedCityName = document.getElementById(prefLOCCheckBoxId).getAttribute('prefLOCCititesName');
	if(selectedCityId != '')
    {
        var tempHTML = document.getElementById('cmsSearch_PrefLoc').innerHTML;
        tempHTML +=   '<a href="#" onClick="removePrefCity(this,\''+selectedCityId+'\');return false;" id="prefloc_'+selectedCityId+'">'+selectedCityName+'<img src="/public/images/cmsSearch_cross.gif" border="0" /><input type="hidden" id="hiddenPrefCity_'+selectedCityId+'" name="hiddenpreferedCity[]" value="'+selectedCityName+'" /></a>';       
        document.getElementById('cmsSearch_PrefLoc').innerHTML = tempHTML;
    }
	return;
}
function removePrefCity(obj,selectedCityId)
{
	obj.parentNode.removeChild(obj);
    var elementDiv = document.getElementById('hiddenPrefCity_'+selectedCityId);
    if(elementDiv)
    {
        elementDiv.parentNode.removeChild(elementDiv);
    }
	if(document.getElementById(selectedCityId)){		
		document.getElementById(selectedCityId).checked = false;
		totprefLocCities =parseInt(totprefLocCities)-1;
		document.getElementById('prefjlocation').value = "Selected Location("+totprefLocCities+")";
	}
   return false;
}
function prefLOCSingleCheckBox(elementObj){
    try{
	if(document.getElementById(elementObj.id).checked){
        if(parseInt(totprefLocCities) == 50)
        {
            document.getElementById(elementObj.id).checked = false;
            alert("You can select upto 50 cities only!");
            return false;
        }
        else
        {
            totprefLocCities = parseInt(totprefLocCities)+1;
            document.getElementById('prefjlocation').value = "Selected Location("+totprefLocCities+")";
            appendPrefCity(elementObj.id);
        }
	} else {
		totprefLocCities = parseInt(totprefLocCities)-1;
		document.getElementById('prefjlocation').value = "Selected Location("+totprefLocCities+")";
		removePrefCity(document.getElementById('prefloc_'+document.getElementById(elementObj.id).value, document.getElementById(elementObj.id).value));		
	}
    }catch(e) {
	// alert(e);
    }
}
</script>
