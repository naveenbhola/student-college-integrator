<?php
$headerComponents = array(
    'cssBundleMobile' => 'sa-ranking-mobile',
    'title'           => $seoData['seoTitle'],
    'canonicalURL'    => $seoData['canonicalUrl'],
    'metaDescription' =>  $seoData['seoDescription'],
    'metaKeywords'    =>  $seoData['seoKeywords'],
    'firstFoldCssPath'    => 'rankingPage/css/rankingPageFirstFoldCss',
    'deferCSS' => true
);

    $this->load->view('commonModule/headerV2',$headerComponents);?>
    <div class="header-unfixed">
        <div class="_topHeadingCont">
            <img id='beacon_img' src="<?php echo IMGURL_SECURE; ?>/public/images/blankImg.gif" width=1 height=1 >
            <!--<a class="back-box" href="#" style="vertical-align:middle"><i class="sprite back-icn"></i></a>-->
            <!--<p style="text-align:center"></p>-->
            <h1 class="_topHeading"><?php echo htmlentities($rankingPageObject->getTitle()); ?></h1>
        </div>
    </div>
