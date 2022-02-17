<?php 
global $countriesForStudyAbroad;
if($countSelected == "new zealand"){
	$countSelected = "newzealand";
}
if($countSelected == "uk-ireland"){
	$countSelected = "ukireland";
}
if($countrySelected == "uk-ireland"){
	$countrySelected = "ukireland";
}
?>
<input type = "hidden" id = "country" value = "<?php echo ((string)$countrySelected); ?>" autocomplete = "off"/>
<input type = "hidden" id = "countryNameO" value = "<?php echo ((string)$countSelected); ?>" autocomplete = "off"/>
<input type = "hidden" id = "subCategoryId" value = "<?php echo $categoryId;?>" autocomplete = "off"/>
<input type = "hidden" id = "cities" value = "<?php echo $selectedCity;?>" autocomplete = "off"/>
<input type="hidden" id="subCategoryNameSelected" value="<?php echo $subCategorySelected; ?>" autocomplete="off"/>
<input type="hidden" id="countryNameSelected" value="<?php echo $countryNameSelected; ?>" autocomplete="off"/>
<input type="hidden" id="cityNameSelected" value="<?php echo $cityNameSelected; ?>" autocomplete="off"/>
<div class="mar_full_10p">
	<div id="userPreferenceCategoryCity" name="userPreferenceCategoryCity" style="width:<?php echo $categoryData['page'] == 'INDIA_PAGE' ? 535 : 150; ?>px;border:1px solid #c4c4c6;background-color:#fefefe;display:none;position:absolute;" onmouseover="this.style.display=''; overlayHackLayerForIE('userPreferenceCategoryCity', document.getElementById('userPreferenceCategoryCity'));" onmouseout="dissolveOverlayHackForIE();this.style.display='none';">
		<div class="lineSpace_10">&nbsp;</div>
		<div class="errorMsg bld" align="center" style="display:none;" id="overlay_error_header">&nbsp;</div>
		<div style="margin-left:5px" id="overlayLocationHolder" name="overlayLocationHolder">
			<div>
				<?php 
					$i = 0;
					foreach($countriesForStudyAbroad as $name=>$value) {	
						if($countryNameSelected == 'UK-ireland'){//Bad hack, but so is everyting outta here
							$countryNameSelected = 'UK-Ireland';
						}
						if($value['name'] == $countryNameSelected) { ?>
							<script>document.getElementById('country').value='<?php echo $value['id']; ?>';</script>	
						<?php
						}                          
						if($name == "uk-ireland"){
					    	$name = "ukireland";
					    }
                            echo '<script>var SHIKSHA_'. strtoupper($name) .'_HOME= "'. constant('SHIKSHA_'.strtoupper($name).'_HOME')  .'"; </script>';
						if($categoryData['page'] == 'INDIA_PAGE') {
							if($value['id'] !=2) continue;                          
						} 
						else 
						{
							if($value['id'] == 2) continue;
							if($i ==0 && $categoryData['page'] != 'INDIA_PAGE') 
							{
								$i++;
								echo "<ul class=\"chgLoc\" style=\"width:100%\">";
							}
							if($value['name'] == $countryOriSelected && $categoryData['page'] != 'INDIA_PAGE') {
								echo "<script>document.getElementById('countryNameSelected').value = '$name'</script>";
								$countrySelectedClassName = 'aselected';
							} else {
								$countrySelectedClassName = 'unselected';
							}
							//$imgname1 = '/public/images/blankImg.gif';
							$imgname1 = '/public/images/gryDot.gif';							
							$styleDotted ='';
							$onClick1 = '';
							$divClass = '';
							if(strpos($value['id'],',') !== false && $pagename == 'studyabroaddetail') { 
								$imgname1 = '/public/images/plusSign.gif';
								$styleDotted ='';
								$onClick1 = "expandCountries('".$value['name']."');return false";
								$divClass = 'splus';
							}

						?>						
							<li id="subCountry_<?php echo $value['id'];?>" class="<?php echo $countrySelectedClassName; ?>">
								<div class="splus">
									<a href="#"><img id="<?php echo 'plusSignimg'.$value['name']?>" onClick="<?php echo $onClick1;?>" src="<?php echo $imgname1; ?>" border="0" style="<?php echo $styleDotted; ?>" /></a> &nbsp; <a href="#" onClick="selectCountry('<?php echo $name;?>','<?php echo $value['id'];?>');return false;" class="sLnk"><?php echo $value['name']; ?></a>
								</div>
<?php
	if(strpos($value['id'],',') !== false && $pagename == 'studyabroaddetail') { ?>
	<div id="<?php echo 'countrydiv'.$value['name']?>" style="display:none;" class="sOpt">
		<?php
			$flag = 0;
			$stripval = explode(',',$value['countryName']);
			$countriessplit = explode(',',$value['id']);
			for($k = 0;$k < count($stripval); $k++)
			{
				$className2 = '';
				$className3 = '';
				if(strtolower($stripval[$k]) == strtolower($countryOriSelected))
				{
					$className2 = 'sldt';					
					$flag = 1;?>
				    <script>document.getElementById('country').value='<?php echo $countriessplit[$k]; ?>';</script>	
		<?php		}
?>
				<div class="<?php echo $className2;?>" id="subCountry_<?php echo $countriessplit[$k];?>"><img src="/public/images/gryDot.gif" border="0" align="absmiddle" /> &nbsp; <a href="#" onClick="selectCountry('<?php echo $stripval[$k];?>','<?php echo $countriessplit[$k];?>','',this,'<?php echo $value['id'];?>');return false;"><?php echo $stripval[$k];?></a></div>
			<?php 
			}
			?>
	</div>
	<?php if($flag == 1){ ?>
		<script>
			document.getElementById('<?php echo 'countrydiv'.$value['name']?>').style.display = '';
			document.getElementById('<?php echo 'plusSignimg' .$value['name']?>').src = '/public/images/closedocument.gif';
		</script>
	<?php  }
	} ?>								
<!--								<div style="display:none" class="sOpt">
									<div class=""><a href="#">Link 1</a></div>
									<div class=""><a href="#">Link 2</a></div>
									<div class="sldt"><a href="#">Link 3</a></div>
								</div>-->
							</li>
							<!--
							<li id="subCountry_<?php echo $value['id'];?>" class="aselected">
								<div class="splus">
									<a href="#"><img class="pS" src="/public/images/closedocument1.gif" border="0" /></a> &nbsp; <a href="#" class="sLnk">South</a>
								</div>
								<div style="display:none" class="sOpt">
									<div class=""><a href="#">Link 1</a></div>
									<div class=""><a href="#">Link 2</a></div>
									<div class="sldt"><a href="#">Link 3</a></div>
								</div>
							</li>
							-->
						<?php
						}
					} 
					?>
					</ul>
			</div>
		</div>
	</div>

