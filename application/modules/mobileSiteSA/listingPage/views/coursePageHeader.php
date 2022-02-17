<?php
    $headerComponents = array(
                            'cssBundleMobile' => 'sa-course-page-mobile',
                            'js'  =>array('listingPageSA','vendor/jquery.lazy.min'),
                            'title'   => $seoData['seoTitle'],
                            'canonicalURL'    => $seoData['seoURL'],
                            'metaDescription' =>  $seoDescription,
                            'metaKeywords'    =>  $seoData['seoKeywords'],
                            'firstFoldCssPath'    => 'listingPage/css/coursePageFirstFoldCss',
                            'deferCSS' => true
                        );

    $this->load->view('commonModule/headerV2',$headerComponents);
?>
    <script>
        var currencyChanger = 0;
    </script>
    <div class="header-unfixed">
        <?php $this->load->view('widgets/courseNavigationBar');?>
        <div class="_topHeadingCont padTop12">
            <img id='beacon_img' src="<?php echo IMGURL_SECURE; ?>/public/images/blankImg.gif" width=1 height=1 >
            <strong class="_topHeading"><?=  htmlentities($universityObj->getName())?></strong>
            <!-- <div id="universityTitleShortlistBox" class="univ-shortlist-box">
                <a href="javascript:void(0);" onclick="addRemoveFromShortlist(<?//=$courseObj->getId()?>,'CoursePageTitle','courseListingPage_mob',this);">
                    <i class="sprite shortlist-icn<?//=$isShortlisted?"-filled":""?>"></i>
                </a>
            </div> -->
        </div>
        <h1 class="course-title"><?=htmlentities($courseObj->getName())?></h1>
    </div>
