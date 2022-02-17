<style type="text/css">
	<?php $this->load->view('/mcommon5/css/hamburgerFooterCSS'); // Hamburger, RHL and Footer CSS only?>
</style>
<!-- Jquery-mobile and jquery-->
<script src="//<?php echo JSURL; ?>/public/js/jquery-2.1.4.min.js"></script>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("main","nationalMobile"); ?>"></script>

<script type="text/javascript">
/*files will load after page loading*/
function lazyLoadExamCss(cssFile){
  var cb = function() {
    var l = document.createElement('link'); l.rel = 'stylesheet';
    l.href = cssFile;
    var h = document.getElementsByTagName('head')[0]; h.appendChild(l);
    };
    /*var raf = requestAnimationFrame || mozRequestAnimationFrame ||
    webkitRequestAnimationFrame || msRequestAnimationFrame;
    if (raf) raf(cb);
    else */window.addEventListener('load', cb);
}

$(document).on('labLoadedJs', function() {
    $LAB.script(
                "//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('shikshaMobile', 'nationalMobile'); ?>",
                "//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('footerNM', 'nationalMobile'); ?>",
                "//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('userRegistrationFormMobile_jq',
                 'nationalMobile'); ?>",
                "//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('mobileSearchV2', 'nationalMobile'); ?>",
                "//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('jquery.lazyload'); ?>"
            <?php if(!empty($jsFooter)) {
                foreach($jsFooter as $jsFile){
             ?>
                ,"//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion($jsFile, 'nationalMobile'); ?>"
            <?php } } ?>
            )
    .wait(function(){
        <?php if($boomr_pageid == 'Mobile5ExamPage') { ?>
                    initializeExamPage();
        <?php }else if($boomr_pageid == 'home'){?>
               if(typeof(homepageLazyLoadCallBack) == 'function'){
                    homepageLazyLoadCallBack();
               }
        <?php }else if($boomr_pageid == 'ComparePage'){?>
                    initializeComparePage();
        <?php }else if($boomr_pageid == 'mobileArticlePage'){?>
                    initializeArticleDetailPage();
        <?php }else if($boomr_pageid == 'mobilesite_AnA_QDP' || $boomr_pageid == 'mobilesite_AnA_DDP' ){?>
                    initializeQDP(entityId,type); 
        <?php }else if($boomr_pageid == 'college_predictor_rank'){?>
                    initCollegePredictor(); 
        <?php }else if($boomr_pageid == 'RANK_PREDICTOR'){?>
                    initRankPredictor();
        <?php }else if($boomr_pageid == 'college_cutoff_page'){?>
                    initDuCollegePredictor();
                    createCutOffViewedResponse();
        <?php }else if($boomr_pageid == 'IIM_PERCENTILE_RESULT_PAGE' || $boomr_pageid == 'IIM_PERCENTILE'){?>
                    initIIMPercentile();
        <?php }else if($boomr_pageid == 'mobilesite_LDP'){?>
                        loadShikshaSlider();
                       initializeSliders();
                       callAskProposition(globalListingId, globalListingType);
                        makeCurrentSectionActive();
                        if(ga_page_name == 'INSTITUTE DETAIL PAGE' || ga_page_name == 'UNIVERSITY DETAIL PAGE') {
                            createInstituteViewedResponse();
                        }
                   jQuery(window).on("orientationchange",function() {
                     setTimeout(function(){ initializeSliders(1); },1000);
                   }); 

            <?php }
             ?>
            $(document).trigger('initToolsSlider');
    });
});
$(function(){
    lazyLoadExamCss('//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("userRegistrationMobile","nationalMobile"); ?>');
    lazyLoadExamCss('//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("search-widget","nationalMobile"); ?>');
    lazyLoadExamCss('//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("anaCommonLayer","nationalMobile"); ?>');
<?php if(!empty($cssFooter)) {
                foreach($cssFooter as $cssFile){
             ?>
        lazyLoadExamCss('//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion($cssFile,"nationalMobile"); ?>');
<?php }} ?>
});
</script>
