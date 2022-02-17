<?php if(!empty($relatedArticles)){?>
    <div class="examRight-widget">
        <div class="bottomBrder">
            <h2>
                <div class="widget-head"><p>You might also like<i class="common-sprite blue-arrw"></i></p></div>
            </h2>
        </div>
        <div class="examRelated-list">
            <ul>
                <?php
                $count=1;
                $srcStr = 'src';
                $lazyClass = '';
                foreach ($relatedArticles as $key => $value) {
                    if($count > 1)
                    {
                        $lazyClass = 'class="lazy" ';
                        $srcStr = 'data-original';
                    }
                    ?>
                    <li class="<?php if($count==count($relatedArticles)) echo 'last';?>" >
                        <div class="artcle-fig">
                            <a target="_blank" href="<?php echo $value['contentURL']; ?>"><img height="213" width="320" alt="<?php echo htmlentities($value['strip_title']); ?>" <?php $imgUrl = str_replace("_s","_300x200",$value['contentImageURL']);  echo $lazyClass.$srcStr.'="'.$imgUrl.'"';?>></a>
                        </div>
                        <div class="artcle-info">
                            <a target="_blank" href="<?php echo $value['contentURL']; ?>"><?php echo htmlentities(formatArticleTitle($value['strip_title'], 150)); ?></a>
                            <?php if(!empty($value['commentCount'] )|| !empty($value['viewCount'])){ ?>
                                <span class="sop-commnt-title">
	  				<?php if(!empty($value['commentCount']))
                    {
                        echo($value['commentCount']==1)?'<i class="sop-sprite gray-commnt-icon"></i>
 1 comment':'<i class="sop-sprite gray-commnt-icon"></i>
 '.$value['commentCount'].' comments';
                    }
                    if(!empty($value['commentCount']) && !empty($value['viewCount']))
                    {
                        echo ' | ';
                    }
                    if(!empty($value['viewCount']))
                    {
                        echo($value['viewCount']==1)?'1 view':$value['viewCount'].' views';
                    } ?>
	  				</span>
                            <?php }?>

                        </div>
                    </li>
                    <?php $count++;}?>
            </ul>
        </div>
    </div>
<?php } ?>