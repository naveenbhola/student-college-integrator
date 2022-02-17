<?php $mouseover = "this.style.display=''; overlayHackLayerForIE('locationlayer', document.getElementById('locationlayer'));" ;
$mouseout = "dissolveOverlayHackForIE();this.style.display='none'";
$jsCheckStmts = array();
?>
<div id = 'userPreferenceCategoryCity_homepageregistration' style = 'display:none;z-index:1000000;position:absolute;width:570px;left:300px;top:155px;' onmouseover="<?php echo $mouseover;?>" onmouseout="<?php echo $mouseout;?>">
	<div class="city-layer-main">
	<div class="city-layer-col">
		<strong>States - Anywhere In:</strong>
        <ul>
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
                    	<li>
                        <input type="checkbox" id="homepage_location_state_<?php echo $stateId; ?>"  name="locationPref[]" value="<?php echo $stateValue; ?>" tag="<?php echo $stateName; ?>"/>
                        <span><?php echo $stateName; ?></span>
                        </li>
			<?php
			if(trim($key) != '') {
					$jsCheckStmts[] = '$("location_state_'. $stateId .'").checked = true; finalArr[$("location_state_'. $stateId .'").value] = 1;';
			}
                         } ?>
                            </ul>
	</div>

	<div class="city-layer-col" style="width:110px;">
		<strong>Metro Cities</strong>
        <ul>
        	
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
                            <li>
                    <input type="checkbox" id="homepage_location_city_<?php echo $cityId; ?>"  name="locationPref[]" value="<?php echo $cityValue; ?>" tag="<?php echo $cityName; ?>"/>
                                    <span><?php echo $cityName; ?></span>
                                    </li>
			<?php
			if(trim($key) != '') {
					$jsCheckStmts[] = '$("location_city_'. $cityId .'").checked = true; finalArr[$("location_city_'. $cityId .'").value] = 1;';
			}
                                    } ?>
                            
			<div class="grayLine_1">&nbsp;</div>
            <div style="display:none"><a href="#" onClick = "document.getElementById('catcityid').value = 'All';opencatpage(1);return false;"><b>All Cities</b></a></div>
        </ul>
     </div>
	
    <div class="city-layer-col city-layer-col-last">
    	<strong>Other Cities</strong>
        <ul>
        	<?php foreach($citiesList['tier2'] as $cityId => $cityDetails) {
                    $key = '';
                    if(isset($userarray['cityarray']))
                    {
                    $key = array_search($cityId,$userarray['cityarray']);
                    }
                    $cityName = $cityDetails['name'];
                    $cityValue = $cityDetails['value'];
                ?>
                	<li>
                    <input type="checkbox" id="homepage_location_city_<?php echo $cityId; ?>" name="locationPref[]"  value="<?php echo $cityValue; ?>" tag="<?php echo $cityName; ?>"/>
                                    <span><?php echo $cityName; ?></span></li>
				    <?php 
				    if(trim($key) != '') {
					$jsCheckStmts[] = '$("location_city_'. $cityId .'").checked = true; finalArr[$("location_city_'. $cityId .'").value] = 1;';
			}
                                    } ?>
                            <?php foreach($citiesList['tier3'] as $cityId => $cityDetails) {
                    $cityName = $cityDetails['name'];
                    $cityValue = $cityDetails['value'];
                ?><li>
                    <input type="checkbox" id="homepage_location_city_<?php echo $cityId; ?>"  name="locationPref[]" value="<?php echo $cityValue; ?>" tag="<?php echo $cityName; ?>"/>
                    <span><?php echo $cityName; ?></span>
                    </li>
                                    <?php } ?>
                                
		</ul>
    </div>
    
    <div class="spacer10 clearFix"></div>
	<div align="center" class="city-layer-btn"><input type="button" class="orange-button" onClick="getDataFromCityLayer();" value="OK" title="OK" /></div>
    </div>
    <!--End_OverlayContent-->
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
    var finalArr = new Array();
    function getDataFromCityLayer() {
        document.getElementById('userPreferenceCategoryCity_homepageregistration').innerHTML = document.getElementById('genOverlayContents').innerHTML; 
        var hiddenVar = document.getElementById('mCityList');
        var hiddenVarCityNam = document.getElementById('mCityListName');
        var inputElems = document.getElementsByTagName('input');
        var cityNames="";
        var numCity = 0;
        hiddenVar.value  ="";
        //alert("dfdfdfdfdf"+hiddenVar.value);
        finalArr = new Array();
        for(var inputElemsCount =0,inputElem; inputElem = inputElems[inputElemsCount++];) {
            if(inputElem.type == 'checkbox' && inputElem.checked && inputElem.name == 'locationPref[]' && inputElem.id.indexOf('homepage') != -1){
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
            document.getElementById("marketingPreferedCity").innerHTML= "Preferred Study Location(s)";
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
