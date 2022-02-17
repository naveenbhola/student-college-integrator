<div class="bottom-slider newExam-widget">
	<h2>
    <div class="widget-head"><p>You might also like<i class="sprite blue-arrw"></i></p></div>
  </h2>
  <div class="slider-box" id="relatedArticlesSliderWrapper" >
	  <ul id="relatedArticlesUl" style="width: 1900px; left: -305px;" class="relatedArticlesUl">
   	  <?php foreach ($relatedArticles as $key => $value) { ?>
      <li class="swipetuple">
       	  <div style="width:300px; height:198px;">
             <a target="_blank" href="<?php echo ($value['contentURL']); ?>" class="guideLinkImg ui-link">
              <img height="198" width="300" alt="<?php echo htmlentities($value['strip_title']); ?>" src="<?php $imgUrl = str_replace("_s","_300x200",$value['contentImageURL']);  echo $imgUrl;?>"></div>
            </a>            
          <div class="artcle-info">
	          <p><?php echo htmlentities(formatArticleTitle($value['strip_title'], 40)); ?></p>
            <?php if(!empty($value['commentCount'] )|| !empty($value['viewCount'])){ ?>
            <p class="comment-view-nfo">
              <?php if(!empty($value['commentCount']))
            { 
              echo($value['commentCount']==1)?'    <i class="mobile-sop-sprite sop-comment-icon"></i>
 1 comment':'<i class="mobile-sop-sprite sop-comment-icon"></i>
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
            </p>
            <?php } ?>
         </div>
      </li>
      <?php  } ?>
  </ul>
</div>
</div>