<?php
$locationSelected = ucwords($cityNameSelected == 'All' ? '' : $cityNameSelected) . ' ' . ucwords($countryNameSelected);
$abroadText = '';
if ($countryNameSelected == 'India' && $cityNameSelected == '') {
	$abroadText = '& Abroad';
}
if ($subCategorySelected != '') {
	$titleText = 'Shiksha.com - ' . ucwords($subCategorySelected) . ' Colleges University Courses Institutes Admission Scholarships in ' . $locationSelected . ' ' . $abroadText . ' - Browse ' . ucwords($cityNameSelected == 'All' ? '' : $cityNameSelected) . ' ' . ucwords($subCategorySelected) . ' Education Career Information - Search College - Study Abroad';
	$metaDescriptionText = 'Browse list of ' . ucwords($subCategorySelected) . ' colleges, universities, courses, institutes in ' . $locationSelected . '.' . $abroadText . ' and get information on ' . ucwords($cityNameSelected . ' ' . $subCategorySelected) . ' education and career prospects in Shiksha.com Now! Find list of engineering, MBA, Medical colleges, university, institutes, courses, schools. Find info on Foreign University, question and answer in education and career forum. Ask the education and career counsellors.';
	$metaKeywordsText = 'Shiksha, ' . strtolower($categoryData['name']) . ',' . strtolower($subCategorySelected) . ', education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships,' . $locationSelected;
} else {
	$titleText = 'Shiksha.com – ' . ucwords($categoryData['name']) . ' Colleges University Courses Institutes Admission Scholarships in ' . $locationSelected . ' ' . $abroadText . ' -  Browse ' . ucwords($cityNameSelected == 'All' ? '' : $cityNameSelected) . ' ' . ucwords($categoryData['name']) . ' Education Career Information - Search College - Study Abroad';
	$metaDescriptionText = 'Find information on ' . ucwords($categoryData['name']) . ' colleges, university, courses, institutes ' . $locationSelected . '.' . $abroadText . ' . Find course details admission details of ' . ucwords($categoryData['name']) . ' colleges. Search the list of colleges and get info on their courses. Find Now!';
	$metaDescriptionText = 'Browse list of ' . ucwords($categoryData['name']) . ' colleges, universities, courses, institutes in ' . $locationSelected . '.' . $abroadText . ' and get information on ' . ucwords($cityNameSelected . ' ' . $categoryData['name']) . ' education and career prospects in Shiksha.com Now! Find list of engineering, MBA, Medical colleges, university, institutes, courses, schools. Find info on Foreign University, question and answer in education and career forum. Ask the education and career counsellors.';
	$metaKeywordsText = 'Shiksha, ' . strtolower($categoryData['name']) . ', education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships,' . $locationSelected;
}

$headerComponents = array (
	'css' => array (
		'raised_all',
		'header',
		'mainStyle'
	),
	'js' => array (
		'common',
		'prototype',
		'cityList',
		'home',
		
	),
	'title' => $titleText,
	'taburl' => site_url(),
	'bannerProperties' => array (
		'pageId' => 'CATEGORY',
		'pageZone' => $categoryData['page'] . '_TOP'
	),
	'tabName' => 'Event Calendar',
	'product' => 'home',
	'displayname' => (isset ($validateuser[0]['displayname']) ? $validateuser[0]['displayname'] : ""),
	'metaKeywords' => $metaKeywordsText,
	'metaDescription' => $metaDescriptionText
);
$this->load->view('common/homepage', $headerComponents);
?>
<div class="mar_full_10p">
	<div class="OrgangeFont bld fontSize_18p">Career Options</div>
	<div class="lineSpace_5">&nbsp;</div>
