
<div class='layer-common hid' id='interlinkingLayer'>
        <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_view_layer'); ?>
</div>

<?php 
if(!empty($banner_details) && !preg_match('/(\.swf)/',$banner_details['file_path']) && $banner_details['file_path'] != '') { ?>
        <div id="RNR_pushdownbanner" class="clear top-ad-wrap" style = "width:943px; display:inline-block;">
            <iframe id="categoryPagePushDownBannerFrame" width="100%" height="300" scrolling="no"  frameborder="0" src="<?php echo $banner_details['file_path'];?>" id="TOP" bordercolor="#000000" vspace="0" hspace="0" marginheight="0" marginwidth="0" style="z-index:1; display:inline-block;"></iframe>
        </div>
<?php }
$this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_filter_section'); 

$this->load->view('nationalInstitute/AllContentPage/widgets/allContentCourseLayer'); ?>

<div id="rankingPageTable">
    <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_table'); ?>
</div>

<div class="cnt_bg cnt_bdr">
    <div class="rnk_contnr m_25top rnk-head-clr">
        <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_related_rankings'); ?>
        <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_article_interlinking'); ?>
        <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_exam_interlinking'); ?>
    </div>
</div>
    <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_category_widget'); ?>
