 <div class="wdh100">
 
 
        <div align="center">
                <?php
                        $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'SIDE');
                        $this->load->view('common/banner.php', $bannerProperties);
                ?>
        </div>
        <div class="latestNewsBlock">
        <div class="fb-like-box" data-href="http://www.facebook.com/shikshacafe" data-width="<?php echo ($fromOthersTopic!='announcement' && $fromOthersTopic!='discussion' && $fromOthersTopic!='review' && $fromOthersTopic!='eventAnA')?"300":"250"?>" data-show-faces="true" data-border-color="#f2f2f2" data-stream="false" data-header="false"></div>
        </div>
        <div class="clearFix spacer20"></div>
</div>
 