<section class="content-wrap2" >
  <div class="articleDetails">
    <?php 
      $blogSlideshow = $blogObj->getDescription();
      $slidesCount = count($blogSlideshow);
      $slideNo = (isset($_COOKIE['slideNo']) && !empty($_COOKIE['slideNo']))?$_COOKIE['slideNo']:0;
      if(isset($_COOKIE['sliderBlogId']) && !empty($_COOKIE['sliderBlogId'])) {
      $cookieBlogId =  $_COOKIE['sliderBlogId'];
      if($blogId != $cookieBlogId){ $slideNo =  0;}
      }
      if($slideNo > $slidesCount -1){ $slideNo = 0;}
      $showPrev = false;
      $showNext = false;
      $blog = $blogSlideshow[$slideNo];
      if($slideNo > 0) $showPrev = true;
      if($slideNo != $slidesCount -1) $showNext = true;
    ?>
    <div class="slide-content" id="slider">
      <p><strong><?=html_escape($blog->getTitle())?></strong></p>
      <p><?=html_escape($blog->getSubtitle())?></p>
    </div>
    <figure class="article-img-box">
      <?php if($showPrev):?>
        <a href="javascript:void(0);" onclick="setCookie('slideNo','<?php echo $slideNo-1;?>',0,'/',COOKIEDOMAIN);
                             setCookie('sliderBlogId','<?php echo $blogId;?>',0,'/',COOKIEDOMAIN);
                                    location.reload();" 
                   class="sprite prevSlider-icon"></a>
      <?php endif;?>  
      <img src="<?=$blog->getImage();?>" alt="" />
      <?php if($showNext):?>
      <a href="javascript:void(0);" onclick="setCookie('slideNo','<?php echo $slideNo+1;?>',0,'/',COOKIEDOMAIN);                                  setCookie('sliderBlogId','<?php echo $blogId;?>',0,'/',COOKIEDOMAIN);
                                   location.reload();"  
               class="sprite nextSlider-icon"></a>
      <?php endif;?>

      <div class="slide-counter"><?=($slideNo+1)?> of <?php echo $slidesCount; ?> photos</div>
    </figure>
    <div class="slide-content">
      <?=addAltTextMobile($blogObj->getTitle(), $blog->getDescription());?>
    </div>
    <ul class="nxt-prev">
      <?php if($showPrev):?>
          <li class="flLt" >
            <a href="javascript:void(0);" onclick="setCookie('slideNo','<?php echo $slideNo-1;?>',0,'/',COOKIEDOMAIN); 
           setCookie('sliderBlogId','<?php echo $blogId;?>',0,'/',COOKIEDOMAIN);
           location.reload(); jQuery('#slider')[0].scrollIntoView();">
          <i class="sprite prv-icon"></i>Previous</a>
          </li>
      <?php endif;?>
      <?php if($showNext):?>
        <li class="flRt" >
          <a href="javascript:void(0);" onclick="setCookie('slideNo','<?php echo $slideNo+1;?>',0,'/',COOKIEDOMAIN); 
          setCookie('sliderBlogId','<?php echo $blogId;?>',0,'/',COOKIEDOMAIN);
          location.reload(); jQuery('#slider')[0].scrollIntoView();">
          <i class="sprite nxt-icon"></i>Next</a>
        </li>      
      <?php endif;?>                     
    </ul>
    <div class="clearfix"></div>
                <!--div class="slide-content">
        <a href="#" onclick= "jQuery('#relatedArticles')[0].scrollIntoView();">View Related Articles</a>
          </div-->
    <div class="clearfix"></div>
  </div>
  <div class="article-readmore" >
    <a class="link-blue-medium articleViewMore" href="javascript:void(0)" >Read Full Article</a>
  </div>  

</section>
<div class="social-wrapper-btm">
  <?php $this->load->view("mcommon5/socialSharingBand", array('widgetPosition' => 'ADP_Bottom')); ?>
</div>