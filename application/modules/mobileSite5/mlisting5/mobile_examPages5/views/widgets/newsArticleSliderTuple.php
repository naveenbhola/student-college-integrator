<?php
    $titleLimit   = 85;
    $summaryLimit = 310;

    $title   = (strlen(strip_tags($tupledata['blogTitle'])) > $titleLimit ) ? substr(strip_tags($tupledata['blogTitle']), 0, $titleLimit)." ..." : $tupledata['blogTitle'];
    $summary = (strlen(strip_tags($tupledata['summary'])) > $summaryLimit ) ? substr(strip_tags($tupledata['summary']), 0, $summaryLimit)."..." : $tupledata['summary'];

    $summaryCharacterLimitFlag = 0;
    if(strlen(strip_tags($tupledata['summary'])) > $summaryLimit )
        $summaryCharacterLimitFlag = 1;

?>
<li style="height:225px;">
    <div class="slide-main-wrap">
        <div class="slide-prev"><?php if($totalSlides > 1) { ?><i class="msprite prv-icn"></i><?php } ?></div>
        <div class="slider-wrap">
            <div class="expg-m-slid slide-main-wrap">
                <p class="expg-m-slid-hd"><a href="<?php echo $tupledata['url']?>"><?php echo html_escape($title)?></a></p>
                <p class="expg-m-slid-dt">On <?=date("M d, Y", strtotime($tupledata['lastModifiedDate']))?></p>
                <p class="expg-m-slid-info"><?php echo html_escape($summary)?>
                    <?php if($summaryCharacterLimitFlag){ ?>
                    <a href="<?php echo $tupledata['url']?>">more</a>
                    <?php } ?>
                </p>
            </div>
        </div>
        <div class="slide-next"><?php if($totalSlides > 1) { ?><i class="msprite nxt-icn"></i><?php }?></div>
    </div>
</li>