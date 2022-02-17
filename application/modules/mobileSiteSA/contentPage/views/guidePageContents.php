<div class="wrapper" data-enhance="false">
    <?php $this->load->view('contentPage/widgets/levelTwoNavBar'); ?>
    <div itemscope itemtype="http://schema.org/Article">
        <div style="display:none;">
            <img id='beacon_img' src="<?=IMGURL_SECURE?>/public/images/blankImg.gif" width=1 height=1 />
        </div>
        <section class="layer-wrap clearfix gryBg">
            <?php
            $this->load->view('widgets/contentTitle');
            //$this->load->view('widgets/fixedHeaderLinks');
            $this->load->view('widgets/contentSectionDetails');
            $this->load->view('widgets/votingSection');
            if($browsewidget['finalArray']['widgetType']=='university'){
                $this->load->view('widgets/browseUniversity');
            }else if($browsewidget['finalArray']['widgetType']=='course'){
                $this->load->view('widgets/browseCourse');
            }
            $this->load->view('widgets/commentsSection');
            $this->load->view('widgets/contentGuideStructuredDataMarkupMeta');
            //$this->load->view('widgets/relatedGuides');
            //$this->load->view('widgets/relatedArticles');
            if($content['data']['is_downloadable'] == "yes"){
                $this->load->view('contentPage/widgets/stickyContentBrochureDownload');
            }
            ?>
        </section>
    </div>
</div>