<div style="border:solid 1px #acacac;position:absolute;z-index:200;display:none; background:#ffffff;width:255px" id="overlayCategoryHolder" name="overlayCategoryHolder" onmouseover="this.style.display=''; overlayHackLayerForIE('overlayCategoryHolder', document.getElementById('overlayCategoryHolder'));" onmouseout="dissolveOverlayHackForIE();this.style.display='none'">
    <div class="mar_full_10p">
        <div class="lineSpace_5">&nbsp;</div>
        <div style="line-height:18px;padding-left:5px;" class="bld">Categories</div>
        <div class="lineSpace_5">&nbsp;</div>
	    <div style="position:relative;width:100%">
			<ul class="refineCategoryOverlay">
            <?php 
            $className = '';
            if($subCategorySel == 'All') { 
            $className = 'aselected';
            }
            ?>
						<li class = "<?php echo $className;?>" style="width:240px" id="subCat_0"><a href="" onClick="selectCategory('All',0);return false;"><span>All</span></a></li>
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
                       // echo "<script>document.getElementById('selectedCategoryPlace').innerHTML = ' Miscellaneous';</script>";
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
<?php echo '<script>
var SHIKSHA_SOUTHEASTASIA_HOME= "'. constant('SHIKSHA_SOUTHEASTASIA_HOME')  .'";
var SHIKSHA_MIDDLEEAST_HOME= "'. constant('SHIKSHA_MIDDLEEAST_HOME')  .'";
var SHIKSHA_EUROPE_HOME= "'. constant('SHIKSHA_EUROPE_HOME')  .'";
var SHIKSHA_UKIRELAND_HOME= "'. constant('SHIKSHA_UK-IRELAND_HOME')  .'";
var SHIKSHA_SINGAPORE_HOME = "'.constant('SHIKSHA_SINGAPORE_HOME').'";
var SHIKSHA_MALAYSIA_HOME = "'.constant('SHIKSHA_MALAYSIA_HOME').'";
var SHIKSHA_RUSSIA_HOME = "'.constant('SHIKSHA_RUSSIA_HOME').'";
var SHIKSHA_SWITZERLAND_HOME = "'.constant('SHIKSHA_SWITZERLAND_HOME').'";
var SHIKSHA_GERMANY_HOME = "'.constant('SHIKSHA_GERMANY_HOME').'";
var SHIKSHA_DENMARK_HOME = "'.constant('SHIKSHA_DENMARK_HOME').'";
var SHIKSHA_SWEDEN_HOME = "'.constant('SHIKSHA_SWEDEN_HOME').'";
var SHIKSHA_SPAIN_HOME = "'.constant('SHIKSHA_SPAIN_HOME').'";
var SHIKSHA_HOLLAND_HOME = "'.constant('SHIKSHA_HOLLAND_HOME').'";
var SHIKSHA_FRANCE_HOME = "'.constant('SHIKSHA_FRANCE_HOME').'";
var SHIKSHA_ITALY_HOME = "'.constant('SHIKSHA_ITALY_HOME').'";
var SHIKSHA_SLOVAKIA_HOME = "'.constant('SHIKSHA_SLOVAKIA_HOME').'";
var SHIKSHA_FINLAND_HOME = "'.constant('SHIKSHA_FINLAND_HOME').'";
var SHIKSHA_IRELAND_HOME = "'.constant('SHIKSHA_IRELAND_HOME').'";
var SHIKSHA_UK_HOME = "'.constant('SHIKSHA_UK_HOME').'";

