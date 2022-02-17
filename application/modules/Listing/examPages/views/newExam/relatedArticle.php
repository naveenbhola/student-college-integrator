<?php if(is_array($examPageArticles) && count($examPageArticles)>0){ 
    $heading = (count($examPageArticles)>1)?'Articles':'Article';
  ?>
<div class="slider__sec">
                        <h2 class="mt__10 f20__clr3 no_border recmd_hdg"><?=$heading?> related to <?=$examName?></h2>
                        <div class="slider__col articleSlider">
			    <?php if(count($examPageArticles)>3){ ?>
                            <a class="prev__slide"><i></i></a>
			    <?php } ?>
                             <ul class="featuredSlider">
                                <?php foreach ($examPageArticles as $article){ ?>
                               <li>
                                   <div class="dflt__card global-box-shadow">
                                      <p class="f14__clr3 fnt__sb fix__height"><?=(strlen($article['blogTitle'])>90)?substr($article['blogTitle'],0,87)."...":$article['blogTitle']?></p>
                                      <a class="f14__clrb ib__block mtop__10" href="<?=SHIKSHA_HOME.$article['url']?>" target="_blank" ga-attr="ARTICLE">Read More</a>
                                   </div>
                               </li>
                                <?php } ?>
                             </ul>
			    <?php if(count($examPageArticles)>3){ ?>
                            <a class="nxt__slide"><i></i></a>
			    <?php } ?>
                        </div>
</div>

<?php } ?>

