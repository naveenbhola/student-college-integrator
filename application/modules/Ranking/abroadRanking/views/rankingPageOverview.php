<?php
if($isAjaxCall == 1)
{
    $this->load->view("abroadRanking/rankingPageOnFilterApply");
    exit;
}

if($isSortAJAXCall == 1)
{
    $this->load->view("abroadRanking/rankingPageOnSorterApply");
    exit;
}

$headerComponents = array(
			    'css'               => array('studyAbroadCommon', 'studyAbroadRanking'),
			    'canonicalURL'      => $seoData["canonicalUrl"],
			    'title'             => ucfirst($seoData["seoTitle"]),
			    'metaDescription'   => ucfirst($seoData["seoDescription"]),
			    'metaKeywords'      => ucfirst($seoData['seoKeywords']),
				'pgType'	        => 'rankingPage',
                'pageIdentifier'    => $beaconTrackData['pageIdentifier']
			);
$this->load->view('common/studyAbroadHeader', $headerComponents);
echo jsb9recordServerTime('SA_RANKING_PAGE',1);
?>
<script> 
var filterSelectedOrder = <?php if(!empty($filterSelectionOrder))echo $filterSelectionOrder; else echo '{}';?>; 
    var highestOrder    = <?php if(!empty($highestSelectionOrder))echo $highestSelectionOrder; else echo '1';?>; 
     
</script>
<!-- breadcrumb -->
<?php $this->load->view('rankingPageBreadcrumb');?>
<!-- END :: breadcrumb -->
<div class="content-wrap clearfix">
    <!-- ranking page title & change country link + layer -->
    <?php $this->load->view("rankingPageTitle"); ?>
    <!-- END :: ranking page title & change country link + layer -->
    
    <!-- filters -->
    <?php
    if($rankingPageObject->getType() != 'university'){
	  // $this->load->view('rankingPageFilters');
    }
    ?>
    <!-- END :: filters -->
    
    <div id="nav-bar-sticky" class="shortlist-nav-bar clearwidth" style="<?php if($rankingPageObject->getType() == 'university') echo "padding-top:0px;"?>" >
    <?php
        //your selection section
	 if($rankingPageObject->getType() != 'university'){
        $this->load->view('rankingPageYourSelection');
	 }
        //navigation between results & shortlisted courses
        $this->load->view('rankingPageNavSection');?>
    </div>
    <!-- ranking table (coures/university)-->
    <div id="dataTuples">
    <?php 
	if($rankingPageObject->getType() == 'course'){
	    $this->load->view('rankingPageCourseTable');
	}
	else{
	    $this->load->view('rankingPageUniversityTable');
	}
    ?>
    </div>
    <div class="clearwidth" style="margin:20px 0; color: #666; font-size:12px;line-height:18px;">
	Source:The above ranking is specially formulated to cater to the needs of Indian students and is based on a combination of publicly available data, internal Shiksha algorithm and in-house expertise of the Shiksha team. For any questions, you can write to us at <a href="mailto:studyabroad@shiksha.com">studyabroad@shiksha.com</a>
    </div>
    
    <!-- END :: ranking table (course/university)-->

    <!-- ranking table pagination-->
    <?php //$this->load->view('rankingPagePagination'); ?>   
    <!-- END :: ranking table pagination-->
    
    <!--<a href="#" class="backtop-btn"><i class="common-sprite bcktop-icon"></i><span>Back to top</span></a>-->
</div>
<?php
	$footerComponents = array(
			    'js'                => array('studyAbroadRankingPage','json2'),
                'isCountryPage'		=>1
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
    $this->load->view('rankingPageFooter');
?>