var SHIKSHA_UAE_HOME = "'.constant('SHIKSHA_UAE_HOME').'";
var SHIKSHA_QATAR_HOME = "'.constant('SHIKSHA_QATAR_HOME').'";
var SHIKSHA_SAUDIARABIA_HOME = "'.constant('SHIKSHA_SAUDIARABIA_HOME').'";

</script>'; ?>

<script>
function selectCountry(countryName,countryId,className,obj,mainid)
{
    if(document.getElementById('subCountry_'+ document.getElementById('country').value).className == "aselected" ||  document.getElementById('subCountry_' + document.getElementById('country').value).className == "sldt")
    {
        document.getElementById('subCountry_'+ document.getElementById('country').value).className ="unselected";
    }
    document.getElementById('country').value = countryId;
    document.getElementById('countryNameO').value = countryName;
    if(document.getElementById('country').value != '2')
    {
        if(typeof(mainid) != 'undefined')
        {
            document.getElementById('subCountry_' + mainid).className = "unselected";
            document.getElementById('subCountry_' + document.getElementById('country').value).className = "sldt";
        }
        else{
            document.getElementById('subCountry_'+ document.getElementById('country').value).className ="aselected";
        }
    }
    if(document.getElementById('subCity_'+document.getElementById('cities').value))
    {
        document.getElementById('subCity_'+document.getElementById('cities').value).className = '';
    }
    document.getElementById('cities').value = '';
    document.getElementById('cityNameSelected').value = '';
    if(typeof(obj) != 'undefined')
    {
        obj.className = className;
    }
    refreshCategoryPage('0');
    return false; 
}

function refreshCategoryPage(reset)
{	
    var urlName = '<?php echo $categoryUrlName; ?>';
    var countryName = document.getElementById('countryNameO').value;
    if(countryName == "newzealand"){
        countryName = "new zealand";
    }
    if(countryName == "Saudi Arabia"){
    	countryName = "saudiarabia";
    }
    if(countryName == "uk-ireland"){
    	countryName = "ukireland";
    }
    //setCookie('categoryPageResultsPerPage',document.getElementById('countOffset').value);
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
    if(location.href.indexOf('studyabroaddetail') > 0)
    {
        url = url.replace('studyabroad','studyabroaddetail');
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
            document.getElementById('overlay_error_header').innerHTML = "Please select a Location";
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

function expandCountries(name)
{

   collapseCountries('South East Asia');
   collapseCountries('Europe');
    document.getElementById('countrydiv' + name).style.display = 'block';
    document.getElementById('plusSignimg' + name).src = '/public/images/closedocument.gif';
    document.getElementById('plusSignimg' + name).onclick = function () { collapseCountries(name);return false; };
}

function collapseCountries(name)
{
    document.getElementById('countrydiv' + name).style.display = 'none';
    document.getElementById('plusSignimg' + name).src = '/public/images/plusSign.gif';
    document.getElementById('plusSignimg' + name).onclick = function () { expandCountries(name) ;return false;};
}

</script>
