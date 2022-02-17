<!--Start_courses_category_Box-->
<script>

function showSubcategoties(objElement, categoryId){
	var overlayElement = document.getElementById('subcategoryDiv');
	overlayElement.style.display = 'block';
	if(objElement) {
		objElement = document.getElementById(objElement);
		overlayElement.style.top = obtainPostitionY(objElement) -5 +'px';
		overlayElement.style.left = obtainPostitionX(objElement)+ (objElement.offsetWidth+15) +'px';
		if(categoryId) {
			var urlName =  objElement.getAttribute('url');
			var url = objElement.href ;
			document.getElementById('subcategoryDivContent').innerHTML = getSubCategories(url, categoryId, urlName);
		}
	}
	overlayHackLayerForIE('subcategoryDiv', overlayElement);
}

function hideSubcategoties(objElement){
	objElement = 'subcategoryDiv';
	document.getElementById(objElement).style.display = 'none';
	dissolveOverlayHackForIE();
}

function getSubCategories(url, id, urlName) {
	var subCategoryHtml = '';
	var catergoryHtml = '';
	if(url.indexOf('getCategoryPage/colleges') < 0 ) {
		url += 'getCategoryPage/colleges';
	}
    if(url.indexOf('//'+urlName) > 0) {
        url += '/' + urlName;
    }
	for(var categoryCount = 0; categoryCount < categoryList.length; categoryCount++) {
		if(id == categoryList[categoryCount].parentId) {
			var categoryUrl = url + '/All/All/'+ categoryList[categoryCount].urlName;
			var categoryRow =  '<div style="margin-bottom:5px;"><a href="'+ categoryUrl +'" title="'+ categoryList[categoryCount].categoryName +'">'+ categoryList[categoryCount].categoryName +'</a></div>';
			if(categoryList[categoryCount].categoryName.toLowerCase().indexOf('other') == 0) {
				catergoryHtml += categoryRow;
			} else {
				subCategoryHtml += categoryRow;
			}
		}
	}
	catergoryHtml = subCategoryHtml + catergoryHtml;
	return catergoryHtml;
}

