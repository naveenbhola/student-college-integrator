<?php ob_start('compress');
$headerComponents = array(
   	'm_meta_title'=>$seoDetails['title'],
   	'm_meta_description'=>$seoDetails['description'],
   	'pageType' => 'articlePage',
   	'canonicalURL'=>$seoURLS['canonicalURL'],
  	'nextURL'=> $seoURLS['nextURL'],
  	'previousURL'=> $seoURLS['prevURL'],
  	'jsMobileFooter' => array('nmArticleNew'),
    'noIndexMetaTag' => $noIndexMetaTag
);
$this->load->view('/mcommon5/header',$headerComponents);
global $isWebViewCall;
?>
<div id="wrapper" data-role="page" class="of-hide" <?php if(!$isWebViewCall){?> style="min-height: 413px;padding-top: 40px;"<?php }?> >
	<?php 
	echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
    echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    if(!$isWebViewCall){
      $this->load->view('mobileArticleHeader');
    }
    ?>
    <div data-role="content">
      <?php 
      $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
     <?php $this->load->view('mobileArticleSubHeader');?>
        <div data-enhance="false">
        	<?php if(!empty($totalArticles)){ $this->load->view('articleTabs');}?>
          <div class="bgArt"><div class="three-quarters-loader-msearch"></div></div>
        	<div id="artList">
            <?php $this->load->view('articleListInner');?>
          </div>
          <div id="ajxLoadMore" style="text-align:center;margin-bottom:7px;"><img border=0 src="/public/mobile5/images/ajax-loader.gif" ></div>
        	<?php $this->load->view('/mcommon5/footerLinks');?>
		</div>
	</div>
</div>
<?php $this->load->view('/mcommon5/footer');?>
<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>
<div data-role="page" id="subcategoryDivForArticles" data-enhance="false"></div>
<script>
var articleNewObj = new articleNewClass();
articleNewObj.totalArticles = '<?php echo $totalArticles!="" && $totalArticles>0 ? $totalArticles : 0; ?>';
articleNewObj.entityType = '<?php echo $entityType; ?>';
articleNewObj.pageSize = '<?php echo $pageSize; ?>';
articleNewObj.params = '<?php echo $paramForURL; ?>';
articleNewObj.pageNum = '<?php echo $currentPage; ?>';
articleNewObj.entityIdForLayer = '<?php echo $entityIdForLayer; ?>';
articleNewObj.currentPage = articleNewObj.pageNum;
$(document).ready(function() {
  articleNewObj.domReadyCalls();        
});
$(window).load(function(){
  articleNewObj.windowLoadCalls();
});
window.onscroll = function(){
  articleNewObj.windowOnScrollCalls();
}
</script>
<?php ob_end_flush(); ?>
 
