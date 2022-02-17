<div class="crs-widget listingTuple" id="gallery">
		<?php 
        if($listing_type == 'course'){
            $GA_Tap_On_Gallery = 'VIEW_GALLERY_COURSEDETAIL_MOBILE';
            $GA_Tap_On_View_All = 'VIEW_ALL_GALLERY_COURSEDETAIL_MOBILE';
        }
        else
        {
            $GA_Tap_On_Gallery = 'VIEW_GALLERY';
            $GA_Tap_On_View_All = 'VIEW_ALL_GALLERY';
        }
        
		$totalMedia = $media['photos']['totalPhotos'] + count($media['videos']['Videos']);
		$mediaText = '';
		if($media['photos']['totalPhotos'] > 0)
			$mediaText .= 'Photos ';
		if(count($media['videos']['Videos']) > 0 && !empty($mediaText))
			$mediaText .= ' & Videos';
		if(count($media['videos']['Videos']) > 0 && empty($mediaText))
			$mediaText .= 'Videos';
        $countText = $totalMedia > 3 ? 3 : $totalMedia;

		?>
        <h2 class="head-L2">Gallery <span class="head-L5">(Showing <?php echo $countText;?> of <?php echo $totalMedia;?>  <?php echo $mediaText;?>)</span></h2>
        <div class="glry-view">
        <?php 
        $displayedPhotos = 0;
        $includeRightDiv = false;
        $numberOfPhotosDisplay = 3;
        foreach($media['photos']['order'] as $orderKey => $orderValue) {
        	foreach ($media['photos']['list'][$orderValue] as $photoKey => $photoValue) {
        		if($displayedPhotos == $numberOfPhotosDisplay)
                { 
        			break;
                }
        		if($displayedPhotos == 0)
        		{	
                    if($totalMedia == 1)
                    {
                        $addMarginClass = 'marginLeft';
                    }

                        ?>
        			<div class="lft-col <?=$addMarginClass;?>"><a href="#galleryDetailList" data-rel="dialog" ga-attr="<?=$GA_Tap_On_Gallery;?>" is-href-url="false" onclick="openGalleryDetailLayer(<?php echo $listing_id;?>,'<?php echo $listing_type?>',<?php echo $photoValue['media_id'];?>,'<?php echo $orderValue;?>','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')"><img class="lazy" id="viewed_<?php echo $displayedPhotos;?>" alt="<?php echo htmlentities($listingGallerytext.' - '.$photoValue['media_title']);?>" title="<?php echo htmlentities($listingGallerytext.' - '.$photoValue['media_title']);?>" data-type="image" data-original="<?php echo $photoValue['media_widget'];?>"/></a></div>
        	<?php }else { 
        		if(!$includeRightDiv) {?>
        			<div class="rgt-col">
        			<?php $includeRightDiv = true;} ?>
	                	<div class="img-blck"><a data-rel="dialog" href="#galleryDetailList" is-href-url="false"><img class="lazy" id="viewed_<?php echo $displayedPhotos;?>" data-type="image" alt="<?php echo htmlentities($listingGallerytext.' - '.$photoValue['media_title']);?>" title="<?php echo htmlentities($listingGallerytext.' - '.$photoValue['media_title']);?>" data-original="<?php echo $photoValue['thumbnail_url'];?>" ga-attr="<?=$GA_Tap_On_Gallery;?>" is-href-url="false" onclick="openGalleryDetailLayer(<?php echo $listing_id;?>,'<?php echo $listing_type?>',<?php echo $photoValue['media_id'];?>,'<?php echo $orderValue;?>','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')" /></a>
                            <?php if($displayedPhotos == $numberOfPhotosDisplay-1) {?>
                                <p class="block-layer" id="viewMoreText" style="display: none"><a class="viewMoreText" data-rel="dialog" href="#galleryList" ga-attr="<?=$GA_Tap_On_View_All;?>" is-href-url="false" onclick="openGalleryViewListLayer(<?php echo $listing_id;?>,'<?php echo $listing_type?>','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')">+<?php echo $totalMedia - $numberOfPhotosDisplay;?> </br/>More</a></p>
                                <?php } ?>
                        </div>
	                		
	                		<?php  } ?>

              <script type="application/ld+json">
                {
                  "@context" : "http://schema.org",
                  "@type" : "ImageObject",
                  "name" : "<?php echo htmlentities($listingGallerytext.' - '.$photoValue['media_title']);?>",
                  "contentUrl" : "<?=$photoValue['media_widget']?>"
                }
              </script>

            	<?php  $displayedPhotos++;}  ?>

        <?php if($displayedPhotos == 3)
                break;} ?>
        <?php foreach($media['videos']['Videos'] as $videoKey => $videoValue) {
            if($displayedPhotos == 3)
                break;
            ?>
            <?php 
            if($displayedPhotos == $numberOfPhotosDisplay)
                {
                    break;
                }
                if($displayedPhotos == 0)
                {  
                    if($totalMedia == 1)
                    {
                        $addMarginClass = 'marginLeft';
                    }
                 ?>
                    <div class="lft-col <?=$addMarginClass;?>"><a data-rel="dialog" href="#galleryDetailList" is-href-url="false"><img class="lazy" id="viewed_<?php echo $displayedPhotos;?>" data-type="video" alt="<?php echo htmlentities($listingGallerytext.' - '.$videoValue['media_title']);?>" title="<?php echo htmlentities($listingGallerytext.' - '.$videoValue['media_title']);?>" data-original="<?php echo $videoValue['thumb_url'];?>" ga-attr="<?=$GA_Tap_On_Gallery;?>"  is-href-url="false" onclick="openGalleryDetailLayer(<?php echo $listing_id;?>,'<?php echo $listing_type?>',<?php echo $videoValue['media_id'];?>,'Videos','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')"  /></a></div>
            <?php }else { 
                if(!$includeRightDiv) {?>
                    <div class="rgt-col">
                    <?php $includeRightDiv = true;} ?>
                        <div class="img-blck"><a data-rel="dialog" href="#galleryDetailList" is-href-url="false"><img class="lazy" id="viewed_<?php echo $displayedPhotos;?>" data-type="video" alt="<?php echo htmlentities($listingGallerytext.' - '.$videoValue['media_title']);?>" title="<?php echo htmlentities($listingGallerytext.' - '.$videoValue['media_title']);?>" data-original="<?php echo $videoValue['thumb_url'];?>" ga-attr="<?=$GA_Tap_On_Gallery;?>" is-href-url="false" onclick="openGalleryDetailLayer(<?php echo $listing_id;?>,'<?php echo $listing_type?>',<?php echo $videoValue['media_id'];?>,'Videos','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')"/></a>
                            <?php if($displayedPhotos == $numberOfPhotosDisplay-1) {?>
                                <p class="block-layer" id="viewMoreText" style="display:none"><a  class="viewMoreText"  data-rel="dialog" ga-attr="<?=$GA_Tap_On_View_All;?>" href="#galleryList" is-href-url="false" onclick="openGalleryViewListLayer(<?php echo $listing_id;?>,'<?php echo $listing_type?>','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')">+<?php echo $totalMedia - $numberOfPhotosDisplay;?> </br/>More</a></p>

                            <?php }  ?>
                            </div>
                            <?php } ?>

              <script type="application/ld+json">
                {
                  "@context" : "http://schema.org",
                  "@type" : "VideoObject",
                  "url" : "<?=$videoValue['media_url']?>",
                  "name" : "<?=htmlentities($listingGallerytext.' - '.$videoValue['media_title']);?>",
                  "description" : "<?=htmlentities($listingGallerytext.' - '.$videoValue['media_title']);?>",
                  "uploadDate" : "<?=$videoValue['uploaded_date']?>",
                  "thumbnailUrl" : "<?=$videoValue['thumb_url']?>"
                }
              </script>

                <?php  $displayedPhotos++;}  ?>

                <?php if($includeRightDiv) {?>
                    </div>
                <?php } ?>
            
            
                </div>
</div>

<script type="text/javascript">
var totalMedia = <?php echo $media['photos']['totalPhotos']+ count($media['videos']['Videos']);?> ;
var displayedPhotos = <?php echo $displayedPhotos;?>;
</script>
