<?php
   $firstContent = $popularContent[0];
   $commentCountString = $viewCountString = '';
   if($firstContent['viewCount']>0 || $firstContent['commentCount']>0)
   {
      if($firstContent['commentCount']>0)
      {
         $commentCountString = ($firstContent['commentCount']>1?$firstContent['commentCount']." comments":$firstContent['commentCount']." comment");
      }
      if($firstContent['viewCount']>0)
      {
         $viewCountString = ($firstContent['viewCount']>1?$firstContent['viewCount']." views":$firstContent['viewCount']." view");
      }
   }
?>
       <section class="article_col cmn__pad">
          <h2 class="fnt16_bold clr__3">Article related to Scholarships</h2>
          <div class="most__schlr">
            <h3 class="fnt__14__semi clr__3">Most popular Articles on Scholarships</h3>
          </div>
          <div class="frChl">
          <?php for($i=0; $i<count($popularContent);$i++){
                    $commentCountString = $viewCountString = '';
                    if($popularContent[$i]['viewCount']>0 || $popularContent[$i]['commentCount']>0){
                       if($popularContent[$i]['commentCount']>0){
                          $commentCountString = ($popularContent[$i]['commentCount']>1?$popularContent[$i]['commentCount']." comments":$popularContent[$i]['commentCount']." comment");
                       }
                       if($firstContent['viewCount']>0){
                          $viewCountString = ($popularContent[$i]['viewCount']>1?$popularContent[$i]['viewCount']." views":$popularContent[$i]['viewCount']." view");
                       }
                    }
         ?>
          <div class="flat__div">
             <a href="<?php echo $popularContent[$i]['contentURL'];?>"class="ib__block fnt13_bold clr__blue"><?php echo htmlentities($popularContent[$i]['strip_title']); ?></a>
             <p class="fnt__12 clr__6 ln-hgt"><?php echo formatArticleTitle(strip_tags($popularContent[$i]['summary']),160); ?></p>
             <?php if($popularContent[$i]['viewCount']>0 || $popularContent[$i]['commentCount']>0){ ?>
                 <p class="fnt__12 clr__9 mt__5">
                   <?php 
                    echo ($commentCountString !=''?'<span class="ib__block icon_place"><i class="sprite home-comment-icon"></i> '.$commentCountString.'</span>':'');
                    echo ($popularContent[$i]['viewCount']>0 && $popularContent[$i]['commentCount']>0?' | ':'');
                    echo ($viewCountString != ''?'<span class="ib__block">'.$viewCountString.'</span>':'');
                   ?>  
                 </p>
                 <?php } ?>
          </div>
          <?php } ?>
            </div>
          <h3 class="fnt__14__semi clr__3 mt__10">Most Recent Articles</h3>
          <section class="rec-art">
          <?php for($i=0; $i<count($recentContent);$i++){
                    $commentCountString = $viewCountString = '';
                    if($recentContent[$i]['viewCount']>0 || $recentContent[$i]['commentCount']>0){
                       if($recentContent[$i]['commentCount']>0){
                          $commentCountString = ($recentContent[$i]['commentCount']>1?$recentContent[$i]['commentCount']." comments":$recentContent[$i]['commentCount']." comment");
                       }
                       if($firstContent['viewCount']>0){
                          $viewCountString = ($recentContent[$i]['viewCount']>1?$recentContent[$i]['viewCount']." views":$recentContent[$i]['viewCount']." view");
                       }
                    }
         ?>
          <div class="flat__div">
             <a href="<?php echo $recentContent[$i]['contentURL'];?>"class="ib__block fnt13_bold clr__blue"><?php echo htmlentities($recentContent[$i]['strip_title']); ?></a>
             <p class="fnt__12 clr__6 ln-hgt"><?php echo formatArticleTitle(strip_tags($recentContent[$i]['summary']),160); ?></p>
             <?php if($recentContent[$i]['viewCount']>0 || $recentContent[$i]['commentCount']>0){ ?>
                 <p class="fnt__12 clr__9 mt__5">
                   <?php 
                    echo ($commentCountString !=''?'<span class="ib__block icon_place"><i class="sprite home-comment-icon"></i> '.$commentCountString.'</span>':'');
                    echo ($recentContent[$i]['viewCount']>0 && $recentContent[$i]['commentCount']>0?' | ':'');
                    echo ($viewCountString != ''?'<span class="ib__block">'.$viewCountString.'</span>':'');
                   ?>  
                 </p>
                 <?php } ?>
          </div>
          <?php } ?>
              </section>
       </section>