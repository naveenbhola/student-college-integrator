<!--Start_SearchPanel-->
    <div style="width:100%">
        	<div style="width:100%">
                <div style="margin-left:20px">
                	<div style="width:100%" class="float_L">
                            <!--Start_CategoryPage_Title-->
                            <div style="width:100%">
                                    <a href="#" onClick = "drpdwnOpenS(this, 'overlayCategoryHolder');return false;"><h1><span class="Fnt18" style="color:#000"><?php if (trim($course_page_heading) != ''){ echo $course_page_heading; } else {echo $categoryData['name'];}?>&nbsp;<!--<span class="orangeColor Fnt12">&#9660;</span>--></span>
                                    <?php if ( trim($show_subcategory) == 'yes') { ?><span class="Fnt14" style = "color:#000"><b><?php if($subCategorySelected) echo "- ".$subCategorySelected; ?></b></span><?php } ?></h1>
                                    <?php if ( trim($show_subcategory) == 'yes') {?>
                                    <span style="font-size:12px;font-weight:400">
                                        <span class="orangeColor">&#9660;</span>
                                      
		                            </span>
                                    <?php } ?>
                                    </a><a href="#" onclick = "showlocationlayer(<?php echo $openOverlay;?>);return false;" style=""><h2><span class="Fnt13" style="color:#000"> <?php if ( trim($show_subcategory) == 'yes') {?>:&nbsp;<?php } else{ ?> - <?php } echo $cityNameSelected?></span></h2>
                                    <span style="font-size:12px;font-weight:400">
                                    <span class="orangeColor">&#9660;</span>
                                    </span></a>
                             </div>
                            <!--End_CategoryPage_Title-->
                    </div>
                </div>
                <div class="clear_L">&nbsp;</div>
				<div class="defaultAdd lineSpace_10">&nbsp;</div>
				<div style="margin-left:8px">
				<?php
				if($course_page_heading){
					$pageZone = str_replace("__","_",str_replace(" ","_",$course_page_heading));
				}else
				{
					if($categoryData['id'] == 3  && $pagename == "topinstitutes"){
						$pageZone = "MBA";
					}else if($categoryData['id'] == 10  && $pagename == "topinstitutes"){
						$pageZone = "MCA";
					}else{
						$pageZone = str_replace("__","_",str_replace(" ","_",$subCategorySelected));
					}
				}
				$bannerProperties = array('pageId'=>'NAUKRI', 'pageZone'=>$pageZone.'_SCRAPPER', 'shikshaCriteria' => $criteriaArray,'width'=>973,'height'=>200);
		        $this->load->view('common/banner',$bannerProperties);
			  ?>
				<script>
					function showSideBannerForNaukrilearning() {
						var mainBanner = $('<?=$pageZone.'_SCRAPPER' ?>');
						mainBanner.src = bannerPool['<?=$pageZone.'_SCRAPPER'?>'];
					}
					showSideBannerForNaukrilearning();
				</script>
				</div>
            </div>
        </div>
    <!--End_SearchPanel-->
    <style>
    .ad_float_on{width:760px; height:100px;}
    .ad_float_off{width:220px; height:100px;}
    </style>
    <div style="display:none">
	    <?php $this->load->view('home/homePageRightSearchPanel');?>
    </div>
	<div id = "imagespace" style="display:none">
	<div id="subCatagories">
	</div>

    </div>
	<script>
	function drpdwnOpenS(which, divId){
		var objElement = which;
		var objDropdown = $(divId);
		var objElementTop =obtainPostitionY(objElement);
		var objElementLeft = obtainPostitionX(objElement);
		objDropdown.style.left = objElementLeft - 1 +'px';
		objDropdown.style.top = (objElementTop+20-2)+'px';
		objDropdown.style.display = 'block';
		objDropdown.style.margin = '0px';
		objDropdown.style.zIndex= 1400;
		objDropdown.style.left = objElementLeft - 1 +'px';
		setTimeout('overlayHackLayerForIE("'+divId+'", $("'+divId+'"));',1);
	}
	</script>
