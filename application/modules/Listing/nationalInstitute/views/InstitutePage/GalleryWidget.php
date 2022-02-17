     <div class="new-row">
       <div class="group-card no__pad gap listingTuple" id="gallery">
         <h2 class="head-1 gap">Gallery</h2>
         <div class="flex-ul gallery">
         <?php 
              $GA_Tap_On_Gallery = 'VIEW_GALLERY';
              $GA_Tap_On_View_All = 'VIEW_ALL_GALLERY';

         $displayedTags        = 0;
         $viewMediaTags        = count($media['photos']['order']);
         $viewMediaTags        += !empty($media['videos']['Videos']) && count($media['videos']['Videos']) > 0 ? 1 : 0;
        
         foreach($media['photos']['order'] as $photoKey => $photoValue) {?>
          <?php if($photoKey <= 4) {?>
             <div class="album-cover" id="album">
             <?php if(count($media['photos']['list'][$photoValue]) > 1) {?>
              <div class="album-L1"></div>
              <div class="album-L2"></div>
              <?php } ?>
              <div id= "gwidget_<?php echo $displayedTags;?>" class="album-L3" ga-attr="<?=$photoValue.'_'.$GA_Tap_On_Gallery?>" onclick="openGalleryLayer(<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo $photoValue?>',<?php echo $media['photos']['list'][$photoValue][0]['media_id'];?>,'<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')">
                    <img class="lazy" alt="<?php echo htmlentities($listingGallerytext.' - '.$media['photos']['list'][$photoValue][0]['media_title']);?>" title="<?php echo htmlentities($listingGallerytext.' - '.$media['photos']['list'][$photoValue][0]['media_title']);?>" data-original="<?php echo $media['photos']['list'][$photoValue][0]['media_widget']?>" />
                    <?php if($displayedTags == 4 )
                     {
                      $viewMoreMsg = '';
                      if($media['photos']['totalPhotos'] > 0)
                        $viewMoreMsg = ' Photos';
                      if(!empty($viewMoreMsg) && !empty($media['videos']['Videos']) && count($media['videos']['Videos']) > 0)
                          $viewMoreMsg .= ' and ';
                      if(!empty($media['videos']['Videos']) && count($media['videos']['Videos']) > 0)
                        $viewMoreMsg .= ' Videos';
                      if(!empty($viewMoreMsg)) {
                      ?>

                      <div class="more-images" id="viewMediaTags" style="display:none" ga-attr="<?=$GA_Tap_On_View_All?>" onclick="openGalleryLayer(<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo $media['photos']['order'][0]?>',<?php echo $media['photos']['list'][$media['photos']['order'][0]][0]['media_id'];?>,'<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')"><p class="show-images"> <strong>+<?php echo $media['photos']['totalPhotos']; ?></strong> <br><?php echo $viewMoreMsg;?> </p></div>
                    <?php }} ?>
              </div>
	      <script type="application/ld+json">
		{
		  "@context" : "http://schema.org",
		  "@type" : "ImageObject", 
		  "name" : "<?php echo htmlentities($listingGallerytext.' - '.$media['photos']['list'][$photoValue][0]['media_title']);?>",
		  "contentUrl" : "<?=$media['photos']['list'][$photoValue][0]['media_widget']?>"
		}
	      </script>

              <p class="head-5" id="gwidget_text_<?php echo $displayedTags;?>"><?php echo $photoValue;?></p>
            </div>
            <?php $displayedTags++;} else { break; }  } ?>

            <?php if(!empty($media['videos']['Videos']) && is_array($media['videos']['Videos']) && count($media['videos']['Videos']) > 0 && $displayedTags < 5 ) { ?>
              <div class="album-cover" id="album">
                <?php if(count($media['videos']['Videos']) > 1) {?>
                <div class="album-L1"></div>
                <div class="album-L2"></div>
                <?php } ?>
                <div class="album-L3" ga-attr="<?='VIDEOS_'.$GA_Tap_On_Gallery?>" onclick="openGalleryLayer(<?php echo $listing_id;?>,'<?php echo $listing_type;?>','videos',<?php echo $media['videos']['Videos'][0]['media_id'];?>,'<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')">
                      <img  alt="<?php echo htmlentities($listingGallerytext.' - '.$media['videos']['Videos'][0]['media_title']);?>" title="<?php echo htmlentities($listingGallerytext.' - '.$media['videos']['Videos'][0]['media_title']);?>" class="lazy" src=""  data-original="<?php echo $media['videos']['Videos'][0]['thumb_url']?>" />
                </div>
              <script type="application/ld+json">
                {
                  "@context" : "http://schema.org",
                  "@type" : "VideoObject",
		  "url" : "<?=$media['videos']['Videos'][0]['media_url']?>",
                  "name" : "<?=htmlentities($listingGallerytext.' - '.$media['videos']['Videos'][0]['media_title']);?>",
		  "description" : "<?=htmlentities($listingGallerytext.' - '.$media['videos']['Videos'][0]['media_title']);?>",
		  "uploadDate" : "<?=$media['videos']['Videos'][0]['uploaded_date']?>",
                  "thumbnailUrl" : "<?=$media['videos']['Videos'][0]['thumb_url']?>"
                }
              </script>

                <p class="head-5" id="gwidget_text_<?php echo $displayedTags;?>">Videos</p>
              </div>
              <?php $displayedTags++;}?>
        </div>
       </div>
     </div>

<script type="text/javascript">
  var viewMediaTags = <?php echo $viewMediaTags;?>;  
</script>
