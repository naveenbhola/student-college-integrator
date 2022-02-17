<?php 	$footerComponents = array(
			'js'                => array('studyAbroadCategoryPage','json2'),
			'asyncJs'           =>array('json2')
		);
	$this->load->view('common/studyAbroadFooter',$footerComponents); ?>
<div style="position: fixed;top: 0px; left: 0px; opacity: 0.7; background: url('//<?php echo IMGURL; ?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFull"></div>

<script>
var bannerURL,data = {};
var pageTitle = '<?=$catPageTitle?>';
<?php if($categoryPageRequest->isExamCategoryPage()){ ?>
    var isExamCategoryPage = true;
    var pageLoadExamMinScore = '<?=$categoryPageRequest->examScore[0]?>';
    var pageLoadExamMaxScore = '<?=$categoryPageRequest->examScore[1]?>';
    <?php if(count($resultantUniversityObjects) == 0){ ?>
        var isExamCategoryScorePageWithZeroResults = true;
        $j("[name='examsMinScore[]']").attr("disabled","disabled");
        $j("[name='examsScore[]']").attr("disabled","disabled");
    <?php }else{ ?>
        var categoryPageExamId = '<?=$categoryPageRequest->examId?>';
        var isExamCategoryScorePageWithZeroResults = false;
        <?php if($minScoreSelectFilled === false && $categoryPageRequest->isExamCategoryPageWithScore){ ?>
            ascendMinExamScoreFilter(pageLoadExamMinScore);
        <?php } ?>
        <?php if($maxScoreSelectFilled === false && $categoryPageRequest->isExamCategoryPageWithScore){ ?>
            descendMaxExamScoreFilter(pageLoadExamMaxScore);
        <?php } ?>
    <?php } ?>
<?php }else{ ?>
    var isExamCategoryPage = false;
    var isExamCategoryScorePageWithZeroResults = false;
    var pageLoadExamMinScore = 0;
    var pageLoadExamMaxScore = 0;
<?php } ?>
$j(window).on('load',function() {
			data['loadFiltersViaSolrForSponsoredFlag'] = <?php echo ($categoryPageRequest->useSolrToBuildCategoryPage() === true /*&&
																		$categoryPageRequest->getPageNumberForPagination() == 1*/
																		?1:0);?>;
			data['isZeroResultPage'] = '<?php echo $isZeroResultPage; ?>';
			data['getFlagToGetCertDiplomaResults'] = '<?php echo $categoryPageRequest->getFlagToGetCertDiplomaResults()?1:0; ?>';
			// remove the help text from all country category page after 10 secs
			data['showGutterHelpText'] = '<?php echo $showGutterHelpText; ?>';
			data['showGutterHelpTime'] = '<?php echo $showGutterHelpTime; ?>';
			data['bannerURL'] = '<?php echo (is_object($banner)?$banner->getURL():""); ?>';
			initializeCategoryPage(data);
            initializeSliderCat();
});
</script>
