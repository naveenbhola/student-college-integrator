<?php
$featuredWidgetData = $widgetData['countryHomeFeaturedColleges']['universities'];
$printData = array('public'=>'Public','private'=>'Private','not_for_profit'=>'Non-Profit');
?>
<script>
    var imageArray = [];
</script>
<div class="<?=($floatClass)?> popular-widget" style="height:345px;">
    <div class="popular-widget-title"><?=($widgetConfigData['countryHomeFeaturedColleges']['title'])?></div>
    <div class="popular-widget-detail clearwidth" style="padding:0;overflow:hidden;">
        <div class="clearwidth">
            <ol id="featuredSliderBubbles" class="featured-slider">
                <!--This is filled via jquery-->
            </ol>
        </div>
        <div class="widget-slider2">
            <a href="javascript:void(0);" class="country-sprite prev-arrw-a" style="z-index: 1;outline: 0 none;" onclick="rotateBackward(1);"></a>
            <a href="javascript:void(0);" class="country-sprite next-arrw-a" style="z-index: 1;outline: 0 none;" onclick="rotateForward(1);"></a>
        </div>
        <div id="slideBox" style="width:20000px">
            <?php foreach($featuredWidgetData as $university){ ?>
                <div class="slider-wrap" style="position:relative;">
                    <div class="widget-slider">
                        <div class="slider-content">
                            <ul>
                                <li><a href="<?=$university['url']?>" target="_blank" style="outline: 0 none;"><img src="" width="300" height="200" alt="<?=htmlentities($university['name'])?>" title="<?=htmlentities($university['name'])?>" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="college-info-wrap">
                        <div class="college-info">
                            <a href="<?=$university['url']?>" target="_blank"><?=formatArticleTitle(htmlentities($university['name']),40)?></a>
                            <?php
                            $universityInfoText ='';
                            if($university['year'] !=''){
                                    $universityInfoText = ', Estd '.$university['year'];
                                }
                            ?>
                            <p><?=htmlentities($printData[$university['type']])?> university <?= $universityInfoText;?></p>
                            <p><?=htmlentities($university['location'])?></p>
                        </div>
                        <a style="margin-top:17px" class="read-more-btn flRt" href="<?=$university['url']?>" target="_blank">Know More <span>&rsaquo;</span></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
<?php foreach($featuredWidgetData as $university){ ?>
    imageArray.push("<?=$university['photo']?>");
<?php } ?>
</script>
