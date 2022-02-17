<?php if(!empty($articleInfo)){ 
        $GA_Tap_On_Article = 'ARTICLE_TUPLE';
        $GA_Tap_On_View_All = 'VIEW_ALL_ARTICLES';
    ?>
    <section>
         <div class="data-card m-5btm">
             <h2 class="color-3 f16 heading-gap font-w6">Articles</h2>
             <div class="card-cmn color-w">
                 <ul class="course-li">
         <?php foreach($articleInfo as $key=>$article){?>
                     <li class="pos-rl">
                         <a href="<?php echo $article['url'];?>" class="f14 l-16 color-3 block font-w6 ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Article;?>"><?php echo htmlentities($article['blogTitle']);?></a>
                     </li>
         <?php } ?>
                 </ul>
         <?php if($totalArticles > 3){?>
                 <a href ="<?php echo $all_article_url?>" class="btn btn-ter color-w color-3 f14 font-w6 m-15top ga-analytic" data-vars-event-name="<?=$GA_Tap_On_View_All;?>">View all Articles</a>
         <?php } ?>
             </div>
         </div>
      </section>
<?php } ?>