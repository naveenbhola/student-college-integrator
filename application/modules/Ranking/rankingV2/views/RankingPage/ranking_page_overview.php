<?php
$title 	 = "";
$description = "";
if(!empty($meta_details)){
	$title = $meta_details['title'];
	$description = $meta_details['description'];
}
$keyword = "";
$metaKeywords = "";
if(isset($ranking_page)){
	$rankingPageId = $ranking_page->getId();
}
$bannerProperties = array('pageId' => 'RANKING', 'pageZone'=>'HEADER', 'shikshaCriteria' => array('keyword' => $keyword));

$headerComponents = array(
	'jsFooter' => array(),
	'cssFooter'	=> array('recommend'),
	'product'=> "ranking",
	'taburl' =>  site_url(),
	'title'	=>	$title,
	'searchEnable' => false,
	'canonicalURL' => $canonical,
	'metaDescription' => $description,
	'metaKeywords'	=> $metaKeywords,
	'rankingBannerProperties' => $bannerProperties,
	'showBottomMargin' => 0,
	'loadFromGruntAsync' => true
);
$this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('NATIONAL_RANKING_NEW', 1);
// $this->load->view(RANKING_PAGE_MODULE.'/ranking_banner');
?>
<div class="notify-bubble report-msg" id="noti" style="top: 130px;opacity: 1; display: none;">
   <div class="msg-toast">
   <a class="cls" href="javascript:void(0);" onclick="closeToast(this);">×</a>
   <p id="toastMsg"></p>
   </div>
</div>
<?php
$this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_seo_table');
$this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_inner_page');
$this->load->view('/common/footerNew',array('loadJQUERY' => 'YES'));
if(SHOW_RANKING_WEBSITE_TOUR){
	echo includeJSFiles('shikshaDesktopWebsiteTour');
}
?>
<script>
	var RANKING_PAGE_MODULE = "rankingV2";
	var shikshaProduct = "ranking";
    var GA_currentPage = "RANKING_PAGE";
    var ga_user_level = "<?php echo $mobileRequest?"MOBILE":"DESKTOP";?>";
    var isShowGlobalNav = false;
    var SHOW_RANKING_WEBSITE_TOUR = "<?php echo SHOW_RANKING_WEBSITE_TOUR; ?>"
    <?php 
    	if(SHOW_RANKING_WEBSITE_TOUR){
    		?>
    		window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
    		<?php
    	}
    ?>
	var mmp_page_type = '<?php echo $mmpData['mmp_details']['display_on_page'];?>';
	function seoMMPRegLayer(){
        var mmp_form_id_on_popup   = '<?php echo $mmpData['mmp_details']['page_id'];?>';
        var customFieldValueSource = <?php echo json_encode($mmpData['regFormPrefillValue']['customFieldValueSource']);?>;
        var customFields           = <?php echo json_encode($mmpData['regFormPrefillValue']['customFields']);?>;
        if(mmp_form_id_on_popup != ''){            
            var formData = {
                'trackingKeyId' : '<?php echo $mmpData['trackingKeyId'];?>',
                'customFields':customFields,
                'customFieldValueSource':customFieldValueSource,
                'submitButtonText':'<?php echo addslashes(htmlentities($mmpData['submitButtonText']));?>',
                'httpReferer':'',
                'formHelpText':'<?php echo json_encode($mmpData['customHelpText']);?>'
            };
            //MMPLayerCommon(formData); 
            //registrationForm.showRegistrationForm(formData);
        }
    }
	var rankingPageVersionedFile = '<?php echo getJSWithVersion("ranking_page"); ?>';
</script>
