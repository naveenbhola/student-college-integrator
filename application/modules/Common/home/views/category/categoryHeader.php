<?php 
global $countries;
$this->load->view('common/commonOverlay');
?>
<div class="mar_full_10p">
<input type = "hidden" id = "country" value = "<?php echo $countrySelected; ?>" autocomplete = "off"/>
<input type = "hidden" id = "subCategoryId" value = "<?php echo $categoryId?>" autocomplete = "off"/>
<input type = "hidden" id = "cities" value = "<?php echo $selectedCity?>" autocomplete = "off"/>
<input type="hidden" id="subCategoryNameSelected" value="<?php echo $subCategorySelected; ?>" autocomplete="off"/>
<input type="hidden" id="countryNameSelected" value="<?php echo $countryNameSelected; ?>" autocomplete="off"/>
<input type="hidden" id="cityNameSelected" value="<?php echo $cityNameSelected; ?>" autocomplete="off"/>
<?php 
?>
<div class="mar_full_10p normaltxt_11p_blk_arial">
        <?php
        $rightMarginForHeader = 0;
        if(!is_array($validateuser))
        {
            $rightMarginForHeader = 145;
        ?>
		<div style="width:150px;float:right">
            <div class="lineSpace_10 txt_align_r" onClick="showuserLoginOverLay(this,'HOMEPAGE_CATEGORY_RIGHTPANEL_JOINBUTTON','refresh')" style="cursor:pointer;position:relative;top:-5px"><img src="/public/images/joinBtn_shiksha.gif"/></div>
		</div>		
        <?php } ?>
		<div style="margin-right:<?php echo $rightMarginForHeader + 10;?>px">
        	<div>
            <span class="OrgangeFont bld" style="font-size:18px"><?php echo $categoryData['displayName']?></span>
            <span class="bld" style="font-size:13px">- <?php echo ($subCategorySelected == 'All' || $subCategorySelected == '')? 'All Categories':$subCategorySelected; ?></span> &nbsp;
                <span onClick="drpdwnOpen(this, 'overlayCategoryHolder');return false;">
                    <span class="bld" style="color:#bfbdbe;font-size:13px">[</span><span> <a href="javascript:void(0);" class="cssSprite_Icons" style="background-position:right -290px;padding-right:15px">Refine Category</a> </span><span class="bld" style="color:#bfbdbe;font-size:13px">]</span>
                </span>
                :<span class="bld" style="font-size:13px;" > <?php if(isset($cityNameSelected) && $cityNameSelected != "" && strtolower($cityNameSelected)!="all") echo urldecode($cityNameSelected).","; ?>  &nbsp;<?php echo $countryNameSelected;?></span> 
                <span onClick="drpdwnOpen(this, 'userPreferenceCategoryCity');document.getElementById('userPreferenceCategoryCity').style.left = '200px' ;return false;">
                    <span class="bld" style="color:#bfbdbe;font-size:13px">[</span><span> <a href="javascript:void(0);" class="cssSprite_Icons" style="background-position:right -290px;padding-right:15px" >Change Location</a> </span><span class="bld" style="color:#bfbdbe;font-size:13px">]</span>
                </span>
            </div>
		</div>
</div>
<div id="userPreferenceCategoryCity" name="userPreferenceCategoryCity" style="width:535px;border:1px solid #c4c4c6;background-color:#fefefe;display:none;position:absolute;" onmouseover="this.style.display=''; overlayHackLayerForIE('userPreferenceCategoryCity', document.getElementById('userPreferenceCategoryCity'));" onmouseout="dissolveOverlayHackForIE();this.style.display='none'">
    <div class="lineSpace_10">&nbsp;</div>
    <div class="errorMsg bld" align="center" style="display:none;" id="overlay_error_header">&nbsp;</div>
    <!--<div style="background-color:#eff8ff;line-height:18px;padding-left:5px" class="bld">Location</div>
    <div class="lineSpace_5">&nbsp;</div>-->
    <div style="margin-left:5px" id="overlayLocationHolder" name="overlayLocationHolder">
        <div>
