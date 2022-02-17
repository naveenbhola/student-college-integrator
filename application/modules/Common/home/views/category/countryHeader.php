<?php 
global $countriesForStudyAbroad;
$this->load->view('common/commonOverlay');
?>
<div class="mar_full_10p">
<input type = "hidden" id = "country" value = "<?php echo ((string)$countrySelected); ?>" autocomplete = "off"/>
<input type = "hidden" id = "subCategoryId" value = "<?php echo $categoryId?>" autocomplete = "off"/>
<input type = "hidden" id = "cities" value = "<?php echo $selectedCity?>" autocomplete = "off"/>
<input type="hidden" id="subCategoryNameSelected" value="<?php echo $subCategorySelected; ?>" autocomplete="off"/>
<input type="hidden" id="countryNameSelected" value="<?php echo $countryNameSelected; ?>" autocomplete="off"/>
<input type="hidden" id="cityNameSelected" value="<?php echo $cityNameSelected; ?>" autocomplete="off"/>
<div class="mar_full_10p normaltxt_11p_blk_arial">
        <?php
        $rightMarginForHeader = 0;
        if(!is_array($validateuser))
        {
            $rightMarginForHeader = 200;
        ?>
		<div style="width:200px;float:right">
            <div class="lineSpace_10 txt_align_r" onClick="showuserLoginOverLay(this,'HOMEPAGE_STUDYABROAD_RIGHTPANEL_JOINBUTTON','refresh')" style="cursor:pointer;position:relative;top:-5px"><img src="/public/images/joinBtn_shiksha.gif"/></div>
		</div>		
        <?php } ?>
		<div style="margin-right:<?php echo $rightMarginForHeader + 10;?>px">
        	<div>
            <span class="OrgangeFont bld" style="font-size:18px"><?php echo $countryNameSelected; ?></span>
                <span onClick="drpdwnOpen(this, 'userPreferenceCategoryCity');document.getElementById('userPreferenceCategoryCity').style.left = '200px';return false;">
                    <span class="bld" style="color:#bfbdbe;font-size:13px">[</span><span> <a href="javascript:void(0);" class="cssSprite_Icons" style="background-position:right -290px;padding-right:15px">Change Location</a> </span><span class="bld" style="color:#bfbdbe;font-size:13px">]</span>
                </span>
                :<span class="bld" style="font-size:13px" id="selectedCategoryPlace"> <?php echo ($subCategorySelected == 'All' || $subCategorySelected == '')? 'All Categories':$subCategorySelected; ?></span> &nbsp;
                <span onClick="drpdwnOpen(this, 'overlayCategoryHolder');return false;">
                    <span class="bld" style="color:#bfbdbe;font-size:13px">[</span><span> <a href="javascript:void(0);" class="cssSprite_Icons" style="background-position:right -290px;padding-right:15px">Refine Category</a> </span><span class="bld" style="color:#bfbdbe;font-size:13px">]</span>
                </span>
            </div>
		</div>
