<?php if(is_array($examPageArticles) && count($examPageArticles)>0){ 
    $articleCount = count($examPageArticles);
    $heading = ($articleCount>1)?'Articles':'Article';
    ?>
            <section>
            <div class="data-card">
                <h2 class="color-3 f16 heading-gap font-w6"><?php echo $articleCount.' '.$heading?> related to <?=$examName?></h2>
                <div class="card-cmn color-w f14 color-3">
		    <input type="checkbox" class="read-more-state hide" id="post-11">
                    <ul class="cls-ul ins-acc-ul read-more-wrap">
                        <?php
                            $i = 0;
                            foreach ($examPageArticles as $article){
                        ?>
                        <li><a class="color-3 f14" href="<?=SHIKSHA_HOME.$article['url']?>"><?=$article['blogTitle']?></a></li>
                        <?php
                            $i++;
                        } ?>
                    </ul>

                    <?php /*if(count($examPageArticles)>3){ ?>
                        <label for="post-11" data-vars-event-name="VIEW_ALL_ARTICLES" class="read-more-trigger btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic">View All <?=count($examPageArticles)?> Articles</label>
		    <?php }*/ ?>
                </div>
               </div>
          </section>
<?php } ?>

