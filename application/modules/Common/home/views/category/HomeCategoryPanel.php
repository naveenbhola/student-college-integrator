<?php
    $height = 'height:260px;';
    if(isset($subCategories) && is_array($subCategories)) {
        if(count($subCategories)>7) {
            $height = '';
        }else {
            $height = 'height:260px;';
        }
    }
	$adImage = isset($categoryData['adImage']) ? $categoryData['adImage'] :	'/public/images/300x250_bannerfoedu.jpg';

	switch($categoryData['page'])
	{
	case 'FOREIGN_PAGE':
	    $searchType='foreign';
	    $searchCategoryId = 1;
	    if($countrySelected != '' && $countrySelected != 1){
	    	$searchCategoryId = $countrySelected;
	    }
	    break;
	case 'ENTRANCE_EXAM_PREPARATION_PAGE':
	    $searchType='testprep';
	    $searchCategoryId = 1;
	    break;
	case 'INDIA_PAGE':
	    $searchType='Country';
	    $searchCategoryId = 2;
	    break;
	default:
		$searchCategoryId = $categoryData['id'];
	    $searchType='Category';
	}
	$clientWidth =  (isset($_COOKIE['client']) && $_COOKIE['client'] != '') ? $_COOKIE['client'] : 1024;
	$truncateStrLength = ($clientWidth < 1000) ? 16 : 33;
