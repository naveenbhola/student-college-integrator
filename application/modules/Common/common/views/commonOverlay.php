
<div style="width:330px; display:none;position:absolute;z-index:1000;" id="commonOverlay" onmouseover="this.style.display=''; overlayHackLayerForIE('commonOverlay', document.getElementById('commonOverlay'));">
	<div id="shadow-container" style="border:none;text-align:left;background:none;">
		       <div class="container <?php if($isQnA == 1){?>top-fix<?php }?>" style="border: 1px solid #E5DCD5;width:300px;">
				  <div>
				     <div style="height:300px;overflow:auto;" id="commonListContainer">
						<div>
						   <div class="lineSpace_10">&nbsp;</div>
						   <div id = "examcategoryList" style = "display:none">
                           <script>var examId = <?php echo isset($examId)?$examId:0?>;</script>
                           <?php
						      	for($i = 0;$i<count($examcategory);$i++) 
                               {?>
						        <div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default">
							 	<div><a href="#" title="<?php echo $examcategory[$i]['blogTitle'];?> " onClick = "selectCategory('<?php echo $examcategory[$i]['blogTitle']; ?>','TestPrep',<?php echo $examcategory[$i]['blogId'] ?>);return false;">&nbsp;<?php echo $examcategory[$i]['blogTitle']; ?></a></div>
                             </div>
                             <?php } ?>
                             </div>

					<?php if(isset($catCountURL) && strpos($catCountURL,'@catName@') > 0){ ?>
                                                   <!-- Start:: Create Category List in Overlay -->
                                                   <div id = "parentcategoryList" style = "display:none">
                                                   <div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default">
                                                                <div><a href="<?php echo str_replace('/@catName@','',$catCountURL);?>" title="All" onClick = "this.className = 'astyles';"  class="quesAnsBullets">All</a></div>
                             			   </div>
			                           <?php global $categoryParentMap;
                                                        foreach($categoryParentMap as $categoryName => $category)
                        			        {?>
                                                        <div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default">
                                                                <div><a href="<?php echo $catReplace = str_replace('@catName@',seo_url_lowercase($categoryName,"-"),$catCountURL);?>" title="<?php echo $categoryName;?> " onClick = "this.className = 'astyles';" class="quesAnsBullets"><?php echo $categoryName; ?></a></div>
                             				</div>
                             				<?php } ?>
                             			   </div>
                                                   <!-- End:: Create Category List in Overlay -->
					<?php }else{ ?>
						   <!-- Start:: Create Category List in Overlay -->
						   <div id = "parentcategoryList" style = "display:none">
						   <div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default">
							 	<div><a href="<?php if(isset($catCountURL)){ $catReplace = str_replace('@cat@','1',$catCountURL); echo str_replace('@coun@',$selectedCountry,$catReplace);} else echo "#";?>" title="All" onClick = "this.className = 'astyles';<?php if(!(isset($catCountURL))) echo "selectCategory('All','All Categories',1);return false;";?>"  class="quesAnsBullets">All</a></div>
                             </div>	
                           <?php global $categoryParentMap;
						      	foreach($categoryParentMap as $categoryName => $category) 
                               {?>
						        <div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default">
							 	<div><a href="<?php if(isset($catCountURL)){ $catReplace = str_replace('@cat@',$categoryParentMap[$categoryName]['id'],$catCountURL); echo str_replace('@coun@',$selectedCountry,$catReplace);} else echo "#";?>" title="<?php echo $categoryName;?> " onClick = "this.className = 'astyles';<?php if(!(isset($catCountURL))) echo "selectCategory('".$categoryName."','',".$categoryParentMap[$categoryName]['id'].");return false;";?>" class="quesAnsBullets"><?php echo $categoryName; ?></a></div>
                             </div>
                             <?php } ?>
                             </div>
						   <!-- End:: Create Category List in Overlay -->
					<?php } ?>

						   <div id = "categoryList" style = "display:none">
