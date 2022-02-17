<?php 
$title = (isset($seoDetails['title'])) ? $seoDetails['title'] : 'College Comparison Tool, compare colleges, universities and institutes';
$description = (isset($seoDetails['description'])) ? $seoDetails['description'] : 'Compare Colleges, Universities, and Institutes on the basis of course, fees, placements, alumni ratings, faculty, infrastructure, mode of study, and various other details.';
$canonical = (isset($seoDetails['canonical'])) ? $seoDetails['canonical'] : SHIKSHA_HOME."/resources/college-comparison";

$headerComponents = array(
        'css'=>array('compare'),
        'js'=>array('compare'),
        'jsFooter' =>array('common','lazyload'),
        'canonicalURL'      => $canonical,
        'title'             => $title,
        'metaDescription'   => $description,
        'doNotShowKeywords' => "true",
        'product'           => 'nationalCompare',
        'lazyLoadJsFiles' => true,
);
$this->load->view('common/header', $headerComponents);
?>
<script>
var _cmpLgn = "cmpLgn"; // cookie name for login on search / similar college
var compareHomePageKeyId = '<?php echo $compareHomePageKeyId;?>';
<?php
if(isset($validateuser) && isset($validateuser[0]['userid'])){
	echo "userlogin=true;";
}
else{
	echo "userlogin=false;";
}
?>
isShowGlobalNav = false;
var emailDataArray  = new Array();
var cookieDataArray = '';
</script>

    <?php
        $this->load->view('autoSuggestorInstitute');
        $this->load->view('compareNewUI_compareSections');
        $this->load->view('collegeReviewLayer');
    ?>
    <div id = "rlbg" class="review-layer-col"></div>

<?php
$this->load->view('common/footer');
if(isset($validateuser) && $validateuser!='false'){
	$this->load->view('makeViewedResponse',array('courseIdArr'=>$courseIdArr,'actionType'=>'COMPARE_VIEWED'));
}
?>

<!-- layer html for Ask Now -->
<div id="tags-layer" class="tags-layer"></div>
<div class="an-layer an-layer-inner" id="an-layer">
    <?php 
    $displayData['responseAction'] = 'Asked_Question_On_Listing';
    $displayData['qPostingTitle']  = 'Post a question to current students of this college';
    $this->load->view('messageBoard/desktopNew/quesDiscPosting',$displayData);
    ?>
</div>

<script>
function LazyLoadForCompareQP(){
  lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
    $LAB
  .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
                '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>')
  .wait(function(){
    compareQpLazyLoadCallBack();
  });
}

//Check if the click is outside Similar institute overlay. If yes, check if it is shown. If yes, hide it.  
$j(document).click(function (e)
{
    var container = $j("#recommendationDivNormal");
    var container2 = $j("#recommendationDivLinkNormal");
    
    if (!container.is(e.target) && container.has(e.target).length === 0 && container.is(':visible') && !container2.is(e.target) && container2.has(e.target).length === 0)
    {
        container.hide();
    }
    
    var container = $j("#recommendationDivSticky");
    var container2 = $j("#recommendationDivLinkSticky");
    
    if (!container.is(e.target) && container.has(e.target).length === 0 && container.is(':visible') && !container2.is(e.target) && container2.has(e.target).length === 0)
    {
        container.hide();
    }

    var container3 = $j(".cmpre-drpdwn");

    if (!container3.is(e.target) // if the target of the click isn't the container...
        && container3.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $j('.custm-drp-layer').hide();
    }
    
});

//var floatingRegistrationSource = 'COMPARE_PAGE';
var showAsk = 'true';

// make Auto response
var isComparePage = true;
var comparePageObj = new comparePageClass();
$j(window).load(function(){
    comparePageObj.windowLoadCalls();
});
$j(document).ready(function(){
    comparePageObj.DOMReadyCalls();
    comparePageObj.getCollegeRecommendations('<?php echo json_encode($courseIdArr); ?>');
    comparePageObj.getCollegeCourseInDropDown('<?php echo json_encode($courseIdArr); ?>');
});
if(typeof isUserLoggedIn != 'undefined' && isUserLoggedIn && getCookie(_cmpLgn) && getCookie(_cmpLgn) !=''){
    var _cmpLgnData = getCookie(_cmpLgn).split("::");
    if(_cmpLgnData[0] == 'cmpSrchLgn'){
        setCookie(_cmpLgn,'',0);
        showSelectedInstitute(_cmpLgnData[3], _cmpLgnData[1], _cmpLgnData[2]);
    }
}
</script>
