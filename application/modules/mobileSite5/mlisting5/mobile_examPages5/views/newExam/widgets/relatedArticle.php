<?php if(is_array($examPageArticles) && count($examPageArticles)>0){ 

    $articleCount = count($examPageArticles);
    $heading = ($articleCount>1)?'Articles':'Article';
    ?>
            <section id="articleSection">
            <div class="data-card">
                <h2 class="color-3 f16 heading-gap font-w6"><?php echo $articleCount.' '.$heading?> related to <?=$examName?></h2>
                <div class="lcard color-w f14 color-3">
                    <ul class="cls-ul ins-acc-ul">
                        <?php 
                         $i = 0; 
			     foreach ($examPageArticles as $article){
                        ?>
                        <li <?php if($i>=3){ echo "style='display:none;'". "class='artcl'";}?> ><a class="color-3 f14" href="<?=SHIKSHA_HOME.$article['url']?>" ga-attr="ARTICLE"><?=$article['blogTitle']?></a></li>
                        <?php $i++;
                        } ?>
                    </ul>
                    
                    <?php if(count($examPageArticles)>3){ ?>
                    <div class="btn-sec">
                        <a href="javascript:void(0);" class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm viewAllArtcl" ga-attr="VIEW_ALL_ARTICLES">View All <?=count($examPageArticles)?> Articles</a>
                    </div>
                    <?php } ?>
                </div>
               </div>
          </section>
<?php } ?>