<?php
                                // Loaded the library to have a common Shiksha standard as we had to support the OLD Cache get here (Don't know why it was used here)..
                                $this->load->library('cacheLib');
                                $cacheLibObj = new cacheLib();
                                $categoryParent = unserialize($cacheLibObj->get('catsubCatList'));
                                if(is_array($categoryParent))
                                    foreach($categoryParent as $categoryName => $category) {
                                    global $categoryMap;
                                    $pageName  = $categoryMap[$categoryParent[$categoryName]['urlName']]['page'];
                                    echo '<script>var '. $pageName .'= "'. constant('SHIKSHA_'.strtoupper($categoryParent[$categoryName]['urlName']).'_HOME')  .'"; </script>';
?>
						        <div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default">
                                <div><span id = "<?php echo 'Catimg'.$categoryName?>"><img src = "/public/images/plusSign.gif" border = "0" onClick = "OpenSubCategories(<?php echo $categoryName?>);return false;"/></span><a id = "<?php echo "cat".$categoryParent[$categoryName]['id']?>" onClick = "selectCategory('<?php echo $categoryParent[$categoryName]['categoryName']?>','',<?php echo $categoryName?>,'<?php echo $pageName?>');return false;" href="#" title="<?php echo $categoryParent[$categoryName]['categoryName'];?> ">&nbsp;<?php echo $categoryParent[$categoryName]['categoryName']; ?></a></div>
                                <div id = "<?php echo 'subCat'.$categoryName?>" style = "display:none">
                                <?php $subCatarray = $categoryParent[$categoryName]['subCategories'];
                                if(count($subCatarray) > 0)
                                {
                                foreach($subCatarray as $cat => $subCat)
                                {?>

                                  <div style = "padding-left:15px"><a onClick = "selectCategory('<?php echo $categoryParent[$categoryName]['categoryName']?>','<?php echo $subCatarray[$cat]['categoryName']?>',<?php echo $cat ?>,'<?php echo $pageName?>');return false;" href="#" title="" class="quesAnsBullets">&nbsp;<?php echo $subCatarray[$cat]['categoryName']?></a></div>

                               <?php }
                               }
                               else { ?>
                               <script>
                               var catimgName = 'Catimg' + <?php echo $categoryName ?>;
//                               document.getElementById(catimgName).innerHTML = '';
  //                             document.getElementById(catimgName).style.paddingLeft = '10px';
                               </script>
                              <?php }  ?>
                                </div>
								</div>
                                    
                             <?php  }
                              
                              ?>
                             </div>
                             <div id = "countryList" style = "display:none">
							 <div class="quesAnsBullets mar_left_10p" id = "allCountryContainer" style="padding:0 5px 5px 10px;cursor:default">
								<a href="<?php if(isset($catCountURL)){ $catReplace = str_replace('@coun@','1',$catCountURL); echo str_replace('@cat@',$selectedCategory,$catReplace);} else echo "#";?>" onClick = "this.className = 'astyles';<?php if(!(isset($catCountURL))) echo "selectCountry('All',1);return false;";?>" title="All">All</a>
							 </div>
                            <?php 
                                global $countries; 
            					foreach($countries as $countryId => $country) {?>
						      <div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default">
				<?php		$countryName = isset($country['name']) ? $country['name'] : '';
							$countryValue = isset($country['value']) ? $country['value'] : '';
                                    echo '<script>var SHIKSHA_'. strtoupper($countryId) .'_HOME= "'. constant('SHIKSHA_'.strtoupper($countryId).'_HOME')  .'"; </script>';
							$countryId = isset($country['id']) ? $country['id'] : '';
					?>
							 <div class="quesAnsBullets"><a href="<?php if(isset($catCountURL)){ $catReplace = str_replace('@coun@',$countryId,$catCountURL); echo str_replace('@cat@',$selectedCategory,$catReplace);} else echo "#";?>" onClick = "this.className = 'astyles';<?php if(!(isset($catCountURL))) echo "selectCountry('".$countryName."',".$countryId.");return false;";?>" title="<?php echo $countryName;?> "><?php echo $countryName; ?></a></div>
								</div>
                    <?php } ?>
                             </div>

                             <div id = "cityList" style = "display:none">

                             </div>
                             <div id = "countrycityList" style = "display:none">
                            <?php 
                                global $countries; 
                            foreach($countries as $countryId => $country) {?>
				<?php		$countryName = isset($country['name']) ? $country['name'] : '';
							$countryValue = isset($country['value']) ? $country['value'] : '';
                            $countryId = isset($country['id']) ? $country['id'] : '';
                            if($countryId == 2 && !stristr($_SERVER['REQUEST_URI'],'india'))
                            continue;
                            else
                            {
					?>
						      <div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default">
							 <div><span id = "<?php echo "countryimg" . $countryId?>"><img src = "/public/images/plusSign.gif" border = "0" onClick = "OpenCountrydiv(<?php echo $countryId?>,'<?php echo $countryName?>')"/></span><a href="#" onClick = "selectCountry1('<?php echo $countryName?>',<?php echo $countryId?>,'','');return false;" title="<?php echo $countryName;?> ">&nbsp;<?php echo $countryName; ?></a></div>
                             <div id = "<?php echo "country".$countryId?>" >
                             </div>
								</div>
                    <?php }} ?>
                             <script>
                             function OpenCountrydiv(countryId,country)
                             {
                                 var  cities = cityList[countryId];
                                 var res = '';
                                 var countryName = "country" + countryId;
                                 var countryimg  = "countryimg" + countryId;
                                 document.getElementById(countryimg).innerHTML = '<img src = "/public/images/closedocument.gif" border = "0" onClick = \'CloseCountrydiv('+countryId+',"'+country+'");return false;\'/>';
                                 if(trim(document.getElementById(countryName).innerHTML) == '') {
                                     for(var city in cities){
                                         cityName = cities[city];
                                         var aElement = document.createElement('a');
                                         aElement.setAttribute('href', '#');
                                         aElement.setAttribute('title', cityName);
                                         aElement.setAttribute('countryId', countryId);
                                         aElement.setAttribute('countryName', country);
                                         aElement.setAttribute('cityId', city);
                                         aElement.onclick = function() { elementOnClick(this); } ;
                                         aElement.innerHTML = '&nbsp;'+ cityName;

                                         var divElement = document.createElement('div');
                                         divElement.className = 'quesAnsBullets';
                                         divElement.style.paddingLeft = '25px';
                                         divElement.appendChild(aElement);

                                         document.getElementById(countryName).appendChild(divElement);
                                         document.getElementById(countryName).style.display = 'inline';
                                     }
                                 } else {
                                     document.getElementById(countryName).style.display = 'inline';
                                 }
                             }

                             function elementOnClick(elementObj) {
                                 var countryId = elementObj.getAttribute('countryId');
                                 var countryName = elementObj.getAttribute('countryName');
                                 var cityId = elementObj.getAttribute('cityId');
                                 var cityName = elementObj.getAttribute('title');
                                 selectCountry1(countryName,countryId,cityName,cityId);
                                 return false;
                             }

                             function CloseCountrydiv(countryId,country)
                             {
                                var countryName = "country" + countryId;
                                var countryimg  = "countryimg" + countryId;
                                document.getElementById(countryName).style.display = 'none';
                                document.getElementById(countryimg).innerHTML = '<img src = "/public/images/plusSign.gif" border = "0" onClick = \'OpenCountrydiv('+countryId+',"'+country+'")\'/>';

                              }
                             </script> 

                             </div>
						      <div class="clear_L"></div>
						   </div>
						</div>
				     </div>
				  </div>
		       </div>
	</div>
