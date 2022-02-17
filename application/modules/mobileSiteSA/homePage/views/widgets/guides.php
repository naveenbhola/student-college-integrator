<section class="content-wrap clearfix" style="height:377px;">
    <div style="padding:12px 8px 0; text-align: center;" class="wrap-title">Most popular student guides</div>
    <div class="slider-box _guidNewBx slider-col" id="guideSliderWrapper">
        <ul id="guideUl" style="width: 10000px; left: -6px;" class="sliderUl">
            <?php $guideCount = 0;
            foreach($topGuides as $guide){
                $guideCount++;
                // comment, view count string:
                $commentViewCountString  = '';
                if($guide['commentCount'] > 0)
                {
                    $commentViewCountString = $guide['commentCount'].' comment';
                    $commentViewCountString .= ($guide['commentCount'] > 1 ? 's':'');
                }
                if($commentViewCountString != '' && $guide['viewCount'] > 0)
                {
                    $commentViewCountString .= ' | ';
                }
                if($guide['viewCount'] > 0)
                {
                    $commentViewCountString .= $guide['viewCount'].' view';
                    $commentViewCountString .= ($guide['viewCount'] > 1 ? 's':'');
                }
                if($beaconTrackData['pageIdentifier']=='homePage')
                {
                    $lazyClass = ($guideCount >2?'lazySwipe':'lazy');
                }else{
                    $lazyClass = 'lazy';
                }
            ?>
            <li class="trendtuple" style="width:300px;height: 343px">
                <div class="figure">
                    <a href="<?=($guide['contentURL'])?>" class="guideLinkImg ui-link">
                        <img class="hm-wdgt-img" alt="<?=(htmlentities($guide['strip_title']))?>" src="<?=resizeImage($guide['contentImageURL'],'300x200')?>" style="display: inline-block; width: 299px; height: 200px;">
                    </a>
                    <div class="caption"> <strong><?=(formatArticleTitle(htmlentities($guide['strip_title']),31))?></strong> </div>
                </div>
                <div class="univ-details _dsc" onclick = "openGuide(this);">
                    <p class="_dsc"><?=(formatArticleTitle(strip_tags($guide['summary']),140))?></p>
                    <span class="_cmntVew">
                        <?=$commentViewCountString?>
                    </span>
                </div>
                <?php if($guide['is_downloadable']=='yes'){?>
                <section class="content-wrap clearfix">
                    <a href="javascript:void(0)" data-transition="slide" contentId= <?php echo $guide['content_id'];?> contentType="<?php echo $guide['type'];?>"
                    dlGuideTrackingId = '1215' class="dlGuideInline btn btn-default btn-full"> <i class="sprite bro-icn"></i>Get this guide</a>
                </section>
                <?php } ?>
                
            </li>
            <?php } ?>
        </ul>
    </div>
</section>
