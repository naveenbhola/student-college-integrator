<?php if(count($articleWidgetsData) > 0) { ?>
<div class="rnk_slider">
    <h2 class="f-20 fnt__sb">Articles about Top <?=$rankingPage->getName();?> Colleges and Courses</h2>
    <div class="rnk_bx ps__rl" id="articleInterlinkingWidget">
        <a id="navPhotoPrev" class="arrw-bx prv hid"><i class="arrows prev"></i></a>
        <div class="r-caraousal">
            <ul class="featuredSlider">
            <?php foreach($articleWidgetsData as $val) { ?>
                        <li>
                            <a target="_blank" href="<?=$val['url'];?>" class="" ga-attr="ARTICLEINTERLINK">
                                <div class="art_card">
                                        <p title="<?=$val['artcileTitle'];?>" class="f-16 fnt__sb ht-44"><?php echo cutString(strip_tags($val['artcileTitle']), 52); ?></p><span class="f-14  block m_10top link_blue">Read More</span>
                                    </div>
                            </a>
                        </li>
            <?php } ?>
            </ul>
        </div>
        <a id="navPhotoNxt" class="arrw-bx nxt hid"><i class="arrows next"></i></a>
    </div>
</div>
<?php } ?> 