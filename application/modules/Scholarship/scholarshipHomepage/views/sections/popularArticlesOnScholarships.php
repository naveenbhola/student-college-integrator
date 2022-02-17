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
<div class="lft__side__article flt__lft">
   <h3 class="fnt__20__semi clr__3 mb__10">Most Popular Articles on Scholarships</h3>
   <div class="data__with__img v__top mb__20">
      <div class="scholar__pic ib__block">
         <img class="lazy" data-original="<?php echo resizeImage($firstContent['contentImageURL'],'300x200'); ?>" alt="<?php echo htmlentities($firstContent['strip_title']); ?>">
      </div>
      <div class="schlr__article__data ib__block v__top">
            <a class="fnt__16__bold clr__blue lh__22 mb__10 ib__block" href="<?php echo $firstContent['contentURL']; ?>"><?php echo htmlentities($firstContent['strip_title']); ?></a>
            <p class="fnt__14 clr__3 lh__24"><?php echo formatArticleTitle(strip_tags($firstContent['summary']),160); ?></p>
            <?php if($firstContent['viewCount']>0 || $firstContent['commentCount']>0){ ?>
            <p class="fnt__12 clr__6 mt__10">
            <?php
            echo ($commentCountString !=''?'<span class="ib__block"><i class="home-sprite home-comment-icon"></i>'.$commentCountString.'</span>':'');
            echo ($firstContent['viewCount']>0 && $firstContent['commentCount']>0?' | ':'');
            echo ($viewCountString != ''?'<span class="ib__block">'.$viewCountString.'</span>':'');
            ?>
            </p>
            <?php } ?>
      </div>
   </div>
<div class="clearfix">
   <?php for($i=1; $i<count($popularContent);$i++){
      $commentCountString = $viewCountString = '';
      if($popularContent[$i]['viewCount']>0 || $popularContent[$i]['commentCount']>0)
      {
         if($popularContent[$i]['commentCount']>0)
         {
            $commentCountString = ($popularContent[$i]['commentCount']>1?$popularContent[$i]['commentCount']." comments":$popularContent[$i]['commentCount']." comment");
         }
         if($firstContent['viewCount']>0)
         {
            $viewCountString = ($popularContent[$i]['viewCount']>1?$popularContent[$i]['viewCount']." views":$popularContent[$i]['viewCount']." view");
         }
      }
      if($i%2!==0){ ?>
         <div class="flat__div clearfix">
      <?php } ?>
         <div class="flt__lft width__50">
            <a href="<?php echo $popularContent[$i]['contentURL']; ?>" class="fnt__14__bold clr__blue ib__block lh__22"><?php echo htmlentities($popularContent[$i]['strip_title']); ?></a>
            <p class="fnt__12 clr__3"><?php echo formatArticleTitle(strip_tags($popularContent[$i]['summary']),160); ?></p>
            <?php if($popularContent[$i]['viewCount']>0 || $popularContent[$i]['commentCount']>0){ ?>
            <p class="fnt__12 clr__6 mt__10">
               <?php
               echo ($commentCountString!=''? '<span class="article-cmnts ib__block"><i class="home-sprite home-comment-icon"></i>'.$commentCountString.'</span>':'');
               echo ($popularContent[$i]['viewCount']>0 && $popularContent[$i]['commentCount']>0?' | ':'');
               echo ($viewCountString != ''?'<span class="ib__block">'.$viewCountString.'</span>':'');
               ?>
            </p>
            <?php } ?>
         </div>
  <?php if($i%2==0){ ?>
         </div>
  <?php }
   }?>
</div>


</div>
