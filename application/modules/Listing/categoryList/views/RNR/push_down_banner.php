<?php

    // if(!empty($filteredBanner) && $filteredBanner->getURL()){
    //  $displayBanner = $filteredBanner; // same on ajax call
    // } else if($categoryPage->getBanner() && $categoryPage->getBanner()->getURL()) {
    //  $displayBanner = $categoryPage->getBanner();
    // }
$displayBanner = "http://ieplads.com/bmsjs/banners/2015/shiksha/HTML_CS/apeejay_2feb16_2/apeejay_2feb16.html";
    if(!empty($displayBanner) && !preg_match('/(\.swf)/',$displayBanner) && $displayBanner != '') { ?>
        <div id="RNR_pushdownbanner" class="clear top-ad-wrap" style = "width:943px; display:inline-block;">
            <iframe id="categoryPagePushDownBannerFrame" width="100%" height="160" scrolling="no"  frameborder="0" src="<?php echo $displayBanner;?>" id="TOP" bordercolor="#000000" vspace="0" hspace="0" marginheight="0" marginwidth="0" style="z-index:1; display:inline-block;"></iframe>
        </div>
    <?php }
    else if(!empty($displayBanner) && $displayBanner) { ?>
        <style>
            .shikFL object{ position:absolute; right:0; }
        </style>
        
        <div id="RNR_pushdownbanner" class="clear top-ad-wrap" style = "width:943px; margin-left:13px;"></div>
        
        <script>
            shoshkeleUrl = '<?=$displayBanner?>';
            //shoshkeleUrl = 'http://images.shiksha.com/mediadata/videos/1399977032php3CA3XQ.swf';
        </script>
    <?php }
    else { ?>
        <div id="RNR_pushdownbanner" class="clear top-ad-wrap" style="display:none;"></div>
    <?php }
    
    if(0 && $request->isAJAXCall()) { ?>
        <script>
            if(typeof(shoshkeleUrl) != 'undefined') {
                showPushDownBannerRnRCategoryPage(shoshkeleUrl);
            }
        </script>
    <?php }
?>
