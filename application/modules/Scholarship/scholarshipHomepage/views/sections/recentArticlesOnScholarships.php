<div class="rght__side__article flt__right">
   <h3 class="fnt__20__semi clr__3 mb__10">Most Recent Articles</h3>
   <div class="clearfix">
   <?php foreach($recentContent as $recent){
      $commentCountString = $viewCountString = '';
      if($recent['viewCount']>0 || $recent['commentCount']>0)
      {
         if($recent['commentCount']>0)
         {
            $commentCountString = ($recent['commentCount']>1?$recent['commentCount']." comments":$recent['commentCount']." comment");
         }
         if($recent['viewCount']>0)
         {
            $viewCountString = ($recent['viewCount']>1?$recent['viewCount']." views":$recent['viewCount']." view");
         }
      }
      ?>
      <div class="recent__articles">
         <a class="ib__block fnt__14__bold clr__blue lh__22" href="<?php echo $recent['contentURL']; ?>"><?php echo htmlentities($recent['strip_title']); ?></a>
         <p class="fnt__12 clr__9 mt__5">
            <?php
            echo ($commentCountString!=''?'<span class="article-cmnts ib__block"><i class="home-sprite home-comment-icon"></i>'.$commentCountString.'</span>':'');
            echo ($recent['viewCount']>0 && $recent['commentCount']>0?' | ':'');
            echo ($viewCountString != ''?'<span class="ib__block">'.$viewCountString.'</span>':'');
            ?>
         </p>
      </div>
   <?php } ?>
   </div>
</div>