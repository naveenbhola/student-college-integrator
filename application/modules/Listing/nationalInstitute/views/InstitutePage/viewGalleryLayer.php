<?php if($listingType == 'university')
  {
    $GA_currentPage = 'UNIVERSITY DETAIL PAGE';
    $GA_Tap_On_Tag = 'GALLERY_LAYER_TAG_UNIVERSITYDETAIL_DESKLDP';
  }
  elseif($listingType == 'institute')
  {
    $GA_currentPage = 'INSTITUTE DETAIL PAGE';
    $GA_Tap_On_Tag = 'GALLERY_LAYER_TAG_INSTITUTEDETAIL_DESKLDP';
  }else if($listingType == 'course'){
        $GA_currentPage = 'COURSE DETAIL PAGE';
        $GA_Tap_On_Tag = 'GALLERY_LAYER_TAG_COURSEDETAIL_DESKTOP';
  }
?>
     <div class="slider" id="gallery_slider">
      <div class="menuBg slider-strip">      
      <div class="rsThumbsArrow rsThumbsArrowLeft gradLeftToRight">
             <i class="rsThumbsArrowIcn photonIcons photonSmallArrL" id="menuSliderPrev"></i>
           </div>

        <ul class="nav1 phListing menuList">
        <?php 
        $instituteGallerytext  = !empty($abbreviation) ?$abbreviation : $institute_name;
        $headerOrder = 0;
        $displayedTags = 0;
        foreach($media['photos']['order'] as $orderKey => $orderValue) {?>
          <li><a class="a <?php echo (($tagName == $orderValue || ($tagName == 'Others' && $orderValue == 'Photos')) ? 'actv1' : '');?>" data-id="<?php echo $orderKey;?>" id="<?php echo $orderKey;?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Tag;?>','<?php echo $GA_userLevel;?>');"><span id="tagText"><?php echo $orderValue;?></span> (<?php echo count($media['photos']['list'][$orderValue]);?>)</a></li>
          <?php $headerOrder = $orderKey; $displayedTags++;} if(!empty($media['videos']['Videos']) && count($media['videos']['Videos']) > 0) {?>
            <li><a data-head="video" class="videoLink a <?php echo ($tagName == 'videos' ? 'actv1' : '');?>" data-id="<?php echo $headerOrder+1;?>" id="<?php echo $headerOrder+1;?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Tag;?>','<?php echo $GA_userLevel;?>');">Videos (<?php echo count($media['videos']['Videos']);?>)</a></li>
            <?php $displayedTags++; } ?>
      
          <p class="clr"></p>
        </ul>
        <div class="rsThumbsArrow rsThumbsArrowRight"><div class="rsThumbsArrowIcn" id="menuSliderNext"></div></div>
       
      </div>
        <a href="javascript:void(0);" class="cls-head" id="close-g" onclick="unBindArrowKeysOnGalleryLayer()"><i class="close"></i></a>      
          
      <div class="s1">
        <a class="sldrIcn prev imgPrevNext" id="phImgPrev"><img src=<?=SHIKSHA_HOME."/public/images/arrow-02.png"?>></a>
        <a class="sldrIcn next imgPrevNext" id="phImgNext"><img src=<?=SHIKSHA_HOME."/public/images/arrow-01.png"?>></a>
        <div class="bxOutr">
          <div id = "photonBkgndImg" style = "display:none;margin: 0% auto;text-align: center;">
            <img src="">
        </div>
        <div  id="photonLoader" class="xidLoader"></div>
          <ul class="bxCntnt actvSlider" id="n_photos" style="display:block;">
            <?php 
            $i = 1 ;
            $addedActvSlide = false;
            foreach($media['photos']['order'] as $orderListKey => $orderListValue) 
              {
                foreach($media['photos']['list'][$orderListValue] as $listKey => $listValue) {
                        if(($tagName == $orderListValue || ($tagName == 'Others' && $orderListValue == 'Photos')) && $listValue['media_id'] == $media_id && !$addedActvSlide)
                        {
                            $activeClassName = 'atvSlide atvSlide1';
                            $src = $listValue['media_url'];
                            $dataOriginal = $listValue['media_url'];
                            $addedActvSlide = true;
                        }
                        else
                        {
                            $activeClassName = ''; 
                            $src = '';
                            $dataOriginal = $listValue['media_url'];
                        }
                  ?>

                <li data-p="<?php echo $orderListKey;?>" data-s="n_video" data-slide="<?php echo $i++;?>"  class="<?php echo $activeClassName;?>"><a ><img alt="<?php echo htmlentities($instituteGallerytext.' - '.$listValue['media_title']);?>" title="<?php echo htmlentities($instituteGallerytext.' - '.$listValue['media_title']);?>" align="middle" class="xid_img_size" id="galleryImage" src="<?php echo $src;?>" data-original="<?php echo $dataOriginal;?>" /></a></li>
            <?php }} ?>
            <iframe data-p="<?php echo $headerOrder+1;?>" class="xid_img_size" id="xid_img_size"  scrolling="yes" style="display:none;padding-left:10px;border:0px;" src="about:blank"  wmode="transparent" type="application/x-shockwave-flash"></iframe>
          <?php// } ?>
         </ul>
         
          
        </div>
      </div>
      <div class="img-strip thumbsWrpr">
            <div class="photoCaption" id="title_media" style="display:none">
              		<span id="galleyTitle"></span>
            </div>
         
           <div class="slider-strip slider-strip1">
           <div class="rsThumbsArrow rsThumbsArrowLeft gradLeftToRight">
             <i class="rsThumbsArrowIcn photonIcons photonSmallArrL" id="phSliderPrev"></i>
           </div>
           
            <ul class="imgSlider">
              <?php 
              $i = 1;
              $menuItem = 1;
              foreach($media['photos']['order'] as $oKey => $oValue){
              foreach ($media['photos']['list'][$oValue] as $childKey => $childValue) {
                ?>
                <li secTag="image" imgid="<?php echo $i;?>" data-title="<?php echo htmlentities($childValue['media_title']);?>" data-menuId="<?php echo $menuItem;?>"><img alt="<?php echo htmlentities($instituteGallerytext.' - '.$childValue['media_title']);?>" title="<?php echo htmlentities($instituteGallerytext.' - '.$childValue['media_title']);?>" src="<?php echo $childValue['thumbnail_url'];?>" data-slider="<?php echo $i;?>" onclick="changeGalleryImage('<?php echo $i?>')" data-loader="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif"/></li>
              <?php $i++;}$menuItem++;} 
              foreach($media['videos']['Videos'] as $videoKey => $videoValue) {
                $videoUrl = str_replace("youtube.com/v/","youtube.com/embed/",$videoValue['media_url']);
                ?>
                  <li data-video="<?php echo $videoKey;?>" vid="<?php echo $videoValue['media_id'];?>" secTag="SVIDEO" imgid="<?php echo $i;?>" data-title="<?php echo htmlentities($videoValue['media_title']);?>" data-menuId="<?php echo $menuItem;?>"><img alt="<?php echo htmlentities($instituteGallerytext.' - '.$videoValue['media_title']);?>" title="<?php echo htmlentities($instituteGallerytext.' - '.$videoValue['media_title']);?>" src="<?php echo $videoValue['thumb_url'];?>" data-loader="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" data-slider="<?php echo $i;?>" onclick="changeGalleryImage('<?php echo $i?>','video')"  data="<?php echo $videoUrl;?>"/></li>
                <?php $i++; } ?>

                


              
          </ul>
          <div class="rsThumbsArrow rsThumbsArrowRight"><div class="rsThumbsArrowIcn" id="phSliderNext"></div></div>
         </div>
         
      </div>
    </div>

<script type="text/javascript">
  enableGalleryBelowSlider('<?php echo $tagName;?>');
  var displayedTags = <?php echo $displayedTags;?>;
 if(displayedTags == 1 && ($j('ul.menuList > li > a[data-id="0"] > span[id="tagText"]').text() == "Others"))
 {
    $j('ul.menuList > li > a[data-id="0"]').attr('style','visibility:hidden;cursor:normal');
    $j('ul.menuList > li').attr('style','visibility:hidden;cursor:normal');
 }
</script>
