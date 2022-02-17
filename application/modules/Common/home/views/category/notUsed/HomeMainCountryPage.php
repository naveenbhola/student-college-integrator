<?php
$locationSelected = ucwords($cityNameSelected == 'All' ? '' : $cityNameSelected) . ' ' . ucwords($countryNameSelected);
$abroadText = '';
if ($countryNameSelected == 'India' && $cityNameSelected == '') {
	$abroadText = '& Abroad';
}
	$titleText = 'Study Abroad - Foreign Colleges Universities - Study in Canada - Singapore - USA - Australia - New Zealand - United Kingdom - Germany';
	$metaDescriptionText = 'Find information for Study Abroad, Foreign Colleges Universities, Study in Canada, Singapore, USA, Australia, New Zealand, United Kingdom, and Germany. Shiksha lists study abroad colleges and universities along with career options, courses in Canada, Singapore, USA, Australia, New Zealand, UK and Germany.';
	$metaKeywordsText = 'study abroad,  foreign colleges universities, study abroad program, study abroad scholarships, education study abroad, study abroad courses, study abroad usa, study abroad university, study abroad tips, study abroad Australia, study abroad information, international study abroad programs, Singapore, USA, Australia, New Zealand, UK, Germany';

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
		'pageZone' => $countryData['page'] . '_TOP'
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
	<div><h1><span class="OrgangeFont bld fontSize_18p">Study Abroad</span></h1></div>
	<div class="lineSpace_5">&nbsp;</div>
</div>
<div class="mar_full_10p" style="">
	<div>
<?php
foreach ($countryMap as $countryMapElementId => $countryListElement) {
	foreach($countryListElement as $countryListElementKey => $countryListElementValue) {
		$$countryListElementKey = $countryListElementValue;
	}
    if($countryMapElementId == 2) { continue; }
    $resultKey = $countryMapElementId;
    if(!is_array($snippets) && isset($snippets[0]) && (count($snippets[0]) < 1)) { continue; }
    $itemSelectionKey = rand(0,count($snippets[0]) - 1);
    $snippet = $snippets[0][$itemSelectionKey];
    if(!is_array($snippet)) { continue; }
    $parentUrl = '/shiksha/showCountryContentPage/All/'.$urlName.'/All/All/'.$itemSelectionKey;
	$imgName = '/public/images/'.strtolower(str_replace(' ','',$urlName)).'_flg.gif';
	$snippetBlogUrl = $snippet['url'];
?>
		<div class="float_L" style="width:49%; margin:0 4px">
			<div class="">
				<div class="careerOptionPanelBrd">
					<div class="careerOptionPanelStudyAbroad">
					<a title="<?php echo $name; ?>" href="<?php echo $parentUrl; ?>"><img src="<?php echo $imgName; ?>" align="absmiddle" border="0" /> <a href="<?php echo $parentUrl; ?>" title="<?php echo $name; ?>"><?php echo $name; ?></a></div>
					<div class="lineSpace_10">&nbsp;</div>
					<div class="pd_full_0_10">
						<div style="height:115px">
							<div class="float_L"><a title="<?php echo $name; ?>" href="<?php echo $parentUrl; ?>"><img src="<?php echo $snippet['blogImageURL']; ?>" border="0" /></a></div>
							<div style="margin-left:180px"><a href="<?php echo $parentUrl; ?>" title="<?php echo $name; ?>" class="blackFont"><?php echo substr((($snippet['summary'] != '') ? $snippet['summary'] : strip_tags($snippet['blogText'])), 0, 300); ?></a></div>
							<div class="clear_L"></div>
							<div class="txt_align_r"><a href="<?php echo $snippetBlogUrl; ?>">Read More..</a></div>
							<div class="lineSpace_10">&nbsp;</div>
							<!--</div>-->
						</div>
						<div class="bld careerSubHeadingColor fontSize_12p">Career Options in <?php echo $name; ?></div>
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
                                        $childUrl= '/shiksha/showCountryContentPage/'. $subCategoryUrlName .'/'.$urlName.'/All/All/'.$itemSelectionKey;
								?>
								<?php echo $seperator; ?><a href="<?php echo $childUrl; ?>" class="fontSize_12p" title="<?php echo $subCategoryName; ?>"><?php echo $subCategoryName; ?></a>
								<?php									
										$seperator =  ', ';
									}
								?>
						</div>
						<div class="lineSpace_10">&nbsp;</div>												
						<div class="brd careerOptionPanelShowInfo">
							<div class="lineSpace_5">&nbsp;</div>
                        <?php 
                            $criteria = array('countryId'=>$resultKey);
                            $productsCount = array('resultKey'=>$resultKey, 'productsCount' => $productsCountForCountry, 'criteria' => $criteria, 'pageType'=>'country', 'countryName' => $urlName);
                            $this->load->view('home/category/HomeMainPagesProductsCountWidget', $productsCount); 
                        ?>
					    </div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				</div>
			</div>			
						<div class="lineSpace_15">&nbsp;</div>
		</div>
        <?php
            }
        ?>
		
		<div class="clear_L"></div>
	</div>
</div>

				<div class="lineSpace_20">&nbsp;</div>
<?php
    $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