</script>
<div style="display:inline; float:left; width:100%">
	<div class="normaltxt_11p_blk fontSize_13p OrgangeFont arial">
	<div class="lineSpace_5">&nbsp;</div>
		<h1><span class="mar_left_10p myHeadingControl bld">Search Institutes by</span></h1>
	</div>
		<div id="blogTabContainer">
			 <div class="float_R mar_right_10p arial" style="margin-top:8px;">&nbsp;
		     </div>
			<div id="blogNavigationTab">
				<ul>
					<li container="browseCollege" tabName="browseCollegeCareer" class="selected" onClick="return selectHomeTab('browseCollege','Career');">
						<a href="#" title="Career Options">Career Options</a>
					</li>
					<li container="browseCollege" tabName="browseCollegeCountry" class="" onClick="return selectHomeTab('browseCollege','Country');">
						<a href="#" title="Countries">Countries</a>						
					</li>
					<li container="browseCollege" tabName="browseCollegeTestPrep" class="" onClick="return selectHomeTab('browseCollege','TestPrep');">
						<a href="#" title="Test Preparation">Test Preparation</a>
					</li>
				</ul>
			</div>
			<div class="clear_L"></div>
		</div>
	
	<div class="raised_lgraynoBG">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">									
			<!-- CategoryPlace Ends -->						
					<div class="lineSpace_5">&nbsp;</div>
					<div class="normaltxt_11p_blk mar_full_10p">
							<div id="browseCollegeCareerBlock" class="row">
								<div class="float_R" style="width:46%">
									<div>
										<div class="catArtLaw" onmouseover="showSubcategoties('homeCategory1',9);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory1" href="<?php echo SHIKSHA_ARTS_HOME; ?>" class="fontSize_12p" url="arts" title="Arts, Law and Languages">Arts, Law and Languages</a>
										</div>
									</div>
									<div>
										<div class="catHospitality" onmouseover="showSubcategoties('homeCategory2', 6);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory2" href="<?php echo SHIKSHA_HOSPITALITY_HOME; ?>" class="fontSize_12p" url="hospitality" title="Hospitality, Tourism and Aviation">Hospitality, Tourism and Aviation</a>
										</div>
									</div>
									<div>
										<div class="catManagement" onmouseover="showSubcategoties('homeCategory3', 3);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory3" href="<?php echo SHIKSHA_MANAGEMENT_HOME; ?>" class="fontSize_12p" url="management" title="Management and Business">Management and Business</a>
										</div>
									</div>
									<div>
										<div class="catMedicine" onmouseover="showSubcategoties('homeCategory4',5);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory4" href="<?php echo SHIKSHA_MEDICINE_HOME; ?>" class="fontSize_12p" url="medicine" title="Medicine and Health Care">Medicine and Health Care</a>
										</div>
									</div>
									<div>
										<div class="catRetail" onmouseover="showSubcategoties('homeCategory5',11);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory5" href="<?php echo SHIKSHA_RETAIL_HOME; ?>" class="fontSize_12p" style="margin-right:30px" url="retail" title="Retail">Retail</a>
										</div>
									</div>
									<div style="display:none">
										<div class="catMiscellaneous" onmouseover="showSubcategoties('homeCategory12',149);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory12" href="<?php echo SHIKSHA_MISCELLANEOUS_HOME; ?>" class="fontSize_12p" style="margin-right:30px" url="miscellaneous" title="Miscellaneous">Miscellaneous</a>
										</div>
									</div>
								</div>
								<div style="width:46%">
									<div>
										<div  class="catAnimation" onmouseover="showSubcategoties('homeCategory6',12);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory6" href="<?php echo SHIKSHA_ANIMATION_HOME; ?>" class="fontSize_12p" url="animation" title="Animation, Multimedia">Animation, Multimedia</a>
										</div>
									</div>
									<div>
										<div class="catBanking" onmouseover="showSubcategoties('homeCategory7',4);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory7" href="<?php echo SHIKSHA_BANKING_HOME; ?>" class="fontSize_12p" url="banking" title="Banking &amp; Finance, Accounting">Banking &amp;  Finance, <span style="margin-left:1px">Accounting</span></a>
										</div>
									</div>
									<div>
										<div class="catInformation" style="padding-bottom:10px;" onmouseover="showSubcategoties('homeCategory8',10);" onmouseout="hideSubcategoties(this,10);">
											<a id="homeCategory8" href="<?php echo SHIKSHA_IT_HOME; ?>" class="fontSize_12p" url="it" title="Information Technology">Information Technology</a>
										</div>
									</div>
									<div>
										<div class="catMedia" onmouseover="showSubcategoties('homeCategory9',7);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory9" href="<?php echo SHIKSHA_MEDIA_HOME; ?>" class="fontSize_12p" url="media" title="Media, Films, Mass Communications">Media, Films, Mass Communications</a>
										</div>
									</div>
									<div>
										<div class="catProfessioanl" onmouseover="showSubcategoties('homeCategory10',8);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory10" href="<?php echo SHIKSHA_PROFESSIONALS_HOME; ?>" class="fontSize_12p" url="professionals" title="Professional Courses">Professional Courses</a>
										</div>										
									</div>
									<div>
										<div class="catScience" onmouseover="showSubcategoties('homeCategory11',2);" onmouseout="hideSubcategoties(this);">
											<a id="homeCategory11" href="<?php echo SHIKSHA_SCIENCE_HOME; ?>" class="fontSize_12p" url="science" title="Science and Engineering">Science and Engineering</a>
										</div>
									</div>
								</div>
								<div class="clear_R"></div>
							</div>
							
							
			<div id="browseCollegeCountryBlock" class="inline" style="display:none">
				<div class="normaltxt_11p_blk fontSize_12p arial mar_full_10p">
					<?php
						global $countries;
						foreach($countries as $countryId => $country) {
                            if(strtoupper($countryId) == 'INDIA') continue;
							$countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
							$countryName = isset($country['name']) ? $country['name'] : '';
							$linkUrl = constant('SHIKSHA_'. strtoupper($countryId) .'_HOME');
							
					?>
					
					<div class="float_L w47_per" style="margin-right:10px; margin-bottom:10px;">
	    				<a href="<?php echo $linkUrl; ?>" class="fontSize_12p" title="Education Information - <?php echo $countryName;?>">
							<div class="f<?php echo str_replace(' ','',$countryName);  ?>" title="Education Information <?php echo $countryName;?>"><?php echo $countryName; ?></div>
						</a>
	    			</div>
	    			<?php 
                        }	
                    ?>
						<div class="clear_L"></div>
					</div>
                </div>
<!-- COuntry ENds -->

<!-- Test Prep -->
            <div id="browseCollegeTestPrepBlock" class="inline" style="display:none">
            <?php global $TestPrepExams ;?>
				<div class="normaltxt_11p_blk fontSize_12p arial">
					<?php for($i = 0;$i<count($TestPrepExams);$i++) { ?>
						<div class="float_L w47_per" style="margin-right:10px; margin-bottom:5px;">
							<div>
								<a href="<?php echo SHIKSHA_TESTPREP_HOME .'/shiksha/testprep/'.$TestPrepExams[$i]['blogId'].'/'.$TestPrepExams[$i]['blogTitle']; ?>" class = "fontSize_12p" title="<?php echo $TestPrepExams[$i]['blogTitle'];?>"><?php echo $TestPrepExams[$i]['blogTitle']; ?></a>
							</div>
                        </div>
                    <?php } ?>
					<div class="clear_L"></div>
				</div>
			</div>
<!-- Test Prep Ends -->
			</div>
			<div class="lineSpace_1">&nbsp;</div>
		</div>
    	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>

</div>
<div id="subcategoryDiv" onmouseover="showSubcategoties();" onmouseout="hideSubcategoties();" class="subCategoryOverlay" style="z-index:500;">
	<div class="inline-l subOptionArrow" style="position:relative;top:0px;left:-16px;"></div>
	<div  class="inline-l" id="subcategoryDivContent" style="padding:5px 5px 5px 0px;"></div>
	<div class="clear_L"></div>
</div>
<script>var categoryList = eval(<?php echo $categoryList; ?>);</script>
<!--End_courses_category_Box-->
