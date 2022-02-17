<?php $mouseover = "this.style.display=''; overlayHackLayerForIE('locationlayer', document.getElementById('locationlayer'));" ;
$mouseout = "dissolveOverlayHackForIE();this.style.display='none'";
$jsCheckStmts = array();
?>
<div id = 'userPreferenceCategoryCity' style = 'display:none;z-index:1000000;position:absolute;width:570px;left:300px;top:155px;' onmouseover="<?php echo $mouseover;?>" onmouseout="<?php echo $mouseout;?>">
    <div style="width:100%;background:#FFF">
        <div style="border:0px solid #c3c5c4">
            <div style="height:313px">
                 <div style="width:100%;padding-top:10px">
                    <div class="float_L" style="width:200px">
                        <div style="padding-left:10px">
                            <div><b>States - Anywhere In:</b></div>
                            <div style="height:227px;overflow:auto;">
                <?php 
                $userarray = json_decode($userDataToShow,true);
                foreach($statesList as $stateId => $stateDetails) {
                    $key = '';
                    if(isset($userarray['statearray']))
                    {
                        $key = array_search($stateId,$userarray['statearray']);
                    }
                    $stateName = $stateDetails['name'];
                    $stateValue = $stateDetails['value'];
                    ?>
                        <input type="checkbox" id="location_state_<?php echo $stateId; ?>"  name="locationPref[]" value="<?php echo $stateValue; ?>" tag="<?php echo $stateName; ?>"/>
                        <span style="font-size: 12px; color: rgb(0, 102, 221);"><?php echo $stateName; ?></span><br />
			<?php
			if(trim($key) != '') {
					$jsCheckStmts[] = '$("location_state_'. $stateId .'").checked = true; finalArr[$("location_state_'. $stateId .'").value] = 1;';
			}
                         } ?>
                            </div>
                        </div>
                    </div>

                    <div class="float_L" style="width:125px">
                        <div style="padding-left:10px">
                            <div><b>Metro Cities</b></div>
                            <div style="line-height:20px;padding-left:10px">
                            <?php
                            foreach($cityTier1 as $list) {
                            $cityId = $list['cityId'];
                            $stateId = $list['stateId'];
                            $cityValue = "2:". $stateId .":".$list['cityId'];
                            $cityName = $list['cityName'];
                            $key = '';
                            if(isset($userarray['cityarray']))
                            {
                                $key = array_search($cityId,$userarray['cityarray']);
                            }
                            ?>
                    <input type="checkbox" id="location_city_<?php echo $cityId; ?>"  name="locationPref[]" value="<?php echo $cityValue; ?>" tag="<?php echo $cityName; ?>"/>
                                    <span style="font-size: 12px; color: rgb(0, 102, 221);"><?php echo $cityName; ?></span><br />
			<?php
			if(trim($key) != '') {
					$jsCheckStmts[] = '$("location_city_'. $cityId .'").checked = true; finalArr[$("location_city_'. $cityId .'").value] = 1;';
			}
                                    } ?>
                            </div>
                            <div class="grayLine_1" style="margin:15px 40px 7px 0">&nbsp;</div>
                            <div style="display:none"><a href="#" onClick = "document.getElementById('catcityid').value = 'All';opencatpage(1);return false;"><b>All Cities</b></a></div>
                        </div>
                    </div>
                    <div class="float_L" style="width:225px">
                        <div style="padding-left:10px">
                            <div style="padding-left:17px"><b>Other Cities</b></div>
                            <div style="line-height:20px;padding-left:27px;border-left:1px solid #e2e2e2">
                                <div style="height:227px;overflow:auto">
                            <?php foreach($citiesList['tier2'] as $cityId => $cityDetails) {
                    $key = '';
                    if(isset($userarray['cityarray']))
                    {
                    $key = array_search($cityId,$userarray['cityarray']);
                    }
                    $cityName = $cityDetails['name'];
                    $cityValue = $cityDetails['value'];
                ?>
                    <input type="checkbox" id="location_city_<?php echo $cityId; ?>" name="locationPref[]"  value="<?php echo $cityValue; ?>" tag="<?php echo $cityName; ?>"/>
                                    <span style="font-size: 12px; color: rgb(0, 102, 221);"><?php echo $cityName; ?></span><br />
				    <?php 
				    if(trim($key) != '') {
					$jsCheckStmts[] = '$("location_city_'. $cityId .'").checked = true; finalArr[$("location_city_'. $cityId .'").value] = 1;';
			}
                                    } ?>
                            <?php foreach($citiesList['tier3'] as $cityId => $cityDetails) {
                    $cityName = $cityDetails['name'];
                    $cityValue = $cityDetails['value'];
                ?>
                    <input type="checkbox" id="location_city_<?php echo $cityId; ?>"  name="locationPref[]" value="<?php echo $cityValue; ?>" tag="<?php echo $cityName; ?>"/>
                                    <span style="font-size: 12px; color: rgb(0, 102, 221);"><?php echo $cityName; ?></span><br />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineSpace_10" style="clear:both">&nbsp;</div>
                    <div style="line-height:1px;font-size:1px;overflow:hidden;background:#c4c4c6;height:1px;margin:0 5px">&nbsp;</div>
                    <div class="lineSpace_10">&nbsp;</div>
                    <div align="center"><input type="button" class="okBtn" onClick="getDataFromCityLayer();" value="OK" /></div>
                    
                 </div>
            </div>
            <!--End_OverlayContent-->
        </div>
    </div>
</div>
<?php
$count = 0;
if ((isset($displayname)) && !empty($displayname)){
if(isset($userarray['cityarray']))                   
$count = count($userarray['cityarray']);
if(isset($userarray['statearray']))
$count = $count + count($userarray['statearray']);
}
?>
<script>
//abc($('marketingPreferedCity').parentNode);
    var finalArr = new Array();
<?php echo implode('',$jsCheckStmts); ?>
    if(isUserLoggedIn)
    {
    document.getElementById('marketingPreferedCity').innerHTML= "&nbsp;Selected ("+<?php echo $count?>+")";
    }
    function putOnTheOldData() {
        var inputElems = document.getElementsByTagName('input');
        for(var inputElemsCount =0,inputElem; inputElem = inputElems[inputElemsCount++];) {
            if(inputElem.type == 'checkbox'){
                if(inputElem.value != 'on') {
                    if(finalArr[inputElem.value] == 1) {
                        inputElem.checked=true;
                    }
                }
            }
        }
        document.getElementById('userPreferenceCategoryCity').innerHTML = "";
    }
    function getDataFromCityLayer() {
        document.getElementById('userPreferenceCategoryCity').innerHTML = document.getElementById('genOverlayContents').innerHTML; 
        var hiddenVar = document.getElementById('mCityList');
        var hiddenVarCityNam = document.getElementById('mCityListName');
        var inputElems = document.getElementsByTagName('input');
        var cityNames="";
        var numCity = 0;
        hiddenVar.value  ="";
        //alert("dfdfdfdfdf"+hiddenVar.value);
        finalArr = new Array();
        for(var inputElemsCount =0,inputElem; inputElem = inputElems[inputElemsCount++];) {
            if(inputElem.type == 'checkbox' && inputElem.checked && inputElem.name == 'locationPref[]'){
                if(inputElem.value != 'on') {
                    if(finalArr[inputElem.value] == 1) {
                        continue;
                    }
                    hiddenVar.value += inputElem.value + ',';
                    //alert("in loop"+hiddenVar.value);
                    finalArr[inputElem.value] = 1;
                    if(inputElem.getAttribute('tag') != null) {
                        cityNames += inputElem.getAttribute('tag')+',';
                        numCity++;
                    }
                }
            }
        }
        hiddenVarCityNam.value = cityNames;
        document.getElementById('marketingPreferedCity').innerHTML= "&nbsp;Selected ("+numCity+")"; 
        document.getElementById('marketingPreferedCity').style.display="inline";
        var flag1 = true;
        var mCityListVal = document.getElementById('mCityList').value;
        if(trim(mCityListVal) == "") {
            flag1 = false;
            document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").innerHTML = "Please select preferred study location(s).";
            document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").parentNode.style.display = "inline";
            document.getElementById("marketingPreferedCity").innerHTML= "&nbsp;&nbsp;Select";
        } else {
            document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").innerHTML = "";
            document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").parentNode.style.display = "none";
        }

        hideOverlayMarketingPage();
        document.getElementById('overlayCloseCross').style.display= "";
        document.getElementById('genOverlayHolderDiv').style.display='';
        openCityOverLay = false;
        return;
    }
</script>
