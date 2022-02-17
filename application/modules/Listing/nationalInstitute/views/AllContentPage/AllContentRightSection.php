<div class="col-md-4 no-padRgt ana-right right-widget" style="float: left">
    <?php
        if($pageType == 'questions'){ ?>
                          <div id="askProposition" style="display:none;">
                                <div style='text-align: center; margin-top: 7px; margin-bottom: 10px;' id='loader-id'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;width: 40px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>
                          </div>

    <?php
    }
    ?>

    <div id="rightStickyWidget">
    <?php
        echo modules::run('nationalInstitute/AllContentPage/getRelatedLinks',$listing_id,$listing_type,$pageType, $courseIdsMapping);
        echo modules::run('nationalInstitute/AllContentPage/getRecommendations',$listing_id,$listing_type,$pageType, $courseIdsMapping);
        if($pageType == 'reviews')
            $this->load->view("AllContentPage/widgets/writeReviewCTA");
        if(($pageType == 'admission' || $pageType == 'scholarships') && !empty($examList) && count($examList) > 0){
            $this->load->view("AllContentPage/widgets/examsOffered");
        }
        if($pageType == 'scholarships'){
            ?>
            <p class="clr"></p>
            <?php
        }
    ?>

    <?php if($pageType != 'scholarships'){ $this->load->view('dfp/dfpCommonRPBanner',array('bannerPlace' => 'C_RP','bannerType'=>"rightPanel")); } ?>
    </div>
</div>