<?php 
foreach($countries as $name=>$value)
                {
                    if($value['name'] == $countryNameSelected)						
                    {?>
			<script>
			   document.getElementById('country').value='<?php echo $value['id']; ?>';
			</script>	
                    <?php
                    } 
                        if(($value['id'] == '2'))
                    { ?> 
                        <div id="subCountry_<?php echo $value['id']; ?>" style="margin-bottom:5px">
							<span class="bld fontSize_14p"><?php echo $value['name'];?></span>
							<div id="countryCities_<?php echo $value['id']; ?>">
                                 <div id="overlayCityHolder" name="overlayCityHolder">
                                    <div>                
                                    <div class="lineSpace_10"> &nbsp; </div>
                                    <div class="mar_full_10p"> Metro Cities </div>
                                    <div class="lineSpace_10"> &nbsp; </div>
                                    <ul class="categoryHeaderContent" style="width:100%">
                                    <?php 
                                    foreach($cityTier1 as $city)
                                    {
                                        if($city['cityId'] != $selectedCity)
                                        {
                                            echo "<li class='' id=\"subCity_".$city['cityId']."\" title = '".$city['cityName']."' style=\"width:110px\"><a href=\"#\" onClick='selectCity(\"".$city['cityName']."\",\"".$city['cityId']."\",\"".$i."\");return false;'><span style='font-size:12px'>".$city['cityName']."</span></a></li>";
                                        }
                                        else
                                        {
                                            echo "<li class='aselected' title = '".$city['cityName']."' id=\"subCity_".$city['cityId']."\" style=\"width:110px\"><a href=\"#\" onClick='selectCity(\"".$city['cityName']."\",\"".$city['cityId']."\",\"".$i."\");return false;'><span style='font-size:12px'>".$city['cityName']."</span></a></li>";
                                        }

                                    }
                                    ?>
                                    </ul>
									<div class="clear_L" style="line-height:1px">&nbsp;</div>                    
                                        <div class="lineSpace_10"> &nbsp; </div>
                                        <div class="mar_full_10p"> Non-Metro Cities </div>
                                    <div style="height:120px; overflow:auto">
                                        <div class="lineSpace_10"> &nbsp; </div>
                                        <ul class="categoryHeaderContent" id="cityli" style="width:97%">
                                        <?php 
                                        foreach($cityTier2 as $city)
                                        {
                                            if($city['cityId'] != $selectedCity)
                                            {
                                                echo "<li class='' title = '".$city['cityName']."' id=\"subCity_".$city['cityId']."\" style=\"width:110px\"><a href=\"#\" onClick='selectCity(\"".$city['cityName']."\",\"".$city['cityId']."\",\"".$i."\");return false;'><span style='font-size:12px'>".$city['cityName']."</span></a></li>";
                                            }
                                            else
                                            {
                                                echo "<li class='aselected' title = '".$city['cityName']."' id=\"subCity_".$city['cityId']."\" style=\"width:110px\"><a href=\"#\" onClick='selectCity(\"".$city['cityName']."\",\"".$city['cityId']."\",\"".$i."\");return false;'><span style='font-size:12px'>".$city['cityName']."</span></a></li>";
                                            }
                                        }
                                        ?>
                                        </ul>
                                    </div>
									<div class="clear_L" style="line-height:1px">&nbsp;</div>                    
									</div>
									<div id="changeCityList" class="txt_align_r" style="margin-right:55px"><span class="plusSign1" style="">&nbsp;</span><a href="" style="position:relative;top:1px;font-size:11px" onClick="getFullCategoryCityList();return false;">More cities</a></div>
                                </div>
                                <div class="lineSpace_10"> &nbsp; </div>
                                <div class="mar_full_10p">
                                    <a href="javascript:void(0);" onClick="refreshCategoryPage('1');return false;"><b>All Cities</b></a>
                                </div>
                                <div class="lineSpace_5"> &nbsp; </div>
							</div> 
                        </div>
                    <?php }
                      else
                      {	  // Please don't change COUNTRY DATA MAP
                          if($value['name'] == "Australia")
                          {
                              echo "<div class=\"bld\" style=\"font-size:14px;padding-bottom:5px\">Abroad</div>";
                              echo "<ul class=\"categoryHeaderContent\" style=\"width:100%\">";
                          }
                          if($value['name'] == $countryNameSelected)
                          {
                              echo "<li id=\"subCountry_".$value['id']."\" style=\"width:115px;\" class=\"aselected\"><a href=\"\" class=\"plusSign1\" onClick=\"selectCountry('".$name."','".$value['id']."');return false;\" style=\"text-decoration:none;\"><span style='font-size:12px'>".$value['name']."</span></a></li>";
                          }
                          else
                          {
                              echo "<li id=\"subCountry_".$value['id']."\" style=\"width:115px\"><a href=\"\" class=\"plusSign1\" onClick=\"selectCountry('".$name."','".$value['id']."');return false;\" style=\"text-decoration:none;\"><span style='font-size:12px'>".$value['name']."</span></a></li>";
                          }
                      }
                } ?>
                </ul> 
        </div>
    </div>  
	<div class="clear_L lineSpace_10">&nbsp;</div>
