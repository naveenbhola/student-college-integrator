<?php 
    ob_start('compress');

    $headerComponents = array(
            'css'              =>   array('marticle5/amp/css/articleDetailPageCss'), //view file path of amp css 
            'js' => $ampJsArray,//array of js files to be included
            'm_meta_title'     => $seoTitle,
            'm_meta_description' => $metaDescription,
            'm_canonical_url' => $canonicalURL,
            'pageType'  => 'articleDetailPage',
            'ampExternalCSS' => $articleInternalCss
        );
    $this->load->view('mcommon5/AMP/header',$headerComponents);
    ?>
    <body>
        <?php 
            $this->load->view('mcommon5/AMP/googleAnalytics');
            echo Modules::run('mcommon5/MobileSiteHamburgerV2/getAMPHamburger',array('fromwhere'=>'articleDetailPage','entityId'=>$blogId,'entityType' => 'blog'));
            $this->load->view('marticle5/amp/views/articleDetailPageContent');
            $this->load->view('mcommon5/AMP/footer');
            $this->load->view('mcommon5/AMP/dfpBannerViewSticky',array("bannerPosition" => "aboveCTA"));
        ?>
    </body>
    <?php
    ob_end_flush();
?>