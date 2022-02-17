
<?php
if(!$city_cookie_set)
{
    $mouseover = '';
    $mouseout = '';
}
else
{
    $mouseover = "this.style.display=''; overlayHackLayerForIE('testprep_locationlayer', document.getElementById('testprep_locationlayer'));" ;
    $mouseout = "dissolveOverlayHackForIE();this.style.display='none'";
}
?>
<style>
#wrapperMainForCompleteShiksha .styles{background:#ff8200 url(/public/images/dotAnimation.gif) no-repeat right top; padding:1px 25px 1px 3px;color:#fff;}
</style>
<div id = 'testprep_locationlayer' style = 'display:none;z-index:1000000;position:absolute;width:390px;left:417px;top:155px;' onmouseover="<?php echo $mouseover;?>" onmouseout="<?php echo $mouseout;?>">
    <div style="width:100%;background:#FFF">
        <div style="border:1px solid #c3c5c4">
            <!--Start_OverlayTitle-->
            <div style="background:#6391cc;line-height:35px">
                <span class="Fnt16 whiteColor" style="padding-left:10px"><b>Please Choose Location to proceed</b></span>
            </div>
            <!--End_OverlayTitle-->
            <!--Start_OverlayContent-->
            <div style="height:390px" id = "maindivid">
                 <div style="width:100%;padding-top:10px">
                    <div class="float_L" style="width:180px">
                        <div style="padding-left:10px">
                            <!--div class="Fnt14" style="padding-bottom:6px"><b>India</b></div-->
                            <div>Metro Cities</div>
                            <div style="line-height:20px;padding-left:10px">
                            <?php for($i = 0;$i<count($cityTier1);$i++) {?>
<!--
<a href="#"
   title = "<?php echo $cityTier1[$i]['cityName']?>"
   onClick = "this.className = 'styles';document.getElementById('catcity').value = '<?php echo str_replace('/','-',$cityTier1[$i]['cityName'])?>';document.getElementById('catcityid').value = '<?php echo $cityTier1[$i]['cityId'];?>';document.getElementById('catcourselevel').value = 'All';document.getElementById('catcourselevel1').value='All';document.getElementById('catcoursetype').value='All';opencatpage(1);return false;"><?php echo $cityTier1[$i]['cityName']?></a>
-->
                <a href="/shiksha/change_location/<?php echo $params['blog_id']?>/<?php echo $cityTier1[$i]['cityId'] ?>"><?php echo $cityTier1[$i]['cityName']?></a>
<br />
                                    <?php } ?>
                            </div>
                            <div class="grayLine_1" style="margin:15px 40px 7px 0">&nbsp;</div>
                            <div><a href="/shiksha/change_location/<?php echo $params['blog_id']?>/-1"><b>All Cities</b></a></div>
                        </div>
                    </div>
                    <div class="float_L" style="width:200px">
                        <div style="padding-left:10px">
                            
                            <div style="padding-left:17px">Non Metro Cities</div>
                            <div style="line-height:20px;padding-left:27px;border-left:1px solid #e2e2e2">
                                <div style="height:305px;overflow:auto;display:none" id = "cityli1">
                                </div>
                                <div style="height:305px;overflow:auto" id = "cityli">
                                <?php for($i = 0;$i<count($cityTier2);$i++) {?>
                                    <!--
                                    <a href="#" title = "<?php echo $cityTier2[$i]['cityName'];?>" onClick = "document.getElementById('catcity').value = '<?php echo $cityTier2[$i]['cityName'];?>';document.getElementById('catcityid').value = '<?php echo $cityTier2[$i]['cityId']?>';document.getElementById('catcourselevel').value = 'All';document.getElementById('catcourselevel1').value='All';document.getElementById('catcoursetype').value='All';this.className = 'styles';opencatpage(1);return false;"><?php echo $cityTier2[$i]['cityName']?></a>
                                    -->
                                    <div style="display:none;"><?php echo $cityTier2[$i]['cityId'] ?></div>
                                    <a href="/shiksha/change_location/<?php echo $params['blog_id']?>/<?php echo $cityTier2[$i]['cityId'] ?>"><?php echo $cityTier2[$i]['cityName']?></a>
                                    <br />
                                    <?php } ?>
                                </div>
							    <div id="changeCityList" class="txt_align_r" style="margin-right:55px;" align="right"><span class="plusSign1" style="">&nbsp;</span><a id="moreCitiesLink" href="javascript:void(0);" style="position:relative;top:1px;font-size:11px" onClick="fillMoreCities();createMoreCity(this);return false;">More cities</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="float_L" style="width:188px">
                        <div style="padding-left:50px">
                            <!--
                            <div class="Fnt14" style="padding-bottom:6px"><b>Abroad</b></div>
                            <div>Top Countries</div>
                            -->
                            <div style="line-height:20px;padding-left:10px">