</div>

<div style="border:solid 1px #acacac;position:absolute;z-index:200;width:350px;display:none; background:#ffffff;" id="overlayCategoryHolder" name="overlayCategoryHolder" onmouseover="this.style.display=''; overlayHackLayerForIE('overlayCategoryHolder', document.getElementById('overlayCategoryHolder'));" onmouseout="dissolveOverlayHackForIE();this.style.display='none'">
    <div class="mar_full_10p">
        <div class="lineSpace_5">&nbsp;</div>
        <div style="line-height:18px;padding-left:5px;" class="bld">Categories</div>
        <div class="lineSpace_5">&nbsp;</div>
	    <div style="position:relative;width:100%">
			<ul class="categoryHeaderContent">
				<?php
                $otherElementId = '';
                $otherElementUrlName = '';
				foreach($subCategoryList as $subcategory)
                {
                    if(strpos($subcategory['name'],'Others..') !== false){
                        $otherElementId = $subcategory['boardId'];
                        $otherElementUrlName = $subcategory['urlName'];
                        continue;
                    }
					if($categoryId == $subcategory['boardId'])
					{
						echo "<li class=\"aselected\" style=\"width:100%\" id=\""."subCat_".$subcategory['boardId']."\"><a href=\"\" onClick=\"selectCategory('".$subcategory['urlName']."','".$subcategory['boardId']."');return false;\"><span>".$subcategory['name']."</span></a></li>";
					}
					else
					{
						echo "<li style=\"width:100%\" id=\""."subCat_".$subcategory['boardId']."\"><a href=\"\" onClick=\"selectCategory('".$subcategory['urlName']."','".$subcategory['boardId']."');return false;\"><span>".$subcategory['name']."</span></a></li>";
					}
                }
                if($otherElementId != '') {
                    if($otherElementId == $categoryId) {
                        $selectedClass = 'aselected'; 
                    } else{
                        $selectedClass = '';
                    }
?>
                <li class="<?php echo $selectedClass; ?>" style="width:100%" id="subCat_<?php echo $otherElementId; ?>"><a href="" onClick="selectCategory('<?php echo $otherElementUrlName; ?>','<?php echo $otherElementId; ?>');return false;"><span>Others...</span></a></li>
				<?php
				    }
				?>  
			</ul>
			<div class="clear_L" style="line-height:1px">&nbsp;</div>
	    </div>
        <div class="lineSpace_10">&nbsp;</div> 
    </div>
</div>

<script>
function selectCountry(countryName,countryId)
{
    if(document.getElementById('subCountry_'+ document.getElementById('country').value).className == "aselected")
    {
        document.getElementById('subCountry_'+ document.getElementById('country').value).className ="";
    }
    document.getElementById('country').value = countryId;
    document.getElementById('countryNameSelected').value = countryName;
    if(document.getElementById('country').value != '2')
    {
        document.getElementById('subCountry_'+ document.getElementById('country').value).className ="aselected";
    }
    if(document.getElementById('subCity_'+document.getElementById('cities').value)) {
        document.getElementById('subCity_'+document.getElementById('cities').value).className = '';
    }
    document.getElementById('cities').value = '';
    document.getElementById('cityNameSelected').value = '';
    refreshCategoryPage('0');
    return false; 
}