</div>
<div id="userPreferenceCategoryCity" name="userPreferenceCategoryCity" style="width:<?php echo $categoryData['page'] == 'INDIA_PAGE' ? 535 : 150; ?>px;border:1px solid #c4c4c6;background-color:#fefefe;display:none;position:absolute;" onmouseover="this.style.display=''; overlayHackLayerForIE('userPreferenceCategoryCity', document.getElementById('userPreferenceCategoryCity'));" onmouseout="dissolveOverlayHackForIE();this.style.display='none'">
    <div class="lineSpace_10">&nbsp;</div>
    <div class="errorMsg bld" align="center" style="display:none;" id="overlay_error_header">&nbsp;</div>
    <!--<div style="background-color:#eff8ff;line-height:18px;padding-left:5px" class="bld">Location</div>
    <div class="lineSpace_5">&nbsp;</div>-->
    <div style="margin-left:5px" id="overlayLocationHolder" name="overlayLocationHolder">
        <div>
            <?php
            $i = 0;
                foreach($countriesForStudyAbroad as $name=>$value) {
                    if($value['name'] == $countryNameSelected) {
            ?>
			<script>document.getElementById('country').value='<?php echo $value['id']; ?>';</script>	
            <?php
                    }
                    if($categoryData['page'] == 'INDIA_PAGE') {
                        if($value['id'] !=2) continue; 
           ?> 
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
                                        foreach($cityTier1 as $city) {
                                            if($city['cityId'] != $selectedCity) {
                                                $citySelectedClassName = '';
                                            } else {
                                                $citySelectedClassName = 'aselected';
                                            }
                                    ?>
                                            <li class='<?php echo $citySelectedClassName; ?>' id="subCity_<?php echo $city['cityId'];?>" style="width:110px">
                                                <a href="#" onClick='selectCity("<?php echo $city['cityName'];?>","<?php echo $city['cityId'];?>","<?php echo $i; ?>");return false;'>
                                                    <span style='font-size:12px'><?php echo $city['cityName']; ?></span>
                                                </a>
                                            </li>
                                    <?php
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
                                            foreach($cityTier2 as $city) {
                                                if($city['cityId'] != $selectedCity) {
                                                    $citySelectedClassName = '';
                                                } else {
                                                    $citySelectedClassName = 'aselected';
                                                }
                                            ?>
                                            <li class='<?php echo $citySelectedClassName; ?>' id="subCity_<?php echo $city['cityId'];?>" style="width:110px">
                                                <a href="#" onClick='selectCity("<?php echo $city['cityName'];?>","<?php echo $city['cityId'];?>","<?php echo $i; ?>");return false;'>
                                                    <span style='font-size:12px'><?php echo $city['cityName']; ?></span>
                                                </a>
                                            </li>
                                            <?php
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
                        <?php 
                        } else {
                            if($value['id'] == 2) continue;
                            if($i ==0 && $categoryData['page'] != 'INDIA_PAGE') {
                            $i++;
                                echo "<ul class=\"categoryHeaderContent\" style=\"width:100%\">";
                            }
                            if($value['name'] == $countryNameSelected && $categoryData['page'] != 'INDIA_PAGE') {
                                echo "<script>document.getElementById('countryNameSelected').value = '$name'</script>";
                                $countrySelectedClassName = 'aselected';
                            } else {
                                $countrySelectedClassName = '';
                            }
                            ?>

                              <li id="subCountry_<?php echo $value['id'];?>" style="width:145px;" class="<?php echo $countrySelectedClassName; ?>">
                                <a href="" class="plusSign1" onClick="selectCountry('<?php echo $name;?>','<?php echo $value['id'];?>');return false;" style="text-decoration:none;">
                                    <span style='font-size:12px'><?php echo $value['name'];?></span>
                                </a>
                             </li>
                <?php
                      }
                } 
                ?>
                </ul> 
        </div>
    </div>  
	<div class="clear_L lineSpace_10">&nbsp;</div>
</div>

<div style="border:solid 1px #acacac;position:absolute;z-index:200;display:none; background:#ffffff;width:300px" id="overlayCategoryHolder" name="overlayCategoryHolder" onmouseover="this.style.display=''; overlayHackLayerForIE('overlayCategoryHolder', document.getElementById('overlayCategoryHolder'));" onmouseout="dissolveOverlayHackForIE();this.style.display='none'">
    <div class="mar_full_10p">
        <div class="lineSpace_5">&nbsp;</div>
        <div style="line-height:18px;padding-left:5px;" class="bld">Categories</div>
        <div class="lineSpace_5">&nbsp;</div>
	    <div style="position:relative;width:100%">
			<ul class="refineCategoryOverlay">
				<?php
                $otherElementId = '';
                $otherElementUrlName = '';
				foreach($subCategoryList as $subcategory)
                {
                    if(strpos($subcategory['name'],'Miscellaneous') !== false){
                        $otherElementId = $subcategory['boardId'];
                        $otherElementUrlName = $subcategory['urlName'];
                        continue;
                    }
					if($categoryId == $subcategory['boardId'])
					{
                    ?>
                        <script>document.getElementById('selectedCategoryPlace').innerHTML = ' <?php echo $subcategory['name'] ;?>';</script>
						<li class="aselected" style="width:240px" id="subCat_<?php echo $subcategory['boardId']; ?>"><a href="" onClick="selectCategory('<?php echo $subcategory['urlName']; ?>','<?php echo $subcategory['boardId'] ;?>');return false;"><span><?php echo $subcategory['name']; ?></span></a></li>
					<?php }
					else
					{
                    ?>
						<li style="width:240px" id="subCat_<?php echo $subcategory['boardId']; ?>"><a href="" onClick="selectCategory('<?php echo $subcategory['urlName'];?>','<?php echo $subcategory['boardId'];?>');return false;"><span><?php echo $subcategory['name']; ?></span></a></li>
				<?php
                    }
                }
                if($otherElementId != '') {
                    if($otherElementId == $categoryId) {
                        $selectedClass = 'aselected'; 
                        echo "<script>document.getElementById('selectedCategoryPlace').innerHTML = ' Miscellaneous';</script>";
                    } else{
                        $selectedClass = '';
                    }
?>
                <li class="<?php echo $selectedClass; ?>" style="width:160px" id="subCat_<?php echo $otherElementId; ?>"><a href="" onClick="selectCategory('<?php echo $otherElementUrlName; ?>','<?php echo $otherElementId; ?>');return false;"><span>Miscellaneous</span></a></li>
				<?php
				    }
				?>  
			</ul>
			<div class="clear_L" style="line-height:1px">&nbsp;</div>
	    </div>
        <div class="lineSpace_10">&nbsp;</div> 
    </div>
</div>

<?php echo '<script>var SHIKSHA_SOUTHEASTASIA_HOME= "'. constant('SHIKSHA_SOUTHEASTASIA_HOME')  .'";var SHIKSHA_EUROPE_HOME= "'. constant('SHIKSHA_EUROPE_HOME')  .'";</script>'; ?>
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
    var countryName = document.getElementById('countryNameSelected').value;
    countryName = countryName.toString();
    countryName = escapeHTML(countryName);
    if(countryName===false)
    {
        return;
    }

    setCookie('categoryPageResultsPerPage',document.getElementById('countOffset').value);
    var url = eval('SHIKSHA_'+ countryName.toUpperCase() + '_HOME');
    if(url.indexOf('getCategoryPage/colleges') < 0 ) {
        url += '/getCategoryPage/colleges';
    }
    /*
    if(url.indexOf('//'+urlName) > -1) {
        url += '/' + urlName;
    }
    */
    if(url.indexOf('studyabroad') < 0) {
        url += '/studyabroad/' + countryName;
    }
    var subCategoryName =  trim(document.getElementById('subCategoryNameSelected').value.replace(/[ /&]/g,'-').replace(/-+/g,'-'));
    subCategoryName = subCategoryName == '' ? 'All' : subCategoryName;
    if(reset && reset == '1')
    {
        document.getElementById('overlay_error_header').innerText = "";
        document.getElementById('overlay_error_header').style.display = "none"; 
        setCookie('userCityPreference','All::::::India');
        var newUrl = url+'/India/All/'+ subCategoryName; 
        window.location = newUrl;
    }
    else
    {
        document.getElementById('overlay_error_header').innerText = "";
        document.getElementById('overlay_error_header').style.display = "none"; 
        var cityId = document.getElementById('cities').value;
        cityId = cityId == '' ? 'All' : cityId;
        var cityName = document.getElementById('cityNameSelected').value==''?'All':document.getElementById('cityNameSelected').value;
        if(countryName!='india' || (countryName=='india' && cityId != 'All'))
        {
            var newUrl =  /*'/'+ countryName +*/ '/'+ cityId +'/'+ subCategoryName  +'/'+ cityName.replace('/','-');
            window.location = url + newUrl;
        }
        else
        {
            document.getElementById('overlay_error_header').innerText = "Please select a Location";
            document.getElementById('overlay_error_header').style.display = ""; 
        }
    }
    return false;
}
function showUserPreferenceOverlay()
{
    showOverlay(535,600,'<span style="color:#000000;font-weight:bold;margin-left:5px;">Select a location to begin exploring Courses and Institutes</span>',document.getElementById('userPreferenceCategoryCity').innerHTML);
    document.getElementById('genOverlayHolderDiv').className = document.getElementById('genOverlayHolderDiv').className.replace('overlayTitleDefault','overlayTitleImg');
    document.getElementById('genOverlayContents').className = '';
    document.getElementById('overlayCloseCross').style.display='none';

}
</script>