<?php global $countriesForStudyAbroad;
					foreach($countriesForStudyAbroad as $countryId => $country) {
							$countryName = isset($country['name']) ? $country['name'] : '';
							$countryValue = isset($country['value']) ? $country['value'] : '';
                            //echo '<script>var SHIKSHA_'. strtoupper($countryId) .'_HOME= "'. constant('SHIKSHA_'.strtoupper($countryId).'_HOME')  .'"; </script>';
							$countryid = isset($country['id']) ? $country['id'] : '';
                            if($countryid != 2) {
					?>
                                <!--
                    <a href = "#" onClick = "this.className = 'styles';selectCountry('<?php echo $countryId?>','<?php echo $countryid?>');return false;"><?php echo $countryName?></a><br/>
                    -->
                    <?php }} ?>
                            </div>
                        </div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
                 </div>
            </div>
            <!--End_OverlayContent-->
        </div>
    </div>
</div>

<script type="text/javascript">

    var divX = parseInt(screen.width/2 - $('testprep_locationlayer').offsetWidth/2) + 20;
    var divY = parseInt(screen.height/2 - $('testprep_locationlayer').offsetHeight/2) - 50;
    $('testprep_locationlayer').style.left = (divX) +  'px';
    $('testprep_locationlayer').style.top = (divY) + 'px';
    var flagForCitiesPpulated = 0;
    
function fillMoreCities() {
    if(flagForCitiesPpulated == 1){ return; }
    var docElemCity = document.getElementById('cityli1');
    var stringForCities = '';
    for(var key in cityList[2]) {
        var cityNameM = cityList[2][key];
        if(!(document.getElementById('subCity_' + key))){
            stringForCities += '<a href="/shiksha/change_location/<?php echo $params['blog_id']?>/' + key + '">' + cityList[2][key] + '</a><br/>';
        }
    }
    docElemCity.innerHTML += stringForCities;
    flagForCitiesPpulated = 1;
    return false;
}
//fillMoreCities();
document.getElementById('cityli1').style.display = 'none';

function createMoreCity(linkObj) {
    if(linkObj.innerHTML.indexOf('More') > -1) {
        linkObj.innerHTML = 'Less cities';
        document.getElementById('cityli1').style.display = '';
        document.getElementById('cityli').style.display = 'none';
        linkObj.parentNode.firstChild.className = 'closedocument1';
    } else {
        linkObj.innerHTML = 'More cities';
        document.getElementById('cityli1').style.display = 'none';
        document.getElementById('cityli').style.display = '';
        linkObj.parentNode.firstChild.className = 'plusSign1';
    }
}

function selectCountry(countryName,countryId)
{
    var urlName = '<?php echo $categoryUrlName; ?>';
    if(countryName.toLowerCase()!= 'india'){
        var countname = 'SHIKSHA_' + countryName.toUpperCase() + '_HOME';
        var newUrl =  eval('SHIKSHA_' + countryName.toUpperCase() + '_HOME');
        if(newUrl.indexOf('studyabroad') < 0) {
            newUrl += '/getCategoryPage/colleges/studyabroad/' + countryName;
        }
        newUrl += '/All/'+  urlName;
        logLayerClicks(newUrl);
        window.location = newUrl;
    }
}

function logLayerClicks(url){
    var clickedOnLayer = 0;
    if(document.getElementById('locationlayer').style.display == 'none') {
        clickedOnLayer = 1;
    }
	var xmlHttp = getXMLHTTPObject();
	var url = '/common/logCLCs/'+ base64_encode(url) + '/'+ base64_encode(window.location)  +'/'+ clickedOnLayer ;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader("Content-length", 0);
    xmlHttp.setRequestHeader("Connection", "close");
    xmlHttp.send(null);
}
</script>
<!--</div>-->
<!--End_Overlay-->