function selectCity(cityName,cityId,tabId)
{
    if(document.getElementById('subCountry_'+ document.getElementById('country').value).className == "aselected")
    {
        document.getElementById('subCountry_'+ document.getElementById('country').value).className ="";
    }
    if(document.getElementById('subCity_'+ document.getElementById('cities').value))
    {
        document.getElementById('subCity_'+ document.getElementById('cities').value).className = '';
    }
    document.getElementById('country').value = '2';
	document.getElementById('countryNameSelected').value = 'india';
    document.getElementById('cities').value = cityId;
    document.getElementById('cityNameSelected').value = cityName;
    if(document.getElementById('subCity_'+ document.getElementById('cities').value))
    {
        document.getElementById('subCity_'+ document.getElementById('cities').value).className = 'aselected';
    }
    refreshCategoryPage('0');
    return false;
}
function selectCategory(subCategoryName, subCategoryId)
{
    document.getElementById('subCategoryNameSelected').value = subCategoryName;
    document.getElementById('subCategoryId').value = subCategoryId;
    categoryParent = document.getElementById('overlayCategoryHolder');
    var categorylistElements = categoryParent.getElementsByTagName('li');
    for(i=0; i < categorylistElements.length ; i++)
    {
        if(categorylistElements[i].id == "subCat_"+subCategoryId)
        {
            categorylistElements[i].className = "aselected";
        }
        else 
        {
            categorylistElements[i].className = "";
        }
    }
    refreshCategoryPage('0');
    return false;
}
function getFullCategoryCityList()
{
    var selectedCity = document.getElementById('cities').value == '' ? '-1':document.getElementById('cities').value;
    populatecities(selectedCity);
    document.getElementById('cityli').innerHTML = document.getElementById('cityList').innerHTML;
    document.getElementById('changeCityList').style.display = 'none';
    return false;
}
function refreshCategoryPage(reset)
{
    var urlName = '<?php echo $categoryUrlName; ?>';
    setCookie('categoryPageResultsPerPage',document.getElementById('countOffset').value);
    var url = eval(pageName);// window.location.href;
    if(url.indexOf('getCategoryPage/colleges') < 0 ) {
        url += '/getCategoryPage/colleges';
    }
    if(url.indexOf('//'+urlName) > 0) {
        url += '/' + urlName;
    }
    var subCategoryName =  trim(document.getElementById('subCategoryNameSelected').value.replace(/[ /&]/g,'-').replace(/-+/g,'-'));
    subCategoryName = subCategoryName == '' ? 'All' : subCategoryName;


    if(reset && reset == '1')
    {
        document.getElementById('overlay_error_header').innerHTML = "";
        document.getElementById('overlay_error_header').style.display = "none"; 
        setCookie('userCityPreference','All::::::India');
        var newUrl = url+'/India/All/'+ subCategoryName; 
        window.location = newUrl;
    }
    else
    {
        document.getElementById('overlay_error_header').innerHTML = "";
        document.getElementById('overlay_error_header').style.display = "none"; 
        var countryName = document.getElementById('countryNameSelected').value;
        var cityId = document.getElementById('cities').value;
        cityId = cityId == '' ? 'All' : cityId;
        var cityName = document.getElementById('cityNameSelected').value==''?'All':document.getElementById('cityNameSelected').value;

        if(countryName.toLowerCase()!='india'){
            var newUrl =  eval('SHIKSHA_'+ countryName.toUpperCase() + '_HOME');

        if(newUrl.indexOf('studyabroad') < 0) {
                newUrl += '/getCategoryPage/colleges/studyabroad/' + countryName;
        }
            newUrl += '/All/'+  urlName;
            logLayerClicks(newUrl);
            window.location = newUrl;
        } else if (countryName.toLowerCase()=='india') {
            var newUrl =  '/'+ countryName + '/'+ cityId +'/'+ subCategoryName  +'/'+ cityName.replace('/','-');
            logLayerClicks(newUrl);
            window.location = url + newUrl;
        } else  {
            document.getElementById('overlay_error_header').innerHTML = "Please select a Location";
            document.getElementById('overlay_error_header').style.display = ""; 
        }
    }
    return false;
}

function logLayerClicks(url){
    var clickedOnLayer = 0;
    if(document.getElementById('userPreferenceCategoryCity').style.display == 'none') {
        clickedOnLayer = 1;
    }
	var xmlHttp = getXMLHTTPObject();
	var url = '/common/logCLCs/'+ base64_encode(url) + '/'+ base64_encode(window.location)  +'/'+ clickedOnLayer ;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader("Content-length", 0);
    xmlHttp.setRequestHeader("Connection", "close");
    xmlHttp.send(null);
}

function showUserPreferenceOverlay()
{
    showOverlay(535,600,'<span style="color:#000000;font-weight:bold;margin-left:5px;">Select a location to begin exploring Courses and Institutes</span>',document.getElementById('userPreferenceCategoryCity').innerHTML);
    document.getElementById('genOverlayHolderDiv').className = document.getElementById('genOverlayHolderDiv').className.replace('overlayTitleDefault','overlayTitleImg');
    //document.getElementById('genOverlayContents').className = '';
    document.getElementById('overlayCloseCross').style.display='none';

}
</script>



