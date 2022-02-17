<?php 
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.


$headerComponents = array(
		'css'   =>      array('college-predictorV2'),
		'js' => array('collegePredictor'),
		'title' =>      $m_meta_title,
		'metaDescription' => $m_meta_description,
		'canonicalURL' =>$canonicalURL,
		'product'       =>'rankPredictorV2',
		'showBottomMargin' => false,
		'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'metaKeywords' => $metaKeywords,
);


$headerComponents['showGutterBanner'] = 1;
switch($examName){
	case 'jee-main': $bannerName = 'JEE';break;
    case 'default': $bannerName = 'JEE';break;
}
$headerComponents['bannerPropertiesGutter'] = array('pageId'=> $bannerName.'_RANK_PREDICTOR', 'pageZone'=>'RIGHT_GUTTER');

$headerComponents['shikshaCriteria'] = array();
$this->load->view('common/header', $headerComponents);
$this->load->view('messageBoard/desktopNew/toastLayer');
?>

<?php 
if($isInputValuesExist){
    $this->load->view('RP/V2/rankPredictorResultPage');
}
else{
    $this->load->view('RP/V2/rankPredictorForm'); 
}
?>

<?php
echo modules::run('comparePage/comparePage/generateCollegeCompareTool');
?>

<?php $this->load->view('common/footer'); ?>

<script>
var regFormId  = '<?php echo $regFormId;?>';
var examName   = '<?php echo $examName;?>';
var cookieName = '<?php echo $cookieName;?>';
var cookieNamePer1 = '<?php echo $cookieNamePer1;?>';
var cookieNamePer2 = '<?php echo $cookieNamePer2;?>';

// var rpFeedBackCookeiName = '<?php echo $rpFeedBackCookieName;?>';
// these variables are using in js validation for score only
var minRange   = parseInt('<?php echo $rpConfig[$examName]['inputField']['score']['minRange'];?>');
var maxRange   = parseInt('<?php echo $rpConfig[$examName]['inputField']['score']['maxRange'];?>');
var GA_currentPage = 'RankPredictor';
var groupId = '<?php echo $eResponseData['groupId'];?>';
publishBanners();
$j(document).ready(function () {
    initRankPredictor();
    $j(document).on('click','#modifySearchButton',function(){
        setCookie('collegepredictor_search_'+"<?php echo $collegePredictorExamName; ?>",'',0);
        setCookie('collegepredictor_filterTypeValueData_desktop_'+"<?php echo $collegePredictorExamName; ?>",'',0);
        window.location.href = "<?php echo $collegePredictorUrl; ?>";
    }) 
});
<?php 
    if($isInputValuesExist){
        ?>
        $j(function() { 
            $j(window).scroll(function() {
                   if(( ($j(this).scrollTop()+400) >= $j('.rnk-predictr').offset().top+($j(document).height()/4)) && !(/MSIE ((5\\.5)|6)/.test(navigator.userAgent) && navigator.platform == "Win32")) {
                        if($j(window).width() < 1000){
                                $j('#toTop').css('left',($j('.rnk-predictr').offset().left+818) + "px");
                        }else{
                                $j('#toTop').css('left',($j('.rnk-predictr').offset().left+925) + "px");
                        }
                        $j('#toTop').fadeIn();
                    } else {
                        $j('#toTop').fadeOut();
                    }
            });
        
            $j('#toTop').click(function() {
                $j('body,html').animate({scrollTop:0},500);
            });
        });
        <?php
    }
    ?>
</script>
<div id="toTop">&#9650; Back to Top</div>
<div id="opacityLayer"></div>

<div id="googleRemarketingDiv" style="display: none;"></div>
