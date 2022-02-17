<?php
$title = (isset($seoDetails['title']))?$seoDetails['title']:'Colleges Comparison Tool - Compare Colleges, Universities, and Institutes';
$description = (isset($seoDetails['description']))?$seoDetails['description']:'Compare Colleges, Universities, and Institutes on the basis of course, fees, placements, alumni ratings, faculty, infrastructure, mode of study, and various other parameters.';
$canonical = (isset($seoDetails['canonical']))?$seoDetails['canonical']:SHIKSHA_HOME."/compare-colleges";

$headerComponents = array(
        'css'=>array('compare'),
        'js'=>array('multipleapply','category','user','customCityList','ajax-api','compare'),
        'jsFooter' =>array('common','lazyload','onlinetooltip','processForm'),
        // 'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),
        'canonicalURL'      => $canonical,
        'title'             => $title,
        'metaDescription'   => $description,
        'doNotShowKeywords' => "true",
        'product'           => 'nationalCompare'
);

$this->load->view('common/header', $headerComponents);
?>
<script>
STUDY_ABROAD_TRACKING_KEYWORD_PREFIX = "";
var compareSlide = 1;
var _cmpLgn = "cmpLgn"; // cookie name for login on search / similar college
var compareHomePageKeyId = '<?php echo $compareHomePageKeyId;?>';
var $categorypage = {};
$categorypage.LDBCourseId = 0;
$categorypage.key = "comparePage";
$categorypage.currentUrl = "http://<?=$_SERVER[HTTP_HOST]?><?=$_SERVER[REQUEST_URI]?>";
<?php
if(isset($validateuser) && isset($validateuser[0]['userid'])){
	echo "userlogin=true;";
}
else{
	echo "userlogin=false;";
}
?>
isShowGlobalNav = false;
</script>

    <?php
        $this->load->view('autoSuggestorInstitute');
        $this->load->view('compareNewUI_compareSections');
    ?>
    <div id = "rlbg" class="review-layer-col"></div>

<?php
$this->load->view('common/footer');
?>

<script>
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

if(getCookie("comparelayer"+$categorypage.key) == 1){
        recommendation_json_i = getCookie('recommendation_json_i');
        recommendation_json_c = getCookie('recommendation_json_c');
        thanksHTML = getCookie('thanks_you_text'+STUDY_ABROAD_TRACKING_KEYWORD_PREFIX);
        if(getCookie('compare_bottom_widget') !== false){
                $j("#thanks-box").show();
        }
        if(recommendation_json_c !== false){
                $('confirmation-box-wrapper').innerHTML = thanksHTML;
        }
        setCookie('thanks_you_text'+STUDY_ABROAD_TRACKING_KEYWORD_PREFIX,"",60,'seconds');
        setCookie("recommendation_json_c","",60,'seconds');
}
var floatingRegistrationSource = 'COMPARE_PAGE';
var showAsk = 'true';

// make Auto response
var isComparePage = true;
$j(window).load(function(){
	<?php
	if($loggedInUserData!=false)
	{  
        //added by akhter, added pageKey on course
        $trackeyStr = $_COOKIE['compare-tracking'];
        $trackeyStrArr = explode('|||',$trackeyStr);
        if(count($trackeyStrArr)>0){
            foreach ($trackeyStrArr as $value) {
                $v= explode('::',$value);
                    $key[$v[1]] = $v[2];
            }
        }

		if(count($responseUserData)>0){ foreach((object)$responseUserData as $res){
            $pageKey = ($key[$res['courseId']]>0) ? $key[$res['courseId']] : $compareHomePageKeyId; 
        ?>
		firstNameVal = escape("<?php echo addslashes($res['firstName']); ?>");
		lastNameVal = escape("<?php echo addslashes($res['lastName']); ?>");
		makeAutoResponse(firstNameVal,lastNameVal,<?=$res['mobile'];?>,'<?=$res['email'];?>',<?=$res['instituteId'];?>, '<?=html_escape($res['instituteName']);?>',<?=$res['courseId'];?>,'COMPARE_VIEWED','<?=$res['currentCityId'];?>','<?=$res['currentLocaLityId'];?>','', <?=$pageKey;?>); // the 12th parameter has to be tracking pagekey
		<?php }}
	}
	?>
});

compare_onload();

if(getCookie(_cmpLgn) && getCookie(_cmpLgn) !=''){
    var _cmpLgnData = getCookie(_cmpLgn).split("::");
    if(_cmpLgnData[0] == 'cmpSrchLgn'){
        showSelectedInstitute(_cmpLgnData[1],_cmpLgnData[2]);
    }
}
</script>