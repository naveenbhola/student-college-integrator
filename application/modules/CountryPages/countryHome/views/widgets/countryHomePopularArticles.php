<?php
$articlesData = $widgetData['countryHomePopularArticles'];
$articlesCount = count($articlesData);
$totalArticleSlides = ceil(($articlesCount/3));
?>
<div class="popular-widget clearwidth" style="width:100%;">
    <div class="popular-widget-title">
        <span class="flLt"><?=($widgetConfigData['countryHomePopularArticles']['title'])?></span>
        <div class="next-prev flRt" id="conOrgWidgetArrow">
            <span class="flLt slider-caption">1 of <?=$totalArticleSlides?></span>
            <a href="javascript:void(0);" onClick="animateArticlesSlider('prev');" class="prev-box"><i class="common-sprite prev-icon"></i></a>
            <?php
            if($totalArticleSlides == 1) {
            ?>
                <a href="javascript:void(0);" class="next-box"><i class="common-sprite next-icon"></i></a>
            <?php
            } else {    ?>
                <a href="javascript:void(0);" class="next-box active" onClick="animateArticlesSlider('next');" ><i class="common-sprite next-icon-active"></i></a>    
            <?php
            }
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="popular-widget-detail clearwidth" style="width:100%; padding:15px 6px">
        <div class="article-display-list" style="display:block; overflow: hidden; width: 916px;">
            <ul id="conOrgWidgetContainer" style="display:block;width: 4000px; position: relative;">
            <?php
            $currentPosition = 1;
            for($i = 0; $i < $articlesCount; $i++)
            {
                $commentCountStr = "";
                if($articlesData[$i]['commentCount'] != 0) {
                    $commentCountStr = $articlesData[$i]['commentCount']. ($articlesData[$i]['commentCount'] > 1 ? " comments" : " comment");
                }
                
                $viewCountStr = "";
                if($articlesData[$i]['viewCount'] != 0) {
                    $viewCountStr = $articlesData[$i]['viewCount']. ($articlesData[$i]['viewCount'] > 1 ? " views" : " view");
                }
                
                if($commentCountStr != "" && $commentCountStr != "") {
                    $commentCountStr .= " | ";
                }
                
                $class = "";
                $liOpenTag = "";                
                $liCloseTag = "";
                
               if($currentPosition == 3) {
                    $class = "last";
                    $liCloseTag = "</li>";                    
               } else if($currentPosition == 1) {                   
                    $liOpenTag = '<li style="width:916px;">';
               }
               
               echo $liOpenTag;
            ?>
                
                    <div class="article-block <?=$class?>">
                        <strong><a href="<?php echo $articlesData[$i]['contentURL'];?>" target="_blank"><?php echo formatArticleTitle(htmlentities($articlesData[$i]['strip_title']),60);?></a></strong>
                        <div class="article-img">
                            <a href="<?php echo $articlesData[$i]['contentURL'];?>" target="_blank"><img  title="<?=htmlentities($articlesData[$i]['strip_title'])?>" alt="<?=htmlentities($articlesData[$i]['strip_title'])?>" src="<?php echo str_replace("_s","_300x200",$articlesData[$i]['contentImageURL']);?>" width="300" height="200"></a>
                        </div>
                        <div class="article-caption">
                            <p><?php echo formatArticleTitle(strip_tags($articlesData[$i]['summary']),130);?></p>
                            <div class="article-cmnt">
                            <?php if($commentCountStr != "") { ?><i class="country-sprite comment-icon"></i><?php echo $commentCountStr; } ?> <?php echo $viewCountStr;?>                            
                            </div>
                        </div>                        
                        <div class="read-more-col">
                            <a href="<?php echo $articlesData[$i]['contentURL'];?>" target="_blank" class="flLt read-more-btn">Read More <span>&rsaquo;</span> </a>
                        </div>
                    </div>
                <?php
            
                echo $liCloseTag;
                
                if($currentPosition >= 3) {
                        $currentPosition = 1;
                } else {
                        $currentPosition++;
                }
                    
            }   // End of for($i = 0; $i < $articlesCount; $i++).            
            
        ?>
            </ul>
        </div>
        <div class="clearwidth">
            <ol class="featured-slider" id="conOrgWidgetbullet"><?php            
            for($i = 0; $i < $totalArticleSlides; $i++) {
                $className = "";
                if($i == 0) {
                    $className = 'class="active"';
                }
            ?>
                <li <?=$className?> onClick="bubbleOriginatedAnimation(<?=$i?>);"></li>
            <?php
            }
            ?>
            </ol>
        </div>
    </div>
</div>
<script>
    var currentSlideNumber = 0;
    var totalArticlesSlides = <?=$totalArticleSlides?>;
</script>