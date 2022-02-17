<div class="gal-layer gal-layer-large">
        <div class="gal-hd">
        <?php 
        if($listing_type == 'university')
        {
            $GA_currentPage = 'UNIVERSITY DETAIL PAGE';
            $GA_Tap_On_View_All = 'VIEW_ALL_GALLERY_UNIVERSITYDETAIL_MOBLDP';
        }
        elseif($listing_type == 'institute')
        {
            $GA_currentPage = 'INSTITUTE DETAIL PAGE';
            $GA_Tap_On_View_All = 'VIEW_ALL_GALLERY_INSTITUTEDETAIL_MOBLDP';
        }

        $hideHeading = 0;
          if((count($media['photos']['order']) == 1 )&& empty($media['videos']) && (count($media['videos']) == 0) && $media['photos']['order'][0] == 'Others')
          {
            $hideHeading = 1;
          }

        ?>
            <a class="grid-icon" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_View_All;?>','<?php echo $GA_userLevel;?>');openViewGalleryListLayer(<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')"></a>
            <a href="javascript:void(0);" onclick="closeGalleryDetailLayer();" >&times;</a>
            <p class="para-L2" id="galleryHeading"><?php echo $tagName;?></p>            

        </div>
        <div class="galry-block opn-glry">
        <div id="galleryBkdImg" class="mediaLoader">
            <img src="">
        </div>
            <ul class="mediaBlock">
                <?php 
                    $displayActvSlide = false;
                    $instituteGallerytext  = !empty($abbreviation) ?$abbreviation : $institute_name;
                        $totalMedia = $media['photos']['totalPhotos'] + count($media['videos']['Videos']);
                      $i = 1;
                      foreach($media['photos']['order'] as $oKey => $oValue){
                      foreach ($media['photos']['list'][$oValue] as $childKey => $childValue) {
                        if($tagName == $oValue && $mediaId == $childValue['media_id'] && !$displayActvSlide)
                        {
                            $activeClass = 'atvSlide currentMedia';
                            $src = $childValue['media_url'];
                            $dataOriginal = $childValue['media_url'];
                            $displayActvSlide = true;
                        }
                        else
                        {
                            $activeClass = '';
                            $src = '';
                            $dataOriginal = $childValue['media_url'];
                        }
                        ?>

                        <li class="<?php echo $activeClass;?>" secTag="image" imgid="<?php echo $i;?>" data-title="<?php echo htmlentities($childValue['media_title']);?>" data-menuTitle="<?php echo $oValue;?>"><div class="img-blck"><img alt="<?php echo htmlentities($instituteGallerytext.' - '.$childValue['media_title']);?>" title="<?php echo htmlentities($instituteGallerytext.' - '.$childValue['media_title']);?>" src="<?php echo $src;?>" data-slider="<?php echo $i;?>"  data-loader="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" data-original="<?php echo $dataOriginal;?>" align="middle"/></div></li>
                      <?php $i++;}} 
                      foreach($media['videos']['Videos'] as $videoKey => $videoValue) {
                         if($tagName == 'Videos' && $mediaId == $videoValue['media_id'] && !$displayActvSlide)
                        {
                            $activeClass = 'atvSlide currentMedia';
                            $displayActvSlide = true;
                        }
                        else
                        {
                            $activeClass = '';
                        }
                        $videoUrl = str_replace("youtube.com/v/","youtube.com/embed/",$videoValue['media_url']);
                        ?>
                          <li class="img-blck <?php echo $activeClass;?>" data-video="<?php echo $videoKey;?>" vid="<?php echo $videoValue['media_id'];?>" secTag="video" imgid="<?php echo $i;?>" data-title="<?php echo htmlentities($videoValue['media_title']);?>" data-menuTitle="Videos"><img alt="<?php echo htmlentities($instituteGallerytext.' - '.$videoValue['media_title']);?>" title="<?php echo htmlentities($instituteGallerytext.' - '.$videoValue['media_title']);?>" src="" data-loader="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" data-slider="<?php echo $i;?>" data="<?php echo $videoUrl;?>"/></li>
                        <?php $i++; } ?>
                <div id="iframeTag_24" class="iframeTag_" style="position:absolute; z-index:999; background:transparent; width:100%;height: 14.5%;left:0;display: none"></div>
                <iframe data-p="" class="xid_img_size youtube-player" id="xid_img_size"  scrolling="yes" style="display:none;padding-left:10px;border:0px;width:100%;padding-right:10px" src="about:blank"  wmode="transparent" type="application/x-shockwave-flash"></iframe>
            </ul>
            <div class="mediaclass">
                <p id="mediaTitle" class="mediaTitle"></p>
            </div>
            <div class="slider-count">
                <i class="bck-icn" id="prevClick"></i>
                <span><p id="currentMedia" class="mediaCount"></p> of <p id="totalMedia" class="mediaCount"><?php echo $totalMedia;?></p></span>
                <i class="nxt-icn" id="nextClick"></i>
            </div>
        </div>
</div>
<script type="text/javascript">
var hideHeading = <?php echo $hideHeading;?>;
    if(hideHeading == 1)
    {
        $('#galleryHeading').attr('style','visibility:hidden');
    }
    $('#currentMedia').text($('li.currentMedia').attr('imgid'));
    $('#mediaTitle').text($('li.currentMedia').attr('data-title'));
</script>