</div>
<?php
foreach ($categoryTree  as $categoryTreeNodeId  => $categoryTreeNode) {
	foreach($categoryTreeNode as $categoryTreeNodeKey => $categoryTreeNodeValue) {
		$$categoryTreeNodeKey = $categoryTreeNodeValue;
	}
    $resultKey = $categoryTreeNodeId;
    if(!is_array($snippets) && isset($snippets[0]) && (count($snippets[0]) < 1)) { continue; }
    $itemSelectionKey =  rand(0,count($snippets[0]) - 1);
    $snippet = $snippets[0][$itemSelectionKey];
    if(!is_array($snippet)) { continue; }
    $parentUrl = '/shiksha/showCategoryContentPage/'. $urlName .'/All/All/All/'.$itemSelectionKey ;
	$snippetBlogUrl = $snippet['url'];
?>
<div class="mar_full_10p normaltxt_11p_blk_arial">		
		<!--LeftPanel-->
		<div class="careerOptionPanelBrd" style="margin-bottom:10px">		
			<div class="careerOptionPanelHeaderBg"><a href="<?php echo $parentUrl; ?>" title="<?php echo $name; ?>"><?php echo $name; ?></a></div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="careerMargin_6">
				<div>
					<div class="float_R brd careerOptionPanelRightBg" style="width:380px;">
						<div class="lineSpace_5">&nbsp;</div>
                        <?php 
                            $criteria = array('categoryId' => $resultKey);
                            $productsCount = array('resultKey'=>$resultKey, 'productsCount' => $productsCountForCategory, 'criteria'=>$criteria, 'pageType'=>'category', 'categoryUrlName' => $urlName);
                            $this->load->view('home/category/HomeMainPagesProductsCountWidget', $productsCount); 
                        ?>
				    </div>
					<div style="margin-right:410px;">
							<div style="height:100px">
								<!--<div class="science_Eng" style="background:url(<?php echo $snippet['blogImageURL']; ?>) no-repeat 0 0">-->
								<div class="float_L"><a href="<?php echo $parentUrl; ?>" title="<?php echo $name; ?>"><img src="<?php echo $snippet['blogImageURL']; ?>" border="0" /></a></div>
								<div style="margin-left:180px"><a href="<?php echo $parentUrl; ?>" title="<?php echo $name; ?>" class="blackFont"><?php echo substr((($snippet['summary'] != '') ? $snippet['summary'] : strip_tags($snippet['blogText'])), 0, 300); ?></a></div>
								<div class="clear_L"></div>
								<div class="txt_align_r"><a href="<?php echo $snippetBlogUrl; ?>">Read More..</a></div>
								<div class="lineSpace_10">&nbsp;</div>
							</div>
							<div class="bld careerSubHeadingColor">Career Options in <?php echo $name; ?></div>
							<div class="lineSpace_3">&nbsp;</div>
							<div class="dottedLine"><img src="/public/images/dotted.gif" /></div>
							<div class="lineSpace_5">&nbsp;</div>
							<div>
								<?php
									$seperator = '';
									$subCategory = array();
									$others = '';
									foreach($subCategories as $subCategory) {
										$subCategoryName = $subCategory['name'];
										$subCategoryId = $subCategory['boardId'];
                                        $subCategoryUrlName = $subCategory['urlName'];
                                        $childUrl = '/shiksha/showCategoryContentPage/'. $urlName .'/All/All/'.$subCategoryUrlName .'/'. $itemSelectionKey;
										if(stripos($subCategoryName,'Others')!== false) {
											$others = $subCategory;
                                            $othersUrl = $childUrl;
											continue;
										}
								?>
								<?php echo $seperator; ?>
								<a href="<?php echo $childUrl; ?>" class="quesAnsBullets fontSize_12p" title="<?php echo $subCategoryName; ?>" style="display:block; width:30%; float:left">
									<?php echo $subCategoryName; ?>
								</a>
								<?php $seperator =  ''; } ?>
								<?php echo $seperator; ?>
								<a href="<?php echo $othersUrl; ?>" class="quesAnsBullets fontSize_12p" title="<?php echo $others['name']; ?>">
									<?php echo $others['name']; ?>
								</a>
							</div>
						<!--</div>-->
					</div>
				</div>
				<div class="lineSpace_8">&nbsp;</div>
			</div>
		</div>
		<!--End_LeftPanel-->
</div>
<?php
}
?>
				<div class="lineSpace_20">&nbsp;</div>
<?php
    $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
