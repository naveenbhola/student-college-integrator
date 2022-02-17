<?php if(count($relatedArticleData) > 0) {?>
    <div class="newExam-widget">
        <h2>
            <div class="widget-head"><p>Related articles <i class="common-sprite blue-arrw"></i></p></div>
        </h2>
            <ul class="examArtcle-list">
            <?php for($i = 0; $i < min(count($relatedArticleData),4); $i++){ ?>
                <?php if($i%2 == 0){ ?> <li <?php echo $i==2?'class="last"':''?>> <?php } ?>
                    <div class="examArtcle-col fl<?php echo ($i%2)?'R':'L'?>t">
                        <div class="examArtcle-img"><a href="<?php echo $relatedArticleData[$i]['contentURL'];?>"><img width="75" height="50" class="lazyImg" alt="<?php echo $relatedArticleData[$i]['strip_title'];?>" data-original="<?php echo str_replace("_s", "_75x50", $relatedArticleData[$i]['contentImageURL']);?>"></a></div>
                        <div class="examArtcle-info"><a href="<?php echo $relatedArticleData[$i]['contentURL'];?>"><?php echo htmlentities(formatArticleTitle($relatedArticleData[$i]['strip_title'],150));?></a></div>
                    </div>
                    <?php if($i%2 == 1){ ?> <div class="clearfix"><?php } ?>
                <?php if($i%2 == 1){ ?> </li> <?php } ?>
            <?php } ?>
        </ul>
    </div>
<?php } ?>