</div>
<script>

function OpenSubCategories(PanelName)
{
var imgname = "Catimg" + PanelName;
var subname = "subCat" + PanelName;

document.getElementById(subname).style.display = '';
document.getElementById(imgname).innerHTML = '<img src = "/public/images/closedocument.gif" border = "0" onClick = \'hideSubCategories('+PanelName+');return false;\'/>';
}

function hideSubCategories(PanelName)
{
var subname = "subCat" + PanelName;
var imgname = "Catimg" + PanelName;
document.getElementById(subname).style.display = 'none';
document.getElementById(imgname).innerHTML = '<img src = "/public/images/plusSign.gif" border = "0" onClick = \'OpenSubCategories('+PanelName+');return false;\'/>';
}

function selectCategory(categoryName,subcategoryName,categoryId,page)
{
dissolveOverlayHackForIE();
if(subcategoryName != "TestPrep") {
    if(typeof(page) != "undefined") {
        pageName = page;
    }
    subcategoryName = subcategoryName == '' ? 'All' : subcategoryName.replace(' ','-').replace('/','-').replace('--','-');
    var cityId = document.getElementById('cities').value;
    cityId = cityId =='' ? 'All' : cityId;
    window.location = eval(page) + '/'+ document.getElementById('countryNameSelected').value +'/'+ cityId + '/'+ subcategoryName + '/'+ document.getElementById('cityNameSelected').value;
    return;
}
    document.getElementById('categoryName').innerHTML = categoryName;
//document.getElementById('cities').value = '' ;
document.getElementById('commonOverlay').style.display = "none" ;
document.getElementById('subCategoryId').value = categoryId ;
//document.getElementById('citySelected').innerHTML = "All Cities";

if(document.getElementById('subcategoryName'))
{
    if(subcategoryName != '')
    document.getElementById('subcategoryName').innerHTML = ': ' + subcategoryName ;
else
    document.getElementById('subcategoryName').innerHTML = ': All categories'  ;
    }
if(subcategoryName == "TestPrep")
{ 
examId = categoryId;
document.getElementById('testprepInstitutesListStartOffSet').value = 0;
document.getElementById('requiredInstitutesListStartOffSet').value = 0;
getCollegesForExam('testprep','examdirpage');
getCollegesForExam('required','examdirpage');
updateBlogsForCatgeoryPages();
var reqTit = "Institutes that require " + categoryName + " for admission";
var prepTit = "Institutes that prepare for " + categoryName ;
document.getElementById('reqTab').innerHTML = reqTit.substring(0,30); 
document.getElementById('reqTab').title = reqTit; 
document.getElementById('prepTab').innerHTML = prepTit.substring(0,30); 
document.getElementById('prepTab').title = prepTit; 
}
}

</script>