?>
<style>
.raised_category {background: transparent; } 
.raised_category .b1, .raised_category .b2, .raised_category .b3, .raised_category .b4, .raised_category .b1b, .raised_category .b2b, .raised_category .b3b, .raised_category .b4b {display:block; overflow:hidden; font-size:2px;} 
.raised_category .b1, .raised_category .b2, .raised_category .b3, .raised_category .b1b, .raised_category .b2b, .raised_category .b3b {height:2px;} 
.raised_category .b2 {background:#ffffff; border-left:2px solid #CEEDFF; border-right:2px solid #CEEDFF;} 
.raised_category .b3 {background:#ffffff; border-left:2px solid #CEEDFF; border-right:2px solid #CEEDFF;} 
.raised_category .b4 {background:#ffffff; border-left:2px solid #CEEDFF; border-right:2px solid #CEEDFF;} 
.raised_category .b4b {background:#ffffff; border-left:2px solid #CEEDFF; border-right:2px solid #CEEDFF;} 
.raised_category .b3b {background:#ffffff; border-left:2px solid #CEEDFF; border-right:2px solid #CEEDFF;} 
.raised_category .b2b {background:#ffffff; border-left:2px solid #CEEDFF; border-right:2px solid #CEEDFF;} 
.raised_category .b1b {margin:0 6px; background:#CEEDFF;} 
.raised_category .b1 {margin:0 6px; background:#CEEDFF;} 
.raised_category .b2, .raised_category .b2b {margin:0 4px; border-width:0px 2px;} 
.raised_category .b3, .raised_category .b3b {margin:0 2px; border-width:0px 2px;} 
.raised_category .b4, .raised_category .b4b {height:2px; margin:0 1px;} 
.raised_category .boxcontent_category {display:block; background-repeat:repeat-x; border-left:2px solid #CEEDFF; border-right:2px solid #CEEDFF;} 

</style>
<!--[if IE 7]>
<style>
       .searchTP{padding:0px 2px;color:#acacac;position:relative;top:-4px;height:18px;border:solid 1px #acacac;}
</style>
<![endif]-->
	<div>
		<div class="fontSize_16p bld">
			<label class="OrgangeFont" id="categoryCollegesLabel"><h3><span class="myHeadingControl bld" style="font-size:16px">Main Institutes</span></h3></label>
		</div>
	</div>
	<div class="lineSpace_5">&nbsp;</div>
	<?php  $this->load->view('common/subCategoryOverlay');?>
	<div id="blogTabContainerCat">
		<div style="width: 99%;" id="blogNavigationTabCat">
			<ul>
				<li onclick="return showSubCategoryOverlay(this);" class="selected" >	
					<a href="#" onclick="return false;"><label id="selectedSubCategoryText"><?php global $selectedSubCategoryText; echo $selectedSubCategoryText; ?></label> <img src="/public/images/arrowCategoryTab.jpg" border="0" align="absmiddle"/></a>
				</li>
			</ul>
		</div>
	</div>
	<div class="row inline-l">
		<div>
			<div class="row inline-l">
				<div class="raised_category">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div class="boxcontent_category">
							<div class="mar_full_10p" style="height:240px;">
								<div class="normaltxt_11p_blk_arial" style="display:none"><span class="bld">Browse by categories:</span>
									<select class="fontSize_10p" id="categorySelect" onChange="return updateCategoryPageForSubCategory(this.value);" style="width:250px;">
										<option value="<?php echo $allCategoryId; ?>">All</option>
										<?php 
											if(isset($subCategories) && is_array($subCategories)) {
												$otherElementId = '';
												foreach($subCategories as $subCategory) {
													$subCategoryId = $subCategory['boardId'];
													$subCategoryName = $subCategory['name'];
													if(strpos($subCategoryName,'Others..') !== false){
														$otherElementId = $subCategoryId ;
														continue;
													}
													if($subCategoryId == $categoryId) {
														$selected = 'selected';
													} else {
														$selected = '';
													}
										?>
											<option value="<?php echo $subCategoryId; ?>" <?php echo $selected; ?>><?php echo $subCategoryName; ?></option>													 
										<?php
											}
											if($otherElementId != '') {
												if($otherElementId == $categoryId) {
													$selected = 'selected'; 
												} else{
													$selected = '';
												}
										?>														 
												 	<option value="<?php echo $otherElementId ; ?>" <?php echo $selected ;?>>Others..</option>
												 <?php
												 		}
												 	}
												 ?>
										</select>
									</div>
									<?php
										$messageText = '';
										
										$totalResults = $collegeList[0]['total'];
										if($totalResults > 0) {
											$startNum = 1;
											$endNum = 10;
											$endNum = $endNum > $totalResults ? $totalResults : $endNum;
											$messageText = 'Showing '. $startNum  . ' - '. $endNum .' institutes out of '. $totalResults;
										} else {
											$messageText = 'No institutes Available.';
										}
									
									?>
									
									<div  align="left"><label style="position:relative;top:8px;" id="categoryCollegesCountLabel"><?php echo $messageText; ?></label>&nbsp;&nbsp;</div>
									<div class="lineSpace_15">&nbsp;</div>
									<div class="fontSize_12p">
										<div>
											<div id="collegeListPlace">
											<?php 
												foreach($collegeList[0]['institutes'] as $college){
													if(empty($college['id'])) {  continue; }
													$collegeId 		= $college['id'];
													$collegeName 	= ucwords($college['title']);
													$collegeCity	= $college['locationArr'][0]['city_name'];
													$collegeCountry = $college['locationArr'][0]['country_name'];
													if($countrySelected != '') {
														for($locationCount = 0; $locationCount < count($college['locationArr']); $locationCount++){
															if($college['locationArr'][$locationCount]['country_id'] == $countrySelected) {
																$collegeCity	= $college['locationArr'][$locationCount]['city_name'];
																$collegeCountry = $college['locationArr'][$locationCount][$country_name];
																break;
															}
														}
													}
													$url = $college['url'];
													$location = $collegeCity;
													if($collegeCity != '' && $collegeCountry!= '') {
														$location .= ' - ';
													}
													$location .= $collegeCountry;
													$sponsoredResult = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'check.gif' : 'grayBullet.gif' ;
													$truncateStrLengthForRecord = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? $truncateStrLength-3 : $truncateStrLength;
													$sponsoredResultMargin = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'margin-left:-6px' : '' ;
													$collegeDisplayName = strlen($collegeName) > $truncateStrLengthForRecord ? substr($collegeName, 0, $truncateStrLengthForRecord - 3) .'...' : $collegeName;
													$locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
													$locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
													$courseId = $college['courseArr'][0]['course_id'];
													$courseUrl = html_entity_decode($college['courseArr'][0]['url']);
													$courseName = html_entity_decode($college['courseArr'][0]['title']);
													$courseNameDisplayText = strlen($courseName) > $truncateStrLengthForRecord ? substr($courseName, 0, $truncateStrLengthForRecord - 3) .'...' : $courseName;
													$courseNameDisplayText = $courseNameDisplayText=='' ? '&nbsp;' : $courseNameDisplayText;
																				
											?>		
											<div class="w49_per float_L">
												<span class="normaltxt_11p_blk fontSize_12p arial">
													<div class="row">
															<div class="float_L" style="padding:5px 5px 5px 0px; <?php echo $sponsoredResultMargin;?>"><img src="/public/images/<?php echo $sponsoredResult; ?>" align="absmiddle"/></div>
															<div class="float_L">
															<div class="normaltxt_11p_blk_arial">
																<a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $collegeName ;?>"><b><?php echo $collegeDisplayName; ?></b></a>
															</div>
															<div class="normaltxt_11p_blk_arial">
																<a href="<?php echo $courseUrl; ?>" title="<?php echo $courseName; ?>" class="blackFont"><?php echo $courseNameDisplayText; ?></a>
															</div>
															<div class="lineSpace_10">&nbsp;</div>
															</div><div class="clear_L"></div>
													</div>
												</span>
											</div>
												<?php } ?>
											
											</div>
											<div class="clear_L"></div>
										</div>
									</div>
								</div>
								<input type="hidden" id="subCategoryId" name="subCategoryId" value="<?php echo $categoryId; ?>"/>
								
								<div class="lineSpace_5">&nbsp;</div>
								<div class="mar_right_10p" align="right">
									<div id="pagingIDc">
									<!--Pagination Related hidden fields Starts-->
									<input type="hidden" id="startOffSet" value="0"/>
									<input type="hidden" id="countOffset" value="10"/>
									<input type="hidden" id="methodName" value="getFeaturedCollegesForCatgeoryPages"/>
									<!--Pagination Related hidden fields Ends  -->
										<div id="paginataionPlace1"></div>
										<div  id="paginataionPlace2" style="display:none"></div>
									</div>
								</div>
								<div class="lineSpace_8">&nbsp;</div>	
								
							</div>
							<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
					</div>
				</div>				
				<div class="clear_R"></div>
			</div>