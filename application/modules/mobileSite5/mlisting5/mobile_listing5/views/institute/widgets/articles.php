<?php if(!empty($articleInfo)){

    $GA_Tap_On_Article = 'ARTICLE_TUPLE';
    $GA_Tap_On_View_All = 'VIEW_ALL_ARTICLES';
    ?>
    <div class="crs-widget listingTuple" id="articles">
            <h2 class="head-L2">Articles<?php if($totalArticles > 3){?><span class="head-L5 pad-left4">(Showing <?=count($articleInfo)?> of <?=$totalArticles?> Articles)</span><?php } ?></h2>
            <div class="lcard">
                <ul class="evnt-List articles">
                    <?php foreach($articleInfo as $key=>$article){?>
                    <li ga-attr="<?=$GA_Tap_On_Article;?>" onclick = "window.location='<?php echo $articles['url'];?>'">
                        <a class="para-L4" href="<?=$article['url'];?>"><?=htmlentities($article['blogTitle'])?></a>
                    </li>
                    <?php } ?>
                </ul>
                <?php if($totalArticles > 3){?>
                <a class="btn-mob-ter" href="<?=$all_article_url?>" ga-attr="<?=$GA_Tap_On_View_All;?>" id="articles_count">View all articles</a>
                <?php } ?>
            </div>
    </div>
<?php } ?>

<?php if(!empty($totalArticles)){?>
  <script>
  var totalArticles = <?php echo $totalArticles;?>;
  </script>
<?php } ?>